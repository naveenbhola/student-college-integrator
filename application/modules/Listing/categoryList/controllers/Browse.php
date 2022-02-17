<?php

class Browse extends MX_Controller
{
	private $categoryRepository;
	private $LDBCourseRepository;
	private $locationRepository;
	private $categoryPageRequest;
	private $categoryPageZeroResultHandler;

	private function _init()
	{
		$this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$this->categoryRepository = $categoryBuilder->getCategoryRepository();

		$this->load->builder('LDBCourseBuilder','LDB');
		$LDBCourseBuilder = new LDBCourseBuilder;
		$this->LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();

		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$this->locationRepository = $locationBuilder->getLocationRepository();
		$this->config->load('categoryPageConfig');
		$this->load->library('CategoryPageZeroResultHandler');
		$this->load->library('CategoryPageRequest');
		$this->load->helper('browse_page');
		$this->load->library('categoryList/clients/CategoryPageClient');
		$this->load->library('listing_client');
		$ListingClientObj = new Listing_client();
		$countryList = $ListingClientObj->getCountriesRegionsForProduct(1,'studyabroad');
		global $countriesForStudyAbroad ;
		$countriesForStudyAbroad = json_decode($countryList,true);
	}

	function page1()
	{
		$redirectUrl = base_url()."sitemap";
		redirect($redirectUrl, 'location', 301);
		
		$this->_init();
		$categories = $this->categoryRepository->getSubCategories(1);
		$data = array(
            'categories' => $categories,
                'loadJQUERY'=>'YES'
		);
		$data['validateuser'] = $this->checkUserValidation();
		$data['canonicalurl'] = SHIKSHA_HOME.'/categoryList/Browse/page1/';
		//Tracking Code
		$data['beaconTrackData'] = array(
		    'pageIdentifier' => 'browsePage',
		    'pageEntityId' => 'page1',
		    'extraData' => array('url'=>get_full_url())
		);

		$this->load->view('footerBrowsePageNew1',$data);
	}

	function page2($categoryId,$categoryName, $scope,$alphabet1='')
	{
		$redirectUrl = base_url()."sitemap";
		redirect($redirectUrl, 'location', 301);
		
		$this->_init();
		$checkAlphabetEmpty = $alphabet1;
		if(empty($alphabet1)) $alphabet1 ='A';
		$request_object = new CategoryPageRequest();
		$CategoryPageClient = new CategoryPageClient();
		$datanew = array();
		if($scope == 'India') {
			$request_object->setData(array('categoryId'=>$categoryId,'countryId'=>2));
			$datanew = $CategoryPageClient->getDynamicLocationListForBrowseInstitute($request_object);
			$states_having_data = array_unique($datanew['states']);
			$cities_having_data = array_unique($datanew['cities']);
			$ldb_course_having_data = array_unique($datanew['ldb_course_id']);
			$states = $this->locationRepository->getStatesByCountry(2);
			$citiesByTiers = $this->locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
			$cities = array();
			foreach($citiesByTiers as $tier => $citiesInTier) {
				foreach($citiesInTier as $city) {
					if(in_array($city->getId(), $cities_having_data)) {
						$cities_name[] = strtoupper(substr($city->getName(),0,1));
						$cities_objects[] = $city;
					}
				}
			}
			foreach($states as $state) {
				if(in_array($state->getId(), $states_having_data)) {
					$states_name[] = strtoupper(substr($state->getName(),0,1));
					$states_objects[] = $state;
				}
			}
			$cities_name = array_unique($cities_name);
			$states_name = array_unique($states_name);
			$location_array = array_merge($cities_name,$states_name);
			$location_array = array_unique($location_array);
			sort($location_array);
			$subCategories = $this->categoryRepository->getSubCategories($categoryId,'national');
			$LDBCourses = array();
			foreach($subCategories as $subCategory) {
				$LDBCoursesForSubCategory = (array) $this->LDBCourseRepository->getLDBCoursesForSubCategory($subCategory->getId());
				$LDBCourses = array_merge($LDBCourses,$LDBCoursesForSubCategory);
			}
			foreach($LDBCourses as $ldb) {
				if(in_array($ldb->getId(),$ldb_course_having_data)) {
					$LDBCourses_new[] = $ldb;
				}
			}
			$LDBCourses = $LDBCourses_new;
		}
		else {
			$request_object = new CategoryPageRequest();
			$request_object->setData(array('categoryId'=>$categoryId,'countryId'=>99)); // Country Id set to 99 to make request for Study Abroad
			$datanew = $CategoryPageClient->getDynamicLocationListForBrowseInstitute($request_object);
			$abroad_country_having_data = $datanew['countries'];
			$abroad_region_having_data = $datanew['regionid'];
			$regions = $this->locationRepository->getRegions();
			foreach($regions as $region) {
				if(in_array($region->getId(), $abroad_region_having_data)) {
					$regions_name[] = strtoupper(substr($region->getName(),0,1));
					$cities_objects[] = $region;
				}
			}
			$countries = $this->locationRepository->getCountries();
			foreach($countries as $country) {
				if(in_array($country->getId(), $abroad_country_having_data)) {
					$countries_name[] = strtoupper(substr($country->getName(),0,1));
					$states_objects[] = $country;
				}
			}
			$regions_name = array_unique($regions_name);
			$countries_name = array_unique($countries_name);
			$location_array = array_merge($regions_name,$countries_name);
			$location_array = array_unique($location_array);
			sort($location_array);
			$subCategories = $this->categoryRepository->getSubCategories($categoryId,'abroad');
			$LDBCourses = array();
		}
		$subcat_array = $datanew['category_id'];
		foreach($subCategories as $subcat) {
			if(in_array($subcat->getId(), $subcat_array)){
				$subCategories_new[] = $subcat;
			}
		}
		$subCategories = $subCategories_new;
		$subcat_name_array = array();
		$subCategories = $this->filterSubcategoryandLdbCourse($datanew['main'],$subCategories,'category_id',$alphabet1,$cities_objects,$states_objects,$scope,$CategoryPageClient);
		if(count($LDBCourses)>0) {
			$LDBCourses = $this->filterSubcategoryandLdbCourse($datanew['main'],$LDBCourses,'ldb_course_id',$alphabet1,$cities_objects,$states_objects,$scope,$CategoryPageClient);
			foreach($subCategories as $subcat_obj) {
				$subcat_name_array[] = trim(strtolower($subcat_obj->getName()));
			}
		}
		if(preg_match('/[b-zB-Z]/',$checkAlphabetEmpty) && strlen($checkAlphabetEmpty)==1){
			$canonicalurl = SHIKSHA_HOME.'/categoryList/Browse/page2/'.$categoryId.'/'.$categoryName.'/'.$scope.'/'.strtoupper($checkAlphabetEmpty);	
		}else{
			$canonicalurl = SHIKSHA_HOME.'/categoryList/Browse/page2/'.$categoryId.'/'.$categoryName.'/'.$scope;	
		}
		$data = array(
            'subCategories' => $subCategories,
            'LDBCourses' => $LDBCourses,
            'scope' => $scope,
            'catname'=>$categoryName,
            'catid'=>$categoryId,
            'alphabet1'=>$alphabet1,
            'locations'=>$location_array,
		    'subcat_name_array'=>$subcat_name_array,
            'loadJQUERY'=>'YES',
	    'canonicalurl' => $canonicalurl,
		);
		
		//Tracking Code
		$data['beaconTrackData'] = array(
		    'pageIdentifier' => 'browsePage2',
		    'pageEntityId' => 'page2-categoryId-'.$categoryId.'-categoryName-'.$categoryName.'-scope-'.$scope.'-alphabet1-'.$alphabet1,
		    'extraData' => array(
				'url'=>get_full_url(),
				'categoryId' => $categoryId,
			)
		);
		$data['validateuser'] = $this->checkUserValidation();
		$this->load->view('footerBrowsePageNew2',$data);
	}

	
	
