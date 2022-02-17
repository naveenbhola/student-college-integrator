<?php

class CategoryPageRepository extends EntityRepository
{
    private $request;
    private $instituteRepository;
    private $bannerRepository;
    private $LDBCourseRepository;
    private $locationRepository;
    private $categoryRepository;
    
    function __construct($dao,$cache = NULL,
                        CategoryPageRequest $request,
                        InstituteRepository $instituteRepository,
                        BannerRepository $bannerRepository,
                        LDBCourseRepository $LDBCourseRepository,
                        LocationRepository $locationRepository,
                        CategoryRepository $categoryRepository)
    {
        parent::__construct($dao,$cache);
        
        $this->request = $request;
        $this->instituteRepository = $instituteRepository;
        $this->bannerRepository = $bannerRepository;
        $this->LDBCourseRepository = $LDBCourseRepository;
        $this->locationRepository = $locationRepository;
        $this->categoryRepository = $categoryRepository;
    }
    
     /*
     * Institutes (with supplementary data like courses/LDB courses)
     * Data consists of sticky+main+paid+free institutes
     */
    public function getInstitutes(CategoryPageRequest $customRequest = NULL)
    {
        //_p($this->request);
        $requestInUse = $this->request;
        if(!empty($customRequest)){
            $requestInUse = $customRequest;
        }
        /*
         * Fetch all countries in category page request
         * Usually there is only one country in the request
         * But for study abroad region pages, we need all countries which belog to that region
         * e.g. Region = Europe, the countries -> Germany, Sweden, France
         */ 
        $categories = $this->_getCategories($requestInUse);
        
        /*
         * Fetch all main categories in category page request
         * Usually there is only one category in the request
         * But for study abroad country pages with no category selected,
         * we need all the main categories
         */
        $countries = $this->_getCountries();
        
        /*
         * Create a clone of CategoryPageRequest
         * so that we can change its data without modifying the original object
         */ 
        $clonedRequest = clone $this->request;
        
        /*
         * For every counrty and cateory, fetch institutes from institute repository
         * Institute repository requires valid category and country
         */
        
        $instituteList = array();
        foreach($categories as $categoryId) {
            foreach($countries as $countryId) {
                $clonedRequest->setData(array('countryId' => $countryId,'categoryId' => $categoryId));
                $instituteList[] = $this->instituteRepository->getCategoryPageInstitutes($clonedRequest);        
            }
        }
        
        /*
         * Consolidate institutes
         * Merge sticky, main, paid and free institutes
         */ 
        return $this->_consolidateInstituteList($instituteList);
    }
    
    public function getBanners()
    {
        $categories = $this->_getCategories();
        $countries = $this->_getCountries();
        
        $clonedRequest = clone $this->request;
        
        $bannerList = array();
        foreach($categories as $categoryId) {
            foreach($countries as $countryId) {
                $clonedRequest->setData(array('countryId' => $countryId,'categoryId' => $categoryId));
                $banners = $this->bannerRepository->getCategoryPageBanners($clonedRequest);
                $bannerList = array_merge_recursive($bannerList,$banners);
            }
        }
        return $bannerList;
    }
    
    public function getMultilocationShoshkele($locationForBannerPool)
    {
        if(empty($locationForBannerPool))
        {   // return 
            return ;
        }
        $categories = $this->_getCategories();
        $countries = $this->_getCountries();
        
        $clonedRequest = clone $this->request;
        
        $bannerList = array();
        foreach($categories as $categoryId) {
            foreach($countries as $countryId) {
                $locations = $clonedRequest->getUserPreferredLocationOrder();
                foreach ($locations as $location){ // go through the list of locations & set banners for the one passed in $locationForBannerPool
                    if($location['location_type'] == $locationForBannerPool['location_type']){
                        //this can be a city /state
                        if($location['location_type'] == 'city')
                        {
                            $cityId = $location['location_id'] ;
                            $clonedRequest->setData(array('countryId' => $countryId,'categoryId' => $categoryId,'cityId'=>$cityId));
                        }
                        else{
                            $stateId = $location['location_id'] ;
                            $clonedRequest->setData(array('countryId' => $countryId,'categoryId' => $categoryId,'stateId'=>$stateId));
                        }
                        $banners = $this->bannerRepository->getCategoryPageBanners($clonedRequest);
                        $bannerList = array_merge_recursive($bannerList,$banners);
                    }
                }
            }
        }
        return $bannerList;
    }
    
