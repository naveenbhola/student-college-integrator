<?php

class CacheUtilityLib
{
	private $CI;
    private $categoryPageBuilder;
	
    private $instituteRepository;
    private $courseRepository;
    private $bannerRepository;
    private $categoryPageRepository;
	private $locationRepository;
	
	private $listingCache;
	
	private $stats = array();
	private $cacheKeys = array();
	private $processedKeys = array();
	
	private $categoryPageCache;
	
	private $snapshotCourseRepository;
    
    function __construct()
    {
		$this->CI = & get_instance();
		
		$this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $this->instituteRepository = $listingBuilder->getInstituteRepository();
        $this->courseRepository = $listingBuilder->getCourseRepository();
        $this->bannerRepository = $listingBuilder->getBannerRepository();
		
		$this->abroadInstituteRepository = $listingBuilder->getAbroadInstituteRepository();
		$this->abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
		$this->universityRepository = $listingBuilder->getUniversityRepository();
		$this->snapshotCourseRepository = $listingBuilder->getSnapshotCourseRepository();
		
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$this->locationRepository = $locationBuilder->getLocationRepository();
		
		$this->CI->load->library('listing/ListingCache');
        $this->listingCache = new ListingCache;
		
		$this->CI->load->library('categoryList/cache/CategoryPageCache');
		$this->categoryPageCache = new CategoryPageCache;
		
		$this->CI->load->builder('CategoryPageBuilder','categoryList');
		$this->categoryPageBuilder = new CategoryPageBuilder;
		
		$this->categoryPageRepository = $this->categoryPageBuilder->getCategoryPageRepository();
    }
    
    public function refreshCacheInTimeWindow($timeWindow)
    {
        $this->_refreshCacheForModifiedInstitutes($timeWindow);
        $this->_refreshCacheForModifiedCourses($timeWindow);
        $this->_refreshCacheForBanners($timeWindow);
        $this->_refreshCacheForStickyInstitutes($timeWindow);
        $this->_refreshCacheForMainInstitutes($timeWindow);
		$this->stats['numCacheKeys'] = count(array_unique($this->cacheKeys));
		return $this->stats;
    }
    
    /*
     * Refresh cache for institutes modified in time window
     */ 
    private function _refreshCacheForModifiedInstitutes($timeWindow)
    {
		$modifiedInstitutes = $this->instituteRepository->getModifiedInstitutes(array('interval' => $timeWindow));
		$this->stats['institutes'] = $modifiedInstitutes;
        $this->refreshCacheForInstitutes($modifiedInstitutes);
    }
    
    /*
     * Refresh cache for institute
     */ 
    public function refreshCacheForInstitutes($instituteIds)
    {
        $this->instituteRepository->disableCaching();
        if(is_array($instituteIds) && count($instituteIds) > 0) {
            $this->instituteRepository->findMultiple($instituteIds);
            
            /*
             * Refresh cache for courses of the institute
             */ 
            $instituteCourses = $this->courseRepository->getCoursesByMultipleInstitutes($instituteIds);
			if(is_array($instituteCourses) && count($instituteCourses) > 0) {
				$courses = array();
				foreach($instituteCourses as $courseList) {
					$courses = array_merge($courses,$courseList);
				}
				$this->stats['instituteCourses'] = $courses;
				$this->refreshCacheForCourses($courses,TRUE);
				
				/*
				 * Update cache state of courses
				 */
				$this->categoryPageRepository->setCategoryPageDataInCacheMemory($courses);
			}
        }
    }
    
    /*
     * Refresh cache for courses modified in time window
     */
    private function _refreshCacheForModifiedCourses($timeWindow)
    {
		$modifiedCourses = $this->courseRepository->getModifiedCourses(array('interval' => $timeWindow));
		$this->stats['courses'] = $modifiedCourses;
        $this->refreshCacheForCourses($modifiedCourses);
    }
    
