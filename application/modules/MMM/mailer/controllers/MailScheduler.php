<?php

class MailScheduler extends MX_Controller
{

	function init()
	{
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

	public function mailScheduleByClient($mailerId){
		
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		
		$data = array();
		$data['userid'] = $cmsUserInfo['userid'];
		$data['usergroup'] = $cmsUserInfo['usergroup'];
		$data['thisUrl'] = $cmsUserInfo['thisUrl'];
		$data['validateuser'] = $cmsUserInfo['validity'];
		$data['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$data['isEdit'] = 0;
		if(empty($mailerId)){
			$this->load->view('mailer/clientPage',$data);
		}else {
			$this->mailSchedulerMain($mailerId);
		}
	
	}

	public function replicateMailer($mailerId) {
		
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		
		$data = array();
		$data['userid'] = $cmsUserInfo['userid'];
		$data['usergroup'] = $cmsUserInfo['usergroup'];
		$data['thisUrl'] = $cmsUserInfo['thisUrl'];
		$data['validateuser'] = $cmsUserInfo['validity'];
		$data['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$data['isEdit'] = 0;
		$data['isReplicate'] = 0;
		$isDripCampaignDataRequired = false;

		$this->mailSchedulerMain($mailerId, $isDripCampaignDataRequired);
		
	
	}

	function mailSchedulerMain($mailerId, $isDripCampaignDataRequired = true){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();

		$clientId = $this->input->post('clientId',true);
		$emailId = $this->input->post('emailId',true);

		$mailSchedulerLibrary =  $this->load->library('MailSchedulerLibrary');

		$data = array();
		$data['isEdit'] = 0;
		$data['isReplicate'] = 0;
		if(!empty($mailerId)){
			$status = 'draft';
			if($isDripCampaignDataRequired === false) {
				$status = 'true';
			}
			$data['savedMailerData'] = $mailSchedulerLibrary->getsavedMailerData($mailerId, $isDripCampaignDataRequired, $status);
			
			$clientId = $data['savedMailerData']['parentMailer']['clientId'];
			if($isDripCampaignDataRequired == false) {
				$data['isReplicate'] = 1;
			} else {
				$data['isEdit'] = 1;
			}
		}

		

		$data['userid'] = $cmsUserInfo['userid'];
		$data['usergroup'] = $cmsUserInfo['usergroup'];
		$data['thisUrl'] = $cmsUserInfo['thisUrl'];
		$data['validateuser'] = $cmsUserInfo['validity'];
		$data['headerTabs'] =  $cmsUserInfo['headerTabs'];

		if(empty($clientId) && empty($emailId)){
			echo "error";
			return;
		}

		if(!empty($clientId)){
			$clientDetails = $mailSchedulerLibrary->getClientDetails($clientId,'clientId');
		}
		else if(!empty($emailId)){
			$clientDetails = $mailSchedulerLibrary->getClientDetails($emailId,'emailId');
		}

		if(empty($clientDetails)){
			echo "error";
			return;
		}

		$data['clientDetails'] = $clientDetails;
        $this->load->config('mailer/mailerConfig');
        $data['mailerAdminUserId'] = $this->config->item('mailerAdminUserId'); 
        $userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];
		$adminType = $userGroupData['user_type'];
		$userid = $cmsUserInfo['userid'];
		
		$allTemplates = $mailSchedulerLibrary->getTemplatesByUserId($userid,$groupId,$adminType);
		$data['templateData'] = $allTemplates;
		$data['senderEmailIds'] = $this->config->item('senderEmailIds');

		$data['usersets']=$mailSchedulerLibrary->getAllUserSearchCriteria($data['userid'], $groupId, $adminType);

		$data['mailCriteria'] = 1;

		$this->load->view('mailer/mailScheduleMainPage',$data);

	}

	function processUserSetCSV($schedule =0){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$templateId = $this->input->post('temp_id',true);
		$templateType = $this->input->post('templateType',true);
		$downloadCheck  = $this->input->post('download_check',true);
		$mailSchedulerLibrary =  $this->load->library('MailSchedulerLibrary');
		
		if(isset($_FILES['files']) ){
			
			$csvArr = array();
			$skipCheck = FALSE;
			$tempFileName = "";
			$returnData = $mailSchedulerLibrary->processUserSetCSV($_FILES['files']['tmp_name'],$templateType,$downloadCheck,$schedule,$userid,$usergroup);
			if( $downloadCheck  == 1){
				if(!empty($returnData['error'])){
					$returnData = $returnData['error'];
				}
					$this->downloadCSV($returnData,'InvalidEmailIds.csv');
				
			} else {
				echo json_encode($returnData);
			}
		}
	}

	function downloadCSV($data,$filename){

		$mime = 'text/csv';
		$csvSTring = $data;

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

	function sendTestMail($templateId, $email, $mailSendingMethod, $mailSubject)
	{
		if((empty($templateId)) || (empty($email))) {
			echo "-1";
		}
		else{
			$this->load->library('mailer/MailerFactory');

			$this->load->model('mailermodel');
			$mailermodelObj = new mailermodel();
			
			/**
			 * Get template from template id
			 */
			$status  = $mailermodelObj->isValidEmailHardbouceCheck($email);
			// $status='dasda';
			if($status === true){		
				$mailerTemplateRepository = MailerFactory::getMailerTemplateRepository();
				$template = $mailerTemplateRepository->getMailerTemplate($templateId);
				$processingParams = array();
				$processingParams['templateId'] = $templateId;
				
				$userId = (int) $mailermodelObj->getUserId($email);
				// _P($userId);die;return;
				if($userId){
					$templateBuilder = MailerFactory::getTemplateBuilder();
					$templateData = $templateBuilder->buildTemplate($template,array($userId),array($userId => array('email' => $email)), $processingParams);
					// _P($templateData);die;return;
					$mailTemplate = $templateData[$userId]['template'];
					// $mailSubject = $templateData[$userId]['subject'];
				}
				else {
					$mailTemplate = $template->getTemplate();
					// $mailSubject = $template->getSubject();
				}
				// _P($mailSubject);die;
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= 'From: <info@shiksha.com>' . "\r\n";

				$mailTemplate = parseUrlFromContent($mailTemplate);

				if($mailSendingMethod == 'shiksha') {
					$val = false;
					$mailermodelObj->saveTestMail($userId, $mailSubject, $mailTemplate,$email,$val);
				}
				else{
					$mailermodelObj->saveTestMailByAmazon($userId, $mailSubject, $mailTemplate, $email);
				}

			}
			else{
				echo "-2";
			}
		}

	}


	function getAddMoreMailCriteria($mailCriteria){
		$existingPageData = $this->input->post('existingPageData',true);
		$existingPageData  = json_decode($existingPageData,true);
		if(!empty($mailCriteria) && !empty($existingPageData)){
			$existingPageData['mailCriteria'] = $mailCriteria;
			$this->load->config('mailer/mailerConfig');
			$existingPageData['mailerAdminUserId'] = $this->config->item('mailerAdminUserId'); 
			echo $this->load->view('mailer/MailSchedulerWidgets/mailCriteria',$existingPageData,true);
		}
	}
	

	public function saveMailerInformation($save=0,$schedule =0,$mailerCriteria,$campaignName,$clientId,$listId,$campaignId,$isEdit,$isUploadCsv)
	{
		$this->load->config('mailer/mailerConfig');
        $indexToCampaignMapping = $this->config->item('indexToCampaignMapping'); 

		$mailerName = $this->input->post('mailer_name');
		$indexOfConfigMapping=0;
		$mailSchedulerLibrary =  $this->load->library('MailSchedulerLibrary');
		while ($indexOfConfigMapping !=5){

			$mailerDetails = $mailSchedulerLibrary->getMailerDetailsFromPost($indexOfConfigMapping,$mailerName,$indexToCampaignMapping,$mailerCriteria,$clientId,$save,$listId,$isUploadCsv);
			$details[$indexToCampaignMapping[$indexOfConfigMapping]] = $mailerDetails["mailerData"] ;
			$subscriptionData[] = $mailerDetails["subscriptionData"];
			$templateMapping[$indexToCampaignMapping[$indexOfConfigMapping]] = $mailerDetails["mailerData"]["templateId"];
			
			$indexOfConfigMapping++;
		}  
		$this->load->model('mailerschedulermodel');
		$mailermodelObj = new mailerschedulermodel();
		$mailerIds[] =array(); 
		if ($save ==1){
			if ($campaignId <= 0 ){
				$campaignId = $mailermodelObj->insertCampaignName($campaignName,$save);
			}
			$parentMailerId = $mailermodelObj->saveMailerInformation($details["parent"],$campaignId,"",$save);
			$mailerIds[]= $parentMailerId;
			foreach ($details as $key => $value) {
				if ($key == "parent"){
					continue;
				}
				$mailerIds[] = $mailermodelObj->saveMailerInformation($value,$campaignId,$parentMailerId,$save);
			}
			if($isEdit == 1){
				 $allMailerIds = $this->input->post("mailer_id");
				 $mailerIdsToDelete = array_diff($allMailerIds,$mailerIds);
				 $mailermodelObj->updateUnselectedMailers($mailerIdsToDelete,$save);
			}
			echo json_encode(array("responseText"=>"1","campaignId"=>$campaignId));
		}
		else{
			$returnValue =  $this->lockCredits($subscriptionData,$details,$campaignName,$templateMapping,$campaignId,$isEdit);
			echo json_encode($returnValue);
			
		}

	}

	public function lockCredits($subscriptionData,$details,$campaignName,$templateMapping,$campaignId,$isEdit){
		$mailSchedulerLibrary =  $this->load->library('MailSchedulerLibrary');
		return $mailSchedulerLibrary->lockCredits($subscriptionData,$details,$campaignName,$templateMapping,$campaignId,$isEdit);
	}

	public function resendMailer($parentMailerId){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$mailSchedulerLibrary =  $this->load->library('MailSchedulerLibrary');
		$this->load->config('mailer/mailerConfig');
		$indexToCampaignMapping = $this->config->item('indexToCampaignMapping');
		$dataForMailer =  $mailSchedulerLibrary->getParentMailerDetailsForResending($parentMailerId,$indexToCampaignMapping);
		$data = array();
		$data['userid'] = $cmsUserInfo['userid'];
		$data['usergroup'] = $cmsUserInfo['usergroup'];
		$data['thisUrl'] = $cmsUserInfo['thisUrl'];
		$data['validateuser'] = $cmsUserInfo['validity'];
		$data['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$data['mailerData'] = $dataForMailer;
    	$data['mailerData']['mailerAdminUserId'] = $this->config->item('mailerAdminUserId'); 
		$data["resendMailer"] =1;
		$userGroupData = $this->userGroupData;
		$groupId = $userGroupData['group_id'];
		$adminType = $userGroupData['user_type'];
		$userid = $cmsUserInfo['userid'];

		$allTemplates = $mailSchedulerLibrary->getTemplatesByUserId($userid,$groupId,$adminType);
		$data['templateData'] = $allTemplates;
		$data['clientDetails'] = $mailSchedulerLibrary->getClientDetails($dataForMailer['parentMailerData']['clientId'],'clientId');
	
		$this->load->view("mailer/clientPage",$data);
	}

	public function indexMMMMailsInES(){
		$this->validateCron();
		ini_set('memory_limit', '1024M');
		
		$mailSchedulerLibrary =  $this->load->library('MailSchedulerLibrary');
		$mailSchedulerLibrary->indexMMMMailerES();
		sleep(5);
		$mailSchedulerLibrary->updateOpenAndClickRateInES();		
	}
}

?>
