<?php

class PortingEntity
{

	private $id;

	private $name;

	private $clientId;

	private $type;

	private $lastPortedId;

	private $createDate;

	private $firsttimeStartdate;

	private $isRunFirsttime;

	private $requestType;

	private $api;

	private $status;

	private $portingCriteria;

	private $mappings;

	private $subscriptionId;

	private $status_modification_datetime;
    
	private $Daily_Limit;
    
	private $porting_time;
	
	private $xml_format;

	private $dataEncode;

	private $custom_header;

	private $vendor_name;

	public function __construct($portingData)
	{
		$this->id = $portingData['id'];
		$this->name = $portingData['name'];
		$this->clientId = $portingData['client_id'];
		$this->type = $portingData['type'];
		$this->lastPortedId = $portingData['last_ported_id'];
		$this->createDate = $portingData['create_date'];
		$this->firsttimeStartdate = $portingData['firsttime_startdate'];
		$this->isRunFirsttime = $portingData['isrun_firsttime'];
		$this->requestType = $portingData['request_type'];
		$this->api = $portingData['api'];
		$this->status = $portingData['status'];
		$this->portingCriteria = $portingData['portingCriteria'];
		$this->mappings = $portingData['mappings'];
		$this->subscriptionId = $portingData['SubscriptionId'];
		$this->status_modification_datetime  = $portingData['status_modification_datetime'];
		$this->Daily_Limit = $portingData['Daily_Limit'];
		$this->porting_time = $portingData['porting_time'];
		$this->dataFormatType = $portingData['data_format'];
		$this->jsonDataKey = $portingData['data_key'];
		$this->duration = $portingData['duration'];
		$this->xml_format = $portingData['xml_format'];
		$this->dataEncode = $portingData['dataEncode'];
		$this->custom_header = $portingData['custom_header'];
		$this->vendor_name = $portingData['vendor_name'];
	}

    public function getModifictaionDateTime()
    {
        return $this->status_modification_datetime;
    }

    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }


    public function getMappings()
    {
        return $this->mappings;
    }


    public function getPortingCriteria()
    {
        return $this->portingCriteria;
    }


    public function getId()
    {
        return $this->id;
    }

	public function getName()
	{
		return $this->name;
	}

	public function getClientId()
	{
		return $this->clientId;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getLastPortedId()
	{
		return $this->lastPortedId;
	}

	public function getCreateDate()
	{
		return $this->createDate;
	}


	public function getFirsttimeStartdate()
	{
		return $this->firsttimeStartdate;
	}


	public function isRunFirsttime()
	{
		return $this->isRunFirsttime;
	}
	
	public function getRequestType()
	{
		return $this->requestType;
	}

	public function getApi()
	{
		return $this->api;
	}

	public function getStatus()
	{
		return $this->status;
	}
    
	public function getDailyLimits()
	{
		return $this->Daily_Limit;
	}
    
	public function getPortingTime()
	{
		return $this->porting_time;
	}
	
	public function getFormatType()
	{
		return $this->dataFormatType;
	}
	
	public function getKeyName()
	{
		return $this->jsonDataKey;
	}
	
	public function getDuration()
	{
		return $this->duration;
	}

	public function getXmlFormat()
	{
		return $this->xml_format;
	}

	public function getDataEncode()
	{
		return $this->dataEncode;
	}

	public function getCustomHeader()
	{
		return $this->custom_header;
	}

	public function getVendorName()
	{
		return $this->vendor_name;
	}
}
