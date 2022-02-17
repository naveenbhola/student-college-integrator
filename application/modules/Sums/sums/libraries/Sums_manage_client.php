<?php

class Sums_Manage_client
{
	var $CI="";
	function init()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "https://$ip/sums/Manage_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	
	function initread()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/sums/Manage_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	
	function getUsers($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getUsers');
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
	
	function getUserForQuotation($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetUserForQuotation');
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
	
	function getUserGroupList($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetUserGroupList');
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
	
	function getBranchList($appId,$request,$userId='-1')
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetBranchList');
		$requestArr = array (array($appId,'int'),array($request,'struct'),array($userId,'int'));
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
	
	function getManagerList($appId,$branchIds,$userGroupId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetManagerList');
		$requestArr = array (array($appId,'int'),array($branchIds,'string'),array($userGroupId,'string'));
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
	
	function addSumsUser($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('saddSumsUser');
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
	
	function getSumsUserInfo($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetSumsUserInfo');
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
	
	function getSumsUserACL($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetSumsUserACL');
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
	
	function getSumsUsers($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetSumsUsers');
		$requestArr = array($appId,'int');
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
	
	function getBranches($appId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetBranches');
		$requestArr = array($appId,'int');
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
	
	function approveManager($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('sapproveManager');
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
	
	function approveFinance($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('sapproveFinance');
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
	
	function approveOps($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('sapproveOps');
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
	
	function approveOpsDerived($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('sapproveOpsDerived');
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
	
	function cancelTransaction($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('scancelTransaction');
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

        function findClientTypeByBaseCatAndSubCat($clientId,$BaseCategory,$BaseSubCategory)
	{
		$this->initread();
                $this->CI->xmlrpc->method('sfindClientTypeByBaseCatAndSubCat');
		$requestArr = array (array($clientId,'int'),array($BaseCategory,'string'),array($BaseSubCategory,'string'));
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
