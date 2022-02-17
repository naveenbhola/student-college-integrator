<?php
class CityFilter extends AbstractFilter {
	function __construct() {
		parent::__construct ();
	}
	
	public function getFilteredValues() {
		asort ( $this->values );
		return $this->values;
	}
	
	public function extractValue(University $university, AbroadCourse $course) {
		$cities = array ();
		$locations = $university->getLocations ();
		
		foreach ( $locations as $location ) {
			$city = $location->getCity ();
			
			if (! empty ( $city )) {
				$cityId = $city->getId ();
				if (! empty ( $cityId ) && $cityId > 0) {
					$cities [] = $cityId;
				}
			}
		}
		return $cities;
	}
	
	
	public function addValue(University $university, AbroadCourse $course) {
		$locations = $university->getLocations ();
		
		foreach ( $locations as $location ) {
			$city = $location->getCity ();
			
			if (! empty ( $city )) {
				$cityId = $city->getId ();
				if (! empty ( $cityId ) && $cityId > 0) {
					$this->values [$cityId] = $city->getName ();
				}
			}
		}
	}
	
	
}
