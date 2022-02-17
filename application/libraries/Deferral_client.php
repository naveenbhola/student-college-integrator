<?php

class Deferral_client
{
	var $CI="";
	function init()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "http://$ip/shikshaStats/Deferral_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}

function getdetails($appId,$request)
	{
		$this->init();
		$this->CI->xmlrpc->method('getDetails');
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

function gettransactiondetails($appId,$request)
	{
		$this->init();
		$this->CI->xmlrpc->method('getTransactionDetails');
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

function getbranchNamebyID($appId,$branchList)
{
		$this->init();
		$this->CI->xmlrpc->method('getbranchNamebyID');
		$requestArr = array (array($appId,'int'),array($branchList,'struct'));
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

function uncountedTransaction($appId)
{
		$this->init();
		$this->CI->xmlrpc->method('UncountedTransaction');
		$requestArr = array (array($appId,'int'));
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


