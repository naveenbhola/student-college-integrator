<?php

class MatchedResponseAgentModel extends MY_Model
{
	function __construct()
	{
		parent::__construct('SearchAgents');
	}

	function getDbHandle($operation = 'read')
	{
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}
    
    public function getUnprocessedResponses($waitPeriod)
    {
        $dbHandle = $this->getDBHandle();
        global $managementStreamMR;
        global $mbaBaseCourse;
        global $engineeringtStreamMR;
        global $btechBaseCourse;
        global $fullTimeEdType;

		$waitPeriod = intval($waitPeriod);
		$delay = date('Y-m-d H:i:00',strtotime("-$waitPeriod min"));
		$timeLimit = date('Y-m-d H:i:00',strtotime("-6 hours"));
		
		$sql =  "SELECT distinct a.id as responseId,a.userId, a.listing_type_id as courseId, c.listing_title as courseName,shkCrseInfo.stream_id as streamId ".
		        " FROM tempLMSTable a ".
				"LEFT JOIN listings_main c on (c.listing_type_id = a.listing_type_id AND c.status = 'live' AND c.listing_type = 'course') ".
				"INNER JOIN shiksha_courses shkCrse ON shkCrse.course_id = a.listing_type_id ".
				"INNER JOIN shiksha_courses_type_information shkCrseInfo ON shkCrseInfo.course_id = a.listing_type_id ".

				"LEFT JOIN LDBExclusionList exl on a.userid = exl.userid and exl.status='live'".
				"INNER JOIN tUserPref pref on pref.userid= a.userId ".
				"INNER JOIN base_attribute_list baseList on baseList.value_id = shkCrseInfo.course_level ".
		        "WHERE a.listing_subscription_type = 'free' ".
				"AND a.listing_type = 'course' ".
				"AND a.submit_date <= ? ".
				"AND a.submit_date > ? ".
				"AND a.is_processed = 'no' ".
				" AND ( (shkCrseInfo.stream_id = $managementStreamMR AND shkCrseInfo.base_course = $mbaBaseCourse)OR (shkCrseInfo.stream_id = $engineeringtStreamMR AND shkCrseInfo.base_course = $btechBaseCourse) ) ".
				" AND shkCrseInfo.substream_id = 0 ".				
				" AND shkCrseInfo.type = 'entry' ".
				" AND shkCrseInfo.status = 'live' ".
				" AND shkCrse.status = 'live' ".
				" AND shkCrse.education_type = $fullTimeEdType ".
				" AND exl.id is null ".
				" AND baseList.attribute_name='Course Level' ".
        		" AND pref.Status ='live' ".
        		" AND baseList.value_name = pref.educationLevel ".        		
        		" AND a.action not in ('Institute_Viewed','MOB_Institute_Viewed','Viewed_Listing','mobile_viewedListing','Viewed_Listing_Pre_Reg','Mob_Viewed_Listing_Pre_Reg')";

        $query = $dbHandle->query($sql, array($delay, $timeLimit));

        $results = $query->result_array();
		
        return $results;
    }

