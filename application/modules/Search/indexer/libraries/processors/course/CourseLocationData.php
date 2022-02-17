<?php 
require_once dirname(__FILE__).'/../DataAbstract.php';

class CourseLocationData extends DataAbstract {
	

	public function __construct()
	{
		$this->_CI = & get_instance();
		$this->_CI->load->builder('location/LocationBuilder');
		$locationBuilder = new LocationBuilder;
		$this->locationRepository = $locationBuilder->getLocationRepository();

	}

	public function _getDataFromObject($courseObject)
	{	
		$courseLocationData = array();
		$locationsDataFromObject = $courseObject->getLocations();
		if(empty($locationsDataFromObject))
			return null;
		foreach ($locationsDataFromObject as $listing_location_id => $particularLocationData) {
			$courseLocationData[$listing_location_id] = array();
			$courseLocationData[$listing_location_id]['nl_course_location_id'] = $particularLocationData->getLocationId();
			$courseLocationData[$listing_location_id]['nl_course_city_id'] = $particularLocationData->getCityId();
			$courseLocationData[$listing_location_id]['nl_course_state_id'] = $particularLocationData->getStateId();
			$courseLocationData[$listing_location_id]['nl_course_locality_id'] = $particularLocationData->getLocalityId();
			$courseLocationData[$listing_location_id]['city_name'] = $particularLocationData->getCityName();
			$courseLocationData[$listing_location_id]['state_name'] = $particularLocationData->getStateName();
			$courseLocationData[$listing_location_id]['locality_name'] = $particularLocationData->getLocalityName();
		}
		return $courseLocationData;
	}

	public function _processData($courseLocationData){
			if($courseLocationData == null)
				return null;
			$cityIds = array();
			$stateIds = array();
			$categoryListClient = $this->_CI->load->library('categoryList/Category_list_client');

			$cityIds = array();
			foreach ($courseLocationData as $listing_location_id => $data) {
				
				// Fetch State Information(if not Present in Object)
				if(empty($data['nl_course_state_id']) || $data['nl_course_state_id'] == null){
					$tempData = array();
					$tempData = $categoryListClient->getDetailsForCityId(1,$data['nl_course_city_id']);
					$courseLocationData[$listing_location_id]['nl_course_state_id'] = $tempData['state_id'];
					$courseLocationData[$listing_location_id]['state_name'] = $tempData['state_name'];
				}

				// Generate CIty Locality ID MAP
				if(!empty($courseLocationData[$listing_location_id]['nl_course_locality_id'])){
					$courseLocationData[$listing_location_id]['nl_course_city_id_locality_id_map'] = $courseLocationData[$listing_location_id]['nl_course_city_id'].":".$courseLocationData[$listing_location_id]['city_name'].":".$courseLocationData[$listing_location_id]['locality_name'].":".$courseLocationData[$listing_location_id]['nl_course_locality_id'];	
				}
				
				// Add Virtual City in city field
				if(!empty($courseLocationData[$listing_location_id]['nl_course_city_id'])){
					$mainCityObject = $this->locationRepository->findCity($courseLocationData[$listing_location_id]['nl_course_city_id']);
					$virtualCity = $mainCityObject->getVirtualCityId();
					$cityId = $courseLocationData[$listing_location_id]['nl_course_city_id'];
					$courseLocationData[$listing_location_id]['nl_course_city_id'] = array();
					$courseLocationData[$listing_location_id]['nl_course_city_id'][] = $cityId;
					if(!empty($virtualCity)){
						$courseLocationData[$listing_location_id]['nl_course_city_id'][] = $virtualCity;
						$virtualCityObject = $this->locationRepository->findCity($virtualCity);
					}

					// CITY NAME ID MAP
					$courseLocationData[$listing_location_id]['nl_course_city_name_id_map'] = array();
					$courseLocationData[$listing_location_id]['nl_course_city_name_id_map'][] = $mainCityObject->getName().":".$cityId;
					if(!empty($virtualCity) && !empty($virtualCityObject)){
						$courseLocationData[$listing_location_id]['nl_course_city_name_id_map'][] = $virtualCityObject->getName().":".$virtualCity;	
					}
					
				}
				
				// STATE Generate Name And ID map
				$courseLocationData[$listing_location_id]['nl_course_state_name_id_map'] = $courseLocationData[$listing_location_id]['state_name'].":".$courseLocationData[$listing_location_id]['nl_course_state_id'];

				
			}
			return $courseLocationData;
	}


}

?>
