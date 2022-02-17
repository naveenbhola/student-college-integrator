<?php
/**
 * Notification Lib Class
 * This is the class for Library functions for Notifications
 * @date    2015-10-27
 * @author  Ankit Bansal
 * @todo    none
*/

class NotificationContributionLib
{
    private $CI;
    private $validationLibObj;
    private $suffixDetails = "_DETAILS";
    private $suffixFunction = "_FUNCTIONS";
    private $appName = "";
    private $globalCountArray;
    private $individualCountArray;


    private $notificationModel;

    private $functionMapping;


    function __construct() {

        $this->CI = &get_instance();
        $this->CI->load->config('NotificationConfig');
        $this->notificationModel = $this->CI->load->model('Notifications/NotificationModel');
        $this->redisLib = PredisLibrary::getInstance();//$this->CI->load->library('common/PredisLibrary');    
        $this->universalApiLib = $this->CI->load->library('v1/NotificationLib');
        $this->apicronmodel = $this->CI->load->model('common_api/apicronmodel');
        $this->appName = SHIKSHA_APP_NAME;
    }


    /**
    * Function is used to generate the Notifications for Actions on Question / Answer(Except from Report Abuse)
    *
    */
    public function NotificationContributionGenerator(){
            
            // Fetch all the stored Notifications from Redis
            $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:contribution:inapp');

            // This is to maintain Users for all for whom notifications are being created, so as to update notification count
            $globalCountArray = array(); 

            // This is to maintain all the keys fetched from Redis inorder to delete them after processing
            $keysForDeletion = array();
            
            foreach ($result as $key => $value) {
                $value = (array)json_decode($value);

                /* This is the variable, used to maintain the users for whom we are sending the notification in One key
                 For Eg. New comment on Question, notification Goes to comment Onwers and discussion owner.
                 Suppose same user asked the discussion and also commented, so for 1 user and 1 actions , 2 notification will be generated
                 To Prevent this, we have kept track of all the user creating notifications and not send them more than once
                */
                $this->individualCountArray = array();

                // Pushing the current key into deletion lists
                array_push($keysForDeletion, $key);

                // Fetching all the data from Redisy
                $type = $value['type'];
                $threadId = $value['threadId'];
                $threadType = $value['threadType'];
                $actioner = $value['actioner'];
                $action_item = $value['action_item'];
                $action_item_id = $value['action_item_id'];
                $action_item_2 = $value['action_item_2'];
                $action_item_id_2 = $value['action_item_id_2'];

                // Start Procerssing
                $this->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2);

            }

            // Delete the keys for which the notifications Have been created
            if(!empty($keysForDeletion)){
                $this->redisLib->deleteMembersOfHash('notificationRedisData:contribution:inapp',$keysForDeletion);    
            }

