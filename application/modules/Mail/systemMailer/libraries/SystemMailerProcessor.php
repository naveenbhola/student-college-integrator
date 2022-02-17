<?php

class SystemMailerProcessor
{
    private $CI;
	private $mailer;
    private $toEmail;
    
    private $alertClient;
    private $userModel;
	private $mailer_details;
    
    function __construct($mailer)
    {
        $this->mailer = $mailer;
        
        $this->CI = & get_instance();
        $this->CI->load->library('Alerts_client');
		$this->alertClient = new Alerts_client();
        
        $this->CI->load->model('user/usermodel');
        $this->userModel = new UserModel;
		
        $this->CI->load->model('systemMailer/systemmailermodel');
        $this->systemmailermodel = new SystemMailerModel;
    }
    
    public function process($toEmail,$fromEmail,$subject = '',$content,$mailType = 'html',$attachment = array(), $sendTime ="0000-00-00 00:00:00", $ccEmail=null, $bccEmail=null, $fromUserName="Shiksha.com", $isSent = 'unsent', $return_mail_id = 'N',$mail_sent_time='', $extra_data = array()){
		$this->setEmailid($toEmail);	
    	$mailer_details = $this->mailer_details;
	
		if((empty($mailer_details)) || ((!empty($mailer_details)) && ($mailer_details['mailer_name'] != $this->mailer))) {
			$mailer_details = $this->systemmailermodel->get_mailer_details_by_name($this->mailer);
	        $this->mailer_details = $mailer_details;
		}
		$response = $this->checkIfUserUnsubscribed($toEmail);
    	if($response == true){
    		return array('status' => false);
    	}

		if(is_array($extra_data)){
			$this->_addAdditionalDetails($extra_data);
		}
		
		$mailerMisId = $this->systemmailermodel->track($this->mailer, NULL, NULL, 'sent', $toEmail, $mailer_details['mailer_id']);
		if($isSent != 'sent') {
    		if(!empty($content)) {
				$mail_content = $this->_postProcessMailContent($content, array('mailerMisId' => $mailerMisId));
			}
    	} else {
			$mail_content = $content;
		}
		
	
		if(!$this->isArrayMultidimensional($attachment)){		
			$new_attachment[0] = $attachment;	//If array is not multidimensional, convert it into multidimensional
		} else {
			$new_attachment = $attachment;
    	}
		
		$attachmentIds = array(); 
        foreach($new_attachment as $key=>$attached){									
	        if($attached['url']) {
				$attachmentId = $this->_saveAttachmentForMail($attached);
				$attachmentIds[] = $attachmentId;
			}
		}
	
		$is_attachment = 'n';				
			if(!empty($attachmentIds)) {	//if attachment exists, set flag '$is_attachment' as 'Y'
			$is_attachment = 'y';
		} else if(!empty($attachment)) {	//for 1D array(brochure download), if attachment exists, set flag as 'Y'
			$is_attachment = 'y';
			$attachmentIds = $attachment;	//attachment Id for brochure mail is same as that of passed attachment
		}	

		if((empty($subject)) && (!empty($this->mailer_details['subject']))) {
			$subject = $this->mailer_details['subject'];
		}
		
		$actual_return_mail_id = $return_mail_id;
		$return_mail_id = 'Y';
		$response = $this->alertClient->externalQueueAdd("12",$fromEmail,$toEmail,$subject,$mail_content,$mailType, $sendTime, $is_attachment, $attachmentIds, $ccEmail,$bccEmail, $fromUserName, $isSent, $return_mail_id,$mail_sent_time);

		$this->systemmailermodel->updateMailIdInMailerMis($mailerMisId, $response);
		if($actual_return_mail_id == 'N'){
			$response = "Inserted Successfully";
		}else{
			$response = array('tMailQueueId' => $response, 'mailerMisId' => $mailerMisId,'status' => true);
		}
		return $response;
    }

    function checkIfUserUnsubscribed($email){
    	$this->CI->load->config('user/unsubscribeConfig');
    	$mailerCategory = $this->CI->config->item('mailerCategory');
    	$unsubscribeCategory = $this->mailer_details['unsubscribeCategory'];
    	$userId = $this->userModel->getUserUnscrCategoryStatus($email,$unsubscribeCategory);
    	if($userId>0){
    		if($mailerCategory[$unsubscribeCategory]['autoOn'] == 1){
	    		$this->userModel->updateUserUnsubscribeCategory($userId,$unsubscribeCategory,'no');
	    		$this->userModel->addUserToIndexingQueue($userId);
	    	}else{
	    		return true;	
	    	}
    	}
    	return false;
    }
	
	public function setEmailid($toEmail) {
		$this->toEmail = $toEmail;
	}
    
	/*
	 *FUNCTION - isArrayMultidimensional is to check whether passws array is multidimensional or not
	 */
	public function isArrayMultidimensional($attachment)
	{	
		$rv = array_filter($attachment,'is_array');
    	if(count($rv)>0){
			return true;
		}
		return false;
	}	

