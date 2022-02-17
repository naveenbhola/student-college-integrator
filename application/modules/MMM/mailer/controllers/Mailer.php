<?php
//exit();
/**
 * Mailer Class
 *
 * @author
 * @package Mailer
 *
 */

class Mailer extends MX_Controller
{
	var $prodId = 25;
	var $appId = 1;

	function init()
	{
		ini_set('upload_max_filesize','100M');
		ini_set('max_execution_time', '1800000');
		$this->load->helper(array('form', 'url','date','image','html'));
		$this->load->library(array('MailerClient','miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','table','LDB_Client'));
		$this->userStatus = $this->checkUserValidation();
		
		// Get all the user group information and all the tabs related to the user
		if(!empty($this->userStatus[0]['userid'])) {
			
			$mailerModel = $this->load->model('mailer/mailermodel');
			
			if(empty($this->userGroupData)) {
	        	$this->userGroupData = $mailerModel->getUserGroupInfo($this->userStatus[0]['userid']);
	    	}

	    	if(empty($this->userGroupData)) {
                header('Location:/enterprise/Enterprise/disallowedAccess');
                exit;
	    	}

	    	if(empty($this->mailerTabs)) {
	        	$this->mailerTabs = $mailerModel->getAllTabs();
	    	}
    	}
	}

	private function checkLoggedInUser(){
		$userStatus = $this->checkUserValidation();
		return $userStatus;
	}

	private function loadModel(){
		$mailerModel = $this->load->model('mailer/mailermodel');
		return $mailerModel;
	}

	function sendTestMail($templateId, $email, $mailSendingMethod)
	{
		if((empty($templateId)) || (empty($email))) {

			echo "-1";
			error_log("Template id or email id is empty");

		} else {

			$this->load->library('mailer/MailerFactory');
			

			$this->load->model('mailermodel');
			$mailermodelObj = new mailermodel();
			
			/**
			 * Get template from template id
			 */
			$status  = $mailermodelObj->isValidEmailHardbouceCheck($email);
			if($status === true){
					
				$mailerTemplateRepository = MailerFactory::getMailerTemplateRepository();
				$template = $mailerTemplateRepository->getMailerTemplate($templateId);
				$processingParams = array();
				$processingParams['templateId'] = $templateId;
				
				$userId = (int) $mailermodelObj->getUserId($email);
				if($userId) {
					$templateBuilder = MailerFactory::getTemplateBuilder();
					$templateData = $templateBuilder->buildTemplate($template,array($userId),array($userId => array('email' => $email)), $processingParams);
					$mailTemplate = $templateData[$userId]['template'];
					$mailSubject = $templateData[$userId]['subject'];
				}
				else {
					$mailTemplate = $template->getTemplate();
					$mailSubject = $template->getSubject();
				}
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= 'From: <info@shiksha.com>' . "\r\n";

				$mailTemplate = parseUrlFromContent($mailTemplate);

				if($mailSendingMethod == 'shiksha') {
					//$val=mail($email,$mailSubject,$mailTemplate,$headers, "-finfo@shiksha.com");
					//error_log('MAHAK'.$val);
					$val = false;
					$mailermodelObj->saveTestMail($userId, $mailSubject, $mailTemplate,$email,$val);
				} else {
					$mailermodelObj->saveTestMailByAmazon($userId, $mailSubject, $mailTemplate, $email);
				}

			} else {
				echo "-2";
				error_log("invalid email");
			}
		}

	}

	function SpamScore($templateId, $score)
	{
		$this->load->model('mailermodel');
		$mailermodelObj = new mailermodel();
		$testMailData = $mailermodelObj->saveSpamScore($templateId,$score);
	}

