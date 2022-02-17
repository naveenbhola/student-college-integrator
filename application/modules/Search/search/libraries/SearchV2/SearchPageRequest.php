<?php

class SearchPageRequest{

	private $CI;
	private $searchKeyword; 
	private $pageNumber;     
	private $categoryId;   
	private $subCategoryId;
	private $city;           
	private $state;          
	private $locality;       
	private $specialization; 
	private $fees;           
	private $exams;     
	private $courseLevel;    
	private $mode;           
	public  $appliedFilters = array();
	private $pageType;
	private $qerResults;
	private $searchedAttributes;
	private $degreePref;
	private $affiliation;
	private $facilities;
	private $classTimings;
	private $trackingSearchId;
	private $twoStepClosedSearch;
	private $requestFrom;
	private $trackingFilterId;
	private $selectedLdbId;
	private $oldKeyword;
	private $relevantResults;
        private $filterSpecializationId;

	public function	__construct($arr = array()){
		$this->CI = & get_instance();
		$this->CI->load->library("search/SearchV2/SearchPageUrlParser");
		$this->CI->load->config("search/SearchPageConfig");

		$this->searchUrlParserObj = new SearchPageUrlParser();
		if(empty($arr)) {
			$this->setDataFromUrlParams();
		}
	}

	private function setDataFromUrlParams(){
		$this->searchUrlParserObj->parseSearchUrl();
		
		$this->searchKeyword             =  $this->searchUrlParserObj->getSearchKeyword();
		$this->pageNumber                =  $this->searchUrlParserObj->getPageNumber();
		$this->categoryId                =  $this->searchUrlParserObj->getCategoryId();
		$this->subCategoryId             =  $this->searchUrlParserObj->getSubCategoryId();
		$this->city                      =  $this->searchUrlParserObj->getCity();
		$this->state                     =  $this->searchUrlParserObj->getState();
		$this->locality                  =  $this->searchUrlParserObj->getLocality();
		$this->specialization            =  $this->searchUrlParserObj->getSpecialization();
		$this->fees                      =  $this->searchUrlParserObj->getFees();
		$this->exams                     =  $this->searchUrlParserObj->getExams();
		$this->courseLevel               =  $this->searchUrlParserObj->getCourseLevel();
		$this->mode                      =  $this->searchUrlParserObj->getMode();
		$this->qerResults        		 =  $this->searchUrlParserObj->getQerResults();
		$this->searchedAttributes        =  $this->searchUrlParserObj->getSearchedAttributes();
		$this->degreePref                =  $this->searchUrlParserObj->getDegreePref();
		$this->affiliation               =  $this->searchUrlParserObj->getAffiliation();
		$this->classTimings				 = 	$this->searchUrlParserObj->getClassTimings();
		$this->facilities                =  $this->searchUrlParserObj->getFacilities();
		$this->trackingSearchId 		 =  $this->searchUrlParserObj->getTrackingSearchId();
		$this->twoStepClosedSearch       =  $this->searchUrlParserObj->getTwoStepClosedSearch();
		$this->requestFrom				 =  $this->searchUrlParserObj->getRequestFrom();
		$this->trackingFilterId 		 =	$this->searchUrlParserObj->getTrackingFilterId();
		$this->selectedLdbId			 =  $this->searchUrlParserObj->getSelectedLdbId();
		$this->oldKeyword				 =	$this->searchUrlParserObj->getOldKeyword();
		$this->relevantResults			 =	$this->searchUrlParserObj->getRelevantResults();
	}

	public function getAppliedFilters(){
		$filterAttribute = array('catId'=>'categoryId','subcatId'=>'subCategoryId','city'=>'city',
								 'state'=>'state','locality'=>'locality','fees'=>'fees','exams'=>'exams',
								 'level'=>'courseLevel','mode'=>'mode','course'=>'specialization',
								 'degreePref'=>'degreePref','affiliation'=>'affiliation','classTimings'=>'classTimings','facilities'=>'facilities'
								 );

		foreach ($filterAttribute as $key => $value) {
			if(!empty($this->$value)){
				$this->appliedFilters[$key] = $this->$value;
			}
		}
		return $this->appliedFilters;
	}