    private function _postProcessMailContent($content, $extraData = array())
    {
    	$encodedEmail = $this->userModel->getEncodedEmail($this->toEmail);

        $content = $this->_generateUnsubscribeURLs($content,$encodedEmail, $extraData['mailerMisId']);
        $content = $this->_generateAutoLoginURLs($content,$encodedEmail, $extraData['mailerMisId']);
		$content = $this->_generateTrackingURLs($content,$encodedEmail, $extraData['mailerMisId']);
		$url = 1;
		/**
         * Add beacon for open rate
         */
		if($extraData['isOpenTrackingAdded'] != 'no') {
			$content .= "<div style='display:none'><img src='".SHIKSHA_HOME."/systemMailer/SystemMailer/track/".$this->mailer."/".$this->toEmail."/".$url."/".$extraData['mailerMisId']."' /></div>";	
		}
		return $content;
    }
    
    private function _saveAttachmentForMail($attachment)
	{
        $this->CI->load->library('Ldbmis_client');
		$misClient = new Ldbmis_client();
        
		//$attachmentContent = base64_encode(file_get_contents($attachment['url']));
		$attachmentContent = array();
		$attachmentExtension = end(explode(".",$attachment['url']));
		$attachmentName = $attachment['name'] ? $attachment['name'] : end(explode("/",$attachment['url']));
		$uniqueId = $misClient->updateAttachment(1);
        
		$attachmentId = $this->alertClient->createAttachment("12",$uniqueId,'COURSE','E-Brochure',$attachmentContent,$attachmentName,$attachmentExtension,'true', $attachment['url']);
		
        return $attachmentId;
	}
    
    private function _generateUnsubscribeURLs($content,$encodedEmail, $mailerMisId)
    {
        $unsubscribeURL = $this->_getUnsubscribeURL($encodedEmail, $mailerMisId);
        $content = str_replace('<!-- #Unsubscribe --><!-- Unsubscribe# -->',$unsubscribeURL,$content);
        return $content;
    }

    private function _generateAutoLoginURLs($content,$encodedEmail, $mailerMisId)
    {
        $key = 'AutoLogin';
        
        /**
         * Search for key in the content
         * <!-- #AutoLogin --><!-- AutoLogin# -->
         */
        
        $pattern = '/\'([^"\']*#'.$key.'[^"\']*'.$key.'#[^"\']*)\'/';
		preg_match_all($pattern, $content, $matches);
		$matches = $matches[1];
        
		for($i=0;$i < count($matches); $i++) {
			$matches[$i] = str_replace("<!-- #".$key." --><!-- ".$key."# -->", '', $matches[$i]);
			$replacement = $this->_getAutoLoginURL($matches[$i],$encodedEmail, $mailerMisId);
			$content = preg_replace($pattern, $replacement, $content, 1);
		}
        
		$pattern = '/"([^\'"]*#'.$key.'[^\'"]*'.$key.'#[^\'"]*)\"/';
		preg_match_all($pattern, $content, $matches);
		$matches = $matches[1];
        
		for($i=0;$i < count($matches); $i++) {
			$matches[$i] = str_replace("<!-- #".$key." --><!-- ".$key."# -->", '', $matches[$i]);
			$replacement = $this->_getAutoLoginURL($matches[$i],$encodedEmail, $mailerMisId);
			$content = preg_replace($pattern, $replacement, $content, 1);
		}
        
        return $content;
    }
    
    private function _getAutoLoginURL($url,$encodedEmail, $mailerMisId)
    {
        // $encodedEmail = $this->userModel->getEncodedEmail($this->toEmail);
		$url = $this->addMailerTrackers($url);
        $url = base64_encode($url);
		$autoLoginURL = SHIKSHA_HOME.'/systemMailer/SystemMailer/autoLogin/mailer~'.$this->mailer.'_email~'.$encodedEmail.'_url~'.$url.'_mailId~'.$mailerMisId;
		return $autoLoginURL;
    }
	
	public function addMailerTrackers($url)
    {
		$mailer_details = $this->mailer_details;
		if((!empty($mailer_details['utm_source'])) || (!empty($mailer_details['utm_medium'])) || (!empty($mailer_details['utm_campaign']))) {
            if($url == SHIKSHA_HOME){
                $url = SHIKSHA_HOME.'/shiksha/index';
            }
            if(strpos($url,'?') === false) {
				$url .= '?utm_source='.$mailer_details['utm_source'].'&utm_medium='.$mailer_details['utm_medium'].'&utm_campaign='.$mailer_details['utm_campaign'];
			}
			else {
				$params = explode('?',$url);
				parse_str($params[1]);
				if (empty($utm_source)){
					$url .= '&utm_source='.$mailer_details['utm_source'];	
				}
				if (empty($utm_medium)){
					$url .= '&utm_medium='.$mailer_details['utm_medium'];
				}
				if (empty($utm_campaign)){
					$url .= '&utm_campaign='.$mailer_details['utm_campaign'];
				}
			}
		}
		return $url;
	}
	