    public function getNonMRUnprocessedResponses($waitPeriod)
    {
		global $managementStreamMR;
        global $mbaBaseCourse;
        global $engineeringtStreamMR;
        global $btechBaseCourse;
        global $fullTimeEdType;
        
        $dbHandle = $this->getDBHandle();
        
		$waitPeriod = intval($waitPeriod);
		$delay = date('Y-m-d H:i:00',strtotime("-$waitPeriod min"));
		$timeLimit = date('Y-m-d H:i:00',strtotime("-24 hours"));
		
		
		$sql =  "SELECT distinct a.id as responseId ".
		        "FROM tempLMSTable a ".
				"LEFT JOIN listings_main c on (c.listing_type_id = a.listing_type_id AND c.status = 'live' AND c.listing_type = 'course') ".

				"INNER JOIN shiksha_courses shkCrse ON shkCrse.course_id = a.listing_type_id ".
				"INNER JOIN shiksha_courses_type_information shkCrseInfo ON shkCrseInfo.course_id = a.listing_type_id ".

				"LEFT JOIN LDBExclusionList exl on a.userid = exl.userid ".
		        "WHERE a.listing_subscription_type = 'free' ".
				"AND a.listing_type = 'course' ".
				"AND a.submit_date <= '".$delay."' ".
				"AND a.submit_date > '".$timeLimit."' ".
				"AND a.is_processed = 'no' ".

				" AND ( (shkCrseInfo.stream_id != $managementStreamMR AND shkCrseInfo.base_course = $mbaBaseCourse)OR (shkCrseInfo.stream_id = $managementStreamMR AND shkCrseInfo.base_course != $mbaBaseCourse) OR  (shkCrseInfo.stream_id != $engineeringtStreamMR AND shkCrseInfo.base_course = $btechBaseCourse) OR  (shkCrseInfo.stream_id = $engineeringtStreamMR AND shkCrseInfo.base_course != $btechBaseCourse) ) ".

				" AND shkCrseInfo.substream_id = 0 ".				
				" AND shkCrseInfo.type = 'entry' ".
				" AND shkCrseInfo.status = 'live' ".
				" AND shkCrse.status = 'live' ".
				" AND shkCrse.education_type = $fullTimeEdType ".
				"AND exl.id is null ";

				
        $query = $dbHandle->query($sql);
        $results = $query->result_array();
		
        return $results;
    }
	
	public function setResponseProcessed($responseId)
	{
		$dbHandle = $this->getDBHandle('write');

		$sql = "UPDATE tempLMSTable SET is_processed = 'yes',submit_date = submit_date WHERE id = ?";
		$dbHandle->query($sql,array($responseId));
	}
	
	
	/**
	 * Fetch all genies matching specified response(client) courses
	 */ 
	public function getGeniesMatchingResponseCourse($courseId)
	{
		$dbHandle = $this->getDBHandle();

		if(ALSO_VIEWED_ALGO == 'COLLABORATIVE_FILTERING') {
			$sql =  "SELECT distinct b.searchAlertId, a.course_id ".
                    "FROM collaborativeFilteredCourses a ".
                    "INNER JOIN SAMultiValuedSearchCriteria b ON (b.value = a.course_id AND b.keyname = 'clientcourse') ".
                    "INNER JOIN listings_main lm on lm.listing_type_id = a.course_id ".
                    "WHERE lm.listing_type='course' and lm.status='live' ".
                    "AND a.recommended_course_id = ? AND a.weight>100000 AND b.is_active='live'";
		}
        else {              			
			$sql = "select searchAlertId, genieCourse as course_id from genieAlsoViewedCourses 
					where alsoViewedCourse =? and status ='live'";
		}

        $query = $dbHandle->query($sql, array($courseId));
        $results = $query->result_array();
        
        return $results;
	}
	
	function getFirstAlsoViewedCourses($courseId){
		$dbHandle = $this->getDBHandle();
		$sql = "select distinct recommended_course_id from alsoViewedFilteredCourses where course_id =?
					 and recommended_course_type = 'free' and status='live'";
		$query = $dbHandle->query($sql,array($courseId));
		$results = $query->result_array();	
		return $results;
	}

	function getSecondAlsoViewedCourses($inClause){
		$dbHandle = $this->getDBHandle();

		$sql = "select distinct recommended_course_id from alsoViewedFilteredCourses where course_id IN(".$inClause.")
					and recommended_course_type = 'free' and status='live'";
		
		$query = $dbHandle->query($sql);
		$results = $query->result_array();	

		return $results;
	}

