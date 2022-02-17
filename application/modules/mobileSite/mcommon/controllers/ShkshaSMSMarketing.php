<?php
ini_set('memory_limit','1024M');
set_time_limit(0);

class ShkshaSMSMarketing extends MX_Controller
{

	function __construct() 
	{
		echo (" \n SHIKSHAMOBI SMS START  \n");
		parent::__construct();
		$this->load->model('smsModel');
		$this->load->library('user_agent'); 
		$this->load->model('smsModel');
		if( $this->agent->is_browser() or $this->agent->is_robot() or $this->agent->is_mobile()) {
			exit();  
		}
	}

	function SendSMS()
	{
		echo (" \n SHIKSHAMOBI SMS IN SENDSMS API START  \n");
		$this->dbLibObj = DbLibCommon::getInstance('User');
		$dbHandle = $this->_loadDatabaseHandle('read');

		$query = $dbHandle->query("select distinct(mobile) as mobile,tuser.userid from tuser,tuserflag where tuser.userid = tuserflag.userId and tuserflag.mobileverified='1' and tuser.usercreationDate > DATE_SUB(now(), INTERVAL 6 MONTH)");
		$results = $query->result_array();
		if(count($results) > 0) {
			echo (" \n SHIKSHAMOBI SMS TOTAL USER FOUND " . count($results) . "\n");
			foreach($results as $result) {
				$message = "Dear User, Shiksha.com is now available on mobile too. Find best careers, courses on the move. Visit  http://www.shiksha.com for details.";
				$Isregistration = 'Yes';
				$tempArray1 = $this->smsModel->addSmsQueueRecord('',$result['mobile'],$message,$result['userid'],'0000-00-00 00:00:00',"",$Isregistration);
				echo (" \n SHIKSHAMOBI SMS SENT To  UserId " . $result['userid'] ." AND MOBI IS " . $result['mobile'] . "\n");
			}
		}
		echo (" \n SHIKSHAMOBI SMS END  \n");
	}

}
