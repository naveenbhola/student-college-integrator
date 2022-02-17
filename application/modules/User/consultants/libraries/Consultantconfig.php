<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::               $:  Revision of last commit 
$Author: shirish $:  Author of last commit 
$Date: 2009-08-12 07:56:37 $:  Date of last commit 
 
This class provides the Event Calendar Server Library.
 
$Id: Consultantconfig.php,v 1.2 2009-08-12 07:56:37 shirish Exp $:  
 
*/ 


class Consultantconfig{

    var $CI_Instance;
    var $dbLib;	
	function init(){
        	$this->CI_Instance = & get_instance();
                $this->CI_Instance->load->library('common/dbLib');
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
