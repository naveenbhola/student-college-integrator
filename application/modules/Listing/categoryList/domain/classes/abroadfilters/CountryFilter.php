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
    
    public function extractValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
    {
        $countries = array();
		$locations = $university->getLocations();
		foreach($locations as $location){
			$country = $location->getCountry();
			$countries[] = $country->getId();
			if($countryId = $country->getId()) { 
			    $this->values[$countryId] = $country->getName();
			}
		}
		return $countries;   
    }
	
	public function extractSnapshotValue(University $university, SnapshotCourse $course)
    {
        $countries = array();
		$locations = $university->getLocations();
		foreach($locations as $location){
			$country = $location->getCountry();
			$countries[] = $country->getId();
			if($countryId = $country->getId()) { 
			    $this->values[$countryId] = $country->getName();
			}
		}
		return $countries;   
    }
    
    public function addValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
    {
        $locations = $university->getLocations();
		foreach($locations as $location){
			$country = $location->getCountry();
			if($countryId = $country->getId()) { 
				$this->values[$countryId] = $country->getName();
			}
		}
    }
	
	public function addSnapshotValue(University $university, SnapshotCourse $course)
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