            // Update the notifications Count for all the users for whom notifications have been created
            if(!empty($this->globalCountArray)){

                // Fetching all the unread notification for the user
                $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

                // set the unread count to redis key
                foreach ($cnt as $key => $value) {
                    $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
                }
                $this->globalCountArray = array();
            }
            
    }

    /**
    * Function to detect all the Information about the notification key fetched from the redis in NotificationContributionGenerator() function
    * Complete Information from NotificationConfig file and then information like recipients etc.
    *
    * @param $type string Type Of Notification(like NEW_ANSWER_ON_QUESTION etc.) == Used to fetch information from Config File
    * @param $threadId string ThreadId 
    * @param $threadType string question/discussion
    * @param $actioner string UserId of the loggedIn Person who did the action which triggerd the Notification
    * @param $action_item string The entity on which actual action is Performed(like answer in case of Upvote on answer)[Called from Config]
    * @param $action_item_id integer The entity on which actual action is Performed(like answer_id(msgId) in case of Upvote on answer)[Called from Config]
    * @param $action_item_2 string NOT USED (Concept same as action_item but not used)
    * @param $action_item_id_2 string NOT USED (Concept same as action_item but not used)
    */
    public function notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item='',$action_item_id=0,$action_item_2='',$action_item_id_2='') {

        // Creating keys to fetch the information from the Config file
        $detailKey = strtoupper($type).$this->suffixDetails;
        $fnKey = strtoupper($type).$this->suffixFunction;
        
        // Fetching the information from the keys created
        $dataForAction = $this->CI->config->item($detailKey);
        $functionMapping = $this->CI->config->item($fnKey);

        // Custom data for passsing to the functions that are called(custom as function name is variable)
        $customDataArrayForFunctions = array();
        $customDataArrayForFunctions['userId'] = $actioner;
        $customDataArrayForFunctions['threadId'] = $threadId;
        $customDataArrayForFunctions['threadType'] = $threadType;

        $dataForNotification = array();

        // Fetch the parameters needed to create the Notification Message
        // Function(s) name is fetched from Config File()
        foreach ($functionMapping as $fnName => $fields) {  

            $info = $this->notificationModel->$fnName(/*$threadId,$threadType,*/$customDataArrayForFunctions);
            foreach ($fields as $value) {
                $dataForNotification[] = $info[$value];
            }
        }

        /*
        DataForNotification - Array - Main Thread ID, Main Thread Title / Text with $2
        Array
        (
            [0] => 2700970
            [1] => fees for executive MBA
            [2] => Name of Follower(In case of Question/Discussion)
        )
        */

        // Fetch all the possisble actors on Question / Discussion to whom the notification could be sent
        if($threadType == 'question'){
            $listOfPossibleActors = $this->CI->config->item('POSSIBLE_ACTORS_ON_QUESTION');
        } else if($threadType == 'discussion'){
            $listOfPossibleActors = $this->CI->config->item('POSSIBLE_ACTORS_ON_DISCUSSION');
        }
        
        // For all the fetched possible actors, iterate over the current key for actors and starts procrssing for them
        $dataForDB = array();
        foreach ($listOfPossibleActors as $actors) {

            if(array_key_exists($actors, $dataForAction)) {

                // For Storing in DB, for identifying uniquely the Action with Whom the notification is sent
                $actionKey = strtoupper($type."$$".$actors); 
                $entityId = $$dataForAction[$actors]['entity_on_action'];                
                $entityUserOwenrId = $this->notificationModel->fetchEntityOwner($entityId);                
                $threadOwnerUserId = $dataForNotification[0];


                if($dataForAction[$actors]['isMultipleRecipient'] === false){
                    // Case for Single Receipent
                    if($actioner ==  -1 || $threadOwnerUserId != $actioner ) {                        
                        // Check for inApp Notification Availibity
                        if($this->universalApiLib->isUserEligibleForInAppNotification($threadOwnerUserId)) {

                            $this->globalCountArray[] = $threadOwnerUserId;
                            $this->individualCountArray[] = $threadOwnerUserId;

                            // Actaul Notification Preparation
                            $this->_generateNotification($entityId,$actionKey,$threadId,$threadOwnerUserId,$actors,$dataForNotification,$dataForAction,$threadType,$action_item,$action_item_id,$actioner);   
                        }
                        
                    }
                    
                } else {
                    // case for multiple recipient                    

                    $userIdFetchFunction = $dataForAction[$actors]['recipientFunction'];
                    $listUserIds = $this->notificationModel->$userIdFetchFunction($threadId,$threadType,$action_item_id);

                    foreach ($listUserIds as $key => $value) {
     
                        // Same User Check
                        if($value['recipient'] != $actioner && !in_array($value['recipient'], $this->individualCountArray)) {
                        
                            if($this->universalApiLib->isUserEligibleForInAppNotification($value['recipient'])) {

                                $this->globalCountArray[] = $value['recipient'];
                                $this->individualCountArray[] = $value['recipient'];                                                   
                                $this->_generateNotification($entityId,$actionKey,$threadId,$value['recipient'],$actors,$dataForNotification,$dataForAction,$threadType,$action_item,$action_item_id);    
                            }                             
                        }
                        
                    }

                }                
                
            } // if of array_key_exists closed
                
        } // foreach end

    

    }


    // Core Logic for generating Notifications(parameters definition same as of above function)
    private function _generateNotification($entityId,$actionKey,$threadId,$userId,$actors,$result,$dataForAction,$threadType,$action_item,$action_item_id,$actioner=0){

            $notificationInfo = $this->notificationModel->fetchNotifificationInfo($actionKey,$entityId,$userId,'unread',$dataForAction[$actors]['entity_on_action']);

            // check whether to send GCM notification or not
            $sendGCMNotificationFlag = false;
            if($dataForAction[$actors]['gcm'] == true && $this->universalApiLib->isUserEligibleForGCMNotification($userId) )
                $sendGCMNotificationFlag = true;
            if($dataForAction[$actors]['apply_limit']){
                if($this->universalApiLib->isGCMNotificationCountExceedLimit($userId , $dataForAction[$actors]['apply_day_limit'] ,$actionKey, $threadId, $action_item_id))
                    $sendGCMNotificationFlag = false;
            }

            if($sendGCMNotificationFlag){

                // Case for adding new GCM notification
                $secondaryData    = array();
                if($action_item   != "" && $action_item != null){
                    $secondaryData[]  = $action_item_id;
                }

                $details['notificationType'] = $dataForAction[$actors]['notificationType'];
                $details['userId']           = $userId;
                $details['title']            = str_replace("<App>", $this->appName, $dataForAction[$actors]['title']);
                $details['message']          = $dataForAction[$actors]['message'];
                $details['message']          = $this->_getNotificationMessage($details['message'], $result);
                $details['primaryId']        = $threadId;
                $details['primaryIdType']    = $threadType;
                $details['secondaryData']    = $secondaryData;
                $details['secondaryDataId']  = $action_item_id;
                $details['landing_page']     = $dataForAction[$actors]['landing_page'];
                $details['trackingUrl']      = $dataForAction[$actors]['trackingURL'];

                $this->apicronmodel->insertGCMNotification($details);
            }

            if(empty($notificationInfo)){
                _p("Case for adding new notification");
                // Case for adding new notification

                $message          = $dataForAction[$actors]['message'];
                $message          = $this->_getNotificationMessage($message, $result);
                $title            = str_replace("<App>", $this->appName, $dataForAction[$actors]['title']);                                    
                $landing_page     = $dataForAction[$actors]['landing_page'];
                $notificationType = $dataForAction[$actors]['notificationType'];
                $commandType      = $dataForAction[$actors]['commandType'];
                $trackingURL      = $dataForAction[$actors]['trackingURL'];
                $secondaryData    = array();

                if($action_item   != "" && $action_item != null){
                    $secondaryData[]  = $action_item_id;
                }
               
               $this->notificationModel->insertDataInNotificationInAppQueue($userId,$actionKey,$title,  $message,date('Y-m-d H:i:s'),'unread',$threadId,$threadType,$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,$action_item_id);
            } else{
                _p("Case for grouping the notifications");
                // Case for grouping the notifications
                $prev_cnt = 1;
                // Searching for pattern like #1#
                if(preg_match("(([#]{1})([0-9])([#]{1}))", $notificationInfo['message'],$matches) === 1){
                    $prev_cnt = intval($matches[2]);
                } 
                $new_cnt = $prev_cnt + 1;
                $message = $dataForAction[$actors]['summation_messgae'];
                $message = str_replace("<App>", $this->appName, $message);
                foreach ($result as $key => $value) {
                    $cnt = $key + 1;                        
                    if(strpos($message, "$".$cnt) !== false){
                        $message = str_replace("$".$cnt, $result[$key], $message);
                    }
               }

               $message = str_replace("#count#", "#".$new_cnt."#", $message);
               $this->notificationModel->updateDataInNotificationInAppQueue($userId,$actionKey,$message,date('Y-m-d H:i:s'),'unread',$threadId,$threadType,$action_item,$action_item_id);
                
            } // else end
    }
    
    function addNotificationForAppInRedis($type,$threadId,$threadType,$actioner=-1,$action_item='',$action_item_id=0,$action_item_2='',$action_item_id_2=0){
        
        $redisData=array(
            'type'=>$type,
            'threadId'=>$threadId,
            'threadType'=>$threadType,
            'actioner'=>$actioner,
            'action_item'=>$action_item,
            'action_item_id'=>$action_item_id,
            'action_item_2'=>$action_item_2,
            'action_item_id_2'=>$action_item_id_2
            
        );
        
        $notificationRedisData = json_encode($redisData);
        $key = $type."__".$actioner."__".time();
        $this->redisLib->addMembersToHash("notificationRedisData:contribution:inapp",array($key=>$notificationRedisData));
        $this->redisLib->expireKey("notificationRedisData:contribution:inapp",24*60*60);
        
    }

    public function addLevelUpNotificationsToRedis($new_level_id,$new_level_name,$userId){

        $type = LEVEL_UP;
        $details = $this->CI->config->item(LEVEL_UP_DETAILS);
        $redisData = array(
                'type' => $type,
                'new_level_name' => $new_level_name,
                'new_level_id' => $new_level_id,
                'userId' => $userId
            );

        $notificationRedisData = json_encode($redisData);
        $key = $type."__".$userId."__".time();        

        if($details['gcm']){
            $this->redisLib->addMembersToHash("notificationRedisData:level:gcm",array($key=>$notificationRedisData));    
        }
        

        $this->redisLib->addMembersToHash("notificationRedisData:level:inapp",array($key=>$notificationRedisData));
        $this->redisLib->expireKey("notificationRedisData:level:inapp",24*60*60);
        
    }

    public function addJoiningBonusNotificationsToRedis($points,$level,$userId){

        $type = JOINING_BONUS;
        $details = $this->CI->config->item(JOINING_BONUS_DETAILS);
        $redisData = array(
                'type' => $type,
                'points' => $points,
                'level' => $level,
                'userId' => $userId
            );

        $notificationRedisData = json_encode($redisData);
        $key = $type."__".$userId."__".time();        

          if($details['gcm']){
            $this->redisLib->addMembersToHash("notificationRedisData:joinbonus:gcm",array($key=>$notificationRedisData));    
        }
        

        $this->redisLib->addMembersToHash("notificationRedisData:joinbonus:inapp",array($key=>$notificationRedisData));
        
    }

    public function addFBFriendNotificationsToRedis($userId){

        $type = FACEBOOK_FRIEND_JOINED;
        $details = $this->CI->config->item(FACEBOOK_FRIEND_JOINED_DETAILS);
        $redisData = array(
                'type' => $type,
                'userId' => $userId
            );

        $notificationRedisData = json_encode($redisData);

        $key = $type."__".$userId."__".time();        

        $this->redisLib->addMembersToHash("notificationRedisData:fbfriend:inapp",array($key=>$notificationRedisData));
        $this->redisLib->expireKey("notificationRedisData:fbfriend:inapp",24*60*60);
        
    }

    public function addAnswerUpvotesNotificationToRedis($noOfUpvotes,$newmsgId){
        $type = ACHIEVEMENT_UPVOTE_ANSWER;
        $details = $this->CI->config->item(ACHIEVEMENT_UPVOTE_ANSWER_DETAILS);
        $questionData = $this->notificationModel->fetchQuestionDetailsForAnswerId($newmsgId);

        $title = $questionData['msgTxt'];
        $threadId = $questionData['msgId'];
        $threadType = 'question';
        $userId = $questionData['userId'];

        $redisData = array(
                'type' => $type,
                'title' => $title,
                'threadId' => $threadId,
                'threadType' => $threadType,
                'upvotes' => $noOfUpvotes,
                'userId' => $userId,
                'answerId' => $newmsgId
            );

        $notificationRedisData = json_encode($redisData);
        $key = $type."__".$userId."__".time();        

        if($details['gcm']){
            $this->redisLib->addMembersToHash("notificationRedisData:acheievement_upvote:gcm",array($key=>$notificationRedisData)); 
        }              
        $this->redisLib->addMembersToHash("notificationRedisData:acheievement_upvote:inapp",array($key=>$notificationRedisData));

    }

     public function addAchievementUserFollowNotificationToRedis($noOfFollowers,$newmsgId){
        $type = ACHIEVEMENT_USER_FOLLOW;
        $details = $this->CI->config->item(ACHIEVEMENT_USER_FOLLOW_DETAILS);
        

        $redisData = array(
                'type' => $type,
                'noOfFollowers' => $noOfFollowers,
                'userId' => $newmsgId,
            );

        $notificationRedisData = json_encode($redisData);
        $key = $type."__".$newmsgId."__".time();        

        if($details['gcm']){
            $this->redisLib->addMembersToHash("notificationRedisData:acheievement_user_follow:gcm",array($key=>$notificationRedisData)); 
        }              
        $this->redisLib->addMembersToHash("notificationRedisData:acheievement_user_follow:inapp",array($key=>$notificationRedisData));

    }

    public function addUserFollowNotificationToRedis($followeeId, $followerId){
        $type = USER_FOLLOW;
        $details = $this->CI->config->item(USER_FOLLOW_DETAILS);

        $redisData = array(
                'type' => $type,
                'followeeId' => $followeeId,
                'followerId' => $followerId,
            );

        $notificationRedisData = json_encode($redisData);
        $key = $type."__".$userId."__".$followerId."__".time();        

        $this->redisLib->addMembersToHash("notificationRedisData:user_follow:inapp",array($key=>$notificationRedisData));

    }

    public function addReportAbuseNotificationToRedis($entityId,$entityType,$threadId,$threadType,$threadTitle,$ownerId,$reportAbuserArray,$status,$entityStatus,$entityTitle,$reportAbuseDateArray){
        $type = REPORT_ABUSE;
        $details = $this->CI->config->item(REPORT_ABUSE_DETAILS);
        $this->CI->load->helper('messageBoard/ana');

        $redisData = array(
                'type' => $type,
                'entityId' => $entityId,
                'entityType' => strtolower($entityType),
                'threadId' => $threadId,
                'threadType' => $threadType,
                'threadTitle' => sanitizeAnAMessageText($threadTitle,$entityType),
                'ownerId' => $ownerId,
                'reportAbuserArray' => json_encode($reportAbuserArray),
                'status' => $status,
                'entityStatus' => $entityStatus,
                'reportAbuseDateArray' => json_encode($reportAbuseDateArray),
                'entityTitle' => sanitizeAnAMessageText($entityTitle,$entityType)
            );

        $notificationRedisData = json_encode($redisData);
        $key = $type."__".$status."__".$reportAbuseDateArray[0]."__".time();        
           
        $this->redisLib->addMembersToHash("notificationRedisData:report_abuse:inapp",array($key=>$notificationRedisData));
    }

    public function addAutoDeleteNotificationToRedis($entityType,$entityTitle,$threadType,$reportAbuserArray,$reportAbuseDateArray,$ownerId,$threadTitle=''){
        $type = REPORT_ABUSE;
        $details = $this->CI->config->item(REPORT_ABUSE_DETAILS);
        $this->CI->load->helper('messageBoard/ana');

        $redisData = array(
                'type' => $type,
                'entityId' => 0,
                'entityType' => strtolower($entityType),
                'threadId' => 0,
                'threadType' => $threadType,
                'threadTitle' => sanitizeAnAMessageText($threadTitle,$entityType),
                'ownerId' => $ownerId,
                'reportAbuserArray' => json_encode($reportAbuserArray),
                'status' => 'auto_delete',
                'entityStatus' => 'abused',
                'reportAbuseDateArray' => json_encode($reportAbuseDateArray),
                'entityTitle' => sanitizeAnAMessageText($entityTitle,$entityType)
            );

        $notificationRedisData = json_encode($redisData);
        $key = $type."__".$status."__".$reportAbuseDateArray[0]."__".time();        
           
        $this->redisLib->addMembersToHash("notificationRedisData:report_abuse:inapp",array($key=>$notificationRedisData));
    }

    public function ReportAbuseNotificationGenerator(){
        $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:report_abuse:inapp');
        $keysForDeletion = array();

        $details = $this->CI->config->item(REPORT_ABUSE_DETAILS);

        foreach ($result as $key => $value) {
            $value = (array)json_decode($value);
            array_push($keysForDeletion, $key);
            $type = $value['type'];
            $entityId = $value['entityId'];
            $entityType = $value['entityType'];
            $threadId = $value['threadId'];
            $threadTitle = $value['threadTitle'];
            $ownerId = $value['ownerId'];
            $reportAbuserArray = (array)json_decode($value['reportAbuserArray']);
            $reportAbuseDateArray = (array)json_decode($value['reportAbuseDateArray']);
            $status = $value['status'];
            $entityStatus = $value['entityStatus'];
            $message = "";


            foreach ($details as $actor => $actorsDetails) {
                if($actor == 'content_owner'){
                    if($status != "Rejectwp" && $status != "Rejectwop"){

                        if($this->universalApiLib->isUserEligibleForInAppNotification($ownerId)){
                            $this->_prepareNotificationForReportAbuseContentOwner($value,$actorsDetails);
                            $this->globalCountArray[] = $ownerId;    
                        }
                        
                    }                    
                } 
                else if($actor == 'request_initiater'){
                    foreach ($reportAbuserArray as $key=>$reportAbuser) {
                        if($this->universalApiLib->isUserEligibleForInAppNotification($reportAbuser)){
                            $value['reportAbuser'] = $reportAbuser;
                            $value['reportAbuseDate'] = $reportAbuseDateArray[$key];
                            $this->_prepareNotificationForReportAbuser($value,$actorsDetails);
                            $this->globalCountArray[] = $reportAbuser;    
                        }                        
                    }
                    
                }
            }
             
        }

            if(!empty($keysForDeletion)) {
                $this->redisLib->deleteMembersOfHash('notificationRedisData:report_abuse:inapp',$keysForDeletion);    
                $this->redisLib->deleteMembersOfHash('notificationRedisData:report_abuse:gcm',$keysForDeletion);   
            }

            if(!empty($this->globalCountArray)) {
                _p($this->globalCountArray);
                $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

                foreach ($cnt as $key => $value) {
                    $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
                }
                $this->globalCountArray = array();
            }    

    }

    private function _prepareNotificationForReportAbuseContentOwner($data,$details){
        $message = $this->_prepareMessageForReportAbuseContentOwner($data);
        $data['message'] = $message;

        $title = $details['title'];   
        $type = $data['type'];
        $message = $data['message'];
        $gcm_message = $data['message'];
        $landing_page = $details['landing_page'];
        $notificationType = $details['notificationType'];
        $userId = $data['ownerId'];

        $gcmNotificationType = $details['notificationType'];

        $commandType = $details['commandType'];
        $trackingURL = $details['trackingURL'];

        $secondaryData = array("Community Guidelines",SHIKSHA_HOME."/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/communityGuideline");

        $isGCM = $details['gcm'];
        $applyLimit = $details['apply_limit'];

        $replaceArray = array();
        $replaceArray['<App>'] = $this->appName;

        foreach ($replaceArray as $key => $value) {
            $title = str_replace($key, $value, $title);
            $message = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
        }

        $time = date('Y-m-d H:i:s');

        $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$message,$time,'unread',0,'',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);
        

        if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($userId)){
            $gcmArray = array();
            $gcmArray['notificationType'] = $gcmNotificationType;
            $gcmArray['userId'] = $userId;
            $gcmArray['title'] = $title;
            
            $gcmArray['message'] = $gcm_message;

            $gcmArray['userId'] = $userId;

            $gcmArray['primaryId'] = $userId;
            $gcmArray['primaryIdType'] = 'user';
            $gcmArray['landing_page'] = $landing_page;

            $gcmArray['secondaryData'] = array("Community Guidelines",SHIKSHA_HOME."/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/communityGuideline");

            if($applyLimit){
                if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($userId)){
                    $this->apicronmodel->insertGCMNotification($gcmArray);    
                }                
            }else {                
                $this->apicronmodel->insertGCMNotification($gcmArray);
            }
            
        }

    }

    private function _prepareNotificationForReportAbuser($data,$details){

        $message = $this->_prepareMessageForReportAbuser($data);
        $data['message'] = $message;

        
        $title = $details['title'];   
        $type = $data['type'];
        $message = $data['message'];
        $gcm_message = $data['message'];
        $landing_page = $details['landing_page'];
        $notificationType = $details['notificationType'];
        $userId = $data['reportAbuser'];

        $gcmNotificationType = $details['notificationType'];

        $commandType = $details['commandType'];
        $trackingURL = $details['trackingURL'];

        $secondaryData = array("Community Guidelines",SHIKSHA_HOME."/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/communityGuideline");

        $isGCM = $details['gcm'];
        $applyLimit = $details['apply_limit'];

        $replaceArray = array();
        $replaceArray['<App>'] = $this->appName;

        foreach ($replaceArray as $key => $value) {
            $title = str_replace($key, $value, $title);
            $message = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
        }

        $time = date('Y-m-d H:i:s');

        $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$message,$time,'unread',0,'',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);
        

        if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($userId)){
            $gcmArray = array();
            $gcmArray['notificationType'] = $gcmNotificationType;
            $gcmArray['userId'] = $userId;
            $gcmArray['title'] = $title;
            
            $gcmArray['message'] = $gcm_message;

            $gcmArray['userId'] = $userId;

            $gcmArray['primaryId'] = $userId;
            $gcmArray['primaryIdType'] = 'user';
            $gcmArray['landing_page'] = $landing_page;

            $gcmArray['secondaryData'] = array("Community Guidelines",SHIKSHA_HOME."/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/communityGuideline");

            if($applyLimit){
                if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($userId)){
                    $this->apicronmodel->insertGCMNotification($gcmArray);    
                }                
            }else {                
                $this->apicronmodel->insertGCMNotification($gcmArray);
            }
            
        }
        
    }

    private function _prepareMessageForReportAbuseContentOwner($data){

        $message = "";
        if($data['status'] == 'Removedwp'){

            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "Your ".$data['entityType']." <b>".$data['entityTitle']."</b> is removed from site on account of report Abuse. We advise you to adhere to our community guidelines while making a post on shiksha to avoid their removal.";
            } else {
                $message = "Your ".$data['entityType']." on <b>".$data['threadTitle']."</b> is removed from site on account of report Abuse. We advise you to adhere to our community guidelines while making a post on shiksha to avoid their removal.";
            }

        }

        else if($data['status'] == 'Removedwop'){


            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "Your ".$data['entityType']." <b>".$data['entityTitle']."</b> is removed from site on account of report Abuse. We advise you to adhere to our community guidelines while making a post on shiksha to avoid their removal.";
            } else {
                $message = "Your ".$data['entityType']." on <b>".$data['threadTitle']."</b> is removed from site on account of report Abuse. We advise you to adhere to our community guidelines while making a post on shiksha to avoid their removal.";
            }
        }

        else if($data['status'] == 'Rejectwp'){

        
           if($data['entityType'] == 'question' || $data['entityType'] == 'discussion') {

                $message = "Your ".$data['entityType']." <b>".$data['entityTitle']."</b> which was removed on account of report abuse has been republished by our moderators as it was found to follow the community guidelines. We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the community.";
            } 
            else {
              $message = "Your ".$data['entityType']." on <b>".$data['threadTitle']."</b> which was removed on account of report abuse has been republished by our moderators as it was found to follow the community guidelines. We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the community.";
            }
        }

        else if($data['status'] == 'Rejectwop'){
            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "Your ".$data['entityType']." <b>".$data['entityTitle']."</b> which was removed on account of report abuse has been republished by our moderators as it was found to follow the community guidelines. We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the community.";
            } else {
                $message = "Your ".$data['entityType']." on <b>".$data['threadTitle']."</b> which was removed on account of report abuse has been republished by our moderators as it was found to follow the community guidelines. We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the community.";
            }
        }

        else if($data['status'] == 'Republishedwp'){           
            $message = ""; 
            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion') {
                $message = "Your ".$data['entityType']." <b>".$data['entityTitle']."</b> which was removed on account of report abuse has been republished by our moderators as it was found to follow the community guidelines. We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the community.";
            } 
            else {
              $message = "Your ".$data['entityType']." on <b>".$data['threadTitle']."</b> which was removed on account of report abuse has been republished by our moderators as it was found to follow the community guidelines. We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the community.";
            }
        }
        else if($data['status'] == 'Republishedwop'){           

            $message = ""; 
             if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "Your ".$data['entityType']." <b>".$data['entityTitle']."</b> which was removed on account of report abuse has been republished by our moderators as it was found to follow the community guidelines. We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the community.";
            } else {
                $message = "Your ".$data['entityType']." on <b>".$data['threadTitle']."</b> which was removed on account of report abuse has been republished by our moderators as it was found to follow the community guidelines. We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the community.";
            }
        }

        else if($data['status'] == 'auto_delete'){

            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "Your ".$data['entityType']." <b>".$data['entityTitle']."</b> is removed from site on account of report abuse. We advise you to adhere to our community guidelines while making a post on shiksha to avoid their removal. Please note that our moderators would review your <b>".$data['entityType']."</b> and if it is found to be appropriate, it would be republished and penalty would be reversed. You do not need to contact us for same.";
            } else {
                $message = "Your ".$data['entityType']." on <b>".$data['threadTitle']."</b> is removed from site on account of report abuse. We advise you to adhere to our community guidelines while making a post on shiksha to avoid their removal. Please note that our moderators would review your <b>".$data['entityType']."</b> and if it is found to be appropriate, it would be republished and penalty would be reversed. You do not need to contact us for same.";
            }
        }
        return $message;
    }

    

    
    private function _prepareMessageForReportAbuser($data){

        $message = "";
        $data['reportAbuseDate'] = date('Y-m-d',strtotime($data['reportAbuseDate']));
        if($data['status'] == 'Removedwp'){
            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){

                $message = ucfirst($data['entityType'])." <b>".$data['threadTitle']."</b> you reported abuse on <b>".$data['reportAbuseDate']."</b> has been removed. We thank you for keeping <b>".$this->appName."</b> clean.";            
            } else {
                $message = ucfirst($data['entityType'])." on <b>".$data['threadTitle']."</b> you reported abuse on <b>".$data['reportAbuseDate']."</b> has been removed. We thank you for keeping <b>".$this->appName."</b> clean.";            
            }            
        }
        else if($data['status'] == 'Removedwop'){
            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){

                $message = ucfirst($data['entityType'])." <b>".$data['threadTitle']."</b> you reported abuse on <b>".$data['reportAbuseDate']."</b> has been removed. We thank you for keeping <b>".$this->appName."</b> clean.";            
            } else {
                $message = ucfirst($data['entityType'])." on <b>".$data['threadTitle']."</b> you reported abuse on <b>".$data['reportAbuseDate']."</b> has been removed. We thank you for keeping <b>".$this->appName."</b> clean.";            
            }

        }

        else if($data['status'] == 'Rejectwp'){           

            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "The report abuse raised by you against ".$data['entityType']." <b>".$data['threadTitle']."</b> on <b>".$data['reportAbuseDate']."</b> has been rejected by the moderator."; 
            } else {
                $message = "The report abuse raised by you against ".$data['entityType']." on <b>".$data['threadTitle']."</b> on <b>".$data['reportAbuseDate']."</b> has been rejected by the moderator."; 
            }
        }
        
        else if($data['status'] == 'Rejectwop'){           
            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "The report abuse raised by you against ".$data['entityType']." <b>".$data['threadTitle']."</b> on <b>".$data['reportAbuseDate']."</b> has been rejected by the moderator."; 
            } else {
                $message = "The report abuse raised by you against ".$data['entityType']." on <b>".$data['threadTitle']."</b> on <b>".$data['reportAbuseDate']."</b> has been rejected by the moderator."; 
            }
        }

        else if($data['status'] == 'Republishedwp'){          

            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "The report abuse raised by you against ".$data['entityType']." <b>".$data['threadTitle']."</b> on <b>".$data['reportAbuseDate']."</b> has been rejected by the moderator."; 
            } else {
                $message = "The report abuse raised by you against ".$data['entityType']." on <b>".$data['threadTitle']."</b> on <b>".$data['reportAbuseDate']."</b> has been rejected by the moderator."; 
            }

        }
        else if($data['status'] == 'Republishedwop'){           

            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = "The report abuse raised by you against ".$data['entityType']." <b>".$data['threadTitle']."</b> on <b>".$data['reportAbuseDate']."</b> has been rejected by the moderator."; 
            } else {
                $message = "The report abuse raised by you against ".$data['entityType']." on <b>".$data['threadTitle']."</b> on <b>".$data['reportAbuseDate']."</b> has been rejected by the moderator."; 
            }
        }

        else if($data['status'] == 'auto_delete'){
            if($data['entityType'] == 'question' || $data['entityType'] == 'discussion'){
                $message = ucfirst($data['entityType'])." <b>".$data['threadTitle']."</b> you reported abuse on <b>".$data['reportAbuseDate']."</b> has been removed. We thank you for keeping <b>".$this->appName."</b> clean.";            
            } else {
                $message = ucfirst($data['entityType'])." on <b>".$data['threadTitle']."</b> you reported abuse on <b>".$data['reportAbuseDate']."</b> has been removed. We thank you for keeping <b>".$this->appName."</b> clean.";            
            }
           
        }
        return $message;
    }    


    public function LevelUpNotificationGenerator($new_level_name,$new_level_id,$userId){
        $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:level:inapp');
        $globalCountArray = array();
        $keysForDeletion = array();

        foreach ($result as $key => $value) {
            $value = (array)json_decode($value);
            array_push($keysForDeletion, $key);
            $type = $value['type'];
            $new_level_id = $value['new_level_id'];
            $new_level_name = $value['new_level_name'];
            $userId = $value['userId'];
            
            if($this->universalApiLib->isUserEligibleForInAppNotification($userId)){
                $this->globalCountArray[] = $userId;
                $this->notificationForLevelsUp($type,$new_level_id,$new_level_name,$userId,'normalCase',$userId,'');
            }
            

            if($new_level_id >= 3){
                $followersArray = $this->notificationModel->fetchFollowersForThread($userId,'user');
                $this->usermodel = $this->CI->load->model('user/usermodel');
                $userData = $this->usermodel->getUserBasicInfoById($userId);
                $name = $userData['firstname'];
                foreach ($followersArray as $followers) {

                    if($this->universalApiLib->isUserEligibleForInAppNotification($followers['recipient'])){
                        $this->globalCountArray[] = $followers['recipient'];
                        $this->notificationForLevelsUp($type,$new_level_id,$new_level_name,$followers['recipient'],'followersCase',$userId,$name);    
                    }
                    
                }
            }

        }


        if(!empty($keysForDeletion)){
            $this->redisLib->deleteMembersOfHash('notificationRedisData:level:inapp',$keysForDeletion);    
            $this->redisLib->deleteMembersOfHash('notificationRedisData:level:gcm',$keysForDeletion);   
        }
        if(!empty($this->globalCountArray)){
            $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

            foreach ($cnt as $key => $value) {
                $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
            }
            $this->globalCountArray = array();
        }
    }

    public function notificationForLevelsUp($type,$new_level_id,$new_level_name,$userId,$whichCase='normalCase',$mainUserId,$mainUserName=''){
        
        $details = $this->CI->config->item(LEVEL_UP_DETAILS);
        
        $configMessages = $this->CI->config->item(LEVEL_UP_MESSAGES);
        $title = $details['title'];        
        $message = $details['message'];
        $gcm_message = $details['gcm_message'];
        $landing_page = $details['landing_page'];
        $notificationType = $details['notificationType'];
        $followersMsg = $details['followersMessage'];

        $gcmNotificationType = $details['gcm_notificationType'];
        $commandType = $details['commandType'];
        $trackingURL = $details['trackingURL'];
        $secondaryData = array();
        $isGCM = $details['gcm'];
        $applyLimit = $details['apply_limit'];



        $replaceArray = array();
        $replaceArray['$1'] = $new_level_name;
        $replaceArray['$2'] = $configMessages[$new_level_id];
        $replaceArray['$3'] = $new_level_id;
        $replaceArray['$4'] = ucfirst($mainUserName);
        $replaceArray['<App>'] = $this->appName;

        foreach ($replaceArray as $key => $value) {
            $title = str_replace($key, $value, $title);
            $message = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
            $followersMsg = str_replace($key, $value, $followersMsg);  
            
        }

        $time = date('Y-m-d H:i:s');
        if($whichCase == 'normalCase'){
            $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$message,$time,'unread',$mainUserId,'user',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);    
        } else {
            $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$followersMsg,$time,'unread',$mainUserId,'user',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);    
            $isGCM = $details['followersGCM'];
        }
        

        if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($userId)){
            $gcmArray = array();
            $gcmArray['notificationType'] = $gcmNotificationType;
            $gcmArray['userId'] = $userId;
            $gcmArray['title'] = $title;
            
            $gcmArray['message'] = $gcm_message;
            if($whichCase == 'followersCase'){
                $gcmArray['message'] = $followersMsg;
            }
            $gcmArray['userId'] = $userId;

            $gcmArray['primaryId'] = $userId;
            $gcmArray['primaryIdType'] = 'user';
            $gcmArray['landing_page'] = $landing_page;

            $gcmArray['dynamicFieldList'] = array(
                'levelNumber' => $new_level_id,
                'greetingTitle' => $configMessages[$new_level_id],
                'greetingMessage' => "You have reached <b>".$new_level_name."</b>. Keep answering!"

            );

            if($applyLimit){
                if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($userId, $details['apply_day_limit'], $type, $userId, 0)){
                    $this->apicronmodel->insertGCMNotification($gcmArray);    
                }                
            }else {                
                $this->apicronmodel->insertGCMNotification($gcmArray);
            }
            
        }



    }


    public function UpvoteAchievementNotificationGenerator(){
        $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:acheievement_upvote:inapp');
        
        $keysForDeletion = array();

        foreach ($result as $key => $value) {
            $value = (array)json_decode($value);
            array_push($keysForDeletion, $key);
            $type = $value['type'];
            $title = $value['title'];
            $threadId = $value['threadId'];
            $threadType = $value['threadType'];
            $userId = $value['userId'];
            $upvotes = $value['upvotes'];
            $answerId = $value['answerId'];
            
            if($this->universalApiLib->isUserEligibleForInAppNotification($userId)){
                $this->globalCountArray[] = $userId;
                $this->notificationForUpvoteAnswerAchievement($type,$title,$threadId,$threadType,$userId,$upvotes,$answerId);
            }
            
        }


        if(!empty($keysForDeletion)){
            $this->redisLib->deleteMembersOfHash('notificationRedisData:acheievement_upvote:inapp',$keysForDeletion);    
            $this->redisLib->deleteMembersOfHash('notificationRedisData:acheievement_upvote:gcm',$keysForDeletion);   
        }

        if(!empty($this->globalCountArray)){
            $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

            foreach ($cnt as $key => $value) {
                $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
            }
            $this->globalCountArray = array();
        }
    }

    public function notificationForUpvoteAnswerAchievement($type,$q_title,$threadId,$threadType,$userId,$upvotes,$answerId){

        $details = $this->CI->config->item(ACHIEVEMENT_UPVOTE_ANSWER_DETAILS);
        
        $title = $details['title'];        
        $message = $details['message'];
        $gcm_message = $details['gcm_message'];
        $landing_page = $details['landing_page'];
        $notificationType = $details['notificationType'];
        $commandType = $details['commandType'];
        $trackingURL = $details['trackingURL'];
        $isGCM = $details['gcm'];
        $applyLimit = $details['apply_limit'];
        $gcmNotificationType = $details['gcm_notificationType'];

        $secondaryData = array($answerId);
        $replaceArray = array();
        $replaceArray['$1'] = $q_title;
        $replaceArray['$2'] = $upvotes;
        $replaceArray['<App>'] = $this->appName;

        foreach ($replaceArray as $key => $value) {
            $title = str_replace($key, $value, $title);
            $message = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
        }

        $time = date('Y-m-d H:i:s');

        $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$message,$time,'unread',$threadId,$threadType,$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,$answerId);

        if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($userId)){
            $gcmArray = array();
            $gcmArray['notificationType'] = $gcmNotificationType;
            $gcmArray['userId'] = $userId;
            $gcmArray['title'] = $title;
            
            $gcmArray['message'] = $gcm_message;
            $gcmArray['userId'] = $userId;
            $gcmArray['secondaryData'] = $secondaryData;

            $gcmArray['primaryId'] = $threadId;
            $gcmArray['primaryIdType'] = 'question';
            $gcmArray['landing_page'] = $landing_page;
            $gcmArray['secondaryDataId'] = $answerId;
            
            if($applyLimit){
                if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($userId, $details['apply_day_limit'], $type, $userId, $answerId)){
                    $this->apicronmodel->insertGCMNotification($gcmArray);    
                }                
            }else {                
                $this->apicronmodel->insertGCMNotification($gcmArray);
            }
            
        }
        
        //public function insertDataInNot       ificationInAppQueue($userId,$action,$title,$message,$time,$readStatus='unread',$entityId='',$entityType='',$action_item='',$action_item_id=0)
    }

    public function FBFriendNotificationGenerator(){
        $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:fbfriend:inapp');
        $this->globalCountArray = array();
        $keysForDeletion = array();

        $usermodel = $this->CI->load->model("user/usermodel");
        foreach ($result as $key => $value) {
            $value = (array)json_decode($value);
            array_push($keysForDeletion, $key);
            $userId = $value['userId'];
            
            $fbFriends = $this->apicronmodel->getFBFriendsOfUser($userId);
            $userDetails = $usermodel->getUserBasicInfoById($userId);
            if($fbFriends && $userDetails){
                
                foreach ($fbFriends as $friendId) {

                    if($this->universalApiLib->isUserEligibleForInAppNotification($friendId)){
                        $this->globalCountArray[] = $friendId;
                        $name = $userDetails['firstname'] ? $userDetails['firstname'] : $userDetails['firstname']." ".$userDetails['lastname'];
                        $this->notificationForFBFriend($friendId, $userId, $name);
                    }
                    
                }
            }
        }

        if(!empty($keysForDeletion)){
            $this->redisLib->deleteMembersOfHash('notificationRedisData:fbfriend:inapp',$keysForDeletion);    
        }
        if(!empty($this->globalCountArray)){
            $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

            foreach ($cnt as $key => $value) {
                $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
            }
            $this->globalCountArray = array();
        }
    }

    public function notificationForFBFriend($userId, $friendId, $friendName){
        
        $details = $this->CI->config->item(FACEBOOK_FRIEND_JOINED_DETAILS);
        
        $type = FACEBOOK_FRIEND_JOINED;
        $title = $details['title'];        
        $message = $details['message'];
        $gcm_message = $details['gcm_message'];
        $landing_page = $details['landing_page'];
        $notificationType = $details['notificationType'];

        $gcmNotificationType = $details['notificationType'];
        $commandType = $details['commandType'];
        $trackingURL = $details['trackingURL'];
        $secondaryData = array();
        $isGCM = $details['gcm'];
        $applyLimit = $details['apply_limit'];

        $replaceArray = array();
        $replaceArray['$1'] = ucfirst($friendName);
        $replaceArray['<App>'] = $this->appName;

        foreach ($replaceArray as $key => $value) {
            $title = str_replace($key, $value, $title);
            $message = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
            
        }

        $time = date('Y-m-d H:i:s');

        $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$message,$time,'unread',$friendId,'user',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);

        if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($userId)){
            $gcmArray = array();
            $gcmArray['notificationType'] = $gcmNotificationType;
            $gcmArray['userId'] = $userId;
            $gcmArray['title'] = $title;
            
            $gcmArray['message'] = $gcm_message;
            $gcmArray['userId'] = $userId;

            $gcmArray['primaryId'] = $friendId;
            $gcmArray['primaryIdType'] = 'user';
            $gcmArray['landing_page'] = $landing_page;

            if($applyLimit){
                if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($userId, $details['apply_day_limit'], $type, $friendId, 0)){
                    $this->apicronmodel->insertGCMNotification($gcmArray);    
                }                
            }else {       
                $this->apicronmodel->insertGCMNotification($gcmArray);
            }
            
        }
    }

    private function _getNotificationMessage($message, $result){
            $message = str_replace("<App>", $this->appName, $message);
            foreach ($result as $key => $value) {
                $result[$key] = str_replace("#", "", $result[$key]);
                $cnt = $key + 1;                        
                if(strpos($message, "$".$cnt) !== false){
                    $message = str_replace("$".$cnt, $result[$key], $message);
                }
            }
            return $message;
    }

     public function UserFollowerAchievementNotificationGenerator(){
        $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:acheievement_user_follow:inapp');
        
        $keysForDeletion = array();

        foreach ($result as $key => $value) {
            $value = (array)json_decode($value);
            array_push($keysForDeletion, $key);
            $type = $value['type'];
            $noOfFollowers = $value['noOfFollowers'];
            $userId = $value['userId'];        

            if($this->universalApiLib->isUserEligibleForInAppNotification($userId)){
                $this->globalCountArray[] = $userId;
                $this->notificationForUserFollowerAchievement($type,$noOfFollowers,$userId);
            }
            
        }


        if(!empty($keysForDeletion)){
            $this->redisLib->deleteMembersOfHash('notificationRedisData:acheievement_user_follow:inapp',$keysForDeletion);    
            $this->redisLib->deleteMembersOfHash('notificationRedisData:acheievement_user_follow:gcm',$keysForDeletion);   
        }

        if(!empty($this->globalCountArray)){
            $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

            foreach ($cnt as $key => $value) {
                $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
            }
            $this->globalCountArray = array();
        }
    }

    public function notificationForUserFollowerAchievement($type,$noOfFollowers,$userId){

        $details = $this->CI->config->item(ACHIEVEMENT_USER_FOLLOW_DETAILS);
        
        $title = $details['title'];        
        $message = $details['message'];
        $gcm_message = $details['gcm_message'];
        $landing_page = $details['landing_page'];
        $notificationType = $details['notificationType'];
        $commandType = $details['commandType'];
        $trackingURL = $details['trackingURL'];
        $isGCM = $details['gcm'];
        $applyLimit = $details['apply_limit'];
        $gcmNotificationType = $details['gcm_notificationType'];

        $secondaryData = array();
        $replaceArray = array();
        $replaceArray['<App>'] = $this->appName;
        $replaceArray['#count#'] = $noOfFollowers;

        foreach ($replaceArray as $key => $value) {
            $title = str_replace($key, $value, $title);
            $message = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
        }

        $time = date('Y-m-d H:i:s');

        $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$message,$time,'unread',$userId,'user',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);

        if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($userId)){
            $gcmArray = array();
            $gcmArray['notificationType'] = $gcmNotificationType;
            $gcmArray['userId'] = $userId;
            $gcmArray['title'] = $title;
            
            $gcmArray['message'] = $gcm_message;
            $gcmArray['userId'] = $userId;
            $gcmArray['secondaryData'] = $secondaryData;

            $gcmArray['primaryId'] = $userId;
            $gcmArray['primaryIdType'] = 'user';
            $gcmArray['landing_page'] = $landing_page;

        
            if($applyLimit){
                if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($userId)){
                    $this->apicronmodel->insertGCMNotification($gcmArray);    
                }                
            }else {                
                $this->apicronmodel->insertGCMNotification($gcmArray);
            }
            
        }
        
        //public function insertDataInNot       ificationInAppQueue($userId,$action,$title,$message,$time,$readStatus='unread',$entityId='',$entityType='',$action_item='',$action_item_id=0)
    }

    public function UserFollowerNotificationGenerator(){
        $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:user_follow:inapp');
        
        $keysForDeletion = array();

        foreach ($result as $key => $value) {
            $value = (array)json_decode($value);
            array_push($keysForDeletion, $key);

            $type       = $value['type'];
            $followeeId = $value['followeeId'];
            $followerId = $value['followerId'];            

            if($this->universalApiLib->isUserEligibleForInAppNotification($followerId)){
                $this->globalCountArray[] = $followerId;
                $this->notificationForUserFollower($type,$followeeId,$followerId);
            }
            
        }


        if(!empty($keysForDeletion)){
            $this->redisLib->deleteMembersOfHash('notificationRedisData:user_follow:inapp',$keysForDeletion);    
        
        }

        if(!empty($this->globalCountArray)){
            $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

            foreach ($cnt as $key => $value) {
                $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
            }

            $this->globalCountArray = array();
        }
    }

    public function notificationForUserFollower($type,$followeeId,$followerId){

        $details = $this->CI->config->item(USER_FOLLOW_DETAILS);
        
        $title               = $details['title'];        
        $message             = $details['message'];
        $gcm_message         = $details['gcm_message'];
        $landing_page        = $details['landing_page'];
        $notificationType    = $details['notificationType'];
        $commandType         = $details['commandType'];
        $trackingURL         = $details['trackingURL'];
        $isGCM               = $details['gcm'];
        $applyLimit          = $details['apply_limit'];
        $gcmNotificationType = $details['gcm_notificationType'];

        $secondaryData = array();
        $replaceArray = array();
        $usermodel = $this->CI->load->model("user/usermodel");
        $userdata = $usermodel->getUserBasicInfoById($followeeId);
        $replaceArray['$1'] = ucfirst($userdata['firstname']);
        $replaceArray['<App>'] = $this->appName;

        foreach ($replaceArray as $key => $value) {
            $title       = str_replace($key, $value, $title);
            $message     = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
        }

        $time = date('Y-m-d H:i:s');

        $notificationInfo = $this->notificationModel->fetchNotifificationInfo(USER_FOLLOW,0,$followerId,'unread',"action_item_id");

        if(empty($notificationInfo)){
            $this->notificationModel->insertDataInNotificationInAppQueue($followerId,$type,$title,$message,$time,'unread',$followeeId,'user',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);
        }
        else{
            $prev_cnt = 1;
            // Searching for pattern like #1#
            if(preg_match("(([#]{1})([0-9])([#]{1}))", $notificationInfo['message'],$matches) === 1){
                $prev_cnt = intval($matches[2]);
            } 
            $new_cnt = $prev_cnt + 1;
            $message = $details['summation_messgae'];

            $message = str_replace("#count#", "#".$new_cnt."#", $message);

            $this->notificationModel->updateDataInNotificationInAppQueue($followerId,$type,$message,date('Y-m-d H:i:s'),'unread',0,'user', "","",$followerId);
        }

        if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($followerId)){
            
            $gcmArray                     = array();
            $gcmArray['notificationType'] = $gcmNotificationType;
            $gcmArray['userId']           = $followerId;
            $gcmArray['title']            = $title;
            $gcmArray['message']          = $gcm_message;
            $gcmArray['secondaryData']    = $secondaryData;
            $gcmArray['primaryId']        = $followeeId;
            $gcmArray['primaryIdType']    = 'user';
            $gcmArray['landing_page']     = $landing_page;

            if($applyLimit){
                if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($followerId)){
                    $this->apicronmodel->insertGCMNotification($gcmArray);    
                }                
            }else {                
                $this->apicronmodel->insertGCMNotification($gcmArray);
            }
            
        }
        
    }

    public function JoiningBonusNotificationGenerator(){
        $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:joinbonus:inapp');
        
        $keysForDeletion = array();

        foreach ($result as $key => $value) {
            $value = (array)json_decode($value);
            array_push($keysForDeletion, $key);
            $type = $value['type'];
            $points = $value['points'];
            $userId = $value['userId'];
            $level = $value['level'];

            if($this->universalApiLib->isUserEligibleForInAppNotification($userId)){
                $this->globalCountArray[] = $userId;
                $this->notificationForJoiningBonus($type,$points,$userId,$level);
            }
            
        }

        if(!empty($keysForDeletion)){
            $this->redisLib->deleteMembersOfHash('notificationRedisData:joinbonus:inapp',$keysForDeletion);    
            $this->redisLib->deleteMembersOfHash('notificationRedisData:joinbonus:gcm',$keysForDeletion);   
        }

        if(!empty($this->globalCountArray)){
            $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

            foreach ($cnt as $key => $value) {
                $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
            }
            $this->globalCountArray = array();
        }
    }

    public function notificationForJoiningBonus($type,$points,$userId,$level){

        $details = $this->CI->config->item(JOINING_BONUS_DETAILS);
        
        $title = $details['title'];        
        $message = $details['message'];
        $gcm_message = $details['gcm_message'];
        $landing_page = $details['landing_page'];
        $notificationType = $details['notificationType'];
        $commandType = $details['commandType'];
        $trackingURL = $details['trackingURL'];
        $isGCM = $details['gcm'];
        $applyLimit = $details['apply_limit'];
        $gcmNotificationType = $details['gcm_notificationType'];

        $secondaryData = array();

        $secondaryData = array("User points system",SHIKSHA_HOME."/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/userPointSystem");

        $replaceArray = array();
        $replaceArray['$1'] = $points;
        $replaceArray['<App>'] = $this->appName;
        

        foreach ($replaceArray as $key => $value) {
            $title = str_replace($key, $value, $title);
            $message = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
        }

        $time = date('Y-m-d H:i:s');

        $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$message,$time,'unread',$userId,'user',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);

        if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($userId)){
            $gcmArray = array();
            $gcmArray['notificationType'] = $gcmNotificationType;
            $gcmArray['userId'] = $userId;
            $gcmArray['title'] = $title;
            
            $gcmArray['message'] = $gcm_message;
            $gcmArray['userId'] = $userId;
            $gcmArray['secondaryData'] = $secondaryData;

            $gcmArray['primaryId'] = $userId;
            $gcmArray['primaryIdType'] = 'user';
            $gcmArray['landing_page'] = $landing_page;

            $gcmArray['dynamicFieldList'] = array(
                'points' => $points,
                'greetingTitle' => "Congrats! Here's your joining bonus",
                'greetingMessage' => "",
                'helpPageUrl' => SHIKSHA_HOME."/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/userPointSystem",
                'helpPageTitle' => 'User points system'
                );
        
            if($applyLimit){
                if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($userId)){
                    $this->apicronmodel->insertGCMNotification($gcmArray);    
                }                
            }else {                
                $this->apicronmodel->insertGCMNotification($gcmArray);
            }
            
        }
        
    }



    /**
    * Function to add Notifications to REDIS
    * $dataArray Array with custom params for notifications Data
    */
    public function addNotificationToRedis($dataArray){
        $type = $dataArray['type'];
        $details = $this->CI->config->item($dataArray['type']."_DETAILS");
        $userId = $dataArray['userId']?$dataArray['userId']:0;
        if($userId){
            $redisData = array();
            foreach ($dataArray as $key => $value) {
                if($key == "type") continue;
                $redisData[$key] = $value;
            }
            $notificationRedisData = json_encode($redisData);
            $key = $type."__".$userId."__".time()."__".rand();  

            $this->redisLib->addMembersToHash("notificationRedisData:".$type.":inapp",array($key=>$notificationRedisData));            
        }
        
    }


    /**
    * Lib function to generate Notification for Most Active User on Tag
    *
    */
    public function generateMostActiveUserOnTagNotification(){
            $type = MOST_ACTIVE_USER_ON_TAG;
            $detailKey = strtoupper($type).$this->suffixDetails;
            $result = $this->redisLib->getAllMembersOfHashWithValue('notificationRedisData:'.$type.':inapp');

            // Generate Tags Mapping to fetch the tags name
            $tagmodel = $this->CI->load->model('Tagging/taggingcmsmodel');
            $streamTags = $tagmodel->getTagsArray('Stream');
            $tagsMapping = array();

            // Fetching Data for Notification from Config
            $dataForAction = $this->CI->config->item($detailKey);
            
            foreach ($streamTags as $key => $value) {
                $tagsMapping[$value['id']] = $value['tags'];
            }

            $keysForDeletion = array();      

            $commonLib = $this->CI->load->library('common_api/APICommonLib');
            foreach ($result as $key => $value) {
                
                $value = (array)json_decode($value);
                array_push($keysForDeletion, $key);
                $userId = $value['userId'];
                $tags = $value['tags'];
                
                // Active User check, true send notification else send mail
                if($commonLib->isUserActiveOnApp($userId)){
                    $this->globalCountArray[] = $userId;
                    // Actual Notification Generation
                    $this->notificationForActiveUserTags($dataForAction,$userId,$tags,$tagsMapping,$type);
                } else {
                    // schedule Mail                    
                    $userData = $this->notificationModel->fetchDataById($userId);
                    
                    if(!empty($userData)){
                        $email = $userData['email'];
                        $contentArr['name'] = ucfirst($userData['firstName']);
                        $contentArr['mail_subject'] = "Congrats! You are a top active user now";
                        $tagsName = array();
                        
                        foreach ($tags as $tagId) {
                           $tagsName[] = $tagsMapping[$tagId];
                        }

                        $tagsNameString = implode(", ", $tagsName);
                        $contentArr['tagsNameString'] = $tagsNameString;
                        $contentArr['receiverId'] = $userId;
                        Modules::run('systemMailer/SystemMailer/tagsTopContributorsMail', $email, $contentArr);    
                    }
                    
                }

            }  
            // Delete the redis key
            if(!empty($keysForDeletion)){
                $this->redisLib->deleteMembersOfHash('notificationRedisData:'.$type.':inapp',$keysForDeletion);
            }

            // Update the Notification Count
            if(!empty($this->globalCountArray)){
                $cnt = $this->notificationModel->fetchUnreadNotificationCount($this->globalCountArray);

                foreach ($cnt as $key => $value) {
                    $this->redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
                }
                $this->globalCountArray = array();
            }

    }

    /**
    * Lib function to actually generate the content for Notification(Running inside loop for each user i.e Redis key)
    *
    */
    function notificationForActiveUserTags($dataForAction,$userId,$tags,$tagsMapping,$type){

        $tagsName = array();
        
        foreach ($tags as $tagId) {
           $tagsName[] = $tagsMapping[$tagId];
        }

        $tagsNameString = implode(", ", $tagsName);

        // Genarate Message & Title for Notification
        $message = $dataForAction['message'];
        $gcm_message = $dataForAction['gcm_message'];
        $title = $dataForAction['title'];

        $time = date('Y-m-d H:i:s');

        // Replace all the dynamic Params
        $replaceArray = array();
        $replaceArray['$1'] = $tagsNameString;
        $replaceArray['<App>'] = $this->appName;

        foreach ($replaceArray as $key => $value) {
            $title = str_replace($key, $value, $title);
            $message = str_replace($key, $value, $message);       
            $gcm_message = str_replace($key, $value, $gcm_message);  
        }
        
        // Other parameters for Notifcations
        $landing_page = $dataForAction['landing_page'];
        $gcmNotificationType = $dataForAction['gcm_notificationType'];
        $notificationType = $dataForAction['notificationType'];
        $commandType = $dataForAction['commandType'];
        $trackingURL = $dataForAction['trackingURL'];
        $isGCM = $dataForAction['gcm'];
        $applyLimit = $dataForAction['apply_limit'];
        $secondaryData = array();
        
        // Generated InApp Notification(Insert into notificationInAppQueue)
        $this->notificationModel->insertDataInNotificationInAppQueue($userId,$type,$title,$message,$time,'unread',$userId,'user',$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,0);


        // Check for GCM Conditions and generate GCM Notifications(insert into GCM Table)
         if($isGCM && $this->universalApiLib->isUserEligibleForGCMNotification($userId)){
        
                $gcmArray = array();
                $gcmArray['notificationType'] = $gcmNotificationType;
                $gcmArray['userId'] = $userId;
                $gcmArray['title'] = $title;
                
                $gcmArray['message'] = $gcm_message;
                $gcmArray['userId'] = $userId;
                $gcmArray['secondaryData'] = $secondaryData;

                $gcmArray['primaryId'] = $userId;
                $gcmArray['primaryIdType'] = 'user';
                $gcmArray['landing_page'] = $landing_page;

                $gcmArray['dynamicFieldList'] = array();
            
                if($applyLimit){
                    if(!$this->universalApiLib->isGCMNotificationCountExceedLimit($userId)){
                        $this->apicronmodel->insertGCMNotification($gcmArray);    
                    }                
                }else {                
                    $this->apicronmodel->insertGCMNotification($gcmArray);
                }
                
            }
        }
}