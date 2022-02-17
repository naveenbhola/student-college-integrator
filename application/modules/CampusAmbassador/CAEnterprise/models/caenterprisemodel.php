<?php
class CAEnterpriseModel extends MY_Model
{ /*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: CampusAmbassadorEnterpriseModel.php

 */


	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	private $dbHandle = '';
	function __construct(){
		parent::__construct('CareerProduct');
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
	function escapeMyString($variable){
		if(mysql_real_escape_string($variable))
		    return mysql_real_escape_string($variable);
		else
		    return mysql_escape_string($variable);
	}

	/*
	 @name: getAllCADetails
	 @description: this function is for getting Details for all applied users.
	 i.e. Badges, Accepted Users, Reject Users, Incomplete Profile Users
	 @param string $userInput: $start,$rows,$filter,$userNameFieldDataCA,$filterTypeFieldDataCA
	*/
	public function getAllCADetails($start,$rows,$filter,$userNameFieldDataCA,$filterTypeFieldDataCA,$catFilter){
		$this->initiateModel('read');
		$extraFilters = ''; $levelFiler = '';
		if($filterTypeFieldDataCA!='' && $filterTypeFieldDataCA=='User Name'){
			$extraFilters .= " and caft.displayName like '%".$this->escapeMyString($userNameFieldDataCA)."%'";
		}
		if($filterTypeFieldDataCA!='' && $filterTypeFieldDataCA=='Institute'){
			$extraFilters .= " and caft.displayName = ".$this->dbHandle->escape($userNameFieldDataCA);
		}
		if($filterTypeFieldDataCA!='' && $filterTypeFieldDataCA=='Email'){
			$extraFilters .= " and tu.email like '%".$this->escapeMyString($userNameFieldDataCA)."%' ";
		}
		
                if($filter == "All"){
                    $filter = "accepted','draft','deleted','incomplete','removed";
                }
		
		/**
		*@param   : $catFilter has (23/56/all) category_id
		*@description : add category filter
		*@author  : akhter
		**/
		$addSql = "";
		if($catFilter != "All"){
			$addSql = "and caft.programId = ".$this->dbHandle->escape($catFilter);
				
			$queryCmd = "select SQL_CALC_FOUND_ROWS caft.* from CA_ProfileTable caft,CA_MainCourseMappingTable camcm 
				  where camcm.caId = caft.id
				  and camcm.instituteId>0
				  and camcm.courseId>0
				  and camcm.locationId>0 $addSql
				  and caft.profileStatus IN ('".$filter."') $extraFilters ORDER BY caft.creationDate DESC LIMIT ".$start." , ".$rows;
                }else{
			$queryCmd = "select SQL_CALC_FOUND_ROWS caft.* from CA_ProfileTable caft where caft.profileStatus IN ('".$filter."') $extraFilters ORDER BY caft.creationDate DESC LIMIT ".$start." , ".$rows;
		}
			
		$query = $this->dbHandle->query($queryCmd);

		$totalCA = 0;
                $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $this->dbHandle->query($queryCmdTotal);
                foreach ($queryTotal->result() as $rowT) {
                        $totalCA  = $rowT->totalRows;
                }
		$result = array();
		$result['totalCA'] = $totalCA;
		$results = $query->result_array();
		$count = $query->num_rows();
		$i=0;
		$userArray = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $key=>$value){ 
				$userArray[] = $value['userId'];
				$result[$i]['ca'] = $value;
				$queryCmd = "select camcm.* from CA_ProfileTable caft join CA_MainCourseMappingTable camcm on (camcm.caId=caft.id)
					     where caft.id = ? and camcm.instituteId>0 and camcm.courseId>0 and camcm.locationId>0";
				$query = $this->dbHandle->query($queryCmd, array($value['id']));
				$eduResults = $query->result_array();
				if(!empty($eduResults) && is_array($eduResults)) {
					foreach ($eduResults as $k1=>$v1){
						$result[$i]['ca']['mainEducationDetails'][] = $v1;
					}
				}
				$queryCmd = "select caocm.* from CA_ProfileTable caft join CA_OtherCourseDetails caocm on (caocm.mappingCAId=caft.id)
					     where caft.id = ? and caocm.courseId>0 and caocm.mainCourseId>0";
				$query = $this->dbHandle->query($queryCmd, array($value['id']));
				$otherEduResults = $query->result_array();
				if(!empty($otherEduResults) && is_array($otherEduResults)) {
					foreach ($otherEduResults as $k2=>$v2){
						$result[$i]['ca']['otherEducationDetails'][] = $v2;
					}
				}

				if($value['imageURL'] != ''){
					$result[$i]['ca']['imageURL'] = addingDomainNameToUrl(array('url' =>$value['imageURL'], 'domainName' => MEDIA_SERVER ));

				}
				$i++;
			}
		}

                if(count($userArray) > 0){
                        $sql = "SELECT tu.userId, tu.displayname as dName, tu.firstname, tu.lastname, tu.email, tu.mobile , tu.isdCode FROM tuser tu WHERE userId IN (?)";
                        $query = $this->dbHandle->query($sql, array($userArray));
                        $users = $query->result_array();
                        $finalResult = array();
                        $finalResult['totalCA'] = $result['totalCA'];
                        foreach ($result as $entry){
                                foreach ($users as $user){
                                        if($entry['ca']['userId'] == $user['userId']){
                                                $entry['ca']['dName'] = $user['dName'];
                                                $entry['ca']['firstname'] = $user['firstname'];
                                                $entry['ca']['lastname'] = $user['lastname'];
                                                $entry['ca']['email'] = $user['email'];
                                                $entry['ca']['mobile'] = $user['mobile'];
                                                $entry['ca']['isdCode'] = $user['isdCode'];
                                                $finalResult[]['ca'] = $entry['ca'];
                                        }
                                }
                        }
                        return $finalResult;
                }