    /*
     * Refresh cache for courses
     */
    public function refreshCacheForCourses($courseIds,$fromInstitutes = FALSE)
    {
        $this->courseRepository->disableCaching();
        if(is_array($courseIds) && count($courseIds) > 0) {
            $this->courseRepository->findMultiple($courseIds);
            $cacheKeys = array();
            foreach($courseIds as $courseId) {
				
				$keys = (array) $this->_getCacheKeysForCourse($courseId);
				
				if($fromInstitutes) {
					$this->stats['keys']['instituteCourse'][$courseId] = $keys;
				}
				else {
					$this->stats['keys']['course'][$courseId] = $keys;
				}
					
                $cacheKeys = array_merge($cacheKeys,$keys);
            }
        
			$cacheKeys = array_values(array_unique($cacheKeys));
			$this->cacheKeys = array_merge($this->cacheKeys,$cacheKeys);
            foreach($cacheKeys as $key) {
                $this->refreshCacheForKey($key);
            }
            /*
             * Update cache state of courses
             */
            $this->categoryPageRepository->setCategoryPageDataInCacheMemory($courseIds);
        }
    }
    
    /*
     * Refresh cache for banners
     * Get expired (yesterday) + modified (in time window) banners
     */ 
    private function _refreshCacheForBanners($timeWindow)
    {
        $expiredBanners = (array) $this->bannerRepository->getExpiredBanners(1);
		$modifiedBanners = (array) $this->bannerRepository->getModifiedBanners(array('interval' => $timeWindow));
		$banners = array_merge($expiredBanners,$modifiedBanners);
		$this->stats['banners'] = $banners;
		
        if(count($banners) > 0) {
			
			$cacheKeys = array();		
			foreach($banners as $bannerId) {
				$params = $this->categoryPageRepository->getCategoryPageParameters('banner',$bannerId);
				$keys = (array) $this->_generateKeys($params);
				$this->stats['keys']['banner'][$bannerId] = $keys;
				$cacheKeys = array_merge($cacheKeys,$keys);
			}
			
            $cacheKeys = array_values(array_unique($cacheKeys));
			$this->cacheKeys = array_merge($this->cacheKeys,$cacheKeys);
            foreach($cacheKeys as $key) {
                $this->refreshCacheForKey($key,TRUE);
            }
            /*
             * Unpublish expired banners (set status to history)
             */ 
            $this->bannerRepository->unpublishExpiredBanners();
        }
    }

    /*
     * Refresh cache for sticky institutes
     * Get expired (yesterday) + modified (in time window) sticky institutes
     */ 
    private function _refreshCacheForStickyInstitutes($timeWindow)
    {
        $expiredStickyInstitutes = (array) $this->instituteRepository->getExpiredStickyInstitutes(1);
		$modifiedStickyInstitutes = (array) $this->instituteRepository->getModifiedStickyInstitutes(array('interval' => $timeWindow));
		$stickyInstitutes = array_merge($expiredStickyInstitutes,$modifiedStickyInstitutes);
	    $this->stats['sticky'] = $stickyInstitutes;
		
        if(count($stickyInstitutes) > 0) {
			
			$cacheKeys = array();
			foreach($stickyInstitutes as $stickyInstituteId) {
				$params = $this->categoryPageRepository->getCategoryPageParameters('sticky',$stickyInstituteId);
				$keys = (array) $this->_generateKeys($params);
				$this->stats['keys']['sticky'][$stickyInstituteId] = $keys;
				$cacheKeys = array_merge($cacheKeys,$keys);
			}
	
            $cacheKeys = array_values(array_unique($cacheKeys));
			$this->cacheKeys = array_merge($this->cacheKeys,$cacheKeys);
            foreach($cacheKeys as $key) {
                $this->refreshCacheForKey($key);
            }
            /*
             * Unpublish expired sticky institutes (set status to history)
             */ 
            $this->instituteRepository->unpublishExpiredStickyInstitutes();   
        }
    }
    
