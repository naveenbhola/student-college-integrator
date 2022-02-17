<?php
/* 
    Model for database related operations related to message board.
    Following is the example this model can be used in the server controllers.
    $this->load->model('QnAModel');
    $this->QnAModel->getTotalQnACountForCriteria('149,12,11,3,8,5,10',$dbHandle); 	 
*/

class QnAModel extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('AnA');
	}

	private function initiateModel($operation='read'){
		$appId = 1;	
		//$this->load->library('messageboardconfig');
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig($appID,$dbConfig);
		//$this->dbHandle = $this->load->database($dbConfig,TRUE);

		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	        $this->dbHandle = $this->getWriteHandle();
		}
	}
		
    function getTotalQnACountForCriteria($dbHandleSent,$paramArray){
		if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		
		$csvCatList = $paramArray['categoryId'];
		$csvCountryList = $paramArray['countryId'];
		$testPrep = isset($paramArray['testprep'])?$paramArray['testprep']:0;
		if($testPrep == 0)
		{
			$groupBy = 'group by m2.categoryId';
			$toSelect = 'm2.categoryId id';
			if($paramArray['groupBy'] != 'category')
			{
				$groupBy = 'group by m3.countryId';
				$toSelect = 'm3.countryId id';
			}
			$CATEGORYCONDITION = "m2.categoryId in(".$csvCatList.")";
			if($csvCatList == "1"){
				$CATEGORYCONDITION = 'm2.categoryId in (select boardId from categoryBoardTable where parentId = 1)';
	        	}
			if($csvCatList != '')
			{
				if($csvCountryList != ''){
					$queryCmd = "select ".$toSelect." ,count(*) as qnacount from messageTable m1 , messageCategoryTable m2 , messageCountryTable m3 where ".$CATEGORYCONDITION." and m3.countryId in (".$csvCountryList.") and m1.threadId = m2.threadId and m1.threadId = m3.threadId and m2.threadId = m3.threadId and m1.parentId = 0 and fromOthers = 'user' and status in ('live','closed') ".$groupBy."";
				}else{
					$groupBy = 'group by m2.categoryId';
					$toSelect = 'm2.categoryId id';
					$queryCmd = "select ".$toSelect." ,count(*) as qnacount from messageTable m1 , messageCategoryTable m2 where ".$CATEGORYCONDITION." and m1.threadId = m2.threadId and m1.parentId = 0 and fromOthers = 'user' and status in ('live','closed') ".$groupBy."";
				}		
			}else{
				$groupBy = 'group by m3.countryId';
	            		$toSelect = 'm3.countryId id';	
	            		if($csvCountryList != '1'){
	                		$queryCmd = "select ".$toSelect." ,count(*) as qnacount from messageTable m1 ,messageCountryTable m3 where m3.countryId in (".$csvCountryList.") and m1.threadId = m3.threadId and m1.parentId = 0 and fromOthers = 'user' and status in ('live','closed') ".$groupBy."";
	            		}else{
	                		$queryCmd = "select ".$toSelect." ,count(*) as qnacount from messageTable m1 ,messageCountryTable m3 where m1.threadId = m3.threadId and m1.parentId = 0 and fromOthers = 'user' and status in ('live','closed') ".$groupBy."";
	            		}        
		        }
		  }else{
			if(($paramArray['examId'] != '') && ($paramArray['examId'] != 0)){
                $queryCmd = " select blogId id, count(distinct messageTable.threadId) as qnacount from blogTable , messageTable ,  messageCategoryTable , categoryBoardTable  where  ( messageCategoryTable.categoryId = blogTable.boardId OR (categoryBoardTable.boardId =  blogTable.boardId and messageCategoryTable.categoryId = categoryBoardTable.parentId and categoryBoardTable.parentId != 1) ) and   messageTable.threadId = messageCategoryTable.threadId and messageTable.status  in ('live','closed') and messageTable.fromOthers  = 'user' and blogTable.parentId = 0 and blogTable.blogId = ".$paramArray['examId']." and blogTable.blogType='exam' group by blogTable.blogId";
			}else{
                $queryCmd = " select blogId id, count(distinct messageTable.threadId) as qnacount from blogTable , messageTable ,  messageCategoryTable , categoryBoardTable  where  ( messageCategoryTable.categoryId = blogTable.boardId OR (categoryBoardTable.boardId =  blogTable.boardId and messageCategoryTable.categoryId = categoryBoardTable.parentId and categoryBoardTable.parentId != 1) ) and   messageTable.threadId = messageCategoryTable.threadId and messageTable.status  in ('live','closed') and blogTable.status != 'draft' and messageTable.fromOthers  = 'user' and blogTable.parentId = 0 and blogTable.blogType='exam' group by blogTable.blogId";

		  	}
		}	
		$queryRes = $this->dbHandle->query($queryCmd);
		$response = array();
	        foreach ($queryRes->result_array() as $row){
	            $response[$row['id']] = $row['qnacount'];
	        }
	        return $response;
	}
	
	function getExpertUsers($dbHandleSent,$start,$count)
	{
		if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		
		//$query = "select SQL_CALC_FOUND_ROWS count(*) count, t.* from messageTable m ,tuser t where t.userId=m.userId and m.status in ('live','closed') and m.fromOthers='user' and m.parentId!=0 and t.usergroup = 'experts' group by m.userId LIMIT $start,$count";
		$query = "select *,(select level from userPointLevel where minLimit<=Res.userPointValue limit 1) level,(select count(*) from messageTable M1 where M1.threadId = M1.parentId and M1.fromOthers='user' and M1.status not in('deleted','abused') and M1.userId = Res.userid) answerCount,(select count(*) from messageTable M2, messageTableBestAnsMap mbp where M2.threadId = M2.parentId and M2.fromOthers='user' and M2.status not in('deleted','abused') and M2.userId = Res.userid and M2.threadId = mbp.threadId) bestAnswerCount from (select count(*) count, t.displayname,t.userid,t.lastlogintime,t.avtarimageurl,t.email,upv.userPointValue,ted.EducationLevel,ted.Options from messageTable m ,userPointSystem upv,tuser t LEFT JOIN tEducationLevel ted  ON (t.educationlevel = ted.EducationId) where t.userId=m.userId and upv.userId = t.userid and t.usergroup = 'experts' and m.status in ('live','closed') and m.fromOthers='user' and m.parentId!=0 group by m.userId order by count DESC LIMIT ".$start.",".$count.") Res ";
		$queryRes = $this->dbHandle->query($query);
		$tempArr = array();
		foreach ($queryRes->result_array() as $row){
			array_push($tempArr,array($row,'struct'));
		}
 		$query = 'SELECT FOUND_ROWS() as totalRows';
		$queryRes = $this->dbHandle->query($query);
		$numOfExpertUsers = 0;
		foreach ($queryRes->result() as $rowT) {
				$numOfExpertUsers  = $rowT->totalRows;
		}
		$response = array('userdata' => $tempArr,'numOfExpertUsers' => $numOfExpertUsers);
		return $response;
	}

	function reportEntityAsAbuse($dbHandleSent,$abuseArray,$abuseCountArray='',$deleteArray='')
	{
		if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}

		$productId = $abuseArray['productId'];
		$product = $abuseArray['product'];
		$userId = $abuseArray['userId'];
		$reasonId = $abuseArray['userId'];
		$other_reason = isset($abuseArray['other_reason'])?$abuseArray['other_reason']:'';

		if(is_array($abuseCountArray)){
			$abuseCountColumnTable = $abuseCountArray['abuseCountColumnTable'];
			$abuseCountColumn = $abuseCountArray['abuseCountColumn'];
		}

		if(is_array($abuseCountArray) && is_array($abuseCountArray)){
			$deleteTable = $deleteArray['deleteTable'];
			$deleteColumn = $deleteArray['deleteColumn'];
			$deleteStatus = $deleteArray['deleteStatus'];
			$maxAbuseCount = $deleteArray['maxAbuseCount'];
		}
		
		$query = "select *,(select level from userPointLevel where minLimit<=Res.userPointValue limit 1) level,(select count(*) from messageTable M1 where M1.threadId = M1.parentId and M1.fromOthers='user' and M1.status not in('deleted') and M1.userId = Res.userid) answerCount,(select count(*) from messageTable M2, messageTableBestAnsMap mbp where M2.threadId = M2.parentId and M2.fromOthers='user' and M2.status not in('deleted') and M2.userId = Res.userid and M2.threadId = mbp.threadId) bestAnswerCount from (select count(*) count, t.displayname,t.userid,t.lastlogintime,t.avtarimageurl,t.email,upv.userPointValue,ted.EducationLevel,ted.Options from messageTable m ,userPointSystem upv,tuser t LEFT JOIN tEducationLevel ted  ON (t.educationlevel = ted.EducationId) where t.userId=m.userId and upv.userId = t.userid and t.usergroup = 'experts' and m.status in ('live','closed') and m.fromOthers='user' and m.parentId!=0 group by m.userId order by count DESC LIMIT ".$start.",".$count.") Res ";
		$queryRes = $this->dbHandle->query($query);
		$tempArr = array();
		foreach ($queryRes->result_array() as $row){
			array_push($tempArr,array($row,'struct'));
		}
 		$query = 'SELECT FOUND_ROWS() as totalRows';
		$queryRes = $this->dbHandle->query($query);
		$numOfExpertUsers = 0;
		foreach ($queryRes->result() as $rowT) {
				$numOfExpertUsers  = $rowT->totalRows;
		}
		$response = array('userdata' => $tempArr,'numOfExpertUsers' => $numOfExpertUsers);
		return $response;
	}

	function getPopularAnswersForQuestions($dbHandleSent,$csvThreadIds,$formatWithXmlRpc=true,$giveAnswerCount=false)
	{
		if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		$answerCountSubQuery = "";
		if($giveAnswerCount){
			$answerCountSubQuery = " , (select count(*) from messageTable M2 where M2.threadId = m1.threadId and M2.threadId = M2.parentId and M2.fromOthers = 'user' and M2.status not in ('deleted','abused')) noOfAnswer";
		}
		//$queryCmd = "select *,SUBSTRING_INDEX(tempAnswer,'#',1)AnswerId,SUBSTRING_INDEX(tempTypeTitle,'#',1) typeOfAnswer, SUBSTRING_INDEX(tempTypeTitle,'#',-1) listingTitle from (select *,SUBSTRING_INDEX(tempAnswer,'#',1)AnswerId,SUBSTRING_INDEX(tempAnswer,'#',-2) tempTypeTitle from  (select ifnull((select CONCAT(m1.msgId,'#','institutebest','#',lmain.listing_title) from listings_main lmain where m1.userId = lmain.username and lmain.listing_type_id IN (select listingTypeId from messageTable mT where mT.threadId in (".$csvThreadIds.") and lmain.status IN ('live') and mT.parentId = 0) and lmain.listing_type IN (select listingType from messageTable mTb where mTb.threadId in (".$csvThreadIds.") and mTb.parentId = 0)),ifnull((select CONCAT(bestAnsId,'#','best','#','') from messageTableBestAnsMap mbm where m1.threadId = mbm.threadId),ifnull((select CONCAT(m2.msgId,'#','digged','#','') from messageTable m2 where (m2.digUp - m2.digDown) > 0 and m2.parentId = m2.threadId and m1.threadId = m2.threadId and m2.fromOthers = 'user' and m2.status not in ('deleted','abused') order by (m2.digUp - m2.digDown) desc,m2.digUp desc limit 1),(select CONCAT(msgId,'#','highPoint','#','') from messageTable m4 LEFT JOIN userpointsystembymodule upv  ON (m4.userId = upv.userId and upv.modulename='AnA') where m4.parentId = m4.threadId and m4.threadId = m1.threadId and m4.fromOthers = 'user' and m4.status not in ('deleted','abused') order by upv.userpointvaluebymodule desc ,CHAR_LENGTH(msgTxt) desc limit 1)))) tempAnswer,m1.path, m1.msgId,m1.threadId,m1.msgTxt,m1.msgCount,m1.viewCount,m1.digUp,m1.digDown,t1.displayname $answerCountSubQuery ,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1,tuser t1 INNER JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA')  where m1.parentId = m1.threadId and m1.fromOthers = 'user' and m1.status not in('deleted','abused')  and m1.threadId in (".$csvThreadIds.") and m1.userId = t1.userid) Res) Res1 where Res1.AnswerId = Res1.msgId";
		$queryCmd = "select *,SUBSTRING_INDEX(tempAnswer,'#',1)AnswerId,SUBSTRING_INDEX(tempTypeTitle,'#',1) typeOfAnswer, SUBSTRING_INDEX(tempTypeTitle,'#',-1) listingTitle from (select *,SUBSTRING_INDEX(tempAnswer,'#',1)AnswerId,SUBSTRING_INDEX(tempAnswer,'#',-2) tempTypeTitle from  (select ifnull((select CONCAT(m1.msgId,'#','institutebest','#',lmain.listing_title) from listings_main lmain where m1.userId = lmain.username and lmain.listing_type_id = (select listingTypeId from messageTable mT where mT.msgId = m1.threadId and lmain.status IN ('live')) and lmain.listing_type IN (select listingType from messageTable mTb where mTb.msgId = m1.threadId)) ,(select CONCAT(msgId,'#','highPoint','#','') from messageTable m4, userpointsystembymodule upv where m4.userId = upv.userId and m4.parentId = m4.threadId and m4.threadId = m1.threadId and m4.fromOthers = 'user' and m4.status not in ('deleted') and upv.moduleName='AnA' order by upv.userpointvaluebymodule desc ,CHAR_LENGTH(msgTxt) desc limit 1)) tempAnswer,m1.path, m1.msgId,m1.threadId,m1.msgTxt,m1.msgCount,m1.viewCount,m1.digUp,m1.digDown,t1.userid,t1.displayname $answerCountSubQuery ,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1,tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId  and upv.modulename = 'AnA') where m1.parentId = m1.threadId and m1.fromOthers = 'user' and m1.status not in('deleted')  and m1.threadId in (?) and m1.userId = t1.userid) Res) Res1 where Res1.AnswerId = Res1.msgId";
		$csvThreadIds = explode(',', $csvThreadIds);
		$Result = $this->dbHandle->query($queryCmd,array($csvThreadIds));
		$csvThreadIds = '';
		$resultArray = array();
		foreach ($Result->result_array() as $row){
			if($formatWithXmlRpc){
				$resultArray[$row['threadId']] = array($row,'struct');
			}else{
				$resultArray[$row['threadId']] = $row;
			}
		}
		return $resultArray;
	}

	function getDataForGlobalAnAWidget($userId,$categoryId,$countryId,$start,$count,$useRandom=0){
		$this->load->library('message_board_client');
		$appId = 1;
		$msgbrdClient = new Message_board_client();
		$resultData =  $msgbrdClient->getEditorialQuestionsForHomePages($appId,$categoryId,$countryId,$userId,$start,$count,$useRandom);
		$response = is_array($resultData[0])?$resultData[0]:array();
		return $response;
	}

	function relatedDataForQuestion($productName='',$productId=0,$relatedProduct='',$relatedJsonData='-1'){
		$this->load->library(array('relatedClient','message_board_client'));
		if($relatedJsonData=='-1'){
			$appId = 1;
			$RelatedClient = new RelatedClient();
			$Result = $RelatedClient->getrelatedData($appId,$productName,$productId,$relatedProduct);
			$relatedJsonData = is_array($Result[0])?$Result[0]['relatedData']:'';
		}
		$tempResult = json_decode($relatedJsonData,true);
		$relatedQuestions = array();
		$noOfResults = 0;
		if(is_array($tempResult)){
			$relatedQuestions = $tempResult['resultList'];
			$noOfResults = $tempResult['numOfResults'];
		}
		
		$threadIdsArray = array();
		foreach($relatedQuestions as $temp){
			array_push($threadIdsArray,$temp['typeId']);
		}
			
		$csvThreadIds = implode(",",$threadIdsArray);
		$msgbrdClient = new Message_board_client();
		if(count($threadIdsArray) > 0){
			$csvThreadIds = implode(",",$threadIdsArray);
			$threadIdsInfo = $msgbrdClient->getInfoForThreads(1,$csvThreadIds,array('answerCount'=>1,'flagForAnswer'=>1,'getPopularAnswers' => 1),$userId);
		}
		
		$answersInfo = isset($threadIdsInfo['popularAnswers'])?$threadIdsInfo['popularAnswers']:array();
		$questionsInfo = isset($threadIdsInfo['Results'])?$threadIdsInfo['Results']:array();
		if(is_array($relatedQuestions)){
		    for($i=0;$i<count($relatedQuestions);$i++){
			    $relatedQuestions[$i]['noOfResults'] = $noOfResults;
			    $relatedQuestions[$i]['answerCount'] = 0;
			    if(array_key_exists($relatedQuestions[$i]['typeId'],$questionsInfo)){
				    $relatedQuestions[$i]['answerCount'] = $questionsInfo[$relatedQuestions[$i]['typeId']]['answerCount'];
			    }
			    $relatedQuestions[$i]['popularAnswerDetails'] = array();
			    if(array_key_exists($relatedQuestions[$i]['typeId'],$answersInfo)){
				    $relatedQuestions[$i]['popularAnswerDetails'] = $answersInfo[$relatedQuestions[$i]['typeId']];
			    }
		    }
		}
		$relatedQuestions = is_array($relatedQuestions)?$relatedQuestions:array();
// 		echo "<pre>"; print_r($relatedQuestions); echo "</pre>";
		return $relatedQuestions;
	}

	function getTreeForMultipleThreads($dbHandleSent,$appId,$csvThreadIds,$userId,$formatWithXmlRpc=true){
		if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		$resultArray = array();
		if($csvThreadIds!=''){
			$csvThreadIds = explode(',', $csvThreadIds);
		$queryCmd = "select m1.*,t1.* from messageTable m1 , tuser t1 where m1.threadId in (?) and m1.status not in ('deleted','abused') and m1.userId = t1.userid";
		$Result = $this->dbHandle->query($queryCmd,array($csvThreadIds));
		$previousThreadId = 0;
		foreach ($Result->result_array() as $row){
			if($formatWithXmlRpc){
				if(!array_key_exists($row['threadId'],$resultArray)){
					$resultArray[$row['threadId']] = array();
					if($previousThreadId != 0){
						$resultArray[$previousThreadId][0] = $tempArr;
						$resultArray[$previousThreadId][1] = 'struct';
					}
					$previousThreadId = $row['threadId'];
					$tempArr = array();
				}
				$tempArr[$row['msgId']] = array($row,'struct');
			}else{
				if(!array_key_exists($row['threadId'],$resultArray)){
					$resultArray[$row['threadId']] = array();
				}
				$resultArray[$row['threadId']][$row['msgId']] = $row;
			}
		}
		}
		return $resultArray;
	}

 function getQuestionsForEnterpriseUser($dbHandleSent,$appId,$userId,$startOffset,$countOffset){
             if(!is_resource($dbHandleSent)){
                     $this->initiateModel();
             }else{
                     $this->dbHandle = $dbHandleSent;
             }

			 //Modified by Ankur on 8 March to remove the slow query
			 $msgArray=array();
			 $threadIds="";
			 $msgArray['questions'] = array();
			 $listingIds = '';
		         $totalRows = 0;
			 $queryCmd = "select lm.listing_type_id, lm.listing_type from listings_main lm where lm.status = 'live' and lm.username = ? and lm.listing_type in ('institute','university_national')";
  	         $Result = $this->dbHandle->query($queryCmd, array($userId));
             $numrows = $Result->num_rows();
			 $finalQuery = '';
			 $subQuery = '';

			 $this->load->library('ContentRecommendation/AnARecommendationLib');
			 foreach($Result->result_array() as $row){
			      $listingIds[] = $row['listing_type_id'];
             }
             if($numrows != ''){

             	//$listingIds = array(47223,50595);
            	  $questionDetails = $this->anarecommendationlib->getAllQuestionsBasedOnTag($listingIds, $countOffset, $startOffset);
            	  foreach($questionDetails['topContent'] as $key=>$detail){
            	  	$questionArray[] = $detail['msgId'];
            	  }

            	  $questionIds = implode(',',$questionArray);

            	  if(!empty($questionArray)){
					      if($subQuery=='')
						  $subQuery .= " (select m1.*,t1.displayname,t1.email,t1.mobile,t1.firstname,t1.lastname,t1.usercreationDate,m1.msgCount ansCount from messageTable m1, tuser t1 where m1.userId = t1.userid and m1.fromOthers='user' and m1.parentId = 0 and m1.status in ('live','closed') and m1.msgId in ($questionIds)) ";

						 if($subQuery!=''){
						      $finalQuery = "select SQL_CALC_FOUND_ROWS * from ( $subQuery ) info order by info.msgId desc ";
						      $Result = $this->dbHandle->query($finalQuery);
						      $totalRows = $questionDetails['numFound'];
						      foreach($Result->result_array() as $row){
							      $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
							      $locationArr = explode('###',$questionDetails['topContent'][$row['threadId']]['locationOfInstitute']);
							      $row['instituteCityName'] = $locationArr[0];
							      $row['instituteCoutryName'] = $locationArr[1];
							      $row['userid'] = $row['userId'];
							      $row['listing_title'] = $questionDetails['topContent'][$row['threadId']]['listing_title'];
							      $row['course_title'] = $questionDetails['topContent'][$row['threadId']]['course_title'];
							      $msgArray['questions'][$row['threadId']] = $row;
							      $threadIds .= (trim($threadIds) == "")?$row['threadId']:(",".$row['threadId']);
							}
						  }
				    }	
		          
		            $msgArray['answers'] = array();
		            if($threadIds!=''){
		             $queryCmd = "select m1.* from messageTable m1 where m1.parentId = m1.threadId and m1.status in ('live','closed') and m1.fromOthers = 'user' and m1.threadId in (".$threadIds.") and m1.userId = ?";
		             $Result = $this->dbHandle->query($queryCmd, array($userId));
		             foreach($Result->result_array() as $row){
		                     $msgArray['answers'][$row['threadId']] = $row;
		             }
		            }

		     		$msgArray['courseIds'] = array();
		            if($threadIds!=''){
		             $queryCmd = "select courseId, messageId as threadId from questions_listing_response where messageId IN (".$threadIds.") AND status='live'";
		             $Result = $this->dbHandle->query($queryCmd);
		             foreach($Result->result_array() as $row){
		                     $msgArray['courseIds'][$row['threadId']] = $row;
		             }
		            }

		            $msgArray['totalRows'] = $totalRows;
		       }
	        return base64_encode(gzcompress(json_encode($msgArray)));
  	    }

		function getLevelAndVCardStausForUser($dbHandleSent,$mainAnswerUserIdCsv,$getLevel = true, $getVCardStatus = true, $isCompress = false)
		{
			if(!is_object($dbHandleSent)){
					$this->initiateModel();
			}else{
					$this->dbHandle = $dbHandleSent;
			}
			//Can not proceed with empty user ids
			if(!$mainAnswerUserIdCsv || $mainAnswerUserIdCsv=='')
			{
				return array();
			}
			
			//Get the Level and VCard status of the question and answers
			$vcardStatusQuery = '';
			$userLevelQuery = ''; $userLevelQueryP = '';
			$mainAnswerUserIdCsv = str_replace(",,",",",$mainAnswerUserIdCsv);
			$mainAnswerUserIdCsv = trim($mainAnswerUserIdCsv, ",");

			$mainAnswerUserIdCsv = explode(',', $mainAnswerUserIdCsv);

			if($getVCardStatus)
              $vcardStatusQuery = " ,ifnull((select 1 from expertOnboardTable vci where vci.userId = t1.userid and vci.status = 'Live'),0) vcardStatus ";
			if($getLevel){
			  $userLevelQuery = ",ifnull((select ups.levelName from userpointsystembymodule ups where ups.modulename = 'AnA' and ups.userId = t1.userid LIMIT 1),'Beginner-Level 1') ownerLevel";
			  $userLevelQueryP = ",ifnull((select ups.levelName from userpointsystembymodule ups where ups.modulename = 'Participate' and ups.userId = t1.userid LIMIT 1),'Beginner-Level 1') ownerLevelP";
			}
			$queryCmd = "select t1.userid $vcardStatusQuery $userLevelQuery $userLevelQueryP from tuser t1 where t1.userid in (?)";

			$Result = $this->dbHandle->query($queryCmd,array($mainAnswerUserIdCsv));
			$msgArrayLevelVcard = array();
			$i=0;
			foreach($Result->result_array() as $row){
				//Modified for Shiksha performance task on 8 March
				if($isCompress){
				  $msgArrayLevelVcard[$i] = $row;
				}
				else{
				  array_push($msgArrayLevelVcard ,array($row,'struct'));
				}
				$i++;
			}
			//End Modifications
			return $msgArrayLevelVcard;
		}

		function getCategoryCountry($dbHandleSent,$threadIdcvs,$getCategory = true, $getCountry = true, $isCompress = false)
		{
			if(!is_object($dbHandleSent)){
					$this->initiateModel();
			}else{
					$this->dbHandle = $dbHandleSent;
			}
			$categoryQuery = '';
			$countryQuery = '';
			if($getCategory)
			  $categoryQuery = ",ifnull((select cbt.name from categoryBoardTable cbt,messageCategoryTable mct where cbt.boardId = mct.categoryId and mct.threadId = m1.msgId and cbt.parentId = 1 LIMIT 1),'Miscellaneous') category, ifnull((select cbt.boardId from categoryBoardTable cbt,messageCategoryTable mct where cbt.boardId = mct.categoryId and mct.threadId = m1.msgId and cbt.parentId = 1 LIMIT 1),1) categoryId";
			if($getCountry)
			  $countryQuery = " ,ifnull((select ct.name from countryTable ct,messageCountryTable mct where ct.countryId = mct.countryId and mct.threadId = m1.msgId and mct.countryId != 1 LIMIT 1),'') country, ifnull((select ct.countryId from countryTable ct,messageCountryTable mct where ct.countryId = mct.countryId and mct.threadId = m1.msgId and mct.countryId != 1 LIMIT 1),0) countryId";
			$msgArrayCatCountry = array();
			if($threadIdcvs!=''){
				$threadIdcvs = explode(',', $threadIdcvs);
				$queryCmd = "select m1.msgId $categoryQuery $countryQuery from messageTable m1 where m1.msgId in (?)";
				$Result = $this->dbHandle->query($queryCmd,array($threadIdcvs));
				$i=0;
				foreach($Result->result_array() as $row){
					//Modified for Shiksha performance task on 8 March
					if($isCompress){
					  $msgArrayCatCountry[$i] = $row;
					}
					else{
					  array_push($msgArrayCatCountry ,array($row,'struct'));
					}
					$i++;
				}
			}
			return $msgArrayCatCountry;
		}

		/****************
		This function will be used to check if the User is an AnA Expert or not.
		It will also return the signature of the user if he has regsitered for a VCard
		****************/
        function checkIfAnAExpert($dbHandleSent,$userIds,$getSignature=false)
        {
            if(!is_object($dbHandleSent)){
                $this->initiateModel();
            }else{
                $this->dbHandle = $dbHandleSent;
            }

			//Can not proceed with empty user ids
			if(!$userIds || $userIds=='')
			{
				return array();
			}
			
			if(strpos($userIds,',')!==false){	//If this is a csv of users
					$vcardStatusQuery = " ,ifnull((select 1 from userpointsystembymodule where userId = t1.userid and moduleName='AnA' and userpointvaluebymodule>=1000),0) expertStatus ";
					if($getSignature){
						$signatureQuery = " ,ifnull((select signature from expertOnboardTable where userId = t1.userid and status='Live'),'') signature ,ifnull((select designation from expertOnboardTable where userId = t1.userid and status='Live'),'') designation ,ifnull((select instituteName from expertOnboardTable where userId = t1.userid and status='Live'),'') instituteName ,ifnull((select highestQualification from expertOnboardTable where userId = t1.userid and status='Live'),'') highestQualification ,ifnull((select aboutCompany from expertOnboardTable where userId = t1.userid and status='Live'),'') aboutCompany ";
					}
					$queryCmd = "select t1.userid $vcardStatusQuery $signatureQuery from tuser t1 where t1.userid in (?)";
					$userIds = explode(',', $userIds);
					$Result = $this->dbHandle->query($queryCmd,array($userIds));
					$msgExpertArray = array();
					$i=0;
					foreach($Result->result_array() as $row){
						$msgExpertArray[$i] = $row;
						$i++;
					}
					return $msgExpertArray;
			}
			else{
					$cardStatus = '0';
					$queryCmd = "select userpointvaluebymodule as points from userpointsystembymodule where userId = ? and moduleName='AnA'";
					$query = $this->dbHandle->query($queryCmd, array($userIds) );
					foreach ($query->result() as $row) {
						if($row->points && $row->points!='' && $row->points >= 1000)
							$cardStatus = '1';
					}
					return $cardStatus;
			}
        }

	function storeSuggestedInstitutes($suggestions,$answerId){
                $this->initiateModel('write');

		$queryCmd = "UPDATE messageTableSuggestions set status = 'deleted' where entityId = ? ";
		$queryUpdate = $this->dbHandle->query($queryCmd,array($answerId));

		$suggestionArray = explode(',',$suggestions);
		foreach ($suggestionArray as $suggestion){
			if($suggestion!=''){
				$insertData = array(
					'entityId' => $answerId,
					'suggestionId' => $suggestion
				);
				$this->dbHandle->insert('messageTableSuggestions',$insertData);				
			}
		}
	}

	/* Not in use anymore */
	
	function getSuggestedInstitutes($answerIds,$isCompress=true,$dbHandleSent=''){
		if(!is_object($dbHandleSent)){
		    $this->initiateModel();
		}else{
		    $this->dbHandle = $dbHandleSent;
		}		
		$suggestionArray = array();
		$i=0;
		if($answerIds!=''){
			$answerIds = preg_replace('#[,]+#', ',', $answerIds);
			$answerIds = trim($answerIds,',');
			//$queryCmd = "select entityId as answerId, suggestionId from messageTableSuggestions where entityId in ( $answerIds ) and status = 'live'";
            //Get only Live institutes as suggestions
            $queryCmd = "select entityId as answerId, suggestionId from messageTableSuggestions m, listings_main l where m.entityId in (?) and m.status = 'live' and l.status='live' and l.listing_type = 'institute' and l.listing_type_id = m.suggestionId";
            if(!is_array($answerIds))
            {
            	$answerIds = explode(',', $answerIds);	
            }
			$Result = $this->dbHandle->query($queryCmd,array($answerIds));
			foreach($Result->result_array() as $row){
				if($isCompress){
				  $suggestionArray[$i] = $row;
				}
				else{
				  array_push($suggestionArray ,array($row,'struct'));
				}				
				$i++;
			}
		}
		return $suggestionArray;		
	}
	/*	
	function getOnlyRelatedQuestions($questionIds,$instituteId){
		$this->initiateModel();
		$returningQuestionString = '';
		if($questionIds!=''){
			$questionIds = preg_replace('#[,]+#', ',', $questionIds);
			$questionIds = trim($questionIds,',');

			if(!is_array($questionIds))
			{
				$questionIds = explode(',', $questionIds);
			}
			$queryCmd = " select msgId from messageTable where msgId IN (?) and listingTypeId!=? and status IN ('live','closed') ";
			$Result = $this->dbHandle->query($queryCmd, array($questionIds,$instituteId) );
			foreach($Result->result_array() as $row){
				$returningQuestionString .= ($returningQuestionString=='')?$row['msgId']:','.$row['msgId'];
			}
		}
		return $returningQuestionString;				
	}
	*/

	function getInstituteRelatedQuestionDetails($appId,$questionIds,$numberOfAnswerPerQuestion){
		$this->initiateModel();
		$returningArray = array();
		$userIdList = '';
        $questionIds = preg_replace('#[,]+#', ',', $questionIds);
        $questionIds = trim($questionIds,',');
		if($questionIds!=''){

			//Execute this query to fetch the question, all answers and all comments + Rating (digUp-digDown) on answers + Reputation of the users
			$queryCmd = "select m1.msgId,m1.userId,m1.mainAnswerId,m1.parentId,m1.msgTxt,m1.threadId,(select points from tuserReputationPoint tp where m1.userId=tp.userId and m1.mainAnswerId=0) reputation,digUp-digDown as rating,creationDate from messageTable m1 where threadId IN ($questionIds) and status IN ('live','closed') and fromOthers='user' order by msgId";
			$Result = $this->dbHandle->query($queryCmd);
			foreach($Result->result_array() as $row){
				$questionId = $row['threadId'];
				$userIdList .= ($userIdList=='')?$row['userId']:','.$row['userId'];
				if($row['parentId']==0){	//In case of a question
					$returningArray['questionList'] .= ($returningArray['questionList']=='')?$questionId:','.$questionId;
					$returningArray[$questionId]['questionId'] = $row['msgId'];
					$returningArray[$questionId]['questionUserId'] = $row['userId'];
					$returningArray[$questionId]['questionText'] = $row['msgTxt'];
					$returningArray[$questionId]['creationDate'] = $row['creationDate'];
			                $returningArray[$questionId]['url'] = getSeoUrl($row['msgId'],'question',$row['msgTxt'],'','',$row['creationDate']);
				}
				else if($row['mainAnswerId']==0){	//In case of an Answer
					$answerId = $row['msgId'];
					//$returningArray[$questionId]['answerList'] .= ($returningArray[$questionId]['answerList']=='')?$answerId:','.$answerId;
					$returningArray[$questionId]['Answers'][$answerId]['answerId'] = $row['msgId'];
					$returningArray[$questionId]['Answers'][$answerId]['answerUserId'] = $row['userId'];
					$returningArray[$questionId]['Answers'][$answerId]['answerText'] = $row['msgTxt'];
					$returningArray[$questionId]['Answers'][$answerId]['bestAnsFlag'] = 0;
					$returningArray[$questionId]['Answers'][$answerId]['rating'] = ($row['rating'])?$row['rating']:0;
					$returningArray[$questionId]['Answers'][$answerId]['reputation'] = ($row['reputation'])?$row['reputation']:10;
					$returningArray[$questionId]['Answers'][$answerId]['creationDate'] = $row['creationDate'];
				}
				else if($row['mainAnswerId']>0){	//In case of an Comment
					$mainAnswerId = $row['mainAnswerId'];
					$commentId = $row['msgId'];
					//$returningArray[$questionId]['Answers'][$mainAnswerId]['Comments'][$commentId]['commentId'] = $row['msgId'];
					$returningArray[$questionId]['Answers'][$mainAnswerId]['Comments'][$commentId]['commentText'] = $row['msgTxt'];
					$returningArray[$questionId]['Answers'][$mainAnswerId]['Comments'][$commentId]['commentUserId'] = $row['userId'];
					$returningArray[$questionId]['Answers'][$mainAnswerId]['Comments'][$commentId]['creationDate'] = $row['creationDate'];
				}
				
			}
			
			//Now, find if there is any Best answer on the question.
			$queryCmdBA = "select bestAnsId,threadId from messageTableBestAnsMap where threadId IN ($questionIds)";
			$ResultBA = $this->dbHandle->query($queryCmdBA);
			foreach($ResultBA->result_array() as $row){
				$questionId = $row['threadId'];
				$answerId = $row['bestAnsId'];
				$returningArray[$questionId]['Answers'][$answerId]['bestAnsFlag'] = 1;
				$returningArray[$questionId]['sortedAnswerList'] .= ($returningArray[$questionId]['sortedAnswerList']=='')?$answerId:','.$answerId;
			}
			
			//Now, sort the answers based on Best Answer Flag, Reputation, Rating and CreationDate
			$questionArray = explode(',',$returningArray['questionList']);
			foreach($questionArray as $questionId){
				$sort = array();
				$answerArrayForSorting = $returningArray[$questionId]['Answers'];
				foreach($answerArrayForSorting as $k=>$v) {
				    $sort['reputation'][$k] = $v['reputation'];
				    $sort['rating'][$k] = $v['rating'];
				    $sort['creationDate'][$k] = $v['creationDate'];
				}
				# sort the array now
				array_multisort($sort['reputation'], SORT_DESC,$sort['rating'], SORT_DESC,$sort['creationDate'], SORT_DESC, $answerArrayForSorting);
				$i=0;
				foreach($answerArrayForSorting as $answerRow){
					if($i<$numberOfAnswerPerQuestion){
						$returningArray[$questionId]['sortedAnswerList'] .= ($returningArray[$questionId]['sortedAnswerList']=='')?$answerRow['answerId']:','.$answerRow['answerId'];
						$i++;
					}
				}
			}
			
			//Now, fetch the Username, display image for the question, answer and comments
            if($userIdList!=''){
    			$queryCmdUser = "select DISTINCT userid, displayname, avtarimageurl from tuser where userid IN ($userIdList)";
	    		$ResultUser = $this->dbHandle->query($queryCmdUser);
		    	foreach($ResultUser->result_array() as $row){
			    	$userId = $row['userid'];
				    $returningArray['userDetails'][$userId]['displayname'] = $row['displayname'];
    				$returningArray['userDetails'][$userId]['avtarimageurl'] = $row['avtarimageurl'];
	    		}
			}

            if($returningArray['questionList']!=''){
    			//Find the country for all the questions
	    		$queryCmdCC = "select countryTable.name countryName, m1.threadId from countryTable, messageCountryTable m1 where countryTable.countryId=m1.countryId and m1.threadId IN (".$returningArray['questionList'].") and m1.countryId>1";
		    	$ResultCC = $this->dbHandle->query($queryCmdCC);
			    foreach($ResultCC->result_array() as $row){
				    $questionId = $row['threadId'];
    				$returningArray[$questionId]['country'] = $row['countryName'];
	    		}

		    	//Find the category for all the questions
			    $queryCmdCC = "select c1.name categoryName, m1.threadId from categoryBoardTable c1, messageCategoryTable m1 where c1.boardId=m1.categoryId and m1.threadId IN (".$returningArray['questionList'].") and m1.categoryId>1 and m1.categoryId<20";
    			$ResultCC = $this->dbHandle->query($queryCmdCC);
	    		foreach($ResultCC->result_array() as $row){
		    		$questionId = $row['threadId'];
			    	$returningArray[$questionId]['category'] = $row['categoryName'];
    			}
		    }	
		}
		return $returningArray;
	}
	
	function getWallDataForListingsByNewAlgorithm($appId=1,$userId,$start,$count,$categoryId,$countryId=1,$threadIdCsv='1',$lastTimeStamp,$questionIds,$type,$instituteId){
		$this->initiateModel();
		$returningArray = array();
		$userIdList = '';

		if(!empty($questionIds)) {
		    $questionIds = implode(",",$questionIds);
		}
		if(!empty($categoryId)) {
		    $categoryId = implode(",",$categoryId);
		}else {
		    $categoryId = 1;
		}
		
		$lastTimeStamp = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
		$lastTimeStamp = date('Y-m-d',$lastTimeStamp);
		$result = '';
		$date = date("Y-m-d");
		$fromForCatpegoryAndCountry = '';
		$conditionForCategory = '';
		$conditionForCountry = '';
	    
	    $queryParams = array();
		$getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=m1.msgId and ral.userId=?),0) reportedAbuse ";
		if($userId > 0)
		{
		      $alreadyAnsweredQuery = " ,IFNULL( (SELECT 1  FROM messageTable m5 WHERE m5.parentId = m1.threadId AND m5.userId = ? AND m5.mainAnswerId =0 AND m1.mainAnswerId = -1 LIMIT 1), 0 ) alreadyAnswered";
		}
		else
		  $alreadyAnsweredQuery="";
	       

		$queryCmd = '';
		
		if(!empty($instituteId)) {
			if( empty($questionIds) && ($type=='INSTITUTE') ){  //when there are no quesstions and type is institute
			    $categoryId = 1;
			    $queryCmd = "select m1.creationDate ,m1.status,m1.userId,m1.msgTxt,m1.msgId,b1.threadId as bestAns,m1.mainAnswerId,m1.threadId,m1.digUp-m1.digDown as score,m1.msgCount $alreadyAnsweredQuery from messageTable m1 LEFT JOIN messageTableBestAnsMap b1 ON b1.threadId = m1.threadId,messageCategoryTable m2, messageCountryTable m3 where m1.threadId = m2.threadId and m2.categoryId in ($categoryId) and m1.threadId = m3.threadId and m3.countryId in (?) and  m1.fromOthers='user' and m1.status in ('live','closed') and m1.creationDate <=  ? and m1.listingTypeId = ? and (m1.mainAnswerId<=0) and m1.msgId NOT IN (?)  order by m1.creationDate desc";
			    if($userId > 0)
			    {
			    	$queryParams[] = $userId;
			    }
			    $queryParams[] = $countryId;
			    $queryParams[] = $lastTimeStamp;
			    $queryParams[] = $instituteId;
			    $queryParams[] = explode(',', $threadIdCsv);
			}
			else if( !empty($questionIds) && ($type=='RELATED') ){ // when there are questions and type is related
			    $questionIds = trim($questionIds,',');
			    $categoryId = 1;
			    $queryCmd = "select m1.creationDate ,m1.status,m1.userId,m1.msgTxt,m1.msgId,b1.threadId as bestAns,m1.mainAnswerId,m1.threadId,m1.digUp-m1.digDown as score,m1.msgCount $alreadyAnsweredQuery from messageTable m1 LEFT JOIN messageTableBestAnsMap b1 ON b1.threadId = m1.threadId,messageCategoryTable m2, messageCountryTable m3 where m1.threadId = m2.threadId and m2.categoryId in ($categoryId) and m1.threadId = m3.threadId and m3.countryId in (?) and  m1.fromOthers='user' and m1.status in ('live','closed') and m1.creationDate <=  ? and m1.listingTypeId!= ? and (m1.mainAnswerId<=0) and m1.msgId NOT IN (?) and m1.threadId IN (?) order by m1.creationDate desc";
			    if($userId > 0)
			    {
			    	$queryParams[] = $userId;
			    }
			    $queryParams[] = $countryId;
			    $queryParams[] = $lastTimeStamp;
			    $queryParams[] = $instituteId;
			    $queryParams[] = explode(',', $threadIdCsv);
			    $queryParams[] = explode(',', $questionIds);
			}else{ //when there are questions and type is institute
		    	if(!empty($questionIds)) {
		    		$categoryId = 1 ;
		    		$questionIds = trim($questionIds,',');
			    	$queryCmd = "select m1.creationDate ,m1.status,m1.userId,m1.msgTxt,m1.msgId,b1.threadId as bestAns,m1.mainAnswerId,m1.threadId,m1.digUp-m1.digDown as score,m1.msgCount $alreadyAnsweredQuery from messageTable m1 LEFT JOIN messageTableBestAnsMap b1 ON b1.threadId = m1.threadId,messageCategoryTable m2, messageCountryTable m3 where m1.threadId = m2.threadId and m2.categoryId in ($categoryId) and m1.threadId = m3.threadId and m3.countryId in (?) and  m1.fromOthers='user' and m1.status in ('live','closed') and m1.creationDate <=  ? and m1.listingTypeId = ? and (m1.mainAnswerId<=0) and m1.msgId NOT IN (?) and m1.threadId IN (?) order by m1.creationDate desc";
			    	if($userId > 0)
				    {
				    	$queryParams[] = $userId;
				    }
				    $queryParams[] = $countryId;
				    $queryParams[] = $lastTimeStamp;
				    $queryParams[] = $instituteId;
				    $queryParams[] = explode(',', $threadIdCsv);
				    $queryParams[] = explode(',', $questionIds);
		    	}
			}
		} 
		
		if(!empty($queryCmd)) {
			$result = $this->dbHandle->query($queryCmd,$queryParams);
		}
		$queryParams = array(); 
		
		$returnArr = array();
		$wallData = array();
		$mainQuestionUserIdCsv = '';
		$qnaList='';
		$detailReturnArr = array();
		$questionList='';
		
		if(!empty($result)) {
			foreach($result->result_array() as $row){
				$questArr = array();
				$qnaList .= ($qnaList == '')?$row['msgId']:','.$row['msgId'];
				if($row['mainAnswerId'] == -1){
				    $mainQuestionUserIdCsv .= ($mainQuestionUserIdCsv == '')?$row['userId']:','.$row['userId'];
				    $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
				    $questionList .= ($questionList == '')?$row['msgId']:','.$row['msgId'];
				    $detailReturnArr[$row['threadId']]['question'] = $row; 
				   
				}
				$questArr[$row['msgId']]=$row;
				if($row['mainAnswerId'] == 0){
					$detailReturnArr[$row['threadId']]['Answer'][$row['msgId']]= $row;
				}
			}
			$questArr[$row['msgId']]=$row;
			if($row['mainAnswerId'] == 0){
				$detailReturnArr[$row['threadId']]['Answer'][$row['msgId']]= $row;
			}
		}
		
		$QuesUserDetailArr = array();
		if($qnaList == '')$qnaList =1;
		$queryCmd = "select m1.*,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId IN (".$qnaList.") order by m1.creationDate desc";
				$query = $this->dbHandle->query($queryCmd,array($userId));$i=0;
				foreach ($query->result_array() as $rowTemp) {
				    $QuesUserDetailArr[$i] = $rowTemp;
				    $i++;
				}         
		$msgArrayCatCountry = array();     
		if($qnaList!=''){
			 $msgArrayCatCountry = $this->getCategoryCountry($dbHandle,$qnaList,true,true,true);
		}
		$msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
		$returnArr[0] = $detailReturnArr;      
		$mainArr[0]['results'] = $returnArr;
		$mainArr[0]['userInfo'] = $QuesUserDetailArr;
		$mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
		$wallData=$this->sortQuestionsOnTheirScoreListingAnAtab($mainArr);
		return $wallData;
	} 
	
	
	function sortQuestionsOnTheirScoreListingAnAtab($wallData){
		$keys=array_keys($wallData[0]['results'][0]);
		$wallArray=$wallData[0]['results'][0];
		$quesInfo=array();
		for($i=0;$i<count($keys);$i++){
			$score=0;
			$questionId=$keys[$i];$j=0;
			if($wallArray[$questionId]['question']['mainAnswerId']==-1){
			    $quesInfo[$questionId]['msgId']=$wallArray[$questionId]['question']['msgId'];
			    $quesInfo[$questionId]['alreadyAnswered']=$wallArray[$questionId]['question']['alreadyAnswered'];
			    $quesInfo[$questionId]['creationDate']=$wallArray[$questionId]['question']['creationDate'];
			    $quesInfo[$questionId]['msgCount']=$wallArray[$questionId]['question']['msgCount'];
			    $quesInfo[$questionId]['url']=$wallArray[$questionId]['question']['url'];
			    $isbestAnswer=($wallArray[$questionId]['question']['bestAns']==null)?0:1;
			
				$now = time(); // or your date as well
				$creationDate =strtotime($quesInfo[$questionId]['creationDate']);
				$datediff = $now - $creationDate;
				foreach($wallArray[$questionId]['Answer'] as $ansId => $ansValue){
					 if($j==0){$ansIdKey=$ansId; $j++;}
					if($ansValue['mainAnswerId']==0){
					    $score+=$ansValue['score'];
					}
					
				 }
			$quesInfo[$questionId]['AmsgId']=$ansIdKey; 
			$finalScore=(0.5*$isbestAnswer+0.5*$score)/$datediff;
			$quesInfo[$questionId]['finalScore']=$finalScore;
			}
		}
			
		$questionsWithoutAnswer=array();
		$questionsWithAnswer=array();
	    
		foreach($quesInfo as $quesId =>$info){
			if($quesInfo[$quesId]['msgCount']==0)
				$questionsWithoutAnswer[$quesId]=$info;
			else
				$questionsWithAnswer[$quesId]=$info;
		}
			
		if(!function_exists("cmp")) {
		    function cmp($a,$b){
			 if ($a['finalScore'] == $b['finalScore']){ 
			  if($a['msgId'] > $b['msgId'])
			      return -1;
			  else
			      return 1;
			  }
			  elseif ($a['finalScore'] > $b['finalScore'])   // $a has higher score, move $b down array
			     return -1;
			  else   // $a has lower score, move $b up array             
			      return 1;
		      }
		}
		uasort($questionsWithAnswer, "cmp");
		$orderedQuestionIds=array_merge($questionsWithAnswer,$questionsWithoutAnswer);
	    
		$mainInfoArray = array();
		for($i=0;$i<count($wallData[0]['userInfo']);$i++){
		      $threadIdTemp = $wallData[0]['userInfo'][$i]['msgId'];
		      $mainInfoArray[$threadIdTemp] = $wallData[0]['userInfo'][$i];
		}
		
		$wallData[0]['results']=array();	
		for($i=0;$i<count($orderedQuestionIds);$i++){
			$msgId=$orderedQuestionIds[$i]['msgId'];
			if($msgId!=''){
			    $wallData[0]['results'][$i][0]=$mainInfoArray[$msgId];
			    $wallData[0]['results'][$i]['sortingTime']=$mainInfoArray[$msgId]['creationDate'];
			    $wallData[0]['results'][$i][0]['alreadyAnswered']=$orderedQuestionIds[$i]['alreadyAnswered'];
			    $wallData[0]['results'][$i][0]['url']=$orderedQuestionIds[$i]['url'];
			    if($mainInfoArray[$msgId]['msgCount']>0){
				$wallData[0]['results'][$i]['type']='answer';
				$wallData[0]['results'][$i][1]=$mainInfoArray[$orderedQuestionIds[$i]['AmsgId']];
			    }
			else
			    $wallData[0]['results'][$i]['type']='question';
			}
		} 
		return $wallData; 
	}
		
	//by pragya
	function getResponseQnADetailsForListingId($userIds,$courseIds){
		$this->initiateModel();
		$userIds= trim($userIds,',');
		$courseIds= trim($courseIds,',');
		$msgArr = array();
		$queryCmd = "SELECT mt.msgId, mt.msgTxt, mt.threadId, DATE_FORMAT(mt.creationDate,'%D %b %Y') as creationDate,qr.courseId,qr.userId,mt.status FROM messageTable mt, questions_listing_response qr WHERE mt.threadId = qr.messageId and mt.mainAnswerId = -1 and mt.fromOthers='user' and mt.status IN ('live','closed','abused','deleted') and qr.userId in (?) and qr.courseId in (?)";
		if(!is_array($userIds))
		{
			$userIds = explode(',', $userIds);	
		}
		if(!is_array($courseIds))
		{
			$courseIds = explode(',', $courseIds);	
		}
		$query = $this->dbHandle->query($queryCmd,array($userIds, $courseIds));
		   
		foreach($query->result_array() as $row) {
			$msgId =$row['msgId'];
			$msgArr[intval($row['userId'])][$row['msgId']]['qmsgTxt']= $row['msgTxt'];
			$msgArr[intval($row['userId'])][$row['msgId']]['qcourseId']= $row['courseId'];
			$row['url'] =  getSeoUrl($row['threadId'],'question',$row['msgTxt']);
			$msgArr[intval($row['userId'])][$row['msgId']]['qstatus']= $row['status'];
			$msgArr[intval($row['userId'])][$row['msgId']]['qurl']= $row['url'];
			$msgArr[intval($row['userId'])][$row['msgId']]['qcreationDate']= $row['creationDate'];
			$queryCmd="SELECT `msgTxt`,`creationDate`,`msgId`,`userId` FROM `messageTable` where threadId = ? and parentId = threadId and status='live'";
			$queryRes = $this->dbHandle->query($queryCmd, array($msgId));
			$i=0;
			foreach($queryRes->result_array() as $row2) {
			     $msgArr[intval($row['userId'])][$row['msgId']]['answers'][$i]['AuserId']= $row2['userId'];
			     $msgArr[intval($row['userId'])][$row['msgId']]['answers'][$i]['AmsgTxt']= $row2['msgTxt'];
			     $msgArr[intval($row['userId'])][$row['msgId']]['answers'][$i]['AmsgId']= $row2['msgId'];
			     $i++;
			}
		}
		return $msgArr;
	}
	
