<?php

class CountriesArrayUpdater
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
        $this->updateCountriesArray();
    }
    
    public function updateCountriesArray()
    {
        global $countries;
        
        $this->_locationRepository->disableCaching();
        $dbCountries = $this->_locationRepository->getCountries();
        
        $existingCountries = array();
        foreach($countries as $country) {
            $existingCountries[] = $country['id'];
        }
        
        $newCountries = $countries;
        foreach($dbCountries as $country) {
            if(!in_array($country->getId(),$existingCountries)) {
                $key = strtolower(str_replace(" ","",$country->getName()));
                $newCountries[$key] = array('name' => $country->getName(), 'flagImage' => '','value' => $country->getName(),'id' => $country->getId());
            }
        }
        
        $lineBreak = "\n";
        
        $data = '<?php'.$lineBreak.$lineBreak;
        $data .= '$countries = array('.$lineBreak;
        
        $countryList = array();
        foreach($newCountries as $countryKey => $country) {
            $countryList[] = "'".$countryKey."' =>  array(".$lineBreak.
                                                                        "'name' => '".$country['name']."',".$lineBreak
                                                                        ."'flagImage' => '".$country['flagImage']."',".$lineBreak
                                                                        ."'value' => '".$country['value']."',".$lineBreak
                                                                        ."'id' => '".$country['id']."'".$lineBreak
                                                        .")";
        }
        
        $data .= implode(",".$lineBreak,$countryList);
        $data .= $lineBreak.');';
        
        $this->_fileDeployer->setFile('globalconfig/countriesarray.php','CodeIgniter/globalconfig/countriesarray.php');
        try {
             $this->_fileDeployer->deploy($data);
        }
        catch(Exception $e) {
            error_log($e->getMessage());
        }
    }
}
