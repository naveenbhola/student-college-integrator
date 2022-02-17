<?php

class Listing_cache
{
	private $_cachelib;
	private $_app_id = 1;
	private $_course_info_key = 'ncoursekey';
	private $_institute_info_key = 'ninstitutekey';
	
	function __construct($cachelib)
	{
		$this->_cachelib = $cachelib;
	}
	
	function getInformationForCourses($course_ids)
    {
		$info_for_courses = array();
		
		foreach($course_ids as $course_id)
		{
			if($course_info = $this->getInformationForCourse($course_id))
			{
				$info_for_courses[$course_id] = $course_info;
			}
		}

		return $info_for_courses;
    }
	
	function getInformationForCourse($course_id)
    {
		$course_cache_key = md5($this->_course_info_key.$this->_app_id.$course_id);
		
		if($this->_cachelib->get($course_cache_key) == 'ERROR_READING_CACHE')
		{
			return FALSE;
		}
		
		return $this->_cachelib->get($course_cache_key);
    }
	
	function storeInformationForCourse($course_id,$data)
	{
		$course_cache_key = md5($this->_course_info_key.$this->_app_id.$course_id);
		$this->_cachelib->store($course_cache_key,$data);
	}
	
	function getInformationForInstitutes($institute_ids)
    {
		$info_for_institutes = array();
		
		foreach($institute_ids as $institute_id)
		{
			if($institute_info = $this->getInformationForInstitute($institute_id))
			{
				$info_for_institutes[$institute_id] = $institute_info;
			}
		}

		return $info_for_institutes;
    }
	
	function getInformationForInstitute($institute_id)
    {
		$institute_cache_key = md5($this->_institute_info_key.$this->_app_id."'".$institute_id."'");
		
		if($this->_cachelib->get($institute_cache_key) == 'ERROR_READING_CACHE')
		{
			return FALSE;
		}
		
		return $this->_cachelib->get($institute_cache_key);
    }
	
	function storeInformationForInstitute($institute_id,$data)
	{
		$institute_cache_key = md5($this->_institute_info_key.$this->_app_id."'".$institute_id."'");
		$this->_cachelib->store($institute_cache_key,$data);
	}
}
?>
