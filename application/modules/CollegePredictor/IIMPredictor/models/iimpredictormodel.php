<?php
/*
   Copyright 2015 Info Edge India Ltd

   $Author: Aditya

   $Id: iimpredictormodel.php

 */
 
/**
 * constructor method.
 *
 * @param array
 * @return array
 */
class iimpredictormodel extends MY_Model {
	
	private $dbHandle = '';
	function __construct(){
		parent::__construct('User');
	}
	
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */

	private function initiateModel($operation='read'){
		
        if($operation=='read'){
            $this->db = $this->getReadHandle();
        }else{
            $this->db = $this->getWriteHandle();
        }
	}
	
	public function saveTrackingData($trackingData = array()){
		
		if(count($trackingData) >0) {
			
			$this->initiateModel('write');
			$sql = $this->db->insert_string('ICP_userTracking',$trackingData);
			$query = $this->db->query($sql);
			
			return $this->db->insert_id();
				
		} else {
			return 0;	
		}
	}
	
	public function updateUserId($id,$userid) {
		$this->initiateModel('write');
		
		if($id>0 && $userid>0)
			$sql = "UPDATE ICP_userTracking SET userId=?,newRegistration=? where id=?";	
			$query = $this->db->query($sql,array($userid,'YES',$id));
	}

	public function getUserTrackingData($id){
		$this->initiateModel('read');

		if($id > 0){
			$sql ="SELECT userId, category, gender, xthBoard, xiithBoard, xthPercentage, xiithPercentage, graduationPercentage, graduationYear,
					xiithStream, graduationStream, experience, VRC_Percentile, DILR_Percentile, QA_Percentile, cat_total, cat_percentile 
					FROM ICP_userTracking WHERE id = ?";

			$returnData = $this->db->query($sql,array($id))->result_array();
			return $returnData[0];
		}
	}

	public function updateUserIdinTracking($userId, $trackingIds){	
		$this->initiateModel('write');
		$sql = "UPDATE ICP_userTracking SET userId = ? WHERE id in (?)";
		$trackingArr = explode(',',$trackingIds);
		$this->db->query($sql,array($userId,$trackingArr));
	}

	public function updateRegistrationFlag($lastId){
		$this->initiateModel('write');
		$sql = "UPDATE ICP_userTracking SET newRegistration = 'YES' WHERE id = ?";
		$this->db->query($sql,array($lastId));
	}

	public function getLatestUserData($userId){
		$this->initiateModel('read');
		
		$sql ="SELECT userId, category, gender, xthBoard, xiithBoard, xthPercentage, xiithPercentage, graduationPercentage, graduationYear,
					xiithStream, graduationStream, experience, VRC_Percentile, DILR_Percentile, QA_Percentile, cat_total, cat_percentile  
					FROM ICP_userTracking WHERE userId = ? order by id desc limit 1";

		$returnData = $this->db->query($sql,array($userId))->result_array();
		return $returnData[0];

	}

	public function trackICPClickData($sessionId,$pageId,$deviceType){
		$this->initiateModel('write');
		
		$sql = "insert into ICP_TrackingTable (UserSessionId,pageId,DeviceType)  VALUES (?,?,?)";

		$this->db->query($sql,array($sessionId,$pageId,$deviceType));
	}

	public function updateCATScore($userId, $catScore){
		$this->initiateModel('write');

		$sql = 'UPDATE tUserEducation set Marks=? Where UserId = ? and name="CAT"';
		$this->db->query($sql,array($catScore,$userId));
	}

