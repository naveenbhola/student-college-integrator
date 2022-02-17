<?php
class AbroadCategoryPage {
	private $request;
	private $categoryPageRepository;
	private $cache;
	private $numInstitutes;
	private $numUniversities;
	private $institutes;
	private $universities;
	private $snapshotUniversities;
	private $filters;
	private $caching = TRUE;
	private $universityIdsWithType;
	private $banner;
	private $numTuples;
	private $abroadCategoryPageCaching = TRUE;
	function __construct(AbroadCategoryPageRequest $request, AbroadCategoryPageRepository $categoryPageRepository, FilterGeneratorService $filterGeneratorService, FilterGeneratorService $filterGeneratorServiceForAppliedFilter, FilterProcessorService $filterProcessorService, SorterService $sorterService, $cache, $retainFilterGeneratorService, $retainFilterProcessorService,RotationService $rotationService) {
		$this->request = $request;
		$this->categoryPageRepository = $categoryPageRepository;
		$this->filterGeneratorService = $filterGeneratorService;
		$this->filterProcessorService = $filterProcessorService;
		$this->sorterService = $sorterService;
		$this->cache = $cache;
		$this->filterGeneratorServiceForAppliedFilter = $filterGeneratorServiceForAppliedFilter;
		$this->retainFilterGeneratorService = $retainFilterGeneratorService;
		$this->retainFilterProcessorService = $retainFilterProcessorService;
		$this->rotationService = $rotationService;
	}
	public function getCompleteUniversityList()
	{
		return $this->universities;
	}
	public function getUniversities($filterGenerationInCacheActive = false)
	{
		$this->CI = & get_instance();
		$builder = new ListingBuilder;
		// fetch all the university data for category page
		$abroadCategoryPageCache = $this->CI->load->library('categoryList/cache/AbroadCategoryPageCache');
		// first check in cache
		// incase of ajax call or exam accepting pages no need to store/read from cache
		if(!$this->request->isAJAXCall() && !$this->request->isExamCategoryPage() && !($this->request->getAcceptedExamId() >0))
		{
			$resultData = $abroadCategoryPageCache->getUniversities($this->request->getPageKey());
		}
		if(empty($resultData ))
		{
			$resultData = $this->categoryPageRepository->getUniversities();
			if($this->abroadCategoryPageCaching && !$this->request->isAJAXCall() && !$this->request->isExamCategoryPage() && !($this->request->getAcceptedExamId() >0) && $resultData['countCourse']>50)
			{
				$abroadCategoryPageCache->storeUniversities($this->request->getPageKey(),$resultData);
			}
		}
		$finalChunk = array();
		$this->universities = $resultData['universities'];
		$this->numCourses = $resultData['countCourse'];
		$this->universityIdsWithType = $resultData['universityIdsWithType'];
		
		global $certificateDiplomaLevels;
		$degreeFlag=0;
		foreach($resultData['universities'] as $univ)
		{
			if($degreeFlag==1)
			{ break; }
			$courses = $univ->getCourses();
			if(count($courses)>0){
				foreach($courses as $course){
					if(!in_array($course->getCourseLevel1Value(),$certificateDiplomaLevels)) // degree course
					{
						$degreeFlag = 1;break;
					}
				}
			}
		}
		$this->isCertDiplomaPage = ($this->request->isCertDiplomaPage()||$degreeFlag==0?true:false);
		// generate all filters according to the university data fetched
		$universitiesWithData = $this->_loadFilters($this->universities);
		$scholarshipMainResult = $this->filterGeneratorService->getFilters();
		$scholarshipMainResult = $scholarshipMainResult['moreoptions']->getFilteredValues();
		if(!in_array("OFR_SCHLSHP",array_keys($scholarshipMainResult))){
			$finalChunk['showScholarship'] = 0;
		}
		else{
			$finalChunk['showScholarship'] = 1;
		}
		//$snapshotUniversitiesWithData = $this->_loadSnapshotFilters($this->snapshotUniversities);
		$this->filters = $this->filterGeneratorService->getFilters();
        $mapCoursesWithSortVals = $this->createMapCourseIdSortValues($universitiesWithData['universities_array']);
		$this->addSortValInUniversityIdsWithType($mapCoursesWithSortVals);
		
		// remove those selected filters that are not in the extracted parent filter set
		
		// get filters seperately for each filter category
		if(SKIP_ABROAD_CP_FILTERS)
		{
			$filterArr = array("exam","specialization","moreoption","fees");
		}
		else{
			$filterArr = array("exam","specialization","location","moreoption","fees","examsScore","examsMinScore");
		}
			
		$appliedFilters = $this->request->getAppliedFilters();
		foreach($filterArr as $value)
		{
                    if(empty($appliedFilters))
                            break;
			
                    // copy(be value) university data to another variable
                    $universitiesWithDataCopy["universities_array"] = $universitiesWithData["universities_array"];
                    foreach($universitiesWithData["universities_obj"] as $key=>$univ)
                        $universitiesWithDataCopy["universities_obj"][$key] = clone $univ;
                    // apply user filters to set the indiviadual filters value
                    $this->_applyUserFiltersForIndividualFilter($universitiesWithDataCopy, $value);
		}
		
		// Function for processing of user selected filters
		$result = $this->_applyUserFilters($universitiesWithData/*,$snapshotUniversitiesWithData*/);
		//if(empty($result['universities']['universities_obj'])){ $finalChunk['snapshotOnlyPostFilteration'] = 1; }
		//else { $finalChunk['snapshotOnlyPostFilteration'] = 0; }
		$universitiesWithData = $result['universities'];
		//$snapshotUniversitiesWithData = $result['snapshotUniversities'];
		$countDistinctUniversities = count($universitiesWithData["universities_obj"])/* + count($snapshotUniversitiesWithData["universities_obj"])*/;
		//Now to remove the double-counting
		$this->_removeUncessarySelectedFilters();
		$this->numUniversities = $countDistinctUniversities;
		$this->numTuples = count($universitiesWithData["universities_obj"])/* + count($snapshotUniversitiesWithData["universities_obj"])*/;
		//$finalSnapshotCourses = $this->_sortSnapshotUniversitiesAndCourses($snapshotUniversitiesWithData['universities_obj']);
		$sortingCriteria = $this->request->getSortingCriteria();
		if(!empty($sortingCriteria) && $sortingCriteria['sortBy'] != 'none') {
			$sortedUniversities = $this->_sortUniversities($universitiesWithData['universities_array'], $sortingCriteria);
			$universities_chunk_array_withoutDept = $this->_applyPagination($sortedUniversities['sortedUniversitiesWithoutDept']);
			$universities_chunk_obj = $this->categoryPageRepository->loadUniversities($universities_chunk_array_withoutDept, $universitiesWithData['universities_obj']);
		}
		else {
			//sort free universities on view count basis
			$sortingCriteria['sortBy'] = 'viewCount';
			$sortingCriteria['params']['order'] = 'DESC';
			$sortedUniversities = $this->_sortUniversities($this->universityIdsWithType['free'], $sortingCriteria);
			$this->universityIdsWithType['free'] = $sortedUniversities['sortedUniversitiesWithoutDept'];
			
            //sort courses within university on view count basis
			$mapOfUniIdAndSortedCourseId = $this->sortCoursesOnViewCount($universitiesWithData['universities_array']);
			
			//apply rotation service
			$this->universityIdsWithType = $this->_applyRotationService($this->universityIdsWithType);
			
			$universitiesWithData['universities_obj'] =  $this->_arrangeUniversitiesWithType($this->universityIdsWithType,$universitiesWithData['universities_obj']);
			$universities_chunk_obj = $this->_applyPagination($universitiesWithData['universities_obj']);
			
			//set sort order of courses for each university object
			$universities_chunk_obj = $this->setSortOrderForCoursesInUniversities($mapOfUniIdAndSortedCourseId, $universities_chunk_obj);
		}
		//Now merge into the chunk our new chunk
		$finalChunk['universities'] = $universities_chunk_obj;
		//$finalChunk['snapshotUniversities'] = $this->_getSnapshotChunk($finalSnapshotCourses,count($result['universities']['universities_obj']));
		if(empty($finalChunk['universities'])/* && empty($finalChunk['snapshotUniversities'])*/ && !$filterGenerationInCacheActive){
			//We got empty chunks
			//_p($this->request->getURL());
			//_p($_SERVER);die;
			
			if((!empty($universitiesWithData) /*|| !empty($snapshotUniversitiesWithData)*/) && strtok($this->request->getUrl(),"?") != getCurrentPageURLWithoutQueryParams()){ //But there are universities/snapshots out there
				//Redirect to the first page : There is nothing for this page, but there is stuff to show so first page will have it.
				$newURL = $this->request->getURL();
				Header("Location: $newURL");
				//exit();
			}
		}
		$finalChunk['onlyCertDiplomaPage'] = $this->isCertDiplomaPage;
		return $finalChunk;
	}
	