	function page3_old($type, $typeId, $alphabet, $scope)
	{
		$redirectUrl = base_url()."sitemap";
		redirect($redirectUrl, 'location', 301);
		
		$this->_init();
// 		$categoryPageZeroResultHandler = new CategoryPageZeroResultHandler(); 
		
// 		$searchCriteria= array();
// 		$categoryPageZeroResultHandler->getNonZeroCategoryPagesForBrowse($searchCriteria);
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$courseName = '';
		$categoryPageRequest = new CategoryPageRequest;
		$CategoryPageClient = new CategoryPageClient();
		if($type == 'category') {
			$category = $this->categoryRepository->find($typeId);
			$courseName = $category->getName();
			$categoryPageRequest->setData(array('categoryId' => $typeId));
		}
		else if($type == 'subcategory') {
			$subcategory = $this->categoryRepository->find($typeId);
			$courseName = $subcategory->getName();
			
			if($scope == 'India' && in_array($typeId,$RNRSubcategories))
			{ 
				$categoryPageRequest->setNewURLFlag(1);
			  	$URLData['subCategoryId'] = $typeId;
			  	$URLData['naukrilearning'] = (int) 0;
			  	$URLData['sortOrder'] = 'none';
				$categoryPageRequest->setData($URLData);
			}
			else 
			{
				$categoryPageRequest->setData(array('subCategoryId' => $typeId));
			}
			
		}
		else {
			$LDBCourse = $this->LDBCourseRepository->find($typeId);
			$courseName = $LDBCourse->getDisplayName();
			
			$subCategory = $this->categoryRepository->getCategoryByLDBCourse($typeId);
			$subCategoryId = $subCategory->getId();
			
			if($scope == 'India' && !empty($subCategoryId) && in_array($subCategoryId,$RNRSubcategories))
			{
				$URLData ['LDBCourseId'] = ( int ) $typeId;
				$URLData['naukrilearning'] = (int) 0;
				$URLData['sortOrder'] = 'none';
				$categoryPageRequest->setNewURLFlag(1);
				$categoryPageRequest->setData($URLData);
			}
			else
			{
					
				$categoryPageRequest->setData(array('LDBCourseId' => $typeId));
					
			}
				
		}
		//if(strtolower($courseName) == 'all') {
		//	$courseName ="";
		//}
		$data = array(
            'categoryPageRequest' => $categoryPageRequest,
            'courseName' => $courseName
		);

		if($scope == 'India') {
			$categoryPageRequest->setData(array('countryId'=>2));
			$datanew = $CategoryPageClient->getDynamicLocationListForBrowseInstitute($categoryPageRequest);
			$states_having_data = array_unique($datanew['states']);
			$cities_having_data = array_unique($datanew['cities']);
			$categoryPageRequest->setData(array('countryId'=>NULL));
			/*
			 * Build states, cities and localities
			 */
			$states = $this->locationRepository->getStatesByCountry(2);
			$citiesByTiers = $this->locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
			$cities = array();
			foreach($citiesByTiers as $tier => $citiesInTier) {
				foreach($citiesInTier as $city) {
					if(in_array($city->getId(),$cities_having_data)) {
						$cities[$city->getId()] = $city;
					}
				}
			}
			foreach($states as $state) {
				if(in_array($state->getId(),$states_having_data)) {
					$states_new[] = $state;
				}
			}
			$states = $states_new;
			//$localities = $this->locationRepository->getLocalities();
			//foreach($localities as $locality) {

			/*
			 * cityName is not a property (attribute) of Locality class
			 * It is dynamically assigned here so that it can be used in the view
			 * http://krisjordan.com/dynamic-properties-in-php-with-stdclass
			 */
			//$locality->cityName = isset($cities[$locality->getCityId()]) ? $cities[$locality->getCityId()]->getName() : '';
			//}

			$states = $this->_filterLocationsByAlphabet($states,$alphabet);
			$cities = $this->_filterLocationsByAlphabet($cities,$alphabet);
                        $states_new_array = array();
			$cities_name_array = array();
			foreach ($cities as $citi_name) {
				$cities_name_array[] = strtolower($citi_name->getName());
			}
			foreach ($states as $state) {
				if(!in_array(strtolower($state->getName()),$cities_name_array)) {
					$states_new_array[] = $state;
				}
			}
			$states = $states_new_array;
			//var_dump($cities_name_array);
			//$localities = $this->_filterLocationsByAlphabet($localities,$alphabet);
			$data['states'] = $states;
			$data['cities'] = $cities;
			//$data['localities'] = $localities;
		}
		else {
			/*
			 * Build study abroad locations
			 * Regions and countries
			 */
			$regions = array();
			$countries = array();

			/*
			 * Get regions and countries in each region
			 */
			$data['categoryPageRequest'] = $categoryPageRequest;
            $categoryPageRequest->setData(array('countryId'=>99)); // Country Id set to 99 to make request for Study Abroad
			$datanew = $CategoryPageClient->getDynamicLocationListForBrowseInstitute($categoryPageRequest);
			$abroad_country_having_data = $datanew['countries'];
			$abroad_region_having_data = $datanew['regionid'];
			$regions = $this->locationRepository->getRegions();
			$countries = $this->locationRepository->getCountries();
			$regions = $this->_filterLocationsByAlphabet($regions,$alphabet);
			$countries = $this->_filterLocationsByAlphabet($countries,$alphabet);
			foreach($regions as $region) {
				if(in_array($region->getId(),$abroad_region_having_data)) {
					$regions_new[] = $region;
				}
			}
			foreach($countries as $country) {
				if(in_array($country->getId(),$abroad_country_having_data)) {
					$countries_new[] = $country;
				}
			}
			$data['regions'] = $regions_new;
			$data['countries'] = $countries_new;
		}

		$data['scope'] = $scope;
		$data['alfa'] = $alphabet;
                $data['loadJQUERY'] = 'YES';
		$data['validateuser'] = $this->checkUserValidation();
		$data['canonicalurl'] = SHIKSHA_HOME.'/categoryList/Browse/page3/subcategory/'.$typeId.'/'.$alphabet.'/'.$scope;
		$this->load->view('footerBrowsePageNew3_old',$data);
	}

