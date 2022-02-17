<?php

class SystemMailer extends MX_Controller
{
    private $systemMailerProcessor;
    private $mailerMisId;

    function __construct()
    {
    	$this->mailerMisId =0;
        $this->load->library('systemMailer/SystemMailerProcessor');
    }
    
    /**
     * Welcome to Shiksha
     * Event: Registration
     */ 
    function sendWelcomeMailer($toEmail,$data = array(),$attachment = array())
    {
    	global $isMobileApp;
    	if($data['usertype'] != 'studyabroad' || ($isMobileApp)){

			$return_mail_id = 'Y'; // for view mail in browser link
			$mail_content = '';
			if($isMobileApp){
				$mailer_name = 'welcomeApp';
				$senderName = ' Shiksha Ask & Answer';
			}else{
				$mailer_name = 'WelcomeFullTimeMBA';
				$senderName = ' Shiksha';
			}
			
			$subject = '';
			$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
			
			$is_attachment = 'N';
			if(!empty($attachment)) {
				$is_attachment = 'Y';
			}
			$mail_id = (int)$response;
			if($mail_id <=0){
	        	return false;
	        }
			$data['mailer_name'] = $mailer_name;
			$data['mail_id'] = $mail_id;
			$data['is_attachment'] = $is_attachment;
			$data['unsubscribeLinkReq'] = 'false';
			$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
			//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
			
			$mailProcessor = new SystemMailerProcessor($mailer_name);
			$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);

		} else {

			$subject = "Congrats! You are a Shiksha member now";
			$mailer_name = 'Welcome';
			$data['unsubscribeLinkReq'] = 'false';
			$content = $this->load->view('systemMailer/welcome',$data,TRUE);
			$this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment);		
			
		}
    }
 
    /**
     * Verify your email address
     * Event: Registration
     */  
    function sendEmailVerificationMailer($toEmail,$data = array(),$attachment = array())
    {
        $subject = "Verify your shiksha account";
        $content = $this->load->view('systemMailer/verifyEmail',$data,TRUE);
        $this->_addMailToQueue('EmailVerification',$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment);		
    }

    /*
     * Send email to student when the order is successfully placed
     */  
    function bookedShipmentMailer($data = array())
    {
        $subject = "Shiksha-DHL express order placed";
        $content = $this->load->view('shipment/templates/bookedShipment',$data,TRUE);
        $this->_addMailToQueue('bookedShipmentMailer',$data['emailId'],ADMIN_EMAIL,$subject,$content,"html",$data['attachment'],"0000-00-00 00:00:00",null,$data['bccEmail']);
    }

    /*
     * Send email to student when the order is successfully delivered
     */ 
    function deliveredShipmentMailer($data = array())
    {
        $subject = "Shiksha-DHL express shipment success";
        $content = $this->load->view('shipment/templates/deliveredShipment',$data,TRUE);
        $this->_addMailToQueue('deliveredShipmentMailer',$data['emailId'],ADMIN_EMAIL,$subject,$content,"html",null,"0000-00-00 00:00:00",null,$data['bccEmail']);
    }

    /**
     * Online form Mailers
     * Event: A lot of them
     */
    function onlineFormMailers($toEmail, $type, $subject, $content, $attachment = array())
    {
        $content .= $this->load->view('systemMailer/common/footerUnsubscribe',$data,TRUE);
        $this->_addMailToQueue($type,$toEmail,"noreply@shiksha.com",$subject,$content,"html",$attachment);
    }

    /**
     * Shortlist Mailer
     * Event: Reminder to User
     */


    function shortlistReminderMailer($toEmail , $subject, $data, $attachment = array()){
        $subject = "Reminder for ".$data['note']['title'];
        $senderName = '';
        $fromEmail = 'info@shiksha.com';
        $mailer_name = 'ShortlistReminderMail';
        $data['leanHeaderFooterV2'] = 1;
        $mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
        $response = $this->_addMailToQueue($mailer_name,$toEmail,$fromEmail,$subject,$mail_content,"html",array(), '', '', '', $senderName, 'unsent', $return_mail_id, '', $additionParams);
    }
    /**
     * Campus Ambassador
     * Event: Accept
     */
    function campusAmbassadorAcceptMailer($toEmail,$data = array(),$attachment = array())
    {
	$subject = "Accept - Congratulations! You have been accepted as ".(($data['badge']=='CurrentStudent')?'Current Student':$data['badge'])." of ".$data['courseObj']->getName()." ".$data['courseObj']->getInstituteName();
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
        $this->_addMailToQueue('CAAcceptMailer',$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment);
    }

     /**
     * Campus Ambassador
     * Event: Reject
     */
    function campusAmbassadorRejectMailer($toEmail,$data = array(),$attachment = array())
    {
        $subject = "We regret to inform you that your form has not been accepted.";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
        $this->_addMailToQueue('CARejectMailer',$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment);
    }

    /**
     * Campus Ambassador
     * Event: Incomplete
     */
    function campusAmbassadorIncompleteMailer($toEmail,$data = array(),$attachment = array())
    {
        $subject = "Please complete your registration form for Shiksha Campus Rep program";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
        $this->_addMailToQueue('CAIncompleteMailer',$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment);
    }
    
    /**
     * Add mail to queue for processing
     */ 
    private function _addMailToQueue($mailer,$toEmail,$fromEmail,$subject = '',$content,$mailType = 'html',$attachment = array(), $sendTime = "0000-00-00 00:00:00", $ccEmail=null, $bccEmail=null, $fromUserName="Shiksha", $isSent = 'unsent', $return_mail_id = 'N', $mail_sent_time ='', $extra_data = array())
    {
        $mailProcessor = new SystemMailerProcessor($mailer);
        $response = $mailProcessor->process($toEmail,$fromEmail,$subject,$content,$mailType,$attachment, $sendTime, $ccEmail,$bccEmail, $fromUserName, $isSent, $return_mail_id,$mail_sent_time, $extra_data);
        if($return_mail_id == 'Y'){
        	if($response['status'] == true){
        		$this->mailerMisId = $response['mailerMisId'];
	        	$response = $response['tMailQueueId'];
        	}else{
        		$response = $response['status'];
        	}
        }
		return $response;
    }
    
    public function track($mailer,$email,$url, $mailId)
    {
        $this->load->model('user/usermodel');
        $email = trim($email);
        $validation = validateEmailMobile("email",trim($email));
        
        if ($validation)
        {    
            $userId = $this->usermodel->getUserIdByEmail($email);
        }
        else{
            mail("teamldb@shiksha.com", "Invalid Email on System Mailer Tracking", "This Could have been a db Error. <br>Url = ".$url.'==mailer=='.$mailer.'==mailid=='.$mailId.'==emailid=='.$email);
        }

        if($url == 1){
        	$url = '';
        }
        if($mailer && ($userId || ($mailer == 'registrationReminderMailer'))){
        	if(!empty($url)) {
				$activity_type = 'click';
			} else {
				$activity_type = 'open';
			}
			$this->load->model('systemMailer/systemmailermodel');
        	$mailer_details = $this->systemmailermodel->get_mailer_details_by_name($mailer);
	        $this->systemmailermodel->track($mailer,$userId,$url, $activity_type, $email, $mailer_details['mailer_id'], $mailId);
        }
        
        if(!$url) {
            $myFile = "/var/www/html/shiksha/public/images/blankImg.gif";
            $fh = fopen($myFile, 'r');
            $theData = fread($fh, filesize($myFile));
            fclose($fh);
            echo $theData;
            exit();
        }
    }
    
    function autoLogin($data,$extraData='')
	{
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		if($extraData){
            $data = $data.'/'.$extraData;
        }
        
		$dataArr = explode("_",$data);
		$finalData = array();
		for($i = 0 ; $i < count($dataArr); $i++) {
			$tempArr = explode("~",$dataArr[$i]);
			$finalData[$tempArr[0]] = $tempArr[1];
		}
        
        if($finalData['url']) {
        	$finalData['url'] = str_replace(' ','+',$finalData['url']);
            $url = base64_decode($finalData['url']);
            $redirectUrl = $url;
        }
        else {
            $redirectUrl = 'https://'.THIS_CLIENT_IP;
        }
        
		$email = $finalData['email'];
		if($email!='') {
			$result = $objmailerClient->autoLogin($this->appId,$email);
			setcookie('user',$result,0,'/',COOKIEDOMAIN);
			setcookie('examPopup', 1, time()+(60*60), '/', COOKIEDOMAIN);  //???????????????????????/

			// check if url is profilePage or not. if yes than check user is national or abroad user.
			$redirectUrl = $this->processRedirectUrl($redirectUrl,$result);
            /**
             * Track this link in MIS
             */ 
            
            if($finalData['mailer']) {
                $resultArr = explode('|',$result);
                $this->track($finalData['mailer'],$resultArr[0],$redirectUrl, $finalData['mailId']);
            }
		}
        
		header( "Location: $redirectUrl" );
	}

	public function processRedirectUrl($redirectUrl,$cookieData){
		if(strpos($redirectUrl, '?selected_tab=1') !== false || strpos($redirectUrl, 'unscr=') !== false){	
        	if(strpos($redirectUrl, '?selected_tab=1') !== false){
        		$redirectUrl = str_replace("selected_tab=1", "unscr=0", $redirectUrl);
        	}
        	$userDetails = $this->checkUserValidation($cookieData);
        	if($userDetails[0]['userid']>0){
        		$usermodel = $this->load->model('user/usermodel');
    			$result = $usermodel->getUserEducationLevel($userDetails[0]['userid']);
    			if($result['ExtraFlag'] == 'studyabroad'){
    				$userprofilelib = $this->load->library('userProfile/UserProfileLib');
    				$SAProfileUrl = $userprofilelib->redirectionUrl($userDetails[0]['displayname']);
    				$redirectUrl = explode("?", $redirectUrl);
    				$redirectUrl = $SAProfileUrl.'?'.$redirectUrl[1];
    			}
        	}
        }
        return $redirectUrl;
	}
	
	/**
	 * Track this link in MIS
	 */
	function mailerTracking($data,$extraData='')
	{
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		if($extraData){
            $data = $data.'/'.$extraData;
        }
        
		$dataArr = explode("_",$data);
		$finalData = array();
		for($i = 0 ; $i < count($dataArr); $i++) {
			$tempArr = explode("~",$dataArr[$i]);
			$finalData[$tempArr[0]] = $tempArr[1];
		}
        
        if($finalData['url']) {
            $url = base64_decode($finalData['url']);
            $redirectUrl = $url;
        }
        else {
            $redirectUrl = 'https://'.THIS_CLIENT_IP;
        }

		$email = $finalData['email'];
		if($email!='') {            
            if($finalData['mailer']) {
				$this->load->model('user/usermodel');
				$decoded_email = $this->usermodel->getDecodedEmail($email);
                $this->track($finalData['mailer'],$decoded_email,$redirectUrl, $finalData['mailId']);
            }
		}
        
		header( "Location: $redirectUrl" );
	}

    function campusAmbassadorQuestionIntimation($toEmail,$data = array(),$attachment = array())
       {
	    $subject = $data['subject'];
	    $data['type']= 'CAQuestionIntimation';
	    $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	    $this->_addMailToQueue('CAQuestionIntimation',$toEmail,'noreply@shiksha.com',$subject,$content,"html",$attachment);
	}

	function careerSimilarRecommendationMailer($toEmail,$data = array(),$attachment = array())
		{
		$firstVar  = checkifvowel($data['mainCareerName']);
		$secondVar = checkifvowel($data['firstRecommendedCareer']);
		$subject   = "You want to be ".$firstVar." ".$data['mainCareerName'].".You could also consider other career options ...";
		$fromEmail = ' info@shiksha.com';
		$mailer_name = 'CareerSimilarRecommendation';
		$data['leanHeaderFooterV2'] = 1;
		$mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		$response = $this->_addMailToQueue($mailer_name,$toEmail,$fromEmail,$subject,$mail_content,"html",array(), '', '', '', $senderName, 'unsent', $return_mail_id, '', $additionParams);
		}  
	
	function campusAmbassadorREBMailer($toEmail,$data = array(),$attachment = array())
		{
		    $subject = "Ask your question to a current student of ".$data['courseObj']->getInstituteName();
		    $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
		    $this->_addMailToQueue('CARequestEBrochure',$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment);
		}

	function collegePredictorMail($toEmail , $data = array() , $attatchment)
		{
			$subject = "Find your  '" .$data['text'] ."' shortlist in your mailbox";
			$content = $this->load->view('systemMailer/CollegePredictor/resultMail',$data,TRUE);
			$this->_addMailToQueue('collegePredictorEmail',$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment);
		
		}	

	function inviteFriendsCPMail($toEmail , $data = array() , $attatchment)
               {
		    $subject = "Your friend ".$data['firstName']." has invited you to use JEE Main College Predictor";
                    $content = $this->load->view('systemMailer/CollegePredictor/inviteFriends',$data,TRUE);
                    $this->_addMailToQueue('inviteFriendsCPMail',$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment);
               
               }
	
	function articleFeedbackMail($toEmail , $data = array() , $attatchment)
               {
		    $subject = "A new feedback has been received on your article.";
                    $content = $this->load->view('systemMailer/studyAbroadArticle/feedbackArticle',$data,TRUE);
                    $this->_addMailToQueue('feedbackArticleMail',$toEmail,'studyabroad@shiksha.com',$subject,$content,"html",$attachment);
               
               }
	       
	function articleCommentMail($toEmail , $data = array() , $attatchment)
               {
		    if($data['articleType'] == 'examPage'){
			    $type = 'exam page';
		    }
		    else if($data['articleType'] == 'guide'){
			    $type = 'guide';
		    }
			else if($data['articleType'] == 'applyContent'){
			    $type = 'Apply Content Page';
		    }
		    else{
			    $type = 'article';
		    }
		    $subject = "A new comment has been received on your ".$type.".";
                    $content = $this->load->view('systemMailer/studyAbroadArticle/commentStudyAbroadArticle',$data,TRUE);
                    $this->_addMailToQueue('commentStudyAbroadArticle',$toEmail,'studyabroad@shiksha.com',$subject,$content,"html",$attachment);
               
               }
	function articleReplyMail($toEmail , $data = array() , $attatchment)
               {
		    if($data['articleType'] == 'examPage'){
			    $type = 'exam page';
		    }
		    else if($data['articleType'] == 'guide'){
			    $type = 'guide';
		    }
			else if($data['articleType'] == 'applyContent'){
			    $type = 'Apply Content Page';
		    }
		    else{
			    $type = 'article';
		    }
		    if($data['type']!= 'author')
			$subject = "A new reply has been received on your comment by ".$data['userName'].".";
		    else
			$subject = "A new reply has been received on your ".$type." by ".$data['userName'].".";
		    $content = $this->load->view('systemMailer/studyAbroadArticle/replyOnCommentMail',$data,TRUE);
                    $this->_addMailToQueue('commentReplyStudyAbroadArticle',$toEmail,'studyabroad@shiksha.com',$subject,$content,"html",$attachment);
               
               }
	function CRAnswerDisapprovalMail($toEmail , $data = array(), $attachment = array())
	{
	    $subject = "Your answer is disapproved.";
            $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	    $this->_addMailToQueue('CampusRepAnswerDisapproved', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
	}
	function CRNewOpenTaskMail($toEmail , $data = array(), $attachment = array())
	{
	    $subject = "You have a new task to participate.";
            $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	    $this->_addMailToQueue('CampusRepNewOpenTask', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
	}
	function CRUnansweredWeeklyDigestMail($toEmail , $data = array(), $attachment = array())
	{
	    $subject = "You have unanswered questions to answer.";
            $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	    $this->_addMailToQueue('CampusRepUnansweredWeeklyDigest', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
	}
	function CRPayoutMail($toEmail , $data = array(), $attachment = array())
	{
	    $subject = "Cheque payment of your earnings.";
            $content = $this->load->view('systemMailer/CampusAmbassador/CAChequePayoutMailer',$data,TRUE);
	    $this->_addMailToQueue('CampusRepChequePayout', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
	}
	function CROpenTasksWeeklyMail($toEmail , $data = array(), $attachment = array())
	{
	    $subject = "Participate in tasks and stand to win Rs ".$data['tasks']['grand_total'].".";
            $content = $this->load->view('systemMailer/CampusAmbassador/CAOpenTasksWeeklyMail',$data,TRUE);
	    $this->_addMailToQueue('CampusRepOpenTasksWeeklyMailer', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
	}
	function CollegeReviewReceived_Mail($toEmail , $data = array(), $attachment = array())
	{
	    $subject = "Thank You! Your College Review has reached us at Shiksha.";

	    if($data['incentiveFlag'] == 'incentive'){
	    	$content = $this->load->view('systemMailer/CollegeReview/reviewReceivedIncentive',$data,TRUE);
	    }else{
            $content = $this->load->view('systemMailer/CollegeReview/reviewReceived',$data,TRUE);
        }
	    $this->_addMailToQueue('CollegeReviewReceived_Mailer', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
	}
	function CollegeReviewPublish_Mail($toEmail , $data = array(), $attachment = array(), $incentiveFlag='')
	{
	    $subject = "Congratulations! Your College Review is now live on Shiksha.";
	  //   if($data['isCampusRep'] == 1)
	  //   {
			// $content = $this->load->view('systemMailer/CollegeReview/reviewPublishForCR',$data,TRUE);
	  //   }
	  //   else
	    {
	    	if($data['incentiveFlag'] == 'incentive'){
	    		$content = $this->load->view('systemMailer/CollegeReview/reviewPublishIncentive',$data,true);
	    	}else{
				$content = $this->load->view('systemMailer/CollegeReview/reviewPublish',$data,TRUE);
			}
	    }
	    $this->_addMailToQueue('CollegeReviewPublish_Mailer', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
	}
	function CollegeReviewReject_Mail($toEmail , $data = array(), $attachment = array(), $delayedMailTime='')
	{
	    $subject = "Oops! Your College Review on Shiksha needs attention!";
  //           if($data['isCampusRep'] == 1)
	 //    {
		// $content = $this->load->view('systemMailer/CollegeReview/reviewRejectedForCR',$data,TRUE);
	 //    }
	 //    else
	    {
		$content = $this->load->view('systemMailer/CollegeReview/reviewRejected',$data,TRUE);
	    }

	    if($delayedMailTime!=''){
	    	$this->_addMailToQueue('CollegeReviewReject_Mailer', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment,'0000-00-00 00:00:00',null,null,'Shiksha','','N', $delayedMailTime);
	    }else{
	    	$this->_addMailToQueue('CollegeReviewReject_Mailer', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
	    }

	}

    function CollegeReviewVerifyDetail_Mail($toEmail , $data = array(), $attachment = array()){
        $subject = 'Verify your Shiksha Review Details';
        $senderName = 'Shiksha Review Team';
        $fromEmail = ' info@shiksha.com';
        $mailer_name = 'CollegeReviewVerifyDetail';
        $data['leanHeaderFooterV2'] = 1;
        $mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
        $response = $this->_addMailToQueue($mailer_name,$data['email'],$fromEmail,$subject,$mail_content,"html",array(), '', '', '', $senderName, 'unsent', $return_mail_id, '', $additionParams);
    }
	
	/**
     * When user forgot Password of shiksha account
     * Event: Forgot Password
     */ 
    function sendForgotPasswordMailer($toEmail,$data = array(),$attachment = array()) {

		$return_mail_id = 'Y'; // for view mail in browser link
		$content = '';
		$attachment = array();
		$subject = '';
		$mailer_name = '';
		$mailer_name = $data['mailer_name'];

        $this->load->library('systemMailer/SystemMailerProcessor');
        $mailProcessor = new SystemMailerProcessor($mailer_name);
        $mailerDetails = $mailProcessor->getMailerDetailsByName();
        $subject = $mailerDetails['subject'];

		$mail_id = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment, '', '', '', 'Shiksha', 'sent', $return_mail_id);

        $mail_id = (int)$mail_id;
        if($mail_id <=0){
        	return false;
        }
        $data['mail_id'] = $mail_id;
        $data['unsubscribeLinkReq'] = 'false';
        $content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
        
        $mailProcessor->setEmailid($toEmail);

        $extraData = array('mailerMisId' => $this->mailerMisId); //, 'openTrackingAdded' => 1);
        $content = $mailProcessor->processMailContent($content, $extraData);

        $isSent = 'unsent';$sendMail = 'direct';
        if($sendMail == 'direct') {
            $val = mail($toEmail,$subject,$content,$data['headers'], '-f info@shiksha.com');    //to send mail immediately
            if($val=='TRUE'){
                $isSent = 'sent';            
            } else {
                $isSent = 'unsent'; 
            }
        }
        $response = $mailProcessor->updateMailToQueue($toEmail, $content, $mail_id, $isSent, $this->mailerMisId, 'no');
		return $response;
    }
	
	public function ViewMailinBrowser($id)
    {
		
		$user = $this->checkUserValidation();
		if(!is_array($user) || !is_array($user[0]) || !$user[0]['userid']) {
			echo "Sorry. You are not authorized to view this page.";
			exit;
		}
		
		$emailArr = explode("|",$user[0]['cookiestr']);
		$email_id = $emailArr[0];
		if(!empty($email_id)) {
			$this->load->model('systemMailer/systemmailermodel');
			$mail_details = $this->systemmailermodel->get_mail_content($id, $email_id);
			if(!empty($mail_details)) {
				$mail_content = $mail_details['content'];
				$this->load->view('systemMailer/CommonMailers/viewMailinBrowser', array('mail_content'=>$mail_content));
			} else {
				echo "Sorry. This mailer does not exist.";exit;
			}
		} else {
			echo "Sorry. This mailer does not exist.";exit;
		}
    }
	
	/**
     * send Request Brochure + Compare + Shortlist + Send Contact Details mail to user
     * Event: sendRequestBrochureMailer
     */ 
    function sendRequestBrochureMailer($toEmail,$data = array(),$attachment = array())
    {
	    $return_mail_id = 'Y'; // for view mail in browser link
	    $mail_content = '';
	    $mailer_name = $data['mailer_name'];

	    $this->load->library('systemMailer/SystemMailerProcessor');
	    $mailProcessor = new SystemMailerProcessor($mailer_name);
	    $mailerDetails = $mailProcessor->getMailerDetailsByName();
	    
	    if(!empty($data['mailContentSecondLineText'])) {
	    	$subject = 'Your '.$data['mailContentSecondLineText'].' report is Here | #courseName at #instituteName';
	    } else {
	    	$subject = $mailerDetails['subject'];
	    }

	    $replaceVariables = array('#instituteName','#courseName');
	    $replaceConstants = array($data['instituteName'],$data['courseName']);
	    $subject = $this->replaceVariablesInSubject($replaceVariables,$replaceConstants,$subject);

	    $mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
	    $response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', 'Shiksha College', '', $return_mail_id);	   	
	   
	    return $response;
	}

	function replaceVariablesInSubject($arrayOfVariables = array(),$arrayOfConstants = array(),$subject){
		$subject = str_replace($arrayOfVariables, $arrayOfConstants, $subject);

		return $subject;
	}
	
    function sendNotificationToUserForExamAlertSubscription($toEmail,$data = array(),$attachment = array())
    {
        $subject         = "Alert: ".$data['exam_name'].", ".$data['event_name'];
        $data['heading'] = $subject;
        $mailer_name     = 'examEventSubscriptionMailer';
        $data['leanHeaderFooterV2'] = 1;
        $mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
        $this->_addMailToQueue($mailer_name,$toEmail, ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, null, $return_mail_id); 
    }
    
    function studyAbroadSendBrochure($data = array()){
        $data['mailer_name'] = 'studyAbroadDownloadBrochure'.$data['template_type'];
        $attachment = array($data['course_brochure_attachment_id'],$data['university_brochure_attachment_id']);
        $attachment = array_values(array_filter($attachment));
        
        if($data['scholarship_brochure_attachment_id']){
        	$attachment = array($data['scholarship_brochure_attachment_id']);
        }
        $content = $data['emailContent'];
        $response = $this->_addMailToQueue($data['mailer_name'],$data['usernameemail'],$data['fromEmail'],$data['emailSubject'],$content,'html',$attachment);
        return $response;
    }

    function studyAbroadStageChange($data = array()){
        $data['mailer_name'] = 'studyAbroadStageChange';
        $response = $this->_addMailToQueue($data['mailer_name'],$data['email'],$data['fromEmail'],$data['emailSubject'],$data['emailContent'],'html',                     null);
        return $response;
    }

    /*
     * mailer name has to passed in param(s) data
     */
    function emailSAGuideToUser($data = array()){
        $attachment = null;
        if(!empty($data['attachmentId'])) {
            $attachment = array($data['attachmentId']);
        }
        $response = $this->_addMailToQueue($data['mailer_name'],$data['toEmail'],$data['fromEmail'],$data['emailSubject'],$data['emailContent'],'html',                     $attachment);
        return $response;
    }

    function studyAbroadRMCReminder($data = array()){
        $data['mailer_name'] = 'studyAbroadRMCReminder';
        $response = $this->_addMailToQueue($data['mailer_name'],$data['toEmail'],$data['fromEmail'],$data['emailSubject'], $data['emailContent'],'html',null,'', $data['ccEmail']);
        return $response;
    }

    function studyAbroadFeedback($data = array()){
        $data['mailer_name'] = 'studyAbroadFeedback';
        $response = $this->_addMailToQueue($data['mailer_name'],$data['toEmail'],$data['fromEmail'],$data['emailSubject'], $data['emailContent'],'html',null, '', $data['ccMail'], $data['bccMail']);
        return $response;
    }

	function studyAbroadShikshaApplyMailers($data = array()){
        $data['mailer_name'] = 'studyAbroadShikshaApplyMailer';
        $content = $data['emailContent'];
        $data['counselor'] = $data['counselor'] ? $data['counselor'] : null;
        $response = $this->_addMailToQueue($data['mailer_name'],$data['usernameemail'],$data['fromEmail'],$data['emailSubject'],$content,'html',null,"0000-00-00 00:00:00",$data['counselor'],"simrandeep.singh@shiksha.com");
        return $response;
    }
    
    function sendAbroadConsultantMailToUsers($data = array()){
        $data['mailer_name'] = 'studyAbroadConsultantMailer';
        $respponse = $this->_addMailToQueue($data['mailer_name'], $data['userEmail'], $data['fromEmail'], $data['mailSubject'], $data['emailContent'], 'html', array(), '', '', '', 'Shiksha.com','','Y');
        return $respponse;
    }

    /**
     * adding utm parameters in mail for tracking
     * Affected table shikshaMailerMis and tMailQueue
     * @author Aman Varshney <aman.varshney@shiksha.com>
     * @date   2015-05-01
     * @param  array      $data Mailer Data
     * @return
     */
    function nationalMailerTrackingCompiler($data = array()){
		$attachment = array();
		$content    = $data['content'];
		
		$response   = $this->_addMailToQueue($data['mailer_name'],$data['to_mail'],$data['from_email'],$data['subject'],$content,'html',$attachment);
		return $response;
    }

   	
   	/**
   	 * send request brochure except mba category
   	 * @author Aman Varshney <aman.varshney@shiksha.com>
   	 * @date   2015-05-05
   	 * @param  		      $toEmail    
   	 * @param  array      $data       
   	 * @param  array      $attachment 
   	 * @return                 
   	 */
    function sendRequestBrochureNationalMailer($toEmail,$data = array(),$attachment = array())
    {

		$mail_content   = $data['content'];
		$mailer_name    = $data['mailer_name'];
		$subject        = $data['subject'];
		$response       = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment);
		return $mail_content;
	}

function menteeRegistrationMailtoMentee($toEmail,$data = array(),$attachment = array())
    {
    $subject = "Thank you for enrollment into the mentorship program. ";
    $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('menteeRegistrationMailtoMentee', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    	
    }

function assignMentorToMenteeMailer($toEmail,$data = array(),$attachment = array())
    {
	$subject = "You have a new engineering mentor";
	$data['type'] = "MentorAssignMailToMentee";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('mentorAssignMailer', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    }
    
function assignMenteeToMentorMailer($toEmail,$data = array(),$attachment = array())
    {
	$subject = "You have a new engineering mentee";
	$data['type'] = "MenteeAssignMailToMentor";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('menteeAssignMailer', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    }
    
function requestChatByMentee($toEmail,$data = array(),$attachment = array())
    {
	$subject = "Request for chat with".' '.$data['menteefirstname'];
	$data['type'] = "requestChatByMentee";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('requestChatByMentee', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    }
    
function mentorToSetUpChatAvailability($toEmail,$data = array(),$attachment = array())
    {
	$subject = "Earn Rs. 50 by completing a chat session with your mentees.";
	$data['type'] = "mentorToSetUpChat";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('mentorToSetUpChat', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    }
    
function menteeToSelectSlot($toEmail,$data = array(),$attachment = array())
    {
	$subject = " Schedule a chat with your mentor";
	$data['type'] = "menteeToSelectSlot";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('menteeToSelectSlot', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    }
    
function acceptDeclineChatRequestByMentor($toEmail,$data = array(),$attachment = array())
    {
	$subject = " Request for chat ".$data['status'];
	$data['type'] = "AcceptDeclineChatRequestByMentor";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('AcceptDeclineChatRequestByMentor', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    
    }
    
function chatScheduledMailToMentee($toEmail,$data = array(),$attachment = array())
    {
	$subject = " New chat scheduled on ".$data['slotTime'];
	$data['type'] = "ChatScheduledMailToMentee";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('ChatScheduledMailToMentee', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    
    }

function chatScheduledMailToMentor($toEmail,$data = array(),$attachment = array())
    {
	$subject = "New chat scheduled on ".$data['slotTime'];
	$data['type'] = "ChatScheduledMailToMentor";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('ChatScheduledMailToMentee', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    
    }

function chatSessionCancelledByMentor($toEmail,$data = array(),$attachment = array())
    {
	$subject = "Chat session cancelled";
	$data['type'] = "ChatSessionCancelledByMentor";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('ChatSessionCancelledByMentor', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    
    }
    
function chatSessionCancelledByMentee($toEmail,$data = array(),$attachment = array())
    {
	$subject = "Chat session cancelled";
	$data['type'] = "ChatSessionCancelledByMentee";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('ChatSessionCancelledByMentee', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    
    }
    
function chatCompletionMailToMentee($toEmail,$data = array(),$attachment = array())
    {
	$subject = "Your chat transcript for chat session on ".$data['slotTime'].'.';
	$data['type'] = "ChatCompletionMailToMentee";
        $content = $this->load->view('systemMailer/CampusAmbassador/CAMailer',$data,TRUE);
	$this->_addMailToQueue('ChatCompletionMailToMentee', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    
    }
    
    /**
     * sending mail for thumb up on an answer thumbs up
     * Affected table shikshaMailerMis and tMailQueue
     * @author Virender Singh <virender.singh@shiksha.com>
     * @date   2015-06-25
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function cafeThumbUpToAnswer($toEmail,$data = array(),$attachment = array())
    {

		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = $data['type'];
		$senderName = ' Shiksha Ask & Answer';
		$subject = ($data['type'] == 'discussionThumbMail') ? $data['raterDisplayName']." upvoted your comment." : $data['raterDisplayName']." upvoted your answer.";
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);
		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);			
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
		
    }
    
    /**
     * sending mail for an answer post to a question
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-03
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function answerPostToQuestion($toEmail,$data = array(),$attachment = array())
    {

		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'AnswerPostToQuestion';
		$senderName = ' Shiksha Ask & Answer';
		
		$subject = $data['answerOwnerName']." posted an answer to your question.";
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		// $mail_id = (int)$response;
		// if($mail_id <=0){
  //       	return false;
  //       }
		// $data['mailer_name'] = $mailer_name;
		// $data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		//$mailProcessor = new SystemMailerProcessor($mailer_name);
		//$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
        $response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment, '', '', '', $senderName, 'unsent');

    }
    
    /**
     * sending mail to all user for an answer post to a question
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-03
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function answerPostToQuestionAllUser($toEmail,$data = array(),$attachment = array())
    {
	
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'AnswerPostToQuestionAllUser';
		$senderName = ' Shiksha Ask & Answer';

		
		$subject = $data['answerOwnerName']." posted an answer to ".$data['questionOwnerName']."'s question.";
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		// $mail_id = (int)$response;
		// if($mail_id <=0){
  //       	return false;
  //       }
		// $data['mailer_name'] = $mailer_name;
		// $data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		//$mailProcessor = new SystemMailerProcessor($mailer_name);
		//$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
        $response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment, '', '', '', $senderName, 'unsent');
    }
    
    /**
     * sending mail to question owner for a comment post to an answer
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht 
     * @date   2016-02-04
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function commentPostOnAnswer($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'CommentPostOnAnswer';
		$senderName = ' Shiksha Ask & Answer';

		
		$subject = $data['mail_subject'];
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }
    
    /**
     * sending mail to all users when an answer is marked as best answer
     * Affected table shikshaMailerMis and tMailQueue
     * @author Virender Singh <virender.singh@shiksha.com>
     * @date   2015-06-29
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function answerSelectedAsBestAnswer($toEmail,$data = array(),$attachment = array())
    {
	$subject = $data['mail_subject'];
	$data['mailer_name'] = 'AnswerSelectedAsBestAnswer';
	$content = $this->load->view('systemMailer/CafeMailers/CafeMailerMain', $data, TRUE);
	$this->_addMailToQueue('AnswerSelectedAsBestAnswer', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    }
    
    /**
     * sending mail to user when user has posted a question/discussion/announcement
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-04
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function questionDiscussionAnnouncementPost($toEmail,$data = array(),$attachment = array())
    {
    	if($data['type'] == 'askQuestion' && ($data['page_name'] == 'myShortlist_Ana' || $data['page_name'] == '2')){
	    		$subject = $data['mail_subject'];
				$data['mailer_name'] = 'QuestionDiscussionAnnouncementPost';
	    		$content = $this->load->view('systemMailer/CafeMailers/CafeMailerMain', $data, TRUE);
				$this->_addMailToQueue('QuestionDiscussionAnnouncementPost', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    	}else{
    			if($data['type'] == 'postTopic'){
    				$mailer_name = 'DiscussionAnnouncementPost';
    			}else{
    				$mailer_name = 'QuestionPost';
    			}
				//$return_mail_id = 'Y'; // for view mail in browser link
				//$mail_content = '';
				
				$senderName = ' Shiksha Ask & Answer';

				
				$subject = $data['mail_subject'];
				
				// $is_attachment = 'N';
				// if(!empty($attachment)) {
				// 	$is_attachment = 'Y';
				// }
				// $mail_id = (int)$response;
				// if($mail_id <=0){
		  //       	return false;
		  //       }
				//$data['mailer_name'] = $mailer_name;
				//$data['mail_id'] = $mail_id;
				$data['is_attachment'] = $is_attachment;
                $data['leanHeaderFooterV2'] = 1;
                $data['leanHeaderFooter'] = 0;
                $data['leanHeaderFooterV3'] = 0;
                $data['leanFooterV4'] = 0;


				$this->load->model('systemMailer/systemmailermodel');
				$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

				$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
				//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
				
				//$mailProcessor = new SystemMailerProcessor($mailer_name);
				$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment, '', '', '', $senderName, 'unsent');
				//$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
			}
    }
    
    /**
     * sending daily mailer to non-Enterprise user about questions posted on their Listing
     * Affected table shikshaMailerMis and tMailQueue
     * @author Virender Singh <virender.singh@shiksha.com>
     * @date   2015-06-30
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function dailyMailerToEnterpriseUser($toEmail,$data = array(),$attachment = array())
    {
	$subject = $data['mail_subject'];
	$data['mailer_name'] = 'dailyMailerToEnterpriseUser';
	$content = $this->load->view('systemMailer/CafeMailers/CafeMailerMain', $data, TRUE);
	$this->_addMailToQueue('dailyMailerToEnterpriseUser', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    }
    
    /**
     * sending daily mailer to non-Enterprise user about questions posted on their Listing
     * Affected table shikshaMailerMis and tMailQueue
     * @author Virender Singh <virender.singh@shiksha.com>
     * @date   2015-06-30
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function dailyMailerToNonEnterpriseUser($toEmail,$data = array(),$attachment = array())
    {
	$subject = $data['mail_subject'];
	$data['mailer_name'] = 'dailyMailerToNonEnterpriseUser';
	$content = $this->load->view('systemMailer/CafeMailers/CafeMailerMain', $data, TRUE);
	$this->_addMailToQueue('dailyMailerToNonEnterpriseUser', $toEmail, ADMIN_EMAIL, $subject, $content, "html", $attachment);
    }


    /**
     * sending mail to user if level is promoted
     * Affected table shikshaMailerMis and tMailQueue
     * @author Virender Singh <virender.singh@shiksha.com>
     * @date   2015-06-29
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
		function userLevelPromotion($toEmail,$data = array(),$attachment = array()) 
 	    { 
			$return_mail_id = 'Y'; // for view mail in browser link
			$mail_content = '';
			$mailer_name = 'userLevelPromotion';
			$senderName = ' Shiksha Ask & Answer';
			
			//$subject = $data['answerOwnerName']." posted an answer to your question.";
			$subject = "Congrats! You just moved one level up"; 
			$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
			
			$is_attachment = 'N';
			if(!empty($attachment)) {
				$is_attachment = 'Y';
			}
			$mail_id = (int)$response;
			if($mail_id <=0){
	        	return false;
	        }
			$data['mailer_name'] = $mailer_name;
			$data['mail_id'] = $mail_id;
			$data['is_attachment'] = $is_attachment;

			$this->load->model('systemMailer/systemmailermodel');
			$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);


			$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
			
			$mailProcessor = new SystemMailerProcessor($mailer_name);
			$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
	 	}


 	/**
     * sending mail to comment owner for a reply post to discussion/announcement
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-04
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function replyPostOnComment($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'PostReplyOnComment';
		$senderName = ' Shiksha Ask & Answer';

		$subject = $data['mail_subject'];
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
	
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }

	/**
     * sending mail on linked Discussion
     * Affected table shikshaMailerMis and tMailQueue
     * @author Ankit Bansal <ankit.b@shiksha.com>
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
		function discussionLinkedStatusChangedMailer($toEmail,$data = array(),$attachment = array()) 
 	    { 
			$return_mail_id = 'Y'; // for view mail in browser link
			$mail_content = '';
			$mailer_name = 'discussionLinkedStatusChangedMailer';
			$senderName = ' Shiksha Ask & Answer';
			$fromMail = $data['fromMail'];
			$subject = $data['subject'];
			$response = $this->_addMailToQueue($mailer_name,$toEmail,$fromMail,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
			
			$is_attachment = 'N';
			if(!empty($attachment)) {
				$is_attachment = 'Y';
			}
			$mail_id = (int)$response;
			if($mail_id <=0){
	        	return false;
	        }
			$data['mailer_name'] = $mailer_name;
			$data['mail_id'] = $mail_id;
			$data['is_attachment'] = $is_attachment;
            $data['leanHeaderFooterV2'] = 1;

			$this->load->model('systemMailer/systemmailermodel');
			$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

			$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
			
			$mailProcessor = new SystemMailerProcessor($mailer_name);
			$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
 		}

/**
     * sending mail to mentioned user
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-04
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function mentionUserOnShiksha($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'MentionUserOnShiksha';
		$senderName = ' Shiksha Ask & Answer';

		$subject = $data['mail_subject'];
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
	
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }

	/**
     * sending mail on linked Question
     * Affected table shikshaMailerMis and tMailQueue
     * @author Ankit Bansal <ankit.b@shiksha.com>
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
	function questionLinkedStatusChangedMailer($toEmail,$data = array(),$attachment = array()) 
    { 
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'questionLinkedStatusChangedMailer';
		$senderName = ' Shiksha Ask & Answer';
		$fromMail = $data['fromMail'];
		$subject = $data['subject'];

		// To get the tMailQueue Id
		$response = $this->_addMailToQueue($mailer_name,$toEmail,$fromMail,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');

		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
	}
	/*
	 * function to send shortlists to rmc candidates
	 */
	function rmcCandidateShortlistMail($data = array())
	{
		$toEmail = $data['toEmail'];
		$subject = "Shiksha.com | University Shortlist";
		$content = $data["mailContent"];
		$ccEmail = $data["ccEmail"];
		$bccEmail = $data["bccEmail"];
		$attachment = $data['attachment'];
		$this->_addMailToQueue('rmcCandidateShortlistMail',$toEmail,'studyabroad@shiksha.com',$subject,$content,"html",$attachment, "0000-00-00 00:00:00", $ccEmail, $bccEmail);
	}

	/**
     * Mailer when entity is auto deleted on account of report abuse to content owner
     * Affected table shikshaMailerMis and tMailQueue
     * @author Ankit Bansal <ankit.b@shiksha.com>
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
	function abuseAutoDeleteMail($toEmail,$data = array(),$attachment = array()) 
    { 
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'abuseAutoDeleteMail';
		$senderName = ' Shiksha Ask & Answer';
		$fromMail = $data['fromMail'];
		$subject = $data['subject'];
		$ccEmail = "";//$data['ccmail'];

		// To get the tMailQueue Id
		$response = $this->_addMailToQueue($mailer_name,$toEmail,$fromMail,$subject,$mail_content,"html",$attachment, '', $ccEmail, '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');

		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
	}


	/** 
     * Mailer when entity is report abuse
     * Affected table shikshaMailerMis and tMailQueue
     * @author Ankit Bansal <ankit.b@shiksha.com>
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
	function abuseReportMail($toEmail,$data = array(),$attachment = array()) 
    { 
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'abuseReportMail';
		$senderName = ' Shiksha Ask & Answer';
		$fromMail = $data['fromMail'];
		$subject = $data['subject'];

		$ccEmail = "";//$data['ccmail'];

		// To get the tMailQueue Id
		$response = $this->_addMailToQueue($mailer_name,$toEmail,$fromMail,$subject,$mail_content,"html",$attachment, '', $ccEmail, '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');

		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
	}


	/**
     * Mailer when entity deleted(from moderation panal) on account of report abuse to content owner
     * Affected table shikshaMailerMis and tMailQueue
     * @author Ankit Bansal <ankit.b@shiksha.com>
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
	function abuseDeleteMail($toEmail,$data = array(),$attachment = array()) 
    { 
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'abuseDeleteMail';
		$senderName = ' Shiksha Ask & Answer';
		$fromMail = $data['fromMail'];
		$subject = $data['subject'];

		// To get the tMailQueue Id
		$response = $this->_addMailToQueue($mailer_name,$toEmail,$fromMail,$subject,$mail_content,"html",$attachment, '','', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');

		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
	}


	/**
     * Mailer when entity deleted(from moderation panal) on account of report abuse to report abuser
     * Affected table shikshaMailerMis and tMailQueue
     * @author Ankit Bansal <ankit.b@shiksha.com>
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
	function abusePeopleMail($toEmail,$data = array(),$attachment = array()) 
    { 
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'abusePeopleMail';
		$senderName = ' Shiksha Ask & Answer';
		$fromMail = $data['fromMail'];
		$subject = $data['subject'];
		$ccEmail = "";//$data['ccmail'];

		// To get the tMailQueue Id
		$response = $this->_addMailToQueue($mailer_name,$toEmail,$fromMail,$subject,$mail_content,"html",$attachment, '',$ccEmail, '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');

		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
	}

	/** 
     * Mailer on question closure
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-08
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function questionClosure($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'QuestionClosureMail';
		$senderName = ' Shiksha Ask & Answer';

		$subject = $data['mail_subject'];
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
	
		// $mail_id = (int)$response;
		// if($mail_id <=0){
  //       	return false;
  //       }
		//$data['mailer_name'] = $mailer_name;
		//$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		//$mailProcessor = new SystemMailerProcessor($mailer_name);
		//$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
        $response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment, '', '', '', $senderName, 'unsent');
    }

    /**
     * sending mail to user on republishing entity
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-08
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function republishAbuseEntity($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'RepublishAbuseEntity';
		$senderName = ' Shiksha Ask & Answer';

		$subject = $data['mail_subject'];
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
	
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }

        /**
     * sending mail to user on rejecting abuse entity
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-08
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function rejectAbuseEntity($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'RejectAbuseEntity';
		$senderName = ' Shiksha Ask & Answer';

		$subject = $data['mail_subject'];
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
	
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }

    /**
     * sending mail to user who reported abuse in case of abuse rejection
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-08
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function mailRepublishUserAbuse($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'MailRepublishUserAbuse';
		$senderName = ' Shiksha Ask & Answer';

		$subject = "Your abuse report has been rejected.";
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
	
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }
    
    
    /**
     * @author Abhinav
     * @date   2016-02-09
     */
    function qnaDailyMailer($toEmail,$data = array(),$attachment = array()){
    	$return_mail_id = 'Y'; // for view mail in browser link
    	$mail_content = '';
    	$mailer_name = 'qnaDailyMailer';
    	$senderName = ' Shiksha Ask & Answer';
    
    	$subject = $data['subject'];
    	$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
    
    	$is_attachment = 'N';
    	/* if(!empty($attachment)) {
    		$is_attachment = 'Y';
    	} */
    
    	$mail_id = (int)$response;
    	if($mail_id <=0){
        	return false;
        }
    	$data['mailer_name'] = $mailer_name;
    	$data['mail_id'] = $mail_id;
    	$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
    	$this->load->model('systemMailer/systemmailermodel');
    	$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['userId']);
    	/* _p($data);die; */
    	$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
    	//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
    
    	$mailProcessor = new SystemMailerProcessor($mailer_name);
    	$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    	
    	return $mail_id;
    }

    /**
     * sending mail on comment post
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-02-11
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function commentPostOnEntity($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'CommentPostOnEntity';
		$senderName = ' Shiksha Ask & Answer';

		$subject = $data['mail_subject'];
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
	
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		//$content = $this->load->view('systemMailer/CommonMailers/CommonMailer',$data,TRUE);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }

    /* Function to add IBA mail to the mail queue
     * @params: $toEmail: Email address of the receiver
     */
    function processIBAMailer($toEmail, $userData){
		$subject = "Excellent Placements with Best ROI - Know More about PGDM Admissions 2016";
		$mailer_name = 'Welcome';

		$data['loginURL'] = SHIKSHA_HOME.'/mba/course/post-graduate-diploma-management-indus-business-academy/121136';
		$content = $this->load->view('systemMailer/IBA/IBAMailer',$data,TRUE);
		$this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$content,"html");		    	

		if(!empty($userData['mobile'])){

			$message = 'Get the Best ROI with Excellent Placements. Know all about PGDM 2016 at one of the Top Ranked B-Schools. Visit http://bit.ly/1S1EXaH or call 9212335443.';

			$this->load->library('alerts_client');
			$this->alerts_client->addSmsQueueRecord("12", $userData['mobile'], $message, $userData['userId'], 0, 'user_defined');
		}
    }

    /* Function to get the encoded active urls(i.e. auto login url)
     * @Params: $email :  Email Id of registered user
     *			$url: URl on which we have to throw user at
     * @return: encoded login url and unsubscribe url
     */
    private function _getActiveURLByEmail($email, $url){
    	mail('naveen.bhola@shiksha.com'.'Unused function Called','Function : _getActiveURLByEmail in systemMailer file');
    	$data = array();

		$this->load->model('user/usermodel');
    	$encodedEmail = $this->usermodel->getEncodedEmail($email);

    	/*Getting auto login URL */
        $url = base64_encode($url);
        $autoLoginURL = SHIKSHA_HOME.'/systemMailer/SystemMailer/autoLogin/email~'.$encodedEmail.'_url~'.$url;
        $data['loginURL'] = $autoLoginURL;

        /*Getting unsubscribe url */
		$url = SHIKSHA_HOME.'/userprofile/edit?unscr=0';
        $url = base64_encode($url);
		$autoLoginURL = SHIKSHA_HOME.'/systemMailer/SystemMailer/autoLogin/email~'.$encodedEmail.'_url~'.$url;
        $data['unsubscibeURL'] = $autoLoginURL;

        return $data;
    }

    /**
     * Sending thank you mailer to user for providing internal feedback
     * @author Romil Goel
     * @date   2016-05-10
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function sendInternalFeedbackThankyouMailer($toEmail,$data = array(),$attachment = array())
    {

		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'InternalFeedbackThankyouMailer';
		$senderName = ' Shiksha Ask & Answer';
		
		$subject = "Thank you for your feedback – We are listening";
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }

    /**
     * Sending internal feedback details to the internal team
     * @author Romil Goel
     * @date   2016-05-10
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function sendInternalFeedbackToTeam($toEmail,$data = array(),$attachment = array())
    {

		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'InternalFeedbackMailerToTeam';
		$senderName = ' Shiksha Ask & Answer';
		
		$subject = "Internal feedback by ".ucwords($data['userDetails']['firstName'])." - ".$data['date'];
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }

    /**
     * sending mail for an answer post to a user who has followed that question
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-05-09
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function answerPostToQuestionFollowed($toEmail,$data = array(),$attachment = array())
    {

		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'AnswerPostToQuestionFollowed';
		$senderName = ' Shiksha Ask & Answer';
		
		$subject = $data['subject'];
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		// $mail_id = (int)$response;
		// if($mail_id <=0){
  //       	return false;
  //       }
		//$data['mailer_name'] = $mailer_name;
		//$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		//$mailProcessor = new SystemMailerProcessor($mailer_name);
		//$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
        $response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$content,"html",$attachment, '', '', '', $senderName, 'unsent');

    }


    /**
     * sending mail for to a user who has followed the discussion regarding new comments posted.
     * Affected table shikshaMailerMis and tMailQueue
     * @author Yamini Bisht
     * @date   2016-05-09
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function commentPostToDiscussionFollowedMailer($toEmail,$data = array(),$attachment = array())
    {

		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'CommentPostToDiscussionFollowedMailer';
		$senderName = ' Shiksha Ask & Answer';
		
		$subject = "New comments on the discussions you follow";
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;

		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);

    }

/**
     * sending mail on tags top contri cron
     * Affected table shikshaMailerMis and tMailQueue
     * @author Ankit Bansal
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function tagsTopContributorsMail($toEmail,$data = array(),$attachment = array())
    {
		$return_mail_id = 'Y'; // for view mail in browser link
		$mail_content = '';
		$mailer_name = 'tagsTopContributorsMail';
		$senderName = ' Shiksha Ask & Answer';

		$subject = $data['mail_subject'];
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, 'sent', $return_mail_id);
		
		$is_attachment = 'N';
		if(!empty($attachment)) {
			$is_attachment = 'Y';
		}
	
		$mail_id = (int)$response;
		if($mail_id <=0){
        	return false;
        }
		$data['mailer_name'] = $mailer_name;
		$data['mail_id'] = $mail_id;
		$data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
		$this->load->model('systemMailer/systemmailermodel');
		$data['userRegisteredInApp'] = $this->systemmailermodel->isUserRegisteredForApp($data['receiverId']);

		$content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
				
		$mailProcessor = new SystemMailerProcessor($mailer_name);
		$mailProcessor->updateMailToQueue($toEmail, $content, $mail_id,'unsent', $this->mailerMisId);
    }

    /**
     * sending mail to users for exam latest update
     * Affected table shikshaMailerMis and tMailQueue
     * @author yamini Bisht
     * @param  array      $data Mailer Data
     * @param string	  $toEmail
     * @return none
     */
    function latestUpdateToUsersMail($toEmail,$data = array(),$attachment = array())
    {
    	$return_mail_id = 'N';
		$mailer_name = 'latestUpdateToUserMail';
		$senderName = 'Shiksha.com Exam Update';
		$fromEmail = 'updates@shiksha.com';

		if($data['totalUpdates']>1){
			$subject = $data['totalUpdates'].' New updates about '.$data['examNameForMailer'];
		}else{
			$subject = 'New update about '.$data['examNameForMailer'];
		}

		$is_attachment = 'N';
        if(!empty($attachment)) {
                $is_attachment = 'Y';
        }
		
		$data['heading'] = $subject;
		$mail_id = (int)$response;
        $data['mailer_name'] = $mailer_name;
        $data['mail_id'] = $mail_id;
        $data['is_attachment'] = $is_attachment;
        $data['leanHeaderFooterV2'] = 1;
        $data['profileUrl']        = SHIKSHA_HOME.'/userprofile/'.$data['userId'];

		$mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
        $this->_addMailToQueue($mailer_name,$toEmail,$fromEmail,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, null, $return_mail_id);
    }

    function sendExamMailerDigest($toEmail,$data = array(),$attachment = array()){
                        $return_mail_id = 'N'; // for view mail in browser link
                        $fromEmail      = 'resources@shiksha.com';
                        $mailer_name    = 'examDigestMail';
                        $senderName     = 'Shiksha.com Exam Digest';
			$subject        = "Everything about ".$data['digestInfo']['examInfo']['examNameForMailer']." you need to know!";
                        $data['mailer_name']      = $mailer_name;                        
                        $data['leanHeaderFooterV2'] = 1;
			$data['digestInfo']['profileUrl'] = SHIKSHA_HOME.'/userprofile/'.$data['digestInfo']['userInfo']['user_id'];
                        $mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);

                        $this->_addMailToQueue($mailer_name,$toEmail,$fromEmail,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, null, $return_mail_id);
    }

    public function sendInstituteDigestByQueue(){
    	$userId = $this->input->post('userId');
    	$instituteId = $this->input->post('instituteId');

    	/*$userId = 1111;
    	$instituteId = 24642;*/

    	if((int)$instituteId <= 0 || (int)$userId <= 0){
    		echo json_encode(array("status" => "success","message" => "Invalid instituteId $instituteId or userId $userId received"));
    		return;
    	}
    	$this->benchmark->mark('queue_start');

    	$userModel = $this->load->model('user/userModel');
    	$this->load->builder("nationalInstitute/InstituteBuilder");
    	$instituteBuilder = new InstituteBuilder();
    	$instituteRepo = $instituteBuilder->getInstituteRepository();

    	$instituteObjs = $instituteRepo->findMultiple(array($instituteId));
    	$instituteObj = $instituteObjs[$instituteId];

    	if(empty($instituteObj) || $instituteObj->getId() == ''){
    		echo json_encode(array("status" => "success","message" => "Blank instituteObj for $instituteId found"));
    		return;
    	}

    	$this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
    	$instituteWiseCourses = $this->instituteDetailLib->getAllCoursesForInstitutes($instituteId);
    	if(empty($instituteWiseCourses['courseIds'])){
    		echo json_encode(array("status" => "success","message" => "Institute with zero courses passed. so skipping"));
    		return;
    	}

    	$mailerName = 'InstituteDigestMailer';
    	$data['leanHeaderFooterV2'] = '1';
    	$data['instituteId'] = $instituteId;
    	$data['userId'] = $userId;
    	$data['instituteObj'] = $instituteObj;
        $data['entityType'] = $instituteObj->getType();
        $data['entityId'] = $instituteId;

    	$userData = $userModel->getUserBasicInfoById($data['userId']);
    	$usergroup = $userData['usergroup'];
    	if(in_array($usergroup,array("enterprise","cms","experts","sums","listingAdmin"))){
    		echo json_encode(array("status" => "success","message" => "Internal userId $userId found, so skipping"));
    		return;
    	}

    	$data['firstName'] = $userData['firstname'];

    	$mailContent = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets',$mailerName,$data);
    	// _p($mailContent);die;

    	$subject = "Get Insights & Details on ".$instituteObj->getName();
    	$senderName = "Shiksha College";
    	$fromEmail = "info@shiksha.com";
    	$toEmail = $userData['email'];
    	$this->_addMailToQueue($mailerName,$toEmail,$fromEmail,$subject,$mailContent,"html",array(),'','','',$senderName,null,'N');

    	// send email
    	$this->benchmark->mark('queue_end');
    	$totalTime = $this->benchmark->elapsed_time('queue_start','queue_end');

    	error_log("\nInstitute Digest Mail sent by By Rabbit MQ. Total execution time : ".$totalTime." Sec. For userId : ".$userId." and for instituteId: ".$instituteId , 3, '/tmp/instituteDigestLog');
    	$returnData = json_encode(array("status" => "success","message" => "Institute digest mail sent for user $userId and for institute $instituteId"));
    	echo $returnData;
    }

    public function sendStreamDigestMailByQueue(){
    	$userId = $this->input->post('userId');
    	$streamId = $this->input->post('streamId');
    	$source = $this->input->post('source');

    	if((int)$streamId <= 0 || (int)$userId <= 0 || (int)$streamId == GOVERNMENT_EXAMS_STREAM){
            // also bypass government exams stream
    		echo json_encode(array("status" => "success","message" => "Invalid streamId $streamId or userId $userId received"));
    		return;
    	}
    	// _p($userId);_p($streamId);die;
    	/*$userId = 11;
    	$streamId = 1;*/
    	if(empty($streamId) || empty($userId)){
    		mail('teamldb@shiksha.com,listingstech@shiksha.com','Error when sending Stream digest mail','Error when sending Stream digest mail encountered blank mandatory params. <br> '.print_r($this->security->xss_clean($_POST),true));
    		$returnData = json_encode(array("status" => "fail","message" => "Stream digest mail not sent for user $userId and for stream $streamId"));
    		// _p($returnData);die;
    		echo $returnData;
    		return;
    	}
    	$userModel = $this->load->model('user/userModel');
    	/*$isStreamDigestSent = $userModel->checkAndUpdateIfStreamDigestSent($userId, $streamId);
    	if(0 && $isStreamDigestSent) {
    		return json_encode(array("status" => "success","message" => "Stream digest mail already sent for user: $userId and for stream : $streamId"));
    	}*/

    	$this->benchmark->mark('queue_start');

    	$mailerName = 'streamDigestMail';
    	$data['leanHeaderFooterV2'] = '1';
    	$data['streamId'] = $streamId;
    	$data['userId'] = $userId;
    	$data['source'] = $source;

    	$userData = $userModel->getUserBasicInfoById($data['userId']);
    	$data['firstName'] = $userData['firstname'];
    	
    	$this->load->builder('ListingBaseBuilder', 'listingBase');
		$listingBaseBuilder = new ListingBaseBuilder();
		$streamRepo = $listingBaseBuilder->getStreamRepository();
		$data['streamName'] = $streamRepo->find($streamId)->getName();

    	$mailContent = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets',$mailerName,$data);

    	$subject = "Get Insights & Details about ".$data['streamName']." Courses & Colleges";
    	$senderName = "Shiksha Stream Digest";
    	$fromEmail = "resources@shiksha.com";
    	$toEmail = $userData['email'];
    	$this->_addMailToQueue($mailerName,$toEmail,$fromEmail,$subject,$mailContent,"html",array(),'','','',$senderName,null,'N');
    	// _p($mailContent);die;
    	// send email
    	$this->benchmark->mark('queue_end');
    	$totalTime = $this->benchmark->elapsed_time('queue_start','queue_end');

    	error_log("\nStream Digest Mail sent by By Rabbit MQ. Total execution time : ".$totalTime." Sec. For userId : ".$userId." and for streamId: ".$streamId , 3, '/tmp/streamDigestLog');
    	$returnData = json_encode(array("status" => "success","message" => "Stream digest mail sent for user $userId and for stream $streamId"));
    	// _p($returnData);die;
    	echo $returnData;
    }

    function sendViewedResponseMail($data = array()) {
        $fromEmail      = 'alerts@shiksha.com';
        $senderName     = 'Shiksha College';
        $subject        = "Get insights & details about the course you viewed | ".$data['courseObj']->getName()." at ".$data['instituteObj']->getName();
        $data['entityId'] = $data['listing_type_id'];
        $data['entityType'] = $data['listing_type'];

        $mailerHtml = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $data['mailer_name'], $data);
        
        $this->_addMailToQueue($data['mailer_name'], $data['userDetails']['email'], $fromEmail, $subject, $mailerHtml, "html", '', '', '', '', $senderName, null, 'N');

        return $mailerHtml;
    }

    function sendRequestToEditAnswerMailer($content=array()){
    	$this->config->load('messageBoard/MessageBoardInternalConfig');
		$reasonsToEdit = $this->config->item("editRequestReasons"); 
		$reasonArr = explode(',', $content['reasonToEdit']);
		foreach ($reasonArr as $key => $value) {
			$reasonTextArr[] = $reasonsToEdit[$value];
		}
        $content['reasonArr'] = $reasonTextArr;
        $fromEmail      = 'alerts@shiksha.com';
        $senderName     = 'Shiksha Q&A';
        $data['reasonTextArr'] = $reasonTextArr;
		$return_mail_id = 'N'; 
        $fromEmail      = 'alerts@shiksha.com';
        $mailer_name    = 'requestToEditMailer';
        $senderName     = 'Shiksha Q&A';
        $subject        = $content['subject'];
        $data['mailer_name']= $mailer_name;                        
        $data['leanHeaderFooterV2'] = '1';
        $data['viewContent'] = $content;
        $data['profileUrl'] = $content['userProfileUrl'];
        $data['userId'] = $content['userId'];
        $mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
        $mailStatus = $this->_addMailToQueue($mailer_name,$content['userEmailId'],$fromEmail,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, null, $return_mail_id);
        return $mailStatus;
    }

    function sendContributionMailerToNonCampusRep($content=array()){
        $fromEmail      = 'info@shiksha.com';
        $senderName     = 'Shiksha Ask & Answer';
		$return_mail_id = 'N'; 
		if($content['type'] == 3){
        	$mailer_name    = 'contributionMailerToNonCampusRep3Months';
        }else if($content['type'] == 6){
        	$mailer_name = 'contributionMailerToNonCampusRep6Months';
        }else {
        	return;
        }
        $subject        = $content['subject'];
        $data['mailer_name']= $mailer_name;                        
        $data['leanHeaderFooterV2'] = '1';
        $data['leanHeaderFooterV2'] = 1;
        $data['leanFooterV4'] = '1';
        $data['viewContent'] = $content;
        $userProfileUrl  = getSeoUrl($content['userId'], 'userprofile');
        $data['profileUrl'] = $content['userProfileUrl'];
        $data['userId'] = $content['userId'];
        $mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
        $mailStatus = $this->_addMailToQueue($mailer_name,$content['userEmailId'],$fromEmail,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, null, $return_mail_id);
        return $mailStatus;
    }

    function sendContributionMailerToCampusRep($content=array()){
        $fromEmail      = 'shikshacampusconnect@shiksha.com';
        $senderName     = 'Shiksha Ask & Answer';
		$return_mail_id = 'N'; 
		$mailer_name    = 'contributionMailerToCampusRep';
        $subject        = $content['subject'];
        $data['mailer_name']= $mailer_name;                        
        $data['leanHeaderFooterV2'] = '1';
        $data['leanHeaderFooterV2'] = 1;
        $data['leanFooterV4'] = '1';
        $data['viewContent'] = $content;
        $userProfileUrl  = getSeoUrl($content['userId'], 'userprofile');
        $data['profileUrl'] = $content['userProfileUrl'];
        $data['userId'] = $content['userId'];
        $mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);	
        $mailStatus = $this->_addMailToQueue($mailer_name,$content['userEmailId'],$fromEmail,$subject,$mail_content,"html",$attachment, '', '', '', $senderName, null, $return_mail_id);
        return $mailStatus;
    }

    function sendRegReminderMailer($toEmail,$frequency = '1')
    {
    	$additionParams = array(
    		'mailTrackingAdditionalParams' => array(
    			'utm_campaign' => '_'.$frequency.'Day'
    		)
    	);

    	$mailer_name = 'registrationReminderMailer';
		$return_mail_id = 'N';
		$senderName = 'Shiksha';
		$subject = '';
		
		$data['mailer_name'] = $mailer_name;
		$data['leanHeaderFooterV3'] = 1;

		$mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		$response = $this->_addMailToQueue($mailer_name,$toEmail,ADMIN_EMAIL,$subject,$mail_content,"html",array(), '', '', '', $senderName, 'unsent', $return_mail_id, '', $additionParams);
    }

    function sendMobileVerificationMailer($data){
    	$additionParams = array(
    		'mailTrackingAdditionalParams' => array(
    			'utm_campaign' => '_'.$data['frequency'].'Day'
    		),
    		'subjectReplace' => array(
    			'replaceVariables' => array('#action'),
    			'replaceConstants' => array($data['actionMapping'])
    		)
    	);

    	$mailer_name = 'MobileVerificationMailer';
		$return_mail_id = 'N';
		$senderName = 'Shiksha';
		$subject = '';
		
		$data['mailer_name'] = $mailer_name;
		$data['leanHeaderFooterV3'] = 1;

		$mail_content = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailer_name, $data);
		$response = $this->_addMailToQueue($mailer_name,$data['email'],ADMIN_EMAIL,$subject,$mail_content,"html",array(), '', '', '', $senderName, 'unsent', $return_mail_id, '', $additionParams);
    }

    function getAbroadMailerHeaderLogo($params){
        $autoLoginStr = '<!-- #AutoLogin --><!-- AutoLogin# -->';
        if($params['autoLogin'] === false){
            $autoLoginStr = '';
        }
        return '<table width="200" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
          <tr><td><a href="'.SHIKSHA_STUDYABROAD_HOME.$autoLoginStr.'" target="_blank"><img src="'.IMGURL_SECURE.'/public/images/abroad_mailer_logo.jpg" width="190" height="55" style="vertical-align:top" /></a></td></tr></table>';
    }
}
