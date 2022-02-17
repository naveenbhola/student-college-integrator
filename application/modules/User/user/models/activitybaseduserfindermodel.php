<?php

/**
 * Activity based user finder model class
 */
class ActivityBasedUserFinderModel extends MY_Model
{
	/**
	 * @var Object DB Handle
	 */
	private $dbHandle;

	/**
	 * Constructor
	 */
	function __construct()
	{
                ini_set('memory_limit','1024M');
		parent::__construct('User');
	}

	/**
	 * Initiate the model
	 *
	 * @param string $operation
	 */
	private function initiateModel($mode = "read", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	/**
	 * Function to get short registered users
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users users who are short registered
	 */
	public function getShortRegisteredUsers($params = array(), $extraParams = array())
	{
		$this->initiateModel();
		
		$clauses = array("tuserflag.userId = tUserPref.UserId", "tuserflag.isLDBUser = 'NO'");
		
		if($params['source'] == 'abroad') {
			$clauses[] = "tUserPref.TimeOfStart IS NOT NULL";
		}
		else {
			$clauses[] = "tUserPref.TimeOfStart IS NULL";
		}

		$boundrySetArray = array();
		
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$boundrySetArray[] = $extraParams['boundrySet'];
			$clauses[] = "tuserflag.userId IN (?)";
		}
		
		$sql =  "SELECT tuserflag.userId ".
			"FROM tuserflag, tUserPref ".
			"WHERE ".implode(' AND ',$clauses);
		
		error_log("ShortRegisteredUsers ".$sql);
		
		$query = $this->dbHandle->query($sql,$boundrySetArray);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}
		
