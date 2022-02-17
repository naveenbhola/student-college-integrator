<?php

class LocalityFilter extends AbstractFilter
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function getFilteredValues()
    {
        $zones = array();
        $localities = array();
        
        foreach($this->values as $value) {
            
            $zoneId = $value['zone']->getId();
            $zoneName = $value['zone']->getName();
            $localityId = $value['locality']->getId();
            $localityName = $value['locality']->getName();
            
            if(!isset($zones[$zoneId])) {
                $zones[$zoneId] = array('name' => $zoneName,'localities' => array());
            }
            
            if(!isset($zones[$zoneId]['localities'][$localityId])) {
                $zones[$zoneId]['localities'][$localityId] = $localityName;
            }
        }
        
        uasort($zones,array($this,'_sortByZoneName'));
        foreach($zones as $zoneId => $localities) {
            asort($localities['localities']);
            $zones[$zoneId] = $localities;
        }
        return $zones;
    }
	
	public function getCityWiseFilteredValues()
    {
		$cityWiseZones = array();
        $localities = array();
        
        foreach($this->values as $value) {
			$cityId = $value['zone']->getCityId();
			$zoneId = $value['zone']->getId();
            $zoneName = $value['zone']->getName();
            $localityId = $value['locality']->getId();
            $localityName = $value['locality']->getName();
            
            if(!isset($cityWiseZones[$cityId][$zoneId])) {
                $cityWiseZones[$cityId][$zoneId] = array('name' => $zoneName,'localities' => array());
            }
            
            if(!isset($cityWiseZones[$zoneId]['localities'][$localityId])) {
                $cityWiseZones[$cityId][$zoneId]['localities'][$localityId] = $localityName;
            }
        }
        
        foreach($cityWiseZones as $cityId => $zones) {
			uasort($zones,array($this,'_sortByZoneName'));
			foreach($zones as $zoneId => $localities) {
				asort($localities['localities']);
				$cityWiseZones[$cityId][$zoneId] = $localities;
			}
        }
        return $cityWiseZones;
    }
    
    private function _sortByZoneName($zone1,$zone2)
    {
        if($zone1['name'] > $zone2['name']) {
            return 1;
        }
        else if($zone1['name'] < $zone2['name']) {
            return -1;
        }
        else {
            return 0;
        }
    }
    
    public function extractValue(Institute $institute,Course $course)
    {
        $localities = array();
		$locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$locality = $location->getLocality();
			$localities[] = $locality->getId();
		}
		return $localities;   
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        $locations = $course->getCurrentLocations();
		foreach($locations as $location){
			$zone = $location->getZone();
			$locality = $location->getLocality();
			if(($localityId = $locality->getId()) && ($zoneId = $zone->getId())) { 
				$this->values[$localityId] = array('zone' => $zone,'locality' => $locality);
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
			    foreach($zoneArr["localities"] as $localityId => $localityArr)
			    {
				$zone = new Zone;
				$zone->__set("zoneId"	, $zoneId);
				$zone->__set("zoneName"	, $zoneArr["name"]);
				$zone->__set("cityId"	, $cityId);
				$zone->__set("countryId"	, $countryId);
				
				$locality = new Locality;
				$locality->__set("localityId"	,$localityId);
				$locality->__set("localityName"	,$localityArr["name"]);
				$locality->__set("zoneId"		,$zoneId);
				$locality->__set("countryId"	,$countryId);
				
				$this->values[$localityId] = array('zone' => $zone,'locality' => $locality);
			    }
			}
		    }
		}
	    }
    }       
}
