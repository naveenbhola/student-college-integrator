<?php

class Mail_client 
{

	var $CI="";
	function init()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		$server_url = INBOX_SERVER_URL;
		$this->CI->xmlrpc->set_debug(0);
		error_reporting(0);
		$this->CI->xmlrpc->server($server_url,INBOX_SERVER_PORT);
	}
	
	function getMailsList($appId,$userId,$type,$pageNo=0,$noOfMessages=10)
	{
		$this->init();
		$this->CI->xmlrpc->method('getMailsList');
		$request = array(
				array($appId,'string'),
				array($userId,'int'),
				array($type,'string'),
				array($pageNo,'int'),
				array($noOfMessages,'int')
				);
		$this->CI->xmlrpc->request($request);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
        else
        {
            $this->inValidateCacheForUnread($appId, $userId);
            return $this->CI->xmlrpc->display_response();
        }	
	}

	function unreadMails($appId,$userId)
	{
		$this->init();
        $this->CI->load->library('cacheLib');
        $cacheLib = new cacheLib();
	$appId = 1;
        $key = md5('unreadMails'.$appId.$userId);
        if($cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('unreadMails');
            $request = array (
                    array ($appId,'string'),
                    array ($userId, 'int')
                    );
            $this->CI->xmlrpc->request($request);
            if (!$this->CI->xmlrpc->send_request()) {
                return $this->CI->xmlrpc->display_error();
            } else {
                $response = $this->CI->xmlrpc->display_response();
                $cacheLib->store($key,$response,300,'Mail');
                return $response;
            }
        } else {
            return $cacheLib->get($key);
        }
	}

	function totalMails($appId, $userId, $type)
	{
		$this->init();
		$this->CI->xmlrpc->method('totalMails');
		$request = array (
				array ($appId, 'string'),
				array ($userId, 'int'),
				array ($type, 'string')
			);
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
	
	function blockUser($appId, $userId,$blockedUserId)
	{
		$this->init();
		$this->CI->xmlrpc->method('blockUser');
		$request = array (
				array($appId, 'string'),
				array($userId,'int'),
				array($blockedUserId,'int')
			);
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

	function save($appId, $userId, $receiverIds, $subject, $body, $contentId)
	{
		$this->init();
		$this->CI->xmlrpc->method('saveDraft');
		$arrreceiverIds = array();
		foreach ($receiverIds as $rec)
		{
			array_push($arrreceiverIds,array($rec,'int'));
		}
                $request = array (
                                array($appId, 'string'),
                                array($userId,'int'),
                                array($arrreceiverIds,'struct'),
				array($subject,'string'),
				array($body, 'string'),
				array($contentId,'int')
                        );
		//error_log_shiksha (print_r($request,true));
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



	function send($appId, $userId, $receiverIds, $subject, $body, $contentId)
        {
                $this->init();
                $this->CI->xmlrpc->method('sendMail');
                $arrreceiverIds = array();
                foreach ($receiverIds as $rec)
                {
                        array_push($arrreceiverIds,array($rec,'int'));
                }
                $request = array (
                                array($appId, 'string'),
                                array($userId,'int'),
                                array($arrreceiverIds,'struct'),
                                array($subject,'string'),
                                array($body, 'string'),
                                array($contentId,'int')
                        );
               // error_log_shiksha (print_r($request,true));
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
	
	function getMail($appId,$userId,$type,$mailId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getMail');
		$request = array (
				array($appId,'string'),
				array($userId,'int'),
				array($type,'string'),
				array($mailId,'int')
			);
		$this->CI->xmlrpc->request($request);
		
		if (!$this->CI->xmlrpc->send_request())
                {
                        return $this->CI->xmlrpc->display_error();
                }
                else
                {
                    $this->inValidateCacheForUnread($appId, $userId);
                    return $this->CI->xmlrpc->display_response();
                }
        }
	
	function setReadFlag($appId,$userId,$mailId)
	{
		$this->init();
		$this->CI->xmlrpc->method('setMailAsRead');
		$request  = array (
				array($appId,'string'),
				array($userId,'int'),
				array($mailId,'int')
			);
		 $this->CI->xmlrpc->request($request);

                if (!$this->CI->xmlrpc->send_request())
                {
                        return $this->CI->xmlrpc->display_error();
                }
                else
                {
                    $this->inValidateCacheForUnread($appId, $userId);
                    return $this->CI->xmlrpc->display_response();
                }
	}
		
	function moveToTrash($appId,$userId,$type,$mailId)
	{
		$this->init();
		$this->CI->xmlrpc->method('moveToTrash');
		$request = array (
				array($appId,'string'),
				array($userId,'int'),
				array($type, 'string'),
				array($mailId,'int')
			);
		$this->CI->xmlrpc->request($request);

                if (!$this->CI->xmlrpc->send_request())
                {
                    return $this->CI->xmlrpc->display_error();
                }
                else
                {
                    $this->inValidateCacheForUnread($appId, $userId);
                    return $this->CI->xmlrpc->display_response();
                }

	}
	
	function getAddressbook($appId,$userId,$strNames)
	{
		$this->init();
		$this->CI->xmlrpc->method('getAddressbook');
		$request = array (
				array($appId,'string'),
				array($userId,'int'),
				array($strNames,'string')
			);
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

	function blockSenders($appId,$userId,$mailIds)
	{
		$this->init();
		$this->CI->xmlrpc->method('blockSenders');
		$request = array(
				array($appId,'string'),
				array($userId,'int'),
				array($mailIds,'struct')
			);
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


	function getUserIdsFromDisplayNames($appId,$strDisplayNames)
	{
		$this->init();
		$this->CI->xmlrpc->method('getUserIdsFromDisplayNames');
		$request = array(
				array($appId,'string'),
				array($strDisplayNames,'string')
			);
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
		

	function getPreviousNext($appId,$userId,$type,$mailId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getPreviousNext');
		$request = array(
				array($appId,'string'),
				array($userId,'int'),
				array($type,'string'),
				array($mailId,'int')
			);
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
		
	function restoreFromTrash($appId,$userId,$mailId)
	{
		$this->init();
		$this->CI->xmlrpc->method('restoreFromTrash');
		$request = array (
				array($appId,'string'),
				array($userId,'int'),
				array($mailId,'int')
			);
		$this->CI->xmlrpc->request($request);
                if (!$this->CI->xmlrpc->send_request())
                {
                        return $this->CI->xmlrpc->display_error();
                }
                else
                {
                    $this->inValidateCacheForUnread($appId, $userId);
                    return $this->CI->xmlrpc->display_response();
                }

        }

    private function inValidateCacheForUnread($appId, $userId){
        $this->init();
        $this->CI->load->library('cacheLib');
        $cacheLib = new cacheLib();
	//appId set to 1 (different values coming from different methods) inconsistency in keys
	$appId = 1;
        $key = md5('unreadMails'.$appId.$userId);
        $cacheLib->store($key,'ERROR_READING_CACHE',30,'Mail');
    }


}

?>
