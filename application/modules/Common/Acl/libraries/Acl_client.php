<?php
class Acl_client{
	var $CI = '';   
	
	function init()
	{
		$this->CI_Instance = & get_instance();
		$this->CI_Instance->load->library('xmlrpc');
		$this->CI_Instance->xmlrpc->set_debug(0);error_log("ACL UserID==".print_r(ACL_SERVER_URL,true));error_log("ACL array==".print_r(ACL_SERVER_PORT,true));
		$this->CI_Instance->xmlrpc->server(ACL_SERVER_URL, ACL_SERVER_PORT);		
	}	
	
	function checkUserRight($userId,$userRights=array()){ //error_log("ACL UserID==".print_r($userId,true));error_log("ACL array==".print_r($userRights,true));
		$this->init();
		$this->CI_Instance->xmlrpc->method('checkUserRight');
		$request = array($userId,array($userRights,'array'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}
	
	function insertIntouserGroupsMappingTable($newLevelOfUser,$userId){
		$this->init();
		$this->CI_Instance->xmlrpc->method('insertIntouserGroupsMappingTable');
		$request = array($newLevelOfUser,$userId);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

}
?>
