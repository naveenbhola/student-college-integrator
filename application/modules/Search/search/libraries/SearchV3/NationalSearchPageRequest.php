<?php

class NationalSearchPageRequest{

	public $CI;
	private $searchKeyword; 
	private $pageNumber;     
	
	private $stream;
	private $substream;
	private $specialization;
	private $baseCourse;
	private $educationType;
	private $deliveryMethod;
	private $credential;
	private $levelCredential;
	private $city;           
	private $state;          
	private $locality;
	private $affiliation;                  
	private $exams;     
	private $fees;     
	private $popularGroup;
	private $certificateProvider;
	private $courseLevel;
	private $approvals;
	private $grants;
	private $collegeOwnership;
	private $accreditation;
	private $offeredByCollege;
	public  $appliedFilters = array();
	private $pageType;
	private $qerResults;
	private $searchedAttributes;
	private $requestFrom;
	private $trackingSearchId;
	private $trackingFilterId;
	private $oldKeyword;
	private $relevantResults;
	private $courseStatus;
	private $isStreamClosedSearch;
	private $isSubStreamClosedSearch;
	private $isBaseCourseClosedSearch;
	private $courseType;
	private $page_limit = 30;
	private $page_limit_mobile = 5;
	private $whichPage = "search";
	private $allCoursesURLWithoutPage = "";
	private $allCoursePageCanonicalUrl = "";
	private $typedKeyword;
	private $isTrending;
	private $isInstituteMultiple;
	private $isInterim;
	
	private $twoStepClosedSearch;	
    
	public function	__construct($arr = array(), $selectedFilter = null){
		$selectedFilter = $arr['selectedFilterFromAllCourses'];
		unset($arr['selectedFilterFromAllCourses']);
		$this->CI = & get_instance();
		$this->CI->load->helper('security');
		$this->CI->load->library("search/SearchV3/NationalSearchPageUrlParser");
		$this->CI->load->config("nationalCategoryList/nationalConfig");
		$this->updatePageFlag();
		$this->searchUrlParserObj = new NationalSearchPageUrlParser();
		$this->selectedFilter = $selectedFilter;
		if(empty($arr)) {
			$this->setDataFromUrlParams($selectedFilter);
		}
		$this->field_alias = $this->CI->config->item('FIELD_ALIAS');
		
	}

	function updatePageFlag(){
		$current_url = getCurrentPageURL();
		if(strpos($current_url, SEARCH_PAGE_URL_PREFIX) === false){
			$this->whichPage = "allcourses";
		}
	}

	private function setDataFromUrlParams($selectedFilter){
		$this->searchUrlParserObj->parseSearchUrl($selectedFilter);
		
		$this->searchKeyword            =	$this->searchUrlParserObj->getSearchKeyword();
		$this->pageNumber               =	$this->searchUrlParserObj->getPageNumber();
		$this->stream                   =	$this->searchUrlParserObj->getStream();
		$this->substream                =	$this->searchUrlParserObj->getSubstream();
		$this->specialization           =	$this->searchUrlParserObj->getSpecialization();
		$this->baseCourse               =	$this->searchUrlParserObj->getBaseCourse();
		$this->city                     =	$this->searchUrlParserObj->getCity();
		$this->state                    =	$this->searchUrlParserObj->getState();
		$this->locality                 =	$this->searchUrlParserObj->getLocality();
		$this->educationType            =	$this->searchUrlParserObj->getEducationType();
		$this->deliveryMethod           =	$this->searchUrlParserObj->getDeliveryMethod();
		$this->credential               =	$this->searchUrlParserObj->getCredential();
		$this->levelCredential          =	$this->searchUrlParserObj->getLevelCredential();
		$this->affiliation              =	$this->searchUrlParserObj->getAffiliation();
		$this->exams                    =	$this->searchUrlParserObj->getExams();
		$this->fees                     =	$this->searchUrlParserObj->getFees();
		$this->popularGroup             =	$this->searchUrlParserObj->getPopularGroup();
		$this->certificateProvider      =	$this->searchUrlParserObj->getCertificateProvider();
		$this->courseLevel              =	$this->searchUrlParserObj->getCourseLevel();
		$this->approvals                =	$this->searchUrlParserObj->getApprovals();
		$this->grants                   =	$this->searchUrlParserObj->getGrants();
		$this->collegeOwnership         =	$this->searchUrlParserObj->getCollegeOwnership();
		$this->accreditation            =	$this->searchUrlParserObj->getAccreditation();
		$this->offeredByCollege         =	$this->searchUrlParserObj->getOfferedByCollege();
		$this->qerResults               =	$this->searchUrlParserObj->getQerResults();
		$this->searchedAttributes       =	$this->searchUrlParserObj->getSearchedAttributes();
		$this->requestFrom              =	$this->searchUrlParserObj->getRequestFrom();
		$this->trackingSearchId         =	$this->searchUrlParserObj->getTrackingSearchId();
		$this->trackingFilterId         =	$this->searchUrlParserObj->getTrackingFilterId();
		$this->oldKeyword               =	$this->searchUrlParserObj->getOldKeyword();
		$this->relevantResults          =	$this->searchUrlParserObj->getRelevantResults();
		$this->courseStatus             =	$this->searchUrlParserObj->getCourseStatus();
		$this->twoStepClosedSearch      =	$this->searchUrlParserObj->getTwoStepClosedSearch();
		$this->singleStreamClosedSearch =	$this->searchUrlParserObj->getSingleStreamClosedSearch();
		$this->additionalFacetsToFetch  =	$this->searchUrlParserObj->getAdditionalFacetsToFetch();
		$this->courseType               =	$this->searchUrlParserObj->getCourseType();
		$this->typedKeyword             =	$this->searchUrlParserObj->getTypedKeyword();
		$this->isTrending               =	$this->searchUrlParserObj->getIsTrending();
		$this->isInstituteMultiple      =	$this->searchUrlParserObj->getIsInstituteMultiple();
		$this->isInterim 				=	$this->searchUrlParserObj->getIsInterim();
		if($this->whichPage == "allcourses"){
			$current_url = getCurrentPageURLWithoutQueryParams();
			$urlArray = explode("-", $current_url);
			if(is_numeric($urlArray[count($urlArray)-1])){
				$this->pageNumber = $urlArray[count($urlArray)-1];
				unset($urlArray[count($urlArray)-1]);
			}else{
				$this->pageNumber = 1;
			}
			$this->allCoursesURLWithoutPage = implode("-", $urlArray);
		}
		$this->setPageNumber($this->pageNumber);
	}

