<?php
class myshortlistmodel extends MY_Model {
    private $dbHandle     = '';
    private $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;
		
		$this->dbHandleMode = $mode;
		$this->dbHandle     = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }
	
    /**
    * Purpose  : Method to get courses which have been shortlisted by a given user
    * Params   : 1. User-id - Integer
    * Author   : Romil Goel
    */
    public function getUserShortListedCourses($userId)
    {
        $this->initiateModel('read');
        
        $query = "SELECT distinct(courseId)
		  FROM userShortlistedCourses
		  WHERE userId = ?
                  AND status = 'live'
                  AND scope = 'national' 
                  ORDER BY shortListTime DESC";
		    
		$rs = $this->dbHandle->query($query, $userId)->result_array();
	    // fetch all data from result set and prepare another array
	    $data = array();
		foreach($rs as $key=>$value){
		    $data[] 	= $value['courseId'];
		}
		return $data;
    }
    
    /**
    * Purpose  : Method to get courses from responses made by a user 
    * Params   : 1. userid
    * Author   : Praveen
    */
    function getCoursesOfResponses($userId, $excludeResponseActions = array()){
		$this->initiateModel('read');
		$this->dbHandle->select('distinct(tlt.listing_type_id) as listing_type_id');
		$this->dbHandle->from('tempLMSTable tlt');
		$this->dbHandle->where('tlt.listing_type','course');
		$this->dbHandle->where('tlt.userId',$userId);
		$this->dbHandle->order_by('tlt.submit_date desc');
		$this->dbHandle->limit('40');
		if(is_array($excludeResponseActions) && count($excludeResponseActions) > 0){
			$this->dbHandle->where_not_in('tlt.action',$excludeResponseActions);
		}
		$result = $this->dbHandle->get()->result_array();
		
        // fetch all data from result set and prepare another array
        $data = array();
        foreach ($result as $key => $value) {
        	$data[] 	= $value['listing_type_id'];
        }
		return $data;
    }
    
    /**
    * Purpose       : Method to get courses viewed in the given session-id
    * Params        : 1. Session-id - String
    * Author        : Romil Goel
    */
    function getCoursesViewedInSession($sessionId)
    {
		$this->initiateModel('read');

		$query  = " SELECT
			    distinct(course_id)
			    FROM 
			    user_session_info usi
			    INNER JOIN
			    listing_track lt
			    ON(usi.id = lt.user_session_id)
			    WHERE usi.session_id  = ?
			    AND lt.is_institute = 0";

		$rs = $this->dbHandle->query($query, $sessionId)->result_array();
	        // fetch all data from result set and prepare another array
	        $data = array();
		foreach($rs as $key=>$value)
		{
		    if($value['course_id'])
			$data[] 	= $value['course_id'];
		}
	        
		return $data;
    }

    /**
    * Purpose       : Method to get courses of a given subcategory of provided institute
    * Params        : 1. Institute id - Integer
    * 		      2. Subcategory-id - Integer
    * Author        : Romil Goel
    */
    function getCoursesOfInstitute($instituteId, $subcategory)
    {
	$this->initiateModel('read');

	$query  = " SELECT course_id
		    FROM categoryPageData WHERE 1
		    and institute_id = ?
		    and category_id = ?
		    and status = 'live' ";

	$rs = $this->dbHandle->query($query, array($instituteId, $subcategory))->result_array();
	
        // fetch all data from result set and prepare another array
        $data = array();
	foreach($rs as $key=>$value)
	{
	    if($value['course_id'])
		$data[] 	= $value['course_id'];
	}
        
	return $data;
    }

    /**
    * Purpose       : Method to get count of shortlists happened for courses provided
    * Params        : 1. Course-ids(optional) - Array
    * Author        : Romil Goel
    */
    function getShortlistCountOfCourses($courseIds = array())
    {
	$this->initiateModel('read');

	// removing this query as this is being handled by fallback logic
	return array();

//	$query = "SELECT courseId, count(distinct userId) as num FROM `userShortlistedCourses` WHERE scope = 'national' AND (pageType like 'ND_%' OR pageType like 'NM_%') ";

	$query = "SELECT column_type FROM information_schema.columns WHERE  table_schema = 'shiksha' AND table_name = 'userShortlistedCourses' AND column_name = 'pageType'";

	$enum_array = $this->dbHandle->query($query)->result_array();

	$regex = "/'(.*?)'/";
	preg_match_all( $regex , $enum_array[0]['column_type'], $enum_fields );
	$enum_fields = $enum_fields[1];
	$fl_array = preg_grep("/(NM_|ND_)\w+/", $enum_fields);


	$query = "SELECT courseId, count(distinct userId) as num FROM `userShortlistedCourses` WHERE scope = 'national' AND pageType IN (?) ";
	
	if(!empty($courseIds))
	    $query .= " AND courseId IN (?) ";

	// group by
	$query .= " group by courseId order by num";

	if(!empty($courseIds)){
		$rs = $this->dbHandle->query($query, array($fl_array,$courseIds))->result_array();
	}
	else{
		$rs = $this->dbHandle->query($query,array($fl_array))->result_array();
	}
	
        // fetch all data from result set and prepare another array
    $data = array();
	foreach($rs as $key=>$value)
	{
	    if($value['courseId'])
		$data[$value['courseId']] = $value['num'];
	}
        
	return $data;
    }
 
    /**
    * Purpose       : To fetch shortlisted course count for a user 
    * Params        : 1. User Id -- int
    * Author        : Vinay
    */
   
    public function getShortListedCourseCount($userId)
    {
    	$this->initiateModel('read');
    
    	$query = "SELECT count( DISTINCT (
		courseId
		) ) as courseCount
		FROM userShortlistedCourses
		WHERE userId = ?
		AND status = 'live'
		AND scope = 'national'";
    	
    	$count = $this->dbHandle->query($query, $userId)->row_array();
            	
    	return $count['courseCount'];
    }
    
    function findCoursesWithOnlineForm($courseIds) {
        $this->initiateModel('read');
        $sql =  "select courseId "
                . "from OF_InstituteDetails "
                . "where status='live' and last_date >= DATE(now()) and courseId in (?) AND courseId NOT IN (164788,130026,234231);";
        $data = $this->dbHandle->query($sql, array($courseIds))->result_array();
        $coursesWithOnlineForm = array();
        foreach($data as $val) {
            $coursesWithOnlineForm[$val['courseId']] = 1;
        }
        return $coursesWithOnlineForm;
    }
    
	function addMissingField($params) {
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		foreach($params['emptyFields'] as $key => $field) {
			if($key == 0) {
				$data = array(
					'courseId' 			=> $params['courseId'],
					'fieldName' 		=> $field,
					'isNonEmpty'		=> 0,
					'status' 			=> 'live'
			    );
			} else {
				$values = $values.", (".$params['courseId'].", '".$field."', 0, 'live')";
			}
		}
		$sql = $this->dbHandle->insert_string('shiksha.shortlistMissingFields', $data).$values." ON DUPLICATE KEY UPDATE isNonEmpty = 0, status = 'live', lastUpdated = CURRENT_TIMESTAMP";
		$this->db->query($sql);
		
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
	  	}
		
		return true;
	}
    
    public function checkIfCourseIsShortlistForAUser($userId,$courseId) {
    	$this->initiateModel('read');
    	
    	$query ="SELECT count(id) as count  
    			FROM `userShortlistedCourses` 
    			WHERE `userId` = ? 
    			AND `courseId` = ? 
    			AND `status` = 'live' 
    			AND (`pageType` like 'ND_%' OR pageType like 'NM_%')";
    	$count = $this->dbHandle->query($query, array($userId, $courseId))->row_array();
    	 
    	return $count['count'] > 0 ? 1 : 0;
    }
	
	public function reportIncorrectData($data) {
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		$data = array(
			'userId' 	=> $data['userId'],
			'courseId' 	=> $data['courseId'],
			'text' 		=> $data['text']
		);
		$this->dbHandle->insert('reportIncorrectShortlist', $data);
		
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
		return true;
	}
  
      public function fetchShortlistedCoursesOfAUser($userId) {
    	if($userId < 1) {
    		return array();
    	}
    	$this->initiateModel('read');
    	 
    	$query ="SELECT courseId as courseIds
    	FROM `userShortlistedCourses`
    	WHERE `userId` = ?
    	AND `status` = 'live'
    	AND (`pageType` like 'ND_%' OR pageType like 'NM_%')";
    	$rs = $this->dbHandle->query($query, array($userId))->result_array();
        $data = array();
        foreach($rs as $key=>$value)
        {
        	if($value['courseIds'])
        		$data[] 	= $value['courseIds'];
        }
       
    	return $data;
    }
    /*
     * This function is used in national rankingPages. Please co-ordinate if any changes are made.
     */
    function findCourseIdsWithOnlineForm() {
        $this->initiateModel('read');
        $sql =  "select courseId "
                . "from OF_InstituteDetails "
                . "where status='live' and last_date >= DATE(now()) AND courseId NOT IN (164788,130026,234231);";
        $data = $this->dbHandle->query($sql)->result_array();
        $coursesWithOnlineForm = array();
        foreach($data as $val) {
            $coursesWithOnlineForm[$val['courseId']] = 1;
        }
        return $coursesWithOnlineForm;
    }
    
	public function getAllShortlistedUsersAndCourses() {
    	$this->initiateModel('read');
    	
    	$query = "SELECT DISTINCT usc.userId, usc.courseId, tu.email ".
    			"FROM `userShortlistedCourses` as usc INNER JOIN tuser as tu ON tu.userid = usc.userId INNER JOIN course_details as cd ON cd.course_id = usc.courseId AND cd.status = 'live' ".
    			"WHERE usc.`status` = 'live' AND (usc.`pageType` LIKE 'ND_%' OR usc.`pageType` LIKE 'NM_%') AND usc.userId > 0 ".
				"ORDER BY usc.shortListTime DESC";
    	$result = $this->dbHandle->query($query)->result_array();
    	foreach($result as $row) {
			$formattedResult['userWiseCourseIds'][$row['userId']][] = $row['courseId'];
			$formattedResult['userEmailId'][$row['userId']] = $row['email'];
			$courseIds[] = $row['courseId'];
		}
		$formattedResult['uniqueCourseIds'] = array_unique($courseIds);
    	return $formattedResult;
    }


    /**
     * get notification template
     * @return array       template result set
     */
    public function getNotificationTemplate(){
    	$this->initiateModel('read');

    	$query = "SELECT * FROM myshortlist_notification_template 
    			  WHERE is_processed  = '0' AND status ='live'";
    	
    	$rs = $this->dbHandle->query($query)->result_array();
    	
    	return $rs;
    }

   /**
    * insert mail entry in tmailqueue and myshortlist user mail table
    * @param  array $processedData
    * @return 
    */
   public function processedMyShortlistMailToUser($processedData){

   		if(!empty($processedData) && is_array($processedData)){
   			// myshortlist user mail entry
			$myShortlistMailEntry                 = $processedData['myShortlistMail'];
			$tMailQueueEntry                      = $processedData['tMailQueue'];
			if(!empty($myShortlistMailEntry) && is_array($myShortlistMailEntry) && !empty($tMailQueueEntry) && is_array($tMailQueueEntry))
			{
				$this->db->insert('myshortlist_user_mail', $myShortlistMailEntry); 
				$lastInsertIdMyShortlistUserMail =  $this->db->insert_id();

					// myshortlist user tMailQueue entry
				if($myShortlistMailEntry['is_valid'] == '1'){
					$this->db->insert('tMailQueue', $tMailQueueEntry); 
					$lastInsertIdTMailQueue          =  $this->db->insert_id();
					//update last Inserted of tMail Queue in myshortlistUserMail
					$this->db->where('id', $lastInsertIdMyShortlistUserMail);
			    	$this->db->update('myshortlist_user_mail', array('tMailQueueId' => $lastInsertIdTMailQueue,'is_processed'=>'1'));
		    	}else{
		    		$this->db->where('id', $lastInsertIdMyShortlistUserMail);
		    		$this->db->update('myshortlist_user_mail', array('is_processed'=>'0'));
		    	}
		    	return true;
			}else{
				return false;
			}
   		}	
	}

	/**
	 * insert user notification  entry in myshortlist user notification
	 * @param  array $myShortlistNotification
	 * @return
	 */
	public function processedMyShortlistNotificationToUser($myShortlistNotification){
		if(!empty($myShortlistNotification) && is_array($myShortlistNotification)){
			$this->db->insert('myshortlist_user_notification', $myShortlistNotification);
		}
	}
	
	/**
	 * insert user notification entry in myshortlist user notification mobile
	 * @param  array $myShortlistNotification
	 * @return
	 */
	public function processedMyShortlistMobileNotification($myShortlistNotification){
		if(!empty($myShortlistNotification) && is_array($myShortlistNotification)){
			$this->db->insert('myshortlist_user_notification_mobile', $myShortlistNotification);
		}
	}

	/**
	 * update processed template ids
	 * @param  integer $notificationTemplateId
	 * @return
	 */
	public function updateNotificationTemplate($notificationTemplateId){
		$this->db->where('id', $notificationTemplateId);
		$this->db->update('myshortlist_notification_template', array('is_processed' => '1'));
    }

    /**
     * save my shortlist notification template data
     * @param  array $data 
     * @return 
     */
    public function insertNotificationTemplate($data){
    	if(!empty($data) && is_array($data)){
			$this->db->insert_batch('myshortlist_notification_template', $data);
		}
    }


    function fetchUserNotifications($userId, $type="desktop") {
    	
    	$this->initiateModel('read');

    	if($type == 'mobile')
    		$query ="SELECT munm.*,mnt.link_type FROM myshortlist_user_notification_mobile munm inner join myshortlist_notification_template mnt ON(mnt.status ='live' AND mnt.id= munm.template_id) WHERE munm.user_id = ? AND munm.is_valid = '1' ORDER BY id DESC limit 3";
    	else
    		$query ="SELECT * FROM `myshortlist_user_notification` WHERE user_id = ? ORDER BY id DESC";

    	$rs = $this->dbHandle->query($query, $userId)->result_array();

    	return $rs;   	
    }
    
    
    function setNotificationAsSeen($notificationId) {
  		    $this->initiateModel('write');
			$query = "UPDATE `shiksha`.`myshortlist_user_notification` 
					  SET `is_seen` = '".date_format(new DateTime(), 'Y-m-d H:i:s')."'
					  WHERE `myshortlist_user_notification`.`id` = ?;
 					 ";
			$this->dbHandle->query($query, $notificationId);
    	
    	   }
	
	function getUserCourseNotesData($userId = false, $courseId = false, $page = false, $batchSize = 2) {
		//check for user id and course id
		if(empty($userId) || empty($courseId)) {
			return false;
		}
		
		$offset = $batchSize * $page;
		
		//get data
		$this->initiateModel('write');
		$query = "SELECT SQL_CALC_FOUND_ROWS mn.note_id, title, body, mn.submit_date, mn.last_updated, reminder_date ".
				"FROM `myshortlist_notes` as mn LEFT JOIN myshortlist_notes_reminder as mnr ON mn.note_id = mnr.note_id AND mnr.status IN ('live') ".
				"WHERE mn.user_id = ? AND mn.course_id = ? AND mn.status = 'live' ".
				"ORDER BY mn.last_updated DESC ".
				"LIMIT ? OFFSET ?";
		
		$query = $this->dbHandle->query($query, array($userId, $courseId, $batchSize, $offset));
		$result['notes'] = $query->result_array();
		
		$queryCount = 'SELECT FOUND_ROWS() as totalRows';
        $result['count'] = $this->dbHandle->query($queryCount)->row()->totalRows;
		
		return $result;
	}
	
	function addEditRemoveUserCourseNotes($data) {
		$this->initiateModel('write');
		
		if($data['action'] == 'add') {
			$data['noteId'] = Modules::run('common/IDGenerator/generateId', 'myshortlist_notes');
			
			//set submit date as current date
			$data['submitDate'] = date('Y-m-d H:i:s');
		}
		
		$this->dbHandle->trans_start();
		
		if($data['action'] == 'edit') {
			//make current live entry history
			$this->dbHandle->query("UPDATE myshortlist_notes SET status = 'history' WHERE note_id = ? AND status = 'live'", $data['noteId']);
		}
		
		if($data['action'] == 'remove') {
			//make current live entry deleted
			$this->dbHandle->query("UPDATE myshortlist_notes SET status = 'deleted' WHERE note_id = ? AND status = 'live'", $data['noteId']);
			$this->dbHandle->query("UPDATE myshortlist_notes_reminder SET status = 'deleted' WHERE note_id = ? AND status = 'live'", $data['noteId']);
			
			$this->dbHandle->trans_complete();
			if ($this->dbHandle->trans_status() === FALSE) {
				throw new Exception('Transaction Failed');
			}
			return true;
		}
		if(!empty($data['noteId'])) {
			//add new note
			$insertData = array(
				'note_id'		=> $data['noteId'],
				'user_id' 		=> $data['userId'],
				'course_id' 	=> $data['courseId'],
				'title' 		=> $data['noteTitle'],
				'body' 			=> $data['noteBody'],
				'status'		=> 'live',
				'submit_date' 	=> $data['submitDate'],
				'last_updated' 	=> date('Y-m-d H:i:s')
			);
			$this->dbHandle->insert('myshortlist_notes', $insertData);
		}

		if($data['action'] == 'add' && $data['reminderDate'] !=='Remind me'  && !empty($data['reminderDate']) && $data['reminderDate'] !=='Set Reminder') {
			list($day, $month, $year) = explode('/', $data['reminderDate']);
			$reminder_date  = $year."-".$month."-".$day." 09:00:00";
			$insertData = array(
					'note_id'    => $data['noteId'],
					'reminder_date' => $reminder_date,
					'status'     => 'live',
					'add_date'   => date('Y-m-d H:i:s')
				);
			$queryRes = $this->dbHandle->insert('myshortlist_notes_reminder',$insertData);
		}


		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
		return true;
	}

	function trackMyShortListSearch($data){
    	$this->initiateModel('write');
		
		$insertData = array(
					'userId'           => $data['userId'],
					'coursePicked'     => $data['courseId'],
					'institutePicked'  => $data['instituteId'],
					'textEntered'      => $data['textEntered'],
					'sourceEventType'  => $data['source'],
					'suggestionsCount' => $data['suggestionsCount'],
					'isZeroResult'     => $data['zeroResult']
				);
		
		$queryRes = $this->dbHandle->insert('myshortlistSearchTracking',$insertData);
	}
	
	/*
	 * Picks reminder entries for current day
	 */
	function getAllReminderNotes() {
		$this->initiateModel('read');
		
		$query = "SELECT mn.user_id, mn.course_id, mn.note_id, mn.title, mnr.reminder_date ".
				"FROM myshortlist_notes as mn ".
				"INNER JOIN userShortlistedCourses as usc ON usc.userId = mn.user_id AND usc.courseId = mn.course_id AND usc.scope = 'national' AND usc.status = 'live' ".
				"INNER JOIN myshortlist_notes_reminder as mnr ON mnr.note_id = mn.note_id AND mnr.status = 'live' AND DATE(mnr.reminder_date) = DATE(now()) ".
				"WHERE mn.status = 'live'";
		$reminderNotes = $this->dbHandle->query($query)->result_array();
		
		return $reminderNotes;
	}
	
	/**
	* To fetch note data
	* @author Vinay Airan <vinay.airan@shiksha.com>
	* @date   2015-04-30
	* @return
	*/

	function getNoteData($noteId) {
		$this->initiateModel('read');
		
		$query = "SELECT  title, body,submit_date,course_id,reminder_date ".
				 "FROM `myshortlist_notes` as mn ".
				 "LEFT JOIN myshortlist_notes_reminder as mnr ".
				 "ON mn.note_id = mnr.note_id AND mnr.status IN ('live') ".
				 "WHERE mn.status = 'live' AND mn.note_id = ? "; 		  
		$notesReminder = $this->dbHandle->query($query,$noteId)->result_array();
		return $notesReminder[0];
	}

	/*
	 * Changes status for particular reminders
	 */
	function updateReminderNotesStatus($noteIds) {
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		$noteIdsStr = implode(',', $noteIds);
		$this->dbHandle->query("UPDATE myshortlist_notes_reminder SET status = 'reminded', last_updated = now() WHERE status='live' AND note_id IN (?)", array($noteIds));
		
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
		return true;
	}


	/*
	 * Changes status for particular reminders
	 */
	function editReminderForNote($noteId,$date) {
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		$sql = "UPDATE myshortlist_notes_reminder ".
				"SET status = 'history', ". 
				"last_updated = now() ". 
				"WHERE note_id = ? ".
				" AND status= 'live'" ;

		$this->dbHandle->query($sql,array($noteId));
		list($day, $month, $year) = explode('/', $date);
		$reminder_date  = $year."-".$month."-".$day." 09:00:00";
		$insertData = array(
					'note_id'    => $noteId,
					'reminder_date' => $reminder_date,
					'status'     => 'live',
					'add_date'   => date('Y-m-d H:i:s')
				);
		$queryRes = $this->dbHandle->insert('myshortlist_notes_reminder',$insertData);
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
			return false;
		}
		return true;
	}

  function removeReminderForNote($noteId) {
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		$sql = "UPDATE myshortlist_notes_reminder ".
				"SET status = 'deleted', ". 
				"last_updated = now() ". 
				"WHERE note_id = ?".
				" AND status= 'live'" ;

		$this->dbHandle->query($sql,array($noteId));
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
			return false;
		}
		return true;
	}


	function findCourseWithOnlineFormData($courseId) {
        $this->initiateModel('read');
        $sql =  "select courseId,externalURL,last_date "
                . "from OF_InstituteDetails "
                . "where status='live' and last_date >= DATE(now()) and courseId= ?";

        $data = $this->dbHandle->query($sql,$courseId)->row_array();
        
        return $data;
    }

	function setMobileNotificationAsSeen($notificationId){
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		$sql = "UPDATE myshortlist_user_notification_mobile ".
				"SET is_seen = '".date_format(new DateTime(), 'Y-m-d H:i:s')."' ".
				"WHERE id = ?";

		$this->dbHandle->query($sql, $notificationId);
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
			return false;
		}
		return true;
	}

	/**
    * Purpose  : Method to get courses which have been shortlisted by a given user
    * Author   : Ankit Garg
    */
    public function getAllShortListedCourseIds() {
        $this->initiateModel('read');
        
        $query = "SELECT DISTINCT(courseId) ".
	  			"FROM userShortlistedCourses ".
		  		"WHERE status = 'live' ".
                "AND scope = 'national' ".
				"AND NOT (pageType LIKE 'ND_%' OR pageType LIKE 'NM_%');";
		    
		$data = $this->dbHandle->query($query)->result_array();
		
		$courseIds = array();
        foreach($data as $shortlistedCourse) {
        	$courseIds[] = $shortlistedCourse['courseId'];
        }
		return $courseIds;
    }

    function migrateUserShortlistedCourses($mbaCourseIds) {
		if(empty($mbaCourseIds)){
		   return false;
		}
		$dbHandle = $this->getWriteHandle();

		$sql = "UPDATE userShortlistedCourses ".
				"SET pageType = ".
				"( CASE ".
				"WHEN pageType = 'mobileCategoryPage' 				THEN 	'NM_Category' ".
				"WHEN pageType = 'mobileCourseDetailPage' 			THEN 	'NM_CourseListing' ".
				"WHEN pageType = 'mobileRankingPage' 				THEN 	'NM_Ranking' ".
				"WHEN pageType = 'mobileComparePage' 				THEN 	'NM_Compare' ".
				"WHEN pageType = 'MOB_CareerCompass_Shortlist' 		THEN 	'NM_CareerCompass' ".
				"ELSE pageType ".
				"END ) ".
		 		"WHERE courseId IN (?)";
		$result = $dbHandle->query($sql, array($mbaCourseIds));
		return true;
	}

	function getShortlistedCourse($userId, $scope = 'national'){
		$this->initiateModel('read');
        $query = "SELECT DISTINCT(courseId) FROM userShortlistedCourses where userId = ? AND scope = ? AND status = 'live'";
		return $this->dbHandle->query($query,array($userId, $scope))->result_array();
	}

	function checkIfShortlistCourseMappingExist($oldCourse){
		if(empty($oldCourse) || $oldCourse <=0){
			return false;
		}
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('read');	
		}
		$this->dbHandle->select('id');
		$this->dbHandle->from('userShortlistedCourses');
		$this->dbHandle->where('status','live');
   		$this->dbHandle->where('courseId',$oldCourse);
		$result = $this->dbHandle->get()->result_array();
		if(count($result) > 0){
   			return true;
   		}else{
   			return false;
   		}
	}

	function migrateOrDeleteShortlistMappingForCourse($oldCourse, $newCourse, $dbHandle){
    	if(empty($oldCourse) || $oldCourse <=0){
			return false;
		}

		if(!empty($newCourse)){
			if($newCourse <=0){
			return false;
			}
		}

		if(empty($newCourse)){
			$fieldsTobeUpdated = array('status' => 'deleted');
   			
   		}else{
   			$fieldsTobeUpdated = array('courseId' => $newCourse);
   		}

		$response = true;
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}
   		
   		$this->dbHandle->where('status','live');
   		$this->dbHandle->where('courseId',$oldCourse);
   		$response = $this->dbHandle->update('userShortlistedCourses',$fieldsTobeUpdated);
   		return $response;
    }

	function getQuestionDetailsForShortlist($questionIds=''){
		$this->initiateModel('read');
		if($questionIds == ''){
			return array();
		}
    	 $query = "select mt.msgId,mt.threadId,mt.fromOthers as queryType,mt.msgTxt as title,mt.status,mt.creationDate, mt.msgCount as answers from messageTable mt where mt.msgId in (?)";
		$questionArr = explode(',',$questionIds);
		return $this->dbHandle->query($query, array($questionArr))->result_array();
	}

}
