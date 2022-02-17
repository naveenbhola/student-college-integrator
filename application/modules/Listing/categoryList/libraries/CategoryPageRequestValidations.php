<?php
class CategoryPageRequestValidations
{    
    private $availableSortOrders = array('none',
					 'lowfees',
					 'highfees',
					 'dateOfCommencement',
					 'reversedateOfCommencement',
					 'viewCount',
					 'longduration',
					 'shortduration',
					 'topInstitutes',
                                         'highformSubmissionDate',
                                         'lowformSubmissionDate');
    private $catPageUrl;
    
    
    /* 	This function checks for Category Page Request parameters and does the following:
     * 	
     * 	i). Return flase if any of the parameter is not with in the valid range and can't be corrected (i.e Not Numeric / Is not Set / Is Zero etc.) so
     * 		that a 404 error page can be thrown to the user.
     * 		
     * 	ii). Check if we have to redirect the Category Page and redirects it using 301 redirect if any wrong param found from any of the follwing:
     * 		a. Going through the Category hierarchic depth (from LDB course - Subcategory - Category) and see if
     * 			wrong param can be corected based on other entities in this depth.
     * 		b. Going through the  Location hierarchic depth (from Locality - Zone - City - State - Country - Region) and
     * 			see if wrong param can be corected based on other entities in this depth.
     *		c. Category and Location mismatch validation: We will redirect to National category page always if we find:
     *			. If request is for National Category page and location is of abroad.
     *			. If request is for Abroad Category page and location is of India.
     *
     * 	iii). Return true if all is fine so that the valid Category Page can be served to the end user.
     */

