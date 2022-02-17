<?php 

class Abroad_SimilarInstitutes_Model extends MY_Model
{
	private $_random_seed_course;
	
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
	
	function getSimilarInstitutes($seed_data,$result_count,$exclusion_list)
	{
		$this->initiateModel();
		
		$course_id = $seed_data['course_id'];
		$city_id = $seed_data['city_id'];
		$state_id = $seed_data['state_id'];
		$country_id = $seed_data['country_id'];
		$ldb_course_id = $seed_data['ldb_course_id'];
		
		/**
		 * If LDB course id pre-defined, use that
		 */ 
		if($ldb_course_id) {
			$shiksha_course_ids = array($ldb_course_id);
		}
		else {
			/*
			 * Otherwise get all LDB courses
			 */
			$sql = "SELECT DISTINCT LDBCourseID as shiksha_course_id FROM `clientCourseToLDBCourseMapping` WHERE `clientCourseID` = ? AND status = 'live'";
			$query = $this->_db->query($sql, array($course_id));
			$shiksha_course_ids = $this->_getResultArray($query,'shiksha_course_id');
		}
		
		if(count($shiksha_course_ids))
		{
			if(isset($country_id) && $country_id != 2) {
				$locationClause = "cd.country_id = ".$this->_db->escape($country_id);
			}
			else {
				$locationClause = ($city_id ? "cd.city_id = ".$this->_db->escape($city_id)." " : "cd.state_id = ".$this->_db->escape($state_id)." ");
			}
			
			
			$tableName = '';
			$sql = "SELECT course_id FROM categoryPageData WHERE course_id = ? AND status = 'live'";
			$query = $this->_db->query($sql, array($course_id));
			$rows = $query->result();
			if(count($rows)) {
				$tableName = 'categoryPageData';
			}
			else {
				$tableName = 'abroadCategoryPageData';
			}
			
			
			/*
			 * Now get similar institutes
			 */
			
			$limit_clause = $result_count>0?"LIMIT ".$result_count:"";

			$getSql = "SELECT DISTINCT cd.institute_id, cd.course_id, lm.viewCount as view_count
						FROM clientCourseToLDBCourseMapping cclm
						INNER JOIN  ".$tableName." cd ON (cd.course_id = cclm.clientCourseID AND cd.status = 'live')
						INNER JOIN listings_main lm ON (lm.listing_type_id = cd.institute_id AND lm.listing_type = 'institute' AND lm.status='live')
						WHERE cclm.LDBCourseID IN (".implode(',',$shiksha_course_ids).")
						AND ".$locationClause.
						(count($exclusion_list)?" AND cd.institute_id NOT IN (".implode(',',$exclusion_list).")":"")."
						AND cclm.status = 'live'
						GROUP BY cd.institute_id
						$limit_clause ";
			$query = $this->_db->query($getSql);
										
			$rows = $query->result();
			
			if(count($rows))
			{
				$this->_random_seed_course = $course_id;
			}
				
			return $rows;	
		}
	}
	
	function getRandomSeedCourse()
	{
		return $this->_random_seed_course;
	}
	
	private function _getResultArray($query,$field)
	{
		$result = array();
		foreach($query->result() as $row)
		{
			$result[] = $row->$field;
		}
		return $result;
	}
}
