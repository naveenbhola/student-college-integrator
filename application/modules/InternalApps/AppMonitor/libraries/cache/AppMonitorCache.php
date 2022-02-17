<?php

class AppMonitorCache
{
	
	private $realHighSQLCacheTtl = 43200; //12 hrs
	private $realHighSQLKey = 'appmonitor:realHighSQLCount'; //12 hrs

	function __construct()
	{
		$this->_redis_client = PredisLibrary::getInstance();
	}
	
	public function getRealHighSQLCount()
    {
    	$realHighSQLCount = $this->_redis_client->getMemberOfString($this->realHighSQLKey);
    	if(!empty($realHighSQLCount))
    		$realHighSQLCount = json_decode($realHighSQLCount, true);

    	return $realHighSQLCount;
	}
	
	public function storeRealHighSQLCount($highSQLCount)
	{
		$data = $this->_redis_client->addMemberToString($this->realHighSQLKey, json_encode($highSQLCount), $this->realHighSQLCacheTtl);
		return $data;
	}

}

