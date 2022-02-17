<?php

class CityFilter extends AbstractFilter
{
    private $locationRepository;
    
    function __construct(LocationRepository $locationRepository)
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
    }
    
    public function getFilteredValues()
    {
        asort($this->values);
        return $this->values;
    }
    
    public function extractValue(Institute $institute,Course $course)
    {
        $cities = array();
        $locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$city = $location->getCity();
			if($cityId = $city->getId()) {
				
				$cities[] = $cityId;
				
				if(($virtualCityId = $city->getVirtualCityId()) && $virtualCityId != $cityId) {
					$cities[] = $virtualCityId;
				}
			}
		}
		return $cities;
    }
    
    public function addValue(Institute $institute,Course $course)
    {
		$locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$city = $location->getCity();
			if($cityId = $city->getId()) {
				
				$this->values[$cityId] = $city->getName();
				
				if(($virtualCityId = $city->getVirtualCityId()) && $virtualCityId != $cityId) {
				
					if(!isset($this->values[$virtualCityId])) {
						
						$virtualCity = $this->locationRepository->findCity($virtualCityId);
						$this->values[$virtualCityId] = $virtualCity->getName();
					}
				}
			}
		}
    }
    
    function setFilterValues($filterVaules = array()) {

	    foreach($filterVaules as $countryId => $countryArr)
            {
		foreach($countryArr["states"] as $stateId => $stateArr)
		{
		    foreach($stateArr["cities"] as $cityId => $cityArr)
		    {
			$this->values[$cityId] = $cityArr["name"];
		    }
		}
	    }
	    
	    if(isset($filterVaules[2]) && isset($filterVaules[2]["course_virtual_city_id"]))
	    {
		foreach($filterVaules[2]["course_virtual_city_id"] as $virtualCityId=>$count){
		
			if(!isset($this->values[$virtualCityId])) {
				
				$virtualCity = $this->locationRepository->findCity($virtualCityId);
				$this->values[$virtualCityId] = $virtualCity->getName();
			}
		}
	    }
    }     
}
