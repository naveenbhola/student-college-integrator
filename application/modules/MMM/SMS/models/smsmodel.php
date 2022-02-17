<?php
/*
    Model for database related operations related to message board.
    Following is the example this model can be used in the server controllers.
    $this->load->model('smsModel');
    $tempArray1 = $this->smsModel->addSmsQueueRecord($dbHandle,$toSms,$content,$userId,$sendTime);
*/

class smsModel extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('SMS');
	}

	private function initiateModel($operation = 'read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

	/**
	*
	* The controller that takes in the sms's which are sent from tSMSOutput table and then verifies if the mobile number was valid, invalid or the sms could not be sent.
	* This controller is run from a cron
	* Input : noOfDays
	* Output : A message stating that the cron ahs run successfully.
	*/
	function performMobileCheck($dbHandleSent,$noOfDays){
	    if(!is_resource($dbHandleSent)){
		    $this->initiateModel('write');
	    }else{
		    $this->dbHandle = $dbHandleSent;
	    }

	 //   $queryCmd = "SELECT id,Type,IsRegistration FROM tSmsOutput, smsQueue where status = 'to_be_verified' AND tSmsOutput.smsQueue_id = smsQueue.id and updateTime >= (DATE_SUB(curdate(), INTERVAL 1 DAY)) ORDER BY updateTime LIMIT 1000;";
	    
	    
		$queryCmd = "SELECT distinct(tSmsOutput.id) FROM tSmsOutput, smsQueue WHERE tSmsOutput.status =  'to_be_verified' and tSmsOutput.noOfTries < 7 and updateTime >= (DATE_SUB(curdate(), INTERVAL 1 DAY)) and tSmsOutput.smsQueue_id = smsQueue.id  AND smsQueue.isRegistration =  'Yes' ORDER BY updateTime LIMIT 1000";


	    error_log("\n SMSALE performMobileCheck START \n" . $queryCmd);
	    $query = $this->dbHandle->query($queryCmd);
	    $idArray = $query->result_array();
	    
	    $CSVList = array();
	    foreach($idArray as $row){
			$CSVList[] = $row['id'];
	    }
        if(!empty($CSVList)){

	        $queryCmd = "UPDATE tSmsOutput SET status = 'in_process' WHERE id IN (?);";
	        $query = $this->dbHandle->query($queryCmd, array($CSVList));

		    foreach($idArray as $row) {
				$queryCmd = "SELECT substring_index(substring_index(input,'TO=\"',-1),'\"',1) as mobile, substring_index(substring_index(output,'GUID=\"',-1),'\"',1) as guid FROM tSmsOutput WHERE id = ? ;";
				error_log("alert ".$queryCmd);
				$query=$this->dbHandle->query($queryCmd, array($row['id']));
				
			   

				//log_message('debug', 'performMobileCheck query cmd is ' . $queryCmd);
				$msgArray = array();
				foreach ($query->result_array() as $row2)
				{
				    if(isset($row2['mobile']) && isset($row2['guid'])){
					$mobile = $row2['mobile'];
					$guid = $row2['guid'];
					$xmlData = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><!DOCTYPE STATUSREQUEST SYSTEM \"http://127.0.0.1/psms/dtd/requeststatusv12.dtd\" ><STATUSREQUEST VER=\"1.2\"><USER USERNAME=\"shiksha\" PASSWORD=\"skh02api08\"/><GUID GUID=\"".$guid."\"><STATUS SEQ=\"1\" /></GUID></STATUSREQUEST>";
					error_log("alert ".$xmlData);
					$this->load->helper('sms');
					$result = makeVerificationSmsCurl($xmlData);
					error_log("alert ".$result);
					$xml = simplexml_load_string($result);
				        if(gettype($xml) == 'object') {
						$xml_guid = $xml->xpath('/STATUSACK/GUID/STATUS');
					} else {
						$xml_guid = array();
					}
					$parametertype = 'sms';
					$loggingput = logToFile($row['id'],$row2['mobile'],$parametertype,$row2['guid'],$xmlData,$xml_guid[0]["ERR"]);
				
					if(isset($xml_guid[0]["ERR"])){
					    $this->checkSmsReturnCode($this->dbHandle,$mobile,$row['id'],$xml_guid[0]["ERR"]);
					}
					else{
					    $this->setWaitingStatus($this->dbHandle,$row['id']); //GUID response not found
					}
				    }
				    else{
					$this->setWaitingStatus($this->dbHandle,$row['id']); //GUID response not found
				    }
				}
		    }
		}
	    $message="inserted successfully";
	    $response=array($message,'string');
	    return $response;
	}

	/**
	* This function sets the mobileVerified field in the tUserFlag based on the status returned.
	* Input : mobile, id, errCode
	* Output : none
	*/
	function checkSmsReturnCode($dbHandleSent,$mobile,$id,$errCode){
	    if(!is_resource($dbHandleSent)){
		    $this->initiateModel('write');
	    }else{
		    $this->dbHandle = $dbHandleSent;
	    }
		error_log("\n SMSALE performMobileCheck checkSmsReturnCode \n" . $errCode);
	    if( $errCode == 8448){
		$this->setMobileVerification($this->dbHandle,$mobile,$id,'1','verified');
	    }
	    else if($errCode == 8449){
		$this->setMobileVerification($this->dbHandle,$mobile,$id,'2','invalid');
	    }
	    else if($errCode == ""){
		$this->setWaitingStatus($this->dbHandle,$id);	//Message still waiting
	    }
	}

	/**
	* This function sets the mobileVerified field in the tUserFlag based on the status returned.
	* Input : mobile, id, verified (1 or 2), status (invalid, verified)
	* Output : none
	* Changed By AshishM (prevent mobile verififcation if already verified)
	*/
	function setMobileVerification($dbHandleSent,$mobile,$id,$verified,$status){
	    if(!is_resource($dbHandleSent)){
		    $this->initiateModel('write');
	    }else{
		    $this->dbHandle = $dbHandleSent;
	    }

$sql =  "SELECT t.userid FROM tuser t,tuserflag tf ".
				"WHERE t.userid = tf.userid ".
				"AND CONCAT(t.isdCode,t.mobile) = ? AND tf.mobileverified != '1'";
		
		$query = $this->dbHandle->query($sql, array($mobile));		
		$userIds = array();
		foreach($query->result_array() as $row) {
			$userIds[] = $row['userid'];
		}


	    $queryCmd = "UPDATE tuserflag, tuser SET tuserflag.mobileverified
=? WHERE tuser.userid = tuserflag.userId and tuser.mobile = ? and tuserflag.mobileverified != '1'";
	    $updateQueryResponse=$this->dbHandle->query($queryCmd, array($verified, $mobile));
	    $queryCmd = "UPDATE tSmsOutput SET status = ?, deliveryTime=current_timestamp WHERE id= ?;";
	    $updateQueryResponse=$this->dbHandle->query($queryCmd, array($status, $id));

$CI = & get_instance();
		$CI->load->library('user/UserLib');
		$userLib = new UserLib;
		foreach($userIds as $userId) {
			$userLib->updateUserData($userId);
		}

	}

	/**
	* This function sets the status field in the tSmsOutput in case the sms verification is still pending
	* Input : id
	* Output : none
	*/
	function setWaitingStatus($dbHandleSent,$id){
	    if(!is_resource($dbHandleSent)){
		    $this->initiateModel('write');
	    }else{
		    $this->dbHandle = $dbHandleSent;
	    }
	    $queryCmd = "UPDATE tSmsOutput SET status = 'to_be_verified', noOfTries=(noOfTries+1) WHERE id= ?;";
	    $updateQueryResponse=$this->dbHandle->query($queryCmd, array($id));
	}

	/**
	*
	* The controller that takes in the sms's to be sent. The controller simply takes all the input elements and stores it in the database. The table contains all smses to be sent. The sms's once sent are shifted to a table tSmsOutput. The smses can be controlled using sendTime. There are a couple of checks while send ing the sms, such as that the user may send only 15 sms per day etc. All further sms's are put in the smsQueue and sent the next day.
	* Input : appId, toSMSNumber, content, userId, and sendTime
	* Output : A message stating that the sms has been inserted properly.
	*/
	function addSmsQueueRecord($dbHandle,$toSms,$content,$userId,$sendTime,$smstype,$IsRegistration, $returnSMSId)
	{
		if(!($toSms > 0)) {
			return '';
		}
		
		if(!is_resource($dbHandleSent)){
			$this->initiateModel('write');
		}else{
			$this->dbHandle = $dbHandleSent;
		}
        /* COMMENTED 12 SEPT 2011 Ravi Raj allow SMS sending for all user */
        
        $this->load->library('ndnc_lib');
        $result = $this->ndnc_lib->ndnc_mobile_check($toSms);
	
        if($result == 'false' && $smstype == 'user-defined')
        {
        
            $data=array('mobileNumber'=> $toSms, 'text'=> $content,'user_id'=>$userId,'processTime'=>$sendTime,'type'=>$smstype,'isRegistration'=>$IsRegistration);
            $queryCmd = $this->dbHandle->insert_string('smsQueue',$data);
            //error_log(" SMSALE call1 addSmsQueueRecord " . $message . $queryCmd);
            $this->dbHandle->query($queryCmd);
            $message="Inserted Successfully";
            if( $returnSMSId =='Y'){
            	$message = $this->dbHandle->insert_id();
            }
        
        }
        elseif($result == 'true' && $smstype == 'user-defined')
        {
            $message="Failed,MobileNo is registred in NDNC DB.";
           // error_log(" SMSALE call3 addSmsQueueRecord " . $message);
        }
		else
		{
			$data=array('mobileNumber'=> $toSms, 'text'=> $content,'user_id'=>$userId,'processTime'=>$sendTime,'type'=>"system",'isRegistration'=>$IsRegistration);
			$query = $this->dbHandle->insert_string('smsQueue',$data);
			// error_log(" SMSALE call4 addSmsQueueRecord " . $message . $query);
			$this->dbHandle->query($query);
			$message="Inserted Successfully";
			if( $returnSMSId =='Y'){
            	$message = $this->dbHandle->insert_id();
            }
			
		}
	
		return $message;
	}

	/**
	* This function sends a daily report for internal use.
	* Input : appId, date
	* Output : The MIS for the date's SMS
	*/
	function sendSMSReport($appId,$date)
	{
		$this->initiateModel('write');
		$dateTom = strtotime("1 days",strtotime($date));
		$dateTom = date ( 'Y-m-j' , $dateTom );

		$queryCmd = "SELECT count(*) totalSMSSent FROM smsQueue where createdDate > ? and createdDate < ? and status = 'processed'";
        //error_log("queryCmd is as".$queryCmd);
		$queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
	        foreach ($queryRes->result_array() as $row){
		  $totalSMSSent = $row['totalSMSSent'];
		}
		$queryCmd = "SELECT count(*) totalSMSFailure FROM smsQueue where createdDate > ? and createdDate < ? and status = 'to_be_processed' and noOfTries >= '7'";
        //error_log("queryCmd is as".$queryCmd);
		$queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
	        foreach ($queryRes->result_array() as $row){
		  $totalSMSFailure = $row['totalSMSFailure'];
		}
		$queryCmd = "SELECT count(*) mobileVerified FROM tuserflag tf, tuser t1 where t1.usercreationDate > ? and t1.usercreationDate < ? and tf.userId=t1.userid and tf.mobileverified = '1' and t1.mobile!='' and t1.mobile!='0'";
        //error_log("queryCmd is as".$queryCmd);
		$queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
	        foreach ($queryRes->result_array() as $row){
		  $mobileVerified = $row['mobileVerified'];
		}
		$queryCmd = "SELECT count(*) totalRegisteredUsers FROM tuser t1 where t1.usercreationDate > ? and t1.usercreationDate < ? and t1.mobile!='' and t1.mobile!='0'";
        //error_log("queryCmd is as".$queryCmd);
		$queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
	        foreach ($queryRes->result_array() as $row){
		  $totalRegisteredUsers = $row['totalRegisteredUsers'];
		}

/*
		$queryCmd = "SELECT count(*) as count_5min from tSmsOutput where updateTime > '$date' and updateTime < '$dateTom' and status='verified' and timediff(deliveryTime, updateTime)<300";
                $queryRes = $this->dbHandle->query($queryCmd);
                foreach ($queryRes->result_array() as $row){
                  $count_5min = $row['count_5min'];
                }
		$queryCmd = "SELECT count(*) as count_30min from tSmsOutput where updateTime > '$date' and updateTime < '$dateTom'  and status='verified' and timediff(deliveryTime, updateTime)<1800";
                $queryRes = $this->dbHandle->query($queryCmd);
                foreach ($queryRes->result_array() as $row){
                  $count_30min = $row['count_30min'];
                }
		$queryCmd = "SELECT count(*) as count_120min from tSmsOutput where updateTime > '$date' and updateTime < '$dateTom'  and status='verified' and timediff(deliveryTime, updateTime)<7200";
                $queryRes = $this->dbHandle->query($queryCmd);
                foreach ($queryRes->result_array() as $row){
                  $count_120min = $row['count_120min'];
                }
		$queryCmd = "SELECT count(*) as mail_count_5min from tMailQueue where createdTime > '$date' and createdTime < '$dateTom'  and isSent='sent' and timediff(sendTime, createdTime)<300";
                $queryRes = $this->dbHandle->query($queryCmd);
                foreach ($queryRes->result_array() as $row){
                  $mail_count_5min = $row['mail_count_5min'];
                }
		$response = array('totalSMSSent' => $totalSMSSent,'totalSMSFailure' => $totalSMSFailure ,'mobileVerified' => $mobileVerified,'totalRegisteredUsers' => $totalRegisteredUsers, 'count_5min' => $count_5min, 'count_30min' => $count_30min, 'count_120min' => $count_120min, 'mail_count_5min' => $mail_count_5min);
*/
//		$response = array('totalSMSSent' => $totalSMSSent,'totalSMSFailure' => $totalSMSFailure ,'mobileVerified' => $mobileVerified,'totalRegisteredUsers' => $totalRegisteredUsers);
		$queryCmd = "SELECT count(*) as count_5min from tSmsOutput where updateTime > ? and updateTime < ? and status='verified' and timestampdiff(second,updateTime, deliveryTime)<=300";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                  $count_5min = $row['count_5min'];
                }
		$queryCmd = "SELECT count(*) as count_30min from tSmsOutput where updateTime > ? and updateTime <?  and status='verified' and timestampdiff(second,updateTime, deliveryTime)<=1800 and timestampdiff(second,updateTime, deliveryTime)>300";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                  $count_30min = $row['count_30min'];
                }
		$queryCmd = "SELECT count(*) as count_120min from tSmsOutput where updateTime > ? and updateTime < ?  and status='verified' and timestampdiff(second,updateTime, deliveryTime)<=7200 and timestampdiff(second,updateTime, deliveryTime)>1800";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                  $count_120min = $row['count_120min'];
                }
		$queryCmd = "SELECT count(*) as mail_count_5min from tMailQueue where createdTime > ? and createdTime < ?  and isSent='sent' and timestampdiff(second, createdTime, sendTime)<=300";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                  $mail_count_5min = $row['mail_count_5min'];
                }
		$queryCmd = "SELECT count(*) as mail_count_30min from tMailQueue where createdTime > ? and createdTime < ?  and isSent='sent' and timestampdiff(second, createdTime, sendTime)<=1800 and timestampdiff(second, createdTime, sendTime)>300";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                  $mail_count_30min = $row['mail_count_30min'];
                }
		$queryCmd = "SELECT count(*) as mail_count_120min from tMailQueue where createdTime > ? and createdTime < ?  and isSent='sent' and timestampdiff(second, createdTime, sendTime)<=7200 and timestampdiff(second, createdTime, sendTime)>1800";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                  $mail_count_120min = $row['mail_count_120min'];
                }
		$queryCmd = "SELECT count(distinct userid) as count_leads from SALeadAllocation where allocationtime > ? and allocationtime < ?";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                $allocated_lead_count = $row['count_leads'];
                }
		$queryCmd = "SELECT count(distinct agentid) as count_agents from SALeadAllocation where allocationtime > ? and allocationtime < ?";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                $allocated_lead_agents_count = $row['count_agents'];
                }
		$queryCmd = "select count(searchagentid) as count_agents from SASearchAgent where is_active='live' and searchagentid not in (select distinct agentid from SALeadAllocation where allocationtime > ? and allocationtime < ?)";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                $unallocated_lead_agents_count = $row['count_agents'];
                }
        $queryCmd = "SELECT count(distinct agentid) as count_agents from SALeadAllocation where allocationtime > ? and allocationtime < ?
        and ((sms_sent='YES' and email_sent='YES' and auto_download='YES' and auto_responder_email='YES' and auto_responder_sms='YES' and auto_responder_email_sent='YES' and auto_responder_sms_sent='YES')
        or (sms_sent='YES' and email_sent='YES' and auto_download='YES' and auto_responder_email='NO' and auto_responder_sms='NO' and auto_responder_email_sent='NO' and auto_responder_sms_sent='NO')
        or (sms_sent='NO' and email_sent='NO' and auto_download='NO' and auto_responder_email='YES' and auto_responder_sms='YES' and auto_responder_email_sent='YES' and auto_responder_sms_sent='YES')
	OR(
        sms_sent = 'YES'
        AND email_sent = 'YES'
        AND auto_download = 'YES'
        AND auto_responder_email = 'NO'
        AND auto_responder_sms = 'YES'
        AND auto_responder_email_sent = 'NO'
        AND auto_responder_sms_sent = 'YES'
        )
        OR(
        sms_sent = 'YES'
        AND email_sent = 'YES'
        AND auto_download = 'YES'
        AND auto_responder_email = 'YES'
        AND auto_responder_sms = 'NO'
        AND auto_responder_email_sent = 'YES'
        AND auto_responder_sms_sent = 'NO'
        )
        OR(
        sms_sent = 'NO'
        AND email_sent = 'NO'
        AND auto_download = 'NO'
        AND auto_responder_email = 'NO'
        AND auto_responder_sms = 'YES'
        AND auto_responder_email_sent = 'NO'
        AND auto_responder_sms_sent = 'YES'
        )
        OR(
        sms_sent = 'NO'
        AND email_sent = 'NO'
        AND auto_download = 'NO'
        AND auto_responder_email = 'YES'
        AND auto_responder_sms = 'NO'
        AND auto_responder_email_sent = 'YES'
        AND auto_responder_sms_sent = 'NO'
        ))";
        //error_log("queryCmd is as".$queryCmd);
                $queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
                foreach ($queryRes->result_array() as $row){
                $delivered_lead_agents_count = $row['count_agents'];
                }
        $responses = array('totalResponses'=>0);
        $queryCmd = "SELECT count(action) as countAction, action FROM tempLMSTable WHERE listing_subscription_type='paid' and submit_date >= ? and submit_date < ? group by action";
        //error_log("queryCmd is as".$queryCmd);
		$queryRes = $this->dbHandle->query($queryCmd, array($date, $dateTom));
		foreach ($queryRes->result_array() as $row){
			$responses[$row['action']] = $row['countAction'];
			$responses['totalResponses'] += intval($row['countAction']);
		}
				
		$queryCmd = "SELECT count(distinct leadid) as leadsMatched FROM SALeadMatchingLog WHERE matchingTime > ? AND matchingTime < ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($date, date('Y-m-d')));
		$mresult = $queryRes->row_array();
		$leadsMatched = $mresult['leadsMatched'];
		
		$queryCmd = "SELECT count(distinct userid) as leadsAllocated FROM SALeadAllocation WHERE allocationtime > ? AND allocationtime < ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($date, date('Y-m-d')));
		$mresult = $queryRes->row_array();
		$leadsAllocated = $mresult['leadsAllocated'];
				
		$response = array('totalSMSSent' => $totalSMSSent,'totalSMSFailure' => $totalSMSFailure ,'mobileVerified' => $mobileVerified,'totalRegisteredUsers' => $totalRegisteredUsers, 'count_5min' => $count_5min, 'count_30min' => $count_30min, 'count_120min' => $count_120min, 'mail_count_5min' => $mail_count_5min, 'mail_count_30min'=> $mail_count_30min, 'mail_count_120min'=> $mail_count_120min, 'allocated_lead_count'=> $allocated_lead_count, 'allocated_lead_agents_count'=> $allocated_lead_agents_count, 'unallocated_lead_agents_count'=> $unallocated_lead_agents_count, 'delivered_lead_agents_count'=> $delivered_lead_agents_count, 'responses'=>$responses,'leadsMatched'=>$leadsMatched,'leadsAllocated'=>$leadsAllocated);
		return $response;
	}

}
?>
