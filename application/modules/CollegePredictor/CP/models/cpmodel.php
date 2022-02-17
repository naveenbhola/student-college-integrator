<?php
class CPModel extends MY_Model
{ 	/*

   Copyright 2014 Info Edge India Ltd

   $Author: Pranjul

   $Id: CPModel.php

 */
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
    private $coursesBySpecialization = array();
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
    
	function checkIfCourseIdInListing($res){
		$this->initiateModel();
		$shikshaCourseIds = '';
		foreach($res as $k=>$v){
			if($v['shikshaCourseId']>0 && $v['shikshaCourseId']!=''){
				$shikshaCourseIds .= "'".$v['shikshaCourseId']."',";
			}
		} 
		$shikshaCourseIds = rtrim($shikshaCourseIds,',');
		if(trim($shikshaCourseIds)==''){
			return $res;
		}
		$sql = "SELECT listing_type_id FROM listings_main WHERE listing_type_Id IN (?) AND listing_type = 'course' AND STATUS = 'live' ORDER BY listing_type_id ASC";
		$shikshaCourseArr = explode(',',$shikshaCourseIds);
		$query = $this->dbHandle->query($sql, array($shikshaCourseArr));
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
				$res1[$value['listing_type_id']] = 1;
			}
		}
		foreach($res as $k=>$v){
			if($v['shikshaCourseId']>0 && $v['shikshaCourseId']!=''){
				if(!empty($res1[$v['shikshaCourseId']])){
					$res2[] = $v;
				}
			}else{
					$res2[] = $v;
			}
		}
		return $res2;
	}

	function getDataForMAHCETType($data){
		$this->initiateModel();

		$selectCountClause = '';
		// if($data['examName'] == 'DU' || strtolower($data['examName']) == 'jee-mains'){
			$selectCountClause = " SQL_CALC_FOUND_ROWS ";
		// }

		$sql = "SELECT  distinct $selectCountClause cpc.id, cpcrrm.branchId, cpcrrm.rankType from CollegePredictor_Colleges cpc join CollegePredictor_LocationTable cplt on cplt.id = cpc.locId AND cpc.status='live' AND cplt.status='live' join CollegePredictor_BranchInformation cpbi on cpc.id = cpbi.clmId AND cpbi.status='live' join CollegePredictor_CategoryRoundRankMapping cpcrrm on cpcrrm.branchId = cpbi.branchId AND cpcrrm.closingRank != 0 AND cpcrrm.status = 'live' ";

		$params = array();$whereStatements = array();
		if(!empty($data['categoryName'])){
			$whereStatements[] = 'cpcrrm.categoryName = ?';
			$params[] = $data['categoryName'];
		}
		if(!empty($data['examName'])){
			$whereStatements[] = 'cpc.exams = ?';
			$params[] = $data['examName'];
		}
		$whereStatements[] = 'cpcrrm.closingRank != 0';
		if(!empty($data['rank'])){
			if(!empty($data['invertLogic']) && $data['invertLogic']==1){
				$whereStatements[] = 'cpcrrm.closingRank <= ?';
			}
			else{
				$whereStatements[] = 'cpcrrm.closingRank >= ?';
			}
			$params[] = $data['rank'];
		}

		if(($data['rankType']=='Home' || $data['rankType']=='StateLevel' || $data['rankType']=='HomeUniversity' || $data['rankType']=='HyderabadKarnatakaQuota') && strtolower($data['examName']) != 'mahcet' && strtolower($data['examName']) != 'jee-mains'){
			$whereStatements[] = "cplt.stateName = ? ";
			$params[] = $data['stateName'];
		}

		if($data['rankType'] == 'StateLevel'){
			$whereStatements[] = "cpcrrm.rankType in (?)";
			$params[] = array($data['rankType'], 'HomeUniversity');
		}else if($data['rankType'] == 'Other' && $data['examName'] == 'MHCET'){

			$whereStatements[] = "cpcrrm.rankType in (?)";
			$params[] = array($data['rankType'], 'OtherThanHome');

		}else if($data['rankType'] == 'Other' && $data['examName'] == 'KCET'){
			$whereStatements[] = "cpcrrm.rankType = ?";
			$params[] = 'KCETGeneral';
		}
		else if(strtolower($data['examName']) == 'mahcet'){
			if(!empty($data['cityId']) && $data['cityId'] > 0){
				$whereStatements[] = "((cplt.id = ? and cpcrrm.rankType = 'Home') OR (cplt.id != ? and cpcrrm.rankType = 'OtherThanHome'))";
				$params[] = $data['cityId'];$params[] = $data['cityId'];
			}
			else{
				$whereStatements[] = "cpcrrm.rankType = 'OtherThanHome'";
			}
		}
		else if(strtolower($data['examName']) == 'jee-mains' && !empty($data['stateName'])){
			$whereStatements[] = "CASE cpcrrm.rankType WHEN 'Home' THEN cplt.stateName = ? ELSE 1 = 1 END";
			$params[] = $data['stateName'];
		}
		else{
			$whereStatements[] = "cpcrrm.rankType = ?";
			$params[] = $data['rankType'];
		}

		if($data['defaultView']) {
			$whereStatements[] = "cpbi.shikshaCourseId > 0";
		}

		$specializationCourses = $this->getCoursesBySpecialization($data['specializationId']);
		
		if(!empty($data['specializationId']) && !empty($data['courseIds'])){ // specialization filter child

			// intersect
			$intersection = array();
			$specializationCourses = array_flip($specializationCourses);
			foreach ($data['courseIds'] as $value) {
				if(array_key_exists($value, $specializationCourses)){
					$intersection[] = $value;
				}
			}
			if(!empty($intersection)){ // safe check
				$whereStatements[] = "cpbi.shikshaCourseId in (?)";
				$params[] = $intersection;
			}
		}
		elseif(!empty($data['specializationId']) && empty($data['courseIds'])){ // specialization filter parent
			if(!empty($specializationCourses)){ // safe check
				$whereStatements[] = "cpbi.shikshaCourseId in (?)";
				$params[] = $specializationCourses;
			}
		}
		elseif(empty($data['specializationId']) && !empty($data['courseIds'])){ // no specialization filter and child
			$whereStatements[] = "cpbi.shikshaCourseId in (?)";
			$params[] = $data['courseIds'];
		}

		if(!empty($data['cutoffRange'])){
			$whereStatements[] = "cpcrrm.closingRank >= ? AND cpcrrm.closingRank < ?";
			$params[] = (int)$data['cutoffRange']['start'];
			$params[] = (int)$data['cutoffRange']['end'];

		}

		//applying specialization filter
		if(!empty($data['branchFilter'])){
			$whereStatements[] = " cpbi.branchName IN (?)";
			$params[] = $data['branchFilter'];
		}

		//applying college filter
		if(!empty($data['collegeFilter'])){
			$whereStatements[] = " cpc.id IN (?)";
			$params[] = $data['collegeFilter'];
		}

		// applying state filter
		if(!empty($data['locationFilter']['state'])){
			$whereStatements[] = " cplt.stateName IN (?)";
			$params[] = $data['locationFilter']['state'];
		}

		// applying city filter
		if(!empty($data['locationFilter']['city'])){
			$whereStatements[] = " cplt.cityName IN (?)";
			$params[] = $data['locationFilter']['city'];
		}

		if(!empty($whereStatements)){
			$sql .= 'where '.implode($whereStatements,' AND ');
		}
		if(!empty($data['invertLogic']) && $data['invertLogic']==1){
			$sql .= ' order by cpcrrm.closingRank desc';
		}
		else{
			$sql .= ' order by cpcrrm.closingRank asc';
		}

		if(empty($data['offset'])) {
			if(isMobileRequest()) {
				$data['offset'] = 10;
			}
			else {
				$data['offset'] = 15;
			}
			$data['start'] = 0;
		}
		// if($data['examName'] == 'DU' || strtolower($data['examName']) == 'jee-mains'){
			$sql .=' limit ?, ?'; 
			$params[]  = (int) $data['start'];
			$params[]  = (int) $data['offset'];
		// }
		$query = $this->dbHandle->query($sql,$params)->result_array();
		
		// echo $this->dbHandle->last_query(); 
		// _p($query); die;	
		// if($data['examName'] == 'DU' || strtolower($data['examName']) == 'jee-mains'){
			$countOfbranch = $this->dbHandle->query("SELECT FOUND_ROWS() AS foundRows")->row_array();
			$countOfbranch = $countOfbranch['foundRows'];
		// }
		$branchToMinRoundMapping = array();
		$collegeIds = array();
		foreach ($query as $row) {
			$branchToMinRoundMapping[$row['branchId']] = $row['branchId'];
			$branchIdsWithRankType[$row['branchId'].'_'.$row['rankType']] = 1;
			$branchRankTypeMapping[$row['branchId']][$row['rankType']] = 1;
		}
		$branchIds = array_keys($branchToMinRoundMapping);

		if(empty($branchIds)){
			return array();
		}

		$sql = "SELECT cpc.id as instituteId, cpc.collegeName, if(cpcrrm.closingRank = 0, 1, 2 ) as sortOrder, cpc.locId, cpc.creationDate, cpc.collegeGroupName, cpc.exams, cpc.status, cpbi.branchId, cpbi.branchAcronym, cpbi.branchName, cpbi.shikshaCourseId, cpbi.courseCode, cpbi.instCourseLink, cpbi.instLink, cpcrrm.rankType, cpcrrm.roundNum as numberOfRound, cpcrrm.closingRank, cpcrrm.categoryName, cplt.cityName, cplt.stateName, cpbi.remarks from CollegePredictor_Colleges cpc join CollegePredictor_LocationTable cplt on cplt.id = cpc.locId and cpc.status='live' and cplt.status='live' join CollegePredictor_BranchInformation cpbi on cpc.id = cpbi.clmId and cpbi.status='live' join CollegePredictor_CategoryRoundRankMapping cpcrrm on cpcrrm.branchId = cpbi.branchId and cpcrrm.status = 'live' ";

		$params = array();$whereStatements = array();
		if(!empty($data['categoryName'])){
			$whereStatements[] = 'cpcrrm.categoryName = ?';
			$params[] = $data['categoryName'];
		}
		if(!empty($data['examName'])){
			$whereStatements[] = 'cpc.exams = ?';
			$params[] = $data['examName'];
		}
		// $whereStatements[] = 'cpcrrm.closingRank != 0';
		if(!empty($branchIds)){
			$whereStatements[] = 'cpcrrm.branchId in (?)';
			$params[] = $branchIds;
		}
		
		//removing rank type checks for mahcet & jee-mains
		if(strtolower($data['examName']) != 'mahcet' && strtolower($data['examName']) != 'jee-mains') {
			$whereStatements[] = 'cpcrrm.rankType = ?';
			$params[] = $data['rankType'];
		}
		if(!empty($whereStatements)){
			$sql .= 'where '.implode($whereStatements,' AND ');
		}
		$sql .= ' order by sortOrder desc, cpcrrm.closingRank asc ';

		
		$query = $this->dbHandle->query($sql,$params)->result_array();

		$query = array_values($query);
	
		if(!empty($query)){
			$query = $this->checkIfCourseIdInListing($query);
		}
		$branchRoundInfo = array();
		foreach ($query as $key => $row) {

			if(strtolower($data['examName']) == 'jee-mains'){ 
				if($branchRankTypeMapping[$row['branchId']]['Other'] && $branchRankTypeMapping[$row['branchId']]['Home'] && $row['rankType'] == 'Other') {
						continue;
				}
			}
			$branchRoundInfo[$row['branchId'].'_'.$row['rankType']][$row['numberOfRound']] = $row;
		}
		$tempArray = array();
		foreach ($branchIdsWithRankType as $branchIdWithRankName => $value) {
			if(!empty($branchRoundInfo[$branchIdWithRankName])) {
				$tempArray[$branchIdWithRankName] = $branchRoundInfo[$branchIdWithRankName];
			}
		}
		$returnArray['collegeData'] =  $tempArray;
		// if($data['examName'] == 'DU' || strtolower($data['examName']) == 'jee-mains'){
			$returnArray['countOfbranch'] = $countOfbranch;	
			$returnArray['examName'] = 	$data['examName'];
		// }
		return $returnArray;
	}

	function getDataForRankTab($data){
		$this->initiateModel();
		$stateQuery    = '';
		$stateSubQuery = '';
		if($data['rankType']=='Home' || $data['rankType']=='StateLevel' || $data['rankType']=='HomeUniversity' || $data['rankType']=='HyderabadKarnatakaQuota'){
			//$stateQuery = 'cplt.stateName = "'.$data['stateName'].'" and ';
			$stateSubQuery = ' and stateName = cplt.stateName';
		}

		$allRounds = array(1,2,3,4,5,6,7);
		$this->dbHandle->select("(select closingRank  from CollegePredictor_CategoryRoundRankMapping  where branchId=cpbi.branchId and categoryName=cpcrrm.categoryName and closingRank=cpcrrm.closingRank and roundNum=cpcrrm.roundNum-1 $stateSubQuery and rankType = cpcrrm.rankType and status = 'live' and cpbi.status='live' and cpcrrm.status='live') previousRoundClosingRank, cpc.id as instituteId, cpc.collegeName, cpc.locId, cpc.creationDate, cpc.collegeGroupName, cpc.exams, cpc.status, cpbi.branchId, cpbi.branchAcronym, cpbi.branchName, cpbi.shikshaCourseId, cpbi.courseCode, cpbi.instCourseLink, cpbi.instLink, cpcrrm.rankType, cpcrrm.roundNum as numberOfRound, cpcrrm.closingRank, cpcrrm.categoryName, cplt.cityName, cplt.stateName");
		$this->dbHandle->from('CollegePredictor_Colleges cpc');
		$this->dbHandle->join("CollegePredictor_LocationTable cplt","cplt.id = cpc.locId","inner");
		$this->dbHandle->join("CollegePredictor_BranchInformation cpbi","cpc.id = cpbi.clmId","inner");
		$this->dbHandle->join("CollegePredictor_CategoryRoundRankMapping cpcrrm","cpcrrm.branchId = cpbi.branchId","inner");
		if($data['rankType']=='Home' || $data['rankType']=='StateLevel' || $data['rankType']=='HomeUniversity' || $data['rankType']=='HyderabadKarnatakaQuota'){
			$this->dbHandle->where('cplt.stateName', $data['stateName']);
		}
		$this->dbHandle->where('cpcrrm.closingRank !=',0);
		if($data['rankType'] == 'StateLevel'){
			$this->dbHandle->where_in('cpcrrm.rankType', array($data['rankType'], 'HomeUniversity'));
		}else if($data['rankType'] == 'Other' && $data['examName'] == 'MHCET'){
			$this->dbHandle->where_in('cpcrrm.rankType', array($data['rankType'], 'OtherThanHome'));
		}else if($data['rankType'] == 'Other' && $data['examName'] == 'KCET'){
			$this->dbHandle->where_in('cpcrrm.rankType', 'KCETGeneral');
		}
		else{
			$this->dbHandle->where('cpcrrm.rankType',$data['rankType']);
		}
		//$this->dbHandle->where('cpcrrm.rankType',$data['rankType']);
		$this->dbHandle->where('cpcrrm.categoryName',$data['categoryName']);
		$this->dbHandle->where_in('cpbi.branchAcronym',$data['branchAcronym']);
		if($data['round']=='all'){
			$this->dbHandle->where_in('cpcrrm.roundNum',$allRounds);
		}else{
			$this->dbHandle->where('cpcrrm.roundNum',$data['round']);
		}
		$this->dbHandle->where('cpc.exams',$data['examName']);
		if(empty($data['rank']) || $data['rank'] == 0){
			$data['rank'] = 1;
		}
		$this->dbHandle->where('cpcrrm.closingRank >=',$data['rank']);
		$this->dbHandle->where('cpc.status','live');
		$this->dbHandle->where('cpbi.status','live');
		$this->dbHandle->where('cpcrrm.status','live');
		$this->dbHandle->where('cplt.status', 'live');
		$this->dbHandle->order_by('cpcrrm.closingRank', 'ASC'); 
		$query = $this->dbHandle->get();

		$results = $query->result_array();
	
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
				if($value['previousRoundClosingRank']!=$value['closingRank']){
					$res[] = $value;	
				}
			}
		}
		if(!empty($res)){
		$res = $this->checkIfCourseIdInListing($res);
		}
		return $res;
	}
	
	function getInstituteIdForGroupName($collegeGroupName,$examName){
		$this->initiateModel();
		$res = array();
		if($collegeGroupName==''){
			return $res;
		}
		$sql = "select id from CollegePredictor_Colleges where collegeGroupName in (?) and exams=? and status=?";
		$collegeGroupArr = explode(',',$collegeGroupName);
		$query = $this->dbHandle->query($sql,array($collegeGroupArr,$examName,'live'));
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
					$res[] = $value['id'];	
			}
		}
		return $res;
	}
	
	function getDataForCollegeTab($data){
		$this->initiateModel();
		$allRounds = array(1,2,3,4,5,6,7);
		$stateSubQuery = '';
		if($data['rankType']=='Home' || $data['rankType']=='StateLevel' || $data['rankType']=='HomeUniversity' || $data['rankType']=='HyderabadKarnatakaQuota'){
			//$stateQuery = 'cplt.stateName = "'.$data['stateName'].'" and ';
			$stateSubQuery = ' and stateName = cplt.stateName';
		}

		$this->dbHandle->select("(select closingRank  from CollegePredictor_CategoryRoundRankMapping  where branchId=cpbi.branchId and categoryName=cpcrrm.categoryName and closingRank=cpcrrm.closingRank and roundNum=cpcrrm.roundNum-1 $stateSubQuery and status = 'live' and cpbi.status='live' and cpcrrm.status='live') previousRoundClosingRank, cpc.id as instituteId, cpc.collegeName, cpc.locId, cpc.creationDate, cpc.collegeGroupName, cpc.exams, cpc.status, cpbi.branchId, cpbi.branchAcronym, cpbi.branchName, cpbi.shikshaCourseId, cpbi.courseCode, cpbi.instCourseLink, cpbi.instLink, cpcrrm.rankType, cpcrrm.roundNum as numberOfRound, cpcrrm.closingRank, cpcrrm.categoryName, cplt.cityName, cplt.stateName");
		$this->dbHandle->from('CollegePredictor_Colleges cpc');
		$this->dbHandle->join("CollegePredictor_LocationTable cplt","cplt.id = cpc.locId","inner");
		$this->dbHandle->join("CollegePredictor_BranchInformation cpbi","cpc.id = cpbi.clmId","inner");
		$this->dbHandle->join("CollegePredictor_CategoryRoundRankMapping cpcrrm","cpcrrm.branchId = cpbi.branchId","inner");
		if($data['rankType']=='Home' || $data['rankType']=='StateLevel' || $data['rankType']=='HomeUniversity' || $data['rankType']=='HyderabadKarnatakaQuota'){
			$this->dbHandle->where('cplt.stateName', $data['stateName']);
		}
		$this->dbHandle->where('cpcrrm.closingRank !=',0);
		if($data['rankType'] == 'StateLevel'){
			$this->dbHandle->where_in('cpcrrm.rankType', array($data['rankType'], 'HomeUniversity'));
		}else if($data['rankType'] == 'Other' && $data['examName'] == 'MHCET'){
			$this->dbHandle->where_in('cpcrrm.rankType', array($data['rankType'], 'OtherThanHome'));
		}else if($data['rankType'] == 'Other' && $data['examName'] == 'KCET'){
			$this->dbHandle->where_in('cpcrrm.rankType', 'KCETGeneral');
		}else{
			$this->dbHandle->where('cpcrrm.rankType',$data['rankType']);
		}
		
		$this->dbHandle->where('cpcrrm.categoryName',$data['categoryName']);
		if(!empty($data['instituteId']) && count($data['instituteId']) > 0){
			$this->dbHandle->where_in('cpc.id',$data['instituteId']);		
		}
		if($data['round']=='all'){
			$this->dbHandle->where_in('cpcrrm.roundNum',$allRounds);
		}else{
			$this->dbHandle->where('cpcrrm.roundNum',$data['round']);
		}
		$this->dbHandle->where('cpc.exams',$data['examName']);
		$this->dbHandle->where('cpc.status','live');
		$this->dbHandle->where('cpbi.status','live');
		$this->dbHandle->where('cpcrrm.status','live');
		$this->dbHandle->where('cplt.status', 'live');
		$this->db->order_by('cpcrrm.closingRank', 'ASC'); 

		$query = $this->dbHandle->get();
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
				if($value['previousRoundClosingRank']!=$value['closingRank']){
					$res[] = $value;	
				}
			}
		}
		if(!empty($res)){
		$res = $this->checkIfCourseIdInListing($res);
		}
		return $res;
	}

	
	function getDataForBranchTab($data){
		$this->initiateModel();
		$stateQuery    = '';
		$stateSubQuery = '';
		$branches = array();
		if($data['rankType']=='Home' || $data['rankType']=='StateLevel' || $data['rankType']=='HomeUniversity' || $data['rankType']=='HyderabadKarnatakaQuota'){
			$stateSubQuery = ' and stateName = cplt.stateName';
		}
		$allRounds = array(1,2,3,4,5,6,7);

	
        $this->dbHandle->select("(select closingRank  from CollegePredictor_CategoryRoundRankMapping  where branchId=cpbi.branchId and categoryName=cpcrrm.categoryName and closingRank=cpcrrm.closingRank and roundNum=cpcrrm.roundNum-1 $stateSubQuery and status = 'live' and cpbi.status='live' and cpcrrm.status='live') previousRoundClosingRank, cpc.id as instituteId, cpc.collegeName, cpc.locId, cpc.creationDate, cpc.collegeGroupName, cpc.exams, cpc.status, cpbi.branchId, cpbi.branchAcronym, cpbi.branchName, cpbi.shikshaCourseId, cpbi.courseCode, cpbi.instCourseLink, cpbi.instLink, cpcrrm.rankType, cpcrrm.roundNum as numberOfRound, cpcrrm.closingRank, cpcrrm.categoryName, cplt.cityName, cplt.stateName");
		$this->dbHandle->from('CollegePredictor_Colleges cpc');
		$this->dbHandle->join("CollegePredictor_LocationTable cplt","cplt.id = cpc.locId","inner");
		$this->dbHandle->join("CollegePredictor_BranchInformation cpbi","cpc.id = cpbi.clmId","inner");
		$this->dbHandle->join("CollegePredictor_CategoryRoundRankMapping cpcrrm","cpcrrm.branchId = cpbi.branchId","inner");
		if($data['rankType']=='Home' || $data['rankType']=='StateLevel' || $data['rankType']=='HomeUniversity' || $data['rankType']=='HyderabadKarnatakaQuota'){
			$this->dbHandle->where('cplt.stateName', $data['stateName']);
		}
		$this->dbHandle->where('cpcrrm.closingRank !=',0);
		if($data['rankType'] == 'StateLevel'){
			$this->dbHandle->where_in('cpcrrm.rankType', array($data['rankType'], 'HomeUniversity'));
		}else if($data['rankType'] == 'Other' && $data['examName'] == 'MHCET'){
			$this->dbHandle->where_in('cpcrrm.rankType', array($data['rankType'], 'OtherThanHome'));
		}else if($data['rankType'] == 'Other' && $data['examName'] == 'KCET'){
			$this->dbHandle->where_in('cpcrrm.rankType', 'KCETGeneral');
		}else{
			$this->dbHandle->where('cpcrrm.rankType',$data['rankType']);
		}
		//$this->dbHandle->where('cpcrrm.rankType',$data['rankType']);
		$this->dbHandle->where('cpcrrm.categoryName',$data['categoryName']);
		$this->dbHandle->where_in('cpbi.branchAcronym',$data['branchAcronym']);
		if($data['round']=='all'){
			$this->dbHandle->where_in('cpcrrm.roundNum',$allRounds);
		}else{
			$this->dbHandle->where('cpcrrm.roundNum',$data['round']);
		}
		$this->dbHandle->where('cpc.exams',$data['examName']);
		if(empty($data['rank'])){
			$data['rank'] = 1;
		}
		$this->dbHandle->where('cpcrrm.closingRank >=',$data['rank']);
		$this->dbHandle->where('cpc.status','live');
		$this->dbHandle->where('cpbi.status','live');
		$this->dbHandle->where('cpcrrm.status','live');
		$this->dbHandle->where('cplt.status', 'live');
		$this->dbHandle->order_by('cpcrrm.closingRank', 'ASC'); 
		$query = $this->dbHandle->get();
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
				if($value['previousRoundClosingRank']!=$value['closingRank']){
					$res[] = $value;	
				}
			}
		}
		if(!empty($res)){
		$res = $this->checkIfCourseIdInListing($res);
		}
		return $res;
	}
	
	function getMultipleData($data){
		$res = $this->getDataForMAHCETType($data);
		/*if(strtoupper($data['examName']) == 'MAHCET'){
		}
		else if($data['tabType']=='rank'){
			$res = $this->getDataForRankTab($data);
		}elseif($data['tabType']=='college'){
			$res = $this->getDataForCollegeTab($data);
		}else{
			$res = $this->getDataForBranchTab($data);
		}*/
		return $res;
				
	}
	
	function getInstiuteData($examName){
		$this->initiateModel();
		$sql = "select cpc.id, cpc.collegeName, cplt.cityName, cplt.stateName  from CollegePredictor_Colleges cpc,  CollegePredictor_LocationTable cplt where cplt.status='live' and cpc.locId=cplt.id and cpc.exams=? and cpc.status=? order by collegeName asc";
		$query = $this->dbHandle->query($sql,array($examName,'live'));
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
				$res[] = $value;
			}
		}
		return $res;
	}
	
	
	function getStates(){
		$this->initiateModel();
		$sql = "select distinct stateName from CollegePredictor_LocationTable where status='live' order by stateName asc";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
				$res[] = $value;
			}
		}
		return $res;
	}

	function getBranches($examName){
		$this->initiateModel();
		$sql = "select distinct branchAcronym from CollegePredictor_BranchInformation cpbi, CollegePredictor_Colleges cpc where cpc.id = cpbi.clmId and cpc.exams=? and cpc.status=? order by branchAcronym asc";
		$query = $this->dbHandle->query($sql,array($examName,'live'));
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
				$res[] = $value;
			}
		}
		return $res;
	}
	
	function getInstiuteGroups($examName) {
		$this->initiateModel();
		$sql = "select distinct(collegeGroupName) from CollegePredictor_Colleges where exams = ? and status =? and collegeGroupName != '' ";
		$query = $this->dbHandle->query($sql,array($examName,'live'));
		$results = $query->result_array();
		if(!empty($results) && is_array($results)) {
			foreach($results as $key=>$value){
				$res[] = $value;
			}
		}
		return $res;
		
	}

        function insertActivityLog($data) {
                $this->initiateModel('write');
		//Sanitize input
		if(!isset($data['userId'])){
			$data['userId'] = 0;
		}
		
		if(empty($data['resultsFound'])){
			$data['resultsFound'] = 0;
		}
		
	        $queryCmd = $this->dbHandle->insert_string('CollegePredictor_ActivityLog',$data);
        	$query = $this->dbHandle->query($queryCmd);
                return '1';
        }
	function saveTrackingData($data){
		$this->initiateModel('write');
			
		$this->dbHandle->insert('CollegePredictor_TrackingData',$data);	

	}
	function saveFeedbackData($data){
		$this->initiateModel('write');
		//Sanitize input
		if(!isset($data['userId'])){
			$data['userId'] = 0;
		}
		if(empty($data['tabUrl'])){
			return;
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

	function getCollegeURL($instituteId,$examinationName){
		$this->initiateModel();
                $sql = "select distinct cpc.id, cpc.collegeName, cpl.cityName, cpc.exams from CollegePredictor_Colleges cpc, CollegePredictor_LocationTable cpl where cpl.status='live' and cpl.id=cpc.locId and cpc.status='live' and cpc.id = ?";
                $query = $this->dbHandle->query($sql, array($instituteId));
                foreach ($query->result_array() as $row){
                       if($row['id']!='' && $row['collegeName']!=''){
                                  $result = getSeoUrl($row['id'],'collegepredictor-'.$row['exams'],$row['collegeName'],array('examName'=>$row['exams'],'cityName'=>$row['cityName']));
                       }
                }
		return $result;
	}

	function getCollegePredictorExamsBasedOnCourseId($courseId){
                $this->initiateModel();
                $sql    = "select distinct cpc.exams from CollegePredictor_Colleges cpc, CollegePredictor_BranchInformation cpbi where cpc.id = cpbi.clmId and cpc.status='live' and cpbi.status='live' and shikshaCourseId=?";
                $query  = $this->dbHandle->query($sql, array($courseId));

                foreach ($query->result_array() as $row){
                        $result['examName'][] = $row['exams'];
                }
                return $result;
        }

    function checkIfPredictorMappingForCourseExist($oldCourse, $dbHandle){
    	if(empty($oldCourse) || $oldCourse <=0){
			return false;
		}

		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('read');	
		}
		$this->dbHandle->select('branchId');
		$this->dbHandle->from('CollegePredictor_BranchInformation');
		$this->dbHandle->where('status','live');	
   		$this->dbHandle->where('shikshaCourseId',$oldCourse);
   		$result = $this->dbHandle->get()->result_array();
   		if(count($result) > 0){
   			return true;
   		}else{
   			return false;
   		}
    }

    function migrateOrDeletePredictorMappingForCourse($oldCourse, $newCourse, $dbHandle){
    	if(empty($oldCourse) || $oldCourse <=0){
			return false;
		}

		if(!empty($newCourse)){
			if($newCourse <=0){
			return false;
			}
		}

		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}

		if(empty($newCourse)){
			$fieldsTobeUpdated = array('status' => 'history');
   		}else{
   			$fieldsTobeUpdated = array('shikshaCourseId' => $newCourse);
   		}

		$response = true;
   		$this->initiateModel('write');
   		$this->dbHandle->where('status','live');
   		$this->dbHandle->where('shikshaCourseId',$oldCourse);
   		$response = $this->dbHandle->update('CollegePredictor_BranchInformation',$fieldsTobeUpdated);
   		return $response;
    }
	function getLocationsByExamAndState($exam='',$state='',$sort_cityName=True){
		if(empty($exam) && empty($state)){
			return array();
		}
		$this->initiateModel();
		$sql = "select distinct cplt.id,cplt.cityName from CollegePredictor_LocationTable cplt join CollegePredictor_Colleges cpc on cplt.id=cpc.locId where cpc.status='live' and cplt.status='live' and cpc.exams = ? and cplt.stateName=? ";
		if($sort_cityName){
			$sql.= ' order by cplt.cityName';
		}
		$params=array($exam,$state);
		
		$query = $this->dbHandle->query($sql,$params)->result_array();
		return $query;
	}

	function getCoursesHavingPredictors($exam,$courseIds){
		if(empty($courseIds)){
			return array();
		}
		$this->initiateModel();
		$sql    = "select distinct shikshaCourseId from CollegePredictor_Colleges cpc, CollegePredictor_BranchInformation cpbi where cpc.id = cpbi.clmId and cpc.status='live' and cpbi.status='live' and shikshaCourseId in (?) and cpc.exams=?";
		$query  = $this->dbHandle->query($sql, array($courseIds,$exam))->result_array();

		return $this->getColumnArray($query,'shikshaCourseId');
	}

	function getCutoffCategoryByCourseIds($exam,$courseIds){
		$this->initiateModel();

		$whereStatements = '';
		$joinStatement = '';
		$params = array();
		if(!empty($courseIds)) {
			$whereStatements = 'cbi.shikshaCourseId in (?) and ';
			$params[] = $courseIds;
		} else {
			$joinStatement = "JOIN shiksha_courses sc on (sc.course_id = cbi.shikshaCourseId and sc.status = 'live')";
		}
		
		$params[] = $exam;
		
		$sql    = "select distinct ccm.categoryName as category from CollegePredictor_CategoryRoundRankMapping ccm 
					JOIN CollegePredictor_BranchInformation cbi ON (ccm.branchId= cbi.branchId) 
					JOIN CollegePredictor_Colleges cc ON (cc.id = cbi.clmId) 
					$joinStatement
					where  $whereStatements cc.exams = ? AND cc.status = 'live'
					and cbi.status = 'live' and ccm.status = 'live' and ccm.closingRank!=0";
					
		$query  = $this->dbHandle->query($sql, $params)->result_array();
		return $this->getColumnArray($query,'category');
	}

	function getCutoffInstitutes($exam,$customData=array()){
		$this->initiateModel();
		$sql    = "select distinct primary_id  from shiksha_courses sc join CollegePredictor_BranchInformation cbi on (sc.course_id = cbi. shikshaCourseId) join CollegePredictor_Colleges cc on(cc.id = cbi.clmId) where cc.exams = ? and cc.status='live' and cbi.status='live' and sc.status='live'";
		$query  = $this->dbHandle->query($sql, array($exam))->result_array();
		$instituteIds = array();
		foreach ($query as $key => $value) {
			$instituteIds[$value['primary_id']]=  $value['primary_id']; 
		}
		if(isset($customData['filterListingId'])){
			$instituteIds[$customData['filterListingId']] = $instituteIds[$customData['filterListingId']];
		}
		$instituteIds = array_keys($instituteIds);
		$sql = "select listing_type_id as institute_id ,listing_seo_url as url, listing_title as name from listings_main where listing_type_id in (?) and listing_type in ('institute','university_national') and status = 'live'";
		$query = $this->dbHandle->query($sql,array($instituteIds))->result_array();
		$urls = array();
		foreach($query as $row)
		{
			if($row['url']!='' && $row['url']!=NULL){
				$college_cutOff_url = $row['url'].'/cutoff';
			    $urls[$row["institute_id"]] = array("url"=>addingDomainNameToUrl(array('url' => $college_cutOff_url , 'domainName' =>SHIKSHA_HOME)), "name"=>$row['name'],"id"=>$row["institute_id"]);
			}
		}
		return $urls;
	}

	function getCutoffSpecializations($exam,$categoryName,$shikshaCourseIds=array()){
		$this->initiateModel();
		$sql = "SELECT DISTINCT cbi.shikshaCourseId as courseIds FROM CollegePredictor_BranchInformation cbi JOIN CollegePredictor_Colleges cc ON (cc.id = cbi.clmId) JOIN CollegePredictor_CategoryRoundRankMapping ccm ON (ccm.branchId = cbi.branchId) WHERE cc.exams = ? AND cc.status = 'live' AND cbi.status = 'live' AND ccm.status = 'live' AND ccm.closingRank != 0 AND ccm.categoryName = ? ";
		$sqlArgs = array($exam,$categoryName);
		if(!empty($shikshaCourseIds)){
			$sqlArgs[]=$shikshaCourseIds;
			$sql .=(" AND cbi.shikshaCourseId in (?)");
		}
		$query = $this->dbHandle->query($sql, $sqlArgs)->result_array();
		$courseIds = $this->getColumnArray($query,'courseIds');
		if(empty($courseIds)){
			return array();
		}

		$sql = "select distinct scti.specialization_id, sp.name from shiksha_courses_type_information scti join specializations sp on scti.specialization_id=sp.specialization_id where scti.status = 'live' and scti.type='entry' and sp.status='live' and sp.type='specialization' and scti.course_id in (?)";
		$query = $this->dbHandle->query($sql, array($courseIds))->result_array();
		$specializations = array();
		foreach($query as $row){
			$specializations[$row['specialization_id']] = $row;
		}
		return $specializations;
	}

	function getAllClosingRank($examName,$categoryName,$specializationIds = array(),$shikshaCourseIds=array()){
		$this->initiateModel();
		$sql = "SELECT DISTINCT ccm.closingRank from CollegePredictor_CategoryRoundRankMapping ccm join CollegePredictor_BranchInformation cbi on (ccm.branchId = cbi.branchId) JOIN CollegePredictor_Colleges cc ON (cc.id = cbi.clmId) WHERE cc.exams = ? AND cc.status = 'live' AND cbi.status = 'live' AND ccm.status = 'live' AND ccm.closingRank != 0 AND ccm.categoryName = ?" ;

		$params = array();
		$params[] = $examName;
		$params[] = $categoryName;
		$specializationCourses = array();
		if(!empty($specializationIds) || !empty($shikshaCourseIds)){
			$specializationCourses =  $this->getCoursesBySpecialization($specializationIds[0]);
		}
		$courseIds = array();
		if(empty($specializationCourses) && !empty($shikshaCourseIds)){
			$courseIds = $shikshaCourseIds;
		}
		else if(!empty($specializationCourses) && empty($shikshaCourseIds)){
			$courseIds = $specializationCourses;
		}
		else if(!empty($specializationCourses) && !empty($shikshaCourseIds)){
			$courseIds = array_intersect($shikshaCourseIds, $specializationCourses);	
		}
		if(!empty($courseIds)){
			$sql .=  ' and cbi.shikshaCourseId in (?)';
			$params[] = $courseIds;
		}

		$sql .= ' order by ccm.closingRank desc';
		$query = $this->dbHandle->query($sql,$params)->result_array();
		$data = $this->getColumnArray($query,'closingRank'); 
		return $data;	
	}
	function getCoursesBySpecialization($specializationIds=array()){
		$this->initiateModel();
		$specializationCourses=array();
		if(!empty($specializationIds) && is_numeric($specializationIds)){
			if(!empty($this->coursesBySpecialization[$specializationIds])){
				
				return $this->coursesBySpecialization[$specializationIds];
			}
			$sql = "select distinct course_id  from shiksha_courses_type_information where status = 'live' and type='entry' and specialization_id in (?)";
			$query = $this->dbHandle->query($sql, array($specializationIds))->result_array();
			$specializationCourses = $this->getColumnArray($query,'course_id');
			$this->coursesBySpecialization[$specializationIds] = $specializationCourses;
		}
		return $specializationCourses;
	}

	function getFiltersForCollegePredictor($data, $type) {
		// _p($data); die;
		$this->initiateModel();
		$params = array();$whereStatements = array();

		switch ($type) {
			case 'state':
				$selectCountClause = " cplt.stateName ";
				$orderBy = ' order by cplt.stateName asc';
				//applying branch filter
				if(!empty($data['branchFilter'])){
					$whereStatements[] = " cpbi.branchName IN (?)";
					$params[] = $data['branchFilter'];
				}
				//applying college filter
				if(!empty($data['collegeFilter'])){
					$whereStatements[] = " cpc.id IN (?)";
					$params[] = $data['collegeFilter'];
				}
				break;
			case 'city':
				$selectCountClause = " cplt.cityName ";
				$orderBy = ' order by cplt.cityName asc';
				//applying branch filter
				if(!empty($data['branchFilter'])){
					$whereStatements[] = " cpbi.branchName IN (?)";
					$params[] = $data['branchFilter'];
				}
				//applying college filter
				if(!empty($data['collegeFilter'])){
					$whereStatements[] = " cpc.id IN (?)";
					$params[] = $data['collegeFilter'];
				}
				break;
			case 'specialization':
				$selectCountClause = " cpbi.branchName ";
				$orderBy = ' order by cpbi.branchName asc';
				//applying college filter
				if(!empty($data['collegeFilter'])){
					$whereStatements[] = " cpc.id IN (?)";
					$params[] = $data['collegeFilter'];
				}
				// applying state filter
				if(!empty($data['locationFilter']['state'])){
					$whereStatements[] = " cplt.stateName IN (?)";
					$params[] = $data['locationFilter']['state'];
				}
				// applying city filter
				if(!empty($data['locationFilter']['city'])){
					$whereStatements[] = " cplt.cityName IN (?)";
					$params[] = $data['locationFilter']['city'];
				}
				break;
			case 'college':
				$selectCountClause = " cpc.id, cpc.collegeName, cpc.collegeGroupName, cplt.cityName, cplt.stateName ";
				$orderBy = ' order by cpc.collegeName asc';
				//applying branch filter
				if(!empty($data['branchFilter'])){
					$whereStatements[] = " cpbi.branchName IN (?)";
					$params[] = $data['branchFilter'];
				}
				// applying state filter
				if(!empty($data['locationFilter']['state'])){
					$whereStatements[] = " cplt.stateName IN (?)";
					$params[] = $data['locationFilter']['state'];
				}

				// applying city filter
				if(!empty($data['locationFilter']['city'])){
					$whereStatements[] = " cplt.cityName IN (?)";
					$params[] = $data['locationFilter']['city'];
				}
				break;
		}

		$sql = "SELECT  distinct $selectCountClause from CollegePredictor_Colleges cpc join CollegePredictor_LocationTable cplt on cplt.id = cpc.locId AND cpc.status='live' AND cplt.status='live' join CollegePredictor_BranchInformation cpbi on cpc.id = cpbi.clmId AND cpbi.status='live' join CollegePredictor_CategoryRoundRankMapping cpcrrm on cpcrrm.branchId = cpbi.branchId AND cpcrrm.closingRank != 0 AND cpcrrm.status = 'live' ";

		if(!empty($data['categoryName'])){
			$whereStatements[] = 'cpcrrm.categoryName = ?';
			$params[] = $data['categoryName'];
		}
		if(!empty($data['examName'])){
			$whereStatements[] = 'cpc.exams = ?';
			$params[] = $data['examName'];
		}
		$whereStatements[] = 'cpcrrm.closingRank != 0';
		if(!empty($data['rank'])){
			if(!empty($data['invertLogic']) && $data['invertLogic']==1){
				$whereStatements[] = 'cpcrrm.closingRank <= ?';
			}
			else{
				$whereStatements[] = 'cpcrrm.closingRank >= ?';
			}
			$params[] = $data['rank'];
		}

		if(($data['rankType']=='Home' || $data['rankType']=='StateLevel' || $data['rankType']=='HomeUniversity' || $data['rankType']=='HyderabadKarnatakaQuota') && strtolower($data['examName']) != 'mahcet' && strtolower($data['examName']) != 'jee-mains'){
			$whereStatements[] = "cplt.stateName = ? ";
			$params[] = $data['stateName'];
		}

		if($data['rankType'] == 'StateLevel'){
			$whereStatements[] = "cpcrrm.rankType in (?)";
			$params[] = array($data['rankType'], 'HomeUniversity');
		}else if($data['rankType'] == 'Other' && $data['examName'] == 'MHCET'){

			$whereStatements[] = "cpcrrm.rankType in (?)";
			$params[] = array($data['rankType'], 'OtherThanHome');

		}else if($data['rankType'] == 'Other' && $data['examName'] == 'KCET'){
			$whereStatements[] = "cpcrrm.rankType = ?";
			$params[] = 'KCETGeneral';
		}
		else if(strtolower($data['examName']) == 'mahcet'){
			if(!empty($data['cityId']) && $data['cityId'] > 0){
				$whereStatements[] = "((cplt.id = ? and cpcrrm.rankType = 'Home') OR (cplt.id != ? and cpcrrm.rankType = 'OtherThanHome'))";
				$params[] = $data['cityId'];$params[] = $data['cityId'];
			}
			else{
				$whereStatements[] = "cpcrrm.rankType = 'OtherThanHome'";
			}
		}
		else if(strtolower($data['examName']) == 'jee-mains' && !empty($data['stateName'])){
			$whereStatements[] = "CASE cpcrrm.rankType WHEN 'Home' THEN cplt.stateName = ? ELSE 1 = 1 END";
			$params[] = $data['stateName'];
		}
		else{
			$whereStatements[] = "cpcrrm.rankType = ?";
			$params[] = $data['rankType'];
		}


		if(!empty($whereStatements)){
			$sql .= 'where '.implode($whereStatements,' AND ');
		}

		$sql .= $orderBy;
		$query = $this->dbHandle->query($sql,$params)->result_array();
		return $query;
		// _p($query); die('aaa');
	}

	function getPredictorTrackingData($startDate,$endDate,$pageNo,$pageSize){
		$sql = "select cptd.user_id AS userId,cptd.exam_name AS examName,cptd.rank AS rank,cptd.percentile AS percentile,cptd.score AS score,created_on AS SubmitDate from CollegePredictor_TrackingData cptd where created_on >= ? AND created_on <= ? group by user_id,exam_name limit ?, ? ";
		$this->initiateModel();
		$query = $this->dbHandle->query($sql,array($endDate,$startDate,$pageNo*$pageSize,$pageSize));
		return $query->result_array();
	}

	function getTrackingDataCount($startDate,$endDate){
		$this->initiateModel();
		$sql = "select count(distinct user_id,exam_name) as count from CollegePredictor_TrackingData where created_on >= ? AND created_on <= ?";
		$query = $this->dbHandle->query($sql,array($endDate,$startDate));
		return $query->result_array();
	}

	function getCoursesHavingCutoffData($courseIds){
		if(empty($courseIds)){
			return array();
		}
		$this->initiateModel();
		$sql    = "select count(distinct course_id) as courseCount from shortlist_courses_cutoff_information where course_id in (?) and status = 'live'";
		$query  = $this->dbHandle->query($sql, array($courseIds))->result_array();
		$data = reset($query);
		
		if($data['courseCount'] > 0) {
			return true;
		}
		return false;
	}
}
