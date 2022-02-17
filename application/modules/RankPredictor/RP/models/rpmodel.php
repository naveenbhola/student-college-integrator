<?php
class RPModel extends MY_Model
{ 	/*

   Copyright 2015 Info Edge India Ltd

   $Author: Ankur

   $Id: RPModel.php

 */
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		parent::__construct('AnA');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}
    

        function insertActivityLog($data) {
                $this->initiateModel('write');
		//Sanitize input
		if(!isset($data['userId'])){
			$data['userId'] = 0;
		}
	        $queryCmd = $this->dbHandle->insert_string('RankPredictor_ActivityLog',$data);
        	$query = $this->dbHandle->query($queryCmd);
                return '1';
        }
	
	function getRankDataForExam($data){
		$this->initiateModel();
		if(isset($data['examName']) && isset($data['calculatedScore']) ){
			$sql = "SELECT minRank,maxRank FROM RankPredictor_ExamData WHERE examName = ? AND minScore <= ? AND maxScore >= ? AND status = 'live'";
			$query = $this->dbHandle->query($sql,array($data['examName'],$data['calculatedScore'],$data['calculatedScore']));
			return $query->result_array();		
		}
		else{
			return "Data is not correct";
		}
	}
	
	function saveFeedbackData($data){
		$this->initiateModel('write');
		//Sanitize input
		if(!isset($data['userId'])){
			$data['userId'] = 0;
		}
		if(!isset($data['feedbackId'])){
			$queryCmd = $this->dbHandle->insert('CollegePredictor_Feedback',$data);
			$feedbackId = $this->dbHandle->insert_id();
		}
	        else{
			$this->dbHandle->where('feedback_id',$data['feedbackId']);
			unset($data['feedbackId']);
			$this->dbHandle->update('CollegePredictor_Feedback', $data);
		}
		if($feedbackId!='')
                  return $feedbackId;
		else
		  return 1;
	}

	function saveRankPredictorData($data,$examName){
		$this->initiateModel('write');
		if(!empty($data)){
			$this->dbHandle->trans_start();
			$this->dbHandle->where('examName',$examName)->where('status','live');
			$this->dbHandle->update('RankPredictorExamScores',array('status'=>'history'));
			
			$this->dbHandle->insert_batch('RankPredictorExamScores',$data);
		    $this->dbHandle->trans_complete();
			if ($this->dbHandle->trans_status() === FALSE) {
				throw new Exception('Transaction Failed');
			}
		}
	}

	function getRankByScore($examName,$score){
		$this->initiateModel();
		return $this->dbHandle->where('examName',$examName)->where('score',$score)->get('RankPredictorExamScores')->row_array();
	}
	function insertExcelData($data){
		$this->initiateModel("write");
		$result = $this->db->insert_batch('percentile_predictor_score_mapping',$data);
		return ;
	}

	function getRankByUserScore($score,$examName){

		if(empty($examName)){
			return;
		}

		$this->initiateModel();

		$query = "SELECT minScore,maxScore,minRank,slope FROM percentile_predictor_score_mapping where examName = ? AND status = 'live' and minScore <= ? AND maxScore >= ? limit 1";
		$rs = $this->dbHandle->query($query,array($examName,$score,$score))->result_array();
		return $rs[0];
	}

	function getMaxRankFromTable($examName){
		$this->initiateModel();
		$query = "SELECT max(maxRank) as maxRank FROM percentile_predictor_score_mapping where examName = ? AND status = 'live'";
		$rs = $this->dbHandle->query($query,array($examName))->result_array();
		return $rs[0]['maxRank'];
	}

	function getPredictorTrackingData($startDate,$endDate,$batchNo,$batchSize){
		$sql = "select rpal.userId AS userId,rpal.examName AS examName,( case when rpal.percentile1>=rpal.percentile2 then rpal.percentile1 else rpal.percentile2 end) AS percentile,rpal.examScore AS score,rpal.creationDate AS SubmitDate from RankPredictor_ActivityLog rpal where rpal.creationDate >= ? AND creationDate <= ? group by rpal.userId,rpal.examName limit ?,?";
		$this->initiateModel();
		$query = $this->dbHandle->query($sql,array($endDate,$startDate,$batchNo*$batchSize,$batchSize));
		return $query->result_array();
	}

	function getTrackingDataCount($startDate,$endDate){
		$this->initiateModel();
		$sql = "select count(distinct userId,examName) as count from RankPredictor_ActivityLog where creationDate >= ? AND creationDate <= ?";
		$query = $this->dbHandle->query($sql,array($endDate,$startDate));
		return $query->result_array();
	}


}
