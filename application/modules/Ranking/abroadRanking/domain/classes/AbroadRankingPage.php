<?php
class AbroadRankingPage {
	private $cache;
	private $universities;
	private $filters;
	private $applied_filters;
	private $rankingKey;
	private $userAppliedFilters;
	private $caching = TRUE;
	
	function __construct(AbroadRankingPageRepository $rankingPageRepository, FilterGeneratorService $filterGeneratorService, FilterGeneratorService $filterGeneratorServiceForAppliedFilter, FilterProcessorService $filterProcessorService, $retainFilterGeneratorService, $retainFilterProcessorService,SorterService $sorterService,$rankingKey) {
		$this->rankingPageRepository = $rankingPageRepository;
		$this->filterGeneratorService = $filterGeneratorService;
		$this->filterProcessorService = $filterProcessorService;
		$this->filterGeneratorServiceForAppliedFilter = $filterGeneratorServiceForAppliedFilter;
		$this->retainFilterGeneratorService = $retainFilterGeneratorService;
		$this->retainFilterProcessorService = $retainFilterProcessorService;
		$this->sorterService = $sorterService;
		$this->rankingKey = $rankingKey;
	}

	public function _loadFilters($rankingPageObj) {
		$data = array ();
		$rankingPageDataObj = $rankingPageObj->getRankingPageData();
		//_p($rankingPageDataObj );die;
		foreach ($rankingPageDataObj as $key => $rankingDataTuple) {
			$university = $rankingDataTuple['university'];
                        $course = $rankingDataTuple['course'];
                                $filterValues = $this->filterGeneratorService->generateFiltersForAbroadFilters($university, $course);
				
                                $filterValues = serialize($filterValues);
				$sortValues = serialize($this->sorterService->getAbroadSortValues($course,$key));
				//_p($filterValues);die;
				$data[$key]['filters'] = $filterValues;
				$data[$key]['sortValues'] = $sortValues;
				$data[$key]['university'] = $university;
				$data[$key]['course'] = $course;
                                $this->filterGeneratorService->addValuesToAbroadFilters($university,$course);
                }
		$rankingPageObj->setRankingPageData($data);
 		$this->filters = $this->filterGeneratorService->getFilters();
		//return $filteredRankings;
	}
	
	public function _applyUserFilters($rankingPageObj)
	{
		// get the user applied filters
		$appliedFilters = $this->getAppliedFilters();

		$rankingPageObjClone = unserialize(serialize($rankingPageObj));
		// apply user filters on the resultset fetched
		if(!empty($appliedFilters))
		{
	          $filteredValues = $this->filterProcessorService->processFiltersForAbroadRanking($rankingPageObj,$appliedFilters);
	        }else{
		  $filteredValues = $rankingPageObjClone;
		}

		$data = array ();
		$rankingPageDataObj = $filteredValues->getRankingPageData();
		foreach ($rankingPageDataObj as $key => $rankingDataTuple) {
			$data [$key] = array ();
			$university = $rankingDataTuple['university'];
                        $course = $rankingDataTuple['course'];
                        $courseId = $course->getId();
                                $filterValues = $this->filterGeneratorServiceForAppliedFilter->generateFiltersForAbroadFilters($university,$course);
                                $filterValues = serialize($filterValues);
				$data[$key]['filters'] = $filterValues;
				$data[$key]['university'] = $university;
				$data[$key]['course'] = $course;
                                $this->filterGeneratorServiceForAppliedFilter->addValuesToAbroadFilters($university,$course);
                }
		$rankingPageObj->setRankingPageData($filteredValues->getRankingPageData());
		//_p($rankingPageObj);die;
		$this->userAppliedFilters = $this->filterGeneratorServiceForAppliedFilter->getFilters();
	}

	
	public function _applyUserFiltersForIndividualFilter($rankingPageObject, $filterName)
	{
		$appliedFilters = $this->getAppliedFilters();
		if(empty($appliedFilters))
			return;
		// unset country,state and city in case of location filter
		if($filterName == 'location')
		{
			unset($appliedFilters['country']);
			unset($appliedFilters['city']);
			unset($appliedFilters['state']);
		}
		else
			unset($appliedFilters[$filterName]);
		
		if(!empty($appliedFilters))
		{
			$rankingPageDataObjAfterFilter = $this->retainFilterProcessorService[$filterName]->processFiltersForAbroadRanking($rankingPageObject,$appliedFilters);
		}
		else
		{
			$rankingPageDataObjAfterFilter = $rankingPageObject;
		}
		$data = array ();
		$count = 0;
		$rankingDataObjAfterFilter = $rankingPageObject->getRankingPageData();
		foreach ( $rankingDataObjAfterFilter as $key=>$tuple ) {
			$count++;
			$university = $tuple['university'];
			$course = $tuple['course'];
			$filterValues = $this->retainFilterGeneratorService[$filterName]->generateFiltersForAbroadFilters ( $university,$course );
			$filterValues = serialize ( $filterValues );
			$this->retainFilterGeneratorService[$filterName]->addValuesToAbroadFilters ( $university,$course );
		}
	
		$userFilterName = "userAppliedFiltersFor".$filterName;
		$this->$userFilterName = $this->retainFilterGeneratorService[$filterName]->getFilters();
	}	
    
     	

