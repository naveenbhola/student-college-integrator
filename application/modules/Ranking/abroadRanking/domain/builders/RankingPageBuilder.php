<?php

class RankingPageBuilder {
	
	private $CI;
	private $filterGeneratorService;
	private $FilterProcessorService;
	private $rankingPageRepository;

	function __construct()
	{
	    $this->CI = &get_instance();
	    $this->CI->load->entities(array('RankingPage'),'abroadRanking');
	}
	
	public function getRankingLib()
	{
		$this->CI->load->library('abroadRanking/RankingLib');
		$rankingLib = new RankingLib();
		return $rankingLib;
	}
	
	public function getRankingPageRepository($rankingLib)
	{
		// Load dependencies for abroad Course Repository
		//$dao 			= $this->CI->load->model('listing/abroadcoursemodel','',TRUE);
		//$courseFinderDao 	= $this->CI->load->model('listing/abroadcoursefindermodel');
		//$cache 			= $this->CI->load->library('listing/cache/ListingCache');
		// Load dependencies for university Repository
	//	$univ_dao 		= $this->CI->load->model('listing/universitymodel','',TRUE);
	  //      $univ_cache 		= $this->CI->load->library('listing/cache/ListingCache');

		$this->CI->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$universityRepository = $listingBuilder->getUniversityRepository();
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
		
		// load ranking cache object
		$rankingCache = $this->CI->load->library('abroadRanking/cache/RankingCache');
	
		// load the ranking page repository
		$this->CI->load->repository('RankingPageRepository','abroadRanking');
		$this->rankingPageRepository = new RankingPageRepository($rankingLib, $universityRepository, $abroadCourseRepository, $rankingCache);
		
		return $this->rankingPageRepository;
	}
	
	public function getFilterGeneratorService()
	{
	    $this->CI->load->domainClasses(array(
			    'abroadfilters/AbstractFilter',
			    'abroadfilters/ExamsFilter',
			    'abroadfilters/FeesFilter',
			    'abroadfilters/CityFilter',
			    'abroadfilters/StateFilter',
			    'abroadfilters/CourseCategoryFilter',
			    'abroadfilters/CountryFilter'
			    ),'abroadRanking');
	
	    $this->CI->load->service('FilterGeneratorService','abroadRanking');
	    
	    
	    $currencyConversionData = $this->rankingPageRepository->getCurrencyDetials();
	    
	    $this->CI->load->service('CurrencyConverterService','abroadRanking');
	    $currencyConverterService = new CurrencyConverterService;
	    $currencyConverterService->setConversionRates($currencyConversionData);
	    
	    $this->CI->load->service('FeeRangeDeciderService','abroadRanking');
	    $feeRangeDeciderService = new FeeRangeDeciderService;
	    $feeRangeDeciderService->setFeeRanges($GLOBALS['CP_ABROAD_FEES_RANGE']);
	    $feeRangeDeciderService->setFeesRangeType('ABROAD_RS_RANGE_IN_LACS');
	    $feeRangeDeciderService->setTextForUnboundedFee("Any fees");
	
	    $defaultFilters['city'] = new CityFilter();
	    $defaultFilters['state'] = new StateFilter();
	    $defaultFilters['exams'] = new ExamsFilter();
	    $defaultFilters['fees'] = new FeesFilter($feeRangeDeciderService, $currencyConverterService);
	    $defaultFilters['country'] = new CountryFilter();
	    $defaultFilters['coursecategory'] = new CourseCategoryFilter();
	
	    $this->filterGeneratorService = new FilterGeneratorService($defaultFilters);
	    return $this->filterGeneratorService;
	}
	
	 public function getFilterProcessorService()
	{
	    $this->CI->load->domainClasses(array(
					    'abroadspecifications/Specification',
					    'abroadspecifications/ExamSpecification',
					    'abroadspecifications/FeesSpecification',
					    'abroadspecifications/CitySpecification',
					    'abroadspecifications/StateSpecification',
					    'abroadspecifications/SpecializationSpecification',
					    'abroadspecifications/CountrySpecification'
					),'abroadRanking');
	    
	    $this->FilterProcessorService = $this->CI->load->service('FilterProcessorService','abroadRanking');
		    
	    $defaultSpecifications = array(
					    'fees' 		=> new FeesSpecification,
					    'exam' 		=> new ExamSpecification,
					    'specialization'=> new SpecializationSpecification,
					    'city' 		=> new CitySpecification,
					    'state' 	=> new StateSpecification,
					    'country' 	=> new CountrySpecification
					);
	    
	    $specificationQuery = "fees AND exam AND specialization AND (city OR state OR country)";
    
	    $filterProcessorService = new FilterProcessorService($defaultSpecifications,$specificationQuery);
	    return $filterProcessorService;
	}
	
	public function getAbroadRankingPage($rankId)
	{
	$filterGeneratorService = $this->getFilterGeneratorService();
	$filterGeneratorServiceForAppliedFilter = $this->getFilterGeneratorService();
	$filterProcessorService = $this->getFilterProcessorService();
	
	$retainFilterGeneratorService['exam'] 		= $this->getFilterGeneratorService();
	$retainFilterProcessorService['exam'] 		= $this->getFilterProcessorService();
	$retainFilterGeneratorService['specialization'] 	= $this->getFilterGeneratorService();
	$retainFilterProcessorService['specialization'] 	= $this->getFilterProcessorService();
	$retainFilterGeneratorService['location'] 		= $this->getFilterGeneratorService();
	$retainFilterProcessorService['location'] 		= $this->getFilterProcessorService();
	$retainFilterGeneratorService['fees'] 			= $this->getFilterGeneratorService();
	$retainFilterProcessorService['fees'] 			= $this->getFilterProcessorService();
	
	$sorterService = $this->getSorterService();

	$this->CI->load->domainClass('AbroadRankingPage','abroadRanking');
	$rankingPage = new AbroadRankingPage(
					  $rankingPageRepository,
					  $filterGeneratorService,
					  $filterGeneratorServiceForAppliedFilter,
					  $filterProcessorService,
					  $retainFilterGeneratorService,
					  $retainFilterProcessorService,
					  $sorterService,
					  $rankId
					  );
	return $rankingPage;
	}
	
	public function getSorterService()
	{
	    $this->CI->load->domainClasses(array(
			    'abroadsorters/AbstractSorter',
			    'abroadsorters/RankSorter',
			    'abroadsorters/FeesSorter',
			    'abroadsorters/ExamScoreSorter'
			    ),'abroadRanking');
		    
	    $this->CI->load->service('SorterService','abroadRanking');
	    $this->CI->load->service('CurrencyConverterService','abroadRanking');
		    
	    $currencyConversionData = $this->rankingPageRepository->getCurrencyDetials();
	    
	    $currencyConverterService = new CurrencyConverterService;
	    $currencyConverterService->setConversionRates($currencyConversionData);
		    
	    $defaultSorters = array(
			    'rank' => new RankSorter(NULL),
			    'fees' => new FeesSorter(NULL, $currencyConverterService),
			    'exam' => new ExamScoreSorter(NULL)
			     );
	
	    $sorterService = new SorterService($defaultSorters);
	    return $sorterService;
	}
	
}
