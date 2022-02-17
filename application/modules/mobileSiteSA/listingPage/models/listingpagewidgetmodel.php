<?php 
class listingpagewidgetmodel extends MY_Model{
	private $dbHandle;
	private $dbHandleMode;
	public function __construct(){
		parent::__construct('ShikshaApply');
	}

	private function initiateModel($mode = 'read'){
		if($this->dbHandle && $this->dbHandleMode == 'write'){
			return;
		}

		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;

		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		}elseif($mode == 'write'){
			$this->dbHandle = $this->getWriteHandle();
		}
    }

    public function getEnrolledUsers($courseIdArr, $limit,$excludeUserList){
        
        
        if(empty($courseIdArr) || !is_array($courseIdArr)){
            return array();
        }
        $this->initiateModel();
        $this->dbHandle->select('courseId, userId');
        $this->dbHandle->from('rmcCandidateEnrollmentCourse');
        $this->dbHandle->where('enrollmentStatus', 'yes');
        $this->dbHandle->where('status', 'live');
        $this->dbHandle->where_in('courseId', $courseIdArr);
        if(!empty($excludeUserList))
        $this->dbHandle->where_not_in('userId', $excludeUserList);
        $this->dbHandle->order_by('modifiedOn desc');
        $this->dbHandle->limit($limit);
        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

    public function getAdmittedUsers($courseIdArr, $limit){
        if(empty($courseIdArr) || !is_array($courseIdArr)){
            return array();
        }
        $this->initiateModel();
    	$this->dbHandle->select('courseId, userId');
    	$this->dbHandle->from('rmcCandidateFinalizedCourses');
        $this->dbHandle->where('admissionOffered', 'accepted');
        $this->dbHandle->where('admissionTaken', 'yes');
        $this->dbHandle->where('status', 'live');
    	$this->dbHandle->where_in('courseId', $courseIdArr);
    	$this->dbHandle->limit($limit);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getAllCoursesWithFilter($filter, $courseIdsToBeSkipped = array()){
        if(empty($filter['univId']) && empty($filter['countryId'])){
            return array();
        }
        $this->initiateModel();
        $this->dbHandle->select('distinct course_id', false);
        $this->dbHandle->from('abroadCategoryPageData');
        $this->dbHandle->where('status', 'live');
        if(!empty($filter['univId'])){
            $this->dbHandle->where('university_id', $filter['univId']);
        }
        if(!empty($filter['countryId'])){
            $this->dbHandle->where('country_id', $filter['countryId']);
        }
        if(!empty($filter['courseLevels'])){
            $this->dbHandle->where_in('course_level', $filter['courseLevels']);
        }
        if(!empty($courseIdsToBeSkipped)){
            $this->dbHandle->where_not_in('course_id', $courseIdsToBeSkipped);
        }
        $this->dbHandle->limit($limit);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }
}