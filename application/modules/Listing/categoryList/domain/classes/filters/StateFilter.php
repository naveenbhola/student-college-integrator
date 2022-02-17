<?php

class StateFilter extends AbstractFilter
{       
    function __construct()
    {
        parent::__construct();        
    }
    
    public function getFilteredValues()
    {
        asort($this->values);
        return $this->values;
    }
    
    public function extractValue(Institute $institute,Course $course)
    {
        $states = array();
        $locations = $course->getCurrentLocations();
	
		foreach($locations as $location){
			$state = $location->getState();
			
			if($stateId = $state->getId()) {
				
				$states[] = $stateId;				
			}
		}
		
		return $states;
    }
    
    public function addValue(Institute $institute,Course $course)
    {
		$locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$state = $location->getState();
			if($stateId = $state->getId()) {				
				$this->values[$stateId] = $state->getName();							    
			}
		}
    }
    
    function setFilterValues($filterVaules = array()) {
	    foreach($filterVaules as $countryId => $countryArr)
            {
		foreach($countryArr["states"] as $stateId => $stateArr)
		{
			$this->values[$stateId] = $stateArr["name"];
		}
	    }
    }
}