	function page3_ForLevelChange($type, $typeId, $alphabet, $scope, $exam = 'none', $affiliation = 'none', $fee = 'none')
	{
		$redirectUrl = base_url()."sitemap";
		redirect($redirectUrl, 'location', 301);
		
		$this->_init();
		$affiliationSuffix = $this->config->item('CP_AFFILIATION_TO_VALUE_MAP');
		$categoryPageRequest = new CategoryPageRequest;
		$CategoryPageClient = new CategoryPageClient();
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$categoryPageZeroResultHandler = new CategoryPageZeroResultHandler();
		$searchCriteria= array();
		$canonicalurlSuffix = "";
		if($exam != 'none')
		{ 	$canonicalurlSuffix = $exam;
			$searchCriteria['exam'] = $exam;
			if($affiliation != 'none')
			{   $canonicalurlSuffix = $canonicalurlSuffix."/".$affiliation;
				$searchCriteria['affiliation'] = $affiliation;
				if ($fee != 'none') {
					$canonicalurlSuffix = $canonicalurlSuffix."/".$fee;
					$searchCriteria['fees'] = $fee;
				}
			}
			
			 
		}
		
		if($type == 'category') {	
			
			$category = $this->categoryRepository->find($typeId);
			$courseName = $category->getName();
			$searchCriteria['categoryId'] = $typeId;
			$categoryPageRequest->setData(array('categoryId' => $typeId));
		}
		else if($type == 'subcategory') {
			$category = $this->categoryRepository->find($typeId);
			$courseName = $category->getName();
			$searchCriteria['subCategoryId'] = $typeId;
			$searchCriteria['LDBCourseId'] = 1;
			$categoryPageRequest->setData(array('subCategoryId' => $typeId));
			
		}else {
			$LDBCourse = $this->LDBCourseRepository->find($typeId);
			$courseName = $LDBCourse->getDisplayName();
			$searchCriteria['LDBCourseId'] = $typeId;
			$categoryPageRequest->setData(array('LDBCourseId' => $typeId));
		}
		
		$data = array();
	if($scope == 'India')
	{ 		$searchCriteria['nameInitial'] = $alphabet;
			$searchCriteria['countryId'] = 2;
				
			$resultSetForCities = $categoryPageZeroResultHandler->getNonZeroCategoryPagesForBrowse( $searchCriteria,'city');
			$resultSetForStates = $categoryPageZeroResultHandler->getNonZeroCategoryPagesForBrowse( $searchCriteria,'state');
		
		if($exam == 'none')
		{    $examList = array();
			foreach ($resultSetForCities as $resultSetForCity)
			{
				if(!in_array($resultSetForCity['exam'],$examList) && $resultSetForCity['exam'] != 'none')
				{ 
					$examList [] = $resultSetForCity['exam'];
				}
			}
			foreach ($resultSetForStates as $resultSetForState)
			{
				if(!in_array($resultSetForState['exam'],$examList) && $resultSetForState['exam'] != 'none')
				{ 
					$examList [] =$resultSetForState['exam'];
				}
			}

			$examList = count($examList) >1 ? $this->_examsWithPriorityOrder($examList) : $examList;
			
		} elseif($fee == 'none')
		{   $feeList = array();
		    foreach ($resultSetForCities as $resultSetForCity)
			{
				if(!in_array($resultSetForCity['fees'],$feeList) && $resultSetForCity['fees'] != 'none')
				{
					$feeList [$resultSetForCity['fees']] = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'][$resultSetForCity['fees']];
				}
			}
			foreach ($resultSetForStates as $resultSetForState)
			{
				if(!in_array($resultSetForState['fees'],$feeList) && $resultSetForState['fees'] != 'none')
				{
					$feeList [$resultSetForState['fees']] = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'][$resultSetForState['fees']];
				}
			}
		  ksort($feeList);
		}
		elseif($affiliation == 'none')
		{   $affliationList = array();
			foreach ($resultSetForCities as $resultSetForCity)
			{
				if(!in_array($resultSetForCity['affiliation'],$affliationList) && $resultSetForCity['affiliation'] != 'none')
				{
					$affliationList [] = $resultSetForCity['affiliation'];
				}
			}
			foreach ($resultSetForStates as $resultSetForState)
			{
				if(!in_array($resultSetForState['affiliation'],$affliationList) && $resultSetForState['affiliation'] != 'none')
				{
					$affliationList [] = $resultSetForState['affiliation'];
				}
			}
		}
			
		
		$states = $this->locationRepository->getStatesByCountry(2);
		$citiesByTiers = $this->locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		foreach ( $states as $state ) {
			foreach ( $resultSetForStates as $resultSetForState ) {
				if ($state->getId () == $resultSetForState ['state_id'] && $resultSetForState['exam'] == $exam  && $resultSetForState['affiliation'] == $affiliation && $resultSetForState['fees'] == $fee) {
					$resultSetForStatesWithOrder [] = $resultSetForState;
					break;
				}
			}
		}
		
		foreach ( $citiesByTiers as $tier => $citiesInTier ) {
			foreach ( $citiesInTier as $city ) {
				foreach ( $resultSetForCities as $resultSetForCity ) {
					if ($city->getId () == $resultSetForCity ['city_id']  && $resultSetForCity['exam'] == $exam  && $resultSetForCity['affiliation'] == $affiliation && $resultSetForCity['fees'] == $fee) {
						$resultSetForCitiesWithOrder [] =  $resultSetForCity;
						break;
					}
				}
			}
		}
	}
	else {
			/*
			 * Build study abroad locations
			* Regions and countries
			*/
			$regions = array();
			$countries = array();
	
			/*
			 * Get regions and countries in each region
			*/
			$data['categoryPageRequest'] = $categoryPageRequest;
			$categoryPageRequest->setData(array('countryId'=>99)); // Country Id set to 99 to make request for Study Abroad
			$datanew = $CategoryPageClient->getDynamicLocationListForBrowseInstitute($categoryPageRequest);
			$abroad_country_having_data = $datanew['countries'];
			$abroad_region_having_data = $datanew['regionid'];
			$regions = $this->locationRepository->getRegions();
			$countries = $this->locationRepository->getCountries();
			$regions = $this->_filterLocationsByAlphabet($regions,$alphabet);
			$countries = $this->_filterLocationsByAlphabet($countries,$alphabet);
			foreach($regions as $region) {
				if(in_array($region->getId(),$abroad_region_having_data)) {
					$regions_new[] = $region;
				}
			}
			foreach($countries as $country) {
				if(in_array($country->getId(),$abroad_country_having_data)) {
					$countries_new[] = $country;
				}
			}
			$data['regions'] = $regions_new;
			$data['countries'] = $countries_new;
		}
		
       foreach($resultSetForStatesWithOrder as $state)
		{
			foreach($resultSetForCitiesWithOrder as $key => $city)
			{
				if($city['city_name'] == $state['state_name'])
				{
					unset($resultSetForCitiesWithOrder[$key]);
				}
			}
		}

		$data['categoryPageRequest'] = $categoryPageRequest;
		$data['courseName'] = $courseName;
		$data['StatesWithKey'] = $resultSetForStatesWithOrder;
		$data['CityWithKey'] = $resultSetForCitiesWithOrder;
		$data['RNRSubcategories'] = $RNRSubcategories;
		$data['examList'] = $examList;
		$data['affliationList'] = $affliationList;
		$data['feeList'] = $feeList;
		$data['type'] = $type;
		$data['typeId'] = $typeId;
		$data['alphabet'] = $alphabet;
		$data['scope'] = $scope;
		$data['affiliationFromURL'] = $affiliation;
		$data['examFromURL'] = $exam;
		$data['feeFromURL'] = $fee;
		$data['affiliationSuffix'] = $affiliationSuffix;
		$data['scope'] = $scope;
		$data['alfa'] = $alphabet;
		$data['loadJQUERY'] = 'YES';
		$data['validateuser'] = $this->checkUserValidation();
		$data['canonicalurl'] = SHIKSHA_HOME.'/categoryList/Browse/page3/subcategory/'.$typeId.'/'.$alphabet.'/'.$scope.'/'.rawurlencode($canonicalurlSuffix);
		$this->load->view('footerBrowsePageNew3',$data);
	}
	



