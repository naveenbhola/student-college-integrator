<?php
class CourseLevelOneFilter extends AbstractFilter {
	private $defaultHide = array('Exam Preparation');

	function __construct()
	{
		parent::__construct();
	}

	public function getFilteredValues()
	{
		return $this->values;
	}

	public function extractValue(Institute $institute,Course $course)
	{
		return $course->getCourseLevel1Value();
	}

	public function addValue(Institute $institute,Course $course)
	{
		if(($courseLevel = $this->extractValue($institute,$course)) && !in_array($courseLevel,$this->defaultHide)) {
			$this->values[$courseLevel] = $courseLevel;
		}
	}
	
	function setFilterValues($filterVaules = array()) {
		foreach($filterVaules as $courseLevel => $occurenceCount){
			if(!in_array($courseLevel,$this->defaultHide))
			    $this->values[$courseLevel] = $courseLevel;
		}
	}
}