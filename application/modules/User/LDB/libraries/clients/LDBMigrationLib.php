<?php
class LDBMigrationLib{

	var $CI;
	
	function init(){
		$this->CI = &get_instance();
		$this->CI->load->library('response/responseLib');
		$this->CI->load->library('CollegeReviewForm/CollegeReviewLib');
	}

	function updateDataForCourseDeletion($courses = array()){
		$this->init();
		$this->responseLib = $this->CI->responselib;
		$this->collegeReviewLib = $this->CI->collegereviewlib;
		$this->responseLib->responseMigration($courses);
		$this->collegeReviewLib->updateCourseIdForReview($courses);
		return;
	}

	function updateDataForInstituteDeletion($oldInstituteIds,$newInstitute){
		$this->init();
		$this->collegeReviewLib = $this->CI->collegereviewlib;
		$this->collegeReviewLib->updateInstituteIdForReview($oldInstituteIds,$newInstitute);
		return;
	}

	public function migrateInstituteScript($old_institute_id, $new_institute_id, $institute_data){
		error_log('======================== Hey! LDB Migation Just Started =====================');

		$this->init();
		$LDBMigrationModel = $this->CI->load->model('LDB/LDBMigrationModel');
		$old_location_id = $institute_data[$old_institute_id]['listingLocationId'];
		$new_location_id = $institute_data[$new_institute_id]['listingLocationId'];

		//Institute Migraton
		$LDBMigrationModel->migrateRecommendationInfo($old_institute_id, $new_institute_id);
		$LDBMigrationModel->migratePortingConditions($old_institute_id, $new_institute_id);
		$LDBMigrationModel->migrateInstCRMapToShikshaInst($old_institute_id, $new_institute_id);
		$LDBMigrationModel->migrateLatestResponseData($old_institute_id, $new_institute_id);
		$this->migrateUserSearchCriteria($old_institute_id, $new_institute_id);

		//location migration
		$LDBMigrationModel->migrateLocationCRMapToShikshaInst($old_location_id, $new_location_id);
		$LDBMigrationModel->migrateResponseLocationTable($old_location_id, $new_location_id);

		error_log('======================== Congrats!LDB Migraton FINISHED Successfully! =====================');
	}

	public function migrateUserSearchCriteria($old_institute_id, $new_institute_id){
		
		$LDBMigrationModel = $this->CI->load->model('LDB/LDBMigrationModel');
		$mailer_model = $this->CI->load->model('mailer/mailermodel');
	   
	   	$result  = $mailer_model->getActivityUserSearchCriteria();

	    foreach ($result as $criteria) {
	    	$criteria_data = json_decode($criteria['criteriaJSON'],true);
	    		    	
	    	$instituteIds = $criteria_data['college_ids'];
	    	$instituteIds = explode(',', $instituteIds);
	    	if(count($instituteIds)<1){
	    		continue;
	    	}

	    	$new_institute_ids = array();
	    	$update_flag= false;
	    	foreach ($instituteIds as $id) {
	    		if($id == $old_institute_id){
	    			$new_institute_ids[] = $new_institute_id;
	    			$update_flag= true;
	    		}else{
	    			$new_institute_ids[] = $id;
	    		}
	    	}

	    	if(!$update_flag){
	    		continue;
	    	}

	    	$new_institute_ids = implode(',', $new_institute_ids);
	    	$criteria_data['college_ids'] = $new_institute_ids;
	    	$criteria_data = json_encode($criteria_data);

	    	
	    	$mailer_model->updateUserSearchCriteria($criteria_data,$criteria['id']);
	    }
	}
	

}
?>