   function page3($type, $typeId, $alphabet, $scope, $exam = 'none', $fee = 'none', $affiliation = 'none')
	{  
		$redirectUrl = base_url()."sitemap";
		redirect($redirectUrl, 'location', 301);
		
		$this->_init();
		$affiliationSuffix = $this->config->item('CP_AFFILIATION_TO_VALUE_MAP');
		$categoryPageRequest = new CategoryPageRequest;
		$CategoryPageClient = new CategoryPageClient();
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$categoryPageZeroResultHandler = new CategoryPageZeroResultHandler();
		$searchCriteria= array();
		$canonicalurlSuffix = "";
		if($exam != 'none')
		{ 	$canonicalurlSuffix = $exam;
			$searchCriteria['exam'] = $exam;
			if($fee != 'none')
			{   $canonicalurlSuffix = $canonicalurlSuffix."/".$fee;
				$searchCriteria['fees'] = $fee;
				if ($affiliation != 'none') {
					$canonicalurlSuffix = $canonicalurlSuffix."/".$affiliation;
					$searchCriteria['affiliation'] = $affiliation;
				}
			}
			
		}

		$beaconTrackingCategoryId = 0;
		$beaconTrackingSubCategoryId = 0;
		$beaconTrackingLDBCourseId = 0;

		if($type == 'category') {	
			
			$category = $this->categoryRepository->find($typeId);
			$courseName = $category->getName();
			$searchCriteria['categoryId'] = $typeId;
			$categoryPageRequest->setData(array('categoryId' => $typeId));
			$beaconTrackingCategoryId = $typeId;
		}
		else if($type == 'subcategory') {
			$category = $this->categoryRepository->find($typeId);
			$courseName = $category->getName();
			$searchCriteria['subCategoryId'] = $typeId;
			$searchCriteria['LDBCourseId'] = 1;
			$categoryPageRequest->setData(array('subCategoryId' => $typeId));
			$beaconTrackingCategoryId = $category->getParentId();
			$beaconTrackingSubCategoryId = $typeId;
			$beaconTrackingLDBCourseId = 1;

		}else {
			$LDBCourse = $this->LDBCourseRepository->find($typeId);
			$courseName = $LDBCourse->getDisplayName();
			$searchCriteria['LDBCourseId'] = $typeId;
			$categoryPageRequest->setData(array('LDBCourseId' => $typeId));

			$beaconTrackingLDBCourseId = $typeId;
			$beaconTrackingSubCategoryId = $LDBCourse->getSubCategoryId();
			$beaconTrackingCategoryId = $LDBCourse->getCategoryId();
		}
		
		$data = array();
	if($scope == 'India')
	{ 		$searchCriteria['nameInitial'] = $alphabet;
			$searchCriteria['countryId'] = 2;
				
			$resultSetForCities = $categoryPageZeroResultHandler->getNonZeroCategoryPagesForBrowse( $searchCriteria,'city');
			$resultSetForStates = $categoryPageZeroResultHandler->getNonZeroCategoryPagesForBrowse( $searchCriteria,'state');
			
		if($exam == 'none')
		{    $examList = array();
			foreach ($resultSetForCities as $resultSetForCity)
			{
				if(!in_array($resultSetForCity['exam'],$examList) && $resultSetForCity['exam'] != 'none')
				{ 
					$examList [] = $resultSetForCity['exam'];
				}
			}
			foreach ($resultSetForStates as $resultSetForState)
			{
				if(!in_array($resultSetForState['exam'],$examList) && $resultSetForState['exam'] != 'none')
				{ 
					$examList [] =$resultSetForState['exam'];
				}
			}

			$examList = count($examList) >1 ? $this->_examsWithPriorityOrder($examList) : $examList;
			
		} elseif($fee == 'none')
		{   $feeList = array();
		    foreach ($resultSetForCities as $resultSetForCity)
			{
				if(!in_array($resultSetForCity['fees'],$feeList) && $resultSetForCity['fees'] != 'none')
				{
					$feeList [$resultSetForCity['fees']] = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'][$resultSetForCity['fees']];
				}
			}
			foreach ($resultSetForStates as $resultSetForState)
			{
				if(!in_array($resultSetForState['fees'],$feeList) && $resultSetForState['fees'] != 'none')
				{
					$feeList [$resultSetForState['fees']] = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'][$resultSetForState['fees']];
				}
			}
		  ksort($feeList);
		}
		elseif($affiliation == 'none')
		{   $affliationList = array();
			foreach ($resultSetForCities as $resultSetForCity)
			{
				if(!in_array($resultSetForCity['affiliation'],$affliationList) && $resultSetForCity['affiliation'] != 'none')
				{
					$affliationList [] = $resultSetForCity['affiliation'];
				}
			}
			foreach ($resultSetForStates as $resultSetForState)
			{
				if(!in_array($resultSetForState['affiliation'],$affliationList) && $resultSetForState['affiliation'] != 'none')
				{
					$affliationList [] = $resultSetForState['affiliation'];
				}
			}
		}
			
		$states = $this->locationRepository->getStatesByCountry(2);
		$citiesByTiers = $this->locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		foreach ( $states as $state ) {
			foreach ( $resultSetForStates as $resultSetForState ) {
				if ($state->getId () == $resultSetForState ['state_id'] && $resultSetForState['exam'] == $exam  && $resultSetForState['affiliation'] == $affiliation && $resultSetForState['fees'] == $fee) {
					$resultSetForStatesWithOrder [] = $resultSetForState;
					break;
				}
			}
		}
		
		foreach ( $citiesByTiers as $tier => $citiesInTier ) {
			foreach ( $citiesInTier as $city ) {
				foreach ( $resultSetForCities as $resultSetForCity ) {
					if ($city->getId () == $resultSetForCity ['city_id']  && $resultSetForCity['exam'] == $exam  && $resultSetForCity['affiliation'] == $affiliation && $resultSetForCity['fees'] == $fee) {
						$resultSetForCitiesWithOrder [] =  $resultSetForCity;
						break;
					}
				}
			}
		}
	}
	else {
			/*
			 * Build study abroad locations
			* Regions and countries
			*/
			$regions = array();
			$countries = array();
	
			/*
			 * Get regions and countries in each region
			*/
			$data['categoryPageRequest'] = $categoryPageRequest;
			$categoryPageRequest->setData(array('countryId'=>99)); // Country Id set to 99 to make request for Study Abroad
			$datanew = $CategoryPageClient->getDynamicLocationListForBrowseInstitute($categoryPageRequest);
			$abroad_country_having_data = $datanew['countries'];
			$abroad_region_having_data = $datanew['regionid'];
			$regions = $this->locationRepository->getRegions();
			$countries = $this->locationRepository->getCountries();
			$regions = $this->_filterLocationsByAlphabet($regions,$alphabet);
			$countries = $this->_filterLocationsByAlphabet($countries,$alphabet);
			foreach($regions as $region) {
				if(in_array($region->getId(),$abroad_region_having_data)) {
					$regions_new[] = $region;
				}
			}
			foreach($countries as $country) {
				if(in_array($country->getId(),$abroad_country_having_data)) {
					$countries_new[] = $country;
				}
			}
			$data['regions'] = $regions_new;
			$data['countries'] = $countries_new;
			
		//	 $data['regions'];
		// $data['countries'];
		}
		

     foreach($resultSetForStatesWithOrder as $state)
     {
     	foreach($resultSetForCitiesWithOrder as $key => $city)
     	{
     		if($city['city_name'] == $state['state_name'])
     		{
     			unset($resultSetForCitiesWithOrder[$key]);
     		}
     	}
     }
   
		$data['categoryPageRequest'] = $categoryPageRequest;
		$data['courseName'] = $courseName;
		$data['StatesWithKey'] = $resultSetForStatesWithOrder;
		$data['CityWithKey'] = $resultSetForCitiesWithOrder;
		$data['RNRSubcategories'] = $RNRSubcategories;
		$data['examList'] = $examList;
		$data['affliationList'] = $affliationList;
		$data['feeList'] = $feeList;
		$data['type'] = $type;
		$data['typeId'] = $typeId;
		$data['alphabet'] = $alphabet;
		$data['scope'] = $scope;
		$data['affiliationFromURL'] = $affiliation;
		$data['examFromURL'] = $exam;
		$data['feeFromURL'] = $fee;
		$data['affiliationSuffix'] = $affiliationSuffix;
		$data['scope'] = $scope;
		$data['alfa'] = $alphabet;
		$data['loadJQUERY'] = 'YES';
		$data['validateuser'] = $this->checkUserValidation();
		$data['canonicalurl'] = SHIKSHA_HOME.'/categoryList/Browse/page3/subcategory/'.$typeId.'/'.$alphabet.'/'.$scope.'/'.rawurlencode($canonicalurlSuffix);
		
		//Tracking Code
		$data['beaconTrackData'] = array(
		    'pageIdentifier' => 'browsePage3',
		    'pageEntityId' => 0,
		    'extraData' => array(
				'url'=>get_full_url(),
				'categoryId' => $beaconTrackingCategoryId,
				'subCategoryId' => $beaconTrackingSubCategoryId,
				'LDBCourseId' => $beaconTrackingLDBCourseId,
			)
		);

		$this->load->view('footerBrowsePageNew3',$data);
	}
	