function getRelatedDiscussions($dbHandle,$categoryId,$subCategoryId,$countryId){
if(!is_resource($dbHandleSent)){
$this->initiateModel();
}else{
$this->dbHandle = $dbHandleSent;
}
$total =4;
$queryCmd="select distinct mt.msgId ,mt.threadId,mt.msgTxt,mt.creationDate from messageTable mt,messageCategoryTable mct1, stickyDiscussionAndAnnoucementTable sdat where mct1.threadId = mt.msgId and mct1.categoryId =? and mt.fromOthers ='discussion' and mt.status IN ('live','closed') and parentId!=0 and mt.mainAnswerId=0 and  mt.threadId=sdat.stickythreadId and sdat.status='live' and sdat.type='discussion' order by sdat.createdDate desc limit 0,$total";
$Result = $this->dbHandle->query($queryCmd, array($subCategoryId));
$numOfRows = $Result->num_rows();
$i=0;
$msgIds ="";
foreach($Result->result_array() as $row){
$queryCmd="select count(mt.mainAnswerId) as total from messageTable mt where mt.fromOthers ='discussion' and mt.status IN ('live','closed') and parentId!=0 and mt.mainAnswerId = ? group by mt.mainAnswerId";

$query = $this->dbHandle->query($queryCmd, array($row['msgId']));
$result = $query->row();
$final[$i]['url'] = getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','',$row['creationDate']);
$final[$i]['msgTxt'] = $row['msgTxt'];
$final[$i]['comment'] = $result->total;
$msgIds .= "'".$row['threadId']."',";
$i++;
}

$remainingResult = $total-$numOfRows;

if($numOfRows < $total){
$msgIdforComments = preg_replace('/,$/','',$msgIds);
if($msgIds!='') $msgCommentQuery = "and mt.threadId not in (".$msgIdforComments.") ";

$queryCmd="select  count(mt.mainAnswerId) as total ,mt.mainAnswerId from messageTable mt, messageCategoryTable mct1 where mct1.threadId = mt.mainAnswerId and mct1.categoryId = ?  and mt.fromOthers ='discussion' and mt.status IN ('live','closed') and parentId!=0 $msgCommentQuery and mt.mainAnswerId not in ('0','-1') group by mt.mainAnswerId order by mt.creationDate desc";
$Result = $this->dbHandle->query($queryCmd, array($subCategoryId));
foreach($Result->result_array() as $rows){
if($numOfRows>=$total) break;
if($rows['total']>=20){
$queryCmd="select distinct mt.msgId,mt.threadId,mt.msgTxt,mt.creationDate from messageTable mt,messageDiscussion md,tuser t,messageCategoryTable mct1 where mct1.threadId = mt.msgId and mct1.categoryId =? and t.userid=mt.userId and mt.msgId=md.threadId and mt.fromOthers ='discussion' and mt.status IN ('live','closed') and parentId!=0 and mt.msgId=? and (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(mt.creationDate))<'7948800' limit 0,$remainingResult";

$Result = $this->dbHandle->query($queryCmd, array($subCategoryId,$rows['mainAnswerId']));
foreach($Result->result_array() as $row){
$final[$i]['url'] = getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','',$row['creationDate']);
$final[$i]['msgTxt'] = $row['msgTxt'];
$final[$i]['comment'] = $rows['total'];
$msgIds .= "'".$row['threadId']."',";
$i++;
$numOfRows++;
}
}

}

$remainingResult = $total-$numOfRows;
if($numOfRows < $total){
$msgIds = preg_replace('/,$/','',$msgIds);
$msgQuery = '';
if($msgIds!='') $msgQuery = "and mt.threadId not in (".$msgIds.") ";
//$queryCmd="select mt.msgId,mt.threadId,mt.msgTxt,mt.creationDate from messageTable mt,messageDiscussion md,tuser t,messageCategoryTable mct1, messageCountryTable mct2 ,stickyDiscussionAndAnnoucementTable sdat where mct1.threadId = mt.msgId and mct1.categoryId ='".$categoryId."' and mct2.threadId=mt.msgId and mct2.countryId ='".$countryId."' and t.userid=mt.userId and mt.msgId=md.threadId and mt.fromOthers ='discussion' and mt.status IN ('live','closed') and parentId!=0 $msgQuery and mt.threadId=sdat.stickythreadId and sdat.status='live' and sdat.type='discussion'  order by sdat.createdDate desc limit 0,$remainingResult";
$queryCmd="select distinct mt.msgId,mt.threadId,mt.msgTxt,mt.creationDate from messageTable mt,messageCategoryTable mct1 ,stickyDiscussionAndAnnoucementTable sdat where mct1.threadId = mt.msgId and mct1.categoryId =? and mt.fromOthers ='discussion' and mt.status IN ('live','closed') and parentId!=0 $msgQuery and mt.threadId=sdat.stickythreadId and sdat.status='live' and sdat.type='discussion'  order by sdat.createdDate desc limit 0,$remainingResult";
$Result = $this->dbHandle->query($queryCmd, array($categoryId));
foreach($Result->result_array() as $row){
$nunRows++;
$queryCmd="select count(mt.mainAnswerId) as total from messageTable mt where mt.fromOthers ='discussion' and mt.status IN ('live','closed') and parentId!=0 and mt.mainAnswerId =? group by mt.mainAnswerId";
$query = $this->dbHandle->query($queryCmd, array($row['msgId']));
$result = $query->row();
$final[$i]['url'] = getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','',$row['creationDate']);
$final[$i]['msgTxt'] = $row['msgTxt'];
$final[$i]['comment'] = $result->total;
$i++;
}
}

}
return $final;
}

	function checkForSameAnswerInPreviousSevenDays($userId,$msgTxt,$threadId){
		$this->initiateModel('read');
        //First check if this is an Enterprise user answering his own questions. If yes, we will not check for duplicacy.
        //$query = "select 1 from messageTable m, listings_main l where m.listingTypeId = l.listing_type_id and m.listingType = l.listing_type and l.status='live' and l.username=? and m.msgId=? and m.fromOthers = 'user' and m.listingTypeId > 0";
        //$queryRes = $this->dbHandle->query($query,array($userId,$threadId));
	//Check if this is an Enterprise user. If yes, we will not check for duplicacy.
	$query = "SELECT 1 FROM tuser where userid=? AND usergroup='enterprise'";
	$queryRes = $this->dbHandle->query($query,array($userId));
        if($queryRes->num_rows() > 0){
            return 0;
        }

		// $query = "select count(*) as total from messageTable M1 where M1.threadId = M1.parentId and M1.fromOthers='user' and M1.status not in('deleted','abused') and M1.userId = ? and (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(M1.creationDate))<'604800' and msgTxt=?";
	
		// $queryRes = $this->dbHandle->query($query,array($userId,htmlspecialchars($msgTxt)));
		// $result = $queryRes->row();
		// return $result->total;
        if(isset($userId)){
			$pastWeek = date("Y-m-d", strtotime("-7 day"));
			$query = "select msgTxt from messageTable use index (creationDateIndex) where threadId = parentId and fromOthers = 'user' and status not in ('deleted','abused') and userId = ? and creationDate >= '$pastWeek'";
			$queryRes = $this->dbHandle->query($query, $userId)->result_array();
			$msgTxt = md5($msgTxt);
			foreach ($queryRes as $key => $value) {
				if(md5($value['msgTxt']) == $msgTxt)
				{
					return 1;
				}
			}
		}
		return 0;
	}

	function hideComment($type,$msgId){
        	$this->initiateModel('write');
	        $queryCmd="update messageTable mt set mt.status = 'deleted' where mt.fromOthers = ? and mt.status IN ('live','closed') and msgId = ?";
        	$queryRes = $this->dbHandle->query($queryCmd,array($type,$msgId));
	        $queryCmd="update messageTable mt set mt.status = 'deleted' where mt.fromOthers = ? and mt.status IN ('live','closed') and path like (?)";
	        $queryRes = $this->dbHandle->query($queryCmd,array($type,"%.$msgId.%"));
		
		$queryCmd="update messageTable mt set mt.msgCount = 0  where mt.fromOthers = ? and mt.status IN ('live','closed') and parentId = ?";
	        $queryRes = $this->dbHandle->query($queryCmd,array($type,$msgId));
	        return 'hide';
	}

	function unhideComment($type,$msgId){
        	$this->initiateModel('write');
		$queryCmd = "select path from messageTable where msgId = ?";
		$queryRes = $this->dbHandle->query($queryCmd,array($msgId));
		$row = $queryRes->row();
		$path = $row->path;
		$parentEntities = explode(".",$path);
	        $parentEntities = implode(",",$parentEntities);
		
		
		
		$queryCmd = "select count(*) as commentCount from messageTable where mt.fromothers=? and mt.status IN ('deleted') and threadId = ? and mainAnswerId >0;";
		$queryRes = $this->dbHandle->query($queryCmd,array($type,$msgId));
		$result = $queryRes->result_array;
		$commentCount = $result['commentCount'];
		
		if(count($parentEntities)>0){
		        $queryCmd="update messageTable mt set mt.status = 'live' where mt.fromOthers = ? and mt.status IN ('deleted') and msgId IN ($parentEntities)";
        		$queryRes = $this->dbHandle->query($queryCmd,array($type));
		}
		
		$queryCmd = "update messageTable set msgCount = ? where mt.fromothers=? and mt.status in('live','closed') and msgId = ? and mainAnswerId >0;";
		$queryRes = $this->dbHandle->query($queryCmd,array($commentCount,$type,$msgId));
		
		
	        return 'unhide';
	}

    /**************
    Function to check if the starting number is less than the total answers available. For eg: If the user wants to see 4th page and there are lets say only 25 answers available (of 3 pages), we will have to redirect the user to Base page.
    ***************/
    function checkForAvailableComments($userId,$topicId,$start,$count){
	$this->initiateModel();
        $this->load->library('AnAConfig');
        if(!in_array($userId,AnAConfig::$userIds)){
             if($count==10){
                   $queryCmd = "select msgCount from messageTable where msgId = ? and status IN ('live','closed')";
             }
             else{
                   $queryCmd = "select count(*) msgCount from messageTable where threadId = ? and status IN ('live','closed') and mainAnswerId>0";
             }
             $Result = $this->dbHandle->query($queryCmd,array($topicId));
             $row = $Result->row();
             $totalCountAvailable = $row->msgCount;
             if($totalCountAvailable<=$start){
                   return 'REDIRECT';
             }
         }
         return 'NOREDIRECT';
     }
     //pragya 
    /**
      * includeInSiteMap flag is set/unset to include question in the sitemap.
      * It is set when couse is paid and there is CA available for that partucular course.	
    */
     function makeResponseOfQuestionAsker($userId,$messageId,$creationTime,$courseId,$instituteId,$includeInSitemap = 1){
	$this->initiateModel('write');
	$insertData = array(
		'userId' => $userId,
		'messageId' => $messageId,
		'courseId'=>$courseId,
		'instituteId'=>$instituteId,
		'creationTime'=>$creationTime,
		'includeInSitemap' => $includeInSitemap
	);
	$this->dbHandle->insert('questions_listing_response',$insertData);					
     }

    /******************************************
     * Function to check if a Discussion is associated with an Institute or not. If yes, return the complete details of the discussion with the last 6 comments
     * Input : Institute Id
     * Output: If no discussion is associated, then False. If a discussion is associated, then an Array with dscussion details
     ******************************************/
    function getInstituteDiscussionDetails($appId,$instituteId){
        $this->initiateModel();
	if($instituteId=='' || $instituteId==0){
		return false;
	}
        //Get the Discussion added on this listing
        $queryCmd = "select mt.*,md.description,tuser.displayname,tuser.avtarimageurl from tuser, messageTable mt LEFT JOIN messageDiscussion md ON (md.threadId=mt.msgId) where mt.listingTypeId = ? and mt.status IN ('live','closed') and fromOthers='discussion' and tuser.userid=mt.userId and mt.parentId=mt.threadId";
        $queryRes = $this->dbHandle->query($queryCmd,array($instituteId));
        $numRows = $queryRes->num_rows();
        if($numRows==0){
            return false;
        }
        foreach($queryRes->result_array() as $row){
            $threadId = $row['threadId'];
            $returningArray = $row;
            $returningArray['url'] =  getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','',$row['creationDate']);
        }

        //Get the last 6 comments of this discussion
        $queryCmd = "select SQL_CALC_FOUND_ROWS msgId,msgTxt,threadId,creationDate,messageTable.userId,tuser.displayname,tuser.avtarimageurl from messageTable, tuser where threadId = ? and status IN ('live','closed') and fromOthers='discussion' and tuser.userid=messageTable.userId and mainAnswerId>0 order by creationDate desc limit 6";
        $queryRes = $this->dbHandle->query($queryCmd,array($threadId));        
        foreach($queryRes->result_array() as $row){
               $returningArray['comments'][] = $row;
        }
	//Also, find the total number of comments on the Discussion
        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $this->dbHandle->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
              $returningArray['totalComments'] = $row->totalRows;
        }
        return $returningArray;
    }

    /******************************************
     * Function to check if a user is a Campus Ambassador/Current Student or not.
     * This will be checked if this user is an owner of a discussion which is associated with any Institute
     * Input : userId
     * Output: 1 or 0
     ******************************************/
    function checkIfUserIsCampusAmbassador($appId,$userId){
        $this->initiateModel();
        $queryCmd = "select 1 from messageTable where listingTypeId IS NOT NULL and listingTypeId >0 and status IN ('live','closed') and fromOthers='discussion' and userId = ? and parentId = 0";
        $queryRes = $this->dbHandle->query($queryCmd,array($userId));
        $numRows = $queryRes->num_rows();
        if($numRows>0){
            return 1;
        }
	return 0;
    }
    
    function checkIfUserIsRegisteredCampusRep($userId){
        $this->initiateModel();
        $queryCmd = "select 1 from CA_ProfileTable where userId=? and profileStatus='accepted'";
        $queryRes = $this->dbHandle->query($queryCmd,array($userId));
        $numRows = $queryRes->num_rows();
        if($numRows>0){
            return 1;
        }
	return 0;
    }
    /******************************************
     * Function to fetch data for ANA Moderation
     * Input : Array which can have Email, Displayname, Date or EntityId for which data needs to be extracted
     * Output: Result set of the data
     ******************************************/
    function getAnAModerationData($data, $courseIdList){
        $this->initiateModel();
	$start = isset($data['start'])?$data['start']:'0';
	$count = isset($data['count'])?$data['count']:'50';
	$queryEmail = $queryDate = $queryEntity = $queryName = $queryInstituteId = $queryDefault = $queryType = $queryCategory = $queryAutoInstituteId = '';
	$joinWithInstititeTable = " LEFT JOIN shiksha_institutes inst on m.listingTypeId=inst.listing_id ";

	$instFilter = " and inst.status='live' ";
	$instSelect = " inst.name, ";
	if( isset($data['email']) && $data['email']!=''){
		$userEmail = $this->dbHandle->escape_str($data['email']);
		$queryEmail = " and t.email = '$userEmail' ";		
	}
	if( isset($data['name']) && $data['name']!=''){
		$name = $this->dbHandle->escape_str($data['name']);
		$queryName = " and t.displayname = '$name' ";		
	}
	/*if( isset($data['date']) && $data['date']!=''){
		$date = $data['date'];
		$dateTom = date('Y-m-d',strtotime($date." + 1 day"));
		$queryDate = " and m.creationDate > '$date' and m.creationDate < '$dateTom' ";		
	}*/
	if( isset($data['date']) && isset($data['dateTo']) && $data['date']!='' && $data['dateTo']!=''){
		$date = $this->dbHandle->escape_str($data['date']);
		$dateTom = $this->dbHandle->escape_str($data['dateTo']);
		$queryDate = " and m.creationDate >= '$date 00:00:00' and m.creationDate <= '$dateTom 23:59:59' ";
	}
	if( isset($data['entity']) && $data['entity']!=''){
		$entity = $this->dbHandle->escape_str($data['entity']);
		$queryEntity = " and m.threadId = '$entity' ";
	}
	if( isset($data['instituteId']) && $data['instituteId']!=''){
		$instituteId = $this->dbHandle->escape_str($data['instituteId']);
		$instituteId = trim($instituteId,',');
		$queryInstituteId = " and m.listingTypeId IN ($instituteId) ";
	}
	if( isset($data['instituteName']) && $data['instituteName']!=''){
		$instituteAutoId = $this->dbHandle->escape_str($data['instituteName']);
		$queryAutoInstituteId = " and m.listingTypeId = $instituteAutoId ";		
	}
	if($queryEmail=='' && $queryName=='' && $queryDate=='' && $queryEntity=='' && $queryInstituteId=='' && $queryCategory=='' && $queryAutoInstituteId==''){
		$queryDefault = " and m.creationDate > DATE(now()) ";				
	}

	if( isset($data['entityType'])){
		if($data['entityType']=='user'){
			$queryType = " and m.fromOthers='user' and m.parentId=0 ";
		}
    if($data['entityType']=='answer'){
        $queryType = " and m.fromOthers='user' and m.mainAnswerId = 0 ";
    }
    if($data['entityType']=='answercomment'){
        $queryType = " and m.fromOthers='user' and m.mainAnswerId>0 ";
    }
	if($data['entityType']=='allccanswers'){
		$queryType = " and m.fromOthers='user' and m.mainAnswerId = 0  ";
		$queryDefault='';
	}
		
	}
	else {
		$queryType = " and m.fromOthers='user' and m.parentId=0 ";
	}

	$ansModStsQuery = '';
	if($data['entityModStatus'] == 'notModerated'){
		$ansModStsQuery = " and caAST.status='draft' ";
	}else if($data['entityModStatus'] == 'approved'){
		$ansModStsQuery = " and caAST.status='approved' ";
	}else if($data['entityModStatus'] == 'disapproved'){
		$ansModStsQuery = " and caAST.status='disapproved' ";
	}
	$result= array();

	if($data['entityType'] == '' || $data['entityType'] == 'user'){
 	$query = "select SQL_CALC_FOUND_ROWS m.*, m.*, caAST.reason, caAST.status answerSts, caMod.flag isModDone, $instSelect t.displayname, (select creationDate from messageTable mT where m.threadId=mT.msgId) questionPostTime, (select msgTxt from messageTable mT where m.threadId=mT.msgId) questionTxt, (select msgTxt from messageTable mT where m.parentId=mT.msgId) answerTxt from questions_listing_response qlr , messageTable m $joinWithInstititeTable  LEFT JOIN CA_AnswerStatusTable caAST on m.msgId=caAST.answerId LEFT JOIN CA_moderations caMod on caMod.msgId=m.msgId , tuser t
		 where t.userid = m.userId and m.fromOthers = 'user'  and qlr.messageId = m.msgId AND qlr.courseId in ($courseIdList) and m.listingType = 'institute' and qlr.status = 'live' $queryType $queryEmail $queryName $queryDate $queryEntity $queryInstituteId $queryAutoInstituteId $ansModStsQuery $queryDefault $instFilter
		and m.status IN ('live','closed')   group by m.msgId ORDER BY m.creationDate DESC LIMIT $start,$count";
		$anaInfo = $this->dbHandle->query($query);
		$countQry = 'SELECT FOUND_ROWS() AS total_records';
		$countRes = $this->dbHandle->query($countQry);
		$totalRecValueArr = $countRes->result_array();
		$result['totalSearchRecords'] = isset($totalRecValueArr[0]['total_records'])?$totalRecValueArr[0]['total_records']:0;
		$result['anaInfo']=$anaInfo;
	}

	if($data['entityType'] == 'answer' || $data['entityType'] == 'answercomment' ){
 	
		$queryMsgId = "Select messageId from questions_listing_response where courseId in ($courseIdList) and status = 'live' ";
	 	$msgIds = $this->dbHandle->query($queryMsgId)->result_array();
	 	$userIdCnt = 0;
		foreach ($msgIds as $key => $value) {
			if($msgIdCnt != 0){
				$msgIdString .= ',';
			}
			$msgIdString .= $value['messageId'];
			$msgIdCnt ++;
		}
 	$query = "select SQL_CALC_FOUND_ROWS m.*, m.*, caAST.reason, caAST.status answerSts, caMod.flag isModDone, $instSelect t.displayname, (select creationDate from messageTable mT where m.threadId=mT.msgId) questionPostTime, (select msgTxt from messageTable mT where m.threadId=mT.msgId) questionTxt, (select msgTxt from messageTable mT where m.parentId=mT.msgId) answerTxt from  messageTable m $joinWithInstititeTable  LEFT JOIN CA_AnswerStatusTable caAST on m.msgId=caAST.answerId LEFT JOIN CA_moderations caMod on caMod.msgId=m.msgId , tuser t
		 where t.userid = m.userId and m.listingType = 'institute' and m.threadId in ($msgIdString) $queryType $queryEmail $queryName $queryDate $queryEntity $queryInstituteId $queryAutoInstituteId $ansModStsQuery $queryDefault $instFilter
		and m.status IN ('live','closed')   group by m.msgId ORDER BY m.creationDate DESC LIMIT $start,$count";
		$anaInfo = $this->dbHandle->query($query);
		$countQry = 'SELECT FOUND_ROWS() AS total_records';
		$countRes = $this->dbHandle->query($countQry);
		$totalRecValueArr = $countRes->result_array();
		$result['totalSearchRecords'] = isset($totalRecValueArr[0]['total_records'])?$totalRecValueArr[0]['total_records']:0;
		$result['anaInfo']=$anaInfo;
	}



	//_p($query);
	if($data['entityType'] =='All' || $data['entityType'] == 'answer' || $data['entityType'] =='' || $data['entityType']=='featuredanswers'){
		$queryCA = "(select id as ca_id,officialCourseId as course_id,userId as userId  from CA_ProfileTable c1 where c1.officialCourseId!=0 && c1.profileStatus='accepted' and c1.officialBadge='Official')
			     union
			    (select ca2.mappingCAId as ca_id,ca2.courseId as course_id,ca1.userId as userId from CA_OtherCourseDetails ca2,CA_ProfileTable ca1 where ca2.status='live' and ca2.badge='official' and ca1.officialBadge='Official' and ca1.profileStatus='accepted' and ca1.officialCourseId=ca2.mainCourseId)
			    union
			    (select ca3.caId as ca_id,ca3.courseId as course_id,caP.userId as userId from CA_MainCourseMappingTable ca3,CA_ProfileTable caP where ca3.status= 'live' and ca3.caId=caP.id and ca3.badge in ('CurrentStudent','Alumni'))
			    union
			    (select capt.id as ca_id,capt.userId,caocd.courseId from CA_ProfileTable capt, CA_MainCourseMappingTable camcmt, CA_OtherCourseDetails caocd where capt.id=camcmt.caId and camcmt.id=caocd.mappingCAId and camcmt.status='live' and caocd.status='live' and camcmt.courseId=caocd.mainCourseId  and caocd.badge in ('CurrentStudent','Alumni') and camcmt.badge in ('CurrentStudent','Alumni'))";
		$caProfileInfo= $this->dbHandle->query($queryCA);
		$result['caProfileInfo']= $caProfileInfo;
	}
	
	if($data['entityType']=='allccanswers' ){
		$programId = $this->dbHandle->escape_str($data['categorySelect']);
	 	$queryUserId = "Select userId from CA_ProfileTable cppt where cppt.programId = ? and cppt.profileStatus = 'accepted' ";
	 	$queryRes = $this->dbHandle->query($queryUserId, array($programId));
		$userIds =  $queryRes->result_array();

	 	$userIdCnt = 0;
		foreach ($userIds as $key => $value) {
			if($userIdCnt != 0){
				$userIdString .= ',';
			}
			$userIdString .= $value['userId'];
			$userIdCnt ++;
		}
		$queryMsgId = "Select messageId from questions_listing_response where courseId in ($courseIdList) and status = 'live' ";
	 	$msgIds = $this->dbHandle->query($queryMsgId)->result_array();
	 	$userIdCnt = 0;
		foreach ($msgIds as $key => $value) {
			if($msgIdCnt != 0){
				$msgIdString .= ',';
			}
			$msgIdString .= $value['messageId'];
			$msgIdCnt ++;
		}
	 $queryCA  ="select SQL_CALC_FOUND_ROWS m.*, caAST.reason, caAST.status answerSts, caMod.flag isModDone, $instSelect t.displayname, (select creationDate from messageTable mT where m.threadId=mT.msgId) questionPostTime, (select msgTxt from messageTable mT where m.threadId=mT.msgId) questionTxt, (select msgTxt from messageTable mT where m.parentId=mT.msgId) answerTxt from messageTable m $joinWithInstititeTable  LEFT JOIN CA_AnswerStatusTable caAST on m.msgId=caAST.answerId LEFT JOIN CA_moderations caMod on caMod.msgId=m.msgId , tuser t
		 where t.userid = m.userId and m.parentId in ($msgIdString)and m.listingType = 'institute' and  m.userId in ($userIdString) $queryType $queryEmail $queryName $queryDate $queryEntity $queryInstituteId $queryAutoInstituteId $ansModStsQuery $queryDefault $instFilter
		and m.status IN ('live','closed')   group by m.msgId ORDER BY m.creationDate DESC LIMIT $start,$count";
		$caProfileInfo= $this->dbHandle->query($queryCA);
		$result['caProfileInfo']= $caProfileInfo;
		$countQry = 'SELECT FOUND_ROWS() AS total_records';
		$countRes = $this->dbHandle->query($countQry);
		$totalRecValueArr = $countRes->result_array();
		$result['totalSearchRecords'] = isset($totalRecValueArr[0]['total_records'])?$totalRecValueArr[0]['total_records']:0;
	}
	return $result;
    }   
        
    function getQuestionsListForSubCategory($subCatId, $dateToCheckFor) {
	$this->initiateModel();
	$sql = "SELECT A .msgId, A.creationDate, A.viewCount, A.msgCount, C.categoryId ".
		"FROM `messageTable` A, messageCategoryTable C ".
		"WHERE A.msgId = C.threadId AND C.categoryId = ? AND A.msgId = A.threadId AND A.fromOthers = 'user' ".
		"AND A.parentId =0 AND A.status IN ('live', 'closed') AND DATE( A.creationDate ) > ?";
	$queryRes = $this->dbHandle->query($sql, array($subCatId,$dateToCheckFor));
	return $queryRes->result_array();
    }

    function getDiscussionsList($subCatId, $dateToCheckFor) {
	$this->initiateModel();
	$sql = "SELECT m1.msgId, m1.threadId as discussionId, (SELECT viewCount FROM messageTable WHERE msgId = m1.threadId) viewCount , m1.creationDate, ".
		" (SELECT COUNT(*) FROM messageTable WHERE threadId = m1.threadId AND mainAnswerId > 0 AND STATUS = 'live') commentCount ".
		" FROM messageTable m1, messageCategoryTable mc1 ".
		" WHERE m1.fromOthers = 'discussion' AND m1.parentId = m1.threadId AND m1.status IN ('live', 'closed' ) AND ".
		" m1.threadId = mc1.threadId AND mc1.categoryId = ?  AND DATE( m1.creationDate ) > ?".
		" HAVING commentCount > 0";
	$queryRes = $this->dbHandle->query($sql, array($subCatId,$dateToCheckFor));
	return $queryRes->result_array();
    }
    
    function getQuestionsCountForSubcategories($subcat_array = array()) {
    	 
    	if(count($subcat_array) == 0) {
    		return array();
    	}
    	
    	$this->initiateModel();
    	
    	$sql = "SELECT count(A .msgId) as msg_count , C.categoryId ".
				"FROM `messageTable` A, messageCategoryTable C ".
				"WHERE A.msgId = C.threadId AND C.categoryId IN (?) AND ".
    			"A.msgId = A.threadId AND A.fromOthers = 'user' ".
				"AND A.parentId =0 AND A.status IN ('live', 'closed') group by C.categoryId";
    	
    	
    	$queryRes = $this->dbHandle->query($sql,array($subcat_array));
    	
    	$response_array = array();    	
    	foreach ($queryRes->result_array() as $row) {
    		$response_array[$row['categoryId']] = $row['msg_count']; 
    	}
    	 
    	return $response_array;
    }
    
    function getDiscussionsCountForSubcategories($subcat_array = array()) {

    	if(count($subcat_array) == 0) {
    		return array();
    	}

    	$this->initiateModel();

    	$sql = "SELECT count(m1.threadId) as nuber_of_discussions, mc1.categoryId ".	
			   " FROM messageTable m1, messageCategoryTable mc1 ".
		       " WHERE m1.fromOthers = 'discussion' AND m1.parentId = m1.threadId ".
    	       "AND m1.status IN ('live', 'closed' ) AND ".
		       "m1.threadId = mc1.threadId AND mc1.categoryId IN (?) ".
    	       "group by mc1.categoryId";


    	$queryRes = $this->dbHandle->query($sql,array($subcat_array));

    	$response_array = array();
    	foreach ($queryRes->result_array() as $row) {
    		$response_array[$row['categoryId']] = $row['nuber_of_discussions'];
    	}

    	return $response_array;
    }
    
    function updatePopularity($msgId, $popularityCount) {
	$this->initiateModel('write');
	$queryCmd="update messageTable set messagePopularity = ? where msgId = ?";
	$this->dbHandle->query($queryCmd, array($popularityCount,$msgId));
    }
   
    /* Not in use */
    /* 
    function getQuestionsByPopularity($subCatId, $dateToCheckFor) {
    	
	$this->initiateModel();
	
	$sql = "SELECT A.msgId, A.msgTxt, A.creationDate, A.viewCount, A.msgCount, C.categoryId, A.messagePopularity as popularityView ".
		" FROM `messageTable` A, messageCategoryTable C, course_pages_content_exceptions cpce ".
		" where A.msgId = cpce.contentTypeId".
		" and A.msgId = C.threadId AND C.categoryId = ? AND A.msgId = A.threadId AND A.messagePopularity IS NOT NULL ".
		" AND A.msgTxt != '' AND A.parentId = 0 AND A.status IN ('live', 'closed') AND DATE( A.creationDate ) > ?".
		" AND (A.listingTypeId IS NULL OR A.listingTypeId = 0) AND A.fromOthers = 'user' $exclusion_query".
		" and cpce.subCategoryId = ? and cpce.exceptionFlag = 'boost' and cpce.contentType ='qna'".
		" ORDER BY cpce.modifiedAt desc, A.messagePopularity desc limit 5";
	$boostedRes = $this->dbHandle->query($sql,array($subCatId, $dateToCheckFor, $subCatId))->result_array();
	$stillNeeded = 5-count($boostedRes);
	if($stillNeeded>0){
		$COURSE_PAGES_EXCLUSION_IDS = array();
		$sql = "select contentTypeId from course_pages_content_exceptions where contentType='qna' and exceptionFlag='noise' and subCategoryId=?";
		$exclusionRes = $this->dbHandle->query($sql, array($subCatId))->result_array();
		foreach($exclusionRes as $exclusionId){
		    $COURSE_PAGES_EXCLUSION_IDS['QNA'][] = reset($exclusionId);
		}
	
		//global $COURSE_PAGES_EXCLUSION_IDS;
		$exclusion_query = "";
		if(count($COURSE_PAGES_EXCLUSION_IDS['QNA']) >0) {
			$exclusion_query = " AND A.msgId NOT IN (".implode(",",$COURSE_PAGES_EXCLUSION_IDS['QNA']).") ";
		}
		
		$sql = "SELECT A.msgId, A.msgTxt, A.creationDate, A.viewCount, A.msgCount, C.categoryId, A.messagePopularity as popularityView ".
			"FROM `messageTable` A, messageCategoryTable C ".
			"WHERE A.msgId = C.threadId AND C.categoryId = ? AND A.msgId = A.threadId AND A.messagePopularity IS NOT NULL ".
			" AND A.msgTxt != '' AND A.parentId = 0 AND A.status IN ('live', 'closed') AND DATE( A.creationDate ) > ?".
			" AND (A.listingTypeId IS NULL OR A.listingTypeId = 0) AND A.fromOthers = 'user' $exclusion_query".
			" ORDER BY A.messagePopularity desc limit ".$stillNeeded;
		$queryRes = $this->dbHandle->query($sql, array($subCatId, $dateToCheckFor));
	
	}
	foreach($queryRes->result_array() as $qna){
		$boostedRes[] = $qna;
	}
	$questionsData = array();
	foreach($boostedRes as $key => $row){
		$questionsData[] = $row;
		$questionsData[$key]['URL'] = getSeoUrl($row['msgId'],'question',$row['msgTxt']);
	}
	
	return $questionsData;
    }
    */

    function getNoOfAnswersForQuestions($questionIds) {
	$this->initiateModel();
	$sql = "select msgCount, msgId from messageTable where msgId in (?)";
	$questionIdsArr = explode(',',$questionIds);
	$queryRes = $this->dbHandle->query($sql, array($questionIdsArr));
	foreach($queryRes->result_array() as $key => $row){
		$questionsData[$row['msgId']] = $row['msgCount'];
	}
	return $questionsData;
    }
   
    /* Not in use */
    /*
    function getDiscussionsByPopularity($subCatId, $dateToCheckFor) {
    	
	$this->initiateModel();
	
	
	$sql = "SELECT m1.msgId, m1.threadId as discussionId, m1.msgTxt, md.description, m1.messagePopularity as popularityView, ".
		" (SELECT COUNT(*) FROM messageTable WHERE threadId = m1.threadId AND mainAnswerId > 0 AND STATUS = 'live') commentCount ".
		" FROM messageTable m1, messageCategoryTable mc1, messageDiscussion md, course_pages_content_exceptions C ".
		" where C.contentTypeId = m1.threadId".
		" and m1.fromOthers = 'discussion' AND m1.parentId = m1.threadId AND m1.status IN ('live', 'closed' ) AND m1.msgId = md.threadId".
		" and C.subCategoryId = ? and C.contentType = 'discussion' and C.exceptionFlag = 'boost'".
		" AND m1.threadId = mc1.threadId AND mc1.categoryId = ? AND m1.messagePopularity IS NOT NULL AND DATE( m1.creationDate ) > ? $exclusion_query".
		" AND (m1.listingTypeId IS NULL OR m1.listingTypeId = 0) HAVING commentCount > 0 ORDER BY C.modifiedAt desc, m1.messagePopularity desc limit 5";
	$boostedRes = $this->dbHandle->query($sql, array($subCatId,$subCatId,$dateToCheckFor))->result_array();
	$recordsNeeded = 5 - count($boostedRes);
	if($recordsNeeded>0){
		$COURSE_PAGES_EXCLUSION_IDS = array();
		$sql = "select contentTypeId from course_pages_content_exceptions
		    where contentType='discussion' and exceptionFlag='noise' and subCategoryId=?";
		$exclusionRes = $this->dbHandle->query($sql, array($subCatId))->result_array();
		foreach($exclusionRes as $exclusionId){
		    $COURSE_PAGES_EXCLUSION_IDS['DISCUSSIONS'][] = reset($exclusionId);
		}
	
		//global $COURSE_PAGES_EXCLUSION_IDS;
		$exclusion_query = "";
		if(count($COURSE_PAGES_EXCLUSION_IDS['DISCUSSIONS']) >0) {
			$exclusion_query = " AND m1.threadId NOT IN (".implode(",",$COURSE_PAGES_EXCLUSION_IDS['DISCUSSIONS']).") ";
		}
		$sql = "SELECT m1.msgId, m1.threadId as discussionId, m1.msgTxt, md.description, m1.messagePopularity as popularityView, ".
			" (SELECT COUNT(*) FROM messageTable WHERE threadId = m1.threadId AND mainAnswerId > 0 AND STATUS = 'live') commentCount ".
			" FROM messageTable m1, messageCategoryTable mc1, messageDiscussion md ".
			" WHERE m1.fromOthers = 'discussion' AND m1.parentId = m1.threadId AND m1.status IN ('live', 'closed' ) AND m1.msgId = md.threadId".
			" AND m1.threadId = mc1.threadId AND mc1.categoryId = ? AND m1.messagePopularity IS NOT NULL AND DATE( m1.creationDate ) > ? $exclusion_query".
			" AND (m1.listingTypeId IS NULL OR m1.listingTypeId = 0) HAVING commentCount > 0 ORDER BY m1.messagePopularity desc limit ".$recordsNeeded;
		
		$queryRes = $this->dbHandle->query($sql, array($subCatId,$dateToCheckFor));
	
	}
	foreach($queryRes->result_array() as $secondaryResult){
		$boostedRes[] = $secondaryResult;
	}
	$data = array();
	foreach($boostedRes as $key => $row){
		$data[] = $row;
		$data[$key]['URL'] = getSeoUrl($row['discussionId'],'discussion',$row['msgTxt']);
	}
	
	return $data;
    }
    */

    function getNoOfCommentsForDiscussions($discussionThreadIds) {
	$this->initiateModel();
	$sql = "SELECT COUNT(*) as commentCount, threadId FROM messageTable WHERE threadId in (?) AND mainAnswerId > 0 AND STATUS = 'live' GROUP BY threadId";	
	$discussionThreadIdsArr = explode(',',$discussionThreadIds);
	$queryRes = $this->dbHandle->query($sql, array($discussionThreadIdsArr));
	foreach($queryRes->result_array() as $key => $row){
		$discussionData[$row['threadId']] = $row['commentCount'];
	}
	return $discussionData;
    }
   
    /* not in use */
    /* 
    function getQuestionsDiscussionsSubcategories($ids_array = array()) {

    	if(count($ids_array) == 0) {
    		return array();
    	}
    	
    	global $COURSE_PAGES_SUB_CAT_ARRAY; 
    	$this->initiateModel();
    	 
    	$sql = "SELECT threadId,categoryId ".
				"FROM messageCategoryTable ".
				"WHERE threadId IN (".implode(",", $ids_array).") AND ".
    	        "categoryId IN (".implode(",", array_keys($COURSE_PAGES_SUB_CAT_ARRAY)).")";    	
    	 
    	 
    	$queryRes = $this->dbHandle->query($sql);    	
    	$response_array = array();
    	foreach ($queryRes->result_array() as $row) {
    		$response_array[$row['threadId']][] = $row['categoryId'];
    	}

    	return $response_array;
    }
    */
	
	function getDiscussionDetails($discussionIds)
	{
		$this->initiateModel();
		
		$sql = "SELECT msgId,fromOthers FROM messageTable where msgId IN (?)";
		$query = $this->dbHandle->query($sql, array($discussionIds));
		$results = $query->result_array();
		
		$discussions = array();
		$questions = array();
		foreach($results as $result) {
			if($result['fromOthers'] == 'user') {
				$questions[] = $result['msgId'];
			}
			else {
				$discussions[] = $result['msgId'];
			}
		}
				
		$sql = array();
		if(count($discussions)) {
			$sql[] = "SELECT m1.msgId,m1.threadId as discussionId, m2.msgTxt, m1.fromOthers,md.description, 
						(SELECT COUNT(*) FROM messageTable WHERE threadId = m1.msgId AND mainAnswerId > 0 AND STATUS = 'live') commentCount 
						FROM messageTable m1
						INNER JOIN messageTable m2 ON m2.parentId = m1.msgId
						LEFT JOIN messageDiscussion md ON m2.msgId = md.threadId
						WHERE m1.status IN ('live', 'closed' )
						AND m1.msgId IN (".implode(',',$discussions).")";
		}
		if(count($questions)) {
			$sql[] = "SELECT m1.msgId,m1.threadId as discussionId, m1.msgTxt, m1.fromOthers,md.description, 
						(SELECT COUNT(*) FROM messageTable WHERE threadId = m1.msgId AND mainAnswerId = 0 AND STATUS = 'live') commentCount 
						FROM messageTable m1 LEFT JOIN messageDiscussion md ON m1.msgId = md.threadId 
						WHERE m1.status IN ('live', 'closed' )
						AND m1.msgId IN (".implode(',',$questions).")";
		}
	
		
	
		$sql = implode(' UNION ',$sql);
		$queryRes = $this->dbHandle->query($sql);
		$data = array();
		foreach($queryRes->result_array() as $key => $row){
			$data[] = $row;
			$data[$key]['URL'] = getSeoUrl($row['discussionId'],'question',$row['msgTxt']);
		}
		return $data;
    }
    
    function sendMailToCampusReps($courseId,$listingTypeId,$msgId,$row = false){
           $instituteId=$listingTypeId;
           $this->load->library('mailerClient');
           $MailerClient = new MailerClient();
           if($courseId>0){
           	   $this->load->builder('nationalCourse/CourseBuilder');
	           $builder = new CourseBuilder();
	           $listingRepository = $builder->getCourseRepository();
	           $listingObj = $listingRepository->find($courseId);
           }else{
           	   $this->load->builder('nationalInstitute/InstituteBuilder');
	           $builder = new InstituteBuilder();
	           $listingRepository = $builder->getInstituteRepository();
	           $listingObj = $listingRepository->find($instituteId);
           }
           
           $contentArr['type'] = 'questionmailtoCampusReps';
           $this->load->model('CA/cadiscussionmodel');
           $this->CADiscussionModel = new CADiscussionModel();
           //$result = $this->CADiscussionModel->getCampusReps($courseId, $instituteId,3,true);
           
		   $this->intitutedetaillib  = $this->load->library("nationalInstitute/InstituteDetailLib");
           $instituteCourseHierarchy = $this->intitutedetaillib->getAllCoursesForInstitutes($instituteId,'direct');
           $courseIds                = $instituteCourseHierarchy['instituteWiseCourses'][$instituteId];
           $result                   = $this->CADiscussionModel->getCR($courseIds);
           //error_log('::shiksha::'.print_r($courseIds, true));
           //error_log('::shiksha::'.print_r($crData, true));


           //$result = $this->CADiscussionModel->getCampusReps($courseId, $instituteId,3,true);
		   //error_log('::shiksha::'.print_r($result, true));           
           if(!$row) {
           		$this->initiateModel();
           		$queryCmd = "SELECT m1.msgTxt,t1.displayname,t1.firstname,t1.lastname,t1.email from tuser t1, 
           			messageTable m1, countryTable ct where m1.userId=t1.userid and m1.msgId=? and ct.countryId IN (select countryId from messageCountryTable where
           			 threadId =? and countryId!=1)";    
           		$query = $this->dbHandle->query($queryCmd, array($msgId,$msgId));
           		$row = $query->row();
           }
           
           if($result['totalReps'] >0 ){
           foreach($result['data'] as $campusRep=>$campusRepInfo){
               $contentArr['subject'] = "A new question has been posted for ".$listingObj->getName();
               $contentArr['campusRepName']=$campusRepInfo['firstname'];
               $userEmail=$campusRepInfo['email'];
               $contentArr['courseName']=($courseId>0)?$listingObj->getName():'';
               $contentArr['instituteName']=($courseId>0)?$listingObj->getInstituteName():$listingObj->getName();
               $contentArr['type'] = 'questionmailtoCampusReps';
               $contentArr['name'] = ($row->firstname=='')?$row->displayname:$row->firstname;
               $contentArr['msgTxt'] = $row->msgTxt;
               //$seoURL=getSeoUrl($msgId,'question',$row->msgTxt);
               //$contentArr['seoURL'] = $seoURL;
               $urlOfLandingPage = SHIKSHA_HOME."/CA/CRDashboard/getCRUnansweredTab";
       			$contentArr['urlOfLandingPage'] = $urlOfLandingPage;
               $contentArr['seoURL'] = $MailerClient->generateAutoLoginLink(1,$userEmail,$urlOfLandingPage);
	       
               
               Modules::run('systemMailer/SystemMailer/campusAmbassadorQuestionIntimation',$userEmail,$contentArr);
           }
          }
          
    }

    function getCourseIdOfQuestion($msgId){
	$this->initiateModel();
	$sql = "SELECT q.courseId FROM questions_listing_response q, listings_main l WHERE messageId=? AND q.status=? AND l.status='live' AND l.listing_type = 'course' AND l.listing_type_id = q.courseId";
	$query = $this->dbHandle->query($sql,array($msgId,'live'));
	$res = $query->result_array();
	$row = $query->row();
	$count = $query->num_rows();
	$courseId = '0';
	if($count>0){
		$courseId = $row->courseId;
	}
	return $courseId;
    }
	

    /**
     * @param varchar  $instituteId
     * Returns the questions list for institute id
     * @author Rahul
     */
    function getQuestionsForInstitute($instituteId ,$pageNo  = 0, $pageSize = 4) { 
	//Rewrite the code for Optimization of the Query
    	/*
    	$returnData = array();
    	$this->initiateModel();
    	
    	$queryCmd = "select SQL_CALC_FOUND_ROWS mt.msgId,mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp,mt.digDown,mt.mainAnswerId,
    				mt.threadId,mt.parentId, tu.userId , tu.displayname, tu.firstname , tu.lastname,mt.status 
    				from messageTable as mt inner join tuser as tu on tu.userid = mt.userId
    				where mt.listingTypeId = '{$instituteId}' and mt.listingType = 'institute' and mt.fromOthers = 'user' and mt.status IN ('live','closed')      				and mt.mainAnswerId = -1 and mt.parentId = 0 order by mt.msgId desc limit {$pageNo} , {$pageSize}";
    	
    	$queryRes = $this->dbHandle->query($queryCmd);
    	
    	$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
    	$queryTotal = $this->dbHandle->query($queryCmdTotal);
    	$queryResults = $queryTotal->result();
    	$totalRows = $queryResults[0]->totalRows;
    	
    	$returnData["total"] = $totalRows;
    	$returnData["data"] = $queryRes->result_array();
    	    	
    	return $returnData;
	*/

        $returnData = array();
        $this->initiateModel();

        $queryCmd = "select SQL_CALC_FOUND_ROWS mt.msgId,mt.msgTxt,mt.creationDate,mt.msgCount,mt.listingTypeId, mt.listingType,mt.digUp,mt.digDown,mt.mainAnswerId,
                                mt.threadId,mt.parentId,mt.status,mt.userId
                                from messageTable as mt
                                where mt.listingTypeId = ? and mt.listingType = 'institute' and mt.fromOthers = 'user' and mt.status IN ('live','closed')                                and mt.mainAnswerId = -1 and mt.parentId = 0 order by mt.msgId desc limit {$pageNo} , {$pageSize}";

        $queryRes = $this->dbHandle->query($queryCmd, array($instituteId));
        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $this->dbHandle->query($queryCmdTotal);
        $queryResults = $queryTotal->result();
        $totalRows = $queryResults[0]->totalRows;

        $userIds = "";
        $data = array();
        foreach($queryRes->result_array() as $key => $row){
               $userIds .= ($userIds=='')?$row['userId']:','.$row['userId'];
        }

        if($userIds != ''){
                $queryCmdUser = "SELECT userId , displayname, firstname , lastname from tuser where userid IN ($userIds)";
                $queryResUser = $this->dbHandle->query($queryCmdUser);
		$i = 0;
                foreach($queryRes->result_array() as $key => $row){
			$data[$i] = $row;
                        foreach($queryResUser->result_array() as $keyU => $rowU){
                                if($row['userId'] == $rowU['userId']){
                                        $data[$i]['displayname'] = $rowU['displayname'];
                                        $data[$i]['firstname'] = $rowU['firstname'];
                                        $data[$i]['lastname'] = $rowU['lastname'];
                                }
                        }
			$i++;
                }
        }

        $returnData["total"] = $totalRows;
        $returnData["data"] = $data;
        return $returnData;		    	
    }



    /**
     * @param varchar $courseId,varchar $questionId
     * Returns the includeSiteMap flag for question of particular course
     * @author Rahul
    */	
    function getSiteMapFlagForQuestion($courseId ,$questionId) {
	$returnVal = false;
	$data = array();
    	$this->initiateModel();
	
	$queryCmd = "select includeInSitemap from questions_listing_response where courseId = ? and messageId = ? and status = 'live'";
    	$queryRes = $this->dbHandle->query($queryCmd, array($courseId,$questionId));
    	$data = $queryRes->result_array();
	if(!empty($data) && !$data[0]['includeInSitemap']){	
		
		$returnVal = true;
	}
	return $returnVal;
    } 

     /**
     * @param varchar  $msgId , $isFeaturedFlag
     * Marks the answer as featured / non - featured
     * @author Pragya
     */
    function makeFeaturedAnswer($msgId,$isFeaturedFlag){
	$this->initiateModel('write');
	$query1 = "select * from featuredAnswersCampusRep where msgId = ? ";
	$queryUpdate = $this->dbHandle->query($query1, array($msgId));
	$result = $queryUpdate->result_array();
	if(empty($result))
	{
		$queryCmd = "INSERT INTO featuredAnswersCampusRep (msgId, isFeatured) VALUES (?,?)";
		$queryInsert = $this->dbHandle->query($queryCmd, array($msgId, $isFeaturedFlag));
	}
	else if(is_array($result[0]) && isset($result[0]['isFeatured']) && $result[0]['isFeatured']==0)
	{
		$queryCmd = "update featuredAnswersCampusRep set isFeatured = ? where msgId = ?";
		$queryUpdate = $this->dbHandle->query($queryCmd, array($isFeaturedFlag, $msgId));
	}
	return $isFeaturedFlag;
    }
    
    function makeAnswerModerated($msgId, $userId, $flag){
	$this->initiateModel('write');
	$query1 = "select * from CA_moderations where msgId = ? and userId = ?";
	$queryUpdate = $this->dbHandle->query($query1, array($msgId, $userId));
	$result = $queryUpdate->result_array();
	if(empty($result)){
	     $queryCmd = "INSERT INTO CA_moderations (flag, msgId, userId, creation_date) VALUES (?, ?, ?, NOW())";
	     $queryUpdate = $this->dbHandle->query($queryCmd, array($flag,$msgId,$userId));
	}
	else{
	     $queryCmd = "UPDATE CA_moderations set flag=?, creation_date=NOW() where msgId = ?";
             $queryUpdate = $this->dbHandle->query($queryCmd, array($flag,$msgId));
	}
	return $flag;
    }

     /**
     * @param varchar  $msgId 
     * Check if Question is Listings Question/Cafe Question
     * @author Ankur
     */
    function checkQuestionSource($msgId){
	$this->initiateModel();
	$query1 = "select listingTypeId from messageTable where msgId = ? and parentId=0 and fromOthers='user'";
	$queryRes = $this->dbHandle->query($query1,array($msgId));
	$result = $queryRes->row();
	$listing_type_id = $result->listingTypeId;
	if($listing_type_id>0){
		return 'Listings';
	}
	else{
		return 'Cafe';
	}
    }
    function updateCRAnswerStatus($sts, $reason, $userId, $ansId)
    {
	$this->initiateModel('write');
	if($sts != 'disapproved')
		$reason = '';
	$qry = "update CA_AnswerStatusTable set status = ? , reason = ? where userId = ? and answerId = ?";
	$queryRes = $this->dbHandle->query($qry,array($sts, $reason, $userId, $ansId));
	return $sts;
    }
    function addCRAnswerApproveEarning($type, $earning, $userId, $entityId)
    {
	$this->initiateModel('write');
	$qry = 'update CA_wallet set reward=?, action=?, status="earned", entityType="answer" where userId = ? and entityId = ? and status="pending"';
	$this->dbHandle->query($qry,array($earning, $type, $userId, $entityId));
    }
    function updateCRDisapproveAnswerEarning($userId, $entityId)
    {
	$this->initiateModel('write');
	$qry = 'update CA_wallet set action="", status="delete" where userId = ? and entityId = ?';
	$this->dbHandle->query($qry,array($userId, $entityId));
    }

    function addCRAnswerFeatureEarning($earning, $type, $userId, $entityId)
    {
	$this->initiateModel('write');
	
	$query1 = "select 1 from CA_wallet where entityId = ? and userId = ? and action='featuredAnswer'";
	$queryUpdate = $this->dbHandle->query($query1, array($entityId, $userId));
	$result = $queryUpdate->result_array();
	if(empty($result))
	{
		$qry = 'insert into CA_wallet (reward, action, status, entityType, userId, entityId) values(?,?,"earned","answer",?,?)';
		$this->dbHandle->query($qry,array($earning, $type, $userId, $entityId));
	}
    }

    function editAnswerByModerator($msgId,$msgTxt)
    {
	$this->initiateModel('write');
	$qry = 'update messageTable set msgTxt=? where msgId = ?';
	return $this->dbHandle->query($qry,array($msgTxt, $msgId));
    }

    function addAnswerStatus($userId,$answerId,$status,$reason){
	    $this->initiateModel('write');
    	    $queryCmd = "INSERT INTO CA_AnswerStatusTable (userId,answerId,creationDate,status,modificationDate,reason) Values( ?, ?, now(),?,'NULL',?)";
            $query = $this->dbHandle->query($queryCmd, array($userId,$answerId,$status,$reason));
            return $this->dbHandle->insert_id();
	}
	
    function getQuestionCreationDate($answerId)
    {
		$this->initiateModel('read');

		//$queryCmd = "Select creationDate as questionCreationDate from messageTable where msgId IN (select threadId from messageTable where msgId = ? and parentId = threadId) and fromOthers='user' and parentId=0";

		$queryCmd  = "SELECT b.creationDate AS questionCreationDate FROM messageTable a, messageTable b WHERE a.parentId = b.msgId AND a.msgId = ? AND a.fromOthers = 'user' AND b.fromOthers = 'user' and b.parentId = 0";

	    $query = $this->dbHandle->query($queryCmd,array($answerId));
	    $row = $query->row();
	    $questionCreationDate = $row->questionCreationDate;
	    return $questionCreationDate;
    }	
    
    function addInWallet($userId,$answerId,$reward)
    {
         $this->initiateModel('write');
         $query = "INSERT into CA_wallet (`userId`,`entityId`,`reward`) values(?,?,?)";
         $this->dbHandle->query($query, array($userId,$answerId,$reward));
	 return $this->dbHandle->insert_id();

     }
     
     function updateDisapprovedAnswerStatus($answerId)
     { 
	$this->initiateModel('write');
	$queryCmd ="UPDATE CA_AnswerStatusTable SET status='draft', modificationDate = now()
	where answerId=? AND status='disapproved'";
	$query = $this->dbHandle->query($queryCmd,array($answerId));
     }
     
     function getCRDetailsForDisapproveMail($userId, $ansId)
     {
	$this->initiateModel('read');
	$qry = "select cap.displayName, usr.email, usr.firstname as fname, usr.lastname as lname, q.courseId, m.userId as crUserId, m.msgId as crAnsId, qm.msgTxt as quesTxt, sts.reason as disapprove_reason from CA_ProfileTable cap left join tuser usr on cap.userId=usr.userid left join messageTable m on cap.userId=m.userId left join questions_listing_response q on q.messageId=m.threadId left join CA_AnswerStatusTable sts on m.msgId=sts.answerId  left join messageTable qm on m.threadId=qm.msgId where cap.userId = ? and m.msgId = ? and cap.profileStatus='accepted'";
	$queryRes = $this->dbHandle->query($qry,array($userId, $ansId));
	return $queryRes->result_array();
     }
     function getAllCRDetailsForNewOpenTaskMail($programId)
     {
	$this->initiateModel('read');
	$qry = "select distinct cap.userId, usr.email, usr.firstname, usr.lastname, cap.profileStatus from CA_ProfileTable cap, CA_MainCourseMappingTable camt, categoryPageData cpd, tuser usr
				WHERE camt.caId = cap.id
				AND camt.instituteId>0
				AND camt.courseId>0
				AND camt.locationId>0
				AND camt.courseId = cpd.course_id
				AND cpd.status = 'live'
				AND camt.badge = 'CurrentStudent'
				AND cap.profileStatus = 'accepted'
				AND camt.status='live'
				AND cap.userId = usr.userid
				AND cap.programId = ?";
	$queryRes = $this->dbHandle->query($qry,array($programId));
	return $queryRes->result_array();
     }
     
     function getAllCRDetailsForWeeklyDigestMail()
     {
	$this->initiateModel('read');
	$qry = "select cap.userId, usr.email, usr.firstname, usr.lastname, cap.profileStatus from CA_ProfileTable cap left join tuser usr on cap.userId=usr.userid where cap.profileStatus='accepted'";
	$queryRes = $this->dbHandle->query($qry);
	return $queryRes->result_array();
     }
     
     
    function updateCRWalletStatus($userId,$ansId,$reward)
    {
		$this->initiateModel('write');
		$queryCmd ="UPDATE CA_wallet SET status='pending', creationDate = now(), reward=?
		where entityId=? AND userId=? AND entityType='answer'";
		$query = $this->dbHandle->query($queryCmd,array($reward,$ansId,$userId));
    }
    
    function getStatusFromWalletTable($userId,$ansId)
	{
		$this->initiateModel('read');
		$queryCmd="select cw.status from CA_wallet cw where cw.userId=? and cw.entityId=? and cw.entityType='answer' LIMIT 1";
		$query = $this->dbHandle->query($queryCmd,array($userId, $ansId));
		return $query->result_array();
	}
	
    function getListingTypeId($userId,$ansId)
	{
		$this->initiateModel('read');
		$queryCmd="select listingTypeId from  messageTable where userId=? and msgId=? LIMIT 1";
		$query = $this->dbHandle->query($queryCmd,array($userId, $ansId));
		return $query->result_array();
	
	}
	
    function getAnswerCourseId($userId,$threadId)
    {
		$this->initiateModel('read');
		$queryCmd="select qlr.courseId from  messageTable mt ,questions_listing_response qlr where mt.threadId= qlr.messageId and mt.userId=? and mt.threadId=? LIMIT 1";
		$query = $this->dbHandle->query($queryCmd,array($userId, $threadId));
		return $query->result_array();
    }
    
    function getDiscussionsDetailsForExamPage($discussionId, $page_number = 0, $item_per_page = 3)
    {
	$this->initiateModel('read');
	
	$position = $page_number * $item_per_page;
	$queryCmd="select msgId,threadId,creationDate,msgTxt as Title, m.userId PostedBy, t.displayname, t.email,t.firstname,t.lastname from messageTable m, tuser t where threadId IN (?) and fromOthers='discussion' and status IN ('live','closed') and mainAnswerId=0 and t.userId=m.userId ORDER BY creationDate desc LIMIT $position, $item_per_page";
	$discussionIdArr = explode(',',$discussionId);
	$query = $this->dbHandle->query($queryCmd, array($discussionIdArr));
	return $query->result_array();
    }
    
    function getLastCommentDetails($parentId)
    {
	$this->initiateModel('read');
	$queryCmd = "select * from (select mt.msgId, mt.parentId, mt.msgTxt as description, mt.creationDate, mt.userId PostedBy, t.displayName, t.avtarimageurl from messageTable mt, tuser t where parentId in (?) and mt.status IN ('live','closed') and mt.mainAnswerId in (?) and t.userId=mt.userId order by parentId, mt.creationDate desc) temp group by temp.parentId";
	$parentIdArr = explode(',',$parentId);
	$query = $this->dbHandle->query($queryCmd, array($parentIdArr,$parentIdArr));
	
	$LastCommentData= array();
	foreach($query->result_array() as $key => $row){
		$LastCommentData[$row['parentId']] = $row;
	}
	return $LastCommentData;
    }
    
    function validateDiscussionIds($discussionId)
    {
	$this->initiateModel('read');
	$queryCmd="select threadId from messageTable m where threadId IN (?) and fromOthers='discussion' and status IN ('live','closed') and mainAnswerId=0";
	$discussionIdArr = explode(',',$discussionId);
	$query = $this->dbHandle->query($queryCmd, array($discussionIdArr));
	return $query->result_array();
	
    }
    
    function insertAnAMobileTracking($userId,$entityType,$source)
    {
	
	$this->initiateModel('write');
	$insertData = array(
		'userId' => $userId,
		'entityType' => $entityType,
		'creationTime'=>date('Y-m-d H:i:s'),
		'source'=>$source
	);
	$this->dbHandle->insert('mobileAnaQuestionsTracking',$insertData);	
	return $this->dbHandle->insert_id();
    }
    
    
    function getCampusRepOnListing($listingId){
	$this->initiateModel('read');
	$sql="select userId from CA_ProfileTable c where id in (select caId from CA_MainCourseMappingTable cmp where instituteId=? and cmp.status = 'live') AND c.profileStatus = 'accepted'";
	$query = $this->dbHandle->query($sql,array($listingId));
	$uids = $query->result_array();       
	return $uids[0]['userId'];
    }
    
    function getCampusRepNameEmail($userIds){
	$this->initiateModel('read');
	$sql = "select userid,email,firstname, lastname from tuser where userid in (?)";
	$userIdsArr = explode(',',$userIds);
	$query = $this->dbHandle->query($sql, array($userIdsArr));
	return $query->result_array();
    }
    
    
    function getUserDetailsForAnswerMailer($threadId){
	$this->initiateModel('read');
	$sql = "select userId from messageTable where msgId=? and status='live'";
	$query = $this->dbHandle->query($sql,array($threadId));
	$uids = $query->result_array();
	return $uids[0]['userId'];
    }
    
    function getUserDetailsForCommentMailer($answerId){
	$this->initiateModel('read');
	$sql = "select m.userId from messageTable m where m.msgId in (select threadId from messageTable mt where mt.parentId=? and mt.status='live') and m.status='live'";
	$query = $this->dbHandle->query($sql,array($answerId));
	$uids = $query->result_array();
	return $uids[0]['userId'];
    }
    
    function getcourseAndUseridForNotification($threadId){
	$this->initiateModel('read');
	$sql = "select courseId,m1.userId from questions_listing_response qlr, messageTable m1 where qlr.messageId=? and m1.msgId=? and  qlr.messageId=m1.msgId and m1.userId=qlr.userId";
	$query = $this->dbHandle->query($sql,array($threadId,$threadId));
	return $query->result_array();
    }
    
    function getDetailForAnswerOwnerNotification($threadId,$mainAnsId){
	$this->initiateModel('read');
	$sql = "select courseId,m1.userId,m1.threadId from questions_listing_response qlr, messageTable m1 where qlr.messageId=? and m1.msgId=? and  qlr.messageId=m1.threadId";
	$query = $this->dbHandle->query($sql,array($threadId,$mainAnsId));
	return $query->result_array();
    }
    
    function checkForShortlistCourse($courseId){
	$this->initiateModel('read');
	$sql = "select courseId from userShortlistedCourses where courseId=? and status='live';";
	$query = $this->dbHandle->query($sql,array($courseId));
	$courseIds = $query->result_array();
	return $courseIds[0]['courseId'];
    }
    
    function insertInNotificationTable($userId,$body,$creationDate){
	$this->initiateModel('write');
	$insertData = array(
			'user_id' => $userId,
			'body' => $body,
			'created' =>$creationDate,
			'updated' =>$creationDate
	);
	$this->dbHandle->insert('myshortlist_user_notification',$insertData);		
    }
    
    
    /*Get all Discussions posted in past 3 days */
    
    function getAllDiscussionIdsInLast3Days(){
	
	$this->initiateModel('read');
	$sql = "SELECT msgId as discussionId FROM messageTable WHERE fromOthers='discussion' AND status IN ('live','closed') AND creationDate >= now()- INTERVAL 3 Day AND parentId=0";
	$query = $this->dbHandle->query($sql);
	$discIds = $query->result_array();
	$discIdArr = array();
	foreach($discIds as $val)
	{
		$discIdArr[] = $val['discussionId']; 
	}	
	return $discIdArr;
	
    }
    
    /*Get all Discussions having comment >0 */
    /* Not in use anymore */
    /*
    function getdiscussionIdsForHappeningNowWidget(){
	
	$this->initiateModel('read');
	
	$result_array = array();
	$disIDs = $this->getAllDiscussionIdsInLast3Days();
	$discussionsIds = implode(',',$disIDs);
	
	if($discussionsIds != ''){
		$sql = "SELECT threadId as discussionId, count(*) as commentCount FROM messageTable where fromOthers='discussion' AND status IN ('live','closed') AND mainAnswerId>0 AND threadId IN ($discussionsIds) GROUP BY threadId HAVING commentCount > 0";

	$query = $this->dbHandle->query($sql);
	$result = $query->result_array();
	
	foreach($result as $val){
		
		$result_array['count'][$val['discussionId']] = $val['commentCount'];
		$result_array['discussionIds'][] = $val['discussionId']; 
		
	}
	}
	
	return $result_array;

    }
    
    */
    /*Get 5 Discussions on the basis of viewCount having comment>0 */
    /* Not in use anymore */
    /*    
    function finalMsgIdsForHappeningNowWidget(){
	
	$this->initiateModel('read');
	
	$result_array = array();
	$disIDs = $this->getdiscussionIdsForHappeningNowWidget();
	$dIds = implode(',',$disIDs['discussionIds']);
	
	if($dIds != ''){
		$sql = "SELECT msgId, viewCount FROM messageTable WHERE msgId IN ($dIds) AND status IN ('live','closed') AND parentId=0 AND fromOthers='discussion' ORDER BY viewCount DESC LIMIT 5";
	
		$query = $this->dbHandle->query($sql);
		$result_array["Result"] =  $query->result_array();
		$result_array["discussionCount"] = count($result_array["Result"]);
	
	}
	
	return $result_array;
	
    }
    */
     /*Get final set of DiscussionsIds on the basis of viewCount having comment>0 */
    /* Not in use anymore */
    /*
    function finalDiscussionIdsForHappeningNowWidget(){
	
	$this->initiateModel('read');
	
	$results = array();
	
	$result = $this->finalMsgIdsForHappeningNowWidget();
	
	$discussionThreadIds = array();
	foreach($result["Result"] as $key=>$value){
		$discussionThreadIds[] = $value['msgId'];
	}
	
	$threadIds = implode(',',$discussionThreadIds);
	
	$resultCount = $this->getdiscussionIdsForHappeningNowWidget();
	$totalDiscussionCommentCount = array();
	$totalDiscussionCommentCount = $resultCount['count'];
	
	$discussionCommentCount = array();
	foreach($discussionThreadIds as $val)
	{
		$discussionCommentCount[$val] = $totalDiscussionCommentCount[$val];
	}
	
	if($threadIds != ''){
		$sql = "SELECT msgId as discussionId,threadId,viewCount,status FROM messageTable WHERE threadId IN ($threadIds) AND status IN ('live','closed') AND mainAnswerId = 0 AND fromOthers='discussion'";
		
		$query = $this->dbHandle->query($sql);
		$result_array = $query->result_array();
		$results['discussionCount'] = $discussionCommentCount;
		$results['result'] = $result_array;
		
	}
	return $results;
	
    }
    */

    /*Get final set of QuestionIds on the basis of thumbup and viewCount having answer>0 */
    /* Not in use anymore */
    /*
    function questionIdsForHappeningNowWidget($discussionCount){
	
	$this->initiateModel('read');
	
	$result = $this->finalMsgIdsForHappeningNowWidget();
	$discussionCount = $result['discussionCount'];
	
	$limit = 20 - $discussionCount;
	
	$currentDate = date("Y-m-d");
        $timeRequired = strtotime("-3 days",strtotime($currentDate));
        $timeRequired = date ('Y-m-j' , $timeRequired);
	
	$sql="select sum(mt1.digup) as digSum,mt.msgId,mt.status from messageTable mt,messageTable mt1 where mt.status='live' and mt1.status='live' and mt.fromOthers='user' and mt.msgCount >= 1 and mt.listingTypeId = 0  and mt1.parentId=mt.msgId and mt.creationDate>='".$timeRequired."' group BY mt.msgId order by digSum DESC,mt.viewCount DESC LIMIT $limit";
	$query = $this->dbHandle->query($sql);
	
	return $query->result_array();	
    }
    */

    function insertQuestionCategoryFromCCIntermediatePage($data)
    {
	$this->initiateModel('write');
	$this->db->insert('shiksha.CA_question_category', $data);
    }
    
    
    function updateMsgCountForAllAnswers(){
	
	$this->initiateModel('read');
	$msgCount = array();
	
	$sql = "select msgId from messageTable where status in ('live','closed') and mainAnswerId = 0 and fromOthers = 'user'";
	
	$query = $this->dbHandle->query($sql);
	$allAnswers = $query->result_array();
	
	foreach($allAnswers as $allAnswer){
		
			$queryCmd  = "select count(*) as commentCount from messageTable where parentId = ? and mainAnswerId >0 and mainAnswerId = parentId and status in ('live','closed') and fromOthers = 'user'";
	
			$query1 = $this->dbHandle->query($queryCmd,array($allAnswer['msgId']));
			
			$msgCount = $query1->result_array();
			
			if(!empty($msgCount)){
				$sql = "update messageTable set msgCount =?  where msgId = ? and status in ('live','closed')";
				$query = $this->dbHandle->query($sql,array($msgCount[0]['commentCount'],$allAnswer['msgId']));
			}
			
		}
	
	
    }
    
	
	function rebuildHomepageCafeWidgetCache($threadId){
		
		$cacheLib = $this->load->library('cacheLib');
		$cacheData = $cacheLib->get(md5('getDataForHomepageCafeWidget'));
		if($cacheData != 'ERROR_READING_CACHE'){
		   $happeningWidgetData = json_decode($cacheData,true);
		   $questionDiscussionThreadIds = array();
		   foreach($happeningWidgetData['otherdata'] as $key=>$value){
		    
			$questionDiscussionThreadIds[] = $key;
			
		   }
		   
		   if(in_array($threadId,$questionDiscussionThreadIds)){
			
			$this->load->library('homepage/Homepageslider_client');
			$slider_object = new Homepageslider_client();
			$slider_object->deleteHomepageCacheHTMLFile();
			$cacheLib->clearCacheForKey(md5('getDataForHomepageCafeWidget'));
			
		   }
		}
		
	}
	
    function getCategoryAndSubCatIds($articleId){
	$this->initiateModel('read');
	$sql= "select boardId from blogTable where blogId =?";
	$query = $this->dbHandle->query($sql,array($articleId));
	$results = $query->result_array();
	$data['subCatId'] = $results[0]['boardId'];

	$sql = 'select parentId from categoryBoardTable where boardId=?';
	$query = $this->dbHandle->query($sql,array($data['subCatId']));
	$results =  $query->result_array();
	$data['catId'] = $results[0]['parentId'];
	return $data;
	}

	function getThreadDetails($id, $type){
		$this->initiateModel('read');
		if($type == 'question'){
			$sql = "select * from messageTable m1 where m1.parentId = 0 and fromOthers = 'user' and status in ('live','closed') and msgId = ?";
			$query = $this->dbHandle->query($sql,array($id));
			$results = $query->result_array();
		}
		else if($type == 'discussion'){
			$sql = "select * from messageTable m1 where m1.parentId = m1.threadId and fromOthers = 'discussion' and status in ('live','closed') and threadId = ?";
			$query = $this->dbHandle->query($sql,array($id));
			$results = $query->result_array();
		}

		return $results;
	}

	function getCommentAndViewCount($blogIdArray){
		$this->initiateModel('read');
		$blogIdString = implode($blogIdArray, ',');
		$query = "select blogId, msgCount, blogView from messageTable mt, blogTable bt where mt.threadId = bt.discussionTopic and mt.mainAnswerId=-1 and blogId in (?)";
		$res = $this->dbHandle->query($query, array($blogIdArray));
		foreach ($res->result_array() as $key => $value) {
			$result[$value['blogId']] = $value;
		}
		return $result;
	}

	/* Not in use anymore */
	/*	
	function getAnACafeModerationData($data){
		
		$this->initiateModel();
		//_p($data);
		$start = isset($data['start'])?$data['start']:'0';
		$count = isset($data['count'])?$data['count']:'50';
		$queryEmail = $queryDate = $queryEntity = $queryName = $queryDefault = $queryType = $queryCategory = '';
		if( isset($data['email']) && $data['email']!=''){
			$userEmail = $data['email'];
			$queryEmail = " and t.email = '$userEmail' ";		
		}
		if( isset($data['name']) && $data['name']!=''){
			$name = $data['name'];
			$queryName = " and t.displayname = '$name' ";		
		}
		if( isset($data['date']) && isset($data['dateTo']) && $data['date']!='' && $data['dateTo']!=''){
			$date = $data['date'];
			$dateTom = $data['dateTo'];
			$queryDate = " and m.creationDate >= '$date 00:00:00' and m.creationDate <= '$dateTom 23:59:59' ";
		}
		if( isset($data['entity']) && $data['entity']!=''){
			$entity = $data['entity'];
			$queryEntity = " and m.threadId = '$entity' ";
		}
		
		if(isset($data['tagValue']) && $data['tagValue']!=''){
			
			$tagId = $data['tagValue'];
			$queryTag = "and tcm.tag_id = '$tagId' and tcm.status='live'";
			$joinWithTagContentMappingTable = "JOIN tags_content_mapping tcm ON tcm.content_id=m.msgId";
			
			
		}
		
		if($queryEmail=='' && $queryName=='' && $queryDate=='' && $queryEntity=='' && $queryCategory=='' && $queryTag=='' ){
			$queryDefault = " and m.creationDate > DATE(now()) ";				
		}
		
		if( isset($data['categorySelect']) && $data['categorySelect']!=''){
			$categoryId = $data['categorySelect'];
			$queryCategory= " and mcat.categoryId = '$categoryId' ";
			$joinWithMessageCatTable=" LEFT JOIN messageCategoryTable mcat  ON m.threadId = mcat.threadId  ";
		}
		if( isset($data['entityType']) && $data['entityType']!='All' ){
			if($data['entityType']=='user'){
				$queryType = " and m.fromOthers='user' and m.parentId=0 ";
			}
			if($data['entityType']=='discussion'){
				$queryType = " and m.fromOthers='discussion' and m.mainAnswerId=0 ";
			}
			
			if($data['entityType']=='answer'){
				$queryType = " and m.fromOthers='user' and m.parentId=m.threadId ";
			}
			if($data['entityType']=='answercomment'){
				$queryType = " and m.fromOthers='user' and m.mainAnswerId>0 ";
			}
			if($data['entityType']=='discussioncomment'){
				$queryType = " and m.fromOthers='discussion' and m.mainAnswerId>0 and m.mainAnswerId = m.parentId ";
			}
			if($data['entityType']=='discussionreply'){
				$queryType = " and m.fromOthers='discussion' and m.mainAnswerId>0 and m.mainAnswerId != m.parentId ";
			}
			if($data['entityType']=='Unansweredquestion'){
				$queryType = "and m.fromOthers='user' and m.msgCount = 0 and m.mainAnswerId = -1";
			}
			
		}
		$result= array();
		
			$query = "select SQL_CALC_FOUND_ROWS m.*,(select categoryId from messageCategoryTable cat where cat.threadId=m.threadId 
			and cat.categoryId>1 LIMIT 1) categoryId, (select countryId from  messageCountryTable con where  m.threadId=con.threadId and 
			con.countryId>1 LIMIT 1) countryId,t.displayname,(select creationDate from messageTable mT where m.threadId=mT.msgId) questionPostTime,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = t.userid and upsm.modulename = 'AnA') as levelName, (select msgTxt from messageTable mT where m.threadId=mT.msgId) questionTxt,(select msgTxt from messageTable mT where mT.msgId=m.mainAnswerId and  mT.mainAnswerId = 0) as answerTxt,(select msgTxt from messageTable mT where mT.msgId=m.parentId and mT.parentId = mT.mainAnswerId) as commentTxt,(select mt.status from messageTable mt where mt.msgId=m.threadId) as threadStatus, (select status from messageTable mT where mT.msgId=m.mainAnswerId and  mT.mainAnswerId = 0) as answerStatus, (select status from messageTable mT where mT.msgId=m.parentId and mT.parentId = mT.mainAnswerId) as commentStatus from messageTable m $joinWithMessageCatTable $joinWithTagContentMappingTable 
			, tuser t where t.userid = m.userId and fromOthers IN ('user','discussion')  
			and m.listingTypeId = 0 and m.status IN ('live','closed') $queryEmail $queryDate $queryEntity
			 $queryName $queryCategory $queryDefault $queryType $queryTag ORDER BY m.creationDate DESC ";
			$anaDetails = $this->dbHandle->query($query)->result_array();
			
			foreach($anaDetails as $anaInformation){
				if($anaInformation['threadStatus']=='live' || $anaInformation['threadStatus']=='closed'){
					if((($anaInformation['mainAnswerId'] == -1 && $anaInformation['fromOthers'] != 'discussion' ) || $anaInformation['mainAnswerId'] == 0)){
						
						$anaInfo[] = $anaInformation;
	
	}else if($anaInformation['mainAnswerId'] > 0 && $anaInformation['mainAnswerId'] == $anaInformation['parentId'] && $anaInformation['answerStatus']=='live' ){
						
						$anaInfo[] = $anaInformation;
	
	}else if($anaInformation['mainAnswerId'] > 0 && ($anaInformation['mainAnswerId'] != $anaInformation['parentId']) && $anaInformation['answerStatus']=='live' && $anaInformation['commentStatus'] == 'live' ){
						
						$anaInfo[] = $anaInformation;
		
					}

				}	
				
			}
			
			$result['totalSearchRecords'] = count($anaInfo);	
			$anaInformations = array_slice($anaInfo, $start,$count);	
			$result['anaInfo']=$anaInformations;
		
		return $result;
		
	}
	*/

	function getAnACafeModerationData_v1($data, $moderatorId, $hasModeratorAccess){
		$this->initiateModel();
		$start = isset($data['start'])?$data['start']:'0';
		$count = isset($data['count'])?$data['count']:'50';
		$queryEmail = $queryByUserids = $queryDate = $queryEntity = $queryName = $queryDefault = $queryType = $queryCategory = '';
		if( isset($data['email']) && $data['email']!=''){
			$userEmail = $data['email'];
			$queryEmail = " and t.email = '$userEmail' ";		
		}
		if( isset($data['userids']) && $data['userids']!=''){
			$userids = $data['userids'];
			$queryByUserids = " and mtm.moderatorId in ($userids) ";
		}
		if( isset($data['name']) && $data['name']!=''){
			$name = $data['name'];
			$queryName = " and t.displayname = '$name' ";		
		}
		if( isset($data['date']) && isset($data['dateTo']) && $data['date']!='' && $data['dateTo']!=''){
			$date = $data['date'];
			$dateTom = $data['dateTo'];
                        $query = "SELECT msgId FROM messageTable WHERE creationDate >= '$date' LIMIT 1";
                        $firstMsgId = $this->dbHandle->query($query)->row()->msgId;
                        $query = "SELECT msgId FROM messageTable WHERE creationDate <= '$dateTom 23:59:59' ORDER BY creationDate DESC LIMIT 1";
                        $lastMsgId = $this->dbHandle->query($query)->row()->msgId;
            if($firstMsgId != '' && $lastMsgId != ''){
				$queryDate = " AND m.msgId >= $firstMsgId AND m.msgId <= $lastMsgId ";
			}
		}
		if(isset($data['tagValue']) && $data['tagValue']!=''){
			$tagId = $data['tagValue'];
			$queryTag = "and tcm.tag_id = '$tagId' and tcm.status='live'";
			$joinWithTagContentMappingTable = "JOIN tags_content_mapping tcm ON tcm.content_id=m.threadId";	
			$groupByTagContent = "";
		}
		if( isset($data['entity']) && $data['entity']!=''){
			$entity = $data['entity'];
			$queryEntity = " and m.threadId = '$entity' ";
		}
		
		if($queryEmail=='' && $queryName=='' && $queryDate=='' && $queryEntity=='' && $queryCategory=='' ){
			//In case, no dates are selected, we will display the data only for past 7 Days. But, if this is locked entity request, we will fetch the entities from begining
			if($data['moderationStatus']=='locked'){
				$date = $data['timeFlag'];
			}
			else{
		                $date = date("Y-m-d");
        	    		$date = strtotime("-7 days",strtotime($date));
	        	        $date = date ( 'Y-m-j' , $date );			
			}
			$query = "SELECT msgId FROM messageTable WHERE creationDate >= '$date' LIMIT 1";
	                $firstMsgId = $this->dbHandle->query($query)->row()->msgId;
			$queryDefault = " AND m.msgId >= $firstMsgId ";				
		}
		
		/*
		//removed from front-end
		if( isset($data['categorySelect']) && $data['categorySelect']!=''){
			$categoryId = $data['categorySelect'];
	        if($categoryId == -1){//condition for miscellaneous 
	                $queryCategory= " and mcat.categoryId is NULL ";
	                $joinWithMessageCatTable=" LEFT JOIN messageCategoryTable mcat ON m.threadId = mcat.threadId  and mcat.categoryId > 1";
	        }else{
	                $queryCategory= " and mcat.categoryId = '$categoryId' ";
	                $joinWithMessageCatTable=" LEFT JOIN messageCategoryTable mcat ON m.threadId = mcat.threadId  ";
	        }
		}*/
		if( isset($data['tagSelect']) && $data['tagSelect']!=''){
			$categoryId = $data['tagSelect'];
			$queryCategory= " and mcat.tagId = '$categoryId' and mcat.status = 'live'";
			$joinWithMessageCatTable=" LEFT JOIN messageTagsTable mcat ON m.threadId = mcat.threadId";
		}
		//$cafeFilter = " and (m.listingTypeId = 0 or m.listingTypeId is NULL) ";
		$cafeFilter = "";
		$quesListingRespJoin = '';
		if( isset($data['entityType']) && $data['entityType']!='All' ){

			//In case of Questions / Answers, fetch the list of questions which have been asked on Inst/Courses having CR. Then, in case of Listing questions, we will fetch a subset of these questions. In case of Cafe questions, we will fetch a subset of all question not falling under this list
			$this->benchmark->mark('fetch_all_CA_start');
		 	$caData = $this->getAllCAAndCourseIds();
			$this->benchmark->mark('fetch_all_CA_end');
			$this->benchmark->mark('get_CA_ques_start');
	                $queryCmd = "select qlr.messageId from questions_listing_response qlr join messageTable m on qlr.messageId = m.msgId where qlr.status = 'live' and m.status in ('live', 'closed') and qlr.courseId in (?) $queryDate $queryDefault 
        	        union
                	select tcm.content_id as messageId from tags_entity te join tags_content_mapping tcm on te.tag_id = tcm.tag_id join messageTable m on tcm.content_id = m.msgId where te.entity_type in ('institute', 'National-University') and te.status = 'live' and m.status in ('live', 'closed') and tcm.content_type = 'question' and tcm.status = 'live' and te.entity_id in (?) $queryDate $queryDefault ";
	                $queryRes = $this->dbHandle->query($queryCmd, array($caData['caCourseIds'], $caData['caInstIds']))->result_array();
			$this->benchmark->mark('get_CA_ques_end');
			$questionIds = array();
			foreach ($queryRes as $question){
				$questionIds[] = $question['messageId'];
			}
			$questionIds = array_unique($questionIds);
			//$questionIds = implode(',',$questionIds);

			//In case of Cafe Question, add a check for Questions
			if($data['entityType']=='user'){
				$queryType = " and m.fromOthers='user' and m.parentId=0 ";
				if(!empty($questionIds)){
					$cafeFilter = " and m.msgId NOT IN (?) ";
				}			
			}

			//In case of Listing question, add a check for Questions
			if($data['entityType']=='lques'){
				//$caData = $this->getAllCAAndCourseIds();
				//$queryType = " and m.fromOthers='user' and m.parentId=0 and qlr.status = 'live'".(count($caData['caCourseIds']) > 0 ? (" and qlr.courseId in (".implode(',', $caData['caCourseIds']).")") : '');
				//$quesListingRespJoin = ' JOIN questions_listing_response qlr on m.msgId = qlr.messageId';
				//$cafeFilter = " and m.listingTypeId > 0 and m.listingType='institute' ";
                                $queryType = " and m.fromOthers='user' and m.parentId=0 ";
                                if(!empty($questionIds)){
                                	$cafeFilter = " and m.msgId IN (?) ";
                                }
                                
			}

			//In case of Listing answers, add a check for Questions
			if($data['entityType']=='lanswer'){
				//$caData = $this->getAllCAAndCourseIds();
				$answeredBy = $data['answeredBy'];
				$queryType = " and m.fromOthers='user' and m.parentId=m.threadId ";
				//$queryType = " and m.fromOthers='user' and m.parentId=m.threadId and qlr.status = 'live'".(count($caData['caCourseIds']) > 0 ? (" and qlr.courseId in (".implode(',', $caData['caCourseIds']).")") : '');
				//$quesListingRespJoin = ' JOIN questions_listing_response qlr on m.parentId = qlr.messageId';
				if($answeredBy == 'cr'){
					$queryType .= count($caData['caUserIds']) > 0 ? " and m.userid in (".implode(',', $caData['caUserIds']).") " : "";
				}else if($answeredBy == 'other'){
					$queryType .= count($caData['caUserIds']) > 0 ? " and m.userid NOT in (".implode(',', $caData['caUserIds']).") " : "";
				}
				//$cafeFilter = " and m.listingTypeId > 0 and m.listingType='institute' ";
				if(!empty($questionIds)){
					$cafeFilter = " and m.threadId IN (?) ";
				}
				
			}
			if($data['entityType']=='discussion'){
				$queryType = " and m.fromOthers='discussion' and m.mainAnswerId=0 ";
			}

			//In case of Cafe answers, add a check for Questions			
			if($data['entityType']=='answer'){
				$queryType = " and m.fromOthers='user' and m.parentId=m.threadId ";
				if(!empty($questionIds)){
					$cafeFilter = " and m.threadId NOT IN (?) ";
				}			
			}
			if($data['entityType']=='answercomment'){
				$queryType = " and m.fromOthers='user' and m.mainAnswerId>0 ";
			}
			if($data['entityType']=='discussioncomment'){
				$queryType = " and m.fromOthers='discussion' and m.mainAnswerId>0 and m.mainAnswerId = m.parentId ";
			}
			if($data['entityType']=='discussionreply'){
				$queryType = " and m.fromOthers='discussion' and m.mainAnswerId>0 and m.mainAnswerId != m.parentId ";
			}
			if($data['entityType']=='Unansweredquestion'){
                                $queryType = " and m.fromOthers='user' and m.parentId=0 and m.msgCount = 0";
                                if(!empty($questionIds)){
                                	$cafeFilter = " and m.msgId NOT IN (?) ";
                                }
                                
				//$queryType = "and m.fromOthers='user' and m.msgCount = 0 and m.mainAnswerId = -1";
			}
			
		}
		$moderationStatusFilter = '';
		if($hasModeratorAccess==3)
		{
			$moderationStatusFilter = ' and (mtm.moderationStatus!="completed" or mtm.moderationStatus is NULL)';
		}
		
		if($data['sortOrder']=='newFirst'){
			$orderBy = ' ORDER BY m.msgId desc';
		}else{
			$orderBy = ' ORDER BY m.msgId ';
		}

		if($data['moderationStatus']=='complete'){
			$moderationStatusQry = ' and mtm.moderationStatus="completed" and mtm.status="live" ';
		}else if($data['moderationStatus']=='pending'){
			$moderationStatusQry = ' and (mtm.moderationStatus="cancelled" or mtm.moderationStatus is NULL) and (mtm.status="live" or mtm.status is NULL) ';
		}else if($data['moderationStatus']=='locked'){
			$moderationStatusQry = ' and mtm.moderationStatus="locked" and mtm.status="live" ';
		}else{
			$moderationStatusQry = ' and (mtm.status="live" or mtm.status is NULL) ';
		}

		if(isset($data['moderationBy']) && $data['moderationBy']!=''){
			$moderationByQry = ' and mtm.moderatorId="'.$data['moderationBy'].'" ';
		}else{
			$moderationByQry = '';
		}

		if($data['contentFlag'] == 'Auto-moderated'){
			$contentFlagJoin = 'JOIN messageEditTracking met ON (met.entityId = m.msgId)';
			$contentFlag = 'and autoModeratedFlag>0';
		}else if($data['contentFlag'] == 'Flagged content'){
			$contentFlagJoin = 'JOIN moderationPanelContentFlagging mpcf ON (m.msgId = mpcf.entityId)';
			$contentFlag = '';
		}
		else if($data['contentFlag'] == 'Request Sent'){
			$contentFlagJoin = 'JOIN moderation_editRequests mer ON (m.msgId = mer.entityId) and mer.status = "live" and mer.editStatus = "no" ';
			$contentFlag = '';
		}
		else if($data['contentFlag'] == 'Edit Done'){
			$contentFlagJoin = 'JOIN moderation_editRequests mer ON (m.msgId = mer.entityId) and mer.status = "live" and mer.editStatus = "yes"';
			$contentFlag = '';
		}

		if(!empty($data['instituteId']) && is_numeric($data['instituteId']))
		{
			//$cafeFilter = " and m.listingTypeId = '".((int)$data['instituteId'])."' and m.listingType='institute' ";
		}
		$result= array();
		$this->benchmark->mark('main_query_start');	
		$query = "select SQL_CALC_FOUND_ROWS m.*,mtm.*, t.displayname, (select msgTxt from messageTable mT where mT.msgId=m.mainAnswerId and mT.mainAnswerId = 0) as answerTxt, (select msgTxt from messageTable mT where mT.msgId=m.parentId and mT.parentId = mT.mainAnswerId) as commentTxt, (select status from messageTable mT where mT.msgId=m.mainAnswerId and mT.mainAnswerId = 0) as answerStatus, (select status from messageTable mT where mT.msgId=m.parentId and mT.parentId = mT.mainAnswerId) as commentStatus from messageTable m left join messageTableModeration mtm on m.msgId=mtm.entityId $quesListingRespJoin $joinWithMessageCatTable $contentFlagJoin $joinWithTagContentMappingTable, tuser t where t.userid = m.userId and fromOthers IN ('user','discussion') and m.msgTxt != 'dummy' $queryTag $cafeFilter $moderationStatusQry $moderationByQry and m.status IN ('live','closed') $moderationStatusFilter $queryEmail $queryByUserids $queryDate $queryEntity $queryName $queryCategory $queryDefault $queryType $groupByTagContent $contentFlag GROUP BY m.msgId $orderBy limit $start, $count";
		
		$anaDetails = $this->dbHandle->query($query,array($questionIds))->result_array();
		$this->benchmark->mark('main_query_end');

                $this->benchmark->mark('count_query_start');
                $queryForCount = 'select FOUND_ROWS() as totalCount';
                $anaDetailCount = $this->dbHandle->query($queryForCount)->result_array();
                $this->benchmark->mark('count_query_end');

		//Get the Automoderated flag in another query
		$this->benchmark->mark('moderation_flag_query_start');
		foreach ($anaDetails as $row){
			$msgIdArray[] = $row['msgId'];
                        $userIdArray[] = $row['userId'];
                        $moderatorIdArray[] = $row['moderatorId'];
                        $threadIdArray[] = $row['threadId'];
		}
		if(count($msgIdArray)>0){
			$query = "SELECT autoModeratedFlag, entityId FROM messageEditTracking WHERE entityId IN (?)";
			$autoMFlag = $this->dbHandle->query($query, array($msgIdArray))->result_array();

                        //Get Level Name
                        $query = "SELECT userId, levelName FROM userpointsystembymodule WHERE userId IN (?) AND modulename='AnA'";
                        $userLevelName = $this->dbHandle->query($query, array($userIdArray))->result_array();

                        //Get Moderator Email
                        $query = "SELECT userId, email as moderatorEmail FROM tuser WHERE userId IN (?)";
                        $moderatorEmails = $this->dbHandle->query($query, array($moderatorIdArray))->result_array();

                        //Get Category Id
                        $query = "SELECT threadId, categoryId FROM messageCategoryTable cat WHERE cat.threadId IN (?) AND cat.categoryId>14 LIMIT 1";
                        $categoryIdArr = $this->dbHandle->query($query, array($threadIdArray))->result_array();

                        //Get Country Id
                        $query = "SELECT threadId, countryId FROM messageCountryTable con WHERE con.threadId IN (?) AND con.countryId>1 LIMIT 1";
                        $countryIdArr = $this->dbHandle->query($query, array($threadIdArray))->result_array();

                        //Get Question details
                        $query = "SELECT threadId, creationDate as questionPostTime, msgTxt as questionTxt, status as threadStatus FROM messageTable mT WHERE mT.msgId IN (?)";
                        $quesDetailArr = $this->dbHandle->query($query, array($threadIdArray))->result_array();

			foreach ($anaDetails as $entityRow){
				$mainEntity = $entityRow['msgId'];
				$entityRow['autoModeratedFlag'] = 0;
				foreach ($autoMFlag as $flagRow){
					if($mainEntity == $flagRow['entityId']){
						$entityRow['autoModeratedFlag'] = 1;
					}
				}
                                foreach ($userLevelName as $row){
                                        if($entityRow['userId'] == $row['userId']){
                                                $entityRow['levelName'] = $row['levelName'];
                                        }
                                }
                                $entityRow['moderatorEmail'] = '';
                                foreach ($moderatorEmails as $row){
                                        if($entityRow['moderatorId'] == $row['userId']){
                                                $entityRow['moderatorEmail'] = $row['moderatorEmail'];
                                        }
                                }
                                $entityRow['categoryId'] = '';
                                foreach ($categoryIdArr as $row){
                                        if($entityRow['threadId'] == $row['threadId']){
                                                $entityRow['categoryId'] = $row['categoryId'];
                                        }
                                }
                                $entityRow['countryId'] = 2;
                                foreach ($countryIdArr as $row){
                                        if($entityRow['threadId'] == $row['threadId']){
                                                $entityRow['countryId'] = $row['countryId'];
                                        }
                                }
                                foreach ($quesDetailArr as $row){
                                        if($entityRow['threadId'] == $row['threadId']){
                                                $entityRow['questionPostTime'] = $row['questionPostTime'];
                                                $entityRow['questionTxt'] = $row['questionTxt'];
                                                $entityRow['threadStatus'] = $row['threadStatus'];
                                        }
                                }

				$anaDetailsTemp[] = $entityRow;
			}
			$anaDetails = $anaDetailsTemp;
		}
		$this->benchmark->mark('moderation_flag_query_end');
		//Finish Auto moderation flag

		$result['sqlQuery'] = $query;
		$result['totalSearchRecords'] = $anaDetailCount[0]['totalCount'];
		$result['anaBasicInfo']=$anaDetails;
		return $result;
		
	}

	function getAllCAAndCourseIds(){
		$this->initiateModel('read');
		$sql = "SELECT cap.userId, cap.displayname, cam.instituteId, cam.courseId mainCourse, cao.courseId as otherCourse, cap.programId 
				from CA_ProfileTable cap 
				join CA_MainCourseMappingTable cam on cap.id = cam.caId
				left join CA_OtherCourseDetails cao on cam.id = cao.mappingCAId
				where cap.profileStatus = 'accepted' and cam.status = 'live' and cam.badge = 'CurrentStudent' and (cao.badge = 'CurrentStudent' or cao.badge is null) and (cao.status = 'live' or cao.status is null)";
		$result = $this->dbHandle->query($sql)->result_array();
		$CACourses = $CAIds = $CAInstitutes = array();
		foreach($result as $value){
			$CAIds[$value['userId']] = $value['userId'];
			$CAInstitutes[$value['instituteId']] = $value['instituteId'];
			$CACourses[$value['mainCourse']] = $value['mainCourse'];
			if(!empty($value['otherCourse'])){
				$CACourses[$value['otherCourse']] = $value['otherCourse'];
			}
		}
		return array('caUserIds' => $CAIds, 'caCourseIds' => $CACourses, 'caInstIds' => $CAInstitutes);
	}
	
	function getTagDetails($entityIds){
		
		$this->initiateModel();
		$queryCmd = "select group_concat(tags) as tagName,tcm.content_id from tags t,tags_content_mapping tcm where tcm.tag_id = t.id and tcm.content_id in (?) and tcm.status = 'live' group By tcm.content_id";
		$entityArr = explode(',',$entityIds);
                
		$query = $this->dbHandle->query($queryCmd, array($entityArr));
		
		$result = $query->result_array();
		
		foreach($result as $val){
			$tags[$val['content_id']] = $val['tagName'];
		}
		
		return $tags;
		
	}

	/*
	 * added by akhter
	 * query to get no of experts
	 * use on homepage get an expert widget
	 */
	function getTotalAnAExpert(){
		
		$cacheLib = $this->load->library('cacheLib');
		$key = "totalANAExpert";
		$res = $cacheLib->get($key);
		if($res == 'ERROR_READING_CACHE'){ 
			$this->initiateModel('read');
			$sql = "select count(userId) as no_of_experts from userpointsystembymodule where userpointvaluebymodule>=1150 AND modulename ='AnA'";
			$query = $this->dbHandle->query($sql);
			$rows = $query->result();
			$cacheLib->store($key,$rows[0]->no_of_experts,10800);
	        return $rows[0]->no_of_experts;
		}else{
			return $res;
		}
		
	}

	function getUserPointHistory($userName,$userId,$emailId,$startDate,$endDate){
		$this->initiateModel();
		$whereClause = '';
		$whereArr = array();
		if($startDate == ''){
			$startDate = date("Y-m-d", strtotime('-7 days'));
		}
		if($endDate == ''){
			$endDate = date("Y-m-d");
		}
		if($startDate == $endDate || $endDate == date("Y-m-d"))
		{
			// $startDate = date("Y-m-d", strtotime('-1 days', strtotime($startDate)));
			$endDate = date("Y-m-d", strtotime('+1 days', strtotime($endDate)));
		}
		$startDate = date("Y-m-d", strtotime($startDate));
	    $endDate = date("Y-m-d", strtotime($endDate));
		if($startDate < $endDate)
		{
			$whereClause .= " and upsl.timestamp > ? and upsl.timestamp < ?";
			$whereArr[] = $this->dbHandle->escape_str($startDate);
			$whereArr[] = $this->dbHandle->escape_str($endDate);
		}
		if($userId!=0){
			$whereClause .= " and  tu.userid = ?";
			$whereArr[] = $this->dbHandle->escape_str($userId);
		}
		if($userName!='')
		{
			$whereClause .= " and tu.displayname = ?";
			$whereArr[] = $this->dbHandle->escape_str($userName);
		}
		if($emailId!='')
		{
			$whereClause .= " and tu.email = ?";
			$whereArr[] = $this->dbHandle->escape_str($emailId);
		}
		$query = "select mt.threadId, mt.msgTxt, mt.fromOthers, upsm.levelName, tu.firstname, tu.lastname, upsl.pointvalue, upsl.action, upsl.timestamp, upsl.entityId from  tuser tu, userpointsystembymodule upsm, userpointsystemlog upsl left join messageTable mt on mt.msgId=upsl.entityId where 1=1 ".$whereClause." and upsm.userId=upsl.userId and upsl.userId=tu.userid and upsm.moduleName='AnA' order by upsl.timestamp desc limit 100";
		$res = $this->dbHandle->query($query,$whereArr);
		return($res->result_array());
	}

	function getTopContributors($appId,$count,$weekly=1,$start=0,$tc=1,$tp=1,$catId=1) {
        $this->benchmark->mark('code_start1');
        error_log("==============startser".memory_get_usage());
        $this->initiateModel();
        $this->load->library('cacheLib');
        $cacheLib = new cacheLib();
        $keyCache = "getTopContributors".$weekly.$catId;
        $resultArrayFinal =array();
        if($cacheLib->get($keyCache)=='ERROR_READING_CACHE'){
	        $userIdString = '';
	        //Added for excluding Shiksha Experts and counsellors from Cafe Star widget
	        $excludedUserString = ' and ups.userid not in (1130, 1156, 3895, 1014, 1020, 249142, 1045, 1318, 343294, 333758, 345634, 341660, 344301, 345646, 345642, 344016, 344227, 345632, 370572, 371307, 371451, 371380, 369633, 364773, 369446, 356573, 351009, 370533, 356504, 356441, 368464, 369661, 339598, 369214, 369687, 370418, 356444, 370543, 370471, 369596, 371346, 368423, 356620, 351011, 365579, 368466, 351005, 370454, 370523, 370372, 358765, 368594, 370398, 351002, 355361, 355354, 370560, 368460, 369443, 365245, 370440, 370480, 370561, 371471, 370525, 371393, 371410, 358820, 369506, 369662, 370552, 369466, 370461, 370570, 371415, 371337, 371325, 356437, 369460,762290,1544835,1459752,1478915) ';

	        if($weekly==0) {
	            $queryCmd="select DISTINCT ups.userId from userpointsystemlog ups where ups.module = 'AnA' and ups.action != 'Register' and ups.userid > 1000 $excludedUserString order by ups.timestamp desc limit 1,1000";      
	        }
	        else {
	            $today = date("Y-m-d");
	            $week = date("Y-m-d", strtotime("-7 day"));
	            if($tc=="1") {
	                if($catId == 1) {
	                    $queryCmd="select DISTINCT ups.userId from userpointsystemlog ups where ups.timestamp >= '".$week."' and ups.action != 'Register' and ups.module='AnA' and ups.userid > 1000 $excludedUserString order by ups.timestamp desc limit 1,1000";
	                }
	                else {
	                    $queryCmd="select DISTINCT ups.userId from userpointsystemlog ups where ups.timestamp >= '".$week."' $excludedUserString and ups.userid > 1000 and ups.action != 'Register' and ups.module='AnA' and ups.entityId is not NULL and ups.entityId!=0 AND ups.entityId IN (select mm.msgId from messageTable mm, messageCategoryTable mmc where mmc.threadId = mm.threadId and mmc.categoryId = ? and mm.msgId = ups.entityId) order by ups.timestamp desc limit 1,1000";
	                }
	            }
	            
	        }

	        unset($excludedUserString);

	        if($tc=="1" && $queryCmd!='') {
	            if($weekly == 1 && $catId != 1)
	                $query = $this->dbHandle->query($queryCmd,array($catId));
	            else
	                $query = $this->dbHandle->query($queryCmd);
	            foreach ($query->result_array() as $key => $value) {
	                if($key != 0)
	                    $userIdString .= ',';
	                $userIdString .= $value['userId'];
	            }

	            if($userIdString == '')
	            {
	            	$cacheLib->store($keyCache, $userIdString, 259200);
	            	return;
	            }

	            $queryTuserJoin = "select t.userid, t.avtarimageurl, t.firstname, t.lastname, t.displayname from tuser t where t.userId in ($userIdString) and t.usergroup != 'cms'";

	            $res1 = $this->dbHandle->query($queryTuserJoin)->result_array();

	            $userIdString = '';
	            foreach ($res1 as $key => $value) {
	            	$tuserDetailsArray[$value['userid']] = $value;
	                if($key != 0)
	                    $userIdString .= ',';
	                $userIdString .= $value['userid'];
	                unset($res1[$key]);
	            }

	            if($userIdString == '')
	            {
	            	$cacheLib->store($keyCache, $userIdString, 259200);
	            	return;
	            }

	            $queryPoints = "select upsm.levelName as level, upsm.userpointvaluebymodule, ups.userId, SUM(ups.pointvalue) userPointValue from userpointsystemlog ups,userpointsystembymodule upsm where ups.timestamp >= '".$week."' and ups.module='AnA' and upsm.modulename='AnA' and upsm.userid = ups.userId and ups.userId in ($userIdString) group by ups.userId having SUM(ups.pointvalue) > 0 order by userPointValue desc limit 0,30";

	            $res3 = $this->dbHandle->query($queryPoints)->result_array();
	            foreach ($res3 as $key => $value) {
	                $totalPointsArray[$value['userId']] = $value;
	                $userIdArray[$value['userId']] = $value['userId'];
	                unset($res3[$key]);
	            }

	            $resultTotal = array();
	            foreach ($userIdArray as $key => $value) {
	            	$resultTotal[$value]['userPointValue'] = $totalPointsArray[$value]['userPointValue'];
	            	$resultTotal[$value]['level'] = $totalPointsArray[$value]['level'];
	            	$resultTotal[$value]['userpointvaluebymodule'] = $totalPointsArray[$value]['userpointvaluebymodule'];
	            	$resultTotal[$value]['firstname'] = $tuserDetailsArray[$value]['firstname'];
	            	$resultTotal[$value]['lastname'] = $tuserDetailsArray[$value]['lastname'];
	            	$resultTotal[$value]['displayname'] = $tuserDetailsArray[$value]['displayname'];
	            	$resultTotal[$value]['avtarimageurl'] = $tuserDetailsArray[$value]['avtarimageurl'];
	            	$resultTotal[$value]['userId'] = $value;
	            	unset($totalPointsArray[$value]);
	                unset($tuserDetailsArray[$value]);
	            }
	            error_log("=======from db");
	            $cacheLib->store($keyCache, $resultTotal, 259200);
	        	}
	    	}
	        else{
	        	error_log("========from cache");
	        	$resultTotal = $cacheLib->get($keyCache);
	        }
	        if($tc=="1" && !empty($resultTotal)) {
	        	foreach ($resultTotal as $key => $value) {
	                $userIdArray[$value['userId']] = $value['userId'];
	            }
	            $userIdSliced = array_slice($userIdArray,$start,10);
	            $userIdString = '';
	            foreach ($userIdSliced as $key => $value) {
	                if($key != 0)
	                    $userIdString .= ',';
	                $userIdString .= $value;
	            }

	            $queryAnswerCount = "select count(*) as totalAnswers, userId from messageTable where userId in ($userIdString) and parentId != 0 and mainAnswerId=0 and status IN ('live','closed') and fromOthers='user' group by userId";
	            $res2 = $this->dbHandle->query($queryAnswerCount)->result_array();

	            foreach ($res2 as $key => $value) {
	                $answerCountArray[$value['userId']] = $value;
	                unset($res2[$key]);
	            }

	            foreach ($userIdSliced as $key => $value) {
	                if($resultTotal[$value]['userPointValue'] != '' && $resultTotal[$value]['userpointvaluebymodule'] > 0){
	                    $resultArrayFinal[$value]['totalAnswers'] = $answerCountArray[$value]['totalAnswers'] == '' ? 0: $answerCountArray[$value]['totalAnswers'];
	                    $resultArrayFinal[$value]['weeklyPoints'] = $resultTotal[$value]['userPointValue'];
	                    $resultArrayFinal[$value]['totalPoints'] = $resultTotal[$value]['userpointvaluebymodule'];
	                    $resultArrayFinal[$value]['level'] = $resultTotal[$value]['level'];
	                    $resultArrayFinal[$value]['avtarimageurl'] = $resultTotal[$value]['avtarimageurl'];
	                    $resultArrayFinal[$value]['firstname'] = $resultTotal[$value]['firstname'];
	                    $resultArrayFinal[$value]['lastname'] = $resultTotal[$value]['lastname'];
	                    $resultArrayFinal[$value]['displayname'] = $resultTotal[$value]['displayname'];
	                    $resultArrayFinal[$value]['userid'] = $value;
	                    unset($answerCountArray[$value]);
	                }
	            }

	            function orderByUserPointWeekDesc($a, $b)
	            {
	                if ($a['weeklyPoints'] == $b['weeklyPoints']) {
	                    return ($a['totalPoints'] > $b['totalPoints']) ? -1 : 1;
	                }
	                return ($a['weeklyPoints'] > $b['weeklyPoints']) ? -1 : 1;
	            }
	            usort($resultArrayFinal, "orderByUserPointWeekDesc");
	        }
        $this->benchmark->mark('code_end1');
        error_log("==============ser".$this->benchmark->elapsed_time('code_start1', 'code_end1'));
        error_log("==============endser".memory_get_usage());
        return($resultArrayFinal);
	}

    /**
     * Update the digup/digdown value of answer/comment.
     * Takes userId threadId as parameter
     * Once the user has done the digup/digdown we allow him to revert it also.
     * Author: Ankur Gupta
     */
    function deleteDigVal($appId, $userId, $msgId, $digVal, $isLoginFlow = FALSE) {

        $this->initiateModel('write');

        //Check if user has already given the rating. Only if he has, we will revert it back.
        $queryToExe = "SELECT userId, digFlag FROM digUpUserMap WHERE userId = ? AND productId = ? AND product = 'qna' AND digUpStatus = 'live'";
        $Result = $this->dbHandle->query($queryToExe,array($userId,$msgId));
        $resultArray =  $Result->result_array();
        $rows = $Result->num_rows();
   		if($rows > 0 && ($isLoginFlow || $digVal != $resultArray[0]['digFlag'])){
   			return array('Result'=>'na');
   		}
        if($rows <= 0) {
                return array('Result'=>'NF');
        }

        $queryCmd = "UPDATE digUpUserMap SET digUpStatus = 'deleted' WHERE userId = ? AND productId = ? AND product = 'qna'";
        $result = $this->dbHandle->query($queryCmd,array($userId,$msgId));
        $numRowsAffected = $this->dbHandle->affected_rows();
        if($numRowsAffected > 0) {

            //Update the accumulated count in message table
            if($digVal == 0) {
                $queryCmd = "update messageTable set digDown = (select count(*) from  digUpUserMap where productId = ? and product = 'qna' and digFlag = 0 and digUpStatus = 'live') where msgId = ?";
            }else {
                $queryCmd = "update messageTable set digUp = (select count(*) from  digUpUserMap where productId = ? and product = 'qna' and digFlag = 1 and digUpStatus = 'live') where msgId = ?";
            }
            $result = $this->dbHandle->query($queryCmd,array($msgId,$msgId));
            $numRowsAffected = $this->dbHandle->affected_rows();
            if($numRowsAffected > 0) {

                //Revert the points in the User point system
                $this->load->model('UserPointSystemModel');
                if($digVal == 1) {

                        $queryToExe = "select userId, fromOthers from messageTable where msgId = ?";
                        $Result = $this->dbHandle->query($queryToExe,array($msgId));
                        $row = $Result->row();
                        $ownerUserId = $row->userId;
                        $entityType = $row->fromOthers;

                        if($entityType == "user"){
                            $this->UserPointSystemModel->updateUserPointSystem($this->dbHandle,$ownerUserId,'removeThumpUpAnswer',$msgId);
                        }
                        else if($entityType == "discussion"){
                            $this->UserPointSystemModel->updateUserPointSystem($this->dbHandle,$ownerUserId,'removeThumpUpComment',$msgId);
                        }
                        $this->UserPointSystemModel->updateUserReputationPoint($userId,'removeThumpUp',$msgId);
                }
                else if($digVal == 0) {

                        $this->UserPointSystemModel->updateUserReputationPoint($userId,'removeThumpDown',$msgId);
                }
            }
        }

        return array('Result'=>'success');
}

