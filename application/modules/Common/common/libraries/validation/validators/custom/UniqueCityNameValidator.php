<?php

class UniqueCityNameValidator extends AbstractValidator
{
    const ERROR_MSG = "The city '%s' already exists.";
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function validate($cityName)
    {
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder();
        $locationDao = $locationBuilder->getLocationDao();
        
        if(!$locationDao->checkUniqueCityName($cityName)) {
            $this->setErrorMsg(sprintf(self::ERROR_MSG,$cityName));
            return FALSE;
        }
        
        return TRUE;
    }
}