<?php

class CourseDetailsModel extends MY_Model
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
	
	private function _dateFormater($date)
	{
		$datesp = explode(" ",$date);
		$newdate = $datesp[0]."T".$datesp[1]."Z";
		return $newdate;
	}
   	
	public function checkIfCourseBelongsToNationalPosting($courseId) {
		if(empty($courseId)) {
	
			return false;
		}
	
		$this->initiateModel('read');
	
	   
		$sql = "SELECT  distinct i.institute_id as institute_id FROM institute i INNER JOIN course_details cd on (cd.course_id = ? and i.institute_id = cd.institute_id and i.institute_type IN ('Department','Department_Virtual'))";
		$result = $this->dbHandle->query($sql,array($courseId))->result_array();

		if($result[0]['institute_id'])
		{
			return false;
				
		} else {
				
			return true;
		}
	}
}
