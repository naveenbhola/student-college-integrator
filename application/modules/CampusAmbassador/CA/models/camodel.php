<?php
class CAModel extends MY_Model
{ /*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: CampusAmbassadorEnterprise

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
	/*
	 @name: getCareerList
	 @description: this is for get list of careers
	 @param string $userInput: No input parameters
	*/
	
	public function saveCAData($CAData, $edit = false , $userId = null){
		$this->initiateModel('write');
		$result = array();
		
		foreach ($CAData as $tableName => $dataToInsert) {
			if($edit) {
				$this->dbHandle->where("id",$dataToInsert['id']);
				$this->dbHandle->update($tableName,$dataToInsert);
			}else {
				if(!empty($dataToInsert)) {
					if($tableName == "CA_ProfileTable") {

						$insertId = $this->dbHandle->insert($tableName,$dataToInsert);
						$last_insert_id = $this->dbHandle->insert_id();
					}else {
						foreach ($dataToInsert as $indes => $data) {
							$data['caId'] = $last_insert_id;
							$this->dbHandle->insert($tableName,$data);
						}
					}
				
				}	
			}
			
		}
		$result["insert_id"] = $insertId;
	 	return $result;
       }

	public function getAllCADetails($caUserId,$status=''){
		$this->initiateModel('write');
		$result = array();
		$queryStatus1 = "";
		$queryStatus2 = "";
		$queryStatus3 = "";
		if($status=='live'){
			$queryStatus1 = " and capt.profileStatus='accepted'";
			$queryStatus2 = " and camcm.status='live'";
			$queryStatus3 = " and caocm.status='live'";
		}
		if($status=='NOTDELETED'){
			$queryStatus1 = " and capt.profileStatus NOT IN ('deleted','removed') ";
			$queryStatus2 = " and camcm.status NOT IN ('deleted','removed') ";
			$queryStatus3 = " and caocm.status NOT IN ('deleted','removed') ";
		}
		
       	$queryCmd = "select capt.*, tu.displayname as dName,
       	tu.firstname, tu.lastname, tu.email, tu.mobile from tuser tu,
       	CA_ProfileTable capt where capt.userId = tu.userid and capt.userId = ? $queryStatus1";
		$query = $this->dbHandle->query($queryCmd,array($caUserId));
		$results = $query->result_array();
		$count = $query->num_rows();
		$i=0;
		if(!empty($results) && is_array($results)) {
			foreach ($results as $key=>$value){ 
				$result[$i]['ca'] = $value;
       			$queryCmd = "select camcm.* from CA_ProfileTable capt
       			join CA_MainCourseMappingTable camcm on (camcm.caId=capt.id)
       			where capt.id = ? $queryStatus2";
				$query = $this->dbHandle->query($queryCmd,array($value['id']));
				$eduResults = $query->result_array();
				if(!empty($eduResults) && is_array($eduResults)) {
					foreach ($eduResults as $k1=>$v1){
						$result[$i]['ca']['mainEducationDetails'][] = $v1;
					}
				}
       			$queryCmd = "select caocm.* from CA_ProfileTable capt
       			join CA_OtherCourseDetails caocm on (caocm.mappingCAId=capt.id)
       			where capt.id = ? $queryStatus3";
				$query = $this->dbHandle->query($queryCmd,array($value['id']));
				$otherEduResults = $query->result_array();
       			if(!empty($otherEduResults) &&
       					is_array($otherEduResults)) {
					foreach ($otherEduResults as $k2=>$v2){
						$result[$i]['ca']['otherEducationDetails'][] = $v2;
					}
				}

				if($value['imageURL'] != ''){
					$result[$i]['ca']['imageURL'] = addingDomainNameToUrl(array('url' =>$value['imageURL'], 'domainName' => MEDIA_SERVER ));
				}
				$i++;
			}
		} //_p($result);
		return $result;
	}
	
	function checkIfUserIsCampusAmbassador($userId){
		$this->initiateModel('read');
		$result = array();
		$queryCmd = "select 1 from tuser tu, CA_ProfileTable capt where capt.userId = tu.userid and capt.profileStatus = 'accepted' and capt.userId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($userId));
		$numRows = $queryRes->num_rows();
		if($numRows>0){
		    return 1;
		}
		return 0;
	}

	function checkApplicationStatus($userId){
		$this->initiateModel('read');
		$result = array();
		$queryCmd = "select id from CA_ProfileTable capt where capt.profileStatus NOT IN ('deleted','removed') and capt.userId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($userId));
		$numRows = $queryRes->num_rows();
		if($numRows>0){
		    $results = $queryRes->result_array();
		    return $results[0]['id'];
		}
		return 0;
}

