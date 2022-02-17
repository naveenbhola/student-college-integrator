<?php

class CategoryPageBuilder
{
    private $CI;
    private $request;
    
    function __construct($params = '', $newUrlFlag = false)
    {
        $this->CI = & get_instance();
        
        if(!$newUrlFlag) {
            $this->request = $this->CI->load->library('categoryList/CategoryPageRequest', $params);
        } else {
            $this->CI->load->library('categoryList/CategoryPageRequest');
            $this->request = new CategoryPageRequest($params, $newUrlFlag);
        }

        //$this->request = new CategoryPageRequest($params, $newUrlFlag);
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function setRequest(CategoryPageRequest $request)
    {
        $this->request = $request;
    }
    
    public function getCategoryPage()
    {
        $filterGeneratorService = $this->getFilterGeneratorService();

        $filterProcessorService = $this->getFilterProcessorService();
        $rotationService = $this->getRotationService();
        $sorterService = $this->getSorterService();
        $bucketingService = $this->getBucketingService();
        
        $categoryPageRepository = $this->getCategoryPageRepository();

        $this->CI->load->library('categoryList/cache/CategoryPageCache');
        $cache = $this->CI->categorypagecache;
        
        $this->CI->load->domainClass('CategoryPage','categoryList');
        $categoryPage = new CategoryPage($this->request,$categoryPageRepository,$filterGeneratorService,$filterProcessorService,$rotationService,$sorterService,$bucketingService,$cache);
        return $categoryPage;
    }
    
    public function getFilterGeneratorService()
    {
        $this->CI->load->domainClasses(array(
                                        'filters/AbstractFilter',
                                        'filters/ModeFilter',
                                        'filters/CourseLevelFilter',
                                        'filters/CourseLevelOneFilter',
                                        'filters/CourseLevelTwoFilter',                                    
                                        'filters/DurationFilter',
                                        'filters/DegreePrefFilter',
                                        'filters/ExamsFilter',
                                        'filters/AIMARatingFilter',
                                        'filters/ClassTimingFilter',
                                        'filters/LocalityFilter',
                                        'filters/CityFilter',
                                        'filters/StateFilter',
                                        'filters/ZoneFilter',
                                        'filters/CountryFilter',
                                        'filters/ExamsScoreFilter', 
                                        'filters/FeesFilter',
                                        'filters/LastModifiedDateFilter',
                                        'filters/SpecializationFilter'
                                    ),'categoryList');
        
        $this->CI->load->service('FilterGeneratorService','categoryList');
        $this->CI->load->service('FeeRangeDeciderService','categoryList');
        $this->CI->load->service('CurrencyConverterService','categoryList');
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        
        $defaultFilters = array(
            'mode' => new ModeFilter,
            'courseLevel' => new CourseLevelFilter,
            'courseLevel1' => new CourseLevelOneFilter,
            'courseLevel2' => new CourseLevelTwoFilter,
            'duration' => new DurationFilter,
            'classTimings' => new ClassTimingFilter,
            'locality' => new LocalityFilter,
            'city' => new CityFilter($locationRepository),            
            'zone' => new ZoneFilter,
            'country' => new CountryFilter,
            'courseexams'=>new ExamsScoreFilter,
            'fees' => new FeesFilter(new FeeRangeDeciderService, new CurrencyConverterService, $this->request),
            'lastmodifieddate' => new LastModifiedDateFilter($this->request)
        );
        
        /*
         * Additional Filter for Management catagory
         */
        if($categoryId = $this->request->getCategoryId()) {
            $this->CI->load->builder('CategoryBuilder','categoryList');
            $categoryBuilder = new CategoryBuilder;
            $categoryRepository = $categoryBuilder->getCategoryRepository();
            $category = $categoryRepository->find($categoryId);
            
            if($category->isManagement() || $category->isEngineering()) {
                $defaultFilters += array(
                                        'degreePref' => new DegreePrefFilter,
                                        'exams' => new ExamsFilter,
                                        'AIMARating' => new AIMARatingFilter,
                                    );
            }
            
        }
        
        $RNRSubcategories = array_keys($this->CI->config->item('CP_SUB_CATEGORY_NAME_LIST'));
        if(in_array($this->request->getSubCategoryId(), $RNRSubcategories)) {
                $defaultFilters += array(
                                         'specialization'=>new SpecializationFilter,
                                    );
        }
        
        //if($this->request->isStudyAbroadPage() && $this->request->getCountryId() > 2) {
            $defaultFilters['state'] = new StateFilter;
        //}
        
        if(!isset($defaultFilters['exams']) && $this->request->isStudyAbroadPage()) {
            $defaultFilters['exams'] = new ExamsFilter;
        }
        
        $filterGeneratorService = new FilterGeneratorService($defaultFilters);
        return $filterGeneratorService;
    }
    
    public function getFilterProcessorService()
    {
        $this->CI->load->domainClasses(array(
                                        'specifications/Specification',
                                        'specifications/ModeSpecification',
                                        'specifications/CourseLevelSpecification',
                                        'specifications/DurationSpecification',
                                        'specifications/DurationRangeSpecification',
                                        'specifications/DegreePrefSpecification',
                                        'specifications/ExamSpecification',
                                        'specifications/AIMARatingSpecification',
                                        'specifications/ClassTimingSpecification',
                                        'specifications/LocalitySpecification',
                                        'specifications/CitySpecification',
                                        'specifications/StateSpecification',
                                        'specifications/ZoneSpecification',
                                        'specifications/CountrySpecification',
                                        'specifications/CourseExamsSpecification',
                                        'specifications/FeesSpecification',
                                        'specifications/LastModifiedDateSpecification'
                                    ),'categoryList');
        
        $this->CI->load->service('FilterProcessorService','categoryList');
                
        $defaultSpecifications = array(
            'mode' => new ModeSpecification,
            'courseLevel' => new CourseLevelSpecification,
            'duration' => new DurationSpecification,
            'durationRange' => new DurationRangeSpecification,
            'degreePref' => new DegreePrefSpecification,
            'exams' => new ExamSpecification,
            'AIMARating' => new AIMARatingSpecification,
            'classTimings' => new ClassTimingSpecification,
            'locality' => new LocalitySpecification,
            'city' => new CitySpecification,
            'state' => new StateSpecification,
            'zone' => new ZoneSpecification,
            'country' => new CountrySpecification,
            'courseexams'=> new CourseExamsSpecification($this->request),
            'fees'  => new FeesSpecification,
            'lastmodifieddate' => new LastModifiedDateSpecification($this->request)
        );
    
        //$specificationQuery = "mode AND courseLevel AND (duration OR durationRange) AND degreePref AND exams AND AIMARating AND classTimings AND (locality OR city OR state) AND zone AND country AND courseexams AND fees";
        $specificationQuery = "mode AND courseLevel AND (duration OR durationRange) AND degreePref AND exams AND AIMARating AND classTimings AND (locality OR city OR state) AND zone AND country AND courseexams AND fees AND lastmodifieddate";

        $filterProcessorService = new FilterProcessorService($defaultSpecifications,$specificationQuery);
        return $filterProcessorService;
    }
    
    public function getSorterService()
    {
        $this->CI->load->domainClasses(array(
                                        'sorters/AbstractSorter',
                                        'sorters/FeesSorter',
                                        'sorters/DurationSorter',
                                        'sorters/ViewCountSorter',
                                        'sorters/TopInstitutesSorter',
                                        'sorters/DateOfCommencementSorter',
        				'sorters/ExamScoreSorter'
                                    ),'categoryList');
        
        $this->CI->load->service('SorterService','categoryList');
        $this->CI->load->service('CurrencyConverterService','categoryList');
        
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $instituteRepository = $listingBuilder->getInstituteRepository();
        
        $defaultSorters = array(
            'fees' => new FeesSorter(NULL,new CurrencyConverterService, $this->request),
            'duration' => new DurationSorter,
            'viewCount' => new ViewCountSorter(NULL,$instituteRepository),
            'topInstitutes' => new TopInstitutesSorter(NULL,$instituteRepository),
            'dateOfCommencement' => new DateOfCommencementSorter,
            'examscore'=> new ExamScoreSorter($this->request)
        );
        
        $sorterService = new SorterService($defaultSorters);
        return $sorterService;
    }
    
    public function getCategoryPageSolrRepository()
    {
        $this->CI->load->library('categoryList/clients/CategoryPageSolrClient');
        $dao = $this->CI->categorypagesolrclient;
        
        $this->CI->load->library('categoryList/cache/CategoryPageCache');
        $cache = $this->CI->categorypagecache;
        
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $instituteRepository = $listingBuilder->getInstituteRepository();
        $bannerRepository = $listingBuilder->getBannerRepository();
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        
        $this->CI->load->builder('LDBCourseBuilder','LDB');
        $LDBCourseBuilder = new LDBCourseBuilder;
        $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
        
        $categoryPageSponsoredRepo = $this->getCategoryPageSponsoredRepository();
        /*
         * Load the reppository
         */
        
        $this->CI->load->repository('CategoryPageSolrRepository','categoryList');
        $categoryPageRepository = new CategoryPageSolrRepository($dao,$cache,$this->request,$instituteRepository,$bannerRepository,$LDBCourseRepository,$locationRepository,$categoryRepository,$categoryPageSponsoredRepo, $solrMultiLocationlib);
        return $categoryPageRepository;
    }
    
    public function getCategoryPageRepository()
    {
        $this->CI->load->library('categoryList/clients/CategoryPageClient');
        $dao = $this->CI->categorypageclient;
        
        $this->CI->load->library('categoryList/cache/CategoryPageCache');
        $cache = $this->CI->categorypagecache;
        
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $instituteRepository = $listingBuilder->getInstituteRepository();
        $bannerRepository = $listingBuilder->getBannerRepository();
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        
        $this->CI->load->builder('LDBCourseBuilder','LDB');
        $LDBCourseBuilder = new LDBCourseBuilder;
        $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
        
        /*
         * Load the reppository
         */ 
        $this->CI->load->repository('CategoryPageRepository','categoryList');
        $categoryPageRepository = new CategoryPageRepository($dao,$cache,$this->request,$instituteRepository,$bannerRepository,$LDBCourseRepository,$locationRepository,$categoryRepository);
        return $categoryPageRepository;
    }
    
    public function getCategoryPageSponsoredRepository()
    {
        $this->CI->load->library('listing/clients/InstituteFinderClient');
        $dao = $this->CI->institutefinderclient;
        
        $this->CI->load->library('categoryList/cache/CategoryPageCache');
        $cache = $this->CI->categorypagecache;
        
        /*
         * Load the reppository
         */ 
        $this->CI->load->repository('CategoryPageSponsoredRepository','categoryList');
        $categoryPageSponsoredRepository = new CategoryPageSponsoredRepository($dao, $cache );
        return $categoryPageSponsoredRepository;
    }
    
    public function getRotationService()
    {
        $this->CI->load->library('categoryList/cache/CategoryPageCache');
        $cache = $this->CI->categorypagecache;
        
        $this->CI->load->service('RotationService','categoryList');
        $rotationService = new RotationService($cache,$this->request);
        return $rotationService;
    }
    
    public function getBucketingService() {
    	
    	$this->CI->load->service('BucketingService','categoryList');
    	$bucketingService = new BucketingService($this->request);
    	return  $bucketingService;
    }
    
    public function getCategroyPageSolr() {
    	$filterGeneratorService = $this->getFilterGeneratorService();
    	
    	$rotationService = $this->getRotationService();
    	$sorterService = $this->getSorterService();
    	$bucketingService = $this->getBucketingService();
    	
    	$categoryPageRepository = $this->getCategoryPageRepository();
    	
    	/**Filters to Hide for current category page request ***/	
    	global $filtersToHideInSolrRequest;
    	$filtersToHideInSolrRequest = $this->filterToHide($categoryPageRepository);
    	
    	$categoryPageSolrRepository = $this->getCategoryPageSolrRepository();
    	
    	$this->CI->load->library('categoryList/cache/CategoryPageCache');
    	$cache = $this->CI->categorypagecache;
    	
    	$this->CI->load->domainClass('CategoryPageSolr','categoryList');
        
        $categoryPage = new CategoryPageSolr($this->request,$categoryPageRepository,$categoryPageSolrRepository,$filterGeneratorService,$rotationService,$sorterService,$bucketingService,$cache);
    	return $categoryPage;
    }
    
    private function filterToHide($categoryPageRepository) {
    	$filtersToHide = array();
    	if($this->request->isLDBCoursePage()) {
    		$filtersToHide = $categoryPageRepository->getAllFiltersToHide('ldbcourse',$this->request->getLDBCourseId());
    	}else if($this->request->isSubCategoryPage()) {
    		$filtersToHide = $categoryPageRepository->getAllFiltersToHide('subcategory',$this->request->getSubCategoryId());
    	} 
    	return $filtersToHide;
    }

}