	public function getOriginalSetOfFilters() {
		$qerFilters = $this->getQERFilters();
		$userSearchedAttributesData = $this->getSearchedAttributes();

		//merge qer and original filters
		$originalFilters['qerFilters'] = array_merge_recursive($qerFilters, $userSearchedAttributesData);
		foreach ($originalFilters['qerFilters'] as $filterName => $filterValue) {
			$originalFilters['qerFilters'][$filterName] = array_unique($filterValue);
		}

		//add subcat if applied
		$subcatId = $this->getSubCategoryId();
		if(!empty($subcatId)) {
			$originalFilters['userAppliedFilters']['subcatId'] = $subcatId;
		}
		return $originalFilters;
	}

	public function getSearchedAttributes($dropDownSelectKeys = false){
		$searchedAttributesString = $this->searchedAttributes;
		$searchedAttributesDataSet = explode("^", $searchedAttributesString);
		
		$searchedAttributesData = array();
		$re = '/^\d+(?:,\d+)*$/';
		$alphaStr = '/^([A-Za-z])+(?:,[A-Za-z]+)*/';
		$examRe = '/^(([A-Za-z]+)_((\d+)?)\|((\d+)?))+(?:,(([A-Za-z]+)_((\d+)?)\|((\d+)?))+)*/';

		if(!empty($searchedAttributesDataSet[0])){
			if (preg_match($re, $searchedAttributesDataSet[0]) ){
				$searchedAttributesData['city'] = explode(",", $searchedAttributesDataSet[0]);
			} 
		}

		if(!empty($searchedAttributesDataSet[1])){
			if (preg_match($re, $searchedAttributesDataSet[1]) ){
				$searchedAttributesData['state'] = explode(",", $searchedAttributesDataSet[1]);
			}
		}

		if(!empty($searchedAttributesDataSet[2])){
			if (preg_match($examRe, $searchedAttributesDataSet[2]) ){
				$searchedAttributesData['exams'] = explode(",", $searchedAttributesDataSet[2]);
			}
		}

		if(!empty($searchedAttributesDataSet[3])){
			$courseSpecializationKey = 'course';
			if($dropDownSelectKeys) {
				$courseSpecializationKey = 'specialization';
			}
			if (preg_match($re, $searchedAttributesDataSet[3]) ){
				$searchedAttributesData[$courseSpecializationKey] = explode(",", $searchedAttributesDataSet[3]);
			}
		}

		if(!empty($searchedAttributesDataSet[4])){
			//if (preg_match('/[\d]+/', $searchedAttributesDataSet[4]) ){
				$searchedAttributesData['fees'] = explode(",", $searchedAttributesDataSet[4]);
			//}
		}

		if(!empty($searchedAttributesDataSet[5])){
			$courseLevelKey = 'level';
			if($dropDownSelectKeys) {
				$courseLevelKey = 'courseLevel';
			}
			if (preg_match($alphaStr, $searchedAttributesDataSet[5]) ){
				$searchedAttributesData[$courseLevelKey] = explode(",", $searchedAttributesDataSet[5]);
			}
		}

		if(!empty($searchedAttributesDataSet[6])){
			if (preg_match($alphaStr, $searchedAttributesDataSet[6]) ){
				$searchedAttributesData['mode'] = explode(",", $searchedAttributesDataSet[6]);
			}
		}

		return $searchedAttributesData;

	}