	public function insertData($excelData,$year){
		if (empty($excelData)) {
			return;
		}
		$this->initiateModel('write');
		$insertEligibilityData = array();
		$count = 0;
		$eligibilityData = $excelData["eligibility"];
		/*_p($eligibilityData);die;*/
		foreach ($eligibilityData as $key => $eligibility) {
			if(is_numeric($key)){
				foreach ($eligibility as $subkey => $category) {
					if($subkey == 0){
						$class_10 = $category[3];
						$class_12 = $category[4];
						$average_science = $category[5];
						$average_commerce = $category[6];
						$average_arts = $category[7];

					}
					$insertEligibilityData[$count]["institute_id"] = $key;
					$insertEligibilityData[$count]["student_category"] = $category[2];
					$insertEligibilityData[$count]["class_10"] = $category[3];
					$insertEligibilityData[$count]["class_12"] = $category[4];
					$insertEligibilityData[$count]["average_science"] = $category[5];
					$insertEligibilityData[$count]["average_commerce"] = $category[6];
					$insertEligibilityData[$count]["average_arts"] = $category[7];
					$insertEligibilityData[$count]["graduation_percentile"] = $category[8];
					$insertEligibilityData[$count]["vrc"] = $category[9];
					$insertEligibilityData[$count]["dilr"] = $category[10];
					$insertEligibilityData[$count]["qa"] = $category[11];
					$insertEligibilityData[$count]["total"] = $category[12];
					$insertEligibilityData[$count]["year"] = $year;
					$count++;
				}
			}
		}
		if(!empty($insertEligibilityData)){
			/*_p(($eligibilityData));die;*/
			$result = $this->db->insert_batch('mba_predictor_eligibility',$insertEligibilityData);
		}



		$cutOffData = $excelData["cutoff"];
		$insertCutOffData = array();

		$count = 0;
		foreach ($cutOffData as $key => $cutoff) {
			if(is_numeric($key)){
				foreach ($cutoff as $subkey => $category) {
					$insertCutOffData[$count]["institute_id"] = $key;
					$insertCutOffData[$count]["student_category"] = $category[2];
					$insertCutOffData[$count]["composite_score"] = $category[3];
					$insertCutOffData[$count]["vrc"] = $category[4];
					$insertCutOffData[$count]["dilr"] = $category[5];
					$insertCutOffData[$count]["qa"] = $category[6];
					$insertCutOffData[$count]["total"] = $category[7];
					$insertCutOffData[$count]["year"] = $year;
					$count++;
				}
			}
		}
		if (!empty($insertCutOffData)) {
			$result = $this->db->insert_batch('mba_predictor_cut_off',$insertCutOffData);
		}
		
		$articleData = $excelData["article"];
		$clpIdsData = $excelData["clp_ids"];
		/*_p($articleData);die;*/
		/*_p($clpIdsData);die;*/
		$mba_predictor_institutes = array();
		$count =0;
		$unavailableArticles = array();
		foreach ($clpIdsData as $key => $institutes) {
			if(is_numeric($key)){
				$mba_predictor_institutes[$count]["institute_id"] = $key;
				$mba_predictor_institutes[$count]["course_id"] = $institutes[1];
/*				_p($articleData[$key]);*/
				$mba_predictor_institutes[$count]["article_id"] = $articleData[$key][1];
				$mba_predictor_institutes[$count]["article_label"] = $articleData[$key][2];
				if($articleData[$key] != null){
					$unavailableArticles[] = $key;
				}
				$count++;
			}
		}

		if (!empty($mba_predictor_institutes)) {
			$result = $this->db->insert_batch('mba_predictor_institutes',$mba_predictor_institutes);
		}
	}


	public function getBoardsAndGraduationStream(){
		$this->initiateModel('read');
		$returnData = array();
		$query = "SELECT board_name,board_alias FROM mba_predictor_school_boards ORDER BY board_name";
		$returnData["board"] = $this->db->query($query)->result_array();
		$query = "SELECT stream_name,stream_alias FROM mba_predictor_graduation_stream ORDER BY stream_name";
		$returnData["graduation_stream"] = $this->db->query($query)->result_array();
		return $returnData; 
	}

	/***
	*
	*
	*/
	function insertBoardIntoDataBase($data){
		if(empty($data)){
			return;
		}
		$this->initiateModel('write');		
		$this->db->insert_batch("mba_predictor_school_boards",$data);
	}

