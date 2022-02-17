<?php

class ABTestingCache extends Cache
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function getABParams($key){
		$params = $this->get($key,1);
		return $params;
	}

	public function storeABParams($key, $params){
		$params = (int)$params;
		$this->store($key,1, $params,-1, 'ABTesting', 1);
	}

	public function incrementCacheValue($key){
		return $this->inc($this->getCacheKey($key,1));
	}
}
