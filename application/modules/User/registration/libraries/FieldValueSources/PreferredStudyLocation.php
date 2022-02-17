<?php
/**
 * File for Value source for preferred study location field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for preferred study location field
 */ 
class PreferredStudyLocation extends AbstractValueSource
{
	/**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        $this->CI->load->library(array('LDB_Client','category_list_client'));
		
		$values = array();
		
		$categoryClient = new \Category_list_client();
        
		$values['tier1Cities'] = $categoryClient->getCitiesInTier($appId,1,2);
		$values['tier2Cities'] = $categoryClient->getCitiesInTier($appId,2,2);
		$values['tier3Cities'] = $categoryClient->getCitiesInTier($appId,3,2);
		
		foreach($values['tier3Cities'] as $index => $city) {
		    if($city['cityId'] == 10166) {
			unset($values['tier3Cities'][$index]);
		    }
		}
		
		$ldbObj = new \LDB_Client();
		
		$citiesData = json_decode($ldbObj->sgetCityStateList(12),true);
        $values['citiesByStates'] = $citiesData[0]['stateMap'];
		
		$cdata = $this->getCityStatesFromMap($citiesData);
		$values['statesList'] = $cdata['states'];
		
		return $values;
    }
	
	/**
	 * Get city and states
	 *
	 * @param array $countryStateCityMap 
	 * @return array
	 */ 
	private function getCityStatesFromMap($countryStateCityMap)
	{
		$cities = $states = $countries = array();
		foreach($countryStateCityMap as $country) {
			$countryName = $country['CountryName'];
			$countryId = $country['CountryId'];
			$countries[$countryId]['name'] = $countryName;
			$countries[$countryId]['value'] = $countryId .':0:0'; // For TUserLocationPref
			$statesForCountry = $country['stateMap'];
			foreach($statesForCountry as $state) {
				$stateName = $state['StateName'];
				$stateId = $state['StateId'];
				if (!empty($stateId)) {
					$states[$stateId]['name'] = $stateName;
					$states[$stateId]['value'] = $countryId .':'. $stateId .':0';
				}
				$citiesForState = $state['cityMap'];
				foreach($citiesForState as $city) {
					$cityName = $city['CityName'];
					$cityId = $city['CityId'];
					$cityTier = $city['Tier'];
					$cities['tier'. $cityTier][$cityId]['name'] = $cityName;
					$cities['tier'. $cityTier][$cityId]['value'] = $countryId  .':'. $stateId .':'. $cityId;
				}
			}
		}
		return array('cities' => $cities, 'states' => $states, 'countries' => $countries);
	}
}