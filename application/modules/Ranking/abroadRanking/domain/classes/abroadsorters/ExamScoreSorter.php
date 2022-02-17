<?php

class ExamScoreSorter extends AbstractSorter
{
	protected $sortKey = ABROAD_CP_SORTER_EXAM;
	
	function __construct()
	{
		parent::__construct();
	}

	public function extractSortValue(AbroadCourse $course)
	{
		//get cut off value for all the exams in a course
		$examsObj = $course->getEligibilityExams();
		foreach($examsObj as $exam) {
			if($exam->getCutoff()!= "N/A"){
				$examNameMarks[$exam->getName()] = $exam->getCutOff();
			}
			else{
				$examNameMarks[$exam->getName()] = 9999;
			}
		}
		return $examNameMarks;
	}
}

