<?php

abstract class AbstractMailerProcessor
{
	protected $CI;
	protected $mailer;
	protected $mailerModel;
	protected $templateBuilder;
	const CHUNKSIZE = 500;
	
	function __construct(Mailer $mailer,TemplateBuilder $templateBuilder)
	{
		$this->CI = & get_instance();
		$this->CI->load->model('mailer/mailermodel');
		$this->mailerModel = new mailermodel;
		$this->mailer = $mailer;
		$this->templateBuilder = $templateBuilder;
	}

	protected function truncateUserSet($userSet)
	{
		//truncate the number of userids to total Mails to be sent
		if(!$this->mailer->isProductMailer() && $this->mailer->getTotalMailsToBeSent() > 0) {
			$userSet = array_slice($userSet,0,$this->mailer->getTotalMailsToBeSent());
		}
		return $userSet;
	}

	protected function truncateProcessedUsers($userSet)
	{
		return array_slice($userSet, $this->mailer->getNumberProcessed());
	}

	protected function chunkifyUserSet($userSet)
	{
		return array_chunk($userSet, self::CHUNKSIZE);
	}

	abstract function process();
}