	public function getAppliedFilters(){
		$this->appliedFilters['stream'] 				= $this->CI->input->get($this->field_alias['stream'],true);
		$this->appliedFilters['substream'] 				= $this->CI->input->get($this->field_alias['substream'],true);
		$this->appliedFilters['specialization'] 		= $this->CI->input->get($this->field_alias['specialization'],true);
		$this->appliedFilters['sub_spec']               = $this->CI->input->get($this->field_alias['sub_spec'],true);
		$this->appliedFilters['base_course'] 			= $this->CI->input->get($this->field_alias['base_course'],true);
		$this->appliedFilters['education_type'] 		= $this->CI->input->get($this->field_alias['education_type'],true);
		$this->appliedFilters['delivery_method'] 		= $this->CI->input->get($this->field_alias['delivery_method'],true);
		$this->appliedFilters['et_dm']                  = $this->CI->input->get($this->field_alias['et_dm'],true);
		$this->appliedFilters['credential'] 			= $this->CI->input->get($this->field_alias['credential'],true);
		$this->appliedFilters['level_credential']		= $this->CI->input->get($this->field_alias['level_credential'],true);
		$this->appliedFilters['exam'] 					= $this->CI->input->get($this->field_alias['exam'],true);
		$this->appliedFilters['locality'] 				= $this->CI->input->get($this->field_alias['locality'],true);
        $this->appliedFilters['state']                  = $this->CI->input->get($this->field_alias['state'],true);
        $this->appliedFilters['city']                   = $this->CI->input->get($this->field_alias['city'],true);
		$this->appliedFilters['fees'] 					= $this->CI->input->get($this->field_alias['fees'],true);
		$this->appliedFilters['popular_group'] 			= $this->CI->input->get($this->field_alias['popular_group'],true);
		$this->appliedFilters['certificate_provider']	= $this->CI->input->get($this->field_alias['certificate_provider'],true);
		$this->appliedFilters['course_level']			= $this->CI->input->get($this->field_alias['course_level'],true);
		$this->appliedFilters['approvals']				= $this->CI->input->get($this->field_alias['approvals'],true);
		$this->appliedFilters['grants']					= $this->CI->input->get($this->field_alias['grants'],true);
		$this->appliedFilters['facilities']				= $this->CI->input->get($this->field_alias['facilities'],true);
		$this->appliedFilters['college_ownership']		= $this->CI->input->get($this->field_alias['college_ownership'],true);
		$this->appliedFilters['accreditation']			= $this->CI->input->get($this->field_alias['accreditation'],true);
		$this->appliedFilters['offered_by_college']		= $this->CI->input->get($this->field_alias['offered_by_college'],true);
		$this->appliedFilters['course_status']			= $this->CI->input->get($this->field_alias['course_status'],true);
		$this->appliedFilters['course_type']			= $this->CI->input->get($this->field_alias['course_type'],true);
		$userAppliedFilters								= $this->CI->input->get('uaf',true);

		if(!empty($this->selectedFilter['stream']) && !in_array('stream', $userAppliedFilters)) {
			if(empty($this->appliedFilters['stream'])) {
				$this->appliedFilters['stream'] = array();
			}
			$this->appliedFilters['stream']	= array_merge($this->appliedFilters['stream'], $this->selectedFilter['stream']);
		}
		if(!empty($this->selectedFilter['base_course']) && !in_array('base_course', $userAppliedFilters)) {
			if(empty($this->appliedFilters['base_course'])) {
				$this->appliedFilters['base_course'] = array();
			}
			$this->appliedFilters['base_course']	= array_merge($this->appliedFilters['base_course'], $this->selectedFilter['base_course']);
		}
		$this->cleanGetParams();
		return $this->appliedFilters;
	}

