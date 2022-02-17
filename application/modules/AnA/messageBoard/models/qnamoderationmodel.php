<?php
class qnaModerationModel extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('AnA');
	}

	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

	public function getModeratorInfo($userIdArr)
	{
		$this->initiateModel('read');
		$result = array();
		if(!empty($userIdArr))
		{
			$queryCmd = "select * from userGroupsMappingTable ugmt join tuser u on ugmt.userId=u.userid where ugmt.status='live' and (ugmt.groupId=3 or ugmt.groupId=4  or ugmt.groupId = 1) and ugmt.userId in (?)";
			$queryRes = $this->dbHandle->query($queryCmd, array($userIdArr));
			$result = $queryRes->result_array();
		}
		return $result;
	}

    //added by akhter
	function getUserPoints($page_number = 0, $item_per_page = 10){
		    $this->initiateModel('read');
		    $position = ($page_number * $item_per_page);
			$queryCmd = "SELECT SQL_CALC_FOUND_ROWS tu.userid ,tu.firstname, tu.lastname, tu.displayname, tu.email, tup.points, tup.previouspoints from tuser tu, tuserReputationPoint tup where tup.userId = tu.userId and tup.points <= 0 order by tup.id DESC LIMIT $position, $item_per_page";
			$query = $this->dbHandle->query($queryCmd);
			$result_array["result"] =  $query->result_array();
			
			$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
			$queryTotal = $this->dbHandle->query($queryCmdTotal);
			$queryResults = $queryTotal->result();
			$totalRows = $queryResults[0]->totalRows;
			$result_array["total"] = $totalRows;
			return $result_array;
	}

	//added by akhter
	function getUserPointsOnSearch($key,$filter){
			$this->initiateModel('read');
			if($filter == 'displayname'){
               	$and = "AND tu.displayname = ?";
			}else if($filter == 'userid'){
				$and = "AND tu.userid = ?";
			}else if($filter == 'email'){
				$and = "AND tu.email = ?";
			}

			$queryCmd = "SELECT distinct tu.userid ,tu.firstname, tu.lastname, tu.displayname, tu.email, tup.points, tup.previouspoints 
			            from tuser tu, tuserReputationPoint tup 
			            WHERE tup.userId = tu.userId $and";
			$query = $this->dbHandle->query($queryCmd,array($key));
			return $query->result_array();
	}

	function updateUserPoint($prevPoints,$currentPoints,$userid){
		$this->initiateModel('write');
		$query = "Update tuserReputationPoint set previouspoints = ?, points = ? where userId = ?";
		$query = $this->dbHandle->query($query,array($prevPoints,$currentPoints,$userid));
		return $this->dbHandle->affected_rows();
	}

	public function lockEntityForUser($msgId, $userId){
		$this->initiateModel('write');
		$result = array();
		$sql = "insert into messageTableModeration (moderatorId, entityId, moderationStatus, modifiedDate) values (?, ?, 'locked', NOW())";
		$this->dbHandle->query($sql, array($userId, $msgId));
		return 1;
	}

	function markModeratedEntityAsHistory($msgId){
		$this->initiateModel('write');
		$result = array();
		$sql = "update messageTableModeration set status='history' where entityId = ? and status = 'live'";
		$this->dbHandle->query($sql, array($msgId));
		return 1;
	}

	public function unlockEntityForUser($msgId, $userId){
		$this->initiateModel('write');
		$result = array();
		$sql = "update messageTableModeration set moderationStatus = 'completed', modifiedDate = NOW() where moderatorId = ? and entityId = ?";
		$this->dbHandle->query($sql, array($userId, $msgId));
		return true;
	}

	public function getModeratorsInfo($hasModeratorAccess, $accessFlag=false){
		$this->initiateModel('read');
		$in = '4';
		if($hasModeratorAccess == 1)
			$in = '1,3,4';
		else if($hasModeratorAccess == 2 && $accessFlag == false) //contains moderators at same level and below
			$in = '1,3,4';
		else if($hasModeratorAccess == 2 && $accessFlag == true) //contains moderators at lower level only
			$in = '4';
		$sql = "select t.userid, t.email from userGroupsMappingTable ugm join tuser t on ugm.userId=t.userid and groupId in (?) where ugm.status='live' order by ugm.groupId";
		$inArr = explode(',',$in);
		$res = $this->dbHandle->query($sql, array($inArr));
		return $res->result_array();
	}

	function getEntitiesModeratedByModerator($moderatorId, $start=0, $count=50){
		$this->initiateModel('read');
		$moderatedQuery = "select mtm.moderationStatus, mtm.moderatorId, mtm.entityId, mtm.modifiedDate, mt.msgTxt, mt.fromOthers, mt.parentId, mt.threadId, mt.mainAnswerId, mt.msgId, (select msgTxt from messageTable m where mt.threadId=m.msgId) questionTxt from messageTableModeration mtm join messageTable mt on mtm.entityId=mt.msgId where mtm.moderatorId=? and mtm.status = 'live' and mtm.moderationStatus='completed' order by mtm.modifiedDate desc limit $start, $count";
		return $this->dbHandle->query($moderatedQuery, array($moderatorId))->result_array();
	}

	function getModeratedEntities()
	{
		$this->initiateModel('read');
		$moderatedQuery = "select * from messageTableModeration where moderationStatus in ('locked', 'completed') and status='live' and modifiedDate >= now()- INTERVAL 7 Day order by modifiedDate desc";
		$data = $this->dbHandle->query($moderatedQuery)->result_array();
		$entityIdArr = array();
		$moderatorIdArr = array();
		foreach ($data as $key => $value) {
			$entityIdArr[] = $value['entityId'];
			$moderatorIdArr[] = $value['moderatorId'];
		}
		$entityIdArr = array_unique($entityIdArr);
		$moderatorIdArr = array_unique($moderatorIdArr);
		return array('moderatorIdArr'=>$moderatorIdArr, 'entityIdArr'=>$entityIdArr);
	}

	function checkIfSomeEntityIsAlreadyLocked($moderatorId){
		$this->initiateModel('write');
		$lockedQuery = "select entityId from messageTableModeration where moderatorId=? and moderationStatus='locked' and status='live'";
		$res = $this->dbHandle->query($lockedQuery, array($moderatorId))->result_array();
		return (isset($res[0]['entityId']) && $res[0]['entityId'] > 0) ? $res[0]['entityId'] : 0;
	}

	public function getModeratedEntityInfo($filter){
		$this->initiateModel('write');
		if($filter['userids'] != '')
		{
			$sql = "select count(*) as msgCount, mtm.moderatorId FROM messageTable m LEFT JOIN messageTableModeration mtm ON m.msgId = mtm.entityId WHERE m.creationDate >= ? and (mtm.moderationStatus = 'completed' and mtm.status='live' or mtm.moderatorId is NULL) and m.status in ('live', 'closed') and (m.listingTypeId=0 or m.listingTypeId is NULL) and fromOthers in ('user', 'discussion') and m.msgTxt!='dummy' and (mtm.moderatorId in (?) or mtm.moderatorId is NULL) group by moderatorId";
			$userIdsArr = explode(',',$filter['userids']);
			return $this->dbHandle->query($sql, array($filter['timeFlag'],$userIdsArr))->result_array();
		}
		return array();
	}

	public function getLockedEntitiesForModerator($moderatorId){
		$this->initiateModel('write');
		if($moderatorId != '' && $moderatorId > 0){
			$sql = "SELECT mtm.*, mt.msgTxt, mt.status as msgSts, mt.creationDate as msgCreationDate FROM messageTableModeration mtm join messageTable mt ON mtm.entityId = mt.msgId WHERE mtm.moderatorId = ? AND mtm.moderationStatus = 'locked' AND mtm.status = 'live' ";
			return $this->dbHandle->query($sql, array($moderatorId))->result_array();
		}
		return array();
	}

	public function expireLockOfEntityByCms($inputLockId){
		$this->initiateModel('write');
		if($inputLockId != '' && $inputLockId > 0){
			$sql = "UPDATE messageTableModeration set status='history', modifiedDate = NOW() where id = ?";
			$this->dbHandle->query($sql, array($inputLockId));
			return true;
		}
		return false;
	}


	public function getAutomoderationMsgChange($entityId,$automoderationFlag){
		$this->initiateModel('read');

		if($automoderationFlag == 1){
			$sql = "select met.mainText as originalTitle, mt.msgTxt as editedTitle from messageEditTracking met JOIN messageTable mt ON (mt.msgId = met.entityId) where met.entityId = ? and mt.status='live' order by met.id desc limit 1";
		}else if($automoderationFlag == 2){
			$sql = "select met.description as originalDescription, md.description as editedDescription from messageEditTracking met JOIN messageDiscussion md ON (md.threadId = met.entityId) where met.entityId = ? order by met.id desc limit 1";
		}else{
			$sql = "select met.mainText as originalTitle, mt.msgTxt as editedTitle ,met.description as originalDescription, md.description as editedDescription from messageEditTracking met JOIN messageTable mt ON (mt.msgId = met.entityId) LEFT JOIN messageDiscussion md ON (md.threadId = mt.msgId) where met.entityId = ? and mt.status='live' order by met.id desc limit 1";
		}
		$result = $this->dbHandle->query($sql,array($entityId))->result_array();
		return $result[0];
	}

	public function saveEditReasons($finalDataArr){
 		$this->initiateModel('write');
 		if(!empty($finalDataArr)){
			$sql = "insert into moderation_editRequests (entityId, reasonToEdit, comment,  isMailSent, creation_date) values (?, ?, ?, 'yes', NOW())";
			$this->dbHandle->query($sql, array($finalDataArr['entityId'], $finalDataArr['reasonToEdit'], $finalDataArr['comment']));
			return $this->dbHandle->insert_id();	
 		}
	}

	public function getEmailIdAndNameOfUser($userId){
		

		$this->initiateModel('read');
		if($userId != '' && $userId > 0){
			$sql = "Select email, firstname from tuser where userid = ? limit 1";
			$result = $this->dbHandle->query($sql, array($userId))->result_array();
			$resultArr = reset($result);
		}
		return $resultArr;
		
	}

	public function getQuestionText($questionId){
		$this->initiateModel('read');
		if($questionId != '' && $questionId > 0){
			$sql = "Select msgTxt from messageTable where msgId = ? limit 1";
			$result = $this->dbHandle->query($sql, array($questionId))->result_array();
		}
		return $result;
	}

}
?>
