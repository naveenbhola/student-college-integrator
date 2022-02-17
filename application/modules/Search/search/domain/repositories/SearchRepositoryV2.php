<?php

class SearchRepositoryV2 extends EntityRepository{
	private $instituteCount;
	private $courseCount;
	private $instituteData;
	private $filters;


	public function __construct($request, $solrSearcher, $autoSuggestorSolrClient, $categoryRepository){
		$this->request                 = $request;
		$this->solrSearcher            = $solrSearcher;
		$this->autoSuggestorSolrClient = $autoSuggestorSolrClient;
		$this->categoryRepository      = $categoryRepository;
	}


	public function getRawSearchData(){
		//get search keyword
		$keyword = $this->request->getSearchKeyword();
		if(empty($keyword)) {
			return false;
		}
		$solrRequestData['keyword'] = $keyword;
		$solrRequestData['oldKeyword'] = $this->request->getOldKeyword();

		//get filters from QER
		$solrRequestData['qerFilters'] = $this->request->getQERFilters();
		
		//get user applied filters
		$solrRequestData['userAppliedFilters'] = $this->request->getAppliedFilters();
		if(!empty($solrRequestData['userAppliedFilters']['catId'])) {
			$catObj = $this->categoryRepository->find($solrRequestData['userAppliedFilters']['catId']);
			$solrRequestData['userAppliedFilters']['catName'] = $catObj->getShortName();
		}

		//get page details
		$solrRequestData['pageLimit'] = SEARCH_PAGE_LIMIT;
		if(isMobileRequest()){
			$solrRequestData['pageLimit'] = SEARCH_PAGE_LIMIT_MOBILE;
		}
		$solrRequestData['pageNum'] = $this->request->getCurrentPageNum();
		$solrRequestData['requestFrom'] = $this->request->getRequestFrom();
		
		if($solrRequestData['qerFilters']['customQer'] == 1) {
			foreach ($solrRequestData['qerFilters']['course'] as $ldbCourse) {
				$solrRequestData['subCatIdsFromLdb'][] = $this->categoryRepository->getCategoryByLDBCourse($ldbCourse)->getId();
			}
		}

		$solrRequestData['getInstitutesAndCourses'] = $this->request->getInstitutesFlag();
		$solrRequestData['getFilters'] = $this->request->getFiltersFlag();
		$solrRequestData['getParentFilters'] = $this->request->getParentFiltersFlag();

		if(DO_SEARCHPAGE_TRACKING){
			$trackingSearchId = $this->request->getTrackingSearchId();
			$trackingFilterId = $this->request->getTrackingFilterId();
			if(!empty($trackingSearchId)){
				$solrRequestData['trackingSearchId'] = $trackingSearchId;
			}
			if(!empty($trackingFilterId)){
				$solrRequestData['trackingFilterId'] = $trackingFilterId;
			}
		}
		
		//get search data from solr
		$solrResult = $this->autoSuggestorSolrClient->getFiltersAndInstitutes($solrRequestData);
		
		//get parent filters from solr
		//$solrOriginalRequestData = $this->request->getOriginalSetOfFilters();
		//$solrParentFilterResult = $this->autoSuggestorSolrClient->getParentFilters($solrOriginalRequestData);
		// _p(count($solrResult['filters']['location']));
		// _p(count($solrParentFilterResult['filters']['location']));
		//die;

		//_p(array_keys($solrResult));exit;
		$this->setSearchData($solrResult);
	}

	private function setSearchData($solrResult) {
		$this->setNumberOfInstitutes($solrResult['numOfInstitutes']);
		$this->setNumberOfCourses($solrResult['numOfCourses']);
		$this->setInstitutes($solrResult['instituteIdCourseIdMap']);
		$this->setFilters($solrResult['filters']); //set using request
		$this->setDebugSortInfo($solrResult['courseDebugSortParam'], $solrResult['instituteDebugSortParam']);
		if(isset($solrResult['relevantResults'])){
			$this->setRelevantResultsFlag($solrResult['relevantResults']);
		}
		if(isset($solrResult['oldKeyword'])){
			$this->setOldKeyword($solrResult['oldKeyword']);
		}
	}

	private function setDebugSortInfo($courseDebugSortParam, $instituteDebugSortParam) {
		$this->debugInfo = array('instituteDebugSortParam'=>$instituteDebugSortParam, 'courseDebugSortParam'=>$courseDebugSortParam);
	}

	private function setRelevantResultsFlag($flag){
		$this->areRelevantResults = $flag;
	}

	public function getRelevantResultsFlag(){
		if(isset($this->areRelevantResults)){
			return $this->areRelevantResults;
		}
		return false;
	}

	private function setOldKeyword($keyword){
		$this->oldKeyword = $keyword;
	}

	public function getOldKeyword(){
		if(isset($this->oldKeyword)){
			return $this->oldKeyword;
		}
		return false;
	}

	public function getDebugSortInfo() {
		return $this->debugInfo;
	}

	private function setNumberOfInstitutes($instituteCount) {
		$this->instituteCount = $instituteCount;
	}

	public function getInstituteCount() {
		return $this->instituteCount;
	}

	private function setNumberOfCourses($courseCount){
		$this->courseCount = $courseCount;
	}

	public function getCourseCount(){
		return $this->courseCount;
	}

	private function setInstitutes($instituteData){
            //		$instituteIdsWithCourseIds = array_map(function ($ar) {return array(key($ar));}, $instituteIds); //array_map('array_keys',$instituteIds);
		 $this->instituteData = $instituteData;
	}

	public function getInstitutes(){
		
		return $this->instituteData;
	}

	private function setFilters($filtersData){
		$this->filters = $filtersData;
	}

	public function getFilters(){
		return $this->filters;
	}
}