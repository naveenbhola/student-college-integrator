<?php 

class AllCoursesPageMobile extends ShikshaMobileWebSite_Controller {

	public function __construct(){
		parent::__construct();
		$this->userStatus = $this->checkUserValidation();
        if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid=$this->userStatus[0]['userid'];
        } else {
            $this->userid=-1;
        }
		$this->load->library('msearch5/msearchDisplayObject');
		$this->load->helper('listingCommon/listingcommon');
		$this->load->helper('html');
		$this->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();

	    $this->load->config("nationalCategoryList/nationalConfig");
	    $this->load->config('CollegeReviewForm/collegeReviewConfig'); 
	    $this->fieldAlias = $this->config->item('FIELD_ALIAS');

	    // get institute repository with all dependencies loaded
	    $this->instituteRepository = $instituteBuilder->getInstituteRepository();


		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
		}
		$this->cmpObj = $this->load->library('comparePage/comparePageLib');
    	$this->load->helper('image');

    	$this->load->library('nationalCourse/CourseDetailLib');
		$this->courseDetailLib = new CourseDetailLib;

	}

	private function _init($selectedFilter){
		
		$this->allCoursesPageLib = $this->load->library('nationalCategoryList/AllCoursesPageLib');
		$selectedFilter = $this->allCoursesPageLib->parseSelectedFilter($selectedFilter);
		$this->pageDfpType = "";
        if(array_key_exists('base_course', $selectedFilter))
        {
        	$this->pageDfpType = 'DFP_BIP';
        }
        elseif(array_key_exists('stream', $selectedFilter))
        {
        	$this->pageDfpType = 'DFP_SIP';	
        }
        else
        {
        	$this->pageDfpType = 'DFP_ALL_COURSES_PAGE';	
        }

		$this->load->builder('SearchBuilderV3','search');
		$this->searchBuilderV3 = new SearchBuilderV3($selectedFilter);
		$this->allCoursesRepository = $this->searchBuilderV3->getAllCoursesRepository();

		$this->request        = $this->searchBuilderV3->getRequest();
		$this->load->library('ContentRecommendation/AnARecommendationLib');
		$this->anaRecomLib = new AnARecommendationLib;

		$this->load->library('ContentRecommendation/ArticleRecommendationLib');
		$this->articleRecomLib = new ArticleRecommendationLib;

		$this->load->library('ContentRecommendation/ReviewRecommendationLib');
		$this->reviewRecomLib = new ReviewRecommendationLib;
	}

	public function index($instituteId, $selectedFilter = null){
		$this->logFilePath = "/tmp/all_courses_404.log";
		$this->_init($selectedFilter);
		
		if(empty($instituteId)) {
			error_log("Invalid instituteId Mobile: ".$instituteId."...\n", 3, $this->logFilePath);
			show_404();
		}
		$this->_init($selectedFilter);

		if(!empty($selectedFilter)) {
 			$isChildHierarchyPage = 1;
 		}

		$instituteObj = $this->instituteRepository->find($instituteId,array('scholarship'));
		
		if(empty($instituteObj)) {
			error_log("Invalid instituteId Mobile: ".$instituteId."...\n", 3, $this->logFilePath);
			show_404();
		}
		else{
			$instituteIdFromObject = $instituteObj->getId();
			if(empty($instituteIdFromObject)){
				error_log("Invalid instituteId from Object Mobile: ".$instituteId."...\n", 3, $this->logFilePath);
				show_404();
			}
		}
		
		$urlWithoutPageNo = $this->request->getCurrentUrlWithoutPageNo();
		$this->_validateURL($instituteObj,$urlWithoutPageNo, $isChildHierarchyPage, $selectedFilter);

		//get data from solr and load their objects
		$searchRepository = $this->searchBuilderV3->getSearchRepository();
		$data = $this->allCoursesRepository->getFiltersAndCourses($instituteId);
		
		$data['appliedFilters'] = $this->request->getAppliedFilters();
		$data['requestFrom']     = $this->request->getRequestFrom();
		$data['urlPrefix'] = $urlWithoutPageNo;

		$fieldAlias        		 = $this->fieldAlias;
		$combinedFilters 		 = $this->config->item('COMBINED_FILTERS');
		$filtersPossible 		 = $this->config->item('FILTERS_POSSIBLE');

		$data['filtersPossible'] = json_encode($filtersPossible);		

		//hide 'college offered by' filter in case of institute
		$instituteType = $instituteObj->getType();
		if($instituteType == 'institute') {
			unset($data['filters']['offered_by_college']);
		}

		$data['instituteObj'] = $instituteObj;
		$data['validateuser'] = $this->userStatus;
		$data['isAjax'] = ($_SERVER['HTTP_X_REQUESTED_WITH'] != '') ? 1 : 0;
		$data['trackForPages'] = true;
		

		$courses = $data['institutes']['courseData'];
		$data['totalPages']      = ceil($data['totalCourseCount']/$this->request->getPageLimit());
		$currentPageNum          = $data['currentPage'] = $this->request->getPageNumber();
		$data['pageNumber'] = $this->request->getCurrentPageNum();

		if(empty($courses) && $data['totalPages'] == 0){
			error_log("Empty Courses Mobile: ".$instituteId."...\n", 3, $this->logFilePath);
			show_404();
		}
		if($data['currentPage'] > $data['totalPages'] && $data['totalPages'] > 0){	
			$redirectUrl = $this->request->getUrlForPagination(1,$urlFetch);
		  	redirect($redirectUrl,'location',301);
		}
		$data['trackingIds']['compare'] = MOBILE_NL_ALL_COURSES_TUPLE_COMPARE;
		$data['trackingIds']['shortlist'] = MOBILE_NL_ALL_COURSES_TUPLE_SHORTLIST;
		$data['trackingIds']['downloadBrochure'] = MOBILE_NL_ALL_COURSES_TUPLE_DEB;

		$brochuresMailed = $_COOKIE['applied_courses'];
		if(empty($brochuresMailed)){
			$brochuresMailed = array();
		}else{
			$brochuresMailed = json_decode(base64_decode($brochuresMailed));
		}
		$data['brochuresMailed'] = $brochuresMailed;

		if($this->userStatus != 'false') {
			$userId = $this->userStatus[0]['userid'];
			$data['shortlistedCourses'] = Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $userId);
		}

		$data['courseCountInCurrentPage'] = count($courses);
		$data['comparedData'] = $this->cmpObj->getComparedData('mobile');
		$finalCourseIds = array();
		$eligibility  =array();
		$CourseIdsForReviews = array();
		$totalReviewCount = 0; 
		foreach ($courses as $courseObj) {
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
				if(!empty($courseId)){
					$displayObject = new msearchDisplayObject();
					$displayObject->setDisplayDataObjectForAllCoursesPage($courseObj, $data['appliedFilters']);
					$data['displayDataObject'][$courseId] = $displayObject;
					$finalCourseIds[] = $courseId;
					$reviewCount = $courseObj->getReviewCount();
					if((isset($reviewCount) && $reviewCount>=1)){
						 if($totalReviewCount<15 || count($CourseIdsForReviews)<4){
							$CourseIdsForReviews[] = $courseId;
							$totalReviewCount += $reviewCount;	
						}
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
		$data['eligibilityUrls'] = $eligibilityUrls;
		unset($courses);
		$data['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);
		foreach ($data['courseStatusData'] as $key => $value) {
			$courseStatus = implode(", ", $value['courseStatusDisplay']);
            $courseStatus = (strlen($courseStatus) > 112) ? substr($courseStatus, 0, 110).'..' : $courseStatus;
            unset($data['courseStatusData'][$key]);
            $data['courseStatusData'][$key] = $courseStatus;
		}

	    $this->_relatedLinksData($data, $instituteId, $instituteObj);
		$data['pageLimit'] = $this->request->getPageLimit();

		$filterBucketName         = $this->config->item('FILTER_NAME_MAPPING');
		$data['filterBucketName'] = json_encode($filterBucketName);
		$data['product'] = "MAllCoursesPage";
		$data['isLazyLoad'] = $this->input->getRawRequestVariable('isLazyLoad');
		$data['urlPassingAttribute']      = $this->prepareUrlNeededParams($this->request);


        $data['reviewWidget'] = modules::run('mobile_listing5/InstituteMobile/getReviewWidget',$instituteId,$instituteObj->getType(),$CourseIdsForReviews,'',false, array('getCount' => 1),$instituteObj,true);

        //Aggregate Reviews Data
       	$collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
	    $data['courseWidgetData'] = $collegeReviewLib->getAggregateReviewsForListing($CourseIdsForReviews,'course');



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
						$trackData['searchKeyword'] = $instituteId;
						$trackData['page'] = 'allCourses';
						$trackData['resultCount'] = $data['totalCourseCount'];
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

		$this->_setPaginationData($data);

		$data['isChildHierarchyPage'] = $isChildHierarchyPage;

		$this->getSeoDetails($data, $instituteObj);
		$data['genericPaginationURL'] = $this->request->getUrlForPagination("#page_no#");
		
		$data['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'ALLCOURSE','page'=>'MOBILE');
		
		if(!$data['isLazyLoad']){
			$this->_beaconTrackData($data);
		}

		if(empty($data['isAjax'])){
			$data['gtmParams'] = $this->getGTMParams($this->request,$instituteId);
		}
		if(empty($data['totalCourseCount'])){
			// Stream List for ZRP
			$data['streamsArray'] = $this->streamRepository->getAllStreams();
	        // Sort streams by name
			uasort($data['streamsArray'], function($a,$b){return strcmp($a["name"], $b["name"]);});
		}

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
        $dpfParam = array('parentPage'=>$this->pageDfpType,'pageType'=>'homepage','instituteObj'=>$data['instituteObj'],'baseCourse'=>$dfpbCourse,'cityId'=>$dfpCity,'educationType'=>$dfpEdu,'stateId'=>$dfpState,'stream_id'=>$dfpStreamId,'substream_id'=>$dfpSubstreamId,'specialization_id'=>$dfpSpec,'deliveryMethod'=>$dfpDm,'courseCredential'=>$dfpCre);

        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        

		if($data['isLazyLoad']){
			$data['currentPageNum'] = $currentPageNum;
			$result['view'] = $this->load->view('msearch5/msearchV3/mAllCoursePageBody',$data,TRUE);

			if($data['totalCourseCount'] <= ((($currentPageNum - 1) * $this->request->getPageLimit()) + $data['courseCountInCurrentPage'])) {
				$result['message'] = 'disableNextLazyLoad';
			} else {
				$result['message'] = 'ok';
			}
			echo json_encode($result);
		}else if($data['requestFrom'] == 'filterBucket'){
			echo json_encode(array('filters'=>$data['filters'],'selectedFilters'=>$data['selectedFilters'],'filterBucketName'=>$filterBucketName));
		}else{
			$this->load->view('msearch5/msearchV3/msearchPageContent',$data);
		}
		
	}

	public function getGTMParams($request,$instituteId){
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

		$gtmParams['pageType'] = 'allCoursesPage';
		$gtmParams['countryId'] = 2;
		$gtmParams['instituteId'] = $instituteId;
		if($this->userid > 0){
			$workExperience = $this->userStatus[0]['experience'];
			if(!empty($workExperience)){
				$gtmParams['workExperience'] = $workExperience;
			}
		}
		return $gtmParams;
	}

	public function loadMoreCourses(){

		
		$courseIds   = $this->input->post('courseIds');
		$instituteId = $this->input->post('instituteId');
		
		$courseIdArr = explode(',', $courseIds);
		$finalCourseIds = array();

		if(!empty($courseIdArr)) {
			$institute = reset($this->instituteRepo->findWithCourses(array($instituteId=>$courseIdArr),array("basic"),array("basic","facility","eligibility","location")));
			$courses = $institute->getCourses();


			foreach ($courses as $courseObj) {
				if(!empty($courseObj)){
					$courseId = $courseObj->getId();
					if(!empty($courseId)){
						$displayObject = new msearchDisplayObject();
						$displayObject->setDisplayDataObjectForLoadMore($courseObj, $data['appliedFilters']);
						$displayData['displayDataObject'][$courseId] = $displayObject;
						$finalCourseIds[] = $courseId;
					}
				}
			}
		}

		$displayData['tuplenumber'] 	   = $this->input->post('tuplenum');
		$displayData['loadedCourseCount']  = $this->input->post('loadedCourseCount');
		$displayData['product']   		   = $this->input->post('product');
		$displayData['courses']                    = $courses;
		$displayData['institute']                  = $institute;
		$collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
	    $courseReviewData = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');
	    $displayData['courseWidgetData'] = $courseReviewData; 


        echo json_encode(array('html'=>$this->load->view('msearch5/msearchV3/mexpandedTupleContent', $displayData, true)));
	}

	private function _validateURL($instituteObj,$url, $isChildHierarchyPage, &$selectedFilter) {
		
		// $currentUrl = $_SERVER['SCRIPT_URI'];
		$currentUrl = $url;
		$extraPageNo = "";
//		$actualScriptUrl = trim($_SERVER['SCRIPT_URI'],"/");
		$actualScriptUrl = trim(getCurrentPageURLWithoutQueryParams(),"/");
		if($currentUrl != $actualScriptUrl){
			$actualScriptUrlArr = explode("-", $actualScriptUrl);
			if(is_numeric($actualScriptUrlArr[count($actualScriptUrlArr)]-1)){
				$extraPageNo = $actualScriptUrlArr[count($actualScriptUrlArr)-1];
			}
		}
		
		$instituteId = $instituteObj->getId();
		$title = $instituteObj->getName();
		$mainLocation = $instituteObj->getMainLocation();
		if(!empty($mainLocation)) {
            $optionalArgs['cityName'] = $mainLocation->getCityName();
            $optionalArgs['localityName'] = $mainLocation->getLocalityName();
        }
        $optionalArgs['typeOfListing'] = $instituteObj->getType();
        $optionalArgs['typeOfPage'] = 'courses';
        if(!empty($isChildHierarchyPage)){
        	$optionalArgs['isChildHierarchyPage'] = 1;
        	//removing pagination number from selected filter
        	if(!empty($extraPageNo)) {
        		$selectedFilter = str_replace("-".$extraPageNo, "", $selectedFilter);
        	}
        	$optionalArgs['selectedFilter'] = $selectedFilter;
        }

        $actualUrl = getSeoUrl($instituteId, 'all_content_pages', $title, $optionalArgs);
		if($currentUrl != $actualUrl) {
			if(!empty($extraPageNo)){
				$actualUrl .= "-".$extraPageNo;
			}
			$queryString = substr(strrchr($_SERVER['REQUEST_URI'], "?"), 0);
			redirect($actualUrl.$queryString, 'location', 301);
		}
	}

	

	public function prepareUrlNeededParams($request){
		$data = array();
		
		$data['tscs'] = xss_clean($request->getTwoStepClosedSearch());
		$data['sscs'] = xss_clean($request->getSingleStreamClosedSearch());
		// $relevantResults = $request->getRelevantResults();
		// if(!empty($relevantResults)){
		// 	$data['rr'] = xss_clean($relevantResults);
		// }
		// $oldKeyword = $request->getOldKeyword();
		// if(!empty($oldKeyword)){
		// 	$data['okw'] = xss_clean($oldKeyword);
		// }
		return json_encode($data);
	}

	public function _setPaginationData(& $data){
		$urlFetch          = $data['urlPrefix'];
		$queryString 	   = $_SERVER['QUERY_STRING'];
		
		$urlArray = explode('pn=',$queryString);
		$prevUrl  = $urlArray[0];
		$urlArray = explode('&',$urlArray[1]);

	    for($i=1;$i<(count($urlArray));$i++) {
	        $nextUrl .= ($nextUrl=='')?$urlArray[$i]:'&'.$urlArray[$i];
	    }

		$url = $prevUrl.$nextUrl;
		if(empty($url)){
			$url .= "pn=";
		}else{
			$url .= "&pn=";
		}
		$lazyLoadsPerPage = 4;
		$data['lazyLoadsPerPage'] = $lazyLoadsPerPage;
		// $pagereq = clone $request;
		if($data['currentPage'] > $data['totalPages'] && $data['totalPages'] > 0){ //safe check
		    $pn = 1;
		    redirect($urlFetch.$url."&pn=".$pn,'location',301);
		}
		

		$maxPageSlots = ceil($data['totalCourseCount']/($data['pageLimit'] * $lazyLoadsPerPage));

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
            $pn = $currentPageNum - $lazyLoadsPerPage;
            $data['paginationURLS']['leftArrow']['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
        }

        // URL NO 1.
        if($currentPageSlot == 1){
        	$data['paginationURLS'][0]['isActive'] = true;
        	$data['paginationURLS'][0]['url'] = "";
        	$data['paginationURLS'][0]['text'] = $currentPageSlot;
        }
        elseif($currentPageSlot == 2) {
  			$pn = $currentPageNum - $lazyLoadsPerPage;
        	$data['paginationURLS'][0]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 1;	
        }
        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
        	$pn =  $currentPageNum - (3 * $lazyLoadsPerPage);
        	$data['paginationURLS'][0]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 3;		
        }
        else{
        	$pn = $currentPageNum - (2 * $lazyLoadsPerPage);
        	$data['paginationURLS'][0]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 2;		
        }

        // URL NO 2
        if($maxPageSlots >= 2){
        	if($currentPageSlot == 1){
	        	$pn = $currentPageNum + $lazyLoadsPerPage;
	        	$data['paginationURLS'][1]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot + 1;
	        }
	        elseif($currentPageSlot == 2) {
	  			$data['paginationURLS'][1]['isActive'] = true;
	        	$data['paginationURLS'][1]['url'] = "";
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot;	
	        }
	        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
	        	$pn = $currentPageNum - (2 * $lazyLoadsPerPage);
	        	$data['paginationURLS'][1]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot - 2;		
	        }
	        else{
	        	$pn = $currentPageNum - (1 * $lazyLoadsPerPage);
	        	$data['paginationURLS'][1]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot - 1;		
	        }
        }

        // URL NO 3
        if($maxPageSlots >= 3){
        	if($currentPageSlot == 1){
	        	$pn = $currentPageNum + (2 * $lazyLoadsPerPage);
	        	$data['paginationURLS'][2]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
	        	$data['paginationURLS'][2]['text'] = $currentPageSlot + 2;
	        }
	        elseif($currentPageSlot == 2) {
	        	$pn = $currentPageNum + (1 * $lazyLoadsPerPage);
	        	$data['paginationURLS'][2]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
	        	$data['paginationURLS'][2]['text'] = $currentPageSlot + 1;	
	        }
	        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
	        	$pn = $currentPageNum - (1 * $lazyLoadsPerPage);
	        	$data['paginationURLS'][2]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
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
	        	$pn = $currentPageNum + (3 * $lazyLoadsPerPage);
	        	$data['paginationURLS'][3]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 3;
	        }
	        elseif($currentPageSlot == 2) {
	        	$pn = $currentPageNum + (2 * $lazyLoadsPerPage);
	        	$data['paginationURLS'][3]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 2;	
	        }
	        elseif($currentPageSlot == $maxPageSlots) {
	        	$data['paginationURLS'][3]['isActive'] = true;
	        	$data['paginationURLS'][3]['url'] = "";
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot;		
	        }
	        else{
	        	$pn = $currentPageNum + (1 * $lazyLoadsPerPage);
	        	$data['paginationURLS'][3]['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 1;		
	        }
        }


        if($currentPageSlot < $maxPageSlots) { 
            $pn = $currentPageNum + $lazyLoadsPerPage;
            $data['paginationURLS']['rightArrow']['url'] = $urlFetch.'-'.$pn.'?'.$url.$pn;
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
        // _p($data['paginationURLS']);die;

	}

	private function _relatedLinksData(& $data, $instituteId, $instituteObj){
		$data['isAnAExist'] = 0;
		$data['isReviewExist'] = 0;
		$data['isArticleExist'] = 0;
		
    	$this->load->library('nationalInstitute/InstituteDetailLib');
		$preFetchedCourseIds = $this->institutedetaillib->getallCoursesForInstitutes($instituteId);
		
		$contentCount = $this->anaRecomLib->getInstituteAnaCounts(array($instituteId),'question',array($instituteId=>$preFetchedCourseIds));
		if(!empty($contentCount[$instituteId]) && $contentCount[$instituteId] > 0 ){
			$data['isAnAExist'] = $contentCount[$instituteId];
		}
		
		$contentCount = $this->reviewRecomLib->getInstituteReviewCounts(array($instituteId),array($instituteId=>$preFetchedCourseIds));
		if(!empty($contentCount[$instituteId]) && $contentCount[$instituteId] > 0 ){
			$data['isReviewExist'] = $contentCount[$instituteId];
		}
		
		$contentCount = $this->articleRecomLib->getInstituteArticleCounts(array($instituteId),array($instituteId=>$preFetchedCourseIds));
		if(!empty($contentCount[$instituteId]) && $contentCount[$instituteId] > 0 ){
			$data['isArticleExist'] = $contentCount[$instituteId];
		}

		$scholarships = $instituteObj->getScholarships();
		$data['isScholarshipExist'] = false;
		if(count($scholarships)>0){
			$data['isScholarshipExist'] = true;
		}

	}

	private function getSeoDetails(& $data, $instituteObj) {

		// $currentUrl = $_SERVER['SCRIPT_URI'];
		$currentUrl = $data['urlPrefix'];
		$this->field_alias = $this->config->item('FIELD_ALIAS');

		$mainLocation = $instituteObj->getMainLocation();
        $instituteNameData = getInstituteNameWithCityLocality($instituteObj->getName(), $instituteObj->getType(), $mainLocation->getCityName(), $mainLocation->getLocalityName());
        $data['instituteNameWithLocation'] = $instituteNameData['instituteString'];
		        
        $seoTitle = htmlentities(str_replace(", "," ",$data['instituteNameWithLocation']))." Courses - Shiksha";
        $temp = ($data['currentPage'] > 1) ? "Page ".$data['currentPage'].': ' : '';
        $seoDescription  = $temp."See ".htmlentities($data['totalCourseCount'])." courses offered by ".htmlentities($data['instituteNameWithLocation']).". Find complete list of ".htmlentities($instituteObj->getName())." courses for UG/PG along with admission process, eligibility, courses reviews, fees, placements, infrastructure and much more on Shiksha.com.";
        if($data['currentPage'] == "1"){
        	$data['m_canonical_url']  = $currentUrl;
        }else{
        	$data['m_canonical_url']  = $currentUrl."-".$data['currentPage'];	
        }

        // $allCoursePageCanonicalUrl = $this->request->getAllCoursePageCanonicalUrl();
        $data['m_canonical_url']  = (!empty($allCoursePageCanonicalUrl)) ? $allCoursePageCanonicalUrl : $data['m_canonical_url'];

        if($data['currentPage'] > 1){
        	$prevPage = $data['currentPage'] - 1;
        	if($prevPage == "1"){
        		$data['prevURL'] = $currentUrl;
        	}else{
        		$data['prevURL'] = $currentUrl."-".$prevPage;
        	}
        }
        
        if($data['currentPage'] < $data['totalPages']){
        	$nextPage = $data['currentPage'] + 1;
        	$data['nextURL'] = $currentUrl."-".$nextPage;
        }

        $data['seoData'] = $this->allCoursesRepository->getPageHeading($data['appliedFilters'], $instituteObj, htmlentities($data['instituteNameWithLocation']));
    	$data['m_meta_title'] = (!empty($data['seoData']['seoTitle'])) ? $data['seoData']['seoTitle'] : $seoTitle;
    	$data['m_meta_description'] = (!empty($data['seoData']['seoDescription'])) ? $data['seoData']['seoDescription'] : $seoDescription;
        return $data;
	}

	private function _beaconTrackData(& $data){

		 $data['beaconTrackData'] = array(
                    'pageIdentifier' => 'allCoursesPage',
                    'pageEntityId' => $data['instituteObj']->getId(),
              	  	'extraData' => array('url'=>get_full_url()),
              	  	'countryId' =>  2
                );
	}


}
