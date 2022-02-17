<?php

class FeesFilter extends AbstractFilter
{
	private $feeRangeDeciderService;
	private $currencyConverterService;
	private $request;
	
    function __construct($feeRangeDeciderService, $currencyConverterService, $request)
    {
		$this->feeRangeDeciderService 	= $feeRangeDeciderService;
		$this->currencyConverterService = $currencyConverterService;
		$this->request = $request;
        parent::__construct();
    }
    
    public function getFilteredValues()
    {
		$originalRanges = $GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'];
		$GLOBALS['originalOrder'] = array_flip($originalRanges);
		$GLOBALS['originalOrder']['No Limit'] = 10000000000; //Very large limit
		uasort($this->values, function($a, $b){ return $GLOBALS['originalOrder'][$a] - $GLOBALS['originalOrder'][$b]; });
		$this->values['No Limit'] = "No Limit"; //No limit should come in any case
		return $this->values;
    }
    
    public function extractValue(Institute $institute,Course $course)
    {
		$feesRange 	= false;
		$fees 		= false;
		$course->setCurrentLocations($this->request);
		$this->currencyConverterService->setBaseCurrency('INR');
		
		$displayLocation = $this->mainLocation($course);
		if(!empty($displayLocation)) {
			$courseFeeValue 	= $course->getFees($displayLocation->getLocationId())->getValue();
			$courseFeeCurrency  = $course->getFees($displayLocation->getLocationId())->getCurrency();
			if(!empty($courseFeeValue) && !empty($courseFeeCurrency)){
				$fees = $this->currencyConverterService->convert((int)$courseFeeValue, $courseFeeCurrency);
			}
		}
		
		if(!empty($fees)){
			$this->feeRangeDeciderService->setFeesRangeType('RS_RANGE_IN_LACS');
			$feesRange = $this->feeRangeDeciderService->feesRange($fees);
		} else {
			$feesRange = "No Limit"; //If fees is not available than mark it as no limit 
		}
		
		return $feesRange;
    }
    
    public function addValue(Institute $institute,Course $course)
    {
		$feesRange = $this->extractValue($institute,$course);
		if(!empty($feesRange))
		{
			$this->values[$feesRange] = $feesRange;
		}
	}
	
	private function mainLocation(Course $course){
		$displayLocation = $course->getCurrentMainLocation();
		$courseLocations = $course->getCurrentLocations();
		$appliedFilters  = $this->request->getAppliedFilters();
		if($appliedFilters){
			foreach($courseLocations as $location){
				$localityId = $location->getLocality() ? $location->getLocality()->getId() : 0;
				if(in_array($localityId, $appliedFilters['locality'])){
					$displayLocation = $location;
					break;
				}
				if(in_array($location->getCity()->getId(), $appliedFilters['city'])){
					$displayLocation = $location;
					break;
				}
			}
		}
		if(!$displayLocation){
			$displayLocation = $course->getMainLocation();
		}
		return $displayLocation;
	}
	
	public function setFilterValues($courseNormalisedFeesArr = array())
	{
		foreach($courseNormalisedFeesArr as $fees=>$count)
		{
			if(!empty($fees))
			{
				$this->feeRangeDeciderService->setFeesRangeType('RS_RANGE_IN_LACS');
				$feesRange = $this->feeRangeDeciderService->feesRange($fees);
			}
			else
			{
				$feesRange = "No Limit"; //If fees is not available than mark it as no limit 
			}
			
			if(!empty($feesRange))
			{
				$this->values[$feesRange] = $feesRange;
				$this->count[$feesRange] += $count;
			}
		}
		
	}
	
	public function getCount()
	{
		return $this->count;
	}
	
}
