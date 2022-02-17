<?php
/**

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-01-17 05:30:10 $:  Date of last commit

MyShiksha_client.php makes call to server using XML RPC calls.

$Id: MyShiksha_client.php,v 1.1.1.1 2008-01-17 05:30:10 amitj Exp $: 

*/


/**
 * Class for making call to server using xml rpc *
 */
class MyShiksha_client{
    
	/**
	 * Variable for storing Controller class
	 * @var Object
	 */ 
	var $CI_Instance;
    
	/**
	 * Function for initialization and loading library
	 */
	function init(){
        $this->CI_Instance = & get_instance();
		$this->CI_Instance->load->library('xmlrpc');
		$this->CI_Instance->xmlrpc->set_debug(0);
		$this->CI_Instance->xmlrpc->server(MyShiksha_SERVER_URL, MyShiksha_SERVER_PORT);			
	}
	
	/**
	 * Function index()
	 */
	function index(){
		echo "Use any webservice method to continue";		
	}

	/**
	 * Function to get the User Shiksha
	 *
	 * @param string $appID
	 * @param integer $userId
	 */
    function getMyShiksha($appID, $userId){
        $this->init();  
        $this->CI_Instance->xmlrpc->method('getMyShikshaS');
        $request = array($appID, $userId); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    
    /**
     * Function to update the user 'Shiksha'
     *
     * @param string $appID
     * @param integer $userId
     * @param string $component
     * @param string $displayStatus
     * @param string $position
     *
     */
    function updateMyShiksha($appID, $userId, $component, $displayStatus, $position){
        $this->init();  
        $this->CI_Instance->xmlrpc->method('updateMyShikshaS');
        $request = array($appID, $userId, $component, $displayStatus, $position); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    /**
     * Function to the user videos
     *
     * @param string $appId
     * @param string $userId
     */
    function getMyVideos($appId, $userId) {
		$request = array();
		$request['type'] = "user";
		$request['id'] = $userId;
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, MDB_SERVER .'getVideos.php');
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, 1);
		curl_setopt($c,CURLOPT_POSTFIELDS,$request);
		$response =  curl_exec($c);
		curl_close($c);
		if(@unserialize($response)) {
			return unserialize($response);
		} else  {
			return "";
		}
    }
    
    /**
     * Function to the user Pictures
     *
     * @param string $appId
     * @param string $userId
     */
    function getMyPictures($appId, $userId) {
		$request = array();
		$request['type'] = "user";
		$request['id'] = $userId;
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, MDB_SERVER.'getImages.php');
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, 1);
		curl_setopt($c,CURLOPT_POSTFIELDS,$request);
		$response =  curl_exec($c);
		curl_close($c);
		if(@unserialize($response)) {
			return unserialize($response);
		} else  {
			return "";
		}
    }
}
?>
