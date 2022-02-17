<?php

class scholarshipHomePageCache extends Cache
{
	private $standardCacheTime = 14400; //4 hour
	
	function __construct()
	{
		parent::__construct();
	}

	public function getSHPCountryWidgetData($key){
		if($key){
			$data = unserialize($this->get($key, ''));
		}
		return $data;
	}	
	
	public function setSHPCountryWidgetData($key,$data){
		if(!empty($data)){
			$this->store($key, '', serialize($data), $this->standardCacheTime, NULL, 1);
		}
	}	
}
