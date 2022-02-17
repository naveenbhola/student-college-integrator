<?php
class CADiscussionModel extends MY_Model
{ /*

   Copyright 2013 Info Edge India Ltd

   $Author: Rahul


 */


	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
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

	/**
	 * Get the Campus Ambassador for a particular course
	 * Also, we need to get the Current student and Alumni
	 * If, these are not available, we need to get the Institute Official
	 * Inputs: CourseId for which we need to fetch the Campus reps, Count of Campus reps to be displayed
	 */	

	function getCampusReps($courseId, $instituteId, $count = 3,$getEmail=false, $getCommentCount=false,$caJoiningDate = false){
		$this->initiateModel('read');
		$result = array();
		$i = $j = $k = 0;
		
		$selectQuery = '';
		$joinQuery = '';
		if($getEmail) {
			$selectQuery = ' t.email,t.firstname, ';
			$joinQuery = ' inner join tuser as t on t.userId = p.userId ';
			
		}
		//First, get the Current student data
		$queryCmd = "SELECT *, ".$selectQuery ." m.courseId as mainCourseId FROM CA_MainCourseMappingTable m, CA_ProfileTable p ".$joinQuery ."WHERE m.courseId = ? AND m.status = 'live' and m.badge='CurrentStudent' and m.caId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate desc LIMIT ?";
		$queryRes1 = $this->dbHandle->query($queryCmd,array($courseId,$count));
		$numRowsCS = $queryRes1->num_rows();
	        foreach ($queryRes1->result_array() as $row){
			$result['data'][$i] = $row;
			$i++;
	        }
		
		$queryCmd = "SELECT *, ".$selectQuery ." oc.mainCourseId FROM CA_OtherCourseDetails oc, CA_ProfileTable p ".$joinQuery .", CA_MainCourseMappingTable as m WHERE oc.courseId = ? AND oc.status = 'live' and oc.badge='CurrentStudent'  and oc.mappingCAId = m.id and p.profileStatus = 'accepted' and m.CAId = p.id ORDER BY p.creationDate desc LIMIT ?";
		$queryRes2 = $this->dbHandle->query($queryCmd,array($courseId,$count));
		$numRowsCS += $queryRes2->num_rows();
	        foreach ($queryRes2->result_array() as $row){
			$result['data'][$i] = $row;
			$i++;
	        }

                //Now, get the Official Users
                $queryCmd = "SELECT *, ".$selectQuery ." officialCourseId as mainCourseId FROM CA_ProfileTable p ".$joinQuery ."WHERE officialCourseId = ? AND profileStatus = 'accepted' ORDER BY creationDate desc LIMIT ?";
                $queryRes1 = $this->dbHandle->query($queryCmd,array($courseId,$count));
                $numRowsOf = $queryRes1->num_rows();
                foreach ($queryRes1->result_array() as $row){
                        $result['data'][$i] = $row;
                        $i++;
                }

                $queryCmd = "SELECT *, ".$selectQuery ." p.officialCourseId as mainCourseId FROM CA_OtherCourseDetails m, CA_ProfileTable p ".$joinQuery ."WHERE m.courseId = ? AND m.status = 'live' and m.badge='Official' and m.mappingCAId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate  desc LIMIT ?";
                $queryRes2 = $this->dbHandle->query($queryCmd,array($courseId,$count));
                $numRowsOf += $queryRes2->num_rows();
                foreach ($queryRes2->result_array() as $row){
                        $result['data'][$i] = $row;
                        $i++;
                }

		//Now, get the Alumni student data
		$queryCmd = "SELECT *, ".$selectQuery ." m.courseId as mainCourseId FROM CA_MainCourseMappingTable m, CA_ProfileTable p ".$joinQuery ."WHERE m.courseId = ? AND m.status = 'live' and m.badge='Alumni' and m.caId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate desc LIMIT ?";
		$queryRes1 = $this->dbHandle->query($queryCmd,array($courseId,$count));
		$numRowsAl = $queryRes1->num_rows();
	        foreach ($queryRes1->result_array() as $row){
			$result['data'][$i] = $row;
			$i++;
	        }

		$queryCmd = "SELECT *, ".$selectQuery ." oc.mainCourseId FROM CA_OtherCourseDetails oc, CA_ProfileTable p ".$joinQuery .", CA_MainCourseMappingTable as m WHERE oc.courseId = ? AND oc.status = 'live' and oc.badge='Alumni'  and oc.mappingCAId = m.id and p.profileStatus = 'accepted' and m.CAId = p.id ORDER BY p.creationDate desc LIMIT ?";
		
		$queryRes2 = $this->dbHandle->query($queryCmd,array($courseId,$count));
		$numRowsAl += $queryRes2->num_rows();
	        foreach ($queryRes2->result_array() as $row){
			$result['data'][$i] = $row;
			$i++;
	        }


		$totalReps = $numRowsAl + $numRowsOf + $numRowsCS;
                $result['totalReps']=$totalReps;          
		//If total number is less than 1, check if the Institute has answered. If yes, we will display Institutes information.
		/*We are returning default value of instituteRep as false because we don't want to display institute official*/
		$result['instituteRep'] = 'false';
		//if($totalReps<1){
			/*$queryCmd = "SELECT 1 FROM listings_main l, messageTable m WHERE l.listing_type_id = ? AND l.listing_type = 'course' and l.status = 'live' and l.username = m.userId and mainAnswerId = 0 and fromOthers = 'user' and m.status IN ('live','closed') and m.listingTypeId = ?";
			$queryRes = $this->dbHandle->query($queryCmd,array($courseId,$instituteId));
			$numRows = $queryRes->num_rows();
			if($numRows>0){
				$result['instituteRep'] = 'true';
			}*/
		//}

		//If we want to get the Number of comments of each Campus rep, fetch them from messageTable
		if($getCommentCount){
			$x = 0;
			foreach ($result['data'] as $userData){
				$campusRepId = $userData['userId'];
				$commentCount = 0 ;
				if($caJoiningDate) {
				
						$queryComments = "
								select count(*) as commentCount from (
								select mt.* ,
								(select mmt.creationDate  from messageTable  as mmt where mmt.msgId = mt.threadId and mmt.fromOthers = 'user' 
								and mmt.status IN ('live','closed') and mmt.listingTypeId > 0 and mmt.listingType = 'institute') as crDate 				 
								from messageTable as mt inner join questions_listing_response as qlr on 
								qlr.messageId = mt.threadId where mt.fromOthers='user' and mt.parentId>0 and mt.mainAnswerId = 0 and mt.listingType = 'institute'
								and mt.status IN ('live','closed') and mt.userId = ? and qlr.courseId = ? and qlr.status = 'live' ) 
								 as outQuery  where outQuery.crDate > ? ";
						
						$queryRes = $this->dbHandle->query($queryComments,array($campusRepId,$courseId,$caJoiningDate));
	                    $rowName = $queryRes->row();
		                $commentCount = $rowName->commentCount;
				}
				$result['data'][$x]['commentCount'] = $commentCount;
				$x++;
			}
		}
		
		return $result;
	}

	
	/***
	 * @input param: instituteId,start,no of tuples to be shown: count,courseIdToExclude
	 * @return array
	 */
	
