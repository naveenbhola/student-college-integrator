<?php

class apicronmodel extends MY_Model
{
    function __construct()
    {
    	parent::__construct('User');
		$this->db = $this->getReadHandle();
    }

    function getFBAccessTokens($fromTimePeriod){

        $dbHandle = $this->getReadHandle();
        $sql      = "select userId,access_token,facebookUserId from facebook_access_token where creationTime >= ? and status = 'live' and facebookUserId != 0";
        $rows     = $dbHandle->query($sql, array($fromTimePeriod))->result_array();
        return $rows;
    }

    function updateFacebookFriendsStatus($userid, $fromStatus, $toStatus){

        $dbHandle       = $this->getWriteHandle();
        $data['status'] = $toStatus;

        $dbHandle->where('userid',$userid);
        $dbHandle->where('status',$fromStatus);
        $status = $dbHandle->update('facebookFriendsMapping',$data);

        return $status;
    }

    function insertFacebookFriends($userId, $facebookUserId, $friendsList){

        $dbHandle       = $this->getWriteHandle();
        $friendsMap = array();
        foreach($friendsList['data'] as $fbFriends){

            $friendMapRow = array();
            $friendMapRow['userid'] = $userId;
            $friendMapRow['facebookUserId'] = $facebookUserId;
            $friendMapRow['friendsShikshaUserId'] = $fbFriends['shikshaUserId'] ? $fbFriends['shikshaUserId'] : NULL;
            $friendMapRow['facebookFriendUserId'] = $fbFriends['id'];
            $friendMapRow['facebookFriendName'] = $fbFriends['name'];
            $friendMapRow['status'] = 'live';

            $friendsMap[] = $friendMapRow;
        }

        if(!empty($friendsMap)){
            $dbHandle->insert_batch('facebookFriendsMapping', $friendsMap);
        }
    }

    function getShikshaUserIdOfFacebookUsers($facebookUserIds){

        if(empty($facebookUserIds))
            return false;

        $result = array();
        $dbHandle = $this->getReadHandle();
        $sql      = "SELECT facebookUserId, userId FROM facebook_access_token WHERE status = 'live' AND facebookUserId IN (?)";
        $rows     = $dbHandle->query($sql, array($facebookUserIds))->result_array();
        foreach ($rows as $value) {
            $result[$value['facebookUserId']] = $value['userId'];
        }

        return $result;
    }

    function getUsersWithAnAPoints($fromTimePeriod){

        $dbHandle = $this->getReadHandle();
        $sql      = "select distinct userId from userpointsystembymodule where moduleName = 'AnA'";
        $rows     = $dbHandle->query($sql)->result_array();
        return $rows;
    }

    function getUsersWithChangedAnAPoints($days = 1){

        $dbHandle = $this->getReadHandle();
        $curDate  = date("Y-m-d", strtotime( '-'.$days.' days' ));
        $sql      = "select distinct userId from userpointsystemlog where module = 'AnA' and timestamp >= ? and timestamp <= ?";
        $rows     = $dbHandle->query($sql, array($curDate." 00:00:00",$curDate." 23:59:59"))->result_array();
        return $rows;
    }

    function getTagsWithRecentActivities($days = 1){

        $dbHandle = $this->getReadHandle();

        $sql = "SELECT distinct entityId FROM tuserFollowTable WHERE entityType = 'tag' AND (DATE(creationTime) = (CURDATE()-INTERVAL ".$days." DAY) OR DATE(modificationTime) = (CURDATE()- INTERVAL ".$days." DAY))";
        $rows = $dbHandle->query($sql)->result_array();

        $tagsFromFollowTable = array();
        foreach ($rows as $value) {
            $tagsFromFollowTable[] = $value['entityId'];
        }
        
        $sql = "SELECT distinct tag_id FROM tags_content_mapping WHERE (DATE(creationTime) = (CURDATE()- INTERVAL ".$days." DAY) OR DATE(modificationTime) = (CURDATE()- INTERVAL ".$days." DAY))";
        $rows = $dbHandle->query($sql)->result_array();

        $tagsFromContentMapTable = array();
        foreach ($rows as $value) {
            $tagsFromContentMapTable[] = $value['tag_id'];
        }

        $result = array("tagsFromFollowTable" => $tagsFromFollowTable, "tagsFromContentMapTable" => $tagsFromContentMapTable);

        return $result;
    }