	public function getUrl(){
		$searchKeyword             = $this->searchKeyword;
		$pageNumber                = $this->pageNumber;
		$categoryId                = $this->categoryId;
		$subCategoryId             = $this->subCategoryId;
		$city                      = $this->city;
		$state                     = $this->state;
		$locality                  = $this->locality;
		$specialization            = $this->specialization;
		$fees                      = $this->fees;
		$exams                     = $this->exams;
		$courseLevel               = $this->courseLevel;
		$mode                      = $this->mode;
		$affiliation 			   = $this->affiliation;
		$classTimings 			   = $this->classTimings;
		$qerResults                = $this->qerResults;
	    $searchedAttributes        = $this->searchedAttributes;
	    $facilities        		   = $this->facilities;
	    $trackingSearchId		   = $this->trackingSearchId;	
	    $twoStepClosedSearch       = $this->twoStepClosedSearch;
	    $requestFrom 			   = $this->requestFrom;
	    $trackingFilterId 		   = $this->trackingFilterId;
	    $selectedLdbId			   = $this->selectedLdbId;
	    $oldKeyword 			   = $this->oldKeyword;
	    $relevantResults 		   = $this->relevantResults;
	    if(!empty($relevantResults)){
	    	global $searchReductionCriteria;
	    	$relevantResults = array_search($relevantResults,$searchReductionCriteria);
	    }

		$queryParams = array();
		$SEARCH_PARAMS_FIELDS_ALIAS = $this->CI->config->item('SEARCH_PARAMS_FIELDS_ALIAS');
		
		$this->prepareQueryParams($queryParams,$searchKeyword,$SEARCH_PARAMS_FIELDS_ALIAS['keyword']);

		$this->prepareQueryParams($queryParams,$pageNumber,$SEARCH_PARAMS_FIELDS_ALIAS['pageNumber']);

		$this->prepareQueryParams($queryParams,$categoryId,$SEARCH_PARAMS_FIELDS_ALIAS['categoryId']);

		$this->prepareQueryParams($queryParams,$subCategoryId,$SEARCH_PARAMS_FIELDS_ALIAS['subCategoryId']);

		$this->prepareQueryParams($queryParams,$fees,$SEARCH_PARAMS_FIELDS_ALIAS['fees']);

		$this->prepareQueryParams($queryParams,$qerResults,$SEARCH_PARAMS_FIELDS_ALIAS['qerResults']);

		$this->prepareQueryParams($queryParams,$searchedAttributes,$SEARCH_PARAMS_FIELDS_ALIAS['searchedAttributes']);

		$this->prepareQueryParams($queryParams,$trackingSearchId,$SEARCH_PARAMS_FIELDS_ALIAS['trackingSearchId']);

		$this->prepareQueryParams($queryParams,$twoStepClosedSearch,$SEARCH_PARAMS_FIELDS_ALIAS['twoStepClosedSearch']);

		$this->prepareQueryParams($queryParams,$requestFrom,$SEARCH_PARAMS_FIELDS_ALIAS['requestFrom']);

		$this->prepareQueryParams($queryParams,$trackingFilterId,$SEARCH_PARAMS_FIELDS_ALIAS['trackingFilterId']);

		$this->prepareQueryParams($queryParams,$city,$SEARCH_PARAMS_FIELDS_ALIAS['city']);

		$this->prepareQueryParams($queryParams,$state,$SEARCH_PARAMS_FIELDS_ALIAS['state']);
		
		$this->prepareQueryParams($queryParams,$locality,$SEARCH_PARAMS_FIELDS_ALIAS['locality']);
		
		$this->prepareQueryParams($queryParams,$specialization,$SEARCH_PARAMS_FIELDS_ALIAS['specialization']);
		
		$this->prepareQueryParams($queryParams,$exams,$SEARCH_PARAMS_FIELDS_ALIAS['exams']);
		
		$this->prepareQueryParams($queryParams,$courseLevel,$SEARCH_PARAMS_FIELDS_ALIAS['courseLevel']);
		
		$this->prepareQueryParams($queryParams,$mode,$SEARCH_PARAMS_FIELDS_ALIAS['mode']);
		
		$this->prepareQueryParams($queryParams,$facilities,$SEARCH_PARAMS_FIELDS_ALIAS['facilities']);

		$this->prepareQueryParams($queryParams,$affiliation,$SEARCH_PARAMS_FIELDS_ALIAS['affiliation']);

		$this->prepareQueryParams($queryParams,$classTimings,$SEARCH_PARAMS_FIELDS_ALIAS['classtimings']);

		$this->prepareQueryParams($queryParams,$selectedLdbId,$SEARCH_PARAMS_FIELDS_ALIAS['selectedLdbId']);

		$this->prepareQueryParams($queryParams,$oldKeyword,$SEARCH_PARAMS_FIELDS_ALIAS['oldKeyword']);

		$this->prepareQueryParams($queryParams,$relevantResults,$SEARCH_PARAMS_FIELDS_ALIAS['relevantResults']);

		if(isMobileRequest()){
			$this->prepareQueryParams($queryParams,1,'newSearch');
		}

		$queryParams = implode("&",$queryParams);
		if($queryParams != '')
            $suffix = '?'.$queryParams;

		$domainPrefix = SHIKSHA_HOME.SEARCH_PAGE_URL_PREFIX.'/';

		return $domainPrefix.$suffix;
	}

