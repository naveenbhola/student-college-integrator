<?php

class AbroadCategoryPageSolrRepository extends EntityRepository
{
    private $request;
    private $instituteRepository;
    private $LDBCourseRepository;
    private $locationRepository;
    private $categoryRepository;
    private $universityRepository;
    private $courseRepository;
    private $bannerRepository;
    protected  $cache;
    private $corruptedUniversityIds;
    
    function __construct($dao,$cache = NULL,
                        AbroadCategoryPageRequest $request,
    		            UniversityRepository $universityRepository,
                        AbroadInstituteRepository $instituteRepository,
    					AbroadCourseRepository $courseRepository,
                        LDBCourseRepository $LDBCourseRepository,
                        LocationRepository $locationRepository,
                        CategoryRepository $categoryRepository,
                        BannerRepository $bannerRepository,
						ListingErrorReportingLib $listingErrorReportingLib,
						AbroadCategoryPageSolrRequestGenerator $solrRequestGenerator,
						AbroadCategoryPageSolrResponseParser $solrResponseParser)
    {
        parent::__construct($dao,$cache);
        $this->cache = $cache;
        $this->request = $request;
        $this->universityRepository = $universityRepository;
        $this->instituteRepository = $instituteRepository;
        $this->courseRepository =  $courseRepository;
        $this->LDBCourseRepository = $LDBCourseRepository;
        $this->locationRepository = $locationRepository;	
        $this->categoryRepository = $categoryRepository;
        $this->bannerRepository = $bannerRepository;
        $this->listingErrorReportingLib = $listingErrorReportingLib;
        $this->solrRequestGenerator = $solrRequestGenerator;
        $this->solrResponseParser = $solrResponseParser;
		$this->solrResponseParser->setRequest($this->request);
        
    }
 
 
	/*
	 * get universities , return in a classified sturcture based on inventory 
	 */
    public function getUniversities($rotationService = NULL)
    {
		$CI = & get_instance();
		$this->benchmark = $CI->benchmark;
		
		$this->benchmark->mark("NO1ST");
		// keep a copy of request
    	$clonedRequest = clone $this->request;
		/**If page is ldb and Subcat type then inventory will be on category id ***/
    	/*if($clonedRequest->isLDBCourseSubCategoryPage() || $clonedRequest->isCategorySubCategoryCourseLevelPage()) 
    	{
			if(is_array($subCats=$clonedRequest->getSubCategoryId()))
			{
				$subcatObj = $this->categoryRepository->findMultiple($subCats);
				$subcatObj = reset($subcatObj);
			}
    		else{
				$subcatObj = $this->categoryRepository->find($subCats);
			}
			$clonedRequest->setData(array('categoryId' => $subcatObj->getParentId()));
    	}*/
		$this->benchmark->mark("NO1END");
		error_log( "clone request time taken:= ".$this->benchmark->elapsed_time('NO1ST', 'NO1END'));
		// use solr to get all univ-course data
		$this->benchmark->mark("NO2ST");
		$solrResultData = $this->_getCategoryPageDataFromSolr($clonedRequest, $rotationService);
		$this->benchmark->mark("NO2END");
		error_log( "url generation, request-response time taken:= ".$this->benchmark->elapsed_time('NO2ST', 'NO2END'));
		
		$this->benchmark->mark("NO3ST");
        
		if(!$this->request->isSolrFilterAjaxCall()){
            $solrResultData['universities'] = $this->loadAndAssociateUniversityCourseOjectsSolr($solrResultData['universities']);
            $this->_getSubcategoriesFromFacet($solrResultData);
		}
		$this->benchmark->mark("NO3END");
		error_log( "load objs time taken:= ".$this->benchmark->elapsed_time('NO3ST', 'NO3END'));
		
		$this->benchmark->mark("NO4ST");
		$solrResultData['filters'] = $this->_formatFilters($solrResultData['rawFilters'],$clonedRequest);
		$this->benchmark->mark("NO4END");
		error_log( "format filters using filter lib time taken:= ".$this->benchmark->elapsed_time('NO4ST', 'NO4END'));
        return $solrResultData;
    }
    
