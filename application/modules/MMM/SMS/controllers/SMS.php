<?php 

/*

Copyright 2007 Info Edge India Ltd

$Rev:: $: Revision of last commit
$Author: ankurg $: Author of last commit
$Date: 2010-08-30 11:05:56 $: Date of last commit

SMS.php controller for SMS.

$Id: SMS.php,v 1.2 2010-08-30 11:05:56 ankurg Exp $: 

*/
class SMS extends MX_Controller {
	function init() {
		$this->load->helper(array('url','form','image'));	
		$this->load->library(array('alerts_client','ajax','register_client','sms_client'));
		$this->userStatus = $this->checkUserValidation();
	}

	/**
	* The controller update the mobile status of all the users who have been sucessfully sent a sms over the noOfDays duration 
	* the function takes in the number of days as a parameter and updates the tuserflag table for the mobile verfied column.
	* the email (hard/soft bounce) is stored in tuserflag table, which is updated by this function
	* Input: Number of days
	* Output: void 
	*/
	function performMobileCheck($appId=12,$noOfDays=1)
	{
		$this->validateCron();
		$this->init();
		$finalResponse = array();
		$smsClient = new sms_client();
		$response = $smsClient->performMobileCheck($appId,$noOfDays);
		echo $response;
	}

	/**
	*
	* The controller that sends the SMS to the users.
	* it is run as a cron to send the SMS
	* Input : appId
	* Output : A message stating that the sms have been sent properly.
	*/
	function shikshaSmsAlert($appId=12)
	{
		$this->validateCron();
		$this->init();
		$finalResponse = array();
		$smsClient = new sms_client();
		$response = $smsClient->shikshaSmsAlert($appId);
		echo $response;
	}
	
}
?>
