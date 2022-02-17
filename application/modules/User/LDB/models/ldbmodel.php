<?php
class LdbModel extends MY_Model {
	function __construct(){
		parent::__construct('LDB');
	}

	/**
	 * @param object $appId default 1
	 * @param object listingconfig
	 * @return object
	 */

	function getDbHandle($operation='read') {
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}

	function getSpecializationList($courseName,$scope)
	{
		$result=array();
		$dbHandle=$this->getDbHandle();
		$queryCmd="select * from tCourseSpecializationMapping where CourseName=? and SpecializationName!='All' and Status='live'";
                if($scope != ""){
                    $queryCmd.= " and scope =".$dbHandle->escape($scope);
                }
		error_log($queryCmd);		
		$query=$dbHandle->query($queryCmd, array($courseName));
		foreach($query->result_array() as $row)
		{
			$result[]=$row;
		}
// error_log("ashish:". print_r($result, true));
		return $result;
	}

	function getSpecializationListByParentId($parentId)
	{
		$result=array();
		$queryCmd="select * from tCourseSpecializationMapping where ParentId=? and SpecializationName!='All' and Status='live'";
		error_log($queryCmd);
		$dbHandle=$this->getDbHandle();
		$query=$dbHandle->query($queryCmd, array($parentId));
		foreach($query->result_array() as $row)
		{
			$result[]=$row;
		}
error_log("ashish:". print_r($result, true));
		return $result;
	}


    function getCourseForCriteria($categoryId, $scopeFlag, $courseLevel) {
		$dbHandle=$this->getDbHandle();
		
		if($scopeFlag == 'abroad') {
			$queryCmd="select * from tCourseSpecializationMapping where SpecializationName='All' and ( CourseName='Bachelors' OR CourseName='Masters' OR CourseName='PhD') and Status='live' and scope = ". $dbHandle->escape($scopeFlag)
." and CategoryId=". $dbHandle->escape($categoryId)." and CourseLevel1=".$dbHandle->escape($courseLevel);
		}
		else {
			$queryCmd="select * from tCourseSpecializationMapping where SpecializationName='All' and ( CourseName='UG' OR CourseName='PG' ) and Status='live' and scope = ". $dbHandle->escape($scopeFlag)
." and CategoryId=". $dbHandle->escape($categoryId)." and CourseLevel1=".$dbHandle->escape($courseLevel);
		}

        error_log('Ashish ::' . $queryCmd);
		$query=$dbHandle->query($queryCmd);
		return ($query->result_array());
	}

    function getCourseList($categoryId='', $scopeFlag = 'india')
	{
		$result=array();
		$dbHandle=$this->getDbHandle();
		$queryCmd="select * from tCourseSpecializationMapping where SpecializationName='All' and CourseName!='All' and Status='live' and scope = ". $dbHandle->escape($scopeFlag);
		if($categoryId!='')
		{
			$queryCmd.=" and CategoryId=".$dbHandle->escape($categoryId);
		}
        error_log('Ashish ::' . $queryCmd);
		$query=$dbHandle->query($queryCmd);
		foreach($query->result_array() as $row)
		{
			$result[]=$row;
		}
		return $result;
	}
	function getCourseListByGroupTestPrep($groupId)
	{
		$dbHandle=$this->getDbHandle();
		$queryCmd = 	"SELECT tCourseGrouping.courseId
				FROM tCourseGrouping,tGroupCreditDeductionPolicy
				WHERE tCourseGrouping.groupId = tGroupCreditDeductionPolicy.groupId
				AND tCourseGrouping.Status = 'live'
				AND tCourseGrouping.extraFlag = 'testprep'";
		if ($groupId != "") {
			$queryCmd .="	AND tCourseGrouping.groupId =".$dbHandle->escape($groupId);
		}
		$queryCmd .="	GROUP BY tCourseGrouping.courseId";		
		$Result = $dbHandle->query($queryCmd);
		$finalResultArray=array();
		error_log(" sql testprep 1" . $queryCmd);
		foreach ($Result->result_array() as $row)
		{
			$finalResultArray[] = $row;
		}
		return $finalResultArray;
	}
    function getCourseListByGroup($categoryId='',$groupId='')
    {
        $result=array();
	$dbHandle=$this->getDbHandle();
        if($groupId!='')
        {
            $queryCmd="select * from tCourseSpecializationMapping,tCourseGrouping, categoryBoardTable where SpecializationName='All' and CourseName!='All' and tCourseGrouping.Status='live' and tCourseSpecializationMapping.Status='live' and tCourseGrouping.courseId=tCourseSpecializationMapping.SpecializationId and categoryBoardTable.boardId=tCourseSpecializationMapping.CategoryId ";
            if($categoryId!='')
            {
                $queryCmd.=" and CategoryId=".$dbHandle->escape($categoryId);
            }
            $queryCmd.=" and tCourseGrouping.groupId=".$dbHandle->escape($groupId);
        }
        else
        {
            $queryCmd="select * from tCourseSpecializationMapping, categoryBoardTable where SpecializationName='All' and CourseName!='All' and tCourseSpecializationMapping.Status='live' and tCourseSpecializationMapping.SpecializationId not in (select courseId from tCourseGrouping where tCourseGrouping.Status='live') and categoryBoardTable.boardId=tCourseSpecializationMapping.CategoryId ";
            if($categoryId!='')
            {
                $queryCmd.=" and CategoryId=".$dbHandle->escape($categoryId);
            }
        }
        $queryCmd.=" order by CategoryId, CourseReach desc";
        error_log($queryCmd);        
        $query=$dbHandle->query($queryCmd);
        foreach($query->result_array() as $row)
        {
            $result[]=$row;
        }
        return $result;
    }

    function getGroupList()
    {
        $result=array();
		$queryCmd="select * from tGroupCreditDeductionPolicy where status='live' order by groupId,FIELD(actionType,'view','email','sms')";
		$dbHandle=$this->getDbHandle();
		$query=$dbHandle->query($queryCmd);
		foreach($query->result_array() as $row)
		{
			$result[]=$row;
		}
		return $result;
    }

