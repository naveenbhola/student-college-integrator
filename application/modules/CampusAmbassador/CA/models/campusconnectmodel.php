<?php
class CampusConnectModel extends MY_Model
{
/***
   Copyright 2015 Info Edge India Ltd
   $Author: UGC Team
 ***/
	private $dbHandle = '';
	function __construct(){
		parent::__construct('CampusAmbassador');
	}
	/**
	 * returns a data base handler object
	 *
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
	
	
	/* Get all Institutes and Courses having programId = $programId*/
	
	function getInstAndCrsIdForProgramId($programId){
		
		$this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');
		
		$key = 'getInstAndCrsIdForProgramId'.$programId;

        if($cacheLib->get($key)=='ERROR_READING_CACHE'){
	 		$sql = "SELECT camt.instituteId, count( camt.caId ) as CA_Count, concat_ws(',',group_concat(distinct(camt.courseId)), group_concat(distinct(caod.courseId))) as courseIdStr
				FROM `CA_ProfileTable` ca 
				Inner Join 
				`CA_MainCourseMappingTable` camt ON ca.id = camt.caId 
				Left join
				`CA_OtherCourseDetails` caod ON caod.mappingCAId = camt.id AND caod.status = 'live'
				WHERE ca.profileStatus = 'accepted' AND camt.status = 'live' 
				AND ca.programId = ? GROUP BY camt.instituteId";

		$query = $this->dbHandle->query($sql,array($programId));
		$resultSet = $query->result_array();
		$cacheLib->store($key,$resultSet, 86400);
		}else{
			$resultSet = $cacheLib->get($key);
		}
		return $resultSet;
	
	}
	
	
	
	
	
	function getCourseIdsWithCA($instIds)
	{
		$this->initiateModel('read');
		
		$sql = 'SELECT group_concat(distinct courseId) as courseIdStr FROM `CA_MainCourseMappingTable` WHERE `badge`="CurrentStudent" and status="live" and `isCurrentlyPursuing`="yes" and instituteId in (?) group by instituteId';
		$instIdsArr = explode(',',$instIds);
		$query = $this->dbHandle->query($sql, array($instIdsArr));
		$resultArray = $query->result_array();
		$res = array();
		foreach($resultArray as $val)
		{
			$res[] = $val['courseIdStr'];
		}
		return implode(',',$res);
	}
	

	/*Get all Paid Institutes from the above list of institutes having campus Reps */
	
	function getAllPaidInstitutesHavingCA($instituteIds){
		
		if(empty($instituteIds) || !is_array($instituteIds)){
			return false;
		}
		$this->initiateModel('read');
		//$instituteIds = implode(',',$instituteIds);
		$cacheLib = $this->load->library('cacheLib');
		$key = 'getAllPaidInstitutesHavingCA';
        if($cacheLib->get($key)=='ERROR_READING_CACHE'){
			$sql = "SELECT listing_type_id as instituteId , listing_type  FROM listings_main WHERE STATUS = 'live' AND listing_type_id IN (?) AND pack_type IN ( ".GOLD_SL_LISTINGS_BASE_PRODUCT_ID.", ".SILVER_LISTINGS_BASE_PRODUCT_ID.", ".GOLD_ML_LISTINGS_BASE_PRODUCT_ID." ) AND listing_type = 'institute' ORDER BY rand() LIMIT 12";
		
			$query = $this->dbHandle->query($sql, array($instituteIds));
			$resultSet = $query->result_array();
			
			$cacheLib->store($key,$resultSet, 86400);
			
		}else{
			$resultSet = $cacheLib->get($key);
		}
		
		return $resultSet;
		
	}
	
	
	/*get Total count of CA in a institute */
	function getTotalCACountOnInstitute($instIds){
		
		$this->initiateModel('read');
		
		if(!empty($instIds)){
		$sql ="SELECT camt.instituteId, count( camt.caId ) as CA_Count FROM `CA_MainCourseMappingTable` camt where camt.status = 'live' AND `isCurrentlyPursuing` = 'yes' and camt.badge='CurrentStudent' and camt.instituteId in (?) GROUP BY camt.instituteId";
		$instIdsArr = explode(',',$instIds);
		
		$query = $this->dbHandle->query($sql, array($instIdsArr));
		return $query->result_array();
	}
	}

	
	function getQuestionsForTopRankedColleges($courseIds)
	{   
		if(empty($courseIds)){return;}
		$this->initiateModel('read');
		
		$sql ="select mt.msgId, mt.msgTxt, mt.viewCount, qlr.courseId, qlr.instituteId, mt.creationDate from messageTable as mt join questions_listing_response as qlr on mt.msgId=qlr.messageId where qlr.status='live' and mt.status in('live','closed') and fromOthers='user' and mt.msgCount>0 and qlr.courseId in (?) order by mt.creationDate desc";
		$courseIdArr = explode(',',$courseIds);	
		$query = $this->dbHandle->query($sql, array($courseIdArr));
		return $query->result_array();
	}

