<?php

class InstituteDetailsModel extends MY_Model
{
	private $dbHandle = null;
	private $cache;

    function __construct($cache)
	{
		parent::__construct('Listing');
		$this->cache = $cache;
    }

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	function getInstituteFacilityFields(){
		$this->initiateModel('write');

		$sql = "select attributeId as id,attributeTitle as title from institute_facilities_attributes where listing_type='institute' and status='live' order by orderId";


		$query = $this->dbHandle->query($sql);
		$facilityFields = array();
		foreach($query->result_array() as $row){
			$facilityFields[$row['id']] = $row['title'];
		}
		return $facilityFields;
	}
	
	private function _dateFormater($date)
	{
		$datesp = explode(" ",$date);
		$newdate = $datesp[0]."T".$datesp[1]."Z";
		return $newdate;
	}
	
	public function checkIfInstituteBelongsToNationalPosting($instituteId) {
		if(empty($instituteId)) {
				
		  return false;	
		}
		
		$this->initiateModel('read');
		
		
		$sql = "SELECT  distinct `institute_id` as institute_id FROM `institute` WHERE  `institute_id` = ?  and `institute_type` IN ('Department','Department_Virtual')";
		$result = $this->dbHandle->query($sql,array($instituteId))->result_array();
		if($result[0]['institute_id']) 
		{  
			return false;
			
		} else {
			
			return true;
		}
	}
}
