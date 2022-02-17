<?php

class ExamScoreSorter extends AbstractSorter
{
	protected $sortKey = CP_SORTER_EXAMSCORE;
	private $request;

	function __construct($request)
	{
		$this->request = $request;
		parent::__construct();
	}

	public function sort($institutes, $institutesGroupByType = array()) {
		if(count($institutes) > 0) {
			$updatedInstituteList = array();
			foreach($institutes as $instituteId => $courses) {
				foreach($courses as $courseId => $course) {
					$exam_sort_values = $course['sortValues'];
					if(!is_array($exam_sort_values)) {
						$exam_sort_values = unserialize($exam_sort_values);
					}
					$exam_sort_values = $exam_sort_values[$this->sortKey];
					$score_value = $exam_sort_values[$this->params['exam']][1] ? $exam_sort_values[$this->params['exam']][1] : 0;
					$examsPresent = array_keys($exam_sort_values);
					if(in_array($this->params['exam'], $examsPresent)) {
						if($this->request->getSubcategoryId() == 56) {
							$ENGINEERING_EXAMS_REQUIRED_SCORES = $GLOBALS['ENGINEERING_EXAMS_REQUIRED_SCORES'];
							$filters = $this->request->getAppliedFilters();
							$examsInFilters = $filters['courseexams'];
							foreach($examsInFilters as $examInFilter){
								$explode = explode("_", $examInFilter);
								if(count($explode) > 1){
									if($this->params['exam'] == $explode[0]){
										$userEnteredValue = $explode[1];
										if(in_array($this->params['exam'], $ENGINEERING_EXAMS_REQUIRED_SCORES)) {
											if($userEnteredValue == 0){
												$updatedInstituteList[$instituteId][$courseId]['sortValues'][$this->sortKey] = $score_value;
											} else {
												if($userEnteredValue >= $score_value) {
													$updatedInstituteList[$instituteId][$courseId]['sortValues'][$this->sortKey] = $score_value;
												}
												//extra confdition for be/btech case, consider empty score/rank values also
												if($score_value == 0){
													$updatedInstituteList[$instituteId][$courseId]['sortValues'][$this->sortKey] = $score_value;
												}
											}
										} else {
											if($userEnteredValue <= $score_value) {
												$updatedInstituteList[$instituteId][$courseId]['sortValues'][$this->sortKey] = $score_value;
											}
											//extra confdition for be/btech case, consider empty score/rank values also
											if($score_value == 0){
												$updatedInstituteList[$instituteId][$courseId]['sortValues'][$this->sortKey] = $score_value;
											}
										}
									}
								}
							}
						} else if($this->request->getSubcategoryId() == 23) {
							$return = $this->getAppliedFilterExamRange($this->params['exam']);
							if($return['min'] !== false && $return['max'] !== false) {
								if($score_value >= $return['min'] && $score_value <= $return['max'] ){
									$updatedInstituteList[$instituteId][$courseId]['sortValues'][$this->sortKey] = $score_value;
								}
							} else {
								$updatedInstituteList[$instituteId][$courseId]['sortValues'][$this->sortKey] = $score_value;
							}	
						}
					}
				}
			}
			//_p("count: " . count(array_keys($institutes)));
			$institutes = $updatedInstituteList;
			$individualMarksBuckets = $this->_createUniqueScoreBuckets($institutes, $this->sortKey);
			//_p($individualMarksBuckets);
			$buckets = $this->_sortBucketItems($individualMarksBuckets, $institutesGroupByType);
			//_p("sorted buckets:");
			$updatedInstitutes = $this->_addInstituteIdAsKey($institutes);
			$GLOBALS['consolidatedListForExamSort'] = $this->_consolidateBucketList($buckets);
			uasort($updatedInstitutes, function ($a, $b) {
				global $consolidatedListForExamSort;
				$aKey = $a['key'];
				$bKey = $b['key'];
				if($consolidatedListForExamSort[$aKey] == $consolidatedListForExamSort[$bKey]){
					return 0;
				}
				return ($consolidatedListForExamSort[$aKey] < $consolidatedListForExamSort[$bKey]) ? -1 : 1;
			});
			$updatedInstitutes = $this->_removeInstituteIdAsKey($updatedInstitutes);
			//_p($GLOBALS['consolidatedListForExamSort']);
			//_p("original order"); _p(implode(",", array_keys($institutes)));
			//_p("count: " . count(array_keys($institutes)));
			//_p("new order"); _p(implode(",", array_keys($updatedInstitutes)));
			//_p("count: " . count(array_keys($updatedInstitutes)));
			return $updatedInstitutes;
		}
	}

	public function extractSortValue(Institute $institute,Course $course)
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
	
