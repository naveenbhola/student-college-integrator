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
    
    public function extractValue(University $university,AbroadCourse $course)
    {
        $countries = array();
		$locations = $university->getLocations();
		foreach($locations as $location){
			$country = $location->getCountry();
			$countries[] = $country->getId();
		}
		return $countries;   
    }
	
    
    public function addValue(University $university,AbroadCourse $course)
    {
        $locations = $university->getLocations();
		foreach($locations as $location){
			$country = $location->getCountry();
			if($countryId = $country->getId()) { 
				$this->values[$countryId] = $country->getName();
			}
		}
    }
}
