<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::               $:  Revision of last commit 
$Author: nehac $:  Author of last commit 
$Date: 2008-03-14 08:03:04 $:  Date of last commit 
 
This class provides the Message Board Server Library.
 
$Id: schoolconfig.php,v 1.3 2008-03-14 08:03:04 nehac Exp $:  
 
*/ 


class facebookconfig{
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
                $config['facebook_api_key'] = FACEBOOK_API_KEY;
                $config['facebook_secret_key'] = FACEBOOK_SECRET_KEY;
                $config['facebook_api_id'] = FACEBOOK_API_ID;
	}
	
	
}

?>