	/**
	 * Fetch all genies matching competitive exams of specified user
	 * Including genies who did not specify exams in criteria
	 */ 
	public function getGeniesMatchingCompetitiveExams($responseUserId)
	{
		$dbHandle = $this->getDBHandle();
		
		$sql = "SELECT a.searchagentid
				FROM SASearchAgent a, SAExamCriteria b, tUserEducation c
				WHERE a.searchagentid = b.searchalertid
				AND b.examName = c.Name
				AND c.Level = 'Competitive exam'
				AND c.marks >= b.minScore
				AND c.marks <= b.maxScore
				AND (c.CourseCompletionDate = b.passingYear OR b.passingYear = '0000-00-00 00:00:00')
				AND a.is_active =  'live'
				AND c.userid =  ?
				AND a.type = 'response'
				
				UNION
				
				SELECT a.searchagentid
				FROM SASearchAgent a
				LEFT JOIN SAExamCriteria b ON a.searchagentid = b.searchalertid
				WHERE a.is_active =  'live'
				AND a.type = 'response'
				AND b.searchalertid IS NULL";
				
		$query = $dbHandle->query($sql,array($responseUserId,$responseUserId));
        $results = $query->result_array();
		
		$matchedGenies = array();
		foreach($results as $result) {
			$matchedGenies[] = $result['searchagentid'];
		}
		
		return $matchedGenies;
	}
	
	/**
	 * Fetch all genies matching specified current location
	 * Including genies who did not specify current location in criteria
	 */ 
	public function getGeniesMatchingCurrentLocation($currentLocation)
	{
		$dbHandle = $this->getDBHandle();
		
		$sql = "SELECT a.searchagentid
				FROM SASearchAgent a
				LEFT JOIN SAMultiValuedSearchCriteria b ON (a.searchagentid = b.searchAlertId AND b.keyname = 'currentlocation')
				WHERE a.is_active =  'live'
				AND a.type = 'response'
				AND b.id IS NULL";
				
		if($currentLocation) {
			$sql .= " UNION
					SELECT a.searchagentid
					FROM SASearchAgent a, SAMultiValuedSearchCriteria b
					WHERE a.searchagentid = b.searchAlertId
					AND b.keyname = 'currentlocation'
					AND a.type = 'response'
					AND b.value = ".$dbHandle->escape($currentLocation)."
					AND a.is_active =  'live'";
		}
				
		$query = $dbHandle->query($sql);
        $results = $query->result_array();
		
		$matchedGenies = array();
		foreach($results as $result) {
			$matchedGenies[] = $result['searchagentid'];
		}
		
		return $matchedGenies;
	}
	
	/**
	 * Fetch all genies matching specified preferred MR locations
	 * Including genies who did not specify preferred MR location in criteria
	 */ 
	public function getGeniesMatchingPreferredMRLocation($responseLocations)
	{
		return; //code not used now
		$dbHandle = $this->getDBHandle();
		
		$sql = "SELECT a.searchagentid
				FROM SASearchAgent a
				LEFT JOIN SAMultiValuedSearchCriteria b ON (a.searchagentid = b.searchAlertId AND b.keyname = 'mrlocation')
				WHERE a.is_active =  'live'
				AND a.type = 'response'
				AND b.id IS NULL";
				
		if(is_array($responseLocations) && count($responseLocations) > 0) {
			$sql .= " UNION
					SELECT a.searchagentid
					FROM SASearchAgent a, SAMultiValuedSearchCriteria b
					WHERE a.searchagentid = b.searchAlertId
					AND b.keyname = 'mrlocation'
					AND a.type = 'response'
					AND b.value IN (".implode(',', $responseLocations).")
					AND a.is_active =  'live'";
		}
				
		$query = $dbHandle->query($sql);
        $results = $query->result_array();
		
		$matchedGenies = array();
		foreach($results as $result) {
			$matchedGenies[] = $result['searchagentid'];
		}
		
		return $matchedGenies;
	}
	
