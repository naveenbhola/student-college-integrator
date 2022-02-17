<?php

require_once('AbstractMailerProcessor.php');

class MailerProcessorCSV extends AbstractMailerProcessor
{
	function __construct(Mailer $mailer,TemplateBuilder $templateBuilder)
	{
		parent::__construct($mailer,$templateBuilder);
	}

	function process()
	{
		$template = $this->mailer->getTemplate();
		
		$listId = $this->mailer->getListId();
		$csvData = $this->mailerModel->getCSVData($listId);
		unset($listId);
		
		/**
		 * Truncate user set to the number to be sent for this mailer
		 */
		$csvData = $this->truncateUserSet($csvData);
		$this->mailerModel->updateNumberOfDripUserSet(count($csvData),$this->mailer->getId());
		
		/**
		 * Truncate already processed users, if any
		 */
		$csvData = $this->truncateProcessedUsers($csvData);
		
		/**
		 * Process in chunks
		 */
		$csvData = $this->chunkifyUserSet($csvData);
		
		foreach($csvData as $chunk) {

			$cnt = 0;
			
			$emailIds = array();
			foreach($chunk as $data) {
				$emailIds[] = "'".$data['email']."'";
			}

			$usersData = array();
			$usersData = $this->mailerModel->getUsersDataByEmailIds($emailIds);
			$userIds   = array();
			
			foreach($chunk as $key => $data) {
				if($usersData[$data['email']]['unsubscribeCategory'] == 5 || $usersData[$data['email']]['hardbounce'] == '1'){
					unset($chunk[$key]);
					unset($usersData[$data['email']]);
				} else {
					if($usersData[$data['email']]['userid'] > 0) {
						$userIds[] = $usersData[$data['email']]['userid'];
					}
				}
			}

			if(count($chunk)<=0){
				continue;
			}

			/**
			 * exclude users which are in counseling loop
			 */
			$this->CI->load->library(array('shikshaApplyCRM/rmcPostingLib'));
			$rmcLibObj                = new rmcPostingLib();
			$userCounsellingStatusMap = $rmcLibObj->getLeadsCounsellingStatusForMMM($userIds);

			foreach($chunk as $key => $data) {
				if($userCounsellingStatusMap[$usersData[$data['email']]['userid']]) {
					unset($chunk[$key]);
					unset($usersData[$data['email']]);
				}
			}
			
			/**
			 * First add all users in the chunk to mail queue
			 * using bulk insert
			 */
			$emailIdMailIdMap = $this->mailerModel->addCSVMailToQueue($chunk, $this->mailer->getId(),'csv',$this->mailer->getSenderMail(),$this->mailer->getSenderName(), $usersData);
			
			unset($usersData);
			
			/**
			 * Build template for each user in the chunk using CSV data
			 */
			foreach($chunk as $data) {
				$data['mailerId']    = $this->mailer->getId();
				$data['mailid']      = $emailIdMailIdMap[$data['email']]['mailid'];
				$data['encodedMail'] = $emailIdMailIdMap[$data['email']]['encodedMail'];
				//$templateData = $this->templateBuilder->buildTemplateWithCSVData($template,$data);
                $templateData = $this->templateBuilder->buildTemplateWithCSVData($template->getTemplateId(),$template,$data);
				$mailHTMLTemplate = $templateData['template'];
				$mailSubject = $templateData['subject'];
				unset($templateData);
				/**
				 * Add mail subject and content
				 */
				$this->mailerModel->updateCSVMailQueue($data['email'], $this->mailer->getId() , 'csv', $mailSubject,$mailHTMLTemplate);
				unset($data);
				unset($mailHTMLTemplate);
				unset($mailSubject);

				$cnt++;

			}
			$this->mailerModel->updateNumberProcessed($this->mailer->getId(), $cnt);
			$this->mailerModel->updateNumberProcessedDripCampaign($this->mailer->getId(), $cnt);

		}
		unset($emailIdMailIdMap);
		unset($csvData);
		return true;
	}
}