    /*
     * Refresh cache for main institutes
     * Get expired (yesterday) + modified (in time window) main institutes
     */ 
    private function _refreshCacheForMainInstitutes($timeWindow)
    {
        $expiredMainInstitutes = (array) $this->instituteRepository->getExpiredMainInstitutes(1);
		$modifiedMainInstitutes = (array) $this->instituteRepository->getModifiedMainInstitutes(array('interval' => $timeWindow));
		$mainInstitutes = array_merge($expiredMainInstitutes,$modifiedMainInstitutes);
		$this->stats['main'] = $mainInstitutes;
        
        if(count($mainInstitutes)) {
			
			$cacheKeys = array();
			foreach($mainInstitutes as $mainInstituteId) {
				$params = $this->categoryPageRepository->getCategoryPageParameters('main',$mainInstituteId);
				$keys = (array) $this->_generateKeys($params);
				$this->stats['keys']['main'][$mainInstituteId] = $keys;
				$cacheKeys = array_merge($cacheKeys,$keys);
			}
			
            $cacheKeys = array_values(array_unique($cacheKeys));
			$this->cacheKeys = array_merge($this->cacheKeys,$cacheKeys);
            foreach($cacheKeys as $key) {
                $this->refreshCacheForKey($key);
            }
            /*
             * Unpublish expired main institutes (set status to history)
             */ 
            $this->instituteRepository->unpublishExpiredMainInstitutes();   
        }
    }
    
    /*
     * Get cache keys for a course
     * Get category page parameters (category, subcategory, ldb course, city, state, country)
     * for live and cached states
     */ 
    private function _getCacheKeysForCourse($courseId)
	{
		$keys = array();
		/*
		 * Get params for course in live state
		 */
		$categoryPageParams = $this->categoryPageRepository->getCategoryPageParameters('course',$courseId,array('status' => 'live'));
		if(is_array($categoryPageParams) && count($categoryPageParams)) {
			$keys = $this->_generateKeys($categoryPageParams);
		}
		
		/*
		 * Get params for course in-cache-memory state
		 */
		$categoryPageParams = $this->categoryPageRepository->getCategoryPageParameters('course',$courseId,array('status' => 'history','cache_state' => 'in_memory'));
		if(is_array($categoryPageParams) && count($categoryPageParams)) {
			$keysInCachedState = $this->_generateKeys($categoryPageParams);
			
			/*
			 * Total keys to be updated is the union of keys in live state and keys in in-cache state
			 */ 
			$keys = array_values(array_unique(array_merge($keys,$keysInCachedState)));
		}
		return $keys;
	}
    
    /*
     * Refresh cache for a key
     * If banner, We only refresh banner cache
     * Otherwise refresh institute cache
     */
	/*
    public function refreshCacheForKey($key,$banner = FALSE)
	{ 
		error_log("MEMORYTESTING: ".$key." -- ".number_format(memory_get_usage(TRUE)));
		$keyIdentifier = $key.($banner?'-banner':'-institute');
		if(isset($this->processedKeys[$keyIdentifier])) {
			return FALSE;
		}
		
		$request = $this->categoryPageBuilder->getRequest();
		$this->_setDataInRequest($request,$key);
		$this->categoryPageBuilder->setRequest($request);
		$childKeys = $this->categoryPageCache->getChildPageKeys($request);
		if(empty($childKeys)){
			$childKeys = array();
		}
		array_unshift($childKeys, $key);
		error_log("REFRESH CACHE : parentkey: ". $key);
		error_log("REFRESH CACHE : childKeys: ". print_r($childKeys, true));
		foreach($childKeys as $childKey) {
			$request = NULL;
			$request = $this->categoryPageBuilder->getRequest();
			$this->_setDataInRequest($request, $childKey);
			$this->categoryPageBuilder->setRequest($request);

			$categoryPage = $this->categoryPageBuilder->getCategoryPage();
			$categoryPage->disableCaching();
			
			if($banner) { 
				$categoryPage->getBanner(TRUE);
			}
			else {
				$categoryPage->getInstitutes(TRUE);
				$categoryPage->getDynamicLDBCoursesList(TRUE);
				$categoryPage->getDynamicCategoryList(TRUE);
				$categoryPage->getDynamicLocationList(TRUE);
			}
		}
		$this->processedKeys[$keyIdentifier] = TRUE;
		error_log("MEMORYTESTING: ".$key." -- ".number_format(memory_get_usage(TRUE))." -- ".number_format(memory_get_peak_usage(TRUE)));
	}
	*/
    
