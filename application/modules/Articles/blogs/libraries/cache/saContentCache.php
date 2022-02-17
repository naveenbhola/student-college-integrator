<?php

class SaContentCache extends Cache
{
    private $cacheExpirationTime = 86400; // 1 Week Cache expiration for First Level Cache.

    function __construct()
	{
		parent::__construct();
	}
    
	function getPopularArticles($pagekey)
    {
		$articlesData = $this->get('SaPopularArticlesLastNumberOfDays-',$pagekey);
		return unserialize(gzuncompress($articlesData));
    }
	
	function storePopularArticles($pagekey,$articlesData)
	{		            
        $this->store('SaPopularArticlesLastNumberOfDays-',$pagekey,gzcompress(serialize($articlesData)),$this->cacheExpirationTime);
	}

	function getContentPageNavLinks($contentTypeId,$contentType){
		$linksData = $this->get('contentNavLinks-',$contentTypeId."_".$contentType);		
		return unserialize(gzuncompress($linksData));
	}

	function setContentPageNavLinks($contentTypeId,$contentType, $linksData, $days = 7)
	{
        $this->store('contentNavLinks-',$contentTypeId."_".$contentType, gzcompress(serialize($linksData)), $this->cacheExpirationTime * $days);
	}
	
	function deleteContentPageNavLinks($contentTypeId,$contentType){
		$this->delete('contentNavLinks-',$contentTypeId."_".$contentType);
	}
}
