<?php

class BannerClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'listing';
		$this->serverFile = 'BannerServer';
	}
	
	function unpublishExpiredBanners()
	{
		$request = array();
		return $this->executeRequest('unpublishExpiredBanners',$request,'write');
	}
}