    function getTagsTopContributors($tagId){
        $dbHandle = $this->getReadHandle();
        $fromDate = date("Y-m-d", strtotime("-7 days"));
        $sql = "SELECT DISTINCT m.userId, count(*) cc FROM messageTable m, tags_content_mapping tc WHERE m.threadId = tc.content_id AND tc.tag_id = ? AND tc.status = 'live' AND m.status IN ('live','closed') AND ((m.parentId = m.threadId AND m.fromOthers = 'user') OR (m.fromOthers='discussion' AND m.mainAnswerId = m.parentId)) AND m.creationDate >= '$fromDate' GROUP BY m.userId ORDER BY cc DESC LIMIT 0,10";
            $rowsUsers = $dbHandle->query($sql, array($tagId))->result_array();

        $userIds = array();
        foreach ($rowsUsers as $row){
            $userIds[] = $row['userId'];
        }

        return $userIds;
    }

    function getTagsFilterByEntity($allowedTagEntitiesArray){

        if(empty($allowedTagEntitiesArray))
            return array();

        $dbHandle = $this->getReadHandle();
        $result = array();
        //$allowedTagEntitiesArray = implode("', '", $allowedTagEntitiesArray);
        $sql = "SELECT id FROM tags WHERE tag_entity IN (?) AND tags != 'India'";
        $rows = $dbHandle->query($sql, array($allowedTagEntitiesArray))->result_array();

        foreach ($rows as $row){
            $result[] = $row['id'];
        }

        return $result;   
    }

    function getInactiveUsers($days = 5){
        $dbHandle = $this->getReadHandle();

        $sql       = "SELECT `userId`, DATEDIFF(now(), `lastActivityTime`) as days_inactivity FROM `appUserLastActivity` WHERE DATEDIFF(now(), `lastActivityTime`) >= ?";
        $rowsUsers = $dbHandle->query($sql, array($days))->result_array();

        $userIds = array();
        foreach ($rowsUsers as $row){
            $userIds[$row['userId']] = $row['days_inactivity'];
        }

        return $userIds;
    }

    function getQuestionCountForTags($tagIds, $daysInactivity = 5, $minThreadCount = 0){

        $result = array();
        if(empty($tagIds))
            return $result;

        $dbHandle = $this->getReadHandle();

        $havingClause = "";
        if($minThreadCount){
            $havingClause = " HAVING count >= ".$minThreadCount;
        }

        $sql       =   "SELECT t.tag_id, COUNT( * ) AS count ".
                       "FROM messageTable m, tags_content_mapping t ".
                       "WHERE m.msgId = t.content_id ".
                       "AND t.status =  'live' ".
                       "AND m.status ".
                       "IN ('live',  'closed') ".
                       "AND t.content_type =  'question' ".
                       "AND t.tag_id IN ( ".implode(",", $tagIds)." )  ".
                       "AND m.parentId =0 ".
                       "AND m.msgCount > 0 ".
                       "AND m.fromOthers =  'user' ".
                       "AND DATEDIFF(now(), m.creationDate) <= ? ".
                       "GROUP BY t.tag_id".$havingClause;

        $rs = $dbHandle->query($sql, array($daysInactivity))->result_array();

        foreach ($rs as $row){
            $result[$row['tag_id']] = $row['count'];
        }

        return $result;        
    }

    function getUnAnsweredQuestionCountForTags($tagIds, $daysInactivity = 5, $minThreadCount = 0){

        $result = array();
        if(empty($tagIds))
            return $result;

        $dbHandle = $this->getReadHandle();

        $havingClause = "";
        if($minThreadCount){
            $havingClause = " HAVING count >= ".$minThreadCount;
        }

        $sql       = "  SELECT t.tag_id, COUNT( * ) AS count
                        FROM messageTable m, tags_content_mapping t
                        WHERE m.msgId = t.content_id
                        AND t.status =  'live'
                        AND m.status
                        IN ('live',  'closed')
                        AND t.content_type =  'question'
                        AND t.tag_id
                        IN ( ".implode(",", $tagIds)." ) 
                        AND m.parentId =0   
                        AND m.msgCount =0
                        AND m.fromOthers =  'user'
                        AND DATEDIFF(now(), m.creationDate) <= ?
                        GROUP BY t.tag_id".$havingClause;

        $rs = $dbHandle->query($sql, array($daysInactivity))->result_array();

        foreach ($rs as $row){
            $result[$row['tag_id']] = $row['count'];
        }

        return $result;        
    }