	/**
	 * Fetch all genies matching specified graduation year
	 * Including genies who did not specify graduation year in criteria
	 */ 
	public function getGeniesMatchingGraduationYear($graduationYear)
	{
		return; //code not used now
		$dbHandle = $this->getDBHandle();

		if(!$graduationYear) {
			$graduationYear = NULL;
		}

		$sql 	= "SELECT a.searchagentid
					FROM SASearchAgent a, SASearchAgentBooleanCriteria b
					WHERE a.searchagentid = b.searchagentid
					AND (b.graduationCompletedFrom <= ".$dbHandle->escape($graduationYear)." OR   b.graduationCompletedFrom is NULL)
					AND (b.graduationCompletedTo >= ".$dbHandle->escape($graduationYear)."  OR  b.graduationCompletedTo is NULL)
					AND a.is_active =  'live'
					AND a.type = 'response'";
				
		$query = $dbHandle->query($sql);
        $results = $query->result_array();
		
		$matchedGenies = array();
		foreach($results as $result) {
			$matchedGenies[] = $result['searchagentid'];
		}
	
		return $matchedGenies;
	}
	
	/**
	 * Fetch all genies matching specified XII year
	 * Including genies who did not specify XII year in criteria
	 */ 
	public function getGeniesMatchingXIIYear($xiiYear)
	{
		return; //method not used now
		$dbHandle = $this->getDBHandle();
		
		$sql = "SELECT a.searchagentid
				FROM SASearchAgent a
				LEFT JOIN SARangedSearchCriteria b ON (a.searchagentid = b.searchAlertId AND b.keyname = 'XIICompleted')
				WHERE a.is_active =  'live'
				AND b.id IS NULL";
				
		if($xiiYear) {
			$sql .= " UNION
					SELECT a.searchagentid
					FROM SASearchAgent a, SARangedSearchCriteria b
					WHERE a.searchagentid = b.searchAlertId
					AND b.keyname = 'XIICompleted'
					AND b.rangeStart <= ".$dbHandle->escape($xiiYear)."
					AND b.rangeEnd >= ".$dbHandle->escape($xiiYear)."
					AND a.is_active =  'live'";
		}
				
		$query = $dbHandle->query($sql);
        $results = $query->result_array();
		
		$matchedGenies = array();
		foreach($results as $result) {
			$matchedGenies[] = $result['searchagentid'];
		}
	
		return $matchedGenies;
	}
	
	public function getGenieClients($genies)
	{
		$dbHandle = $this->getDBHandle();
		
		$sql =  "SELECT searchagentid,clientid ".
				"FROM SASearchAgent ".
				"WHERE searchagentid IN (".implode(',',$genies).")";
				
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		
		$genieClients = array();
		foreach($results as $result) {
			$genieClients[$result['searchagentid']] = $result['clientid'];
		}
		
		return $genieClients;
	}
	
	public function logMatchedGenies($userId,$matchedGenies,$genieData,$matchedFor,$responseProfile,$responseTime)
	{
		$dbHandle = $this->getDBHandle('write');
		
		$sa_model = $this->load->model('search_agent_main_model');
		$new_match_genies = array();
				
		$matchedGenies = $sa_model->getPortingGenies($matchedGenies);		
		
		if(count($matchedGenies) == 0) {
				return;
		}

		$sql = "INSERT IGNORE INTO SALeadMatchingLog(leadid,searchAgentid,clientid,stream,matchingTime) 
				VALUES";
		
		foreach ($matchedGenies as $genieId) {
			 $sql .= "('".$userId."','".$genieId."','".$genieData[$genieId]['client']."',".$responseProfile['StreamId'].",'".date('Y-m-d H:i:s')."'),";		
		}
		
		$sql = substr($sql, 0,-1);

		$dbHandle->query($sql);
		$matchId  = array();
		$matchedForCourseIds = array();
		foreach($matchedGenies as $genieId) {	
			//$dbHandle = $this->getDBHandle();
			
			$sql =  "SELECT id FROM SALeadMatchingLog ".
				" WHERE leadid = ? AND searchagentid = ?".
				" AND clientid = ?";
				//" ORDER BY id ASC";

			$query = $dbHandle->query($sql, array($userId, $genieId, $genieData[$genieId]['client']));
			$results = $query->result_array();

			foreach($results as $result) {
				$matchId[] = $result['id'];
				$matchedForCourseIds[$result['id']] = $matchedFor[$genieId];
			}
		}

		if((!empty($matchId)) && (!empty($matchedForCourseIds))) {
			
			$uniquematchedForCourseIds = array();
			$listing_ids = '';
			$uniquematchedForCourseIds = array_unique($matchedForCourseIds);
			$listing_ids = implode(",",$uniquematchedForCourseIds);

			$sql =  "SELECT listing_type_id,listing_title from listings_main where listing_type_id in ($listing_ids) and listing_type=? and status = ?";
			$query = $dbHandle->query($sql, array('course','live'));

			$courseNames = array();
			foreach($query->result_array() as $result) {
				$courseNames[$result['listing_type_id']] = $result['listing_title'];
			}
         
	        $sql = "INSERT IGNORE INTO userMatchedResponseCoursesTable(matchingLogId,userId,matchedCourseId,matchedCourse,matchingTime) 
					VALUES";
			
			foreach ($matchId as $matchid) {
				$matchedForCourseId = '';$matchedForCourseName = '';
				$matchedForCourseId = $matchedForCourseIds[$matchid];
				$matchedForCourseName = $courseNames[$matchedForCourseId];

				$matchedForCourseName = addslashes($matchedForCourseName);	//to fix db error, course name consists of single quote

				$sql .= "('".$matchid."','".$userId."','".$matchedForCourseId."','".$matchedForCourseName."','".$responseTime."'),";		
			}
		}

		$sql = substr($sql, 0,-1);
		$dbHandle->query($sql);
	
	}
	