	private function _examsWithPriorityOrder($unSortedexamList)
	{
		$examListWithWeightage = array();
		foreach ($unSortedexamList as $exam)
		{
			global $exam_weightage_array;
			$examWeightage = $exam_weightage_array[$exam];
			$examWeightage = $examWeightage >0 ?  $examWeightage : 0;
			if(array_key_exists($examWeightage,$examListWithWeightage) && (!in_array($exam,$examListWithWeightage[$examWeightage])))
			{
				array_push($examListWithWeightage [$examWeightage],$exam);
			}
			else
			{
				$examListWithWeightage [$examWeightage] = array($exam);
			}
		}
		ksort($examListWithWeightage);
		$examListWithWeightage = array_reverse($examListWithWeightage);
		$examListWithPriorityOrder = array();
		foreach($examListWithWeightage as $key => $examList){
			foreach($examList as $exam)
			{
				$examListWithPriorityOrder [] = $exam;
			}
		
		}
		return $examListWithPriorityOrder;
		
	}
	private function _filterLocationsByAlphabet($locations,$alphabet)
	{
		$filteredLocations = array();
		foreach($locations as $location) {
			if(strtoupper(substr($location->getName(),0,1)) == strtoupper($alphabet)) {
				$filteredLocations[] = $location;
			}
		}
		return $filteredLocations;
	}

