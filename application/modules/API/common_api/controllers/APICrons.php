<?php
class APICrons extends MX_Controller {

	private $mailingList = array( "ankur.gupta@shiksha.com", "abhinav.pandey@shiksha.com");

	function __construct(){

		$this->load->library('alerts_client');
		$this->load->config("apiConfig");
		$this->load->config("NotificationConfig");
		$this->alertClient 		= new Alerts_client();
	}
	
	/**
	 * Cron to fetch facebook friends of the access token that are created within the specified period
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-07-27
	 * @param  integer    $day   [number of days]
	 * @param  integer    $hours [number of hours]
	 * @return [type]            [description]
	 */
	function fetchFacebookFriends($day = 0, $hours = 0){
		$this->validateCron();
		$scriptStartTime = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		// load resources
		$this->load->model("apicronmodel");
		$this->load->library("facebook");

		// compute the datetime till when the access tokens needs to be processed
		$fromTimePeriod = date("Y-m-d h:i:s", strtotime("-".$day." days -".$hours." hours"));

		// fetch the eligible access tokens
		$accessTokens = $this->apicronmodel->getFBAccessTokens($fromTimePeriod);

        $FB = new Facebook();

        // loop through each access token to fetch friend details
		foreach ($accessTokens as $accessTokensRow) {
			
			try{
	            
	            // fetch friends details
	            $friends = $FB->api("/me/friends",array("access_token" => $accessTokensRow['access_token']));	

	            // get facebookUserId of these friends
	            $facebookUserIds = array();
	            foreach ($friends['data'] as $value) {
	            	$facebookUserIds[] = $value['id'];
	            }

	            $shikshaUserIdsforFacebookUsers = array();
	            $shikshaUserIdsforFacebookUsers = $this->apicronmodel->getShikshaUserIdOfFacebookUsers($facebookUserIds);

	            $friendsShikshaUserIds = array();
	            foreach ($friends['data'] as $key=>$value) {
	            	if($shikshaUserIdsforFacebookUsers[$value['id']]){
	            		$friends['data'][$key]['shikshaUserId'] = $shikshaUserIdsforFacebookUsers[$value['id']];
	            		$friendsShikshaUserIds[] = $shikshaUserIdsforFacebookUsers[$value['id']];
	            	}
	            }

	            //Add the entry in Redis for Personalized Homepage 
				if($friendsShikshaUserIds && $accessTokensRow['userId']){ 
					$this->load->library('common/personalization/UserInteractionCacheStorageLibrary'); 
					$this->userinteractioncachestoragelibrary->updateFreindList($accessTokensRow['userId'], $friendsShikshaUserIds); 
				} 

	            // delete previous entries of friends
	            if($friends['data'])
	            	$this->apicronmodel->updateFacebookFriendsStatus($accessTokensRow['userId'], 'live', 'history');

	            // make new entries
	            if($friends['data'])
	            	$this->apicronmodel->insertFacebookFriends($accessTokensRow['userId'], $accessTokensRow['facebookUserId'], $friends);

	        }catch(FacebookApiException $e){
	            error_log("Facebook_Friend_Cron : ".print_r($e->getResult(), true));
	        }
		}

		// send GCM and InApp notification to those users whose friends have joined shiksha APP recently
		$this->load->library("Notifications/NotificationContributionLib");
        $notificationContributionLib = new NotificationContributionLib();
        $notificationContributionLib->FBFriendNotificationGenerator();


		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>FB Access Tokens Processed : ".count($accessTokens)."<br/>Contact Person: Ankur Gupta";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);

	}

	/**
	 * Script for indexing all those users who have some AnA Points
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-09-30
	 * @return [type]     [description]
	 */
	function scriptReIndexUsersWithAnAPoints(){

		ini_set("memory_limit", "2000M");

		$scriptStartTime = time();
		$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, "romil.goel@shiksha.com", "Started : ".__METHOD__, "", "html", '', 'n');

		$this->load->model("apicronmodel");	

		$apicronmodel = new apicronmodel();
		$users        = $apicronmodel->getUsersWithAnAPoints();

		$userIds = array();
		foreach($users as $userRow)
			$userIds[] = $userRow['userId'];

		$userIds = array_chunk($userIds, 5000);

