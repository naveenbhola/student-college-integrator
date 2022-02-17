<?php

class LeadDashboardModel extends MY_Model
{
	private $dbHandle;
		
	function __construct()
	{
	    parent::__construct('LDB');
	}
		
	private function initiateModel($operation = 'read')
	{
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
			$this->dbHandle = $this->getWriteHandle();
		}
	}
            
        public function getLeadsCreatedByLDBCourses($from, $to)
	{
		mail('teamldb@shiksha.com', 'function -getLeadsCreatedByLDBCourses after Unsubscribe removed from tuserflag', print_r($_SERVER,true));
        return;
		$this->initiateModel();
		$sql = "SELECT c.SpecializationId, c.CourseName, count( DISTINCT a.UserId ) AS UserCount, a.ExtraFlag, c.CategoryId
			FROM tUserPref a, tuserflag b, tCourseSpecializationMapping c, tuser d
			WHERE a.UserId = b.userId
			AND a.UserId = d.userid
			AND b.isLDBUser = 'YES' AND b.isTestUser = 'NO' AND b.isResponseLead = 'NO' 
			AND b.hardbounce = '0' AND b.softbounce = '0' AND b.ownershipchallenged = '0'
			AND b.abused = '0' AND b.mobileverified = '1' AND b.unsubscribe = '0'
			AND a.SubmitDate >= ? AND a.SubmitDate <= ?
			AND a.DesiredCourse = c.SpecializationId
			AND ( a.ExtraFlag IS NULL OR a.ExtraFlag != 'testprep' )
			AND a.DesiredCourse > 1
			AND a.Status = 'live' AND c.Status = 'live'
			AND d.usergroup NOT IN ('sums','enterprise','cms')
			GROUP BY c.SpecializationId";
		// error_log("### here 3".print_r($sql,true));
		$resultArrayAllLeads = $this->dbHandle->query($sql, array($from, $to))->result_array();
		$abroadCategoryIdsArray = array();
		foreach($resultArrayAllLeads as $result){
			if($result['ExtraFlag'] == 'studyabroad' && $result['CategoryId'] !== 1 && ($result['CourseName'] == 'Bachelors' || $result['CourseName'] == 'Masters' || $result['CourseName'] == 'PhD')){
				if(!in_array($result['CategoryId'],$abroadCategoryIdsArray)){
					$abroadCategoryIdsArray[] = $result['CategoryId'];
				}
			}
		}
		$studyAbroadCoursesArray = array();		
		if(!empty($abroadCategoryIdsArray)){
			$sqlStudyAbroad = "SELECT boardId, name FROM categoryBoardTable
					   WHERE boardId IN (" . implode(',',$abroadCategoryIdsArray) .")
					   AND flag = 'studyabroad' AND isOldCategory = '0'";
			// error_log("### here 4".print_r($sqlStudyAbroad,true));
			$resultArrayStudyAbroadCourses = $this->dbHandle->query($sqlStudyAbroad)->result_array();
			foreach($resultArrayStudyAbroadCourses as $studyAbroadCourse){
				$studyAbroadCoursesArray[$studyAbroadCourse['boardId']]['BoardId'] = $studyAbroadCourse['boardId'];
				$studyAbroadCoursesArray[$studyAbroadCourse['boardId']]['CategoryName'] = $studyAbroadCourse['name'];
			}
		}
		
		$sql2 = "SELECT DISTINCT a.UserId, a.PrefId
				FROM tUserPref a, tuserflag b, tuser c
				WHERE a.UserId = b.userId
				AND a.UserId = c.userid
				AND b.isLDBUser = 'YES' AND b.isTestUser = 'NO' AND b.isResponseLead = 'NO' 
				AND b.hardbounce = '0' AND b.softbounce = '0' AND b.ownershipchallenged = '0'
				AND b.abused = '0' AND b.mobileverified = '1' AND b.unsubscribe = '0'
				AND a.SubmitDate >= ? AND a.SubmitDate <= ?
				AND a.ExtraFlag = 'testprep'
				AND a.Status = 'live'
				AND c.usergroup NOT IN ('sums','enterprise','cms')";
		// error_log("### here 5".print_r($sql2,true));
		$resultArrayTestPrepLeads = $this->dbHandle->query($sql2, array($from, $to))->result_array();
		$prefIdsArray = array();
		foreach($resultArrayTestPrepLeads as $result){
			if(!in_array($result['PrefId'],$prefIdsArray)){
				$prefIdsArray[] = $result['PrefId'];
			}
		}
		$testPrepCoursesArray = array();
		if(!empty($prefIdsArray)){
			$sqlTestPrep = "SELECT a.prefid, b.blogTitle
					FROM `tUserPref_testprep_mapping` a, blogTable b
					WHERE a.blogId = b.blogid
					AND a.status = 'live'
					AND b.status != 'draft'
					AND a.prefid IN (". implode(',',$prefIdsArray).")";
			error_log("### here 6".print_r($sqlTestPrep,true));
			$resultArrayTestPrepCourses = $this->dbHandle->query($sqlTestPrep)->result_array();
			foreach($resultArrayTestPrepCourses as $testPrepCourse){
				$testPrepCoursesArray[$testPrepCourse['prefid']]['PrefId'] = $testPrepCourse['prefid'];
				$testPrepCoursesArray[$testPrepCourse['prefid']]['BlogTitle'] = $testPrepCourse['blogTitle'];
			}
		}
		
		$returnArray['AllLeads'] = $resultArrayAllLeads;
		$returnArray['TestPrepLeads'] = $resultArrayTestPrepLeads;
		$returnArray['TestPrepCourses'] = $testPrepCoursesArray;
		$returnArray['StudyAbroadCourses'] = $studyAbroadCoursesArray;
		return $returnArray;
	}
	
	public function getLeadsConsumedByLDBCourses($from, $to)
	{
		$this->initiateModel();
		$sql = "SELECT b.SpecializationId, b.CourseName, count( DISTINCT a.UserId ) as UserCount, a.ExtraFlag, b.CategoryId
			FROM tUserPref a, tCourseSpecializationMapping b, LDBLeadContactedTracking c
			WHERE a.UserId = c.UserId
			AND c.ContactDate >= ? AND c.ContactDate <= ?
			AND a.DesiredCourse = b.SpecializationId 
			AND ( a.ExtraFlag IS NULL OR a.ExtraFlag != 'testprep' )
			AND a.DesiredCourse > 1
			AND a.Status = 'live' AND b.Status = 'live'
			GROUP BY b.SpecializationId";
		// error_log("### here 7".print_r($sql,true));
		$resultArrayAllLeads = $this->dbHandle->query($sql, array($from, $to))->result_array();
		$abroadCategoryIdsArray = array();
		foreach($resultArrayAllLeads as $result){
			if($result['ExtraFlag'] == 'studyabroad' && $result['CategoryId'] !== 1 && ($result['CourseName'] == 'Bachelors' || $result['CourseName'] == 'Masters' || $result['CourseName'] == 'PhD')){
				if(!in_array($result['CategoryId'],$abroadCategoryIdsArray)){
					$abroadCategoryIdsArray[] = $result['CategoryId'];
				}
			}
		}
		$studyAbroadCoursesArray = array();
		if(!empty($abroadCategoryIdsArray)){
			$sqlStudyAbroad = "SELECT boardId, name FROM categoryBoardTable
					   WHERE boardId IN (" . implode(',',$abroadCategoryIdsArray) .")
					   AND flag = 'studyabroad' AND isOldCategory = '0'";
			// error_log("### here 8".print_r($sqlStudyAbroad,true));
			$resultArrayStudyAbroadCourses = $this->dbHandle->query($sqlStudyAbroad)->result_array();
			foreach($resultArrayStudyAbroadCourses as $studyAbroadCourse){
				$studyAbroadCoursesArray[$studyAbroadCourse['boardId']]['BoardId'] = $studyAbroadCourse['boardId'];
				$studyAbroadCoursesArray[$studyAbroadCourse['boardId']]['CategoryName'] = $studyAbroadCourse['name'];
			}
		}
		
		$sql2 = "SELECT DISTINCT a.UserId, a.PrefId
				FROM tUserPref a, LDBLeadContactedTracking b
				WHERE a.UserId = b.UserId
				AND b.ContactDate >= ? AND b.ContactDate <= ?
				AND a.ExtraFlag = 'testprep'
				AND a.Status = 'live'";
		// error_log("### here 9".print_r($sql2,true));
		$resultArrayTestPrepLeads = $this->dbHandle->query($sql2, array($from, $to))->result_array();
		$prefIdsArray = array();
		foreach($resultArrayTestPrepLeads as $result){
			if(!in_array($result['PrefId'],$prefIdsArray)){
				$prefIdsArray[] = $result['PrefId'];
			}
		}
		$testPrepCoursesArray = array();
		if(!empty($prefIdsArray)){
			$sqlTestPrep = "SELECT a.prefid, b.blogTitle
					FROM `tUserPref_testprep_mapping` a, blogTable b
					WHERE a.blogId = b.blogid
					AND a.status = 'live'
					AND b.status != 'draft'
					AND a.prefid IN (". implode(',',$prefIdsArray).")";
			// error_log("### here 10".print_r($sqlTestPrep,true));
			$resultArrayTestPrepCourses = $this->dbHandle->query($sqlTestPrep)->result_array();
			foreach($resultArrayTestPrepCourses as $testPrepCourse){
				$testPrepCoursesArray[$testPrepCourse['prefid']]['PrefId'] = $testPrepCourse['prefid'];
				$testPrepCoursesArray[$testPrepCourse['prefid']]['BlogTitle'] = $testPrepCourse['blogTitle'];
			}
		}
		$returnArray['AllLeads'] = $resultArrayAllLeads;
		$returnArray['TestPrepLeads'] = $resultArrayTestPrepLeads;
		$returnArray['TestPrepCourses'] = $testPrepCoursesArray;
		$returnArray['StudyAbroadCourses'] = $studyAbroadCoursesArray;
		return $returnArray;
	}
	
	public function manual_script_tracking($tracking_data) {
		$this->initiateModel('write');
		$sql = "INSERT INTO manual_script_tracking (script_name, user_ids, users_count, porting_type, data_type, data) VALUES (?, ?, ?, ?, ?, ?)";
		$this->dbHandle->query($sql, array($tracking_data['script_name'], $tracking_data['user_ids'], $tracking_data['users_count'], $tracking_data['porting_type'], $tracking_data['data_type'], $tracking_data['data']));		
	}
		
	/*
	 *Function to get ClientID and leads
	 *Return : array of clientId and no. of leads
	 */
	public function getClientLeadDetails()	{
		$this->initiateModel();
	
		$sql = "SELECT sa.clientid, count( DISTINCT la.id ) as leads FROM SASearchAgent sa ".
			"inner join SALeadAllocation la on sa.searchagentid=la.agentid ".
			"WHERE la.`agentid` in (271,384,493,494,495,558,559,560,561,562,389, ".
			"674,675,688,842,843,845,563,940,942,944,945,612,1167,1053,1234,1246,1247 ".
			",1284,1367, 1387,1388,1401,1435,1460,1482,1483,1484,1485,1486,1487,1360,1358, ".
			"1608,1609,1610,1611,1612,1613,1634,1658,1681,1192,1734,1735,1736,1760,1190,1931, ".
			"1932,1933,1934,1935,1936,1937,1930,1697,1191,1193,1194,1195,1196,1197,1943,2041, ".
			"2042,2067,1175,2136,2166,2167,2169,2170,2171,2240,2272,518,519,452,2485,2103,2898, ".
			"3023,3022,3021,3033,3176,3429,3613,2273,2275,3718,3871,3872,3938,3957,3958,3633,3324, ".
			"3325,4201,4218,4508,4510,4105,4104,4550,3753,497,4850,4827,4826,4825,4824,4823,4822,5009, ".
			"5043,5045,2463,5143,5063,5062,5168,4765,5229,4281,5032,5033,5039,5040,5683,5684,4854,5252, ".
			"5567,5902,5565,3034,4881,5995,5553,5842,6049,1566,6110,5095,5094,5093,5092,6125,6105,6175, ".
			"4233,6107,6106,5993,5992,1894,1893,5423,5424,5425,5426,5444,5445,5446,5448,5449,5451,5458,5462, ".
			"6467,6424,6593,6575,6574,6573,6563,6054,6665,6623,6669,6761,6762,6763,6767,6802,6801,6651,6889,6890, ".
			"6891,6980,6981,6993,7020,2866,4173,4174,7095,4488,4489,4491,4490,6606,6748,7288,6618,6616,5677,7420, ".
			"2853,5037,5036,7523,7524,7525,7526,4579,4580,4581,4582,7761,5895,5896,6840,7246,8077,8094,8090,8089, ".
			"8316,8317,8355,3020,3024,8467,8554,8555,8556,8557,8563,8565,5381,6178,8800,8801,8802,8803,8804,8805, ".
			"6365,8815,8630,8960,6026,8978,8979,8980,8693,8564,8567,8566,9021,9043,8078,8899,8898,8897,8896,8895, ".
			"8894,7003,9070,9071,9078,9141,9179,9142,9143,9065,9064,9063,9062,9061,9183,9184,6102,9208,9220,9263, ".
			"9288,9289,9306,1856,9372,9373,9374,9375,8954,9409,9410,9412,6046,9483,9484,9577,9144,9572,9145,9571, ".
			"9146,9570,9147,9569,9148,9568,9149,9567,9565,9150,9564,9151,9152,9562,9153,9561,9154,9559,9155,9558, ".
			"9620,9639,9640,9641,9642,9643,9644,9730,9731,9732,9733,9734,9738,9864,9865,9866,9867,9874,9902,9929, ".
			"9544,9545,9977,9978,10017,10020,6940,5253,6959,5230,6960,6958,9621,9619,9618,5900,5899,5901,9376,2837, ".
			"4782,4781,4780,6025,6101,6108,9599,9912,1513,1512,2706,1750,4642,6258,5696,6283,2705,7750,7749,1573) and la.allocationtime <= '2014-12-16 13:00:00'".
			"group by sa.clientid";
	
		$resultArrayAllClients = $this->dbHandle->query($sql)->result_array();
		
		return $resultArrayAllClients;
	
	}
	
	/*
	 *Function to get Subcription details and no. of credits remaining with the client
	 *Param : List of clientID mapped with no. of leads
	 *Return : Array with all data of client
	 */	
	public function getClientLeadSubscriptionDetails($resultArrayAllClients = array()){
		  //mail added for code scan safe check    
        mail('teamldb@shiksha.com', 'function -getLeadDashboardData', 'Cron to get lead data');
        return;
        
		$all_clients = array();
		$all_client_data = array();
		
		foreach($resultArrayAllClients as $result){
	
			$all_clients[] = $result['clientid'];
			
		}
		if(!empty($all_clients)) {
			$imp_all_clients = implode(",",$all_clients);
			$dbHandle = $this->getReadHandleByModule('SUMS');
			$sql2 = "SELECT s.ClientUserId, s.SubscriptionId, s.NavSubscriptionId, spm.BaseProdRemainingQuantity ".
				"FROM `Subscription_Product_Mapping` spm INNER JOIN Subscription s ON spm.subscriptionid ".
				"= s.subscriptionid WHERE s.`ClientUserId` in (".$imp_all_clients.") AND spm.`BaseProductId` ".
				"IN (127) and spm.status = 'ACTIVE' and spm.SubscriptionEndDate >= NOW()";
		
			$resultArrayTestPrepLeads = $dbHandle->query($sql2)->result_array();
			
			foreach ($resultArrayTestPrepLeads as $result){
				$all_client_data[$result['ClientUserId']]['client_id'] = $result['ClientUserId'];
				$data = $this->getClientNames($result['ClientUserId']);
				//$all_client_data[$key]['orderID'] = $result['NavSubscriptionId'];
				$all_client_data[$result['ClientUserId']]['client-name'] = $data['name'];
				$all_client_data[$result['ClientUserId']]['email'] = $data['Email'];
				$all_client_data[$result['ClientUserId']]['mobile'] = $data['Mobile'];
				foreach($resultArrayAllClients as $key){
					if($key['clientid'] == $result['ClientUserId']){
						$all_client_data[$result['ClientUserId']]['leads'] = $key['leads'];
					}
				}
				$all_client_data[$result['ClientUserId']]['subscription_id'] = $result['SubscriptionId'];
				if($result['BaseProdRemainingQuantity']>=40){
					$all_client_data[$result['ClientUserId']]['remaining_credits'] += $result['BaseProdRemainingQuantity'];
				}else{
					$all_client_data[$result['ClientUserId']]['remaining_credits'] += 0;
				}
				
			}
	
		}
		
		return $all_client_data;
	}
	
	public function getClientNames($clientID){
		$this->initiateModel();
		
		$sql = "SELECT CONCAT(IFNULL( firstname, '' ), ' ', IFNULL( lastname, '' )) as name , email as Email,
		mobile as Mobile FROM `tuser` WHERE `userid` = ?";
		$name = $this->dbHandle->query($sql,$clientID)->result_array();
		
		return $name[0];
	}
	
	public function getMBALeadsGenerated($from, $to)
	{
		mail('teamldb@shiksha.com', 'function -getMBALeadsGenerated after Unsubscribe removed from tuserflag', print_r($_SERVER,true));
        return;
		$this->initiateModel();
		$this->benchmark->mark('query_start');
		
		$sql = "SELECT count( DISTINCT a.UserId ) as UserCount
			FROM tUserPref a, tuserflag b, tuser c
			WHERE a.UserId = b.userId
			AND a.UserId = c.userid
			AND b.isLDBUser = 'YES' AND b.isTestUser = 'NO' AND b.isResponseLead = 'NO' 
			AND b.hardbounce = '0' AND b.softbounce = '0' AND b.ownershipchallenged = '0'
			AND b.abused = '0' AND b.mobileverified = '1' AND b.unsubscribe = '0'
			AND a.SubmitDate >= ? AND a.SubmitDate <= ?
			AND a.DesiredCourse = 2
			AND ( a.ExtraFlag IS NULL OR a.ExtraFlag != 'testprep' OR a.ExtraFlag != 'studyabroad' )
			AND c.usergroup NOT IN ('sums','enterprise','cms') ";
			
		// error_log("### place 1 ".print_r($sql,true));
		
		$resultArrayAllLeads = $this->dbHandle->query($sql, array($from, $to))->result_array();
		$returnArray = $resultArrayAllLeads;
		
		$this->benchmark->mark('query_end');
		error_log("###total time taken to run query for MBA Leads:::".$this->benchmark->elapsed_time('query_start', 'query_end')."seconds");
		register_shutdown_function("self::printErrorStack");
		
		return $returnArray;
	}
	
	public function getMBAResponsesGenerated($type, $from, $to)
	{
		mail('teamldb@shiksha.com', 'function -getMBAResponsesGenerated after Unsubscribe removed from tuserflag', print_r($_SERVER,true));
        return;
		$this->initiateModel();
		$this->benchmark->mark('query_start');
		
		$sql = "SELECT count( DISTINCT a.UserId ) as UserCount
			FROM tempLMSTable a, tuserflag b, tuser c, categoryPageData d
			WHERE a.userId = b.userId
			AND a.userId = c.userid
			AND b.isTestUser = 'NO'
			AND a.listing_type = 'course'
			AND a.listing_type_id = d.course_id
			AND d.category_id = 23 AND d.status = 'live'
			AND b.hardbounce = '0' AND b.softbounce = '0' AND b.ownershipchallenged = '0'
			AND b.abused = '0' AND b.mobileverified = '1' AND b.unsubscribe = '0'
			AND a.submit_date >= ? AND a.submit_date <= ?
			AND a.listing_subscription_type = ?
			AND c.usergroup NOT IN ('sums','enterprise','cms') ";
			
		// error_log("### place 2 ".print_r($sql,true));
		
		$resultArrayAllResponses = $this->dbHandle->query($sql, array($from, $to, $type))->result_array();
		$returnArray = $resultArrayAllResponses;
		
		$this->benchmark->mark('query_end');
		error_log("###total time taken to run query for MBA Responses:::".$this->benchmark->elapsed_time('query_start', 'query_end')."seconds");
		register_shutdown_function("self::printErrorStack");
		
		return $returnArray;
		
	}
	
}

?>
