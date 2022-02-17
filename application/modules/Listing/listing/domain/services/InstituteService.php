<?php

class InstituteService
{
    private $_instituteRepository;
    
    function __construct(InstituteRepository $instituteRepository)
    {
        $this->_instituteRepository = $instituteRepository;
    }
    
    public function isInstituteStudyAbroad($instituteId)
    {
        $isAbroad = FALSE;
        
        $instituteId = (int) $instituteId;
        $institute = $this->_instituteRepository->find($instituteId);
        if($institute) {
            $instituteCountry = $institute->getMainLocation()->getCountry()->getId();
            if($instituteCountry > 2) {
                $isAbroad = TRUE;
            }    
        }
        
        return $isAbroad;
    }
}