	function getCoursesForListing($instituteId,$start,$count,$courseIdToExclude){
                $this->initiateModel('read');
                if($courseIdToExclude){
                        $courseIdToExclude= "and course_id !=?";
                }
                $status = "'live'";
                $queryCmd = "select SQL_CALC_FOUND_ROWS courseTitle,course_id, group_concat(course_details.status) as status,listing_seo_url from course_details,listings_main where institute_id =? and course_details.status in ($status) and course_details.version = listings_main.version and listings_main.listing_type='course' and listings_main.listing_type_id = course_id ". $courseIdToExclude." group by course_id order by  course_order limit $start,$count";
                $query = $this->dbHandle->query($queryCmd,array($instituteId,$courseIdToExclude));
                $result_array['courses'] = $query->result_array();
                $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $this->dbHandle->query($queryCmdTotal);
                $queryResults = $queryTotal->result();
                $totalRows = $queryResults[0]->totalRows;
                $result_array["total"] = $totalRows;
                return $result_array;
        }

	
	/**
	 * Get the Quenstions and Answers on the basis filters
	 * For the links the link data is passed as parameter
	 * @param array $historyData
	 * @param constant $all
	 * @return return total question/answers and
	 */
	function getQnA($historyData,$all = 1,$messageType = "question" , $questionsIds = '' ,$pageNo = null,$pageSize = null,$questionType = "All",$userId = 0,$excludeQuestionId = array(), $fromWhere = 'other', $campusRepId, $fromPage = '',$isMobile = false) {
	
		$this->initiateModel('read');
		$returnData = array();
		
		$questionLimitQuery = '';
		
		// Get the questions start date as the date when CA joined
		$caJoinDate = $this->getCAJoinDate($historyData['courseId']);

		// Get the questions start date as the date when CA joined
		if(isset($_GET['link_id']) && $_GET['link_id']!='' && empty($caJoinDate)){
			$caJoinDate = $this->getCAJoinDateForArchieveLinks($historyData['courseId']);			
		}
		
		if(empty($caJoinDate)) {
			return $returnData;
		}
		
		$dateCheckCourseQuery = "";
		$dateCheckInstituteQuery = "";
		
		// Format Query for Questions and answers separately
		if($messageType == "question") {
			// Set limit query based on pageNo and pageSiaze
			$questionQuery = "and mt.mainAnswerId = -1";
			if($pageSize == -1){
				$questionLimitQuery = "";
				$questionQuery = "and mt.mainAnswerId = -1";
			}
			else if(isset($pageNo) && isset($pageSize) && $pageNo >= 0 && $pageSize > 0) {
				$questionLimitQuery = "limit $pageNo,$pageSize";
				$questionQuery = "and mt.mainAnswerId = -1";
			}else {
				$questionLimitQuery = " limit 0,10 ";
				$questionQuery = "and mt.mainAnswerId = -1";
			}
				
			// Set the order Query differently for the case union and non union query
			if($fromWhere == 'listing'){
				$orderQuery = "order by creationDate DESC ";
			}
			else{
				$orderQuery = "order by isFeatured DESC,ifAnswered DESC,creationDate DESC ";
			}
			if($fromWhere == 'listing')
			{
				if($questionType == 'Unanswered' && $fromPage == 'courseListingPage'){
					$dateCheckCourseQuery = " and mt.creationDate >= NOW() - INTERVAL 1 MONTH ";
				}
				else{
					$dateCheckCourseQuery = " and mt.creationDate >= NOW() - INTERVAL 2 YEAR ";	
				}
			}else{
				if(!$all) {
					if($historyData["linkType"] != "All") {
						$date = (strtotime($caJoinDate) > strtotime($historyData["creationDate"]))?$caJoinDate:$historyData["creationDate"];
						$dateCheckCourseQuery = " and mt.creationDate > '{$date}' and mt.creationDate <= '{$historyData["endDate"]}' ";
					}else {
						$dateCheckCourseQuery = " and mt.creationDate > '{$caJoinDate}' and mt.creationDate <= '{$historyData["endDate"]}' ";
					}
				}else {
					$dateCheckCourseQuery = " and mt.creationDate > '{$caJoinDate}' ";
				}	
			}
			
			// Query for unanswered questions
			if($questionType == 'Unanswered') {
				$questionQuery .= " and mt.msgCount = 0";
			}
			$statusQuery = " mt.status IN ('live','closed') ";
			
			
			if(!empty($excludeQuestionId)) {
				$excludeQuestionIdQuery =  " mt.msgId NOT IN (".implode(',',$excludeQuestionId).") and ";
			}
		}else {
			// Format query in case when no link is selected
			$answerQuery = "and mt.mainAnswerId != -1 and mt.threadId IN (".implode(',',$questionsIds).") ";
				
			if(!$all && $historyData["linkType"] != "All") {
				$orderQuery = "order by mt.creationDate ASC ";
			}else {
				$orderQuery = "order by creationDate ASC ";
			}
			$statusQuery = " mt.status = 'live' ";
			
		}
	
		// query for report abuse functionality
		$getReportedAbuse = '';
		if($userId > 0)
			$getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=mt.msgId and ral.userId=?),0) reportedAbuse ";
	
		if($messageType == 'question') {
			if($campusRepId != ''){
				//get threadID of answers given by Campus Rep
				$queryCmd = "select SQL_CALC_FOUND_ROWS DISTINCT(mt.threadId),mt.threadId from messageTable mt, questions_listing_response qlr, messageTable mt1 where mt1.msgId=mt.threadId and mt1.status in ('live','closed') and mt.mainAnswerId != -1 and mt.userId in ($campusRepId) and mt.status='live' and mt.fromOthers= 'user' and mt.threadId=qlr.messageId and qlr.courseId = '{$historyData["courseId"]}' and qlr.status = 'live' ".$excludeQuestionIdQuery.$dateCheckCourseQuery.'order by mt1.creationDate DESC  '.$questionLimitQuery;
			}
			else{
				$queryCmd = "select SQL_CALC_FOUND_ROWS DISTINCT(mt.msgId),mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp, mt.digDown,mt.mainAnswerId,mt.threadId,mt.parentId,mt.viewCount, IFNULL((select fac.isFeatured from messageTable mt1,featuredAnswersCampusRep fac where mt1.fromOthers='user' and mt1.mainAnswerId = 0 and mt.msgId =mt1.threadId and fac.msgId = mt1.msgId and fac.isFeatured limit 0,1),0) isFeatured, (select if(mt.msgCount>0,1,0)) ifAnswered, tu.userId , tu.displayname, tu.firstname , tu.lastname,mt.status $getReportedAbuse from messageTable as mt inner join questions_listing_response as qlr on mt.threadId = qlr.messageId inner join tuser as tu on tu.userid = mt.userId where qlr.courseId = '{$historyData["courseId"]}' and qlr.status = 'live' and mt.fromOthers = 'user' and ".$excludeQuestionIdQuery.$statusQuery.$questionQuery.$dateCheckCourseQuery.$orderQuery.$questionLimitQuery;
			}

		}else {
			//get answer details posted by campus representative students (latest answer for every question)
			if($isMobile && $campusRepId !='')
			{
				$queryCmd = "select SQL_CALC_FOUND_ROWS DISTINCT(mt.msgId),mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId,mt.listingType,mt.digUp, mt.digDown,mt.mainAnswerId,mt.threadId,mt.parentId, tu.userId, tu.displayname, tu.firstname , tu.lastname,tu.avtarimageurl,mt.status  from messageTable as mt inner join (select mt.threadId,max(msgId) as msgId  from messageTable mt where mt.listingTypeId = '{$historyData['instituteId']}' and mt.listingType = 'institute' and mt.fromOthers = 'user' and  mt.status = 'live' and mt.mainAnswerId = 0 and mt.threadId IN (".implode(',',$questionsIds).") and mt.userId in ($campusRepId) group by threadId) temp on temp.msgId = mt.msgId inner join tuser as tu on tu.userid = mt.userId";
			}
			elseif($campusRepId == ''){
				$queryCmd = "select SQL_CALC_FOUND_ROWS DISTINCT(mt.msgId),mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp, mt.digDown,mt.mainAnswerId,mt.threadId,mt.parentId, tu.userId , tu.displayname, tu.firstname , tu.lastname,mt.status $getReportedAbuse from messageTable as mt inner join tuser as tu on tu.userid = mt.userId where mt.listingTypeId = '{$historyData['instituteId']}' and mt.listingType = 'institute' and mt.fromOthers = 'user' and ".$statusQuery.$answerQuery. $orderQuery ;
			}
			else{
				//get question details
				$whereClause = '';
				if(!empty($questionsIds)){
					$whereClause = "and mt.msgId in (".implode($questionsIds, ',').") ";
				}
				$queryCmd = "select SQL_CALC_FOUND_ROWS DISTINCT(mt.msgId),mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp, mt.digDown,mt.mainAnswerId,mt.threadId,mt.parentId, mt.viewCount, tu.userId , tu.displayname, tu.firstname , tu.lastname, mt.status $getReportedAbuse from messageTable as mt inner join tuser as tu on tu.userid = mt.userId where mt.listingTypeId = '{$historyData['instituteId']}' and mt.listingType = 'institute' and mt.fromOthers = 'user' and mt.status in ('live','closed') and mainAnswerId = -1 $whereClause order by creationDate desc" ;
			}
		}
		
		$queryRes = $this->dbHandle->query($queryCmd,array($userId));
 
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $this->dbHandle->query($queryCmdTotal);
		$queryResults = $queryTotal->result();
		$totalRows = $queryResults[0]->totalRows;
		$returnData["total"] = $totalRows;
		$returnData["data"] = $queryRes->result_array();
			
		return $returnData;
	}
	
	/**
	 * Gets the LinkData From Link id.
	 * @param unknown_type $linkId
	 */
	function getLinkDataForLinkId($linkId) {
		
		$this->initiateModel('read');
		$queryCmd = "select * from CA_HistoryTable where id = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($linkId));
		$data = $queryRes->result_array();
		
		return $data;
	}
	
	/**
	 * Gets all the history data for the courseId
	 * @param unknown_type $courseId
	 * @return unknown
	 */
	function getCourseHistoryLinks($courseId){

		$this->initiateModel('read');
		$queryCmd = "select * from CA_HistoryTable where courseId = ? and status = 'history'";
		$queryRes = $this->dbHandle->query($queryCmd,array($courseId));
		$data = $queryRes->result_array();
		
		return $data;
		
	}
	
	/**
	 * @param varchar $listingId
	 * @param varchar $listingType
	 * returns the date when first CA for the couse joined
	 * @author Rahul
	 */	
	function caJoinDateForListing($listingId , $listingType) {
		
		$joinDate = false;
		
		if($listingType == 'course') { 
			$joinDate = $this->getCAJoinDate($listingId);
		}else {	
			$this->initiateModel('read');
			$queryCmd =
			"select pt.creationDate as joinDate from CA_ProfileTable as pt where pt.OfficialInstituteId = ? " .
			"and pt.profileStatus = 'accepted' and pt.officialBadge = 'Official'  ".
			" UNION ".
			"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_MainCourseMappingTable as mcmt on pt.id  = mcmt.caId ".
			"where mcmt.instituteId = ? and pt.profileStatus = 'accepted' and mcmt.badge IN ('CurrentStudent','Alumni') and mcmt.status = 'live' ".
			" UNION ".
			"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_MainCourseMappingTable as mcmt on pt.id  = mcmt.caId ".
			" inner join CA_OtherCourseDetails as ocd on ocd.mappingCAId = mcmt.id  inner join course_details as cd on cd.course_id = ocd.courseId ".
			" where cd.institute_id = ? and pt.profileStatus = 'accepted' and ocd.badge IN ('CurrentStudent','Alumni') and ocd.status = 'live' ".
			" and mcmt.status = 'live' and cd.status = 'live' ".
			" UNION ".
			"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_OtherCourseDetails as ocd on pt.id  = ocd.mappingCAId ".
			" inner join course_details as cd on cd.course_id = ocd.courseId ".			
			" where cd.institute_id = ? and pt.profileStatus = 'accepted' and ocd.badge = 'Official' and ocd.status = 'live' and ".
			" ocd.mainCourseId = pt.officialCourseId and cd.status = 'live' ".
			" order by joinDate ASC limit 0,1 ";
			
			$queryRes = $this->dbHandle->query($queryCmd,array($listingId,$listingId,$listingId,$listingId));
			$data = $queryRes->result_array();
			
			$joinDate = $data[0]['joinDate'];
		}

		return $joinDate;
	} 

	/**
	 * @param varchar $courseId
	 * returns the date when first CA for the couse joined
	 * @author Rahul 
	 */
	function getCAJoinDate($courseId)  {
		
		$this->initiateModel('read');
		$joinDate = false;		
		$queryCmd = 
					"select pt.creationDate as joinDate from CA_ProfileTable as pt where pt.OfficialCourseId = ? " . 
					"and pt.profileStatus = 'accepted' and pt.officialBadge = 'Official'  ".
					" UNION ".
					"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_MainCourseMappingTable as mcmt on pt.id  = mcmt.caId ".
					"where mcmt.courseId = ? and pt.profileStatus = 'accepted' and mcmt.badge IN ('CurrentStudent','Alumni') and mcmt.status = 'live' ".
					" UNION ".
					"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_MainCourseMappingTable as mcmt on pt.id  = mcmt.caId ".
					" inner join CA_OtherCourseDetails as ocd on ocd.mappingCAId = mcmt.id ".
					" where ocd.courseId = ? and pt.profileStatus = 'accepted' and ocd.badge IN ('CurrentStudent','Alumni') and ocd.status = 'live' ".
					" and mcmt.status = 'live'".
					" UNION ".
					"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_OtherCourseDetails as ocd on pt.id  = ocd.mappingCAId ".
					" where ocd.courseId = ? and pt.profileStatus = 'accepted' and ocd.badge = 'Official' and ocd.status = 'live' and ocd.mainCourseId = pt.officialCourseId ".
					" order by joinDate ASC limit 0,1 ";

		$queryRes = $this->dbHandle->query($queryCmd,array($courseId,$courseId,$courseId,$courseId));
		$data = $queryRes->result_array();

		if(!empty($data) && !empty($data[0]['joinDate'])) {
			$joinDate = $data[0]['joinDate'];
		}
		return $joinDate;
		
	}
	
	/**
 	 * Get the Quenstions and Answers for study abroad on the basis filters
	 * For the links the link data is passed as parameter
	 * @param array $historyData
	 * @param constant $all
	 * @return return total question/answers and
	 */	
	function getQnAForStudyAbroad($historyData,$all = 1,$messageType = "question" , $questionsIds = '' ,$pageNo = null,$pageSize = null,$questionType = "All",$userId = 0,$studyAbroad = 0) {
		
		$this->initiateModel('read');
		$questionLimitQuery = '';
		// Hard Code value of Date for differentiating between instiute and course questions s
		$queryDate = '2013-11-20';
		$dateCheckCourseQuery = "";
		$dateCheckInstituteQuery = "";
		
		// Format Query for Questions and answers separately
		if($messageType == "question") {
			// Set limit query based on pageNo and pageSiaze
			if(isset($pageNo) && isset($pageSize) && $pageNo >= 0 && $pageSize>0) {
				$questionLimitQuery = "limit $pageNo,$pageSize";
				$questionQuery = "and mt.mainAnswerId = -1";
			}else {
				$questionLimitQuery = " limit 0,10 ";
				$questionQuery = "and mt.mainAnswerId = -1";
			}
			// Set the order Query differently for the case union and non union query
			//$orderQuery = "order by creationDate DESC ";
			$orderQuery = "order by isFeatured DESC,ifAnswered DESC,creationDate DESC ";
		        $checkForFeaturedDate= ",IFNULL((select fac.isFeatured from messageTable mt1,featuredAnswersCampusRep fac where mt1.fromOthers='user' and mt1.mainAnswerId = 0 and mt.msgId =mt1.threadId and fac.msgId = mt1.msgId and fac.isFeatured limit 0,1),0) isFeatured,
						(select if(mt.msgCount>0,1,0)) ifAnswered";
			
			if(!$all) {
				if($historyData["linkType"] != "All") {
					$dateCheckCourseQuery = " and mt.creationDate > '{$historyData["creationDate"]}' and mt.creationDate <= '{$historyData["endDate"]}' ";
					$orderQuery = "order by mt.creationDate DESC ";
				}else {
					$dateCheckCourseQuery = " and DATE(mt.creationDate) > '{$queryDate}' and mt.creationDate <= '{$historyData["endDate"]}' ";
					$dateCheckInstituteQuery = " and DATE(mt.creationDate) <= '{$queryDate}'  " ;
				}
			}else {
				$dateCheckCourseQuery = " and DATE(mt.creationDate) > '{$queryDate}' ";
				$dateCheckInstituteQuery = " and DATE(mt.creationDate) <= '{$queryDate}' ";
			}
			
			// Query for unanswered questions
			if($questionType == 'Unanswered') {
				$questionQuery .= " and mt.msgCount = 0";
			}
			$statusQuery = " mt.status IN ('live','closed') ";
		}else {
			// Format query in case when no link is selected
			$questionQuery = "and mt.mainAnswerId != -1 and mt.threadId IN (".implode(',',$questionsIds).") ";
				
			if(!$all && $historyData["linkType"] != "All") {
				$orderQuery = "order by mt.creationDate ASC ";
			}else {
				$orderQuery = "order by creationDate ASC ";
			}
			$statusQuery = " mt.status = 'live' ";
			
		}
	
		// query for report abuse functionality
		$getReportedAbuse = '';
		if($userId > 0)
			$getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=mt.msgId and ral.userId=?),0) reportedAbuse ";
		
		
		if(!$all) {
			if($historyData["linkType"] == "All") {
				$queryCmd = "select SQL_CALC_FOUND_ROWS mt.msgId,mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId,
				mt.listingType,mt.digUp,mt.digDown,mt.mainAnswerId,
				mt.threadId,mt.parentId, tu.userId , tu.displayname, tu.firstname , tu.lastname,mt.status $checkForFeaturedDate $getReportedAbuse
				from messageTable as mt inner join questions_listing_response as qlr on mt.threadId = qlr.messageId
				inner join tuser as tu on tu.userid = mt.userId
				where qlr.courseId = '{$historyData["courseId"]}' and qlr.status = 'live' and mt.fromOthers = 'user' and "
				.$statusQuery.
				$dateCheckCourseQuery.$questionQuery.
				" UNION ".
				"select mt.msgId,mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp
				,mt.digDown,mt.mainAnswerId,mt.threadId,mt.parentId , tu.userId , tu.displayname, tu.firstname , tu.lastname,mt.status $checkForFeaturedDate $getReportedAbuse
				from messageTable as mt inner join tuser as tu on tu.userid = mt.userId
				where mt.listingTypeId = '{$historyData['instituteId']}' and mt.listingType = 'institute' and mt.fromOthers = 'user' and mt.status = 'live'".
				$dateCheckInstituteQuery.$questionQuery.' '.$orderQuery.' '.$questionLimitQuery;
			}else {
				$queryCmd = "select SQL_CALC_FOUND_ROWS mt.msgId, mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId,mt.listingType,mt.digUp,mt.digDown,mt.threadId,mt.parentId,mt.mainAnswerId
				,tu.userId , tu.displayname, tu.firstname , tu.lastname,mt.status $getReportedAbuse
				from messageTable as mt inner join questions_listing_response as qlr on mt.threadId = qlr.messageId
				inner join tuser as tu on tu.userid = mt.userId
				where qlr.courseId = '{$historyData["courseId"]}' and qlr.status = 'live' and mt.fromOthers = 'user' and "
				.$statusQuery.
				$dateCheckCourseQuery.$questionQuery.' '.$orderQuery.' '.$questionLimitQuery;
				}
				
		}else {
				$queryCmd = "select SQL_CALC_FOUND_ROWS mt.msgId,mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp,mt.digDown,mt.mainAnswerId,
				mt.threadId,mt.parentId, tu.userId , tu.displayname, tu.firstname , tu.lastname,mt.status $checkForFeaturedDate $getReportedAbuse
				from messageTable as mt inner join questions_listing_response as qlr on mt.threadId = qlr.messageId
				inner join tuser as tu on tu.userid = mt.userId
				where qlr.courseId = '{$historyData["courseId"]}' and qlr.status = 'live' and mt.fromOthers = 'user' and "
				.$statusQuery.
				$dateCheckCourseQuery.$questionQuery.
				" UNION ". 
				"select  mt.msgId,mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp,mt.digDown,mt.mainAnswerId,
				mt.threadId,mt.parentId, tu.userId , tu.displayname, tu.firstname , tu.lastname,mt.status $checkForFeaturedDate $getReportedAbuse
				from messageTable as mt inner join tuser as tu on tu.userid = mt.userId
				where mt.listingTypeId = '{$historyData['instituteId']}' and mt.listingType = 'institute' and mt.fromOthers = 'user' and " 
				.$statusQuery.
				$dateCheckInstituteQuery.$questionQuery." $orderQuery ".$questionLimitQuery;
				}
				
				$queryRes = $this->dbHandle->query($queryCmd,array($userId));
	 
				$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
				$queryTotal = $this->dbHandle->query($queryCmdTotal);
				$queryResults = $queryTotal->result();
				$totalRows = $queryResults[0]->totalRows;
	
				$returnData["total"] = $totalRows;
				$returnData["data"] = $queryRes->result_array();
				
				return $returnData;
		
	}

	/**
	 *  Get Campus Reps for the Course/Institute.
     *  In case of institute pass courseIds as an array.  
     *  Pass type based on listing Type ( course/institute)
     *  computes comments (answers) count on the basis  joining date of CA for course/institute.
     *  returns an array indexed with courseId  and data as campus reps for that course.  
	 */
	
	function getCampusRepInfoForCourse($courseArr, $type='course', $instituteId, $count=3, $getAllCampusReps = false,$getCaAnsCount='true'){
		$this->initiateModel('read');
                $result = array();
                $i = $j = $k = 0;
		//$courseStr = implode(',',$courseArr);
		$storeCourseId = '';
		$storeUserId = '';
		$totalRep = 0;$limit='';
		if($type=='course' && !$getAllCampusReps) {
			$limit = ' limit '.$count;
		}
                //First, get the Current student data
                $queryCmd = "SELECT p.userId, p.displayName, p.imageURL, m.instituteId, m.courseId, m.badge FROM CA_MainCourseMappingTable m, CA_ProfileTable p WHERE m.courseId in (?) AND m.status = 'live' and m.badge='CurrentStudent' and m.caId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate desc $limit";
                $queryRes1 = $this->dbHandle->query($queryCmd, array($courseArr));
                $numRowsCS = $queryRes1->num_rows();
                foreach ($queryRes1->result_array() as $row){
			$result['caInfo'][$row['courseId']][$i]['userId'] = $row['userId'];
                        $result['caInfo'][$row['courseId']][$i]['instituteId'] = $row['instituteId'];
			$result['caInfo'][$row['courseId']][$i]['courseId'] = $row['courseId'];
			$result['caInfo'][$row['courseId']][$i]['badge'] = $row['badge'];
			$result['caInfo'][$row['courseId']][$i]['displayName'] = $row['displayName'];
			$result['caInfo'][$row['courseId']][$i]['isPrimaryCourse'] = true;
			//$result['caInfo'][$row['courseId']][$i]['imageURL'] = $row['imageURL'];
			if($row['imageURL']==''){
				 $result['caInfo'][$row['courseId']][$i]['imageURL'] = SHIKSHA_HOME.'/public/images/photoNotAvailable.gif';
			}else{
				 $result['caInfo'][$row['courseId']][$i]['imageURL'] = addingDomainNameToUrl(array('url' =>$row['imageURL'], 'domainName' => MEDIA_SERVER ));
			}
			$storeCourseId .= $row['courseId'].',';
			$storeUserId .= $row['userId'].',';
                        $i++;$totalRep++;
                }
		if(($totalRep<$count && $type=='course') || $type=='institute'){
	                $queryCmd = "SELECT p.userId, p.displayName, p.imageURL,m.instituteId, oc.courseId, oc.badge FROM CA_OtherCourseDetails oc, CA_ProfileTable p , CA_MainCourseMappingTable as m WHERE oc.courseId in (?) AND oc.status = 'live' and oc.badge='CurrentStudent'  and oc.mappingCAId = m.id and p.profileStatus = 'accepted' and m.CAId = p.id ORDER BY p.creationDate desc $limit";
        	        $queryRes2 = $this->dbHandle->query($queryCmd, array($courseArr));
                	$numRowsCS += $queryRes2->num_rows();
	                foreach ($queryRes2->result_array() as $row){
				$result['caInfo'][$row['courseId']][$i]['userId'] = $row['userId'];
				$result['caInfo'][$row['courseId']][$i]['instituteId'] = $row['instituteId'];
                	        $result['caInfo'][$row['courseId']][$i]['courseId'] = $row['courseId'];
                        	$result['caInfo'][$row['courseId']][$i]['badge'] = $row['badge'];
	                        $result['caInfo'][$row['courseId']][$i]['displayName'] = $row['displayName'];
        	                //$result['caInfo'][$row['courseId']][$i]['imageURL'] = $row['imageURL'];
                	        if($row['imageURL']==''){
                        	         $result['caInfo'][$row['courseId']][$i]['imageURL'] = SHIKSHA_HOME.'/public/images/photoNotAvailable.gif';
	                        }else{
	                        		$result['caInfo'][$row['courseId']][$i]['imageURL'] = addingDomainNameToUrl(array('url' =>$row['imageURL'], 'domainName' => MEDIA_SERVER ));
	                        }
	
				$storeCourseId .= $row['courseId'].',';
				$storeUserId .= $row['userId'].',';
                        	$i++;$totalRep++;
	                }
		}
		//Now, get the Official Users
		/*
		if(($totalRep<$count && $type=='course') || $type=='institute'){
	                $queryCmd = "SELECT p.userId, p.displayName, p.imageURL, p.officialCourseId, p.officialInstituteId as instituteId, p.officialBadge FROM CA_ProfileTable p WHERE officialCourseId in (?) AND profileStatus = 'accepted' and officialBadge='Official' ORDER BY creationDate desc $limit";
        	        $queryRes1 = $this->dbHandle->query($queryCmd, array($courseArr));
                	$numRowsOf = $queryRes1->num_rows();
	                foreach ($queryRes1->result_array() as $row){
							$result['caInfo'][$row['officialCourseId']][$i]['userId'] = $row['userId'];
        	                $result['caInfo'][$row['officialCourseId']][$i]['instituteId'] = $row['instituteId'];
                	        $result['caInfo'][$row['officialCourseId']][$i]['courseId'] = $row['officialCourseId'];
                        	$result['caInfo'][$row['officialCourseId']][$i]['badge'] = $row['officialBadge'];
	                        $result['caInfo'][$row['officialCourseId']][$i]['displayName'] = $row['displayName'];
        	                //$result['caInfo'][$row['officialCourseId']][$i]['imageURL'] = $row['imageURL'];
                	        if($row['imageURL']==''){
                        	         $result['caInfo'][$row['officialCourseId']][$i]['imageURL'] = SHIKSHA_HOME.'/public/images/photoNotAvailable.gif';
	                        }else{
	                        		$result['caInfo'][$row['officialCourseId']][$i]['imageURL'] = addingDomainNameToUrl(array('url' =>$row['imageURL'], 'domainName' => MEDIA_SERVER ));
	                        }
	
				$storeCourseId .= $row['officialCourseId'].',';
				$storeUserId .= $row['userId'].',';
                        	$i++;$totalRep++;
	                }
		}
		if(($totalRep<$count && $type=='course') || $type=='institute'){
	                $queryCmd = "SELECT p.userId, p.displayName, p.imageURL,p.officialInstituteId as instituteId, m.courseId as officialCourseId, m.badge as officialBadge FROM CA_OtherCourseDetails m, CA_ProfileTable p WHERE m.courseId in (?) AND m.status = 'live' and m.badge='Official' and m.mappingCAId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate desc $limit";
        	        $queryRes2 = $this->dbHandle->query($queryCmd,array($courseArr));
                	$numRowsOf += $queryRes2->num_rows();
	                foreach ($queryRes2->result_array() as $row){
							$result['caInfo'][$row['officialCourseId']][$i]['userId'] = $row['userId'];
        	                $result['caInfo'][$row['officialCourseId']][$i]['instituteId'] = $row['instituteId'];
                	        $result['caInfo'][$row['officialCourseId']][$i]['courseId'] = $row['officialCourseId'];
                        	$result['caInfo'][$row['officialCourseId']][$i]['badge'] = $row['officialBadge'];
	                        $result['caInfo'][$row['officialCourseId']][$i]['displayName'] = $row['displayName'];
        	                //$result['caInfo'][$row['officialCourseId']][$i]['imageURL'] = $row['imageURL'];
                	        if($row['imageURL']==''){
                        	         $result['caInfo'][$row['officialCourseId']][$i]['imageURL'] = SHIKSHA_HOME.'/public/images/photoNotAvailable.gif';
	                        }else{
	                        		$result['caInfo'][$row['officialCourseId']][$i]['imageURL'] = addingDomainNameToUrl(array('url' =>$row['imageURL'], 'domainName' => MEDIA_SERVER ));
	                        }

				$storeCourseId .= $row['officialCourseId'].',';
				$storeUserId .= $row['userId'].',';
                        	$i++;$totalRep++;
	                }
		}
		*/

		//Now, get the Alumni student data
		/*
		if(($totalRep<$count && $type=='course') || $type=='institute'){
	               $queryCmd = "SELECT p.userId, p.displayName, p.imageURL, m.courseId, m.instituteId, m.badge FROM CA_MainCourseMappingTable m, CA_ProfileTable p WHERE m.courseId in (?) AND m.status = 'live' and m.badge='Alumni' and m.caId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate desc $limit";
        	        $queryRes1 = $this->dbHandle->query($queryCmd,array($courseArr));
                	$numRowsAl = $queryRes1->num_rows();
	                foreach ($queryRes1->result_array() as $row){
				$result['caInfo'][$row['courseId']][$i]['userId'] = $row['userId'];
        	                $result['caInfo'][$row['courseId']][$i]['instituteId'] = $row['instituteId'];
                	        $result['caInfo'][$row['courseId']][$i]['courseId'] = $row['courseId'];
                        	$result['caInfo'][$row['courseId']][$i]['badge'] = $row['badge'];
	                        $result['caInfo'][$row['courseId']][$i]['displayName'] = $row['displayName'];
        	                //$result['caInfo'][$row['courseId']][$i]['imageURL'] = $row['imageURL'];
                	        if($row['imageURL']==''){
                        	         $result['caInfo'][$row['courseId']][$i]['imageURL'] = SHIKSHA_HOME.'/public/images/photoNotAvailable.gif';
	                        }else{
	                        		$result['caInfo'][$row['courseId']][$i]['imageURL'] = addingDomainNameToUrl(array('url' =>$row['imageURL'], 'domainName' => MEDIA_SERVER ));
	                        }

				$storeCourseId .= $row['courseId'].',';
				$storeUserId .= $row['userId'].',';
                        	$i++;$totalRep++;
	                }
		}
		if(($totalRep<$count && $type=='course') || $type=='institute'){
	                $queryCmd = "SELECT p.userId, p.displayName, p.imageURL, m.instituteId, oc.courseId, oc.badge,oc.mainCourseId FROM CA_OtherCourseDetails oc, CA_ProfileTable p , CA_MainCourseMappingTable as m WHERE oc.courseId in (?) AND oc.status = 'live' and oc.badge='Alumni'  and oc.mappingCAId = m.id and p.profileStatus = 'accepted' and m.CAId = p.id ORDER BY p.creationDate desc $limit";
	
        	        $queryRes2 = $this->dbHandle->query($queryCmd,array($courseArr));
                	$numRowsAl += $queryRes2->num_rows();
	                foreach ($queryRes2->result_array() as $row){
				$result['caInfo'][$row['courseId']][$i]['userId'] = $row['userId'];
        	                $result['caInfo'][$row['courseId']][$i]['instituteId'] = $row['instituteId'];
                	        $result['caInfo'][$row['courseId']][$i]['courseId'] = $row['courseId'];
                        	$result['caInfo'][$row['courseId']][$i]['badge'] = $row['badge'];
	                        $result['caInfo'][$row['courseId']][$i]['displayName'] = $row['displayName'];
        	                //$result['caInfo'][$row['courseId']][$i]['imageURL'] = $row['imageURL'];
                	        if($row['imageURL']==''){
                        	         $result['caInfo'][$row['courseId']][$i]['imageURL'] = SHIKSHA_HOME.'/public/images/photoNotAvailable.gif';
	                        }else{
	                        		$result['caInfo'][$row['courseId']][$i]['imageURL'] = addingDomainNameToUrl(array('url' =>$row['imageURL'], 'domainName' => MEDIA_SERVER ));
	                        }
				$storeCourseId .= $row['courseId'].',';
				$storeUserId .= $row['userId'].',';
                        	$i++;$totalRep++;
	                }
		}
		*/

		$finalArr = array();
		foreach($result['caInfo'] as $key=>$value){
			foreach($value as $k=>$v){
				$finalArr['caInfo'][$key][] = $v;
			}
		}
		if($storeCourseId=='' || $storeUserId==''){
			$finalArr['commentCount'] = 0;
			return $finalArr;
		}
		$storeCourseId  = rtrim($storeCourseId,',');
		$storeUserId = rtrim( $storeUserId,',');
		//added by akhter for campus rep widget on institute / course / ana page's
		if(isset($getCaAnsCount) && $getCaAnsCount == 'true')
		{
		$listingId = ($type == "course")?$courseArr[0]:$instituteId;
		
		$caJoinDate = $this->caJoinDateForListing($listingId , $type);

        $queryComments = " select count(*) as commentCount from (
				           select mt.* ,
						  (select mmt.creationDate  from messageTable  as mmt where mmt.msgId = mt.threadId and mmt.fromOthers = 'user' 
						   and mmt.status IN ('live','closed') and mmt.listingTypeId > 0 and mmt.listingType = 'institute') as crDate 	          
				          from messageTable as mt inner join questions_listing_response as qlr on  
				          qlr.messageId = mt.threadId where mt.fromOthers='user' and mt.parentId > 0 and mt.listingType = 'institute' 
				           and mt.status IN ('live','closed') and mt.userId in ($storeUserId) and qlr.courseId in ($storeCourseId) and  
				           qlr.status = 'live' and mt.mainAnswerId=0 ) 
        					as outQuery  where outQuery.crDate > ? ";

        $queryRes = $this->dbHandle->query($queryComments,array($caJoinDate));
        
		foreach ($queryRes->result_array() as $row){
	                $commentCount = $row['commentCount'];
        	        $finalArr['commentCount'] += $commentCount;
		}
		}
		return $finalArr;	
	}

	function getCAJoinDateForArchieveLinks($courseId){
		$this->initiateModel('read');
		$joinDate = false;		
		$queryCmd = 
					"select pt.creationDate as joinDate from CA_ProfileTable as pt where pt.OfficialCourseId = ? " . 
					"and pt.profileStatus ='removed' and pt.officialBadge = 'Official'  ".
					" UNION ".
					"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_MainCourseMappingTable as mcmt on pt.id  = mcmt.caId ".
					"where mcmt.courseId = ? and pt.profileStatus = 'removed' and mcmt.badge IN ('CurrentStudent','Alumni') and mcmt.status = 'removed'".
					" UNION ".
					"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_MainCourseMappingTable as mcmt on pt.id  = mcmt.caId ".
					" inner join CA_OtherCourseDetails as ocd on ocd.mappingCAId = mcmt.id ".
					" where ocd.courseId = ? and pt.profileStatus = 'removed' and ocd.badge IN ('CurrentStudent','Alumni') and ocd.status = 'live' ".
					" and mcmt.status = 'removed'".
					" UNION ".
					"select pt.creationDate as joinDate from CA_ProfileTable as pt inner join CA_OtherCourseDetails as ocd on pt.id  = ocd.mappingCAId ".
					" where ocd.courseId = ? and pt.profileStatus = 'removed' and ocd.badge = 'Official' and ocd.status = 'live' and ocd.mainCourseId = pt.officialCourseId ".
					" order by joinDate ASC limit 0,1 ";

		$queryRes = $this->dbHandle->query($queryCmd,array($courseId,$courseId,$courseId,$courseId));
		$data = $queryRes->result_array();

		if(!empty($data) && !empty($data[0]['joinDate'])) {
			$joinDate = $data[0]['joinDate'];
		}
		return $joinDate;
	}


        function getDetailsOfCampusRepForCourses($courseIds){
                $this->initiateModel('read');
                //$commaSeperateCourseIds = implode(',',$courseIds);
                foreach($courseIds as $key=>$value){
                        $resultSet[$value] = 'false';
                }

                //First, get the Current student data
                $sql = "select camcmt.courseId, capt.userId, capt.displayName  from CA_ProfileTable capt, CA_MainCourseMappingTable camcmt where capt.id = camcmt.caId and camcmt.badge='CurrentStudent' and capt.profileStatus='accepted' and camcmt.status='live' and camcmt.courseId in (?) ORDER BY capt.creationDate";
                $query = $this->dbHandle->query($sql, array($courseIds));
                $res = $query->result_array();
                $discardCourseIds = array();
                foreach($res as $key=>$value){
                        $resultSet[$value['courseId']] = array();
                        $resultSet[$value['courseId']]['id'] = $value['userId'];
                        $resultSet[$value['courseId']]['displayName'] = $value['displayName'];
                        $discardCourseIds[] = $value['courseId'];
                }

                $remainingCourseIds = array_diff($courseIds,$discardCourseIds);
                if(!empty($remainingCourseIds))
                {
                        //$commaSeperateRemainingCourseIds = implode(',',$remainingCourseIds);

                        $sql = "SELECT oc.mainCourseId as courseId, p.userId, p.displayName FROM CA_OtherCourseDetails oc, CA_ProfileTable p, CA_MainCourseMappingTable as m WHERE oc.courseId IN (?) AND oc.status = 'live' and oc.badge='CurrentStudent'  and oc.mappingCAId = m.id and p.profileStatus = 'accepted' and m.CAId = p.id ORDER BY p.creationDate";
                
                        $query = $this->dbHandle->query($sql, array($remainingCourseIds));
                        $res = $query->result_array();
                        foreach($res as $key=>$value){
                                $resultSet[$value['courseId']] = array();
                                $resultSet[$value['courseId']]['id'] = $value['userId'];
                                $resultSet[$value['courseId']]['displayName'] = $value['displayName'];
                                $discardCourseIds[] = $value['courseId'];
                        }
                }

                //Now, get the Official Users
                $remainingCourseIds = array_diff($courseIds,$discardCourseIds);
                if(!empty($remainingCourseIds))
                {
                        //$commaSeperateRemainingCourseIds = implode(',',$remainingCourseIds);

                        $sql = "SELECT officialCourseId as courseId, p.userId, p.displayName FROM CA_ProfileTable p WHERE officialCourseId IN (?) AND profileStatus = 'accepted' ORDER BY creationDate";
                
                        $query = $this->dbHandle->query($sql,array($remainingCourseIds));
                        $res = $query->result_array();
                        foreach($res as $key=>$value){
                                $resultSet[$value['courseId']] = array();
                                $resultSet[$value['courseId']]['id'] = $value['userId'];
                                $resultSet[$value['courseId']]['displayName'] = $value['displayName'];
                                $discardCourseIds[] = $value['courseId'];
                        }
                }

                $remainingCourseIds = array_diff($courseIds,$discardCourseIds);
                if(!empty($remainingCourseIds))
                {
                        //$commaSeperateRemainingCourseIds = implode(',',$remainingCourseIds);

                        $sql = "SELECT p.officialCourseId as courseId, p.userId, p.displayName FROM CA_OtherCourseDetails m, CA_ProfileTable p WHERE m.courseId IN (?) AND m.status = 'live' and m.badge='Official' and m.mappingCAId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate";
                
                        $query = $this->dbHandle->query($sql,array($remainingCourseIds));
                        $res = $query->result_array();
                        foreach($res as $key=>$value){
                                $resultSet[$value['courseId']] = array();
                                $resultSet[$value['courseId']]['id'] = $value['userId'];
                                $resultSet[$value['courseId']]['displayName'] = $value['displayName'];
                                $discardCourseIds[] = $value['courseId'];
                        }
                }

                //Now, get the Alumni student data
                $remainingCourseIds = array_diff($courseIds,$discardCourseIds);
                if(!empty($remainingCourseIds))
                {
                        //$commaSeperateRemainingCourseIds = implode(',',$remainingCourseIds);

                        $sql = "SELECT m.courseId as courseId, p.userId, p.displayName FROM CA_MainCourseMappingTable m, CA_ProfileTable p  WHERE m.courseId IN (?) AND m.status = 'live' and m.badge='Alumni' and m.caId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate";
                
                        $query = $this->dbHandle->query($sql,array($remainingCourseIds));
                        $res = $query->result_array();
                        foreach($res as $key=>$value){
                                $resultSet[$value['courseId']] = array();
                                $resultSet[$value['courseId']]['id'] = $value['userId'];
                                $resultSet[$value['courseId']]['displayName'] = $value['displayName'];
                                $discardCourseIds[] = $value['courseId'];
                        }
                }

                $remainingCourseIds = array_diff($courseIds,$discardCourseIds);
                if(!empty($remainingCourseIds))
                {
                        //$commaSeperateRemainingCourseIds = implode(',',$remainingCourseIds);

                        $sql = "SELECT oc.mainCourseId courseId, p.userId, p.displayName FROM CA_OtherCourseDetails oc, CA_ProfileTable p , CA_MainCourseMappingTable as m WHERE oc.courseId IN (?) AND oc.status = 'live' and oc.badge='Alumni'  and oc.mappingCAId = m.id and p.profileStatus = 'accepted' and m.CAId = p.id ORDER BY p.creationDate";
                
                        $query = $this->dbHandle->query($sql,array($remainingCourseIds));
                        $res = $query->result_array();
                        foreach($res as $key=>$value){
                                $resultSet[$value['courseId']] = array();
                                $resultSet[$value['courseId']]['id'] = $value['userId'];
                                $resultSet[$value['courseId']]['displayName'] = $value['displayName'];
                                $discardCourseIds[] = $value['courseId'];
                        }
                }

                return $resultSet;
        }

	function getQnAMyShortlistMobile($historyData,$all = 1,$messageType = "question" , $questionsIds = '' ,$pageNo = null,$pageSize = null,$questionType = "All",$userId = 0,$excludeQuestionId = array()) {
	
		$this->initiateModel('read');
		$returnData = array();
		
		$questionLimitQuery = '';
		
		// Get the questions start date as the date when CA joined
		$caJoinDate = $this->getCAJoinDate($historyData['courseId']);
		// Get the questions start date as the date when CA joined
		if(isset($_GET['link_id']) && $_GET['link_id']!='' && empty($caJoinDate)){
			$caJoinDate = $this->getCAJoinDateForArchieveLinks($historyData['courseId']);			
		}
		

		if(empty($caJoinDate)) {
			return $returnData;
		}
		
		$dateCheckCourseQuery = "";
		$dateCheckInstituteQuery = "";
		
		// Format Query for Questions and answers separately
		if($messageType == "question") {
			// Set limit query based on pageNo and pageSiaze
			$questionQuery = "and mt.mainAnswerId = -1";
			if($pageSize == -1){
				$questionLimitQuery = "";
				$questionQuery = "and mt.mainAnswerId = -1";
			}
			else if(isset($pageNo) && isset($pageSize) && $pageNo >= 0 && $pageSize > 0) {
				$questionLimitQuery = "limit $pageNo,$pageSize";
				$questionQuery = "and mt.mainAnswerId = -1";
			}else {
				$questionLimitQuery = " limit 0,10 ";
				$questionQuery = "and mt.mainAnswerId = -1";
			}
				
			// Set the order Query differently for the case union and non union query
			$orderQuery = "order by isFeatured DESC,ifAnswered DESC,creationDate DESC ";
			if(!$all) {
				if($historyData["linkType"] != "All") {
					$date = (strtotime($caJoinDate) > strtotime($historyData["creationDate"]))?$caJoinDate:$historyData["creationDate"];
					$dateCheckCourseQuery = " and mt.creationDate > '{$date}' and mt.creationDate <= '{$historyData["endDate"]}' ";
				}else {
					$dateCheckCourseQuery = " and mt.creationDate > '{$caJoinDate}' and mt.creationDate <= '{$historyData["endDate"]}' ";
				}
			}else {
				$dateCheckCourseQuery = " and mt.creationDate > '{$caJoinDate}' ";
			}
			
			// Query for unanswered questions
			if($questionType == 'Unanswered') {
				$questionQuery .= " and mt.msgCount = 0";
			}
			$statusQuery = " mt.status IN ('live','closed') ";
			
			
			if(!empty($excludeQuestionId)) {
				$excludeQuestionIdQuery =  " mt.msgId NOT IN (".implode(',',$excludeQuestionId).") and ";
			}
		}else {
			// Format query in case when no link is selected
			$answerQuery = "and mt.mainAnswerId != -1 and mt.threadId IN (".implode(',',$questionsIds).") ";
				
			if(!$all && $historyData["linkType"] != "All") {
				$orderQuery = "order by mt.creationDate ASC ";
			}else {
				$orderQuery = "order by creationDate ASC ";
			}
			$statusQuery = " mt.status = 'live' ";
			
		}
	
		// query for report abuse functionality
		$getReportedAbuse = '';
		if($userId > 0)
			$getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=mt.msgId and ral.userId=?),0) reportedAbuse ";
	
		
			$queryCmd = "select SQL_CALC_FOUND_ROWS DISTINCT(mt.msgId),mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp,
						mt.digDown,mt.mainAnswerId,mt.threadId,mt.parentId,
						IFNULL((select fac.isFeatured from messageTable mt1,featuredAnswersCampusRep fac where mt1.fromOthers='user' and mt1.mainAnswerId = 0 and mt.msgId =mt1.threadId and fac.msgId = mt1.msgId and fac.isFeatured limit 0,1),0) isFeatured,
						(select if(mt.msgCount>0,1,0)) ifAnswered,mt.status 
						$getReportedAbuse from messageTable as mt inner join questions_listing_response as qlr on mt.threadId = qlr.messageId where qlr.courseId = '{$historyData["courseId"]}' and qlr.status = 'live' 
						and mt.fromOthers = 'user' and ".$excludeQuestionIdQuery.$statusQuery.$questionQuery.$dateCheckCourseQuery.$orderQuery.$questionLimitQuery;
		
		$queryRes            = $this->dbHandle->query($queryCmd,array($userId));
		
		$queryCmdTotal       = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal          = $this->dbHandle->query($queryCmdTotal);
		$queryResults        = $queryTotal->result();
		$totalRows           = $queryResults[0]->totalRows;
		
		
		$returnData["total"] = $totalRows;
		$returnData["data"]  = $queryRes->result_array();
			
		return $returnData;
	}

	/***
	 * functionName : _checkCategoryOnCourse
	 * functionType : return type
	 * param        : $courseIds (array)
	 * desciption   : check campus rep course category
	 * @author      : akhter
	 * @team        : UGC
	***/
	
	function _checkCategoryOnCourse($courseIds, $allowSubCatArr)
	{
		$this->initiateModel('read');
		if(is_array($allowSubCatArr) && count($allowSubCatArr)>0){
			$allowSubCatArr = array_unique($allowSubCatArr);
		}else{
			$allowSubCatArr = array('23,56');
		}
		if(empty($courseIds)){
			return array();
		}
		$queryCmd = "SELECT cpd.course_id,cpd.category_id FROM `categoryPageData` cpd
				WHERE cpd.course_id IN (?)
				AND cpd.category_id IN (?) 
				AND cpd.status = 'live'";
		 $result = $this->dbHandle->query($queryCmd, array($courseIds, $allowSubCatArr))->result_array();
		foreach($result as $value)
		{
			$res[$value['course_id']] = $value['category_id'];
		}
		return $res;
	}
	
	/***
	 * functionName : _getCampusRepAnswerCount
	 * functionType : return type
	 * param        : $instituteId,$type='institute',$storeUserId (array),$storeCourseId (array)
	 * desciption   : get campus rep answer count by category Mba/be/b.tech
	 * @author      : akhter
	 * @team        : UGC
	***/
	
	function _getCampusRepAnswerCount($instituteId,$type='institute',$storeUserId,$storeCourseId)
	{
		$this->initiateModel('read');
//		$storeUserId = implode(',',$storeUserId);
//		$storeCourseId = implode(',',$storeCourseId);
		$caJoinDate = $this->caJoinDateForListing($instituteId , $type);
		$queryComments = "select count(*) as commentCount from (
				           select mt.* ,
						  (select mmt.creationDate  from messageTable  as mmt where mmt.msgId = mt.threadId and mmt.fromOthers = 'user' 
						   and mmt.status IN ('live','closed') and mmt.listingTypeId > 0 and mmt.listingType = 'institute') as crDate 	          
				          from messageTable as mt inner join questions_listing_response as qlr on  
				          qlr.messageId = mt.threadId where mt.fromOthers='user' and mt.parentId > 0 and mt.listingType = 'institute' 
				           and mt.status IN ('live','closed') and mt.userId in (?) and qlr.courseId in (?) and  
				           qlr.status = 'live' and mt.mainAnswerId=0 ) 
        					as outQuery  where outQuery.crDate > ? ";

				$queryRes = $this->dbHandle->query($queryComments,array($storeUserId, $storeCourseId, $caJoinDate));
				foreach ($queryRes->result_array() as $row){
					$commentCount = $row['commentCount'];
					$finalArr['commentCount'] += $commentCount;
				}
				return $finalArr;
		
	}

	// added by akhter
	// used on SRPV2 page
	function _isCAOnCourses($courseStr)
	{
		$this->initiateModel('read');

		//First, get the Current student data from maintable
          $queryCmd = "SELECT m.courseId,p.userId FROM CA_MainCourseMappingTable m, CA_ProfileTable p WHERE m.courseId in (".$courseStr.") and m.badge='CurrentStudent' and m.caId = p.id and p.profileStatus in ('accepted','removed') ORDER BY p.creationDate desc";
    	$queryRes1 = $this->dbHandle->query($queryCmd);
    	$numRowsCS = $queryRes1->num_rows();

    	foreach ($queryRes1->result_array() as $row){
			$finalArr[$row['courseId']][] = $row;
		}
		//Second, get the Current student data from othercourse
        $queryCmd = "SELECT oc.courseId,p.userId FROM CA_OtherCourseDetails oc, CA_ProfileTable p , CA_MainCourseMappingTable as m WHERE oc.courseId in (".$courseStr.") and oc.badge='CurrentStudent'  and oc.mappingCAId = m.id and p.profileStatus in ('accepted','removed') and m.CAId = p.id ORDER BY p.creationDate desc";
        $queryRes2 = $this->dbHandle->query($queryCmd);
    	$numRowsCS += $queryRes2->num_rows();
        foreach ($queryRes2->result_array() as $row){
    	        $finalArr[$row['courseId']][] = $row;
        }
		return $finalArr;
	}
	
	function getQuestionCountOnCourses($courseId,$campusRepId){
		$this->initiateModel('read');
		if($campusRepId == ''){
			return;
		}

		$sql = "select count(DISTINCT(mt.threadId)) as totalQuestions from messageTable mt, messageTable mt1, questions_listing_response qlr where mt1.msgId = mt.threadId and mt1.status in ('live','closed') and mt.mainAnswerId != -1 and mt.userId in ($campusRepId) and mt.status='live' and mt.fromOthers= 'user' and mt.threadId=qlr.messageId and qlr.courseId = ? and qlr.status = 'live'  AND mt.creationDate >= NOW() - INTERVAL 2 YEAR order by mt.creationDate DESC";
		$res = $this->dbHandle->query($sql , array($courseId));
		return $res->row_array();
	}
	function getTagsForEntity($questionId)
	{
		if(empty($questionId))
			return array();
		$this->initiateModel('read');
		$sql = "SELECT t.id,t.tags,tag_entity from tags_content_mapping tcm INNER JOIN tags t ON t.id = tcm.tag_id where content_id = ? AND t.status = 'live' AND tcm.status = 'live'";
		$result = $this->dbHandle->query($sql, array($questionId))->result_array();
		error_log('=====1============rows'.print_r($rows,true));
		return $result;
	}
	function isUserFollowingEntity($userId, $entityIds, $entityType = array('question'))
	{
		$this->initiateModel('read');
        $finalArray = array();
        if(!empty($entityString)){
        	error_log(' reddy A query');
                $sql = "SELECT entityId as following FROM tuserFollowTable WHERE userId = ? AND status = 'follow' AND entityType IN (?) AND entityId IN ($entityIds)";
                $rows = $this->dbHandle->query($sql, array($userId, $entityType, $entityIds))->result_array();
                $finalArray = array();
                
                foreach ($rows as $value) {
                    $finalArray[] = $value['following'];
                }
                error_log('=== reddy A '.$this->dbHandle->last_query());
                return $finalArray;
        }
        return array();
	}

	function getThreadFollowers($threadIds,$entityType = array('question')){

		if(empty($threadIds))
			return array();
		$this->initiateModel();
		$data 	  = array();
		$queryCmd = "SELECT entityId, count(*) as followers FROM tuserFollowTable WHERE status = 'follow' and entityType IN (?) and entityId IN (?) GROUP BY entityId;";

		$query  = $this->dbHandle->query($queryCmd, array($entityType,$threadIds));		
		$result = $query->result_array();

		foreach ($result as $value) {
			$data[$value['entityId']] = $value['followers'];
		}
		return $data;
	}
	function getUserUpvotedOnEntity($entityId = array(),$userId)
	{
		if(empty($entityId))
			return array();
		$this->initiateModel();
		$sql = "SELECT productId,digFlag FROM digUpUserMap WHERE productId IN (?) AND userId=? AND digUpStatus='live'";
		$result = $this->dbHandle->query($sql, array($entityId, $userId))->result_array();
		$data = array();
		foreach ($result as $resultKey => $resultValue) {
			$data[$resultValue['productId']] = $resultValue['digFlag'];
		}
		return $data;
	}
	function getAboutMeForUserIds($userId = array())
	{
		if(empty($userId))
			return array();
		$this->initiateModel();
		$sql = "SELECT userId,aboutMe from tUserAdditionalInfo where userId IN (?)";
		$result = $this->dbHandle->query($sql, array($userId))->result_array();
		$data = array();
		foreach ($result as $resultKey => $resultValue) {
			$data[$resultValue['userId']] = $resultValue['aboutMe'];
		}
		return $data;
	}
	function getQuestionsAnsweredByUser($questionIds, $loggedInUserId){
		if(empty($questionIds) || $loggedInUserId <= 0)
			return array();
        $this->initiateModel();

        $questionDetailArray = array();
        if($questionIds != ''){
                $sql = "SELECT distinct m.threadId FROM messageTable m WHERE m.threadId = m.parentId AND m.threadId IN (?) AND m.status IN ('live','closed') AND m.fromOthers='user' and m.userId = ?";
                $question_rows = $this->dbHandle->query($sql, array($questionIds, $loggedInUserId))->result_array();
                foreach ($question_rows as $qDetails){
                        $questionDetailArray[] = $qDetails['threadId'];
                }
         }
     return $questionDetailArray;
    }

    function _checkCategoryOnCourseForMigration($courseIds, $allowSubCatArr)
	{
		$this->initiateModel('read');

		$allowSubCatArr = array(23,28,56,69,84,70,20,33,18);
		if(is_array($allowSubCatArr) && count($allowSubCatArr)>0){
			$allowSubCatArr = array_unique($allowSubCatArr);
		}else{
		}
		if(empty($courseIds)){
			return array();
		}
		$queryCmd = "SELECT cpd.course_id,cpd.category_id FROM `categoryPageData` cpd
				WHERE cpd.course_id IN (?)
				AND cpd.category_id IN (?) 
				AND cpd.status = 'live'";
		$result = $this->dbHandle->query($queryCmd , array($courseIds, $allowSubCatArr))->result_array();
		foreach($result as $value)
		{
			$res[$value['course_id']] = $value['category_id'];
		}
		return $res;
	}
	function getCR($courseId){
			$this->initiateModel('read');
			$result=array();
                if(!empty($courseId) && count($courseId)>0 && is_array($courseId)){
                    $query = "SELECT t.email,t.firstname, m.courseId as mainCourseId FROM CA_MainCourseMappingTable m, CA_ProfileTable p  inner join tuser as t on t.userId = p.userId WHERE m.courseId in (?) AND m.status = 'live' and m.badge='CurrentStudent' and m.caId = p.id and p.profileStatus = 'accepted' ORDER BY p.creationDate desc";
	                $queryRes  = $this->dbHandle->query($query, array($courseId));
	                $i = 0;
	                foreach ($queryRes->result_array() as $row){
	                        $result['data'][$i] = $row;
	                        $i++;
	                }
	                $result['totalReps']    = $i;
	                $result['instituteRep'] = 'false';
                }
                
                return $result;
        }
	
}
