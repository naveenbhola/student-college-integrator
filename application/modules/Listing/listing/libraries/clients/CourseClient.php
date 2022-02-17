<?php

class CourseClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'listing';
		$this->serverFile = 'CourseServer';
	}
	
	function getDataForCourse($courseId,$filters = '')
	{
		$request = array($courseId,$filters); 
		return $this->executeRequest('getDataForCourse',$request);
	}
	
	function getDataForMultipleCourses($courseIds,$filters = '',$isCustomFilter = FALSE)
	{  
		$courseIds = utility_encodeXmlRpcResponse($courseIds);
		$request = array($courseIds,$filters,$isCustomFilter); 
		return $this->executeRequest('getDataForMultipleCourses',$request);
	}
}