		return $result;
	}
	  /*
	 @name: removeCAProfilePic
	 @description: this function is to remove pic for Campus Ambassador or Applied User.
	 @param string $userInput: $userId
	*/
	function removeCAProfilePic($userId){
		$this->initiateModel('write');
		$queryUpdate = "update CA_ProfileTable set imageURL='' where userId=?";
		$queryRes = $this->dbHandle->query($queryUpdate,array($userId));
	}
	 /*
	 @name: updateStatusAndBadges
	 @description: this function is to update Status for Applied user.When user apply it's status in DB is 'draft'
	 When you Accept the user it's status in DB becomes 'accepted'
	 When you Reject the user it's status in DB becomes 'deleted'
	 When you send incomplete mailer to user it's status in DB becomes 'incomplete'
	 Moderator can also change the badges here i.e. 'None','Current Student', 'Official', 'Alumni'
	 @param string $userInput: $userId,$courseBadgeRelation,$courseBadgeOfficialRelation,$actionType
	*/  
	function updateStatusAndBadges($userId,$courseBadgeRelation,$courseBadgeOfficialRelation,$actionType,$uniqueId_official_main){
		$this->initiateModel('write');
		$queryPursuingStatus = '';
		$caBadges = '';
		$acceptedCount = '';
		if($actionType=='accepted'){
			$caStatus = 'live';
		}else{
			$caStatus = $actionType;
		}
		
		if($courseBadgeOfficialRelation==''){
			$queryUpdate = "update CA_ProfileTable set profileStatus=?, modificationDate=now() where id=?";
			$queryRes = $this->dbHandle->query($queryUpdate,array($actionType,$uniqueId_official_main));
			
			
		}else{
				$courseBadgeOfficialRelationArr = explode(':',$courseBadgeOfficialRelation);
				$uniqueId = $this->dbHandle->escape($courseBadgeOfficialRelationArr[0]);
				$courseId = $this->dbHandle->escape($courseBadgeOfficialRelationArr[1]);
				$badge    = $this->dbHandle->escape($courseBadgeOfficialRelationArr[2]);
				if($actionType=='accepted'){
					$caBadges = ', officialBadge="'.$badge.'"';
					if($badge!='None'){
						$acceptedCount = ", acceptedCount=acceptedCount+1";
				}
				}
				$queryUpdate = "update CA_ProfileTable set profileStatus=?, modificationDate=now() $caBadges $acceptedCount where id=?";
				$queryRes = $this->dbHandle->query($queryUpdate,array($actionType,$uniqueId));
		}
		
		if($courseBadgeRelation!=''){
			$courseBadgeRelation = rtrim($courseBadgeRelation,'#');
			$courseBadgeRelationArr = explode('#',$courseBadgeRelation);
			foreach($courseBadgeRelationArr as $key=>$value){
				$courseBadgeArr = explode(':',$value);
				$uniqueId = $courseBadgeArr[0];
				$courseId = $courseBadgeArr[1];
				$badge = $courseBadgeArr[2];
				if($actionType=='accepted'){
					$caBadges = ', badge="'.$badge.'"';
					if($badge!='CurrentStudent'){
						$queryPursuingStatus = ', isCurrentlyPursuing="No"';
					}else{
						$queryPursuingStatus = ', isCurrentlyPursuing="Yes"';
					}
					$acceptedCount = ", acceptedCount=acceptedCount+1";
				}
				
				$queryUpdate = "update CA_MainCourseMappingTable set status=? $caBadges $queryPursuingStatus $acceptedCount where id=?";
				$queryRes = $this->dbHandle->query($queryUpdate,array($caStatus,$uniqueId));
			}
		}
	}
	 /*
	 @name: getAllCADetailsForInstitute
	 @description: this function is to fetch Applied Users their details for a given Institute.
	 Currently we are implementing this for Auto Suggestor.
	 @param string $userInput: $start,$rows,$filter,$userNameFieldDataCA,$filterTypeFieldDataCA,$instituteId
	*/ 
	function getAllCADetailsForInstitute($start,$rows,$filter,$userNameFieldDataCA,$filterTypeFieldDataCA,$instituteId,$catFilter){
		$this->initiateModel('read');
		$extraFilters = ''; $levelFiler = '';
		if($filterTypeFieldDataCA!='' && $filterTypeFieldDataCA=='User Name'){
			$extraFilters .= " and caft.displayName like '%".$this->dbHandle->escape_like_str($userNameFieldDataCA)."%'";
		}
		if($filterTypeFieldDataCA!='' && $filterTypeFieldDataCA=='Institute'){
			$extraFilters .= " and caft.displayName = ".$this->dbHandle->escape($userNameFieldDataCA);
		}
		if($filterTypeFieldDataCA!='' && $filterTypeFieldDataCA=='Email'){
			$extraFilters .= " and tu.email = ".$this->dbHandle->escape($userNameFieldDataCA);
		}
		if($filter == "All")
                    $filter = "accepted','draft','deleted','incomplete','removed";
		
		/**
		*@param   : $catFilter has (23/56/all) category_id
		*@description : add category filter
		*@author  : akhter
		**/
		$addSql = "";
		if($catFilter != "All"){
			$addSql = " And camcmt.courseId = (SELECT cpd.course_id FROM `categoryPageData` cpd
				WHERE cpd.`course_id` = camcmt.courseId
				And cpd.category_id = ".$this->dbHandle->escape($catFilter)." 
				And cpd.status = 'live'
				limit 1)";
		}		

		$querySelect = "SELECT distinct SQL_CALC_FOUND_ROWS capt.id as uniqueId from CA_ProfileTable capt WHERE  officialInstituteId = ?  union SELECT caId as uId from CA_MainCourseMappingTable camcmt WHERE instituteId = ? and camcmt.instituteId>0 and camcmt.courseId>0 and camcmt.locationId>0 $addSql limit $start,$rows";
		
		$query = $this->dbHandle->query($querySelect,array($instituteId,$instituteId));
		$results = $query->result_array();
		$uniqueIds = '';
		foreach($results as $key=>$value){
			$uniqueIds .= $value['uniqueId'].',';
		}
		$uniqueIds =  rtrim($uniqueIds,',');
		$totalCA = 0;
                $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $this->dbHandle->query($queryCmdTotal);
                foreach ($queryTotal->result() as $rowT) {
                        $totalCA  = $rowT->totalRows;
                }
		$result = array();
		$result['totalCA'] = $totalCA;
		if($totalCA==0){
			return $result;
		}
		$queryCmd = "select SQL_CALC_FOUND_ROWS caft.*, tu.displayname as dName, tu.firstname, tu.lastname, tu.email, tu.mobile, tu.isdCode from tuser tu, CA_ProfileTable caft where caft.userId = tu.userid and caft.profileStatus IN ('".$filter."') $extraFilters  and caft.id in ($uniqueIds) ORDER BY caft.creationDate DESC";
		$query = $this->dbHandle->query($queryCmd);
		$results = $query->result_array();
		$i=0;
		if(!empty($results) && is_array($results)) {
			foreach ($results as $key=>$value){ 
				$result[$i]['ca'] = $value;
				$queryCmd = "select camcm.* from CA_ProfileTable caft join CA_MainCourseMappingTable camcm on (camcm.caId=caft.id)
					     where caft.id = ? and camcm.instituteId>0 and camcm.courseId>0 and camcm.locationId>0";
				$query = $this->dbHandle->query($queryCmd, array($value['id']));
				$eduResults = $query->result_array();
				if(!empty($eduResults) && is_array($eduResults)) {
					foreach ($eduResults as $k1=>$v1){
						$result[$i]['ca']['mainEducationDetails'][] = $v1;
					}
				}
				$queryCmd = "select caocm.* from CA_ProfileTable caft join CA_OtherCourseDetails caocm on (caocm.mappingCAId=caft.id)
					     where caft.id = ? and caocm.courseId>0 and caocm.mainCourseId>0";
				$query = $this->dbHandle->query($queryCmd, array($value['id']));
				$otherEduResults = $query->result_array();
				if(!empty($otherEduResults) && is_array($otherEduResults)) {
					foreach ($otherEduResults as $k2=>$v2){
						$result[$i]['ca']['otherEducationDetails'][] = $v2;
					}
				}

				if($value['imageURL'] != ''){
					$result[$i]['ca']['imageURL'] = addingDomainNameToUrl(array('url' =>$value['imageURL'], 'domainName' => MEDIA_SERVER ));
				}
				
				$i++;
			}
		}
		return $result;
	}
	 /*
	 @name: allOtherCourseForCA
	 @description: this function is to get all Other courses for a given CA.
	 @param string $userInput: $userId,$status
	*/ 
	function allOtherCourseForCA($userId,$status='',$mainCourseId,$badge){
		$this->initiateModel('read');
		if($status!=''){
			$statusQuery = " and status='live'";
		}
		$queryCmd = "select courseId from  CA_OtherCourseDetails caocm where mappingCAId=? $statusQuery and mainCourseId=? and badge=? and caocm.courseId>0 and caocm.mainCourseId>0";
		$query = $this->dbHandle->query($queryCmd,array($userId,$mainCourseId,$badge));
		$results = $query->result_array();
		$i=0;
		$mainArr = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $key=>$value){
				$mainArr[] = $value['courseId'];
			}
		}
		return $mainArr;
	}
	 /*
       @name: addOtherCourseWithCA
       @description: this function is to add Other courses with a Given Campus Ambassdor.
       After this action the campus ambassador has same badge for other courses also as he has for applied course.
       @param string $userInput: $userId,$commonCourseIds,$courseIdsForUpdateStatusDelete,$courseIdsForInsertStatusLive,$badge
      */ 
	function addOtherCourseWithCA($userId,$commonCourseIds,$courseIdsForUpdateStatusDelete,$courseIdsForInsertStatusLive,$badge,$mainCourseId,$uniqueId){
		$this->initiateModel('write');
		if(!empty($commonCourseIds)){
			$commonCourseIdsStr = implode(',',$commonCourseIds);
			$queryUpdate = "update CA_OtherCourseDetails set status='live',`acceptedCount` = `acceptedCount`+1 where badge=? and mappingCAId=? and courseId in ($commonCourseIdsStr)";
			$queryRes = $this->dbHandle->query($queryUpdate,array($badge,$uniqueId));
		}
		if(!empty($courseIdsForUpdateStatusDelete)){
			$courseIdsForUpdateStatusDeleteStr = implode(',',$courseIdsForUpdateStatusDelete);
			$queryUpdate = "update CA_OtherCourseDetails set status='deleted',modificationDate=now() where mappingCAId=? and courseId in (?)";
			$queryRes = $this->dbHandle->query($queryUpdate,array($uniqueId, $courseIdsForUpdateStatusDelete));
		}
		if(!empty($courseIdsForInsertStatusLive)){
			$queryStr = '';
			foreach($courseIdsForInsertStatusLive as $key=>$value){
				$queryStr .= "('".$this->dbHandle->escape($uniqueId)."','".$this->dbHandle->escape($value)."','live','".$this->dbHandle->escape($badge)."','".$this->dbHandle->escape($mainCourseId)."',`acceptedCount`+1),";
			}
			$queryStr = rtrim($queryStr,',');
			$queryInsert = "insert into CA_OtherCourseDetails (`mappingCAId`,`courseId`,`status`,`badge`,`mainCourseId`,`acceptedCount`) values $queryStr";
			$queryRes = $this->dbHandle->query($queryInsert);
		}
	}
	
	/*function getMainCourseData($userId){
		$this->initiateModel('read');
		$queryCmd = "select capt.isOfficial,capt.officialInstituteId,capt.officialCourseId,capt.officialInstituteLocId from CA_ProfileTable capt where capt.userId = ?";
		$query = $this->dbHandle->query($queryCmd,array($userId));
		$courseOfficalData = $query->result_array();
		$result = array();
		if(!empty($courseOfficalData) && is_array($courseOfficalData)) {
			foreach ($courseOfficalData as $key=>$value){
				if($value['isOfficial']=='Yes'){
					$result['official']['officialInstituteId'] = $value['officialInstituteId'];
					$result['official']['officialCourseId'] = $value['officialCourseId'];
					$result['official']['officialInstituteLocId'] = $value['officialInstituteLocId'];
				}
			}
		}
		
		$queryCmd = "select camcm.* from CA_ProfileTable capt join CA_MainCourseMappingTable camcm on (camcm.caId=capt.userId)
					     where capt.userId= ?";
		$query = $this->dbHandle->query($queryCmd,array($userId));
		$courseEduData = $query->result_array();
		if(!empty($courseEduData) && is_array($courseEduData)) {
			$i=0;
			foreach ($courseEduData as $key=>$value){
				$result['main'][$i]['instituteId'] = $value['instituteId'];
				$result['main'][$i]['courseId']    = $value['courseId'];
				$result['main'][$i]['locationId']  = $value['locationId'];
				$result['main'][$i]['isCurrentlyPursuing']  = $value['isCurrentlyPursuing'];
				$i++;
			}
		}
		return $result;
	}*/
	 /*
       @name: getUserDetails
       @description: this function is to fetch user detial from tuser table.
       We are using this function to send User Detials in Mailer.
       @param string $userInput: $userId
      */ 
	function getUserDetails($userId){
		$this->initiateModel('read');
		$queryCmd = "select email, textpassword,firstname, lastname from tuser where userId = ?";
		$query = $this->dbHandle->query($queryCmd,array($userId));
		$result = $query->result_array();
		return $result[0];
	}
	
	 /*
       @name: getAllCourseDiscussions
       @description: this function is to get discussions for all the courses.
       @param string $userInput:$start, $rows,$filter,$instituteId
      */ 
	function getAllCourseDiscussions($start = 0,$rows = 5,$filter = "All",$instituteId = 0,$courseIds = array()) {
               
		$this->initiateModel('read');       
		$filterIni = $filter ; 
		if($filter == "All")
		$filter = "live','history";
	       
		$courseIdStr = implode(',',$courseIds);
				
		if(!empty($instituteId) && $instituteId != 0 ) {
			if($filterIni == 'All' || $filterIni == 'live') {
                       $queryCmd = "select SQL_CALC_FOUND_ROWS lm.listing_type_id as institute_id ,lm.listing_title as institute_name , cd.course_id as course_id ,
                       cd.courseTitle as course_name , caht.sessionYear as session_year , caht.status as status from listings_main as lm left join
                       course_details as cd on cd.institute_id = lm.listing_type_id  left join CA_HistoryTable as caht on caht.courseId = cd.course_id
                       and caht.status IN ('".$filter."')
                       where lm.listing_type = 'institute' and lm.status = 'live' and cd.status = 'live' and lm.listing_type_id = ?  order by LOWER(TRIM(lm.listing_title)) ASC  limit $start,$rows";
                         }else {
                       $queryCmd = "select SQL_CALC_FOUND_ROWS lm.listing_type_id as institute_id ,lm.listing_title as institute_name , cd.course_id as course_id ,
                       cd.courseTitle as course_name , caht.sessionYear as session_year , caht.status as status from listings_main as lm left join
                       course_details as cd on cd.institute_id = lm.listing_type_id  left join CA_HistoryTable as caht on caht.courseId = cd.course_id
                       where lm.listing_type = 'institute' and lm.status = 'live' and cd.status = 'live'  and caht.status IN ('".$filter."') and  lm.listing_type_id = ? order by LOWER(TRIM(lm.listing_title)) ASC  limit $start,$rows";
				
			       }
                       $query = $this->dbHandle->query($queryCmd,array($instituteId,$instituteId));
               }else {
                      if($filterIni == 'All' || $filterIni == 'live') {
                       $queryCmd = "select SQL_CALC_FOUND_ROWS lm.listing_type_id as institute_id ,lm.listing_title as institute_name 
                       , cd.course_id as course_id ,
                       cd.courseTitle as course_name , caht.sessionYear as session_year , caht.status as status from listings_main as lm left join
                       course_details as cd on cd.institute_id = lm.listing_type_id  left join CA_HistoryTable as caht on caht.courseId = cd.course_id
                       and caht.status IN ('".$filter."')
                       where lm.listing_type = 'institute' and lm.status = 'live' and cd.status = 'live'
					   and cd.course_id IN (?)	
                       order by LOWER(TRIM(lm.listing_title)) ASC limit $start,$rows ";
                       
		      }else {
                       $queryCmd = "select SQL_CALC_FOUND_ROWS lm.listing_type_id as institute_id ,lm.listing_title as institute_name 
                       , cd.course_id as course_id ,
                       cd.courseTitle as course_name , caht.sessionYear as session_year , caht.status as status from listings_main as lm left join
                       course_details as cd on cd.institute_id = lm.listing_type_id  left join CA_HistoryTable as caht on caht.courseId = cd.course_id
                       where lm.listing_type = 'institute' and lm.status = 'live' and cd.status = 'live'  and caht.status IN ('".$filter."') 
                       and cd.course_id IN (?)	
                       order by LOWER(TRIM(lm.listing_title)) ASC limit $start,$rows";
			
		      }
                       $query = $this->dbHandle->query($queryCmd, array($courseIds));
          	}
               $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
               $queryTotal = $this->dbHandle->query($queryCmdTotal);
               $queryResults = $queryTotal->result();
               $totalRows = $queryResults[0]->totalRows;
               $result = $query->result_array();
               $returnData["total"] = $totalRows;
               $returnData["data"] = $result ;

		//Get the Session information for each of the course
		$historyData = array();
	        foreach ($result as $row){
			$course_id = $row['course_id'];
			//Get the History values for this Course
			$queryCmd = "SELECT sessionYear as lastSessionYear FROM CA_HistoryTable WHERE courseId = ? and status IN ('live','history') ORDER BY sessionYear DESC LIMIT 1";
			$queryRes = $this->dbHandle->query($queryCmd,array($course_id));
		        foreach ($queryRes->result_array() as $rowRes){
				$historyData[$course_id] = $rowRes['lastSessionYear'];
			}
	        }
		$returnData["historyDataOfCourse"] = $historyData;

               return $returnData;                     
       }

	 /*
       @name: archiveDiscussion
       @description: this function is used to Archive any discussion related to Courses
       @param string $userInput:InstituteId, courseId, Session Year (for which the new discussino needs to be created, type = 'All'/'Session' and Title of the Old discussion)
      */ 
       function archiveDiscussion($instituteId,$courseId,$sessionYear,$type,$title){
		$this->initiateModel();
		if($type=="All"){
			//Insert an Entry for the ALL session
			$queryCmd = "INSERT INTO CA_HistoryTable (instituteId,courseId,linkType,historyLink,endDate,status) VALUES (?,?,'All',?,now(),'history')";
			$query = $this->dbHandle->query($queryCmd,array($instituteId,$courseId,$title));
		}
		else{
			//Update an Entry for the Old Session which needs to be Archived
			$queryCmd = "UPDATE CA_HistoryTable SET historyLink = ? ,endDate = now() ,status = 'history' WHERE courseId = ? AND status='live' AND linkType= 'Session' AND sessionYear != ?";
			$query = $this->dbHandle->query($queryCmd,array($title,$courseId,$sessionYear));
		}
		
		//Insert an Entry for the New batch session
		$queryCmd = "INSERT INTO CA_HistoryTable (instituteId,courseId,sessionYear,linkType,status) VALUES (?,?,?,'Session','live')";
		$query = $this->dbHandle->query($queryCmd,array($instituteId,$courseId,$sessionYear));
		
		return 1;	
       }
       
       /*function getCourseResponses($courseId){
		$this->initiateModel();
		$queryCmd = "select r.*,t.email, t.firstname, t.lastname from tempLMSTable r , tuser t where listing_type = 'course' and listing_type_id = ? and action = 'Asked_Question_On_Listing' and submit_date >= DATE_SUB(NOW(),INTERVAL 15 DAY) and r.userId = t.userid;";
		$query = $this->dbHandle->query($queryCmd,array($courseId));
		$result = $query->result_array();
		return $result;
       }*/
       
       /**
        * Funtion to get all the course ids for which CA exists or existed sometime
        * @return uniquee courseids array
        */
       function getCACourseIds() {
	       	$this->initiateModel('read');
	       
	       	$queryCmd1 = "select distinct(officialCourseId) as courseId from CA_ProfileTable";
	       	$queryCmd2 = "select distinct(courseId) as courseId from CA_MainCourseMappingTable camcm where camcm.instituteId>0 and camcm.courseId>0 and camcm.locationId>0";
	       	$queryCmd3 = "select distinct(courseId) as courseId from CA_OtherCourseDetails caocm where caocm.courseId>0 and caocm.mainCourseId>0";
	       	 
	       	$query1 = $this->dbHandle->query($queryCmd1,array());
	       	$query2 = $this->dbHandle->query($queryCmd2,array());
	       	$query3 = $this->dbHandle->query($queryCmd3,array());
	       	$result1 = $query1->result_array();
	       	$result2 = $query2->result_array();
	       	$result3 = $query3->result_array();
	       	 
	       	$courseIdArray = array();
	       	foreach ($result1 as $id => $data) {
	       		$courseIdArray[] = $data['courseId'];
	       	}
	       	 
	       	foreach ($result2 as $id => $data) {
	       		$courseIdArray[] = $data['courseId'];
	       	}
	       
	       	foreach ($result3 as $id => $data) {
	       		$courseIdArray[] = $data['courseId'];
	       	}
	       
	       
	       	$courseIdArray = array_unique($courseIdArray);
	       
	       	return $courseIdArray;
       }

       function checkAcceptedCountOfCAForCourse($courseId){
		$this->initiateModel('read');
		$queryCmd = "SELECT SUM( t.acceptedCount ) AS total_count FROM ( SELECT IFNULL(sum(acceptedCount),0) as acceptedCount FROM CA_ProfileTable WHERE officialCourseId = ?
			UNION ALL SELECT IFNULL(sum(acceptedCount),0) as acceptedCount FROM CA_MainCourseMappingTable WHERE courseId = ?
			UNION ALL SELECT IFNULL(sum(acceptedCount),0) as acceptedCount FROM CA_OtherCourseDetails WHERE courseId = ?) t";
		$queryRes = $this->dbHandle->query($queryCmd,array($courseId,$courseId,$courseId));
		$results = $queryRes->result_array();
		return $results[0]['total_count'];
       }
       
       function getResponseDataForCourseId($courseId){
		$this->initiateModel('read');
		$queryCmd = "SELECT tu.firstname, tu.lastname, lms.* FROM `tempLMSTable` lms join tuser  tu on (tu.email = lms.email) WHERE lms.ACTION in('Request_E-Brochure','RANKING_PAGE_REQUEST_EBROCHURE','SEARCH_REQUEST_EBROCHURE') AND 0<=(unix_timestamp( now( ) ) - unix_timestamp( `lms`.`submit_date` ) ) and (unix_timestamp( now( ) ) - unix_timestamp( `lms`.`submit_date` ) )<=1296000  and lms.listing_subscription_type='paid' and lms.listing_type='course' and lms.listing_type_id=?";
		$query = $this->dbHandle->query($queryCmd,array($courseId));
		$results = $query->result_array();
		$mainArr = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $key=>$value){
				$mainArr[] = $value;
			}
		}
		return $mainArr;
       }

       function isCheckInstitute($institute_id)
       {
		$this->initiateModel('read');
	    $queryCmd = "SELECT listing_id as institute_id from shiksha_institutes
	                             where listing_id = ?
	                             AND status = 'live'";
	    $queryRes = $this->dbHandle->query($queryCmd,array($institute_id));
	    $results = $queryRes->result_array();
	    return $results[0]['institute_id'];
       }
       
       function updateCAInfo($institute_id,$course_id,$userId)
       {
		$this->initiateModel('write');
		$queryUpdate = "update CA_MainCourseMappingTable set `instituteId` = ?, `courseId` = ?
				where `caId` = (SELECT id from CA_ProfileTable where userId = ? AND (profileStatus = 'accepted' OR profileStatus = 'draft') limit 1) AND `badge` = 'CurrentStudent'";
		$queryRes = $this->dbHandle->query($queryUpdate,array($institute_id,$course_id,$userId));
		return $this->dbHandle->affected_rows();
       }
       
       function updateCAProfilePic($imageUrl, $userId)
       {
		$this->initiateModel('write');
		$queryUpdate = "update CA_ProfileTable set imageURL = ?
				where userId = ?
				AND (profileStatus = 'accepted' OR profileStatus = 'draft')";
		$queryRes = $this->dbHandle->query($queryUpdate,array($imageUrl, $userId));
		return $this->dbHandle->affected_rows();
       }
       
	/**
	     * this function is used when the CA removed,accepet,delete
	     * In case re-index on solr
	**/       
        function getCAOtherCourse($userId)
	{
		$this->initiateModel('read');	
		$query = "SELECT GROUP_CONCAT( otc.courseId ) AS totalOtherCourseId
			FROM CA_MainCourseMappingTable AS cm, CA_OtherCourseDetails AS otc, `CA_ProfileTable` AS c
			WHERE cm.id = otc.mappingCAId
			AND (cm.status = 'live' OR cm.status ='deleted' OR cm.status = 'removed')
			AND (otc.status = 'live' OR otc.status ='deleted' OR otc.status = 'removed')
			AND c.userId = ?
			AND (c.profileStatus = 'accepted' OR c.profileStatus = 'deleted' OR c.profileStatus = 'removed') 
			AND c.id = cm.caId";
			$query = $this->dbHandle->query($query,array($userId));
			$coursesId = $query->result();
		    return $coursesId[0]->totalOtherCourseId;
	}
	
	function getAllCACourseIdFromCR($userId)
	{
		$this->initiateModel('read');
		
		$other = $this->getCAOtherCourse($userId);
		
		if(isset($other) && $other !=''){
			$allOther = ','.$other;
		}
		
		$query = "SELECT concat(GROUP_CONCAT( cm.courseId ),',',c.officialCourseId) AS totalCourseId ,c.officialCourseId, c.id AS CampusRepId
			FROM CA_MainCourseMappingTable AS cm, `CA_ProfileTable` AS c
			WHERE cm.caId = c.id
			AND (cm.status = 'live' OR cm.status ='deleted' OR cm.status = 'removed')
			AND c.userId = ?
			AND (c.profileStatus = 'accepted' OR c.profileStatus = 'deleted' OR c.profileStatus = 'removed')
			AND c.id = cm.caId";
			$query = $this->dbHandle->query($query,array($userId));
			$coursesId = $query->result();
		        return $coursesId[0]->totalCourseId . $allOther;
	}
	
	function getAllCoursesWithCA()
	{
		$cacheLib = $this->load->library('cacheLib');
		$cacheKey = md5('getAllCoursesWithCA');
		$this->initiateModel('read');
		if($cacheLib->get($cacheKey)=='ERROR_READING_CACHE')
		{
			$sql = 'SELECT distinct courseId FROM `CA_MainCourseMappingTable` WHERE `badge`="CurrentStudent" and status="live" and `isCurrentlyPursuing`="yes"
				union
				SELECT courseId FROM `CA_OtherCourseDetails` WHERE `badge`="CurrentStudent" and status="live"';
			
			$query = $this->dbHandle->query($sql);
			$result = $query->result_array();
			$res = array();
			foreach($result as $course)
			{
				$res[] = $course['courseId'];
			}
			$cacheLib->store($cacheKey, $res, 86400);
			return $res;
		}else{
			return $cacheLib->get($cacheKey);
		}
	}
	
	function getCategoryId($courseId){
		
		$this->initiateModel('read');
		
		$sql = "SELECT  parentId from categoryBoardTable WHERE boardId in (SELECT distinct category_id from `categoryPageData` WHERE course_id =? AND status = 'live')";
			
		$query = $this->dbHandle->query($sql,array($courseId));
		$result = $query->result_array();
		return $result[0]['parentId'];	
		
	}
	
	
	function getMentorQnA($data)
	{
		$this->initiateModel('read');
		$result = array();
		foreach($data as $key=>$value)
		{
			$sql = "select * from CA_mentorship_answers ma join  CA_mentorship_questions mq on ma.quesId=mq.qid where ma.status='live' and ma.userId=?";
			$query = $this->dbHandle->query($sql, array($value['userId']));
			$result[$value['userId']] = $query->result_array();
		}
		return $result;
	}
	
	/**
		*@param 
		*@description : get all mentee details to assign mentor
		*@author  : yamini
	**/
		
	function getAllMenteeDetails($start,$rows,$statusFilter,$userNameFieldDataMentee,$filterTypeFieldDataMentee){
		
		$this->initiateModel('read');
		$extraFilters = ''; $levelFiler = '';
		if($filterTypeFieldDataMentee!='' && $filterTypeFieldDataMentee=='User Name'){
			$extraFilters .= " and tu.displayname like '%".$this->escapeMyString($userNameFieldDataMentee)."%'";
		}
		
		if($filterTypeFieldDataMentee!='' && $filterTypeFieldDataMentee=='Email'){
			$extraFilters .= " and tu.email like '%".$userNameFieldDataMentee."%' ";
		}
		
                if($statusFilter == "All"){
                    $statusFilter = "assigned','unassigned','deleted";
                }
		
		
			
		$queryCmd = "select SQL_CALC_FOUND_ROWS cmmd.*,tu.* from CA_mentorship_mentee_detail cmmd,tuser tu  where cmmd.userId=tu.userid and cmmd.menteeStatus in ('".$statusFilter."') $extraFilters  ORDER BY cmmd.creationDate DESC LIMIT ".$start." , ".$rows;;
				
		$query = $this->dbHandle->query($queryCmd);
	

		$totalMentee = 0;
                $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $this->dbHandle->query($queryCmdTotal);
                foreach ($queryTotal->result() as $rowT) {
                        $totalmentee  = $rowT->totalRows;
                }
		
		$result = array();
		$result['totalMentee'] = $totalmentee;
		$result['menteeData'] = $query->result_array();
		$count = $query->num_rows();
		
		return $result;		
		
	}
	
	function getMentorIdAssignedToMentee($menteeId){
		$this->initiateModel('read');
		$result = array();
		
		$sql = "select mentorId from CA_mentorship_relation cmr where cmr.menteeId = ? and status='live' limit 1";
		
		$query = $this->dbHandle->query($sql,array($menteeId));
		$result = $query->result_array();
		return $result;
		
	}
	
	
	function findMentorDetailsByEmail($mentorEmail){
		$this->initiateModel('read');
		$result = array();
		
		$sql = "select * from tuser tu where tu.email = ? limit 1";
		
		$query = $this->dbHandle->query($sql,array($mentorEmail));
		$result = $query->result_array();
		return $result;
		
	}
	
	function findMentorDetailsById($mentorId){
		$this->initiateModel('read');
		$result = array();
		
		$sql = "select * from tuser tu where tu.userid = ? limit 1";
		
		$query = $this->dbHandle->query($sql,array($mentorId));
		$result = $query->result_array();
		return $result;
		
	}
	
	function findMenteeCount($mentorUserId){
		
		$this->initiateModel('read');
		$result = array();
		
		$sql = "select count(*) as menteeCount from CA_mentorship_relation where mentorId = ? and status = 'live'";
		
		$query = $this->dbHandle->query($sql,array($mentorUserId));
		$result = $query->result_array();
		return $result[0]['menteeCount'];
		
	}
	
	function getMenteeExamDetails($menteeId){
		$this->initiateModel('read');
		$result = array();
		
		$sql = "select examName from CA_mentorship_mentee_mapping_exam where menteeId = ?";
		
		$query = $this->dbHandle->query($sql,array($menteeId));
		$result = $query->result_array();
		return $result;
		
	}
	
	function checkIfChatIdAlreadyExists($chatId){
		$this->initiateModel('read');
		$result = array();
		
		$sql = "select * from CA_mentorship_relation where chatId = ?";
		
		$query = $this->dbHandle->query($sql,array($chatId));
		$result = $query->result_array();
		return $result;
		
	}
	
	function addMentorMenteeRelation($mentorId,$menteeId,$chatId){
		$this->initiateModel('write');
		$queryCmd = "INSERT INTO CA_mentorship_relation (id,mentorId,menteeId,status,chatId,creation_date) VALUES (NULL,?,?,'live',?,now())";
		$query = $this->dbHandle->query($queryCmd,array($mentorId,$menteeId,$chatId));
		
		$sql = "UPDATE CA_mentorship_mentee_detail SET menteeStatus = 'assigned' WHERE userId = ?";
		$query1 = $this->dbHandle->query($sql,array($menteeId));
		
	}
	
	
	function updateMentorMenteeRelation($mentorId,$menteeId){
		$this->initiateModel('write');
		
		$queryCmd = "UPDATE CA_mentorship_relation SET status = 'deleted' WHERE menteeId = ?  and status='live'";
		$query = $this->dbHandle->query($queryCmd,array($menteeId));
		
		$sql = "UPDATE CA_mentorship_mentee_detail SET menteeStatus = 'unassigned' WHERE userId = ?";
		$query1 = $this->dbHandle->query($sql,array($menteeId));
			
	}
	
	function getMenteeInformation($menteeId){
		
		$this->initiateModel('read');
		
		$queryCmd = "select tu.firstname,tu.lastname,tu.email,tu.userid,tu.textpassword,cmmd.menteeId,cmmd.startEngYear,cmmd. 	prefCollegeLocation1,cmmd.prefCollegeLocation2,cmmd.prefCollegeLocation3,cmmd.prefEngBranche1,cmmd.prefEngBranche2,cmmd.prefEngBranche3 from tuser tu,CA_mentorship_mentee_detail cmmd where tu.userid = ? and tu.userid = cmmd.userId and cmmd.userId = ?";
		$query = $this->dbHandle->query($queryCmd,array($menteeId,$menteeId));
		
		$result = $query->result_array();
		return $result;
	}
	
	function getMentorInformation($mentorId){
		
		$this->initiateModel('read');
		
		$queryCmd = "select tu.displayname,tu.firstname,tu.lastname,tu.email,cpt.id, cmt.instituteId,cmt.semester,cmt.courseId from tuser tu ,CA_ProfileTable cpt, CA_MainCourseMappingTable cmt where tu.userid = ? and tu.userid = cpt.userId and cpt.profileStatus='accepted' and cpt.id = cmt.caId ";
		$query = $this->dbHandle->query($queryCmd,array($mentorId));
		
		$result = $query->result_array();
		return $result;
	}

	/**
		*@param 
		*@description : Get the schedule of chats which are complete
		*@author  : Bharat
		**/
	
	function getSchedule($start,$row,$statusFilter,$userNameFieldDataChatModeration,$filterTypeFieldDataChatModeration){
		
		$this->initiateModel('read');

		$extraFilters = ''; 
		if($filterTypeFieldDataChatModeration!='' && $filterTypeFieldDataChatModeration=='User Name'){
			$extraFilters .= " and ca.displayname like '%".$this->escapeMyString($userNameFieldDataChatModeration)."%'";
		}
		
		if($filterTypeFieldDataChatModeration!='' && $filterTypeFieldDataChatModeration=='Email'){
			$extraFilters .= " and ca.userid in (select userid from tuser where email like '%".$this->escapeMyString($userNameFieldDataChatModeration)."%') ";
		}
		
        if($statusFilter == "All"){
            $statusFilter = "draft','approved','disapproved";
        }
		
		

		$queryCmd = "select SQL_CALC_FOUND_ROWS tu.displayname as menteeName,casch.slotId, caslot.slotTime, casch.mentorId,casch.menteeId,ca.displayname as mentorName,cmmt.instituteId,cmch.chatText, cmch.id, cmch.chatStatus from tuser tu,CA_mentorship_chatSchedules casch,CA_mentorship_chatSlots caslot,CA_ProfileTable ca,CA_MainCourseMappingTable cmmt,CA_mentorship_chatHistory cmch where tu.userid = casch.menteeId and casch.slotId = caslot.slotId and ca.userId = casch.mentorId and ca.id = cmmt.caId and casch.scheduleStatus = 'completed' and casch.slotId = cmch.slotId and cmch.chatStatus in ('".$statusFilter."') $extraFilters GROUP BY casch.slotId ORDER BY casch.modificationDate DESC LIMIT ".$start.",".$row;;

		$query = $this->dbHandle->query($queryCmd);

		$totalData = 0;
                $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $this->dbHandle->query($queryCmdTotal);
                foreach ($queryTotal->result() as $rowT) {
                        $totalData  = $rowT->totalRows;
                }
		
		$result = array();
		$res['totalData'] = $totalData;
		$res['scheduleData'] = $query->result_array();
		$count = $query->num_rows();
		
		return $res;

	}

	



}
?>
