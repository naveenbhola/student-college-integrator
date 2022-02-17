<?php

class MailerWidgetAbstract
{
	protected $CI;
	protected $mailer;
	protected $mailerModel;

	public function __construct(MailerModel $mailerModel)
	{
		$this->CI =& get_instance();
		$this->mailerModel = $mailerModel;
	}
	
	public function getAutoLoginLink($encodedEmail)
	{
		$autologinUrl = THIS_CLIENT_IP."/mailer/Mailer/autoLogin/email~".$encodedEmail;
		return $autologinUrl;
	}

	public function setMailer(Mailer $mailer)
	{
		$this->mailer = $mailer;
	}
}