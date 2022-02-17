<?php

class anaRecommendationModel extends MY_Model {
	
	private $dbHandle = '';
	
	function __construct(){
		parent::__construct('ContentRecommendation');
		$this->dbHandle = $this->getReadHandle();
		$this->dbWriteHandle = $this->getWriteHandle();
		$this->load->helper('ContentRecommendation/recommend');
	}

	public function getRecommendedInstituteAnA($instituteId,$exclusionList=array(),$contentType='question'){
		if(empty($contentType) || !in_array($contentType, array('discussion','question'))){
			return array();
		}
		if(!is_array($instituteId) && $instituteId != '' && $instituteId > 0){
			$instituteId = array($instituteId);
		}
		$instituteId = array_filter($instituteId);
		if(count($instituteId) <= 0 || !is_array($instituteId)){
			return array();
		}
		
		$instituteId = array_unique($instituteId);
		$this->dbHandle->select('instituteId,AnARecommendation.msgId,lastActivity,isCA,tagCount,instituteTagType,threadQuality');
		$this->dbHandle->from('AnARecommendation');
		$this->dbHandle->where('instituteId in ('.implode(",", $instituteId).')');
		$this->dbHandle->where('contentType',$contentType);
		$this->dbHandle->where("status in ('live','closed')");
		
		$exclusionClause = getExclusionClause($exclusionList,'AnARecommendation.msgId');
		if($exclusionClause!=''){
			$this->dbHandle->where($exclusionClause);
		}
		$this->dbHandle->join('messageTable', 'AnARecommendation.msgId=messageTable.msgId');
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$content = array();
		foreach ($result as $value) {
			$temprow = array();
			$temprow['threadRecentTime'] = $value['lastActivity'];
			$temprow['CA'] = $value['isCA'];
			$temprow['tagCount'] = $value['tagCount'];
			$temprow['tagType'] = $value['instituteTagType'];
			$temprow['threadQualityScore'] = $value['threadQuality'];
			$content[$value['instituteId']][$value['msgId']] = $temprow;
		}
		return $content;
	}

	public function getRecommendedInstituteAnaByFactor($instituteIds,$exclusionList=array(),$count=5,$offset=0,$contentType='question',$orderByFactor=null){
		
		if(!is_array($instituteIds) && $instituteIds != '' && $instituteIds > 0){
			$instituteIds = array($instituteIds);
		}
		$instituteIds = array_filter($instituteIds);
		if(count($instituteIds) <= 0 || !is_array($instituteIds)){
			return array();
		}
		$instituteIds = array_unique($instituteIds);
		if(empty($contentType) || !in_array($contentType, array('discussion','question'))){
			return array();
		}
		
		$orderByClause = $this->_getOrderbyClause($orderByFactor);
    	if($orderByClause!=''){
    		$this->dbHandle->order_by($orderByClause);
    	}
		
		$this->dbHandle->distinct();
		$this->dbHandle->select('AnARecommendation.msgId');
		$this->dbHandle->from('AnARecommendation');
		$this->dbHandle->join('messageTable', 'AnARecommendation.msgId=messageTable.msgId');
		$this->dbHandle->where('AnARecommendation.instituteId in '. "(".implode(",", $instituteIds).")");
		$this->dbHandle->where("messageTable.status in ('live','closed')");
		$this->dbHandle->where('AnARecommendation.contentType',$contentType);
		

		$exclusionClause = getExclusionClause($exclusionList,'AnARecommendation.msgId');
		if($exclusionClause!=''){
			$this->dbHandle->where($exclusionClause);
		}

		$this->dbHandle->limit($count,$offset);
		
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$messageIds = array();
		foreach ($result as $row) {
			$messageIds[]=$row['msgId'];
		}
		
		//fetch total count
		$selectClause = 'count(distinct AnARecommendation.msgId) as cnt';
		$this->dbHandle->select($selectClause);
		$this->dbHandle->from('AnARecommendation');
		$this->dbHandle->join('messageTable', 'AnARecommendation.msgId=messageTable.msgId');
		$this->dbHandle->where('AnARecommendation.instituteId in '. "(".implode(",", $instituteIds).")");
		$this->dbHandle->where("messageTable.status in ('live','closed')");
		$this->dbHandle->where('AnARecommendation.contentType',$contentType);
		
		$exclusionClause = getExclusionClause($exclusionList,'AnARecommendation.msgId');
		if($exclusionClause!=''){
			$this->dbHandle->where($exclusionClause);
		}
		
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$response=array();
		if($messageIds!=null && count($result[0]['cnt'])>0){
			$response=array('topContent'=>$messageIds,'numFound'=>$result[0]['cnt']);
		}
		
		return $response;
	}

