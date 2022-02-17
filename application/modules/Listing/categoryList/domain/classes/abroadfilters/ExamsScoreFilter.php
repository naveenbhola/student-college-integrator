<?php

class ExamsScoreFilter extends AbstractFilter
{
	function __construct()
	{
		parent::__construct();
	}

	public function getFilteredValues()
	{
		foreach($this->values as $key=>$value){
			$value = array_unique($value);
			if($key==9){
				asort($value);
			}else{
				arsort($value);
			}
			
			$this->values[$key] = $value;
		}
		return $this->values;
	}

	public function extractValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
	{
		$exams = $course->getEligibilityExams();
		$examScoreArray = array();
		foreach($exams as $exam) {
			if($examAcronym = $exam->getId()) {
				$marks = $exam->getCutoff()?$exam->getCutoff():"";
				$this->values[$examAcronym][] = $marks;
				$examScoreArray[$examAcronym] = $marks;
				
			}
		}
		return $examScoreArray;
	}

	public function addValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
	{
		$exams = $this->extractValue($university,$institute,$course);
		
	}
	
}
