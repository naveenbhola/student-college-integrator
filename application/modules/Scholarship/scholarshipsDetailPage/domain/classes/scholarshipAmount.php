<?php

class scholarshipAmount {
    private $totalAmountPayout;
    private $amountCurrency;
    private $amountInterval;
    private $expensesCovered;
    private $amountDescription;
    private $amountDescriptionLink;
    private $convertedTotalAmountPayout;
    public function getTotalAmountPayout(){
        if(!isset($this->totalAmountPayout)){
            throw new Exception("Please load the Scholarship Object with 'totalAmountPayout' field in amount section", 1);
        }
    	return $this->totalAmountPayout;
    }
    public function getConvertedTotalAmountPayout(){
        if(!isset($this->convertedTotalAmountPayout)){
            throw new Exception("Please load the Scholarship Object with 'convertedTotalAmountPayout' field in amount section", 1);
        }
    	return $this->convertedTotalAmountPayout;
    }
    public function getAmountCurrency(){
        if(!isset($this->amountCurrency)){
            throw new Exception("Please load the Scholarship Object with 'amountCurrency' field in amount section", 1);
        }
        return $this->amountCurrency;
    }
    public function getAmountInterval(){
        if(!isset($this->amountInterval)){
            throw new Exception("Please load the Scholarship Object with 'amountInterval' field in amount section", 1);
        }
        return $this->amountInterval;
    }
    public function getExpensesCovered(){
        if(!isset($this->expensesCovered)){
            throw new Exception("Please load the Scholarship Object with 'expensesCovered' field in amount section", 1);
        }
        return $this->expensesCovered;
    }
    public function getAmountDescription(){
        if(!isset($this->amountDescription)){
            throw new Exception("Please load the Scholarship Object with 'amountDescription' field in amount section", 1);
        }
        return $this->amountDescription;
    }
    public function getAmountDescriptionLink(){
        if(!isset($this->amountDescriptionLink)){
            throw new Exception("Please load the Scholarship Object with 'amountDescriptionLink' field in amount section", 1);
        }
        return $this->amountDescriptionLink;
    }
        function __set($property,$value){
        $this->$property = $value;        
    }
}
