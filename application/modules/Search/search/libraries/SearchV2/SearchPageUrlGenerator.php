<?php 

class SearchPageUrlGenerator{

	private $CI;

	public function __construct()
	{
		$this->CI = & get_instance();

		$this->CI->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$this->categoryRepository = $categoryBuilder->getCategoryRepository();

		$this->CI->load->builder('LDBCourseBuilder','LDB');
		$LDBCourseBuilder = new LDBCourseBuilder;
		$this->LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		// loading config file
    	$this->CI->load->config("search/SearchPageConfig");
		$this->trackmodel = $this->CI->load->model('searchmatrix/searchmatrixmodel');//for search tracking
	}
	

	private function _init() {
		$this->CI->load->library('search/Searcher/SolrSearcher');
        $this->solrSearcher = new SolrSearcher;

        $this->CI->load->library('search/Solr/AutoSuggestorSolrClient');
        $this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;
	}

	public function createClosedSearchUrl($postData){
		$this->CI->load->library("search/SearchV2/searchPageRequest");
		$request = new SearchPageRequest();

		$this->CI->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;
		
		$keyword        = $postData['keyword'];
		$categoryId     = $postData['categoryId'];
		$selectedId  	= $postData['selectedId'];
		$locations      = $postData['locations'];
		$specialization = $postData['specialization'];
		$fees           = $postData['fees'];
		$exams          = $postData['exams'];
		$courseLevel    = $postData['courseLevel'];
		$mode           = $postData['mode'];
		$garbageWord    = $postData['garbageWord'];
		
		if(!empty($selectedId)) {
			$selectedIdArr = explode('-', $selectedId);
			if($selectedIdArr[0] == 's') {
				$subCategoryId = $selectedIdArr[1];
			} else {
				$ldbIds = $selectedIdArr[1];
				$ldbIdsArr = explode(' ', $ldbIds);
				if(count($ldbIdsArr) > 1) {
					//create open search url
					$requestData = array();
					$requestData['keyword'] = $keyword;
					$requestData['locations'] = $locations;
					$requestData['requestFrom'] = 'searchwidget';
					$requestData['customQerSpecFilters'] = $ldbIdsArr;
					$requestData['garbageWord'] = $garbageWord;
					if(isset($postData['userId']))
						$requestData['userId'] = $postData['userId'];
					if(isset($postData['tracking'])){
						$requestData['tracking'] = $postData['tracking'];
					}
					return $this->createOpenSearchUrlForMultipleSpecialization($requestData);
				} else {
					$specialization = $ldbIdsArr;
					//find subcat for this ldb id
					$ldbCourseObj = $this->LDBCourseRepository->find($ldbIdsArr[0]);
					$subCategoryId = $ldbCourseObj->getSubCategoryId();
					$data['selectedLdbId'] = $selectedId;
				}
			}
		}
		
		if($categoryId == 0 && !empty($subCategoryId)){
			//get Category Id 
			$this->CI->load->builder('CategoryBuilder','categoryList');
	        $categoryBuilder = new CategoryBuilder;
	        $categoryRepository = $categoryBuilder->getCategoryRepository();
	        $subCatObj = $categoryRepository->find($subCategoryId);
	        $categoryId = $subCatObj->getParentId();
		}

		$data['searchKeyword'] = $keyword;
		$data['categoryId']    = $categoryId;
		$data['subCategoryId'] = $subCategoryId;

		$this->_setLocationDataIntoDataset($data,$locations);
		
		$noLimitText = "No limit";

		if(is_array($specialization) && !empty($specialization)){
			foreach ($specialization as $key => $value) {
				if($value != $noLimitText) {
					$data['specialization'][] = $value;
				}
			}	
		}

		// fees
		if(!empty($fees)){
			$data['fees'] = $fees;
		}

		//exams
		//hard coding search suffix in case of search
		$suffix = "_|";
		if(is_array($exams) && !empty($exams)){
			foreach($exams as $examName){
				if($examName != $noLimitText) {
					$data['exams'][]=$examName.$suffix;
				}
			}
		}

		// courselevel
		if(is_array($courseLevel) && !empty($courseLevel)){
			foreach ($courseLevel as $key => $value) {
				if($value != $noLimitText) {
					$data['courseLevel'][] = ucwords($value);	
				}
			}
		}

		if(is_array($mode) && !empty($mode)){
			foreach ($mode as $key => $value) {
				if($value != $noLimitText) {
					$data['mode'][] = $value;	
				}
			}
		}

		$tracking = true;
		if(isset($postData['tracking'])){
			$tracking = $postData['tracking'];
		}

		$data['requestFrom'] = $postData['requestFrom'];
		//get and attach tracking id to url
		if(DO_SEARCHPAGE_TRACKING && $tracking){
			if(isset($postData['userId'])){
				$data['userId'] = $postData['userId'];
			}
			$data['trackingSearchId'] = $this->trackmodel->getTrackingIdForCourse($data);
		}

		if(!empty($garbageWord)){
			//hit qer
			$qerFilters = $solrSearcher->getQERFiltersForSearch($garbageWord);
			$this->prepareQerString($data,$qerFilters,'close');
		}

		// persist inital search
		$this->prepareSearchedAttributesString($data);

		$request->setData($data);
		$closeSearchUrl = $request->getUrl();

		if(empty($closeSearchUrl))
			$closeSearchUrl = 'No Url Found';

		return $closeSearchUrl;
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
	public function createOpenSearchUrl($postData) {
		$data         = array();
		
		$this->CI->load->library("search/SearchV2/searchPageRequest");
		if($postData['createNewUrl']){
			$request      = new SearchPageRequest(1);
		} else {
			$request      = new SearchPageRequest();
		}
		
		$this->CI->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;
		
		$keyword               	= $postData['keyword'];
		$locations             	= $postData['locations'];
		$requestComingFrom 		= $postData['requestComingFrom'];
		
		$data['searchKeyword'] = $keyword;
		if(isset($postData['userId'])){
			$data['userId'] = $postData['userId'];
		}

		if(!empty($postData['oldKeyword'])){
			$data['oldKeyword'] = $postData['oldKeyword'];
		}

		if(!empty($postData['relevantResults'])){
			$data['relevantResults'] = $postData['relevantResults'];
		}
		
		// convert location data into search page setData format
        $this->_setLocationDataIntoDataset($data,$locations);


        $filteredKeyword = $this->_isConvertibleToClosedSearch($data['searchKeyword']);
        if($filteredKeyword) {
        	$data['searchKeyword'] = $filteredKeyword;
        	if(isset($postData['tracking'])){
        		$data['tracking'] = $postData['tracking'];
        	}
        	$closeSearchUrl = $this->convertToCloseSearch($data, $request, $requestComingFrom, $locations);
        	return $closeSearchUrl;
        }

        //hit solr to search for synonym/acronym (QER alternative)
        $time_start = microtime_float(); $start_memory = memory_get_usage();
        $this->_init();
        $qerFilters = $this->autoSuggestorSolrClient->solrVersionQER($data);
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: Extra solr call | ".getLogTimeMemStr($time_start, $start_memory)."\n\n\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        
        $tracking = true;
        if(isset($postData['tracking'])){
        	$tracking = $postData['tracking'];
        }

        $data['requestFrom'] = $requestComingFrom;
        //get and attach tracking id to url
        // this tracking call should be here only. Don't move it
        if(DO_SEARCHPAGE_TRACKING && $tracking){
        	$data['currentpageurl'] = isset($postData['currentpageurl']) && !empty($postData['currentpageurl']) ? $postData['currentpageurl'] : '';
        	$data['trackingSearchId'] = $this->trackmodel->getTrackingIdForCourse($data);
        }
        
        if(empty($qerFilters)) { //hit qer
	        $qerFilters = $solrSearcher->getQERFiltersForSearch($data['searchKeyword']);
        } else {
        	$qerFilters['customQER'] = 1;
        }
        
        //function to prepare QerString 
        $this->prepareQerString($data,$qerFilters,'open');
		
		// persist inital search
		$this->prepareSearchedAttributesString($data);
		
		// set data in search page request 
		$request->setData($data);

		//get open search url from search page request
		$openSearchUrl = $request->getUrl();
		
		if(empty($openSearchUrl))
			$openSearchUrl = 'No Url Found';
		
		return 	$openSearchUrl;
		
	}


	private function _setLocationDataIntoDataset(& $data,$locations){
		$allIndia = 0;
		if(!empty($locations) && is_array($locations)){
			foreach ($locations as $key => $location) {
				$locationString = explode("_", $location);									
				if($locationString[0] == 'city'){
					if($locationString[1] == 1){
						$allIndia =1;
						break;
					}else{
						$citiesIds[]   = $locationString[1];
					}
				}elseif($locationString[0] == 'state'){
					$stateIds[]   = $locationString[1];
				}
			}
		}

		if($allIndia != 1){
			$data['city'] = $citiesIds;
			$data['state'] = $stateIds;
		}
		else {
			$data['city'] = array(1);
		}
	}

	private function _isConvertibleToClosedSearch($keyword) {
		$keyword = $this->sanitizeKeywordForCloseSearchConversion($keyword);
		$KEYWORD_TO_SUBCAT_MAPPING = $this->CI->config->item('KEYWORD_TO_SUBCAT_MAPPING');
		$storedKeywords = array_keys($KEYWORD_TO_SUBCAT_MAPPING);
		if(in_array($keyword, $storedKeywords)) {
			return $keyword;
		}
		return 0;
	}

	public function convertToCloseSearch($data, $request, $requestComingFrom, $locations) {
		$KEYWORD_TO_SUBCAT_MAPPING 	= $this->CI->config->item('KEYWORD_TO_SUBCAT_MAPPING');
		$data['categoryId']    		= $KEYWORD_TO_SUBCAT_MAPPING[$data['searchKeyword']]['categoryId'];
		$data['subCategoryId'] 		= $KEYWORD_TO_SUBCAT_MAPPING[$data['searchKeyword']]['subcategoryId'];
		$specializationId 			= $KEYWORD_TO_SUBCAT_MAPPING[$data['searchKeyword']]['specializationId'];

		$this->_setLocationDataIntoDataset($data,$locations);

		if(!empty($specializationId)){
			$data['specialization'][] = $specializationId;
		}

		//persist inital search
		$this->prepareSearchedAttributesString($data);
		$tracking = true;
		if(isset($data['tracking'])){
			$tracking = $data['tracking'];
		}

		$data['requestFrom'] = $requestComingFrom;
		//get and attach tracking id to url
		if(DO_SEARCHPAGE_TRACKING && $tracking){
			$data['currentpageurl'] = $_SERVER['HTTP_REFERER'];
			$data['trackingSearchId'] = $this->trackmodel->getTrackingIdForCourse($data);
		}
		
		$request->setData($data);
		$closeSearchUrl = $request->getUrl();

		if(empty($closeSearchUrl))
			$closeSearchUrl = 'No Url Found';

		return $closeSearchUrl;
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

            $FILTER_FACETS_ON_SUBCAT_SEARCH = $this->CI->config->item('FILTER_FACETS_ON_SUBCAT_SEARCH');

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
				$searchedAttributesParams[] = implode(',', $data['fees']);
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

	private function sanitizeKeywordForCloseSearchConversion($keyword){
		$stopWords = array('institutes', 'institute', 'colleges', 'college', 'university', 'universities', 'courses', 'course', 'top', 'best');
		
		$keyword = strtolower($keyword);
		$keyword = preg_replace("/-/", " ", $keyword);
		$keyword = preg_replace("/[^a-zA-Z\s]/", "", $keyword);
		$keyword = preg_replace("/\s+/", " ", $keyword);
		$keyword = trim($keyword);
		$keywordArr = explode(' ', $keyword);
		foreach ($keywordArr as $key => $word) {
			if(in_array($word, $stopWords)) {
				$keywordArr[$key] = '';
			}
		}
		$keyword = implode(' ', $keywordArr);
		$keyword = trim($keyword);
		return $keyword;
	}

	public function createOpenSearchUrlForMultipleSpecialization($requestData) {
		$data         = array();
		$this->CI->load->library("search/SearchV2/searchPageRequest");
		$request      = new SearchPageRequest();
		
		$this->CI->load->library('search/Searcher/SolrSearcher');
		$solrSearcher = new SolrSearcher;
		
		$data['searchKeyword'] 	= $requestData['keyword'];
		$locations             	= $requestData['locations'];
		$requestComingFrom 		= $requestData['requestFrom'];
		
		// convert location data into search page setData format
        $this->_setLocationDataIntoDataset($data,$locations);
        $tracking = true;
        if(isset($requestData['tracking'])){
        	$tracking = $requestData['tracking'];
        }

        $data['requestFrom'] = $requestComingFrom;
        //get and attach tracking id to url
        if(DO_SEARCHPAGE_TRACKING && $tracking){
        	$data['currentpageurl'] = isset($requestData['currentpageurl']) && !empty($requestData['currentpageurl']) ? $requestData['currentpageurl'] : '';
			if(isset($requestData['userId'])){
				$data['userId'] = $requestData['userId'];
			}
			$trackData = $data;
			$trackData['specialization'] = $requestData['customQerSpecFilters'];
        	$data['trackingSearchId'] = $this->trackmodel->getTrackingIdForCourse($trackData);
        }

        if(!empty($requestData['garbageWord'])){
        	//hit qer
        	$qerFilters = $solrSearcher->getQERFiltersForSearch($requestData['garbageWord']);
        }

		$qerFilters['course'] = $requestData['customQerSpecFilters'];
       	$qerFilters['customQER'] = 1;
        
        //function to prepare QerString 
        $this->prepareQerString($data,$qerFilters,'open');
		
        //prepareQERString doesnot set specialization in 'open' case because specialization filter is not shown in open search.
        // If it sets, they will be shown as sp[]= in the url which we do when only one specialization which gives only one subcat
		//So to insert specialization search as closed search in DB... setting specialization key 
		//to detect this as close search in track model
        // $trackData = $data;
        // $trackData['specialization'] = $qerFilters['course'];

		// persist inital search
		$this->prepareSearchedAttributesString($data);
		
		// set data in search page request 
		$request->setData($data);

		//get open search url from search page request
		$openSearchUrl = $request->getUrl();
		
		if(empty($openSearchUrl))
			$openSearchUrl = 'No Url Found';
		
		return 	$openSearchUrl;
		
	}




}