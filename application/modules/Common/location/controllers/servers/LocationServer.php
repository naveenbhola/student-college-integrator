<?php

class LocationServer extends MX_Controller
{
	private $model;
	
	function index()
	{
		$this->load->model('location/locationmodel','',TRUE);
		$this->model = $this->locationmodel;
		
		$config['functions']['getCity'] = array('function' => 'LocationServer.getCity');
        $config['functions']['getMultipleCity'] = array('function' => 'LocationServer.getMultipleCity');
		$config['functions']['getCitiesByMultipleTiers'] = array('function' => 'LocationServer.getCitiesByMultipleTiers');
		$config['functions']['getStatesByMultipleTiers'] = array('function' => 'LocationServer.getStatesByMultipleTiers');
		$config['functions']['saveCity'] = array('function' => 'LocationServer.saveCity');
		$config['functions']['getCitiesHavingZones'] = array('function' => 'LocationServer.getCitiesHavingZones');
		
		$config['functions']['getCountry'] = array('function' => 'LocationServer.getCountry');
		$config['functions']['getCountries'] = array('function' => 'LocationServer.getCountries');
		$config['functions']['getAbroadCountries'] = array('function' => 'LocationServer.getAbroadCountries');
		
		$config['functions']['getCountriesByRegion'] = array('function' => 'LocationServer.getCountriesByRegion');
		$config['functions']['getCountryByURLName'] = array('function' => 'LocationServer.getCountryByURLName');
		$config['functions']['getCities'] = array('function' => 'LocationServer.getCities');
		$config['functions']['getCitiesByState'] = array('function' => 'LocationServer.getCitiesByState');
		$config['functions']['getCitiesByVirtualCity'] = array('function' => 'LocationServer.getCitiesByVirtualCity');
		$config['functions']['saveCountry'] = array('function' => 'LocationServer.saveCountry');
		$config['functions']['saveZone'] = array('function' => 'LocationServer.saveZone');
		$config['functions']['saveLocality'] = array('function' => 'LocationServer.saveLocality');

				
		
		$config['functions']['getState'] = array('function' => 'LocationServer.getState');
		$config['functions']['getStatesByCountry'] = array('function' => 'LocationServer.getStatesByCountry');
		
		$config['functions']['getZone'] = array('function' => 'LocationServer.getZone');
		$config['functions']['getZones'] = array('function' => 'LocationServer.getZones');
		$config['functions']['getZonesByCity'] = array('function' => 'LocationServer.getZonesByCity');
		$config['functions']['getLocalitiesByZone'] = array('function' => 'LocationServer.getLocalitiesByZone');
		$config['functions']['getLocalitiesByCity'] = array('function' => 'LocationServer.getLocalitiesByCity');
		$config['functions']['getLocality'] = array('function' => 'LocationServer.getLocality');
		$config['functions']['getLocalities'] = array('function' => 'LocationServer.getLocalities');
		
		$config['functions']['getRegion'] = array('function' => 'LocationServer.getRegion');
		$config['functions']['getRegions'] = array('function' => 'LocationServer.getRegions');
		$config['functions']['getRegionByURLName'] = array('function' => 'LocationServer.getRegionByURLName');
		
		$config['functions']['checkUniqueCountryName'] = array('function' => 'LocationServer.checkUniqueCountryName');
		$config['functions']['checkUniqueCityName'] = array('function' => 'LocationServer.checkUniqueCityName');
		$config['functions']['checkUniqueZoneName'] = array('function' => 'LocationServer.checkUniqueZoneName');
		$config['functions']['checkUniqueLocalityName'] = array('function' => 'LocationServer.checkUniqueLocalityName');
		$config['functions']['getMultipleStates'] = array('function' => 'LocationServer.getMultipleStates');
		$config['functions']['getMultipleLocalities'] = array('function' => 'LocationServer.getMultipleLocalities');
		$config['functions']['getMultipleZones'] = array('function' => 'LocationServer.getMultipleZones');

		
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}