	private function _generateTrackingURLs($content,$encodedEmail, $mailerMisId)
    {
        $key = 'Tracking';
        
        /**
         * Search for key in the content
         * <!-- #Tracking --><!-- Tracking# -->
         */
        
        $pattern = '/\'([^"\']*#'.$key.'[^"\']*'.$key.'#[^"\']*)\'/';
		preg_match_all($pattern, $content, $matches);
		$matches = $matches[1];
        
		for($i=0;$i < count($matches); $i++) {
			$matches[$i] = str_replace("<!-- #".$key." --><!-- ".$key."# -->", '', $matches[$i]);
			$replacement = $this->_getTrackingURL($matches[$i],$encodedEmail, $mailerMisId);
			$content = preg_replace($pattern, $replacement, $content, 1);
		}
        
		$pattern = '/"([^\'"]*#'.$key.'[^\'"]*'.$key.'#[^\'"]*)\"/';
		preg_match_all($pattern, $content, $matches);
		$matches = $matches[1];
        
		for($i=0;$i < count($matches); $i++) {
			$matches[$i] = str_replace("<!-- #".$key." --><!-- ".$key."# -->", '', $matches[$i]);
			$replacement = $this->_getTrackingURL($matches[$i],$encodedEmail, $mailerMisId);
			$content = preg_replace($pattern, $replacement, $content, 1);
		}
        
        return $content;
    }
    
    private function _getTrackingURL($url,$encodedEmail, $mailerMisId)
    {
        // $encodedEmail = $this->userModel->getEncodedEmail($this->toEmail);
		$url = $this->addMailerTrackers($url);
        $url = base64_encode($url);
		$autoLoginURL = SHIKSHA_HOME.'/systemMailer/SystemMailer/mailerTracking/mailer~'.$this->mailer.'_email~'.$encodedEmail.'_url~'.$url.'_mailId~'.$mailerMisId;
		return $autoLoginURL;
    }
    
    private function _getUnsubscribeURL($encodedEmail, $mailerMisId)
    {
        //$encodedEmail = $this->userModel->getEncodedEmail($this->toEmail);
		//$unsubscribeURL = SHIKSHA_HOME.'/userprofile/edit?selected_tab=1&encodedMail='.$encodedEmail.'&mailerUnsubscribe=1';
		$unsubscribeURL = SHIKSHA_HOME.'/userprofile/edit?unscr='.$this->mailer_details['unsubscribeCategory'];
        return $this->_getAutoLoginURL($unsubscribeURL,$encodedEmail, $mailerMisId);
    }
	
	public function updateMailToQueue($toEmail, $content, $mail_id, $isSent='unsent', $mailerMisId, $isOpenTrackingAdded = 'yes') {
		$this->setEmailid($toEmail);
		$mail_content = $this->processMailContent($content, array('mailerMisId' => $mailerMisId, 'isOpenTrackingAdded' => $isOpenTrackingAdded));
			
		//$isSent = 'unsent';
		$this->systemmailermodel->update_mail_content($mail_content, $isSent, $mail_id);
		return 'Updated Successfully';
	}
	
	public function processMailContent($content, $extraData = array()) {
		$mailer_details = $this->mailer_details;
		if((empty($mailer_details)) || ((!empty($mailer_details)) && ($mailer_details['mailer_name'] != $this->mailer))) {
			$mailer_details = $this->systemmailermodel->get_mailer_details_by_name($this->mailer);
			$this->mailer_details = $mailer_details;
		}
		$mail_content = '';
		if(!empty($content)) {
			$mail_content = $this->_postProcessMailContent($content, $extraData);
		}
		return $mail_content;
	}

	public function getMailerDetailsByName() {
		$mailer_details = $this->mailer_details;
		if((empty($mailer_details)) || ((!empty($mailer_details)) && ($mailer_details['mailer_name'] != $this->mailer))) {
			$mailer_details = $this->systemmailermodel->get_mailer_details_by_name($this->mailer);
			$this->mailer_details = $mailer_details;
		}
		return $mailer_details;
	}

	public function getMailerDetails() {
		return $this->mailer_details;
	}

	private function _addAdditionalDetails($extra_data){
		if(isset($extra_data['mailTrackingAdditionalParams'])){
			$this->mailer_details['utm_campaign'] = $this->mailer_details['utm_campaign'].$extra_data['mailTrackingAdditionalParams']['utm_campaign'];
		}

		if(isset($extra_data['subjectReplace'])){
			$this->mailer_details['subject'] = str_replace($extra_data['subjectReplace']['replaceVariables'], $extra_data['subjectReplace']['replaceConstants'], $this->mailer_details['subject']);
		}
	}
}
