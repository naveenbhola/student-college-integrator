<?php

class LDBCourseCache extends Cache
{
    function __construct()
	{
		parent::__construct();
	}
    
    /*
	 * LDB Course
	 */ 
	function getLDBCourse($LDBCourseId)
    {
		return unserialize($this->get('LDBCourse',$LDBCourseId));
    }
	
	function storeLDBCourse($LDBCourse)
	{
		$this->store('LDBCourse',$LDBCourse->getId(),serialize($LDBCourse),-1,CACHEPRODUCT_LDBCOURSE,0);
	}
        
        function getMultiLDBCourses($LDBCourseIds) {
            $data = $this->multiGet('LDBCourse', $LDBCourseIds);
            $res = array();
            
            foreach($data as $key => $value) {
                $res[$key] = unserialize($value);
            }
            return $res;
        }

        

        /*
	 * LDB Courses for Sub-Category
	 */ 
	function getLDBCoursesForSubCategory($subCategoryId)
    {
		return unserialize($this->get('LDBCoursesForSubCategory',$subCategoryId));
    }
	
	function storeLDBCoursesForSubCategory($subCategoryId,$LDBCourses)
	{
		$this->store('LDBCoursesForSubCategory',$subCategoryId,serialize($LDBCourses),-1,CACHEPRODUCT_LDBCOURSE,0);
	}
}