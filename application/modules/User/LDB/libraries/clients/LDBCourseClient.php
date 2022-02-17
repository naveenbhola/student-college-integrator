<?php

class LDBCourseClient extends XmlRpcClient
{
    function __construct()
	{
		parent::__construct();
		
		$this->server = 'LDB';
		$this->serverFile = 'LDBCourseServer';
	}
	
	function getLDBCourse($LDBCourseId)
	{
		$request = array($LDBCourseId); 
		return $this->executeRequest('getLDBCourse',$request);
	}
        
        
	function getMutlipleLDBCourses($LDBCourseIds)
	{
		$request = array(utility_encodeXmlRpcResponse($LDBCourseIds));
		return $this->executeRequest('getMutlipleLDBCourses',$request);
	}
                
	function getLDBCoursesForSubcategory($subCategoryId)
	{
		$request = array($subCategoryId); 
		return $this->executeRequest('getLDBCoursesForSubCategory',$request);
	}
	
	function getLDBCoursesForClientCourse($clientCourseId)
	{
		$request = array($clientCourseId); 
		return $this->executeRequest('getLDBCoursesForClientCourse',$request);
	}
	
	function getSpecializations($LDBCourseId)
	{
		$request = array($LDBCourseId); 
		return $this->executeRequest('getSpecializations',$request);
	}
}

