<?php
class abroadlistingseomigrationmodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;

		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }
	
	
	public function updateCourseSeoUrl(){
		
		$this->initiateModel("write");
		//$this->dbHandle->trans_start();
		error_log("SEO Migration: Course Selection Start ".date("h:i:sa"));
		$courses = array();
		$query = "SELECT distinct lm.`listing_type_id` , lm.listing_title AS courseName, acpd.course_id, u.name AS universityName, c.name AS countryName
		FROM `listings_main` lm
		JOIN abroadCategoryPageData acpd ON lm.listing_type_id = acpd.course_id
		AND lm.listing_type = 'course'
		JOIN university u ON acpd.university_id = u.university_id
		JOIN countryTable c ON c.countryId = acpd.country_id
		AND c.showOnRegistration = 'yes'
		WHERE lm.status = 'live'
		AND acpd.status = 'live'
		AND u.status = 'live' order by lm.`listing_type_id` limit 0,45000";
		$query = $this->dbHandle->query($query);
		//echo  $this->dbHandle->last_query()."<br/>";	
		$result = $query->result_array();
		error_log("SEO Migration: Course Selection Complete ".date("h:i:sa"));
		//echo "rows=".count(array_keys($result));
		foreach($result as $key=>$row)
		{
			$url = seo_url_lowercase($row['countryName']).'/universities/'.seo_url_lowercase($row['universityName'])."/".seo_url_lowercase($row['courseName']);
			$url = SHIKSHA_STUDYABROAD_HOME."/".$url;
			$row['newUrl'] = $url;
			$courses[$row['course_id']] = $row;
			
		}
		$updateData = array();
		foreach($courses as $course)
		{
			$temp = array();
			$temp['listing_type_id'] = $course['course_id'];
			$temp['listing_seo_url'] = $course['newUrl'];
			$updateData[] = $temp;
		}
		$this->dbHandle->where('listing_type','course');
		$this->dbHandle->where_in("status",array('live','draft'));
		$this->dbHandle->update_batch('listings_main',$updateData,'listing_type_id');
		//echo $this->dbHandle->last_query();
		$this->dbHandle->affected_rows();
		error_log("SEO Migration: Course URL Update Complete".date("h:i:sa")." | ROWS Affected ".$this->dbHandle->affected_rows());
		/*$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
			
		}*/	
		
	}
	
	public function getUniversityDetailsForMigration(){
		$this->initiateModel('read');
		$this->dbHandle->select("u.university_id as universityId");
		$this->dbHandle->select("ct.name as countryName");
		$this->dbHandle->select("u.name as universityName");
		$this->dbHandle->from("university u");
		$this->dbHandle->join("university_location_table ult","ult.university_id = u.university_id and ult.status = u.status and u.status in ('live','draft')","inner");
		$this->dbHandle->join("countryTable ct","ct.countryId = ult.country_id","inner");
		$this->dbHandle->group_by("u.university_id");
		$res = $this->dbHandle->get()->result_array();
		return $res;
	}

	public function updateNewUniversitySeoUrl($univData){
		$this->initiateModel('write');
		$this->dbHandle->where('listing_type','university');
		$this->dbHandle->where_in("status",array('live','draft'));
		$this->dbHandle->update_batch('listings_main',$univData,'listing_type_id');
		return true;
	}
	
}
