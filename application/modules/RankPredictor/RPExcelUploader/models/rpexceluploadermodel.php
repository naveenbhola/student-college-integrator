<?php
/*
   Copyright 2015 Info Edge India Ltd

   $Author: Ankur

   $Id: rpexceluploadermodel.php

 */
/**
 * constructor method.
 *
 * @param array
 * @return array
 */
class RPExcelUploaderModel extends MY_Model {
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
	
	public function saveDataIntoTable($dataArray,$examName){
		$this->initiateModel('write');

		$sql = "UPDATE RankPredictor_ExamData SET status = 'history' WHERE examName = ?";
		$this->dbHandle->query($sql,array($examName));

		foreach($dataArray as $key=>$value){
			if($value['MinRank'] != '' && $value['MaxRank'] != ''){
				$sql = "INSERT INTO RankPredictor_ExamData (minScore,maxScore,minRank,maxRank,examName) VALUES (?, ?, ?, ?, ?)";
				$this->dbHandle->query($sql,array($value['MinScore'],$value['MaxScore'],$value['MinRank'],$value['MaxRank'],$examName));
				$last_insert_id = $this->dbHandle->insert_id();
			}
		}
	}
}
