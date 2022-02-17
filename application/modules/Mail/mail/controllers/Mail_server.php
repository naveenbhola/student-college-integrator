<?php

class Mail_server extends MX_Controller
{
/*
	const MAX_MAIL_LIMIT_PER_DAY = 100;
        function _init() {
		$this->load->library('dbLibCommon');
        	$this->dbLibObj = DbLibCommon::getInstance('Mail');
        }
	function index()
	{
                $this->_init();
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$config['functions']['setMailAsRead'] = array('function'=>'Mail_server.setMailAsRead');
		$config['functions']['getMail'] = array('function'=>'Mail_server.getMail');
		$config['functions']['getMailsList'] = array('function'=> 'Mail_server.getMailsList');
		$config['functions']['sendMail']  = array('function' => 'Mail_server.sendMail');
		$config['functions']['reportSpam']  = array('function' => 'Mail_server.reportSpam');
		$config['functions']['moveToTrash']  = array('function' => 'Mail_server.moveToTrash');
		$config['functions']['saveDraft']  = array('function' => 'Mail_server.saveDraft');
		$config['functions']['unreadMails']  = array('function' => 'Mail_server.unreadMails');
		$config['functions']['totalMails'] = array ('function' => 'Mail_server.totalMails');
		$config['functions']['blockUser'] = array('function' => 'Mail_server.blockUser');
		$config['functions']['getAddressbook'] = array('function' => 'Mail_server.getAddressbook');
		$config['functions']['getUserIdsFromDisplayNames'] = array('function' => 'Mail_server.getUserIdsFromDisplayNames');
		$config['functions']['getPreviousNext'] = array('function' => 'Mail_server.getPreviousNext');
		$config['functions']['blockSenders'] = array('function' => 'Mail_server.blockSenders');
		$config['functions']['restoreFromTrash'] = array('function'=>'Mail_server.restoreFromTrash');

		$args = func_get_args(); $method = $this->getMethod($config,$args);
		
		return $this->$method($args[1]);
	}

	function setMailAsRead($request)
	{
		mail('naveen.bhola@shiksha.com','setMailAsRead() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$dbHandle = $this->dbLibObj->getWriteHandle();
		$userId = $parameters['1'];
		$mailId = $parameters['2'];
		$query = "UPDATE mails SET read_flag='read' WHERE receivers_id = ? AND mail_id=?";
		$dbHandle->query($query, array($userId, $mailId));
		$response = array('read','string');
		return $this->xmlrpc->send_response($response);
	}


	function getMailsList($request)
	{
	    mail('naveen.bhola@shiksha.com','getMailsList() call  at '.date('Y-m-d H:i:s'), 'calling');	
		//echo "<pre>"; print_r($request->output_parameters()); echo "</pre>";
		$parameters = $request->output_parameters();
                // this code is not required
		//$this->load->database();
		$arrResponse = array();
		$userId = $parameters['1'];
		$messageType  = $parameters['2'];
		$pageNo = $parameters['3'];
		$noOfMessages = $parameters['4'];
		$offset = $pageNo * $noOfMessages;
		$returnval = "";

		switch ($messageType)
		{
			case "sent" :
				$returnval = $this->getSentMails($userId,$offset,$noOfMessages);
				break;
			case "drafts" :
				$returnval = $this->getDrafts($userId,$offset,$noOfMessages);
				break;
			case "inbox" :
				$returnval = $this->getInbox($userId,$offset,$noOfMessages);
				break;
			case "trash" :
				$returnval = $this->getTrash($userId,$offset,$noOfMessages);
				break;
		}
		//error_log_shiksha(print_r($returnval,true));

		return $this->xmlrpc->send_response($returnval);
	}


	function getSentMails($userId,$offset,$noOfMessages)
	{
		$dbHandle = $this->dbLibObj->getReadHandle();
		$arrSentMails = array();
		$query = "SELECT DISTINCT(content_id) FROM mails
			  WHERE senders_id = ? AND senders_flag = ?
			  ORDER BY time_created DESC LIMIT $offset,$noOfMessages";
		$strContentIds = "";
		$arrContents = array();
		$contentId = $dbHandle->query($query,array($userId,'sent'));
		foreach ($contentId->result() as $row)
		{
			$strContentIds .= $row->content_id . ",";
		}
		$strContentIds .= "0";

		$query = "SELECT * FROM mail_content WHERE content_id in ($strContentIds)";
		$content = $dbHandle->query($query);
		foreach($content->result() as $row)
		{
			$arrContent[$row->content_id] = array($row->subject,$row->preview);
		}
		$query = "SELECT * FROM mails
			  WHERE senders_id = ? AND senders_flag = ? AND content_id in ($strContentIds)
                          ORDER BY time_created DESC";
		$result = $dbHandle->query($query,array($userId,'sent'));

		foreach ($result->result() as $row)
		{
			if (!isset($arrSentMails[$row->content_id]))
			{
				$arrSentMails[$row->content_id] = array(
				array(
				'mail_ids' =>array(array(array($row->mail_id,'int')),'struct'),
				'receivers_ids' => array(array(array($row->receivers_id,'int')),'struct'),
				'subject' => array($arrContent[$row->content_id][0],'string'),
				'preview' => array(strip_tags($arrContent[$row->content_id][1],'string')),
				'time_created' => array($row->time_created,'string')),
				'struct');
			}
			else
			{
				array_push($arrSentMails[$row->content_id][0]['mail_ids'][0],array($row->mail_id,'int'));
				array_push($arrSentMails[$row->content_id][0]['receivers_ids'][0],array($row->receivers_id,'int'));
			}
		}
		$arrResponse = array($arrSentMails,'struct');
		//echo "<pre>";print_r($arrResponse);echo "</pre>";
		return $arrResponse;
	}


	function getDrafts($userId,$offset,$noOfMessages)
	{
		$dbHandle = $this->dbLibObj->getReadHandle();
		$arrDrafts = array();
		$query = "SELECT DISTINCT(content_id) FROM mails
                          WHERE senders_id = ? AND senders_flag = ?
                          ORDER BY time_created DESC LIMIT $offset,$noOfMessages";
		$strContentIds = "";
		$arrContents = array();
		$contentId = $dbHandle->query($query,array($userId,'draft'));

		foreach ($contentId->result() as $row)
		{
			$strContentIds .= $row->content_id . ",";
		}
		$strContentIds .= "0";

		$query = "SELECT * FROM mail_content WHERE content_id in ($strContentIds)";
		$content = $dbHandle->query($query);
		foreach($content->result() as $row)
		{
			$arrContent[$row->content_id] = array($row->subject,$row->preview);
		}
		$query = "SELECT * FROM mails
                          WHERE senders_id = ? AND senders_flag = ? AND content_id in ($strContentIds)
                          ORDER BY time_created DESC";
		$result = $dbHandle->query($query,array($userId,'draft'));

		foreach ($result->result() as $row)
		{

			if (!isset($arrDrafts[$row->content_id]))
			{
				$arrDrafts[$row->content_id] = array(
				array(
				'mail_ids' =>array(array(array($row->mail_id,'int')),'struct'),
				'receivers_ids' => array(array(array($row->receivers_id,'int')),'struct'),
				'subject' => array($arrContent[$row->content_id][0],'string'),
				'preview' => array(strip_tags($arrContent[$row->content_id][1],'string')),
				'time_created' => array($row->time_created,'string')),
				'struct');
			}
			else
			{
				array_push($arrDrafts[$row->content_id][0]['mail_ids'][0],array($row->mail_id,'int'));
				array_push($arrDrafts[$row->content_id][0]['receivers_ids'][0],array($row->receivers_id,'int'));
			}
		}
		$arrResponse = array($arrDrafts,'struct');
		//echo "<pre>";print_r($arrResponse);echo "</pre>";
		return $arrResponse;
	}

	function getInbox($userId,$offset,$noOfMessages=10)
	{
		$dbHandle = $this->dbLibObj->getReadHandle();
		$arrInboxTmp = array();
		$arrInbox = array();
		$arrContent = array();
		$query = "SElECT * FROM mails
			  WHERE receivers_id = ? AND readers_flag = ? AND senders_flag <> ?
			  ORDER BY time_created DESC LIMIT  $offset,$noOfMessages";
		$result = $dbHandle->query($query,array($userId,'inbox','draft'));
		$strContentIds = "";
		foreach ($result->result() as $row)
		{
			array_push ($arrInboxTmp,
			array($row->mail_id,
			$row->content_id,
			$row->senders_id,
			$row->read_flag,
			$row->time_created));
			$strContentIds .= $row->content_id .",";
		}
		$strContentIds.= "0";
		$query = "SELECT * FROM mail_content where content_id in ($strContentIds)";
		$content = $dbHandle->query($query);
		foreach ($content->result() as $row)
		{
			$arrContent[$row->content_id] = array($row->subject, $row->preview);
		}
		foreach ($arrInboxTmp as $tmp)
		{
			array_push($arrInbox, array(
			array('mail_id' => array($tmp[0],'string'),
			'senders_id' => array($tmp[2],'string'),
			'read_flag' =>array($tmp[3],'string'),
			'subject'=> array($arrContent[$tmp[1]][0],'string'),
			'preview' => array(strip_tags($arrContent[$tmp[1]][1],'string')),
			'time_created' =>array($tmp[4],'string')),
			'struct'
			)
			);

		}
		$arrResponse = array($arrInbox,'struct');
		//echo "<pre>";print_r($arrResponse);echo "</pre>";
		return $arrResponse;
	}

	function getTrash($userId,$offset,$noOfMessages=10)
	{
		$dbHandle = $this->dbLibObj->getReadHandle();
		$arrTrash = array();
		$arrContent = array();

		$query = "SELECT distinct(content_id) FROM mails WHERE (receivers_id = ? AND readers_flag= ?) OR (senders_id = ? AND (senders_flag = ? OR senders_flag= ?))
			  	  ORDER BY time_created DESC LIMIT $offset,$noOfMessages";
		
		$contentId = $dbHandle->query($query,array($userId,'trash',$userId,'s_trash','d_trash'));

		$strContentIds = "";
		foreach ($contentId->result() as $row)
		{
			$strContentIds .= $row->content_id . ",";
		}
		if($strContentIds !=""){
			$strContentIds = substr($strContentIds,0,strlen($strContentIds)-1);

			$query = "SELECT * FROM mail_content where content_id in ($strContentIds)";
			$content = $dbHandle->query($query);
			foreach ($content->result() as $row)
			{
				$arrContent[$row->content_id] = array($row->subject, $row->preview);
			}


			$query = "SElECT * FROM mails WHERE content_id in ($strContentIds)
							  ORDER BY time_created DESC LIMIT 0, $noOfMessages";
			$result = $dbHandle->query($query);
			
			foreach ($result->result() as $row)
			{
				if (!isset($arrTrash[$row->content_id]))
				{
					$arrTrash[$row->content_id] = array(
					array(
					'mail_ids' =>array(array(array($row->mail_id,'int')),'struct'),
					'receivers_ids' => array(array(array($row->receivers_id,'int')),'struct'),
					'subject' => array($arrContent[$row->content_id][0],'string'),
					'senders_id' => array($row->senders_id,'string'),
					'preview' => array(strip_tags($arrContent[$row->content_id][1],'string')),
					'time_created' => array($row->time_created,'string')),
					'struct');
				}
				else
				{
					array_push($arrTrash[$row->content_id][0]['mail_ids'][0],array($row->mail_id,'int'));
					array_push($arrTrash[$row->content_id][0]['receivers_ids'][0],array($row->receivers_id,'int'));
				}
			}
		}
		$arrResponse = array($arrTrash,'struct');
		//echo "<pre>";print_r($arrResponse);echo "</pre>";
		return $arrResponse;
	}

	function sendMail($request)
	{
		mail('naveen.bhola@shiksha.com','sendMail() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$receiversIds = $parameters['2'];
		$subject = $parameters['3'];
		$body = $parameters['4'];
		$preview = ($body!="")?substr($body,0,99):"";
		$contentId = $parameters['5'];

		$newMail = ($contentId==0)?true:false;
		//error_log_shiksha(print_r($contentId,true));
		$dbHandle = $this->dbLibObj->getWriteHandle();
		$data = array ('subject' => $subject, 'body' => $body, 'preview' =>$preview);
		if ($newMail)
		{
			$query = $dbHandle->insert_string('mail_content',$data);
			$dbHandle->query($query);
			$contentId = $dbHandle->insert_id();
		}
		else
		{
			$where = "content_id = $contentId";
			$query = $dbHandle->update_string('mail_content',$data,$where);
			$dbHandle->query($query);
		}
		//error_log_shiksha(print_r($receiversIds,true));
		$arrReceiversId = array();
		if (count($receiversIds)>0)
		{
			$query = "SELECT mail_id,receivers_id FROM mails WHERE content_id = ?";
			$con = $dbHandle->query($query, array($contentId));
			$deleteIds = array();
			foreach ($con->result() as $cid)
			{
				if (!in_array($cid->receivers_id,$receiversIds))
					array_push($deleteIds,$cid->mail_id);
				array_push($arrReceiversId,$cid->receivers_id);
			}

			$query = "INSERT INTO mails (content_id, senders_id, receivers_id, senders_flag,  time_created) VALUES";
			foreach ($receiversIds as $receiver)
			{
				if (!in_array($receiver,$arrReceiversId))
				$query .= "($contentId, $userId, $receiver, 'sent', now()),";

			}
			$query = substr($query,0,strlen($query)-1);
			if (strpos($query,"now")!==false)
			$dbHandle->query($query);
			if (count($deleteIds)>0)
			{
				$str = implode(",",$deleteIds);
				$query = "DELETE FROM mails WHERE content_id=? AND mail_id in ($str)";
				$dbHandle->query($query, array($contentId));
			}
			$query = "UPDATE mails SET senders_flag ='sent' , time_created = now() WHERE content_id = ?";
			$dbHandle->query($query, array($contentId));

			foreach ($receiversIds as $receiver)
			{
				$query = "INSERT INTO addressbook VALUES (?,?,1) ON DUPLICATE KEY UPDATE frequency=frequency+1";
				$dbHandle->query ($query, array($userId, $receiver));
			}
		}

		$message = "Mail(s) sent successfully";
		$response = array($message, 'string');
		return $this->xmlrpc->send_response($response);
	}

	function reportSpam($request)
	{
		mail('naveen.bhola@shiksha.com','reportSpam() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$reporterId = $parameters['1'];
		$mailId = $parameters['2'];
		$reason = $parameters['3'];

		$dbHandle = $this->dbLibObj->getWriteHandle();
		$query = "INSERT INTO mail_spam (mail_id , reporter_id , reason)
			  	  VALUES (?,?,?)";
		$dbHandle->query($query,array($mailId,$reporterId,$reason));
		$message = "Spam has been reported.";
		$response = array($message, 'string');
		return $this->xmlrpc->send_response ($response);
	}

	function moveToTrash($request)
	{
		mail('naveen.bhola@shiksha.com','moveToTrash() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$type = $parameters['2'];
		$mailId = $parameters['3'];
		$dbHandle = $this->dbLibObj->getWriteHandle();

		switch ($type)
		{
			case 'sent':
				$query = "SELECT content_id FROM mails where mail_id =?";
				$content = $dbHandle->query($query, array($mailId));
				$contentId = $content->first_row()->content_id;
				$query = "UPDATE mails SET senders_flag = 's_trash' WHERE content_id = ? AND senders_id = ?";
				$dbHandle->query($query, array($contentId, $userId));
				break;
			case 'drafts':
				$query = "SELECT content_id FROM mails where mail_id =?";
				$content = $dbHandle->query($query, array($mailId));
				$contentId = $content->first_row()->content_id;
				$query = "UPDATE mails SET senders_flag = 'd_trash' WHERE content_id = ? AND senders_id = ?";
				$dbHandle->query($query, array($contentId, $userId));
				break;
			case 'inbox':
				$query = "UPDATE mails SET readers_flag = ?
					  WHERE mail_id = ? AND receivers_id = ?";
				$dbHandle->query($query,array('trash',$mailId,$userId));
				break;
			case 'trash':
				$query = "SELECT content_id FROM mails WHERE mail_id = ?";
				$content = $dbHandle->query($query, array($mailId));
				$contentId = $content->first_row()->content_id;
				$queryS = "UPDATE mails SET senders_flag ='delete' WHERE content_id = ? AND senders_id =? and (senders_flag = 's_trash' or senders_flag = 'd_trash')";
				$queryR = "UPDATE mails SET readers_flag = 'delete' WHERE content_id =? AND receivers_id = ?";
				$dbHandle->query($queryS, array($contentId, $userId));
				$dbHandle->query($queryR, array($contentId, $userId));
				break;
		}

		$message = "Mail has moved to trash";
		$response = array($message, 'string');
		return $this->xmlrpc->send_response($response);
	}

	function saveDraft($request)
	{
		mail('naveen.bhola@shiksha.com','saveDraft() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$receiversIds = $parameters['2'];
		$subject = $parameters['3'];
		$body = $parameters['4'];
		$preview = ($body!="")? substr($body,0,99):"";
		$contentId = $parameters['5'];
		$newDraft = ($contentId==0)?true:false;
		$dbHandle = $this->dbLibObj->getWriteHandle();
		$data = array ('subject' => $subject, 'body' => $body, 'preview'=> $preview);
		if ($newDraft)
		{
			$query = $dbHandle->insert_string('mail_content',$data);
			$dbHandle->query($query);
			$contentId = $dbHandle->insert_id();
		}
		else
		{
			$where = "content_id = $contentId";
			$query = $dbHandle->update_string('mail_content',$data,$where);
			$dbHandle->query($query);
		}
		$arrReceiversId = array();
		$query = "SELECT receivers_id FROM mails WHERE content_id = ?";
		$con = $dbHandle->query($query, array($contentId));
		$deleteIds = array();
		foreach ($con->result() as $cid)
		{
			if (!in_array($cid->receivers_id,$receiversIds))
			{
				if ($cid->receivers_id==NULL)
				{
					$cid->receivers_id =0;
				}
				array_push($deleteIds,$cid->receivers_id);
			}
			array_push($arrReceiversId,$cid->receivers_id);
		}

		$query = "INSERT INTO mails (content_id, senders_id, receivers_id, senders_flag,  time_created) VALUES";
		if(count($receiversIds)>0)
		{
			foreach ($receiversIds as $receiver)
			{
				if (!in_array($receiver,$arrReceiversId))
				{
					
					//if (!$this->checkIfBlocked($receiver,$userId))
					{
						$query .= " ($contentId, $userId, $receiver, 'draft', now()),";
					}
				}

			}
		}
		else
		{
			$query .= " ($contentId,$userId,0,'draft',now()),";
		}
		$query = substr($query,0,strlen($query)-1);
		if (strpos($query,'now')!== false)
		$dbHandle->query($query);
		if (count($deleteIds)>0)
		{
			$str = implode(",",$deleteIds);
			$query = "DELETE FROM mails WHERE content_id=? AND receivers_id in ($str)";
			$dbHandle->query($query, array($contentId));
		}
		$response = array($contentId,'int');
		return $this->xmlrpc->send_response($response);
	}

	function unreadMails($request)
	{
		mail('naveen.bhola@shiksha.com','unreadMails() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$dbHandle = $this->dbLibObj->getReadHandle();
		$query = "SELECT COUNT(mail_id) as unread FROM mails WHERE receivers_id = ? AND readers_flag='inbox' AND read_flag='unread' AND senders_flag <> 'draft'";
		$result = $dbHandle->query($query,array($userId));
		foreach ($result->result() as $row)
		$count = $row->unread;
		$response = array($count,'int');

		return $this->xmlrpc->send_response($response);
	}

	function totalMails($request)
	{
		mail('naveen.bhola@shiksha.com','totalMails() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$type = $parameters['2'];
		$query ='';
		switch ($type)
		{
			case 'inbox' :
				$query = "SELECT COUNT(mail_id) as total FROM mails WHERE receivers_id = ? AND readers_flag = 'inbox' AND senders_flag <> 'draft'";
				break;
			case 'drafts' :
				$query = "SELECT COUNT(DISTINCT(content_id)) as total FROM mails WHERE senders_id = ? AND senders_flag = 'draft'";
				break;
			case 'sent' :
				$query = "SELECT COUNT(DISTINCT(content_id)) as total FROM mails WHERE senders_id = ? AND senders_flag = 'sent'";
				break;
			case 'trash' :
				$query = "SELECT COUNT(DISTINCT(content_id)) as total FROM mails WHERE (receivers_id = ? AND readers_flag = 'trash') OR (senders_id = $userId AND (senders_flag='s_trash' OR senders_flag ='d_trash'))";
				break;
		}
		$dbHandle = $this->dbLibObj->getReadHandle();
		$result = $dbHandle->query ($query,array($userId));
		foreach ($result->result() as $row)
		$count = $row->total;
		$response = array($count,'int');
		return $this->xmlrpc->send_response($response);
	}

	function checkMailLimit($userId, $noOfMailsToSend)
	{
		mail('naveen.bhola@shiksha.com','checkMailLimit() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$query = "SELECT COUNT(mail_id) as total FROM mails
			  WHERE senders_id = ?  AND senders_flag = 'sent' AND time_created > ADDDATE(NOW(), INTERVAL -24 hour)";
		$dbHandle = $this->dbLibObj->getReadHandle();
		$result = $dbHandle->query($query,array($userId));
		foreach ($result->result() as $row)
		$count = $row->total;
		$count += $noOfMailsToSend;
		if ($count > MAX_MAIL_LIMIT_PER_DAY)
		return false;
		else
		return true;
	}

	function checkIfBlocked($userId,$friendId)
	{
		mail('naveen.bhola@shiksha.com','checkIfBlocked() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$query = "SELECT * FROM blocked_users WHERE user_id = ? AND blocked_user_id = ?";
		$dbHandle = $this->dbLibObj->getReadHandle();
		$result = $dbHandle->query($query,array($userId,$friendId));
		if ($result->num_rows()==1)
		return true;
		else
		return false;
	}

	function blockUser($request)
	{
		mail('naveen.bhola@shiksha.com','blockUser() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$blockedUserId = $parameters['2'];
		$dbHandle = $this->dbLibObj->getWriteHandle();
		$query = "INSERT INTO blocked_users values (?,?) ON DUPLICATE KEY UPDATE blocked_user_id = ? ";
		$dbHandle->query($query,array($userId,$blockedUserId,$blockedUserId));
		$response = array("User Blocked",'string');
		return $this->xmlrpc->send_response($response);
	}

	function getMail($request)
	{
		mail('naveen.bhola@shiksha.com','getMail() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$type = $parameters['2'];
		$mailId = $parameters['3'];
		$dbHandle = $this->dbLibObj->getReadHandle();

		$query = "SELECT content_id FROM mails WHERE mail_id =? AND (senders_id = ? OR receivers_id=?)";
		$content = $dbHandle->query($query, array($mailId, $userId, $userId));
		$contentId = $content->first_row()->content_id;

		$query = "SELECT * FROM mail_content WHERE content_id =?";
		$content = $dbHandle->query($query, array($contentId));
		$row=$content->first_row();
		$arrContent[$row->content_id] = array($row->subject,$row->body);

		switch ($type)
		{
			case 'inbox':
				$query = "SELECT * FROM mails WHERE mail_id = ".$dbHandle->escape($mailId);
				break;
			case 'drafts':
			case 'sent':
			case 'trash':
				$query = "SELECT * FROM mails WHERE content_id = ".$dbHandle->escape($contentId);
				break;
		}
		$arrResult = array();
		$result = $dbHandle->query ($query);
		foreach ($result->result() as $row)
		{
			//error_log_shiksha(print_r($row->mail_id,true));
			if (!isset($arrResult[0]))
			{
				$arrResult[0] = array(
				array(
				'mail_ids' =>array(array(array($row->mail_id,'int')),'struct'),
				'senders_id' => array($row->senders_id,'int'),
				'receivers_ids' => array(array(array($row->receivers_id,'int')),'struct'),
				'subject' => array($arrContent[$row->content_id][0],'string'),
				'body' => array(utf8_encode($arrContent[$row->content_id][1]),'string'),
				'content_id' => array($row->content_id,'int'),
				'time_created' => array($row->time_created,'string')),
				'struct');
			}
			else
			{
				array_push($arrResult[0][0]['mail_ids'][0],array($row->mail_id,'int'));
				array_push($arrResult[0][0]['receivers_ids'][0],array($row->receivers_id,'int'));
			}
		}
		$arrResponse = array($arrResult,'struct');
		//echo "<pre>";print_r($arrResponse);echo "</pre>";
		return $this->xmlrpc->send_response($arrResponse);
	}

	function getAddressBook ($request)
	{
		mail('naveen.bhola@shiksha.com','getAddressBook() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$strNames = $parameters['2'];
		$dbHandle = $this->dbLibObj->getReadHandle();

		$query = "SELECT userId,displayname FROM tuser WHERE displayname like '".$dbHandle->escape_like_str($strNames)."%'";
		$userIds = $dbHandle->query($query);
		$arrResult = array();
		foreach ($userIds->result() as $user)
		{
			array_push($arrResult,array(
			array(
			'userId' => array($user->userId,'int'),
			'displayname' => array($user->displayname,'string')),
			'struct'
			)
			);

		}
		$response = array($arrResult,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getUserIdsFromDisplayNames($request)
	{
		mail('naveen.bhola@shiksha.com','getUserIdsFromDisplayNames() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$strDisplayNames = $parameters['1'];
		$dbHandle = $this->dbLibObj->getReadHandle();
		$strQueryNames = "'".str_replace(",","','",$strDisplayNames)."'";
		$escapeStr = mysql_escape_string($strQueryNames);
		$query = "SELECT userId,displayname FROM tuser WHERE displayname in ($escapeStr)";
		$result = $dbHandle->query($query);
		$arrResult = array();
		foreach ($result->result() as $row)
		{
			$arrResult[$row->displayname] = array($row->userId,'int');
		}
		$response = array ($arrResult,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getPreviousNext($request)
	{
		mail('naveen.bhola@shiksha.com','getPreviousNext() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$dbHandle = $this->dbLibObj->getReadHandle();
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$type = $parameters['2'];
		$mailId = $parameters['3'];
		$query = "SELECT * FROM mails where mail_id = ?";
		$content = $dbHandle->query ($query, array($mailId));
		$contentId= $content->first_row()->content_id;
		$timeCreated = $content->first_row()->time_created;

		switch ($type)
		{
			case 'inbox':
		//		$queryForNext = "SELECT mail_id from mails WHERE receivers_id =$userId AND readers_flag = 'inbox' AND senders_flag<>'draft'  AND content_id <> $contentId AND time_created >='$timeCreated' order by time_created limit 1";
		//		$queryForPrevious = "SELECT mail_id from mails WHERE receivers_id =$userId AND readers_flag = 'inbox' AND senders_flag<>'draft' AND content_id <> $contentId AND time_created <= '$timeCreated' order by time_created desc limit 1";
				$queryForNext = "Select mail_id from mails where receivers_id = ".$dbHandle->escape($userId)." and readers_flag = 'inbox' and senders_flag <> 'draft' and mail_id > $mailId order by time_created limit 1";
				$queryForPrevious = "SELECT mail_id from mails WHERE receivers_id = ".$dbHandle->escape($userId)." AND readers_flag = 'inbox' AND senders_flag<>'draft' AND mail_id < $mailId order by time_created desc,mail_id desc limit 1";
				break;
			case 'trash':

				$queryForNext = "SELECT mail_id from mails WHERE ((receivers_id =".$dbHandle->escape($userId)." AND readers_flag = 'trash') OR (senders_id = ".$dbHandle->escape($userId)." AND (senders_flag='s_trash' OR senders_flag='d_trash'))) AND mail_id > ".$dbHandle->escape($mailId)." order by time_created limit 1";
				$queryForPrevious = "SELECT mail_id from mails WHERE ((receivers_id = ".$dbHandle->escape($userId)." AND readers_flag = 'trash') OR (senders_id = ".$dbHandle->escape($userId)." AND (senders_flag='s_trash' OR senders_flag='d_trash'))) AND mail_id < ".$dbHandle->escape($mailId)." order by time_created desc,mail_id desc limit 1";
				break;
			case 'sent' :

				$queryForNext = "SELECT mail_id from mails WHERE senders_id =".$dbHandle->escape($userId)." AND senders_flag = 'sent' AND mail_id > ".$dbHandle->escape($mailId)." order by time_created limit 1  ";
				$queryForPrevious = "SELECT mail_id from mails WHERE senders_id =".$dbHandle->escape($userId)." AND senders_flag = 'sent' AND mail_id < ".$dbHandle->escape($mailId)." order by time_created desc,mail_id desc limit 1";
				break;

		}
		$resultNext = $dbHandle->query($queryForNext);
		$next = $resultNext->first_row()->mail_id;
		$resultPrevious = $dbHandle->query($queryForPrevious);
		$previous = $resultPrevious->first_row()->mail_id;
		$arrResult = array();
		$arrResult['previous'] = array($previous,'int');
		$arrResult['next'] = array($next,'int');
		$response = array($arrResult,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function blockSenders($request)
	{
		mail('naveen.bhola@shiksha.com','blockSenders() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$mailIds = $parameters['2'];

		$dbHandle = $this->dbLibObj->getWriteHandle();
		foreach ($mailIds as $mailId)
		{
			$query = "SELECT senders_id FROM mails WHERE mail_id = ?";
			$senderRes = $dbHandle->query($query, array($mailId));
			$senderId = $senderRes->first_row()->senders_id;
			$query = "INSERT INTO blocked_users VALUES ($userId,$senderId) ON DUPLICATE KEY UPDATE blocked_user_id = $senderId";
			$dbHandle->query($query);
		}
		$message = "Senders(s) blocked";
		$response = array($message,'string');
		return $this->xmlrpc->send_response($response);

	}

	function restoreFromTrash($request)
	{
		mail('naveen.bhola@shiksha.com','restoreFromTrash() call  at '.date('Y-m-d H:i:s'), 'calling');	
		$parameters = $request->output_parameters();
		$userId = $parameters['1'];
		$mailId = $parameters['2'];

		$dbHandle = $this->dbLibObj->getWriteHandle();
		$query = "SELECT * FROM mails where mail_id = ?";
		$result = $dbHandle->query($query, array($mailId));
		$mail = $result->first_row();
		$flags = array();
		if ($mail->senders_id==$userId)
			array_push($flags,$mail->senders_flag);
		if ($mail->receivers_id==$userId)
			array_push($flags,$mail->readers_flag);
		error_log_shiksha(print_r($flags,true));

		foreach ($flags as $flag)
		{
			switch ($flag)
			{
				case 'trash':
					$query = "UPDATE mails SET readers_flag = 'inbox' WHERE receivers_id = ".$dbHandle->escape($userId)." AND content_id = ".$dbHandle->escape($mail->content_id);
					break;
				case 's_trash':
					$query = "UPDATE mails SET senders_flag = 'sent' WHERE senders_id = ".$dbHandle->escape($userId)." AND content_id = ".$dbHandle->escape($mail->content_id);
					break;
				case 'd_trash' :
					$query = "UPDATE mails SET senders_flag = 'draft' WHERE senders_id = ".$dbHandle->escape($userId)." AND content_id = ".$dbHandle->escape($mail->content_id);
					break;
			}
		}
		$dbHandle->query($query);
		$message = "Restored successfully";
		return $this->xmlrpc->send_response($response);
	}
*/
}
?>