	private function prepareQueryParams(& $queryParams,$item,$key){
		if(!empty($item)){
			if(is_array($item)){
				foreach($item as $val){
					$queryParams[]=$key."[]=".urlencode($val);
				}
			}else{
				$queryParams[]=$key."=".urlencode($item);
			}
		}
	}

	public function getSearchKeyword(){
		return $this->searchKeyword;
	}

	public function getCategoryId(){
		return $this->categoryId;
	}

	public function getSubCategoryId(){
		return $this->subCategoryId;
	}

	/**
	 * function to set data in request
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-07-22
	 * @param  array      $data [description]
	 */
	public function setData($data = array()){
		foreach($data as $key => $val){
			// setting data in search url parser
			$setterName = "set".ucfirst($key);
			$getterName = "get".ucfirst($key);
			
			if(method_exists($this->searchUrlParserObj, $setterName)){
				$this->searchUrlParserObj->$setterName($val);

				//setting search page request variable
				$this->$key = $this->searchUrlParserObj->$getterName();
			}
		}
	}

	public function getQERFilters(){
		$qerFiltersData = array();
		$qerResults = $this->qerResults;

		$qerResultsDataSet = explode("|", $qerResults);

		if(!empty($qerResultsDataSet[0])){
			$qerFiltersData['q'] = $qerResultsDataSet[0];
		}

		if(!empty($qerResultsDataSet[1])){
			$qerFiltersData['city'] = explode(",", $qerResultsDataSet[1]);
		}

		if(!empty($qerResultsDataSet[2])){
			$qerFiltersData['state'] = explode(",", $qerResultsDataSet[2]);
		}

		if(!empty($qerResultsDataSet[3])){
			$qerFiltersData['locality'] = explode(",", $qerResultsDataSet[3]);
		}

		if(!empty($qerResultsDataSet[4])){
			$qerFiltersData['institute'] = explode(",", $qerResultsDataSet[4]);
		}

		if(!empty($qerResultsDataSet[5])){
			$qerFiltersData['course'] = explode(",", $qerResultsDataSet[5]);
		}

		if(!empty($qerResultsDataSet[6])){
			$qerFiltersData['level'] = explode(",", $qerResultsDataSet[6]);
		}

		if(!empty($qerResultsDataSet[7])){
			$qerFiltersData['mode'] = explode(",", $qerResultsDataSet[7]);
		}

		if(!empty($qerResultsDataSet[8])){
			$qerFiltersData['customQer'] = $qerResultsDataSet[8];
		}

		return $qerFiltersData;
		
	}

