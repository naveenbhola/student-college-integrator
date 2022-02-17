<?php

class LastModifiedDateSpecification extends CompositeSpecification
{
    
    function __construct()
    {
    
    }
    
    function isSatisfiedBy($course)
    {
		$lastModificationDate  = $course['filterValues'][CP_FILTER_LASTMODIFIEDDATE];
		if(strtotime($lastModificationDate) >= strtotime($this->filterValues)){
			return TRUE;
		}
        return FALSE;
    }
    
    public function setFilterValues($filterValues)
    {
        if(!empty($filterValues))
        {
            $this->filterValues = $filterValues; 
        }
    }
	
}