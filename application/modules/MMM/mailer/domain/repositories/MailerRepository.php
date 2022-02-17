<?php

class MailerRepository
{
	private $CI;
	private $model;

	function __construct($model)
	{
		$this->CI = & get_instance();
		$this->model = $model;
		$this->CI->load->entities(array('MailerEntity','MailerTemplate'),'mailer');
	}

	public function find($id)
	{
		$data = $this->model->getMailerById($id);
		return $this->buildMailerObject($data[0]);
	}
	
	public function getUnprocessedMailers($batch)
	{
		if($batch < 0){
			return false;
		}
		$mailers = array();
		$mailerDataset = $this->model->getUnprocessedMailers($batch);
		foreach($mailerDataset as $mailerData) {
			$mailerData['isProductMailer'] = False;
			$mailers[] = $this->buildMailerObject($mailerData);
		}

		unset($mailerDataset);
		return $mailers;
	}

	public function getProductMailers($batch)
	{
		$mailers = array();
		$mailerDataset = $this->model->getProductMailers($batch);
		foreach($mailerDataset as $mailerData) {
			$mailerData['isProductMailer'] = True;
			$mailers[] = $this->buildMailerObject($mailerData);
		}
		return $mailers;
	}

	private function buildMailerObject($mailerData)
	{
		$mailerData['templateVars'] = $this->model->getTemplateVars($mailerData['templateId']);
		$mailerData['template'] = new MailerTemplate($mailerData);
		return new MailerEntity($mailerData);
	}

}
