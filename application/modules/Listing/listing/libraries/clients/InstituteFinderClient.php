<?php

class InstituteFinderClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'listing';
		$this->serverFile = 'InstituteFinderServer';
	}
	
	public function getCategoryPageInstitutes(CategoryPageRequest $request)
	{
		$request = array(base64_encode(serialize($request)));
		return $this->executeRequest('getCategoryPageInstitutes',$request);
	}
	
	public function getTopInstitutesInCategory($categoryId)
	{
		$request = array($categoryId);
		return $this->executeRequest('getTopInstitutesInCategory',$request);
	}
	
	public function getModifiedInstitutes($criteria)
	{
		$request = array(utility_encodeXmlRpcResponse($criteria));
		return $this->executeRequest('getModifiedInstitutes',$request);
	}
	
	public function getExpiredStickyInstitutes($numDays)
	{
		$request = array($numDays);
		return $this->executeRequest('getExpiredStickyInstitutes',$request);
	}
	
	public function getModifiedStickyInstitutes($criteria)
	{
		$request = array(utility_encodeXmlRpcResponse($criteria));
		return $this->executeRequest('getModifiedStickyInstitutes',$request);
	}
	
	public function getExpiredMainInstitutes($numDays)
	{
		$request = array($numDays);
		return $this->executeRequest('getExpiredMainInstitutes',$request);
	}
	
	public function getModifiedMainInstitutes($criteria)
	{
		$request = array(utility_encodeXmlRpcResponse($criteria));
		return $this->executeRequest('getModifiedMainInstitutes',$request);
	}
	
	public function getLiveInstitutes()
	{
		$request = array();
		return $this->executeRequest('getLiveInstitutes',$request);
	}
	
	public function getCategoryPageStickyInstitutes(CategoryPageRequest $request){
		$request = array(base64_encode(serialize($request)));
		return $this->executeRequest('getCategoryPageStickyInstitutes',$request);
	}
	
	public function getCategoryPageMainInstitutes(CategoryPageRequest $request){
		$request = array(base64_encode(serialize($request)));
		return $this->executeRequest('getCategoryPageMainInstitutes',$request);
	}
	
	
}

