<?php

class SupportServer extends MX_Controller
{
    private $model;
    
    function index()
	{ 
		$this->dbLibObj = DbLibCommon::getInstance('User');
		$dbHandle = $this->_loadDatabaseHandle('write');
		
        $this->load->model('support/support_model');
        $this->model = new support_model($dbHandle);
      
		$this->load->library(array('xmlrpc','xmlrpcs'));
		$config['functions']['getUserDetails'] = array('function' => 'SupportServer.getUserDetails');
        $config['functions']['getUserByEmail'] = array('function' => 'SupportServer.getUserByEmail');
        $config['functions']['blockUser'] = array('function' => 'SupportServer.blockUser');
        $config['functions']['editUser'] = array('function' => 'SupportServer.editUser');
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
    
    function getUserDetails($request)
    {
        $parameters = $request->output_parameters();
		$userId = $parameters[0];
        
        $userDetails = $this->model->getUserDetails($userId);
        return $this->xmlrpc->send_response(utility_encodeXmlRpcResponse($userDetails));	
    }
    
    function getUserByEmail($request)
    {
        $parameters = $request->output_parameters();
		$email = $parameters[0];
        
        $userId = $this->model->getUserByEmail($email);
        return $this->xmlrpc->send_response(array($userId));	
    }
    
    function blockUser($request)
    {
        $parameters = $request->output_parameters();
		$userId = $parameters[0];
        $loggedInUserId = $parameters[1];
        
        $result = $this->model->blockUser($userId,$loggedInUserId);
        return $this->xmlrpc->send_response(utility_encodeXmlRpcResponse(array($result)));	
    }
    
    function editUser($request)
    {
        $parameters = $request->output_parameters();
		$userId = $parameters[0];
        $displayName = $parameters[1];
        $email = $parameters[2];
		$mobile = $parameters[3];
		$userGroup = $parameters[4];
        $loggedInUserId = $parameters[5];
        
        $result = $this->model->editUser($userId,$displayName,$email,$mobile,$userGroup,$loggedInUserId);
        return $this->xmlrpc->send_response(utility_encodeXmlRpcResponse($result));	
    }
}