	function getQuestionsForBottomCollegeCard($courseIds, $orderBy='viewCount')
	{
		if(empty($courseIds)){return;}
		$this->initiateModel('read');
		if($orderBy == 'viewCount')
		{
			$orderBy = 'mt.viewCount desc';
		}
		else
		{
			$orderBy = 'mt.creationDate desc';
		}
		$sql ="select mt.msgId, mt.msgTxt, mt.viewCount, qlr.courseId, qlr.instituteId, mt.creationDate from messageTable as mt join questions_listing_response as qlr on mt.msgId=qlr.messageId where qlr.status='live' and mt.status in('live','closed') and fromOthers='user' and mt.msgCount>0 and qlr.courseId in (?) order by ".$orderBy;
		$courseIdArr = explode(',',$courseIds);
		$query = $this->dbHandle->query($sql, array($courseIdArr));
		return $query->result_array();
	}
	
	function getTopRankedRecentAnswers($quesids)
	{
		$this->initiateModel('read');
		$result = array();
		if($quesids!='')
		{
			$sql ="select mt.msgId, mt.parentId, mt.msgTxt, mt.viewCount, mt.creationDate from messageTable as mt where mt.status in('live','closed') and mt.fromOthers='user' and mt.parentId in (?) order by mt.creationDate desc";
			/*$sql = "select mt.msgId, mt.parentId, mt.msgTxt, mt.viewCount, mt.creationDate from messageTable mt , questions_listing_response qlr, CA_ProfileTable cap, CA_MainCourseMappingTable camt, CA_OtherCourseDetails cmot where qlr.courseId in ($courseIds) and  mt.status in('live','closed') and qlr.status='live' and cap.profileStatus='accepted' and qlr.messageId=mt.threadId and mt.userId=cap.userId and (qlr.courseId = camt.courseId or (qlr.courseId=cmot.courseId and cmot.status='live'))  and camt.caId=cap.id  and mt.fromOthers='user' and mt.parentId in ($quesids) group by mt.threadId order by mt.creationDate";*/		
			$quesidsArr = explode(',',$quesids);
			$query = $this->dbHandle->query($sql, array($quesidsArr));
			$result = $query->result_array();
		}
		return $result;
	}
	
	
	function getMostViewedAnswers($quesids)
	{
		$this->initiateModel('read');
		$result = array();
		if($quesids!='')
		{
			$sql ="select mt.msgId, mt.parentId, mt.msgTxt, mt.viewCount, mt.creationDate from messageTable as mt where mt.status in('live','closed') and mt.fromOthers='user' and mt.parentId in (?) order by mt.creationDate desc";
			/*$sql = "select mt.msgId, mt.parentId, mt.msgTxt, mt.viewCount, mt.creationDate from messageTable mt , questions_listing_response qlr, CA_ProfileTable cap, CA_MainCourseMappingTable camt, CA_OtherCourseDetails cmot where qlr.courseId in ($courseIds) and  mt.status in('live','closed') and qlr.status='live' and cap.profileStatus='accepted' and qlr.messageId=mt.threadId and mt.userId=cap.userId and (qlr.courseId = camt.courseId or (qlr.courseId=cmot.courseId and cmot.status='live'))  and camt.caId=cap.id  and mt.fromOthers='user' and mt.parentId in ($quesids) group by mt.threadId order by mt.creationDate";*/
			$quesidsArr = explode(',',$quesids);
			$query = $this->dbHandle->query($sql, array($quesidsArr));
			$result = $query->result_array();
		}
		return $result;
	}
	
	function getAllAnswersWithFeaturedFlag()
	{
		$cacheLib = $this->load->library('cacheLib');
		$cacheKey = md5('getAllAnswersWithFeaturedFlag');
		$this->initiateModel('read');
		$res = array();
		if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE')
		{
			$sql = "select msgId from featuredAnswersCampusRep WHERE isFeatured='1'";
			$query = $this->dbHandle->query($sql);
			$result = $query->result_array();
			foreach($result as $val)
			{
				$res[] = $val['msgId'];
			}
			$cacheLib->store($cacheKey, $res, 86400);
			return $res;
		}else{
			return $cacheLib->get($cacheKey);
		}
	}
	
