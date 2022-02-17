<?php
/*

   Copyright 2007 Info Edge India Ltd

   $Rev:: $: Revision of last commit
   $Author: ankurg $: Author of last commit
   $Date: 2010-08-30 11:09:15 $: Date of last commit

   sms_client.php makes call to server using XML RPC calls.

   $Id: sms_client.php,v 1.2 2010-08-30 11:09:15 ankurg Exp $: 

 */

class sms_client{
    var $CI_Instance;
    var $cacheLib;
    function init(){
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->library('xmlrpc');
	$this->CI_Instance->load->helper('url');
        $this->CI_Instance->xmlrpc->set_debug(0);
        $this->CI_Instance->xmlrpc->server(SMS_SERVER_URL, SMS_SERVER_PORT);
    }

    function performMobileCheck($appId,$frequency)
    {
	    $this->init();
	    $this->CI_Instance->xmlrpc->method('performMobileCheck');	
	    $request = array (
	    array($appId, 'string'),
	    array($frequency, 'int'),   	
	    'struct'			
	    );
	    $this->CI_Instance->xmlrpc->request($request);	

	    if ( ! $this->CI_Instance->xmlrpc->send_request())
	    {
	      return  $this->CI_Instance->xmlrpc->display_error();
	    }
	    else
	    {
	      return $this->CI_Instance->xmlrpc->display_response();
	    }
    }

    function addSmsQueueRecord($appId,$toSms,$content,$userId,$sendTime="0000-00-00 00:00:00")
    {
        $this->init();
        $this->CI_Instance->xmlrpc->method('addSmsQueueRecord');
        $request = array (
               array($appId, 'string'),
               array($toSms, 'string'),
               array($content, 'string'),
               array($userId, 'string'),
               array($sendTime, 'string'),
               'struct'            
               );
        $this->CI_Instance->xmlrpc->request($request);
        if (!$this->CI_Instance->xmlrpc->send_request())
        {
              return  $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
              return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function shikshaSmsAlert($appId)
    {
	    $this->init();
	    $this->CI_Instance->xmlrpc->method('shikshaSmsAlert');
	    $request = array (array($appId, 'string'),'struct');
	    $this->CI_Instance->xmlrpc->request($request);
	    if ( ! $this->CI_Instance->xmlrpc->send_request())
	    {
	      return  $this->CI_Instance->xmlrpc->display_error();
	    }
	    else
	    {
	      return $this->CI_Instance->xmlrpc->display_response();
	    }
    }

}
?>
