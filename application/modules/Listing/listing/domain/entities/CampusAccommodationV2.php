<?php

class CampusAccommodationV2
{
    private $accommodation_details;
    private $accommodation_website_url;
    private $currency_entity;

    //new Object value
    private $livingExpensesValue;
    private $livingExpensesCurrency;
    private $livingExpensesDesc;
    private $livingExpensesURL;
    
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
        return $this->livingExpensesValue;
    }
    
    public function getLivingExpenseCurrency()
    {
        return $this->livingExpensesCurrency;
    }
    
    public function getLivingExpenseDetails()
    {
        return $this->livingExpensesDesc;
    }
    
    public function getLivingExpenseWebsiteURL()
    {
        return $this->livingExpensesURL;
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
		unset($this->livingExpensesValue);
		unset($this->livingExpensesCurrency);
		unset($this->currency_entity);
		unset($this->livingExpensesDesc);
		unset($this->livingExpensesURL);
	}
}