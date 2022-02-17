<?php

class BannerFinderClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'listing';
		$this->serverFile = 'BannerFinderServer';
	}
	
	function getCategoryPageBanners($categoryPageRequest)
	{
		$request = array(base64_encode(serialize($categoryPageRequest)));
		return $this->executeRequest('getCategoryPageBanners',$request);
	}
	
	function getExpiredBanners($numDays)
	{
		$request = array($numDays);
		return $this->executeRequest('getExpiredBanners',$request);
	}
	
	function getModifiedBanners($criteria)
	{
		$request = array(utility_encodeXmlRpcResponse($criteria));
		return $this->executeRequest('getModifiedBanners',$request);
	}
}

