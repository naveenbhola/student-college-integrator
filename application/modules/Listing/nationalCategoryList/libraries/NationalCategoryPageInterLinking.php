<?php
define("ENT_MAX_LINKS_QUERY_LIMIT"	  , 16);
if(isMobileRequest()){
	define("ENT_MAX_LINKS_LIMIT"	  , 12);
	define("ENT_MAX_LINKS_PER_LIMIT"  , 6);
}else{	
	define("ENT_MAX_LINKS_LIMIT"	  , 16);
	define("ENT_MAX_LINKS_PER_LIMIT"  , 8);
}

/*Logic details present in LF_6412 and LF-6416*/
class NationalCategoryPageInterLinking{
	private $categoryPageRequest;
	private $finalData = array();
	public function __construct($request){
		$this->CI 			= & get_instance();

		$this->CI->load->library("nationalCategoryList/NationalCategoryPageLib");
		$this->nationalCategoryPageLib = new NationalCategoryPageLib();

		$this->categoryPageModel = 	$this->CI->load->model('nationalCategoryList/categorypagemodel');

		$this->categoryPageRequest = $request;
		$this->useCache = true;
		$this->buildCategoryPageInterLinkingUrl();
	}

	public function disableCaching(){
		$this->useCache = false;
	}

	/**
	 * Method to get related category url for given category page request
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2017-03-29
	 * @return
	 */
	function buildCategoryPageInterLinkingUrl() {	
		if(empty($this->categoryPageRequest)){
			return;
		}
		
		$dataByCurrentCatRequest       = array();		
		$dataByLocation                = array();		
		
		$currentPageUniqueId           = $this->categoryPageRequest->getCategoryPageKey();
		
		$nationalCategoryListCache     = $this->CI->load->library('nationalCategoryList/cache/NationalCategoryListCache');

		if($this->useCache) {
			$categoryPageRelatedLinksCache = $nationalCategoryListCache->getCategoryPageRelatedLinks($currentPageUniqueId);

			if(!empty($categoryPageRelatedLinksCache)) {
				error_log("===========RENDER FROM CACHE =========");
				$categoryPageLinksCache                 = json_decode($categoryPageRelatedLinksCache,true);
				$dataByCurrentCatRequest                = $categoryPageLinksCache['dataByCurrentCatRequest'];
				$dataByLocation                         = $categoryPageLinksCache['dataByLocation'];

				$this->finalData = $this->_applyBucketing($dataByCurrentCatRequest,$dataByLocation);
			}
		}
		else {
			error_log("===========RENDER FROM DB ========= $currentPageUniqueId");
			$dataByCurrentCatRequest                = $this->_getCategoryPageByCurrentCatRequest();

			foreach ($dataByCurrentCatRequest as $key => $row) {
				$dataByCurrentCatRequest[$key]['url'] = '/'.$dataByCurrentCatRequest[$key]['url'];
			}
			$dataByLocation                         = $this->_getCategoryPageUrlByLocation();
			foreach ($dataByLocation as $key => $row) {
				$dataByLocation[$key]['url'] = '/'.$dataByLocation[$key]['url'];
			}
			
			$dataToCache['dataByCurrentCatRequest'] = $dataByCurrentCatRequest;
			$dataToCache['dataByLocation']          = $dataByLocation;

			//_p($currentPageUniqueId);
			$nationalCategoryListCache->storeCategoryPageRelatedLinks($currentPageUniqueId,json_encode($dataToCache));
		}
		
		return $finalData;
	}

	function getCategoryPageInterLinkingUrl(){
		return $this->finalData;
	}

	/**
	 * RULE 1
	 * 1.Stream level page (for location) - if stream present
	 * 2 Sub-stream level page (for location) - if sub-stream present
     * 3 Current category page x child nodes (for location) - if pages available. * If pages are not available, then show all the child nodes of Popular 
     * course *(if available) else stream + sub-stream + base course
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2017-03-30
	 * @return data by Current Category Page request
	 */
	private function _getCategoryPageByCurrentCatRequest(){
		$catPageData = $this->categoryPageRequest->getData();
		//error_log(" CAT InterLinking RULE 1 : Stage 1 \n");
		$data = $this->_getData(ENT_MAX_LINKS_QUERY_LIMIT,true);			
		
		if(empty($data)  && ($catPageData['specialization'] || $catPageData['credential'] || $catPageData['exam']) ){
			//relaxing the condition
			//error_log(" CAT InterLinking RULE 1 : Stage 2 \n");
			$data = $this->_getData(ENT_MAX_LINKS_QUERY_LIMIT,true,array('specialization_id','credential','exam_id'));
		}
		return $data;		
	}

