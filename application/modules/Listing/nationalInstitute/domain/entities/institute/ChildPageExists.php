<?php

class ChildPageExists{

	private $placementPageExists;
	private $flagshipCoursePlacementDataExists;
	private $naukriPlacementDataExists;
	private $cutoffPageExists;
	private $cutoffPageExamName;
	private $reviewPageExists;
	private $admissionPageExists;
	private $allCoursePageExists;

	function __set($property,$value)
	{
		$this->$property = $value;
	}

	function isPlacementPageExists() {
		return $this->placementPageExists;
	}

	function isFlagshipCoursePlacementDataExists() {
		return $this->flagshipCoursePlacementDataExists;
	}

	function isNaukriPlacementDataExists() {
		return $this->naukriPlacementDataExists;
	}

	function isCutoffPageExists() {
		return $this->cutoffPageExists;
	}

	function getCutoffPageExamName() {
		return $this->cutoffPageExamName;
	}

	function isReviewPageExists() {
		return $this->reviewPageExists;
	}

	function isAdmissionPageExists() {
		return $this->admissionPageExists;
	}

	function isAllCoursePageExists() {
		return $this->allCoursePageExists;
	}

}