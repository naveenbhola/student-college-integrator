<?php

class CurrencyConverterService
{
    private $conversionRates = array(
        'INR' => array(
                        'INR' => 1,    
                        'USD' => 50,
                        'AUD' => 50,
                        'CAD' => 50,
                        'SGD' => 40,
                        'GBP' => 80,
                        'NZD' => 40,
                        'EUR' => 70
                    )
    );
    
    private $baseCurrency;
    
    function __construct($baseCurrency)
    {
        $this->baseCurrency = $baseCurrency;    
    }
    
    public function setConversionRates($conversionRates)
    {
    	$this->conversionRates = $conversionRates;
    } 

    public function setBaseCurrency($baseCurrency)
    {
        $this->baseCurrency = $baseCurrency;    
    }
    
    public function convert($value,$currency)
    {
        if(!array_key_exists($this->baseCurrency,$this->conversionRates)) {
            throw new Exception("Invalid base currency");
        }
        
        $conversionRates = $this->conversionRates[$this->baseCurrency];
        
        if(!array_key_exists($currency,$conversionRates)) {
            throw new Exception("Invalid currency");
        }
        
        $conversionRate = $conversionRates[$currency];
        $valueInBaseCurrency = $conversionRate * $value;
        
        return $valueInBaseCurrency;
    }
}