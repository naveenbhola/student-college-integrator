<?php

class CategoryPageClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'categoryList';
		$this->serverFile = 'CategoryPageServer';
	}
	
	function getFiltersToHide($type,$typeId)
	{
		$request = array($type,$typeId); 
		return $this->executeRequest('getFiltersToHide',$request);
	}
	
	function getHeaderText(CategoryPageRequest $categoryPageRequest)
	{
		$request = array(base64_encode(serialize($categoryPageRequest))); 
		return $this->executeRequest('getHeaderText',$request);
	}
	
	function getDynamicLDBCoursesList(CategoryPageRequest $categoryPageRequest)
	{
		//$request = array(base64_encode(serialize($categoryPageRequest)));
		$request = array(utility_encodeXmlRpcResponse(serialize($categoryPageRequest)));
		return $this->executeRequest('getDynamicLDBCoursesList',$request);
	}
	function getDynamicCategoryList(CategoryPageRequest $categoryPageRequest)
	{
		//$request = array(base64_encode(serialize($categoryPageRequest)));
		$request = array(utility_encodeXmlRpcResponse(serialize($categoryPageRequest)));
		return $this->executeRequest('getDynamicCategoryList',$request);
	}
	function getDynamicLocationList(CategoryPageRequest $categoryPageRequest)
	{
		//$request = array(base64_encode(serialize($categoryPageRequest)));
		$request = array(utility_encodeXmlRpcResponse(serialize($categoryPageRequest)));
		return $this->executeRequest('getDynamicLocationList',$request);
	}	
	function getCategoryPageParameters($entity,$entityId,$criteria)
	{
		$request = array($entity,$entityId,utility_encodeXmlRpcResponse($criteria));
		return $this->executeRequest('getCategoryPageParameters',$request);
	}
	
	function setCategoryPageDataInCacheMemory($courseIds)
	{
		$request = array(utility_encodeXmlRpcResponse($courseIds));
		return $this->executeRequest('setCategoryPageDataInCacheMemory',$request,'write');
	}
	
	public function raiseAlert($type,$data)
    {
		$request = array($type,$data);
		return $this->executeRequest('raiseAlert',$request,'write',FALSE);
    }
	
	public function trackFilters($sessionId,$categoryPageRequest,$appliedFilters, $resultCount)
	{
		$request = array($sessionId,base64_encode(serialize($categoryPageRequest)),base64_encode(serialize($appliedFilters)), $resultCount);
		return $this->executeRequest('trackFilters',$request,'write',FALSE);
	}
	
	/*
	 * Cron management functions
	 */ 
	function getCronData()
	{
		$request = array();
		return $this->executeRequest('getCronData',$request);
	}
	
	function registerCron($cronPid,$status)
	{
		$ipAddress = S_REMOTE_ADDR;
		$request = array($cronPid,$status,$ipAddress);
		return $this->executeRequest('registerCron',$request,'write',FALSE);
	}
	
	function updateCron($cronId,$status,$timeWindow,$stats = array())
	{
		$request = array($cronId,$status,$timeWindow,utility_encodeXmlRpcResponse($stats));
		return $this->executeRequest('updateCron',$request,'write');
	}
         function getDynamicLocationListForBrowseInstitute(CategoryPageRequest $categoryPageRequest)
	{
                //echo "Sdsdsd";
		$request = array(base64_encode(serialize($categoryPageRequest)));
		return $this->executeRequest('getDynamicLocationListForBrowseInstitute',$request);
	}
	function getRegionsForCountries($array)
	{
		$request = array(json_encode($array));
		return $this->executeRequest('getRegionsForCountries',$request);
	}
}
