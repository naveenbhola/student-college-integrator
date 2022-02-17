<?php

abstract class NationalListingModelAbstract extends MY_Model
{
    function __construct(){
		parent::__construct('Listing');
		global $forceListingWriteHandle;
		if($forceListingWriteHandle){
			$this->db = $this->getWriteHandle();
		}else{
			$this->db = $this->getReadHandle();
		}
		
    }
    
    /*
	 * Check that the string of pipe separated filters contains all valid filters
	 * On success, convert string to array containg each valid filter
	 */ 
	protected function validateFilters($filters)
	{
		$validFilters = array();
		$invalidFilters = array();
	
        $defaultFilters = $this->defaultFilters;
    	
		$filters = trim($filters);

		if($filters){	
			$filters = explode('|',$filters);

			foreach($filters as $filter){
				if(!in_array($filter,$defaultFilters)){
					$invalidFilters[] = $filter;
				}
				else{
					$validFilters[] = $filter;
				}
			}
		}
		else{
			$validFilters = $defaultFilters;
		}
		
		if(count($invalidFilters) > 0){
			throw new Exception("Invalid filters: ".implode(', ',$invalidFilters).". ".
								"Valid filters are: ".implode(', ',$defaultFilters)); 
		}
		return $validFilters;
	}
    
    protected function validateCategoryPageRequest(CategoryPageRequest $categoryPageRequest)
	{
		Contract::mustBeNumericValueGreaterThanZero($categoryPageRequest->getCategoryId(),'Category ID');
		Contract::mustBeNumericValueGreaterThanZero($categoryPageRequest->getSubCategoryId(),'Sub-Category ID');
		Contract::mustBeNumericValueGreaterThanZero($categoryPageRequest->getCityId(),'City ID');
		Contract::mustBeNumericValueGreaterThanZero($categoryPageRequest->getStateId(),'State ID');
		Contract::mustBeNumericValueGreaterThanZero($categoryPageRequest->getCountryId(),'$country ID');
	}
    
    /*
     * Get the data for multiple institutes/courses indexed by filters
     * Reindex so that data is now "Indexed by institute/course Ids -> Filters"
     */ 
    protected function indexFilteredDataByListingIds($data,$listingKey,$keysForRemovingExtraDepth = array())
	{
        $indexedData = array();
		foreach($data as $filter => $dataForFilter) {
            $indexedDataForFilter = $this->indexDataByKey($dataForFilter,$listingKey);
			foreach($indexedDataForFilter as $listingId => $listingData) {
				$indexedData[$listingId][$filter] = $listingData;
			}
		}
		foreach($indexedData as $listingId => $listingData) {
				$indexedData[$listingId] = $this->removeExtraDepthForKeys($listingData,$keysForRemovingExtraDepth);
		}
		return $indexedData;
	}
    
    /*
     * Index array by provided key e.g.
     * array(array(1=>'a',2=>'b'),array(1=>'c',2=>'d'))
     * to
     * array(1 => array('a','c'), 2=> array('b','d'))
     */ 
    protected function indexDataByKey($data,$key)
    {
        $indexedData = array();
        
        foreach($data as $row) {
            if(isset($row[$key])) {
                $indexedData[$row[$key]][] = $row;
            }
        }
        
        return $indexedData;
    }
    
    /*
     * Remove extra depth from array for selected keys e.g.
     * array('general' => array(0=>array(1,2,3)))
     * to
     * array('general' => array(1,2,3))
     */ 
    protected function removeExtraDepthForkeys($array,$keys)
    {
        foreach($array as $key => $value) {
            if(in_array($key,$keys)) {
                if(isset($array[$key][0])) {
                    $array[$key] = $array[$key][0];    
                }
            }    
        }
        return $array;
    }
}