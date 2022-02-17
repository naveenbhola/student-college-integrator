<?php

class CategoryPageSolrMultiLocationLib
{
    private $CI;
    
    function __construct()
    {
        $this->CI = & get_instance();
		$this->CI->config->load('categoryPageConfig');
		$this->CI->load->builder('LocationBuilder','location');
		$this->CI->load->builder('CategoryPageBuilder','categoryList');
        $locationBuilder 					= new LocationBuilder;
		$categoryPageBuilder  				= new CategoryPageBuilder;
        $this->locationRepository 			= $locationBuilder->getLocationRepository();
		$this->categoryPageSponsoredRepo 	= $categoryPageBuilder->getCategoryPageSponsoredRepository();
    }
	
	public function mergeListings($stickyAndMainListingsByLocation, $paidAndFreeListingsByLocation) {
		$mergedData = array();
		$encounteredIds = array();
		foreach($stickyAndMainListingsByLocation as $sponsored) {
			$locationType 	= $sponsored['location_type'];
			$locationId   	= $sponsored['location_id'];
			$csResults    	= $sponsored['cs'];
			$mainResults  	= $sponsored['main'];
			
			$encounteredIds = array_merge($encounteredIds);
			foreach($csResults as $instituteId => $courseIds) {
				if(in_array($instituteId, $encounteredIds)) {
					unset($csResults[$instituteId]);
				} else {
					$encounteredIds[] = $instituteId;
				}
			}
			
			foreach($mainResults as $instituteId => $courseIds) {
				if(in_array($instituteId, $encounteredIds)) {
					unset($mainResults[$instituteId]);
				} else {
					$encounteredIds[] = $instituteId;
				}
			}
			
			if($locationType == 'city') {
				if(array_key_exists($locationId, $paidAndFreeListingsByLocation['cities'])){
					$paidResults = $paidAndFreeListingsByLocation['cities'][$locationId]['paid'];
					$freeResults = $paidAndFreeListingsByLocation['cities'][$locationId]['free'];
				}	
			}
			if($locationType == 'state') {
				if(array_key_exists($locationId, $paidAndFreeListingsByLocation['states'])){
					$paidResults = $paidAndFreeListingsByLocation['states'][$locationId]['paid'];
					$freeResults = $paidAndFreeListingsByLocation['states'][$locationId]['free'];
				}	
			}
			foreach($paidResults as $instituteId => $courseIds) {
				if(in_array($instituteId, $encounteredIds)) {
					unset($paidResults[$instituteId]);
				} else {
					$encounteredIds[] = $instituteId;
				}
			}
			foreach($freeResults as $instituteId => $courseIds) {
				if(in_array($instituteId, $encounteredIds)) {
					unset($freeResults[$instituteId]);
				} else {
					$encounteredIds[] = $instituteId;
				}
			}
			
			$csResultsUpdated = array();
			foreach($csResults as $instituteId => $courses){
				$csResultsUpdated[$instituteId] = array_combine($courses, $courses);
			}
			$mainResultsUpdated = array();
			foreach($mainResults as $instituteId => $courses){
				$mainResultsUpdated[$instituteId] = array_combine($courses, $courses);
			}		
			$freeResultsUpdated = array();
			foreach($freeResults as $instituteId => $courses){
				$freeResultsUpdated[$instituteId] = array_combine($courses, $courses);
			}
			$paidResultsUpdated = array();
			foreach($paidResults as $instituteId => $courses){
				$paidResultsUpdated[$instituteId] = array_combine($courses, $courses);
			}
			$data = array();
			$data['location_type'] 	= $locationType;
			$data['location_id'] 	= $locationId;
			$data['instituteData']  = array(
						    'cs'   => $csResultsUpdated	  ,
						    'main' => $mainResultsUpdated ,
						    'paid' => $paidResultsUpdated ,
						    'free' => $freeResultsUpdated
						    );
			$mergedData[] = $data;
		}
		
		return $mergedData;
	}
	