	function index($page=0)
	{
		$redirectUrl = base_url()."sitemap";
		redirect($redirectUrl, 'location', 301);
		
		$this->_init();

		/*
		 * Build category data
		 * Categories, Subcategories and LDB Courses for National
		 * Categories and Subcategories for Study Abroad
		 */
		$categoryData = array();
		$categories = $this->categoryRepository->getSubCategories(1);
		foreach($categories as $category) {
			$subCategories = $this->categoryRepository->getSubCategories($category->getId(),'national');

			$LDBCourses = array();
			foreach($subCategories as $subCategory) {
				$LDBCoursesForSubCategory = (array) $this->LDBCourseRepository->getLDBCoursesForSubCategory($subCategory->getId());
				$LDBCourses = array_merge($LDBCourses,$LDBCoursesForSubCategory);
			}
			$categoryData['national'][] = array('category' => $category, 'subcategories' => $subCategories, 'ldbcourses' => $LDBCourses);

			$subCategoriesStudyAbroad = $this->categoryRepository->getSubCategories($category->getId(),'abroad');
			$categoryData['studyabroad'][] = array('category' => $category, 'subcategories' => $subCategoriesStudyAbroad);
		}

		/*
		 * Build study abroad locations
		 * Regions and countries
		 */
		$studyAbroadLocations = array();

		/*
		 * Get regions and countries in each region
		 */
		$regions = $this->locationRepository->getRegions();
		foreach($regions as $region) {

			$studyAbroadLocations[] = $region;
			$countries = (array) $this->locationRepository->getCountriesByRegion($region->getId());
			$studyAbroadLocations = array_merge($studyAbroadLocations,$countries);
		}

		/*
		 * Get countries without region
		 */
		$countries = $this->locationRepository->getCountries();
		foreach($countries as $country) {
			$countryId = (int) $country->getId();
			$regionId = (int) $country->getRegionId();
			if($countryId > 2 && !$regionId) {
				$studyAbroadLocations[] = $country;
			}
		}

		$data = array(
            'categoryData' => $categoryData,
            'studyAbroadLocations' => $studyAbroadLocations,
            'page' => intval($page),
            'categoryPageRequest' => new CategoryPageRequest
		);
		//Tracking Code
		$data['beaconTrackData'] = array(
		    'pageIdentifier' => 'browsePage',
		    'pageEntityId' => 'index-page-no-'.$page,
		    'extraData' => array('url'=>get_full_url())
		);

		$this->load->view('footerBrowse',$data);
	}

