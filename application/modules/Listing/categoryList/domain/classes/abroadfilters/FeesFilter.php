<?php

class FeesFilter extends AbstractFilter{
	private $feeRangeDeciderService;
	private $currencyConverterService;
	
	
	function __construct($feeRangeDeciderService, $currencyConverterService){
		$this->feeRangeDeciderService 	= $feeRangeDeciderService;
		$this->currencyConverterService = $currencyConverterService;
		parent::__construct();
	}
    
	public function getFilteredValues(){
		return $this->values;
	}
    
	public function extractValue(University $university,AbroadInstitute $institute,AbroadCourse $course){
		$feeRanges = $GLOBALS['CP_ABROAD_FEES_RANGE']['ABROAD_RS_RANGE_IN_LACS'];
		$feesRange 	= false;
		$fees 		= false;
		$this->currencyConverterService->setBaseCurrency(1);
		$courseFeeValue 	= $course->getTotalFees()->getValue();
		$courseFeeCurrency  = $course->getTotalFees()->getCurrencyEntity()->getId();
		if(!empty($courseFeeValue) && !empty($courseFeeCurrency)){
			$fees = $this->currencyConverterService->convert((int)$courseFeeValue, $courseFeeCurrency);
		}
		if(!empty($fees)){
			foreach ($feeRanges as $feeRangeValue => $feeRangeText){
				$feeLimits = explode('-', $feeRangeValue);
				if(floatVal($fees) < floatVal($feeLimits[1])){
					$feesRange[$feeRangeValue] =  $feeRangeText;
				}else{
					continue;
				}
			}
		}
		if(!empty($feesRange)){
			$feeValue = array_keys($feesRange);
			foreach($feeValue as $value){
				$this->values[$value] = $feesRange[$value];
			}
		}
		return $feesRange;
	}
	
	public function extractSnapshotValue(University $university,SnapshotCourse $course)
	{
		return array();	//SnapshotCourses do not have exams.
	}
    
	public function addValue(University $university,AbroadInstitute $institute,AbroadCourse $course){
		$feesRange = $this->extractValue($university,$institute,$course);
		if(!empty($feesRange)){
			$feeValue = array_keys($feesRange);
			foreach($feeValue as $value){
				$this->values[$value] = $feesRange[$value];
			}
		}
	}
	
	public function addSnapshotValue(University $university,SnapshotCourse $course){
		//Snapshot Courses do not have fees.
	}
	
}
