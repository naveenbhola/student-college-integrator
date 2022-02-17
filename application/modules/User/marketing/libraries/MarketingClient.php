<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: shirish $:  Author of last commit
$Date: 2009-08-12 07:56:37 $:  Date of last commit


$Id: MarketingClient.php,v 1.4 2009-08-12 07:56:37 shirish Exp $:

*/

class MarketingClient{
    var $CI_Instance;
	function init(){
	//set_time_limit(0);
	ini_set('max_execution_time', '1800');
        $this->CI_Instance = & get_instance();
		$this->CI_Instance->load->library('xmlrpc');
		$this->CI_Instance->xmlrpc->set_debug(0);
		$this->CI_Instance->xmlrpc->timeout(5400);
		$this->CI_Instance->xmlrpc->server(MARKETING_SERVER_URL,MARKETING_SERVER_PORT);
        error_log("1234566 LKJ");
	}

    function registerUserForLead($appId, $userId,$userArr,$LeadInterest,$updateuserinfo,$keyvalue,$flag) {
        $this->init();
        error_log("123 LKJ");
	error_log(print_r($keyvalue,true).'KEYVALUE');
        $this->CI_Instance->xmlrpc->method('registerUserForLead');
        $userData = json_encode($userArr);
        $leadInterest = json_encode($LeadInterest);
//        $userUpdateData = json_encode($userUpdateArr);
        error_log("TYU ".$userUpdateData);
        $request = array($appID, $userId,$userData,$leadInterest,json_encode($updateuserinfo),json_encode($keyvalue),$flag);
        $this->CI_Instance->xmlrpc->request($request);

        error_log("123456789 LKJ".print_r($this->CI_Instance->xmlrpc->display_response(),true));
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function runGenerateLeadCron($appId,$page) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('runGenerateLeadCron');
        $request = array($appID,$page);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function runGenerateLeadCronForConsultants($appId,$page) {
	error_log("gasdasda");
        $this->init();
        $this->CI_Instance->xmlrpc->method('runGenerateLeadCronForConsultants');
        $request = array($appID,$page);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }


        
}
?>
