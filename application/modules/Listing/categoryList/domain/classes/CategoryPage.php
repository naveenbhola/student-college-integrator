<?php

class CategoryPage
{
    private $request;
    private $categoryPageRepository;
    private $filterGeneratorService;
    private $filterProcessorService;
    private $rotationService;
    private $sorterService;
    private $cache;
    private $bucketingService;
    
    private $banner;
    private $numInstitutes;
    private $institutes;
    private $filters;
    private $filtersToHide = array();
    
    private $defaultSortingCriteria = array('sortBy' => 'viewCount','order' => 'DESC');
    const SORTING_REFRESH_INTERVAL = 6;
    private $caching = TRUE;
    private $apply_bucketing = FALSE;
    private $RNR_SUB_CATEGORY_IDS = array(23, 56);

    function __construct(CategoryPageRequest $request,
                         CategoryPageRepository $categoryPageRepository,
                         FilterGeneratorService $filterGeneratorService,
                         FilterProcessorService $filterProcessorService,
                         RotationService $rotationService,
                         SorterService $sorterService,
                         BucketingService $bucketingService,
                         $cache
                        )
    {
        $this->request = $request;
        $this->categoryPageRepository = $categoryPageRepository;
        $this->filterGeneratorService = $filterGeneratorService;
        $this->filterProcessorService = $filterProcessorService;
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
        //return FALSE;
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
    public function getInstitutes($recompute = FALSE, $rawDataFromCache = FALSE)
    {  
        if($this->institutes && !$recompute) {
            return $this->institutes;
        }
        $institutes = $this->_getInstitutesFromCache();
        if($this->caching && $institutes) {
            $institutes = $this->_refreshSorting($institutes);
            
            /* Check if this is a child page and also add this page key in the child-parent mapping if it doesn't exists
            * This check is to prevent the cases when child page exists in  the memcache but their mapping in the parent-child mapping
            * is absent.
            */
            if( $this->request->isChildPage() )
            {
                $parentReq = $this->categoryPageRepository->getParentPageRequest($this->request);
                $childKeys = $this->cache->getChildPageKeys($parentReq);
                //add this child page key in the parent-children list if it doesn't exists 
                if( !in_array($this->request->getPageKeyString(), $childKeys) )
                {
                    $this->cache->storeChildPageKeys($parentReq, $this->request);
                }
            }
            
        } else {
            if($this->request->isChildPage() && $this->isRNRSubCategoryPage()) {
                $parentPageRequest = $this->categoryPageRepository->getParentPageRequest($this->request);

                $institutes = $this->_getCategoryPageInstitutesFromDB($parentPageRequest);

                $institutes = $this->_prepareChildPage($institutes);
                $this->cache->storeInstitutes($this->request, $institutes);
                $this->cache->storeChildPageKeys($parentPageRequest, $this->request);
            } else {
                $institutes = $this->_getCategoryPageInstitutesFromDB();
                $this->cache->storeInstitutes($this->request, $institutes);
            }
        }
        if($rawDataFromCache){
            return $institutes;
        }
        return $this->_processInstitutes($institutes);
    }
    
    private function _getCategoryPageInstitutesFromDB(CategoryPageRequest $parentPageRequest = NULL) {
        /*
        * Fetch institutes for database
        */
        $requestInUse = $this->request;
        if(!empty($parentPageRequest)){
            $requestInUse = $parentPageRequest;
        }
        
        $institutes = $this->categoryPageRepository->getInstitutes($requestInUse);
        foreach($institutes as $instituteType => $institutesForType) {
            /*
             * Apply category filter
             */
            $institutes[$instituteType] = $this->_applyCategoryFilter($institutesForType);
            /*
            * Apply LDB Course filter
            */        
            if($this->request->isLDBCoursePage()) {
                $LDBCourseId = (int) $this->request->getLDBCourseId();
                $institutes[$instituteType] = $this->_applyLDBCourseFilter($institutes[$instituteType],$LDBCourseId);    
            }
            /*
             * Load institute/course data
             */
            $institutes[$instituteType] = $this->categoryPageRepository->loadInstitutes($institutes[$instituteType]);
        }
        /*
         * Generate filters based on data in institutes and courses
         */
        $institutes = $this->_loadFilters($institutes, $requestInUse);
        /*
         * Sort institutes by default sorting criteria set for category pages
        */ 
        foreach($institutes as $instituteType => $institutesForType) {
            $institutes[$instituteType] = $this->_serializeSortValues($this->_sortInstitutes($institutesForType,$this->defaultSortingCriteria));
        }
        return $institutes;
    }
    
    private function _prepareChildPage($institutes = array()) {
        if(empty($institutes)){
            return array();
        }
        foreach($institutes as $instituteType => $institutesForType) {
            $institutes[$instituteType] = $this->categoryPageRepository->loadInstitutes($institutes[$instituteType]);
        }
        $institutes = $this->_applyExtraFilters($this->request, $institutes);
        /*
         * Generate child page filters based on data in institutes and courses
        */
        $institutes = $this->_loadFilters($institutes);
        /*
         * Sort institutes by default sorting criteria set for category pages
        */ 
        foreach($institutes as $instituteType => $institutesForType) {
            $institutes[$instituteType] = $this->_serializeSortValues($this->_sortInstitutes($institutesForType, $this->defaultSortingCriteria));
        }
        return $institutes;
    }
    
    private function _getInstitutesFromCache(CategoryPageRequest $categoryPageRequest = NULL) {
        /*
         * If we have institutes in cache, process and return
         */
        $start = microtime(TRUE);
        if(!empty($categoryPageRequest)){
            $institutes = $this->cache->getInstitutes($categoryPageRequest);
        } else {
            $institutes = $this->cache->getInstitutes($this->request);
        }
        $end = microtime(TRUE);
        /*
         * Tracking
         * 1. If cache reads takes time
         * 2. Cache miss occurs
         */ 
        if($institutes) {
            $timeToReadFromCache = ceil(1000 * ($end-$start));
            if($timeToReadFromCache > 100) {
                $this->categoryPageRepository->raiseAlert('FirstLevelCacheTime',$timeToReadFromCache);
            }
        } else {
            $this->categoryPageRepository->raiseAlert('FirstLevelCacheMiss',$this->request->getPageKey());
        }
        return $institutes;
    }
    
    /*
     * Refresh sorting in cached institues after specific time intervals (e.g. 6 hours)
     * Useful in case of default sorting by view count
     * as view count keeps changing
     */ 
    private function _refreshSorting($institutes)
    {
        $lastRefreshTime = $this->cache->getCategoryPageInstitutesStoreTime($this->request);
        if($lastRefreshTime) {
            $timeElapsed = time() - $lastRefreshTime;
            if($timeElapsed > self::SORTING_REFRESH_INTERVAL * 3600 && $this->defaultSortingCriteria['sortBy'] == 'viewCount') {
                foreach($institutes as $instituteType => $institutesForType) {
                    $institutes[$instituteType] = $this->_serializeSortValues($this->_sortInstitutes($institutesForType,$this->defaultSortingCriteria,TRUE));
                }
                $this->cache->storeInstitutes($this->request,$institutes);
            }
        }
        return $institutes;
    }
    
    private function _processInstitutes($institutes)
    {
        $stickyInstitutes = array_keys($institutes['sticky']);
        $mainInstitutes = array_keys($institutes['main']);
        $stickyInstituteIds = array();
        /*
         * First apply user selected filters
         */
        foreach($institutes as $instituteType => $institutesForType) {
            $institutes[$instituteType] = $this->_applyUserFilters($institutesForType);
        }
        
        if($sortingCriteria = $this->request->getSortingCriteria()) {
            $instituteCoursesGroupByType = array();
            if($sortingCriteria['sortBy'] == 'examscore'){
                $institutes = $this->_applyRotationService($institutes);
                $stickyInstituteIds = array_keys($institutes['sticky']);
                $instituteCoursesGroupByType = $this->_groupInstituteCoursesByType($institutes);
            }
            $institutes = $this->_consolidateInstituteList($institutes);
            $institutes = $this->_sortInstitutes($institutes, $sortingCriteria, FALSE, $instituteCoursesGroupByType);
        } else {
            $institutes = $this->_applyRotationService($institutes);
            $stickyInstituteIds = array_keys($institutes['sticky']);
            $institutes = $this->_consolidateInstituteList($institutes);   
        }
        
        $this->numInstitutes = count($institutes);   
        $this->_bucketingRequired(); 
        if($this->apply_bucketing == FALSE) {
            $institutes = $this->_applyPagination($institutes);
        }
        $institutes = $this->categoryPageRepository->loadInstitutes($institutes);
        
        /*
         * Set sticky and main institutes
         */ 
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
        $this->institutes = $institutes;
        return $institutes;
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
    
    private function _groupInstituteCoursesByType($institutes = array()){
        $list = array();
        foreach($institutes as $instituteType => $instituteList){
            $list[$instituteType] = array_keys($instituteList);
        }
        return $list;
    }

    /*
     * An option sortWithFreshData can be passed
     * In that case, sorting will be done by fetching fresh values from the database
     * rathar thn possibly stale values in cache
     * Useful in cases such as sorting by ViewCount, which keeps changing
     */ 
    private function _sortInstitutes($institutes,$sortingCriteria,$sortWithFreshData = FALSE, $institutesGroupByType = array())
    {
        $institutes = $this->_unserializeSortValues($institutes);
        return $this->sorterService->sort($institutes, $sortingCriteria, $sortWithFreshData, $institutesGroupByType);
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
    
    private function _loadFilters($institutes, CategoryPageRequest $customRequest = NULL)
    {
        /*
         * Get filters to hide for this page
         */
        $requestInUse = $this->request;
        if(!empty($customRequest)){
            $requestInUse = $customRequest;
        }
        
        $filtersToHide = $this->_getFiltersToHide();
        $instituteIdsWithFilters = array();
        foreach($institutes as $instituteType => $institutesForType) {
            $data = array();
            foreach($institutesForType as $instituteId => $institute) {
                $data[$instituteId] = array();
                $courses = $institute->getCourses();
                foreach($courses as $course) {
            
                    $courseId = $course->getId();
                    $course->setCurrentLocations($requestInUse);
                   
                    /*check for Marks Type :Starts*/
                    
                    $exams = $course->getEligibilityExams();
                    foreach($exams as $exam){
                    	$examName = $exam->getAcronym();
                    	$validMarksTypeFlag = $this->_validateMarksType($examName,$exam);
                    	if(!$validMarksTypeFlag)
                    	{ 
	                    	$course->removeEligibilityExams($exam);
                    	}
                    }
                     /*check for Marks Type :Ends*/
            
                    $filterValues = $this->filterGeneratorService->generate($institute,$course);
                    
                    /*
                     * Check if course has any filter value which matches in filters to hide
                     * If yes, exclude this course
                     * e.g. If filters to hide for mode are 'Part Time','Correspondence'
                     * and course's mode if 'Part Time', exclude the course
                     */ 
                    if($this->_courseHasMatchingFiltersToHide($filterValues,$filtersToHide)) {
                        continue;
                    }
                    if(in_array($this->request->getSubCategoryId(), $this->RNR_SUB_CATEGORY_IDS)) {
                        $lastModifiedDateOfCourse = $course->getLastUpdatedDate();
                        $appliedFilters = $this->request->getAppliedFilters();
                        if(array_key_exists('lastmodifieddate', $appliedFilters)){
                            $lastModifiedDateFilterValue = $appliedFilters['lastmodifieddate'];
                            if(!empty($lastModifiedDateFilterValue)){
                                if(strtotime($lastModifiedDateOfCourse) < strtotime($lastModifiedDateFilterValue)){
                                    continue;
                                }
                            }
                        }
                    }
                    $this->filterGeneratorService->addValues($institute,$course);
                    $filterValues = serialize($filterValues);    
                    $sortValues = serialize($this->sorterService->getSortValues($institute,$course));
                    $data[$instituteId][$courseId] = array('filterValues' => $filterValues,'sortValues' => $sortValues);
                }
                if(count($data[$instituteId]) == 0) {
                    unset($data[$instituteId]);
                }
            }
            $institutesWithFilters[$instituteType] = $data;
        }
        $filters = $this->filterGeneratorService->getFilters();
        $this->cache->storeFilters($requestInUse, $filters);
        $this->filters = $filters;
        return $institutesWithFilters;
    }
    
    /*
     * Get filters to hide for a set of LDB Courses
     * If more than one LDB Course supplied, merge the values (Union)
     */ 
    private function _getFiltersToHide()
    {
        $filtersToHide = array();
        if($this->request->isLDBCoursePage()) {
            $filtersToHide = $this->categoryPageRepository->getFiltersToHide('ldbcourse',$this->request->getLDBCourseId());
        }
        else if($this->request->isSubCategoryPage()) {
            $filtersToHide = $this->categoryPageRepository->getFiltersToHide('subcategory',$this->request->getSubCategoryId());
        }
        
        foreach($filtersToHide as $filterKey => $values) {
            if($values) {
                $filtersToHide[$filterKey] = array_map('trim',explode(',',$values));
            }
        }
        return $filtersToHide;
    }
    
    /*
     * Check if course has a filter value which matches in filters to hide
     */ 
    private function _courseHasMatchingFiltersToHide($filterValues,$filtersToHide)
    {
        foreach($filtersToHide as $filterKey => $valuesToHide) {
            $valuesForCourse = $filterValues[constant('CP_FILTER_'.strtoupper($filterKey))];                
            if(in_array($valuesForCourse,$valuesToHide)) {
                return TRUE;
            }
        }
    }

    /*
     * Serialize/Unserialize sort values of the display course of each institute
     * Display course is the first course
     * Unserializing applied just before sorting
     * Serializing applied before storing in cache
     */ 
    private function _serializeSortValues($institutes)
    {
        foreach($institutes as $instituteId => $courses) {
            list($courseId,$courseData) = each($courses);
            $institutes[$instituteId][$courseId]['sortValues'] = serialize($courseData['sortValues']);
        }
        return $institutes;
    }
    
    private function _unserializeSortValues($institutes)
    {
        foreach($institutes as $instituteId => $courses) {
            list($courseId,$courseData) = each($courses);
            $institutes[$instituteId][$courseId]['sortValues'] = unserialize($courseData['sortValues']);
        }
        return $institutes;
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
        if($this->filters) {
            return $this->filters;
        }
        if($filtersInCache = $this->cache->getFilters($this->request)) {
            $this->filters = $filtersInCache;
            return $filtersInCache;
        }
        else {
            $this->disableCaching();
            $this->getInstitutes(TRUE);
            $filtersInCache = $this->cache->getFilters($this->request);
            $this->filters = $filtersInCache;
            $this->enableCaching();
            return $filtersInCache;
        }
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
        return $this->categoryPageRepository->getDynamicLDBCoursesList($disableCache);
    }
    public function getDynamicCategoryList($disableCache = false)
    {
        return $this->categoryPageRepository->getDynamicCategoryList($disableCache);
    }
    public function getDynamicLocationList($disableCache = false)
    {
        return $this->categoryPageRepository->getDynamicLocationList($disableCache);
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
    
    private function getRNRSubCategoryIds() {
        return $this->RNR_SUB_CATEGORY_IDS;
    }
    
    private function isRNRSubCategoryPage() {
        if(in_array($this->request->getSubCategoryId(), $this->getRNRSubCategoryIds())){
            return TRUE;
        }
        return FALSE;
    }
    
    private function getRNRExtraPageLevelFilters(){
        return array("exam", "fees", "affiliation");
    }
    
    private function _applyExtraFilters(CategoryPageRequest $request, $institutes = array()) {
        if(empty($request) || empty($institutes)){
            return $institutes;
        }
        $subCategoryId          = $request->getSubCategoryId();
        $RNR_SUB_CATEGORY_IDS   = $this->getRNRSubCategoryIds();
        $examName               = $request->getExamName();
        $feesValue              = $request->getFeesValue();
        $affiliationValue 	= $request->getAffiliationName();
        $localityId             = $request->getLocalityId();
        
        if(!in_array($subCategoryId, $RNR_SUB_CATEGORY_IDS)){
            return $institutes;
        }
        
        $updatedInstitutesByType = array();
        foreach($institutes as $instituteType => $institutesForType) {
            $updatedInstitutes = array();
            foreach($institutesForType as $instituteId => $institute) {
                $data[$instituteId] = array();
                $courses            = $institute->getCourses();
                $filteredCourses    = array();
                foreach($courses as $course) {
                    $courseId           = $course->getId();
                    $clonedRequest      = clone $request;
                    $course->setCurrentLocations($clonedRequest);
                    
                    $examExistFlag  = TRUE;
                    if(!empty($examName)){
                        $examExistFlag = $this->isExamExist($examName, $course);
                    }
                    $feesExistFlag = TRUE;
                    if(!empty($feesValue) && $feesValue != -1) {
                        $feesExistFlag = $this->isFeesExist($feesValue, $course);
                    }
                    $affiliationFlag = TRUE;
                    if(!empty($affiliationValue)) {
                        $affiliationFlag = $this->isAffiliationExist($affiliationValue, $course);
                    }
                    $localityFlag = TRUE;
                    if(!empty($localityId)) {
                        $localityFlag = $this->isLocalityExist($localityId, $course);
                    }
                    
                    $validCourse = FALSE;
                    if($examExistFlag && $feesExistFlag && $affiliationFlag && $localityFlag) {
                        $validCourse = TRUE;
                    }
                    if($validCourse){
                        $filteredCourses[] = $course;
                    } 
                }
                if(!empty($filteredCourses)){
                    $institute->setCourses($filteredCourses);
                    $updatedInstitutes[$instituteId] = $institute;
                }
            }
            $updatedInstitutesByType[$instituteType] = $updatedInstitutes;
        }
        return $updatedInstitutesByType;
    }
    
    private function isExamExist($examName, Course $course) {
        $flag = FALSE;
        if(empty($course)){
            return $flag;
        }
        $courseId = $course->getId();
        if(empty($courseId)){
            return $flag;
        }
        $exams = $course->getEligibilityExams();
        foreach($exams as $exam){
            $examAcronym = $exam->getAcronym();
            if($examAcronym == $examName){
				$validMarksTypeFlag = $this->_validateMarksType($examName,$exam);
            	if($validMarksTypeFlag)
            	{
               		$flag = TRUE;
               		break;
            	}
            }
        }
        return $flag;
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
    
 
    private function isFeesExist($feesValue, Course $course) {
        $flag = FALSE;
        if( empty($course) ) {
            return $flag;
        }
        $courseId = $course->getId();
        if(empty($courseId)) {
           return $flag;
        }
        
        $defaultLocation = NULL;
        $courseLocations    = $course->getCurrentLocations();
        $requestLocalityId  = $this->request->getLocalityId();
        $requestCityId      = $this->request->getCityId();
        $requestStateId     = $this->request->getStateId();
        
        if(!empty($requestLocalityId)) {
            foreach($courseLocations as $location){
                if($location->getLocality()->getId() == $requestLocalityId) {
                    $defaultLocation = $location;
                    break;
                }
            }    
        }
		
        if(empty($defaultLocation)){
            if(!empty($requestCityId)) {
                foreach($courseLocations as $location){
                    if($location->getCity()->getId() == $requestCityId) {
                        $defaultLocation = $location;
                        break;
                    }
                }    
            }
        }
        
        if(empty($defaultLocation)){
            if(!empty($requestStateId)) {
                foreach($courseLocations as $location){
                    if($location->getState()->getId() == $requestStateId) {
                        $defaultLocation = $location;
                        break;
                    }
                }
            }
        }
        
        if(empty($defaultLocation)){
            $displayLocation = $course->getMainLocation();
        } else {
            $displayLocation = $defaultLocation;
        }
        
        $displayLocationId = $displayLocation->getLocationId();
        $fees = $course->getFees($displayLocationId);
        $feeVal = $fees->getValue();
        if(!empty($feeVal)){
            if($feeVal <= $feesValue) {
                $flag = TRUE;
            }
        }
        return $flag;
    }
    
    private function isAffiliationExist($affiliationValue, Course $course) {
        $flag = FALSE;
        if( empty($course) ) {
            return $flag;
        }
        $courseId = $course->getId();
        if(empty($courseId)) {
           return $flag;
        }
        $approvals = $course->getApprovals();
        if(in_array($affiliationValue, $approvals)){
            $flag = TRUE;
        }
        return $flag;
    }
    
    private function isLocalityExist($localityId, Course $course){
        $flag = FALSE;
        if( empty($course) ) {
            return $flag;
        }
        $courseId = $course->getId();
        if(empty($courseId)) {
           return $flag;
        }
        $locations = $course->getLocations();
        foreach($locations as $locationId => $location){
            $locId = $location->getLocality()->getId();
            if($locId == $localityId){
                $flag = TRUE;
                break;
            }
        }
        return $flag;
    }
    
    /**
     * Function to fetch  the number of institutes for a particular category page
     **/
    public function fetchInstitutesWithoutOrder()
    {
        $this->getInstitutes();
        //$start = microtime(true);
        $institutes = $this->_getInstitutesFromCache();
        
        if($this->caching && $institutes)
        {
                //_p("From Cache ".$paramRequest->getURL()."  ".$paramRequest->getPageKey());
        }
        else
        {
                if($this->request->isChildPage()
                   && in_array($this->request->getSubCategoryId(), $this->getRNRSubCategoryIds()))
                {
                    $parentPageRequest = $this->categoryPageRepository->getParentPageRequest($this->request);
                    $institutes = $this->_getInstitutesFromCache($parentPageRequest);
                    if(!$this->caching || empty($institutes))
                    {
                        //_p("From Db ".$paramRequest->getURL()."  ".$paramRequest->getPageKey());
                        $institutes = $this->_getCategoryPageInstitutesFromDB($parentPageRequest);
                    }
                }
                else
                {
                    $institutes = $this->_getCategoryPageInstitutesFromDB();
                }
        }
        
        foreach($institutes as $instituteType => $institutesForType) {
            $institutes[$instituteType] = $this->_applyUserFilters($institutesForType);
        }
        
        $institutes = $this->_consolidateInstituteList($institutes);
        
        return $institutes;
    }
}