	function getCADetailsForMarketingPage($startFrom,$rows,$instId){
		$this->initiateModel('read');
		$mainArr = array();$subQuery1='';$subQuery2='';
		if($instId>0){
			$subQuery1 = " and officialInstituteId=?";
			$subQuery2 = " and instituteId=?";
		}
		$courseIds = '';$otherCourseIds = '';
		$queryCmd = "select officialCourseId as courseId, userId, officialBadge, imageURL, displayName, officialInstituteLocId as insLocId from CA_ProfileTable where officialBadge!='None' and profileStatus='accepted' and isOfficial='Yes' $subQuery1";
		$query = $this->dbHandle->query($queryCmd,array($instId));
		$results = $query->result_array();
		$i=0;
		if(!empty($results) && is_array($results)) {
				foreach ($results as $key=>$value){
					$data[$i]['courseIds'] = $value['courseId'];
					$data[$i]['userIds'] = $value['userId'];
					$data[$i]['badges'] = $value['officialBadge'];
					$data[$i]['imageURL'] = $value['imageURL'];
					$data[$i]['displayName'] = $value['displayName'];
					$data[$i]['insLocId'] = $value['insLocId'];
					$i++;
				}
		}
		$queryCmd = "select courseId,userId, badge, imageURL,displayName, locationId as insLocId from CA_ProfileTable capt join CA_MainCourseMappingTable camcmt on (capt.id=camcmt.caId) where badge!='None' and status='live' and profileStatus='accepted' $subQuery2";
		$query = $this->dbHandle->query($queryCmd,array($instId));
                $results = $query->result_array();
                if(!empty($results)){
			foreach ($results as $key=>$value){
	                        $data[$i]['courseIds'] = $value['courseId'];
	                        $data[$i]['userIds'] = $value['userId'];
				$data[$i]['badges'] = $value['badge'];
				$data[$i]['imageURL'] = addingDomainNameToUrl(array('url' =>$value['imageURL'], 'domainName' => MEDIA_SERVER ));
				$data[$i]['displayName'] = $value['displayName'];
				$data[$i]['insLocId'] = $value['insLocId'];
				$i++;
			}
                }
		foreach($data as $key=>$value){
			$arr[] = $value['courseIds'];
			
		}
		if(empty($data)){
                        $final['totalCount'] = 0;
                        return $final;
                }
		$unique_array_courseIds = array_unique($arr);
		$courseIds = implode(',',$unique_array_courseIds);

		$queryCmd = "select caocd.courseId, capt.officialBadge as badge, capt.userId, capt.imageURL,capt.displayName, capt.officialInstituteLocId as insLocId from CA_ProfileTable capt join CA_OtherCourseDetails caocd on (capt.officialCourseId=caocd.mainCourseId) where caocd.status='live' and capt.profileStatus='live' and officialBadge='Official' and caocd.mainCourseId in ($courseIds) union (select caocd.courseId, camcpt.badge, capt.userId, capt.imageURL,capt.displayName,camcpt.locationId as insLocId from CA_ProfileTable capt join CA_MainCourseMappingTable camcpt on (capt.id=camcpt.caId) join CA_OtherCourseDetails caocd on (camcpt.id=caocd.mappingCAId) where caocd.status='live' and camcpt.status='live' and capt.profileStatus='accepted' and caocd.mainCourseId in ($courseIds))";
                $query = $this->dbHandle->query($queryCmd);
                $results = $query->result_array();
                if(!empty($results) && is_array($results)) {
                                foreach ($results as $key=>$value){
                                        $otherCourseIds .= $value['courseId'].',';
                                        $data[$i]['courseIds'] = $value['courseId'];
                                        $data[$i]['userIds'] = $value['userId'];
                                        $data[$i]['badges'] = $value['badge'];
                                        $data[$i]['imageURL'] = $value['imageURL'];
                                        $data[$i]['displayName'] = $value['displayName'];
                                        $data[$i]['insLocId'] = $value['insLocId'];
                                        $i++;
                                }
                }
		$totalCount = count($data);
		$j=0;
		$otherCourseIds = rtrim($otherCourseIds,',');
		$finalCourseIds  = rtrim($courseIds.','.$otherCourseIds,',');
		if($instId>0){
			$subQuery = " and mt.listingTypeId=?";
		}
                $queryCmd = "select SQL_CALC_FOUND_ROWS mt.listingTypeId, mt.userId, mt.parentId, count(qlr.courseId) as totalAnsCount ,mt.creationDate,qlr.courseId from messageTable mt inner join questions_listing_response qlr on (qlr.messageId = mt.parentId) where  mt.mainAnswerId=0 and mt.status in ('live','closed') $subQuery and mt.fromOthers='user'  and mt.listingType='institute' and mt.listingTypeId!=0 and mt.userId in (select userId from CA_ProfileTable where profileStatus='accepted' and officialCourseId in ($finalCourseIds) and profileStatus='accepted' union select userId from CA_ProfileTable capt where capt.id in (select caId from CA_MainCourseMappingTable camcmt where camcmt.courseId in ($finalCourseIds) and status='live') and capt.profileStatus='accepted' union select userId from CA_ProfileTable where userId in (select id from CA_MainCourseMappingTable where courseId in ((select mainCourseId from CA_OtherCourseDetails where courseId in ($finalCourseIds))) and status='live') and profileStatus='accepted') and qlr.status = 'live' and qlr.courseId in ($finalCourseIds) and unix_timestamp(now()) - unix_timestamp(mt.creationDate) <= 604800 group by qlr.courseId, mt.userId having ((select count(*) from CA_ProfileTable where userId=mt.userId and officialCourseId=qlr.courseId and profileStatus='accepted' and profileStatus='accepted')  > 0) or ((select count(*) from CA_MainCourseMappingTable where caId=(select id from CA_ProfileTable where userId=mt.userId and profileStatus='accepted') and courseId=qlr.courseId and status='live')  > 0 ) or (select count(*) from CA_OtherCourseDetails where mappingCAId in (select id from CA_MainCourseMappingTable where caId=(select id from CA_ProfileTable where userId=mt.userId and profileStatus='accepted') and courseId in (select mainCourseId from CA_OtherCourseDetails where courseId=qlr.courseId and status='live') and status='live') and courseId=qlr.courseId and status='live')>0 order by totalAnsCount desc";
		$query = $this->dbHandle->query($queryCmd,array($instId));
		$results = $query->result_array();
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $this->dbHandle->query($queryCmdTotal);
                $queryResults = $queryTotal->result();
                $totalRows = $queryResults[0]->totalRows;
		if(!empty($results) && is_array($results)) {
				foreach ($results as $key=>$value){
					$mainArr[]= $value;
				}
		}
		foreach($data as $key => $val) {
   		  if (is_array($val)) { 
			 foreach($mainArr as $key2 => $val2) {
			       if($val['userIds']==$val2['userId'] && $val['courseIds']==$val2['courseId'])
			       {
			         unset($data[$key]);
			         continue;
			       }
			}
		    }
		}
		
		$finalArr = array();
		$i=0;
		foreach($mainArr as $key=>$value){
                        $queryCmd = "select (select displayName from tuser where userid=?) as dName, displayName,officialBadge as badge,imageURL,officialInstituteLocId as insLocId from CA_ProfileTable where officialBadge!='None' and userId=? and profileStatus='accepted' and officialCourseId=? and isOfficial='Yes' union select (select displayName from tuser where userid=?) as dName,(select displayName from CA_ProfileTable where userId=? and profileStatus='accepted') displayName,badge,(select imageURL from CA_ProfileTable where userId=? and profileStatus='accepted') imageURL,locationId as insLocId from CA_MainCourseMappingTable where caId=(select id from CA_ProfileTable where userId=? and profileStatus='accepted') and status='live' and courseId=? union select (select displayName from tuser where userid=?) as dName,(select displayName from CA_ProfileTable where userId=? and profileStatus='accepted') displayName,badge,(select imageURL from CA_ProfileTable where userId=? and profileStatus='accepted') imageURL,(select officialInstituteLocId as insLocId from CA_ProfileTable where officialBadge!='None' and userId=? and profileStatus='accepted' and officialCourseId= caocd.mainCourseId and isOfficial='Yes' union select locationId  as insLocId from CA_MainCourseMappingTable where caId=(select id from CA_ProfileTable where userId=? and profileStatus='accepted') and status='live' and courseId=caocd.mainCourseId) as insLocId from CA_OtherCourseDetails caocd where caocd.mappingCAId in (select id from CA_MainCourseMappingTable camcmt where caId in (select id from CA_ProfileTable where userId=?) and courseId in (select mainCourseId from CA_OtherCourseDetails where courseId=? and status='live')) and caocd.status='live'";
                        $query = $this->dbHandle->query($queryCmd,array($value['userId'],$value['userId'],$value['courseId'],$value['userId'],$value['userId'],$value['userId'],$value['userId'],$value['courseId'],$value['userId'],$value['userId'],$value['userId'],$value['userId'],$value['userId'],$value['userId'],$value['courseId'],$value['courseId']));

			$res = $query->result_array();
			if(!empty($res) && is_array($res)) {
				foreach ($res as $k=>$v){
					$finalArr['caDetails'][$i]['badge']	  = $v['badge'];
					$finalArr['caDetails'][$i]['displayName']= $v['displayName'];
					$finalArr['caDetails'][$i]['listingTypeId']= $value['listingTypeId'];
					$finalArr['caDetails'][$i]['userId']= $value['userId'];
					$finalArr['caDetails'][$i]['totalAnsCount']= $value['totalAnsCount'];
					$finalArr['caDetails'][$i]['courseId']= $value['courseId'];
					$finalArr['caDetails'][$i]['imageURL']= $v['imageURL'];
					$finalArr['caDetails'][$i]['dName']= $v['dName'];
					$finalArr['caDetails'][$i]['insLocId']= $v['insLocId'];
					$i++;
				}
			}
		}
		foreach($data as $key=>$value){
			$queryCmd = "select displayName from tuser where userid=?";
			$query = $this->dbHandle->query($queryCmd ,array($value['userIds']));
                        $res = $query->result_array();
		        $row = $query->row();
                        $dName = $row->displayName;	
			$finalArr['caDetails'][$i]['badge']       = $value['badges'];
                        $finalArr['caDetails'][$i]['displayName']= $value['displayName'];
       	                $finalArr['caDetails'][$i]['listingTypeId']= '';
               	        $finalArr['caDetails'][$i]['userId']= $value['userIds'];
                       	$finalArr['caDetails'][$i]['totalAnsCount']= '0';
                        $finalArr['caDetails'][$i]['courseId']= $value['courseIds'];
       	                $finalArr['caDetails'][$i]['imageURL']= $value['imageURL'];
               	        $finalArr['caDetails'][$i]['dName']= $dName;
                       	$finalArr['caDetails'][$i]['insLocId']= $value['insLocId'];
                        $i++;
		}
		$finalArr["totalCount"] = $totalCount;
		return $finalArr;
}

