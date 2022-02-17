<?php 

class AllCoursesPage extends MX_Controller {
	function _init($selectedFilter) {
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
		    $this->userid=$this->userStatus[0]['userid'];
		} else {
		    $this->userid=-1;
		}
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
		
		$this->load->config("nationalCategoryList/nationalConfig");
		$this->fieldAlias = $this->config->item('FIELD_ALIAS');

		// get institute repository with all dependencies loaded
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepository = $instituteBuilder->getInstituteRepository();

        $this->load->config("nationalCategoryList/nationalConfig");
        if(DO_SEARCHPAGE_TRACKING){
        	$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
        }
        $this->load->helper('image');
		$this->_initOther();
		$this->cmpObj = $this->load->library('comparePage/comparePageLib');

		$this->load->config('CollegeReviewForm/collegeReviewConfig');
    }

    private function _initOther(){
    	$this->myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");
        $this->national_course_lib = $this->load->library('listing/NationalCourseLib');
        $this->load->library('nationalCategoryList/NationalCategoryDisplayObject');
        $this->load->helper('listingCommon/listingcommon');

        $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib'); 

        $this->load->library('nationalCourse/CourseDetailLib');
		$this->courseDetailLib = new CourseDetailLib;

		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$this->streamRepository = $listingBase->getStreamRepository();

		$this->load->library('ContentRecommendation/AnARecommendationLib');
		$this->anaRecomLib = new AnARecommendationLib;

		$this->load->library('ContentRecommendation/ArticleRecommendationLib');
		$this->articleRecomLib = new ArticleRecommendationLib;

		$this->load->library('ContentRecommendation/ReviewRecommendationLib');
		$this->reviewRecomLib = new ReviewRecommendationLib;

		$this->load->library('Online/OnlineFormUtilityLib');
		$this->onlineFormLib = new OnlineFormUtilityLib();

		$this->instituteDetailLib = $this->load->library("nationalInstitute/InstituteDetailLib");

    }

	public function index($instituteId, $selectedFilter = null) {
		$this->logFilePath = "/tmp/all_courses_404.log";

		if(empty($instituteId)) {
			error_log("Invalid instituteId: ".$instituteId."...\n", 3, $this->logFilePath);
			show_404();
		}
		
		//BIP/SIP page
		if(!empty($selectedFilter)) {
			$isChildHierarchyPage = 1;
		}

		$this->_init($selectedFilter);


		$instituteObj = $this->instituteRepository->find($instituteId,array('scholarship'));

		if(empty($instituteObj)) {
			error_log("Empty InstituteObject: ".$instituteId."...\n", 3, $this->logFilePath);
			show_404();
		} else{
			$instituteIdFromObject = $instituteObj->getId();
			if(empty($instituteIdFromObject)){
				error_log("Empty InstituteId from InstituteObject: ".$instituteId."...\n", 3, $this->logFilePath);
				show_404();
			}
		}


		if($instituteId == 35861){
			ini_set("memory_limit", "2000M");
		}
		

		$urlWithoutPageNo = $this->request->getCurrentUrlWithoutPageNo();
		
		$this->_validateURL($instituteObj,$urlWithoutPageNo, $isChildHierarchyPage, $selectedFilter);

		//get data from solr and load their objects
		$data = $this->allCoursesRepository->getFiltersAndCourses($instituteId);
		$data['appliedFilters'] = $this->request->getAppliedFilters();
		$data['filterBucketName'] =  $this->config->item('FILTER_NAME_MAPPING');
		$fieldAlias =  $this->fieldAlias;

		foreach ($data['filters'] as $key => $value) {
			$filtersList[$key] = $fieldAlias[$key];
		}
		$data['jsonFiltersList'] = json_encode($filtersList);
		

		//hide 'college offered by' filter in case of institute
		$instituteType = $instituteObj->getType();
		if($instituteType == 'institute') {
			unset($data['filters']['offered_by_college']);
		}
		
		$currentLocationObj          = $this->instituteDetailLib->getInstituteCurrentLocation($instituteObj);
		$data['currentLocationObj']  = $currentLocationObj;
		$data['instituteLocations']  = $instituteObj->getLocations();
		$data['isMultilocation']     = count($data['instituteLocations']) > 1 ? true : false;

		if(empty($instituteObj) || empty($instituteId)){
		    show_404();
		    exit(0);
		}

		$data['coursesWidgetData']   = $this->instituteDetailLib->getInstitutePageCourseWidgetData($instituteObj, $currentLocationObj, $data['isMultilocation'], 2, 2, 'mobile');

		$flagshipCourseId = $data['coursesWidgetData']['flaghshipCourse'];

		$data['flagshipCourseId'] = $flagshipCourseId;   

		$data['instituteObj'] = $instituteObj;
		$data['validateuser'] = $this->userStatus;

		$data['product'] = "AllCoursesPage";
		$data['trackForPages'] = true;
		$data['tupleListSource'] = "AllCoursesPage";
		$data['isAjax'] = ($_SERVER['HTTP_X_REQUESTED_WITH'] != '') ? 1 : 0;
		$data['listing_id'] = $instituteId;
		$courses = $data['institutes']['courseData'];
		$data['totalPages'] = ceil($data['totalCourseCount']/$this->request->getPageLimit());
		$data['currentPage'] = $this->request->getPageNumber();
		if(empty($courses) & $data['totalPages'] == 0){
			error_log("Empty Courses: ".$instituteId."...\n", 3, $this->logFilePath);
			show_404();
		}
		$finalCourseIds = array();
		$CourseIdsForReviews = array();
		$totalReviewCount = 0; 
		$eligibility = array();
		foreach ($courses as $courseObj) {
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
					$displayObject = new NationalCategoryDisplayObject();
					$displayObject->setDisplayDataObjectForLoadMore($courseObj, $data['appliedFilters']);
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

		$data['questionsCountCombined'] = $this->anaRecomLib->getRecommendedCourseQuestionsCount($finalCourseIds);
		$data['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);
		$data['maxPagesOnPaginitionDisplay'] = 5;

		$data['pageNumber'] = $this->request->getCurrentPageNum();
		$data['genericPaginationURL'] = $this->request->getUrlForPagination("#page_no#");
		$data['urlPrefix'] = $this->request->getCurrentUrlWithoutPageNo();
        $data['reviewWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getReviewWidget',$instituteId,$instituteObj->getType(),$CourseIdsForReviews,'', array('getCount' => 1),$instituteObj,true);

		$isLoggedIn = true;
		if($this->userStatus == "false"){
			$isLoggedIn = false;
		}

		$data['isChildHierarchyPage'] = $isChildHierarchyPage;

		//get rating review tuple
		$collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
		$data['aggregateReviewsData'] = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');
		
			
		//add SEO details
		$this->getSeoDetails($data, $instituteObj);
		
		// Redirect to 1st page if number out of range
		
		if($data['currentPage'] > $data['totalPages'] && $data['totalPages'] > 0){
			$urlStart = $data['metadata']['canonical'];

			$urlEnd   = $_SERVER['QUERY_STRING'];
			if(!empty($urlEnd)){
				$urlEnd = "?".$urlEnd;
			}
			$finalUrl = $urlStart.$urlEnd;
			
			$redirectUrl = $this->request->getUrlForPagination(1,$finalUrl);
		  	redirect($redirectUrl,'location',301);
		}

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
					$trackData = array();
					$trackData['searchKeyword'] = $instituteId;
					$trackData['page'] = 'allCourses';
					$trackData['resultCount'] = $data['totalCourseCount'];
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

		$this->_beaconTrackData($data);

		$data['isTwoStepClosedSearch'] = $this->request->getTwoStepClosedSearch();

		if($data['isTwoStepClosedSearch'] == true){
			$backData['url'] = $this->backToClosedSearchUrl($this->request,$data['metadata']['canonical']);
			$backData['stream'] = reset($data['appliedFilters']['stream']);
			if(!empty($backData['stream'])){
				$dataStream = $this->streamRepository->find($backData['stream']);
				$backData['stream'] = $dataStream->getName();	
			}
			$data['backToClosedSearch'] = $backData;
		}


		// Apply CTA Data
		$data['onlineApplicationCoursesUrl'] = $this->onlineFormLib->getOAFBasicInfo($finalCourseIds);
		$data['comparetrackingPageKeyId'] = 1090;
		$data['applyOnlinetrackingPageKeyId']= 1092;
		$data['ebrochureTrackingId'] = 1091;
		$data['stickyEbrochureTrackingId'] = 941;
		$data['shortlistTrackingId'] = 1093;
		/*$data['coursesWithOnlineForm']['250758'] = 1;
		$data['onlineApplicationCoursesUrl']['250758']['seo_url'] = "/aim-delhi-online-application-form-mba-122193";*/

		//shortLlist CTA
		$data['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$this->userid); 

		// compare CTA
		$data['alreadyComparedCourses'] = $this->cmpObj->getComparedData();

		if(empty($data['totalCourseCount'])){
			// Stream List for ZRP
			$data['streamsArray'] = $this->streamRepository->getAllStreams();
	        // Sort streams by name
			uasort($data['streamsArray'], function($a,$b){return strcmp($a["name"], $b["name"]);});
		}
		

		if($instituteType == "institute"){
			$data['isAnAExist'] = $this->anaRecomLib->checkContentExistForInstitute(array($instituteId));
			if(empty($data['isAnAExist'])){
				$data['isAnAExist'] = false;
			}
			
			$data['isReviewExist'] = $this->reviewRecomLib->checkContentExistForInstitute(array($instituteId));
			if(empty($data['isReviewExist'])){
				$data['isReviewExist'] = false;
			}

			$data['isArticleExist'] = $this->articleRecomLib->checkContentExistForInstitute(array($instituteId));
			if(empty($data['isArticleExist'])){
				$data['isArticleExist'] = false;
			}
			
		}else{
			$data['isAnAExist'] = $this->anaRecomLib->checkContentExistForUniversity(array($instituteId));
			if(empty($data['isAnAExist'])){
				$data['isAnAExist'] = false;
			}

			$data['isReviewExist'] = $this->reviewRecomLib->checkContentExistForUniversity(array($instituteId));
			if(empty($data['isReviewExist'])){
				$data['isReviewExist'] = false;
			}
			
			$data['isArticleExist'] = $this->articleRecomLib->checkContentExistForUniversity(array($instituteId));
			if(empty($data['isArticleExist'])){
				$data['isArticleExist'] = false;
			}
		}
		
		$scholarships = $instituteObj->getScholarships();
		$data['isScholarshipExist'] = false;
		if(count($scholarships)>0){
			$data['isScholarshipExist'] = true;
		}
		
		if(empty($data['isAjax'])){
			$data['gtmParams'] = $this->getGTMParams($this->request,$instituteId);
		}

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
		$data['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'ALLCOURSE','page'=>'DESKTOP');
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


        $data['metadata']['canonical'] = $this->instituteDetailLib->getCanonnicalUrl($instituteId,$data['metadata']['canonical']);

        global $MESSAGE_MAPPING,$INSTITUTE_MESSAGE_KEY_MAPPING;
        $data['SRM_DATA'] = $MESSAGE_MAPPING[$INSTITUTE_MESSAGE_KEY_MAPPING[$instituteId]];

        if(!empty($data['SRM_DATA'])){
                $data['showToastMsg'] = true;
            }

		$this->load->view('nationalCategoryList/categoryPageContent', $data);
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

	private function getSeoDetails(&$data, $instituteObj) {
		$currentUrl = $data['urlPrefix'];

		$this->field_alias = $this->config->item('FIELD_ALIAS');
		
		$secondaryName = $instituteObj->getSecondaryName();
		$mainLocation = $instituteObj->getMainLocation();
		$courseCount = $data['totalCourseCount'];

		$currentYear = date('Y');
        $currentMonth = date('m');
        if($currentMonth > 10 ){
            $currentYear = $currentYear + 1;
        }

		if(empty($mainLocation)) return;
        $instituteNameData = getInstituteNameWithCityLocality($instituteObj->getName(), $instituteObj->getType(), $mainLocation->getCityName(), $mainLocation->getLocalityName());
        $data['instituteNameWithLocation'] = $instituteNameData['instituteString'];
        $seoTitle = htmlentities($data['instituteNameWithLocation'])." Courses, Fees & Fee Structure ".$currentYear;
        if(!empty($secondaryName)){
        	$seoDescription  = "See ".$courseCount." Courses & Fees of ".htmlentities($data['instituteNameWithLocation'])." (".$secondaryName.") for ".$currentYear.". Find Fee Structure along with Placement Reviews, Cutoff & Eligibility";
        }
        else{
        	$seoDescription  = "See ".$courseCount." Courses & Fees of ".htmlentities($data['instituteNameWithLocation'])." for ".$currentYear.". Find Fee Structure along with Placement Reviews, Cutoff & Eligibility";
        }

        if($data['currentPage'] > 1){
        	$data['metadata']['canonical']  = $currentUrl;
        }else{
        	$data['metadata']['canonical']  = $currentUrl;
        }

        if($data['currentPage'] > 1){
			$prevPage = $data['currentPage'] - 1;
			if($prevPage == "1"){
				$data['metadata']['previousURL'] = $currentUrl;
			}else{
				$data['metadata']['previousURL'] = $currentUrl."-".$prevPage;	
			}
			
		}
		
		if($data['currentPage'] < $data['totalPages']) {
			$nextPage = $data['currentPage'] + 1;
			$data['metadata']['nextURL'] = $currentUrl."-".$nextPage;
		}
		
		
        $tempObj = new stdClass;
        $tempObj->name = "Home";
        $tempObj->url = SHIKSHA_HOME;
        $data['breadcrumb'][] = $tempObj;

        $tempObj = new stdClass;
        $tempObj->name = "Colleges";
        $tempObj->url = "";
        $data['breadcrumb'][] = $tempObj;


        $tempObj = new stdClass;
        $tempObj->name = htmlentities($data['instituteNameWithLocation']);
        $tempObj->url = $instituteObj->getURL();
        $tempObj->useActual = true;
        $data['breadcrumb'][] = $tempObj;

        $breadcrumbUrl = '';
    	$data['seoData'] = $this->allCoursesRepository->getPageHeading($data['appliedFilters'], $instituteObj, htmlentities($data['instituteNameWithLocation']), $secondaryName, $currentYear);
    	$data['metadata']['title'] = (!empty($data['seoData']['seoTitle'])) ? $data['seoData']['seoTitle'] : $seoTitle;
    	$data['metadata']['description'] = (!empty($data['seoData']['seoDescription'])) ? $data['seoData']['seoDescription'] : $seoDescription;

    	if(!empty($data['seoData']['seoDescription'])) {
    		$breadcrumbUrl = $instituteObj->getAllContentPageUrl('courses');
    	}

        $tempObj = new stdClass;
        $tempObj->name = "Courses";
        $tempObj->url = $breadcrumbUrl;
        $data['breadcrumb'][] = $tempObj;
    

    	if(!empty($data['seoData']['seoDescription'])) {
    		$tempObj = new stdClass;
	        $tempObj->name = $data['seoData']['pageHeadingWithoutUrl'];
	        $tempObj->url = "";
	        $data['breadcrumb'][] = $tempObj;
    	}
        return $data;
	}

	public function backToClosedSearchUrl($pageRequest,$url){
		$this->load->library('search/SearchV3/nationalSearchPageRequest');
        $requestToGenerateFreshSearchUrl = new NationalSearchPageRequest(array('11'));

		$data['searchKeyword']      = $pageRequest->getSearchKeyword();
		$data['searchedAttributes'] = $pageRequest->getSearchedAttributesString();
		
		$data['qerResults']         = $pageRequest->getQERFiltersString();
		$initalAttributes           = $pageRequest->getSearchedAttributes();

		if(isset($initalAttributes['city']) && !empty($initalAttributes['city'])){
			$data['city']               = $initalAttributes['city'];
		}

		if(isset($initalAttributes['state']) && !empty($initalAttributes['state'])){
			$data['state']               = $initalAttributes['state'];
		}

		$data['requestFrom'] = 'searchwidget';
		if(DO_SEARCHPAGE_TRACKING){
			$trackingSearchId = $pageRequest->getTrackingSearchId();
			if(!empty($trackingSearchId)){
				$data['trackingSearchId'] = $trackingSearchId;
			}
		}
		$requestToGenerateFreshSearchUrl->setData($data);
        return $url;
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
