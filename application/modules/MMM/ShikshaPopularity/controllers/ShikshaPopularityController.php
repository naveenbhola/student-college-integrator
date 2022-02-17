<?php 

	//add error logs in controller

	class ShikshaPopularityController extends MX_Controller {

	function init(){
		
		$this->load->library('ShikshaPopularityDataLib');
		$this->dataLib = new ShikshaPopularityDataLib();
		
		$this->load->library('ShikshaPopularityRankLib');
		$this->rankLib = new ShikshaPopularityRankLib();
		
		$this->load->model('shikshapopularitymodel');
		$this->popularityModel = new ShikshaPopularityModel();

	}

	function startPopularityCron($startLimit, $endLimit) {
		
		$this->validateCron();
		//Benchmark Start Cron

		$this->calculateShikshaPopularity($startLimit, $endLimit);
		
		//benchmark end cron
	}

	private function calculateShikshaPopularity($startLimit, $endLimit){
		$this->init();

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		error_log("Section: Fetching live institutes.... | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
		$allLiveInstituteIds = $this->popularityModel->getAllLiveInstitutes($startLimit,$endLimit);
		error_log("Section: Fetched it | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$liveInstituteIds = array_chunk($allLiveInstituteIds, 100);
		error_log("Section: Created chunks | ".getLogTimeMemStr($time_start, $start_memory)."\n\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

		foreach ($liveInstituteIds as $key => $liveInstituteIdsArray) {
			$time_start = microtime_float(); $start_memory = memory_get_usage();
			error_log("Section: Processing batch $key..... \n\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
			$this->calculateShikshaPopularityForInstitute($liveInstituteIdsArray);
			error_log("Section: Processing done | ".getLogTimeMemStr($time_start, $start_memory)."\n\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

			//break;
		}
		echo 'Cron End';
	}

	private function calculateShikshaPopularityForInstitute($liveInstituteIds = array()){
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		
		error_log("\tSection: Fetching Hierarchy and Courses For Institute..... \n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
		$hierarchyCourseData = $this->popularityModel->getHierarchyAndCoursesForInstitute($liveInstituteIds);
		error_log("\tSection: Fetched it | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

		if(!empty($hierarchyCourseData)) {
		
			$institutes = array();

			foreach($hierarchyCourseData as $instituteId => $coursesData) {
				error_log("\t\tSection: Processing institute $instituteId.... \n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

				$dataForRankLibrary = array();

				$time_start = microtime_float(); $start_memory = memory_get_usage();
				error_log("\t\tSection: Getting data for courses.... \n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
				$dataForRankLibrary = $this->dataLib->getDataForCourses($coursesData);
				error_log("\t\tSection: Got it. | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

				$configurableData = array();

				$time_start = microtime_float(); $start_memory = memory_get_usage();
				error_log("\t\tSection: Getting Configurable Data For InstituteId $instituteId.... \n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
				$configurableData = $this->popularityModel->getConfigurableDataByInstituteId($instituteId);
				error_log("\t\tSection: Got it. | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

				$instituteHierarchies = array(); $instituteCourses = array();

				$time_start = microtime_float(); $start_memory = memory_get_usage();
				error_log("\t\t\tSection: Looping course data to add response count and view count.... \n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
				
				foreach($coursesData as $courseId => $instituteData) {

					if(!empty($instituteData['hierarchies'])) {
						foreach($instituteData['hierarchies'] as $hierarchy_id) {
							$instituteHierarchies[$hierarchy_id]['pageViews'] += $dataForRankLibrary['pageViews'][$courseId];
							$instituteHierarchies[$hierarchy_id]['responseCount'] += $dataForRankLibrary['responseCount'][$courseId];
						}
					}

					if(!empty($instituteData['baseCourses'])) {
						foreach($instituteData['baseCourses'] as $baseCourseId) {
							$instituteCourses[$baseCourseId]['pageViews'] += $dataForRankLibrary['pageViews'][$courseId];
							$instituteCourses[$baseCourseId]['responseCount'] += $dataForRankLibrary['responseCount'][$courseId];
						}
					}
				}
				error_log("\t\t\tSection: Looping done. | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

				$insertValues = array(); $insertValuesHierarchy = array(); $insertValuesCourses = array();

				// Score Calculation for hierarchies
				$time_start = microtime_float(); $start_memory = memory_get_usage();
				error_log("\t\tSection: Doing score calculations.... \n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
				
				if(!empty($instituteHierarchies)) {
					$insertValuesHierarchy = $this->rankLib->getValuesForInsertion($instituteId, 'hierarchy', $instituteHierarchies, $configurableData['hierarchy']);
				}

				// Score Calculation for Base Courses
				$time_start = microtime_float(); $start_memory = memory_get_usage();
				
				if(!empty($instituteCourses)) {
					$insertValuesCourses = $this->rankLib->getValuesForInsertion($instituteId, 'base_course', $instituteCourses, $configurableData['base_course']);
				}
				
				$insertValues = array_merge($insertValuesHierarchy, $insertValuesCourses);				

				$insertValuesInsttWise[$instituteId] = $insertValues;
				unset($insertValues);
				unset($insertValuesHierarchy);
				unset($insertValuesCourses);
				unset($dataForRankLibrary);
				unset($instituteHierarchies);
				unset($instituteCourses);
				$institutes[$instituteId] = $instituteId;

				error_log("\t\tSection: Done. | ".getLogTimeMemStr($time_start, $start_memory)."\n\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
			}
			
			$time_start = microtime_float(); $start_memory = memory_get_usage();
			error_log("\tSection: Inserting values .... \n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
			
			if(!empty($insertValuesInsttWise)) {
				$insert = array();
				foreach ($insertValuesInsttWise as $key => $value) {
					$insert = array_merge($insert, $value);
				}
				unset($insertValuesInsttWise);
				$numOfRows = count($insert);
				
				$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
				$this->popularityModel->markPreviousDataHistory($institutes);
				error_log("\tSection: Marked history. | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

				$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
				$this->popularityModel->storePopularityDataCategoryInstituteWise($insert);
				error_log("\tSection: Insertion done. | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);
			}

			error_log("\tSection: Insertion done for $numOfRows rows.... | ".getLogTimeMemStr($time_start, $start_memory)."\n\n", 3, LOG_POPULARITY_PERFORMANCE_DATA_FILE_NAME);

			foreach($institutes as $instituteId) {
				Modules::run('search/Indexer/addToQueue', $instituteId, 'institute');
			}

			unset($institutes);
		}

	}

}

?>