	public function checkContentExistForInstitute($instituteId,$contentType='question'){
		if(empty($contentType) || !in_array($contentType, array('discussion','question'))){
			return array();
		}
		if(!is_array($instituteId) && $instituteId != '' && $instituteId > 0){
			$instituteId = array($instituteId);
		}
		$instituteId = array_filter($instituteId);
		if(count($instituteId) <= 0 || !is_array($instituteId)){
			return array();
		}
		$instituteId = array_unique($instituteId);
		
		$this->dbHandle->distinct();
		$this->dbHandle->select('instituteId');
		$this->dbHandle->from('AnARecommendation');
		$this->dbHandle->where('instituteId in ('.implode(",", $instituteId).')');
		$this->dbHandle->where('contentType',$contentType);
		$this->dbHandle->where("status in ('live','closed')");
		
		$this->dbHandle->join('messageTable', 'AnARecommendation.msgId=messageTable.msgId');
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$content = array();
		foreach ($result as $value) {
			$content[] = $value['instituteId'];
		}
		return $content;
	}

	public function deleteInstituteAnARecommendation($startTime,$endTime){
		
		$sql = "DELETE FROM newtab USING AnARecommendation newtab,
		tags_entity te,
		tags_content_mapping tcm 
		WHERE
		te.tag_id = tcm.tag_id
		AND te.status = 'live'
		AND te.entity_type in ('institute','National-University')
		AND tcm.status = 'deleted'
		AND (tcm.modificationTime BETWEEN ? AND ?)
		AND newtab.instituteId = te.entity_id
		AND newtab.msgId = tcm.content_id;";
		
		$result = $this->dbWriteHandle->query($sql, array($startTime,$endTime));
		return $result;
	}

    /*
    * Function to get contentId whose tags/tagType have been modified $interval Minutes before $timestamp    
    */
	public function getNewlyTagged($startTime,$endTime){
		
		$sql = "SELECT 
		distinct content_id
		FROM
		tags_content_mapping tcm
		JOIN
		tags_entity te ON tcm.tag_id = te.tag_id
		WHERE
		te.status = 'live'
		AND te.entity_type in ('institute','National-University')
		AND tcm.status = 'live'
		AND (tcm.creationTime BETWEEN ? AND ?)";
		
		$result = $this->dbHandle->query($sql, array($startTime,$endTime));
		$result = $result->result_array();

		$contentId = array();
		foreach ($result as $value) {
			$contentId[] = $value['content_id'];
		}

		return $contentId;
	}

    /*
    * Function to get questionId which have been answered/commented $interval Minutes before $timestamp
    * for lastActivity, CA in AnARecommendationTable Queue    
    */
	public function getNewlyAnswered($startTime,$endTime){
		
		$sql = "SELECT DISTINCT
		tcm.content_id
		FROM
		tags_entity te
		JOIN
		tags_content_mapping tcm ON te.tag_id = tcm.tag_id
		JOIN
		messageTable mt ON tcm.content_id = mt.threadId
		WHERE
		te.status = 'live'
		AND te.entity_type in ('institute','National-University')
		AND tcm.status = 'live'
		AND (mt.creationDate BETWEEN ? AND ?)
		AND mt.status IN ('live' , 'closed')";

		$result = $this->dbHandle->query($sql, array($startTime,$endTime));
		$result = $result->result_array();

		$threadId = array();
		foreach ($result as $value) {
			$threadId[] = $value['content_id'];
		}
		return $threadId;
		}
		