	function checkIfUserIsCAOfCourse($userId,$courseId){
		$this->initiateModel('read');
		$queryCmd = "select count(*) as total from CA_ProfileTable where userId=? and officialCourseId=? and profileStatus=? and officialBadge!=?";
		$query = $this->dbHandle->query($queryCmd ,array($userId,$courseId,'accepted','None'));
		$row = $query->row();
		$flag = 'false';
		if($row->total > 0){
			$flag = 'true';
		}
		if($flag=='false'){
			$queryCmd = "select count(*) as total1 from CA_ProfileTable capt inner join CA_MainCourseMappingTable camcmt on (capt.id=camcmt.caId) where capt.userId=? and camcmt.courseId=? and capt.profileStatus=? and camcmt.badge!=? and camcmt.status=?";
	                $query = $this->dbHandle->query($queryCmd ,array($userId,$courseId,'accepted','None','live'));
			$row = $query->row();
			if($row->total1 > 0){
                        	$flag = 'true';
			}
			if($flag=='false'){
				$queryCmd = "select count(*) as total2 from CA_ProfileTable capt inner join CA_MainCourseMappingTable camcmt on (capt.id=camcmt.caId) inner join CA_OtherCourseDetails caocd on (camcmt.id=caocd.mappingCAId and caocd.mainCourseId=camcmt.courseId) where capt.userId=? and capt.profileStatus='accepted' and camcmt.status='live' and caocd.status='live' and caocd.courseId=?";
				$query = $this->dbHandle->query($queryCmd ,array($userId,$courseId));
	                        $row = $query->row();
        	                if($row->total2 > 0){
                	                $flag = 'true';
                        	}

				if($flag=='false'){
				$queryCmd = "select count(*) as total3 from CA_ProfileTable capt inner join CA_OtherCourseDetails caocd on (capt.id=caocd.mappingCAId) where capt.userId=? and capt.profileStatus='accepted'  and caocd.status='live' and caocd.courseId=? and capt.officialCourseId=caocd.mainCourseId and isOfficial='Yes'";
                                $query = $this->dbHandle->query($queryCmd ,array($userId,$courseId));
                                $row = $query->row();
                                if($row->total3 > 0){
                                        $flag = 'true';
                                }
				}
			}
		}
		
		return $flag;
	}
	
	function getCADetailsForWeeklyMailer($type,$subcatString){
		
		if($type=='marketingReport'){
			if(!empty($subcatString)){
				$category = "and cpt.category_id in (".$this->dbHandle->escape_str($subcatString).")";
			}
			$caName =",concat(tu.firstname,' ',tu.lastname) as caName";
			$joinWithtuser = ',tuser tu';
			$joinCondition = 'and tu.userid = capt.userId';
			$joinForCategory = ',categoryPageData cpt';
			$joinCategoryCondition = 'and camcmt.courseId = cpt.course_id and cpt.status = "live"';
			$joinCategoryFrom = ',cpt.category_id';
		}
		
		$sql = "select capt.userId $caName,camcmt.badge as badge, capt.creationDate,camcmt.courseId,camcmt.instituteId $joinCategoryFrom from CA_ProfileTable capt $joinWithtuser,  CA_MainCourseMappingTable camcmt $joinForCategory where capt.profileStatus='accepted' and camcmt.status='live' and camcmt.badge in ('CurrentStudent') and capt.id = camcmt.caId $joinCondition $joinCategoryCondition $category
			UNION
			select capt.userId $caName,caocd.badge as badge, caocd.creationDate, caocd.courseId,camcmt.instituteId $joinCategoryFrom from CA_ProfileTable capt $joinWithtuser, CA_MainCourseMappingTable camcmt, CA_OtherCourseDetails caocd $joinForCategory where capt.id=camcmt.caId and camcmt.id=caocd.mappingCAId and camcmt.status='live' and caocd.status='live' and camcmt.courseId=caocd.mainCourseId and caocd.badge in ('CurrentStudent') and camcmt.badge in ('CurrentStudent') $joinCondition $joinCategoryCondition $category";
		
		 $query = $this->dbHandle->query($sql);
                 $queryResults = $query->result_array();
		 $arr = array();
		 foreach($queryResults as $key=>$value){
			$arr[$value['courseId']][] = $value;
		 }
                 return $arr;
        }

        function questionAskedInInsHavingCR($duration='ALL'){
                $this->initiateModel('read');
                //calculate total question count for each campus rep from his joining date-time
                 $results = $this->getCADetailsForWeeklyMailer();
                 $totalQuestionCount = 0;
		 foreach($results as $rows){
			foreach($rows as $row){
	                        $creationTimeArr[$row['courseId']][] = strtotime($row['creationDate']);
			}
		 }
                 foreach($results as $key=>$value){
			$strFirstCRCreationDateTime = min($creationTimeArr[$key]);
			//$firstCRCreationDateTime = date('Y-m-d H:i:s',$strFirstCRCreationDateTime);
	                        if($duration=='WEEK'){
        	                        $weekTimeCheck = 'unix_timestamp(qlr.creationTime)>=? and unix_timestamp(now()) - unix_timestamp(qlr.creationTime) <= 604800 and ';
                	         }

	                        $sql = "select count(*) as total from messageTable mt join questions_listing_response qlr on (mt.msgId=qlr.messageId) where  qlr.courseId = ? and $weekTimeCheck unix_timestamp(mt.creationDate)>=? and mt.listingTypeId>0 and mt.listingType is not NULL and mt.status in ('live','closed')";
				
        	                $query = $this->dbHandle->query($sql,array($key,$strFirstCRCreationDateTime,$strFirstCRCreationDateTime));
                	        $rowRes = $query->row();
                        	$totalQuestionCount += $rowRes->total;
				
                 }
                 return $totalQuestionCount;
        }

