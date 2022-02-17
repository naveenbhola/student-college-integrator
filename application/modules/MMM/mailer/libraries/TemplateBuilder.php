<?php

class TemplateBuilder
{
	private $mailer;
	private $mailerTemplateDataValidatorService;
	private $mailSubjectGeneratorService;
	private $mailerWidgetPostProcessorService;
	private $CI;

	function __construct(MailerTemplateDataValidatorService $mailerTemplateDataValidatorService, MailSubjectGeneratorService $mailSubjectGeneratorService,MailerWidgetPostProcessorService $mailerWidgetPostProcessorService)
	{
		$this->CI = & get_instance();
		$this->CI->load->library('mailer/MailerFactory');
		
		$this->mailerTemplateDataValidatorService = $mailerTemplateDataValidatorService;
		$this->mailSubjectGeneratorService = $mailSubjectGeneratorService;
		$this->mailerWidgetPostProcessorService = $mailerWidgetPostProcessorService;
	}

	public function setMailer(MailerEntity $mailer)
	{
		$this->mailer = $mailer;
	}
	
	public function buildTemplate(MailerTemplate $template,$userIds,$customData = array(),$processingParams = array(), $isUserSetMailer = false, $existingWidgetsData = array())
	{
		$templateVariables = $template->getVariables();
		
		$templateWidgets = array_keys($templateVariables);
		
		if(!in_array('tuser', $templateWidgets))
			$templateWidgets[] = 'tuser';
		
		unset($templateVariables);
		
		if($this->mailer) {
		    $subjectForUsers = $this->_getSubjectForProductMailer($userIds, $this->mailer);
		}

		$data = array();
		/**
		 * Get data for all variables in widgets present in the template
		 */
		foreach($templateWidgets as $widget) {
			if(!$existingWidgetsData[$widget]) {
				$widgetObj = MailerFactory::getWidgetObj($widget);
				if($widgetObj) {
					$widgetObj->setMailer($this->mailer);
					$data[$widget] = $widgetObj->getData($userIds, $processingParams);
				}
				unset($widgetObj);
			}
		}
		
		unset($templateWidgets);
		/**
		 * Merge data from different widgets
		 */
		$userData = $this->_mergedWidgetData($data);
		$userData = $this->mailerWidgetPostProcessorService->doPostProcessing($userData);

		if(!empty($data['NewsletterArticleList'])) {
			$newsletterArticleList = $data['NewsletterArticleList'];
		}
		unset($data);
		
		/**
		 * Add custom data to widgets data
		 */
		foreach($customData as $userId => $userCustomData) {
			foreach($userCustomData as $dataKey => $dataValue) {
				$userData[$userId][$dataKey] = $customData[$userId][$dataKey];
			}
		}
		unset($customData);

		foreach($subjectForUsers as $userId=>$subject){
		    $userData[$userId]['customSubject'] = $subject;
		}
		
		unset($subjectForUsers);

		$userTemplateData = array();
		if($isUserSetMailer){
			foreach($userData as $userId => $userWidgetData) {		
				if(!$this->_canBuildTemplate($userWidgetData)) {
					unset($userData[$userId]);
				}
			}
			if(!empty($newsletterArticleList)) {
				$userData['NewsletterArticleList'] = $newsletterArticleList;
			}

			unset($newsletterArticleList);
			return $userData;
		}else{
		
			$userTemplateData = $this->getTestMailUsersData($userData, $template, $newsletterArticleList);
			unset($userData);
			unset($newsletterArticleList);
			return $userTemplateData;
		}
	}

	private function getTestMailUsersData($userData, $template, $newsletterArticleList) {

		if(!empty($newsletterArticleList)) {

			$this->CI->load->config('mailer/mailerConfig');
			$mptHtmlHashkey = $this->CI->config->item('mptHtmlHashkey');

			if(strpos($newsletterArticleList, $mptHtmlHashkey) !== false) {
				try {
					$userIds = array_keys($userData);
					$mptHTML = Modules::run('MPT/MPTController/getMPTHtmlForUsers',$userIds);
				} catch(Exception $e) {
					mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com','Exception in getting HTML for MPT tupple (fn getTestMailUsersData in file TemplateBuilder.php) at '.date('Y-m-d H:i:s'), 'Exception: '.$e->getMessage().'<br/>Chunk:'.print_r($userIds, true));
				}
			}

		}

		foreach($userData as $userId => $userWidgetData) {
			if(!empty($newsletterArticleList)) {
				$userMPTHTML= '';
				if(!empty($mptHTML[$userId])) {
					$userMPTHTML = $mptHTML[$userId];
				}
				$userWidgetData['NewsletterArticleList'] = str_replace($mptHtmlHashkey, $userMPTHTML, $newsletterArticleList);
			}
			
			if($this->_canBuildTemplate($userWidgetData)) {
				$mailHTMLTemplate = $template->buildTemplate($userWidgetData);
				$mailSubject = $template->replaceSubject($userWidgetData);
				$userTemplateData[$userId] = array('template' => $mailHTMLTemplate, 'subject' => $mailSubject);
				unset($mailHTMLTemplate);
				unset($mailSubject);
			}
		}

		unset($userData);

		return $userTemplateData;
	}

	private function _mergedWidgetData($data)
	{
		$returnData = array();
		// First iteration with main key
		foreach ($data as $key => $mainData) {
			// Second iteration with userData
			foreach($mainData as $userId => $userData) {
				// Checking whther userId exists in returnData
				if (! isset($returnData[$userId])) {
					$returnData[$userId] = array();
				}
				$returnData[$userId] = array_merge($returnData[$userId], $userData);
			}
			unset($mainData);
		}
		unset($data);
		return $returnData;
	}
	
	private function _canBuildTemplate($data)
	{
		/**
		 * If no mailer is set, go ahead and build template with whatever data we have
		 */ 
		if(!$this->mailer) {
			return TRUE;
		}
		
		return $this->mailerTemplateDataValidatorService->validate($this->mailer,$data);
	}

	private function _getSubjectForProductMailer($userIds, $mailer)
	{
    		return $this->mailSubjectGeneratorService->generate($userIds, $mailer);
	}

	public function buildTemplateWithCSVData($templateId,$template,$csvData)
	{
		$mailHTMLTemplate = $template->buildTemplate($csvData,TRUE);
		$mailSubject = $template->buildSubject($csvData,TRUE);
		return array('template' => $mailHTMLTemplate, 'subject' => $mailSubject);
	}
}
