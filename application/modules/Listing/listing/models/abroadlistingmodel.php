<?php
class abroadlistingmodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;

		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }
	
	public function increaseContactNumberViewCount($params = array()) {
		$this->initiateModel('write');
		$return = false;
		if(!empty($params)){
			$queryCmd 			= $this->dbHandle->insert_string('abroad_contact_number_tracking', $params);
			$query 				= $this->dbHandle->query($queryCmd); 
			$return 			= $this->dbHandle->insert_id();
		}
		return $return;
	}
	
	public function getSubCategoryOfAbroadCourse($courseId)
	{
	    if(empty($courseId))
		return 0;

	    $this->initiateModel('read');
	    /*
	    $sql = "SELECT lcat.category_id as subcat
		    FROM 
		    course_details cd 
		    LEFT JOIN listing_category_table lcat 
		    ON (lcat.listing_type_id = cd.course_id AND lcat.listing_type = 'course' AND lcat.status = '".ENT_SA_PRE_LIVE_STATUS."') 
		    WHERE cd.status = '".ENT_SA_PRE_LIVE_STATUS."' 
		    AND cd.course_id = ".$courseId;
	    */
	     $sql = "SELECT listing_type_id as course_id,category_id as subcat FROM listing_category_table
		    WHERE listing_type = 'course' AND status = 'live'";

	    if(is_array($courseId)){
		$sql .= " AND listing_type_id IN (?)";
	    }else{
		$sql .= " AND listing_type_id = ?";
	    }

	    $result = $this->dbHandle->query($sql,array($courseId))->result_array();
	    if(empty($result))
		return 0;
	    elseif(is_array($courseId)){
		return $result;
	    }else{
		return $result[0]["subcat"];
	    }
	}
	
	public function getSimilarCoursesOfUniversity($universityId, $categoryId)
	{
	    $this->initiateModel('read');
	    if($universityId == "" ||  $categoryId == "") {
		return array();
	    }
		
	    
	     $sql = "SELECT cd.course_id
		    FROM course_details cd 
		    LEFT JOIN institute_university_mapping unimp 
		    ON (unimp.institute_id = cd.institute_id AND unimp.status = 'live') 
		    LEFT JOIN listing_category_table lcat 
		    ON (lcat.listing_type_id = cd.course_id AND lcat.listing_type = 'course' AND lcat.status = 'live'), 
		    categoryBoardTable cbt 
		    WHERE cbt.boardId = lcat.category_id AND cd.status = 'live' 
		    AND unimp.university_id = ?
		    AND cbt.parentId = ?";

	    $result = $this->dbHandle->query($sql,array($universityId,$categoryId))->result_array();

	    return $result;
	}

	 /**
	  * Purpose : Method to get total view count of last n days
	  * Params  :	Listing Type, Listing Type Id, No Of Days
	  * Author  : Vinay
	  */
	 
	 public function getLisitngViewCountForLastNnoOfDays($listingType,$listingIds = array(),$noOfDays = -1){
	 	$this->initiateModel('read');
        $dataArray = array('listingType' => $listingType);
	 	if(count($listingIds)>0){
	 		$whereClauseForListingId = " AND listingId IN (?) ";
            $dataArray['listingIds'] = $listingIds;
	 	}
	 	if($noOfDays==0 || $noOfDays > 0)
	 	{
	 		$whereClauseForViewDate = " AND viewDate between DATE_SUB( CURDATE( ) , INTERVAL ? DAY ) and CURDATE() ";
            $dataArray['noOfDays'] = $noOfDays;
	 	}	
	 	$sql = "SELECT listingId,sum(viewCount) as viewCount 
	 			FROM `abroadListingViewCountDetails`
	 			WHERE listingType=? ". 
	 			$whereClauseForListingId.
	 			$whereClauseForViewDate." 
	 			GROUP BY listingId";
	 	 
	 	$result = $this->dbHandle->query($sql,$dataArray)->result_array();
	 	 $viewcountResult;
	 	 foreach($result as $resultrow)
	 	 {
	 	 	$viewcountResult[$resultrow['listingId']] = $resultrow['viewCount'];
	 	 }
	 	 return $viewcountResult;
	 }
	 
	 /*
	  * Author : Abhinav
	  * Purpose : To get Guide Visa for Country
	  * Params : Country-ID
	  */
	 public function getVisaGuideDetail($countryId){
	    $this->initiateModel('read');
	    
	    $sql = 'select
				cnt.content_id,
				cnt.summary,
				cnt.content_url as contentURL,
				cnt.created_on as created
			from
				sa_content cnt
			inner join sa_content_attribute_mapping cntmp on
				(cnt.content_id = cntmp.content_id and cntmp.attribute_mapping="country"
				and cnt.status = cntmp.status)
			left join sa_content_course_mapping cncmp on
				(cnt.content_id = cncmp.content_id
				and cnt.status = cncmp.status)
			left join sa_content_attribute_mapping cnexm on
				(cnt.content_id = cnexm.content_id and cnexm.attribute_mapping="exam"
				and cnt.status = cnexm.status)
			left join sa_content_attribute_mapping cnldb on
				(cnt.content_id = cnldb.content_id and cnldb.attribute_mapping="ldbcourse"
				and cnt.status = cnldb.status)
			left join sa_content_tags_mapping cntag on
				(cnt.content_id = cntag.content_id
				and cnt.status = cntag.status)
			left join sa_content_attribute_mapping cnuni on
				(cnt.content_id = cnuni.content_id and cnuni.attribute_mapping="university"
				and cnt.status = cnuni.status)
			';
	    
	    //where clause for guide and country-Id Checks
	    $sql .= 'where
		cnt.status = "live"
		and cnt.type = "guide"
		and cntmp.attribute_id =? 
		and cncmp.id is null
		and cnexm.id is null
		and cnldb.id is null
		and cntag.id is null
		and cnuni.id is null
		order by
			cnt.created_on asc
		limit 1 ';
	    
	    $resultSet = $this->dbHandle->query($sql,array($countryId))->result_array();
        $resultSet[0]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$resultSet[0]['contentURL'];
	    return $resultSet[0];
	}
	
	/*
	 * query to get response from tempLmsTable for a particular course
	 * params: courseId, interval (optional), unit of intervals (optional [day/month])
	 * note : default intervals are taken 6 months as requirement for study abroad pages
	 */
	public function getLastXMonthsResponseCount($courseId, $interval = 6, $intervalUnit = "MONTH")
	{
		$this->initiateModel('write');
		$sql   = "select count(*) as response_count from tempLMSTable
			  where listing_type='course' and listing_type_id = ?
			  and 
			  date(submit_date) >= curdate() - INTERVAL ".mysql_escape_string($interval)." ".mysql_escape_string($intervalUnit).";";
		
		$results = $this->dbHandle->query($sql,array($courseId))->result_array();
		return $results[0]['response_count'];
	}
	/*
	 * to get last inserted id from tMailQueue for a given toEmail, used for email tracking in abroad download brochure
	 * param: $toEmail - email id to which mail was sent
	 * return val : id of last record inserted in queue
	 */
	public function getLastMailQueueId($toEmail = ""){
		$this->initiateModel('write');
	    if($toEmail == "")
	    {
		return -1; //for invalid email id
	    }
	    $sql = "select id as tMailQueueId from tMailQueue where toEmail = ? order by id desc limit 1";
	    $results = $this->dbHandle->query($sql,array($toEmail))->result_array();
	    return $results[0]['tMailQueueId'];
	}
	
	public function getViewCountDumpForListingType($listingType = 'course', $pastDays = 7, $listingTypeIds=array()) {
	    $this->initiateModel('read');
	    $listingIdsCheck = '';
        $inputData = array();
        $inputData['pastDays'] = $pastDays;
	    if(!empty($listingTypeIds)){
		$listingIdsCheck = "AND listingId IN (?)";
        $inputData['listingTypeIds'] = $listingTypeIds;
	    }
	    $sql =  "SELECT listingId, SUM( viewCount ) AS view_count ".
			    "FROM abroadListingViewCountDetails ".
			    "WHERE viewDate BETWEEN DATE_SUB( CURDATE( ) , INTERVAL ? DAY ) AND CURDATE( ) ".
			    $listingIdsCheck.
			    "AND listingType = ? ".
			    "GROUP BY listingId ".
			    "HAVING view_count >= 1 ORDER BY view_count DESC";
        $inputData['listingType'] = $listingType;
	    $query 	= $this->dbHandle->query($sql,$inputData);
	    $data 	= array();
	    foreach($query->result_array() as $row){
		    $data[$row['listingId']] = $row['view_count'];
	    }
	    return $data;
	}
	
	public function getDepartmentCountOfUniversity($university_id){
	    $this->initiateModel('read');
	    $sql = "select count(1) as dcount from institute_university_mapping where university_id = ? and status = 'live'";
	    $query = $this->dbHandle->query($sql,array());
	    $result = $query->result_array($university_id);
	    return $result[0]['dcount'];
	}
	
	/*
	 * Author : Abhinav
	 * Purpose : Add Snapshot Course Request
	 * Params : sesson-Id,user-Id,snapshot Course-ID
	*/
	public function addSnapshotCourseRequest($sessionId,$userId,$snapshotCourseId){
	    //Obtain write mode handle on DB
	    $this->initiateModel('write');
	    $sql = "INSERT INTO `snapshotRequest`(`sessionId`,`userId`,`snapshotCourseId`,`requestedAt`)VALUES(?,?,?,NOW())";
	    //error_log("insert query: ".$sql);
	    $this->dbHandle->query($sql,array($sessionId,$userId,$snapshotCourseId));
	    return '1';
	}
	
	/*
	  * Author : Rahul Bhatnagar
	  * Purpose : To get Category and Subcategory of any Content
	  * Params : Article ID
	  */
	public function getUniversityCategorySubcategoryOfContent($contentId){
	    $this->initiateModel('read');
	    $sql = "select
			parent_category_id as categoryId,
			subcategory_id as subcatId
		    from
			sa_content_course_mapping
		    where
			content_id = ? and status = ?";
	    $values = $this->dbHandle->query($sql,array($contentId,'live'))->result_array();
	    $values = reset($values);
	    
	    $sql = "select
			attribute_id as ldb_course_id
		    from
			sa_content_attribute_mapping
		    where
			content_id = ? and attribute_mapping='ldbcourse' and status = ?";
	    $ldb = $this->dbHandle->query($sql,array($contentId,'live'))->result_array();
	    $ldb = reset($ldb);
	    
	    $sql = "select
			t1.attribute_id as universityId,
			t2.city_id,
			t2.country_id
		    from
			sa_content_attribute_mapping t1,
			university_location_table t2
		    where
			t1.content_id = ? AND t1.attribute_mapping='university'
			AND t2.university_id = t1.attribute_id
			AND t1.status = 'live'
			AND t2.status = 'live'";
	    $res = $this->dbHandle->query($sql,array($contentId))->result_array();
	    $res = reset($res);
	    $values['universityId'] = $res['universityId'] ;
	    $values['city_id'] = $res['city_id'] ;
	    $values['country_id'] = $res['country_id'] ; 
	    $values['ldb_course_id'] = $ldb['ldb_course_id'];   
	    return $values;
	}



	public function getLastTempLMSRequestIDForUser($userid, $listingType, $listingId)
	{
	    $this->initiateModel('write');
	    $yesterdayDate = date("Y-m-d H:i:s", strtotime("-1 days"));
	    $sql  = "SELECT id FROM tempLMSTable WHERE 1
		      and listing_type = ?
		      and listing_type_id = ?
		      and userId = ?
		      and  submit_date > ?
		      order by id desc limit 1";
	    $query = $this->dbHandle->query($sql,array($listingType,$listingId,$userid,$yesterdayDate));
	    $result = $query->row_array();
	    return $result;
	}
	
	public function getUniversitiesOfOwner($owner_string = ''){
	    if(empty($owner_string)) return array();
	    $this->initiateModel('read');
	    $sql = "SELECT a.listing_type_id, a.listing_title FROM listings_main a WHERE a.status='live' ".
			    "AND a.username = ? AND a.listing_type = 'university' ";
	    $result = $this->dbHandle->query($sql,array($owner_string))->result_array();
	    return $result;
        }
	
	public function getContactCountForAbroadListing($listing_type_id,$listing_type){
	    $this->initiateModel('read');
	    $sql = "select count(1) as vals , listing_type, listing_id, contact_number from  abroad_contact_number_tracking
		    where listing_id = ? and listing_type = ?";
	    return $this->dbHandle->query($sql,array($listing_type_id,$listing_type))->result_array();
	}
	
	public function getViewCountForAbroadListing($listingTypeId,$listing_type){
	    $this->initiateModel('read');
	    $sql = "select viewCount, listing_type, listing_type_id from  listings_main
		    where listing_type_id = ? and listing_type = ?
		    and status = 'live'";
	    return reset(reset($this->dbHandle->query($sql,array($listingTypeId,$listing_type))->result_array()));
	}
	
	//Author : Rahul Bhatnagar
	public function getViewCountForAggregatedAbroadListings($listingTypeIdArray){
	    if(count($listingTypeIdArray) == 0) return array();
	    $this->initiateModel('read');
	    $sql = "select viewCount, listing_type, listing_type_id from listings_main
		    where listing_type_id in (?) and status = 'live'";
        $this->dbHandle->query($sql,array($listingTypeIdArray))->result_array();
	    return $this->dbHandle->query($sql,array($listingTypeIdArray))->result_array();
	    
	}
	/*
	 * to save userfeedback
	 */
	public function saveFeedbackData($dataArr)
	{
	    $this->initiateModel('write');
	    $this->dbHandle->insert('userFeedback', $dataArr);
	    return $this->dbHandle->insert_id();
	}
	public function getOtherDepartments($deptObj){
		$deptId = $deptObj->getId();
		if(empty($deptId)){
			return array();
		}
		$universityId = $deptObj->getUniversityId();
		$this->initiateModel('read');
		
		$sql = "select ium.institute_id, count(cd.course_id) as courseCount from institute_university_mapping ium, course_details cd where ium.status='live' and ium.university_id=? and ium.institute_id!=? and ium.institute_id = cd.institute_id and cd.status='live' group by cd.institute_id having courseCount>0 order by courseCount desc limit 8;";
		$result = $this->dbHandle->query($sql,array($universityId,$deptId))->result_array();
		$otherDepts = array();
		$otherDeptIds=array();
		foreach($result as $row){
			$otherDepts[$row['institute_id']] = array('id' => $row['institute_id'],'courseCount'=>$row['courseCount']);
			$otherDeptIds[] = $row['institute_id'];
		}
		$deptIdString = mysql_escape_string(implode(",",$otherDeptIds));
		if(empty($deptIdString)){
			return array();
		}
		$sql = "select listing_title as name, listing_type_id as institute_id, listing_seo_url as url
				from listings_main where listing_type_id in (?) and listing_type='institute' and status='live'";
		$result = $this->dbHandle->query($sql,array($otherDeptIds))->result_array();
		foreach($result as $row){
			$otherDepts[$row['institute_id']]['name'] = $row['name'];
			$otherDepts[$row['institute_id']]['url'] = SHIKSHA_STUDYABROAD_HOME.$row['url'];
		}
	    return $otherDepts;
	}
	
	public function getSnapshotRequestFlag($userId,$snapshotCourseId){
	    $this->initiateModel('read');
	    
	    $sql = "select count(1) totalCount from snapshotRequest where userId=? and snapshotCourseId=? ";
	    $resultSet = $this->dbHandle->query($sql,array($userId,$snapshotCourseId))->result_array();
	    return $resultSet[0]['totalCount'];
	}
	
	public function getEligibleExamWithGuide($examIds = array())
	{
		if(count($examIds)==0){
			return array();
		}
		$this->initiateModel('read');
	    $this->dbHandle->select('sac.content_id,sac.exam_id as examId, sac.content_url as contentURL',false);
	    $this->dbHandle->from('sa_content sac');
		$this->dbHandle->where("sac.status","live");
		$this->dbHandle->where_in("sac.exam_id",$examIds);
		$this->dbHandle->where("sac.type","examContent");
		$this->dbHandle->where("sac.is_homepage",1);
	    $query = $this->dbHandle->get()->result_array();
	    $result =array();
	    foreach($query as $tuple){
			if($tuple['contentURL'] !='')
			{
				$tuple['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$tuple['contentURL'];
				$result[$tuple['examId']] =  $tuple;
			}
	    }
	    return $result; 
	}
	/*
	 * this function gets the counsellor id for given university id 
	 */
	public function checkIfUniversityHasCounsellor($universityIds = array())
	{
	    if(empty($universityIds)){
		return array();
	    }
	    $this->initiateModel('read');
	    $this->dbHandle->select('university_id,counsellor_id');
	    $this->dbHandle->from('RMS_counsellor_university_mapping');
	    $this->dbHandle->where('status','live');
	    //$this->dbHandle->where('date(end_date) >=','curdate()',FALSE);
	    //$this->dbHandle->where('date(start_date) <=','curdate()',FALSE);
	    $this->dbHandle->where_in('university_id',$universityIds,FALSE);
	    $query_res = $this->dbHandle->get()->result_array();
	    if(count($query_res) >= 1)
	    {
		return $query_res;
	    }
	    else{
		return false;
	    }
	}
	/* 
	 * this function gets the counsellor details for given university ids
	 * (accepts single numeric value as well as array of numeric ids)
	 */
	public function getCounsellorsForUniversities($universityIds = array(),$forRMSType = '')
	{
	    if(!is_numeric($universityIds) && count($universityIds)==0)
	    { 	// if not a number & array is empty
		return false;
	    }
	    else if(is_numeric($universityIds))
	    {	// single university id
		$universityIds = array($universityIds);
	    }
	    $this->initiateModel('read');
	    $this->dbHandle->select('rcum.university_id,rcum.RMSType,rc.counsellor_id,rc.counsellor_name,rc.counsellor_email,rc.counsellor_mobile,rc.counsellor_manager_id');
	    $this->dbHandle->from('RMS_counsellor_university_mapping rcum');
	    $this->dbHandle->join('RMS_counsellor rc', 'rcum.counsellor_id=rc.counsellor_id and rcum.status=rc.status','inner');
	    $this->dbHandle->where('rcum.status','live');
	    //$this->dbHandle->where('date(rcum.end_date) >=','curdate()',FALSE);
	    //$this->dbHandle->where('date(rcum.start_date) <=','curdate()',FALSE);
	    if($forRMSType !=""){
		//$this->dbHandle->where('rcum.RMSType',$forRMSType);
	    }
	    $this->dbHandle->where_in('rcum.university_id',array_map('intval', $universityIds),FALSE);
	    $query_res = $this->dbHandle->get()->result_array();
	    if(count($query_res) >= 1)
	    {
		return $query_res;
	    }
	    else{
		return false;
	    }
	}
	/* 
	 * this function gets the number of unique callback responses created today by given user
	 */
	public function getUserCallbackResponseCountForToday($userId = 0)
	{
	    if(!is_numeric($userId))
	    { 	
		return false;
	    }
	    
	    $this->initiateModel('read');
	    $this->dbHandle->select('userid, count(*) as count');
	    $this->dbHandle->from('tempLMSTable');
	    $this->dbHandle->where('action','Request_Callback');
	    $this->dbHandle->where('submit_date >=','curdate()',FALSE);
	    $this->dbHandle->where('userid',$userId, FALSE);
	    $query_res = $this->dbHandle->get()->result_array();
	    $result = array();
	    foreach($query_res as $row)
	    {
		$result[$row['userid']] = $row;
	    }
	    //echo "SQL".$this->dbHandle->last_query();
	    if(count($result) >= 1)
	    {
		return $result;
	    }
	    else{
		return false;
	    }
	}
	
	public function getUniversityNameOfDepartment($deptId){
		$this->initiateModel('read');
		$this->dbHandle->select('lm.listing_title');
		$this->dbHandle->from('listings_main lm');
		$this->dbHandle->join('institute_university_mapping ium','ium.university_id = lm.listing_type_id');
		$this->dbHandle->where('ium.institute_id',$deptId);
		$this->dbHandle->where('ium.status','live');
		$this->dbHandle->where('lm.status','live');
		$this->dbHandle->where('lm.listing_type','university');
		$result = $this->dbHandle->get()->result_array();
		return reset(reset($result));
	}
	/*
	 * function to track brochure download for abroad course/university (paid & free both)
	 */
	public function trackAbroadListingsBrochureDownload($trackingInfo)
	{		//echo "OO";_p($trackingInfo);die;
		$this->db = $this->getWriteHandle();
		$userInfo['session_id'] = isset($userInfo['session_id'])?$userInfo['session_id']:""; 
		$tableData = array(
				   'listingType'	=>$trackingInfo['listing_type'],
				   'listingTypeId'	=>$trackingInfo['listing_type_id'],
				   'userId'		=>$trackingInfo['user_id'],
				   //'downloadedAt'	=>'now()',
				   'downloadedFrom'	=>$trackingInfo['downloadedFrom'],
				   'brochureUrl'	=>$trackingInfo['brochureUrl'],
				   'sessionId'		=>$trackingInfo['session_id']
				   );
		$this->db->set('downloadedAt', 'NOW()', FALSE);
		$this->db->insert('listingsBrochureDownloadTracking ', $tableData);
		$insert_id = $this->db->insert_id();
		return $insert_id ;
	}
	/*
	 * function to update download tracking id columns in listingsbrochureemailtracking table
	 */
    	public function updateAbroadBrochureDownloadInEmailTracking($trackInfo)
	{
            $this->db = $this->getWriteHandle();
            if($trackInfo['tempLmsTableId'] == 0  || $trackInfo['tempLmsTableId'] == 1 && empty($trackInfo['brochureEmailInsertId']) || (int)$trackInfo['brochureEmailInsertId']==0)
            { // when the layer is closed, the last inserted tempLMS id gets lost hence need to find that...
                    $sql = "select id  from tempLMSTable where userid = ? and listing_type = ? and listing_type_id = ?
                            and date(submit_date) >= curdate() - INTERVAL 1 DAY ";

                    $results = $this->db->query($sql,array($trackInfo['user_id'],$trackInfo['listing_type'],$trackInfo['listing_type_id']))->result_array();
                    $trackInfo['tempLmsTableId'] = $results[0]['id'];
            }
            $sql = "update listingsBrochureEmailTracking ";
            if($trackInfo['listing_type'] == 'course' || $trackInfo['listing_type'] == 'scholarship')
            {
                $sql .=	" set downloadTrackingId = ?";
            }
            else{ // university
                $sql .=	" set universityBrochureDownloadTracking = ?";
            }
            if(!empty($trackInfo['brochureEmailInsertId']) && (int)$trackInfo['brochureEmailInsertId']>0){
                $sql= $sql." where id=?";
                $this->db->query($sql,array($trackInfo['downloadTrackingId'],$trackInfo['brochureEmailInsertId']));
            }
            else{
                $sql .=	" where listingTypeId = ?
                    and userId = ? 
                    and tempLmsId = ?";
                $this->db->query($sql,array($trackInfo['downloadTrackingId'],$trackInfo['listing_type_id'],$trackInfo['user_id'],$trackInfo['tempLmsTableId']));
            }
            
	}
	/*
	 * function to find download count for brochure of course or university within same session
	 */
	public function getBrochureDownloadCountForSession($userInfo)
	{
		$this->initiateModel('read');
		$sql = "select count(*) as result_count from listingsBrochureDownloadTracking where listingType = ? and listingTypeId = ? and userId = ? and sessionId = ?";
		$data = $this->dbHandle->query($sql,array($userInfo['listing_type'],$userInfo['listing_type_id'],$userInfo['user_id'],$userInfo['session_id']))->result_array();
		return $data[0]['result_count'];
	}
	
	
	/*
	 * Author	: Abhinav
	 * Purpose	: Get Courses for University along with Category,Sub-category and LDB-Course-IDS
	 * Params	: Array(univerityId,course_level,ldbCourseId,categoryId,subCategoryId)
	 */
	public function getCoursesWithCategories($checkForCourses){
	    if(!($checkForCourses['univerityId'] > 0)){
		return array();
	    }
	    $whereCheckArray = array("university_id='".mysql_escape_string($checkForCourses['univerityId'])."'",
				     "status='live'"
				     );
	    if($checkForCourses['courseLevel'] != ''){
		$whereCheckArray[] = "course_level='".mysql_escape_string($checkForCourses['courseLevel'])."'";
	    }
	    
	    if($checkForCourses['categoryId'] != ''){
		$whereCheckOptionalArray[] = "category_id='".mysql_escape_string($checkForCourses['categoryId'])."' ";
	    }
	    if($checkForCourses['subCategoryId'] != ''){
		$whereCheckOptionalArray[] = "sub_category_id='".mysql_escape_string($checkForCourses['subCategoryId'])."' ";
	    }
	    if($checkForCourses['ldbCourseId'] != ''){
		$whereCheckOptionalArray[] = "ldb_course_id='".mysql_escape_string($checkForCourses['ldbCourseId'])."' ";
	    }
	    $whereCheckOptionalArray = implode(' OR ',$whereCheckOptionalArray);
	    $whereCheckArray = implode(' AND ',$whereCheckArray);
	    $sql = 'select course_id,category_id,sub_category_id,ldb_course_id';
	    $sql .= ' from abroadCategoryPageData';
	    $sql .= ' where '.$whereCheckArray;
	    $sql .= ($whereCheckOptionalArray != '')?' AND ('.$whereCheckOptionalArray.')':'';
	    //echo "<br/>sql: ".$sql;
	    $this->initiateModel('read');
	    $resultSet = $this->dbHandle->query($sql)->result_array();
	    return $resultSet;
	}
	
	public function getPopularCourseVisibilityForCountryPage($countryId){
		$this->initiateModel('read');
		$this->dbHandle->select("distinct(ldb_course_id)");
		if($countryId!=1){
			$this->dbHandle->where("country_id",$countryId);
		}
		$this->dbHandle->where("status","live");

		global $studyAbroadPopularCourseToCategoryMapping;
        $desiredCourseArray = array_keys($studyAbroadPopularCourseToCategoryMapping); 

		$this->dbHandle->where_in("ldb_course_id",$desiredCourseArray);
		$result = $this->dbHandle->get("abroadCategoryPageData")->result_array();
		$data = array_map(function($a){
		    return $a['ldb_course_id'];
		    },$result);
		return $data;
	}
	
	public function getExcludedCoursesForUniversity($univId){
		$this->initiateModel('read');
	    $this->dbHandle->select("courseId, consultantId");
	    $this->dbHandle->from("consultantUniversityExcludedCourseMapping");
	    $this->dbHandle->where("universityId",$univId);
	    $this->dbHandle->where("status","live");
	    $data = $this->dbHandle->get()->result_array();
	    $result = array();
	    foreach($data as $row){
		$result[$row['consultantId']][] = $row['courseId'];
	    }
	    return $result;
	}
	
	//This function gets the data needed for making the consultant tab/widget on listing pages.
	// The data is fetched at the university level since all mappings are done at the university level.
	// Input : University Id
	// Output: array(consultantId => consultantData)
	// Author: Rahul Bhatnagar
	public function getActiveConsultantsForUniversity($univId){
		$this->initiateModel('read');
	    $sql = 
		"select 
		    cum.consultantId, 
		    cum.universityId,
            cum.isOfficialRepresentative,
		    crs.regionId as regionId,
		    cr.name as regionName,
		    c.name as consultantName,
		    c.logo as consultantLogo,
		    ccs.subscriptionId as subId,
		    ccs.costPerResponse
		from consultantUniversityMapping cum
		inner join consultant c on c.consultantId = cum.consultantId and c.status = 'live'
		inner join consultantRegionSubscription crs
		    on crs.consultantId = cum.consultantId
		    and crs.universityId = cum.universityId
		    and crs.status='live'
		    and crs.startDate < NOW() and crs.endDate > NOW()
		inner join consultantRegions cr on crs.regionId = cr.id and cr.status='live'
		inner join consultantClientSubscriptionDetail ccs on ccs.consultantId = cum.consultantId and ccs.status='live'
		where
		    cum.universityId = ?
		    and cum.status = 'live'";
	    $data = $this->dbHandle->query($sql,array($univId))->result_array();
	    $subscriptionIds = array_map(function($ele){return $ele['subId'];},$data);
	    $subscriptionIds = array_unique($subscriptionIds);
	    if(empty($subscriptionIds)){
		return array();
	    }
	    $CI = &get_instance();
	    $CI->load->library('sums/Subscription_client');
	    $subClient = new Subscription_client;
	    $subData = $subClient->getMultipleSubscriptionDetails(1,$subscriptionIds);
	    $fSubData = array();
	    foreach($subData as $rec){
		$fSubData[intval($rec['SubscriptionId'])] = $rec;
	    }
	    foreach($data as $key=>$row){
		if(!in_array($row['subId'],array_keys($fSubData))){
		    unset($data[$key]);
		    continue;
		}
		if($row['costPerResponse'] > $fSubData[$row['subId']]['BaseProdRemainingQuantity']){
		    unset($data[$key]);
		}
		
	    }
	    return $data;
	}
	
	public function getCourseIdsForDepartment($departmentId){
		$this->initiateModel('read');
	    $this->dbHandle->select("course_id");
	    $this->dbHandle->from("course_details");
	    $this->dbHandle->where("institute_id",$departmentId);
	    $this->dbHandle->where("status","live");
	    $res = $this->dbHandle->get()->result_array();
	    $res = array_map(function($ele){return reset($ele);},$res);
	    return $res;
	}
	public function getCourseIdsForUniversity($universityId){
		$this->initiateModel('read');
	    $sql = "select distinct course_id from abroadCategoryPageData where university_id = ? and status = 'live'";
	    $res = $this->dbHandle->query($sql,array($universityId))->result_array();
	    $res = array_map(function($ele){return reset($ele);},$res);
	    return $res;
	}
	
	/*
	 * this function gets the consultantData for given university ids array
	 */
	
	
	public function checkIfUniversityHasConsultants($universityIds = array(),$sumsProductObj)
	{
		$this->initiateModel('read');
	    $universityIds = array_filter($universityIds);
	    if(empty($universityIds))
	    {
		return false;
	    }
		
	     $sql = 
		"select 
		    cum.consultantId, 
		    cum.universityId,
		    ccs.subscriptionId,
		    ccs.costPerResponse
		from consultantUniversityMapping cum
		inner join consultant c on c.consultantId = cum.consultantId and c.status = 'live'
		inner join consultantRegionSubscription crs
		    on crs.consultantId = cum.consultantId
		    and crs.universityId = cum.universityId
		    and crs.status='live'
		    and crs.startDate < NOW() and crs.endDate > NOW()
		inner join consultantRegions cr on crs.regionId = cr.id and cr.status='live'
		inner join consultantClientSubscriptionDetail ccs on ccs.consultantId = cum.consultantId and ccs.status='live'
		where
		    cum.status = 'live'
		    and cum.universityId in (?) ";
	    
	    $consdata = $this->dbHandle->query($sql,array($universityIds))->result_array();
	    $excludedCourses = $this->checkForUnivExcludedCourses($universityIds);

	    $consIds = array();
	    $i=0;
	    if(!empty($consdata))
		{
			
		    //check if consultant has active subscriptions and remaining Credit
		    $consAux;
		    foreach($consdata as $value)
		    {
		      $subsIds[$i] = $value['subscriptionId'];
		      $consAux[$value['subscriptionId']]['subscriptionId']		= $value['subscriptionId'];
		      $consAux[$value['subscriptionId']]['universityId']		= $value['universityId'];
		      $consAux[$value['subscriptionId']]['costPerResponse']     = $value['costPerResponse'];
		      $consAux[$value['subscriptionId']]['consultantId']       	= $value['consultantId'];
		      $i++;
		    }
		    
		    $subscriptionData   = $sumsProductObj->getMultipleSubscriptionDetails(CONSULTANT_CLIENT_APP_ID,$subsIds);
		    if(empty($subscriptionData))
		    {
			unset($consData);
			return false;
		    }
		    else
		    {
		    $subsAux=array();
		    //preparing final result array for all those who have active subscriptions
		    foreach($subscriptionData as $value)
		    {
			    $subsAux[(int)$value['SubscriptionId']]['subscriptionId']		= (int)$value['SubscriptionId'];
			    $subsAux[(int)$value['SubscriptionId']]['creditLeft']       	= $value['BaseProdRemainingQuantity'];
		    }
		    foreach($subsAux as $value)
		    {

			if( $value['creditLeft']> $consAux[$value['subscriptionId']]['costPerResponse'])
			{
			    $result[$consAux[$value['subscriptionId']]['universityId']][$consAux[$value['subscriptionId']]['consultantId']]['subscriptionId']		= $value['subscriptionId'];
			    //$result[$consAux[$value['subscriptionId']]['universityId']][$consAux[$value['subscriptionId']]['consultantId']]['universityId']		= $consAux[$value['subscriptionId']]['universityId'];
			    //$result[$consAux[$value['subscriptionId']]['universityId']][$consAux[$value['subscriptionId']]['consultantId']]['consultantId']		= $consAux[$value['subscriptionId']]['consultantId'];
			    $result[$consAux[$value['subscriptionId']]['universityId']][$consAux[$value['subscriptionId']]['consultantId']]['excludedCourses']      	= $excludedCourses[$consAux[$value['subscriptionId']]['consultantId']][$consAux[$value['subscriptionId']]['universityId']];
			}    
		    }
		    
			unset($consData);
			unset($subsAux);
			unset($subscriptionData);
			return $result;
		    }
	    
		}
	    else
	    {
	    	
		return false;
		
	    }
	}
	
	/*
	 *this function checks for the excluded courses for the universities array
	*/
	public function checkForUnivExcludedCourses($universityIds = array())
	{
	    $this->initiateModel('read');
	    $this->dbHandle->select("courseId, consultantId, universityId");
	    $this->dbHandle->from("consultantUniversityExcludedCourseMapping");
	    $this->dbHandle->where("status","live");
	    $this->dbHandle->where_in('universityId',$universityIds,FALSE);
	    $data = $this->dbHandle->get()->result_array();
	    //echo $this->db->last_query();
	    //_p($data);
	    //die;
	    $result = array();
	    foreach($data as $value)
	    {
		$result[$value['consultantId']][$value['universityId']][] = $value['courseId'];
	    }
	    return $result;
	}
	
	public function checkForConsExcludedCourses($consultantIds = array(),$universityId = FALSE)
	{
	    $this->initiateModel('read');
	    if($universityId!=FALSE)
	    {
		$this->dbHandle->where("universityId",$universityId);
	    }
	    
	    $this->dbHandle->select("courseId, consultantId, universityId");
	    $this->dbHandle->from("consultantUniversityExcludedCourseMapping");
	    $this->dbHandle->where("status","live");
	    $this->dbHandle->where_in('consultantId',$consultantIds,FALSE);
	    $data = $this->dbHandle->get()->result_array();
	    $result = array();
	    foreach($data as $value)
	    {
		$result[$value['consultantId']][$value['universityId']][] = $value['courseId'];
	    }
	    return $result;
	}
    
    public function getUniversitiesOfCountry($countryId) {
        if(!($countryId)){
            return array();
        }
        
        $this->initiateModel('read');
        $this->dbHandle->select('distinct(university_id) as university_id');
        $this->dbHandle->from('university_location_table');
        $this->dbHandle->where( array(  'country_id'=> $countryId,
                                        'status'    => 'live'
                                    )
                                );
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }
    
    public function getResponseCourseIdForCurrentDate($userId){
        if($userId=='' || is_array($userId)){
            return array();
        }
        $this->initiateModel('write');
        $this->dbHandle->select('listing_type_id');
        $this->dbHandle->from('tempLMSTable');
        $this->dbHandle->where( array(  'userId' => $userId));
        $this->dbHandle->where('submit_date >= NOW() - INTERVAL 1 day');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }
    
    public function getSpecializationId($categoryId,$courseLevel){
		if(is_string($courseLevel) && strtolower($courseLevel) == "certificate - diploma"){
			global $certificateDiplomaLevels;
			$courseLevel = $certificateDiplomaLevels;
		}
		$this->initiateModel('read');
		$this->dbHandle->select("SpecializationId");
		if(is_array($courseLevel)){
			$this->dbHandle->where_in("CourseName",$courseLevel);
		}else{
			$this->dbHandle->where("CourseName",$courseLevel);
		}
		$this->dbHandle->where("CategoryId",$categoryId);
		$this->dbHandle->where("status","live");
		$res = $this->dbHandle->get("tCourseSpecializationMapping")->result_array();
		$result = array_map(function($a){return $a['SpecializationId'];},$res);
		return $result;
    }
    
    public function getRecommendedCountryData($specializationId,$countryId,$limit=false){
		if(is_null($specializationId))
		{
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select("relatedCountry");
		if(!in_array(1,$countryId)){
			$this->dbHandle->select("sum(count) as c");
			$this->dbHandle->where_in("parentCountry",$countryId);
			$this->dbHandle->where_not_in("relatedCountry",$countryId);
			$this->dbHandle->group_by("relatedCountry");
		}else{
			$this->dbHandle->select("sum(count) as c");
			$this->dbHandle->group_by("relatedCountry");
		}
		$this->dbHandle->having("c >",0);
		$this->dbHandle->order_by("c desc");
		$this->dbHandle->where_in("specializationId",$specializationId);
		if($limit	)
		{
			$this->dbHandle->limit($limit);
		}
		$res = $this->dbHandle->get("countriesCoregistrationData")->result_array();
		return $res;
    }
	/*
	 *Note:  a join is made with listings_main so we that we pick only live entities
	 */
	public function getAbroadLogLikelihoodData($entityIds,$entityType='university',$limit=10){
		$this->initiateModel('read');
		if(count($entityIds)>0){
			$this->dbHandle->select("ll.secondayEntityId, ll.score");
			$this->dbHandle->from("studyAbroadLogLikelihoodAnalysis ll");
			$this->dbHandle->join("listings_main lm","ll.entityType = lm.listing_type and ll.status = lm.status and ll.secondayEntityId = lm.listing_type_id",'inner');
			$this->dbHandle->where_in("ll.primaryEntityId",$entityIds);
			$this->dbHandle->where("ll.entityType",$entityType);
			$this->dbHandle->where("ll.status","live");
			$this->dbHandle->order_by("ll.score desc");
			$this->dbHandle->limit($limit);
			return $this->dbHandle->get()->result_array();
		}else{
			return array();
		}
	}

    public function getCollegeCountsByCountry($countryIds,$request){
		$this->initiateModel('read');
		$this->dbHandle->select('count(distinct(university_id)) as c, country_id');
		$this->dbHandle->from("abroadCategoryPageData");
		if($request->isLDBCoursePage()){
			$this->dbHandle->where("ldb_course_id",$request->getLDBCourseId());
		}elseif( $request->isLDBCourseSubCategoryPage()){
			$this->dbHandle->where("ldb_course_id",$request->getLDBCourseId());
			$this->dbHandle->where("sub_category_id",$request->getSubCategoryId());
		}elseif($request->isCategorySubCategoryCourseLevelPage()){
			$courseLevelStr = strtolower($request->getCourseLevel());
			if($courseLevelStr == "certificate - diploma"){
				global $certificateDiplomaLevels;
				$this->dbHandle->where_in("course_level",$certificateDiplomaLevels);
			}else{
				$this->dbHandle->where("course_level",$courseLevelStr);
			}
			$this->dbHandle->where("category_id",$request->getCategoryId());
		}elseif($request->isCategoryCourseLevelPage()){
			$courseLevelStr = strtolower($request->getCourseLevel());
			if($courseLevelStr == "certificate - diploma"){
				global $certificateDiplomaLevels;
				$this->dbHandle->where_in("course_level",$certificateDiplomaLevels);
			}else{
				$this->dbHandle->where("course_level",$courseLevelStr);
			}
			$this->dbHandle->where("category_id",$request->getCategoryId());
		}else{
			return array();
		}
		if(count($countryIds)>0)
		{	
			$this->dbHandle->where_in("country_id",$countryIds);
		}
		$this->dbHandle->where("status","live");
		$this->dbHandle->group_by("country_id");
		$res = $this->dbHandle->get()->result_array();
		$result = array();
		foreach($res as $row){
			$result[$row['country_id']] = $row['c'];
		}
		return $result;
    }
	/*
	* This function prepares application process data for the course which is shiksha enabled 
	* and is  mapped to a shiksha apply enabled university
	*/
	public function getApplicationProcessData($courseIds) {

        $this->initiateModel('read');
        $this->dbHandle->select('cad.courseId,uap.sopRequired,uap.sopComments,uap.lorRequired,uap.lorComments,uap.essayRequired,uap.essayComments,uap.cvComments,uap.cvRequired,uap.allDocuments,uap.applyNowLink,uap.admissionType,cad.courseId');
        $this->dbHandle->select('cad.universityCourseProfileId,cad.applicationFeeDetail,cad.feeAmount,cad.currencyId,cad.isCreditCardAccepted,cad.isDebitCardAccepted,cad.iswiredMoneyTransferAccepted,cad.isPaypalAccepted,cad.feeDetails,cad.isInterviewRequired,cad.additionalRequirement');
        $this->dbHandle->from("courseApplicationDetails cad");
        $this->dbHandle->join('universityApplicationProfiles uap', 'uap.applicationProfileId = cad.universityCourseProfileId', 'inner');
        if (is_array($courseIds)) {
            $this->dbHandle->where_in('cad.courseId', $courseIds);
        } else {
            $this->dbHandle->where('cad.courseId', $courseIds);
        }
        $this->dbHandle->where('cad.status', 'live');
        $this->dbHandle->where('uap.status', 'live');
        $applicationProcessData = $this->dbHandle->get()->result_array();
        return $applicationProcessData;
    }

    public function getCourseApplicationEligibilityDetails($courseIds){
            $this->initiateModel('read');
            $this->dbHandle->select('cad.courseId,cad.12thCutoff,cad.12thcomments,cad.bachelorScoreUnit,cad.bachelorCutoff,cad.bachelorScoreUnit,cad.bachelorComments,cad.pgCutoff,cad.pgComments,cad.workExperniceValue,cad.workExperinceDescription,cad.isWorkExperinceRequired');
            $this->dbHandle->from("abroadCourseApplicationEligibiltyDetails cad");
            if(is_array($courseIds))
	    {
	    	$this->dbHandle->where_in('cad.courseId',$courseIds);
	    }
	    else
	    {
	    	$this->dbHandle->where('cad.courseId',$courseIds);
	    }
            $this->dbHandle->where('cad.status','live');
            $result = $this->dbHandle->get()->result_array();
            return $result;
        }

    public function getApplicationSubmissionDatesbyProfileId($profileIds){
		
		$this->initiateModel('read');
		$this->dbHandle->select('applicationSubmissionName,applicationSubmissionLastDate,applicationProfileId');
		$this->dbHandle->from("applicationSubmissionDates asd");
		if(is_array($profileIds))
	    {
	    	$this->dbHandle->where_in('asd.applicationProfileId',$profileIds);
	    }
	    else
	    {
	    	$this->dbHandle->where('asd.applicationProfileId',$profileIds);
	    }
	    
	    $this->dbHandle->where('asd.status','live');
	    $result = $this->dbHandle->get()->result_array();
	    //echo $this->dbHandle->last_query();
		return $result;
		
	}
	
	public function getCollegeCountsByLDBCourseAndExam($ldbCourseIds,$examId){
		
		if(count($ldbCourseIds)>0 && (int)$examId !='')
		{
			$this->initiateModel('read');
			$this->dbHandle->select('count(distinct(acpd.university_id)) as c, acpd.ldb_course_id');
			$this->dbHandle->from("abroadCategoryPageData acpd");
			$this->dbHandle->join("listingExamAbroad lea","acpd.course_id=lea.listing_type_id");
			$this->dbHandle->where_in("ldb_course_id",$ldbCourseIds);
			$this->dbHandle->where("acpd.status","live");
			$this->dbHandle->where("lea.listing_type","course");
			$this->dbHandle->where("lea.examId",$examId);
			$this->dbHandle->where("lea.status","live");
			$this->dbHandle->group_by("ldb_course_id");
			$res = $this->dbHandle->get()->result_array();
			//echo $this->dbHandle->last_query();
			$result = array();
			foreach($res as $row){
				$result[$row['ldb_course_id']] = $row['c'];
			}
		}
		else{
			$result = array();
		}
		return $result;
    }

    public function getCurrencyCodeById($id){
    	if(empty($id) || (integer)$id <=0){
    		return 'INR';
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select("*");
    	$this->dbHandle->from("currency");
    	$this->dbHandle->where("id",$id);
    	$res = $this->dbHandle->get()->result_array();
    	return reset($res);
    }
	
	public function getListingIdByUrl($listingUrl)
	{
		$this->initiateModel('read');
		$this->dbHandle->select('listing_type_id,listing_type,listing_id');
		$this->dbHandle->from('listings_main ls');
		$this->dbHandle->where('listing_seo_url',$listingUrl);
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		$result = reset($result);
		return $result;			
	}
	/*
	 *  function to index abroad listings: course ,dept, university, that have been modified in past 24 hours
	 */
	public function getAbroadListingsModifiedInPast24Hours($date = "")
	{
		$this->initiateModel('read');
		if($date == "")
		{
			// 1day ago by default
			$date = '"'.date('Y-m-d', strtotime('-2 days', strtotime(date('Y-m-d H:i:s')))).'"';
		}
		// first get departments and universities that were modified
		$this->dbHandle->select('listing_type, listing_type_id, status');
		$this->dbHandle->from('listings_main');
		$this->dbHandle->where('listing_type','university');
		$this->dbHandle->where_in('status',array('live','deleted'));
		$this->dbHandle->where('last_modify_date>'.$date,'',false);
		$univs = $this->dbHandle->get()->result_array();
		$univIds = array_map(function($a){return $a['listing_type_id'];}, $univs);
		echo "<br>".$this->dbHandle->last_query();
		// now departments
		$this->dbHandle->select('distinct cd.course_id, lm.listing_type_id, lm.status',false);
		$this->dbHandle->from('listings_main lm');
		$this->dbHandle->join('institute_university_mapping ium','ium.institute_id = lm.listing_type_id and lm.status = ium.status', 'inner');
		$this->dbHandle->join('course_details cd','cd.institute_id = lm.listing_type_id and lm.status = cd.status', 'inner');
		$this->dbHandle->where('lm.listing_type','institute');
		if(count($univIds)>0)
		{
			$this->dbHandle->where_not_in('ium.university_id',$univIds); // do not pick depts belonging to univs whose all docs are gonna get indexed anyways
		}
		$this->dbHandle->where_in('lm.status',array('live','deleted'));
		$this->dbHandle->where('last_modify_date>'.$date,'',false);
		$depts = $this->dbHandle->get()->result_array();
		$deptCourses = array_map(function($a){return $a['course_id'];}, $depts);
		echo "<br>".$this->dbHandle->last_query();
		// now course
		$this->dbHandle->select('distinct lm.listing_type_id, lm.status',false);
		$this->dbHandle->from('listings_main lm');
		$this->dbHandle->join('abroadCategoryPageData acpd','acpd.course_id = lm.listing_type_id and lm.status = acpd.status', 'inner');
		$this->dbHandle->where('lm.listing_type','course');
		if(count($univIds)>0)
		{
			$this->dbHandle->where_not_in('acpd.university_id',$univIds); // do not pick courses belonging to univs whose all docs are gonna get indexed anyways
		}
		if(count($deptCourses)>0)
		{
			$this->dbHandle->where_not_in('acpd.course_id',$deptCourses); // do not pick courses belonging to depts whose all docs are gonna get indexed anyways
		}
		$this->dbHandle->where_in('lm.status',array('live','deleted'));
		$this->dbHandle->where('last_modify_date>'.$date,'',false);
		$courses = $this->dbHandle->get()->result_array();
		echo "<br>".$this->dbHandle->last_query();
		return array( 'univs'=>$univs,
							'depts'=>$depts,
							'courses'=>$courses);			
	}

	public function getCurrenncyRateDetails($sourceId, $destId){
		$this->initiateModel('read');
		$this->dbHandle->select('conversion_factor, modified');
		$this->dbHandle->from('currency_exchange_rates');
		$this->dbHandle->where('source_currency_id', $sourceId);
		$this->dbHandle->where('destination_currency_id', $destId);
		$this->dbHandle->where('status', 'live');
		$currencyupdateDate = $this->dbHandle->get()->result_array();
		return reset($currencyupdateDate);
	}

	public function getCurrency($currencyId){
		$this->initiateModel('read');
		$this->dbHandle->select('id as currency_id,currency_code,currency_name,country_id as currency_country_id');
		$this->dbHandle->from('currency');
		$this->dbHandle->where('id', $currencyId);
		$currencyData = $this->dbHandle->get()->result_array();
		return reset($currencyData);
	}
}
