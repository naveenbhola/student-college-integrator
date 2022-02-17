<?php 

class Recommendation_Front_Lib
{
	private $_ci;
	private $_error_msg;
	
	function __construct()
	{
		$this->_ci = & get_instance();
		$this->_ci->load->library('xmlrpc');
	}
	
	function setError($error_msg)
	{
		$this->_error_msg = $error_msg;
	}
	
	function getError()
	{
		return $this->_error_msg;
	}
	
	private function _setMode($mode = 'read')
	{
		$server_url = RECOMMENDATION_READ_SERVER;
		$server_port = RECOMMENDATION_READ_SERVER_PORT;
		
		if($mode == 'write')
		{
			$server_url = RECOMMENDATION_WRITE_SERVER;
			$server_port = RECOMMENDATION_WRITE_SERVER_PORT;
		}
	
		$this->_ci->xmlrpc->set_debug(0);
		$this->_ci->xmlrpc->server($server_url,$server_port );
	}
		
	function getRecommendations($user_request_data, $numResultsPerUser = 0, $disableExclusion = 'no')
	{
		$this->_setMode('read');
		
		$this->_ci->xmlrpc->method('getRecommendations');
		
		$request = array(array($user_request_data,'struct'), array($numResultsPerUser,'string'), array($disableExclusion,'string'));
		$this->_ci->xmlrpc->request($request);
		
		if (!$this->_ci->xmlrpc->send_request())
		{
			$this->setError($this->_ci->xmlrpc->display_error());
		}
		else
		{
			$response = ($this->_ci->xmlrpc->display_response());
			return json_decode(base64_decode($response), TRUE);
		}
	}
		
	function processRecommendationMailer($listing_type,$recommendation_id)
	{
		$this->_setMode('read');
		
		$this->_ci->xmlrpc->method('processRecommendationMailer');
		
		$request = array($listing_type,$recommendation_id); 
		$this->_ci->xmlrpc->request($request);
		
		if (!$this->_ci->xmlrpc->send_request())
		{
			$this->setError($this->_ci->xmlrpc->display_error());
			return -1;
		}
		else
		{
			$response = ($this->_ci->xmlrpc->display_response());
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function registerClickOnRecommendation($recommendation_id,$listing_id,$listing_type)
	{
		$this->_setMode('write');
		
		$this->_ci->xmlrpc->method('registerClickOnRecommendation');
		
		$request = array($recommendation_id,$listing_id,$listing_type); 
		$this->_ci->xmlrpc->request($request);
		
		if (!$this->_ci->xmlrpc->send_request())
		{
			$this->setError($this->_ci->xmlrpc->display_error());
			return -1;
		}
		else
		{
			$response = ($this->_ci->xmlrpc->display_response());
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
}