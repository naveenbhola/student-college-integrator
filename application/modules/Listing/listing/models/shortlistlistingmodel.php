<?php
/**
 *  model for shortlisted coures on abroad category pages.
 */

class shortlistlistingmodel extends MY_Model {
	function __construct() {
		parent::__construct ( 'Listing' );
	}
	
	public function updateShortListedCourse($data, $action) {
		//below line is used for stroing visitorsessionid for conversion tracking purpose
		if(!isset($data['visitorSessionid']))
		{
			$data['visitorSessionid']= getVisitorSessionId();
		}

		if(empty($data['scope'])){
			$data['scope'] = 'abroad';
		}
		$dbHandle = $this->getWriteHandle ();
		if ($action == 'delete') {
			$status = $dbHandle->update ( 'userShortlistedCourses', array (
					'status' => $data ['status'] , 'tracking_keyid' => $data['tracking_keyid'], 'visitorSessionid' => $data['visitorSessionid']
			), "courseId =" . $data ['courseId'] . " AND userId = " . $data ['userId'] . " ".($data['userId']==-1?' AND sessionId = "'.$data['sessionId'].'"':'')." AND scope = '".$data['scope']."'" );
		} else {
			$courseIsAlreadyShortlisted = false;
			$resultData = $this->fetchIfUserHasShortListedCourses($data,$dbHandle);
			if(count ($resultData ['courseIds']) > 0) {
				$courseIsAlreadyShortlisted = in_array($data ['courseId'], $resultData ['courseIds']);
			}
			if(!$courseIsAlreadyShortlisted)
			{
				$status = $dbHandle->insert ( 'userShortlistedCourses', $data );
			}
			
			return $courseIsAlreadyShortlisted  ? $resultData['count'] : $resultData['count'] + 1;
		}
		if ($status && $action == 'delete') {
			$resultData = $this->fetchIfUserHasShortListedCourses($data,$dbHandle);
			return $resultData['count'];
		} else {
			return $status;
		}
	}
	
	public function fetchIfUserHasShortListedCourses($data,$dbHandle = null ) {
		$dbHandle = !empty($dbHandle) ? $dbHandle : $this->getReadHandle ();
		if(empty($data['scope'])){
		   $data['scope'] = 'abroad';
		}
		
		$sql = "SELECT group_concat(distinct courseId) as courseIds
		 			FROM `userShortlistedCourses` 
		 			WHERE userId = " . mysql_escape_string($data ['userId']) . "
		 			AND status = 'live' ".($data['userId']==-1?' AND sessionId = "'.mysql_escape_string($data['sessionId']).'"':'')
					." and scope='".mysql_escape_string($data['scope'])."'";
		$result = $dbHandle->query ( $sql )->result_array ();
		$resultData ['courseIds'] = explode ( ',', $result [0] ['courseIds'] );
		$resultData ['count'] = empty ( $result [0] ['courseIds'] ) ? 0 : count ( $resultData ['courseIds'] );
		return $resultData;
	}
	
	function getShortlistedCoursesDetail($data) {
		if(empty($data['scope'])){
			$data['scope'] = 'abroad';
		}
		$dbHandle = $this->getReadHandle ();
		if(!empty($data['noOfResultPerPage']) && (!empty($data['rowIdOfLastTuple']) || $data['rowIdOfLastTuple'] == 0))
		{	
		  $limitClause = " LIMIT 0,".mysql_escape_string($data['noOfResultPerPage'])." ";
		  $rowIdClause = $data['rowIdOfLastTuple'] != 0 ? " AND usc.id < ".mysql_escape_string($data['rowIdOfLastTuple'])." " : " ";
		}  
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS
					DISTINCT cd.course_id as course_id,usc.id rowId
					FROM course_details cd JOIN userShortlistedCourses usc
					ON cd.status = 'live' 
					AND cd.course_id = usc.courseId 
					AND usc.status = 'live'
					WHERE usc.userId = " . mysql_escape_string($data ['userId']) . " ".
					"AND usc.scope = '".mysql_escape_string($data['scope'])."'".
					$rowIdClause."
					ORDER BY usc.shortListTime DESC ".$limitClause;
		
		$result['courseIdsArraySortedByTime'] = $dbHandle->query ( $sql )->result_array ();
		
		// fetch the count of total rows fetched
		$query = "SELECT FOUND_ROWS() as totalCount";
		$row   =  $dbHandle->query($query)->row_array();
		$result['totalCount'] = $row['totalCount'];
		return $result;
	}

