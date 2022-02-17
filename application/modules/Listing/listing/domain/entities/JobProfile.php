<?php

class JobProfile
{
    private $percentage_employed;
    private $average_salary;
    private $average_salary_currency_id;
    private $popular_sectors;
    private $internships;
    private $internships_link;
    private $career_services_link;
    private $currency;
    
    function __construct()
    {
        
    }
    
    public function getPercentageEmployed()
    {
        return $this->percentage_employed;
    }
    
    public function getAverageSalary()
    {
        return $this->average_salary;
    }
    
    public function getAverageSalaryCurrencyId()
    {
        return $this->average_salary_currency_id;
    }
    
    public function getPopularSectors()
    {
        return $this->popular_sectors;
    }
    
    public function getInternships()
    {
        return $this->internships;
    }
    
    public function getInternshipsLink()
    {
        return $this->internships_link;
    }
    
    public function getCareerServicesLink()
    {
        return $this->career_services_link;
    }
    
    public function setCurrencyEntity(Currency $currency){
		$this->currency =  $currency;
	}
	
	public function getCurrencyEntity(){
		return $this->currency;
	}
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}
