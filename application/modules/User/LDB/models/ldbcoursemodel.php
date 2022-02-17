<?php

class LDBCourseModel extends MY_Model
{
	private $dbHandle;
	
    function __construct()
    {
        parent::__construct('LDB');
    }
	
	private function initiateModel($operation = 'read')
	{
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	public function getLDBCourse($LDBCourseId)
	{
		Contract::mustBeNumericValueGreaterThanZero($LDBCourseId,'LDB Course ID');
		
		$this->initiateModel();
		/*$sql =  "SELECT t.*, m.categoryID as SubCategoryId ".
				"FROM tCourseSpecializationMapping t, LDBCoursesToSubcategoryMapping m ".
				"WHERE t.SpecializationId = '".$LDBCourseId."' ".
				"AND m.status = 'live' ".
				"AND m.ldbCourseID = t.SpecializationId ".
				"AND t.status = 'live'";
		*/
		
		$sql =  "SELECT t.*, m.categoryID as SubCategoryId ".
				"FROM tCourseSpecializationMapping t LEFT JOIN LDBCoursesToSubcategoryMapping m ".
				"ON ( m.status = 'live' AND m.ldbCourseID = t.SpecializationId ) ".
				"WHERE t.SpecializationId = ? ".
				"AND t.status = 'live'";		
		
		return $this->dbHandle->query($sql, array($LDBCourseId))->row_array();		
	}
        
        public function getMutlipleLDBCourses($LDBCourseIds)
	{
            Contract::mustBeNonEmptyArrayOfIntegerValues($LDBCourseIds,'LDB Course ID');

            $this->initiateModel();

            $this->dbHandle->select('t.*, m.categoryID as SubCategoryId', false);
            $this->dbHandle->from('tCourseSpecializationMapping t');
            $this->dbHandle->join('LDBCoursesToSubcategoryMapping m', 'm.status = "live" AND m.ldbCourseID = t.SpecializationId', 'LEFT');
            $this->dbHandle->where_in('t.SpecializationId', $LDBCourseIds);
            $this->dbHandle->where('t.status', 'live');

            return $this->dbHandle->get()->result_array();
	}

	/*** To get the LDB courses (from course_pages_subcategory_desiredcourse_mapping) not to be shown in add new course form in enterprise login***/
	public function getGloballyRestrictedLDBCourses($allow_global_registration, $status){
		
		$this->initiateModel();
		
		$sql = "select
				subCatId as sub_cat_id,
				desiredCourseId as ldb_course_id
			from course_pages_subcategory_desiredcourse_mapping
			where
				allow_global_registration = ?
				and status = ?";
		
		return $this->dbHandle->query($sql, array($allow_global_registration, $status))->result_array();
		
	}
	/*** END : To get the LDB courses (from course_pages_subcategory_desiredcourse_mapping) not to be shown in add new course form in enterprise login***/
	
	private function _getCachedRestrictedCourses(){
		
		$ci = & get_instance();
		$cache_lib = $ci->load->library('cacheLib');
		$key = "getGloballyRestrictedLDBCourses";

		if($cache_lib->get($key) == 'ERROR_READING_CACHE') {	
			$restrictedLDBCourses = $this->getGloballyRestrictedLDBCourses('no', 'live');
			$cache_lib->store($key,$restrictedLDBCourses,86400);
		} else {
			$restrictedLDBCourses = $cache_lib->get($key);
		}
           
        unset($ci);   
        unset($cache_lib);

        return $restrictedLDBCourses;
	}
	public function getLDBCoursesForSubCategory($subCategoryId)
	{
		Contract::mustBeNumericValueGreaterThanZero($subCategoryId,'Subcategory ID');
		$restrictedCourses = "";
		$restrictedLDBCourses = $this->_getCachedRestrictedCourses();
       
		foreach($restrictedLDBCourses as $key => $value) {			
			$restrictedCourses .= ($restrictedCourses == "" ? "" : ",").$value['ldb_course_id'];
		}
		
		if($restrictedCourses != "") {
			$restrictedLDBClause = " AND T.SpecializationId NOT IN (".$restrictedCourses.") ";
		}

		$this->initiateModel();
		$sql =  "SELECT L.isPopularCourse,T.* ".
				"FROM LDBCoursesToSubcategoryMapping L ".
				"INNER JOIN tCourseSpecializationMapping T ON T.SpecializationId = L.ldbCourseID ".
				"WHERE L.categoryID = ? ".
				"AND L.status = 'live' AND T.status = 'live' ".$restrictedLDBClause;
		return $this->dbHandle->query($sql, array($subCategoryId))->result_array();
	}
	
	public function getLDBCoursesForClientCourse($clientCourseId)
	{
		Contract::mustBeNumericValueGreaterThanZero($clientCourseId,'Client Course ID');
		$restrictedCourses = "";
		$restrictedLDBCourses = $this->_getCachedRestrictedCourses();
		
		foreach($restrictedLDBCourses as $key => $value) {			
			$restrictedCourses .= ($restrictedCourses == "" ? "" : ",").$value['ldb_course_id'];
		}
		
		if($restrictedCourses != "") {
			$restrictedLDBClause = " AND T.SpecializationId NOT IN (".$restrictedCourses.") ";
		}

		$this->initiateModel();
		$sql =  "SELECT T.*, S.categoryID AS SubCategoryId ".
				"FROM clientCourseToLDBCourseMapping L ".
				"INNER JOIN tCourseSpecializationMapping T ON T.SpecializationId = L.LDBCourseID ".
				"INNER JOIN LDBCoursesToSubcategoryMapping S ON S.ldbCourseID = T.SpecializationId ".
				"WHERE L.clientCourseID = ? ".
				"AND L.status = 'live' AND T.status = 'live' AND S.status = 'live' ".$restrictedLDBClause;
		return $this->dbHandle->query($sql, array($clientCourseId))->result_array();
	}
	
	public function getSpecializations($LDBCourseId)
	{
		Contract::mustBeNumericValueGreaterThanZero($LDBCourseId,'LDB Course ID');
		
		$this->initiateModel();
		$sql =  "SELECT * ".
				"FROM tCourseSpecializationMapping ".
				"WHERE ParentId = ? ".
				"AND Status = 'live'";
				
		return $this->dbHandle->query($sql, array($LDBCourseId))->result_array();		
	}
	
	public function getTestPrepCourseMapping($LDBCourseId) {
		Contract::mustBeNumericValueGreaterThanZero($LDBCourseId,'LDB Course ID');
		
		$this->initiateModel();
		
		$sql = "SELECT blogId
			FROM testPrepCourseMapping
			WHERE specializationId = ?
			AND status = 'live'";
			
		return $this->dbHandle->query($sql, array($LDBCourseId))->result_array();
	}

	function getSubCategoriesForMultipleClientCourse($clientCourseIds) {
		//mail added for code scan safe check    
        mail('teamldb@shiksha.com', 'function -getSubCategoriesForMultipleClientCourse call at'.date('Y-m-d H:i:s'), 'Data: '.print_r($clientCourseIds,true)."        ".$_SERVER['SCRIPT_URI']." http referer : ". $_SERVER['HTTP_REFERER']);
        return;
		$this->initiateModel();
		$sql = "SELECT category_id, course_id ".
				"FROM categoryPageData ".
				"WHERE course_id IN (".implode(',',$clientCourseIds).") ".
				"AND STATUS = 'live' ".
				"GROUP BY course_id, category_id;";
				
		return $this->dbHandle->query($sql)->result_array();
	}
}