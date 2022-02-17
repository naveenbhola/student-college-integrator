<?php

class alumni_reviews_model extends MY_Model {
	private $dbHandle = '';
	
	function __construct(){
		parent::__construct('Listing');
    }
	
	private function initiateModel($mode = "read"){
		$this->dbHandle = NULL;
		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	public function getAlumnusDetaisByListingType($listingId = NULL, $listingType = NULL, $status = 'published') {
		if(empty($listingType) || empty($listingId) || !is_numeric($listingId)){
			return false;
		}
		$this->initiateModel('read');
		$typeClause = "";
		if($listingType == 'institute'){
			$typeClause = " td.institute_id = ? ";
		} else {
			$typeClause = " td.course_id = ? ";
		}
		$queryCmd = "SELECT td.email, td.name, td.institute_id, td.course_id, td.course_name, td.course_comp_year, tfr.criteria_id, tfr.criteria_rating, tfr.criteria_desc, tfr.publishedTime, tfc.criteria_name FROM talumnus_details td, talumnus_feedback_rating tfr,  talumnus_feedback_criteria tfc WHERE $typeClause AND td.email = tfr.email AND tfr.status = ?  AND  tfr.criteria_rating != 0 AND tfr.criteria_id = tfc.criteria_id AND tfr.criteria_desc != '' order by tfr.publishedTime desc";
		$query = $this->dbHandle->query($queryCmd, array($listingId, $status));
		
		$alumnus = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$temp = array();
				$temp['email'] 				= $row->email;
				$temp['name']  				= $row->name;
				$temp['institute_id']  		= $row->institute_id;
				$temp['course_id']  		= $row->course_id;
				$temp['course_name']  		= $row->course_name;
				$temp['completion_year']  	= $row->course_comp_year;
				$temp['criteria_id']  		= $row->criteria_id;
				$temp['criteria_name']  	= $row->criteria_name;
				$temp['criteria_rating']  	= $row->criteria_rating;
				$temp['criteria_desc']  	= $row->criteria_desc;
				$temp['time']  				= $row->publishedTime;
				$alumnus[] = $temp;
			}
		}
		return $alumnus;
	}
	
	public function getAlumnusRatingsForInstitutes($instituteIds = array()) {
		if(empty($instituteIds)) {
			return array();
		}
		$this->initiateModel('read');
		
		$sql = "SELECT td.institute_id, td.email, tfc.criteria_name, tfr.criteria_rating
			FROM talumnus_details td, talumnus_feedback_rating tfr, talumnus_feedback_criteria tfc
			WHERE td.institute_id IN (?)
			AND td.email = tfr.email
			AND tfr.status = 'published'
			AND tfr.criteria_rating != 0
			AND tfr.criteria_id = tfc.criteria_id
			AND tfr.criteria_desc != ''";
		
		$query = $this->dbHandle->query($sql,array($instituteIds));
		
		if($query->num_rows() > 0) {
			return $query->result_array();
		}
		else {
			return array();
		}
	}
}