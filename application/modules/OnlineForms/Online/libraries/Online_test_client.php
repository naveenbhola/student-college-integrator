<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: manishz $:  Author of last commit
$Date: 2010/02/19 06:18:53 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.
$Id: Message_board_client.php,v 1.73 2010/02/19 06:18:53 manishz Exp $: 
*/
class Online_test_client  {

	var $CI;
	var $CI_operation;
	var $cacheLib;
	function init($what='read')
	{
		$this->CI_operation = & get_instance();
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$server_url = OT_READ_SERVER;
		$server_port = OT_READ_SERVER_PORT;
		if($what=='write'){
	        $server_url = OT_WRITE_SERVER;
		$server_port = OT_WRITE_SERVER_PORT;
		}
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,$server_port );
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
	}

    function getOnlineTestData($appId,$userId,$exam,$duration,$section,$level){
		$this->init('write');
		$this->CI->xmlrpc->method('getOnlineTestData');
		$request = array($appId,$userId,$exam,$duration,$section,$level);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
		}else{
				$response = $this->CI->xmlrpc->display_response();
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				return $response;
		}
     }

	 function getUsersOnlineTest($appId, $userId){
		$this->init();
		$this->CI->xmlrpc->method('getUsersOnlineTest');
		$request = array($appId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
		}else{
				$response = $this->CI->xmlrpc->display_response();
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				return $response;
		}
	 }

}
