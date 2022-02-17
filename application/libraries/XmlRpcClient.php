<?php

class XmlRpcClient
{
	protected $CI;
	protected $error;
	protected $server;
	protected $serverFile;
	
	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->library('xmlrpc');
		$this->CI->xmlrpc->set_debug(0);
	}
	
    protected function setServer($mode)
    {
		global $shikshaServers;
		
		$server = "http://".$shikshaServers[$this->server][$mode]['ip']."/".$this->server."/servers/".$this->serverFile;
		$port = $shikshaServers[$this->server][$mode]['port'];
        $this->CI->xmlrpc->server($server, $port);
    }
	
	protected function executeRequest($serverMethod,$request,$mode = 'read',$decode = TRUE)
	{
		$this->setServer($mode);
		$this->CI->xmlrpc->method($serverMethod);
		$this->CI->xmlrpc->request($request);
		
		if (!$this->CI->xmlrpc->send_request()) {
			$this->setError($this->CI->xmlrpc->display_error());
			return FALSE;
		}
		else {
			$response = ($this->CI->xmlrpc->display_response());
			return $decode ? utility_decodeXmlRpcResponse($response) : $response;
		}
	}
	
	public function setError($error)
	{
		$this->error = $error;
	}
	
	public function getError()
	{
		return $this->error;
	}
}