	/**
	 * RULE 2
	 * For all-India page, top cities 
	 * For state page, show all-India page followed by top cities of the state
	 * For city page, show all-India page, state page and top cities of India
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2017-03-30
	 * @param  integer     $limit Number of results to fetch
	 * @return data by category page location
	 */
	private function _getCategoryPageUrlByLocation(){
		$finalData = array();

		if((!$this->categoryPageRequest->isStatePage()) && (!$this->categoryPageRequest->isCityPage())){
			//error_log(" CAT InterLinking RULE 2 : India Page \n");
			/*	All india category page	 */						
			$finalData = $this->_getData(ENT_MAX_LINKS_QUERY_LIMIT,false,array('city_id','state_id'),array('topCities'=>1),true);
		}else if($this->categoryPageRequest->isCityPage()){			
			$limit = ENT_MAX_LINKS_QUERY_LIMIT;
			//error_log(" CAT InterLinking RULE 2 : City Page Stage 1 \n");
			/*	City level category page */
			//india link
			$data = $this->_getData(1,false,array(),array('city_id'=>1,'state_id'=>1),true);
			if(!empty($data)){
				$limit = $limit - 1;
				$finalData 		= array_merge($finalData, $data);
			}			
			
			//error_log(" CAT InterLinking RULE 2 : City Page Stage 2 \n");
			//state page
			//incase of virtual city, we need to fetch state attached to virtual city and get single max result count on any of these states.   		
			if($this->categoryPageRequest->isVirtualCityPage()){
				$this->CI->load->builder('LocationBuilder','location');
				$locationBuilder = new LocationBuilder();
				$this->locationRepository = $locationBuilder->getLocationRepository();
				$mainCityFromVirtualCity = $this->locationRepository->getCitiesByVirtualCity($this->categoryPageRequest->getCity());				
				$stateId = array();
				foreach ($mainCityFromVirtualCity as $cityObject) {					
					$stateAttachedToVirtaulCity[] = $cityObject->getStateId();
				}
				$stateId = array_unique($stateAttachedToVirtaulCity);				
			}else{
				if($this->categoryPageRequest->getState()){
					$stateId = $this->categoryPageRequest->getState();
				}				
			}

			$data = $this->_getData(1,false,array(),array('city_id'=>1,'state_id'=>$stateId),true);
			if(!empty($data)){
				$limit = $limit - 1;
				$finalData 		= array_merge($finalData, $data);
			}

			//error_log(" CAT InterLinking RULE 2 : City Page Stage 3 \n");

			// top cities of the india
			$data = $this->_getData($limit,false,array('city_id','state_id'),array('topCities'=>1),true);			
			if(!empty($data)){			
				$finalData 		= array_merge($finalData, $data);
			}
		}else if($this->categoryPageRequest->isStatePage()){			
			$limit = ENT_MAX_LINKS_QUERY_LIMIT;
			//error_log(" CAT InterLinking RULE 2 : State Page Stage 1 \n");
			/*	State level category page */
			//india link
			$data = $this->_getData(1,false,array(),array('city_id'=>1,'state_id'=>1),true);			
			if(!empty($data)){
				$limit = $limit - 1;
				$finalData 		= array_merge($finalData, $data);
			}
			
			//error_log(" CAT InterLinking RULE 2 : State Page Stage 2 \n");
			// top cities of the state
			$data = $this->_getData($limit,false,array('city_id'),array('topCities'=>1),true);			
			if(!empty($data)){
				$finalData 		= array_merge($finalData, $data);
			}
		}		

		return $finalData;
	}

	private function _getColumnNameByPageDataKey($key){
		switch ($key) {
			case 'stream':
				$filterKey = 'stream_id';
				break;
			case 'substream':
				$filterKey = 'substream_id';
				break;
			case 'specialization':
				$filterKey = 'specialization_id';
				break;
			case 'base_course':
				$filterKey = 'base_course_id';
				break;
			case 'state':
				$filterKey = 'state_id';
				break;
			case 'city':
				$filterKey = 'city_id';
				break;
			case 'exam':
				$filterKey = 'exam_id';
				break;
			default:
				$filterKey = $key;
				break;
		}
		
		return $filterKey;
	}

	
	/**
	 * 
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2017-03-30
	 * @param  Integer    $noOfResultToFetch Number of results to fetch
	 * @param  boolean    $isDrillDown       Flag to fetch child nodes.Default value is false.
	 * @param  array      $excludeKeys       To remove given keys in filter criteria
	 * @param  array      $extraFilters      To add keys in filter criteria
	 * @return data according to the filter criteria 
	 */
	private function _getData($noOfResultToFetch,$isDrillDown = false,$excludeKeys = array(),$extraFilters = array(),$maintainCriteria = false){
		$filter              = array();
		$categoryPageData    = $this->categoryPageRequest->getData();
		$currentPageUniqueId = $this->categoryPageRequest->getCategoryPageKey();
		foreach ($categoryPageData as $key => $value) {
			$filterKey = $this->_getColumnNameByPageDataKey($key);	
			if($this->categoryPageRequest->isVirtualCityPage() && $filterKey == 'state_id'){
				$value = -1;
			}
			if($maintainCriteria){
				if(!in_array($filterKey, $excludeKeys)){
					$filter['AND'][$filterKey] = $value;
				}
			}else{
				if(!empty($value) && !in_array($filterKey, $excludeKeys)){
					$filter['AND'][$filterKey] = $value;
				}else{
					if($isDrillDown)
						$filter['OR'][$filterKey]  = $value;
				}				
			}
		}
		foreach ($extraFilters as $key => $value) {
			$filter['AND'][$key] = $value;
		}

		$filter['limit']         = $noOfResultToFetch;
		$filter['excludePageId'] = $currentPageUniqueId;
		$data = $this->categoryPageModel->getCategoryPageRelatedLinks($filter);

		return $data;
	}

