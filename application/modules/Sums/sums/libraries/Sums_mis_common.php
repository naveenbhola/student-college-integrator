<?php

/**
 * Class Sums_mis_common represents common APIs for MIS
 *
 * @package Sums_mis_common
 * @author  Shiksha Team
 */

class Sums_mis_common {
	
	var $CI="";
	
	function init()  {
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();	
		global $ip;
		
		$server_url = "https://$ip/sums/Manage_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}
	
	function initread()  {
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();	
		global $searchIP;
		
		$server_url = "https://$searchIP/sums/Manage_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}
	
	function getAllBranches ($appId,$request,$userId) {
		$this->initread();
		$this->CI->xmlrpc->method('sgetBranchList');
		$requestArr = array (array($appId,'int'),array($request,'string'),array($userId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request()) {
			return $this->CI->xmlrpc->display_error();
		} else {
			$response = $this->CI->xmlrpc->display_response();
		}
		return $response;
	}
	
	function getAllExecutive ($appId,$branchIds,$userGroupId=NULL) {
		$this->initread();
		$this->CI->xmlrpc->method('sgetAllExecutiveList');
		$requestArr = array (array($appId,'int'),array($branchIds,'string'),array($userGroupId,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request()) {
			return $this->CI->xmlrpc->display_error();
		}
		else {
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getAllclients ($appId,$Executives)
	{
		$this->initread();
		$this->CI->xmlrpc->method('sgetAllClientsList');
		$requestArr = array (array($appId,'int'),array($Executives,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			$data = json_decode(base64_decode($this->CI->xmlrpc->display_response()),true);
			return $data;
		}
	}


	
}

?>