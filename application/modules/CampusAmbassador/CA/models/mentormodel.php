<?php
class MentorModel extends MY_Model
{
	/***
	   Copyright 2015 Info Edge India Ltd
	   $Author: UGC Team
	 ***/
	private $dbHandle = '';
	function __construct(){
		parent::__construct('CampusAmbassador');
	}
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */

    	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
        	$this->dbHandle = $this->getWriteHandle();
		}		
	}
	/**
	*@param   : $category
	*@description : get mentor info based on category default for engineering (56)
	*@author  : akhter
	**/
	function getMentorDetails($category = 56)
	{
		$this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');
		
		$key = 'getMentorCount';
                if($cacheLib->get($key)=='ERROR_READING_CACHE'){
			
			$query1 = "select SQL_CALC_FOUND_ROWS caft.userId,camt.instituteId,camt.courseId,camt.locationId, camt.semester,cpd.category_id, caft.imageURL from CA_ProfileTable caft,CA_MainCourseMappingTable camt,categoryPageData cpd where camt.caId = caft.id AND caft.profileStatus = 'accepted' AND camt.instituteId>0 AND camt.courseId = cpd.course_id AND camt.courseId>0 AND camt.locationId>0 AND camt.badge = 'CurrentStudent' AND cpd.category_id = ? AND cpd.status = 'live' group by caft.userId";

			$result_array["result"] = $this->dbHandle->query($query1,array($category))->result_array();
			
			$queryCmdTotal = 'SELECT FOUND_ROWS() as totalMentor';
			$queryTotal = $this->dbHandle->query($queryCmdTotal);
			$queryResults = $queryTotal->result();
			$totalRows = $queryResults[0]->totalMentor;
			$result_array["totalMentor"] = $totalRows;
			$i=0;
			foreach($result_array["result"] as $results){			
				$userIdsArray[] = $results['userId'];
				if($results['imageURL'] != ''){
					$result_array["result"][$i]['imageURL'] = addingDomainNameToUrl(array('url' =>$results['imageURL'], 'domainName' => MEDIA_SERVER ));
				}
				
				$i++;
			}
			
			$userIdString = implode(',',$userIdsArray);
			if($userIdString == '')
			{
				return 0;
			}
			$query2 = "select tu.firstname, tu.lastname ,tu.userid from tuser tu where userid in ($userIdString)";
			$mentorDetail = $this->dbHandle->query($query2)->result_array();
			
			foreach($mentorDetail as $mentorDetails){
				
				$mentorInformation[$mentorDetails['userid']] = $mentorDetails;
			}
			$result_array["mentorInformation"] = $mentorInformation;
			
			$query3 = "select IFNULL(count(mA.msgId),0) as totalAns,mA.userId from messageTable mA where mA.fromOthers='user' and mA.mainAnswerId=0 and mA.userId in ($userIdString) and status in ('live','closed') GROUP BY mA.userId";
			
			$mentorAnsCount = $this->dbHandle->query($query3)->result_array();
			
			
			foreach($mentorAnsCount as $mentorAnsCounts){
				
				$mentorTotalAns[$mentorAnsCounts['userId']] = $mentorAnsCounts;
			}

			$result_array["mentorAnsCount"] = $mentorTotalAns;
					
			$cacheLib->store($key,$result_array, 7200);
		}else{
			$result_array = $cacheLib->get($key);
		}
		return $result_array;
	}
	
	/*
	Method to check if a user is mentor or not.
	Method Name   = checkUserIfMentor
	Input Params  = user id in form of array
	Outupt Params = if user is mentor 
					then 
					return mentorid as index of array with course id as a value
					else
					return mentorid as index of array with false as a value
	 */

	function checkUserIfMentor($userIds){
		$this->initiateModel('read');
		$res = array();
		$userIdsStr = "'".implode("','",$userIds)."'";
		$sql = "select (SELECT cpd.course_id FROM `categoryPageData` cpd
				WHERE cpd.`course_id` = camcmt.courseId
				And cpd.category_id = '56'
				And cpd.status = 'live'
				limit 1) as courseId, capt.userId from CA_ProfileTable capt, CA_MainCourseMappingTable camcmt where capt.profileStatus = 'accepted' 
				and capt.userId in ($userIdsStr) and capt.id=camcmt.caId and camcmt.status='live' and camcmt.badge='CurrentStudent'";
		$queryRes = $this->dbHandle->query($sql);
		foreach($queryRes->result_array() as $key => $val)
		{
			if($val['courseId']==''){
				$res[$val['userId']] = 'false';
			}else{
				$res[$val['userId']][] = $val['courseId'];
			}
		}
		return $res;		
	}

	/*
	Method to check if mentee assined to a mentor or not.
	Method Name   = checkIfMentorAssignedToMentee
	Input Params  = mentee id in form of array
	Outupt Params = if mentee assign to a mentor
					then 
					return menteeId as index of array with mentor id as a value
					else
					return menteeId as index of array with false as a value
	 */
	function checkIfMentorAssignedToMentee($menteeIds){
		$this->initiateModel('read');
		$resultSet = array();
		foreach($menteeIds as $key=>$value){
			$resultSet[$value] = false;
		}
		$menteeIdsStr = "'".implode("','",$menteeIds)."'";
		$sql = "select * from CA_mentorship_relation where menteeId in ($menteeIdsStr) and status='live'";
		$query = $this->dbHandle->query($sql);
		$res = $query->result_array();$res = $query->result_array();
		$discardCourseIds = array();
		foreach($res as $key=>$value){
			$resultSet[$value['menteeId']]['mentorId'] = $value['mentorId'];
			$resultSet[$value['menteeId']]['chatId'] = $value['chatId'];
		}
		return $resultSet;
	}
	
	function addMentorShipChatSlot($input)
	{
		$this->initiateModel('write');
		$sql = "insert into CA_mentorship_chatSlots (ownerId, slotTime, discussionTopic, userType, slotStatus, createdDate, modificationDate) values (?,?,?,?,?,?,?)";
		$query = $this->dbHandle->query($sql,array($input['userId'],$input['slotTime'],$input['discussionTopic'],$input['userType'],$input['slotStatus'],$input['createdDate'],$input['modificationDate']));
	}
	
	function updateChatSlotStatus($slotStatus, $slotId)
	{
		$this->initiateModel('write');
		$sql = "update CA_mentorship_chatSlots set slotStatus = ? where slotId = ?";
		$this->dbHandle->query($sql, array($slotStatus, $slotId));
	}
	
	function addNewChatSchedule($insertData)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('CA_mentorship_chatSchedules', $insertData);
		return $this->dbHandle->insert_id();
	}
	
	function getAllMentees($mentorId)
	{
		$this->initiateModel('read');
		$sql = "select * from CA_mentorship_relation where mentorId = ? and status = 'live'";
		$query = $this->dbHandle->query($sql,array($mentorId));
		return $query->result_array();
	}
	
	function updateChatScheduleStatus($status, $cancelledBy, $scheduleId)
	{
		$this->initiateModel('write');
		$sql = "update CA_mentorship_chatSchedules set scheduleStatus = ?, cancelledBy = ? where id = ?";
		$this->dbHandle->query($sql, array($status, $cancelledBy, $scheduleId));
	}
	
	function addMentee($userId,$prefC1,$prefC2,$prefC3,$examYr,$branchPref1,$branchPref2,$branchPref3,$targetClg)
	{
		$this->initiateModel('write');
		$query = "INSERT into `CA_mentorship_mentee_detail` (`userId`,`prefCollegeLocation1`,`prefCollegeLocation2`,`prefCollegeLocation3`,`startEngYear`,`prefEngBranche1`,`prefEngBranche2`,`prefEngBranche3`,`targetColleges`) values(?,?,?,?,?,?,?,?,?)";
		$this->dbHandle->query($query,array($userId,$prefC1,$prefC2,$prefC3,$examYr,$branchPref1,$branchPref2,$branchPref3,$targetClg));
		$insertId = $this->dbHandle->insert_id();
	        return $insertId;
	}
	
	function addMenteeExam($menteeId,$examName,$score)
	{
		$this->initiateModel('write');
		$query = "INSERT into `CA_mentorship_mentee_mapping_exam` (`menteeId`,`examName`,`score`) values(?,?,?)";
		$this->dbHandle->query($query,array($menteeId,$examName,$score));
		$insertId = $this->dbHandle->insert_id();
	        return $insertId;
	}
	
	function existMentee($userId)
	{
		$this->initiateModel('write');
		$sql = "select menteeId from CA_mentorship_mentee_detail where userId = ?";
		$query = $this->dbHandle->query($sql,array($userId));
		$queryResults =  $query->result();
		return $queryResults[0]->menteeId;
	}


	function checkMentorSlots($mentorId){
		$this->initiateModel('read');
		$sql = "select slotTime,slotStatus,slotId from CA_mentorship_chatSlots where ownerId=? and userType='mentor' and slotStatus in ('free','booked') and slotTime > now() order by slotTime";
		$query = $this->dbHandle->query($sql,array($mentorId));
		$res = $query->result_array();
		foreach($res as $key=>$value){
			$resultSet['slotTime'][$value['slotStatus']][$value['slotId']] = $value['slotTime'];
		}
		return $resultSet;
	}

	function getMenteeId($userId)
	{
		$this->initiateModel('read');
		$sql = "select menteeId from CA_mentorship_mentee_detail where userId = ?";
		$query = $this->dbHandle->query($sql , array($userId));
		$resultQuery = $query->result_array();
		$res = $resultQuery[0]['menteeId'];
		return $res;
	}

	function getMenteeDetails($menteeId){
		
		$this->initiateModel('write');
		$sql = "select userId,prefCollegeLocation1,prefCollegeLocation2,prefCollegeLocation3,prefEngBranche1,prefEngBranche2,prefEngBranche3 from CA_mentorship_mentee_detail where menteeId = ? ";
		$query = $this->dbHandle->query($sql, array($menteeId));
		$res = $query->result_array();
		return $res;

}
	function getMenteeEmailIdAndName($userid){
		$this->initiateModel('write');
		$sql = "select email,firstname,lastname,mobile,city from tuser where userid = ? ";
		$query = $this->dbHandle->query($sql,array($userid));
		$res = $query->result_array();
		return $res;

	}

	function getMenteeExamTaken($menteeId){
		$this->initiateModel('write');
		$sql = "select examName from CA_mentorship_mentee_mapping_exam where menteeId = ? ";
		$query = $this->dbHandle->query($sql,array($menteeId));
		$res = $query->result_array();
		return $res;

	}

	function checkSlotStatus($slotId){
		$this->initiateModel('read');
		$sql = "select slotStatus from CA_mentorship_chatSlots where slotId=?";
		$query = $this->dbHandle->query($sql,array($slotId));
		$res = $query->result_array();
		return $res[0]['slotStatus'];
	}

	function bookFreeSlotByMentee($params){		
		$this->initiateModel('write');
		$sql = "update CA_mentorship_chatSlots set slotStatus=?,discussionTopic=? where slotId=?";
		$queryRes = $this->dbHandle->query($sql,array('booked',$params['discussionTopic'],$params['slotId']));

		$sql = "insert into CA_mentorship_chatSchedules (`slotId`,`mentorId`,`menteeId`,`scheduleStatus`,`createdDate`) values (?,?,?,?,now());";
		$queryRes = $this->dbHandle->query($sql,array($params['slotId'],$params['mentorId'],$params['menteeId'],'accepted'));
	}

	function checkIfMenteeBookedOrRequestSlot($menteeId,$mentorId){
		$this->initiateModel('read');
		$bufferTime = 29*60 + 50;
		$sql = "(select camcs.userType, camcs.slotId, camcs.slotTime, camcs.discussionTopic, camcs.slotStatus,camcsh.id, camcsh.mentorId, camcsh.menteeId, camcsh.scheduleStatus from CA_mentorship_chatSlots camcs left join CA_mentorship_chatSchedules camcsh on (camcs.slotId = camcsh.slotId) where camcs.ownerId=? and camcs.slotStatus in ('free','booked') and camcs.slotTime > now() - $bufferTime)
		UNION
		(select camcs.userType, camcs.slotId, camcs.slotTime, camcs.discussionTopic, camcs.slotStatus,camcsh.id, camcsh.mentorId, camcsh.menteeId, camcsh.scheduleStatus from CA_mentorship_chatSlots camcs left join CA_mentorship_chatSchedules camcsh on (camcs.slotId = camcsh.slotId) where camcs.ownerId=? and camcs.slotStatus ='booked' and camcsh.scheduleStatus in ('accepted','started')  and camcs.slotTime > now() - $bufferTime and camcsh.menteeId=?)";
		$queryRes = $this->dbHandle->query($sql,array($menteeId,$mentorId,$menteeId));
		$res = $queryRes->result_array();
		return $res[0];
	}
	
	function checkIfMenteeHasAnyScheduledChat($menteeId, $mentorId)
	{
		$this->initiateModel('read');
		$sql = "select * from CA_mentorship_chatSchedules where mentorId = ? and menteeId = ? and scheduleStatus = 'started' limit 1";
		$queryRes = $this->dbHandle->query($sql, array($mentorId, $menteeId));
		return $queryRes->result_array();
	}
	
	function declineOtherRequestedSlots($slotTime, $menteeIds)
	{
		if($menteeIds != '')
		{
			$this->initiateModel('write');
			$sql = "update CA_mentorship_chatSlots set slotStatus = 'decline' where ownerId in (".$menteeIds.") and slotTime between ? and ?";
			$this->dbHandle->query($sql, array(date('Y-m-d H:i:s', strtotime($slotTime) - 30*60 + 1), date('Y-m-d H:i:s', strtotime($slotTime) + 30*60 - 1)));
		}
	}


	function checkIfRquestedSlotAlreadyBooked($slotTime,$mentorId,$menteeId){
		$this->initiateModel('read');
		$menteeInfo = $this->getAllMentees($mentorId);
		$menteeId =array();
		foreach ($menteeInfo as $key => $value) {
			$menteeId[] = $value['menteeId'];
		}
		$menteeIdsStr = "'".implode($menteeId, "','")."'";
		$res = array();
		$flagStatus = 0;
		$sql = "select 1 as flag from CA_mentorship_chatSlots camcs where camcs.ownerId=? and UNIX_TIMESTAMP(camcs.slotTime)=UNIX_TIMESTAMP('".$slotTime."') and camcs.slotStatus in ('free','booked')
				UNION
				select 1 as flag from CA_mentorship_chatSlots camcs where camcs.ownerId in ($menteeIdsStr) and UNIX_TIMESTAMP(camcs.slotTime)=UNIX_TIMESTAMP('".$slotTime."') and camcs.slotStatus in ('free','booked')";
		$queryRes = $this->dbHandle->query($sql,array($mentorId));
		$res = $queryRes->result_array();
		if(!empty($res)){
			$flagStatus = 1;
		}
		return $flagStatus;
	}
	
	function startChatSession($scheduleId)
	{
		$this->initiateModel('write');
		$sql = "update CA_mentorship_chatSchedules set scheduleStatus='started' where id = ?";
		$this->dbHandle->query($sql, array($scheduleId));
	}

	function getUserDetails($userId)
	{
		$this->initiateModel('read');
		$result = array();
		
		$sql = "select firstname,lastname,email from tuser tu where tu.userid = ? limit 1";
		
		$query = $this->dbHandle->query($sql,array($userId));
		$result = $query->result_array();
		return $result;
		
	}
	
	function getMentorshipChatHistory($mentorId, $menteeId, $fromWhere = 'desktop')
	{
		$this->initiateModel('read');
		if($fromWhere == 'mobile')
		{
			$sql = "select h.id as chatLogId, h.slotId, h.scheduleId, h.mentorId, h.menteeId, h.chatStatus, h.chatComments, s.slotTime, s.discussionTopic from CA_mentorship_chatHistory h join CA_mentorship_chatSlots s on h.slotId = s.slotId where h.mentorId = ? and h.menteeId = ? and chatStatus <> 'deleted' order by s.slotTime desc";
			$res = $this->dbHandle->query($sql, array($mentorId, $menteeId));
		}
		else
		{
			if(isset($menteeId) && $menteeId != '' && $menteeId > 0) {
				$sql = "select h.id as chatLogId, h.slotId, h.scheduleId, h.mentorId, h.menteeId, h.chatStatus, h.chatComments, s.slotTime, s.discussionTopic, h.chatText from CA_mentorship_chatHistory h join CA_mentorship_chatSlots s on h.slotId = s.slotId where h.mentorId = ? and h.menteeId = ? and chatStatus <> 'deleted' order by s.slotTime desc";
				$res = $this->dbHandle->query($sql, array($mentorId, $menteeId));
			} else {
				$sql = "select h.*, h.id as chatLogId, s.slotTime, s.discussionTopic, u.firstname, u.lastname, u.displayname from CA_mentorship_chatHistory h join CA_mentorship_chatSlots s on h.slotId = s.slotId join tuser u on h.menteeId = u.userid where h.mentorId = ? and chatStatus <> 'deleted' order by s.slotTime desc";
				$res = $this->dbHandle->query($sql, array($mentorId));
			}
		}
		return $res->result_array();
	}
	
	function addMentorshipChat($insertData)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('CA_mentorship_chatHistory', $insertData);
		return $this->dbHandle->insert_id();
	}
	
	function updateChatHistoryText($chatTxt, $logId)
	{
		$this->initiateModel('write');
		$sql = "update CA_mentorship_chatHistory set chatText = ? where id = ?";
		$this->dbHandle->query($sql, array($chatTxt, $logId));
	}

	function getSlotDetailsById($slotId)
	{
		$this->initiateModel('read');
		$sql = "select slotTime,ownerId from CA_mentorship_chatSlots where slotId=?";
		$query = $this->dbHandle->query($sql,array($slotId));
		$res = $query->result_array();
		return $res;
	}

