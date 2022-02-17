<?php

class CategoryPageSolr
{
    private $request;
    private $categoryPageRepository;
    private $categoryPageSolrRepository;
    private $filterGeneratorService;
    private $rotationService;
    private $sorterService;
    private $cache;
    private $bucketingService;
    
    private $banner;
    private $numInstitutes;
    private $institutes;
	private $locationWiseInstitutesRaw;
    private $multiLocationInstitutes;
    private $filters;
    private $filtersToHide = array();
    
    private $defaultSortingCriteria = array('sortBy' => 'viewCount','order' => 'DESC');
    const SORTING_REFRESH_INTERVAL = 6;
    private $caching = TRUE;
    private $apply_bucketing = FALSE;
    private $RNR_SUB_CATEGORY_IDS = array(23, 56);

    function __construct(CategoryPageRequest $request,
                         CategoryPageRepository $categoryPageRepository,
    					 CategoryPageSolrRepository	$categoryPageSolrRepository,
    					 FilterGeneratorService $filterGeneratorService,
                         RotationService $rotationService,
                         SorterService $sorterService,
                         BucketingService $bucketingService,
                         $cache
                        )
    {
        $this->request = $request;
        $this->categoryPageSolrRepository = $categoryPageSolrRepository;
        $this->categoryPageRepository = $categoryPageRepository;
        $this->filterGeneratorService = $filterGeneratorService;
        $this->rotationService = $rotationService;
        $this->sorterService = $sorterService;
        $this->bucketingService = $bucketingService;
        $this->cache = $cache;
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
        // if page is a mltilocation category page, return banner for the first lisitng in the returned array
	$sortingCriteria = $this->request->getSortingCriteria();
	// .. check if multilocatn page & sorting not applied
	if($this->request->isMultilocationPage() && (empty($sortingCriteria) || $sortingCriteria == 'none'))
	{
	    if(count($this->multiLocationInstitutes)== 0)
	    {
		return;
	    }
	    // get institutes ...
	    $institutes = $this->multiLocationInstitutes;
	    // find first one
	    $institutes = array_slice($institutes, 0, 1,true);
	    $institutes = $this->categoryPageRepository->loadInstitutes($institutes);
	    $institute = reset($institutes);
	    // get all banners
	    /* to load relevant set of banners     */
	    foreach($this->locationWiseInstitutesRaw as $locData)
	    {
		if(count($locData['instituteData']['cs']) > 0)
		{
		    $locationForBannerPool = array ( 'location_type' => $locData['location_type'],
						     'location_id'   => $locData['location_id']);
		    break;
		}
	    }
	    /* to load relevant set of banners:END */
	    $banners = $this->categoryPageRepository->getMultilocationShoshkele($locationForBannerPool);
	    $banners = $this->_applyCategoryFilter($banners);
	    $bannerFound = false;
	    $independentBannners = array();
	    $coupledBanners = array();
	    // find banner
	    foreach($banners as $k=>$banner)
	    {
		if($institute->getId() == $banner->getInstituteId())
		{
		    $this->banner = $banner;
		    $bannerFound = true;
		    break;
		}
		else{
		    if($banner->getInstituteId() == "")
		    {	//collect independent shoshkeles so that they can be randomly picked with cat sponsors that do not have shoshkele coupled to them (discussed with saurabh shakya .... mentioned in LF-114)
			$independentBannners[$k] = $banner;
		    }
		    else{
			//collect already coupled shoshkeles so that they can be randomly picked with cat sponsors that do not have shoshkele coupled to them (discussed with saurabh shakya .... mentioned in LF-114)
			$coupledBanners[$k] = $banner;
		    }
		}
	    }
	    // if no banner found but there are independent shoshkeles available
	    if(!$bannerFound ){
		if(count($independentBannners)>0){
		    $key = array_rand($independentBannners,1);
		    //randomly select among independent banners & set as banner to be used
		    $this->banner = $independentBannners[$key];
		}
		else if(count($coupledBanners)>0){
		    $key = array_rand($coupledBanners,1);
		    //randomly select among coupled banners & set as banner to be used
		    $this->banner = $coupledBanners[$key];
		}
	    }
	    // return banner
	    return $this->banner;
	}
	
        if($this->banner && !$recompute) {
            return $this->banner;
        }
        
        /*
         * Get banners from cache
         */ 
        if($this->caching) {
            $banners = $this->cache->getBanners($this->request);
        }
        
        /*
         * If not found in cache, fetch from db and store in cache
         */ 
        if(!$banners) {
            $banners = $this->categoryPageRepository->getBanners();
            $this->cache->storeBanners($this->request,$banners);
        }
        
        $banners = $this->_applyCategoryFilter($banners);
        
        if(is_array($banners) && count($banners) > 0) {
            $bannersAfterRotation = $this->rotationService->rotateBanners($banners);
            $this->banner = array_pop($bannersAfterRotation);
            return $this->banner;
        }
    }
    
