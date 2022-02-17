<?php

class MISCache extends Cache
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * Institute
	 */ 
	/*public function getTopPagesForShikshaResponses()
    {
		$data = unserialize($this->get('TopPagesForShikshaResponses091215-080116',1));
		return $data;
	}	
	
	public function storeTopPagesForShikshaResponses($topPagesData)
	{
		$data = serialize($topPagesData);
		$this->store('TopPagesForShikshaResponses',1, $data,-1, 'TRACKING_MIS', 1);
	}*/
	
	public function getTopData($key){
		$data = unserialize($this->get($key,1));
		return $data;
	}

	public function storeTopData($topPagesData,$key)
	{
		$data = serialize($topPagesData);
		$this->store($key,1, $data,86400, 'TRACKING_MIS', 1);
	}
}