	function getAllFeaturedAnswers($courseIds = '')
	{
		//Removing Cache logic since this function is also called from Intermediate page for different courseIds
                //$cacheLib = $this->load->library('cacheLib');
                //$cacheKey = md5('getAllFeaturedAnswers');	

		$this->initiateModel('read');
		$featureAns = $this->getAllAnswersWithFeaturedFlag();
		if($courseIds != '' && !empty($featureAns))
		{
			//if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE'){
				$sql ="SELECT DISTINCT mt.msgId, mt.threadId, mt.msgTxt, qlr.courseId FROM messageTable mt join questions_listing_response qlr on mt.threadId=qlr.messageId WHERE mt.msgId in (".implode(',', $featureAns).") and mt.status='live' and qlr.status='live' and fromOthers='user' and qlr.courseId in (?)";
				$courseIdsArr = explode(',',$courseIds);
				$query = $this->dbHandle->query($sql, array($courseIdsArr));
				$result = $query->result_array();
				//$cacheLib->store($cacheKey, $result, 86400);
				return $result;
			//}
			//else{
			//	return $cacheLib->get($cacheKey);
			//}
		}
		return array();
	}
	
	
	function getQuestionsWithFeaturedAnswer($andIds)
	{
		$this->initiateModel('read');
		$result = array();
		if($andIds!='')
		{
			$sql ="select mt.msgId, mt.msgTxt, mt.creationDate, mt.viewCount, mt.msgCount, mt.listingTypeId as instituteId from messageTable as mt where mt.msgId in (?) and status IN ('live','closed') order by mt.creationDate desc limit 24";
			$andIdsArr = explode(',',$andIds);		
			$query = $this->dbHandle->query($sql, array($andIdsArr));
			$result = $query->result_array();
		}
		return $result;
	}
	
	
	function getAllCoursesWithCA($courseArr)
	{
		if(isset($courseArr) && $courseArr !='')
		{
			$addSql = ' And courseId in ('.$courseArr.')';
		}
		
		$cacheLib = $this->load->library('cacheLib');
		$cacheKey = md5('getAllCoursesWithCA');
		$this->initiateModel('read');
		if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE' || $courseArr != '')
		{
			$sql = "SELECT distinct courseId FROM `CA_MainCourseMappingTable` WHERE `badge`='CurrentStudent' and status='live' and `isCurrentlyPursuing`='yes' $addSql
				union
				SELECT courseId FROM `CA_OtherCourseDetails` WHERE `badge`='CurrentStudent' and status='live' $addSql";
			
			$query = $this->dbHandle->query($sql);
			$result = $query->result_array();
			$res = array();
			foreach($result as $course)
			{
				$res[] = $course['courseId'];
			}
			if(!empty($res)){			
				$data = $this->filterCoursesForFullTimeMBA(implode(',', $res));
				$cacheLib->store($cacheKey, $data, 86400);
				return $data;
		}
		}else{
			return $cacheLib->get($cacheKey);
		}
	}
	
	
	function filterCoursesForFullTimeMBA($courseIds)
	{
		$this->initiateModel('read');
		//$cacheLib = $this->load->library('cacheLib');
		//$cacheKey = md5('getAllCoursesWithCA_categoryFilter');
		//if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE')
		//{
			$sql = "select distinct course_id,institute_id from categoryPageData where course_id in (?) and category_id='23' and status='live'";
			$courseIdsArr = explode(',',$courseIds);
			$query = $this->dbHandle->query($sql, array($courseIdsArr));
			$result = $query->result_array();
			$res = array();
			foreach($result as $course)
			{
				$res['courseIds'][] = $course['course_id'];
				$res['instituteIds'][] = $course['institute_id'];
				
			}
			//$cacheLib->store($cacheKey, $res, 86400);
			return $res;
		//}else{
		//	return $cacheLib->get($cacheKey);
		//}
	}
	
	
	/**  get All Listing questions in last 30 days **/
	function questionsInLast30Days()
	{
		$this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');
		$cacheKey = md5('questionsInLast30Days');
		if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE')
		{
		
		$sql = "SELECT mt.msgId from messageTable mt where mt.status in ('live','closed') and  mt.mainAnswerId = -1 and mt.fromOthers='user' and mt.listingTypeId >0 and mt.msgCount>0 and mt.creationDate >= NOW() - INTERVAL 30 DAY";
			
			$query = $this->dbHandle->query($sql);
			$resultArray = $query->result_array();
		
			$questionIds = array();
			foreach($resultArray as $key=>$val){
			
				$questionIds[] = $val['msgId'];
			
			}
			$cacheLib->store($cacheKey, $questionIds, 86400);
			
				return $questionIds;
		}else{
				return $cacheLib->get($cacheKey);
		}
			
	}
	
	
	/*** get courseIds for questions ***/
	function getCourseIdForQuestions($questionIds)
	{
		$this->initiateModel('read');
		
		if($questionIds != '') {
			$sql = "select distinct courseId from questions_listing_response where messageId in (?) and status = 'live'";
			$questionIdsArr = explode(',',$questionIds);
			
			$query = $this->dbHandle->query($sql, array($questionIdsArr));
			$resultArray = $query->result_array();
			
			$courseIds = array();
			foreach($resultArray as $key=>$val){
				
				$courseIds[] = $val['courseId'];
				
			}
			
			
			return $courseIds;
		}
			
	}
	