	function questionAnsweredInInsHavingCR($userType='ALL',$duration='ALL',$digUp='NO'){
                $this->initiateModel('read');
                //calculate total question count for each campus rep from his joining date-time
                $results = $this->getCADetailsForWeeklyMailer();
		foreach($results as $rows){
                        foreach($rows as $row){
                                $creationTimeArr[$row['courseId']][] = strtotime($row['creationDate']);
                        }
                }
                $totalAnswerCount = 0;
		$caUserTypeCheckSubQuery = '';
		$caUserDigUpCheckSubQuery = '';
                foreach($results as $key=>$value){
			$strFirstCRCreationDateTime = min($creationTimeArr[$key]);
                        //$firstCRCreationDateTime = date('Y-m-d H:i:s',$strFirstCRCreationDateTime);
                        if($duration=='WEEK'){
                                $caCreationTimeSubQuery = ' unix_timestamp(mt.creationDate)>="'.$strFirstCRCreationDateTime.'" and unix_timestamp(now()) - unix_timestamp(mt.creationDate) <= 604800 and  unix_timestamp(qlr.creationTime)>="'.$strFirstCRCreationDateTime.'" and unix_timestamp(now()) - unix_timestamp(qlr.creationTime) <= 604800 and ';
                        }
                        if($duration=='ALL'){
                                $caCreationTimeSubQuery = ' unix_timestamp(mt.creationDate)>="'.$strFirstCRCreationDateTime.'"  and ';
                        }
			foreach($value as $k=>$v){
				if($userType=='CR'){
					$caUserTypeCheckSubQuery = ' and mt.userId="'.$v['userId'].'"';
				}
				if($digUp=='YES'){
					$caUserDigUpCheckSubQuery = ' and mt.digUp > 0';
				}
	                        $sql = "select mt.threadId from messageTable mt, questions_listing_response qlr where  $caCreationTimeSubQuery mt.threadId=qlr.messageId and  mt.status in ('live','closed') and mt.parentId>0 and mt.mainAnswerId=0 and mt.fromOthers='user' $caUserTypeCheckSubQuery and mt.listingTypeId>0 and mt.listingType='institute' and qlr.courseId=? and unix_timestamp(qlr.creationTime)>='".$strFirstCRCreationDateTime."' $caUserDigUpCheckSubQuery ";
        	                $query = $this->dbHandle->query($sql,array( $v['courseId']));
				$queryResults = $query->result_array();
				foreach($queryResults as $key=>$value){
		                         $threadId[] = $value['threadId'];
                 		}
                        	
			}
                }
		$questionAnsweredCount = count(array_unique($threadId));
                return $questionAnsweredCount;
        }


        function campusRepActivityDetails(){
                $data['totalQuestionCount'] = $this->questionAskedInInsHavingCR('ALL');
                $data['QuestionCountInWeek'] = $this->questionAskedInInsHavingCR('WEEK');
                $data['questionAnsweredByCRFullDuration'] = $this->questionAnsweredInInsHavingCR('CR','ALL');
                $data['questionAnsweredByCRWeekly'] = $this->questionAnsweredInInsHavingCR('CR','WEEK');
		$data['questionAnsweredByAllFullDuration'] = $this->questionAnsweredInInsHavingCR('ALL','ALL');
		$data['questionAnsweredByAllWeekly'] = $this->questionAnsweredInInsHavingCR('ALL','WEEK');
		$data['questionAnsweredByCRFullDurationWithDigUp'] = $this->questionAnsweredInInsHavingCR('CR','ALL','YES');
		$data['questionAnsweredByCRWeeklyWithDigUp'] = $this->questionAnsweredInInsHavingCR('CR','WEEK','YES');
		$data['questionAnsweredByAllFullDurationWithDigUp'] = $this->questionAnsweredInInsHavingCR('ALL','ALL','YES');
                $data['questionAnsweredByAllWeeklyWithDigUp'] = $this->questionAnsweredInInsHavingCR('ALL','WEEK','YES');
		return $data;
        }
	
	function getOutstandingAmountData()
	{
		$this->initiateModel('read');
		$sql = "select userId, sum(reward) as amount, 'walletTable' as fromWhere from CA_wallet where status = 'earned' group by userId
			union
			select userId, sum(paidAmount) as amount, 'payoutTable' as fromWhere from CA_payout group by userId";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		$CATotalAmount = array();
		$CAPaidAmount  = array();
		foreach($result as $value)
		{
			if($value['fromWhere'] == 'walletTable')
				$CATotalAmount[$value['userId']] = $value['amount'];
			else if($value['fromWhere'] == 'payoutTable')
				$CAPaidAmount[$value['userId']]  = $value['amount'];
		}
		return array('walletData'=>$CATotalAmount, 'payoutData'=>$CAPaidAmount);
	}
	function questionAskedInInsHavingCRmR(&$results){
                $this->initiateModel('read');
                //calculate total question count for each campus rep from his joining date-time
		foreach($results as $courseId=>$value){
			//$sql = "select count(*) as total from messageTable mt join questions_listing_response qlr on (mt.msgId=qlr.messageId) where qlr.creationTime>=? and qlr.courseId = ? and mt.creationDate>=? and qlr.status='live' and mt.listingTypeId>0 and mt.listingType is not NULL and mt.status in ('live','closed')";
			$sql = "select (select count(*) as total from questions_listing_response qlr where qlr.creationTime>='".date('Y-m-d', strtotime('-30 days'))."' and qlr.courseId = $courseId and qlr.status='live') as totalQuestionSinceDOJ, (select count(*) as total from questions_listing_response qlr where qlr.creationTime>='".date('Y-m-d', strtotime('-15 days'))."' and qlr.courseId = $courseId and qlr.status='live') as totalQuestionIn15Days, (select count(*) as total from questions_listing_response qlr where qlr.creationTime>='".date('Y-m-d', strtotime('-7 days'))."' and qlr.courseId = $courseId and qlr.status='live') as totalQuestionThisWeek";
			$query = $this->dbHandle->query($sql);
			$temp = $query->result_array();
			for($i=0;$i<count($value);$i++){
				$results[$courseId][$i]['totalQuestionThisWeek'] = $temp[0]['totalQuestionThisWeek'];
				$results[$courseId][$i]['totalQuestionIn15Days'] = $temp[0]['totalQuestionIn15Days'];
				$results[$courseId][$i]['totalQuestionSinceDOJ'] = $temp[0]['totalQuestionSinceDOJ'];
			}
		}
                return $results;
        }
	
	function questionAnsweredInInsHavingcrMR(&$results,$duration='ALL',$digUp='NO',$isFeatured= 'NO'){
                $this->initiateModel('read');
                //calculate total question count for each campus rep from his joining date-time
		foreach($results as $key=>$value){
			for($i=0;$i<count($value);$i++){	
				$totalAnswerCount = 0;
				$threadId=array();
				$courseId = $value[$i]['courseId'];
				$CRCreationDateTime = $value[$i]['creationDate'];
				if($duration=='WEEK'){
					$dateCheck = date('Y-m-d', strtotime('-7 days'));
				}
				else if($duration=='15DAY'){
					$dateCheck = date('Y-m-d', strtotime('-15 days'));
				}
				else {
					$dateCheck = date('Y-m-d', strtotime('-30 days'));
				}
				$sql = "select mt.threadId from messageTable mt, questions_listing_response qlr where mt.creationDate >= ? and mt.threadId=qlr.messageId and mt.status in ('live','closed') and mt.parentId>0 and mt.mainAnswerId=0 and mt.fromOthers='user' and mt.userId=? and mt.listingTypeId>0 and mt.listingType='institute' and qlr.courseId=? group by mt.threadId";
				
				$query = $this->dbHandle->query($sql,array($dateCheck,$value[$i]['userId'],$courseId));
				$queryResults = $query->result_array();
				foreach($queryResults as $key=>$v){
					 $threadId[] = $v['threadId'];
				}
				$totalAnswerCount = count(array_unique($threadId));
				if($duration=='WEEK')
					$results[$courseId][$i]['totalQuestionAnsweredThisWeek']=$totalAnswerCount;
				else if($duration=='15DAY')
					$results[$courseId][$i]['totalQuestionAnsweredIn15Days']=$totalAnswerCount;
				else
					$results[$courseId][$i]['totalQuestionsAnsweredsinceDOJ']=$totalAnswerCount;
			}
                }
		return $results;
        }
	
