<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev:: 71            $:  Revision of last commit 
$Author: puneet $:  Author of last commit 
$Date: 2008-03-14 10:27:48 $:  Date of last commit 
 
This class provides the Listing Server Library.
 
$Id: Listingconfig.php,v 1.4 2008-03-14 10:27:48 puneet Exp $:  
 
*/ 


class Listingconfig{
    //Table(s) Name
    public $auditTable = 'audit_table';
    public $categoryTable = 'category';
    public $courseDetailsTable = 'course_details';
    public $instituteTable = 'institute';
    public $iun_dbTable = 'iun_db';
    public $listingMainTable = 'listings_main';
    public $countryTable = 'countryTable';
    public $countryCityTable = 'countryCityTable';
    public $otherCoursesTable = 'otherCourses';

    /*
     * Course Info Types
     */
        
    public static $course_info_types = array(
    	'general' => array(
    						'file_name' => 'course_generalinfo',
    						'class_name' => 'Course_GeneralInfo'
    					  ),
    	'salientfeatures' => array(
    								'file_name' => 'course_salientfeatures',
    								'class_name' => 'Course_SalientFeatures'
    					  		  ),
    	'attributes' => array(
    							'file_name' => 'course_attributes',
    							'class_name' => 'Course_Attributes'
    					  	 ),
    	'exams' => array(
    						'file_name' => 'course_exams',
    						'class_name' => 'Course_Exams'
    					)
    );
    
     /*
     * Institute Info Types
     */
        
    public static $institute_info_types = array(
    	'general' => array(
    						'file_name' => 'institute_generalinfo',
    						'class_name' => 'Institute_GeneralInfo'
    					  ),
    	'headerimage' => array(
    								'file_name' => 'institute_headerimage',
    								'class_name' => 'Institute_HeaderImage'
    					  		  )
    );
    
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


    public function getDbConfig_test($appID,&$config){	
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
    
    public static function getCourseInfoTypes()
    {
    	return array(self::COURSE_INFO_GENERAL,
    				 self::COURSE_INFO_SALIENT_FEATURES,
    				 self::COURSE_INFO_ATTRIBUTES,
    				 self::COURSE_INFO_EXAMS);
    }
}
?>
