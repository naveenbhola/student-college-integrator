<?php 

class McategoryList extends ShikshaMobileWebSite_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	private function _init() {
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
		    $this->userid=$this->userStatus[0]['userid'];
		} else {
		    $this->userid=-1;
		}

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$this->load->builder('nationalCategoryList/NationalCategoryPageBuilder');
        $this->categoryPageBuilder = new NationalCategoryPageBuilder();

        if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - Build category page data | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);

        $this->request = $this->categoryPageBuilder->getCategoryPageRequest();
        $this->nationalCategoryPageRepository = $this->categoryPageBuilder->getCategoryPageRepository();

		$this->load->config('nationalInstitute/instituteStaticAttributeConfig');
		$this->load->config('CollegeReviewForm/collegeReviewConfig'); 

		$this->load->library('nationalCategoryList/BannersLibrary');
		$this->bannersLib = new BannersLibrary;

		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$this->streamRepository = $listingBase->getStreamRepository();

		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
		}		

		$this->_initOther();	    
		$this->cmpObj = $this->load->library('comparePage/comparePageLib');
		$this->load->helper('image');

		$this->institutedetailsmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
		$this->instituteDetailLib = $this->load->library('nationalInstitute/instituteDetailLib');
	}

	private function _initOther(){
		$this->load->library('recommendation/alsoviewed');
		$this->load->helper('listingCommon/listingcommon');
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();

	    $this->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();

	    // get institute repository with all dependencies loaded
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();

	    $this->load->model('nationalCategoryList/categorypagemodel');
        $this->categoryPageModel = new CategoryPageModel();
        
        $this->load->config("nationalCategoryList/nationalConfig");
        $this->FILTER_BUCKET_NAME = $this->config->item('FILTER_NAME_MAPPING');
        $this->fieldAlias = $this->config->item('FIELD_ALIAS');

        $this->load->library('customizedmmp/customizemmp_lib');
        $this->customizedMMPLib = new customizemmp_lib();
        $this->myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");
        $this->national_course_lib = $this->load->library('listing/NationalCourseLib');
        $this->load->library('msearch5/msearchDisplayObject');
        $this->load->helper('listingCommon/listingcommon');

        $this->load->library('nationalCourse/CourseDetailLib');
		$this->courseDetailLib = new CourseDetailLib;

		$this->load->helper('security');
	}

	public function index() {
		global $serverStartTime;
		$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();

		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - Reached controller | ".getLogTimeMemStr($serverStartTime, $start_memory_1)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);

		//set category page
		$this->_init();
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();

		//get processed institutes and filters
		$data = $this->nationalCategoryPageRepository->getFiltersAndInstitutes();
		$stream = $this->request->getStream();

		if($this->request->getRequestFrom() != 'filters' && in_array($stream,array(DESIGN_STREAM,IT_SOFTWARE_STREAM,ANIMATION_STREAM)) && $this->request->isAllIndiaRequest()){
			$data['showLocLayerOnLoad'] = true;
		}
		else{
			$data['showLocLayerOnLoad'] = false;	
		}
		
		//AB testing
		$data['abTestVersion'] = '';
		$data['isAbTestPage'] = 'no';
		/*
		$requestUrl = $this->request->getCurrentUrl();
		$mbaUrl 	= strpos($requestUrl, '/mba/colleges/');
		
		if($mbaUrl === false) {
			$data['abTestVersion'] = '';
			$data['isAbTestPage'] = 'no';
		} else {
			$data['abTestVersion'] = $this->input->get('version', true);
			$data['isAbTestPage'] = 'yes';
			
			switch ($data['abTestVersion']) {
				case 1:
					$data['brochureText'] = 'Download Brochure';
					break;
				
				case 2:
					$data['brochureText'] = 'Email Brochure';
					break;

				default:
					$data['brochureText'] = 'Request Brochure';
					break;
			}
		} */
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - Get filters and institutes | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();

		$data['appliedFilters'] = $this->request->getAppliedFilters();

		$fieldAlias        		 = $this->fieldAlias;
		$combinedFilters 		 = $this->config->item('COMBINED_FILTERS');
		$filtersPossible 		 = $this->config->item('FILTERS_POSSIBLE');
		$data['filtersPossible'] = json_encode($filtersPossible);

		$finalCourseIds 		 = array();
		$eligibility = array();
		foreach ($data['institutes']['instituteData'] as $instituteId => $instituteObj) {
			$courseObj = $instituteObj->getCourses();
			$courseObj = reset($courseObj);
			if(!empty($courseObj)){
				//get all exam Ids
				$exams = $courseObj->getEligibility();
    			foreach ($exams['general'] as $exam) {
        			$examId = $exam->getExamId();
        			if($exam>0){
        				$eligibility[] = $examId;
        			}
 				}
				$allExamsList = $courseObj->getAllEligibilityExams(false);
				foreach ($allExamsList as $examId=>$examName) {
				    if($examId>0){
        				$eligibility[] = $examId;
        			}
				}

				$courseId = $courseObj->getId();
				$finalCourseIds[] = $courseId;
				unset($courseObj);
				$displayObject = new msearchDisplayObject();
				$displayObject->setDisplayDataObject($instituteObj, $data['appliedFilters']);
				$data['displayDataObject'][$courseId] = $displayObject;
			}
			$isMultilocation    = count($instituteObj->getLocations()) > 1 ? true : false;
			if($isMultilocation) {
				$instituteIdsArray[] = $instituteId;
			}
		}
		$eligibility = array_unique($eligibility);
		
        $eligibilityUrls = array();
		if(count($eligibility)>0){
        	$this->examPageLib    = $this->load->library('examPages/ExamPageLib');
        	$eligibilityUrls = $this->examPageLib->getExamMainUrlsById($eligibility);
		}
		$data['eligibilityUrls'] = $eligibilityUrls;

		if(!empty($instituteIdsArray)) {
			$instituteCourses = $this->instituteDetailLib->getAllCoursesForMultipleInstitutes($instituteIdsArray);
			foreach ($instituteCourses as $instituteId => $value) {
				$locationsMappedToCourses = $this->institutedetailsmodel->getUniqueCoursesLocations($value['courseIds']);
				$data['isInstituteMultilocation'][$instituteId] = count($locationsMappedToCourses) > 1 ? true : false;
		    	if($data['isInstituteMultilocation'][$instituteId]) {
		    		$data['isInstituteMultilocation'][$instituteId] = $data['institutes']['instituteData'][$instituteId]->getURL()."?showAllBranches=1";
		    	}
			}
		}
		
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - Prepared filters | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();

		$criteriaArray = $this->_getBannerCriteria($this->request);
		$data['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);

		foreach ($data['courseStatusData'] as $key => $value) {
			$courseStatus = implode(", ", $value['courseStatusDisplay']);
            $courseStatus = (strlen($courseStatus) > 112) ? substr($courseStatus, 0, 110).'..' : $courseStatus;
            unset($data['courseStatusData'][$key]);
            $data['courseStatusData'][$key] = $courseStatus;
		}

		$data['requestFrom']     = $this->request->getRequestFrom();
		$data['comparedData'] = $this->cmpObj->getComparedData('mobile');
		$data['totalPages']      = ceil($data['totalInstituteCount']/$this->request->getPageLimit());
		$data['currentPage'] = $this->request->getPageNumber();
		$data['pageNumber'] = $this->request->getPageNumber() ? $this->request->getPageNumber() : 1;
		$data['isAjax'] = ($_SERVER['HTTP_X_REQUESTED_WITH'] != '') ? 1 : 0;
		$data['trackForPages'] = true;

		
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - Prepared banners and recommendation | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();

		if($data['currentPage'] > $data['totalPages'] && $data['totalPages'] > 0) { //safe check
		    redirect($this->request->getUrlForPagination(1),'location',301);
		}
		
		/*SEO DATA*/
		$data['m_canonical_url'] = $this->request->getCanonicalUrl();
		$data['m_meta_title'] = $this->request->getTitle();
		$data['m_meta_description'] = ($data['currentPage'] > 1) ? "Page ".$data['currentPage'].": ".$this->request->getDescription() : $this->request->getDescription();
		$data['keyword'] = $this->request->getHeading();
		$data['h2Text'] = $this->request->getH2Text();

		$urlWithoutPageNo = $data['urlPrefix'] = $this->request->getCurrentUrlWithoutPageNo();
		//$data['breadcrumb'] = $this->request->getBreadcrumb();
		if($data['currentPage'] > 1){
			$prevPage = $data['currentPage'] - 1;
			if($prevPage == "1"){
				$data['prevURL'] = $urlWithoutPageNo;
			}else{
				$data['prevURL'] = $urlWithoutPageNo."-".$prevPage;
			}
		}
		
		if($data['currentPage'] < $data['totalPages']){
			$nextPage = $data['currentPage'] + 1;
			$data['nextURL'] = $urlWithoutPageNo."-".$nextPage;
		}

		/*SEO Data End*/		

		if(DO_SEARCHPAGE_TRACKING){
			if(empty($data['isAjax'])){
				if($this->request->getRequestFrom() == 'filters'){
					$data['trackingFilterId'] = $this->trackmodel->trackFilterClick($this->request);
					$data['trackingSearchId'] = $this->request->getTrackingSearchId();
					$this->request->setTrackingFilterId($data['trackingFilterId']);
				}
				else{
					$trackingSearchId = $this->request->getTrackingSearchId();
					if(!empty($trackingSearchId)){
						$data['trackingSearchId'] = $trackingSearchId;
						$trackingFilterId = $this->request->getTrackingFilterId();
						if(!empty($trackingFilterId)){
							$data['trackingFilterId'] = $trackingFilterId;
						}
					}
					else{
						// $data['updateResultCountForTracking'] = true;
						$trackData = array();
						$trackData['searchKeyword'] = !empty($criteriaArray['nickname']) ? $criteriaArray['nickname'] : 'Empty Keyword';
						$trackData['page'] = 'category';
						$trackData['resultCount'] = $data['totalInstituteCount'];
						$trackData['userId'] = ($this->userid > 0) ? $this->userid : NULL;
						$data['trackingSearchId'] = $this->trackmodel->getTrackingIdForCourse($trackData);
						$this->request->setTrackingSearchId($data['trackingSearchId']);
					}
				}
			}
			else{
				$trackingSearchId = $this->request->getTrackingSearchId();
				if(!empty($trackingSearchId)){
					$data['trackingSearchId'] = $trackingSearchId;
					$trackingFilterId = $this->request->getTrackingFilterId();
					if(!empty($trackingFilterId)){
						$data['trackingFilterId'] = $trackingFilterId;
					}
				}
			}
		}

	    $data['product'] = "McategoryList";
		$data['pageLimit'] = $this->request->getPageLimit();
		$this->_setPaginationData($data);

		$data['genericPaginationURL'] = $this->request->getUrlForPagination("#page_no#");
		
		$data['filterBucketName'] = json_encode($this->FILTER_BUCKET_NAME);

		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - SEO and tracking done | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		
		if($this->userStatus != 'false') {
			$userId = $this->userStatus[0]['userid'];
			$data['shortlistedCourses'] = Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $userId);
		}

		//show iim call predictor banner when full time MBA PGDM page
		$data['showIIMCallPredictor'] = 0;
		if($this->request->getBaseCourse() == 101 && $this->request->getEducationType() == 20) {
			$data['showIIMCallPredictor'] = 1;
		}

		$data['isShowIcpBanner'] = false;
		$collegePredBannerDetails = getAndShowCollegePredBanner($stream, $this->request->getBaseCourse());
        if(!empty($collegePredBannerDetails)){
            $data['predBannerStream'] = $collegePredBannerDetails['predStream'];
            $data['isShowIcpBanner'] = true;
        }
	    $data['isLazyLoad'] = $this->input->getRawRequestVariable('isLazyLoad');
	    $data['urlPassingAttribute']      = $this->prepareUrlNeededParams($this->request);

	    if(!$data['isLazyLoad']){
	    	$this->_beaconTrackData($data, $this->request);
	    }

	    if(empty($data['isAjax'])){
	    	$data['gtmParams'] = $this->getGTMParams($this->request);
	    	$data['defaultFiltersApplied'] = $this->request->getDefaultCategoryPageFilters();
	    }
	    
	    $brochuresMailed = $_COOKIE['applied_courses'];
		if(empty($brochuresMailed)){
			$brochuresMailed = array();
		}else{
			$brochuresMailed = json_decode(base64_decode($brochuresMailed));
		}
		$data['brochuresMailed'] = $brochuresMailed;

		if(empty($data['totalInstituteCount'])){
			// Stream List for ZRP
			$data['streamsArray'] = $this->streamRepository->getAllStreams();
	        // Sort streams by name
			uasort($data['streamsArray'], function($a,$b){return strcmp($a["name"], $b["name"]);});
		}
		else if($data['totalInstituteCount'] > 0 && !$data['isAjax']) {
			$this->catPageInterLinkingLib = $this->load->library('nationalCategoryList/NationalCategoryPageInterLinking', $this->request);
    		$data['categoryPageInterlinkgUrls'] = $this->catPageInterLinkingLib->getCategoryPageInterLinkingUrl();
    		$data['categoryPagekey'] = $this->request->getCategoryPageKey();
		}
		$data['trackingIds']['compare'] = MOBILE_NL_CTPG_TUPLE_COMPARE;
		$data['trackingIds']['shortlist'] = MOBILE_NL_CATEGORY_TUPLE_COURSESHORTLIST;
		$data['trackingIds']['downloadBrochure'] = MOBILE_NL_CTPG_TUPLE_DEB;


		//Aggregate Review Data 
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $courseReviewData = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');
        $data['courseWidgetData'] = $courseReviewData; 

		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - Shortlist, brochure, beacon | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - Total page load without view | ".getLogTimeMemStr($serverStartTime, $start_memory_1)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		$data['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'CTPG','page'=>'MOBILE');

		if($data['isLazyLoad']){
			$result['view'] = $this->load->view('msearch5/msearchV3/msearchPageBody',$data,TRUE);

			if($data['totalInstituteCount'] <= ((($data['currentPage'] - 1) * $this->request->getPageLimit()) + $data['institutes']['instituteCountInCurrentPage'])) {
				$result['message'] = 'disableNextLazyLoad';
			} else {
				$result['message'] = 'ok';
			}
			echo json_encode($result);
		}else if($data['requestFrom'] == 'filterBucket'){
			echo json_encode(array('filters'=>$data['filters'],'selectedFilters'=>$data['selectedFilters'],'filterBucketName'=>$this->FILTER_BUCKET_NAME));
		}else{
			$this->load->view('msearch5/msearchV3/msearchPageContent',$data);
		}

		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Mobile - Total page load from index with view | ".getLogTimeMemStr($serverStartTime, $start_memory_1)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
	}

	public function getGTMParams($request){
		$appliedFilters = $request->getAppliedFilters();//_p($appliedFilters);die;
		$temp = array('city'=>'cityId','state'=>'stateId','stream'=>'streamId','substream'=>'substreamId','specialization'=>'specializationId','base_course'=>'baseCourseId','education_type'=>'educationType','delivery_method'=>'deliveryMethod','credential'=>'credential','exam'=>'exams');
		$flipFieldAlias = array_flip($this->fieldAlias);
		$gtmParams = array();
		foreach ($appliedFilters as $key => $val) {
			if(!empty($val)){
				switch($key){
					case 'sub_spec':
					case 'et_dm':
						foreach ($val as $filterValue) {
							$temp1 = explode('::',$filterValue);
							foreach ($temp1 as $temp2) {
								$keyArr = explode('_',$temp2);
								if(empty($gtmParams[$temp[$flipFieldAlias[$keyArr[0]]]])){
									$gtmParams[$temp[$flipFieldAlias[$keyArr[0]]]] = array();
								}
								if(!in_array($keyArr[1],$gtmParams[$temp[$flipFieldAlias[$keyArr[0]]]])){
									$gtmParams[$temp[$flipFieldAlias[$keyArr[0]]]][] = $keyArr[1];
								}
							}
						}
						break;
					default:
						if(!empty($temp[$key])){
							$gtmParams[$temp[$key]] = $val;
						}
						break;
				}
			}
		}

		$gtmParams['pageType'] = 'categoryPage';
		$gtmParams['countryId'] = 2;
		if($this->userid > 0){
			$workExperience = $this->userStatus[0]['experience'];
			if(!empty($workExperience)){
				$gtmParams['workExperience'] = $workExperience;
			}
		}
		return $gtmParams;
	}

	public function _setPaginationData(& $data) {
		$lazyLoadsPerPage = 4;
		$data['lazyLoadsPerPage'] = $lazyLoadsPerPage;
		
		$maxPageSlots = ceil($data['totalInstituteCount']/($data['pageLimit'] * $lazyLoadsPerPage));
		
		$currentPageSlot = ceil($data['currentPage']/$lazyLoadsPerPage); //1 2 3 4 5
		$currentPageNum = $data['currentPage'];

		$data['paginationURLS'][0] = array();
		$data['paginationURLS'][1] = array();
		$data['paginationURLS'][2] = array();
		$data['paginationURLS'][3] = array();
		$data['paginationURLs']['leftArrow'] = array();
		$data['paginationURLs']['rightArrow'] = array();

		// LEFT ARROW
		if($currentPageSlot > 1) { 
            $data['paginationURLS']['leftArrow']['url'] = $this->request->getUrlForPagination($currentPageNum - $lazyLoadsPerPage);
        }
        
        // URL NO 1.
        if($currentPageSlot == 1){
        	$data['paginationURLS'][0]['isActive'] = true;
        	$data['paginationURLS'][0]['url'] = "";
        	$data['paginationURLS'][0]['text'] = $currentPageSlot;
        }
        elseif($currentPageSlot == 2) {
        	$data['paginationURLS'][0]['url'] = $this->request->getUrlForPagination($currentPageNum - $lazyLoadsPerPage);
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 1;	
        }
        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
        	$data['paginationURLS'][0]['url'] = $this->request->getUrlForPagination($currentPageNum - (3 * $lazyLoadsPerPage));
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 3;		
        }
        else{
        	$data['paginationURLS'][0]['url'] = $this->request->getUrlForPagination($currentPageNum - (2 * $lazyLoadsPerPage));
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 2;		
        }

        // URL NO 2
        if($maxPageSlots >= 2){
        	if($currentPageSlot == 1){
	        	$data['paginationURLS'][1]['url'] = $this->request->getUrlForPagination($currentPageNum + $lazyLoadsPerPage);
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot + 1;
	        }
	        elseif($currentPageSlot == 2) {
	  			$data['paginationURLS'][1]['isActive'] = true;
	        	$data['paginationURLS'][1]['url'] = "";
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot;	
	        }
	        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
	        	$data['paginationURLS'][1]['url'] = $this->request->getUrlForPagination($currentPageNum - (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot - 2;		
	        }
	        else{
	        	$data['paginationURLS'][1]['url'] = $this->request->getUrlForPagination($currentPageNum - (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot - 1;		
	        }
        }

        // URL NO 3
        if($maxPageSlots >= 3){
        	if($currentPageSlot == 1){
	        	$data['paginationURLS'][2]['url'] = $this->request->getUrlForPagination($currentPageNum + (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][2]['text'] = $currentPageSlot + 2;
	        }
	        elseif($currentPageSlot == 2) {
	        	$data['paginationURLS'][2]['url'] = $this->request->getUrlForPagination($currentPageNum + (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][2]['text'] = $currentPageSlot + 1;	
	        }
	        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
	        	$data['paginationURLS'][2]['url'] = $this->request->getUrlForPagination($currentPageNum - (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][2]['text'] = $currentPageSlot - 1;		
	        }
	        else{
	        	$data['paginationURLS'][2]['isActive'] = true;
	        	$data['paginationURLS'][2]['url'] = "";
	        	$data['paginationURLS'][2]['text'] = $currentPageSlot;		
	        }
        }

        // URL NO 3
        if($maxPageSlots >= 4){
        	if($currentPageSlot == 1){
	        	$data['paginationURLS'][3]['url'] = $this->request->getUrlForPagination($currentPageNum + (3 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 3;
	        }
	        elseif($currentPageSlot == 2) {
	        	$data['paginationURLS'][3]['url'] = $this->request->getUrlForPagination($currentPageNum + (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 2;	
	        }
	        elseif($currentPageSlot == $maxPageSlots) {
	        	$data['paginationURLS'][3]['isActive'] = true;
	        	$data['paginationURLS'][3]['url'] = "";
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot;		
	        }
	        else{
	        	$data['paginationURLS'][3]['url'] = $this->request->getUrlForPagination($currentPageNum + (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 1;		
	        }
        }
        
        if($currentPageSlot < $maxPageSlots) { 
            $data['paginationURLS']['rightArrow']['url'] = $this->request->getUrlForPagination($currentPageNum + $lazyLoadsPerPage);
        }

        if(DO_SEARCHPAGE_TRACKING){
        	$trackingSearchId = $this->request->getTrackingSearchId();
        	if(!empty($trackingSearchId)){
        		$params[$this->fieldAlias['trackingSearchId']] = $trackingSearchId;
        		$trackingFilterId = $this->request->getTrackingFilterId();
        		if(!empty($trackingFilterId)){
        			$params[$this->fieldAlias['trackingFilterId']] = $trackingFilterId;
        		}
        		foreach ($data['paginationURLS'] as $key => $value) {
        			if(!empty($value) && !empty($value['url'])){
        				$data['paginationURLS'][$key]['url'] = add_query_params($value['url'],$params);
        			}
        		}
        	}
        }
	}

	public function prepareUrlNeededParams($request){
		$data = array();
		return json_encode($data);
	}

	private function _getBannerCriteria($request){
		
		$nickNameCriteria = array(
			'stream_id' => $this->request->getStream(),
			'substream_id' => $this->request->getSubstream(),
			'base_course_id' => $this->request->getBaseCourse(),
			'credential' => $this->request->getCredential(),
			'education_type' => $this->request->getEducationType(),
			'delivery_method' => $this->request->getDeliveryMethod()
		);

		$cptgNickName = "";
		$result = $this->categoryPageModel->getCategoryPageNickName($nickNameCriteria);

		if(!empty($result)){
			$result = reset($result);
			$cptgNickName = $result['criterion_name'];
			$ctpgId = $result['criterion_id'];
		}

		// City for Banners, If Main City(one in request is there in filters)
		// then use this else use any one from filters else use 1(all India)
		$cityForBanners = 1;
		$cityFromRequest = $request->getCity();
		$appliedFilters = $request->getAppliedFilters();
		$cityFromFilters = $appliedFilters['city'];
		$stateFromFilters = $appliedFilters['state'];
		$stateFromRequest = $this->request->getState();
		$stateForBanner = $stateFromRequest;

		if(is_array($cityFromFilters) && in_array($cityFromRequest, $cityFromFilters)){
			$cityForBanners = $cityFromRequest;
		}else if(!empty($cityFromFilters)){
			$cityForBanners = $cityFromFilters[array_rand($cityFromFilters)];
		}

		if(is_array($stateFromFilters) && in_array($stateFromRequest, $stateFromFilters)){
			$stateForBanner = $stateFromRequest;
		}else if(!empty($stateFromFilters)){
			$stateForBanner = $stateFromFilters[array_rand($stateFromFilters)];
		}
		
		$stateKeywordPrefix = "";
		if($cityForBanners == 1){
			$stateKeywordPrefix = "BMS_STATE_".$stateForBanner."_";
		}

		$countryForBanners = 2;

		$bannerCriteria = array(
					'country' => $countryForBanners,
					'city' => $cityForBanners,
					'nickname' => $cptgNickName,
					'keyword' => $stateKeywordPrefix.rtrim(strtoupper(preg_replace("/[^a-z0-9_]+/i", "_",$cptgNickName)),"_")."_".$ctpgId
			);
		
		return $bannerCriteria;
	}

	private function _beaconTrackData(& $data, $request){

		 $data['beaconTrackData'] = array(
                    'pageIdentifier' => 'categoryPage',
                    'pageEntityId' => $request->getCategoryPageKey(),
              	  	'extraData' => array('url'=>get_full_url()),
              	  	'cityId' =>  $request->getCity(),
              	  	'stateId' =>  $request->getState(),
  	  			    'baseCourseId' => $request->getBaseCourse(),
		 			'educationType' => $request->getEducationType(),
		 			'deliveryMethod' => $request->getDeliveryMethod(),
              	  	'countryId' =>  2
                );
		 $data['beaconTrackData']['hierarchy'][0]['streamId'] = $request->getStream();
		 $data['beaconTrackData']['hierarchy'][0]['substreamId'] = $request->getSubstream();
		 $data['beaconTrackData']['hierarchy'][0]['specializationId'] = $request->getSpecialization();		 
		 $data['beaconTrackData']['hierarchy'][0] = array_filter($data['beaconTrackData']['hierarchy'][0]);

	}
}
