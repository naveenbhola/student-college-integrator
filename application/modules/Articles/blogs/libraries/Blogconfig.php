<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev:: 490           $:  Revision of last commit 
$Author: ashishj $:  Author of last commit 
$Date: 2008-05-16 11:59:57 $:  Date of last commit 
 
This class provides the Message Board Server Library.
 
$Id: Blogconfig.php,v 1.2 2008-05-16 11:59:57 ashishj Exp $:  
 
*/ 


class Blogconfig{

	

	//Table Name
	public $categoryTable = 'categoryBoardTable';
	public $messageTable = 'blogTable';
	
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