		return $users;
	}

	/**
	 * Function to get LDB users
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users LDB users
	 */
	public function getLDBUsers($params = array(), $extraParams = array())
	{
		$this->initiateModel();
		
		$clauses = array();
		$boundrySetArray = array();
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$boundrySetArray[] = $extraParams['boundrySet'];
			$clauses[] = " tu.userid IN (?) ";
		}	
		
		$sql =  "SELECT tu.userid ".
				"FROM tuserdata tu ".
				"WHERE tu.isLDBUser = 'YES' ".
				(count($clauses) > 0 ? "AND ".implode(' AND ',$clauses) : '');
		
		error_log("LDBUsers ".$sql);
		
		$query = $this->dbHandle->query($sql,$boundrySetArray);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM)) {
			$users[$result[0]] = TRUE;
		}
		
		return $users;
	}

	/**
	 * Function to get response users
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users users who are responses
	 */
	public function getResponseUsers($params = array(), $extraParams = array())
	{
		$this->initiateModel();
		
		$users = array();
		$clauses = array();
		$responseCourseIds = array();
		$boundrySetArray = array();
		
		if($params['from'] == 'acrosssite') {

			$boundryFilteredUserClause = "";
			if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
				$boundrySetArray[] = $extraParams['boundrySet'];
				$boundryFilteredUserClause = " AND userId IN (?)";
			}			

			//All response users from across site having last response in particular category
			if($params['type'] == 'lastResponse') {
				$clauses = array();
				if($params['category']) {
					$clauses[] = "categoryId IN (".$params['category'].")";
				}
				else if($params['excludeCategory']) {
					$clauses[] = "categoryId NOT IN (".$params['excludeCategory'].")";
				}
				
				if($params['location'] == 'india') {
					$clauses[] = 'countryId = 2';
				}
				else if($params['location'] == 'abroad') {
					$clauses[] = 'countryId > 2';
				}
				
				$clauseQuery = '';
				if(count($clauses)) {
					$clauseQuery = implode(' OR ', $clauses);
					$clauseQuery = ' WHERE '.$clauseQuery;
				}
				
				if(strlen($boundryFilteredUserClause)) {
					$clauseQuery .= $boundryFilteredUserClause;
				}
				
				//Get last response users
				$sql = "SELECT userId FROM latestUserResponseData".$clauseQuery;
				$query = $this->dbHandle->query($sql,$boundrySetArray);
				foreach ($query->result_array() as $result) {
					$users[$result['userId']] = TRUE;
				}
			}
			else {
				//All response users from across site
				$sql = "SELECT DISTINCT userId FROM tempLMSTable WHERE userId > 0".$boundryFilteredUserClause;
				$query = $this->dbHandle->query($sql,$boundrySetArray);
				foreach ($query->result_array() as $result) {
					$users[$result['userId']] = TRUE;
				}
			}
		
			error_log("ResponseUsersAcrossSite ".$sql);
			
			return $users;
		}else if($params['from'] == 'defaultUsers'){			
			if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
				foreach ($extraParams['boundrySet'] as $key => $result) {
					$users[$result] = TRUE;
				}
			}							
			return $users;
		}
	}

	/**
	 * Function to get online form users
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users online form users
	 */
	public function getOnlineFormUsers($criteria = array(), $extraParams = array())
	{
		//code not in use
		$this->initiateModel();
		
		$boundrySet = '';
		$boundrySetArray = array();
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$boundrySetArray[] = $extraParams['boundrySet'];
			$boundrySet = " AND userId IN (?)";
		}
		
		$sql_arr = array();
		foreach($criteria as $params) {		
			if($params['status'] == 'profile_incomplete') {
				$sql_arr[] = "SELECT userId FROM OF_UserForms ".
					"WHERE courseId = 0 AND status IN ('started','uncompleted')".$boundrySet;
			}
			else if($params['status'] == 'profile_completed') {
				$sql_arr[] = "SELECT userId FROM OF_UserForms u ".
					"WHERE courseId = 0 AND status = 'completed' ".
					"AND NOT EXISTS ".
					"(SELECT userId FROM OF_UserForms u2 WHERE u2.userId = u.userId AND courseId > 0 AND status NOT IN ('started', 'uncompleted'))".$boundrySet;
			}
			else if($params['status'] == 'payment_pending') {
				if($params['instituteId']) {
					$sql = "SELECT course_id FROM course_details ".
						"WHERE status = 'live' AND institute_id IN (".$params['instituteId'].")";
					
					error_log("onlineCourses ".$sql);
					$query = $this->dbHandle->query($sql);
					$results = $query->result_array();
						
					if(count($results) > 0) {
						$courseIds = array();
						foreach($results as $result) {
							$courseIds[] = $result['course_id'];
						}
						$sql_arr[] = "SELECT userId FROM OF_UserForms ".
							"WHERE courseId in (".implode(',',$courseIds).") AND status = 'completed' ".$boundrySet;
					}
				}
				else {
					$sql_arr[] = "SELECT userId FROM OF_UserForms ".
						"WHERE courseId > 0 AND status = 'completed' ".$boundrySet;
				}
			}
			else if($params['status'] == 'payment_success') {
				if($params['instituteId']) {
					$clauses = "instituteId in (".$params['instituteId'].") AND status = 'Success'";
				}
				else {
					$clauses = "status = 'Success'";
				}
				
				$sql_arr[] = "SELECT userId FROM OF_Payments ".
					"WHERE ".$clauses.$boundrySet;
			}
		}
		
		$users = array();
		
		if(count($sql_arr)) {
			$sql = implode(' UNION ',$sql_arr);
			error_log("OnlineFormUsers ".$sql);
			
			$query = $this->dbHandle->query($sql,$boundrySetArray);			
			
			while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
			{
				$users[$result[0]] = TRUE;
			}
		}
		return $users;
	}

	/**
	 * Function to get AnA users
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users AnA users
	 */
	public function getAnAUsers($params = array(), $extraParams = array())
	{
		// code not in use
		$this->initiateModel();
		
		$clauses = array("fromOthers='user' AND parentId=0 AND listingType='institute' AND status IN ('live', 'closed')");
		
		if($params['instituteId']) {
			$clauses[] = "listingTypeId IN (".$params['instituteId'].")";
		}
		$boundrySetArray = array();
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$boundrySetArray[] = $extraParams['boundrySet'];
			$clauses[] = "userId IN (?)";
		}
		
		$sql =  "SELECT userId ".
				"FROM messageTable ".
				"WHERE ".implode(' AND ',$clauses);
		
		error_log("AnAUsers ".$sql);
		
		$query = $this->dbHandle->query($sql,$boundrySetArray);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}
		return $users;
	}

	/**
	 * Function to get users for mailer
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users users for mailer
	 */
	public function getUsersForMailer($params = array(), $extraParams = array())
	{
		$this->initiateModel();
		
		$clauses = array();
		if ($params['type'] == 'range') {
			if (!empty($params['from'])) {
				list($day, $month, $year) = split('/', $params['from']);
				$fromDate = $year.'-'.$month.'-'.$day;
				$clauses[] = "tu.usercreationDate >= ".$this->dbHandle->escape($fromDate);
			}
			if (!empty($params['to'])) {
				list($day, $month, $year) = split('/', $params['to']);
				$toDate = $year.'-'.$month.'-'.$day." 23:59:59";
				$clauses[] = "tu.usercreationDate <= ".$this->dbHandle->escape($toDate);
			}
		}
		else if ($params['type'] == 'interval' && !empty($params['interval']) && is_numeric($params['interval'])) {
			$interval = intval($params['interval']);
			$fromtime = time() - ($interval * 24 * 60 * 60);
			$fromDate = date('Y-m-d', $fromtime);
			$clauses[] = "tu.usercreationDate >= ".$this->dbHandle->escape($fromDate);
		}
		else if ($params['type'] == 'timeWindow') {
			$frequency = intval($params['frequency']);
			if(empty($frequency)) {
				$frequency = 7;
			}
			
			$date = $this->dbHandle->escape($params['date']);
			if (empty($date)) {
				$date = date('Y-m-d');
			}
			$clauses[] = "MOD(DATEDIFF($date,tu.usercreationDate),$frequency) = 0";
			
			if (!empty($params['start'])) {
				$clauses[] = "TIME(tu.usercreationDate ) >= ".$this->dbHandle->escape($params['start']);
			}
			if (!empty($params['end'])) {
				$clauses[] = "TIME(tu.usercreationDate ) <= ".$this->dbHandle->escape($params['end']);
			}
			
			if (!empty($params['from'])) {
				$clauses[] = "tu.usercreationDate >= ".$this->dbHandle->escape($params['from']);
			}
			if (!empty($params['to'])) {
				$clauses[] = "tu.usercreationDate <= ".$this->dbHandle->escape($params['to']);
			}
		}
		else if ($params['type'] == 'users') {
			if (count($params['usersSet']) > 0) {
				$clauses[] = "tu.userid IN (".implode(',',$params['usersSet']).")";
			}
			else {
				return array();
			}
		}
		
		$clauses[] = "tu.hardbounce='0' AND tu.softbounce='0' AND tu.ownershipchallenged='0' AND tu.abused='0' AND tu.unsubscribe='0'";
		
		$sql =  "SELECT tu.userid ".
				"FROM tuserdata tu FORCE INDEX (usercreationDate) ".
				"WHERE tu.usergroup NOT IN ('sums', 'enterprise', 'cms', 'saAdmin', 'saCMS') AND ".implode(' AND ',$clauses);
		
		error_log("UsersFromTimeWindow ".$sql);
		
		$query = $this->dbHandle->query($sql);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}

		return $users;
	}

	/**
	 * Function to get response registrations
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users response registrations
	 */
	public function getResponseRegistrations($params = array(), $extraParams = array())
	{
		$this->initiateModel();
		
		$time_windows = array();
		$current_time_window = $extraParams['timeWindow'];
		list($time_window_start,$time_window_end) = explode(';',$current_time_window);
		
		if($params['frequency'] && $params['delay']) {		
			$numberOfIntervals = $params['delay']/$params['frequency'];
			$recommendationDelay = 0;
			
			for($interval = $numberOfIntervals; $interval >= 1; $interval--) {
				$window = array();
				$delay = '-'.$recommendationDelay.' day';
				
				
				$window['start'] = date('Y-m-d H:i:s',strtotime($delay,strtotime($time_window_start)));
				$window['end'] = date('Y-m-d H:i:s',strtotime($delay,strtotime($time_window_end)));
				$time_windows[] = $window;
				
				$recommendationDelay++;
			}
			
		}
		else if($params['delay']) {
			$recommendationDelay = $params['delay'];
			$window = array();
			$window['start'] = date('Y-m-d H:i:s',strtotime($recommendationDelay,strtotime($time_window_start)));
			$window['end'] = date('Y-m-d H:i:s',strtotime($recommendationDelay,strtotime($time_window_end)));
			$time_windows[] = $window;
		}
		
		$windowClause = "";
		if(count($time_windows)) {
			$windowClause .= '(';
			$count = 1;
			foreach($time_windows as $window) {
				$time_window_start = $window['start'];
				$time_window_end = $window['end'];
				$windowClause .= "(t.usercreationDate >= ".$this->dbHandle->escape($time_window_start)." AND t.usercreationDate < ".$this->dbHandle->escape($time_window_end)." AND lms.submit_date >= ".$this->dbHandle->escape($time_window_start)." AND lms.submit_date < ".$this->dbHandle->escape($time_window_end).")";
				if($count != count($time_windows)) {
					$windowClause .= ' OR ';
				}
				$count++;
			}
			$windowClause .= ')';
		}
		
		$boundrySetArray = array();
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$boundrySetArray[] = $extraParams['boundrySet'];
			$clauses[] = "t.userid IN (?)";
		}
		
		$sql =  "SELECT DISTINCT t.userid ".
				"FROM tuser t, tempLMSTable lms, tuserflag tf ".
				"WHERE lms.userId = t.userid AND t.userid = tf.userId ".
				"AND tf.hardbounce = '0' AND tf.softbounce = '0' ".
				"AND tf.ownershipchallenged='0' AND tf.abused='0' AND tf.unsubscribe = '0' ".
				"AND t.usergroup!='sums' AND t.usergroup!='enterprise' AND t.usergroup!='cms' ".
				"AND ".$windowClause." ".
                                "AND lms.listing_subscription_type ='paid' ".
				(count($clauses) > 0 ? " AND ".implode(' AND ',$clauses) : "");
		
		error_log("ResponseRegistrations ".$sql);
		
		$query = $this->dbHandle->query($sql,$boundrySetArray);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}
		return $users;
	}

	/**
	 * Function to get users for compare responses
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users users who are compare responses
	 */
	public function getUsersForCompareResponses($params = array(), $extraParams = array())
	{
		$this->initiateModel();
		
		$current_time_window = $extraParams['timeWindow'];
		list($time_window_start,$time_window_end) = explode(';',$current_time_window);
		
		$comparisonDelay = intval($params['delay']);
		if(empty($comparisonDelay)) {
			$comparisonDelay = 0;
		}
		$recommendedDelay = $comparisonDelay - 24;
		
		if($params['delayUnit']) {
			$delayUnit = $params['delayUnit'];
		}
		else {
			$delayUnit = 'hours';
		}
		$recommendedDelay .= ' '.$delayUnit;
		$comparisonDelay .= ' '.$delayUnit;
		
		$startTime = date('Y-m-d H:i:s',strtotime($recommendedDelay,strtotime($time_window_start)));
		$clauses[] = "submit_date > ".$this->dbHandle->escape($startTime);
		
		$endTime = date('Y-m-d H:i:s',strtotime($comparisonDelay,strtotime($time_window_start)));
		$clauses[] = "submit_date <= ".$this->dbHandle->escape($endTime);
		
		$boundrySetArray = array();
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$boundrySetArray[] = $extraParams['boundrySet'];
			$clauses[] = "userId IN (?)";
		}
		
		$sql =  "SELECT userId FROM tempLMSTable WHERE listing_subscription_type='paid' AND ".implode(' AND ',$clauses);
		
		error_log("UsersForCompareResponses ".$sql);
		
		$query = $this->dbHandle->query($sql,$boundrySetArray);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}
		return $users;
	}

	/**
	 * Function to get users for online responses
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users users who are online responses
	 */
	public function getUsersForOnlineResponses($params = array(), $extraParams = array())
	{
		$this->initiateModel();
		
		$current_time_window = $extraParams['timeWindow'];
		list($time_window_start,$time_window_end) = explode(';',$current_time_window);
		
		$frequency = intval($params['frequency']);
		if(empty($frequency)) {
			$frequency = 7;
		}
		
		$startDate = date('Y-m-d H:i:s',strtotime('-6 months',strtotime($time_window_end)));
		
		$clauses[] = "MOD(TIMESTAMPDIFF(DAY,submit_date,'$time_window_end'),$frequency) = 0";
		
		$clauses[] = "submit_date > ".$this->dbHandle->escape($startDate);
		
		$clauses[] = "submit_date <= ".$this->dbHandle->escape($time_window_end);
		
		$boundrySetArray = array();
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$boundrySetArray[] = $extraParams['boundrySet'];
			$clauses[] = "userId IN (?)";
		}
		
		$sql =  "SELECT userId FROM tempLMSTable WHERE listing_subscription_type='paid' AND ".implode(' AND ',$clauses);
		
		error_log("UsersForOnlineResponses ".$sql);
		
		$query = $this->dbHandle->query($sql,$boundrySetArray);
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}
		return $users;
	}
	
	/**
	 * Function to find users who are not study abroad responses
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $finalUserIds users who are not study abroad responses
	 */
	public function excludeStudyAbroadResponses($userIds,$startDate)
	{
		$this->initiateModel();
		
		$sql =  "SELECT DISTINCT lms.userid ".
				"FROM tempLMSTable lms, course_details cd,institute_location_table ilt ".
				"WHERE lms.listing_type = 'course' AND lms.listing_type_id = cd.course_id ".
				"AND cd.institute_id = ilt.institute_id ".
				"AND ilt.country_id = 2 ".
				"AND lms.submit_date >= ?";
		
		$query = $this->dbHandle->query($sql, array($startDate));
		$abroadResponses = array();
		foreach($query->result_array() as $result) {
			$abroadResponses[$result['userid']] = TRUE;
		}
		
		$finalUserIds = array();
		foreach($userIds as $userId) {
			if(!$abroadResponses[$userId]) {
				$finalUserIds[] = $userId;
			}
		}
		
		return $finalUserIds;
	}
	
	/**
	 * Function to find leads of study abroad
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $abroadLeads users who are study abroad leads
	 */
	public function getStudyAbroadLeads($params = array(), $extraParams = array())
	{
		$this->initiateModel();
		
		$boundrySetClause = '';
		$boundrySetArray = array();
		if($extraParams['boundrySet'] && count($extraParams['boundrySet']) > 0) {
			$boundrySetArray[] = $extraParams['boundrySet'];
			$boundrySetClause = " AND userid IN (?)";
		}
		
		$sql =  "SELECT userid FROM tUserPref WHERE ExtraFlag = 'studyabroad'".$boundrySetClause;
		$query = $this->dbHandle->query($sql,$boundrySetArray);
		
		error_log("StudyAbroadLeads ".$sql);
		
		$abroadLeads = array();
		foreach($query->result_array() as $result) {
			$abroadLeads[$result['userid']] = TRUE;
		}
		
		return $abroadLeads;
	}
	
	/**
	 * Function to get study abroad users who need to be excluded
	 *
	 * @return array $users study abroad users who need to be excluded
	 */
	public function getStudyAbroadUsersToBeExcluded()
	{
		$this->initiateModel();
		
		$startDate = date('Y-m-d',strtotime('-6 months'));
		
		$users = array();
		
		$sql =  "SELECT userid ".
				"FROM tuserdata ".
				"WHERE usercreationDate > ? AND ExtraFlag = 'studyabroad' ";
		$query = $this->dbHandle->query($sql, array($startDate));
		
		foreach($query->result_array() as $result) {
			$users[$result['userid']] = TRUE;
		}
		
/*		
		$sql =  "SELECT DISTINCT lms.userid ".
				"FROM tempLMSTable lms, categoryPageData cpd ".
				"WHERE lms.listing_type = 'course' AND lms.listing_type_id = cpd.course_id ".
				"AND cpd.country_id > 2 ".
				"AND lms.submit_date >= '".$startDate."'";
				
		$query = $this->dbHandle->query($sql);
		foreach($query->result_array() as $result) {
			$users[$result['userid']] = TRUE;
		}
		
		$sql =  "SELECT DISTINCT lms.userid ".
				"FROM tempLMSTable lms, abroadCategoryPageData acpd ".
				"WHERE lms.listing_type = 'course' AND lms.listing_type_id = acpd.course_id ".
				"AND acpd.status = 'live' ".
				"AND lms.submit_date >= '".$startDate."'";
		
		$query = $this->dbHandle->query($sql);
		foreach($query->result_array() as $result) {
			$users[$result['userid']] = TRUE;
		}
*/

		$sql =  "SELECT DISTINCT lms.userid ".
				"FROM tempLMSTable lms, course_details cd, institute_location_table ilt ".
				"WHERE lms.listing_type = 'course' AND lms.listing_type_id = cd.course_id ".
				"AND cd.institute_id = ilt.institute_id ".
				"AND ilt.country_id > 2 ".
				"AND lms.submit_date >= ?";
				
		$query = $this->dbHandle->query($sql, array($startDate));
		foreach($query->result_array() as $result) {
			$users[$result['userid']] = TRUE;
		}
	
		
		$sql =  "SELECT DISTINCT lms.userid ".
				"FROM tempLMSTable lms, institute_location_table ilt ".
				"WHERE lms.listing_type = 'institute' AND lms.listing_type_id = ilt.institute_id ".
				"AND ilt.country_id > 2 ".
				"AND lms.submit_date >= ?";
				
		$query = $this->dbHandle->query($sql, array($startDate));
		foreach($query->result_array() as $result) {
			$users[$result['userid']] = TRUE;
		}	
		
		return $users;
	}

	/**
	 * Function to get activity users
	 *
	 * @param array $criteria
	 * @return array $users users who are responses
	 */
	public function getActivityUsersByCriteria($criteria = array())
	{
		$this->initiateModel();
		
		$users = array();
		$clauses = array();
		$responseCourseIds = array();

		if($criteria['targetDB'] == 'custom') {
			if($criteria['college_ids'] || $criteria['course_ids']) {
				
				$courseIdsArray = array();
				if($criteria['college_ids']) {
					
					$this->load->builder("nationalInstitute/InstituteBuilder");
			        $instituteBuilder = new InstituteBuilder();
			        $instituteRepo = $instituteBuilder->getInstituteRepository();
			        $this->load->library("nationalInstitute/InstituteDetailLib");
			        $lib = new InstituteDetailLib();

			        $collegeIdsArray = array();
			        $collegeIdsArray = explode(', ', $criteria['college_ids']);
			        
			        $allInstitutes = $instituteRepo->findMultiple($collegeIdsArray);
			        foreach ($allInstitutes as $instituteId => $instituteObject) {
						$type = $instituteObject->getType();
			            $instituteCourseMap = $lib->getInstituteCourseIds($instituteId, $type,'all', null, false);
			            foreach ($instituteCourseMap['courseIds'] as $key => $course_id) {
			            	$responseCourseIds[$course_id] = TRUE;
							$courseIdsArray[] = $course_id;
			            }
			            
			        }

				}
				
				if($criteria['course_ids']) {
					$paramCourseIds = explode(', ', $criteria['course_ids']);
					foreach ($paramCourseIds as $paramCourseId) {
						$responseCourseIds[trim($paramCourseId)] = TRUE;
						$courseIdsArray[] = trim($paramCourseId);
					}
				}
				
				if(!empty($courseIdsArray)) {
					$sql =  "SELECT courseId, userIds FROM tresponsedata 
							WHERE courseId in (?) ";

					$query = $this->dbHandle->query($sql,array($courseIdsArray));
					
					if(count($responseCourseIds) > 0) {
						while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM)) {
							if($responseCourseIds[$result[0]]) {
								$thisUsers = explode(',',$result[1]);
								foreach($thisUsers as $thisUser) {
									if($thisUser > 0) { 
										$users[$thisUser] = TRUE;
									}
								}
							}
						}	
						return $users;
					}
				}
			}

		}
	}
	
	/**
	 * Function to get users for time range
	 *
	 * @param array $criteria
	 * @return array $users users 
	 */
	public function getUsersForTimeRange($criteria = array())
	{
		$this->initiateModel();
		
		$clauses = array();
		if ($criteria['timeRange'] == 'duration') {
			if (!empty($criteria['timeRangeDurationFrom'])) {
				list($day, $month, $year) = split('/', $criteria['timeRangeDurationFrom']);
				$fromDate = $year.'-'.$month.'-'.$day;
				$clauses[] = "tu.usercreationDate >= '".$fromDate."'";
			}
			if (!empty($criteria['timeRangeDurationTo'])) {
				list($day, $month, $year) = split('/', $criteria['timeRangeDurationTo']);
				$toDate = $year.'-'.$month.'-'.$day." 23:59:59";
				$clauses[] = "tu.usercreationDate <= '".$toDate."'";
			}
		}
		else if ($criteria['timeRange'] == 'interval' && !empty($criteria['timeRangeIntervalDays']) && is_numeric($criteria['timeRangeIntervalDays'])) {
			$interval = intval($criteria['timeRangeIntervalDays']);
			$fromtime = time() - ($interval * 24 * 60 * 60);
			$fromDate = date('Y-m-d', $fromtime);
			$clauses[] = "tu.usercreationDate >= '".$fromDate."'";
		}

		$forceIndex = '';
		if(!empty($clauses)){
			$forceIndex = 'USE INDEX (usercreationDate)';
		}
		
		$clauses[] = "tu.hardbounce='0' AND tu.softbounce='0' AND tu.ownershipchallenged='0' AND tu.abused='0'";
		
		$sql =  "SELECT tu.userid,unscr.unsubscribe_category FROM tuserdata tu ".$forceIndex." left join user_unsubscribe_mapping unscr on unscr.user_id = tu.userid and unscr.status = 'live' and unscr.unsubscribe_category =5 WHERE tu.usergroup NOT IN ('sums', 'enterprise', 'cms', 'saAdmin', 'saCMS') AND ".implode(' AND ',$clauses);

		$result = $this->dbHandle->query($sql)->result_array();
		$users = array();
		foreach ($result as $key => $userDetails) {
			if(!($userDetails['unsubscribe_category'] == 5)){
				$users[$userDetails['userid']] = TRUE;
			}
		}
		return $users;
	}


	/**
	 * Function to get response users for mailer
	 *
	 * @param array $params
	 * @param array $extraParams
	 * @return array $users users for mailer
	 */
	public function getResponseUsersForMailer($params = array(), $extraParams = array())
	{
		$this->initiateModel('read');
		
		ini_set('memory_limit','2048M');

		$clauses = array();$data = array();
		$clauses[] = "tu.ExtraFlag IS NULL";
		if ($params['type'] == 'timeWindow') {
			$frequency = intval($params['frequency']);
			if(empty($frequency)) {
				$frequency = 7;
			}
			
			$date = $params['date'];
			if (empty($date)) {
				$date = date('Y-m-d');
			}

			if (!empty($params['from'])) {
				$clauses[] = "lur.modified_at >= ?";
				$data[] = $params['from'];
			}
			if (!empty($params['to'])) {
				$clauses[] = "lur.modified_at <= ?";
				$data[] = $params['to'];
			}		
			if (!empty($params['start'])) {
				$clauses[] = "TIME(tu.usercreationDate) >= ?";
				$data[] = $params['start'];
			}
			if (!empty($params['end'])) {
				$clauses[] = "TIME(tu.usercreationDate) <= ?";
				$data[] = $params['end'];
			}

			if($frequency > 1) {
				$clauses[] = "MOD(DATEDIFF(?,tu.usercreationDate),?) = 0";
				$data[] = $date;
				$data[] = $frequency;
			}

		}
				
		$sql = "SELECT tu.userid FROM latestUserResponseData lur inner join tuserdata tu on lur.userId = tu.userid WHERE ".implode(' AND ',$clauses);
		
		error_log("ResponseUsersFromTimeWindow ".$sql);
		
		$query = $this->dbHandle->query($sql, $data);
		
		$users = array();
		while ($result = mysqli_fetch_array($query->result_id, MYSQLI_NUM))
		{
			$users[$result[0]] = TRUE;
		}

		return $users;
	}

	public function getUserIdsForRedis($time){

		if(empty($time)){
			return;
		}
		$this->initiateModel('read','User');

		$sql = "select userId from latestUserResponseData where modified_at >= '".$time." 00:00:00' and modified_at <= '".$time." 23:59:59'";

		$userids = $this->dbHandle->query($sql)->result_array();
		return $userids;
	}

	public function getUserIdAndTimeMap($userids){
		if(empty($userids)){
			return;
		}

		$this->initiateModel('read','User');

		$sql = "select userid, usercreationDate from tuserdata where userid in (?) and ExtraFlag IS NULL";

		$userids = $this->dbHandle->query($sql,array($userids))->result_array();
		return $userids;		
	}
		
}
