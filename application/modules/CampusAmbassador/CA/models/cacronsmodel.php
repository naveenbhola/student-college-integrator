<?php
class CACronsModel extends MY_Model
{ /*

   Copyright 2014 Info Edge India Ltd

   $Author: Virender

   $Id: Campus Ambassador Crons

 */


	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
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
	
	function getCAListForOpenTasks()
	{
		$this->initiateModel();
		$sql = "SELECT distinct cap.userId, u.firstname, u.lastname, u.email, cap.programId FROM CA_ProfileTable as cap
                                        left join CA_MainCourseMappingTable as camc on cap.id = camc.caId
                                        left join tuser as u on u.userid = cap.userId
                                        WHERE
                                        cap.profileStatus='accepted'
                                        AND camc.instituteId>0
					AND camc.courseId>0
					AND camc.locationId>0
                                        AND camc.status='live'
                                        AND camc.badge='CurrentStudent'";
		$res = $this->dbHandle->query($sql);
		return $res->result_array();
	}
	
	/*
	Function to get all open tasks for
	a user with sum of all prize money
	
	Author : Virender
	*/
	function getOpenTasksForCAMailer($userId,$programId)
	{
		$this->initiateModel('read');
		$result = array();
		$sql = "select SQL_CALC_FOUND_ROWS mt.*, mtp.*, group_concat(mtp.prize_amount order by mtp.prize_id) as prize_amount_str, sum(mtp.prize_amount) as total_prize from my_tasks mt join my_task_prizes mtp on (mt.id = mtp.my_task_id) where mt.status='live'  and mt.programId = ? and mtp.status='live' and (mt.start_date<=CURDATE() and mt.end_date>CURDATE() and mt.end_date is NOT NULL) and mt.id not in (select taskRefId from CA_TaskSubmission where userId=?) group by mt.id
			UNION
			select mt1.*, mtp1.*, group_concat(mtp1.prize_amount order by mtp1.prize_id) as prize_amount_str, sum(mtp1.prize_amount) as total_prize from my_tasks mt1 join my_task_prizes mtp1 on (mt1.id = mtp1.my_task_id) where mt1.status='live'  and mt1.programId = ? and mtp1.status='live' and mt1.start_date<=CURDATE() and mt1.end_date is NULL and mt1.id not in (select taskRefId from CA_TaskSubmission where userId=?) group by mt1.id
			order by start_date, end_date asc";
		$query = $this->dbHandle->query($sql,array($programId, $userId, $programId, $userId));
		$resultSet = $query->result_array();
		return $resultSet;
	}

	function getMentorsHavingMentees()
	{	
		$this->initiateModel('read');
		$result = array();
		$sql = "select distinct mentorId from CA_mentorship_relation where status = 'live'";
 
		$query = $this->dbHandle->query($sql);
		$resultSet = $query->result_array();
		return $resultSet;	
		
	}
	
	function getMentorsToSetUpChat()
	{	
		$this->initiateModel('read');
		
		$allMentorsHavingMentee = $this->getMentorsHavingMentees();
		
		$mentorArray = array();
		foreach($allMentorsHavingMentee as $values){
			
			$mentorArray[] = $values['mentorId'];
 		}
		$mentorString = implode(',',$mentorArray);
 		
		$result = array();
		if(!empty($mentorArray)){
			$sql = "select capt.userId,tu.firstname,tu.email from CA_ProfileTable capt,CA_MainCourseMappingTable camcmt,categoryPageData cpd,tuser tu where capt.profileStatus = 'accepted' and capt.id=camcmt.caId and camcmt.status='live' and camcmt.badge='CurrentStudent' and cpd.`course_id` = camcmt.courseId And cpd.category_id = '56' And cpd.status = 'live' AND tu.userid = capt.userId AND capt.userId in ($mentorString) AND capt.userId NOT IN(select ownerId from CA_mentorship_chatSlots where userType = 'mentor' and createdDate > now() - INTERVAL 7 day GROUP BY ownerId)  GROUP BY  capt.userId";
	 
			$query = $this->dbHandle->query($sql);
			$resultSet = $query->result_array();
			return $resultSet;
		}
		
		return array();
		
	}
	
	function getMenteeToSelectSlot()
	{	
		$this->initiateModel('read');
		$result = array();
		$sql = "select cmmd.userId,tu.firstname,tu.email from CA_mentorship_mentee_detail cmmd,tuser tu where cmmd.menteeStatus='assigned' AND tu.userid = cmmd.userId AND cmmd.userId NOT IN(select ownerId from CA_mentorship_chatSlots where userType = 'mentee' and createdDate > now() - INTERVAL 7 day GROUP BY ownerId) GROUP BY cmmd.userId";
 
		$query = $this->dbHandle->query($sql);
		$resultSet = $query->result_array();
		return $resultSet;
		
	}
	
	function getMenteeToAcceptChatSchedule()
	{
		$this->initiateModel('read');
		$result = array();
		$sql = "select cmmd.userId,tu.firstname,tu.email from CA_mentorship_mentee_detail cmmd,tuser tu where cmmd.menteeStatus='assigned' AND tu.userid = cmmd.userId AND cmmd.userId NOT IN (select menteeId from CA_mentorship_chatSchedules where scheduleStatus = 'accepted' and modificationDate > now() - INTERVAL 7 day GROUP BY menteeId) GROUP BY cmmd.userId";
 
		$query = $this->dbHandle->query($sql);
		$resultSet = $query->result_array();
		return $resultSet;
		
	}
	
}
