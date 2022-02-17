<?php

class CategoryPageRequest
{
    private $categoryId;
	private $subCategoryId;
	private $LDBCourseId;
	private $localityId;
	private $zoneId;
	private $cityId;
	private $stateId;
	private $countryId;
	private $regionId;
	private $sortOrder;
	private $pageNumber;
	private $naukrilearning;
	private $pageIdentifier;
	
	public function __construct($categoryPageURLQueryString = '')
    {
		$this->buildFromURLQueryString($categoryPageURLQueryString);
    }
	
	public function buildFromURLQueryString($categoryPageURLQueryString)
	{
		$categoryPageParameters = explode("-",$categoryPageURLQueryString);
		
		$this->categoryId = (int) $categoryPageParameters['0'];
		$this->subCategoryId =  (int) $categoryPageParameters['1'];
		$this->LDBCourseId =  (int) $categoryPageParameters['2'];
		$this->localityId = (int) $categoryPageParameters['3'];
		$this->zoneId = (int) $categoryPageParameters['4'];
		$this->cityId =  (int) $categoryPageParameters['5'];
		$this->stateId =  (int) $categoryPageParameters['6'];
		$this->countryId =  (int) $categoryPageParameters['7'];
		$this->regionId =  (int) $categoryPageParameters['8']? $categoryPageParameters['8']:0;
		$this->sortOrder = $categoryPageParameters['9']!=""?$categoryPageParameters['9']:'none';
		$this->pageNumber = (int) $categoryPageParameters['10']!=""?$categoryPageParameters['10']:1;
		$this->naukrilearning = (int) $categoryPageParameters['11']?1:0;
	}
	
	public function setData($data = array())
	{
		foreach($data as $key => $value) {
			if(property_exists($this,$key)) {
				$this->$key = $value;
			}
		}
	}

	public function isMainCategoryPage()
	{
		return (intval($this->categoryId) > 1 && intval($this->subCategoryId) <= 1);
	}
	
	public function isSubcategoryPage()
	{
		return (intval($this->subCategoryId) > 1 && intval($this->LDBCourseId) <= 1);
	}
	
	public function isLDBCoursePage()
	{
		return intval($this->LDBCourseId) > 1;
	}
	
	public function isLocalityPage()
	{
		return intval($this->localityId) > 0;
	}
	
	public function isZonePage()
	{
		return (intval($this->zoneId) > 0 && intval($this->localityId) == 0);
	}
	
	public function isCityPage()
	{
		return (intval($this->cityId) > 1 && intval($this->zoneId) == 0 && intval($this->localityId) == 0);
	}
	
	public function isStatePage()
	{
		return (intval($this->stateId) > 1 && intval($this->cityId) <= 1 && intval($this->zoneId) == 0 && intval($this->localityId) == 0);
	}
	
	public function getAppliedFilters()
	{
		$cookiePrefix = '';
		if($this->pageIdentifier == 'ResponseMarketing') {
			$cookiePrefix = 'response_marketing_';
		}
		
		$appliedFilters = json_decode(base64_decode($_COOKIE[$cookiePrefix."filters-".$this->getPageKey()]),true);
		if($this->zoneId > 0 && !$appliedFilters['locality']){
			$appliedFilters['zone'] = array($this->zoneId);
			if($this->localityId > 0){
				$appliedFilters['locality'] = array($this->localityId);
			}
		}
		return $appliedFilters;
	}
	
	public function getSortingCriteria()
	{
		if(isset($_COOKIE["sortby-".$this->getPageKey()])){
			$this->sortOrder = $_COOKIE["sortby-".$this->getPageKey()];
		}
		switch($this->sortOrder){
			case 'highfees':
				$sortingCriteria = array('sortBy' => 'fees', 'params' => array('order' => 'DESC'));
				break;
			case 'lowfees':
				$sortingCriteria = array('sortBy' => 'fees', 'params' => array('order' => 'ASC'));
				break;
			case 'longduration':
				$sortingCriteria = array('sortBy' => 'duration', 'params' => array('order' => 'DESC'));
				break;
			case 'shortduration':
				$sortingCriteria = array('sortBy' => 'duration', 'params' => array('order' => 'ASC'));
				break;
			case 'viewCount':
				$sortingCriteria = array('sortBy' => 'viewCount', 'params' => array('order' => 'DESC'));
				break;
			case 'topInstitutes':
				$sortingCriteria = array('sortBy' => 'topInstitutes', 'params' => array('category' => $this->getCategoryId()));
				break;
			case 'dateOfCommencement':
				$sortingCriteria = array('sortBy' => 'dateOfCommencement', 'params' => array('order' => 'ASC'));
				break;
			case 'reversedateOfCommencement':
				$sortingCriteria = array('sortBy' => 'dateOfCommencement', 'params' => array('order' => 'DESC'));
				break;
		}
		
		if($sortingCriteria) {
			return $sortingCriteria;
		}
	}
	