	/*
    * Function to get contentId whose thread Quality have been modified $interval Minutes before $timestamp 
    */
	public function getUpdatedThreads($startTime,$endTime){
		$sql = "SELECT 
		distinct tqt.threadId
		FROM
		tags_entity te
		JOIN
		tags_content_mapping tcm ON te.tag_id = tcm.tag_id
		JOIN
		threadQualityTable tqt ON tcm.content_id = tqt.threadId
		WHERE
		te.status = 'live'
		AND te.entity_type in ('institute','National-University')
		AND tcm.status = 'live'
		AND tqt.lastUpdate >= ? AND tqt.lastUpdate < ?";

		$result = $this->dbHandle->query($sql, array($startTime,$endTime));
		$result = $result->result_array();

		$threadId = array();
		foreach ($result as $value) {
			$threadId[] = $value['threadId'];
		}
		return $threadId;
	}

	//fetch phase
	public function getInstituteAndTagType($msgId){
		
		if(!is_array($msgId) && $msgId != '' && $msgId > 0){
			$msgId = array($msgId);
		}
		$msgId = array_filter($msgId);
		if(count($msgId) <= 0 || !is_array($msgId)){
			return array();
		}

		$sql = "SELECT 
		distinct te.entity_id,tcm.content_id,tcm.tag_type
		FROM
		tags_content_mapping tcm
		JOIN
		tags_entity te ON tcm.tag_id = te.tag_id
		WHERE
		te.status = 'live'
		AND te.entity_type in ('institute','National-University')
		AND tcm.status = 'live'
		AND tcm.content_id in (?)";
		
		$query = $this->dbHandle->query($sql, array($msgId));
		$result = $query->result_array();
		
		$contentId = array();
		foreach ($result as $value) {
			$contentId[$value['content_id']][$value['entity_id']] = $value['tag_type'];
		}

		return $contentId;
	}

	public function filterContentByStatus($contentId){

		if(!is_array($contentId) && $contentId != '' && $contentId > 0){
			$contentId = array($contentId);
		}
		$contentId = array_filter($contentId);
		if(count($contentId) <= 0 || !is_array($contentId)){
			return array();
		}
		$questionMsgId=array();
		$discussionMsgId=array();
		$msgId=array();

		$sql="select msgId,threadId,fromOthers from messageTable where status in ('live','closed') and fromOthers = 'user' and parentId = 0 and msgCount>0 and msgId in (?) ";

		$result = $this->dbHandle->query($sql, array($contentId));
		
		foreach ($result->result_array() as $value) {
			$questionMsgId[$value['threadId']] = array('threadId'=>$value['threadId'],'contentType'=>$value['fromOthers']); 	
		} 
		
		$sql="select msgId,threadId,fromOthers from messageTable where status in ('live','closed') and  fromOthers = 'discussion' and parentId = threadId and threadId in (?)";

		$result = $this->dbHandle->query($sql, array($contentId));
		
		foreach ($result->result_array() as $value) {
			$discussionMsgId[$value['threadId']] = array('threadId'=>$value['threadId'],'contentType'=>$value['fromOthers']); 	
		} 
		
		$msgId = $questionMsgId + $discussionMsgId;
		return $msgId;
	}

	public function getThreadQuality($msgId){

		if(!is_array($msgId) && $msgId != '' && $msgId > 0){
			$msgId = array($msgId);
		}
		$msgId = array_filter($msgId);
		if(count($msgId) <= 0 || !is_array($msgId)){
			return array();
		}
		
		$sql = "select threadId, qualityScore from threadQualityTable where threadId in (?)";
		//where threadType
		$content = array();
		$result = $this->dbHandle->query($sql, array($msgId));
		foreach ($result->result_array() as $value) {
			$content[$value['threadId']] = $value['qualityScore']; 	
		} 
		return $content;
	}

	public function getTimeOfRecentContentOnThreads($threadIds = array()){
		if(empty($threadIds)){
			return array();
		}
		if(!is_array($threadIds) && $threadIds != '' && $threadIds > 0){
			$threadIds = array($threadIds);
		}
		$threadIds = array_filter($threadIds);
		if(count($threadIds) <= 0 || !is_array($threadIds)){
			return array();
		}
		$result = array();
		$sql    = "SELECT threadId, MAX(creationDate) AS creationDate FROM messageTable"
		. " WHERE status IN ('live','closed') AND fromOthers in ('user','discussion') ";
		$sql    .= " AND threadId IN (?) GROUP BY 1";

		$resultSet  = $this->dbHandle->query($sql, array($threadIds))->result_array();
		$result = array();
		foreach($resultSet as $data){
			$result[$data['threadId']] = $data['creationDate'];
		}
		return $result;
	}

