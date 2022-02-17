<?php

class ExamsScoreFilter extends AbstractFilter
{
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
		$exams = $course->getEligibilityExams();
		$examAcronyms = array();
		foreach($exams as $exam) {
			if($examAcronym = $exam->getAcronym()) {
				$marks = $exam->getMarks()?$exam->getMarks():"";
				$type = $exam->getMarksType()?$exam->getMarksType():"";	
				$examAcronyms[$examAcronym] = array($examAcronym,$marks,$type);
			}
		}
		return $examAcronyms;
	}

	public function addValue(Institute $institute,Course $course)
	{
		$examAcronyms = $this->extractValue($institute,$course);
		foreach($examAcronyms as $key=>$examAcronym) {	
			$this->values[$key] = $examAcronym;
		}
	}
	
	function setFilterValues($filterVaules = array()) {
	 arsort($filterVaules); // sort filter by no of result	
	foreach($filterVaules as $value => $occurenceCount)
		{
			//$value = strtoupper($value);  LF-1677
			$this->values[$value] = array($value);
		}
	}
}
