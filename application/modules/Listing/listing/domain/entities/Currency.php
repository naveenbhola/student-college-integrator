<?php

class Currency
{
    private $currency_id;
    private $currency_code;
    private $currency_name;
	private $currency_country_id;
    
    function __construct()
    {
        
    }
    
	public function getId(){
		return $this->currency_id;
	}
	
	public function getCode(){
		return $this->currency_code;
	}
	
	public function getName(){
		return $this->currency_name;
	}
	
	public function getCountryId(){
		return $this->currency_country_id;
	}
	
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}