    function getDiscussionCountForTags($tagIds, $daysInactivity = 5, $minThreadCount = 0){

        $result = array();
        if(empty($tagIds))
            return $result;

        $dbHandle = $this->getReadHandle();

        $havingClause = "";
        if($minThreadCount){
            $havingClause = " HAVING count >= ".$minThreadCount;
        }

        $sql       = "  SELECT t.tag_id, COUNT( * ) AS count
                        FROM messageTable m, tags_content_mapping t
                        WHERE m.threadId = t.content_id
                        AND t.status =  'live'
                        AND m.status IN ('live',  'closed')
                        AND t.content_type =  'discussion'
                        AND t.tag_id IN (".implode(',', $tagIds).")
                        AND m.parentId = m.threadId
                        AND m.fromOthers =  'discussion'
                        AND DATEDIFF(now(), m.creationDate) <= ?
                        GROUP BY t.tag_id".$havingClause;

        $rs = $dbHandle->query($sql, array($daysInactivity))->result_array();

        foreach ($rs as $row){
            $result[$row['tag_id']] = $row['count'];
        }

        return $result;        
    }

    function getAnAUsersLevelId($userIds){

        if(empty($userIds))
            return array();
        $dbHandle = $this->getReadHandle();
        $data     = array();
        $queryCmd = "SELECT upsm.userId, upsm.levelId FROM userpointsystembymodule upsm WHERE upsm.userId IN (".implode(',', $userIds).") AND upsm.modulename = 'AnA'";

        $query  = $dbHandle->query($queryCmd);        
        $result = $query->result_array();

        foreach ($result as $value) {
            $data[$value['userId']] = $value['levelId'];
        }
        return $data;
    }

    function getPendingGCMNotifications(){

        $dbHandle = $this->getReadHandle();
        $data     = array();
        $queryCmd = "SELECT * FROM notificationsGCMQueue WHERE deliverStatus='unsent' ORDER BY id asc";

        $query  = $dbHandle->query($queryCmd);        
        $result = $query->result_array();

        foreach ($result as $value) {
            $data[] = $value;
        }
        return $data;   
    }

    function insertGCMNotification($details){


        if($details['userId']){
            $dbHandle                  = $this->getWriteHandle();
        
            $data = array();
            $data['notificationType']  = $details['notificationType'];
            $data['userId']            = $details['userId'];
            $data['title']             = $details['title'];
            $data['message']           = $details['message'];
            $data['primaryId']         = $details['primaryId'];
            $data['primaryIdType']     = $details['primaryIdType'];

            if(isset($details['secondaryData']) && $details['secondaryData'])
                $data['secondaryData'] = json_encode($details['secondaryData']);

            if(isset($details['secondaryDataId']) && $details['secondaryDataId'])
                $data['secondaryDataId'] = json_encode($details['secondaryDataId']);
            
            if(isset($details['dynamicFieldList']) && $details['dynamicFieldList'])
                $data['dynamicFieldList']   = json_encode($details['dynamicFieldList']);

            if(isset($details['trackingUrl']) && $details['trackingUrl'])
                $data['trackingUrl']       = $details['trackingUrl'];

            $data['landing_page']      = $details['landing_page'];
            $data['iconUrl']      = $details['iconUrl'] ? $details['iconUrl'] : 'D';
            
            $status                    = $dbHandle->insert('notificationsGCMQueue',$data);   
        }

         
    }

    function getFBFriendsOfUser($userId){

        $dbHandle = $this->getReadHandle();
        $data     = array();
        $queryCmd = "SELECT friendsShikshaUserId FROM facebookFriendsMapping WHERE userid=? AND status='live' AND !ISNULL(friendsShikshaUserId)";

        $query  = $dbHandle->query($queryCmd, array($userId));        
        $result = $query->result_array();

        foreach ($result as $value) {
            $data[] = $value['friendsShikshaUserId'];
        }
        return $data;   
    }