	public function refreshCacheForKey($key,$banner = FALSE)
	{ 
		
		$debugLog = 0;
		if($debugLog)
		{
		error_log("\n ---------------------------------------------------",3,"/tmp/refreshCache.log");
		error_log("\n Refresh Start for KEY   :".$key,3,"/tmp/refreshCache.log");
		}
		$request = $this->categoryPageBuilder->getRequest();
		$parent_request = clone $request;
		$this->_setDataInRequest($request,$key);
		$this->categoryPageBuilder->setRequest($request);
		$childKeys = $this->categoryPageCache->getChildPageKeys($request);
		if($debugLog)
		{
		$k = print_r($childKeys,1);
		error_log("\n Child Key found  :".$k,3,"/tmp/refreshCache.log");
		}
		
		if(empty($childKeys)) {
			$childKeys = array();
		}
		$childKeys = array();
		array_unshift($childKeys, $key); //Combined Parent key with child keys
		//_p("KEYS"); _p($childKeys);
		foreach($childKeys as $childKey) {
			error_log("REFRESH CACHE MEMORYTESTING: ".$childKey." -- ".number_format(memory_get_usage(TRUE)));
			$keyIdentifier = $childKey.($banner?'-banner':'-institute');
			if(!isset($this->processedKeys[$keyIdentifier])) {
				
				if($debugLog)
				{
				error_log("\n ----------",3,"/tmp/refreshCache.log");
				error_log("\n Processing Child Page or Parent page   KEY :".$childKey,3,"/tmp/refreshCache.log");
				}					
				
				//$request =NULL;
				//$request = $this->categoryPageBuilder->getRequest();
			   	  $request = clone $parent_request;
				$this->_setDataInRequest($request, $childKey);
				$this->categoryPageBuilder->setRequest($request);
				if($debugLog)
				{
				error_log("\n Key requested for Pocessing  :".$this->categoryPageBuilder->getRequest()->getPageKey(),3,"/tmp/refreshCache.log");
				}
				$categoryPage = $this->categoryPageBuilder->getCategoryPage();
				$categoryPage->disableCaching();
				
				if($banner) {
					if($debugLog)
					{
					error_log("\n Refreshing cache for BANNERS  of KEY  :".$this->categoryPageBuilder->getRequest()->getPageKey(),3,"/tmp/refreshCache.log");
					}
					$categoryPage->getBanner(TRUE);
				} else {
					if($debugLog)
					{
						error_log("\n Refreshing cache for institute/DynamicLDBCoursesList/DynamicCategoryList/DynamicLocationList  of KEY  :".$this->categoryPageBuilder->getRequest()->getPageKey(),3,"/tmp/refreshCache.log");
					}
					$categoryPage->getInstitutes(TRUE);
					$categoryPage->getDynamicLDBCoursesList(TRUE);
					$categoryPage->getDynamicCategoryList(TRUE);
					$categoryPage->getDynamicLocationList(TRUE);
				}
				$this->processedKeys[$keyIdentifier] = TRUE;
				if($debugLog)
				{  
					error_log("\n REFRESHED KEY  :".$this->categoryPageBuilder->getRequest()->getPageKey(),3,"/tmp/refreshCache_keyREFRESHED.log");				
					error_log("\n Refresh Done for KEY :".$this->categoryPageBuilder->getRequest()->getPageKey(),3,"/tmp/refreshCache.log");
					error_log("\n ----------",3,"/tmp/refreshCache.log");
				}
			}
			error_log("MEMORYTESTING: ".$childKey." -- ".number_format(memory_get_usage(TRUE))." -- ".number_format(memory_get_peak_usage(TRUE)));
		}
		if($debugLog)
		{
         error_log("---------------------------------------------------",3,"/tmp/refreshCache.log");
		}
	}
	