	public function getNormalDeliveryGenies($genies)
	{
		$dbHandle = $this->getDBHandle();
		
		if(!is_array($genies) || count($genies) == 0) {
		return array();
        }
		
		$sql =  "SELECT searchagentid ".
				"FROM SASearchAgent ".
				"WHERE searchagentid in (".implode(',',$genies).") ".
				"AND deliveryMethod = 'normal' AND is_active = 'live'";
		
		$query = $dbHandle->query($sql);
		return $this->getColumnArray($query->result_array(),'searchagentid');
	}
	
	public function getEnabledGenies($genies)
	{return;
		$dbHandle = $this->getDBHandle();
		
		if(!is_array($genies) || count($genies) == 0) {
            return array();
        }
		
		$sql =  "SELECT searchagentid ".
				"FROM SASearchAgent ".
				"WHERE searchagentid in (".implode(',',$genies).") ".
				"AND (flag_auto_download = 'live' OR flag_auto_responder_sms = 'live' OR flag_auto_responder_email = 'live') ".
				"AND is_active = 'live'";
		
		$query = $dbHandle->query($sql);
		return $this->getColumnArray($query->result_array(),'searchagentid');
	}
	
	/**
	 * Fetch LDB credits available with each of the specified clients
	 * If there are multiple LDB subscriptions for a client,
	 * look only at the one with maximum credits
	 */ 
	public function getLDBCredits($clients)
	{
		$dbHandle = $this->getReadHandleByModule('SUMS');
		
		$sql = "SELECT S.ClientUserId,SPM.BaseProdRemainingQuantity
				FROM Subscription_Product_Mapping SPM
				INNER JOIN Subscription S ON S.SubscriptionId = SPM.SubscriptionID
				INNER JOIN Base_Products B ON SPM.BaseProductId=B.BaseProductId
				WHERE S.ClientUserId IN (".implode(',',$clients).")
				AND S.SubscrStatus='ACTIVE'
				AND SPM.BaseProdRemainingQuantity > 0
				AND DATE(SPM.SubscriptionEndDate) >= curdate()
				AND DATE(SPM.SubscriptionStartDate) <= curdate()
				AND SPM.Status='ACTIVE'
				AND B.BaseProdCategory = 'Lead-Search'";
				
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		
		$LDBCredits = array();
		foreach($results as $result) {
			if(intval($result['BaseProdRemainingQuantity']) > intval($LDBCredits[$result['ClientUserId']])) {
				$LDBCredits[$result['ClientUserId']] = $result['BaseProdRemainingQuantity'];
			}
		}
		
		return $LDBCredits;
	}

