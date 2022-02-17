<?php
	class AnALibrary{
		private $CI;
		
		public function __construct(){
			$this->CI = & get_instance();
		}
		
		public function getRecentThreadsForAllQuestionDiscussion($userId = 0,$threadType, $dateForWhichThreadsToBePicked, $startDateOfPostingOfThreadEpoch, $pageNumber = 1, $resultPerPage = 20){
			if(!in_array($threadType, array('user','discussion'))){
				return array();
			}
			if(empty($dateForWhichThreadsToBePicked)){
				$dateForWhichThreadsToBePicked = date('Y-m-d');
			}
			if(empty($pageNumber) || $pageNumber <= 0){
				$pageNumber = 1;
			}
			
			$dateForWhichThreadsToBePickedEpochFormat	= strtotime($dateForWhichThreadsToBePicked);
			$currentDateEpochFormat						= strtotime(date('Y-m-d'));
			
			$this->CI->load->model('AnAModel');
			$this->CI->load->model('v1/tagsmodel');
			$this->CI->load->model("user/usermodel");
			$predisLibrary	= PredisLibrary::getInstance();
			$response = array();
			$result = $this->CI->AnAModel->getRecentThreads($threadType, $dateForWhichThreadsToBePicked, (($pageNumber - 1) * $resultPerPage), $resultPerPage);
			//_p($result);
			//die;
			if($threadType == 'user'){
				$type				= 'Q';
				$uniqueId			= 1;
				$threadHeading		= "New Question";
				$threadTypeForUrl	= "question";
			}else{
				$type				= 'D';
				$uniqueId			= 2;
				$threadHeading		= "New Discussion";
				$threadTypeForUrl	= "discussion";
			}
			$recentThreadIdsRecieved = array_keys($result['data']);
			
			$answerCommentIds	= array();
			$userIdsInThreadData= array();
			$threadTags			= array();
			$tagIds				= array();
			$threadIds			= array();
            $answeredThreads    = array();
			
			foreach ($result['data'] as $key => $data){
				//echo 'data : threadOwner : '.$data['threadOwner'];die;
				$userIdsInThreadData[]			= $data['threadOwner'];
				$threadIds[]					= $data['threadId'];
				$threadTags[$data['threadId']]	= $predisLibrary->getMembersInSortedSet('threadTags:thread:'.$data['threadId'], 0, -1, FALSE, FALSE);
				$tagIds							= array_merge($tagIds, $threadTags[$data['threadId']]);
				if(isset($data['answerCommentId']) && $data['answerCommentId'] > 0){
					$answerCommentIds[]	= $data['answerCommentId'];
					$userIdsInThreadData[] = $data['answerCommentOwnerId'];
                    if($threadType == 'user'){
                        $answeredThreads[] = $data['threadId'];
                    }
				}
			}
			//_p($result);
			$threadFollowing		= array();
			$ratingCounts			= array();
			$userDetails			= array();
			$userAdditionalDetails	= array();
			$userLevelDetails		= array();
			$commentAnswerDetails	= array();
			$tagDetails				= array();
			$discussionsCommentCount= array();
			$lastThreadIdInResponse = 0;
			
			if(!empty($tagIds)){
				$tagDetails = $this->CI->tagsmodel->getTagsDetailsById($tagIds);
				
				// for every thread having tags, sort tagIds in order of tag_entity in ('Colleges','University')
				foreach ($threadTags as $key => &$tagIdsArray){
					usort($tagIdsArray, function($a , $b) use($tagDetails){
						if(in_array($tagDetails[$a]['tag_entity'], array('Colleges','University'))){
							return -1;
						}elseif (in_array($tagDetails[$b]['tag_entity'], array('Colleges','University'))){
							return 1;
						}else{
							return -1;
						}
					});
						 
				}
			}
			
			if(!empty($threadIds)){
				/* if($threadType == 'question'){
					$threadBasicDetails	= $this->CI->AnAModel->getQuestionsBasicDetails($threadIds);
				}else{
					$threadBasicDetails	= $this->CI->AnAModel->getDiscussionsBasicDetails($threadIds);
					$discussionsCommentCount = $this->CI->AnAModel->getDiscussionsCommentCount($discussionIds);
				} */
				if($threadType == 'discussion'){
					$discussionsCommentCount = $this->CI->AnAModel->getDiscussionsCommentCount($threadIds);
				}
				$threadFollowers = $this->CI->AnAModel->getThreadFollowers($threadIds);
			}
			
			if(!empty($userId) && $userId > 0){
				$threadFollowing        = $this->CI->tagsmodel->isUserFollowingEntity($userId, $threadIds, array('question','discussion'));
                if(!empty($answeredThreads)){
                    $userAnsweredQuestions  = $this->CI->tagsmodel->getQuestionsAnsweredByUser(implode(',',$answeredThreads), $userId);
                }
			}
			
			if(!empty($answerCommentIds)){
				$ratingCounts	= $this->CI->AnAModel->getUpAndDownVotesOfEntities($answerCommentIds);
			}
			
			if(!empty($answerCommentIds) && $userId > 0){
				$commentAnswerDetails	= $this->CI->AnAModel->getAnswerCommentDetails($answerCommentIds, $userId);
			}
			
			if(!empty($userIdsInThreadData)){
				$userDetails			= $this->CI->usermodel->getUsersBasicInfoById($userIdsInThreadData);
				$userAdditionalDetails	= $this->CI->usermodel->getUsersAdditionalInfo($userIdsInThreadData);
				$userLevelDetails		= $this->CI->AnAModel->getAnAUsersLevel($userIdsInThreadData);
			}
			
			foreach ($result['data'] as $key => $data){
				$temp	= array();
				$temp['type'] 				= $type;
				$temp['uniqueId']			= $uniqueId;
				$temp['id']					= $data['threadId'];
				$temp['questionId']			= $data['threadId'];
				$temp['activityTime']		= makeRelativeTime($data['threadCreationDate']);
				$temp['headingUserId']		= $data['threadOwner'];
				$temp['heading']			= $threadHeading;
				$temp['setHeadingUsername']	= FALSE;
				$temp['title']				= htmlspecialchars_decode($data['threadTxt']);
				//$temp['answerCount']		= '';
				if($threadTypeForUrl == 'question'){
					$temp['answerCount']	= (isset($data['msgCount']) && $data['msgCount'] >= 0)?$data['msgCount']:0;
				}else{
					$temp['answerCount']	= (isset($discussionsCommentCount[$data['threadId']]) && $discussionsCommentCount[$data['threadId']] >= 0)?$discussionsCommentCount[$data['threadId']]:0;
				}
				$temp['viewCount']			= $data['viewCount'];
				$temp['threadStatus']		= $data['status'];
				if($data['threadOwner'] == $userId){
					$temp['isThreadOwner']	= TRUE;
				}else{
					$temp['isThreadOwner']	= '';
				}
                if($type == 'Q'){
                    $temp['hasUserAnswered']= (is_array($userAnsweredQuestions) && in_array($data['threadId'], $userAnsweredQuestions))?TRUE:FALSE;
                }
				$temp['creationDate']		= $data['threadCreationDate'];
				$temp['followerCount']		= (isset($threadFollowers[$data['threadId']]) && $threadFollowers[$data['threadId']] >= 0)?$threadFollowers[$data['threadId']]:0;
				$temp['URL']				= getSeoUrl($data['threadId'], $threadTypeForUrl, $data['threadTxt'], array(), 'NA', date('Y-m-d', strtotime($data['threadCreationDate'])));
				$temp['isUserFollowing']	= in_array($data['threadId'], $threadFollowing)?TRUE:FALSE;
				if(isset($data['answerCommentId']) && $data['answerCommentId'] > 0){
					$temp['likeCount']				= (isset($ratingCounts[$data['answerCommentId']][1]) && $ratingCounts[$data['answerCommentId']][1] >= 0)?$ratingCounts[$data['answerCommentId']][1]:0;
					$temp['dislikeCount']			= (isset($ratingCounts[$data['answerCommentId']][0]) && $ratingCounts[$data['answerCommentId']][0] >= 0)?$ratingCounts[$data['answerCommentId']][0]:0;
					$temp['answerId']				= $data['answerCommentId'];
					$temp['answerText']				= sanitizeAnAMessageText(htmlspecialchars_decode($data['answerCommentTxt']), 'answer');
					$temp['answerOwnerUserId']		= $data['answerCommentOwnerId'];
					$temp['answerOwnerName']		= $userDetails[$data['answerCommentOwnerId']]['firstname'].' '.$userDetails[$data['answerCommentOwnerId']]['lastname'];
					if(empty($userDetails[$data['answerCommentOwnerId']]['avtarimageurl'])){
						$temp['answerOwnerImage']	= NULL;
					}else{
						$temp['answerOwnerImage'] = addingDomainNameToUrl(array('url' => trim($userDetails[$data['answerCommentOwnerId']]['avtarimageurl']),'domainName' => MEDIA_SERVER));
					}
					$temp['answerOwnerLevel']		= isset($userLevelDetails[$data['answerCommentOwnerId']])?$userLevelDetails[$data['answerCommentOwnerId']]:'Beginner-Level 1';
					$temp['hasUserVotedUp']			= ($commentAnswerDetails[$data['answerCommentId']]['hasUserVoted'] == '1')?TRUE:FALSE;
					$temp['hasUserVotedDown']		= ($commentAnswerDetails[$data['answerCommentId']]['hasUserVoted'] == '0')?TRUE:FALSE;
					$temp['aboutMe']				= isset($userAdditionalDetails[$data['answerCommentOwnerId']])?html_entity_decode($userAdditionalDetails[$data['answerCommentOwnerId']]['aboutMe']):NULL;
				}else{
					$temp['likeCount']				= 0;
					$temp['dislikeCount']			= 0;
					$temp['answerId']				= 0;
					$temp['answerText']				= NULL;
					$temp['answerOwnerUserId']		= NULL;
					$temp['answerOwnerName']		= NULL;
					$temp['answerOwnerImage']		= NULL;
					$temp['answerOwnerLevel']		= NULL;
					$temp['hasUserVotedUp']			= NULL;
					$temp['hasUserVotedDown']		= NULL;
					$temp['aboutMe']				= NULL;
				}
				
				$temp[tags] = array();
				foreach ($threadTags[$data['threadId']] as $threadTagId){
					if(count($temp['tags']) == 2){
						break;
					}
					$temp[tags][] = array('tagId' => $threadTagId, 'tagName' => $tagDetails[$threadTagId]['tags']);
				}
				$lastThreadIdInResponse	= $data['threadId'];
				$response['homepage'][]	= $temp;
			}
			
			// set total records for pagination purpose
			$response['totalRecords'] = $result['totalRecords'];
            if($result['totalRecords'] <= ($pageNumber * $resultPerPage)){
				if($dateForWhichThreadsToBePickedEpochFormat == $currentDateEpochFormat){
					$previousPaginationDateEpoch = strtotime("- 1 month",$dateForWhichThreadsToBePickedEpochFormat);
					
				}else{
					$previousPaginationDateEpoch = strtotime("- 1 day",$dateForWhichThreadsToBePickedEpochFormat);
				}
				if($previousPaginationDateEpoch && $previousPaginationDateEpoch >= $startDateOfPostingOfThreadEpoch){
					if($threadTypeForUrl == 'question'){
						$response['datePagination']['previousDayPagination']['url']		= "/questions/".date('dmY', $previousPaginationDateEpoch);
						$response['datePagination']['previousDayPagination']['text']	= "View ".DateTime::createFromFormat('Y-m-d h:i:s', date('Y-m-d h:i:s', $previousPaginationDateEpoch) )->format('jS F, Y')." Questions";
					}else{
						$response['datePagination']['previousDayPagination']['url']		= "/all-discussions/".date('dmY', $previousPaginationDateEpoch);
						$response['datePagination']['previousDayPagination']['text']	= "View ".DateTime::createFromFormat('Y-m-d h:i:s', date('Y-m-d h:i:s', $previousPaginationDateEpoch) )->format('jS F, Y')." Discussions";
					}
				}
			}
			if($pageNumber == 1){
				if($dateForWhichThreadsToBePickedEpochFormat != $currentDateEpochFormat){
					$nextPaginationDateEpoch	= strtotime("+ 1 day", $dateForWhichThreadsToBePickedEpochFormat);
					if($nextPaginationDateEpoch){
						$nextPaginationDateTime = new DateTime(date('Y-m-d', $nextPaginationDateEpoch));
						$currentDateTime		= new DateTime(date('Y-m-d', $currentDateEpochFormat));
						$interval	= $nextPaginationDateTime->diff($currentDateTime);
						$nextMonthMaxDays = date("t",$nextPaginationDateEpoch);
                        if($interval->y == 0 && $interval->m == 0 /*&& $interval->d == ($nextMonthMaxDays - 1)*/){
							if($threadTypeForUrl == 'question'){
								$response['datePagination']['nextDayPagination']['url']		= "/questions";
								$response['datePagination']['nextDayPagination']['text']	= "View Recent"." Questions";
							}else{
								$response['datePagination']['nextDayPagination']['url']		= "/all-discussions";
								$response['datePagination']['nextDayPagination']['text']	= "View Recent"." Discussions";
							}
						}else{
							if($threadTypeForUrl == 'question'){
								$response['datePagination']['nextDayPagination']['url']		= "/questions/".date('dmY', $nextPaginationDateEpoch);
								$response['datePagination']['nextDayPagination']['text']	= "View ".DateTime::createFromFormat('Y-m-d h:i:s', date('Y-m-d h:i:s', $nextPaginationDateEpoch) )->format('jS F, Y')." Questions";
							}else{
								$response['datePagination']['nextDayPagination']['url']		= "/all-discussions/".date('dmY', $nextPaginationDateEpoch);
								$response['datePagination']['nextDayPagination']['text']	= "View ".DateTime::createFromFormat('Y-m-d h:i:s', date('Y-m-d h:i:s', $nextPaginationDateEpoch) )->format('jS F, Y')." Discussions";
							}
						}
					}
				}
			}
			
			return $response;
		}
		
		function getUserDetails($userId){
			$this->CI->load->model('messageBoard/AnAModel');
			$userLevel = $this->CI->AnAModel->getOwnerLevel($userId);
			$this->usermodel = $this->CI->load->model('user/usermodel');
			$userData = $this->CI->usermodel->getUserBasicInfoById($userId);
		
			if(empty($userData['avtarimageurl'])){
				$userData['avtarimageurl'] = NULL;
			}else{
				$userData['avtarimageurl'] = addingDomainNameToUrl(array('url' => $userData['avtarimageurl'], 'domainName' => MEDIA_SERVER));
			}
		
			if($userData['aboutMe'] == ''){
				$userData['aboutMe'] = NULL;
			}else{
				$userData['aboutMe'] = html_entity_decode($userData['aboutMe']);
			}
		
			return $userDetailsArray = array(
					'userId'      => $userData['userid'],
					'firstName'   => $userData['firstname'],
					'lastName'    => $userData['lastname'],
					'picUrl'      => $userData['avtarimageurl'],
					'displayName' => $userData['displayname'],
					'email'       => $userData['email'],
					'level'       => (isset($userLevel['levelName']))?$userLevel['levelName']:'Beginner-Level 1',
					'levelId'     => $userLevel['levelId'],
					'userpoints'  => $userLevel['userpointvaluebymodule'],
					'aboutMe'     => $userData['aboutMe']
			);
		}
	function getEntityCountAndUrl($entityId, $entityType, $contentType='question'){
            $this->CI->load->model('messageBoard/QnAModel');
            $tagInfo  = $this->CI->QnAModel->getTagInfo($entityId, $entityType);
            $tagId    = $tagInfo['tag_id'];
            $this->CI->load->library("common_api/APICommonCacheLib");
            $apiCommonCacheLib = new APICommonCacheLib();
            $tagResult = $apiCommonCacheLib->getTagStats($tagId);
            if(!empty($tagResult)){
            	$res = json_decode($tagResult[0],true);
                $tags                     = $res['tagName'];
                $displayData['count']     = $res['questionCount'];
            }else{
                $result   			  = $this->CI->QnAModel->getEntityCount($entityId, $entityType, $contentType);
                $tags     			  = $result['tags'];
                $displayData['count'] = $result['totalCount'];
            }
            $displayData['url']   = getSeoUrl($tagId, 'tag', $tags);
            if($displayData['count'] <= 0) {
                return array();
            }
            return $displayData;
        }	

	function getAnsweredQuestiontIdBasedOnTagId($entityId, $entityType, $contentType='question',$limit = 3){
            $this->CI->load->model('messageBoard/QnAModel');
            $this->CI->load->model('v1/tagsmodel');
            $tagInfo  = $this->CI->QnAModel->getTagInfo($entityId, $entityType);
            $tagId    = $tagInfo['tag_id'];
            $questionIds    = $this->CI->QnAModel->getAnsweredQuestiontIdBasedOnTagId($tagId,$limit);
	    if(!empty($questionIds)){
            	$questionDetail = $this->CI->tagsmodel->getQuestionDetails(implode(",",$questionIds));
	            return $questionDetail;
	    }
	    return '';
        }

         function clientWidgetAnA($tags, $device = 'desktop'){
                 $this->CI->load->library('messageBoard/AnAClientWidgetConfig');
                 $client_details_array = AnAClientWidgetConfig::$client_details_array;
                 foreach($tags as $key=>$value){
                     if(isset($client_details_array[$value['tagName']])){
                         $values = $client_details_array[$value['tagName']];
                     }
                 }

		 if(is_array($values) && count($values)>0){
			 $currentDate = date('Y-m-d');                 
			 $finalVal = array();
			 $finalVal['headingText'] = $values['headingText'];
			 foreach ($values as $key=>$value){
				if(isset($value['startDate'])){
					if($value['startDate'] <= $currentDate && $value['endDate'] >=  $currentDate){
						$finalVal[$key] = $value;
					}
				}
		 	 }
		 }

                 if(is_array($finalVal)){
                     $displayData['links'] = $finalVal;
                     if($device == 'mobile'){
                     	$this->CI->load->view('mobile/cards/clientWidget',$displayData);        
                     }
                     else{
                         $this->CI->load->view('desktopNew/widgets/clientWidget',$displayData);
                     }
                 }
         }

        function getDetailsForMultipleQuestion($quesIds){
	        $this->CI->load->model('messageBoard/AnAModel');
	        $quesData = $this->CI->AnAModel->getDetailsForMultipleQuestions($quesIds);
	        $ansDetails = $this->CI->AnAModel->getUpvotedAnswerForMultipleQues($quesIds);
	        foreach($quesData as $details){
	        	$quesAnswerDetails[$details['msgId']]['ques'] = $details;

	            $quesAnswerDetails[$details['msgId']]['ques']['creationDate'] = makeRelativeTime($details['creationDate']);
	        	if($details['viewCount']>=1000){
        			$quesAnswerDetails[$details['msgId']]['ques']['viewCount'] = round(($details['viewCount']/1000),1).'k';
        		}

	            $quesAnswerDetails[$details['msgId']]['answer'] = $ansDetails[$details['msgId']];
	            $quesAnswerDetails[$details['msgId']]['answer']['userData']['firstname'] = utf8_decode($quesAnswerDetails[$details['msgId']]['answer']['userData']['firstname']);
	            $qdp_url = getSeoUrl($details['msgId'], 'question', $details['quesTxt']); 
	            $quesAnswerDetails[$details['msgId']]['ques']['url'] = $qdp_url;
	            if($details['answerCount']>=1000){
        			$quesAnswerDetails[$details['msgId']]['ques']['answerCount'] = round(($details['answerCount']/1000),1).'k';
        		}
        		if(empty($quesAnswerDetails[$details['msgId']]['answer']['userData']['picUrl'])){
                    $quesAnswerDetails[$details['msgId']]['answer']['userData']['picUrl'] = NULL;
		        }else 
		        {
		            $quesAnswerDetails[$details['msgId']]['answer']['userData']['picUrl'] = addingDomainNameToUrl(array('url' =>$quesAnswerDetails[$details['msgId']]['answer']['userData']['picUrl'], 'domainName' => MEDIA_SERVER ));   
		        }
	        }
	        return $quesAnswerDetails;

	    } 

	    function getDetailsForMultipleUnansweredQues($quesIds){
	        $this->CI->load->model('messageBoard/AnAModel');
	        $this->CI->load->helper('mAnA5/ana');       
	        $data = $this->CI->AnAModel->getDetailsForMultipleUnansweredQues($quesIds);
	        
	        foreach($data as $quesId=>$details){
	        	$qdp_url = getSeoUrl($details['quesDetails']['msgId'], 'question', $details['quesDetails']['msgTxt']); 
	        	$data[$details['quesDetails']['msgId']]['quesDetails']['url'] = $qdp_url;

	        	foreach($details['tags'] as $key=>$val){
	        		$data[$quesId]['tags'][$key]['url'] = getSeoUrl($val['tagId'], 'tag', $val['tagName']);
	        	}
	        	$data[$quesId]['quesDetails']['postedDate'] = makeRelativeTime($details['quesDetails']['postedDate']);
	        	if($details['quesDetails']['viewCount']>=1000){
        			$data[$quesId]['quesDetails']['viewCount'] = round(($details['quesDetails']['viewCount']/1000),1).'k';
        		}
	        }

	        return $data;
	    } 

	    function getDetailsForMultipleTag($tagIds){
	        $this->CI->load->model('messageBoard/AnAModel');
	        $this->CI->load->library("common_api/APICommonCacheLib");
            $apiCommonCacheLib = new APICommonCacheLib();
	        foreach($tagIds as $tagId){
	        	$tagResult = $apiCommonCacheLib->getTagStats($tagId);
	        	if(!empty($tagResult)){
	        		$res = json_decode($tagResult[0],true);
	        		$cacheData[$tagId]=$res;
	        	}
	        }
	        $tagData = $this->CI->AnAModel->getTagDetailsForMultipleTag($tagIds);
	        $followData= $this->CI->AnAModel->getFollowCountForMultipleTag($tagIds);

	        foreach($tagData as $key=>$value) {
	            $finalData[$key] = $value;
	            $finalData[$key]['tagUrl'] = getSeoUrl($key, 'tag', $value['tagName']);   
	            if(array_key_exists($key,$cacheData)){
	            	$finalData[$key]['followCount'] = $cacheData[$key]['followerCount'];
	            	$finalData[$key]['quesCount'] = $cacheData[$key]['questionCount'];
	            }else{
	            	$finalData[$key]['followCount'] = $followData[$key]['followCount'];
	            	$finalData[$key]['quesCount'] = $finalData[$key]['quesCount'];
	            }
	        }
	        foreach($finalData as $key=>$value){
	        	if($finalData[$key]['followCount'] >= 1000){
	   				$followerCount = round(($finalData[$key]['followCount']/1000),1).'k';
	   			}else{
	   				$followerCount = $finalData[$key]['followCount'];
	   			}

	   			$finalData[$key]['followCount'] = $followerCount;

	   			if($finalData[$key]['quesCount'] >= 1000){
	   				$quesCount = round(($finalData[$key]['quesCount']/1000),1).'k';
	   			}else{
	   				$quesCount = $finalData[$key]['quesCount'];
	   			}

	   			$finalData[$key]['quesCount'] = $quesCount;

	   			if($finalData[$key]['answerCount'] >= 1000){
	   				$ansCount = round(($finalData[$key]['answerCount']/1000),1).'k';
	   			}else{
	   				$ansCount = $finalData[$key]['answerCount'];
	   			}

	   			$finalData[$key]['answerCount'] = $ansCount;
	        }
	        
	        return $finalData;
	    } 

	    function checkWhetherEntitiesFollowedByUser($entityIds,$entityType,$userId){
	        $this->CI->load->model('messageBoard/AnAModel');
	        $data = $this->CI->AnAModel->checkIfUserHasFollowedAnEntity($entityIds,$entityType,$userId);
	        foreach($data as $details){
	           $followEntitySet[] = $details['entityId']; 
	        }

	        return $followEntitySet;
	    } 

	    function getTop2QuestionBasedOnQualityScore($tagIds){
	    	$this->CI->load->model('messageBoard/AnAModel');
	    	$this->CI->load->helper('mAnA5/ana');
	        $data = $this->CI->AnAModel->getQuestionsBasedOnQualityScore($tagIds);
	        
	        foreach($data as $tag_id => $details){
	        	foreach($details as $key=>$val){
	        		$data[$tag_id][$key]['postedDate'] = makeRelativeTime($val['postedDate']);
	        		$data[$tag_id][$key]['qdp_url'] = getSeoUrl($val['msgId'], 'question', $val['msgTxt']);
	        		if($val['viewCount'] >= 1000) {
	        			$data[$tag_id][$key]['viewCount'] = round(($val['viewCount']/1000),1).'k';
	        		}
	        	}
	        }

	        return $data;
	    }

	    function getQuestionCountForTags($tagIds) {
	    	$this->CI->load->model('messageBoard/AnAModel');
	    	$data = $this->CI->AnAModel->getQuestionCountForTags($tagIds);

	    	return $data;
	    }

         function articleWidgetAnA($device = 'desktop'){

               $articlemodel = $this->CI->load->model('article/articlenewmodel');
               $limit = array('pageSize'=>'10','lowerLimit'=>'0');
               $articles = $articlemodel->getAllArticles($limit);
               
              if(is_array($articles) && count($articles)>0){
                  $displayData['articles'] = $articles;
                  if($device == 'mobile'){
                     $this->CI->load->view('mobile/cards/articleWidget',$displayData);
                  }
                  else{
                      $this->CI->load->view('desktopNew/widgets/articleWidget',$displayData);
                  }
              }
         }

        function getAnAStats(){
               $this->CI->load->helper('string');
               $this->CI->load->library("common_api/APICommonCacheLib");
               $apiCommonCacheLib = new APICommonCacheLib();
               $result = $apiCommonCacheLib->getAnAStats();
               $finalArray = array();
               if(!empty($result)){
                  $finalArray = json_decode($result[0],true);
               }
               else{
                  $this->CI->load->model('messageBoard/AnAModel');
                  $result = $this->CI->AnAModel->getAnAStats();
                  $visitorCount = $this->getAnAVisitorCount();
                  $finalArray = array(
                        "contributorCount"        => formatNumberStats($result['contributorCount']),
                        "visitorCount"            => formatNumberStats($visitorCount),
                        "topicCount"              => formatNumberStats($result['topicCount']),
                        "answerCount"             => formatNumberStats($result['answerCount'])
                  );
                  $apiCommonCacheLib->insertAnAStats(array(json_encode($finalArray)));                  
               }
               return $finalArray;
        }
        
        function getAnAVisitorCount($durationInDays = 365){
        	$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
         	$clientCon = $ESConnectionLib->getShikshaESServerConnection();

        	$startDate = time();
        	$startDate = date('Y-m-d', strtotime('-'.$durationInDays.' day', $startDate));
        	$startDate .= ' 00:00:00';
        	$startDate = convertDateISTtoUTC($startDate);
        	$startDate = str_replace(" ", "T", $startDate);

        	$elasticQuery['index'] = PAGEVIEW_INDEX_NAME;
        	$elasticQuery['type']  = 'pageview';
        	$elasticQuery['body']['size'] = 0;
        	$elasticQuery['body']['query'] = array();

        	$elasticQuery['body']['query']['bool']['filter']['bool']['must'] = 
        	array(
        			array('terms' => array(
        									"pageIdentifier" => array("qnaPage","discussionPage","unAnsweredPage","questionDetailPage","discussionDetailPage","allQuestionsPage","allDiscussionsPage")
        								)
        				),
        			array("range" => array("visitTime" => array("gte" => $startDate)))
        		);
	        $elasticQuery['body']['aggs']['users']['cardinality']['field'] = 'visitorId';
	        $search = $clientCon->search($elasticQuery);
        	return $search['aggregations']['users']['value'];
        }
    function getChpUrlForTagsExistOnHierarchies($tagIds){
    	if(empty($tagIds))
    	{
    		return array();
    	}
    	$this->CI->load->library('TagUrlMapping');
        $tagUrlMappingObj = new TagUrlMapping(); 
        $tagsChpRequest = $tagUrlMappingObj->getTagsExistOnEntityTypes($tagIds,array("Stream","Sub-Stream","Specialization","Course"));

        $chpInputRequest = array();

        if(!empty($tagsChpRequest))
        {
            foreach ($tagsChpRequest as $key => $value) {
                if($value['entity_type'] == "Stream")
                {
                    $chpInputRequest[] = array('streamId' => $value['entity_id']);
                }
                else if($value['entity_type'] == "Sub-Stream")
                {
                    $chpInputRequest[] = array('substreamId' => $value['entity_id']);   
                }
                else if($value['entity_type'] == "Specialization")
                {
                    $chpInputRequest[] = array('specializationId' => $value['entity_id']);   
                }
                else if($value['entity_type'] == "Course")
                {
                    $chpInputRequest[] = array('basecourseId' => $value['entity_id']);   
                }
            }

            $this->CI->load->config("chp/chpAPIs");
	        $apiUrl = $this->CI->config->item('CHP_URL_BY_HIERACHIES');
	        $this->ChpClient = $this->CI->load->library('chp/ChpClient');
	        $result = $this->ChpClient->makeCURLCall('POST',$apiUrl, json_encode($chpInputRequest));
	        
	        $result = json_decode($result,true);

	        $chpTagMappingUrls = array();
	        //_p($result);die;
	        foreach ($result['data'] as $rkey => $rvalue) {
	        	if(!empty($rvalue["basecourseId"]))
	        	{ 
	        		if(!isset($chpTagMappingUrls['Course'][$rvalue["basecourseId"]])){
	        			$chpTagMappingUrls['Course'][$rvalue["basecourseId"]] = $rvalue["url"];
	        		}
	        	}
	        	else if(!empty($rvalue["specializationId"])) {
	        		$chpTagMappingUrls['Specialization'][$rvalue["specializationId"]] = $rvalue["url"];
	        	}
	        	else if(!empty($rvalue['substreamId'])) {
	        		$chpTagMappingUrls['Sub-Stream'][$rvalue["substreamId"]] = $rvalue["url"];	
	        	}
	        	else
	        	{
	        		$chpTagMappingUrls['Stream'][$rvalue["streamId"]] = $rvalue["url"];	
	        	}
	        }

	        $chpTagUrls = array();

	        foreach ($tagsChpRequest as $tagKey => $tagValue) {
	        	if(array_key_exists($tagValue['entity_type'], $chpTagMappingUrls) && array_key_exists($tagValue['entity_id'], $chpTagMappingUrls[$tagValue['entity_type']]))
	        	{
	        		$chpTagUrls[$tagKey] = array('url' => addingDomainNameToUrl(array('url' => $chpTagMappingUrls[$tagValue['entity_type']][$tagValue['entity_id']],'domainName' => SHIKSHA_HOME)),'type' => $tagValue['entity_type']);
	        	}
	        }
	        return $chpTagUrls;
        }
    }
      
}
?>
