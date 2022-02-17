<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::             $:  Revision of last commit 
$Author: shashwats $:  Author of last commit 
$Date: 2008-04-24 14:02:48 $:  Date of last commit 
 
This class provides the Enterprise Server Library.
 
$Id: Enterpriseconfig.php,v 1.2 2008-04-24 14:02:48 shashwats Exp $:  
 
*/ 


class EnterpriseConfig{

        //Table(s) Name
        public $categoryTable = 'category';
        public $countryTable = 'countryTable';
        public $countryCityTable = 'countryCityTable';
        public $keyPageTable = 'KeyPageDb';
        public $pageBlogTable = 'PageBlogDb';
        public $pageMsgBoardTable = 'PageMsgBoardDb';
        public $pageAdmissionTable = 'PageAdmissionDb';
        public $pageScholarshipTable = 'PageScholDb';
        public $pageEventsTable = 'PageEventsDb';
        public $pageCourseColTable = 'PageCourseDb';
        public $pageCollegeTable = 'PageCollegeDb';
        public $pageNetworkTable = 'PageNetworkDb';

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
