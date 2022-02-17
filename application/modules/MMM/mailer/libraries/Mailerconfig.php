<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::               $:  Revision of last commit 
$Author: ankurg $:  Author of last commit 
$Date: 2010/09/07 07:20:00 $:  Date of last commit 
 
This class provides the Event Calendar Server Library.
 
$Id: Mailerconfig.php,v 1.2.110.1 2010/09/07 07:20:00 ankurg Exp $:  
 
*/ 


class Mailerconfig{

    //Table Name

    var $CI_Instance;

    var $dbLib;
    public $listUserMaximumLimit = 100000;

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
        $config['database'] = "mailer";
        $config['dbdriver'] = "mysqli";
        $config['dbprefix'] = "";
        $config['pconnect'] = TRUE;
        $config['db_debug'] = TRUE;
        $config['active_r'] = TRUE;
    }
}

?>