		foreach ($userIds as $key=>$usersChunk) {
			error_log("AnAPointsUserIndexing: Indexing for user chunk ".($key+1)." , total chunks = ".count($userIds));
			Modules::run("user/UserIndexer/indexMultipleUsers", $usersChunk);		
		}

		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Script Ended Successfully.<br/>Time Taken : ".$timeTaken." mins";
		$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, "romil.goel@shiksha.com", "Ended : ".__METHOD__, $text, "html", '', 'n');

	}

	/**
	 * Cron to index all those users whose ana points have changed in the last n number of days
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-09-30
	 * @param  integer    $days [no of days for which users activity need to be checked]
	 * @return [type]           [description]
	 */
	function indexUsersWithChangedAnAPoints($days = 1){
		$this->validateCron();

		// increase memory limit
		ini_set("memory_limit", "1024M");

		// send cron starting alert
		$scriptStartTime = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		$this->load->model("apicronmodel");		
		$apicronmodel = new apicronmodel();

		// get users to be indexed
		$users = $apicronmodel->getUsersWithChangedAnAPoints($days);

		$userIds = array();
		foreach($users as $userRow)
			$userIds[] = $userRow['userId'];

		// make chunks of user-ids
		$userIds = array_chunk($userIds, 1000);

		// process each chunk one-by-one
		foreach ($userIds as $key=>$usersChunk) {
			error_log("IndexUsersWithChangedAnAPoints: Indexing for user chunk ".($key+1)." , total chunks = ".count($userIds));
			Modules::run("user/UserIndexer/indexMultipleUsers", $usersChunk,true);		
		}

		// send cron ending alert
		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Users Indexed : ".count($users)."<br/>Contact Person: Ankur Gupta";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);
	}

	/**
	 * Cron to index all those tags for which recently activities like follow,unfollow,tagging,untagging has happened in the last n number of days
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-09-30
	 * @param  integer    $days [no of days for which users activity need to be checked]
	 * @return [type]           [description]
	 */
	function indexTagsWithRecentActivities($days = 1){
		$this->validateCron();

		// increase memory limit
		ini_set("memory_limit", "1024M");

		// send starting alert
		$scriptStartTime = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		$this->load->model("apicronmodel");		
		$apicronmodel = new apicronmodel();

		// get tags to be indexed
		$tagsData        = $apicronmodel->getTagsWithRecentActivities($days);

		$tagsTobeIndexed = array_merge($tagsData['tagsFromFollowTable'], $tagsData['tagsFromContentMapTable']);
		$tagsTobeIndexed = array_filter($tagsTobeIndexed);
		$tagsTobeIndexed = array_unique($tagsTobeIndexed);

		error_log("IndexTagsWithRecentActivities: Total tags to be indexed : ".count($tagsTobeIndexed));

		// index the tags one-by-one
		foreach ($tagsTobeIndexed as $tagId) {
			Modules::run("search/Indexer/index", $tagId, 'tag', 'false');		
		}

		// send the successful ending alert
		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Tags Indexed : ".count($tagsTobeIndexed)."<br/>Contact Person: Ankur Gupta";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);
	}

	function sendCronAlert($subject, $body, $emailIds){

		if(empty($emailIds))
			$emailIds = $this->mailingList;

		foreach($emailIds as $key=>$emailId)
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $body, "html", '', 'n');
	}

	/**
	 * Cron to fetch top contributors for tags and also find most active tags for users
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-10-19
	 * @param  integer    $days [no of days for which users activity need to be checked]
	 * @return [type]           [description]
	 */
	function topContributorsAndMostActiveTagsCron($days = 1){
		$this->validateCron();

		// increase memory limit
		ini_set("memory_limit", "1024M");

		// send cron starting alert
		$scriptStartTime = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		$this->load->model("apicronmodel");		
		$apicronmodel = new apicronmodel();

		//$allowedTagEntitiesArray = array('Stream','Exams','Country','Careers');
		$allowedTagEntitiesArray = array('Stream');

		$eligibleTags = $apicronmodel->getTagsFilterByEntity($allowedTagEntitiesArray);

		$topContributors = array();
		$userActiveTags = array();
		foreach ($eligibleTags as $tagId) {
			$topContributors[$tagId] = $apicronmodel->getTagsTopContributors($tagId);

			foreach($topContributors[$tagId] as $userId){
				$userActiveTags[$userId][] = $tagId;
			}
		}

		$seconds = 60*60*24*7*$days;

		$predisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');

		// delete all previous keys
		$keysArray = array();
		$keysArray = $predisLib->fetchKeysBasedOnPattern('tagsTopContributors:*');
		if($keysArray)
			$predisLib->deleteKey($keysArray);
		$keysArray = array();
		$keysArray = $predisLib->fetchKeysBasedOnPattern('usersActiveTags:*');
		if($keysArray)
			$predisLib->deleteKey($keysArray);

		$predisLib->setPipeLine();
		foreach ($topContributors as $tagId=>$users) {
			if($users){
				$rediskey = 'tagsTopContributors:'.$tagId;
				$predisLib->addMembersToSet($rediskey, $users, TRUE);
				$predisLib->expireKey($rediskey, $seconds, TRUE);
			}
		}
		$predisLib->executePipeLine();

		$predisLib->setPipeLine();
		$notificationLib = $this->load->library('Notifications/NotificationContributionLib');
		foreach ($userActiveTags as $userId=>$tags) {
			if($tags){
				$dataForNotification = array();
				$rediskey = 'usersActiveTags:'.$userId;
				$tags 	  = array_slice($tags, 0, 10);
				$predisLib->addMembersToSet($rediskey, $tags, TRUE);
				$predisLib->expireKey($rediskey, $seconds, TRUE);
				$dataForNotification['userId'] = $userId;
				$dataForNotification['tags'] = $tags;
				$dataForNotification['type'] = MOST_ACTIVE_USER_ON_TAG;
				$notificationLib->addNotificationToRedis($dataForNotification);
			}
		}
		$predisLib->executePipeLine();

		// Compute Overall Most active users on AnA
		$rediskey = "mostActiveAnAUsers";
		$predisLib->setPipeLine();
		$mostActiveAnAUsers = $apicronmodel->getMostActiveAnAUsers();
		$predisLib->deleteKey(array($rediskey)); // delete the old cached data
		$predisLib->addMembersToSortedSet($rediskey, $mostActiveAnAUsers, TRUE);
		$predisLib->expireKey($rediskey, $seconds, TRUE);
		$predisLib->executePipeLine();
		
		// send cron ending alert
		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins <br/>Contact Person: Ankur Gupta";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);
	}

	function sendRecommedationsToInActiveUsers(){
		$this->validateCron();

		// increase memory limit
		ini_set("memory_limit", "1024M");

		// send cron starting alert
		$scriptStartTime = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		$this->load->model("apicronmodel");
		$predisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
		$apicronmodel = new apicronmodel();

		$inactiveUsers = $apicronmodel->getInactiveUsers();	
		$tagsmodel = $this->load->model("v1/tagsmodel");

		$this->load->library("common_api/APICommonCacheLib");
        $apiCommonCacheLib = new APICommonCacheLib();
        $highLevelTags = $apiCommonCacheLib->getHighLevelTags();

		$minThreadCount = 0;
		$daysInterval = array(5,10,15,20,25,30,45,60,90,120);

		$allNotifications = array();

		foreach ($daysInterval as $inactivityIndex=>$days) {

				$userIds = array();
				$usersLevelId = array();
				foreach($inactiveUsers as $userId=>$daysInactivity){
					if($daysInactivity == $days)
						$userIds[] = $userId;
				}

				// if(empty($userIds)){
				// 	continue;
				// }

				$usersLevelId = $apicronmodel->getAnAUsersLevelId($userIds);

				// fetch top 3 tags of the user
				$usersTags = array();
				foreach ($userIds as $userId) {
					// get top 20 tags which user follow
					$userFollowedTags   = $predisLib->getMembersInSortedSet('userFollowsTag:user:'.$userId,0,-1,true, false);
					if(empty($userFollowedTags)){
							$tagAffinityList  = $apicronmodel->getTagAffinityOfUser($userId);
							$tagAffinityList  = (array)json_decode($tagAffinityList, true);
							$tagAffinityList  = array_keys($tagAffinityList);	
							$userFollowedTags = $tagAffinityList;
					}

					// get matching high level tags
					$commonTags         = array_intersect((array)$userFollowedTags, (array)$highLevelTags);

					// remove those matching high level tags from user followed-tags
					$userFollowedTags   = array_diff((array)$userFollowedTags, (array)$commonTags);

					// get only 3 tags from follow list
					$usersTags[$userId] = array_slice($userFollowedTags, 0, 3);
				}

				// get tag for which recommendation is to be sent
				$usersTopTags = array();
				$tagIds = array();
				foreach ($usersTags as $userId => $tags) {
					if(!empty($tags)){
						$tagRotationIndex = ($inactivityIndex)%count($tags);
						// _p("tags : ".count($tags)." tagRotationIndex : ".$tagRotationIndex);

						$usersTopTags[$userId] = $tags[$tagRotationIndex];
						$tagIds[]              = $tags[$tagRotationIndex];
					}
				}
				$tagIds = array_unique($tagIds);

				$tagDetails = $tagsmodel->getTagsDetailsById($tagIds);

				$questionCount           = array();
				$discussionCount         = array();
				$unansweredQuestionCount = array();

				// get question count
				$questionCount           = $apicronmodel->getQuestionCountForTags($tagIds, $days, $minThreadCount);
				// get discussion count
				$discussionCount         = $apicronmodel->getDiscussionCountForTags($tagIds, $days, $minThreadCount);
				// get un-asnwered question count
				$unansweredQuestionCount = $apicronmodel->getUnAnsweredQuestionCountForTags($tagIds, $days, $minThreadCount);
				
				$userNotificationArray = array();
				foreach ($usersTopTags as $userId => $tagId) {

					$msg = array();
					if($questionCount[$tagId])
						$msg[] = $questionCount[$tagId]." new question".($questionCount[$tagId] > 1 ? 's' : '');

					if($discussionCount[$tagId])
						$msg[] = $discussionCount[$tagId]." new discussion".($discussionCount[$tagId] > 1 ? 's' : '');

					if($unansweredQuestionCount[$tagId] && $usersLevelId[$userId] >= 9)
						$msg[] = $unansweredQuestionCount[$tagId]." new unanswered question".($unansweredQuestionCount[$tagId] > 1 ? 's' : '');

					if($msg && $tagDetails[$tagId]){
						$userNotificationArray[$userId]['text']  = "You might want to check-out ".implode(", ", $msg)." on ".$tagDetails[$tagId]['tags'];
						$userNotificationArray[$userId]['topTag']  = $tagId;
						$userNotificationArray[$userId]['tagName']  = $tagDetails[$tagId]['tags'];
					}
				}

				
				$allNotifications[$days] = $userNotificationArray;

		}

		$this->load->library("v1/NotificationLib");
        $notificationLib = new NotificationLib();

        $totalCount = 0;
		foreach ($allNotifications as $day => $notifications) {
			
			foreach ($notifications as $userId => $value) {

					// check if user is eligible for the GCM notification or not
					if(!$notificationLib->isUserEligibleForGCMNotification($userId)){
						continue;
					}

					$data                     = array();
					$data['notificationType'] = TAG_DETAIL_PAGE_DEFAULT;
					$data['userId']           = $userId;
					$data['title']            = SHIKSHA_APP_NAME;
					$data['message']          = $value['text'];
					$data['primaryId']        = $value['topTag'];
					$data['primaryIdType']    = 'tag';
					$data['landing_page']     = APP_TAG_DETAIL_PAGE;

					$apicronmodel->insertGCMNotification($data);
					$totalCount++;
			}
		}
		
		// send cron ending alert
		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Total Notifications Sent : ".$totalCount."<br/>Contact Person: Ankur Gupta";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);
	}

	public function generateAppNotifications(){
		$this->validateCron();

		$this->load->model("apicronmodel");

		$cronName     = "APP_NOTIFICATION_CRON";
		$apicronmodel = new apicronmodel();
		$cronStatus   = $apicronmodel->getCronStatus($cronName);

		//  check if cron is already running or not
		if(!empty($cronStatus)){

			$attempts = $cronStatus['attempts'];

			if($attempts < MAX_APP_CRON_ATTEMPTS){
				$attempts++;
				$apicronmodel->updateCronAttempts($cronStatus['id'], $attempts);

				// cron already running
				$this->sendCronAlert(__METHOD__." CRON Already Running", __METHOD__." CRON Already Running. This cron will not run until previous instance ends.");
				return false;
			}
			else{
				// terminate previous instance entry
				$apicronmodel->updateCronStatus($cronStatus['id'], 'TERMINATED');
				// start cron
				$cronId = $apicronmodel->startCron($cronName, 'RUNNING');
			}
		}
		else{
			// make an entry for cron starting
			$cronId = $apicronmodel->startCron($cronName, 'RUNNING');
		}

		// 1. Contribution Based
		$this->generateContributionBasedNotifications();

		// 2. Acheivement Based
		$this->generateAchievementsNotifications();

		//3. Generate Most Active user on tag
		$this->generateMostActiveUserOnTagNotifications();

		// Cron Ends, update its status
		$apicronmodel->updateCronStatus($cronId, 'FINISHED');
	}


	public function generateMostActiveUserOnTagNotifications(){

		$this->load->library('Notifications/NotificationContributionLib');
		
		$notificationLib = new NotificationContributionLib();
		$notificationLib->generateMostActiveUserOnTagNotification();
		unset($notificationLib);
	}

	public function generateDailySummaryNotification(){
		$this->validateCron();

		$scriptStartTime = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		$this->load->library("v1/NotificationLib");
        $notificationLib = new NotificationLib();
        $totalCount = $notificationLib->dailyActivitySummaryCron();

        // send cron ending alert
		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Total Notifications Sent : ".$totalCount."<br/>Contact Person: Ankur Gupta";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);
	}

	public function sendPendingGCMNotifications(){
		$this->validateCron();

		// increase memory limit
		ini_set("memory_limit", "1024M");

		$this->load->model("apicronmodel");
		
		$cronName     = "GCM_NOTIFICATION_CRON";
		$apicronmodel = new apicronmodel();
		$cronStatus   = $apicronmodel->getCronStatus($cronName);

		//  check if cron is already running or not
		if(!empty($cronStatus)){

			$attempts = $cronStatus['attempts'];

			if($attempts < MAX_APP_CRON_ATTEMPTS){
				$attempts++;
				$apicronmodel->updateCronAttempts($cronStatus['id'], $attempts);

				// cron already running
				$this->sendCronAlert(__METHOD__." CRON Already Running", __METHOD__." CRON Already Running. This cron will not run until previous instance ends.");
				return false;
			}
			else{
				// terminate previous instance entry
				$apicronmodel->updateCronStatus($cronStatus['id'], 'TERMINATED');
				// start cron
				$cronId = $apicronmodel->startCron($cronName, 'RUNNING');
			}
		}
		else{
			// make an entry for cron starting
			$cronId = $apicronmodel->startCron($cronName, 'RUNNING');
		}

		$notificationinfomodel = $this->load->model("v1/notificationinfomodel");
        
		$notificationsPending = $apicronmodel->getPendingGCMNotifications();

		$this->load->library("common_api/GCMNotification");

		foreach ($notificationsPending as $notif) {

			$GCMNotification = new GCMNotification();

			$data 						= array();
			$data['notificationId']     = $notif['id'];
	        $data['notificationType']   = $notif['notificationType'];
	        $data['commandType']        = $notif['commandType'] ? $notif['commandType'] : 0;
	        $data['landingPage']        = $notif['landing_page'];
	        $data['userId']             = $notif['userId'];
	        $data['messageTitle']       = strip_tags(html_entity_decode($notif['title']));
	        $data['messageDescription'] = strip_tags(html_entity_decode($notif['message']));
	        $data['primaryId']          = $notif['primaryId'];
	        
	        if($notif['secondaryData'])
	        	$data['secondaryData']      = (array)json_decode($notif['secondaryData'], true);

	        if($notif['dynamicFieldList']){
	        	$data['dynamicFieldList']   = (array)json_decode($notif['dynamicFieldList'], true);
	        }

	        if($notif['notificationType'] == INAPP_NOTIFICATION){
				$data['collapse_key'] = "daily_summary";
				$data['TTL']          = 18000;
	        }
	        $data['iconUrl']            = $notif['iconUrl'];
	        $data['actions']            = array(); // pending
	        $data['trackingUrl']        = $notif['trackingUrl'];
	        
	        $gcmIds = $notificationinfomodel->getUserGCMIds($notif['userId']);

	        if(!empty($gcmIds)){
	        	// send GCM notification to user if GCM id(s) exists
	        	$response = $GCMNotification->sendNotification($gcmIds, $data);
	        }
	        else{
	        	// discard the GCM notification in case no GCM id exists
	        	$notificationinfomodel->updateGCMNotificationStatus($notif['id'], "discard");
	        }
			
		}

		// Cron Ends, update its status
		$apicronmodel->updateCronStatus($cronId, 'FINISHED');

		// insert api-tracking calls stored in Redis(appApiTracking) to DB(api_tracking)
		// $this->insertAPITracking();
		
	}

	private function generateContributionBasedNotifications(){

		$this->load->library('Notifications/NotificationContributionLib');

		// 1. Contribution Based - Any action on question/discussion(except report abuse) GCM+InApp
		$notificationLib = new NotificationContributionLib();
		$notificationLib->NotificationContributionGenerator();
		unset($notificationLib);

		// 2. User Follow notification (GCM+InApp)
		$notificationLib = new NotificationContributionLib();
		$notificationLib->UserFollowerNotificationGenerator();	
		unset($notificationLib);
	}

	private function generateAchievementsNotifications(){
		
		$this->load->library('Notifications/NotificationContributionLib');

		// 1. Level-up notification (GCM+InApp)
		$notificationLib = new NotificationContributionLib();
		$notificationLib->LevelUpNotificationGenerator();
		unset($notificationLib);
		
		// 2. Answer upvote acheivement notification (GCM+InApp)
		$notificationLib = new NotificationContributionLib();
		$notificationLib->UpvoteAchievementNotificationGenerator();	
		unset($notificationLib);

		// 3. User follower acheivement notification (GCM+InApp)
		$notificationLib = new NotificationContributionLib();
		$notificationLib->UserFollowerAchievementNotificationGenerator();	
		unset($notificationLib);

		// 4. Report Abuse Notification (GCM+InApp) 
		$notificationLib = new NotificationContributionLib();
		$notificationLib->ReportAbuseNotificationGenerator();	
		unset($notificationLib);

		// 5. Joining Bonus Notification (GCM+InApp) 
		$notificationLib = $this->load->library('Notifications/NotificationContributionLib');
		$notificationLib->JoiningBonusNotificationGenerator();	
		unset($notificationLib);
	}

	function reindexSpecialQuestions(){
		
		$this->load->model("apicronmodel");		
		$apicronmodel = new apicronmodel();

		$questionIds = $apicronmodel->getThreadWithGivenTagType("question", "objective_parent");
		foreach ($questionIds as $id) {
			modules::run('search/Indexer/index', $id, 'question', 'false');
		}
		
	}
	public function computeThreadQuality(){
			$this->validateCron();
                	$personalizationLibrary = $this->load->library('common/personalization/PersonalizationLibrary');
                        $personalizationLibrary->computeThreadQuality();
                        echo ' Call Complete : '.date('Y-m-d H:i:s');
        }
	
	function insertAPITrackingData(){
		$this->validateCron();
		$this->predisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
		$data = $this->predisLib->getMembersOfSet("appApiTracking");
		$this->predisLib->deleteKey(array("appApiTracking"));

		$this->apicommonmodel = $this->load->model("common_api/apicommonmodel");

		foreach ($data as $key=>$value) {

			$trackingData = json_decode($value, true);
			$this->apicommonmodel->trackAPI($trackingData);	

			// update user's last activity time
			if($trackingData['userId']){
				$this->apicommonmodel->updateUserLastActivityTime($trackingData['userId'], $trackingData['creationDate']);
			}
		}

	}
	
	/*
	 * @author	: Abhinav
	 * @description	: This is one time script for adding tagIds into redis on key highLevelTagsForPersonalization
	 * 					Tags details will be added into personlization config which are to be added into highLevelTagsForPersonalization key
	 */
	public function prepareHighLevelTagsData(){
		$this->config->load('personalizationConfig');
		$highLevelTags = $this->config->item('highLevelTags');
		$this->load->model('common/personalizationmodel');
		$tagIds = array();
		foreach ($highLevelTags as $key=>$value){
			switch($key){
				case 'tags'			:	$data = $this->personalizationmodel->getTagIds($value);
										break;
				case 'tag_entity'	:	$data = $this->personalizationmodel->getTagIds(array(),$value);
										break;
				default:break;
			}
			if(!empty($data)){
				$tagIds = array_merge($tagIds, $data);
			}
		}
		$predisLibrary = PredisLibrary::getInstance();
		$predisLibrary->deleteKey(array('highLevelTagsForPersonalization'));
		$predisLibrary->addMembersToSet('highLevelTagsForPersonalization', $tagIds);
		echo 'DONE';
		exit(0);
	}


	function testScript(){
		ini_set('memory_limit', '1024M');
		$model = $this->load->model('apicronmodel');

		error_log("Fetching instituteList");
		_p("Fetching instituteList");
		$instituteList = $model->fetchInstituteList();
		//_p($instituteList);
		
		error_log("Fetching TagsList");
		_p("Fetching TagsList");
		$tagsList = $model->fetchTagsList();
		//_p($tagsList);

		$matchedArray = array();	
		$unMatchedArray = array();
		$dbUpdateArray = array();


		$counter = 0;
		foreach ($instituteList as $key => $value) {

			$value['listing_title'] =  trim(strtolower($value['listing_title']));
			if(array_key_exists($value['listing_title'], $tagsList)){
				$matchedArray[] = $value['listing_type_id']."-----".$tagsList[$value['listing_title']];


				$dbUpdateArray[$counter]['tag_id'] = $tagsList[$value['listing_title']];
				$dbUpdateArray[$counter]['listing_id'] = $value['listing_type_id'];
				$dbUpdateArray[$counter]['status'] = 'live';
				$dbUpdateArray[$counter]['listing_type'] = 'institute';

			} else {
				$unMatchedArray[] = $value['listing_type_id'];
			}

			$counter++;
		}

		
		$model->createTagsListingMapping($dbUpdateArray);
		error_log("matched Array");
		_p("matched Array");
		_p($matchedArray);

		error_log("unmatched Array");
		_p("unmatched Array");
		_p($unMatchedArray);

	}
	
	/**
	 * This cron is responsible for preparing home feed for users for whom data is there in redis but sorting is not performed.
	 * This is being done because we don't want to keep unnecessary data in redis.
	 * @author	Abhinav
	 */
	public function prepareHomeFeedForUsers(){
		$this->validateCron();

		$scriptStartTime	= time();
		$predisLibrary		= PredisLibrary::getInstance();
		$redisMemoryInfo	= $predisLibrary->infoRedis('memory');
		$text				=	"<br/>Redis Memory Stat Before Starting Sorting For Home Feed ";
		$text				.=	"<br/> ".json_encode($redisMemoryInfo);
		$this->sendCronAlert("Started : ".__METHOD__, $text);
		$text				= "";

		ini_set('max_execution_time', -1);
		ini_set('memory_limit', -1);
		
		$userNewHomeFeedKeys	= $predisLibrary->fetchKeysBasedOnPattern('userNewHomeFeed:user:*');
		$userBackFillKeys		= $predisLibrary->fetchKeysBasedOnPattern('userBackFillHomeFeed:user:*');
		$userIdsForHomeFeed		= array();
		$visitorIdsForHomeFeed	= array();
		foreach($userNewHomeFeedKeys as $value){
			$data = explode(':', $value);
			if(ctype_digit($data[2]) && !in_array($data[2], $userIdsForHomeFeed)){
				$userIdsForHomeFeed[] = $data[2];
			}elseif(ctype_alnum($data[2]) && !in_array($data[2], $visitorIdsForHomeFeed)) {
				$visitorIdsForHomeFeed[] = $data[2];
			}
		}
		foreach ($userBackFillKeys as $value){
			$data = explode(':', $value);
			if(!in_array($data[2], $userIdsForHomeFeed)){
				$userIdsForHomeFeed[] = $data[2];
			}
		}
		
		// This piece of code is for testing to check if its working fine.
		// Otherwise we have to run this cron after commenting below array_slice code.
		//$userIdsForHomeFeed = array_slice($userIdsForHomeFeed, 0, 10);
		
		$this->load->library('common/personalization/PersonalizationLibrary');
		foreach($userIdsForHomeFeed as $value){
			$this->personalizationlibrary->setUserId($value); // assigning userId for actual registered users
			$this->personalizationlibrary->setVisitorId(''); // And then assigning visitorId blank as this is actual registered users
			$this->personalizationlibrary->getHomeFeedData(0, 'home', 10, TRUE);
		}
		foreach ($visitorIdsForHomeFeed as $value){
			$this->personalizationlibrary->setUserId(0); // assigning userId 0 in case of visitors
			$this->personalizationlibrary->setVisitorId($value); // And then assigning actual visitorId
			$this->personalizationlibrary->getHomeFeedData(0, 'home', 10, TRUE);
		}
		$redisMemoryInfo	= $predisLibrary->infoRedis('memory');
		error_log('<br/> Home Feed Sorted and Prepared for '.count($userIdsForHomeFeed).' users');
		error_log('<br/> Sorting done for users : '.print_r($userIdsForHomeFeed, TRUE));
		error_log('<br/> DONE');
		
		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Home Feed Sorting Done : ".count($userIdsForHomeFeed)." users."
						." <br/>Contact Person: Abhinav Pandey"
						." <br/>Redis Memory Stat After Ending Sorting For Home Feed :"
						." <br/>".json_encode($redisMemoryInfo);
		$this->sendCronAlert("Ended : ".__METHOD__, $text);

	}
    
    /**
     * @author Abhinav Pandey
     */
    public function cleanDataForInactiveUsers(){
        ini_set('max_execution_time', -1);
        ini_set("memory_limit","33012M");

	$this->validateCron();

    	// this return call is placed as this piece of code will go live in next sprint of SPRINT_10MAY_23MAY
    	//return;
    	$scriptStartTime	= time();
    	$predisLibrary		= PredisLibrary::getInstance();
    	$redisMemoryInfo	= $predisLibrary->infoRedis('memory');
    	$text				= "<br/>Redis Memory Stat Before Starting Clean Data For In-Active Users ";
    	$text				.= "<br/> ".json_encode($redisMemoryInfo);
    	$this->sendCronAlert("Started : ".__METHOD__, $text);
    	$text = "";
        $this->load->library("common/personalization/PersonalizationLibrary");
        $response = $this->personalizationlibrary->cleanPersonalizationDataForInActiveUsers();
        
        $redisMemoryInfo	= $predisLibrary->infoRedis('memory');
        $scriptEndTime = time();
        $timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
        $text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>In-Active Users/Visitors Data Cleaning Done. "
        							." <br/> ".$response['usersCount']." users data cleaned"
        							." <br/> ".$response['visitorsCount']." visitors data cleaned"
        							." <br/>Contact Person: Abhinav Pandey"
        							." <br/>Redis Memory Stat After Ending Sorting For Home Feed :"
        							." <br/>".json_encode($redisMemoryInfo);
        $this->sendCronAlert("Ended : ".__METHOD__, $text);
        echo 'DONE';
    }
    
    /**
     * This cron is responsible for updating view count of threads based on data in Redis.
     */
    public function updateViewCountOfThreads(){
	$this->validateCron();
    	$this->load->library("common_api/APICommonLib");
    	$this->apicommonlib->updateViewCountOfThreads();
    	echo 'SUCCESS';
    }

    public function doStreamQuesTagsMapping($cronType='daily'){
	$this->validateCron();

    	$this->load->model("apicronmodel");
    	$redisLib = PredisLibrary::getInstance();
    	$this->load->model('Tagging/taggingmodel');

    	$this->load->model('Tagging/taggingcmsmodel');

    	$threadIdCompleteData = array();

    	if($cronType == 'daily'){
    		$threadIdCompleteData = $redisLib->getAllMembersOfHashWithValue('questionPostedLastNMins');
    	}else{
    		$threadIdCompleteData = $this->apicronmodel->fetchThreadIds();	
    	}
    	
		if(empty($threadIdCompleteData)) return;
		$COUNT = 1000;
		$loopCount = count($threadIdCompleteData);
		
		// Fetch All Stream Tags
		$streamTags = $this->taggingcmsmodel->getTagsArray('Stream');
		
		$streamTagsId = array();
		foreach ($streamTags as $key => $value) {
			$streamTagsId[] = $value['id'];
		}

		for($i=0;$i<$loopCount;$i+=$COUNT){
			error_log("** STARTED $i **********");
			$threadIdInitialData = array_slice($threadIdCompleteData, $i, $COUNT,true);
			$this->mapQuesToStreams($threadIdInitialData, $streamTagsId);
			error_log("** ENDED $i **********");
		}

		$redisLib->deleteMembersOfHash('questionPostedLastNMins',array_keys($threadIdCompleteData));
    }

    public function mapQuesToStreams($threadIdInitialData,$streamTagsId){

    	$threadIdPostArray = array();
  		$threadIdEditArray = array();
  	
  		foreach ($threadIdInitialData as $key => $value) {
  			if($value == 'post'){
  				$threadIdPostArray[] = $key;
  			}else{
  				$threadIdEditArray[] = $key;
  			}
  		}
  		if(!empty($threadIdPostArray)){
  			$this->mapQuesToStreamsInternal($threadIdPostArray,$streamTagsId,'post');
  		}
  		if(!empty($threadIdEditArray)){
  			$this->mapQuesToStreamsInternal($threadIdEditArray,$streamTagsId,'edit');
  		}
  	}

  	public function mapQuesToStreamsInternal($dataArray,$streamTagsId,$action){

  		$tagsData = array();
		$tagsList = "";
		$dataToInsert = array();
		$dataToRemove = array();
  		$threadIdsData = $this->apicronmodel->fetchTagsData($dataArray);

		foreach ($threadIdsData as $key => $value) {
			
			$tagsList .= $value['tagsIds'].",";
			$threadIdsData[$key]['tagsArray'] = explode(",",$value['tagsIds']);
			$newKey = $value['content_id'];
			$threadIdsData[$newKey]  = $threadIdsData[$key];
			unset($threadIdsData[$key]);
		}
		
		foreach ($dataArray as $key => $value) {
			if(!array_key_exists($value, $threadIdsData)){
				$threadIdsData[$value] = array();
				$threadIdsData[$value]['tagsArray'] = array();
				$threadIdsData[$value]['content_id'] = $value;
			}
		}

		$tagsListArray = array_values(array_unique(array_filter(explode(",", $tagsList))));
		$tagsListFinalArray = array();
		
		// Fetch two level parent of All Tags
		$finalResult = array();

		if(!empty($tagsListArray)){
			$finalResult = $this->taggingmodel->findAllTwoLevelParentTags($tagsListArray);	
		}
	
		$tagsParentMapping = array();
		foreach ($finalResult as $key => $value) {
			if(!empty($value['levelOneParent'])){
				$tagsParentMapping[$value['mainTag']][] = $value['levelOneParent'];
			}
			if(!empty($value['levelTwoParent'])){
				$tagsParentMapping[$value['mainTag']][] = $value['levelTwoParent'];
			}
		}

		// Populate Parent Tags for Threads
		foreach ($threadIdsData as $key=>$threadData) {
			$previousTags = $threadData['tagsArray'];
			foreach ($previousTags as $value) {
				$parentTags = $tagsParentMapping[$value];
				foreach ($parentTags as $indiTag) {
					array_push($threadIdsData[$key]['tagsArray'],$indiTag);	
				}
			}
			$threadIdsData[$key]['tagsArray'] = array_values(array_filter(array_unique($threadIdsData[$key]['tagsArray'])));
			
			$streamTagsForThread = array_values(array_intersect($threadIdsData[$key]['tagsArray'], $streamTagsId));
			if(!empty($streamTagsForThread)){
					foreach ($streamTagsForThread as $streamTagsIndividual) {
						$tempArray = array();
						$tempArray['threadId'] = $threadData['content_id'];
						if($action == "edit"){
							$dataToRemove[] = $threadData['content_id'];	
						}
						$tempArray['tagId'] = $streamTagsIndividual;
						$dataToInsert[] = $tempArray;
					}
			}else {
					$tempArray = array();
					$tempArray['threadId'] = $threadData['content_id'];
					$tempArray['tagId'] = -1;
					$dataToInsert[] = $tempArray;
					if($action == "edit"){
						$dataToRemove[] = $threadData['content_id'];	
					}
			}
		}
		unset($threadIdsData);
		if($action == "edit"){
			$this->apicronmodel->deleteMessageStreamMapping($dataToRemove);
		}
		$this->apicronmodel->insertMessageStreamMapping($dataToInsert);
  	}

}
?>


