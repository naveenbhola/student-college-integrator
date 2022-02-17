<?php
class AbroadCategoryPageSolr {
	private $request;
	private $categoryPageRepository;
	private $cache;
	private $numInstitutes;
	private $numUniversities;
	private $institutes;
	private $universities;
	private $filters;
	private $caching = TRUE;
	private $banner;
	private $numTuples;
	function __construct(AbroadCategoryPageRequest $request, AbroadCategoryPageRepository $categoryPageRepository,$cache,RotationService $rotationService) {
		$this->request = $request;
		$this->categoryPageRepository = $categoryPageRepository;
		$this->cache = $cache;
		$this->rotationService = $rotationService;
	}
	public function getCompleteUniversityList()
	{
		return $this->universities;
	}
	public function getUniversities()
	{
		$this->CI = & get_instance();
		// load library to call a solr query
		$builder = new ListingBuilder;
	
		$this->CI->benchmark->mark("IRU2START");
		$resultData = $this->categoryPageRepository->getUniversities($this->rotationService);
		$this->CI->benchmark->mark("IRU2END");
        error_log( "getUniversities called in categorypagesolrrepo time taken:= ".$this->CI->benchmark->elapsed_time('IRU2START', 'IRU2END'));
		
		$this->CI->benchmark->mark("IRU3START");
		$finalChunk = array();
		$this->universities = $resultData['universities'];
		$this->numCourses = $resultData['courseCount'];
		$this->isCertDiplomaPage = $this->request->isCertDiplomaPage() || $this->request->getFlagToGetCertDiplomaResults();
		$this->filters = $resultData['filters']['filters'];
		if(!array_key_exists("OFR_SCHLSHP",$this->filters['moreoptions'])){
			$finalChunk['showScholarship'] = 0;
		}
		else{
			$finalChunk['showScholarship'] = 1;
		}	
		$this->banner = $resultData['currentBanner'];
		$this->userAppliedFilters = $resultData['filters']['userAppliedfilters'];
		$universitiesWithData = $result['universities'];
		$this->CI->benchmark->mark("IRU3END");
		error_log( "SetResultsInClass time taken:= ".$this->CI->benchmark->elapsed_time('IRU3START', 'IRU3END'));
		//Now to remove the double-counting
		$this->CI->benchmark->mark("IRU4START");
		$this->_removeUncessarySelectedFilters();
		$this->numUniversities = $resultData['totalCount'];
		$this->numTuples = $resultData['totalCount'];
		
		//Now merge into the chunk our new chunk
		$finalChunk['universities'] = $resultData['universities'];
		// fat footer subcat page links
		if(count($resultData['popularSubcats'])>0){
			$finalChunk['popularSubcats'] = $resultData['popularSubcats'];
		}

		if(empty($finalChunk['universities']) && !$this->request->isSolrFilterAjaxCall()){
			if((empty($universitiesWithData)) && $this->request->getPageNumberForPagination() > 1){ //But there are universities/snapshots out there
				//Redirect to the first page : There is nothing for this page, but there is stuff to show so first page will have it.
				$newURL = $this->request->getURL();
				redirect($newURL,'location',302);//temporarily
				//exit();
			}
		}
		$this->CI->benchmark->mark("IRU4END");
        error_log( "remove extra filter/ redirect time taken:= ".$this->CI->benchmark->elapsed_time('IRU4START', 'IRU4END'));
		return $finalChunk;
	}

    /*
     * Banner (Shoshkele) on category page
	 *
	 * If this function is called multiple times,
	 * do not re-compute, return the results stored in variable
	 * However if this function is called multiple times with a different request object
	 * (as in while refreshing cache of multiple pages)
	 * set recompute to TRUE
	 */
    public function getBanner($recompute = FALSE)
    {
    	//return FALSE;
    	if($this->banner && !$recompute) {
    		return $this->banner;
    	}
		// here this will use banners from acpsolr repo
		$banner = $this->categoryPageRepository->getSingleRotatedBanner($this->rotationService);
		// get single rotated banner
    	return $banner;
    }

	/*
	 * ******************************************************* Fetchers for category page data *******************************************************
	 */
	public function getFilters() {
		if ($this->filters) {
			return $this->filters;
		}
	}

	public function getCategory() {
		return $this->categoryPageRepository->getCategory ();
	}
	public function getSubCategory() {
		return $this->categoryPageRepository->getSubCategory ();
	}
	public function getLDBCourse() {
		return $this->categoryPageRepository->getLDBCourse ();
	}
	public function getCity() {
		return $this->categoryPageRepository->getCity ();
	}
	public function getCountry() {
		return $this->categoryPageRepository->getCountry ();
	}
	public function getState() {
		return $this->categoryPageRepository->getState ();
	}
	public function getRequest() {
		return $this->request;
	}
	public function setRequest(CategoryPageRequest $request) {
		$this->request = $request;
	}
	public function getRepository() {
		return $this->categoryPageRepository;
	}
	public function getTotalNoOfUniversities()
	{
		return $this->numUniversities;
	}
	public function getTotalNoOfCourses()
	{
		return $this->numCourses;
	}
	public function getUserAppliedFilters()
	{
		return $this->userAppliedFilters;
	}
	
	private function _removeUncessarySelectedFilters()
	{
		
		$applied_filters 		   = $this->request->getAppliedFilters();
		$applied_filters['fees']      	   = array_intersect($applied_filters['fees']      	   , array_keys($this->filters['fee']) );
		$applied_filters['exam']           = array_intersect($applied_filters['exam']           , array_keys($this->filters['exams']) );
		$applied_filters['specialization'] = array_intersect($applied_filters['specialization'] , array_keys($this->filters['coursecategory']) );
		$applied_filters['moreoption']     = array_intersect($applied_filters['moreoption']     , array_keys($this->filters['moreoptions']) );
		$applied_filters['city']           = array_intersect($applied_filters['city']           , array_keys($this->filters['city']) );
		$applied_filters['state']          = array_intersect($applied_filters['state']          , array_keys($this->filters['state']) );
		$applied_filters['country']        = array_intersect($applied_filters['country']        , array_keys($this->filters['country']) );	
		$scoreValues = $this->filters['examsScore'];
		foreach($applied_filters['examsScore'] as $key=>$val)
		{
			$filterDetail = explode('--',$val);
			$examKey = $filterDetail[2];
			if(array_search($filterDetail[1],$scoreValues[$examKey])=== false || array_search($filterDetail[1],$scoreValues[$examKey])=== NULL)
			{
				unset($applied_filters['examsScore'][$key]);
			}
		}
		$applied_filters = array_filter($applied_filters);
		$this->request->setAppliedFilters($applied_filters);
	}
	
	public function getNumTuples(){
		return $this->numTuples;
	}
	
}
