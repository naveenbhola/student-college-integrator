<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev:: 490           $:  Revision of last commit 
$Author: amitj $:  Author of last commit 
$Date: 2008-08-29 10:03:09 $:  Date of last commit 
 
This class provides the Message Board Server Library.
 
$Id: Relateddataconfig.php,v 1.2 2008-08-29 10:03:09 amitj Exp $:  
 
*/ 


class Relateddataconfig {

	

	//Table Name
	public $categoryTable = 'categoryBoardTable';
	public $messageTable = 'messageTable';
	
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