	private function _loadFilters($universities, AbroadCategoryPageRequest $customRequest = NULL) {
		$universitiesWithData = array ();
		$data = array ();
		$courseIdArray = array();
		global $levelFilter;
        $levelFilter = false;
		foreach ($universities as $universityId => $university) {
		//	if($universityId == 551){unset($universities[$universityId]); continue;}		// This is for a live issue with this university listing
			$data [$universityId] = array ();
			$courses = $university->getCourses();
			$department = '';		//We have removed departments from category page loading. Filters don't use them anyway.
			foreach ( $courses as $course ) {
				$courseId = $course->getId();
				$filterValues = $this->filterGeneratorService->generateFiltersForAbroadFilters($university, $department, $course, $this->isCertDiplomaPage);
				$filterValues = serialize($filterValues);
				$sortValues = serialize($this->sorterService->getAbroadSortValues($course));
				$courseIdArray[] = $courseId;
				$data [$universityId][$courseId] = array (
						'filterValues' => $filterValues,
						'sortValues' => $sortValues
				);
			}
			
		}
		$CI = &get_instance();
		$AbroadListingCommonLib  =   $CI->load->library('listing/AbroadListingCommonLib');
		$viewCounts = $AbroadListingCommonLib->getViewCountForCourseListByDays($courseIdArray,7);
		foreach($data as $univId => $course){
			foreach($course as $courseId=>$courseProps){
				$sortVariable = unserialize($courseProps['sortValues']);
				$sortVariable[2] = $viewCounts[$courseId];					// 2 is the global variable value for View Count
				$data[$univId][$courseId]['sortValues'] = serialize($sortVariable);
			}
		}
		unset($CI);
		$universitiesWithData ['universities_obj'] = $universities;
		$universitiesWithData ['universities_array'] = $data;
		return $universitiesWithData;
	}
	