	function answersOnQuestionsAftrCampusRepJoined(&$results,$duration='ALL'){
                $this->initiateModel('read');
                //calculate total question count for each campus rep from his joining date-time
                foreach($results as $courseId=>$value){
			for($i=0;$i<count($value);$i++){	
				$totalQuestionsAnsweredCount = 0;
				$CRCreationDateTime = $value[$i]['creationDate'];
				if($duration=='WEEK'){
					$dateCheck = date('Y-m-d', strtotime('-7 days'));
				}
				else if($duration=='15DAY'){
					$dateCheck = date('Y-m-d', strtotime('-15 days'));
				}
				else{
					$dateCheck = date('Y-m-d', strtotime('-30 days'));
				}
				
				$sql="select count(*) as answersCount from (
				select mt.* ,
				    (select mmt.creationDate from messageTable  as mmt where mmt.msgId = mt.threadId and mmt.fromOthers = 'user' and mmt.status IN ('live','closed') and mmt.listingTypeId > 0 and mmt.listingType = 'institute') as crDate
				    from messageTable as mt inner join questions_listing_response as qlr on qlr.messageId = mt.threadId where mt.creationDate>=? and qlr.creationTime>=? and mt.creationDate>='$CRCreationDateTime' and qlr.creationTime>='$CRCreationDateTime' and mt.fromOthers='user' and mt.mainAnswerId = 0 and mt.listingType = 'institute'
				    and mt.status IN ('live','closed') and mt.userId=? and qlr.courseId = ? and qlr.status = 'live' ) as outQuery where outQuery.crDate >= ? and outQuery.crDate>='$CRCreationDateTime'";
				$query = $this->dbHandle->query($sql,array($dateCheck,$dateCheck,$value[$i]['userId'],$courseId,$dateCheck));
				$rowRes = $query->row();
				
                        	$totalQuestionsAnsweredCount = $rowRes->answersCount;
				if($duration=='WEEK')
				    $results[$courseId][$i]['answersOnQuestionsAskedLastWeek']=$totalQuestionsAnsweredCount;
				else if($duration=='15DAY')
				    $results[$courseId][$i]['answersOnQuestionsAskedIn15Days']=$totalQuestionsAnsweredCount;
				else
				    $results[$courseId][$i]['answersOnQuestionsAskedAfterDoj']=$totalQuestionsAnsweredCount;
			}	
                }
		return $results;
        }
	
	function unansweredQuestions(&$results,$duration='ALL'){
		$this->initiateModel('read');
                //calculate total question count for each campus rep from his joining date-time
	        foreach($results as $courseId=>$value){
			$sql="select (select count(*) as total from messageTable mt join questions_listing_response qlr on (mt.msgId=qlr.messageId) where qlr.creationTime>='".date('Y-m-d', strtotime('-30 days'))."' and qlr.courseId = $courseId and qlr.status='live' and mt.listingTypeId>0 and mt.listingType is not NULL and mt.status in ('live','closed') and mt.msgCount=0) as unansweredSinceDOJ, (select count(*) as total from messageTable mt join questions_listing_response qlr on (mt.msgId=qlr.messageId) where qlr.creationTime>='".date('Y-m-d', strtotime('-15 days'))."' and qlr.courseId = $courseId and qlr.status='live' and mt.listingTypeId>0 and mt.listingType is not NULL and mt.status in ('live','closed') and mt.msgCount=0) as unansweredIn15Days, (select count(*) as total from messageTable mt join questions_listing_response qlr on (mt.msgId=qlr.messageId) where qlr.creationTime>='".date('Y-m-d', strtotime('-7 days'))."' and qlr.courseId = $courseId and qlr.status='live' and mt.listingTypeId>0 and mt.listingType is not NULL and mt.status in ('live','closed') and mt.msgCount=0) as unansweredSinceWeek";
			$query = $this->dbHandle->query($sql);
			$temp = $query->result_array();
			for($i=0;$i<count($value);$i++){
				$results[$courseId][$i]['unansweredSinceWeek'] = $temp[0]['unansweredSinceWeek'];
				$results[$courseId][$i]['unansweredIn15Days']  = $temp[0]['unansweredIn15Days'];
				$results[$courseId][$i]['unansweredSinceDOJ']  = $temp[0]['unansweredSinceDOJ'];
			}
                }
		return $results;
	}
	
       function campusRepMarketingReport($subcatId){
                $this->initiateModel('read');
                error_log(':::CAMarketingReport:::IN MODEL:::'.'call to getCADetailsForWeeklyMailer function');
                $results = $this->getCADetailsForWeeklyMailer('marketingReport',$subcatId);
                error_log(':::CAMarketingReport:::IN MODEL:::'.'return from getCADetailsForWeeklyMailer function');
                error_log(':::CAMarketingReport:::IN MODEL'.'get questions asked data - start');
                $this->benchmark->mark('questions_start');
                $this->questionAskedInInsHavingCRmR($results);
                $this->benchmark->mark('questions_end');
                error_log(':::CAMarketingReport:::IN MODEL:::'.'get questions asked data - end');
                error_log(':::CAMarketingReport:::IN MODEL:::'.$this->benchmark->elapsed_time('questions_start', 'questions_end'));
                error_log(':::CAMarketingReport:::IN MODEL:::'.'get unanswered questions data - start');
                $this->benchmark->mark('unanswered_start');
                $this->unansweredQuestions($results);
                $this->benchmark->mark('unanswered_end');
                error_log(':::CAMarketingReport:::IN MODEL:::'.'get unanswered questions data - end');
                error_log(':::CAMarketingReport:::IN MODEL:::'.$this->benchmark->elapsed_time('unanswered_start', 'unanswered_end'));
                error_log(':::CAMarketingReport:::IN MODEL:::'.'get answered questions data - start');
                $this->benchmark->mark('answered_start');
                $this->answersOnQuestionsAftrCampusRepJoined($results, 'ALL');
                $this->answersOnQuestionsAftrCampusRepJoined($results,'15DAY');
                $this->answersOnQuestionsAftrCampusRepJoined($results,'WEEK');
                $this->benchmark->mark('answered_end');
                error_log(':::CAMarketingReport:::IN MODEL:::'.'get answered questions data - end');
                error_log(':::CAMarketingReport:::IN MODEL:::'.$this->benchmark->elapsed_time('answered_start', 'answered_end'));
                return $results;
        }
	
	function checkIfCampusRepForCourses($courseIds,$fromPage){
		$this->initiateModel('read');
		$commaSeperateCourseIds = implode(',',$courseIds);
		$selectQuery = '';
		if(empty($commaSeperateCourseIds) || $commaSeperateCourseIds=="undefined"){
			return array();
		}
		foreach($courseIds as $key=>$value){
			$resultSet[$value] = 'false';
		}
		if($fromPage == 'listing'){
			$selectQuery = ' ,capt.userId ';
			$fromQuery = ' CA_ProfileTable capt, ';
			$whereQuery = " and capt.id = camcmt.caId and capt.profileStatus='accepted'";
		}
		$sql = "select camcmt.courseId $selectQuery from CA_ProfileTable capt, CA_MainCourseMappingTable camcmt where capt.id = camcmt.caId and camcmt.badge='CurrentStudent' and capt.profileStatus='accepted' and camcmt.status='live' and camcmt.courseId in (?)";
		$query = $this->dbHandle->query($sql, array($courseIds));
		$res = $query->result_array();
		$discardCourseIds = array();
		foreach($res as $key=>$value){
			$resultSet[$value['courseId']] = 'true';
			if($fromPage == 'listing'){
				$resultSet['CA_Details'][$value['courseId']][] = $value['userId'];
			}
			$discardCourseIds[] = $value['courseId'];
		}
		
		$remainingCourseIds = array_diff($courseIds,$discardCourseIds);
		if(!empty($remainingCourseIds))
		{
			$commaSeperateRemainingCourseIds = implode(',',$remainingCourseIds);	
	
			$sql = "select caocd.courseId $selectQuery from $fromQuery CA_OtherCourseDetails caocd,CA_MainCourseMappingTable camcmt where caocd.courseId in (?) and caocd.status='live' and caocd.badge='CurrentStudent' and camcmt.id = caocd.mappingCAId and camcmt.status='live' $whereQuery";
			$query = $this->dbHandle->query($sql ,array($remainingCourseIds));
			$res = $query->result_array();
			foreach($res as $key=>$value){
				$resultSet[$value['courseId']] = 'true';
				if($fromPage == 'listing'){
					$resultSet['CA_Details'][$value['courseId']][] = $value['userId'];
				}
			}
		}
		return $resultSet;
	}
	
	function getMentorQuestions($progId)
	{
		$this->initiateModel('read');
		$result = array();
		$queryCmd = "select qid, questionText, type from CA_mentorship_questions where status = 'live' and programId = ?";
		$queryRes = $this->dbHandle->query($queryCmd, array($progId));
		return $queryRes->result_array();		
	}
	
	function getMentorAnswers($userId)
	{
		$this->initiateModel('read');
		$result = array();
		$queryCmd = "select id as answerId, quesId, answerText from CA_mentorship_answers where userId = ? and status = 'live'";
		$queryRes = $this->dbHandle->query($queryCmd, array($userId));
		$res = array();
		foreach($queryRes->result_array() as $key => $val)
		{
			$res[$val['quesId']] = $val;
		}
		return $res;		
	}
	
	function getMentorSampleAnswers($qids)
	{
		$this->initiateModel('read');
		$result = array();
		$queryCmd = "select id as sampleAnswerId, quesId, sampleAnswerText from CA_mentorship_sample_answers where status = 'live' and quesId in ($qids)";
		$queryRes = $this->dbHandle->query($queryCmd);
		$res = array();
		foreach($queryRes->result_array() as $key => $val)
		{
			$res[$val['quesId']][] = $val;
		}
		return $res;		
	}
	
	function saveMentorAnswers($userId, $ansArr)
	{
		$this->initiateModel('write');
		$dateCreated = date('Y-m-d H:i:s');
		foreach($ansArr as $qid => $ans)
		{
			$queryCmd = "insert into CA_mentorship_answers (quesId, userId, answerText, creationDate, status) values (?, ?, ?, '$dateCreated', 'live')";
			error_log("Section: CA Answer Query Tracking = ".$userId."\n".$ans."\n", 3, '/tmp/CAAnswerTracking.log');
			$this->dbHandle->query($queryCmd, array($qid, $userId, $ans));
		}
	}
	
	
	function campusRepReportQuesAskedInstBased($subcatString,$duration = 'month',$courses)
	{
		$this->initiateModel('read');
		$res = array();
		
		if($duration == 'month'){
			$creationDate = 'and  qlr.creationTime > (now() - INTERVAL 30 DAY)';
		}else{
			$creationDate = 'and  qlr.creationTime > (now() - INTERVAL 7 DAY)';
			
		}
		$queryCmd = "select group_concat(distinct qlr.messageId) as messageIds, count(distinct qlr.messageId) as questionAsked,qlr.instituteId,cpd.category_id from questions_listing_response qlr,categoryPageData cpd, messageTable mt where qlr.messageId = mt.msgId and mt.status in ('live','closed') and qlr.courseId = cpd.course_id and qlr.courseId in (".$this->dbHandle->escape_str($courses).") and cpd.category_id in (".$this->dbHandle->escape_str($subcatString).") and cpd.status = 'live' and qlr.status = 'live' $creationDate GROUP BY qlr.instituteId, cpd.category_id";
		$queryRes = $this->dbHandle->query($queryCmd);
		$res = array();
		foreach($queryRes->result_array() as $key => $val)
		{
			$res['result'][$val['instituteId']][$val['category_id']]['questionAsked'] = $val['questionAsked'];
			$res['result'][$val['instituteId']][$val['category_id']]['instituteId'] = $val['instituteId'];
			$res['result'][$val['instituteId']][$val['category_id']]['category_id'] = $val['category_id'];
			$res['messageIds'][$val['instituteId']][$val['category_id']] = $val['messageIds'];
		}
		return $res;				
	}