	function getCityStateList()
	{
		$countryCityStateMap=array();
		$queryCmd="select countryId,name from countryTable where enabled=0 and countryId=2";
		$dbHandle=$this->getDbHandle();
		$query=$dbHandle->query($queryCmd);
		foreach($query->result_array() as $row)
		{
			$result=array();
			$result['CountryName']=$row['name'];
			$result['CountryId']=$row['countryId'];
			$queryCmd1="select state_id,state_name from stateTable where enabled=0 and countryId=? order by state_name";
			error_log($queryCmd1);
			$query1=$dbHandle->query($queryCmd1, array($row['countryId']));
			foreach($query1->result_array() as $row1)
			{
				$state=array();
				$state['StateName']=$row1['state_name'];
				$state['StateId']=$row1['state_id'];
				$queryCmd2="select city_id,city_name,tier from countryCityTable where enabled=0 and state_id=? order by city_name";
				error_log($queryCmd2);
				$query2=$dbHandle->query($queryCmd2, array($row1['state_id']));
				foreach($query2->result_array() as $row2)
				{
					$cityArray=array();
					$cityArray['CityName']=$row2['city_name'];
					$cityArray['CityId']=$row2['city_id'];
					$cityArray['Tier']=$row2['tier'];
					$state['cityMap'][]=$cityArray;
				}

				$result['stateMap'][]=$state;
			}
			$countryCityStateMap[]=$result;
		}
		return $countryCityStateMap;
	}

    function addCourseToGroup($groupId,$courseId,$ExtraFlag)
    {
	$tCourseGroupingColumnList['groupId'] =$groupId;
	$tCourseGroupingColumnList['courseId'] = $courseId;
	$tCourseGroupingColumnList['status'] = 'live';
	if ($ExtraFlag == 'true') {
		$tCourseGroupingColumnList['extraFlag'] = 'testprep';
	} else {
		$tCourseGroupingColumnList['extraFlag'] = 'course';
	}
	$dbHandle=$this->getDbHandle('write');
        $insertQuery= $dbHandle->insert_string('tCourseGrouping',$tCourseGroupingColumnList);
	error_log(" insQ add" . $insertQuery );
        $dbHandle->query($insertQuery);
        return 1;
    }

    function removeCourseFromGroup($groupId,$courseId,$ExtraFlag)
    {
	$dbHandle = $this->getDbHandle('write');
	if ($ExtraFlag == 'true')
	{
		$extraFlag = 'true';
		$sql = " and extraFlag ='testprep'";
	} else {
		$sql = "";
	}
        $updateQuery = "update tCourseGrouping set status='history' where groupId=? and courseId=? " . $sql;
        error_log(" insQ add" . $updateQuery );
        $dbHandle->query($updateQuery, array($groupId, $courseId));
        return 1;
    }

    function addGroupCreditConsumptionPolicy($groupId,$action,$creditCount)
    {
       $tGroupCreditDeductionPolicyColumnList['groupId'] =$groupId;
       $tGroupCreditDeductionPolicyColumnList['actionType'] = $action;
       $tGroupCreditDeductionPolicyColumnList['deductcredit'] = $creditCount;
       $tGroupCreditDeductionPolicyColumnList['status'] = 'live';
       $dbHandle=$this->getDbHandle('write');
       $updateQuery = "update tGroupCreditDeductionPolicy set status='history' where groupId=? and actionType=?";
       $dbHandle->query($updateQuery, array($groupId, $action));
       $insertQuery= $dbHandle->insert_string('tGroupCreditDeductionPolicy',$tGroupCreditDeductionPolicyColumnList);
       $dbHandle->query($insertQuery);
       return 1;
    }

