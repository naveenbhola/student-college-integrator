<?php

class CampusAccommodation
{
    private $accommodation_details;
    private $accommodation_website_url;
    private $living_expenses;
    private $currency;
    private $currency_entity;
    private $living_expense_details;
    private $living_expense_website_url;
    
    function __construct()
    {
        
    }
    
    public function getAccommodationDetails()
    {
        return $this->accommodation_details;
    }
    
    public function getAccommodationWebsiteURL()
    {
        return $this->accommodation_website_url;
    }
    
    public function getLivingExpenses()
    {
        return $this->living_expenses;
    }
    
    public function getLivingExpenseCurrency()
    {
        return $this->currency;
    }
    
    public function getLivingExpenseDetails()
    {
        return $this->living_expense_details;
    }
    
    public function getLivingExpenseWebsiteURL()
    {
        return $this->living_expense_website_url;
    }
    
    public function setCurrencyEntity(Currency $currency_entity){
		$this->currency_entity =  $currency_entity;
	}
	
	public function getCurrencyEntity(){
		return $this->currency_entity;
	}
	
	/**
	 *  Below funtion checks if any of the field  have data or not.
	 */
	public function hasAnyInformation() {
		return ! empty ( $this->accommodation_details ) || 
				! empty ( $this->accommodation_website_url );
	}
	
    function __set($property,$value)
    {
        $this->$property = $value;
    }
	
	public function cleanForCategoryPage(){
		unset($this->living_expenses);
		unset($this->currency);
		unset($this->currency_entity);
		unset($this->living_expense_details);
		unset($this->living_expense_website_url);
	}
}