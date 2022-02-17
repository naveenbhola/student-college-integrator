<?php
	class CollegeShortlistModel extends MY_Model{
		function __construct()
		{
			parent::__construct('CollegePredictor');
		}
		
		private function initiateModel($operation='read'){
			if($operation=='read'){ 
				$this->dbHandle = $this->getReadHandle();
			}else{
			    $this->dbHandle = $this->getWriteHandle();
			}		
		}

		function getCollegeShortlistForSolrIndexing($examId){
			$this->initiateModel('read');
			$whereClause = "";
			if(!empty($examId)){
				$whereClause .= " AND exam_id = ?";
			}
			$sql = "SELECT * FROM shortlist_courses_cutoff_information where status = 'live' ".$whereClause;
			$rs = $this->dbHandle->query($sql,array($examId))->result_array();
			return $rs;
		}
		function getExamBaseInformation($examId){
			$this->initiateModel('read');
			$whereClause = "";
			if(!empty($examId)){
				$whereClause .= " where exam_id = ?";
				$params[] = $examId;
			}
			$sql = "SELECT exam_id, exam_name, exam_cutoff_type FROM shortlist_exam_base_table ".$whereClause;
			return $this->dbHandle->query($sql,$params)->result_array();
		}
		function getSingleFieldsFromCollegeShortlist($columnName) {
			if(empty($columnName)){
				return array();
			}
			$this->initiateModel('read');
			$sql = "SELECT distinct ".$columnName." FROM shortlist_courses_cutoff_information";
			$rs = $this->dbHandle->query($sql)->result_array();
			$result = array();
			foreach ($rs as $key => $value) {
				$result[] = $value[$columnName];
			}
			return $result;	
		}
		function getCategoryNameId($examId){
			$this->initiateModel('read');
			$examIds = array(-1);
			if(!empty($examId)){
				array_push($examIds, $examId);
			}
			$sql = "SELECT id,full_name FROM shortlist_category_table WHERE exam_id IN (?)";
			return $this->dbHandle->query($sql,array($examIds))->result_array();
		}
	}
?>