	/**
	 * Fetch credits required to view a lead/response
	 * for each for the specified genies
	 */ 
	public function getRequiredCredits($genies)
	{
		$dbHandle = $this->getDBHandle();
		
		$sql =  "SELECT a.searchagentid,a.price_per_lead,b.actionType,b.deductcredit ".
				"FROM SASearchAgent a ".
				"LEFT JOIN tGroupCreditDeductionPolicy b ON (b.groupId = a.credit_group AND b.status = 'live') ".
				"WHERE a.searchagentid in (".implode(',',$genies).") ";
		
		$query = $dbHandle->query($sql);
		
		$credits = array();
		foreach($query->result_array() as $result) {
			$credits[$result['searchagentid']]['pricePerLead'] = $result['price_per_lead'];
			if($result['actionType'] == 'view' || $result['actionType'] == 'email' || $result['actionType'] == 'sms') {
				$credits[$result['searchagentid']][$result['actionType']] = $result['deductcredit'];
			}
		}
		
		return $credits;
	}
	
	/**
	 * Fetch all the clients to whom given user has
	 * already been allocated
	 */ 
	public function getAllocatedClients($userId)
	{
		$dbHandle = $this->getDBHandle('write');
		
		$sql =  "SELECT b.clientid ".
				"FROM SALeadAllocation a ".
				"INNER JOIN SASearchAgent b ON b.searchagentid = a.agentid ".
				"WHERE a.userid = ? AND b.is_active = 'live'";
		
		$query = $dbHandle->query($sql,array($userId));
		
		return $this->getColumnArray($query->result_array(),'clientid');
	}
	
	public function getGenieDeliveryPreferences($genies)
	{
		$dbHandle = $this->getDBHandle();
		
		$sql =  "SELECT searchagentid,flag_auto_download,flag_auto_responder_sms,flag_auto_responder_email ".
				"FROM SASearchAgent ".
				"WHERE searchagentid in (".implode(',',$genies).") ";
		
		$query = $dbHandle->query($sql);
		
		$prefs = array();
		foreach($query->result_array() as $result) {
			$prefs[$result['searchagentid']] = array(
				'autoDownload' => $result['flag_auto_download'] == 'live' ? 'YES' : 'NO',
				'autoResponderSMS' => $result['flag_auto_responder_sms'] == 'live' ? 'YES' : 'NO',
				'autoResponderEmail' => $result['flag_auto_responder_email'] == 'live' ? 'YES' : 'NO'
			);
		}
		
		return $prefs;
	}
	
	public function getDailyQuotaForGenies($genies)
	{
		$dbHandle = $this->getDBHandle();
		
		$sql =  "SELECT a.searchagentid,a.leads_daily_limit as viewLimit,b.daily_limit as emailLimit,c.daily_limit as smsLimit ".
				"FROM SASearchAgent a ".
				"LEFT JOIN SASearchAgentAutoResponder_email b ON (b.searchagentid = a.searchagentid AND b.is_active = 'live') ".
				"LEFT JOIN SASearchAgentAutoResponder_sms c ON (c.searchagentid = a.searchagentid AND c.is_active = 'live') ".
				"WHERE a.searchagentid in (".implode(',',$genies).") ";
		
		$query = $dbHandle->query($sql);
		
		$quota = array();
		foreach($query->result_array() as $result) {
			$quota[$result['searchagentid']] = array(
				'viewLimit' => intval($result['viewLimit']),
				'emailLimit' => intval($result['emailLimit']) + intval($result['viewLimit']),
				'smsLimit' => intval($result['smsLimit']) + intval($result['viewLimit'])
			);
		}
		
		return $quota;
	}
	
