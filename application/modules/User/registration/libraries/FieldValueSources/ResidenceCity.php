<?php

/**
 * File for value source for residence city field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for residence city field
 */ 
class ResidenceCity extends AbstractValueSource
{
    /**
     * Get values
     *
     * @param array $params Additional parameters
     * @return array
     */ 
    
    public function getValues($params = array()) {
        $this->CI->load->library(array('LDB_Client','category_list_client','MY_sort_associative_array'));
        $categoryClient = new \Category_list_client();
        $values = array();
        
        if($params['isNational']){
            
            $tier1Cities = $categoryClient->getCitiesInTier($appId,1,2);
            $sorter = new \MY_sort_associative_array;
            
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
		
        }else{
            
            $values['tier1Cities'] = $categoryClient->getCitiesInTier($appId,1,2);
            $values['tier2Cities'] = $categoryClient->getCitiesInTier($appId,2,2);
            $values['tier3Cities'] = $categoryClient->getCitiesInTier($appId,0,2);

            $ldbObj = new \LDB_Client();

            $citiesData = json_decode($ldbObj->sgetCityStateList(12),true);
            $values['citiesByStates'] = $citiesData[0]['stateMap'];
            
        }

        return $values;
    }
    
    /**
     * deprecated
     */
    public function getValuesOld($params = array())
    {
        $this->CI->load->library(array('LDB_Client','category_list_client'));
		
		$values = array();
		
		$categoryClient = new \Category_list_client();
        
		$values['tier1Cities'] = $categoryClient->getCitiesInTier($appId,1,2);
		$values['tier2Cities'] = $categoryClient->getCitiesInTier($appId,2,2);
		$values['tier3Cities'] = $categoryClient->getCitiesInTier($appId,0,2);
		
		
		$ldbObj = new \LDB_Client();
		
		$citiesData = json_decode($ldbObj->sgetCityStateList(12),true);
        $values['citiesByStates'] = $citiesData[0]['stateMap'];
		
		return $values;
    }
}