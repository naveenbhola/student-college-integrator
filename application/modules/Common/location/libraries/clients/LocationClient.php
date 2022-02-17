<?php

class LocationClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'location';
		$this->serverFile = 'LocationServer';
	}
	
	function getCity($cityId)
	{
		$request = array($cityId); 
		return $this->executeRequest('getCity',$request);
	}

    function getMultipleCity($cityArr)
	{
		$request = array(array($cityArr,'struct'));
		return $this->executeRequest('getMultipleCity',$request);
	}

	function getCitiesByMultipleTiers($tiers,$countryId=0)
	{
		$request = array(array($tiers,'struct'),$countryId); 
		return $this->executeRequest('getCitiesByMultipleTiers',$request);
	}

	function getStatesByMultipleTiers($tiers,$countryId=0)
	{
		$request = array(array($tiers,'struct'),$countryId);
		return $this->executeRequest('getStatesByMultipleTiers',$request);
	}

    function getCitiesHavingZones()
	{
		$request = array();
		return $this->executeRequest('getCitiesHavingZones',$request);
	}


	function getCountry($countryId)
	{
		$request = array($countryId); 
		return $this->executeRequest('getCountry',$request);
	}
	
	function getCountries()
	{
		$request = array(); 
		return $this->executeRequest('getCountries',$request);
	}
	
	function getAbroadCountries()
	{
		$request = array(); 
		return $this->executeRequest('getAbroadCountries',$request);
	}
	
	function getCountriesByRegion($regionId)
	{
		$request = array($regionId); 
		return $this->executeRequest('getCountriesByRegion',$request);
	}
	
	function getCountryByURLName($urlName)
	{
		$request = array($urlName); 
		return $this->executeRequest('getCountryByURLName',$request);
	}
	
	function getLocality($localityId)
	{
		$request = array($localityId); 
		return $this->executeRequest('getLocality',$request);
	}
	
	function getMultipleLocalities($localityIds){
		$request = array(array($localityIds,'struct'));
		return $this->executeRequest('getMultipleLocalities',$request);
	}
	
	function getMultipleStates($statedIds){
		$request = array(array($statedIds,'struct'));
		return $this->executeRequest('getMultipleStates',$request);
	}
	
	function getMultipleZones($zoneIds){
		$request = array(array($zoneIds,'struct'));
		return $this->executeRequest('getMultipleZones',$request);
	}
	
	
	function getLocalities()
	{
		$request = array(); 
		return $this->executeRequest('getLocalities',$request);
	}

	function getLocalitiesByZone($zoneId)
	{
		$request = array($zoneId); 
		return $this->executeRequest('getLocalitiesByZone',$request);
	}
	
	function getLocalitiesByCity($cityId)
	{
		$request = array($cityId);
		return $this->executeRequest('getLocalitiesByCity',$request);
	}

	function getZone($zoneId)
	{
		$request = array($zoneId); 
		return $this->executeRequest('getZone',$request);
	}

	function getZones()
	{
		$request = array();
		return $this->executeRequest('getZones',$request);
	}

	function getZonesByCity($cityId)
	{
		$request = array($cityId);
		return $this->executeRequest('getZonesByCity',$request);
	}

	function getState($stateId)
	{
		$request = array($stateId); 
		return $this->executeRequest('getState',$request);
	}
	
	function getStatesByCountry($countryId)
	{
		$request = array($countryId); 
		return $this->executeRequest('getStatesByCountry',$request);
	}
	
	function getRegion($regionId)
	{
		$request = array($regionId); 
		return $this->executeRequest('getRegion',$request);
	}
	
	function getRegions()
	{
		$request = array(); 
		return $this->executeRequest('getRegions',$request);
	}
	
	function getRegionByURLName($urlName)
	{
		$request = array($urlName); 
		return $this->executeRequest('getRegionByURLName',$request);
	}
        
	function saveCountry($countryName,$regionId)
	{
		$request = array($countryName,$regionId);
		return $this->executeRequest('saveCountry',$request,'write',FALSE);
	}
	
	function saveZone($zone)
	{
		$request = array($zone);
		return $this->executeRequest('saveZone',$request,'write',FALSE);
	}
	
	function saveLocality($localityName,$zone,$city)
	{
		$request = array($localityName,$zone,$city);
		return $this->executeRequest('saveLocality',$request,'write',FALSE);
	}
    

	function saveCity($cityName, $stateId, $countryId)
	{
		$request = array($cityName, $stateId, $countryId);
		return $this->executeRequest('saveCity',$request,'write',FALSE);
	}

    function getCities($countryId, $include_virtual=False)
	{
		$request = array($countryId, $include_virtual);
		return $this->executeRequest('getCities',$request);
	}
	
	function getCitiesByState($stateId, $include_virtual=False)
	{
		$request = array($stateId, $include_virtual);
		return $this->executeRequest('getCitiesByState',$request);
	}
	
	function getCitiesByVirtualCity($cityId)
	{
		$request = array($cityId);
		return $this->executeRequest('getCitiesByVirtualCity',$request);
	}
	
	function checkUniqueCountryName($countryName)
	{
		$request = array($countryName);
		$result = $this->executeRequest('checkUniqueCountryName',$request,'read',FALSE);
		return $result == 'Unique' ? TRUE: FALSE;
	}
	
	function checkUniqueCityName($cityName)
	{
		$request = array($cityName);
		$result = $this->executeRequest('checkUniqueCityName',$request,'read',FALSE);
		return $result == 'Unique' ? TRUE: FALSE;
	}
	
	function checkUniqueLocalityName($locality,$zone)
	{
		$request = array($locality,$zone);
		$result = $this->executeRequest('checkUniqueLocalityName',$request,'read',FALSE);
		return $result == 'Unique' ? TRUE: FALSE;
	}
	
	function checkUniqueZoneName($zone)
	{
		$request = array($zone);
		$result = $this->executeRequest('checkUniqueZoneName',$request,'read',FALSE);
		return $result == 'Unique' ? TRUE: FALSE;
	}
	
}