	private function getAppliedFilterExamRange($examName) {
		$filters = $this->request->getAppliedFilters();
		$exams = $filters['courseexams'];
		$return = array('min'=> false, 'max' => false);
		if(!empty($exams)){
			foreach($exams as $exam){
				$explode = explode("_", $exam);
				if(count($explode) > 1){
					if($examName == $explode[0]){
						if($this->request->getSubcategoryId() == 23){
							$return = $this->getMinMaxValueForManagementExam($examName, $explode[1]);
						}
					}
				}
			}
		}
		return $return;
	}
	
	private function getMinMaxValueForManagementExam($examName, $range) {
		global $MBA_NO_OPTION_EXAMS;
		
		$diff = 5;
		$min = false;
		$max = false;
		$explode = explode("-", $range);
		$last = false;
		if(count($explode) > 1){
			$first = $explode[0];
			$last  = $explode[1];
		} else {
			$first = $explode[0];
		}
		if($examName == "CMAT" || $examName == "GMAT"){
			$max = $last;
			if(empty($max)){
				$max = 1000000000000; //infinity
			}
			$min = $first;
			if($first == 0 && !empty($last)){
				$min = 1;
			}
		} else if(in_array($examName, $MBA_NO_OPTION_EXAMS)) {
			$min = false;
			$max = false;
		} else {
			if($first == 0){
				$max = 1000000000000; //infinity
				$min = 0;
			}
			else if($first == 54) {
				$max = 54;
				$min = 1;
			} else if($first == 95) {
				$min = $first;
				$max = $first + $diff;
			} else if($first == 75 || $first == 85) {
				$min = $first + 1;
				$max = 100;
			} else {
				$min = $first - $diff;
				$max = $first + $diff;
			}
		}
		return array("min" => $min, "max" => $max);
	}
	
	private function _createUniqueScoreBuckets($institutes = array(), $sortKey = NULL) {
		$buckets = array();
		$c = 0;
		foreach($institutes as $instituteId => $courses) {
			$courseIds 	= array_keys($courses);
			$courseId 	= $courseIds[0];
			$course 	= $courses[$courseId];
			$sortValue 	= $course['sortValues'][$sortKey];
			if(empty($sortValue)){
				$c++;
			}
			if(!array_key_exists($sortValue, $buckets)){
				$buckets[$sortValue] = array();
			}
			if(!in_array($instituteId, $buckets[$sortValue])){
				$buckets[$sortValue][] = $instituteId;
			}
		}
		return $buckets;
	}
	
	private function _sortBucketItems($buckets = array(), $institutesGroupByType = array()) {
		$examName = FALSE;
		$sortOrder = FALSE;
		if(!empty($this->params)){
			$examName  = $this->params['exam'];
			$sortOrder = $this->params['order'];
		}
		if(!empty($examName) && !empty($sortOrder)){
			if($sortOrder == 'DESC'){
				krsort($buckets);
			} else {
				ksort($buckets);
				if(array_key_exists(0, $buckets)){
					$zeroValueBucket = $buckets[0];
					unset($buckets[0]);
					$buckets = array_merge($buckets, $zeroValueBucket);
				}
			}
			$GLOBALS['hashSet'] = $this->_getInstituteHashByType($institutesGroupByType);
			$newBuckets = array();
			foreach($buckets as $bucketKey => $bucketValues){
				usort($bucketValues, function ($a, $b) {
					global $hashSet;
					$aType = $hashSet[$a];
					$bType = $hashSet[$b];
					if($aType == $bType){
						return 0;
					}
					return ($aType < $bType) ? 1 : -1;
				});
				$newBuckets[$bucketKey] = $bucketValues;
			}
			$buckets = $newBuckets;
		}
		return $buckets;
	}
	
	private function _getInstituteHashByType($institutesGroupByType = array()) {
		$hash = array();
		$typeMap = array('free' => 1, 'paid' => 2, 'main' => 3, 'sticky' => 4);
		foreach($institutesGroupByType as $type => $instituteList){
			foreach($instituteList as $instituteId){
				$hash[$instituteId] = $typeMap[$type];
			}
		}
		return $hash;
	}
	
	private function _consolidateBucketList($buckets = array()) {
		$list = array();
		foreach($buckets as $bucket) {
			$list = array_merge((array)$list, (array)$bucket);
		}
		$list = array_flip($list);
		return $list;
	}
	
	private function _addInstituteIdAsKey($institutes = array()) {
		$list = array();
		foreach($institutes as $instituteId => $values){
			$values['key'] = $instituteId;
			$list[$instituteId] = $values;
		}
		return $list;
	}
	
	private function _removeInstituteIdAsKey($institutes = array()){
		$list = array();
		foreach($institutes as $instituteId => $values){
			unset($values['key']);
			$list[$instituteId] = $values;
		}
		return $list;
	}
	
}

