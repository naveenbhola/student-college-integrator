<?php

class BucketingService
{
	private $request;

	function __construct($request = array())
	{
		$this->request = $request;
	}
	public function createBucketsOnExams($institutes, $stickyInstituteIds) {
		$exams_with_score = array();
		$exams_without_score = array();
		$diff = 0;
		// check if category is mangament
		if($this->request->getCategoryId() == 3) {
			$diff = 5;
		}

		$applied_filters 	= $this->request->getAppliedFilters();
		$course_exams 		= $applied_filters['courseexams'];
		foreach ($course_exams as $exam) {
			$exam_array = explode("_", $exam);
			if(!empty($exam_array[1])) {
				$exams_with_score[$exam_array[0]] = array('exam'=>$exam_array[0],'score'=>$exam_array[1]);
			} else {
				$exams_without_score[$exam_array[0]] = $exam_array[0];
			}
		}
		
		$debugFlag = false;
		if($_REQUEST['pank'] == "true" && $this->request->getPageKey() == 'CATPAGE-3-23-1-0-1-106-2-0-none-MAT-500000') {
			$debugFlag = true;
		}
        
		$exams_with_score_bucket = array();
		$exams_without_score_bucket = array();
		$final_with_score_bucket = array();
		$final_without_score_bucket = array();
		$engineering_exams_exception_list = $GLOBALS['ENGINEERING_EXAMS_REQUIRED_SCORES'];
		// start bucketing
		foreach ($institutes as $institute_id => $institute_object) {
			if(!empty($institute_object)) {
				$courses = $institute_object->getCourses();
				foreach ($courses as $course_index =>$course) {
					if(!empty($course)){
						$exams = $course->getEligibilityExams();
						foreach($exams as $exam) {
							if($examAcronym = $exam->getAcronym()) {
								$marks = $exam->getMarks()?$exam->getMarks():"0";
								if(array_key_exists($examAcronym, $exams_with_score)) {
									$temp = $exams_with_score[$examAcronym];
									if($this->request->getCategoryId() == 2) {
										if(in_array($examAcronym, $engineering_exams_exception_list)) {
											if(0 < $marks && $marks <= $temp['score']) { //score
												$exams_with_score_bucket[$examAcronym][] = $course->getId();
											}
										} else {
											if($marks >= $temp['score']) { //rank
												$exams_with_score_bucket[$examAcronym][] = $course->getId();
											}
										}
									} else if($this->request->getCategoryId() == 3) {
										if($examAcronym == "CMAT" || $examAcronym == "GMAT"){
											$explode = explode("-", $temp['score']);
											if(count($explode) > 1){
												$min = $explode[0];
												$max = $explode[1];
											} else {
												$min = $explode[0];
												$max = 1000000000000; //kind of infinity
											}
											if($min == 0){
												if($marks > $min && $marks <= $max) {
													$exams_with_score_bucket[$examAcronym][] = $course->getId();
												}	
											} else {
												if($marks >= $min && $marks <= $max) {
													$exams_with_score_bucket[$examAcronym][] = $course->getId();
												}
											}
										} else if($examAcronym == "MAT" || $examAcronym == "XAT" || $examAcronym == "NMAT"){
											if($examAcronym == "MAT"){
												$maxScoreForExam = maxValueForExam("MAT"); 
											} else if($examAcronym == "XAT"){
												$maxScoreForExam = maxValueForExam("XAT"); 
											} else if($examAcronym == "NMAT"){
												$maxScoreForExam = maxValueForExam("NMAT"); 
											}
											if($temp['score'] == 54) { //min condition
												if(0 < $marks && $marks <= $temp['score']) {
													$exams_with_score_bucket[$examAcronym][] = $course->getId();
												}
											} else if($temp['score'] == $maxScoreForExam) { //max condition
												if($temp['score'] < $marks && $marks <= 100) {
													$exams_with_score_bucket[$examAcronym][] = $course->getId();
												}
											} else {
												if(($temp['score']-$diff) <= $marks && $marks <= ($temp['score']+$diff)) {
													$exams_with_score_bucket[$examAcronym][] = $course->getId();
												}
											}	
										} else {
											if($temp['score'] == 54) {
												if(0 < $marks && $marks <= $temp['score']) {
													$exams_with_score_bucket[$examAcronym][] = $course->getId();
												}
											} else if($temp['score'] == 95) {
												if($temp['score'] <= $marks && $marks <= ($temp['score']+$diff)) {
													$exams_with_score_bucket[$examAcronym][] = $course->getId();
												}
											} else {
												if(($temp['score']-$diff) <= $marks && $marks <= ($temp['score']+$diff)) {
													$exams_with_score_bucket[$examAcronym][] = $course->getId();
												}
											}	
										}
									}
								}
								if(array_key_exists($examAcronym, $exams_without_score)) {
									$exams_without_score_bucket[$examAcronym][] = $course->getId();
								}
							}
						}
					}
				}
			}
			
			$elegible_courses = $this->calculateElegibileCourse($exams_with_score_bucket,$exams_without_score_bucket);
			
			$courses_array = array();
			$courses_remaining_array = array();
			foreach($courses as $course) {
				if(!empty($course)){
					if(in_array($course->getId(),$elegible_courses['course_ids'])) {
						$courses_array[] = $course;
					} else {
						$courses_remaining_array[] = $course;
					}	
				}
			}
			$courses_array = $courses_array + $courses_remaining_array;
			$institute_object->setCourses($courses_array);

			if($elegible_courses['with_score']) {
				$institute_object->temp_exams = $elegible_courses['key'];
				$final_with_score_bucket[$elegible_courses['key']][$institute_id] = $institute_object;
			} else if(!$elegible_courses['with_score']) {
				$institute_object->temp_exams = $elegible_courses['key'];
				$final_without_score_bucket[$elegible_courses['key']][$institute_id] = $institute_object;
			}
			$exams_with_score_bucket = $exams_without_score_bucket = array();
		}

		//We don't need inbucket sort for individual institutes
		/*
		foreach ($final_with_score_bucket as $key=>$values) {
			uasort($values, function ($a, $b) {
				$course1 = reset($a->getCourses());
				$exams = $course1->getEligibilityExams();
				$score1 = 0;
				foreach ($exams as $exam) {
					if($exam->getAcronym() == $a->temp_exams) {
						$score1 = $exam->getMarks();
						break;
					}
				}

				$course2 = reset($b->getCourses());
				$exams = $course2->getEligibilityExams();
				$score2 = 0;
				foreach ($exams as $exam) {
					if($exam->getAcronym() == $a->temp_exams) {
						$score2 = $exam->getMarks();
						break;
					}
				}
				if ($score1 == $score2) {
					return 0;
				}
				return ($score1 < $score2) ? 1 : -1;
			}
			);

			unset($final_with_score_bucket[$key]);
			$final_with_score_bucket[$key] = $values;
		}
		*/
		if($debugFlag){
			//_p($final_with_score_bucket); die();
		}
		uasort($final_with_score_bucket, function ($a, $b) {
			global $exam_weightage_array;
			$exam_name1= reset($a)->temp_exams;
			$exam_name2= reset($b)->temp_exams;
			if ($exam_weightage_array[$exam_name1] == $exam_weightage_array[$exam_name2]) {
				return 0;
			}
			return ($exam_weightage_array[$exam_name1] < $exam_weightage_array[$exam_name2]) ? 1 : -1;
			}
		);

		uasort($final_without_score_bucket, function ($a, $b) {
			global $exam_weightage_array;
			$exam_name1= reset($a)->temp_exams;
			$exam_name2= reset($b)->temp_exams;
			if ($exam_weightage_array[$exam_name1] == $exam_weightage_array[$exam_name2]) {
				return 0;
			}
			return ($exam_weightage_array[$exam_name1] < $exam_weightage_array[$exam_name2]) ? 1 : -1;
		}
		);

		$final_array1 = array();
		foreach ($final_with_score_bucket as $key => $values) {
			$final_array1 = $final_array1 + $values;
		}
		$final_array2 = array();
		foreach ($final_without_score_bucket as $key => $values) {
			$final_array2 = $final_array2 + $values;
		}
		$results = ($final_array1 + $final_array2);
		$finalResult = $this->_reorderBucketForCategorySponsor($results, $stickyInstituteIds);
		return $finalResult;
	}

