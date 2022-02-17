<?php

class prilinemodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{
		parent::__construct('Listing');
	}
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */
	private function _getDbHandle($operation='read')
	{
		if($operation=='read'){
			$dbHandle = $this->getReadHandle();
		}
		else{
			$dbHandle = $this->getWriteHandle();
		}
		if($dbHandle == ''){
			error_log('error can not create db handle');
		}
		return $dbHandle;
	}
	
	
	public function setPRINumber($courseId,$PRINumber,$cityId,$categoryId,$setOn)
	{
		$dbHandle = $this->_getDbHandle('write');
		$data = array(
			'courseId' => $courseId,
			'PRINumber' => $PRINumber,
			'cityId' => $cityId,
			'categoryId' => $categoryId,
			'setOn' => $setOn
		);
		$dbHandle->insert('PRIAssignment',$data);
		$this->_createResponse($courseId,$PRINumber);
	}
	
	private function _createResponse($courseId,$PRINumber)
	{
		$dbHandle = $this->_getDbHandle('write');
		$sql = "SELECT userid,displayname,email FROM tuser WHERE mobile = ? LIMIT 1";
		$query = $dbHandle->query($sql,array($PRINumber));
		$row = $query->row_array();
		if($row['userid']) {
			$addReqInfo = array();
			$addReqInfo['displayName'] = $row['displayname'];
			$addReqInfo['contact_cell'] = $PRINumber;
			$addReqInfo['userId'] = $row['userid'];
			$addReqInfo['contact_email'] = $row['email'];
			$addReqInfo['action'] = "Request_E-Brochure";
			$addReqInfo['listing_type'] = 'course';
			$addReqInfo['listing_type_id'] = $courseId;
			
			$CI = & get_instance();
			$appId = 1;
			
			$CI->load->library(array('LmsLib','LDB_Client'));
			
			$LmsClientObj = new LmsLib();
			$addLeadStatus = $LmsClientObj->insertTempLead($appId, $addReqInfo);
			
			$ldbObj = new LDB_client();
			$signedInUser = $ldbObj->sgetUserDetails($appId, $row['userid']);
			
			$addReqInfo['userInfo'] = $signedInUser;
			$addReqInfo['sendMail'] = false;
			$addReqInfo['tempLmsRequest'] = $addLeadStatus['leadId'];
			$addLeadStatus = $LmsClientObj->insertLead($appId, $addReqInfo);
		}
	}
	
	public function resetPRINumber($courseId)
	{
		$dbHandle = $this->_getDbHandle('write');
		$sql = "UPDATE PRIAssignment SET status = 'history',resetOn = ? WHERE courseId = ? AND status = 'live'";
		$dbHandle->query($sql,array(date('Y-m-d H:i:s'), $courseId));
	}
	
	public function isAlreadyAssigned($PRINumber)
	{
		$dbHandle = $this->_getDbHandle();
		$sql = "SELECT id from PRIAssignment WHERE PRINumber = ? AND status = 'live'";
		$query = $dbHandle->query($sql,array($PRINumber));
		$row = $query->row_array();
		return $row['id'];
	}
	
	public function getPRIAssignments($status = 'live')
	{
		$dbHandle = $this->_getDbHandle();
		$sql = "SELECT * from PRIAssignment WHERE status = 'live'";
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		$PRIAssignments = array();
		foreach($results as $result) {
			$PRIAssignments[$result['courseId']] = $result;
		}
		return $PRIAssignments;
	}
	
	public function getPRIMapping($history = FALSE)
	{
		$dbHandle = $this->_getDbHandle();
		$sql = "SELECT p.PRINumber,p.setOn,p.resetOn,cct.city_name as cityName,cbt.name as categoryName,c.courseTitle as courseName,i.institute_name as instituteName,u.displayName as clientName ".
			   "FROM PRIAssignment p ".
			   "INNER JOIN countryCityTable cct ON cct.city_id = p.cityId ".
			   "INNER JOIN categoryBoardTable cbt ON cbt.boardId = p.categoryId ".
			   "INNER JOIN course_details c ON (c.course_id = p.courseId AND c.status = 'live') ".
			   "INNER JOIN institute i ON (i.institute_id = c.institute_id AND i.status = 'live') ".
			   "INNER JOIN listings_main l ON (l.listing_type_id = p.courseId AND l.listing_type = 'course' AND l.status = 'live') ".
			   "INNER JOIN tuser u ON u.userid = l.username ";
			   
		if($history) {
			$sql .= " WHERE p.status = 'history' ORDER BY p.PRINumber,p.setOn";
		}
		else {
			$sql .= " WHERE p.status = 'live' ORDER BY p.setOn";
		}
			   
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		return $results;
	}
}