	public function getFilters() {
		if ($this->filters) {
			return $this->filters;
		}
	}
	public function getRequest() {
		return $this->request;
	}
	public function disableCaching() {
		$this->caching = FALSE;
	}
	public function enableCaching() {
		$this->caching = TRUE;
	}
	
	public function getUserAppliedFilters()
	{
		return $this->userAppliedFilters;
	}
	public function getUserAppliedFiltersForSpecialization()
	{
		return $this->userAppliedFiltersForspecialization;
	}
	public function getUserAppliedFiltersForExam()
	{
		return $this->userAppliedFiltersForexam;
	}
	public function getUserAppliedFiltersForLocation()
	{
		return $this->userAppliedFiltersForlocation;
	}
	public function getUserAppliedFiltersForMoreoption()
	{
		return $this->userAppliedFiltersFormoreoption;
	}
	public function getUserAppliedFiltersForFees()
	{
		return $this->userAppliedFiltersForfees;
	}
	
	public function setAppliedFilters($filters = array())
	{
		$this->appliedFilters = $filters;
	}
	
	public function _removeUncessarySelectedFilters()
	{
		$applied_filters 		   = $this->getAppliedFilters();
		$applied_filters['fees']      	   = array_intersect($applied_filters['fees']      	   , array_keys($this->filters['fees']->getFilteredValues()		) );
		$applied_filters['exam']           = array_intersect($applied_filters['exam']           , array_keys($this->filters['exams']->getFilteredValues()		) );
		$applied_filters['specialization'] = array_intersect($applied_filters['specialization'] , array_keys($this->filters['coursecategory']->getFilteredValues()	) );
		$applied_filters['city']           = array_intersect($applied_filters['city']           , array_keys($this->filters['city']->getFilteredValues()		) );
		$applied_filters['state']          = array_intersect($applied_filters['state']          , array_keys($this->filters['state']->getFilteredValues()		) );
		$applied_filters['country']        = array_intersect($applied_filters['country']        , array_keys($this->filters['country']->getFilteredValues()		) );

		$applied_filters = array_filter($applied_filters);
		$this->setAppliedFilters($applied_filters);
	}
	
	public function getAppliedFilters()
	{
		if(!empty($this->appliedFilters)){
			return $this->appliedFilters;
		}
		else
		{
		    // get filter data from the cookie
		    $filterRankKey 	= "saRankFilter-".$this->rankingKey;
		    $filterValue 	= $_COOKIE[$filterRankKey];
		    $filterValue 	= json_decode($filterValue,true);
		    
		    // get the filter selection order from the cookie to be used to set the javascript object in the header
		    $this->filterSelectionOrder = $filterValue["filterSelectedOrder"];
		    
		    // parse the form string to make the individual variables out of string
		    parse_str($filterValue["filterValues"]);

		    if(!empty($exam))
			$appliedFilters["exam"] = $exam;
		    if(!empty($city))
			$appliedFilters["city"] = $city;
		    if(!empty($fee))
			$appliedFilters["fees"] = $fee;
		    if(!empty($course))
			$appliedFilters["specialization"] = $course;
		    if(!empty($countryList))    
			$appliedFilters["country"] = $countryList;
		    if(!empty($stateList))    
			$appliedFilters["state"] = $stateList;
		    if(!empty($cityList))
			$appliedFilters["city"] = $cityList;
			
		    $this->appliedFilters = $appliedFilters;
		    return $this->appliedFilters;
		}
	}

	public function getFilterSelectionOrder()
	{
	    return $this->filterSelectionOrder;
	}
	
	
        
        public function _sortCourses($rankingPageObject, $sortingCriteria)
	{
                $rankingPageData = $rankingPageObject->getRankingPageData();
		$rankingPageData = $this->_unserializeSortValues($rankingPageData);
		$rankingPageData = $this->sorterService->abroadSort($rankingPageData, $sortingCriteria);
		$rankingPageObject->setRankingPageData($rankingPageData);
		return $rankingPageObject;
	}
	
	private function _serializeSortValues($rankingPageData)
	{
		foreach($rankingPageData as $key => $getRankingPageTuple)
		{
		  $getRankingPageTuple['sortValues'] = serialize($getRankingPageTuple['sortValues']);
		}
	    return $rankingPageData;
	}
	    
	private function _unserializeSortValues($rankingPageData)
	{
		foreach($rankingPageData as $key => $RankingPageTuple)
		{
		  $rankingPageData[$key]['sortValues'] = unserialize($RankingPageTuple['sortValues']);
		}
	    return $rankingPageData;
	}
}
