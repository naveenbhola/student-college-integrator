<?php

class CourseExamsSpecification extends CompositeSpecification
{
	private $request = null;
	public function __construct($request) {
		$this->request = $request;
	}

	function isSatisfiedBy($course) {
		$diff = 0;
		if($this->request->getCategoryId() == 3) {
			$diff = 5;
		}

		$courseExams = (array) $course['filterValues'][CP_FILTER_COURSEEXAMS];
		$applied_course_exams = $this->filterValues;
		$flag_array = array();
		$engineering_exams_exception_list = $GLOBALS['ENGINEERING_EXAMS_REQUIRED_SCORES'];
		$mba_exams_exception_list = $GLOBALS['MBA_EXAMS_REQUIRED_SCORES'];
		foreach($applied_course_exams as $exam) {
			$flag = FALSE;
			$exam_array = explode("_",$exam);
			$exam_name =  $exam_array[0];
			$exam_score = $exam_array[1];
			if(array_key_exists($exam_name, $courseExams)) {
				$tempExamMarksType = $courseExams[$exam_name][2];
				//Defined in utility_helper
				$isExamMarksTypeValid = isExamMarksTypeValid($this->request->getSubCategoryId(), $exam_name, $tempExamMarksType); 
				if($isExamMarksTypeValid){
					$flag =  TRUE;
					if(!empty($exam_score)) {
						$course_exam = $courseExams[$exam_name];
						if($this->request->getCategoryId() == 2) {
							if(in_array($exam_name, $engineering_exams_exception_list)) {
								if($course_exam[1] != 0) { //empty score should always be considered
									if(!(( 0 < $course_exam[1]  && $course_exam[1] <= $exam_score))) {
										$flag =  FALSE;
									}	
								}
							} else {
								if($course_exam[1] != 0) { //empty score should always be considered
									if(!(($course_exam[1] >= $exam_score))) {
										$flag =  FALSE;
									}
								}
							}
						} else if($this->request->getCategoryId() == 3) {
							if(in_array($exam_name, $mba_exams_exception_list)) {
									$explode = explode("-", $exam_score);
									if(count($explode) > 1){
										$min = $explode[0];
										$max = $explode[1];
									} else {
										$min = $explode[0];
										$max = 1000000000000; //kind of infinity
									}
									if($min == 0){
										if(!($course_exam[1] > $min && $course_exam[1] <= $max)) {
											$flag =  FALSE;
										}
									} else {
										if(! ($course_exam[1] >= $min && $course_exam[1] <= $max) ) {
											$flag =  FALSE;
										}
									}
							} else {
								if($exam_score == 54) {
									if(!((0 < $course_exam[1]  && $course_exam[1] <= $exam_score))) {
										$flag =  FALSE;
									}
								} else if($exam_score == 75 || $exam_score == 85) {
									if(!(($exam_score < $course_exam[1]  && $course_exam[1] <= 100))) {
										$flag =  FALSE;
									}
								} else if($exam_score == 95) {
									if(!(($exam_score <= $course_exam[1]  && $course_exam[1] <= ($exam_score+$diff)))) {
										$flag =  FALSE;
									}
								} else {
									if(!((($exam_score-$diff) <= $course_exam[1]  && $course_exam[1] <= ($exam_score+$diff)))) {
										$flag =  FALSE;
									}
								}
							}
						}
					}
				}
			}
			$flag_array[$exam_name] = $flag;
		}
		if(count($flag_array)>0) {
			foreach($flag_array as $flag) {
				if($flag) {
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
}