function getCourseIdsCampusRep($programId)
	{
		$programCheck = '';
		if($programId != '' || $programId != 0){
			$programCheck = " AND capt.programId =". $programId;
		}
		$this->initiateModel('read');
		$query = "select distinct camt.courseId from CA_ProfileTable capt,CA_MainCourseMappingTable camt where camt.caId = capt.id AND capt.profileStatus = 'accepted' AND camt.instituteId>0 AND camt.courseId>0 AND camt.locationId>0 AND camt.badge = 'CurrentStudent' $programCheck ";
		$coursequeryres = $this->dbHandle->query($query)->result_array();

		$queryOtherCourse = "select distinct caoc.courseId from CA_ProfileTable capt,CA_OtherCourseDetails caoc, CA_MainCourseMappingTable camt where caoc.mappingCAId = camt.CAId and camt.CAId = capt.id AND capt.profileStatus = 'accepted' AND caoc.courseId>0 AND caoc.badge = 'CurrentStudent' $programCheck ";

		$otherCourseQueryRes = $this->dbHandle->query($queryOtherCourse)->result_array();

		$count = 0;
		foreach ($coursequeryres as $key => $value) {
			if($count != 0){
				$courseIdString .= ',';
			}
			$courseIdString .= $value['courseId'];
			$count ++;
		}

		$check = !empty($courseIdString);
		$count = 0;
		foreach ($otherCourseQueryRes as $key => $value) {
			if($count != 0 || $check == 1){
				$courseIdString .= ',';
			}
			$courseIdString .= $value['courseId'];
			$count ++;
		}
		
		return $courseIdString;
	}

