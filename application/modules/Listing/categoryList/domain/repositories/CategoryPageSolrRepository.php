<?php

class CategoryPageSolrRepository extends EntityRepository
{
    private $request;
    private $instituteRepository;
    private $bannerRepository;
    private $LDBCourseRepository;
    private $locationRepository;
    private $categoryRepository;
    private $filtersValue;
    private $totalNoOfResult;
    private $instituteWithType;
    private $categoryPageSponsoredRepo;
    private $stickyInstitutes = array();
    private $mainInstitutes = array();
	private $rawSOLRInstituteData = array();
	
    function __construct($dao,$cache = NULL,
                        CategoryPageRequest $request,
                        InstituteRepository $instituteRepository,
                        BannerRepository $bannerRepository,
                        LDBCourseRepository $LDBCourseRepository,
                        LocationRepository $locationRepository,
                        CategoryRepository $categoryRepository,
						CategoryPageSponsoredRepository $categoryPageSponsoredRepo
						)
    {
        parent::__construct($dao,$cache);
        
        $this->request = $request;
        $this->instituteRepository = $instituteRepository;
        $this->bannerRepository = $bannerRepository;
        $this->LDBCourseRepository = $LDBCourseRepository;
        $this->locationRepository = $locationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->categoryPageSponsoredRepo = $categoryPageSponsoredRepo;
		$this->CI->config->load('categoryPageConfig');
		$this->CI->load->library('categoryList/solr/CategoryPageSolrMultiLocationLib');
		$this->CI->load->library('categoryList/CategoryPageRelatedLib');
	}
    
     /*
     * Institutes (with supplementary data like courses/LDB courses)
     * Data consists of sticky+main+paid+free institutes
     */
    
