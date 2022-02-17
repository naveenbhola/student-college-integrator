<?php
class CPScriptModel extends MY_Model
{
	function __construct(){ 
		parent::__construct('CollegePredictor');
	}

	private function initiateModel($operation='write'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

	function getAllCollegeIdsForExam($examName){
		$res = array();
		if(empty($examName)){
			return $res;
		}
		$this->initiateModel('read');
		$sql = "SELECT id from CollegePredictor_Colleges where exams = ? and status='live'";
		$query = $this->dbHandle->query($sql, array($examName));
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $value){
					$res[] = $value['id'];	
			}
		}
		return $res;
	}

	function getAllBranchesForColleges($collegeIds){
		$res = array();
		if(empty($collegeIds) && !is_array($collegeIds)){
			return $res;
		}
		$this->initiateModel('read');
		//$collegeIds = implode(',', $collegeIds);
		$sql = "SELECT branchId from CollegePredictor_BranchInformation where clmId in (?) and status='live'";
		$query = $this->dbHandle->query($sql, array($collegeIds));
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $value){
					$res[] = $value['branchId'];	
			}
		}
		return $res;
	}

	function removeAllRoundInfoForCollegePredictor($branchIds){
		if(empty($branchIds) && !is_array($branchIds)){
			return false;
		}
		$this->initiateModel('write');
		//$branchIds = implode(',', $branchIds);
		$sql = "UPDATE CollegePredictor_CategoryRoundRankMapping set status = 'history' where branchId in (?) and status='live'";
		$query = $this->dbHandle->query($sql, array($branchIds));
		return true;
	}

	function removeAllBranchOfCollegePredictor($collegeIds){
		$res = array();
		if(empty($collegeIds) && !is_array($collegeIds)){
			return false;
		}
		$this->initiateModel('write');
		//$collegeIds = implode(',', $collegeIds);
		$sql = "UPDATE CollegePredictor_BranchInformation set status = 'history' where clmId in (?) and status='live'";
		$query = $this->dbHandle->query($sql, array($collegeIds));
		return true;
	}

	function removeAllCollegesForCollegePredictor($examName){
		if(empty($examName)){
			return false;
		}
		$this->initiateModel('write');
		$sql = "UPDATE CollegePredictor_Colleges set status = 'history' where exams = ? and status='live'";
		$query = $this->dbHandle->query($sql, array($examName));
		return true;
	}
}
