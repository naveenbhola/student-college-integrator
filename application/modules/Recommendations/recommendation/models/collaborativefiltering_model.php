<?php

class CollaborativeFiltering_Model extends MY_Model
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
	
	function getCollaborativeFilteredCourses($courseIds, $exclusionList, $limit = 20)
	{
		if(!count($courseIds) || empty($courseIds)) {
			return;
		}

		$exclusionList = array_filter($exclusionList);
		
		$this->initiateModel();
		$this->_db->select('recommended_course_id as courseId, recommended_institute_id as instituteId')->from('collaborativeFilteredCoursesReco as a');
		$this->_db->join('shiksha_courses as b','b.course_id = a.recommended_course_id');
		$this->_db->where(" b.status='live' ",NULL,false);
		$this->_db->where_in("a.course_id",$courseIds,false);
		$this->_db->order_by('a.weight','desc');
		if(!$_REQUEST['disableRecoLimit']) {
			if(!empty($exclusionList)){
				$this->_db->where_not_in('recommended_institute_id', $exclusionList);
			}
			$maxCourseCountOfInstitute = 50;
			$limit = $maxCourseCountOfInstitute * $limit;
			$this->_db->limit($limit);
		}
		// _p($this->_db->_compile_select());
		$query = $this->_db->get();

		return $query->result_array();
	}
}
