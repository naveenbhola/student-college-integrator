<?php

class FeesSorter extends AbstractSorter
{
    protected $sortKey = ABROAD_CP_SORTER_FEES;
    private $currencyConverterService;
	private $request;
    
    function __construct($params = array(),CurrencyConverterService $currencyConverterService)
    {
        parent::__construct($params);
        $this->currencyConverterService = $currencyConverterService;
		//set base currency to rupee
        $this->currencyConverterService->setBaseCurrency(1);
    }
    
    public function extractSortValue(AbroadCourse $course)
    {
        //convert fees to base currency
		$feeValueInRupee = $this->currencyConverterService->convert($course->getTotalFees()->getValue(), $course->getTotalFees()->getCurrency());
        
		return round($feeValueInRupee);
    }
}
