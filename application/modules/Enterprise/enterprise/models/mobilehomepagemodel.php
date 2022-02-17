<?php

class mobilehomepagemodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */
	private function _getDbHandle($operation='read')
	{
		if($operation=='read'){
			$dbHandle = $this->getReadHandle();
		}
		else{
			$dbHandle = $this->getWriteHandle();
		}
		if($dbHandle == ''){
			error_log('error can not create db handle');
		}
		return $dbHandle;
	}
	
	public function getPopularCourseList($limit)
	{
		$dbHandle = $this->_getDbHandle('read');
		$sql = 'select id, id as popularCourseId, name, subcatId, parentId, bgcolor from mobile_homepage_popular_course where status="live" order by id limit ?';
		$query = $dbHandle->query($sql, array($limit));
		return $query->result_array();
	}
	
	/*public function getPopularCourseListForPreview($limit)
	{
		$dbHandle = $this->_getDbHandle('read');
		$sql = 'select id, id as popularCourseId, name, subcatId, parentId, bgcolor from mobile_homepage_popular_course where status="preview" order by id limit ?';
		$query = $dbHandle->query($sql, array($limit));
		return $query->result_array();
	}*/
	
	public function getRightSideMainLinks($popularCourseIdsArray = array(), $limit)
	{
		$dbHandle = $this->_getDbHandle('read');
		$sql = 'select * from mobile_homepage_right_main_links where menu = "top" and popularCourseId in (?) order by id';
		$query = $dbHandle->query($sql, array($popularCourseIdsArray));
		return $query->result_array();
	}
	
	public function getStudentToolLinks($popularCourseIdsArray = array(), $limit)
	{
		$dbHandle = $this->_getDbHandle('read');
		$sql = 'select * from mobile_homepage_right_main_links where menu = "tool" and popularCourseId in (?) order by id';
		$query = $dbHandle->query($sql, array($popularCourseIdsArray));
		return $query->result_array();
	}
	
	public function getPopularExamList($popularCourseIdsArray = array(), $limit)
	{
		$dbHandle = $this->_getDbHandle('read');
		$sql = 'select * from mobile_homepage_popular_exam where popularCourseId in (?) order by id';
		$query = $dbHandle->query($sql, array($popularCourseIdsArray));
		return $query->result_array();
	}
	
	public function disableAllPopularCourse()
	{
		$dbHandle = $this->_getDbHandle('write');
		$sql = 'update mobile_homepage_popular_course set status = "deleted" where status = "live"';
		$query = $dbHandle->query($sql);
	}
	
	/*public function disableOldPreviewConfig()
	{
		$dbHandle = $this->_getDbHandle('write');
		$sql = 'update mobile_homepage_popular_course set status = "deleted" where status = "preview"';
		$query = $dbHandle->query($sql);
	}
	
	public function publishAllNewPopularCourses()
	{
		$dbHandle = $this->_getDbHandle('write');
		$sql = 'update mobile_homepage_popular_course set status = "live" where status = "preview"';
		$query = $dbHandle->query($sql);
	}*/
	
	public function addPopularCourse($insertData)
	{
		$dbHandle = $this->_getDbHandle('write');
		$dbHandle->insert('mobile_homepage_popular_course',$insertData);
		return $dbHandle->insert_id();
	}
	
	public function addRightSideLinksData($insertData)
	{
		$dbHandle = $this->_getDbHandle('write');
		$dbHandle->insert('mobile_homepage_right_main_links',$insertData);
		return $dbHandle->insert_id();
	}
	
	public function addRightSideToolsData($insertData)
	{
		$dbHandle = $this->_getDbHandle('write');
		$dbHandle->insert('mobile_homepage_right_main_links',$insertData);
		return $dbHandle->insert_id();
	}
	
	public function addPopularExamsData($insertData)
	{
		$dbHandle = $this->_getDbHandle('write');
		$dbHandle->insert('mobile_homepage_popular_exam',$insertData);
		return $dbHandle->insert_id();
	}
}
