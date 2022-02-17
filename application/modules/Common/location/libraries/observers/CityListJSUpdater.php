<?php

class CityListJSUpdater
{
    private $_locationRepository;
    private $_fileDeployer;
    
    function __construct()
    {
        $CI = & get_instance();
        
        $CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder();
        $this->_locationRepository = $locationBuilder->getLocationRepository();
        
        $CI->load->library('common/FileDeployer');
        $this->_fileDeployer = new FileDeployer;
    }
    
    public function update()
    {
        $this->updateCityListJS();
    }
    
    public function updateCityListJS()
    {
        /*
         * Get all cities afresh from database and prepare cityList json
         */
        $this->_locationRepository->disableCaching();
        $cities = $this->_locationRepository->getCities();
        
        $cityMapping = array();
        foreach($cities as $city) {
            if(trim($city->getName()) && intval($city->getCountryId()) > 0 && intval($city->getId()) > 0) {
                $cityMapping[$city->getCountryId()][$city->getId()] = $city->getName();
            }
        }
        
        $jsFileContent = json_encode($cityMapping);
    
        $this->_fileDeployer->setFile('public/js/cityList.js','html/js/rawJs/cityList.js');
        try {
            $fileDeployer->deploy($data);
        }
        catch(Exception $e) {
            error_log($e->getMessage());
        }
    }
}