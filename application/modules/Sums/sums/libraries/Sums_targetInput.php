<?php

/**
 * Class Sums_targetInput represents Query APIs for Sums_targetInput
 *
 * @package targetInput
 * @author  Shiksha Team
 */

class Sums_targetInput {
	
	var $CI="";
	
	function init()  {
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "https://$ip/sums/targetInputServer";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}
	
	function initread()  {
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/sums/targetInputServer";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}
	
	function getTargetDetails($appId,$targetInputId) 
		{
		$this->initread();
		$this->CI->xmlrpc->method('getTargetDetails');
		$request = array($appId,$targetInputId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			error_log_shiksha("error123 ravi");
			return $this->CI->xmlrpc->display_error();
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
		}
	
	function updateTargetDetails($appId,$targetId,$branch_id,$executive_id,
								 $assigned_by,$quarter,$year,$target) {
		$this->init();
		$this->CI->xmlrpc->method('updateTargetDetails');
		$request = array($appId,$targetId,$branch_id,$executive_id,
			$assigned_by,$quarter,$year,$target);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			error_log_shiksha("error123 ravi");
			return $this->CI->xmlrpc->display_error();
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
	}
	
	function deleteTarget($appId,$targetInputId)
		{
		$this->init();
		$this->CI->xmlrpc->method('deleteTarget');
		$request = array($appId,$targetInputId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			error_log_shiksha("error123 ravi");
			return $this->CI->xmlrpc->display_error();
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
		}
	
	function getALLTargetBranch($appId,$branchId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getALLTargetBranch');
		$request = array($appId,$branchId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			error_log_shiksha("error123 ravi");
			return $this->CI->xmlrpc->display_error();
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
		}
	
	function getALLTarget($appId,$branchId,$quarter,$year,$executiveId,$assigned_by)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getALLTarget');
		$request = array($appId,$branchId,$quarter,$year,$executiveId,$assigned_by);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			error_log_shiksha("error123 ravi");
			return $this->CI->xmlrpc->display_error();
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
		}
	
	function currQuarterFY()
		{
		return $currQuarter = ceil(( date('n') + ( 21 -  ( 4 -1 ))) /3 ) %4 +1 ;
		}
	
	function getAllQuarters($appId) {
		$this->initread();
		$this->CI->xmlrpc->method('getAllQuarters');
		$requestArr = array (array($appId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request()) {
			return $this->CI->xmlrpc->display_error();
		}
		else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function getExecutiveData($appId,$ExecutiveArray,$branchId,$year,$Quarter) {
		$this->initread();
		$this->CI->xmlrpc->method('getExecutiveData');
		$requestArr = array ($appId,json_encode($ExecutiveArray),$branchId,$year,$Quarter);
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request()) {
			return $this->CI->xmlrpc->display_error();
		}
		else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function updateExecutiveData($appId,$ExecutiveArray,$branchId,$year,$Quarter,$assigned_by) {
		$this->initread();
		$this->CI->xmlrpc->method('updateExecutiveData');
		$requestArr = array ($appId,json_encode($ExecutiveArray),$branchId,$year,$Quarter,$assigned_by);
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request()) {
			return $this->CI->xmlrpc->display_error();
		}
		else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function handleReport($appId,$report_type,$search_array) {
		$this->initread();
		if ($report_type == 'Month_till_date_sales_Report') {
			$this->CI->xmlrpc->method('Month_till_date_sales_Report');	
		} elseif ($report_type == 'Quarter_till_date_sales_Report') {
			$this->CI->xmlrpc->method('Quarter_till_date_sales_Report');	
		} elseif ($report_type == 'Product_MIX_Report') {
			$this->CI->xmlrpc->method('Product_MIX_Report');	
		}
		$requestArr = array ($appId,$report_type,json_encode($search_array));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request()) {
			return $this->CI->xmlrpc->display_error();
		}
		else {
			return $this->CI->xmlrpc->display_response();
		}
	}
}

?>
