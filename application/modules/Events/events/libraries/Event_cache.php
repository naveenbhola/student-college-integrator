<?php

class Event_cache
{
	private $_cachelib;
	private $_app_id = 1;
	private $_upcomingevents_key = 'upcomingevents';
	
	function __construct($cachelib)
	{
		$this->_cachelib = $cachelib;
	}
	
	function getUpcomingEvents($category_id,$subcategory_id)
    {
		$cache_key = md5($this->_upcomingevents_key.$this->app_id.$category_id.$subcategory_id);
		
		if($this->_cachelib->get($cache_key) == 'ERROR_READING_CACHE')
		{
			return FALSE;
		}
		
		return $this->_cachelib->get($cache_key);
    }
	
	function storeUpcomingEvents($category_id,$subcategory_id,$data)
	{
		$cache_key = md5($this->_upcomingevents_key.$this->app_id.$category_id.$subcategory_id);
		$this->_cachelib->store($cache_key,$data,3600);
	}
}
?>