    private function _getCountries(CategoryPageRequest $customRequest = NULL)
    {
        $requestInUse = $this->request;
        if(!empty($customRequest)){
            $requestInUse =  $customRequest;
        }
        
        $countryId = (int) $requestInUse->getCountryId();
        if($countryId > 1) {
            $countries = array($requestInUse->getCountryId());
        }
        else if($regionId = $requestInUse->getRegionId()) {
            
            $countries = array();
            $countriesInRegion = $this->locationRepository->getCountriesByRegion($regionId);
            foreach($countriesInRegion as $country) {
                $countries[] = $country->getId();
            }
        }
        return $countries;
    }
    
    private function _getCategories(CategoryPageRequest $customRequest = NULL)
    {
        $requestInUse = $this->request;
        if(!empty($customRequest)){
            $requestInUse =  $customRequest;
        }
        
        $categoryId = $requestInUse->getCategoryId();
        $categories = array($categoryId);
        
        /*
         * If no category specified, fetch all categories
         */
        if($categoryId <= 1) {
            $categories = array();
            $categoryObjs = $this->categoryRepository->getSubCategories(1);
            foreach($categoryObjs as $categoryObj) {
                $categories[] = $categoryObj->getId();
            }
        }
        
        return $categories;
    }
    
    private function _consolidateInstituteList($institutes)
    {
        $sticky = array();
        $main = array();
        $paid = array();
        $free = array();
        
        foreach($institutes as $institute) {
            
            foreach($institute['sticky'] as $type => $typeInstitutes) {
                
                if($typeInstitutes && is_array($typeInstitutes)) {
                    if(!isset($sticky[$type])) {
                        $sticky[$type] = $typeInstitutes;
                    }
                    else {
                        $sticky[$type] = $sticky[$type] + $typeInstitutes;
                    }
                }
            }
            
            foreach($institute['main'] as $type => $typeInstitutes) {
                
                if($typeInstitutes && is_array($typeInstitutes)) {
                    if(!isset($main[$type])) {
                        $main[$type] = $typeInstitutes;
                    }
                    else {
                        $main[$type] = $main[$type] + $typeInstitutes;
                    }
                }
            }
            
            $paid = $paid + (array) $institute['paid'];
            $free = $free + (array) $institute['free'];
        }
        
        $mergedInstitutes = array(
            'sticky' => $sticky,
            'main' => $main,
            'paid' => $paid,
            'free' => $free
        );
        
        return $mergedInstitutes;
    }
    
