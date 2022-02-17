<?php

class RecruitingCompany
{
    private $company_id;
    private $company_name;
    private $company_order;
    private $logo_url;
    
    function __construct()
    {
        
    }
    
    public function getName()
    {
        return trim($this->company_name);
        
    }
	
	public function getLogoURL()
    {
            if(!empty($this->logo_url)){
                return MEDIAHOSTURL.$this->logo_url;
            }
        return;
        
    }
	
	
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}