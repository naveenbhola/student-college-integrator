<?php
/**
 * File for Value source for destination country field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for destination country field
 */ 
class DestinationCountry extends AbstractValueSource
{
	/**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {		
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder = new \LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		
		if($params['twoStep'] && $params['registerationDomain']=='studyAbroad') {
                    $countries = $locationRepository->getAbroadCountries();
                    ksort($countries); //short by name
		    return $countries;
                }
		
		$regions = $locationRepository->getRegions();
		foreach($regions as $region) {
			$regions[$region->getId()] = $region;
		}
		unset($regions[0]);
		
		$countries = $locationRepository->getCountries();
		usort($countries,function($country1,$country2) {
			return strcasecmp ($country1->getName(),$country2->getName());
		});
		
		$regionWiseCountries = array();
		foreach($countries as $country) {
			if($country->getId() > 2) {
				$regionWiseCountries[intval($country->getRegionId())][] = $country;
			}
		}
		
		ksort($regionWiseCountries);
		return array('regions' => $regions, 'countries' => $regionWiseCountries);
    }
}