    private function _setDataInRequest($request, $key)
	{
		$keyElements = explode('-',$key);
		$data = array('categoryId' 		=> $keyElements[0],
					  'subCategoryId' 	=> $keyElements[1],
					  'LDBCourseId' 	=> $keyElements[2],
					  'localityId' 		=> $keyElements[3],
					  'cityId' 			=> $keyElements[4],
					  'stateId' 		=> $keyElements[5],
					  'countryId' 		=> $keyElements[6],
					  'regionId' 		=> $keyElements[7],
					  'affiliation' 	=> $keyElements[8] == 'none' ? "" : $keyElements[8],
					  'examName' 		=> $keyElements[9] == 'none' ? "" : str_replace('_','-',$keyElements[9]),
					  'feesValue' 		=> $keyElements[10] == 'none' ? "" :$keyElements[10],
					);
		$RNRSubcategories = array_keys($this->CI->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		if(in_array($data['subCategoryId'], $RNRSubcategories)){
			$request->setNewURLFlag(1);
		}
		$request->setData($data);
	}
    
    /*
     * Generate category page cache keys from category pae parameters
     */ 
    public function _generateKeys($results)
	{
		$categories = array();
		foreach($results as $result) {
			$categories[$result['categoryId']][$result['subCategoryId']][] = $result['LDBCourseId'];
			$country = $result['countryId'];
			$state = $result['stateId'];
			$city = $result['cityId'];
			$virtualCity = $result['virtualCityId'];
			$region = $result['regionId'];
		}
		
		$locationParts = $this->_getLocationPartsOfKey($city,$virtualCity,$state,$country,$region);
		
		$keys = array();
		$RNRSubcategories = array_keys($this->CI->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$extraParts = array();
		$extraParts[] = "none-none-none";
		
		/*
		 * For study abroad, we have country and region pages (all categories)
		 */ 
		if($country > 2) {
			foreach($locationParts as $locationPart) {
				foreach($extraParts as $extraPart){
					$keys[] = '1-1-1-'.$locationPart."-". $extraPart;
				}
			}
		}
		
		foreach($categories as $categoryId => $subCategories) {
			
			foreach($locationParts as $locationPart) {
				$keys[] = $categoryId.'-1-1-'.$locationPart."-none-none-none";
			}
			
			foreach($subCategories as $subCategoryId => $LDBCourses) {
				
				foreach($locationParts as $locationPart) {
					foreach($extraParts as $extraPart){
						$keys[] = $categoryId.'-'.$subCategoryId.'-1-'.$locationPart. "-" . $extraPart;
					}
				}
				
				foreach($LDBCourses as $LDBCourse) {
					foreach($locationParts as $locationPart) {
						foreach($extraParts as $extraPart){
							$keys[] = $categoryId.'-'.$subCategoryId.'-'.$LDBCourse.'-'.$locationPart. "-" . $extraPart;
						}
					}
				}
			}	
		}
		return $keys;
	}
	
	private function _getLocationPartsOfKey($city,$virtualCity,$state,$country,$region)
	{
		$localityId = 0;
		if($state < 1) {
			$state = 1;
		}
		if($city < 1) {
			$city = 1;
		}
		
		$parts = array();
		
		if($region > 0) {
			
			//-- Region Page
			$parts[] = $localityId.'-'.'1-1-1-'.$region;
			
			//-- Country Page
			if($country > 2) {
				$parts[] = $localityId.'-'.'1-1-'.$country.'-'.$region;
			}
		}
		else if($country > 1) {
			$parts[] = $localityId.'-'.'1-1-'.$country.'-0';
			if($state > 1) {
				$parts[] = $localityId.'-'.'1-'.$state.'-'.$country.'-0';
			}
			if($city > 1) {
				if($state <= 1) {
					$cityObj = $this->locationRepository->findCity($city);
					$state = $cityObj->getStateId();
					if($state < 1) {
						$state = 1;
					}
				}
				$parts[] = $localityId.'-'.$city.'-'.$state.'-'.$country.'-0';
			}
			if($virtualCity > 1) {
				$parts[] = $localityId.'-'.$virtualCity.'-1-'.$country.'-0';
			}
		}
		return $parts;
	}
    
    /*
     * Build second-level (Institue + Course Data) from scratch
     */ 
    public function buildNationalSecondLevelCache()
	{
		set_time_limit(0);
		$instituteCacheKeys = array();
		$courseCacheKeys = array();
		
        /*
         * Get Ids of all live institutes [From categoryPageData]
         */ 
        $instituteIds = $this->instituteRepository->getLiveInstitutes();
		error_log("SECONDLEVELCACHE: Total no. of institutes: ".count($instituteIds));
		
		$this->instituteRepository->disableCaching();
	
        /*
         * Build institute cache in chuncks of 2500 institutes
         */ 
        $startTime = microtime(TRUE);
		$num = count($instituteIds);
		$done = 0;
		
        for($i=0;$i<$num;$i+=2500) {

			$chunkStartTime = microtime(TRUE);
			$chunk = array_slice($instituteIds,$i,2500);
			$this->instituteRepository->findMultiple($chunk);
			$chunkEndTime = microtime(TRUE);
			echo ($chunkEndTime-$chunkStartTime)."<br />";
			
			$done += count($chunk);
			error_log("SECONDLEVELCACHE: Institute Caching done for: ".$done." institutes");
		}
		
		$this->listingCache->storeInstituteCacheKeys($instituteIds);
		
        $endTime = microtime(TRUE);
        error_log('SECONDLEVELCACHE:institutetime'.($endTime-$startTime));
		echo $endTime-$startTime;
		
        /*
         * Get Ids of all live courses [From categoryPageData]
         */ 
		$courseIds = $this->courseRepository->getLiveCourses();
		error_log("SECONDLEVELCACHE: Total no. of courses: ".count($courseIds));
		
		$this->courseRepository->disableCaching();
		
        /*
         * Build course cache in chuncks of 5000 institutes
         */ 
        $startTime = microtime(TRUE);
		$num = count($courseIds);
		$done = 0;
		
		for($i=0;$i<$num;$i+=5000) {
			
			$chunkStartTime = microtime(TRUE);
			$chunk = array_slice($courseIds,$i,5000);
			$this->courseRepository->findMultiple($chunk);
			$chunkEndTime = microtime(TRUE);
			echo ($chunkEndTime-$chunkStartTime)."<br />";
			
			$done += count($chunk);
			error_log("SECONDLEVELCACHE: Course Caching done for: ".$done." courses");
		}
		
		$this->listingCache->storeCourseCacheKeys($courseIds);
		
        $endTime = microtime(TRUE);
        error_log('SECONDLEVELCACHE:coursetime'.($endTime-$startTime));
		echo $endTime-$startTime;
	}


	public function buildAbroadUniversitySecondLevelCache(){
		set_time_limit(0);
		error_log("SECONDLEVELCACHE: Starting university caching of abroad Entities at ".date("h:i:sa"));

		// Get Ids of all live universities [From abroadCategoryPageData]
		$universityIds = $this->universityRepository->getLiveAbroadUniversities();
		error_log("SECONDLEVELCACHE: Total no. of abroad universities: ".count($universityIds));
		$this->universityRepository->disableCaching();
		
		// Build universities cache in chunks of 2500 universities
		$startTime 	= microtime(TRUE);
		$num 		= count($universityIds);
		$done 		= 0;
			
		for( $i=0; $i<$num; $i += 2500 )
		{
			$chunkStartTime = microtime(TRUE);
			$chunk 		= array_slice($universityIds,$i,2500);
			
			$this->universityRepository->findMultiple($chunk);
			$chunkEndTime 	= microtime(TRUE);
			
			echo ($chunkEndTime-$chunkStartTime)."<br />";
			$done 		+= count($chunk);
			error_log("SECONDLEVELCACHE: University Caching done for: ".$done." abroad Universites");
		}
	}

	public function buildAbroadCourseSecondLevelCache(){
		set_time_limit(0);
		error_log("SECONDLEVELCACHE: Starting course caching of abroad Entities at ".date("h:i:sa"));

		// Get Ids of all live courses [From abroadCategoryPageData]
		$courseIds = $this->abroadCourseRepository->getLiveAbroadCourses();
		error_log("SECONDLEVELCACHE: Total no. of abroad courses: ".count($courseIds).", started at ".date("h:i:sa"));
		
		$this->abroadCourseRepository->disableCaching();
			
		// Build course cache in chuncks of 5000 institutes
		$startTime 	= microtime(TRUE);
		$num 		= count($courseIds);
		$done 		= 0;
			
		for( $i=0; $i<$num; $i += 5000 )
		{
			$chunkStartTime 	= microtime(TRUE);
			$chunk 			= array_slice($courseIds,$i,5000);
			
			$this->abroadCourseRepository->findMultiple($chunk);
			$chunkEndTime 		= microtime(TRUE);
			echo ($chunkEndTime-$chunkStartTime)."<br />";
			
			$done 			+= count($chunk);
			error_log("SECONDLEVELCACHE: Course Caching done for: ".$done." abroad courses");
		}

		$endTime = microtime(TRUE);
		error_log('SECONDLEVELCACHE:Abroad course time'.($endTime-$startTime).", completed at ".date("h:i:sa"));
		echo $endTime-$startTime;
	}
	
	
	/*
	* Purpose : Build second-level (Course + Institue + University) for abroad from scratch
	* Params  : none
	* Author  : Romil Goel
	*/ 
	public function buildAbroadSecondLevelCache()
	{
		set_time_limit(0);
		error_log("SECONDLEVELCACHE: Starting caching of abroad Entities at ".date("h:i:sa"));
		
		// initialize cache keys arrays
		$universityCacheKeys 	= array();
		$instituteCacheKeys 	= array();
		$courseCacheKeys 	= array();

		/************************ Start : University Caching Section *************/
		$this->buildAbroadUniversitySecondLevelCache();
		
		/************************ End : University Caching Section *************/

		/************************ Start : Institute Caching Section *************/
		// Get Ids of all live institutes [From abroadCategoryPageData]
		$instituteIds = $this->abroadInstituteRepository->getLiveAbroadInstitutes();
		error_log("SECONDLEVELCACHE: Total no. of abroad institutes: ".count($instituteIds).", started at ".date("h:i:sa"));
		$this->abroadInstituteRepository->disableCaching();
		
		// Build institute cache in chuncks of 2500 institutes
		$startTime 	= microtime(TRUE);
		$num 		= count($instituteIds);
		$done 		= 0;
			
		for( $i=0; $i<$num; $i += 2500 )
		{
			$chunkStartTime = microtime(TRUE);
			$chunk 		= array_slice($instituteIds,$i,2500);
			
			$this->abroadInstituteRepository->findMultiple($chunk);
			$chunkEndTime 	= microtime(TRUE);
			
			echo ($chunkEndTime-$chunkStartTime)."<br />";
			$done 		+= count($chunk);
			error_log("SECONDLEVELCACHE: Institute Caching done for: ".$done." abroad institutes");
		}

		$endTime = microtime(TRUE);
		error_log('SECONDLEVELCACHE:Abroad institute time'.($endTime-$startTime).", completed at ".date("h:i:sa"));
		echo $endTime-$startTime;
			
		/************************ End : Institute Caching Section *************/
			
		/************************ Start : Course Caching Section *************/
		$this->buildAbroadCourseSecondLevelCache();
		/************************ End : Course Caching Section *************/
		
		//build scholarship object cache
                $this->buildScholarshipCache();
	}
	
	public function buildCategoryPageFilterCache(){
		Modules::run('categoryList/AbroadCategoryPageCron/saveFiltersToCacheForAllCategoryPages');
	}
	public function buildCountryWiseAveragesCache(){
		Modules::run('listing/AbroadListingCrons/createCountryWiseAverages');
	}
        public function buildScholarshipCache(){
                Modules::run('scholarshipsDetailPage/scholarshipCrons/buildScholarshipCache');
        }

        public function buildAbroadCaches()
	{
		// build second level cache for abroad
		$this->buildAbroadSecondLevelCache();
		
		//build category page filter cache for homepage
		$this->buildCategoryPageFilterCache();

		//build country wise averages: fee, exam score, living expense
		$this->buildCountryWiseAveragesCache();
	}
}