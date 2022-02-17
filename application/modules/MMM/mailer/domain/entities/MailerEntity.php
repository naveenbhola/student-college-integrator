<?php

class MailerEntity
{

	private $mailerId;

	private $mailerName;

	private $listId;

	private $mailerCriteria;

	private $template;

	private $senderMail;

    private $senderName;

	private $numberprocessed;

	private $totalMailsToBeSent;

	private $isProductMailer;

	private $lastProcessedTimeWindow;

	private $templateCriteria;

	private $parentMailerId;

	private $dripMailerType;

	private $clientId;

	public function __construct($mailerData)
	{
		$this->mailerId                = $mailerData['mailerId'];
		$this->mailerName              = $mailerData['mailerName'];
		$this->listId                  = $mailerData['listId'];
		$this->mailerCriteria          = $mailerData['criteria'];
		$this->template                = $mailerData['template'];
		$this->senderMail              = $mailerData['senderMail'];
		$this->numberprocessed         = $mailerData['numberprocessed'];
		$this->totalMailsToBeSent      = $mailerData['totalMailsToBeSent'];
		$this->isProductMailer         = $mailerData['isProductMailer'];
		$this->lastProcessedTimeWindow = $mailerData['last_processed_time_window'];
		$this->templateCriteria        = $mailerData['templateCriteria'];
		$this->senderName              = $mailerData['sendername'];
		$this->parentMailerId		   = $mailerData['parentMailerId'];
		$this->dripMailerType		   = $mailerData['dripMailerType'];
		$this->clientId 			   = $mailerData['clientId'];
	}

    public function getSenderName()
    {
        return $this->senderName;
    }

	public function getTotalMailsToBeSent()
	{
		return $this->totalMailsToBeSent;
	}

	public function getId()
	{
		return $this->mailerId;
	}

	public function getNumberProcessed()
	{
		return $this->numberprocessed;
	}

	public function getName()
	{
		return $this->mailerName;
	}

	public function getListId()
	{
		return $this->listId;
	}

	public function getCriteria()
	{
		return $this->mailerCriteria;
	}

	public function getTemplate()
	{
		return $this->template;
	}

	public function getSenderMail()
	{
		return $this->senderMail;
	}

	public function isProductMailer()
	{
		return $this->isProductMailer;
	}

	public function getLastProcessedTimeWindow()
	{
		return $this->lastProcessedTimeWindow;
	}
	
	public function isCSV()
	{
		return ($this->listId > 0);
	}

	public function getTemplateCriteria()
	{
		return $this->templateCriteria;
	}

	public function getParentMailerId(){
		return $this->parentMailerId;
	}

	public function getDripMailerType(){
		return $this->dripMailerType;
	}

	public function getClientId(){
		return $this->clientId;
	}
}