    public function getBannersForCurrentRequest() {
        /*
         * Get banners from cache
         */ 
        if($this->caching) {
            $banners = $this->cache->getBanners($this->request);
        }
        /*
         * If not found in cache, fetch from db and store in cache
         */ 
        if(!$banners) {
            $banners = $this->categoryPageRepository->getBanners();
            $this->cache->storeBanners($this->request,$banners);
        }
        $banners = $this->_applyCategoryFilter($banners);
        return $banners;
    }
    
    /*
     * If getInstitutes() is called multiple times,
     * do not re-compute, return the results stored in variable
     * However if this function is called multiple times with a different request object
     * (as in while refreshing cache of multiple pages)
     * set recompute to TRUE
     */ 
    public function getInstitutes()
    {
		/*Multilocation Page without sorting */
		$sortingCriteria = $this->request->getSortingCriteria();
		if($this->request->isMultilocationPage() && (empty($sortingCriteria) || $sortingCriteria == 'none')) {
			$startTime = microtime(true);
			$institutes = $this->categoryPageSolrRepository->getInstitutesForMultilocation();
		} else {
			$startTime = microtime(true);
			$institutes = $this->categoryPageSolrRepository->getInstitutes();
		}
		
		$startTime = microtime(true);
		$filters = $this->categoryPageSolrRepository->getFiltersValue();
		if(EN_LOG_FLAG) error_log("\narray( section => 'getFilterValues', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		$startTime = microtime(true);
		$this->_loadFilters($filters);
		if(EN_LOG_FLAG) error_log("\narray( section => '_loadFilters', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		if($this->request->isMultilocationPage() && (empty($sortingCriteria) || $sortingCriteria == 'none')){
			$this->locationWiseInstitutesRaw = $institutes;
			return $this->_processInstitutesForMultiLocation($institutes);
		} else {
			return $this->_processInstitutes($institutes);
		}
	}
     
	private function _processInstitutesForMultiLocation($institutes) {
	    $startTime = microtime(true);
	    /********************** Load the MultiLocationCategoryPageProcessor library *******************/
	    $CI  = &get_instance();
	    $CI->load->library('categoryList/MultiLocationCategoryPageProcessor');
	    $multiLocationCategoryPageProcessor = new MultiLocationCategoryPageProcessor();
	    /********************** get processed array (rotated, consolidated  paginated ??? but without data) *******************/
	    $sTime = microtime(true);
	    $processedInstitutes = $multiLocationCategoryPageProcessor->processLocationWiseData($institutes, $this->rotationService);
	    if(EN_LOG_FLAG) error_log("\narray( section => ':_processInstitutesForMultiLocation :processLocationWiseData', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
	    $this->multiLocationInstitutes = $processedInstitutes;
	    $this->numInstitutes 	=  count($processedInstitutes);//_p($this->categoryPageSolrRepository->getTotalNoOfResult());   
	    /************************************** apply pagination ****************************************/
	    $institutes = $this->_applyPagination($processedInstitutes);
	    $sTime = microtime(true);
	    /****************** $processedInstitutes needs to be subjected to data population *************/

        $this->instituteCourseList = $processedInstitutes;
	    $institutes = $this->categoryPageRepository->loadInstitutes($institutes);
	    if(EN_LOG_FLAG) error_log("\narray( section => ':_processInstitutesForMultiLocation :loadInstitutes', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
	    /*
	     * Set sticky and main institutes
	     */
	    // get get Processed Data For All Inventories so that institutes can be distinguished as sticky n main
	    $sTime = microtime(true);
	    $processedDataForAllInventories = $multiLocationCategoryPageProcessor->getProcessedDataForAllInventories();
	    if(EN_LOG_FLAG) error_log("\narray( section => ':_processInstitutesForMultiLocation :getProcessedDataForAllInventories', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
	    $stickyInstitutes = array_keys($processedDataForAllInventories['cs']);
	    $mainInstitutes   = array_keys($processedDataForAllInventories['main']);
	    foreach($institutes as $instituteId => $institute) {
		if(in_array($instituteId,$stickyInstitutes)) {
		    $institute->setSticky();
		}
		else if(in_array($instituteId,$mainInstitutes)) {
		    $institute->setMain();
		}
	    }
	    if(EN_LOG_FLAG) error_log("\narray( section => '_processInstitutesForMultiLocation', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
	    return $institutes;
	}
	
	private function _processInstitutes($institutes)
    {
		$startTime = microtime(true);
		$sTime = microtime(true);
		$stickyInstitutes 	= $this->categoryPageSolrRepository->getStickyListings();
        $mainInstitutes 	= $this->categoryPageSolrRepository->getMainListings();
		if(EN_LOG_FLAG) error_log("\narray( section => '_processInstitutes sponsoredlistings', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
       
	    $stickyInstituteIds = array();
        $sortingCriteria = $this->request->getSortingCriteria();
		$sTime = microtime(true);
		if(empty($sortingCriteria) || $sortingCriteria == 'none') {
            $institutes 			= $this->_applyRotationService($institutes);
            $stickyInstituteIds 	= array_keys($institutes['sticky']);
			$institutes 			= $this->_consolidateInstituteList($institutes);
		} else {
			$institutes 			= $institutes['institutes'];
		}
		if(EN_LOG_FLAG) error_log("\narray( section => '_processInstitutes applyrotation', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		$this->numInstitutes 	=  $this->categoryPageSolrRepository->getTotalNoOfResult();   
        $this->_bucketingRequired(); 
        if($this->apply_bucketing == FALSE) {
            $institutes = $this->_applyPagination($institutes);
        }
        $this->instituteCourseList = $institutes;
		$sTime = microtime(true);
        $institutes = $this->categoryPageRepository->loadInstitutes($institutes);
		if(EN_LOG_FLAG) error_log("\narray( section => '_processInstitutes loadInstitutes', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        /*
         * Set sticky and main institutes
         */
		$sTime = microtime(true);
        foreach($institutes as $instituteId => $institute) {
            if(in_array($instituteId,$stickyInstitutes)) {
                $institute->setSticky();
            }
            else if(in_array($instituteId,$mainInstitutes)) {
                $institute->setMain();
            }
        }
        if($this->apply_bucketing == TRUE) {
            $institutes = $this->bucketingService->createBucketsOnExams($institutes, $stickyInstituteIds);
            $institutes = $this->_applyPagination($institutes);
        }
        $institutes = $this->_getOnlyNonEmptyInstitutes($institutes);
		if(EN_LOG_FLAG) error_log("\narray( section => '_processInstitutes otherprocessing', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        
		$this->institutes = $institutes;
	
		return $institutes;
    }
    
    
    private function _groupInstituteCoursesByType($institutes = array()){
    	$list = array();
    	foreach($institutes as $instituteType => $instituteList){
    		$list[$instituteType] = array_keys($instituteList);
    	}
    	return $list;
    }
    
    
    private function _applyRotationService($institutes = array()){
        /*
        * Rotate sticky, main and paid
        */
        if(is_array($institutes['sticky']) && count($institutes['sticky']) > 1) {
            $institutes['sticky'] = $this->rotationService->rotateStickyInstitutes($institutes['sticky'],$this->getBanner());
        }
        if(is_array($institutes['main']) && count($institutes['main']) > 1) {
            $institutes['main'] = $this->rotationService->rotateMainInstitutes($institutes['main']);
        }
        if(is_array($institutes['paid']) && count($institutes['paid']) > 1) {
            $institutes['paid'] = $this->rotationService->rotatePaidInstitutes($institutes['paid']);
        }
        return $institutes;
    }
	
    private function _loadFilters($filtersValue, CategoryPageRequest $customRequest = NULL)
    {
        /*
         * Get filters to hide for this page
         */
        $requestInUse = $this->request;
        if(!empty($customRequest)){
            $requestInUse = $customRequest;
        }

	   $filters = $this->filterGeneratorService->setFilters($filtersValue);
    	$filters = $this->filterGeneratorService->getFilters();
	    $this->filters = $filters;

    }
    
    
    
    /*
     * Apply category filter
     * Data = Banners or Sticky Institutes or Main Institutes
     * On main category page, show only category data
     * On sub-category page, show only sub-category data
     * On LDB Course page, show category+subcategory data
     */
    private function _applyCategoryFilter($data)
    {
        if(isset($data['category']) || isset($data['subcategory']) || isset($data['country'])) {
            
            $categoryData = (array) $data['category'];
            $subcategoryData = (array) $data['subcategory'];
            $countryData = (array) $data['country'];
            
            if($this->request->isStudyAbroadPage()) {
                return $categoryData + $countryData;
            }
            if($this->request->isLDBCoursePage() || $this->request->isSubcategoryPage()) {
                return $categoryData + $subcategoryData + $countryData;
            }
            if($this->request->isMainCategoryPage()) {
                return $categoryData + $countryData;
            }
        }
        else {
            return $data;
        }
    }
    
    /*
     * If current page is an LDB course page, filter out courses
     * belonging to the LDB course
     */ 
    private function _applyLDBCourseFilter($institutes,$LDBCourseId)
    {
        foreach($institutes as $instituteId => $courses) {
            foreach($courses as $courseId => $course) {
                if(!in_array($LDBCourseId,$course['ldbCourses'])) {
                    unset($institutes[$instituteId][$courseId]);
                }
            }
            if(count($institutes[$instituteId]) == 0) {
                unset($institutes[$instituteId]);
            }
        }
        
        return $institutes;
    }
    
    /*
     * Get filter set selected by user on the current page
     */ 
    private function _getAppliedFilters()
    {
        $appliedFilters = $this->request->getAppliedFilters();
        foreach($appliedFilters as $filterValues) {
            if(count($filterValues) > 0) {
                return $appliedFilters;
            }
        }
    }
    
    /*
     * Apply user selected filter on institutes
     */ 
    private function _applyUserFilters($institutes)
    {
        if($appliedFilters = $this->_getAppliedFilters()) {
            $institutes = $this->filterProcessorService->processFilters($institutes,$appliedFilters);
        }
        return $institutes;
    }
    
    /*
     * Get institutes on the current page as per the paginations
     */ 
    private function _applyPagination($institutes)
    {
        $startIndex = 0;
	$resultsPerPage = $this->request->getSnippetsPerPage();    
        if($pageNumber = $this->request->getPageNumberForPagination()) {
            $startIndex = ($pageNumber-1) * $resultsPerPage;
        }
    
        return array_slice($institutes,$startIndex,$resultsPerPage,TRUE);
    }
    
    /*
     ********************************************************
	 * Fetchers for category page data
	 ********************************************************
	 */
    public function getTotalNumberOfInstitutes()
    {
        return $this->numInstitutes;
    }
    
    public function getFilters()
    {
       return $this->filters;
    }
    
	public function getCategory()
    {
        return $this->categoryPageRepository->getCategory();
    }
    
    public function getSubCategory()
    {
        return $this->categoryPageRepository->getSubCategory();
    }
    
    public function getLDBCourse()
    {
        return $this->categoryPageRepository->getLDBCourse();
    }
    
    public function getCity()
    {
        return $this->categoryPageRepository->getCity();
    }
    
    public function getCountry()
    {
        return $this->categoryPageRepository->getCountry();
    }
    
    public function getState()
    {
        return $this->categoryPageRepository->getState();
    }
    
    public function getLocality()
    {
        return $this->categoryPageRepository->getLocality();
    }
    
    public function getZone()
    {
        return $this->categoryPageRepository->getZone();
    }
    
    public function getRegion()
    {
        return $this->categoryPageRepository->getRegion();
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function getHeaderText()
    {
        return $this->categoryPageRepository->getHeaderText();
    }
    
    public function getDynamicLDBCoursesList($disableCache = false)
    {
       if(!$this->request->isMultilocationPage() ) {
	return $this->categoryPageRepository->getDynamicLDBCoursesList();
        } else {
        return $this->categoryPageSolrRepository->getDynamicLDBCoursesList();
       }
  }
    public function getDynamicCategoryList($disableCache = false)
    {
      if(!$this->request->isMultilocationPage() ) {
	 return $this->categoryPageRepository->getDynamicCategoryList();  
      }  else  {
       return $this->categoryPageSolrRepository->getDynamicCategoryList();
      } 
 }
    public function getDynamicLocationList($disableCache = false)
    {
        return $this->categoryPageSolrRepository->getDynamicLocationList($disableCache);
    }  
    
    
    public function disableCaching()
    {
        $this->caching = FALSE;
    }
    
    public function enableCaching()
    {
        $this->caching = TRUE;
    }
    
    public function setRequest(CategoryPageRequest $request)
    {
        $this->request = $request;
    }
    
    public function getRepository()
    {
        return $this->categoryPageRepository;
    }

    
    private function _bucketingRequired() {
        $applied_filters = $this->request->getAppliedFilters();
        $sorting_criteria = $this->request->getSortingCriteria();
        $course_exams = $applied_filters['courseexams'];
        // check if bucketing required or not
        if(empty($course_exams) || count($course_exams) == 0 || !empty($sorting_criteria)) {
            $this->apply_bucketing = FALSE;
        } else {
            $this->apply_bucketing = TRUE;
        }
    }
    
       
    private function _validateMarksType($examName,$examFromCourse)
    {	$validMarksTypeFlag = FALSE;
    	$SubCatId = $this->request->getSubCategoryId();
    	$MarksTypFromCourseExam = $examFromCourse->getMarksType();
    	if($SubCatId == 23) //For MBA Exams
    	{   
    		$DefaultMarksType = 'percentile';
    		$MBAExmasNotReqPrcntl = $GLOBALS['MBA_EXAMS_REQUIRED_SCORES'];
    		$MBANoOptionExam = $GLOBALS['MBA_NO_OPTION_EXAMS'];
    	    if(in_array($examName,$MBAExmasNotReqPrcntl))   //Exceptional Marks Type check
    	    {
    	    	$DefaultMarksType = 'total_marks';
    	    	if(!empty($MarksTypFromCourseExam) && $DefaultMarksType == $MarksTypFromCourseExam)
    	    	{
    	    		$validMarksTypeFlag = TRUE;
    	    	}
    	    }
    	    elseif(!empty($MarksTypFromCourseExam) && $DefaultMarksType == $MarksTypFromCourseExam)  //Default Check
    	    {
    	    	$validMarksTypeFlag = TRUE;
    	    }
    	    elseif(empty($MarksTypFromCourseExam) && !empty($MBANoOptionExam) && in_array($examName,$MBANoOptionExam))
    	    {
    	    	$validMarksTypeFlag = TRUE;
    	    }
     	}
    	elseif($SubCatId == 56) //For BE/Betch Exams
    	{
    		$DefaultMarksType = 'rank';
    		$EngExamsNotReqRank = $GLOBALS['ENGINEERING_EXAMS_REQUIRED_SCORES'];
    		if(in_array($examName,$EngExamsNotReqRank))   //Exceptional Marks Type check
    		{
    			$DefaultMarksType = 'total_marks';
    			if($DefaultMarksType == $MarksTypFromCourseExam)
    			{
    				$validMarksTypeFlag = TRUE;
    			}
    		}
    	 	elseif($DefaultMarksType == $MarksTypFromCourseExam)  //Default Check
    	    {
    	    	$validMarksTypeFlag = TRUE;
    	    }
       	}
    	else
    	{
    		$validMarksTypeFlag = TRUE;
    	}
    	
    	return $validMarksTypeFlag;
    }

    private function _getOnlyNonEmptyInstitutes($institutes = array()) {
    	$nonEmptyInstitutes = array();
    	if(!empty($institutes)){
    		foreach($institutes as $instituteId => $institute){
    			$courses = $institute->getCourses();
    			if(!empty($courses)){
    				$keys = array_keys($courses);
    				if(!empty($keys)){
    					$key    = $keys[0];
    					$course = $courses[$key];
    					if(!empty($course)){
    						$nonEmptyInstitutes[$instituteId] = $institute;
    					}
    				}
    			}
    		}
    	}
    	return $nonEmptyInstitutes;
    }
    
    /*
     * Merge institute sets (Sticky, Main, Paid, Free) so that there are no duplicates
    */
    private function _consolidateInstituteList($instituteList)
    {
    	$consolidatedList = array();
    	foreach($instituteList as $institutesInList) {
    		if(is_array($institutesInList)) {
    			$consolidatedList = $consolidatedList + array_diff_key($institutesInList,$consolidatedList);
    		}
    	}
    	return $consolidatedList;
    }
    
    function getSolrFacetValues()
    {
	return $this->categoryPageSolrRepository->getFiltersValue();
    }
    
    public function getPageHeadingTextForRNR($locationname,$pageTitleForFilters,$isSourceRegistration) {
      

    $this->CI = &get_instance();
    $this->CI->load->helper('category_page');
    
    $categoryPageRequest  = $this->request;
    $categoryPageSolr     = $this;
            
    return getPageHeadingTextForRNR($locationname,$pageTitleForFilters,$isSourceRegistration,$categoryPageRequest,$categoryPageSolr);


      // $headingText = '';
      // if($this->request->isMainCategoryPage()){
      //     $change = "Career Option"; 
      // } elseif($this->request->isSubcategoryPage()){
      //     $change = "Course";
      // } elseif($this->request->isLDBCoursePage()){
      //     $change = "Course"; 
      // }

      // $cityId = $this->request->getCityID();
      // $stateId = $this->request->getStateID();
      // if($cityId == 1 && $stateId == 1 && (!($isSourceRegistration and $this->request->getSubCategoryId() == 23))) {
      //     $headingText = $this->getTotalNumberOfInstitutes().($this->getSubCategory()->getName()=='All'?$this->getCategory()->getName():$this->getSubCategory()->getName()).(($this->getTotalNumberOfInstitutes() == 1)?" College":" Colleges")." Found";
      // }
      // else{
      //     $headingText = $pageTitleForFilters;
      // }
      
      // $localityName = $this->request->getLocalityName();
      // $cityName = $this->request->getCityName();
      // $stateName = $this->request->getStateName();
      // $countryName = $this->request->getCountryName();
      // $locationName1 = "";
      // $locationName2 = "";
      // if($localityName) {
      //     $locationName1 = $localityName.", ";
      // }
      // $locationName1 = ucfirst($locationName1);
      // if($cityId > 0) {
      //     $locationName2 = $cityName;
      //     if($cityId == 1 && $stateId > 0) {
      //         $locationName2 = $stateName;
      //         if($stateId == 1 && $countryName) {
      //             $locationName2 = $countryName;
      //         }
      //     }
      //     $locationName2 = ucfirst($locationName2);
      //     $locationname = $locationName1.$locationName2;
      // }
      // else {
      //     $locationname = "";
      // }

      // if($isSourceRegistration && $subcat_id_course_page == 23) {
      //     $locationname = '';
      // }
      // if(!empty($locationname) && !($cityId == 1 and $stateId == 1)) {
      //     $headingText .= "in " . $locationname;
      // }
      // return $headingText;
  }

  function getInstituteCourseList(){
    return $this->instituteCourseList;
  }
}
