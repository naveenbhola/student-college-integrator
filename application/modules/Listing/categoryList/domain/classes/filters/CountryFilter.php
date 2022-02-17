<?php

class CountryFilter extends AbstractFilter
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
        $countries = array();
		$locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$country = $location->getCountry();
			$countries[] = $country->getId();
		}
		return $countries;   
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        $locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$country = $location->getCountry();
			if($countryId = $country->getId()) { 
				$this->values[$countryId] = $country->getName();
			}
		}
    }
    
    function setFilterValues($filterVaules = array()) {

	    foreach($filterVaules as $countryId => $countryArr)
            {
		$this->values[$countryId] = $countryArr["name"];
	    }
    }
}