    public function redirectIfInvalidRequestParamsExist($request, $categoryBuilder, $LDBCourseBuilder, $locationBuilder) {
	
	$this->catPageUrl = $_SERVER["REQUEST_URI"];
	
	/*
	 *	If Sort Order value is not from the available values we support, reset it to the default value 'none'..
	 */
	if( !(in_array($request->getSortOrder(), $this->availableSortOrders)) ) {
	    $request->setData(array('sortOrder'=>'none'));
	}
	
	/*
	 * 	Firstly check if all params are set and are numeric..
	 */ 
	if(!$this->_areParamsSetAndNumeric($request)) {
	    error_log($this->catPageUrl.", :REQUEST_VALIDATION: 1. _areParamsSetAndNumeric FAILS");		
	    $this->_redirectCategoryPage();
	}
	
	$requestData = $this->getDefaultRequestData($request);
	
	/*
	 * 	Lets check the Category hierarchic depth..
	 */ 	
	$categoryResponse = $this->_validateCategoryHierarchicDepth($request, $categoryBuilder, $LDBCourseBuilder, $locationBuilder);
	if(!$categoryResponse['status']) {	    
	    $this->_redirectCategoryPage();
	}
			
	// echo "<br> categoryResponse redirectionFlag = ".$categoryResponse['redirectionFlag'];	    
    
	if($categoryResponse['redirectionFlag'] == 1) {
	    $requestData = $categoryResponse['requestData'];
	}
	
	/*
	 * 	Lets check the Location hierarchic depth..
	 */ 		
	$locationResponse = $this->_validateLocationHierarchicDepth($request, $locationBuilder, $requestData, $categoryBuilder);	
	if(!$locationResponse['status']) {	    
	    $this->_redirectCategoryPage();
	}

	// echo "<br> locationResponse redirectionFlag = ".$locationResponse['redirectionFlag']; // echo "<br>URL: ".$request->getURL();

	if($categoryResponse['redirectionFlag'] == 1 || $locationResponse['redirectionFlag'] == 1) {
	    $request->setData($locationResponse['requestData']);	    
	    $this->_redirectCategoryPage($request->getURL());	    
	}
	
	// echo "<br><b>ALL well till now</b>"; die;
	return true;
    }
    
    
    private function _validateCategoryHierarchicDepth($request, $categoryBuilder, $LDBCourseBuilder, $locationBuilder) {
	
	$requestData = $this->getDefaultRequestData();
	$response['requestData'] = array();	
	
	$categoryDepthParamsArray = array("ldbCourse", "subCategory", "category");
	
	foreach($categoryDepthParamsArray as $key => $entity) {
	    
	    /*
	     * 	LDB Course validation..
	     */
	    if($entity == "ldbCourse") {
		if($request->getLDBCourseId() == 0) {		    
		    error_log($this->catPageUrl.", :REQUEST_VALIDATION: 4. _isValidLDBCourseId FAILS");
		    $response['status'] = false;
		    return $response;
		} else if($request->getLDBCourseId() != 1) {
		    
		    $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		    $LDBCourse = $LDBCourseRepository->find($request->getLDBCourseId());		    
		    if($LDBCourse->getId() != "") {
			$requestData['LDBCourseId'] = $request->getLDBCourseId();
			if($LDBCourse->getSubCategoryId() != $request->getSubCategoryId()) {			    
			    error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of LDB COURSE ID");
			    $requestData['categoryId'] = $LDBCourse->getCategoryId();
			    $requestData['subCategoryId'] = $LDBCourse->getSubCategoryId();			    
			    $response['status'] = true;			    
			    $response['requestData'] = $requestData;
			    $response['redirectionFlag'] = 1;
			    return $response;
			}
		    } else {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of LDB COURSE ID, No such LDB COURSE exists.");
			$requestData['LDBCourseId'] = 1;			
			$redirectionFlag = 1;
			continue;
		    }
		} else {
		    $requestData['LDBCourseId'] = $request->getLDBCourseId();		    
		}
	    }
	    
	    /*
	     * 	Subcategory validation..
	     */
	    if($entity == "subCategory") {		 
		if($request->getSubCategoryId() != 1) {
		    
		    if($request->getSubCategoryId() == 0) {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: 4. _isValidSubCategoryId FAILS");
			$response['status'] = false;
			return $response;
		    }
		    
		    $requestData['subCategoryId'] = $request->getSubCategoryId();
		    
		    $categoryRepository = $categoryBuilder->getCategoryRepository();
		    $category = $categoryRepository->find($request->getSubCategoryId());
		    
		    if($category->getId() != "") {
			    $crossPromotedSubcategoryId = 0;		    
			    if($category->getFlag() == 'studyabroad' && $request->getCountryId() == 2) {
				$crossPromotedSubcategoryId = $categoryRepository->getCrossPromotionMappedCategory($request->getSubCategoryId());			
			    }
			    
			    if($crossPromotedSubcategoryId != 0) {
				$requestData['categoryId'] = $crossPromotedSubcategoryId->getParentId();
				$requestData['subCategoryId'] = $crossPromotedSubcategoryId->getId();
				$requestData['LDBCourseId'] = 1;				
				$requestData['localityId'] = $request->getLocalityId();
				$requestData['zoneId'] = $request->getZoneId();
				$requestData['cityId'] = $request->getCityId();
				$requestData['stateId'] = $request->getStateId();
				$requestData['countryId'] = $request->getCountryId();
				$requestData['regionId'] = $request->getRegionId();
				error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of crossPromotedSubcategoryId.");
				$response['status'] = true;
				$response['requestData'] = $requestData;
				$response['redirectionFlag'] = 1;
				return $response;
			    }
			    
			    if($category->getParentId() != $request->getCategoryId()) {
				// Redirecting now.			
				$requestData['categoryId'] = $category->getParentId();
				$requestData['subCategoryId'] = $request->getSubCategoryId();
				error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of SUB category ID.");
				$response['status'] = true;
				$response['requestData'] = $requestData;
				$response['redirectionFlag'] = 1;
				return $response;
			    }
		    } else {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of SUB category ID, No such SUB category exists.");
			$requestData['LDBCourseId'] = 1;
			$requestData['subCategoryId'] = 1;
			$redirectionFlag = 1;
			continue;
		    }
		} else {
		    $requestData['subCategoryId'] = $request->getSubCategoryId();
		}
	    }
	    
	    
	    /*
	     * 	Main Category validation..
	     */
	    if($entity == "category") {	    
		if($request->getCategoryId() == 0 || ($request->getCategoryId() == 1 && $request->getCountryId() == 2)) {
		    error_log($this->catPageUrl.", :REQUEST_VALIDATION: 4. _isValidCategoryId FAILS");
		    $response['status'] = false;
		    return $response;
		}
		
		if(!$this->_isValidCategoryId($request, $categoryBuilder)) {
		    error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of Main category ID.");
		    $response['status'] = true;
		    $response['requestData'] = $requestData;
		    $response['redirectionFlag'] = 1;
		    return $response;
		} else {
		    $requestData['categoryId'] = $request->getCategoryId();
		}
	    }
	    
	}	// End of foreach($categoryDepthParamsArray as $key => $entity).
	
	$response['status'] = true;
	$response['requestData'] = $requestData;	
	if($redirectionFlag == 1) {
	    $response['redirectionFlag'] = 1;
	} else {
	    $response['redirectionFlag'] = 0;
	}
	
	return $response;
		
    }
        
