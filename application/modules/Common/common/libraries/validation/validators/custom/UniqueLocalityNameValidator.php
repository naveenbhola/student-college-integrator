<?php

class UniqueLocalityNameValidator extends AbstractValidator
{
    const ERROR_MSG = "The locality '%s' already exists.";
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function validate($locality,$value,$data)
    {
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder();
        $locationDao = $locationBuilder->getLocationDao();
        
        $zone = $data['Zone'];
        if(!$locationDao->checkUniqueLocalityName($locality,$zone)) {
            $this->setErrorMsg(sprintf(self::ERROR_MSG,$locality));
            return FALSE;
        }
        
        return TRUE;
    }
}