	function cmsUserValidation()
	{
            
                if($this->userStatus) {
			$validity = $this->userStatus;
                } else {
			$validity = $this->checkUserValidation();
                }

		//$validity = $this->checkUserValidation();
		global $logged;
		global $userid;
		global $usergroup;
		$thisUrl = $_SERVER['REQUEST_URI'];
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		} else {
			$logged = "Yes";
			$userid = $validity[0]['userid'];
			$usergroup = $validity[0]['usergroup'];

			$mailerModel = $this->load->model('mailer/mailermodel');
			
			if(empty($this->userGroupData)) {
	        	$this->userGroupData = $mailerModel->getUserGroupInfo($userid);
	    	}

			if(empty($this->userGroupData)) {
				if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser") {
					header("location:/enterprise/Enterprise/migrateUser");
					exit;
				}
				if( !(($usergroup == "cms")) ){
					header("location:/enterprise/Enterprise/unauthorizedEnt");
					exit();
				}
			}
		}
		/* $this->load->library('enterprise_client');
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']); */
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));
		$returnArr['userid']=$userid;
		$returnArr['usergroup']=$usergroup;
		$returnArr['logged'] = $logged;
		$returnArr['thisUrl'] = $thisUrl;
		$returnArr['validity'] = $validity;
		$returnArr['headerTabs'] = $headerTabs;
		$returnArr['myProducts'] = $myProductDetails;
		return $returnArr;
	}

	function RunCron($dodo)
	{
		ini_set('max_execution_time', '1800');
		$this->load->helper(array('form', 'url','date','image','html'));
		$this->load->library(array('MailerClient','miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','table'));
		$objmailerClient = new MailerClient;
		$response = array();
		$response['resultSet'] = $objmailerClient->runCronMailer("1");
	}

	function SmsOldTemplate($prodId)
	{
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$this->init();
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userid;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;
		$cmsPageArr['templateType'] = "sms";
		$response = array();
		$objmailerClient = new MailerClient;
		$response['resultSet'] = $objmailerClient->getAllSmsTemplates($this->appId,$userid,$usergroup);
		$response['countresult'] = count($response['resultSet']);
		$cmsPageArr['response'] = $response;
		$this->load->view('mailer/mailer_homepage',$cmsPageArr);
	}

	// Mailer Home Page View Load
	function index($prodId)
	{
		$cmsUserInfo = $this->cmsUserValidation();
		$userid      = $cmsUserInfo['userid'];
		$usergroup   = $cmsUserInfo['usergroup'];
		$thisUrl     = $cmsUserInfo['thisUrl'];
		$validity    = $cmsUserInfo['validity'];
		$this->init();

		$userGroupData = $this->userGroupData;
		$groupId       = $userGroupData['group_id'];
		$adminType     = $userGroupData['user_type'];

		$cmsPageArr                 = array();
		$cmsPageArr['userid']       = $userid;
		$cmsPageArr['usergroup']    = $usergroup;
		$cmsPageArr['thisUrl']      = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs']   =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts']   = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId']       = $this->prodId;
		$cmsPageArr['templateType'] = "mail";
		$cmsPageArr['allAdminData'] = $this->mailermodel->getAllAdminData();
		
		$response                = array();
		$objmailerClient         = new MailerClient;
		$response['resultSet']   = $objmailerClient->getAllTemplates($this->appId,$userid,$usergroup,"no", $groupId, $adminType);
		$response['countresult'] = count($response['resultSet']);
		$cmsPageArr['response']  = $response;
		$this->load->view('mailer/mailer_homepage',$cmsPageArr);
	}

	function getAllTemplates()
	{
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$this->init();
		$response = array();
		$objmailerClient = new MailerClient;
		$response['resultSet'] = $objmailerClient->getAllTemplates($this->appId,$userid,$usergroup);
		$response['countresult'] = count($response['resultSet']);
		$this->load->view('mailer/all_templates',$response);
	}

	function EditTemplateSms()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$selectedTmpId = $this->input->post('selectedTmpId',true);
		$selectedTmpType = $this->input->post('templateType',true);
		if($selectedTmpType == ""){
			$selectedTmpType = "sms";
		}
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userid;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;
		$cmsPageArr['cmsUserInfo'] = $cmsUserInfo;
		if ($selectedTmpId != '-1') {
			$cmsPageArr['mode'] = 'edit';
			$objmailerClient = new MailerClient;
			$cmsPageArr['resultSet'] = $objmailerClient->getTemplateInfo($this->appId,$selectedTmpId,$userid,$usergroup);
			$cmsPageArr[0]['htmlTemplate'] = $cmsPageArr['resultSet'][0]['htmlTemplate'];
		} else {
			$cmsPageArr['mode'] = 'new';
			$cmsPageArr['resultSet'] = array();
		}
		$cmsPageArr['templateType'] = $selectedTmpType;
		$this->load->view('mailer/edit_template',$cmsPageArr);
	}

	function EditTemplate()
	{
		$this->init();
		global $newsletterTemplateIdsArray;
		$cmsUserInfo = $this->cmsUserValidation();
		$selectedTmpId = $this->input->post('selectedTmpId',true);
		
		if(in_array($selectedTmpId,$newsletterTemplateIdsArray)) {
			$this->load->model('mailer/mailermodel');
			$mailsToBeSent = $this->mailermodel->getNewsletterMailsToBeSent($selectedTmpId);
			
			if($mailsToBeSent > 0) {
				echo "<script>alert('Newsletter mails to be processed: ".$mailsToBeSent."');location.href = '".SHIKSHA_HOME."/index.php/mailer/Mailer';</script>";
				return;
			}
		}
		
		$selectedTmpType = $this->input->post('templateType',true);
		if($selectedTmpType == ""){
			$selectedTmpType = "mail";
		}
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userid;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;
		$cmsPageArr['cmsUserInfo'] = $cmsUserInfo;

		if ($selectedTmpId > 0) {
			$cmsPageArr['mode'] = 'edit';
			$objmailerClient = new MailerClient;
			$cmsPageArr['resultSet'] = $objmailerClient->getTemplateInfo($this->appId,$selectedTmpId,$userid,$usergroup);
			$cmsPageArr[0]['htmlTemplate'] = $cmsPageArr['resultSet'][0]['htmlTemplate'];
		} else {
			$cmsPageArr['mode'] = 'new';
			$cmsPageArr['resultSet'] = array();
		}

		$cmsPageArr['templateType'] = $selectedTmpType;
		$this->load->view('mailer/edit_template',$cmsPageArr);
	}

	function UpdateForm()
	{
		global $config;
		// $config['global_xss_filtering'] = FALSE;
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		
		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];

		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$request = array();
		$request ['edit_form_mode'] = $this->input->post('edit_form_mode',true);
		$request ['temp_id'] = $this->input->post('temp_id',true);
		
		if($request ['edit_form_mode'] == 'new'){
			if(!empty($request ['temp_id'])){
				return;
			}
		}else if($request ['edit_form_mode'] == 'edit'){
			if(!(is_numeric($request ['temp_id']) && $request ['temp_id'] >0)){
				return;
			}
		}else{
			return;
		}

		$request ['tep_name'] = $this->input->post('temp1_name',true);
		$request ['temp_desc'] = $this->input->getRawRequestVariable('temp_desc');
		$request ['temp_subj'] = $this->input->post('temp_subj',true);
		$request ['templateType'] = $this->input->post('templateType',true);
		$request ['createdBy'] = $userid;

		$temp_html = $this->input->post('temp_html',true);
		
		$temp_html = parseUrlFromContent($temp_html);
		$temp_html = str_replace("https://ieplads.com", "https://www.ieplads.com", $temp_html);

		// add tracking in html
		$temp_html = $this->addTrackingInHtml($temp_html);

		$request ['temp_html'] = $temp_html;

		$objmailerClient = new MailerClient;
		try {
			$cmsPageArr['resultSet'] = $objmailerClient->insertOrUpdateTemplate($this->appId, $request ['temp_id'], $request['tep_name'], $request ['temp_desc'],$request ['temp_subj'],$request ['temp_html'], $request ['createdBy'], $request ['templateType'], $groupId);
		} catch (Exception $e) {
			throw $e;
			error_log_shiksha('Error occoured during Template Saving'.$e,'CMS-Mailer');
		}
		$request ['VariablesKey'] = $objmailerClient->getVariablesKey($this->appId);
		// To Do Fix Refersh Reload Problem here
		if ($cmsPageArr['resultSet'][0]['id']) {
			//echo $cmsPageArr['resultSet'][0]['id'];
			$var_result['result'] = $objmailerClient->getTemplateVariables($this->appId, $cmsPageArr['resultSet'][0]['id'],$userid, $usergroup);
			//print_r($var_result['result']);
			$var_result['mode'] = $request ['edit_form_mode'];
			$var_result['temp_id'] = $cmsPageArr['resultSet'][0]['id'];
			$var_result['VariablesKey'] = $request ['VariablesKey'];
			$var_result['templateType'] = $request ['templateType'];
			$this->load->view('mailer/edit_variable_template',$var_result);
		}
	}

	function addTrackingInHtml($html){
		$html = htmlspecialchars_decode($html);

		$widgetTracker = "<!-- #widgettracker --><!-- widgettracker# -->";
		$tracker = "<!-- #tracker --><!-- tracker# -->";

		$trackerKeys = array($widgetTracker, $tracker);
		$html = str_replace($trackerKeys, '', $html);
		
		$allMatches = array();
		$replaceData = array();
		$matches = array(); $matchesWithRef = array(); $matchesWithoutRef = array();

		$pattern = "/[hH][rR][eE][fF][ ]*=[ ]*'(?![mM][aA][iI][lL][tT][oO]:)([^\']*)\'/";
		preg_match_all($pattern, $html, $matches);
		$matchesWithRef = $matches[0];
		$matchesWithoutRef = $matches[1];

		foreach ($matchesWithoutRef as $key => $value) {
			$value = trim($value);
			if($value != '' && $value != '#') {

				$withRefKey = $matchesWithRef[$key];
				$allMatches[] = $withRefKey;

				$withRefKey = rtrim($withRefKey,"'");
				$withRefKey = rtrim($withRefKey," ");
				$withRefKey = $withRefKey.$widgetTracker."'";
				$replaceData[] = $withRefKey;
			}
		}

		unset($matchesWithRef);
		unset($matchesWithoutRef);
		unset($withRefKey);

		$matches = array();
		$pattern = '/[hH][rR][eE][fF][ ]*=[ ]*"(?![mM][aA][iI][lL][tT][oO]:)([^\"]*)\"/';
		preg_match_all($pattern, $html, $matches);
		$matchesWithRef = $matches[0];
		$matchesWithoutRef = $matches[1];

		foreach ($matchesWithoutRef as $key1 => $value1) {
			$value1 = trim($value1);
			if($value1 != '' && $value1 != '#') {

				$withRefKey = $matchesWithRef[$key1];
				$allMatches[] = $withRefKey;

				$withRefKey = rtrim($withRefKey,'"');
				$withRefKey = rtrim($withRefKey,' ');
				$withRefKey = $withRefKey.$widgetTracker.'"';
				$replaceData[] = $withRefKey;			
			}
		}

		$html = str_replace($allMatches, $replaceData, $html);
        return $html;
	}
	
	function configureNewsletter()
	{
		global $config;
		// $config['global_xss_filtering'] = FALSE;
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		
		$templateId = trim($this->input->post('temp_id'));
		
		$this->load->model('mailer/mailermodel');
		$var_result['newsletterParams'] = $this->mailermodel->getNewsletterParams($templateId);
		
		$this->load->view('mailer/configure_newsletter',$var_result);
	}
	
	function saveConfigureNewsletter()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		
		$templateId = trim($this->input->post('temp_id'));
		$articleIDs = trim($this->input->post('articleIDs'));
		$discussionIDs = trim($this->input->post('discussionIDs'));
		$eventIDs = trim($this->input->post('eventIDs'));
		$include_MPT_tuple = trim($this->input->post('include_MPT_tuple'));

		if($include_MPT_tuple=='on'){
			$include_MPT_tuple = 'no';
		}
		else{
			$include_MPT_tuple = 'yes';
		}
		
		$this->load->model('mailer/mailermodel');
		$this->mailermodel->saveNewsletterParams($templateId,$articleIDs,$discussionIDs,$eventIDs,$include_MPT_tuple);
		
		try {
			$var_result['temp_id'] = $templateId;
			$var_result['temp_op_mode'] = 'edit';
			$var_result['templateType'] = 'mail';

			$sendData = json_encode($var_result);
			error_log($sendData);
			header('location:/mailer/Mailer');

			// header('location:/enterprise/Enterprise/searchUserForListingPost/22/'.base64_encode($sendData).'/N');
			// Load user saved lists

			//$this->load->view('mailer/test_mailer_templates',$var_result);

		} catch (Exception $e) {
			throw $e;
			error_log_shiksha('Error occoured during Variables Saving'.$e,'CMS-Mailer');
		}
	}

	function setTemplateVariables()
	{
		$this->init();
		global $newsletterTemplateIdsArray;
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		
		$temp_name = $this->input->post('temp_name',true);
		$VariablesKey = $this->input->post('VariablesKey',true);
		$var_name = $this->input->post('var_name',true);
		$temp_id = $this->input->post('temp_id',true);
		$temp_op_mode = $this->input->post('temp_op_mode',true);
		$templateType = $this->input->post('templateType',true);
		$result = array();
		$i = 0;
		foreach ($var_name as $var) {
			$result[$i][] = $var;
			if ($VariablesKey[$i] == -1) {
				$result[$i][] = $temp_name[$i];
				$result[$i][] = 'true';
			} else {
				$result[$i][] = $VariablesKey[$i];
				$result[$i][] = 'false';
			}
			$i++;
		}
		$objmailerClient = new MailerClient;
		try {
			$objmailerClient->setTemplateVariables($this->appId, $temp_id, $result,$userid,$usergroup);
			$var_result['temp_id'] = $temp_id;
			$var_result['temp_op_mode'] = $temp_op_mode;
			$var_result['templateType'] = $templateType;

			$sendData = json_encode($var_result);
			error_log($sendData);
			
			if(in_array($temp_id, $newsletterTemplateIdsArray)) {
				$this->load->model('mailer/mailermodel');
				$var_result['newsletterParams'] = $this->mailermodel->getNewsletterParams($temp_id);
				$this->load->view('mailer/configure_newsletter',$var_result);
			}
			else {
				//$this->load->view('mailer/test_mailer_templates',$var_result);
				header('location:/mailer/Mailer');
				// header('location:/enterprise/Enterprise/searchUserForListingPost/22/'.base64_encode($sendData).'/N');
				// Load user saved lists
			}
		} catch (Exception $e) {
			throw $e;
			error_log_shiksha('Error occoured during Variables Saving'.$e,'CMS-Mailer');
		}
	}

	function getClientIdAndSubscriptionId()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		// $var_result['thisUrl'] = $thisUrl;
		// $var_result['validateuser'] = $validity;
		// $var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		// $var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		// $var_result['cmsUserInfo'] = $cmsUserInfo;
		// get hidden values from Test mail page
		$edit_form_mode = $this->input->post('edit_form_mode',true);
		$temp_id = $this->input->post('temp_id',true);
		$var_result['edit_form_mode'] = $edit_form_mode;
		$var_result['temp_id'] = $temp_id;
		$sendData = json_encode($var_result);
		error_log($sendData);
		header('location:/enterprise/Enterprise/searchUserForListingPost/22/'.base64_encode($sendData).'/N');
		// Load user saved lists
	}

	function setVariables_from_home()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		global $newsletterTemplateIdsArray;
		
		$selectedTmpId = $this->input->post('selectedTmpId',true);
		if(in_array($selectedTmpId, $newsletterTemplateIdsArray)) {
			$this->load->model('mailer/mailermodel');
			$mailsToBeSent = $this->mailermodel->getNewsletterMailsToBeSent($selectedTmpId);
			
			if($mailsToBeSent > 0) {
				echo "<script>alert('Newsletter mails to be processed: ".$mailsToBeSent."');location.href = '".SHIKSHA_HOME."/index.php/mailer/Mailer';</script>";
				return;
			}
		}
		
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$var_result['templateType'] = $this->input->post('templateType',true);
		$objmailerClient = new MailerClient;
		$var_result['result'] = $objmailerClient->getTemplateVariables($this->appId,$selectedTmpId,$userid, $usergroup);
		$var_result['mode'] = 'edit';
		$var_result['temp_id'] = $selectedTmpId;
		$VariablesKey = $objmailerClient->getVariablesKey($this->appId);
		$var_result['VariablesKey'] = $VariablesKey;

		// add tracking to templete
		$this->_addTrackingToTemplete($selectedTmpId, $objmailerClient);
		$this->load->view('mailer/edit_variable_template',$var_result);
	}

	private function _addTrackingToTemplete($templateId, $objmailerClient){
		if($templateId <=0){
			return;
		}

		if(!is_object($objmailerClient)){
			$objmailerClient = new MailerClient;
		}
		$result = $objmailerClient->getTemplateInfo($appID, $templateId);
		$result = $result[0];
		if($result){
			$template  = $this->addTrackingInHtml($result['htmlTemplate']);

			$mailerModel = $this->load->model('mailer/mailermodel');
			$mailerModel->updateTemplateHtml($templateId,$template);
		}
	}

	function dodo()
	{
		echo "<html><body><div style='display:none'><img src='https://172.16.3.226/mailer/Mailer/blank' /></div></body></html>";
	}

	function blank($redirectUrl, $mailerId, $emailId, $templateId = 0, $mailId)
	{
		// hack to allow base64 encoded url as input parameter
		// $redirectUrl = str_replace(' ','+',$redirectUrl);

		if(!empty($mailId) && !is_numeric($mailId)){
			return;
		}

		if(empty($emailId)){
			return;
		}

		$mailData = $redirectUrl.'  MailerId : '.$mailerId.' Email Id : '.$emailId.' Mail Id'.$mailId;

		$this->load->helper('utility');
		$this->load->library(array('MailerClient'));
		$objmailerClient = new MailerClient;
		$redirectUrl = trim(url_base64_decode($redirectUrl));
		
		if(stripos($redirectUrl,'/mailer/Mailer/autoLogin/') !== false){
        	
			list($domain,$data) = explode('/mailer/Mailer/autoLogin/', $redirectUrl);
			$dataArr            = explode("_",$data);
			$finalData          = array();
			for($i = 0 ; $i < count($dataArr); $i++) {
				$tempArr = explode("~",$dataArr[$i]);
				$finalData[$tempArr[0]] = $tempArr[1];
			}
			$redirectUrl = base64_decode($finalData["url"]);
			
        }

        $widgetName = '';
        if (strpos($redirectUrl,'/userprofile/edit?selected_tab=1') != false) {
			$widgetName = 'unsubscribe';
		}

        if($redirectUrl == "1") {
			$redirectUrl = "";
		}

		$objmailerClient->submitOpenMail($this->appId,$mailerId,$emailId, $redirectUrl, $mailId, $widgetName);
		if(trim($redirectUrl) == "") {
			$myFile = "/var/www/html/shiksha/public/images/blankImg.gif";
			$fh = fopen($myFile, 'r');
			$theData = fread($fh, filesize($myFile));
			fclose($fh);
			echo $theData;
			exit();
		}
		$redirectUrl = rtrim($redirectUrl, '/');
		if(strpos($redirectUrl,'/registerAutomaticFeedback') === false) {
			$redirectUrl = $this->_addMailerTrackers($redirectUrl, $mailerId, $templateId);
		}
		header('location:'.$redirectUrl);
		exit();
	}

    function WidgetLogin($redirectUrl, $mailerId, $emailId, $templateId = 0, $mailId){

    	if(empty($emailId)) {
    		//mail('naveen.bhola@shiksha.com,praveen.singhal@99acres.com','Email id empty in Function WidgetLogin','URL : '.$redirectUrl.'\n MailerId : '.$mailerId.'\n templateId : '.$templateId.'\n mailId : '.$mailId);
    		return;
    	}
        $this->load->library('MailerClient');
        $objmailerClient = new MailerClient;
        $redirectUrl = trim(url_base64_decode($redirectUrl));
       
        if((!empty($mailerId)) && (!empty($emailId)) && $redirectUrl == "1") {
        	$redirectUrl = "";
        	$objmailerClient->captureMisData($this->appId,$redirectUrl,"",$mailerId, $emailId, $mailId);
        }else{
        	$this->captureMailWidgetClick($redirectUrl, $mailerId, $emailId, $templateId = 0, $mailId,$objmailerClient);
        }
    }

    function captureMailWidgetClick($redirectUrl, $mailerId, $emailId, $templateId = 0, $mailId, $objmailerClient){
    	if(!is_object($objmailerClient)){
    		$this->load->library('MailerClient');
	        $objmailerClient = new MailerClient;
    	}
	    	
    	if(stripos($redirectUrl,'/mailer/Mailer/autoLogin/') !== false){
			list($domain,$data) = explode('/mailer/Mailer/autoLogin/', $redirectUrl);
			$dataArr            = explode("_",$data);
			$finalData          = array();
			for($i = 0 ; $i < count($dataArr); $i++) {
				$tempArr = explode("~",$dataArr[$i]);
				$finalData[$tempArr[0]] = $tempArr[1];
			}
			$url = base64_decode($finalData["url"]);
        } else if(strpos($redirectUrl,'/registerAutomaticFeedback') !== false) {
			$url = $redirectUrl;
    	} else {
        	list($url,$widgetName) = explode("~",$redirectUrl);
        }

		if((!empty($mailerId)) && (!empty($emailId))) {
			$isMatched = false;
        	if (ENVIRONMENT != 'production'){
        		$urlsToCheck = array('shiksha.com',SHIKSHACLIENTIP);
	            $urlToCheck[] = SHIKSHACLIENTIP;
	            foreach ($urlsToCheck as $urlToMatch) {
			    	if(strrpos($url, $urlToMatch) !== false){
		        		$isMatched = true;
		        		break;
		        	}
			    }
	        }else{
	        	if(strrpos($url, 'shiksha.com') !== false){
	        		$isMatched = true;
	        	}
	        }
        	if($isMatched == true){
        		$result = $objmailerClient->autoLogin($this->appId,$emailId);
		        //list($emailId, $password) = explode("|",$result);
		        setcookie('user',$result,0,'/',COOKIEDOMAIN);

				//// check if url is profilePage or not. if yes than check user is national or abroad user.
				$returnData = $this->processRedirectUrl($url,$result,$widgetName);
				$url = $returnData['redirectUrl'];
				$widgetName = $returnData['widgetName'];
        	}
        	
	        $objmailerClient->captureMisData($this->appId,$redirectUrl,$widgetName,$mailerId, $emailId, $mailId);
    	}
    	if(strpos($url,'http://') !== 0 && strpos($url,'https://') !== 0) {
        	$url = 'https://'.$url;
        }

        $url = $this->_addMailerTrackers($url, $mailerId, $templateId);

        header("Location: ".$url);
        exit;
    }

    function _addMailerTrackers($url, $mailerId = '', $templateId = 0)
    {   
		$urlParams = explode('?',$url);		
		$queryParams = array();
		if($urlParams['1'] != '') {
			for($i=1;$i<count($urlParams);$i++) {
				parse_str($urlParams[$i], $params);
				$queryParams = $queryParams+$params;
			}
		}

		$mailId = intval(str_replace('mailer-', '', $mailerId));
        
        if($queryParams['utm_source'] == ''){
        	$utm_source = 'shiksha';
        	if($mailId == 6548){
            	$utm_source .= '_sa';
        	}
        	$queryParams['utm_source'] = $utm_source;
        }        

        if($queryParams['utm_medium'] == '') {
            $queryParams['utm_medium'] = 'email';
        }

		if($queryParams['utm_campaign'] == '') {			
			global $newsletterTemplateIdsArray;
			global $mailerIdArrayForMEA;
			if($mailId > 0 && $mailId == 6548) {
				$utm_campaign = 'recomailers';
			}
			else if($mailId > 0 && in_array($mailId, array(22093, 22094))) {
				$utm_campaign = 'DetailedRecommendationMailer';
			}
			else if($templateId > 0 && in_array($templateId, $newsletterTemplateIdsArray)) {
				$utm_campaign = 'newsletters';
			}else if(in_array($mailId, $mailerIdArrayForMEA)){
				$utm_campaign = 'MEA';
			}else{
				$utm_campaign = 'clientmailers';
			}
			$queryParams['utm_campaign'] = $utm_campaign;
		}
		
        $url = $urlParams[0].'?'.http_build_query($queryParams);
	    $url = parseUrlFromContent($url);

    	return $url;
    }

	function _select_city_list() {
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
		$this->load->library('MY_sort_associative_array');
		$sorter = new MY_sort_associative_array;
		$finalArray = array();
		foreach ($cityListTier1 as $list) {
			if ($list['stateId'] == '-1') {
				$finalArray['virtualCity'][] = json_decode($categoryClient->getCitiesForVirtualCity(1, $list['cityId']), true);
			} else {
				$finalArray['metroCity'][] = $list;
			}
		}
		$string = '';
		$finalArray['virtualCity'] = $sorter->sort_associative_array($finalArray['virtualCity'], 'city_name');
		foreach ($finalArray['virtualCity'] as $list) {
			foreach ($list as $key) {
				if ($key['virtualCityId'] == $key['city_id']) {
					$string .='<OPTGROUP LABEL="' . $key['city_name'] . '">';
					foreach ($finalArray['virtualCity'] as $list1) {
						$list1 = $sorter->sort_associative_array($list1, 'city_name');
						foreach ($list1 as $key1) {
							if ($key1['virtualCityId'] != $key1['city_id']) {
								if ($key['city_id'] == $key1['virtualCityId']) {
									$string .='<OPTION ddid="' . $key1['city_id'] . '"  title="' . $key1['city_name'] . '" value="' . base64_encode(json_encode(array('cityId' => $key1['city_id'], 'stateId' => $key1['state_id'], 'countryId' => 2))) . '">' . $key1['city_name'] . '</OPTION>';
								}
							}
						}
					}
				}
			}
		}
		$string .='<OPTGROUP LABEL="Metro Cities">';
		$finalArray['metroCity'] = $sorter->sort_associative_array($finalArray['metroCity'], 'cityName');
		foreach ($finalArray['metroCity'] as $key1) {
			$string .='<OPTION ddid="' . $key1['cityId'] . '"  title="' . $key1['cityName'] . '" value="' . base64_encode(json_encode(array('cityId' => $key1['cityId'], 'stateId' => $key1['stateId'], 'countryId' => 2))) . '">' . $key1['cityName'] . '</OPTION>';
		}
		$ldbObj = new LDB_Client();
		$listing_client = new listing_client();
		$country_state_city_list = json_decode($ldbObj->sgetCityStateList(12), true);
		foreach ($country_state_city_list as $list) {
			if ($list['CountryId'] == 2) {
				foreach ($list['stateMap'] as $list2) {
					$string .='<OPTGROUP LABEL="' . $list2['StateName'] . '">';
					foreach ($list2['cityMap'] as $list3) {
						$string .='<OPTION ddid="' . $list3['CityId'] . '" title="' . $list3['CityName'] . '" value="' . base64_encode(json_encode(array('cityId' => $list3['CityId'], 'stateId' => $list2['StateId'], 'countryId' => 2))) . '">' . $list3['CityName'] . '</OPTION>';
					}
				}
			}
		}
		return $string;
	}

	function addNewUserset($userset_type)
	{
		if($userset_type == 'profile_india' || $userset_type == 'activity' || $userset_type == 'exam' ){
			echo Modules::run('userSearchCriteria/searchCriteria/addNewUserset',$userset_type);
			return;
		}
		
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];

		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
			
		$this->load->library(array('LDB_Client','category_list_client','MultipleMarketingPageClient','listingPosting/AbroadCommonLib'));
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new \LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$categoryClient = new Category_list_client();
		$abroadCommonLib = new AbroadCommonLib();
			
		if($userset_type == 'profile_abroad'){
			$catSubcatList['popular'] = $abroadCommonLib->getAbroadMainLDBCourses();
			$catSubcatList['category'] = $categoryClient->getCategoryList(1,1,'newStudyAbroad');
			$var_result['destinationCountryList'] = array($locationRepository->getAbroadCountries());
			//$budgetFieldValueSource = new \registration\libraries\FieldValueSources\Budget;
			//$var_result['budgetValues'] = $budgetFieldValueSource->getValues();
		
		}
		else {
			$catSubcatList = $categoryClient->getCatSubcatList(1,"1");
		}
			
		error_log("####categoryList".print_r($catSubcatList,true));
		$subCategories = array();
		foreach($catSubcatList as $value){
			foreach($value['subcategories'] as $value2){
				$subCategories[] = $value2['catId'];
			}
		}
			
		$marketingPageClient = MultipleMarketingPageClient::getInstance();
		$courses = json_decode($marketingPageClient->getTestPrepCoursesListForApage(1,0,'testpreppage','complete_list'),true);
		$exams = array();
		foreach($courses['courses_list'] as $courseList) {
			foreach($courseList as $course) {
				$exams[$course['title']][$course['child']['blogId']] = $course['child']['acronym'];
			}
		}
			
		$courseList = $categoryClient->getSubCategoryCourses(implode(",",$subCategories), True);
		$catSubcatCourseList = array();
		foreach($catSubcatList as $key=>$value){
			$catSubcatCourseList[$key] = $value;
			foreach($value['subcategories'] as $value2){
				$catSubcatCourseList[$key]['subcategories'][$value2['catId']]['courses'] =  $courseList[$value2['catId']];
			}
			if ($key == 14) {
				$catSubcatCourseList[$key]['exams'] = $exams;
			}
		}
		$var_result['catSubcatCourseList'] = $catSubcatCourseList;
		error_log("####catSubcatCourseList".print_r($catSubcatCourseList,true));
		
		$cityListTier1 = $categoryClient->getCitiesInTier($this->appId, 1, 2);
		$cityListTier2 = $categoryClient->getCitiesInTier($this->appId, 2, 2);
		$var_result['cityList'] = array_merge($cityListTier1, $cityListTier2);
		$var_result['cityList_tier1'] = $cityListTier1;
		$var_result['cityList_tier2'] = $cityListTier2;
		$var_result['country_list'] = $categoryClient->getCountries($this->appId);
		$var_result['regions'] = json_decode($categoryClient->getCountriesWithRegions($this->appId), true);
		$ldbObj = new LDB_client();
		$var_result['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList($this->appId), true);
		$var_result['select_city_list'] = $this->_select_city_list();
		$var_result['userset_type'] = $userset_type;
			
		error_log("####var_result".print_r($var_result,true));
		$this->load->view('mailer/addNewUserset_template',$var_result);
	}

	function insertUserset()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];

		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];
		$adminType = $userGroupData['user_type'];

		$usersetname = $this->input->post('usersetname',true);
		$usersettype = $this->input->post('usersettype',true);
		unset($_POST["_"]);
		$_POST['countFlag'] = false;
		$criteriaJSON = json_encode($_POST);

		$this->load->model('mailermodel');
		$mailermodelObj = new mailermodel();
		$usersetId = $mailermodelObj->saveUserSearchCriteria($usersetname, $criteriaJSON, $usersettype, $userid, $groupId);
		echo $usersetId;
	}

	function SelectUserList()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		global $newsletterTemplateIdsArray;
		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];
		$adminType = $userGroupData['user_type'];

		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];

		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$var_result['newsletterTemplateIdsArray'] = $newsletterTemplateIdsArray;

		$extraInfoArray = urldecode($this->input->post('extraInfoArray',true));

		$extraInfoArray = base64_decode($extraInfoArray); // {"userid":"31","usergroup":"cms","prodId":25,"edit_form_mode":"edit","temp_id":"29"}
		$extraInfoArray = json_decode($extraInfoArray,true);

		$var_result['edit_form_mode'] = $extraInfoArray['edit_form_mode'];
		$var_result['temp_id'] = $extraInfoArray['temp_id'];

		$subscriptionId = $this->input->post('selectedSubs',true);
		$sumsData['subscriptionId'] = $subscriptionId;
		$sumsData['clientUser'] = $this->input->post('clientUser',true);
		$selectedUserId = $this->input->post('selectedUserId',true);

		if ($selectedUserId != 166660) {
			$this->load->library('Subscription_client');
			$objSubs = new Subscription_client();
			$subDetails=$objSubs->getSubscriptionDetails($this->appId,$subscriptionId);
			$sumsData['BaseProdRemainingQuantity'] = $subDetails[0]['BaseProdRemainingQuantity'];
			$sumsData['BaseProdCategory'] = $subDetails[0]['BaseProdCategory'];
		}

		$objmailerClient = new MailerClient;
		$templateInfo = $objmailerClient->getTemplateInfo($this->appId,$var_result['temp_id'],$userid,$usergroup);
		$templateInfo[0]['htmlTemplate'] = $templateInfo['resultSet'][0]['htmlTemplate'];
		$templateType = $templateInfo[0]['templateType'];
		$var_result['templateType'] = $templateType;
		error_log("CDE ".$sumsData['BaseProdCategory']);
		error_log("CDE ".$templateType);
		preg_match('/'.$templateType.'/',strtolower($sumsData['BaseProdCategory']), $matches, PREG_OFFSET_CAPTURE);
		error_log("CDE ".print_r($matches,true));
		if(count($matches) <= 0 && $selectedUserId != 166660) {
			echo "<script>alert('Oops! You Choose Wrong Client/Subscription');history.go(-2);</script>";
		}
		$var_result['sumsData'] = base64_encode(json_encode($sumsData));

		$this->load->model('mailermodel');
		$mailermodelObj = new mailermodel();
		$var_result['usersets'] = $mailermodelObj->getAllUserSearchCriteria($userid, $groupId, $adminType);
		$this->load->view('mailer/SelectUser_template',$var_result);
	}

	function handle_userlist(){	
		ini_set('memory_limit','4048M');
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];

		global $newsletterTemplateIdsArray;
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$var_result['newsletterTemplateIdsArray'] = $newsletterTemplateIdsArray;

		$var_result['edit_form_mode'] =  $this->input->post('edit_form_mode',true);
		$sumsData = $this->input->post('sums_data',true);
		$var_result['sumsData'] = $sumsData;
		$templateId = $this->input->post('temp_id',true);
		$var_result['temp_id'] = $templateId;
		$templateType = $this->input->post('templateType',true);
		$var_result['templateType'] = $templateType;
		$downloadCheck = $this->input->post('download_check',true);
		$user_count = $this->input->post('user_count',true);
		$error = true;

		if ($_REQUEST['user_list_template']=='use_userset') {

			$userSearchCriteria = $this->input->post('userset',true);
			$inc_exc_array = array();
			foreach ($userSearchCriteria as $inc_exc_key => $inc_exc_data) {
				$and_array = array();
				foreach ($inc_exc_data as $and_key => $and_data) {
					$and_data = $this->remove_array_empty_values($and_data);
					$union = join('OR', array_unique($and_data));
					if (!empty($union)) {
						$and_array[$and_key] = $union;
					}
				}
				$intersection = join('AND', array_unique($and_array));
				if (!empty($intersection)) {
					$inc_exc_array[$inc_exc_key] = $intersection;
				}
			}
			$finalUserSeachCriteria = join('EXCLUDE', $inc_exc_array);
			if($user_count > 0) {
				$numUsers = $user_count;
			} else {
				$this->load->library('mailer/MailerFactory');
				error_reporting(E_FATAL);
				$mailerCriteriaEvaluatorService = MailerFactory::getMailerCriteriaEvaluatorService();
				$response = $mailerCriteriaEvaluatorService->evaluateCriteria($finalUserSeachCriteria,false);
				$numUsers = count($response);
			}

			if (!empty($numUsers)) {
				$var_result['criteria'] = $finalUserSeachCriteria;
				$var_result['numUsers'] = $numUsers;
				$this->load->view('mailer/test_mailer_templates',$var_result);
				//$this->load->view('mailer/Edit_Userset_template',$var_result);
				$error = false;
			}
			else {
				$var_result['empty_array_error'] = 'TRUE_SET';
			}
		}
		else if(isset($_FILES['c_csv'])&&($_REQUEST['user_list_template']=='upload_csv')) {
			$csv_array = array();
			$skipCheck = FALSE;
			$cvs_array = $this->buildCVSArray($_FILES['c_csv']['tmp_name']);
		

			if(count($cvs_array['email']) > 25000){
				$error = true;
				$var_result['email_size_error'] = 'EMAIL_COUNT_EXCEEDS';
				$skipCheck = True;
			}

			if(!$skipCheck){

				$invalidCSVIndex = $this->findInvalidIndex($cvs_array);	

				if($downloadCheck == 0){
					$invalidEmailIds = $this->filterCSV($cvs_array,'invalid',$invalidCSVIndex);
				}
			
				if($downloadCheck == 1){
					$cvs_array = $this->filterCSV($cvs_array,'valid',$invalidCSVIndex);
				}				

				if($downloadCheck == 0 && count($invalidEmailIds)>0){
					$this->downloadCSV($invalidEmailIds);
				
				} else {

					$numUsers = count($cvs_array['email']);
					if (!empty($numUsers)) {
						
						$objmailerClient = new MailerClient;
						$skipEmailValidation = TRUE;
						$result = $objmailerClient->checkTemplateCsv($this->appId,$templateId,$cvs_array,$userid,$usergroup, $skipEmailValidation);

						$var_result['criteria'] = $finalUserSeachCriteria;
						$var_result['numUsers'] = $numUsers;
						$error = false;
					}

					if($result[0] == '-1') {
						$var_result['template_validation_error'] = 'TRUE_TEMPLATE';
					}
					else if($result[0] == '-2') {
						$var_result['email_validation_error'] = 'TRUE_EMAIL';
					}
					else if ($result[0]['isActive'] == 'true') {
						$var_result['list_id'] = $result[0]['id'];
						$this->load->view('mailer/test_mailer_templates',$var_result);
						//$this->load->view('mailer/Edit_Userset_template',$var_result);
						$error = false;
					}
				}
			}
		}

		if ($error) {
			$var_result['error'] = 'TRUE';
			$this->load->model('mailermodel');
			$mailermodelObj = new mailermodel();
			$var_result['usersets'] = $mailermodelObj->getAllUserSearchCriteria();
			$this->load->view('mailer/SelectUser_template',$var_result);
		}
	}

	function scheduleMailer($mailerId){	
		ini_set('memory_limit', -1);
		$this->init();

		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];

		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
	
		if(!empty($mailerId)) {

			$mailerDetails = $this->mailermodel->getMailerDetailsByMailerId($mailerId, 'draft');
			if(empty($mailerDetails)) {
				header('location: '.SHIKSHA_HOME.'/mailer/Mailer/manageMails');
				exit();
			}

			$var_result['numUsers'] = $mailerDetails['totalMailsToBeSent'];
			$var_result['totalUsersInCriteria'] = $mailerDetails['totalUsersInCriteria'];
			$var_result['mailerDetails'] = $mailerDetails;
			$var_result['sumsData'] = $mailerDetails['subscriptionDetails'];
			$var_result['temp_id'] = $mailerDetails['templateId'];

		} else {

			$var_result['edit_form_mode'] =  $this->input->post('edit_form_mode',true);
			$sumsData = $this->input->post('sums_data',true);
			$var_result['sumsData'] = $sumsData;
			$templateId = $this->input->post('temp_id',true);
			$var_result['temp_id'] = $templateId;
			$templateType = $this->input->post('templateType',true);
			$var_result['templateType'] = $templateType;
			$downloadCheck = $this->input->post('download_check',true);
			$criteria = $this->input->post('criteria',true);
			$var_result['criteria'] = $criteria;
			$numUsers = $this->input->post('numUsers',true);
			$var_result['numUsers'] = $numUsers;
			$var_result['totalUsersInCriteria'] = $numUsers;
			$list_id = $this->input->post('list_id',true);
			$var_result['list_id'] = $list_id;

		}

		if (!empty($var_result['numUsers'])) {
			$this->load->view('mailer/Edit_Userset_template',$var_result);
		}

	}

	function validateEmail($emailID){
		
			$pattern = "/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/";

	/*$pattern = "/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$";*/
    	
    	return preg_match($pattern, $emailID) ? true : false;
	}

	function findInvalidIndex($cvs_array){

		foreach ($cvs_array as $key => $value) {
			if($key == 'email'){
				foreach ($value as $index => $emailID) {
					$validCheck = $this->validateEmail($emailID);
					if(!$validCheck){
						$invalidCSVIndex[] = $index;
					}
				}
			}		
		}

		return $invalidCSVIndex;
	}


	function filterCSV($cvs_array,$csvType,$invalidCSVIndex){

		if($csvType == 'invalid'){
			foreach ($invalidCSVIndex as $index) {			
					$invalidEmailIds[] = $cvs_array['email'][$index];
			}

			return $invalidEmailIds;

		}else{

			foreach ($invalidCSVIndex as $index) {
				foreach ($cvs_array as $key => $value) {
					unset($cvs_array[$key][$index]);
				}
			}
			
			foreach ($cvs_array as $key => $value) {
				$cvs_array[$key] = array_values($cvs_array[$key]);
			}

			/*$cvs_array['email'] = array_values($cvs_array['email']);*/
			
			foreach ($cvs_array as $key => $value) {
                 $cvs_array[$key] = array_values($cvs_array[$key]);
            }

			return $cvs_array;
		}
	}

	function downloadCSV($data,$type){

		$mime = 'text/csv';

		if ($type == 'MisReport'){
			$filename = 'mailerReport.csv';
			$csvSTring = $data;
		}

		else{
			$csvSTring = "";
			if(!empty($data)){
				$csvSTring = implode(',', $data);
			}
			$filename = 'InvalidEmailIds.csv';
		}

		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($csvSTring));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($csvSTring));
        }
		 echo $csvSTring;
		return;
	}

	function UpdateUserList()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];

		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;

		$var_result['mode'] = $this->input->post('mode',true);
		$var_result['temp_id'] = $this->input->post('temp_id',true);
		$var_result['list_id'] = $this->input->post('list_id',true);
		$var_result['mails_limit_text'] = $this->input->post('mails_limit_text',true);
		$var_result['mails_limit'] = $this->input->post('mails_limit',true);
		$var_result['export_csv'] = $this->input->post('export_csv',true);
		$var_result['all_Lists'] = $this->input->post('all_Lists',true);
		$var_result['sumsData'] = $this->input->post('sumsData',true);

		if (!empty($var_result['mails_limit_text']) && is_numeric($var_result['mails_limit_text'])) {
			$numEmail = $var_result['mails_limit_text'];
		} else if (!empty($var_result['mails_limit']) && ($var_result['mails_limit'] == '-1')) {
			$numEmail = $var_result['mails_limit'];
		}

		if (empty($var_result['all_Lists'])){
			$list = array();
		} else {
			$list = $var_result['all_Lists'];
		}

		$objmailerClient = new MailerClient;
		$var_result['result'] = $objmailerClient->submitList($this->appId,$var_result['list_id'],$list,$numEmail,$userid,$usergroup);
		if ($var_result['result'][0]['isActive'] == 'false') {
			$var_result['new_list_id'] = $var_result['result'][0]['id'];
			$var_result['new_list_name'] = $var_result['result'][0]['name'];
			$var_result['new_list_desc'] = $var_result['result'][0]['description'];
			$var_result['new_usersArr'] = $var_result['result'][0]['usersArr'];
			$var_result['numUsers'] = $var_result['result'][0]['numUsers'];
			$this->load->view('mailer/update_New_List_template',$var_result);
		} else {
			$this->load->view('mailer/Summary_List_template',$var_result);
		}
	}

	function update_New_List_template()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];

		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;

		$listId = $this->input->post('list_id',true);
		$name = $this->input->post('temp1_name',true);
		$desc = $this->input->post('temp_desc',true);
		$var_result['mode'] = $this->input->post('mode',true);
		$var_result['temp_id'] = $this->input->post('temp_id',true);
		$var_result['list_id'] = $listId;
		$var_result['sumsData'] = $this->input->post('sumsData',true);
		$objmailerClient = new MailerClient;
		$var_result['result'] = $objmailerClient->updateListInfo($this->appId, $listId, $name, $desc, $userid, $usergroup);
		$this->load->view('mailer/Summary_List_template',$var_result);
	}

	function Summary_List_template()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		
		$mode = $this->input->post('edit_form_mode',true);
		$sumsData = $this->input->post('sumsData',true);
		$temp_id = $this->input->post('temp_id',true);
		$list_id = $this->input->post('list_id',true);
		$criteria = $this->input->post('criteria',true);

		$mails_limit_text = $this->input->post('mails_limit_text',true);
		$mails_limit = $this->input->post('mails_limit',true);

		if (!empty($mails_limit_text) && is_numeric($mails_limit_text)) {
			$numUsers = intval($mails_limit_text);
		}
		else {
			$numUsers = $mails_limit;
		}
		
		$mailSchedule = $this->input->post('mailSchedule',true);
		$trans_start_date = $this->input->post('trans_start_date',true);
		$mailScheduleHours = $this->input->post('mailScheule_hours',true);
		$mailScheduleMinutes = $this->input->post('mailScheule_minutes',true);
		if($mailSchedule == 'immediately') {
			$trans_start_date = date('Y-m-d H:i:s');
		}
		else {
			$trans_start_date = $trans_start_date." ".$mailScheduleHours.":".$mailScheduleMinutes.":00";
		}
		$temp1_name = $this->input->post('temp1_name',true);
		$sender_name = $this->input->post('sender_name',true);
		$userFeedbackEmail = $this->input->post('userFeedbackEmail',true);
		$mailStatus = $this->input->post('mailStatus',true);
		$totalUsersInCriteria = $this->input->post('totalUsersInCriteria',true);

		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];

		$subject = '';
		if($mailStatus == 'false') {
			$templateDetails = $this->mailermodel->getTestMailData($temp_id);
			$subject = $templateDetails['subject'];
		}

		$objmailerClient = new MailerClient;
		$result = $objmailerClient->saveMailer($this->appId,$temp1_name,$temp_id,$list_id,$trans_start_date,$userFeedbackEmail, $userid, $usergroup,$sumsData, $criteria, $numUsers, $sender_name, $groupId, $mailStatus, $totalUsersInCriteria, $subject);
		error_log("CONSUME RETURN ".print_r($result,true));
		if ( !($result['ERROR'] == 1)) {
			$var_result['trans_start_date'] = $trans_start_date;
			$var_result['temp1_name'] = $temp1_name;
			$var_result['mailStatus'] = $mailStatus;
			$this->load->view('mailer/Summary_mailer_Result_template',$var_result);
		} else {
			$var_result['edit_form_mode'] = $mode;
			$var_result['sumsData'] = $sumsData;
			$var_result['temp_id'] = $temp_id;
			$var_result['list_id'] = $list_id;
			$var_result['criteria'] = $criteria;
			$var_result['numUsers'] = $mails_limit;
			$var_result['error'] = 'TRUE';
			$this->load->view('mailer/Edit_Userset_template',$var_result);
		}
	}
	/*
	 * For Ajax call to test html mail
	 */
	function Test_mail_List($TempId, $email)
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->sendTestMailer($this->appId,$TempId,$email,$userid,$usergroup);
		if ( !empty($result[0]) && is_numeric($result[0])) {
			echo $result[0];
		}
		else if ($TempId == 'null') {
			echo '-1';
		}
	}

	function s_getSearchFormData()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->s_getSearchFormData($this->appId);
		/* Generate Array for Form Start */
		$form_array = array();
		if (count(json_decode($result[0])) > 0 ) {
			$i = 0;
			foreach (json_decode($result[0]) as $value) {
				if (is_object($value)) {
					$option = array();
					foreach ($value as $element) {
						if (is_object($element)) {
							$option[] = array(
							$element->filterValueId  =>
							$element->filterValueName
							);
						}
					}
					$form_array[$value->filterType][$value->filterId][$value->filterName][] = $option;
				}
				$i++;
			}
		}
		/* Generate Array for Form End */
		return $form_array;
	}

	function save_SearchFormDataParams($temp_id,$mode,$userid,$usergroup)
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$var_result['temp_id'] = $temp_id;
		$objmailerClient = new MailerClient;
		$sumsData = $this->input->post('sums_data',true);
		$var_result['sumsData'] = $sumsData;
		if(count($_REQUEST) > 0 ) {
			$sendArr = array();
			error_log("TYUI ".print_r($_REQUEST,true));
			foreach ($_REQUEST as $key=>$value) {
				if (ereg('combo_search',$key)) {
					$sendArr[$key[0]] = $value;
				}
				if (ereg('checkbox_search',$key)) {
					$sendArr[$key[0]] = $value;
				}
				if (ereg('range_search',$key)) {
					if($value[0] != ""){
						$keyArr = explode("_",$key);
						$sendArr[$keyArr[0]][] = array(
							'value'=>$value[0]
						// 'id'=>$key[1]
						);
					}
				}
				if (ereg('date_search',$key)) {
					if($value[0] != ""){
						$keyArr = explode("_",$key);
						$sendArr[$keyArr[0]][] = array(
							'value'=>$value[0]
						// 'id'=>$key[1]
						);
					}
				}
			}
		}
		/* Hack to handle error */
		$flag_empty_array = true;
		foreach ($_REQUEST as $k=>$v) {
			if (ereg('_search',$k)) {
				foreach ($v as $val) {
					if(empty($val)) {
						$flag_empty_array = false;
					} else {
						$flag_empty_array = true;
						break 2;
					}
				}
			}
		}
		if ($flag_empty_array == false) {
			$var_result['empty_form_selection'] = 'TRUE';
			$this->load->view('mailer/Summary_List_template',$var_result);
		} else {
			$result['result']= $objmailerClient->s_submitSearchQuery($this->appId,$sendArr,$userid,$usergroup);
		}
		if ($result['result'][0] != '-1') {
			$list_id = $result['result'][0]['id'];
			$var_result['new_list_id'] = $list_id;
			$var_result['new_list_name'] = $result['result'][0]['name'];
			$var_result['new_list_desc'] = $result['result'][0]['description'];
			$var_result['new_usersArr'] = $result['result'][0]['usersArr'];
			$var_result['numUsers'] = $result['result'][0]['numUsers'];
			$var_result['edit_form_mode'] =  $mode;
			$var_result['temp_id'] = $temp_id;
			$var_result['all_Lists'] = $objmailerClient->getAllLists($this->appId,$userid,$usergroup);
			$var_result['selectedListId'] = $list_id;
			$var_result['List_Detail'] = $objmailerClient->getListInfo($this->appId,$list_id,$userid,$usergroup);
			$this->load->view('mailer/Edit_List_template',$var_result);
		}
		else
		{
			if ($result['result'][0] == '-1'){
				$var_result['error_empty_array'] = 'TRUE';
			}
			$this->load->view('mailer/Summary_List_template',$var_result);
		}
	}

	function buildCVSArray($File)
	{
		$handle = fopen($File, "r");
		$fields = fgetcsv($handle, 1000, ",");
		while($data = fgetcsv($handle, 1000, ",")) {
				$detail[] = $data;			
		}
		$x = 0;
		foreach($fields as $z) {
			foreach($detail as $i) {
				$stock[$z][] = $i[$x];
			}
			$x++;
		}
		return $stock;
	}

	function MisReportDisplay($range = 3)
	{
		// echo "closed for maintenance"; die;
		$this->init();

		$cmsUserInfo = $this->cmsUserValidation();
		
		$action = $this->input->post('clickedOn');
		$userGroupData = $this->userGroupData;

		$groupFilter = $this->input->post('group_choose');
		$timeStart = $this->input->post('timerangeFrom');
		$timeEnd = $this->input->post('timerangeTill');
		$var_result = array();
		$var_result['timeStart'] = $timeStart;
		$var_result['timeEnd'] = $timeEnd;
		$var_result['groupFilter'] = $groupFilter;

		if($timeStart == ""){
			$timeEnd = date('Y-m-d');
			$timeStart = date('Y-m-d',strtotime('-7 days', time()));
			$var_result['timeStart'] = "";
			$var_result['timeEnd'] = "";
		}

		$timeStart   = $timeStart." 00:00:00";
        	$timeEnd     = $timeEnd." 23:59:59";

		$groupId = $userGroupData['group_id'];
		$adminType = $userGroupData['user_type'];

		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		
		$mailerModel = $this->load->model('mailer/mailermodel');

		$mailerIdsArray  = $mailerModel->getMailerIdsToDisplay($groupId,$timeStart,$timeEnd,$groupFilter,$adminType,'true',$userid);

		$mailerIds = array();
		foreach ($mailerIdsArray as  $value) {
			$userIds[] = $value['clientId'];
			$mailerIds[]= $value['id'];
			$mailerDetails[$value['id']] = $value;
			$campaignIds[$value['campaignId']] = $value['campaignId'];
			if($value['parentMailerId'] > 0) {
			}
		}

		$mailerData  = $mailerModel->getMailerReportData($mailerIds);
		$mailerSchedulerModel = $this->load->model('mailer/mailerschedulermodel');
		$campaignDetails = $mailerSchedulerModel->getCampaignDetails($campaignIds);
		$campaignNames = array();
		foreach($campaignDetails as $campaignDetail) {
			$campaignNames[$campaignDetail['id']] = $campaignDetail['name'];
		}

		$childMailerDetails = $mailerSchedulerModel->getChildMailersCount($mailerIds);
		$mailerChildCount = array();
		foreach($childMailerDetails as $childMailerDetail) {
			$mailerChildCount[$childMailerDetail['parentMailerId']]  = $childMailerDetail['count'];
		}

		$this->load->config('mailer/mailerConfig');
		$dripCampaignTypes = $this->config->item('dripCampaignTypes');   

		$userModel = $this->load->model('user/usermodel');
		$clientData = $userModel->getNameByUserId($userIds);
		$clientDataMap = array();
		foreach ($clientData as $key => $data) {
			$clientDataMap[$data['userid']] = $data['firstname'].' '.$data['lastname'];
		}

		$var_result['resultSet'] = array();
		$var_result['mailerChildCount'] = $mailerChildCount;
		if(!empty($mailerData)){
			foreach ($mailerData as $value) {
				 $mailerInfo = array();
				 $clientIds[$value['clientId']] = $value['clientId'];
				 $mailerInfo['clientId'] = $value['clientId'];
				 $mailerInfo['mailerId'] = $value['mailerId'] ;
				 $mailerInfo['mailerName'] =$value['mailerName'] ;
				 $mailerInfo['totalMails'] = $value['totalMails'] ;
				 $mailerInfo['processedMails'] = $value['processedMails'] ;
				 $mailerInfo['sentMails'] = $value['sentMails'] ;
				 $mailerInfo['uniqueMailsOpened'] =$value['uniqueMailsOpened'];
				 $mailerInfo['openRate'] = $value['openRate'] ;
				 $mailerInfo['uniqueMailsClicked'] = $value['uniqueMailsClicked'] ;
				 $mailerInfo['clickRate'] = $value['clickRate'] ;
				 $scheduleDateTime  = $value['scheduledDate'] ;
				 $scheduleDateTime  = explode(" ",$scheduleDateTime) ;
				 $mailerInfo['date'] = $scheduleDateTime[0];
				 $mailerInfo['time'] = $scheduleDateTime[1];
				 $mailerInfo['uniqueUnsubscribeClicked'] = $value['uniqueUnsubscribeClicked'] ;
				 $mailerInfo['unsubscribeRate'] = $value['unsubscribeRate'] ;
				 $mailerInfo['createdBy'] = $value['createdBy'] ;
				 $mailerInfo['totalMailsOpened'] = $value['totalMailsOpened'] ;
				 $mailerInfo['totalMailsClicked'] = $value['totalMailsClicked'] ;
				 $mailerInfo['totalUnsubscribeClicked'] = $value['totalUnsubscribeClicked'] ;

				 if ($value['clientId']){
					$mailerInfo['displayname'] = $clientDataMap[$value['clientId']];
				 }
				 else{
					$mailerInfo['displayname'] = 'MMM';
				 }

				 $mailerInfo['campaignName'] = $campaignNames[$mailerDetails[$value['mailerId']]['campaignId']];
				 $mailerInfo['campaignId'] = $mailerDetails[$value['mailerId']]['campaignId'];
				 $mailerInfo['parentMailerId'] = $mailerDetails[$value['mailerId']]['parentMailerId'];
				 $mailerInfo['dripCampaignType'] = '-';

				 if($mailerInfo['parentMailerId'] > 0) {
					$mailerInfo['dripCampaignType'] = $dripCampaignTypes[$mailerDetails[$value['mailerId']]['dripMailerType']];
					 
				 }
				 $mailerInfo['subject'] = $mailerDetails[$value['mailerId']]['subject'];
				 $mailerInfo['sendername'] = $mailerDetails[$value['mailerId']]['sendername'];

				 $var_result['resultSet'] [] = $mailerInfo;
			 }
		}
		

		// $objmailerClient = new MailerClient;
		// $var_result['resultSet']= $objmailerClient->getMailersList($this->appId,$userid,$usergroup,$range, $groupId, $adminType,$groupFilter,$timeStart,$timeEnd, 'true');

		// $total = 0;
		// $total_sent = 0;
		// $total_open = 0;
		// $total_click = 0;
		// $total_processed = 0;
		// $total_spam = 0;
		// $total_unsubscribe = 0;
		// $mailerIds = array();

		// foreach ($var_result['resultSet'] as $key => $value) {
			
		// 	$total_sent += $value['sent'];
		// 	$total_processed += $value['processed'];
		// 	$total += $value['total'];
		// 	$var_result['resultSet'][$key]['open'] = 0;
		// 	$var_result['resultSet'][$key]['click'] = 0;
		// 	$var_result['resultSet'][$key]['spam'] = 0;
		// 	$var_result['resultSet'][$key]['unsubscribe'] = 0;

		// 	$var_result['resultSet'][$key]['open_rate'] = 0;
		// 	$var_result['resultSet'][$key]['click_rate'] = 0;
		// 	$var_result['resultSet'][$key]['spam_rate'] = 0;
		// 	$var_result['resultSet'][$key]['unsubscribe_rate'] = 0;

		// 	if(!in_array($key, $mailerIds)){
		// 		$mailerIds[] = $key;
		// 	}

		// }

		// if(!empty($mailerIds)){
        
		// 	// $res = $this->getTrackingDetails($groupId,'',$adminType, $groupFilter, $timeStart, $timeEnd, $userid, $mailerIds);
		// 	$res = $this->getTrackingDetails($mailerIds);
		// 	foreach ($res as $key => $value) {

		// 		if(array_key_exists($value['mailerId'], $var_result['resultSet'])){
					
		// 			if($value['trackerId'] == "Open Rate" && $value['count'] > 0){
		// 				$var_result['resultSet'][$value['mailerId']]['open'] = $value['count'];
		// 				$var_result['resultSet'][$value['mailerId']]['open_rate'] = round($value['count'] / $var_result['resultSet'][$value['mailerId']]['sent'] * 100,2);
		// 				$total_open += $value['count'];
		// 			}

		// 			if($value['trackerId'] == "Click Rate" && $value['count'] > 0){
						
		// 				$var_result['resultSet'][$value['mailerId']]['click'] = $value['count'];
		// 				$var_result['resultSet'][$value['mailerId']]['click_rate'] = round($value['count'] / $var_result['resultSet'][$value['mailerId']]['open'] * 100,2);
		// 				$total_click += $value['count'];
		// 			}

		// 			if($value['trackerId'] == "Spam Rate" && $value['count'] > 0){

		// 				$var_result['resultSet'][$value['mailerId']]['spam'] = $value['count'];
		// 				$var_result['resultSet'][$value['mailerId']]['spam_rate'] = round($value['count'] / $var_result['resultSet'][$value['mailerId']]['open'] * 100,2);
		// 				$total_spam += $value['count'];
		// 			}

		// 			if($value['trackerId'] == "Unsubscribe Rate" && $value['count'] > 0){
		// 				$var_result['resultSet'][$value['mailerId']]['unsubscribe'] = $value['count'];
		// 				$var_result['resultSet'][$value['mailerId']]['unsubscribe_rate'] = round($value['count'] / $var_result['resultSet'][$value['mailerId']]['open'] * 100,2);
		// 				$total_unsubscribe += $value['count'];
		// 			}	
		// 		}
		
		// 	}
		// 	unset($res);

		// }
		
		$var_result['countresult'] = count($var_result['resultSet']);
		$var_result['allAdminData'] = $this->mailermodel->getAllAdminData();
		$var_result['group_list'] = $this->mailermodel->getGroupsList();
		$var_result['adminType'] = $adminType;

		// $total_open_rate = round($total_open / $total_sent * 100,2);
		// $total_click_rate = round($total_click / $total_open * 100, 2);
		// $total_spam_rate = round($total_spam / $total_open * 100,2);
		// $total_unsubscribe_rate = round($total_unsubscribe / $total_open * 100,2);

		// $var_result['total_open_rate'] = $total_open_rate;
		// $var_result['total_click_rate'] = $total_click_rate;
		// $var_result['total_spam_rate'] = $total_spam_rate;
		// $var_result['total_unsubscribe_rate'] = $total_unsubscribe_rate;
		// $var_result['total_open'] = $total_open;
		// $var_result['total_sent'] = $total_sent;
		// $var_result['total_spam'] = $total_spam;
		// $var_result['total_click'] = $total_click;
		// $var_result['total'] = $total;
		// $var_result['total_unsubscribe'] = $total_unsubscribe;
		// $var_result['total_processed'] = $total_processed;
		
		if ($action == 'download'){
			// _P($var_result);die;
			$this->downloadMisReport($var_result['resultSet'],$clientIds);
		}
		else
			$this->load->view('mailer/MisReportDisplay',$var_result);
	}


	// function getTrackingDetails($groupId, $trackerId = "", $adminType, $groupFilter,$timeStart,$timeEnd, $userId, $mailerIds=array()){
	function getTrackingDetails($mailerIds=array()){

        if(empty($mailerIds)){
        	return;
        }
		$this->load->model('mailer/mailermodel');
		// $whereQuery = "";
		// $queryCmd = "";

    	// if($adminType == 'group_admin') {
     //        $whereQuery .= " and b.group_id = ".$groupId;
     //    } else if ($adminType == 'normal_admin') {
     //        $whereQuery .= " and b.userId = ".$userId;
     //    } else if($adminType == 'super_admin' && $groupFilter != ""){
     //        $whereQuery .= " and b.group_id = ".$groupFilter;
     //    }

     //    $timeStart = $timeStart." 00:00:00";
     //    $timeEnd = $timeEnd." 23:59:59";

        //(DATE_SUB(CURDATE(), INTERVAL 12 WEEK)) OLD
		// if($trackerId != ""){
  //           $queryCmd = "select a.*,count(*) as count from mailerMis a,mailer b where a.mailerId = b.id 
  //           				and a.trackerId = '$trackerId' ".$whereQuery." and b.time >= ? 
  //           				and b.time <= ? group by a.mailerId,a.trackerId order by a.mailerId";
  //       }else {
  //           $queryCmd = "select a.*,count(*) as count from mailer.mailerMis a,mailer.mailer b where a.mailerId = b.id ".$whereQuery. 
  //           			" and b.time >=? and b.time <= ? group by a.mailerId,a.trackerId order by a.mailerId";
  //       }

        

        // $result = $this->mailermodel->getTrackingDetails($queryCmd, $timeStart, $timeEnd);
        $result = $this->mailermodel->getTrackingDetails($mailerIds);

		$openRate = 0;
		$clickRate = 0;
        $total_click_rate = 0;
        $msgArray = array();
        $current = 0;
        $previous = 0;
        $count = 0;
        $spam  = 0;
        $unsubscribe = 0;
    	$temp = array();
    	
    	
        foreach ($result as $row){

			$prev = $current;
        	$current = $row->mailerId;
        	
        	if($current != $prev && $count != 0){
        		
        		$matchCount++;

	    		$temp['id'] = '-2';
	            $temp['mailerId'] = $prev;
                $temp['trackerId'] = "Open Rate";
                $temp['count'] = $openRate;
                array_push($msgArray,$temp);

				if($clickRate) {	
	                $temp['id'] = '-3';
	                $temp['mailerId'] = $prev;
	                $temp['trackerId'] = "Click Rate";
	                $temp['count'] = $total_click_rate;

					array_push($msgArray,$temp);
				}
		   
                if($spam){
                	$temp['id'] = '-4';
	                $temp['mailerId'] = $prev;
	                $temp['trackerId'] = "Spam Rate";
	                $temp['count'] = $spam;
					array_push($msgArray,$temp);	
                }
                
                if($unsubscribe){
                	$temp['id'] = '-4';
	                $temp['mailerId'] = $prev;
	                $temp['trackerId'] = "Unsubscribe Rate";
	                $temp['count'] = $unsubscribe;
					array_push($msgArray,$temp);	
                }
				
				$openRate = 0;
				$clickRate = 0;
        		$total_click_rate = 0;		
        		$spam = 0;
        		$unsubscribe = 0;
    			$prev = $current;       		
			}

       		$count++;
			
			if(!$row->trackerId) {
				$openRate = $row->count;
			}

			else if($row->trackerId && strpos($row->trackerId,'mailReportSpam=1') !== FALSE){
				$spam = $spam + $row->count;
			}

			else if($row->trackerId && strpos($row->trackerId,'encodedUnsubscribeURL') !== FALSE){
				$unsubscribe = $unsubscribe + $row->count;
			}
			else {
				 $total_click_rate = $total_click_rate + $row->count;
				 $clickRate = $row->count;
			}
        }
		
	    $temp['id'] = '-2';
        $temp['mailerId'] = $current;
        $temp['trackerId'] = "Open Rate";
        $temp['count'] = $openRate;
        array_push($msgArray,$temp);

        if($clickRate) {	
            $temp['id'] = '-3';
            $temp['mailerId'] = $current;
            $temp['trackerId'] = "Click Rate";
            $temp['count'] = $total_click_rate;

			array_push($msgArray,$temp);
		}

        if($spam){
	        $temp['id'] = '-4';
	        $temp['mailerId'] = $prev;
	        $temp['trackerId'] = "Spam Rate";
	        $temp['count'] = $spam;
			array_push($msgArray,$temp);
		}

		if($unsubscribe){
			$temp['id'] = '-4';
   		 	$temp['mailerId'] = $prev;
   		 	$temp['trackerId'] = "Unsubscribe Rate";
   		 	$temp['count'] = $unsubscribe;
			array_push($msgArray,$temp);
		}
		
		return $msgArray;
	}
	function getMailerTrackingUrls($id)
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$var_result['resultSet']= $objmailerClient->getMailerTrackingUrls($this->appId,$userid,$usergroup,$id,'','','');
		$var_result['countresult'] = count($var_result['resultSet']);
		$var_result['id'] = $id;
		$this->load->view('mailer/getMailerTrackingUrls',$var_result);
	}

	function MailerTrackingUrlsformsubmit()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		$objmailerClient = new MailerClient;
		$mailerId = $this->input->post('id',true);
                //echo $mailerId;
		$startTime = $this->input->post('trans_start_date',true);
		$endTime = $this->input->post('trans_end_date',true);
                //echo $startTime."____".$endTime;
		$var_result['resultSet']= $objmailerClient->getMailerTrackingUrls($this->appId,$userid,$usergroup,$mailerId,'',$startTime,$endTime);
                //_P($var_result['resultSet']);
                $var_result['startTime'] = $startTime;
                $var_result['endTime'] = $endTime; 
		$var_result['countresult'] = count($var_result['resultSet']);
		$var_result['id'] = $this->input->post('id',true);;
		$this->load->view('mailer/getMailerTrackingUrls',$var_result);
	}

	function poll_form_submit($poll_quest,$ans1,$ans2,$ans3)
	{
		$optionArray = array();
		$optionArray = array($ans1,$ans2,$ans3);
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->createPoll($this->appId,$poll_quest,$poll_quest,$optionArray);

		$content = '<div style="text-align: right; width: 170;">
					<form action="'.SHIKSHA_HOME_URL.'/mailer/Mailer/userPollOpinion" method="POST">
					<table width="170" cellpadding="2" cellspacing="0" border="0" style="background-color: none; border: 1px #333333 solid;">
					<tr><td align="center" colspan="2" style="color: #000000; font-family: Verdana; font-weight: bold;">'.$poll_quest.'<br><br></td></tr>
					<tr><td><input type="radio" name="answer"  value="'.$result['pollOption'][0]['optionId'].'"/></td>
					<td width="100%"><label for="'.$result['pollOption'][0]['optionId'].'" style="color: #000000; font-family: Verdana;">'.$result['pollOption'][0]['optionName'].'</label></td></tr>
					<tr><td><input type="radio" name="answer"  value="'.$result['pollOption'][1]['optionId'].'"/></td>
					<td width="100%"><label for="'.$result['pollOption'][1]['optionId'].'" style="color: #000000; font-family: Verdana;">'.$result['pollOption'][0]['optionName'].'</label></td></tr>
					<tr><td><input type="radio" name="answer"  value="'.$result['pollOption'][2]['optionId'].'"/></td>
					<td width="100%"><label for="'.$result['pollOption'][2]['optionId'].'" style="color: #000000; font-family: Verdana;">'.$result['pollOption'][0]['optionName'].'</label></td></tr>
					<tr><td colspan="2" align="center">
					<br><input type="submit" value="Vote" style="border: 1px #333333 solid;"/><br>
					<input type="hidden" name="poll_id" value="'.$result['poll_id'].'"/>
					<input type="hidden" name="emailId" value="<!-- #varNamemailId --><!-- varNamemailId# -->"/>
					<input type="hidden" name="trackerId" value="<!-- #varNametrackerId --><!-- varNametrackerId# -->"/>
					</td></tr>
					</table></form></div>';
		echo $content;
	}

	function userPollOpinion()
	{
		mail('praveen.singhal@99acres.com,naveen.bhola@shiksha.com','Function userPollOpinion called.','Function userPollOpinion called.');
		// print_r($_POST);
		$poll_id = $this->input->post('poll_id');
		$poll_opinion = $this->input->post('answer');
		$email = $this->input->post('emailId');
		$mailer_id = $this->input->post('trackerId');
		// $userOpinion = $_POST['answer[]'];
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->registerPoll($this->appId,$mailer_id, $email, $poll_id, $poll_opinion);
		echo "<script>alert('Thank you for submitting your Vote! Please visit shiksha.com for all education related Queries!!');location.href = 'https://www.shiksha.com' ;</script>";
	}
	
	function registerAutomaticFeedback($data,$extradata) {
		try {
			$dataArr = explode("_",$data);
		
            $finalData = array();
            for($i = 0 ; $i < count($dataArr); $i++) {
                    $tempArr = explode("~",$dataArr[$i]);
                    $finalData[$tempArr[0]] = $tempArr[1];
            }

	       	if(!empty($finalData['courseId'])) {				
			
				$courseIds = split(",",$finalData["courseId"]);
            	$firstCourseId = $courseIds[0];

				$course_model = $this->load->model('listing/coursemodel');
				$isStudyAboradListing = $course_model->isStudyAboradListing($firstCourseId, 'course');

				if($isStudyAboradListing) {

					$is_course_deleted = $course_model->getDeletedCourseInstituteById($finalData['courseId']);
					if($is_course_deleted > 0) {
						return false;
					}			

					$this->load->builder('ListingBuilder','listing');
					$listingBuilder = new ListingBuilder;
					$courseRepository = $listingBuilder->getCourseRepository();		

					$course = $courseRepository->find($finalData['courseId']);

	       			$instituteCityIds = array_fill_keys(explode(',', $finalData['instituteCityId']), TRUE);

		            if(count($instituteCityIds) == 1 && key($instituteCityIds) > 0 && $finalData['instituteLocalityId'] > 0) {
		                
		                $this->registerMailerResponse($data,"","",$extradata);

		            } else if($finalData['courseId'] > 0) {					

						$courseName = $course->getName();
						$courseURL = $course->getURL();
						
						$this->load->model('mailer/mailermodel');			
						$courseLocations = $this->mailermodel->getCourseLocations($finalData['courseId']);
						
						if(count($instituteCityIds) == 1 && key($instituteCityIds) > 0 && (count($courseLocations['localities'][key($instituteCityIds)]) == 1 || (empty($courseLocations['localities'][key($instituteCityIds)]) && !empty($courseLocations['cities'][key($instituteCityIds)])))) {
							$this->registerMailerResponse($data,"","",$extradata);
						}
						
						if($courseLocations['numOfCities'] > 1 || $courseLocations['numOfLocalities'] > 1) {
							$headerComponents = array(
								'css'   =>  array('nationalCourses','common_new'),
								'js'    =>  array(),
								'title' =>  'Location preference'
							);
							
							$dataArray = array();
							$dataArray['headerComponents'] = $headerComponents;
							$dataArray['courseLocations'] = $courseLocations;
							$dataArray['instituteCityIds'] = $instituteCityIds;
							$dataArray['courseName'] = $courseName;
							$dataArray['courseURL'] = $courseURL;
							$dataArray['responseURL'] = SHIKSHA_HOME_URL.'/mailer/Mailer/registerMailerResponse/'.$data;
							
							$this->load->helper('utility_helper');
							$this->load->view('mailer/multiLocationResponsePref', $dataArray);
						} else {
							$this->registerMailerResponse($data,"","",$extradata);
						}
						
					}

				} else {

					$this->createNationalResponse($data, '', '', $extradata);				

				}	
				
			}
	
			
	  	} catch(Exception $e) {
		  //
      	}
	}

    function registerMailerResponse($data, $cityId, $localityId,$extradata) {

        $this->load->library('MailerClient');
        $objmailerClient = new MailerClient;

        $dataArr = explode("_",$data);

        $finalData = array();
        for($i = 0 ; $i < count($dataArr); $i++) {
            $tempArr = explode("~",$dataArr[$i]);
            $finalData[$tempArr[0]] = $tempArr[1];
        }
        if(!empty($finalData['url']) && !empty($extradata)) {
            $finalData['url'] = $finalData['url'].'/'.$extradata;
        }
        if(isset($finalData["instituteId"])){
            $typeId = $finalData["instituteId"];
            $type = "institute";
        }
        else if(isset($finalData["courseId"])){
            $typeId = $finalData["courseId"];
            $type = "course";
        }

        $typeIdArr = split(",",$typeId);

        $objmailerClient->registerData($this->appId, $data, $cityId, $localityId);

    	if(count($typeIdArr) == 1) {

            $url = base64_decode($finalData["url"]);
            $urlArr = split(",",$url);

            if(count($urlArr) == 1 && $url != '') {
                $redirectUrl = $url;
            } else {

				$instituteCityId = $cityId > 0 ? $cityId : $finalData["instituteCityId"];
				$instituteLocalityId = $localityId > 0 ? $localityId : $finalData["instituteLocalityId"];
               	$redirectUrl = SHIKSHA_HOME_URL.'/getListingDetail/'.$typeId.'/'.$type.'?city='.$instituteCityId.'&locality='.$instituteLocalityId;

            }

    	} else {
			$redirectUrl = SHIKSHA_HOME_URL;
        }

        $redirectUrl = $this->_addMailerTrackers($redirectUrl);

		/*$URLQuery = parse_url($redirectUrl,PHP_URL_QUERY);
		if($URLQuery) {
		    $redirectUrl .= '&utm_source=shiksha&utm_medium=email&utm_campaign=clientmailers';
		}
		else {
		    $redirectUrl .= '?utm_source=shiksha&utm_medium=email&utm_campaign=clientmailers';
		}*/

        echo "<script>alert('Thank you for showing your interest!!');location.href = '".$redirectUrl."' ;</script>";
    }

    function createNationalResponse($data, $cityId, $localityId, $extradata) {

        $dataArr = explode("_",$data);

        $finalData = array();
        for($i = 0 ; $i < count($dataArr); $i++) {
            $tempArr = explode("~",$dataArr[$i]);
            $finalData[$tempArr[0]] = $tempArr[1];
        }

        if(empty($finalData["email"])) {
        	return false;
        }

        $this->load->model('mailer/mailermodel');			

		$userAllDetails = $this->isUserLoggedIn($finalData["email"]);
		if(empty($userAllDetails)) {
			return false;
		}
		$user_id = $userAllDetails[0]["userid"];

		if(isset($finalData["courseId"])){
            $courseIds = $finalData["courseId"];
        }
 		$courseIds = split(",",$courseIds);
 		$multiLocationLayer = false;

 		if(count($courseIds) == 1) {

 			$courseId = $courseIds[0];

 			$national_course_model = $this->load->model('nationalCourse/nationalcoursemodel'); 				
			$is_course_deleted = $national_course_model->getCourseDetailById($courseId); 
			if(empty($is_course_deleted)) {
				return false;
			}

			$this->load->builder("nationalCourse/CourseBuilder");
			$builder = new CourseBuilder();
			$courseRepository = $builder->getCourseRepository();

			$course = $courseRepository->find($courseId, array('location'));

			$nationalCourseURL = '';
			if(is_object($course)) {	

				$course_id = $course->getId();
				if($course_id < 0) {
					return false;
				}							
				$nationalCourseURL = $course->getURL();
			} else {
				return false;
			}

			// check if it was a valid response by user..
			$isValidResponseUser = modules::run('registration/RegistrationForms/isValidUser', $courseId, $user_id, false, true);
			
			if($isValidResponseUser != false) {

	   			$instituteCityIds = explode(',', $finalData['instituteCityId']);
	   			$instituteLocalityIds = explode(',', $finalData['instituteLocalityId']);

	   			if($cityId > 0) {

	   				$instituteCityId = $cityId;

					$responseData = array();
					$responseData['user_id'] = $user_id;
					$responseData['listing_id'] = $courseId;
					$responseData['action_type'] = $finalData["action"];
					$responseData['preferred_city_id'] = $cityId;
					if($localityId > 0) {
						$responseData['preferred_locality_id'] = $localityId;
						$instituteLocalityId = $localityId;
					}

					$this->createMailerResponse($responseData);

					unset($course);

	   			} else if((count($instituteCityIds) == 1) && ($instituteCityIds[0] > 0) && ((empty($finalData['instituteLocalityId'])) || (count($instituteLocalityIds) == 1 && $instituteLocalityIds[0] > 0))) {

						$instituteCityId = $instituteCityIds[0];
						$instituteLocalityId = $instituteLocalityIds[0];

						$responseData = array();
						$responseData['user_id'] = $user_id;
						$responseData['listing_id'] = $courseId;
						$responseData['action_type'] = $finalData["action"];
						$responseData['preferred_city_id'] = $instituteCityIds[0];
						$responseData['preferred_locality_id'] = $instituteLocalityIds[0];

						$this->createMailerResponse($responseData);

						unset($course);

				} else {
					$locations = $course->getLocations();
		
					$courseLocations = array();
					if(!empty($locations)) {		

						$numOfCities = 0;
						$numOfLocalities = 0;
						foreach($locations as $location) {	
							$city_id = $location->getCityId();//echo $city_id;exit;
							$locality_id = $location->getLocalityId(); //echo $locality_id;exit;

							if($city_id > 0 && empty($courseLocations['cities'][$city_id])) {
								$numOfCities++;
								$courseLocations['cities'][$city_id] = $location->getCityName();
							}
								
							if($city_id > 0 && $locality_id > 0) {
								$numOfLocalities++;
								$courseLocations['localities'][$city_id][$locality_id] = $location->getLocalityName();
							}		
							
						}

						$courseLocations['numOfCities'] = $numOfCities;
						$courseLocations['numOfLocalities'] = $numOfLocalities;
					}

		 			if($courseLocations['numOfCities'] > 1 || $courseLocations['numOfLocalities'] > 1) {

						$headerComponents = array(
							'css'   =>  array('nationalCourses','common_new'),
							'js'    =>  array(),
							'title' =>  'Location preference'
						);

						$dataArray = array();
						$dataArray['headerComponents'] = $headerComponents;
						$dataArray['courseLocations'] = $courseLocations;
						$dataArray['instituteCityIds'] = $instituteCityIds;
						$dataArray['courseName'] = $course->getName();
						$dataArray['courseURL'] = $nationalCourseURL;
						$dataArray['responseURL'] = SHIKSHA_HOME_URL.'/mailer/Mailer/createNationalResponse/'.$data;

						$this->load->helper('utility_helper');
						$this->load->view('mailer/multiLocationResponsePref', $dataArray);
						$multiLocationLayer = true;

					} else {

						$responseData = array();
						$responseData['user_id'] = $user_id;
						$responseData['listing_id'] = $courseId;
						$responseData['action_type'] = $finalData["action"];

						$this->createMailerResponse($responseData);

						unset($course);
					}					
				}

			}

 		} else {

			$this->createMultipleResponses($courseIds, $user_id, $finalData["action"]);

		}

		if(!$multiLocationLayer) {

			if(!empty($finalData['url']) && !empty($extradata)) {
				$finalData['url'] = $finalData['url'].'/'.$extradata;
			}

			if(count($courseIds) == 1) {

	            $url = base64_decode($finalData["url"]);
	            $urlArr = split(",",$url);

	            if(count($urlArr) == 1 && $url != '') {

	                $redirectUrl = $url;

	            } else {

	           		$redirectUrl = $nationalCourseURL.'?city='.$instituteCityId.'&locality='.$instituteLocalityId;

	            }

	    	} else {
				$redirectUrl = SHIKSHA_HOME_URL.'/shiksha/index';
	        }

	        $redirectUrl = $this->_addMailerTrackers($redirectUrl);

			/*$URLQuery = parse_url($redirectUrl,PHP_URL_QUERY);
			if($URLQuery) {
			    $redirectUrl .= '&utm_source=shiksha&utm_medium=email&utm_campaign=clientmailers';
			}
			else {
			    $redirectUrl .= '?utm_source=shiksha&utm_medium=email&utm_campaign=clientmailers';
			}*/

	        echo "<script>alert('Thank you for showing your interest!!');location.href = '".$redirectUrl."' ;</script>";
		}

    }
	
	function isUserLoggedIn($encryptedEmail) {
		
		$userDetails = $this->mailermodel->getUserDetailsByEmail($encryptedEmail);

		if(!empty($userDetails)) {

			$email = $userDetails['email'];
			$password = $userDetails['ePassword'];
        	$cookie = $email."|".$password;

			setcookie('user',$cookie,0,'/',COOKIEDOMAIN);

			$userAllDetails = $this->checkUserValidation($cookie);

			$userId = $userAllDetails[0]["userid"];
	    	if($userId > 0 && $userId != '') {
	        	$this->load->model('user/usermodel');                
        		$this->usermodel->trackUserLogin($userId);
        	}
		}

		return $userAllDetails;
	}

	function createMailerResponse($data) {

	    $responseData = array();
		$responseData['user_id'] = $data['user_id'];
		$responseData['listing_id'] = $data['listing_id'];						
		$responseData['preferred_city_id'] = $data['preferred_city_id'];						
		$responseData['preferred_locality_id'] = $data['preferred_locality_id'];						
		$responseData['listing_type'] = 'course';		

		if(isMobileRequest()){
			$responseData['tracking_keyid'] = 676;
		} else {
			$responseData['tracking_keyid'] = 675;
		}

		$action_type = $data['action_type'];						
		$action = 'mailerAlert';
        if($action_type != '') {
            $action = $action_type;
        }
    	if(isMobileRequest()){
 	   		$action = "MOB_".$action;
		}
		$responseData['action_type'] = $action;
		$responseData['isViewedResponse'] = 'yes';
		
		Modules::run('response/Response/createResponseByParams',$responseData); 

	}

	function createMultipleResponses($courseIds, $user_id, $action_type) {

		if(!empty($courseIds)) {
			
	    	$national_course_model = $this->load->model('nationalCourse/nationalcoursemodel'); 				

			foreach($courseIds as $courseId) {

				$is_course_deleted = $national_course_model->getCourseDetailById($courseId); 
				if(empty($is_course_deleted)) {
					return false;
				}

				$this->load->builder("nationalCourse/CourseBuilder");
				$builder = new CourseBuilder();
    			$courseRepository = $builder->getCourseRepository();

    			$course = $courseRepository->find($courseId);

				if(is_object($course)) {
					$course_id = $course->getId();
					if($course_id < 0) {
						return false;
					}							
				} else {
					return false;
				}

				$isValidResponseUser = modules::run('registration/RegistrationForms/isValidUser', $courseId, $user_id, false, true);

				if($isValidResponseUser != false) {

					$responseData = array();
					$responseData['user_id'] = $user_id;
					$responseData['listing_id'] = $courseId;
					$responseData['action_type'] = $action_type;

					$this->createMailerResponse($responseData);

				}

				unset($course);

			}		
		}
	}

	function listingAutoLogin($data)
	{
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$dataArr = explode("_",$data);
		$finalData = array();
		for($i = 0 ; $i < count($dataArr); $i++) {
			$tempArr = explode("~",$dataArr[$i]);
			$finalData[$tempArr[0]] = $tempArr[1];
		}
		error_log("LKJ ".print_r($finalData,true));
		$typeId = $finalData["instituteId"];
		$typeIdArr = split(",",$typeId);
		error_log("LKJ haaa".$typeId);
		if(count($typeIdArr)==1 ) {
			error_log("LKJ haaa");
			$redirectUrl = 'https://'.THIS_CLIENT_IP.'/getListingDetail/'.$typeId.'/institute/';
		}else {
			$redirectUrl = 'https://'.THIS_CLIENT_IP;
		}
		$email = $finalData['email'];
		$result = $objmailerClient->autoLogin($this->appId,$email);
		setcookie('user',$result,0,'/',COOKIEDOMAIN);
		echo "<script>location.href = '".$redirectUrl."' ;</script>";
	}

	function autoLogin($data,$extraData='')
	{
		//mail('praveen.singhal@99acres.com','Function autoLogin called.','Function autoLogin called for emailId : '.print_r($email,true));
		if($extraData){
			$data = $data.'/'.$extraData;
		}
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$dataArr = explode("_",$data);
		$finalData = array();
		for($i = 0 ; $i < count($dataArr); $i++) {
			$tempArr = explode("~",$dataArr[$i]);
			$finalData[$tempArr[0]] = $tempArr[1];
		}
		$url = base64_decode($finalData["url"]);
		$urlArr = split(",",$url);
		if(count($urlArr)==1 && $url!='') {
			$redirectUrl = $url;
		}else {
			$redirectUrl = 'https://'.THIS_CLIENT_IP;
		}
		$email = $finalData['email'];
		if($email!='')
		{
			$result = $objmailerClient->autoLogin($this->appId,$email);
			setcookie('user',$result,0,'/',COOKIEDOMAIN);
			$_COOKIE['user'] = $result;
		}
		
		if($_POST['mfid']) {
			echo Modules::run('mailer/MarketingFormProcessor/doMISLogging',$email);
		}
		else {
			header( "Location: $redirectUrl" );
		}
	}

	function Unsubscribe($encodedMail)
	{
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$objmailerClient->unsubscribe($this->appId,$encodedMail);
		$result = $objmailerClient->autoLogin($this->appId,$encodedMail);
		setcookie('user',$result,0,'/',COOKIEDOMAIN);
		header("Location: ".SHIKSHA_HOME);
	}

	function leadFeedback()
	{
		error_log("POI".$feedback);
		$email = $_POST['code'];
		$mailer_id = $this->input->post('trackerId');
		$typeId = $this->input->post('typeId');
		$type = $this->input->post('type');
		$feedback = $this->input->post('feedback');
		error_log("POI".$feedback);
		// $userOpinion = $_POST['answer[]'];
		// print_r($_POST);
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->registerLead($this->appId,$mailer_id, $email, $feedback, $typeId, $type);
		echo "<script>alert('Thank you for submitting your Feedback! Please visit shiksha.com for all education related Queries!!');location.href = 'https://".THIS_CLIENT_IP."' ;</script>";
	}

	function autoLoginQuestion($data)
	{
		error_log("BNM ");
		echo "BNM";
		$dataArr = explode("_",$data);
		$finalData = array();
		for($i = 0 ; $i < count($dataArr); $i++) {
			$tempArr = explode("~",$dataArr[$i]);
			$finalData[$tempArr[0]] = $tempArr[1];
		}
		$email = $finalData['email'];
		$typeId = $finalData['questionId'];
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->autoLogin($this->appId, $email);
		error_log("BNM122222 ".print_r($result,true));
		setcookie('user',$result,0,'/',COOKIEDOMAIN);
		header('location:/getTopicDetail/'.$typeId);
	}

	function userFeedback()
	{
		$email = $this->input->post('emailId');
		$mailer_id = $this->input->post('trackerId');
		$feedback = $this->input->post('feedback');
		// $userOpinion = $_POST['answer[]'];
		// print_r($_POST);
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->registerFeedback($this->appId,$mailer_id, $email, $feedback);
		echo "<script>alert('Thank you for submitting your Feedback! Please visit shiksha.com for all education related Queries!!');location.href = 'https://".THIS_CLIENT_IP."' ;</script>";
	}

	function encryptCSV()
	{
		$startTime=time();
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		//ini_set('upload_max_filesize','100M');
		//ini_set('max_execution_time', '1800');
		if(isset($_REQUEST['submit']) && $_REQUEST['submit']==1 && (isset($_FILES['c_csv']['name'])) && ($_FILES['c_csv']['name']!=''))
		{
			$csv_array = $this->buildCVSArray($_FILES['c_csv']['tmp_name']);
			// echo "<pre>\n".print_r($_FILES,true)."\n</pre>";
			$split_array=array();
			foreach($csv_array as $key=>$value){
				for($i=0;$i<count($value);$i++)
				{
					$split_array[$i/100][$key][$i%100]=$value[$i];
				}
			}
			// echo "<pre>\n".print_r($split_array,true)."\n</pre>";
			
			$objmailerClient = new MailerClient;
			foreach($split_array as $onearray)
			{
				$result[]= $objmailerClient->encryptCsv(1,$onearray,trim($_REQUEST['field']),trim($_REQUEST['url']),trim($_REQUEST['eurl']),trim($_REQUEST['unsuburl']));
			}
			unset($csv_array);
			foreach($result as $oneresult)
			{
				$newArray[] = json_decode($oneresult['result']);
			}
			//       echo "<pre>\n".print_r($newArray,true)."\n</pre>";
			$newcsvArray=array();
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=".$_FILES['c_csv']['name']);
			foreach($newArray as $onenewArray)
			{
				foreach($onenewArray as $key=>$value)
				{
					for($i=0;$i<count($value);$i++)
					{
						$newcsvArray[$key][]=$value[$i];
					}
				}
			}
			foreach($newcsvArray as $key=>$value)
			{
				$i=0;
				foreach($value as $val)
				{
					$csvArray[$i][$key]=$val;
					$i++;
				}
			}
			$leads=$csvArray;
			$csv = '';
			foreach ($leads as $lead){
				foreach ($lead as $key=>$val){
					$csv .= '"'.$key.'",';
				}
				$csv .= "\n";
				break;
			}
			foreach ($leads as $lead){
				foreach ($lead as $key=>$val){
					$csv .= '"'.$val.'",';
				}
				$csv .= "\n";
			}
			$endtime=time();
			error_log("shivam1 time taken".($endtime-$startTime).$csv);
			echo $csv;
		}
		else
		{
			$this->load->view('mailer/encryptCSVView',$var_result);
		}
	}

	function registrationMailGenerator($userId,$userCategory,$duration)
	{
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->sendRegistrationQuestionMailer($this->appId,$userId,'Shalabh', $userCategory,$duration,'manish.zope@naukri.com');
		echo "<pre>".$result[0]['html']."</pre>";
	}

	function generateWeeklyMailer()
	{
		return;
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->generateWeeklyMailer($this->appId);
		echo "<pre>".$result[0]['html']."</pre>";
	}

	function generateDailyMailer()
	{
		return;
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->generateDailyMailer($this->appId);
		echo "<pre>".$result[0]['html']."</pre>";
	}
	
	function generateQnAMailer(){
		mail('teamldb@shiksha.com', 'function -generateQnAMailer called after Unsubscribe removed from tuserflag', print_r($_SERVER,true));
        return;
		$this->validateCron();
		ini_set('max_execution_time', '1800');
        ini_set('memory_limit','512M');

		$this->load->library('MailerClient');
		$result = $this->mailerclient->generateQnAMailer();
		echo 'MAIL IDS Generated : ';
		echo 'DONE : '._p($result);
		
		exit(0);
	}

	function generateBestAnswerMailer()
	{
		return;
		$this->load->library('MailerClient');
		$objmailerClient = new MailerClient;
		$result = $objmailerClient->generateBestAnswerMailer($this->appId);
		echo "<pre>".$result[0]['html']."</pre>";
	}

	function userSets($selectedSet = 0)
	{
		$this->init();

		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];
		$adminType = $userGroupData['user_type'];

		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		
		$data = array();
		$data['userid'] = $userid;
		$data['usergroup'] = $usergroup;
		$data['thisUrl'] = $thisUrl;
		$data['validateuser'] = $validity;
		$data['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$data['myProducts'] = $cmsUserInfo['myProducts'];
		$data['prodId'] = $this->prodId;
		$data['cmsUserInfo'] = $cmsUserInfo;
		
		$this->load->model('mailer/mailermodel');
		$userSets = $this->mailermodel->getUserSets($userid, $groupId, $adminType);

		$allAdminData = $this->mailermodel->getAllAdminData();
		
		foreach($userSets as $userSet) {
			if($userSet['id'] == $selectedSet) {
				$data['successMsg'] = "User set <b>".$userSet['name']."</b> has been successfully added";
			}
		}
		
		$data['userSets'] = $userSets;
		$data['allAdminData'] = $allAdminData;

		$this->load->view('mailer/UserSets',$data);
	}

	function compositeUserSets()
	{
		$this->init();

		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];
		$adminType = $userGroupData['user_type'];

		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		
		$data = array();
		$data['userid'] = $userid;
		$data['usergroup'] = $usergroup;
		$data['thisUrl'] = $thisUrl;
		$data['validateuser'] = $validity;
		$data['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$data['myProducts'] = $cmsUserInfo['myProducts'];
		$data['prodId'] = $this->prodId;
		$data['cmsUserInfo'] = $cmsUserInfo;
		
		$data['compositeUserSet'] = 1;
		$allAdminData = $this->mailermodel->getAllAdminData();
		
		$this->load->model('mailer/mailermodel');
		$data['usersets'] = $this->mailermodel->getAllUserSearchCriteria($userid, $groupId, $adminType);
		$data['allAdminData'] = $allAdminData;

		$this->load->view('mailer/CompositeUserSets',$data);
	}

	function getUserCountInUserSet($setId,$countFlag)
	{	
		if($countFlag == 'true') {
			$countFlag = true;
		} else if($countFlag == 'false') {
			$countFlag = false;
		}

		$userIds = $this->_executeUserInUserSet($setId,$countFlag);

		if($countFlag){
			$numUsers = $userIds;
		} else{
			$numUsers = count($userIds);
		}

		echo $numUsers;
	}

	function downloadUserInUserSet($setId)
	{
		ini_set('memory_limit','4096M');
		set_time_limit(0);
		$userIds = $this->_executeUserInUserSet($setId,false);
	
		$data = array();
		$userData = array();
		
		$chunkSize =10000;
		$counter =1;
		$totalCount = count($userIds);

		$this->load->model('user/usermodel');
		
		$finalOutput = $this->getColumnNameForCSV();

		foreach ($userIds as $key => $value) {
			$chunkUserId[] =$value;

			if($counter%$chunkSize == 0 || $counter == $totalCount){
				$chunkUserData = $this->usermodel->getUserInfoForMMMCSV($chunkUserId);
				$output = $this->_createCSV($chunkUserData);

				$finalOutput = $finalOutput.$output;
				
				unset($output);
				unset($chunkUserData);
				unset($chunkUserId);
			}

			$counter++;
		}

		unset($userIds);
		

		$filename = 'UserSet-'.$setId.'-on-'.date('Y-m-d h-i-s').'.csv';
		$mime = 'text/x-csv';
		
		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header("Content-Length: ".strlen($finalOutput));
		}
		else {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header("Content-Length: ".strlen($finalOutput));
		}
		echo ($finalOutput);
	}

	private function getColumnNameForCSV($type){	
		$coumnName = "";
		if ($type == 'MisReport')
		{
			$csvFieldForUserSet = array("Client ID",
										"Mailer ID",
										"Mailer Name",
										"Total Mails",
										"Processed Mails",
										"Sent Mails",
										"Unique mails Opened",
										"Open Rate(%)",
										"Unique mails Clicked ",
										"Click Rate(%)",
										"Scheduled Date",
										"Scheduled Time",
										"Unique Unsubscribe clicks",
										"Unsubscribe Rate(%)",
										"Created By",
										"Number of times mail opened",
										"Number of times mail clicked",
										"Number of times Unsubscribe link clicked",
										"Client Display Name",
										"Campaign Name",
										"Campaign Id",
										"Parent Id",
										"Campaign Activity",
										"Mailer Subject",
										"Sender Name");	
		}
		else{
			$csvFieldForUserSet = array('email','firstname','lastname','mobile');
		}
		$coumnName = '"'.implode('","', $csvFieldForUserSet).'",';
		$coumnName = trim($coumnName)."\n";

		return $coumnName;
	}


	function _executeUserInUserSet($setId, $countFlag = false)
	{
		if($this->_canStartMMMQueryRequest()) {
			$this->load->library('mailer/MailerFactory');
			$mailerCriteriaEvaluatorService = MailerFactory::getMailerCriteriaEvaluatorService();
			$response = $mailerCriteriaEvaluatorService->evaluateCriteria($setId, $countFlag);
			$this->_completeMMMQueryRequest();
			return $response;
		}
		else {
			echo "SERVER_OVERLOAD";
			exit();
		}
	}

	function getUserCountInSearchCriteria($countFlag)
	{
		if($this->_canStartMMMQueryRequest()) {
			$userSetType = $this->input->post('usersettype',true);
			unset($_POST["_"]);
			$criteriaJSON = json_encode($_POST);
				
			$this->load->library('mailer/MailerFactory');
			$mailerCriteriaEvaluatorService = MailerFactory::getMailerCriteriaEvaluatorService();
			$response = $mailerCriteriaEvaluatorService->getUserListByCriteria($criteriaJSON,$userSetType);
			
			$criteriaObj = json_decode($criteriaJSON,true);
    		$countFlag = $criteriaObj['countFlag'];

			if($countFlag){
				echo $response;
				return;
			}

			unset($criteriaObj);
			unset($countFlag);

			$numUsers = count($response);
			$this->_completeMMMQueryRequest();
			_P($numUsers);die;
			echo $numUsers;
		}
		else {
			echo "SERVER_OVERLOAD";
		}
	}

	function getUserCountInCompositeSearchCriteria($mailerCriteria)
	{
		ini_set('memory_limit','4096M');
		$numUsers = $this->_executeUserInCompositeSearchCriteria(false,$mailerCriteria);
		if(is_array($numUsers)) {
			echo count($numUsers);
		} else {
			echo $numUsers;
		}
	}

	function downloadUserInCompositeSearchCriteria()
	{
		ini_set('memory_limit','4096M');
		set_time_limit(0);
		$downloadCheckFlag= true;
		$userIds = $this->_executeUserInCompositeSearchCriteria($downloadCheckFlag);
		$data = array();
		$userData = array();
		if(!empty($userIds)) {
			$this->load->model('user/usermodel');
			$userData = $this->usermodel->getUserInfoForMMMCSV($userIds);
		}
		$finalOutput = $this->getColumnNameForCSV();
		$finalOutput .= $this->_createCSV($userData);
		$filename = date('Y-m-d h-i-s').'.csv';
		$mime = 'text/x-csv';
		
		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header("Content-Length: ".strlen($finalOutput));
		}
		else {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header("Content-Length: ".strlen($finalOutput));
		}
		echo ($finalOutput);
	}

	private function _executeUserInCompositeSearchCriteria($downloadCheckFlag = false,$mailerCriteria)
	{
		if($this->_canStartMMMQueryRequest()) {
			$userset = 'userset';
			if(!empty($mailerCriteria)){
				$userset  = $userset."_".$mailerCriteria;
			}
			$userSearchCriteria = $this->input->post($userset,true);
			$inc_exc_array = array();
			foreach ($userSearchCriteria as $inc_exc_key => $inc_exc_data) {
				$and_array = array();
				foreach ($inc_exc_data as $and_key => $and_data) {
					$and_data = $this->remove_array_empty_values($and_data);
					$union = join('OR', array_unique($and_data));
					if (!empty($union)) {
						$and_array[$and_key] = $union;
					}
				}
				$intersection = join('AND', array_unique($and_array));
				if (!empty($intersection)) {
					$inc_exc_array[$inc_exc_key] = $intersection;
				}
			}
			$finalUserSeachCriteria = join('EXCLUDE', $inc_exc_array);
			
			$this->load->library('mailer/MailerFactory');
			$mailerCriteriaEvaluatorService = MailerFactory::getMailerCriteriaEvaluatorService();
			$response = $mailerCriteriaEvaluatorService->evaluateCriteria($finalUserSeachCriteria, false);
			
			$this->_completeMMMQueryRequest();
			return $response;
		}
		else {
			echo "SERVER_OVERLOAD";
			exit();
		}
	}

	private function _createCSV($misData){
		$data = "";
		foreach($misData as $row){
			$line = "";
			foreach($row as $key=>$value){

				if(!isset($value) || $value == ""){
					$value = ",";
				}else{
					$value = str_replace('"', '""', $value);
					$value = '"' . $value . '"' . ",";
				}
				$line.= $value;
			}
			$data .= trim($line)."\n";
		}

		$data = str_replace("\r", "", $data);
		return $data;
	}

	private function _canStartMMMQueryRequest()
	{	return true; // by pass this code as per product
		$this->load->library('cacheLib');
		$cacheLibObj = new cacheLib();
		$MMMQueryCount = intval($cacheLibObj->get("MMMQueryCount"));
		if($MMMQueryCount < 3) {
			$MMMQueryCount++;
			$cacheLibObj->store("MMMQueryCount",$MMMQueryCount);
			$cacheLibObj->store("MMMQueryCountLastUpdate",time());
			return TRUE;
		}
		else {
			$lastUpdate = $cacheLibObj->get("MMMQueryCountLastUpdate");
			$timeDiff = time()-$lastUpdate;
			if($timeDiff > 900) {
				$cacheLibObj->store("MMMQueryCount",1);
				$cacheLibObj->store("MMMQueryCountLastUpdate",time());
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}

	private function _completeMMMQueryRequest()
	{
		$this->load->library('cacheLib');
		$cacheLibObj = new cacheLib();
		$MMMQueryCount = intval($cacheLibObj->get("MMMQueryCount"));
		$MMMQueryCount--;
		$cacheLibObj->store("MMMQueryCount",$MMMQueryCount);
	}

	private function remove_array_empty_values($array) {
		$new_array = array();
		
		foreach ($array as $key => $value) {
			$value = trim($value);
				
			if (!empty($value)) {
				$new_array[] = $value;
			}
		}
		return $new_array;
	}

	public function recordMailerReportSpam() {
		$validity = $this->checkUserValidation();
		if(!empty($validity[0]['userid'])) {
			$mailerId = $this->input->post('mailerId',true);
			$mailId = $this->input->post('mailId',true);
			$reportSpamReasons = $this->input->post('reportSpamReasons',true);
			$this->load->model('mailermodel');
			return $this->mailermodel->recordMailerReportSpam($validity[0]['userid'],$mailerId,$mailId,$reportSpamReasons);
		}
		return false;
	}

	public function mailerReportSpam($mailerId,$mailId) {
		list($text,$mailerId) = explode('-',$mailerId);
		$data['mailerId'] = $mailerId;
		$data['mailId'] = $mailId;
		$this->load->model('mailermodel');
		$data['reasons'] = $this->mailermodel->getMailerReportSpamReasons($mailerId);
		$this->load->view('mailerReportSpam',$data);
	}

	public function viewMailer($mailId,$date)
	{
		$user = $this->checkUserValidation();
		if(!is_array($user) || !is_array($user[0]) || !$user[0]['userid']) {
			echo "Sorry. You are not authorized to view this page.";
			exit();
		}
		
		$this->load->model('mailermodel');
		
		$userId = $user[0]['userid'];
		if(!$this->mailermodel->hasAccessToMailer($userId,$mailId)) {
			echo "Sorry. You are not authorized to view this page.";
			exit();
		}
	
		if($mailer = $this->mailermodel->getMailContent($mailId,$date)) {
			$this->load->view('mailer/viewMailer',array('mailer' => $mailer));
		}
		else {
			echo "Sorry. This mailer does not exist.";
		}
	}
        
        public function viewMailer1($mailId) {
                $this->load->model('mailermodel');   
                $date = '20150317';
		if($mailer = $this->mailermodel->getMailContent($mailId,$date)) {
                        //var_dump($mailer);exit;    
			$this->load->view('mailer/viewMailer',array('mailer' => $mailer));
		}
		else {
			echo "Sorry. This mailer does not exist.";
		}
        }
   
	public function mailerUnsubscribe($encodedMail) {
		$data['encodedMail'] = $encodedMail;
		$this->load->view('mailerUnsubscribeForm',$data);
	}

	public function profileCompleteTrigger() {
		$validity = $this->checkUserValidation();
		if(!empty($validity[0]['userid'])) {
			/**
			 * Update userMailerSentCount table to reset triggers of product mailers when a product mail is opened
			 */
			$this->load->library('mailer/ProductMailerEventTriggers');
			$this->productmailereventtriggers->resetMailerTriggers($validity[0]['userid'], 'profileCompleted');
		}
		return true;
	}
	
	public function getExams($categoryId)
	{
		$desiredCourse = 0;
		if($categoryId == 3) {
			$desiredCourse = 2;
		}
		else if($categoryId == 2) {
			$desiredCourse = 52;
		}
		if($desiredCourse) {
			$examsFieldValueSource = new \registration\libraries\FieldValueSources\Exams;
			$examValues = $examsFieldValueSource->getAllValue(array('desiredCourse' => $desiredCourse));
			$this->load->view('mailer/profile_india_exams',array('examValues' => $examValues));
		}
	}
	
	public function getSpecializationsForStudyAbroad($courseId,$type)
	{
		$this->load->model('LDB/ldbmodel');
		$course_specialization_list = $this->ldbmodel->getStudyAbroadSpecializations($courseId,$type);
		error_log("course_specialization_list".print_r($course_specialization_list,true));
		$this->load->view('mailer/profile_abroad_specializations',array('course_specialization_list' => $course_specialization_list));
	}
	
	public function getSubcategoriesCoursesList($property, $selectedCategories, $selectedSubCategories)
	{
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$catSubcatList = $categoryClient->getCatSubcatList(1,"1");
		$data = array();
			
		if(!empty($selectedCategories)){
			$selectedCategories = explode(',',$selectedCategories);
		}
		if(!empty($selectedSubCategories)){
			$selectedSubCategories = explode(',',$selectedSubCategories);
		}
			
		$categories = array();
		foreach($selectedCategories as $category){
			foreach($catSubcatList as $key=>$value){
				if($category == $key){
					$categories[$key]['categoryName'] = $value['name'];
					$categories[$key]['checked'] = 'true';
					foreach($value['subcategories'] as $value2){
						$categories[$key]['subCat'][$value2['catId']]['name'] = $value2['catName'];
						$categories[$key]['subCat'][$value2['catId']]['checked'] = 'true';
					}
				}
			}
		}
		$courseList = $categoryClient->getSubCategoryCourses(implode(",",$selectedSubCategories), True);
			
		if(!empty($selectedSubCategories)){
			$categories = array();
			foreach($selectedCategories as $category){
				foreach($catSubcatList as $key=>$value){
					if($category == $key){
						foreach($selectedSubCategories as $subCategory){
							foreach($value['subcategories'] as $value2){
								if($subCategory == $value2['catId']){
									$categories[$key]['categoryName'] = $value['name'];
									$categories[$key]['subCat'][$value2['catId']]['name'] = $value2['catName'];
									$categories[$key]['subCat'][$value2['catId']]['checked'] = 'true';
									foreach($courseList[$value2['catId']] as $courses){
										$categories[$key]['subCat'][$value2['catId']]['courses'][$courses['SpecializationId']] = $courses['CourseName'];
									}
								}
							}
						}
					}
				}
			}
                        $data['selectedSubCategories'] = true;
		}
		$data['property'] = $property;
		$data['categories'] = $categories;
		error_log("####data".print_r($data,true));
		$html = $this->load->view('mailer/subCategoriesCoursesList', $data, true);
			
		echo $html;
	}
	
	function abroadFullRegister() {
		$this->load->model('user/usermodel');
		$userModel = new UserModel;
		
		$loggedInUserData = $this->getLoggedInUserData();
		
		if($loggedInUserData['userId'] > 0) {
			$isLDBUser = $userModel->checkIfLDBUser($loggedInUserData['userId']);
			
			if($isLDBUser == 'YES') {
				header('Location: '.SHIKSHA_STUDYABROAD_HOME);
			}
			
			$abroadShortRegistrationData = $userModel->getAbroadShortRegistrationData($loggedInUserData['userId']);
			
			if(empty($abroadShortRegistrationData['whenPlanToGo'])) {
				header('Location: '.SHIKSHA_STUDYABROAD_HOME);
			}
		}
		else {
			header('Location: '.SHIKSHA_STUDYABROAD_HOME);
		}
		
		$headerComponents = array(
						'css'   =>  array('studyAbroadCommon'),
						'js'    =>  array('userRegistration', 'prototype', 'studyAbroadCommon'),
						'title' =>  'Register Study Abroad'
					);
		
		$data = array();
		$data['headerComponents'] = $headerComponents;
		$data['abroadShortRegistrationData'] = $abroadShortRegistrationData;
		
		$this->load->view('mailer/studyAbroadFullRegister', $data);	
	}
	

	/*
	* Load view for backend interface for creating sms campaign
	* Interface for: mailer@shiksha.com
	* @params: $error=> error message for invalid file upload
	*/
	public function responseSms($error=""){
		$this->init();
		// global $newsletterTemplateIdsArray;
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$data = array();
		$data['userid'] = $userid;
		$data['usergroup'] = $usergroup;
		$data['thisUrl'] = $thisUrl;
		$data['validateuser'] = $validity;
		$data['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$data['myProducts'] = $cmsUserInfo['myProducts'];
		$data['prodId'] = $this->prodId;
		$data['cmsUserInfo'] = $cmsUserInfo;
		$data['errorMessage'] = "";
		if(!($error == "")){
			$data['errorMessage'] = $error;
		}

		$data['savedCampaign'] = $this->getSavedSMSCampaign();
		$data['scheduledCampaign'] = $this->getScheduledCampaign();

		$this->load->view('mailer/smsResponseTemplate',$data);
	}

	/*
	* Extracts data from excel sheet, It handles only two columns
	* @params: file through POST
	*/
	public function getDataFromExcel(){
		$this->load->library('common/PHPExcel/IOFactory');

 		$fileName = $_FILES['userSetFile']['tmp_name'];
 		$fileType = PHPExcel_IOFactory::identify($fileName);

 		$objReader = PHPExcel_IOFactory:: createReader($fileType);
 		$objReader->setReadDataOnly(true);

 		$objPHPExcel = $objReader->load($fileName);
 		$activeSheet = $objPHPExcel->getActiveSheet();

 		$rows = $activeSheet->getHighestRow();
 		$cols = $activeSheet->getHighestColumn();
 		$columns = PHPExcel_Cell::columnIndexFromString($cols);

 		$data = array();
 		for($i=2; $i<=$rows; $i++){
 			for ($j=0; $j<$columns; $j++) {
 				$data[$i-1][$j] = $activeSheet->getCellByColumnAndRow($j,$i)->getValue();
 			}
 		}

 		return $data;
	}

	/*
	* Save sms Campaign for a client from mailer interface
	* @params: clientId, File, Message through POST
	*/
	 public function saveSMSList() {
		ini_set('memory_limit','1024M');
	 	$error ="";
	 	if(isset($_FILES['userSetFile']['name']) && $_FILES['userSetFile']['name'] !=''){
	 		$allowedExtensions = array('csv','ods','xls','xlsx');

	 		$fileExtension = pathinfo($_FILES['userSetFile']['name'], PATHINFO_EXTENSION);

	 		if(in_array($fileExtension, $allowedExtensions)){
	 			if($fileExtension == 'csv'){
	 				$csvData = $this->buildCVSArray($_FILES['userSetFile']['tmp_name']);
	 				// making data just like other formats
	 				$data = array();
	 				$i = 0; $j=1; $z=1;
	 				foreach($csvData as $row=>$val){
	 					foreach($val as $k=>$v){
	 						if($i == 0){
	 							$data[$j][] = $v;
	 							$j++;
	 						}else if($i == 1){
	 							$data[$z][] = $v;
	 							$z++;
	 						}
	 					}
	 					$i++;
	 				}
	 			}else{
	 				$data = $this->getDataFromExcel();
	 			}
	 			$clientId = $this->input->post('clientId',true);
	 			$message = $this->input->post('message',true);
	 			$campaignName = $this->input->post('campaignName',true);

	 			if($clientId != "" && $message != ""){
	 				$this->_saveSMSCanpaign($data, $clientId, $message, $campaignName);
	 			}

	 		}else{
	 			$error = "Invalid Format, allowed formats are csv, xls, xlsx, ods";
	 		}
	 	}else{
	 		$error = "Please Upload File";
	 	}

	 	redirect('/mailer/Mailer/responseSms/'.$error);
	 }

	/*
	 * Makes entry in smsCampaign table and forms data for it
	 * @params: data=>array($userEmail, $courseId), clentId [enterprise user], message [to sent to user]
	 */
	 private function _saveSMSCanpaign($data, $clientId, $message, $campaignName){
	 	$this->load->model('user/usermodel');
	 	$this->load->model('mailermodel');

	 	$date = new DateTime();
	 	
	 	$modelData = array();
	 	$emailIds = array();
		$userData = array();
	 	
	 	$i=0; $j=0;

		$id = $this->mailermodel->getLastAutoIncrementValue();

		foreach($data as $row=>$val){
			//$emailIds[] = "'".$val[0]."'";
			if(empty($val[0]) || empty($val[1])) {
				continue;
            }
			$excelData[$val[0]] = $val[1];
		} 
		
		$emailIds = array_keys($excelData);
        $new_ids = array();
		foreach($emailIds as $email) {
				$new_ids[] = "'".$email."'";
		}
        $emailIds = $new_ids;

		while($j < count($emailIds)){
			$chunkEmail = array_slice($emailIds, $j, 100);
			$userData = $this->usermodel->getUsersBasicInfoByEmail($chunkEmail);
			
			foreach($userData as $key=>$val){
				if($val['email'] == ''){
					continue;
				}

				$timeSequence = round(microtime(true));
	 			$saltKey = bcmul($timeSequence, $id);
	 			$uniqueKey = base_convert($saltKey,10,36);
	 			$id++;

	 			$landingURL = "shiksha.com/SR-".$uniqueKey;
	 			$message = str_replace("@landingURL",$landingURL,$message);
	 		
	 			$modelData[$i]['email'] = $val['email'];
	 			$modelData[$i]['userid'] = $val['userid'];
	 			$modelData[$i]['mobile'] = $val['mobile'];
	 			$modelData[$i]['clientId'] = $clientId;
	 			$modelData[$i]['campaignName'] = $campaignName;
	 			$modelData[$i]['message'] = $message;
	 			$modelData[$i]['courseId'] = $excelData[$val['email']];
	 			$modelData[$i]['savedDate'] = $date->format('Y-m-d H:i:s');
	 			$modelData[$i]['unique_key'] = $uniqueKey;
	 	 		$i++;	
			}

			$j += 100;	
		}

	 	$this->mailermodel->saveSMSCampaign($modelData);
	 }
	 /*
	 * Extracts data from smsCampaign table and form data accordingle
	 */
	 public function getSavedSMSCampaign(){
	 	$this->load->model('mailermodel');
	 	$this->load->model('user/usermodel');
	 	$clientId = $this->mailermodel->getAllSavedSMSCampaign();

	 	if(!empty($clientId)){
	 		$i=0;
	 		foreach($clientId as $row=>$value){
			 	$data[$i]['clientId'] = $value['clientId'];
			 	$data[$i]['campaignName'] = $value['campaignName'] ;
		 		$i++;
	 		}
		}

	 	return $data;
	 }

	 public function getScheduledCampaign(){
	 	$this->load->model('mailermodel');

	 	$savedCampaign= array();
	 	$savedCampaign = $this->mailermodel->getSavedSMSCampaign();
	 	return $savedCampaign;
	 }

	 /*
	 * Make's a new sms campaign from mailer interface
	 * Handles Ajax request
	 * @params: clientIds = array(), through POST
	 */
	 public function startSMSCampaign(){
	 	$campaignData = ($this->input->post('campaignName',true));
	 	$campaignData = json_decode($campaignData,true);

	 	if(empty($campaignData)){
	 		echo "failed";
	 		return;
	 	}

	 	$this->load->model('mailermodel');

	 	foreach($campaignData as $campaignName=>$campaignTime){
	 		$campaignTime.=":00";	//to make in tinme stamp hh:mm:ss
	 		$this->mailermodel->updateCampaignTime($campaignName,$campaignTime);
	 	}

	 	echo "saved";

		
	 }

	/*
	* send alert email to Aditya, Neha, Himanshu Gupta and Saurabh, if a test prep course is not mapped to any group
	*/

	 public function sendContactIssueRequest($userIds, $ExtraFlag, $mailReferer){
	 	$this->load->model('mailermodel');

	 	if($ExtraFlag == 'true'){
	 		$courseDetails = $this->mailermodel->getBlogAcronymForNonGrouped($userIds);
		 	foreach($courseDetails as $rows=>$cols){
		 		$courses[] = $cols['acronym'];
		 		$courseId[] = $cols['blogId'];
		 	}
		 	$courseType = "Test Prep Course(s) or Exam(s)"; $courseID = "test prep Id(s)";
	 	}else{
	 		$courseDetails = $this->mailermodel->getSpecializationDetails($userIds);
	 		foreach($courseDetails as $rows=>$cols){
	 			$courses[] = $cols['CourseName'];
	 			$courseId[] = $cols['SpecializationId'];
	 		}
	 		$courseType = "course(s)"; $courseID = "course Id(s)";
	 	}
	 	
	 	$message = "";
	 	
	 	$courses = implode(',', $courses);
	 	$courseId = implode(',',$courseId);
	 	$userIds = implode(',',$userIds);
	 	$cookievalue = explode("|",$_COOKIE['user']);
	 	$clientEmail = $cookievalue[0];

	 	// $receiverEmails = array("aditya.roshan@shiksha.com", "saurabh.gupta@shiksha.com","gupta.himanshu@shiksha.com");
	 	$receiverEmails = array("aditya.roshan@shiksha.com","harshit.mago@shiksha.com");
	 	
	 	$subject = "Some courses are not mapped to any group.";
	 	$message = "Please note that ".$courseType." ".$courses.", and their ".$courseID." ".$courseId. " is/are not mapped to any group and page referer =".$mailReferer."<br /> list of user in the search criteria are ".$userIds."<br />".$clientEmail." is the client who is facing problem";

		$fromAddress = 'info@shiksha.com';
		
		$this->load->library('Alerts_client');
	 	$alertClient = new Alerts_client();

	 	foreach($receiverEmails as $row=>$mailTo){
	 		$alertClient->externalQueueAdd(12,$fromAddress,$mailTo,$subject,$message,"html");
	 	}

	 }

	 /*
	 * Update status to delected of a campaign in smsCampaign
	 * @params: campaign Name
	 */
	 public function deleteCampaign(){
	 	$campaignName = $this->input->post('campaignName',true);

	 	$this->load->model('mailermodel');
	 	if($this->mailermodel->deleteSMSCampaign($campaignName)){
	 		echo "1";
	 	}else{
	 		echo "0";
	 	}
	 }

	 
	/*
	* create sms response and track it
	*/

	public function GSR($key) {

		if(!empty($key)) {
			
			$this->load->model('mailer/mailermodel');
			$campaignDetails = $this->mailermodel->getSMSCampaignDataById($key);

			if(!empty($campaignDetails)) {
				
				$campaignId = $campaignDetails['id'];
				$userId = $campaignDetails['userId'];
				$courseId = $campaignDetails['courseId'];
				
				if($userId > 0 && $courseId > 0) {
					
					// Get User Details for Auto Login
					$this->load->model('user/usermodel');
					$userModel = new UserModel;
					$user = $userModel->getUserById($userId);

					$displayname = $user->getDisplayName();
					$mobile = $user->getMobile();
					$password = $user->getEpassword();
					$email = $user->getEmail();
				
					$cookie = $email."|".$password;
					setcookie('user',$cookie,0,'/',COOKIEDOMAIN);
		
					$userStatus =  $this->checkUserValidation($cookie);

					$type = 'course';
					$action = 'Clicked_on_SMS';
					
					if ($userStatus != 'false') {						
						
						$tracking_key_id = "";
						if(isMobileRequest()){							
							$national_course_lib = $this->load->library('listing/NationalCourseLib');
							$course_type = $national_course_lib->getCourseTypeById($addReqInfo['listing_type_id']);							
							if($course_type == 'testprep') {
								$tracking_key_id = 711;
							} else if($course_type == 'domestic') {
								$tracking_key_id = 709;
							} else if($course_type == 'abroad') {
								$tracking_key_id = 710;
							}							
						}						
						// Get Course Listing Data
						$this->createResponse($courseId, $userStatus, $email, $type, $action,$tracking_key_id);						
						// Tracking of Sms Clicked by user
						$this->doSMSTracking($campaignId, $userId, $courseId);
					}
					
					$redirectUrl = SHIKSHA_HOME_URL.'/getListingDetail/'.$courseId.'/'.$type.'?city='.$instituteCityId.'&locality='.$instituteLocalityId;
					
					$URLQuery = parse_url($redirectUrl,PHP_URL_QUERY);
					if($URLQuery) {
						$redirectUrl .= '&utm_source=shiksha&utm_medium=sms&utm_campaign='.$action;
					}
					else {
						$redirectUrl .= '?utm_source=shiksha&utm_medium=sms&utm_campaign='.$action;
					}

					echo "<script>alert('Thank you for showing your interest!!');location.href = '".$redirectUrl."' ;</script>";
				} else {
					header("Location:".SHIKSHA_HOME_URL);
				}
			} else {
				header("Location:".SHIKSHA_HOME_URL);
			}
		} else {
			header("Location:".SHIKSHA_HOME_URL);
		}
	}
	
	public function createResponse($courseId, $userStatus, $email, $type, $action,$tracking_key_id = "") {

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		$course = $courseRepository->find($courseId);
		$courseName = $course->getName();
		$courseURL = $course->getURL();
		$isPaid = $course->isPaid();
		$listing_subscription_type = 'free';
		if($isPaid) {
			$listing_subscription_type = 'paid';
		}

		$location = $course->getMainLocation();
		$instituteCityId =  $location->getCity()?$location->getCity()->getId():0;
		$instituteLocalityId =  $location->getLocality()?$location->getLocality()->getId():0;
		
		// Response Data Gathering
		$this->load->library(array('lmsLib'));
		$LmsClientObj = new LmsLib();
		
		$addReqInfo = array();
		$addReqInfo['listing_type'] = $type;
		$addReqInfo['listing_type_id'] = $courseId;
		$addReqInfo['preferred_city'] = $instituteCityId;
		$addReqInfo['preferred_locality'] = $instituteLocalityId;
		$addReqInfo['displayName'] = $userStatus[0]['displayname'];
		$addReqInfo['contact_cell'] = $userStatus[0]['mobile'];
		$addReqInfo['userId'] = $userStatus[0]['userid'];
		$addReqInfo['contact_email'] = $email;
		$addReqInfo['action'] = $action;
		$addReqInfo['userInfo'] = json_encode($userStatus);
		$addReqInfo['sendMail'] = false;
		$addReqInfo['listing_subscription_type'] = $listing_subscription_type;		
		if(!empty($tracking_key_id)) {			
			$addReqInfo['trackingPageKeyId'] = $tracking_key_id;
		}		
		// Response Creation Done here...
		$addLeadStatus1 = $LmsClientObj->insertTempLead($this->appId,$addReqInfo);	
		$addReqInfo['tempLmsRequest'] = $addLeadStatus1['leadId'];	 
		$addLeadStatus = $LmsClientObj->insertLead($this->appId,$addReqInfo);
	}
	
	public function doSMSTracking($campaignId, $userId, $courseId) {
		$this->load->model('mailer/mailermodel');
		
		$trackingData = array();
		$trackingData['campaign_id'] = $campaignId;
		$trackingData['user_id'] = $userId;
		$trackingData['course_id'] = $courseId;
		$this->mailermodel->SMSCampaignTracking($trackingData);
	}

	function cronAddCampaignInSmsQueue(){
		$this->validateCron();
		$this->load->model('mailer/mailermodel');

		$dataForSmsQueue = $this->mailermodel->fetchScheduledCampaign();

		$this->load->model('SMS/smsModel');
		$smsModel_ob = new smsModel();
		foreach ($dataForSmsQueue as $key => $value) {
			$smsModel_ob->addSmsQueueRecord("1", $value['mobile'], $value['message'], $value['userId'], 0, 'shiksha');
			$this->mailermodel->updateSmsCampaignStatus($value['id']);
		}
	}

	// Manage Mails Page
	function manageMails($prodId)
	{
		$cmsUserInfo = $this->cmsUserValidation();
		$userId = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$this->init();

		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];
		$adminType = $userGroupData['user_type'];

		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userId;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;

		$cmsPageArr['allAdminData'] = $this->mailermodel->getAllAdminData();
		
		$status = "'draft','false'";
		$mailersData = $this->mailermodel->getMailersByStatus($status, $userId, $groupId, $adminType, 'client');
		if(!empty($mailersData)) {
			$mailerIds = array();
			foreach($mailersData as $row) {
				$mailers[$row['mailsSent']][] = $row;
				$campaignIds[] = $row['campaignId'];
			}
			$mailerSchedulerModel = $this->load->model('mailer/mailerschedulermodel');
			$campaignDetails = $mailerSchedulerModel->getCampaignDetails($campaignIds);
			$campaignNames = array();
			foreach($campaignDetails as $campaignDetail) {
				$campaignNames[$campaignDetail['id']] = $campaignDetail['name'];
			}
		}

		$cmsPageArr['mailers'] = $mailers;
		$cmsPageArr['campaignNames'] = $campaignNames;
		$this->load->view('mailer/manageMails',$cmsPageArr);
	}

	public function updateMailerDetails()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		
		$var_result = array();
		$var_result['userid'] = $userid;
		$var_result['usergroup'] = $usergroup;
		$var_result['thisUrl'] = $thisUrl;
		$var_result['validateuser'] = $validity;
		$var_result['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$var_result['myProducts'] = $cmsUserInfo['myProducts'];
		$var_result['prodId'] = $this->prodId;
		$var_result['cmsUserInfo'] = $cmsUserInfo;
		
		$sumsData = $this->input->post('sumsData',true);
		$mails_limit_text = $this->input->post('mails_limit_text',true);
		$mails_limit = $this->input->post('mails_limit',true);
		$temp_id = $this->input->post('temp_id',true);

		if (!empty($mails_limit_text) && is_numeric($mails_limit_text)) {
			$numUsers = intval($mails_limit_text);
		} else {
			$numUsers = $mails_limit;
		}
		
		$mailSchedule = $this->input->post('mailSchedule',true);
		$trans_start_date = $this->input->post('trans_start_date',true);
		$mailScheduleHours = $this->input->post('mailScheule_hours',true);
		$mailScheduleMinutes = $this->input->post('mailScheule_minutes',true);
		if($mailSchedule == 'immediately') {
			$trans_start_date = date('Y-m-d H:i:s');
		} else {
			$trans_start_date = $trans_start_date." ".$mailScheduleHours.":".$mailScheduleMinutes.":00";
		}
		$mailerName = $this->input->post('temp1_name',true);
		$mailerName = htmlentities(strip_tags($mailerName));
		$sender_name = $this->input->post('sender_name',true);
		$senderEmail = $this->input->post('userFeedbackEmail',true);
		$mailStatus = $this->input->post('mailStatus',true);
		$mailerId = $this->input->post('mailerId',true);

		$subject = '';
		if($mailStatus == 'false') {
			$templateDetails = $this->mailermodel->getTestMailData($temp_id);
			$subject = $templateDetails['subject'];
		}

		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];

		$objmailerClient = new MailerClient;
		$result = $objmailerClient->updateMailerDetails($this->appId, $mailerName, $trans_start_date, $senderEmail, $userid, $sumsData, $numUsers, $sender_name, $mailStatus, $mailerId, $subject);

		error_log("CONSUME RETURN ".print_r($result,true));

		if ( !($result['ERROR'] == 1)) {
			$var_result['trans_start_date'] = $trans_start_date;
			$var_result['temp1_name'] = $mailerName;
			$var_result['mailStatus'] = $mailStatus;
			$this->load->view('mailer/Summary_mailer_Result_template',$var_result);
		} else {
			$var_result['sumsData'] = $sumsData;
			$var_result['numUsers'] = $mails_limit;
			$var_result['error'] = 'TRUE';
			
			$mailerDetails = $this->mailermodel->getMailerDetailsByMailerId($mailerId, 'draft');
			$var_result['mailerDetails'] = $mailerDetails;
			$this->load->view('mailer/Edit_Userset_template',$var_result);
		}
	}

	public function cancelMailerDetails()	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		
		$cancelledMailers = $this->input->post('scheduledMailer',true);
		foreach ($cancelledMailers as $cancelledMailer) {
			if(!(!empty($cancelledMailer) && is_numeric($cancelledMailer) && $cancelledMailer > 0)){	
				return;
			}
		}
		
		$dripParents = array();
		$mailersToCancel = array();
		if(!empty($cancelledMailers)) {
			$mailerDetails = $this->mailermodel->getMultipleMailerDetails($cancelledMailers);
			foreach($mailerDetails as $mailerDetail) {
				if(empty($mailerDetail['parentMailerId'])){
					$dripParents[$mailerDetail['id']] = $mailerDetail['id'];
				}
				$mailersToCancel[$mailerDetail['id']] = $mailerDetail['id'];
			}

			if(!empty($dripParents)){
				$parentMailerData = $this->mailermodel->getDripMailersFromParentMailers($dripParents);
				foreach ($parentMailerData as $key => $value) {
					$mailersToCancel[$value['id']] = $value['id'];
				}
			}

			$newStatus = 'cancel';
			$previousStatus = 'false';
			$this->mailermodel->updateMailersStatusByMailerId($mailersToCancel, $newStatus, $previousStatus);
			$this->mailermodel->freeLockForCancelledMailer($mailersToCancel);

			header('location: '.SHIKSHA_HOME.'/mailer/Mailer/manageMails');
			exit();
		}

	}

	public function deleteUserSet($usersetId){
		$userStatus = $this->checkLoggedInUser();
		if(!empty($userStatus[0]['userid'])) {
			$this->mailermodel = $this->loadModel();
			if($usersetId > 0){
				$this->mailermodel->deleteUserSet($usersetId);
				echo "success";
				exit();
			}
		}
	}

	public function deleteTemplate($templateId,$templateType){
		$userStatus = $this->checkLoggedInUser();
		if(!empty($userStatus[0]['userid'])) {
			$this->mailermodel = $this->loadModel();
			if($templateId > 0 && !empty($templateType)){
				$this->mailermodel->deleteTemplate($templateId,$templateType);
				echo "success";
				exit();
			}
		}
	}

	public function updateLiveData() {

		$mailerModel = $this->load->model('mailer/mailermodel');
		$userSets = $mailerModel->getallProfileUserSets();
		$usersetIds = array();
		foreach($userSets as $userset) {
			$criteriaJSON = json_decode($userset['criteriaJSON'], true);
			if($criteriaJSON['usersettype'] == 'Profile' && $criteriaJSON['country'] == 'abroad') {
				$usersetIds[] = $userset['id'];
			}
		}
		
		if(!empty($usersetIds)) {
			$mailerModel->updateProfileUserSets($usersetIds);
		}
		echo 'Cron completed';
	}

	public function getMailReport($mailerId) {

		ini_set('memory_limit','1000M');

		if($mailerId > 0) {
			$subject = 'Mail Report for Mailer Id : '.$mailerId;
			$message = 'Please find attached mail report.';
		    $to = 'saurabh.jain@shiksha.com,mahesh.mahajan@shiksha.com,ruchika.rathee@shiksha.com,gurpreet.singh1@shiksha.com'; 

			$csv = array(); $userIds = array();
			$csv[] = array('User Id', 'Email', 'Action Type', 'Mobile');
			
			$mailerModel = $this->load->model('mailer/mailermodel');
			$mailData = $mailerModel->getMailReport($mailerId);

			if(!empty($mailData)) {
				foreach($mailData as $data){
					$trackingType = 'Open';
					if($data['trackerId'] != '') {
						$trackingType = 'Click';
					}
					$csv[$data['userId']] = array($data['userId'], $data['emailId'], $trackingType);
					$userIds[] = $data['userId'];
				}

				if(!empty($userIds)) {
					$usermodelObj = $this->load->model('user/usermodel');
					$userData = $usermodelObj->getUsersBasicInfoById($userIds);
					foreach($userData as $userId=>$user) {
						$csv[$userId][] =  $user['mobile'];
					}
				}
			   
			    $this->load->library('CollegeReviewForm/CollegeReviewLib');
				$this->crLib = new CollegeReviewLib;
		        $this->crLib->send_csv_mail($csv,$message, $to, $subject, 'info@shiksha.com');

		        echo 'Mail Report Sent.';
	    	}	
	    }
	}

	public function processRedirectUrl($redirectUrl,$cookieData,$widgetName){
		$oldUnsubscribeUrl = 0;
		if(strpos($redirectUrl, 'userprofile/edit?selected_tab=1') !== false){
			$redirectUrl = str_replace("selected_tab=1", "unscr=5", $redirectUrl);
			$oldUnsubscribeUrl = 1;
		}

		if($oldUnsubscribeUrl || strpos($redirectUrl, 'userprofile/edit?unscr=') !== false){
			$widgetName = 'unsubscribe';
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
        return array('redirectUrl' => $redirectUrl, 'widgetName' => $widgetName);
	}

	public function populateMailerTracking(){
		$this->validateCron();
		ini_set('memory_limit','1024M');
		$mailerCronLibrary = $this->load->library('MailerCronLibrary');
		$mailerCronLibrary->updateProcessedAndSentMailsData();
		$mailerCronLibrary->updateUserActionData();

		error_log("cron done");
	}

	private function downloadMisReport($reportData,$userIds){
		$downloadData = $this->getColumnNameForCSV('MisReport');
		$downloadData .= $this->_createCSV($reportData);
		$this->downloadCSV($downloadData,'MisReport');
	}
	public  function populateMailerClientId(){
		$this->validateCron();
		ini_set('memory_limit','512M');
		$mailerModel = $this->load->model('mailer/mailermodel');
		$subscriptionDetails = $mailerModel->getSubscriptionDetails();
		$mailerWiseClientIds = array();
		foreach ($subscriptionDetails as $key => $mailerSubscriptionInfo) {
			$clientDetails = array();
			if(empty($mailerSubscriptionInfo['subscriptionDetails'])  ){
				continue;
			}
			$clientDetails['id'] = $mailerSubscriptionInfo['id'];
			$subscriptionInfo  = base64_decode($mailerSubscriptionInfo['subscriptionDetails']);
			$subscriptionInfo  = json_decode($subscriptionInfo,true);
			$clientDetails['clientId'] = $subscriptionInfo['clientUser'];
			if(empty($clientDetails['clientId'])){
				continue;
			}
			$mailerWiseClientIds[]=$clientDetails;
		}
		$mailerModel->populateMailerClientId($mailerWiseClientIds);
	}
	public function migrateMailerMisData(){
		$this->validateCron();
		ini_set('memory_limit','1024M');
		$mailerModel = $this->load->model('mailer/mailermodel');
		
		$id = 303;
		$batchSize = 1000;

		while(1){
			$data = $mailerModel->getMailerMisOldData($id,$batchSize);
			if(empty($data)){
				break;
			}
			$id = end($data)['id'];
			$mailerModel->populateNewMailerMisTable($data);
			usleep(200000);
		} 
	}



}
 

