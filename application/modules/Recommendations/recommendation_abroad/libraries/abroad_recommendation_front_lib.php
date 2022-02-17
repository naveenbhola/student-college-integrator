<?php 

class Abroad_Recommendation_Front_Lib
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
		$server_url = RECOMMENDATION_ABROAD_READ_SERVER;
		$server_port = RECOMMENDATION_ABROAD_READ_SERVER_PORT;
		
		if($mode == 'write')
		{
			$server_url = RECOMMENDATION_ABROAD_WRITE_SERVER;
			$server_port = RECOMMENDATION_ABROAD_WRITE_SERVER_PORT;
		}
	
		$this->_ci->xmlrpc->set_debug(0);
		$this->_ci->xmlrpc->server($server_url,$server_port );
	}
		
	function getRecommendations($user_request_data, $disableExclusion = 'no', $numResultsPerUser = 0)
	{
		$this->_setMode('read');
		
		$this->_ci->xmlrpc->method('getRecommendations');
		
		$request = array(array($user_request_data,'struct'), array($disableExclusion,'string'), array($numResultsPerUser,'string'));
		$this->_ci->xmlrpc->request($request);
		
		if (!$this->_ci->xmlrpc->send_request())
		{
			$this->setError($this->_ci->xmlrpc->display_error());
		}
		else
		{
			$response = ($this->_ci->xmlrpc->display_response());
			return $response;
		}
	}
}