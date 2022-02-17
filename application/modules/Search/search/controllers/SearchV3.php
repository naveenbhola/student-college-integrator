<?php

class SearchV3 extends MX_Controller {
	
	private $AutoSuggestorInitLib;

	public function __construct() {
		parent::__construct();

		$this->userStatus = $this->checkUserValidation();
        if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid=$this->userStatus[0]['userid'];
        } else {
            $this->userid=-1;
        }

        $this->load->helper('listingCommon/listingcommon');
		$this->load->config("nationalCategoryList/nationalConfig");
		$this->load->config('CollegeReviewForm/collegeReviewConfig');
	}

	private function _initCourseSearch() {
		$this->AutoSuggestorInitLib = $this->load->library("search/Autosuggestor/AutoSuggestorInitLib");

		// $this->searchPageDataProcessor = $this->load->library("search/SearchV2/SearchPageDataProcessor");

		$this->fieldAlias = $this->config->item('FIELD_ALIAS');

		$this->myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");

        $this->national_course_lib = $this->load->library('listing/NationalCourseLib');
        
        $this->load->library('nationalCategoryList/NationalCategoryDisplayObject');

        $this->load->library('nationalCourse/CourseDetailLib');
		$this->courseDetailLib = new CourseDetailLib;

		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$this->streamRepository = $listingBase->getStreamRepository();
		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
		}
		$this->load->helper('image');
		$this->load->library('Online/OnlineFormUtilityLib');
		$this->onlineFormLib = new OnlineFormUtilityLib();
		$this->cmpObj = $this->load->library('comparePage/comparePageLib');

		$this->load->library('ContentRecommendation/AnARecommendationLib');
		$this->anaRecomLib = new AnARecommendationLib;

		$this->institutedetailsmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
		$this->instituteDetailLib = $this->load->library('nationalInstitute/instituteDetailLib');
	}
	
	public function index(){
		$this->_initCourseSearch();

		if($this->input->get('source') == 'google') {
			$this->searchFromGoogle();
		}
		//$_REQUEST['newSearch'] = 1;
		
		$this->load->builder('SearchBuilderV3','search');
		$searchBuilderV3           			= new SearchBuilderV3();
		$this->request                   			= $searchBuilderV3->getRequest();
		
		$this->getDebugInfo();
		
		$searchRepository = $searchBuilderV3->getSearchRepository();
		$fieldAlias =  $this->fieldAlias;

		$data = $searchRepository->getRawSearchData();
		//_p($data);die;
		$data['requestFrom'] = $this->request->getRequestFrom();
		
		// HACK --- auto select single resultant stream (will happen only during open search) & redirect to closed search
		if(count($data['filters']['stream']) == 1 && !empty($data['requestFrom']) && !in_array($data['requestFrom'], array('filters','filterBucket'))) {
			$this->request->setSingleStreamClosedSearch(true);
			$url = $this->request->getUrl();
			$redirectUrl = $url.'&'.$fieldAlias['stream'].'[]='.$data['filters']['stream'][0]['id'];
			$redirectUrl .= "&".$fieldAlias['twoStepClosedSearch']."=1";
			redirect($redirectUrl, 'location', 301);
		}

		foreach ($data['filters'] as $key => $value) {
			$filtersList[$key] = $fieldAlias[$key];
		}

		if(DO_SEARCHPAGE_TRACKING){
			if(empty($data['isAjax'])){
				$trackingSearchId = $this->request->getTrackingSearchId();
				
				if(empty($trackingSearchId)){
					$keyword = $this->request->getSearchKeyword();
					if(!empty($keyword)){
						$temp = array('stream'=>'getStream','substream'=>'getSubstream','specialization'=>'getSpecialization','baseCourse'=>'getBaseCourse','credential'=>'getCredential','educationType'=>'getEducationType','deliveryMethod'=>'getDeliveryMethod','exams'=>'getExam','city' => 'getCity', 'state' => 'getState');
						$trackData = array();
						foreach ($temp as $key => $method) {
							$trackData[$key] = call_user_func(array($this->request,$method));
						}
						$oldKeyword = $this->request->getOldKeyword();
						$trackData['searchKeyword'] = empty($oldKeyword) ? $keyword : $oldKeyword;
						$trackData['page'] = 'search';
						$trackData['resultCount'] = $data['totalInstituteCount'];
						$trackData['userId'] = ($this->userid > 0) ? $this->userid : NULL;
						$trackData['requestFrom'] = $this->request->getRequestFrom();

						$typedKeyword = $this->request->getTypedKeyword();
						$trackData['typedKeyword'] = empty($oldKeyword) ? $typedKeyword : $oldKeyword;
						$trackData['isTrending'] = $this->request->getIsTrending();
						$trackData['isInterim'] = $this->request->getIsInterim();
						
						$data['trackingSearchId'] = $this->trackmodel->getTrackingIdForCourse($trackData);
						$this->request->setTrackingSearchId($data['trackingSearchId']);	

						if(!empty($data['requestFrom']) && !in_array($data['requestFrom'],array('filters','filterBucket'))){
							$data['updateResultCountForTracking'] = true;
						}
					}
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

		$data['jsonFiltersList'] = json_encode($filtersList);
		$data['appliedFilters']   = $data['appliedFilters']; //$this->request->getAppliedFilters();
		$data['filterBucketName'] =  $this->config->item('FILTER_NAME_MAPPING');
		$finalCourseIds  = array();
		$eligibility = array();
		foreach ($data['institutes']['instituteData'] as $instituteId => $instituteObj) {
			$courseObj = $instituteObj->getCourses();
			$courseObj = reset($courseObj);
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
				$finalCourseIds[] = $courseId;
				unset($courseObj);
				$displayObject = new NationalCategoryDisplayObject();
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

		$data['trackForPages'] = true;
		$data['questionsCountCombined'] = $this->anaRecomLib->getRecommendedCourseQuestionsCount($finalCourseIds);
		$data['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);
		$data['relevantResults'] = $this->request->getRelevantResults();
		$data['totalPages'] = ceil($data['totalInstituteCount']/$this->request->getPageLimit());
		$data['currentPage'] = $this->request->getPageNumber();

		$collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
		$aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');
		$data['aggregateReviewsData'] = $aggregateReviews;
		//_p($data['aggregateReviewsData']);die;

		$keyword = $this->request->getOldKeyword();
		if(empty($keyword)){
           $keyword = $this->request->getSearchKeyword();
        }
        $data['keyword'] = $keyword;
        $data['searchKeyword'] = $this->request->getSearchKeyword();
        $qerFilters = $this->request->getQERFilters();

        if(!empty($qerFilters['q']) && in_array($data['relevantResults'], array('relax','relaxandspellcheck'))){
        	$data['qerKeyword'] = $qerFilters['q'];
        	//$data['strikeMessage'] = $data['searchKeyword'];
        	$qerKeywordArr = explode(" ",$data['qerKeyword']);
        	
        	if($data['relevantResults'] == "relax"){
        		$keywordArr = explode(' ', $data['searchKeyword']);
        		foreach ($qerKeywordArr as $key1 => $qerWord) {
        			foreach ($keywordArr as $key2 => $str) {
        				if(strtolower($str) == strtolower($qerWord)) {
        					$keywordArr[$key2] = "<span class='strikeThrough'>".$str."</span>";
        				}
        			}
	        	}
	        	$data['strikeMessage'] = implode(' ', $keywordArr);
        	}
        }
        
        //SEO
        if(empty($data['searchKeyword'])){
			$data['metadata']['title'] = "Colleges Search - Find the Right College for You | Shiksha.com";
			$data['metadata']['description'] = "Search for colleges, courses, universities, exams, admission, career advice, articles, latest news, discussions, and more education resources at Shiksha.com";
		} else {
			$data['metadata']['title'] = "Search Results for &#8220;".htmlspecialchars(htmlentities($data['searchKeyword'], ENT_QUOTES))."&#8221; - Shiksha.com";
			$resultsStr = ($data['totalInstituteCount'] == 1)? 'result':'results';

			$data['metadata']['description'] = $data['totalInstituteCount']." $resultsStr found for &#8220;".htmlspecialchars(htmlentities($data['searchKeyword'], ENT_QUOTES))."&#8221;. Search for colleges, courses, universities, exams, careers, and more education resources at Shiksha.com";
		}

		// Max Number of pages in pagination
		$data['maxPagesOnPaginitionDisplay'] = 5;
		$data['pageNumber'] = $this->request->getCurrentPageNum();

		$data['tupleListSource'] = "searchPage";
		// Redirect to 1st page if number out of range
		if($data['currentPage'] > $data['totalPages'] && $data['totalPages'] > 0){
			$this->request->setPageNumber(1);
		  	redirect($this->request->getUrl(),'location',301);
		}
		$data['isAjax'] = ($_SERVER['HTTP_X_REQUESTED_WITH'] != '') ? 1 : 0;
		$data['validateuser'] = $this->userStatus;
		$data['isTwoStepClosedSearch'] = $this->request->getTwoStepClosedSearch();
		$data['singleStreamClosedSearch'] = $this->request->getSingleStreamClosedSearch();

		if(!empty($data['singleStreamClosedSearch'])){
			$streams = $this->request->getStream();
			$data['singleStreamClosedSearchStream'] = $streams[0];
		}

		if($data['isTwoStepClosedSearch'] == true){
			$backData['url'] = $this->backToClosedSearchUrl($this->request);
			$backData['stream'] = reset($data['appliedFilters']['stream']);
			if(!empty($backData['stream'])){
				$dataStream = $this->streamRepository->find($backData['stream']);
				$backData['stream'] = $dataStream->getName();	
			}
			$data['backToClosedSearch'] = $backData;
		}

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_SRP','pageType'=>'homepage');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');


		// Apply CTA Data

		$data['onlineApplicationCoursesUrl'] = $this->onlineFormLib->getOAFBasicInfo($finalCourseIds);
		$data['applyOnlinetrackingPageKeyId']= 1040;
		$data['comparetrackingPageKeyId'] = 1041;
		$data['shortlistTrackingId'] = 1042;
		$data['ebrochureTrackingId'] = 1043;
		/*$data['coursesWithOnlineForm']['250758'] = 1;
		$data['onlineApplicationCoursesUrl']['250758']['seo_url'] = "/aim-delhi-online-application-form-mba-122193";*/

		$data['product'] = "SearchV2";
		$data['searchFilterData'] 		= $this->request->processPreSelectedSearchedFiters();

		//shortLlist CTA
		$data['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$this->userid); 

		//Compare
		$data['alreadyComparedCourses'] = $this->cmpObj->getComparedData();


		if(empty($data['totalInstituteCount'])){
			// Stream List for ZRP
			$data['streamsArray'] = $this->streamRepository->getAllStreams();
	        // Sort streams by name
			uasort($data['streamsArray'], function($a,$b){return strcmp($a["name"], $b["name"]);});
		}
		

		if(empty($data['isAjax'])){
			$data['gtmParams'] = $this->getGTMParams();
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
		$data['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'SRP','page'=>'DESKTOP');
		//set data for view,
		$this->load->view('nationalCategoryList/categoryPageContent', $data);
	}

	public function showQuestionSRP($tab) {
		$this->load->builder('SearchBuilderV3','search');
		$searchBuilderV3 = new SearchBuilderV3();

		$this->load->helper('image');

		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
		}
		
		$this->questionRequest = $searchBuilderV3->getQuestionRequest();
		$this->searchRepository = $searchBuilderV3->getQuestionSearchRepository();

		$this->questionRequest->setUserId($this->userid);
		
		$displayData = array();
		$displayData = $this->searchRepository->getRawSearchData($tab);


		$displayData['currentTab'] = $this->questionRequest->getCurrentTab();
		$displayData['userId'] = $this->userid;
		$displayData['validateuser'] = $this->userStatus;
		$displayData['keyword'] = $this->questionRequest->getSearchKeyword();
		$displayData['product'] = "SearchV2";
		$displayData['pageType'] = "questions";
		$displayData['boomr_pageid'] = "quesSearch";
		$displayData['pageTab'] = "question_".$displayData['currentTab'];
		$displayData['trackingKeyIds']['unQuesFollow'] = DESKTOP_NL_QUES_SEARCH_UNQUES_FOLLOW;
		$displayData['trackingKeyIds']['aQuesFollow'] = DESKTOP_NL_QUES_SEARCH_AQUES_FOLLOW;
		$displayData['trackingKeyIds']['tagFollow'] = DESKTOP_NL_QUES_SEARCH_TAG_FOLLOW;
		$displayData['trackingKeyIds']['unWriteAnswer'] = DESKTOP_NL_QUES_SEARCH_UNQUES_WRITE_ANS;
		$displayData['trackingKeyIds']['aWriteAnswer'] = DESKTOP_NL_QUES_SEARCH_AQUES_WRITE_ANS;
		
		$filterBy = $this->questionRequest->getFilterBy();
		if(empty($filterBy) && $displayData['tupleData']['resultCount'] == 1) {
			unset($displayData['tupleData']['filters']);
		}

		foreach ($displayData['tupleData']['filters'] as $key => $value) {
			unset($displayData['tupleData']['filters'][$key]);
			
			$urlData['filterBy'] = $value;
			$displayData['tupleData']['filters'][$key]['name'] = $value;
			$displayData['tupleData']['filters'][$key]['url'] = $this->questionRequest->getTabURL($urlData);
		}

		$activeFilter = $this->questionRequest->getFilterBy();
		if(in_array($activeFilter, array('year', 'month', 'week'))) {
			$displayData['tupleData']['activeFilter'] = $activeFilter;

			//get URL for "all time" filter
			$displayData['tupleData']['no_filter']['url'] = $this->questionRequest->getTabURL();
		}
		
		if(DO_SEARCHPAGE_TRACKING) {
			// if(!empty($this->userid) && $this->userid != -1) {
			// 	$trackingData['userId'] = $this->userid;
			// }
			// $trackingData['keyword'] = $this->questionRequest->getSearchKeyword();
			// $trackingData['searchType'] = 'open';
			
			// $trackId = $this->trackmodel->getQuestionTrackingId($trackingData);

			$trackId = $this->questionRequest->getTrackingKeyId();

			if(!empty($trackId)) {
				$this->trackmodel->updateQuestionResultCount($trackId, $displayData['tupleData']['resultCount']);
			}
		}
		    $this->load->library('messageBoard/TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping();
            foreach($displayData['tupleData']['result'] as $k1=>$res)
            {
            	//_p($res);die;
            	foreach($res['tags'] as $m=>$v)
            	{
                	$tagIdsForUrl[$v['tagId']]=$v['tagName'];
            	}
            	$qids[]=$k1;
            }
            $tagTypeInfo = $tagUrlMappingObj->getTagsContentType($tagIdsForUrl,$qids);

            //_p($tagIdsForUrl);die;
 
            //_p($tagIds);die;
            $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($tagIdsForUrl);
            // _p($tagTypeInfo);
            // _p($displayData);
            foreach($displayData['tupleData']['result'] as $k1=>$res)
            {
            	foreach ($res['tags'] as $m => $v) 
            	{
            		$displayData['tupleData']['result'][$k1]['tags'][$m]['url']=$tagUrlInfo[$v['tagId']]['url'];
            		$displayData['tupleData']['result'][$k1]['tags'][$m]['type']=$tagUrlInfo[$v['tagId']]['type'];
                	$displayData['tupleData']['result'][$k1]['tags'][$m]['tag_type'] = $tagTypeInfo[$k1][$v['tagId']];
            	}
            	$displayData['tupleData']['result'][$k1]['tags'] = TagUrlMapping::sortTags($displayData['tupleData']['result'][$k1]['tags']);
            }

		if(empty($displayData['tupleData'])) {
			//load ZRP
			$displayData['isZRP'] = 1;

			$this->load->view('searchV3/QSRP/ZRP', $displayData);
			return;
		}

		$this->_setPaginationDataQSRP($displayData);
		
		$this->load->view('searchV3/QSRP/searchPageContent', $displayData);
	}

	private function _setPaginationDataQSRP(& $data) {
		$data['totalPages'] = $maxPages = ceil($data['tupleData']['resultCount']/$this->questionRequest->getPageLimit());
		$data['currentPage'] = $currentPageNum = $this->questionRequest->getPageNumber();
		
		// Redirect to 1st page if number out of range
		if($currentPageNum > $maxPages) { //safe check
			$this->questionRequest->setPageNumber(1);
		  	redirect($this->questionRequest->getTabURL(), 'location', 301);
		}
		
		if($data['totalPages'] == 1) {
			return;
		}

		$data['totalTupleCount'] = $data['tupleData']['resultCount'];

		$tab = $this->questionRequest->getCurrentTab();
		$filter = $this->questionRequest->getFilterBy();

		$urlData['tab'] = $tab;
		$urlData['filterBy'] = $filter;

		// LEFT ARROW
		if($currentPageNum > 1) { 
			$urlData['pageNumber'] = $currentPageNum - 1;
            $data['paginationURLS']['leftArrow']['url'] = $this->questionRequest->getTabURL($urlData);
        }

        // URL NO 1
        if($currentPageNum == 1){
        	$data['paginationURLS'][0]['isActive'] = true;
        	$data['paginationURLS'][0]['url'] = "";
        	$data['paginationURLS'][0]['text'] = $currentPageNum;
        }
        elseif($currentPageNum == 2) {
        	$urlData['pageNumber'] = $currentPageNum - 1;
  			$data['paginationURLS'][0]['url'] = $this->questionRequest->getTabURL($urlData);
        	$data['paginationURLS'][0]['text'] = $currentPageNum - 1;
        }
        elseif($currentPageNum > 3 && $currentPageNum == $maxPages) {
        	$urlData['pageNumber'] = $currentPageNum - 3;
        	$data['paginationURLS'][0]['url'] = $this->questionRequest->getTabURL($urlData);
        	$data['paginationURLS'][0]['text'] = $currentPageNum - 3;
        }
        else {
        	$urlData['pageNumber'] = $currentPageNum - 2;
        	$data['paginationURLS'][0]['url'] = $this->questionRequest->getTabURL($urlData);
        	$data['paginationURLS'][0]['text'] = $currentPageNum - 2;
        }

        // URL NO 2
        if($maxPages >= 2) {
        	if($currentPageNum == 1) {
        		$urlData['pageNumber'] = $currentPageNum + 1;
	        	$data['paginationURLS'][1]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][1]['text'] = $currentPageNum + 1;
	        }
	        elseif($currentPageNum == 2) {
	  			$data['paginationURLS'][1]['isActive'] = true;
	        	$data['paginationURLS'][1]['url'] = "";
	        	$data['paginationURLS'][1]['text'] = $currentPageNum;
	        }
	        elseif($currentPageNum > 3 && $currentPageNum == $maxPages) {
	        	$urlData['pageNumber'] = $currentPageNum - 2;
	        	$data['paginationURLS'][1]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][1]['text'] = $currentPageNum - 2;
	        }
	        else {
	        	$urlData['pageNumber'] = $currentPageNum - 1;
	        	$data['paginationURLS'][1]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][1]['text'] = $currentPageNum - 1;
	        }
        }

        // URL NO 3
        if($maxPages >= 3) {
        	if($currentPageNum == 1) {
        		$urlData['pageNumber'] = $currentPageNum + 2;
	        	$data['paginationURLS'][2]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][2]['text'] = $currentPageNum + 2;
	        }
	        elseif($currentPageNum == 2) {
	        	$urlData['pageNumber'] = $currentPageNum + 1;
	  			$data['paginationURLS'][2]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][2]['text'] = $currentPageNum + 1;
	        }
	        elseif($currentPageNum > 3 && $currentPageNum == $maxPages) {
	        	$urlData['pageNumber'] = $currentPageNum - 1;
	        	$data['paginationURLS'][2]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][2]['text'] = $currentPageNum - 1;
	        }
	        else {
	        	$data['paginationURLS'][2]['isActive'] = true;
	        	$data['paginationURLS'][2]['url'] = "";
	        	$data['paginationURLS'][2]['text'] = $currentPageNum;
	        }
        }

        // URL NO 4
        if($maxPages >= 4) {
        	if($currentPageNum == 1) {
        		$urlData['pageNumber'] = $currentPageNum + 3;
	        	$data['paginationURLS'][3]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][3]['text'] = $currentPageNum + 3;
	        }
	        elseif($currentPageNum == 2) {
	        	$urlData['pageNumber'] = $currentPageNum + 2;
	  			$data['paginationURLS'][3]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][3]['text'] = $currentPageNum + 2;
	        }
	        elseif($currentPageNum == $maxPages) {
	  			$data['paginationURLS'][3]['isActive'] = true;
	        	$data['paginationURLS'][3]['url'] = "";
	        	$data['paginationURLS'][3]['text'] = $currentPageNum;
	        }
	        else {
	        	$urlData['pageNumber'] = $currentPageNum + 1;
	  			$data['paginationURLS'][3]['url'] = $this->questionRequest->getTabURL($urlData);
	        	$data['paginationURLS'][3]['text'] = $currentPageNum + 1;
	        }
        }

        if($currentPageNum < $maxPages) {
        	$urlData['pageNumber'] = $currentPageNum + 1;
            $data['paginationURLS']['rightArrow']['url'] = $this->questionRequest->getTabURL($urlData);
        }
	}

	private function getGTMParams(){
		$appliedFilters = $this->request->getAppliedFilters();//_p($appliedFilters);die;
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

		$gtmParams['pageType'] = 'searchPage';
		$gtmParams['countryId'] = 2;
		if($this->userid > 0){
			$workExperience = $this->userStatus[0]['experience'];
			if(!empty($workExperience)){
				$gtmParams['workExperience'] = $workExperience;
			}
		}
		// _p($gtmParams);die;
		return $gtmParams;
	}

	private function getDebugInfo() {
		if(DEBUGGER) {
			$searchKeyword = $this->request->getSearchKeyword();
			$qerFilters = $this->request->getQERFilters();
			_p('Search keyword: '.$searchKeyword);
			_p('QER output: '); _p($qerFilters);
		}
	}

	public function createOpenSearchUrl($requestData = false, $returnURL = false) {
		$data         = array();
		
		$this->load->library("search/SearchV3/NationalSearchPageUrlGenerator");
		$urlGenerator      = new NationalSearchPageUrlGenerator(1);
		
		if(!empty($requestData)){
			$data['keyword']               	= $requestData['keyword'];
			$data['typedKeyword']           = $requestData['typedKeyword'];
			$data['locations']             	= $requestData['locations'];
			$data['requestComingFrom'] 		= $requestData['requestFrom'];
		} else {
			$data['searchPage']            	= $this->input->post('search',true);
			$data['keyword']               	= $this->input->post('keyword',true);
			$data['isTrending']             = $this->input->post('isTrending',true);
			$data['typedKeyword']           = $this->input->post('typedKeyword',true);
			$data['locations']             	= $this->input->post('locations',true);
			$data['requestComingFrom'] 		= $this->input->post('requestFrom', true);
			$data['isInstituteMultiple']	= $this->input->post('isInstituteMultiple', true);
			$data['isInterim']				= $this->input->post('isInterim', true);
		}

		if($this->userid !== -1){
			$data['userId'] = $this->userid;
		}

		$url = $urlGenerator->createOpenSearchUrl($data);
		
		if($returnURL == TRUE){
			return 	$url;
		} else {
			echo json_encode(array('url'=>$url));
			exit;
		}
	}

	private function searchFromGoogle() {
    	$data         = array();
    	$keyword = $this->input->get('q',true);

    	$data['keyword'] 				= $keyword;
    	$data['requestComingFrom'] 		= 'fromgoogle';

    	$this->load->library("search/SearchV3/NationalSearchPageUrlGenerator");
		$urlGenerator      = new NationalSearchPageUrlGenerator();

    	$url = $urlGenerator->createOpenSearchUrl($data);//_p($url);die;
    	redirect($url,'location',301);
    }

    public function createClosedSearchUrl($requestData = false, $returnUrl = false){
        $data = array();
        if($requestData){
            $data['keyword']        = $requestData['keyword'];
            $data['entityId']       = $requestData['entityId'];
            $data['entityType'] 	= $requestData['entityType'];
            $data['exams']          = $requestData['Exams_Accepted'];
            $data['requestFrom'] = $requestData['requestFrom'];
        }
        else {
            $data['keyword'] = $this->input->post('keyword', true);
            $data['entityId'] = (int)$this->input->post('entityId', true);
            $data['entityType'] = $this->input->post('entityType', true);
            $data['locations'] = $this->input->post('locations', true);
            $data['stream'] = $this->input->post('Stream', true);
            $data['substream'] = $this->input->post('Substream', true);
            $data['specialization'] = $this->input->post('Specialization', true);
            $data['fees'] = $this->input->post('Total_Fees', true);
            $data['exams'] = $this->input->post('Exams_Accepted', true);
            $data['courseLevel'] = $this->input->post('Course_Level', true);
            $data['course'] = $this->input->post('Course', true);
            $data['mode'] = $this->input->post('Mode_of_study', true);
            $data['garbageWord'] = $this->input->post('garbageWord', true);
            $data['requestFrom'] = $this->input->post('requestFrom', true);
            $data['credential'] = $this->input->post('Credential', true);
            $data['isTrending'] = $this->input->post('isTrending', true);
            $data['typedKeyword'] = $this->input->post('typedKeyword', true);
            $data['isInterim'] = $this->input->post('isInterim', true);
            $ispwa = isset($_POST['ispwa']) ? $this->input->post('ispwa', true) : false;
            if (!empty($data['keyword'])) {
                $data['keyword'] = htmlspecialchars_decode($data['keyword']);
            }
        }
        // _p($data);die;
        $this->load->library("search/SearchV3/NationalSearchPageUrlGenerator");
        $urlGenerator      = new NationalSearchPageUrlGenerator();

        if($this->userid !== -1){
            $data['userId'] = $this->userid;
        }

        $closeSearchUrl = $urlGenerator->createClosedSearchUrl($data);
        if($returnUrl){
            return $closeSearchUrl;
        }
        if($ispwa)
        {
            echo json_encode(array('status' => "success", "data" => $closeSearchUrl,"message" => null));
        }
        else
        {
            echo json_encode(array('url'=>$closeSearchUrl));
        }
        exit;
    }

	public function loadSearchLocalityLayer() {
		$data = array();
		$data['cityId'] 					= $this->input->post('cityId');
		$locality 							= $this->input->post('localityFilterValues');
		$data['localityCount'] 				= $this->input->post('localityCount');
		$data['appliedLocality'] 			= $this->input->post('appliedLocality');
		$data['localitySearchText'] 		= $this->input->post('localitySearchText');

		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$this->locationRepository = $locationBuilder->getLocationRepository();

		$localityObjs = array(); $zoneIds = array();
		if(is_array($locality) && count($locality) > 0) {
			$localityObjs = $this->locationRepository->findMultipleLocalities($locality);
			foreach($localityObjs as $localityVal) {
				$zoneIds[] = $localityVal->getZoneId();
				$localityTree[$localityVal->getZoneId()]['localities'][$localityVal->getId()]['id']    = $localityVal->getId();
				$localityTree[$localityVal->getZoneId()]['localities'][$localityVal->getId()]['name']  = $localityVal->getName();
				$localityTree[$localityVal->getZoneId()]['localities'][$localityVal->getId()]['count'] = $data['locality'][$localityVal->getId()];
			}
		}
		
		//get zone objects
		$zoneObjs = array();
		$zoneIds = array_unique($zoneIds);
		if(!empty($zoneIds)) {
			$zoneObjs = $this->locationRepository->findMultipleZones($zoneIds);
		}

		//sort the zones and its localities, alphabetically
		foreach ($localityTree as $zoneId => $locality) {
			$localityTree[$zoneId]['id'] = $zoneId;
			if(!empty($zoneObjs[$zoneId])) {
				$localityTree[$zoneId]['name'] = $zoneObjs[$zoneId]->getName();
			} else {
				$localityTree[$zoneId]['name'] = 'Other Localities';
			}
			uasort($localityTree[$zoneId]['localities'], array('SearchV2', 'compareByName'));
		}
		uasort($localityTree, array('SearchV2', 'compareByName'));

		//after sorting, format it in final way
		$zoneIds = array(); $localityIds = array();
		$zoneLocalitiesArr = array();
		foreach($localityTree as $zoneId => $zone) {
			$zoneIds[] = $zone['id'];
			$zoneLocalitiesArr[] = array('id' => $zone['id'], 'name' => $zone['name'], 'type' => 'zone');
	        foreach($zone['localities'] as $localityId => $locality) {
	        	$localityIds[] = $locality['id'];
	            $zoneLocalitiesArr[] = array('id' => $locality['id'], 'zoneId' => $zone['id'], 'name' => $locality['name'], 'count' => $locality['count'], 'type' => 'locality');
	        }
	        $localityTree = array();
	        $localityTree['all'] = $zoneLocalitiesArr;
	    }
	    $localityTree['zoneIds'] = $zoneIds;
	    $localityTree['localityIds'] = $localityIds;

	    $data['localityFilterValues'] = $localityTree['all'];
	    $data['zoneIds']			  = $localityTree['zoneIds'];
		echo json_encode(array('html'=>$this->load->view('nationalCategoryList/filters/localities', $data, true), 'localityFilterValues'=>$data['localityFilterValues']));
	}

	public function testQER($keyword) {
		$this->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;
		$qerFilters = $solrSearcher->getQERFiltersForSearch($keyword);
		_p($qerFilters);
	}

	private function backToClosedSearchUrl($pageRequest){
		$this->load->library('search/SearchV3/nationalSearchPageRequest');
        $requestToGenerateFreshSearchUrl = new NationalSearchPageRequest(array('11'));

		$data['searchKeyword']      = $pageRequest->getSearchKeyword();
		$data['searchedAttributes'] = $pageRequest->getSearchedAttributesString();
		
		$data['qerResults']         = $pageRequest->getQERFiltersString();
		$initalAttributes           = $pageRequest->getSearchedAttributes();

		// set all those attributes which can be identified by QER in open search
		$temp = array('city'=>'city','state'=>'state','base_course'=>'baseCourse','specialization'=>'specialization','substream'=>'substream','stream'=>'stream','popular_group'=>'popularGroup','certificate_provider'=>'certificateProvider','education_type'=>'educationType','delivery_method'=>'deliveryMethod','course_type'=>'courseType','credential'=>'credential');
		foreach ($temp as $key => $value) {
			if(!empty($initalAttributes[$key])){
				$data[$value] = $initalAttributes[$key];
			}
		}
		
		$data['requestFrom'] = 'searchwidget';
		if(DO_SEARCHPAGE_TRACKING){
			$trackingSearchId = $pageRequest->getTrackingSearchId();
			if(!empty($trackingSearchId)){
				$data['trackingSearchId'] = $trackingSearchId;
			}
		}

		$requestToGenerateFreshSearchUrl->setData($data);
        return $requestToGenerateFreshSearchUrl->getUrl();
	}

	public function trackTupleClick(){
		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
			$this->trackmodel->trackTupleClick();
		}
	}

	public function trackSearchQuery(){
		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking

			$userId = '';
			if($this->userid !== -1){
				$userId = $this->userid;
			}
			$tracktype = $this->input->post('tracktype');
			if($tracktype == 'exams'){
				$this->trackmodel->trackExamSearch($userId);
			}
			else if($tracktype == 'careers'){
				$this->trackmodel->trackCareerSearch($userId);
			}
			else if($tracktype == 'institute'){
				$this->trackmodel->trackInstituteSearch($userId);
			}
			else if($tracktype == 'tag'){
				$this->trackmodel->trackTagSearch($userId);
			}
			else if($tracktype == 'question'){
				$trackingKeyId = $this->trackmodel->trackQuestionSearch($userId);
				echo $trackingKeyId;
			}
			else if($this->input->get('count') != -1){
				$data = array();
				$data['trackingSearchId'] = base64_decode(urldecode($this->input->get('ts')));
				$data['pageType'] = base64_decode(urldecode($this->input->get('pageType')));
				$data['entityId'] = base64_decode(urldecode($this->input->get('entityId')));
				$data['entityType'] = base64_decode(urldecode($this->input->get('entityType')));
				$data['count'] = base64_decode(urldecode($this->input->get('count')));
				$data['newKeyword'] = base64_decode(urldecode($this->input->get('newKeyword')));
				$data['criteriaApplied'] = base64_decode(urldecode($this->input->get('criteriaApplied')));
				$this->trackmodel->updateResultCount($data);
			}
		}
	}

	public function test() {
        _p(whiteListGetParams('23::67_utA(*(')); die;
    }

	public function getResponseCountWithinSearchSessions($period) {
		$this->validateCron();
		$time_start = microtime_float(); $start_memory = memory_get_usage();

		$this->trackmodel = $this->load->model('trackingMIS/statsmodel');//for search tracking

		$periodArr[0]['startDate'] = '2017-12-01 00:00:00';
		$periodArr[0]['endDate'] = '2017-12-31 00:00:00';

		$periodArr[1]['startDate'] = '2018-01-01 00:00:00';
		$periodArr[1]['endDate'] = '2018-01-31 00:00:00';

		$periodArr[2]['startDate'] = '2018-02-01 00:00:00';
		$periodArr[2]['endDate'] = '2018-02-28 00:00:00';

		$periodArr[3]['startDate'] = '2018-03-01 00:00:00';
		$periodArr[3]['endDate'] = '2018-03-30 00:00:00';

		foreach ($periodArr as $key => $period) {
			$result = $this->trackmodel->getResponseCount($period, 'response');
			
			error_log("Period - ".$key."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Total responses in search sessions - ".$result['total_responses']."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Responses in search sessions breakup - ".print_r($result['response'], true)."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Total search sessions - ".$result['session']."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Cron execution - ".getLogTimeMemStr($time_start, $start_memory)."\n\n", 3, '/tmp/log_search_numbers.log');
		}

		error_log("=======================SESSIONS WITH RESPONSES=====================\n", 3, '/tmp/log_search_numbers.log');
		
		foreach ($periodArr as $key => $period) {
			$result = $this->trackmodel->getResponseCount($period, 'session');
			
			error_log("Period - ".$key."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Total Sessions with responses - ".$result['total_responses']."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Total search sessions - ".$result['session']."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Sessions with responses breakup - ".print_r($result['response'], true)."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Cron execution - ".getLogTimeMemStr($time_start, $start_memory)."\n\n", 3, '/tmp/log_search_numbers.log');
		}

		error_log("=======================TOTAL RESPONSES=====================\n", 3, '/tmp/log_search_numbers.log');

		foreach ($periodArr as $key => $period) {
			$result = $this->trackmodel->getResponseCount($period, 'total_responses');
			
			error_log("Period - ".$key."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Total responses in this month - ".$result."\n", 3, '/tmp/log_search_numbers.log');
			error_log("Cron execution - ".getLogTimeMemStr($time_start, $start_memory)."\n\n", 3, '/tmp/log_search_numbers.log');
		}
	}
	function pwaClosedSearchUrl()
	{
		switch (ENVIRONMENT) {
			case 'development':
				$requestHeader = "http://localshiksha.com:3022";
				break;

			case 'test1':
				$requestHeader = "https://pwa.shikshatest01.infoedge.com";
				break;

			case 'production':
				$requestHeader = SHIKSHA_HOME;
				break;
		}
		header("Access-Control-Allow-Origin: ".$requestHeader);
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
		header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
		header("Content-Type: application/json; charset=utf-8");
		$this->createClosedSearchUrl();
	}
}
