<?php 


class MsearchV3 extends ShikshaMobileWebSite_Controller {

	public function __construct() {
		parent::__construct();

		$this->userStatus = $this->checkUserValidation();
        if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid=$this->userStatus[0]['userid'];
        } else {
            $this->userid=-1;
        }

        $this->load->config("search/SearchPageConfig");
		$this->load->config("nationalCategoryList/nationalConfig");
		$this->load->config('CollegeReviewForm/collegeReviewConfig'); 
		$this->load->helper('security');
		$this->load->helper('image');
	}

	private function _initCourseSearch() {
        //$this->AutoSuggestorInitLib = $this->load->library("search/Autosuggestor/AutoSuggestorInitLib");
		
		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
		}
		$this->load->library('msearch5/msearchDisplayObject');
		$this->load->helper('listingCommon/listingcommon');

		$this->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();

	    // get institute repository with all dependencies loaded
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();
	    
	    $this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
	    $this->streamRepository = $listingBase->getStreamRepository();

	    $this->cmpObj = $this->load->library('comparePage/comparePageLib');

	    $this->load->library('nationalCourse/CourseDetailLib');
		$this->courseDetailLib = new CourseDetailLib;

		$this->fieldAlias = $this->config->item('FIELD_ALIAS');

		$this->institutedetailsmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
		$this->instituteDetailLib = $this->load->library('nationalInstitute/instituteDetailLib');
	}

	public function index(){
		$this->_initCourseSearch();
		//$_REQUEST['newSearch'] = 1;
		
		$this->load->builder('SearchBuilderV3','search');
		$searchBuilderV3 = new SearchBuilderV3();
		$this->request   = $searchBuilderV3->getRequest();
		
		$this->getDebugInfo();
		
		$searchRepository = $searchBuilderV3->getSearchRepository();
		$data             = $searchRepository->getRawSearchData();
		
		$data['appliedFilters']  = $this->request->getAppliedFilters();
		$data['relevantResults'] = $this->request->getRelevantResults();
		$data['totalPages']      = ceil($data['totalInstituteCount']/$this->request->getPageLimit());

		$data['requestFrom']     = $this->request->getRequestFrom();
		$fieldAlias        		 = $this->fieldAlias;
		$combinedFilters 		 = $this->config->item('COMBINED_FILTERS');
		$filtersPossible 		 = $this->config->item('FILTERS_POSSIBLE');

		// HACK --- auto select single resultant stream (will happen only during open search) & redirect to closed search
		if(count($data['filters']['stream']) == 1 && !empty($data['requestFrom']) && !in_array($data['requestFrom'], array('filters','filterBucket'))) {
			$this->request->setSingleStreamClosedSearch(true);
			$url = $this->request->getUrl();
			$redirectUrl = $url.'&'.$fieldAlias['stream'].'[]='.$data['filters']['stream'][0]['id'];
			$redirectUrl .= "&".$fieldAlias['twoStepClosedSearch']."=1";
			redirect($redirectUrl, 'location', 301);
		}

		foreach ($data['filters'] as $key => $value) {
			if($key == 'location'){
				$filtersList[] = 'city';
				$filtersList[] = 'state';
				$filtersList[] = 'locality';
			}
			else if(in_array($key,array_keys($combinedFilters))){
				$filtersList[] = $key;
				foreach ($combinedFilters[$key] as $value) {
					$filtersList[] = $value;
				}
			}
			else{
				$filtersList[] = $key;
			}
		}
		// $data['jsonFiltersList'] = json_encode($filtersList);
		$data['filtersPossible'] = json_encode($filtersPossible);
		$data['trackForPages'] = true;

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
		
		if(DO_SEARCHPAGE_TRACKING){
			if(empty($data['isAjax'])){
				if($this->request->getRequestFrom() == 'filters'){
					$data['trackingFilterId'] = $this->trackmodel->trackFilterClick($this->request);
					$data['trackingSearchId'] = $this->request->getTrackingSearchId();
					$this->request->setTrackingFilterId($data['trackingFilterId']);
				}
				else{
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
							$trackData['isInterim'] = $this->request->getIsInterim();
							$data['trackingSearchId'] = $this->trackmodel->getTrackingIdForCourse($trackData);
							$this->request->setTrackingSearchId($data['trackingSearchId']);

							if(!empty($data['requestFrom']) && !in_array($data['requestFrom'],array('filters','filterBucket'))){
								$data['updateResultCountForTracking'] = true;
							}
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
		$data['trackingIds']['compare'] = MOBILE_NL_SEARCHV2_TUPLE_COMPARE;
		$data['trackingIds']['shortlist'] = MOBILE_NL_SEARCHV2_TUPLE_SHORTLIST;
		$data['trackingIds']['downloadBrochure'] = MOBILE_NL_SEARCHV2_TUPLE_DEB;

		$data['currentPage'] 	 = $this->request->getPageNumber();
		$data['pageNumber'] = $this->request->getCurrentPageNum();
		$keyword                 = $this->request->getOldKeyword();

		if(empty($keyword)){
           $keyword = $this->request->getSearchKeyword();
        }

		$data['keyword']          = $keyword;
		$data['searchKeyword']    = $this->request->getSearchKeyword();
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
		$filterBucketName         = $this->config->item('FILTER_NAME_MAPPING');
		$data['filterBucketName'] = json_encode($filterBucketName);
		
		$data['isAjax']           = ($this->input->is_ajax_request()) ? 1 : 0;
		$data['comparedData'] = $this->cmpObj->getComparedData('mobile');
		$finalCourseIds = array();
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
		
		$this->benchmark->mark('examTest_start');
        $eligibilityUrls = array();
		if(count($eligibility)>0){
        	$this->examPageLib    = $this->load->library('examPages/ExamPageLib');
        	$eligibilityUrls = $this->examPageLib->getExamMainUrlsById($eligibility);
		}
        $this->benchmark->mark('examTest_end');
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
		
		$data['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);
		foreach ($data['courseStatusData'] as $key => $value) {
			$courseStatus = implode(", ", $value['courseStatusDisplay']);
            $courseStatus = (strlen($courseStatus) > 112) ? substr($courseStatus, 0, 110).'..' : $courseStatus;
            unset($data['courseStatusData'][$key]);
            $data['courseStatusData'][$key] = $courseStatus;
		}
	
	    if(empty($data['isAjax'])){
	    	$data['gtmParams'] = $this->getGTMParams();
	    	$data['defaultFiltersApplied'] = $this->request->getDefaultSearchPageFilters();
	    }

	    $data['searchFilterData'] 		= json_encode($this->request->processPreSelectedSearchedFiters());
	    
	    $data['product'] = "MsearchV3";
	    $data['pageLimit'] = $this->request->getPageLimit();
		$this->_setPaginationData($data);

		if(empty($data['totalInstituteCount'])){
			// Stream List for ZRP
			$data['streamsArray'] = $this->streamRepository->getAllStreams();
	        // Sort streams by name
			uasort($data['streamsArray'], function($a,$b){return strcmp($a["name"], $b["name"]);});
		}
		
	    $data['isLazyLoad'] = $this->input->getRawRequestVariable('isLazyLoad');
	    $data['urlPassingAttribute']      = $this->prepareUrlNeededParams();
	    $data['singleStreamClosedSearch'] = $this->request->getSingleStreamClosedSearch();
		$data['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'SRP','page'=>'MOBILE');

	    //Aggregate Review Data 
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $data['courseWidgetData'] = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');

	    if(!empty($data['singleStreamClosedSearch'])){
	    	$streams = $this->request->getStream();
	    	$data['singleStreamClosedSearchStream'] = $streams[0];
	    }

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_SRP','pageType'=>'homepage');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		if($data['isLazyLoad']){
			$result['view'] = $this->load->view('msearch5/msearchV3/msearchPageBody',$data,TRUE);

			if($data['totalInstituteCount'] <= ((($data['currentPage'] - 1) * $this->request->getPageLimit()) + $data['institutes']['instituteCountInCurrentPage'])) {
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

	public function showQuestionSRP($tab) {
		$this->load->builder('SearchBuilderV3','search');
		$searchBuilderV3 = new SearchBuilderV3();

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
		$displayData['keyword'] = $this->questionRequest->getSearchKeyword();
		$displayData['product'] = "MsearchV3";
		$displayData['boomr_pageid'] = "MQuesSearchV3";
		$displayData['pageTab'] = "question_".$displayData['currentTab'];
		$displayData['trackingKeyIds']['quesFollow'] = MOBILE_NL_QUES_SEARCH_QUES_FOLLOW;
		$displayData['trackingKeyIds']['tagFollow'] = MOBILE_NL_QUES_SEARCH_TAG_FOLLOW;
		$displayData['trackingKeyIds']['writeAnswer'] = MOBILE_NL_QUES_SEARCH_WRITE_ANS;
		
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
			
			// //_p($trackingData);
			// $trackId = $this->trackmodel->getQuestionTrackingId($trackingData);
			// //_p($trackId); die;

			$trackId = $this->questionRequest->getTrackingKeyId();

			if(!empty($trackId)) {
				$this->trackmodel->updateQuestionResultCount($trackId, $displayData['tupleData']['resultCount']);
			}
		}
		if(empty($displayData['tupleData'])) {
			//load ZRP
			$displayData['isZRP'] = 1;

			$this->load->view('msearch5/msearchV3/QSRP/mZRP', $displayData);
			return;
		}

		$this->_setPaginationDataQSRP($displayData);
		//_p($displayData);die;
            foreach($displayData['tupleData']['result'] as $k1=>$res)
            {
            	//_p($res);die;
            	foreach($res['tags'] as $m=>$v)
            	{
                	$tagIdsForUrl[$v['tagId']]=$v['tagName'];
            	}
            	$qids[]=$k1;
            }
            $this->load->library('messageBoard/TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping(); 
            $tagTypeInfo = $tagUrlMappingObj->getTagsContentType($tagIdsForUrl,$qids);

            //_p($tagIdsForUrl);die;

            //_p($tagIds);die;
            $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($tagIdsForUrl);
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
            $displayData['tagUrlInfo']=$tagUrlInfo;
            
		$this->load->view('msearch5/msearchV3/QSRP/mSearchPageContent', $displayData);
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

        // URL NO 1.
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
		return $gtmParams;
	}

	public function loadMoreCourses(){
		$this->_initCourseSearch();

		$courseIds   = $this->input->post('courseIds');
		$instituteId = $this->input->post('instituteId');
		
		$courseIdArr = explode(',', $courseIds);

		if(!empty($courseIdArr) && !empty($courseIdArr[0])) {
			$institute = reset($this->instituteRepo->findWithCourses(array($instituteId=>$courseIdArr),array("basic"),array("basic","facility","eligibility","location")));
			$courses = $institute->getCourses();
			foreach($courseIdArr as $courseId){
				$finalCourseResult[$courseId] = array();
			}
			$finalCourseIds = array();
			$eligibility  =array();
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
						$displayObject->setDisplayDataObjectForLoadMore($courseObj, $data['appliedFilters']);
						$displayData['displayDataObject'][$courseId] = $displayObject;
						$finalCourseResult[$courseId] = $courseObj;
						$finalCourseIds[] = $courseId;
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
			$displayData['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'CTPG','page'=>'MOBILE');
			$collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
	        $displayData['courseWidgetData'] = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');
		}


		$displayData['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);

		foreach ($displayData['courseStatusData'] as $key => $value) {
			$courseStatus = implode(", ", $value['courseStatusDisplay']);
            $courseStatus = (strlen($courseStatus) > 112) ? substr($courseStatus, 0, 110).'..' : $courseStatus;
            unset($displayData['courseStatusData'][$key]);
            $displayData['courseStatusData'][$key] = $courseStatus;
		}

		$brochuresMailed = $_COOKIE['applied_courses'];
		if(empty($brochuresMailed)){
			$brochuresMailed = array();
		}else{
			$brochuresMailed = json_decode(base64_decode($brochuresMailed));
		}
		$displayData['brochuresMailed'] = $brochuresMailed;
		
		if($this->userStatus != 'false') {
			$userId = $this->userStatus[0]['userid'];
			$displayData['shortlistedCourses'] = Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $userId);
		}
		
		$displayData['product']   		   = $this->input->post('product');
		if($displayData['product'] == 'McategoryList') {
			$displayData['trackingIds']['compare'] = MOBILE_NL_CTPG_TUPLE_COMPARE;
			$displayData['trackingIds']['shortlist'] = MOBILE_NL_CATEGORY_TUPLE_COURSESHORTLIST;
			$displayData['trackingIds']['downloadBrochure'] = MOBILE_NL_CTPG_TUPLE_DEB;

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
		else { //search page
			$displayData['trackingIds']['compare'] = MOBILE_NL_SEARCHV2_TUPLE_COMPARE;
			$displayData['trackingIds']['shortlist'] = MOBILE_NL_SEARCHV2_TUPLE_SHORTLIST;
			$displayData['trackingIds']['downloadBrochure'] = MOBILE_NL_SEARCHV2_TUPLE_DEB;
		}
		$displayData['tuplenumber'] 	   = $this->input->post('tuplenum');
		$displayData['loadedCourseCount']  = $this->input->post('loadedCourseCount');
		$displayData['courses']            = array_filter(array_values($finalCourseResult));
		$displayData['institute']          = $institute;
		$displayData['comparedData']       = $this->cmpObj->getComparedData('mobile');

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
		// echo("<script>console.log('PHP: ".implode(',', $finalCourseIds)."');</script>");

		// 				//_p($finalCourseIds);
		//_p($displayData['courseWidgetData']);
		// 	die('aaaaaaaaaaaaa');



        echo json_encode(array('html'=>$this->load->view('msearch5/msearchV3/mexpandedTupleContent', $displayData, true)));
	}

	public function getSearchWidgetLayer($tab){

		if(empty($tab)){
			$tab = 'colleges';
		}

		$examMainLib = $this->load->library('examPages/ExamMainLib');
		$data = $examMainLib->getAllStreamsHavingExams();
		foreach ($data as $val) {
			$categories[$val['id']] = $val['name'];
		}
		$layer = $this->load->view("msearch5/msearchV3/searchWidgetLayer",array('tabRequested'=>$tab,'categories'=>$categories),true);
		echo $layer;
		exit;
	}

	public function createOpenSearchUrl($requestData = false, $returnURL = false) {
		$data         = array();
		
		$this->load->library("search/SearchV3/NationalSearchPageUrlGenerator");
		$urlGenerator      = new NationalSearchPageUrlGenerator();
		
		if(!empty($requestData)){
			$data['keyword']               	= $requestData['keyword'];
			$data['locations']             	= $requestData['locations'];
			$data['requestComingFrom'] 		= $requestData['requestFrom'];
		} else {
			$data['searchPage']            	= $this->input->post('search',true);
			$data['keyword']               	= $this->input->post('keyword',true);
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

	public function createClosedSearchUrl(){
		$data = array();
		$data['keyword']        = $this->input->post('keyword',true);
		$data['entityId']     = (int) $this->input->post('entityId',true);
		$data['entityType'] 	= $this->input->post('entityType',true);
		$data['locations']      = $this->input->post('locations',true);
		$data['stream'] 		= $this->input->post('Stream',true);
		$data['substream'] 		= $this->input->post('Substream',true);
		$data['specialization'] = $this->input->post('Specialization',true);
		$data['fees']           = $this->input->post('Total_Fees',true);
		$data['exams']          = $this->input->post('Exams_Accepted',true);
		$data['courseLevel']    = $this->input->post('Course_Level',true);
		$data['course']		    = $this->input->post('Course',true);
		$data['mode']           = $this->input->post('Mode_of_study',true);
		$data['garbageWord']    = $this->input->post('garbageWord',true);
		$data['requestFrom']    = $this->input->post('requestFrom',true);
		$data['typedKeyword']	= $this->input->post('typedKeyword',true);
		$data['isInterim']		= $this->input->post('isInterim',true);
		$ispwa         = isset($_POST['ispwa']) ? $this->input->post('ispwa',true) : false ;
		if(!empty($data['keyword'])){
			$data['keyword'] = htmlspecialchars_decode($data['keyword']);
		}
		
		$this->load->library("search/SearchV3/NationalSearchPageUrlGenerator");
		$urlGenerator      = new NationalSearchPageUrlGenerator();

		if($this->userid !== -1){
			$data['userId'] = $this->userid;
		}
		
		$closeSearchUrl = $urlGenerator->createClosedSearchUrl($data);

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

	public function getCourseTabUrl(){
		return;
		$instituteId        = $this->input->post('instituteId',true);
		$cityId        		= $this->input->post('city',true);
		$catId       		= $this->input->post('catId',true);
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder      = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$instObj             = $instituteRepository->find($instituteId);

		$params = array(
					'instituteId'=>$instObj->getId(),
					'instituteName'=>$instObj->getName(),
					'type'=>'institute',
					'locality'=>"",
					'city'=>$instObj->getMainLocation()->getCity()->getName()
				);

		$additionalURLParams = "?city=".$cityId;
		if($catId){
			$additionalURLParams = $additionalURLParams.'&cat='.$catId;
		}

		$courseTabUrl = listing_detail_course_url($params) . $additionalURLParams;
		echo json_encode(array('url'=>$courseTabUrl));
		exit;
	}

	
	function runTopSearches() {
		return;
        $this->load->library("search/SearchV2/searchPageRequest");
		
		$this->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;
		
		$this->load->builder('SearchBuilder','search');
        $this->solrSearchSever = SearchBuilder::getSearchServer();
		
		$keywords = array(	'bca Delhi NCR',
							'mca',
							'bca',
							'mba',
							'Architecture',
							'mba India',
							'LLb',
							'Bcom',
							'Hotel Management',
							'sap',
							'mca india',
							'Amity University Noida',
							'bba',
							'B.Ed',
							'BBA / BBM / BBS',
							'Animation',
							'b ed India',
							'college india',
							'BEd',
							'Engineering courses',
							'mba part time',
							'B.Com',
							'Full Time MBA / PGDM',
							'Lovely Professional University',
							'Executive MBA',
							'pharmacy India',
							'B.arch',
							'BSc',
							'Phd',
							'BCA India');

		foreach ($keywords as $keyword) {
			$data         = array();
			$data['searchKeyword'] = $keyword;			

			//hitting qer api
			$qerFilters = array();
	        $qerFilters = $solrSearcher->getQERFiltersForSearch($keyword);
	        
	        //function to prepare QerString 
	        $this->prepareQerString($data,$qerFilters,'open');
			
			// persist inital search
			$this->prepareSearchedAttributesString($data);
			
			// set data in search page request
			$request      = new SearchPageRequest();
			$request->setData($data);

			//get open search url from search page request
			$openSearchUrl = $request->getUrl();
			if(empty($openSearchUrl)) {
				$openSearchUrl = 'No Url Found';
			}

			$content = unserialize($this->solrSearchSever->curl_mobile($openSearchUrl));
			_p($openSearchUrl);
		}
	}

	function runTopClosedSearches() {
		return;
		$this->load->library("search/SearchV2/searchPageRequest");
		
		$this->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;
		
		$this->load->builder('SearchBuilder','search');
        $this->solrSearchSever = SearchBuilder::getSearchServer();
		
		$subcategories = array('23', '56', '59', '24', '27', '25', '26');
		$categories = array('3', '2', '2', '3', '3', '3', '3');
		
		foreach ($subcategories as $key=>$subcategoryId) {
			$data         		   = array();
			$data['searchKeyword'] = 'keyword';
			$data['categoryId']    = $categories[$key];
			$data['subCategoryId'] = $subcategoryId;
			$data['requestFrom']   = 'searchwidget';
			
			// set data in search page request
			$request = new SearchPageRequest();
			$request->setData($data);

			//get open search url from search page request
			$closedSearchUrl = $request->getUrl(); 
			if(empty($closedSearchUrl)) {
				$closedSearchUrl = 'No Url Found';
			}
			
			$content = unserialize($this->solrSearchSever->curl_mobile($closedSearchUrl));
			_p($closedSearchUrl);
		}
	}

	public function performanceAnalysis() {
		return;
		$handle = fopen(LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME, "r");
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
	    //_p($parentArr); die;

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
			else if($tracktype == 'question'){
				$trackingKeyId = $this->trackmodel->trackQuestionSearch($userId);
				echo $trackingKeyId;
			}
			else if($tracktype == 'institute'){
				$this->trackmodel->trackInstituteSearch($userId);
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

	public function trackTupleClick(){
		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
			$this->trackmodel->trackTupleClick();
		}
	}

	private function prepareUrlNeededParams(){
		$data = array();
		$data['q'] = xss_clean($this->request->getSearchKeyword());
		if($this->request->getQERFiltersString())
			$data['qer'] = xss_clean($this->request->getQERFiltersString());
		
		$data['sa'] = xss_clean($this->request->getSearchedAttributesString());
		$data['tscs'] = xss_clean($this->request->getTwoStepClosedSearch());
		$data['sscs'] = xss_clean($this->request->getSingleStreamClosedSearch());
		$relevantResults = $this->request->getRelevantResults();
		if(!empty($relevantResults)){
			$data['rr'] = xss_clean($relevantResults);
		}
		$oldKeyword = $this->request->getOldKeyword();
		if(!empty($oldKeyword)){
			$data['okw'] = xss_clean($oldKeyword);
		}
		return json_encode($data);
	}

	private function _setPaginationData(& $data){
		$lazyLoadsPerPage = 4;
		$data['lazyLoadsPerPage'] = $lazyLoadsPerPage;
		$pagereq = clone $this->request;
		if($data['currentPage'] > $data['totalPages'] && $data['totalPages'] > 0){ //safe check
		    $pagereq->setPageNumber(1);
		    redirect($pagereq->getUrlForPagination(1),'location',301);
		}
		
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
            $pagereq->setPageNumber($currentPageNum - $lazyLoadsPerPage);
            $data['paginationURLS']['leftArrow']['url'] = $pagereq->getUrlForPagination($currentPageNum - $lazyLoadsPerPage);
        }

        // URL NO 1.
        if($currentPageSlot == 1){
        	$data['paginationURLS'][0]['isActive'] = true;
        	$data['paginationURLS'][0]['url'] = "";
        	$data['paginationURLS'][0]['text'] = $currentPageSlot;
        }
        elseif($currentPageSlot == 2) {
  			$pagereq->setPageNumber($currentPageNum - $lazyLoadsPerPage);
        	$data['paginationURLS'][0]['url'] = $pagereq->getUrlForPagination($currentPageNum - $lazyLoadsPerPage);
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 1;	
        }
        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
        	$pagereq->setPageNumber($currentPageNum - (3 * $lazyLoadsPerPage));
        	$data['paginationURLS'][0]['url'] = $pagereq->getUrlForPagination($currentPageNum - (3 * $lazyLoadsPerPage));
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 3;		
        }
        else{
        	$pagereq->setPageNumber($currentPageNum - (2 * $lazyLoadsPerPage));
        	$data['paginationURLS'][0]['url'] = $pagereq->getUrlForPagination($currentPageNum - (2 * $lazyLoadsPerPage));
        	$data['paginationURLS'][0]['text'] = $currentPageSlot - 2;		
        }

        // URL NO 2
        if($maxPageSlots >= 2){
        	if($currentPageSlot == 1){
	        	$pagereq->setPageNumber($currentPageNum + $lazyLoadsPerPage);
	        	$data['paginationURLS'][1]['url'] = $pagereq->getUrlForPagination($currentPageNum + $lazyLoadsPerPage);
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot + 1;
	        }
	        elseif($currentPageSlot == 2) {
	  			$data['paginationURLS'][1]['isActive'] = true;
	        	$data['paginationURLS'][1]['url'] = "";
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot;	
	        }
	        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
	        	$pagereq->setPageNumber($currentPageNum - (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][1]['url'] = $pagereq->getUrlForPagination($currentPageNum - (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot - 2;		
	        }
	        else{
	        	$pagereq->setPageNumber($currentPageNum - (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][1]['url'] = $pagereq->getUrlForPagination($currentPageNum - (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][1]['text'] = $currentPageSlot - 1;		
	        }
        }

        // URL NO 3
        if($maxPageSlots >= 3){
        	if($currentPageSlot == 1){
	        	$pagereq->setPageNumber($currentPageNum + (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][2]['url'] = $pagereq->getUrlForPagination($currentPageNum + (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][2]['text'] = $currentPageSlot + 2;
	        }
	        elseif($currentPageSlot == 2) {
	        	$pagereq->setPageNumber($currentPageNum + (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][2]['url'] = $pagereq->getUrlForPagination($currentPageNum + (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][2]['text'] = $currentPageSlot + 1;	
	        }
	        elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
	        	$pagereq->setPageNumber($currentPageNum - (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][2]['url'] = $pagereq->getUrlForPagination($currentPageNum - (1 * $lazyLoadsPerPage));
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
	        	$pagereq->setPageNumber($currentPageNum + (3 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['url'] = $pagereq->getUrlForPagination($currentPageNum + (3 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 3;
	        }
	        elseif($currentPageSlot == 2) {
	        	$pagereq->setPageNumber($currentPageNum + (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['url'] = $pagereq->getUrlForPagination($currentPageNum + (2 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 2;	
	        }
	        elseif($currentPageSlot == $maxPageSlots) {
	        	$data['paginationURLS'][3]['isActive'] = true;
	        	$data['paginationURLS'][3]['url'] = "";
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot;		
	        }
	        else{
	        	$pagereq->setPageNumber($currentPageNum + (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['url'] = $pagereq->getUrlForPagination($currentPageNum + (1 * $lazyLoadsPerPage));
	        	$data['paginationURLS'][3]['text'] = $currentPageSlot + 1;		
	        }
        }

        if($currentPageSlot < $maxPageSlots) { 
            $pagereq->setPageNumber($currentPageNum + $lazyLoadsPerPage);
            $data['paginationURLS']['rightArrow']['url'] = $pagereq->getUrlForPagination($currentPageNum + $lazyLoadsPerPage);
        }
	}

	public function getRecommendationLayer(){
		$this->_initCourseSearch();

		$this->load->builder('nationalCourse/CourseBuilder');
		$builder = new CourseBuilder();
		$courseRepository = $builder->getCourseRepository();
		unset($builder);
		
		$this->load->library('recommendation/alsoviewed');

		$courseId = (integer)$this->input->post('courseId');
		$pageType = $this->input->post('pageType');
		$ampViewFlag = !empty($_POST['ampViewFlag']) ? $this->input->post('ampViewFlag') : false;

		$results = $this->alsoviewed->getAlsoViewedCourses(array($courseId), 12);
		$courseIds = array_map(function($ele){return $ele['courseId'];}, $results);
		if(empty($courseIds)){
			echo json_encode(array('status'=>'fail'));
			return false;
		}
		$instituteIds = array_map(function($ele){return $ele['instituteId'];}, $results);
		$courseObjs = $courseRepository->findMultiple($courseIds,array('location','eligibility'));
		$instituteObjs = $this->instituteRepo->findMultiple($instituteIds,array('media','location'));
		$data = array();
		$finalCourseIds = array();

		global $COURSE_MESSAGE_KEY_MAPPING; 
 	    global $MESSAGE_MAPPING; 
 	    if(!empty($COURSE_MESSAGE_KEY_MAPPING[$courseId])){ 
 	            $data['customMsgText'] = $MESSAGE_MAPPING[$COURSE_MESSAGE_KEY_MAPPING[$courseId]]['onSiteMobile']; 
 	    }

		foreach ($instituteObjs as $instituteId => $instituteObj) {
			$isMultilocation    = count($instituteObj->getLocations()) > 1 ? true : false;
			if($isMultilocation) {
				$instituteIdsArray[] = $instituteId;
			}
		}

		if(!empty($instituteIdsArray)) {
			$instituteCourses = $this->instituteDetailLib->getAllCoursesForMultipleInstitutes($instituteIdsArray);
			foreach ($instituteCourses as $instituteId => $value) {
				$locationsMappedToCourses = $this->institutedetailsmodel->getUniqueCoursesLocations($value['courseIds']);
				$data['isInstituteMultilocation'][$instituteId] = count($locationsMappedToCourses) > 1 ? true : false;
		    	if($data['isInstituteMultilocation'][$instituteId]) {
		    		$data['isInstituteMultilocation'][$instituteId] = $instituteObjs[$instituteId]->getURL()."?showAllBranches=1";
		    	}
			}
		}

		$eligibility  =array();
		foreach ($courseObjs as $courseObj) {
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
				$displayObject = new msearchDisplayObject();
				$displayObject->setDisplayDataObjectForRecommendationOverlay($courseObj);
				$data['displayDataObject'][$courseId] = $displayObject;
				$finalCourseIds[] = $courseId;
			}
		}
		$eligibility = array_unique($eligibility);
		$eligibilityUrls = array();
		if(count($eligibility)>0){
        	$this->examPageLib    = $this->load->library('examPages/ExamPageLib');
        	$eligibilityUrls = $this->examPageLib->getExamMainUrlsById($eligibility);
		}
		$data['eligibilityUrls'] = $eligibilityUrls;
		
		$data['courseStatusData'] = $this->courseDetailLib->getCourseStatus($finalCourseIds);

		foreach ($data['courseStatusData'] as $key => $value) {
			$courseStatus = implode(", ", $value['courseStatusDisplay']);
            $courseStatus = (strlen($courseStatus) > 112) ? substr($courseStatus, 0, 110).'..' : $courseStatus;
            unset($data['courseStatusData'][$key]);
            $data['courseStatusData'][$key] = $courseStatus;
		}
		
		//Aggregate Review Data 
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $data['courseWidgetData'] = $collegeReviewLib->getAggregateReviewsForListing($finalCourseIds,'course');

		$data['courseObjs'] = $courseObjs;
		$data['instituteObjs'] = $instituteObjs;

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

		$layerTrackingIds = array();
		switch($pageType){
			case 'NM_CourseListing':
			case 'coursePage':
				if($ampViewFlag)
				{
					$layerTrackingIds['compare'] = 1223;
					$layerTrackingIds['downloadBrochure'] = 1222;
					$layerTrackingIds['shortlist'] = 1224;	
				}
				else
				{
					$layerTrackingIds['compare'] = 1074;
					$layerTrackingIds['downloadBrochure'] = 1073;
					$layerTrackingIds['shortlist'] = 1075;
				}
			break;
			case 'NM_InstituteDetailPage':
			case 'instituteDetailPage':
				if($ampViewFlag)
				{
					$layerTrackingIds['compare'] = 1230;
					$layerTrackingIds['downloadBrochure'] = 1229;
					$layerTrackingIds['shortlist'] = 1231;	
				}
				else
				{
				$layerTrackingIds['compare'] = 1064;
				$layerTrackingIds['downloadBrochure'] = 1063;
				$layerTrackingIds['shortlist'] = 1208;
				}
			break;
			case 'universityDetailPage':
				if($ampViewFlag)
				{
					$layerTrackingIds['compare'] = 1241;
					$layerTrackingIds['downloadBrochure'] = 1239;
					$layerTrackingIds['shortlist'] = 1240;	
				}
				else
				{
					$layerTrackingIds['compare'] = 1076;
					$layerTrackingIds['downloadBrochure'] = 1077;
					$layerTrackingIds['shortlist'] = 1070;
				}
			break;
			case 'NM_Category': //to cater page type passed for shortlist 
			case 'McategoryList':
				$layerTrackingIds['compare'] = MOBILE_NL_CTPG_TUPLE_COMPARE_RECO;
				$layerTrackingIds['downloadBrochure'] = MOBILE_NL_CTPG_TUPLE_DEB_RECO;
				$layerTrackingIds['shortlist'] = MOBILE_NL_CTPG_SHORTLIST_RECO;
			break;
			case 'mobileRankingPage': //to cater page type passed for shortlist 
            case 'rankingMobile':
            case 'mRankingPage':
				$layerTrackingIds['compare'] = 1095;
				$layerTrackingIds['downloadBrochure'] = 1096;
				$layerTrackingIds['shortlist'] = 1097;
			break;
			case 'allContentPage':
			case 'admission':
                $layerTrackingIds['compare'] = 1098;
                $layerTrackingIds['downloadBrochure'] = 1099;
                $layerTrackingIds['shortlist'] = 1100;
            break;
            case 'NM_SERP': //to cater page type passed for shortlist 
            case 'MsearchV3':
            	$layerTrackingIds['compare'] = MOBILE_NL_SEARCHV2_TUPLE_COMPARE_RECO;
				$layerTrackingIds['downloadBrochure'] = MOBILE_NL_SEARCHV2_TUPLE_DEB_RECO;
				$layerTrackingIds['shortlist'] = MOBILE_NL_SEARCHV2_TUPLE_SHORTLIST_RECO;
			break;
			case 'NM_ALL_COURSES': //to cater page type passed for shortlist 
			case 'MAllCoursesPage':
            	$layerTrackingIds['compare'] = MOBILE_NL_ALL_COURSES_TUPLE_COMPARE_RECO;
				$layerTrackingIds['downloadBrochure'] = MOBILE_NL_ALL_COURSES_TUPLE_DEB_RECO;
				$layerTrackingIds['shortlist'] = MOBILE_NL_ALL_COURSES_SHORTLIST_RECO;
			break;
			case 'questions':
            	$layerTrackingIds['compare'] = 1159;
				$layerTrackingIds['downloadBrochure'] = 1158;
				$layerTrackingIds['shortlist'] = 1065;
			break;
			case 'reviews':
            	$layerTrackingIds['compare'] = 1155;
				$layerTrackingIds['downloadBrochure'] = 1154;
				$layerTrackingIds['shortlist'] = 1156;
			break;
			case 'articles':
            	$layerTrackingIds['compare'] = 1150;
				$layerTrackingIds['downloadBrochure'] = 1149;
				$layerTrackingIds['shortlist'] = 1151;
			break;
			case 'scholarships':
            	$layerTrackingIds['compare'] = 1601;
				$layerTrackingIds['downloadBrochure'] = 1599;
				$layerTrackingIds['shortlist'] = 1603;
			break;
			case 'articleDetailPage':
				$layerTrackingIds['compare'] = 1272;
				$layerTrackingIds['downloadBrochure'] = 1271;
				$layerTrackingIds['shortlist'] = 1270;
			break;
			case 'entityWidget_articleDetailPage':
				$layerTrackingIds['compare'] = 1284;
				$layerTrackingIds['downloadBrochure'] = 1283;
				$layerTrackingIds['shortlist'] = 1282;
			break;
			case 'entityWidget_questionDetailPage':
				$layerTrackingIds['compare'] = 1370;
				$layerTrackingIds['downloadBrochure'] = 1369;
				$layerTrackingIds['shortlist'] = 1368;
			break;
			case 'entityWidget_questionDetailPage':
				$layerTrackingIds['compare'] = 1370;
				$layerTrackingIds['downloadBrochure'] = 1369;
				$layerTrackingIds['shortlist'] = 1368;
			break;
			case 'iim_percentile':
				$layerTrackingIds['compare'] = 1973;
				$layerTrackingIds['downloadBrochure'] = 1971;
				$layerTrackingIds['shortlist'] = 1975;
			break;
			case 'iimCallPredictor':
				$layerTrackingIds['compare'] = 2003;
				$layerTrackingIds['downloadBrochure'] = 2001;
				$layerTrackingIds['shortlist'] = 2005;
			break;
		}
		
		$data['trackingIds'] = $layerTrackingIds;
		$data['product'] = 'brochureRecoLayer';
		$data['ampViewFlag'] = $ampViewFlag;
        $data['comparedData'] = $this->cmpObj->getComparedData('mobile');   
		$data['ga_exam'] = array('attr'=>'TUPLE_EXAM','optlabel'=>'RECO','page'=>'MOBILE');
		$html = $this->load->view('msearch5/msearchV3/recommendationLayer',$data,true);
		echo json_encode(array('status'=>'success','html'=>$html));
	}

	/*
	below function is used for creating search widget page for AMP
	*/

	function searchWidgetPage()
	{

		$tab = 'colleges';
		
		$examMainLib = $this->load->library('examPages/ExamMainLib');
		$data = $examMainLib->getAllStreamsHavingExams();
		foreach ($data as $val) {
			$categories[$val['id']] = $val['name'];
		}

		$displayData['beaconTrackData'] = array(
                                    'pageIdentifier' => 'searchWidgetPage',
                                );
	 	$displayData['gtmParams'] = array(
                    "pageType"    => 'searchWidgetPage',
                    "countryId"     => 2
            );

		if($this->userid > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        if($this->userid !== -1){
			$displayData['userId'] = $this->userid;
		}
        
        $displayData['categories'] = $categories;
        $displayData['tabRequested'] = $tab;

		$this->load->view("msearch5/msearchV3/SearchWidgetPage",$displayData);
	}
	function pwaClosedSearchUrl()
	{
		$requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
		header("Access-Control-Allow-Origin: ".$requestHeader);
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
		header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
		header("Content-Type: application/json; charset=utf-8");
		$this->createClosedSearchUrl();
	}

    function pwaOpenSearchUrl()
    {
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        error_log($requestHeader);
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
        $this->createOpenSearchUrl();
    }
}
