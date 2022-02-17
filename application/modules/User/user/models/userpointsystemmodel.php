<?php
/** 
 * Model for database related operations related to user point system.
 * Following is the example this model can be used in the server controllers.
 * $this->load->model('UserPointSystemModel');
 * $this->UserPointSystemModel->updateUserPointSystem($dbHndle,$userId,$action);
*/

/**
 * Model for database related operations related to user point system.
 */
class UserPointSystemModel extends MY_Model {
	/**
	 * Variable for DB Handling
	 * @var object
	 */
	private $dbHandle = '';
	
	/**
	 * Constructor Function
	 */
	function __construct(){
		parent::__construct('User');
	}
	
	/**
	 * Function to initiate the Model
	 * 
	 * @param string $operation
	 *
	 */
	private function initiateModel($operation = 'read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

     /**
      * Function to check the reputation for 30 days
      * 
      * @param integer $userId
      * @param integer $newmsgId
      * @param string $action
      */
      function checkReputationForThirtyDays($userId,$newmsgId,$action){
			$this->initiateModel();
            $queryCmd1 = "SELECT upsl.*,mt.userId FROM userpointsystemlog upsl join messageTable mt on (upsl.entityId = mt.msgId and mt.userId= (select userId from messageTable where msgId = ?)) WHERE upsl.userId=? and  (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`timestamp`)<2592000) and upsl.action=?";
            $queryRes1 = $this->dbHandle->query($queryCmd1, array($newmsgId, $userId, $action));
            $numOfRows  = $queryRes1->num_rows();
            return $numOfRows;
        }
      
	/**
	 * Function to calculate the Reputation Points
	 * @param integer $numOfRows
	 * @param integer $levelOfTheUser
	 * @param string $action
	 * 
	 */
        function calculateRutationpoints($numOfRows,$levelOfTheUser,$action){
              if($action=='rateThumpUp' && $numOfRows >= 1 ){
                  $level = $levelOfTheUser*(0.2);
                  $newAwardPoints = $level*(1- (($numOfRows)*20/100));
              }elseif($action=='rateThumpDown' && $numOfRows >= 1 ){
                  $newAwardPoints = (0.2)*(1- (($numOfRows)*20/100));
              }elseif(($action=='selectBestAnswer') && $numOfRows >= 1 ){
              $newAwardPoints = (1- (($numOfRows)*20/100));
              }
              return $newAwardPoints;
      }
	
	/**
	 * Function to get the User Level based on points
	 *
	 * @param integer $userId
	 */
	function getLevel($userId){
		$this->initiateModel('write');
		$queryCmd3 = "SELECT levelId from userpointsystembymodule where userId=? and modulename='AnA'";
		$queryRes3 = $this->dbHandle->query($queryCmd3, array($userId));
		$points  = $queryRes3->result_array();
		$level = 1;
		if(isset($points[0]['levelId'])){
		    $level = $points[0]['levelId'];
		}
		return $level;
	}

	function getPointsAndHQ($userId, $moduleName = 'AnA'){
                $this->initiateModel('write');
                $queryCmd3 = "SELECT userpointvaluebymodule, HQContentCount from userpointsystembymodule where userId=? and modulename=?";
                $queryRes3 = $this->dbHandle->query($queryCmd3, array($userId,$moduleName));
                $points  = $queryRes3->result_array();
                $pointArr = array('points'=>0, 'HQCount'=>0);
                if(isset($points[0]['userpointvaluebymodule'])){
                    $pointArr['points'] = $points[0]['userpointvaluebymodule'];
		    $pointArr['HQCount'] = $points[0]['HQContentCount'];
                }
                return $pointArr;
	}

	function getLevelFromPoints($points,$HQCount){
                $this->load->helper('messageBoard/abuse');
                return getLevelFromPoints($points,$HQCount);
	}

      /**
       * Function to calculate new award points
       *
       * @param integer $userId
       * @param integer $newmsgId
       * @param string $action
       * @param integer $numOfRows
       */
      function newAwardPoints($userId,$newmsgId,$action,$numOfRows){
            $level = $this->getLevel($userId);
            $newAwardPoints = $this->calculateRutationpoints($numOfRows,$level,$action);
            if($newAwardPoints<=0 && ($action=='rateThumpDown' || $action=='selectBestAnswer' || $action=='rateThumpUp'))
            {
                $newAwardPoints = 0;
            }
            return $newAwardPoints;
          
      }
	
      /**
       * Function to calculate the Rank based on Reputation Points
       *
       * @param object $dbHandleSent
       * @param integer $userId
       */
      function calculateRankByRepuationPoints($dbHandleSent,$userId){
                if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
                $queryTotal  = "select distinct(floor(points)) as points from tuserReputationPoint order by points desc";
                $exeTotal = $this->dbHandle->query($queryTotal);
                $exeTotalNumRow = $exeTotal->num_rows();
                $resTotal  = $exeTotal->result_array();
                
                $queryCmd = "select points from tuserReputationPoint where userId = ?";
                $queryRes = $this->dbHandle->query($queryCmd, array($userId));
                
                $reputationPoints  = $queryRes->result_array();
                $repPonits=  floor($reputationPoints[0]['points']);
                for($i=0;$i<$exeTotalNumRow;$i++){
                        if($resTotal[$i][points]-$repPonits=='0'){
                            return ($i+1);
                        }
                }

      }
	
	/**
	 * function followUserReputationPoints
	 *
	 * @param object $dbHandleSent
	 * @param integer $userId
	 * @param integer $followingUserId
	 * @param string $action
	 *
	 * @return boolean
	 *
	 */
      function followUserReputationPoints($dbHandleSent,$userId,$followingUserId,$action){
                if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
             $level = $this->getLevel($followingUserId);
             $this->tuserReputationPointEntry($userId, 0.2*($level),$action);
             return true;
      }

      
     /**
      * Function to update the user points
      *
      * @param object $dbHandleSent
      * @param integer $userID
      * @param string $action
      * @param integer $newmsgId
      *
      */
	function updateUserPointSystem ($dbHandleSent,$userId,$action,$newmsgId=0){
		$this->initiateModel('write');
		//In case of User profile action, check if the entry is already present in the DB. If not, proceed
		if(strpos($action,'profile')===0){
			$this->benchmark->mark('is_profile_start');
			if($this->isProfileEntryPresent($userId,$action)){
				return false;
			}
			$this->benchmark->mark('is_profile_end');
		}

		//In case of Start discussion, just award Points from useractionpointmapping
		//In case of followDiscussion/followQuestion, find the number of followers this Discussion got.
		//In case of receiveThumbUpAnswer/receiveThumbUpComment, find the number of Total thumb up this Answer/Comment got.
		//In case of Report abuse, first check that this should not be first RA. If not, find the level of the user whose entity is getting abused
		switch ($action){
			case 'userFollow':
						 	$noOfFollowers = $this->getNumberOfFollowers($newmsgId, 'user');
							$noOfFollowers = intval($noOfFollowers);

							if($noOfFollowers == "100" || $noOfFollowers == "500" || $noOfFollowers == "1000"){
								$notificationLib =  $this->load->library('Notifications/NotificationContributionLib');
		        				$notificationLib->addAchievementUserFollowNotificationToRedis($noOfFollowers,$newmsgId);	
							}
							else if($noOfFollowers % 10000 == 0){
								$notificationLib =  $this->load->library('Notifications/NotificationContributionLib');
		        				$notificationLib->addAchievementUserFollowNotificationToRedis($noOfFollowers,$newmsgId);
							}
							return;
			case 'discussionFollow': 	$noOfFollowers = $this->getNumberOfFollowers($newmsgId, 'discussion');
							if($noOfFollowers == "25"){
								$action = "discussionFollow25";
							}
							else if($noOfFollowers == "100"){
								$action = "discussionFollow100";
							}

                                                        //Here, userId will be entity owner
                                                        $queryCmd = "select userId from messageTable where msgId = ?";
                                                        $query = $this->dbHandle->query($queryCmd, array($newmsgId));
                                                        $res   = $query->row();
                                                        $userId = $res->userId;

							break;
			case 'questionFollow': 		$noOfFollowers = $this->getNumberOfFollowers($newmsgId, 'question');
							if($noOfFollowers == "25"){
								$action = "questionFollow25";
							}
							else if($noOfFollowers == "100"){
								$action = "questionFollow100";
							}

                                                        //Here, userId will be entity owner
                                                        $queryCmd = "select userId from messageTable where msgId = ?";
                                                        $query = $this->dbHandle->query($queryCmd, array($newmsgId));
                                                        $res   = $query->row();
                                                        $userId = $res->userId;

							break;
			case 'receiveThumpUpAnswer': 	
							$noOfUpvotes = $this->getNumberOfUpvotes($newmsgId, 'Answer');
							$this->updateHQContentCount($userId, 'AnA');
							if($noOfUpvotes == "10"){
								$action = "answerUpvotes10";
							}
							else if($noOfUpvotes == "25"){
								$notificationLib =  $this->load->library('Notifications/NotificationContributionLib');
		        				$notificationLib->addAnswerUpvotesNotificationToRedis($noOfUpvotes,$newmsgId);	
								$action = "answerUpvotes25";
							}
							else if($noOfUpvotes == "100"){
								$notificationLib =  $this->load->library('Notifications/NotificationContributionLib');
		        				$notificationLib->addAnswerUpvotesNotificationToRedis($noOfUpvotes,$newmsgId);
								$action = "answerUpvotes100";
							}
							break;
			case 'receiveThumpUpComment': 	$noOfUpvotes = $this->getNumberOfUpvotes($newmsgId, 'Comment');
							if($noOfUpvotes == "10"){
								$action = "commentUpvotes10";
							}
							else if($noOfUpvotes == "25"){
								$action = "commentUpvotes25";
							}
							else if($noOfUpvotes == "100"){
								$action = "commentUpvotes100";
							}
							break;
                        case 'removeThumpUpAnswer':   $noOfUpvotes = $this->getNumberOfUpvotes($newmsgId, 'Answer');
                                                       $this->reduceHQContentCount($userId, 'AnA'); 
                                                       	if($noOfUpvotes == "9"){
                                                                $action = "answerUpvoteRemoval10";
                                                        }
                                                        else if($noOfUpvotes == "24"){
                                                                $action = "answerUpvoteRemoval25";
                                                        }
                                                        else if($noOfUpvotes == "99"){
                                                                $action = "answerUpvoteRemoval100";
                                                        }
                                                        break;
                        case 'removeThumpUpComment':   $noOfUpvotes = $this->getNumberOfUpvotes($newmsgId, 'Comment');
                                                        if($noOfUpvotes == "9"){
                                                                $action = "commentUpvoteRemoval10";
                                                        }
                                                        else if($noOfUpvotes == "24"){
                                                                $action = "commentUpvoteRemoval25";
                                                        }
                                                        else if($noOfUpvotes == "99"){
                                                                $action = "commentUpvoteRemoval100";
                                                        }
                                                        break;
			case 'reportAbuseAccepted':
							//Here, userId will be entity owner
							$queryCmd = "select userId from messageTable where msgId = ?";
							$query = $this->dbHandle->query($queryCmd, array($newmsgId));
							$res   = $query->row();
							$userId = $res->userId;

							//Now, check how many RA this user has got and what is his level
							$noOfAbuses = $this->getNumberOfAbuses($userId);
							if($noOfAbuses >= 1){
								$levelNo = $this->getLevel($userId);
								if($levelNo >= 1 && $levelNo <= 5){
									$action = "reportAbuseBeginner";
								}
								else if($levelNo >= 6 && $levelNo <= 10){
									$action = "reportAbuseContributor";
								}
								else if($levelNo >= 11 && $levelNo <= 15){
									$action = "reportAbuseGuide";
								}
								else if($levelNo >= 16 && $levelNo <= 18){
									$action = "reportAbuseExpert";
								}
							}
							else{
								$action = "reportAbuseFirstTime";
							}
							break;
			case 'republishQuestion': 	
			case 'republishDiscussion':
			case 'republishAnswer':
                                                        $levelNo = $this->getLevel($userId);
                                                        if($levelNo >= 1 && $levelNo <= 5){
                                                                $action = "republishEntityBeginner";
                                                        }
                                                        else if($levelNo >= 6 && $levelNo <= 10){
                                                                $action = "republishEntityContributor";
                                                        }
                                                        else if($levelNo >= 11 && $levelNo <= 15){
                                                                $action = "republishEntityGuide";
                                                        }
                                                        else if($levelNo >= 16 && $levelNo <= 18){
                                                                $action = "republishEntityExpert";
                                                        }
							break;
			case 'postQuestion':
				$noOfQues = $this->getCountOfQuesPostedByUser($userId);
				if($noOfQues > 5){
					$action = 'postQuestionButNoPoints';
				}
				break;
		}
		$this->benchmark->mark('get_level_start');
        $level = $this->getLevel($userId);
		$this->benchmark->mark('get_level_end');

            if($action=='postDiscussionComment' || $action=='postAnnouncementComment'){
			if($action=='postDiscussionComment'){
			    $type = 'discussion';
			}
			if($action=='postAnnouncementComment'){
			    $type = 'announcement';
			}

			//$queryCmd = "select userId from messageTable where userId=$userId and mainAnswerId not in ('0', '-1')and threadId = $newmsgId and status IN ('live','closed') and fromOthers = '$type' and parentId!=0 order by creationDate asc";
			//$queryCmd = "select mt1.msgId,mt1.threadId from messageTable as mt1 where mt1.userId=$userId and mt1.mainAnswerId not in ('0', '-1')and mt1.threadId = $newmsgId and mt1.status IN ('live','closed') and mt1.fromOthers = '$type' and mt1.parentId!=0 order by mt1.creationDate desc limit 0,1";
			$queryCmd = "select mt1.msgId,mt1.threadId from messageTable as mt1 where mt1.userId=? and mt1.mainAnswerId > 0 and mt1.threadId = ? and mt1.status IN ('live','closed') and mt1.fromOthers = ? order by mt1.creationDate desc limit 0,1";
			$query = $this->dbHandle->query($queryCmd, array($userId,$newmsgId, $type));
			$res   = $query->row();
			
			$queryCmd1 = "select mt1.userId from messageTable as mt1 where mt1.mainAnswerId not in ('0', '-1')and mt1.threadId = ? and mt1.status IN ('live','closed') and mt1.fromOthers = ? and mt1.parentId!=0 order by mt1.creationDate asc";
			//$queryCmd1 = "select mt1.userId from messageTable as mt1 where mt1.msgId=($res->msgId-1) and mt1.mainAnswerId not in ('0', '-1') and mt1.status IN ('live','closed') and mt1.fromOthers = '$type' and mt1.parentId!=0 order by mt1.creationDate asc";
			$query1 = $this->dbHandle->query($queryCmd1, array($res->threadId, $type));
			
			$res1   = $query1->row();
			$rowNo1 = $query1->num_rows();
			if($rowNo1){
				if($res1->userId==$res->userId){
				    $info = 0;
				}else{
				    $info = 1;
				}
			}else{
				$info = 1;
			}
                }else{
			//Get the owner of the Entity
			$queryCmd = "select userId from messageTable where msgId = ?";
			$queryRes = $this->dbHandle->query($queryCmd, array($newmsgId));
			$newUserId  = $queryRes->result_array();
                }
                
                

                //In case of firstAns and ansQuestion, we will get the points from the tReputationPoints
		/*
                if($action=='ansQuestion' || $action == 'firstAnswer'){
			//Absolute points will be awarded only for the firstAnswer
                        $queryCmd = "SELECT * FROM tuserReputationPoint WHERE userId=?";
                        $queryRes = $this->dbHandle->query($queryCmd, array($userId));
                        $userModule = 'AnA';
                        if ($queryRes->num_rows() == 0)
                        {
                            $userPoint = 10;
                        }else{
                                foreach ($queryRes->result_array() as $row){
                                     $userPoint= round($row['points']);
                                }
                        }
                        if($action == 'firstAnswer'){
                            $userPoint += 5;
                        }
                }
                else{
                        if($action=='answerabusefromfront'){
				$queryCmd = "SELECT * FROM userpointsystemlog WHERE entityId=? and action IN ('firstAnswer','ansQuestion')";
				$queryRes = $this->dbHandle->query($queryCmd, array($newmsgId));
				
                                if ($queryRes->num_rows() == 0)
                                {
                                    return false;
                                }
                                foreach ($queryRes->result_array() as $row){
                                        $userModule = 'AnA';
                                        $userPoint  = -(3*($row['pointvalue']));
                                       
                                }
                                 
                                 //Get the owner of the Answer and also the points assigned to him
                                
                        }else if($action=='republishAnswer'){
				$queryCmd = "SELECT * FROM userpointsystemlog WHERE entityId=? and action IN ('firstAnswer','ansQuestion')";
				$queryRes = $this->dbHandle->query($queryCmd, array($newmsgId));
				if ($queryRes->num_rows() == 0)
				{
				  return false;
				}
				foreach ($queryRes->result_array() as $row){
				       $userModule = 'AnA';
				       $userPoint  = (3*($row['pointvalue']));

				}

                                 //Get the owner of the Answer and also the points assigned to him
                            
                        }elseif($action == 'rejectAbuseAnA'){
				$queryCmd = "SELECT * FROM useractionpointmapping WHERE action=?";
				$queryRes = $this->dbHandle->query($queryCmd, array($action));
				
                                if ($queryRes->num_rows() == 0)
                                {
                                    return false;
                                }
                                foreach ($queryRes->result_array() as $row){
                                        $userModule = $row['modulename'];
                                        $userPoint  = $row['point'];
                                }
                            
                        }elseif($info){
				$queryCmd = "SELECT pointvalue FROM userpointsystemlog WHERE module='Participate' and action=? and entityId=? and userId=? order by timestamp desc limit 0,1";
				$queryRes = $this->dbHandle->query($queryCmd, array($action, $newmsgId, $userId));
				$res   = $queryRes->row();
				$numRow = $queryRes->num_rows();
				if($numRow){
				     $userModule = 'Participate';
				     $userPoint  = $res->pointvalue-1;
				   
				}else{
				     $userModule = 'Participate';
				     $userPoint  = 3;
				}
                                  
                        }else{
                                $queryCmd = "SELECT * FROM useractionpointmapping WHERE action=?";
                                $queryRes = $this->dbHandle->query($queryCmd, array($action));
                                error_log("user point is :".$userPoint);
                                if ($queryRes->num_rows() == 0)
                                {
                                    return false;
                                }
                                foreach ($queryRes->result_array() as $row){
                                        $userModule = $row['modulename'];
                                        $userPoint  = $row['point'];
                                }
                        }
                       
                            
                }
		*/
		$this->benchmark->mark('get_points_start');
		$queryCmd = "SELECT * FROM useractionpointmapping WHERE action=?";
		$queryRes = $this->dbHandle->query($queryCmd, array($action));
		$this->benchmark->mark('get_points_end');
		if ($queryRes->num_rows() == 0)
		{
		    return false;
		}
		foreach ($queryRes->result_array() as $row){
			$userModule = $row['modulename'];
			$userPoint  = $row['point'];
		}
		
		//Ankur Gupta: If a user approved as Expert has negative points, we will assign him extra points
		// Also, if the expert has more than 3500 point, we will not add any more points since he is already an Expert.
                if($action=='registerExpert'){
                        $queryCmd = "SELECT * FROM userpointsystembymodule WHERE modulename='AnA' AND userId = ?";
                        $queryRes = $this->dbHandle->query($queryCmd, array($userId));
                        if ($queryRes->num_rows() > 0)
                        {
                                foreach ($queryRes->result_array() as $row){
                                        $userPointOfUser = $row['userpointvaluebymodule'];
                                }
                                if($userPointOfUser<0){
                                        $userPoint = $userPoint + abs($userPointOfUser);
                                }
				else if($userPointOfUser>=3500){
					return true;
				}
                        }
                }
                
		$userID = $userId;
		$userAction = $action;
		if(intval($userPoint)!=0){
			$this->benchmark->mark('update_total_points_start');
			$this->updateTotalUserPoint($userID, $userPoint);
			$this->benchmark->mark('update_total_points_end');
			$this->benchmark->mark('update_user_points_module_start');
			$this->upadteUserPointByModule($userID, $userPoint, $userModule);
			$this->benchmark->mark('update_user_points_module_end');
		}
		$this->benchmark->mark('make_log_entry_start');
		$this->makeUserPointSystemLogEntry($userID, $userPoint, $userModule, $userAction,$newmsgId);
		$this->benchmark->mark('make_log_entry_end');
		
		//ALso, in case of Thumb up on Answer/Comment, we need to see if this crosses HQ content mark. If yes, update the HQ Content count too.
	/*
		if($action == "answerUpvotes25" || $action == "commentUpvotes25"){
			$this->updateHQContentCount($userID, $userModule);
		}
        else if($action == "answerUpvoteRemoval25" || $action == "commentUpvoteRemoval25"){
                $this->reduceHQContentCount($userID, $userModule);
        }
*/

		//Also, check for the Joining Bonus
		$this->benchmark->mark('update_joining_bonus_start');
		//$this->assignJoiningBonus($userID, $userModule);
		$this->benchmark->mark('update_joining_bonus_end');
		return true;
	}

	function updateUserReputationPoint($userId, $action, $msgId){ 
	 	 
			$this->initiateModel('write');
            $queryCmd = "select userId from messageTable where msgId = ?"; 
            $queryRes = $this->dbHandle->query($queryCmd, array($msgId)); 
            $userIdOfOwner  = $queryRes->result_array(); 

            $levelNo = $this->getLevel($userId); 	
 
            if($levelNo >= 1 && $levelNo <= 5){ 
                    $level = 1; 
            } 
            else if($levelNo >= 6 && $levelNo <= 10){ 
                    $level = 2; 
            }  
            else if($levelNo >= 11 && $levelNo <= 15){ 
                    $level = 3; 
            } 
            if($levelNo >= 16 && $levelNo <= 18){ 
                    $level = 4; 
            } 

	 	                 
            if($action=='receiveThumpUpAnswer' ||  $action=='receiveThumpUpComment' || $action=='removeThumpUp' ){ 
                    $this->tuserReputationPointEntry($userIdOfOwner[0]['userId'], $level*(0.2),$action); 
            } 
                     
 
            else if($action=='receiveThumpDownAnswer' ||  $action == 'receiveThumpDownComment' || $action == 'removeThumpDown' ){ 
                    $this->tuserReputationPointEntry($userIdOfOwner[0]['userId'], $level*(0.1),$action); 
            } 
 
             
            if($action=='answerabusefromfront' || $action=='reportAbuseAccepted' || $action=='deleteQuestion' || $action=='deleteAnswer'){ 
                    $this->tuserReputationPointEntry($userIdOfOwner[0]['userId'], 1,$action); 
            } 
            else if($action=='republishAnswer' || $action=='republishQuestion' || $action=='republishDiscussion'  || $action=='rejectAbuseAnA' ){
               $pastStatus =  $this->checkReportAbuseStatusFromLogTable($userIdOfOwner[0]['userId'], $msgId);
                   if($pastStatus >= 1){
                    	$this->tuserReputationPointEntry($userIdOfOwner[0]['userId'], 1,$action); 
                    }
            }
        } 
	

	function checkReportAbuseStatusFromLogTable($userId, $msgId){
			
			$this->initiateModel('read');
            $queryCmd = "SELECT * FROM userpointsystemlog WHERE userId = ? AND entityId = ? AND action like 'reportAbuse%' AND module = 'AnA'";
            $queryRes = $this->dbHandle->query($queryCmd, array($userId, $msgId));
            $row = $queryRes->num_rows();
			return $row;
	}
	
	function assignJoiningBonus($userID, $userModule){
		//Check if Joining Bonus is assigned
                $this->initiateModel('write');
                $queryCmd = "SELECT 1 FROM userpointsystemlog WHERE userId = ? AND action = 'joiningBonus'";
                $queryRes = $this->dbHandle->query($queryCmd, array($userID));
                if($queryRes->num_rows() > 0){
			return false;
		}
		//Provide Joining Bonus to the user
                $queryCmd = "SELECT * FROM useractionpointmapping WHERE action='joiningBonus'";
                $queryRes = $this->dbHandle->query($queryCmd);
                foreach ($queryRes->result_array() as $row){
                        $userModule = $row['modulename'];
                        $userPoint  = $row['point'];
                }
		$this->updateTotalUserPoint($userID, $userPoint);
		$this->upadteUserPointByModule($userID, $userPoint, $userModule);
		$this->makeUserPointSystemLogEntry($userID, $userPoint, $userModule, 'joiningBonus','');

                $notificationLib =  $this->load->library('Notifications/NotificationContributionLib');
                $levelNo = $this->getLevel($userID);
		$levelName = $this->getLevelNameFromLevelId($levelNo);
                $notificationLib->addJoiningBonusNotificationsToRedis($userPoint, $levelName, $userID);
                return true;
	}
	
	function isProfileEntryPresent($userId,$action){
                $this->initiateModel('write');
                $queryCmd = "SELECT 1 FROM userpointsystemlog WHERE userId = ? AND action = ?";
                $queryRes = $this->dbHandle->query($queryCmd, array($userId,$action));
                $row = $queryRes->num_rows();
		if($row > 0){
			return true;
		}
		else{
			return false;
		}
	}

	function getNumberOfFollowers($entityId, $entityType){
		
		$this->initiateModel('write');
		$queryCmd = "SELECT count(*) as cc FROM tuserFollowTable WHERE entityId = ? AND entityType = ? AND status = 'follow'";
		
		$queryRes = $this->dbHandle->query($queryCmd, array($entityId, $entityType));

		$row = $queryRes->row();
		return $row->cc;
	}

	function getNumberOfUpvotes($entityId, $entityType){
		$this->initiateModel('write');
		if($entityType == 'Answer'){
			$queryCmd = "SELECT digUp FROM messageTable WHERE msgId = ? AND status IN ('live','closed')";
		}
		else{
			$queryCmd = "SELECT count(*) as digUp FROM digUpUserMap WHERE productId = ? AND digFlag = 1 AND digUpStatus = 'live'";
		}
		$queryRes = $this->dbHandle->query($queryCmd, array($entityId));
		$row = $queryRes->row();
		return $row->digUp;
	}

	function getNumberOfAbuses($userId){
		$this->initiateModel('write');
		$queryCmd = "SELECT count(*) as cc FROM userpointsystemlog WHERE userId = ? AND module = 'AnA' AND action like 'reportAbuseFirstTime'";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
		$row = $queryRes->row();
		return $row->cc;
	}
	
	
	function getPointsAwarded($userId, $newmsgId){
		$this->initiateModel('write');
		$queryCmd = "SELECT pointvalue FROM userpointsystemlog WHERE userId = ? AND entityId = ? LIMIT 1";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId, $newmsgId));
		$row = $queryRes->row();
		return $row->pointvalue;		
	}
	
	/**
	 * Function to get the user Reputation Points
	 *
	 *  @param object $dbHandleSent
	 *  @param integer $userId
	 *
	 */
         function getUserReputationPoints($dbHandleSent,$userId){
             if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
             $queryCmd = "select previouspoints,points from tuserReputationPoint where userId = ?";
             $queryRes = $this->dbHandle->query($queryCmd, array($userId));
             $reputationPoints  = $queryRes->result_array();
             $rank = $this->calculateRankByRepuationPoints($dbHandle,$userId);
             $res = array();
             if ($queryRes->num_rows() > 0){
             if($reputationPoints[0]['points']){
                 $res['rank'] = $rank;
                 $res['reputationPoints'] = $reputationPoints[0]['points'];
                 $res['difference'] = $reputationPoints[0]['points']-$reputationPoints[0]['previouspoints'];
             }else{
               $res['rank'] = '0';
               $res['reputationPoints'] = 0;
               $res['difference'] = -$reputationPoints[0]['previouspoints'];
             }
             }else{
               $res['rank'] = '0';
               $res['reputationPoints'] = '9999999';
               $res['difference'] = '0';
             }
             return $res;
         }
         
	 /**
	  * Update the user Previous Points
	  *
	  * @param integer $userId
	  * @param object $dbHandle
	  *
	  */
	function tuserReputationPreviousPointEntry($userId,$dbhandle=''){
                
			$this->initiateModel('write');
		

                $queryCmd = "UPDATE tuserReputationPoint SET previouspoints = points";
                $queryRes = $this->dbHandle->query($queryCmd);
                return true;
        }
        
	/**
	 * Function to insert / update the user points entry
	 *
	 * @param integer $userId
	 * @param integer $point
	 * @param string $action
	 * @param object $handle
	 * @param integer $percentage
	 */
        function tuserReputationPointEntry($userId, $point,$action,$dbhandle='',$percentage=0){
                
			$this->initiateModel('write');
		
                // update in userpointsystem the older one.
                $queryCmd = "select userId, points from tuserReputationPoint where userId = ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
                if ($queryRes->num_rows() > 0)
                {
					//In case of Ten days inactivity, we will now reduce a percent of the point depending on the value sent
					if($action=='tenDaysInactive'){
							$row = $queryRes->row();
							if($percentage>0)
								$point = (intval($row->points) * $percentage) / 100;
							else
								$point = 0;
					}
					//End changes
                    if($action=='receiveThumpUpAnswer' || $action == 'receiveThumpUpComment' || $action == 'removeThumpDown'){ 
                        $queryCmd = "UPDATE tuserReputationPoint SET points = points+($point) WHERE userId=".$this->dbHandle->escape($userId);
                    }
                    else if($action=='receiveThumpDownAnswer' || $action == 'receiveThumpDownComment'  || $action == 'removeThumpUp'){ 
                        $queryCmd = "UPDATE tuserReputationPoint SET points = points-($point) WHERE userId=".$this->dbHandle->escape($userId);
                    }
                    else if($action=='answerabusefromfront' || $action=='reportAbuseAccepted'){ 
	 	 				$queryCmd = "UPDATE tuserReputationPoint SET points = points-($point) WHERE userId=".$this->dbHandle->escape($userId); 
	 	 
	 	            } 
                   else if($action=='republishAnswer' || $action=='republishQuestion' || $action=='republishDiscussion'  || $action=='rejectAbuseAnA'){ 

                        $queryCmd = "UPDATE tuserReputationPoint SET points = points+($point) WHERE userId=".$this->dbHandle->escape($userId); 
	 	 			} 
				else
                    	$queryCmd =''; 
               
                
                }else{
                    if($action=='receiveThumpUpAnswer' || $action=='receiveThumpUpComment'  || $action == 'removeThumpDown' ){ 
                        $finalPoint = 10+$point;
                    }else if($action=='receiveThumpDownAnswer' || $action=='receiveThumpDownComment' || $action=='answerabusefromfront'  || $action=='reportAbuseAccepted' || $action=='deleteAnnouncement'  || $action == 'removeThumpUp'){ 
                        $finalPoint = 10-$point;
                    }
                    else if($action=='deleteAnswer' || $action=='deleteQuestion' || $action=='deleteAnnouncement'){
                    	$finalPoint = 10;
                    }
                    $queryCmd = "INSERT INTO tuserReputationPoint (`userId`,`points`) VALUES  (".$this->dbHandle->escape($userId).",".$this->dbHandle->escape($finalPoint).") ";
                }
                if($queryCmd != '')
               $queryRes = $this->dbHandle->query($queryCmd);
				//In case of Ten days inactivity, if the points is less than 10, we will make it as 10
		/*	if($action=='tenDaysInactive'){
						$queryCmdA = "select userId, points from tuserReputationPoint where userId = ?";
						$queryResA = $this->dbHandle->query($queryCmdA, array($userId));
						$row = $queryResA->row();
						$point = $row->points;
						if($point<10){
	                        $queryCmdA = "UPDATE tuserReputationPoint SET points = 10 WHERE userId=?";
			                $queryResA = $this->dbHandle->query($queryCmdA, array($userId));
						}
				}
				
				*/
				//End changes
			$userNewReputationPoints = $this->getUserReputationPoints($dbhandle, $userId);
			if($userNewReputationPoints['reputationPoints'] <= 0){
				$this->checkIfReputationIndexIsZero($userId);
                
   }

		return $action;
	}
	
	//Function to check if reputation index is decreased to zero, if yes send a mail to Moderation.
	function checkIfReputationIndexIsZero($userId){
		$this->initiateModel('write');
		$queryCmd = "select t1.userid, t1.email, t1.displayname, t1.firstname, t1.lastname from tuser t1 where t1.userid=?";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
        $data     = $queryRes->result_array();
		$userProfileLink = site_url('getUserProfile').'/'.$data[0]['displayname'];
	;
		$subject = $data[0]['displayname']." Reputation index is below/equal to zero now";
		$mailBody = "The Reputation index of the following user is equal to/below zero and would not be able to access any content on Shiksha Q&A. Please verify if it’s a genuine case of spammer.<br/>
			User details<br/>
			User Id :".$data[0]['userid']." <br/>
			Profile link :<a href=".$userProfileLink."> $userProfileLink</a><br/>
			E-mail :" .$data[0]['email']." <br/>
			Name :" .$data[0]['firstname'].' '.$data[0]['lastname'];
		$emails = array('moderator@shiksha.com', 'k.akhil@shiksha.com');
		$this->load->library('alerts_client');
		$alertClient = new Alerts_client();
        for($i=0; $i<count($emails); $i++)
			{
        	$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emails[$i], $subject, $mailBody, "html", '');
   		}

	}


	/**
	 * Function to update the user Points values
	 *
	 *  @param integer $userId
	 *  @param integer $point
	 */
        Private function updateTotalUserPoint($userId, $point){
		$this->initiateModel('write');
		// update in userpointsystem the older one.
		$queryCmd = "UPDATE userPointSystem SET userPointValue = userPointValue+($point) WHERE userId=?";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));	
		return true;
	}
	
	/**
	 * Function to update the user Points values by modules
	 *
	 *  @param integer $userId
	 *  @param integer $point
	 *  @param string $modulename
	 */
	Private function upadteUserPointByModule($userId, $point, $modulename){
		$this->initiateModel('write');
		//First check if the user entry exists in the table
		$queryCmd = "select userpointvaluebymodule from userpointsystembymodule where modulename = 'AnA' and userId = ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
		if($queryRes->num_rows() <= 0){
			if($point>=25 && $modulename=='AnA'){
                                $IsSendMail = true;
			}
		}
	
		//If it is a level promotion, then set the Last upgraded time
		$queryCmd = "select ups.userpointvaluebymodule oldPoints, (ups.userpointvaluebymodule+$point) totalPoints, HQContentCount from userpointsystembymodule ups where ups.modulename = 'AnA' and ups.userId = ? having (oldPoints<25 and totalPoints>=25) OR (oldPoints<50 and totalPoints>=50) OR (oldPoints<100 and totalPoints>=100) OR (oldPoints<200 and totalPoints>=200) OR (oldPoints<400 and totalPoints>=400) OR (oldPoints<700 and totalPoints>=700) OR (oldPoints<1150 and totalPoints>=1150) OR (oldPoints<1750 and totalPoints>=1750) OR (oldPoints<2500 and totalPoints>=2500) OR (oldPoints<3500 and totalPoints>=3500 and HQContentCount>=100) OR (oldPoints<5000 and totalPoints>=5000 and HQContentCount>=100) OR (oldPoints<7500 and totalPoints>=7500 and HQContentCount>=100) OR (oldPoints<11500 and totalPoints>=11500 and HQContentCount>=100) OR (oldPoints<17500 and totalPoints>=17500 and HQContentCount>=100) OR (oldPoints<27500 and totalPoints>=27500 and HQContentCount>=250) OR (oldPoints<42500 and totalPoints>=42500 and HQContentCount>=500) OR (oldPoints<67500 and totalPoints>=67500 and HQContentCount>=1000)";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
                if ($queryRes->num_rows() > 0)
                {
                        $IsSendMail = true;
                }

		//Now, set the total points in the table
		$queryCmd = "INSERT INTO userpointsystembymodule (`userId`,`userpointvaluebymodule`,`modulename`,`lastLevelUpgradedTime`) VALUES (?, ?, ?,'0000-00-00 00:00:00') ON DUPLICATE KEY UPDATE userpointvaluebyModule=userpointvaluebyModule+($point)";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId, $point, $modulename));

		//Also, send the mail to the user for Congratulations on Level promotion
		if($IsSendMail){
			$this->upgradeLevel($userId,$modulename);
		}

		//If it is a level demotion, then unset the Last upgraded time, Update LevelId, Level Name
		$queryCmd = "select (ups.userpointvaluebymodule-($point)) oldPoints, (ups.userpointvaluebymodule) totalPoints, HQContentCount from userpointsystembymodule ups where ups.modulename = ? and ups.userId = ? having (oldPoints>=25 and totalPoints<25) OR (oldPoints>=50 and totalPoints<50) OR (oldPoints>=100 and totalPoints<100) OR (oldPoints>=200 and totalPoints<200) OR (oldPoints>=400 and totalPoints<400) OR (oldPoints>=700 and totalPoints<700) OR (oldPoints>=1150 and totalPoints<1150) OR (oldPoints>=1750 and totalPoints<1750) OR (oldPoints>=2500 and totalPoints<2500) OR (oldPoints>=3500 and totalPoints<3500) OR (oldPoints>=5000 and totalPoints<5000) OR (oldPoints>=7500 and totalPoints<7500) OR (oldPoints>=11500 and totalPoints<11500) OR (oldPoints>=17500 and totalPoints<17500) OR (oldPoints>=27500 and totalPoints<27500) OR (oldPoints>=42500 and totalPoints<42500) OR (oldPoints>=67500 and totalPoints<67500)";
		$queryRes = $this->dbHandle->query($queryCmd, array($modulename, $userId));
		if ($queryRes->num_rows() > 0)
		{
			$this->downgradeLevel($userId,$modulename);
		}
		
		return true;
	}

	/**
	 * Function to Upgrade User level in the Table. This will update LevelId, LevelName and LastUpgradedTime in the DB
	 *
	 *  @param integer $userId
	 *  @param string $modulename
	 */
	function upgradeLevel($userId,$modulename){
                $pointsArray = $this->getPointsAndHQ($userId);
                $levelNo = $this->getLevelFromPoints($pointsArray['points'],$pointsArray['HQCount']);
                $levelName = $this->getLevelNameFromLevelId($levelNo);
                $this->initiateModel('write');
                //In case of Level promotion, update the UpgradeTime, LevelId & Name
                $queryCmd = "UPDATE userpointsystembymodule ups SET levelId = ?, levelName = ? where ups.userId = ? and ups.modulename = ?";
                $queryRes = $this->dbHandle->query($queryCmd, array($levelNo, $levelName, $userId, $modulename));

                if($levelNo > 2){
                        $queryCmd = "UPDATE userpointsystembymodule ups SET ups.lastLevelUpgradedTime=NOW() where ups.userId = ? and ups.modulename = ?";
                        $queryRes = $this->dbHandle->query($queryCmd, array($userId, $modulename));
                }

                if($modulename == 'AnA'){
		        	$notificationLib =  $this->load->library('Notifications/NotificationContributionLib');
		        	$notificationLib->addLevelUpNotificationsToRedis($levelNo,$levelName,$userId);	
		        
                		//Send Upgrade Mailer
                $this->sendUpgradeMail($userId);
				$this->sendUpgradeMailToInternalTeam($userId);
		}

				// re-index user
				$data = array(
					'userId' => $userId,
					'queueTime' => date('Y-m-d H:i:s'),
					'status' => 'queued'
				);
				$this->dbHandle->insert('tuserIndexingQueue',$data);
	}
	
	/**
	 * Function to Downgrade User level in the Table. This will update LevelId, LevelName and LastUpgradedTime in the DB
	 *
	 *  @param integer $userId
	 *  @param string $modulename
	 */
	function downgradeLevel($userId,$modulename){
		$levelNo = $this->getLevel($userId) - 1;
		$levelName = $this->getLevelNameFromLevelId($levelNo);
		$this->initiateModel('write');
		//In case of Level promotion, update the UpgradeTime, LevelId & Name
		$queryCmd = "UPDATE userpointsystembymodule ups SET ups.lastLevelUpgradedTime=NULL, levelId = ?, levelName = ? where ups.userId = ? and ups.modulename = ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($levelNo, $levelName, $userId, $modulename));

		// re-index user
		$data = array(
			'userId' => $userId,
			'queueTime' => date('Y-m-d H:i:s'),
			'status' => 'queued'
		);
		$this->dbHandle->insert('tuserIndexingQueue',$data);
	}
	
	/**
	 * Function to find Level name from Level Id
	 *
	 *  @param integer $levelId
	 */
	function getLevelNameFromLevelId($levelNo){
		$this->load->helper('messageBoard/abuse');
		return getLevelNameFromLevelId($levelNo);		
	}
	
	/**
	 * Function to Send Mailer for Upgrade User level 
	 *
	 *  @param integer $userId
	 */
	function sendUpgradeMail($userId){
		$this->initiateModel('write');
		$queryCmd = "select t1.email, t1.displayname,levelName level from tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where t1.userid=?";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
		$row = $queryRes->row();
		$fromMail = "noreply@shiksha.com";
		$subject = "Congratulations! You have been promoted by the Community.";
		$contentArr = array();
		$contentArr['receiverId'] = $userId;
		if($levelId <= 10){
		  $urlOfLandingPage = SHIKSHA_ASK_HOME;
		}
		else{
		  $urlOfLandingPage = SHIKSHA_HOME."/messageBoard/MsgBoard/advisoryBoard";
		}
		$email = $row->email;
		//$autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage);
		$contentArr['type'] = 'promotionMail';
		$contentArr['level'] = $row->level;
		$contentArr['username'] = $row->displayname;
		$contentArr['NameOfUser'] = $row->displayname;
		$contentArr['link'] = $urlOfLandingPage;
		$contentArr['mail_subject'] = $subject;
		Modules::run('systemMailer/SystemMailer/userLevelPromotion', $email, $contentArr);
		
	}
	
	/**
	 * Function to Update HQ Content count for a user in the DB. For this, we have checked if any of his content has received 100 Likes, we will update the Content Count. This might also result in upgradation of User Level
	 *
	 *  @param integer $userId
	 *  @param string $modulename
	 */
	function updateHQContentCount($userId, $moduleName){
		$this->initiateModel('write');
		//Check If it is a level promotion, then set the Last upgraded time
		$levelUpgrade = false;
		$queryCmd = "select ups.userpointvaluebymodule points, (HQContentCount+1) CC from userpointsystembymodule ups where ups.modulename = ? and ups.userId = ? having (points>=3500 and  CC=100) OR (points>=27500 and CC=250) OR (points>=42500 and CC=500) OR (points>=67500 and CC=1000)";
		$queryRes = $this->dbHandle->query($queryCmd, array($moduleName, $userId));
                if ($queryRes->num_rows() > 0)
                {
                        $levelUpgrade = true;
                }

		//Now, set the total points in the table
		$queryCmd = "UPDATE userpointsystembymodule SET HQContentCount = (HQContentCount+1) WHERE userId = ? and moduleName = ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId, $moduleName));

		//Also, send the mail to the user for Congratulations on Level promotion
		if($levelUpgrade){
			$this->upgradeLevel($userId,$moduleName);
		}
	}

        /**
         * Function to Reduce HQ Content count for a user in the DB. For this, we have checked if any of his content had received 25 Likes, and has just removed a like. Due to this, the HQ Content count might get reduced by 1. Also, this might affect the level of the user
         *
         *  @param integer $userId
         *  @param string $modulename
         */
        function reduceHQContentCount($userId, $moduleName){
                $this->initiateModel('write');

                //If it is a level demotion, then unset the Last upgraded time, Update LevelId, Level Name
                $levelDowngrade = false;
        		$queryCmd = "select ups.userpointvaluebymodule points, (HQContentCount) CC from userpointsystembymodule ups where ups.modulename = ? and ups.userId = ? having (points>=3500 and  CC=100) OR (points>=27500 and CC=250) OR (points>=42500 and CC=500) OR (points>=67500 and CC=1000)";
                $queryRes = $this->dbHandle->query($queryCmd, array($moduleName, $userId));
                if ($queryRes->num_rows() > 0)
                {
                        $levelDowngrade = true;
                }

                //Now, set the total points in the table
                $queryCmd = "UPDATE userpointsystembymodule SET HQContentCount = (HQContentCount-1) WHERE userId = ? and moduleName = ?";
                $queryRes = $this->dbHandle->query($queryCmd, array($userId, $moduleName));

                if($levelDowngrade){
                        $this->downgradeLevel($userId,$moduleName);
                }
        }
	
	/**
	 * Function to insert data into user Point system Log
	 *
	 * @param integer $userId
	 * @param integer $point
	 * @param string $module
	 * @param string $action
	 * @param integer $threadId 
	 *
	 */
	Private function makeUserPointSystemLogEntry($userId, $point, $module, $action,$threadId){
		$this->initiateModel('write');
		$queryCmd = "INSERT INTO userpointsystemlog (userId,pointvalue,action,module,timestamp,entityId) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP,?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId, $point, $action, $module, $threadId));
		return true;
	}
	
	
	/**
	 * Funciton to add new user(Quick Registration)
	 *
	 * @param array $userarray
	 * @param integer $secCode
	 * @param string $secCodeIndex
	 * @param boolean $secCodeChecked
	 */
	function doQuickRegistration($userarray,$secCode=0,$secCodeIndex="default",$secCodeChecked=false){
        $appID = 1;
		$this->load->library('Register_client');
        $register_client = new Register_client();
        //Display Name
        $responseCheckAvail = $register_client->checkAvailability($appId,$userarray['displayname'],'displayname');
        while($responseCheckAvail == 1){
            $userarray['displayname'] = $userarray['displayname'].rand(10001,99999);
            $responseCheckAvail = $register_client->checkAvailability($appId,$userarray['displayname'],'displayname');
        }
        //Ends
        $userarray['appId'] = $appID;
	
	if(!isset($userarray['tracking_keyid']) && $userarray['tracking_keyid'] == ''){
		$userarray['tracking_keyid'] = isset($_POST['tracking_keyid']) ? $this->input->post('tracking_keyid', true) : 0;
	}
        //$bypass = $this->input->post('b_1_p'); // Removing after discussion with Neha

        /*
	Validate email & mobile number [if JS failed]
	if($userarray['email'] == '' || $userarray['displayname'] == '' || $userarray['password'] == '')
        {
            return "Blank" ;
        }
        */
	
if ((validateEmailMobile('email',$userarray['email']) == false) && (validateEmailMobile('mobile',$userarray['mobile'])
== false))
	{
	    return "Blank" ;
	    exit;
	}
        else
        {
            if(verifyCaptcha($secCodeIndex,$secCode, 1) || ($secCodeChecked == true))
            {
                $addResult = $register_client->adduser_new($userarray);
                if($addResult['status'] > 0)
                {
                    //$Validate = $this->checkUserValidation();
                    //if(!isset($Validate[0]['userid'])){
                        $value = $userarray['email'].'|'.$userarray['ePassword'];
                        $this->cookie($value);
                    //}
					
					$_COOKIE['user'] = $value;
			        $this->load->library('common/CookieBannerTrackingLib');
			        $this->cookieBanner = new CookieBannerTrackingLib();
			        $this->cookieBanner->newUserCookieSet();

                    $this->load->library('user/UserLib');
                    $userLib = new UserLib;
                    $userLib->sendEmailsOnRegistration($addResult['status'], array(), $userarray['password']);
					$userLib->updateUserData($addResult['status']);
					
                    return $addResult['status'];

                }
                else
                {
                    if($addResult['status'] == -1)
                    {
                        if($addResult['email'] == -1 && $addResult['displayname'] == -1)
                            return 'both';
                        else    
                        {
                            if($addResult['email'] == -1)
                                return 'email';
                            if($addResult['displayname'] == -1)
                                return 'displayname';
                        }

                    }
                    else
                        return 0;
                }
            }else{
                return 'code';
            }
        }
    }


    /**
     * Select Question based on category
     *
     * @param integer $userId
     * @param object $dbhandle
     */
       function getCategoryBasedQuestion($userId,$dbhandle=''){
                if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
                $userExpertize = array();
                $userQuestion = array();
 		$queryCmd = "select mc1.categoryId,count(*) answerCount, c1.name, c1.boardId from messageTable m1, messageCategoryTable mc1, categoryBoardTable c1 where m1.userId=? and m1.fromOthers='user' and m1.status IN ('live','closed') and m1.threadId = mc1.threadId and m1.parentId!=0 and m1.mainAnswerId=0 and mc1.categoryId=c1.boardId and c1.boardId > 1 and c1.boardId < 20 group by c1.name order by answerCount desc limit 2";
 		$query = $this->dbHandle->query($queryCmd, array($userId));
 		foreach ($query->result_array() as $rowTemp)
 		array_push($userExpertize,array($rowTemp,'struct'));

                foreach($userExpertize as $categoryId){
                    $queryCmd = "select mt.msgTxt,mt.threadId from messageTable as mt join messageCategoryTable mct on (mct.threadId = mt.threadId) and mct.categoryId=? and mt.fromOthers='user' and mt.status IN ('live','closed')and mt.parentId=0 and mt.mainAnswerId=-1 and userId!=? order by mct.threadId desc limit 0,2";
                    $query = $this->dbHandle->query($queryCmd, array($categoryId[0][categoryId], $userId));
                    foreach ($query->result_array() as $rowTemp1)
                    array_push($userQuestion,array($rowTemp1,'struct'));
                    return $userQuestion;

               }
                
               return true;
       }
	
    /**
     * Function to set cookie
     *
     * @param string $value
     */
    function cookie($value)
    {
        $value .= '|pendingverification';
        setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
    }
	
	/**
	 * Function to send mail to power user
	 *
	 * @param integer $newLevelOfUser
	 * @param integer $userId
	 */
    function sendPowerUserMailer($newLevelOfUser,$userId ){error_log("aclmodelquery inside sendPowerUserMailer===");
		if(!is_resource($dbHandleSent)){
					$this->initiateModel();
				}else{
					$this->dbHandle = $dbHandleSent;
				}
		$this->load->library('mailerClient');
		$this->load->library('alerts_client');
		$MailerClient = new MailerClient();
		$mail_client = new Alerts_client();
		$queryCmd = "select displayname,email,firstname from tuser where userId={".$this->dbHandle->escape($userId)."}";
		$query = $this->dbHandle->query($queryCmd);
		$res = $query->row();
		$email = $res->email;
		$fromMail = "noreply@shiksha.com";
		$ccmail = "";
		$subject="You have been chosen as a power user by Shiksha.com moderators";
		$contentArr['NameOfUser'] = ($res->firstname=='')?$res->displayname:$res->firstname;
		$contentArr['type'] = 'makePowerUser';
		$content = $this->load->view("search/searchMail",$contentArr,true);
		$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
	}

        function migrateOldPointsToNewSystem(){
                $this->initiateModel('write');

                //First fetch all the users from userpointsystembymodule Table
                $queryCmd = "select * from userpointsystembymodule where modulename = 'AnA' AND levelId = 1";
                $queryRes = $this->dbHandle->query($queryCmd);
                $result = $queryRes->result_array();
                foreach ($result as $row){
                        $HQContentCount = 0;
                        $userId = $row['userId'];
                        switch(true){
                                case  ($row['userpointvaluebymodule'] < 250):   $newScore = 10;
                                                                                $levelId = 1;
                                                                                $levelName = 'Beginner-Level 1';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 1000):  $newScore = 20;
                                                                                $levelId = 2;
                                                                                $levelName = 'Beginner-Level 2';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 1750):  $newScore = 60;
                                                                                $levelId = 3;
                                                                                $levelName = 'Beginner-Level 3';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 2500):  $newScore = 110;
                                                                                $levelId = 4;
                                                                                $levelName = 'Beginner-Level 4';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 3750):  $newScore = 220;
                                                                                $levelId = 5;
                                                                                $levelName = 'Beginner-Level 5';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 5000):  $newScore = 420;
                                                                                $levelId = 6;
                                                                                $levelName = 'Contributor-Level 6';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 7500):  $newScore = 720;
                                                                                $levelId = 7;
                                                                                $levelName = 'Contributor-Level 7';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 10000):         $newScore = 950;
                                                                                $levelId = 7;
                                                                                $levelName = 'Contributor-Level 7';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 15000):         $newScore = 1170;
                                                                                $levelId = 8;
                                                                                $levelName = 'Contributor-Level 8';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 20000):         $newScore = 1500;
                                                                                $levelId = 8;
                                                                                $levelName = 'Contributor-Level 8';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 40000):         $newScore = 1800;
                                                                                $levelId = 9;
                                                                                $levelName = 'Contributor-Level 9';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 60000):         $newScore = 2000;
                                                                                $levelId = 9;
                                                                                $levelName = 'Contributor-Level 9';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 80000):         $newScore = 2200;
                                                                                $levelId = 9;
                                                                                $levelName = 'Contributor-Level 9';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 100000):        $newScore = 2400;
                                                                                $levelId = 9;
                                                                                $levelName = 'Contributor-Level 9';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 250000):        $newScore = 2600;
                                                                                $levelId = 10;
                                                                                $levelName = 'Contributor-Level 10';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 400000):        $newScore = 2800;
                                                                                $levelId = 10;
                                                                                $levelName = 'Contributor-Level 10';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 550000):        $newScore = 3000;
                                                                                $levelId = 10;
                                                                                $levelName = 'Contributor-Level 10';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 700000):        $newScore = 3200;
                                                                                $levelId = 10;
                                                                                $levelName = 'Contributor-Level 10';
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 1200000):       $newScore = 3600;
                                                                                $levelId = 11;
                                                                                $levelName = 'Guide-Level 11';
                                                                                $HQContentCount = 25;
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 1700000):       $newScore = 3900;
                                                                                $levelId = 11;
                                                                                $levelName = 'Guide-Level 11';
                                                                                $HQContentCount = 25;
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 2200000):       $newScore = 4200;
                                                                                $levelId = 11;
                                                                                $levelName = 'Guide-Level 11';
                                                                                $HQContentCount = 25;
                                                                                break;
                                case  ($row['userpointvaluebymodule'] < 2700000):       $newScore = 4500;
                                                                                $levelId = 11;
                                                                                $levelName = 'Guide-Level 11';
                                                                                $HQContentCount = 25;
                                                                                break;
                                case  ($row['userpointvaluebymodule'] >= 2700000):      $newScore = 4800;
                                                                                $levelId = 11;
                                                                                $levelName = 'Guide-Level 11';
                                                                                $HQContentCount = 25;
                                                                                break;
                        }
                        $queryCmdUpdate = "UPDATE userpointsystembymodule SET userpointvaluebymodule = ?, levelId = ?, levelName = ?, HQContentCount = ?, lastLevelUpgradedTime = NULL WHERE userId = ? AND modulename = 'AnA'";
                        $queryUpdate = $this->dbHandle->query($queryCmdUpdate,array($newScore,$levelId,$levelName,$HQContentCount,$userId));

                }

                return true;
        }

	function sixtyDaysInactivity(){
                $this->initiateModel();
		$today = date("Y-m-d");
		$pastDay = strtotime("-60 days",strtotime($today));
		$pastDay = date ('Y-m-j' , $pastDay);

		//Find all users who are Experts
                $queryCmdExpert = "SELECT DISTINCT userId,levelId,userpointvaluebymodule FROM userpointsystembymodule WHERE levelId >= 11 AND modulename = 'AnA'";
                $queryResExpert = $this->dbHandle->query($queryCmdExpert);
                $resultExpert = $queryResExpert->result_array();
                foreach ($resultExpert as $row){
			
			//First, check that he has not got the inactivity impact in past 60 days
			$queryCmd = "SELECT * FROM userpointsystemlog WHERE userId = ? AND action = 'inactivePenalty' AND timestamp >= '$pastDay'";
			$queryRes = $this->dbHandle->query($queryCmd, array($row['userId']));
			$result = $queryRes->num_rows();
			
			if($result == 0){
				//Now, for each of this user, find if he has been inactive for past 60 Days.
				$queryCmdActivity = "SELECT msgId,creationDate FROM messageTable WHERE creationDate >= '$pastDay' AND userId = ? AND status IN ('live','closed') AND fromOthers IN ('user','discussion')";
				$queryResActivity = $this->dbHandle->query($queryCmdActivity, array($row['userId']));
				$resultActivity = $queryResActivity->num_rows();
				
				if($resultActivity == 0){
					//If yes, decrease his Level by 1 and his Level Name and his lastLevelUpgradedTime
					//Get the Upper limit of this Level and set the Points to this
					$this->load->helper('messageBoard/abuse');
					$rangeArray = getPointRangeForLevel($row['levelId'] - 1);
					$upperLimit = $rangeArray['upperLimit'];		
					$userPoint = $upperLimit - $row['userpointvaluebymodule'];

		                        $this->updateTotalUserPoint($row['userId'], $userPoint);
                		        $this->upadteUserPointByModule($row['userId'], $userPoint, 'AnA');
		                        $this->makeUserPointSystemLogEntry($row['userId'], $userPoint, 'AnA', 'inactivePenalty');
				}
				
			}
		}
	}
	
	
	/**
	 * Function to Send Mailer for to internal team tUpgrade User level 
	 *
	 *  @param integer $userId
	 */
	function sendUpgradeMailToInternalTeam($userId){

		$queryCmd = "select t1.userid,t1.email,t1.firstname,lastname,t1.mobile,t1.displayname,levelName level,levelId from tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where t1.userid=?";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
		$row = $queryRes->row();
		
		if($row->levelId >=3){
			$levelName = $row->level;
			$userName = $row->displayname;
			$fname = $row->firstname;
			$lname = $row->lastname;
			$userId = $row->userid;
			$email = $row->email;
			$mobileNo = $row->mobile;
			$fromMail = "noreply@shiksha.com";
			$subject = $fname." got promoted to ".$levelName;
			$emails = array('mudit.pandey@shiksha.com', 'k.akhil@shiksha.com');
			$userProfileLink = SHIKSHA_HOME.'/getUserProfile/'.$userName;
			$contentArr = '<p>Hi,</p>
			<p>'.$fname.' has got promoted to '.$levelName.'.'.'User details are
			</p>
			<p>&nbsp;</p>
			<div>
			<table>
			<tr>
				<td>Name :</td>
				<td>'.$fname.' '.$lname.'</td>
			</tr>
			<tr>
				<td>EmailId :</td>
				<td>'.$email.'</td>
			</tr>
			<tr>
				<td>Mobile Number :</td>
				<td>'.$mobileNo.'</td>
			</tr>
			<tr>
				<td>User profile link :</td>
				<td><a href="'.$userProfileLink.'" target="_blank">'.$userProfileLink.'</td>
			</tr>
			<tr>
				<td>User Id :</td>
				<td>'.$userId.'</td>
			</tr>
			</table>
			</div>
			<p>&nbsp;</p>
			<p>Best wishes,</p>
			<p>Shiksha.com</p>';
			
			$this->load->library('alerts_client');
			$alertClient = new Alerts_client();
	  
			for($i=0; $i<count($emails); $i++)
			{
				$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emails[$i], $subject, $contentArr, "html", '');
			}
		}
		
	}


	function getCountOfQuesPostedByUser($userId){
	 	if(!empty($userId)){
		 	$this->initiateModel();
	        $queryCmd = "SELECT count(*) as count from  messageTable where userId = ? and fromOthers = 'user' and parentId = 0 and status in ('live', 'closed')";
	        $queryRes = $this->dbHandle->query($queryCmd, array($userId,$newmsgId, $type));
	        $row = $queryRes->row();
			return $row->count;
		}else {
			return 0;
		}
	}

	function getUsersFromUserPointSys($limit){
		//code not in use
	 	$this->initiateModel();
		$queryCmd = "select userId from userpointsystembymodule where modulename = 'AnA' $limit";
        $queryRes = $this->dbHandle->query($queryCmd);
		$users  = $queryRes->result_array();
		return $users;
	}

	function getUserDigUpScore($userIdArr){
		$this->initiateModel();
	 	$queryCmd = "SELECT mt.userId, sum(mt.digUp) as HQContentCount from messageTable mt, messageTable mt1 where mt.parentId = mt.threadId AND mt.fromOthers = 'user' and mt.status = 'live' and mt1.msgId = mt.threadId and mt1.status in ('live' , 'closed') group by mt.userId having HQContentCount > 0";
	    $queryRes = $this->dbHandle->query($queryCmd);
		$usersDigUpScore  = $queryRes->result_array();
		return $usersDigUpScore;
	}

	function updateUserDataInTable($userDataArr){
		if(!is_array($userDataArr) || empty($userDataArr)){
		 	return;
		}
		$this->initiateModel('write');
	    $this->dbHandle->trans_start();
		$this->dbHandle->where('modulename','AnA');
	 	$this->dbHandle->update_batch('userpointsystembymodule', $userDataArr, 'userId'); 
	    $this->dbHandle->trans_complete();
	    return ($this->dbHandle->trans_status() === FALSE) ? false : true;
	}

	function getUserswithMoreThan5Ques(){
		$this->initiateModel();
	 	$queryCmd = "SELECT count(*) cc , userId from messageTable where parentId = 0 and fromOthers = 'user' and status in ('live', 'closed') group by userId having cc > 5";
	    $queryRes = $this->dbHandle->query($queryCmd);
		$usersWithQues  = $queryRes->result_array();
		return $usersWithQues;
	}

	function getPointsOfUserswithMoreThan5Ques($allUserIds){
		if(empty($allUserIds)){
			return;
		}
		$this->initiateModel();
	 	$queryCmd = "SELECT userpointvaluebymodule, userId from userpointsystembymodule where modulename = 'AnA' and userId in (?)";
	    $queryRes = $this->dbHandle->query($queryCmd,array($allUserIds));
		$usersPoints  = $queryRes->result_array();
		return $usersPoints;
	}


	function getUsersOfHigherLevel(){
		$this->initiateModel();
	 	$queryCmd = "SELECT userId from userpointsystembymodule where modulename = 'AnA' and userpointvaluebymodule >= 3500";
	    $queryRes = $this->dbHandle->query($queryCmd);
		$usersIds  = $queryRes->result_array();
		return $usersIds;	
	}

	function getCurrentPointsAndHQ($userId = array()){
        $this->initiateModel('write');
        // if(!empty($userId)){
        // 	$userIds = implode($userId, ',');
        // }
        $queryCmd = "SELECT userId, userpointvaluebymodule, HQContentCount, levelName as prevLevel, levelId as prevLevelId from userpointsystembymodule where userId in (?) and modulename = 'AnA' ";
        $queryRes = $this->dbHandle->query($queryCmd,array($userIds));
        $pointArr  = $queryRes->result_array();
        return $pointArr;
	}




}
?>
