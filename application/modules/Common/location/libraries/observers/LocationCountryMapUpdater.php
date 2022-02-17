<?php

class LocationCountryMapUpdater
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
        $this->updateLocationCountryMap();   
    }
    
    public function updateLocationCountryMap()
    {
        $this->_locationRepository->disableCaching();
        $countries = $this->_locationRepository->getCountries();
        
        $lineBreak = "\n";
        
        $data = '<?php'.$lineBreak.$lineBreak;
        $data .= '$shikshaCountryMap = array('.$lineBreak;
        
        $countryList = array();
        foreach($countries as $country) {
            $countryList[] = $country->getId()." =>  array('name' => '".$country->getName()."', 'region' => ".intval($country->getRegionId()).")";
        }
        
        $data .= implode(",".$lineBreak,$countryList);
        $data .= $lineBreak.');';
        
        $this->_fileDeployer->setFile('globalconfig/locationCountryMap.php','CodeIgniter/globalconfig/locationCountryMap.php');
        try {
            $this->_fileDeployer->deploy($data);
        }
        catch(Exception $e) {
            error_log($e->getMessage());
        }
    }
}