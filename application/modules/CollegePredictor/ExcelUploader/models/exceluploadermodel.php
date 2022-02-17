<?php
/*
   Copyright 2014 Info Edge India Ltd

   $Author: Pranjul

   $Id: exceluploadermodel.php

 */
/**
 * constructor method.
 *
 * @param array
 * @return array
 */
class ExcelUploaderModel extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('CampusAmbassador');
	}
/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */

    	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
	        	$this->dbHandle = $this->getWriteHandle();
		}		
	}
	
	public function saveDataIntoTable($dataArray,$exams){
		$this->initiateModel('write');
		//error_log(print_r($dataArray,true),3,'/home/pranjul/Desktop/pranjul.txt');
		foreach($dataArray as $key=>$value){
			$sql = "INSERT INTO  CollegePredictor_LocationTable (cityName, stateName) VALUES (?, ?) ON DUPLICATE KEY UPDATE `id` = LAST_INSERT_ID(`id`), status=?";
			$this->dbHandle->query($sql,array($value['city'],$value['state'],'live'));
			$last_insert_id = $this->dbHandle->insert_id();
			foreach($value['collegeName'] as $k=>$v){
					$sql = "insert into CollegePredictor_Colleges (`collegeName`,`locId`,`collegeGroupName`,`exams`) values (?,?,?,?) ON DUPLICATE KEY UPDATE `id` = LAST_INSERT_ID(`id`), collegeGroupName=?";
					$this->dbHandle->query($sql,array($v,$last_insert_id,$value['collegeGroupName'][$k],$exams,$value['collegeGroupName'][$k]));
					$last_insert_id1 = $this->dbHandle->insert_id();
					
					foreach($value['branchName'][$k] as $k1=>$v1){
						$sql = "insert into CollegePredictor_BranchInformation (`clmId`,`branchName`,`branchAcronym`,`shikshaCourseId`,`courseCode`,`instCourseLink`,`instLink`,`remarks`) values (?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE `branchId` = LAST_INSERT_ID(`branchId`), branchAcronym=?, shikshaCourseId=?, courseCode=?, instCourseLink=?, instLink=?, remarks=?, status = 'live'";
						$this->dbHandle->query($sql,array($last_insert_id1,$v1,$value['branchAcronym'][$k][$k1],$value['shikshaCourseId'][$k][$k1],$value['courseCode'][$k][$k1],$value['instCourseLink'][$k][$k1],$value['instLink'][$k][$k1],$value['remarks'][$k][$k1],$value['branchAcronym'][$k][$k1],$value['shikshaCourseId'][$k][$k1],$value['courseCode'][$k][$k1],$value['instCourseLink'][$k][$k1],$value['instLink'][$k][$k1],$value['remarks'][$k][$k1]));
						$last_insert_id2 = $this->dbHandle->insert_id();
						$temp = array();
						foreach($value['roundInfo'][$k][$k1] as $k2=>$v2){
							foreach($v2 as $k3=>$v3){
								$categoryRoundRankData = array();
								$categoryRoundRankData['branchId'] = $last_insert_id2;
								$categoryRoundRankData['categoryName'] = $v3['category'];
								$categoryRoundRankData['closingRank'] = $v3['closingRank'];
								$categoryRoundRankData['roundNum'] = $v3['round'];
								$categoryRoundRankData['rankType'] = $v3['rankType'];
								$temp[] = $categoryRoundRankData;
							// $sql = "insert into CollegePredictor_CategoryRoundRankMapping  (`branchId`,`categoryName`,`closingRank`,`roundNum`,`rankType`) values (?,?,?,?,?) ON DUPLICATE KEY UPDATE `closingRank`=?, `roundNum` = ?, status = 'live'";
							// $this->dbHandle->query($sql,array($last_insert_id2,$v3['category'],$v3['closingRank'],$v3['round'],$v3['rankType'],$v3['closingRank'], $v3['round']));
							}
						}
						$this->dbHandle->insert_batch('CollegePredictor_CategoryRoundRankMapping',$temp);
					}
				
			}
		}
	}

	function getPredictorData($examName, $cityName, $stateName){
		
		if(empty($examName)){
			return;
		}
		$db = $this->initiateModel('read');
		if(!empty($cityName)){
			$cityName = " and cl.cityName = ".$this->dbHandle->escape($cityName);
		}
		
		if(!empty($stateName)){
			$stateName = " and cl.stateName = ".$this->dbHandle->escape($stateName);
		}
		
		$where = $stateName.$cityName;	
		$sql = "SELECT cc.collegeName, cc.collegeGroupName,
				cb.branchId, cb.branchName, cb.branchAcronym, cb.shikshaCourseId, cb.courseCode, cb.instCourseLink, cb.instLink, cb.remarks
				from CollegePredictor_LocationTable cl 
				INNER JOIN CollegePredictor_Colleges cc ON cc.locId = cl.id
				INNER JOIN CollegePredictor_BranchInformation cb ON cc.id = cb.clmId
				WHERE cl.status = 'live' and cc.status='live' and cb.status='live' and cc.exams = ? $where";
		return $this->dbHandle->query($sql,array($examName))->result_array();
	}

	function getRoundInfo($branchIdStr){
		if(empty($branchIdStr)){
			return;
		}
		$db = $this->initiateModel('read');
		$sql = "SELECT cr.branchId, cr.rankType,cr.categoryName,cr.roundNum as round,cr.closingRank
				from CollegePredictor_CategoryRoundRankMapping cr 
				WHERE cr.status = 'live' AND cr.branchId IN (?)";
		$branchIdArr = explode(',',$branchIdStr);
		return $this->dbHandle->query($sql, array($branchIdArr))->result_array();
	}

	function isExamLive($examName){
		if(empty($examName)){
			return;
		}
		$db = $this->initiateModel('read');
		$sql = "SELECT exams
				From CollegePredictor_Colleges 
				WHERE exams = ? AND status = 'live' limit 1";
		$res = $this->dbHandle->query($sql,array($examName))->result_array();
		return empty($res[0]['exams']) ? false : true;
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
		if(empty($collegeIds) && count($collegeIds) < 1){
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
		if(empty($branchIds) && count($branchIds) < 1){
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
		if(empty($collegeIds) && count($collegeIds) < 1){
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

	function getLiveCourse($shikshaCourseIds){
		if(count($shikshaCourseIds)<=0 || empty($shikshaCourseIds)){
			return;
		}
		//$shikshaCourseIds = implode(',', $shikshaCourseIds);	
		$this->initiateModel('read');
		$sql = "SELECT listing_type_id as course FROM listings_main WHERE listing_type_Id IN (?) AND listing_type = 'course' AND status = 'live' ORDER BY listing_type_id ASC";
		$res = $this->dbHandle->query($sql,array($shikshaCourseIds))->result_array();
		foreach ($res as $key => $value) {
			$course[] = $value['course'];
		}
		return array_unique($course);
	}

	function saveDataIntoSpellCheckTable($result){
		if(empty($result)){
			return;
		}
		$this->initiateModel('write');
		$this->db->insert_batch('spellCheckWords', $result);	
	}
}
