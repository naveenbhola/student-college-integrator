<?php

class OAFPortingEntity
{

	private $id;

	private $name;

	private $clientId;

	private $type;

	private $requestType;

	private $api;

	private $portingCriteria;

	private $mappings;

	private $dataFormatType;

	private $jsonDataKey;

	private $status;

	private $xml_format;

	private $dataEncode;

	private $lastPortedId;

	private $isRunFirsttime;

	private $vendor_name;

	public function __construct($portingData)
	{
		$this->id = $portingData['id'];
		$this->name = $portingData['name'];
		$this->clientId = $portingData['client_id'];
		$this->type = $portingData['type'];
		$this->requestType = $portingData['request_type'];
		$this->api = $portingData['api'];
		$this->portingCriteria = $portingData['portingCriteria'];
		$this->mappings = $portingData['mappings'];
		$this->dataFormatType = $portingData['data_format'];
		$this->jsonDataKey = $portingData['data_key'];
		$this->xml_format = $portingData['xml_format'];
		$this->dataEncode = $portingData['dataEncode'];
		$this->status = $portingData['status'];
		$this->lastPortedId = $portingData['last_ported_id'];
		$this->isRunFirsttime = $portingData['isrun_firsttime'];
		$this->vendor_name = $portingData['vendor_name'];
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

	public function getRequestType()
	{
		return $this->requestType;
	}

	public function getApi()
	{
		return $this->api;
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

	public function getStatus()
	{
		return $this->status;
	}

	public function getLastPortedId()
	{
		return $this->lastPortedId;
	}

	public function isRunFirsttime()
	{
		return $this->isRunFirsttime;
	}

	public function getVendorName()
	{
		return $this->vendor_name;
	}
}
