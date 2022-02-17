<?php

class UniqueCountryNameValidator extends AbstractValidator
{
    const ERROR_MSG = "The country '%s' already exists.";
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function validate($countryName)
    {
        $countryName = trim($countryName);
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder();
        $locationDao = $locationBuilder->getLocationDao();
        
        if(!$locationDao->checkUniqueCountryName($countryName)) {
            $this->setErrorMsg(sprintf(self::ERROR_MSG,$countryName));
            return FALSE;
        }
        
        return TRUE;
    }
}