	function getLeadsForInstitutes($institute_ids,$processed = true)
	{
		if(!is_array($institute_ids) || !count($institute_ids))
		{
			return false;
		}
		$leads = array();

		$dbHandle = $this->getDbHandle('write');

		/*
		 Get leads of type course
		*/
		$query = $dbHandle->query("
									SELECT  lms.id,t.firstname,t.lastname,lms.email,lms.contact_cell as mobile,lms.submit_date
									".($processed?",loc.city,loc.locality ":"")."
									,course.courseTitle as course
									FROM tempLMSTable lms
									".($processed?" LEFT JOIN responseLocationPref loc ON loc.response_id = lms.id ":"")."
									LEFT JOIN course_details course ON (course.course_id = lms.listing_type_id AND
																		lms.listing_type = 'course' AND
																		course.status = 'live')
									INNER JOIN tuserflag tf ON tf.userid = lms.userId
									INNER JOIN tuser t ON t.userid = lms.userId
									WHERE course.institute_id IN (".implode(',',$institute_ids).")
									AND lms.listing_type = 'course'
                                                                        AND lms.listing_subscription_type ='paid'
									AND tf.isTestUser = 'NO'
									".($processed?" AND loc.processed = 'No'":"")
								);

		$leads = $query->result_array();

		/*
		 Get leads of type institute
		*/

		$query = $dbHandle->query("
									SELECT  lms.id,t.firstname,t.lastname,lms.email,lms.contact_cell as mobile,lms.listing_type_id,lms.submit_date
									".($processed?",loc.city,loc.locality ":"")."
									FROM tempLMSTable lms
									".($processed?" LEFT JOIN responseLocationPref loc ON loc.response_id = lms.id ":"")."
									INNER JOIN tuserflag tf ON tf.userid = lms.userId
									INNER JOIN tuser t ON t.userid = lms.userId
									WHERE lms.listing_type_id IN (".implode(',',$institute_ids).")
									AND lms.listing_type = 'institute'
                                                                        AND lms.listing_subscription_type ='paid'
									AND tf.isTestUser = 'NO'
									".($processed?" AND loc.processed = 'No'":"")
								);

		$rows = $query->result_array();

		foreach($rows as $row)
		{
			/*
			 Get flagship course for institute
			*/

			$query = $dbHandle->query("
										SELECT courseTitle
										FROM course_details
										WHERE institute_id = ?
										AND status = 'live'
										ORDER BY course_order
										LIMIT 1
									  ", array($row['listing_type_id']));
			$result = $query->row();
			$row['course'] = $result->courseTitle;

			unset($row['listing_type_id']);

			$leads[] = $row;
		}
		if($processed){
			$this->_updateLeadProcessedStatus($dbHandle,$leads);
		}
		return $leads;
	}

	private function _updateLeadProcessedStatus($dbHandle,$leads)
	{
		foreach($leads as $lead)
		{
			$dbHandle->query("UPDATE responseLocationPref SET processed = 'Yes' WHERE response_id = ? ", array($lead['id']));
		}
	}
    
    function saveEmailsForListing($listingId,$listingLocationId,$email,$listingType)
    {
        $dbHandle=$this->getDbHandle('write');
        $queryCmd = "insert into responseExportPref values('',".mysql_escape_string($listingId).",'".mysql_escape_string($listingType)."',".mysql_escape_string($listingLocationId).",'".mysql_escape_string($email)."') ON DUPLICATE KEY UPDATE emails='".mysql_escape_string($email)."'";
        error_log(" funny save email" . $queryCmd );
        $query = $dbHandle->query($queryCmd);
        return 1;
    }

	function getListingtobeExported()
	{
		$dbHandle=$this->getDbHandle('read');
		$queryCmd = "SELECT lm.username as clientId, lm.listing_title as title, 
		rep.listingId, rep.listingType, rep.listingLocationId as locationId, rep.emails as email 
		FROM responseExportPref as rep, listings_main as lm WHERE rep.listingId = lm.listing_type_id 
		and rep.listingType = lm.listing_type and lm.status='live' and rep.emails!=''";
		error_log(" funny getListingtobeExported" . $queryCmd);
		$emailExportQuery = $dbHandle->query($queryCmd);
		$results = $emailExportQuery->result_array();
		
		$instituteIds = array();
		$universityIds = array();
		foreach($results as $result) {
			if($result['listingType'] == 'institute' || $result['listingType'] == 'university_national') {
				$instituteIds[] = $result['listingId'];
			}
			else if($result['listingType'] == 'university') {
				$universityIds[] = $result['listingId'];
			}
		}
		
		if(count($instituteIds)) {
			$queryCmd = "SELECT primary_id as institute_id 
				     FROM shiksha_courses
				     WHERE status = 'live'
				     AND primary_id IN (".implode(',',$instituteIds).")
				     GROUP BY primary_id";
			$query = $dbHandle->query($queryCmd);
			
			$validInstituteIds = array();
			foreach($query->result_array() as $row) {
				$validInstituteIds[$row['institute_id']] = TRUE;
			}
		}
		
		if(count($universityIds)) {
			$queryCmd = "SELECT ium.university_id
				     FROM course_details as cd
				     INNER JOIN institute_university_mapping as ium
					ON cd.institute_id = ium.institute_id AND ium.university_id IN (".implode(',',$universityIds).") AND ium.status = 'live'
				     WHERE cd.status = 'live'
				     GROUP BY ium.university_id";
			$query = $dbHandle->query($queryCmd);
			
			$validUniversityIds = array();
			foreach($query->result_array() as $row) {
				$validUniversityIds[$row['university_id']] = TRUE;
			}
		}
		
		$validResults = array();
		foreach($results as $result) {
			if($validInstituteIds[$result['listingId']] || $validUniversityIds[$result['listingId']]) {
				$validResults[] = $result;
			}
		}
		
		return $validResults;
	}

	
	public function getResponsesForClient($clientId, $userIds)
	{
		//$dbHandle=$this->getDbHandle('read');
		$dbHandle = $this->getReadHandleByModule('Listing');
		$sql ="SELECT DISTINCT t.userId FROM tempLMSTable t INNER JOIN listings_main l ON (l.listing_type = t.listing_type AND l.listing_type_id = t.listing_type_id AND l.status = 'live') AND t.listing_subscription_type ='paid' AND l.username = ?";

		if(count($userIds)>0){
			$sql .= " and t.userId in (".implode(',', $userIds).")";
		}

		$query = $dbHandle->query($sql, array($clientId));
        $responseResults = $query->result_array();
        $responses = array();
        foreach($responseResults as $result) {
            $responses[] = $result['userId'];
        }
		return $responses;
	}
	
	public function getResponseCountForCourses($courseIds)
	{
		$dbHandle=$this->getDbHandle('read');
		$query = $dbHandle->query("SELECT listing_type_id,count(1) as cnt ".
								  "FROM tempLMSTable t ".
								  "WHERE listing_type = 'course' ".
								  "AND listing_type_id IN (".implode(',',$courseIds).") ".
                                                                  "AND t.listing_subscription_type='paid' ".
								  "AND submit_date > '".date('Y-m-d',strtotime('-1 Month'))."' ".
								  "GROUP BY listing_type_id"
                    );
        $responseResults = $query->result_array();
        $responses = array();
        foreach($responseResults as $result) {
            $responses[$result['listing_type_id']] = $result['cnt'];
        }
		return $responses;
	}
	
	public function getLDBCourseByName($courseName,$categoryId)
	{
		$dbHandle=$this->getDbHandle('read');
		$query = $dbHandle->query("SELECT * ".
									"FROM tCourseSpecializationMapping  ".
									"WHERE CourseName = ? ".
									"AND CategoryId = ? AND SpecializationName = 'All' "
								,array($courseName,$categoryId));
        return $query->row_array();
	}
	
	public function getStudyAbroadSpecializationsByName($courseName,$courseType)
	{
		$dbHandle = $this->getDbHandle('read');
		if($courseType == 'popular') {
			$sql = "SELECT SpecializationId as id FROM tCourseSpecializationMapping WHERE CourseName = ?";
		}
		else {
			$sql = "SELECT boardId as id FROM categoryBoardTable WHERE name = ? AND flag = 'studyabroad' AND isOldCategory = '0'";
		}
		
		$query = $dbHandle->query($sql,array($courseName));
		$row = $query->row_array();
		
		return $this->getStudyAbroadSpecializations($row['id'],$courseType);
	}
	
	
	public function getStudyAbroadSpecializations($id,$type)
	{
		$dbHandle = $this->getDbHandle('read');
		
		if($type == 'category') {
			$sql =  "SELECT boardId as id,name ".
					"FROM categoryBoardTable ".
					"WHERE parentId = ? AND flag = 'studyabroad' AND isOldCategory = '0' ORDER BY name";		
		}
		else {
			$sql =  "SELECT boardId as id,name ".
					"FROM abroadCategoryPageData a ".
					"INNER JOIN categoryBoardTable b ON b.boardId = a.sub_category_id ".
					"WHERE a.ldb_course_id = ? AND status = 'live' ORDER BY name";
		}
		
		$query = $dbHandle->query($sql,array($id));
		$rows = $query->result_array();
		
		$specializations = array();
		foreach($rows as $row) {
			$specializations[$row['id']] = $row['name'];
		}
		
		return $specializations;
	}
	
	public function getPopularAbroadLDBCourseIdByName($courseName)
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql =  "SELECT SpecializationId ".
				"FROM tCourseSpecializationMapping ".
				"WHERE categoryId = 1 AND parentId = 1 AND Status = 'live' AND scope = 'abroad' AND CourseReach = 'national' ".
				"AND CourseName = ?";		
		$query = $dbHandle->query($sql,array($courseName));
		$row = $query->row_array();
		
		return $row['SpecializationId'];
	}
	