	public function getRequestObjListForUserLocation(CategoryPageRequest $request, $order = NULL) {
		$RNRSubcategoryIds = array_keys($this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
		$pageSubcategoryId = $request->getSubCategoryId();
		$locationRequestList = array();
		foreach($order as $location) {
			$locationRequest = NULL;
			$locationRequest = clone $request;
			if($location['location_type'] == 'city') {
				$data['cityId'] 	= $location['location_id'];
				$cityObject 		= $this->locationRepository->findCity($data['cityId']);
				$data['stateId'] 	= $cityObject->getStateId();
			}
			if($location['location_type'] == 'state') {
				$data['stateId'] = $location['location_id'];
				$data['cityId']  = 1;
			}
			if(in_array($pageSubcategoryId, $RNRSubcategoryIds)){
				$locationRequest->setNewURLFlag(1);
			}
			$locationRequest->setData($data);
			$location['request'] = $locationRequest;
			$locationRequestList[] = $location;
		}
		return $locationRequestList;
	}
	
	public function getStickyAndMainListingsForRequest($requestListByLocation = array()) {
		$sponsoredResultList = array();
		foreach($requestListByLocation as $requestList){
			$request = NULL;
			$request = $requestList['request'];
			$stickyListings 	= $this->categoryPageSponsoredRepo->getStickyListings($request);
			$mainListings   	= $this->categoryPageSponsoredRepo->getMainListings($request);
			$sponsoredResult 	= array();
			$sponsoredResult['location_type'] = $requestList['location_type'];
			$sponsoredResult['location_id']   = $requestList['location_id'];
			$sponsoredResult['cs']		  	  = $stickyListings;
			$sponsoredResult['main']		  = $mainListings;
			$sponsoredResultList[] 			  = $sponsoredResult;
		}
		return $sponsoredResultList;
	}
	
	public function getFreeAndPaidListingsByLocation($instituteData, $order = NULL) {
		$cities = array();
		$states = array();
		foreach($order as $value) {
			if($value['location_type'] == 'city'){
				$cities[] = $value['location_id'];
			}
			if($value['location_type'] == 'state'){
				$states[] = $value['location_id'];
			}
		}
		
		$virutalCities = $this->getCitiesUnderVirtualCity($cities);
		
		$locationWiseFreePaidInstitutes['cities'] = array();
		$locationWiseFreePaidInstitutes['states'] = array();
		foreach($instituteData as $instituteId => $courses) {
			foreach($courses as $course) {
				$paid = false;
				if($course['course_pack_type'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $course['course_pack_type'] == SILVER_LISTINGS_BASE_PRODUCT_ID || $course['course_pack_type'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID){
					$paid = true;
				}
				
				$course['course_city_id'] = $this->searchForValInArray($course['course_city_id'], $virutalCities);
				
				if($course['course_city_id'] != -1) {
					if(!array_key_exists($course['course_city_id'], $locationWiseFreePaidInstitutes['cities'])){
						$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']] = array();
						$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['location_type'] 	= 'city';
						$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['location_id'] 	= $course['course_city_id'];
						$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['paid'] 			= array();
						$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['free'] 			= array();
					}
					if($paid){
						if(!array_key_exists($course['institute_id'], $locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['paid'])){
							$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['paid'][$course['institute_id']] = array();
						}
						$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['paid'][$course['institute_id']][] = $course['course_id'];
						
					} else {
						if(!array_key_exists($course['institute_id'], $locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['free'])){
							$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['free'][$course['institute_id']] = array();
						}
						$locationWiseFreePaidInstitutes['cities'][$course['course_city_id']]['free'][$course['institute_id']][] = $course['course_id'];
					}
				}
				
				if(in_array($course['course_state_id'], $states)) {
					if(!array_key_exists($course['course_state_id'], $locationWiseFreePaidInstitutes['states'])){
						$locationWiseFreePaidInstitutes['states'][$course['course_state_id']] = array();
						$locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['location_type'] 	= 'state';
						$locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['location_id'] 	= $course['course_state_id'];
						$locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['paid'] 			= array();
						$locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['free'] 			= array();
					}
					if($paid){
						if(!array_key_exists($course['institute_id'], $locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['paid'])){
							$locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['paid'][$course['institute_id']] = array();
						}
						$locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['paid'][$course['institute_id']][] = $course['course_id'];
						
					} else {
						if(!array_key_exists($course['institute_id'], $locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['free'])){
							$locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['free'][$course['institute_id']] = array();
						}
						$locationWiseFreePaidInstitutes['states'][$course['course_state_id']]['free'][$course['institute_id']][] = $course['course_id'];
					}
				}
			}
		}
		
		return $locationWiseFreePaidInstitutes;
	}
	
	public function getCitiesUnderVirtualCity($cityIdArray) {
		$virutalCityIdMap = array();
		foreach($cityIdArray as $cityId) {
			$cityObjects = $this->locationRepository->getCitiesByVirtualCity($cityId);
			if(!empty($cityObjects)) {
				foreach($cityObjects as $cityObj) {
					$virutalCityIdMap[$cityId][] = $cityObj->getId();
				}
			}
		}
		return $virutalCityIdMap;
	}
	
	function searchForValInArray($val, $array) {
		foreach ($array as $key => $valueArr) {
			if (in_array($val, $valueArr)) {
				return $key;
			}
		}
		return -1;
	}
}