	private function _loadSnapshotFilters($snapshotUniversities){
                if(empty($snapshotUniversities)){
                    return array();
                }
		$universitiesWithData = array();
		$data = array ();
		$startTime = microtime(true);
		$time = array();
		$serTime = 0;
		foreach ($snapshotUniversities as $universityId => $university) {
			$data [$universityId] = array ();
			$courses = $university->getSnapshotCourses($this->snapshotRepository,true);
			
			foreach ( $courses as $course ) {
				$courseId = $course->getId();
				$filterValues = $this->filterGeneratorService->generateSnapshotFiltersForAbroadFilters($university, $course,$this->subCategoryIdNameMapping,$time);
				//$this->filterGeneratorService->addSnapshotValuesToAbroadFilters($university, $course,$this->subCategoryIdNameMapping);
				$serStart = microtime(true);
				$filterValues = serialize($filterValues);
				$serEnd = microtime(true);
				$serTime = $serEnd - $serStart;
				$data [$universityId][$courseId] = array (
							'filterValues' => $filterValues,
					);
				
			}
		}
		$endTime = microtime(true);
		$universitiesWithData ['universities_obj'] = $snapshotUniversities;
		$universitiesWithData ['universities_array'] = $data;
		return $universitiesWithData;
	}
	
	private function createMapCourseIdSortValues($universitiesArrWithSortVals) {
		$coursesWithSortVals = array();
		foreach($universitiesArrWithSortVals as $uniId => $courses) {
			foreach($courses as $courseId => $value) {
				$coursesWithSortVals[$courseId] = $value['sortValues'];
			}
		}
		return $coursesWithSortVals;
	}
	
