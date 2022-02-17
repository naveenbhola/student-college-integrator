<?php
/**
 * File for Value source of preferred study locality field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source of preferred study locality field
 * The locality field is a combination of two fields
 * 
 * 1. City dropdown
 * 2. Locality dropdown, which is populated by AJAX based on the city selected
 * 
 * So the ValueSource will either return the cities or localities based on the parameter passed
 */ 

class PreferredStudyLocality extends AbstractValueSource
{
	/**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        if($cityId = $params['cityId']) {
			if($params['cityGroup']) {
				return $this->_getCityGroup($cityId);
			}
			else {
				return $this->_getLocalitiesForCity($cityId);
			}
		}
		
		if($params['cities']) {
			return $this->_getCitiesForLocalities();
		}
    }
	
	/**
	 * Get cities for localities
	 *
	 * @return array
	 */ 
	private function _getCitiesForLocalities()
	{
		$this->CI->load->library(array('LDB_Client','category_list_client','MY_sort_associative_array'));
		$categoryClient = new \Category_list_client();
		
		$tier1Cities = $categoryClient->getCitiesInTier($appId,1,2);
		$sorter = new \MY_sort_associative_array;
		
		$values = array();
		foreach($tier1Cities as $city) {
			if ($city['stateId'] == '-1') {
				$citiesInVirtualCity = json_decode($categoryClient->getCitiesForVirtualCity(1,$city['cityId']),true);
				$citiesInVirtualCity = $sorter->sort_associative_array($citiesInVirtualCity, 'city_name');
				$values['virtualCities'][$city['cityId']] = array('name' => $city['cityName'],'cities' => $citiesInVirtualCity);
			} else {
				$values['metroCities'][] = $city;
			}
		}
		
		krsort($values['virtualCities']);
		$values['metroCities'] = $sorter->sort_associative_array($values['metroCities'], 'cityName');
		
		$ldbObj = new \LDB_Client();
		$countryStateCityList = json_decode($ldbObj->sgetCityStateList(12),true);
		
		foreach($countryStateCityList as $list) {
			if($list['CountryId'] == 2) {
				$values['stateCities'] = $list['stateMap'];
			}
		}
		
		return $values;
	}
	
	/**
	 * Get localities of a city
	 *
	 * @param integer $cityId 
	 * @return array
	 */ 
	private function _getLocalitiesForCity($cityId)
	{
        $this->CI->load->library('category_list_client');
        $categoryClient = new \Category_list_client();
        $localities =  json_decode($categoryClient->getZonewiseLocalitiesForCityId(1, $cityId), true);
		return array_map('current',$localities);
	}
	
	/**
	 * Get citiies in the same virtual city as given city 
	 *
	 * @param integer $cityId 
	 * @return array
	 */ 
	private function _getCityGroup($cityId)
	{
        $this->CI->load->library('category_list_client');
        $categoryClient = new \Category_list_client();
		
        $appId = 1;
        $citiesInGroup = json_decode($categoryClient->getCityGroupInSameVirtualCity($appId, $cityId), true);
		return $citiesInGroup;
	}
}