<?php

/**
 * Description
 * @author
 */
class smartmodel extends MY_Model {

	private $db = null;

	function __construct() {
		parent::__construct();
	}

	private function initiateModel($mode = "write", $module = '') {
		if($mode == 'read') {
			$this->db = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		}
		else {
			$this->db = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	function getSODataByUserId($userid)
	{
		$this->initiateModel('read','SUMS');
    	$query = "SELECT  t.nav_transaction_id as SalesOrderNumber,t.ClientUserId as ClientUserId ,    s.subscriptionid as SubcriptionId, t.transactTime as TransactionTime ,bp.BaseProdCategory as BaseProdCategory	, bp.BaseProdSubCategory as BaseProdSubCategory,spm.`TotalBaseProdQuantity` as TotalQuantity,spm.`BaseProdRemainingQuantity` as RemainingQuantity,spm.`SubscriptionStartDate` as SubscriptionStartDate ,spm.SubscriptionEndDate as SubscriptionEndDate ,s.nav_subscription_line_no as LineItemNumber , spm.status as SubscriptionStatus, t.SalesBy as EmployeeId FROM Subscription_Product_Mapping spm,Transaction t, Subscription s, Base_Products bp WHERE spm.subscriptionid = s.subscriptionid AND spm.`BaseProductId` = bp.`BaseProductId`  AND t.TransactionId = s.transactionId and t.nav_transaction_id !='0' and t.clientUserId IN (?)";

		$result = $this->db->query($query,array($userid))->result_array();
		return $result;
	}


	function getSOUserId($email)
	{
		$this->initiateModel('read','User');
		$query = "select userid,email from tuser where email IN (?)";
    	$result = $this->db->query($query,array($email))->result_array();
    	return $result;
	}

	function getUserData($SalesOrderNumber)
	{
		$this->initiateModel('read','SUMS');
		$query = "select distinct clientuserid from Transaction t where t.nav_transaction_id IN (?)";

		$result = $this->db->query($query,array($SalesOrderNumber))->result_array();
		return $result;
	}

	function getClientEmailIds($userid)
	{
		$this->initiateModel('read','User');
		$query = "select email,userid from tuser where userid IN (?)";

		$result = $this->db->query($query,array($userid))->result_array();
		return $result;
	}

	function getSOData($SalesOrderNumber)
	{
		$this->initiateModel('read','SUMS');
		$query = "SELECT  t.nav_transaction_id as SalesOrderNumber,t.ClientUserId as ClientUserId, s.SubscriptionId as SubcriptionId, t.transactTime as TransactionTime,bp.BaseProdCategory as BaseProdCategory	, bp.BaseProdSubCategory as BaseProdSubCategory,spm.`TotalBaseProdQuantity` as TotalQuantity,spm.`BaseProdRemainingQuantity` as RemainingQuantity,spm.`SubscriptionStartDate` as SubscriptionStartDate ,spm.SubscriptionEndDate as SubscriptionEndDate ,s.nav_subscription_line_no as LineItemNumber , spm.status as SubscriptionStatus, t.SalesBy as EmployeeId FROM Subscription_Product_Mapping spm,Transaction t,Subscription s,Base_Products bp WHERE spm.subscriptionid = s.subscriptionid AND spm.`BaseProductId` = bp.`BaseProductId`  AND t.TransactionId = s.transactionId  and t.nav_transaction_id IN (?)";

		$result = $this->db->query($query,array($SalesOrderNumber))->result_array();
		return $result;
	}

	function getClientsForSalesPerson($userIds) {
		$this->initiateModel("read","SUMS");

		$returnArr = array();

		if(count($userIds) == 0) {
			return $returnArr;
		}

		$sql = "select client.ClientUserId, sums.userId from (SELECT T1.ClientUserId, T1.SalesBy FROM Transaction T1 LEFT JOIN Transaction T2 ON ( T1.ClientUserId = T2.ClientUserId AND T1.TransactionId < T2.TransactionId ) WHERE T2.TransactionId IS NULL) as client, Sums_User_Details as sums, shiksha.tuser as t where sums.EmployeeId = client.SalesBy and sums.userId in (?) and client.ClientUserId = t.userid and t.usergroup = 'enterprise'";
		
		$results = $this->db->query($sql,array($userIds))->result_array();
		
		foreach($results as $result) {
			$returnArr['clientsArray'][] 							  = $result['ClientUserId'];
			$returnArr['executiveClientMapping'][$result['userId']][] = $result['ClientUserId'];
		}
		

		// if($list) {
		// 	foreach($results as $result) {
		// 		$returnArr[] = $result['ClientUserId'];
		// 	}
		// }
		// else {
		// 	foreach($results as $result) {
		// 		$returnArr[$result['userId']][] = $result['ClientUserId'];
		// 	}
		// }
		return $returnArr;
	}

	function canUserAccessSmartInterface($userid) {
		$this->initiateModel("read","User");

		$query = $this->db->query("SELECT id FROM sales_hierarchy WHERE `status` = 'ACTIVE' and userId =?", array($userid));

		$totalRows = $query->num_rows();
		if($totalRows > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	function getSalesPersonForClients($userIds) {
		$this->initiateModel("read","SUMS");

		$returnArr = array();

		if(count($userIds) == 0) {
			return $returnArr;
		}
		
		$sql = "select client.ClientUserId, sums.userId from ".
				"(SELECT T1.ClientUserId, T1.SalesBy FROM Transaction T1 LEFT JOIN Transaction T2 ON ( T1.ClientUserId = T2.ClientUserId AND T1.TransactionId < T2.TransactionId ) WHERE T2.TransactionId IS NULL AND T1.ClientUserId IN (?)) as client, ".
				"Sums_User_Details as sums where sums.EmployeeId = client.SalesBy";
		
		//$sql = "SELECT executive_userid,client_userid FROM sales_executives_clients_mapping WHERE Is_Pair_ACTIVE = 'ACTIVE' and client_userid IN (".implode(',',$userIds).")";

		$results = $this->db->query($sql,array($userIds))->result_array();
		
		foreach($results as $result) {
			$returnArr['executivesArray'][] 								= $result['userId'];
			$returnArr['clientExecutiveMapping'][$result['ClientUserId']][] = $result['userId'];
		}

		// if($list) {
		// 	foreach($results as $result) {
		// 		$returnArr[] = $result['userId'];
		// 	}
		// }
		// else {
		// 	foreach($results as $result) {
		// 		$returnArr[$result['ClientUserId']] = $result['userId'];
		// 	}
		// }
		return $returnArr;
	}

	function getSalesUserType($userid) {
		$this->initiateModel("read","User");

		$query = $this->db->query("SELECT role FROM sales_hierarchy WHERE `status` = 'ACTIVE' and userId =?", array($userid));
		$results = $query->result_array();

		foreach($results as $result) {
			if(trim($result['role']) == 'Business Head/Admin') {
				$salesUserType = 'Admin';
			}
			elseif(trim($result['role']) == 'Sales Branch Head' || trim($result['role']) == 'Sales Manager') {
				$salesUserType = 'Manager';
			}
			elseif(trim($result['role']) == 'Sales Executive' || trim($result['role']) == 'Executive') {
				$salesUserType = 'Executive';
			}
		}
		return $salesUserType;
	}
	
	function getClientType($userId)
	{
		$this->initiateModel("read","User");
		
		$sql = "SELECT DISTINCT b.institute_type FROM listings_main a,institute b WHERE a.listing_type = 'institute' AND a.listing_type_id = b.institute_id AND a.status = 'live' AND b.status = 'live' AND a.username = ?";
		$query = $this->db->query($sql,array($userId));
		$rows = $query->result_array();
		
		$isAbroad = FALSE;
		$isNational = FALSE;
		
		foreach($rows as $row) {
			if($row['institute_type'] == 'Department' || $row['institute_type'] == 'Department_Virtual') {
				$isAbroad = TRUE;
			}
			else {
				$isNational = TRUE;
			}
		}
		
		if($isAbroad && $isNational) {
			return 'Both';
		}
		else if($isAbroad) {
			return 'Abroad';
		}
		else {
			return 'National';
		}
	}

	function getInstitutesForClients($userIds, $list = True) {
		$this->initiateModel("read","User");

		$returnArr = array();

		if(count($userIds) == 0){
			return $returnArr;
		}

		$sql = $this->db->query("SELECT username,listing_type_id,listing_title,listing_type FROM listings_main WHERE status = 'live' and listings_main.listing_type in ('institute','university_national') and username IN (".implode(',',$userIds).")");

		$results = $sql->result_array();

		if($list) {
			foreach($results as $result) {
				$returnArr[] = $result['listing_type_id'];
			}
		}
		else {
			foreach($results as $result) {
				if($result['listing_type'] == 'university_national') {
					$returnArr[$result['username']][$result['listing_type_id']] = $result['listing_title'].'(University)';
				} else {
					$returnArr[$result['username']][$result['listing_type_id']] = $result['listing_title'];
				}
			}
		}
		return $returnArr;
	}

	function getCourseAndInstituteName($courseIds, $list = True){
		$this->initiateModel("read","User");

		$returnArr = array();

		if(count($courseIds) == 0) {
			return $returnArr;
		}

		$sql = "SELECT cd.primary_id as institute_id,cd.course_id,cd.name as courseTitle,lm.listing_title,lm.username,lm.listing_type ".
			"FROM shiksha_courses cd, listings_main lm WHERE lm.listing_type_id = cd.primary_id AND lm.listing_type in ('institute','university_national') AND lm.status = 'live' AND cd.status = 'live' AND cd.course_id IN (?) ";

		$results = $this->db->query($sql,array($courseIds))->result_array();

		if($list) {
			foreach ($results as $row) {
				$returnArr[$row['course_id']]['instituteId'] = $row['institute_id'];
				if($row['listing_type'] == 'university_national') {
					$returnArr[$row['course_id']]['instituteTitle'] = $row['listing_title'].'(University)';
				} else {
					$returnArr[$row['course_id']]['instituteTitle'] = $row['listing_title'];
				}
				$returnArr[$row['course_id']]['courseTitle'] = $row['courseTitle'];
				$returnArr[$row['course_id']]['clientId'] = $row['username'];
			}
		}
		else{
			foreach ($results as $row) {
				if($row['listing_type'] == 'university_national') {
					$returnArr[$row['institute_id']]['instituteTitle'] = $row['listing_title'].'(University)';
				} else {
					$returnArr[$row['institute_id']]['instituteTitle'] = $row['listing_title'];
				}
			}
			
		}
		
		
		return $returnArr;
	}
	
	function getCourseAndUniversityName($courseIds, $list = True)
	{
		$this->initiateModel("read","User");

		$returnArr = array();

		if(count($courseIds) == 0) {
			return $returnArr;
		}

		$sql = "SELECT um.university_id,cd.course_id,cd.courseTitle,lm.listing_title,lm.username ".
				"FROM course_details cd, institute_university_mapping um, listings_main lm ".
				"WHERE um.institute_id = cd.institute_id ".
				"AND um.university_id = lm.listing_type_id ".
				"AND lm.listing_type = 'university' ".
				"AND lm.status = 'live' ".
				"AND um.status = 'live' ".
				"AND cd.status = 'live' ".
				"AND cd.course_id IN (?) ";

		$results = $this->db->query($sql,array($courseIds))->result_array();

		if($list) {
			foreach ($results as $row) {
				$returnArr[$row['course_id']]['instituteId'] = $row['university_id'];
				$returnArr[$row['course_id']]['instituteTitle'] = $row['listing_title'];
				$returnArr[$row['course_id']]['courseTitle'] = $row['courseTitle'];
				$returnArr[$row['course_id']]['clientId'] = $row['username'];
			}
		}
		else{
			foreach ($results as $row) {
				$returnArr[$row['university_id']]['instituteTitle'] = $row['listing_title'];
			}
			
		}
		
		
		return $returnArr;
	}
	
	function getResponseByLocation($courses,$startDate,$endDate,$interval,$list = false)
	{
		$this->initiateModel("read","User");
		
		if(count($courses) == 0) {
			return array();
		}

		$sql = "SELECT listing_type_id, city_name, DATE( submit_date ) AS date,
		COUNT( * ) as responses FROM  `tempLMSTable`
		LEFT JOIN tuser ON (tempLMSTable.userId = tuser.userid) 
		LEFT JOIN countryCityTable ON (tuser.city = countryCityTable.city_id )
		WHERE submit_date >= ? AND submit_date <=  ? 
		and listing_type = 'course' 
                AND listing_subscription_type='paid' 
		AND listing_type_id IN ( ? ) 
		GROUP BY listing_type_id, countryCityTable.city_id, `date` order by date ";
		$final_end_date = $endDate.' 23:59:59';
		$query = $this->db->query($sql, array($startDate, $final_end_date, $courses));
		$results = $query->result_array();
		
		$responses = array();
		if($list){
			foreach($results as $result) {
				$responses[$result['city_name']] += $result['responses'];
				
			}
			return $responses;
		}
		else {
			foreach($results as $result) {
			$responses[$result['listing_type_id']][$result['date']][$result['city_name']] = $result['responses'];
			}
		
			$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);
			return $responsesCount;
		}
	}
	
	//
	// Lead Genie Data
	//
	
	function getLeadGenieData($searchAgents,$startDate,$endDate,$interval,$userIds)
	{
		$this->initiateModel("read","User");
		
		if(count($searchAgents) == 0) {
			return array();
		}
		
		$sql = "SELECT LA.agentid, LA.userid, l.CreditConsumed, date( LA.allocationtime ) AS allocationdate
			FROM SALeadAllocation AS LA, LDBLeadContactedTracking l
			WHERE LA.allocationtime >= ?
			AND LA.allocationtime <= ?
			AND LA.userid = l.userid
			AND l.ClientId IN ( ? )
			AND LA.agentid in ( ? )
			AND l.ContactType = 'view'
			AND LA.auto_download = 'YES'
			AND l.activity_flag = 'SA'";
		$final_start_date = $startDate.' 00:00:00';
		$final_end_date = $endDate.' 23:59:59';
		$query = $this->db->query($sql, array($final_start_date, $final_end_date, $userIds, $searchAgents));
		$results = $query->result_array();
		
		$responses = array();
		$users = array();
		foreach($results as $result) {
			if(!$users[$result['userid']] && $result['CreditConsumed'] > 0) {
				$responses[$result['agentid']][$result['allocationdate']]['leads'] += 1;
				$responses[$result['agentid']][$result['allocationdate']]['credits'] += $result['CreditConsumed'];
				$users[$result['userid']] = TRUE;
			}
		}
		
		$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);
		
		return $responsesCount;
	}
	
	/*
	 *  Search Agent log query
	 */
	

	function getSAMatchingLog($searchAgents,$startDate,$endDate,$interval)
	{
		$this->initiateModel("read","User");
		
		
		$sql = "SELECT SALM.searchAgentid as Sa_ID, COUNT( leadId ) AS count_leads, date( matchingTime ) AS date
			FROM SALeadMatchingLog AS SALM
			WHERE matchingTime >= ?
			AND matchingTime <= ?
			AND SALM.searchAgentid in ( ? )
			GROUP BY SALM.searchagentid, date
			ORDER BY date";
		$final_start_date = $startDate.' 00:00:00';
		$final_end_date = $endDate.' 23:59:59';
		
		$query = $this->db->query($sql, array($final_start_date, $final_end_date, $searchAgents));
		$results = $query->result_array();

		$responses = array();
		foreach($results as $result) {
			
			$responses[$result['Sa_ID']][$result['date']]['leadsMatched'] = $result['count_leads'];
			
		}
		
		$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);
		
		return $responsesCount;
	}
	
	
	

	function getResponseByAction($courses,$startDate,$endDate,$interval)
	{
		$this->initiateModel("read","User");
		
		if(count($courses) == 0) {
			return array();
		}

		$sql = "SELECT listing_type_id, action, DATE( submit_date ) AS `date`, COUNT( * ) as responses
			FROM  `tempLMSTable`
			WHERE submit_date >= ?
                        AND listing_subscription_type='paid' 
			AND submit_date <= ? 
			and listing_type = 'course'
			AND listing_type_id
			IN ( ? )
			GROUP BY listing_type_id, action, `date` order by `date`";
		$final_end_date = $endDate.' 23:59:59';

		$query = $this->db->query($sql, array($startDate, $final_end_date, $courses));
		$results = $query->result_array();

		$responses = array();
		foreach($results as $result) {
			$responses[$result['listing_type_id']][$result['date']][$result['action']] = $result['responses'];
		}
		
		$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);

		return $responsesCount;
	}

