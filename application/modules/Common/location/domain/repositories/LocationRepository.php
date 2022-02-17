<?php

class LocationRepository extends EntityRepository
{
    function __construct($dao,$cache)
    {
        parent::__construct($dao,$cache);
        
        /*
         * Load entities required
         */ 
        $this->CI->load->entities(array('Locality','Zone','City','State','Country','Region'),'location');
    }
    
    public function findLocality($localityId)
    {
        Contract::mustBeNumericValueGreaterThanZero($localityId,'Locality ID');
        
        if($this->caching) {
            $data = $this->cache->getLocality($localityId);
            // _p($data);die('aaa');
        }

        if(empty($data)){
            $data = $this->dao->getLocality($localityId);
            $this->cache->storeLocality($localityId, $data);
        }
        
        $locality = new Locality;
        $this->fillObjectWithData($locality,$data);
        return $locality;
    }
    
    public function getLocalities()
    {
        if($this->caching && $localities = $this->cache->getLocalities()) {
            return $localities;
        }
        
        $localities = array();
        $localityDataResults = $this->dao->getLocalities();
        foreach($localityDataResults as $localityDataResult) {
            $locality = new Locality;
            $this->fillObjectWithData($locality,$localityDataResult);
            $localities[] = $locality;
        }
        $this->cache->storeLocalities($localities);
        return $localities;
    }

    public function getLocalitiesByZone($zoneId = 0)
    {
        if($this->caching && $localities = $this->cache->getLocalitiesByZone($zoneId)) {
            return $localities;
        }

        $localityDataResults = $this->dao->getLocalitiesByZone($zoneId);
        $localities = array();
        foreach($localityDataResults as $localityDataResult) {
            $locality = new Locality;
            $this->fillObjectWithData($locality,$localityDataResult);
            $localities[] = $locality;
        }
        $this->cache->storeLocalitiesByZone($zoneId,$localities);
        return $localities;
    }
    
    public function getLocalitiesByCity($cityId = 0)
    {
        if($this->caching && $localities = $this->cache->getLocalitiesByCity($cityId)) {
            return $localities;
        }
        
        $localityDataResults = $this->dao->getLocalitiesByCity($cityId);
        $localities = array();
        foreach($localityDataResults as $localityDataResult) {
            $locality = new Locality;
            $this->fillObjectWithData($locality,$localityDataResult);
            $localities[] = $locality;
        }
        $this->cache->storeLocalitiesByCity($cityId,$localities);
        return $localities;
    }

    public function getZones()
    {
        if($this->caching && $zones = $this->cache->getZones()) {
            return $zones;
        }

        $zones = array();
        $zonesDataResults = $this->dao->getZones();
        foreach($zonesDataResults as $zonesDataResult) {
            $zone = new Zone;
            $this->fillObjectWithData($zone,$zonesDataResult);
            $zones[] = $zone;
        }
        $this->cache->storeZones($zones);
        return $zones;
    }

    public function getZonesByCity($cityId = 0)
    {
        if($this->caching && $zones = $this->cache->getZonesByCity($cityId)) {
            return $zones;
        }

        $zonesDataResults = $this->dao->getZonesByCity($cityId);
        $zones = array();
        foreach($zonesDataResults as $zonesDataResult) {
            $zone = new Zone;
            $this->fillObjectWithData($zone,$zonesDataResult);
            $zones[] = $zone;
        }
        $this->cache->storeZonesByCity($cityId,$zones);
        return $zones;
    }

    public function findCity($cityId)
    {
        Contract::mustBeNumericValueGreaterThanZero($cityId,'City ID');
        
        if($this->caching) {
            $data = $this->cache->getCity($cityId);
            // _p($data);die('aaa');
        }
        if(empty($data)){
            $data = $this->dao->getCity($cityId);
            // _p($data);die;
            $this->cache->storeCity($cityId, $data);
        }
        
        $city = new City;
        $this->fillObjectWithData($city,$data);

        return $city;
    }
    
	/**
	 * return Cities objects indexed with city id
	 *
	 * @param array $cityIds city ids
	 * @return Object
	 */
	public function findMultipleCities($cityIds)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($cityIds,'City ID');

        $dataFromDb = array();
        $dataFromCache = array();

        if($this->caching) {
            $dataFromCache = $this->cache->getMultipleCities($cityIds);
            // _p($dataFromCache);die('aaa');
        }

