<?php

class shikshaPopularityDataLib {

	function __construct() {
		$this->CI = & get_instance();	
	}

	function init(){
		
		$this->CI->load->model('ShikshaPopularity/shikshapopularitymodel');

		$this->popularityModel = new ShikshaPopularityModel();

		$this->CI->load->config('ShikshaPopularity/ShikshaPopularityConfig');

	}

	function getDataForCourses($coursesData = array()){

		$this->init();		

		$dataForRankCalculation = array();
		if(!empty($coursesData)) {

			global $parameterFunctionMapping;

			$courses = array();
			$courses = array_keys($coursesData);			

			foreach ($parameterFunctionMapping as $parameter => $paraSpecificFunction) {
				$time_start = microtime_float(); $start_memory = memory_get_usage();
				error_log("\t\t\tSection: Getting $paraSpecificFunction.... \n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
				$dataForRankCalculation[$parameter] = $this->popularityModel->$paraSpecificFunction($courses);
				error_log("\t\t\tSection: Got it | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
			}
		}
				
		return $dataForRankCalculation;
	}

	function getTotalViewsOnCourse($courseIdArray) {
		$this->init();
		return $this->popularityModel->getTotalViewsOnCourse($courseIdArray);
	}

	/** 
	* Function to get all the Popularites for the Institute
	* @param: instituteId 
	*
	* @return Array with all the popularites data
	*/
	function getPopularityDataForInstitute($instituteId = null){

		if($instituteId == null) return array();
		$this->init();
		$popularityData = $this->popularityModel->fetchPopularityData($instituteId);
		return $popularityData;
	}

	function getTopCollegesByStream($streamId,$limit=3){
		$this->init();
		$popularityData = $this->popularityModel->getTopCollegesByStream($streamId,$limit);
		return $popularityData;
	}

	/**
	* Functionm to map the Stream, Subtream, Base Couese with the Popularity Data and return the scores
	*
	*/
	function mapPopularityToBaseEntities($streamId = 0, $substreamId = 0 , $baseCourse=0, $popularityData){

		$result = array();
		foreach ($popularityData as $popularityDataVal) {
			if($popularityDataVal['attribute_type'] == 'hierarchy'  && ($popularityDataVal['stream_id'] == $streamId) && ($popularityDataVal['substream_id'] == $substreamId) && !empty($substreamId)){
				$result['popularity_score_stream_substream'] = $popularityDataVal['popularity_score'];
			}
			if($popularityDataVal['attribute_type'] == 'hierarchy' && $popularityDataVal['stream_id'] == $streamId && empty($popularityDataVal['substream_id'])){
				$result['popularity_score_stream'] = $popularityDataVal['popularity_score'];
			}
			
			if($popularityDataVal['attribute_type'] == 'base_course' && $popularityDataVal['attribute_id'] == $baseCourse){
				$result['popularity_score_base_course'] = $popularityDataVal['popularity_score'];
			}
		} 
		return $result;
	}

} ?>	