	public function cleanGetParams() {
        foreach ($this->appliedFilters as $key1 => $value) {
            if(is_array($value) && !empty($value)) {
                foreach ($value as $key2 => $string) {
                    if(whiteListGetParams($string)) {
                        show_404();
                    }
                }
            } else {
                if(!empty($value)) {
                    if(whiteListGetParams($value)) {
                        show_404();
                    }
                }
            }
        }
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
		$stream = $this->getStream();
		if(!empty($stream)) {
			$originalFilters['userAppliedFilters']['stream'][] = $stream;
		}
		return $originalFilters;
	}

	/*
	city | state | exam |  base_course | specialization | substream | stream | popular_group | certificate_provider | education_type | delivery_method | course_type | credential | fees
	*/
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
			if (preg_match($re, $searchedAttributesDataSet[2]) ){
				$searchedAttributesData['exam'] = explode(",", $searchedAttributesDataSet[2]);
			}
		}

		if(!empty($searchedAttributesDataSet[3])){
			if (preg_match($re, $searchedAttributesDataSet[3]) ){
				$searchedAttributesData['base_course'] = explode(",", $searchedAttributesDataSet[3]);
			}
		}

		if(!empty($searchedAttributesDataSet[4])){
			if (preg_match($re, $searchedAttributesDataSet[4]) ){
				$searchedAttributesData['specialization'] = explode(",", $searchedAttributesDataSet[4]);
			}
		}

		if(!empty($searchedAttributesDataSet[5])){
			if (preg_match($re, $searchedAttributesDataSet[5]) ){
				$searchedAttributesData['substream'] = explode(",", $searchedAttributesDataSet[5]);
			}
		}

		if(!empty($searchedAttributesDataSet[6])){
			if (preg_match($re, $searchedAttributesDataSet[6]) ){
				$searchedAttributesData['stream'] = explode(",", $searchedAttributesDataSet[6]);
			}
		}

		if(!empty($searchedAttributesDataSet[7])){
			if (preg_match($re, $searchedAttributesDataSet[7]) ){
				$searchedAttributesData['popular_group'] = explode(",", $searchedAttributesDataSet[7]);
			}
		}

		if(!empty($searchedAttributesDataSet[8])){
			if (preg_match($re, $searchedAttributesDataSet[8]) ){
				$searchedAttributesData['certificate_provider'] = explode(",", $searchedAttributesDataSet[8]);
			}
		}

		if(!empty($searchedAttributesDataSet[9])){
			if (preg_match($re, $searchedAttributesDataSet[9]) ){
				$searchedAttributesData['education_type'] = explode(",", $searchedAttributesDataSet[9]);
			}
		}

		if(!empty($searchedAttributesDataSet[10])){
			if (preg_match($re, $searchedAttributesDataSet[10]) ){
				$searchedAttributesData['delivery_method'] = explode(",", $searchedAttributesDataSet[10]);
			}
		}

		if(!empty($searchedAttributesDataSet[11])){
			if (preg_match($re, $searchedAttributesDataSet[11]) ){
				$searchedAttributesData['course_type'] = explode(",", $searchedAttributesDataSet[11]);
			}
		}

		if(!empty($searchedAttributesDataSet[12])){
			if (preg_match($re, $searchedAttributesDataSet[12]) ){
				$searchedAttributesData['credential'] = explode(",", $searchedAttributesDataSet[12]);
			}
		}

		if(!empty($searchedAttributesDataSet[13])){
			if (preg_match($alphaStr, $searchedAttributesDataSet[13]) ){
				$searchedAttributesData['fees'] = explode(",", $searchedAttributesDataSet[13]);
			}
		}

		if(!empty($searchedAttributesDataSet[14])){
			if (preg_match($alphaStr, $searchedAttributesDataSet[14]) ){
				$searchedAttributesData['autosuggestData'] = explode(",", $searchedAttributesDataSet[14]);
			}
		}


		return $searchedAttributesData;

	}

	public function getUrl(){

		$searchKeyword       = $this->searchKeyword;
		$pageNumber          = $this->pageNumber;
		$stream              = $this->stream;
		$substream           = $this->substream;
		$specialization      = $this->specialization;
		$baseCourse          = $this->baseCourse;
		$educationType       = $this->educationType;
		$deliveryMethod      = $this->deliveryMethod;
		$credential          = $this->credential;
		$levelCredential     = $this->levelCredential;
		$city                = $this->city;
		$state               = $this->state;
		$locality            = $this->locality;
		$affiliation         = $this->affiliation;
		$courseType         = $this->courseType;
		$exams               = $this->exams;
		$fees                = $this->fees;
		$popularGroup        = $this->popularGroup;
		$certificateProvider = $this->certificateProvider;
		$courseLevel         = $this->courseLevel;
		$approvals           = $this->approvals;
		$grants              = $this->grants;
		$collegeOwnership    = $this->collegeOwnership;
		$accreditation       = $this->accreditation;
		$offeredByCollege    = $this->offeredByCollege;
		$qerResults          = $this->qerResults;
		$searchedAttributes  = $this->searchedAttributes;
		$requestFrom         = $this->requestFrom;
		$oldKeyword          = $this->oldKeyword;
		$relevantResults     = $this->relevantResults;
		$courseStatus        = $this->courseStatus;
		$twoStepClosedSearch = $this->twoStepClosedSearch;
		$trackingSearchId    = $this->trackingSearchId;
		$trackingFilterId    = $this->trackingFilterId;
		$singleStreamClosedSearch    = $this->singleStreamClosedSearch;
		$typedKeyword   	 = $this->typedKeyword;
		$isTrending    		 = $this->isTrending;
		$isInstituteMultiple = $this->isInstituteMultiple;
		$isInterim 			 = $this->isInterim;

	    if(!empty($relevantResults)){
	    	global $searchReductionCriteria;
	    	$relevantResults = array_search($relevantResults,$searchReductionCriteria);
	    }

		$queryParams = array();
		$SEARCH_PARAMS_FIELDS_ALIAS = $this->CI->config->item('FIELD_ALIAS');
		
		$this->prepareQueryParams($queryParams,$searchKeyword,$SEARCH_PARAMS_FIELDS_ALIAS['keyword']);
		$this->prepareQueryParams($queryParams,$stream,$SEARCH_PARAMS_FIELDS_ALIAS['stream']);
		$this->prepareQueryParams($queryParams,$specialization,$SEARCH_PARAMS_FIELDS_ALIAS['specialization']);
		$this->prepareQueryParams($queryParams,$substream,$SEARCH_PARAMS_FIELDS_ALIAS['substream']);
		$this->prepareQueryParams($queryParams,$baseCourse,$SEARCH_PARAMS_FIELDS_ALIAS['base_course']);
		$this->prepareQueryParams($queryParams,$educationType,$SEARCH_PARAMS_FIELDS_ALIAS['education_type']);
		$this->prepareQueryParams($queryParams,$deliveryMethod,$SEARCH_PARAMS_FIELDS_ALIAS['delivery_method']);
		$this->prepareQueryParams($queryParams,$qerResults,$SEARCH_PARAMS_FIELDS_ALIAS['qerResults']);
		$this->prepareQueryParams($queryParams,$searchedAttributes,$SEARCH_PARAMS_FIELDS_ALIAS['searchedAttributes']);
		$this->prepareQueryParams($queryParams,$trackingSearchId,$SEARCH_PARAMS_FIELDS_ALIAS['trackingSearchId']);
		$this->prepareQueryParams($queryParams,$trackingFilterId,$SEARCH_PARAMS_FIELDS_ALIAS['trackingFilterId']);
		$this->prepareQueryParams($queryParams,$requestFrom,$SEARCH_PARAMS_FIELDS_ALIAS['requestFrom']);
		$this->prepareQueryParams($queryParams,$city,$SEARCH_PARAMS_FIELDS_ALIAS['city']);
		$this->prepareQueryParams($queryParams,$state,$SEARCH_PARAMS_FIELDS_ALIAS['state']);
		$this->prepareQueryParams($queryParams,$locality,$SEARCH_PARAMS_FIELDS_ALIAS['locality']);	
		$this->prepareQueryParams($queryParams,$exams,$SEARCH_PARAMS_FIELDS_ALIAS['exam']);
		$this->prepareQueryParams($queryParams,$credential,$SEARCH_PARAMS_FIELDS_ALIAS['credential']);
		$this->prepareQueryParams($queryParams,$levelCredential,$SEARCH_PARAMS_FIELDS_ALIAS['level_credential']);
		$this->prepareQueryParams($queryParams,$affiliation,$SEARCH_PARAMS_FIELDS_ALIAS['affiliation']);
		$this->prepareQueryParams($queryParams,$pageNumber,$SEARCH_PARAMS_FIELDS_ALIAS['pageNumber']);
		$this->prepareQueryParams($queryParams,$fees,$SEARCH_PARAMS_FIELDS_ALIAS['fees']);
		$this->prepareQueryParams($queryParams,$oldKeyword,$SEARCH_PARAMS_FIELDS_ALIAS['oldKeyword']);
		$this->prepareQueryParams($queryParams,$relevantResults,$SEARCH_PARAMS_FIELDS_ALIAS['relevantResults']);
		$this->prepareQueryParams($queryParams,$popularGroup,$SEARCH_PARAMS_FIELDS_ALIAS['popular_group']);
		$this->prepareQueryParams($queryParams,$certificateProvider,$SEARCH_PARAMS_FIELDS_ALIAS['certificate_provider']);
		$this->prepareQueryParams($queryParams,$courseLevel,$SEARCH_PARAMS_FIELDS_ALIAS['course_level']);
		$this->prepareQueryParams($queryParams,$approvals,$SEARCH_PARAMS_FIELDS_ALIAS['approvals']);
		$this->prepareQueryParams($queryParams,$grants,$SEARCH_PARAMS_FIELDS_ALIAS['grants']);
		$this->prepareQueryParams($queryParams,$collegeOwnership,$SEARCH_PARAMS_FIELDS_ALIAS['college_ownership']);
		$this->prepareQueryParams($queryParams,$accreditation,$SEARCH_PARAMS_FIELDS_ALIAS['accreditation']);
		$this->prepareQueryParams($queryParams,$offeredByCollege,$SEARCH_PARAMS_FIELDS_ALIAS['offered_by_college']);
		$this->prepareQueryParams($queryParams,$courseStatus,$SEARCH_PARAMS_FIELDS_ALIAS['course_status']);
		$this->prepareQueryParams($queryParams,$twoStepClosedSearch,$SEARCH_PARAMS_FIELDS_ALIAS['twoStepClosedSearch']);
		$this->prepareQueryParams($queryParams,$singleStreamClosedSearch,$SEARCH_PARAMS_FIELDS_ALIAS['singleStreamClosedSearch']);
		$this->prepareQueryParams($queryParams,$courseType,$SEARCH_PARAMS_FIELDS_ALIAS['course_type']);
		$this->prepareQueryParams($queryParams,$typedKeyword,$SEARCH_PARAMS_FIELDS_ALIAS['typedKeyword']);
		$this->prepareQueryParams($queryParams,$isTrending,$SEARCH_PARAMS_FIELDS_ALIAS['isTrending']);
		$this->prepareQueryParams($queryParams,$isInstituteMultiple,$SEARCH_PARAMS_FIELDS_ALIAS['isInstituteMultiple']);
		$this->prepareQueryParams($queryParams,$isInterim,$SEARCH_PARAMS_FIELDS_ALIAS['isInterim']);
		
		// if(isMobileRequest()){
		// 	$this->prepareQueryParams($queryParams,1,'newSearch');
		// }

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

	// public function getCategoryId(){
	// 	return $this->categoryId;
	// }

	// public function getSubCategoryId(){
	// 	return $this->subCategoryId;
	// }

	/**
	 * function to set data in request
	 * @author Ankit Bansal
	 * @date   2016-11-24
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

	/**
	* q | city | state | locality | institute | base_course | specialization | substream | stream | popular_group | certificate_provider | education_type | delivery_method | course_type | credential
	*/
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
			$qerFiltersData['base_course'] = explode(",", $qerResultsDataSet[5]);
		}

		if(!empty($qerResultsDataSet[6])){
			$qerFiltersData['specialization'] = explode(",", $qerResultsDataSet[6]);
		}

		if(!empty($qerResultsDataSet[7])){
			$qerFiltersData['substream'] = explode(",", $qerResultsDataSet[7]);
		}

		if(!empty($qerResultsDataSet[8])){
			$qerFiltersData['stream'] = explode(",", $qerResultsDataSet[8]);
		}

		if(!empty($qerResultsDataSet[9])){
			$qerFiltersData['popular_group'] = explode(",", $qerResultsDataSet[9]);
		}

		if(!empty($qerResultsDataSet[10])){
			$qerFiltersData['certificate_provider'] = explode(",", $qerResultsDataSet[10]);
		}
		if(!empty($qerResultsDataSet[11])){
			$qerFiltersData['education_type'] = explode(",", $qerResultsDataSet[11]);
		}

		if(!empty($qerResultsDataSet[12])){
			$qerFiltersData['delivery_method'] = explode(",", $qerResultsDataSet[12]);
		}

		if(!empty($qerResultsDataSet[13])){
			$qerFiltersData['course_type'] = explode(",", $qerResultsDataSet[13]);
		}

		if(!empty($qerResultsDataSet[14])){
			$qerFiltersData['credential'] = explode(",", $qerResultsDataSet[14]);
		}


		return $qerFiltersData;
		
	}

	public function getQERFiltersString(){
		 return $this->qerResults;
	}

	public function getSearchedAttributesString(){
		 return $this->searchedAttributes;
	}
	
	public function getCurrentUrlWithoutPageNo(){
		return $this->allCoursesURLWithoutPage;
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

	public function setTrackingSearchId($value){
		$this->trackingSearchId = $value;
	}

	public function getTypedKeyword(){
		return $this->typedKeyword;
	}

	public function setTypedKeyword($value){
		$this->typedKeyword = $value;
	}

	public function getIsTrending(){
		return $this->isTrending;
	}

	public function setIsTrending($value){
		$this->isTrending = $value;
	}

	public function getIsInstituteMultiple(){
		return $this->isInstituteMultiple;
	}

	public function setIsInstituteMultiple($value){
		$this->isInstituteMultiple = $value;
	}

	public function getIsInterim(){
		return $this->isInterim;
	}

	public function setIsInterim($value){
		$this->isInterim = $value;
	}

	public function setTwoStepClosedSearch($value){
		$this->twoStepClosedSearch = $value;
	}

	public function getTwoStepClosedSearch(){
		return $this->twoStepClosedSearch;
	}

	public function setSingleStreamClosedSearch($value){
		$this->singleStreamClosedSearch = $value;
	}

	public function getSingleStreamClosedSearch(){
		return $this->singleStreamClosedSearch;
	}

	public function setAdditionalFacetsToFetch($value){
		$this->additionalFacetsToFetch = $value;
	}

	public function getAdditionalFacetsToFetch(){
		return $this->additionalFacetsToFetch;
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

	// public function getSelectedLdbId() {
	// 	return $this->selectedLdbId;
	// }

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
		if(isMobileRequest()&& $filtersFlag) {
			return 1;
		} else {
			return 0;
		}
		return 0;
	}

	/**
    * Purpose       : Process and return pre selected filters entered by a user
    * Params        : search page request
    * Author        : Ankit Bansal
    * date          : 2016-11-24
    */
	public function processPreSelectedSearchedFiters() {
		$appliedFilters = $this->getSearchedAttributes(true);
		$autosuggestData = 0;
		$entityId = 0;
		$entityType = "";
		$fieldAlias = $this->CI->config->item('FIELD_ALIAS');
		$fieldAliasReverse = array_flip($fieldAlias);
		if(!empty($appliedFilters['autosuggestData'])){
			$autosuggestData = reset($appliedFilters['autosuggestData']);
			$autosuggestData = str_replace(":", "_", $autosuggestData);
			
			list($entityType,$entityId) = explode("_", $autosuggestData);
			if(!is_numeric($entityId) || !ctype_alpha($entityType)) {
				return;
			}
			$entityType = $fieldAliasReverse[$entityType];
		}
		
		$advancedFiltersList = $this->CI->config->item('FACETS_ADVANCED_FILTER_MAPPING');
		$field_alias = $this->CI->config->item('FIELD_ALIAS');
		
		

		$orderedFilters = $advancedFiltersList[$entityType][$entityId];
        if(empty($orderedFilters)) {
            if(!empty($entityType)) {
                $orderedFilters = $advancedFiltersList[$entityType]['default'];
            } else {
                $orderedFilters = $advancedFiltersList['default'];
            }
        }


        $combinedFilters = $this->CI->config->item('COMBINED_FILTERS');

		$searchFilterData = array();
		foreach($orderedFilters as $key => $orderName) {

			if(!empty($appliedFilters[$orderName]) || array_key_exists($orderName, $combinedFilters)) {

				if(array_key_exists($orderName, $combinedFilters)){
					$localFilters = $combinedFilters[$orderName];
					foreach ($localFilters as $localFilterValue) {
						foreach ($appliedFilters[$localFilterValue] as $appliedFiltersValue) {
							$searchFilterData['dropDownFilters'][$key][] = $field_alias[$localFilterValue]."::".$appliedFiltersValue;
						}						
					}
				}else{

					$searchFilterData['dropDownFilters'][$key] = $appliedFilters[$orderName];	
				}
				
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
		$searchFilterData['words_achieved_id'] 	= $autosuggestData;
		$searchFilterData['entityId'] 			= $entityId;
		
		return $searchFilterData;
	}
	
	/*public function setIsStreamClosedSearch($isStreamClosedSearch){
		$this->isStreamClosedSearch = $isStreamClosedSearch;
	}

	public function getIsStreamClosedSearch(){
		return $this->isStreamClosedSearch;
	}

	public function setIsSubStreamClosedSearch($isSubStreamClosedSearch){
		$this->isSubStreamClosedSearch = $isSubStreamClosedSearch;
	}

	public function getIsSubStreamClosedSearch(){
		return $this->isSubStreamClosedSearch;
	}

	public function setIsBaseCourseClosedSearch($isBaseCourseClosedSearch){
		$this->isBaseCourseClosedSearch = $isBaseCourseClosedSearch;
	}
	public function getIsBaseCourseClosedSearch(){
		return $this->isBaseCourseClosedSearch;
	}*/

	public function isStreamClosedSearch(){
		if(!empty($this->stream) && is_array($this->stream) && count($this->stream) == 1){
			return true;
		}
		return false;
	}

	public function isSubStreamClosedSearch(){

		if(!$this->isStreamClosedSearch() && !empty($this->substream) && is_array($this->substream) && count($this->substream) == 1){
			return true;
		}
		return false;
	}

	public function isBaseCourseClosedSearch(){
		if(!$this->isStreamClosedSearch() &&  !$this->isSubStreamClosedSearch() && !empty($this->baseCourse) && is_array($this->baseCourse) && count($this->baseCourse) == 1){
			return true;
		}
		return false;
	}

	public function isClosedSearch() {
		return ($this->isStreamClosedSearch() || $this->isSubStreamClosedSearch() || $this->isBaseCourseClosedSearch());
	}

	public function isAutosuggestorClosedSearch() {
		return ($this->isStreamClosedSearch() || $this->isSubStreamClosedSearch() || $this->isBaseCourseClosedSearch() || $this->isSpecializationClosedSearch() || $this->isCertificateProvClosedSearch() || $this->isPopularGroupClosedSearch());
	}

	public function isSpecializationClosedSearch() {
		if(!empty($this->specialization) && is_array($this->specialization) && count($this->specialization) == 1){
			return true;
		}
		return false;
	}

	public function isCertificateProvClosedSearch() {
		if(!empty($this->certificateProvider) && is_array($this->certificateProvider) && count($this->certificateProvider) == 1){
			return true;
		}
		return false;
	}
	
	public function isPopularGroupClosedSearch() {
		if(!empty($this->popularGroup) && is_array($this->popularGroup) && count($this->popularGroup) == 1){
			return true;
		}
		return false;
	}

	public function getStream(){
		return $this->stream;
	}

	public function getSubstream(){
		return $this->substream;
	}

	public function getBaseCourse(){
		return $this->baseCourse;
	}

	public function getSpecialization(){
		return $this->specialization;
	}

	function getPageLimit() {
		if(isMobileRequest()){
			return $this->page_limit_mobile;
		}else{
			return $this->page_limit;	
		}
        
    }

    function getPageNumber() {
        return $this->pageNumber;
    }

    function getCourseStatus(){
    	return $this->courseStatus;
    }

    function setCourseStatus($courseStatus){
    	$this->courseStatus = $courseStatus;
    }

    function getCity(){
    	return $this->city;
    }

    function getState(){
    	return $this->state;
    }

    function getEducationType(){
    	return $this->educationType;
    }

    function getDeliveryMethod(){
    	return $this->deliveryMethod;
    }

    function getCredential(){
    	return $this->credential;
    }

    function getExam(){
    	return $this->exams;
    }

    function getObjectAsArray() {
        return get_object_vars($this);
    }

    function getWhichPage(){
    	return $this->whichPage;
    }

    function getUrlForPagination($pageNumber) {
    	if($this->whichPage == "search"){
    		if(empty($pageNumber)) {
	            $pageNumber = 1;
	        }
    		$this->setPageNumber($pageNumber);
    		$url = $this->getUrl();
    		return $url;		
    	}
    	
        if(empty($pageNumber)) {
            $pageNumber = 1;
        }
        //Remove 'pn=' ===> break by 'pn='
        $url_with_query_params = SHIKSHA_HOME.$this->CI->input->server('REQUEST_URI', true);
        
        $urlArray     = explode($this->field_alias['pageNumber'].'=', $url_with_query_params);
        
        //take the part before 'pn='
        $urlFirstHalf = $urlArray[0];

        if($this->whichPage == "allcourses"){
	        $urlFirstHalfArray = explode("?", $urlFirstHalf);
	        if($pageNumber != "1"){
	            $urlFirstHalfArray[0] = $this->allCoursesURLWithoutPage."-".$pageNumber;
	        }else{
	            $urlFirstHalfArray[0] = $this->allCoursesURLWithoutPage ;
	        }

	        $urlFirstHalf = implode("?", $urlFirstHalfArray);
        }
        
        //take the part after 'pn='
        //take position of first '&' and remove the part before it(which will be the page number in URL)
        $posOfFirstAmp = strpos($urlArray[1], '&');
        if(empty($posOfFirstAmp)) {
            $urlLastHalf  = '';
        } else {
            $urlLastHalf  = substr($urlArray[1], $posOfFirstAmp);
        }
        
        $pageQueryParam = $this->field_alias['pageNumber']."=".$pageNumber;
        
        $posTempQues = strpos($urlFirstHalf, '?');
        if(!empty($posTempQues)) {
            if($posTempQues == (strlen($urlFirstHalf) - 1)) { //? is last char of the string
                $urlWithPagination = $urlFirstHalf.$pageQueryParam.$urlLastHalf;
            } else {
                $posTempAmp = strpos($urlFirstHalf, '&');
                if($posTempAmp == (strlen($urlFirstHalf) - 1)) { //& is last char of the string
                    $urlWithPagination = $urlFirstHalf.$pageQueryParam.$urlLastHalf;
                } else {
                    $urlWithPagination = $urlFirstHalf.'&'.$pageQueryParam.$urlLastHalf;
                }
            }
        } else {
            $urlWithPagination = $urlFirstHalf.'?'.$pageQueryParam.$urlLastHalf;
        }

        if(DO_SEARCHPAGE_TRACKING){
            $trackingSearchId = $this->getTrackingSearchId();
            if(!empty($trackingSearchId)){
                $params[$this->field_alias['trackingSearchId']] = $trackingSearchId;
                $trackingFilterId = $this->getTrackingFilterId();
                if(!empty($trackingFilterId)){
                    $params[$this->field_alias['trackingFilterId']] = $trackingFilterId;
                }
                $urlWithPagination = add_query_params($urlWithPagination,$params);
            }
        }

        return $urlWithPagination;
    }

    function getDefaultSearchPageFilters(){
    	//all those filters where keyword can be identified by QER
    	$qerFilters = $this->getQERFilters();
    	$temp = array('stream','substream','specialization','city','state','locality','institute','base_course','popular_group','certificate_provider','education_type','delivery_method','course_type','credential');
    	$returnArr = array();
    	foreach ($temp as $key) {
    		if(!empty($qerFilters[$key])){
    			$returnArr[$key] = $qerFilters[$key];
    		}
    	}
    	return $returnArr;
    }

    function getAllCoursePageCanonicalUrl() {
    	if(empty($this->appliedFilters)) {
    		$this->getAppliedFilters();
    	}
    	$queryParamCount = 0;
    	if(!empty($this->appliedFilters['stream']) &&  count($this->appliedFilters['stream']) == 1) {
    		$queryParamCount++;
    		$queryParams[] = $this->field_alias['stream'].'[]='.reset($this->appliedFilters['stream']);
    	}
    	
    	if(!empty($this->appliedFilters['base_course']) && count($this->appliedFilters['base_course']) == 1) {
    		$queryParamCount++;
    		$queryParams[] = $this->field_alias['base_course'].'[]='.reset($this->appliedFilters['base_course']);
    	}
    	if($queryParamCount != 1) {
    		return '';
    	}
    	if(empty($this->allCoursesURLWithoutPage)){
			$current_url = getCurrentPageURLWithoutQueryParams();
			$urlArray = explode("-", $current_url);
			if(is_numeric($urlArray[count($urlArray)-1])){
				$this->pageNumber = $urlArray[count($urlArray)-1];
				unset($urlArray[count($urlArray)-1]);
			}else{
				$this->pageNumber = 1;
			}
			$this->allCoursesURLWithoutPage = implode("-", $urlArray);
		}
		$this->allCoursePageCanonicalUrl = $this->allCoursesURLWithoutPage;
		if(!empty($queryParams)) {
			$this->allCoursePageCanonicalUrl .= '?'.implode("&", $queryParams);
		}
		return $this->allCoursePageCanonicalUrl;
    }
}
