<?php

class AbroadCategoryPageCache extends Cache
{
    private $cacheExpirationTime = 604800; // 1 Week Cache expiration for First Level Cache.

    function __construct()
	{
		parent::__construct();
	}
    
	/*
	 * get abroad cat page filters from cache
	 * @param: Page key
	 */
	function getFilters($pagekey)
    {
		$filters = $this->get('FILTERS-',$pagekey);
		return unserialize(gzuncompress($filters));
    }
	
	function storeFilters($pagekey,$filters)
	{		            
        $this->store('FILTERS-',$pagekey,gzcompress(serialize($filters)),$this->cacheExpirationTime);
	}
	/*
	 * gets currency conversion data & puts it to cache
	 */
	function getCurrencyDetails()
	{
		return $this->get('CategoryPageCurrencyDetails',1);
	}
	function storeCurrencyDetails($currencyDetails)
	{
		$this->store('CategoryPageCurrencyDetails',1,$currencyDetails,43200,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	/*
	 * get universities for given key for category page
	 */
	public function getUniversities($pageKey)
	{
		$universities = unserialize(gzuncompress($this->get('AbroadCategoryPageUniversities', $pageKey)));
		return $universities;
	}
	public function storeUniversities($pageKey,$universities)
	{
		$this->store('AbroadCategoryPageUniversities',$pageKey,gzcompress(serialize($universities)),3600);
		return $institutes;
	}
	/*
	 * get fat footer widget data
	 * @param: Page key
	 */
	function getFatFooterWidgetData($pagekey)
	{
		$fatFooterWidgetData = $this->get('SACTPGPopSubcat-',$pagekey);
		return unserialize($fatFooterWidgetData);
	}
	
	function storeFatFooterWidgetData($pagekey,$fatFooterWidgetData)
	{		            
        	$this->store('SACTPGPopSubcat-',$pagekey,serialize($fatFooterWidgetData),21600);//6 hours
	}
}
