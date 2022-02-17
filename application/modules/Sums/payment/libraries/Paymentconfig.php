<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/* 
 
Copyright 2007 Info Edge India Ltd 
 
$Rev::               $:  Revision of last commit 
$Author: puneet $:  Author of last commit 
$Date: 2008-03-14 10:27:48 $:  Date of last commit 
 
This class provides the Event Calendar Server Library.
 
$Id: Paymentconfig.php,v 1.3 2008-03-14 10:27:48 puneet Exp $:  
 
*/ 


class Paymentconfig{

    //Table Name
    public $productTable = 'tProduct';
    public $assetTable = 'tAsset';
    public $productPropertyTable = 'tProduct_Property';
    public $transactionPropertyTable = 'tTransaction_Property';
    public $transactionTable = 'tTransaction';
    public $assetLogTable = 'tAssetLog';

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
