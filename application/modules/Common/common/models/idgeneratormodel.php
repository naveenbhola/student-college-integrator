<?php

class IDGeneratorModel extends MY_Model
{
    private static $entityTableMapping = array(
        'institute' 			=> 'tickets_institute',
        'instituteLocation' 		=> 'tickets_institute_location',
        'course' 			=> 'tickets_course',
		'snapshot_course' 		=> 'tickets_snapshot_course',
		'university' 			=> 'tickets_university',
		'university_location' 		=> 'tickets_university_location',
		'study_abroad_ranking' 		=> 'tickets_study_abroad_ranking',
		'content' 			=> 'tickets_study_abroad_content',
		'admissionprocessguide' 	=> 'tickets_admission_process_guide',
		//'RMS_counsellor' 		=> 'tickets_RMS_counsellor',
        'exampage'			=> 'tickets_exampage',
        'ranking_source'		=> 'tickets_ranking_page_sources',
        'ranking_publisher'        => 'tickets_ranking_page_publishers',
        'ranking_source_params'		=> 'tickets_ranking_page_source_params',
		'coupons'			=> 'tickets_coupons',
        'consultant' 			=> 'tickets_consultant',
        'consultant_location' 		=> 'tickets_consultant_location',
        'consultant_student_profile'    => 'tickets_consultant_student_profile',
		'myshortlist_notes' => 'tickets_myshortlist_notes',
		'university_application_profiles' => 'tickets_university_application_profiles',
		'rmcUserCourseRating'=>'tickets_rmcUserCourseRating',
        'searchFilterTracking' => 'tickets_searchFilterTracking',
        'homepagecms'   => 'tickets_homepageBannerProduct',
        // 'institute_academic_staff' => 'tickets_institute_academic_staffs',
        // 'institute_events' => 'tickets_institute_events',
        'scholarship'	=> 'tickets_scholarship',
        'content_navbar' => 'tickets_content_navbar',
        'sa_publisher_master' => 'tickets_sa_publisher_master',
        'sa_publisher_ranking_master' => 'tickets_sa_publisher_ranking_master',
        'sa_publisher_ranking_buckets' => 'tickets_sa_publisher_ranking_buckets'
    );
    
    private $dbHandle = null;
    
    function __construct()
	{
		parent::__construct('Listing');
    }

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
    
    public function generateId($entity)
    {
        $table = self::$entityTableMapping[$entity];
        if(!$table) {
            return FALSE;
        }
        
        $this->initiateModel();
        $sql = "REPLACE INTO $table (stub) VALUES ('a');";
        $query = $this->dbHandle->query($sql);
        
        return $this->dbHandle->insert_id();
    }
}
