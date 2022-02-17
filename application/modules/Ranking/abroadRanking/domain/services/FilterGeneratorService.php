<?php

class FilterGeneratorService
{
    private $defaultFilters;
    
    function __construct($defaultFilters)
    {
        $this->defaultFilters = $defaultFilters;
    }
    
    public function getFilters()
    {
        return $this->defaultFilters;
    }
    
    public function generateFiltersForAbroadFilters($university,$course)
    {
    	$filterValues = array();
    	foreach($this->defaultFilters as $filterKey => $filter) {
            if(is_object($course) && is_object($course->getCourseSubCategoryObj())){
                $filterValues[constant('CP_FILTER_'.strtoupper($filterKey))] = $filter->extractValue($university,$course);
            }
    		
    	}
    	return $filterValues;
    }
    
    
    public function addValuesToAbroadFilters($university,$course)
    {
    	foreach($this->defaultFilters as $filterKey => $filter) {
			$filter->addValue($university,$course);
    	}
    }
	
    
    public function generate($institute,$course)
    {
        $filterValues = array();
        foreach($this->defaultFilters as $filterKey => $filter) {
            $filterValues[constant('CP_FILTER_'.strtoupper($filterKey))] = $filter->extractValue($institute,$course);
        }
        return $filterValues;
    }
    
    public function addValues($institute,$course)
    {
        foreach($this->defaultFilters as $filterKey => $filter) {
            $filter->addValue($institute,$course);
        }
    }
    
    public function setFilters($filtersValue = array()) {
    	//_p($this->defaultFilters ); die;
    	foreach($this->defaultFilters as $filterKey => $filter) {
    		
    		if(isset($filtersValue[$filterKey])) {
    			$filter->setFilterValues($filtersValue[$filterKey]);
    		}
    	}
    }
}