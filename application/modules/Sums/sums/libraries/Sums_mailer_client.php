<?php

class Sums_mailer_client
{
	var $CI="";
	var $requests_array = array();
	function init()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "https://$ip/sums/SumsMailerServer";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	
	function initread()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/sums/SumsMailerServer";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	function sendSumsMails($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('ssendSumsMails');
		$requestStr  = json_encode($request);
		$requestArr = array($appId,$requestStr);
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
	
	function getCronMails($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('sgetCronMails');
		if(!$this->is_valid_request($request)) {
			$request = 'ERROR_ON_REQUEST';
		}
		$requestArr = array (array($appId,'int'),array($request,'string'));
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
	
	function is_valid_request($request)
		{
		if (is_array($this->requests_array)) {
			$this->requests_array = array(
				'15_DAYS_DUE_PAYMENT_NOTIFICATION'=>'15_DAYS_DUE_PAYMENT_NOTIFICATION',
				'DUE_PAYMENT_NOTIFICATION'=>'DUE_PAYMENT_NOTIFICATION'
			);
			if (array_key_exists($request, $this->requests_array)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
		}
	
	function transactionDetailStandAlone($transactionId)
		{
		$this->init();
		$this->CI->xmlrpc->method('stransactionDetailStandAlone');
		$request = array($transactionId);
		$this->CI->xmlrpc->request($request);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{	
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	
}
?>
