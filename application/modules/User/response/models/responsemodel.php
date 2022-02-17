<?php
/** 
 * Model for database related operations related to response.
*/

class ResponseModel extends MY_Model {
	/**
	 * Variable for DB Handling
	 * @var object
	 */
	private $dbHandle = '';
	
	/**
	 * Constructor Function
	 */
	function __construct(){
		parent::__construct('User');
	}
	
	/**
	 * Function to initiate the Model
	 * 
	 * @param string $operation
	 *
	 */
	private function initiateModel($mode = "read", $module = ''){
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	/**
	 * Function to store data to temp_response_queue
	 * @param : $data array
	 * @return : $last_insert_id
	*/

	function storeTempResponseData($data){

		if(is_array($data) && $data['user_id'] > 0){

			$this->initiateModel('write');
			$sql = "insert into temp_response_queue (`user_id`, `listing_id`, `listing_type`, `preferred_city_id`, `preferred_locality_id`, `visitor_session_id`, `tracking_key`, `is_response_made`, `is_mail_sent`,`action_type`, `submit_time`) values (?,?,?,?,?,?,?,?,?,?,?)";

			$this->dbHandle->query($sql,array($data['user_id'],$data['listing_id'],$data['listing_type'],$data['preferred_city_id'],$data['preferred_locality_id'],$data['visitor_session_id'],$data['tracking_key'],$data['is_response_made'],$data['is_mail_sent'],$data['action_type'], $data['submit_time']));

			$last_insert_id = $this->dbHandle->insert_id();


			//storing queue Id for elastic Indexing
			$sqlEl = "insert into response_elastic_map (queue_id,response_from) values (".$last_insert_id.",'national')";
			$this->dbHandle->query($sqlEl);

			return $last_insert_id;
		}
	}

	/**
	 * Function to store data to user_response_profile
	 * @param : $data array
	 * @return : $last_insert_id
	*/

	function storeUserResponseProfile($data){
		if(is_array($data) && $data['user_id'] > 0){
			$this->initiateModel('write');
			$is_empty_profile = 0;

			$sql = "insert into user_response_profile (`user_id`, `listing_id`, `stream_id`, `substream_id`, `user_profile`, `submit_date`, `queue_id`, status, education_level,`listing_type`) values ";
			$inputData = array();
			if(!empty($data['substream_id']) ){

				foreach ($data['substream_id'] as $key=>$subStreamId) {
					if($subStreamId == 'ungrouped'){
						$subStreamId = 0;
					}
					if($data['user_profile'][$subStreamId] == ''){
						$is_empty_profile = 1;
					}
					$sql .= "(?,?,?,?,?,?,?,?,?,?),";
					$inputData[] = $data['user_id'];
					$inputData[] = $data['listing_id'];
					$inputData[] = $data['stream_id'];
					$inputData[] = $subStreamId;
					$inputData[] = $data['user_profile'][$subStreamId];
					$inputData[] = $data['submit_time'];
					$inputData[] = $data['id'];
					$inputData[] = $data['status'];
					$inputData[] = $data['education_level'];
					$inputData[] = $data['listing_type'];
				}
			}else{
				foreach ($data['user_profile'] as $num => $user_profile) {
					if($user_profile == ''){
						$is_empty_profile = 1;
					}

					$sql .= "(?,?,?,'',?,?,?,?,?,?),";
					$inputData[] = $data['user_id'];
					$inputData[] = $data['listing_id'];
					$inputData[] = $data['stream_id'];
					$inputData[] = $user_profile;
					$inputData[] = $data['submit_time'];
					$inputData[] = $data['id'];
					$inputData[] = $data['status'];
					$inputData[] = $data['education_level'];
					$inputData[] = $data['listing_type'];
				}
			}

			$sql = substr($sql, 0,-1);
			$this->dbHandle->query($sql,$inputData);
			return $is_empty_profile;
		}
	}

	/**
	* Function to get data from queued table
	*/
	function getDataFromQueue($flagType = 'response', $listingType = '', $queueId ='') {

		$this->initiateModel();
		$column = "q.is_response_made";

		$now = strtotime(date('Y-m-d H:i:s'));
		$toTime = date('Y-m-d H:i:s', $now - 60);
		$fromTime = date('Y-m-d H:i:s', $now - 3600);
		
		if($flagType == 'mail') {
			// added to delay the instant mailer for 1 Minute for sending MPT tupple
			$timeFilter = " and q.submit_time > ? and q.submit_time < ? ";
			$column = " q.is_mail_sent ";

		}

	    $query = "SELECT q.*,rp.stream_id from temp_response_queue q inner join user_response_profile rp on q.id = rp.queue_id where ".$column." = 'no' ".$timeFilter ;

	    $sqlData = array();
	    if(!empty($queueId) && $queueId >0){
	    	$query .= " and q.id = ? ";
	    	$sqlData[] = $queueId;
	    }

	    if($listingType != ''){
	    	$query .= " and q.listing_type = ? ";
	    	$sqlData[] = $listingType;
	    }
	    if ($timeFilter){
	    	$sqlData[] = $fromTime;
	    	$sqlData[] = $toTime;
	    }
	    $query .= " group by rp.queue_id";
	    $queryres = $this->dbHandle->query($query,$sqlData);
	    $result  = $queryres->result_array();

	    return $result;

	}

	/**
	* Function to check Existing Response for a user on a listing
	*/
	function checkExistingResponse($data) {

		if($data['user_id'] < 0 || $data['listing_id'] < 0 || $data['listing_type'] == '') {
			return;
		}

		$this->initiateModel('write');

		$current_time = strtotime($data['submit_time']);
		$old_timestamp = $current_time-86400;
		$old_time = date('Y-m-d H:i:s', $old_timestamp);

	    $query = "select id, action from tempLMSTable where userId = ? and listing_type_id = ? and listing_type = ? and submit_date > ?";
	    $queryres = $this->dbHandle->query($query, array($data['user_id'], $data['listing_id'], $data['listing_type'], $old_time));
	    $result  = $queryres->row_array();

	    return $result;
	}

	function getPreviousDayResponses($data) {
		if(empty($data['listing_type']) || empty($data['action'])) {
			return;
		}

		$this->initiateModel('read');

		$from_time = date('Y-m-d H:i:s',strtotime("-2 days"));
		$to_time = date('Y-m-d H:i:s',strtotime("-1 days"));

	    $query = "select t.id, t.userId, tu.firstname as displayName, t.email, t.listing_type_id, t.action, tu.mobile from tempLMSTable t ".
	    		"inner join tuser tu on tu.userid = t.userId ".
	    		"where t.listing_type = ? and t.action in (?) and t.submit_date between ? AND ?";
	    $queryres = $this->dbHandle->query($query, array($data['listing_type'], $data['action'], $from_time, $to_time));
	    
	    $result  = $queryres->result_array();
	    return $result;
	}
    
    /**
	* Function to update response data
	*/
	function updateResponseData($data, $response_id) {

		$this->initiateModel('write');

	    $query = "update tempLMSTable SET action = ?, submit_date = ?, tracking_keyid = ?, visitorsessionid = ? where id = ?";
		$this->dbHandle->query($query, array($data['action_type'], $data['submit_time'], $data['tracking_key'], $data['visitor_session_id'], $response_id));

		$this->dbHandle->where('queue_id', $data['id']);
		$this->dbHandle->where('response_from', 'national');
    	$this->dbHandle->update('response_elastic_map', array('temp_lms_id' => $response_id,'temp_lms_time'=> date("Y-m-d H:i:s"),'use_for_response'=>'y'));

	    return true;

	}

    /**
	* Function to save upgraded response data
	*/
	function saveUpgradedResponseData($response_id, $submit_time) {

		$this->initiateModel('write');

	    $query = "INSERT into upgradedResponseData (tempLMSId,isProcessed,responseDate) values(?,'no',?) on duplicate key update isProcessed='no', responseDate = ?";
		$this->dbHandle->query($query, array($response_id, $submit_time, $submit_time));

	    return true;

	}

	/**
	* Function to update latest user response data
	*/
	function updateLatestUserResponseData($data) {

		$this->initiateModel('write');

	    $query = "UPDATE latestUserResponseData SET action = ?, modified_at = ? WHERE userId = ? AND courseId = ?";
		$this->dbHandle->query($query, array($data['action_type'], $data['submit_time'], $data['user_id'], $data['listing_id']));

	    return true;

	}

	/**
	* Function for checking same course response
	*/
    function checkForSameCourseResponse($user_id, $courseIds){
		
		if(count($courseIds) < 0 || $user_id < 0) {
			return;
		}

		$this->initiateModel('write');

		$queryCmd = "select count(*) as noOfEntries from tempLMSTable where userId = ? and listing_type_id in (?) and listing_type = 'course'";
		$Result = $this->dbHandle->query($queryCmd, array($user_id,$courseIds));
		$row = $Result->row();
		if($row->noOfEntries > 5){
			return false;
		} else {
			return true;
		}
	}

	/**
	* Function for saving response Data
	*/	
	function saveResponseData($data){

		$this->initiateModel('write');

		$sql = "insert into tempLMSTable (userId, displayName, contact_cell, listing_type, listing_type_id, submit_date, action, email, message, marketingFlagSent, marketingUserKeyId, listing_subscription_type, tracking_keyid, visitorsessionid,isClientResponse) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$this->dbHandle->query($sql,array($data['user_id'], $data['display_name'], $data['mobile'], $data['listing_type'], $data['listing_id'], $data['submit_time'], $data['action_type'], $data['email_id'], $data['message'], $data['marketingFlagSent'], $data['marketingUserKeyId'], $data['listing_subscription_type'], $data['tracking_key'], $data['visitor_session_id'],$data['isClientResponse']));

		$lastResponseId = $this->dbHandle->insert_id();

		$this->dbHandle->where('queue_id', $data['id']);
		$this->dbHandle->where('response_from', 'national');
    	$this->dbHandle->update('response_elastic_map', array('temp_lms_id' => $lastResponseId,'temp_lms_time'=> date("Y-m-d H:i:s"),'use_for_response'=>'y'));

		return $lastResponseId;
	}

	/**
	* Function to check Existing Response for a user in latestUserResponse Table
	*/
	function checkExistingResponseInLatestUserResponse($userId) {

		if($userId < 0) {
			return;
		}

		$this->initiateModel('write');

	    $query = "SELECT userId FROM latestUserResponseData WHERE userId = ?";
	    $queryres = $this->dbHandle->query($query, array($userId));
	    $result  = $queryres->row_array();

	    return $result;

	}

	/**
	* Function to update latest user response data
	*/
	function updateLatestUserResponseDataByUserId($data) {

		$this->initiateModel('write');

	    $query = "UPDATE latestUserResponseData SET courseId = ?, instituteId = ?, countryId = ?, action = ?, listing_subscription_type = ?, modified_at = ?, universityId = NULL, categoryId = '0' WHERE userId = ?";
		$this->dbHandle->query($query, array($data['listing_id'], $data['institute_id'], $data['country_id'], $data['action_type'], $data['listing_subscription_type'], $data['submit_time'], $data['user_id']));

	    return true;

	}

	/**
	* Function to update user flag 
	*/
	function updateUserFlag($userId) {

		if($userId < 0) {
			return;
		}

		$this->initiateModel('write');

	    $query = "UPDATE tuserflag SET isLDBUser = 'YES' WHERE userId = ?";
		$this->dbHandle->query($query, array($userId));

	    return true;

	}

	/**
	* Function for saving latest user response Data
	*/	
	function saveLatestUserResponseData($data){

		$this->initiateModel('write');

		$sql = "insert into latestUserResponseData (userId, courseId, instituteId, countryId, action, listing_subscription_type, modified_at) values (?,?,?,?,?,?,?)";
		$this->dbHandle->query($sql,array($data['user_id'], $data['listing_id'], $data['institute_id'], $data['country_id'], $data['action_type'], $data['listing_subscription_type'], $data['submit_time']));

	    return true;

	}

	/**
	* Function to check Existing Response Data in tresponsedata Table
	*/
	function checkExistingResponseData($listing_id, $action) {

		if($listing_id < 0 || $action == '') {
			return;
		}

		$this->initiateModel('write');

	    $query = "SELECT id, userIds FROM tresponsedata WHERE courseId = ? and action = ?";
	    $queryres = $this->dbHandle->query($query, array($listing_id, $action));
	    $result  = $queryres->row_array();

	    return $result;

	}

	/**
	* Function for saving consolidated user response Data
	*/	
	function saveConsolidatedUserResponseData($listing_id, $action_type, $user_id){

		$this->initiateModel('write');

		$sql = "insert into tresponsedata (courseId, action, userIds) values (?,?,?)";
		$this->dbHandle->query($sql,array($listing_id, $action_type, $user_id));

	    return true;

	}
	
	/**
	* Function for updating consolidated user response Data
	*/	
	function updateConsolidatedUserResponseData($userIdArray, $id) {

		if((empty($userIdArray)) || ($id < 0)) {
			return;
		}

		$this->initiateModel('write');

		$userIds = implode(",", $userIdArray);
		$sql = "update tresponsedata set userIds = ? where id = ?";
		$this->dbHandle->query($sql,array($userIds, $id));

	    return true;

	}

	/**
	* Function for updating action in recommendations_info table
	*/	
	function registerApplyOnRecommendationInstitute($institute_id, $user_id, $submit_time)	{

		if($institute_id < 0 || $user_id < 0 || $submit_time == '') {
			return;
		}

		$this->initiateModel('write');

		$sql = "UPDATE recommendations_info SET actionTaken = 'applied', actionTakenAt = ? WHERE userID = ? AND instituteID = ? AND actionTaken = 'clicked'";
		$this->dbHandle->query($sql,array($submit_time, $user_id, $institute_id));

	    return true;

	}

	/**
	* Function for updating action in recommendations_courses_info table
	*/	
	function registerApplyOnRecommendationCourse($listing_id,$user_id)	{

		$this->initiateModel('write');

		$sql = "UPDATE recommendations_courses_info SET actionTaken = 'applied', actionTakenAt = ? WHERE userID = ? AND courseID = ? AND actionTaken = 'clicked'";
		$this->dbHandle->query($sql,array(date('Y-m-d H:i:s'), $user_id, $listing_id));

	    return true;

	}

	/**
	* Function for saving consolidated user response Data
	*/	
	function saveResponseLocationData($lastResponseId, $instituteLocationId, $requestResponseId){

		$this->initiateModel('write');

		$sql = "insert into responseLocationTable (responseId, instituteLocationId, requestResponseId) values (?,?,?)";
		$this->dbHandle->query($sql,array($lastResponseId, $instituteLocationId, $requestResponseId));

	    return true;

	}

	/**
	* Function for saving response Data in tempLmsRequest
	*/	
	function saveTempResponseData($data){

		$this->initiateModel('write');

		$is_mail_sent = 'no';
		if($data['isClientResponse'] == 'No'){
			$is_mail_sent = 'NotToSend';
		}

		$sql = "insert into tempLmsRequest (listing_type_id, listing_type, userId, displayName, email, contact_cell, listing_subscription_type, submit_date, mailSent) values (?,?,?,?,?,?,?,?,?) on duplicate key update count=count+1, submit_date = ?";

		$this->dbHandle->query($sql,array($data['listing_id'], $data['listing_type'], $data['user_id'], $data['display_name'], $data['email_id'], $data['mobile'], $data['listing_subscription_type'], $data['submit_time'], $is_mail_sent, $data['submit_time']));

		$lastTempResponseId = $this->dbHandle->insert_id();

		$this->dbHandle->where('queue_id', $data['id']);
		$this->dbHandle->where('response_from', 'national');
    	$this->dbHandle->update('response_elastic_map', array('temp_lms_request_id' => $lastTempResponseId));


		return $lastTempResponseId;
	}

	/**
	* Function to get course contact details
	*/
	function getCourseContactDetails($listing_id, $listing_type) {

		if($listing_id < 0 || $listing_type == '') {
			return;
		}

		$this->initiateModel('write');

	    $query = "SELECT slc.generic_contact_number, slc.admission_contact_number, tu.mobile,tu.userid FROM listings_main lm LEFT JOIN shiksha_listings_contacts slc ON lm.listing_type_id = slc.listing_id LEFT JOIN tuser tu ON lm.username = tu.userid WHERE lm.listing_type_id = ? AND lm.listing_type = ? AND lm.status = 'live'";
	    $queryres = $this->dbHandle->query($query, array($listing_id, $listing_type));
	    $result  = $queryres->row_array();

	    return $result;

	}

	/**
	* Function for saving call data for Tracking
	*/	
	function recordCallWidgetLoad($instituteId, $courseId, $action, $sessionId){

		$this->initiateModel('write');

		$sql = "INSERT INTO callPatchingPilot (date, type, instituteId, courseId, sessionId) values (CURRENT_TIMESTAMP,?,?,?,?)";

		$this->dbHandle->query($sql,array($action, $instituteId, $courseId, $sessionId));

		return true;
	}

	/**
	* Function for updating flag in temp_response_queue table
	*/	
	function updateTempResponseQueue($temp_response_queue_id, $updateMailFlag = 'N') {

		$this->initiateModel('write');

		$column = 'is_response_made';
		if($updateMailFlag == 'Y') {
			$column = 'is_mail_sent';
		} 

		$sql = "UPDATE temp_response_queue set ".$column." = 'yes' where id = ?";

		$this->dbHandle->query($sql,array($temp_response_queue_id));

		return true;

	}

	/**
	* Function for tracking download brochure
	*/	
	function trackDownloadBrochure($data, $brochureURL){

		$this->initiateModel('write');

		$sql = "INSERT INTO listingsBrochureDownloadTracking (listingType, listingTypeId, userId, downloadedAt, downloadedFrom, brochureUrl, sessionId) values (?,?,?,?,?,?,?)";

		$this->dbHandle->query($sql,array($data['listing_type'], $data['listing_id'], $data['user_id'], $data['submit_time'], $data['action_type'], $brochureURL, $data['visitor_session_id']));

		return true;
	}

	/**
	* Function for updating user response profile table status By User Id
	*/	
	function updateResponseProfileStatusByUserId($userId) {
		if(empty($userId) || $userId == 5137653 || $userId == '5137653'){
			return;
		}

		$this->initiateModel('write');

		$sql = "UPDATE user_response_profile set status = 'draft' where user_id = ?";
		$this->dbHandle->query($sql,array($userId));

		return true;

	}

	/**
	* Function for updating user response profile table status By User Id
	*/	
	function updateResponseProfileStatusFromRegistration($userId) {
		if(empty($userId) || $userId == 5137653 || $userId == '5137653'){
			return;
		}

		$this->initiateModel('write');

		$sql = "UPDATE user_response_profile set status = 'draft' where user_id = ? and education_level != 'None'";

		$this->dbHandle->query($sql,array($userId));

		return true;

	}

	/**
	* Function to get Queue Ids For a user id and listing id
	*/
	function getQueueIdByUserAndListing($user_id, $listing_id,$listing_type) {

		if($user_id < 0 || $listing_id < 0) {
			return;
		}

		$this->initiateModel();

	    $query = "SELECT queue_id from user_response_profile where user_id = ? and listing_id = ? and listing_type = ? and status = 'live' order by id desc limit 1";
	    $queryres = $this->dbHandle->query($query, array($user_id, $listing_id, $listing_type));
	   
	    
	    $result  = $queryres->row_array();

	    return $result;

	}

	/**
	* Function for updating user response profile table status by profile Id
	*/	
	function updateResponseProfileStatusById($user_id, $listing_id, $queue_id) {

		if($user_id < 0 || $listing_id < 0 || $queue_id < 0 || $user_id == 5137653 || $user_id == '5137653') {
			return;
		}

		$this->initiateModel('write');

		$sql = "UPDATE user_response_profile set status = 'draft' where user_id = ? and listing_id = ? and queue_id != ?";
		$this->dbHandle->query($sql, array($user_id, $listing_id, $queue_id));

		return true;

	}

	/**
	* Function for updating response location affinity
	*/	
	function updateResponseLocationAffinity($userId, $cityId) {

		if($userId < 0 || $cityId < 0) {
			return;
		}

		$this->initiateModel('write');

		$sql = "INSERT INTO userResponseLocationAffinity (userId, cityId, affinity) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE affinity = affinity+1";

		$this->dbHandle->query($sql, array($userId, $cityId));

		return true;

	}

	/**
	 * Function to get pre registration course views
	 *
	 * @param array $userIds user ids
	 * @return array $preRegistrationCourseViews pre registration course views for users
	 */
	public function getPreRegistrationCourseViews($userIds) {

	    $this->initiateModel('read');
	    
	    $preRegistrationCourseViews = array();
	    
	    if(count($userIds)) {
			$sql = "SELECT DISTINCT tuser.userid, listing_track.course_id
				FROM listing_track
				INNER JOIN user_session_info ON listing_track.user_session_id = user_session_info.id
				INNER JOIN tuser ON user_session_info.user_id  = tuser.userid
				WHERE tuser.userid IN (?)
				AND listing_track.visit_time < tuser.usercreationDate
				AND listing_track.is_institute = 0
				ORDER BY listing_track.visit_time DESC";
			
			$query = $this->dbHandle->query($sql,array($userIds));
			
			if($query->num_rows() > 0) {
			    foreach($query->result_array() as $row) {
					$preRegistrationCourseViews[$row['userid']][] = $row['course_id'];
			    }
			}
	    }
	    
	    return $preRegistrationCourseViews;
	}

	/**
	* Function for getting all shortlisted course users
	*/	
	public function getUserAllShortlistedCourses() {

        $this->initiateModel('read');
        
        $userShortlistedCourses = array();
        
        $shortlistedBefore = date("Y-m-d H:i:s", strtotime("-30 minutes"));

        //$recatLiveDate = '2017-02-28 00:00:00';
        $shortlistedAfter = date("Y-m-d H:i:s", strtotime("-6 months"));
        
        $sql = "SELECT DISTINCT userShortlistedCourses.userId, userShortlistedCourses.courseId, userShortlistedCourses.pageType, userShortlistedCourses.scope,userShortlistedCourses.tracking_keyid, userShortlistedCourses.visitorSessionid
            FROM userShortlistedCourses
            WHERE userShortlistedCourses.isResponseConverted = 0
            AND userShortlistedCourses.shortListTime <= ?
            AND userShortlistedCourses.shortListTime >= ?
            AND userShortlistedCourses.userId > 0
            AND userShortlistedCourses.status = 'live'            
            ORDER BY userShortlistedCourses.shortListTime ASC";
        
        $query = $this->dbHandle->query($sql,array($shortlistedBefore,$shortlistedAfter))->result_array();
        return $query;

    }

    /**
	* Function to get data from queued table
	*/
	function getLastResponseActionType($userId) {

		$this->initiateModel('write');

	    $query = "SELECT action_type from temp_response_queue where user_id = ? order by id desc limit 1";
	    $queryres = $this->dbHandle->query($query, array($userId));
	    $result  = $queryres->row_array();

	    return $result;

	}

	/**
	* Function to last education level in user response profile table
	*/
	function getEducationLevelOfUserResponseProfile($userId) {

		$this->initiateModel('write');

	    $query = "SELECT distinct education_level from user_response_profile where user_id = ? and education_level != 'None' and status = 'live'";
	    $queryres = $this->dbHandle->query($query, array($userId));
	    $result  = $queryres->row_array();

	    return $result['education_level'];

	}

	/**
	* Function to update data in tuserdata table
	*/
	function updateUserData($userId) {

		$this->initiateModel('write');		 
		$userId = intval($userId);

		$sql = "SELECT a.userid,a.usercreationDate,a.usergroup,
				b.hardbounce,b.softbounce,b.unsubscribe,b.ownershipchallenged,b.abused,b.isLDBUser,d.ExtraFlag 
				FROM `tuser` a
				LEFT JOIN tuserflag b ON b.userid = a.userid
				LEFT JOIN tUserPref d ON d.UserId = a.userid
				WHERE a.userid = ?";
		
		$query = $this->dbHandle->query($sql,array($userId));
		$tUserData = $query->row_array();
		
		$tUserData['countryId'] = 2;
		
		$sql = "SELECT userid FROM tuserdata WHERE userid = ?";
		$query = $this->dbHandle->query($sql,array($userId));
		$currentUserData = $query->row_array();
		
		if($currentUserData['userid']) {
			$this->dbHandle->where('userid',$userId);
			$this->dbHandle->update('tuserdata',$tUserData);
		}
		else {
			$this->dbHandle->insert('tuserdata',$tUserData);
		}

	}

	/**
	* Function to check Existing Response for a user on a listing
	*/
	function checkExistingResponseFromQueue($data) {

		if($data['user_id'] < 0 || $data['listing_id'] < 0 || $data['listing_type'] == '') {
			return;
		}

		$this->initiateModel('write');

		$current_time  = strtotime($data['submit_time']);
		$old_timestamp = $current_time-86400;
		$old_time      = date('Y-m-d H:i:s', $old_timestamp);
		global $contactDetailsResponseTrackingKeys,$shortlistTrackingKeys,$compareTrackingKeys;
		$TrackingKeyExceptDownloadBroucher = array_merge($contactDetailsResponseTrackingKeys,$shortlistTrackingKeys,$compareTrackingKeys);

		if($data['listing_type'] == 'exam'){
			global $examViewedResponseTrackingKeys;
			$query    = "select id from temp_response_queue where user_id = ? and listing_id = ? and listing_type = ? and submit_time > ? and is_mail_sent = 'yes' and tracking_key not in (?) and action_type not in (?)";
			$inputArray = array($data['user_id'], $data['listing_id'], $data['listing_type'],$old_time,$examViewedResponseTrackingKeys, $data['actionTypesForNotSendingMail']);
		}
		else{
			if(isset($data['tracking_keys']))
			{
				$query    = "select id from temp_response_queue where user_id = ? and listing_id = ? and listing_type = ? and tracking_key in (?) and action_type not in (?) and submit_time > ? and is_mail_sent = 'yes'";
				$inputArray = array($data['user_id'], $data['listing_id'], $data['listing_type'], $data['tracking_keys'], $data['actionTypesForNotSendingMail'], $old_time);
			}
			else
			{	
				$query    = "select id from temp_response_queue where user_id = ? and listing_id = ? and listing_type = ? and tracking_key not in (?) and action_type not in (?) and submit_time > ? and is_mail_sent = 'yes'";			
				$inputArray = array($data['user_id'], $data['listing_id'], $data['listing_type'], $TrackingKeyExceptDownloadBroucher, $data['actionTypesForNotSendingMail'], $old_time);
			}
		}

		$queryres = $this->dbHandle->query($query, $inputArray);
		$result   = $queryres->row_array();
	    return $result;

	}

	public function upgradedResponseDataForMis($responseId){
		if(empty($responseId)){
			return false;
		}

		$this->initiateModel('write');
		$insertQuery = "INSERT into upgradedResponseData (tempLMSId,isProcessed,responseDate) values(?,'no',now()) on duplicate key update isProcessed='no', responseDate =now() ";
        return $this->dbHandle->query($insertQuery, array($responseId));
	}


    /**
	* Function to update response submit time
	*/
	function updateResponseTablesTime($data, $response_id) {

		$this->initiateModel('write');

	    $query = "UPDATE tempLMSTable SET submit_date = ? where id = ?";
		$this->dbHandle->query($query, array($data['submit_time'], $response_id));


	    $query2 = "UPDATE latestUserResponseData SET modified_at = ? WHERE userId = ? AND courseId = ?";
		$this->dbHandle->query($query2, array($data['submit_time'], $data['user_id'], $data['listing_id']));

	    return true;

	}


	function updateExamResponseTablesTime($data,$response_id){
		$this->initiateModel('write');

	    $query = "UPDATE tempLMSTable SET submit_date = ? where id = ?";
		$this->dbHandle->query($query, array($data['submit_time'], $response_id));
	}

	function fetchResponsesToIndex($userId,$listing_type, $listing_type_id){
		if($userId<1 || $listing_type == '' || empty($listing_type) || $listing_type_id<1 ){
			return false;
		}
		

		$this->initiateModel();

		if($listing_type == 'exam' ){
			
			$query = "SELECT tLMS.id, tu.userId, tu.city as user_city, tu.locality as user_locality,tPref.ExtraFlag, tLMS.listing_type, tLMS.listing_type_id, action, listing_subscription_type, tracking_keyid, tKey.keyName , site, siteSource,tLMS.submit_date, exmGrp.examId from tempLMSTable tLMS 
	    	join tracking_pagekey tKey on tracking_keyid=tKey.id 
	    	join tuser tu on tu.userid = tLMS.userId 
	    	join tUserPref tPref on tPref.userId = tu.userid
	    	join exampage_groups exmGrp on exmGrp.groupId = tLMS.listing_type_id
	    	where exmGrp.status='live' and tLMS.userId = ? and tLMS.listing_type=? and tLMS.listing_type_id=? ";

		}else{
			$query = "SELECT tLMS.id, tu.userId, tu.city as user_city, tu.locality as user_locality,tPref.ExtraFlag, tLMS.listing_type, tLMS.listing_type_id, action, listing_subscription_type, tracking_keyid, tKey.keyName , site, siteSource,tLMS.submit_date, instLoc.city_id as response_city, instLoc.locality_id as response_locality from tempLMSTable tLMS 
	    	join tracking_pagekey tKey on tracking_keyid=tKey.id 
	    	join tuser tu on tu.userid = tLMS.userId 
	    	join tUserPref tPref on tPref.userId = tu.userid
	    	left join  responseLocationTable rLoc on rLoc.responseId=tLMS.id
	    	left join shiksha_institutes_locations instLoc on instLoc.listing_location_id= rLoc.instituteLocationId and instLoc.status='live' 
	    	where tLMS.userId = ? and tLMS.listing_type=? and tLMS.listing_type_id=? ";
	
		}

	    $result = $this->dbHandle->query($query, array($userId,$listing_type,$listing_type_id))->result_array();
	    
	    return $result;

	}

	function getListingData($listing_type,$listing_type_id){
		if( $listing_type == '' || empty($listing_type) || $listing_type_id<1 ){
			return false;
		}

		$this->initiateModel();

	    $query = "select lm.username as client_id, shk.primary_id as institute_id from listings_main lm join shiksha_courses shk on shk.course_id= lm.listing_type_id where lm.listing_type_id = ? and lm.status='live' and shk.status='live' and lm.listing_type='course'";
	    	    
	    $result = $this->dbHandle->query($query, array($listing_type_id))->result_array();
	    
	    return $result;

	}

	public function insertInIndexingQueue($userId, $listing_type, $listing_type_id, $extraData){
		if($userId<1 ){
			return false;
		}

		if($listing_type_id<1 && $extraData==''){
			return false;
		}

		$this->initiateModel('write');

		$data_array = array();

		if($listing_type_id>0){
			if(!empty($extraData)){
	    		$query = "insert into UserResponseIndexingQueue (user_id, listing_type,listing_type_id,  extra_data) VALUES (?, ?, ?,?)";

	    		$data_array = array($userId, $listing_type, $listing_type_id, $extraData);
			}else{

				$query = "insert into UserResponseIndexingQueue (user_id, listing_type, listing_type_id) VALUES (?,?,?)";

				$data_array = array($userId, $listing_type, $listing_type_id);
			}			
		}else{
			$query = "insert into UserResponseIndexingQueue (user_id, extra_data) VALUES (?,?)";
			$data_array = array($userId, $extraData);
		}
	    	    
	    $result = $this->dbHandle->query($query,$data_array);
	    
	    return $result;		

    }

    public function insertInResponseIndexLog($userId, $queueId, $extraData){
		if($userId<1 ){
			return false;
		}

		if($queueId<1 && $extraData==''){
			return false;
		}

		$this->initiateModel('write');
		$data_array = array();
		if($queueId>0){
			if(!empty($extraData)){
	    		$query = "insert into response_index_log (user_id, queue_id,  extra_data) VALUES (?, ?, ?)";
	    		$data_array = array($userId, $queueId, $extraData);
			}else{
				$query = "insert into response_index_log (user_id, queue_id) VALUES (?,?)";
				$data_array = array($userId, $queueId);
			}			
		}else{
			$query = "insert into response_index_log (user_id, extra_data) VALUES (?,?)";
			$data_array = array($userId, $extraData);
		}
	    	    
	    $result = $this->dbHandle->query($query,$data_array);	    
	    return $result;		
    }

    public function insertInIndexingQueueProfile($userId, $listing_type, $listing_type_id, $extraData){
		if($userId<1 || $listing_type == '' || empty($listing_type) || $listing_type_id<1 ){
			return false;
		}

		$this->initiateModel('write');

	    $query = "insert into UserResponseIndexingQueue (user_id, listing_type_id, listing_type, extra_data) VALUES ($userId, $listing_type, $listing_type_id, $extraData)";
	    	    
	    $result = $this->dbHandle->query($query);
	    
	    return $result;		


    }

    public function getUserExtraFlag($user_id){
    	if($user_id<1){
    		return;
    	}

    	$this->initiateModel();

	    $query = "select ExtraFlag from tUserPref where userId=?";
	    	    
	    $result = $this->dbHandle->query($query, array($user_id))->result_array();
	    
	    return $result[0]['ExtraFlag'];
    }

    public function getUsersToIndexFromQueue(){
    	$this->initiateModel();

    	$query = "select id, user_id, listing_type_id, listing_type, extra_data from UserResponseIndexingQueue where status='queued'";
	    	    
	    $result = $this->dbHandle->query($query)->result_array();
	    
	    return $result;
    }

    public function getDataFromResponseLog(){
    	$this->initiateModel();

    	$timeLimit = date('Y-m-d H:i:00',strtotime("-5 min"));

    	$query = "select id, user_id, queue_id, extra_data, queued_time from response_index_log where  queued_time<='". $timeLimit."' and status='queued' limit 2000";	    	    
	    $result = $this->dbHandle->query($query)->result_array();
	    
	    return $result;
    }

    public function updateUserStatusInQueue($user_ids, $last_index_id){
    	if($user_ids<1){
    		return;
    	}

    	$this->initiateModel('write');

    	$query = "update UserResponseIndexingQueue set status='indexed', indexed_time = now() where user_id in (?) and status='queued' and id <= ?";
	    	    
	    $result = $this->dbHandle->query($query, array($user_ids,$last_index_id ));

	    return $result;
    }

    public function updateResponseLogQueue($indexLogPrimaryIds){
    	if($indexLogPrimaryIds<1){
    		return;
    	}

    	$this->initiateModel('write');

    	$query = "update response_index_log set status='indexed', indexed_time = now() where id in (?) and status='queued'";
	    	    
	    $result = $this->dbHandle->query($query, array($indexLogPrimaryIds));

	    return $result;
    }
   

    public function getLastProcessedId($process ='RESPONSE_ALLOCATION',$handle = 'read'){
		$this->initiateModel($handle);

    	$query = "select lastprocessedid from SALeadAllocationCron where process= ?";
	    	    
	    $result = $this->dbHandle->query($query,array($process))->result_array();
	    
	    return $result[0]['lastprocessedid'];    	
    }

    public function getUnallocatedResponses($last_processed_id){
    	if($last_processed_id<1){
    		return;
    	}

    	$this->initiateModel();
    	$current_year_as_pref_year = array(2018,2019);

    	$query = "select tLMS.id, tu.userId, tu.city, listing_type_id, action, tLMS.submit_date from tempLMSTable tLMS join tuser tu on tu.userId=tLMS.userId join tuserflag tFlag on tu.userId=tFlag.userId join tUserPref pref on pref.userId=tu.userId
    	 where tLMS.id>? and listing_type='exam' and tFlag.isTestUser='NO' and pref.prefYear in (?)";
	    	    
	    $result = $this->dbHandle->query($query,array($last_processed_id, $current_year_as_pref_year))->result_array();
	    
	    return $result;
    }

    public function getGroupMatchedSubscriptions($exam_group_id, $submit_date){
    	if ($exam_group_id<1) {
    		return ;
    	}

    	if (empty($submit_date)) {
    		return ;
    	}

    	$this->initiateModel();

    	$query = "select eResCrtria.subscriptionId, eResCrtria.entityValue, campaignType, quantityExpected, quantityDelivered, endDate from examResponseSubscriptionEnitity eResCrtria join examResponseSubscription eResSubsrptn on  eResCrtria.subscriptionId = eResSubsrptn.id where eResSubsrptn.status='active' and eResCrtria.entityType='groupId' and eResCrtria.entityValue IN (?) and eResSubsrptn.startDate <= ?";
	    	    
	    $result = $this->dbHandle->query($query,array($exam_group_id, $submit_date))->result();
	    return $result;	
    }

    public function getCityMatchedSubscriptions($subscrption_id, $city_id){
    	$this->initiateModel();

    	if(count($subscrption_id)<1 || count($city_id)<1){
    		return;
    	}

    	$query = "select eResCrtria.subscriptionId, clientId from examResponseSubscriptionEnitity  eResCrtria join examResponseSubscription eResSubsrptn on  eResCrtria.subscriptionId = eResSubsrptn.id where  eResSubsrptn.status='active' and eResCrtria.entityType='userCity' and eResCrtria.entityValue IN (?) and eResCrtria.subscriptionId IN (?)";
	    	    
	    $result = $this->dbHandle->query($query,array($city_id, $subscrption_id))->result();
	    
	    return $result;	
    }

    public function storeIndividualMatchedSubscription($user_response_data, $client_ids){
		$this->initiateModel('write');  
		
		if(count($client_ids)<1){
			return;
		}

		$query = "INSERT IGNORE INTO examResponseAllocation(subscriptionId, userId,clientId,entityType,entityValue,tempLmsId) 
				VALUES";
		
		$inputData = array();
		foreach ($client_ids as $subscriptionId => $client_id) {
			$query .= "(?,?,?,?,?,?),";
			$inputData[] = $subscriptionId;
			$inputData[] = $user_response_data['userId'];
			$inputData[] = $client_id;
			$inputData[] = $user_response_data['entityType'];
			$inputData[] = $user_response_data['listing_type_id'];
			$inputData[] = $user_response_data['id'];
			
		}
		
		$query = substr($query, 0,-1);

		$this->dbHandle->query($query,$inputData);
    }

    public function updateLastProcessedId($recent_processed_id,$process ='RESPONSE_ALLOCATION' ){
    	if($recent_processed_id<1){
    		return;
    	}

    	$this->initiateModel('write');  

    	$query = "update SALeadAllocationCron set lastprocessedid = ? where process=?";
	    	    
	    $result = $this->dbHandle->query($query,array($recent_processed_id,$process));
    }

    public function getNewExamResponseSubscription(){
    	$this->initiateModel();

    	$query = "select id, clientId, groupIds, userLocationIds, campaignType, startDate, endDate, quantityExpected, quantityDelivered from examResponseSubscription where status='active' and isProcessed='no'";
	    	    
	    $result = $this->dbHandle->query($query)->result_array();
	    
	    return $result;    		
    }

    public function updateDeliveredCountForSubscription($matched_subscriptions, $quantity_to_add){
    	if (count($matched_subscriptions)<1) {
    		return;
    	}

    	$this->initiateModel('write');  

    	$query = "update examResponseSubscription set quantityDelivered = quantityDelivered+ ? where id IN (?)";
	    	    
	   	$this->dbHandle->query($query,array($quantity_to_add, $matched_subscriptions));
	    
    }

    public function getUsersForQuantityBased($city_ids, $exam_group_ids, $last_processed_id, $start_date, $quantityExpected){
    	if(count($exam_group_ids)<1 || $last_processed_id<1 || empty($quantityExpected)){
    		return;
    	}

    	$this->initiateModel();

    	$city_where_clause = '';
    	$sql_input_array = array();
    	if($city_ids != '' ){
    		$city_ids = explode(',', $city_ids);    		
    		array_push($sql_input_array, $city_ids);

    		 $city_where_clause = "tu.city in (?) and";
    	}

    	$query = "select distinct tu.userId
    				from tempLMSTable tLMS 
    				join tuser tu on tu.userId=tLMS.userId 
    				join tuserflag tFlag on tFlag.userId=tu.userId 
    				where $city_where_clause   tLMS.listing_type_id in (?)
    					 and listing_type='exam' and  tLMS.id<=? 
    					 and tFlag.isTestUser='NO' and tLMS.submit_date>=? 
    					 limit $quantityExpected";

    					 

    	array_push($sql_input_array, $exam_group_ids);
    	array_push($sql_input_array, $last_processed_id);
    	array_push($sql_input_array, $start_date);


	    	    
	    $result = $this->dbHandle->query($query,$sql_input_array)->result();
	    return $result;    			
    }

    public function getResponsesForQuantityBased($user_ids, $exam_group_ids){
    	if(count($exam_group_ids)<1 || count($user_ids)<1){
    		return;
    	}

    	$this->initiateModel();

    	$query = "select userId, id, listing_type_id from tempLMSTable where userId in (?) and listing_type_id in (?) and listing_type='exam' order by id desc";
	    	    
	    $result = $this->dbHandle->query($query,array($user_ids, $exam_group_ids))->result_array();
	    
	    return $result;    			
    }

    public function storeMatchedResponses($responses, $client_id, $entityType, $subscriptionId,$campaignType,$quantity_expected=0){
    	if(count($responses)<1){
    		return;
    	}
    	$totalResponse = 0;
    	$responseUserGrpMapping = array();
		$this->initiateModel('write');  
		
		$query = "INSERT IGNORE INTO examResponseAllocation(subscriptionId, userId,clientId,entityType,entityValue,tempLmsId, mailSent) 
				VALUES";
		
		$inputData = array();
		foreach ($responses as $response) {	
			$query .= "(?,?,?,?,?,?,?),";
			$inputData[] = $subscriptionId;
			$inputData[] = $response['userId'];
			$inputData[] = $client_id;
			$inputData[] = $entityType;
			$inputData[] = $response['listing_type_id'];
			$inputData[] = $response['id'];
			$inputData[] = 'NotToSend';

			// get unique response mapping
			if(!isset($responseUserGrpMapping[$response['userId']][$response['listing_type_id']])){
				$totalResponse ++;
				$responseUserGrpMapping[$response['userId']][$response['listing_type_id']] = 1;
				if($campaignType == "quantity"){
					if($totalResponse == $quantity_expected){
						break;
					}
				}
			}
		}		

		$query = substr($query, 0,-1);

		$this->dbHandle->query($query,$inputData);
		return $totalResponse;
    }

    public function markSubscriptionProcessed($subscription_id){
    	if($subscription_id<1){
    		return;
    	}

		$this->initiateModel('write');

		$query = "update examResponseSubscription set isProcessed ='YES' where id = ?";

		$this->dbHandle->query($query,array($subscription_id));
    }

    public function getResponsesForDurationBased($city_ids, $exam_group_ids, $last_processed_id, $start_date){
    	$this->initiateModel();

    	if($city_ids != '' ){
    		$city_where_clause = " tu.city in ($city_ids) and";
    	}

    	$query = "select tu.userId, tLMS.id, tLMS.listing_type_id from tempLMSTable tLMS join tuser tu on tu.userId=tLMS.userId join tuserflag tFlag on tFlag.userId=tu.userId where $city_where_clause   tLMS.listing_type_id in (?) and listing_type='exam' and tFlag.isTestUser='NO' and  tLMS.id<=? and tLMS.submit_date>=? order by tLMS.id desc";
	    	    
	    $result = $this->dbHandle->query($query,array($exam_group_ids, $last_processed_id, $start_date))->result_array();
	    return $result;
    }

    public function markSubscriptionInactive($subscriptionIds){
    	if(!is_array($subscriptionIds) || count($subscriptionIds) <1){
    		return;
    	}

    	$updateData = array(
			'status' 			=> 'inactive',
			'statusUpdatedAt' 	=> date('Y-m-d H:i:s')
		);
    	$this->initiateModel('write');
    	$this->dbHandle->where_in('id',$subscriptionIds);
    	$this->dbHandle->update('examResponseSubscription',$updateData);
		
    }

    public function updateTempIdInExamResponseAllocation($examGroupId,$userId,$lastResponseId){
  
  		if(empty($examGroupId) || empty($userId) || empty($lastResponseId)){
  			return false;
  		}

		$this->initiateModel('write');
		$query = "SELECT count(*) as responseCount FROM examResponseAllocation WHERE entityType = 'examGroup' and userId = ?  and entityValue = ?";
		$result = $this->dbHandle->query($query,array($userId,$examGroupId))->row();
		
		if($result->responseCount > 0){
			$query = "UPDATE examResponseAllocation SET tempLmsId = ?  WHERE entityType = 'examGroup' and userId = ?  and entityValue = ?";	
			$this->dbHandle->query($query,array($lastResponseId,$userId,$examGroupId));	
		}
		
    }

    public function getUserAllocationData($userIds){
    	if(!is_array($userIds) || count($userIds) <1){
			return false;
		}

		$this->initiateModel('write');
		$this->dbHandle->select('subscriptionId, userId,entityValue');
		$this->dbHandle->from('examResponseAllocation');
		$this->dbHandle->where_in('userId',$userIds);
		$this->dbHandle->where('entityType', 'examGroup');
		$result = $this->dbHandle->get()->result_array();
		return $result;
    }

    public function getSubscription(){
    	$this->initiateModel('write');
		$this->dbHandle->select('id, campaignType, endDate, quantityExpected, quantityDelivered');
		$this->dbHandle->from('examResponseSubscription');
		$this->dbHandle->where('status','active');
		$this->dbHandle->where('isProcessed', 'YES');
		$result = $this->dbHandle->get()->result_array();
		return $result;
    }

    public function getExamResponseToDeliver($subscription_id){
    	if($subscription_id<1){
    		return;
    	}

    	$this->initiateModel();

    	$this->dbHandle->select('tLMS.userId, submit_date, entityValue, eResAllctn.id');
    	$this->dbHandle->from('examResponseAllocation eResAllctn');
    	$this->dbHandle->join('tempLMSTable tLMS', 'eResAllctn.tempLmsId = tLMS.id', 'inner');
    	$this->dbHandle->where('mailSent','NO');
    	$this->dbHandle->where('subscriptionId',$subscription_id);
    	$this->dbHandle->order_by('eResAllctn.id','asc');
    	$result = $this->dbHandle->get()->result_array();
    	
	    return  $result;
    }

    public function getDistinctSubscriptionsForDelivery(){
    	$this->initiateModel();

    	$this->dbHandle->distinct();
    	$this->dbHandle->select('subscriptionId');
    	$this->dbHandle->from('examResponseAllocation ');
    	$this->dbHandle->where('mailSent','NO');
	    $result = $this->dbHandle->get()->result_array();

	    return  $result;
    }

    public function markResponsesProcessed($subscription_id, $last_processed_id, $min_processed_id){
    	$this->initiateModel('write');

    	$update_fields = array('mailSent'=>'YES');

    	$this->dbHandle->where('subscriptionId',$subscription_id);
    	$this->dbHandle->where('id <=',$last_processed_id);
    	$this->dbHandle->where('id >=',$min_processed_id);
    	$this->dbHandle->where('mailSent','NO');
    	$this->dbHandle->update('examResponseAllocation', $update_fields);
	    
    }

    function storeResponseInterestData($data){
		
		if(is_array($data) && $data['user_id'] > 0 && $data['listing_id'] > 0){

			if($data['listing_type'] ==''){
				$data['listing_type'] = 'course';
			}

			$this->initiateModel('write');
			$sql = "insert into temp_response (`user_id`, `listing_id`, `listing_type`, `preferred_city_id`, `preferred_locality_id`, `visitor_session_id`, `tracking_key`,`action_type`,  `reg_form_id`, `submit_time`) values (?,?,?,?,?,?,?,?,?,?)";

			$this->dbHandle->query($sql,array($data['user_id'],$data['listing_id'],$data['listing_type'],$data['preferred_city_id'],$data['preferred_locality_id'],$data['visitor_session_id'],$data['tracking_key'],$data['action_type'],$data['reg_form_id'], $data['submit_time']));

			$last_insert_id = $this->dbHandle->insert_id();

			return $last_insert_id;
		}
	}

    public function updateDataForResponseElasticMap($queueData,$id,$columnName){
    	$this->initiateModel('write');
    	$this->dbHandle->where('queue_id', $queueData['id']);
    	$this->dbHandle->where('response_from', 'national');
    	$this->dbHandle->update('response_elastic_map', array($columnName => $id));
    }

    public function fetchCourseResponse($queueId){
    	$this->initiateModel('write');

    	$this->dbHandle->select('rMap.queue_id,rMap.temp_lms_request_id, rMap.temp_lms_time, rMap.user_mail_queue_id,rMap.client_mail_queue_id,rMap.client_sms_queue_id,rMap.use_for_response');
    	$this->dbHandle->select('tQueue.action_type as action,tQueue.submit_time as queue_time,tQueue.user_id as userId,tQueue.visitor_session_id as sessionId,tQueue.listing_type, tQueue.listing_id as listing_type_id');
    	// $this->dbHandle->select('urp.user_profile,urp.stream_id,urp.substream_id');
    	$this->dbHandle->select('tLMS.id as temp_lms_id, tLMS.listing_subscription_type,tLMS.submit_date, tLMS.isClientResponse');
    	$this->dbHandle->select('tKey.id,tKey.keyName,tKey.page,tKey.widget,tKey.site, tKey.siteSource,tKey.pageGroup, tKey.siteSourceType');
    	$this->dbHandle->select('st.utm_source,st.utm_medium,st.utm_campaign, st.source');
    	$this->dbHandle->select('instLoc.listing_location_id,instLoc.city_id as response_city, instLoc.locality_id as response_locality, instLoc.listing_id as institute_id');

    	
    	$this->dbHandle->from('response_elastic_map rMap');
    	$this->dbHandle->join('temp_response_queue tQueue', 'rMap.queue_id = tQueue.id');
    	// $this->dbHandle->join('user_response_profile urp', 'urp.queue_id = tQueue.id','left');
    	$this->dbHandle->join('tempLMSTable tLMS', 'rMap.temp_lms_id = tLMS.id','left');
    	$this->dbHandle->join('tracking_pagekey tKey', 'tKey.id = tQueue.tracking_key','left');
    	$this->dbHandle->join('session_tracking st', 'st.sessionId = tQueue.visitor_session_id','left');
    	$this->dbHandle->join('responseLocationTable rLoc', 'rLoc.responseId = tLMS.id','left');
    	$this->dbHandle->join('shiksha_institutes_locations instLoc', 'instLoc.listing_location_id = rLoc.instituteLocationId and instLoc.status in ("live","deleted")','left');
    	$this->dbHandle->where('rMap.queue_id',$queueId);    	
	    $result = $this->dbHandle->get()->row_array();

	    return  $result;
    }

    public function fetchExamResponse($queueId){
    	$this->initiateModel();
    	$this->dbHandle->select('rMap.queue_id,rMap.temp_lms_request_id, rMap.temp_lms_time, rMap.user_mail_queue_id,rMap.client_mail_queue_id,rMap.client_sms_queue_id,rMap.use_for_response');
    	$this->dbHandle->select('tQueue.action_type as action,tQueue.submit_time as queue_time,tQueue.user_id as userId,tQueue.visitor_session_id as sessionId,tQueue.listing_type, tQueue.listing_id as listing_type_id');
    	$this->dbHandle->select('tLMS.id as temp_lms_id,  tLMS.listing_subscription_type,tLMS.submit_date');
    	$this->dbHandle->select('tKey.id,tKey.keyName,tKey.page,tKey.widget,tKey.site, tKey.siteSource,tKey.pageGroup, tKey.siteSourceType');
    	$this->dbHandle->select('st.utm_source,st.utm_medium,st.utm_campaign, st.source');
    	$this->dbHandle->select('exmGrp.examId,exmGrp.groupName');
    	$this->dbHandle->select('exmMain.name as examName');

    	$this->dbHandle->from('response_elastic_map rMap');
    	$this->dbHandle->join('temp_response_queue tQueue', 'rMap.queue_id = tQueue.id');
    	$this->dbHandle->join('tempLMSTable tLMS', 'rMap.temp_lms_id = tLMS.id','left');
    	$this->dbHandle->join('tracking_pagekey tKey', 'tKey.id = tQueue.tracking_key','left'); 
    	$this->dbHandle->join('session_tracking st', 'st.sessionId = tQueue.visitor_session_id','left');   	
    	$this->dbHandle->join('exampage_groups exmGrp', 'exmGrp.groupId = tQueue.listing_id','left');    	
    	$this->dbHandle->join('exampage_main exmMain', 'exmMain.id = exmGrp.examId');    	
    	$this->dbHandle->where('queue_id',$queueId);    	
    	$this->dbHandle->where('exmGrp.status','live');    	
    	$this->dbHandle->where('exmMain.status','live');    	
    	$this->dbHandle->where('response_from','national');
	    $result = $this->dbHandle->get()->row_array();

	    return  $result;
    }

    public function fetchSAResponse($queueId){
    	$this->initiateModel('write');
    	$this->dbHandle->select('rMap.queue_id, rMap.temp_lms_time, rMap.user_mail_queue_id,rMap.client_mail_queue_id,rMap.client_sms_queue_id,rMap.use_for_response');
    	$this->dbHandle->select('tQueue.actionType as action,tQueue.creationDate as queue_time,tQueue.userId as userId,tQueue.visitorSessionId as sessionId,tQueue.listingType as listing_type, tQueue.listingTypeId as listing_type_id');
    	$this->dbHandle->select('tLMS.id as temp_lms_id, tLMS.listing_subscription_type,tLMS.submit_date');
    	$this->dbHandle->select('tKey.id,tKey.keyName,tKey.page,tKey.widget,tKey.site, tKey.siteSource,tKey.pageGroup, tKey.siteSourceType');
    	$this->dbHandle->select('st.utm_source,st.utm_medium,st.utm_campaign, st.source');
    	$this->dbHandle->select('abroadLoc.institute_location_id as listing_location_id,abroadLoc.city_id as response_city, abroadLoc.institute_id as institute_id');

    	$this->dbHandle->from('response_elastic_map rMap');
    	$this->dbHandle->join('responsesMessageQueue tQueue', 'rMap.queue_id = tQueue.id');    
    	$this->dbHandle->join('tempLMSTable tLMS', 'rMap.temp_lms_id = tLMS.id','left');
    	$this->dbHandle->join('tracking_pagekey tKey', 'tKey.id = tQueue.MISTrackingId','left');
    	$this->dbHandle->join('session_tracking st', 'st.sessionId = tQueue.visitorSessionId','left');
    	$this->dbHandle->join('responseLocationTable rLoc', 'rLoc.responseId = tLMS.id','left');    	
    	$this->dbHandle->join('institute_location_table abroadLoc','abroadLoc.institute_location_id = rLoc.instituteLocationId and abroadLoc.status in ("live","deleted")','left');
    	$this->dbHandle->where('queue_id',$queueId);
    	$this->dbHandle->where('response_from','abroad');
	    $result = $this->dbHandle->get()->row_array();

	    return  $result;
    }

    public function getTMailQueueInfo($mailQueueId){
    	$this->initiateModel();
    	$this->dbHandle->select('createdTime');
    	$this->dbHandle->from('tMailQueue');
    	$this->dbHandle->where('id',$mailQueueId);    	
    	$result = $this->dbHandle->get()->row_array();
    	return $result;
    }

    public function getSMSQueueInfo($smsQueueId){
    	$this->initiateModel();
    	$this->dbHandle->select('createdDate');
    	$this->dbHandle->from('smsQueue');
    	$this->dbHandle->where('id',$smsQueueId);    	
    	$result = $this->dbHandle->get()->row_array();
    	return $result;
    }
   
	public function getQueueIdByTempLMSId($tempLMSId,$excludeQueueId = '',$site){
		$this->initiateModel();
    	$this->dbHandle->select('queue_id');
    	$this->dbHandle->from('response_elastic_map');
    	$this->dbHandle->where('temp_lms_id',$tempLMSId);    	
    	$this->dbHandle->where('use_for_response','y');    	
    	$this->dbHandle->where('response_from',$site);    	
    	if($excludeQueueId){
    		$this->dbHandle->where('queue_id !=',$excludeQueueId);    	
    	}
    	$result = $this->dbHandle->get()->row_array();
    	return $result;
	}

	public function updateResponseStatus($queueId,$site,$responseStatus){
		$this->initiateModel('write');
		$this->dbHandle->where('queue_id', $queueId);
		$this->dbHandle->where('response_from', $site);
    	$this->dbHandle->update('response_elastic_map', array('use_for_response' => $responseStatus));    
    	return true;
	}

	public function getAllCourseResponses($userId,$tempLMSId){
		/*$this->initiateModel('read','MISTracking');
 
    	$this->dbHandle->select("'y' as use_for_response",FALSE);
    	$this->dbHandle->select('tLMS.id as temp_lms_id,tLMS.listing_type, tLMS.listing_type_id, tLMS.action, tLMS.listing_subscription_type,tLMS.submit_date,tLMS.userId,tLMS.visitorsessionid as sessionId');
    	$this->dbHandle->select('tKey.id,tKey.keyName,tKey.page,tKey.widget,tKey.site, tKey.siteSource');
    	$this->dbHandle->select('instLoc.listing_location_id,instLoc.city_id as response_city, instLoc.locality_id as response_locality, instLoc.listing_id as institute_id');
    	$this->dbHandle->select('st.utm_source,st.utm_medium,st.utm_campaign');
    	$this->dbHandle->select('abroadLoc.institute_location_id as abroadListingLocationId,abroadLoc.city_id as abroad_response_city, abroadLoc.locality_id as abroad_response_locality, abroadLoc.institute_id as abroad_institute_id');
    	
    	$this->dbHandle->from('tempLMSTable tLMS');
    	$this->dbHandle->join('tracking_pagekey tKey', 'tKey.id = tLMS.tracking_keyid','left');
    	$this->dbHandle->join('session_tracking st', 'st.sessionId = tLMS.visitorsessionid','left');
    	$this->dbHandle->join('responseLocationTable rLoc', 'rLoc.responseId = tLMS.id','left');
    	$this->dbHandle->join('shiksha_institutes_locations instLoc', 'instLoc.listing_location_id = rLoc.instituteLocationId and instLoc.status in ("live","deleted")','left');
    	$this->dbHandle->join('institute_location_table abroadLoc','abroadLoc.institute_location_id = rLoc.instituteLocationId and abroadLoc.status in ("live","deleted")','left');
    	
    	$this->dbHandle->where('tLMS.listing_type', 'course');
    	$this->dbHandle->where('tLMS.userId ', $userId);
    	$this->dbHandle->where('tLMS.id <', $tempLMSId);*/

    	$this->initiateModel('read','MISTracking');
 
    	$this->dbHandle->select("'y' as use_for_response",FALSE);
    	$this->dbHandle->select('tLMS.id as temp_lms_id,tLMS.listing_type, tLMS.listing_type_id, tLMS.action, tLMS.listing_subscription_type,tLMS.submit_date,tLMS.userId,tLMS.visitorsessionid as sessionId, tLMS.tracking_keyid');
    	$this->dbHandle->select('rLoc.instituteLocationId');
    	$this->dbHandle->from('tempLMSTable tLMS');
    	$this->dbHandle->join('responseLocationTable rLoc', 'rLoc.responseId = tLMS.id','left');
    	$this->dbHandle->where('tLMS.listing_type', 'course');
    	$this->dbHandle->where('tLMS.userId ', $userId);
    	$this->dbHandle->where('tLMS.id <', $tempLMSId);


	    $result = $this->dbHandle->get()->result_array();

	    return  $result;
	}	

	public function distintResponseUsers($start){
		$this->initiateModel('read','MISTracking');
		$query = "select distinct userId from tempLMSTable where listing_type = 'course' limit ".$start.", 50000";
		$result = $this->dbHandle->query($query)->result_array();
	    return  $result;
	}

	public function getCourseClientId($courseIds,$status){
		$this->initiateModel();
		$this->dbHandle->select('lm.listing_type_id,lm.username');
		$this->dbHandle->from('listings_main lm');
		$this->dbHandle->where_in('lm.listing_type_id', $courseIds); 		
		$this->dbHandle->where('lm.listing_type', 'course'); 		
		$this->dbHandle->where_in('lm.status', $status); 		
		$result = $this->dbHandle->get()->result_array();		                                

		$listingData = array();
		foreach ($result as $key => $value) {
			$listingData[$value['listing_type_id']]['username'] = $value;
		}
		unset($result);		
		return $listingData;
	}


	public function getResponseUserProfileCall($data){
		$this->initiateModel('read','MISTracking');

		//get response profile 
		$this->dbHandle->select('urp.stream_id,urp.substream_id,urp.user_profile, urp.listing_id');
		$this->dbHandle->from('user_response_profile urp');		
		$this->dbHandle->where('urp.user_id', $data['user_id']); 		
		$this->dbHandle->where_in('urp.listing_id', $data['listingid']); 		 		
		
		$result = $this->dbHandle->get()->result_array();		                                		
		return $result;		
	}


	public function getResponseUserProfile($data){
		$this->initiateModel('read','MISTracking');

		//get response profile 
		$this->dbHandle->select('urp.stream_id,urp.substream_id,urp.user_profile');
		$this->dbHandle->from('user_response_profile urp');		
		$this->dbHandle->where('urp.user_id', $data['user_id']); 		
		$this->dbHandle->where('urp.listing_id', $data['listing_type_id']); 		
		$this->dbHandle->where('urp.listing_type', $data['listing_type']); 		
		if($data['submit_date']){
			$this->dbHandle->where('urp.submit_date', $data['submit_date']); 					
		}
		$result = $this->dbHandle->get()->result_array();		                                		
		
		return $result;		
	}

	public function getResponseUserProfileByQueueId($queueId){
		$this->initiateModel();
		//get response profile 
		$this->dbHandle->select('urp.stream_id,urp.substream_id,urp.user_profile');
		$this->dbHandle->from('user_response_profile urp');		
		$this->dbHandle->where('urp.queue_id', $queueId); 		
		$result = $this->dbHandle->get()->result_array();

		return $result;
	}

	public function totalExamResponses($tempLmsId){
		$this->initiateModel('read','MISTracking');
		$sql = 'SELECT COUNT(*) AS count FROM tempLMSTable lms JOIN exampage_groups epg on lms.listing_type_id = epg.groupId WHERE epg.status in ("live","deleted") and lms.listing_type = "exam" AND lms.userId > 0 AND lms.id < '.$tempLmsId;
		$totalCount = $this->dbHandle->query($sql)->result_array();
		$totalCount = $totalCount[0]['count'];
		return $totalCount;
	}

	public function getExamResponses($start,$batch,$tempLmsId){
		$this->initiateModel('read','MISTracking');
		$this->dbHandle->select("'y' as use_for_response",FALSE);
    	$this->dbHandle->select('tLMS.id as temp_lms_id,tLMS.listing_type, tLMS.listing_type_id, tLMS.action, tLMS.listing_subscription_type,tLMS.submit_date,tLMS.userId,tLMS.visitorsessionid as sessionId');
    	$this->dbHandle->select('tKey.id,tKey.keyName,tKey.page,tKey.widget,tKey.site, tKey.siteSource');
    	$this->dbHandle->select('st.utm_source,st.utm_medium,st.utm_campaign');
    	$this->dbHandle->select('exmGrp.examId,exmGrp.groupName');
    	$this->dbHandle->select('exmMain.name as examName');

    	$this->dbHandle->from('tempLMSTable tLMS');
    	$this->dbHandle->join('tracking_pagekey tKey', 'tKey.id = tLMS.tracking_keyid','left');
    	$this->dbHandle->join('session_tracking st', 'st.sessionId = tLMS.visitorsessionid','left');   	
    	$this->dbHandle->join('exampage_groups exmGrp', 'exmGrp.groupId = tLMS.listing_type_id');
    	$this->dbHandle->join('exampage_main exmMain', 'exmMain.id = exmGrp.examId');     	
    	$this->dbHandle->where('tLMS.listing_type', 'exam');
    	$this->dbHandle->where_in('exmGrp.status',array('live','deleted'));    	
    	$this->dbHandle->where_in('exmMain.status',array('live','deleted'));    	    	
    	$this->dbHandle->where('tLMS.id < ',$tempLmsId);    	    	
    	$this->dbHandle->limit($batch,$start);    	
	    $result = $this->dbHandle->get()->result_array();

	    return  $result;
	}

	public function totalScholarshipResponses($tempLMSId){
		$this->initiateModel('read','MISTracking');
		$sql = "SELECT COUNT(*) AS count FROM tempLMSTable lms  WHERE  lms.listing_type = 'scholarship' AND lms.userId > 0 AND lms.id < $tempLMSId";
		$totalCount = $this->dbHandle->query($sql)->result_array();
		$totalCount = $totalCount[0]['count'];
		return $totalCount;
	}

	public function getScholarshipResponses($start,$batch,$tempLMSId){
		$this->initiateModel('read','MISTracking');
 
    	$this->dbHandle->select("'y' as use_for_response",FALSE);
    	$this->dbHandle->select('tLMS.id as temp_lms_id,tLMS.listing_type, tLMS.listing_type_id, tLMS.action, tLMS.listing_subscription_type,tLMS.submit_date,tLMS.userId,tLMS.visitorsessionid as sessionId');
    	$this->dbHandle->select('tKey.id,tKey.keyName,tKey.page,tKey.widget,tKey.site, tKey.siteSource');
    	$this->dbHandle->select('st.utm_source,st.utm_medium,st.utm_campaign');
    	
    	
    	$this->dbHandle->from('tempLMSTable tLMS');
    	$this->dbHandle->join('tracking_pagekey tKey', 'tKey.id = tLMS.tracking_keyid','left');
    	$this->dbHandle->join('session_tracking st', 'st.sessionId = tLMS.visitorsessionid','left');    	
    	
    	$this->dbHandle->where('tLMS.listing_type', 'scholarship');    	    	
    	$this->dbHandle->where('tLMS.id <', $tempLMSId); 
    	$this->dbHandle->limit($batch,$start);   	
	    $result = $this->dbHandle->get()->result_array();

	    return  $result;
	}

	public function getResponseExportPref($listingType,$listingLocationIds,$orCond = ''){
		$this->initiateModel('read');
		$this->dbHandle->select("listingId,listingLocationId,emails");
		$this->dbHandle->from('responseExportPref ref');
		if($listingLocationIds)
			$this->dbHandle->where_in('ref.listingLocationId', $listingLocationIds);

		if($listingType)
			$this->dbHandle->where_in('ref.listingType', $listingType);

		if($orCond)
			$this->dbHandle->where($orCond);			

		$result = $this->dbHandle->get()->result_array();
		foreach($result as $row) {
			$emailExportMap[$row['listingId'].'_'.$row['listingLocationId']] = $row['emails'];
		}

	    return  $emailExportMap;
	}


	function getAllTrackingIdData(){
		$this->initiateModel('read','MISTracking');		
		$sql = "select tKey.id,tKey.keyName,tKey.page,tKey.widget,tKey.site, tKey.siteSource from tracking_pagekey tKey";
		return $this->dbHandle->query($sql)->result_array();
	}

	function getNationalInstiLocation(){
		$this->initiateModel('read','MISTracking');
		$sql = "select instLoc.listing_location_id, instLoc.city_id as response_city, instLoc.locality_id as response_locality, instLoc.listing_id as institute_id from shiksha_institutes_locations instLoc where status in ('live','deleted')";

		return $this->dbHandle->query($sql)->result_array();
	}

	function getAbroadInstiLocation(){
		$this->initiateModel('read','MISTracking');
		$sql ="select abroadLoc.institute_location_id as abroadListingLocationId,abroadLoc.city_id as abroad_response_city, abroadLoc.institute_id as abroad_institute_id from institute_location_table abroadLoc where status in ('live','deleted')";

		return $this->dbHandle->query($sql)->result_array();

	}

	function getAllLiveOAF(){
		$todayDate  = date('Y-m-d');

		$this->initiateModel('read');
		$sql ="select courseId,otherCourses, externalURL from OF_InstituteDetails where status='live' and last_date>=?";

		return $this->dbHandle->query($sql,array($todayDate))->result_array();
	}
	
	/**
	* Function to add data in response porting queue
	*/	
	function addToResponsePortingQueue($responseId, $userId, $listingId, $listingType) {

		if($responseId <= 0 || $userId <= 0 || $listingId <= 0) {
			return;
		}

		$this->initiateModel('write');
		$sql = "insert ignore into client_data_queue (responseId, userId, listingId, listingType) values (?,?,?,?)";
		$this->dbHandle->query($sql,array($responseId, $userId, $listingId, $listingType));

	}

	/**
	* Function to add data in response porting queue
	*/	
	function getResponsePortingQueueData($noOfTries, $lastUpdatedTime, $recordsCount, $listingType) {

		if($noOfTries <= 0 || $lastUpdatedTime == '' || $recordsCount <=0) {
			return;
		}

		$this->initiateModel();
		$sql = "select id, responseId from client_data_queue where ((status = ?) OR (status = ? and noOfTries < ? and updatedOn < ?)) and listingType = ? limit ".$recordsCount;
		$results = $this->dbHandle->query($sql,array('queued', 'failed', $noOfTries, $lastUpdatedTime, $listingType))->result_array();

		return $results;

	}

	/**
	 * Function to store data in client_data_log
	 * @param : last_queue_id and dataToBePorted
	 * @return : $last_insert_id
	*/
	function storeResponsePortingData($last_queue_id, $dataToBePorted) {

		if($last_queue_id <= 0 || empty($dataToBePorted)) { 
			return;
		}

		$this->initiateModel('write');
		$sql = "insert into client_data_log (`response_queue_id`, `sent_data`) values (?,?)";
		$this->dbHandle->query($sql,array($last_queue_id, $dataToBePorted));
		
		$last_insert_id = $this->dbHandle->insert_id();

		return $last_insert_id;

	}

	/**
	 * Function to update status for sent status
	 * @param : $status, $logId
	 * @return : true
	*/
	function updateSentDataStatus($status, $logId) {

		if(empty($status) || $logId <= 0) { 
			return;
		}

		$this->initiateModel('write');
		$updatedOn = date("Y-m-d H:i:s");
		$sql = "update client_data_log set sent_status = ?, updatedOn = ? where id = ?";
		$this->dbHandle->query($sql,array($status, $updatedOn, $logId));

	}

	/**
	 * Function to update status for sent status
	 * @param : $status, $logId
	 * @return : true
	*/
	function updateTriesCounts($logId) {

		if($logId <= 0) { 
			return;
		}

		$this->initiateModel('write');
		$updatedOn = date("Y-m-d H:i:s");
		$sql = "update client_data_queue set status= ?, noOfTries=noOfTries+1, updatedOn = ? where id = ?";
		$this->dbHandle->query($sql,array('inprocess', $updatedOn, $logId));

	}

	/**
	 * Function to update status for sent status
	 * @param : $status, $logId
	 * @return : true
	*/
	function updateQueueStatus($status, $id) {

		if(empty($status) || $id <= 0) { 
			return;
		}

		$this->initiateModel('write');
		$updatedOn = date("Y-m-d H:i:s");
		$sql = "update client_data_queue set status = ?, updatedOn = ? where id = ?";
		$this->dbHandle->query($sql,array($status, $updatedOn, $id));

	}

	/**
	 * Function to update data in client_data_log
	 * @param : $reason, $responseTime, $logId
	 * @return : true
	*/
	function updateLogResponse($status, $responseTime, $logId, $reason = '') {

		if($logId <= 0 || empty($responseTime) || empty($status)) { 
			return;
		}

		$this->initiateModel('write');
		$sql = "update client_data_log set response_status = ?, response = ?, response_time = ? where id = ?";
		$this->dbHandle->query($sql,array($status, $reason, $responseTime, $logId));

	}

	/**
	 * Function to get state and city mapping of client
	 * @param : city id array and state id array
	 * @return : mapping of array type
	*/
	function getVendorMapping($vendor, $cityIds = array(), $stateIds = array()) {

		if(empty($vendor) || (empty($cityIds) && empty($stateIds))) {
			return;
		}

		$params = array($vendor); $queryCondition = array();
		
		if(!empty($cityIds)) {
			$queryCondition[] = '(entity_type = ? and shiksha_entity in (?))';
			$params[] = 'city';
			$params[] = $cityIds;
		}
		if(!empty($stateIds)) {
			$queryCondition[] = '(entity_type = ? and shiksha_entity in (?))';
			$params[] = 'state';
			$params[] = $stateIds;
		}

		$this->initiateModel();
	    $query = "SELECT * from shiksha_vendor_mapping where vendor_name = ? and (".implode(' OR ', $queryCondition).")";
	    $queryres = $this->dbHandle->query($query,$params);	   
	    $result  = $queryres->result_array();

	    return $result;
	}

	public function getResponsesData($responseIds){

    	if(empty($responseIds)){
    		return;
    	}

    	$this->initiateModel();
    	$query = "select id, userId as user_id, listing_type_id as listing_id, listing_type from tempLMSTable where id in (?)";	    	    
	    $results = $this->dbHandle->query($query,array($responseIds))->result_array();
	    
	    return $results;    			
    }

    public function getQueueDataByLogId($logId){

    	if($logId <= 0) {
    		return;
    	}

    	$this->initiateModel();
    	$query = "select response_queue_id from client_data_log where id = ?";
	    $result = $this->dbHandle->query($query,array($logId))->row_array();
	    
	    return $result;    			
    }

    public function getExistingRecordByUserIdListingId($userId, $listingId, $listingType) {

    	if($userId <= 0 || $listingId <= 0) {
    		return;
    	}

    	$this->initiateModel();
    	$query = "select id from client_data_queue where userId = ? and listingId = ? and listingType = ?";
	    $result = $this->dbHandle->query($query,array($userId, $listingId, $listingType))->row_array();
	    
	    return $result;    			
    }


	function getAllResponseDeliveryCriteria(){

		$this->initiateModel('read');
		$sql ="select client_id, entity_type, entity_id, action_type, action_value, location_ids from response_delivery_criteria where status='live'";

		return $this->dbHandle->query($sql)->result_array();	
	}

	public function getUnsubscribedCategoryFiveOfUser($userid,$unsubscribeCategory){
		if(empty($userid) || empty($unsubscribeCategory)){
			return;
		}

		$this->initiateModel('read');
		$sql ="select unsubscribe_category from user_unsubscribe_mapping where user_id = ? and unsubscribe_category in (?) and status='live'";

		return $this->dbHandle->query($sql,array($userid,$unsubscribeCategory))->result_array();

	}
}
?>
