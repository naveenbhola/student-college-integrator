<?php
/*
    Model for database related operations related to Universal APIs
    Following is the example this model can be used in the server controllers.
    $this->load->model('UniversalModel');
    $this->UniversalModel->followEntity();
*/

class UniversalModel extends MY_Model {
        private $dbHandle = '';
        function __construct(){
                parent::__construct('AnA');
        }

        private function initiateModel($operation='read'){
                $appId = 1;
                if($operation=='read'){
                        $this->dbHandle = $this->getReadHandle();
                }
                else{
                        $this->dbHandle = $this->getWriteHandle();
                }
        }


        /**
         * @desc API to Keep a log of FOllow/Unfollow of Question/Discussion//Tags/User by a User
         * @param param userId which is the user id
         * @param param entityId which is the entity id
         * @param param entityType which is the entity type
         * @param param action which could be follow/unfollow
         * @return true
         * @date 2015-08-09
         * @author Ankur Gupta
         */
        function followEntity($userId, $entityId, $entityType, $action,$followType='explicit',$tracking_keyid=''){
        
        if($userId > 0 && $entityId > 0){
                    $this->initiateModel('write');

                    $followTypeText = "|".$followType."|";
                    if($action == 'follow'){
                        if(empty($tracking_keyid))    
                        {
                            $tracking_keyid = 0;
                        }
                        $queryCmd = "INSERT INTO tuserFollowTable (userId, entityId, entityType, status,followType, modificationTime,tracking_keyid,visitorsessionid) VALUES (?, ?, ?, ?, ?,now(),?,?) ON DUPLICATE KEY UPDATE modificationTime = now(), followType = IF(LOCATE('".$followTypeText."', followType) > 0, followType, concat(IF(ISNULL(followType), '', followType), '".$followTypeText."')) ,tracking_keyid = ".$tracking_keyid." , visitorsessionid= '".getVisitorSessionId()."' , status = ?";
                        $query = $this->dbHandle->query($queryCmd, array($userId, $entityId, $entityType, $action,$followTypeText,$tracking_keyid,getVisitorSessionId(), $action));                    
                    }
                    else{
                        $queryCmd = "INSERT INTO tuserFollowTable (userId, entityId, entityType, status,followType, modificationTime) VALUES (?, ?, ?, ?, ?,now()) ON DUPLICATE KEY UPDATE status = IF(followType = '".$followTypeText."' OR ('$followType' = 'explicit'), 'unfollow', status), followType = IF(followType = '".$followTypeText."' OR ('$followType' = 'explicit'), NULL, REPLACE(followType, '".$followTypeText."', ''))  ,modificationTime = now()";
                        $query = $this->dbHandle->query($queryCmd, array($userId, $entityId, $entityType, $action, $followTypeText));                    
                    }

                        //Add the entry in Redis for Personalized Homepage
            $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
            $entityType = strtolower($entityType);
                        if($action == 'follow'){
                $this->userinteractioncachestoragelibrary->storeUserActionFollow($userId, $entityId, $entityType);
                        }
            else if($action == 'unfollow'){
                $this->userinteractioncachestoragelibrary->storeUserActionUnfollow($userId, $entityId, $entityType);
            }
			$entityMapping = array('Country' => 'countries_interest',
						'Stream' => 'stream_interest',
						'Course Level' => 'course_level');
                        // update User point system
                        if($action == 'follow'){
                                $this->load->model('UserPointSystemModel');
                                $this->UserPointSystemModel->updateUserPointSystem($this->dbHandle,$userId,$entityType.'Follow',$entityId);
                            if($entityType == 'user'){
                                // send notification of the followee
                                $notificationLib =  $this->load->library('Notifications/NotificationContributionLib');
                                $notificationLib->addUserFollowNotificationToRedis($userId, $entityId);
                            }
                        }
			
			//check user data in userProfileFollowCheck table
			if($entityType == 'tag'){
//				
				$sql = "select tag_entity from tags where id = ? limit 1";
				$query = $this->dbHandle->query($sql, array($entityId));
				$row = $query->row_array();
				$entityDB = $row['tag_entity'];
				$checkFollowType = isset($entityMapping[$entityDB])?$entityMapping[$entityDB]:"";
	                        $profileFollowData = $this->checkEntryInuserProfileFollowCheckTable($userId,$checkFollowType,'tag');

				if(empty($profileFollowData) && $checkFollowType != ""){
					$this->userprofilebuildermodel = $this->load->model('user/userprofilebuildermodel');
					$this->userprofilebuildermodel->insertUserProfileFollowCheckData($userId,'tag',$checkFollowType,'live');
				}

                                //Trigger ILP/ULP Digest if the user follows the Institute/University Tag
                                //Check if this Tag is mapped to any DOmestic Institute / University
                                $sql = "SELECT entity_id, entity_type FROM tags_entity WHERE status = 'live' AND entity_type IN ('institute','National-University') AND tag_id = ?";
                                $query = $this->dbHandle->query($sql, array($entityId));
                                $row = $query->row_array();
                                $instituteId = isset($row['entity_id'])?$row['entity_id']:0;
                                //If InstituteId is present and this is a follow action, trigger the Mailer
                                if($action == 'follow' && $instituteId > 0){
                                        $this->load->library("common/jobserver/JobManagerFactory");
                                        try {
                                            $jobManager = JobManagerFactory::getClientInstance();
                                            if ($jobManager) {
                                                    if(!empty($instituteId)){
                                                        $mailerData['instituteId'] = $instituteId;
                                                        $mailerData['userId'] = $userId;
                                                        $jobManager->addBackgroundJob("InstituteDigestMailerQueue", $mailerData);
                                                    }
                                            }
                                        }catch (Exception $e) {
                                            error_log("Unable to connect to rabbit-MQ");
                                        }
                                }

			}
		
			//Award points to user in case he is filling up his profile
			if( (strpos($followType,'country') !== false || strpos($followType,'countries_interest') !== false) && $action == 'follow' && $entityType == 'tag'){
				$this->UserPointSystemModel->updateUserPointSystem($this->dbHandle,$userId,$userPointType = 'profileCountryInterest');
			}
			else if( (strpos($followType,'stream') !== false || strpos($followType,'stream_interest') !== false) && $action == 'follow' && $entityType == 'tag'){
                                $this->UserPointSystemModel->updateUserPointSystem($this->dbHandle,$userId,$userPointType = 'profileStream');
                        }
			

                    return true;
        }
        else{
            return false;
        }
        }
	
