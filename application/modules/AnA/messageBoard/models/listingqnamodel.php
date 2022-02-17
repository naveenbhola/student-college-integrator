<?php
class listingQnaModel extends MY_Model {
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

	function getAllCAAndCourseIds(){
		$redisLib = PredisLibrary::getInstance();
		$caKey = 'caUserIdCourseIdInstId';
		$caDetailsCache = $redisLib->getAllMembersOfHashWithValue($caKey);
		if(empty($caDetailsCache)){
			$this->initiateModel('read');
		
		        $date = date("Y-m-d");
		        $date = strtotime("-5 days",strtotime($date));
		        $date = date ( 'Y-m-j' , $date );
			$sql = "SELECT cap.userId, cap.displayname, cam.instituteId, cam.courseId mainCourse, cap.programId 
					from CA_ProfileTable cap 
					join CA_MainCourseMappingTable cam on cap.id = cam.caId 
					where cap.profileStatus = 'accepted' and cam.status = 'live' and cam.badge = 'CurrentStudent' AND cap.modificationDate < '$date'";
			$result = $this->dbHandle->query($sql)->result_array();
			$CACourses = $CAIds = $CAInstitutes = array();
			foreach($result as $value){
				$CAIds[$value['userId']] = $value['userId'];
				$CAInstitutes[$value['instituteId']] = $value['instituteId'];
				$CACourses[$value['mainCourse']] = $value['mainCourse'];
				//if(!empty($value['otherCourse'])){
				//	$CACourses[$value['otherCourse']] = $value['otherCourse'];
				//}
			}
			$caDetailsDB = array('caUserIds' => json_encode($CAIds), 'caCourseIds' => json_encode($CACourses), 'caInstIds' => json_encode($CAInstitutes));
			$redisLib->addMembersToHash($caKey, $caDetailsDB);
			$redisLib->expireKey($caKey, 12*60*60);
			unset($redisLib);
			return array('caUserIds' => $CAIds, 'caCourseIds' => $CACourses, 'caInstIds' => $CAInstitutes);
		}else{
			$caDetails = array();
			$caDetails['caUserIds']   = json_decode($caDetailsCache['caUserIds'], true);
			$caDetails['caCourseIds'] = json_decode($caDetailsCache['caCourseIds'], true);
			$caDetails['caInstIds']   = json_decode($caDetailsCache['caInstIds'], true);
			return $caDetails;
		}
	}

