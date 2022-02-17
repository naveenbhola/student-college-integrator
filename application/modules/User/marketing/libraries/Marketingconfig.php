<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::               $:  Revision of last commit 
$Author: vibhu $:  Author of last commit 
$Date: 2009-05-08 12:37:33 $:  Date of last commit 
 
This class provides the Event Calendar Server Library.
 
$Id: Marketingconfig.php,v 1.1 2009-05-08 12:37:33 vibhu Exp $:  
 
*/ 


class Marketingconfig{

    //Table Name

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
        $config['database'] = "shiksha";
        $config['dbdriver'] = "mysqli";
        $config['dbprefix'] = "";
        $config['pconnect'] = TRUE;
        $config['db_debug'] = TRUE;
        $config['active_r'] = TRUE;
    }
}

?>