function getMentorshipChatById($id)
	{
		$this->initiateModel('read');
		$sql = "select chatText from CA_mentorship_chatHistory where id = ? and chatStatus <> 'deleted'";
		$res = $this->dbHandle->query($sql, array($id));
		$result = $res->result_array();
		return $result[0]['chatText'];
	}

	function insertReasonForDisapproval($reason, $logId){
		$this->initiateModel('write');
		$sql = "UPDATE  `CA_mentorship_chatHistory` SET  `chatComments` =  ? , chatStatus = 'disapproved' WHERE  `id` = ?;";
		$query = $this->dbHandle->query($sql,array($reason, $logId));
			
	}

	function insertReasonForApproval($logId){
		$this->initiateModel('write');
		$sql = "UPDATE  `CA_mentorship_chatHistory` SET  chatStatus = 'approved' WHERE  `id` = ?;";
		$query = $this->dbHandle->query($sql,array($logId));
			
	}

function getTotalMentorCount($category = 56)
	{
		$this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');
		
		$key = 'getTotalMentorCount';
                if($cacheLib->get($key)=='ERROR_READING_CACHE'){
			
			$query1 = "select SQL_CALC_FOUND_ROWS caft.userId,camt.instituteId,camt.courseId,camt.locationId, camt.semester,cpd.category_id, caft.imageURL from CA_ProfileTable caft,CA_MainCourseMappingTable camt,categoryPageData cpd where camt.caId = caft.id AND caft.profileStatus = 'accepted' AND camt.instituteId>0 AND camt.courseId = cpd.course_id AND camt.courseId>0 AND camt.locationId>0 AND camt.badge = 'CurrentStudent' AND cpd.category_id = ? AND cpd.status = 'live' group by caft.userId";

			$result_array["result"] = $this->dbHandle->query($query1,array($category))->result_array();
			
			$queryCmdTotal = 'SELECT FOUND_ROWS() as totalMentor';
			$queryTotal = $this->dbHandle->query($queryCmdTotal);
			$queryResults = $queryTotal->result();
			$totalRows = $queryResults[0]->totalMentor;
			$res = $totalRows;
			return $res;
		}
	}
}