	public function getQuotaFilledForGenies($genies)
	{
		$dbHandle = $this->getDBHandle();
		
		$sql =  "SELECT searchagentid,leads_sent_today,leads_sent_today_email,leads_sent_today_sms ".
				"FROM SALeadsLeftoverStatus  ".
				"WHERE searchagentid in (".implode(',',$genies).") ";
		
		$query = $dbHandle->query($sql);
		
		$quotaFilled = array();
		foreach($query->result_array() as $result) {
			$quotaFilled[$result['searchagentid']] = array(
				'view' => intval($result['leads_sent_today']),
				'email' => intval($result['leads_sent_today_email']),
				'sms' => intval($result['leads_sent_today_sms'])
			);
		}
		
		return $quotaFilled;
	}
	
	public function allocateToGenies($userId,$genies,$genieData,$responseTime,$matchedFor)
	{
		$dbHandle = $this->getDBHandle('write');
		
		foreach($genies as $genie) {
			$data = array(
				'userid' => $userId,
				'agentid' => $genie,
				'allocationtime' => date('Y-m-d H:i:s'),
				'sms_sent' => 'NO',
				'email_sent' => 'NO',
				'auto_download' => $genieData[$genie]['autoDownload'] ? 'YES' : 'NO',
				'auto_responder_email' => $genieData[$genie]['autoResponderEmail'] ? 'YES' : 'NO',
				'auto_responder_sms' => $genieData[$genie]['autoResponderSMS'] ? 'YES' : 'NO',
				'auto_responder_email_sent' => 'NO',
				'auto_responder_sms_sent' => 'NO',
				'matchedFor' => $matchedFor[$genie],
				'responseTime' => $responseTime
			);
			
			$dbHandle->insert('SALeadAllocation',$data);
		}
	}
	
	public function updateGeniesLeftOverStatus($genies,$genieData)
	{
		$dbHandle = $this->getDBHandle('write');
		
		foreach($genies as $genie) {
			if($genieData[$genie]['autoDownload']) {
				$sql =  "UPDATE SALeadsLeftoverStatus ".
						"SET leads_sent_today = leads_sent_today + 1, last_sent_time = now() ".
						"WHERE searchagentid = ?";
				
				$dbHandle->query($sql,array($genie));
					
				$sql =  "UPDATE SALeadsLeftoverStatus a, SASearchAgent b ".
						"SET leftover_leads = leftover_leads - 1, last_sent_time = now() ".
						"WHERE a.searchagentid = ? ".
						"AND a.searchagentid = b.searchagentid ".
						"AND b.is_active = 'live' ".
						"AND (b.leads_daily_limit - a.leads_sent_today) < 0";
				
				$dbHandle->query($sql,array($genie));
			}
			
			if($genieData[$genie]['autoResponderSMS']) {
				$sql =  "UPDATE SALeadsLeftoverStatus ".
						"SET leads_sent_today_sms = leads_sent_today_sms + 1, last_sent_time = now() ".
						"WHERE searchagentid = ?";
				
				$dbHandle->query($sql,array($genie));
			}
			
			if($genieData[$genie]['autoResponderEmail']) {
				$sql =  "UPDATE SALeadsLeftoverStatus ".
						"SET leads_sent_today_email = leads_sent_today_email + 1, last_sent_time = now() ".
						"WHERE searchagentid = ?";
						
				$dbHandle->query($sql,array($genie));
			}
		}
	}

	function checkReponseForClient($userId, $clientId){
		$dbHandle = $this->getDBHandle('read');

		/*$queryToCheckResponse = "SELECT id from tempLMSTable where userId =? and listing_type_id IN 
								(select listing_type_id from listings_main where username = ? and status = 'live' and pack_type IN(1,2,375))";*/

		$queryToCheckResponse = "select id from tempLMSTable t JOIN listings_main l on t.listing_type_id = l.listing_type_id
								where t.userId= ? and l.username = ? and l.status = 'live' and l.pack_type IN(1,2,375) 
								and l.listing_type = 'course'";
				
		$result = $dbHandle->query($queryToCheckResponse,array((int)$userId,(int)$clientId))->num_rows();

		return $result;

	}

