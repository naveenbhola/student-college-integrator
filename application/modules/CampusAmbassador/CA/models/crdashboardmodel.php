<?php
class CRDashboardModel extends MY_Model
{ 
	private $dbHandle = '';
	var $cacheLib;
	function __construct(){
		parent::__construct('CampusAmbassador');
	}
	/**
	 * returns a data base handler object
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
	
	function getOtherCourse($userId)
	{
		$this->initiateModel('read');	
		$query = "SELECT GROUP_CONCAT( otc.courseId ) AS totalOtherCourseId
			FROM CA_MainCourseMappingTable AS cm, CA_OtherCourseDetails AS otc, `CA_ProfileTable` AS c
			WHERE cm.id = otc.mappingCAId
			AND cm.status = 'live'
			AND otc.status = 'live'
			AND c.userId = ?
			AND c.profileStatus = 'accepted'
			AND c.id = cm.caId";
			$query = $this->dbHandle->query($query,array($userId));
			$coursesId = $query->result();
		    return $coursesId[0]->totalOtherCourseId;
	}
	
	function getAllCourseIdFromCR($userId)
	{
		$this->initiateModel('read');
		
		$other = $this->getOtherCourse($userId);
		
		if(isset($other) && $other !=''){
			$allOther = ','.$other;
		}
		
		$query = "SELECT concat(GROUP_CONCAT( cm.courseId ),',',c.officialCourseId) AS totalCourseId ,c.officialCourseId, c.id AS CampusRepId
			FROM CA_MainCourseMappingTable AS cm, `CA_ProfileTable` AS c
			WHERE cm.caId = c.id
			AND cm.status = 'live'
			AND c.userId = ?
			AND c.profileStatus = 'accepted'
			AND c.id = cm.caId";
			$query = $this->dbHandle->query($query,array($userId));
			$coursesId = $query->result();
		        return $coursesId[0]->totalCourseId . $allOther;
	}
	
	
	function getUnansweredQuestionsFromDb($userId,$coursesId,$page_number = 0, $item_per_page = 10)
	{
	        $this->initiateModel('read');
		
		$position = ($page_number * $item_per_page);
		
		$query = "SELECT SQL_CALC_FOUND_ROWS mt.msgId,mt.creationDate,mt.userId,mt.msgTxt,mt.status,qlr.courseId,qlr.instituteId, ifnull ((select msgId from messageTable mA where mA.fromOthers='user' and mA.mainAnswerId=0 and mA.threadId=mt.msgId and mA.userId = ? limit 1),0) isAlreadyAnswered,tu.displayname as `PostedBy`
			FROM messageTable mt
			JOIN questions_listing_response qlr ON ( mt.msgId = qlr.messageId )
			JOIN tuser tu ON ( mt.userId = tu.userid )
			JOIN CA_ProfileTable ca ON (ca.userId = ?)
			WHERE qlr.courseId in ($coursesId)   
			AND qlr.status = 'live'
			AND mt.status = 'live'
			AND mt.parentId = 0
			AND mt.fromOthers = 'user'
			AND mt.mainAnswerId = -1 
			AND mt.creationDate >= ca.modificationDate
			AND ca.profileStatus = 'accepted'
			AND ca.userId != mt.userId
			having isAlreadyAnswered = 0
			ORDER BY `mt`.`msgId` DESC
			LIMIT $position, $item_per_page";
			
			$query = $this->dbHandle->query($query,array($userId,$userId));
			$result_array["result"] =  $query->result();
			
			$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
			$queryTotal = $this->dbHandle->query($queryCmdTotal);
			$queryResults = $queryTotal->result();
			$totalRows = $queryResults[0]->totalRows;
			$result_array["total"] = $totalRows;
			
			return $result_array;
	}

		
	function getApprovedAnswersFromDb($userId,$courseId,$page_number = 0, $item_per_page = 10,$status1,$status2,$status3)
	{
     
             $this->initiateModel('read');
             $position = ($page_number * $item_per_page);
             if($status3==''){
               $status="ast.status in ('$status1','$status2')";
             }else{
                $status="ast.status = ?";
             }
             $query = "SELECT 
mt.msgId,mt.creationDate,mt.userId,mt.msgTxt,mt.status,m.userId,m.msgId as answerId,m.msgTXT as Answer,m.creationDate as answerCreationDate,qlr.courseId,qlr.instituteId,ast.answerId,ast.status as answer_status, ast.reason,
ifnull ((select msgId from messageTable mA where mA.fromOthers='user' 
and mA.mainAnswerId=0 and mA.threadId=mt.msgId and mA.userId=? limit 1),0) 
isAlreadyAnswered,IFNULL((select fac.isFeatured from featuredAnswersCampusRep fac where m.fromOthers='user' and m.mainAnswerId = 0 and m.threadId=mt.msgId and fac.msgId = m.msgId and fac.isFeatured limit 0,1),0) isFeatured,tu.displayname as `PostedBy`
               FROM messageTable mt, messageTable m, questions_listing_response qlr, tuser tu, CA_AnswerStatusTable ast, CA_ProfileTable ca
               WHERE qlr.courseId in ($courseId)
               AND qlr.status = 'live'
               AND mt.status = 'live'
               AND m.status='live'
               AND mt.parentId = 0
               AND mt.fromOthers = 'user'
               AND mt.userId = tu.userid
               AND m.userId = ?
               AND ca.userId = ?
               AND mt.creationDate >= ca.modificationDate
	           AND ca.profileStatus = 'accepted'
               AND mt.msgId = qlr.messageId
               AND m.parentId=mt.msgId
               AND m.msgId=ast.answerId
               AND $status
               having isAlreadyAnswered != 0
               ORDER BY `mt`.`msgId` DESC
               LIMIT $position, $item_per_page";
               $query = $this->dbHandle->query($query, array($userId,$userId,$userId,$status3));
               return $query->result();
        }
     
	function getCRAnswerComment($answerId)
	{
          $this->initiateModel('read');
          if($answerId !=''){
          
          $query="SELECT m.msgId,m.msgTXT,m.parentId,m.creationDate,m.userId,tu.displayname from messageTable m ,tuser tu where m.parentId in (".$this->dbHandle->escape_str($answerId).") AND m.status='live' AND m.userId=tu.userId ORDER BY `m`.`msgId` ASC";
          $result = $this->dbHandle->query($query)->result_array();
          
          $answerComments = array();
          if(count($result)) {
               foreach($result as $row) {
                    $answerComments[$row['parentId']][] = $row;
               }
          }
          }
          return $answerComments;
	}
	
	function getCRProfileInfo($userId)
	{
		$this->initiateModel('read');
		$query = "SELECT c.displayName, c.imageURL,c.modificationDate as acceptedDate,cm.courseId AS mainCourseId,c.officialCourseId
			FROM CA_MainCourseMappingTable AS cm, `CA_ProfileTable` AS c
			WHERE c.id = cm.caId
			AND cm.status = 'live'
			AND c.userId = ? 
			AND c.profileStatus = 'accepted'";
			$query = $this->dbHandle->query($query,array($userId));	
			$result = $query->result();

			$i = 0;
			foreach($result as $value){
				$result[$i]->imageURL = addingDomainNameToUrl(array('url' =>$value->imageURL, 'domainName' => MEDIA_SERVER ));
				$i++;
			}

			return $result;
	}
	
	function getTotalDigAnser($userId)
	{
		$this->initiateModel('read');
		$query = "SELECT count( dg.productId ) AS totalDig
			FROM digUpUserMap dg, messageTable mt
			WHERE mt.msgId = dg.productId
			AND dg.digFlag =1
			AND dg.product = 'qna'
			AND dg.digUpStatus = 'live' 
			AND mt.userId = ? 
			AND mt.status = 'live'
			AND mt.mainAnswerId = 0
			AND mt.fromOthers = 'user'";
			$query = $this->dbHandle->query($query,array($userId));	
			return $query->result();
	}
	
	function getTotalApprovedAnswer($userId,$coursesId,$status1,$status2,$status3)
	{
		$this->initiateModel('read');
		if($coursesId == ''){
			return array();
		}
                if($status3==''){
                    $status="ast.status in ('$status1','$status2')";
                }else{
                    $status="ast.status = ? ";
                }
		$query = "SELECT count(mt.msgId) as totalAnswer,ifnull ((select msgId from messageTable mA where mA.fromOthers='user'
and mA.mainAnswerId=0 and mA.threadId=mt.msgId and mA.userId=? limit 1),0)
isAlreadyAnswered FROM messageTable mt, messageTable m,questions_listing_response qlr, CA_AnswerStatusTable ast,CA_ProfileTable ca
			WHERE qlr.courseId in ($coursesId)
			AND qlr.status = 'live'
			AND mt.status = 'live'
			AND m.status='live'
			AND mt.parentId = 0
			AND mt.fromOthers = 'user'
			AND m.userId = ?
			AND ca.userId = ?
			AND mt.creationDate >= ca.modificationDate
			AND ca.profileStatus = 'accepted'
			AND mt.msgId = qlr.messageId
			AND m.parentId=mt.msgId
                        AND m.msgId=ast.answerId
                        AND $status
			having isAlreadyAnswered != 0
			ORDER BY `mt`.`msgId` DESC";
			$query = $this->dbHandle->query($query,array($userId,$userId,$userId,$status3));	
			return $query->result();
	}
	
	function checkValidUser($userId)
	{
		$this->initiateModel('read');
		$query = "SELECT c.id,c.userId,c.modificationDate as acceptedDate,c.displayName
			FROM `CA_ProfileTable` AS c
			WHERE c.userId = ? 
			AND c.profileStatus = 'accepted'";
			$query = $this->dbHandle->query($query,array($userId));	
			return $query->result();
	}
	
	function getAverageAnswerTime($userId)
	{
		$this->initiateModel('read');
                $this->load->library('Common/cacheLib');
                $this->cacheLib = new cacheLib();
		$key = "averageAnswerTimeCampusRep_".$userId;		
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$coursesId = $this->getAllCourseIdFromCR($userId);
			$query = "SELECT
				mt.msgId,mt.creationDate,mt.msgTxt,m.msgId as answerId,m.msgTXT as Answer,m.creationDate as 
				answerCreationDate,qlr.courseId,qlr.instituteId,
				ifnull ((select msgId from messageTable mA where mA.fromOthers='user'
				and mA.mainAnswerId=0 and mA.threadId=mt.msgId and mA.userId = ? limit 1),0)
				isAlreadyAnswered
				FROM messageTable mt, messageTable m, 
				questions_listing_response qlr,CA_ProfileTable ca
				WHERE qlr.courseId in ($coursesId)
				AND qlr.status = 'live'
				AND mt.status = 'live'
				AND m.status='live'
				AND mt.parentId = 0
				AND mt.fromOthers = 'user'
				AND m.userId = ?
				AND mt.creationDate >= ca.modificationDate
	                   	AND ca.profileStatus = 'accepted'
				AND ca.userId = ?
				AND mt.msgId = qlr.messageId
				AND m.parentId=mt.msgId
				having isAlreadyAnswered != 0";
			$query = $this->dbHandle->query($query,array($userId,$userId,$userId));
			$averageTime = $query->result();
			$totalTime = 0;
			if(is_array($averageTime))
			{
				$total = Array();
				foreach((object) $averageTime as $time)
				{
					$total[] =  (strtotime($time->answerCreationDate) - strtotime($time->creationDate));
				}
				$totalTime = array_sum($total)/count($total);
			}
			
			$this->cacheLib->store($key,$totalTime,21600);
			return $totalTime;
		}
		else{
			return $this->cacheLib->get($key);
		}
	}

	function getTotalQuestion($userId,$coursesId)
	{
		$this->initiateModel('read');
		
		$query = "SELECT count(mt.msgId) as totalQuestion
			FROM messageTable mt
			JOIN questions_listing_response qlr ON ( mt.msgId = qlr.messageId )
			JOIN tuser tu ON ( mt.userId = tu.userid )
			JOIN CA_ProfileTable ca ON (ca.userId = ?)
			WHERE qlr.courseId IN ($coursesId)
			AND qlr.status = 'live'
			AND mt.status = 'live'
			AND mt.parentId = 0
			AND mt.creationDate >= ca.modificationDate
			AND ca.profileStatus = 'accepted'
			AND ca.userId != mt.userId
			AND mt.fromOthers = 'user'";
			$query = $this->dbHandle->query($query,array($userId));	
			$result = $query->result();
			return $result[0]->totalQuestion;
		
	}
        
function getListOfTasks($taskType,$userId,$programId=""){
		$this->initiateModel('read');
		if($programId < 0 || $programId=='')
		{
			return;
		}
		//if($taskType=='open'){
			$tasksList['open'] = $this->getOpenTasks($userId,$programId);
		//}else if($taskType=='closed'){
			$tasksList['closed'] = $this->getClosedTasks($userId,$programId);
		//}else{
			$tasksList['upcoming'] = $this->getUpcomingTasks($userId,$programId);
		//}
		return $tasksList;
	}
	
	public function getOpenTasks($userId,$programId){
		$result = array();
	$sql = "select SQL_CALC_FOUND_ROWS mt.*, mtp.* from my_tasks mt join my_task_prizes mtp on (mt.id = mtp.my_task_id) where mt.status='live' and mtp.status='live' and mt.programId = ? and (mt.start_date<=CURDATE() and mt.end_date>CURDATE() and mt.end_date is NOT NULL) and mt.id not in (select taskRefId from CA_TaskSubmission where userId=?)
			UNION
			select mt1.*, mtp1.* from my_tasks mt1 join my_task_prizes mtp1 on (mt1.id = mtp1.my_task_id) where mt1.status='live' and mtp1.status='live' and mt1.programId = ? and mt1.start_date<=CURDATE() and mt1.end_date is NULL and mt1.id not in (select taskRefId from CA_TaskSubmission where userId=?)
			order by start_date, end_date asc";
		$query = $this->dbHandle->query($sql,array($programId,$userId,$programId,$userId));
		$resultSet = $query->result_array();
		
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $this->dbHandle->query($queryCmdTotal);
		$queryResults = $queryTotal->result();
		$totalRows = $queryResults[0]->totalRows;
		if($totalRows>0){
			$result = $resultSet;
		}
		return $result;
	}
	
	public function getClosedTasks($userId,$programId){
		$result = array();
		$sql = "select SQL_CALC_FOUND_ROWS * from my_tasks mt join my_task_prizes mtp on (mt.id = mtp.my_task_id) where mt.status='live' and mtp.status='live' and mt.programId = ? and ((mt.end_date < CURDATE() and mt.end_date is NOT NULL) or mt.id in (select taskRefId from CA_TaskSubmission where userId=?))
			UNION
			select mt1.*, mtp1.* from my_tasks mt1 join my_task_prizes mtp1 on (mt1.id = mtp1.my_task_id) where mt1.status='live' and mtp1.status='live' and mt1.programId = ? and mt1.end_date is NULL and mt1.id in (select taskRefId from CA_TaskSubmission where userId=?)
			order by start_date, end_date asc";
		$query = $this->dbHandle->query($sql,array($programId,$userId,$programId,$userId));
		$resultSet = $query->result_array();
		
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $this->dbHandle->query($queryCmdTotal);
		$queryResults = $queryTotal->result();
		$totalRows = $queryResults[0]->totalRows;
		if($totalRows>0){
			$result = $resultSet;
		}
		return $result;
	}
	
	public function getUpcomingTasks($userId,$programId){
		$result = array();
		$sql = "select SQL_CALC_FOUND_ROWS * from my_tasks mt join my_task_prizes mtp on (mt.id = mtp.my_task_id) where mt.status='live' and mtp.status='live' and mt.programId = ? and (mt.start_date>CURDATE()) and mt.id not in (select taskRefId from CA_TaskSubmission where userId=?)
			UNION
			select mt1.*, mtp1.* from my_tasks mt1 join my_task_prizes mtp1 on (mt1.id = mtp1.my_task_id) where mt1.status='live' and mtp1.status='live' and mt1.programId = ? and mt1.start_date>CURDATE() and mt1.end_date is NULL and mt1.id not in (select taskRefId from CA_TaskSubmission where userId=?)
			order by start_date, end_date asc";
		$query = $this->dbHandle->query($sql,array($programId,$userId,$programId,$userId));
		$resultSet = $query->result_array();
		
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $this->dbHandle->query($queryCmdTotal);
		$queryResults = $queryTotal->result();
		$totalRows = $queryResults[0]->totalRows;
		if($totalRows>0){
			$result = $resultSet;
		}
		return $result;		
	}
	
	public function endTask($taskId, $userId){
		$this->initiateModel('write');
		$sql = "insert into CA_TaskSubmission (taskRefId, userId) values (?,?)";
		$query = $this->dbHandle->query($sql,array($taskId,$userId));
	}
	
	public function uploadTask($userId, $taskId, $fileName, $url){
		$this->initiateModel('write');
		$sql = "insert into CA_MappingToTaskPrize (userId,taskId,name,url,creationDate) values (?,?,?,?,now())";
		$query = $this->dbHandle->query($sql,array($userId, $taskId, $fileName, $url));
		$last_insert_id = $this->dbHandle->insert_id();
		return $last_insert_id;
	}
	
	public function removeUploadedTask($task_uploaded_id){
		$this->initiateModel('write');
		$sql = "update CA_MappingToTaskPrize set status = ?, modificationDate=now() where id=?";
		$query = $this->dbHandle->query($sql,array('deleted',$task_uploaded_id));
	}
	
	public function getUploadedTasks($userId,$taskId){
		$sql = "select * from CA_MappingToTaskPrize where userId=? and taskId=? and status=?";
		$query = $this->dbHandle->query($sql,array($userId,$taskId,'live'));
		$resultSet = $query->result_array();
		return $resultSet;
	}
	
	function getUnansweredQuestionsFromDbForWeeklyDigestMailer($userId,$coursesId,$fromDate,$page_number = 0, $item_per_page = 10)
	{
	        $this->initiateModel('read');
		
		$position = ($page_number * $item_per_page);
		
		$query = "SELECT SQL_CALC_FOUND_ROWS mt.msgId,mt.creationDate,mt.userId,mt.msgTxt,mt.status,qlr.courseId,qlr.instituteId, ifnull ((select msgId from messageTable mA where mA.fromOthers='user' and mA.mainAnswerId=0 and mA.threadId=mt.msgId and mA.userId = ? limit 1),0) isAlreadyAnswered,tu.displayname as `PostedBy`
			FROM messageTable mt
			JOIN questions_listing_response qlr ON ( mt.msgId = qlr.messageId )
			JOIN tuser tu ON ( mt.userId = tu.userid )
			JOIN CA_ProfileTable ca ON (ca.userId = ?)
			WHERE qlr.courseId in ($coursesId)   
			AND qlr.status = 'live'
			AND mt.status = 'live'
			AND mt.parentId = 0
			AND mt.fromOthers = 'user'
			AND mt.mainAnswerId = -1
			AND mt.creationDate >= ?
			AND mt.creationDate >= ca.modificationDate
			AND ca.profileStatus = 'accepted'
			AND ca.userId != mt.userId
			having isAlreadyAnswered = 0
			ORDER BY `mt`.`msgId` DESC
			LIMIT $position, $item_per_page";
			
			$query = $this->dbHandle->query($query,array($userId,$userId,$fromDate));
			$result_array["result"] =  $query->result();
			
			$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
			$queryTotal = $this->dbHandle->query($queryCmdTotal);
			$queryResults = $queryTotal->result();
			$totalRows = $queryResults[0]->totalRows;
			$result_array["total"] = $totalRows;
			
			return $result_array;
	}
	    
	public function checkAtleastOneTaskSubmitted($userId,$taskId){
	    $this->initiateModel();
	    $sql = 'select SQL_CALC_FOUND_ROWS * from CA_MappingToTaskPrize where userId=? and taskId=? and status=?';
	    $queryTotal  = $this->dbHandle->query($sql,array($userId,$taskId,'live'));
	    $num_rows = 0;
	    $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
	    $queryTotal = $this->dbHandle->query($queryCmdTotal);
	    foreach ($queryTotal->result() as $rowT) {
		    $num_rows  = $rowT->totalRows;
	    }
	    return $num_rows;
	}
	
	function getAllCrAnswers(){
	     $this->initiateModel();
	     $sql="select m.msgId,m.userId,m.threadId,m.creationDate from messageTable m,CA_ProfileTable ca where m.mainAnswerId=0 and m.creationDate>=now() - INTERVAL 1 DAY and status='live' and m.userId =ca.userId and ca.profileStatus='accepted'";
	     $query = $this->dbHandle->query($sql);
	     return $query->result_array();  
		
	}
	
	
	function checkAnswerExistence($userId,$msgId){
		$this->initiateModel('read');
		$sql = "select entityId from CA_wallet where userId=? and entityId=?";
		$query = $this->dbHandle->query($sql,array($userId,$msgId));
		$data = $query->result_array();
		return $data[0]['entityId'];
	
	}
	
	function checkExistenceinAnswerStatus($userId,$answerId){
		$this->initiateModel('read');
		$sql = "select answerId from CA_AnswerStatusTable where userId=? and answerId=?";
		$query = $this->dbHandle->query($sql,array($userId,$answerId));
		$data = $query->result_array();
		return $data[0]['answerId'];
	}
	
	function getCrProgram($userId)
	{
		$this->initiateModel('read');
		if($userId !=''){
			$query = "select programId from CA_ProfileTable where profileStatus = 'accepted' AND userId = ?
					limit 1";
					return $this->dbHandle->query($query,array($userId))->result_array();
		}
		return array();

	}

	function getMainCourseIdInstituteIdUserIdOfCR($userId){
                $query = "SELECT cm.courseId , c.id AS campusRepId , cm.instituteId FROM CA_MainCourseMappingTable AS cm, `CA_ProfileTable` AS c WHERE cm.caId = c.id AND cm.status = 'live' AND c.userId = ? AND c.profileStatus = 'accepted' AND c.id = cm.caId and badge='CurrentStudent'";
                $query = $this->dbHandle->query($query,array($userId));
                $result = $query->result_array();
                return $result[0];
        }

	function getUnansweredQuestions($userId, $courseIds ,$instituteIds, $page_number = 0, $item_per_page = 10){
		if(empty($courseIds)){
			return array();
		}
		if(is_array($courseIds)){
			$courseIds = implode(',',$courseIds);

		}
		if(is_array($instituteIds)){
			$instituteIds = implode(',',$instituteIds);
		}
		$position = ($page_number * $item_per_page);
		$query = "select SQL_CALC_FOUND_ROWS * from (select mt.msgId, mt.msgTxt, md.description, mt.status, tu.displayname as `PostedBy`, ifnull ((select msgId from messageTable mA where mA.fromOthers='user' and mA.mainAnswerId=0 and mA.threadId=mt.msgId and mA.userId = ? limit 1),0) isAlreadyAnswered, qlr.courseId, qlr.instituteId, mt.creationDate from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId JOIN tuser tu ON ( mt.userId = tu.userid ) JOIN CA_ProfileTable ca ON (ca.userId = ?) LEFT JOIN messageDiscussion md on (mt.msgId=md.threadId) where qlr.status = 'live' and mt.status in ('live', 'closed') and qlr.courseId in ($courseIds) AND mt.creationDate >= ca.modificationDate AND ca.profileStatus = 'accepted'  AND UNIX_TIMESTAMP(mt.creationDate)> UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))  having isAlreadyAnswered =0
                union
                select tcm.content_id as msgId, mt.msgTxt, md.description, mt.status, tu.displayname as `PostedBy`, ifnull ((select msgId from messageTable mA where mA.fromOthers='user' and mA.mainAnswerId=0 and mA.threadId=mt.msgId and mA.userId = ? limit 1),0) isAlreadyAnswered, 0 as courseId, te.entity_id as instituteId, mt.creationDate from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable mt on tcm.content_id = mt.msgId JOIN tuser tu ON ( mt.userId = tu.userid ) JOIN CA_ProfileTable ca ON (ca.userId = ?) LEFT JOIN messageDiscussion md on (tcm.content_id=md.threadId) where te.entity_type in ('institute', 'National-University') and te.status = 'live' and mt.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and te.entity_id in ($instituteIds) AND mt.creationDate >= ca.modificationDate  AND UNIX_TIMESTAMP(mt.creationDate)> UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY)) AND ca.profileStatus = 'accepted'  having isAlreadyAnswered=0) res group by msgId order by msgId desc LIMIT $position, $item_per_page";
		$query = $this->dbHandle->query($query,array($userId,$userId,$userId,$userId));
                $result['result'] = $query->result();
                $queryCmdTotal  = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal     = $this->dbHandle->query($queryCmdTotal);
                $queryResults   = $queryTotal->result();
                $totalRows      = $queryResults[0]->totalRows;
		$data["total"]  = $totalRows;
		$data['result'] = $this->checkIfCourseIdExists($result); 
                return $data;
	}

	function checkIfCourseIdExists($result){ 
		$this->initiateModel('read'); 
		$msgString = '';$data=array(); 
		foreach($result['result'] as $key=>$value){ 
			if($value->courseId==0){ 
				$msgString .= $value->msgId.','; 
			} 
		} 
		$msgString = rtrim($msgString,','); 
		if(empty($msgString)){ 
			return $result['result']; 
		} 
		$query = "select messageId as msgId, courseId from questions_listing_response where messageId in (".$this->dbHandle->escape($msgString).") and status='live'"; 
		$query = $this->dbHandle->query($query); 
		$data = $query->result(); 
		foreach($data as $key=>$value){ 
			foreach($result['result'] as $k=>$v){ 
				if($value->msgId==$v->msgId){ 
					$result['result'][$k]->courseId = $value->courseId; 
				} 
			} 
		} 
		return $result['result']; 
	}
}
