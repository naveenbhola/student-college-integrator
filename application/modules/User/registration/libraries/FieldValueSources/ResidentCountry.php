<?php
/**
 * File for Value source for resident country field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for resident country field
 */ 
class ResidentCountry extends AbstractValueSource
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
			if($country->getId() > 1) {
				$regionWiseCountries[intval($country->getRegionId())][] = $country;
			}
		}
		
		ksort($regionWiseCountries);
		return array('regions' => $regions, 'countries' => $regionWiseCountries);
    }
}