	function browseInIndia($type,$typeId)
	{
		$redirectUrl = base_url()."sitemap";
		redirect($redirectUrl, 'location', 301);
		
		$this->_init();

		/*
		 * Build states, cities and localities
		 */
      	$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
       	$states = $this->locationRepository->getStatesByCountry(2);
		$citiesByTiers = $this->locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		$cities = array();
		foreach($citiesByTiers as $tier => $citiesInTier) {
			foreach($citiesInTier as $city) {
				$cities[$city->getId()] = $city;
			}
		}
		$localities = $this->locationRepository->getLocalities();
		foreach($localities as $locality) {

			/*
			 * cityName is not a property (attribute) of Locality class
			 * It is dynamically assigned here so that it can be used in the view
			 * http://krisjordan.com/dynamic-properties-in-php-with-stdclass
			 */
			$locality->cityName = isset($cities[$locality->getCityId()]) ? $cities[$locality->getCityId()]->getName() : '';
		}

		$courseName = '';
		$categoryPageRequest = new CategoryPageRequest;

		if($type == 'category') {
			$category = $this->categoryRepository->find($typeId);
			$courseName = $category->getName();
			$categoryPageRequest->setData(array('categoryId' => $typeId));
		}
		else if($type == 'subcategory') {
			$subcategory = $this->categoryRepository->find($typeId);
			$courseName = $subcategory->getName();
			if(in_array($subCategoryId, $RNRSubcategories))
			{
				$URLData ['subCategoryId'] = ( int ) $subCategoryId;
				$URLData['naukrilearning'] = (int) 0;
				$URLData['sortOrder'] = 'none';
				$categoryPageRequest->setNewURLFlag(1);
				$categoryPageRequest->setData($URLData);
			}
			else
			{
				
				$categoryPageRequest->setData(array('subCategoryId' => $typeId));
					
			}
		
		}
		else {
			$LDBCourse = $this->LDBCourseRepository	->find($typeId);
		    $subCategory = $this->categoryRepository->getCategoryByLDBCourse($typeId);
			$subCategoryId = $subCategory->getId();
			$courseName = $LDBCourse->getDisplayName();
			if(in_array($subCategoryId, $RNRSubcategories))
			{
				$URLData ['LDBCourseId'] = ( int ) $typeId;
				$URLData['naukrilearning'] = (int) 0;
				$URLData['sortOrder'] = 'none';
				$categoryPageRequest->setNewURLFlag(1);
				$categoryPageRequest->setData($URLData);
			}
			else 
			{
				$categoryPageRequest->setData(array('LDBCourseId' => $typeId));
			}		
	
			
		}

		$data = array(
            'states' => $states,
            'cities' => $cities,
            'localities' => $localities,
            'categoryPageRequest' => $categoryPageRequest,
            'courseName' => $courseName,
		);
		//Tracking Code
		$data['beaconTrackData'] = array(
		    'pageIdentifier' => 'browsePage',
		    'pageEntityId' => 'browseInIndia-type-'.$type."-typeId-".$typeId,
		    'extraData' => array('url'=>get_full_url())
		);

		$this->load->view('footerBrowseIndia',$data);
	}
	function filterSubcategoryandLdbCourse($data,$filter,$type,$alphabet1,$cities_objects,$states_objects,$scope,$CategoryPageClient) {
		$redirectUrl = base_url()."sitemap";
		redirect($redirectUrl, 'location', 301);
		
		foreach ($filter as $obj) {
			foreach ($data as $obj1) {
				if($obj1[$type] == $obj->getId()) {
					$array[$obj1[$type]]['states'][] = $obj1['state_id'];
					$array[$obj1[$type]]['cities'][] = $obj1['virtualCityId'];
					$array[$obj1[$type]]['countries'][] = $obj1['country_id'];
				}
			}
		}
		foreach ($array as $key=>$value) {
			$array1[$key]['states'] = array_unique($value['states']);
			$array1[$key]['cities'] = array_unique($value['cities']);
			$array1[$key]['countries'] = array_unique($value['countries']);
		}
		foreach ($array1 as $key=>$value) {
			$states_objects1 = array();
			$cities_objects1 = array();
			if($scope == 'India') {
				foreach ($states_objects as $state) {
					if(in_array($state->getId(), $value['states'])) {
						$states_objects1[] = $state;
					}
				}
				foreach ($cities_objects as $city) {
					if(in_array($city->getId(), $value['cities'])) {
						$cities_objects1[] = $city;
					}
				}
			} else {
				$regs = $CategoryPageClient->getRegionsForCountries($value['countries']);
				foreach ($cities_objects as $city) {
					if(in_array($city->getId(), $regs["regionid"])) {
						$cities_objects1[] = $city;
					}
				}
				foreach ($states_objects as $state) {
					if(in_array($state->getId(), $value['countries'])) {
						$states_objects1[] = $state;
					}
				}
			}
			$array100 = $this->_filterLocationsByAlphabet($states_objects1,$alphabet1);
			$array200 = $this->_filterLocationsByAlphabet($cities_objects1,$alphabet1);
			if(count($array100)>0 || count($array200)>0) {
				$finalsubcat[] = $key;
			}
		}
		foreach ($filter as $obj) {
			if(in_array($obj->getId(),$finalsubcat)) {
				$filter1[] = $obj;
			}
		}
		return $filter1;
	}
}
