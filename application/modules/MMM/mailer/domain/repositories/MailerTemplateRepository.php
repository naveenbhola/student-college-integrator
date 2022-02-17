<?php

class MailerTemplateRepository
{
	private $CI;
	private $model;

	function __construct($model)
	{
		$this->CI = & get_instance();
		$this->model = $model;
		$this->CI->load->entities(array('MailerEntity','MailerTemplate'),'mailer');
	}

	public function getMailerTemplate($templateId)
	{
		$mailerTemplateData = $this->model->getTemplateData($templateId);
		return new MailerTemplate($mailerTemplateData);
	}
}