        $idsFromDb = array_diff($cityIds, array_keys($dataFromCache));
        if(!empty($idsFromDb)){
            $dataFromDb = $this->dao->getMultipleCity($idsFromDb);
            foreach ($dataFromDb as $key => $row) {
                $this->cache->storeCity($key, $row);
            }
        }
        // _p($dataFromDb);die;

        $finalData = array();
        foreach ($dataFromCache as $key => $row) {
            $finalData[$key] = $row;
        }
        foreach ($dataFromDb as $key => $row) {
            $finalData[$key] = $row;
        }

        $returnData = array();
        foreach ($finalData as $key => $row) {
            $city = new City;
            $this->fillObjectWithData($city,$row);
            $returnData[$key] = $city;
        }
        return $returnData;

		/*$orderOfCityIds = $cityIds;
		$citiesFromCache = array();

		if($this->caching) {
			$citiesFromCache = $this->cache->getMultipleCities($cityIds);
			$foundInCache = array_keys($citiesFromCache);
			$cityIds = array_diff($cityIds,$foundInCache);
		}

        $cityFromDB = array();
		if(count($cityIds) > 0) {
			$cityDataResults = $this->dao->getMultipleCity($cityIds);
			foreach($cityDataResults as $cityData) {
                $city = new City;
                $this->fillObjectWithData($city,$cityData);                                
				$this->cache->storeCity($city);
                $cityFromDB[$city->getId()] = $city;
			}
		}

		$cities = array();
		foreach($orderOfCityIds as $cityId) {
			if(isset($citiesFromCache[$cityId])) {
				$cities[$cityId] = $citiesFromCache[$cityId];
			}
			else if(isset($cityFromDB[$cityId])) {
				$cities[$cityId] = $cityFromDB[$cityId];
			}
		}
    
		return $cities;*/
	}
    
    public function findMultipleLocalities($localityIds)
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($localityIds,'Locality ID');

        $dataFromCache = array();
        $dataFromDb = array();
        if($this->caching) {
            $dataFromCache   = $this->cache->getMultipleLocalities($localityIds);
            // _p($dataFromCache);die('aaa');
        }

        $idsFromDb = array_diff($localityIds, array_keys($dataFromCache));
        if(!empty($idsFromDb)){
            $data = $this->dao->getMultipleLocalities($idsFromDb);
            foreach ($data as $row) {
                $this->cache->storeLocality($row['localityId'],$row);
                $dataFromDb[$row['localityId']] = $row;
            }
            // _p($dataFromDb);die;
        }

        $returnData = array();
        $finalData = array();
        foreach ($dataFromCache as $key => $row) {
            $finalData[$key] = $row;
        }
        foreach ($dataFromDb as $key => $row) {
            $finalData[$key] = $row;
        }
        foreach ($finalData as $row) {
            $locality = new Locality;
            $this->fillObjectWithData($locality, $row);
            $returnData[$locality->getId()] = $locality;
        }
        return $returnData;

		/*$orderOfLocalityIds = $localityIds;
		$localityFromMemcache = array();
        $localitiesFromDb = array();
		if($this->caching) {
			$localityFromMemcache   = $this->cache->getMultipleLocalities($localityIds);
            _p($localityFromMemcache);die;
			$foundInCache           = array_keys($localityFromMemcache);
			$localityIds            = array_diff($localityIds, $foundInCache);
		}
        
		if(count($localityIds) > 0) {
            $localityDataResults = $this->dao->getMultipleLocalities($localityIds);
            foreach($localityDataResults as $localityData) {
                    $locality = new Locality;
                    $this->fillObjectWithData($locality, $localityData);
                    $this->cache->storeLocality($locality);
                    $localitiesFromDb[$localityData['localityId']] = $locality;
            }
		}

		$localities = array();
		foreach($orderOfLocalityIds as $localityId) {
			if(isset($localityFromMemcache[$localityId])) {
				$localities[$localityId] = $localityFromMemcache[$localityId];
			}
			else if(isset($localitiesFromDb[$localityId])) {
				$localities[$localityId] = $localitiesFromDb[$localityId];
			}
		}
    
		return $localities;*/
    }
    
    public function findMultipleStates($stateIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($stateIds,'State ID');

        $dataFromCache = array();
        $dataFromDb = array();

        if($this->caching) {
            $dataFromCache   = $this->cache->getMultipleStates($stateIds);
            // _p($dataFromCache);die('aaa');
        }

        $idsFromDb = array_diff($stateIds, array_keys($dataFromCache));
        if(!empty($idsFromDb)){
            $data = $this->dao->getMultipleStates($idsFromDb);
            foreach ($data as $row) {
                $dataFromDb[$row['state_id']] = $row;
                $this->cache->storeState($row['state_id'],$row);
            }
        }

        // _p($dataFromDb);die;

        $finalData = array();
        foreach ($dataFromCache as $key => $row) {
            $finalData[$key] = $row;
        }
        foreach ($dataFromDb as $key => $row) {
            $finalData[$key] = $row;
        }

        $returnData = array();
        foreach ($finalData as $key => $row) {
            $state = new State;
            $this->fillObjectWithData($state, $row);
            $returnData[$key] = $state;
        }
        return $returnData;

		// $orderOfStateIds = $stateIds;
		// $statesFromMemcache = array();
  //       $statesFromDb = array();
		// if($this->caching) {
		// 	$statesFromMemcache   = $this->cache->getMultipleStates($stateIds);
		// 	$foundInCache           = array_keys($statesFromMemcache);
		// 	$stateIds            = array_diff($stateIds, $foundInCache);
		// }

		// if(count($stateIds) > 0) {
  //           $stateDataResults = $this->dao->getMultipleStates($stateIds);
  //           foreach($stateDataResults as $stateData) {
  //               $state = new State;
  //               $this->fillObjectWithData($state, $stateData);
  //               $this->cache->storeState($state);
  //               $statesFromDb[$stateData['state_id']] = $state;
  //           }
		// }

		// $states = array();
		// foreach($orderOfStateIds as $stateId) {
		// 	if(isset($statesFromMemcache[$stateId])) {
		// 		$states[$stateId] = $statesFromMemcache[$stateId];
		// 	}
		// 	else if(isset($statesFromDb[$stateId])) {
		// 		$states[$stateId] = $statesFromDb[$stateId];
		// 	}
		// }
    
		// return $states;
    }
   
    
    public function findMultipleZones($zoneIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($zoneIds,'Zone ID');

		$orderOfZoneIds = $zoneIds;
		$zonesFromMemcache = array();
        $zonesFromDb = array();
		if($this->caching) {
			$zonesFromMemcache   = $this->cache->getMultipleZones($zoneIds);
			$foundInCache         = array_keys($zonesFromMemcache);
			$zoneIds            = array_diff($zoneIds, $foundInCache);
		}

		if(count($zoneIds) > 0) {
            $zoneDataResults = $this->dao->getMultipleZones($zoneIds);
            foreach($zoneDataResults as $zoneData) {
                $zone = new Zone;
                $this->fillObjectWithData($zone, $zoneData);
                $this->cache->storeZone($zone);
                $zonesFromDb[$zoneData['zoneId']] = $zone;
            }
		}

		$zones = array();
		foreach($orderOfZoneIds as $zoneId) {
			if(isset($zonesFromMemcache[$zoneId])) {
				$zones[$zoneId] = $zonesFromMemcache[$zoneId];
			}
			else if(isset($zonesFromDb[$zoneId])) {
				$zones[$zoneId] = $zonesFromDb[$zoneId];
			}
		}
    
		return $zones;
    }
    
    public function getCitiesByMultipleTiers($tiers,$countryId=0)
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($tiers,'Tiers');

        $finalData = array();

        if($this->caching) {
            foreach($tiers as $tier) {
                $data = $this->cache->getCitiesByTier($tier."-".$countryId);
                if(!empty($data)){
                    $finalData[$tier] = $data;
                }
            }
        }
        // _p($finalData);die('aaa');


        $tiersFromDb = array_diff($tiers, array_keys($finalData));
        if(!empty($tiersFromDb)){
            $data = $this->dao->getCitiesByMultipleTiers($tiersFromDb,$countryId);
            foreach ($data as $tier => $row) {
                $this->cache->storeCitiesByTier($tier."-".$countryId,$row);
                $finalData[$tier] = $row;
            }
        }
        // _p($finalData);die;

        $returnData = array();
        foreach ($finalData as $tier => $tierResults) {
            foreach($tierResults as $result) {
                $city = new City;
                $this->fillObjectWithData($city,$result);
                $returnData[$tier][] = $city;
            }
        }
        return $returnData;

        
        /*$cities = array();
        if($this->caching) {
            foreach($tiers as $tier) {
                if($citiesByTier = $this->cache->getCitiesByTier($tier."-".$countryId)) {
                    $cities[$tier] = $citiesByTier;
                }
            }
        }
        // _p($cities);die('aaa');
        
        $tiers = array_diff($tiers,array_keys($cities));
        if(count($tiers) > 0) { 
            $cityDataResults = $this->dao->getCitiesByMultipleTiers($tiers,$countryId);
            _p($cityDataResults);die;
            foreach($cityDataResults as $tier => $tierResults) {
                foreach($tierResults as $result) {
                    $city = new City;
                    $this->fillObjectWithData($city,$result);
                    $cities[$tier][] = $city;
                }
                $this->cache->storeCitiesByTier($tier."-".$countryId,$cities[$tier]);
            }
        }
        
        return $cities;*/
    }


    /**
     * @param     $tiers
     * @param int $countryId
     *
     * @return array
     *
     * @see \Category_list_client::getstateBasedontier
     */
    public function getStatesByMultipleTiers($tiers,$countryId=0)
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($tiers,'Tiers');

        $states = array();
        if($this->caching) {
            foreach($tiers as $tier) {
                if($statesByTier = $this->cache->getStatesByTier($tier."-".$countryId)) {
                    $states[$tier] = $statesByTier;
                }
            }
        }

        $tiers = array_diff($tiers,array_keys($states));
        if(count($tiers) > 0) {
            $stateDataResults = $this->dao->getStatesByMultipleTiers($tiers,$countryId);
            foreach($stateDataResults as $tier => $tierResults) {
                foreach($tierResults as $result) {
                    $state = new State();
                    $this->fillObjectWithData($state,$result);
                    $states[$tier][] = $state;
                }
                $this->cache->storeStatesByTier($tier."-".$countryId,$states[$tier]);
            }
        }

        return $states;
    }


    public function getCitiesHavingZones()
    {
        if($this->caching && $cities = $this->cache->getCitiesHavingZones()) {
            return $cities;
        }

        $cities = array();
        $citiesDataArray = $this->dao->getCitiesHavingZones();
        foreach($citiesDataArray as $cityData) {
            $city = new City;
            $this->fillObjectWithData($city,$cityData);
            $cities[] = $city;
        }
        $this->cache->storeCitiesHavingZones($cities);
        return $cities;
    }
    
    public function findCountry($countryId)
    {
        Contract::mustBeNumericValueGreaterThanZero($countryId,'Country ID');
        
        if($this->caching && $country = $this->cache->getCountry($countryId)) {
            return $country;
        }
        
        $countryDataResults = $this->dao->getCountry($countryId);
        $country = new Country;
        $this->fillObjectWithData($country,$countryDataResults);
        $this->cache->storeCountry($country);
        return $country;
    }
    
    public function getCountries()
    {
        if($this->caching && $countries = $this->cache->getCountries()) {
            return $countries;
        }
        
        $countries = array();
        $countryDataResults = $this->dao->getCountries();
        foreach($countryDataResults as $countryDataResult) {
            $country = new Country;
            $this->fillObjectWithData($country,$countryDataResult);
            $countries[] = $country;
        }
        $this->cache->storeCountries($countries);
        return $countries;
    }
    
    public function getCountriesByRegion($regionId = 0)
    {
        if($this->caching && $countries = $this->cache->getCountriesByRegion($regionId)) {
            return $countries;
        }
        
        $countryDataResults = $this->dao->getCountriesByRegion($regionId);
        
        $countries = array();
        foreach($countryDataResults as $result) {
            $country = new Country;
            $this->fillObjectWithData($country,$result);
            
            $countries[] = $country;
        }
        
        $this->cache->storeCountriesByRegion($regionId,$countries);
        return $countries;
    }
    
    public function getCountryByURLName($urlName)
    {
        $countryDataResults = $this->dao->getCountryByURLName($urlName);
        $country = new Country;
        $this->fillObjectWithData($country,$countryDataResults);
        return $country;
    }
    
    public function findZone($zoneId)
    {
        Contract::mustBeNumericValueGreaterThanZero($zoneId,'Zone ID');
        
        if($this->caching && $zone = $this->cache->getZone($zoneId)) {
            return $zone;
        }
        $zoneDataResults = $this->dao->getZone($zoneId);
        $zone = new Zone;
        $this->fillObjectWithData($zone,$zoneDataResults);
        $this->cache->storeZone($zone);
        return $zone;
    }
    
    public function findState($stateId)
    {
        Contract::mustBeNumericValueGreaterThanZero($stateId,'State ID');
        
        if($this->caching) {
            $data = $this->cache->getState($stateId);
            // _p($data);die('aaa');
        }
        if(empty($data)){
            $data = $this->dao->getState($stateId);
            // _p($data);die;
            $this->cache->storeState($stateId, $data);
        }
        
        $state = new State;
        $this->fillObjectWithData($state,$data);
        return $state;
    }
    
    public function getStatesByCountry($countryId = 0)
    {
        if($this->caching && $states = $this->cache->getStatesByCountry($countryId)) {
            return $states;
        }
        
        $stateDataResults = $this->dao->getStatesByCountry($countryId);   
        $states = array();
        foreach($stateDataResults as $result) {
            $state = new State;
            $this->fillObjectWithData($state,$result);
            $states[] = $state;
        }
        $this->cache->storeStatesByCountry($countryId,$states);
        return $states;
    }
        
    public function findRegion($regionId)
    {
        Contract::mustBeNumericValueGreaterThanZero($regionId,'Region ID');
        
        if($this->caching && $region = $this->cache->getRegion($regionId)) {
            return $region;
        }
        
        $regionDataResults = $this->dao->getRegion($regionId);
        $region = new Region;
        $this->fillObjectWithData($region,$regionDataResults);
        $this->cache->storeRegion($region);
        return $region;
    }
    
    public function getRegions()
    {
        if($this->caching && $regions = $this->cache->getRegions()) {
            return $regions;
        }
        
        $regions = array();
        $regionDataResults = $this->dao->getRegions();
        foreach($regionDataResults as $regionDataResult) {
            $region = new Region;
            $this->fillObjectWithData($region,$regionDataResult);
            $regions[] = $region;
        }
        $this->cache->storeRegions($regions);
        return $regions;
    }
    
    public function getRegionByURLName($urlName)
    {
        $regionDataResults = $this->dao->getRegionByURLName($urlName);
        $region = new Region;
        $this->fillObjectWithData($region,$regionDataResults);
        return $region;
    }
    
    public function getCities($countryId = 0, $include_virtual=False)
    {
       
    	if($this->caching && $cities = $this->cache->getCities($countryId,$include_virtual)) {
    		return $cities;
    	}
    	$cityDataResults = $this->dao->getCities($countryId, $include_virtual);
        $cities = array();
        foreach($cityDataResults as $result) {
            $city = new City;
            $this->fillObjectWithData($city,$result);
            $cities[] = $city;
        }
        
        $this->cache->storeCities($countryId,$include_virtual,$cities);
        
        return $cities;
    }
    
    public function getCitiesByState($stateId = 0)
    {
        if($this->caching && $cities = $this->cache->getCitiesByState($stateId)) {
            return $cities;
        }
        
        $cityDataResults = $this->dao->getCitiesByState($stateId);
        $cities = array();
        foreach($cityDataResults as $cityDataResult) {
            $city = new City;
            $this->fillObjectWithData($city,$cityDataResult);
            $cities[] = $city;
        }
        $this->cache->storeCitiesByState($stateId,$cities);
        return $cities;
    }

    public function getCitiesByMultipleStates($stateIds)
    {
        $orderOfStateIds = $stateIds;
        $statesFromCache = array();

        if($this->caching) {
            $statesFromCache = $this->cache->getCitiesByMultipleStates($stateIds);
            $foundInCache = array_keys($statesFromCache);
            $stateIds = array_diff($stateIds,$foundInCache);
        }
        $stateFromDB = array();
        if(count($stateIds) > 0) 
        {
            foreach ($stateIds as $key => $stateId) 
            {
                $cityDataResults = $this->dao->getCitiesByState($stateId);
                $cities = array();
                foreach($cityDataResults as $cityDataResult) {
                    $city = new City;
                    $this->fillObjectWithData($city,$cityDataResult);
                    $cities[] = $city;
                }
                $this->cache->storeCitiesByState($stateId,$cities);
                $stateFromDB[$stateId] = $cities;
            }
        }
       
        $cities = array();
        foreach($orderOfStateIds as $stateId) {
            if(isset($statesFromCache[$stateId])) {
                $cities[$stateId] = $statesFromCache[$stateId];
            }
            else if(isset($stateFromDB[$stateId])) {
                $cities[$stateId] = $stateFromDB[$stateId];
            }
        }
        return $cities;
    }
    
    public function getCitiesByVirtualCity($cityId = 0)
    {
        if($this->caching) {
            $data = $this->cache->getCitiesByVirtualCity($cityId);
        }
        // _p($data);die('aaa');

        if(empty($data)){
            $data = $this->dao->getCitiesByVirtualCity($cityId);
            $this->cache->storeCitiesByVirtualCity($cityId,$data);
        }

        // _p($data);die;
        
        $cities = array();
        foreach($data as $cityDataResult) {
            $city = new City;
            $this->fillObjectWithData($city,$cityDataResult);
            $cities[] = $city;
        }
        return $cities;
    }
    
    /*
     * Save methods
     */
    
    public function saveCountry($countryName,$regionId = 0)
    {
        $countryId = $this->dao->saveCountry($countryName,$regionId);
        return $countryId;
    }
    
    public function saveCity($cityName,$stateId,$countryId)
    {
        $cityId = $this->dao->saveCity($cityName,$stateId,$countryId);
        return $cityId;
    }
    
    public function saveZone($zone)
    {
        $zoneId = $this->dao->saveZone($zone);
        return $zoneId;
    }
    
    public function saveLocality($localityName,$zone,$city)
    {
        $locality = $this->dao->saveLocality($localityName,$zone,$city);
        return $locality;
    }
    
    public function getAbroadCountries()
    {
        if($this->caching && $countries = $this->cache->getAbroadCountries()) {
            return $countries;
        }
        $countries = array();
        $countryDataResults = $this->dao->getAbroadCountries();
        foreach($countryDataResults as $countryDataResult) {
            $country = new Country;
            $this->fillObjectWithData($country,$countryDataResult);
            $countries[$country->getName()] = $country;
        }
        $this->cache->storeAbroadCountries($countries);
        return $countries;
    }
    /*
     * find multiple countries without any restriction, india /abroad/shown on registration or not, all of them
     * @params: countryIds - non-empty array please
     */
    public function findMultipleCountries($countryIds)
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($countryIds,'Country IDs');
        if($this->caching){
            $countries = $this->cache->getMultipleCountries($countryIds);
        }
        if(count($countries) == count($countryIds))
        {   // return if you found'em all
            return $countries;
        }else{
            // gotta find'em all 
            $foundCountryIds = array_map(function($a){ return $a->getId(); },$countries);
            $remainingCountryIds = array_diff($countryIds, $foundCountryIds);
        }
        $countries = array();
        $this->locModel = $this->CI->load->model('location/locationmodel');
        $countryDataResults = $this->locModel->getAllCountries($remainingCountryIds);
        foreach($countryDataResults as $countryDataResult) {
            $country = new Country;
            $this->fillObjectWithData($country,$countryDataResult);
            $this->cache->storeCountry($country);
            $countries[$country->getName()] = $country;
        }
        return $countries;
    }
    
    public function getAllCountries()
    {
        $this->locModel = $this->CI->load->model('location/locationmodel');
        $countryIds = $this->locModel->getAllCountryIds();
        $countries = $this->findMultipleCountries($countryIds);
        return $countries;
    }
    
    public function getAbroadCountryByIds($countryIds = array(),$allFlag = false)
    {
        $countryList = array();
        if(!$allFlag){
            $countries = $this->getAbroadCountries();
        }else{
           $countries = $this->getAllCountries();
        }
        foreach($countries as $countryobj){
            $countryId = $countryobj->getId();
            if(in_array($countryId, $countryIds)){
                $countryList[$countryId] = $countryobj;
            }
        }
        return $countryList;
    }
    
    public function groupLocationByAlphabet($lists)
    {
        $resultArray = array();
        if(is_array($lists)){
            foreach($lists as $key=>$list){
               $name = $key;
               $currentchar = strtolower(substr($name,0,1));
               $resultArray[$currentchar][] = $list; 
            }
            return $resultArray;
        }else{
            return $lists;
        }
    }
    public function disableCaching()
    {
        $this->caching = false;
    }

    public function clearLocationCache() {
        $this->cache->clearLocationCache();
    }

function storeHierarchy($data){
    	$this->cache->storeLocationHierarchy($data);
    }

    function getHierarchy(){
    	return $this->cache->getLocationHierarchy();
    }
}