	public function getPageKey()
	{
		$pageKey =  'CATPAGE-'.$this->categoryId.'-'.$this->subCategoryId.'-'.$this->LDBCourseId.'-'.$this->cityId.'-'.$this->stateId.'-'.$this->countryId.'-'.$this->regionId;
		return $pageKey;
	}
	
	public function getPageNumberForPagination()
	{
		if($this->pageNumber == "") {
			$this->pageNumber = 1;
		}
		//echo "hey".$this->pageNumber;
		return $this->pageNumber;
	}
	
	public function isNaukrilearningPage()
	{
		return $this->naukrilearning;
	}
	
	public function getSortOrder()
	{
		if(isset($_COOKIE["sortby-".$this->getPageKey()])){
			$this->sortOrder = $_COOKIE["sortby-".$this->getPageKey()];
		}
		return $this->sortOrder;
	}
	
	/*
	 * Getters
	 */
	
	public function getCategoryId()
	{
		return $this->categoryId;
	}
	
	public function getSubCategoryId()
	{
		return $this->subCategoryId;
	}
	
	public function getLocalityId()
	{
		return $this->localityId;
	}
	
	public function getLDBCourseId()
	{
		return $this->LDBCourseId;
	}
	
	public function getZoneId()
	{
		return $this->zoneId;
	}
	
	public function getCityId()
	{
		return $this->cityId;
	}
	
	public function getStateId()
	{
		return $this->stateId;
	}
	
	public function getCountryId()
	{
		return $this->countryId;
	}
	
	public function getRegionId()
	{
		return $this->regionId;
	}
	
	public function isStudyAbroadPage()
	{
		return (($this->regionId>0)||($this->countryId!=2));
	}
	
	public function getSnippetsPerPage()
	{
		return $this->_setNoOfResults();
	}

	private function _setNoOfResults()
	{
		if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile'))
		{
			return 10;
		}
		else if($this->pageIdentifier == 'ResponseMarketing') {
			return 15;
		}
	    else
		{
		        return 30;
		}
	}

	public function isAJAXCall()
	{
		return ($_REQUEST['AJAX'] == 1);
	}
	
	public function getURL($pageNumber = 1)
	{
		$urlData = $this->_getURLData($pageNumber);
		if($urlData) {
			return $urlData['url'];
		}
	}
	
	public function getCanonicalURL($pageNumber = 1)
	{
		$requestData['sortOrder'] = 'none';
		$requestData['naukrilearning'] = 0;		
                $this->setData($requestData);
		return $this->getURL((int)($pageNumber));
	}
	
	public function getMetaData($numResults = 0)
	{
		return $this->_getURLData($pageNumber,$numResults);
	}
	
