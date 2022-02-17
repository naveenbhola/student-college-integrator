<?php

/**
 *
 *  
 * 
 * @category LDB
 * @author Shiksha Team
 * @link http://www.shiksha.com
 */

class CRMFeedback_Client {

	var $CI_Instance;

	/**
	 * init API 4 write DB call
	 */

	function init()
	{
		set_time_limit(0);
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "https://$ip/crm/CRMFeedback_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->timeout(6000);
		$this->CI->xmlrpc->server($server_url,80);
	}

	/**
	 * initread API for Read DB call
	 */

	function initread()
	{
		set_time_limit(0);
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/crm/CRMFeedback_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->timeout(6000);
		$this->CI->xmlrpc->server($server_url,80);
	}



	function getlistingdetails($appId,$clientId) {


		$this->initread();
		error_log("1");
		$this->CI->xmlrpc->method('getlistingdetails');
		$request = array(array($appId,'int'),array($clientId,'int'));

		$this->CI->xmlrpc->request($request);

		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}


	function getrespectivecourses($clientId)
	{

		$this->initread();
		$this->CI->xmlrpc->method('getrespectivecourses');

		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}



	}


	function getinstituteids($appID,$clientId) {
		$this->initread();
		$this->CI->xmlrpc->method('getinstituteids');
		$request = array($appID, $clientId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}



	function getlistingdetailsforlistingtypeid($course_ids,$institute_ids) {
		$this->initread();
		$this->CI->xmlrpc->method('getlistingdetailsforlistingtypeid');
		$request = array($course_ids, $institute_ids);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}


	function getcourseids($course_ids,$institute_ids) {
		$this->initread();
		$this->CI->xmlrpc->method('getcourseids');
		$request = array($course_ids, $institute_ids);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}



	function getcourselist($appId,$Id) {
		$this->initread();
		$this->CI->xmlrpc->method('getcourselist');
		$request = array($appId,$Id);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}
	

	function EnterpriseUserRegisterFeedback($appID,$requestArray)
	{
		$this->init();
		$this->CI->xmlrpc->method('EnterpriseUserRegisterFeedback');
		$request = array(array($appID,'int'),array($requestArray,'struct'));
		$this->CI->xmlrpc->request($request);	
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		} 	
	}


}