	public function getAllCourseAndInstQuestions($param){
		$this->initiateModel('read');
		$result = array();
		$queryCmd = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ?
		union
		select tcm.content_id as messageId, 0 as courseId, te.entity_id as instituteId, date(mt.creationDate) as creationTime from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable mt on tcm.content_id = mt.msgId where te.entity_type in ('institute', 'National-University') and te.status = 'live' and mt.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and date(mt.creationDate) >= ? and date(mt.creationDate) <= ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $param['startDate'], $param['endDate']));
		$result = $queryRes->result_array();
		$quesData = array();
		$tempCourses = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			if(!empty($value['courseId'])){
				$quesData['institutes'][$value['instituteId']][$value['courseId']][] = $value['messageId'];
				$tempCourses[] = $value['messageId'];
			}else{
				if(!in_array($value['messageId'], $tempCourses)){
					$quesData['institutes'][$value['instituteId']][0][] = $value['messageId'];
				}
			}
		}
		return $quesData;
	}

	public function getAllCourseQuestions($param)
	{
		$this->initiateModel('read');
		$result = array();
		$queryCmd = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate']));
		$result = $queryRes->result_array();
		$quesData = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			$quesData['institutes'][$value['instituteId']][$value['courseId']][] = $value['messageId'];
		}
		return $quesData;
	}

	public function getAllInstQuestions($param){
		$this->initiateModel('read');
		$result = array();

		$queryCmd1 = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ?";
		$queryRes1 = $this->dbHandle->query($queryCmd1, array($param['startDate'], $param['endDate']));
		$result1 = $queryRes1->result_array();
		$courseQuesIds = array();
		foreach ($result1 as $value) {
			$courseQuesIds[] = $value['messageId'];
		}

		$courseQuesIdCheck = '';
		if(!empty($courseQuesIds)){
			$courseQuesIds = implode(',', $courseQuesIds);
			$courseQuesIdCheck = "and tcm.content_id NOT in ($courseQuesIds)";
		}

		$queryCmd = "select tcm.content_id as messageId, 0 as courseId, te.entity_id as instituteId, date(mt.creationDate) as creationTime from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable mt on tcm.content_id = mt.msgId where te.entity_type in ('institute', 'National-University') and te.status = 'live' and mt.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? $courseQuesIdCheck";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate']));
		$result = $queryRes->result_array();
		$quesData = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			$quesData['institutes'][$value['instituteId']][0][] = $value['messageId'];
		}
		return $quesData;
	}

	function getAllCourseAndInstQuestionsHavingCR($param, $caCourses, $caInstitutes){
		$this->initiateModel('read');
		$result = array();
		if(empty($caCourses) || empty($caInstitutes)){
			return array();
		}
		//$caCourses = implode(',', $caCourses);
		//$caInstitutes = implode(',', $caInstitutes);
		$queryCmd = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and qlr.courseId in (?)
		union
		select tcm.content_id as messageId, 0 as courseId, te.entity_id as instituteId, date(mt.creationDate) as creationTime from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable mt on tcm.content_id = mt.msgId where te.entity_type in ('institute', 'National-University') and te.status = 'live' and mt.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and te.entity_id in (?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $caCourses, $param['startDate'], $param['endDate'], $caInstitutes));
		$result = $queryRes->result_array();
		$quesData = array();
		$tempCourses = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			if(!empty($value['courseId'])){
				$quesData['institutes'][$value['instituteId']][$value['courseId']][] = $value['messageId'];
				$tempCourses[] = $value['messageId'];
			}else{
				if(!in_array($value['messageId'], $tempCourses)){
					$quesData['institutes'][$value['instituteId']][0][] = $value['messageId'];
				}
			}
		}
		return $quesData;
	}

	function getAllCourseQuestionsHavingCR($param, $caCourses){
		$this->initiateModel('read');
		$result = array();
		if(empty($caCourses)){
			return array();
		}
		//$caCourses = implode(',', $caCourses);
		$queryCmd = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and qlr.courseId in (?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $caCourses));
		$result = $queryRes->result_array();
		$quesData = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			$quesData['institutes'][$value['instituteId']][$value['courseId']][] = $value['messageId'];
		}
		return $quesData;
	}

	function getAllInstQuestionsHavingCR($param, $caInstitutes){
		$this->initiateModel('read');
		$result = array();
		if(empty($caInstitutes)){
			return array();
		}
		//$caInstitutes = implode(',', $caInstitutes);
		$queryCmd = "select tcm.content_id as messageId, 0 as courseId, te.entity_id as instituteId, date(mt.creationDate) as creationTime from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable mt on tcm.content_id = mt.msgId where te.entity_type in ('institute', 'National-University') and te.status = 'live' and mt.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and te.entity_id in (?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $caInstitutes));
		$result = $queryRes->result_array();
		$quesIds = array();
		foreach ($result as $value) {
			$quesIds[] = $value['messageId'];
		}

		//get questions which are posted only on courses in above resultset
		$filteredQuesIds = array();
		if(!empty($quesIds)){
			$quesIds = implode(',', $quesIds);
			$queryCmd = "select qlr.messageId from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and qlr.messageId in ($quesIds)";
			$queryRes1 = $this->dbHandle->query($queryCmd);
			$result1 = $queryRes1->result_array();
			foreach ($result1 as $value1) {
				$filteredQuesIds[$value1['messageId']] = $value1['messageId'];
			}
		}
		$quesData = array();
		foreach ($result as $value) {
			if(empty($filteredQuesIds[$value['messageId']])){
				$quesData['questions'][$value['messageId']] = $value['creationTime'];
				$quesData['institutes'][$value['instituteId']][0][] = $value['messageId'];
			}
		}
		return $quesData;
	}

	function getAllCourseAndInstQuestionsNotHavingCR($param, $caCourses, $caInstitutes){
		$this->initiateModel('read');
		$result = array();
		if(empty($caCourses) || empty($caInstitutes)){
			return array();
		}
		//$caCourses = implode(',', $caCourses);
		//$caInstitutes = implode(',', $caInstitutes);
		$queryCmd = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and qlr.courseId NOT in (?) and qlr.instituteId NOT in (?)
		union
		select tcm.content_id as messageId, 0 as courseId, te.entity_id as instituteId, date(mt.creationDate) as creationTime from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable mt on tcm.content_id = mt.msgId where te.entity_type in ('institute', 'National-University') and te.status = 'live' and mt.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and te.entity_id NOT in (?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $caCourses, $caInstitutes, $param['startDate'], $param['endDate'], $caInstitutes));
		$result = $queryRes->result_array();
		$quesData = array();
		$tempCourses = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			if(!empty($value['courseId'])){
				$quesData['institutes'][$value['instituteId']][$value['courseId']][] = $value['messageId'];
				$tempCourses[] = $value['messageId'];
			}else{
				if(!in_array($value['messageId'], $tempCourses)){
					$quesData['institutes'][$value['instituteId']][0][] = $value['messageId'];
				}
			}
		}
		return $quesData;
	}

	function getAllCourseQuestionsNotHavingCR($param, $caCourses, $caInstitutes){
		$this->initiateModel('read');
		$result = array();
		if(empty($caCourses) || empty($caInstitutes)){
			return array();
		}
		//$caCourses = implode(',', $caCourses);
		//$caInstitutes = implode(',', $caInstitutes);
		$queryCmd = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and qlr.courseId NOT in (?) and qlr.instituteId NOT in (?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $caCourses, $caInstitutes));
		$result = $queryRes->result_array();
		$quesData = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			$quesData['institutes'][$value['instituteId']][$value['courseId']][] = $value['messageId'];
		}
		return $quesData;
	}

	function getAllInstQuestionsNotHavingCR($param, $caInstitutes){
		$this->initiateModel('read');
		$result = array();
		if(empty($caInstitutes)){
			return array();
		}
		//$caInstitutes = implode(',', $caInstitutes);
		$queryCmd = "select tcm.content_id as messageId, 0 as courseId, te.entity_id as instituteId, date(mt.creationDate) as creationTime from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable mt on tcm.content_id = mt.msgId where te.entity_type in ('institute', 'National-University') and te.status = 'live' and mt.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and te.entity_id NOT in (?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $caInstitutes));
		$result = $queryRes->result_array();
		
		$quesIds = array();
		foreach ($result as $value) {
			$quesIds[] = $value['messageId'];
		}

		//get questions which are posted only on courses in above resultset
		$filteredQuesIds = array();
		if(!empty($quesIds)){
			$quesIds = implode(',', $quesIds);
			$queryCmd = "select qlr.messageId from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and qlr.messageId in ($quesIds)";
			$queryRes1 = $this->dbHandle->query($queryCmd);
			$result1 = $queryRes1->result_array();
			foreach ($result1 as $value1) {
				$filteredQuesIds[$value1['messageId']] = $value1['messageId'];
			}
		}

		$quesData = array();
		foreach ($result as $value) {
			if(empty($filteredQuesIds[$value['messageId']])){
				$quesData['questions'][$value['messageId']] = $value['creationTime'];
				$quesData['institutes'][$value['instituteId']][0][] = $value['messageId'];
			}
		}
		return $quesData;
	}

	function getAllCourseQuestionsHavingIndirectCR($param, $caCourses, $caInstitutes){
		$this->initiateModel('read');
		$result = array();
		if(empty($caCourses)){
			return array();
		}
		if(empty($caInstitutes)){
			return array();
		}
		//$caCourses    = implode(',', $caCourses);
		//$caInstitutes = implode(',', $caInstitutes);
		$queryCmd = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and qlr.courseId NOT in (?) and instituteId in (?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $caCourses, $caInstitutes));
		$result = $queryRes->result_array();
		$quesData = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			$quesData['institutes'][$value['instituteId']][$value['courseId']][] = $value['messageId'];
		}
		return $quesData;
	}

	function getAllCourseAndInstQuestionsHavingIndirectCR($param, $caCourses, $caInstitutes){
		$this->initiateModel('read');
		$result = array();
		if(empty($caCourses)){
			return array();
		}
		if(empty($caInstitutes)){
			return array();
		}
		//$caCoursesStr = implode(',', $caCourses);
		//$caInstitutes = implode(',', $caInstitutes);
		$queryCmd = "select qlr.messageId, qlr.courseId, qlr.instituteId, date(mt.creationDate) as creationTime from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and qlr.courseId NOT in (?) and qlr.instituteId in (?)
		union
		select tcm.content_id as messageId, 0 as courseId, te.entity_id as instituteId, date(mt.creationDate) as creationTime from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable mt on tcm.content_id = mt.msgId where te.entity_type in ('institute', 'National-University') and te.status = 'live' and mt.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and date(mt.creationDate) >= ? and date(mt.creationDate) <= ? and te.entity_id in (?)";
		$queryRes = $this->dbHandle->query($queryCmd, array($param['startDate'], $param['endDate'], $caCourses, $caInstitutes,  $param['startDate'], $param['endDate'], $caInstitutes));
		$result = $queryRes->result_array();
		
		$quesIds = array();
		foreach ($result as $value) {
			$quesIds[] = $value['messageId'];
		}

		//get questions which are posted only on courses in above resultset
		$filteredQuesIds = array();
		if(!empty($quesIds)){
			$quesIds = implode(',', $quesIds);
			$queryCmd = "select qlr.messageId from questions_listing_response qlr join messageTable mt on qlr.messageId = mt.msgId where qlr.status = 'live' and mt.status in ('live', 'closed') and qlr.messageId in ($quesIds)";
			$queryRes1 = $this->dbHandle->query($queryCmd);
			$result1 = $queryRes1->result_array();
			foreach ($result1 as $value1) {
				$filteredQuesIds[$value1['messageId']] = $value1['messageId'];
			}
		}

		$quesData = array();
		$tempCourses = array();
		foreach ($result as $value) {
			$quesData['questions'][$value['messageId']] = $value['creationTime'];
			if(!empty($value['courseId']) && empty($caCourses[$value['courseId']])){
				$quesData['institutes'][$value['instituteId']][$value['courseId']][] = $value['messageId'];
				$tempCourses[] = $value['messageId'];
			}else if(empty($filteredQuesIds[$value['messageId']])){
				if(!in_array($value['messageId'], $tempCourses)){
					$quesData['institutes'][$value['instituteId']][0][] = $value['messageId'];
				}
			}
		}
		return $quesData;
	}

	function getAnswersOfQuestions($threadIds){
		$this->initiateModel('read');
		if(empty($threadIds)){
			return array();
		}

		$this->dbHandle->select('msgId as answerId, threadId as questionId, date(creationDate) as answerDate, userId as answeredBy');
		$this->dbHandle->from('messageTable');
		$this->dbHandle->where('status', 'live');
		$this->dbHandle->where('mainAnswerId', 0);
		$this->dbHandle->where('fromOthers', 'user');
		$this->dbHandle->where_in('parentId', $threadIds);
		$queryRes = $this->dbHandle->get();
		return $queryRes->result_array();
	}
}