	private function _getURLData($pageNumber,$numResults)
	{
		global $categoryURLDataIndia, $subCategoryURLData, $LDBCourseURLData;
		global $categoryURLPrefixMapping, $countryURLPrefixMapping, $regionURLPrefixMapping;
		global $typeMappingForCatOrSubcat, $metaTitleTypeMapping, $metaDescriptionMapping, $categoryMetaTitleMapping, $categoryMetaDescMapping, $subcategoryMetaTitleMapping, $subcategoryMetaDescMapping;
		
		//global $shikshaCountryMap, $shikshaCityMap, $shikshaStateMap, $shikshaRegionMap;
		
		$ci = & get_instance();
		$ci->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		
		$ci->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		
		$categoryId = (int) $this->categoryId;
		$subCategoryId = (int) $this->subCategoryId;
		$LDBCourseId = (int) $this->LDBCourseId;
		
		$localityId = (int) $this->localityId;
		$zoneId = (int) $this->zoneId;
		$cityId = (int) $this->cityId;
		$stateId = (int) $this->stateId;
		$countryId = (int) $this->countryId;
		$regionId = (int) $this->regionId;
		
		$sortOrder = $this->sortOrder;
		$naukrilearning = (int) $this->naukrilearning;
		
		if($countryId > 2 || $regionId >= 1) {
			if(!$categoryId) {
				$categoryId = 1;
			}
		}
		else if($countryId == 2) {
			if($categoryId <= 1) {
				return FALSE;
			}
		}
		else {
			if($categoryId > 1 || $subCategoryId > 1 || $LDBCourseId > 1) {
				$countryId = 2;
			}
			else {
				return FALSE;
			}
		}

		$pageType = '';$partialSeoText = 'colleges';
		
		/*
		 * If this is an LDB Course Page
		 */ 
		if($LDBCourseId > 1) {

			$ci->load->builder('LDBCourseBuilder','LDB');
			$ldbBuilder = new LDBCourseBuilder;
			$ldbCourseRepository = $ldbBuilder->getLDBCourseRepository();

			$urlData = $LDBCourseURLData[$LDBCourseId];
			//_p()die("<br> DATA = ");
			/*
			 * Make sure we have category Id and subcategory Id
			 */
			if($subCategoryId <= 1) {
				$subCategory = $categoryRepository->getCategoryByLDBCourse($LDBCourseId);
				$subCategoryId = $subCategory->getId();
				$categoryId = $subCategory->getParentId();
			}
			$pageType = 'specialization';
			foreach($typeMappingForCatOrSubcat as $key => $arr){
				if(in_array($subCategoryId,$arr['subcategory'])){
					$partialSeoText = $key;
					break;
				}
			}
			$LDBCourseObj = $ldbCourseRepository->find($LDBCourseId);
			$pagename = $LDBCourseObj->getSpecialization();
			if(strtolower(trim($pagename)) == 'all'){
				$pagename = $LDBCourseObj->getCourseName();
			}
		}
		else if($subCategoryId > 1) {
			
			$urlData = $subCategoryURLData[$subCategoryId];
			/*
			 * Make sure we have category Id and LDB Course Id
			 */ 
			$LDBCourseId = 1;
			$subCategory = $categoryRepository->find($subCategoryId);

			if($categoryId <= 1) {
				$categoryId = $subCategory->getParentId();
			}
			$pageType = 'subcategory';
			foreach($typeMappingForCatOrSubcat as $key => $arr){
				if(in_array($subCategoryId,$arr[$pageType])){
					$partialSeoText = $key;
					break;
				}
			}
			$pagename = $subCategory->getName();
		}
		else {
			
			$urlData = $categoryURLDataIndia[$categoryId];
			$subCategoryId = 1;
			$LDBCourseId = 1;

			$pageType = 'category';
			foreach($typeMappingForCatOrSubcat as $key => $arr){
				if(in_array($categoryId,$arr[$pageType])){
					$partialSeoText = $key;
					break;
				}
			}

			$pagename = $categoryRepository->find($categoryId)->getName();
		}

		if($pageType == 'category'){
			foreach($categoryMetaTitleMapping as $key => $arr){
				if(in_array($categoryId,$arr)){
					$urlData['title'] = $metaTitleTypeMapping[$key];
					break;
				}
			}
			foreach($categoryMetaDescMapping as $key => $arr){
				if(in_array($categoryId,$arr)){
					$urlData['description'] = $metaDescriptionMapping[$key];
					break;
				}
			}
		}
		else{
			foreach($subcategoryMetaTitleMapping as $key => $arr){
				if(in_array($subCategoryId,$arr)){
					$urlData['title'] = $metaTitleTypeMapping[$key];
					break;
				}
			}
			foreach($subcategoryMetaDescMapping as $key => $arr){
				if(in_array($subCategoryId,$arr)){
					$urlData['description'] = $metaDescriptionMapping[$key];
					break;
				}
			}
		}
		
		$location = '';
		
		if($localityId > 0) {
			
			$locality = $locationRepository->findLocality($localityId);
			$location = $locality->getName();
			
			$zoneId = $locality->getZoneId();
			$cityId = $locality->getCityId();
			$stateId = $locality->getStateId();
			$countryId = $locality->getCountryId();
		}
		else if($zoneId > 0) {
			
			$zone = $locationRepository->findZone($zoneId);
			$location = $zone->getName();
			
			$localityId = 0;
			$cityId = $zone->getCityId();
			$stateId = $zone->getStateId();
			$countryId = $zone->getCountryId();
		}
		else if($cityId > 1) {
		    
			$localityId = 0;
			$zoneId = 0;
			
			if($countryId > 2) {
			   	$countryObj = $locationRepository->findCountry($countryId);
			   	$location = $countryObj->getName();
			   	$cityId = 1; 
			   	$stateId = 1;
			   	$regionId = $countryObj->getRegionId();
			} else {
				$cityObj = $locationRepository->findCity($cityId);
			    $location = $cityObj->getName();
			    $stateId = $cityObj->getStateId();
			    if($stateId < 1) {
				    $stateId = 1;
			    }
			    $countryId = 2;
			}
			
		}
		else if($stateId > 1) {
			$localityId = 0;
			$zoneId = 0;
			$cityId = 1;			
			if($countryId > 2) {
				$countryObj = $locationRepository->findCountry($countryId);
			    $location = $countryObj->getName();
			    $stateId = 1;
			    $regionId = $countryObj->getRegionId();
			} else {
				$stateObj = $locationRepository->findState($stateId);
			    $location = $stateObj->getName();
			    $countryId = 2;
			}
		}
		else if($countryId > 1) {
			$location = $countryObj->getName();
			$localityId = 0;
			$zoneId = 0;
			$cityId = 1;
			$stateId = 1;
			$regionId = $countryObj->getRegionId();
		}
		else if($regionId >= 1) {
			$regionObj = $locationRepository->findRegion($regionId);
			$location = $regionObj->getName();
			$location2 = $regionObj->getName()." - ";
			$countriesArray1 = $locationRepository->getCountriesByRegion($regionId);
			foreach($countriesArray1 as $country){
				$countriesArray[] = $country->getName();
			}
			$location2 .= implode(", ",$countriesArray);

			$localityId = 0;
			$zoneId = 0;
			$cityId = 1;
			$stateId = 1;
			$countryId = 1;
		}
		else {
			
			$localityId = 0;
			$zoneId = 0;
			$cityId = 1;
			$stateId = 1;
			$countryId = 2;
			$location = 'India';
		}
		
		$urlIdentifier = '-categorypage-'.$categoryId.'-'.$subCategoryId.'-'.$LDBCourseId.'-'.$localityId.'-'.$zoneId.'-'.$cityId.'-'.$stateId.'-'.$countryId.'-'.$regionId.'-'.$sortOrder.'-'.$pageNumber.'-'.$naukrilearning;
		
		$url = '';
		if($numResults == 1){
			if($partialSeoText == 'classes'){
				$partialSeoText = rtrim($partialSeoText,'es');
			}
			else{
				$partialSeoText = rtrim($partialSeoText,'s');
			}
		}

		if($urlData && is_array($urlData) && count($urlData)) {
			if(!$location2){
				$location2 = $location;
			}
			foreach($urlData as $key => $value)
			{
				if($key != 'url'){
					$urlData[$key] = str_ireplace('{location}',$location2,$urlData[$key]);
					$urlData[$key] = str_ireplace('{resultCount}',$numResults,$urlData[$key]);
					if($key == 'description' && $numResults == 1){
						if($partialSeoText == 'classes'){
							$val = rtrim($partialSeoText,'es');
						}
						else{
							$val = rtrim($partialSeoText,'s');
						}
						$urlData[$key] = str_ireplace('{type}',$val,$urlData[$key]);
					}
					else{
						$urlData[$key] = str_ireplace('{type}',$partialSeoText,$urlData[$key]);
					}
					$urlData[$key] = str_ireplace('{coursetype}',$pagename,$urlData[$key]);
					if($key == 'title' && $this->getPageNumberForPagination() > 1){
						$urlData[$key] = 'Page '.$this->getPageNumberForPagination().' - '.$urlData[$key];
					}
					if($key == 'description' && $categoryId == 13 && $this->getPageNumberForPagination() > 1){
						$urlData[$key] = 'Page '.$this->getPageNumberForPagination().' - '.$urlData[$key];
					}
				}else{
					$urlData[$key] = str_ireplace('{location}',$location,$urlData[$key]);
				}
				
			}
			$url = $urlData['url'];
			$url = str_replace(array(' ','/','(',')',','),'-',$url);
			$url = preg_replace('!-+!', '-', $url);
			$url = strtolower(trim($url,'-'));
		}
	
		$domainPrefix = SHIKSHA_HOME;
		if($naukrilearning) {
			$domainPrefix = NAUKRI_SHIKSHA_HOME;
		}
		else if($countryId > 2) {
			$domainPrefix = $countryURLPrefixMapping[$countryId];
		}
		else if($regionId > 0) {
			$domainPrefix = $regionURLPrefixMapping[$regionId];
		}
		else if($categoryId > 1) { 
			$domainPrefix = $categoryURLPrefixMapping[$categoryId];
		}
	
                if($domainPrefix == "")
                    $domainPrefix = SHIKSHA_HOME;

		// $url = $domainPrefix.'/'.$url.$urlIdentifier;
                if($subCategoryId == 1 && $cityId == 1 && $stateId == 1 && $countryId == 2 && (!$pageNumber || $pageNumber < 2) && SHIKSHA_ENV == 'live'){
                       $url = $domainPrefix;
               }else{
                       $url = $domainPrefix.'/'.$url.$urlIdentifier;
               }
		
		$urlData['url'] = $url;
		return $urlData;
	}
	
	public function isTestPrep(){
		return  $this->categoryId == 14;
	}

        // Added by Amit K on 27th April 2012 for Cat pages Gutter banners, ticket #920
        public function showGutterBanner() {
	     return 1; // Need to show on all of the Category Pages.
             /*
             if($this->getSubCategoryId() == 23 && $this->cityId == 10223) {        // Full Time MBA/PGDM Delhi/NCR Cat page.
                return 1;
             } else if($this->getSubCategoryId() == 28 && $this->cityId == 10223) {  // Full Time BBA/BBM Delhi/NCR Cat page.
                return 1;
             }
             return 0;
	     */	
        }
}