	private function addSortValInUniversityIdsWithType($coursesWithSortVals) {
		$universityIdsWithType = $this->universityIdsWithType;
		foreach($universityIdsWithType as $type => $universities) {
			foreach($universities as $universityId => $courses) {
				foreach($courses as $courseId => $someValue) {
					$universityIdsWithType[$type][$universityId][$courseId]['sortValues'] = $coursesWithSortVals[$courseId];
				}
			}
		}
		$this->universityIdsWithType = $universityIdsWithType;
	}
	
	private function sortCoursesOnViewCount($universities) {
		$universities = $this->_unserializeSortValues($universities);
		return $this->abroadCourseSort($universities);
	}
	
	public function abroadCourseSort($universities) {		// Sorting similar courses within university
        foreach($universities as $uniId => $courses) {
			$sortValue = array();
			foreach($courses as $courseId => $values) {
				$sortValue[$values['sortValues'][2]][] = $courseId; //viewCount
			}
			krsort($sortValue);
			foreach($sortValue as $viewCount => $sortedCourseIds) {
				foreach($sortedCourseIds as $sortedCourseId) {
					$mapOfUniAndSortedCourse[$uniId][] = $sortedCourseId;
				}
			}
        }
		
		return $mapOfUniAndSortedCourse;
    }
	
	private function setSortOrderForCoursesInUniversities($mapOfUniAndSortedCourse, $universityObjs) {
		//create a map of object and corresponding id
		foreach($universityObjs as $universityId => $university) {
			$departments = $university->getDepartments();
			foreach($departments as $index => $department) {
				$courses = $department->getCourses();
				foreach($courses as $course){
					$university->setSortOrderForSimilarCourses($mapOfUniAndSortedCourse[$universityId]);
				}
			}
		}
		
		return $universityObjs;
	}
	
	private function _sortUniversities($universities, $sortingCriteria)
	{
		$universities = $this->_unserializeSortValues($universities);
		$sortedUniversities = $this->sorterService->abroadSort($universities, $sortingCriteria);
		$sortedUniversitiesSer['sortedUniversitiesWithoutDept'] = $this->_serializeSortValues($sortedUniversities['sortedUniversitiesWithoutDept'], false);
		return $sortedUniversitiesSer; 
	}
	
	/*
     * Serialize/Unserialize sort values of the display course of each institute
     * Display course is the first course
     * Unserializing applied just before sorting
     * Serializing applied before storing in cache
     */ 
    private function _serializeSortValues($universities, $flagWithDept)
    {
		if($flagWithDept){
			foreach($universities as $universityId => $departments) {
				foreach($departments as $departmentId => $courses) {
					foreach($courses as $courseId => $value) {
						$universities[$universityId][$departmentId][$courseId]['sortValues'] = serialize($value['sortValues']);
					}
				}
			}
		} else {
			foreach($universities as $universityId => $courses) {
				foreach($courses as $courseId => $value) {
					$universities[$universityId][$courseId]['sortValues'] = serialize($value['sortValues']);
				}
			}
		}
        return $universities;
    }
	
	private function _unserializeSortValues($universities)
    {
		foreach($universities as $universityId => $courses) {
			foreach($courses as $courseId => $value) {
				$universities[$universityId][$courseId]['sortValues'] = unserialize($value['sortValues']);
			}
        }
		return $universities;
    }
	
	private function _applyPagination($universities) {
		$startIndex = 0;
		$resultsPerPage = $this->request->getSnippetsPerPage ();
		
		if ($pageNumber = $this->request->getPageNumberForPagination()) {
			$totalNoOfResults = $this->getTotalNoOfUniversities();
			$lastPageIndex = ceil($totalNoOfResults/$resultsPerPage);
			if(empty($lastPageIndex))
				$lastPageIndex = 1;
			/*
			 *This has been removed from here to accomodate the new snapshot courses which will be added to the Category Page.
			if($lastPageIndex < $pageNumber){
				$this->request->setData(array('pageNumber' => 1));
				$newURL = $this->request->getURL();
				Header("Location: $newURL");
				exit();
				//$pageNumber = $this->request->getPageNumberForPagination ();
			}
			*/
			$startIndex = ($pageNumber - 1) * $resultsPerPage;
		}
		
		return array_slice ( $universities, $startIndex, $resultsPerPage, TRUE );
    }
	