    private function _validateLocationHierarchicDepth($request, $locationBuilder, $requestData, $categoryBuilder) {
	
	$locationDepthParamsArray = array("locality", "zone", "city", "state", "country", "region");
	$locationRepository = $locationBuilder->getLocationRepository();
	$categoryRepository = $categoryBuilder->getCategoryRepository();
	$redirectionFlag = 0;
	
	foreach($locationDepthParamsArray as $key => $entity) {
	    /*
	     * 	Locality validation..
	     */
	    if($entity == "locality" && $request->getLocalityId() != 0) {		
		    $locality = $locationRepository->findLocality($request->getLocalityId());		    
		    if($locality->getId() != "" && $locality->getCityId() != "") {			
			$requestData['localityId'] = $request->getLocalityId();			
			if(!($locality->getZoneId() == $request->getZoneId() &&
			    $locality->getCityId() == $request->getCityId() &&
				$locality->getStateId() == $request->getStateId() &&
				    $locality->getCountryId() == $request->getCountryId())) {
			    
				if(!$this->_checkCategoryLocationMatch($categoryRepository, $request, $locality->getCountryId())) {
				    error_log($this->catPageUrl.", :REQUEST_VALIDATION: _checkCategoryLocationMatch FAILS in locality entity.");
				    $response['status'] = false;
				    return $response;				    
				}
				
				error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of Locality ID");
				$response['status'] = true;
				$response['requestData'] = $requestData;
				$response['redirectionFlag'] = 1;
				return $response;
			}
		    } else {
				error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of Locality ID, No such locality exists.");
				$requestData['localityId'] = 0;
				$redirectionFlag = 1;
				continue;
		    }
	    }

	    /*
	     * 	Zone validation..
	     */
	    if($entity == "zone" && $request->getZoneId() != 0) {
		$zone = $locationRepository->findZone($request->getZoneId());		
		if($zone->getId() != "" && $zone->getCityId() != "") {
		    $requestData['zoneId'] = $request->getZoneId();		    
		    if(!($zone->getCityId() == $request->getCityId() &&
			$zone->getStateId() == $request->getStateId() &&
			    $zone->getCountryId() == $request->getCountryId())) {			
			if(!$this->_checkCategoryLocationMatch($categoryRepository, $request, $zone->getCountryId())) {
			    error_log($this->catPageUrl.", :REQUEST_VALIDATION: _checkCategoryLocationMatch FAILS in zone entity.");
			    $response['status'] = false;
			    return $response;				    
			}
			
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of ZONE ID");
			$response['status'] = true;
			$response['requestData'] = $requestData;
			$response['redirectionFlag'] = 1;
			return $response;
		    }	
		} else {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of ZONE ID, No such ZONE exists.");
			$requestData['localityId'] = 0;
			$requestData['zoneId'] = 0;
			$redirectionFlag = 1;
			continue;
		}
	    }	    

	    /*
	     * 	City validation..
	     */
	    if($entity == "city" && $request->getCityId() != 1) {	
		    if($request->getCityId() == 0) {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: 4. _isValidCityId FAILS");
			$response['status'] = false;
			return $response;
		    }		    		
		    		    
		    $city = $locationRepository->findCity($request->getCityId());		    
		    
		    if($city->getId() != "" && $city->getCountryId() != "") {
			$requestData['cityId'] = $request->getCityId();
			if($request->getSubCategoryId() != 0 || $request->getSubCategoryId() != 1){
			    $countryOfCity = $city->getCountryId();			    
			    $category = $categoryRepository->find($request->getSubCategoryId());
			    
			    if($category->getFlag() == 'national' && ($request->getCountryId() != 2 || $countryOfCity != 2) ) {
				$crossPromotedSubcategoryId = $categoryRepository->getCrossPromotionMappedCategory($request->getSubCategoryId());
			    }
			    
			    if( !($city->getStateId() == $request->getStateId() && $city->getCountryId() == $request->getCountryId())) {
				
				if(!$this->_checkCategoryLocationMatch($categoryRepository, $request, $city->getCountryId())) {
				    error_log($this->catPageUrl.", :REQUEST_VALIDATION: _checkCategoryLocationMatch FAILS in city entity.");
				    $response['status'] = false;
				    return $response;
				}
				
				error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of CITY ID");
				$requestData['stateId'] = ($city->getStateId() == 0 ? 1 : $city->getStateId());
				$requestData['countryId'] = $city->getCountryId();
				if($city->getCountryId() != 2) {
				    $country = $locationRepository->findCountry($request->getCountryId());	    
				    if($country->getId() != "") {
					$requestData['regionId'] = $country->getRegionId();
				    }	
				} else {
				    $requestData['regionId'] = 0;
				}
				
				$response['status'] = true;
				$response['requestData'] = $requestData;
				$response['redirectionFlag'] = 1;
				return $response;
			    }			    
			}		    
		    } else {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of CITY ID, No such CITY exists.");
			$requestData['localityId'] = 0;
			$requestData['zoneId'] = 0;
			$requestData['cityId'] = 1;
			$redirectionFlag = 1;
			continue;
		    }
	    }

	    /*
	     * 	State validation..
	     */
	    if($entity == "state" && $request->getStateId() != 1) {
		if($request->getStateId() == 0) {
		    error_log($this->catPageUrl.", :REQUEST_VALIDATION: 4. _isValidStateId FAILS");
		    $response['status'] = false;
		    return $response;
		}
			    
		$state = $locationRepository->findState($request->getStateId());
		if($state->getId() != "") {
		    $requestData['stateId'] = $request->getStateId();
		    
		    if($state->getCountryId() != $request->getCountryId()) {			
			if(!$this->_checkCategoryLocationMatch($categoryRepository, $request, $state->getCountryId())) {
			    error_log($this->catPageUrl.", :REQUEST_VALIDATION: _checkCategoryLocationMatch FAILS in state entity.");
			    $response['status'] = false;
			    return $response;
			}
			
			if($state->getCountryId() != 2) {
			    $requestData['stateId'] = 1;			    
			}
			
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of STATE ID");
			$requestData['countryId'] = $state->getCountryId();
			$response['status'] = true;
			$response['requestData'] = $requestData;
			$response['redirectionFlag'] = 1;
			return $response;			
		    }
		} else {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of STATE ID, No such STATE exists.");
			$requestData['localityId'] = 0;
			$requestData['zoneId'] = 0;
			$requestData['cityId'] = 1;
			$requestData['stateId'] = 1;
			$redirectionFlag = 1;
			continue;
		}
		    
	    }

	    /*
	     * 	Country validation..
	     */
	    if($entity == "country") {
		if($request->getCountryId() == 0) {
		    error_log($this->catPageUrl.", :REQUEST_VALIDATION: 4. _isValidCountryId FAILS");
		    $response['status'] = false;
		    return $response;
		}
		
		$country = $locationRepository->findCountry($request->getCountryId());		

		if($country->getId() != "") {
		    $requestData['countryId'] = $request->getCountryId();
		    $flag = 0;
		    		    
		    // $categoryRepository = $categoryBuilder->getCategoryRepository();
		    $category = $categoryRepository->find($request->getSubCategoryId());
		    if($category->getFlag() == 'national' && ($request->getCountryId() != 2) ) {
			// $crossPromotedSubcategoryId = $categoryRepository->getCrossPromotionMappedCategory($request->getSubCategoryId());
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of COUNTRY ID and crossPromotedSubcategoryId mismatch.");
			$requestData['countryId'] = 2;
			$requestData['regionId'] = 0;
			$response['status'] = true;
			$response['requestData'] = $requestData;
			$response['redirectionFlag'] = 1;
			return $response;			
		    }
		    
		    if($country->getId() == 1 && $request->getRegionId() == 0) {			
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of COUNTRY ID");
			$requestData['countryId'] = 2;
			$requestData['regionId'] = 0;
			$response['status'] = true;
			$response['requestData'] = $requestData;
			$response['redirectionFlag'] = 1;
			return $response;
		    }
		    
		    if($country->getId() > 2 && $country->getRegionId() != $request->getRegionId()) {
			// $flag = 1;
			/** AMITk **/
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of COUNTRY ID");
			$requestData['countryId'] = $country->getId();
			$requestData['regionId'] = $country->getRegionId();
			$response['status'] = true;
			$response['requestData'] = $requestData;
			$response['redirectionFlag'] = 1;
			return $response;
		    }
		   
		    if($country->getId() == 2 && $request->getRegionId() != 0) {
			$flag = 1;			
		    }
		    
		    if($flag == 1) {			
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of COUNTRY ID");			
			$requestData['regionId'] = $country->getRegionId();
			$response['status'] = true;
			$response['requestData'] = $requestData;
			$response['redirectionFlag'] = 1;
			return $response;			
		    }		
		} else {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of COUNTRY ID, No such COUNTRY exists.");
			$requestData['localityId'] = 0;
			$requestData['zoneId'] = 0;
			$requestData['cityId'] = 1;
			$requestData['stateId'] = 1;
			$requestData['countryId'] = 2;
			$requestData['regionId'] = 0;			
			$response['status'] = true;
			$response['requestData'] = $requestData;
			$response['redirectionFlag'] = 1;
			return $response;
		}		
	    }

	    /*
	     * 	Region validation..
	     */
	    if($entity == "region" && $request->getRegionId() != 0) {
		    $region = $locationRepository->findRegion($request->getRegionId());	
		    if($region->getId() == "") {			
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: Redirecting on the basis of REGION ID, No such REGION exists.");
			$requestData['localityId'] = 0;
			$requestData['zoneId'] = 0;
			$requestData['cityId'] = 1;
			$requestData['stateId'] = 1;
			$requestData['countryId'] = 2;
			$requestData['regionId'] = 0;
			$response['status'] = true;
			$response['requestData'] = $requestData;
			$response['redirectionFlag'] = 1;
			return $response;
		    }
	    }
	    
	}	// End of foreach($locationDepthParamsArray as $key => $entity).
		
	$response['status'] = true;
	$response['requestData'] = $requestData;
	if($redirectionFlag == 1) {
	    $response['redirectionFlag'] = 1;
	} else {
	    $response['redirectionFlag'] = 0;
	}
	return $response;	
    }
    