	function getInstIdsMappedToQues($widgetType,$courseIds,$noOfTopInst,$programId){
		$this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');	

		$questionIdsArray = $this->questionsInLast30Days();
		//$questionIds = implode(',',$questionIdsArray);

		$courseArray = $this->getCourseIdForQuestions($questionIds);
		$course_ids = array_intersect($courseArray, $courseIds);
		if(!$course_ids){
			return array();
		}
		//$course_ids = implode(',',$course_ids);
		$key1 = 'getMostViewedColleges'.$programId;
		$key2 = 'getFeaturedColleges'.$programId;

		if($widgetType == 'mostViewed'){
			if($cacheLib->get($key1)=='ERROR_READING_CACHE'){
				if(!empty($questionIdsArray) && !empty($course_ids)){
					$sql = "SELECT qlr.instituteId,mt.viewCount from questions_listing_response qlr,messageTable mt where qlr.messageId = mt.msgId and qlr.messageId in (?) and qlr.courseId in (?) and qlr.status = 'live' and mt.status in ('live','closed') ORDER BY mt.viewCount DESC ";
					if($noOfTopInst > 0){
						$sql .= ' LIMIT '.$noOfTopInst;
					}
					$query = $this->dbHandle->query($sql, array($questionIdsArray ,$course_ids));
					$resultSet = $query->result_array();
				}
				$cacheLib->store($key1,$resultSet, 86400);
				}else{
					$resultSet = $cacheLib->get($key1);
			}
		}else{
			if($cacheLib->get($key2)=='ERROR_READING_CACHE'){
				 if(!empty($questionIdsArray) && !empty($course_ids)){
					$sql = "SELECT qlr.instituteId,mt.viewCount from questions_listing_response qlr,messageTable mt where qlr.messageId = mt.msgId and qlr.messageId in (?) and qlr.courseId in (?) and qlr.status = 'live' and mt.status in ('live','closed') ORDER BY mt.creationDate DESC ";
					if($noOfTopInst > 0){
						$sql .= ' LIMIT '.$noOfTopInst;
					}
					$query = $this->dbHandle->query($sql, array($questionIdsArray ,$course_ids));
					$resultSet = $query->result_array();
				}
				$cacheLib->store($key2,$resultSet, 86400);
				}else{
					$resultSet = $cacheLib->get($key2);
			}
		}
		return $resultSet;
	}

	
	/** get Full Time MBA CourseIds mapped to questions **/
	function getFullTimeMBACourseIdsMappedToQues($widgetType)
	{
		$this->initiateModel('read');
		$cacheLib = $this->load->library('cacheLib');
		
		$questionIdsArray = $this->questionsInLast30Days();
		$questionIds = implode(',',$questionIdsArray);
		
		$courseArray = $this->getCourseIdForQuestions($questionIds);
		$course_ids = implode(',',$courseArray);
		
		
		$courseIdsArray = $this->getAllCoursesWithCA($course_ids);
		$courseIds = implode(',',$courseIdsArray['courseIds']);
		$key1 = 'getMostViewedColleges';
		$key2 = 'getFeaturedColleges';
		
		if($widgetType == 'mostViewed'){
			
			if($cacheLib->get($key1)=='ERROR_READING_CACHE')
				{
				if($questionIds != '' && $courseIds != ''){
					$sql = "SELECT qlr.instituteId,mt.viewCount from questions_listing_response qlr,messageTable mt where qlr.messageId = mt.msgId and qlr.messageId in($questionIds) and qlr.courseId in ($courseIds) and qlr.status = 'live' and mt.status in ('live','closed') ORDER BY mt.viewCount DESC ";
			
					$query = $this->dbHandle->query($sql);
					$resultSet = $query->result_array();
				}
					$cacheLib->store($key1,$resultSet, 86400);
			
				}else{
					$resultSet = $cacheLib->get($key1);
			}
			
			
		}else{
			if($cacheLib->get($key2)=='ERROR_READING_CACHE')
				{
				if($questionIds != '' && $courseIds != ''){
					$sql = "SELECT qlr.instituteId,mt.viewCount from questions_listing_response qlr,messageTable mt where qlr.messageId = mt.msgId and qlr.messageId in($questionIds) and qlr.courseId in ($courseIds) and qlr.status = 'live' and mt.status in ('live','closed') ORDER BY mt.creationDate DESC ";
			
					$query = $this->dbHandle->query($sql);
					$resultSet = $query->result_array();
				}
					$cacheLib->store($key2,$resultSet, 86400);
				
				}else{
					$resultSet = $cacheLib->get($key2);
			}
				
		}
			
		return $resultSet;
	}
	
	
	
function getLatestAndPopularQuestions($orderOfQuestion, $userIds, $courseIds){

		$this->initiateModel('read');
                $courseSubQuery = '';
		$userIds = implode(",",$userIds);
                if($orderOfQuestion == 'Recent'){

			$sql = "select mt.msgId,mt.msgTxt,mt.viewCount,mt.msgCount from messageTable mt,questions_listing_response qlr where mt.msgId = qlr.messageId and qlr.courseId in (".$this->dbHandle->escape($courseIds).") and mt.mainAnswerId=-1 and mt.fromOthers = 'user' and mt.status = 'live' and mt.msgCount > 0 and qlr.status='live' order By mt.creationDate desc";
			$query = $this->dbHandle->query($sql);
            $results = $query->result_array();
			if(!empty($results)){
				
			foreach($results as $value){
				$msgIdArray .= $value['msgId'].",";
				
				$questions[] = $value['msgTxt'];
				$display['msgData'][$value['msgId']]['question'][] = $value['msgTxt'];
				$display['msgData'][$value['msgId']]['ansCount'][] = $value['msgCount'];
				$display['msgData'][$value['msgId']]['viewCount'][] = $value['viewCount'];
			}
			$msgIdArray = rtrim($msgIdArray, ','); 

			$sql1 = "select mt.msgId,mt.msgTxt,mt.viewCount,mt.threadId from messageTable mt where mt.mainAnswerId=0 and mt.fromOthers = 'user' and mt.status = 'live' and mt.userid in (".$this->dbHandle->escape($userIds).") and  mt.threadId in ($msgIdArray) ORDER BY mt.creationDate desc";

			$query1 = $this->dbHandle->query($sql1);
                        $finalresult = $query1->result_array();
			
			foreach($finalresult as $final){
				//$answer[] = $final['msgTxt'];
				if(!isset($display['msgData'][$final['threadId']]['answer']))
				$display['msgData'][$final['threadId']]['answer'] = $final['msgTxt'];
			}
			}
                }
		else if($orderOfQuestion == 'MostViewed'){
			$sql = "select mt.msgId, mt.msgTxt, mt.viewCount, mt.msgCount from messageTable mt,questions_listing_response qlr where mt.msgId = qlr.messageId and qlr.courseId in (".$this->dbHandle->escape($courseIds).") and mt.mainAnswerId=-1 and mt.fromOthers = 'user' and mt.status = 'live' and mt.msgCount > 0 and  mt.status = 'live' and qlr.status='live' and DATE(`creationDate`) > 
DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-%d' ) order By mt.creationDate, mt.viewCount desc ";
                        $query = $this->dbHandle->query($sql);
                        $results = $query->result_array();
			if(!empty($results)){
			foreach($results as $value){
				$msgIdArray .= $value['msgId'].",";
				$questions[] = $value['msgTxt'];
				$display['msgData'][$value['msgId']]['question'][] = $value['msgTxt'];
				$display['msgData'][$value['msgId']]['viewCount'][] = $value['viewCount'];
				$display['msgData'][$value['msgId']]['ansCount'][] = $value['msgCount'];

			
			}
			$msgIdArray = rtrim($msgIdArray, ',');
			
			$sql1 = "select mt.msgId, mt.msgTxt, mt.viewCount,mt.threadId from messageTable mt where mt.mainAnswerId=0 and mt.fromOthers = 'user' and mt.status = 'live' and mt.userid in (".$this->dbHandle->escape($userIds).") and mt.threadId in ($msgIdArray) ORDER BY mt.creationDate desc";
			$query1 = $this->dbHandle->query($sql1);
                        $finalresult = $query1->result_array();
			
			foreach($finalresult as $final){
				//$answer[] = $final['msgTxt'];
				if(!isset($display['msgData'][$final['threadId']]['answer']))
				$display['msgData'][$final['threadId']]['answer'] = $final['msgTxt'];
			}
			}
                }
		else if($orderOfQuestion == 'Featured'){
		$allFeaturedAns = $this->getAllFeaturedAnswers($courseIds);
		$ansId = array();
		foreach($allFeaturedAns as $val)
		{
			$ansId[] = $val['threadId'];
		}
		$allQues = $this->getQuestionsWithFeaturedAnswer(implode(',', $ansId));
		foreach($allQues as $value){
		$display['msgData'][$value['msgId']]['question'][] = $value['msgTxt'];
		$display['msgData'][$value['msgId']]['viewCount'][] = $value['viewCount'];
		$display['msgData'][$value['msgId']]['ansCount'][] = $value['msgCount'];
		}
		
		$answerData = get24AnswersForQuestionsWithFeaturedAnswer($allFeaturedAns, $allQues);
	
		foreach($answerData as $qid=>$final){
			
			if(!isset($display['msgData'][$qid]['answer']))
			$display['msgData'][$qid]['answer'] = $final;
			
			}
			
			
                }
                return $display;
        }

