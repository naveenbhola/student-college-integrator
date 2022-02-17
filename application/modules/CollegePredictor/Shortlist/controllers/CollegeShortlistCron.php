<?php
	class CollegeShortlistCron extends MX_Controller {
		function __construct(){

		}
		function updateCourseDataInCollegeShortlist(){
			$this->validateCron();
			ini_set("memory_limit", "30000M");
			set_time_limit(0);

			$shortlistLib = $this->load->library('Shortlist/collegeshortlistlibrary');
			$shortlistLib->updateCourseDataInCollegeShortList();
		}

		public function cacheSanitizedPredictorExamNames(){
	        // $this->validateCron();
	        ini_set("memory_limit", "6000M");
	        ini_set('max_execution_time', -1);

	        $shortlistLib = $this->load->library('Shortlist/collegeshortlistlibrary');
			$shortlistLib->cacheSanitizedPredictorExamNames();
			_p("Done");
	   }
	}
?>