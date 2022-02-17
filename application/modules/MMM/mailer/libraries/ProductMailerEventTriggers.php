<?php

class ProductMailerEventTriggers
{
	private $CI;
	private $actionMailerMap;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model('mailer/mailermodel');
		$this->CI->load->library('mailer/ProductMailerConfig');
		$this->actionMailerMap = ProductMailerConfig::getMailersForTriggerResetAction();
	}

	public function resetMailerTriggers($userId, $actionType, $params=array())
	{
		if(!empty($userId) && !empty($actionType) && !empty($this->actionMailerMap[$actionType])) {
			$mailerIds = $this->actionMailerMap[$actionType];
			
			if($actionType == 'mailOpened' || $actionType == 'mailClicked') {
				if(!empty($params['mailerId']) && in_array($params['mailerId'],$mailerIds)) {
					$mailerIds = array($params['mailerId']);
				}
				else {
					$mailerIds = array();
				}
			}
			
			if(!empty($mailerIds)) {
				foreach ($mailerIds as $mailerId) {
					$dependMailers = ProductMailerConfig::getItem($mailerId, 'AlsoResetDependMailers');
					if(!empty($dependMailers)) {
						foreach ($dependMailers as $dependmailerId) {
							if(!in_array($dependmailerId,$mailerIds)) {
								$mailerIds[] = $dependmailerId;
							}
						}
					}
				}
				$this->CI->mailermodel->resetUserMailerSentTrigger($userId, $mailerIds);
			}
		}
	}

	public function updateMailerTriggers($userIds, $mailerId)
	{
		$this->CI->mailermodel->updateUserMailerSentTrigger($userIds, $mailerId);
	}

}
