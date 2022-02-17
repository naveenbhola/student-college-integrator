<?php

class LocationBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = & get_instance();    
    }
    
    public function getLocationDao()
    {
        $this->CI->load->library('location/clients/LocationClient');
        
        $dao = $this->CI->locationclient;
        return $dao;
    }
    
    public function getLocationCache()
    {
        $this->CI->load->library('location/cache/LocationCache');
        
        $cache = $this->CI->locationcache;
        return $cache;
    }
    
    public function getLocationRepository()
    {
        /*
         * Load dependencies for Location Repository
         */
        $dao = $this->getLocationDao();
              
        $cache = $this->getLocationCache();
        
        /*
         * Load the reppository
         */ 
        
        $this->CI->load->repository('LocationRepository','location');
        $locationRepository = new LocationRepository($dao,$cache);

        return $locationRepository;
    }
}