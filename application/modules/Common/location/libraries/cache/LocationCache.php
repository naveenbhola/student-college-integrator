<?php

class LocationCache extends Cache
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * Locality
	 */ 
	function getLocality($localityId)
    {
		return json_decode($this->get('V1Locality',$localityId),true);
    }
	
	function storeLocality($localityId, $locality)
	{
		$this->store('V1Locality',$localityId,json_encode($locality),-1,CACHEPRODUCT_LOCATION,0);
	}
	
    function getLocalities()
    {
		return unserialize($this->get('Localities',1));
    }

    function storeLocalities($localities)
    {
		$this->store('Localities',1,serialize($localities),-1,CACHEPRODUCT_LOCATION,0);
    }

    function getLocalitiesByZone($zoneId)
    {
        return unserialize($this->get('LocalitiesByZone',$zoneId));
    }

    function storeLocalitiesByZone($zoneId,$localities)
    {
        $this->store('LocalitiesByZone',$zoneId,serialize($localities),-1,CACHEPRODUCT_LOCATION,0);
    }
    
    function getLocalitiesByCity($cityId)
    {
        return unserialize($this->get('LocalitiesByCity',$cityId));
    }

    function storeLocalitiesByCity($cityId,$localities)
    {
        $this->store('LocalitiesByCity',$cityId,serialize($localities),-1,CACHEPRODUCT_LOCATION,0);
    }



    /*
	 * Zone
	 */ 
	function getZone($zoneId)
    {
		return unserialize($this->get('Zone',$zoneId));
    }
	
	function storeZone($zone)
	{
		$this->store('Zone',$zone->getId(),serialize($zone),-1,CACHEPRODUCT_LOCATION,0);
	}


    function getZones()
    {
        return unserialize($this->get('Zones',1));
    }

    function storeZones($zones)
    {
	$this->store('Zones',1,serialize($zones),-1,CACHEPRODUCT_LOCATION,0);
    }

    function getZonesByCity($cityId)
    {
        return unserialize($this->get('ZonesByCity',$cityId));
    }

    function storeZonesByCity($cityId,$zones)
    {
        $this->store('ZonesByCity',$cityId,serialize($zones),-1,CACHEPRODUCT_LOCATION,0);
    }


    /*
	 * City
	 */ 
	function getCity($cityId)
    {
		return json_decode($this->get('V1City',$cityId),true);
    }
    
	public function getMultipleCities($citiesIds)
    {
		$cities =  $this->multiGet('V1City',$citiesIds);
        $returnData = array();
        foreach ($cities as $row) {
            $temp = json_decode($row,true);
            $returnData[$temp['city_id']] = $temp;
        }
		return $returnData;
    }    
    
	function getMultipleLocalities($localityIds)
    {
		$localities =  $this->multiGet('V1Locality', $localityIds);
        $returnData = array();
        foreach ($localities as $row) {
            $temp = json_decode($row,true);
            $returnData[$temp['localityId']] = $temp;
        }
		return $returnData;
    }
	
	function getMultipleStates($stateIds)
    {
		$states =  $this->multiGet('V1State', $stateIds);

        $returnData = array();
        foreach ($states as $row) {
            $temp = json_decode($row,true);
            $returnData[$temp['state_id']] = $temp;
        }
		return $returnData;
    }
	
	function getMultipleZones($zoneIds)
    {
		$zones =  $this->multiGet('Zone', $zoneIds);
		$zones = array_map('unserialize',$zones);		 
		return $zones;
    }

    function clearLocationCache()
    {
        $this->cacheLib->clearCache(CACHEPRODUCT_LOCATION);
    }
	
	function storeCity($cityId,$city)
	{
		if(!empty($city)){
			$this->store('V1City',$cityId,json_encode($city),-1,CACHEPRODUCT_LOCATION,0);
		}
	}
    
    function getCitiesByTier($tier)
    {
        return json_decode($this->get('V1CitiesByTier',$tier),true);
    }
    
    function storeCitiesByTier($tier,$cities)
    {
        $this->store('V1CitiesByTier',$tier,json_encode($cities),-1,CACHEPRODUCT_LOCATION,0);
    }

    function getStatesByTier($tier)
    {
        return unserialize($this->get('StatesByTier',$tier));
    }

    function storeStatesByTier($tier,$states)
    {
        $this->store('StatesByTier',$tier,serialize($states),-1,CACHEPRODUCT_LOCATION,0);
    }


    function getCities($countryId,$include_virtual)
    {   
    	$cacheKey = $countryId.($include_virtual ? "true" : "false");
    	return unserialize($this->get('CitiesByCountry',$cacheKey));
    }
    
    function storeCities($countryId,$include_virtual,$cities)
    {   
    	$cacheKey = $countryId.($include_virtual ? "true" : "false");
    	$this->store('CitiesByCountry',$cacheKey,serialize($cities),1800,CACHEPRODUCT_LOCATION,0);
    }
     
    
    
    
    function getCitiesByState($stateId)
    {
        return unserialize($this->get('CitiesByState',$stateId));
    }

    function getCitiesByMultipleStates($stateIds)
    {
    	$cities =  $this->multiGet('CitiesByState', $stateIds);
		$cities = array_map('unserialize',$cities);
		return $cities;
    }
    
    function storeCitiesByState($stateId,$cities)
    {
        $this->store('CitiesByState',$stateId,serialize($cities),-1,CACHEPRODUCT_LOCATION,0);
    }
    
    function getCitiesByVirtualCity($cityId)
    {
        return json_decode($this->get('V1CitiesByVirtualCity',$cityId), true);
    }
    
    function storeCitiesByVirtualCity($cityId,$cities)
    {
        $this->store('V1CitiesByVirtualCity',$cityId,json_encode($cities),-1,CACHEPRODUCT_LOCATION,0);
    }

    function getCitiesHavingZones()
    {
        return unserialize($this->get('CitiesHavingZones',1));
    }

    function storeCitiesHavingZones($cities)
    {
        $this->store('CitiesHavingZones', 1, serialize($cities), -1, CACHEPRODUCT_LOCATION,0);
    }


    /*
	 * State
	 */ 
	function getState($stateId)
    {
		return json_decode($this->get('V1State',$stateId),true);
    }
	
	function storeState($stateId, $state)
	{
		$this->store('V1State',$stateId,json_encode($state),-1,CACHEPRODUCT_LOCATION,0);
	}
    
    function getStatesByCountry($countryId)
    {
        return unserialize($this->get('StatesByCountry',$countryId));
    }
    
	function storeStatesByCountry($countryId,$states)
    {
        $this->store('StatesByCountry',$countryId,serialize($states),-1,CACHEPRODUCT_LOCATION,0);
    }
    
    /*
	 * Country
	 */ 
	function getCountry($countryId)
    {
		return unserialize($this->get('Country',$countryId));
    }
	
	function storeCountry($country)
	{
		$this->store('Country',$country->getId(),serialize($country),-1,CACHEPRODUCT_LOCATION,0);
	}
    
	function getCountries()
    {
		return unserialize($this->get('Countries',1));
    }
	
	function storeCountries($countries)
	{
		$this->store('Countries',1,serialize($countries),-1,CACHEPRODUCT_LOCATION,0);
	}
	
	function getMultipleCountries($countryIds)
    {
		$countries = $this->multiGet('Country',$countryIds);
		$countries = array_map('unserialize', $countries);
		return $countries;
    }
	
    function getCountriesByRegion($regionId)
    {
        return unserialize($this->get('CountriesByRegion',$regionId));
    }
    
	function storeCountriesByRegion($regionId,$countries)
    {
        $this->store('CountriesByRegion',$regionId,serialize($countries),-1,CACHEPRODUCT_LOCATION,0);
    }
    
    /*
	 * Region
	 */ 
	function getRegion($regionId)
    {
		return unserialize($this->get('Region',$regionId));
    }
	
	function storeRegion($region)
	{
		$this->store('Region',$region->getId(),serialize($region),-1,CACHEPRODUCT_LOCATION,0);
	}
	
	function getRegions()
    {
		return unserialize($this->get('Regions',1));
    }
	
	function storeRegions($regions)
	{
		$this->store('Regions',1,serialize($regions),-1,CACHEPRODUCT_LOCATION,0);
	}
	
	function clearCache()
	{
		$keys = $this->getByKey(CACHEPRODUCT_LOCATION);
		
		foreach($keys as $key) {
			$this->deleteByKey($key);
		}
		
		$this->deleteByKey(md5("getCountriesWithRegions1"));
		$this->deleteByKey(md5("getCountriesWithRegions"));
		
		$this->deleteByKey(md5("getCountries"));
		$this->deleteByKey(md5("getCountries1"));
		$this->deleteByKey(md5("getCountries12"));
		
		$this->deleteByKey(md5("getCountriesCategoryClient"));
		$this->deleteByKey(md5("getCountriesCategoryClient1"));
		$this->deleteByKey(md5("getCountriesCategoryClient12"));
		
		
		$this->deleteByKey(md5("getCountriesForProduct1studyabroad"));
		
		
		
		/*
		 * Enpty the product
		 */ 
		$this->storeByKey(CACHEPRODUCT_LOCATION,1,-1);
	}
	
	function getAbroadCountries()
    {
		return unserialize($this->get('AbroadCountries',1));
    }
	
	function storeAbroadCountries($countries)
	{
		$this->store('AbroadCountries',1,serialize($countries),-1,CACHEPRODUCT_LOCATION,0);
	}

	function getLocationHierarchy()
    {
        return unserialize($this->get('getLocationHierarchy',1));
    }
    
    function storeLocationHierarchy($data)
    {
        $this->store('getLocationHierarchy',1,serialize($data),86400,CACHEPRODUCT_LOCATION,0);
    }

    function deleteAbroadCityCache($cityId){
	    $this->delete('V1AbroadCity',$cityId);
    }
}
