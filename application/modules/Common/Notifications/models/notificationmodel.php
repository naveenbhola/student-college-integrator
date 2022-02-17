<?php
class NotificationModel extends MY_Model
{ 
	private $dbHandle = '';

	/**
	* Constructor Function 
	*/	
	function __construct(){
		parent::__construct('User');
		
	}

	
	/**
	* To Initiate the dbHandler instance
	*/
	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
			$this->dbHandle = $this->getWriteHandle();
		}		
	}


	public function fetchInfoOfQuestion($dataArray){

		$threadId = $dataArray['threadId'];
		$threadType = $dataArray['threadType'];

		$this->load->helper('messageBoard/ana');
		$this->initiateModel('read');
		$sql = "";
		if($threadType == 'question'){
			$sql = "SELECT userId as ownerId,msgTxt as title from messageTable where threadId = ? AND parentId = 0 and fromOthers = 'user' and status IN('live','closed') LIMIT 1";	
		} else if($threadType == 'discussion'){
			$sql = "SELECT userId as ownerId,msgTxt as title from messageTable where threadId = ? AND parentId = threadId and fromOthers = 'discussion' and status IN('live','closed') LIMIT 1";	
		}

		$query = $this->dbHandle->query($sql, array($threadId));
		$result = $query->row_array($query);
		
		$result['title'] = sanitizeAnAMessageText(htmlspecialchars_decode($result['title']),$threadType);
		
		return $result;		
	}

	public function fetchEntityOwner($msgId){
		$this->initiateModel('read');
		$sql = "SELECT userId as ownerId from messageTable where msgId = ? and status IN('live','closed') LIMIT 1";
		$query = $this->dbHandle->query($sql, array($msgId));
		$result = $query->row_array($query);
		return $result['ownerId'];			
	}

	public function fetchNotifificationInfo($action,$entityId,$userId,$status='unread',$columnName){

		$this->initiateModel('read');

		if($columnName == 'threadId'){
			$columnName = 'primaryId';
		}

		if($columnName == 'action_item_id'){
			$columnName = 'secondaryDataId';
		}

		$sql = "SELECT * from notificationsInAppQueue where readStatus = ? and userId = ? and action = ? and $columnName = ? LIMIT 1";

		$query = $this->dbHandle->query($sql, array($status,$userId,$action,$entityId));
		$result = $query->row_array();
		return $result;
	}

	public function fetchFollowersForThread($threadId,$threadType,$ansOrCom=0){
		$this->initiateModel('read');
		$sql = "SELECT distinct userId as recipient FROM tuserFollowTable WHERE entityType = ? AND entityId = ? and status = 'follow'";

		$query = $this->dbHandle->query($sql, array($threadType,$threadId));
		$result = $query->result_array();
		return $result;
	}


	//All Answers on Questions,or all comments on discussions
	public function fetchAnswerOrCommentGivenUsersForThread($threadId,$threadType,$ansOrCom=0){
		$this->initiateModel('read');
		$sql = "";
		$result = array();
		if($threadType == 'question'){
			//$sql = "SELECT distinct userId as recipient FROM messageTable where fromOthers = 'user' and threadId = '".$threadId."' and parentId = threadId and status IN('live','closed')";
			$sql = "SELECT distinct userId as recipient FROM messageTable where fromOthers = 'user' and parentId = ? and status IN('live','closed')";
		} else if($threadType == 'discussion'){
			$sql = "SELECT distinct userId as recipient FROM messageTable where fromOthers = 'discussion' and threadId = ? and mainAnswerId > 0 and status IN('live','closed') and mainAnswerId = parentId";
		}
		if($sql != ""){
			$query = $this->dbHandle->query($sql, array($threadId));
			$result = $query->result_array();	
		}
		
		
		return $result;	
	}

		// case 2 - All comment on question, or all replies on discussion
		public function fetchCommentOrReplyOnAnsOrComment($threadId,$threadType,$ansOrCom=0){
			$this->initiateModel('read');
			$sql = "";
			$result = array();
			if($threadType == 'question'){
				$sql = "SELECT distinct userId as recipient FROM messageTable where fromOthers = 'user' and threadId = ? and status IN('live','closed') and mainAnswerId = parentId and mainAnswerId > 0";
			} else if($threadType == 'discussion'){
				$sql = "SELECT distinct userId as recipient FROM messageTable where fromOthers = 'discussion' and threadId = ? and mainAnswerId > 0 and status IN('live','closed') and mainAnswerId != parentId";
			}
			if($sql != ""){
				$query = $this->dbHandle->query($sql, array($threadId));
				$result = $query->result_array();	
			}
			
			
			return $result;	
		}

		// case 3 - All comment om particular ans, or all reply on comment
		public function fetchCommentOrReplyOnParticularAnsOrComment($threadId,$threadType,$ansOrCom=0){
			$this->initiateModel('read');
			$sql = "";
			$result = array();
			if($threadType == 'question'){
				$sql = "SELECT distinct userId as recipient FROM messageTable where fromOthers = 'user' and threadId = ? and status IN('live','closed') and mainAnswerId > 0 and parentId=?";
			} else if($threadType == 'discussion'){
				$sql = "SELECT distinct userId as recipient FROM messageTable where fromOthers = 'discussion' and threadId = ? and mainAnswerId > 0 and status IN('live','closed') and parentId=?";
			}
			if($sql != ""){
				$query = $this->dbHandle->query($sql, array($threadId,$ansOrCom));
				$result = $query->result_array();	
			}
			
			return $result;	
		}


	public function fetchAnswerOrCommentGivenParticularUsersForThread($threadId,$threadType,$msgId){
		$this->initiateModel('read');
		$sql = "";
		$result = array();
		if($threadType == 'question'){
			$sql = "SELECT userId as recipient FROM messageTable where msgId = ? and status IN('live','closed')";
		} else if($threadType == 'discussion'){
			$sql = "SELECT userId as recipient FROM messageTable where msgId = ? and status IN('live','closed')";
		}
		if($sql != ""){
			$query = $this->dbHandle->query($sql, array($msgId));
			$result = $query->result_array();	
		}
		
		
		return $result;	
	}


	public function insertDataInNotificationInAppQueue($userId,$action,$title,$message,$time,$readStatus='unread',$entityId='',$entityType='',$secondaryData=array(),$landing_page,$notificationType=1,$commandType=0,$trackingURL='', $secondaryDataId=0){

		if($userId){
			$this->initiateModel('write');
			$secondaryData = json_encode($secondaryData);
			$sql = "INSERT INTO notificationsInAppQueue(userId,action,title,message,creationTime,modificationTime,readStatus,primaryId,primaryIdType,secondaryData,landing_page,notificationType,commandType,trackingURL,secondaryDataId) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$query = $this->dbHandle->query($sql,array($userId,$action,$title,$message,$time,$time,$readStatus,$entityId,$entityType,$secondaryData,$landing_page,$notificationType,$commandType,$trackingURL,$secondaryDataId));		
		}
		
	}

	public function updateDataInNotificationInAppQueue($userId,$action,$message,$time,$readStatus,$entityId,$entityType,$action_item,$action_item_id, $newPrimaryId =0 ){
		$this->initiateModel('write');

		$extraUpdateFactors = "";
		if($newPrimaryId){
			$extraUpdateFactors = ", primaryId = ".$this->dbHandle->escape($newPrimaryId);
		}

		$extraWhereClause = "";
		if($entityId){
			$extraWhereClause = " and primaryId = ".$this->dbHandle->escape($entityId);
		}

		$sql = "UPDATE notificationsInAppQueue set message = ?, modificationTime = ? ".$extraUpdateFactors." where readStatus='".$readStatus."' and userId = '".$userId."' and action = '".$action."' ".$extraWhereClause;

		$query = $this->dbHandle->query($sql,array($message,$time));
	}

	public function fetchUnreadNotificationCount($userIdOneDArray){
		$this->initiateModel('write');
		$in_query = implode("','", $userIdOneDArray);
		$sql = "SELECT count(*) as count,userId from notificationsInAppQueue where userId IN('".$in_query."') and readStatus = 'unread' GROUP BY userId";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}


	public function fetchQuestionDetailsForAnswerId($msgId){
		$this->initiateModel('read');
		$this->load->helper('messageBoard/ana');
		
		$sql = "select b.msgTxt as msgTxt,b.msgId,a.userId  from messageTable a,messageTable b where a.parentId = b.msgId and a.msgId = ? and a.status IN('live','closed') and b.status IN('live','closed') LIMIT 1";
		$query = $this->dbHandle->query($sql, array($msgId));
		$result = $query->row_array();
		$result['msgTxt'] = sanitizeAnAMessageText(htmlspecialchars_decode($result['msgTxt']),'question');
		return $result;
	}


	/**
	* Function to fetch the name of User by UseriD
	* @param integer UserId
	* return Name of User (string)
	* MAB-1498 
	*/
	public function fetchNameById($dataArray = array()){
		$this->initiateModel('read');
		$userId = $dataArray['userId']?$dataArray['userId']:0;

		if($userId != 0){
			$sql = "SELECT firstName as userName from tuser where userId = ? LIMIT 1";			
			$query = $this->dbHandle->query($sql, array($userId));
			$result = $query->row_array();			
			return $result;
		}
		return "";
		 
	}

	/**
	* Function to fetch the name,email of User by UseriD
	* @param integer UserId
	* return Name of User (string), email
	*/
	public function fetchDataById($userId=0){
		$this->initiateModel('read');
	
		if($userId != 0){
			$sql = "SELECT email,firstName from tuser where userId = ? LIMIT 1";			
			$query = $this->dbHandle->query($sql, array($userId));
			$result = $query->row_array();			
			return $result;
		}
		return "";
		 
	}
}