//gives details of removed(expired) campus rep (not deleted)
	function getPastCampusRepDetailsOfACourse($courseStr)
	{
		if(empty($courseStr)){
			return;
		}
		$this->initiateModel('read');

        $queryCmd = "SELECT distinct(p.userId),m.courseId FROM CA_MainCourseMappingTable m, CA_ProfileTable p WHERE m.courseId in (".$courseStr.") and m.badge='CurrentStudent' and m.caId = p.id and p.profileStatus = 'removed' ORDER BY p.creationDate desc";
    	$queryRes1 = $this->dbHandle->query($queryCmd);
    	foreach ($queryRes1->result_array() as $row){
			$finalArr[$row['userId']][$row['courseId']] = $row['courseId'];
		}

        $queryCmd = "SELECT distinct(p.userId),m.courseId FROM CA_OtherCourseDetails oc, CA_ProfileTable p , CA_MainCourseMappingTable as m WHERE oc.courseId in (".$courseStr.") and oc.badge='CurrentStudent'  and oc.mappingCAId = m.id and p.profileStatus = 'removed' and m.CAId = p.id  ORDER BY p.creationDate desc";
        $queryRes2 = $this->dbHandle->query($queryCmd);
        foreach ($queryRes2->result_array() as $row){
        	$finalArr[$row['userId']][$row['courseId']] = $row['courseId'];
        }
		return $finalArr;
	}	
	
	function campusRepReportQuesAnsweredInstBased($subcatString,$duration = 'month',$courses)
	{
		$this->initiateModel('read');
		
		$res= array();
		
		if($duration == 'month'){
			$creationDate = 'and  mt1.creationDate > (now() - INTERVAL 30 DAY)';
		}else{
			$creationDate = 'and  mt1.creationDate > (now() - INTERVAL 7 DAY)';
			
		}

		echo $queryCmd = "select qlr.instituteId,count(distinct qlr.messageId) as questionAnswered,cpd.category_id from messageTable mt,messageTable mt1,questions_listing_response qlr,categoryPageData cpd where mt.parentId = mt1.msgId and mt.mainAnswerId = 0 and mt1.mainAnswerId = -1 and mt1.msgId = qlr.messageId $creationDate and mt1.status in ('live','closed') and qlr.status = 'live' and mt.status in ('live','closed') and qlr.courseId = cpd.course_id and qlr.courseId in (".$this->dbHandle->escape_str($courses).") and cpd.category_id in (".$this->dbHandle->escape_str($subcatString).") and cpd.status = 'live' and mt.fromOthers = 'user' and mt1.fromothers = 'user' GROUP BY qlr.instituteId, cpd.category_id";die;
		$queryRes = $this->dbHandle->query($queryCmd);
		foreach($queryRes->result_array() as $key => $val)
		{
			$res[$val['instituteId']][$val['category_id']] = $val;
		}
		return $res;				
	}

//live/closed questions which have been answered by CR
	function campusRepReportQuesAnsweredByCRInstBased($messageIds,$campusRepIds,$subcatString)
	{
		if(empty($messageIds) || empty($campusRepIds) || empty($subcatString)){
			return;
		}
		$this->initiateModel('read');
		$queryCmd = "select distinct(mt.threadId), mt.status,mt.msgId,mt.userId,qlr.instituteId,cpd.category_id, qlr.courseId from messageTable mt, questions_listing_response qlr, categoryPageData cpd where mt.mainAnswerId = 0 and mt.threadId in ($messageIds) and mt.fromOthers = 'user' and mt.userId in ($campusRepIds) and qlr.courseId = cpd.course_id and qlr.messageId = mt.threadId and qlr.status = 'live' and cpd.status = 'live' and cpd.category_id in ($subcatString) ";
		$res = $this->dbHandle->query($queryCmd)->result_array();
		return $res;				
	}
	
	
	function  getDiffBtweenQuesAns($courseIds,$userId,$joiningDate,$duration = 'All'){
		
		$this->initiateModel('read');
		
		$res = array();
		
		if($duration=='Week'){
			$TimeCheck = ' and mt.creationDate>= (now() - INTERVAL 7 DAY) ';
		}
		else if($duration=='15Day'){
			$TimeCheck = ' and mt.creationDate>= (now() - INTERVAL 15 DAY) ';
		}
		else{
			$TimeCheck = ' and mt.creationDate >= (now() - INTERVAL 30 DAY) ';
		}
		
		$queryCmd = "SELECT mt.creationDate as quesDate ,mt1.creationDate as ansDate ,mt1.userId, (unix_timestamp(mt1.creationDate) - unix_timestamp(mt.creationDate)) as difference from messageTable mt,messageTable mt1,questions_listing_response qlr where mt.msgId = mt1.parentId and mt.mainAnswerId  = -1 and mt1.mainAnswerId = 0 and mt.status in ('live','closed') and mt1.status in ('live','closed') and qlr.messageId = mt.msgId and qlr.status = 'live' and qlr.courseId in (".$this->dbHandle->escape_str($courseIds).") and mt1.userId = ? $TimeCheck";
		$queryRes = $this->dbHandle->query($queryCmd,array($userId));
		$res = $queryRes->result_array();
		return $res;	
	}
	
	function getPendingAnswersInstituteWise($subcatString,$duration='month'){
		$this->initiateModel('read');
		
		$res = array();
		
		if($duration == 'month'){
			$creationDate = 'and  qlr.creationTime > (now() - INTERVAL 30 DAY)';
		}else{
			$creationDate = 'and  qlr.creationTime > (now() - INTERVAL 7 DAY)';
			
		}
		
		$queryCmd = "select count(distinct mt.msgId) as ansCount , qlr.instituteId,cpd.category_id from CA_AnswerStatusTable cast JOIN messageTable mt on (cast.answerId = mt.msgId) JOIN questions_listing_response qlr on (mt.threadId = qlr.messageId) JOIN categoryPageData cpd on (qlr.courseId = cpd.course_id) JOIN messageTable mt1 on (mt1.msgId = mt.threadId) where cast.status = 'draft' and mt1.status in ('live','closed') and qlr.status = 'live' and mt.mainAnswerId = 0 $creationDate and cpd.status = 'live' and cpd.category_id in (".$this->dbHandle->escape_str($subcatString).") GROUP BY qlr.instituteId, cpd.category_id";
		$queryRes = $this->dbHandle->query($queryCmd);
		
		foreach($queryRes->result_array() as $key => $val)
		{
			$res[$val['instituteId']][$val['category_id']]['ansCount'] = $val['ansCount'];
		}
		return $res;
		
	}


