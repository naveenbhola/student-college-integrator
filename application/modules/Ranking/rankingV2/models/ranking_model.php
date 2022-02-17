<?php

class ranking_model extends MY_Model {
	private $dbHandle = '';
	
	function __construct(){
		parent::__construct('Ranking');
		$this->config->load('ranking_config');
    }
	
	private function initiateModel($mode = "write"){

		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;
		
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
		$this->dbHandle->_protect_identifiers = false;
    }

    public function getRankingPageDataCount($rankingPageIds){
        if(!is_array($rankingPageIds) || count($rankingPageIds)==0){
            return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select('ranking_page_id,count(*) as dataCount');
        $this->dbHandle->from(RANKING_PAGE_DATA_TABLE);
        $this->dbHandle->where_in('ranking_page_id',$rankingPageIds);
        $this->dbHandle->group_by('ranking_page_id');
        $result = $this->dbHandle->get()->result_array();
        $rankingPageDataCount = array();
        foreach ($result as $dataRow){
            $rankingPageDataCount[$dataRow['ranking_page_id']] = $dataRow['dataCount'];
        }
        return $rankingPageDataCount;
    }

	public function getTierUsingCombination(&$rankingPage){
        $this->initiateModel('read');
        $stream_id        = (''!=$rankingPage->getStreamId())?$rankingPage->getStreamId():0;
        $substream_id     = (''!=$rankingPage->getSubstreamId())?$rankingPage->getSubstreamId():0;
        $credential       = (''!=$rankingPage->getCredential())?$rankingPage->getCredential():0;
        $education_type   = (''!=$rankingPage->getEducationType())?$rankingPage->getEducationType():0;
        $delivery_method  = (''!=$rankingPage->getDeliveryMethod())?$rankingPage->getDeliveryMethod():0;
        $base_course_id   = (''!=$rankingPage->getBaseCourseId())?$rankingPage->getBaseCourseId():0;
        
        $this->dbHandle->select('tier');
        $this->dbHandle->from('category_subscription_criteria');
        $this->dbHandle->where('stream_id',$stream_id);
        $this->dbHandle->where('substream_id',$substream_id);
        $this->dbHandle->where('education_type',$education_type);
        $this->dbHandle->where('delivery_method',$delivery_method);
        $this->dbHandle->where('base_course_id',$base_course_id);
        $this->dbHandle->where('credential',$credential);
        $result=$this->dbHandle->get()->result_array();
        return $result[0]['tier'];
    }
	
	public function getRankingPages($params = array(),$isNewHandle = true){
		if(empty($params)){
			return array();
		}
		if($isNewHandle){
			$this->initiateModel('read');
		}
		$this->dbHandle->select("SQL_CALC_FOUND_ROWS *",FALSE);
		$this->dbHandle->from(RANKING_PAGE_TABLE);
		if(isset($params['id'])) {
			$this->dbHandle->where('id',$params['id']);
		}

		if(isset($params['status'])) {
			$this->dbHandle->where_in('status',$params['status']);
		}
		
		if(isset($params['orderBy']) && $params['orderBy'] == 'ranking_page_text') {
			$this->dbHandle->order_by('ranking_page_text');
		}
		else if(isset($params['orderBy']) && $params['orderBy'] == 'ranking_page_id') {
			$this->dbHandle->order_by('id');
		}
		else {
			$this->dbHandle->order_by('updated desc');
		}

		$calculateTotalRows = false;
		if(!empty($params['limit'])){
			if(isset($params['offset'])){
				$calculateTotalRows = true;
				$this->dbHandle->limit($params['limit'],$params['offset']);
			}
		}
		$data = $this->dbHandle->get()->result_array();
		
		$totalRows = -1;
		if($calculateTotalRows){
			$queryCmdTotal 		= 'SELECT FOUND_ROWS() as totalRows';
			$totalRows 		= reset(reset($this->dbHandle->query($queryCmdTotal)->result_array()));
		}
		
		$returnData = array();
		if($calculateTotalRows){
			$returnData['results'] 		= $data;
			$returnData['totalrows'] 	= $totalRows;
		} else {
			$returnData = $data;
		}
		return $returnData;
	}
	
	public function changeRankingPageStatus($id = NULL, $status = NULL){
		if(empty($status) || empty($id)){
			return false;
		}
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		$queryCmd ="UPDATE ". RANKING_PAGE_TABLE . " ".
					"SET ".
					"status = ? ".
					"WHERE ".
					"id = ? ";					
		$query = $this->dbHandle->query($queryCmd, array($status, $id));
		$data = $this->getPageSourceMappingData($id);
		$sourceIds = array();
		foreach($data as $mapping) {
			$sourceIds[] = $mapping['source_id'];
		}
		$query = $this->updateSourceMappingStatusToHistory($id);
		$result = $this->insertRankingSourceMappings($id, $sourceIds, $status);
		
		$this->dbHandle->trans_complete();
		
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
	  	}
		$return = true;
		return $return;
	}
	
	private function updateRankingPageLastUpdatesTime($id = NULL, $newDbHandleFlag = true){

		if(empty($id)){
			return false;
		}
		if($newDbHandleFlag) {
			$this->initiateModel('write');
		}
		
		$queryCmd ="UPDATE ". RANKING_PAGE_TABLE . " ".
					"SET ".
					"updated = '" . date("Y-m-d H:i:s") . "' ".
					"WHERE ".
					"id = ? ";
		$query = $this->dbHandle->query($queryCmd, array($id));
		$return = false;

		if($query === true){
			$return = true;
		} 
		return $return;
	}
	
