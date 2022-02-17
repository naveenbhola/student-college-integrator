<?php

class AbroadCategoryPageBuilder
{
    private $CI;
    private $request;
    
    function __construct($params = '')
    {
		$this->CI = & get_instance();
        $this->CI->load->library('categoryList/AbroadCategoryPageRequest');
		
        $this->request = new AbroadCategoryPageRequest($params);
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function setRequest(AbroadCategoryPageRequest $request)
    {
        $this->request = $request;
    }
    
    public function getCategoryPage()
    {
		$categoryPageRepository = $this->getCategoryPageRepository();
		
		$this->CI->load->library('categoryList/cache/CategoryPageCache');
		$cache = $this->CI->categorypagecache;
		$rotationService = $this->getRotationService();
		
		if($this->request->useSolrToBuildCategoryPage()){
			$this->CI->load->domainClass('AbroadCategoryPageSolr','categoryList');
			$categoryPage = new AbroadCategoryPageSolr($this->request,
					   $categoryPageRepository,
					   $cache,
					   $rotationService);
		}else{
            $filterGeneratorService = $this->getFilterGeneratorService();
            $filterGeneratorServiceForAppliedFilter = $this->getFilterGeneratorService();
            $filterProcessorService = $this->getFilterProcessorService();
            
            $retainFilterGeneratorService['exam']       = $this->getFilterGeneratorService();
            $retainFilterProcessorService['exam']       = $this->getFilterProcessorService();
            $retainFilterGeneratorService['examsScore'] = $this->getFilterGeneratorService();
            $retainFilterProcessorService['examsScore'] = $this->getFilterProcessorService();
            $retainFilterGeneratorService['examsMinScore'] = $this->getFilterGeneratorService();
            $retainFilterProcessorService['examsMinScore'] = $this->getFilterProcessorService();
            $retainFilterGeneratorService['specialization']     = $this->getFilterGeneratorService();
            $retainFilterProcessorService['specialization']     = $this->getFilterProcessorService();
            $retainFilterGeneratorService['location']       = $this->getFilterGeneratorService();
            $retainFilterProcessorService['location']       = $this->getFilterProcessorService();
            $retainFilterGeneratorService['moreoption']         = $this->getFilterGeneratorService();
            $retainFilterProcessorService['moreoption']         = $this->getFilterProcessorService();
            $retainFilterGeneratorService['fees']           = $this->getFilterGeneratorService();
            $retainFilterProcessorService['fees']           = $this->getFilterProcessorService();
            
            $sorterService = $this->getSorterService();
			$this->CI->load->domainClass('AbroadCategoryPage','categoryList');
			$categoryPage = new AbroadCategoryPage($this->request,
					   $categoryPageRepository,
					   $filterGeneratorService,
					   $filterGeneratorServiceForAppliedFilter,
					   $filterProcessorService,
					   $sorterService,
					   $cache,
					   $retainFilterGeneratorService,
					   $retainFilterProcessorService,
					   $rotationService );
		}
		return $categoryPage;
	}
  
    public function getCategoryPageRepository()
    {
        
    	$this->CI->load->model('categoryList/abroadcategorypagemodel', '', TRUE);
        $dao = new abroadcategorypagemodel();
        
         $this->CI->load->library('categoryList/cache/CategoryPageCache');
        $cache = $this->CI->categorypagecache;
       
 
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $instituteRepository = $listingBuilder->getAbroadInstituteRepository();
        
        $universityRepository = $listingBuilder->getUniversityRepository();
        
        $courseRepository = $listingBuilder->getAbroadCourseRepository();
        
        $bannerRepository = $listingBuilder->getBannerRepository(); //Banner Repo

        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        
        $this->CI->load->builder('LDBCourseBuilder','LDB');
        $LDBCourseBuilder = new LDBCourseBuilder;
        $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
        
        $this->CI->load->library('listing/ListingErrorReportingLib');
        $listingErrorReportingLib = new ListingErrorReportingLib();
        
        /*
         * Load the repository
         */
		if($this->request->useSolrToBuildCategoryPage())
		{
			$this->CI->load->repository('AbroadCategoryPageSolrRepository','categoryList');
			$solrRequestGenerator = $this->CI->load->library('categoryList/solr/AbroadCategoryPageSolrRequestGenerator');
			$solrResponseParser = $this->CI->load->library('categoryList/solr/AbroadCategoryPageSolrResponseParser');
			$categoryPageRepository = new AbroadCategoryPageSolrRepository($dao,$cache,$this->request,$universityRepository,$instituteRepository,$courseRepository,$LDBCourseRepository,$locationRepository,$categoryRepository,$bannerRepository,$listingErrorReportingLib,$solrRequestGenerator,$solrResponseParser);
		}else{
			$this->CI->load->repository('AbroadCategoryPageRepository','categoryList');
			$categoryPageRepository = new AbroadCategoryPageRepository($dao,$cache,$this->request,$universityRepository,$instituteRepository,$courseRepository,$LDBCourseRepository,$locationRepository,$categoryRepository,$bannerRepository,$listingErrorReportingLib);
		}
        return $categoryPageRepository;
    }
    
    public function getFilterGeneratorService()
    {
    	$this->CI->load->domainClasses(array(
    			'abroadfilters/AbstractFilter',
    			'abroadfilters/ExamsFilter',
                'abroadfilters/ExamsScoreFilter',
				'abroadfilters/ExamsMinScoreFilter',
    			'abroadfilters/FeesFilter',
    			'abroadfilters/CityFilter',
    			'abroadfilters/StateFilter',
    			'abroadfilters/MoreOptionsFilter',
    			'abroadfilters/CourseCategoryFilter',
    			'abroadfilters/CountryFilter'
    			),'categoryList');
    
    	$this->CI->load->service('FilterGeneratorService','categoryList');
    	
    	$categoryPageRepository = $this->getCategoryPageRepository();
    	
    	$currencyConversionData = $categoryPageRepository->getCurrencyDetials();
    	
    	$this->CI->load->service('CurrencyConverterService','categoryList');
    	$currencyConverterService = new CurrencyConverterService;
       	$currencyConverterService->setConversionRates($currencyConversionData);
    	
       	$this->CI->load->service('FeeRangeDeciderService','categoryList');
       	$feeRangeDeciderService = new FeeRangeDeciderService;
       	$feeRangeDeciderService->setFeeRanges($GLOBALS['CP_ABROAD_FEES_RANGE']);
       	$feeRangeDeciderService->setFeesRangeType('ABROAD_RS_RANGE_IN_LACS');
       	$feeRangeDeciderService->setTextForUnboundedFee("Any fees");
    
        $defaultFilters['city'] = new CityFilter();
        $defaultFilters['state'] = new StateFilter();
    	$defaultFilters['exams'] = new ExamsFilter();
        $defaultFilters['examsScore'] = new ExamsScoreFilter();
		$defaultFilters['examsMinScore'] = new ExamsMinScoreFilter();
    	$defaultFilters['fees'] = new FeesFilter($feeRangeDeciderService, $currencyConverterService);
    	$defaultFilters['moreoptions'] = new MoreOptionsFilter();
    	$defaultFilters['coursecategory'] = new CourseCategoryFilter();
    	$defaultFilters['country'] = new CountryFilter();
    
    	$filterGeneratorService = new FilterGeneratorService($defaultFilters);
    	return $filterGeneratorService;
    }
    
    
    public function getSorterService()
    {
    	$this->CI->load->domainClasses(array(
    			'abroadsorters/AbstractSorter',
    			'abroadsorters/ViewCountSorter',
				'abroadsorters/FeesSorter',
				'abroadsorters/ExamScoreSorter'
    			),'categoryList');
		
    	$this->CI->load->service('SorterService','categoryList');
		$this->CI->load->service('CurrencyConverterService','categoryList');
		
		$this->CI->load->library('listing/AbroadListingCommonLib');
		$abroadListingCommonLib = $this->CI->abroadlistingcommonlib;
		
		$categoryPageRepository = $this->getCategoryPageRepository();
    	$currencyConversionData = $categoryPageRepository->getCurrencyDetials();
    	
		$currencyConverterService = new CurrencyConverterService;
       	$currencyConverterService->setConversionRates($currencyConversionData);
		
    	$defaultSorters = array(
    			'viewCount' => new ViewCountSorter(NULL, $abroadListingCommonLib),
				'fees' => new FeesSorter(NULL, $currencyConverterService),
				'exam' => new ExamScoreSorter(NULL)
    			  	);
    
    	$sorterService = new SorterService($defaultSorters);
    	return $sorterService;
    }
    
    public function getFilterProcessorService()
    {
        $this->CI->load->domainClasses(array(
                                        'abroadspecifications/Specification',
                                        'abroadspecifications/ExamSpecification',
                                        'abroadspecifications/ExamsScoreSpecification',
										'abroadspecifications/ExamsMinScoreSpecification',
                                        'abroadspecifications/FeesSpecification',
                                        'abroadspecifications/CitySpecification',
                                        'abroadspecifications/StateSpecification',
                                        'abroadspecifications/MoreOptionSpecification',
                                        'abroadspecifications/SpecializationSpecification',
                                        'abroadspecifications/CountrySpecification'
                                    ),'categoryList');
        
        $this->CI->load->service('FilterProcessorService','categoryList');
                
        $defaultSpecifications = array(
					'fees' 		=> new FeesSpecification,
					'exam' 		=> new ExamSpecification,
                    'examsScore'=> new ExamsScoreSpecification,
					'examsMinScore'=> new ExamsMinScoreSpecification,
					'specialization'=> new SpecializationSpecification,
					'moreoption' 	=> new MoreOptionSpecification,
					'city' 		=> new CitySpecification,
					'state' 	=> new StateSpecification,
					'country' 	=> new CountrySpecification
				    );
	
        //$specificationQuery = "mode AND courseLevel AND (duration OR durationRange) AND degreePref AND exams AND AIMARating AND classTimings AND (locality OR city OR state) AND zone AND country AND courseexams AND fees AND lastmodifieddate";
    	//$specificationQuery = "fees AND exam AND specialization AND moreoption AND city AND state AND country";
    	$specificationQuery = "fees AND exam AND examsScore AND examsMinScore AND specialization AND moreoption AND (city OR state OR country)";
        $filterProcessorService = new FilterProcessorService($defaultSpecifications,$specificationQuery,$snapshotSpecificationQuery);
        return $filterProcessorService;
    }
    
    public function getRotationService()
    {
    	$this->CI->load->library('categoryList/cache/CategoryPageCache');
    	$cache = $this->CI->categorypagecache;
    
    	$this->CI->load->service('RotationService','categoryList');
    	$rotationService = new RotationService($cache,$this->request);
    	return $rotationService;
    }

    public function getProcessedCountriesForBMSInventory($countryIds) {
        $rotationService = $this->getRotationService();
        return ($rotationService->rotateCountryList($countryIds));
    }
    
	/*  Reason :For rotating country array for all country page 
	 * Params : $countryIds
	 *  author : Abhay
	*/
     public function getProcessedCountriesForCountryPage($countryIds) {
        $rotationService = $this->getRotationService();
        return ($rotationService->rotateCountryListForCountryPageBanner($countryIds));
    }    
    
}
