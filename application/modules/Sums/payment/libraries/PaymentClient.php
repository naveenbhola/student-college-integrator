<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-09-09 06:12:09 $:  Date of last commit


$Id: PaymentClient.php,v 1.7 2008-09-09 06:12:09 amitj Exp $: 

*/

class PaymentClient{
    var $CI_Instance;
	function init(){
        $this->CI_Instance = & get_instance();
		$this->CI_Instance->load->library('xmlrpc');
		$this->CI_Instance->xmlrpc->set_debug(0);
		$this->CI_Instance->xmlrpc->server(PAYMENT_SERVER_URL,PAYMENT_SERVER_PORT);			
	}


    function getProductData($appID, $productID = 0){
		$this->init();	
        error_log_shiksha("in getProductData of client library");
		$this->CI_Instance->xmlrpc->method('getProductData');
		$request = array($appID, $productID);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
	}

    function getProductForUser($appID, $userId){
		$this->init();	
        error_log_shiksha("in getProductForUser of client library");
		$this->CI_Instance->xmlrpc->method('getProductForUser');
		$request = array($appID, $userId);
        error_log_shiksha("doooooommmmmmmmm".$userId);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
	}

    function updateUserAsset($appID, $userId, $val, $type){
		$this->init();	
        error_log_shiksha("in updateUserAsset of client library");
		$this->CI_Instance->xmlrpc->method('updateUserAsset');
		$request = array($appID, $userId, $val,$type);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
	}






    function addTransaction($appID, $productID,$userId,$paymentOption){
		$this->init();	
        error_log_shiksha("in addTransaction of client library");
		$this->CI_Instance->xmlrpc->method('addTransaction');
		$request = array($appID, $productID,$userId,$paymentOption);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
	}

        function getTransactionHistory($appID,$userId){
		$this->init();	
                error_log_shiksha("in getTransactionHistory of client library");
		$this->CI_Instance->xmlrpc->method('sgetTransactionHistory');
                $request = array($appID, $userId);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
	}

}
?>