	/***
	*
	*
	*/
	function insertGraduationStreamsIntoDataBase($data){
		if(empty($data)){
			return;
		}
		$this->initiateModel('write');		
		$this->db->insert_batch("mba_predictor_graduation_stream",$data);
	}
	/**
	*
	*/
	function getSortedInstitutes($institutes,$category,$year,$offset=0,$limit=10){

		if(empty($institutes))
			return;

		$this->initiateModel('read');
		$query = "SELECT ins.institute_id as instituteId,vrc as VRC_Percentile,dilr as DILR_Percentile,qa as QA_Percentile,total as Total_Percentile,article_id as articleId, course_id as courseId, article_label as articleLabel FROM mba_predictor_cut_off cut INNER JOIN mba_predictor_institutes ins ON cut.institute_id = ins.institute_id WHERE cut.institute_id IN (?) AND student_category = ? AND year = ? ORDER BY total desc,avg_rating_course DESC,instituteId desc limit $offset,$limit";
		$resultData = $this->db->query($query,array($institutes,$category,$year))->result_array();
		$rs = array();
		foreach ($resultData as $key => $value) {
			$rs[$value['instituteId']] = $value;
		}
		return $rs;
	}

	function getEligibilityCollegesCount($data,$year){
		/**
		select ins_id from A where (10_c > 0 AND 10_c < 10_u AND 12_c > 0 AND 12_c < 12_u) OR (sci_c > 0 AND sci_u > 0 AND sci_c < sci_u) OR (arts_c > 0 AND arts_u > 0 AND arts_c < arts_u) OR (comm_c > 0 AND comm_u > 0 AND comm_c < comm_u)
		*/

		$catScoreCondition = "";
		if(isset($data['VRC_Percentile']) && isset($data['DILR_Percentile']) && isset($data['QA_Percentile'])){
			$catScoreCondition = " AND vrc <= ? AND dilr <= ? AND qa <= ?";
		}

		$whereCondition = "";
		$avgValue = 0;

		if(!empty($data) && isset($data['xiithStream']) && in_array($data['xiithStream'], array('Commerce','Science','Arts'))) {
			$whereCondition = " average_".strtolower($data['xiithStream']) .' > 0 ' ;
			$whereCondition .=  " AND average_".strtolower($data['xiithStream']) .' <= ?' ;
			$whereCondition .= " AND graduation_percentile <= ?";
			$avgValue = $data['X_XII_avg'];

			$whereCondition = "OR (".$whereCondition.") ";
		}

		$this->initiateModel();
		$sql = "SELECT institute_id from mba_predictor_eligibility where student_category = ? AND Year = ? AND (((class_10 <= ? AND class_12 <= ? AND graduation_percentile <= ?  AND average_science = 0 AND average_commerce = 0 AND average_arts = 0) ".$whereCondition.") ". $catScoreCondition .")";

		$result = $this->db->query($sql,array($data['category'],$year,$data['xthPercentage'],$data['xiithPercentage'],$data['graduationPercentage'],$avgValue,$data['graduationPercentage'],$data['VRC_Percentile'],$data['DILR_Percentile'],$data['QA_Percentile']))->result_array();

		//_p($this->db->last_query());die;

		//_p($this->db->last_query());die;

		$rs = array();
		foreach ($result as $key => $value) {
			$rs[$value['institute_id']] = $value['institute_id'];
		}
		return $rs;
	}
	function getInEligibilityCollegesCount($data,$year,$fetchInEligibleInfo){
		$whereCondition = "";
		$avgValue = 0;		

		$catScoreCondition = "";
		if(isset($data['VRC_Percentile']) && isset($data['DILR_Percentile']) && isset($data['QA_Percentile'])){
			$catScoreCondition = " OR vrc > ? OR dilr > ? OR qa > ?";
		}

		if(!empty($data) && isset($data['xiithStream']) && in_array($data['xiithStream'], array('Commerce','Science','Arts'))) {
			$whereCondition = " (average_".strtolower($data['xiithStream']) .' > 0 ' ;
			$whereCondition .=  " AND average_".strtolower($data['xiithStream']) .' > ? )' ;
			$whereCondition .= " OR (graduation_percentile > ? )";
			$avgValue = $data['X_XII_avg'];

			$whereCondition = "OR (".$whereCondition.") ";
		}
		$this->initiateModel();

		$selectClause = "";

		if($fetchInEligibleInfo){
			$selectClause = " ,class_10 as xthPercentage ,class_12 as xiithPercentage ,graduation_percentile as graduationPercentage,average_science,average_commerce,average_arts,vrc as VRC_Percentile,dilr as DILR_Percentile,qa as QA_Percentile ";
		}

		$sql = "SELECT institute_id as instituteId ".$selectClause." from mba_predictor_eligibility where student_category = ? AND Year = ? AND ((((class_10 > ? OR class_12 > ? OR graduation_percentile > ?) AND average_science = 0 AND average_commerce = 0 AND average_arts = 0) ".$whereCondition.") ".$catScoreCondition." )";

		$result = $this->db->query($sql,array($data['category'],$year,$data['xthPercentage'],$data['xiithPercentage'],$data['graduationPercentage'],$avgValue,$data['graduationPercentage'],$data['VRC_Percentile'],$data['DILR_Percentile'],$data['QA_Percentile']))->result_array();

		$rs = array();
		foreach ($result as $key => $value) {
			$rs[$value['instituteId']] = $value;
		}
		return $rs;
	}

