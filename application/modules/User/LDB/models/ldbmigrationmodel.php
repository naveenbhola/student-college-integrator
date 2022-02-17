<?php

class LDBMigrationModel extends MY_Model
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
            
    public function migrateRecommendationInfo($old_institute_id, $new_institute_id){
	    $this->initiateModel('write');

	    $sql = "update recommendations_info set instituteID=? where instituteID=?";

	    $this->dbHandle->query($sql, array($new_institute_id, $old_institute_id));

	}

	public function migratePortingConditions($old_institute_id, $new_institute_id){
	    $this->initiateModel('write');

	    $sql = "update porting_conditions set value=? where `key`='institute' and value=?";

	    $this->dbHandle->query($sql, array($new_institute_id, $old_institute_id));

	}

	public function migrateInstCRMapToShikshaInst($old_institute_id, $new_institute_id){
	    $this->initiateModel('write');

	    $sql = "update CollegeReview_MappingToShikshaInstitute set instituteId=? where  instituteId=?";

	    $this->dbHandle->query($sql, array($new_institute_id, $old_institute_id));

	}

	public function migrateLatestResponseData($old_institute_id, $new_institute_id){
	    $this->initiateModel('write');

	    $sql = "update latestUserResponseData set instituteId=? where  instituteId=?";

	    $this->dbHandle->query($sql, array($new_institute_id, $old_institute_id));

	}

	public function migrateLocationCRMapToShikshaInst($old_location_id, $new_location_id){
	    $this->initiateModel('write');

	    $sql = "update CollegeReview_MappingToShikshaInstitute set locationId=? where  locationId=?";

	    $this->dbHandle->query($sql, array($new_location_id, $old_location_id));

	}

	public function migrateResponseLocationTable($old_location_id, $new_location_id){
	    $this->initiateModel('write');

	    $sql = "update responseLocationTable set instituteLocationId=? where  instituteLocationId=?";

	    $this->dbHandle->query($sql, array($new_location_id, $old_location_id));

	}

	

	public function getActivityUserSearchCriteria(){
		$this->initiateModel('write');
		 $sql = "select id, criteriaJSON from mailer.userSearchCriteria where criteriaType='Activity' and status='live'";

	    $result = $this->dbHandle->query($sql)->result_array();

	    return $result;
	}

	public function updateUserSearchCriteria($criteria_data,$id){
		$this->initiateModel('write');
		$sql_update = 'update mailer.userSearchCriteria set criteriaJSON=? where id=?';
	   	$this->dbHandle->query($sql_update,array($criteria_data,$id));
	}
}

?>
