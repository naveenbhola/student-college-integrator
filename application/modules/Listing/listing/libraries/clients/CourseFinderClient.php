<?php

class CourseFinderClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'listing';
		$this->serverFile = 'CourseFinderServer';
	}
	
	public function getCoursesByInstitute($instituteId)
	{
		$request = array($instituteId);
		return $this->executeRequest('getCoursesByInstitute',$request);
	}
	
	public function getCoursesByMultipleInstitutes($instituteIds)
	{
		$request = array(array($instituteIds,'struct'));
		return $this->executeRequest('getCoursesByMultipleInstitutes',$request);
	}
	
	public function getModifiedCourses($criteria)
	{
		$request = array(utility_encodeXmlRpcResponse($criteria));
		return $this->executeRequest('getModifiedCourses',$request);
	}
	
	public function getLiveCourses()
	{
		$request = array();
		return $this->executeRequest('getLiveCourses',$request);
	}
}