	function _reorderBucketForCategorySponsor($instituteList = array(), $stickyInstituteIds = array()) {
		if(empty($instituteList) || empty($stickyInstituteIds)){
			return $instituteList;
		}
		$categorySponsorResults = array();
		foreach($stickyInstituteIds as $stickyId) {
			if(array_key_exists($stickyId, $instituteList)) {
				$categorySponsorResults[$stickyId] = $instituteList[$stickyId];
			}
		}
		foreach($stickyInstituteIds as $stickyId) {
			if(array_key_exists($stickyId, $instituteList)) {
				unset($instituteList[$stickyId]);
			}
		}
		if(!empty($categorySponsorResults)){
			$instituteList = $categorySponsorResults + $instituteList;
		}
		return $instituteList;
	}
	

	private function calculateElegibileCourse($exams_with_score_bucket,$exams_without_score_bucket) {

		$score_value_array = array();
		$temp_array = array();
		global $exam_weightage_array;
		if(count($exams_with_score_bucket) > 0) {

			foreach ($exams_with_score_bucket as $key => $value) {
				$temp_array[$exam_weightage_array[$key]] = $key;
			}
			krsort($temp_array);
			$key = reset($temp_array);
			$top_contender =  $exams_with_score_bucket[$key];
			if(count($top_contender) == 1) {

				return array("with_score"=>true,'key'=>$key,'course_ids'=>$top_contender);

			} else {

				$course_score_array = array();
				foreach ($top_contender as $course_id) {
					$course_score_array[$course_id] = $this->calculateScore($course_id,$exams_with_score_bucket,$exams_without_score_bucket);
				}
				arsort($course_score_array);
				$top_score = reset($course_score_array);
				$courses_ids = array();
				foreach ($course_score_array as $id=>$score) {
					if($score == $top_score) {
						$courses_ids[] = $id;
					}
				}

				return array("with_score"=>true,'key'=>$key,'course_ids'=>$courses_ids);
			}

		} else {

			foreach ($exams_without_score_bucket as $key => $value) {
				$temp_array[$exam_weightage_array[$key]] = $key;
			}
			krsort($temp_array);
			$key = reset($temp_array);
			$top_contender =  $exams_without_score_bucket[$key];
			if(count($top_contender) == 1) {
				return array("with_score"=>false,'key'=>$key,'course_ids'=>$top_contender);
			} else {
				$course_score_array = array();
				foreach ($top_contender as $course_id) {
					$course_score_array[$course_id] = $this->calculateScore($course_id,$exams_with_score_bucket,$exams_without_score_bucket);
				}
				arsort($course_score_array);
				$top_score = reset($course_score_array);
				$courses_ids = array();
				foreach ($course_score_array as $id=>$score) {
					if($score == $top_score) {
						$courses_ids[] = $id;
					}
				}
				return array("with_score"=>false, 'key'=> $key, 'course_ids'=>$courses_ids);
			}
		}

	}

	private function calculateScore($course_id,$exams_with_score_bucket,$exams_without_score_bucket) {

		$score = 0;
		global $exam_weightage_array;
		foreach ($exams_with_score_bucket as $key => $values) {
			if(in_array($course_id, $values)) {
				$score = $score + $exam_weightage_array[$key];
			}
		}

		foreach ($exams_without_score_bucket as $key => $values) {
			if(in_array($course_id, $values)) {
				$score = $score + $exam_weightage_array[$key];
			}
		}

		return $score;

	}


}