	function checkIfAlreadyAllocatedToClient($userId, $clientId){
		if(empty($userId) || empty($clientId) ){
			return false;
		}

		$dbHandle = $this->getDBHandle('read');

		$queryToCheckResponse = "select id from LDBLeadContactedTracking where ClientId=? and UserId=? and ContactType='view' ";
				
		$result = $dbHandle->query($queryToCheckResponse,array($clientId,$userId))->num_rows();

		return $result;
	}

	function getResponseTime($responseUserId,$responseCourseId){
		$dbHandle = $this->getDBHandle('read');

		$sql = "select submit_date from tempLMSTable where userId=? and listing_type_id=? order by id desc limit 1";
		
		$result = $dbHandle->query($sql,array($responseUserId,$responseCourseId))->row_array();	

		return $result;
	}

	function getUnmatchedGenie($cronFrequency){
		$dbHandle = $this->getDBHandle('read');

		$sql = "select S1.searchAlertId,S1.value as genieCourse from SAMultiValuedSearchCriteria S1
				INNER JOIN SASearchAgent S2 ON S2.searchagentid = S1.searchAlertId
 				where S1.keyname = 'clientcourse'
 				AND S2.is_active ='live'";

 		if($cronFrequency == 'hourly'){	
 			$timeLimit = date('Y-m-d H:i:00',strtotime("-30 min"));	//to pick genies created in last 30min

 			$clause = " and S2.updated_on > ".$dbHandle->escape($timeLimit);
 			$sql.=$clause;
 		}
		$result = $dbHandle->query($sql)->result_array();	

		return $result;
	}

	function insertBatchData($batchData){
		$dbHandle = $this->getDBHandle('write');


		$sql = "INSERT IGNORE INTO genieAlsoViewedCourses(searchAlertId,genieCourse,alsoViewedCourse) 
				VALUES";
		
		foreach ($batchData as $row) {
			 $sql .= "(".$dbHandle->escape($row['searchAlertId']).",".$dbHandle->escape($row['genieCourse']).",".$dbHandle->escape($row['alsoViewedCourse'])."),";		
		}
		
		$sql = substr($sql, 0,-1);
        $dbHandle->query($sql);	
	}

	function markOldGenieHistory(){
		$dbHandle = $this->getDBHandle('write');

		$sql = "update genieAlsoViewedCourses set status='history'";
		$dbHandle->query($sql);
	}

	function deleteHistoryGenie(){
		$dbHandle = $this->getDBHandle('write');

		$sql = "delete from genieAlsoViewedCourses where status='history'";
		$dbHandle->query($sql);
	}

	function getMatchedResponsesDetails($userids, $startDate, $endDate){
		
		$dbHandle = $this->getDBHandle('write');
		
		$sql = "select userid as leadid, matchedFor, responseTime as submitDate from SALeadAllocation where
				userid IN( ".$userids." ) and responseTime >= ".$dbHandle->escape($startDate)." and responseTime <= ".$dbHandle->escape($endDate);
		
		$result = $dbHandle->query($sql)->result_array();	
		error_log("###sql".print_r($sql,true));
		return $result;
	}

	public function getResponseLocationsForMRMatch($userIds)
	{
		global $requiredAffinityForCityTier;
		$dbHandle = $this->getDbHandle();
	
		$sql =  "SELECT a.userId, a.cityId, a.affinity, b.tier ".
				"FROM userResponseLocationAffinity a ".
				"INNER JOIN countryCityTable b ON b.city_id = a.cityId ".
				"WHERE a.userId in (".implode(',',$userIds).") ".
				"AND b.countryId = 2";
				
		$query = $dbHandle->query($sql);
			
		$responseLocationsWithAffinity = array();
		foreach ($query->result_array() as $row) {
			if($row['affinity'] >= $requiredAffinityForCityTier[$row['tier']]) {
				$responseLocationsWithAffinity[$row["userId"]][] = $row['cityId'];
			}
		}
		
		return $responseLocationsWithAffinity;
	}
}
