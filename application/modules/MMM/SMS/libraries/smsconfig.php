<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::               $:  Revision of last commit 
$Author: ankurg $:  Author of last commit 
$Date: 2010-08-30 11:08:51 $:  Date of last commit 
 
This class provides the SMS Server Library.
 
$Id: smsconfig.php,v 1.2 2010-08-30 11:08:51 ankurg Exp $:  
 
*/ 


class smsconfig{
	//Table Name
	public $smsTable = 'tSmsOutput';
	public $smsQueueTable = 'smsQueue';
	public $smsStatTable = 'smsStatTable';
	public $smsNumberLimit = 500;
	public $sendSmsTries = 3;
	var $CI_Instance;
        var $dbLib;	
	function init(){
        	$this->CI_Instance = & get_instance();
                $this->CI_Instance->load->library('dbLib');
                $this->dbLib = new dbLib();
    	}
	
	public function getDbConfig($appID,&$config){	
		$this->init();
		$config['hostname'] = $this->dbLib->getServerIP($appID);
		$config['username'] = $this->dbLib->getUserName($appID);
		$config['password'] = $this->dbLib->getUserPassword($appID);
		$config['database'] = $this->dbLib->getDbName($appID);
		$config['dbdriver'] = "mysqli";
		$config['dbprefix'] = "";
		$config['pconnect'] = TRUE;
		$config['db_debug'] = TRUE;
		$config['active_r'] = TRUE;
	}
}

?>
