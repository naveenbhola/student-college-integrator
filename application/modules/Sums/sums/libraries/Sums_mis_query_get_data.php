<?php

/**
 * Class Sums_mis_query_get_data represents Query APIs for MIS
 *
 * @package Sums_mis_query_get_data
 * @author  Shiksha Team
 */

class Sums_mis_query_get_data {
	
	var $CI="";
	
	function init()  {
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		
		$server_url = "https://$ip/sums/mis_server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}
	
	function initread()  {
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		
		$server_url = "https://$searchIP/sums/mis_server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}
	function getMISReportData ($appId,$request,$type,$Inventry_type=NULL,$id,$result,$num,$report_action,$branchIds) {
		$this->initread();
		if ($type == 'transaction' ) {
			$this->CI->xmlrpc->method('getTransactionMIS');
		} else if ($type == 'payment') {
			$this->CI->xmlrpc->method('getPaymentMIS');
		} else if ($type == 'inventory') {
			if ($Inventry_type != NULL) {
				if( $Inventry_type == 'bcpm' ) {
					$this->CI->xmlrpc->method('getProductMIS');	
				} else if ($Inventry_type == 'csm') {
					$this->CI->xmlrpc->method('getInventryMIS');
				} else if ($Inventry_type == 'scpim') {
					$this->CI->xmlrpc->method('getShikshaInventryMIS');
				} else if ($Inventry_type == 'sspm') {
					$this->CI->xmlrpc->method('getShikshaInventryMIS');
				}
			}
		}
		$requestArr = array();
		for ($i = 0; $i < count($request); $i++) {
			$requestArr[$i] = array($request[$i],'string');
		}
		$requestArr[]  = array ($id,'int');
		//Params for pagination
		$requestArr[]  = array ($result,'int');
		$requestArr[]  = array ($num,'int');
		$requestArr[]  = array ($report_action,'string');
		if ((strlen($branchIds) > 0) && ($branchIds != '') ) {
			$requestArr[]  = array ($branchIds,'string');
		}
		//print_r($requestArr);
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request()) {
			return $this->CI->xmlrpc->display_error();
		}
		else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	function getdata($appId,$transactionId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getdata');
		$requestArr = array (array($appId,'int'),array($transactionId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		
		
		}
	
	function getTransactionAndPaymentDetails($appId,$transactionId,$Part_Number=1)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getTransactionAndPaymentDetails');
		$requestArr = array (array($appId,'int'),array($transactionId,'int'),array($Part_Number,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
		
		
	function getClientTransactionDetails($appId,$transactionId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetClientTransactionDetails');
		
		$requestArr = array (array($appId,'int'),array($transactionId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}	

     function getEditPaymentDetails($appId,$transactionId)
         {
        $this->initread();
        $this->CI->xmlrpc->method('sgetEditPaymentDetails');

        $requestArr = array (array($appId,'int'),array($transactionId,'int'));
        $this->CI->xmlrpc->request($requestArr);
        if (!$this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
       }

       function getPartPaymentHistory($appId,$paymentId,$partNumber)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getPartPaymentHistory');
		$requestArr = array (array($appId,'int'),array($paymentId,'int'),array($partNumber,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
}

?>