	function getCourseIdforInstitute($instId){
		$this->initiateModel('read');
		
		$sql ="SELECT distinct camt.courseId as courseIdStr FROM `CA_MainCourseMappingTable` camt, `CA_ProfileTable` ca WHERE ca.id = camt.caId AND `badge` = 'CurrentStudent' AND ca.profileStatus = 'accepted' AND camt.status = 'live' AND `isCurrentlyPursuing` = 'yes' AND instituteId IN (?) ORDER BY camt.creationDate desc";
			$instIdArr = explode(',',$instId);
			$query = $this->dbHandle->query($sql, array($instIdArr));
			$resultSet = $query->result_array();
			
			return $resultSet;

		
	}
	function getUserIdforInstitute($courseIds){

		$this->initiateModel('read');

		if(empty($courseIds)){
			return array();
		}
		
		$sql ="SELECT CA_ProfileTable.userId FROM  CA_ProfileTable JOIN CA_MainCourseMappingTable ON CA_ProfileTable.id=CA_MainCourseMappingTable.caId   where CA_MainCourseMappingTable.courseId in (?) AND CA_MainCourseMappingTable.badge='CurrentStudent' And CA_ProfileTable.profileStatus = 'accepted' And CA_MainCourseMappingTable.status = 'live' ORDER BY CA_ProfileTable.creationDate DESC";
			$courseIdsArr = explode(',',$courseIds);
			$query = $this->dbHandle->query($sql, array($courseIdsArr));
			$resultSet = $query->result_array();
			//$cacheLib->store($key,$resultSet, 86400);

		return $resultSet;

		
	}

	
	function getCampusRepDataForInstitute($courseIds){
	
	$this->initiateModel('read');
	$sql = "SELECT p.userId, p.creationDate, p.displayName, p.imageURL, m.instituteId, m.courseId, m.badge FROM CA_MainCourseMappingTable m, CA_ProfileTable p WHERE m.courseId in (?) AND m.status = 'live' and m.badge='CurrentStudent' and m.caId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate desc";
		$courseIdsArr = explode(',',$courseIds);
		$query = $this->dbHandle->query($sql, array($courseIdsArr));
		$resultSet = $query->result_array();
		return $resultSet;
	}
	