    public function getCronStatus($cronType){
        $dbHandle = $this->getReadHandle();
        
        $queryCmd = "SELECT * FROM cron_management
                     WHERE cron_type = ? AND
                     status = 'running'";

        $query = $dbHandle->query($queryCmd, array($cronType));
        $data  = array();
        $row   = $query->result();
        $data  = (array)$row[0];
        return $data;
    }

    public function startCron($cronType, $status = 'RUNNING', $comment = ""){
        $last_insert_id = null;
        $dbHandle = $this->getWriteHandle();
        
        $processId = getmypid();
        $queryCmd ="INSERT INTO cron_management
                    (pid, cron_type, status, comment)
                    values
                    (?, ?, ?, ?)";

        $query = $dbHandle->query($queryCmd, array($processId, $cronType, $status, $comment));
        $last_insert_id = $dbHandle->insert_id();
        return $last_insert_id;
    }

    public function updateCronStatus($cronId = NULL, $status = NULL){
        if($cronId != null && $status != null){
            $dbHandle = $this->getWriteHandle();
            
            $queryCmd ="UPDATE cron_management
                        SET status = ? , end_time = NOW()
                        WHERE id = ?";

            $query = $dbHandle->query($queryCmd, array($status, $cronId));
            return $query;
        }
    }

    public function updateCronAttempts($cronId = NULL, $newAttemptCount = NULL){
        if($cronId != null && $newAttemptCount != null){
            $dbHandle = $this->getWriteHandle();
            
            $queryCmd ="UPDATE cron_management
                        SET attempts = ? 
                        WHERE id = ?";
            $query = $dbHandle->query($queryCmd, array($newAttemptCount, $cronId));
            return $query;
        }
    }

    public function getThreadWithGivenTagType($threadType = 'question', $tagType = 'objective'){

        $dbHandle = $this->getReadHandle();
            
        $queryCmd ="select distinct(content_id) from tags_content_mapping where tag_type = '".$tagType."' and content_type = '".$threadType."' order by content_id asc";

        $result = $dbHandle->query($queryCmd)->result_array();

        $data = array();
        foreach ($result as $value) {
            $data[] = $value['content_id'];
        }
        return $data;
    }

    function fetchInstituteList(){
        $dbHandle = $this->getReadHandle();
      //  $sql = "select listing_type_id,listing_title from listings_main where listing_type='institute' and status='live'";
        $sql = "select distinct listing_type_id, listing_title from listings_main a, institute b where a.listing_type_id = b.institute_id and a.status='live' and b.status='live' and a.listing_type='institute' and b.institute_type NOT IN ('Department','Department_Virtual')";


        $result = $dbHandle->query($sql)->result_array();
        return $result;
    }

    function fetchTagsList(){
        $dbHandle = $this->getReadHandle();
        $sql = "select main_id,tags from tags where tag_entity IN('Colleges','Colleges synonym') and status = 'live'";

        $result = $dbHandle->query($sql)->result_array();
        $tagsList = array();

        foreach ($result as $key => $value) {
            $value['tags'] = trim(strtolower($value['tags']));
            $tagsList[$value['tags']] = $value['main_id'];
        }
        return $tagsList;
    }

    function createTagsListingMapping($data){
        $dbHandle  = $this->getWriteHandle();
        $dbHandle->insert_batch('tags_entity', $data);

    }

    function getAllAbusedUserList(){

        $dbHandle = $this->getReadHandle();
        $sql = "SELECT userId FROM `tuserflag` WHERE abused = '1'";

        $result = $dbHandle->query($sql)->result_array();
        $userList = array();

        foreach ($result as $key => $value) {
            $userList[] = $value['userId'];
        }
        return $userList;   
    }

    function updateUsersAbuseFlag($userIds){

        if(empty($userIds))
            return false;

        $dbHandle = $this->getWriteHandle();
        $sql = "update `tuserflag` set abused='1' WHERE userid IN (".implode(",", $userIds).")";

        $result = $dbHandle->query($sql);
    }