	function getCity($request)
	{
		$parameters = $request->output_parameters();
		$cityId = $parameters['0'];
	
        try {
            $response = $this->model->getCity($cityId);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}

	function getMultipleCity($request)
	{
		$parameters = $request->output_parameters();
		$cityArr = $parameters['0'];

        try {
            $response = $this->model->getMultipleCity($cityArr);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}

	function getMultipleLocalities($request)
	{
		$parameters = $request->output_parameters();
		$localityIds = $parameters['0'];

        try {
            $response = $this->model->getMultipleLocalities($localityIds);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getMultipleStates($request)
	{
		$parameters = $request->output_parameters();
		$stateIds = $parameters['0'];

        try {
            $response = $this->model->getMultipleStates($stateIds);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getMultipleZones($request)
	{
		$parameters = $request->output_parameters();
		$zoneIds = $parameters['0'];

        try {
            $response = $this->model->getMultipleZones($zoneIds);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	
	
	function getCitiesByMultipleTiers($request)
	{
		$parameters = $request->output_parameters();
		$tiers = $parameters['0'];
		$countryId = $parameters['1'];
	
        try {
            $response = $this->model->getCitiesByMultipleTiers($tiers,$countryId);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}

	function getStatesByMultipleTiers($request)
	{
		$parameters = $request->output_parameters();
		$tiers = $parameters['0'];
		$countryId = $parameters['1'];

        try {
            $response = $this->model->getStatesByTier($tiers,$countryId);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}

        function getCitiesHavingZones($request) {
            $parameters = $request->output_parameters();
            $response = $this->model->getCitiesHavingZones();
            return $this->sendXmlRpcResponse($response);
        }
	
	function getCountry($request)
	{
		$parameters = $request->output_parameters();
		$countryId = $parameters['0'];
	
        try {
            $response = $this->model->getCountry($countryId);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getCountries($request)
	{
		$parameters = $request->output_parameters();

		$response = $this->model->getCountries();
		return $this->sendXmlRpcResponse($response);
	}
	
	function getAbroadCountries($request)
	{
		$parameters = $request->output_parameters();

		$response = $this->model->getAbroadCountries();
		return $this->sendXmlRpcResponse($response);
	}
	
	
	function getCountriesByRegion($request)
	{
		$parameters = $request->output_parameters();
		$regionId = $parameters['0'];
	
        try {
            $response = $this->model->getCountriesByRegion($regionId);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getCountryByURLName($request)
	{
		$parameters = $request->output_parameters();
		$urlName = $parameters['0'];
		$response = $this->model->getCountryByURLName($urlName);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getState($request)
	{
		$parameters = $request->output_parameters();
		$stateId = $parameters['0'];
	
                try {
                    $response = $this->model->getState($stateId);
                                return $this->sendXmlRpcResponse($response);
                }
                catch(Exception $e) {
                    return $this->sendXmlRpcError($e->getMessage());
                }
	}
	
	function getStatesByCountry($request)
	{
		$parameters = $request->output_parameters();
		$countryId = $parameters['0'];
	
		$response = $this->model->getStatesByCountry($countryId);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getZone($request)
	{
		$parameters = $request->output_parameters();
		$zoneId = $parameters['0'];
	
                try {
                    $response = $this->model->getZone($zoneId);
                                return $this->sendXmlRpcResponse($response);
                }
                catch(Exception $e) {
                    return $this->sendXmlRpcError($e->getMessage());
                }
	}

        function getZones($request)
	{
		$parameters = $request->output_parameters();

		$response = $this->model->getZones();
		return $this->sendXmlRpcResponse($response);
	}

	function getZonesByCity($request)
	{
		$parameters = $request->output_parameters();
		$cityId = $parameters['0'];

		$response = $this->model->getZones($cityId);
		return $this->sendXmlRpcResponse($response);
	}

	function getLocality($request)
	{
		$parameters = $request->output_parameters();
		$localityId = $parameters['0'];
	
        try {
            $response = $this->model->getLocality($localityId);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getLocalities($request)
	{
		$parameters = $request->output_parameters();
		
		$response = $this->model->getLocalities();
		return $this->sendXmlRpcResponse($response);
	}

	function getLocalitiesByZone($request)
	{
		$parameters = $request->output_parameters();
		$zoneId = $parameters['0'];

		$response = $this->model->getLocalities($zoneId, 0);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getLocalitiesByCity($request)
	{
		$parameters = $request->output_parameters();
		$cityId = $parameters['0'];

		$response = $this->model->getLocalities(0, $cityId);
		return $this->sendXmlRpcResponse($response);
	}

	function getRegion($request)
	{
		$parameters = $request->output_parameters();
		$regionId = $parameters['0'];
	
        try {
            $response = $this->model->getRegion($regionId);
			return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getRegions($request)
	{
		$parameters = $request->output_parameters();
	
		$response = $this->model->getRegions();
		return $this->sendXmlRpcResponse($response);
	}
	
	function getRegionByURLName($request)
	{
		$parameters = $request->output_parameters();
		$urlName = $parameters['0'];
		$response = $this->model->getRegionByURLName($urlName);
		return $this->sendXmlRpcResponse($response);
	}

	function saveCity($request)
	{
		$parameters = $request->output_parameters();
		$cityName = $parameters['0'];
		$stateId = $parameters['1'];
		$countryId = $parameters['2'];
		$response = $this->model->saveCity($cityName, $stateId, $countryId);
		return $this->sendXmlRpcResponse($response,FALSE);
	}

	function saveCountry($request)
	{
		$parameters = $request->output_parameters();
		$countryName = $parameters['0'];
		$regionId = $parameters['1'];
		error_log($countryName."-".$regionId);
		$response = $this->model->saveCountry($countryName,$regionId);
		return $this->sendXmlRpcResponse($response,FALSE);
	}
	
	function saveLocality($request)
	{
		$parameters = $request->output_parameters();
		$localityName = $parameters['0'];
		$zone = $parameters['1'];
		$city= $parameters['2'];

		$response = $this->model->saveLocality($localityName,$zone,$city);
		return $this->sendXmlRpcResponse($response,FALSE);
	}
	
	function saveZone($request)
	{
		$parameters = $request->output_parameters();
		$zoneName = $parameters['0'];
				
		$response = $this->model->saveZone($zoneName);
		return $this->sendXmlRpcResponse($response,FALSE);
	}

	function getCities($request)
	{
		$parameters = $request->output_parameters();
		$countryId = $parameters['0'];
		$include_virtual = $parameters['1'];
		$response = $this->model->getCities($countryId, $include_virtual);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getCitiesByState($request)
	{
		$parameters = $request->output_parameters();
		$stateId = $parameters['0'];
		$include_virtual = $parameters['1'];
		
		$response = $this->model->getCitiesByState($stateId, $include_virtual);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getCitiesByVirtualCity($request)
	{
		$parameters = $request->output_parameters();
		$cityId = $parameters['0'];
		
		$response = $this->model->getCitiesByVirtualCity($cityId);
		return $this->sendXmlRpcResponse($response);
	}
	
	function checkUniqueCountryName($request)
	{
		$parameters = $request->output_parameters();
		$countryName = $parameters['0'];
		$response = $this->model->checkUniqueCountryName($countryName);
		return $this->sendXmlRpcResponse($response,FALSE);
	}
	
	function checkUniqueCityName($request)
	{
		$parameters = $request->output_parameters();
		$cityName = $parameters['0'];
		$response = $this->model->checkUniqueCityName($cityName);
		return $this->sendXmlRpcResponse($response,FALSE);
	}
	
	function checkUniqueLocalityName($request)
	{
		$parameters = $request->output_parameters();
		$locality = $parameters['0'];
		$zone = $parameters['1'];

		$response = $this->model->checkUniqueLocalityName($locality,$zone);
		return $this->sendXmlRpcResponse($response,FALSE);
	}
	
	function checkUniqueZoneName($request)
	{
		$parameters = $request->output_parameters();
		$zone = $parameters['0'];
		$response = $this->model->checkUniqueZoneName($zone);
		return $this->sendXmlRpcResponse($response,FALSE);
	}
	
	
	
}