	function checkEntryInuserProfileFollowCheckTable($userId,$followType,$entityType){
		
	    $this->initiateModel('read');
            
            $queryCmd = "select * from userProfileFollowCheck where userId = ? and followType like '%".$followType."%' and status='live' and entityType=? "; 
            $query = $this->dbHandle->query($queryCmd,array($userId,$entityType));
            
            $results =  $query->result_array();
        
            return $results;
		
	}

        function migrateAboutMeSection(){

            $this->initiateModel('write');

            $queryCmd = "select userId, aboutMe from CA_ProfileTable where profileStatus='accepted' and aboutMe!='' ";
            $query = $this->dbHandle->query($queryCmd);
            $results =  $query->result_array();
            foreach ($results as $user){
                $user['aboutMe'] = substr ($user['aboutMe'], 0, 500);
                $queryCmdA = "select * from tUserAdditionalInfo where userId = ? ";
                $queryA = $this->dbHandle->query($queryCmdA, array($user['userId']));
                if($queryA->num_rows()>0){      //If info is found, update the row
                        $userInfo = $queryA->row();
                        if($userInfo->bio == ''){
                                $queryCmdU = " update tUserAdditionalInfo set bio = ? where userId = ? ";
                                $queryU = $this->dbHandle->query($queryCmdU, array($user['aboutMe'],$user['userId']));
                        }
                }
                else{   //Insert the data
                        $queryCmdU = " INSERT INTO tUserAdditionalInfo (userId, bio) VALUES (?, ?) ";
                        $queryU = $this->dbHandle->query($queryCmdU, array($user['userId'],$user['aboutMe']));
                }
            }

            $queryCmd = "select userId, aboutMe from expertOnboardTable where status='Live' and aboutMe!='' ";
            $query = $this->dbHandle->query($queryCmd);
            $results =  $query->result_array();
            foreach ($results as $user){
                $user['aboutMe'] = substr ($user['aboutMe'], 0, 500);
                $queryCmdA = "select * from tUserAdditionalInfo where userId = ? ";
                $queryA = $this->dbHandle->query($queryCmdA, array($user['userId']));
                if($queryA->num_rows()>0){      //If info is found, update the row
                        $userInfo = $queryA->row();
                        if($userInfo->bio == ''){
                                $queryCmdU = " update tUserAdditionalInfo set bio = ? where userId = ? ";
                                $queryU = $this->dbHandle->query($queryCmdU, array($user['aboutMe'],$user['userId']));
                        }
                }
                else{   //Insert the data
                        $queryCmdU = " INSERT INTO tUserAdditionalInfo (userId, bio) VALUES (?, ?) ";
                        $queryU = $this->dbHandle->query($queryCmdU, array($user['userId'],$user['aboutMe']));
                }
            }

            return 1;
        }

        /**
         * Function to get tags for a specified entity and entity type
         * @author Romil Goel <romil.goel@shiksha.com>
         * @date   2016-04-05
         * @param  [type]     $entityId   [entity id]
         * @param  [type]     $entityType [entity type]
         * @return [type]                 [tags ids list]
         */
        public function getTagsForEntity($entityId, $entityType){

            if(empty($entityId) || empty($entityType))
                return false;

            $this->initiateModel('read');

            $sql = "SELECT tag_id FROM tags_entity WHERE entity_id=? and entity_type=? and status='live'";
            $result = $this->dbHandle->query($sql, array($entityId, $entityType))->result_array();
            
            $data = array();
            foreach ($result as $value) {
                $data[] = $value['tag_id'];
            }
            return $data;
        }

        public function getStreamsByTagIds($tagIds){
            if(empty($tagIds)){
                return array();
            }
            $this->initiateModel('read');
            $sql = "SELECT entity_id from tags_entity where tag_id in (?) and entity_type = 'Stream' and status='live'";
            $result = $this->dbHandle->query($sql, array($tagIds))->result_array();

            $streamIds = $this->getColumnArray($result,'entity_id');
            return $streamIds;
        }

}
?>
