<?php

class MatchCourse_Model extends MY_Model
{
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

	public function getCourseMatchingParameters($courseIds)
	{
		$this->initiateModel();
		
		$matchingParams = array();
		
		$courseIdClause = "";
		if(is_array($courseIds) && count($courseIds) > 0) {
			$courseIdClause = "AND course_id IN (".implode(", ", $courseIds).")";
		}
		
		$sql =  "SELECT course_id, primary_id, education_type, delivery_method ".
				"FROM shiksha_courses WHERE status = 'live' ".$courseIdClause;

		$query = $this->_db->query($sql);
		
		foreach($query->result_array() as $row) {
				$matchingParams[$row['course_id']]['institute_id'] = intval($row['primary_id']);
				$matchingParams[$row['course_id']]['education_type'] = intval($row['education_type']);
				$matchingParams[$row['course_id']]['delivery_method'] = intval($row['delivery_method']);
		}
		
		$sql =  "SELECT course_id, credential, course_level, base_course, stream_id, substream_id ".
				"FROM shiksha_courses_type_information WHERE status = 'live' AND type = 'entry' ".$courseIdClause;

		$query = $this->_db->query($sql);
		
		foreach($query->result_array() as $row) {
				$matchingParams[$row['course_id']]['credential'] = intval($row['credential']);
				$matchingParams[$row['course_id']]['course_level'] = intval($row['course_level']);
				$matchingParams[$row['course_id']]['base_course'] = intval($row['base_course']);
				$stream = intval($row['stream_id']);
				$substream = intval($row['substream_id']);
				$hkey = $stream.":".$substream;
				$matchingParams[$row['course_id']]['hierarchies'][$hkey] = array($stream, $substream);
		}
		
		$courseIdClauseForLocation = "";
		if(is_array($courseIds) && count($courseIds) > 0) {
			$courseIdClauseForLocation = "AND a.course_id IN (".implode(", ", $courseIds).")";
		}
		
		$sql = "SELECT DISTINCT a.course_id, b.city_id ".
			   "FROM shiksha_courses_locations a, shiksha_institutes_locations b ".
			   "WHERE a.listing_location_id = b.listing_location_id ".
			   "AND a.status = 'live' ".
			   "AND b.status = 'live' ".$courseIdClauseForLocation;
			   
		$query = $this->_db->query($sql);
	
		foreach($query->result_array() as $row) {
			$matchingParams[$row['course_id']]['cities'][] = intval($row['city_id']);
		}	   
		
		return $matchingParams;
	}
}
