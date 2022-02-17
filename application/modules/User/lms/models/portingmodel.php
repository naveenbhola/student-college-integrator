<?php

class portingmodel extends MY_Model
{
	private $dbHandle = null;

	function __construct()
	{
		parent::__construct('User');
	}

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			if($module == "MISTracking"){
				$module ='Listing';
			}
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

    public function getUserIdsPortedForPortingAgent($clientId){
        $this->initiateModel('read','MISTracking');
        $sql = "select c.leadid from  `porting_status` a, porting_main b, SALeadMatchingLog c where b.client_id = ? and a.porting_master_id = b.id and c.id = a.ported_item_id";
        $query = $this->dbHandle->query($sql, array($clientId));
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = $row->leadid;
        }
        return $retArr;
    }

    public function getUserIdsPortedForPortingAgentByClient($portingIds, $leadIds){
    	if(!is_array($portingIds) || count($portingIds)<=0){
    		return array();
    	}

    	if(!is_array($leadIds) || count($leadIds)<=0){
    		return array();
    	}

        $this->initiateModel('read','MISTracking');
        $this->dbHandle->select('SALML.leadid');
        $this->dbHandle->from('porting_status ps');
        $this->dbHandle->join('SALeadMatchingLog SALML','SALML.id = ps.ported_item_id','inner');
        $this->dbHandle->where_in('ps.porting_master_id',$portingIds);
        $this->dbHandle->where_in('SALML.leadid',$leadIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getPortedData($portingId, $ids){
        $this->initiateModel('read','MISTracking');
        if(count($ids) == 0) {
			return array();
		}
		$sql = "select ported_item_id, sent_data from  `porting_status` where porting_master_id = ? and ported_item_id in (?)";
        $query = $this->dbHandle->query($sql, array($portingId,$ids));
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[$row->ported_item_id] = $row->sent_data;
        }
        return $retArr;
    }

    public function getPortingsByClientId($clientId){
        $this->initiateModel('read','MISTracking');
        $sql = "SELECT a.id, a.name, a.type, b.SubscriptionId FROM `porting_main` a, `porting_subscription` b where a.id = b.porting_master_id and b.status='live' and a.client_id=?
        		UNION
        		SELECT pm.id, pm.name, pm.type, Null FROM `porting_main` pm WHERE pm.`client_id` = ? and pm.type = 'examResponse'";
        $query = $this->dbHandle->query($sql, array($clientId, $clientId));
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[$row->id]['subs'] = $row->SubscriptionId;
            $retArr[$row->id]['name'] = $row->name;
            $retArr[$row->id]['type'] = $row->type;
            $retArr[$row->id]['portingid'] = $row->id;
        }
        return $retArr;
    }

    public function getMisData($portingId, $dateFrom, $dateTo){
    	$dateFrom = $dateFrom.' 00:00:00';
    	$dateTo = $dateTo.' 23:59:59';
        $this->initiateModel('read','MISTracking');
        $sql = "select porting_master_id, request_time, ported_item_id, response, flag, sent_data from  `porting_status` where porting_master_id = ? and request_time >= ? and  request_time <= ? order by request_time desc";
        $query = $this->dbHandle->query($sql, array($portingId, $dateFrom, $dateTo));
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function getMisCountData($portingId, $dateFrom, $dateTo, $reportFormat){
        $this->initiateModel('read','MISTracking');
        if($reportFormat == 'D'){
            $sql = "select porting_master_id as portingID, left(request_time,10)  as portingDate, count(*) as number from  `porting_status` where porting_master_id = ".$this->dbHandle->escape($portingId)." and left(request_time,10) >= ".$this->dbHandle->escape($dateFrom)." and  left(request_time,10) <= ".$this->dbHandle->escape($dateTo)."  group by left(request_time,10) DESC";
        }
        if($reportFormat == 'W'){
            $sql = "select porting_master_id as portingID, CONCAT( DATE_ADD( left(request_time,10),INTERVAL(1 - DAYOFWEEK(left(request_time,10))) DAY), ' TO ' , DATE_ADD(left(request_time,10), INTERVAL(7 - DAYOFWEEK(left(request_time,10))) DAY)) as portingWeek, count(*) as number  from  `porting_status` where porting_master_id = ".$this->dbHandle->escape($portingId)." and left(request_time,10) >= ".$this->dbHandle->escape($dateFrom)." and  left(request_time,10) <= ".$this->dbHandle->escape($dateTo)."  group by WEEK(request_time),YEAR(request_time) DESC  order by YEAR(request_time), MONTH(request_time)";
        }
        if($reportFormat == 'M'){
            $sql = "select porting_master_id as portingID, MONTHNAME(request_time) as portingMonth, count(*) as number from  `porting_status` where porting_master_id = ".$this->dbHandle->escape($portingId)." and left(request_time,10) >= ".$this->dbHandle->escape($dateFrom)." and  left(request_time,10) <= ".$this->dbHandle->escape($dateTo)."  group by YEAR(request_time), MONTH(request_time) DESC order by YEAR(request_time), MONTH(request_time)";
        }

        $query = $this->dbHandle->query($sql);
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

	public function getAllLivePortings($flag = '',$portingTime='real_time',$type)
    {
    	
		$this->initiateModel('read','MISTracking');
		$exludeIncludeLead = "('lead','matched_response')";
		if ($type == 'lead'){
			$appendSql = " and a.type in ".$exludeIncludeLead;
		}
		else{
			$appendSql = " and a.type not in ".$exludeIncludeLead;	
		}
		if ($flag == 'Email'){
			$sql = "SELECT a.*, b.SubscriptionId FROM `porting_main` a, `porting_subscription` b where a.id = b.porting_master_id and a.status = 'live' and b.status='live' and a.request_type = 'EMAIL' and a.porting_time='".$this->dbHandle->escape_str($portingTime)."' ".$appendSql;
		} else {
			$sql = "SELECT a.*, b.SubscriptionId FROM `porting_main` a, `porting_subscription` b where a.id = b.porting_master_id and a.status in ('live','intest') and b.status='live' and a.request_type NOT IN ('EMAIL') ".$appendSql;
		}
		$query = $this->dbHandle->query($sql);
		$retArr = array();
		foreach($query->result() as $row) {
			$row->Daily_Limit = 0;			//hard coded to discard porting level daily limit
			$retArr[$row->id] = (array)$row;
		}
		
		$runSearchAgentQueryFlag = false;
		$ids = array_keys($retArr);
		if(count($ids) >0 ){
		    // $joinedIds = implode(",",$ids);
		    $sql = "SELECT `porting_master_id`,`key`, `value` FROM `porting_conditions` WHERE `porting_master_id` in (?) AND `status` = 'live'";
		    
		    $query = $this->dbHandle->query($sql, array($ids));
		    foreach($query->result() as $row) {
				$retArr[$row->porting_master_id]['portingCriteria'][] = (array)$row;

				if($row->key == 'searchagent'){
					$runSearchAgentQueryFlag = true;
					//$retArr[$row->porting_master_id]['searchagent'][] = $row->value;
					$allSearchAgents[] = $row->value;
					$allSearchAgentMap[$row->value] = $row->porting_master_id;
				}
		    }

		    

		    $sql = "SELECT a.porting_master_id  ,a.client_field_name, b.name, b.field_group, a.master_field_id , a.other_value as other_value FROM `porting_field_mappings` a left join  `porting_masterfield_list` b on a.master_field_id = b.id where a.`porting_master_id` in (?) and a.status='live'";
		    
		    $query = $this->dbHandle->query($sql, array($ids));
		    foreach($query->result() as $row) {
				if($row->master_field_id >0){
					if ($flag == 'Email'){
						$retArr[$row->porting_master_id]['mappings'][$row->field_group][$row->name][] = $row->client_field_name;
					}
					else {
						$retArr[$row->porting_master_id]['mappings'][$row->field_group][$row->name] = $row->client_field_name;
					}
				}
				else{
				    $retArr[$row->porting_master_id]['mappings']['other'][$row->client_field_name] = $row->other_value;
				}
		    }

		    if($runSearchAgentQueryFlag){
		    	$sql = "select leads_daily_limit, searchAgentId from SASearchAgent where searchAgentId in (?)";
		    	$agentDailylimit = $this->dbHandle->query($sql, array($allSearchAgents))->result_array();
		    }

		    foreach ($agentDailylimit as $agentData) {
		    	$retArr[$allSearchAgentMap[$agentData['searchAgentId']]]['Daily_Limit'] += $agentData['leads_daily_limit'];
		    }

		}
		return $retArr;
	}

    public function getResponses($ids, $responseTypes, $type, $lastPortedId, $startDate,$portingId,$portingId){
        
        $this->initiateModel('read','Listing');
        $joinedIds = implode(",",$ids);
		
		//$file = '/tmp/porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');

		$normalCourseIds = array();
		$normalCourseIds = $ids;
		$joinedNormalCourseIds = $joinedIds;

		$returnData = $this->getDailyPortingLimitAndDuration($portingId);
		$DailyLimits = $returnData[0]['Daily_Limit'];
		$duration = $returnData[0]['duration'];
		$durationClause = null;
		if($duration !== 'old'){
			$startDate = date('Y-m-d 00:00:00');
			$durationClause = " and a.submit_date > '".$startDate."' ";
		}

		$portingIds=array($portingId);
		$responsesPorted = $this->getNumberOfItemsPortedForAgents($portingIds);
		foreach($responsesPorted as $k=>$v){
			$response_ported=$v;	
		}
		$difference = $DailyLimits - $response_ported;
		
		if($difference < 0){
			$difference = 0;
		}

		if(count($responseTypes) > 0){
            $formattedTypes = array();
	    	$responseTypeClause = null;
	    
			if($responseTypes[0] == "All"){

			} else {
				$clauses = array();
				foreach($responseTypes as $responseType){
					if($responseType != "Others"){
						$formattedTypes[] = "'".$responseType."'";
					}
				}
				if(count($formattedTypes) > 0){
					$joinedFormattedTypes = implode(",",$formattedTypes);
					$clauses[] = "action in ($joinedFormattedTypes)";
				}
				if(in_array('Others', $responseTypes)) {
					$this->load->config('ResponseTypes',TRUE);
					$responseConfigTypes = $this->config->item('responseTypes', 'ResponseTypes');
					if(count($responseConfigTypes) > 0) {
						$joinedResponseTypes = "'".implode("','",$responseConfigTypes)."'";
						$clauses[] = "action not in ($joinedResponseTypes)";
					}
				}
				if(count($clauses) > 0) {
					$responseTypeClause = " and (".implode(" or ",$clauses).")";
				}
			}
		}
		
		if(!empty($normalCourseIds)){
			if($lastPortedId > 0){
				if(!$DailyLimits){
					$sql = "select a.id, a.userId, a.listing_type, a.listing_type_id, a.action, a.submit_date, c.instituteLocationId from `tempLMSTable` a, tuserflag b, responseLocationTable c where a.listing_subscription_type='paid' and a.userid = b.userid and a.listing_type = '".$this->dbHandle->escape_str($type)."' and a.listing_type_id in (?) and a.id >".$this->dbHandle->escape($lastPortedId)." and a.isClientResponse = 'yes' and b.isTestUser = 'NO' and a.id = c.responseId " . $durationClause . " " . $responseTypeClause . " order by a.`id` asc";
				}else{
					$sql = "select a.id, a.userId, a.listing_type, a.listing_type_id, a.action, a.submit_date, c.instituteLocationId from `tempLMSTable` a, tuserflag b, responseLocationTable c where a.listing_subscription_type='paid' and a.userid = b.userid and a.listing_type = '".$this->dbHandle->escape_str($type)."' and a.listing_type_id in (?) and a.id >".$this->dbHandle->escape($lastPortedId)." and a.isClientResponse = 'yes' and b.isTestUser = 'NO' and a.id = c.responseId " . $durationClause . " " . $responseTypeClause . " order by a.`id` asc LIMIT 0 , ".$difference;
				}
			}
			else{
				if(!$DailyLimits){
					$sql = "select a.id, a.userId, a.listing_type, a.listing_type_id, a.action, a.submit_date, c.instituteLocationId from `tempLMSTable` a , tuserflag b, responseLocationTable c where a.listing_subscription_type='paid' and a.userid = b.userid and a.listing_type = '".$this->dbHandle->escape_str($type)."' and a.isClientResponse = 'yes' and a.listing_type_id in (?) and a.submit_date >'".$this->dbHandle->escape_str($startDate)."' and b.isTestUser = 'NO' and a.id = c.responseId " . $responseTypeClause . " order by a.`id` asc";
				}else{
					$sql = "select a.id, a.userId, a.listing_type, a.listing_type_id, a.action, a.submit_date, c.instituteLocationId from `tempLMSTable` a , tuserflag b, responseLocationTable c where a.listing_subscription_type='paid' and a.userid = b.userid and a.listing_type = '".$this->dbHandle->escape_str($type)."' and a.listing_type_id in (?) and a.submit_date >'".$this->dbHandle->escape_str($startDate)."' and a.isClientResponse = 'yes' and b.isTestUser = 'NO' and a.id = c.responseId " . $responseTypeClause . " order by a.`id` asc LIMIT 0 , ".$difference;
				}
			}
		}
		
		echo "sql == Responses====".$sql."<br/>";
		
		$retArr = array();
			
		if($sql){

			$query = $this->dbHandle->query($sql,array($ids));
			
			//fwrite($fp,'response SQL == '.$this->dbHandle->last_query()."\n");

			//Stop study abroad UG responses
			$userIds = array();
			$courseIds = array();
			$UGUsers = array();
			$UGStudyAbroadCourses = array();
			foreach ($query->result() as $row) {
				$userIds[] = $row->userId;
				$customFlag = null;
				if($row->listing_type == 'course') {
					$courseIds[] = $row->listing_type_id;
					$customFlag = 'false';
				}
			}
			$userIds = array_unique($userIds);
			$courseIds = array_unique($courseIds);
			
			if(count($userIds) && count($courseIds)) {
				$queryCmd = "SELECT DISTINCT cd.course_id FROM course_details AS cd INNER JOIN abroadCategoryPageData AS cpd ON cpd.course_id = cd.course_id WHERE cd.course_id IN ( ".implode(',',$courseIds)." ) AND cd.course_level_1 = 'Under Graduate' AND cpd.country_id !=2 AND cd.status = 'live' AND cpd.status = 'live'";
				$queryForUGCourses = $this->dbHandle->query($queryCmd);
				foreach ($queryForUGCourses->result() as $row) {
					$UGStudyAbroadCourses[$row->course_id] = 1;
				}
				
				if(count($UGStudyAbroadCourses)) {
					$queryCmd = "SELECT DISTINCT UserId FROM tUserEducation WHERE UserId IN ( ".implode(',',$userIds)." ) AND Level = 'UG'";
					$queryForUGUser = $this->dbHandle->query($queryCmd);
					foreach ($queryForUGUser->result() as $row) {
						$UGUsers[$row->UserId] = 1;
					}
				}
			}
			
			foreach($query->result() as $row) {
				if(!($UGStudyAbroadCourses[$row->listing_type_id] == 1 AND $UGUsers[$row->userId] == 1 AND $row->listing_type == 'course')) {
					$retArr[$row->id]['listing_type'] = $row->listing_type;
					$retArr[$row->id]['listing_type_id'] = $row->listing_type_id;
					$retArr[$row->id]['userid'] = $row->userId;
					$retArr[$row->id]['action'] = $row->action;
					$retArr[$row->id]['submit_date'] = $row->submit_date;
					$retArr[$row->id]['instituteLocationId'] = $row->instituteLocationId;
					$retArr[$row->id]['custom_flag'] = $customFlag;
				}
			}
		}
		
		return $retArr;
    }

    public function getBackLogResponses($ids, $responseTypes, $type, $startDate, $portingId){
        $this->initiateModel('read','MISTracking');
        $joinedIds = implode(",",$ids);
		
		$normalCourseIds = array();
		$normalCourseIds = $ids;
		$joinedNormalCourseIds = $joinedIds;

		$returnData = $this->getDailyPortingLimitAndDuration($portingId);
		$DailyLimits = $returnData[0]['Daily_Limit'];
		$duration = $returnData[0]['duration'];
		
		// $durationClause = null;
		if($duration !== 'old'){
			$startDate = date('Y-m-d 00:00:00');
			//$durationClause = " and a.matchingTime > '".$startDate."' ";
		}
		
		$portingIds = array($portingId);
		$responsesPorted = $this->getNumberOfItemsPortedForAgents($portingIds);
		foreach($responsesPorted as $k=>$v){
			$response_ported = $v;	
		}

		$difference = $DailyLimits - $response_ported;
		if($difference < 0){
			$difference = 0;
		}
	
		if(count($responseTypes) > 0){

            $formattedTypes = array();
	    	$responseTypeClause = null;
	    
            if($responseTypes[0] == "All"){

            } else {

				$clauses = array();
				foreach($responseTypes as $responseType){
					if($responseType != "Others"){
						$formattedTypes[] = "'".$responseType."'";
					}
				}

				if(count($formattedTypes) > 0){
					$joinedFormattedTypes = implode(",",$formattedTypes);
					$clauses[] = "action in ($joinedFormattedTypes)";
				}

				if(in_array('Others', $responseTypes)) {
					$this->load->config('ResponseTypes',TRUE);
					$responseConfigTypes = $this->config->item('responseTypes', 'ResponseTypes');
					if(count($responseConfigTypes) > 0) {
						$joinedResponseTypes = "'".implode("','",$responseConfigTypes)."'";
						$clauses[] = "action not in ($joinedResponseTypes)";
					}
				}
				
				if(count($clauses) > 0) {
					$responseTypeClause = " and (".implode(" or ",$clauses).")";
				}

			}

        }
	
		if(!empty($normalCourseIds)){

			if(!$DailyLimits){
				$sql = "select a.id, a.userId, a.listing_type, a.listing_type_id, a.action, a.submit_date, c.instituteLocationId from `tempLMSTable` a , tuserflag b, responseLocationTable c where a.listing_subscription_type='paid' and a.userid = b.userid and a.listing_type = ? and a.listing_type_id in (?)  and a.submit_date >? and b.isTestUser = 'NO' and a.isClientResponse ='yes' and a.id = c.responseId " . $responseTypeClause . " order by a.`id` asc";
			} else {
				$sql = "select a.id, a.userId, a.listing_type, a.listing_type_id, a.action, a.submit_date, c.instituteLocationId from `tempLMSTable` a , tuserflag b, responseLocationTable c where a.listing_subscription_type='paid' and a.userid = b.userid and a.listing_type = ? and a.listing_type_id in (?)  and a.submit_date >? and b.isTestUser = 'NO' and a.isClientResponse ='yes' and a.id = c.responseId " . $responseTypeClause . "order by a.`id` asc LIMIT 0 , ".$difference;
			}

		}

		echo "sql == backlogResponses====".$sql."<br/>";
		
		$retArr = array();
			
		if($sql){	
			$query = $this->dbHandle->query($sql, array($type,$ids, $startDate));
			_P($this->dbHandle->last_query());
			//Stop study abroad UG responses
			$userIds = array();
			$courseIds = array();
			$UGUsers = array();
			$UGStudyAbroadCourses = array();

			foreach ($query->result() as $row) {
				$userIds[] = $row->userId;
				$customFlag = null;
				if($row->listing_type == 'course') {
					$courseIds[] = $row->listing_type_id;
					$customFlag = 'false';
				}
			}

			$userIds = array_unique($userIds);
			$courseIds = array_unique($courseIds);
			
			if(count($userIds) && count($courseIds)) {

				$queryCmd = "SELECT DISTINCT cd.course_id FROM course_details AS cd INNER JOIN abroadCategoryPageData AS cpd ON cpd.course_id = cd.course_id WHERE cd.course_id IN ( ".implode(',',$courseIds)." ) AND cd.course_level_1 = 'Under Graduate' AND cpd.country_id !=2 AND cd.status = 'live' AND cpd.status = 'live'";
				$queryForUGCourses = $this->dbHandle->query($queryCmd);
				foreach ($queryForUGCourses->result() as $row) {
					$UGStudyAbroadCourses[$row->course_id] = 1;
				}
				
				if(count($UGStudyAbroadCourses)) {
					$queryCmd = "SELECT DISTINCT UserId FROM tUserEducation WHERE UserId IN ( ".implode(',',$userIds)." ) AND Level = 'UG'";
					$queryForUGUser = $this->dbHandle->query($queryCmd);
					foreach ($queryForUGUser->result() as $row) {
						$UGUsers[$row->UserId] = 1;
					}
				}

			}
			
			foreach($query->result() as $row) {
				if(!($UGStudyAbroadCourses[$row->listing_type_id] == 1 AND $UGUsers[$row->userId] == 1 AND $row->listing_type == 'course')) {
					$retArr[$row->id]['listing_type'] = $row->listing_type;
					$retArr[$row->id]['listing_type_id'] = $row->listing_type_id;
					$retArr[$row->id]['userid'] = $row->userId;
					$retArr[$row->id]['action'] = $row->action;
					$retArr[$row->id]['submit_date'] = $row->submit_date;
					$retArr[$row->id]['instituteLocationId'] = $row->instituteLocationId;
					$retArr[$row->id]['custom_flag'] = $customFlag;
				}
			}

		}
		
        return $retArr;
    }

    public function getBackLogLeads($ids, $startDate, $portingId){
        $this->initiateModel('read','MISTracking');
        // $joinedIds = implode(",",$ids);
        $retArr = array();
	
		//$DailyLeadsLimits = $this->getDailyPortingLimitAndDuration($portingId);
		
		$returnData = $this->getDailyPortingLimitAndDuration($portingId);
		$DailyLeadsLimits = $returnData[0]['Daily_Limit'];
		$duration = $returnData[0]['duration'];
		
		$durationClause = null;
		if($duration !== 'old'){
			$startDate = date('Y-m-d 00:00:00');
			//$durationClause = " and a.matchingTime > '".$startDate."' ";
		}
		
		$portingId=array($portingId);
			
		$leadsPorted = $this->getNumberOfItemsPortedForAgents($portingId);
			
		foreach($leadsPorted as $k=>$v){
			$lead_ported=$v;	
		}
		$difference = $DailyLeadsLimits - $lead_ported;

		if($difference < 0){
			$difference = 0;
		}

		if(!($DailyLeadsLimits)){
			$sql = "SELECT a.id, a.leadid, a.searchAgentid, a.stream, a.substream, a.ProfileType FROM `SALeadMatchingLog` a, tuserflag b  where a.leadid = b.userid and a.searchAgentid in (?) and a.matchingTime >= ? and b.isTestUser = 'NO' group by a.leadid order by a.`id` asc";
		}else{
			$sql = "SELECT a.id, a.leadid, a.searchAgentid, a.stream, a.substream, a.ProfileType FROM `SALeadMatchingLog` a, tuserflag b  where a.leadid = b.userid and a.searchAgentid in (?) and a.matchingTime >= ? and b.isTestUser = 'NO' group by a.leadid order by a.`id` asc LIMIT 0 , ".$difference;
		}

		echo "sql == backlogLeads====".$sql."<br/>"; 
		$query = $this->dbHandle->query($sql, array($ids,$startDate));
        foreach($query->result() as $row) {
            $retArr[$row->id]['leadid'] = $row->leadid;
            $retArr[$row->id]['searchAgentid'] = $row->searchAgentid;
            $retArr[$row->id]['stream'] = $row->stream;
            $retArr[$row->id]['substream'] = $row->substream;
            $retArr[$row->id]['ProfileType'] = $row->ProfileType;
        }
        return $retArr;
    }

    public function getLeads($portingId,$ids,$lastPortedId, $startDate, $portingId){
		$this->initiateModel('read','MISTracking');
		$joinedIds = implode(",",$ids);
		$retArr    = array();
		
		//$file = '/tmp/porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');
		
		$returnData       = $this->getDailyPortingLimitAndDuration($portingId);
		//$DailyLeadsLimits = $returnData[0]['Daily_Limit'];
		$duration         = $returnData[0]['duration'];
		
		//$durationClause   = null;
		$indexRequire = "";
		if($duration !== 'old'){
			$indexRequire = "matchingTime";
			$startDate      = date('Y-m-d 00:00:00');
		}else{
			$indexRequire = "searchAgentid";
		}
		
		$portingIds  =array($portingId);
		
		$leadsPorted = $this->getNumberOfItemsPortedForAgents($portingIds);
			
		foreach($leadsPorted as $k=>$v){
			$lead_ported=$v;	
		}
		
		/*$difference = $DailyLeadsLimits - $lead_ported;

		if($difference < 0){
			$difference = 0;
		}*/

		$this->initiateModel('read','MISTracking');
		
		if($lastPortedId >0){
			/*if($DailyLeadsLimits >0){
				if($difference == 0){
					return array();
				}
			}*/

			$this->dbHandle->select('id, leadid, searchAgentid, stream, substream, ProfileType');
			$this->dbHandle->where_in('searchAgentid',$ids);
			$this->dbHandle->where('id > ',$lastPortedId);
			if($duration !== 'old'){
				$this->dbHandle->from('SALeadMatchingLog use index (matchingTime)');
				$this->dbHandle->where('matchingTime > ',$startDate);
	       	}else{
	       		$this->dbHandle->from('SALeadMatchingLog use index (searchAgentid)');

	       	}

			$this->dbHandle->group_by('leadid');
			$result = $this->dbHandle->get()->result_array();

	       	$leads_array = array();	       	
			$leadPortingData = array();
			$leadIdForCheck = 0;
			foreach($result as $row) {
				$leadPortingData[$row['leadid']] = $row;
				$leads_array[] = intval($row['leadid']);
				$leadIdForCheck = $row['leadid'];
			}
			unset($result);

			if($leadIdForCheck > 0) {
				$this->dbHandle->select('distinct(SALeadML.leadid) leadid');
				$this->dbHandle->from('SALeadMatchingLog SALeadML');
				$this->dbHandle->join('porting_status ps','SALeadML.id = ps.ported_item_id','inner');
				$this->dbHandle->where('ps.porting_master_id', intval($portingId));
				$this->dbHandle->where_in('SALeadML.leadid',$leads_array);
				$result = $this->dbHandle->get()->result_array();
			
			    $leads_sent_array = array();
				foreach($result as $row) {
					if(isset($leadPortingData[$row['leadid']])){
						unset($leadPortingData[$row['leadid']]);
					} 
				}


				/*if($DailyLeadsLimits){
					$leadPortingData = array_slice($leadPortingData, 0,$difference);
				}*/

				foreach ($leadPortingData as $leadId => $leadDetails) {
					$retArr[$leadDetails['id']]['leadid']        = $leadDetails['leadid'];
					$retArr[$leadDetails['id']]['searchAgentid'] = $leadDetails['searchAgentid'];
					$retArr[$leadDetails['id']]['stream']        = $leadDetails['stream'];
					$retArr[$leadDetails['id']]['substream']     = $leadDetails['substream'];
					$retArr[$leadDetails['id']]['ProfileType']   = $leadDetails['ProfileType'];
				}
			}
        }else{
			/*if(!$DailyLeadsLimits){
				$sql = "SELECT a.id, a.leadid, a.searchAgentid, a.stream, a.substream, a.ProfileType FROM tuserflag b, `SALeadMatchingLog` a 
						where a.leadid = b.userid and a.searchAgentid in ($joinedIds) 
						and a.matchingTime > ".$this->dbHandle->escape($startDate)." 
						and b.isTestUser = 'NO' group by a.leadid order by a.`id` asc";
			}else{
				$sql = "SELECT a.id, a.leadid, a.searchAgentid, a.stream, a.substream, a.ProfileType FROM tuserflag b, `SALeadMatchingLog` a USE INDEX (".$indexRequire.") where a.leadid = b.userid and a.searchAgentid in ($joinedIds) and a.matchingTime > ".$this->dbHandle->escape($startDate)." and b.isTestUser = 'NO' group by a.leadid order by a.`id` asc LIMIT 0 , ".$difference;
			}
*/
			//echo "getLeads === ".$sql."<br/>"; 
			//error_log($sql);

			$sql = "SELECT a.id, a.leadid, a.searchAgentid, a.stream, a.substream, a.ProfileType FROM tuserflag b, `SALeadMatchingLog` a 
						where a.leadid = b.userid and a.searchAgentid in ($joinedIds) 
						and a.matchingTime > ".$this->dbHandle->escape($startDate)." 
						and b.isTestUser = 'NO' group by a.leadid order by a.`id` asc";

			$query = $this->dbHandle->query($sql);
	        foreach($query->result() as $row) {
	            $retArr[$row->id]['leadid'] = $row->leadid;
	            $retArr[$row->id]['searchAgentid'] = $row->searchAgentid;
	            $retArr[$row->id]['stream'] = $row->stream;
	            $retArr[$row->id]['substream'] = $row->substream;
	            $retArr[$row->id]['ProfileType'] = $row->ProfileType;
	        }
		}

		//fwrite($fp,'Leads SQL == '.$this->dbHandle->last_query()."\n");
        return $retArr;
    }

    public function getUserCityForPorting($ids, $params){

        $this->initiateModel('read','MISTracking');
        // $joinedIds = implode(",",$ids);
        
        $sql = "select a.userid, IFNULL(b.city_name,'N.A.') as city_name from tuser a left join countryCityTable b on a.city = b.city_id where a.userid in (?)";
        $query = $this->dbHandle->query($sql,array($ids));
        $data = array();
        foreach($query->result() as $row){
            $data[$row->userid]['Residence_City'] = $row->city_name;
        }
        foreach ($ids as $userid) {
        	if(!isset($data[$userid])){
        		$data[$userid]['Residence_City'] = 'N.A.';
        	}
        }

        return $data;
    }

    public function getMatchedResponseDataForPorting($userId,$matchId,$portingType=''){
        $this->initiateModel('read','MISTracking');
		$sql = "select userId, IFNULL( matchedCourse, 'NA' ) as matchedCourse, matchedCourseId, matchingTime from  userMatchedResponseCoursesTable ".
	        " where userId = ? and matchingLogId = ?";
        $query = $this->dbHandle->query($sql, array($userId, $matchId));
		
        $data = array();
		foreach($query->result() as $row) {
			if($portingType == 'email'){
				$data[$row->userId]['Matched Response For'] = $row->matchedCourse;
				$matchingTime = explode(' ',$row->matchingTime);
				$data[$row->userId]['Response Date'] = $matchingTime[0];
			} else {
				$data[$row->userId]['MAT_RES_Course'] = $row->matchedCourse;
				$data[$row->userId]['MAT_RES_CourseId'] = $row->matchedCourseId;
			}
		}

		if($portingType == ''){
			if(!isset($data[$userId])){
	        	$data[$userId]['MAT_RES_Course'] = 'N.A.';
	        	$data[$userId]['MAT_RES_CourseId'] = 'N.A.';
	        }
	    }
        
		return $data;
    }
    
    public function getUserXIIYearForPorting($ids, $params){
        $this->initiateModel('read','MISTracking');
        // $joinedIds = implode(",",$ids);
		$sql="select UserId, IFNULL(DATE_FORMAT(CourseCompletionDate,'%Y'),'N.A.') as XII_Completion_Year from  tUserEducation where UserId in (?) and Level = '12'";
		//error_log("abcd :: sql query".$sql);
		$query = $this->dbHandle->query($sql,array($ids));
        $data = array();
        foreach($query->result() as $row) {
            $data[$row->UserId]['SA_XII_Year'] = $row->XII_Completion_Year;
        }
        foreach ($ids as $userid) {
        	if(!isset($data[$userid])){
        		$data[$userid]['SA_XII_Year'] = 'N.A.';
        	}
        }
        return $data;
    }
    
    public function getUserGradYearForPorting($ids, $params){
        $this->initiateModel('read','MISTracking');
        //$joinedIds = implode(",",$ids);
		$sql="select UserId, IFNULL(DATE_FORMAT(CourseCompletionDate,'%Y'),'N.A.') as Grad_Completion_Year from  tUserEducation where UserId in (?) and Level = 'UG'";
		$query = $this->dbHandle->query($sql,array($ids));
        $data = array();
        foreach($query->result() as $row) {
            $data[$row->UserId]['SA_Graduation_Year'] = $row->Grad_Completion_Year;
        }
        foreach ($ids as $userid) {
        	if(!isset($data[$userid])){
        		$data[$userid]['SA_Graduation_Year'] = 'N.A.';
        	}
        }
        return $data;
    }

    public function getCompetitiveExamsForPorting($userIds) {
    	$this->initiateModel('read','MISTracking');
    	// $joinedIds = implode(",",$userIds);
    	$sql = "select UserId, Name from  tUserEducation where UserId in (?) and Level = 'Competitive exam'";
    	$query = $this->dbHandle->query($sql,array($userIds));
    	$data = array();
    	$returnData = array();
    	
    	foreach ($query->result() as $row) {
    		if($row->Name){
    			$data[$row->UserId][] = $row->Name;
    		}
    	}

    	foreach ($userIds as $userid) {
    		if(isset($data[$userid])){
    			$returnData[$userid]['Exams Taken'] = implode(', ', $data[$userid]);
    		} else {
    			$returnData[$userid]['Exams Taken'] = 'N.A.';
    		}
    	}
    	unset($data);

    	return $returnData;

    }
    
    public function getUserLocalityForPorting($ids,$params){
		$this->initiateModel('read','MISTracking');
        // $joinedIds = implode(",",$ids);
		$sql="select a.userid, IFNULL(b.localityName,'N.A.') as Residence_Locality from tuser a left join localityCityMapping b on a.Locality = b.localityId  where a.userid in (?)";
		$query = $this->dbHandle->query($sql,array($ids));
        $data = array();
        foreach($query->result() as $row) {
            $data[$row->userid]['Residence_Locality'] = $row->Residence_Locality;
        }
        foreach ($ids as $userid) {
        	if(!isset($data[$userid])){
        		$data[$userid]['Residence_Locality'] = 'N.A.';
        	}
        }
        return $data;
    }
    
    // not in use now
    public function getUserPrefCityForPorting($ids, $params){
        $this->initiateModel('read','MISTracking');
        // $joinedIds = implode(",",$ids);
        $sql = "SELECT IFNULL(b.city_name,IFNULL(c.state_name,'N.A.')) as city_name , a.userid 
            FROM tUserLocationPref a left join countryCityTable b on a.CityId = b.city_id left join stateTable c on a.StateId = c.state_id where  a.userid in (?)
            ORDER BY a.prefid ASC";

        $query = $this->dbHandle->query($sql,array($ids));
        $data = array();
        foreach($query->result() as $row) {
            $data[$row->userid]['LDB_Preferred_City'] = $row->city_name;
        }
        $userIdArr = array_keys($data);
        foreach($ids as $k=>$v){
            if(!in_array($v,$userIdArr)){
                $data[$v]['LDB_Preferred_City'] = "N.A.";
            }
        }

        return $data;
    }

    public function getUserPrefSpecializationForPorting($ids,$params){
		$this->initiateModel('read','MISTracking');
        // $joinedIds = implode(",",$ids);
		$sql = "SELECT a.name, b.UserId FROM categoryBoardTable a
		LEFT JOIN tUserPref b ON a.boardId = b.abroad_subcat_id
		WHERE b.UserId in (?) AND a.flag = 'studyabroad' AND a.isOldCategory = '0'
		AND b.ExtraFlag = 'studyabroad'";
		$query = $this->dbHandle->query($sql,array($ids));
        $data = array();
        foreach($query->result() as $row) {
            $data[$row->UserId]['LDB_SA_Course_Specialization'] = $row->name;
        }
        $userIdArr = array_keys($data);
        foreach($ids as $k=>$v){
            if(!in_array($v,$userIdArr)){
                $data[$v]['LDB_SA_Course_Specialization'] = "N.A.";
            }
        }
        return $data;
    }
    
    public function getUserPreferredCourseForPorting($ids, $params){
        $this->initiateModel('read','MISTracking');
        // $userIds = implode(",",$ids);
        $sql = "SELECT c.name as course, a.userid, a.ExtraFlag
	            FROM tUserPref a, tCourseSpecializationMapping b,categoryBoardTable c
	            WHERE a.desiredcourse = b.specializationid
	            AND b.CategoryId = c.boardId
	            AND a.userid IN (?)
	            AND a.DesiredCourse > 0 AND a.ExtraFlag  = 'studyabroad'";

        $sql2 = "SELECT b.CourseName as course, a.userid
				FROM tUserPref a, tCourseSpecializationMapping b
				WHERE a.DesiredCourse = b.SpecializationId
				AND a.userid IN (?)
				AND a.DesiredCourse > 0 AND a.ExtraFlag  = 'studyabroad'";

        $query = $this->dbHandle->query($sql,array($ids));
		$query2 = $this->dbHandle->query($sql2,array($ids));
	
		$data = array();
        foreach($query->result() as $row) {
		    $data[$row->userid]['LDB_SA_Course'] = $row->course;
		    $extraFlag[$row->userid] = $row->ExtraFlag;
        }
		foreach($ids as $id){
			if($data[$id]['LDB_SA_Course'] == "All" && $extraFlag[$id] == "studyabroad") {
				foreach($query2->result() as $row) {
	                /*if(in_array($row->course,array('BE/Btech','MS','MBA'))){
						$data[$row->userid]['LDB_SA_Course'] = $row->course;
	                }*/

	                if(in_array($row->course,array(STUDY_ABROAD_POPULAR_MBA,STUDY_ABROAD_POPULAR_MS,STUDY_ABROAD_POPULAR_BEBTECH, STUDY_ABROAD_POPULAR_MEM,STUDY_ABROAD_POPULAR_MPHARM,STUDY_ABROAD_POPULAR_MFIN,STUDY_ABROAD_POPULAR_MDES,STUDY_ABROAD_POPULAR_MFA,STUDY_ABROAD_POPULAR_MENG,STUDY_ABROAD_POPULAR_BSC,STUDY_ABROAD_POPULAR_BBA,STUDY_ABROAD_POPULAR_MBBS,STUDY_ABROAD_POPULAR_BHM,STUDY_ABROAD_POPULAR_MARCH,STUDY_ABROAD_POPULAR_MIS ,STUDY_ABROAD_POPULAR_MIM,STUDY_ABROAD_POPULAR_MASC,STUDY_ABROAD_POPULAR_MA))){
						$data[$row->userid]['LDB_SA_Course'] = $row->course;
	                }
				}
			}
		}
	
        $userIdArr = array_keys($data);
        foreach($ids as $k=>$v){
			if(!in_array($v,$userIdArr)){
				$data[$v]['LDB_SA_Course'] = "N.A.";
			}
        }
        return $data;
    }

    public function getBasicUserDetailsForPorting($ids, $params){
        
        $this->initiateModel('read','MISTracking');
        $this->load->helper('shikshaUtility');
		//$joinedIds = implode(",",$ids);
        
        $sql = "select userid, IFNULL( firstname, 'N.A.' ) as `First Name` , IFNULL( lastname, 'N.A.' ) as `Last Name`,
		CONCAT(IFNULL( firstname, 'N.A.' ), ' ', IFNULL( lastname, 'N.A.' )) as Name , email as Email,
		mobile as Mobile,isdCode as ISD_Code, experience as `Work Experience`, concat('+',isdCode,'-',mobile) as ISDCode_Mobile from tuser where userid in (?)";
        $query = $this->dbHandle->query($sql,array($ids));
        $data = array();
		foreach($query->result() as $row) {
			foreach($params as $key=>$mappedValue){

				if($key == 'Work Experience'){
					
					$value = $row->$key;
					$workex = getExperienceText($value);
					$data[$row->userid][$key] = $workex;

				} else {
					$data[$row->userid][$key] = $row->$key;
				}
			}
		}

		return $data;
    }

    public function getStateForLDBUser($ids, $params){
		
		$this->initiateModel('read','MISTracking');
	    
	    // $joinedIds = implode(",",$ids);
		$sql = "Select a.state_name, c.userid from  stateTable a left join countryCityTable b on (b.state_id = a.state_id) left join tuser c on (b.city_id = c.city) where c.userid in (?)";	
		$query = $this->dbHandle->query($sql,array($ids))->result();
		$data = array();
		foreach($query as $row) {
			$data[$row->userid]['Residence_State'] = $row->state_name;
		}
		
		foreach($ids as $userid){
			$queryForCity = "SELECT `userid`,`city`  FROM `tuser` WHERE `userid` = ?";
			$queryForCityResult = $this->dbHandle->query($queryForCity, array($userid))->result();
			foreach($queryForCityResult as $cityStateResult){
				if($cityStateResult->city == '10223'){
					$data[$cityStateResult->userid]['Residence_State'] = 'Delhi';
				}
				if($cityStateResult->city == '10224'){
					$data[$cityStateResult->userid]['Residence_State'] = 'Maharashtra';
				}
				if($cityStateResult->city == '12292'){
					$data[$cityStateResult->userid]['Residence_State'] = 'Punjab & Harayana';
				}
			}
			if(!isset($data[$userid])){
        		$data[$userid]['Residence_State'] = 'N.A.';
        	}
		}
		return $data;

	}

    public function getUserPreferredCountryForPorting($ids, $params){
        $this->initiateModel('read','MISTracking');
        //$joinedIds = implode(",",$ids);
		$sql = "SELECT a.userid, IFNULL(b.name,'N.A.') as country 
                FROM tUserLocationPref a left join countryTable b
				on a.CountryId = b.countryId  where  a.userid in (?)
                ORDER BY a.userid, a.prefid ASC";
        $query = $this->dbHandle->query($sql,array($ids));
        $data = array();
		foreach($query->result() as $row) {
			if(!is_array($tempdata[$row->userid])) {
				$i = 1;
			}
			$key = 'LDB_SA_Country_'.$i;
			$tempdata[$row->userid][$key] = $row->country;
			$i++;
        }
		foreach($tempdata as $userId=>$userData) {
			foreach($userData as $field=>$value) {
				if(array_key_exists($field,$params)){
					$data[$userId][$field] = $value;
				}
			}
		}
		foreach ($ids as $userid) {
			if(!isset($data[$userid])){
				if(array_key_exists('LDB_SA_Country_1', $params)){
					$data[$userid]['LDB_SA_Country_1'] = 'N.A.';
				}
				if(array_key_exists('LDB_SA_Country_2', $params)){
					$data[$userid]['LDB_SA_Country_2'] = 'N.A.';
				}
				if(array_key_exists('LDB_SA_Country_3', $params)){
					$data[$userid]['LDB_SA_Country_3'] = 'N.A.';
				}
			}
		}
		return $data;
    }
    
    public function updatePortingStatus($id, $portingMasterId, $response, $flagFirstTime, $serializedData, $status){
        /*$this->initiateModel('write','User');
        $serializedData = base64_encode($serializedData);
        $response = addslashes($response);
        $sql = "INSERT INTO `porting_status` (`id`, `porting_master_id`, `request_time`, `ported_item_id`, `response`, `flag`, `sent_data`) VALUES (NULL, ?, now(), ?, '$response', ?, ?)";
        $sql2 = "update  `porting_main` set last_ported_id = ? where id = ?";
        $this->dbHandle->query($sql, array($portingMasterId, $id, $flagFirstTime, $serializedData));
        $this->dbHandle->query($sql2, array($id, $portingMasterId));*/
        
        $this->initiateModel('write','User');
        $serializedData = base64_encode($serializedData);
        $response = addslashes($response);
        $sql = "INSERT INTO `porting_status` (`id`, `porting_master_id`, `request_time`, `ported_item_id`, `response`, `flag`, `sent_data`) VALUES (NULL, '$portingMasterId', now(), '$id', '$response', '$flagFirstTime', '$serializedData')";
        $sql2 = "update `porting_main` set last_ported_id = ? , status= ? where id = ?";
        $this->dbHandle->query($sql);
        $this->dbHandle->query($sql2, array($id, $status, $portingMasterId));
    }

    public function updateFirstTimePortingStatus($id, $status){
        $this->initiateModel('write','User');
        $sql = "update  `porting_main` set isrun_firsttime = ? where id = ?";
        $this->dbHandle->query($sql, array($status, $id));
    }

    public function getAllPortings()
    {
        $this->initiateModel('read','MISTracking');
        $sql = "SELECT * FROM (SELECT pm.*, CONCAT(IFNULL( tu.firstname, '' ) , ' ', IFNULL( tu.lastname, '' ))  as displayname , ps.`SubscriptionId` 
        		FROM `porting_main` pm, `tuser` tu, `porting_subscription` ps 
        		WHERE pm.`client_id` = tu.`userid` and pm.`id` = ps.`porting_master_id` and ps.`status` = 'live' 

        		UNION

        		SELECT pm.*, CONCAT(IFNULL( tu.firstname, '' ) , ' ', IFNULL( tu.lastname, '' ))  as displayname , Null 
        		FROM `porting_main` pm, `tuser` tu
        		WHERE pm.`client_id` = tu.`userid` and pm.type = 'examResponse')  tmp  order by  client_id,id ";

        $query = $this->dbHandle->query($sql);
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function getAllPortingSearchAgents($clientId)
    {
        $this->initiateModel('read','MISTracking');
        $sql = "SELECT searchagentid , searchagentName, type FROM SASearchAgent WHERE clientid =? AND is_active = 'live' AND deliveryMethod = 'porting'";
        $query = $this->dbHandle->query($sql, array($clientId));
        $retArr = array();
        foreach ($query->result_array() as $row) {
            $retArr[$row['searchagentid']]['Name'] = $row['searchagentName'];
	    	$retArr[$row['searchagentid']]['type'] = $row['type'];
        }
        return $retArr;
    }

    public function getShikshaFields($portingType='')
    {
        $this->initiateModel('read','MISTracking');
        $typeClause = '';
        if($portingType){
        	$typeClause = 'AND portingType in ("both","'.$this->dbHandle->escape_str($portingType).'")';
        }
        $sql = "SELECT * FROM `porting_masterfield_list` where status = 'live' $typeClause ORDER BY order_no";
        $query = $this->dbHandle->query($sql);
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        
        return $retArr;
    }

    public function getClientFields($portingId)
    {
        $this->initiateModel('read','MISTracking');
        $sql = "SELECT * FROM `porting_field_mappings` WHERE `status` = 'live' and `porting_master_id` = ?";
        $query = $this->dbHandle->query($sql, array($portingId));
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function getPortingConditions($portingId)
    {
        $this->initiateModel('read','MISTracking');
        $retArr = array();
        $sql = "SELECT `key`, `value` FROM `porting_conditions` WHERE `status` = 'live' and `porting_master_id` = ?";
        $query = $this->dbHandle->query($sql, array($portingId));
        foreach($query->result() as $row) {
            $retArr[$row->key][$row->value] = true;
        }
        return $retArr;
    }

    public function getPortingMain($portingId)
    {
        $this->initiateModel('read','MISTracking');
        $sql = "SELECT * FROM `porting_main` WHERE `id` = ?";
        $query = $this->dbHandle->query($sql, array($portingId));
        foreach($query->result() as $row) {
            $retArr = (array)$row;
        }
        return $retArr;
    }

    public function getPortingActiveSubscription($portingId)
    {
        $this->initiateModel('read','MISTracking');
        $sql = "SELECT SubscriptionId FROM `porting_subscription` WHERE `status` = 'live' AND `porting_master_id` = ?";
        $query = $this->dbHandle->query($sql, array($portingId));
        foreach($query->result() as $row) {
            return $row->SubscriptionId;
        }
    }

	public function addPorting($portingData)
	{
		$this->initiateModel('write','User');
		if($portingData['dataEncode']==null)
		{
			$portingData['dataEncode']='no';
		}
		else
		{
			$portingData['dataEncode']='yes';
		}
		$portingData['xmlFormat'] = htmlspecialchars_decode($portingData['xmlFormat']);
		$data = array(
			'client_id'           => $portingData['userid'],
			'name'                => $portingData['porting_name'],
			'type'                => $portingData['porting_data'],
			'firsttime_startdate' => $portingData['timerange_from'],
			'request_type'        => $portingData['porting_method'],
			'api'                 => $portingData['porting_url'],
			'Daily_Limit'         => $portingData['porting_DailyLimits'],
			'porting_time'        => $portingData['porting_time'],
			'data_format'         => $portingData['dataFormatType'],
			'data_key'            => $portingData['jsonDataKey'],
			'duration'            => $portingData['porting_duration'],
			'xml_format'          => $portingData['xmlFormat'],
			'custom_header'       => trim($portingData['custom_header']),
			'dataEncode'          => $portingData['dataEncode'],
		);
	
		if($portingData['dataFormatType'] == 'json' ){
			$data['xml_format'] = $portingData['jsonFormat'];
		}

		$this->dbHandle->insert('porting_main',$data);
		$portingMasterId = $this->dbHandle->insert_id();
		
		if($portingData['porting_data'] != 'examResponse') {
			$subscriptionData = array(
				'porting_master_id'=> $portingMasterId,
				'SubscriptionId'=> $portingData['selected_subscription'],
				'status'=> 'live'
			);
			
			$this->dbHandle->set('created_on', 'NOW()', FALSE);
			$this->dbHandle->insert('porting_subscription',$subscriptionData);
			
		}


		$portingConditions =array();
		if($portingData['porting_data'] == 'response') {			
			foreach($portingData['university_id'] as $universityId) {
				$portingConditions[] = array(
					'porting_master_id' => $portingMasterId,
					'key'               => 'university',
					'value'             => $universityId,
					'status'            => 'live'
				);
			}
			
			foreach($portingData['institute_id'] as $instituteId) {
				$portingConditions[] = array(
					'porting_master_id' => $portingMasterId,
					'key'               => 'institute',
					'value'             => $instituteId,
					'status'            => 'live'
				);
			}
			
			foreach($portingData['course_id'] as $courseId) {
				$portingConditions[] = array(
					'porting_master_id' => $portingMasterId,
					'key'               => 'course',
					'value'             => $courseId,
					'status'            => 'live'
				);
			}
			
			if(in_array('All', $portingData['response_types'])) {
				$portingData['response_types'] = array('All');
			}
			
			foreach($portingData['response_types'] as $responseType) {
				$portingConditions[] = array(
					'porting_master_id' => $portingMasterId,
					'key'               => 'responsetype',
					'value'             => $responseType,
					'status'            => 'live'
				);
			}
			
		} elseif($portingData['porting_data'] == 'lead' || $portingData['porting_data'] == 'matched_response') {
			
			foreach($portingData['lead_genies'] as $leadGenieId) {
				$portingConditions[] = array(
								'porting_master_id' => $portingMasterId,
								'key'               => 'searchagent',
								'value'             => $leadGenieId,
								'status'            => 'live'
							);
			}
		}else if($portingData['porting_data'] == 'examResponse'){

			if(in_array('All', $portingData['response_types'])) {
				$portingData['response_types'] = array('All');
			}
			
			foreach($portingData['response_types'] as $responseType) {
				$portingConditions[] = array(
					'porting_master_id' => $portingMasterId,
					'key'               => 'responsetype',
					'value'             => $responseType,
					'status'            => 'live'
				);
			}

			foreach($portingData['subscriptionIds'] as $subscriptionid) {
				$portingConditions[] = array(
					'porting_master_id' => $portingMasterId,
					'key'               => 'examSubscription',
					'value'             => $subscriptionid,
					'status'            => 'live'
				);
			}
		}
		
		$this->dbHandle->insert_batch('porting_conditions',$portingConditions);
		$portingFieldMappings =array();
		
		foreach($portingData['var_name'] as $key => $clientField) {
			$clientField = trim($clientField);
			if(!empty($clientField)) {
				$portingFieldMappings[] = array(
					'porting_master_id' => $portingMasterId,
					'client_field_name' => $clientField,
					'master_field_id'   => $portingData['var_key'][$key],
					'other_value'       => $portingData['temp_name'][$key],
					'status'            => 'live'
				);
			}
		}
		$this->dbHandle->insert_batch('porting_field_mappings',$portingFieldMappings);
		return $portingMasterId;
	}

	public function updatePorting($portingData)
	{
		$this->initiateModel('write','User');
		if($portingData['dataEncode']==null)
		{
			$portingData['dataEncode']='no';
		}
		else
		{
			$portingData['dataEncode']='yes';
		}
		$data = array(
			'name'         => $portingData['porting_name'],
			'request_type' => $portingData['porting_method'],
			'api'          => $portingData['porting_url'],
			'type'         => $portingData['porting_data'],
			'Daily_Limit'  => $portingData['porting_DailyLimits'],
			'porting_time' => $portingData['porting_time'],
			'data_format'  => $portingData['dataFormatType'],
			'data_key'     => $portingData['jsonDataKey'],
			'duration'     => $portingData['porting_duration'],
			'custom_header'       => trim($portingData['custom_header']),
			'dataEncode'   => $portingData['dataEncode'],
		);
		if(!empty($portingData['porting_first_time'])) {
			$data['firsttime_startdate'] = $portingData['timerange_from'];
		}
		$portingData['xmlFormat'] = htmlspecialchars_decode($portingData['xmlFormat']);

		if($portingData['dataFormatType'] == 'json' ){
			$data['xml_format'] = $portingData['jsonFormat'];
		}

		$sql_xml_format = "UPDATE porting_main SET xml_format=? WHERE id=?";
		$this->dbHandle->query($sql_xml_format,array($portingData['xmlFormat'],$portingData['porting_id']));
		
		$this->dbHandle->where('id', $portingData['porting_id']);
		$this->dbHandle->update('porting_main',$data);

		$deletePortingData = array('status'=> 'deleted');
		$this->dbHandle->where('porting_master_id', $portingData['porting_id']);
		$this->dbHandle->update('porting_conditions',$deletePortingData);

		$this->dbHandle->where('porting_master_id', $portingData['porting_id']);
		$this->dbHandle->update('porting_field_mappings',$deletePortingData);
		
		if($portingData['porting_data'] != 'examResponse') {
			$this->dbHandle->where('porting_master_id', $portingData['porting_id']);
			$this->dbHandle->update('porting_subscription',$deletePortingData);

			$subscriptionData = array(
				'porting_master_id' => $portingData['porting_id'],
				'SubscriptionId'    => $portingData['selected_subscription'],
				'status'            => 'live'
			);
			
			$this->dbHandle->set('created_on', 'NOW()', FALSE);
			$this->dbHandle->insert('porting_subscription',$subscriptionData);		
		}

		
		$portingConditions =array();
		if($portingData['porting_data'] == 'response') {
			foreach($portingData['university_id'] as $universityId) {
				$portingConditions[] = array(
					'porting_master_id' => $portingData['porting_id'],
					'key'               => 'university',
					'value'             => $universityId,
					'status'            => 'live'
				);
			}
			
			foreach($portingData['institute_id'] as $instituteId) {
				$portingConditions[] = array(
					'porting_master_id' => $portingData['porting_id'],
					'key'               => 'institute',
					'value'             => $instituteId,
					'status'            => 'live'
				);
			}
			
			foreach($portingData['course_id'] as $courseId) {
				$portingConditions[] = array(
					'porting_master_id' => $portingData['porting_id'],
					'key'               => 'course',
					'value'             => $courseId,
					'status'            => 'live'
				);
			}
			if(in_array('All', $portingData['response_types'])) {
				$portingData['response_types'] = array('All');
			}
			foreach($portingData['response_types'] as $responseType) {
				$portingConditions[] = array(
								'porting_master_id' => $portingData['porting_id'],
								'key'               => 'responsetype',
								'value'             => $responseType,
								'status'            => 'live'
							);
			}
		}
		elseif($portingData['porting_data'] == 'lead' || $portingData['porting_data'] == 'matched_response') {
			foreach($portingData['lead_genies'] as $leadGenieId) {
				$portingConditions[] = array(
								'porting_master_id' => $portingData['porting_id'],
								'key'               => 'searchagent',
								'value'             => $leadGenieId,
								'status'            => 'live'
							);
			}
		}else if($portingData['porting_data'] == 'examResponse'){
			if(in_array('All', $portingData['response_types'])) {
				$portingData['response_types'] = array('All');
			}
			
			foreach($portingData['response_types'] as $responseType) {
				$portingConditions[] = array(
					'porting_master_id' => $portingData['porting_id'],
					'key'               => 'responsetype',
					'value'             => $responseType,
					'status'            => 'live'
				);
			}

			foreach($portingData['subscriptionIds'] as $subscriptionid) {
				$portingConditions[] = array(
					'porting_master_id' => $portingData['porting_id'],
					'key'               => 'examSubscription',
					'value'             => $subscriptionid,
					'status'            => 'live'
				);
			}
		}
		$this->dbHandle->insert_batch('porting_conditions',$portingConditions);

		$portingFieldMappings =array();
		foreach($portingData['var_name'] as $key => $clientField) {
			$clientField = trim($clientField);
			if(!empty($clientField)) {
				if($portingData['porting_method'] == "EMAIL" && ($portingData['var_key'][$key] == 12 || $portingData['var_key'][$key] == 13)){
					$portingFieldMappings[] = array(
								'porting_master_id' => $portingData['porting_id'],
								'client_field_name' => $clientField,
								'master_field_id'   => $portingData['var_key'][$key],
								'other_value'       => $portingData['temp_name'][$key],
								'status'            => 'live'
							);
				}
				else if(($portingData['var_key'][$key] < 12 || $portingData['var_key'][$key] >13) && ($portingData['porting_method'] == "GET" || $portingData['porting_method'] == "POST")){
					$portingFieldMappings[] = array(
								'porting_master_id' => $portingData['porting_id'],
								'client_field_name' => $clientField,
								'master_field_id'   => $portingData['var_key'][$key],
								'other_value'       => $portingData['temp_name'][$key],
								'status'            => 'live'
							);
				}
			}
		}
		$this->dbHandle->insert_batch('porting_field_mappings',$portingFieldMappings);
		return $portingMasterId;
	}

    public function changePortingStatus($portingId,$status,$portingType)
	{
		$this->initiateModel('write','User');
		$data = array('status' => $status, 'status_modification_datetime' => date("Y-m-d H:i:s"));
		
		if($portingType =="examResponse" && $status=="live"){
			$data['isrun_firsttime'] = "no";
		}
		$this->dbHandle->where('id',$portingId);
		$this->dbHandle->update('porting_main',$data);
		//$sql = "update `porting_main` set status = ?, status_modification_datetime = now() where id = ?";
		//$this->dbHandle->query($sql, array($status,$portingId));
		return true;
	}
	
	public function setCustomizedMappedFields($clientId, $entity_ids, $customFieldIds, $customField)
	{
		$this->initiateModel('write','User');
		$entity = '';
		$entity = $customField;
		/*if($customField == 'name'){
			$field = 'course_name';
			$oppfield = 'course_level';
		}
		else if($customField == 'level') {
			$field = 'course_level';
			$oppfield = 'course_name';
		}
		
		$query = "select course_id, $oppfield from porting_customized_fields where client_id=?";		
		$indexedFields = array();
		foreach($this->dbHandle->query($query, array($clientId))->result_array() as $row) {
			$indexedFields[$row['course_id']] = $row[$oppfield];	
		}
		$sql = "delete from porting_customized_fields where client_id=?";*/

		$sql = "update porting_customized_fields  set status = 'history' where client_id = ? and entity_type = ? ";
		$this->dbHandle->query($sql, array($clientId, $entity));


		foreach ($entity_ids as $key => $value){
			if ($customFieldIds[$key] || $indexedFields[$entity_ids[$key]] ){
				$sql = "insert into porting_customized_fields(client_id, entity_id, entity_value, entity_type,status) values ".
					"(?,?,?,?,?)";
				$insert_array = array($clientId,$entity_ids[$key],$customFieldIds[$key],$entity,'live');
				$this->dbHandle->query($sql,$insert_array);
			}
		}
		
		return true;
	}
	
	public function getCustomizedMappedFields($entityIds, $customField, $fieldlist = 'single',$client_id)
	{
		$this->initiateModel('read','MISTracking');
		
		$mappedFields = array();
		if(empty($entityIds)) {
			return $mappedFields;	
		}
		
		$field = $customField;
		
		if($field == 'course_name'){
			$field = array('course_name','course_name_ivr');
		}
		
		if ($field == 'course_level'){
			$field = array('course_level','course_level_ivr');
		}

		$entityIdList = explode(",", $entityIds);
		 
		if ($fieldlist == 'single'){
			$sql = "select entity_id, entity_value from porting_customized_fields where entity_id in (?) and entity_type in (?) and status = ?";
		} else if($fieldlist == 'all') {
			$sql = "select * from porting_customized_fields where entity_id in (?) and entity_type = ? and status = ?";
		}
		if(!empty($client_id)){
			$sql = $sql."and client_id = ?";
			$result = $this->dbHandle->query($sql,array($entityIdList,$field,'live',$client_id))->result_array();
		}
		else{

			$result = $this->dbHandle->query($sql,array($entityIdList,$field,'live'))->result_array();
		}
		

		foreach($result as $row){
			if ($fieldlist == 'single'){ 
				$mappedFields[$row['entity_id']] = $row['entity_value'];
			} else if($fieldlist == 'all') {
				$mappedFields[$row['entity_id']] = $row;				
			}
		}
		return $mappedFields;
	}

	public function getDummyCustomizedMappedFields($clientId,$customField,$fieldlist = 'single')
	{
		$this->initiateModel('read','MISTracking');

		$mappedFields = array();
		if(empty($clientId)) {
			return $mappedFields;	
		}
		
		$field = $customField;
		
		if ($fieldlist == 'single'){
			$sql = "select entity_id, entity_value from porting_customized_fields where client_id  = ? and entity_id = -1 and entity_type = ? and status = ?";
		}else if($fieldlist == 'all') {
			$sql = "select * from porting_customized_fields where client_id  = ? and entity_id = -1 and entity_type = ? and status = ?";
		}
		
		$result = $this->dbHandle->query($sql,array($clientId,$field,'live'))->result_array();
		
		foreach($result as $row){
			if ($fieldlist == 'single'){ 
				$mappedFields[$row['entity_id']] = $row['entity_value'];
			} else if($fieldlist == 'all') {
				$mappedFields[$row['entity_id']] = $row;				
			}
		}

		return $mappedFields;
	}
	
	public function getUserPrefAbroadStatus($userId) {
		$this->initiateModel('read','MISTracking');
		$sql = "SELECT count(*) FROM listings_main WHERE listing_type = 'university' AND username = ?";
		$query = $this->dbHandle->query($sql, array($userId));
		$data = $query->result_array();
		if($data[0]['count(*)'] > 0) {
			$extraFlag = 'studyabroad';
		}
		else {
			$extraFlag = null;
		}
		return $extraFlag;
	}
	
	public function getNumberOfItemsPortedForAgents($porting_ids = array()) {
		
		if(count($porting_ids) == 0) {
				return array();
		}
				
	    $this->initiateModel('read','MISTracking');
	    $date = date("Y-m-d");
		$return_array = array();

	    if (count($porting_ids) !=1)
	    {
		    $sql = "SELECT count(*) as count,`porting_master_id` ".
		             "FROM `porting_status` WHERE `porting_master_id` in (?) ".
		             "AND `request_time`>='$date 00:00:00' AND `request_time`<='$date 23:59:59' group by porting_master_id";
			     
		    $result = $this->dbHandle->query($sql,array($porting_ids))->result_array();
			foreach($result as $row) {
				$return_array[$row['porting_master_id']] = $row['count'];
			}   
		}    
		else
		{

			 $sql = "SELECT count(*) as count,`porting_master_id` ".
		             "FROM `porting_status` force index (`request_time`) WHERE `porting_master_id` in (?) ".
		             "AND `request_time`>='$date 00:00:00' AND `request_time`<='$date 23:59:59'";
		     $result = $this->dbHandle->query($sql,array($porting_ids))->result_array();
		     $return_array[$result[0]['porting_master_id']] = $result[0]['count'];
		}
	    return $return_array;		
	}
	
	function getDailyPortingLimitAndDuration($portingId){
		$this->initiateModel('read','MISTracking');
		$sql="SELECT `Daily_Limit`,`duration` FROM `porting_main` WHERE `id` = ?";
		
		$result = $this->dbHandle->query($sql, array($portingId))->result_array();
		  
		return $result;
		//return $result[0]['Daily_Limit'];
	}

	/*
	 *Functions for Add Custom Location Interface begin here
	 */
	
	/*
	 *Function to insert in Custom Location Table
	 *Param: data from CSV, courseID and InstituteID
	 */
	public function insertInCustomLocationTable($allData = array(),$courseID,$instituteID){
		
		$this->initiateModel('write','User');
		
		$updateInCustomLocationTableQuery = "UPDATE `shiksha`.`CustomLocationTable` SET `Status` = 'History' ".
						    " WHERE `CustomLocationTable`.`course_id` =?";
			       
		$updateInCustomLocationTableResult = $this->dbHandle->query($updateInCustomLocationTableQuery,$courseID);
		
		for($i=0; $i<count($allData); $i++){
			$parameters[]=array(
				'institute_id'=>$instituteID,
				'course_id' =>$courseID,
				'City' => $allData[$i][0],
				'Locality' => $allData[$i][1],
				'location_code' => $allData[$i][2]
			);
		}
		
		$insertInCustomLocationTableQuery = $this->dbHandle->insert_batch('CustomLocationTable',$parameters);
		if(!$insertInCustomLocationTableQuery){
			return 0;
		}else{
			return 1;
		}
	}
	
	/*
	 *Function to check if given courseId exists in CustomLocationTable
	 *Param: Array of courseId => courseName
	 *Return: courseId of existing course
	 */
	public function existingCourseIdInCustomLocationTable($courseList=array()){
		$this->initiateModel('read','MISTracking');
		$courseExistInCustomLocationTable = array();
		
		foreach($courseList as $k=>$v){
			
			$sql = "SELECT *  FROM `CustomLocationTable` WHERE `course_id` = ? AND `Status` = 'Live'";
			$result = $this->dbHandle->query($sql,$k);
		
			if($result->num_rows() > 0){
				$courseExistInCustomLocationTable[] = $k;
			}
		
		}
		
		return $courseExistInCustomLocationTable;
	}
	
	/*
	 *Function to delete a course tupple from CustomLocationTable
	 *Param: CourseID
	 */
	public function deleteCourseEntryFromCustomLocationTable($courseID){
		
		$this->initiateModel('write','User');
		
		$deleteCourseFromCustomLocationTableQuery = "UPDATE `shiksha`.`CustomLocationTable` SET `Status` =".
							    "'History' WHERE `CustomLocationTable`.`course_id` =?";
		
		$result = $this->dbHandle->query($deleteCourseFromCustomLocationTableQuery,$courseID);
		return $result;
	}

	/**
	* Function to get data for admin report
	* 
	* @param : $dateFrom Date Date to begin searching records from
	* @param : $dateTo Date Date to search till
	* @return : $portingReportData array array containing all the data for required fields
	*/ 
	public function getReportData($dateFrom,$dateTo){
		
		$this->initiateModel('read','MISTracking');
		$dateFrom          = $dateFrom." 00:00:00";
		$dateTo            = $dateTo." 23:59:59";
		$portingIds        = array();
		$ERPortingIds      = array();
		$portingReportData = array();

		$getPortingDataQuery = "Select pm.client_id as 'Client Id', CONCAT(IFNULL( tu.firstname, '' ) , ' ', IFNULL( tu.lastname, '' ))".
								" as 'Client Name', tu.email as 'Client Email Id', pm.type as 'Porting Type', ps.porting_master_id".
								" as 'Porting Id', pm.name as 'Porting Name', pm.request_type as 'Porting Method',".
								" count(ps.porting_master_id) as 'Quantity Delivered', pm.status as 'Current Status' from `shiksha`.porting_status ps".
								" left join `shiksha`.porting_main pm on ps.porting_master_id = pm.id left join `shiksha`.tuser tu".
								" on (pm.`client_id` = tu.`userid`) where ps.request_time >= ? and ps.request_time <= ?  group by ps.porting_master_id";
		$query = $this->dbHandle->query($getPortingDataQuery,array($dateFrom,$dateTo));

		foreach($query->result() as $row) {
			$portingRow = (array)$row;
			if($portingRow['Porting Type'] == 'examResponse'){
				$ERPortingIds[] = $portingRow['Porting Id'];
			} else {
				$portingIds[] = $portingRow['Porting Id'];
			}
			$portingReportData[$portingRow['Porting Id']] = $portingRow;
		}
		if(!empty($portingIds)){
			
			$salesManagerDetailsQuery1 = "select PSub.porting_master_id , CONCAT( IFNULL( tu.firstname, '' ) , ' ', IFNULL( tu.lastname, '' ) ) AS 'Sales Manager Name', tu.email AS 'Sales Manager Email' from `shiksha`.porting_subscription as PSub Join `SUMS`.Subscription as sub ON (PSub.SubscriptionId = sub.SubscriptionId) Join `SUMS`.Transaction as tr ON (sub.TransactionId = tr.TransactionId) left Join `SUMS`.Sums_User_Details as sud ON (tr.SalesBy = sud.EmployeeId) left JOIN `shiksha`.tuser as tu on (sud.userId = tu.userid) where PSub.porting_master_id in (?) and PSub.status = 'live'";
			$result1 = $this->dbHandle->query($salesManagerDetailsQuery1,array($portingIds));
			
			foreach($result1->result() as $row) {
				$extportingRow = (array)$row;
				$portingReportData[$extportingRow['porting_master_id']]['Sales Manager Name'] = $extportingRow['Sales Manager Name'];
				$portingReportData[$extportingRow['porting_master_id']]['Sales Manager Email'] = $extportingRow['Sales Manager Email'];
			}

		}

		if(!empty($ERPortingIds)){

			$salesManagerDetailsQuery2 = "select pm.id , CONCAT( IFNULL( tu.firstname, '' ) , ' ', IFNULL( tu.lastname, '' ) ) AS 'Sales Manager Name', tu.email AS 'Sales Manager Email' from `shiksha`.porting_main as pm Join `SUMS`.Transaction as tr ON (pm.client_id = tr.ClientUserId) left Join `SUMS`.Sums_User_Details as sud ON (tr.SalesBy = sud.EmployeeId) left JOIN `shiksha`.tuser as tu on (sud.userId = tu.userid) where pm.id in (?) and pm.type = 'examResponse'";
			$result2 = $this->dbHandle->query($salesManagerDetailsQuery2,array($ERPortingIds));
			
			foreach($result2->result() as $row) {
				$extportingRow = (array)$row;
				$portingReportData[$extportingRow['id']]['Sales Manager Name'] = $extportingRow['Sales Manager Name'];
				$portingReportData[$extportingRow['id']]['Sales Manager Email'] = $extportingRow['Sales Manager Email'];
			}
			
		}
		
		return $portingReportData;
	}

	public function getConditionsForMultiplePorting($portingIds){
		if(!is_array($portingIds) || count($portingIds)<=0){
			return false;
		}
		//$file = '/tmp/exam_response_porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');
        //fwrite($fp, "getConditionsForMultiplePorting Start ==".time()."\n");
		$this->initiateModel('read','MISTracking');
		$this->dbHandle->select('porting_master_id, key, value');
		$this->dbHandle->from('porting_conditions');
		$this->dbHandle->where_in('porting_master_id',$portingIds);
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		//fwrite($fp, "getConditionsForMultiplePorting End ==".time()."\n");
		//fwrite($fp, "getConditionsForMultiplePorting Query ==".$this->dbHandle->last_query()."\n");
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getActiveExamResSubscription($subscriptionIds){
		if(!is_array($subscriptionIds) || count($subscriptionIds)<=0){
			return false;
		}

		$this->initiateModel('read','MISTracking');
		$this->dbHandle->select('id');
		$this->dbHandle->from('examResponseSubscription');
		$this->dbHandle->where_in('id',$subscriptionIds);
		$this->dbHandle->where('status','active');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		return $result;
	}

	public function getAllLiveExamResponsePortings($ids){
		if(!is_array($ids) || count($ids) <=0){
			return array();
		}
		//$file = '/tmp/exam_response_porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');
        //fwrite($fp, "getAllLiveExamResponsePortings Start ==".time()."\n");
		$status = array('live','intest');
		$this->initiateModel('read','MISTracking');
		$this->dbHandle->select('*');
		$this->dbHandle->from('porting_main');
		$this->dbHandle->where_in('status',$status);
		$this->dbHandle->where_in('id',$ids);
		$this->dbHandle->where('type','examResponse');
		$result = $this->dbHandle->get()->result_array();
		//fwrite($fp, "getAllLiveExamResponsePortings End ==".time()."\n");
		//fwrite($fp, "getAllLiveExamResponsePortings Query ==".$this->dbHandle->last_query()."\n");
		//echo $this->dbHandle->last_query().'<br>';die;
		return $result;
	}

	public function getExamResponses($data){
    	//_p($data);die;

    	// add all validation hare
    	if(!is_array($data['subscriptionIds']) && count($data['subscriptionIds']) <=0 ){
    		return array();
    	}
    	//$file = '/tmp/exam_response_porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');
        //fwrite($fp, "getExamResponses Start ==".time()."\n");

    	$sqlData = array();

        $this->initiateModel('read','MISTracking');

        $sql = "select era.id, era.userId, era.tempLmsId, era.entityValue, tlt.action from examResponseAllocation era inner join tempLMSTable tlt on tlt.id = era.tempLmsId";			        

		//$data['responseTypes'] = array('Others');// temp
		if(is_array($data['responseTypes']) && count($data['responseTypes']) > 0 && ($data['responseTypes'][0] != "All")){
			$hasOtherActionType = 0;
			$hasActionTypes = 0;

			foreach ($data['responseTypes'] as $key => $responseType) {
				if($responseType == 'Others'){
					unset($data['responseTypes'][$key]);
					$hasOtherActionType = 1;
				}else{
					$hasActionTypes = 1;
				}
			}

			$clauses = array();
			if($hasActionTypes == 1){
				$clauses[] = " tlt.action in (?) ";
				$sqlData[] = $data['responseTypes'];
			}
			if($hasOtherActionType == 1){
				$this->load->config('response/responseConfig');
    			global $examResponseGrades;
    			if(count($examResponseGrades) > 0){
    				$examResponseGrades = array_keys($examResponseGrades);
    				$clauses[] =" tlt.action not in (?) ";
    				$sqlData[] = $examResponseGrades;
    			}
			}
			if(count($clauses) > 0) {
				$sql .= " where (".implode(" or ",$clauses).")";
			}
		}
	
		if($hasOtherActionType == 1 || $hasActionTypes == 1){
			$sql .= " and era.entityType = ? ";
		}else{
			$sql .= " where era.entityType = ? ";
		}
        $sqlData[] = 'examGroup';
        $sql .= " and era.subscriptionId in (?) and era.id <= ? ";
        $sqlData[] = $data['subscriptionIds'];
        $sqlData[] = $data['maxERAllocationId'];

		if($portingStatus == 'intest'){
			$sql .= " limit 1";
		}

		if($data['lastPortedId'] > 0){
			$sql .= " and era.id > ?";
			$sqlData[] = $data['lastPortedId'];
		}else{
			if($data['isRunFirstTime'] == 'no'){
				if((!empty($data['startDate'])) && $data['startDate'] != '0000-00-00') {
					$sql .= " and era.allocationTime >= ?";
					$sqlData[] = $data['startDate'].' 00:00:00';
				}else{
					$sql .= " and era.id > ?";
					$sqlData[] = $data['lastERAllocationId'];
				}
			}else{
				$sql .= " and era.id > ?";
				$sqlData[] = $data['lastERAllocationId'];
			}		
		}

		$result = $this->dbHandle->query($sql, $sqlData)->result_array();
		//echo $this->dbHandle->last_query();die;
		//_p($result);die;
		//fwrite($fp, "getExamResponses End ==".time()."\n");
		//fwrite($fp, "getPortingIdsForNewExamResponses Query ==".$this->dbHandle->last_query()."\n");
		return $result;	
    }

    public function updateLastPortedId($portingId, $lastPortedId){
    	if($portingId <1 || $lastPortedId<1){
    		return false;
    	}	
    	$data = array('last_ported_id' => $lastPortedId);
    	$this->initiateModel('write','User');
    	$this->dbHandle->where('id',$portingId);
    	$this->dbHandle->update('porting_main',$data);

    }

	public function getPortingFieldsMapping($portingIds){
		if(is_array($portingIds) && count($portingIds) >0){
			//$file = '/tmp/exam_response_porting_time_log'.date('Y-m-d').".txt";
        	//$fp = fopen($file,'a');
        	//fwrite($fp, "getPortingFieldsMapping Start ==".time()."\n");

			$this->initiateModel('read','MISTracking');
			$this->dbHandle->select('a.porting_master_id  ,a.client_field_name, b.name, b.field_group, a.master_field_id , a.other_value as other_value');
			$this->dbHandle->from('porting_field_mappings a');
			$this->dbHandle->join('porting_masterfield_list b','a.master_field_id = b.id','left');
			$this->dbHandle->where_in('a.porting_master_id',$portingIds);
			$this->dbHandle->where('a.status','live');
			$result = $this->dbHandle->get()->result_array();
			//fwrite($fp, "getPortingFieldsMapping End ==".time()."\n");
			//fwrite($fp, "getPortingIdsForNewExamResponses Query ==".$this->dbHandle->last_query()."\n");
			//echo $this->dbHandle->last_query();die;
			return $result;
		}else{
			return array();
		}
		
	}

	public function getUserExamResponseDetails($examGroupIds){
		if(is_array($examGroupIds) && count($examGroupIds) >0){
			//$file = '/tmp/exam_response_porting_time_log'.date('Y-m-d').".txt";
        	//$fp = fopen($file,'a');
        	//fwrite($fp, "getUserExamResponseDetails Start ==".time()."\n");

			$this->initiateModel('read','MISTracking');
			$this->dbHandle->select('epg.groupId, epg.groupName , epm.name examName');
			$this->dbHandle->from('exampage_groups epg');
			$this->dbHandle->join('exampage_main epm','epm.id = epg.examId','inner');
			$this->dbHandle->where_in('epg.groupId', $examGroupIds);
			$this->dbHandle->where('epm.status','live');
			$this->dbHandle->where('epg.status','live');
			$result = $this->dbHandle->get()->result_array();
			//echo $this->dbHandle->last_query();die;
			//fwrite($fp, "getUserExamResponseDetails End ==".time()."\n");
			//fwrite($fp, "getPortingIdsForNewExamResponses Query ==".$this->dbHandle->last_query()."\n");
			return $result;
		}else{
			return array();		
		}
	}

	public function getLastProcessedId($process){
		if(empty($process)){
			return false;
		}
		$this->initiateModel('read','MISTracking');
		$this->dbHandle->select('lastprocessedid');
		$this->dbHandle->from('SALeadAllocationCron');
		$this->dbHandle->where('process',$process);
	    $result = $this->dbHandle->get()->result_array();
	    //echo $this->dbHandle->last_query().'<br>';die;
	    return $result[0]['lastprocessedid'];    	
    }

    public function updateLastExamResponseProcessedId($recent_processed_id, $process){
    	if($recent_processed_id<1 || empty($process)){
    		return;
    	}

    	$data = array('lastprocessedid' => $recent_processed_id);
    	$this->initiateModel('write','User');
    	$this->dbHandle->where('process',$process);
    	$this->dbHandle->update('SALeadAllocationCron',$data);
    	//echo $this->dbHandle->last_query().'<br>';
    }

    public function getPortingIdsForNewExamResponses($lastERAllocationId){
    	if($lastERAllocationId<1){
    		return array();
    	}
    	//$file = '/tmp/exam_response_porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');
        //fwrite($fp, "getPortingIdsForNewExamResponses Start ==".time()."\n");
    	$this->initiateModel('read','MISTracking');
    	$this->dbHandle->select('esa.id, pm.id portingId');
    	$this->dbHandle->from('examResponseAllocation esa');
    	$this->dbHandle->join('porting_conditions pc','pc.value = esa.subscriptionId and pc.key = "examSubscription"','inner');
    	$this->dbHandle->join('porting_main pm','pc.porting_master_id = pm.id','inner');
    	$this->dbHandle->where_in('pm.status',array('live','intest'));
    	$this->dbHandle->where('pc.status','live');
    	$this->dbHandle->where('esa.id >', $lastERAllocationId);
    	$result = $this->dbHandle->get()->result_array();
    	//fwrite($fp, "getPortingIdsForNewExamResponses End ==".time()."\n");
    	//fwrite($fp, "getPortingIdsForNewExamResponses Query ==".$this->dbHandle->last_query()."\n");
    	//echo $this->dbHandle->last_query().'<br>';die;
    	return $result;
    }

    public function getNewPortingIds($portingType){
    	if(empty($portingType)){
    		return false;
    	}
    	//$file = '/tmp/exam_response_porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');
        //fwrite($fp, "getNewPortingIds Start ==".time()."\n");
    	$this->initiateModel('read','MISTracking');
    	$this->dbHandle->select('id');
    	$this->dbHandle->from('porting_main');
    	$this->dbHandle->where_in('status',array('live','intest'));
    	$this->dbHandle->where('isrun_firsttime', 'no');
    	$this->dbHandle->where('type', 'examResponse');
    	$result = $this->dbHandle->get()->result_array();
    	//fwrite($fp, "getNewPortingIds End ==".time()."\n");
    	//fwrite($fp, "getNewPortingIds Query ==".$this->dbHandle->last_query()."\n");
    	//echo $this->dbHandle->last_query().'<br>';die;
    	return $result;

    }

    public function getUserIdsForEmail($emailIds){
    	if(!is_array($emailIds) || count($emailIds) <=0){
    		return false;
    	}
    	$this->initiateModel('read','MISTracking');
    	$this->dbHandle->select('userid,email');
    	$this->dbHandle->from('tuser');
    	$this->dbHandle->where_in('email',$emailIds);
    	$result = $this->dbHandle->get()->result_array();
    	//echo $this->dbHandle->last_query();die;
    	return $result;
    }

    public function filterLeadFromSALeadMatchingLog($portingId,$userIds){
    	if(!is_array($userIds) || count($userIds) <=0){
    		return false;
    	}

    	$this->initiateModel('read','MISTracking');
    	$this->dbHandle->select('distinct(SALeadML.leadid) leadid');
		$this->dbHandle->from('SALeadMatchingLog SALeadML');
		$this->dbHandle->join('porting_status ps','SALeadML.id = ps.ported_item_id','inner');
		$this->dbHandle->where('ps.porting_master_id', $portingId);
		$this->dbHandle->where_in('SALeadML.leadid',$userIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
    }
    
    public function addUsersToSALeadMatcingLog($userDetails, $searchAgentId, $clientId, $streamId, $subStreamId, $profileType ){
    	$matchingTime = date('Y-m-d H:i:s');
    	//_p($userIds);_p($searchAgentId);_p($clientId);_p($streamId);_p($subStreamId);_p($profileType);_p($matchingTime);die;
    	//echo 'dffdf';_p($userDetails);
    	$this->initiateModel('write','User');

    	$sqlData = array();
    	foreach ($userDetails as $email => $userId) {
    		$sqlData[] = array(
    			'leadid' => $userId,
    			'searchAgentid' => $searchAgentId,
    			'clientid' => $clientId,
    			'stream'	=> $streamId,
    			'substream' => $subStreamId,
    			'matchingTime' => $matchingTime,
    			'ProfileType' => $profileType
    			);
    	}
    	$this->dbHandle->insert_batch('SALeadMatchingLog',$sqlData);

    	$this->dbHandle->select('id, leadid');
    	$this->dbHandle->from('SALeadMatchingLog');
    	$this->dbHandle->where('searchAgentid',$searchAgentId);
    	$result = $this->dbHandle->get()->result_array();
    	//echo $this->dbHandle->last_query();
    	//_p($result);
    	$userDataMapping = array();
    	foreach ($result as $key => $matchigLogData) {
    		$userDataMapping[$matchigLogData['leadid']] = $matchigLogData['id'];
    	}
    	//_p($userDataMapping);die;
    	return $userDataMapping;
    }

    public function updateSearchAgentDeliveryCount($searchAgentId, $deliveryCount){
		$this->initiateModel('write');    	

		$sql = "update SALeadsLeftoverStatus set leads_sent_today= leads_sent_today+ ? where searchagentid=?";
		$this->dbHandle->query($sql, array($deliveryCount, $searchAgentId ));
    }

    public function getCityForVendor($cities,$vendor_name){
    	if (empty($cities) || empty($vendor_name)){
    		return;
    	}
    	$this->initiateModel();    	
		$sql = "select city_name,vendor_entity as vendor_city from countryCityTable join shiksha_vendor_mapping on (city_id = shiksha_entity) and city_name in (?) and vendor_name = ? and entity_type = 'city'";
		return $this->dbHandle->query($sql, array($cities,$vendor_name))->result_array();
    	
    }

    public function getStateForVendor($states,$vendor_name){
    	if (empty($states) || empty($vendor_name)){
    		return;
    	}
    	$this->initiateModel();    	
		$sql = "select state_name,vendor_entity as vendor_state from stateTable join shiksha_vendor_mapping on (state_id = shiksha_entity) and state_name in (?) and vendor_name = ? and entity_type = 'state'";
		return $this->dbHandle->query($sql, array($states,$vendor_name))->result_array();
    	
    }

    public function getAllStreams(){
    	$this->initiateModel('read','Listing');
    	$sql = "select stream_id,name from streams where status = 'live'";
    	return $this->dbHandle->query($sql)->result_array();
    }

    public function getAllBaseCourses(){
    	$this->initiateModel('read','Listing');
    	$sql = "select base_course_id,name from base_courses where status = 'live'";
    	return $this->dbHandle->query($sql)->result_array();
    }

    public function getAllPortingCustomizedFields($clientId){
    	if ($clientId < 0 || empty($clientId)){
    		return;
    	}
    	$this->initiateModel('read','MISTracking');
    	$sql = "select entity_type,entity_id,entity_value from porting_customized_fields where client_id = ? and status = 'live'";
    	return $this->dbHandle->query($sql, $clientId)->result_array();
    }


    public function getPortingIdByDate($date,$portingType){

    	if($date == ""){
    		return 0;
    	} else {
    		$sql ="";
    		$this->initiateModel('read');
    		if($portingType == "lead" || $portingType == "matched_response"){
    			$sql = "select id from SALeadMatchingLog where matchingTime >= ? limit 1";
    		} else if($portingType == "response"){
    			$sql = "select id from tempLMSTable where submit_date >= ? limit 1";
    		}
    		$last_ported_ids =  $this->dbHandle->query($sql, array($date))->result_array();
    		if(!empty($last_ported_ids)){
    			return $last_ported_ids[0]['id'];
    		}
    	}
    }

    public function makePortingTrackingEntry($portingId,$last_ported_id,$last_ported_item_date){
    	$this->initiateModel('write');
    	if($portingId == null){
    		return;
    	} 
    	$sql =  "INSERT INTO `lmsPorting_Tracking` (`porting_id`, `last_ported_id`, `last_ported_item_date`) VALUES (?,?,?)";
    	$this->dbHandle->query($sql, array($portingId,$last_ported_id,$last_ported_item_date));
    	
    }

    public function getLastTempLMSId(){
    	$this->initiateModel('read');
    	$sql ="select id from tempLMSTable order by id desc limit 1";
    	$return = $this->dbHandle->query($sql)->result_array();
    	return $return[0]['id'];

    }


    public function updateLastPortedIdForResponsePorting($maxtempLMSId){
    	$this->initiateModel('write');

    	$sql ="update porting_main set last_ported_id=? where status='live' and type='response'";
    	$this->dbHandle->query($sql,array($maxtempLMSId));
    	
    }


    public function generateBulkQueryForPortingStatus($responses,$serializedData,$portingMasterId,$flag,$portedItemId){
    	if (empty($responses) ||empty($serializedData) ||empty($portingMasterId) ||empty($flag) ||empty($portedItemId) )
    	{
    		return;
    	}
    	$this->initiateModel('write');
	 	$insertQuery = "INSERT INTO porting_status (`porting_master_id`, `request_time`, `ported_item_id`, `response`, `flag`, `sent_data`) VALUES ";

        $maxtempLMSId = 0;
        $chunk = 0;

        foreach ($responses as $index => $clientResponse) {
        	if ($chunk == 500){
        		$query = substr($query, 0,-1);
            	$finalQuery = $insertQuery.$query;
            	$this->dbHandle->query($finalQuery,$inputData);
        		$inputData= array();
        		$query = "";
        		$chunk =0;
            }
            $chunk = $chunk + 1 ;
            $query .=  "(?,?,?,?,?,?),";
            $inputData[] = $portingMasterId;
            $inputData[] = date("Y-m-d H:i:s");
            $inputData[] = $portedItemId[$index];
            if ($maxtempLMSId < $portedItemId[$index]){
            	$maxtempLMSId = $portedItemId[$index];
            }
            $inputData[] = addslashes($clientResponse);
            $inputData[] = $flag;
            $inputData[] = base64_encode($serializedData[$index]);
        }

        if (!empty($inputData) && !empty($query)){
        	$query = substr($query, 0,-1);
        	$finalQuery = $insertQuery.$query;
	    	$this->dbHandle->query($finalQuery,$inputData);

        }
        $this->updateLastPortedIdForPorting($portingMasterId,$maxtempLMSId);
    }

    public function updateLastPortedIdForPorting($portingMasterId,$lastPortedId){
    	if (empty($portingMasterId) || empty($lastPortedId)){;
    		return;
    	}
    	$this->initiateModel('write');
    	$query = "update porting_main set last_ported_id = ? where id = ?";

    	$this->dbHandle->query($query,array($lastPortedId,$portingMasterId));

    }

    public function updateClientDataFromCSV($clientId,$entityType,$entityId,$entityIdValueMapping){
    	$this->initiateModel('write');
    	$this->dbHandle->trans_start();
		$this->updateClientMappingAsHistory($clientId,$entityType,$entityId);
		$this->uploadClientMapping($clientId,$entityType,$entityIdValueMapping);
    	$this->dbHandle->trans_complete();
        if($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function updateClientMappingAsHistory($clientId,$entityType,$entityId){
    	if (empty($clientId) || empty($entityType) || empty($entityId)){
    		return;
    	}
		$sql = "update porting_customized_fields set status = 'history' where client_id = ? and entity_type = ? and entity_id in (?)";
    	$this->dbHandle->query($sql,array($clientId,$entityType,$entityId));
    }


    public function uploadClientMapping($clientId,$entityType,$entityIdValueMapping){
    	
    	if (empty($clientId) || empty($entityType) || empty($entityIdValueMapping)){
    		return;
    	}
    	
    	$query = "INSERT INTO porting_customized_fields (`client_id`,`entity_type`,`entity_id`,`entity_value`) VALUES ";
	 	
        foreach ($entityIdValueMapping as $entityId => $entityValue) {
            $query .=  "(?,?,?,?),";
            $inputData[] = $clientId;
            $inputData[] = $entityType;
            $inputData[] = $entityId;
            $inputData[] = trim($entityValue);
        }
        $query = substr($query, 0,-1);
    	$this->dbHandle->query($query,$inputData);
    }

    	
    public function checkValidClient($clientId){
    	if($clientId <= 0  || empty($clientId)){
    		return;
    	}
    	
    	$this->initiateModel('read');
    	$sql = "select usergroup from tuser where userid = ?";
    	return $this->dbHandle->query($sql,$clientId)->result_array();
    }

}
