<?php

class Sums_Quotation_client
{
	var $CI="";
	function init()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "https://$ip/sums/Quotation_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	
	function initread()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/sums/Quotation_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	function addQuotation ($appId,$quotationArray,$quotationProducts)
		{
		$this->init();
		$this->CI->xmlrpc->method('saddQuotation');
		$requestArr = array(
			array($appId,'int'),
			array($quotationArray,'struct'),
			array($quotationProducts,'struct')
		);
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
	
	function getQuotation($appId,$UIQuotationId,$quoteType)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetQuotation');
		$requestArr = array(array($appId,'int'),
			array($UIQuotationId,'string'),
			array($quoteType,'string')
		);
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
	
	function getQuotationById($appId,$QuotationId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetQuotationById');
		$requestArr = array(array($appId,'int'),
			array($QuotationId,'int')
		);
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
	
	function searchQuotation($appId, $request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('ssearchQuotation');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
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
	
	function getQuotationHistory($quotationId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetQuotationHistory');
		$requestArr = array (array($quotationId,'string'));
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
	
	function getPaymentDetails($UIQuotationId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetPaymentDetails');
		$requestArr = array (array($UIQuotationId,'string'));
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
	
	function updatePaymentInfo($appId,$paymentDetails,$paymentId,$partNumber)
		{
		$this->init();
		$this->CI->xmlrpc->method('supdatePaymentDetails');
		$requestArr = array (array($appId,'string'),array($paymentDetails,'struct'),array($paymentId,'int'),array($partNumber,'int'));
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
	
	function getQuotationDerivedProds($appId,$UIQuotationId,$derivedStatus)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetQuotationDerivedProds');
		$requestArr = array(array($appId,'int'),
			array($UIQuotationId,'string'),
			array($derivedStatus,'string')
		);
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
	
	function fetchBranchesForExecutive($appId,$execId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sfetchBranchesForExecutive');
		$requestArr = array (array($appId,'string'),array($execId,'string'));
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
	
	
}
?>