function checkIfUserHasRatedAnswer($userId, $answerId){
		$this->initiateModel('read');
		
		$queryCmd = "select digFlag, productId from digUpUserMap where userId =? and productId in (?) and (digFlag = 1 OR digFlag = 0) and digUpStatus = 'live'";
		$answerIdArr = explode(',',$answerId);
		$query = $this->dbHandle->query($queryCmd,array($userId,$answerIdArr));
		
		$result = $query->result_array();

		foreach($result as $row){
			$finalResult[$row['productId']] = $row['digFlag'];
		}
		return $finalResult	;
	}
	public function getUserIdsByEmails($internaluserEmailIds){
		$this->initiateModel();
		$ids = array();
		if(!empty($internaluserEmailIds))
		{
			$internaluserEmailIdStr = '"'.implode('","', $internaluserEmailIds).'"';
			$query = "SELECT userid from tuser where email in (?)";
			$res = $this->dbHandle->query($query, array($internaluserEmailIds))->result_array();
			foreach ($res as $key => $value) {
				$ids[] = $value['userid'];
			}
		}
		return $ids;
	}

	public function getCategoryIdsForAnaMetric(){
		$this->initiateModel();
		$ids = array();
		$query = "SELECT 1 as boardId, 'Miscellaneous' as name
					union
				SELECT boardId, name from categoryBoardTable where parentId = '1' and (flag = 'national' or flag = 'testprep') 
				order by name";
		$res = $this->dbHandle->query($query)->result_array();
		foreach ($res as $key => $value) {
			$ids[$value['boardId']] = $value['name'];
		}
		return $ids;
	}

	public function getQuestionMetricForExternalUsers($internaluserUserIdStr){
		$this->initiateModel();
		$returnData = array();
		$internaluserQuery = '';
		if($internaluserUserIdStr != '')
		{
			$internaluserQuery = " and mt1.userId not in ($internaluserUserIdStr) ";
		}
		$query = "SELECT mct.categoryId, count(case when mt1.msgCount=0 then mt1.msgCount end) as zeroAnsCount, count(case when mt1.msgCount>0 then mt1.msgCount end) as moreThan1AnsCount FROM `messageTable` mt1 join messageCategoryTable mct on mt1.msgId = mct.threadId WHERE mct.categoryId<=14 and mct.categoryId > 1 and mt1.`mainAnswerId` = '-1' and mt1.`fromOthers` = 'user' and mt1.`creationDate` >= now() - INTERVAL 7 DAY and mt1.status in ('live','closed') and (mt1.`listingTypeId` = 0 or mt1.`listingTypeId` is NULL) $internaluserQuery group by mct.categoryId";
		$returnData['category'] = $this->dbHandle->query($query)->result_array();

		$query = "SELECT '1' as categoryId, count(case when mt1.msgCount=0 then mt1.msgCount end) as zeroAnsCount, count(case when mt1.msgCount>0 then mt1.msgCount end) as moreThan1AnsCount FROM `messageTable` mt1 left join messageCategoryTable mct on mt1.msgId = mct.threadId and mct.categoryId > 1 WHERE mt1.`mainAnswerId` = '-1' and mt1.`fromOthers` = 'user' and mt1.`creationDate` >= now() - INTERVAL 7 DAY and mt1.status in ('live','closed') and (mt1.`listingTypeId` = 0 or mt1.`listingTypeId` is NULL) $internaluserQuery and mct.categoryId is NULL";
		$returnData['miscellaneous'] = $this->dbHandle->query($query)->result_array();
		return $returnData;
	}

	public function getQuestionMetricForInternalUsers($internaluserUserIdStr){
		$this->initiateModel();
		$internaluserQuery = '';
		$returnData = array();
		if($internaluserUserIdStr != '')
		{
			$internaluserQuery = " and mt1.userId in ($internaluserUserIdStr) ";
			$query = "SELECT mct.categoryId, count(case when mt1.msgCount=0 then mt1.msgCount end) as zeroAnsCount, count(case when mt1.msgCount>0 then mt1.msgCount end) as moreThan1AnsCount FROM `messageTable` mt1 join messageCategoryTable mct on mt1.msgId = mct.threadId WHERE mct.categoryId<=14 and mct.categoryId > 1 and mt1.`mainAnswerId` = '-1' and mt1.`fromOthers` = 'user' and mt1.`creationDate` >= now() - INTERVAL 7 DAY and mt1.status in ('live','closed') and (mt1.`listingTypeId` = 0 or mt1.`listingTypeId` is NULL) $internaluserQuery group by mct.categoryId";
			$returnData['category'] = $this->dbHandle->query($query)->result_array();

			$query = "SELECT '1' as categoryId, count(case when mt1.msgCount=0 then mt1.msgCount end) as zeroAnsCount, count(case when mt1.msgCount>0 then mt1.msgCount end) as moreThan1AnsCount FROM `messageTable` mt1 left join messageCategoryTable mct on mt1.msgId = mct.threadId and mct.categoryId > 1 WHERE mt1.`mainAnswerId` = '-1' and mt1.`fromOthers` = 'user' and mt1.`creationDate` >= now() - INTERVAL 7 DAY and mt1.status in ('live','closed') and (mt1.`listingTypeId` = 0 or mt1.`listingTypeId` is NULL) $internaluserQuery and mct.categoryId is NULL";
			$returnData['miscellaneous'] = $this->dbHandle->query($query)->result_array();
		}
		return $returnData;
	}

	public function getTatDataForQuestionsByExternalUsers($internaluserUserIdStr){
		$this->initiateModel();
		$internaluserQuery = '';
		$query = "SELECT m1.msgId as queId, m1.creationDate as quePostDate, m2.msgId as ansId, m2.creationDate as ansPostDate, mct.categoryId from messageTable m1 left join messageTable m2 on m1.msgId = m2.parentId left join messageCategoryTable mct on mct.threadId = m1.msgId where m1.mainAnswerId = '-1' and m2.mainAnswerId = '0' and m1.fromOthers = 'user' and m1.status in ('live', 'closed') and m2.status in ('live', 'closed') and m1.creationDate >= NOW() - INTERVAL 7 DAY  and mct.categoryId <= 14 order by m2.creationDate";
		return $this->dbHandle->query($query)->result_array();
	}

	public function getTatDataForQuestionsByInternalUsers($internaluserUserIdStr){
		$this->initiateModel();
		$internaluserQuery = '';
		if($internaluserUserIdStr != '')
		{
			$internaluserQuery = " and m1.userId in ($internaluserUserIdStr) ";
			$query = "SELECT m1.msgId as queId, m1.creationDate as quePostDate, m2.msgId as ansId, m2.creationDate as ansPostDate, mct.categoryId from messageTable m1 left join messageTable m2 on m1.msgId = m2.parentId left join messageCategoryTable mct on mct.threadId = m1.msgId where m1.mainAnswerId = '-1' and m2.mainAnswerId = '0' and m1.fromOthers = 'user' and m1.status in ('live', 'closed') and m2.status in ('live', 'closed') and m1.creationDate >= NOW() - INTERVAL 7 DAY  and mct.categoryId <= 14 $internaluserQuery order by m2.creationDate";
			return $this->dbHandle->query($query)->result_array();
		}
	}

	public function getCategoryWiseDiscussionPosted(){
		$this->initiateModel();
		$query = "SELECT m.msgId, mct.categoryId from messageTable m join messageCategoryTable mct on mct.threadId=m.msgId where m.`fromOthers` = 'discussion' and m.status in ('live', 'closed') and m.parentId = 0 and m.creationDate >= NOW() - INTERVAL 7 DAY and mct.categoryId <=14";
		return $this->dbHandle->query($query)->result_array();
	}

	public function getUsersPostingAnswersAndDscnComments($internaluserUserIdStr){
		$this->initiateModel();
		$returnData = array();
		$internaluserQuery = '';
		if($internaluserUserIdStr != '')
		{
			$internaluserQuery = " and m1.userId not in ($internaluserUserIdStr) ";
		}
		//category data
		$query = "SELECT m1.userId, m1.threadId, m1.msgId, mct.categoryId FROM messageTable as m1 join messageCategoryTable mct on mct.threadId = m1.threadId WHERE m1.creationDate >= NOW() - INTERVAL 7 DAY and (m1.fromOthers = 'user' and m1.parentId = m1.threadId and m1.mainAnswerId = 0 or m1.fromOthers = 'discussion' and m1.mainAnswerId > 0 and m1.mainAnswerId = m1.parentId) $internaluserQuery and m1.status in ('live', 'closed') and mct.categoryId <= 14 and mct.categoryId > 1 group by m1.userId, mct.categoryId";
		$returnData['category'] = $this->dbHandle->query($query)->result_array();

		//miscellaneous category data
		$query = "SELECT m1.userId, m1.threadId, m1.msgId, '1' as categoryId FROM messageTable as m1 left join messageCategoryTable mct on mct.threadId = m1.threadId and mct.categoryId > 1 WHERE m1.creationDate >= NOW() - INTERVAL 7 DAY and (m1.fromOthers = 'user' and m1.parentId = m1.threadId and m1.mainAnswerId = 0 or m1.fromOthers = 'discussion' and m1.mainAnswerId > 0 and m1.mainAnswerId = m1.parentId) $internaluserQuery and m1.status in ('live', 'closed') and mct.categoryId is NULL";
		$returnData['miscellaneous'] = $this->dbHandle->query($query)->result_array();
		return $returnData;
	}

	public function getNumOfQuestionsAnsweredByExternalUsers($internaluserUserIdStr){
		$this->initiateModel();
		$returnData = array();
		$internaluserQuery = '';
		if($internaluserUserIdStr != '')
		{
			$internaluserQuery = " and m2.userId not in ($internaluserUserIdStr) ";
		}
		//category data
		$query = "SELECT m1.msgId as queId, m2.msgId as ansId, mct.categoryId FROM messageTable as m1 join messageTable m2 on m1.msgId = m2.parentId join messageCategoryTable mct on mct.threadId=m1.msgId WHERE m2.creationDate >= NOW() - INTERVAL 7 DAY and m1.fromOthers = 'user' and m1.status in ('live', 'closed') and m2.status in ('live', 'closed') and m1.mainAnswerId = -1 and m2.mainAnswerId = 0 $internaluserQuery and mct.categoryId <=14 and mct.categoryId > 1 group by m1.msgId, mct.categoryId";
		$returnData['category'] = $this->dbHandle->query($query)->result_array();
		
		//miscellaneous category data
		$query = "SELECT m1.msgId as queId, m2.msgId as ansId, '1' as categoryId FROM messageTable as m1 join messageTable m2 on m1.msgId = m2.parentId left join messageCategoryTable mct on mct.threadId=m1.msgId and mct.categoryId > 1 WHERE m2.creationDate >= NOW() - INTERVAL 7 DAY and m1.fromOthers = 'user' and m1.status in ('live', 'closed') and m2.status in ('live', 'closed') and m1.mainAnswerId = -1 and m2.mainAnswerId = 0 $internaluserQuery and mct.categoryId is NULL group by m1.msgId";
		$returnData['miscellaneous'] = $this->dbHandle->query($query)->result_array();
		return $returnData;
	}


	/*
	 Function to get all discussions information in which atleast 1 comment has posted in last 24 hours and get all the users information who had followed these discussions.
	*/
	public function getUserDetailFollowedDiscsussion(){
		$this->initiateModel();

		//get all discussion having atleast 1 comment in last 24 hrs
		$query = "select mt.threadId, count(*) as commentCount from messageTable mt, messageTable mt1 where mt.threadId= mt1.msgId and mt1.status in ('live','closed') and mt.status = 'live' and mt.fromOthers = 'discussion' and mt.mainAnswerId>0 and mt.mainAnswerId = mt.parentId and mt.creationDate > DATE_SUB(NOW(),INTERVAL 1 DAY) group By mt.threadId ";

		$result = $this->dbHandle->query($query)->result_array();

		foreach($result as $key=>$value){
			$discussionIdArray[] = $value['threadId']; 
			$commentCountArray[$value['threadId']] = $value['commentCount'];
		}
		$discussionIds = implode(',',$discussionIdArray);

		$resultArray = array();
		if($discussionIds != ''){

			//get discussion and user details who have followed these discussions
			$sql = "select tuft.userId,tu.firstname,tu.lastname,tu.email, mt.threadId, mt.msgTxt as discussionTxt, mt.creationDate from tuserFollowTable tuft, messageTable mt, tuser tu  where tuft.userId = tu.userid and tuft.entityId = mt.parentId and tuft.entityType='discussion' and tuft.status='follow' and tuft.entityId in ($discussionIds)";

			$result2 = $this->dbHandle->query($sql)->result_array();

			$sql1 = "select count(*) as totalComment, threadId from messageTable where threadId in ($discussionIds) and mainAnswerId>0 and mainAnswerId = parentId group By threadId";

			$result3 = $this->dbHandle->query($sql1)->result_array();

			foreach($result3 as $key=>$value){
				$totalComment[$value['threadId']] = $value['totalComment'];
			}

			foreach($result2 as $key=>$value){
				$resultArray[$value['userId']]['userDetails'] = array('userId'=>$value['userId'],'firstname'=>$value['firstname'],'lastname'=>$value['lastname'],'email'=>$value['email']);
				$resultArray[$value['userId']]['discussionDetails'][] = array('threadId'=>$value['threadId'],'discussionTxt'=>$value['discussionTxt'],'commentCountIn24'=>$commentCountArray[$value['threadId']],'totalCommentCount'=>$totalComment[$value['threadId']],'seoUrl'=>getSeoUrl($value['threadId'],'discussion',$value['msgTxt'],array(),'NA',date('Y-m-d',strtotime($value['creationDate'])))); 
			}
		}
		
		return $resultArray;

	}

	function automodrationUploader($tableName, $data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch($tableName, $data); 
		return $this->dbHandle->insert_id();	
	}
    
    /**
     * @author Abhinav
     * @param int $subCategoryId Should be greater than zero.
     * @param date $dateToCheckFor Should be valid date.
     * @param int $tagId Should be valid tagId greater than zero.
     * @return array
     */
    public function getQuestionByPopularityNew($subCategoryId, $dateToCheckFor, $tagId, $noOfThreadsToBePicked = 0){
        if(empty($subCategoryId) || $subCategoryId <= 0 || empty($dateToCheckFor) || empty($tagId) || $tagId <= 0 || $noOfThreadsToBePicked <= 0){
            return array();
        }
        $exceptionalThreads = array();
        $result = array();
        $dateToCheckForEpoch = strtotime($dateToCheckFor);
        $this->initiateModel('read');
        if($subCategoryId > 0){
            $sql    = "SELECT mt.threadId, mt.creationDate, cpce.exceptionFlag, cpce.modifiedAt, mt.msgCount FROM "
                    . " course_pages_content_exceptions cpce INNER JOIN messageTable mt ON(cpce.contentTypeId = mt.threadId)"
                    . " WHERE mt.status IN ('live','closed') AND mt.fromOthers = 'user' AND cpce.subCategoryId = ?"
                    . " AND cpce.contentType = 'qna' AND exceptionFlag IN('boost','noise') AND mt.parentId = 0"
                    . " AND mt.mainAnswerId = -1 ORDER BY cpce.modifiedAt DESC";
            $resultSet = $this->dbHandle->query($sql, array($subCategoryId))->result_array();
            foreach($resultSet as $data){
                if($data['exceptionFlag'] == 'boost' && $data['msgCount'] > 0){
                    if($dateToCheckForEpoch < strtotime($data['creationDate'])){
                        $result['boost'][] = $data['threadId'];
                    }
                }elseif($data['exceptionFlag'] == 'noise'){
                    $result['noise'][] = $data['threadId'];
                }
            }
        }
        
        if(is_array($resultSet['boost']) && count($resultSet['boost']) >= $noOfThreadsToBePicked){
            return $result;
        }else{
            $sql    = "SELECT mt1.threadId, mt1.creationDate, mt1.msgCount, tqt.qualityScore"
                    . " FROM messageTable mt1 INNER JOIN tags_content_mapping tcm ON (mt1.threadId = tcm.content_id)"
                    . " LEFT JOIN threadQualityTable tqt ON(tcm.content_id = tqt.threadId) "
                    . " WHERE mt1.creationDate >= ? AND mt1.status IN ('live' , 'closed') "
                    . " AND tcm.status = 'live' AND tcm.content_type = 'question' AND tcm.tag_type IN('objective','manual')"
                    . " AND tcm.tag_id = ? AND mt1.fromOthers = 'user' AND mt1.parentId = 0 AND mt1.mainAnswerId = -1"
                    . ((is_array($result['noise']) && count($result['noise']) > 0)?" AND mt1.threadId NOT IN (".  implode(",", $result['noise']).")":"")
                    . " AND (mt1.listingTypeId IS NULL OR mt1.listingTypeId = 0) AND mt1.msgCount > 0 ORDER BY tqt.qualityScore DESC";
            $resultSet  = $this->dbHandle->query($sql,array($dateToCheckFor." 00:00:00",$tagId))->result_array();
            foreach($resultSet as $data){
                if(!in_array($data['threadId'], $result['boost'])){
                    $result['normal'][$data['threadId']] = array('threadId' => $data['threadId'], 'creationDate' => $data['creationDate'], 'qualityScore' => $data['qualityScore']);
                }
            }
        }
        
        return $result;
    }
    
    /**
     * @author Abhinav
     * @param int $subCategoryId Should be greater than zero.
     * @param date $dateToCheckFor Should be valid date.
     * @param int $tagId Should be valid tagId greater than zero.
     * @return array
     */
    public function getDiscussionsByPopularityNew($subCategoryId, $dateToCheckFor, $tagId, $noOfThreadsToBePicked = 0){
        if(empty($subCategoryId) || $subCategoryId <= 0 || empty($dateToCheckFor) || empty($tagId) || $tagId <= 0 || $noOfThreadsToBePicked <= 0){
            return array();
        }
        $exceptionalThreads = array();
        $result = array();
        $dateToCheckForEpoch = strtotime($dateToCheckFor);
        $this->initiateModel('read');
        if($subCategoryId > 0){
            $sql    = "SELECT mt.threadId, mt.creationDate, cpce.exceptionFlag, cpce.modifiedAt, mt.msgCount FROM "
                    . " course_pages_content_exceptions cpce INNER JOIN messageTable mt ON(cpce.contentTypeId = mt.threadId)"
                    . " WHERE mt.status IN ('live','closed') AND mt.fromOthers = 'discussion' AND cpce.subCategoryId = ?"
                    . " AND cpce.contentType = 'discussion' AND exceptionFlag IN('boost','noise') AND mt.parentId = mt.threadId"
                    . " AND mt.mainAnswerId = 0 ORDER BY cpce.modifiedAt DESC";
            $resultSet = $this->dbHandle->query($sql, array($subCategoryId))->result_array();
            foreach($resultSet as $data){
                if($data['exceptionFlag'] == 'boost' && $data['msgCount'] > 0){
                    if($dateToCheckForEpoch < strtotime($data['creationDate'])){
                        $result['boost'][] = $data['threadId'];
                    }
                }elseif($data['exceptionFlag'] == 'noise'){
                    $result['noise'][] = $data['threadId'];
                }
            }
        }
        
        if(is_array($resultSet['boost']) && count($resultSet['boost']) >= $noOfThreadsToBePicked){
            return $result;
        }else{
            $sql    = "SELECT mt1.threadId, mt1.creationDate, tqt.qualityScore"
                    . " FROM messageTable mt1 INNER JOIN tags_content_mapping tcm ON (mt1.threadId = tcm.content_id)"
                    . " LEFT JOIN threadQualityTable tqt ON(tcm.content_id = tqt.threadId)"
                    . " WHERE mt1.creationDate >= ? AND mt1.status IN ('live' , 'closed')"
                    . " AND tcm.status = 'live' AND tcm.content_type = 'discussion' AND tcm.tag_type IN('objective','manual')"
                    . " AND tcm.tag_id = ? AND mt1.fromOthers = 'discussion' AND mt1.parentId = mt1.threadId  AND mt1.mainAnswerId = 0"
                    . ((is_array($result['noise']) && count($result['noise']) > 0)?" AND mt1.threadId NOT IN (".  implode(",", $result['noise']).")":"")
                    . " AND (mt1.listingTypeId IS NULL OR mt1.listingTypeId = 0) AND mt1.msgCount > 0 ORDER BY tqt.qualityScore DESC";
            $resultSet  = $this->dbHandle->query($sql,array($dateToCheckFor." 00:00:00",$tagId))->result_array();
            foreach($resultSet as $data){
                if(!in_array($data['threadId'], $result['boost'])){
                    $result['normal'][$data['threadId']] = array('threadId' => $data['threadId'], 'creationDate' => $data['creationDate'], 'qualityScore' => $data['qualityScore']);
                }
            }
        }
        
        return $result;
    }
    
    public function getTimeOfRecentContentOnThreads($threadIds = array(), $threadType){
        if(empty($threadIds) || !in_array($threadType, array('discussion','question'))){
            return array();
        }
        $result = array();
        $sql    = "SELECT threadId, MAX(creationDate) AS creationDate FROM messageTable"
                . " WHERE status IN ('live','closed')";
        if($threadType == 'question'){
            $sql    .= " AND fromOthers = 'user' AND parentId = threadId AND mainAnswerId = 0";
        }else{
            $sql    .= " AND fromOthers = 'discussion' AND parentId = mainAnswerId AND mainAnswerId > 0";
        }
        $sql    .= " AND threadId IN (?) GROUP BY 1";
        $this->initiateModel('read');
        $resultSet  = $this->dbHandle->query($sql, array($threadIds))->result_array();
        foreach($resultSet as $data){
            $result[$data['threadId']] = $data['creationDate'];
        }
        return $result;
    }
    
    public function getTopQuestionsRelatedData($threadIds = array()){
        if(!is_array($threadIds)){
            return array();
        }
        $threadIds = array_filter($threadIds);
        if(count($threadIds) <= 0){
            return array();
        }
        $sql    = " SELECT msgId, creationDate, msgTxt, threadId, viewCount, msgCount FROM messageTable"
                . " WHERE msgId IN (?) AND fromOthers = 'user' AND status IN('live','closed') ";
        $result = array();
        $this->initiateModel('read');
        $resultSet  = $this->dbHandle->query($sql, array($threadIds))->result_array();
        foreach($resultSet as $data){
            $result[$data['threadId']]['msgId']         = $data['msgId'];
            $result[$data['threadId']]['creationDate']  = $data['creationDate'];
            $result[$data['threadId']]['msgTxt']        = $data['msgTxt'];
            $result[$data['threadId']]['viewCount']     = $data['viewCount'];
            $result[$data['threadId']]['msgCount']      = $data['msgCount'];
        }
        return $result;
    }
    
    public function getTopDiscussionsRelatedData($threadIds = array()){
        if(!is_array($threadIds)){
            return array();
        }
        $threadIds = array_filter($threadIds);
        if(count($threadIds) == 0){
            return array();
        }
        $sql    = " SELECT mt.msgId, mt.creationDate, mt.msgTxt, mt.parentId , mt.threadId as discussionId, mt.viewCount, mt.msgCount, md.description FROM messageTable mt"
                . " LEFT JOIN messageDiscussion md ON(mt.msgId = md.threadId)"
                . " WHERE mt.threadId IN (?) AND mt.fromOthers = 'discussion'"
                . " AND mt.mainAnswerId <= 0 AND (mt.parentId = 0 OR mt.parentId = mt.threadId) AND mt.status IN('live','closed')";
        $this->initiateModel('read');
        $resultSet  = $this->dbHandle->query($sql, array($threadIds))->result_array();
        //_p($resultSet);
        $result = array();
        foreach($resultSet as $data){
            if($data['parentId'] == 0){
                $result[$data['msgId']]['viewCount']     = $data['viewCount'];
            }elseif ($data['parentId'] == $data['discussionId']) {
                $result[$data['discussionId']]['threadId']      = $data['discussionId'];
                $result[$data['discussionId']]['msgId']         = $data['msgId'];
                $result[$data['discussionId']]['creationDate']  = $data['creationDate'];
                $result[$data['discussionId']]['msgTxt']        = $data['msgTxt'];
                $result[$data['discussionId']]['description']   = $data['description'];
                $result[$data['discussionId']]['msgCount']      = $data['msgCount'];
            }
        }
        return $result;
    }


	function getAllCCPrograms(){

	$this->initiateModel('read');
	$query = "SELECT programId, programName FROM campusConnectProgram where status ='live'";
	$programData = $this->dbHandle->query($query)->result_array();
	return $programData;
	}
	   /*get user details of the user who had posted the question*/
    function getUserDetailsPostedQues($threadId){
    	$this->initiateModel('write');
    	$queryCmd = "select tu.mobile,tu.email,tu.userId,tu.displayName as dName,mt.tracking_keyid from tuser tu inner join messageTable mt on (mt.userId=tu.userId) where msgId = ?";
        $resultSet = $this->dbHandle->query($queryCmd,array($threadId))->row_array();
        return $resultSet;
    }

    function moveQuestionToInstitute($threadId,$courseId,$instId,$isPaid){
    	$this->initiateModel('write');
       
       	$userDetails = $this->getUserDetailsPostedQues($threadId);
       	$dName = $userDetails['dName'];
       	$mobile = $userDetails['mobile'];
       	$email = $userDetails['email'];
       	$quesOwner = $userDetails['userId'];
       	$trackingKeyId = $userDetails['tracking_keyid'];

       	$queryCmd = "insert into questions_listing_response (courseId,instituteId,messageId,creationTime,userId) values (?,?,?,now(),?)";
        $query = $this->dbHandle->query($queryCmd,array($courseId,$instId,$threadId,$quesOwner));

    }

    function moveQuestionToAnA($threadId){
    	$this->initiateModel('write');

    	$userDetails = $this->getUserDetailsPostedQues($threadId);
       	$dName = $userDetails['dName'];
       	$mobile = $userDetails['mobile'];
       	$email = $userDetails['email'];
       	$quesOwner = $userDetails['userId'];
       	
       	$queryCmd = "select courseId,instituteId from questions_listing_response where userId=? and messageId=? and status=? LIMIT 1";
        $resultSet = $this->dbHandle->query($queryCmd,array($quesOwner,$threadId,'live'))->row_array();
        $courseId = $resultSet['courseId'];
        $instId = $resultSet['instituteId'];

        $queryCmd = "update questions_listing_response set status='deleted' where courseId=? and instituteId=? and messageId=? and userId=?";
        $query = $this->dbHandle->query($queryCmd,array($courseId,$instId,$threadId,$quesOwner));

        $queryCmd = "update tempLMSTable set action=? where userId=? and displayName=? and listing_type_id=? and email=? AND listing_subscription_type='paid' and action = 'Asked_Question_On_Listing'";
        $query = $this->dbHandle->query($queryCmd,array('Viewed_Listing',$quesOwner,$dName,$courseId,$email));
      
    }

    function getTagInfo($entityId, $entityType){
         if(!is_resource($dbHandleSent)){
                $this->initiateModel();
        }else{
                $this->dbHandle = $dbHandleSent;
        }
        $this->dbHandle->select('tag_id');
        $this->dbHandle->from('tags_entity');
        $this->dbHandle->where('entity_id', $entityId);
        $this->dbHandle->where('entity_type', $entityType);
        $this->dbHandle->where('status', 'live');
        $result   = $this->dbHandle->get()->result_array();
        return $result[0];
    }

    function getEntityCount($entityId, $entityType, $contentType ){
        if(!is_resource($dbHandleSent)){
                $this->initiateModel();
        }else{
                $this->dbHandle = $dbHandleSent;
        }
        $this->dbHandle->select('count(distinct tcm.content_id) as totalCount, `t`.`tags`, `te`.`tag_id` as tagId');
        $this->dbHandle->from('tags_entity te');
        $this->dbHandle->join('tags_content_mapping tcm','tcm.tag_id=te.tag_id','INNER');
        $this->dbHandle->join('messageTable mt','mt.msgId=tcm.content_id','INNER');
        $this->dbHandle->join('tags t','te.tag_id=t.id','INNER');
        $this->dbHandle->where_in('te.entity_id', $entityId);
        $this->dbHandle->where_in('te.entity_type', $entityType);
        $this->dbHandle->where_in('mt.status', array('live','closed'));
        $this->dbHandle->where('tcm.status', 'live');
        $this->dbHandle->where('t.status', 'live');
        $this->dbHandle->where('tcm.content_type', $contentType);
        $result   = $this->dbHandle->get()->result_array();
        return $result[0];
    }	

    function getAnsweredQuestiontIdBasedOnTagId($tagId,$limit){
    	$cacheLib = $this->load->library('cacheLib');
		$cacheKey = "answeredQuestionId".$tagId;
		$res = $cacheLib->get($cacheKey);
		if($res == 'ERROR_READING_CACHE'){
			$queryCmd = "select distinct mt.threadId from messageTable mt 
					INNER JOIN tags_content_mapping tcm 
					ON tcm.content_id = mt.threadId
					INNER JOIN threadQualityTable tqt
					ON tqt.threadId=mt.threadId
					where tcm.tag_id=? 
					and tcm.content_type='question' 
					and tcm.status='live' 
					AND mt.status IN ('live','closed') 
					AND mt.parentId = mt.threadId
					AND (select status from messageTable where msgId=mt.threadId) IN ('live','closed') 
					AND mt.fromOthers='user'
					order by tqt.qualityScore desc limit 0, $limit";
			$resultSet = $this->dbHandle->query($queryCmd,array($tagId))->result_array();
			foreach($resultSet as $key=>$value){
	        		$questionId[] = $value['threadId'];
	        }
	        $cacheLib->store($cacheKey,$questionId,86400); // Cache for 1 Day
	        return $questionId;
		}else{
			return $res;
		}
    }

    function sortQuestionIdsBasedOnPopularity($questionIds, $limit=3){
        $queryCmd = "select msgId, msgTxt from threadQualityTable tqt, messageTable mt where tqt.threadId=mt.msgId and tqt.threadId in (?) and mt.status in ('live','closed') order by tqt.qualityScore desc limit 0, $limit";
        $resultSet = $this->dbHandle->query($queryCmd,array($questionIds))->result_array();
        return $resultSet;
    }

    function getQuestionList($time){
    	if(!is_resource($dbHandleSent)){
                $this->initiateModel();
        }else{
                $this->dbHandle = $dbHandleSent;
        }
    	$queryCmd = "select msgId, msgTxt from messageTable where parentId=0 and mainAnswerId=-1 and status in ('live','closed') and fromOthers='user' and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(creationDate))<='".$time."'";
    	$resultSet = $this->dbHandle->query($queryCmd)->result_array();
    	$result = array();
    	foreach($resultSet as $key=>$value){
    		$result[$value['msgId']] = $value['msgTxt'];
    	}
    	return $result;
    }

    function checkIfEditRequested($allMsgIds){
    	if(!is_resource($dbHandleSent)){
                $this->initiateModel();
        }else{
                $this->dbHandle = $dbHandleSent;
        }
    	if(!empty($allMsgIds)){
		$queryCmd = "select entityId, editStatus from moderation_editRequests where entityId in (?) and status = 'live'";
    	$resultSet = $this->dbHandle->query($queryCmd, array($allMsgIds))->result_array();
    	$result = array();
    	foreach($resultSet as $key=>$value){
    		$result[$value['entityId']] = $value['editStatus'];
    	}
    	return $result;
    	}
    }

    function getEditRequestedAnswerIds($type){
    	if(!is_resource($dbHandleSent)){
                $this->initiateModel();
        }else{
                $this->dbHandle = $dbHandleSent;
        }
        if($type == 'secondMail'){
        	$queryCmd = "SELECT entityId, reasonToEdit, comment from moderation_editRequests where creation_date < DATE_SUB(NOW(), INTERVAL 48 HOUR) and status = 'live' and editStatus = 'no' and isMailSent = 'yes' and isSecondMailSent = 'no' ";
        }else if($type == 'deleteAnswers'){
        	$queryCmd = "select entityId, reasonToEdit from moderation_editRequests where creation_date < DATE_SUB(NOW(), INTERVAL 72 HOUR) and status = 'live' and editStatus = 'no' and isMailSent = 'yes' and isSecondMailSent = 'yes' ";
        }else{
        	return;
        }
        
    	$resultSet = $this->dbHandle->query($queryCmd)->result_array();	
    	return $resultSet;
    }

    function getQuestionIdsAndUserIdOfAnswers($secondMailAnswerIds){
    	if(empty($secondMailAnswerIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
        }else{
            $this->dbHandle = $dbHandleSent;
        }

        $queryCmd = "Select msgId, parentId, userId from messageTable where msgId in (?) and status = 'live'";
        $resultSet = $this->dbHandle->query($queryCmd, array($secondMailAnswerIds))->result_array();	
    	return $resultSet;
    }

    function getQuestionText($questionIds){
    	if(empty($questionIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
        }else{
            $this->dbHandle = $dbHandleSent;
        }

        $queryCmd = "Select msgId, msgTxt from messageTable where msgId in (?) and status = 'live'";
        $resultSet = $this->dbHandle->query($queryCmd, array($questionIds))->result_array();	
    	return $resultSet;
    }

    function getUserNameAndEmail($userIds){
    	if(empty($userIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
        }else{
            $this->dbHandle = $dbHandleSent;
        }

        $queryCmd = "Select userid, email, firstname from tuser where userid in (?)";
        $resultSet = $this->dbHandle->query($queryCmd, array($userIds))->result_array();	
    	return $resultSet;
    }

    function updateAnswerMailStatus($answerIdsToBeUpdated){
    	if(empty($answerIdsToBeUpdated)){
    		return;
    	}
        $this->initiateModel('write');
		$queryCmd = "Update moderation_editRequests set isSecondMailSent = 'yes' where entityId in (?) and status = 'live' and editStatus = 'no'";
        $this->dbHandle->query($queryCmd, array($answerIdsToBeUpdated));
        if ($this->dbHandle->affected_rows() > 0)
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
    }

    function deleteAnswersFromMessageTable($finalAnswerIdsToDelete){
        if(empty($finalAnswerIdsToDelete)){
                return;
        }
        $this->initiateModel('write');
        $queryCmd = "Update messageTable set status = 'deleted' where msgId in (?)";
        $this->dbHandle->query($queryCmd, array($finalAnswerIdsToDelete));
        if ($this->dbHandle->affected_rows() > 0)
        {
                //Fetch details for all answers
                $queryCmd = "SELECT msgId, threadId, userId FROM messageTable WHERE msgId in (?)";
                $result = $this->dbHandle->query($queryCmd, array($finalAnswerIdsToDelete))->result_array();
                foreach ($result as $res){
                        //Reduce the no of answers by 1
                        $queryCmd="update messageTable set msgCount = (msgCount - 1) where msgId = ?";
                        $query = $this->dbHandle->query($queryCmd,array($res['threadId']));

                        //Update in Redis
                        $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
                        $this->userinteractioncachestoragelibrary->deleteEntity($res['userId'],$res['threadId'],'question','answer');

                        //Add call for re-indexing
                        modules::run('search/Indexer/addToQueue', $res['threadId'], 'question');
                }
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }

    function deleteAnswersFromModerationEdit($finalAnswerIdsToDelete){
    	if(empty($finalAnswerIdsToDelete)){
    		return;
    	}
    	$this->initiateModel('write');
		$queryCmd = "Update moderation_editRequests set status = 'deleted' where entityId in (?)";
        $this->dbHandle->query($queryCmd, array($finalAnswerIdsToDelete));
        if ($this->dbHandle->affected_rows() > 0)
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
    }

    function getAllCampusReps(){
	    if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }

        $queryCmd = "select capt.userId, camt.instituteId, camt.courseId from CA_ProfileTable capt, CA_MainCourseMappingTable camt where capt.id = camt.caId and capt.profileStatus = 'accepted' and camt.status = 'live'";
        $resultSet = $this->dbHandle->query($queryCmd)->result_array();	
      
    	return $resultSet;	
    }

    function getContributionMailerUsers($type, $userIds){
	    
	    if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    
	    $dateCheck = '';
	    
	    if($type == 'first') {
	    	$dateCheck = 'and creationDate > DATE_SUB(NOW(), INTERVAL 90 DAY)';
	    	$userIdCheck = 'and userId not in (?)';
	    }else if($type == 'second') {
		    $dateCheck = 'and creationDate < DATE_SUB(NOW(), INTERVAL 90 DAY) and creationDate > DATE_SUB(NOW(), INTERVAL 180 DAY)';
	    	$userIdCheck = 'and userId not in (?)';
        }else {
        	$userIdCheck = 'and userId in (?)';
        	$dateCheck = '';
        }

        $queryCmd = "select userId, msgId, parentId from messageTable m where status = 'live' and fromOthers='user' and (select status from messageTable where msgId=m.threadId) in ('live','closed') and mainAnswerId = 0 $userIdCheck $dateCheck order by creationDate desc";
        $resultSet = $this->dbHandle->query($queryCmd, array($userIds))->result_array();
        if($type == 'noCheck'){
	        foreach ($resultSet as $key => $value) {
	    		$returnData[$value['userId']]['answerIds'][] = $value['msgId'];
	    		$returnData[$value['userId']]['questionIds'][] = $value['parentId'];
	        }
        }else {	
	        foreach ($resultSet as $key => $value) {
	    		$returnData[$value['userId']] = $value['msgId'];
	        }
	    }
    	return $returnData;	
    }

    function getQuestionViewCount($questionIds){
    	if(empty($questionIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    $queryCmd = "select SUM(viewCount) as total from messageTable where msgId in (?) and status in ('live', 'closed') ";
        $resultSet = $this->dbHandle->query($queryCmd, array($questionIds))->result_array();
        return reset($resultSet);
    }

    function getAnswerUpvotesCount($answerIds){
    	if(empty($answerIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    $queryCmd = "select SUM(digUp) as totalUpVotes from messageTable where msgId in (?) and status = 'live' ";
        $resultSet = $this->dbHandle->query($queryCmd, array($answerIds))->result_array();
        return reset($resultSet);
    }

    function getTopicsFollowed($totalMailUsers){
    	if(empty($totalMailUsers)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    $queryCmd = "select userId, entityId from tuserFollowTable where userId in (?) and entityType = 'tag' and status = 'follow' ";
        $resultSet = $this->dbHandle->query($queryCmd, array($totalMailUsers))->result_array();
        foreach ($resultSet as $key => $value) {
        	$finalResult[$value['userId']][] = $value['entityId'];
        }
        return $finalResult;
    }

    function getTagsEntityFollowed($tagIds){
    	if(empty($tagIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    
	    $queryCmd = "select distinct content_id, m.msgTxt from tags_content_mapping tcm, messageTable m where m.msgId = tcm.content_id and  tcm.tag_id in (?) and tcm.content_type = 'question' and m.msgCount = 0 and m.parentId = 0 and tcm.status = 'live' and m.status = 'live' and m.fromOthers='user' and m.creationDate > DATE_SUB(NOW(), INTERVAL 30 DAY) order by m.creationDate desc limit 5";
        $resultSet = $this->dbHandle->query($queryCmd, array($tagIds))->result_array();
        foreach ($resultSet as $key => $value) {
        	$returnData[$value['content_id']] = $value['msgTxt'];
        }
        return $returnData;
    }

    function getLastAnsweredQuestionOfUser($lastAnsweredArr){
		if(empty($lastAnsweredArr)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    $queryCmd = "select msgTxt from messageTable where msgId in (?) and status in ('live', 'closed')";
        $resultSet = $this->dbHandle->query($queryCmd, array($lastAnsweredArr))->result_array();
        return $resultSet[0]['msgTxt'];
    }


    function getCountOfUnansQuestions($tagIds){
    	//_p($tagIds);die;
		if(empty($tagIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    $queryCmd = "select count(tcm.content_id) as count , tcm.tag_id  from tags_content_mapping tcm, messageTable m where m.msgId = tcm.content_id and  tcm.tag_id in (?) and tcm.content_type = 'question' and m.msgCount = 0 and m.parentId = 0 and tcm.status = 'live' and m.status in ('live','closed') and m.fromOthers='user' and m.creationDate > DATE_SUB(NOW(), INTERVAL 30 DAY) group by tcm.tag_id order by count desc limit 5";
        $resultSet = $this->dbHandle->query($queryCmd, array($tagIds))->result_array();
        foreach ($resultSet as $key => $value) {
        	$returnData[$value['tag_id']] = $value['count'];
        }

        return $returnData;
    }

    function getTagNames($tagIds){
		if(empty($tagIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    $queryCmd = "select tags, id from tags where id in (?) and status = 'live'";
        $resultSet = $this->dbHandle->query($queryCmd, array($tagIds))->result_array();
        foreach ($resultSet as $key => $value) {
        	$returnData[$value['id']] = $value['tags'];
        }
        return $returnData;
    }

    function getQuestionsBasedOnCourse($allCourseIds){
		if(empty($allCourseIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    $resultSet = array();
	    $queryCmd = "select qlr.userId, qlr.messageId, mt.msgTxt from questions_listing_response qlr, messageTable mt where qlr.messageId = mt.msgId and qlr.courseId in (?) and mt.msgCount = 0 and mt.parentId = 0 and qlr.status = 'live' and mt.status  = 'live' and qlr.creationTime > DATE_SUB(NOW(), INTERVAL 10 DAY) order by qlr.creationTime desc limit 5";
        $resultSet = $this->dbHandle->query($queryCmd, array($allCourseIds))->result_array();

        foreach ($resultSet as $key => $value) {
        	$returnData[$key]['question'] = $value['msgTxt'];
        	$returnData[$key]['msgId'] = $value['messageId'];
        }
        return $returnData;
    }

    function getTagsMappedToInst($allInstIds){
		if(empty($allInstIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    $queryCmd = "select tag_id, entity_id from tags_entity where entity_id in (?) and entity_type = 'institute' and status = 'live'";
        $resultSet = $this->dbHandle->query($queryCmd, array($allInstIds))->result_array();
        foreach ($resultSet as $key => $value) {
        	$returnData[$value['entity_id']] = $value['tag_id'];
        }
        return $returnData;
    }

    function getQuestionsBasedOnInst($tagIds, $excludeMsgIds, $type){
    	if(empty($tagIds)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    if(!empty($excludeMsgIds)){
	    	$excludeCourseMsgIds = 'and m.msgId not in (?)';
	    }else {
	    	$excludeCourseMsgIds = '';
	    }

	    if($type == 'inst'){
	    	$limitCheck = 'limit 5';
	    	$intervalCheck = 'and m.creationDate > DATE_SUB(NOW(), INTERVAL 10 DAY)';	    
	    }else if($type == 'tag'){
	    	$limitCheck = 'limit 100';
	    	$intervalCheck = 'and m.creationDate > DATE_SUB(NOW(), INTERVAL 30 DAY)';	    
		}else {
			return;			
		}
	    
	    $queryCmd = "select distinct content_id, m.msgTxt from tags_content_mapping tcm, messageTable m where m.msgId = tcm.content_id and  tcm.tag_id in (?) and tcm.content_type = 'question' and m.msgCount = 0 and m.parentId = 0 and tcm.status = 'live' $excludeCourseMsgIds and m.status = 'live' and m.fromOthers='user' $intervalCheck order by m.creationDate desc $limitCheck";
        $resultSet = $this->dbHandle->query($queryCmd, array($tagIds, $excludeMsgIds))->result_array();
        foreach ($resultSet as $key => $value) {
        	$returnData[$key]['question'] = $value['msgTxt'];
        	$returnData[$key]['msgId'] = $value['content_id'];
        }
      
        return $returnData;
    }

    function getTagsMappedToCourseType($mappingArr, $type){
		if(empty($mappingArr) || empty($type)){
    		return;
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
	    if($type == 'stream'){
	    	$entityCheck = "and entity_type = 'Stream'";
	    }else if($type == 'substream'){
	    	$entityCheck = "and entity_type = 'Sub-Stream'";
	    }else if($type == 'spec'){
	    	$entityCheck = "and entity_type = 'Specialization'";
	    }else {
	    	return;
	    }
	    
	    $queryCmd = "select tag_id, entity_id from tags_entity where entity_id in (?) $entityCheck and status = 'live'";
        $resultSet = $this->dbHandle->query($queryCmd, array($mappingArr))->result_array();
        foreach ($resultSet as $key => $value) {
        	$returnData[$value['entity_id']] = $value['tag_id'];
        }
        return $returnData;
    }

    function getCommentsOnTopic($threadNo, $start=0, $limit=5){
    	if(empty($threadNo)){
    		return array();
    	}
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }
    	//This query gets the comments to the entity
        $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage,(select mtb.status from messageTable mtb where mtb.msgId = ?) blogStatus from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.threadId = ? and m1.parentId = ? and m1.status NOT IN ('deleted','abused') order by m1.creationDate DESC limit $start,$limit";
        $query = $this->dbHandle->query($queryCmd, array($threadNo, $threadNo, $threadNo));
        
        $msgArray = array();
        foreach ($query->result_array() as $key => $row) {
        	$msgArray[$key] = $row;
            $mainAnswerIds[] = $row['msgId'];
        }
        
        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $this->dbHandle->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
            $totalRows = $row->totalRows;   // total comments
        }
        
    	$mainArr = array();
        $mainArr['Replies'] = $this->getReplyOnComment($threadNo, $mainAnswerIds);
        $mainArr['MsgTree'] = $msgArray;
        $mainArr['totalRows'] = $totalRows;
       	return $mainArr;
	}

    function getReplyOnComment($threadNo, $mainAnswerIds, $start=0, $limit=5){
    	if(empty($threadNo) || count($mainAnswerIds)<=0){
    		return array();
    	}
    	
    	if(!is_resource($dbHandleSent)){
            $this->initiateModel();
	    }else{
            $this->dbHandle = $dbHandleSent;
	    }

	    $commentArray = array();
    	//This query gets the replies to the comments
	    foreach ($mainAnswerIds as $key => $mainAnsId) {
	    	if($threadNo!='' && $mainAnsId!=''){
			    $queryCmd = "select m1.*,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage,if((m1.mainAnswerId<=0),true,(select displayname from tuser tp,messageTable mp where tp.userid=mp.userId and mp.msgId = m1.parentId)) parentDisplayName from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.threadId = ? and m1.parentId != ? and m1.parentId > 0 and m1.mainAnswerId IN (".$mainAnsId.") and m1.status NOT IN ('deleted','abused') order by m1.creationDate DESC limit $start, $limit";
			    $query = $this->dbHandle->query($queryCmd, array($threadNo,$threadNo));
			    if(count($query->result_array())>0){
			    	foreach ($query->result_array() as $key => $row) {
			    		$commentArray[] = $row;
			    	}
			    }
			}
	    }
		return $commentArray;
	}
}
?>
