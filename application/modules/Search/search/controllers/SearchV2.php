<?php

class SearchV2 extends MX_Controller {
	
	private $AutoSuggestorInitLib;

	public function __construct(){
		parent::__construct();
		$this->userStatus = $this->checkUserValidation();
        if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid=$this->userStatus[0]['userid'];
        } else {
            $this->userid=-1;
        }
		$this->AutoSuggestorInitLib = $this->load->library("search/Autosuggestor/AutoSuggestorInitLib");
		$this->searchPageDataProcessor = $this->load->library("search/SearchV2/SearchPageDataProcessor");
		$this->nationalCourseLib = $this->load->library("listing/NationalCourseLib");

		$this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$this->categoryRepository = $categoryBuilder->getCategoryRepository();

		// $this->load->builder('LDBCourseBuilder','LDB');
		// $LDBCourseBuilder = new LDBCourseBuilder;
		// $this->LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		
		$this->caHelper = $this->load->library('CA/CAHelper');

		// loading config file
    	$this->load->config("search/SearchPageConfig");
		$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
	}

	private function _init() {
		$this->load->library('search/Searcher/SolrSearcher');
        $this->solrSearcher = new SolrSearcher;

        $this->load->library('search/Solr/AutoSuggestorSolrClient');
        $this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;
	}
		
	public function index() {
		$this->checkAndRedirectOldSearchUrls();
		if($this->input->get('source') == 'google'){
			$this->searchFromGoogle();
		}
		
		$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
		if($_GET['q'] == '') {
			$_GET['q'] = 'mba';
		}
		$_REQUEST['newSearch'] = 1;
		$this->load->builder('SearchBuilderV2','search');
		$searchBuilderV2           			= new SearchBuilderV2();
		$request                   			= $searchBuilderV2->getRequest();
		$displayData               			= array();
		
		$this->getDebugInfo($request, $searchPage);

		$displayData['updateResultCountForTracking'] = false;
		if(DO_SEARCHPAGE_TRACKING){
			if(in_array($request->getRequestFrom(), array('searchwidget','subcatopentoclose','subcatclosetoopen','zeroresultspage','landingpage','listpage','oldsearch','fromgoogle','subcatclosetoclose'))){
				$displayData['updateResultCountForTracking'] = true;
			}
			if(in_array($request->getRequestFrom(),array('subcatopentoclose','subcatclosetoclose','subcatclosetoopen'))){
				$userId = '';
				if($this->userid !== -1){
					$userId = $this->userid;
				}
				$newTrackingSearchId = $this->trackmodel->generateNewTrackingKey($request,$userId);
				$request->setData(array('trackingSearchId' => $newTrackingSearchId,'trackingFilterId' => ''));
			}
			if(trim($request->getRequestFrom()) == 'filters' && $request->getTrackingSearchId()){
				$trackingFilterId = $this->trackmodel->trackFilterClick($request);
				$request->setData(array('trackingFilterId' => $trackingFilterId));
			}
		}
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Search request loaded and tracking done | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();

		$searchPage                			= $searchBuilderV2->getSearchPage();
		$displayData['requestFrom'] = $request->getRequestFrom();

		$request->setData(array('requestFrom'=>''));
		$relevantResults = $searchPage->getRelevantResultsFlag();
		if(!empty($relevantResults)){
			$request->setData(array('relevantResults'=>$relevantResults));
		}
		$oldKeyword = $searchPage->getOldKeyword();
		if(!empty($oldKeyword)){
			$request->setData(array('oldKeyword'=>$oldKeyword));
		}
		$displayData['relevantResults'] = $request->getRelevantResults();//_p($displayData['relevantResults']);die;
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Set raw data from solr, total time | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['institutes']          = $searchPage->getInstitutes();
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Load resultant institute objects | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['filters']             = $searchPage->getFilters();
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Load filters data, total time | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData['totalInstituteCount'] = ($searchPage->getInstituteCount()) ? $searchPage->getInstituteCount() : 0;
		$displayData['selectedFilters']     = $searchPage->getSelectedFilters();
		$displayData['request']             = $request;
		$displayData['appliedFilters']      = $request->getAppliedFilters();
		$displayData['searchPageDataProcessorLib'] = $this->searchPageDataProcessor;
		$displayData['validateuser']		= $this->checkUserValidation();

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		//$displayData['courseReviews']		= $this->nationalCourseLib->getCourseReviewsData($displayData['institutes']['popularCourses']);
		$displayData['courseReviews']		= $this->nationalCourseLib->getCourseReviewCount($displayData['institutes']['popularCourses']);
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get courseReviews | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['courseQuestions']		= $this->caHelper->getQuestionCountForCourses($displayData['institutes']['popularCourses']);
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get courseQuestions | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		//$displayData['brochureURL'] 		= $this->nationalCourseLib->getMultipleCoursesBrochure($displayData['institutes']['popularCourses']);
		$displayData['brochureURL'] 		= $this->nationalCourseLib->getMultipleCoursesBrochureFromInsttObjects($displayData['institutes']['instituteData']);
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get brochureURL | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData['subCatId']			= $request->getSubCategoryId();
		$displayData['openSearch']			= false;

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		if(empty($displayData['subCatId'])) { //populate only in case of open search
			$displayData['openSearch']			= true;
			$displayData['courseWiseSubcatIds']	= $this->getCourseSubcatIds($displayData['institutes']['instituteData']);
		}
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get courseWiseSubcatIds | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		//shortlisted Course
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		if(isset($displayData['validateuser'][0]['userid'])) {
		 	$displayData['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/fetchShortlistedCoursesOfAUser',$displayData['validateuser'][0]['userid']);
		}
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get shortlisted courses | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		//function to generate fresh searchUrl (back to open search url)
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		if($request->getTwoStepClosedSearch() == true) {
			$backData['url']                   = $this->backToClosedSearchUrl($request);
			$backSubData                       = $searchPage->prepareBackToSearchUrlData();
			$backData['category']              = $backSubData['categoryName'];
			$backData['subCategory']           = $backSubData['subCategoryName'];
			$displayData['backToClosedSearch'] = $backData;
		}
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get back to open search url | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		// showing category and their corresponding sub categories for zero result page
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		if(empty($displayData['totalInstituteCount'])){
			$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
			$this->rankingCommonLib    = RankingPageBuilder::getRankingPageCommonLib();
			$displayData['categories'] = $this->rankingCommonLib->getCategories();	
		}
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get categories | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['searchFilterData'] 		= $request->processPreSelectedSearchedFiters();
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Process data to show search widget pre selected | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData['autosuggestorConfigArray'] = $this->AutoSuggestorInitLib->createAutoSuggestorConfigArray(array('courseInstituteSearch', 'careers'));
		$displayData['isAjax'] = ($_SERVER['HTTP_X_REQUESTED_WITH'] != '') ? 1 : 0;
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Model hit for TrackfilerKey | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
			
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Total Page load without view | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData['debugSortInfo'] = $searchPage->getDebugSortInfo();

		// The page view tracking information
		$displayData['beaconTrackData'] = array(
			'pageEntityId'   => 0,
			'pageIdentifier' => 'searchV2Page',
			'extraData'      => array(
				'url'           => SHIKSHA_HOME."/searchv2",
			)
		);

		$displayData['tracking_keyid_shortlist'] = DESKTOP_NL_SEARCHV2_TUPLE_SHORTLIST;
		$displayData['tracking_keyid_deb'] = DESKTOP_NL_SEARCHV2_TUPLE_DEB;

		$displayData['trackForPages']         = true; //For JSB9

		$displayData['comparetrackingPageKeyId'] = 628;

		$this->load->view('nationalV2/searchPageContent', $displayData);
		
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Total Page load with view | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n\n\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
	}

	private function getDebugInfo($request) {
		if(DEBUGGER) {
			$searchKeyword = $request->getSearchKeyword();
			$subcatId = $request->getSubCategoryId();
			$qerFilters = $request->getQERFilters();
			
			_p('Search keyword: '.$searchKeyword);

			if(empty($qerFilters) && !empty($subcatId)) {
				$subcatObj = $this->categoryRepository->find($subcatId);
				$subcatName = $subcatObj->getName();
				$garbageWord = substr($searchKeyword, strlen($subcatName));

				_p('Keyword identified as closed: '.$subcatObj->getName());
				_p('Subcat id identified: '.$subcatId);
				_p('Search type: Close');
				_p('QER used / QER return values applied: No');
				_p('------------------------------------');
				_p('Institutes sorted on: popularity for subcat '.$subcatId);
				_p('Courses within institute sorted on: course view count (yearly)');
			} else {
				if(empty($subcatId)) {
					_p('Keyword identified as closed: NULL');
					_p('Keyword identified as open(QER input): '.$searchKeyword);
					_p('Search type: Open');
					_p('-------------------------------------');
					if(!empty($qerFilters['institute']) || !empty($qerFilters['course'])) {
						if($qerFilters['customQer'] == 1) {
							_p('Courses within institute sorted on: max(popularities of subcat of ldbids identified)');
						} else {
							_p('Courses within institute sorted on: course view count (yearly)');
						}
						_p('Institutes sorted on: course view count (yearly) of course with highest view count');
					} else {
						_p('Sorted on solr score. Boosting applied.');
					}
				} else {
					$subcatObj = $this->categoryRepository->find($subcatId);
					$subcatName = $subcatObj->getName();
					$garbageWord = substr($searchKeyword, strlen($subcatName));
					_p('Keyword identified as open: '.trim($garbageWord));
					_p('Keyword identified as closed: '.$subcatName);
					_p('Subcat id identified: '.$subcatId);
					_p('Search type: Half open - half close');
					_p('------------------------------------');
					_p('Institutes sorted on: popularity for subcat '.$subcatId);
					_p('Courses within institute sorted on: course view count (yearly)');
				}
				_p('-------------------------------------');
				_p('QER hit: Yes');
				_p('QER output: '); _p($qerFilters);
			}
			_p('------------------------------------');
		}
	}

	public function backToClosedSearchUrl($pageRequest){
		$this->load->library('search/SearchV2/searchPageRequest');
        $requestToGenerateFreshSearchUrl = new SearchPageRequest(array('11'));
       
		$data['searchKeyword']      = $pageRequest->getSearchKeyword();
		$data['searchedAttributes'] = $pageRequest->getSearchedAttributesString();
		$data['qerResults']         = $pageRequest->getQERFiltersString();
		$initalAttributes           = $pageRequest->getSearchedAttributes();
		if(isset($initalAttributes['city']) && !empty($initalAttributes['city']))
			$data['city']               = $initalAttributes['city'];

		if(isset($initalAttributes['state']) && !empty($initalAttributes['state']))
			$data['state']               = $initalAttributes['state'];

		if(DO_SEARCHPAGE_TRACKING){
			$data['requestFrom'] = 'subcatclosetoopen';
		}
		$requestToGenerateFreshSearchUrl->setData($data);
        return $requestToGenerateFreshSearchUrl->getUrl();
	}

	public function loadMoreCourses() {
		$courseIds   = $this->input->post('courseIds');
		$instituteId = $this->input->post('instituteId');
		$subCatId    = $this->input->post('subCatId');
		$courseIdArr = explode(',', $courseIds);

		if($instituteId == 35861){
            ini_set("memory_limit","250M");
        }
		
		$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
		if(!empty($courseIdArr)) {
			$this->load->builder('SearchBuilderV2','search');
			$searchBuilderV2           			= new SearchBuilderV2();
			$request                   			= $searchBuilderV2->getRequest();
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$institute = reset($instituteRepository->findWithCourses(array($instituteId=>$courseIdArr)));
			$courses = $institute->getCourses();
		}
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In load more courses, load institute with courses object | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData = array();
		$displayData['tuplenumber'] 	   = $this->input->post('tuplenum');
		$displayData['loadedCourseCount']  = $this->input->post('loadedCourseCount');

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		if(DO_SEARCHPAGE_TRACKING){
			if($this->input->post('trackingFilterId') > 0){
				$request->setData(array('trackingFilterId' => $this->input->post('trackingFilterId')));
			}
			$request->setData(array(
									'trackingSearchId'	=>	$this->input->post('trackingSearchId'),
									'pageNumber'		=>	$this->input->post('pagenum')
									)
							 );
		}
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In load more courses, tracking done | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		$displayData['courses']                    = $courses;
		$displayData['subCatId']                   = $subCatId;
		$displayData['institute']                  = $institute;
		$displayData['searchPageDataProcessorLib'] = $this->searchPageDataProcessor;

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		//$displayData['courseReviews']              = $this->nationalCourseLib->getCourseReviewsData($courseIdArr);
		$displayData['courseReviews']              = $this->nationalCourseLib->getCourseReviewCount($courseIdArr);
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In load more courses, get course review data | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['courseQuestions']            = $this->caHelper->getQuestionCountForCourses($courseIdArr);
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In load more courses, get course question | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['brochureURL']                = $this->nationalCourseLib->getMultipleCoursesBrochure($courseIdArr);
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In load more courses, get brochure url | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		//get subcat ids of all courses
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		if(empty($displayData['subCatId'])) { //populate only in case of open search
			$displayData['openSearch']	= true;
			foreach ($courses as $key => $course) {
				$ldbCourseIds = $course->getLdbCourses();
		        foreach ($ldbCourseIds as $key => $ldbCourseId) {
		            $catObj = $this->categoryRepository->getCategoryByLDBCourse($ldbCourseId);
		            $subcatIds[$course->getId()][] = $catObj->getId();
		        }
			}
		}
		$displayData['courseWiseSubcatIds']	= $subcatIds;
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In load more courses, get course wise subcat | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		//shortlisted Course
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['validateuser']               = $this->checkUserValidation();
		if(isset($displayData['validateuser'][0]['userid'])) {
		 	$displayData['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/fetchShortlistedCoursesOfAUser',$displayData['validateuser'][0]['userid']);
		}
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In load more courses, get shortlisted courses | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData['openSearch']				   = false;
		$displayData['request']				   	   = $request;
		if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In load more courses, total time taken | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData['tracking_keyid_shortlist'] = DESKTOP_NL_SEARCHV2_TUPLE_SHORTLIST;
		$displayData['tracking_keyid_deb'] = DESKTOP_NL_SEARCHV2_TUPLE_DEB;

		$displayData['comparetrackingPageKeyId'] = 616;
		
		echo json_encode(array('html'=>$this->load->view('common/gridTuple/expandedTupleContent', $displayData, true)));
	}

	public function getCourseSubcatIds($instituteObjects) {
		$subcatIds = array();
		foreach ($instituteObjects as $instituteId => $instituteObj) {
			if(is_object($instituteObj)) {
				$course = reset($instituteObj->getCourses());
			}
			if(is_object($course)) {
				$ldbCourseIds = $course->getLdbCourses();
		        foreach ($ldbCourseIds as $key => $ldbCourseId) {
		        	if(!empty($ldbCourseId)) {
		            	$catObj = $this->categoryRepository->getCategoryByLDBCourse($ldbCourseId);
		            	$subcatIds[$course->getId()][] = $catObj->getId();
		        	}
		        }
		    }
		}
		return $subcatIds;
	}
	
	public function searchV2() {
		$this->_init();
		//closed search
		//$qer = 0;
		//$solrRequestData['pageType'] = '2';
		
		// $userAppliedFilters['subcatId'] = 23;
		// $subcatObj = $this->categoryRepository->find($userAppliedFilters['subcatId']);
		// $userAppliedFilters['catId'] = $subcatObj->getParentId();
		// $catObj = $this->categoryRepository->find($userAppliedFilters['catId']);
		// $userAppliedFilters['catName'] = $catObj->getShortName();

		//$userAppliedFilters['city'] = array(10223, 59);
		//$userAppliedFilters['state'] = array(123);
		//$userAppliedFilters['course'] = array(3, 5); //spec
		//$userAppliedFilters['fees'] = '500000'; //less than
		//$userAppliedFilters['exams'] = array('CAT_70', 'MAT_0');
		//$keyword = $subcatName // = $text;

		$solrRequestData['userAppliedFilters'] = $userAppliedFilters;
		//$userFiltersKeys = array_keys($userAppliedFilters);

		//open search
		$qer = 1;
		$keyword = 'amity noida';
		//$qerFilterKeys = array();
		if($qer) {
			$solrRequestData['qerFilters'] = $this->solrSearcher->getQERFiltersForSearch($keyword);
			//$solrRequestData['qerFilters']['city'] = array(10223, 161, 30);
			//$solrRequestData['qerFilters']['institute'] = array(41334, 36839, 44005, 36312);
			//$solrRequestData['qerFilters']['course'] = array(735, 285, 34, 1339, 3);
			//$solrRequestData['qerFilters']['state'] = array(108);
			//$solrRequestData['qerFilters']['locality'] = array(60, 49); //city: 30
			//$solrRequestData['qerFilters']['courseLevel'] = array('Masters');
			//$solrRequestData['qerFilters']['mode'] = array('Under Graduate');
			//$qerFilterKeys = array_keys($solrRequestData['qerFilters']);
		}
		
		$solrRequestData['pageLimit'] = 30;
		$solrRequestData['pageNum'] = 1;
		//$solrRequestData['appliedFilterKeys'] = array_merge($userFiltersKeys, $qerFilterKeys);
		//$userAppliedFilters = array('city'=>array(10223, 59));
		
		//$solrRequestData = array_merge_recursive($qerFilters, $userAppliedFilters);
		//_p($solrRequestData);
		$solrResult = $this->autoSuggestorSolrClient->getFiltersAndInstitutes($solrRequestData);
		_p($solrResult);
	}


	/**
	 * Method to create open search url through search box
	 * To create the open search url you need to set data in search page request 
	 * then call the getUrl method from search page request
	 * You need to set the following key
	 * searchKeyword
	 * city
	 * state
	 * qerResults(if you are hitting qer api)
	 * searchedAttributes (city and state)
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2015-07-29
	 * @return Open Search Url
	 */
	public function createOpenSearchUrl($requestData = false, $returnURL = false) {
		$data         = array();
		
		$this->load->library("search/SearchV2/SearchPageUrlGenerator");
		$urlGenerator      = new SearchPageUrlGenerator();
		
		
		if(!empty($requestData)){
			$data['keyword']               	= $requestData['keyword'];
			$data['locations']             	= $requestData['locations'];
			$data['requestComingFrom'] 		= $requestData['requestFrom'];
		} else {
			$data['keyword']               	= $this->input->post('keyword',true);
			$data['locations']             	= $this->input->post('locations',true);
			$data['requestComingFrom'] 		= $this->input->post('requestFrom');	
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

	

	/**
	 * Purpose: to append a qer results in url
	 * Url Qer String Order : q | city | state | locality | institute | course | level | mode
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2015-07-29
	 * @param  Array     &$data      
	 * @param  Array     $qerFilters result set from qer
	 * @return url qer String
	 */
	private function prepareQerString(& $data , $qerFilters,$type){
		// discard logic of filters based on subcategory filter in config
		if(isset($data['subCategoryId']) && $data['subCategoryId'] > 0){

            $FILTER_FACETS_ON_SUBCAT_SEARCH = $this->config->item('FILTER_FACETS_ON_SUBCAT_SEARCH');

			$filtersSubCategoryWise = $FILTER_FACETS_ON_SUBCAT_SEARCH[$data['subCategoryId']];
			if(empty($filtersSubCategoryWise)){
				$filtersSubCategoryWise = $FILTER_FACETS_ON_SUBCAT_SEARCH['default'];
			}

			if(!empty($filtersSubCategoryWise)){
				foreach ($qerFilters as $key => $value) {
					if($value == 'city' || $value == 'state' || $value == 'locality'){
						if(!in_array('location', $filtersSubCategoryWise)){
							unset($qerFilters[$value]);
						}
					}elseif ($value == 'course') {
						if(!in_array('specialization', $filtersSubCategoryWise)){
							unset($qerFilters[$value]);	
						}
					}elseif ($value == 'mode'){
						if(!in_array('mode', $filtersSubCategoryWise)){
							unset($qerFilters[$value]);	
						}
					}elseif ($value == 'level'){
						if(!in_array('courseLevel', $filtersSubCategoryWise)){
							unset($qerFilters[$value]);	
						}
					}
				}
			}
		}
		
		$qerParams = array();

		if(array_key_exists('q', $qerFilters)){
			$qerParams[]  = $qerFilters['q'];
		}else{
			$qerParams[]  = 0;
		}

		if(array_key_exists('city', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['city']);
			foreach ($qerFilters['city'] as $key => $value) {
				if(!in_array($value, $data['city']))
					$data['city'][]=$value;
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('state', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['state']);
			foreach ($qerFilters['state'] as $key => $value) {
				if(!in_array($value, $data['state']))
					$data['state'][]=$value;
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('locality', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['locality']);
			foreach ($qerFilters['locality'] as $key => $value) {
				$data['locality'][]=$value;
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('institute', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['institute']);
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('course', $qerFilters)){
			$filteredLDBCourseIds = array();
			if($type == 'close'){
				foreach ($qerFilters['course'] as $key => $value) {
		            $catObj = $this->categoryRepository->getCategoryByLDBCourse($value);
					if($data['subCategoryId'] == $catObj->getId()){
						$data['specialization'][] = $value;
						$filteredLDBCourseIds[] = $value; 
					}
				}
			
			if(!empty($filteredLDBCourseIds))	
				$qerParams[] = implode(',', $filteredLDBCourseIds);	

			}else{
				$qerParams[] = implode(',', $qerFilters['course']);
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('level', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['level']);
			if($type == 'close'){
				foreach ($qerFilters['level'] as $key => $value) {
					$data['courseLevel'][] = ucwords(str_replace("-", " ", $value));
				}
			}	
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('mode', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['mode']);
			if($type == 'close'){
				foreach ($qerFilters['mode'] as $key => $value) {
					$data['mode'][] = ucwords(str_replace("-", " ", $value));
				}
			}	
		}else{
			$qerParams[]  = 0;
		}
		
		if(array_key_exists('customQER', $qerFilters)) {
			$qerParams[]  = 1;
		} else {
			$qerParams[]  = 0;
		}

		$qerUrlString = implode("|", $qerParams);
		$data['qerResults'] = $qerUrlString;
	}

	/**
	 * To maintain the inital search of a user throughout the particular keyword search lifecycle
	 * Searched Attribute Order : city | state | exam | specialization | fees | course level | mode
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2015-07-29
	 * @param  [type]     $data [description]
	 * @return [type]           [description]
	 */
	private function prepareSearchedAttributesString(& $data){

			$searchedAttributesParams = array();
			if(array_key_exists('city', $data)){
				$searchedAttributesParams[] = implode(',', $data['city']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('state', $data)){
				$searchedAttributesParams[] = implode(',', $data['state']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('exams', $data)){
				$searchedAttributesParams[] = implode(',', $data['exams']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('specialization', $data)){
				$searchedAttributesParams[] = implode(',', $data['specialization']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('fees', $data)){
				$searchedAttributesParams[] = $data['fees'];
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('courseLevel', $data)){
				$searchedAttributesParams[] = implode(',', $data['courseLevel']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('mode', $data)){
				$searchedAttributesParams[] = implode(',', $data['mode']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			$searchedAttributesString = implode("^", $searchedAttributesParams);

			$data['searchedAttributes'] = $searchedAttributesString;
	}

	public function createClosedSearchUrl(){
		$data = array();
		$data['keyword']        = $this->input->post('keyword',true);
		$data['categoryId']     = (int) $this->input->post('categoryId',true);
		$data['selectedId'] 	= $this->input->post('subCategoryId',true);
		$data['locations']      = $this->input->post('locations',true);
		$data['specialization'] = $this->input->post('Specialisation',true);
		$data['fees']           = $this->input->post('Total_Fees',true);
		$data['exams']          = $this->input->post('Exams_Accepted',true);
		$data['courseLevel']    = $this->input->post('Course_Level',true);
		$data['mode']           = $this->input->post('Mode_of_study',true);
		$data['garbageWord']    = $this->input->post('garbageWord',true);
		$data['requestFrom']    = $this->input->post('requestFrom',true);
		
		$this->load->library("search/SearchV2/SearchPageUrlGenerator");
		$urlGenerator      = new SearchPageUrlGenerator();

		if($this->userid !== -1){
			$data['userId'] = $this->userid;
		}
		
		$closeSearchUrl = $urlGenerator->createClosedSearchUrl($data);

		echo json_encode(array('url'=>$closeSearchUrl));
		exit;
	}

	

	
	public function testQER($keyword) {
		$this->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;
		$qerFilters = $solrSearcher->getQERFiltersForSearch($keyword);
		_p($qerFilters);
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
		if(count($locality) > 0) {
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
		echo json_encode(array('html'=>$this->load->view('nationalV2/filters/localities', $data, true), 'localityFilterValues'=>$data['localityFilterValues']));
	}

	static function compareByName($a, $b) {
  		return strcmp($a["name"], $b["name"]);
	}

	public function trackSearchQuery(){
		if(DO_SEARCHPAGE_TRACKING){
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
			else if($this->input->get('count') != -1){
				$data = array();
				$data['trackingSearchId'] = $this->input->get('ts');
				$data['count'] = $this->input->get('count');
				$data['newKeyword'] = $this->input->get('newKeyword');
				$data['criteriaApplied'] = $this->input->get('criteriaApplied');
				$this->trackmodel->updateResultCount($data);
			}
		}
	}

	public function trackTupleClick(){
		if(DO_SEARCHPAGE_TRACKING){
			$this->trackmodel->trackTupleClick();
		}
	}

	public function handleOldListURL($urlString = NULL) {
		if(empty($urlString)){
			show_404();
		}
		$searchModel 	= $this->load->model('search/searchmodel');
		$oldURLDetails 	= $searchModel->getListURLData($urlString);
		if(empty($oldURLDetails)){
			show_404();
		}
		$redirectTo = $oldURLDetails['redirect_to'];
		switch($redirectTo) {
			case 'category':
				$categoryPageRequest = $this->load->library("categoryList/CategoryPageRequest");
				$requestData = array();
				$requestData['subCategoryId'] 	= $oldURLDetails['subcategory_id'];
				$requestData['cityId'] 			= $oldURLDetails['city_id'];
				if(!empty($requestData['subCategoryId'])) {
					$categoryObj 				= $this->categoryRepository->find($requestData['subCategoryId']);
					$requestData['categoryId'] 	= $categoryObj->getParentId();
				}
				if(in_array($requestData['subCategoryId'], array(23, 56))){
					$categoryPageRequest->setNewURLFlag(1);
				}
				$categoryPageRequest->setData($requestData);
				$url = $categoryPageRequest->getURL();
				break;
			case 'search':
				$requestData = array();
				$requestData['keyword'] 		= $oldURLDetails['keyword'];
				if(!empty($oldURLDetails['city_id'])){
					$requestData['locations'][] 	= "city_" . $oldURLDetails['city_id'];
				}
				$requestData['requestFrom'] 	= "listpage";
				$requestData['currentpageurl']  = $_SERVER['HTTP_REFERER'];
				$url = $this->createOpenSearchUrl($requestData, TRUE);
				break;
		}
		if(!empty($url)){
			redirect($url,'location', 301);
		} else {
			show_404();
		}
	}

	public function performanceAnalysis() {
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

	function runTopSearches() {
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

			$content = unserialize($this->solrSearchSever->curl($openSearchUrl));
			_p($openSearchUrl);
		}
	}

	function runTopClosedSearches() {
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
			
			// set data in search page request
			$request = new SearchPageRequest();
			$request->setData($data);

			//get open search url from search page request
			$closedSearchUrl = $request->getUrl();
			if(empty($closedSearchUrl)) {
				$closedSearchUrl = 'No Url Found';
			}
			
			$content = unserialize($this->solrSearchSever->curl($closedSearchUrl));
			_p($closedSearchUrl);
		}
	}

	private function checkAndRedirectOldSearchUrls(){
		$oldSearchKeyword = $this->input->get('keyword');
		if(!empty($oldSearchKeyword)){
			$requestData = array();
			$requestData['keyword'] 		= $oldSearchKeyword;
			$requestData['requestFrom'] 	= "oldsearch";
			if(DO_SEARCHPAGE_TRACKING){
				$requestData['currentpageurl'] = $_SERVER['HTTP_REFERER'];
			}
			$url = $this->createOpenSearchUrl($requestData,TRUE);
			redirect($url,'location', 301);
		}
	}

	public function getSaveSearch($start,$row){
    	return ;
    }

    /*Method is used when searching from google sitelinks box 
    Generate url and redirect to search url*/
    private function searchFromGoogle(){
    	$data         = array();
    	$keyword = $this->input->get('q',true);

    	$data['keyword'] 				= $keyword;
    	$data['currentpageurl']			= $_SERVER['HTTP_REFERER'];
    	$data['requestComingFrom'] 		= 'fromgoogle';

    	$this->load->library("search/SearchV2/SearchPageUrlGenerator");
    	$urlGenerator      = new SearchPageUrlGenerator();

    	$url = $urlGenerator->createOpenSearchUrl($data);//_p($url);die;
    	redirect($url,'location',301);
    }
}
