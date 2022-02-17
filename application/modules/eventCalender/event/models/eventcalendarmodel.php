<?php
class eventcalendarmodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
	parent::__construct();
    }
    
    private function initiateModel($mode = "write"){
        if($this->dbHandle && $this->dbHandleMode == 'write') {
            return;
        }
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
                $this->dbHandle = $this->getReadHandle();
        } else {
                $this->dbHandle = $this->getWriteHandle();
        }
    }
    
    function addUserSubscription($userId, $streamId,$courseId,$educationTypeId, $examArr, $checkForRedundancy=false,$tracking_keyid='', $examGroupIdMap)
    {
        $this->initiateModel();
		if($checkForRedundancy){
	    	$examAlreadySubscribed = $this->getSubscribedGroupsOfUser($userId, $streamId);
		}

		//below line is used for retrieving session id value
		$sessionidTracking=getVisitorSessionId();

        $insertData = array();
        $insertData['userId'] = $userId;
        $insertData['streamId'] = $streamId;
        $insertData['category_name'] = $streamId == 2 ? 'Engineering' : 'MBA';
        $insertData['courseId'] = $courseId;
        $insertData['educationTypeId'] = $educationTypeId;
        $insertData['status'] = 'live';
        $insertData['date_created'] = date('Y-m-d H:i:s');
        $insertData['tracking_keyid']=$tracking_keyid;
        $insertData['visitorsessionid']=$sessionidTracking;
        $temp = array();
        $finalInsertData = array();
		foreach($examArr as $exam_name){
			if(empty($examGroupIdMap[$exam_name])){
				continue;
			}
			if(!in_array($examGroupIdMap[$exam_name], $examAlreadySubscribed)){
				$insertData['exam_name'] = $exam_name;
		    	$insertData['exam_group_id'] = $examGroupIdMap[$exam_name];
		    	$finalInsertData[] = $insertData;
			}
        }
        if(!empty($finalInsertData)){
        	$this->dbHandle->insert_batch('eventCalendar_subscriptions', $finalInsertData);
        }
		if(empty($examArr)){
	    	$temp = $examAlreadySubscribed;
		}else{
	    	$temp = array_diff($examAlreadySubscribed, array_values($examGroupIdMap));
		}
		foreach($temp as $groupId){
	    	$this->dbHandle->update('eventCalendar_subscriptions', array('status'=>'deleted'), array('userId'=>$userId, 'exam_group_id'=>$groupId));
		}
    }

    function getSubscribedGroupsOfUser($userId, $streamId){
    	$this->initiateModel('read');
    	$this->dbHandle->select('exam_group_id');
    	$this->dbHandle->from('eventCalendar_subscriptions');
    	$this->dbHandle->where('userId', $userId);
    	$this->dbHandle->where('status', 'live');
    	if($streamId > 0){
    		$this->dbHandle->where('streamId', $streamId);
    	}
    	$result = $this->dbHandle->get()->result_array();
    	$groupIds = array();
    	foreach ($result as $value) {
    		if(!empty($value['exam_group_id'])){
    			$groupIds[] = $value['exam_group_id'];
    		}
    	}
    	return $groupIds;
    }
    
    function getSubscribedExamsOfUser($userid, $examFilter){
		$this->initiateModel('read');
		$sql = "select exam_name from eventCalendar_subscriptions where userId = ? and streamId =? and courseId = ? and educationTypeId = ? and status = 'live'";
		$arr = $this->dbHandle->query($sql,array($userid, $examFilter['streamId'],$examFilter['courseId'],$examFilter['educationTypeId']))->result_array();
		$exams = array();
		foreach($arr as $exam){
	    	$exams[] = $exam['exam_name'];
		}
		return $exams;
    }
    
    function getUserDetailsToSendNotification()
    {
		$this->initiateModel('read');
        $sql = "SELECT sub.*, ecs.id as subscription_id, ecs.userId, ecs.category_name, ecs.streamId, tu.email, tu.firstname, tu.lastname FROM eventCalendar_subscriptions ecs join (SELECT epd.event_name, em.name as exam_name,epd.start_date as exam_from_date,epd.end_date as exam_to_date, eg.groupId as entityId from exampage_content_dates epd join exampage_master epm on epd.page_id=epm.exampage_id JOIN exampage_groups eg ON (eg.groupId = epm.groupId) JOIN exampage_main em ON em.id = eg.examId WHERE epm.status='live' and epd.status='live' and em.status = 'live' and eg.status = 'live' and epd.start_date = (CURRENT_DATE() + INTERVAL 1 DAY)) sub on ecs.exam_group_id=sub.entityId join tuser tu on ecs.userId=tu.userid where ecs.status = 'live' group by ecs.userId, sub.entityId";
        return $this->dbHandle->query($sql)->result_array();
    }
    
    function addUserReminder($data, $checkForRedundancy=false, $action='addEdit', $fromWhere='', $oldData=array())
    {
		$this->initiateModel();
		//below line is used for retreiving session value
		$sessionidTracking=getVisitorSessionId();
		if(!isset($data['tracking_keyid'])){
			$data['tracking_keyid']=NULL;
		}

		if($fromWhere == 'editEvent'){
			$this->dbHandle->update('eventCalendar_reminders', array('status'=>'deleted'), 
		 	array('userId'=>$oldData['userId'], 'event_name'=>$oldData['event_name'], 'event_date'=>$oldData['event_date']));
		    if($data['reminder_date'] == ''){
				return;
		    }
		}

		if($action == 'delete'){
		    $this->dbHandle->update('eventCalendar_reminders', array('status'=>'deleted'), array('userId'=>$data['userId'], 'event_name'=>$data['event_name'], 'event_date'=>$data['event_date']));
		}else{ // case add or edit
		    $curData = array();
		    if($checkForRedundancy){
				$curData = $this->getUserReminders($data['userId'], $data['event_name'], $data['event_date']);
		    }
		    if(!empty($curData) && $curData[$data['userId']][$data['event_name']] == 'live'){
				$this->dbHandle->update('eventCalendar_reminders', 
							array('reminder_date'=>$data['reminder_date'], 'date_created'=>$data['date_created'],'tracking_keyid'=>$data['tracking_keyid']),
							array('userId'=>$data['userId'], 'event_name'=>$data['event_name'], 'event_date'=>$data['event_date']
					));
		    }else if(!empty($curData) && $curData[$data['userId']][$data['event_name']] == 'deleted'){
		    	$this->dbHandle->order_by('id','desc');
		    	$this->dbHandle->limit(1);
				$this->dbHandle->update('eventCalendar_reminders', 
					array('reminder_date'=>$data['reminder_date'], 'date_created'=>$data['date_created'], 'status'=>'live','tracking_keyid'=>$data['tracking_keyid']), 
					array('userId'=>$data['userId'], 'event_name'=>$data['event_name'], 'event_date'=>$data['event_date']));
		    }else{
				$data['status'] = 'live';
				$data['visitorsessionid']=$sessionidTracking;
				$this->dbHandle->insert('eventCalendar_reminders', $data);
		    }
		}
    }
    
    function getUserReminders($userId, $eventName, $eventDate)
    {
	$this->initiateModel('read');
        $sql = "select userId, event_name, status from eventCalendar_reminders where userId = ? and event_name= ? and event_date = ? and status in ('live','deleted')";
	$rdata = $this->dbHandle->query($sql, array($userId, $eventName, $eventDate))->result_array();
	$data = array();
	foreach($rdata as $row)
	{
	    $data[$row['userId']][$row['event_name']] = $row['status'];
	}
	return $data;
    }
    
    function getUsersActiveReminders($userId)
    {
		$this->initiateModel('read');
        $sql = "select * from eventCalendar_reminders where userId = ? and status = 'live'";
		$rdata = $this->dbHandle->query($sql, array($userId))->result_array();
		$data = array();
		foreach($rdata as $row)
		{
		    $data[$row['event_date']][$row['event_name']] = $row;
		}
		return $data;
    }
    
    function getUserDetailsToSendReminders()
    {
	$this->initiateModel('read');
	$sql = "select ecr.*, ecr.id as reminder_id, tu.email, tu.mobile, tu.firstname, tu.lastname from eventCalendar_reminders ecr join tuser tu on ecr.userId=tu.userid where ecr.status='live' and ecr.reminder_date = CURRENT_DATE()";
	return $this->dbHandle->query($sql)->result_array();
    }
    
    function updateReminderQueue($remIdArr){
		if(!empty($remIdArr)){
	    $this->initiateModel('read');
	    $sql = "update eventCalendar_reminders set status = 'sent' where id in (".implode(',', $remIdArr).")";
	    $this->dbHandle->query($sql);
		}
    }
    
    function addEvent($params){
		$this->initiateModel('write');
		$sessionidTracking=getVisitorSessionId();
		$sql = "insert into eventCalendar_CustomEvent (userId, eventStatus, eventTitle, eventDescription, eventStartDate, eventEndDate,streamId,courseId,educationTypeId,tracking_keyid,visitorsessionid) values (?,?,?,?,?,?,?,?,?,?,?)";
		$this->dbHandle->query($sql,array($params['userId'],'live',$params['eventTitle'], $params['eventDescription'], $params['eventStartDate'], $params['eventEndDate'],$params['streamId'],$params['courseId'],$params['educationTypeId'],$params['tracking_keyid'],$sessionidTracking));
    }
	
    function updateEvent($params)
    {
	$this->initiateModel('write');
	$sql = "update eventCalendar_CustomEvent set eventTitle=?, eventDescription=?, eventStartDate=?, eventEndDate=? where eventId=?";
	$this->dbHandle->query($sql,array($params['eventTitle'], $params['eventDescription'], $params['eventStartDate'], $params['eventEndDate'],$params['customEventId']));
    }
	
    function getCustomEvents($loggedInUserId, $examFilter)
    {
		$this->initiateModel('read');
		$sql = "select eventId, userId as ownerId, eventTitle as title, eventDescription as description, eventStartDate as start, eventEndDate as end from eventCalendar_CustomEvent where userId = ? and eventStatus = ? and eventStartDate BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND (CURRENT_DATE() + INTERVAL 3 MONTH) and streamId =? and courseId = ? and educationTypeId = ? order by eventStartDate";
		$userSpecificResultSet = $this->dbHandle->query($sql,array($loggedInUserId,'live',$examFilter['streamId'],$examFilter['courseId'],$examFilter['educationTypeId']))->result_array();

	 	$sql = "SELECT userid FROM  `tuser` WHERE usergroup =  'cms' and userid !=?";
	 	$query = $this->dbHandle->query($sql, array($loggedInUserId));
	 	foreach($query->result_array() as $key=>$value){
			$cmsGroupUserIds[] = $value['userid'];
	 	}
	 	$implodeCMSUserIds = "'".implode("','",$cmsGroupUserIds)."'";
	 	$sql = "select eventId, userId as ownerId, eventTitle as title, eventDescription as description, eventStartDate as start, eventEndDate as end from eventCalendar_CustomEvent where userId in ($implodeCMSUserIds) and eventStatus = 'live' and eventStartDate BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND (CURRENT_DATE() + INTERVAL 3 MONTH) and streamId =? and courseId = ? and educationTypeId = ? order by eventStartDate";
		$cmsResultSet = $this->dbHandle->query($sql,array($examFilter['streamId'],$examFilter['courseId'],$examFilter['educationTypeId']))->result_array();
		$userSpecificArrCount  = count($userSpecificResultSet);
		$counter = $userSpecificArrCount;
		foreach($cmsResultSet as $key=>$value){
			$userSpecificResultSet[$counter] = $value;
			$counter++;
		}
		return $userSpecificResultSet;
    }

    function deleteEvent($customEventId)
    {
		$this->initiateModel('write');
		$sql = "update eventCalendar_CustomEvent set eventStatus=? where eventId=?";
		$this->dbHandle->query($sql,array('deleted',$customEventId));
    }

    function deleteReminder($userId,$customEventTitle){
    	$this->initiateModel('write');
		$sql = "update eventCalendar_reminders set status=? where userId=?  and event_name = ?";
		$this->dbHandle->query($sql,array('deleted',$userId,$customEventTitle));
    }
	
    function getEventDetails($eventid)
    {
		$this->initiateModel('read');
		$sql = "select * from eventCalendar_CustomEvent where eventId=?";
		return $this->dbHandle->query($sql,array($eventid))->result_array();
    }
    
    /**
    * This Model expires all the User subscription which are older than 8 Months.
    * @author Virender Singh <virender.singh@shiksha.com>
    * @date   2015-07-22
    * @param  none    
    * @return none
    */
    function expireExamSubscriptionForAllUsers()
    {
	$this->initiateModel('write');
	echo $sql = "update eventCalendar_subscriptions set status = 'expired' where status = 'live' and modified_date < (CURRENT_DATE() - INTERVAL 8 MONTH)";
	$this->dbHandle->query($sql);
	return;
    }
}