	private function _getCategoryPageDataFromSolr($clonedRequest, $rotationService)
	{
		$this->benchmark->mark('SO1ST');
		if($clonedRequest->checkIfCertDiplomaCountryCatPage($clonedRequest))
		{
			$clonedRequest->setFlagToGetCertDiplomaResults(true);
		}
		$solrRequestUrl = $this->solrRequestGenerator->generateUrlForCategoryPage($clonedRequest);
		$this->benchmark->mark('SO1END');
		error_log( "url generation time taken:= ".$this->benchmark->elapsed_time('SO1ST', 'SO1END'));
		
		$this->benchmark->mark('SO2ST');
		$CI = & get_instance();
		$solrClient = $CI->load->library("SASearch/AutoSuggestorSolrClient");
		
		$response = $solrClient->getCategoryPageResults($solrRequestUrl, 'Abroad Category Page');
		$this->benchmark->mark('SO2END');
		error_log( "solr call time taken:= ".$this->benchmark->elapsed_time('SO2ST', 'SO2END'));
		
		$this->benchmark->mark('SO3ST');
		// check if only cert diploma courses are available & page is NOT a cert-diploma page
		$response = $this->_checkIfOnlyCertDiplomaResultsExist($solrClient, $clonedRequest, $response);
		$this->benchmark->mark('SO3END');
		error_log( "check if only cert diploma time taken:= ".$this->benchmark->elapsed_time('SO3ST', 'SO3END'));
		
		$this->benchmark->mark('SO4ST');
		if(!$this->request->isSolrFilterAjaxCall()){
			$this->solrResponseParser->setRotationService($rotationService); // needed for rotation
			$currentBanner = $this->getSingleRotatedBanner($rotationService);
			$this->solrResponseParser->setBanner($currentBanner); // needed for rotation of cat spon
		}
		$this->benchmark->mark('SO4END');
		error_log( "rotate & get banner  time taken:= ".$this->benchmark->elapsed_time('SO4ST', 'SO4END'));
		
		$this->benchmark->mark('SO5ST');
		$categoryPageData = $this->solrResponseParser->getCategoryPageDataFromSolrResponse($response);
		if(!is_null($currentBanner))
		{
			$categoryPageData['currentBanner'] = $currentBanner;
		}
			
		$this->benchmark->mark('SO5END');
		error_log( "response parsing time taken:= ".$this->benchmark->elapsed_time('SO5ST', 'SO5END'));
		
        return $categoryPageData;
	}
	
    /*
     * function to get subcategories from solr response
     */
    private function _getSubcategoriesFromFacet(&$solrResultData)
    {
        // only if this wasn't a subcat page already
        if(!$this->request->isAJAXCall() && !is_null($solrResultData['rawFilters']))
        {
            if(count($solrResultData['rawFilters']['facet_fields']['saCourseSubcategoryId'])>0)
            {
                $solrResultData['popularSubcats'] = array_filter(array_keys($solrResultData['rawFilters']['facet_fields']['saCourseSubcategoryId']));
            }
            unset($solrResultData['rawFilters']);
        }
    }

	/*
	 * function that:
	 * - checks if current response has zero results & it's NOT a cert-diploma page.
	 * - sends another request for only cert-diploma courses
	 * - returns that response if there are any results OR returns original response.
	 */
	private function _checkIfOnlyCertDiplomaResultsExist($solrClient, $clonedRequest, $response)
	{
			$tempResp = (reset($response['grouped']));

	$tempResp = (reset($response['grouped']));
		/* make second call only if :
		 * - this is NOT a call to get filters (as results will be deliberately set to 0 in that case),
		 * - there were no results returned earlier in response
		 * - this is not a cert-diploma page
		 */
		if(!$this->request->isSolrFilterAjaxCall() &&
		   !$this->request->isCertDiplomaPage() &&
			$tempResp['matches'] == 0
		   )
		{
			$clonedRequest->setFlagToGetCertDiplomaResults(true);
			$this->request->setFlagToGetCertDiplomaResults(true);
			$solrRequestUrl = $this->solrRequestGenerator->generateUrlForCategoryPage($clonedRequest);
			$response = $solrClient->getCategoryPageResults($solrRequestUrl, 'Abroad Category Page');
		}
		$tempResp = (reset($response['grouped']));
		return $response;
	}
	
