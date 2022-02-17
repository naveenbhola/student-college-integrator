<?php

class Support_lib
{
    private $CI;

	function __construct()
	{
        global $ip;
        
		$this->CI = & get_instance();
		$this->CI->load->library('xmlrpc');
        
        $server = 'http://'.$ip.'/support/SupportServer';
        $port = 80;
		$this->CI->xmlrpc->server($server,$port);
	}
	
	public function getUserDetails($userId)
    {
		$this->CI->xmlrpc->method('getUserDetails');
		
		$request = array($userId);
		$this->CI->xmlrpc->request($request);
		
		if (!$this->CI->xmlrpc->send_request())
		{		
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return utility_decodeXmlRpcResponse($this->CI->xmlrpc->display_response());
		}
    }
	
	public function getUserByEmail($email)
    {
		$this->CI->xmlrpc->method('getUserByEmail');
		
		$request = array($email);
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
	
	public function blockUser($userId,$loggedInUserId)
    {
		$this->CI->xmlrpc->method('blockUser');
		
		$request = array($userId,$loggedInUserId);
		$this->CI->xmlrpc->request($request);
		
		if (!$this->CI->xmlrpc->send_request())
		{		
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return utility_decodeXmlRpcResponse($this->CI->xmlrpc->display_response());
		}
    }
	
	public function editUser($userId,$displayName,$email,$mobile,$userGroup,$loggedInUserId)
    {
		$this->CI->xmlrpc->method('editUser');
		
		$request = array($userId,$displayName,$email,$mobile,$userGroup,$loggedInUserId);
		$this->CI->xmlrpc->request($request);
		
		if (!$this->CI->xmlrpc->send_request())
		{		
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return utility_decodeXmlRpcResponse($this->CI->xmlrpc->display_response());
		}
    }
}