	public function getThreadTagTypeCount($msgId){

		if(!is_array($msgId) && $msgId != '' && $msgId > 0){
			$msgId = array($msgId);
		}
		$msgId = array_filter($msgId);
		if(count($msgId) <= 0 || !is_array($msgId)){
			return array();
		}
		
		$sql = "select content_id,tag_type,count(*) as cnt from tags_content_mapping where status ='live' and content_id in (?) group by content_id,tag_type";
		
		$result = $this->dbHandle->query($sql, array($msgId));
		$content = array();
		foreach ($result->result_array() as $value) {
			$content[$value['content_id']][$value['tag_type']] = $value['cnt']; 	
		} 
		return $content;
	}

	public function getThreadCAFlag($threadId){

		if(!is_array($threadId) && $threadId != '' && $threadId > 0){
			$threadId = array($threadId);
		}
		$threadId = array_filter($threadId);
		if(count($threadId) <= 0 || !is_array($threadId)){
			return array();
		}
		
		$sql = "select distinct threadId from messageTable mt use index (threadId) join CA_ProfileTable cp on mt.userId=cp.userId where parentId=threadId and status in ('live','closed') and fromOthers = 'user' and profileStatus in ('accepted','removed') and threadId in (?)";
		
		$result = $this->dbHandle->query($sql, array($threadId));
		$caFlag = array();
		foreach ($result->result_array() as $row) {
			$caFlag[$row['threadId']] = 1; 	
		} 
		$response = array();
		foreach ($threadId as $value) {
			$response[$value] = $caFlag[$value]==1?1:0;
	}
		return $response;
	}

	public function updateAnARecommendation($data){
		if(!is_array($data)){
			return false;
		}
		if(count($data) == 0){
			return true;
		}

		$sql = "INSERT INTO `AnARecommendation` (
		`instituteId` ,
		`msgId` ,
		`contentType` ,
		`lastActivity` ,
		`isCA` ,
		`tagCount` ,
		`instituteTagType` ,
		`threadQuality`
		)
		VALUES ";
		foreach ($data as $key => $value) {
			if(!is_numeric($value['threadQualityScore'])){
				$value['threadQualityScore'] = 0;
			}
			$sql.="(".$value['instituteId'].",".$value['msgId'].",'".$value['contentType']."','".$value['threadRecentTime']."',".$value['CA'].",".$value['tagCount'].",'".$value['tagType']."',".$value['threadQualityScore']."),";
		}
		$sql = substr($sql, 0, -1);
		$sql.=" on duplicate key update lastActivity=values(lastActivity),isCA=values(isCA),tagCount=values(tagCount),instituteTagType=values(instituteTagType),threadQuality=values(threadQuality)";
		
