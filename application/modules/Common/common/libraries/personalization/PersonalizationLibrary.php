<?php
	class PersonalizationLibrary{
		private $CI;
		private $userId;
		private $visitorId;
		private $visitorIdentificationKey;
		private $isUserFlag = FALSE;
		private $isVisitorFlag = FALSE;
		private $predisLibrary;
		private $personalizationmodel;
		private $userHomeFeedDataKeyPattern = 'userHomeFeed:user:<userId>';
		private $userHomeFeedNewDataKeyPattern = 'userNewHomeFeed:user:<userId>';
		private $userHomeFeedBackFillDataKeyPattern = 'userBackFillHomeFeed:user:<userId>';
		private $numberToStringMapping;
		private $maxHomeFeedItem;
		private $totalResponsePerRequest=10;
		private $halfLifeForThreads = 86400;
		private $topTagsForUserCount = 10;
		private $debugFlag = 0;
		private $debugData = array();
		
		function __construct($userId){
			$this->CI 						= & get_instance();
			$this->userId 					= (int) $userId;
			$this->predisLibrary			= PredisLibrary::getInstance();
			$this->CI->load->model('common/personalizationmodel');
			$this->personalizationmodel		= new personalizationmodel();
			$this->CI->load->library('common/personalization/PersonalizedData');
			$this->CI->config->load('personalizationConfig');
			$this->numberToStringMapping	= $this->CI->config->item('personalizationNumberToStringMapping');
			$this->maxHomeFeedItem 			= $this->CI->config->item('maxHomeFeedItem');
			$this->topTagsForUserCount		= $this->CI->config->item('topTagsForUserCount');
			$this->debugData				= array();
		}
		
		public function setUserId($userId){
			$this->userId = $userId;
		}
		
		public function setVisitorId($visitorId){
			$this->visitorId = $visitorId;
		}
		
		public function setVisitorIdentificationKey(){
			if(!empty($this->userId) && $this->userId !== FALSE && $this->userId > 0){
				$this->visitorIdentificationKey = $this->userId;
				$this->isUserFlag = TRUE;
				$this->isVisitorFlag = FALSE;
				return TRUE;
			}elseif (!empty($this->visitorId) && $this->visitorId !== FALSE){
				$this->visitorIdentificationKey = $this->visitorId;
				$this->isUserFlag = FALSE;
				$this->isVisitorFlag = TRUE;
				return TRUE;
			}else {
				$this->visitorIdentificationKey = NULL;
				$this->isUserFlag = FALSE;
				$this->isVisitorFlag = FALSE;
				return FALSE;
			}
		}
		
		public function getVisitorIdentificationKey(){
			return $this->visitorIdentificationKey;
		}
		
		public function isUser(){
			return $this->isUserFlag;
		}
		
		public function isVisitor(){
			return $this->isVisitorFlag;
		}
		
		/**
		 * 
		 * @param number $startIndex
		 * @param string $type one of unanswered/discussion/home
		 */
		public function getHomeFeedData($startIndex=0, $filter = 'home', $totalResponsePerRequest = 10, $applyOnlySorting = FALSE){
			
			if(!$this->setVisitorIdentificationKey()){
				return array();
			}
			
			if($totalResponsePerRequest >= 0){
				$this->totalResponsePerRequest = $totalResponsePerRequest;
			}
			
			$startIndex = (integer) $startIndex;
			if($startIndex < 0){
				$startIndex = 0;
			}
			$userHomeFeedHashKey		= str_replace('<userId>', $this->getVisitorIdentificationKey(), $this->userHomeFeedDataKeyPattern);
			$userNewHomeFeedKey			= str_replace('<userId>', $this->getVisitorIdentificationKey(), $this->userHomeFeedNewDataKeyPattern);
			$userBackFillHomeFeedKey	= str_replace('<userId>', $this->getVisitorIdentificationKey(), $this->userHomeFeedBackFillDataKeyPattern);
			$homeFeedDataArray 			= $this->predisLibrary->getAllMembersOfHashWithValue($userHomeFeedHashKey);
			// if home feed bucket is empty and this is apply only sorting call, then obviously no need to perform sorting. And therefor no restoration of data for in-active users.
			if($applyOnlySorting && empty($homeFeedDataArray)){
				return;
			}
			//global $isWebAPICall;
			// if home feed bucket is empty and this is apply only sorting call, then obviously no need to perform sorting. And therefor no restoration of data for in-active users.
			if($applyOnlySorting && empty($homeFeedDataArray)){
				return;
			}
			// this is to check if empty home feed and is registered user, then check if in-active user
			if((empty($homeFeedDataArray) && $this->isUser() && $startIndex == 0) || ($startIndex == 0 && $this->isVisitor()/* && $isWebAPICall == 1*/)){
            //if(empty($homeFeedDataArray) && $startIndex == 0){
				$restoreHomeFeedDataArray = $this->restorePersonalizationDataForReturningUser(TRUE);
				if(!empty($restoreHomeFeedDataArray)){
					$homeFeedDataArray = array_merge($homeFeedDataArray,$restoreHomeFeedDataArray); // merge restored feed with actual feed
				}
			}
			$newStoryReceived = 0;
			if($startIndex == 0){
				// if fresh page request is raised, check for all new items in userNewHomeFeed:user:<userId> sorted set and apply merging and sorting
				$homeFeedNewDataArray = $this->predisLibrary->getMembersInSortedSet($userNewHomeFeedKey,0,-1,FALSE);
				$backFillDataArray = $this->predisLibrary->getAllMembersOfHashWithValue($userBackFillHomeFeedKey);
				if(!empty($homeFeedNewDataArray) || !empty($backFillDataArray)){
					
					$homeFeedDataArray = $this->_applySortingOnHomeFeed($homeFeedDataArray,$homeFeedNewDataArray,$backFillDataArray);
					
					// after applying sorting remove userNewHomeFeed:user:<userId> key from redis
					if (!empty($homeFeedNewDataArray)){
						$this->predisLibrary->removeMembersInSortedSet($userNewHomeFeedKey,array_keys($homeFeedNewDataArray));
					}
					if (!empty($backFillDataArray)){
						$this->predisLibrary->deleteMembersOfHash($userBackFillHomeFeedKey,array_keys($backFillDataArray));
					}
					
					$homeFeedDataArray = array_slice($homeFeedDataArray, 0, $this->maxHomeFeedItem, TRUE);
					$this->predisLibrary->deleteKey(array($userHomeFeedHashKey));
					if(count($homeFeedDataArray) > 0){
						$this->predisLibrary->addMembersToHash($userHomeFeedHashKey,$homeFeedDataArray);
					}
					
				}
			}else{
				$homeFeedNewDataArray	= $this->predisLibrary->getMembersInSortedSet($userNewHomeFeedKey,0,-1,FALSE,TRUE);
			}
			// if apply only sorting call, then no need to process further as sorting is done.
			if($applyOnlySorting){
				return ;
			}
			$parsedHomeFeedResult 		= $this->_parseHomeFeedData($homeFeedDataArray, $homeFeedNewDataArray, $filter);
			if($startIndex > 0 && $filter != 'unanswered'){
				$newStoryReceived		= count($parsedHomeFeedResult['newFeeds']);
			}
			$personalizedDataObject 	= $parsedHomeFeedResult['homeFeed'];
			$threadIdToBeRemovedFromUserHomeFeed = array();
			foreach ($parsedHomeFeedResult['threadToBeRemoved'] as $id){
				$threadIdToBeRemovedFromUserHomeFeed[] = 'thread:'.$id;
			}
			if(count($threadIdToBeRemovedFromUserHomeFeed) > 0){
				$this->predisLibrary->deleteMembersOfHash($userHomeFeedHashKey,$threadIdToBeRemovedFromUserHomeFeed);
			}
			
			$currentPaginationIndex	= $this->_getCurrentPaginationIndex($startIndex, $this->totalResponsePerRequest, $homeFeedDataArray, $threadIdToBeRemovedFromUserHomeFeed);
			$actualHomeFeedCount 	= count($personalizedDataObject);
			$personalizedDataObject = array_slice($personalizedDataObject, $currentPaginationIndex, $this->totalResponsePerRequest);
			$nextPaginationIndex	= $currentPaginationIndex + $this->totalResponsePerRequest;
			if($nextPaginationIndex >= $this->maxHomeFeedItem){
				$nextPaginationIndex = -1;
			}
			/*
			 * Get static content from Redis if reuired. But check it should already not be in user's home feed
			 */
			$staticContent = array();
			$unansweredContent = array();
			if(count($personalizedDataObject) < $this->totalResponsePerRequest){
				if($actualHomeFeedCount == 0){
					$startIndexForStaticContent = $startIndex;
					$lengthForStaticContent = $this->totalResponsePerRequest;
				}else{
					//$startIndexForStaticContent = $startIndex - count($homeFeedDataArray);
					if(count($personalizedDataObject) > 0){
						$startIndexForStaticContent = 0;
					}else{
						//$startIndexForStaticContent = $this->totalResponsePerRequest - (count($homeFeedDataArray) % $this->totalResponsePerRequest);
						$startIndexForStaticContent = $startIndex - $actualHomeFeedCount;
					}
					$lengthForStaticContent = $this->totalResponsePerRequest - count($personalizedDataObject);
				}
				if($filter == 'unanswered'){

					$highLevelTags		= $this->predisLibrary->getMembersOfSet('highLevelTagsForPersonalization');
					$userFollowsTags	= $this->predisLibrary->getMembersInSortedSet('userFollowsTag:user:'.$this->getVisitorIdentificationKey(), 0, -1, TRUE, FALSE);
					$userUnfollowTags 	= array();
					if($this->isUser()){
						$userUnfollowTags	= $this->personalizationmodel->getFollowTypeData(array($this->getVisitorIdentificationKey()), 'tag', 'unfollow');
					}
					if(is_array($userUnfollowTags[$this->getVisitorIdentificationKey()])){
						$tagsForWhichUnansweredToFetch = array_values(array_diff($userFollowsTags, $userUnfollowTags[$this->getVisitorIdentificationKey()]));
					}else{
						$tagsForWhichUnansweredToFetch = $userFollowsTags;
					}
					if(is_array($highLevelTags) && count($highLevelTags) > 0){
						$tagsForWhichUnansweredToFetch = array_diff($tagsForWhichUnansweredToFetch, $highLevelTags);
					}
					
					$unansweredContent = $this->_getUnansweredContent($startIndexForStaticContent, $lengthForStaticContent, $tagsForWhichUnansweredToFetch, array_keys($homeFeedDataArray));
					if(count($unansweredContent) < $lengthForStaticContent){
						$nextPaginationIndex = -1;
					}elseif ((count($unansweredContent) + $startIndexForStaticContent + $actualHomeFeedCount) >= $this->maxHomeFeedItem){
						$nextPaginationIndex = -1;
					}
					else{
						$nextPaginationIndex = $actualHomeFeedCount + (count($unansweredContent) + $startIndexForStaticContent);
					}
				}else{
					$staticContentResult = $this->_getStaticContentForUser($startIndexForStaticContent, $lengthForStaticContent, array_keys($homeFeedDataArray), $filter);
					$staticContent = $staticContentResult['staticContent'];
					if(count($staticContent) < $lengthForStaticContent){
						$nextPaginationIndex = -1;
					}elseif ((count($staticContent) + $startIndexForStaticContent + $actualHomeFeedCount) >= $this->maxHomeFeedItem){
						$nextPaginationIndex = -1;
					}
					else{
						$nextPaginationIndex = $actualHomeFeedCount + (count($staticContent) + $startIndexForStaticContent) + $staticContentResult['threadsExcluded'];
					}
				}
			}
			
			$finalPersonalizedDataObjectArray = array_merge($personalizedDataObject, $staticContent, $unansweredContent);
			
			if($filter != 'unanswered'){
				// compute for top answer/comment for home feeds with no answer/comment in case when unanswered feeds are requested
				$threadsWithNoActionItemId = array();
				foreach ($finalPersonalizedDataObjectArray as $personalizedData){
					if(!$personalizedData->getActionItemId()){
						$threadsWithNoActionItemId[] = $personalizedData->getThreadId();
					}
				}
				
				if(!empty($threadsWithNoActionItemId)){
					$commentAnswerOfThreadsWithNoActionItemId = $this->personalizationmodel->getTopCommentAnswerForThread($threadsWithNoActionItemId);
					
					foreach ($finalPersonalizedDataObjectArray as $key => &$personalizedData){
						if(isset($commentAnswerOfThreadsWithNoActionItemId[$personalizedData->getThreadId()]) && !$personalizedData->getActionItemId()){
							$personalizedData->setActionItemId($commentAnswerOfThreadsWithNoActionItemId[$personalizedData->getThreadId()]['answerCommentId']);
							$personalizedData->setThreadType($commentAnswerOfThreadsWithNoActionItemId[$personalizedData->getThreadId()]['threadType']);
							if(!$personalizedData->getActionDateTime()){
								$personalizedData->setActionDateTime($commentAnswerOfThreadsWithNoActionItemId[$personalizedData->getThreadId()]['creationDate']);
							}
						}
						// remove those threads(in static bucket) from final home feed that are not found in database : this case will occur when thread gets deleted but exist in redis static bucket
						if(in_array($personalizedData->getThreadId(),$threadsWithNoActionItemId) && !key_exists('thread:'.$personalizedData->getThreadId(), $homeFeedDataArray) && !key_exists($personalizedData->getThreadId(), $commentAnswerOfThreadsWithNoActionItemId)){
							error_log("ABHINAV@TEST :: Thread Removed : ".$personalizedData->getThreadId());
							unset($finalPersonalizedDataObjectArray[$key]);
						}
					}
				}
			}
			
			return array('homeFeed' => $finalPersonalizedDataObjectArray, 'nextPaginationIndex' => $nextPaginationIndex, 'newHomeFeedItems' => $newStoryReceived);
		}
		
		private function _getCurrentPaginationIndex($currentIndex = 0, $itemsDeliveredPerRequest = 10, $totalItems = array(), $itemsDeleted = array()){
			$currentPaginationIndex = $currentIndex;
			foreach ($itemsDeleted as $value){
				$indexOfDeletedItem = array_search('thread:'.$value, $totalItems);
				if($indexOfDeletedItem >=0 && $indexOfDeletedItem < $currentPaginationIndex){
					$currentPaginationIndex = $currentPaginationIndex - 1;
				}
			}
			
			return $currentPaginationIndex;
		}
		
		private function _getUnansweredContent($startIndex, $totalResponse, $tagIds = array(), $exclusionList = array()){
			$result = array();
			try{
				$excludeThreadIds = array();
				foreach ($exclusionList as $value){
					$data = explode(':', $value);
					$excludeThreadIds[] = $data[1];
				}
				$excludeThreadByUserIds = array();
				if($this->isUser()){
					$excludeThreadByUserIds[] = $this->getVisitorIdentificationKey();
				}
				$resultData = $this->personalizationmodel->getUnansweredQuestionsReatedToTags($tagIds, $startIndex, $totalResponse, $excludeThreadIds, $excludeThreadByUserIds);
				foreach ($resultData as $data){
					$personalizedData = new PersonalizedData();
					$personalizedData->setThreadId($data['msgId']);
					$personalizedData->setActionByUserId($data['userId']);
					$personalizedData->setActionDateTime($data['creationDate']);
					$personalizedData->setTagId($data['tagId']);
					$tagsOnThisThread = $this->predisLibrary->getMembersInSortedSet('threadTags:thread:'.$personalizedData->getThreadId(), 0, -1, FALSE, TRUE);
					$personalizedData->setTagsAttachedToThread($tagsOnThisThread);
					$personalizedData->setAction('post');
					$personalizedData->setThreadType($data['threadType']);
					$result[] = $personalizedData;
				}
			} catch (Exception $e) {
				error_log(":: Error Occured :: common/Personalization Library :: member => _getUnansweredContent ".$e->getMessage().' :: '.$e->getTrace());
			}
			return $result;
		}
		
		/* private function _prepareHomeFeedData($homeFeedDataArray = array()){
			
			$personalizedDataObject = $this->_parseHomeFeedData($homeFeedDataArray);
			return $personalizedDataObject;
		} */
		
		private function _parseHomeFeedData($homeFeedDataArray = array(), $homeFeedNewDataArray = array(), $filter){
			$homeFeedObjectArray = array();
			$threadToDeleteFromHomeFeed = array();
			$homeFeedNewItems = array();
			$finalResult = array();
			try {
				$userIds = array();
				$answerCommentIds = array();
				$upvoteIds = array();
				$threadIds = array();
				foreach ($homeFeedDataArray as $homeFeedKey => $homeFeedValue){
					$personalizedDataArray = explode(':', $homeFeedValue);
					$personalizedDataObject = new PersonalizedData();
					foreach ($personalizedDataArray as $key => $value){
						switch ($key){
							case 0	:	$personalizedDataObject->setActionByUserId($value);
										$userIds[] = $value;
										break;
							case 1	:	$personalizedDataObject->setAction($this->numberToStringMapping[$value]);
										break;
							case 2	:	$personalizedDataObject->setThreadId($value);
										break;
							case 3	:	$personalizedDataObject->setActionDateTime($value);
										break;
							case 4	:	$personalizedDataObject->setActionItemId($value);
										break;
							case 5	:	$personalizedDataObject->setTagId($value);
										break;
							case 6	:	$this->_homeFeedDataReason($personalizedDataObject, $value);
										break;
							case 7	:	$personalizedDataObject->setSortingScore($value);
										break;
							default	:	break;
						}
					}
					
					$tagsOnThisThread = $this->predisLibrary->getMembersInSortedSet('threadTags:thread:'.$personalizedDataObject->getThreadId(), 0, -1, FALSE, TRUE);
					$personalizedDataObject->setTagsAttachedToThread($tagsOnThisThread);
					if(in_array($personalizedDataObject->getAction(), array('answer','comment','upvote')) && $personalizedDataObject->getActionItemId() > 0){
						$answerCommentIds[] = $personalizedDataObject->getActionItemId();
						if($personalizedDataObject->getAction() == 'upvote'){
							$upvoteIds[] = $personalizedDataObject->getActionItemId();
						}
					}
					$threadIds[] = $personalizedDataObject->getThreadId();
					$homeFeedObjectArray[] = $personalizedDataObject;
				}
				
				if(empty($threadIds)){
					$finalResult = array(	'homeFeed' 			=> array_values($homeFeedObjectArray),
											'threadToBeRemoved'	=> $threadToDeleteFromHomeFeed,
											'newFeeds'			=> $homeFeedNewItems
									);
					return $finalResult;
				}
				
				// sort home feed data objects based on sorting score
				usort($homeFeedObjectArray, function($a, $b){
					if($a->getSortingScore() == $b->getSortingScore()){
						return 0;
					}else{
						return ($a->getSortingScore() > $b->getSortingScore()) ? -1 : 1;
					}
				});
				
				$threadIds = array_unique(array_merge($threadIds, $answerCommentIds));
				$threadTypeFilteredResult = $this->personalizationmodel->getThreadTypeForThreadsBasedOnFilter($threadIds/* ,$filter */);
				
				// get data for upvoted answer/comment on threads from database.
				$upvoteIds = array_unique($upvoteIds);
				$upvoteIdsWithUserIds = array();
				if(!empty($upvoteIds)){
					$upvoteIdsWithUserIds = $this->personalizationmodel->getUserIdsOnUpvotedMsgIds($upvoteIds);
				}
				
				// filtering of home feed if tag/comment/answer gets detached/deleted
				foreach ($homeFeedObjectArray as $key => &$personalizedDataObject){
					if(in_array($personalizedDataObject->getActionItemId(), $answerCommentIds) && !key_exists($personalizedDataObject->getActionItemId(), $threadTypeFilteredResult)){
						// check if any answer/comment is deleted due to which feed was generated, then add this thread to threadToDeleteFromHomeFeed array
						$threadToDeleteFromHomeFeed[] = $personalizedDataObject->getThreadId();
						unset($homeFeedObjectArray[$key]);
					}elseif($personalizedDataObject->getAction() == 'upvote' /* && key_exists($personalizedDataObject->getActionItemId(), $upvoteIdsWithUserIds) */ && !in_array($personalizedDataObject->getActionByUserId(), $upvoteIdsWithUserIds[$personalizedDataObject->getActionItemId()])){
						// filter those threads which became home feed due to upvote on answer/comment but now upvote doesn't exist by the same user on same answer/comment
						$threadToDeleteFromHomeFeed[] = $personalizedDataObject->getThreadId();
						unset($homeFeedObjectArray[$key]);
					}elseif(key_exists($personalizedDataObject->getThreadId(), $threadTypeFilteredResult)){
						// thread is in live/closed state then perform filteration
						$tagsOnThisThread = $personalizedDataObject->getTagsAttachedToThread();
						if($filter == 'discussion' && $threadTypeFilteredResult[$personalizedDataObject->getThreadId()]['threadType'] == 'discussion'){
							$personalizedDataObject->setThreadType($threadTypeFilteredResult[$personalizedDataObject->getThreadId()]['threadType']);
							if(key_exists('thread:'.$personalizedDataObject->getThreadId(), $homeFeedNewDataArray)){
								$homeFeedNewItems[] = $personalizedDataObject->getThreadId();
							}
						}elseif ($filter == 'unanswered' && $threadTypeFilteredResult[$personalizedDataObject->getThreadId()]['threadType'] == 'user' && $threadTypeFilteredResult[$personalizedDataObject->getThreadId()]['msgCount'] == 0){
							$personalizedDataObject->setThreadType($threadTypeFilteredResult[$personalizedDataObject->getThreadId()]['threadType']);
							if(key_exists('thread:'.$personalizedDataObject->getThreadId(), $homeFeedNewDataArray)){
								$homeFeedNewItems[] = $personalizedDataObject->getThreadId();
							}
						}elseif($filter == 'home'){
							$personalizedDataObject->setThreadType($threadTypeFilteredResult[$personalizedDataObject->getThreadId()]['threadType']);
							if(key_exists('thread:'.$personalizedDataObject->getThreadId(), $homeFeedNewDataArray)){
								$homeFeedNewItems[] = $personalizedDataObject->getThreadId();
							}
						}else{
							// if none of the filters apply, remove from resulting feed which is to be delivered
							unset($homeFeedObjectArray[$key]);
						}
						if($personalizedDataObject->getTagId() > 0 && !key_exists($personalizedDataObject->getTagId(), $tagsOnThisThread)){
							$threadToDeleteFromHomeFeed[] = $personalizedDataObject->getThreadId();
							unset($homeFeedObjectArray[$key]);
						}
					}else{
						// if thread is deleted, then add this thread to threadToDeleteFromHomeFeed array
						$threadToDeleteFromHomeFeed[] = $personalizedDataObject->getThreadId();
						unset($homeFeedObjectArray[$key]);
					}
				}
				
			} catch (Exception $e) {
				error_log(":: Error Occured :: common/Personalization Library :: function => _parseHomeFeedData ".$e->getMessage().' :: '.$e->getTrace());
			}
			$finalResult = array(	'homeFeed' 			=> array_values($homeFeedObjectArray),
									'threadToBeRemoved'	=> $threadToDeleteFromHomeFeed,
									'newFeeds'			=> $homeFeedNewItems
								);
			return $finalResult;
		}
		
		private function _homeFeedDataReason(PersonalizedData &$personalizedDataObject, $reason){
			$reason = explode('-',$reason);
			foreach ($reason as $key => $value){
				if($this->numberToStringMapping[$value] == "userfreind"){
					$personalizedDataObject->setIsFreind(true);
				}elseif ($this->numberToStringMapping[$value] == "userfollower"){
					$personalizedDataObject->setIsFollower(true);
				}else{
					$personalizedDataObject->setReason($this->numberToStringMapping[$value]);
				}
			}
			return;
		}
		
		private function _prepareStaticHomeFeedData(){
			
			// provided by QA
			//$questionIds = array(3345557,3345555,3345552,3345551,3345550,3345549,3345548,3345527,3345517,3345515,3345493,3345492,3345491,3345488,3345420,3345419,3345370,3345373,3345409,3345408,3345414,3345395,3345394,3345393,3345392,3345391,3345390,3345389,3345358,3345352,3345342,3345335,3345314,3345312,3345306,3345295,3345292,3345290,3345286,3345284,3345245,3345238,3345236,3345226,3345225,3345224,3345223,3345222,3345221,3345220,3345219,3345218,3345215,3345208,3345207,3345205,3345201,3345200,3345196,3345170,3345169,3345168,3345167,3345166,3345165,3345164,3345163,3345162,3345161,3345160,3345144,3345138,3345131,3345123,3345111,3345110,3345109,3345404,3345106,3345107,3345103,3345102,3345101,3345100,3345099,3345098,3345096,3345095,3345094,3345093,3345092,3345091,3345069,3345068,3345067,3345066,3345065,3345064,3345063,3345062,3345052,3345051,3345050,3345043,3345042,3345041,3345040,3345039,3345038,3345037,3345036,3345035,3345034,3345033,3345032,3345031,3345030,3345029,3345028,3345027,3345026,3345024,3345022,3345021,3345020,3345019,3345018,3345017,3345016,3345015,3345014,3345013,3345012,3345011,3345010,3345004,3345003,3345002,3345001,3345000,3344999,3344998,3344997,3344996,3344994,3344993,3344991,3344990,3344989,3344988,3344986,3344985,3344984,3344982,3344981,3344980,3344978,3344977,3344976,3344974,3344973,3344969,3344967,3344965,3344964,3344959,3344958,3344957,3344956,3344955,3344954,3344952,3344951,3344950,3344948,3344944,3344943,3344940,3344939,3344938,3344931,3344930,3344926,3344925,3344915,3344902,3344896,3344867,3344794,3344788,3344786,3344776,3344775,3344773,3344771,3344759,3344757,3344755,3344732,3344718);
			//$discussionIds = array(2758947,2748317,2798995,3027413,3345525,3345505,3345503,3345501,3345499,3345397,3345384,3345381,3345343,3345315,3345239,3345141,3344962,3344960,3344053,3343656,3343037,3342872,3342372,3341801,3341586,3341298,3339934,3338930,3338916,3338063,3337762,3337764,3337496,3335628,3335616,3334073,3331773,3331767,3331498,3330339,3327081,3327079,3326411,3324768,3324738,3323951,3318243,3318165,3316044,3315944,3315941,3315442,3313601,3312375,3312230,3311979,3311585,3308963,3308478,3306625,3306620,3300697,3289220,3288337);
		
			$categorizedQuestionIds	=	array	(   'Banking&Finance'   =>  array(3388133,3388129,3383303,3382135,2278614),
								                	'Teaching'          =>  array(3397151,3397143,3396957,3385026,3382283),
									                'Art & Humanities'  =>  array(3389048,3388026,3388006,3387960,3387942,3387933),
									                'Law'               =>  array(3389847,3389821,3389807,3389782,3385176,3382289),
									                'Beauty&Fitness'    =>  array(3392590,3392581,2806972),
									                'Aviation'          =>  array(3396386,3394582,3388061,3382815,3382698),
									                'IT'                =>  array(3395231,3394484,3387830,3387829,3385976,3385965,3304113,3217292,2802501,2668171),
									                'MassComm'          =>  array(3387777,3387170,3387118,3385098,3385074,3382460,3382457),
									                'Design'            =>  array(3388904,3387169,3384541,3383561,3383555,3213140,2051173),
									                'Animation'         =>  array(3389579,3388914,3383588,3382143,2851735),
									                'Science'           =>  array(3388923,3384512,3383456),
									                'HotelMgmt'         =>  array(3394630,3383539,3383534,3383357,3382758),
									                'Retail'            =>  array(3395249,3393739,3386296,3383189,3382634)
            								);
			$discussionIds	=	array(3306625,3347698,3347700,3347710,3347762,3347784,3348055,3348111,3348115,3349215,3349222,3349788,3351301,3351694,3351702,3353685,3354236,3355966,3355971,3355977,3357162,3359059,3359482,3361700,3362065,3362069,3362275,3362493,3364227,3364261);
			$counter = 0;
			$questionIds = array();
			while(TRUE){
				$counterFlag = FALSE;
				foreach($categorizedQuestionIds as $category => $threadIds){
					if(isset($threadIds[$counter])){
						$questionIds[] = $threadIds[$counter];
						$counterFlag = $counterFlag || TRUE;
					}
				}
				if(!$counterFlag){
					break;
				}else {
					++$counter;
				}
			}
			$staticThreadIds = array();
			for($i=0;$i<count($questionIds);$i++){
				$staticThreadIds[] = 'question:thread:'.$questionIds[$i];
				if(isset($discussionIds[$i])){
					$staticThreadIds[] = 'discussion:thread:'.$discussionIds[$i];
				}
			}
			$this->predisLibrary->rightPushInList('userHomeFeedStaticCuratedBucket',$staticThreadIds);
			$this->personalizationmodel->insertStaticThreadsIntoDB($staticThreadIds);
			return ;
		}
		
		private function _getStaticContentForUser($start=0, $length=10, $exclusionList = array(), $filter = 'home'){
			$result = array();
			/* $stop = ($start+$length+count($exclusionList)) - 1;
			if ($stop < 0) {
				return array();
			} */
			$stop = -1;
			$staticContent = $this->predisLibrary->getMembersOFList('userHomeFeedStaticCuratedBucket',0 ,$stop);
			if(count($staticContent) == 0){
				if(method_exists($this->predisLibrary, 'isRedisAlive') && $this->predisLibrary->isRedisAlive() === FALSE){
					// for DB query stop will be + 1
					$staticContent = $this->personalizationmodel->getStaticThreadFromDB(0 , $stop);
					
				}else{
					$this->_prepareStaticHomeFeedData();
					$staticContent = $this->predisLibrary->getMembersOFList('userHomeFeedStaticCuratedBucket',0 ,$stop);
				}
			}

			
			$threadsExcluded = 0;
			$startIndex = 0;

			foreach ($staticContent as $value){
				$threadDetails = explode(':thread:', $value);
				// this check is to exclude members from static bucket list and also to exclude threads which are niether in live nor in closed state

				/* if(in_array('thread:'.$threadDetails[1], $exclusionList)){
					$threadsExcluded += 1;
					continue;
				}else */
				if ($filter == 'discussion' && $threadDetails[0] != 'discussion'){
					// check to get only discussions from static bucket if discussion filter is applied
					continue;
				}
				
				if($startIndex < $start){ // exclude all threads already delivered
					$startIndex += 1;
					continue;
				}
				
				if(in_array('thread:'.$threadDetails[1], $exclusionList)){
					$threadsExcluded += 1;
					continue;
				}
				
				// this check is to serve content up to length specified
				if($length-- == 0){ // test if this check is working properly
					break;
				}
				$personalizedData = new PersonalizedData();
				$personalizedData->setThreadId($threadDetails[1]);
				$personalizedData->setReason('topContent');
				$tagsOnThisThread = $this->predisLibrary->getMembersInSortedSet('threadTags:thread:'.$personalizedData->getThreadId(), 0, -1, FALSE, TRUE);
				$personalizedData->setTagsAttachedToThread($tagsOnThisThread);
				$result[] = $personalizedData; 
			}
			error_log("ABHINAV@TEST :: static bucket : ".print_r(array('staticContent' => $result, 'threadsExcluded' => $threadsExcluded),true));
			return array('staticContent' => $result, 'threadsExcluded' => $threadsExcluded);
		}
		
		private function _applySortingOnHomeFeed($homeFeedDataArray = array(), $homeFeedNewDataArray = array(), $backFillDataArray = array()){
			//return array_reverse($homeFeedDataArray, TRUE);
			$finalRawHomeFeed = array();
			$finalRawHomeFeed = $homeFeedDataArray;
			foreach($backFillDataArray as $key => $value){
				if(!(key_exists($key, $finalRawHomeFeed))){
					$finalRawHomeFeed[$key] = $value;
				}
			}
			$threadIdsArray = array();
			foreach ($finalRawHomeFeed as $key => $value){
				$data = explode(':', $key);
				$threadIdsArray[] = $data[1];
			}
			$threadSpecificData = array();
			foreach($threadIdsArray as $threadId){
				$response = $this->predisLibrary->getMemberOfString('threadQuality:thread:'.$threadId);
				if(is_null($response)){
					$threadSpecificData[$threadId]['qualityScore'] = 0;
				}else{
					$threadSpecificData[$threadId]['qualityScore'] = $response;
				}
				$threadSpecificData[$threadId]['contributors'] = $this->predisLibrary->getMembersOfSet('threadContributors:thread:'.$threadId);
				$threadSpecificData[$threadId]['tags'] = $this->predisLibrary->getMembersInSortedSet('threadTags:thread:'.$threadId,0,-1);
			}
			$userFriends = array();
			$userFollows = array();
			$userTags = array();
			if($this->isUser()){
				$userFriends = $this->predisLibrary->getMembersOfSet('userFreind:user:'.$this->getVisitorIdentificationKey());
				$userFollows = $this->predisLibrary->getMembersOfSet('userFollows:user:'.$this->getVisitorIdentificationKey());
			}
			$userFollowsTags = $this->predisLibrary->getMembersInSortedSet('userFollowsTag:user:'.$this->getVisitorIdentificationKey(),0,-1);
			
			$homeFeedThreadScore = array();
			$incorrectThreads = array();
			$currenDate	= new DateTime('now');
			//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId);
			foreach ($threadIdsArray as $value){
				//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value);
				$homeFeedDataValue = explode(':', $finalRawHomeFeed['thread:'.$value]);
				// avoid if any in-correct home feed generated
				/*$homeFeedReason	= explode(":", $homeFeedDataValue[6]);
				if($homeFeedReason[0] == 11 && (in_array($homeFeedDataValue[1], array(5,6)))){
					// avoid if user is tagfollower and action performed is any of upvote/follow
					$incorrectThreads[] = $value;
					continue;
				}*/
				
				$actionByUserId = $homeFeedDataValue[0];
				$score = 0;
				
				
				// 1. add log of thread quality to score
				if(!(isset($threadSpecificData[$value]['qualityScore']) && $threadSpecificData[$value]['qualityScore'] > 0)){
					$threadSpecificData[$value]['qualityScore'] = 1;
				}
				
				if($this->debugFlag) $this->debugData['sorting'][$value][] = ' Quality-score: '.$threadSpecificData[$value]['qualityScore'];

				//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value.' QUALITY-SCORE: '.$threadSpecificData[$value]['qualityScore']);
				$score += log($threadSpecificData[$value]['qualityScore'],2);
				//echo '<br/>thread:'.$value.' score1 :'.$score;
				
				$userTagMatchedToThreadTagScore = 0;
				foreach ($threadSpecificData[$value]['tags'] as $tagId => $type){
					if(key_exists($tagId, $userFollowsTags)){
						if($type == 0){
							$userTagMatchedToThreadTagScore += $userFollowsTags[$tagId];
						}else{
							$userTagMatchedToThreadTagScore += ($userFollowsTags[$tagId] / 2);
						}
					}
				}
				
				// 2. add matched tag's affinity to score
				if(!(isset($userTagMatchedToThreadTagScore) && $userTagMatchedToThreadTagScore > 0)){
					$userTagMatchedToThreadTagScore = 1;
				}
				if($this->debugFlag) $this->debugData['sorting'][$value][] = ' UserTagMatchedToThreadTagScore: '.$userTagMatchedToThreadTagScore;
				//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value.' userTagMatchedToThreadTagScore: '.$userTagMatchedToThreadTagScore);
				$score += log($userTagMatchedToThreadTagScore,2);
				//echo '<br/>thread:'.$value.' score2 :'.$score;
				
				if($this->isUser()){
					$userNetwork = array();
					$userNetwork = array_intersect($threadSpecificData[$value]['contributors'], $userFollows);
					$userNetwork = array_merge($userNetwork,array_intersect($threadSpecificData[$value]['contributors'], $userFriends));
					$userNetwork = array_unique($userNetwork);
					
					// 3. add n/w contributors count to score
					if($this->debugFlag) $this->debugData['sorting'][$value][] = ' Count of userNetwork: '.count($userNetwork);
					//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value.' count of userNetwork: '.count($userNetwork));
					$score += count($userNetwork);
					//echo '<br/>thread:'.$value.' score3 :'.$score;
				}
				
				// 4. add user self involvement in score
				if($this->isUser() && in_array($this->userId, $threadSpecificData[$value]['contributors'])){
					if($this->debugFlag) $this->debugData['sorting'][$value][] = ' User self involvement score: 1';
					//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value.' user self involvement score: 1');
					$score += 1;
				}
				
				// 5. add user connection with user who performed action compatibility score
				if($this->isUser() && in_array($actionByUserId, $userFollows)){
					if($this->debugFlag) $this->debugData['sorting'][$value][] = ' Compatibility score follower: 2';
					//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value.' compatibility score follower: 2');
					$score += 2;
				}
				if($this->isUser() && in_array($actionByUserId, $userFriends)){
					if($this->debugFlag) $this->debugData['sorting'][$value][] = ' Compatibility score friend: 1';
					//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value.' compatibility score friend: 1');
					$score += 1;
				}
				//echo '<br/>thread:'.$value.' score4 :'.$score;
				
				// 6. add time recency factor to net result
				$actionTime = $homeFeedDataValue[3];
				//echo '<br/> action_time :'.$actionTime;
				$intervalFromAction = $currenDate->diff(new DateTime(date('Y-m-d H:i:s',strtotime($actionTime))));
				//echo '<br/> intervalFromAction : ';_p($intervalFromAction);
				$intervalFromActionInSeconds = 0 + ($intervalFromAction->s) + ($intervalFromAction->i * 60) + ($intervalFromAction->h * 60 * 60) + ($intervalFromAction->d * 24 * 60 * 60) + ($intervalFromAction->m * 30 * 24 * 60 * 60) + ($intervalFromAction->y * 365 * 24 * 60 * 60);
				$intervalFromActionInSeconds = ($intervalFromActionInSeconds > 0)?$intervalFromActionInSeconds:1;
				//echo '<br/> intervalFromActionInSeconds : '.$intervalFromActionInSeconds;
				$recencyScore = $this->_getRecencyScoreForHomeFeedSorting($intervalFromActionInSeconds);
				//echo '<br/> thread:'.$value.' time from last activity : '.($intervalFromActionInSeconds/86400).'  score :'.$recencyScore;
				//echo '<br/>recency_score : '.$recencyScore;
				if($this->debugFlag) $this->debugData['sorting'][$value][] = 'Recency in seconds: '.$intervalFromActionInSeconds." (score = ".$recencyScore.")";
				//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value.' recency in seconds: '.$intervalFromActionInSeconds);
				//error_log('::HOME-FEED::SORTING:: USER-ID:'.$this->userId.' THREAD-ID: '.$value.' recency score: '.$recencyScore);
				$score += $recencyScore;
				
				$homeFeedThreadScore[$value] = $score;
			}

			arsort($homeFeedThreadScore,SORT_NUMERIC);
			$finalProcessedHomeFeed = array();
			foreach($homeFeedThreadScore as $threadId => $threadScore){
				//if(! in_array($threadId, $incorrectThreads)){
					$homeFeedValueArray = explode(":", $finalRawHomeFeed['thread:'.$threadId]);
					// assign sorting score back into home feed string
					$homeFeedValueArray[7] = $threadScore;
					$finalProcessedHomeFeed['thread:'.$threadId] = implode(":", $homeFeedValueArray);
				//}
				//$finalProcessedHomeFeed['thread:'.$threadId] = $finalRawHomeFeed['thread:'.$threadId];
			}
			//_p($finalProcessedHomeFeed);die;
			return $finalProcessedHomeFeed;

		}
		
		/**
		 * This function computes recency based on duration.
		 * Formula used here is (1/exp(log(2)*(duration/HL)))*100
		 * @param integer $durationInSeconds : This has to be in seconds greater than 0
		 */
		private function _getRecencyScoreForHomeFeedSorting($durationInSeconds=1){
			$score = 0;
			try {
				Contract::mustBeNumericValue($durationInSeconds, 'Duration In Seconds');
				if($durationInSeconds < 0){
					return $score;
				}
				$exponentFactor = log(2) * ($durationInSeconds / $this->halfLifeForThreads);
				$score = (1 / exp($exponentFactor)) * 100;
				//echo '<br/> score computed for '.$durationInSeconds.' seconds score :'.$score.' dd '.($durationInSeconds / $this->halfLifeForThreads).' ee:'.exp($exponentFactor);
			} catch (Exception $e) {
				error_log(':: Error Ocurred :: Message: '.$e->getMessage().' Traces : '.$e->getTrace());
			}
			return $score;
		}
		
		public function computeThreadQuality() {
			// get threadIds for which quality is to be computed
			$threadIds = $this->predisLibrary->getMembersOfSet('computeThreadQualityQueue');
			$threadQualityResult = array();
			
			// for each threadId compute thread quality
			foreach ($threadIds as $value){
				$threadQualityParameter = $this->predisLibrary->getAllMembersOfHashWithValue('threadQualityProperty:thread:'.$value);
				if(empty($threadQualityParameter) && count($threadQualityParameter)==0){
					error_log('\n'.'====threadId===='.$value,3,"/tmp/emptyThreadQualityParameter.log");
				}
				$threadQualityResult[$value] = $this->_calculateThreadQuality($threadQualityParameter);
				if(!is_numeric($threadQualityResult[$value])){
					unset($threadQualityResult[$value]);
				}
			}
			
			if(empty($threadQualityResult)){
				return ;
			}
			$this->predisLibrary->setPipeLine();
			// for every thread with computed score update its score in redis
			foreach ($threadQualityResult as $key => $value){
				$this->predisLibrary->addMemberToString('threadQuality:thread:'.$key,$value,-1,FALSE,TRUE);
			}
			
			// remove all threads for which quality is computed from computeThreadQualityQueue set in redis
			$this->predisLibrary->removeMembersOfSet('computeThreadQualityQueue',array_keys($threadQualityResult),TRUE);
			
			$this->predisLibrary->executePipeLine();
			
			// add/update thread quality in DB
			$this->personalizationmodel->updateThreadQuality($threadQualityResult);
			
			// index the updated threads on Solr
			$threadTypesArray = $this->personalizationmodel->getThreadType(array_keys($threadQualityResult));
			//modules::run('search/Indexer/addToQueue', $threadId, 'question');
			// foreach ($threadTypesArray as $data){
			// 	if($data['fromOthers'] == 'user' && array_key_exists($data['msgId'], $threadQualityResult)){
			// 		modules::run('search/Indexer/addToQueue', $data['msgId'], 'question');
			// 	}elseif ($data['fromOthers'] == 'discussion' && array_key_exists($data['msgId'], $threadQualityResult)){
			// 		modules::run('search/Indexer/addToQueue', $data['msgId'], 'discussion');
			// 	}
			// }
			
			return TRUE;
		}
		
		private function _calculateThreadQuality($parameterWithValue = array()){
			$score = 0;
			try {
				Contract::mustBeNonEmptyArray($parameterWithValue, 'Thread Quality Parameter');
				if(!(isset($parameterWithValue['vi']) && $parameterWithValue['vi'] > 0)){
					$parameterWithValue['vi'] = 1;
				}
				if(!(isset($parameterWithValue['co']) && $parameterWithValue['co'] > 0)){
					$parameterWithValue['co'] = 0;
				}
				if(!(isset($parameterWithValue['an']) && $parameterWithValue['an'] > 0)){
					$parameterWithValue['an'] = 0;
				}
				if(!(isset($parameterWithValue['sh']) && $parameterWithValue['sh'] > 0)){
					$parameterWithValue['sh'] = 0;
				}
				if(!(isset($parameterWithValue['fo']) && $parameterWithValue['fo'] > 0)){
					$parameterWithValue['fo'] = 0;
				}
				if(!(isset($parameterWithValue['up']) && $parameterWithValue['up'] > 0)){
					$parameterWithValue['up'] = 1;
				}
				if(!(isset($parameterWithValue['do']) && $parameterWithValue['do'] > 0)){
					$parameterWithValue['do'] = 1;
				}
				$score = log($parameterWithValue['vi']) + (1.2 * $parameterWithValue['co']) + (1.5 * $parameterWithValue['an']) + $parameterWithValue['sh'] + $parameterWithValue['fo'] + log($parameterWithValue['up']) - log($parameterWithValue['do']);
			} catch (Exception $e) {
				error_log(' :: Exception Occured :: '.$e->getMessage().' :: Trace '.$e->getTrace());
				$score = 'FAIL';
			}
			return $score;
		}
		
		public function getTagRecommendations(){
			
			if(!$this->setVisitorIdentificationKey()){
				return array();
			}
			$recommendedTagResults			= array();
			$recommendedTagResultsReason	= array();
			$recommendedTagDueToParentTag	= array();
			$finalResult					= array();
			$userActiveTags					= array();
			$userFollowsTags				= array();
			$userUnfollowsTags				= array();
			$userFollowsUser				= array();
			$userFreinds					= array();
			try {
				if(method_exists($this->predisLibrary, 'isRedisAlive') && $this->predisLibrary->isRedisAlive() === FALSE){
					return $finalResult;
				}
				$highLevelTags		= $this->predisLibrary->getMembersOfSet('highLevelTagsForPersonalization');
				if($this->isUser()){
					$userActiveTags		= $this->predisLibrary->getMembersInSortedSet('userFollowsTag:user:'.$this->getVisitorIdentificationKey(),0,$this->topTagsForUserCount,TRUE,TRUE);
					$userFollowsTags	= $this->predisLibrary->getMembersOfSet('userFollowsTagExplicitly:user:'.$this->getVisitorIdentificationKey());
					$userUnfollowsTags	= $this->personalizationmodel->getFollowTypeData( array($this->getVisitorIdentificationKey()), 'tag', 'unfollow');
				}
				if(!is_array($userUnfollowsTags[$this->getVisitorIdentificationKey()])){
					$userUnfollowsTags[$this->getVisitorIdentificationKey()] = array();
				}
				
				if (count($userActiveTags) == 0) {
					//error_log('NO ACTIVE TAGS FOR THIS USER');
					$streamWiseTagsArray = $this->personalizationmodel->getTagBasedOnEntity(array('Stream'));
					$recommendedTagResults = $streamWiseTagsArray['Stream'];
					$recommendedTagResults = array_diff($recommendedTagResults, $highLevelTags, $userFollowsTags, $userUnfollowsTags[$this->getVisitorIdentificationKey()]);
					foreach ($recommendedTagResults as $data){
						$recommendedTagResultsReason[$data] = 'topTags';
					}
				}else{
					foreach ($userActiveTags as $key => $value){
						if(!in_array($key, $userFollowsTags) && !in_array($key, $userUnfollowsTags[$this->getVisitorIdentificationKey()]) && !in_array($key, $highLevelTags)){
							//$filteredUserActiveTags[$key] = -1;
							$recommendedTagResults[] = $key;
							$recommendedTagResultsReason[$key] = 'activeTags';
						}
					}
					$countOfRecommendedTags = count($recommendedTagResults);
					if($countOfRecommendedTags >= 10){
						// break execution here
						$recommendedTagResults = array_slice($recommendedTagResults, 0, 10, TRUE);
					}else{
						$finalArray = array();
						$count = 0;
						$tagsToParentMapping = $this->personalizationmodel->getTagsParents(array_keys($userActiveTags));
						$tagForWhichChildrenToBeFetched = array();
						foreach($tagsToParentMapping as $key => $data){
							$tagForWhichChildrenToBeFetched = array_merge($tagForWhichChildrenToBeFetched,array_values($data));
						}
						$tagForWhichChildrenToBeFetched = array_merge($tagForWhichChildrenToBeFetched, array_keys($userActiveTags));
						$tagForWhichChildrenToBeFetched = array_unique($tagForWhichChildrenToBeFetched);
						$parentToChildrenMapping = $this->personalizationmodel->getChildrenOfTags($tagForWhichChildrenToBeFetched,$userFollowsTags);
						foreach ($parentToChildrenMapping as $tagId=>$children){
							/* if(!in_array($tagId, $userFollowsTags) && !key_exists($tagId, $filteredUserActiveTags)){
								$finalArray[$key] += 1;
							} */
							foreach($children as $key => $id ){
								if(in_array($id, $userFollowsTags) || in_array($id, $userUnfollowsTags[$this->getVisitorIdentificationKey()])  || key_exists($id, $userActiveTags)){
									continue;
								}else {
									$finalArray[$id] += 1;
								}
							}
							$count = (count($children) > $count)?count($children):$count;
						}
						arsort($finalArray);
						
						$countForRecommendation = count($finalArray) - 1;
						//$finalRecommendationTag = array();
						reset($finalArray);
						while($score = current($finalArray)){
							if($score == 1){
								$keysWithScore = array_keys($finalArray,$score);
								for($i=0;$i<$count;$i++){
									foreach ($parentToChildrenMapping as $parentId => $children){
										if(isset($children[$i]) && !in_array($children[$i], $recommendedTagResults) && !in_array($children[$i], $userUnfollowsTags[$this->getVisitorIdentificationKey()]) && !in_array($children[$i], $highLevelTags)){
											//$finalRecommendationTag[++$countOfRecommendedTags] = $children[$i];
											$recommendedTagResults[$countOfRecommendedTags++] = $children[$i];
											$recommendedTagDueToParentTag[$children[$i]] = 1;
											$recommendedTagResultsReason[$children[$i]] = 'relatedTags';
											if($countOfRecommendedTags > 10){
												break 3;
											}
										}
									}
								}
								break;
							}else {
								if(!in_array(key($finalArray), $highLevelTags)){
									//$finalRecommendationTag[++$countOfRecommendedTags] = key($finalArray);
									$recommendedTagResults[$countOfRecommendedTags++] = key($finalArray);
									$recommendedTagDueToParentTag[key($finalArray)] = 1;
									$recommendedTagResultsReason[key($finalArray)] = 'relatedTags';
									if($countOfRecommendedTags > 10){
										break;
									}
								}
								next($finalArray);
							}
						}
						if($countOfRecommendedTags <= 10){
							$streamWiseTagsArray = $this->personalizationmodel->getTagBasedOnEntity(array('Stream'));
							foreach ($streamWiseTagsArray['Stream'] as $data){
								if(!in_array($data, $userFollowsTags) && !in_array($data, $userUnfollowsTags) && !in_array($data, $recommendedTagResults) && !in_array($data, $highLevelTags)){
									$recommendedTagResults[$countOfRecommendedTags++] = $data;
									$recommendedTagResultsReason[$data] = 'topTags';
									if($countOfRecommendedTags >= 10){
										break;	
									}
								}
							}
						}
					}
					$recommendedTagResults = array_slice(array_values($recommendedTagResults), 0, 10, TRUE);
				}
				
				if($this->isUser()){
					$userFollowsUser	= $this->predisLibrary->getMembersOfSet('userFollows:user:'.$this->getVisitorIdentificationKey());
					$userFreinds		= $this->predisLibrary->getMembersOfSet('userFreind:user:'.$this->getVisitorIdentificationKey());
					$userNetwork		= array_unique(array_merge($userFollowsUser, $userFreinds));
				}
				$userNetworkTags	= array();
				if(!empty($userNetwork)){
					$userNetworkTags	= $this->personalizationmodel->getFollowTypeData($userNetwork, 'tag', 'follow', $recommendedTagResults);
				}
				
				$tagsIdsForWhichDataToBeFetched = $recommendedTagResults;
				if(!empty($recommendedTagDueToParentTag)){
					foreach($recommendedTagDueToParentTag as $key => $value){
						foreach($parentToChildrenMapping as $parentTagId => $childTagsIds){
							if(in_array($key, $childTagsIds)){
								$recommendedTagDueToParentTag[$key] = $parentTagId;
								break;
							}
						}
					}
					$tagsIdsForWhichDataToBeFetched = array_unique(array_merge($tagsIdsForWhichDataToBeFetched, $recommendedTagDueToParentTag));
				}
				$recommendedTagResultsData = $this->personalizationmodel->getTagNames($tagsIdsForWhichDataToBeFetched);
				//if(!empty($userNetwork)){
					foreach($recommendedTagResults as $data){
						$temp = array();
						$temp['tagId']		= $recommendedTagResultsData[$data]['tagId'];
						$temp['tagName']	= $recommendedTagResultsData[$data]['tagName'];
						$temp['followers']	= $recommendedTagResultsData[$data]['followers'];

						foreach($userNetworkTags as $userIdOfNetwork => $networkTags){
							if(in_array($data, $networkTags)){
								$temp['usersFromNetwork'][] = $userIdOfNetwork;
							}
						}
						$temp['reason'] = $recommendedTagResultsReason[$data];
						if($temp['reason'] == 'relatedTags' && key_exists($temp['tagId'], $recommendedTagDueToParentTag)){
							$temp['reasonTag'] = $recommendedTagResultsData[$recommendedTagDueToParentTag[$temp['tagId']]]['tagName'];
						}
						$finalResult[] = $temp;
					}
				//}
			} catch (Exception $e) {
				error_log(' :: Exception Occured :: '.$e->getMessage().' :: Trace '.$e->getTrace());
			}
			
			return $finalResult;
		}

		function setDebugFlag($flag){
			$this->debugFlag = $flag;
		}

		function getDebugData(){
			return $this->debugData;
		}

		public function getUserRcommendation(){
			$recommendedUserResults = array();
			try {
				// if this is not a logged in user(i.e. visiting user is normal visitor or other case), no need to generate user recommendation
				if(!($this->setVisitorIdentificationKey() && $this->isUser())){
					return array();
				}
				if(method_exists($this->predisLibrary, 'isRedisAlive') && $this->predisLibrary->isRedisAlive() === FALSE){
					return $recommendedUserResults;
				}
				$userFollows = $this->predisLibrary->getMembersOfSet('userFollows:user:'.$this->userId);
				$userFriends = $this->predisLibrary->getMembersOfSet('userFreind:user:'.$this->userId);
				$userUnfollowsUser = $this->personalizationmodel->getFollowTypeData(array($this->userId), 'user', 'unfollow');
				if(!is_array($userUnfollowsUser[$this->userId])){
					$userUnfollowsUser[$this->userId] = array();
				}
				
				$userInNetwork = array_unique(array_merge($userFollows, $userFriends));
				//_p($userInNetwork);
				$usersFollowersBucket = array();
				foreach ($userInNetwork as $value){
					
					// if someone in network but yet not being followed on shiksha(also not explicitly unfollowed) then we need to push that user also in recommendation
					if(!in_array($value, $userFollows) && !in_array($value, $userUnfollowsUser[$this->userId]) && !in_array($this->userId, $usersFollowersBucket[$value]) && $value != $this->userId){
						$usersFollowersBucket[$value][] = $this->userId;
					}
					
					// get all users being followed by userInNetwork
					$follows = $this->predisLibrary->getMembersOfSet('userFollows:user:'.$value);
					//echo 'for :'.$value;_p($follows);
					foreach ($follows as $followsId){
						if((in_array($followsId, $userFollows)) || ($followsId == $this->userId) || (in_array($followsId, $userUnfollowsUser[$this->userId]))){
							continue;
						}
						if(!in_array($value, $usersFollowersBucket[$followsId])){
								$usersFollowersBucket[$followsId][] = $value;
						}
					}
				}
				//_p($usersFollowersBucket);//die;
				uasort($usersFollowersBucket, function ($a, $b){
					if (count($a) == count($b)){
						return 0;
					}else{
						return ((count($a) < count($b))? 1 : -1 );
					}
					
				});
				//echo 'haha';
				/* _p($usersFollowersBucket);
				die; */
				$usersTopActiveTags = $this->predisLibrary->getMembersInSortedSet('userFollowsTag:user:'.$this->userId, 0, $this->topTagsForUserCount, TRUE, FALSE);
				$userUnfollowsTag	= $this->personalizationmodel->getFollowTypeData(array($this->userId), 'tag', 'unfollow', $usersTopActiveTags);
				if(!is_array($userUnfollowsTag[$this->userId])){
					$userUnfollowsTag[$this->userId] = array();
				}
				$userTopActiveTagFiltered = array_diff($usersTopActiveTags, $userUnfollowsTag[$this->userId]);
				$topTagTopContributorForUser = array();
				if(count($userTopActiveTagFiltered) == 0){
					$streamWiseTagsArray = $this->personalizationmodel->getTagBasedOnEntity(array('Stream'));
					$userTopActiveTagFiltered = array_diff($streamWiseTagsArray['Stream'], $userUnfollowsTag[$this->userId]);
				}
				//_p($userTopActiveTagFiltered);die;
				foreach ($userTopActiveTagFiltered as $value){
					$contributors = $this->predisLibrary->getMembersOfSet('tagsTopContributors:'.$value);
					//echo 'for_tag_id: '.$value;_p($contributor);
					
					foreach ($contributors as $contributorId){
						if(($contributorId != $this->userId) && !in_array($contributorId, $userFollows) && !in_array($contributorId, $userUnfollowsUser[$this->userId]) && !key_exists($contributorId, $topTagTopContributorForUser)){
							$topTagTopContributorForUser[$contributorId] = array(	'userId' => $contributorId, 'tagId' => $value);
							break;
						}
					}
					if(count($topTagTopContributorForUser) >= 10){
						break;
					}
				}
				reset($usersFollowersBucket);
				reset($topTagTopContributorForUser);
				/* _p($usersFollowersBucket);
				_p($topTagTopContributorForUser);//die; */
				
				
				while (TRUE){
					$followerValue = current($usersFollowersBucket);
					if($followerValue){
						if(!key_exists(key($usersFollowersBucket), $recommendedUserResults)){
							$temp = array();
							$temp['userId']	= key($usersFollowersBucket);
							if(count($followerValue) == 1 && $followerValue[0] == $this->userId){
								$temp['reason']	= 'facebookFriend';
							}else {
								$temp['reason']	= 'userNetwork';
							}
							$temp['userIds']= array_values(array_diff($followerValue, array($this->userId)));
							$recommendedUserResults[$temp['userId']] = $temp;
							//_p($temp);
						}
						next($usersFollowersBucket);
					}
					$topTagvalue = current($topTagTopContributorForUser);
					if($topTagvalue){
						if(!key_exists($topTagvalue['userId'], $recommendedUserResults)){
							$temp = array();
							$temp['userId'] = $topTagvalue['userId'];
							$temp['reason']= 'topTagContributor';
							$temp['tagId']	= $topTagvalue['tagId'];
							$recommendedUserResults[$temp['userId']] = $temp;
							//_p($temp);
						}
						next($topTagTopContributorForUser);
					}
					if(!($followerValue || $topTagvalue)){
						break;
					}elseif (count($recommendedUserResults) >= 10){
						break;
					}
				}//die;
				
			} catch (Exception $e) {
				error_log(' :: Exception Occured :: '.$e->getMessage().' :: Trace '.$e->getTrace());
			}
			return $recommendedUserResults;
		}
		
		/**
		 * 	This function is responsible for migration of visitor profile related data to corresponding user profile.
		 * @author	Abhinav
		 * @param	visitorId	Visitor ID for whom data is to be migrated
		 * @param	userId		User ID to whom data is to be migrated
		 */
		public function convertVisitorProfileToUserProfile($visitorId = '', $userId = ''){
			if(!empty($visitorId)){
				$this->setVisitorId($visitorId);
			}
			if(!empty($userId)){
				$this->setUserId($userId);
			}
			if(!($this->setVisitorIdentificationKey() && $this->isUser())){
				return FALSE;
			}
			$visitorHomeFeedHashKey		= str_replace('<userId>', $this->visitorId, $this->userHomeFeedDataKeyPattern);
			$visitorNewHomeFeedKey		= str_replace('<userId>', $this->visitorId, $this->userHomeFeedNewDataKeyPattern);
			$userHomeFeedHashKey		= str_replace('<userId>', $this->userId, $this->userHomeFeedDataKeyPattern);
			$userNewHomeFeedKey			= str_replace('<userId>', $this->userId, $this->userHomeFeedNewDataKeyPattern);
			
			$predisLibrary = PredisLibrary::getInstance();
			$visitorTagAffinityArray = $predisLibrary->getMembersInSortedSet('userFollowsTag:user:'.$this->visitorId, 0, -1, FALSE, TRUE);
			foreach ($visitorTagAffinityArray as $key => $value){
				$predisLibrary->removeMembersOfSet('tagFollowers:tag:'.$key, array($this->visitorId), TRUE);
				$predisLibrary->addMembersToSet('tagFollowers:tag:'.$key, array($this->userId), TRUE);
			}
			//$predisLibrary->executePipeLine();
			if($predisLibrary->checkIfKeyExists('userFollowsTag:user:'.$this->visitorId)){
				//$predisLibrary->renameKey('userFollowsTag:user:'.$this->visitorId, 'userFollowsTag:user:'.$this->userId, TRUE);
				$predisLibrary->incrementValueOfMembersOfSortedSet('userFollowsTag:user:'.$this->userId, $visitorTagAffinityArray, TRUE);
				$predisLibrary->deleteKey(array('userFollowsTag:user:'.$this->visitorId),TRUE);
			}
			if($predisLibrary->checkIfKeyExists($visitorNewHomeFeedKey)){
				//$predisLibrary->renameKey($visitorNewHomeFeedKey, $userNewHomeFeedKey, TRUE);
				$predisLibrary->deleteKey(array($visitorNewHomeFeedKey),TRUE);
			}
			if($predisLibrary->checkIfKeyExists($visitorHomeFeedHashKey)){
				//$predisLibrary->renameKey($visitorHomeFeedHashKey, $userHomeFeedHashKey, TRUE);
				$visitorHomeFeed = $predisLibrary->getAllMembersOfHashWithValue($visitorHomeFeedHashKey);
				$predisLibrary->addMembersToHash($userHomeFeedHashKey, $visitorHomeFeed, TRUE,TRUE);
				$predisLibrary->deleteKey(array($visitorHomeFeedHashKey),TRUE);
			}
			$predisLibrary->executePipeLine();
			return TRUE;
		}
        
		/**
		 * 	This function is responsible for handling in-active users of system. Based on cases of different definitions(in terms of days) of in-activity handling will be done. 
		 * @author	Abhinav
		 * 
		 */
        public function cleanPersonalizationDataForInActiveUsers(){
        	
        	$predisLibrary = PredisLibrary::getInstance();
            $inActiveDaysForUser = $this->CI->config->item('inActiveDaysForUser');
            $inActiveDaysForVisitor = $this->CI->config->item('inActiveDaysForVisitor');
            
            $userHomeFeedKeysInRedis = $predisLibrary->fetchKeysBasedOnPattern(str_replace('<userId>', '*', $this->userHomeFeedDataKeyPattern));

            $userIds = array();
            $visitorIds = array();

            foreach($userHomeFeedKeysInRedis as $key => $value){
                $data = explode(':', $value);
                if(ctype_digit($data[2])){
                    $userIds[] = $data[2];
                }else{
                    $visitorIds[] = $data[2];
                }
            }
            
            $inactiveUserIds = $this->personalizationmodel->getInActiveUsersFromGivenSet($userIds,$inActiveDaysForUser);
            
            $inactiveVisitorsIds = $this->personalizationmodel->getInActiveVisitorsFromGivenSet($visitorIds, $inActiveDaysForVisitor);

            // remove non-ascii chars from homefeed keys
            foreach($inactiveVisitorsIds as $key=>$value) {

            	if(preg_match('/[^\x20-\x7f]/', $value))
            		unset($inactiveVisitorsIds[$key]);
                // $string = preg_replace('/[[:^print:]]/', '', $value);
                
            }

	       	error_log("inactiveUserIds : ".count($inactiveUserIds));
			error_log("inactiveVisitorsIds : ".count($inactiveVisitorsIds));
			error_log("data : ".$inActiveDaysForVisitor." and ".$inActiveDaysForUser);
           	error_log("Inactive users : ".count($inactiveUserIds)." and inactive visitors : ".count($inactiveVisitorsIds));
            
	    $this->cleanDataForUser($inactiveUserIds, 'user');
            $this->cleanDataForUser($inactiveVisitorsIds, 'visitor');
		return array('usersCount' => count($inactiveUserIds) , 'visitorsCount' => count($inactiveVisitorsIds));
        }
        
        private function cleanDataForUser($entityIds = array(), $type = 'user'){
            if(!is_array($entityIds) || empty($entityIds) || !in_array($type, array('user', 'visitor'))){
                return FALSE;
            }
            $predisLibrary					= PredisLibrary::getInstance();
            $tagUserIdsToBeRemoved			= array();
            $highLevelTags					= $predisLibrary->getMembersOfSet('highLevelTagsForPersonalization');
            $userTagAffinityForDatabase		= array();
            $userTagAffinityKeysToDelete	= array();
            $existingTagAffinityForUsers	= array();
            $existingTagAffinity			= array();
            // get tag affinity if already in database
            for($i=0;$i<count($entityIds);$i+=200){
            	$entityIdsForWhichDataToFetch = array_slice($entityIds, $i, 200);
            	if(!empty($entityIdsForWhichDataToFetch)){
            		$existingTagAffinityForUsers += $this->personalizationmodel->getTopTagsFromDatabaseForInActiveUsers($entityIdsForWhichDataToFetch,$type);
            	}
            }
            //echo ' Existing Tag Affinity IN DB for '.$type.' : '._p($existingTagAffinityForUsers);
            foreach($entityIds as $entityId){
			error_log("For Entity ID  : ".$entityId);
            	$existingTagAffinity = array();
                // get all implicit tags for users
                $userImplicitTags = $predisLibrary->getMembersInSortedSet('userFollowsTag:user:'.$entityId, 0, -1, TRUE, TRUE);
                if(array_key_exists($entityId, $existingTagAffinityForUsers)){ // if tags already in database, then consider them also
                	$existingTagAffinity = json_decode($existingTagAffinityForUsers[$entityId]);
                	foreach ($existingTagAffinity as $key => $value){
                		if(key_exists($key, $userImplicitTags)){// if already in database then add affinity to existing value
                			$userImplicitTags[$key] += $value;
                		}else{// otherwise add as new tag with affinity value
                			$userImplicitTags[$key] = $value;
                		}
                	}
                }
                $countOfTopActiveTagsThisUser = 0;
                foreach ($userImplicitTags as $tagId => $tagAffinityValue){
                    $tagUserIdsToBeRemoved[$tagId][] = $entityId;
                    if($countOfTopActiveTagsThisUser < $this->topTagsForUserCount){
                    	$userTagAffinityForDatabase[$entityId][$tagId] = $tagAffinityValue;
                    	if(!in_array($tagId, $highLevelTags)){
                    		$countOfTopActiveTagsThisUser++;
                    	}
                    }else{
                    	break;
                    }
                }
                $userTagAffinityKeysToDelete[] = 'userFollowsTag:user:'.$entityId;
                if(count($userTagAffinityForDatabase) >= 200){

                	// insert in database
                	$this->personalizationmodel->insertTagAffinityDataForUsers($userTagAffinityForDatabase , $type);
                	// delete use tag affinity keys
                	$predisLibrary->deleteKey($userTagAffinityKeysToDelete);

                	$userTagAffinityForDatabase	= array();
                	$userTagAffinityKeysToDelete= array();
                }
                
                /** 
				*	add user in in-active user bit set. Only if it is a user
				*	This specific userId check is added to avoid invalid userIds. We found some random userId on live server with some very high value of userId.
				*/
                if($type == 'user' && $entityId < 9999999){
                	$predisLibrary->setBit('inActiveUserForHomeFeed',$entityId,1);
                }
            }
            // further insert in database and remove from database if there are any left
            if(count($userTagAffinityForDatabase) > 0){

            	$this->personalizationmodel->insertTagAffinityDataForUsers($userTagAffinityForDatabase , $type);
            	$predisLibrary->deleteKey($userTagAffinityKeysToDelete);
            }
            foreach ($tagUserIdsToBeRemoved as $tagId => $userIdArray){
            	// remove all in-active userIds from respective tags
            	$predisLibrary->removeMembersOfSet('tagFollowers:tag:'.$tagId, $userIdArray);
            }
            $keysToDelete = array();
            // delete home feed related bucket
            for($i=0; $i < count($entityIds); $i++){
            	$keysToDelete[] = str_replace('<userId>', $entityIds[$i], $this->userHomeFeedDataKeyPattern);
            	$keysToDelete[] = str_replace('<userId>', $entityIds[$i], $this->userHomeFeedNewDataKeyPattern);
            	$keysToDelete[] = str_replace('<userId>', $entityIds[$i], $this->userHomeFeedBackFillDataKeyPattern);
            	if(count($keysToDelete) >= 200){
            		$predisLibrary->deleteKey($keysToDelete);
            		$keysToDelete = array();
            	}
            }
            if(count($keysToDelete) > 0){
            	$predisLibrary->deleteKey($keysToDelete);
            }
            $predisLibrary->executePipeLine();
            return ;
        }
        
        public function restorePersonalizationDataForReturningUser($backFillThreadFlag = FALSE){
        	// this return call is placed as this piece of code will go live in next sprint of SPRINT_10MAY_23MAY
        	//return;
        	$tagAffinityForUser = array();
        	$this->setVisitorIdentificationKey();
        	if($this->isUser()){
        		$isInActiveUser = $this->predisLibrary->getBit('inActiveUserForHomeFeed',$this->getVisitorIdentificationKey());
        		if($isInActiveUser == 0){ // if not in-active user then we don't need to prepare home feed from this process
        			return array();
        		}
        		$tagAffinityForUser = $this->personalizationmodel->getTopTagsFromDatabaseForInActiveUsers(array($this->getVisitorIdentificationKey()),'user');
        	}elseif($this->isVisitor()){
        		$tagAffinityForUser = $this->personalizationmodel->getTopTagsFromDatabaseForInActiveUsers(array($this->getVisitorIdentificationKey()),'visitor');
        	}else{
        		return array();
        	}
        	if(empty($tagAffinityForUser)){ // if no tag affnity for user and this user is visitor then no need for this process
        		return array();
        	}
        	
        	// decode tag affinity data from database
        	$tagAffinityForUser = json_decode($tagAffinityForUser[$this->getVisitorIdentificationKey()],true);
        	
        	// get high level tags for user from redis
        	$highLevelTags = $this->predisLibrary->getMembersOfSet('highLevelTagsForPersonalization');
        	
        	// insert tag affinity data for user
        	foreach($tagAffinityForUser as $key=>$value){
        		if(!in_array($key, $highLevelTags)){
        			$this->predisLibrary->addMembersToSet('tagFollowers:tag:'.$key,array($this->getVisitorIdentificationKey()),TRUE);
        		}
        	}
        	
        	// add user tag affinity in redis
        	$this->predisLibrary->incrementValueOfMembersOfSortedSet('userFollowsTag:user:'.$this->getVisitorIdentificationKey(),$tagAffinityForUser, TRUE);
        	$this->predisLibrary->executePipeLine();
        	if($this->isUser()){
        		$this->personalizationmodel->markTagsAsHistoryForActiveUsers(array($this->getVisitorIdentificationKey()),'user');
        		// finally remove(set to false) this user from in-active user set
        		$this->predisLibrary->setBit('inActiveUserForHomeFeed',$this->getVisitorIdentificationKey(),0);
        	}elseif ($this->isVisitor()){
        		$this->personalizationmodel->markTagsAsHistoryForActiveUsers(array($this->getVisitorIdentificationKey()),'visitor');
        	}else{
        		return array();
        	}
        	
        	// if backFillThreadFlag is set to TRUE then only back fill data for this user
        	if($backFillThreadFlag !== TRUE){
        		return array();
        	}
        	
        	$tagsForWhichThreadToBeFetched = array();
        	// get all those tags which are not high level tags
        	foreach($tagAffinityForUser as $tagId => $affinityScore){
        		if(!in_array($tagId, $highLevelTags)){
        			$tagsForWhichThreadToBeFetched[] = $tagId;
        		}
        	}
        	if(empty($tagsForWhichThreadToBeFetched)){
        		return array();
        	}
        	
        	if($this->isUser()){
        		$threadDataToBeRestoredForReturningUser = $this->personalizationmodel->getTopThreadsBasedOnTags($tagsForWhichThreadToBeFetched,$this->getVisitorIdentificationKey());
        	}else{
        		$threadDataToBeRestoredForReturningUser = $this->personalizationmodel->getTopThreadsBasedOnTags($tagsForWhichThreadToBeFetched);
        	}
        	
        	$homeFeedDataForUser = array();
        	$newHomeFeedDataForUser = array();
        	foreach ($threadDataToBeRestoredForReturningUser as $threadId => $threadData){
				$personalizedData = new PersonalizedData();
				$personalizedData->setActionByUserId($threadData['actionByUser']);
				$personalizedData->setAction($threadData['action']);
				$personalizedData->setThreadId($threadData['threadId']);
				$personalizedData->setActionDateTime(date('Ymdhis', strtotime($threadData['creationDate'])));
				$personalizedData->setActionItemId($threadData['msgId']);
				$personalizedData->setTagId($threadData['tagId']);
				$personalizedData->setReason('11');
				$homeFeedDataForUser['thread:'.$threadId] = $personalizedData->getHomeFeedString();
				$newHomeFeedDataForUser['thread:'.$threadId] = -1;
        	}
        	
        	if(!empty($homeFeedDataForUser)){
        		$this->predisLibrary->addMembersToHash(str_replace('<userId>', $this->getVisitorIdentificationKey(), $this->userHomeFeedDataKeyPattern),$homeFeedDataForUser);
        		$this->predisLibrary->addMembersToSortedSet(str_replace('<userId>', $this->getVisitorIdentificationKey(), $this->userHomeFeedNewDataKeyPattern),$newHomeFeedDataForUser);
        	}
        	
        	return $homeFeedDataForUser;
        }
	}