	/*
	 *	Author : Rahul Bhatnagar
	 */
	private function _getSnapshotChunk($finalSnapshotUniversities,$normalCourseCount){
                if(empty($finalSnapshotUniversities)){
                    return array();
                }
		$startIndex = 0;
		$snapshotCount = count($finalSnapshotUniversities);
		$resultsPerPage = $this->request->getSnippetsPerPage();
		$pageNumber = $this->request->getPageNumberForPagination();
		if(empty($pageNumber)) $pageNumber = 1;
		$detailCourseCountOnThisPage = $normalCourseCount-($pageNumber*$resultsPerPage); 	//While this number is > 20, we do nothing.
																							//When this number is in [0-20] we show limited chunk
																							//When this number is <0, we show 20 (or max) sized chunk.
		$magicNumber = -1*$detailCourseCountOnThisPage;
		$resultsToShow = $resultsPerPage;
		if($magicNumber >0 && $magicNumber <20){
			$resultsToShow = $magicNumber;
		}
		else if($magicNumber > 20){
			$startIndex = $magicNumber-20;
		}
		else if($magicNumber <= 0){
			$resultsToShow = 0;
		}
		else{
			//magic number undefined cuz reasons.
		}
		if($resultsToShow > 20){
			$resultsToShow = 20;
		}
		return array_slice ( $finalSnapshotUniversities, $startIndex, $resultsToShow, TRUE );
	}

	/*
	* Purpose : Apply user selected filter on institutes
	* To Do   : none
	* Author  : Romil Goel
	*/ 
	private function _applyUserFilters($universities/*,$snapshotUniversities*/)
	{
		// get the user applied filters
		$appliedFilters = $this->request->getAppliedFilters();

		// apply user filters on the resultset fetched
		if(!empty($appliedFilters))
	     {
            $result = $this->filterProcessorService->processFiltersForAbroad($universities,$appliedFilters/*,$snapshotUniversities*/);
			$universities = $result['universities'];
			//$snapshotUniversities = $result['snapshotUniversities'];
            $this->numCourses = $this->filterProcessorService->getNoOfAbroadCoursesLeftAfterFilterProcess();
           }
		$universities = $universities['universities_obj'];
		//$snapshotUniversities = $snapshotUniversities['universities_obj'];
		
		$universitiesWithData = array ();
		$data 			 = array ();
		$courseIdArray = array();
		// loop through each university, department, course to regenerate the filters
		foreach ( $universities as $universityId => $university ) {
			
			$data [$universityId] = array ();
			$courses = $university->getCourses ();
			$department = '';
			foreach ( $courses as $course ) {
				$courseId = $course->getId ();
				$filterValues = $this->filterGeneratorServiceForAppliedFilter->generateFiltersForAbroadFilters ( $university, $department, $course );
				$filterValues = serialize ( $filterValues );
				$sortValues = serialize($this->sorterService->getAbroadSortValues($course));
				$courseIdArray[] = $courseId;
				$data [$universityId][$courseId] = array (
						'filterValues' => $filterValues,
						'sortValues' => $sortValues
				);
			}
			
		}
		
		$CI = &get_instance();
		$AbroadListingCommonLib  =   $CI->load->library('listing/AbroadListingCommonLib');
		$viewCounts = $AbroadListingCommonLib->getViewCountForCourseListByDays($courseIdArray,7);
		foreach($data as $univId => $course){
			foreach($course as $courseId=>$courseProps){
				$sortVariable = unserialize($courseProps['sortValues']);
				$sortVariable[2] = $viewCounts[$courseId];
				$data[$univId][$courseId]['sortValues'] = serialize($sortVariable);
			}
		}
		
		// Do the same for snapshot Courses
		/*
		$snapshotUniversitiesWithData = array();
		$snapshotData = array();
		foreach($snapshotUniversities as $universityId =>$university){
			$courses = $university->getSnapshotCourses($this->snapshotRepository);
			foreach($courses as $course){
				$courseId = $course->getId();
				$filterValues = $this->filterGeneratorServiceForAppliedFilter->generateSnapshotFiltersForAbroadFilters($university,$course,$this->subCategoryIdNameMapping );
				$filterValues = serialize($filterValues);
				$snapshotData[$universityId][$courseId] = array('filtervalues'=>$filterValues);
				//$this->filterGeneratorServiceForAppliedFilter->addSnapshotValuesToAbroadFilters($university,$course,$this->subCategoryIdNameMapping);
			}
		}*/
		$universitiesWithData ['universities_obj'] = $universities;
		$universitiesWithData ['universities_array'] = $data;
		//$snapshotUniversitiesWithData['universities_obj'] = $snapshotUniversities;
		//$snapshotUniversitiesWithData['universities_array'] = $snapshotData;
		$this->userAppliedFilters = $this->filterGeneratorServiceForAppliedFilter->getFilters();

		return array('universities' => $universitiesWithData/*,'snapshotUniversities'=>$snapshotUniversitiesWithData*/);
	}

