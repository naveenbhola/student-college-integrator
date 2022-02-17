<?php 

class NationalSearchPageUrlGenerator{

	private $CI;

	public function __construct()
	{
		$this->CI = & get_instance();
		// loading config file
    //	$this->CI->load->config("search/SearchPageConfig");
    	$this->CI->load->config("nationalCategoryList/nationalConfig");
    	if(DO_SEARCHPAGE_TRACKING){
    		$this->trackmodel = $this->CI->load->model('searchmatrix/searchmatrixmodel');//for search tracking
    	}
	}

	private function _init() {
		$this->CI->load->library('search/Searcher/SolrSearcher');
        $this->solrSearcher = new SolrSearcher;

        $this->CI->load->library('search/Solr/AutoSuggestorSolrClient');
        $this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;

        $this->CI->load->library("search/SearchV3/nationalSearchPageRequest");
        $this->request = new NationalSearchPageRequest(1);

        $this->CI->load->library('search/Solr/SolrClient');
        $this->solrClient = new SolrClient;
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
	 * @author Ankit Bansal
	 * @return Open Search Url
	 */
	public function createOpenSearchUrl($postData) {
		$this->_init();
		
		switch ($postData['searchPage']) {
			case 'question':
				$openSearchUrl = $this->createQuestionOpenSearchUrl($postData);
				break;
			
			default:
				$openSearchUrl = $this->createCourseOpenSearchUrl($postData);
				break;
		}
		
		if(empty($openSearchUrl))
			$openSearchUrl = 'No Url Found';

		return $openSearchUrl;
	}

	private function createQuestionOpenSearchUrl($postData) {
		$fieldAlias = $this->CI->config->item('FIELD_ALIAS');

		$queryParams = array();
		$queryParams[] = $fieldAlias['keyword']."=".urlencode($postData['keyword']);
		if($postData['pageNumber'] > 1) {
			$queryParams[] = $fieldAlias['pageNumber']."=".urlencode($postData['pageNumber']);
		}
		if(!empty($postData['filterBy'])) {
			$queryParams[] = $fieldAlias['filterBy']."=".urlencode($postData['filterBy']);
		}

		$queryParamsString = implode("&",$queryParams);
		if($queryParamsString != '') {
            $suffix = '?'.$queryParamsString;
		}

		//get data from solr to get first non empty tab
		if(empty($postData['searchPageTab'])) {
			$solrRequestData = array();
	        $solrRequestData['keyword'] = $postData['keyword'];

	        //get page details
	        $solrRequestData['pageLimit'] = 0;
	        
	        $solrResults = $this->solrClient->getQuestionsAndTags($solrRequestData);
	        
	        if(!empty($solrResults['data']['questions_answered'])) {
                $postData['searchPageTab'] = 'answered';
            } else if (!empty($solrResults['data']['questions_topics'])) {
                $postData['searchPageTab'] = 'topics';
            } else if (!empty($solrResults['data']['questions_unanswered'])) {
                $postData['searchPageTab'] = 'unanswered';
            }
		}

		$domainPrefix = SHIKSHA_HOME.SEARCH_PAGE_QUESTION_URL_PREFIX.'/'.$postData['searchPageTab'];

		return $domainPrefix.$suffix;
	}

	private function createCourseOpenSearchUrl($postData) {
		$data = array();
		
		$data['searchKeyword'] = $postData['keyword'];
		$data['typedKeyword'] = $postData['typedKeyword'];
		
		$data['isTrending'] = $postData['isTrending'];
		$data['isInterim'] = $postData['isInterim'];
		$data['isInstituteMultiple'] = $postData['isInstituteMultiple'];
		$locations             	= $postData['locations'];

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
        $this->_setLocationDataIntoDataset($data, $locations);

        $data['requestFrom'] = $postData['requestComingFrom'];
	        
        if(empty($qerFilters)) { //hit qer
	        $qerFilters = $this->solrSearcher->getQERFiltersForSearch($data['searchKeyword']);
        }

 		$userSelectedData = $data;
        // Prepare QER String & Populate $data with qer Params
        $this->prepareQerString($data,$qerFilters,'open');
        
		// persist inital search
		$this->prepareSearchedAttributesString($data);
		
		// Check if the request is eligible for Closed Search
        $closedSearchParams = $this->_isConvertibleToClosedSearch($qerFilters);

        if(DO_SEARCHPAGE_TRACKING){
        	if(!empty($postData['trackingSearchId'])){
        		$data['trackingSearchId'] = $postData['trackingSearchId'];
        	}
        	if(!empty($postData['trackingFilterId'])){
        		$data['trackingFilterId'] = $postData['trackingFilterId'];
        	}
        }
		
        if(!empty($closedSearchParams)){
        	if(empty($locations)){
        		$locations = array();
        		if(!empty($qerFilters['city'])){
        			foreach ($qerFilters['city'] as $key => $value) {
        				$locations[] = "city_".$value;
        			}
        		}
        		if(!empty($qerFilters['state'])){
        			foreach ($qerFilters['state'] as $key => $value) {
        				$locations[] = "state_".$value;
        			}
        		}
        	}
        	$data['userSelectedDataForTracking'] = $userSelectedData;
        	$closeSearchUrl = $this->convertToCloseSearch($data, $locations, $closedSearchParams);
        	return $closeSearchUrl;
        }

        // set data in search page request 
        $this->request->setData($data);
        
        //get open search url from search page request
		$openSearchUrl = $this->request->getUrl();

		return $openSearchUrl;
	}

	public function createClosedSearchUrl($postData){
		$this->_init();

		$fieldAlias = $this->CI->config->item('FIELD_ALIAS');
		$fieldAliasReverse = array_flip($fieldAlias);
		
		$keyword        = $postData['keyword'];
		$entityId     	= $postData['entityId'];
		$entityType    	= $postData['entityType'];
		$locations      = $postData['locations'];
		$specialization = $postData['specialization'];
		$fees           = $postData['fees'];
		$exams          = $postData['exams'];
		$courseLevel    = $postData['courseLevel'];
		$baseCourse    = $postData['course'];
		$mode           = $postData['mode'];
		$garbageWord    = $postData['garbageWord'];
		$credential    = $postData['credential'];
		$isTrending    = $postData['isTrending'];
		$typedKeyword    = $postData['typedKeyword'];

		// Added for SA
		$stream    = $postData['stream'];
		$substream    = $postData['substream'];

		$entityType = $fieldAliasReverse[$entityType];
		$this->_setLocationDataIntoDataset($data,$locations);
		$noLimitText = "No limit";
		$data['autoSuggestorEntityId'] = $entityId;
		$data['autoSuggestorEntityType'] = $entityType;
		$data['typedKeyword'] = $typedKeyword;
		$data['isTrending'] = $isTrending;
		$data['isInstituteMultiple'] = $isInstituteMultiple;
		$data['isInterim']		= $postData['isInterim'];

		if($entityType == "stream"){
			$data['stream'][]=$entityId;
		}
		else if($entityType == "substream"){
			$data['substream'][]=$entityId;	
		} 
		else if($entityType == "specialization"){
			$data['specialization'][]=$entityId;	
		} 
		else if($entityType == "base_course"){
			$data['baseCourse'][]=$entityId;
		} 
		else if($entityType == "popular_group"){
			$data['popularGroup'][]=$entityId;	
		} 
		else if($entityType == "certificate_provider"){
			$data['certificateProvider'][]=$entityId;		
		} 

		$data['searchKeyword'] = $keyword;


		if(is_array($stream) && !empty($stream)){
			foreach ($stream as $key => $value) {
				if($value != $noLimitText) {
					$data['stream'][] = $value;
				}
			}	
		}

		if(is_array($substream) && !empty($substream)){
			foreach ($substream as $key => $value) {
				if($value != $noLimitText) {
					$data['substream'][] = $value;
				}
			}	
		}

		if(is_array($specialization) && !empty($specialization)){
			foreach ($specialization as $key => $value) {
				if($value != $noLimitText) {
					if(strpos($value, "::") !== false){
						$tempData = explode("::", $value);
						$data[$fieldAliasReverse[$tempData[0]]][] = $tempData[1];
					}else{
						$data['specialization'][] = $value;	
					}
				}
			}	
		}

		// fees
		if(!empty($fees)){
			foreach($fees as $value){
				$data['fees'][] = $value;
			}			
		}

		// exams
		if(is_array($exams) && !empty($exams)){
			foreach($exams as $examId){
				if($examId != $noLimitText) {
					$data['exams'][]=$examId.$suffix;
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

		// baseCourse
		if(is_array($baseCourse) && !empty($baseCourse)){
			foreach ($baseCourse as $key => $value) {
				if($value != $noLimitText) {
					$data['baseCourse'][] = ucwords($value);	
				}
			}
		}

		// credential
		if(is_array($credential) && !empty($credential)){
			foreach ($credential as $key => $value) {
				if($value != $noLimitText) {
					$data['credential'][] = ucwords($value);	
				}
			}
		}

		if(is_array($mode) && !empty($mode)){
			foreach ($mode as $key => $value) {
				if($value != $noLimitText) {
					if(strpos($value, "::") !== false){
						$tempData = explode("::", $value);
						$keyName = $fieldAliasReverse[$tempData[0]];
						if($keyName == "education_type"){
							$data['educationType'][] = $tempData[1];
						}else if($keyName == "delivery_method"){
							$data['deliveryMethod'][] = $tempData[1];
						}
					}
				}
			}
		}
		$data['requestFrom'] = $postData['requestFrom'];

		if(!empty($garbageWord)){
			//hit qer
			$qerFilters = $this->solrSearcher->getQERFiltersForSearch($garbageWord);
			$this->prepareQerString($data,$qerFilters,'close');
		}

		// persist inital search
		$this->prepareSearchedAttributesString($data);

		$this->request->setData($data);
		
		$closeSearchUrl = $this->request->getUrl();

		if(empty($closeSearchUrl))
			$closeSearchUrl = 'No Url Found';

		return $closeSearchUrl;
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

	/**
	* Check if the Open Search Request is Valid for Closed Search
	*
	*/
	private function _isConvertibleToClosedSearch($qerResults) {
		
		if(!empty($qerResults['stream']) && count($qerResults['stream']) == 1){
			return array('stream' => reset($qerResults['stream']));
		} 
		else if(!empty($qerResults['substream']) && count($qerResults['substream']) == 1){
			return array('substream' => reset($qerResults['substream']));
		} 
		else if(!empty($qerResults['base_course']) && count($qerResults['base_course']) == 1){
			return array('base_course' => reset($qerResults['base_course']));
		}

		return false;
	}

	private function convertToCloseSearch($data, $locations,$closedSearchParams) {
		// Remove QER Results String from the Data		
		$this->_setLocationDataIntoDataset($data,$locations);

		//persist inital search
		$this->prepareSearchedAttributesString($data);
		
		$this->request->setData($data);

/*		if(array_key_exists("stream", $closedSearchParams)){
    		$request->setIsStreamClosedSearch(true);
    	} 
    	else if(array_key_exists("substream", $closedSearchParams)){
    		$request->setIsSubStreamClosedSearch(true);
    	}
    	else if(array_key_exists("base_course", $closedSearchParams)){
    		$request->setIsBaseCourseClosedSearch(true);
    	}*/

		$closeSearchUrl = $this->request->getUrl();

		if(empty($closeSearchUrl))
			$closeSearchUrl = 'No Url Found';

		return $closeSearchUrl;
	}

	/**
	 * Purpose: to append a qer results in url
	 * Url Qer String Order : q | city | state | locality | institute | base_course | specialization | substream | stream | popular_group | certificate_provider | education_type | delivery_method | course_type | credential
	 * @author Ankit Bansal
	 * @param  Array     &$data      
	 * @param  Array     $qerFilters result set from qer
	 * @return url qer String
	 */
	private function prepareQerString(& $data , $qerFilters,$type){
		$qerParams = array();

		if(array_key_exists('q', $qerFilters)){
			$qerParams[]  = $qerFilters['q'];
		}else{
			$qerParams[]  = 0;
		}
		if(array_key_exists('city', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['city']);
			foreach ($qerFilters['city'] as $key => $value) {
				if(!in_array($value, $data['city'])) {
					$data['city'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('state', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['state']);
			foreach ($qerFilters['state'] as $key => $value) {
				if(!in_array($value, $data['state'])) {
					$data['state'][]=$value;
				}
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

		if(array_key_exists('base_course', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['base_course']);
			foreach ($qerFilters['base_course'] as $key => $value) {
				if(!in_array($value, $data['base_course'])) {
					$data['baseCourse'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('specialization', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['specialization']);	
			foreach ($qerFilters['specialization'] as $key => $value) {
				if(!in_array($value, $data['specialization'])) {
					$data['specialization'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('substream', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['substream']);	
			foreach ($qerFilters['substream'] as $key => $value) {
				if(!in_array($value, $data['substream'])) {
					$data['substream'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('stream', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['stream']);	
			foreach ($qerFilters['stream'] as $key => $value) {
				if(!in_array($value, $data['stream'])) {
					$data['stream'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('popular_group', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['popular_group']);	
			foreach ($qerFilters['popular_group'] as $key => $value) {
				if(!in_array($value, $data['popular_group'])) {
					$data['popularGroup'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('certificate_provider', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['certificate_provider']);	
			foreach ($qerFilters['certificate_provider'] as $key => $value) {
				if(!in_array($value, $data['certificate_provider'])) {
					$data['certificateProvider'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('education_type', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['education_type']);	
			foreach ($qerFilters['education_type'] as $key => $value) {
				if(!in_array($value, $data['education_type'])) {
					$data['educationType'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('delivery_method', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['delivery_method']);	
			foreach ($qerFilters['delivery_method'] as $key => $value) {
				if(!in_array($value, $data['delivery_method'])) {
					$data['deliveryMethod'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('course_type', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['course_type']);
			foreach ($qerFilters['course_type'] as $key => $value) {
				if(!in_array($value, $data['course_type'])) {
					$data['courseType'][]=$value;
				}
			}
		}else{
			$qerParams[]  = 0;	
		}

		if(array_key_exists('credential', $qerFilters)){
			$qerParams[] = implode(',', $qerFilters['credential']);	
			foreach ($qerFilters['credential'] as $key => $value) {
				if(!in_array($value, $data['credential'])) {
					$data['credential'][]=$value;
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
	 * Searched Attribute Order : city | state | exam |  base_course | specialization | substream | stream | popular_group | certificate_provider | education_type | delivery_method | course_type | credential | fees
	 * @author Ankit Bansal
	 * @param  [type]     $data [description]
	 * @return [type]           [description]
	 */
	private function prepareSearchedAttributesString(& $data){
			$fieldAlias = $this->CI->config->item('FIELD_ALIAS');
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

			if(array_key_exists('baseCourse', $data)){
				$searchedAttributesParams[] = implode(',', $data['baseCourse']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('specialization', $data)){
				$searchedAttributesParams[] = implode(',', $data['specialization']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('substream', $data)){
				$searchedAttributesParams[] = implode(',', $data['substream']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('stream', $data)){
				$searchedAttributesParams[] = implode(',', $data['stream']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('popularGroup', $data)){
				$searchedAttributesParams[] = implode(',', $data['popularGroup']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('certificateProvider', $data)){
				$searchedAttributesParams[] = implode(',', $data['certificateProvider']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('educationType', $data)){
				$searchedAttributesParams[] = implode(',', $data['educationType']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('deliveryMethod', $data)){
				$searchedAttributesParams[] = implode(',', $data['deliveryMethod']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('courseType', $data)){
				$searchedAttributesParams[] = implode(',', $data['courseType']);
			}else{
				$searchedAttributesParams[] = 0;
			}

			if(array_key_exists('credential', $data)){
				$searchedAttributesParams[] = implode(',', $data['credential']);
			}else{
				$searchedAttributesParams[] = 0;
			}
			if(array_key_exists('fees', $data)){
				$searchedAttributesParams[] = implode(',', $data['fees']);
			}else{
				$searchedAttributesParams[] = 0;
			}
			if(array_key_exists('autoSuggestorEntityId', $data)){
				$searchedAttributesParams[] = $fieldAlias[$data['autoSuggestorEntityType']].":".$data['autoSuggestorEntityId'];
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


}