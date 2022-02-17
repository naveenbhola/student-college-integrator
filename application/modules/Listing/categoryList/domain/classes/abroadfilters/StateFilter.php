<?php
class StateFilter extends AbstractFilter {
	function __construct() {
		parent::__construct ();
	}
	
	public function getFilteredValues() {
		asort ( $this->values );
		return $this->values;
	}
	
	public function extractValue(University $university, AbroadInstitute $institute, AbroadCourse $course) {
		$states = array ();
		$locations = $university->getLocations ();
		foreach ( $locations as $location ) {
			$state = $location->getState ();
			if (! empty ( $state )) {
				$stateId = $state->getId ();
				if (! empty ( $stateId ) && $stateId > 0) {
					$states [] = $stateId;
					$this->values [$stateId] = $state->getName ();
				}
			}
		}
		return $states;
	}
	
	public function extractSnapshotValue(University $university,SnapshotCourse $course) {
		$states = array ();
		$locations = $university->getLocations ();
		
		foreach ( $locations as $location ) {
			$state = $location->getState ();
			if (! empty ( $state )) {
				$stateId = $state->getId ();
				if (! empty ( $stateId ) && $stateId > 0) {
					$states [] = $stateId;
					$this->values [$stateId] = $state->getName ();
				}
			}
		}
		return $states;
	}
	
	public function addValue(University $university, AbroadInstitute $institute, AbroadCourse $course) {
		$locations = $university->getLocations ();
		foreach ( $locations as $location ) {
			$state = $location->getState ();
			if (! empty ( $state )) {
				$stateId = $state->getId ();
				if (! empty ( $stateId ) && $stateId > 0) {
					$this->values [$stateId] = $state->getName ();
				}
			}
		}
	}
	
	public function addSnapshotValue(University $university,SnapshotCourse $course) {
		$locations = $university->getLocations ();
		foreach ( $locations as $location ) {
			$state = $location->getState ();
			if (! empty ( $state )) {
				$stateId = $state->getId ();
				if (! empty ( $stateId ) && $stateId > 0) {
					$this->values [$stateId] = $state->getName ();
				}
			}
		}
	}
}