	/*
	* Purpose : Method to compute filters to be shown for individual filter categories
	* To Do   : none
	* Author  : Romil Goel
	*
	* Snapshot Course Functionality edits by Rahul Bhatnagar
	*/ 
	private function _applyUserFiltersForIndividualFilter($universities, $filterName)
	{
		$appliedFilters = $this->request->getAppliedFilters();
		// get the applied filters, return if empty
		if(empty($appliedFilters))
		{
			return; 
		}
		// remove whichever filtername was sent, unset country,state and city in case of location filter
		if($filterName == 'location')
		{
			unset($appliedFilters['country']);
			unset($appliedFilters['city']);
			unset($appliedFilters['state']);
		}
		else
		{
			unset($appliedFilters[$filterName]);
		}
		// now if applied filters is still not empty
		if(!empty($appliedFilters))
		{	// process the filter passed for given universities
			$result = $this->retainFilterProcessorService[$filterName]->processFiltersForAbroad($universities,$appliedFilters);
			// this will give filtered results
			$universitiesTemp = $result['universities']; // keep the result from this
		}
		else		// When only a single filter type is selected and it gets unset, use the full dataset to make it because
					// of the independence filter clause.
		{
			$universitiesTemp = $universities;
		}
		$universitiesTemp = $universitiesTemp['universities_obj'];
		$universitiesWithFilters = array ();
		$data = array ();
		//$count = 0;
		foreach ( $universitiesTemp as $universityId => $university ) { // loop over each univ
			$data [$universityId] = array ();
			$courses = $university->getCourses ();
			$department = '';
			foreach ( $courses as $course ) { // loop over each course of each univ
				$filterValues = $this->retainFilterGeneratorService[$filterName]->generateFiltersForAbroadFilters ( $university, $department, $course, $this->isCertDiplomaPage);
			}
		}
		$userFilterName = "userAppliedFiltersFor".$filterName;
		$this->$userFilterName = $this->retainFilterGeneratorService[$filterName]->getFilters();
	}
     	
    private function _applyRotationService($universities = array()){
        /*
        * Rotate sticky, main and paid
        */
    	
        if(is_array($universities['sticky']) && count($universities['sticky']) > 1) {
            $universities['sticky'] = $this->rotationService->rotateStickyInstitutes($universities['sticky'],$this->getBanner());
        }
        if(is_array($universities['main']) && count($universities['main']) > 1) {
            $universities['main'] = $this->rotationService->rotateMainInstitutes($universities['main']);
        }
        if(is_array($universities['paid']) && count($universities['paid']) > 1) {
            $universities['paid'] = $this->rotationService->rotatePaidInstitutes($universities['paid']);
        }
        return $universities;
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
    
    	$banners = $this->categoryPageRepository->getBanners();
    	
    	if(is_array($banners) && count($banners) > 0) {
    		$bannersAfterRotation = $this->rotationService->rotateBanners($banners,TRUE);/*** TRUE in Second parameter represent abroad request in common code***/
    		$this->banner = array_pop($bannersAfterRotation);
    		return $this->banner;
    	}
    }
	private function _arrangeUniversitiesWithType($universityIdsWithType, $universities) {
		if(empty($universities)){
			return $universities;
		} 	
		$universitiesObjs ['main'] = array ();
		$universitiesObjs ['paid'] = array ();
		$universitiesObjs ['sticky'] = array ();
		foreach ( $universityIdsWithType as $type => $universityIdsWithDeptandCourseIds ) {
			$universityIds = array_keys ( $universityIdsWithDeptandCourseIds );
			$isPaidInventory = FALSE;
			foreach ( $universityIds as $universityId ) {
				if (isset ( $universities [$universityId] )) {
					$univObj = $universities [$universityId];
					if ($type == 'sticky') {
						$univObj->setSticky ();
						$isPaidInventory = TRUE;
					} elseif ($type == 'main') {
						$univObj->setMain ();
						$isPaidInventory = TRUE;
					} else if($type == 'paid') {
						$isPaidInventory = TRUE;
					} else {
						$isPaidInventory = FALSE;
					} 
					if($isPaidInventory) {
					$universitiesObjs [$type] [$universityId] = $univObj;
					unset ( $universities [$universityId] ); 
					}
				}
			}
		}
	         
        if(empty($universities)) {
            $universities = array();
        }	
		$universities = $universitiesObjs ['sticky'] + $universitiesObjs ['main'] + $universitiesObjs ['paid'] + $universities;
		return $universities;
	}