    public function loadAndAssociateUniversityCourseOjectsSolr($rawUniversities = array())
    {
        $this->CI->load->config('listing/studyAbroadListingCacheConfig');
        $universitiesId = array();
        $departmentIds = array();
        $courseIdarray = array();
        $courseSubCatIds = array();
        foreach($rawUniversities as $universityId=>$courseProperties){
            $universitiesId [] = $universityId;
            foreach($courseProperties as $courseId =>$courseProperty) {
                $courseIdarray [] = $courseId;
                $courseSubCatIds [] = $courseProperty['saCourseSubcategoryId'];
            }
        }
		// remove invalid course ids if any
		$courseIdarray = array_filter($courseIdarray,function($e){if($e > 0) return true; else return false;});
	global $shikshaCacheProfile;
    $debug['beforeObj'] = memory_get_usage();
        $debug['beforeObjCache'] =  $shikshaCacheProfile['cacheReadSize'];
        if(count($courseIdarray) > 0 && count($universitiesId) > 0 ){
            // fields to be fetched from cache for course & objects
            $universityObjectFieldsCTP = $this->CI->config->item('universityObjectFieldsCTP');
            $courseObjectFieldsCTP = $this->CI->config->item('courseObjectFieldsCTP');
            // get the objects
            $universities = $this->universityRepository->findMultiple($universitiesId, $universityObjectFieldsCTP);
            $courses = $this->courseRepository->findMultiple($courseIdarray,$courseObjectFieldsCTP,array('univObjs'=>$universities));
            $subCategoryObj = $this->categoryRepository->findMultiple($courseSubCatIds);


            foreach($rawUniversities as $universityId=>$courseProperties) {
                    $courseProperties = (array) $courseProperties;
                    $currentUniversity = $universities[$universityId];
					$currentHighestInvType =100; // an unreasonably high number to denote inv type even lower than free(4)
                    foreach($courseProperties as $courseId =>$courseProperty) {                        
                        if(isValidAbroadCourseObject($courses[$courseId]))
                        {
                           $courses[$courseId]->setCourseSubCategoryObj($subCategoryObj[$courseProperty['saCourseSubcategoryId']]);
                        }    
                        if(isValidAbroadUniversityObject($currentUniversity))
                        {
                           if(isValidAbroadCourseObject($courses[$courseId]))
                           { 
                            $currentUniversity->addCourse($courses[$courseId]);
                           }
						   if($currentHighestInvType>$courseProperty['saCourseInventoryType'])
						   {
							$currentHighestInvType = $courseProperty['saCourseInventoryType'];
						   }
                        } 

                    }
                    if($currentHighestInvType ==1)
                    {
                             $currentUniversity->setSticky();
                    }
                    else if($currentHighestInvType==2)
                    {
                             $currentUniversity->setMain();
                    }
                    if( isValidAbroadUniversityObject($currentUniversity) &&count($currentUniversity->getCourses())==0)
                    {
                        unset($universities[$universityId]);
                    }
            }
            $debug['afterObj'] = memory_get_usage();
            $debug['afterObjCache'] =  $shikshaCacheProfile['cacheReadSize'];
            error_log( "SRB Memory taken: = ".print_r($debug,true));

            return $universities;
        }
    
    }
    
    public function getCategory()
    {
        $categoryId = $this->request->getCategoryId();
        return $this->categoryRepository->find($categoryId);
    }
    
    public function getSubCategory()
    {
        $subCategoryId = $this->request->getSubCategoryId();
        return $this->categoryRepository->find($subCategoryId);
    }
    
    public function getLDBCourse()
    {
        $LDBCourseId = $this->request->getLDBCourseId();
        return $this->LDBCourseRepository->find($LDBCourseId);
    }
    
    public function getCity()
    {
        $cityId = $this->request->getCityId();
        return $this->locationRepository->findCity($cityId);
    }
    
    public function getCountry()
    {
        $countryId = $this->request->getCountryId();
        return $this->locationRepository->findCountry($countryId);
    }
    
    public function getState()
    {
        $stateId = $this->request->getStateId();
        return $this->locationRepository->findState($stateId);
    }
    
    public function setRequest(AbroadCategoryPageRequest $request)
    {
        $this->request = $request;
    }
    
    public function getCurrencyDetials() {
     if($this->cache && $currencyConversionData = $this->cache->getCurrencyDetails())
     {
     	return $currencyConversionData;
     }
     $currencyData = $this->dao->getCurrencyDetailsForCategoryPage();
     $currencyConversionData[1][1] = 1;
     foreach($currencyData as $row){
     	$currencyConversionData[1][$row['source_currency_id']] = $row['conversion_factor'];
     }
     $this->cache->storeCurrencyDetails($currencyConversionData);
     return $currencyConversionData;
    }
     
    public function getBanners() {
    	
    	$clonedRequest = clone $this->request;
    	if($clonedRequest->isLDBCourseSubCategoryPage() || $clonedRequest->isCategorySubCategoryCourseLevelPage()) /**If page is ldb and Subcat type then inventory will be on category id ***/
    	{
			if(is_array($subCats=$clonedRequest->getSubCategoryId())) 
			{ 
					$subcatObj = $this->categoryRepository->findMultiple($subCats); 
					$subcatObj = reset($subcatObj); 
			} 
			else{ 
					$subcatObj = $this->categoryRepository->find($subCats); 
			}
    		$clonedRequest->setData(array('categoryId' => $subcatObj->getParentId()));
    	}
    	return $banners = $this->bannerRepository->getAbroadCategoryPageBanners($clonedRequest);
    }
	
    public function getSingleRotatedBanner($rotationService)
	{
		$banners = $this->getBanners();
    	if(is_array($banners) && count($banners) > 0) {
			$bannersAfterRotation = $rotationService->rotateBanners($banners,TRUE);/*** TRUE in Second parameter represent abroad request in common code***/
    		$this->banner = array_pop($bannersAfterRotation);
    		return $this->banner;
    	}
	}

    private function _formatFilters($solrResponse){
        $filterLib = $this->CI->load->library("categoryList/solr/AbroadCategoryPageSolrFilterLib");
        $filters =  $filterLib->formatFilterResults($solrResponse,$this->locationRepository,$this->categoryRepository);
        return $filters;
    }
	
}
