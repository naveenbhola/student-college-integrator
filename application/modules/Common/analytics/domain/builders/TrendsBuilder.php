<?php

class TrendsBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getTrendsRepository()
    {
        /*
         * Load dependencies for Repository
         */
        $this->CI->load->model('analytics/trendsmodel','',TRUE);
		$model = $this->CI->trendsmodel;
        
        $this->CI->load->library('analytics/cache/TrendsCache');  
        $cache = $this->CI->trendscache;

        $this->CI->load->library('analytics/elastic/TrendsElasticLibrary');  
        $dao = $this->CI->trendselasticlibrary;
        
        /*
         * Load the repository
         */ 
        $this->CI->load->repository('TrendsRepository','analytics');

        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        
        $trendsRepository = new TrendsRepository($dao,$cache,$model,$locationRepository);
        return $trendsRepository;
    }
}