	function getTotalAnsCountOfCampusReps($userIds){
		//$userIds = implode(",",$userIds);
		$this->initiateModel('read');
		$sql = "Select Count(*) as count,`userId` from messageTable as AnsCount where userId in (?) and `fromOthers` = 'user' and `status`='live' and `mainAnswerId` = 0 group by userId";
	
		$query = $this->dbHandle->query($sql, array($userIds));
		$resultSet = $query->result_array();
		return $resultSet;
		
	}

	function updateProgramId($key, $value){
		$this->initiateModel('read');

		$sql = "UPDATE CA_ProfileTable capt, CA_MainCourseMappingTable camt
				SET capt.programId = ?
				WHERE capt.id = camt.caId and camt.courseId = ? ";
	
		$result = $this->dbHandle->query($sql, array($value, $key));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function getCCProgramMapping($programIds, $getAllData){
                if(!empty($programIds) && is_array($programIds)){
                //      $progIdString = implode(",", $programIds);
                        $programIdCheck = "programId in (?) and ";
                }else if(empty($programIds) && $getAllData == 1){
                        $programIdCheck ="";
                }else{
                        return;
                }
                $this->initiateModel('read');
                $sql = "Select programId, entityType, entityId from campusConnectProgram where $programIdCheck status ='live'";
                $query = $this->dbHandle->query($sql, array($programIds));
                $resultSet = $query->result_array();
                return $resultSet;
        }

	function findCourseInMain($id, $isCourse=true, $dbHandle){
		if(empty($id)){return;}

		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('read');	
		}
		
	    if($isCourse){
	    	$and = " camt.courseId = ?";// courseId
	    }else{
	    	$and = " camt.id In ($id)"; // id
	    }

		$sql = "SELECT GROUP_CONCAT(camt.id) as id, camt.instituteId, camt.courseId, camt.locationId, ca.programId 
				FROM `CA_MainCourseMappingTable` camt, `CA_ProfileTable` ca 
				WHERE ca.id = camt.caId 
				AND camt.status in ('draft','live','incomplete') 
				AND $and
				group by camt.locationId";
		$query = $this->dbHandle->query($sql, array($id));
		return $query->result_array();
	}
	
