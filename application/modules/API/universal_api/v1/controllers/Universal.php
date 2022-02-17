<?php
/**
 * Universal Class
 * This is the class for all the Universal APIs Like followEntity
 * @date    2015-08-09
 * @author  Ankur Gupta
 * @todo    none
*/

class Universal extends APIParent {

        private $validationObj;

        function __construct() {
                parent::__construct();
                $this->load->library(array('UniversalValidationLib'));
                $this->validationObj = new UniversalValidationLib();
        }

        /**
         * @desc API to Follow/Unfollow a question/discussion/user/tag by a User
         * @param POST param entityId which is the entity id
         * @param POST param entityType which is the entity type
         * @param POST param action which could be follow/unfollow
         * @return JSON string with HTTP Code 200 and Success/Failure message
         * @date 2015-08-09
         * @author Ankur Gupta
         */
        function followEntity(){
        
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            $entityId = $this->input->post('entityId');
	    	$entityType = $this->input->post('entityType');
	    	$action = isset($_POST['action'])?$this->input->post('action'):'follow';
	        $tracking_keyid = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
            
            //step 2:validate all the fields
            if(! $this->validationObj->validateFollowEntity($this->response, array('userId'=>$userId, 'entityId'=>$entityId, 'entityType'=>$entityType, 'action'=>$action))){
                    return;
            }
            //Step 3: Send the Input to Backend and make the changes
            $this->load->model('common/UniversalModel');
            $result = $this->UniversalModel->followEntity($userId, $entityId, $entityType, $action,'explicit',$tracking_keyid);
	    	//Add notification of APP in redis
                if($this->response->getStatusCode() == 0){
			$this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
            if($entityType=='question' && $action == 'follow'){
                $this->appNotification->addNotificationForAppInRedis('new_follower_on_question',$entityId,'question',$userId);
			}else if($entityType=='discussion' && $action == 'follow'){
				$this->appNotification->addNotificationForAppInRedis('new_follower_on_discussion',$entityId,'discussion',$userId);
			}
		}
        
            //Step 4: Return the Response
            $this->response->output();    
        }

        /**
         * @desc API for Auto Suggestor results to be shown
         * @param GET param text = text entered by user
         * @param GET param type = type of Auto-suggestor. This can be tag/user/institute/course/content
         * @return JSON object with the Results
         * @date 2015-08-19
         * @author Ankur Gupta
         */
        function autoSuggestor(){
            
            $text   = $this->input->post("text");
            $type   = $this->input->post("type");
            $count  = $this->input->post("count");
            $entity = $this->input->post("entity");
            
            $type   = $type ? $type : 'tag';
            $count  = $count ? $count : 25;
            $entity = $entity ? $entity : "";

            //step 1:Fetch the Input from GET/POST
            $Validate     = $this->getUserDetails();
            $userId       = isset($Validate['userId'])?$Validate['userId']:0;
            
            $optionParams = array('numRows'=>$count);


            if($type == 'tag')
            {
                $this->load->config('TaggingConfig');
                $allowedUsers = $this->config->item('special_tags_users');
                $specialtags  = $this->config->item('special_tags');

                if(in_array($userId, $allowedUsers)){
                    $specialUser = true;
                }

                $optionParams['specialtags'] = $specialtags;
                
                if($specialUser){
                    $optionParams['specialUser'] = true;
                }else{
                    unset($optionParams['specialUser']);
                }
            }


            //step 2:validate all the fields
            if(! $this->validationObj->validateAutoSuggestor($this->response, array('userId'=>$userId, 'text'=>$text, 'type'=>$type, 'count'=>$count ))){
                    return;
            }

            //Step 3: Send the Input to Backend and make the changes
            // Get the list of user's to whom current user is following
            if(in_array($type, array('user', 'tag_user'))){
                $this->load->library('common_api/APICommonCacheLib');
                $apiCommonCacheLib = new APICommonCacheLib();
                $userFollowList                 = $apiCommonCacheLib->getUserFollowList($userId);
                $userFollowerList               = $apiCommonCacheLib->getUserFollowerList($userId);
                $userFriendList                 = $apiCommonCacheLib->getUserFriendList($userId);
                $finalList                      = array_merge((array)$userFollowList, (array)$userFollowerList, (array)$userFriendList);
                $optionParams['userFollowList'] = $finalList;
            }
    	    //Load the Auto Suggestor Library here and send the data
    	    $this->load->library('search/Autosuggestor/AutosuggestorLib');
    	    $result = $this->autosuggestorlib->getAutoSuggestions($type, $text, $optionParams, $entity);
    	    $this->response->setBody($result);

            //Step 4: Return the Response
            $this->response->output();
        }
        
}