	public function getAbroadCategoryIdByName($categoryName)
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql =  "SELECT boardId ".
				"FROM categoryBoardTable ".
				"WHERE isOldCategory = '0' AND flag = 'studyabroad' ".
				"AND name = ?";
						
		$query = $dbHandle->query($sql,array($categoryName));
		$row = $query->row_array();
		
		return $row['boardId'];
	}
        
        public function getAbroadCategoryIds()
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql =  "SELECT boardId ".
				"FROM categoryBoardTable ".
				"WHERE isOldCategory = '0' AND flag = 'studyabroad' ".
				"AND parentId = 1";
						
		$query = $dbHandle->query($sql);
                $category = array();
		foreach ($query->result_array() as $row){
                    $category[] = $row['boardId'];
                }
                return $category;
	}
	
	public function getAbroadSpecializationNames($specializationIds)
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql =  "SELECT name ".
				"FROM categoryBoardTable ".
				"WHERE boardId in (".implode(',',$specializationIds).")";
						
		$query = $dbHandle->query($sql);
		$rows = $query->result_array();
		
		$specializationNames = array();
		foreach($rows as $row) {
			$specializationNames[] = $row['name'];
		}
		
		return $specializationNames;
	}
	
	public function getLDBCourseToCategoryMapping()
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql = "SELECT SpecializationId,CategoryId FROM tCourseSpecializationMapping WHERE Status = 'live'";
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		$LDBCourseToCategoryMapping = array();
		foreach($results as $result) {
			$LDBCourseToCategoryMapping[$result['SpecializationId']] = $result['CategoryId'];
		}
		
		return $LDBCourseToCategoryMapping;
	}
	
	public function getLDBCourseToSubCategoryMapping()
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql = "SELECT ldbCourseID,categoryID FROM LDBCoursesToSubcategoryMapping WHERE status = 'live'";
		$query = $dbHandle->query($sql);
		$results = $query->result_array();
		$LDBCourseToSubCategoryMapping = array();
		foreach($results as $result) {
			$LDBCourseToSubCategoryMapping[$result['ldbCourseID']] = $result['categoryID'];
		}
		
		return $LDBCourseToSubCategoryMapping;
	}
	
	public function searchLeadsMR($dataArr, $user, $responseUsers = array()) {
		if(count($responseUsers) === 0) {
			return array('userIds' => array(), 'totalRows' => 0);
		}
		
		$requestTime = date("Y-m-d H:i:s");
		
		$dbHandle = $this->getDbHandle('read');
		
		/**
		  Mapping or SearchFields with table Columns with comparision operator
		 */
		$tableKeyMapping = array(
			'CurrentLocation' => array('table' => 'tuser', 'query' => 'tuser.city='),
			'ExamScore' => array('table' => 'tUserEducation tue1', 'query' => ''),
			'GraduationCompletedFrom' => array('table' => 'tUserEducation tue', 'query' => 'tue.Level = \'UG\' and tue.CourseCompletionDate>='),
			'GraduationCompletedTo' => array('table' => 'tUserEducation tue', 'query' => 'tue.Level = \'UG\' and tue.CourseCompletionDate<='),
			'XIICompletedFrom' => array('table' => 'tUserEducation tue', 'query' => 'tue.Level = \'12\' and tue.CourseCompletionDate>='),
			'XIICompletedTo' => array('table' => 'tUserEducation tue', 'query' => 'tue.Level = \'12\' and tue.CourseCompletionDate<='),
			'Viewed' => array('table' => 'LDBLeadContactedTracking', 'query' => 'LDBLeadContactedTracking.ContactType="view" and LDBLeadContactedTracking.ClientId='),
			'Emailed' => array('table' => 'LDBLeadContactedTracking', 'query' => 'LDBLeadContactedTracking.ContactType="email" and LDBLeadContactedTracking.ClientId='),
			'Smsed' => array('table' => 'LDBLeadContactedTracking', 'query' => 'LDBLeadContactedTracking.ContactType="sms" and LDBLeadContactedTracking.ClientId=')
		);
		
		
		/*
		  Mapping for Base Joiners or Tables
		 */
		$tableBaseJoinerMapping = array(
			'tUserEducation tue' => 'tue.UserId=tuser.userid and tue.Status="live"',
			'tUserEducation tue1' => 'tue1.UserId=tuser.userid and tue1.Status="live"',
			'LDBLeadContactedTracking' => 'LDBLeadContactedTracking.UserId=tuser.userid'
		);
		
		
		/*
		  Mapping of Prerequisite Tables
		 */
		$tableDependencyMapping = array(
			'tuser' => array('tuserflag'),
			'tUserEducation' => array('tuser', 'tuserflag'),
			'LDBLeadContactedTracking' => array('tuser', 'tuserflag')
		);
		
		
		/*
		  Handling of Location And OR
		 */
		if (isset($dataArr['LocationAndOr']) && $dataArr['LocationAndOr'] == 1) {
			$keyOrMap[] = array('CurrentLocation', 'PreferredLocation');
		}
		
		
		$baseQuery = "select tuser.userid from ";
		$tableMap = array();  //Array of required tables
		$tableBaseJoinerMap = array(); //Array or Base joiners
		$queryAppend = array(); //Array or where clauses
		$showContactQuery = ''; //Query Parameter for Already Viewed Contact Display on and off
		foreach ($dataArr as $key => $val) {
			if (isset($val)) {
				if ((empty($val) || $val == '' || count($val) == 0) && !is_numeric($val)) {
				    continue;
				}
				
				if(is_array($val)) {
					foreach($val as $valKey => $valValue) {
						if(!is_array($valValue)) {
							$val[$valKey] = $dbHandle->escape_str($valValue);
						}
					}
				}
				else {
					$val = $dbHandle->escape_str($val);
				}
				
				if (array_key_exists($key, $tableKeyMapping)) {
					$tableReq = $tableKeyMapping[$key]['table'];
					if (!array_key_exists($tableReq, $tableMap)) {
						$tableMap[$tableReq] = $tableReq;
						if (isset($tableBaseJoinerMapping[$tableReq])) {
							$tableBaseJoinerMap[$tableReq] = $tableBaseJoinerMapping[$tableReq];
						}
					}
					if (is_array($val)) {
						$query = $tableKeyMapping[$key]['query'];
						$appended = false;
						foreach ($keyOrMap as $id => $value) {
							if (in_array($key, $value)) {
								foreach ($value as $newVal) {
									if (array_key_exists($newVal, $queryAppend)) {
										$queryAppend[$newVal].=" OR " . $query . "'" . implode("' OR " . $query . "'", $val) . "'";
										$appended = true;
									}
								}
							}
						}
						if (!$appended) {
							$queryAppend[$key] = " (" . $query . "'" . implode("' OR " . $query . "'", $val) . "'";
						}
					}
					else {
						$appended = false;
						foreach ($keyOrMap as $id => $value) {
							if (in_array($key, $value)) {
								foreach ($value as $newVal) {
									if (array_key_exists($newVal, $queryAppend)) {
										$queryAppend[$newVal].=" OR " . $tableKeyMapping[$key]['query'] . "'" . $val . "'";
										$appended = true;
									}
								}
							}
						}
						if (!$appended) {
							$queryAppend[$key] = " (" . $tableKeyMapping[$key]['query'] . "'" . $val . "'";
						}
					}
				}
				
				
				/*
				  Special Handling of ExamScore
				 */
				if ($key == 'ExamScore') {
					
					/*
					$appendArray = array();
					
					foreach ($val as $examName => $value) {
						$tableMap['tUserEducation ' . $examName] = 'tUserEducation ' . $examName;
						$tableBaseJoinerMap['tUserEducation ' . $examName] = $examName . '.UserId=tuser.userid and ' . $examName . '.Status="live"';
						$appendQuery = '(' . $examName . '.Name="' . $dbHandle->escape_str($examName) . '"';
						if (!empty($value['min'])) {
							$appendQuery.= " and " . $examName . ".Marks>='" . $dbHandle->escape_str($value['min'])."'";
						}
						if (!empty($value['max'])) {
							$appendQuery.= " and " . $examName . ".Marks<='" . $dbHandle->escape_str($value['max'])."'";
						}
						if (!empty($value['year'])) {
							$appendQuery.= " and " . $examName . ".CourseCompletionDate='" . $dbHandle->escape_str($value['year'])."'";
						}
						if (!empty($appendQuery)) {
							$appendArray['tUserEducation ' . $examName] = $appendQuery;
						}
					}
					if (!empty($appendQuery)) {
						$queryAppend['ExamScore'] = "(" . implode(") OR ", $appendArray) . ")";
					}
					*/
					$tempQuery = array();
					$mainQuery = " ( ";
					$count_exam = 1;
					$len = count($val);
					
					foreach ($val as $examName => $value) {
						
						$tempQuery[$examName] = '(tue1.name = "'.$examName.'"';
						if (!empty($value['min'])) {
							$tempQuery[$examName] .= " and tue1.Marks >= '".$dbHandle->escape_str($value['min'])."'";
						}
						if (!empty($value['max'])) {
							$tempQuery[$examName] .= " and tue1.Marks <= '".$dbHandle->escape_str($value['max'])."'";	
						}
						if (!empty($value['year'])) {
							$tempQuery[$examName] .= " and tue1.CourseCompletionDate = '".$dbHandle->escape_str($value['year'])."'";	
						}

						$tempQuery[$examName] .= " )";
						if($count_exam == $len){
							$mainQuery .= $tempQuery[$examName];
						} else {
							$mainQuery .= $tempQuery[$examName]." OR ";
						}


						$count_exam++;
					}

					//$mainQuery .= " ) ";
					$queryAppend['ExamScore'] = $mainQuery;
					unset($mainQuery);
					unset($tempQuery);
					
		
				}
				
				if ($key == 'DontShowContacted') {
					if ($val == 1) {
						$showContactQuery.=" and tuser.userid not in (select UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.ClientId=" . $dbHandle->escape($user) . ") ";
					}
				}
				if ($key == 'DontShowViewed') {
					if ($val == 1) {
						$showContactQuery.=" and tuser.userid not in (select UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.ClientId=" . $dbHandle->escape($user) . " and LDBLeadContactedTracking.ContactType='view') ";
					}
				}
				if ($key == 'DontShowEmailed') {
					if ($val == 1) {
						$showContactQuery.=" and tuser.userid not in (select UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.ClientId=" . $dbHandle->escape($user) . " and LDBLeadContactedTracking.ContactType='email') ";
					}
				}
				if ($key == 'DontShowSmsed') {
					if ($val == 1) {
						$showContactQuery.=" and tuser.userid not in (select UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.ClientId=" . $dbHandle->escape($user) . " and LDBLeadContactedTracking.ContactType='sms') ";
					}
				}
			}
		}
		
		
		/*
		  Handling of Missing Prerequisite Tables
		 */
		foreach ($tableMap as $tableName) {
			$tableElement = split(" ", $tableName);
			$tableName = $tableElement[0];
			$tableRequired = $tableDependencyMapping[$tableName];
			if (is_array($tableRequired)) {
				foreach ($tableRequired as $value) {
					if (!array_key_exists($value, $tableMap)) {
						$tableMap[$value] = $value;
					}
				}
			}
		}
		
                /*
                  Final Query Generater
                 */
		if(count($tableMap) === 0) {
			$finalQuery = $baseQuery . "tuser, tuserflag where";
		}
		else {
			if (count($tableBaseJoinerMap) != 0) {
				$finalQuery = $baseQuery . implode(",", $tableMap) . " where " . implode(" and ", $tableBaseJoinerMap) . " and " . implode(" ) and ", $queryAppend) . ") and";
			}
			else {
				$finalQuery = $baseQuery . implode(",", $tableMap) . " where " . implode(" ) and ", $queryAppend) . ") and";
			}
		}
		
		$finalQuery .= " tuser.userId = tuserflag.userId and tuserflag.mobileverified = '1' and tuserflag.hardbounce = '0' and tuserflag.ownershipchallenged = '0' and tuserflag.abused = '0' and tuserflag.softbounce = '0' and tuserflag.isTestUser = 'NO'  and tuser.usergroup NOT IN (\"sums\", \"enterprise\", \"cms\", \"experts\", \"lead_operator\", \"saAdmin\", \"saCMS\", \"saContent\", \"saSales\") and tuser.userId IN(".implode(',', $responseUsers).")";
		
		//error_log('HusamQ'.$finalQuery); 
		try {
			//$queryStartTime = microtime(true);
			$query = $dbHandle->query($finalQuery);
			//$queryRunTime = microtime(true) - $queryStartTime;
			//error_log("LDBQUERY" . $finalQuery." took ".$queryRunTime);
		}
		catch (Exception $e) {
			$responseArray = array('error' => 'Caught DB Exception ' . $e);
			$response = array(base64_encode(json_encode($responseArray)), 'string');
			return $this->xmlrpc->send_response($response);
		}
		 
		unset($finalQuery);
		   
		$userIds = array();
		$k = 0;
		$user_ids_list = array();
		foreach ($query->result() as $row) {
			$user_ids_list[$row->userid] = strtotime($row->SubmitDate);
		}
            
		arsort($user_ids_list);
		$userIds = array_keys($user_ids_list);
		
		unset($user_ids_list);
            
		if($showContactQuery) {
			$showContactQuery = ltrim($showContactQuery,"and tuser.userid not in");
			$showContactQuery = str_replace("and tuser.userid not in", "UNION", $showContactQuery);
			$contacted_user_ids = array();
			try {
				//$startTime = microtime_float();
				$query = $dbHandle->query($showContactQuery);
				unset($showContactQuery);
				//error_log("LDBQUERYCONTACTED" . $showContactQuery." took ".(microtime_float() - $startTime));
				//$startTime = microtime_float();
				$result_array = $query->result_array();
				foreach ($result_array as $row) {
					$contacted_user_ids[$row['UserId']] = $row['UserId'];
				}
				
				unset($result_array);
				
				$final_user_list = array();
				if(count($contacted_user_ids) >0 && count($userIds)>0) {
					foreach ($userIds as $key=>$user_id) {
						if(!$contacted_user_ids[$user_id]) {
							$final_user_list[] = $user_id;
						}
					} 
					
					$userIds = $final_user_list;
					
					$final_user_list = array(); 
					$contacted_user_ids = array();
				}
				
				//error_log("LDBQUERYCONTACTEDJHR2 took ".(microtime_float() - $startTime));
			}
			catch (Exception $e) {
				// do nothing
			}
		}
		
		$totalRows = count($userIds);
            
		$searchResult = array('userIds' => $userIds, 'totalRows' => $totalRows);
		
		return $searchResult;
	}
	
	public function getDesiredCourseDetailsbyUserId($userId)
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql = "SELECT UserId, PrefId, DesiredCourse, ExtraFlag FROM tUserPref WHERE Status = 'live' AND UserId = ?";
		
		$query = $dbHandle->query($sql, array($userId));
		$result = $query->row_array();
		$userDesiredCourseDetails = array();
		
		if($result['ExtraFlag'] != 'testprep') {
			
			$userDesiredCourseDetails['DesiredCourse'] = $result['DesiredCourse'];
			$userDesiredCourseDetails['ExtraFlag'] = $result['ExtraFlag'];
			
		} else {
			
			$sql = "SELECT blogid FROM tUserPref_testprep_mapping WHERE prefid = ?";
			$query = $dbHandle->query($sql, array($result['PrefId']));
			$testprep_result = $query->row_array();
			$userDesiredCourseDetails['DesiredCourse'] = $testprep_result['blogid'];
			$userDesiredCourseDetails['ExtraFlag'] = 'testprep';
			
		}
		
		return $userDesiredCourseDetails;
	}

	function getCreditToConsume($userIds, $action, $ExtraFlag="false", $course_id) {

       	$dbHandle = $this->getDbHandle('read');

       	$userIDCSV = implode(",", $userIds);
        if ($ExtraFlag == 'true') {
            $queryCmd = "SELECT tUserPref.userid,deductcredit FROM tCourseGrouping,tGroupCreditDeductionPolicy,tUserPref,tUserPref_testprep_mapping 
            WHERE tUserPref.UserId in ($userIDCSV) 
            AND tUserPref_testprep_mapping.blogid = tCourseGrouping.courseId AND tUserPref_testprep_mapping.prefid = tUserPref.PrefId
			AND tGroupCreditDeductionPolicy.groupId=tCourseGrouping.groupId
			AND tGroupCreditDeductionPolicy.actionType = ".$dbHandle->escape($action)."
			AND tCourseGrouping.status = 'live'
			AND tCourseGrouping.extraFlag = 'testprep'
			AND tGroupCreditDeductionPolicy.status = 'live' group by tUserPref.UserId";
        } else if(!empty($course_id)) {
             $queryCmd = "select tGroupCreditDeductionPolicy.deductcredit from tCourseGrouping, tGroupCreditDeductionPolicy where tCourseGrouping.courseId = ".$dbHandle->escape($course_id)." and tGroupCreditDeductionPolicy.groupId=tCourseGrouping.groupId and tGroupCreditDeductionPolicy.actionType=".$dbHandle->escape($action)." and tCourseGrouping.status='live' and tGroupCreditDeductionPolicy.status='live'";
        } else {
            $queryCmd = "select tUserPref.userid,deductcredit from tCourseGrouping, tGroupCreditDeductionPolicy, tUserPref where tUserPref.UserId in ($userIDCSV) and ";
            $queryCmd .="tUserPref.DesiredCourse=tCourseGrouping.courseId and tGroupCreditDeductionPolicy.groupId=tCourseGrouping.groupId and ";
            $queryCmd .="actionType=".$dbHandle->escape($action)." and tCourseGrouping.status='live' and tGroupCreditDeductionPolicy.status='live' and tUserPref.Status='live' group by tUserPref.UserId";
        }

        error_log(" sql Credit-2-Consume " . $queryCmd);
        $query = $dbHandle->query($queryCmd);
        if(!empty($course_id)) {
            $resultArray = $query->row_array();
            $retArray = array();$i=0;
            foreach ($userIds as $userId) {
                $retArray[$i]["userid"] = $userId;
                $retArray[$i]["deductcredit"] = $resultArray['deductcredit'];
                $i++;
            }

            return $retArray;
        } else {
            return $resultArray = $query->result_array();            
        }
    }
	

    function saveManualLDBAccessData($data)
    {
        $dbHandle=$this->getDbHandle('write');

        $queryCmd = "insert into manualLDBAccessData (client_id, ended_on, status, salesUserId, salesUserName) values (?,?,?,?,?)";
        $query = $dbHandle->query($queryCmd, array($data['client_id'], $data['ended_on'], 'live', $data['salesUserId'], $data['salesUserName']));
        return true;
    }

    public function getManualLDBAccessData($client_id = '', $date = '')
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql = "SELECT ma.*,u.firstname,u.lastname from manualLDBAccessData ma inner join tuser u on ma.client_id = u.userid where ma.status = ?";
		if(!empty($client_id)) {
			$sql .= " and ma.client_id = ".$dbHandle->escape($client_id);
		}
		if(!empty($date)) {
			$sql .= " and ma.ended_on >= ".$dbHandle->escape($date);
		}
		$query = $dbHandle->query($sql, array('live'));
		$results = $query->result_array();

		return $results;
	}
	
	function deleteManualLDBAccessData($id)
    {
        $dbHandle=$this->getDbHandle('write');

        $queryCmd = "update manualLDBAccessData set status = ? where id = ?";
        $query = $dbHandle->query($queryCmd, array('history', $id));
        return true;
    }
    
    public function getManualLDBAccessDataByStream($client_id = '')
	{
		$dbHandle = $this->getDbHandle('read');
		
		$sql = "SELECT ma.*,u.firstname,u.lastname ".
		       "from manualLDBAccessDataByStream ma inner join tuser u on ma.client_id = u.userid where ma.status = ?";
		       
		if(!empty($client_id)) {
			$sql .= " and ma.client_id = ".$dbHandle->escape($client_id);
		}
		
		$query = $dbHandle->query($sql, array('live'));
		$results = $query->result_array();
		
		return $results;
	}
	
	function deleteManualLDBAccessDataByStream($id)
    {
		if($id <=0) {
			return;
		}
		
        $dbHandle=$this->getDbHandle('write');
        $queryCmd = "update manualLDBAccessDataByStream set status = ? where id = ?";
        $query = $dbHandle->query($queryCmd, array('history', $id));
        
        return true;
    }
    
    function saveManualLDBAccessDataByStream($client_id,$data) {
		    		
        $dbHandle=$this->getDbHandle('write');        
        if(is_array($data) && count($data)>0) {
			// update
			$update_data = "UPDATE manualLDBAccessDataByStream set status='history' where client_id=?";
			$dbHandle->query($update_data,array($client_id));
			//insert			
			$query = $dbHandle->insert_batch('manualLDBAccessDataByStream',$data);			
		}
    }

    function getExcludedList(){
    	$dbHandle = $this->getDbHandle('read');

    	$sql= "select userid from LDBExclusionList where status = 'live'";

    	$result = $dbHandle->query($sql)->result_array();

    	foreach ($result as $key => $value) {
            $finalResult[] = $value['userid'];
        }

        return $finalResult;

    }

	function getUsersAnAPoints($userIds)
	{
		$result=array();
		if(!empty($userIds) && count($userIds) > 0){
			//$queryCmd="select userId, userpointvaluebymodule,levelId from userpointsystembymodule where moduleName = 'AnA' AND userId IN (".implode(',',$userIds).") order by userpointvaluebymodule desc ";
			$queryCmd="select userId, userpointvaluebymodule,levelId from userpointsystembymodule where moduleName = 'AnA' AND userId IN (?) ";
			$dbHandle=$this->getDbHandle();
			$query=$dbHandle->query($queryCmd, array($userIds));
			foreach($query->result_array() as $row)
			{
				$result[$row['userId']]=$row;
			}
		}
		return $result;
	}

	/*
	 * Function to get userids who created reponse in given time period for given course ids
	 */
	function getResponseDataForCourses($courseIds = array(), $from, $to, $start=0, $end=500){
		if(empty($courseIds) || empty($from) || empty($to)){
			return array();
		}

		$dbHandle = $this->getDbHandle('read');
		$sql = 'SELECT UserId, listing_type_id AS courseId, action FROM tempLMSTable WHERE submit_date>=? AND submit_date<=? AND listing_type_id IN ('.implode(',', $courseIds).') AND listing_type="course" AND listing_subscription_type = "paid" LIMIT '.$start.', '.$end;
		return $dbHandle->query($sql, array($from, $to))->result_array();
	}

	/*
	 * Function to get response count in given time period for given course ids
	 */
	function getResponseCountForCoursesInTimeRange($courseIds = array(), $from, $to){
		if(empty($courseIds) || empty($from) || empty($to)){
			return array('0'=>array('count'=>0));
		}

		$dbHandle = $this->getDbHandle('read');
		$sql = 'SELECT count(*) AS count FROM tempLMSTable WHERE submit_date>=? AND submit_date<=? AND listing_type_id IN ('.implode(',', $courseIds).') AND listing_type="course" AND listing_subscription_type = "paid"';
		return $dbHandle->query($sql, array($from, $to))->result_array();
	}

	function getCityNamesFromCityIds($cityIds = array()){
		if(empty($cityIds)){
          return array();
        }

        $dbHandle = $this->getDbHandle('read');
        $sql = "SELECT city_name AS CityName, city_id AS CityId FROM countryCityTable WHERE city_id IN (".implode(',', $cityIds).")";
        return $dbHandle->query($sql)->result_array();
	}

	function getLocalitiesNamesFromLocalityIds($localityIds = array()){
        if(empty($localityIds)){
          return array();
        }
        $dbHandle = $this->getDbHandle('read');
        $sql= "SELECT localityName, localityId FROM localityCityMapping WHERE localityId IN (".implode(',', $localityIds).")";
        return $dbHandle->query($sql)->result_array();
    }

    function getExclusionFlag($userIds){
    	if(count($userIds) == 0){
    		return false;
    	}

    	$dbHandle = $this->getDbHandle('read');
        $sql= "SELECT userId, exclusionType FROM LDBExclusionList where status = 'live' and userId IN (?)";
    	$query = $dbHandle->query($sql,array($userIds));

    	$finalResult = array();
    	foreach ($query->result_array() as $row) {
            $finalResult[$row['userId']]['userId'] = $row['userId'];
            $finalResult[$row['userId']]['exclusionType'] = $row['exclusionType'];
        }

        return $finalResult;

    }

    public function getCreditToBeConsumed($streamId,$courseId,$mode){
    	if($streamId == '' || !isset($streamId)){
            return false;   
        }
        
        $dbHandle = $this->getDbHandle('read');

        $sql = "select ViewCredit,SmsCredit,EmailCredit,ViewCount,SMSCount,EmailCount from UserProfileCreditAndViewCount where StreamId =? ";

        if($courseId != '' && isset($courseId)){
           $sql .= 'and Course = '.$courseId;
        }

        if($mode != '' && isset($mode)){
           $sql .= ' and Mode = '.$mode;
        }

        $query = $dbHandle->query($sql,array($streamId))->result_array();

        return $query[0];

    }

    public function getContactCountForClient($clientId, $streamId,$subStreamId,$contactType, $flagType = ''){

    	if($contactType == '' || !isset($contactType)){
    		return false;
    	}

    	$dbHandle = $this->getDbHandle('read');
    	
    	$sql = "select count(distinct userId) as count from UserProfileMappingToClient where clientId =? and streamId =? and ContactType =?";

    	if(count($subStreamId>0) && is_array($subStreamId)){
    		$sql .= " and subStreamId IN (".implode(',',$subStreamId).")";
    	}

    	if(!empty($flagType)) {
    		$sql .= " and FlagType IN ('".implode("','", $flagType)."')";
    	}

    	$query = $dbHandle->query($sql,array($clientId,$streamId,$contactType))->row_array();

    	return $query['count'];
    }

    public function getUserContactCount($userId, $clientId, $contactType, $flagType = ''){

    	if($contactType == '' || !isset($contactType)){
    		return false;
    	}

    	$dbHandle = $this->getDbHandle('read');
    	
    	$sql = "select count(*) from UserProfileMappingToClient where userId =? and clientId = ? and ContactType = ?";

    	if(!empty($flagType)) {
    		$sql .= " and FlagType IN ('".implode("','", $flagType)."')";
    	}

    	$query = $dbHandle->query($sql,array($userId,$clientId,$contactType))->row_array();
    	
		return $query['count(*)'];
    }

    public function getContactedUsers($clientId, $streamId,$subStreamId,$contactType,$resultOffset,$numberOfResults, $flagType = ''){
    	if($contactType == '' || !isset($contactType)){
    		return false;
    	}

    	$dbHandle = $this->getDbHandle('read');
    	
    	$sql = "select distinct userId,FlagType from UserProfileMappingToClient where clientId =? and streamId =? and ContactType =?";

    	if(count($subStreamId>0) && is_array($subStreamId)){
    		$sql .= " and subStreamId IN (".implode(',',$subStreamId).")";
    	}

    	if(!empty($flagType)) {
    		$sql .= " and FlagType IN ('".implode("','", $flagType)."')";
    	}

    	$sql .= " order by submitTime desc, id desc ";

    	if($resultOffset >=0 && $numberOfResults>=0){
    		$sql .= ' limit '.$resultOffset.','.$numberOfResults;
    	}

    	$query = $dbHandle->query($sql,array($clientId,$streamId,$contactType))->result_array();

    	return $query;
    }

    public function getLeadAllocationData($userIds) {
    	if(empty($userIds)) {
    		return false;
    	}

    	$dbHandle = $this->getDbHandle('read');
    	
    	$sql = "select userid, matchedFor, responseTime from SALeadAllocation where userid IN (".implode(",", $userIds).")";
		$query = $dbHandle->query($sql);
		
		$finalResult = array();
		foreach ($query->result_array() as $row) {
            $finalResult[$row['userid']]['matchedFor'] = $row['matchedFor'];
            $finalResult[$row['userid']]['responseTime'] = $row['responseTime'];
        }

    	return $finalResult;
    }

    function getUserContactMap($clientUserId, $streamId,$subStreamId,$contactType,$userIdArray){
    	if($contactType == '' || !isset($contactType)){
    		return false;
    	}

    	$dbHandle = $this->getDbHandle('read');
    	
    	$sql = "select distinct userId,FlagType from UserProfileMappingToClient where clientId =? and streamId =? and ContactType =?";

    	if(count($subStreamId>0) && is_array($subStreamId)){
    		$sql .= " and subStreamId IN (".implode(',',$subStreamId).")";
    	}    	
  	
  		$sql .=" and userId IN (".implode(',',$userIdArray).")";

    	$query = $dbHandle->query($sql,array($clientUserId,$streamId,$contactType))->result_array();

    	return $query;
    }

    function dataInCacheForMigration(){
    	$dbHandle = $this->getDbHandle('read');
    	
    	$sql ="select StreamId,Course,Mode, ViewCredit,SmsCredit,EmailCredit,ViewCount,SMSCount,EmailCount from UserProfileCreditAndViewCount";
		
		$result = $dbHandle->query($sql,array($clientUserId,$streamId,$contactType))->result_array();

    	return $result;

    }
}
?>
