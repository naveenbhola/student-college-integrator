<?php

class AlsoViewed_Precomputation_Model extends MY_Model
{
	function __construct()
	{
		parent::__construct('recommendation');
	}

	private function initiateModel($operation = 'read')
	{
		if($operation=='read'){
			$this->_db = $this->getReadHandle();
		}
		else{
        	$this->_db = $this->getWriteHandle();
		}
	}

	public function getCoursesToBeUpdated($type)
	{
		$this->initiateModel();
		
        if($type == 'full') {
            $sql = "SELECT course_id ".
                   "FROM shiksha_courses ".
                   "WHERE status = 'live'";
        }
        else {
            $sql = "SELECT DISTINCT also_viewed_course_mapping.course_id
                    FROM also_viewed_course_mapping, shiksha_courses
                    WHERE also_viewed_course_mapping.course_id = shiksha_courses.course_id
                    AND also_viewed_course_mapping.is_Updated = 0
                    AND shiksha_courses.status = 'live'";
        }
		$result = $this->_db->query($sql)->result_array();
		return $this->getColumnArray($result, 'course_id');
	}
	
	function updatePreComputedAlsoViewed($courses, $preComputedAlsoViewed)
	{
		$this->initiateModel('write');
		
		if(is_array($courses) && count($courses) > 0) {
			$sql = "UPDATE alsoViewedFilteredCourses ".
				   "SET status = 'history' ".
				   "WHERE course_id IN (".implode(',',$courses).")";
			$this->_db->query($sql);
		}
		
		if(is_array($preComputedAlsoViewed) && count($preComputedAlsoViewed)) {
			$this->_db->insert_batch('alsoViewedFilteredCourses', $preComputedAlsoViewed);
		}
		
		$sql = "UPDATE also_viewed_course_mapping ".
			   "SET is_Updated = 1 ".
			   "WHERE course_id IN (".implode(',',$courses).")";
        $this->_db->query($sql);
	}
}
