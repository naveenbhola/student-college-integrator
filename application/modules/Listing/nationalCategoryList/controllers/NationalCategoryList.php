<?php

class NationalCategoryList extends MX_Controller {

	private function _init() {
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
		    $this->userid=$this->userStatus[0]['userid'];
		} else {
		    $this->userid=-1;
		}

		$time_start = microtime_float(); $start_memory = memory_get_usage(); $this->benchmark->mark('Set_category_page_data_total_start');
		$this->load->builder('nationalCategoryList/NationalCategoryPageBuilder');
        $this->categoryPageBuilder = new NationalCategoryPageBuilder();

        $this->benchmark->mark('Set_category_page_data_total_end');
        if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Set category page data - total | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);

        $this->request = $this->categoryPageBuilder->getCategoryPageRequest();
        $this->nationalCategoryPageRepository = $this->categoryPageBuilder->getCategoryPageRepository();

		$this->load->config('nationalInstitute/instituteStaticAttributeConfig');

		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$this->streamRepository = $listingBase->getStreamRepository();		
		$this->baseCourseRepository = $listingBase->getBaseCourseRepository();

		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
		}

		$this->instituteDetailLib = $this->load->library('nationalInstitute/instituteDetailLib');
		$this->institutedetailsmodel = $this->load->model('nationalInstitute/institutedetailsmodel');

		$this->load->config('CollegeReviewForm/collegeReviewConfig');

		$this->_initOther('init');
	}

	private function _initOther($from='direct'){

		if($from == "direct"){
			$this->userStatus = $this->checkUserValidation();
			if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
			    $this->userid=$this->userStatus[0]['userid'];
			} else {
			    $this->userid=-1;
			}
		}
		$this->load->library('recommendation/alsoviewed');
		$this->load->helper('listingCommon/listingcommon');
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();

		$this->load->library('nationalCategoryList/BannersLibrary');
		$this->bannersLib = new BannersLibrary;

		$this->courseRepo = $courseBuilder->getCourseRepository();

	    $this->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();

	    // get institute repository with all dependencies loaded
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();

	    $this->load->model('nationalCategoryList/categorypagemodel');
        $this->categoryPageModel = new CategoryPageModel();
        $this->load->config("nationalCategoryList/nationalConfig");
        $this->fieldAlias = $this->config->item('FIELD_ALIAS');
        $this->load->library('customizedmmp/customizemmp_lib');
        $this->customizedMMPLib = new customizemmp_lib();
        $this->myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");
        $this->national_course_lib = $this->load->library('listing/NationalCourseLib');
        $this->load->library('nationalCategoryList/NationalCategoryDisplayObject');
        $this->load->helper('listingCommon/listingcommon');

        $this->load->library('nationalCourse/CourseDetailLib');
		$this->courseDetailLib = new CourseDetailLib;

		$this->load->library('common/RecatCommonLib');
		$this->recatCommonLib = new RecatCommonLib;
		$this->load->helper('image');

		$this->load->library('Online/OnlineFormUtilityLib');
		$this->onlineFormLib = new OnlineFormUtilityLib();

		$this->cmpObj = $this->load->library('comparePage/comparePageLib');  
		$this->load->library('ContentRecommendation/AnARecommendationLib');
		$this->anaRecomLib = new AnARecommendationLib;
		$this->load->config('CollegeReviewForm/collegeReviewConfig');
	}
	
	public function index() {
		$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
		$this->benchmark->mark('Total_page_load_with_view_start');
		$this->benchmark->mark('Total_page_load_without_view_start');

		$currentUrl = "https://".$this->security->xss_clean($_SERVER[HTTP_HOST]). $this->security->xss_clean($_SERVER[REQUEST_URI]);
        	$newUrl = SHIKSHA_HOME . $this->security->xss_clean($_SERVER[REQUEST_URI]);
        	if($newUrl !== $currentUrl){
            		redirect($newUrl, 'location', 301);
        	}
		//set category page
		$this->_init();

		//get processed institutes and filters
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		
		$data = $this->nationalCategoryPageRepository->getFiltersAndInstitutes();
		$stream = $this->request->getStream();
		if(in_array($stream,array(DESIGN_STREAM,IT_SOFTWARE_STREAM,ANIMATION_STREAM)) && $this->request->isAllIndiaRequest()){
			$data['showLocLayerOnLoad'] = true;
		}
		else
		{
			$data['showLocLayerOnLoad'] = false;	
		}

		//AB testing
		$data['isAbTestPage'] = 'no';
		/*
		$requestUrl = $this->request->getCurrentUrl();
		$mbaUrl 	= strpos($requestUrl, '/mba/colleges/');
		//$btechUrl 	= strpos($requestUrl, '/b-tech/colleges/');
		
		if($mbaUrl === false && $btechUrl == false) {
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
			error_log("Section: Get filters and institutes | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$this->benchmark->mark('Set_Display_Object_start');

		$data['customAppliedFilters'] = $this->request->getCustomAppliedFilters();
		$data['filterBucketName'] =  $this->config->item('FILTER_NAME_MAPPING');
		$fieldAlias =  $this->fieldAlias;
		
		foreach ($data['filters'] as $key => $value) {
			$filtersList[$key] = $fieldAlias[$key];
		}

		$data['jsonFiltersList'] = json_encode($filtersList);
		$instituteIdsArray = array();
		$finalCourseIds  = array();
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
				$finalCourseResult[$courseId] = $courseObj;
				unset($courseObj);
				$displayObject = new NationalCategoryDisplayObject();
				$displayObject->setDisplayDataObject($instituteObj, $data['appliedFilters']);
				$data['displayDataObject'][$courseId] = $displayObject;
				$finalCourseIds[] = $courseId;
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

        // $this->load->builder('ExamBuilder','examPages');
        // $examBuilder          = new ExamBuilder();
        // $this->examRepository = $examBuilder->getExamRepository();
        // $examArray = $this->examRepository->findMultiple($eligibility);
		$data['eligibilityUrls'] = $eligibilityUrls;

		//CourseIds that we have so far are the ones that are part of result from solr (filtered courses)
		//To find all courses of an institute, direct or indirect, we hit the query
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

		$this->benchmark->mark('Set_Display_Object_end');
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Prepared filters | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$this->benchmark->mark('Banners_and_recommendation_start');

		global $criteriaArray;
		$criteriaArray = $this->_getBannerCriteria($this->request);
		$data['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds, $finalCourseResult, array(), $data['institutes']['instituteData']);
		unset($finalCourseResult);
		$data['questionsCountCombined'] = $this->anaRecomLib->getRecommendedCourseQuestionsCount($finalCourseIds);
		$data['keyword'] = !empty($criteriaArray['nickname']) ? $criteriaArray['nickname'] : 'Empty NickName';
		$data['totalPages'] = ceil($data['totalInstituteCount']/$this->request->getPageLimit());
		$data['currentPage'] = $this->request->getPageNumber();
		// Redirect to 1st page if number out of range
		if($data['currentPage'] > $data['totalPages'] && $data['totalPages'] > 0){
			$urlRedirect = $this->request->getUrlForPagination(1);
		  	redirect($urlRedirect,'location',301);
			exit;
		}
		$data['showGutterBanner'] = $this->request->showGutterBanner();

		$data['isMBAPGDMPage'] = $this->_isMBAPGDMPage($this->request);
		$data['trackForPages'] = true;
		$data['cityFromRequest'] = $this->request->getCity();
		$data['stateFromRequest'] = $this->request->getState();

		// Banners Display Index
		$data['iimPredictorBannerIndex'] = 8;
		if($data['institutes']['instituteCountInCurrentPage'] < $data['iimPredictorBannerIndex']){
	    	$data['iimPredictorBannerIndex'] = $data['institutes']['instituteCountInCurrentPage'];
	    }

		// Max Number of pages in pagination
		$data['maxPagesOnPaginitionDisplay'] = 10;
		$urlWithoutPageNo = $data['urlPrefix'] = $this->request->getCurrentUrlWithoutPageNo();
		$topInstitute = reset($data['institutes']['instituteData']);
		$data['topInstitute'] = 0;
		if(!empty($topInstitute)){
			$data['topInstitute'] = $topInstitute->getId();
			unset($topInstitute);
		}
		
		$this->_beaconTrackData($data, $this->request);
		$data['tupleListSource'] = "categoryPage";
		$data['validateuser'] = $this->userStatus;
		$data['isAjax'] = ($_SERVER['HTTP_X_REQUESTED_WITH'] != '') ? 1 : 0;

		$this->benchmark->mark('Banners_and_recommendation_end');

		$this->benchmark->mark('Aggregate_reviews_start');
		$collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
		$data['aggregateReviewsData'] = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');
		$this->benchmark->mark('Aggregate_reviews_end');
		//_p($data['aggregateReviewsData']);die;

		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Prepared banners and recommendation | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage(); 

		$this->benchmark->mark('Tracking_start');
	
		// SEO DATA
		$data['metadata']['canonical'] = $this->request->getCanonicalUrl();
		// $data['metadata']['title'] = "Page ".$data['currentPage'].": ".$this->request->getTitle();
		$data['metadata']['title'] = $this->request->getTitle();

		$data['metadata']['description'] = ($data['currentPage'] > 1) ? "Page ".$data['currentPage'].": ".$this->request->getDescription(): $this->request->getDescription();
		$data['breadcrumb'] = $this->request->getBreadcrumb();
		$data['heading'] = $this->request->getHeading();
		$data['h2Text'] = $this->request->getH2Text();
		$data['isLocationCTP'] = $this->request->isLocationCTP();
		if($data['currentPage'] > 1){
			$prevPage = $data['currentPage'] - 1;
			if($prevPage == "1"){
				$data['metadata']['previousURL'] = $urlWithoutPageNo;
			}else{
				$data['metadata']['previousURL'] = $urlWithoutPageNo."-".$prevPage;
			}
			
		}
		
		if($data['currentPage'] == 1){
			global $randomSolrTrackingKey;
			error_log("===CTPG ORDER RECAT===".$data['metadata']['canonical']."====".print_r($instituteIdsArray,true)."===".getVisitorSessionId()."====".$randomSolrTrackingKey);	
		}
		
		if($data['currentPage'] < $data['totalPages']){
			$nextPage = $data['currentPage'] + 1;
			$data['metadata']['nextURL'] = $urlWithoutPageNo."-".$nextPage;
		}
		// SEO DATA END

		if(DO_SEARCHPAGE_TRACKING){
			if(empty($data['isAjax'])){
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
					$temp = array('stream'=>'getStream','substream'=>'getSubstream','specialization'=>'getSpecialization','baseCourse'=>'getBaseCourse','credential'=>'getCredential','educationType'=>'getEducationType','deliveryMethod'=>'getDeliveryMethod','exams'=>'getExam');
					$trackData = array();
					foreach ($temp as $key => $method) {
						$trackData[$key] = call_user_func(array($this->request,$method));
					}
					$trackData['searchKeyword'] = $data['keyword'];
					$trackData['page'] = 'category';
					$trackData['resultCount'] = $data['totalInstituteCount'];
					$trackData['userId'] = ($this->userid > 0) ? $this->userid : NULL;
					$data['trackingSearchId'] = $this->trackmodel->getTrackingIdForCourse($trackData);
					$this->request->setTrackingSearchId($data['trackingSearchId']);
				}
			}
			else{
				if($this->request->getRequestFrom() == 'filters'){
					$data['trackingFilterId'] = $this->trackmodel->trackFilterClick($this->request);
					$data['trackingSearchId'] = $this->request->getTrackingSearchId();
					$this->request->setTrackingFilterId($data['trackingFilterId']);
				}
			}
		}

		$this->benchmark->mark('Tracking_end');
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: SEO and tracking done | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$this->benchmark->mark('MMP_and_Online_form_data_start');
		
		$data['pageNumber'] = $this->request->getPageNumber() ? $this->request->getPageNumber() : 1;
		// SEO LAYER FOR Registration
		$isLoggedIn = true;
		if($this->userStatus == "false"){
			$isLoggedIn = false;
		}
		
		/*if($data['isMBAPGDMPage']){
			$mmpType = 'newmmpcategory';
			$mmpData = $this->customizedMMPLib->seoMMPLayerFromOrganicTraffic($mmpType, $isLoggedIn);	
		}*/
		
		$data['genericPaginationURL'] = $this->request->getUrlForPagination("#page_no#");
		
		if(!empty($mmpData)){
			global $mbaBaseCourse;
			$data['mbaBaseCourse'] = $mbaBaseCourse;
			$data['mmpData'] = $mmpData;
			$res['baseCourseId'] = $mbaBaseCourse;
			$data['regFormPrefillValue'] = $this->recatCommonLib->getRegistrationFormPopulationDataByParams($res);
		}
		
		// SEO LAYER FOR Registration END

		// Apply CTA Data
		$data['onlineApplicationCoursesUrl'] = $this->onlineFormLib->getOAFBasicInfo($finalCourseIds);

		$data['applyOnlinetrackingPageKeyId']= 1038;
		$data['comparetrackingPageKeyId'] = 1039;
		$data['ebrochureTrackingId'] = 213;
		$data['shortlistTrackingId'] = 215;
		
		$this->benchmark->mark('MMP_and_Online_form_data_end');
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: MMP, Online form data | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage(); $this->benchmark->mark('Shortlist_and_compare_data_start');

		//shortLlist CTA
		$data['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$this->userid); 

		// compare CTA
		$data['alreadyComparedCourses'] = $this->cmpObj->getComparedData();

		$this->benchmark->mark('Shortlist_and_compare_data_end');
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Shortlist, compare data | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage(); $this->benchmark->mark('Interlinking_start');

		$data['product'] = "Category";
		
		if(empty($data['isAjax'])){
			$data['gtmParams'] = $this->getGTMParams($this->request);
		}

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
		
		$this->benchmark->mark('Interlinking_end');

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dfpbCourse = ($data['gtmParams']['baseCourseId'] && $data['gtmParams']['baseCourseId'][0]) ? $data['gtmParams']['baseCourseId'][0] : '';
        $dfpEdu = ($data['gtmParams']['educationType'] && $data['gtmParams']['educationType'][0]) ? $data['gtmParams']['educationType'][0] : '';
        $dfpCity = ($data['gtmParams']['cityId'] && $data['gtmParams']['cityId'][0]) ? $data['gtmParams']['cityId'][0] : '';
        $dfpState = ($data['gtmParams']['stateId'] && $data['gtmParams']['stateId'][0]) ? $data['gtmParams']['stateId'][0] : '';
        $dfpStreamId = ($data['gtmParams']['streamId'] && $data['gtmParams']['streamId'][0]) ? $data['gtmParams']['streamId'][0] : '';
        $dfpSubstreamId = ($data['gtmParams']['substreamId'] && $data['gtmParams']['substreamId'][0]) ? $data['gtmParams']['substreamId'][0] : '';
        $dfpSpec = ($data['gtmParams']['specializationId'] && $data['gtmParams']['specializationId'][0]) ? $data['gtmParams']['specializationId'][0] : '';
        $dfpDm   = ($data['gtmParams']['deliveryMethod'] && $data['gtmParams']['deliveryMethod'][0]) ? $data['gtmParams']['deliveryMethod'][0] : '';
        $dfpCre   = ($data['gtmParams']['credential'] && $data['gtmParams']['credential'][0]) ? $data['gtmParams']['credential'][0] : '';
        $dpfParam = array('parentPage'=>'DFP_CategoryPage','entity_id'=>$this->request->getCategoryPageKey(),'baseCourse'=>$dfpbCourse,'cityId'=>$dfpCity,'educationType'=>$dfpEdu,'stateId'=>$dfpState,'stream_id'=>$dfpStreamId,'substream_id'=>$dfpSubstreamId,'specialization_id'=>$dfpSpec,'deliveryMethod'=>$dfpDm,'courseCredential'=>$dfpCre);
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
		
		$this->benchmark->mark('Filter_links_start');
		$this->getFilterLinksFromFilterData($data);
		$this->benchmark->mark('Filter_links_end');

		$this->benchmark->mark('Total_page_load_without_view_end');
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Total page load without view | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);


		/*code to show website tour or not*/
		/*$tourCookie = $this->input->cookie('showWebsiteTour');
		if(empty($tourCookie)){
			$tourCookie = array();
		}
		else{
			$tourCookie = json_decode($tourCookie,true);
		}
		if(empty($tourCookie['cta'])){
			if($this->userid > 0){
				$seenTour = Modules::run('common/WebsiteTour/checkIfUserSeenTour','cta',$this->userid);
				// _p($seenTour);die;
				if($seenTour){
					$tourCookie['cta'] = 'no';
					$this->input->set_cookie('showWebsiteTour',json_encode($tourCookie),86400*365);
				}
			}
		}*/

		$data['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
		$data['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'CTPG','page'=>'DESKTOP');
		//set data for view
		$this->load->view('nationalCategoryList/categoryPageContent', $data);
		
		$this->benchmark->mark('Total_page_load_with_view_end');
		if(LOG_CL_PERFORMANCE_DATA)
			error_log("Section: Total page load with view | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
	}

	private function getFilterLinksFromFilterData(&$data){
		$appliedFilters = $data['customAppliedFilters'];
		$nationalCategoryListCache = $this->load->library('nationalCategoryList/cache/NationalCategoryListCache');
		$flag = true;
		$temp = array('stream','substream','specialization','base_course_id','education_type','delivery_method','credential','exam','city','state','sub_spec','et_dm',"review");
		foreach ($temp as $key => $filterType) {
			if(!empty($appliedFilters[$filterType]) && count($appliedFilters[$filterType]) > 1){
				$flag = false;
			}
		}
		/*city + state should be less than 1*/
		$cityCount = empty($appliedFilters['city']) ? 0 : count($appliedFilters['city']);
		$stateCount = empty($appliedFilters['state']) ? 0 : count($appliedFilters['state']);
		if(($cityCount + $stateCount) > 1){
			$flag = false;
		}
		$filters = $data['filters'];
		$fieldAlias = $data['fieldAlias'];

		$currentUrl = $this->request->getCurrentUrlWithQueryParams();
		$defaultUrlData = parse_shiksha_url_with_multiparams($currentUrl);
		if(!empty($defaultUrlData['queryParams'])){
			unset($defaultUrlData['queryParams'][$fieldAlias['trackingSearchId']]);
			unset($defaultUrlData['queryParams'][$fieldAlias['trackingFilterId']]);
			unset($defaultUrlData['queryParams'][$fieldAlias['requestFrom']]);
		}

		if($flag){
			$streamId = empty($appliedFilters['stream']) ? 0 : reset($appliedFilters['stream']);
			$substreamId = empty($appliedFilters['substream']) ? 0 : reset($appliedFilters['substream']);
			$specId = empty($appliedFilters['specialization']) ? 0 : reset($appliedFilters['specialization']);
			$baseCourseId = empty($appliedFilters['base_course']) ? 0 : reset($appliedFilters['base_course']);
			$educationType = empty($appliedFilters['education_type']) ? 0 : reset($appliedFilters['education_type']);
			$deliveryMethod = empty($appliedFilters['delivery_method']) ? 0 : reset($appliedFilters['delivery_method']);
			$credential = empty($appliedFilters['credential']) ? 0 : reset($appliedFilters['credential']);
			$examId = empty($appliedFilters['exam']) ? 0 : reset($appliedFilters['exam']);
			$cityId = empty($appliedFilters['city']) ? 1 : reset($appliedFilters['city']);
			$stateId = empty($appliedFilters['state']) ? 1 : reset($appliedFilters['state']);
			if($cityId > 1){
				$stateId = 1;
			}

			foreach ($filters['location'] as $filterType => $filterRows) {
				if($filterType != 'city' && $filterType != 'state'){
					continue;
				}
				$keyList = array();
				foreach ($filterRows as $rowIndex => $filterRow) {
					if($filterType == 'city'){
						$stringKey = $streamId.'_'.$substreamId.'_'.$specId.'_'.$baseCourseId.'_'.$educationType.'_'.$deliveryMethod.'_'.$credential.'_'.$examId.'_'.$filterRow['id'].'_1';
					}
					else{
						$stringKey = $streamId.'_'.$substreamId.'_'.$specId.'_'.$baseCourseId.'_'.$educationType.'_'.$deliveryMethod.'_'.$credential.'_'.$examId.'_1_'.$filterRow['id'];
					}
					$keyList[] = $stringKey;
				}
				if(!empty($keyList)){

					$cacheData = $nationalCategoryListCache->getNonZeroPageLinksCache($keyList);
					$cacheData = array_values($cacheData);

					$urlData = $defaultUrlData;
					$uafParams = $urlData['queryParams']['uaf'];
					if(!empty($appliedFilters[$filterType]) && !in_array('location',$uafParams)){
						$urlData['queryParams']['uaf'][] = 'location';
					}

					for ($i=0; $i < count($cacheData); $i++) { 
						$url = $cacheData[$i];
						if(empty($url)){
							$urlData['queryParams'][$fieldAlias['city']] = array();
							$urlData['queryParams'][$fieldAlias['state']] = array();
							$urlData['queryParams'][$fieldAlias[$filterType]] = array($filterRows[$i]['id']);
							$data['filters']['location'][$filterType][$i]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
						}
						else{
							$data['filters']['location'][$filterType][$i]['url'] = $url;
						}
					}
				}
			}

			$keyList = array();
			foreach ($filters['specialization'] as $rowIndex => $filterRow) {
				$stringKey = $streamId.'_'.$substreamId.'_'.$filterRow['id'].'_'.$baseCourseId.'_'.$educationType.'_'.$deliveryMethod.'_'.$credential.'_'.$examId.'_'.$cityId.'_'.$stateId;
				$keyList[] = $stringKey;
			}
			if(!empty($keyList)){
				$cacheData = $nationalCategoryListCache->getNonZeroPageLinksCache($keyList);
				$cacheData = array_values($cacheData);

				$urlData = $defaultUrlData;
				$uafParams = $urlData['queryParams']['uaf'];
				if(!empty($appliedFilters['specialization']) && !in_array('specialization',$uafParams)){
					$urlData['queryParams']['uaf'][] = 'specialization';
				}

				for ($i=0; $i < count($cacheData); $i++) { 
					$url = $cacheData[$i];
					if(empty($url)){
						$urlData['queryParams'][$fieldAlias['sub_spec']] = array();
						$urlData['queryParams'][$fieldAlias['specialization']] = array($filters['specialization'][$i]['id']);
						$data['filters']['specialization'][$i]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
					}
					else{
						$data['filters']['specialization'][$i]['url'] = $url;
					}
				}
			}

			$keyList = array();
			foreach ($filters['stream'] as $rowIndex => $filterRow) {
				$stringKey = $filterRow['id'].'_'.$substreamId.'_'.$specId.'_'.$baseCourseId.'_'.$educationType.'_'.$deliveryMethod.'_'.$credential.'_'.$examId.'_'.$cityId.'_'.$stateId;
				$keyList[] = $stringKey;
			}
			if(!empty($keyList)){
				$cacheData = $nationalCategoryListCache->getNonZeroPageLinksCache($keyList);
				$cacheData = array_values($cacheData);

				$urlData = $defaultUrlData;
				$uafParams = $urlData['queryParams']['uaf'];
				if(!empty($appliedFilters['stream']) && !in_array('stream',$uafParams)){
					$urlData['queryParams']['uaf'][] = 'stream';
				}

				for ($i=0; $i < count($cacheData); $i++) { 
					$url = $cacheData[$i];
					if(empty($url)){
						$urlData['queryParams'][$fieldAlias['stream']] = array($filters['stream'][$i]['id']);
						$data['filters']['stream'][$i]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
					}
					else{
						$data['filters']['stream'][$i]['url'] = $url;
					}
				}
			}
			//_p($data['filters']); die;

			$keyList = array();
			$mappings = array();
			$specMapping = array();
			foreach ($filters['sub_spec'] as $rowIndex => $filterRow) {
				if($filterRow['type'] == 'substream'){
					$stringKey = $streamId.'_'.$filterRow['id'].'_0_'.$baseCourseId.'_'.$educationType.'_'.$deliveryMethod.'_'.$credential.'_'.$examId.'_'.$cityId.'_'.$stateId;
					$keyList[] = $stringKey;
					$mappings[$stringKey] = array('rowIndex' => $rowIndex,'type' =>'substream');

					foreach ($filterRow['specialization'] as $specIdx => $specRow) {
						$stringKey = $streamId.'_'.$filterRow['id'].'_'.$specRow['id'].'_'.$baseCourseId.'_'.$educationType.'_'.$deliveryMethod.'_'.$credential.'_'.$examId.'_'.$cityId.'_'.$stateId;
						$keyList[] = $stringKey;
						$mappings[$stringKey] = array('rowIndex' => $rowIndex,'type' =>'specialization','specIdx' => $specIdx);
					}
				}
				else if($filterRow['type'] == 'specialization'){
					$stringKey = $streamId.'_0_'.$filterRow['id'].'_'.$baseCourseId.'_'.$educationType.'_'.$deliveryMethod.'_'.$credential.'_'.$examId.'_'.$cityId.'_'.$stateId;
					$keyList[] = $stringKey;
					$mappings[$stringKey] = array('rowIndex' => $rowIndex, 'type' => 'specialization');
				}
			}
			// _p($keyList);die;
			if(!empty($keyList)){
				$cacheData = $nationalCategoryListCache->getNonZeroPageLinksCache($keyList);

				$urlData = $defaultUrlData;
				$uafParams = $urlData['queryParams']['uaf'];
				if((!empty($appliedFilters['specialization']) || !empty($appliedFilters['substream']) || !empty($appliedFilters['sub_spec'])) && !in_array('sub_spec',$uafParams)){
					$urlData['queryParams']['uaf'][] = 'sub_spec';
				}

				foreach ($cacheData as $key => $url) {
					$rowIndex = $mappings[$key]['rowIndex'];
					$urlData['queryParams'][$fieldAlias['substream']] = array();
					$urlData['queryParams'][$fieldAlias['specialization']] = array();

					if(array_key_exists('specIdx', $mappings[$key])){
						if(empty($url)){
							$urlData['queryParams'][$fieldAlias['sub_spec']] = array($fieldAlias['substream'].'_'.$data['filters']['sub_spec'][$rowIndex]['id'].'::'.$fieldAlias['specialization'].'_'.$data['filters']['sub_spec'][$rowIndex]['specialization'][$mappings[$key]['specIdx']]['id']);
							$data['filters']['sub_spec'][$rowIndex]['specialization'][$mappings[$key]['specIdx']]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
						}
						else{
							$data['filters']['sub_spec'][$rowIndex]['specialization'][$mappings[$key]['specIdx']]['url'] = $url;
						}
					}
					else{
						if(empty($url)){
							if($mappings[$key]['type'] == 'substream'){
								$value = $fieldAlias['substream'].'_'.$data['filters']['sub_spec'][$rowIndex]['id'];
							}
							else{
								$value = $fieldAlias['specialization'].'_'.$data['filters']['sub_spec'][$rowIndex]['id'];
							}
							$urlData['queryParams'][$fieldAlias['sub_spec']] = array($value);
							$data['filters']['sub_spec'][$rowIndex]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
						}
						else{
							$data['filters']['sub_spec'][$rowIndex]['url'] = $url;
						}
					}
				}
			}
		}
		else{
			$urlData = $defaultUrlData;
			$uafParams = $urlData['queryParams']['uaf'];
			if(!empty($appliedFilters[$filterType]) && !in_array('location',$uafParams)){
				$urlData['queryParams']['uaf'][] = 'location';
			}
			foreach ($filters['location'] as $filterType => $filterRows) {
				if($filterType != 'city' && $filterType != 'state'){
					continue;
				}
				foreach ($filterRows as $rowIndex => $filterRow) {
					$urlData['queryParams'][$fieldAlias['city']] = array();
					$urlData['queryParams'][$fieldAlias['state']] = array();
					$urlData['queryParams'][$fieldAlias[$filterType]] = array($filterRow['id']);
					$data['filters']['location'][$filterType][$rowIndex]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
				}
			}

			$urlData = $defaultUrlData;
			$uafParams = $urlData['queryParams']['uaf'];
			if(!empty($appliedFilters[$filterType]) && !in_array('specialization',$uafParams)){
				$urlData['queryParams']['uaf'][] = 'specialization';
			}
			foreach ($filters['specialization'] as $rowIndex => $filterRow) {
				$urlData['queryParams'][$fieldAlias['sub_spec']] = array();
				$urlData['queryParams'][$fieldAlias['specialization']] = array($filterRow['id']);
				$data['filters']['specialization'][$rowIndex]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
			}

			$urlData = $defaultUrlData;
			$uafParams = $urlData['queryParams']['uaf'];
			if((!empty($appliedFilters['specialization']) || !empty($appliedFilters['substream']) || !empty($appliedFilters['sub_spec'])) && !in_array('sub_spec',$uafParams)){
				$urlData['queryParams']['uaf'][] = 'sub_spec';
			}
			foreach ($filters['sub_spec'] as $rowIndex => $filterRow) {
				$urlData['queryParams'][$fieldAlias['substream']] = array();
				$urlData['queryParams'][$fieldAlias['specialization']] = array();

				if($filterRow['type'] == 'substream'){
					$urlData['queryParams'][$fieldAlias['sub_spec']] = array($fieldAlias['substream'].'_'.$filterRow['id']);
					$data['filters']['sub_spec'][$rowIndex]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
				}
				else{
					$urlData['queryParams'][$fieldAlias['sub_spec']] = array($fieldAlias['specialization'].'_'.$filterRow['id']);
					$data['filters']['sub_spec'][$rowIndex]['url'] = get_shiksha_multiparamsurl_from_components($urlData);
				}
			}
		}
		//_p($data['filters']);die;
	}

	public function getGTMParams($request){
		$appliedFilters = $request->getAppliedFilters();
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

	public function loadMoreCourses() {
		$this->_initOther();
		$courseIds   = $this->input->post('courseIds');
		$instituteId = $this->input->post('instituteId');
		$displayData = array();
		
		$courseIdArr = explode(',', $courseIds);
		foreach($courseIdArr as $courseId){
			$finalCourseResult[$courseId] = array();
		}
		$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
		$eligibility  =array();
		if(!empty($courseIdArr) && !empty($courseIdArr[0])) {
			$institute = reset($this->instituteRepo->findWithCourses(array($instituteId=>$courseIdArr),array("basic","media"),array("basic","facility","eligibility","location")));
			$courses = $institute->getCourses();
			$finalCourseIds = array();
			foreach ($courses as $key=>$courseObj) {
				if(!empty($courseObj)){
					// get all exam Ids
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
					if(!empty($courseId)){
						$finalCourseIds[] = $courseId;
						$displayObject = new NationalCategoryDisplayObject();
						$displayObject->setDisplayDataObjectForLoadMore($courseObj, $data['appliedFilters']);
						$displayData['displayDataObject'][$courseId] = $displayObject;
						$finalCourseResult[$courseId] = $courseObj;
						unset($courses[$key]);
					}
				}
			}
		}

		$eligibility = array_unique($eligibility);
		$eligibilityUrls = array();
		if(count($eligibility)>0){
        	$this->examPageLib    = $this->load->library('examPages/ExamPageLib');
        	$eligibilityUrls = $this->examPageLib->getExamMainUrlsById($eligibility);
		}
		$displayData['eligibilityUrls'] = $eligibilityUrls;

		$displayData['questionsCountCombined'] = $this->anaRecomLib->getRecommendedCourseQuestionsCount($finalCourseIds);
		$displayData['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);
		//if(LOG_CL_PERFORMANCE_DATA) error_log("Section: In load more courses, load institute with courses object | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$displayData['tuplenumber'] 	   = $this->input->post('tuplenum');
		$displayData['loadedCourseCount']  = $this->input->post('loadedCourseCount');
		$displayData['tupleListSource']    = $this->input->post('tupleListSource');
		$displayData['product']   		   = $this->input->post('product');

		if($displayData['product'] == 'Category') {
			$displayData['abTestVersion']		   = $this->input->post('abTestVersion');
			switch ($displayData['abTestVersion']) {
				case 1:
					$displayData['brochureText'] = 'Download Brochure';
					break;
				
				case 2:
					$displayData['brochureText'] = 'Email Brochure';
					break;

				default:
					$displayData['brochureText'] = 'Request Brochure';
					break;
			}
		}

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		
		//if(LOG_CL_PERFORMANCE_DATA) error_log("Section: In load more courses, tracking done | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
		
		$displayData['courses']                    = array_filter(array_values($finalCourseResult));
		$displayData['institute']                  = $institute;
		$displayData['searchPageDataProcessorLib'] = $this->searchPageDataProcessor;

		// APPLY CTA
		$displayData['onlineApplicationCoursesUrl'] = $this->onlineFormLib->getOAFBasicInfo($finalCourseIds);


		if($displayData['tupleListSource'] == 'searchPage'){
			$displayData['applyOnlinetrackingPageKeyId']= 1040;
			$displayData['comparetrackingPageKeyId'] = 1041;
			$displayData['shortlistTrackingId'] = 1042;
			$displayData['ebrochureTrackingId'] = 1043;
			$displayData['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'SRP','page'=>'DESKTOP');
		}
		else{
			$displayData['applyOnlinetrackingPageKeyId']= 1038;
			$displayData['comparetrackingPageKeyId'] = 1039;
			$displayData['ebrochureTrackingId'] = 213;
			$displayData['shortlistTrackingId'] = 215;
			$displayData['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'CTPG','page'=>'DESKTOP');
		}

		//shortLlist CTA
		$displayData['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$this->userid); 

		// compare CTA
		$displayData['alreadyComparedCourses'] = $this->cmpObj->getComparedData();
		

		$displayData['validateuser']               = $this->checkUserValidation();		
		$displayData['recommendationsApplied'] = isset($_COOKIE['recommendation_applied'])?explode(',',$_COOKIE['recommendation_applied']):array();

		$collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
		$displayData['aggregateReviewsData'] = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');
		//_p($displayData['aggregateReviewsData']);
		

		if(DO_SEARCHPAGE_TRACKING){
			$trackingFilterId = $this->input->post('trackingFilterId');
			if(!empty($trackingFilterId)){
				$displayData['trackingFilterId'] = $trackingFilterId;
			}
			$trackingSearchId = $this->input->post('trackingSearchId');
			if(!empty($trackingSearchId)){
				$displayData['trackingSearchId'] = $trackingSearchId;
			}
		}
		$displayData['pageNumber'] = $this->input->post('pagenum');
		$displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');

		echo json_encode(array('html'=>$this->load->view('common/gridTuple/expandedTupleContent', $displayData, true)));
	}

	public function fetchEBrochureDownloadRecom($showRecom="true"){
		$this->_initOther();

		$courseId = $this->input->post('courseId', true);

		if(empty($courseId) || $courseId == 'undefined' ||  !(preg_match('/^\d+$/',$courseId))) {
			exit;
		}
		$courseName = htmlentities($this->input->post('courseName',true));
		$mainPageName = $this->input->post('page',true);
		$extraDivParam = $this->input->post('extraDivParam', true);
		$ebrochureTrackingId = !empty($_POST['dBrochureRecoId'])?$this->input->post('dBrochureRecoId',true):'';
		$comparetrackingPageKeyId = !empty($_POST['compareRecoId'])?$this->input->post('compareRecoId',true):'';
		$applyOnlinetrackingPageKeyId = !empty($_POST['applyNowRecoId'])?$this->input->post('applyNowRecoId',true):'';
		$shortlistTrackingId = !empty($_POST['shortlistRecoId'])?$this->input->post('shortlistRecoId',true):'';
		$action = $this->input->post('action',true);
		$results  = array();
		if($showRecom == "true"){
			$results = $this->alsoviewed->getAlsoViewedCourses(array($courseId), 12);
		}
		
		$formattedResults = array();
		foreach ($results as $key => $value) {
			$formattedResults[$value['instituteId']][] = $value['courseId'];
		}
		$institutesData = array();
		if(!empty($formattedResults) && $showRecom == "true"){
			$institutesData = $this->instituteRepo->findWithCourses($formattedResults,array('basic','facility','media','location'),array('basic','location','eligibility'));
		}
		
		$data = array();
		$data['zeroRecommendations'] = false;
		if(empty($results)){
			$data['zeroRecommendations'] = true;
		}
		if(!$showRecom == "true"){
			$data['zeroRecommendations'] = true;
		}
		$data['institutes']['instituteData'] = $institutesData;
		$data['institutes']['totalInstituteCount'] = count($institutesData);
		$data['tupleListSource'] = "ebochureCallback";
		$data['courseName'] = $courseName;
		$data['product'] = "ebochureCallback";
		$data['extraDivParam'] = $extraDivParam;
		$data['action'] = $action;

		global $COURSE_MESSAGE_KEY_MAPPING;
		global $MESSAGE_MAPPING;
		if(!empty($COURSE_MESSAGE_KEY_MAPPING[$courseId])){
			$data['customMsgText'] = $MESSAGE_MAPPING[$COURSE_MESSAGE_KEY_MAPPING[$courseId]]['onSiteDesktop'];
		}

		// $data['shortlistRecoId'] = $shortlistRecoId;
		// $data['dBrochureRecoId'] = $dBrochureRecoId;
		// $data['compareRecoId'] = $compareRecoId;
		$finalCourseIds = array();
		$eligibility  =array();
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
                if(empty($courseId)){
                    continue;
                }
				$finalCourseIds[] = $courseId;
				unset($courseObj);
				$displayObject = new NationalCategoryDisplayObject();
				$displayObject->setDisplayDataObject($instituteObj);
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
			$this->instituteDetailLib = $this->load->library('nationalInstitute/instituteDetailLib');
			$instituteCourses = $this->instituteDetailLib->getAllCoursesForMultipleInstitutes($instituteIdsArray);
			foreach ($instituteCourses as $instituteId => $value) {
				$locationsMappedToCourses = $this->institutedetailsmodel->getUniqueCoursesLocations($value['courseIds']);
				$data['isInstituteMultilocation'][$instituteId] = count($locationsMappedToCourses) > 1 ? true : false;
		    	if($data['isInstituteMultilocation'][$instituteId]) {
		    		$data['isInstituteMultilocation'][$instituteId] = $data['institutes']['instituteData'][$instituteId]->getURL()."?showAllBranches=1";
		    	}
			}
		}

		// Apply CTA Data
		$data['onlineApplicationCoursesUrl'] = $this->onlineFormLib->getOAFBasicInfo($finalCourseIds);
		$data['questionsCountCombined'] = $this->anaRecomLib->getRecommendedCourseQuestionsCount($finalCourseIds);
		$data['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);
		// $data['applyOnlinetrackingPageKeyId']= !empty($applyNowRecoId)?$applyNowRecoId:775;
		
		//shortLlist CTA
		$data['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$this->userid); 
		// compare CTA
		$data['alreadyComparedCourses'] = $this->cmpObj->getComparedData();

		switch ($mainPageName) {
			case 'ND_SERP':
			case 'searchPage':
				$data['comparetrackingPageKeyId'] = 1045;
				$data['applyOnlinetrackingPageKeyId']= 1046;
				$data['ebrochureTrackingId'] = 1044;
				$data['shortlistTrackingId'] = 1047;
				break;

			case 'ND_Category':
			case 'categoryPage':
				$data['comparetrackingPageKeyId'] = 1036;
				$data['applyOnlinetrackingPageKeyId']= 1037;
				$data['ebrochureTrackingId'] = 214;
				$data['shortlistTrackingId'] = 216;
				break;

			case 'ND_Ranking':
			case 'rankingPage':
				$data['comparetrackingPageKeyId'] = 1106;
				$data['applyOnlinetrackingPageKeyId']= 1107;
				$data['ebrochureTrackingId'] = 1104;
				$data['shortlistTrackingId'] = 1105;
				break;

			case 'ND_ALL_COURSES_PAGE':
			case 'AllCoursesPage':
				$data['comparetrackingPageKeyId'] = 2455;
				$data['applyOnlinetrackingPageKeyId']= 1025;
				$data['ebrochureTrackingId'] = 2459;
				$data['shortlistTrackingId'] = 2457;
				break;

			case 'ND_CourseListing':
				$data['comparetrackingPageKeyId'] = 1012;
				$data['applyOnlinetrackingPageKeyId']= 1013;
				$data['ebrochureTrackingId'] = 1011;
				$data['shortlistTrackingId'] = 1014;
				break;

			case 'ND_InstituteDetailPage':
				$data['comparetrackingPageKeyId'] = 996;
				$data['applyOnlinetrackingPageKeyId']= 998;
				$data['ebrochureTrackingId'] = 995;
				$data['shortlistTrackingId'] = 997;
				break;

			case 'ND_UniversityDetailPage':
				$data['comparetrackingPageKeyId'] = 1001;
				$data['applyOnlinetrackingPageKeyId']= 1006;
				$data['ebrochureTrackingId'] = 1005;
				$data['shortlistTrackingId'] = 1009;
				break;

			case 'ND_AllContentPage_Questions':
				$data['comparetrackingPageKeyId'] = 1031;
				$data['applyOnlinetrackingPageKeyId']= 1028;
				$data['ebrochureTrackingId'] = 1030;
				$data['shortlistTrackingId'] = 1032;
				break;

			case 'ND_AllContentPage_Reviews':
				$data['comparetrackingPageKeyId'] = 1016;
				$data['applyOnlinetrackingPageKeyId']= 1017;
				$data['ebrochureTrackingId'] = 1015;
				$data['shortlistTrackingId'] = 1018;
				break;

			case 'ND_AllContentPage_Articles':
				$data['comparetrackingPageKeyId'] = 1020;
				$data['applyOnlinetrackingPageKeyId']= 1027;
				$data['ebrochureTrackingId'] = 1019;
				$data['shortlistTrackingId'] = 1022;
				break;

			case 'placementPage':
			case 'ND_AllContentPage_Placement':
				$data['comparetrackingPageKeyId'] = 3263;
				$data['applyOnlinetrackingPageKeyId']= 3265;
				$data['ebrochureTrackingId'] = 3265;
				$data['shortlistTrackingId'] = 3261;
				break;

			case 'ND_AllContentPage_Admission':
			case 'admissionPage':
			case 'ND_AdmissionPage':
				$data['comparetrackingPageKeyId'] = 1034;
				$data['applyOnlinetrackingPageKeyId']= 1029;
				$data['ebrochureTrackingId'] = 1033;
				$data['shortlistTrackingId'] = 1035;
				break; 
			case 'ND_AllContentPage_Scholarships':
				$data['comparetrackingPageKeyId'] = 1593;
				$data['applyOnlinetrackingPageKeyId']= 1597;
				$data['ebrochureTrackingId'] = 1591;
				$data['shortlistTrackingId'] = 1595;
				break;
			case 'articleDetailPage':
				$data['comparetrackingPageKeyId'] = 1269;
				$data['applyOnlinetrackingPageKeyId']= 1274;
				$data['ebrochureTrackingId'] = 1268;
				$data['shortlistTrackingId'] = 1267;
				break;
			case 'entityWidget_articleDetailPage':
				$data['comparetrackingPageKeyId'] = 1280;
				$data['applyOnlinetrackingPageKeyId']= 1281;
				$data['ebrochureTrackingId'] = 1279;
				$data['shortlistTrackingId'] = 1278;
				break;
			case 'entityWidget_questionDetailPage':
				$data['comparetrackingPageKeyId'] = 1375;
				$data['applyOnlinetrackingPageKeyId']= 1376;
				$data['ebrochureTrackingId'] = 1373;
				$data['shortlistTrackingId'] = 1374;
				break;
			case 'courseHomePage':
				$data['comparetrackingPageKeyId'] = 1961;
				$data['applyOnlinetrackingPageKeyId']= 1963;
				$data['ebrochureTrackingId'] = 1957;
				$data['shortlistTrackingId'] = 1959;
				break;
			case 'iimCallPredictor':
				$data['comparetrackingPageKeyId'] = 2015;
				$data['applyOnlinetrackingPageKeyId']= 1963;
				$data['ebrochureTrackingId'] = 2011;
				$data['shortlistTrackingId'] = 2013;
				break;
			default:
				$data['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
				$data['applyOnlinetrackingPageKeyId']= $applyOnlinetrackingPageKeyId;
				$data['ebrochureTrackingId'] = $ebrochureTrackingId;
				$data['shortlistTrackingId'] = $shortlistTrackingId;	
				break;
		}
		$data['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'RECO','page'=>'DESKTOP');

		$collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
		$data['aggregateReviewsData'] = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');
		 
		
        $data['mainPageName'] = $mainPageName;
		$this->load->view('nationalCategoryList/ebochureCallback',$data);
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
			$cptgNickName = trim($result['criterion_name']);
			$ctpgId = $result['criterion_id'];
		}

		// City for Banners, If Main City(one in request is there in filters)
		// then use this else use any one from filters else use 1(all India)
		$cityForBanners = 1;
		$cityFromRequest = $request->getCity();
		$appliedFilters = $request->getCustomAppliedFilters();
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
		$stateSuffix = "";
		
		if($cityForBanners == 1){
			if(empty($stateForBanner)) {
				$stateForBanner = 1;
			}
			$stateKeywordPrefix = "BMS_STATE_".$stateForBanner."_";
			$cptgNickName = "CRITERIA";
			$stateSuffix = "_CTPG";
		}

		$countryForBanners = 2;
		$finalkeyword = $stateKeywordPrefix.rtrim(strtoupper(preg_replace("/[^a-z0-9_]+/i", "_",$cptgNickName)),"_")."_".$ctpgId.$stateSuffix;

		if($finalkeyword == "ANIMATION_8"){
			$finalkeyword = "ANIMATION_8_STREAM";
		}

		$bannerCriteria = array(
					'country' => $countryForBanners,
					'city' => $cityForBanners,
					'nickname' => $cptgNickName,
					'keyword' => $finalkeyword
			);
		
		return $bannerCriteria;
	}

	private function _isMBAPGDMPage($request){
		global $mbaBaseCourse;
		$baseCourseFromRequest = $request->getBaseCourse();

		if($baseCourseFromRequest == $mbaBaseCourse){
			return true;
		}
		$appliedFilters = $request->getAppliedFilters();
		$baseCourseFromFIlters = $appliedFilters['base_course'];
		if(in_array($mbaBaseCourse, $baseCourseFromFIlters)){
			return true;
		}
		return false;
	}

	public function getShoskele(){
		die();
		$this->_initOther();
		
		$topInstitute = $this->input->post('instituteId');
		if(empty($topInstitute)) return;
		$appliedFilters = (array)json_decode($this->input->post('appliedFilters'));
		$cityFromRequest = $this->input->post('cityFromRequest');
		$stateFromRequest = $this->input->post('stateFromRequest');
		$topInstituteObj = $this->instituteRepo->find($topInstitute);
		if(!empty($topInstituteObj)){
			// Fetch the Top Institute
			$topInstituteId = $topInstituteObj->getId();
			// Fetch the banners for top institute
			$shoshkeleBanners = $this->bannersLib->findCTPGShoskele($topInstituteId, $appliedFilters, $cityFromRequest, $stateFromRequest);
			if(!empty($shoshkeleBanners)){
				$data['displayBanner'] = $shoshkeleBanners['bannerurl'];
			}
		}
		$this->load->view('nationalCategoryList/shoshkeleBanner',$data);
	}

	public function getTopRightGutterBanners() {
		$topBannerProperties = array();
		$topBannerProperties['pageId'] = $this->input->post('topPageId');
		$topBannerProperties['pageZone'] = $this->input->post('topPageZone');
		$topBannerProperties['shikshaCriteria'] = (array)json_decode($this->input->post('topRightShikshaCriteria'));

		$rightBannerProperties = array();
		$rightBannerProperties['pageId'] = $this->input->post('rightPageId');
		$rightBannerProperties['pageZone'] = $this->input->post('rightPageZone');
		$rightBannerProperties['shikshaCriteria'] = (array)json_decode($this->input->post('topRightShikshaCriteria'));

		$banners['topBanner'] = $this->load->view('common/banner', $topBannerProperties, TRUE);
		$banners['rightBanner'] = $this->load->view('common/banner', $rightBannerProperties, TRUE);
		echo json_encode($banners);
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

	public function performanceAnalysis() {
		$handle = fopen(LOG_CL_PERFORMANCE_DATA_FILE_NAME, "r");
	    $parentArr = array();
	    if ($handle) {
			while (($line = fgets($handle)) !== false) {
			    $line = trim($line);
			    if(!$line)
					continue;
			    
			    $line = explode("|",$line);
			    
			    $arr = array();
			    foreach($line as $row) {
					$row = explode(":", $row);
					$arr[trim($row[0])] = trim($row[1]);
			    }
			    
			    if($line)
					$parentArr[$arr['Section']][] = $arr;
			}
	    }
	    else {
			_p("Error opening file");
	    }

        echo "<style type='text/css'>
	    body {background:#eee; margin:0; padding:0; font:normal 14px arial;}
	    table {border-left:1px solid #ccc; border-top:1px solid #ccc;}
	    td {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:8px 5px; font-size:13px;}
	    th {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:5px; text-align:left; background:#f6f6f6; font-size:13px;}
	    h1 {font-size:30px; margin-top: 10px;}
	    a {text-decoration:none; color:#444;}
	    #overlay {
	                position: fixed;
	                top: 0;
	                left: 0;
	                width: 100%;
	                height: 100%;
	                background-color: #000;
	                filter:alpha(opacity=50);
	                -moz-opacity:0.5;
	                -khtml-opacity: 0.5;
	                opacity: 0.3;
	                z-index: 10;
	            }
		</style>";
		   
	    echo "<table width='1000' cellspacing='0' cellpadding='0'><tbody><tr><th>#</th><th> Function </th><th>No. of request</th><th>Avg time (MS)</th><th>Avg memory used (MB)</th><th>Memory limit, allocated (MB)</th></tr>";

		$count = 1;
	    foreach($parentArr as $key=>$row)
	    {
			$totalTime = 0;
			foreach($row as $typeRow)
			{
			    $totalTime += $typeRow['Time taken'];
			    $totalMemUsed += $typeRow['Memory used'];
			    $totalMemAllocated += $typeRow['Memory limit (allocated)'];
			}
			echo "<tr>
		    <td valign='top'>".$count++."</td>
		    <td valign='top'>$key</td>
		    <td valign='top'>".count($row)."</td>
		    <td valign='top'>".round($totalTime/count($row), 4) ."</td>
		    <td valign='top'>".round($totalMemUsed/count($row), 4) ."</td>
		    <td valign='top'>".round($totalMemAllocated/count($row), 4) ."</td>
		 	</tr>";
			//_p("Total time for ".$key." is ".$totalTime." for ".count($row)." entries. Average = ".($totalTime/count($row)) . " sec ==> " . ($totalTime/count($row)*1000) . " ms" );
	    }
	    echo "</tbody></table>";
		
	    fclose($handle);
	}

	public function solrPerformanceAnalysis($bucket = 1) {
		//_p($bucket);
		$handle = fopen("/tmp/log_solr_query_time_".$bucket.".log", "r");
	    $parentArr = array();
	    if ($handle) {
			echo "<style type='text/css'>
		    body {background:#eee; margin:0; padding:0; font:normal 14px arial;}
		    table {border-left:1px solid #ccc; border-top:1px solid #ccc;width:900px;}
		    td {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:8px 5px; font-size:13px;word-break:initial;}
		    th {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:5px; text-align:left; background:#f6f6f6; font-size:13px;word-break:break-all;min-width:110px;}
		    h1 {font-size:30px; margin-top: 10px;}
		    a {text-decoration:none; color:#444;}
		    #overlay {
		                position: fixed;
		                top: 0;
		                left: 0;
		                width: 100%;
		                height: 100%;
		                background-color: #000;
		                filter:alpha(opacity=50);
		                -moz-opacity:0.5;
		                -khtml-opacity: 0.5;
		                opacity: 0.3;
		                z-index: 10;
		            }
			</style>";
			   
		    echo "<table width='1000' cellspacing='0' cellpadding='0'><tbody><tr><th>#</th><th> Time </th><th>Page</th><th>Query</th><th>Avg time (MS)</th></tr>";

		    $count = 0;
			while (($line = fgets($handle)) !== false) {
				$count++;
			    $line = trim($line);
			    if(!$line)
					continue;
			    
			    $line = explode(" | ",$line);
			    
			    $arr = array();
			    foreach($line as $row) {
					$row = explode(": ", $row);
					$arr[trim($row[0])] = trim($row[1]);
			    }

			    echo "<tr>
				<td valign='top'>".$count."</td>
			    <td valign='top'>".$arr["Date"]."</td>
			    <td valign='top'>".$arr["Page"]."</td>
			    <td valign='top'>".$arr["Query"]."</td>
			    <td valign='top'>".$arr["Time taken"] ."</td>
			    </tr>";
			}
		   
			// $count = 1;
		 //    foreach($parentArr as $key=>$row)
		 //    {
			// 	$totalTime = 0;
			// 	foreach($row as $typeRow)
			// 	{
			// 	    $totalTime += $typeRow['Time taken'];
			// 	    $totalMemUsed += $typeRow['Memory used'];
			// 	    $totalMemAllocated += $typeRow['Memory limit (allocated)'];
			// 	}
			// 	echo "<tr>
			//     <td valign='top'>".$count++."</td>
			//     <td valign='top'>$key</td>
			//     <td valign='top'>".count($row)."</td>
			//     <td valign='top'>".round($totalTime/count($row), 4) ."</td>
			//     <td valign='top'>".round($totalMemUsed/count($row), 4) ."</td>
			//     <td valign='top'>".round($totalMemAllocated/count($row), 4) ."</td>
			//  	</tr>";
			// 	//_p("Total time for ".$key." is ".$totalTime." for ".count($row)." entries. Average = ".($totalTime/count($row)) . " sec ==> " . ($totalTime/count($row)*1000) . " ms" );
		 //    }
		    echo "</tbody></table>";
			
		    fclose($handle);
	    }
	    else {
			_p("Error opening file");
	    }

	    // _p($arr);
	    // _p($parentArr);
	}

	public function solrLogInDb($server) {
		die;
		$handle = fopen("/home/nikita/Desktop/Search/Performance/AppMonitor/SolrQueryLogsProduction/20181204/logs_".$server, "r");
		$count = 0;
		if ($handle) {
			$arr = array();
			while (($line = fgets($handle)) !== false) {
				$line = trim($line);
			    if(!$line)
					continue;
				$count++;
			    $line = explode(" | ",$line);
			    
			    foreach($line as $key => $row) {
			    	if($key == 0) {
			    		$referrer = $this->get_string_between($row, "referer", "] [TransactionId");
			    		$transactionId = $this->get_string_between($row, "TransactionId", "] SLOWPAGES");
			    		$arr[$count]['referrer'] = trim($referrer);
			    		$arr[$count]['transactionId'] = trim($transactionId);
			    		continue;
			    	}

					$row = explode(": ", $row);
					if($row[0] == "Time taken") {
						$row[1] = substr($row[1], 0, strpos($row[1], ' ms'));
					}
					if($row[0] == "Query") {
						$solrQuery = explode("/",$row[1]);
						
						$arr[$count]['solrServer'] = $solrQuery['2'];
						if(strpos($solrQuery['4'], '?') > 0) {
							$solrQuery['5'] = $solrQuery['4'];
							$solrQuery['4'] = '';
						}
						$arr[$count]['collection'] = $solrQuery['4'];
						
						$solrSubQuery = explode("?",$solrQuery[5]);
						$arr[$count]['handler'] = $solrSubQuery['0'];
					}
					if($row[0] == "Server") {
						$row[1] = $server;
					}
					$arr[$count][trim($row[0])] = trim($row[1]);
			    }
			}
		}
		//_p($arr); die;

		$this->model = $this->load->model('categorypagemodel');
		$this->model->storeSolrQueryDataInDb($arr);
	}

	function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public function solrLogsDisplay() {
    	$this->load->view("logs/solrLogs");
    }

    public function solrLogsByQueryDisplay() {
    	$this->load->view("logs/solrLogsByQuery");
    }

    public function fetchClientCoursesForCriteria($criteria) {
    	$criteria[0]['stream'] = 2;
    	$criteria[0]['base_course'] = 10;
    	$criteria[0]['city'] = 278;
    	$criteria[0]['state'] = 106;

    	$criteria[1]['stream'] = 1;
    	$criteria[1]['city'] = 278;

    	$criteria[2]['stream'] = 1;
    	$criteria[2]['base_course'] = 101;

    	$criteria[3]['base_course'] = 101;
    	$criteria[3]['city'] = 10;

    	$this->load->library('nationalCategoryList/NationalCategoryPageLib');
		$this->nationalCategoryPageLib = new NationalCategoryPageLib;

		$courseIds = $this->nationalCategoryPageLib->fetchClientCoursesForCriteria($criteria);
		_p($courseIds);
    }

    public function testAPI() {
    	ini_set('memory_limit', '2048M');

    	$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();

    	$time_start = microtime_float(); $start_memory = memory_get_usage();
    	$stream = 1;
		while($stream<=20){
		  	$base_course = 1;
		  	while($base_course<=200){
		   		$state = 1;
		   		while($state<=150){
		    		$value = array();
				    $value["stream"] = $stream;
				    $value["base_course"] = $base_course;
				    $value["state"]  = $state;

				    $uniqueProfiles[] = $value;
				    $state++;
		    		// /_P($uniqueProfiles);die;
		   		}
		   		$base_course++;
		  	}
		  	$stream++;
		}
		// _p($uniqueProfiles); die;
		error_log("Section: Profiles created - total | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, "/tmp/clientCoursePerformance.log");

		$this->load->library('nationalCategoryList/NationalCategoryPageLib');
		$this->nationalCategoryPageLib = new NationalCategoryPageLib;

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$courseIds = $this->nationalCategoryPageLib->fetchClientCoursesForCriteria($uniqueProfiles);
		error_log("Section: Data fetched - total | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, "/tmp/clientCoursePerformance.log");

		error_log("Section: Cron ended - total | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, "/tmp/clientCoursePerformance.log");
		_p($courseIds);
    }
}
