<?php 

class Mail extends MX_Controller
{
	function init()
	{
		$this->load->helper(array('form','url','shikshautility'));
		$this->load->library(array('xmlrpc','mail_client','login_client','register_client'));

		$this->userStatus = $this->checkUserValidation();
		if (!is_array($this->userStatus) || !$this->userStatus)
		{
			//$currentUrl = site_url('mail/mail/mailbox');
			//redirect('user/login/userlogin/'.base64_encode($currentUrl),'location');
			redirect ();
		}
		else
		{
			if($this->userStatus[0]['quicksignuser'] == "1")
				{	
					$base64url = base64_encode(site_url('mail/Mail/mailbox'));
					header('Location:/user/Userregistration/index/'.$base64url.'/1');
					exit();
				}	
			$this->userId = $this->userStatus[0]['userid'];
			$this->displayName = $this->userStatus[0]['displayname'];
			$this->validateUser = $this->userStatus;

		}
		//$this->userId = 197;
		$this->appId = 10;

	}

	function index()
	{
		$this->init();
		$this->load->view('mail/default');
	}

	function mailbox($type='inbox',$pageNo=0,$noOfMessages=10)
	{
		$this->init();
		log_message("debug","INSIDE SHIKSHA MAILSSSSSS ");
		$mail_client = new Mail_client();
		$response['mails'] = $mail_client->getMailsList($this->appId,$this->userId,$type,$pageNo,$noOfMessages);
		$response['userDetails']= $this->getUserInfoForSimple($response['mails']);
		$response['type'] = $type;
		$response['noOfUnread'] = $mail_client->unreadMails($this->appId,$this->userId);
		$response['totalMails'] = $mail_client->totalMails($this->appId,$this->userId,$type);
		$response['pageNo'] = $pageNo;
		$response['noOfMessages'] = $noOfMessages;
		$response['validateuser']= $this->validateUser;
		//echo "<pre>";print_r($response);echo "</pre>";
		//below code used for beacon tracking
		$response['trackingpageIdentifier'] = 'mailBoxPage';
		$response['trackingcountryId']=2;


		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($response);
		$this->load->view('mail/mailView',$response);
	}

	function getUserInfo ($strUserIds)
	{
		$register_client = new register_client();
		//echo $strUserIds;
		if(!($strUserIds == "")) {
			$info = $register_client->getDetailsForUsers($this->appId,$strUserIds);
			$arrUser = array();
			//print_r($info);
			if(count($info)>0) {
				foreach ($info as $indi)
				{
					$arrUser[$indi['userid']] = $indi;
				}
			}
		}else {
			$arrUser = array();
		}
		return $arrUser;
	}

	function getUserInfoForSimple($arrMails)
	{
		$strSendersId = "";
		foreach ($arrMails as $mail)
		{
			$strSendersId .= $mail['senders_id'].",";
		}
		$strSendersId = substr($strSendersId,0,strlen($strSendersId)-1);

		$response= $this->getUserInfo($strSendersId);
		return $response;
	}

	function getUserInfoForMultiple($arrMails)
	{
		$strUserIds = "";
		//print_r($arrMails);
		foreach ($arrMails as  $mails)
		{
			foreach ($mails['receivers_ids'] as $ids)
			{
				if ($ids!="")
				$strUserIds .= $ids.",";
			}
		}
		$strUserIds = substr($strUserIds,0,strlen($strUserIds)-1);
		$response = $this->getUserInfo($strUserIds);
		return $response;

	}

	function getMailsList($type,$pageNo=0,$noOfMessages=10)
	{
		$this->init();
		$mail_client = new Mail_client();
		$response['mails'] = $mail_client->getMailsList($this->appId,$this->userId,$type,$pageNo,$noOfMessages);
		if ($type=="inbox" || $type=="trash")
			$response['userDetails'] = $this->getUserInfoForSimple($response['mails']);
		else
			$response['userDetails'] = $this->getUserInfoForMultiple($response['mails']);
		$response['type'] = $type;
		if ($type == 'inbox')
		$response['noOfUnread'] = $mail_client->unreadMails($this->appId,$this->userId);
		//print_r($this->userId);
		$response['totalMails'] = $mail_client->totalMails($this->appId,$this->userId,$type);
		$pageNo = isset($pageNo)?$pageNo:0;
		$response['pageNo'] = $pageNo;
		$response['noOfMessages'] = $noOfMessages;
		//echo "<pre>"; print_r($response);echo "</pre>";
		$this->load->view("mail/$type",$response);

	}

