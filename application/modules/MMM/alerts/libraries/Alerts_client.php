<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010-08-18 08:29:17 $:  Date of last commit

Alerts_client.php makes call to server using XML RPC calls.

$Id: Alerts_client.php,v 1.29 2010-08-18 08:29:17 ankurg Exp $: 

*/
class Alerts_client
{
	var $CI = '';   
	
	function init()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		//$server_url = 'http://172.16.0.160/shirish/alert_server/';		
		$server_url = ALERT_SERVER;		
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url, ALERT_SERVER_PORT);			
	}	
	
	function getUserAlerts($appId,$userId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getUserAlerts');	
		$request = array (
		array($appId, 'string'),
		array($userId, 'int'),   	
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
	
	function createAlert($appId,$productId,$userId,$alertName,$alertValueId,$frequency,$alertType,$mail,$sms,$im,$state)
	{
		$this->init();
		$this->CI->xmlrpc->method('createAlert');	
		$request = array (
		array($appId, 'string'),
		array($productId, 'int'),
		array($userId, 'int'),
		array($alertName, 'string'),   
		array($alertValueId, 'string'), 
 		array($frequency, 'string'),   	
		array($alertType, 'string'),
		array($mail, 'string'),   	
		array($sms, 'string'),   	
		array($im, 'string'),
		array($state, 'string'),	   			
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
	
	function getMyAlertCount($appId,$userId,$productId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getMyAlertCount');	
		$request = array (
		array($appId, 'string'),
		array($userId, 'int'),
		array($productId, 'int'),
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
	
	function createWidgetAlert($appId,$userId,$productId,$alertType,$alertValueId,$alertName='',$filterId='')
	{
		$this->init();
		$this->CI->xmlrpc->method('createWidgetAlert');	
		$filterType = 'int';
		if(is_string($filterId))
		{
			$filterType = 'string';
		}
		$request = array (
		array($appId, 'string'),
		array($userId, 'int'),
		array($productId, 'int'),
		array($alertType, 'string'),
		array($alertValueId, 'string'), 
		array($alertName, 'string'),   			
		array($filterId,$filterType),
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
	
	function getWidgetAlert($appId,$userId,$productId,$alertType,$alertValueId,$filterId='')
	{
		$this->init();
		$this->CI->xmlrpc->method('getWidgetAlert');	
		if(is_string($filterId))
		{
			$filterType = 'string';
		}
		$request = array (
		array($appId, 'string'),
		array($userId, 'int'),
		array($productId, 'int'),
		array($alertType, 'string'),
		array($alertValueId, 'int'), 
		array($filterId,$filterType), 
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
	
	function createEventAlert($appId,$productId,$userId,$alertName,$alertValueId,$filterId,$frequency,$alertType,$mail,$sms,$im,$state)
	{
		$this->init();
		$this->CI->xmlrpc->method('createEventAlert');	
		$filterType = 'int';
		if(is_string($filterId))
		{
			$filterType = 'string';
		}
		$request = array (
		array($appId, 'string'),
		array($productId, 'int'),
		array($userId, 'int'),
		array($alertName, 'string'),   
		array($alertValueId, 'int'), 
		array($filterId, $filterType),  	
 		array($frequency, 'string'),   	
		array($alertType, 'string'),
		array($mail, 'string'),   	
		array($sms, 'string'),   	
		array($im, 'string'),
		array($state, 'string'),	   			
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
	
	function updateAlert($appId,$alertId,$userId,$frequency,$mail,$sms,$im)
	{
		$this->init();
		$this->CI->xmlrpc->method('updateAlert');	
		$request = array (
		array($appId, 'string'),
		array($alertId, 'int'),
		array($userId, 'int'),
 		array($frequency, 'string'),   	
		array($mail, 'string'),   	
		array($sms, 'string'),   	
		array($im, 'string'),		
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
	
	function updateEventAlert($appId,$alertId,$userId,$frequency,$mail,$sms,$im,$alertName,$filterId)
	{
		$this->init();
		$this->CI->xmlrpc->method('updateEventAlert');	
		$request = array (
		array($appId, 'string'),
		array($alertId, 'int'),
		array($userId, 'int'),
 		array($frequency, 'string'),   	
		array($mail, 'string'),   	
		array($sms, 'string'),   	
		array($im, 'string'),		
		array($alertName, 'string'),		
		array($filterId, 'int'),
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
	
	function deleteAlert($appId,$alertId,$userId)
	{
		$this->init();
		$this->CI->xmlrpc->method('deleteAlert');	
		$request = array (
		array($appId, 'string'),
		array($alertId, 'int'),
		array($userId, 'int'),
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
	
	function getProductAlerts($appId,$userId,$productId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getProductAlerts');	
		$request = array (
		array($appId, 'string'),
		array($userId, 'int'),
		array($productId, 'int'),		
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

	function getProductWithTypeAlerts($appId,$userId,$productId,$type)
	{
		$this->init();
		$this->CI->xmlrpc->method('getProductWithTypeAlerts');	
		$request = array (
		array($appId, 'string'),
		array($userId, 'int'),
		array($productId, 'int'),		
		array($type, 'string'),		
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

	function getAlert($appId,$alertId,$userId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getAlert');	
		$request = array (
		array($appId, 'string'),
		array($alertId, 'int'),
		array($userId, 'int'),
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
	
	function updateState($appId,$alertId,$userId,$state)
	{
		$this->init();
		$this->CI->xmlrpc->method('updateState');	
		$request = array (
		array($appId, 'string'),
		array($alertId, 'int'),
		array($userId, 'int'),
		array($state, 'string'),		
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
    function externalQueueAdd($appId,$fromEmail,$toEmail,$subject,$content,$contentType="text",$sendTime="0000-00-00 00:00:00",$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null,$fromUserName="Shiksha.com",$isSent='', $return_mail_id = 'N',$mail_sent_time)
	{
		$this->init();
		$this->CI->xmlrpc->method('externalQueueAdd');	
		$request = array (
		array($appId, 'string'),
		array($fromEmail, 'string'),
		array($toEmail, 'string'),
		array($subject, 'string'),		
		array($content, 'string'),
		array($contentType, 'string'), 
	        array($sendTime, 'string'),
	        array($attachment, 'string'),
	        array($attachmentArray, 'array'),
		array($ccEmail, 'string'),
		array($bccEmail, 'string'),
		array($fromUserName,'string'),
		array($isSent,'string'),	//to add isSent value to tMailQueue
		array($return_mail_id,'string'), // for returning inserted row mail id
		array($mail_sent_time,'string'), // to send mail in future not real time
		'struct'		
		);
		$this->CI->xmlrpc->request($request);	

		if (!$this->CI->xmlrpc->send_request())
		{
			return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
    
	
	//Added sms type in addSmsQueueRecord where default type is static message
	
    function addSmsQueueRecord($appId,$toSms,$content,$userId,$sendTime="0000-00-00 00:00:00",$smstype="system",$IsRegistration="no",$returnSMSId="no")
    {

        /**
         *  Add default value of API argument sms type
         */
        if ($smstype != "system" && $smstype != "user-defined")
        {
                $smstype = "system";
        
        }    

        $this->init();
        $this->CI->xmlrpc->method('addSmsQueueRecord');
        $request = array (
               array($appId, 'string'),
               array($toSms, 'string'),
               array($content, 'string'),
               array($userId, 'string'),
               array($sendTime, 'string'),
	       array($smstype, 'string'),
	       array($IsRegistration, 'string'),
	       array($returnSMSId, 'string'),
               'struct'            
               );
        $this->CI->xmlrpc->request($request);
        if (!$this->CI->xmlrpc->send_request())
        {
              return  $this->CI->xmlrpc->display_error();
        }
        else
        {
              return $this->CI->xmlrpc->display_response();
        }
    }
    
    
	function getDigestNetwork($appId,$frequency)
	{
		$this->init();
        error_log_shiksha("At first Level");
		$this->CI->xmlrpc->method('getDigestNetwork');	
		$request = array (
		array($appId, 'string'),
		array($frequency, 'int'),   	
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
	function getDigestMail($appId,$frequency)
	{
		$this->init();
        error_log_shiksha("At first Level");
		$this->CI->xmlrpc->method('getDigestMail');	
		$request = array (
		array($appId, 'string'),
		array($frequency, 'int'),   	
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
	function performEmailCheck($appId,$frequency)
	{
		$this->init();
		$this->CI->xmlrpc->method('performEmailCheck');	

		$request = array (
		array($appId, 'string'),
		array($frequency, 'int'),   	
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

	function createAttachment($appId, $type_id,$type, $attachmentType, $attachmentContent, $attachment_name, $attachment_file_type, $attachment_file_encoded_type = 'false',$attachmenturl = 'NULL')
	    {
		$this->init();
		$this->CI->xmlrpc->method('createAttachment');	

		$request = array (
		array($appId, 'string'),
		array($type_id, 'int'),   	
		array($type, 'string'),   	
		array($attachmentType, 'string'),   	
		array($attachmentContent, 'string'),   	
		array($attachment_name, 'string'),   	
		array($attachment_file_type, 'string'),
		array($attachment_file_encoded_type,'string'),
		array($attachmenturl,'string'),
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
    
    function getAttachmentId($appId, $type_id,$type, $document_type,$attachment_name='')
    {
		$this->init();
		$this->CI->xmlrpc->method('getAttachmentId');	

		$request = array (
		array($appId, 'string'),
		array($type_id, 'int'),   	
		array($type, 'string'),   	
		array($document_type, 'string'),   	
		array($attachment_name, 'string'),   	
		'struct'			
		);
        //error_log("getAttachId" .print_r($request,true));
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
	
	function getCompareUrl($appId, $userId, $pageUrl,$key)
    {
		$this->init();
		$this->CI->xmlrpc->method('getCompareUrl');	

		$request = array (
		array($appId, 'string'),
		array($userId, 'int'),   	
		array($pageUrl, 'string'),
		array($_COOKIE['compare-'.$key], 'string'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	
		
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
    }
	
	
	function getCompareContent($appId,$compareId)
    {
		$this->init();
		$this->CI->xmlrpc->method('getCompareContent');	

		$request = array (
		array($compareId, 'int'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	
		
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
    }


	

}
?>
