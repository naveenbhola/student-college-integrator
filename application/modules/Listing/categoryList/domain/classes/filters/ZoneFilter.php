<?php

class ZoneFilter extends AbstractFilter
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function getFilteredValues()
    {
        return $this->values;
    }
    
    public function extractValue(Institute $institute,Course $course)
    {
        $zones = array();
		$locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$zone = $location->getZone();
			$zones[] = $zone->getId();
		}
		return $zones;
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        $locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$zone = $location->getZone();
			if($zoneId = $zone->getId()) { 
				$this->values[$zoneId] = $zone->getName();
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
			foreach($cityArr["zones"] as $zoneId => $zoneArr)
			{
			    $this->values[$zoneId] = $zoneArr["name"];
			}
		    }
		}
	    }
    }     
}