	function getTotalResponses($courses,$startDate,$endDate,$interval,$list = 'course'){
		$this->initiateModel("read","User");
		
		if(count($courses) == 0) {
			return array();
		}
				
		$sql = "SELECT listing_type_id, DATE( submit_date ) AS `date`, COUNT( * ) as responses
			FROM  `tempLMSTable`
			WHERE submit_date >= ?
                        AND listing_subscription_type='paid'  
			AND submit_date <=  ?
			and listing_type = 'course'
			AND listing_type_id
			IN ( ? )
			GROUP BY listing_type_id, `date`";
		$final_end_date = $endDate.' 23:59:59';
		$query = $this->db->query($sql, array($startDate, $final_end_date, $courses));
		$results = $query->result_array();

		$responses = array();
		if($list == 'periodwise') {
			foreach($results as $result) {
				if(empty($responses[$result['date']])) {
					$responses[$result['date']][0] = $result['responses'];
				}
				else {
					$responses[$result['date']][0] += $result['responses'];
				}
			}
			
			return $responses;
		}
		elseif($list == 'institutewise'){
			foreach($results as $result) {
				$responses[0] += $result['responses'];
			}
			return $responses;
		}
		else {
			foreach($results as $result) {
				$responses[$result['listing_type_id']][$result['date']] = $result['responses'];
			}
			
			$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);
			
			return $responsesCount;
		}
	}

	function getQuestionsForInstitues($institutes, $startDate, $endDate) {
		$this->initiateModel("read","User");
		
		if(empty($institutes)) {
			return array();
		}
		
		$sql = "SELECT msgId AS 'Question ID', DATE(creationDate) AS 'Date'
			FROM  messageTable
			WHERE 	    creationDate >= ?
				AND creationDate <= ?
				AND listingTypeId IN ( ? )
				AND listingType in('institute','university_national')
				AND parentId = 0
				AND fromOthers = 'user'
				AND msgId = threadId
				AND status NOT IN ('deleted')";
		
		$query = $this->db->query($sql, array($startDate, $endDate, $institutes));
		$results = $query->result_array();

		$questionIds = array();
		
		foreach($results as $result) {
			$questionIds[$result['Question ID']] = $result['Date'];
		}
		
		return $questionIds;
	}

	function getQuestionsByDate($questions) {
		$this->initiateModel("read","User");
		
		if(empty($questions)) {
			return array();
		}
		
		$sql = "SELECT DATE(creationDate) AS 'Date', COUNT(*) AS 'Question Count'
			FROM  messageTable
			WHERE msgId IN ( ? )
			GROUP BY Date";
		
		$query = $this->db->query($sql, array($questions));
		$results = $query->result_array();

		$questionsByDate = array();
		
		foreach($results as $result) {
			if(empty($questionsByDate[$result['Date']])) {
				$questionsByDate[$result['Date']] = $result['Question Count'];
			}
			else {
				$questionsByDate[$result['Date']] += $result['Question Count'];
			}
		}
		
		return $questionsByDate;
	}

	function getAnswersByInstitute($questions) {
		$this->initiateModel("read","User");
		
		if(empty($questions)) {
			return array();
		}
		
		$sql = "SELECT DISTINCT parentId AS 'QuestionID'
			FROM messageTable AS mt
			WHERE 	    userId IN (SELECT username
					       FROM listings_main
					       WHERE listing_type_id = mt.listingTypeId
					       AND listing_type  in ('institute','university_national')
					       AND status = 'live')
				AND parentId IN ( ? )
				AND mainAnswerId = 0
				AND parentId = threadId
				AND status NOT IN ('deleted')";
		
		$query = $this->db->query($sql, array(array_keys($questions)));
		$results = $query->result_array();

		$answersByDate = array();
		
		foreach($results as $result) {
			if(empty($answersByDate[$questions[$result['QuestionID']]])) {
				$answersByDate[$questions[$result['QuestionID']]] = 1;
			}
			else {
				$answersByDate[$questions[$result['QuestionID']]]++;
			}
		}
		
		return $answersByDate;
	}

	function getAnswersByOther($questions) {
		$this->initiateModel("read","User");
		
		if(empty($questions)) {
			return array();
		}
		
		$sql = "SELECT DISTINCT parentId AS 'QuestionID'
			FROM messageTable AS mt
			WHERE 	    userId NOT IN (SELECT username
						   FROM listings_main
						   WHERE listing_type_id = mt.listingTypeId
					           AND listing_type in ('institute','university_national')
						   AND status = 'live')
				AND parentId IN ( ? )
				AND mainAnswerId = 0
				AND parentId = threadId
				AND status NOT IN ('deleted')";
		
		$query = $this->db->query($sql, array(array_keys($questions)));
		$results = $query->result_array();
		
		$answersByDate = array();
		
		foreach($results as $result) {
			if(empty($answersByDate[$questions[$result['QuestionID']]])) {
				$answersByDate[$questions[$result['QuestionID']]] = 1;
			}
			else {
				$answersByDate[$questions[$result['QuestionID']]]++;
			}
		}
		
		return $answersByDate;
	}

	function getQuestionsPosted($institutes,$startDate,$endDate,$interval, $list = false){
		$this->initiateModel("read","User");
		
		if(count($institutes) == 0) {
			return array();
		}
				
		$sql = "select listingTypeId, DATE( creationDate ) AS `date`,count(*) as QuestionsPosted
			FROM  messageTable
			WHERE 	creationDate >= ?
				AND creationDate <= ? 
				AND listingTypeId IN ( ? )
				AND listingType in ('institute','university_national')
				AND parentId = 0
				AND msgId = threadId
				AND fromOthers = 'user'
				AND status NOT IN ('deleted')
				GROUP BY listingTypeId, `date`
				order by date";		
		$final_end_date = $endDate.' 23:59:59';
				
		$query = $this->db->query($sql, array($startDate, $final_end_date, $institutes));
		$results = $query->result_array();

		$responses = array();
		foreach($results as $result) {
			$responses[$result['listingTypeId']][$result['date']] = $result['QuestionsPosted'];
		}
		
		$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);

		return $responsesCount;
	}

	
	function getQuestions($institutes, $startDate, $endDate) {
		$this->initiateModel("read","User");
		
		if(empty($institutes)) {
			return array();
		}
		
		$sql = "SELECT msgId AS QuestionID,listingTypeId
			FROM  messageTable
			WHERE 	creationDate >= ?
				AND creationDate <= ? 
				AND listingTypeId IN ( ? )
				AND listingType in ('institute','university_national')
				AND parentId = 0
				AND msgId = threadId
				AND fromOthers = 'user'
				AND status NOT IN ('deleted')";
		
		$query = $this->db->query($sql, array($startDate, $endDate, $institutes));
		$results = $query->result_array();

		$questionIds = array();
		
		foreach($results as $result) {
			$questionIds[$result['listingTypeId']][] = $result['QuestionID'];
		}
		
		return $questionIds;
	}

	function getQuestionsAnsweredByInstituteOwners($questions,$startDate,$endDate){
		$this->initiateModel("read","User");
		
		if(empty($questions)) {
			return array();
		}
		
		$sql = "SELECT count(distinct(parentId)) AS count
			FROM messageTable AS mt
			WHERE userId IN (SELECT username
					       FROM listings_main
					       WHERE listing_type_id = mt.listingTypeId
					       AND listing_type in ('institute','university_national')
					       AND status = 'live')
				AND parentId IN ( ? )
				AND mainAnswerId = 0
				AND parentId = threadId
				AND status NOT IN ('deleted')";
		
		$query = $this->db->query($sql, array($questions));
		$results = $query->result_array();

		$responses = array();
		foreach($results as $result) {
			$responses['QuestionsAnsweredByOwners'] = $result['count'];
		}
		return $responses;
		
	}

	function getQuestionsAnsweredByOthers($questions,$startDate,$endDate){
		$this->initiateModel("read","User");
		
		if(empty($questions)) {
			return array();
		}
		
		$sql = "SELECT count(distinct(parentId)) AS count
			FROM messageTable AS mt
			WHERE mainAnswerId = 0
				AND parentId = threadId	AND userId NOT IN (SELECT username
						   FROM listings_main
						   WHERE listing_type_id = mt.listingTypeId
					           AND listing_type in ('institute','university_national')
						   AND status = 'live')
				AND parentId IN ( ? )				
				AND status NOT IN ('deleted')";
		
		$query = $this->db->query($sql, array($questions));
		$results = $query->result_array();

		$responses = array();
		foreach($results as $result) {
			$responses['QuestionsAnsweredByOthers'] = $result['count'];
		}
		return $responses;
	
		
	}

	function getstudentSearchData($userIds,$startDate,$endDate,$interval)
	{
		$this->initiateModel("read","User");
		
		if(count($userIds) == 0) {
			return array();
		}
	
		
		$sql = "SELECT ClientId, COUNT( userid ) AS count_leads, date( ContactDate ) AS `date`
			FROM LDBLeadContactedTracking
			WHERE date( ContactDate ) >= ? 
			AND date( ContactDate ) <= ?
			AND activity_flag = 'LDB'
			and ClientId in ( ? )
			AND ContactType = 'view'
			AND CreditConsumed > 0
			GROUP BY ClientId, date
			ORDER BY date";
		
		
		$query = $this->db->query($sql, array($startDate, $endDate, $userIds));
		$results = $query->result_array();

		
		$responses = array();
		foreach($results as $result) {
			$responses[$result['ClientId']][$result['date']] = $result['count_leads'];
		}
		
		
		$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);

		
		return $responsesCount;
	}

	function getPortingsData($userIds,$startDate,$endDate,$interval,$list = false,$type='lead'){
		$this->initiateModel("read","User");

		if(count($userIds) == 0) {
			return array();
		}

		$this->db->select('id, name, client_id');
		$this->db->from('porting_main');
		$this->db->where_in('id',$userIds);
		$this->db->where('type',$type);
		$result = $this->db->get()->result_array();
		
		$portingData = array();
		$portingIds = array();
		foreach ($result as $key => $porting) {
			$portingData[$porting['id']] = array('name'=> $porting['name'],'clientId'=> $porting['client_id']);
			$portingIds[$porting['id']] = $porting['id'];
		}
		unset($result);

		$this->db->select('porting_master_id, date( request_time ) AS date, count(*) as counts');
		$this->db->from('porting_status');
		$this->db->where_in('porting_master_id',$portingIds);
		$this->db->where('request_time >=',$startDate.' 00:00:00');
		$this->db->where('request_time <=',$endDate.' 23:59:59');
		$this->db->group_by('porting_master_id, date');
		$this->db->order_by('date');
		$results = $this->db->get()->result_array();
		unset($portingIds);
		
		$responses = array();
		if($list) {
			foreach($results as $result) {
				$responses[$result['porting_master_id']][$result['date']] = $result['counts'];
			}
			
			$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);	
			return $responsesCount;
		}
		else {
			foreach($results as $result) {
				$responses[$portingData[$result['porting_master_id']]['clientId']][$result['date']][$portingData[$result['porting_master_id']]['name']] = $result['counts'];
			}
			
			$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);
			return $responsesCount;
		}
	}

	function getListingsViewCount($courses,$startDate,$endDate,$interval){
		$this->initiateModel("read","User");
		
		if(count($courses) == 0) {
			return array();
		}
		
		$sql = "SELECT listing_type_id, DATE( view_Date ) AS `date` , no_of_Views AS view_count
			FROM listings_main, view_Count_Details AS v
			WHERE view_Date > ?
			AND view_Date < ?
			AND listing_type_id = v.listing_id
			AND listings_main.listing_type_id
			IN ( ? )
			AND listings_main.status = 'live'
			GROUP BY listings_main.listing_type_id, view_Date
			ORDER BY view_Date DESC";

		$query = $this->db->query($sql, array($startDate, $endDate, $courses));
		$results = $query->result_array();

		$responses = array();
		foreach($results as $result) {
			$responses[$result['listingTypeId']][$result['date']] = $result['ListingsViewed'];
		}

		$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);

		return $responsesCount;
	}

	function getCreditsLeft($userIds,$startDate,$endDate,$interval, $productType='credit'){
		$this->initiateModel("read","SUMS");

		if(count($userIds) == 0) {
			return array();
		}
		
		if($productType == 'credit') {
			$products[] = 127;
		}
		else {
			$products[] = GOLD_SL_LISTINGS_BASE_PRODUCT_ID;
			$products[] = GOLD_ML_LISTINGS_BASE_PRODUCT_ID;
		}

		foreach ($userIds as $userid) {
            if(intval($userid) >1 && !in_array($userid, $tempUserIdArray)){
                $tempUserIdArray[] = $userid;
            }
    	}
		
		if(!empty($tempUserIdArray)) {
			$sql = "Select s.ClientUserId,sum(spm.BaseProdRemainingQuantity) as count
				From Subscription_Product_Mapping spm, Subscription s
				where s.SubscriptionId = spm.SubscriptionId 
				AND BaseProdRemainingQuantity >= 1 
				AND spm.Status='ACTIVE' 
				AND date(SubscriptionEndDate) >= CURDATE() 
				AND date(SubscriptionStartDate) <= CURDATE()
				and s.BaseProductId in ( ? )
				and s.ClientUserId IN ( ? )
				GROUP BY s.ClientUserId";

			$query = $this->db->query($sql, array($products, $tempUserIdArray));
			$results = $query->result_array();

		}

		$responses = array();
		foreach($results as $result) {
			$responses[$result['ClientUserId']] = $result['count'];
		}
		
		return $responses;
	}

	function getCreditsConsumedPeriodWise($userIds,$startDate,$endDate,$interval,$list = false){
		$this->initiateModel("read","SUMS");

		if(is_array($userIds)) {

			if(count($userIds) == 0) {
				return array();
			}

			$tempUserIdArray = array();

			foreach ($userIds as $userid) {
	            if(intval($userid) > 1 && !in_array($userid, $tempUserIdArray)){
	                $tempUserIdArray[] = $userid;
	            }
        	}
			
			if(!empty($tempUserIdArray)) {
				$sql = "SELECT ClientUserId, sum( NumberConsumed ) as count , DATE( ConsumptionTime ) AS date
					FROM SubscriptionLog sl
					WHERE ConsumedBaseProductId = 127
					AND STATUS = 'ACTIVE'
					and date( ConsumptionTime ) >= ?
					and date( ConsumptionTime ) <= ?
					AND ClientUserId IN ( ? )
					GROUP BY ClientUserId, date";
			
				$query = $this->db->query($sql, array($startDate, $endDate, $tempUserIdArray));
				$results = $query->result_array();
			}

			$responses = array();
			if($list) {
				foreach($results as $result) {
					if(empty($responses[$result['date']])) {
						$responses[$result['date']] = $result['count'];
					}
					else {
						$responses[$result['date']] += $result['count'];
					}
				}
				
				return $responses;
			}
			else {
				foreach($results as $result) {
					$responses[$result['ClientUserId']][$result['date']] = $result['count'];
				}
				
				$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);
				
				return $responsesCount;
			}

		}
		
	}


	function getLoginAndSessionDetails($userIds,$startDate,$endDate,$interval){
		$this->initiateModel("read","User");

		if(count($userIds) == 0) {
			return array();
		}

		$sql = "SELECT id,userId, ipAddress , date(activityTime) AS date, activity
			FROM  tuserLoginTracking
			WHERE date( activityTime ) >= ?
			and date( activityTime ) <= ?
			AND userId IN ( ? )
			GROUP BY id, date";

		$query = $this->db->query($sql, array($startDate, $endDate, $userIds));
		$results = $query->result_array();

		$responses = array();
		foreach($results as $result) {
			$responses[$result['userId']][$result['date']][$result['id']] = 1;
		}

		$responsesCount = $this->breakByInterval($responses,$interval,$startDate,$endDate);
		return $responsesCount;
	}


	function getLoginDetails($Ids){
		$this->initiateModel("read","User");

		if(count($Ids) == 0) {
			return array();
		}

		$IdsArray = explode(',', $Ids);

		$sql = "SELECT id,userId, ipAddress , activityTime , activity
			FROM  tuserLoginTracking
			WHERE 
			id IN (?)
			GROUP BY id order by id asc";

		$query = $this->db->query($sql, array($IdsArray));
		$results = $query->result_array();

		$responses = array();
		foreach($results as $result) {
			$responses[$result['userId']][$result['id']]['activityTime'] = $result['activityTime'];
			$responses[$result['userId']][$result['id']]['activity'] = $result['activity'];
			$responses[$result['userId']][$result['id']]['ipAddress'] = $result['ipAddress'];
		}
		return $responses;
	}


	function getResponsesForResponseViewerGraph($clients,$instituteIds){
		$this->initiateModel("read","User");
		$returnArr = array();

		$query1 = "SELECT course_id FROM course_details,listings_main WHERE institute_id IN (?) AND username in (?) and course_details.institute_id = listings_main.listing_type_id  AND listings_main.status = 'live' ";
		$sql = $this->db->query($query1,array($instituteIds, $clients));

		$listingIds['course_id'] = $this->getColumnArray($sql->result_array(),'course_id');

		$fromDate = date('Y-m-d', strtotime( '-7 days' ) );
		$tillDate = date('Y-m-d');

		$courseIds[] = $listingIds['course_id'];
		$clauses[] = "((listing_type = 'institute' AND listing_type_id IN (".implode(',',$instituteIds).")) OR (listing_type = 'course' AND listing_type_id IN (".implode(',',$courseIds[0]).")))";

		if (!empty($fromDate)) {
			$clauses['fromDate'] = "date(submit_date) >= ".$this->db->escape($fromDate);
		}
		if (!empty($tillDate)) {
			$clauses['tillDate'] = "date(submit_date) < ".$this->db->escape($tillDate);
		}

		$queryLastWeek = $this->db->query("SELECT count(*) as responses FROM tempLMSTable ".(count($clauses) > 0 ? "WHERE  listing_subscription_type='paid' AND  ".implode(' AND ',$clauses) : " WHERE listing_subscription_type='paid'"));
		$results = $queryLastWeek->result_array();

		$fromDate = date('Y-m-d', strtotime( '-15 days' ) );
		$tillDate = date('Y-m-d', strtotime( '-8 days' ));

		if (!empty($fromDate)) {
			$clauses['fromDate'] = "date(submit_date) >= ".$this->db->escape($fromDate);
		}
		if (!empty($tillDate)) {
			$clauses['tillDate'] = "date(submit_date) <= ".$this->db->escape($tillDate);
		}

		$queryLastToLastWeek = $this->db->query("SELECT count(*) as responses FROM tempLMSTable ".(count($clauses) > 0 ? "WHERE listing_subscription_type='paid' AND  ".implode(' AND ',$clauses) : "WHERE listing_subscription_type='paid'"));
		$resultsLastToLastWeek = $queryLastToLastWeek->result_array();

		foreach($results as $result) {
			$returnArr['responsesLastWeek'] = $result['responses'];
			$returnArr['responsesLastToLastWeek'] = $resultsLastToLastWeek[0]['responses'];
		}
		return $returnArr;
	}
	
	function getFlavorArticlesByCategory($criteria,$orderBy,$startOffset,$countOffset,$categories) {
		$this->initiateModel("read","User");
	
		$criteriaText = '';
		foreach($criteria as $criteriaKey => $criteriaValue) {
		    switch($criteriaKey) {
			case 'startDate': $criteriaText .= ' AND pbd.StartDate <= '. $this->db->escape($criteriaValue);
					    break; // The Condition added like this as right now this is the only condition required. If required to make it more generalize make the $criteriaValue as an array with operator and value.
					    default: $criteriaText .= ' AND '. $key .' = '. $this->db->escape($criteriaValue) ;
						
		    }
		}
		
		$query = 'SELECT SQL_CALC_FOUND_ROWS bt.*,pbd.startDate, pbd.endDate FROM blogTable bt INNER JOIN PageBlogDb pbd ON pbd.BlogId = bt.blogId WHERE bt.status="live" AND pbd.KeyId = 51 AND bt.boardId IN ('.implode(",",$categories).')  '. $criteriaText .' ORDER BY '. $orderBy .' LIMIT '. $startOffset .','. $countOffset ;
		
		$resultSet = $this->db->query($query);
			$response = array();
			foreach($resultSet->result_array() as $result) {
				$response['articles'][] = $result;
			}
			
		return $response;
		
    }

	
	function getLatestUpdatesByCategory($startOffset,$categories) {
		$this->initiateModel("read","User");
	
		 $query = 'SELECT SQL_CALC_FOUND_ROWS bt.*,pbd.startDate, pbd.endDate FROM blogTable bt INNER JOIN PageBlogDb pbd ON pbd.BlogId = bt.blogId WHERE bt.status="live" AND pbd.KeyId = 52 AND bt.boardId IN ('.implode(",",$categories).') ORDER BY pbd.startDate Desc LIMIT '.$startOffset;
        $resultSet = $this->db->query($query);
                $response = array();
                foreach($resultSet->result_array() as $result) {
                        $response['articles'][] = $result;
                }
	
		return json_encode($response);
	}
    
    
	function getSubordinatesForExecutive($executives, $list = True, $excludeSuperiors = array()) {
		$this->initiateModel("read","User");

		$sql = "SELECT `userId`,`managerId` FROM `sales_hierarchy` WHERE `status` = 'ACTIVE' AND `managerId` IN(".implode(",",$executives).") ".(count($excludeSuperiors) > 0 ? "AND `userId` NOT IN(".implode(",",array_keys($excludeSuperiors)).")" : "");
		$query = $this->db->query($sql);
		$results = $query->result_array();

		if(count($results) == 0) {
			return array();
		}
		else {
			foreach($results as $result) {
				$subordinates[$result['userId']] = $result['managerId'];
				$finalResult[$result['managerId']][$result['userId']] = array();
				$excludeSuperiors[$result['managerId']] = True;
			}
			$temp = $this->getSubordinatesForExecutive(array_keys($subordinates), $list, $excludeSuperiors);
				
			if($list) {
				foreach($temp as $userId) {
					$subordinates[$userId] = True;
				}
				return array_keys($subordinates);
			}
			else {
				foreach($temp as $userId => $juniorArray) {
					$finalResult[$subordinates[$userId]][$userId] = $juniorArray;
				}
				return $finalResult;
			}
		}
	}
	
	function getAccountManagerDetails($userId) {
		$returnArr = array();
		if(empty($userId)) {
			return $returnArr;
		}

		$this->initiateModel("read","User");

		$sql = "SELECT userid, displayname AS 'name',email,mobile FROM `tuser` WHERE userid = ?";
		$query = $this->db->query($sql, array($userId));
		$results = $query->result_array();

		foreach($results as $result) {
				$returnArr['name'] = $result['email'];
				$returnArr['email'] = $result['email'];
				$returnArr['mobile'] = $result['mobile'];
		}


		return $returnArr;
	}
	
	function getSubCategories($categoryId){
		$returnArr = array();
		if(empty($categoryId)) {
			return $returnArr;
		}

		$this->initiateModel("read","User");

		$sql = "select boardId from categoryBoardTable where parentId = ?";
		$query = $this->db->query($sql, array($categoryId));
		$results = $query->result_array();

		foreach($results as $result) {
				$returnArr[] = $result['boardId'];
		}
		
		return $returnArr;
	}
	
	function getActiveLeadGenies($userIds, $list = True) {
		$this->initiateModel("read","User");

		$returnArr = array();

		if(count($userIds) == 0) {
			return $returnArr;
		}

		$sql = $this->db->query("SELECT searchagentid, searchagentName, is_active, clientid FROM SASearchAgent WHERE is_active = 'live' and clientid IN (".implode(',',$userIds).")");

		$results = $sql->result_array();

		if($list) {
			foreach($results as $result) {
				$returnArr[$result['clientid']][$result['searchagentid']] = $result['searchagentName'];
			}
		}
		else {
			
			foreach($results as $result) {
				$returnArr[$result['clientid']]['searchAgentID'] .= ", ".$result['searchagentid'];
			}
		}

		return $returnArr;
	}

	function getCoursesForInstitutes($instituteIds, $list = True){
				
		$returnArr = array();
		$this->initiateModel("read","User");
		
		$new_array  = array();
		foreach($instituteIds as $institute_id) {
			
			$institute_id = intval($institute_id);
			if($institute_id >0) {
				$new_array[] = $institute_id;
			}
		}
		
		$instituteIds = $new_array;
		
		if(count($instituteIds) == 0) {
			return $returnArr;
		}
		
		// $sql = "SELECT cd.course_id,cd.primary_id as institute_id,cd.name as courseTitle ".
		// 	"FROM shiksha_courses cd, listings_main lm WHERE lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live' AND cd.status = 'live' AND cd.primary_id IN (".implode(",", $instituteIds).")";

		$sql = "SELECT cd.course_id,cd.primary_id as institute_id,cd.name as courseTitle ".
			"FROM shiksha_courses cd WHERE cd.status = 'live' AND cd.primary_id IN (".implode(",", $instituteIds).")";
					
		$query = $this->db->query($sql);
		$results = $query->result_array();
		
		if($list){
			foreach ($results as $row) {
				$returnArr[] = $row['course_id'];
			}
			
		}else{
			foreach ($results as $row) {
				$returnArr[$row['institute_id']][] = $row['course_id'];
			}
			
		}
		return $returnArr;
		
	}
	
	function getCoursesForUniversities($universityIds, $list = True){
		
		$returnArr = array();
		$this->initiateModel("read","User");
		
		if(count($universityIds) == 0) {
			return $returnArr;
		}
		
		$sql =  "SELECT cd.course_id,um.university_id,cd.courseTitle ".
				"FROM course_details cd, institute_university_mapping um,listings_main lm ".
				"WHERE um.institute_id = cd.institute_id ".
				"AND lm.listing_type_id = cd.course_id ".
				"AND lm.listing_type = 'course' ".
				"AND lm.status = 'live' ".
				"AND um.status = 'live' ".
				"AND cd.status = 'live' ".
				"AND um.university_id IN (".implode(",", $universityIds).")";
		
		
		//$sql = "SELECT cd.course_id,cd.institute_id,cd.courseTitle ".
		//	"FROM course_details cd, listings_main lm WHERE lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live' AND cd.status = 'live' AND cd.institute_id IN (".implode(",", $instituteIds).")";
		
		$query = $this->db->query($sql);
		$results = $query->result_array();
		
		if($list){
			foreach ($results as $row) {
				$returnArr[] = $row['course_id'];
			}
			
		}else{
			foreach ($results as $row) {
				$returnArr[$row['university_id']][] = $row['course_id'];
			}
			
		}
		return $returnArr;
	}

	function getUserName($userIds) {
		$returnArr = array();

		if(count($userIds) == 0) {
			return $returnArr;
		}

		$this->initiateModel("read","User");

		$sql = "SELECT userid, displayname AS 'name', email FROM `tuser` WHERE userid IN (".implode(',',$userIds).")";
		$query = $this->db->query($sql);
		$results = $query->result_array();

		foreach($results as $result) {
				$returnArr[$result['userid']] = $result['email'];
		}


		return $returnArr;
	}

	function getEnterpriseClientDisplayname($userIds) {
		$returnArr = array();

		if(count($userIds) == 0) {
			return $returnArr;
		}

		$this->initiateModel("read","User");

		$sql = "SELECT userid,displayname,email FROM tuser WHERE userid IN (".implode(',',$userIds).")";
		
		$query = $this->db->query($sql);
		$results = $query->result_array();

		foreach($results as $result) {
			$returnArr[$result['userid']] = $result['email'];
		}

		return $returnArr;
	}

	function getLeadGenieForClient($userIds, $status, $list = True) {
		$this->initiateModel("read","User");

		$returnArr = array();

		if(count($userIds) == 0) {
			return $returnArr;
		}
		
		$active = '';
		if($status == 'active') {
			$active = "AND T1.is_active = 'live'";
		}
		else if($status == 'deleted') {
			$active = "AND T1.is_active = 'history'";
		}
		
		$sql = "SELECT T1.searchagentid, T1.searchagentName, T1.is_active, T1.clientid, T1.created_on FROM SASearchAgent T1 LEFT JOIN SASearchAgent T2 ON ( T1.searchagentid = T2.searchagentid AND T1.id < T2.id ) WHERE T2.id IS NULL AND T1.deliveryMethod = 'normal' AND T1.clientid IN (?) ".$active;
		
		$query = $this->db->query($sql,array($userIds));
		$results = $query->result_array();

		if($list) {
			foreach($results as $result) {
				$returnArr[$result['searchagentid']]['name'] = 'Lead Genie - '.$result['searchagentName'];
				$returnArr[$result['searchagentid']]['status'] = $result['is_active'];
				$returnArr[$result['searchagentid']]['create_date'] = $result['created_on'];
				$returnArr[$result['searchagentid']]['clientid'] = $result['clientid'];
			}
		}
		else {
			foreach($results as $result) {
				$returnArr[$result['clientid']][$result['searchagentid']]['name'] = $result['searchagentName'];
				$returnArr[$result['clientid']][$result['searchagentid']]['status'] = $result['is_active'];
				$returnArr[$result['clientid']][$result['searchagentid']]['create_date'] = $result['created_on'];
			}
		}

		return $returnArr;
	}
	
	function getResponsePortingsForClient($userIds, $status, $list = True){
		$this->initiateModel("read","User");
		
		$returnArr = array();
		
		if(count($userIds) == 0) {
			return $returnArr;
		}
		
		$active = '';
		if($status == 'active') {
			$active = "and status = 'live'";
		}
		else if($status == 'deleted') {
			$active = "and status = 'stopped'";
		}
		
		$sql = "SELECT id, name, status, client_id, create_date FROM porting_main WHERE type = 'response' and client_id IN (?) ".$active;	
		
		$query = $this->db->query($sql,array($userIds));
		$results = $query->result_array();
		
		if($list) {
			foreach($results as $result) {
				$returnArr[$result['id']]['name'] = 'Response Porting - '.$result['name'];
				$returnArr[$result['id']]['status'] = $result['status'];
				$returnArr[$result['id']]['create_date'] = $result['create_date'];
				$returnArr[$result['id']]['clientid'] = $result['client_id'];
			}
		}
		else {
			foreach($results as $result) {
				$returnArr[$result['client_id']][$result['id']]['name'] = $result['name'];
				$returnArr[$result['client_id']][$result['id']]['status'] = $result['status'];
				$returnArr[$result['client_id']][$result['id']]['create_date'] = $result['create_date'];
			}
		}
		
		return $returnArr;
	}

	function getLeadPortingsForClient($userIds, $status, $list = True) {
		$this->initiateModel("read","User");
		
		$returnArr = array();
		
		if(!is_array($userIds) || count($userIds) == 0) {
			return $returnArr;
		}

		$newUserIds = array();
		foreach($userIds as $key=>$userId) {
			$newUserIds[$key] = $this->db->escape($userId);
		}		
		unset($userIds);
		$userIds = $newUserIds;
		unset($newUserIds);

		$active = '';
		if($status == 'active') {
			$active = "and status = 'live'";
		}
		else if($status == 'deleted') {
			$active = "and status = 'stopped'";
		}
		
		$sql = "SELECT id, name, status, client_id, create_date FROM porting_main WHERE type = 'lead' and client_id IN (?) ".$active;
		
		$query = $this->db->query($sql,array($userIds));
		$results = $query->result_array();
		
		if($list) {
			foreach($results as $result) {
				$returnArr[$result['id']]['name'] = 'Lead Porting - '.$result['name'];
				$returnArr[$result['id']]['status'] = $result['status'];
				$returnArr[$result['id']]['create_date'] = $result['create_date'];
				$returnArr[$result['id']]['clientid'] = $result['client_id'];
			}
		}
		else {
			foreach($results as $result) {
				$returnArr[$result['client_id']][$result['id']]['name'] = $result['name'];
				$returnArr[$result['client_id']][$result['id']]['status'] = $result['status'];
				$returnArr[$result['client_id']][$result['id']]['create_date'] = $result['create_date'];
			}
		}
		
		return $returnArr;
	}
	
	//
	//API to get responses Expectation filled for client
	//
	
	function getClientExpectations($instituteIds, $list = true){
		$this->initiateModel("read","User");
		
		$returnArr = array();
		
		if(count($instituteIds) == 0) {
			return $returnArr;
		}
		
		$sql = "SELECT Institute_Id , Client_Expectation_Of_Responses , `StartDate` , `EndDate` , `Clients_Expected_Run_Rate` , `submitted_date` FROM Client_Expectations where `Is_Active` = 'ACTIVE' and `Institute_Id` in (".implode(',',$instituteIds).")";
		
		$query = $this->db->query($sql);
		$results = $query->result_array();

		$responses = array();
		
		if($list) {
			foreach($results as $result) {
				$responses[$result['Institute_Id']]['clientExpectationOfResponses'] = $result['Client_Expectation_Of_Responses'];
				$responses[$result['Institute_Id']]['DateRange'] = ";".$result['StartDate'].";".$result['EndDate'];
				$responses[$result['Institute_Id']]['clientsExpectedRunRate'] = $result['Clients_Expected_Run_Rate'];
			}
		}
		else {
			foreach($results as $result) {
				$responses[$result['Institute_Id']]['clientExpectationOfResponses'] = $result['Client_Expectation_Of_Responses'];
				$responses[$result['Institute_Id']]['fromDate'] = $result['StartDate'];
				$responses[$result['Institute_Id']]['toDate'] = $result['EndDate'];
				$responses[$result['Institute_Id']]['clientsExpectedRunRate'] = $result['Clients_Expected_Run_Rate'];
			}
		}
		
		return $responses;
	}
	
	//
	// Adding Break interval function
	//

	function breakByInterval($results,$interval,$startDate,$endDate){
		
		$returnArr = array();
		
		foreach($results as $listing_type_id => $countData){
			
			$key1 = $listing_type_id;
			
			foreach($countData as $key=>$value){
				if($interval == 'daily'){
					$key2 = "Daily ;".$key.";".$key;
				}
				elseif($interval == 'weekly'){
					$key2 = $this->getWeek($key,$startDate,$endDate);
				}
				elseif($interval == 'monthly'){
					$key2 = $this->getmonths($key,$startDate,$endDate);
				}
				elseif($interval == 'quarterly'){
					$key2 = $this->getQuarterByMonth($key,$startDate,$endDate);
				}
				elseif($interval == 'yearly'){
					$key2 = $this->getYears($key,$startDate,$endDate);
				}
				elseif($interval == 'summary'){
					$key2 = "Summary ;".$startDate.';'.$endDate;
				}
				elseif($interval == 'summarybyinstitute'){
					
					$key2 = ";".$startDate.';'.$endDate;
				}
				
				if(is_array($value)) {
					foreach($value as $vk => $vv) {
						$returnArr[$key1][$key2][$vk] += intval($vv);
					}
				}
				else {
					$returnArr[$key1][$key2] += intval($value);
				}
			}
		}
		return $returnArr;
	}

	public function getWeek($date_string,$startDate,$endDate){
		
		$week_start = (date('w', strtotime($date_string)) == 1) ? date('d-m-Y', strtotime($date_string)) : date('d-m-Y', strtotime('last Monday', strtotime($date_string)));
		$week_end = (date('w', strtotime($date_string)) == 0) ? date('d-m-Y', strtotime($date_string)) : date('d-m-Y', strtotime('next Sunday', strtotime($date_string)));
		
		if(strtotime($week_start) < strtotime($startDate)) {
			$week_start = $startDate;
		}
		
		if(strtotime($week_end) > strtotime($endDate)) {
			$week_end = $endDate;
		}
		
		$startAndEndDate = "Week ;".$week_start.";".$week_end;
		return $startAndEndDate;
	}

	public function getMonths($date_string,$startDate,$endDate){
		
		$month_start = date('01-m-Y',strtotime($date_string));
		$month_end = date('t-m-Y',strtotime($date_string));
		
		if(strtotime($month_start) < strtotime($startDate)) {
			$month_start = $startDate;
		}
		
		if(strtotime($month_end) > strtotime($endDate)) {
			$month_end = $endDate;
		}
		
		$startAndEndDate = "Month ".";".$month_start.";".$month_end;
		return $startAndEndDate;
	}
	
	public function getQuarterByMonth($date_string,$startDate,$endDate){
		
		$month = date('m',strtotime($date_string));
		
		if($month == 4 || $month == 5 || $month == 6) {
			$quarter_start = date('01-04-Y',strtotime($date_string));
			$quarter_end = date('30-06-Y',strtotime($date_string));
			$quarter = 'Q1';
		}
		elseif($month == 7 || $month == 8 || $month == 9) {
			$quarter_start = date('01-07-Y',strtotime($date_string));
			$quarter_end = date('30-09-Y',strtotime($date_string));
			$quarter = 'Q2';
		}
		elseif($month == 10 || $month == 11 || $month == 12) {
			$quarter_start = date('01-10-Y',strtotime($date_string));
			$quarter_end = date('31-12-Y',strtotime($date_string));
			$quarter = 'Q3';
		}
		elseif($month == 1 || $month == 2 || $month == 3) {
			$quarter_start = date('01-01-Y',strtotime($date_string));
			$quarter_end = date('31-03-Y',strtotime($date_string));
			$quarter = 'Q4';
		}
		
		if(strtotime($quarter_start) < strtotime($startDate)) {
			$quarter_start = $startDate;
		}
		
		if(strtotime($quarter_end) > strtotime($endDate)) {
			$quarter_end = $endDate;
		}
		
		$startAndEndDate = $quarter." ;".$quarter_start.";".$quarter_end;
		return $startAndEndDate;
	}

	public function getYears($date_string,$startDate,$endDate) {
		
		$year_start = date('01-01-Y',strtotime($date_string));
		$year_end = date('31-12-Y',strtotime($date_string));
		
		if(strtotime($year_start) < strtotime($startDate)) {
			$year_start = $startDate;
		}
		
		if(strtotime($year_end) > strtotime($endDate)) {
			$year_end = $endDate;
		}
		
		$startAndEndDate = "Year ".";".$year_start.";".$year_end;
		return $startAndEndDate;
	}
	
	public function setExpectationForInstitutes($institute, $expectation, $runRate, $fromDate, $toDate) {
		$this->initiateModel("write","User");
		
		$sql = 'INSERT IGNORE INTO Client_Expectations (Institute_Id, Client_Expectation_Of_Responses, Clients_Expected_Run_Rate, StartDate, EndDate, Is_active) VALUES 
			(?, ?, ?, ?, ?, ?)';
		$this->db->query($sql, array($institute, $expectation, $runRate, date('Y-m-d H:i:s', strtotime($fromDate)), date('Y-m-d H:i:s', strtotime($toDate)), 'Active'));
		
		$maxSubmittedDate = $this->getMaxExpecationSubmittedDate($institute);
		
		$this->updateInactiveExpectationRecords($institute, $maxSubmittedDate);
		
		$this->deleteActiveDuplicateRecords($institute, $maxSubmittedDate);
		
		$totalRows = $this->getExpectationCount($institute);
		
		if($totalRows > 5) {
			$this->deleteInactiveExpectationRecords($institute, $totalRows - 5);
		}
	}
	
	private function getMaxExpecationSubmittedDate($institute) {
		$this->initiateModel("read","User");
		$sql = 'SELECT MAX(Submitted_Date) AS Date FROM Client_Expectations WHERE Institute_Id = ?';
		$results = $this->db->query($sql, array($institute))->result_array();
		
		foreach($results as $result) 
			return $result['Date'];
	}
	
	private function updateInactiveExpectationRecords($institute, $maxSubmittedDate) {
		$this->initiateModel("write","User");
		$sql = 'UPDATE IGNORE Client_Expectations SET Is_active = \'Inactive\' WHERE Institute_Id = ? AND Submitted_Date < ?';
		$this->db->query($sql, array($institute, $maxSubmittedDate));
	}
	
	private function getExpectationCount($institute) {
		$this->initiateModel("read","User");
		$sql = 'SELECT * FROM Client_Expectations WHERE Institute_Id = ?';
		$totalRows = $this->db->query($sql, array($institute))->num_rows();
		return $totalRows;
	}
	
	private function deleteActiveDuplicateRecords($institute, $maxSubmittedDate) {
		$this->initiateModel("write","User");
		$sql = 'DELETE FROM Client_Expectations WHERE Institute_Id = ? AND Is_active = \'Active\' AND Submitted_Date < ?';
		$this->db->query($sql, array($institute, $maxSubmittedDate));
	}
	
	private function deleteInactiveExpectationRecords($institute, $noRows) {
		$this->initiateModel("write","User");
		$sql = 'DELETE FROM Client_Expectations WHERE Institute_Id = ? AND Is_active = \'Inactive\' ORDER BY Submitted_Date ASC LIMIT '.$noRows.'';
		$this->db->query($sql, array($institute));
	}
	
	public function getAllSalesUsers() {
		$this->initiateModel("read","User");
		$sql = "SELECT userId FROM sales_hierarchy WHERE status = 'ACTIVE'";
		$results = $this->db->query($sql)->result_array();
		
		$salesUsers = array();
		
		foreach($results as $result) {
			$salesUsers[] = $result['userId'];
		}
		
		return $salesUsers;
	}
	
	public function getActiveClientExpections($startDate, $endDate) {
		$this->initiateModel("read","User");
		$sql = "SELECT Institute_Id, Client_Expectation_Of_Responses, DATE(StartDate) AS StartDate, DATE(EndDate) AS EndDate
			FROM Client_Expectations
			WHERE DATE(StartDate) <= ?
			AND DATE(EndDate) >= ?
			AND Is_active = 'Active'";
		$results = $this->db->query($sql, array($endDate, $startDate))->result_array();
		
		$startDate = strtotime($startDate);
		$endDate = strtotime($endDate);
		$instituteToExpectationMapping = array();
		$durationStart = null;
		$durationEnd = null;
		$numOfDays = null;
		$totalDays = null;
		$clientExpectation = null;
		
		foreach($results as $result) {
			$resultStartDate = strtotime($result['StartDate']);
			$resultEndData = strtotime($result['EndDate']);
			
			if($resultStartDate <= $startDate) {
				$durationStart = $startDate;
			}
			else {
				$durationStart = $resultStartDate;
			}
			
			if($resultEndData >= $endDate) {
				$durationEnd = $endDate;
			}
			else {
				$durationEnd = $resultEndData;
			}
			
			$numOfDays = ($durationEnd - $durationStart) / (24 * 60 * 60) + 1;
			$totalDays = ($resultEndData - $resultStartDate)/(24 * 60 * 60) + 1;
			
			$clientExpectation = round(($result['Client_Expectation_Of_Responses'] * $numOfDays) / $totalDays);
			
			$instituteToExpectationMapping[$result['Institute_Id']]['WeeklyExpectation'] = $clientExpectation;
			$instituteToExpectationMapping[$result['Institute_Id']]['TotalExpectation'] = $result['Client_Expectation_Of_Responses'];
			$instituteToExpectationMapping[$result['Institute_Id']]['StartDate'] = $result['StartDate'];
			$instituteToExpectationMapping[$result['Institute_Id']]['EndDate'] = $result['EndDate'];
		}
		
		return $instituteToExpectationMapping;
	}
	
	function getUserEmail($userIds) {
		$returnArr = array();
		
		if(empty($userIds)) {
			return $returnArr;
		}
		
		$this->initiateModel("read","User");
		$sql = "SELECT userid, email FROM tuser WHERE userid IN (".implode(',',$userIds).")";
		$query = $this->db->query($sql);
		$results = $query->result_array();
		
		foreach($results as $result) {
			$returnArr[$result['userid']] = $result['email'];
		}
		
		return $returnArr;
	}

	/**
	*Functions to check, insert or update sales_hierarchy according to values entered in SmartUser view
	*
	*The function checks if all the respective fields are filled, checks and updates for existing users and inserts new users.
	*
	*@param: values from the fields of the form: User Email, User Role, Manager Email
	*
	*return: integer value to determine success or failure handled in controller addSmartUser
	*/
	
	/**
	 *Function to get userid of Managers and pass it on to addSmartUser
	*/
	function addSmartUsers($userEmails,$roles,$managerEmails,$empID){	
		
		$this->initiateModel("write","User");
		$userFoundFlag = 3;
		
		if(empty($userEmails) || empty($managerEmails) || count($userEmails) !=  count($managerEmails)){
			echo 'Provide User as well as Manager email ids respectively.';
			exit;
		}
	
		
			
			$query = "select userid from tuser where email = ? AND `usergroup` LIKE 'sums';";
			$result = $this->db->query($query,array($managerEmails));
			//error_log("abcde :: query result for managerID ====".$result);
			
			if($result->result_array()){
					
				foreach ($result->result_array() as $row){
					$managerId = $row['userid'];
					$userFoundFlag = $this->addSmartUser($userEmails,$roles,$managerId,$empID); 
				}
		
			}else{
				$userFoundFlag = 5;
			}
			//error_log("abcde :: userFoundFlag ====".$userFoundFlag);
			

			//return 1;

				if($userFoundFlag == 0){
					return "USER_NOT_FOUND";
				}else if($userFoundFlag == 2){
					return "INVALID_QUERY";
				}else if($userFoundFlag == 3){
					return "CREATED_SUCCESSFULLY";
				}else if($userFoundFlag == 4){
					return "UPDATED_SUCCESSFULLY";
				}else if(!$managerId || $userFoundFlag == 5){
					return "MANAGER_NOT_FOUND";
				}else if($userFoundFlag == 6){
					return "EMP_ID_USER_ID_NOT_MATCH";
				}
				
	}
	
	/**
	 *Function to get userId of user and pass it on for insertion or updation
	*/
	function addSmartUser($userEmails,$roles,$managerId,$empID){
		
		$this->initiateModel("write","User");
				
		
			$query = "select userid from tuser where email = ? AND `usergroup` LIKE 'sums';";
			$smartUser = array();
			$result = $this->db->query($query, array($userEmails));
			if($result->result_array()){
		
				foreach ($result->result_array() as $row){
					$smartUser['userid'] = $row['userid'];
					$smartUser['managerid'] = $managerId;
					$smartUser['role'] = $roles;
				
				}
		
				/**
				 *check if user already exists
				*/
		
				$alreadyExists = array();
				$checkQuery = "select userId from sales_hierarchy where userId = ?";
				$checkResult = $this->db->query($checkQuery,array($smartUser['userid']));
		
				if($this->db->affected_rows()){
					$queryFlag=1;
		
					foreach ($checkResult->result_array() as $row) {
						$alreadyExists[] = $row['userid'];
						$queryFlag=$this->updateSmartUser($smartUser,$empID);
	
						if($queryFlag == 0)
							$flag = 2;
						else if($queryFlag == 6){
							$flag = 6;
						}
						else
							$flag = 4;
					}
	
				}else{
					$queryFlag=$this->insertSmartUser($smartUser);
		
					if($queryFlag == 0)
						$flag = 2;
					else
						$flag = 3;
				}
	
			}else{
				$flag = 0;
			}
		//error_log("abcde :: flag=-=-=-=-=-=-=-=-".$flag);
		if($flag != 0 && $flag != 2 && $flag != 3 && $flag != 4 && $flag !=6 ){
			$flag = 1;
		}
		
		return $flag;
	}
	
	/*
	 *Function to get the userID by email
	 */
	
	function getUserIdByEmail($email){
		$this->initiateModel("read","User");
		$userId = 0;
		$query = "select userid, email from tuser where email = ?";
		
		$result = $this->db->query($query,array($email));
		
		if($result->result_array()){
			
			foreach ($result->result_array() as $row){
				$userId = $row['userid'];
			}
		}
		return $userId;
	}
	
		
	/*
	 *Insert user if not in sales_hierarchy table
	*/
	function insertSmartUser($smartUser){
	
		$ManagersRole=array();
		$roleNameQuery ="SELECT * FROM `SUMS`.`Sums_User_Groups` WHERE `id` = ?";
		$query = $this->db->query($roleNameQuery,array($smartUser['role']));
		$roleName = $query->result();
	
		foreach($query->result() as $row){
			$ManagersRole=$row->userGroupName;
		}
		
		$insertQuery = "INSERT INTO `shiksha`.`sales_hierarchy` ( `id` , `userId` , `managerId` , `role` , `status` ) VALUES ( NULL ,?,?,?, 'ACTIVE' );";
	
		if (!($this->db->query($insertQuery,array($smartUser['userid'],$smartUser['managerid'],$ManagersRole)))) {
			$flag = 0;
		}else{
			$flag = 1;
		}
		
		return $flag;
	}
			
	/**
	 *Update if user exists in sales_hierarchy table
	*/
	function updateSmartUser($smartUser,$empID){
		
		$empQuery = "select EmployeeId from `SUMS`.`Sums_User_Details` where  userId= ?";
		$empQueryResult = $this->db->query($empQuery,array($smartUser['userid']))->result_array();
		foreach($empQueryResult as $row) {
			$empid_to_check = $row['EmployeeId'];	
		}
		
		if(!empty($empid_to_check) && !empty($empID) && $empid_to_check !=$empID) {
			return 6;
		}
				
		$ManagersRole=array();
		$roleNameQuery ="SELECT * FROM `SUMS`.`Sums_User_Groups` WHERE `id` = ?";
		$query = $this->db->query($roleNameQuery,array($smartUser['role']));
		$roleName = $query->result();
		
		foreach($query->result() as $row){
			$ManagersRole=$row->userGroupName;
		}
		
		$updateQuery = "update sales_hierarchy set managerId=?, role =?, status = 'ACTIVE' where userId =?";
		
		if (!($this->db->query($updateQuery,array($smartUser['managerid'],$ManagersRole,$smartUser['userid'])))) {			   
			$flag = 0;
		
		}else{
			$flag = 1;
		}
		return $flag;
	}
	
	/**
	 *Function to get User Rolls from table Sums_User_Groups
	*/
	function getUserRoles(){
		
		$this->initiateModel("read","SUMS");
		$role = array();
		$sql = "SELECT DISTINCT `userGroupName`,id FROM `Sums_User_Groups`";
		$result = $this->db->query($sql);
		if($result->result_array()){
			
			foreach ($result->result_array() as $row){
				$role[$row['id']] = $row['userGroupName'];
			}
		}
		return $role;
	}
	
	/**
	 *Function to get name of branch
	 */
	function getBranch(){
		$this->initiateModel("read","SUMS");
		$branch = array();
		$sql ="SELECT DISTINCT `BranchName`,BranchId FROM `Sums_Branch_List`";
		$result = $this->db->query($sql);
	
		if($result->result_array()){
			
			foreach ($result->result_array() as $row){
				$branch[$row['BranchId']] = $row['BranchName'];
			}
		}
		return $branch;
	}
	
	
	function checkUserInSums_User_Detail($userId){

		$this->initiateModel("read","SUMS");
		
		$query = "SELECT *  FROM `Sums_User_Details` WHERE `userId` = ?";
		$sql = $this->db->query($query, array($userId));
		if($sql->result()){
			return 0;
		}else{
			return 1;
		}
	}
	
	
	/**
	 *Function to insert into Sums_User_Details
	 */
	function insertSumsUserDetails($empid, $useremail, $managerEmail, $role){
		
		//$this->initiateModel("write","SUMS");
		
		$empIdAlreadyExistsWithDifferentUserId = 0;	//Assuming that the empid entered is unique
		$empIdAlreadyExists = 0;			//Assuming empid does not exist
		$userID = $this->getUserIdByEmail($useremail);
		$managerID = $this->getUserIdByEmail($managerEmail);
		$userIdExists = 1;

		// Here we are checking if the given userID exists in the table Sums_User_Details. If it does not exist, then the flow for insert
		if($this->checkUserInSums_User_Detail($userID)){	
		        $this->initiateModel("write","SUMS");	
			//Here we check if given employye Id exists in the table
			$empQuery = "select * from `SUMS`.`Sums_User_Details` where EmployeeId = ?;";
			error_log("===== Stage 2 :  empQuery = ".$empQuery);
			$resultEmp = $this->db->query($empQuery, array($empid));
			if($resultEmp){
				foreach($resultEmp->result_array() as $row){
					$empIdAlreadyExists = 1;	//Employee ID exists
					$empIdAlreadyExistsWithDifferentUserId = 1;	//Employee ID is not unique
					$userIdExists = 1;
				}
			}
			error_log("===== Stage 2.1 :  empIdAlreadyExists = ".$empIdAlreadyExists);
				
			//If employee id does not exist in the table then we insert the details in sums_user_details
			if($empIdAlreadyExists == 0){
				error_log("===== Stage 2.2 :  ");
				$empIdAlreadyExistsWithDifferentUserId = 0;
				$userIdExists = 1;
				$insertQuery = "INSERT INTO `SUMS`.`Sums_User_Details` (`EmployeeId`, `userId`, `managerId`, `Role`, `DiscountLimit`) VALUES (?, ?, ?, ?, '');";
				
				if (!($this->db->query($insertQuery,array($empid,$userID,$managerID,$role)))) {
					error_log("====== insertSumsUserDetails : stage 1");
					$flag = 0;
				}else{
					error_log("====== insertSumsUserDetails : stage 2");
					$flag = 1;
				}
				
			}
			
			
		}else{
                        $this->initiateModel("write","SUMS");
			//If user Id exists in the table, we update if the given empID matches the corresponding value in the table
			$flag = 0;
			$userIdExists = 0;
			$empQuery = "select * from `SUMS`.`Sums_User_Details` where EmployeeId = ?;";
			$resultEmp = $this->db->query($empQuery, array($empid));
			
			if($resultEmp->num_rows() > 0){
				$empIdAlreadyExistsWithDifferentUserId = 0;
				foreach($resultEmp->result_array() as $row){
					//$empIdAlreadyExists = 1;
					if($row['userId'] != $userID){
						$empIdAlreadyExistsWithDifferentUserId = 1;	//Employee ID exists for another userID
					}
				}
				if($empIdAlreadyExistsWithDifferentUserId == 0){	//If employeeID exists for the same user ID as entered then we update
					error_log("====== insertSumsUserDetails : stage 3.3");
					$updateQuery = "UPDATE `SUMS`.`Sums_User_Details` SET `managerId` = ?,`Role` = ? WHERE `Sums_User_Details`.`userId` =? AND `EmployeeId` =?;";
					if (!($this->db->query($updateQuery,array($managerID,$role,$userID,$empid)))){
						$flag = 0;
					}else{
						$flag = 1;
						$userIdExists = 1;
						error_log("===== Stage 3.4 :  empIdAlreadyExistsWithDifferentUserId = ".$empIdAlreadyExistsWithDifferentUserId);
					}
					$userIdExists = 1;
				}else{
					$flag = 0;
					$userIdExists = 1;
				}
			}else{
				$flag = 0;
				$userIdExists = 0;
				}
		}
		error_log("===== Stage Mahak :Different employee id for the same userID = ".$userIdExists);
		return array("flag" => $flag, "empIdAlreadyExistsWithDifferentUserId" => $empIdAlreadyExistsWithDifferentUserId, "userIdExists" => $userIdExists);
		
	}
	
	/**
	 *Function to insert into Sums_User_Branch_Mapping
	 */
	
	function insertSumsUserBranchMapping($userEmail,$branchID){
		//$this->initiateModel("write","SUMS");
		
		

		//error_log("====== insertSumsUserBranchMapping stage 0 : ");
			$userID = $this->getUserIdByEmail($userEmail);
			$checkQuery = "SELECT * FROM `SUMS`.`Sums_User_Branch_Mapping` WHERE `userId` = ?";

		//	error_log("====== insertSumsUserBranchMapping stage 1 : ".$checkQuery);
                        $this->initiateModel("write","SUMS"); 
			if(!($this->db->query($checkQuery,array($userID))->result())){
			$insertQuery= "INSERT INTO `SUMS`.`Sums_User_Branch_Mapping` (`userId`, `BranchId`) VALUES (?,?);";
			$flag = 0;

		//	error_log("====== insertSumsUserBranchMapping ".$insertQuery);
			$rs = $this->db->query($insertQuery,array($userID,$branchID));
				if (!$this->db->affected_rows()) {
		//			error_log("====== FAIL");
					$flag = 0;
				}else{
		//			error_log("====== PASS");
					$flag = 1;
				}
			}else{
				$updateQuery = "UPDATE `SUMS`.`Sums_User_Branch_Mapping` SET `BranchId` = ? WHERE `Sums_User_Branch_Mapping`.`userId` = ?";
				$resultUpdate = $this->db->query($updateQuery,array($branchID,$userID));
				if(!$resultUpdate){
					$flag = 0;
				}else{
					$flag = 1;
				}
			}
		
		return $flag;
	}

	function getClientLoginDetails($userId) {
		
		$this->initiateModel("read","User");
		$sql = 'SELECT * FROM tuser WHERE userid = ?';
		$result = $this->db->query($sql,array($userId));

		return $result->result_array();
	}

	function insertClientLoginDetails($data=array()) { //ClientAutoLoginTable

		$this->initiateModel("write","User");
		$sql = 'insert into shiksha.ClientAutoLoginTable (`loginBy_id`, `loginTo_id`) values (?, ?) ';
		$result = $this->db->query($sql,array($data['loginById'],$data['loginToId']));
		
	}

	function getClientIdsFromInstituteIds($instituteIds){

		$this->initiateModel("read","User");
			
		$clientIds = array();

		$sqlForClientIds = "SELECT GROUP_CONCAT( DISTINCT username ) as clientIds FROM `listings_main` 
							WHERE `listing_type_id` in ( $instituteIds )
							AND `status` in ( 'live' , 'deleted' ) 
							AND `listing_type` in ('institute','university_national') ";

		$result = $this->db->query($sqlForClientIds);
		
		$clientIdsArray = $result->result_array();
		$clientIds = $clientIdsArray[0]['clientIds'];
		return $clientIds;

	}
	
		function getClientDetails($userIds, $categoryId, $subCategoryId, $startDate, $endDate) {
				return false;
				$this->initiateModel("read","User");
				
				$returnData = array();
				$instituteIdsArray = array();

				$startDate = date('Y-m-d 00:00:00',strtotime($startDate));
				$endDate = date('Y-m-d 23:59:59',strtotime($endDate));
								
				if (isset($subCategoryId) && $subCategoryId != '') {
						
						if($userIds != ''){
								$sql1 = "SELECT DISTINCT a.username, a.listing_type_id, a.subscriptionId, c.boardId, c.name, d.TransactionId
										FROM shiksha.listings_main a, shiksha.categoryPageData b, shiksha.categoryBoardTable c, SUMS.Subscription d 
										WHERE a.username in ( $userIds ) 
										AND a.listing_type_id = b.course_id
										AND a.listing_type = 'course' 
										AND a.subscriptionId > 0
										AND c.parentId = ? AND c.boardId = ?
										AND c.boardId = b.category_id 
										AND a.subscriptionId = d.SubscriptionId
										AND a.submit_date >= ? AND a.submit_date <= ? ";
										
								$result1 = $this->db->query($sql1,array($categoryId, $subCategoryId, $startDate, $endDate));
								
								$sql4 = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.bannerid, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
										FROM SUMS.Subscription a, shiksha.tbannerlinks b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d 
										WHERE a.ClientUserId in ( $userIds ) 
										AND b.subcategoryid = ? 
										AND a.SubscriptionId = b.subscriptionid 
										AND d.SubscriptionId = a.SubscriptionId 
										AND a.TransactionId = c.TransactionId
										AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ?
										AND b.startdate >= ? AND b.startdate <= ? 
										AND a.ClientUserId = c.ClientUserId ";
										
								$result4 = $this->db->query($sql4,array($subCategoryId, $startDate, $endDate, $startDate, $endDate));

								$sqlCatSponsor = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.listing_type_id, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
										FROM SUMS.Subscription a, shiksha.tlistingsubscription b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d 
										WHERE a.ClientUserId in ( $userIds ) 
										AND b.subcategory = ? 
										AND b.listing_type = 'institute' 
										AND a.SubscriptionId = b.subscriptionid 
										AND d.SubscriptionId = a.SubscriptionId 
										AND a.TransactionId = c.TransactionId
										AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ?
										AND b.startdate >= ? AND b.startdate <= ? 
										AND a.ClientUserId = b.clientid 
										AND a.ClientUserId = c.ClientUserId ";
										
								$resultCatSponsor = $this->db->query($sqlCatSponsor,array($subCategoryId, $startDate, $endDate, $startDate, $endDate));
										
						} else {
								$sql1 = "SELECT DISTINCT a.username, a.listing_type_id, a.subscriptionId, c.boardId, c.name, d.TransactionId
										FROM shiksha.listings_main a, shiksha.categoryPageData b, shiksha.categoryBoardTable c, SUMS.Subscription d 
										WHERE a.listing_type_id = b.course_id
										AND a.listing_type = 'course'
										AND a.subscriptionId > 0
										AND c.parentId = ? AND c.boardId = ?
										AND c.boardId = b.category_id
										AND a.subscriptionId = d.SubscriptionId
										AND a.submit_date >= ? AND a.submit_date <= ? ";
												
								$result1 = $this->db->query($sql1,array($categoryId, $subCategoryId, $startDate, $endDate));
								
								$sql4 = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.bannerid, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate 
										FROM SUMS.Subscription a, shiksha.tbannerlinks b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
										WHERE b.subcategoryid = ? 
										AND a.SubscriptionId = b.subscriptionid 
										AND d.SubscriptionId = a.SubscriptionId 
										AND a.TransactionId = c.TransactionId
										AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ?
										AND b.startdate >= ? AND b.startdate <= ? 
										AND a.ClientUserId = c.ClientUserId ";
										
								$result4 = $this->db->query($sql4,array($subCategoryId, $startDate, $endDate, $startDate, $endDate));

								$sqlCatSponsor = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.listing_type_id, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
										FROM SUMS.Subscription a, shiksha.tlistingsubscription b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d 
										WHERE b.subcategory = ? 
										AND b.listing_type = 'institute' 
										AND a.SubscriptionId = b.subscriptionid 
										AND d.SubscriptionId = a.SubscriptionId 
										AND a.TransactionId = c.TransactionId
										AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ?
										AND b.startdate >= ? AND b.startdate <= ? 
										AND a.ClientUserId = b.clientid 
										AND a.ClientUserId = c.ClientUserId ";
										
								$resultCatSponsor = $this->db->query($sqlCatSponsor,array($subCategoryId, $startDate, $endDate, $startDate, $endDate));
								
						}
						
				} else {
						
						$sql4a = "SELECT GROUP_CONCAT(boardId) FROM shiksha.categoryBoardTable WHERE parentId = ? AND flag = 'national' ";

						$result4a = $this->db->query($sql4a,array($categoryId));

						foreach ($result4a->result_array() as $row){
								$subCategoryIds = $row['GROUP_CONCAT(boardId)'];
						}
						
						if($userIds != ''){
								$sql1 = "SELECT DISTINCT a.username, a.listing_type_id, a.subscriptionId, c.boardId, c.name, d.TransactionId
										FROM shiksha.listings_main a, shiksha.categoryPageData b, shiksha.categoryBoardTable c, SUMS.Subscription d 
										WHERE a.username in ( $userIds ) 
										AND a.listing_type_id = b.course_id
										AND a.listing_type = 'course' 
										AND a.subscriptionId > 0
										AND c.parentId = ? AND c.boardId = b.category_id
										AND a.subscriptionId = d.SubscriptionId
										AND a.submit_date >= ? AND a.submit_date <= ? ";
												
								$result1 = $this->db->query($sql1,array($categoryId, $startDate, $endDate));
								
								$sql4 = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.bannerid, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate 
										FROM SUMS.Subscription a, shiksha.tbannerlinks b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
										WHERE a.ClientUserId in ( $userIds )
										AND ( b.categoryid = ? OR b.subcategoryid in ( $subCategoryIds ) )
										AND a.SubscriptionId = b.subscriptionid 
										AND d.SubscriptionId = a.SubscriptionId 
										AND a.TransactionId = c.TransactionId
										AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ?
										AND b.startdate >= ? AND b.startdate <= ? 
										AND a.ClientUserId = c.ClientUserId ";
										
								$result4 = $this->db->query($sql4,array($categoryId, $startDate, $endDate, $startDate, $endDate));

								$sqlCatSponsor = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.listing_type_id, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
										FROM SUMS.Subscription a, shiksha.tlistingsubscription b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d 
										WHERE a.ClientUserId in ( $userIds ) 
										AND ( b.categoryid = ? OR b.subcategory in ( $subCategoryIds ) ) 
										AND b.listing_type = 'institute' 
										AND a.SubscriptionId = b.subscriptionid 
										AND d.SubscriptionId = a.SubscriptionId 
										AND a.TransactionId = c.TransactionId
										AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ?
										AND b.startdate >= ? AND b.startdate <= ? 
										AND a.ClientUserId = b.clientid 
										AND a.ClientUserId = c.ClientUserId ";
										
								$resultCatSponsor = $this->db->query($sqlCatSponsor,array($categoryId, $startDate, $endDate, $startDate, $endDate));
								
						} else {
								$sql1 = "SELECT DISTINCT a.username, a.listing_type_id, a.subscriptionId, c.boardId, c.name, d.TransactionId
										FROM shiksha.listings_main a, shiksha.categoryPageData b, shiksha.categoryBoardTable c, SUMS.Subscription d 
										WHERE a.listing_type_id = b.course_id
										AND a.listing_type = 'course' 
										AND a.subscriptionId > 0
										AND c.parentId = ? AND c.boardId = b.category_id
										AND a.subscriptionId = d.SubscriptionId
										AND a.submit_date >= ? AND a.submit_date <= ? ";
												
								$result1 = $this->db->query($sql1,array($categoryId, $startDate, $endDate));
								
								$sql4 = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.bannerid, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate 
										FROM SUMS.Subscription a, shiksha.tbannerlinks b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
										WHERE ( b.categoryid = ? OR b.subcategoryid in ( $subCategoryIds ) )
										AND a.SubscriptionId = b.subscriptionid 
										AND d.SubscriptionId = a.SubscriptionId 
										AND a.TransactionId = c.TransactionId
										AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ?
										AND b.startdate >= ? AND b.startdate <= ? 
										AND a.ClientUserId = c.ClientUserId ";
										
								$result4 = $this->db->query($sql4,array($categoryId, $startDate, $endDate, $startDate, $endDate));

								$sqlCatSponsor = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.listing_type_id, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
										FROM SUMS.Subscription a, shiksha.tlistingsubscription b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d 
										WHERE ( b.categoryid = ? OR b.subcategory in ( $subCategoryIds ) ) 
										AND b.listing_type = 'institute' 
										AND a.SubscriptionId = b.subscriptionid 
										AND d.SubscriptionId = a.SubscriptionId 
										AND a.TransactionId = c.TransactionId
										AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ?
										AND b.startdate >= ? AND b.startdate <= ? 
										AND a.ClientUserId = b.clientid 
										AND a.ClientUserId = c.ClientUserId ";
										
								$resultCatSponsor = $this->db->query($sqlCatSponsor,array($categoryId, $startDate, $endDate, $startDate, $endDate));
								
						}
				}
				
				foreach ($result1->result_array() as $row){
						
						$returnData[$row['username']]['Listing Details'][$row['subscriptionId']]['Client ID'] = $row['username'];
						$returnData[$row['username']]['Listing Details'][$row['subscriptionId']]['Gold Listings'][$row['listing_type_id']]['ID'] = $row['listing_type_id'];
						$returnData[$row['username']]['Listing Details'][$row['subscriptionId']]['Gold Listings'][$row['listing_type_id']]['Subscription'] = $row['subscriptionId'];
						$returnData[$row['username']]['Listing Details'][$row['subscriptionId']]['Gold Listings'][$row['listing_type_id']]['Transaction'] = $row['TransactionId'];
						$returnData[$row['username']]['Listing Details'][$row['subscriptionId']]['Gold Listings'][$row['listing_type_id']]['Sub Category'] = $row['name'];
						
				}
				
				foreach ($result4->result_array() as $row){
								
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientUserId'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'Banner';
								
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
						$subsIds[$row['ClientUserId']][] = $row['SubscriptionId'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Derived Product ID'][$row['SubscriptionId']] = $row['DerivedProductId'];
						$derivedProdIds[$row['ClientUserId']][] = $row['DerivedProductId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Currency ID'][$row['SubscriptionId']] = $row['CurrencyId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Quantity'][$row['SubscriptionId']] = $row['TotalBaseProdQuantity'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
						$transIds[$row['ClientUserId']][] = $row['TransactionId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Product Category'][$row['SubscriptionId']] = 'Banner';
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Product Sub Category'][$row['SubscriptionId']] = 'CS';
						
						
				}

				foreach ($resultCatSponsor->result_array() as $row){
								
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientUserId'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'Sticky';
								
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
						$subsIds[$row['ClientUserId']][] = $row['SubscriptionId'];
						if(!in_array($row['listing_type_id'],$instituteIdsArray)) {
								$instituteIdsArray[] = $row['listing_type_id'];
						}

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Derived Product ID'][$row['SubscriptionId']] = $row['DerivedProductId'];
						$derivedProdIds[$row['ClientUserId']][] = $row['DerivedProductId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Currency ID'][$row['SubscriptionId']] = $row['CurrencyId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Quantity'][$row['SubscriptionId']] = $row['TotalBaseProdQuantity'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
						$transIds[$row['ClientUserId']][] = $row['TransactionId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Product Category'][$row['SubscriptionId']] = 'Sticky';
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Product Sub Category'][$row['SubscriptionId']] = 'Sticky';
						
						
				}

				if($userIds != '') {
						
						$sql6 = "SELECT DISTINCT e.ClientUserId, a.subscriptionId, e.TransactionId, a.listing_type_id
								FROM shiksha.PageCollegeDb a, SUMS.Subscription e
								WHERE e.ClientUserId in ( $userIds )
								AND a.listing_type = 'institute'
								AND a.subscriptionId > 0
								AND a.subscriptionId = e.SubscriptionId
								AND a.StartDate >= ? 
								AND a.StartDate <= ? ";
						
						$result6 = $this->db->query($sql6,array($startDate, $endDate));
						
				} else {
						
						$sql6 = "SELECT DISTINCT e.ClientUserId, a.subscriptionId, e.TransactionId, a.listing_type_id
								FROM shiksha.PageCollegeDb a, SUMS.Subscription e
								WHERE a.listing_type = 'institute'
								AND a.subscriptionId > 0
								AND a.subscriptionId = e.SubscriptionId
								AND a.StartDate >= ? 
								AND a.StartDate <= ? ";
						
						$result6 = $this->db->query($sql6,array($startDate, $endDate));
						
				}
				
				foreach ($result6->result_array() as $row){
		
						$returnData[$row['ClientUserId']]['Listing Details'][$row['subscriptionId']]['Client ID'] = $row['ClientUserId'];
						$returnData[$row['ClientUserId']]['Listing Details'][$row['subscriptionId']]['MI Listings'][$row['listing_type_id']]['Institute ID'] = $row['listing_type_id'];
						if(!in_array($row['listing_type_id'],$instituteIdsArray)) {
								$instituteIdsArray[] = $row['listing_type_id'];
						}
						$returnData[$row['ClientUserId']]['Listing Details'][$row['subscriptionId']]['MI Listings'][$row['listing_type_id']]['Subscription'] = $row['subscriptionId'];
						$returnData[$row['ClientUserId']]['Listing Details'][$row['subscriptionId']]['MI Listings'][$row['listing_type_id']]['Transaction'] = $row['TransactionId'];
						
				}
				
				$instituteIds = htmlspecialchars(implode(',',$instituteIdsArray),ENT_QUOTES);
				
				if(!empty($instituteIdsArray)) {
						
						if (isset($subCategoryId) && $subCategoryId != '') {
								
								$sql6a = "SELECT DISTINCT c.institute_id, c.course_id, d.boardId, d.name
										FROM shiksha.categoryPageData c, shiksha.categoryBoardTable d
										WHERE c.institute_id
										IN ( $instituteIds )
										AND d.parentId = ? 
										AND d.boardId = ? 
										AND d.boardId = c.category_id";
										
								$result6a = $this->db->query($sql6a,array($categoryId,$subCategoryId));
								
						} else {
								
								$sql6a = "SELECT DISTINCT c.institute_id, c.course_id, d.boardId, d.name
										FROM shiksha.categoryPageData c, shiksha.categoryBoardTable d
										WHERE c.institute_id
										IN ( $instituteIds )
										AND d.parentId = ? 
										AND d.boardId = c.category_id";
										
								$result6a = $this->db->query($sql6a,array($categoryId));
										
						}
						
						foreach ($result6a->result_array() as $row){
								
								$courses[$row['institute_id']][$row['course_id']]['Course ID'] = $row['course_id'];
								$courses[$row['institute_id']][$row['course_id']]['Sub Category'] = $row['name'];
								
						}
						
				}
				
				if($userIds != ''){
						
						$sqlMailer = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.BaseProdCategory, b.BaseProdSubCategory, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
								FROM SUMS.Subscription a, SUMS.Base_Products b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
								WHERE a.ClientUserId in ( $userIds ) 
								AND a.BaseProductId = b.BaseProductId 
								AND a.ClientUserId = c.ClientUserId 
								AND b.BaseProdCategory = 'Mass-Mailer' 
								AND a.TransactionId = c.TransactionId
								AND d.SubscriptionId = a.SubscriptionId
								AND d.BaseProductId = a.BaseProductId
								AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? ";
								
				} else {
						
						$sqlMailer = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.BaseProdCategory, b.BaseProdSubCategory, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
								FROM SUMS.Subscription a, SUMS.Base_Products b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
								WHERE a.BaseProductId = b.BaseProductId 
								AND a.ClientUserId = c.ClientUserId 
								AND b.BaseProdCategory = 'Mass-Mailer' 
								AND a.TransactionId = c.TransactionId
								AND d.SubscriptionId = a.SubscriptionId
								AND d.BaseProductId = a.BaseProductId
								AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? ";
								
				}
				
				$result = $this->db->query($sqlMailer,array($startDate, $endDate));
				
				foreach ($result->result_array() as $row){
								
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientUserId'];
						
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'Mailer';
						
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
						$subsIds[$row['ClientUserId']][] = $row['SubscriptionId'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Derived Product ID'][$row['SubscriptionId']] = $row['DerivedProductId'];
						$derivedProdIds[$row['ClientUserId']][] = $row['DerivedProductId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Currency ID'][$row['SubscriptionId']] = $row['CurrencyId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Quantity'][$row['SubscriptionId']] = $row['TotalBaseProdQuantity'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
						$transIds[$row['ClientUserId']][] = $row['TransactionId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Product Category'][$row['SubscriptionId']] = $row['BaseProdCategory'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Product Sub Category'][$row['SubscriptionId']] = $row['BaseProdSubCategory'];
						
						
				}

				if($userIds != ''){
						
						$sqlBanner = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.BaseProdCategory, b.BaseProdSubCategory, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
								FROM SUMS.Subscription a, SUMS.Base_Products b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
								WHERE a.ClientUserId in ( $userIds ) 
								AND a.BaseProductId = b.BaseProductId 
								AND a.ClientUserId = c.ClientUserId 
								AND b.BaseProdCategory = 'Banner Campaign' 
								AND a.TransactionId = c.TransactionId
								AND d.SubscriptionId = a.SubscriptionId
								AND d.BaseProductId = a.BaseProductId
								AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? ";
								
				} else {
						
						$sqlBanner = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.BaseProdCategory, b.BaseProdSubCategory, c.TotalTransactionPrice, c.CurrencyId, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
								FROM SUMS.Subscription a, SUMS.Base_Products b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
								WHERE a.BaseProductId = b.BaseProductId 
								AND a.ClientUserId = c.ClientUserId 
								AND b.BaseProdCategory = 'Banner Campaign' 
								AND a.TransactionId = c.TransactionId
								AND d.SubscriptionId = a.SubscriptionId
								AND d.BaseProductId = a.BaseProductId
								AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? ";
								
				}
				
				$result = $this->db->query($sqlBanner,array($startDate, $endDate));
				
				foreach ($result->result_array() as $row){
								
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientUserId'];
						
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'Other Banners';
						
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
						$subsIds[$row['ClientUserId']][] = $row['SubscriptionId'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Derived Product ID'][$row['SubscriptionId']] = $row['DerivedProductId'];
						$derivedProdIds[$row['ClientUserId']][] = $row['DerivedProductId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Currency ID'][$row['SubscriptionId']] = $row['CurrencyId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Quantity'][$row['SubscriptionId']] = $row['TotalBaseProdQuantity'];

						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
						$transIds[$row['ClientUserId']][] = $row['TransactionId'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Product Category'][$row['SubscriptionId']] = $row['BaseProdCategory'];
						$returnData[$row['ClientUserId']]['Product Details'][$row['TransactionId']]['Product Sub Category'][$row['SubscriptionId']] = $row['BaseProdSubCategory'];
						
						
				}
				
				if($userIds != '') {

					if($categoryId == '3' && ($subCategoryId == '' || $subCategoryId == '23')) {

							$sql7SA = "SELECT GROUP_CONCAT( DISTINCT c.searchagentid ) as searchAgentIds
									FROM shiksha.SAMultiValuedSearchCriteria a, shiksha.categoryPageData b, shiksha.SASearchAgent c 
									WHERE c.clientid in ( $userIds ) 
									AND c.searchagentid = a.searchAlertId
									AND b.course_id = a.value 
									AND b.category_id = 23
									AND a.keyname = 'clientcourse' 
									AND c.deliveryMethod = 'normal' 
									AND c.type = 'response'
									AND c.created_on >= ? AND c.created_on <= ? ";

							$result7SA = $this->db->query($sql7SA,array($startDate, $endDate));

							foreach ($result7SA->result_array() as $row){
									$searchAgentIds = $row['searchAgentIds'];
							}

							if($searchAgentIds != '') {

								$sql7 = "SELECT DISTINCT c.TransactionId, c.TotalTransactionPrice, e.ClientId, d.userid, e.CreditConsumed, e.SubscriptionId, b.SubscriptionStartDate, b.SubscriptionEndDate
										FROM SUMS.Subscription a, SUMS.Subscription_Product_Mapping b, SUMS.Transaction c, shiksha.SALeadAllocation d, shiksha.LDBLeadContactedTracking e 
										WHERE e.ClientId in ( $userIds ) 
										AND d.agentid in ( $searchAgentIds )
										AND e.UserId = d.userid
										AND e.activity_flag = 'SA'
										AND e.CreditConsumed > 0 
										AND a.SubscriptionId = b.SubscriptionId 
										AND a.SubscriptionId = e.SubscriptionId 
										AND a.TransactionId = c.TransactionId  
										AND b.SubscriptionStartDate >= ? AND b.SubscriptionStartDate <= ? 
										AND e.ContactDate >= ? AND e.ContactDate <= ? 
										AND d.allocationtime >= ? AND d.allocationtime <= ? ";
										
								$result7 = $this->db->query($sql7,array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
								
								foreach ($result7->result_array() as $row){

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientId'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'MR';
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
										$subsIds[$row['ClientId']][] = $row['SubscriptionId'];
										
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
										$transIds[$row['ClientId']][] = $row['TransactionId'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Credit Consumed'] += $row['CreditConsumed'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['View Count'] += 1;

								}
							
							}

							$sql7a = "SELECT DISTINCT a.ClientId, a.CreditConsumed, a.SubscriptionId, b.ViewCount, e.TransactionId, e.TotalTransactionPrice, d.SubscriptionStartDate, d.SubscriptionEndDate
									FROM shiksha.LDBLeadContactedTracking a, shiksha.LDBLeadViewCount b, SUMS.Subscription c, SUMS.Subscription_Product_Mapping d, SUMS.Transaction e 
									WHERE a.ClientId in ( $userIds )
									AND a.UserId = b.UserId
									AND b.DesiredCourse = 2 
									AND b.Flag = 'national'
									AND a.SubscriptionId = c.SubscriptionId 
									AND c.SubscriptionId = d.SubscriptionId 
									AND c.TransactionId = e.TransactionId  
									AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? 
									AND b.UpdateTime >= ? AND b.UpdateTime <= ?
									AND a.ContactDate >= ? AND a.ContactDate <= ? 
									AND a.activity_flag = 'LDB' 
									AND a.CreditConsumed != 0 ";
									
							$result7a = $this->db->query($sql7a,array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
							
							foreach ($result7a->result_array() as $row){

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientId'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'MR';
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
									$subsIds[$row['ClientId']][] = $row['SubscriptionId'];
									
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
									$transIds[$row['ClientId']][] = $row['TransactionId'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Credit Consumed'] += $row['CreditConsumed'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['View Count'] += $row['ViewCount'];

							}
							
					} else if($categoryId == '2' && ($subCategoryId == '' || $subCategoryId == '56')) {
							
							$sql7SA = "SELECT GROUP_CONCAT( DISTINCT c.searchagentid ) as searchAgentIds
									FROM shiksha.SAMultiValuedSearchCriteria a, shiksha.categoryPageData b, shiksha.SASearchAgent c 
									WHERE c.clientid in ( $userIds ) 
									AND c.searchagentid = a.searchAlertId
									AND b.course_id = a.value 
									AND b.category_id = 56
									AND a.keyname = 'clientcourse' 
									AND c.deliveryMethod = 'normal' 
									AND c.type = 'response'
									AND c.created_on >= ? AND c.created_on <= ? ";

							$result7SA = $this->db->query($sql7SA,array($startDate, $endDate));

							foreach ($result7SA->result_array() as $row){
									$searchAgentIds = $row['searchAgentIds'];
							}

							if($searchAgentIds != '') {

								$sql7 = "SELECT DISTINCT c.TransactionId, c.TotalTransactionPrice, e.ClientId, d.userid, e.CreditConsumed, e.SubscriptionId, b.SubscriptionStartDate, b.SubscriptionEndDate
										FROM SUMS.Subscription a, SUMS.Subscription_Product_Mapping b, SUMS.Transaction c, shiksha.SALeadAllocation d, shiksha.LDBLeadContactedTracking e 
										WHERE e.ClientId in ( $userIds ) 
										AND d.agentid in ( $searchAgentIds )
										AND e.UserId = d.userid
										AND e.activity_flag = 'SA'
										AND e.CreditConsumed > 0 
										AND a.SubscriptionId = b.SubscriptionId 
										AND a.SubscriptionId = e.SubscriptionId 
										AND a.TransactionId = c.TransactionId  
										AND b.SubscriptionStartDate >= ? AND b.SubscriptionStartDate <= ? 
										AND e.ContactDate >= ? AND e.ContactDate <= ? 
										AND d.allocationtime >= ? AND d.allocationtime <= ? ";
										
								$result7 = $this->db->query($sql7,array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
								
								foreach ($result7->result_array() as $row){

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientId'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'MR';
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
										$subsIds[$row['ClientId']][] = $row['SubscriptionId'];
										
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
										$transIds[$row['ClientId']][] = $row['TransactionId'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Credit Consumed'] += $row['CreditConsumed'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['View Count'] += 1;

								}

							}
							
							$sql7a = "SELECT DISTINCT a.ClientId, a.CreditConsumed, a.SubscriptionId, b.ViewCount, e.TransactionId, e.TotalTransactionPrice, d.SubscriptionStartDate, d.SubscriptionEndDate
									FROM shiksha.LDBLeadContactedTracking a, shiksha.LDBLeadViewCount b, SUMS.Subscription c, SUMS.Subscription_Product_Mapping d, SUMS.Transaction e 
									WHERE a.ClientId in ( $userIds )
									AND a.UserId = b.UserId
									AND b.DesiredCourse = 52 
									AND b.Flag = 'national'
									AND a.SubscriptionId = c.SubscriptionId 
									AND c.SubscriptionId = d.SubscriptionId 
									AND c.TransactionId = e.TransactionId  
									AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? 
									AND b.UpdateTime >= ? AND b.UpdateTime <= ?
									AND a.ContactDate >= ? AND a.ContactDate <= ? 
									AND a.activity_flag = 'LDB' 
									AND a.CreditConsumed != 0 ";
									
							$result7a = $this->db->query($sql7a,array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
							
							foreach ($result7a->result_array() as $row){

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientId'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'MR';
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
									$subsIds[$row['ClientId']][] = $row['SubscriptionId'];
									
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
									$transIds[$row['ClientId']][] = $row['TransactionId'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Credit Consumed'] += $row['CreditConsumed'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['View Count'] += $row['ViewCount'];

							}
					}

				} else {

					if($categoryId == '3' && ($subCategoryId == '' || $subCategoryId == '23')) {

							$sql7SA = "SELECT GROUP_CONCAT( DISTINCT c.searchagentid ) as searchAgentIds
									FROM shiksha.SAMultiValuedSearchCriteria a, shiksha.categoryPageData b, shiksha.SASearchAgent c 
									WHERE c.searchagentid = a.searchAlertId
									AND b.course_id = a.value 
									AND b.category_id = 23
									AND a.keyname = 'clientcourse' 
									AND c.deliveryMethod = 'normal' 
									AND c.type = 'response'
									AND c.created_on >= ? AND c.created_on <= ? ";

							$result7SA = $this->db->query($sql7SA,array($startDate, $endDate));

							foreach ($result7SA->result_array() as $row){
									$searchAgentIds = $row['searchAgentIds'];
							}

							if($searchAgentIds != '') {

								$sql7 = "SELECT DISTINCT c.TransactionId, c.TotalTransactionPrice, e.ClientId, d.userid, e.CreditConsumed, e.SubscriptionId, b.SubscriptionStartDate, b.SubscriptionEndDate
										FROM SUMS.Subscription a, SUMS.Subscription_Product_Mapping b, SUMS.Transaction c, shiksha.SALeadAllocation d, shiksha.LDBLeadContactedTracking e 
										WHERE d.agentid in ( $searchAgentIds )
										AND e.UserId = d.userid
										AND e.activity_flag = 'SA'
										AND e.CreditConsumed > 0 
										AND a.SubscriptionId = b.SubscriptionId 
										AND a.SubscriptionId = e.SubscriptionId 
										AND a.TransactionId = c.TransactionId  
										AND b.SubscriptionStartDate >= ? AND b.SubscriptionStartDate <= ? 
										AND e.ContactDate >= ? AND e.ContactDate <= ? 
										AND d.allocationtime >= ? AND d.allocationtime <= ? ";
										
								$result7 = $this->db->query($sql7,array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
								
								foreach ($result7->result_array() as $row){

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientId'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'MR';
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
										$subsIds[$row['ClientId']][] = $row['SubscriptionId'];
										
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
										$transIds[$row['ClientId']][] = $row['TransactionId'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Credit Consumed'] += $row['CreditConsumed'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['View Count'] += 1;

								}

							}
							
							$sql7a = "SELECT DISTINCT a.ClientId, a.CreditConsumed, a.SubscriptionId, b.ViewCount, e.TransactionId, e.TotalTransactionPrice, d.SubscriptionStartDate, d.SubscriptionEndDate
									FROM shiksha.LDBLeadContactedTracking a, shiksha.LDBLeadViewCount b, SUMS.Subscription c, SUMS.Subscription_Product_Mapping d, SUMS.Transaction e 
									WHERE a.UserId = b.UserId
									AND b.DesiredCourse = 2 
									AND b.Flag = 'national'
									AND a.SubscriptionId = c.SubscriptionId 
									AND c.SubscriptionId = d.SubscriptionId 
									AND c.TransactionId = e.TransactionId  
									AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? 
									AND b.UpdateTime >= ? AND b.UpdateTime <= ?
									AND a.ContactDate >= ? AND a.ContactDate <= ? 
									AND a.activity_flag = 'LDB' 
									AND a.CreditConsumed != 0 ";
									
							$result7a = $this->db->query($sql7a,array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
							
							foreach ($result7a->result_array() as $row){

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientId'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'MR';
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
									$subsIds[$row['ClientId']][] = $row['SubscriptionId'];
									
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
									$transIds[$row['ClientId']][] = $row['TransactionId'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Credit Consumed'] += $row['CreditConsumed'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['View Count'] += $row['ViewCount'];

							}
							
					} else if($categoryId == '2' && ($subCategoryId == '' || $subCategoryId == '56')) {
							
							$sql7SA = "SELECT GROUP_CONCAT( DISTINCT c.searchagentid ) as searchAgentIds
									FROM shiksha.SAMultiValuedSearchCriteria a, shiksha.categoryPageData b, shiksha.SASearchAgent c 
									WHERE c.searchagentid = a.searchAlertId
									AND b.course_id = a.value 
									AND b.category_id = 56
									AND a.keyname = 'clientcourse' 
									AND c.deliveryMethod = 'normal' 
									AND c.type = 'response'
									AND c.created_on >= ? AND c.created_on <= ? ";

							$result7SA = $this->db->query($sql7SA,array($startDate, $endDate));

							foreach ($result7SA->result_array() as $row){
									$searchAgentIds = $row['searchAgentIds'];
							}

							if($searchAgentIds != '') {

								$sql7 = "SELECT DISTINCT c.TransactionId, c.TotalTransactionPrice, e.ClientId, d.userid, e.CreditConsumed, e.SubscriptionId, b.SubscriptionStartDate, b.SubscriptionEndDate
										FROM SUMS.Subscription a, SUMS.Subscription_Product_Mapping b, SUMS.Transaction c, shiksha.SALeadAllocation d, shiksha.LDBLeadContactedTracking e 
										WHERE d.agentid in ( $searchAgentIds )
										AND e.UserId = d.userid
										AND e.activity_flag = 'SA'
										AND e.CreditConsumed > 0 
										AND a.SubscriptionId = b.SubscriptionId 
										AND a.SubscriptionId = e.SubscriptionId 
										AND a.TransactionId = c.TransactionId  
										AND b.SubscriptionStartDate >= ? AND b.SubscriptionStartDate <= ? 
										AND e.ContactDate >= ? AND e.ContactDate <= ? 
										AND d.allocationtime >= ? AND d.allocationtime <= ? ";
										
								$result7 = $this->db->query($sql7,array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
								
								foreach ($result7->result_array() as $row){

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientId'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'MR';
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
										$subsIds[$row['ClientId']][] = $row['SubscriptionId'];
										
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
										$transIds[$row['ClientId']][] = $row['TransactionId'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Credit Consumed'] += $row['CreditConsumed'];
										$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['View Count'] += 1;

								}

							}
								
							$sql7a = "SELECT DISTINCT a.ClientId, a.CreditConsumed, a.SubscriptionId, b.ViewCount, e.TransactionId, e.TotalTransactionPrice, d.SubscriptionStartDate, d.SubscriptionEndDate
									FROM shiksha.LDBLeadContactedTracking a, shiksha.LDBLeadViewCount b, SUMS.Subscription c, SUMS.Subscription_Product_Mapping d, SUMS.Transaction e 
									WHERE a.UserId = b.UserId
									AND b.DesiredCourse = 52 
									AND b.Flag = 'national'
									AND a.SubscriptionId = c.SubscriptionId 
									AND c.SubscriptionId = d.SubscriptionId 
									AND c.TransactionId = e.TransactionId  
									AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? 
									AND b.UpdateTime >= ? AND b.UpdateTime <= ?
									AND a.ContactDate >= ? AND a.ContactDate <= ? 
									AND a.activity_flag = 'LDB' 
									AND a.CreditConsumed != 0 ";
									
							$result7a = $this->db->query($sql7a,array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
							
							foreach ($result7a->result_array() as $row){

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientId'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'MR';
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
									$subsIds[$row['ClientId']][] = $row['SubscriptionId'];
									
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
									$transIds[$row['ClientId']][] = $row['TransactionId'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];

									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['Credit Consumed'] += $row['CreditConsumed'];
									$returnData[$row['ClientId']]['Product Details'][$row['TransactionId']]['View Count'] += $row['ViewCount'];

							}

					}

				}

				foreach ($returnData as $userId => $data) {
						
						foreach ($data['Listing Details'] as $index => $subsData) {
								
								foreach($subsData['MI Listings'] as $id => $listingsData) {
										
										foreach($courses as $instituteId => $coursesData){
												
												if($id == $instituteId){
														
														foreach($coursesData as $courseId => $values){
																
																$returnData[$userId]['Listing Details'][$listingsData['Subscription']]['MI Listings'][$values['Course ID']]['ID'] = $values['Course ID'];
																$returnData[$userId]['Listing Details'][$listingsData['Subscription']]['MI Listings'][$values['Course ID']]['Subscription'] = $listingsData['Subscription'];
																$returnData[$userId]['Listing Details'][$listingsData['Subscription']]['MI Listings'][$values['Course ID']]['Transaction'] = $listingsData['Transaction'];
																$returnData[$userId]['Listing Details'][$listingsData['Subscription']]['MI Listings'][$values['Course ID']]['Sub Category'] = $values['Sub Category'];
																
														}
												}
										}
								}
						}
				}
				
				foreach ($returnData as $userId => $data) {
						
						foreach ($data['Listing Details'] as $index => $subsData) {
								
								foreach($subsData['Gold Listings'] as $id => $listingsData){
										
										if(!in_array($listingsData['ID'],$listingIds[$userId])){
												$listingIds[$userId][] = $listingsData['ID'];
										}
										if(!in_array($listingsData['Subscription'],$listingSubsIds[$userId])){
												$listingSubsIds[$userId][] = $listingsData['Subscription'];
										}
										if(!in_array($listingsData['Subscription'],$subsIds[$userId])){
												$subsIds[$userId][] = $listingsData['Subscription'];
										}
										if(!in_array($listingsData['Transaction'],$transIds[$userId])){
												$transIds[$userId][] = $listingsData['Transaction'];
										}
										if(!in_array($listingsData['Sub Category'],$subCategories[$userId])){
												$subCategories[$userId][] = $listingsData['Sub Category'];
										}
										
								}
								
								foreach($subsData['MI Listings'] as $id => $listingsData) {
										
										if(!in_array($listingsData['ID'],$listingIds[$userId]) && !empty($listingsData['ID'])){
												$listingIds[$userId][] = $listingsData['ID'];
										}
										if(!in_array($listingsData['Subscription'],$listingSubsIds[$userId]) && !empty($listingIds[$userId])){
												$listingSubsIds[$userId][] = $listingsData['Subscription'];
										}
										if(!in_array($listingsData['Subscription'],$subsIds[$userId]) && !empty($listingIds[$userId])){
												$subsIds[$userId][] = $listingsData['Subscription'];
										}
										if(!in_array($listingsData['Transaction'],$transIds[$userId]) && !empty($listingIds[$userId])){
												$transIds[$userId][] = $listingsData['Transaction'];
										}
										if(!in_array($listingsData['Sub Category'],$subCategories[$userId]) && !empty($listingsData['Sub Category'])){
												$subCategories[$userId][] = $listingsData['Sub Category'];
										}
								}
						}
						
						if(empty($subsIds[$userId]) || empty($transIds[$userId])){
								continue;
						}
						
						$listingSubscriptionIds = htmlspecialchars(implode(',',$listingSubsIds[$userId]),ENT_QUOTES);

						$subscriptionIds = htmlspecialchars(implode(',',$subsIds[$userId]),ENT_QUOTES);

						$sql3 = "SELECT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.BaseProdCategory, b.BaseProdSubCategory, c.CurrencyId, c.TotalTransactionPrice, d.TotalBaseProdQuantity, d.SubscriptionStartDate, d.SubscriptionEndDate
								FROM SUMS.Subscription a, SUMS.Base_Products b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
								WHERE a.ClientUserId = ? AND a.SubscriptionId in ( $listingSubscriptionIds )
								AND a.BaseProductId = b.BaseProductId
								AND a.ClientUserId = c.ClientUserId 
								AND a.TransactionId = c.TransactionId 
								AND d.SubscriptionId = a.SubscriptionId
								AND d.BaseProductId = a.BaseProductId
								AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? ";
						
						$result3 = $this->db->query($sql3,array($userId, $startDate, $endDate));
						
						foreach ($result3->result_array() as $row){
								
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Client ID'] = $row['ClientUserId'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Subscription Type'][$row['SubscriptionId']] = 'Listing';
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Subscription ID'][$row['SubscriptionId']] = $row['SubscriptionId'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Transaction ID'] = $row['TransactionId'];
								
								if(empty($returnData[$userId]['Product Details'][$row['TransactionId']]['Total Transaction Price'])) {
										$returnData[$userId]['Product Details'][$row['TransactionId']]['Total Transaction Price'] = $row['TotalTransactionPrice'];
								}

								$returnData[$userId]['Product Details'][$row['TransactionId']]['Start Date'][$row['SubscriptionId']] = $row['SubscriptionStartDate'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['End Date'][$row['SubscriptionId']] = $row['SubscriptionEndDate'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Product Category'][$row['SubscriptionId']] = $row['BaseProdCategory'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Product Sub Category'][$row['SubscriptionId']] = $row['BaseProdSubCategory'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Course Sub Category'] = implode(',',$subCategories[$userId]);
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Course ID'] = implode(',',$listingIds[$userId]);
								
						}
						
						if(empty($returnData[$userId]['Product Details'])){
								continue;
						}
						
						$sql2 = "SELECT a.userid, b.city_name, a.firstname, a.lastname
								FROM shiksha.tuser a LEFT JOIN shiksha.countryCityTable b ON a.city = b.city_id
								WHERE a.userid = ? AND a.usergroup = 'enterprise' ";
						
						$result2 = $this->db->query($sql2, array($userId));
								
						foreach ($result2->result_array() as $row){
								$returnData[$row['userid']]['Client ID'] = $row['userid'];
								$returnData[$row['userid']]['Client Name'] = $row['firstname']. " " . $row['lastname'];
								$returnData[$row['userid']]['Client City'] = $row['city_name'];
						}
						
						$transactionIds = htmlspecialchars(implode(',',$transIds[$userId]),ENT_QUOTES);
						
						$sql5a = "SELECT DISTINCT a.ClientUserId, a.SubscriptionId, a.DerivedProductId, a.TransactionId, b.SuggestedPrice, b.BaseProdQuantity, c.CurrencyId, c.TotalTransactionPrice, d.TotalBaseProdQuantity
								FROM SUMS.Subscription a, SUMS.Derived_Products_Mapping b, SUMS.Transaction c, SUMS.Subscription_Product_Mapping d
								WHERE a.ClientUserId = ? AND a.SubscriptionId in ( $subscriptionIds )
								AND a.SubscriptionId = d.SubscriptionId
								AND b.CurrencyId = c.CurrencyId
								AND b.SuggestedPrice > 0 
								AND a.DerivedProductId = b.DerivedProductId 
								AND a.ClientUserId = c.ClientUserId 
								AND a.TransactionId = c.TransactionId 
								AND d.SubscriptionStartDate >= ? AND d.SubscriptionStartDate <= ? ";
						
						$result5a = $this->db->query($sql5a,array($userId, $startDate, $endDate));
						
						foreach ($result5a->result_array() as $row){

								$returnData[$userId]['Product Details'][$row['TransactionId']]['Total Base Product Quantity'][$row['SubscriptionId']] = $row['TotalBaseProdQuantity'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Price'][$row['SubscriptionId']] = $row['SuggestedPrice'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['Subscription Revenue'][$row['SubscriptionId']] = $row['TotalBaseProdQuantity'] * ($row['SuggestedPrice']/$row['BaseProdQuantity']);
								
						}

						foreach ($returnData[$userId]['Product Details'] as $transactionId => $details) {
							
							foreach ($details['Subscription Revenue'] as $key => $value) {
								
								if(($details['Subscription Type'][$key] == 'Listing') && ($details['Subscription ID'][$key] == $key)){
									
									$returnData[$userId]['Product Details'][$transactionId]['Listing Revenue'] += $value;
								
								} 

								if(($details['Subscription Type'][$key] == 'Sticky') && ($details['Subscription ID'][$key] == $key)){
									
									$returnData[$userId]['Product Details'][$transactionId]['Sticky Revenue'] += $value;
								
								} 

								if(($details['Subscription Type'][$key] == 'Mailer') && ($details['Subscription ID'][$key] == $key)){
									
									$returnData[$userId]['Product Details'][$transactionId]['Mailer Revenue'] += $value;
								
								} 

								if(($details['Subscription Type'][$key] == 'Banner') && ($details['Subscription ID'][$key] == $key)){
									
									$returnData[$userId]['Product Details'][$transactionId]['Banner Revenue'] += $value;
								
								}

								if(($details['Subscription Type'][$key] == 'Other Banners') && ($details['Subscription ID'][$key] == $key)){
									
									$returnData[$userId]['Product Details'][$transactionId]['Other Banners Revenue'] += $value;
								
								}
								
							}

						}

						$sql5 = "SELECT a.ClientUserId, a.TransactionId, e.userid, d.BranchName, e.firstname, e.lastname 
								FROM SUMS.Transaction a
								JOIN SUMS.Sums_User_Details b ON b.EmployeeId = a.SalesBy 
								LEFT JOIN SUMS.Sums_User_Branch_Mapping c ON c.userId = b.userId
								JOIN SUMS.Sums_Branch_List d ON d.BranchId = c.BranchId 
								JOIN shiksha.tuser e ON e.userid = b.userId 
								WHERE a.ClientUserId = ? AND a.TransactionId in ( $transactionIds ) ";		
						
						$result5 = $this->db->query($sql5,array($userId));
						
						foreach ($result5->result_array() as $row){

								if(empty($returnData[$userId]['Product Details'][$row['TransactionId']])){
										continue;
								}

								$returnData[$userId]['Product Details'][$row['TransactionId']]['SR Name'] = $row['firstname'] . " " . $row['lastname'];
								$returnData[$userId]['Product Details'][$row['TransactionId']]['SR Branch'] = $row['BranchName'];
						}
						
						$startDateLastYear = date('Y-m-d 00:00:00',strtotime($startDate." -1 Year"));
						$endDateLastYear = date('Y-m-d 23:59:59',strtotime($endDate." -1 Year"));
						$startDateLast30Days = date('Y-m-d 00:00:00',strtotime(date('Y-m-d')." -1 month"));
						$endDateLast30Days = date('Y-m-d 23:59:59');
						
						$listingsIds = htmlspecialchars(implode(',',$listingIds[$userId]),ENT_QUOTES);
						
						if($listingsIds != '') {
								
								$sql8 = "SELECT tlms.listing_type_id, COUNT( * ) as Count
										FROM tempLMSTable tlms 
										WHERE tlms.submit_date >= ? 
										AND tlms.submit_date <=  ? 
										AND tlms.listing_subscription_type = 'paid'
										AND tlms.listing_type = 'course'
										AND tlms.listing_type_id IN ( $listingsIds )
										AND tlms.action LIKE '%client%' ";
										
						} else {
								
								$sql8 =	"SELECT tlms.listing_type_id, COUNT( * ) as Count
										FROM tempLMSTable tlms, listings_main lm
										WHERE tlms.submit_date >= ? 
										AND tlms.submit_date <=  ? 
										AND tlms.listing_subscription_type = 'paid'
										AND tlms.listing_type = 'course'
										AND lm.listing_type_id = tlms.listing_type_id 
										AND lm.listing_type = 'course'
										AND lm.username = ".$this->db->escape($userId)." 
										AND tlms.action LIKE '%client%' ";
								
						}
						
						$result1 = $this->db->query($sql8,array($startDateLastYear, $endDateLastYear));
						
						foreach ($result1->result_array() as $row){
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['Last Year Start Date'] = $startDateLastYear;
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['Last Year End Date'] = $endDateLastYear;
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['Last Year Count'] = $row['Count'];
						}
						
						$result2 = $this->db->query($sql8,array($startDate, $endDate));
						
						foreach ($result2->result_array() as $row){
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['This Year Start Date'] = $startDate;
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['This Year End Date'] = $endDate;
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['This Year Count'] = $row['Count'];
						}
						
						$result3 = $this->db->query($sql8,array($startDateLast30Days, $endDateLast30Days));
						
						foreach ($result3->result_array() as $row){
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['Last 30 Days Start Date'] = $startDateLast30Days;
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['Last 30 Days End Date'] = $endDateLast30Days;
								$returnData[$userId]['Mailer Responses'][$row['listing_type_id']]['Last 30 Days Count'] = $row['Count'];
						}
						
						if($listingsIds != ''){
								
								$sql9 =	"SELECT tlms.listing_type_id, COUNT( * ) as Count
										FROM tempLMSTable tlms 
										WHERE tlms.submit_date >= ? 
										AND tlms.submit_date <=  ? 
										AND tlms.listing_subscription_type = 'paid'
										AND tlms.listing_type = 'course'
										AND tlms.listing_type_id IN ( $listingsIds )
										AND tlms.action NOT LIKE '%client%' ";
								
						} else {
								
								$sql9 =	"SELECT tlms.listing_type_id, COUNT( * ) as Count
										FROM tempLMSTable tlms, listings_main lm
										WHERE tlms.submit_date >= ? 
										AND tlms.submit_date <=  ? 
										AND tlms.listing_subscription_type = 'paid'
										AND tlms.listing_type = 'course'
										AND lm.listing_type_id = tlms.listing_type_id 
										AND lm.listing_type = 'course'
										AND lm.username = ".$this->db->escape($userId)." 
										AND tlms.action NOT LIKE '%client%' ";
										
						}
						
						$result1 = $this->db->query($sql9,array($startDateLastYear, $endDateLastYear));
						
						foreach ($result1->result_array() as $row){
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['Last Year Start Date'] = $startDateLastYear;
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['Last Year End Date'] = $endDateLastYear;
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['Last Year Count'] = $row['Count'];
						}
						
						$result2 = $this->db->query($sql9,array($startDate, $endDate));
						
						foreach ($result2->result_array() as $row){
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['This Year Start Date'] = $startDate;
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['This Year End Date'] = $endDate;
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['This Year Count'] = $row['Count'];
						}
						
						$result3 = $this->db->query($sql9,array($startDateLast30Days, $endDateLast30Days));
						
						foreach ($result3->result_array() as $row){
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['Last 30 Days Start Date'] = $startDateLast30Days;
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['Last 30 Days End Date'] = $endDateLast30Days;
								$returnData[$userId]['Non-Mailer Responses'][$row['listing_type_id']]['Last 30 Days Count'] = $row['Count'];
						}

						if($listingsIds != ''){
								
								$sql10 = "SELECT DISTINCT lm.listing_type_id, lm.listing_title, ilc.city_name, lm.username
											FROM `listings_main` lm, institute_location_table ilc, course_details cd
											WHERE cd.course_id IN ( $listingsIds )
											AND cd.institute_id = lm.listing_type_id
											AND lm.listing_type = 'institute'
											AND cd.institute_id = ilc.institute_id
											AND lm.status = 'live'
											AND ilc.status = 'live'
											AND ilc.institute_id = lm.listing_type_id
											AND lm.listing_type = 'institute'
											AND lm.username = ? ";
								
						} else {
								
								$sql10 = "SELECT DISTINCT lm.listing_type_id, lm.listing_title, ilc.city_name, lm.username
											FROM `listings_main` lm, institute_location_table ilc, course_details cd
											WHERE cd.institute_id = lm.listing_type_id
											AND lm.listing_type = 'institute'
											AND cd.institute_id = ilc.institute_id
											AND lm.status = 'live'
											AND ilc.status = 'live'
											AND ilc.institute_id = lm.listing_type_id
											AND lm.listing_type = 'institute'
											AND lm.username = ? ";
										
						}

						$result10 = $this->db->query($sql10,array($userId));
						
						foreach ($result10->result_array() as $row){
								$returnData[$userId]['Institute ID'] = $row['listing_type_id'];
								$returnData[$userId]['Institute Name'] = $row['listing_title'];
								$returnData[$userId]['Institute City'] = $row['city_name'];
						}
				
				}
				
				return $returnData;
		}
		function getInstituteCityById($institute_id)
		{
			if (empty($institute_id)){
				return;
			}
			$this->initiateModel("read","User");
		
			$sql = "select city_name,listing_id from shiksha_institutes_locations a join 
			countryCityTable b on a.city_id = b.city_id where listing_id in (?) and status='live'";		
			
			$results = $this->db->query($sql, array($institute_id))->result_array();
		
			return $results;
			
		}	

		function getPaidInstitutes($courseArray)
		{
			$this->initiateModel("read","User");
			

			$sql = "select  primary_id from shiksha_courses as course join listings_main as listing on course.course_id = listing.listing_type_id and listing.listing_type='course' where course.course_id in (?) and listing.pack_type in (?) and course.status = 'live' and listing.status='live'";		
			
			$paid_constants = array(SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_SL_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID);

			$results = $this->db->query($sql, array($courseArray, $paid_constants))->result_array();
			

			
			return $results;
			
		}
}
