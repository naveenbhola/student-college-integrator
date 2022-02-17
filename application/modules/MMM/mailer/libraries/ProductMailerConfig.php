<?php

class ProductMailerConfig
{
	private static $MailersConfigMap = array(
		6548 => array(
					'Name'                => 'MRecBAbroad',
					'Frequency'           => 3,
					'ArticleTagging'      => False,
					'Subjects'            => array(),
					'TriggerResetActions' => array('mailOpened','mailClicked','questionPosted','responseCreated','profileCompleted'),
					'TotalDays' => '-3 months',
					'UsersType' => 'registration'),
		22093 => array('Name' => 'MRecNationalRegistration',
					'Frequency' => 1,
					'ArticleTagging' => True,
					'Subjects' => array(),
					'TriggerResetActions' => array('mailOpened','mailClicked','questionPosted'),
					'TotalDays'=> '-90 days',
					'UsersType' => 'registration'),
		22094 => array('Name' => 'MRecNationalResponse',
					'Frequency' => 1,
					'ArticleTagging' => True,
					'Subjects' => array(),
					'TriggerResetActions' => array('mailOpened','mailClicked','questionPosted'),
					'TotalDays'=> '-90 days',
					'UsersType' => 'response'),
		56485 => array('Name' => 'examAlertMailer',
					'Frequency' => 1,
					'ArticleTagging' => False,
					'Subjects' => array(),
					'TriggerResetActions' => array('mailOpened','mailClicked','questionPosted'),
					'TotalDays'=> '-180 days',
					'UsersType' => 'registration')
	);

	public static function getItem($mailerId, $item)
	{
		$mailerConfig = self::$MailersConfigMap[$mailerId];
		
		if(!empty($mailerConfig) && !empty($mailerConfig[$item])) {
			return $mailerConfig[$item];
		}
		else {
			return FALSE;
		}
	}

	/*
		$mailersForArticleTagging = array(
			'4406' => 'MRecA',
			'4407' => 'MRecB',
			'4408' => 'MCont',
		);
	*/
	public static function getMailersForArticleTagging()
	{
		$mailersForArticleTagging = array();
		foreach (self::$MailersConfigMap as $mailerId => $mailerConfig) {
			if(!empty($mailerConfig['ArticleTagging'])) {
				$mailersForArticleTagging[$mailerId] = $mailerConfig['Name'];
			}
		}
		return $mailersForArticleTagging;
	}

	/*
		$actionMailerMap = array('mailOpened' => array(4405,4406,4407,4408),
								'mailClicked' => array(4405,4406,4407,4408),
								'questionPosted' => array(4405,4406,4407,4408),
								'responseCreated' => array(4407));
	*/
	public static function getMailersForTriggerResetAction()
	{
		$actionMailerMap = array();
		foreach (self::$MailersConfigMap as $mailerId => $mailerConfig) {
			if(!empty($mailerConfig['TriggerResetActions'])) {
				foreach ($mailerConfig['TriggerResetActions'] as $resetAction) {
					$actionMailerMap[$resetAction][] = $mailerId;
				}
			}
		}
		return $actionMailerMap;
	}

	/*
		$subjectsForMailers = array(
			'4406' => array(1=>abc,2=>def),
			'4407' => array(1=>xyz,2=>pkl)
		);
	*/
	public static function getSubjectsForMailers()
	{
		$subjectsForMailers = array();
		foreach (self::$MailersConfigMap as $mailerId => $mailerConfig) {
			if(!empty($mailerConfig['Subjects'])) {
				$subjectsForMailers[$mailerId] = $mailerConfig['Subjects'];
			}
		}
		return $subjectsForMailers;
	}

}
