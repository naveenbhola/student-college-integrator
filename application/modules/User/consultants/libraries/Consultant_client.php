<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: shirish $:  Author of last commit
$Date: 2009-08-13 10:43:46 $:  Date of last commit

Consultant_client.php makes call to server using XML RPC calls.

$Id: Consultant_client.php,v 1.3 2009-08-13 10:43:46 shirish Exp $: 

*/
class Consultant_client
{
	var $CI = '';   
	
	function init()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		//$server_url = 'https://172.16.0.160/shirish/alert_server/';		
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server(CONSULTANT_SERVER, CONSULTANT_SERVER_PORT);			
	}

	function addConsultant($consultant_name,$consultant_email,$consultant_mobile,$consultant_address,$consultant_branceOfficeCity,$consultant_categories,$consultant_countries,$consultant_startDate,$consultant_endDate,$consultant_fundSource,$userId)
	{
		error_log(print_r(array($consultant_name,$consultant_email,$consultant_mobile,$consultant_address,$consultant_branceOfficeCity,$consultant_categories,$consultant_countries,$consultant_startDate,$consultant_endDate,$consultant_fundSource,$userId),true));
		$this->init();
		$this->CI->xmlrpc->method('addConsultant');	
		$request = array (
		array($consultant_name, 'string'),
		array($consultant_email, 'string'),
		array($consultant_mobile, 'string'),
		array($consultant_address, 'string'),
		array($consultant_branceOfficeCity, 'string'),
		array($consultant_categories, 'struct'),
		array($consultant_countries, 'struct'),
		array($consultant_startDate, 'string'),
		array($consultant_endDate, 'string'),
		array($consultant_fundSource, 'struct'),
		array($userId, 'string'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 error_log("Error : ".$this->CI->xmlrpc->display_error());
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 error_log("Success : ".$this->CI->xmlrpc->display_response());
		 return $this->CI->xmlrpc->display_response();
		}
	}

	function editConsultant($consultant_name,$consultant_email,$consultant_mobile,$consultant_address,$consultant_branceOfficeCity,$consultant_categories,$consultant_countries,$consultant_startDate,$consultant_endDate,$consultant_fundSource,$userId, $consultant_id)
	{
		error_log(print_r(array($consultant_name,$consultant_email,$consultant_mobile,$consultant_address,$consultant_branceOfficeCity,$consultant_categories,$consultant_countries,$consultant_startDate,$consultant_endDate,$consultant_fundSource,$userId,$consultant_id),true). " Shirish");
		$this->init();
		$this->CI->xmlrpc->method('editConsultant');	
		$request = array (
		array($consultant_name, 'string'),
		array($consultant_email, 'string'),
		array($consultant_mobile, 'string'),
		array($consultant_address, 'string'),
		array($consultant_branceOfficeCity, 'string'),
		array($consultant_categories, 'struct'),
		array($consultant_countries, 'struct'),
		array($consultant_startDate, 'string'),
		array($consultant_endDate, 'string'),
		array($consultant_fundSource, 'struct'),
		array($userId, 'string'),
		array($consultant_id, 'string'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 error_log("Error : ".$this->CI->xmlrpc->display_error());
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 error_log("Success : ".$this->CI->xmlrpc->display_response());
		 return $this->CI->xmlrpc->display_response();
		}
	}


	function getConsultantData($consultant_id)
	{
		$this->init();
		$this->CI->xmlrpc->method('getConsultantData');	
		$request = array (
		array($consultant_id, 'int'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 error_log("Error : ".$this->CI->xmlrpc->display_error());
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 error_log("Success : ".$this->CI->xmlrpc->display_response());
		 return $this->CI->xmlrpc->display_response();
		}
	}

	function checkConsultantName($consultant_id,$consultant_name)
	{
		$this->init();
		$this->CI->xmlrpc->method('checkConsultantName');	
		$request = array (
		array($consultant_id, 'int'),
		array($consultant_name, 'string'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 error_log("Error : ".$this->CI->xmlrpc->display_error());
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 error_log("Success : ".$this->CI->xmlrpc->display_response());
		 return $this->CI->xmlrpc->display_response();
		}
	}


	function deleteConsultant($consultant_id)
	{
		$this->init();
		$this->CI->xmlrpc->method('deleteConsultant');	
		$request = array (
		array($consultant_id, 'int'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 error_log("Error : ".$this->CI->xmlrpc->display_error());
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 error_log("Success : ".$this->CI->xmlrpc->display_response());
		 return $this->CI->xmlrpc->display_response();
		}
	}
	function getConsultantListByKeyword($keyword,$startCount,$rowCount,$sortField)
	{
		$this->init();
		$this->CI->xmlrpc->method('getConsultantListByKeyword');	
		$request = array (
		array($keyword, 'string'),
		array($startCount, 'int'),
		array($rowCount, 'int'),
		array($sortField, 'string'),   	

		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 error_log("Error : ".$this->CI->xmlrpc->display_error());
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 error_log("Success : ".$this->CI->xmlrpc->display_response());
		 return $this->CI->xmlrpc->display_response();
		}
	}

	function getConsultantList($cityId,$countryId,$categoryId,$startCount,$rowCount,$sortField)
	{
		$this->init();
		$this->CI->xmlrpc->method('getConsultantList');	
		$request = array (
		array($cityId, 'int'),
		array($countryId, 'int'),
		array($categoryId, 'int'),
		array($startCount, 'int'),
		array($rowCount, 'int'),
		array($sortField, 'string'),   	
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 error_log("Error : ".$this->CI->xmlrpc->display_error());
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 error_log("Success : ".$this->CI->xmlrpc->display_response());
		 return $this->CI->xmlrpc->display_response();
		}
	}
} 

