<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class CourseFinderModel extends ListingModelAbstract
{
    function __construct()
    {
        parent::__construct();
    }

	public function getCoursesByInstitute($instituteId)
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		$courses = $this->getCoursesByMultipleInstitutes(array($instituteId));
		return $courses[$instituteId];
	}
	
	public function getCoursesByMultipleInstitutes($instituteIds)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');
		
		$sql =  "SELECT course_id,institute_id ".
				"FROM course_details ".
                "WHERE status = 'live' ".
				"AND institute_id IN (?)";
		
		$results = $this->db->query($sql, array($instituteIds))->result_array();
		$courses = array();
		foreach($results as $result) {
			$courses[$result['institute_id']][] = $result['course_id'];
		}
		return $courses;
	}
	
	/*
	 * Select all the courses which were modified in given criteria
	 */ 
	public function getModifiedCourses($criteria)
	{
		$interval = $criteria['interval'];
		
		$sql =  "SELECT distinct listing_type_id ".
				"FROM listings_main lm ".
				"inner join ".
				"categoryPageData cpd ON(lm.listing_type_id = cpd.course_id)".
				"WHERE lm.last_modify_date >= ? ".
				"AND lm.last_modify_date < ? ".
				"AND lm.listing_type = 'course' ".
				"AND (lm.status = 'live' OR lm.status = 'deleted')";


		$results = $this->db->query($sql,array($interval['start'],$interval['end']))->result_array();
		return $this->getColumnArray($results,'listing_type_id');
	}
	
	/*
	 * All courses which are currently live
	 */ 
	public function getLiveCourses()
	{
		$sql = 	"SELECT DISTINCT course_id ".
				"FROM categoryPageData ".
				"WHERE status = 'live' AND country_id = 2 ".
				"ORDER BY course_id";
		
		return $this->getColumnArray($this->db->query($sql)->result_array(),'course_id');		
	}

	public function getCoursesHavingZeroExpiryDate() {
		$sql =  "SELECT listing_type_id,subscriptionId 
                         FROM  listings_main
                         WHERE listing_type = 'course' AND
                         date(expiry_date) = '0000-00-00' AND
                         status = 'live'  AND pack_type IN (".GOLD_SL_LISTINGS_BASE_PRODUCT_ID.", ".GOLD_ML_LISTINGS_BASE_PRODUCT_ID.", ".SILVER_LISTINGS_BASE_PRODUCT_ID.")";
		$results = $this->db->query($sql)->result_array();
        return $results;

	}

	public function getCourseInfoToExpire($courseId,$dateToCheckFrom) {

		$sql =  "SELECT `listing_type_id`,`subscriptionId` 
                         FROM  `listings_main`
                         WHERE `listing_type` = 'course' AND
                         date(expiry_date) <= ? AND
                         listing_type_id = ? AND
                         status = 'live' AND pack_type IN ( '".GOLD_SL_LISTINGS_BASE_PRODUCT_ID."', '".GOLD_ML_LISTINGS_BASE_PRODUCT_ID."', '".SILVER_LISTINGS_BASE_PRODUCT_ID."')";
			 
		$results = $this->db->query($sql,array($dateToCheckFrom,$courseId))->result_array();
    	return $results;
	}
}