		$query = $this->dbWriteHandle->query($sql);
		return $query;
	}

	public function getRecommendedCourseQuestions($courseId,$exclusionList=array()){

		if(!is_array($courseId) && $courseId != '' && $courseId > 0){
			$courseId = array($courseId);
		}
		$courseId = array_filter($courseId);
		if(count($courseId) <= 0 || !is_array($courseId)){
			return array();
		}
		
		$this->dbHandle->select('messageId,lastActivity,isCA,tagCount,instituteTagType,threadQuality');
		$this->dbHandle->from('questions_listing_response');
		$this->dbHandle->join('messageTable','questions_listing_response.messageId=messageTable.msgId');
		// For checking data sync because question is inserted into AnArecommendation table after cron delay.
		$this->dbHandle->join('AnARecommendation','questions_listing_response.messageId=AnARecommendation.msgId'); 
		$this->dbHandle->where('courseId in ('.implode(",", $courseId).')');
		$this->dbHandle->where("questions_listing_response.status",'live');
		$this->dbHandle->where("messageTable.status in ('live','closed')");
		
		$exclusionClause = getExclusionClause($exclusionList,'messageId');
		if($exclusionClause!=''){
			$this->dbHandle->where($exclusionClause);
		}

		$query = $this->dbHandle->get();
		$result = $query->result_array();

		$content = array();
		foreach ($result as $value) {
			$temprow = array();
			$temprow['threadRecentTime'] = $value['lastActivity'];
			$temprow['CA'] = $value['isCA'];
			$temprow['tagCount'] = $value['tagCount'];
			$temprow['tagType'] = $value['instituteTagType'];
			$temprow['threadQualityScore'] = $value['threadQuality'];
			$content[$value['messageId']] = $temprow;
		}
		return $content;
	}

	public function getRecommendedCourseQuestionsCount($courseIds){

		if(!is_array($courseIds) && $courseIds != '' && $courseIds > 0){
			$courseIds = array($courseIds);
		}

		if(!is_array($courseIds)){
			$courseIds = array($courseIds);
		}
		$courseIds = array_filter($courseIds);
		if(count($courseIds) <= 0 || !is_array($courseIds)){
			return array();
		}

		$this->dbHandle->select('courseId,count(distinct(AnARecommendation.msgId)) as cnt');
		$this->dbHandle->from('questions_listing_response');
		$this->dbHandle->join('messageTable','questions_listing_response.messageId=messageTable.msgId');
		// For checking data sync because question is inserted into AnArecommendation table after cron delay.
		$this->dbHandle->join('AnARecommendation','questions_listing_response.messageId=AnARecommendation.msgId'); 
		$this->dbHandle->where('courseId in ('.implode(",", $courseIds).')');
		$this->dbHandle->where("questions_listing_response.status",'live');
		$this->dbHandle->where("messageTable.status in ('live','closed')");
		$this->dbHandle->group_by('questions_listing_response.courseId');
		
		$query = $this->dbHandle->get();

		$result = $query->result_array();
		
		$finalResult = array();
		foreach ($result as $key => $value) {
			$finalResult[$value['courseId']] = $value['cnt'];
		}
		return $finalResult;
		
	}

	public function getInstituteAnaIds($instituteIds,$contentType){

		if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
		$this->dbHandle->distinct();
		$this->dbHandle->select('instituteId,AnARecommendation.msgId');
		$this->dbHandle->from('AnARecommendation');
		$this->dbHandle->where('instituteId in ('.implode(",", $instituteIds).')');
		$this->dbHandle->where('contentType',$contentType);
		$this->dbHandle->where("status in ('live','closed')");
		
		$this->dbHandle->join('messageTable', 'AnARecommendation.msgId=messageTable.msgId');
		
		$query = $this->dbHandle->get();
		$result = $query->result_array();
		
		$content = array();
		foreach ($result as $row) {
			$content[$row['instituteId']][] = $row['msgId'];
		}
		return $content;
	}

	public function checkContentExistForCourse($courseId){

		if(!is_array($courseId) && $courseId != '' && $courseId > 0){
			$courseId = array($courseId);
		}
		$courseId = array_filter($courseId);
		if(count($courseId) <= 0 || !is_array($courseId)){
			return array();
		}
		
		$this->dbHandle->distinct();
		$this->dbHandle->select('courseId');
		$this->dbHandle->from('questions_listing_response');
		$this->dbHandle->join('messageTable','questions_listing_response.messageId=messageTable.msgId');
		// For checking data sync because question is inserted into AnArecommendation table after cron delay.
		$this->dbHandle->join('AnARecommendation','questions_listing_response.messageId=AnARecommendation.msgId'); 
		$this->dbHandle->where('courseId in ('.implode(",", $courseId).')');
		$this->dbHandle->where("questions_listing_response.status",'live');
		$this->dbHandle->where("messageTable.status in ('live','closed')");
		
		$query = $this->dbHandle->get();
		$result = $query->result_array();

		$content = array();
		foreach ($result as $value) {
			$content[] = $value['courseId'];
		}
		return $content;
	}

	function getLastProcessedTimeWindow()
    {
		$query = $this->dbWriteHandle->query("select max(lastProcessedTimeWindow) as tmp from InstituteAna where status='success'");
		$row = $query->row();
		return $row->tmp;
	}
        
	function registerCron($lastWindow)
        {
		$startTime = date('Y-m-d H:i:s');
		$data = array('lastProcessedTimeWindow' => $lastWindow, 'startTime' => $startTime);
		
		if($this->dbWriteHandle->insert('InstituteAna',$data))
		{
			$cron_id = $this->dbWriteHandle->insert_id();
			return $cron_id;
		}
		else 
		{
			return false;
		}
        }
        
	function updateCron($cronId,$status)
	{
		if(empty($status) || !in_array($status, array('success','failed'))){
			return false;
    }
		$end_time = '0000-00-00 00:00:00';
		if($status == 'success'){
			$endTime = date('Y-m-d H:i:s');
		}
		
		$query = $this->dbWriteHandle->query("UPDATE InstituteAna
			SET status = ?,endTime = ?
			WHERE id = ? ", array($status, $endTime, $cronId));
		return $query;
	}

        public function checkContentExistForCourse_Unanswered($courseId){
                if(!is_array($courseId) && $courseId != '' && $courseId > 0){
                        $courseId = array($courseId);
                }
                $courseId = array_filter($courseId);
                if(count($courseId) <= 0 || !is_array($courseId)){
                        return array();
                }

                $this->dbHandle->distinct();
                $this->dbHandle->select('courseId');
                $this->dbHandle->from('questions_listing_response');
                $this->dbHandle->join('messageTable','questions_listing_response.messageId=messageTable.msgId');
                $this->dbHandle->where('courseId in ('.implode(",", $courseId).')');
                $this->dbHandle->where("questions_listing_response.status",'live');
                $this->dbHandle->where("messageTable.status in ('live','closed')");
                $this->dbHandle->where("messageTable.msgCount = 0");

                $query = $this->dbHandle->get();
                $result = $query->result_array();

                $content = array();
                foreach ($result as $value) {
                        $content[] = $value['courseId'];
                }
                return $content;
        }

        public function checkContentExistForInstitute_Unanswered($instituteId){
                //if(empty($contentType)){
                //        return array();
                //}
                if(!is_array($instituteId) && $instituteId != '' && $instituteId > 0){
                        $instituteId = array($instituteId);
                }
                $instituteId = array_filter($instituteId);
                if(count($instituteId) <= 0 || !is_array($instituteId)){
                        return array();
                }

                $sql = "SELECT distinct entity_id 
                FROM tags_content_mapping tcm, tags_entity te, messageTable m
                WHERE te.status = 'live' AND tcm.tag_id = te.tag_id AND m.parentId = 0 AND m.fromOthers='user' AND m.status IN ('live','closed') AND m.msgCount = 0 AND te.entity_type in ('institute','National-University') AND tcm.status = 'live' 
                AND te.entity_id IN (?) AND tcm.content_id = m.msgId AND tcm.content_type = 'question'";

                $result = $this->dbHandle->query($sql, array($instituteId))->result_array();

                $content = array();
                foreach ($result as $value) {
                        $content[] = $value['entity_id'];
                }
                return $content;
        }

      	public function updateInstituteIdAnARecommendation($allInstituteId,$newInstituteId){
      		$sql = "UPDATE IGNORE AnARecommendation 
		      		SET instituteId =? 
					WHERE
					instituteId in ($allInstituteId)
					AND contentType = 'question'";
			
			$result = $this->dbWriteHandle->query($sql,array($newInstituteId));
      	}

  	private function _getOrderbyClause($orderby){
        switch($orderby) {
            case 'THREAD_RECENCY':
                return 'AnARecommendation.lastActivity desc ';
            default:
                return '';
        }
    }

	public function getAllQuestionBasedOnTag($instituteIds, $start, $count){
            if(!is_array($instituteIds) && $instituteIds != '' && $instituteIds > 0){
                    $instituteIds = array($instituteIds);
            }
            $instituteIds = array_filter($instituteIds);
            if(count($instituteIds) <= 0 || !is_array($instituteIds)){
                    return array();
            }

            $sql = "SELECT SQL_CALC_FOUND_ROWS 
            distinct msgId, te.entity_id
            FROM
            tags_content_mapping tcm JOIN tags_entity te  ON (tcm.tag_id = te.tag_id) JOIN messageTable m ON (tcm.content_id = m.msgId)  
            WHERE
            te.status = 'live' AND m.parentId = 0 AND m.fromOthers='user' AND m.status IN ('live','closed') AND te.entity_type in ('institute','National-University') AND tcm.status = 'live' AND te.entity_id IN (?) AND tcm.content_type = 'question' ORDER BY msgId DESC LIMIT $start, $count";

            $resultSet = $this->dbHandle->query($sql, array($instituteIds))->result_array();

            $finalArray = array();
            foreach ($resultSet as $entry){
            	$msgIds[] = $entry['msgId'];
            	$listingIds[] = $entry['entity_id'];
                $finalArray[$entry['msgId']]= array('msgId'=>$entry['msgId'],'listing_id'=>$entry['entity_id']);
                    
            }
            
	        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        	$query = $this->dbHandle->query($queryCmd);
	        $totalRows = 0;
        	foreach ($query->result() as $row) {
	            $totalRows = $row->totalRows;
        	}

        	if(!empty($msgIds)){
        		$sql = "select courseId ,messageId,instituteId FROM questions_listing_response where messageId in (?)";

	        	$result = $this->dbHandle->query($sql, array($msgIds))->result_array();

	        	foreach($result as $val){
	        		$courseIds[] = $val['courseId']; 
	        		$finalArray[$val['messageId']]['courseId'] = $val['courseId'];

	        	}

	       	$uniqueCourseIds = array_unique($courseIds);

        	}
        	
        	$uniqueInstituteIds = array_unique($listingIds);

        	if(!empty($uniqueInstituteIds)){
        		$this->load->builder("nationalInstitute/InstituteBuilder");
        		$instituteBuilder = new InstituteBuilder();
        		$this->instituteRepo = $instituteBuilder->getInstituteRepository();       
        		$instituteObj = $this->instituteRepo->findMultiple($uniqueInstituteIds,array('basic'),true);
        		foreach ($instituteObj as $instKey => $instValue){
                    $instituteId = $instValue->getId();
                    $instituteName = $instValue->getName();
                    $mainLocationObj = $instValue->getMainLocation();
                    if(!empty($mainLocationObj)){
			            $main_location = $mainLocationObj->getCityName();
			        }
                    $instDetail[$instituteId] = array('listing_title' =>$instituteName,'locationOfInstitute'=>$main_location);
                }
        	}

        	/*if(!empty($uniqueCourseIds)){
        		$this->load->builder("nationalCourse/CourseBuilder");
        		$courseBuilder = new CourseBuilder();
        		$this->courseRepo = $courseBuilder->getCourseRepository();   
        		$courseObj = $this->courseRepo->findMultiple($uniqueInstituteIds,array('basic'),false);
        		foreach ($courseObj as $courseKey => $courseValue){
                    $courseId = $courseValue->getId();
                    $courseName = $courseValue->getName();
                    $instituteName = $courseValue->getInstituteName();

                    $courseDetail[$courseId] = array('course_title' => $courseName, 'listing_title' =>$instituteName,'locationOfInstitute'=>'');
                }
        	}*/
        	if(!empty($uniqueCourseIds)){
        		$sql = "select course_id,name from shiksha_courses where course_id in (?)";

        		$result = $this->dbHandle->query($sql, array($uniqueCourseIds))->result_array();

        		foreach($result as $key=>$val){
        			$courseDetail[$val['course_id']] = $val['name'];

        		}
        	}

        	foreach($resultSet as $entry){
        			$finalArray[$entry['msgId']]['course_title'] = $courseDetail[$finalArray[$entry['msgId']]['courseId']];
        			$finalArray[$entry['msgId']]['listing_title'] = $instDetail[$entry['entity_id']]['listing_title'];
        			$finalArray[$entry['msgId']]['locationOfInstitute'] = $instDetail[$entry['entity_id']]['locationOfInstitute'];
        	}

            return array('topContent'=>$finalArray,
		     'numFound'=>$totalRows);
	}

}
?>
