<?php
class CAReportsModel extends MY_Model
{
	private $dbHandle = '';
	function __construct(){
		parent::__construct();
	}

	private function initiateModel($operation='write'){
		if($operation == 'read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
        	$this->dbHandle = $this->getWriteHandle();
		}		
	}
	public function getAllCAList(){
		$this->initiateModel('read');
		$sql = "SELECT cap.userId, cap.displayname, cam.courseId mainCourse, cao.courseId as otherCourse, cap.programId 
				from CA_ProfileTable cap 
				join CA_MainCourseMappingTable cam on cap.id = cam.caId
				left join CA_OtherCourseDetails cao on cam.id = cao.mappingCAId
				where cap.profileStatus = 'accepted' and cam.status = 'live' and cam.badge = 'CurrentStudent' and (cao.badge = 'CurrentStudent' or cao.badge is null) and (cao.status = 'live' or cao.status is null)";
		$resultset = $this->dbHandle->query($sql);
		//echo $this->dbHandle->last_query();
		$resut = $resultset->result_array();
		//_p($resut);
		return $resut;
	}

	public function getQuestionsOnCourses($courseList, $days){
		$this->initiateModel('read');
		if(empty($courseList)){
			return array();
		}else{
			$courseListStr = implode(', ', $courseList);
		}
		$dateSql = " and DATE(creationTime) < curdate() and DATE(creationTime) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
		$sql = "SELECT courseId, count(messageId) quesCount, group_concat(messageId) quesIds from questions_listing_response where courseId in ('".$this->dbHandle->escape_str($courseListStr)."') and status = 'live' $dateSql group by courseId";
		$resultset = $this->dbHandle->query($sql,array($days));
		//echo $this->dbHandle->last_query();
		$result = $resultset->result_array();
		//_p($result);
		$quesData = array();
		foreach ($result as $key => $value) {
			$quesData['quesCount'][$value['courseId']] = $value['quesCount'];
			$quesData['quesIds'][$value['courseId']] = $value['quesIds'];
		}
		//_p($quesData);
		return $quesData;
	}

	public function getAnswerCountByCA($qIds, $caIds, $days){
		$this->initiateModel('read');
		if(empty($qIds) || empty($caIds)){
			return 0;
		}else{
			$caIds = implode(',', $caIds);
			$dateSql = " and DATE(creationDate) < curdate() and DATE(creationDate) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
			$sql = "SELECT count(msgId) ansCount from messageTable where userId in (".$this->dbHandle->escape($caIds).") and parentId in (".$this->dbHandle->escape($qIds).") and mainAnswerId = 0 and fromOthers = 'user' and status in ('live', 'closed') $dateSql";
			$resultset = $this->dbHandle->query($sql,array($days));
			//echo $this->dbHandle->last_query();
			$result = $resultset->result_array();
			return $result[0]['ansCount'];
			//_p($result);
		}
	}
}