    private function _checkCategoryLocationMatch($categoryRepository, $request, $countryId) {
	
	$category = $categoryRepository->find($request->getCategoryId());
	$subCategory = $categoryRepository->find($request->getSubCategoryId());
	
	if($category->getId() == 1 && in_array($subCategory->getId(), array(1,2)) && $countryId == 2) {	    
	    return false;
	}	
	return true;
    }
    
    
    public function areValidRequestParameters($request, $categoryBuilder, $LDBCourseBuilder, $locationBuilder) {

	    $this->catPageUrl = $_SERVER["REQUEST_URI"];
	    
	    // Firstly check if all params are set and are numeric..
	    if(!$this->_areParamsSetAndNumeric($request)) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 1. _areParamsSetAndNumeric FAILS");		
		return false;
	    }
	    
	    
	    if(!$this->_isValidCategoryId($request, $categoryBuilder)) {		
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 2. _isValidCategoryId FAILS");
		return false;
	    }
	    
	    if(!$this->_isValidSubCategoryId($request, $categoryBuilder)) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 3. _isValidSubCategoryId FAILS");
		return false;	    
	    }

	    if(!$this->_isValidLDBCourseId($request, $LDBCourseBuilder)) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 4. _isValidLDBCourseId FAILS");
		return false;
	    }
	    
	    if(!$this->_isValidLocalityId($request, $locationBuilder)) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 5. _isValidLocalityId FAILS");
		return false;		
	    }

	    if(!$this->_isValidZoneId($request, $locationBuilder)) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 6. _isValidZoneId FAILS");
		return false;		
	    }

	    if(!$this->_isValidCityId($request, $locationBuilder)) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 7. _isValidCityId FAILS");
		return false;		
	    }
	    
	    if(!$this->_isValidStateId($request, $locationBuilder)) {	
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 8. _isValidStateId FAILS");
		return false;		
	    }

	    if(!$this->_isValidCountryId($request, $locationBuilder)) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 9. _isValidCountryId FAILS");
		return false;
	    }
	    	    
	    if($request->getRegionId() != 0 && !$this->_isValidRegionId($request, $locationBuilder)) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 10. _isValidRegionId FAILS");
		return false;
	    }	    		    
				
	    $categoryPageParameters = explode("-categorypage-", $this->catPageUrl);
	    if(!$this->_isValidSortOrder($request->getSortOrder(), $categoryPageParameters[1])) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 11. _isValidSortOrder FAILS");
		return false;
	    }			    	
	    // error_log($this->catPageUrl.", :REQUEST_VALIDATION: 12. _isValidNaukriLearningParameter YES");	    
	    if(!$this->_isValidNaukriLearningParameter($request->isNaukrilearningPage(), $categoryPageParameters[1])) {
		error_log($this->catPageUrl.", :REQUEST_VALIDATION: 12. _isValidNaukriLearningParameter FAILS");
		return false;
	    }			    	
	    	    
	    return true;
    }
        
    private function _isValidCategoryId($request, $categoryBuilder) {
	if($request->getCategoryId() == 1) {
	    return true;
	}
	
        if($request->getCategoryId() == 0) {
            return false;
        }	
	
	$categoryRepository = $categoryBuilder->getCategoryRepository();
	$category = $categoryRepository->find($request->getCategoryId());
	if($category->getParentId() == 1) {
	    return true;
	} else {
	    return false;
	}
    }
    
    private function _isValidSubCategoryId($request, $categoryBuilder) {
	if($request->getSubCategoryId() == 1) {
	    return true;
	}
	
        if($request->getSubCategoryId() == 0) {
            return false;
        }	
	
	$categoryRepository = $categoryBuilder->getCategoryRepository();
	$category = $categoryRepository->find($request->getSubCategoryId());    
	if($category->getParentId() != $request->getCategoryId()) {
	    return false;
	}
	
	if($category->getFlag() == 'national' && $request->getCountryId() != 2) {
	    return false;
	}
	
	if($category->getFlag() == 'studyabroad' && $request->getCountryId() == 2) {
	    return false;
	}

	return true;	
    }    
        
    private function _isValidLDBCourseId($request, $LDBCourseBuilder) {
	if($request->getLDBCourseId() == 1) {
	    return true;
	}
	
        if($request->getLDBCourseId() == 0) {
            return false;
        }
	
	$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
	$LDBCourse = $LDBCourseRepository->find($request->getLDBCourseId());	
	if($LDBCourse->getSubCategoryId() == $request->getSubCategoryId()) {
	    return true;
	} else {
	    return false;
	}
    }    
    
    private function _isValidLocalityId($request, $locationBuilder) {
	if($request->getLocalityId() == 0) {
	    return true;
	}
	
	$locationRepository = $locationBuilder->getLocationRepository();
	$locality = $locationRepository->findLocality($request->getLocalityId());	
	
	if($locality->getZoneId() == $request->getZoneId() &&
	    $locality->getCityId() == $request->getCityId() &&
		$locality->getStateId() == $request->getStateId() &&
		    $locality->getCountryId() == $request->getCountryId()) {
	    return true;
	} else {
	    return false;
	}	
    }  
    
    private function _isValidZoneId($request, $locationBuilder) {
	if($request->getZoneId() == 0) {
	    return true;
	}
	
	$locationRepository = $locationBuilder->getLocationRepository();
	$zone = $locationRepository->findZone($request->getZoneId());		
	if($zone->getCityId() == $request->getCityId() &&
	    $zone->getStateId() == $request->getStateId() &&
		$zone->getCountryId() == $request->getCountryId()) {
	    return true;
	} else {
	    return false;
	}	
    }    
    

    private function _isValidCityId($request, $locationBuilder) {
	if($request->getCityId() == 1) {
	    return true;
	}
	
        if($request->getCityId() == 0) {
            return false;
        }	
	
	$locationRepository = $locationBuilder->getLocationRepository();
	$city = $locationRepository->findCity($request->getCityId());	
	
	if($city->getStateId() == $request->getStateId() &&
		$city->getCountryId() == $request->getCountryId()) {
	    return true;
	} else {
	    return false;
	}	
    }
    
    private function _isValidStateId($request, $locationBuilder) {
	if($request->getStateId() == 1) {
	    return true;
	}

        if($request->getStateId() == 0) {
            return false;
        }
	
	
	$locationRepository = $locationBuilder->getLocationRepository();
	$state = $locationRepository->findState($request->getStateId());
	
	if($state->getCountryId() == $request->getCountryId()) {
	    return true;
	} else {
	    return false;
	}	
    }
    
    private function _isValidCountryId($request, $locationBuilder) {
	if($request->getCountryId() == 1 && $request->getRegionId() > 0) {
	    return true;
	}
	
        if($request->getCountryId() == 0) {
            return false;
        }
	
	if($request->getCountryId() == 1 && $request->getRegionId() == 0) {
            return false;
        }
			
	$locationRepository = $locationBuilder->getLocationRepository();
	$country = $locationRepository->findCountry($request->getCountryId());
		
	if($country->getId() != "") {
	    if($country->getId() > 2 && $country->getRegionId() != $request->getRegionId()) {
		return false;
	    }
	   
	    if($country->getId() == 2 && $request->getRegionId() != 0) {
		return false;
	    }
	    
	    return true;
	
	} else {
	    return false;
	}	
    }

    private function _isValidRegionId($request, $locationBuilder) {
		
	$locationRepository = $locationBuilder->getLocationRepository();
	$region = $locationRepository->findRegion($request->getRegionId());	
	
	if($region->getId() != "") {
	    return true;
	} else {
	    return false;
	}	
    }    

    private function _isValidSortOrder($sortOrder, $categoryPageParameters) {		
	$categoryPageParameters = explode("-",$categoryPageParameters);	
	$urlSortOrder = $categoryPageParameters['9'];  // Reading Sort Order from the URL..
	if(isset($urlSortOrder) && $urlSortOrder != "") {
	    if(in_array($sortOrder, $this->availableSortOrders) && in_array($urlSortOrder, $this->availableSortOrders)) {
		return true;
	    } else {
		return false;
	    }
	}
	return true;
    }
    
    private function _isValidNaukriLearningParameter($naukriLearningParam, $categoryPageParameters) {		
	$categoryPageParameters = explode("-",$categoryPageParameters);	
	$urlNaukriLearningParam = $categoryPageParameters['11'];  // Reading Naukri Learning Param from the URL..
	if(isset($urlNaukriLearningParam) && $urlNaukriLearningParam != "") {
	    if(!is_numeric($urlNaukriLearningParam)) {
		return false;
	    }
	    
	    $naukriLearningValidValues = array(0,1);	    
	    if(in_array($naukriLearningParam, $naukriLearningValidValues) && in_array($urlNaukriLearningParam, $naukriLearningValidValues)) {		
		return true;
	    } else {		
		return false;
	    }
	}
	return true;
    }    
    
    private function _areParamsSetAndNumeric($request) {
	
	if($this->invalidDataCheck($request->getCategoryId()) || $request->getCategoryId() == 0 ||
		$this->invalidDataCheck($request->getSubCategoryId())  ||  $request->getSubCategoryId() == 0 ||
		    $this->invalidDataCheck($request->getLDBCourseId()) ||  $request->getLDBCourseId() == 0 ||
			$this->invalidDataCheck($request->getLocalityId()) ||
			    $this->invalidDataCheck($request->getZoneId()) ||
				$this->invalidDataCheck($request->getCityId()) ||  $request->getCityId() == 0 ||
				    $this->invalidDataCheck($request->getStateId()) ||  $request->getStateId() == 0 ||
					$this->invalidDataCheck($request->getCountryId()) ||  $request->getCountryId() == 0 ||
					    $this->invalidDataCheck($request->getRegionId()) ||
						 $this->invalidDataCheck($request->isNaukrilearningPage()) ||
						    $this->invalidDataCheck($request->getPageNumberForPagination()))
	    {
		return false;
	    }
	    
	    return true;
    }
    
    public function invalidDataCheck($dataVar) {
	    if( !isset($dataVar) OR $dataVar === "" OR !is_numeric($dataVar))
		return true;
	    else
		return false;
    }

    function getDefaultRequestData($request = ""){
	// Setting the default values..	
       $requestData = array();
       if(is_object($request)) {
	$requestData['categoryId'] = $request->getCategoryId();
	$requestData['subCategoryId'] = $request->getSubCategoryId();
	$requestData['LDBCourseId'] = $request->getLDBCourseId();
	$requestData['localityId'] = $request->getLocalityId();
	$requestData['zoneId'] = $request->getZoneId();
	$requestData['cityId'] = $request->getCityId();
	$requestData['stateId'] = $request->getStateId();
	$requestData['countryId'] = $request->getCountryId();
	$requestData['regionId'] = $request->getRegionId();
       } else {
	$requestData['categoryId'] = 3;
	$requestData['subCategoryId'] = 1;
	$requestData['LDBCourseId'] = 1;
	$requestData['localityId'] = 0;
	$requestData['zoneId'] = 0;
	$requestData['cityId'] = 1;
	$requestData['stateId'] = 1;
	$requestData['countryId'] = 2;
	$requestData['regionId'] = 0;	
       }

       return ($requestData);
    }
    
    private function _redirectCategoryPage($url = "") {
	// echo "<br><b>Need to redirect it on :</b> $url"; die;
	// error_log("REQUEST_VALIDATION: REDIRECTING ON: ".$url);
	if($url === "") { // Show 404 error page..
	    show_404();    
	} else {	// Redirected to the requested URL using 301 redirect..
	    redirect($url, 'location', 301);
	}
	exit();
    }
}