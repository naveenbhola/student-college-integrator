<?php

class CategoryPageCache extends Cache
{
    private $cacheExpirationTime = 604800; // 1 Week Cache expiration for First Level Cache.

    function __construct()
	{
		parent::__construct();
	}
    
	/*
	 * Category Page Banners
	 */
	function getBanners(CategoryPageRequest $request)
    {
		return $this->get('CategoryPageBanners',$request->getPageKey());
    }
	
	function storeBanners(CategoryPageRequest $request,$banners)
	{		            
            $this->store('CategoryPageBanners',$request->getPageKey(),$banners,1800,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}

	/*
	 * Category Page Institutes
	 */ 
	function getInstitutes(CategoryPageRequest $request)
	{
		//_p(unserialize(gzuncompress($this->get('CategoryPageInstitutes', $request->getPageKey())))); exit();
		
		 /*
         * First get sponsered institutes (sticky and main institutes)
         */
		//_p("retrieving institutes for category page key: ". $request->getPageKey());
        $institutes = unserialize(gzuncompress($this->get('CategoryPageInstitutes', $request->getPageKey())));
		
//        /*
//         * Now get paid and free in chunks
//         */ 
//        foreach(array('paid','free') as $instituteType) {
//            $institutesForType = array();
//            $chunkId = 1;
//			$ignoreDirtyChunksLimit = 3;
//			$tempCount = 0;
//            while(true) {
//				$chunk = unserialize(gzuncompress($this->get('CategoryPageInstitutes-'.$instituteType.'-'.$chunkId,$request->getPageKey())));
//				if(is_array($chunk) && count($chunk) > 0) {
//					$institutesForType = $institutesForType + array_diff_key($chunk,$institutesForType);
//					$chunkId++;
//				}
//				else {
//					$chunkId++;
//					$tempCount++;
//					if($tempCount >= $ignoreDirtyChunksLimit){
//						break;
//					}
//				}
//                
//                /*
//                 * Infinite-loop safe condition
//                 */ 
//                if($chunkId > 100) {
//                    break;
//                }
//            }
//            if(count($institutesForType) > 0) {
//                $institutes[$instituteType] = $institutesForType;
//            }
//        }
        return $institutes;
	}
	
	function storeInstitutes(CategoryPageRequest $request,$institutes)
	{
		$this->store('CategoryPageInstitutes',$request->getPageKey(),gzcompress(serialize($institutes)),$this->cacheExpirationTime,CACHEPRODUCT_CATEGORYPAGE_INSTITUTES,0);
//		
//		
//        $sponseredInstitutes = array('sticky' => $institutes['sticky'],'main' => $institutes['main']);
//		//_p("storing institutes for category page key: ". $request->getPageKey());
//        $this->store('CategoryPageSponseredInstitutes',$request->getPageKey(),serialize($sponseredInstitutes),$this->cacheExpirationTime,CACHEPRODUCT_CATEGORYPAGE_INSTITUTES,0);
//        
//        foreach(array('paid','free') as $instituteType) {
//			/*
//			* First delete old cache chunks
//			*/
//			$chunkId = 1;
//            while(true) {
//				$chunk = unserialize($this->get('CategoryPageInstitutes-'.$instituteType.'-'.$chunkId,$request->getPageKey()));
//				if(is_array($chunk) && count($chunk) > 0) {
//					$this->delete('CategoryPageInstitutes-'.$instituteType.'-'.$chunkId,$request->getPageKey());
//					$chunkId++;
//				}
//				else {
//					break;
//				}
//                
//                /*
//                 * Infinite-loop safe condition
//                 */ 
//                if($chunkId > 100) {
//                    break;
//                }
//            }
//			
//            $institutesForType = $institutes[$instituteType];
//            $numberOfChunks = ceil(count($institutesForType)/350);
//            $start = 0;
//            for($chunkId=1;$chunkId<=$numberOfChunks;$chunkId++) {
//                $chunk = array_slice($institutesForType,$start,350,TRUE);
//                if(count($chunk)>0) {					
//                    $this->store('CategoryPageInstitutes-'.$instituteType.'-'.$chunkId,$request->getPageKey(),gzcompress(serialize($chunk)),$this->cacheExpirationTime,CACHEPRODUCT_CATEGORYPAGE_INSTITUTES,0);
//					//echo gzcompress(serialize($chunk))."<br /><br />";
//                    $start = $start+350;
//                }
//            }
//        }
		$this->store('CategoryPageInstitutesStoreTime',$request->getPageKey(),time(),-1,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function getCategoryPageInstitutesStoreTime($request)
	{
		return $this->get('CategoryPageInstitutesStoreTime',$request->getPageKey());
	}
	
	/*
	 * Category Page Filters
	 */
	function getFilters(CategoryPageRequest $request)
    {
		//_p("Get filters for page key: " . $request->getPageKey());
		return unserialize(gzuncompress($this->get('CategoryPageFilters',$request->getPageKey())));
    }
	
	function storeFilters(CategoryPageRequest $request,$filters)
	{
		//_p("filters storing for page key: " . $request->getPageKey());
        $this->store('CategoryPageFilters',$request->getPageKey(),gzcompress(serialize($filters)),$this->cacheExpirationTime,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	/*
	 * Category Page Banner Rotation Index
	 */
	function getBannerRotationIndex(CategoryPageRequest $request)
        {
		$rotationIndex = $this->get('CategoryPageBannerRotation',$request->getPageKey());
                if($rotationIndex == "ZERO_INDEX_VALUE")
                    $rotationIndex = 0;

                return $rotationIndex;
        }
	
	function storeBannerRotationIndex(CategoryPageRequest $request,$rotationIndex)
	{
                if($rotationIndex == 0)
                    $rotationIndex = "ZERO_INDEX_VALUE";
                
		$this->store('CategoryPageBannerRotation',$request->getPageKey(),$rotationIndex,3600,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}

	function getLastBannerRotationFlag(CategoryPageRequest $request)
        {
		$rotationIndex = $this->get('CategoryPageLastBannerRotationFlag',$request->getPageKey());
                return $rotationIndex;
        }
	
	function storeLastBannerRotationFlag(CategoryPageRequest $request,$isAbroadCategoryPageRequest = FALSE)
	{   
		$ttl = 0; // as cache minimum ttl is 1800 sec.      
		if($isAbroadCategoryPageRequest) {
			$ttl = 900; // 15 min ttl : Change in CacheLib Memcache special check to handle this scenario 
		}   
		$this->store('CategoryPageLastBannerRotationFlag',$request->getPageKey(),1,$ttl,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}

	
	/*
	 * Category Page Institutes Rotation Index
	 */
	function getInstitutesRotationIndex($type,CategoryPageRequest $request)
    {
		return $this->get(ucfirst($type).'InstitutesRotation',$request->getPageKey());
    }
	
	function storeInstitutesRotationIndex($type,CategoryPageRequest $request,$rotationIndex)
	{
		$this->store(ucfirst($type).'InstitutesRotation',$request->getPageKey(),$rotationIndex,0,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function getDynamicLDBCoursesList(CategoryPageRequest $request)
    {
		$cacheKey = $request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-"."-".$request->getRegionId();
		return $this->get('dynamicLDBCoursesList',$cacheKey);
    }
	
	function storeDynamicLDBCoursesList(CategoryPageRequest $request,$data)
	{
		$cacheKey = $request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-"."-".$request->getRegionId();		
                $this->store('dynamicLDBCoursesList',$cacheKey,$data,$this->cacheExpirationTime,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function getDynamicCategoryList(CategoryPageRequest $request)
    {
		$cacheKey = $request->getCategoryId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-"."-".$request->getRegionId();
		return $this->get('DynamicCategoryList',$cacheKey);
    }
	
	function storeDynamicCategoryList(CategoryPageRequest $request,$data)
	{
		$cacheKey = $request->getCategoryId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-"."-".$request->getRegionId();		
        $this->store('DynamicCategoryList',$cacheKey,$data,$this->cacheExpirationTime,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function getDynamicLocationList(CategoryPageRequest $request)
    {
		$cacheKey = $request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->isStudyAbroadPage();
		return $this->get('DynamicLocationList',$cacheKey);
    }
	
	function storeDynamicLocationList(CategoryPageRequest $request,$data)
	{
		$cacheKey = $request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->isStudyAbroadPage();
        $this->store('DynamicLocationList',$cacheKey,$data,$this->cacheExpirationTime,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function storeDynamicLocationListSolr(CategoryPageRequest $request,$data)
	{
		$cacheExpirationTime = 21600; // 6 hours
		$cacheKey = $request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->isStudyAbroadPage();
        $this->store('DynamicLocationList',$cacheKey,$data,$cacheExpirationTime,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function getChildPageKeys(CategoryPageRequest $parentRequest) {
		//_p("In function getChildPageKeys");
		$parentPageKey = $parentRequest->getPageKey();
		//_p("key: " . $parentPageKey);
		$keys = $this->get('parentChildMappingKeys', $parentPageKey);
		//_p("All keys:"); _p($keys);
		return $keys;
	}
	
	function storeChildPageKeys(CategoryPageRequest $parentRequest, CategoryPageRequest $childRequest) {
		//_p("In function storeChildPageKeys");
		$childPageKey  = $childRequest->getPageKeyString();
		$parentPageKey = $parentRequest->getPageKey();
		$parentChildMappingKeys = $this->getChildPageKeys($parentRequest);
		if(empty($parentChildMappingKeys)){
			$parentChildMappingKeys = array();
		}
		if(!in_array($childPageKey, $parentChildMappingKeys)){
			$parentChildMappingKeys[] = $childPageKey;
		}
		//_p("updated keys: ");
		//_p($parentChildMappingKeys);
		$this->store('parentChildMappingKeys', $parentPageKey, $parentChildMappingKeys,-1); // -1 is passed to store data in cache for unlimited time. 
	}
	
	function deleteChildPageKeys(CategoryPageRequest $parentRequest) {
		$parentPageKey = $parentRequest->getPageKey();
		//_p("Delete child keys for: " . $parentPageKey);
		$this->delete('parentChildMappingKeys', $parentPageKey);
	}
	
	function storeCurrencyDetails($currencyDetails){
		$this->store('CategoryPageCurrencyDetails',1,$currencyDetails,43200,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function getCurrencyDetails()
	{
		return $this->get('CategoryPageCurrencyDetails',1);
	}
	
	/*
	 * Abroad Category Page Country Rotation Index..
	 */
	function getCountryRotationIndex(CategoryPageRequest $request)
        {
		$rotationIndex = $this->get('CategoryPageCountryRotation',$request->getPageKey());
                if($rotationIndex == "ZERO_INDEX_VALUE")
                    $rotationIndex = 0;

                return $rotationIndex;
        }
	
	function storeCountryRotationIndex(CategoryPageRequest $request,$rotationIndex)
	{
                if($rotationIndex == 0)
                    $rotationIndex = "ZERO_INDEX_VALUE";
                
		$this->store('CategoryPageCountryRotation',$request->getPageKey(),$rotationIndex,43200,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}

	function getLastCountryRotationFlag(CategoryPageRequest $request)
        {
		$rotationIndex = $this->get('CategoryPageLastCountryRotationFlag',$request->getPageKey());
                return $rotationIndex;
        }
	
	function storeLastCountryRotationFlag(CategoryPageRequest $request)
	{
		$this->store('CategoryPageLastCountryRotationFlag',$request->getPageKey(),1,900,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}	
	
	
	
	/*  Reason :For maintaining separate cache for  country page banner 
	 * Params : None
	 *  author : Abhay
	*/
	function getCountryRotationIndexForCountryPageBanner()
        {
		$rotationIndex = $this->get('CountryPageCountryRotation','ForBanner');
                if($rotationIndex == "ZERO_INDEX_VALUE")
                    $rotationIndex = 0;
          
                return $rotationIndex;
        }
	
	function storeCountryRotationIndexForCountryPageBanner($rotationIndex)
	{
                if($rotationIndex == 0)
                    $rotationIndex = "ZERO_INDEX_VALUE";
          
		$this->store('CountryPageCountryRotation','ForBanner',$rotationIndex,43200,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}

	function getLastCountryRotationFlagForCountryPageBanner()
        {
		$rotationIndex = $this->get('CountryPageLastCountryRotationFlag','ForBanner'); 		
                return $rotationIndex;
        }
	
	function storeLastCountryRotationFlagForCountryPageBanner()
	{ 
		$this->store('CountryPageLastCountryRotationFlag','ForBanner',1,900,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function getCategoryPageStickyListings(CategoryPageRequest $request) {
		$key = $request->getPageKey();
		$data = unserialize(gzuncompress($this->get('CATPAGE_STICKY_INSTITUTES', $key)));
		return $data;
	}
	
	function storeCategoryPageStickyListings(CategoryPageRequest $request, $data){
		$key  = $request->getPageKey();
		if(empty($data)) {
			$data = "EMPTY RESULT";
		}
		$data = gzcompress(serialize($data), 9);
		$this->store('CATPAGE_STICKY_INSTITUTES', $key, $data, 3600, NULL, 1);
	}
	
	function getCategoryPageMainListings(CategoryPageRequest $request) {
		$key = $request->getPageKey();
		$data = unserialize(gzuncompress($this->get('CATPAGE_MAIN_INSTITUTES', $key)));
		return $data;
	}
	
	function storeCategoryPageMainListings(CategoryPageRequest $request, $data) {
		$key  = $request->getPageKey();
		if(empty($data)) {
			$data = "EMPTY RESULT";
		}
		$data = gzcompress(serialize($data), 9);
		$this->store('CATPAGE_MAIN_INSTITUTES', $key, $data, 3600, NULL, 1);
	}
	
	function getCategoryPageFilterToHide() {
		$data = unserialize(gzuncompress($this->get('CATPAGE_FILTERS', 'FILTER_TO_HIDE')));
		return $data;
	}
	
	function storeCategoryPageFilterToHide($data){
		$timeTocache = 604800; // 7 Days
		$data = gzcompress(serialize($data), 9);
		$this->store('CATPAGE_FILTERS', 'FILTER_TO_HIDE', $data, $timeTocache, NULL, 1);
	}

	/************************** for caching Inventory Bucket Institutes Rotation Index  *******************************/
	function storeInventoryBucketInstitutesRotationIndex($type,CategoryPageRequest $request,$rotationIndex)
	{ error_log("IN CTPG CACHE storing".$rotationIndex);
	    $ttl = 10800; // keep the rot. index for 6 hours
	    
	    if($rotationIndex == 0)
                {
		    $rotationIndex = "ZERO_INDEX_VALUE";
		}
	    $this->store(ucfirst($type).'InstitutesRotation',$request->getPageKey(),$rotationIndex,$ttl,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}

	function getInventoryBucketInstitutesRotationIndex($type,CategoryPageRequest $request)
        {
	    $rotationIndex = $this->get(ucfirst($type).'InstitutesRotation',$request->getPageKey());
	    if($rotationIndex == "ZERO_INDEX_VALUE")
                $rotationIndex = 0;

            return $rotationIndex;
	}

	function storeInventoryBucketInstitutesRotationFlag(CategoryPageRequest $request)
	{   
	    $this->store('InventoryBucketInstitutesRotationFlag',$request->getPageKey(),1,0,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
	}
	
	function getInventoryBucketInstitutesRotationFlag(CategoryPageRequest $request)
        {
            return $this->get('InventoryBucketInstitutesRotationFlag',$request->getPageKey());
        }
	/************************** END :: for caching Inventory Bucket Institutes Rotation Index  *******************************/
    
    function getFatFooter($key) {
    	return unserialize($this->get('fatFooter',$key));
    }

    function storeFatFooter($key,$data) {
    	$this->store('fatFooter',$key,serialize($data),1728000,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
    }

    function getCategoryPageData(CategoryPageRequest $request)
    {
	$key  = $request->getPageKeyForCachingSolrResults();
	$data = unserialize(gzuncompress($this->get('CATPAGE_SOLR_DATA_', $key)));
	return $data;
    }
    
    function storeCategoryPageData(CategoryPageRequest $request,$data)
    {
	$cacheKey = $request->getPageKeyForCachingSolrResults();
	$data = gzcompress(serialize($data), 9);
	$this->store('CATPAGE_SOLR_DATA_',$cacheKey,$data,3600,CACHEPRODUCT_CATEGORYPAGE_MISC,0);
    }
    
    function getRankingRelatedLinks(CategoryPageRequest $request)
    {
	$key  = $request->getPageKey();
	$data = unserialize(gzuncompress($this->get('CATPAGE_RANKING_RELATED_', $key)));
	return $data;
    }
    
    function storeRankingRelatedLinks(CategoryPageRequest $request,$data)
    {
	$cacheKey = $request->getPageKey();
	$data = gzcompress(serialize($data), 9);
	$this->store('CATPAGE_RANKING_RELATED_',$cacheKey,$data,86400,CACHEPRODUCT_CATEGORYPAGE_MISC,0); // 1 day cache
    }

    function getCategoryRelatedLinks(CategoryPageRequest $request)
    {
	$key  = $request->getPageKey();
	$data = unserialize(gzuncompress($this->get('CATPAGE_RELATED_', $key)));
	return $data;
    }
    
    function storeCategoryRelatedLinks(CategoryPageRequest $request,$data)
    {
	$cacheKey = $request->getPageKey();
	$data = gzcompress(serialize($data), 9);
	$this->store('CATPAGE_RELATED_',$cacheKey,$data,86400,CACHEPRODUCT_CATEGORYPAGE_MISC,0); // 1 day cache
    }
	
	function storeCategoryPageQuickLinks($key, $data){
		$timeTocache = 1728000; //20 Days
		$data = gzcompress(serialize($data), 9);
		$this->store('CATPAGE_QUICK_LINKS', $key, $data, $timeTocache, NULL, 1);
	}       
 	
 	function getCategoryPageQuickLinks($key) {
		$data = unserialize(gzuncompress($this->get('CATPAGE_QUICK_LINKS', $key)));
		return $data;
	}
}
