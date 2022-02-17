<?php

class AutosuggestorFinder {
	
	private $_ci;
	
	public function __construct(){
		$this->_ci = & get_instance();
	}
	
	public function getData($courseIndexData, $instituteIndexData) {
		if(empty($courseIndexData) || empty($instituteIndexData)){
			return array();
		}
		$autosuggestorIndexData = $this->preprocessRawData($courseIndexData, $instituteIndexData);
		return $autosuggestorIndexData;
	}
	
	public function preprocessRawData($course, $institute){
		$autoSuggestorList = array();
		$courseLdbSpecializationName = $course['course_ldb_specialization_name'];
		$courseLdbCategoryName 		 = $course['course_ldb_category_name'];
		$courseLdbCourseName 		 = $course['course_ldb_course_name'];
		$count = 0;
		foreach($courseLdbSpecializationName as $specializationName){
			foreach($courseLdbCategoryName as $categoryName){
				foreach($courseLdbCourseName as $courseName){
					$autoSuggestor = array();
					// institute information
					$autoSuggestor['institute_id'] 				= $institute['institute_id'];
					$autoSuggestor['institute_title'] 			= $institute['institute_title'];
					$autoSuggestor['course_id'] 				= $course['course_id'];
					//Location information
					$autoSuggestor['course_city_id'] 			= $course['course_city_id'];
					$autoSuggestor['course_city_name'] 			= $course['course_city_name'];
					$autoSuggestor['course_state_id'] 			= $course['course_state_id'];
					$autoSuggestor['course_state_name'] 		= $course['course_state_name'];
					$autoSuggestor['course_country_id'] 		= $course['course_country_id'];
					$autoSuggestor['course_country_name'] 		= $course['course_country_name'];
					//ldb information
					$autoSuggestor['course_ldb_course_name'] 	= $courseName;
					$autoSuggestor['course_ldb_category_name'] 	= $categoryName;
					$autoSuggestor['course_ldb_specialization_name'] = $specializationName;
					// course type information
					$autoSuggestor['course_type'] 				= $course['course_type'];
					$autoSuggestor['course_level'] 				= $course['course_level'];
					//Category Information
					$categoryIds 								= $course['course_all_category_ids'];
					$explodedValued = array();
					if(!empty($categoryIds)){
						$explodedValued = explode(",", $categoryIds);
					}
					$autoSuggestor['course_category_id_list'] 	= $explodedValued;
					$autoSuggestor['course_review_count'] 		= $course['course_review_count'];
					$autoSuggestor['course_cr_exist'] 			= $course['course_cr_exist'];
					
					// general
					$autoSuggestor['facetype'] 					= 'autosuggestor';
					$autoSuggestor['unique_id'] 				= 'autosuggestor_' . $course['course_id'] . "_" . $count;
					$count++;
					array_push($autoSuggestorList, $autoSuggestor);
				}
			}
		}
		return $autoSuggestorList;	
	}

}
	