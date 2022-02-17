<?php

/**
 *
 *  
 * 
 * @category LDB
 * @author Shiksha Team
 * @link https://www.shiksha.com
 */
	
	class Ldbmis_client {
	
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
			global $bit64ServerIP;
			$server_url = "https://$bit64ServerIP/MIS/Ldbmis_server";
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
			global $bit64ServerIP;
			$server_url = "https://$bit64ServerIP/MIS/Ldbmis_server";
			$this->CI->xmlrpc->set_debug(0);
			$this->CI->xmlrpc->timeout(6000);
			$this->CI->xmlrpc->server($server_url,80);
		}
	
		function getdataforsearchagent()
		{
			$this->initread();
			$this->CI->xmlrpc->method('getsearchagents');
			
		
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
			}

		}
	
		function getnewsearchsagents()
		{
			$this->initread();
			$this->CI->xmlrpc->method('newsearchagents');
			
			
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
			}	
			
			
			
			
		}
		
		function getleadviewdata()
		{
			$this->initread();
			$this->CI->xmlrpc->method('getleads');
	
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
			}	
			
			
			
			
		}	
	
	
		function getresponseviewdata()
		{
			$this->initread();
			$this->CI->xmlrpc->method('getresponseviewdata');
	
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
			}	
			
			
		}	

		function getleadsallocatedtosearchagent($appID,$agentid,$startDate,$endDate,$clientid,$status)
		{
			$this->initread();
			$this->CI->xmlrpc->method('getleadsallocatedtosearchagent');
			$request = array($appID,$agentid,$startDate,$endDate,$clientid,$status);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
			}
		}
		
	    function getleadsallocatedtoclient($appID,$clientid,$days)
		{
			$this->initread();
			$this->CI->xmlrpc->method('getleadsallocatedtoclient');
			$request = array($appID,$clientid,$days);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
			}
		}
		
		function getsalesperson()
		{
			$this->initread();
			$this->CI->xmlrpc->method('getsalesperson');
			
			
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
			}


		}


		function updateAttachment($appId)
		{
			$this->initread();
			$this->CI->xmlrpc->method('updateAttachment');	
			$request = array (
					array($appId, 'int'),
					'struct'			
					);
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
	
	
	
	
	
	