	public function deleteCourseFromRankingPageData($rankingPageId = NULL, $courseId = NULL){
		if(empty($rankingPageId) || empty($courseId)){
			return false;
		}
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		$queryCmd ="SELECT ".
					"id ".
					"FROM ". RANKING_PAGE_DATA_TABLE ." ".
					"WHERE ".
					"ranking_page_id = ? AND ".
					"course_id = ? ";
		$query = $this->dbHandle->query($queryCmd, array($rankingPageId, $courseId));
		$data = array();
		$id = -1;
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$id = $row->id;
			}
		}
		$rankingPageUpdateFlag = false;
		
		$queryCmd ="DELETE ".
					"FROM ". RANKING_PAGE_DATA_TABLE ." ".
					"WHERE ".
					"ranking_page_id = ? AND ".
					"course_id = ? ";
		$query = $this->dbHandle->query($queryCmd, array($rankingPageId, $courseId));
		$noOfRowsUpdated = $this->dbHandle->affected_rows();
		if($noOfRowsUpdated > 0){
			$rankingPageUpdateFlag = true;
		}
		
		if($id != -1){
			$queryCmd ="DELETE ".
						"FROM ".
						"ranking_page_course_source_data ".
						"WHERE ".
						"ranking_page_course_id = ? ";
			$query = $this->dbHandle->query($queryCmd, array($id));
			$noOfRowsUpdated = $this->dbHandle->affected_rows();
			if($noOfRowsUpdated > 0){
				$rankingPageUpdateFlag = true;
			}
		}
		
		if($rankingPageUpdateFlag){
			$this->updateRankingPageLastUpdatesTime($rankingPageId, false); //update lastupdate time of ranking page
		}
		
		$this->dbHandle->trans_complete();
		
		if ($this->dbHandle->trans_status() === FALSE) {
			$return = false;
			throw new Exception('Transaction Failed');
	  	}
		
		$return = true;
		return $return;
	}
	
	public function removeCourseFromSourceData($rankingPageId = NULL, $courseId = NULL, $sourceId = NULL) {
		if(empty($rankingPageId) || empty($courseId) || empty($sourceId)){
			return false;
		}
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		$queryCmd ="SELECT id FROM ". RANKING_PAGE_DATA_TABLE ." WHERE ranking_page_id = ? AND course_id = ?";
		$query = $this->dbHandle->query($queryCmd, array($rankingPageId, $courseId));
		
		$id = -1;
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$id = $row->id;
			}
		}
		$rankingPageUpdateFlag = false;
		
		if($id != -1){
			$queryCmd ="DELETE FROM ranking_page_course_source_data WHERE ranking_page_course_id = ? AND source_id = ?";
			$query = $this->dbHandle->query($queryCmd, array($id, $sourceId));
			$noOfRowsUpdated = $this->dbHandle->affected_rows();
			if($noOfRowsUpdated > 0){
				$rankingPageUpdateFlag = true;
			}
		}
		$this->updateRankingPageLastUpdatesTime($rankingPageId, false); //update lastupdate time of ranking page
		
		$this->dbHandle->trans_complete();
		
		if ($this->dbHandle->trans_status() === FALSE) {
			$return = false;
			throw new Exception('Transaction Failed');
	  	}
		
		$return = true;
		return $return;
	}
	
	public function getRankingPageData($params = array()){
		if(empty($params)){
			return array();
		}
		$this->initiateModel('read');	
		$queryCmd ="SELECT *
					FROM
					" . RANKING_PAGE_DATA_TABLE ."
					WHERE
					1
					";
		$queryStringExt = "";
		if(!empty($params['ranking_page_id'])){
			if(is_array($params['ranking_page_id'])){
				$sqlString = implode(",", $params['ranking_page_id']);
				$sqlString = trim($sqlString);
				$sqlString = trim($sqlString, ",");
				$sqlString = " AND ranking_page_id IN (".$sqlString.")";
				$queryStringExt .= $sqlString;
			} else {
				$queryStringExt .= " AND ranking_page_id = ".$this->dbHandle->escape($params['ranking_page_id'])."";
			}
		}
		if(!empty($params['institute_id'])){
			$queryStringExt .= " AND institute_id = ".$this->dbHandle->escape($params['institute_id'])."";
		}
		if(!empty($params['course_id'])){
			$queryStringExt .= " AND course_id = ".$this->dbHandle->escape($params['course_id'])."";
		}
		if(!empty($params['id'])){
			$queryStringExt .= " AND id = ".$this->dbHandle->escape($params['id'])."";
		}
		//$queryStringExt .= " ORDER BY rank ASC";
		
		$queryCmd = $queryCmd.$queryStringExt;
		$query = $this->dbHandle->query($queryCmd);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = (array)$row;
			}
		}
		return $data;
	}
	
	public function saveRankingCourseDetails($rankPageId, $instituteId, $courseId, $rankScoreDetails, $courseAltText, $sourceId){
		$return = array("success" => "false", "error_type" => array());
		if(empty($rankPageId) || empty($instituteId) || empty($courseId) || empty($courseAltText)){
			$return['error_type'][] = "INVALID_INPUT_PARAMS";
			return $return;
		}
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		$insertData = array (
								'ranking_page_id' => $rankPageId,
								'institute_id' => $instituteId,
								'course_id' => $courseId,
								'course_alt_text' => addslashes($courseAltText),

								);

		$queryCmd = $this->dbHandle->insert_string(RANKING_PAGE_DATA_TABLE,$insertData);
        $queryCmd = $queryCmd." ON DUPLICATE KEY UPDATE ".
        			"course_alt_text = '".addslashes($courseAltText)."' ";

		$query 	= $this->dbHandle->query($queryCmd);
		if($query === true){
			$rankingCourseId = $this->getRankingCourseId($rankPageId, $courseId);
			$paramQueryResult = $this->insertParamValues($rankScoreDetails, $rankingCourseId, $sourceId);
			$this->updateRankingPageLastUpdatesTime($rankPageId, false);
			$return["success"] 			= "true";
			$return["error_type"] 		= array();
		}
		$this->dbHandle->trans_complete();
		if($this->dbHandle->trans_status() == FALSE){
			$return['success'] 			= "false";
			$return["error_type"][] 	= "Transaction Failed";

		}
		return $return;
	}
	
	private function getRankingCourseId($rankPageId, $courseId) {
		$query = "SELECT id FROM ".RANKING_PAGE_DATA_TABLE." WHERE ranking_page_id = ? AND course_id = ?";
		$result = $this->dbHandle->query($query,array($rankPageId,$courseId))->result_array();
		return $result[0]['id'];
	}
	
	private function insertParamValues($rankScoreDetails, $rankingCourseId, $sourceId) {
	
		$paramDetails	 = explode(":",$rankScoreDetails);
		$paramDetails['rank'] = $paramDetails[1];
		$queryResultFlag = true;

		if($paramDetails['rank'] == 'NULL') {
				$queryCmd ="DELETE FROM ranking_page_course_source_data WHERE ranking_page_course_id = ? AND source_id = ? ";
				$query = $this->dbHandle->query($queryCmd, array($rankingCourseId, $sourceId));
				if($query !== true) {
					$queryResultFlag = false;
				}
			}
			else
			{
				$insertData = array (
									'ranking_page_course_id' => $rankingCourseId,
									'source_id' => $sourceId,
									'rank' => $paramDetails['rank']);

				$queryCmd = $this->dbHandle->insert_string('ranking_page_course_source_data',$insertData);
	        	$queryCmd = $queryCmd." ON DUPLICATE KEY UPDATE ".
							"rank = ".$this->dbHandle->escape($paramDetails['rank']);

				$query	= $this->dbHandle->query($queryCmd);
				if($query !== true) {

					$queryResultFlag = false;
				}			
			}
		return $queryResultFlag;
	}
	
	public function getExamById($id = NULL){
		if(empty($id)){
			return array();
		}
		$this->initiateModel('read');	
		$queryCmd ="SELECT * FROM exampage_main WHERE id = ? AND status = 'live'";
					
		$query = $this->dbHandle->query($queryCmd, array($id));
		$data = array();
		if($query->num_rows() > 0) {
			$row = $query->result();
			$data = (array)$row[0];
		}
		return $data;
	}
	
	public function getRankingPagesBySpecializations($specializationIds = array()){ //change
		if(empty($specializationIds)){
			return array();
		}
		$this->initiateModel('read');
		
		$specializationIdsString = implode(",", $specializationIds);
		$specializationIdsString = trim($specializationIdsString, ",");
		
		$queryCmd ="SELECT *
					FROM
					" . RANKING_PAGE_TABLE . "
					WHERE
					specialization_id IN (".$specializationIdsString.") AND
					status = 'live'
					";
		$query = $this->dbHandle->query($queryCmd);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = (array)$row;
			}
		}
		return $data;
	}
	
	/*Banner related functions */
	
	public function insertBannerDetails($data) {
		$this->initiateModel('write');
		$params = array(
					'ranking_page_id' 		=> $data['ranking_page_id'],
					'file_path'		  		=> $data['file_path'],
					//'landing_url'			=> $data['landing_url'],
					'client_id'				=> $data['client_id'],
					'set_time'				=> date("Y-m-d H:i:s"),
					'subscription_id'		=> $data['subscription_id'],
					'subscription_start_time'	=> $data['subscription_startDate'],
					'subscription_expire_time'	=> $data['subscription_endDate'],
					'status'					=> 'live',
					'city_id'					=> $data['city_id'],
					'state_id'					=> $data['state_id']
					);
		$queryCmd 			= $this->dbHandle->insert_string('ranking_banner_products', $params);
		$query 				= $this->dbHandle->query($queryCmd); 
		$unique_insert_id 	= $this->dbHandle->insert_id();
		return $unique_insert_id;
	}
	
	public function getAllBanners($limit = -1, $offset = 0){
		$this->initiateModel('read');
		$data = array();
		
		$limitCondition = "";
		if($limit != -1){
			$limitCondition = " LIMIT $offset, $limit";
		}
		$queryCmd = "SELECT rp.ranking_page_text as ranking_page_text, rbp.* 
					FROM
					" . RANKING_PAGE_TABLE . " rp,
					ranking_banner_products as rbp
					WHERE
					rp.id = rbp.ranking_page_id AND
					rp.status = rbp.status AND
					rp.status= 'live'
					ORDER BY set_time DESC
					$limitCondition
					";
		
		$totalRows = -1;
		if($limit != -1){
			$queryCmdTotal 		= 'SELECT count(*) as totalRows from ranking_banner_products WHERE status = "live"';
			$queryTotal 		= $this->dbHandle->query($queryCmdTotal);
			if($queryTotal){
				$queryTotalResult 	= $queryTotal->result();
				$totalRows 			= $queryTotalResult[0]->totalRows;
			}
		}
		
		$data['results'] 	= array();
		$data['total_rows'] = $totalRows;
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data['results'][] = (array)$row;
			}
		}
		return $data;
	}
	
	public function deleteBanner($banner_id = NULL){ //change
		if(empty($banner_id)) {
			return false;
		}
		
		$this->initiateModel('write');
		
		// $queryCmd = "UPDATE
		// 			 ranking_banner_products
		// 			 SET
		// 			 status = 'disable'
		// 			 WHERE
		// 			 id = $banner_id";

		$updateData = array ('status'=>'disable');
		$where = "id = $banner_id"; 
		$queryCmd = $this->dbHandle->update_string('ranking_banner_products', $updateData, $where); 

		$query = $this->dbHandle->query($queryCmd);
		$returnFlag = false;
		if($query === true){
			$returnFlag = true;
		}
		return $returnFlag;
	}
	
	public function getBannerData($banner_id = NULL){ //change
		if(empty($banner_id)) {
			return; 
		} 
		$this->initiateModel('read');
		$queryCmd = "SELECT
					*
					FROM
					ranking_banner_products
					WHERE
					id = ".$this->dbHandle->escape($banner_id);
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
			$row = $query->result();
			$data = (array)$row[0];
		}
		return $data;
	}
	
	public function updateBanner($bannerId = NULL, $data){ //change
		if(empty($bannerId)){
			return false;
		}
		$this->initiateModel('write');
		
		$landing_url 	= $data['landing_url'];
		$file_path 		= $data['file_path'];
		$landinURLSql 	= "";
		$bannerSql 		= "";
		if(!empty($landing_url)){
			$landinURLSql = " landing_url = '" .$landing_url. "'";
		}
		if(!empty($file_path)){
			$commaString = "";
			if(!empty($landinURLSql)){
				$commaString = ",";
			}
			$bannerSql = $commaString . " file_path = '" .$file_path. "'";
		}
		
		if(empty($landinURLSql) && empty($bannerSql)){
			return false;
		}
		
		$queryCmd = "UPDATE
					 ranking_banner_products
					 SET
					 $landinURLSql
					 $bannerSql
					 WHERE
					 id = ".$this->dbHandle->escape($bannerId);
		$query 	= $this->dbHandle->query($queryCmd);
		$return = false;
		if($query === true) {
			$return = true;
		}
		return $return;
	}
	
	public function getBannersByLocationAndRankingPage($rankingPageId = NULL, $locationType = NULL, $locationId = NULL, $client_id = NULL){ //change
		$data = array();
		if(empty($rankingPageId) || empty($locationType) || empty($locationId)){
			return $data;
		}
		$this->initiateModel('read');
		
		$locationSQL = "";
		if($locationType == "city"){
			$locationSQL = " city_id = ".$this->dbHandle->escape($locationId)." ";
		} else if($locationType == "state"){
			$locationSQL = " state_id = ".$this->dbHandle->escape($locationId)." ";
		}
		
		$clientSQL = "";
		if(!empty($client_id)){
			$clientSQL = " AND client_id = ".$this->dbHandle->escape($client_id)." ";
		}
		
		$queryCmd = "SELECT
					*
					FROM
					ranking_banner_products
					WHERE
					ranking_page_id = ".$this->dbHandle->escape($rankingPageId)." AND
					$locationSQL
					$clientSQL
					AND
					status = 'live'
					AND
					subscription_expire_time >= NOW()
					";
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = (array)$row;
			}
		}
		return $data;
	}
	
	function changeRankingPageMetaDetails($data = NULL) {
		if($data['rankingPageLocationType'] == 'city'){
			$whereStatement = ' and city_id = ?';
		}
		else{
			$whereStatement = ' and state_id = ?';	
		}
		$param[] = (int)$data['rankingPageLocationId'];

		$this->initiateModel('write');
		$returnFlag = false;
		$this->dbHandle->trans_start();
		$query = "update rankingpage_meta_details set status='history' WHERE status='live' and ranking_page_id = ".$this->dbHandle->escape($data['rankingPageId']);
		$query .= $whereStatement;
		$query = $this->dbHandle->query($query, $param);
		$params = array(
				'ranking_page_id' 			=> $data['rankingPageId'],
				'ranking_page_title'		=> $data['rankingPageTitle'],
				'ranking_page_description'	=> $data['rankingPageDescription'],
				'h1'		=> $data['rankingPageHeading'],
				'last_updated_time'			=> date("Y-m-d H:i:s"),
				'ranking_page_title_exam' => $data['rankingPageTitleExam'],
				'ranking_page_description_exam' => $data['rankingPageDescriptionExam'],
				'counter'					=> 1,
				'disclaimer' => $data['disclaimer'],
				$data['rankingPageLocationType'].'_id' => $data['rankingPageLocationId'],
				'status' => 'live'
			);
		$queryCmd 			= $this->dbHandle->insert_string('rankingpage_meta_details', $params);
		$query 				= $this->dbHandle->query($queryCmd); 
		if($query === true){
			$returnFlag = true;
		}
		$this->dbHandle->trans_complete();
		
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
	  	}
		return $returnFlag;
	}
	
	function getRankingPageMetaDetails($rankingPageId = NULL) {
		$data = false;
		if(empty($rankingPageId)){
			return $data;
		}
		$this->initiateModel('write');
		$query = $this->dbHandle->where('ranking_page_id',$rankingPageId)->get('rankingpage_meta_details');
		if($query->num_rows() > 0) {
			$row = $query->result();
			$result = (array)$row[0];
			$data['title'] 		 = $result['ranking_page_title'];
			$data['description'] = $result['ranking_page_description'];
			$data['title_exam'] 		 = $result['ranking_page_title_exam'];
			$data['description_exam'] 	 = $result['ranking_page_description_exam'];
		}
		return $data;
	}
	
	function getRankingPageRowsCount($rankingPageId = NULL) {
		$data = false;
		$this->initiateModel('read');
		$queryCmd = "SELECT ranking_page_id, count(ranking_page_id) as count
					 FROM " . RANKING_PAGE_DATA_TABLE ."
					 GROUP BY ranking_page_id";
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$tempData = (array)$row;
				$data[$tempData['ranking_page_id']] = $tempData['count'];
			}
		}
		return $data;
	}
	
	function getAllSources($status = array('live')) {
		$this->initiateModel('read');
		$query = "SELECT source_id, publisher_id, publisher_name as name, year, status FROM ranking_page_sources WHERE status in (?) order by publisher_id desc, year asc;";
		$result = $this->dbHandle->query($query,array($status))->result_array();
		return $result;
	}
	
	private function insertRankingSourceMappings($rankingPageId, $sourceIds, $status) {
		foreach($sourceIds as $sourceId) {
			$data = array(
			    'id' 				=> NULL,
			    'ranking_page_id' 	=> $rankingPageId,
			    'source_id' 		=> $sourceId,
			    'status' 			=> $status
			    );
		    $arrayToBeInserted[] = $data;
		}
		return $this->dbHandle->insert_batch('ranking_page_source_mapping', $arrayToBeInserted);
	}
	
	function getRankingPageSourceData($rankingPageId, $rankingPageStatus) { //check for QA
		$this->initiateModel('read');
		$query = "SELECT rps.source_id, rps.publisher_name as name, rps.year ".
				"FROM ranking_page_source_mapping as rpsm ".
				"INNER JOIN ranking_page_sources as rps ON rpsm.source_id = rps.source_id AND rps.status = 'live' ".
				"WHERE rpsm.ranking_page_id = ? AND rpsm.status = ?";
		$result = $this->dbHandle->query($query,array($rankingPageId,$rankingPageStatus))->result_array();	
		return $result;
	}
	
	function getRankingPageCourseParamData($rankingPageCourseIds) { //check for QA//status check
		$this->initiateModel('read');
		//$query = "SELECT * FROM ranking_page_course_source_data WHERE ranking_page_course_id IN (".$this->dbHandle->escape(implode(',',$rankingPageCourseIds)).")";
		//$result = $this->dbHandle->query($query)->result_array();
		$this->dbHandle->from('ranking_page_course_source_data');
		$this->dbHandle->where_in('ranking_page_course_id',$rankingPageCourseIds);
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		return $result;
	}
	
	private function updateSourceMappingStatusToHistory($rankingPageId) {
		$queryCmd ="UPDATE ".
					"ranking_page_source_mapping ".
					"SET ".
					"status = 'history' ".
					"WHERE ".
					"ranking_page_id = ? ";
		$query = $this->dbHandle->query($queryCmd, array($rankingPageId));
		return $query;
	}
	
	function getPageSourceMappingData($rankingPageId) {
		$query = "SELECT * FROM ranking_page_source_mapping WHERE ranking_page_id = ? AND status IN ('live','draft','disable')";
		$result = $this->dbHandle->query($query, $rankingPageId)->result_array();
		return $result;
	}

	private function _getNewAutogeneratedId($type = '') {
		return Modules::run('common/IDGenerator/generateId',$type);
	}
	
	function validateSourceForm() {
		$error = '';
		if($this->input->post('sourceName')     == '') $error .= '--Source Name is empty';
		return $error;
	}
	
	function insertSourceData($data) {
		$this->initiateModel('write');
		
		$this->dbHandle->trans_start();
		
		if($data['action'] == 'save') {
			$data['publisherId'] = $this->_getNewAutogeneratedId('ranking_publisher');
		} 
		elseif($data['action'] == 'edit') {
			$currentData = $this->getPublisherData($data['publisherId'], array('live', 'disable'));
			
			if($currentData[0]['name'] != $data['sourceName']) {
				foreach ($currentData as $key => $value) {
					$insertData[] = array(
						'source_id' => $value['source_id'],
						'publisher_id' => $value['publisher_id'],
						'publisher_name' => $data['sourceName'],
						'year' => $value['year'],
						'status' => $value['status'],
						'created' => $value['created']
			        );
				}
				$this->updatePublisherDataStatus('history', $data['publisherId']);
			}
		}

		foreach ($data['year'] as $key => $year) {
			$rankingSourceId[$key] = $this->_getNewAutogeneratedId('ranking_source');
		}

		foreach ($data['year'] as $key => $year) {
			$insertData[] = array(
				'source_id' => $rankingSourceId[$key],
				'publisher_id' => $data['publisherId'],
				'publisher_name' => $data['sourceName'],
				'year' => $year,
				'status' => $data['status'],
				'created' => date('Y-m-d H:i:s')
	        );
		}
		if(!empty($insertData)){
				$this->dbHandle->insert_batch('ranking_page_sources', $insertData);
        }
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
                throw new Exception('Transaction Failed');
        }
        return true;
	}
	
	function updatePublisherDataStatus($status, $rankingPublisherId) {
		$sql = "Update ranking_page_sources set status=?, last_modified = NOW() where publisher_id=? AND status IN ('live','disable');";
		$this->dbHandle->query($sql, array($status, $rankingPublisherId));
	}

	function updateSourceDataStatus($status, $rankingSourceId) {
		$this->initiateModel('write');
		
		$this->dbHandle->trans_start();

		$sql = "Update ranking_page_sources set status=?, last_modified = NOW() where source_id=? AND status IN ('live','disable');";
		$this->dbHandle->query($sql, array($status, $rankingSourceId));

		$this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
                throw new Exception('Transaction Failed');
        }
        return true;
	}

	function getPublisherData($publisherIds, $status = array('live')) {
		$this->initiateModel('read');

		if(!is_array($publisherIds)) {
			$publisherIds = array($publisherIds);
		}

		if(!empty($publisherIds)) {
			$sql = "select *,rps.publisher_name as name from ranking_page_sources rps where publisher_id IN (?) AND status in ( ? ) order by year desc ";
			$queryTotal  = $this->dbHandle->query($sql, array($publisherIds, $status));
			$data        = $queryTotal->result_array();
			
			return $data;
		}
		else {
			return false;
		}
	}
	
	function updateSourceRankingPage($data) {
		$return = array("success" => "false", 'error_type' => array('SERVER_ERROR'));
		if(empty($data) || empty($data['sourceSelectedValuesArray']) || empty($data['rankingPageId'])){
			$error_type = array();
			$error_type[] = "INVALID_INPUT_PARAMS";
			$return['error_type'] = $error_type;
			return $return;
		}
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		$rankingPageId = $data['rankingPageId'];
		$rankingPageStatus = $data['rankingPageStatus'];
		$sourceSelectedValuesArray = $data['sourceSelectedValuesArray'];
		
		$query = $this->updateSourceMappingStatusToHistory($rankingPageId);
		$result = $this->insertRankingSourceMappings($rankingPageId, $sourceSelectedValuesArray, $rankingPageStatus);
		$this->updateRankingPageLastUpdatesTime($rankingPageId, false); //update lastupdate time of ranking page
		
		$this->dbHandle->trans_complete();
		
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
	  	}
		
		if($query === true && $result === true) {
			$return["success"] 			= "true";
			$return["error_type"] 		= array();
		}
		return $return;
	}
	
	/**
	 *@method: Returns ranking page details data(ranking page rows source-wise)
	 *@params: 1. array of paramters containing ranking page id, status, source-id etc.
	 *@return: Array containing ranking page details data, source data
	 *@author: Romil
	 */
	public function getRankingPagesData($params = array()){
		if(empty($params)){
			return array();
		}
		$source_id = $params['source_id'];
		$status = $params['status'];
		$pageId = $params['ranking_page_id'];
		$publisherId = $params['publisherId'];
		unset($params['status']);
		unset($parans['source_id']);
		unset($params['ranking_page_id']);
		$params = $this->_cleanseHeirarchyParams($params);
		// return empty array if no input parameters are provided

		$actionType 	= $this->input->post("actionType");
		$columnType 	= $this->input->post("columnType");
		
		if($actionType == 'sort' && $columnType == 'source')
		{
			$columnTypeVal 	= $this->input->post("columnTypeVal");
			$sortType 	= $this->input->post("sortType");
		}
		$this->initiateModel('read');
		// get the source-id if not provided
        $recentSourcesData = $this->getRecentSourcesForPublisher($pageId,$publisherId);
        $recentSourceIds = array_keys($recentSourcesData);
        // _p($recentSourcesData); die('11');
        // $rankingPageSourceData 	= $this->getRankingPageSources($pageId,$status);
        // _p($rankingPageSourceData); die;
        $preferredSource 	= reset($recentSourceIds);
        // $source_id 		= $recentSourceIds[0];
		
		// prepare the query
		$this->dbHandle->select('rpd.id'); 
		$this->dbHandle->select('rpd.ranking_page_id'); 
		$this->dbHandle->select('rpd.institute_id'); 
		$this->dbHandle->select('rpd.course_id'); 
		$this->dbHandle->select('rpd.course_alt_text'); 
		$this->dbHandle->select('rpcsd.source_id'); 
		$this->dbHandle->select('rpcsd.rank'); 
		// $this->dbHandle->select('rpsp.source_id'); 
		// $this->dbHandle->select('rpsp.param_name'); 
		// $this->dbHandle->select('rpsp.param_value'); 
		$this->dbHandle->from(RANKING_PAGE_TABLE." rp");		
		$this->dbHandle->join(RANKING_PAGE_DATA_TABLE." rpd","rp.id = rpd.ranking_page_id","inner");
		$this->dbHandle->join("ranking_page_course_source_data rpcsd","rpd.id = rpcsd.ranking_page_course_id","inner");
		// $this->dbHandle->join("ranking_page_source_params rpsp","rpcsd.parameter_id = rpsp.param_id","inner");
		// $this->dbHandle->where("rpsp.param_name",OVERALL_PARAM);
		foreach($params as $key=>$value){
			$this->dbHandle->where('rp.'.$key,$value);
		}
		if(!empty($pageId)){
			$this->dbHandle->where('rp.id',$pageId);
		}
		if($status){
			if(is_array($status) && !empty($status)){
				$this->dbHandle->where_in('rp.status',$status);
			} else {
				$this->dbHandle->where('rp.status',$status);
			}
		}
		// _p($recentSourceIds); die;
		if(!empty($recentSourceIds)) {
			$this->dbHandle->where_in('rpcsd.source_id',$recentSourceIds);
		}

		if(!empty($preferredSource)) {
			$this->dbHandle->order_by("FIELD(rpcsd.source_id,".$preferredSource.") desc, ISNULL(rpcsd.rank),rpcsd.rank asc");
		}
		else {
			$this->dbHandle->order_by("rpcsd.rank asc");
		}
		// $this->dbHandle->group_by('rpd.institute_id');
		$data = $this->dbHandle->get()->result_array();
		// echo $this->dbHandle->last_query(); die;
		// _p($data); die;
		$returnData                          = array();
		$returnData['results']               = $data;
		$returnData['preferredSourceId']     = $preferredSource;
		$returnData['rankingPageSourceData'] = $recentSourcesData;
		return $returnData;
	}
	
	/**
	 *@method: Returns ranking page sources data for given ranking page id
	 *@params: 1. Ranking page id
	 *	   2. Status
	 *@return: Array containing ranking page source data
	 *@author: Romil
	 */
	public function getRankingPageSources($pageId,$status,$source_id)
	{
		$this->initiateModel('read');
		
		$this->dbHandle->select("rpsm.source_id");
		$this->dbHandle->select("rps.publisher_name as name");
		$this->dbHandle->select("rps.publisher_id");
		$this->dbHandle->select("rps.year");
		$this->dbHandle->from("ranking_pages rpn");
		$this->dbHandle->join("ranking_page_source_mapping rpsm","rpn.id = rpsm.ranking_page_id AND rpsm.status = rpn.status","inner");
		$this->dbHandle->join("ranking_page_sources rps","rpsm.source_id = rps.source_id AND rps.status = 'live'","inner");
		
		if(isset($pageId)){
			$this->dbHandle->where('rpn.id',$pageId);
		}
		if(isset($status)){
			if(is_array($status) && !empty($status)){
				$this->dbHandle->where_in('rpn.status',$status);
			} else {
				$this->dbHandle->where('rpn.status',$status);
			}
		}		
		if($source_id){
			$this->dbHandle->order_by("FIELD(rpsm.source_id, ".$source_id.") desc");
		}
		
		// get the result-set
		$rs 	= $this->dbHandle->get()->result_array();
		
		// format the data
		$sourceParamArr = array();
		foreach($rs as $sourceParamsRow)
		{
			$sourceParamArr[$sourceParamsRow['source_id']]['source_id'] 	= $sourceParamsRow['source_id'];
			$sourceParamArr[$sourceParamsRow['source_id']]['publisher_id'] 	= $sourceParamsRow['publisher_id'];
			$sourceParamArr[$sourceParamsRow['source_id']]['name'] 			= $sourceParamsRow['name'];
			$sourceParamArr[$sourceParamsRow['source_id']]['year'] 			= $sourceParamsRow['year'];
		}
		
		return $sourceParamArr;
	}
	
	public function getSourceWiseCourseRanks($courseId, $maxRank = 1000) {
		$this->initiateModel('read');
		// $sortOrder = $this->dbHandle->escape_str($sortOrder);
		$query = "SELECT DISTINCT rps.source_id as source_id, ".
						"rps.publisher_name as source_name, ".
						"rps.publisher_id as publisher_id, ".
						"rps.year as source_year, ".
						"rpsm.ranking_page_id as ranking_page_id, ".
						"rpcsd.rank as rank ".
				"FROM ranking_page_sources as rps ".
				"INNER JOIN ranking_page_source_mapping as rpsm ON rpsm.source_id = rps.source_id and rpsm.status = 'live' ".
				"INNER JOIN ".RANKING_PAGE_DATA_TABLE." as rpd ON rpd.ranking_page_id = rpsm.ranking_page_id and rpd.course_id = ? ".
				"INNER JOIN ranking_page_course_source_data as rpcsd ON rpcsd.ranking_page_course_id = rpd.id AND rpcsd.source_id = rps.source_id ".
				"WHERE rps.status = 'live' 
				AND rank < ? AND rps.publisher_id != ? ".
				"ORDER BY rps.year desc";
		
		$result = $this->dbHandle->query($query, array($courseId, $maxRank, RANKING_SHIKSHA_DEFAULT_PUBLISHER))->result_array();
		return $result;
	}


	function populateRankingNonZeroResultTable($insertRows){
		
		$this->initiateModel('write');
		
		$this->dbHandle->trans_start();
		
		error_log("\n".date("Y-m-d:H:i:s")."    "."Deleting rows from ranking_non_zero_pages table ",3,"/tmp/rankingNonZero.log");
		$query = "DELETE from ranking_non_zero_pages WHERE 1";
		$this->dbHandle->query($query);
		
		// create chunks of insert statements for easy insertion of data
		$final_array = array_chunk( $insertRows, 5000 );
		
		// insert all chunks of insert statements
		foreach( $final_array as $key=>$chunk ){
			error_log("\n".date("Y-m-d:H:i:s")."    "."Inserting Chunk : ".$key,3,"/tmp/rankingNonZero.log");

			$this->dbHandle->insert_batch('ranking_non_zero_pages',$chunk);
		}

		// get the count of rows inserted
		$query = "SELECT count(*) as row_count FROM ranking_non_zero_pages";
		$rowCount = $this->dbHandle->query($query)->row_array();

		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
		
		return $rowCount['row_count'];
	}

	// Function that takes stream, substream, specialization, base course and education type as params and returns sources as output.
	function getRankingPageSourceByParams($params){
		$params = $this->_cleanseHeirarchyParams($params);
		$this->initiateModel('read');
		$this->dbHandle->select('distinct rps.source_id, rps.publisher_name as name',false);
		$this->dbHandle->from('ranking_pages rpn');
		$this->dbHandle->join('ranking_page_data rpdn','rpdn.ranking_page_id = rpn.id','inner');
		$this->dbHandle->join('ranking_page_course_source_data rpcsd','rpcsd.ranking_page_course_id = rpdn.id','inner');
		$this->dbHandle->join('ranking_page_sources rps','rps.source_id = rpcsd.source_id','inner');
		$this->dbHandle->where('rps.status','live');
		foreach($params as $key=>$value){
			$this->dbHandle->where($key,$value);
		}
		$this->dbHandle->order_by('rps.publisher_name');
		$this->dbHandle->limit('1');
		$res = $this->dbHandle->get()->result_array();
		return $res;
	}

	function getRankingPageCourseIdsForSource($rankingPageId, $rankingPageOldSourceId) {
		$this->initiateModel('read');
		$query 	= 	"SELECT course_id FROM ranking_page_data rpdn ".
					"JOIN ranking_page_course_source_data rpcs ".
					"ON rpcs.ranking_page_course_id = rpdn.id ".
					"AND rpcs.source_id = ? ".
					"WHERE rpdn.ranking_page_id = ? ".
					"GROUP BY rpdn.course_id;";
		return $this->dbHandle->query($query, array($rankingPageOldSourceId, $rankingPageId))->result_array();
	}

	function getSourceParamIdBySourceName($sourceName) {
		//not in use
		$this->initiateModel('read');
		$query 	= 	"SELECT rpsp.param_id, rpsp.source_id FROM ranking_page_source_params rpsp ".
					"JOIN ranking_page_sources rps ".
					"ON rps.source_id = rpsp.source_id ".
					"AND rpsp.status = 'live' ".
					"AND rps.status = 'live' ".
					"WHERE rps.publisher_name = ?;";
		return $this->dbHandle->query($query, $sourceName)->result_array();
	}

	function getRankingPageIdByName(array $rankingPageNames) {
		if(empty($rankingPageNames)) {
			return array();
		}

		$this->initiateModel('read');
		$query 	= 	"SELECT id, ranking_page_text name FROM ranking_pages rpn ".
					"WHERE rpn.ranking_page_text ".
					"IN ('".implode("','",$rankingPageNames)."') AND ".
					"rpn.status IN ('live', 'draft') ".
					"GROUP BY id;";
		$data = $this->dbHandle->query($query)->result_array();
		$rankingPageIds = array();
		foreach($data as $rankingPageData) {
			$rankingPageIds[$rankingPageData['name']] = $rankingPageData['id'];
		}
		return $rankingPageIds;
	}

	function getAllValidRankingPageIds(){
		$this->initiateModel('read');
		$query = $this->dbHandle->distinct()->select('id')->where('status','live')->get('ranking_pages');
		return $this->getColumnArray($query->result_array(),'id');
	}

	/*
     * One time script to add ordering for ranking pages
     */
	function updateRankingPageOrderInDb() {
		$ordering = array('2' => 0,
						  '18'=> 1,
						  '93'=> 2,
						  '44'=> 3,
						  '101'=>4,
						  '95'=> 5,
						  '56'=> 6,
						  '99'=> 7,
						  '98'=> 8,
						  '100'=>9,
						  '97'=> 10,
						  '96'=> 11,
						  '94'=> 12);

		$this->initiateModel('write');
		$queryString = "UPDATE ranking_pages SET ranking_order = ? WHERE id = ?";
		foreach ($ordering as $rankingPageId => $order) {
			$this->dbHandle->query($queryString, array($order, $rankingPageId));
		}
	}

	public function addRankingPage($mainTableData,$sources,$metaDetails){
		$checkData = array(
			'ranking_page_text' => $mainTableData['ranking_page_text'],
			'stream_id' => $mainTableData['stream_id'],
			'substream_id' => $mainTableData['substream_id'],
			'specialization_id' => $mainTableData['specialization_id'],
			'base_course_id' => $mainTableData['base_course_id'],
			'education_type' => $mainTableData['education_type'],
			'delivery_method' => $mainTableData['delivery_method'],
			'credential' => $mainTableData['credential']
		);
		if($this->_checkIfRankingPageExists($checkData)){
			return array('status'=>'error','errorMessage'=>'Another ranking page with the same name and parameters already exists.');
		}
		$this->initiateModel('write');
		$this->dbHandle->trans_start();

		$this->dbHandle->insert('ranking_pages',$mainTableData);
		$rankingPageId = $this->dbHandle->insert_id();
		$sourceData = array();
		foreach($sources as $sourceId){
			$sourceData[] = array('ranking_page_id'=>$rankingPageId,'source_id'=>$sourceId,'status'=>'draft');
		}
		$this->dbHandle->insert_batch('ranking_page_source_mapping',$sourceData);
		$metaDetails['ranking_page_id'] = $rankingPageId;
		$metaDetails['counter'] = 1;
		$metaDetails['status'] = 'live';
		$metaDetails['last_updated_time'] = date('Y-m-d H:i:s'); 

		$this->dbHandle->insert('rankingpage_meta_details',$metaDetails);
		$this->dbHandle->trans_complete();
		return array('status'=>'success');
	}

	public function _checkIfRankingPageExists($data,$rankingPageId){
		$this->initiateModel('read');
		$this->dbHandle->select("*");
		foreach($data as $col => $val){
			$this->dbHandle->where($col,$val);
		}
		if(!empty($rankingPageId)){
			$this->dbHandle->where("id != ",$rankingPageId);
		}
		$res = $this->dbHandle->get('ranking_pages')->result_array();
		if(count($res) > 0){
			return true;
		}
		return false;
	}

	public function editRankingPage($rankingPageId,$mainTableData,$metaDetails){
		$checkData = array(
			'ranking_page_text' => $mainTableData['ranking_page_text'],
			'stream_id' => $mainTableData['stream_id'],
			'substream_id' => $mainTableData['substream_id'],
			'specialization_id' => $mainTableData['specialization_id'],
			'base_course_id' => $mainTableData['base_course_id'],
			'education_type' => $mainTableData['education_type'],
			'delivery_method' => $mainTableData['delivery_method'],
			'credential' => $mainTableData['credential']
		);
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		$this->dbHandle->where('id',$rankingPageId);
		$this->dbHandle->update('ranking_pages',$mainTableData);
		$metaDetails['ranking_page_id'] = $rankingPageId;
		$metaDetails['counter'] = 1;
		$metaDetails['status'] = 'live';
		$metaDetails['last_updated_time'] = date('Y-m-d H:i:s');
		$sql = "update rankingpage_meta_details set status='history' where ranking_page_id = ? and city_id=0 and state_id=0 and status='live'"; 
		$this->dbHandle->query($sql,array($rankingPageId));
		$this->dbHandle->insert('rankingpage_meta_details',$metaDetails);
		$this->dbHandle->trans_complete();
		return array('status'=>'success');		
	}

	public function getCourseRankBySource($courseIds = array()){
		if(!(is_array($courseIds) && count($courseIds) > 0)){
			return false;
		}
		$this->initiateModel();
		$this->dbHandle->select('rpdn.course_id,rpdn.ranking_page_id,rpcsd.source_id,rpcsd.rank');
		$this->dbHandle->from('ranking_page_data rpdn');
		$this->dbHandle->join('ranking_page_course_source_data rpcsd','rpcsd.ranking_page_course_id = rpdn.id','inner');
		$this->dbHandle->where_in('rpdn.course_id',$courseIds);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getCoursesRankData($courseIds = array(), $excludeEnggSpecializationRanking, $maxRank){
		if(!(is_array($courseIds) && count($courseIds) > 0)){
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('rpdn.course_id,rpdn.ranking_page_id,rpcsd.source_id,rpcsd.rank,rpn.ranking_page_text,rps.year as source_year,rps.publisher_id as publisher_id ');
		$this->dbHandle->from('ranking_page_data rpdn');
		$this->dbHandle->join('ranking_page_course_source_data rpcsd','rpcsd.ranking_page_course_id = rpdn.id','inner');
		$this->dbHandle->join('ranking_pages rpn',"rpn.id = rpdn.ranking_page_id AND rpn.status =  'live'",'inner');
		$this->dbHandle->join('ranking_page_source_mapping rpsm',"rpsm.source_id = rpcsd.source_id AND rpsm.status = 'live' AND rpsm.ranking_page_id = rpdn.ranking_page_id",'inner');
		$this->dbHandle->join('ranking_page_sources rps',"rps.source_id = rpsm.source_id AND rps.status = 'live'",'inner');
		$this->dbHandle->where_in('rpdn.course_id',$courseIds);
		if($excludeEnggSpecializationRanking)
			$this->dbHandle->where('!(rpn.stream_id =2 AND rpn.specialization_id !=0)');
		if(!empty($maxRank)) {
			$this->dbHandle->where('rpcsd.rank <', $maxRank);
		}
		//exclude shiksha default publisher
		$this->dbHandle->where("rps.publisher_id !=" , RANKING_SHIKSHA_DEFAULT_PUBLISHER);
		$this->dbHandle->order_by('rps.year','desc');
		// $this->dbHandle->order_by('rpdn.updated','desc');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getInstituteRankBySourceDetails($instituteIds,$filters){
		if(!(is_array($instituteIds) && count($instituteIds) > 0)){
			return false;
		}
		$this->initiateModel();
		$this->dbHandle->select('rpdn.institute_id,rpdn.course_id,rpdn.ranking_page_id,rpcsd.source_id,rpcsd.rank');
		$this->dbHandle->from('ranking_pages rpn');
		$this->dbHandle->join('ranking_page_data rpdn','rpn.id = rpdn.ranking_page_id','inner');
		$this->dbHandle->join('ranking_page_course_source_data rpcsd','rpcsd.ranking_page_course_id = rpdn.id','inner');

		if(is_array($filters) && count($filters) > 0){
			foreach($filters as $key=>$value){
				$this->dbHandle->where('rpn.'.$key,$value);
			}
		}
		$this->dbHandle->where('rpn.status','live');
		$this->dbHandle->where_in('rpdn.institute_id',$instituteIds);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getRankingPageToSourceMapping($rankingPageIds){
		if(!(is_array($rankingPageIds) && count($rankingPageIds)>0)){
			return false;
		}
		$this->initiateModel();
		$this->dbHandle->select('ranking_page_id,source_id');
		$this->dbHandle->from('ranking_page_source_mapping');
		$this->dbHandle->where('status','live');
		$this->dbHandle->where_in('ranking_page_id',$rankingPageIds);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getSourceDetails($sourceIds){
		if(!(is_array($sourceIds) && count($sourceIds) > 0)){
			return false;
		}
		$this->initiateModel();
		$this->dbHandle->select('source_id,publisher_name as name');
		$this->dbHandle->from('ranking_page_sources');
		$this->dbHandle->where_in('source_id',$sourceIds);
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getLatestUpdatedSource($sourceIds){
		if(!(is_array($sourceIds) && count($sourceIds))){
			return false;
		}
		$this->initiateModel();
		$this->dbHandle->select('source_id');
		$this->dbHandle->from('ranking_page_sources');
		$this->dbHandle->where_in('source_id',$sourceIds);
		$this->dbHandle->where('status','live');
		$this->dbHandle->order_by('last_modified','desc');
		$this->dbHandle->limit('1');
		$result = $this->dbHandle->get()->result_array();
		return $result;	
	}
	/*
	 * used for creation of similar ranking filter on ranking pages
	 * Note: uses a set of criteria : stream Id, substream Id, base course id, specialization id to find similar (child) ranking pages
	 * 	It also gives the parent for current page (for which we pass the criteria)
	 * 	@params : $criteriaArray(current page params), $parentCriteriaArray(to find parent ranking page)
	 */
	public function getSimilarRankingPagesForRankingPageFilter($criteriaArray = array(), $parentCriteriaArray = array(), $currentRankingPageId = NULL)
	{
		if(count($criteriaArray) == 0 || is_null($currentRankingPageId ))
		{
			return array();
		}
		$returnArray = array("childRP"=>array());
		$this->initiateModel();
		// first all the similar(or children pages)
		$this->dbHandle->select('id,ranking_page_text,stream_id,substream_id,base_course_id,specialization_id');
		$this->dbHandle->from('ranking_pages');
		$this->dbHandle->where('status','live');
		foreach($criteriaArray as $column => $value)
		{
			$this->dbHandle->where($column,$value);
		}
		$this->dbHandle->where('id!=',$currentRankingPageId,false);
		$this->dbHandle->where('specialization_id!=','0',false);
		$result1 = $this->dbHandle->get()->result_array();
		$returnArray["childRP"]=$result1;
		//echo "<br>".$this->dbHandle->last_query();
		//_p($result1);
		if(count($parentCriteriaArray) != 0)
		{
			// now for the parent
			$this->dbHandle->select('id,ranking_page_text,stream_id,substream_id,base_course_id,specialization_id');
			$this->dbHandle->from('ranking_pages');
			$this->dbHandle->where('status','live');
			foreach($parentCriteriaArray as $column => $value)
			{
				$this->dbHandle->where($column,$value);
			}
			$result2 = $this->dbHandle->get()->result_array();
			//echo "<br>".$this->dbHandle->last_query();
			//_p($result2);
			$returnArray["parentRP"]=$result2;
		}
		return $returnArray;	
	}

	public function getCourseHomePageIdCorrespondingToParams($params){
		$this->initiateModel('read');
		$this->dbHandle->select('course_home_id, stream_id, substream_id, base_course_id');
		$this->dbHandle->from('courseHomePages');
		if(!empty($params['stream'])){
			$this->dbHandle->where('stream_id',$params['stream']);
		}else{
			$this->dbHandle->where('base_course_id',$params['popularCourse']);
		}
		$res = $this->dbHandle->get()->result_array();
		if(count($res) == 0){
			return false;
		}
		if(count($res) == 1){
			return $res[0]['course_home_id'];
		}
		// Need to narrow down!
		if(!empty($params['stream'])){					// Stream Options
			if(empty($params['substream'])){
				$params['substream'] = 0;
			}
			$validSubstreamPages = array();
			foreach($res as $row){
				if($row['substream_id'] == $params['substream']){
					$validSubstreamPages[] = $row;
				}
			}
			if(count($validSubstreamPages) == 0){		// None of the substreams matched; return false
				return false;
			}
			if(count($validSubstreamPages) == 1){		// One of the substreams matched; return ID
				return $validSubstreamPages[0]['course_home_id'];
			}
														// More than one substream matched, check at the specialization level
			if(empty($params['specialization'])){
				$params['specialization'] = 0;
			}
			$validSpecializationPages = array();
			foreach($validSubstreamPages as $row){
				if($row['specialization'] == $params['specialization']){
					$validSpecializationPages[] = $row;
				}
			}
			if(count($validSpecializationPages) == 0){	// None of the specializations matched; return false;
				return false;
			}
			if(count($validSpecializationPages) == 1){	// One of the specializations matched; return ID.
				return $$validSpecializationPages[0]['course_home_id'];
			}
		}
		if(!empty($params['popularCourse'])){
			$page = reset($res);
			return $res['course_home_id'];
		}
	}

	public function getArticlesByHeirarchies($heirarchies){
		$this->initiateModel('read');
		$this->dbHandle->select('distinct articleId');
		$this->dbHandle->where_in('entityType',array('hierarchy','primaryHierarchy'));
		$this->dbHandle->where_in('entityId',$heirarchies);
		$this->dbHandle->where('status','live');
		$res = $this->dbHandle->get('articleAttributeMapping FORCE INDEX (entity_Id)')->result_array();
		foreach ($res as $key => $value) {
			$ids[] = $value['articleId'];
		}
		
		if(empty($ids)){
			return array();
		}
		$this->dbHandle->select('blogTitle, url');
		$this->dbHandle->from('blogTable');
		$this->dbHandle->where_in('blogId',$ids);
		$this->dbHandle->where('status','live');
		$this->dbHandle->order_by('creationDate desc');
		$this->dbHandle->limit(6);
		$res = $this->dbHandle->get()->result_array();
		$result = array();
		foreach($res as $row){
			$result[] = array('artcileTitle'=>$row['blogTitle'],'url'=>addingDomainNameToUrl(array('url'=>$row['url'],'domainName'=>SHIKSHA_HOME)));
		}
		return $result;
	}

	public function getArticlesByBaseCourseId($baseCourseId){
		$this->initiateModel('read');
		$this->dbHandle->select('articleId');
		$this->dbHandle->where('entityType','course');
		$this->dbHandle->where('entityId',$baseCourseId);
		$this->dbHandle->where('status','live');
		$res = $this->dbHandle->get('articleAttributeMapping')->result_array();
		$ids = array_map(function($ele){return reset($ele);}, $res);
		if(empty($ids)){
			return array();
		}
		$this->dbHandle->select('blogTitle, url');
		$this->dbHandle->from('blogTable');
		$this->dbHandle->where_in('blogId',$ids);
		$this->dbHandle->where('status','live');
		$this->dbHandle->order_by('creationDate desc');
		$this->dbHandle->limit(6);
		$res = $this->dbHandle->get()->result_array();
		$result = array();
		foreach($res as $row){
			$result[] = array('artcileTitle'=>$row['blogTitle'],'url'=>addingDomainNameToUrl(array('url'=>$row['url'],'domainName'=>SHIKSHA_HOME)));
		}
		return $result;
	}

	function getRankingDetailsByFilter($filters){
		$this->initiateModel('read');
		$this->dbHandle->select('*');
		$this->dbHandle->from(RANKING_PAGE_TABLE);
		foreach($filters as $key=>$value){
			$this->dbHandle->where($key,$value);
		}
		
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getNonZeroRankingPages($filters, $limit = false){
	    $this->initiateModel('read');
	    $this->dbHandle->order_by("result_count","desc");
	    if(isset($filters['stream_id'])){
	    	$this->dbHandle->where('stream_id',$filters['stream_id']);
	    }
	    else{
	    	$this->dbHandle->where('stream_id',0);
	    }

	    if(isset($filters['substream_id'])){
	    	$this->dbHandle->where('substream_id',$filters['substream_id']);
	    }
	    else{
	    	$this->dbHandle->where('substream_id',0);
	    }

	    if(isset($filters['shiksha_specialization_id'])){
	    	$this->dbHandle->where('specialization_id',$filters['shiksha_specialization_id']);
	    }
	    else{
	    	$this->dbHandle->where('specialization_id',0);
	    }

	    if(isset($filters['education_type'])){
	    	$this->dbHandle->where('education_type',$filters['education_type']);
	    }
	    else{
	    	$this->dbHandle->where('education_type',0);
	    }

	    if(isset($filters['delivery_method'])){
	    	$this->dbHandle->where('delivery_method',$filters['delivery_method']);
	    }
	    else{
	    	$this->dbHandle->where('delivery_method',0);
	    }

	    if(isset($filters['credential'])){
	    	$this->dbHandle->where('credential',$filters['credential']);
	    }
	    else{
	    	$this->dbHandle->where('credential',0);
	    }

	    if(isset($filters['base_course_id'])){
	    	$this->dbHandle->where('base_course_id',$filters['base_course_id']);
	    }
	    else{
	    	$this->dbHandle->where('base_course_id',0);
	    }		    

	    if(isset($filters['cityId'])){
			if(is_array($filters['cityId']) && !empty($filters['cityId'])){
				$this->dbHandle->where_in('city_id',$filters['cityId']);
			}
			else{
				$this->dbHandle->where('city_id',$filters['cityId']);
			}
	    }

	    if(isset($filters['stateId'])){
	    	$this->dbHandle->where('state_id',$filters['stateId']);
	    }
	    
	    if(isset($filters['examId'])){
	    	$this->dbHandle->where('exam_id',$filters['examId']);
	    }
	    
	    if($filters['examNeeded'] == 1){
	    	$this->dbHandle->where('exam_id !=',0);
	    }

	    $this->dbHandle->where('status','live');

	    if($limit){
	    	$this->dbHandle->limit($limit);
	    }

	    $data = $this->dbHandle->get('ranking_non_zero_pages')->result_array();

	    return $data;
	}

	//get Exam Group Ids based on Hieraichy (Exam Revamp)
	public function getExamsByHeirarchies($heirarchies){
		if(!is_array($heirarchies) || empty($heirarchies)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('groupId');
		$this->dbHandle->from('examAttributeMapping');
		$this->dbHandle->where_in('entityType',array('primaryHierarchy','hierarchy'));
		$this->dbHandle->where_in('entityId',$heirarchies);
		$this->dbHandle->where('status','live');
		$res = $this->dbHandle->get()->result_array();
		return array_map(function($ele){return reset($ele);}, $res);
	}

	//get Exam Group Ids based on BaseCourse (Exam Revamp)
	public function getExamsByBaseCourse($baseCourseId){
		if(!is_integer($baseCourseId) || $baseCourseId <=0){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('groupId');
		$this->dbHandle->from('examAttributeMapping');
		$this->dbHandle->where('entityType','course');
		$this->dbHandle->where('entityId',$baseCourseId);
		$this->dbHandle->where('status','live');
		$res = $this->dbHandle->get()->result_array();
		return array_map(function($ele){return reset($ele);}, $res);
	}
	/**
	* @param $ids = Exam GrupIds 
	*/

	public function getExamWidgetDataByIds($ids){
		if(!is_array($ids) || empty($ids)){
			return array();
		}
		$this->initiateModel('read');

		$year = array();
	        $sql = "SELECT entityId, groupId FROM examAttributeMapping WHERE groupId IN (?) AND status='live' AND entityType='Year'";
        	$returnData = $this->dbHandle->query($sql,array($ids))->result_array();
		foreach ($returnData as $data){
			$year[$data['groupId']] = $data['entityId'];
		}

		$this->dbHandle->select('epm.id id, eg.groupId, epm.name name, epm.fullName, epm.url,eg.isPrimary');
		$this->dbHandle->from('exampage_main epm');
		$this->dbHandle->join('exampage_groups eg','eg.examId = epm.id');
		$this->dbHandle->join('exampage_master epma','eg.groupId = epma.groupId','inner');
		$this->dbHandle->where_in('eg.groupId',$ids);
		// $this->dbHandle->where('esd.status','live');
		// $this->dbHandle->where('esd.section_name','home');
		$this->dbHandle->where('epm.status','live');
		$this->dbHandle->where('eg.status','live');
		$this->dbHandle->where('epma.status','live');
		$this->dbHandle->limit(16);
		$res = $this->dbHandle->get()->result_array();
		$result = array();
		foreach($res as $row){
			if(array_key_exists($row['id'], $result) && $row['isPrimary'] == 1)
			{
				$result[$row['id']] = array('name'=>$row['name'],'fullName'=>$row['fullName'],'year'=>$year[$row['groupId']],'url'=>addingDomainNameToUrl(array('url'=>$row['url'],'domainName'=>SHIKSHA_HOME)));	
			}
			elseif(!array_key_exists($row['id'], $result))
			{
				$result[$row['id']] = array('name'=>$row['name'],'fullName'=>$row['fullName'],'year'=>$year[$row['groupId']],'url'=>addingDomainNameToUrl(array('url'=>$row['url'].'?course='.$row['groupId'],'domainName'=>SHIKSHA_HOME)));	
			}
		}
		return $result;
	}

	private function _cleanseHeirarchyParams($params){
		$validParams = array(
			'stream_id'=>1,
			'substream_id'=>1,
			'shiksha_specialization_id'=>1,
			'education_type'=>1,
			'delivery_method'=>1,
			'credential'=>1,
			'base_course_id'=>1
		);
		$filteredParams = array_intersect_key($params, $validParams);
		return $filteredParams;
	}

	public function getAllLiveRankingPageIds(){
		$this->initiateModel('read');
		$this->dbHandle->select('id');
		$this->dbHandle->from('ranking_pages');
		$this->dbHandle->where('status','live');
		$res = $this->dbHandle->get()->result_array();
		return array_map(function($ele){return reset($ele);}, $res);
	}

	public function updateInstituteForCourseIds($courseIds,$instituteId){
		if(empty($courseIds) || empty($instituteId)){
			return;
		}
		$this->initiateModel('write');
		$query = "update ranking_page_data set institute_id = ? where course_id in (?)";
		$this->dbHandle->query($query,array($instituteId,$courseIds));
	}

	public function updateInstituteId($oldId,$newId){
		if(empty($oldId) || empty($newId)){
			return;
		}
		$this->initiateModel('write');
		$query = "update ranking_page_data set institute_id = ? where institute_id = ?";
		$this->dbHandle->query($query,array($newId,$oldId));
	}

    public function markCoursesDeletedInRankingPages($courseIds,$userStatus){
        if(!is_array($courseIds)){
            return 'Invalid Input';
        }
        if(count($courseIds)==0){
            return "Success";
        }
        $this->initiateModel('read');
        $this->dbHandle->select('id');
        $this->dbHandle->from(RANKING_PAGE_DATA_TABLE);
        $this->dbHandle->where_in('course_id',$courseIds);
        $result = $this->dbHandle->get()->result_array();
        $rankingPageDataRowIds = array();
        foreach($result as $resultRow){
            $rankingPageDataRowIds[] = $resultRow['id'];
        }
        if(count($rankingPageDataRowIds)>0){
            return $this->deleteCourses($rankingPageDataRowIds,$userStatus);
        }
        return "Success";
    }
    private function deleteCourses($rankingPageDataRowIds,$userStatus){
        if($userStatus[0]['usergroup']!='listingAdmin'){
            return "Access Denied";
        }
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        
        
        $this->dbHandle->where_in('ranking_page_course_id',$rankingPageDataRowIds);
        $this->dbHandle->delete('ranking_page_course_source_data');
        
        
        $this->dbHandle->where_in('id',$rankingPageDataRowIds);
        $this->dbHandle->delete(RANKING_PAGE_DATA_TABLE);
        
        
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
			return "Failed";
        }
        else{
            return "Success";
        }
    }

    public function getRankingPagesByMultipleFilterParams($paramsArr){
    	if(empty($paramsArr)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$columns = array('rp.ranking_page_id','rp.stream_id','rp.substream_id','rp.specialization_id','rp.education_type','rp.base_course_id','rp.delivery_method','rp.state_id','rp.city_id');
    	// if($uniqueBy == 'city'){
    	// 	$columns[] = 'rp.city_id';
    	// }
    	// else{
    	// 	$columns[] = '1 as city_id';
    	// }
    	$columns = implode(',',$columns);
    	$sql = "SELECT distinct $columns from ranking_non_zero_pages rp where rp.status='live' and ";
    	// $sql = "SELECT distinct $columns from ranking_pages rp join ranking_page_data rpd ON rp.status = 'live' and rp.id = rpd.ranking_page_id join shiksha_courses_locations scl ON scl.course_id = rpd.course_id and scl.status = 'live' and scl.is_main = 1 join shiksha_institutes_locations sil ON sil.status = 'live' and sil.listing_location_id = scl.listing_location_id where ";

    	$whereStatements = array();
		foreach ($paramsArr as $key => $params) {
			$where = array();
			if(!empty($params['stream_id'])){
				$where[] = " rp.stream_id = ".$params['stream_id'];
			}
			$where[] = " rp.substream_id = ".$params['substream_id'];
			$where[] = " rp.specialization_id = ".$params['specialization_id'];
			$where[] = " rp.base_course_id = ".$params['base_course_id'];
			$where[] = " rp.education_type = ".$params['education_type'];
			if(!empty($params['delivery_method'])){
				$where[] = " rp.delivery_method = ".$params['delivery_method'];
			}

			if(!empty($params['city_id']) && $params['city_id'] > 1){
				$where[] = " rp.city_id = ".$params['city_id'];
			}
			else if(!empty($params['state_id']) && $params['state_id'] > 1) {
				$where[] = " rp.state_id = ".$params['state_id'];
			}
			$whereStatements[] = implode(' AND ', $where);
		}

		if(empty($whereStatements)) {
			return;
		}
		$finalWhereStatement = implode(' OR ', $whereStatements);

		$sql = $sql.$finalWhereStatement;

    	$result = $this->dbHandle->query($sql)->result_array();
    	return $result;
    }


    function getRankingPageByFilters($filters, $limit = 1) {
    	if(empty($filters)) {
    		return array();
    	}
    	$this->initiateModel('read');
		$streamId         = (!empty($filters['streamId'])) ? $filters['streamId'] : null;
		$substreamId      = (!empty($filters['substreamId']) && $filters['substreamId'][0] != 0) ? $filters['substreamId'] : null;
		$specializationId = (!empty($filters['specializationId']) && $filters['specializationId'][0] != 0) ? $filters['specializationId'] : null;
		$baseCourseId     = (!empty($filters['baseCourseId']) && $filters['baseCourseId'][0] != 0) ? $filters['baseCourseId'] : array(0);
		$credential       = (!empty($filters['credential']) && $filters['credential'][0] != 0) ? $filters['credential'] : null;
		$deliveryMethod   = (!empty($filters['delivery_method']) && $filters['delivery_method'][0] != 0) ? $filters['delivery_method']: null;
        // $education_type   = ($filters['education_type'])?:0;
        
        $this->dbHandle->select('id');
        $this->dbHandle->from('ranking_pages');
        if(!empty($streamId)) {
        	$this->dbHandle->where_in('stream_id',$streamId);
        }
        if(!empty($substreamId)) {
        	$this->dbHandle->where_in('substream_id',$substreamId);
        }
        $this->dbHandle->where_in('specialization_id',$specializationId);
        if($baseCourseId[0] != 0) {
        	$baseCourseId[] = 0;
        }
        $this->dbHandle->where_in('base_course_id',$baseCourseId);
        if(!empty($credential)) {
        	$this->dbHandle->where_in('credential',$credential);
        }
        if(!empty($deliveryMethod)) {
        	$this->dbHandle->where_in('delivery_method',$deliveryMethod);
        }
        $this->dbHandle->where("status",'live');
        $this->dbHandle->limit($limit);
        // $this->dbHandle->where('education_type',$education_type);
        // echo $this->dbHandle->_compile_select(); die;
        return $this->dbHandle->get()->result_array();
    }

    public function getRankingPageDataWithSourceByRankingId($rankingPageId,$sourceIds){
    	$this->initiateModel('read');
    	$sql = "SELECT distinct rpd.institute_id,rpd.course_id,rpcs.source_id,rpd.ranking_page_id,rpcs.rank from ranking_page_course_source_data rpcs join ranking_page_data rpd ON rpd.id = rpcs.ranking_page_course_id join ranking_page_sources rps on rps.source_id = rpcs.source_id and rps.status='live' where rpd.ranking_page_id = ? and rpcs.source_id in (?)";
    	$returnData = $this->dbHandle->query($sql,array($rankingPageId,$sourceIds))->result_array();
    	return $returnData;
    }

    function getFilters($criteria = array(), $filterType = '') {
    	if(empty($criteria) || $filterType == '') {
    		return array();
    	}
		$this->initiateModel('read');

		$filterType;
		$filters = array('city' => 'city_id', 'state' => 'state_id', 'exam' => 'exam_id', 'specialization' => 'ranking_page_id', 'publisher' => 'publisher_id', 'stream' => 'stream_id', 'substream' => 'substream_id', 'base_course' => 'base_course_id');

		$selectColumn = $filters[$filterType];
		
		//to get all filters
		if($filterType == 'city') {
			unset($filters['state']);
		}
		unset($filters[$filterType]);
		$this->dbHandle->select("DISTINCT ".$selectColumn.", result_count");
        $this->dbHandle->from('ranking_non_zero_pages');

        //filtering all criteria except current filter
        foreach ($filters as $filterName => $whereColumn) {
        	// _p($criteria[$whereColumn]);
        	if($criteria[$whereColumn] >= 0 && $filterName != 'publisher') {
        		$this->dbHandle->where($whereColumn, $criteria[$whereColumn]);
        	}
        }
        
        $this->dbHandle->where($selectColumn." > 0", false, null);
        $this->dbHandle->where('status', 'live');
        if($filterType == 'publisher') {
        	$this->dbHandle->where('publisher_id != '.RANKING_SHIKSHA_DEFAULT_PUBLISHER);
        	$this->dbHandle->order_by('result_count', 'desc');
        }
		$dbResult =  $this->dbHandle->get()->result_array();
		
		$returnArray = array();
		foreach ($dbResult as $value) {
			$returnArray[$value[$selectColumn]] = $value;
		}
		
		return $returnArray;
    }

    function getParentRankingPage($rankingPageId, $params) {
    	//we will use first id as parent id
    	if(!empty($params['base_course_id']) || empty($params['substream_id'])) {
    		$this->dbHandle->where('base_course_id', $params['base_course_id']);
    		$this->dbHandle->order_by('ranking_page_id', 'asc');
    	}
    	else {
    		$this->dbHandle->where('specialization_id', 0);
    	}
    	if(!empty($params['substream_id'])) {
    		$this->dbHandle->where('substream_id', $params['substream_id']);
    	}
    	if(!empty($params['stream_id'])) {
    		$this->dbHandle->where('stream_id', $params['stream_id']);
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select("DISTINCT ranking_page_id");
    	$this->dbHandle->where('status', 'live');
        $this->dbHandle->from('ranking_non_zero_pages');
        $this->dbHandle->limit(1);
        $dbResult =  $this->dbHandle->get()->result_array();
        $parentRankingPageId = reset($dbResult)['ranking_page_id'];
        
        $isLocationMapped = true;
        if(!empty($parentRankingPageId) && (!empty($params['state_id']) || !empty($params['city_id'])) ) {
	    	$this->dbHandle->where('status', 'live');
	    	if(!empty($params['stateId'])) {
        		$this->dbHandle->select("DISTINCT state_id");
	    		$this->dbHandle->where('state_id', $params['state_id']);
	    	}
	    	else {
        		$this->dbHandle->select("DISTINCT city_id");
	    		$this->dbHandle->where('city_id', $params['city_id']);
	    	}
	    	$this->dbHandle->where('ranking_page_id', $parentRankingPageId);
	        $this->dbHandle->from('ranking_non_zero_pages');
	        $dbResult =  $this->dbHandle->get()->result_array();
	       	if(empty($dbResult)) {
	       		$isLocationMapped = false;
	       	}
        }
        $returnArray = array('isLocationMapped' => $isLocationMapped, 'rankingPageId' => ($parentRankingPageId)?:'');
        return $returnArray;
    }

    public function getRecentSourcesForPublisher($rankingPageId,$publisherId){
    	$this->initiateModel('read');
    	// $sql = "SELECT distinct rpsm.source_id, rps.publisher_id, rps.publisher_name as name, rps.year from ranking_page_source_mapping rpsm join ranking_page_sources rps on rps.source_id = rpsm.source_id and rps.status = 'live' and rpsm.status = 'live' and rpsm.ranking_page_id = ? and rps.publisher_id = ? join ranking_page_course_source_data rpcsd on rpcsd.source_id = rpsm.source_id order by rps.year desc limit 2";
    	$sql = "SELECT distinct rpsm.source_id, rps.publisher_id, rps.publisher_name as name, rps.year from ranking_page_source_mapping rpsm join ranking_page_data rpd on rpd.ranking_page_id = rpsm.ranking_page_id join ranking_page_sources rps on rps.source_id = rpsm.source_id and rps.status = 'live' and rpsm.status = 'live' and rpsm.ranking_page_id = ? and rps.publisher_id = ? join ranking_page_course_source_data rpcsd on rpcsd.source_id = rpsm.source_id and rpcsd.ranking_page_course_id = rpd.id order by rps.year desc limit 2";
    	$returnData = $this->dbHandle->query($sql,array($rankingPageId,$publisherId))->result_array();
    	$returnArray = array();
    	// get latest year and latest year -1
    	if(count($returnData) > 1){
    		$years = $this->getColumnArray($returnData,'year');
    		if($years[1] != ($years[0] -1)){
    			unset($returnData[1]);
    		}
    	}
    	foreach ($returnData as $key => $value) {
    		$returnArray[$value['source_id']] = $value;
    	}
    	return $returnArray;
    }

    public function getAllPublishersMappedToRankingPages(){
    	$this->initiateModel('read');
    	$sql = "SELECT rpsm.ranking_page_id,rps.publisher_id from ranking_page_source_mapping rpsm join ranking_page_sources rps on rps.source_id = rpsm.source_id and rps.status='live' and rpsm.status='live'";
    	$data = $this->dbHandle->query($sql)->result_array();
    	$returnData = array();
    	foreach ($data as $row) {
    		$returnData[$row['ranking_page_id']][] = $row['publisher_id'];
    	}
    	foreach ($returnData as $key => $value) {
    		$returnData[$key] = array_unique($value);
    	}
    	return $returnData;
    }

    public function getRankingPagePublishersWithData($rankingPageId, $rankingPageStatus) {
    	$this->initiateModel('read');
    	$sql = "select distinct publisher_id, publisher_name as name ".
    			"from ranking_page_course_source_data rpcs ".
				"inner join ranking_page_source_mapping rpm on rpm.source_id = rpcs.source_id and rpm.status IN (?) ".
				"inner join ranking_page_sources rps on rps.source_id = rpcs.source_id and rps.status = 'live' ".
    			"inner join ranking_page_data rd on rd.id = rpcs.ranking_page_course_id and rd.ranking_page_id = ? ".
				"where rpm.ranking_page_id = ?";
    	$returnData = $this->dbHandle->query($sql, array($rankingPageStatus, $rankingPageId, $rankingPageId))->result_array();
    	
    	return $returnData;
    }

    public function getRankingPageMetaData($rankingPageId,$cityIds,$stateIds){
    	$this->initiateModel('read');
    	$sql = "select * from rankingpage_meta_details where ranking_page_id = ? and city_id in (?) and state_id in (?) and status = 'live' ";
    	$returnData = $this->dbHandle->query($sql, array($rankingPageId, $cityIds,$stateIds))->result_array();
    	return $returnData;

    }

    public function getRankingSourcesForCourses($courseList){
    	if(empty($courseList) || !is_array($courseList)){
    		return array();
    	}
    	$this->initiateModel('read');
		$this->dbHandle->select('rpd.id as ranking_page_course_id, rpd.ranking_page_id, rpd.course_id, rpcsd.rank, rps.publisher_id, rps.source_id, rps.publisher_name, rps.year');
		$this->dbHandle->from('ranking_page_data rpd');
		$this->dbHandle->join('ranking_page_course_source_data rpcsd', 'rpcsd.ranking_page_course_id = rpd.id');
		$this->dbHandle->join('ranking_page_source_mapping rpsm', 'rpsm.ranking_page_id = rpd.ranking_page_id and rpsm.source_id = rpcsd.source_id');
		$this->dbHandle->join('ranking_page_sources rps', 'rps.source_id = rpsm.source_id');
		$this->dbHandle->where('rpsm.status', 'live');
		$this->dbHandle->where('rps.status', 'live');
		$this->dbHandle->where_in('rpd.course_id', $courseList);
    	$result = $this->dbHandle->get()->result_array();
    	//echo $this->dbHandle->last_query();
    	$finalResult1 = $finalResult2 = array();
    	foreach ($result as $key => $value) {
    		$finalResult1[$value['course_id']][$value['ranking_page_id']][$value['publisher_id']][] = array(
    			'ranking_page_id'=>$value['ranking_page_id'], 
    			'source_id'=>$value['source_id'], 
    			'publisher_name'=>$value['publisher_name'], 
    			'publisher_id'=>$value['publisher_id'], 
    			'year'=>$value['year'], 
    			'rank'=>$value['rank']
    		);
    		$finalResult2[$value['course_id']][$value['ranking_page_course_id']] = $value['ranking_page_id'];
    	}
    	return array('source_id'=>$finalResult1, 'ranking_page_course_id'=>$finalResult2);
    }
    public function getLatestSourceForRankingPage($rankingPageId){
    	if(empty($rankingPageId)){
    		return false;
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('distinct rpd.ranking_page_id, rpcsd.source_id, rps.publisher_id, rps.publisher_name, rps.year');
    	$this->dbHandle->from('ranking_pages rp');
    	$this->dbHandle->join('ranking_page_data rpd', 'rp.id=rpd.ranking_page_id');
    	$this->dbHandle->join('ranking_page_course_source_data rpcsd', 'rpd.id=rpcsd.ranking_page_course_id');
    	$this->dbHandle->join('ranking_page_sources rps', 'rps.source_id = rpcsd.source_id');
    	$this->dbHandle->where('rp.status', 'live');
    	$this->dbHandle->where('rps.status', 'live');
    	$this->dbHandle->where('rp.id', $rankingPageId);
    	$this->dbHandle->order_by('rps.year', 'desc');
    	$result = $this->dbHandle->get()->result_array();
    	//echo $this->dbHandle->last_query();
    	$latestPubData = array();
    	foreach ($result as $value) {
    		if(!isset($latestPubData[$value['publisher_id']])){
    			$latestPubData[$value['publisher_id']] = $value['source_id'];
    		}
    	}
    	return $latestPubData;
    }
    public function getRankingSourcesForCourses_old($courseList){
    	if(empty($courseList) || !is_array($courseList)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('rp.id as ranking_page_id, rpd.id as ranking_page_course_id, rpsm.source_id, rpd.course_id');
    	$this->dbHandle->from('ranking_pages rp');
    	$this->dbHandle->join('ranking_page_data rpd', 'rp.id = rpd.ranking_page_id');
    	$this->dbHandle->join('ranking_page_source_mapping rpsm', 'rpd.ranking_page_id = rpsm.ranking_page_id');
    	$this->dbHandle->where('rp.status', 'live');
    	$this->dbHandle->where('rpsm.status', 'live');
    	$this->dbHandle->where_in('rpd.course_id', $courseList);
    	$result = $this->dbHandle->get()->result_array();
    	//echo $this->dbHandle->last_query();
    	$finalResult1 = $finalResult2 = array();
    	foreach ($result as $key => $value) {
    		$finalResult1[$value['course_id']][$value['ranking_page_id']][] = $value['source_id'];
    		$finalResult2[$value['course_id']][$value['ranking_page_course_id']] = $value['ranking_page_id'];
    	}
    	return array('source_id'=>$finalResult1, 'ranking_page_course_id'=>$finalResult2);
    }

    public function getRankingSourcesInfo($sourceIds){
    	if(empty($sourceIds) || !is_array($sourceIds)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('source_id, publisher_name, year');
    	$this->dbHandle->from('ranking_page_sources');
    	$this->dbHandle->where('status', 'live');
    	$this->dbHandle->where_in('source_id', $sourceIds);
    	$this->dbHandle->order_by('publisher_name', 'asc');
    	$this->dbHandle->order_by('year', 'desc');
    	return $this->dbHandle->get()->result_array();
    }

    public function getSourceWiseRanks($rankingPageCourseId, $sourceIds){
    	if(empty($rankingPageCourseId) || !is_array($rankingPageCourseId)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('ranking_page_course_id, source_id, rank');
    	$this->dbHandle->from('ranking_page_course_source_data');
    	$this->dbHandle->where_in('ranking_page_course_id', $rankingPageCourseId);
    	if(!empty($sourceIds) && is_array($sourceIds)){
    		$this->dbHandle->where_in('source_id', $sourceIds);
    	}
    	$result = $this->dbHandle->get()->result_array();
    	//echo $this->dbHandle->last_query();
    	return $result;
    }

    public function getDistinctCoursesBySource($rankingPageId, $sourceIds){
    	if(empty($rankingPageId)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('distinct course_id');
    	$this->dbHandle->from('ranking_page_data rpd');
    	$this->dbHandle->join('ranking_page_course_source_data rpcsd', 'rpd.id = rpcsd.ranking_page_course_id');
    	$this->dbHandle->where('rpd.ranking_page_id', $rankingPageId);
    	if(!empty($sourceIds) && is_array($sourceIds)){
    		$this->dbHandle->where_in('rpcsd.source_id', $sourceIds);
    	}
    	$result = $this->dbHandle->get()->result_array();
    	if(!empty($result)) {
    		$courseIds = $this->getColumnArray($result,'course_id');
    	} 
    	// _p($courseIds); die;
    	//echo $this->dbHandle->last_query();
    	return $courseIds;
    }

    public function getAllCoursesOnRankingPage($rankingPageId){
    	if(empty($rankingPageId)){
    		return array();
    	}
    	$courseArr = array();
    	$this->initiateModel('read');
    	$this->dbHandle->select('course_id');
    	$this->dbHandle->from('ranking_page_data');
    	$this->dbHandle->where('ranking_page_id', $rankingPageId);
    	$dbData = $this->dbHandle->get()->result_array();
    	foreach ($dbData as $value) {
    		$courseArr[] = $value['course_id'];
    	}
    	return $courseArr;
    }

    public function indexCourseForRanking($courseIds, $comment = ''){
    	if(empty($courseIds)){
    		return false;
    	}
    	if(empty($comment)){
    		$comment = 'Update from Ranking CMS';
    	}
    	$this->initiateModel('write');
    	$arrayToBeInserted = array();
    	foreach ($courseIds as $courseId) {
    		$arrayToBeInserted[] = array(
									'operation' => 'index',
									'listing_type' => 'course',
									'listing_id' => $courseId,
									'status' => 'pending',
									'comment' => $comment
								);
    	}
    	$this->dbHandle->insert_batch('indexlog', $arrayToBeInserted);
    	return true;
    }

    public function getAllSourcesForPublisher($publisherId){
    	if(empty($publisherId)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('source_id');
    	$this->dbHandle->from('ranking_page_sources');
    	$this->dbHandle->where('publisher_id', $publisherId);
    	$this->dbHandle->where_in('status', array('live', 'draft'));
    	$result = $this->dbHandle->get()->result_array();
    	$newResult = array();
    	foreach ($result as $value) {
    		$newResult[] = $value['source_id'];
    	}
    	return $newResult;
    }

    public function getAllCoursesForSources($sourceIds){
    	if(empty($sourceIds) || !is_array($sourceIds)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('rpd.course_id');
    	$this->dbHandle->from('ranking_pages rp');
    	$this->dbHandle->join('ranking_page_data rpd', 'rp.id = rpd.ranking_page_id');
    	$this->dbHandle->join('ranking_page_source_mapping rpsm', 'rpd.ranking_page_id = rpsm.ranking_page_id');
    	$this->dbHandle->where_in('rp.status', array('live', 'draft'));
    	$this->dbHandle->where_in('rpsm.status', array('live', 'draft'));
    	$this->dbHandle->where_in('rpsm.source_id', $sourceIds);
    	$result = $this->dbHandle->get()->result_array();
    	$newResult = array();
    	foreach ($result as $value) {
    		$newResult[] = $value['course_id'];
    	}
    	return $newResult;
    }

    public function getRankingPageCourseIds($instIdArr){
    	if(empty($instIdArr) || !is_array($instIdArr)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('rpd.id, institute_id, course_id');
    	$this->dbHandle->from('ranking_pages rp');
    	$this->dbHandle->join('ranking_page_data rpd', 'rp.id=rpd.ranking_page_id');
    	$this->dbHandle->where('rp.status', 'live');
    	$this->dbHandle->where_in('institute_id', $instIdArr);
    	$result = $this->dbHandle->get()->result_array();
    	$finalResult = array();
    	foreach ($result as $key => $value) {
    		$finalResult[$value['id']] = $value;
    	}
    	//echo $this->dbHandle->last_query();die;
    	return $finalResult;
    }

    public function putRankingPageCourseSourceRank($rankingPageCourseSourceRankData){
    	if(empty($rankingPageCourseSourceRankData) || !is_array($rankingPageCourseSourceRankData)){
    		return false;
    	}
    	$this->initiateModel('write');
    	$this->dbHandle->insert_batch('ranking_page_course_source_data', $rankingPageCourseSourceRankData);
    	return true;
    }

    public function uploadRankingPageInstituteData($data){
    	$this->initiateModel('write');
    	$this->dbHandle->trans_start();
    	$sql = "Update ranking_bulk_upload SET status = 'history' where ranking_page_id = ? and source  = ? and status ='draft' ";

		$this->dbHandle->query($sql, array($data[0]['ranking_page_id'], $data[0]['source']));
		
		$this->dbHandle->insert_batch('ranking_bulk_upload', $data);
		$this->updateRankingPageLastUpdatesTime($data[0]['ranking_page_id'], false);
		$return = true;
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			$return = false;
	  	}
	  	return $return;
    }


    public function getUnprocessedRankingPages(){
    	$this->initiateModel('write');
    	$this->dbHandle->trans_start();
    	$sql = "SELECT * from ranking_bulk_upload where status ='draft'";
    	$result = $this->dbHandle->query($sql)->result_array();
    	$rankingpageIds = array();
    	$returnData=array();
    	foreach ($result as $key => $value) {
    		$rankingPageId = $value['ranking_page_id'];
    		$source = $value['source'];
    		$instituteInfo[ $rankingPageId][$source] [$value['instituteId']]=$value['rank'];
    		$returnData[ $rankingPageId ] ['sourceWiseData'] [ $source ]= $instituteInfo[$rankingPageId][$source];
    	}

    	return $returnData;
    }
    public function markDataAsProcessed($rankingPageId,$source){

    	$sql = "Update ranking_bulk_upload SET status='processed' where ranking_page_id = ? and source = ? and status = 'draft' ";

        $this->dbHandle->query($sql,array($rankingPageId,$source));
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
				$return = false;
			throw new Exception('Transaction Failed');
	  	}
    }
     public function getDetailsforMappedInstitutes($rankingPageId, $allInstitutes){

   	 	$sql="Select id, institute_id from ranking_page_data where ranking_page_id = ? and institute_id IN(?) GROUP BY institute_id";
   	 	$returnData = $this->dbHandle->query($sql,array($rankingPageId,$allInstitutes))->result_array();
	   	return $returnData;
    }

  	public function updateRankDetails($rankingPageId,$data){
    	$this->dbHandle->insert_batch('ranking_page_course_source_data', $data);
  	}

    public function getCourseCriteriaforRankingPage($ranking_page_id){
   	 	$sql="Select DISTINCT stream_id,substream_id,specialization_id,base_course_id from
   	 			ranking_pages where id = ?";
   	 	$returnData = $this->dbHandle->query($sql,array($ranking_page_id))->row_array();
   	 	return $returnData;

    }

    public function deleteExistingRankDataforSource($rankingPageId,$source){
   	 	$sql="Select rpcsd.id from ranking_page_course_source_data rpcsd JOIN  ranking_page_data rpd ON (rpd.id = rpcsd.ranking_page_course_id and rpd.ranking_page_id = ? and rpcsd.source_id = ?)";

   		$returnData = $this->dbHandle->query($sql,array($rankingPageId,$source))->result_array();
   		$ids=array();
   		foreach ($returnData as $key => $value) {
   			$ids[]=$value["id"];
   		}
   		if(!empty($ids)){
	   		$this->dbHandle->where_in( 'id', $ids);
			$this->dbHandle->delete('ranking_page_course_source_data');
		}

    }
    public function saveCourseDetailsForInstitutes($data){
    	$this->dbHandle->insert_batch('ranking_page_data', $data);
    }


    public function cleanDataforInstituteAndCourse($rankingPageId,$data){
     	$sql = "UPDATE ranking_page_data SET institute_id = CASE";
    	foreach ($data as $instituteId => $courseData) {
    		$sql = $sql." WHEN ranking_page_id = ".$rankingPageId." and course_id = ".$courseData['course_id']." THEN ".$instituteId;
    	 	}
    	$sql = $sql." ELSE institute_id END";
    	$this->dbHandle->query($sql);
     }
}
