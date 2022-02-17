<?php

class InstituteClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'listing';
		$this->serverFile = 'InstituteServer';
	}
	
	function getDataForInstitute($instituteId,$filters = '')
	{
		$request = array($instituteId,$filters); 
		return $this->executeRequest('getDataForInstitute',$request);
	}
	
	function getDataForMultipleInstitutes($instituteIds,$filters = '')
	{
		$instituteIds = utility_encodeXmlRpcResponse($instituteIds);
		$request = array($instituteIds,$filters); 
		return $this->executeRequest('getDataForMultipleInstitutes',$request);
	}
	
	function unpublishExpiredStickyInstitutes()
	{
		$request = array();
		return $this->executeRequest('unpublishExpiredStickyInstitutes',$request,'write');
	}
	
	function unpublishExpiredMainInstitutes()
	{
		$request = array();
		return $this->executeRequest('unpublishExpiredMainInstitutes',$request,'write');
	}
}

