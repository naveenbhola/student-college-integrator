<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/08/30 11:13:35 $:  Date of last commit

This class provides the SMS Server Web Services.

$Id: sms_server.php,v 1.3 2010/08/30 11:13:35 ankurg Exp $:

*/

class sms_server extends MX_Controller {

	/*
	*	index function to recieve the incoming request
	*/

	function index(){
		$this->dbLibObj = DbLibCommon::getInstance('SMS');
		//load XML RPC Libs
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('smsconfig');
		$this->load->helper('url');

		//Define the web services method
		$config['functions']['performMobileCheck'] = array('function'=>'sms_server.performMobileCheck');
		$config['functions']['addSmsQueueRecord'] = array('function'=>'sms_server.addSmsQueueRecord');
		$config['functions']['shikshaSmsAlert'] = array('function'=>'sms_server.shikshaSmsAlert');

		//initialize
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

	/**
	* The controller update the mobile status of all the users who have been sucessfully sent a sms over the noOfDays duration
	* The controller is called as a cron
	* the function takes in the number of days as a parameter and updates the tuserflag table for the mobile verfied column.
	* the email (hard/soft bounce) is stored in tuserflag table, which is updated by this function
	* Input: Number of days
	* Output: void
	*/
	function performMobileCheck($request)
	{
	    $parameters = $request->output_parameters();
	    $appId=$parameters['0'];
	    $noOfDays=$parameters['1'];

	    //connect DB
	    $dbHandle = $this->_loadDatabaseHandle('write');
	    $this->load->model('smsModel');
	    $tempArray1 = $this->smsModel->performMobileCheck($dbHandle,$noOfDays);

	    $message="inserted successfully";
	    $response=array($message,'string');
	    return $this->xmlrpc->send_response($response);
	}

	/**
	*
	* The controller that takes in the sms's to be sent. The controller simply takes all the input elements and stores it in the database. The table contains all smses to be sent. The sms's once sent are shifted to a table tSmsOutput. The smses can be controlled using sendTime. There are a couple of checks while send ing the sms, such as that the user may send only 15 sms per day etc. All further sms's are put in the smsQueue and sent the next day.
	* Input : appId, toSMSNumber, content, userId, and sendTime
	* Output : A message stating that the sms has been inserted properly.
	*/

	function addSmsQueueRecord($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$toSms = $parameters['1'];
		$content = $parameters['2'];
		$userId = $parameters['3'];
		$sendTime= $parameters['4'];

		$dbHandle = $this->_loadDatabaseHandle('write');

		$this->load->model('smsModel');
		$msg = $this->smsModel->addSmsQueueRecord($dbHandle,$toSms,$content,$userId,$sendTime);
		$response=array($msg,'string');
		return $this->xmlrpc->send_response($response);
	}

	/**
	*
	* The controller that sends the SMS to the users.
	* it is run as a cron to send the SMS
	* Input : appId
	* Output : A message stating that the sms have been sent properly.
	*/
	function shikshaSmsAlert($request)
	{
	    $parameters = $request->output_parameters();
	    $appId=$parameters['0'];
	
	    //connect DB
	    $dbHandle = $this->_loadDatabaseHandle('write');
	    //Get the to be processed SMS which needs to be sent
	    
		//$queryCmd = "SELECT smsQueue.type,smsQueue.id,mobileNumber,text,smsQueue.user_id FROM smsQueue INNER JOIN tuser ON smsQueue.user_id = tuser.userId WHERE  (smsQueue.processTime = \"0000-00-00 00:00:00\" or smsQueue.processTime<now()) and status = 'to_be_processed' and noOfTries < ".$this->smsconfig->sendSmsTries." ORDER BY createdDate LIMIT ".$this->smsconfig->smsNumberLimit;
		
		$chkDate = date('Y-m-d',strtotime('-2 days'));
		
		$queryCmd = "SELECT smsQueue.type,smsQueue.id,mobileNumber,text,smsQueue.user_id,tuser.isdCode FROM smsQueue INNER JOIN tuser ON smsQueue.user_id = tuser.userId WHERE createdDate > ? and status = 'to_be_processed' and noOfTries < ? ORDER BY createdDate LIMIT ".$this->smsconfig->smsNumberLimit;
		
		
	    $query = $dbHandle->query($queryCmd, array($chkDate, $this->smsconfig->sendSmsTries));
	    //Set the status of these to be sent SMS to in process
	    $idArray = $query->result_array();
	    $CSVList = array();
	    foreach($idArray as $row){
			$CSVList[] = $row['id'];
	    }
        if(!empty($CSVList)){
    	    $queryCmdProcess = "UPDATE smsQueue SET status = 'in_process' WHERE id IN (?)";
	        $res = $dbHandle->query($queryCmdProcess, array($CSVList));
        }

	    //For each of these SMS entry, send the SMS and also make an entry in smsStatTable
	    foreach ($query->result_array() as $row){
		    $id = $row['id'];
		    $mobileNumber = $row['mobileNumber'];
		    $type = $row['type'];
		    if($mobileNumber != ''){
				$text = $row['text'];
				$contactDetailTypeSMS = strpos($text, "Contact details requested by you are");
				if($contactDetailTypeSMS === false) {
						$text = htmlspecialchars($text);
				}
				$userId = $row['user_id'];
				$isdCode = $row['isdCode'];
				$mobileNumber = $mobileNumber;
		      $this->sendSingleSms($id,$mobileNumber,$text,$type,$isdCode);
		      $queryCmdStat = "INSERT INTO smsStatTable (user_id) VALUES (?) ON duplicate key UPDATE noOfSms=(noOfSms+1)";
		      $res = $dbHandle->query($queryCmdStat, array($userId));
		    }
	    }
	    $message="done";
	    $response=array($message,'string');
	    return $this->xmlrpc->send_response($response);
	}

	/**
	*
	* The controller that takes sends the SMS to the user and then sends them making a CURL call
	* it is run as a cron to send the SMS
	* Input : id, mobile, message
	* Output : none
	*/
	function sendSingleSms($id,$mobile,$message,$type ="system",$isdCode="")
	{
	    $dbHandle = $this->_loadDatabaseHandle('write');
	    //Send the SMS
	  
	    $this->config->load('sms_settings');
	    
	    if($type == "system")
	    {
		$credentials = $this->config->item('system');
	    }
	    elseif($type == "user-defined")
	    {
		$credentials = $this->config->item('user_defined');
	    }
	    
	    $xmlMessage = '<?xml version="1.0" encoding="ISO-8859-1"?><!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" ><MESSAGE VER="1.2"><USER USERNAME="'.$credentials['username'].'" PASSWORD="'.$credentials['PASSWORD'].'"/><SMS UDH="0" CODING="1" TEXT="'.$message.'" PROPERTY="0" ID="2"><ADDRESS FROM="Shksha" TO="'.$isdCode.$mobile.'" SEQ="1" TAG="" /></SMS></MESSAGE>';
	    
	    $this->load->helper('sms');
	    $response = makeSmsCURLcall($xmlMessage);
	    //After sending the SMS, check if it was a success or a failure. On the basis of this,set the status in smsQueue table
	    $xml = simplexml_load_string($response);
            if(gettype($xml) == 'object') {
	    	$xml_guid = $xml->xpath('/MESSAGEACK/GUID');
	    } else {
		$xml_guid = array();
	    }		
	    error_log("\n SMSALE Time of celebration GUID array is \n" . print_r($response,true));
	    $status = "to_be_verified";
	    error_log("\n SMSALE response xml_guid GUID ".$xml_guid[0]["GUID"]);
		
		$smsStatus = 'SUCCESS';
		
		if(isset($xml_guid[0]["GUID"])) {	//In case of success when we receive a GUID
			/*
			 * Check if there is an ERROR tag, which means SMS failed
			 */ 
			$xml_error = $xml->xpath('/MESSAGEACK/GUID/ERROR');
			if($xml_error[0]["CODE"]) {
				$smsStatus = 'FAILED';
			}
		}
		else {
			$smsStatus = 'FAILED';
		}
		
	    if($smsStatus == 'SUCCESS') {
	      $sql = "UPDATE smsQueue SET status='processed', processTime = now() WHERE id=?";
	      $query = $dbHandle->query($sql, array($id));
	    }else{	//In case of error
	      $sql = "UPDATE smsQueue SET status= 'to_be_processed' , noOfTries=(noOfTries+1), processTime = now() WHERE id=?";
	      $query = $dbHandle->query($sql, array($id));
	      $status = "failed";
	    }
	    
	    //Make an entry in the tSmsOutput table so that we can verify the same
		//    $sql = "INSERT INTO tSmsOutput (input,output,status,type) VALUES(".$dbHandle->escape($xmlMessage).",".$dbHandle->escape($response).",".$status.");";
	    $sql = "INSERT INTO tSmsOutput (input,output,smsQueue_id,status) VALUES(".$dbHandle->escape($xmlMessage).",".$dbHandle->escape($response).",".$dbHandle->escape($id).",".$dbHandle->escape($status).");";

	    $query = $dbHandle->query($sql);

	}
	
	function sendOTP($trackingParams, $trackId) {

		$id      = $trackingParams['otp_verification_id'];
		$mobile  = $trackingParams['mobile'];
		$OTP     = $trackingParams['OTP'];
		$isdCode = $trackingParams['isdCode'];
		$mobile  = $isdCode.$mobile;
		
		$this->dbLibObj = DbLibCommon::getInstance('SMS');
		$dbHandle = $this->_loadDatabaseHandle('write');
		
		$this->load->model('userVerification/verificationmodel');
		$verificationmodel = new verificationmodel();
		
		$this->load->helper('sms');
		
		$this->config->load('sms_settings');
		$credentials = $this->config->item('OTP');

		if($trackingParams['site_source'] == 'National'){
			$message = $OTP." is your One Time Password (OTP) to verify your mobile number on Shiksha. Valid till ".date('h:i A',strtotime("+30 minutes")).'.';
		}else{
			$message = 'Your One time password is '.$OTP.'. This is valid for the next 30 min only.';	
		}
		

		$xmlMessage = '<?xml version="1.0" encoding="ISO-8859-1"?><!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" ><MESSAGE VER="1.2"><USER USERNAME="'.$credentials['username'].'" PASSWORD="'.$credentials['PASSWORD'].'"/><SMS UDH="0" CODING="1" TEXT="'.$message.'" PROPERTY="0" ID="2"><ADDRESS FROM="Shksha" TO="'.$mobile.'" SEQ="1" TAG="" /></SMS></MESSAGE>';
		
		$smsStatus = 'FAILED';
		$sendTries = 0;
		
		while($smsStatus == 'FAILED' && $sendTries < 3) {
			$response = makeSmsCURLcall($xmlMessage);
			$sendTries++;
			
			$xml = simplexml_load_string($response);
			
			if(gettype($xml) == 'object') {
				$xml_guid = $xml->xpath('/MESSAGEACK/GUID');
			}
			else {
				$xml_guid = array();
			}
			
			$GUID = (string)$xml_guid[0]["GUID"][0];

			$smsStatus = 'SUCCESS';
			if(isset($xml_guid[0]["GUID"])) {
				$xml_error = $xml->xpath('/MESSAGEACK/GUID/ERROR');
				if($xml_error[0]["CODE"]) {
					$errorCode = $xml_error[0]["CODE"];
					$smsStatus = 'FAILED';
				}
			}
			else {
				$smsStatus = 'FAILED';
			}

			if($sendTries == 1){
				if($smsStatus == 'SUCCESS'){
					$verificationmodel->updateOTPTrackingStatus($trackId, $id, 'sent', $GUID, $errorCode);
				} else {
					$verificationmodel->updateOTPTrackingStatus($trackId, $id, 'failed', $GUID, $errorCode);
				}
			} else {
				if($smsStatus == 'SUCCESS'){
					$trackingParams['otp_status'] = 'sent';
				} else {
					$trackingParams['otp_status'] = 'failed';
				}
				$trackingParams['GUID']       = $GUID;
				$trackingParams['errorCode']  = $errorCode;
				$trackingParams['isResend']   = '2';
				$verificationmodel->trackOTP($trackingParams);
			}

		}
		
		if($smsStatus == 'SUCCESS') {
			$verificationmodel->updateOTPStatus($id, 'sent', '', '', '', '', $GUID);
			return 'yes';
		}
		else {
			return 'no';
		}
	}
	
	function sendSingleSmsWithoutQueue($mobile,$message,$type ="system") {		
	  
	    $this->config->load('sms_settings');
	    
	    if($type == "system") {	    
			$credentials = $this->config->item('system');
	    } elseif($type == "user-defined") {	    
	    
			$credentials = $this->config->item('user_defined');
	    }
	    
	    error_log("\n SMSALEXXXXXXXXXX yummie  load data from config \n".print_r($credentials,true));
		
	    $xmlMessage = '<?xml version="1.0" encoding="ISO-8859-1"?><!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" ><MESSAGE VER="1.2"><USER USERNAME="'.$credentials['username'].'" PASSWORD="'.$credentials['PASSWORD'].'"/><SMS UDH="0" CODING="1" TEXT="'.$message.'" PROPERTY="0" ID="2"><ADDRESS FROM="Shksha" TO="'.$mobile.'" SEQ="1" TAG="" /></SMS></MESSAGE>';
	    
	    error_log("\n SMSALEXXXXXXXXXX yummie xml string \n".$xmlMessage);
	    
	    $this->load->helper('sms');
	    $response = makeSmsCURLcall($xmlMessage);
	    error_log("\n SMSALEXXXXXXXXXX response from value 1 of sending sms \n".$response);
	    
	    //After sending the SMS, check if it was a success or a failure. On the basis of this,set the status in smsQueue table
	    $xml = simplexml_load_string($response);
	    
        if(gettype($xml) == 'object') {
	    	$xml_guid = $xml->xpath('/MESSAGEACK/GUID');
	    } else {
			$xml_guid = array();
	    }		
	    
	    error_log("\n SMSALEXXXXXXXXXX Time of celebration GUID array is \n" . print_r($response,true));
	    $status = "to_be_verified";
	    error_log("\n SMSALEXXXXXXXXXX response xml_guid GUID ".$xml_guid[0]["GUID"]);
		
		$smsStatus = 'SUCCESS';
		
		if(isset($xml_guid[0]["GUID"])) {	//In case of success when we receive a GUID
			/*
			 * Check if there is an ERROR tag, which means SMS failed
			 */ 
			$xml_error = $xml->xpath('/MESSAGEACK/GUID/ERROR');
			if($xml_error[0]["CODE"]) {
				$smsStatus = 'FAILED';
			}
		}
		else {
			$smsStatus = 'FAILED';
		}
		
		return $smsStatus;
	}
}
?>
