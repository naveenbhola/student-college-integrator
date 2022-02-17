<?php
class collegeshortlist extends MY_Model
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

	function getCategories($categoryNames,$examId){
		$res = array();
		if (empty($categoryNames) && !is_array($categoryNames)) {
			return $res;
		}
		$this->initiateModel('read');
		$sql = "SELECT id,short_name FROM shortlist_category_table where short_name IN (?) and exam_id IN (?)";
		$query = $this->dbHandle->query($sql, array($categoryNames,array($examId,-1)));
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $value){
				$res[$value['short_name']] = $value['id'];	
			}
		}
		return $res;
	}

	function updateStatusForExisting($examId){
		if (!is_numeric($examId) && $examId <= 0 ) {
			error_log("Invalid examId");
			return;
		}
		$this->initiateModel();
		$sql = "UPDATE shortlist_courses_cutoff_information set status = 'history' WHERE exam_id = ? and status = 'live'";
		$query = $this->dbHandle->query($sql,$examId);
		return ;
	}

	function insertExamCutOffInformation($data){
		$this->initiateModel();
		$this->dbHandle->insert_batch('shortlist_courses_cutoff_information',$data);
	}

	function uploadData($dbAllUpdateData,$sheetName){
		$this->updateStatusForExisting($sheetName);

		$chunkSize = 500;      
        $batchSize = ceil(count($dbAllUpdateData)/$chunkSize);
        $dataChunk = array();
        for($i = 0; $i < $batchSize; $i++) {
	        $dataChunk[] = array_slice($dbAllUpdateData, $i*$chunkSize,$chunkSize);
			$this->insertExamCutOffInformation($dataChunk[0]);
			error_log(($i+1)." Batch of 500 batch inserted </br> ".$sheetName."</br>");
			unset($dataChunk);
        }
	}

	function getPredictorTrackingData($startDate,$endDate,$batchNo,$batchSize){
		$this->initiateModel('read');
		$sql = "select std.user_id AS userId,std.exam_name AS examName,std.result AS result,std.result_type AS resultType, created_on AS SubmitDate from shortlist_tracking_data std where std.created_on >= ? AND created_on <= ? group by std.user_id,std.exam_name,std.result_type limit ?,?";
		$query = $this->dbHandle->query($sql,array($endDate,$startDate,$batchNo*$batchSize,$batchSize));
		return $query->result_array();
	}

	function getTrackingDataCount($startDate,$endDate){
		$this->initiateModel('read');
		$sql = "select count(distinct user_id,exam_name) as count from shortlist_tracking_data where created_on >= ? AND created_on <= ? ";
		$query = $this->dbHandle->query($sql,array($endDate,$startDate));
		return $query->result_array();
	}
}