	function save()
	{
		$this->init();
		$receiverNames = $this->input->post('names',true);
		$subject = $this->input->post('subject',true);
		$body = $this->input->post('body',true);
		$content = (!$this->input->post('contentId'))? 0: $this->input->post('contentId');
		$type = $this->input->post('mailtype');
		$mail_client = new Mail_client();

		if (strpos($receiverNames,',')===false)
		{
			$receivedNames[0] = $receiverNames;
			$receivedCount =1;
		}
		else
		{
			$receivedNames = explode(',',$receiverNames);
			$receivedCount = count($receivedNames);
		}

		$ids = $mail_client->getUserIdsFromDisplayNames($this->userId,$receiverNames);
		$fetchedCount = count($ids);
		$arrNonExistingDisplayNames = array();

		if ($fetchedCount==0)
		{
		   //echo json_encode("nonexisting");
		   //return;
		}

		if ($receivedCount > $fetchedCount)
		{
			foreach ($receivedNames as $row)
			{
				if(!isset($ids[$row]))
				array_push($arrNonExistingDisplayNames,$row);
			}

		}
		if (count($arrNonExistingDisplayNames)>0)
		{
			$returnStr =  implode(',',$arrNonExistingDisplayNames);
			//error_log_shiksha($returnStr);
			if ($returnStr!="") {
			echo json_encode("Invalid Shiksha displayname: ".$returnStr."; Enter only Shiksha displaynames. For eg. ThisIsManish.");
			return;
		     }
		}

		$receiverIds = array();
		foreach ($ids as $receiver)
		{
			array_push($receiverIds,$receiver);
		}
		if ($type == 'save')
		$response = $mail_client->save($this->appId,$this->userId,$receiverIds,$subject,$body,$content);
		else if ($type == 'send')
		$response = $mail_client->send($this->appId,$this->userId,$receiverIds,$subject,$body,$content);
		echo json_encode ($response);
	}

	function sendMailByPOST()
	{
       error_log_shiksha(print_r($_REQUEST,true));
	   $this->load->library(array('xmlrpc','mail_client'));
	   $senderId = $this->input->post('senderId',true);
	   $receiverId = $this->input->post('receiverIds',true);
	   $subject = $this->input->post('subject',true);
	   $body = $this->input->post('body',true);
	   $mail_client = new Mail_client();
       $receiverIds=array($receiverId);
	   $response = $mail_client->send(1,$senderId,$receiverIds,$subject,$body,0);
	   echo $response;
	}

	function getSenderDetails($arrMails)
	{
		$strUserId = "";
		if(count($arrMails)>0 ){
			foreach ($arrMails as $mail)
			{
				$strUserId .= $mail['senders_id'].",";
			}
			$strUserId = substr($strUserId,0,strlen($strUserId)-1);
			$response = $this->getUserInfo($strUserId);
		}else {
			$response = "";
		}
		return $response;
	}


	function getMail($type,$mailId)
	{
		$this->init();
		$mail_client = new Mail_client();
		$res = $mail_client->getMail($this->appId,$this->userId,$type,$mailId);
		$response['page']= $mail_client->getPreviousNext($this->appId,$this->userId,$type,$mailId);
		$response['receiverDetails']= $this->getUserInfoForMultiple($res);
		$response['senderDetails']= $this->getSenderDetails($res);
		$response['mail'] = $res[0];
		$response['type'] = $type;

		if ($type=="inbox")
		$mail_client->setReadFlag($this->appId,$this->userId,$mailId);
		//echo "<pre>";print_r($response);echo "</pre>";
		if ($type!=="drafts")
		$this->load->view("mail/readMail",$response);
		else
		$this->load->view("mail/compose",$response);

	}

	function delete($type)
	{
		$this->init();
		$mailIds = $this->input->post('mailId');
		$flag = $this->input->post('flag');
		$mail_client = new Mail_client();

		if ($flag =='block')
		{
			$mail_client->blockSenders($this->appId,$this->userId,$mailIds);
		}
		else if ($flag=='delete')
		{
			foreach ($mailIds as $mails)
			{
				$mail_client->moveToTrash($this->appId,$this->userId,$type,$mails);
			}
		}
		else if ($flag =="restore")
		{
			foreach ($mailIds as $mails)
			{
				$mail_client->restoreFromTrash($this->appId,$this->userId,$mails);
			}
		}
		echo "1";
	}

	function getAddressbook()
	{
		return false;
		$this->init();
		$strNames = $this->input->post('names');
		$mail_client = new Mail_client();
		$response['string']=$strNames;

		$response['matches'] = $mail_client->getAddressbook($this->appId,$this->userId,$strNames);
		$this->load->view('mail/autocomplete',$response);
	}

	function showCompose()
	{
		$this->init();
		$this->load->view('mail/compose');
	}
	
	
	function sendMail()
	{
		$this->init();
		$this->load->library('alerts_client');
		$alerts_client = new Alerts_client();
		$userClient = new Register_client();
		$emails = $this->input->post('inviteEmails');
		$emails = str_replace(",",";",$emails);
		$tosend = explode(';',$emails);
        $data['displayName'] = $this->displayName;
        $content=$this->load->view('inviteFriends/inviteMail',$data,true);
        $content=preg_replace("/(\\n)|(\\t)/"," ",$content);
		foreach ($tosend as $e)
		{
            $userClient->userInfoSystemPoint_Client($this->userId, 'inviteFriends');
		   $sendMailRes = $alerts_client->externalQueueAdd($this->appId,ADMIN_EMAIL,trim($e),$this->displayName." has invited you to join Shiksha.com",$content,"html");
		}
        error_log_shiksha($content);
        echo json_encode ($sendMailRes);
	}
}


?>
