<?php

class mptmodel extends MY_Model
{
	private $dbHandle = null;

	private function initiateModel($mode = "read", $module = 'User')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function getNewAddedUserProfileForMigration($start_date, $other_base_courses, $end_date){
		$this->initiateModel();
		
		$sql = "select tui.userId, tui.streamId, tspz.baseCourseId  from tUserInterest tui join tUserCourseSpecialization tspz on tui.interestId=tspz.interestId  where tui.status='live' and tspz.status='live' and tspz.time>? and tspz.baseCourseId not in (?) and tspz.time<?";
		$result = $this->dbHandle->query($sql,array($start_date, $other_base_courses, $end_date))->result_array();
		
		return $result;
	}


	public function getNewAddedUserProfile($start_date, $other_base_courses){
		$this->initiateModel();

		$sql = "select tui.userId, tspz.time, tui.streamId, tspz.baseCourseId  from tUserInterest tui join tUserCourseSpecialization tspz on tui.interestId=tspz.interestId  where tui.status='live' and tspz.status='live' and tspz.time>? and tspz.baseCourseId not in (?)";
		$result = $this->dbHandle->query($sql,array($start_date, $other_base_courses))->result_array();
		
		return $result;
	}

	public function getStateForUsers($user_ids){
		$this->initiateModel();

		$sql = "select cct.state_id, tu.userId from tuser tu join countryCityTable cct on tu.city=cct.city_id where userid  in (?)";
		$result = $this->dbHandle->query($sql,array($user_ids))->result_array();

		return $result;	
	}

	public function addMPTMailerTracking($sql,$inputData){
		$this->initiateModel('write');
		if (!empty($inputData) && !empty($sql))
			$this->dbHandle->query($sql,$inputData);
	}
	
}