	/**
	 * Bucketing Logic
	 * If both the bucket count is less than max no. of urls to show, then we will show all the links of both the buckets
	 * If one bucket count is greater than assigned bucket limit and another bucket count is less than assigned bucket limit then merge accordingly.
	 * If both the bucket count is greater than assigned bucket limit, then merge accordingly.
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2017-04-03
	 * @param  array     $bucket1 
	 * @param  array     $bucket2
	 * @return
	 */
	private function _applyBucketing($bucket1,$bucket2){
		$bucket1Count     = count($bucket1);
		$bucket2Count     = count($bucket2);
		$totalBucketCount = $bucket1Count + $bucket2Count;
		// _p($bucket1);
		// _p($bucket2);die;
		if($totalBucketCount <= ENT_MAX_LINKS_LIMIT){
			return $this->_mergeBuckets($bucket1,$bucket2,$bucket1Count,$bucket2Count);
		}else if($bucket1Count > ENT_MAX_LINKS_PER_LIMIT && $bucket2Count <= ENT_MAX_LINKS_PER_LIMIT){
			$resultFetchFromBucket1 = ENT_MAX_LINKS_LIMIT - $bucket2Count;
			return $this->_mergeBuckets($bucket1,$bucket2,$resultFetchFromBucket1,ENT_MAX_LINKS_PER_LIMIT);
		}else if($bucket1Count <= ENT_MAX_LINKS_PER_LIMIT && $bucket2Count > ENT_MAX_LINKS_PER_LIMIT){
			$resultFetchFromBucket2 = ENT_MAX_LINKS_LIMIT - $bucket1Count;
			return $this->_mergeBuckets($bucket1,$bucket2,ENT_MAX_LINKS_PER_LIMIT,$resultFetchFromBucket2);
		}else if($bucket1Count > ENT_MAX_LINKS_PER_LIMIT && $bucket2Count > ENT_MAX_LINKS_PER_LIMIT){
			return $this->_mergeBuckets($bucket1,$bucket2,ENT_MAX_LINKS_PER_LIMIT,ENT_MAX_LINKS_PER_LIMIT);
		}
	}

	private function _mergeBuckets($bucket1,$bucket2,$pickFromBucket1,$pickFromBucket2){
		$finalBucket    = array();		
		//filling bucket from set 1
		$this->_fillBucket($bucket1,$pickFromBucket1,$finalBucket);

		//filling bucket from set 2
		$this->_fillBucket($bucket2,$pickFromBucket2,$finalBucket);
		
		return $finalBucket;
	}

	private function _fillBucket($bucket,$pickFromBucket,&$finalBucket){
		$currentCounter = 0;
		foreach ($bucket as $key => $value) {
			if($currentCounter >= $pickFromBucket){
				continue;
			}
			if(!isset($finalBucket[$value['id']])){					
				$finalBucket[$value['id']]['url'] = SHIKSHA_HOME.'/'.$value['url'];
				if(isMobileRequest()) {
					$finalBucket[$value['id']]['heading'] = $value['heading_mobile'];
				}
				else {
					$finalBucket[$value['id']]['heading'] = $value['heading_desktop'];
				}
			}
			$currentCounter++;
		}
	}

	public function generateInterlinkingCacheForCTPGs(){
		$this->disableCaching();
		$this->categorypageseomodel = $this->CI->load->model('nationalCategoryList/categorypageseomodel');
		$this->CI->load->library('nationalCategoryList/NationalCategoryPageRequest',true);
		$nationalCategoryListCache = $this->CI->load->library('nationalCategoryList/cache/NationalCategoryListCache');
		$batchSize = 5000;
		$count = 1;
		$start = 0;
		do {
			error_log(" ********** Getting category Page Data for batch $count ********** ");
			$categoryPageData = $this->categorypageseomodel->findCategoryPageNonZeroData($start,$batchSize);
			//_p($categoryPageData); die;
			$idsToBeDeleted = array();
			if(!empty($categoryPageData)){
				foreach ($categoryPageData as $key=>$row) {
					if($row['result_count'] > 0){
						$this->categoryPageRequest = new NationalCategoryPageRequest($row);
						$this->buildCategoryPageInterLinkingUrl();
					}
					else{
						$idsToBeDeleted[] = $row['id'];
					}
				}
			}
			if(!empty($idsToBeDeleted)){
				$nationalCategoryListCache->removeCategoryPageRelatedLinksCache($idsToBeDeleted);
			}
			$count++;
			$start += $batchSize;
		} while(!empty($categoryPageData));
		error_log(" ********** Done **********");
	}
}