	function findCourseInOther($oldCourse, $dbHandle){
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('read');	
		}

		$sql = "SELECT id as otherId, mappingCAId 
				FROM `CA_OtherCourseDetails` 
				WHERE courseId = ?
				AND status in ('live','draft')";
		$query = $this->dbHandle->query($sql, array($oldCourse));
		return $query->result_array();
	}	

	function updateCRCourseInOther($updateIdStr, $newCourseId, $dbHandle){
		if(empty($updateIdStr) || empty($newCourseId)){return;}

		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}

		$sql = "UPDATE CA_OtherCourseDetails
				SET courseId = ?
				WHERE id in (?)";
		$updateIdArr = explode(',',$updateIdStr);
		$result = $this->dbHandle->query($sql, array($newCourseId,$updateIdArr));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function updateCRCourseInMain($updateIdStr, $newCourseId, $dbHandle, $newInstituteId){
		if(empty($updateIdStr) || empty($newCourseId)){return;}
		
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');
		}

		$sql = "UPDATE CA_MainCourseMappingTable
				SET courseId = ? , instituteId = ?
				WHERE id in (?)";
		$updateIdArr = explode(',',$updateIdStr);
		$result = $this->dbHandle->query($sql, array($newCourseId , $newInstituteId, $updateIdArr));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function getIdsFromMainCRTable($courseId,$dbHandle){
		if(empty($courseId)){return;}
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}

		$this->dbHandle->select('id,caId');
		$this->dbHandle->from('CA_MainCourseMappingTable');
		$this->dbHandle->where('status','live');
		$this->dbHandle->where('courseId',$courseId);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	function getOtherCrIds($courseId,$dbHandle){
		if(empty($courseId)){return;}
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}

		$this->dbHandle->select('distinct(CA_ocd.id) as id');
		$this->dbHandle->from('CA_MainCourseMappingTable CA_mcmt');
		$this->dbHandle->join('CA_OtherCourseDetails CA_ocd','CA_mcmt.id = CA_ocd.mappingCAId','inner');
		$this->dbHandle->where('CA_mcmt.courseId',$courseId);
		$this->dbHandle->where('CA_ocd.status','live');
		$this->dbHandle->where('CA_mcmt.status','live');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	function updateStatusForDeletedCourseInOtherForIds($ids,$dbHandle){
		if(!(is_array($ids) && count($ids) > 0)){
			return;
		}
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}

		$ids = implode(',',$ids);

		$sql = "UPDATE CA_OtherCourseDetails
				SET status = 'deleted'
				WHERE id in (".$this->dbHandle->escape($ids).")";
		$result = $this->dbHandle->query($sql);
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function updateStatusForDeletedCourseInProfileTable($ids,$dbHandle){
		if(!(is_array($ids) && count($ids) > 0)){
			return;
		}
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}

		//$ids = implode(',',$ids);

		$sql = "UPDATE CA_ProfileTable
				SET profileStatus = 'deleted'
				WHERE id in (?)";
		$result = $this->dbHandle->query($sql, array($ids));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function updateStatusForDeletedCourseInMain($courseId,$dbHandle){
		if(empty($courseId)){return;}
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}
		$sql = "UPDATE CA_MainCourseMappingTable
				SET status = 'deleted'
				WHERE courseId = ?";
		$result = $this->dbHandle->query($sql, array($courseId));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function checkIfCRExistInOtherTable($courseId,$dbHandle){
		if(empty($courseId)){return;}
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}
		$sql = "SELECT * from CA_OtherCourseDetails where status ='live' and courseId = ?";
		$result = $this->dbHandle->query($sql, array($courseId))->result_array();
		if(count($result) > 0){
   			return true;
   		}else{
   			return false;
   		}
	}

	function updateStatusForDeletedCourseInOther($courseId,$dbHandle){
		if(empty($courseId)){return;}
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}
		$sql = "UPDATE CA_OtherCourseDetails
				SET status = 'deleted'
				WHERE courseId = ?";
		$result = $this->dbHandle->query($sql, array($courseId));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function checkIfCRMappingExistForInstituteInMain($oldInstId){
		if(empty($oldInstId)){return false;}
		if(!is_array($oldInstId) || !(count($oldInstId) > 0)){
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('distinct(instituteId)');
		$this->dbHandle->from('CA_MainCourseMappingTable camt');
		$this->dbHandle->join('CA_ProfileTable capt','camt.caId = capt.id','inner');
		$this->dbHandle->where('camt.status','live');
		$this->dbHandle->where('capt.profileStatus','accepted');
		$this->dbHandle->where_in('camt.instituteId',$oldInstId);
		$result = $this->dbHandle->get()->result_array();
		$instituteIds = array();
		if(count($result)){
			foreach ($result as $key => $value) {
				$instituteIds[$value['instituteId']] = 0;
			}
			$instituteIds = array_keys($instituteIds);
			return array(
				'status' => true,
				'instituteIds' => implode(',',$instituteIds)
				);
		}else{
			return array('status' => false);
		}
	}

	function updateStatusForDeletedInstInMain($instId){
		if(empty($instId)){return;}
		if(!is_array($instId) || !(count($instId) > 0)){
			return;
		}
		//$instId = implode(',', $instId);
		$this->initiateModel('write');
		$sql = "UPDATE CA_MainCourseMappingTable camt, CA_ProfileTable capt SET camt.status = 'deleted', capt.profileStatus = 'deleted'
				WHERE camt.instituteId in (?) and camt.caId = capt.id";
		$result = $this->dbHandle->query($sql, array($instId));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function updateStatusForDeletedInstInOther($instId){
		if(empty($instId)){return;}
		if(!is_array($instId) || !(count($instId) > 0)){
			return;
		}
		//$instId = implode(',', $instId);
		$this->initiateModel('write');
		$sql = "UPDATE CA_MainCourseMappingTable camt, CA_OtherCourseDetails caod SET caod.status = 'deleted'
				WHERE camt.instituteId in (?) and camt.id = caod.mappingCAId";
		$result = $this->dbHandle->query($sql, array($instId));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function updateInstIdOfCR($oldInstId, $newInstId){
		if(empty($oldInstId) || empty($newInstId)){return;}
		if(!is_array($oldInstId) || !(count($oldInstId) > 0)){
			return;
		}
		$instId = implode(',', $oldInstId);
		$this->initiateModel('write');
		
		$sql = "UPDATE CA_MainCourseMappingTable camt SET camt.instituteId = ?
				WHERE camt.instituteId in (?) ";
		$result = $this->dbHandle->query($sql, array($newInstId,$oldInstId));
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	function getAllCCPrograms(){
		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('read');	
		}
		$sql = "select programId, programName, entityId, entityType from campusConnectProgram where status = 'live'";
		$result = $this->dbHandle->query($sql)->result_array();
		return $result;
	}

	function getAllCoursesWithCAForMigration()
	{
		$this->initiateModel('read');
		
		$sql = "SELECT distinct courseId FROM `CA_MainCourseMappingTable` WHERE status='live' 
				union
				SELECT courseId FROM `CA_OtherCourseDetails` WHERE status='live' ";
			
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		$res = array();
		foreach($result as $course)
		{
			$res[] = $course['courseId'];
		}
		return $res;
		
	}
	
}
