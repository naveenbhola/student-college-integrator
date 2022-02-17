<?php

class UniqueZoneNameValidator extends AbstractValidator
{
    const ERROR_MSG = "The zone '%s' already exists.";
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function validate($zone)
    {
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder();
        $locationDao = $locationBuilder->getLocationDao();
        
        if(!$locationDao->checkUniqueZoneName($zone)) {
            $this->setErrorMsg(sprintf(self::ERROR_MSG,$zone));
            return FALSE;
        }
        
        return TRUE;
    }
}