	/*;;
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
	public function disableCaching() {
		$this->caching = FALSE;
	}
	public function enableCaching() {
		$this->caching = TRUE;
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
	public function getUserAppliedFiltersForSpecialization()
	{
		return $this->userAppliedFiltersForspecialization;
	}
	public function getUserAppliedFiltersForExam()
	{
		return $this->userAppliedFiltersForexam;
	}
	public function getUserAppliedFiltersForExamsScore()
	{
		return $this->userAppliedFiltersForexamsScore;
	}
	public function getUserAppliedFiltersForExamsMinScore(){
		return $this->userAppliedFiltersForexamsMinScore;
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
	
	private function _removeUncessarySelectedFilters()
	{
		
		$applied_filters 		   = $this->request->getAppliedFilters();

		$applied_filters['fees']      	   = array_intersect($applied_filters['fees']      	   , array_keys($this->filters['fees']->getFilteredValues()		) );
		$applied_filters['exam']           = array_intersect($applied_filters['exam']           , array_keys($this->filters['exams']->getFilteredValues()		) );
		$applied_filters['specialization'] = array_intersect($applied_filters['specialization'] , array_keys($this->filters['coursecategory']->getFilteredValues()	) );
		$applied_filters['moreoption']     = array_intersect($applied_filters['moreoption']     , array_keys($this->filters['moreoptions']->getFilteredValues()	) );
		$applied_filters['city']           = array_intersect($applied_filters['city']           , array_keys($this->filters['city']->getFilteredValues()		) );
		$applied_filters['state']          = array_intersect($applied_filters['state']          , array_keys($this->filters['state']->getFilteredValues()		) );
		$applied_filters['country']        = array_intersect($applied_filters['country']        , array_keys($this->filters['country']->getFilteredValues()		) );	
		$scoreValues = $this->filters['examsScore']->getFilteredValues();
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
	
	private function _sortSnapshotUniversitiesAndCourses($snapshotUniversitiesWithData){
                if(empty($snapshotUniversitiesWithData)){
                    return array();
                }
                $snapshotCourseIds = array();
		foreach($snapshotUniversitiesWithData as $university){
			foreach($university->getSnapshotCourses($this->snapshotRepository) as $course){
				$snapshotCourseIds[] = $course->getId();
			}
		}
		$CI = & get_instance();
		$library = $CI->load->library('listing/AbroadListingCommonLib');
		$courseViewCounts = $library->getViewCountForListingsByDays($snapshotCourseIds,'snapshotcourse',21);
		//_p($courseViewCounts);die;
		$universitySortCriteria = array();
		foreach($snapshotUniversitiesWithData as $university){
			$university->sortInternalSnapshotCourses($courseViewCounts);	// This will sort the internal courses of each university object.
			$universitySortCriteria[$university->getId()] = $university->getMaxViewCountOfInternalSnapshotCourses($courseViewCounts);
		}
		usort($snapshotUniversitiesWithData,function($univ1,$univ2) use ($universitySortCriteria){		// This will sort universities based on view count.
			return -1*($universitySortCriteria[$univ1->getId()] - $universitySortCriteria[$univ2->getId()]);
			});
		return $snapshotUniversitiesWithData;
	}
	
}
