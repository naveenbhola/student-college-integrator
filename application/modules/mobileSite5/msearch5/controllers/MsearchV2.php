<?php 

class MsearchV2 extends ShikshaMobileWebSite_Controller {

	public function __construct(){
		parent::__construct();
		$this->userStatus = $this->checkUserValidation();
        if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid=$this->userStatus[0]['userid'];
        } else {
            $this->userid=-1;
        }

        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();

		$this->AutoSuggestorInitLib = $this->load->library("search/Autosuggestor/AutoSuggestorInitLib");
		$this->searchPageDataProcessor = $this->load->library("search/SearchV2/SearchPageDataProcessor");
		$this->nationalCourseLib = $this->load->library("listing/NationalCourseLib");
		$this->load->service("categoryList/CurrencyConverterService");
		$this->currencyConvertService 	= new CurrencyConverterService('INR');

		$this->load->config("search/SearchPageConfig");
		$this->trackmodel = $this->load->model('searchmatrix/searchmatrixmodel');//for search tracking
		$this->load->helper('security');
	}

	public function index(){
		global $serverStartTime;
		if(LOG_SEARCH_PERFORMANCE_DATA)
		    error_log("Section: Reached index function | ".getLogTimeMemStr($serverStartTime)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
		// echo "Search Result Page";
		// exit;
		$_REQUEST['newSearch'] = 1;
		$this->load->builder('SearchBuilderV2','search');
		$searchBuilderV2           			= new SearchBuilderV2();
		$request                   			= $searchBuilderV2->getRequest();
		$displayData               			= array();

		$displayData['updateResultCountForTracking'] = false;
		$displayData['trackForPages'] = true; //For JSB9 Tracking
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
			error_log("Section: Search request loaded | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		
		$displayData['requestFrom'] = $request->getRequestFrom();
		
		if(in_array($displayData['requestFrom'],array('subcatopentoclose','subcatclosetoclose'))){
			$request->setData(array('requestFrom'=>'searchwidget'));
			redirect($request->getUrl(),'location');
		}

		$searchPage                			= $searchBuilderV2->getSearchPage();
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
		$displayData['filters']				= json_encode($searchPage->getFilters());
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Load filters data, total time | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['filterBucketName']	= json_encode($searchPage->getFilterBucketName());
		$displayData['totalInstituteCount'] = ($searchPage->getInstituteCount()) ? $searchPage->getInstituteCount() : 0;
		$selectedFiltersAndCount			= $searchPage->getSelectedFilters();
		$selectedFilters					= $selectedFiltersAndCount['selectedFilters'];
		$displayData['selectedFilters']		= $selectedFilters;
		$displayData['appliedFilterCount'] 	= json_encode($selectedFiltersAndCount['count']);
		$displayData['request']             = $request;
		$displayData['appliedFilters']      = $request->getAppliedFilters();
		$displayData['filterCount']			= $this->getBucketWiseCountOfAppliedFilters($displayData['appliedFilters']);
		$displayData['searchPageDataProcessorLib'] = $this->searchPageDataProcessor;
		$displayData['validateuser']		= $this->userStatus;
		$displayData['dns_prefetch']		= getDNSPrefetchLinks('MOBILE');
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Load other information about filters, total time | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$displayData['courseReviews']		= $this->nationalCourseLib->getCourseReviewCount($displayData['institutes']['popularCourses']);
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get courseReviews | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData['subCatId']			= $request->getSubCategoryId();
		$displayData['openSearch']			= false;
		$displayData['comparetrackingPageKeyId'] = MOBILE_NL_SEARCHV2_TUPLE_COMPARE;
		$displayData['currencyConvertService'] = $this->currencyConvertService;
		$displayData['searchFilterData'] 	= json_encode($request->processPreSelectedSearchedFiters());

		// $displayData['urlParaMapping'] 		= $this->config->item('SEARCH_PARAMS_FIELDS_ALIAS');

		$displayData['urlPassingAttribute']      = $this->prepareUrlNeededParams($request);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		if(empty($displayData['subCatId'])) { //populate only in case of open search
			$displayData['openSearch']			= true;
			$displayData['courseWiseSubcatIds']	= $this->getCourseSubcatIds($displayData['institutes']['instituteData']);
		}
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Get courseWiseSubcatIds | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		$displayData['boomr_pageid'] = 'searchV2';

		$displayData['autosuggestorConfigArray'] = $this->AutoSuggestorInitLib->createAutoSuggestorConfigArray(array('courseInstituteSearch', 'careers'));
		//$displayData['isAjax'] = ($_SERVER['HTTP_X_REQUESTED_WITH'] != '') ? 1 : 0;
		$displayData['isAjax'] = ($this->input->is_ajax_request()) ? 1 : 0;
		
  		if(empty($displayData['totalInstituteCount'])){
            $this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
            $this->rankingCommonLib    = RankingPageBuilder::getRankingPageCommonLib();
            $displayData['categories'] = $this->rankingCommonLib->getCategories();
        }

		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Total Page load without view | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

		$displayData['isLazyLoad'] = $this->input->getRawRequestVariable('isLazyLoad');
		if($displayData['isLazyLoad']){
			$result['view'] = $this->load->view('msearch5/msearchV2/msearchPageBody',$displayData,TRUE);
			if($displayData['totalInstituteCount'] <= ((($request->getCurrentPageNum() - 1) * SEARCH_PAGE_LIMIT_MOBILE) + $displayData['institutes']['instituteCountInCurrentPage'])) {
				$result['message'] = 'disableNextLazyLoad';
			} else {
				$result['message'] = 'ok';
			}
			echo json_encode($result);
		}else if($displayData['requestFrom'] == 'filterBucket'){
			echo json_encode(array('filters'=>$searchPage->getFilters(),'selectedFilters'=>array_diff_key($selectedFilters,array("subCategory"=>'',"catId"=>'')),'appliedFilterCount'=>$selectedFiltersAndCount['count'],'filterBucketName'=>$searchPage->getFilterBucketName()));
		}else{
			$this->load->view('msearch5/msearchV2/msearchPageContent',$displayData);
		}

		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Total Page load with view | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n\n\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
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

	public function loadMoreCourses(){
		$courseIds   = $this->input->post('courseIds');
		$instituteId = $this->input->post('instituteId');
		$subCatId    = $this->input->post('subCatId');
		$courseIdArr = explode(',', $courseIds);

		if($instituteId == 35861){
            ini_set("memory_limit","250M");
        }

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

        $displayData = array();
        $displayData['tuplenumber'] 	   = $this->input->post('tuplenum');
        $displayData['loadedCourseCount']  = $this->input->post('loadedCourseCount');

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

        $displayData['courses']                    = $courses;
        $displayData['subCatId']                   = $subCatId;
        $displayData['institute']                  = $institute;
        $displayData['searchPageDataProcessorLib'] = $this->searchPageDataProcessor;
        $displayData['openSearch']				   = false;
        $displayData['comparetrackingPageKeyId']   = MOBILE_NL_SEARCHV2_TUPLE_COMPARE;
        $displayData['currencyConvertService'] = $this->currencyConvertService;

        $displayData['courseReviews']              = $this->nationalCourseLib->getCourseReviewCount($courseIdArr);
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
        $displayData['request']				= $request;
        echo json_encode(array('html'=>$this->load->view('msearch5/msearchV2/mexpandedTupleContent', $displayData, true)));
	}

	public function getSearchWidgetLayer($tab){

		if(empty($tab)){
			$tab = 'colleges';
		}

		global $subCategoriesEntranceExamGNB;
		ksort($subCategoriesEntranceExamGNB);

		$categories = $subCategoriesEntranceExamGNB;
		foreach($categories as $key => $val){
			$categories[$key] = $key;
		}

		$layer = $this->load->view("msearch5/msearchV2/searchWidgetLayer",array('tabRequested'=>$tab,'categories'=>$categories),true);
		echo $layer;
		exit;
	}

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

		$data['tracking']					= true;

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
		$data['minimumFees']	= $this->input->post('Minimum_Fees',true);

		$data['tracking']		= true;

		if($this->userid !== -1){
			$data['userId'] = $this->userid;
		}
		
		$this->load->library("search/SearchV2/SearchPageUrlGenerator");
		$urlGenerator      = new SearchPageUrlGenerator();
		
		$closeSearchUrl = $urlGenerator->createClosedSearchUrl($data);

		echo json_encode(array('url'=>$closeSearchUrl));
		exit;
	}

	public function getCourseTabUrl(){
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

	public function prepareUrlNeededParams($request){
		$data = array();
		$data['q'] = xss_clean($request->getSearchKeyword());
		$data['c'] = xss_clean($request->getCategoryId());
		$data['sc'] = xss_clean($request->getSubCategoryId());
		if($request->getQERFiltersString())
			$data['qer'] = xss_clean($request->getQERFiltersString());
		
		$data['sa'] = xss_clean($request->getSearchedAttributesString());
		$data['tscs'] = xss_clean($request->getTwoStepClosedSearch());
		$relevantResults = $request->getRelevantResults();
		if(!empty($relevantResults)){
			$data['rr'] = xss_clean($relevantResults);
		}
		$oldKeyword = $request->getOldKeyword();
		if(!empty($oldKeyword)){
			$data['okw'] = xss_clean($oldKeyword);
		}
		return json_encode($data);
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

			$content = unserialize($this->solrSearchSever->curl_mobile($openSearchUrl));
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
			$userId = '';
			if($this->userid !== -1){
				$userId = $this->userid;
			}
			$tracktype = $this->input->post('tracktype');
			if($tracktype == 'exams'){
				$this->trackmodel->trackExamSearch($userId);
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
}