    /*
     * Get domain objects corresponding to institue and course ids
     */ 
    public function loadInstitutes($instituteIds)
    {
        if(is_array($instituteIds) && count($instituteIds) > 0) {

            if($this->request->getSubCategoryId() == 56)
                $instituteIdsWithCourseIds = array_map('array_keys',$instituteIds);
            else
		$instituteIdsWithCourseIds = array_map(function ($ar) {return array(key($ar));}, $instituteIds); //array_map('array_keys',$instituteIds);

            return $this->instituteRepository->findWithCourses($instituteIdsWithCourseIds);
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
    
    public function getLocality()
    {
        if($localityId = $this->request->getLocalityId()) {
            return $this->locationRepository->findLocality($localityId);
        }
    }
    
    public function getZone()
    {
        if($zoneId = $this->request->getZoneId()) {
            return $this->locationRepository->findZone($zoneId);
        }
    }
    
    public function getRegion()
    {
        if($regionId = $this->request->getRegionId()) {
            return $this->locationRepository->findRegion($regionId);
        }
    }
    
    public function getFiltersToHide($type,$typeId)
    {
        return $this->dao->getFiltersToHide($type,$typeId);
    }
    
    public function getHeaderText()
    {
        $this->CI->load->model('categoryList/categorypagemodel');
        $this->CI->load->model('location/locationmodel');
        $locationModel = new LocationModel;
        // get the objects of the model
	$categoryPageModelObj = new CategoryPageModel;
        $categoryPageModelObj->init($locationModel);

        return $categoryPageModelObj->getHeaderText($this->request);
        //return $this->dao->getHeaderText($this->request);
    }
    
    public function getDynamicLDBCoursesList($disableCache = false)
    {
        if($this->caching && ($data = $this->cache->getDynamicLDBCoursesList($this->request)) && !$disableCache) {
            return $data;
        }
        // load the models
		$this->CI->load->model('location/locationmodel','',TRUE);
		$this->CI->load->model('categoryList/categorypagemodel', '', TRUE);
        $locationModel = new LocationModel;
		$categorypagemodel = new CategoryPageModel;
		$categorypagemodel->init($locationModel);
        
		$data = $categorypagemodel->getDynamicLDBCoursesList($this->request);
        $this->cache->storeDynamicLDBCoursesList($this->request,$data);
        return $data;
    }
        
  
    public function getDynamicCategoryList($disableCache = false)
    {
        if($this->caching && ($data = $this->cache->getDynamicCategoryList($this->request)) && !$disableCache) {
            return $data;
        }
        /*
        $data = $this->dao->getDynamicCategoryList($this->request);
        */
        // load the models
		$this->CI->load->model('location/locationmodel','',TRUE);
		$this->CI->load->model('categoryList/categorypagemodel', '', TRUE);
        $locationModel = new LocationModel;
		$categorypagemodel = new CategoryPageModel;
		$categorypagemodel->init($locationModel);
        $data = $categorypagemodel->getDynamicCategoryList($this->request);
        $this->cache->storeDynamicCategoryList($this->request,$data);
        
        return $data;
    }
    
    public function getDynamicLocationList($disableCache = false)
    {
        if($this->caching && ($data = $this->cache->getDynamicLocationList($this->request)) && !$disableCache) {
            return $data;
        }
        //$data = $this->dao->getDynamicLocationList($this->request);
        // load the models
		$this->CI->load->model('location/locationmodel','',TRUE);
		$this->CI->load->model('categoryList/categorypagemodel', '', TRUE);
        $locationModel = new LocationModel;
		$categorypagemodel = new CategoryPageModel;
		$categorypagemodel->init($locationModel);
        $data = $categorypagemodel->getDynamicLocationList($this->request);
        $this->cache->storeDynamicLocationList($this->request,$data);
        
        return $data;
    }
    
    
    public function getAllFiltersToHide($type,$typeId)
    { 
    	if($this->caching && ($data = $this->cache->getCategoryPageFilterToHide())) {
    		return $data[$type][$typeId];
    	}
    	
    	$this->CI->load->model('categoryList/categorypagemodel', '', TRUE);
    	$categorypagemodel = new CategoryPageModel;
    	$data = $categorypagemodel->getAllFiltersToHide();
    	$this->cache->storeCategoryPageFilterToHide($data);
    	return $data[$type][$typeId];
    }
    
    public function setRequest(CategoryPageRequest $request)
    {
        $this->request = $request;
    }
    
    /*
     * Get category page parameters for an entity based on given criteria
     * parameters = (category, subcategory, ldb course, city, state, country)
     * entity = (course,banner,stickyInstitute,MainInstitute)
     */
    public function getCategoryPageParameters($entity,$entityId,$criteria=array())
    {
        return $this->dao->getCategoryPageParameters($entity,$entityId,$criteria);
    }
    
    public function setCategoryPageDataInCacheMemory($courseIds)
    {
        return $this->dao->setCategoryPageDataInCacheMemory($courseIds);
    }
    
    public function raiseAlert($type,$data)
    {
        $this->dao->raiseAlert($type,$data);
    }
    
    public function getParentPageRequest(CategoryPageRequest $childPageRequest)
    {
        $parentPageRequest = clone $childPageRequest;
        $data = array();
        $data['examName']     = "";
        $data['feesValue']    = "";
        $data['affiliation']  = "";
        $data['localityId']   = 0;
        $parentPageRequest->setNewURLFlag(1);
        $parentPageRequest->setData($data);
        return $parentPageRequest;
    }
}
