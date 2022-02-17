<?php 

class Abroad_AlsoViewed
{
	private $_ci;
	
	private $_results = array();
	private $_course_selection_scheme = array();
	private $_source = array();
	
	function __construct()
	{		
		$this->_ci = & get_instance();
		$this->_ci->load->model('recommendation_abroad/abroad_alsoviewed_model');
	}

	/*
	 * @array - seed data in format: array(0=>institute_id,1=>country_id)
	 * @array - institutes to be excluded from result
	 */
	function getAlsoViewedListings($courseIds, $num_results, $exclusion_list = array(), $fallbackLimit = 9, $preCompute = false)
	{
		ini_set("memory_limit", '256M');
		$final_results = array();
		$filteredCourses = array();
		$institutesInReco = array();
		$resultPriority = array('back-mapping' => 1, 'triangular-mapping' => 2, 'no-mapping' => 3);
		$filterPriority = array('LDB' => 1, 'Subcategory' => 2);
		$hasExclusion = count($exclusion_list) > 0 ? true : false;
		$hasMultipleCourse = count($courseIds) > 1 ? true : false;
		
		$filteredCourses = $this->_ci->abroad_alsoviewed_model->getFilteredAlsoViewedCourses($courseIds);		
		
		foreach($filteredCourses as $key => $course) {			
			if($hasExclusion && in_array($course['recommended_institute_id'], $exclusion_list)) {
				unset($filteredCourses[$key]);
				continue;
			}
			
			if($hasMultipleCourse) {
				$filter = $course['filter_type'];
				$instituteId = $course['recommended_institute_id'];
				$alsoViewedCourseId = $course['recommended_course_id'];
				$weight = $course['weight'];
				$type = $course['mapping_type'];
				
				$updateData = false;
				if(!empty($institutesInReco[$instituteId])) {
					if($filterPriority[$institutesInReco[$instituteId]['filter_type']] > $filterPriority[$filter]) {
						$updateData = true;
					}
					else if($filterPriority[$institutesInReco[$instituteId]['filter_type']] == $filterPriority[$filter]) {
						if($resultPriority[$institutesInReco[$instituteId]['mapping_type']] > $resultPriority[$type]) {
							$updateData = true;
						}
						else if($resultPriority[$institutesInReco[$instituteId]['mapping_type']] == $resultPriority[$type]) {
							if($institutesInReco[$instituteId]['weight'] < $weight) {
								$updateData = true;
							}
						}
					}
				}
				else {
					if(empty($institutesInReco[$instituteId])) {
						$updateData = true;
					}
				}
				
				if($updateData) {
					$institutesInReco[$instituteId]['filter_type'] = $filter;
					$institutesInReco[$instituteId]['recommended_institute_id'] = $instituteId;
					$institutesInReco[$instituteId]['recommended_course_id'] = $alsoViewedCourseId;
					$institutesInReco[$instituteId]['weight'] = $weight;
					$institutesInReco[$instituteId]['mapping_type'] = $type;
				}
			}
		}
		
		
		if($hasMultipleCourse) {
			uasort($institutesInReco, array('alsoviewed','compareAlsoViewedResults'));
			$filteredCourses = array_values($institutesInReco);
		}
		else {
			$filteredCourses = array_values($filteredCourses);
		}
		
		
		if(!isset($num_results)) {
			$num_results = 10;
		}
		$num_results = $num_results > count($filteredCourses) ? count($filteredCourses) : $num_results;
		
		for($index = 0; $index < $num_results; $index++) {
			$final_results[] = array($filteredCourses[$index]['recommended_institute_id'], $filteredCourses[$index]['recommended_course_id']);
		}
		
		
		return $final_results;
	}
	
	function compareAlsoViewedResults($result1, $result2) {		
		if($result1['mapping_type'] == $result2['mapping_type']) {
			if($result1['weight'] >= $result2['weight']) {
				return -1;
			}
			else {
				return 1;
			}
		}
		else {
			if($result1['mapping_type'] == 'back-mapping') {
				return -1;
			}
			else if($result1['mapping_type'] == 'triangular-mapping') {
				if($result2['mapping_type'] == 'back-mapping') {
					return 1;
				}
				else {
					return -1;
				}
				
			}
			else {
				return 1;
			}
		}
	}
}
