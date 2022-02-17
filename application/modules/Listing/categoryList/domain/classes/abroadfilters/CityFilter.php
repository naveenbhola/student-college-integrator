<?php
class CityFilter extends AbstractFilter {
	function __construct() {
		parent::__construct ();
	}
	
	public function getFilteredValues() {
		asort ( $this->values );
		return $this->values;
	}
	
	public function extractValue(University $university, AbroadInstitute $institute, AbroadCourse $course) {
		$cities = array ();
		$locations = $university->getLocations ();
		foreach ( $locations as $location ) {
			//if state doesn't exists then populate the city
			$stateId 	= $location->getState()->getId();
			$cityId 	= $location->getCity ()->getId();
			$city = $location->getCity ();
			
			if(empty($stateId) && !empty($cityId))
			{
				$cityId = $city->getId ();
				if(! empty ( $cityId ) && $cityId > 0)
				{
					$cities [] = $cityId;
					$this->values [$cityId] = $city->getName ();
				}
			}
		}
		return $cities;
	}
	
	public function extractSnapshotValue(University $university, SnapshotCourse $course) {
		$cities = array ();
		$locations = $university->getLocations ();
		
		foreach ( $locations as $location ) {
			$city = $location->getCity ();
			
			if (! empty ( $city )) {
				$cityId = $city->getId ();
				if (! empty ( $cityId ) && $cityId > 0) {
					$cities [] = $cityId;
					$this->values [$cityId] = $city->getName ();
				}
			}
		}
		return $cities;
	}
	
	public function addValue(University $university, AbroadInstitute $institute, AbroadCourse $course) {
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
	
	public function addSnapshotValue(University $university, SnapshotCourse $course) {
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