    function getUserWithFBPic($userIds){

        $dbHandle = $this->getReadHandle();

        if(empty($userIds))
            $sql = "SELECT userid, avtarimageurl FROM  tuser WHERE 1 AND avtarimageurl LIKE  'https://fbcdn%'";
        else
            $sql = "SELECT userid, avtarimageurl FROM  tuser WHERE 1 AND userid IN (".implode(",", $userIds).")";

        $result = $dbHandle->query($sql)->result_array();
        $userList = array();

        foreach ($result as $key => $value) {
            $userList[] = $value;
        }
        return $userList;      
    }

    function updateUserProfilePic($userId, $profilePic){

        if(empty($userId))
            return;

        $dbHandle = $this->getWriteHandle();
        
        $data['avtarimageurl'] = $profilePic;

        $dbHandle->where('userid',$userId);
        $status = $dbHandle->update('tuser',$data);

    }
    
    /**
     * This model function will update view count of msgIds.
     * If msgIds already not in database then it will insert one.
     * Therefore prior calling to this function ensure that valid msgIds are submitted
     */
    function updateViewCountOfThreads($msgIds = array()){
    	if(!is_array($msgIds)){
    		return FALSE;
    	}
    	$msgIds = array_filter($msgIds);
    	if(empty($msgIds)){
    		return FALSE;
    	}
    	
    	$dbHandle 		= $this->getWriteHandle();
    	$insertColumnSql= "UPDATE messageTable SET viewCount = CASE ";
    	$whereCaluseMsgIds = array();
    	$whenClause = "";
    	$counter	= 0;
    	foreach ($msgIds as $key => $value){
    		$whenClause .= " WHEN msgId = ".$key." THEN viewCount + ".$value;
    		$whereCaluseMsgIds[] = $key;
    		if(++$counter >= 5000){
    			$sql = $insertColumnSql.$whenClause." ELSE viewCount END WHERE msgId IN(".implode(",", $whereCaluseMsgIds).") ";
    			$sql .= "AND fromOthers = 'user' AND status IN ('live','closed')";
    			$dbHandle->query($sql);
    			error_log("ABHINAV@TEST : sql : ".$sql);
    			$counter = 0;
    			$sql = "";
    			$whereCaluseMsgIds = array();
    			$whenClause = "";
    		}
    	}
    	if($counter > 0){
    		$sql = $insertColumnSql.$whenClause." ELSE viewCount END WHERE msgId IN(".implode(",", $whereCaluseMsgIds).") ";
    		$sql .= "AND fromOthers = 'user' AND status IN ('live','closed')";
    		$dbHandle->query($sql);
    		error_log("ABHINAV@TEST : sql : ".$sql);
    	}
    	
    	return TRUE;
    }
    
    function insertThreadViewLogData($threadViewLogData = array()){
    	if(empty($threadViewLogData)){
    		return FALSE;
    	}
    	$insertValues	= array();
    	$dateTime		= date('Y-m-d H:i:s');
    	$dbHandle		= $this->getWriteHandle();
    	//$sql = "INSERT INTO threadViewTrackingLog (entityId, entityType, msgId, pageType, pageSource, viewTime)";
    	for($i=0; $i<count($threadViewLogData); $i++){
    		$threadViewLogData[$i]['viewTime'] = $dateTime;
    		$insertValues[] = $threadViewLogData[$i];
    		if($i > 0 && ($i) % 1000 == 0){
    			$dbHandle->insert_batch('threadViewTrackingLog',$insertValues);
    			$insertValues = array();
    		}
    	}
    	if(count($threadViewLogData) > 0){
    		$dbHandle->insert_batch('threadViewTrackingLog',$insertValues);
    		$insertValues = array();
    	}
    	return TRUE;
    }
    