    public function getShortlistedCoursesCount($data)
    {
        if(empty($data['scope'])){
            $data['scope'] = 'abroad';
        }
        $dbHandle = $this->getReadHandle ();

        $sql = "SELECT count(DISTINCT cd.course_id) as COUNT
                FROM course_details cd JOIN userShortlistedCourses usc
                ON cd.status = 'live' 
                AND cd.course_id = usc.courseId 
                AND usc.status = 'live'
                WHERE usc.userId = " . mysql_escape_string($data ['userId']) .
                " AND usc.scope = '".mysql_escape_string($data['scope'])."'";
        $result = $dbHandle->query ( $sql )->result_array ();
        $count = (isset($result)&&isset($result[0]['COUNT']))?$result[0]['COUNT']:0;
        return $count;
    }
	
	public function putShortListCouresFromCookieToDB($shortListCourseDataToInsertIntoDB,$myBool) {
		
		$dbHandle = $this->getWriteHandle ();
		$dbHandle->trans_start();
		if($myBool){	//Mybool = true means there's a fourth record to insert~
			$insertionRecord = array_pop($shortListCourseDataToInsertIntoDB);
			if(empty($insertionRecord['scope'])){
				$insertionRecord['scope'] = 'abroad';
			}
			$dbHandle->insert ('userShortlistedCourses', $insertionRecord);
		}
		foreach($shortListCourseDataToInsertIntoDB as $data){
			$status = $dbHandle->update ( 'userShortlistedCourses', array (
					'userId' => $data ['userId'] 
			), "sessionId ='" . $data ['sessionId']."' and userId = -1");
		}
		$dbHandle->trans_complete();
		if ($dbHandle->trans_status() === FALSE) {
			$status = false;
			throw new Exception('Transaction Failed');
		} 
		 else {
		 	$status = $resultData ['count'];
		 }
		return 1;
	}
	
	public function existsCourse($courseId){
		$dbHandle = $this->getReadHandle();
		$sql = "select listing_type_id from listings_main where listing_type_id = ? and listing_type='course' and status='live'";
		$result = reset($dbHandle->query($sql,array($courseId))->result_array());
		if(!empty($result)){
			return true;
		}
		return false;
	}
	
	public function checkIfCourseAlreadyShortlisted($data) {
		$dbHandle = $this->getReadHandle();
		
		if(empty($data['scope'])) {
			$data['scope'] = 'abroad';
		}
		
		$sql = "SELECT courseId ".
				"FROM userShortlistedCourses usc ".
				"WHERE usc.status = 'live' ".
				"AND usc.userId = ? ".
				"AND usc.scope = ? ".
				"AND usc.courseId = ?";
				
		$result = reset($dbHandle->query($sql,array($data ['userId'],$data['scope'],$data['courseId']))->result_array());
		
		if(!empty($result['courseId'])) {
			return true;
		}
		else {
			return false;
		}
	}

	function getUserShortlistedCoursesMyShortlist($data) {
		$dbHandle = !empty($dbHandle) ? $dbHandle : $this->getReadHandle ();
		if(empty($data['scope'])){
		   $data['scope'] = 'abroad';
		}
		
		$sql = "SELECT courseId, pageType
		 			FROM `userShortlistedCourses` 
		 			WHERE userId = " . mysql_escape_string($data ['userId']) . "
		 			AND status = 'live' ".($data['userId']==-1?' AND sessionId = "'.mysql_escape_string($data['sessionId']).'"':'')
					." and scope='".mysql_escape_string($data['scope'])."' ".
					"GROUP BY courseId;";
		$result = $dbHandle->query ( $sql )->result_array();
		return $result;
	}

	function addCourseToUserShortlistList($data){
		$dbHandle = $this->getWriteHandle();
		if(!empty($data) && is_array($data)){
			$dbHandle->insert('userShortlistedCourses', $data);
		}
	}

	function removeCourseFromUserShortlistList($userId, $courseId, $data){
		$dbHandle = $this->getWriteHandle();
		$dbHandle->where('userId', $userId);
		$dbHandle->where('courseId', $courseId);
		$dbHandle->update('userShortlistedCourses', $data);
	}
}	