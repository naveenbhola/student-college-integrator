<?php

class CourseFees
{
    private $fees_unit;
    private $fees_value;
    private $feestypes;
	private $currency;
    private $show_fees_disclaimer; // LF-4327
    
    function __construct()
    {
        
    }
    
    public function getValue()
    {
        $feesValue = (int) $this->fees_value;
        if($feesValue > 0) {
            return $this->fees_value;
        }
    }
    
    public function getCurrency()
    {
        return $this->fees_unit;
    }

    public function getCurrencySymbol(){
        $feesCurrencySymbol = $this->fees_unit;
        if($this->fees_unit == 'INR'){
            $feesCurrencySymbol = '&#8377;';
        }
        return $feesCurrencySymbol;   
    }

    public function getFeesTypes() {
		$feeTypeList = array();
		if(!empty($this->feestypes)){
			$feesTypes = explode(",", $this->feestypes);
			foreach($feesTypes as $fee){
				$explode = explode("_", $fee);
				$feeTypeList[] = ucfirst(trim($explode[0]));
			}
			asort($feeTypeList);
		}
		return $feeTypeList;
    }

    // LF-4327 changes
    public function setFeeDisclaimer($fees_disclaimer){
        $this->show_fees_disclaimer = $fees_disclaimer;
    }

    public function getFeeDisclaimer(){
        return $this->show_fees_disclaimer;
    }
    // LF-4327 changes end

    public function __toString()
    {
        $feesValue = (int) $this->fees_value;
        if($this->fees_unit && $feesValue > 0) {
            return $this->fees_value?$this->fees_unit.' '.$this->fees_value:'';
        }
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
	
	public function cleanForCategorypage(){
		unset($this->feestypes);
	}
}