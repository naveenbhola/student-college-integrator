<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class AbroadCourseFinderModel extends ListingModelAbstract
{
    function __construct()
    {
        parent::__construct();
    }
	
	public function getCoursesOfferedByUniversity($universityId, $groupBy = 'department', $includeVirtualDept = false, $tabStatus = 'live')
	{
		Contract::mustBeNumericValueGreaterThanZero($universityId,'University ID');
		
		$courses 	= array();
		$courseIds 	= array();

		if($tabStatus == 'live') 
			$statusClause = " AND unimp.status = 'live' ";
		else if($tabStatus == 'deleted')
			$statusClause = " AND unimp.status in ('live','deleted') ";

		if($groupBy == 'department') {
			
			if($includeVirtualDept == true) {
				$moreInSelectClause = " ,i.institute_type as institute_type ";
			} else {
				$clauseToNotIncludeVirtualDept = " AND i.institute_type != 'Department_Virtual' ";
			}

			$sql =  "SELECT cd.course_id,cd.course_level_1 as course_level, cd.institute_id, cd.courseTitle ".
					 $moreInSelectClause.
					"FROM course_details cd ".
					"INNER JOIN institute i ON (i.institute_id = cd.institute_id ".$clauseToNotIncludeVirtualDept." ) ".
					"LEFT JOIN institute_university_mapping unimp ON (unimp.institute_id = cd.institute_id AND unimp.status = 'live') ".
					"WHERE cd.status = 'live' ".
					"AND unimp.university_id = ?";
			$results = $this->_db->query($sql,array($universityId))->result_array();
			foreach($results as $result) {
				if(!in_array($result['course_id'], $courseIds)) {
					$courseIds[] = $result['course_id'];
				}
				if($includeVirtualDept == true) {
					$courses[$result['institute_id']][$result['course_level']][$result['course_id']] = array('course_id'=>(int)$result['course_id'],'courseTitle'=>(string)$result['courseTitle'],'instituteType'=>(string)$result['institute_type']);					
				} else {
					$courses[$result['institute_id']][$result['course_level']][$result['course_id']] = array('course_id'=>(int)$result['course_id'],'courseTitle'=>(string)$result['courseTitle']);					
				}
				
			}		
		} else if($groupBy == 'stream') {
			$sql =  "SELECT cd.course_id,cd.course_level_1 as course_level,cd.courseTitle,cbtp.name as category_name ".
					"FROM course_details cd ".
					"LEFT JOIN institute_university_mapping unimp ON (unimp.institute_id = cd.institute_id AND unimp.status = 'live') ".
					"LEFT JOIN listing_category_table lcat ON (lcat.listing_type_id = cd.course_id AND lcat.listing_type = 'course' AND lcat.status = 'live') ".
					"LEFT JOIN categoryBoardTable cbt ON (cbt.boardId = lcat.category_id)".
					"LEFT JOIN categoryBoardTable cbtp ON (cbtp.boardId = cbt.parentId)".
					"WHERE cd.status = 'live' ".
					"AND unimp.university_id = ?";
			
			$results = $this->_db->query($sql,array($universityId))->result_array();
			foreach($results as $result) {
				if(!in_array($result['course_id'], $courseIds)) {
					$courseIds[] = $result['course_id'];
				}
				$courses[$result['category_name']][$result['course_level']][$result['course_id']] = array('course_id'=>$result['course_id'],'courseTitle'=> $result['courseTitle']);
			}		
		}
		else if($groupBy == 'list') {
		    $sql =  "SELECT cd.course_id, cd.institute_id, cd.courseTitle, cd.status ".
					"FROM course_details cd ".
					"LEFT JOIN institute_university_mapping unimp ON (unimp.institute_id = cd.institute_id " .$statusClause. " ) ".
					"WHERE cd.status = ? ".
					"AND unimp.university_id = ?";
			$results = $this->_db->query($sql,array($tabStatus,$universityId))->result_array();
			foreach($results as $result) {
				if(!in_array($result['course_id'], $courseIds)) {
					$courseIds[] = $result['course_id'];
				}
				$courses[] = array('courseID'=>(int)$result['course_id'],'courseName'=>(string)$result['courseTitle'],'status'=>(string)$result['status']);
			}
		}
		$data = array('courses' => $courses, 'course_ids' => $courseIds);
		return $data;
	}
	
	public function getCoursesOfferedByMultipleUniversities($universityList, $courseType = 'ALL')
	{
		$courses 	= array();
		$courseIds 	= array();
		$universityIds  = array();
		switch($courseType) {
                    case 'PAID':
                        $courseTypeClause = ' AND lm.pack_type IN ('.GOLD_SL_LISTINGS_BASE_PRODUCT_ID.', '.GOLD_ML_LISTINGS_BASE_PRODUCT_ID.', '.SILVER_LISTINGS_BASE_PRODUCT_ID.') ';
                        break;
                    case 'FREE':
                        $courseTypeClause = ' AND lm.pack_type NOT IN ('.GOLD_SL_LISTINGS_BASE_PRODUCT_ID.', '.GOLD_ML_LISTINGS_BASE_PRODUCT_ID.', '.SILVER_LISTINGS_BASE_PRODUCT_ID.') ';
                        break;
                    default :
                        $courseTypeClause = '';
                        break;
		}

		if(empty($universityList)){
			return array();
		}
		if(!(is_array($universityList))){
			$universityList = explode(',', $universityList);
		}
		
		$sql =  "SELECT unimp.university_id, cd.course_id, cd.institute_id, cd.courseTitle, cd.status ".
			"FROM course_details cd LEFT JOIN institute_university_mapping unimp ".
			"ON (unimp.institute_id = cd.institute_id AND unimp.status = 'live') ".
			"LEFT JOIN listings_main lm ON lm.listing_type_id = cd.course_id ".
			"WHERE cd.status = 'live' AND lm.listing_type = 'course' AND lm.status = 'live' ".
			"AND unimp.university_id in (?) ".$courseTypeClause;
		$results = $this->_db->query($sql,array($universityList))->result_array();
		foreach($results as $result) {
			if(!in_array($result['course_id'], $courseIds)) {
				$courseIds[] = $result['course_id'];
			}
			if(!in_array($result['university_id'], $universityIds)) {
				$universityIds[] = $result['university_id'];
			}
			$courses[] = array('universityID'=>(int)$result['university_id'],'courseID'=>(int)$result['course_id'],'courseName'=>(string)$result['courseTitle'],'status'=>(string)$result['status']);
			
		}
		$data = array('courses' => $courses, 'course_ids' => $courseIds, 'university_ids' => $universityIds);
		return $data;
	}
	
	
	public function getSnapShotCoursesOfferedByUniversity($universityId) {
		Contract::mustBeNumericValueGreaterThanZero($universityId,'University ID');
		$courses = array();
		$courseIds = array();
		$sql =  "SELECT ssc.course_id, ssc.course_type as course_level, ssc.course_name as courseTitle, cbtp.name as category_name ".
				"FROM snapshot_courses ssc ".
				"LEFT JOIN categoryBoardTable cbt ON (cbt.boardId = ssc.category_id)".
				"LEFT JOIN categoryBoardTable cbtp ON (cbtp.boardId = cbt.parentId)".
				"WHERE ssc.status = 'live' ".
				"AND ssc.university_id = ?";
		
		$results = $this->_db->query($sql,array($universityId))->result_array();
		$courses = array();
		foreach($results as $result) {
			if(!in_array($result['course_id'], $courseIds)) {
				$courseIds[] = $result['course_id'];
			}
			$courses[$result['category_name']][$result['course_level']][$result['course_id']] = array('course_id'=>$result['course_id'],'courseTitle'=>$result['courseTitle']);
		}	
		$data = array('courses' => $courses, 'course_ids' => $courseIds);
		return $data;	
	}
	
	public function getCoursesOfferedByInstitute($instituteId)
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		
		$sql =  "SELECT course_id, course_level_1, courseTitle ".
				"FROM course_details ".
				"WHERE institute_id = ? AND status = 'live' ";
			
		$results = $this->_db->query($sql,array($instituteId))->result_array();
		
		$courses = array();
		foreach($results as $result) {
			$courses[$result['course_level_1']][] = array('course_id'=>$result['course_id'],'course_name'=> $result['courseTitle']);
		}		
		
		return $courses;
	}
	/*
	 * to get those courses whose data was modified in past 2  hours
	 * params : time interval in hours (default 2)
	 */
	public function getModifiedAbroadCoursesByHours($interval = 2)
	{
	    $sql = "select distinct
			acpd.course_id 
		    from
			abroadCategoryPageData acpd
			    inner join
			listings_main lm ON (lm.listing_type = 'course'
			    and lm.listing_type_id = acpd.course_id
			    and lm.status = 'live')
			    inner join
			course_details cd ON (cd.course_id = lm.listing_type_id
			    and cd.status = 'live')
		    where
			acpd.status = 'live'
			    and lm.last_modify_date >= now() - INTERVAL ? HOUR ";
			    
	    $results = $this->getColumnArray($this->_db->query($sql,array($interval))->result_array(),'course_id');
	    return $results;
	}
}