	function getInstitutesMappingData($instituteIds, $isFetchAll=false){

		if(empty($instituteIds) && !$isFetchAll){
            return;
        }

		$this->initiateModel();

		$sql    =   "SELECT institute_id as instituteId,course_id as courseId,article_id as articleId,article_label as articleLabel"
		            ." FROM mba_predictor_institutes ";
        /**
         * Append instituteIds if available
         */
		if (!empty($instituteIds)) {
		    $sql .= "where institute_id IN (?)";
            $result = $this->db->query($sql,array($instituteIds))->result_array();
        } elseif ($isFetchAll){
		    $sql .= "where 1";
            $result = $this->db->query($sql)->result_array();
        } else{
		    return;
        }

		$rs = array();
		foreach ($result as $key => $value) {
			$rs[$value['instituteId']] = $value;
		}
		return $rs;
	}

	public function updateAverageReviewOfInstitutes($updateDataArray = array()) {
	    if (null == $updateDataArray || empty($updateDataArray)) {
	        return false;
        }
	    $this->initiateModel('write');
	    $updateSql  =   "UPDATE `mba_predictor_institutes` SET `avg_rating_course` = ? "
                        ."WHERE `institute_id` = ? AND `course_id` = ? ";
	    foreach ($updateDataArray AS $key=>$value){
            $this->db->query($updateSql,array($value['avgRating'],$key,$value['courseId']));
        }
	    return true;
    }

    public function getInstituteIdsPredictorTable($year){
    	if(empty($year)){
    		return array();
    	}
    	$this->initiateModel("read");
    	$sql = "SELECT distinct institute_id as instituteId FROM mba_predictor_eligibility where year = ?";
    	$result = $this->db->query($sql,array($year))->result_array();
    	$rs = array();
    	foreach ($result as $key => $value) {
    		if(!empty($value['instituteId'])){
    			$rs[] = $value['instituteId'];
    		}
    	}
    	return $rs;
    }

	function getPredictorTrackingData($startDate,$endDate,$pageNo,$pageSize){
		$sql = "select userId,cat_total as score,cat_percentile AS percentile,submit_date AS SubmitDate from ICP_userTracking where submit_date >= ? AND submit_date <= ? group by userId limit ?, ? ";
		$this->initiateModel();
		$query = $this->db->query($sql,array($endDate,$startDate,$pageNo*$pageSize,$pageSize));
		return $query->result_array();
	}

	function getTrackingDataCount($startDate,$endDate){
		$this->initiateModel();
		$sql = "select count(distinct userId) as count from ICP_userTracking where submit_date >= ? AND submit_date <= ?";
		$query = $this->db->query($sql,array($endDate,$startDate));
		return $query->result_array();
	}

}

