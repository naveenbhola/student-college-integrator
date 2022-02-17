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
    
    public function generateFiltersForAbroadFilters($university,$institute,$course,$isCertDiplomaPage = false)
    {
    	$filterValues = array();
    	foreach($this->defaultFilters as $filterKey => $filter) {
    		$filterValues[constant('CP_FILTER_'.strtoupper($filterKey))] = $filter->extractValue($university,$institute,$course,$isCertDiplomaPage);
    	}
    	return $filterValues;
    }
	
	public function generateSnapshotFiltersForAbroadFilters($university,$snapshotCourse,$subcatArray,& $time)
    {
    	$filterValues = array();
    	foreach($this->defaultFilters as $filterKey => $filter) {
			$start = microtime(true);
    		$filterValues[constant('CP_FILTER_'.strtoupper($filterKey))] = $filter->extractSnapshotValue($university,$snapshotCourse,$subcatArray);
			$end = microtime(true);
			$time[$filterKey] = $time[$filterKey] + ($end-$start);
    	}
    	return $filterValues;
    }
    
    public function addValuesToAbroadFilters($university,$institute,$course)
    {
    	foreach($this->defaultFilters as $filterKey => $filter) {
			$filter->addValue($university,$institute,$course);
    	}
    }
	
	public function addSnapshotValuesToAbroadFilters($university,$snapshotCourse,$subcatArray)
    {
    	foreach($this->defaultFilters as $filterKey => $filter) {
    		$filter->addSnapshotValue($university,$snapshotCourse,$subcatArray);
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