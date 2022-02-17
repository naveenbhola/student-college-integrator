<?php

class ListingBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getInstituteRepository()
    {
        /*
         * Load dependencies for Institute Repository
         */
        $this->CI->load->library('listing/clients/InstituteClient');
        $dao = $this->CI->instituteclient;
        
        $this->CI->load->model('listing/institutemodel','',TRUE);
		$model = $this->CI->institutemodel;

        $this->CI->load->library('listing/clients/InstituteFinderClient');  
        $instituteFinderDao = $this->CI->institutefinderclient;
        
        $this->CI->load->library('listing/cache/ListingCache');  
        $cache = $this->CI->listingcache;
    
        $courseRepository = $this->getCourseRepository();
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository(); 
        
        unset($locationBuilder);

        /*
         * Load the reppository
         */ 
        
             
        $this->CI->load->repository('InstituteRepository','listing'); 
        
        $instituteRepository = new InstituteRepository($dao,$cache,$instituteFinderDao,$courseRepository,$locationRepository,$model);
        return $instituteRepository;
    }
    
    public function getAbroadInstituteRepository()
    {
        /*
         * Load dependencies for Institute Repository
         */
        //$this->CI->load->library('listing/clients/InstituteClient');
        //$dao = $this->CI->instituteclient;
        
        $this->CI->load->model('listing/abroadinstitutemodel','',TRUE);
		$dao = $this->CI->abroadinstitutemodel;

        //$this->CI->load->library('listing/clients/InstituteFinderClient');
        //$instituteFinderDao = $this->CI->institutefinderclient;
        
        $this->CI->load->library('listing/cache/ListingCache');
        $cache = $this->CI->listingcache;
    
        //$courseRepository = $this->getCourseRepository();
        
        //$this->CI->load->builder('LocationBuilder','location');
        //$locationBuilder = new LocationBuilder;
        //$locationRepository = $locationBuilder->getLocationRepository();
        
        /*
         * Load the reppository
         */ 
        $this->CI->load->repository('AbroadInstituteRepository','listing');
        $abroadInstituteRepository = new AbroadInstituteRepository($dao,$cache);
        return $abroadInstituteRepository;
    }
    
    public function getCourseRepository()
    {
        /*
         * Load dependencies for Course Repository
         */
        $this->CI->load->library('listing/clients/CourseClient');
        $dao = $this->CI->courseclient;

        $this->CI->load->model('listing/coursemodel','',TRUE);
	    $model = $this->CI->coursemodel;
        
        $this->CI->load->library('listing/clients/CourseFinderClient');
        $courseFinderDao = $this->CI->coursefinderclient;
        
        $this->CI->load->library('listing/cache/ListingCache');
        $cache = $this->CI->listingcache;
        
        /*
         * Load the repository
         */
           
        $this->CI->load->repository('CourseRepository','listing');
        $courseRepository = new CourseRepository($dao,$cache,$courseFinderDao,$model);
        return $courseRepository;
    }
    
    public function getAbroadCourseRepository($abroadCourseObjectRestructringFlag = true)
    {
        if($abroadCourseObjectRestructringFlag == true){

            $this->CI->load->model('listing/abroadcoursemodelV1','',TRUE);
            $dao = $this->CI->abroadcoursemodelV1;

            $this->CI->load->model('listing/abroadcoursefindermodel');
            $courseFinderDao = $this->CI->abroadcoursefindermodel;

            $this->CI->load->library('listing/cache/ListingCacheV2');
            $cache = $this->CI->listingcachev2;

            /*
             * Load the repository
             */
            $this->CI->load->repository('AbroadCourseRepositoryV1','listing');
            $abroadCourseRepositoryV1 = new AbroadCourseRepositoryV1($dao,$cache,$courseFinderDao);
            return $abroadCourseRepositoryV1;
        }
        else {
            /*
             * Load dependencies for Course Repository
             */
            $this->CI->load->model('listing/abroadcoursemodel', '', TRUE);
            $dao = $this->CI->abroadcoursemodel;

            $this->CI->load->model('listing/abroadcoursefindermodel');
            $courseFinderDao = $this->CI->abroadcoursefindermodel;

            $this->CI->load->library('listing/cache/ListingCache');
            $cache = $this->CI->listingcache;

            /*
             * Load the repository
             */
            $this->CI->load->repository('AbroadCourseRepository', 'listing');
            $abroadCourseRepository = new AbroadCourseRepository($dao, $cache, $courseFinderDao);
            return $abroadCourseRepository;
        }
    }


    public function getBannerRepository()
    {
        /*
         * Load entities required by Banner Repository
         */ 
        $this->CI->load->entities(array('Banner'),'listing');
        
        /*
         * Load dependencies for Course Repository
         */
        $this->CI->load->library('listing/clients/BannerClient');
        $dao = $this->CI->bannerclient;
        
        $this->CI->load->library('listing/clients/BannerFinderClient');
        $bannerFinderDao = $this->CI->bannerfinderclient;
        
        $this->CI->load->library('listing/cache/ListingCache');
        $cache = $this->CI->listingcache;
        
        /*
         * Load the repository
         */ 
        $this->CI->load->repository('BannerRepository','listing');
        $bannerRepository = new BannerRepository($dao,$cache,$bannerFinderDao);
        return $bannerRepository;
    }
	
    public function getInstituteService()
    {
	    $this->CI->load->service('InstituteService','listing');
	    return new InstituteService($this->getInstituteRepository());
    }
    
    public function getUniversityRepository($restructureObjectFlag = true)
    {
        
        if($restructureObjectFlag == true){
             $this->CI->load->model('listing/universitymodelv2','',TRUE);
             $dao = $this->CI->universitymodelv2;
             $this->CI->load->library('listing/cache/ListingCacheV2');
             $cache = $this->CI->listingcachev2;

             /*
             * Load the reppository
             */ 
            $this->CI->load->repository('UniversityRepositoryAbstract','listing'); 
            $this->CI->load->repository('UniversityRepositoryV2','listing');
            $universityRepository = new UniversityRepositoryV2($dao,$cache);
        }else{
            $this->CI->load->model('listing/universitymodel','',TRUE);
            $dao = $this->CI->universitymodel;

            $this->CI->load->library('listing/cache/ListingCache');
            $cache = $this->CI->listingcache;
                
            /*
             * Load the reppository
             */ 
            $this->CI->load->repository('UniversityRepository','listing');
            $universityRepository = new UniversityRepository($dao,$cache);
        }
        
        return $universityRepository;
    }
    
    function getListingCache()
    {
    	$this->CI->listingcache = $this->CI->load->library('listing/cache/ListingCacheV2');
    	return $this->CI->listingcache;
    }
	
	function getSnapshotCourseRepository(){
		$this->CI->load->model("listing/snapshotcoursemodel",'',TRUE);
		$dao = $this->CI->snapshotcoursemodel;
		$this->CI->load->library('listing/cache/AbroadListingCache');
		$cache = $this->CI->abroadlistingcache;
		$this->CI->load->repository('SnapshotCourseRepository','listing');
		$snapshotCourseRepository = new SnapshotCourseRepository($dao,$cache);
		return $snapshotCourseRepository;
	}

    function getCurrencyRepository(){
         $this->CI->load->model('listing/abroadlistingmodel','',TRUE);
         $dao = $this->CI->abroadlistingmodel;
         
         $this->CI->load->library('listing/cache/ListingCacheV2');
         $cache = $this->CI->listingcachev2;

        $this->CI->load->repository('CurrencyRepository','listing');
        $currencyRepository = new CurrencyRepository($dao,$cache);
        return $currencyRepository;         
    }
	
	function getSnapshotCourseCache(){
		$this->CI->load->library('listing/cache/AbroadListingCache');
		$cache = $this->CI->abroadlistingcache;
		return $cache;
	}
}