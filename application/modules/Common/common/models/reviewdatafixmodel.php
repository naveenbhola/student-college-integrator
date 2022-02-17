<?php 
class reviewdatafixmodel extends MY_Model {
	var $totalReviewsToBeDeleted = 0;
	var $totalReviewsWithWrongInstitute = 0;
	var $totalReviewsToBeMigrated = 0;
	
	function __construct() {
		parent::__construct('default');
		$this->CollegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
	}

	private function initiateModel($mode = "write", $module = ''){
		if($mode == 'read') {
		    $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
		    $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function checkAllReviewData(){
		$this->initiateModel('read');
	
		//First fetch all the Reviews which are not yet deleted
		$courses = array();
		$sql =  "SELECT map.reviewId, map.instituteId, map.locationId, map.courseId FROM CollegeReview_MappingToShikshaInstitute map, CollegeReview_MainTable main WHERE map.reviewId = main.id AND main.status NOT IN ('deleted','history','rejected')";
		$reviews = $this->dbHandle->query($sql)->result_array();
		
		foreach ($reviews as $review){
			$courseId = $review['courseId'];
			
			//Now, for each review, check if the Course Is Live.
			$query = "SELECT * FROM shiksha_courses WHERE course_id = ? AND status = 'live'";
			$courses = $this->dbHandle->query($query, array($review['courseId']))->result_array();
			if(count($courses) <= 0){
				
				//Also, check if the Course has been migrated to another Listing. If yes, replace it in Review table. If not, delete the review
				$query =  "SELECT replacement_lisiting_type_id FROM deleted_listings_mapping_table del, shiksha_courses sc WHERE del.listing_type_id = ? AND del.listing_type = 'course' AND sc.status = 'live' AND sc.course_id = del.listing_type_id ORDER BY del.id DESC LIMIT 1";
				$migratedCourse = $this->dbHandle->query($query, array($courseId))->result_array();
				if(count($migratedCourse) <= 0){	
					error_log("Review Id = ".$review['reviewId'].", For courseId = ".$courseId." . Course is deleted.");
					$this->CollegeReviewLib->deleteCourseIdForReview($courseId);
					$this->totalReviewsToBeDeleted++;
				}
				else{
					error_log("Review Id = ".$review['reviewId'].", For courseId = ".$courseId." . Course is migrated to another course.");
					$this->CollegeReviewLib->updateCourseIdForReview(array($courseId => $migratedCourse['replacement_lisiting_type_id']));
					$this->totalReviewsToBeMigrated++;					
				}
				
			}
			else{			
				//Now, check if the Course & Institute are mapped correctly
				if($review['instituteId'] != $courses[0]['primary_id']){
					//If not, find the Primary InstituteId of this course & update it in Review table
					error_log("Review Id = ".$review['reviewId'].", For courseId = ".$courseId." . Course-Inst mapping is incorrect.");
					$this->CollegeReviewLib->updateCourseIdForReview(array($courseId => $courseId));
					$this->totalReviewsWithWrongInstitute++;
				}				
			}
		}
		
		error_log("Total reviews which need to be deleted == ".$this->totalReviewsToBeDeleted);
		error_log("Total reviews which need to be migrated to another course == ".$this->totalReviewsToBeMigrated);
		error_log("Total reviews where Institute mapping is wrong == ".$this->totalReviewsWithWrongInstitute);
		return true;
	}

}
?>