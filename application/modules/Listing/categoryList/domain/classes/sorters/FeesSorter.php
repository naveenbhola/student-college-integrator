<?php

class FeesSorter extends AbstractSorter
{
    protected $sortKey = CP_SORTER_FEES;
    private $currencyConverterService;
	private $request;
    
    function __construct($params = array(),CurrencyConverterService $currencyConverterService, $request)
    {
        parent::__construct($params);
        $this->currencyConverterService = $currencyConverterService;
		$this->request = $request;
    }
    
    public function extractSortValue(Institute $institute,Course $course)
    {
		
		$fees 		= 0;
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
		return $fees;
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
}
