<?php

class notificationinfomodel extends MY_Model
{
    private $dbHandle = '';
    function __construct()
    {
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

    public function fetchInAppNotification($userId,$cnt,$lastId=0){

        $this->initiateModel('read');
        $sql = "";
        $sql = "select * from notificationsInAppQueue where userId = ".$userId." order by modificationTime desc limit ".$cnt;
        
        /*if($lastId == 0){
            $sql = "select * from notificationsInAppQueue where userId = '".$userId."' order by modificationTime desc limit ".$cnt;
        }else {
            $sql = "select * from notificationsInAppQueue where userId = '".$userId."' and id < '".$lastId."'order by modificationTime desc limit ".$cnt;    
        }*/
        
       
        $query = $this->dbHandle->query($sql);
        $result = $query->result_array();

        /*$idArray = array();
        foreach ($result as $key => $value) {
            $idArray[] = $value['id'];
        }
        $in_query = implode("','", $idArray);
        $this->initiateModel('write');
        $time = date('Y-m-d H:i:s');

        $sql = "update notificationsInAppQueue set readStatus = 'read' , modificationTime = '".$time."' where id IN ('".$in_query."')";
       
        $query = $this->dbHandle->query($sql);*/
        return $result;
 
    }

    public function updateReadStatus($userId){ 
        $this->initiateModel('write'); 
        $time = date('Y-m-d H:i:s'); 
        $sql = "update notificationsInAppQueue set readStatus = 'read' where userId = '".$userId."' and readStatus = 'unread'"; 
        $query = $this->dbHandle->query($sql); 
      
    } 

    function getUserGCMIds($userId){

        $dbHandle = $this->getReadHandle();
        $sql      = "SELECT gcmId from devicesAuthKey where userId = ? AND status = 1 AND !ISNULL(gcmId) AND gcmId != ''";
        $rs       = $dbHandle->query($sql, array($userId));

        $gcmIds = array();
        foreach($rs->result_array() as $row)
        {
            $gcmIds[] = $row['gcmId'];
        }

        return $gcmIds;
    }

    public function getUserIdsForInAppNotification($userId){

        $dbHandle = $this->getReadHandle();
        $sql      = "SELECT userId from appUserLastActivity where userId = ? ";
        $rs       = $dbHandle->query($sql, array($userId));

        $userIdArray = array();
        foreach($rs->result_array() as $row)
        {
            $userIdArray[] = $row['userId'];
        }

        return $userIdArray;
    }



    function fetchNotificationCountForUser($userId=0){
        $this->initiateModel('read');
	$todayDate = date("Y-m-d");
        $sql    = "SELECT count(*) as count from notificationsGCMQueue where userId = ? and creationTime >= ?";
        $userId = (int)$userId;
        $query = $this->dbHandle->query($sql, array($userId,$todayDate));
        $row = $query->row_array();        
        return $row['count'];
    }

    function checkIfNotificationsAlreadyExistsForToday($userId,$actionKey, $primaryId, $secondaryId){

        $this->initiateModel('read');

        $sql   = "SELECT id FROM notificationsInAppQueue WHERE userId = ? AND action = ? AND primaryId = ? AND secondaryDataId = ? AND DATE(modificationTime) = CURDATE()";

        $query = $this->dbHandle->query($sql, array($userId,$actionKey, $primaryId, $secondaryId));

        if($query->num_rows() > 0)
            return true;
        else
            return false;
    }

    function getTodaysAnswers(){

        $this->initiateModel('read');

        $sql   = "SELECT msgId,threadId FROM `messageTable` WHERE fromOthers='user' AND threadId = parentId AND creationDate >= now() - INTERVAL 1 DAY AND status IN('live','closed')";

        $rs = $this->dbHandle->query($sql);

        $result = array();
        foreach($rs->result_array() as $row)
        {
            $result[] = $row;
        }

        return $result;
    }

    function getTodaysRepliesOnAnswers(){

        $this->initiateModel('read');

        $sql   = "SELECT msgId,parentId,threadId FROM `messageTable` WHERE fromOthers='user' AND threadId != parentId AND parentId !=0 AND status IN('live','closed') AND creationDate >= now() - INTERVAL 1 DAY";

        $rs = $this->dbHandle->query($sql);

        $result = array();
        foreach($rs->result_array() as $row)
        {
            $result[] = $row;
        }

        return $result;
    }

    function getQuestionsOwner($questionIds){

        $result = array();

        if(empty($questionIds))
            return $result;

        $this->initiateModel('read');

        $sql   = "SELECT msgId,userId FROM `messageTable` WHERE fromOthers='user' AND msgId IN(".implode(",", $questionIds).") AND parentId = 0 AND status IN ('live','closed')";
        $rs = $this->dbHandle->query($sql);

        foreach($rs->result_array() as $row)
        {
            $result[$row['msgId']] = $row;
        }

        return $result;
    }

    function getAnswerOwners($answerIds){

        $result = array();

        if(empty($answerIds))
            return $result;

        $this->initiateModel('read');

        $sql   = "SELECT msgId,userId FROM `messageTable` WHERE fromOthers='user' AND msgId IN(".implode(",", $answerIds).") AND status IN ('live','closed')";
        $rs = $this->dbHandle->query($sql);

        foreach($rs->result_array() as $row)
        {
            $result[$row['msgId']] = $row;
        }

        return $result;   
    }

    function getTodaysUpvotes(){

        $result = array();

        $this->initiateModel('read');

        $sql   = "SELECT userId, productId FROM `digUpUserMap` WHERE digTime >= now() - INTERVAL 1 DAY AND product = 'qna' AND digFlag = 1 AND digUpStatus = 'live'";
        $rs = $this->dbHandle->query($sql);

        foreach($rs->result_array() as $row)
        {
            $result[] = $row;
        }

        return $result;   
    }

    function filterAnswers($upvotesThreads){

        $result = array();
        if(empty($upvotesThreads))
            return $result;

        $this->initiateModel('read');

        $sql   = "SELECT msgId,userId FROM `messageTable` WHERE fromOthers='user' AND threadId = parentId AND msgId IN (".implode(",", $upvotesThreads).")";

        $rs = $this->dbHandle->query($sql);

        foreach($rs->result_array() as $row)
        {
            $result[$row['msgId']] = $row;
        }

        return $result;   
    }

    function filterComments($upvotesThreads){

        $result = array();
        if(empty($upvotesThreads))
            return $result;

        $this->initiateModel('read');

        $sql   = "SELECT msgId,userId FROM `messageTable` WHERE fromOthers='discussion' AND msgId IN (".implode(",", $upvotesThreads).")";

        $rs = $this->dbHandle->query($sql);

        foreach($rs->result_array() as $row)
        {
            $result[$row['msgId']] = $row;
        }

        return $result;   
    }


    function getTodaysDiscussionCommentsAndReplies(){

        $this->initiateModel('read');

        $sql   = "SELECT msgId , threadId, parentId, (LENGTH(path) - LENGTH(REPLACE(path, '.', '')))+1 as pathCount FROM `messageTable` WHERE fromOthers = 'discussion' AND creationDate >= now() - INTERVAL 1 DAY AND status IN('live','closed') ";

        $rs = $this->dbHandle->query($sql);

        $result = array();
        foreach($rs->result_array() as $row)
        {
            $result[] = $row;
        }

        return $result;
    }

    function getDiscussionsEntityOwner($discussionCommentsThreadIds){

        $result = array();
        if(empty($discussionCommentsThreadIds))
            return $result;

        $this->initiateModel('read');

        $sql   = "SELECT msgId , userId FROM `messageTable` WHERE msgId IN(".implode(",", $discussionCommentsThreadIds).") ";

        $rs = $this->dbHandle->query($sql);

        foreach($rs->result_array() as $row)
        {
            $result[$row['msgId']] = $row;
        }

        return $result;
    }

    function updateGCMNotificationStatus($notificationId, $toStatus = 'discard'){

        if(empty($notificationId) || empty($toStatus))
            return false;

        $this->initiateModel('write');
        
        $data                  = array();
        $data['deliverStatus'] = $toStatus;
        
        $this->dbHandle->where('id',$notificationId);
        $status = $this->dbHandle->update('notificationsGCMQueue',$data);
    }
}
