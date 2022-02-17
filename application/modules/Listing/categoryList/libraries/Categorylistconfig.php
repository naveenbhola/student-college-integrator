<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::               $:  Revision of last commit 
$Author: amitj $:  Author of last commit 
$Date: 2008-01-25 07:07:06 $:  Date of last commit 
 
This class provides the Event Calendar Server Library.
 
$Id: Categorylistconfig.php,v 1.2 2008-01-25 07:07:06 amitj Exp $:  
 
*/ 


class Categorylistconfig{
	private $userName = 'root';
	private $password = 'shiksha';
	private $serverIP = 'localhost';
	private $dbName = 'shiksha';
	private $hostname= 'localhost';
	

	//Table Name
	public $eventTable = 'event';
	public $eventVenueTable = 'event_venue';
	public $eventDateTable = 'event_date';
	
	//Column Name in categoryTable
	public $categoryName = 'name';
	public $parent_id = 'parent_id';
	public $userId = 'userId';
	public $category_id = 'category_id';

	
	public function getDbConfig($appID,&$config){	
		$config['hostname'] = $this->getServerIP($appID);
		$config['username'] = $this->getUserName($appID);
		$config['password'] = $this->getUserPassword($appID);
		$config['database'] = $this->getDbName($appID);
		$config['dbdriver'] = "mysqli";
		$config['dbprefix'] = "";
		$config['pconnect'] = TRUE;
		$config['db_debug'] = TRUE;
		$config['active_r'] = TRUE;
	}
	
	private function getUserName($appID){
		return $this->userName;
	}
	
	private function getUserPassword($appID){
		return $this->password;
	}
	
	private function getServerIP($appID){
		return $this->serverIP;
	}

	private function getDbName($appID){
		return $this->dbName;
	}

}

?>