    /* function getLiveClosedMessages($msgIds = array()){
    	if(!is_array($msgIds)){
    		return array();
    	}
    	$msgIds = array_filter($msgIds);
    	if(empty($msgIds)){
    		return array();
    	}
    	$arrayPointer	= 0;
    	$msgIdsForSql 	= array_splice($msgIds, $arrayPointer, 5000);
    	$result			= array();
    	while(TRUE){
    		$sql		=	"SELECT msgId FROM messageTable where status IN ('live','closed') AND fromOthers = 'user'"
    						." AND msgId IN(".implode(",", $msgIdsForSql).") ";
    		$resultSet	= $this->db->query($sql)->result_array();
    		foreach ($resultSet as $data){
    			$result[] = $data['msgId'];
    		}
    		$msgIdsForSql = array_splice($msgIds, ($arrayPointer + 5000),5000);
    		if(count($msgIdsForSql) > 0){
    			$arrayPointer += 5000;
    		}else{
    			break;
    		}
    	}
    	return $result;
    }*/

    function getTagAffinityOfUser($userId){

        if(empty($userId))
            return array();

        $dbHandle = $this->getReadHandle();

        $sql = "SELECT affinity FROM  tagAffinityForUser WHERE entityId = ? AND entityType = 'user' AND status='live'";
        
        $result = $dbHandle->query($sql, array((string)$userId))->result_array();
        $tagAffinityList = array();

        foreach ($result as $key => $value) {
            $tagAffinityList = $value['affinity'];
        }
        return $tagAffinityList;      
    }

    function getMostActiveAnAUsers($days = 7){

        $dbHandle = $this->getReadHandle();

        $userExclusionList = array(1130, 1156, 3895, 1014, 1020, 249142, 1045, 1318, 343294, 333758, 345634, 341660, 344301, 345646, 345642, 344016, 344227, 345632, 370572, 371307, 371451, 371380, 369633, 364773, 369446, 356573, 351009, 370533, 356504, 356441, 368464, 369661, 339598, 369214, 369687, 370418, 356444, 345634, 370543, 370471, 369596, 371346, 368423, 356620, 351011, 365579, 368466, 351005, 1130, 370454, 370523, 370372, 358765, 368594, 370398, 351002, 1156, 344301, 1045, 345646, 355361, 355354, 370560, 368460, 369443, 365245, 370440, 370480, 370561, 345642, 371471, 370525, 371393, 371410, 358820, 369506, 369662, 370552, 369466, 370461, 370570, 371415, 371337, 371325, 356437, 345632, 369460,762290,1544835,1459752,1478915);
        
        $fromDate = date("Y-m-d", strtotime("-".$days." days"));
        $sql = "SELECT SUM(pointvalue) cc, userId from userpointsystemlog use index (idx_timestamp) where userId NOT IN (".implode(',',$userExclusionList).") and module='AnA' and timestamp>='".$fromDate."' group by userId order by cc desc limit 0,10";
            $rowsUsers = $dbHandle->query($sql, array($tagId))->result_array();

        $activeUsers = array();
        foreach ($rowsUsers as $row){
            $activeUsers[$row['userId']] = $row['cc'];
        }

        return $activeUsers;
        
    }

	 public function fetchTagsData($threadIdsArray){

        $result = array();
        if(!empty($threadIdsArray)){
            $threadIdsArray = array_unique(array_filter($threadIdsArray));
            $dbHandle = $this->getReadHandle();
            $sql = "SELECT group_concat(tag_id) as tagsIds,content_id from tags_content_mapping where content_id IN(".implode(",", $threadIdsArray).") AND status = 'live' GROUP BY content_id";

            $result = $dbHandle->query($sql)->result_array();
        }
        return $result;
    }

    public function insertMessageStreamMapping($data){
        $dbHandle = $this->getWriteHandle();
        $dbHandle->insert_batch('messageTagsTable',$data);
    }

    public function fetchThreadIds(){
        $dbHandle = $this->getReadHandle();
        $sql = "select distinct threadId from messageTable order by threadId desc limit 100000";
        $query = $dbHandle->query($sql);
        $result = $query->result_array();
        $finalResult = array();
        foreach ($result as $key => $value) {
            $finalResult[$value['threadId']] = 'post';
        }
        return $finalResult;
    }

    public function deleteMessageStreamMapping($data){
        $dbHandle = $this->getWriteHandle();
        $data = array_filter(array_unique($data));
        if(!empty($data)){
            $sql = "UPDATE messageTagsTable set status = 'deleted' where threadId IN(".implode(",", $data).") ";
            $dbHandle->query($sql);
        }
    }

}


