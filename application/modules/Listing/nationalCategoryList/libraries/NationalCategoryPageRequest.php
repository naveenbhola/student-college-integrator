<?php

class NationalCategoryPageRequest {
	private $CI;

	private $unique_page_id;
	private $stream_id;
	private $substream_id;
	private $specialization_id;
	private $base_course_id;
	private $education_type;
	private $delivery_method;
	private $credential;
    private $level_credential;
    private $exam_id;
    private $city_id;
    private $state_id;

    private $rating;
    private $heading;
    private $title;
    private $description;

    private $trackingSearchId;
    private $trackingFilterId;
    private $requestFrom;

    private $appliedFilters;
    private $customAppliedFilters;
    private $page_number;
    private $desktop_page_limit = 30;
    private $mobile_page_limit = 5;
    private $sort_by;
    
    private $current_full_url;
    private $current_script_url;
    private $current_url_with_query_params;

    private $userAppliedFilters;

	function __construct($categoryPageData) {
        $time_start = microtime_float(); $start_memory = memory_get_usage();
		$this->CI = & get_instance();
        $this->categoryPageSEOModel = $this->CI->load->model('nationalCategoryList/categorypageseomodel');
        $this->CI->load->config("nationalCategoryList/nationalConfig");
        $this->field_alias = $this->CI->config->item('FIELD_ALIAS');
        $this->combined_filters = $this->CI->config->item('COMBINED_FILTERS');
        $this->nationalCategoryPageLib = $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder();
        $this->locationRepository = $locationBuilder->getLocationRepository();
        
        if(LOG_CL_PERFORMANCE_DATA)
            error_log("Section: NationalCategoryPageRequest, Load request dependencies | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);

        //set this data in this library
        $time_start = microtime_float(); $start_memory = memory_get_usage();
        $this->buildCategoryPageData($categoryPageData);
        if(LOG_CL_PERFORMANCE_DATA)
            error_log("Section: NationalCategoryPageRequest, Find URL in DB and set data | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
    }

    function buildCategoryPageData($categoryPageData) {
        //get page details from DB/cache
        if(empty($categoryPageData)){
            $categoryData = $this->parseCurrentUrl();
        }
        else{
            $categoryData = $categoryPageData;
        }
        // _p($categoryData);
        
    	//set the data
		$this->setCurrentUrl();
    	$this->setCategoryPageKey($categoryData['id']);
    	$this->setStream($categoryData['stream_id']);
    	$this->setSubstream($categoryData['substream_id']);
    	$this->setSpecialization($categoryData['specialization_id']);
    	$this->setBaseCourse($categoryData['base_course_id']);
    	$this->setEducationType($categoryData['education_type']);
    	$this->setDeliveryMethod($categoryData['delivery_method']);
    	$this->setCredential($categoryData['credential']);
    	$this->setExam($categoryData['exam_id']);
    	$this->setCity($categoryData['city_id'], 0);
    	$this->setState($categoryData['state_id']);
        
    	//set applied filters
    	$this->setAppliedFilters();
        $this->setCustomAppliedFilters();

        //set seo details
        $this->setBreadcrumb($categoryData['breadcrumb']);
        $this->setTitle($categoryData['meta_title']);
        $this->setDescription($categoryData['meta_description']);

        if(isMobileRequest()) {
            $this->setHeading($categoryData['heading_mobile']);
        } else {
            $this->setHeading($categoryData['heading_desktop']);
        }
        
        //set page number
        // $this->setPageNumber();

        //set sort by option
        $this->setSortBy();
    }

    //get page details from DB/cache
    function parseCurrentUrl() {
    	$scriptUrl = $this->CI->input->server('SCRIPT_URL', true);
		$scriptUrlArr = explode("-", $scriptUrl);
        if(is_numeric($scriptUrlArr[count($scriptUrlArr)-1])){
            $this->page_number = $scriptUrlArr[count($scriptUrlArr)-1];    
            unset($scriptUrlArr[count($scriptUrlArr)-1]);
        }else{
            $this->page_number = 1;
        }
        $scriptUrl = implode("-", $scriptUrlArr);
        $scriptUrl = trim($scriptUrl, '/');

        $data = $this->nationalCategoryPageLib->getCategoryPageDataByUrl($scriptUrl);
        if(DEBUGGER) {
            _p($data);
        }
        if(empty($data)) {
            //Send this url to old flow and get old params to redirected to new.
            $urlInParts = explode('/colleges/', $scriptUrl);
            $rnrUrlInParts = explode('/', $urlInParts[1]);
            
            $rnrFlag = 'RNRURL'; $params = $urlInParts[1];
            if(count($rnrUrlInParts) > 1) {
                $rnrFlag = 0;
                $params = $rnrUrlInParts[1];
            }
            
            $return = Modules::run('categoryList/CategoryList/categoryPage', $params, $rnrFlag);
		}
		elseif($data['status'] == 'deleted') {
			if($data['show_404']) {
				show_404(); // show zrp
			} elseif(!empty($data['301_url'])) {
				$redirectUrl = base_url().$data['301_url'];
				redirect($redirectUrl, 'location', 301);
			} else {
				redirect(base_url(), 'location', 301); //zrp //404
			}
		}
		elseif($data['status'] != 'live') {
			show_404(); // show zrp
		}

        $this->setRequestFrom($this->CI->input->get($this->field_alias['requestFrom'],true));
        if(DO_SEARCHPAGE_TRACKING){
            $this->setTrackingSearchId((int)$this->CI->input->get($this->field_alias['trackingSearchId'],true));
            $this->setTrackingFilterId((int)$this->CI->input->get($this->field_alias['trackingFilterId'],true));
        }
		return $data;
	}

    private function setCategoryPageKey($pageId) {
    	$this->unique_page_id = $pageId;
    }

    function getCategoryPageKey() {
    	return $this->unique_page_id;
    }

    private function setStream($streamId) {
    	$this->stream_id = $streamId;
    }

    function getStream() {
    	return $this->stream_id;
    }

    private function setSubstream($substreamId) {
    	$this->substream_id = $substreamId;
    }

    function getSubstream() {
    	return $this->substream_id;
    }

    private function setSpecialization($specializationId) {
    	$this->specialization_id = $specializationId;
    }

    function getSpecialization() {
    	return $this->specialization_id;
    }

    private function setBaseCourse($baseCourseId) {
    	$this->base_course_id = $baseCourseId;
    }

    function getBaseCourse() {
    	return $this->base_course_id;
    }

    private function setEducationType($educationType) {
    	$this->education_type = $educationType;
    }

    function getEducationType() {
    	return $this->education_type;
    }

    private function setDeliveryMethod($deliveryMethod) {
    	$this->delivery_method = $deliveryMethod;
    }

    function getDeliveryMethod() {
    	return $this->delivery_method;
    }

    private function setCredential($credential) {
    	$this->credential = $credential;
        if(!empty($credential)) {
            //$this->level_credential = ":".$credential;
        }
    }

    function getCredential() {
    	return $this->credential;
    }

    private function setExam($examId) {
    	$this->exam_id = $examId;
    }

    function getExam() {
    	return $this->exam_id;
    }

    private function setCity($cityId, $setState = 1) {
    	$this->city_id = $cityId;
    	
    	if($setState) {
    		if($cityId != 1) {
    			$cityObj = $this->locationRepository->findCity($cityId);
    			$this->state_id = $cityObj->getStateId() != 1? $cityObj->getStateId() : -1;
    		}
    		else if($cityId == 1) {
    			$this->state_id = 1;
    		}
    	}
    }

    function getCity() {
    	return $this->city_id;
    }

    private function setState($stateId) {
    	$this->state_id = $stateId;
    }

    function getState() {
    	if($this->state_id == -1) { //virtual city case
    		return;
    	}
    	return $this->state_id;
    }

    function setPageNumber() {
        $this->page_number = intval($this->CI->input->get($this->field_alias['pageNumber'], true));
        if(empty($this->page_number)) {
            $this->page_number = 1;
        }
    }

    function getPageNumber() {
        return $this->page_number;
    }

    function getPageLimit() {
        if(isMobileRequest()) {
            return $this->mobile_page_limit;
        } else {
            return $this->desktop_page_limit;
        }
    }

    function setSortBy() {
        $this->sort_by = $this->CI->input->get($this->field_alias['sort_by'], true);
        if(empty($this->sort_by)) {
            if($this->isLocationCTP()) {
                $this->sort_by = 'view_count';
            } else {
                $this->sort_by = 'sponsored';
            }
        }
    }

    function getSortBy() {
        return $this->sort_by;
    }

    function isLocationCTP() {
        if(!$this->isStreamPage() && !$this->isSubstreamPage() && !$this->isPopularCoursePage()) {
            return true;
        } else {
            return false;
        }
    }

    function isVirtualCityPage() {
    	return ($this->state_id == -1) ? 1 : 0;
    }

    function isCityPage() {
        return ($this->getCity() != 1) ? 1 : 0;
    }

    function isStatePage() {
        return (intval($this->getState()) > 1) ? 1 : 0;
    }

    function isStreamPage() {
        $streamId = $this->getStream();
        return (!empty($streamId)) ? 1 : 0;
    }

    function isSubstreamPage() {
        $substreamId = $this->getSubstream();
        return (!empty($substreamId)) ? 1 : 0;
    }

    function isPopularCoursePage() {
        $baseCourseId = $this->getBaseCourse();
        $streamId = $this->getStream();
        return (!empty($baseCourseId) && empty($streamId)) ? 1 : 0;
    }

    function getData() {
        $categoryPageData['stream']             = $this->getStream();
        $categoryPageData['substream']          = $this->getSubstream();
        $categoryPageData['specialization']     = $this->getSpecialization();
        $categoryPageData['base_course']        = $this->getBaseCourse();
        $categoryPageData['education_type']     = $this->getEducationType();
        $categoryPageData['delivery_method']    = $this->getDeliveryMethod();
        $categoryPageData['credential']         = $this->getCredential();
        $categoryPageData['exam']               = $this->getExam();
        $categoryPageData['city']               = $this->getCity();
        $categoryPageData['state']              = $this->getState();

        return $categoryPageData;
    }

    private function setCurrentUrl() {
    	$this->current_url = getCurrentPageURLWithoutQueryParams();
    	$this->current_script_url = trim($this->CI->input->server('SCRIPT_URL', true), '/');
    	$this->current_url_with_query_params = SHIKSHA_HOME.$this->CI->input->server('REQUEST_URI', true);
        
        $scriptUrlArr = explode("-", $this->current_url);
        if(is_numeric($scriptUrlArr[count($scriptUrlArr)-1])){
            $this->page_number = $scriptUrlArr[count($scriptUrlArr)-1];    
            unset($scriptUrlArr[count($scriptUrlArr)-1]);
        }else{
            $this->page_number = 1;
        }
        $this->current_url_without_page = implode("-", $scriptUrlArr);
        
    }

    function getCurrentUrl() {
    	return $this->current_url;
    }

    function getCurrentScriptUrl() {
    	return $this->current_script_url;
    }

    function getCurrentUrlWithQueryParams() {
    	return $this->current_url_with_query_params;
    }

    function getCanonicalUrl() {
        return $this->current_url;
    }

    function getCurrentUrlWithoutPageNo(){
        return $this->current_url_without_page;
    }

    function getUrlForPagination($pageNumber) {
        if(empty($pageNumber)) {
            $pageNumber = 1;
        }
        //Remove 'pn=' ===> break by 'pn='
        $urlArray     = explode($this->field_alias['pageNumber'].'=', $this->current_url_with_query_params);
        
        //take the part before 'pn='
        $urlFirstHalf = $urlArray[0];
        
        $urlFirstHalfArray = explode("?", $urlFirstHalf);
        if($pageNumber != "1"){
            $urlFirstHalfArray[0] = $this->current_url_without_page."-".$pageNumber;
        }else{
            $urlFirstHalfArray[0] = $this->current_url_without_page ;
        }

        $urlFirstHalf = implode("?", $urlFirstHalfArray);

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

    private function setBreadcrumb($breadcrumb) {
    	$this->breadcrumb = json_decode($breadcrumb);
    }

    function getBreadcrumb() {
    	return $this->breadcrumb;
    }

    private function setTitle($title) {
    	$this->title = $title;
    }

    function getTitle() {
    	return $this->title;
    }

    private function setDescription($description) {
    	$this->description = $description;
    }

    public function setCountInDescription($resultCount) {
        $this->description = str_replace("[n]", $resultCount, $this->description);
    }

    public function getDescription() {
        return $this->description;
    }

    private function setHeading($heading) {
        //LF-6385
        $categoryFiltersByUser = $this->CI->input->get('uaf',true); //in case of All India, this is needed
        $cityFilterByUser = $this->CI->input->get($this->field_alias['city'],true); //only to check if filter is applied or not
        $stateFilterByUser = $this->CI->input->get($this->field_alias['state'],true);

        if(in_array('location', $categoryFiltersByUser) || !empty($cityFilterByUser) || !empty($stateFilterByUser)) {
            $stringWithoutLocation = $this->removeLocationStringInH1($heading);
            $locationString = $this->getLocationStringFromFilters();

            $this->heading = $stringWithoutLocation." ".$locationString;
        } else {
            $this->heading = $heading;
        }
    }

    private function removeLocationStringInH1($heading) {
        while (($lastPos = strpos($heading, "in", $lastPos)) !== false) {
            $position = $lastPos;
            $lastPos = $lastPos + 2;
        }

        $locationStartPos = $position + 3;
        $stringWithoutLocation = substr($heading, 0, $locationStartPos);

        return $stringWithoutLocation;
    }

    private function getCriteriaFromHeading($heading){
        $criteriaHeading = $heading;
        if(stripos($heading,' colleges') !== false){
            $criteriaHeading = substr($heading,0,stripos($heading,' colleges'));
        }
        if(stripos($heading,' courses') !== false){
            $criteriaHeading = substr($heading,0,stripos($heading,' courses'));
        }
        if(stripos($heading,' institutes') !== false){
            $criteriaHeading = substr($heading,0,stripos($heading,' institutes'));
        }
        return $criteriaHeading;
    }

    public function getH2Text(){
        $categoryFiltersByUser = $this->CI->input->get('uaf',true);
        if(empty($categoryFiltersByUser)){
            $stringWithoutLocation = $this->removeLocationStringInH1($this->heading);
            $locationString = $this->getLocationStringFromFilters();
            $criteria = $this->getCriteriaFromHeading($stringWithoutLocation);
            return array('criteria' => $criteria,'locationString' => $locationString);
        }
        return array();
    }

    private function getLocationStringFromFilters() {
        $cityCount = 0;
        if(!empty($this->appliedFilters['city'])) {
            $cityCount = count($this->appliedFilters['city']);
        }

        $stateCount = 0;
        if(!empty($this->appliedFilters['state'])) {
            $stateCount = count($this->appliedFilters['state']);
        }

        $locationCount = $cityCount + $stateCount;
        
        if($locationCount == 0) { //All India
            $locationString = 'India';
        }
        elseif($cityCount > 2) { //show 1st city name and "x-1 locations"
            $cityId = reset($this->appliedFilters[city]);
            $cityObj = $this->locationRepository->findCity($cityId);
            $cityName = $cityObj->getName();

            $locationString = $cityName." and ".($locationCount-1)." locations";
        }
        elseif($stateCount > 2) { //show 1st state name and "x-1 locations"
            $stateId = reset($this->appliedFilters[state]);
            $stateObj = $this->locationRepository->findState($stateId);
            $stateName = $stateObj->getName();

            $locationString = $stateName." and ".($locationCount-1)." locations";
        }
        elseif($cityCount == 2) {
            if($stateCount == 0) { //show 1st city name and 2nd city name
                $cityObjs = $this->locationRepository->findMultipleCities($this->appliedFilters['city']);
                foreach ($cityObjs as $key => $cityObj) {
                    $cityNames[] = $cityObj->getName();
                }
                $locationString = implode(' and ', $cityNames);
            }
            else { //show 1st city name and "x-1 locations"
                $cityId = reset($this->appliedFilters['city']);
                $cityObj = $this->locationRepository->findCity($cityId);
                $cityName = $cityObj->getName();

                $locationString = $cityName." and ".($locationCount-1)." locations";
            }
        }
        elseif($stateCount == 2) {
            if($cityCount == 0) { //show 1st state name and 2nd state name
                $stateObjs = $this->locationRepository->findMultipleStates($this->appliedFilters['state']);
                foreach ($stateObjs as $key => $stateObj) {
                    $stateNames[] = $stateObj->getName();
                }
                $locationString = implode(' and ', $stateNames);
            }
            else { //show 1st state name and "x-1 locations"
                $stateId = reset($this->appliedFilters['state']);
                $stateObj = $this->locationRepository->findState($stateId);
                $stateName = $stateObj->getName();

                $locationString = $stateName." and ".($locationCount-1)." locations";
            }
        }
        elseif($cityCount == 1 && $stateCount == 1) { //show 1 city and 1 state
            $cityId = reset($this->appliedFilters['city']);
            $cityObj = $this->locationRepository->findCity($cityId);
            $cityName = $cityObj->getName();

            $stateId = reset($this->appliedFilters['state']);
            $stateObj = $this->locationRepository->findState($stateId);
            $stateName = $stateObj->getName();

            if($cityName == $stateName) {
                $locationString = $cityName;
            } else {
                $locationString = $cityName." and ".$stateName;
            }
        }
        elseif($cityCount == 1) { //show 1 city
            $cityId = reset($this->appliedFilters['city']);
            $cityObj = $this->locationRepository->findCity($cityId);
            $cityName = $cityObj->getName();

            $locationString = $cityName;
        }
        else { //show 1 state
            $stateId = reset($this->appliedFilters['state']);
            $stateObj = $this->locationRepository->findState($stateId);
            $stateName = $stateObj->getName();

            $locationString = $stateName;
        }

        return $locationString;
    }

    public function getHeading() {
    	return $this->heading;
    }

    private function setAppliedFilters() {
        $this->handleAbTestingUtmReferrer();

    	//user applied filters
		$this->appliedFilters['stream'] 				= $this->CI->input->get($this->field_alias['stream'],true);
		$this->appliedFilters['substream'] 				= $this->CI->input->get($this->field_alias['substream'],true);
		$this->appliedFilters['specialization'] 		= $this->CI->input->get($this->field_alias['specialization'],true);
        $this->appliedFilters['sub_spec']               = $this->CI->input->get($this->field_alias['sub_spec'],true);
		$this->appliedFilters['base_course'] 			= $this->CI->input->get($this->field_alias['base_course'],true);
		$this->appliedFilters['education_type'] 		= $this->CI->input->get($this->field_alias['education_type'],true);
		$this->appliedFilters['delivery_method'] 		= $this->CI->input->get($this->field_alias['delivery_method'],true);
        $this->appliedFilters['et_dm']                  = $this->CI->input->get($this->field_alias['et_dm'],true);
		$this->appliedFilters['credential'] 			= $this->CI->input->get($this->field_alias['credential'],true);
		$this->appliedFilters['exam'] 					= $this->CI->input->get($this->field_alias['exam'],true);
		$this->appliedFilters['locality'] 				= $this->CI->input->get($this->field_alias['locality'],true);
        $this->appliedFilters['state']                  = $this->CI->input->get($this->field_alias['state'],true);
        $this->appliedFilters['city']                   = $this->CI->input->get($this->field_alias['city'],true);
        $this->appliedFilters['fees']                   = $this->CI->input->get($this->field_alias['fees'],true);
		$this->appliedFilters['review'] 				= $this->CI->input->get($this->field_alias['review'],true);

		$this->appliedFilters['popular_group'] 			= $this->CI->input->get($this->field_alias['popular_group'],true);
		$this->appliedFilters['certificate_provider']	= $this->CI->input->get($this->field_alias['certificate_provider'],true);
		$this->appliedFilters['course_level']			= $this->CI->input->get($this->field_alias['course_level'],true);
        $this->appliedFilters['level_credential']       = $this->CI->input->get($this->field_alias['level_credential'],true);
		$this->appliedFilters['approvals']				= $this->CI->input->get($this->field_alias['approvals'],true);
		$this->appliedFilters['grants']					= $this->CI->input->get($this->field_alias['grants'],true);
		$this->appliedFilters['facilities']				= $this->CI->input->get($this->field_alias['facilities'],true);
		$this->appliedFilters['college_ownership']		= $this->CI->input->get($this->field_alias['college_ownership'],true);
		$this->appliedFilters['accreditation']			= $this->CI->input->get($this->field_alias['accreditation'],true);
        $this->appliedFilters['course_status']          = $this->CI->input->get($this->field_alias['course_status'],true);
        $categoryFiltersByUser                          = $this->CI->input->get('uaf',true);

        $this->cleanGetParams();
        $this->userAppliedFilters = $this->appliedFilters;

        //add current url filters in applied filters on page load
        if(!empty($this->stream_id) && !in_array($this->stream_id, $this->appliedFilters['stream']) && empty($this->appliedFilters['stream'])) {
            $this->appliedFilters['stream'][] = $this->stream_id;
        }
        if(!empty($this->base_course_id) && !in_array($this->substream_id, $this->appliedFilters['base_course']) && empty($this->appliedFilters['base_course']) && !in_array('base_course', $categoryFiltersByUser)) {
            $this->appliedFilters['base_course'][] = $this->base_course_id;
        }
        if(!empty($this->substream_id) && !in_array($this->substream_id, $this->appliedFilters['substream']) && empty($this->appliedFilters['substream']) && !in_array('sub_spec', $categoryFiltersByUser)) {
            $this->appliedFilters['substream'][] = $this->substream_id;
        }
        
        if(!empty($this->specialization_id) && !in_array($this->specialization_id, $this->appliedFilters['specialization']) && empty($this->appliedFilters['specialization']) && (!in_array('specialization', $categoryFiltersByUser) && !in_array('sub_spec', $categoryFiltersByUser))) {
            $this->appliedFilters['specialization'][] = $this->specialization_id;
        }
        
        // if filter for et is set separately, merge it into et_dm
        $skipDeliveryMethodFilter = 0;
        if(!empty($this->appliedFilters['education_type'])) {
            foreach ($this->appliedFilters['education_type'] as $key => $value) {
                if($value == PART_TIME_MODE && empty($this->appliedFilters['delivery_method'])) {
                    $this->appliedFilters['et_dm'][] = $this->field_alias['education_type']."_".$value."::".$this->field_alias['delivery_method'].'_any';
                } elseif($value == FULL_TIME_MODE) {
                    if(!empty($this->appliedFilters['delivery_method'])) {
                        $skipDeliveryMethodFilter = 1;
                    }
                    $this->appliedFilters['et_dm'][] = $this->field_alias['education_type']."_".$value;
                }
            }
            unset($this->appliedFilters['education_type']);
        }
        if(!empty($this->education_type) && 
            !in_array($this->field_alias['education_type']."_".$this->education_type, $this->appliedFilters['et_dm']) && 
            !in_array('et_dm', $categoryFiltersByUser)) {
            
            if($this->education_type == PART_TIME_MODE && empty($this->delivery_method)) {
                if(empty($this->appliedFilters['et_dm'])) {
                    $this->appliedFilters['et_dm'][] = $this->field_alias['education_type']."_".$this->education_type."::".$this->field_alias['delivery_method'].'_any';
                }
            } elseif($this->education_type == FULL_TIME_MODE) {
                $this->appliedFilters['et_dm'][] = $this->field_alias['education_type']."_".$this->education_type;
            }
        }
        
        // if filter for dm is set separately, merge it into et_dm
        if(!empty($this->appliedFilters['delivery_method']) && !$skipDeliveryMethodFilter) {
            foreach ($this->appliedFilters['delivery_method'] as $key => $value) {
                $this->appliedFilters['et_dm'][] = $this->field_alias['education_type']."_".PART_TIME_MODE."::".$this->field_alias['delivery_method'].'_'.$value;
            }
            unset($this->appliedFilters['delivery_method']);
        }
        else if(!empty($this->delivery_method) && 
            !in_array($this->field_alias['education_type']."_21::".$this->field_alias['delivery_method'].'_'.$this->delivery_method, $this->appliedFilters['et_dm']) && 
            !in_array('et_dm', $categoryFiltersByUser)) {
            if($this->education_type == PART_TIME_MODE) {
                $this->appliedFilters['et_dm'][] = $this->field_alias['education_type']."_".PART_TIME_MODE."::".$this->field_alias['delivery_method'].'_'.$this->delivery_method;
            }
        }

        if(!empty($this->level_credential) && !in_array($this->level_credential, $this->appliedFilters['level_credential']) && empty($this->appliedFilters['level_credential']) && !in_array('level_credential', $categoryFiltersByUser)) {
            $this->appliedFilters['level_credential'][] = $this->level_credential;
        } elseif(!empty($this->credential) && !in_array($this->credential, $this->appliedFilters['credential']) && empty($this->appliedFilters['credential']) && !in_array('credential', $categoryFiltersByUser)) {
    		$this->appliedFilters['credential'][] = $this->credential;
    	}
        
    	if(!empty($this->exam_id) && !in_array($this->exam_id, $this->appliedFilters['exam']) && empty($this->appliedFilters['exam']) && !in_array('exam', $categoryFiltersByUser)) {
    		$this->appliedFilters['exam'][] = $this->exam_id;
    	}

        //in case of All India, we are not setting city/state in applied filters
        if($this->city_id != 1 && !in_array($this->city_id, $this->appliedFilters['city']) && empty($this->appliedFilters['city']) && !in_array('location', $categoryFiltersByUser)) {
    		$this->appliedFilters['city'][] = $this->city_id;
    	}
    	else if($this->city_id == 1 && $this->state_id != 1 && !in_array($this->state_id, $this->appliedFilters['state']) && empty($this->appliedFilters['state'])  && !in_array('location', $categoryFiltersByUser)) { //if state page but not all India
    		$this->appliedFilters['state'][] = $this->state_id;
    	}

        $this->sanitizeFilters();
    }

    private function handleAbTestingUtmReferrer() {
        foreach ($_GET as $key => $value) {
            if($delete) {
                unset($_GET[$key]);
            }
            if($key == 'utm_referrer') {
                $delete = true;
            }
        }
    }

    public function setCustomAppliedFilters() {
        $this->customAppliedFilters = $this->appliedFilters;
        $combinedFilterNames = array_keys($this->combined_filters);
        
        foreach ($this->appliedFilters as $filterType => $filterValue) {
            if(in_array($filterType, $combinedFilterNames)) {
                foreach ($filterValue as $key => $value) {
                    $valueArr1 = explode('::', $value);
                    foreach ($valueArr1 as $key => $deepValue) {
                        if(!empty($deepValue)) {
                            $deepValueArr = explode('_', $deepValue);
                            if($deepValueArr[1] != 'any') {
                                $this->customAppliedFilters[array_search($deepValueArr[0], $this->field_alias)][] = $deepValueArr[1];
                            }
                        }
                    }
                }
            }
        }
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

    public function sanitizeFilters() {
        foreach ($this->appliedFilters as $key1 => $filter) {
            foreach ($filter as $key2 => $value) {
                if($value == 'undefined' || $value == '') {
                    unset($this->appliedFilters[$key1][$key2]);
                }
            }
        }
    }

    public function getRequestFrom(){
        return $this->requestFrom;
    }

    public function setRequestFrom($value){
        if(!empty($value)){
            $this->requestFrom = $value;
        }
    }

    public function getTrackingSearchId(){
        return $this->trackingSearchId;
    }

    public function setTrackingSearchId($value){
        if(!empty($value)){
            $this->trackingSearchId = $value;
        }
    }

    public function getTrackingFilterId(){
        return $this->trackingFilterId;
    }

    public function setTrackingFilterId($value){
        if(!empty($value)){
            $this->trackingFilterId = $value;
        }
    }

    function getAppliedFilters() {
    	return $this->appliedFilters;
    }

    function getCustomAppliedFilters() {
        return $this->customAppliedFilters;
    }

    function getUserAppliedFilters(){
        return $this->userAppliedFilters;
    }

    function isMultilocationPage() {
        $cityCount  = count($this->appliedFilters['city']);
        $stateCount = count($this->appliedFilters['state']);
        if($cityCount + $stateCount > 1) {
            return true;
        } else {
            return false;
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

    /*
     * Set the parameters with the keys as -
     * $categoryData['stream_id']
     * $categoryData['substream_id']
     * $categoryData['specialization_id']
     * $categoryData['base_course_id']
     * $categoryData['education_type']
     * $categoryData['delivery_method']
     * $categoryData['credential']
     * $categoryData['exam_id']
     * $categoryData['city_id']
     * $categoryData['state_id']
     *
     * Description -
     * The function will return URL based on current parameters. The ones set in the argument will overwrite the current param.
     */
    function getUrl($categoryData) {
        return;
	    if(empty($categoryData)) {
	    	return $this->getCurrentUrl();
	    }
	    if(!isset($categoryData['stream_id'])) {
			$categoryData['stream_id'] = empty($this->stream_id) ? 0 : $this->stream_id;
		}
		if(!isset($categoryData['substream_id'])) {
			$categoryData['substream_id'] = empty($this->substream_id) ? 0 : $this->substream_id;
		}
		if(!isset($categoryData['specialization_id'])) {
			$categoryData['specialization_id'] = empty($this->specialization_id) ? 0 : $this->specialization_id;
		}
		if(!isset($categoryData['base_course_id'])) {
			$categoryData['base_course_id'] = empty($this->base_course_id) ? 0 : $this->base_course_id;
		}
		if(!isset($categoryData['education_type'])) {
			$categoryData['education_type'] = empty($this->education_type) ? 0 : $this->education_type;
		}
		if(!isset($categoryData['delivery_method'])) {
			$categoryData['delivery_method'] = empty($this->delivery_method) ? 0 : $this->delivery_method;
		}
		if(!isset($categoryData['credential'])) {
			$categoryData['credential'] = empty($this->credential) ? 0 : $this->credential;
		}
		if(!isset($categoryData['exam_id'])) {
			$categoryData['exam_id'] = empty($this->exam_id) ? 0 : $this->exam_id;
		}
		if(!isset($categoryData['state_id']) && !isset($categoryData['city_id'])) {
			$categoryData['state_id'] = empty($this->state_id) ? 1 : $this->state_id;
		}
		if(!isset($categoryData['city_id'])) {
			$categoryData['city_id'] = empty($this->city_id) ? 1 : $this->city_id;
		}

		$url = $this->categoryPageSEOModel->getUrlByParams($categoryData);
    	$url = base_url().$url['url'];
    	return $url;
    }
    
    public function showGutterBanner() {
         return 1; // Need to show on all of the Category Pages. 
    }

    public function getDefaultCategoryPageFilters(){
        $temp = array('stream'=>'getStream','substream'=>'getSubstream','specialization'=>'getSpecialization','base_course'=>'getBaseCourse','education_type'=>'getEducationType','delivery_method'=>'getDeliveryMethod','credential'=>'getCredential','exam'=>'getExam','city'=>'getCity','state'=>'getState');
        $returnArr = array();
        foreach ($temp as $key => $method) {
            $value = call_user_func(array($this,$method));
            if(!empty($value)){
                $returnArr[$key] = $value;
            }
        }
        return $returnArr;
    }

    public function isAllIndiaRequest(){
        $appliedFilters = $this->getAppliedFilters();
        if((empty($appliedFilters['city']) || (count($appliedFilters['city']) == 1 && $appliedFilters['city'][0] == 1)) && (empty($appliedFilters['state']) || (count($appliedFilters['state']) == 1 && $appliedFilters['state'][0] == 1))){
            return true;
        }
        return false;
    }
}