	public function getQERFiltersString(){
		 return $this->qerResults;
	}

	public function getSearchedAttributesString(){
		 return $this->searchedAttributes;
	}

	public function getCurrentPageNum() {
		if(empty($this->pageNumber))
			$this->pageNumber = 1;

		return $this->pageNumber;
	}

	public function setPageNumber($pagenum){
		$this->pageNumber = $pagenum;
	}

	public function getPageType(){
		if(empty($this->subCategoryId)){
			return 'open';
		}else{
			return 'close';
		}
	}

	public function getTrackingSearchId(){
		return $this->trackingSearchId;
	}

	public function setTwoStepClosedSearch($value){
		$this->twoStepClosedSearch = $value;
	}

	public function getTwoStepClosedSearch(){
		return $this->twoStepClosedSearch;
	}

	public function getRequestFrom(){
		return $this->requestFrom;
	}

	public function getTrackingFilterId(){
		return $this->trackingFilterId;
	}

	public function setTrackingFilterId($value){
		$this->trackingFilterId = $value;
	}

	public function getSelectedLdbId() {
		return $this->selectedLdbId;
	}

	public function getOldKeyword(){
		return $this->oldKeyword;
	}

	public function setOldKeyword($keyword){
		$this->oldKeyword = $keyword;
	}

	public function getRelevantResults(){
		return $this->relevantResults;
	}

	public function setRelevantResults($val){
		$this->relevantResults = $val;
	}

	public function getInstitutesFlag() {
		if($this->requestFrom == 'filterBucket') {
			return 0;
		} else {
			return 1;
		}
	}

	public function getFiltersFlag() {
		if($this->CI->input->getRawRequestVariable('isLazyLoad')) {
			return 0;
		} else {
			return 1;
		}
	}

	public function getParentFiltersFlag() {
		$filtersFlag = $this->getFiltersFlag();
		if(!empty($this->subCategoryId) && isMobileRequest() && $filtersFlag) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
    * Purpose       : Process and return pre selected filters entered by a user
    * Params        : search page request
    * Author        : Ankit Garg
    * date          : 2015-07-29
    */
	public function processPreSelectedSearchedFiters() {
		$appliedFilters = $this->getSearchedAttributes(true);
		$subCategoryId = $this->getSubCategoryId();
		global $subcatToAdvancedFiltersMapping;
		$orderedFilters = $subcatToAdvancedFiltersMapping[$subCategoryId];
		$searchFilterData = array();
		foreach($orderedFilters as $key => $orderName) {
			if(!empty($appliedFilters[$orderName])) {
				$searchFilterData['dropDownFilters'][$key] = $appliedFilters[$orderName];
			}
		}

		$cityFilters 	= $appliedFilters['city'];
		array_walk($cityFilters, function(&$val, $key) { $val = 'city_'.$val; });
		$stateFilters 	= $appliedFilters['state'];
		array_walk($stateFilters, function(&$val, $key) { $val = 'state_'.$val; });

		if(empty($cityFilters)) {
			$cityFilters = array();
		}
		if(empty($stateFilters)) {
			$stateFilters = array();
		}
		$searchFilterData['locations'] 			= array_merge($cityFilters, $stateFilters);
		$searchKeyword 							= $this->getOldKeyword();
		if(empty($searchKeyword)){
			$searchKeyword = $this->getSearchKeyword();
		}
		$searchFilterData['keyword'] 			= (!empty($searchKeyword)) ? base64_encode($searchKeyword) : '';
		
		if($this->getSelectedLdbId() != '') {
			$searchFilterData['subCategoryId'] = $this->getSelectedLdbId();
		}
		else if(!empty($subCategoryId)) {
			$searchFilterData['subCategoryId'] = "s-".$subCategoryId;
		}
		else {
			$searchFilterData['subCategoryId'] = "s-0";
		}
		return $searchFilterData;
	}
}
