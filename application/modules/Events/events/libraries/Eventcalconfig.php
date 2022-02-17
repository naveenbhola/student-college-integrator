<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::               $:  Revision of last commit 
$Author: amitj $:  Author of last commit 
$Date: 2008-03-14 07:37:16 $:  Date of last commit 
 
This class provides the Event Calendar Server Library.
 
$Id: Eventcalconfig.php,v 1.2 2008-03-14 07:37:16 amitj Exp $:  
 
*/ 


class Eventcalconfig{
	//Table Name
	public $eventTable = 'event';
	public $eventVenueTable = 'event_venue';
	public $eventDateTable = 'event_date';
	
	//Column Name in categoryTable
	public $categoryName = 'name';
	public $parent_id = 'parent_id';
	public $userId = 'userId';
	public $category_id = 'category_id';
	
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