//gives pending moderation answer count for cr
	function getPendingModerationCountForCRs($subcatString,$duration='month',$answerIdsCR,$campusRepIds){
		$this->initiateModel('read');
		$answerIdCheck = '';
		$groupByClause = '';
		if(!empty($answerIdsCR)){
			$answerIdCheck = ' and mt.msgId in ('.$answerIdsCR.') ';
		}
		
		$res = array();
		
		if($duration == 'month'){
			$creationDate = 'and  qlr.creationTime > (now() - INTERVAL 30 DAY)';
		}elseif($duration == 'week'){
			$creationDate = 'and  qlr.creationTime > (now() - INTERVAL 7 DAY)';
		}elseif($duration == '15Days'){
			$creationDate = 'and  qlr.creationTime > (now() - INTERVAL 7 DAY)';
		}elseif($duration == 'all'){
			$creationDate = '';
		}

		if(!empty($campusRepIds)){
			$selectCRId = ', cast.userId as crId';
			$crIdCheck = ' and cast.userId in ('.$this->dbHandle->escape_str($campusRepIds).') ';
			$groupByClause = ', crId';
		}
		
		$queryCmd = "select count(distinct mt.msgId) as ansCount $selectCRId ,qlr.instituteId, cpd.category_id from CA_AnswerStatusTable cast, messageTable mt, questions_listing_response qlr, categoryPageData cpd where qlr.courseId = cpd.course_id and cast.answerId = mt.msgId and cast.status = 'draft' and mt.threadId = qlr.messageId $answerIdCheck $crIdCheck and qlr.status = 'live' $creationDate and cpd.status = 'live' and cpd.category_id in (".$this->dbHandle->escape_str($subcatString).") GROUP BY qlr.instituteId, cpd.category_id".$groupByClause;
		$queryRes = $this->dbHandle->query($queryCmd);
		foreach($queryRes->result_array() as $key => $val)
		{
			if(!empty($campusRepIds)){
				$res[$val['instituteId']][$val['category_id']][$val['crId']]['ansCount'] = $val['ansCount'];
			}
			else{
				$res[$val['instituteId']][$val['category_id']]['ansCount'] = $val['ansCount'];
			}
		}
		return $res;
		
	}
	
	/* Models for Controller : campusRepTATDataOnDateRange() - start */
	function getAllTATDataForCA($startDate, $endDate)
	{
		$begin = microtime(true);
		//echo '<br>'.$startDate.'---'.$endDate.'<br>';
		$this->initiateModel('read');
		//get all CA (caIds) and their course Ids
		$sql1 = "select cap.displayName, cap.userId as userId, camt.courseId, cap.creationDate from CA_ProfileTable cap join CA_MainCourseMappingTable camt on cap.id = camt.caId join categoryPageData cpd on camt.courseId = cpd.course_id where cap.profileStatus='accepted' and camt.badge='CurrentStudent' and camt.status='live' and cpd.category_id = '23' and cpd.status = 'live'
			UNION
			select cap.displayName, cap.userId as userId, caot.courseId, cap.creationDate from CA_ProfileTable cap join CA_MainCourseMappingTable camt on cap.id=camt.caId join CA_OtherCourseDetails caot on camt.id = caot.mappingCAId join categoryPageData cpd on camt.courseId = cpd.course_id where camt.courseId=caot.mainCourseId and camt.status='live' and camt.badge='CurrentStudent' and caot.status='live' and caot.badge='CurrentStudent' and cpd.category_id = '23' and cpd.status = 'live'";
		$caData = $this->dbHandle->query($sql1)->result_array();
		//_p($caData);die;
		$allCAIds = array();
		$allCourseIds = array();
		$caCoursemap = array();
		foreach($caData as $val)
		{
			$allCAIds[] = $val['userId'];
			$allCourseIds[] = $val['courseId'];
			$caCoursemap[$val['userId']][] = $val['courseId'];
			$caNames[$val['userId']] = array('displayName'=>$val['displayName'], 'dateOfJoining'=>$val['creationDate']);
		}
		$allCAIds = array_unique($allCAIds);
		//_p($caNames);die;
		//_p($caCoursemap);die;
		//_p($allCAIds);die;
		//_p($allCourseIds);die;
		//get all questions asked on this courses in the given date range
		if(!empty($allCourseIds))//all course ids having CA
		{
			$sql3 = "select qlr.courseId, qlr.messageId, qlr.creationTime from questions_listing_response qlr where qlr.courseId in (".implode(',', $allCourseIds).") and qlr.status='live' and qlr.creationTime >= ? and qlr.creationTime <= ?";
			$query = $this->dbHandle->query($sql3, array($startDate, $endDate));
			$qdata = $query->result_array();
			//_p($qdata);die;
			
			$allQuesInRange = array();
			$quesCourseMap = array();
			$courseQuesMap = array();
			foreach($qdata as $ques)
			{
				$allQuesInRange[] = $ques['messageId'];
				$quesCourseMap[$ques['messageId']] = array('courseId'=>$ques['courseId'], 'creationTime'=>$ques['creationTime']);
				$courseQuesMap[$ques['courseId']][] = array('messageId'=>$ques['messageId'], 'creationTime'=>$ques['creationTime']);
			}
			//_p($quesCourseMap);die;
			//_p($courseQuesMap);die;
			
			if(!empty($allQuesInRange))
			{
				foreach($allCAIds as $userId)
				{
					//get all user-wise answers for the questions posted on courses having CA
					$sql4 = "select msgId as ansId, parentId, creationDate, userId from messageTable mt where mt.parentId in (".implode(',', $allQuesInRange).") and fromOthers='user' and mt.status in ('live', 'closed') and userId = $userId";
					$query = $this->dbHandle->query($sql4);
					$tempAnsData = $query->result_array();
					foreach($tempAnsData as $val)
					{
						//$val['courseId'] = $quesCourseMap[$val['parentId']]['courseId'];
						$adata[$userId][$quesCourseMap[$val['parentId']]['courseId']][] = $val;
					}
				}
				//_p($adata);die;
			}
			$timeDiff = array();
			foreach($caCoursemap as $userId => $courseArr)
			{
				$timeDiff[$userId]['ansCount'] = 0;
				$timeDiff[$userId]['queCount'] = 0;
				foreach($courseArr as $courseId)
				{
					$timeDiff[$userId]['ansCount']      += count($adata[$userId][$courseId]);
					$timeDiff[$userId]['queCount']      += count($courseQuesMap[$courseId]);
					$timeDiff[$userId]['caName']        = $caNames[$userId]['displayName'];
					$timeDiff[$userId]['dateOfJoining'] = $caNames[$userId]['dateOfJoining'];
					if(is_array($adata[$userId][$courseId]) && count($adata[$userId][$courseId]) > 0)
					{
						foreach($adata[$userId][$courseId] as $ans)
						{
							$timeDiff[$userId]['responseTime'][] = (strtotime($ans['creationDate']) - strtotime($quesCourseMap[$ans['parentId']]['creationTime']))/(60*60);
						}
					}
				}
			}
			//_p($timeDiff);die;
		}
		//echo 'Query time = ';
		$end = microtime(true);
		//echo $end - $begin;
		//echo ' microseconds';
		return $timeDiff;
	}
	/* Models for Controller : campusRepTATDataOnDateRange() - end */

	function getAllCCPrograms(){

	$this->initiateModel('read');
	$query = "SELECT programId, programName FROM campusConnectProgram where status ='live'";
	$programData = $this->dbHandle->query($query)->result_array();
	return $programData;
}

	function getCCProgramDetails($progId){
	$this->initiateModel('read');
	$query = "SELECT entityId, entityType, programName, redirectURL FROM campusConnectProgram where programId = ? and status ='live'";
	$query = $this->dbHandle->query($query,array($progId));
	$results = $query->result_array();	
	return $results;	
	}
}