    public function getInstitutes()
    {
		$startTime = microtime(true);
		$institutesData = $this->getCategoryPageInstituteDataFromSolr();
		if(array_key_exists('institutesWithSortValues', $institutesData)) {
			$sTime = microtime(true);
			$sortingCriteria 	= $this->request->getSortingCriteria();
			$institutesData 	= $this->applySorting($institutesData, $sortingCriteria);
			$this->_formatResultOnSortedData($institutesData);
			if(EN_LOG_FLAG) error_log("\narray( section => 'IN getInstitutes sorting and format', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		} else {
            $sTime = microtime(true);
			$this->_applyInventoryBucketing($institutesData['institutes']);
            if(EN_LOG_FLAG) error_log("\narray( section => 'IN getInstitutes _applyInventoryBucketing', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		}
		if(EN_LOG_FLAG) error_log("\narray( section => 'getInstitutes END', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		return $this->instituteWithType;
	}

	public function getInstitutesForMultilocation($userPreferredLocationOrder = array()) {
		return $this->getInstitutesForUserPreferredLocation($this->request, $userPreferredLocationOrder);
	}
	
	public function applySorting($institutesData, $sortingCriteria) {
		$sortedInstitutes = array();
		$sortedInstituteData = array();
		$institutesWithSortValue = array();
		$institutesWithNoSortValue = array();
		
		$sortOrder = $sortingCriteria['params']['order'];
		
		//LF-4387: Removing sort by view count
		/*
		if($sortingCriteria['sortBy'] == 'viewCount') {
			$institutesData['institutesWithSortValues'] = $this->instituteRepository->getInstitutesViewCount(array_keys($institutesData['institutesWithSortValues']));
		} */
		
		if($sortingCriteria['sortBy'] == 'topInstitutes') {
			$sortOrder  = 'ASC';
			$categoryId = $sortingCriteria['params']['category'];
			
			//prepare data for ranking sort
			$data = array();
			$data['subcatId'] = $this->request->getSubCategoryId();
			$data['specializationId'] = 0;
			if($this->request->isLDBCoursePage()) {
				$data['specializationId'] = $this->request->getLDBCourseId();
			}
			$data['instituteIds'] = array_keys($institutesData['institutesWithSortValues']);

			$categoryPageRelatedLib 	= $this->CI->categorypagerelatedlib;
			$topInstitutesOfThisCategory = $categoryPageRelatedLib->sortInstitutesOnRanking($data);
			//$topInstitutesOfThisCategory = $this->instituteRepository->getTopInstitutesInCategory($categoryId);
			foreach($topInstitutesOfThisCategory as $instituteId => $rank) {
				if(array_key_exists($instituteId, $institutesData['institutesWithSortValues'])) {
					$institutesData['institutesWithSortValues'][$instituteId] = $rank;
				}
			}
		}
		
		foreach($institutesData['institutesWithSortValues'] as $instituteId=>$sortValue) {
			if(empty($sortValue)) {
				$institutesWithNoSortValue[] = $instituteId;
			} else {
				$institutesWithSortValue[$instituteId] = $sortValue;
			}
		}
		
		if($sortOrder == 'ASC') {
			asort($institutesWithSortValue);
		} else {
			arsort($institutesWithSortValue);
		}
		
		foreach($institutesWithSortValue as $instituteId=>$sortValue) {
			$sortedInstituteData[$instituteId] = $institutesData['institutes'][$instituteId];
		}
		foreach($institutesWithNoSortValue as $instituteId) {
			$sortedInstituteData[$instituteId] = $institutesData['institutes'][$instituteId];
		}
		$institutesData['institutes'] = $sortedInstituteData;
		
		return $institutesData;
	}

	private function getCategoryPageInstituteDataFromSolr(){
            
		$institutesData = $this->getInstitutesDataFromSolr();
                //_p(gzcompress(serialize($institutesData)));die;
		$this->rawSOLRInstituteData 	        = $institutesData['institutes'];
		$this->filtersValue 			= $institutesData['filters'];
                $this->totalNoOfResult 			= $institutesData['totalResults'];
		return $institutesData;
	}
	
    public function getTotalNoOfResult() {
    	return  $this->totalNoOfResult;
    }
    
    public function getFiltersValue() {
    	return  $this->filtersValue;
    }
    
    private function _applyInventoryBucketing($institutes)
    { 
    	$instituteWithType['sticky'] = array();
    	$instituteWithType['main'] = array();
    	$instituteWithType['paid'] = array();
    	$instituteWithType['free'] = array();
        
		$sTime = microtime(true);
    	$this->stickyInstitutes = array_keys($this->categoryPageSponsoredRepo->getStickyListings($this->request));
        $this->mainInstitutes =   array_keys($this->categoryPageSponsoredRepo->getMainListings($this->request));
		if(EN_LOG_FLAG) error_log("\narray( section => 'IN _applyInventoryBucketing sponsored listings', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);

        $instituteType = 'free';
        foreach($institutes as $instituteId => $institueDetail) {
        	if(in_array($instituteId, $this->stickyInstitutes)) {
        	$instituteType = 'sticky';
        	} else if(in_array($instituteId, $this->mainInstitutes)) {
        	$instituteType = 'main';
        	} else {
        	$instituteType = 'free';
        	}
        	        	$courses = array();
        	foreach ($institueDetail as $courseId => $courseDetails) {  /** check if any course of institute is paid ***/
        																 /** if any course is found paid then change intitute type to paid **/	
        		 if( $instituteType == 'free' && in_array($courseDetails['course_pack_type'],array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID)))
        		 {
        		 	$instituteType = 'paid';
        		 } 
        		
        		 $courses[$courseId] = $courseDetails;
        	}
        	
        	$instituteWithType[$instituteType][$instituteId] = $courses;
        	
        }
        
        if(!empty($instituteWithType['free'])) {  // sorting of free institutes by view count
        	$sortingCriteriaForFreeInstitutes = array('sortBy' => 'viewCount', 'params' => array('order' => 'DESC'));
        	$institutesToSort['institutesWithSortValues']=$instituteWithType['free'];
        	$institutesToSort['institutes']=$instituteWithType['free'];
   	    	$sortedFreeInstitutes = $this->applySorting($institutesToSort, $sortingCriteriaForFreeInstitutes);
    	   	$instituteWithType['free'] =$sortedFreeInstitutes['institutes'];
        }
       $this->instituteWithType = $instituteWithType;  
    }
   
    private function _formatResultOnSortedData($institutes)
    {
		if($this->request->isMultilocationPage()) {
			$order 	= $this->request->getUserPreferredLocationOrder();
			$solrMultiLocationLib 	= $this->CI->categorypagesolrmultilocationlib;
			$requestListByLocation 	= $solrMultiLocationLib->getRequestObjListForUserLocation($this->request, $order);
			$stickyAndMainListingsByLocation = $solrMultiLocationLib->getStickyAndMainListingsForRequest($requestListByLocation);
			$stickyListings = array();
			$mainListings 	= array();
			foreach($stickyAndMainListingsByLocation as $sponsoredData){
				$stickyListings 	= array_merge($stickyListings, array_keys($sponsoredData['cs']));
				$mainListingsTemp 	= array_merge($mainListings, array_keys($sponsoredData['main']));
			}
			$stickyListings   = array_unique($stickyListings);
			$mainListingsTemp = array_unique($mainListingsTemp);
			foreach($mainListingsTemp as $mainId){
				if(!in_array($mainId, $stickyListings)){
					$mainListings[] = $mainId;
				}
			}
			$this->stickyInstitutes = $stickyListings;
			$this->mainInstitutes   = $mainListings;
		} else {
			$this->stickyInstitutes = array_keys($this->categoryPageSponsoredRepo->getStickyListings($this->request));
			$this->mainInstitutes =   array_keys($this->categoryPageSponsoredRepo->getMainListings($this->request));
		}
		foreach($institutes as $instituteId => $institueDetail) {
			$courses = array();
        	foreach ($institueDetail as $courseId => $courseDetails) {  /** check if any course of institute is paid ***/
				$courses[$courseId] = $courseDetails;
        	}
        	$institutes[$instituteId] = $courses;
        }
		$this->instituteWithType = $institutes;  
    }
    
    public function getStickyListings() {
    	return $this->stickyInstitutes;
    }
    
    public function getMainListings() {
    	return $this->mainInstitutes;
    }
	
	public function getInstitutesForUserPreferredLocation(CategoryPageRequest $request, $userPreferredLocation = NULL) {
                $startTime = microtime(true);
		if(empty($this->rawSOLRInstituteData)){
			$this->getCategoryPageInstituteDataFromSolr();
		}
                if(EN_LOG_FLAG) error_log("\narray( section => 'getInstitutesForUserPreferredLocation_getCategoryPageInstituteDataFromSolr', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		$data  = $this->rawSOLRInstituteData;
		$solrMultiLocationLib 			 = $this->CI->categorypagesolrmultilocationlib;
                $startTime = microtime(true);
		$order 							 = $request->getUserPreferredLocationOrder();
                if(EN_LOG_FLAG) error_log("\narray( section => 'getInstitutesForUserPreferredLocation_getUserPreferredLocationOrder', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
                $startTime = microtime(true);
		$requestListByLocation 			 = $solrMultiLocationLib->getRequestObjListForUserLocation($request, $order);
                if(EN_LOG_FLAG) error_log("\narray( section => 'getInstitutesForUserPreferredLocation_getRequestObjListForUserLocation', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
                $startTime = microtime(true);
		$stickyAndMainListingsByLocation = $solrMultiLocationLib->getStickyAndMainListingsForRequest($requestListByLocation);
		$validInstituteIds = array_keys($this->rawSOLRInstituteData);

		foreach($stickyAndMainListingsByLocation as $key => $stickyAndMainInstitute) {
		
			foreach($stickyAndMainInstitute['cs'] as $instituteIdSticky => $instDataSticky ) {
				if(!in_array($instituteIdSticky,$validInstituteIds)) {
					unset($stickyAndMainListingsByLocation[$key]['cs'][$instituteIdSticky]);
				} else {
					
					  $courseIds  = (array_keys($this->rawSOLRInstituteData[$instituteIdSticky]));
					  if(!empty($courseIds)) {
					  	$stickyAndMainListingsByLocation[$key]['cs'][$instituteIdSticky] = $courseIds;
					  } else {
					  	unset($stickyAndMainListingsByLocation[$key]['cs'][$instituteIdSticky]);
					  }
					
				}
			}
			foreach($stickyAndMainInstitute['main'] as $instituteIdMain => $instDataSticky ) {
				if(!in_array($instituteIdMain,$validInstituteIds)) {
					unset($stickyAndMainListingsByLocation[$key]['main'][$instituteIdMain]);
				} else {
					   
					 $courseIds  = (array_keys($this->rawSOLRInstituteData[$instituteIdMain]));
					 if(!empty($courseIds)) {
					 	$stickyAndMainListingsByLocation[$key]['main'][$instituteIdMain] = $courseIds;
					 } else {
					 	unset($stickyAndMainListingsByLocation[$key]['main'][$instituteIdMain]);
					 }
					
				}
			
			}
		}         

       if(EN_LOG_FLAG) error_log("\narray( section => 'getInstitutesForUserPreferredLocation_getStickyAndMainListingsForRequest', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
                $startTime = microtime(true);
		$paidAndFreeListingsByLocation   = $solrMultiLocationLib->getFreeAndPaidListingsByLocation($data, $order);
                if(EN_LOG_FLAG) error_log("\narray( section => 'getInstitutesForUserPreferredLocation_getFreeAndPaidListingsByLocation', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
                $startTime = microtime(true);
		$mergedResults 					 = $solrMultiLocationLib->mergeListings($stickyAndMainListingsByLocation, $paidAndFreeListingsByLocation);
                if(EN_LOG_FLAG) error_log("\narray( section => 'getInstitutesForUserPreferredLocation_mergeListings', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		return $mergedResults;
	}
	
	public function getDynamicLDBCoursesList() {
		$filters = $this->getFiltersValue();
		$ldbCourseIds = array_keys($filters['ldbCourseIds']);
		return $ldbCourseIds;
	}
	
	public function getDynamicCategoryList() {
		$filters = $this->getFiltersValue();
		$categoryIds = array_keys($filters['subcatIds']);
		if(!empty($categoryIds)) {
			$categoryObjects = $this->categoryRepository->findMultiple($categoryIds);
			foreach($categoryObjects as $categoryId=>$object) {
				if($object->getParentId() == 1){
					$parentCategoryIds[] = $categoryId;
				} elseif($object->getParentId() != 0) {
					$subCategoryIds[] = $categoryId;
				}
			}
		}
		return $subCategoryIds;
	}
	
	public function getDynamicLocationList($disableCache) {
		if($this->caching && ($locationList = $this->cache->getDynamicLocationList($this->request)) && !$disableCache && $locationList['cities']) {
		    return $locationList;
        }
		
		$locationList = array();
		$locationClusters = $this->dao->getInstitutesForAllCities($this->request);
		foreach($locationClusters['locations'] as $countryId=>$countryData) {
			$locationList['countries'][] = $countryId;
			foreach($countryData['states'] as $stateId=>$stateData) {
				$locationList['states'][] = $stateId;
				foreach($stateData['cities'] as $cityId=>$cityData) {
					$locationList['cities'][] = $cityId;
				}
			}
		}
		$locationList['cities'] = array_merge($locationList['cities'], $locationClusters['virtual_cities']);
		
		$this->cache->storeDynamicLocationListSolr($this->request, $locationList);
		
		return $locationList;
	}
	
	function getInstitutesDataFromSolr()
	{
	    $RNRSubcategories 		   = array_keys($this->CI->config->item('CP_SUB_CATEGORY_NAME_LIST'));
	    $numEmpty 			   = 0;
            $getDataWhenNoFilterIsApplied  = false;
	    
	    // do not serve the mba/be-btech pages from memcache
	    if(in_array($this->request->getSubCategoryId(), $RNRSubcategories))
	    {
		return $this->dao->getInstitutes($this->request);
	    }
            
            $appliedFilters = $this->request->getAppliedFilters();
            $sortValues  = $this->request->getSortingCriteria();
            foreach($appliedFilters as $val)
            {
                if(!empty($val))
                    $numEmpty++;
            }
            if($numEmpty == 0 || ($numEmpty == 1 && isset($appliedFilters['lastmodifieddate']))) {
                $getDataWhenNoFilterIsApplied = true;
            }
            
            if(!$this->request->isMultilocationPage())
            {
                if(!$this->request->isAjaxCall() && $getDataWhenNoFilterIsApplied && !isset($sortValues['sortBy']))
                {
                    $institutesData = $this->cache->getCategoryPageData($this->request);
                    if(!$institutesData || !(isset($institutesData['totalResults']) && $institutesData['totalResults'] > 0))
                    {
                        //_p("From Solr");
                        $institutesData = $this->dao->getInstitutes($this->request);
                        $this->cache->storeCategoryPageData($this->request, $institutesData);
                    }
                    else
                    {
                        //_p("From Cache");
                    }
                }
                else
                {
                    //_p("From Solr 1");
                    $institutesData = $this->dao->getInstitutes($this->request);
                }
            }
            else
            {
                //var_dump($this->request->isMultiLocationPageLoad());
                 if(!$this->request->isAjaxCall() && $this->request->isMultiLocationPageLoad()  && $getDataWhenNoFilterIsApplied && !isset($sortValues['sortBy']))
                {
                    $institutesData = $this->cache->getCategoryPageData($this->request);
                    if(!$institutesData)
                    {
                        //_p("From Solr ML");
                        $institutesData = $this->dao->getInstitutes($this->request);
                        $this->cache->storeCategoryPageData($this->request, $institutesData);
                    }
                    else
                    {
                        //_p("From Cache ML");
                    }
                }
                else
                {
                    //_p("From Solr  ML 1");
                    $institutesData = $this->dao->getInstitutes($this->request);
                }
            }
	    
	    return $institutesData;
	}
}
