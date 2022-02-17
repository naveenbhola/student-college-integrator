<?php 
      
class AlsoViewedPrecomputation extends MX_Controller
{
	private $CI;
    private $alsoViewedModel;
	private $alsoViewedPrecomputationModel;
	private $courseModel;
	
	private $matchCourseFactory;
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('alsoviewed_model');
    	$this->alsoViewedModel = new AlsoViewed_Model;
		
		$this->load->model('alsoviewed_precomputation_model');
    	$this->alsoViewedPrecomputationModel = new AlsoViewed_Precomputation_Model;
		
		$this->load->model('nationalCourse/nationalcoursemodel');
    	$this->courseModel = new nationalcoursemodel;

		$this->load->library('recommendation/matchCourse/MatchCourseFactory');
		$this->matchCourseFactory = new MatchCourseFactory;
	}
	
    /**
     * type = full | incremental
     * full = run for all live courses
     * incremental = run only for courses updated after last run
     */ 
	function compute($type = 'incremental')
	{
		ini_set('memory_limit', '-1');
	    ini_set('max_execution_time', '-1');
		
		$this->validateCron();
		$coursesToBeUpdated = $this->alsoViewedPrecomputationModel->getCoursesToBeUpdated($type);
		if(count($coursesToBeUpdated) == 0) {
			return;
		}
        
		$this->matchCourseFactory->setBulkOp();
	
		$paidCourses = $this->courseModel->getPaidCourses();
		$paidCourseMap = array_flip($paidCourses);
		
		$alsoViewedCourseData = $this->alsoViewedModel->getAlsoViewedCoursesWithWeight($coursesToBeUpdated);
		
		$updatedCourses = array();
		$preComputedAlsoViewed = array();
		$batchSize = 50;
		
		foreach($alsoViewedCourseData as $seedCourseId => $alsoViewedDataForCourse) {
			$seedCourse = $this->matchCourseFactory->getMatchCourse($seedCourseId);
		
			$alsoViewedCourseIds = array_keys($alsoViewedDataForCourse);
			$alsoViewedCourses = $this->matchCourseFactory->getMatchCourses($alsoViewedCourseIds);
		
			/**
			 * Filter individual also viewed courseIds
			 */
			foreach($alsoViewedCourses as $alsoViewedCourse) {
				 /*
				  * Check if the seed course matches the also viewed course
				  * on the basis of filters defined
				  */
				 if($seedCourse->matches($alsoViewedCourse)) {
					
					$dataArray = array();
					$dataArray['course_id'] = $seedCourseId;
					$dataArray['recommended_course_id'] = $alsoViewedCourse->getId();
					$dataArray['recommended_institute_id'] = $alsoViewedCourse->getInstituteId();

					if(array_key_exists($alsoViewedCourse->getId(), $paidCourseMap)) {
						$dataArray['recommended_course_type'] = 'paid';
					}
					else {
						$dataArray['recommended_course_type'] = 'free';
					}

					$dataArray['weight'] = $alsoViewedDataForCourse[$alsoViewedCourse->getId()];
					$dataArray['weight_percentage'] = -1;

					$dataArray['status'] = 'live';
					
					$preComputedAlsoViewed[] = $dataArray;
				}
			}
		
			$updatedCourses[] = $seedCourseId;
			if(count($updatedCourses) == $batchSize) {
				$this->alsoViewedPrecomputationModel->updatePreComputedAlsoViewed($updatedCourses, $preComputedAlsoViewed);
				$updatedCourses = array();
				$preComputedAlsoViewed = array();
			}
		}
		
		if(count($updatedCourses) > 0) {
			$this->alsoViewedPrecomputationModel->updatePreComputedAlsoViewed($updatedCourses, $preComputedAlsoViewed);
		}
	}
}
