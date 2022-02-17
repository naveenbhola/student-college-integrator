<?php
/* 
    Model for database related operations related to message board.
    Following is the example this model can be used in the server controllers.
    $this->load->model('AnAModel');
    $this->AnAModel->getTotalQnACountForCriteria('149,12,11,3,8,5,10',$dbHandle); 	 
*/

class AnAModel extends MY_Model {
	private $dbHandle = '';
	private $CI;
	private $dateCheckForAnAStart = '2008-05-11';
	
	function __construct(){
		parent::__construct('AnA');
		$this->CI = &get_instance();
		$this->CI->load->helper('messageBoard/ana');
	}

	private function initiateModel($operation='read'){
		$appId = 1;	
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	        $this->dbHandle = $this->getWriteHandle();
		}
	}
		
	function getUnansweredQuestions($userId, $start, $count){
		//Create the DB Handler
		$this->initiateModel();

		//Get the Unanswered questions on the basis of the Date.
                $date = date("Y-m-d");
                $x = 60;
                $startDate = date("2008-04-01");
    
                do {
                    $date = strtotime("-".$x." days",strtotime($date));
                    $date = date ( 'Y-m-j' , $date );
                    $queryCmd = "SELECT m1.msgId as questionId, m1.msgTxt as questionTitle, m1.creationDate as questionCreationDate, m1.viewCount, m1.status, m1.msgCount as answerCount, m1.digUp as UpVote, m1.digDown as DownVote ,ifnull((SELECT mmd.description FROM messageDiscussion mmd WHERE mmd.threadId = m1.msgId LIMIT 1),'') questionDescription FROM messageTable m1 WHERE m1.status IN ('live','closed') AND m1.fromOthers='user' AND m1.parentId=0 AND m1.msgCount = 0 AND m1.creationDate > ? AND m1.listingTypeId=0 AND m1.creationDate < NOW() ORDER BY creationDate DESC LIMIT $start,$count";
                    $query = $this->dbHandle->query($queryCmd, array($date));
                    $x = $x + ceil($start/50);
                }while(count($query->result_array())<$count && ($date>$startDate) );

		$response = array();
		$i = 0;
	        foreach ($query->result_array() as $row){
	            $response[$i] = $row;
		    $response[$i]['ownerLevel'] = $this->getOwnerLevel($row['ownerId']);
		    $response[$i]['ownerLevel'] = $response[$i]['ownerLevel']['levelName'];
		    $response[$i]['shareCount'] = $this->getShareCount($row['questionId']);
		    $response[$i]['followerCount'] = $this->getFollowerCount($row['questionId']);
		    $response[$i]['tags'] = $this->getTags($row['questionId']);
		    $i++;
	        }
	        return $response;
	}

	function getShareCount($entityId, $entityType = 'question'){
		return '5';
	}

	function getFollowerCount($entityId, $entityType = 'question'){
		$this->initiateModel();
		
		$queryCmd = "select count(*) as followCount from tuserFollowTable where entityId = ? and entityType = ? and status = 'follow'";
                
		$query = $this->dbHandle->query($queryCmd, array($entityId,$entityType));
		
		$result = $query->result_array();
		
		return $result[0]['followCount'];
        }
	
	function checkIfUserHasFollowedAnEntity($entityId, $entityType = 'question',$userId){

                if(empty($userId) || $userId<=0){
                        return false;
                }

		$this->initiateModel();
		
		$queryCmd = "select * from tuserFollowTable where entityId in (?) and entityType = ? and status = 'follow' and userId = ?";
                
		$query = $this->dbHandle->query($queryCmd, array($entityId,$entityType,$userId));
		
		$result = $query->result_array();
		
		if(!empty($result)){
			if(is_array($entityId) && !empty($entityId)){
				return $result;
			}else{
				return true;
			}
			
		}else{
			return false;
			
		}
    }
	
	function getOwnerLevel($userId){
		$data = array();
		$this->initiateModel();
		$queryCmd = "SELECT levelName, levelId, userpointvaluebymodule FROM userpointsystembymodule WHERE userId = ? AND modulename = 'AnA'";
		$query = $this->dbHandle->query($queryCmd, array($userId));
		if($query->num_rows() > 0){
			$row = $query->row();
			$data['levelName'] = $row->levelName;
			$data['levelId'] = $row->levelId;
			$data['userpointvaluebymodule'] = $row->userpointvaluebymodule;
		}
		else{
			$data['levelName'] = "Beginner-Level 1";
			$data['levelId'] = 1;
			$data['userpointvaluebymodule'] = 0;
		}
		return $data;
	}

	function getTags($entityId, $entityType = 'question'){
		
		$this->initiateModel();
		
		$queryCmd = "select tags,tcm.tag_id from tags t,tags_content_mapping tcm where tcm.tag_id = t.id and tcm.content_id = ? and content_type = ? and tcm.status = 'live'";
                
		$query = $this->dbHandle->query($queryCmd, array($entityId,$entityType));
		
		$result = $query->result_array();
		
		if(empty($result)){
			$result = NULL;
		}
		
		return $result;
		
	}
	
	function getQuestionDetails($questionId,$userId){
		
		$this->initiateModel();
		
		$queryCmd = "select mt.msgId,mt.path,mt.abuse,mt.parentId,mt.threadId,mt.fromOthers as queryType,mt.msgTxt as title,mt.status,mt.creationDate,mt.viewCount,mt.msgCount as childCount,tu.displayName,tu.firstname,tu.lastname,tu.userid as userId,tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,md.description,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = ?) as hasUserReportedAbuse from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN messageDiscussion md ON (md.threadId = mt.msgId) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where mt.msgId = ? and mt.fromOthers = 'user' and mt.status in ('live','closed') and mt.mainAnswerId = -1 ";
	
		$query = $this->dbHandle->query($queryCmd, array($userId,$questionId,$questionId));
		
		return $query->result_array();
			
	}
	
	function getAnswerDetails($questionId,$userId,$start = 0,$count = 5,$sortOrder = "Upvotes",$referenceAnswerId = "0"){
		$this->initiateModel();
		
		global $order;
		
		if(strtolower($sortOrder) == 'upvotes'){	
			$order = 'digUp';
			
		}else if(strtolower($sortOrder) == 'latest' || strtolower($sortOrder) == 'oldest'){
			$order = 'creationDate';
			
		}
		
		
		if($referenceAnswerId>0){
			$queryCmd = "select mt.msgId,mt.path,mt.abuse,mt.fromOthers as queryType, mt.msgTxt, mt.threadId, mt.parentId,mt.status, mt.creationDate, tu.displayName, tu.firstname, tu.lastname, tu.userid as userId,tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,mt.digUp,mt.digDown,mt.msgCount as childCount,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = mt.msgId) as hasUserReportedAbuse from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where mt.parentId = ? and mt.status in ('live','closed') and mt.mainAnswerId = 0 and mt.fromOthers = 'user' and msgId = ? 
			UNION
			select mt.msgId,mt.path,mt.abuse,mt.fromOthers as queryType, mt.msgTxt,mt.threadId, mt.parentId,mt.status, mt.creationDate, tu.displayName, tu.firstname, tu.lastname, tu.userid as userId, tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,mt.digUp,mt.digDown,mt.msgCount as childCount,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = mt.msgId) as hasUserReportedAbuse from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where mt.parentId = ? and mt.status in ('live','closed') and mt.mainAnswerId = 0 and mt.fromOthers = 'user' and msgId != ?";
			
			$query = $this->dbHandle->query($queryCmd, array($userId,$questionId,$referenceAnswerId,$userId,$questionId,$referenceAnswerId));			
			$result = $query->result_array();
		}else{

			//$queryCmd = "select mt.msgId,mt.path,mt.abuse,mt.fromOthers as queryType, mt.msgTxt,mt.threadId, mt.parentId,mt.status, mt.creationDate, tu.displayName, tu.firstname, tu.lastname,tu.userid as userId,tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,mt.digUp,mt.digDown,mt.msgCount as childCount,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = mt.msgId) as hasUserReportedAbuse from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where mt.parentId = ? and mt.status in ('live','closed') and mt.mainAnswerId = 0 and mt.fromOthers = 'user'";
			$queryCmd = "select mt.msgId,mt.path,mt.abuse,mt.fromOthers as queryType, mt.msgTxt,mt.threadId, mt.userId, mt.parentId,mt.status, mt.creationDate, mt.digUp, mt.digDown, mt.msgCount as childCount from messageTable mt where mt.parentId = ? and mt.status in ('live','closed') and mt.mainAnswerId = 0 and mt.fromOthers = 'user'";
			$answerDetails = $this->dbHandle->query($queryCmd, array($questionId))->result_array();
			$answeredUserIds = array();
			$ratingAnswerIds = array();
			foreach ($answerDetails as $anskey => $ansalue) {
				if(!empty($ansalue['userId'])){
					$answeredUserIds[] = $ansalue['userId'];	
				}
				if(!empty($ansalue['msgId'])){
					$ratingAnswerIds[] = $ansalue['msgId'];	
				}
			}
			$userInfoResult = $this->getUserDetailsBasedOnIds($answeredUserIds);
			$abuseEntityInfo = array();
			if(!empty($ratingAnswerIds)) {
				$ratingSql = "select entityId, count(entityId) as cnt from tReportAbuseLog where userId = ? and entityId in (?) group by entityId";
				$ratingArray = $this->dbHandle->query($ratingSql,array($userId,$ratingAnswerIds))->result_array();
				foreach ($ratingArray as $key => $value) {
					$abuseEntityInfo[$value['entityId']] = $value['cnt'];
				}
			}

			$result = array();
			foreach ($answerDetails as $akey => $avalue) {
				$temp = $avalue;
				if(!empty($userInfoResult[$temp['userId']])){
					$temp['firstname'] = $userInfoResult[$temp['userId']]['firstname'];
					$temp['lastname'] = $userInfoResult[$temp['userId']]['lastname'];
					$temp['displayname'] = $userInfoResult[$temp['userId']]['displayname'];
					$temp['picUrl'] = $userInfoResult[$temp['userId']]['picUrl'];
					$temp['aboutMe'] = $userInfoResult[$temp['userId']]['aboutMe'];
					$temp['levelName'] = $userInfoResult[$temp['userId']]['levelName'];
				}
				$temp['hasUserReportedAbuse'] = !empty($abuseEntityInfo[$temp['msgId']]) ? $abuseEntityInfo[$temp['msgId']] : 0;
				$result[] = $temp;
			}
		}
        if(!empty($result)){
			$totalRows = count($result);
		
			$res['hasUserAnswered'] = $this->hasUserAnswered($questionId, $result,$totalRows, $userId);
		
			global $isMobileApp;
			foreach($result as $key=>$val){
				$result[$key]['userImage'] = $result[$key]['picUrl'];
				$result[$key]['displayname'] = $result[$key]['displayName'];
				$result[$key]['reportedAbuse'] = $result[$key]['hasUserReportedAbuse'];
				$result[$key]['userLevelDesc'] = $this->getUserLevelDescription($userId);
				if($isMobileApp){
					$result[$key]['msgTxt'] = sanitizeAnAMessageText(strip_tags(html_entity_decode($result[$key]['msgTxt'])),'answer');
				}
				$answerIds .= ($answerIds == '')?$result[$key]['msgId']:','.$result[$key]['msgId'];
			}
		
			if($referenceAnswerId>0){
				$referenceArray = array_shift($result);
			}
			
			if(strtolower($sortOrder) == 'oldest'){
				function temp_func($a, $b){
					global $order;
				
					if($a[$order] == $b[$order])
					{
						return 0;
					}
					return ($a[$order] < $b[$order]) ? -1 : 1;
				}
			
				usort($result, 'temp_func');
			
				if($referenceAnswerId>0){
					array_unshift($result,$referenceArray);	
				}
			
				$res['childDetails'] = array_slice($result, $start,$count);
			
			}else{
				if (!function_exists("temp_func")) {
                    function temp_func($a, $b){
                        global $order;

                        if($a[$order] == $b[$order])
                        {
                            return 0;
                        }
                        return ($a[$order] > $b[$order]) ? -1 : 1;
                    }
				}
			
				usort($result, 'temp_func');
			
				if($referenceAnswerId>0){
					array_unshift($result,$referenceArray);
				}
			
				$res['childDetails'] = array_slice($result, $start,$count);
					
			}

		        if($answerIds != '' && $isMobileApp){
                		//Get list of Suggested Institues for the Answers
		                $this->load->library("common_api/APICommonLib");
                		$apiCommonLib = new APICommonLib();
		                $suggestedInstitutes = $apiCommonLib->getSuggestedInstitutes($answerIds);
            		}
		
			foreach($res['childDetails'] as $key=>$value){								
					if($value['queryType'] == 'user'){
						$res['childDetails'][$key]['queryType'] = 'Q';
						$res['childDetails'][$key]['fromOthers'] = 'user';
					}else if($value['queryType'] == 'discussion'){
						$res['childDetails'][$key]['queryType'] = 'D';
						$res['childDetails'][$key]['fromOthers'] = 'discussion';
					}

					$answerId = $value['msgId'];
	                if(isset($suggestedInstitutes[$answerId]) && count($suggestedInstitutes[$answerId])>0 && $isMobileApp){
	                        $html = $apiCommonLib->getSuggestedInstituteHTML($suggestedInstitutes[$answerId]);
                			//$res['childDetails'][$key]['msgTxt'] = $res['childDetails'][$key]['msgTxt']." ".$html;
					$res['childDetails'][$key]['suggestionText'] = $html;
	                }
	                $answerIdArray[]=$value['msgId'];
				
				}


			$upVotedData = $this->checkRatingForEntityByUser($answerIdArray,$userId,'1');
			$downVotedData = $this->checkRatingForEntityByUser($answerIdArray,$userId,'0');

			foreach($res['childDetails'] as $key=>$value){
				$res['childDetails'][$key]['hasUserVotedUp'] = $upVotedData[$value['msgId']];
				$res['childDetails'][$key]['hasUserVotedDown'] = $downVotedData[$value['msgId']];
			}

			if($totalRows-($start+$count) <= 0){
				$res['showViewMore'] = false;
			}else{
				$res['showViewMore'] = true;
			}
		}
		
		return $res;
	}

	function getUserDetailsBasedOnIds($userIds){
		$userInfoResult = array();
		$userInfo = array();
		if(!empty($userIds) && is_array($userIds) && count($userIds) > 0){
			$usersql = "SELECT t.userid, t.firstname, t.lastname, t.displayname,t.avtarimageurl as picUrl, tuai.aboutMe, (select upsm.levelName from userpointsystembymodule upsm where upsm.userId = t.userid and upsm.modulename = 'AnA') as levelName FROM tuser t LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = t.userid) WHERE t.userid IN (?)";
			$userInfo = $this->dbHandle->query($usersql,array($userIds))->result_array();
			foreach ($userInfo as $key => $value) {
				$userInfoResult[$value['userid']] = $value;
			}
		}
		return $userInfoResult;
	}
	
	function getCommentDetails($answerId,$userId,$start=0,$count=10,$sortOrder="latest",$referenceCommentId,$fromOthers='question'){
		$this->initiateModel();
		
		$limit = '';
		
		if($start>=0 && $count>0){
			
			$limit = "LIMIT $start,$count";
			
		}
		
		global $order;
		
		if(strtolower($sortOrder) == 'upvotes'){
			$order = 'digUp';
			
		}else if(strtolower($sortOrder) == 'latest' || strtolower($sortOrder) == 'oldest'){
			$order = 'creationDate';
			
		}
		
		if($fromOthers == 'question'){
			$condition = 'mt.mainAnswerId = ?';
		}else if($fromOthers == 'discussion'){
			$condition = 'mt.parentId = ?';
		}
		
		
		if($referenceCommentId>0){
		
			$queryCmd = "select mt.msgId, mt.path,mt.abuse,mt.fromOthers as queryType, mt.msgTxt,mt.threadId,mt.parentId,mt.status ,mt.creationDate,mt.digUp,mt.digDown,(select status from messageTable where msgId  = mt.threadId) as threadStatus,tu.displayName,tu.firstname,tu.lastname,tu.userid as userId,tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = mt.msgId) as hasUserReportedAbuse ,(select count(*) from messageTable mt1 where mt1.mainAnswerId>0 and mt1.mainAnswerId != mt1.parentId and mt1.status in ('live','closed') and parentId = mt.msgId) childCount from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where $condition and mt.status in ('live','closed') and mt.mainAnswerId > 0 and mt.mainAnswerId = mt.parentId and msgId = ?
			UNION
			select mt.msgId,mt.path,mt.abuse,mt.fromOthers as queryType, mt.msgTxt,mt.threadId,mt.parentId,mt.status ,mt.creationDate,mt.digUp,mt.digDown,(select status from messageTable where msgId  = mt.threadId) as threadStatus,tu.displayName,tu.firstname,tu.lastname,tu.userid as userId,tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = mt.msgId) as hasUserReportedAbuse ,(select count(*) from messageTable mt1 where mt1.mainAnswerId>0 and mt1.mainAnswerId != mt1.parentId and mt1.status in ('live','closed') and parentId = mt.msgId) childCount from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where $condition and mt.status in ('live','closed') and mt.mainAnswerId > 0 and mt.mainAnswerId = mt.parentId and msgId != ? ";

			$query = $this->dbHandle->query($queryCmd, array($userId,$answerId,$referenceCommentId,$userId,$answerId,$referenceCommentId));
		}else{
			$queryCmd = "select mt.msgId, mt.path,mt.abuse, mt.fromOthers as queryType, mt.msgTxt,mt.threadId ,mt.parentId,mt.status ,mt.creationDate,mt.digUp,mt.digDown,(select status from messageTable where msgId  = mt.threadId) as threadStatus,tu.displayName,tu.firstname,tu.lastname,tu.userid as userId,tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = mt.msgId) as hasUserReportedAbuse ,(select count(*) from messageTable mt1 where mt1.mainAnswerId>0 and mt1.mainAnswerId != mt1.parentId and mt1.status in ('live','closed') and parentId = mt.msgId) childCount from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where $condition and mt.status in ('live','closed') and mt.mainAnswerId > 0 and mt.mainAnswerId = mt.parentId";
			$query = $this->dbHandle->query($queryCmd, array($userId,$answerId));
			
		}
		
		$result = $query->result_array();
		
		if(!empty($result)){
			$totalRows = count($result);
		
			global $isMobileApp;
			foreach($result as $key=>$val){
				$result[$key]['displayname'] = $result[$key]['displayName'];
				$result[$key]['userImage']   = $result[$key]['picUrl'];
				$result[$key]['reportedAbuse']   = $result[$key]['hasUserReportedAbuse'];
				$result[$key]['userLevelDesc'] = $this->getUserLevelDescription($userId);
				if($val['hasUserReportedAbuse'] == 0){
					$result[$key]['hasUserReportedAbuse'] = false;	
				}else{
					$result[$key]['hasUserReportedAbuse'] = true;
				}
			
				if($isMobileApp){
					$result[$key]['msgTxt'] = sanitizeAnAMessageText(strip_tags(html_entity_decode($result[$key]['msgTxt'])),'comment');
				}
			}
		
			if($referenceCommentId>0){
				$referenceArray = array_shift($result);
			}
		
			if(strtolower($sortOrder) == 'oldest'){
				function temp_func($a, $b){
					global $order;
				
					if($a[$order] == $b[$order])
					{
						return 0;
					}
					return ($a[$order] < $b[$order]) ? -1 : 1;
				}
			
				usort($result, 'temp_func');
			
				if($referenceCommentId>0){
					array_unshift($result,$referenceArray);	
				}
			
				$res['childDetails'] = array_slice($result, $start,$count);
			
			}else{
				function temp_func($a, $b){
					global $order;
				
					if($a[$order] == $b[$order])
					{
						if($order == 'digUp')
						{
							return ($a['creationDate'] > $b['creationDate']) ? -1 : 1;
						}
						else
						return 0;
					}
					return ($a[$order] > $b[$order]) ? -1 : 1;
				}
			
				usort($result, 'temp_func');
			
				if($referenceCommentId>0){
					array_unshift($result,$referenceArray);
				}
			
				$res['childDetails'] = array_slice($result, $start,$count);
					
			}
		
			foreach($res['childDetails'] as $key=>$value){
				if($value['queryType'] == 'user'){
					$res['childDetails'][$key]['queryType'] = 'Q';
					$res['childDetails'][$key]['fromOthers'] = 'user';
				}else if($value['queryType'] == 'discussion'){
					$res['childDetails'][$key]['queryType'] = 'D';
					$res['childDetails'][$key]['fromOthers'] = 'discussion';
				}
				$commentIdsArray[]=$value['msgId'];
			}

			$upVotedData = $this->checkRatingForEntityByUser($commentIdsArray,$userId,'1');
			$downVotedData = $this->checkRatingForEntityByUser($commentIdsArray,$userId,'0');

			foreach($res['childDetails'] as $key=>$value){
				$res['childDetails'][$key]['hasUserVotedUp'] = $upVotedData[$value['msgId']];
				$res['childDetails'][$key]['hasUserVotedDown'] = $downVotedData[$value['msgId']];
			}
		
			if($totalRows-($start+$count) <= 0){
				$res['showViewMore'] = false;
			}else{
				$res['showViewMore'] = true;
			}
		}
		
		return $res;
				
	}
	
	function getQuestionDetailPageData($userId,$questionId,$start,$count,$sortOrder,$referenceAnswerId){
		
		$result =array();
	 	$this->benchmark->mark('get_ques_detail_start');	
		$quesDetailArray  = $this->getQuestionDetails($questionId,$userId);
		$this->benchmark->mark('get_ques_detail_end');
		
		$result['entityDetails'] = $quesDetailArray[0];
		
		if(!empty($result['entityDetails'])){
			$this->benchmark->mark('get_answer_detail_start');
			$res= $this->getAnswerDetails($questionId,$userId,$start,$count,$sortOrder,$referenceAnswerId);
			$this->benchmark->mark('get_answer_detail_end');
			
			
			$result['childDetails'] =  $res['childDetails'];
			
			if(empty($result['childDetails'])){
				$result['childDetails'] = NULL;
			}
			
			$result['entityDetails']['msgTxt'] = $result['entityDetails']['title'];
			$result['entityDetails']['msgCount'] = $result['entityDetails']['childCount'];
			$result['entityDetails']['userImage'] = $result['entityDetails']['picUrl'];
			$result['entityDetails']['displayname'] = $result['entityDetails']['displayName'];
			$result['entityDetails']['reportedAbuse'] = $result['entityDetails']['hasUserReportedAbuse'];
			$result['entityDetails']['title'] = sanitizeAnAMessageText(strip_tags(html_entity_decode($result['entityDetails']['title'])),'question');
			if($result['entityDetails']['userId'] == $userId){
				$result['entityDetails']['isEntityOwner'] = true;
			}else{
				$result['entityDetails']['isEntityOwner'] = false;
			}
			
			if($result['entityDetails']['queryType'] == 'user'){
				$result['entityDetails']['queryType'] = 'Q';
				$result['entityDetails']['fromOthers'] = 'user';
			}else if($result['entityDetails']['queryType'] == 'discussion'){
				$result['entityDetails']['queryType'] = 'D';
				$result['entityDetails']['fromOthers'] = 'discussion';
			}
			
			$result['entityDetails']['showViewMore'] = $res['showViewMore'];
			//$result['entityDetails']['userLevel'] = $this->getUserLevel($userId);
			$this->benchmark->mark('get_user_detail_start');
			$result['entityDetails']['userLevelDesc'] = $this->getUserLevelDescription($userId);
			$this->benchmark->mark('get_user_detail_end');
			$result['entityDetails']['hasUserAnswered'] = $res['hasUserAnswered'];
			//$result['quesDetail']['shareCount'] = $this->getShareCount($questionId, 'question');		
			$this->benchmark->mark('get_followers_start');
			$result['entityDetails']['followerCount'] = $this->getFollowerCount($questionId,'question');
			$this->benchmark->mark('get_followers_end');
			$this->benchmark->mark('get_user_follow_start');
			$result['entityDetails']['hasUserFollowed'] = $this->checkIfUserHasFollowedAnEntity($questionId,'question',$userId);
			$this->benchmark->mark('get_user_follow_end');
			$this->benchmark->mark('get_tags_start');
			$tagDetails = $this->getTags($questionId,'question');
			$this->benchmark->mark('get_tags_end');
			
			if(!empty($tagDetails)){
				foreach($tagDetails as $tagId=>$tagName){
					$result['entityDetails']['tagsDetail'][] = array("tagId" => $tagName['tag_id'], "tagName" => $tagName['tags']);
				 }
			}else{
				$result['entityDetails']['tagsDetail'] = NULL;
			}

            //Check if this is an Institute question. If yes, fetch the Course Name and Institute Name
			$this->benchmark->mark('get_course_start');
			$this->load->model('QnAModel');
            $courseId = $this->QnAModel->getCourseIdOfQuestion($questionId);
            if($courseId > 0){
                    
                	$this->load->builder("nationalCourse/CourseBuilder");
					$courseBuilder = new CourseBuilder();
					$courseRepository = $courseBuilder->getCourseRepository();
                    $courseObj = $courseRepository->find($courseId);
					$referrenceString = "Asked about ".$courseObj->getInstituteName()." - ".$courseObj->getName();
                    $result['entityDetails']['referrenceName'] = $referrenceString;
            }
			$this->benchmark->mark('get_course_end');
			
		
		}
		
		//Add the entry in Redis for Personalized Homepage
		global $isGoodBot;
		if($start==0 && intval($_SERVER['HTTP_ISGOODBOT']) != 1 && intval($isGoodBot) != 1){
			$this->benchmark->mark('redis_store_start');
			$this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
			$this->userinteractioncachestoragelibrary->storeUserActionView($userId, $questionId, 'question');
			$this->benchmark->mark('redis_store_end');
		}
			
		return $result;
		
	}
	
	function hasUserAnswered($entityId, $entityDetailsArray, $answerCount, $userId){
		
		if($answerCount<=10){
			//Traverse the Answer array to find if the user has answered
			$count=0;
			$i=0;
			foreach($entityDetailsArray as $entityDetails){
				if($userId == $entityDetails['userId']){
					
					$count++;
					break;
				}
				$i++;
			}
			return $count;
			
		}
		else{
			$this->initiateModel();

			$queryCmd = "select count(msgId) as answerCount from messageTable mt1 where mt1.parentId = ? and mt1.mainAnswerId = 0 and mt1.userId = ? ";
		
			$query = $this->dbHandle->query($queryCmd, array($entityId,$userId));
			
			$result = $query->result_array();
			
			return $result[0]['answerCount'];
		
		}
	}
	
	function getDiscussionDetailData($discussionId,$userId){
		
		$this->initiateModel();
		
		$queryCmd = "select mt.msgId,mt.path,mt.abuse,mt.threadId,mt.parentId,mt.fromOthers as queryType,mt.msgTxt as title,mt.status,mt.creationDate,(select viewCount from messageTable where msgId = ?) as viewCount,(select m.status from messageTable m where m.msgId = mt.threadId) as discussionStatus,tu.displayName,tu.firstname,tu.lastname,tu.userId,md.description,tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = mt.msgId) as hasUserReportedAbuse,(select count(*) from messageTable mt1 where mt1.mainAnswerId>0 and mt1.status in ('live','closed') and mt1.threadId = ? and parentId = mt.msgId) childCount from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN messageDiscussion md ON (md.threadId = mt.msgId) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where mt.parentId = ? and fromOthers = 'discussion' and mt.status in ('live','closed') and mt.mainAnswerId = 0 group by mt.msgId having discussionStatus in ('live','closed')";
	
		$query = $this->dbHandle->query($queryCmd, array($discussionId,$userId,$discussionId,$discussionId));
		
		return $query->result_array();
		
	}
	
	
	function getDiscussionPageData($userId,$discussionId,$start,$count,$sortOrder='latest',$referenceCommentId){
		
		$result =array();
		
		$discussionDetailArray = $this->getDiscussionDetailData($discussionId,$userId);
		$result['entityDetails'] = $discussionDetailArray[0];
		
		if(!empty($result['entityDetails'])){

			$res = $this->getCommentDetails($result['entityDetails']['msgId'],$userId,$start,$count,$sortOrder,$referenceCommentId,'discussion');
			$result['childDetails'] = $res['childDetails'];
			
			global $isMobileApp;
			
			if($isMobileApp){
				$result['entityDetails']['title'] = sanitizeAnAMessageText(strip_tags(html_entity_decode($result['entityDetails']['title'])),'discussion');
			}
			$result['entityDetails']['msgTxt'] = $result['entityDetails']['title'];
			$result['entityDetails']['displayname'] = $result['entityDetails']['displayName'];
			$result['entityDetails']['userImage'] = $result['entityDetails']['picUrl'];
			$result['entityDetails']['reportedAbuse'] = $result['entityDetails']['hasUserReportedAbuse'];
			
			if($result['entityDetails']['userId'] == $userId){
					$result['entityDetails']['isEntityOwner'] = true;
			}else{
				$result['entityDetails']['isEntityOwner'] = false;
			}
			
			if($result['entityDetails']['hasUserReportedAbuse'] == 0){
				$result['entityDetails']['hasUserReportedAbuse'] = false;   
			}else{
				$result['entityDetails']['hasUserReportedAbuse'] = true;  
			}
			
			if($result['entityDetails']['queryType'] == 'user'){
				$result['entityDetails']['queryType'] = 'Q';
				$result['entityDetails']['fromOthers'] = 'user';   
			}else if($result['entityDetails']['queryType'] == 'discussion'){
				$result['entityDetails']['queryType'] = 'D';
				$result['entityDetails']['fromOthers'] = 'discussion';   
			}
			
			$result['entityDetails']['showViewMore'] = $res['showViewMore'];
			//$result['entityDetails']['userLevel'] = $this->getUserLevel($userId);
			$result['entityDetails']['userLevelDesc'] = $this->getUserLevelDescription($userId);	
			$result['entityDetails']['followerCount'] = $this->getFollowerCount($discussionId,'discussion');
			$result['entityDetails']['hasUserFollowed'] = $this->checkIfUserHasFollowedAnEntity($discussionId,'discussion',$userId);
			$tagDetails = $this->getTags($discussionId,'discussion');
			
			if(!empty($tagDetails)){
				foreach($tagDetails as $tagId=>$tagName){
					$result['entityDetails']['tagsDetail'][] = array("tagId" => $tagName['tag_id'], "tagName" => $tagName['tags']);
				 }
			}else{
				$result['entityDetails']['tagsDetail'] = NULL;
			}
			
			$linkedEntities = $this->getLinkedQuestionDiscussionDetails($discussionId,'discussion');
			
			if(!empty($linkedEntities)){
				$result['entityDetails']['isLinked'] = 1;
			}else{
				$result['entityDetails']['isLinked'] = 0;
			}
			
		}
		
		//Add the entry in Redis for Personalized Homepage
		if($start==0){
			$this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
			$this->userinteractioncachestoragelibrary->storeUserActionView($userId, $discussionId, 'discussion');
		}
		
		return $result;
			
	}

        function getDiscussionMainAnsId($topicId){
                $this->initiateModel();
                $queryCmd = "SELECT msgId FROM messageTable WHERE threadId = ? AND parentId = threadId LIMIT 1";
                $query = $this->dbHandle->query($queryCmd, array($topicId));
                $result = $query->row();
                return $result->msgId;
        }

        /**
         * @desc API to Keep a log of Sharing of Question/Discussion/Answer
	 * @param param userId which is the user id	
         * @param param entityId which is the entity id
         * @param param entityType which is the entity type
         * @param param destination which is the third-party site on which the content is shared
         * @return true
         * @date 2015-08-05
         * @author Ankur Gupta
         */	
        function shareEntity($userId, $entityId, $entityType, $destination){
                $this->initiateModel('write');
                $insertData = array(
                                   'userId' => $userId,
                                   'entityId' => $entityId,
                                   'entityType' => $entityType,
                                   'destination' => $destination
                              );
                $this->dbHandle->insert('entityShareLog',$insertData);
		
                //Add the entry in Redis for Personalized Homepage
		$entityType = strtolower($entityType);
		if($entityType == 'question' || $entityType == 'discussion'){
			$this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
			$this->userinteractioncachestoragelibrary->storeUserActionShare($userId, $entityId, $entityType);
		}
                return true;
        }
	
	
	function getAnswerOwnerUserId($msgId){
		
		$this->initiateModel('read');
		$sql = "Select userId from messageTable where msgId = ?";
		
		$query = $this->dbHandle->query($sql, array($msgId));
		
		$result = $query->result_array();
		
		return $result[0]['userId'];
		
	}
	

	/* Get ownerId of an entity*/
	
	function getOwnerIdOfEntity($msgId){
		
		$this->initiateModel('read');
		
		$queryCmd = "select userId from messageTable where msgId =?";
		$query = $this->dbHandle->query($queryCmd,array($msgId));
		$result = $query->result_array();
		
		return $result[0]['userId'];
		
		
	}

        /**
         * @desc API to Keep Shortlisting of Question/Discussion
         * @param param userId which is the user id
         * @param param entityId which is the entity id
         * @param param entityType which is the entity type
         * @return true
         * @date 2015-08-14
         * @author Ankur Gupta
         */
        function shortlistEntity($userId, $entityId, $entityType, $status,$trackingPageKeyId){
                if($userId > 0 && $entityId > 0){
                        $this->initiateModel('write');
                        $visitorSessionId = getVisitorSessionId();
                        if($status == 'live')
                        	$updateString = ", tracking_keyid =".$this->dbHandle->escape($trackingPageKeyId)." , visitorsessionid = '".$visitorSessionId."'";
                        $queryCmd = "INSERT INTO entityShortlist (userId, entityId, entityType, status, tracking_keyid, visitorsessionid) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE status = ?, modificationTime = now() ".$updateString;
                        $query = $this->dbHandle->query($queryCmd, array($userId, $entityId, $entityType, $status, $trackingPageKeyId, $visitorSessionId, $status));
                        return true;
                }
                else{
                        return false;
                }
        }

	function getShortlistedEntities($userId, $entityType, $start, $count){
                $this->initiateModel('read');

                if($entityType=='question'){
                        $queryCmd = "SELECT 'Q' as 'type', m.msgId as entityId, m.msgTxt, m.creationDate, m.msgCount, m.viewCount FROM messageTable m, entityShortlist e WHERE m.msgId = e.entityId AND e.userId = ? AND entityType = 'question' AND e.status = 'live' AND m.status IN ('live','closed') AND m.fromOthers = 'user' AND m.parentId = 0 ORDER BY e.creationTime DESC LIMIT $start, $count";
                        $query = $this->dbHandle->query($queryCmd,array($userId));
                }
                else if($entityType=='discussion'){
                        $queryCmd = "SELECT 'D' as 'type', m.threadId as entityId, m.msgTxt, m.creationDate, m.msgCount, (SELECT viewCount FROM messageTable WHERE msgId = m.threadId) viewCount FROM messageTable m, entityShortlist e WHERE m.threadId = e.entityId AND e.userId = ? AND entityType = 'discussion' AND e.status = 'live' AND m.status IN ('live','closed') AND m.fromOthers = 'discussion' AND m.parentId = m.threadId ORDER BY e.creationTime DESC LIMIT $start, $count";
                        $query = $this->dbHandle->query($queryCmd,array($userId));
                }
                else{
                        $queryCmd = "(SELECT 'Q' as 'type', m.msgId as entityId, m.msgTxt, m.creationDate, m.msgCount, m.viewCount, e.creationTime FROM messageTable m, entityShortlist e WHERE m.msgId = e.entityId AND e.userId = ? AND entityType = 'question' AND e.status = 'live' AND m.status IN ('live','closed') AND m.fromOthers = 'user' AND m.parentId = 0) UNION (SELECT 'D' as 'type', m.threadId as entityId, m.msgTxt, m.creationDate, m.msgCount, (SELECT viewCount FROM messageTable WHERE msgId = m.threadId) viewCount, e.creationTime FROM messageTable m, entityShortlist e WHERE m.threadId = e.entityId AND e.userId = ? AND entityType = 'discussion' AND e.status = 'live' AND m.status IN ('live','closed') AND m.fromOthers = 'discussion' AND m.parentId = m.threadId) ORDER BY creationTime DESC LIMIT $start, $count";
                        $query = $this->dbHandle->query($queryCmd,array($userId, $userId));
                }
                $result = $query->result_array();
                return $result;
	}

	function trackEditOperation($editEntityId, $entityType, $userId, $automoderationFlag=0){
		$this->initiateModel('write');

		//Extract all the information from the Tables (Title, Description, Tags in case of Question/Discussion)
		switch ($entityType){
			case 'question': 	$queryCmd = "SELECT m.msgTxt, md.description, GROUP_CONCAT(tag_id SEPARATOR ', ') tagCSV FROM messageTable m LEFT JOIN tags_content_mapping t ON (m.msgId = t.content_id AND t.status = 'live') LEFT JOIN messageDiscussion md ON (m.msgId = md.threadId) WHERE m.msgId = ? AND m.fromOthers = 'user'";
						break;
			case 'discussion': 	$queryCmd = "SELECT m.msgTxt, md.description, GROUP_CONCAT(tag_id SEPARATOR ', ') tagCSV FROM messageTable m LEFT JOIN tags_content_mapping t ON (m.threadId = t.content_id AND t.status = 'live') LEFT JOIN messageDiscussion md ON (m.msgId = md.threadId) WHERE m.threadId = ? AND m.parentId = m.threadId AND m.fromOthers = 'discussion'";
						break;
			case 'answer': 		
			case 'reply':
			case 'comment':
						$queryCmd = "SELECT m.msgTxt FROM messageTable m WHERE m.msgId = ?";
						break;
			default : 		return false;
		}
		$query = $this->dbHandle->query($queryCmd,array($editEntityId));
		$result = $query->result_array();

		//Now, store all this information in the Table
		if(isset($result[0]) && isset($result[0]['msgTxt'])){
			$result = $result[0];
			if($entityType == 'question' || $entityType == 'discussion'){
		               	$queryCmd = "INSERT INTO messageEditTracking (userId, entityId, entityType, mainText, description, tags, automoderatedFlag) VALUES (?, ?, ?, ?, ?, ?, ?)";
	        	        $query = $this->dbHandle->query($queryCmd, array($userId, $editEntityId, $entityType, $result['msgTxt'], $result['description'], $result['tagCSV'], $automoderationFlag));
			}
			else{
                                $queryCmd = "INSERT INTO messageEditTracking (userId, entityId, entityType, mainText, automoderatedFlag) VALUES (?, ?, ?, ?, ?)";
                                $query = $this->dbHandle->query($queryCmd, array($userId, $editEntityId, $entityType, $result['msgTxt'], $automoderationFlag));
			}
		}

	}

	function editCommentReply($detailArray){
		$this->initiateModel('write');
	        $msgTxt = $detailArray['msgTxt'];
        	$dataToUpdate = array('msgTxt' =>htmlspecialchars($msgTxt),'requestIP'=>$detailArray['requestIP']);
	        $where = "msgId = ".$detailArray['msgId'];
        	$query = $this->dbHandle->update_string('messageTable',$dataToUpdate,$where);
	        $this->dbHandle->query($query);
		return 'edited';
	}


	function getUserLevel($userId){
		return 'Beginner';
	}
	
	function getUserLevelDescription($userId){
		return 'Iam a Beginner';
	}
	
	function getRelatedQuestions($threadId){
		
		$result = array('0'=>array('entityId'=>'3229528','title'=>'Which websites are good for preparing for group discussions and group tasks?','childCount'=>'3','viewCount'=>'5'),
				'1'=>array('entityId'=>'3229526','title'=>'How do college students prepare group discussions for campus recruitments?','childCount'=>'2','viewCount'=>'4'),
				'2'=>array('entityId'=>'3229512','title'=>'How should I prepare for the group discussions and personal interview conducted by IIMs after CAT?','childCount'=>'1','viewCount'=>'3')
			       );
			
		
		
		return $result;
	}
	
	
	function checkIfUserisPowerUser($userId){
		
		$this->initiateModel('read');
		
		//$queryCmd = "select userId from userGroupsMappingTable where userId =? and status = 'live' and groupId = '1'";
		$queryCmd = "select userId from userGroupsMappingTable where userId =? and status = 'live'";
		$query = $this->dbHandle->query($queryCmd,array($userId));
		$result = $query->result_array();
		
		return $result[0]['userId'];
			
	}
	
	function checkRatingForEntityByUser($entityIds,$userId,$digVal){

		if(empty($userId) || $userId<=0){
			return false;
		}

		$this->initiateModel('read');
		
		$queryCmd = "select digFlag,userId,productId from digUpUserMap where userId =? and productId in (?) and digFlag = ? and digUpStatus = 'live'";
		$query = $this->dbHandle->query($queryCmd,array($userId,$entityIds,$digVal));
		
		$result = $query->result_array();
		$finalArray=array();
		foreach($result as $val){
			if(!empty($val)){
				$finalArray[$val['productId']] = true;
			}else{
				$finalArray[$val['productId']] = false;
			}
		}

		return $finalArray;		
	}
	
	function getReplyDetails($commentId,$userId,$start=0,$count=10){
		
		$this->initiateModel();
		
		$limit = '';
		
		if($start>=0 && $count>0){
			
			$limit = "LIMIT $start,$count";
			
		}
		
		$queryCmd = "select mt.msgId, mt.fromOthers as queryType,mt.msgTxt,mt.threadId,mt.parentId ,mt.creationDate,mt.digUp,mt.digDown,tu.displayName,tu.firstname,tu.lastname,tu.userid as userId,tu.avtarimageurl as picUrl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,(select count(entityId) from tReportAbuseLog where userId = ? and entityId = mt.msgId) as hasUserReportedAbuse from messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where mt.parentId = ? and mt.status in ('live','closed') and mt.mainAnswerId > 0 ORDER BY mt.creationDate DESC $limit ";
	
		$query = $this->dbHandle->query($queryCmd, array($userId,$commentId));
		
		$result = $query->result_array();
		
		global $isMobileApp;
		foreach($result as $key=>$val){
			//$result[$key]['userLevel'] = $this->getUserLevel($userId);
			$result[$key]['userLevelDesc'] = $this->getUserLevelDescription($userId);
			if($val['hasUserReportedAbuse'] == 0){
				$result[$key]['hasUserReportedAbuse'] = false;	
			}else{
				$result[$key]['hasUserReportedAbuse'] = true;
			}
			$result[$key]['fromOthers'] = $val['queryType'];
			if($val['queryType'] == 'user'){
				$result[$key]['queryType'] = 'Q';	
			}else if($val['queryType'] == 'discussion'){
				$result[$key]['queryType'] = 'D';
			}
			
			global $isMobileApp;
			if($isMobileApp){
				$result[$key]['msgTxt'] = sanitizeAnAMessageText(strip_tags(html_entity_decode($result[$key]['msgTxt'])),'reply');
			}
				
		}
		
		return $result;
				
	}
	
	function getLinkedQuestionDiscussionDetails($entityId,$entityType, $limit = 10){
		
		$this->initiateModel();
		
		if($entityType=='discussion'){
			$join = "mt.parentId = qd.linkingEntityId";
			$viewCount = "(select viewCount from messageTable where msgId = mt.threadId) as viewCount";
		}else{
			$join = "mt.msgId = qd.linkingEntityId";
			$viewCount = "viewCount";
		}
		
		$queryCmd = "SELECT msgId, msgTxt as title, msgCount as childCount, $viewCount ,threadId,mt.creationDate FROM messageTable mt, questionDiscussionLinkingTable qd WHERE $join AND qd.status = 'accepted' AND type = ? AND linkedEntityId = ? AND mt.status in ('live','closed') ORDER BY qd.id desc LIMIT ".$limit;
		
		$query = $this->dbHandle->query($queryCmd, array($entityType,$entityId));
		
		$result = $query->result_array();
		
		global $isMobileApp;
		if($isMobileApp){
			foreach($result as $key=>$val){
				$result[$key]['title'] = sanitizeAnAMessageText(strip_tags(html_entity_decode($result[$key]['title'])),$entityType);
			}
		}
		
		return $result;
		
	}

	function getContentTags($contentArray, $type){
                $this->initiateModel();
		$contentIds = implode(",",$contentArray);
		if(!empty($contentIds)){
	                $queryCmd = "select tags,tcm.tag_id,tcm.content_id,t.tag_entity from tags t,tags_content_mapping tcm where tcm.tag_id = t.id and tcm.content_id IN ($contentIds) and content_type = ? and tcm.status = 'live'";
        	        $query = $this->dbHandle->query($queryCmd, array($type));
                	$rows = $query->result_array();
	                $finalArray = array();
        	        foreach ($contentArray as $entity){
				$i = 0;
                        	foreach ($rows as $row){
                                	if($entity == $row['content_id']){
                                        	$finalArray[$entity][$i]['tagId'] = $row['tag_id'];
						$finalArray[$entity][$i]['tagName'] = $row['tags'];
						$finalArray[$entity][$i]['tagType'] = $row['tag_entity'];
						$i ++;
        	                        }
                	        }
        	        }
                	return $finalArray;
		}
                return $result;
	}
	
	function getExpertLevels($moduleName)
	{
		$this->initiateModel();
		$query = "SELECT DISTINCT levelName from userpointsystembymodule where modulename = ? order by levelId";
		$res = $this->dbHandle->query($query, array($moduleName))->result_array();
		return $res;

	}

	function getQuestionsBasicDetails($questionIds){

		if(empty($questionIds))
			return array();
		$this->initiateModel();

		$data = array();
		$queryCmd = "SELECT msgId as id, msgTxt, msgCount, viewCount, status, mt.userId, tu.firstname, tu.lastname,mt.creationDate FROM messageTable mt JOIN tuser tu ON (mt.userId = tu.userid) WHERE msgId IN (".implode(',', $questionIds).") AND fromOthers = 'user' AND status IN ('live','closed')";

		$query  = $this->dbHandle->query($queryCmd);		
		$result = $query->result_array();
		
		global $isMobileApp;
		foreach ($result as $value) {
			$data[$value['id']] = $value;
			if($isMobileApp){
				     $data[$value['id']]['msgTxt'] = sanitizeAnAMessageText(htmlspecialchars_decode($value['msgTxt']),'question');
			}else{
				     $data[$value['id']]['msgTxt'] = $value['msgTxt'];
			}
                        $qDate = date('Y-m-d',strtotime($value['creationDate']));
                        $data[$value['id']]['URL']   = getSeoUrl($value['id'], 'question', $value['msgTxt'],array(),'NA',$qDate);

        }
		
		return $data;
	}

	function getDiscussionsBasicDetails($discussionIds){

		if(empty($discussionIds))
			return array();
		$this->initiateModel();
		$data 	  = array();
		$queryCmd = " SELECT msgId, parentId as id, msgTxt, viewCount, status, userId, creationDate FROM messageTable "
					." WHERE (parentId IN (".implode(',', $discussionIds).") AND mainAnswerId = 0 AND parentId = threadId) "
					." OR msgId IN(".implode(',', $discussionIds).") AND fromOthers = 'discussion' AND status IN ('live','closed') ";

		$query  = $this->dbHandle->query($queryCmd);		
		$result = $query->result_array();

		global $isMobileApp;
		foreach ($result as $value) {
			if($value['id'] == 0){
				$data[$value['msgId']]['viewCount']= $value['viewCount'];
			}else{
				$data[$value['id']]['id']		= $value['id'];
				if($isMobileApp){
				     $data[$value['id']]['msgTxt'] = sanitizeAnAMessageText(htmlspecialchars_decode($value['msgTxt']),'discussion');
				}else{
					 $data[$value['id']]['msgTxt']	= $value['msgTxt'];
				}
				$data[$value['id']]['status']	= $value['status'];
				$data[$value['id']]['userId']	= $value['userId'];
				$data[$value['id']]['creationDate']	= $value['creationDate'];
			}
			//$data[$value['id']] = $value;
		}
		return $data;
	}

	function getAnAUsersLevel($userIds){

		if(empty($userIds))
			return array();
		$this->initiateModel();
		$data 	  = array();
		$queryCmd = "SELECT upsm.userId, upsm.levelName FROM userpointsystembymodule upsm WHERE upsm.userId IN (".implode(',', $userIds).") AND upsm.modulename = 'AnA'";

		$query  = $this->dbHandle->query($queryCmd);		
		$result = $query->result_array();

		foreach ($result as $value) {
			$data[$value['userId']] = $value['levelName'];
		}
		return $data;
	}

	function getUpAndDownVotesOfEntities($commentAndAnswerIds){

		if(empty($commentAndAnswerIds))
			return array();
		$this->initiateModel();
		$data 	  = array();
		$queryCmd = "SELECT productId, digFlag, count(*) as votes FROM digUpUserMap WHERE productId IN (".implode(',', $commentAndAnswerIds).") AND product = 'qna' AND digUpStatus = 'live' GROUP BY productId,digFlag";

		$query  = $this->dbHandle->query($queryCmd);		
		$result = $query->result_array();

		foreach ($result as $value) {
			$data[$value['productId']][$value['digFlag']] = $value['votes'];
		}
		return $data;
	}

	function getThreadFollowers($threadIds){

		if(empty($threadIds))
			return array();
		$this->initiateModel();
		$data 	  = array();
		$queryCmd = "SELECT entityId, count(*) as followers FROM tuserFollowTable WHERE status = 'follow' and entityType IN ('question','discussion') and entityId IN (".implode(',', $threadIds).") GROUP BY entityId;";

		$query  = $this->dbHandle->query($queryCmd);		
		$result = $query->result_array();

		foreach ($result as $value) {
			$data[$value['entityId']] = $value['followers'];
		}
		return $data;
	}

	function getDiscussionsCommentCount($threadIdList){

		if(empty($threadIdList))
			return array();

		$this->initiateModel('read');	

		$queryCmd = "SELECT threadId,msgId FROM messageTable WHERE threadId = parentId AND threadId IN (?) AND fromOthers = 'discussion'";

		$rs = $this->dbHandle->query($queryCmd,array($threadIdList))->result_array();

		$result = array();
		foreach($rs as $value){
			$result[$value['threadId']] = $value['msgId'];
		}

		if(empty($result) && count($result) == 0)
		{
			return $result;
		}
		$queryCmd = "SELECT count(*) as count, threadId FROM messageTable WHERE parentId IN (".implode(',', $result).") 
					 AND threadId IN (".implode(',', $threadIdList).") 
					 AND fromOthers = 'discussion'
					 AND status IN ('live','closed')
					 GROUP BY threadId";

		$rs = $this->dbHandle->query($queryCmd)->result_array();

		$result = array();
		foreach($rs as $value){
			$result[$value['threadId']] = $value['count'];
		}
		return $result;
	}

	function getAnswerCommentDetails($commentAndAnswerIds, $userId){

		if(empty($commentAndAnswerIds))
			return array();
		$this->initiateModel();
		$data 	  = array();
		$queryCmd = "SELECT m.msgId, m.msgTxt, m.userId, m.creationDate, (SELECT digFlag FROM digUpUserMap WHERE productId=m.msgId AND userId=? AND digUpStatus='live') hasUserVoted FROM messageTable m where m.msgId IN (".implode(',', $commentAndAnswerIds).") and m.status IN ('live','closed')";

		$query  = $this->dbHandle->query($queryCmd, array($userId));		
		$result = $query->result_array();

		global $isMobileApp;
		foreach ($result as $value) {
			if($isMobileApp){
				$value['msgTxt'] = sanitizeAnAMessageText(htmlspecialchars_decode($value['msgTxt']),'answer');
			}
			$answerIds .= ($answerIds == '')?$value['msgId']:','.$value['msgId'];
			$data[$value['msgId']] = $value;
		}

		//Get list of Suggested Institues for the Answers
		//If any suggested institute is found, append it in the Answer text
                if($answerIds != '' && $isMobileApp){
                        $this->load->library("common_api/APICommonLib");
                        $apiCommonLib = new APICommonLib();
                        $suggestedInstitutes = $apiCommonLib->getSuggestedInstitutes($answerIds);
                }
                foreach($data as $key=>$value){
                        $answerId = $value['msgId'];
                        if(isset($suggestedInstitutes[$answerId]) && count($suggestedInstitutes[$answerId])>0 && $isMobileApp){
                                $html = $apiCommonLib->getSuggestedInstituteHTML($suggestedInstitutes[$answerId]);
                                $data[$key]['msgTxt'] = $data[$key]['msgTxt']." ".$html;
                        }
                }

		return $data;
	}
	
	function checkIfAnswerLaterMarked($userId,$entityId,$entityType)
	{
		$this->initiateModel();
		$query = "SELECT userId,entityId from entityShortlist where userId = ? and entityId=? and entityType=? and status='live'";
		$res = $this->dbHandle->query($query, array($userId,$entityId,$entityType))->result_array();
		return $res;

	}

	function getAnAUsersDetails($userIds){

		if(empty($userIds))
			return array();
		$this->initiateModel();
		$data 	  = array();

		$userIdArray = array();
		foreach ($userIds as $userKey) {
			$userIdArray[] = $this->dbHandle->escape($userKey);
		}

		$queryCmd = "SELECT upsm.userId, upsm.levelName, upsm.userpointvaluebymodule, upsm.levelId FROM userpointsystembymodule upsm WHERE upsm.userId IN (".implode(',', $userIdArray).") AND upsm.modulename = 'AnA'";

		$query  = $this->dbHandle->query($queryCmd);		
		$result = $query->result_array();

		foreach ($result as $value) {
			$data[$value['userId']] = $value;
		}
		return $data;
	}
	
	function getReportAbuseThreadData($threadId){
		$this->initiateModel();
		global $isMobileApp;
		
		$queryCmd = "SELECT mt.fromOthers,mt.msgTxt as threadTitle,mt.status from messageTable mt where msgId = ? ";
		$query  = $this->dbHandle->query($queryCmd,array($threadId));
		$result = $query->result_array();
		
		if($isMobileApp){
			$result[0]['threadTitle'] = sanitizeAnAMessageText(htmlspecialchars_decode($result[0]['threadTitle']),'question');
		}

		
		return $result;
	}
	
	function getReportAbuseEntityData($entityId,$entityType='question'){
		
		global $isMobileApp;
		$this->initiateModel();
		$queryCmd = "SELECT mt.fromOthers,mt.status,mt.msgTxt as entityTitle from messageTable mt where msgId = ? ";
		$query  = $this->dbHandle->query($queryCmd,array($entityId));
		$result = $query->result_array();
		
		if($isMobileApp){
			$result[0]['entityTitle'] = sanitizeAnAMessageText(htmlspecialchars_decode($result[0]['entityTitle']),$entityType);
		}
		
		return $result;
	}
	
	function getReportAbuseUserData($entityId){
		
		$this->initiateModel();
		$queryCmd = "SELECT userId,creationDate from tReportAbuseLog where entityId =?";
		$query  = $this->dbHandle->query($queryCmd,array($entityId));
		$result = $query->result_array();
		
		return $result;
	}
	
	public function getSecondLevelCommentReplyCount($firstLevelAnswerCommentIds = array()){
		$result = array();
		$discussionCommentList = $questionCommentList = "";
		try {
			$this->initiateModel('read');
			$sql = "SELECT msgId, fromOthers FROM messageTable WHERE msgId IN (".implode(",", $firstLevelAnswerCommentIds).")";
			$resultSet = $this->dbHandle->query($sql)->result_array();
			foreach ($resultSet as $data){
				if($data['fromOthers'] == 'discussion'){
					$discussionCommentList .= ($discussionCommentList=='')?$data['msgId']:','.$data['msgId'];
				}
				else if ($data['fromOthers'] == 'user'){
					$questionCommentList .= ($questionCommentList=='')?$data['msgId']:','.$data['msgId'];
				}
			}

			if($discussionCommentList != ''){
				$sql = "SELECT parentId, count(1) as childrenCount FROM messageTable where parentId IN (".$discussionCommentList.")"
					." AND status = 'live' "
					." GROUP BY 1";
				$resultSet = $this->dbHandle->query($sql)->result_array();
				foreach($resultSet as $data){
					$result[$data['parentId']] = $data['childrenCount'];
				}
			}

                        if($questionCommentList != ''){
                                $sql = "SELECT mainAnswerId, count(1) as childrenCount FROM messageTable where mainAnswerId IN (".$questionCommentList.")"
                                        ." AND status = 'live' "
                                        ." GROUP BY 1";
                                $resultSet = $this->dbHandle->query($sql)->result_array();
                                foreach($resultSet as $data){
                                        $result[$data['mainAnswerId']] = $data['childrenCount'];
                                }
                        }

		} catch (Exception $e) {
			error_log(' :: Error Ocurred :: '.$e->getTrace());
		}
		return $result;
	}


	function savePhoneNoForAppLink($sessionId, $phoneNo, $status){
        $this->initiateModel('write');
        $insertData = array(
	                           'session_id' => $sessionId,
	                           'PhoneNo' => $phoneNo,
	                           'status' => $status   );
        
        $this->dbHandle->insert('getAppLinkSms',$insertData);
		return $this->dbHandle->insert_id();
	}

function getUserDetailsWhoVotedup($userId,$entityId,$start,$count){
		$this->initiateModel();
		$queryCmd = "SELECT dum.userId,tu.firstname,tu.lastname,tu.avtarimageurl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,(select status from tuserFollowTable tft where tft.entityId = tu.userid and tft.status = 'follow' and tft.userId = ?) as isUserFollowing  from digUpUserMap dum JOIN tuser tu ON (dum.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where dum.productId =? and dum.digUpStatus = 'live' and dum.digFlag = '1' ORDER BY dum.digTime DESC LIMIT $start,$count";
		$query  = $this->dbHandle->query($queryCmd,array($userId,$entityId));
		$result = $query->result_array();
		return $result;
	}
	
	function getUserDetailsWhoFollowedQues($userId,$entityId,$start,$count,$entityType){
		$this->initiateModel();
		$queryCmd = "SELECT tft.userId,tu.firstname,tu.lastname,tu.avtarimageurl,tuai.aboutMe,(select upsm.levelName from userpointsystembymodule upsm where upsm.userId = tu.userid and upsm.modulename = 'AnA') as levelName,(select status from tuserFollowTable tft where tft.entityId = tu.userid and tft.status = 'follow' and tft.userId = ?) as isUserFollowing  from tuserFollowTable tft JOIN tuser tu ON (tft.userId = tu.userid) LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = tu.userid) where tft.entityId =? and status = 'follow' and entityType = ? ORDER BY tft.creationTime DESC LIMIT $start,$count";
		$query  = $this->dbHandle->query($queryCmd,array($userId,$entityId,$entityType));
		$result = $query->result_array();
		
		return $result;
	}

	function getExpertDataForContent($emailIds, $fromDate, $toDate){
		$this->initiateModel();
		if($emailIds != ''){
		/*$emailIds = explode(',', $emailIds);
		$emailIdArray= array();
		foreach ($emailIds as $emailKey) {
            $emailIdArray[] = $this->dbHandle->escape($emailKey);
        }
        $emailIds = implode(',', $emailIdArray);*/
		$queryCmd = "SELECT userId,email from tuser where email in ($emailIds)";
		$query  = $this->dbHandle->query($queryCmd);
		$result = $query->result_array();
		$finalUserIds ='';
		$email = array();
		foreach ($result as $value) {
				$finalUserIds .= ($finalUserIds != '')?','.$value['userId']:$value['userId']; 
				$email[$value['userId']] = $value['email'];
			}	
		if($finalUserIds != ''){
		$queryCmd1 = "SELECT COUNT(*) as answerCount, userId from messageTable where userId in ($finalUserIds) AND fromOthers = 'user' AND status in ('live', 'closed') AND mainAnswerId = 0 and creationDate >= ? and creationDate <= ? group by userId";
		$query  = $this->dbHandle->query($queryCmd1,array($fromDate,$toDate));
		$finalResult = $query->result_array();}	
		$data = array();
		$data['emailId'] = $email;
		$data['answerCount'] = $finalResult;
		return $data;
		}
	}

	function getEntityType($entityId){
		$this->initiateModel();

		$queryCmd = "SELECT fromOthers from messageTable where msgId =?";
		$query  = $this->dbHandle->query($queryCmd,array($entityId));
		$result = $query->result_array();

		return $result[0]['fromOthers'];

	}

        function getEntityStatus($entityId){
                $this->initiateModel();

                $queryCmd = "SELECT status from messageTable where msgId =?";
                $query  = $this->dbHandle->query($queryCmd,array($entityId));
                $result = $query->result_array();

                return $result[0]['status'];
        }

	//get data of entities like question/answer/discusssiom/commnet/reply posted in last 1 hour for auto moderation
	function getEntityDataForAutomoderation(){
		$this->initiateModel();
		$query = "select msgId,msgTxt,fromOthers,parentId,mainAnswerId,mt.threadId,description from messageTable mt LEFT JOIN messageDiscussion md ON (mt.msgId = md.threadId) where status='live' and msgTxt != 'dummy' and listingTypeId = 0 and creationDate>=DATE_SUB(NOW(),INTERVAL 1800 SECOND) and fromOthers in ('user','discussion')";
		$result = $this->dbHandle->query($query)->result_array();
		return $result;
	}


	//get keywords from DB for auto moderation
	function getAutoModerationKeywordData(){

		$key = md5('autoModerationKeywordData');
		$cacheLib = $this->load->library('cacheLib');
		$cacheData = $cacheLib->get($key);
		if($cacheData != 'ERROR_READING_CACHE') {
			$data = $cacheData;
		} else {
			$this->initiateModel();
			$query = "select Lingo,Actual_words from autoModerationKeywordsMapping where Status = 'live'";
			$result = $this->dbHandle->query($query)->result_array();
			foreach($result as $value){
				$lingoArray[] = '/\b('.$value['Lingo'].')\b/i';
				$actualWordArray[] = $value['Actual_words']; 
			}

			$data['lingoArray'] = $lingoArray;
			$data['actualWordArray'] = $actualWordArray;
			
			$cacheLib->store($key, $data, 2*60*60);
		}

		return $data;
	}

	//update message text in case of auto moderation
	function updateMsgTextAutoModerate($updatedMsg, $entityId){
		$this->initiateModel('write');
		$query = "UPDATE messageTable set msgTxt = ? where msgId = ? and status='live'";
		$result = $this->dbHandle->query($query,array($updatedMsg, $entityId));
	}

	//update message Description for an entity in case of auto moderation
	function updateEntityDescription($updatedDesc, $entityId){
		$this->initiateModel('write');
		$query = "UPDATE messageDiscussion set description = ? where threadId = ? ";
		$result = $this->dbHandle->query($query,array($updatedDesc, $entityId));
		
	}

	//Insert content flag In DB for moderation panel
	function insertContentFlagInDB($entityId, $entityType, $email_flag, $phoneNo_flag, $url_flag){  
         $this->initiateModel('write');
         $queryCmd = "INSERT INTO moderationPanelContentFlagging (entityId, entityType, email_flag, phoneNo_flag, creationTime, url_flag) VALUES (?, ?, ?, ?, now(), ?) ON DUPLICATE KEY  UPDATE email_flag = ?, phoneNo_flag = ?, url_flag = ?";
         $query = $this->dbHandle->query($queryCmd, array($entityId, $entityType, $email_flag, $phoneNo_flag,$url_flag, $email_flag, $phoneNo_flag, $url_flag));
         
	}
	
	/**
	 * @author Abhinav
	 * @param String $threadType Valid thread Type (fromOthers/discussion)
	 * @param Date $date Valid Date in form of 'Y-m-d h:i:s'
	 * @param number $offSet Offset from where data to be picked
	 */
	public function getRecentThreads($threadType, $date, $offSet = 0, $limit = 20){
		if(!in_array($threadType, array('user','discussion'))){
			return array();
		}
		if(empty($offSet) || $offSet <= 0){
			$offSet = 0;
		}
		if(empty($limit) || $limit <= 0){
			$limit = 20;
		}
		if(empty($date)){
			$date = date('Y-m-d');
		}else{
			$date = date('Y-m-d', strtotime($date));
		}
		$upperDateTimeLimit = $date.' 23:59:59';
		if(strtotime($date) == strtotime(date('Y-m-d'))){
			//$lowerDateTimeLimit = $date.' 00:00:00';
			$lowerDateTimeLimit = date('Y-m-d', strtotime('-1 month', strtotime($date)))." 00:00:00";
		}else{
			$lowerDateTimeLimit = $date.' 00:00:00';
		}
		
		$result			= array();
		$childMsgIds	= array();
		$threadTypeCheckSql = "";
		$sql	=	"SELECT SQL_CALC_FOUND_ROWS mt1.threadId AS threadId,mt1.msgTxt AS msgTxt, mt1.creationDate AS creationDate, mt1.userId AS userId, mt1.msgId AS msgId1, mt1.status AS status, mt1.viewCount, mt1.msgCount, mt1.fromOthers FROM messageTable mt1 USE INDEX(creationDateIndex)"
					." WHERE mt1.creationDate >= '".$lowerDateTimeLimit."' AND mt1.creationDate <= '".$upperDateTimeLimit."' AND mt1.status IN ('live' , 'closed')";
		if($threadType == 'user'){
			$threadTypeCheckSql	=	" AND mt1.fromOthers = 'user' AND mt1.parentId = 0 AND mt1.mainAnswerId = - 1 ";
		}else{
			$threadTypeCheckSql	=	" AND mt1.fromOthers = 'discussion' AND mt1.mainAnswerId = 0 AND mt1.parentId = mt1.threadId";
		}
		
		$groupOrderBySql = " ORDER BY creationDate DESC";
		$limitSql	= " LIMIT ".$offSet.",".$limit;
		
		$sql = $sql.$threadTypeCheckSql.$groupOrderBySql.$limitSql;
		
		$this->initiateModel('read');
		// get result set data for threads
		$resultSet['threadData']	= $this->dbHandle->query($sql)->result_array();
		// number of threads found in result set data for threads
		$resultSet['rowsCount']		= $this->dbHandle->query("SELECT FOUND_ROWS() AS foundRows")->row_array();
		$resultSet['rowsCount']		= $resultSet['rowsCount']['foundRows'];
		
		foreach ($resultSet['threadData'] as $data){
			$result['data'][$data['threadId']]['threadId']				= $data['threadId'];
			$result['data'][$data['threadId']]['threadTxt']				= $data['msgTxt'];
			$result['data'][$data['threadId']]['threadCreationDate']	= $data['creationDate'];
			$result['data'][$data['threadId']]['threadOwner']			= $data['userId'];
			$result['data'][$data['threadId']]['status']				= $data['status'];
			if($data['fromOthers'] == 'user'){
				$result['data'][$data['threadId']]['viewCount']			= $data['viewCount'];
				$result['data'][$data['threadId']]['msgCount']			= $data['msgCount'];
			}else{
				$childMsgIds[] = $data['threadId'];
			}
			/* if($data['msgId1'] != $data['msgId2']){
				$childMsgIds[] = $data['msgId2'];
			} */
		}
		$result['totalRecords']	=	$resultSet['rowsCount'];
		
		$recentThreadIds = array_keys($result['data']);
		if(count($recentThreadIds) > 0){
			/* Adding SQL here to get recent Answer on question or Comment on discussion */
			$resultSet	= array();
			$sql	= "SELECT threadId, MAX(msgId) AS msgId FROM messageTable WHERE threadId IN(".implode(',', $recentThreadIds).") AND status IN('live','closed') ";
			if($threadType == 'user'){
				$sql	.= " AND fromOthers = 'user' AND parentId = threadId AND mainAnswerId = 0 ";
			}else{
				$sql	.= " AND fromOthers = 'discussion' AND parentId = mainAnswerId AND mainAnswerId > 0";
			}
			$sql	.= " GROUP BY threadId ";
			$resultSet	= $this->dbHandle->query($sql)->result_array();
			foreach ($resultSet as $data){
				if($result['data'][$data['threadId']] != $data['msgId']){
					$childMsgIds[]	= $data['msgId'];
				}
			}
			
			$resultSet	= array();
			if(!empty($childMsgIds)){ // if not empty child msgIds(answer/comment) then find their data
				$sql	=	"SELECT msgId, msgTxt, creationDate, threadId, userId, fromOthers, parentId, mainAnswerId, viewCount FROM messageTable"
						." WHERE status IN ('live','closed') AND msgId IN(".implode(",", $childMsgIds).") ";
							
						$resultSet = $this->dbHandle->query($sql)->result_array();
						foreach ($resultSet as $data){
							if($data['fromOthers'] == 'discussion' && $data['parentId'] == 0 && $data['mainAnswerId'] == -1){ // this check is for only to capture view count of discussion, as it is stored at dummy entry for a given discussion
								$result['data'][$data['threadId']]['viewCount'] = $data['viewCount'];
							}else{
								$result['data'][$data['threadId']]['answerCommentId']			= $data['msgId'];
								$result['data'][$data['threadId']]['answerCommentTxt']			= $data['msgTxt'];
								$result['data'][$data['threadId']]['answerCommentOwnerId']		= $data['userId'];
								$result['data'][$data['threadId']]['answerCommentCreationDate']	= $data['creationDate'];
							}
						}
			}
		}
		
		return $result;
	}

	function getExpertsCountByLevel()
	{
		$this->initiateModel('read');
		$sql = "select count(userId) as count, levelId as Level,levelName from userpointsystembymodule where modulename='AnA' AND  levelId >= 11  group by levelId,levelName order by levelId desc";
		$usersPerLevel = $this->dbHandle->query($sql)->result_array();
		/*_p($this->dbHandle->last_query());
		die;*/
		return $usersPerLevel;
		
	}
	/** 
		below function is used for get experts information
		@param: $level : specifies level Id default empty string (i.e all level ids greater than 10)
		@param : $start specifies
	*/
	function getExpertsInfo($level = '',$levelstart='',$start = '',$count = '')
	{
		if( $start >=0 && $count >= 0)
		{
			$limit = " limit ".$start.",".$count;
		}
		else
		{
			$limit = " ";
		}
		$this->initiateModel('read');
		if($level == 'All')
			$sql = "SELECT userId,userpointvaluebymodule as points,levelId,levelName FROM userpointsystembymodule WHERE moduleName = 'AnA'  AND levelId >= 11 ".($level == 'All'? " AND levelId <= ? " : " " )." order by levelId desc,userpointvaluebymodule desc ".$limit;
		else
		{
			$levelstart = $level;
			$sql = "SELECT userId,userpointvaluebymodule as points,levelId,levelName FROM userpointsystembymodule WHERE moduleName = 'AnA'  AND levelId = ?  order by levelId desc,userpointvaluebymodule desc ".$limit;
		}
		$result = $this->dbHandle->query($sql,$levelstart)->result_array();
		
		return $result;
	}

	public function getLinkedThreadsCount($threadId, $threadType, $status = array()){
		if(empty($threadId) || $threadId <= 0 || !in_array($threadType, array('question','discussion'))){
			return FALSE;
		}
        if(!is_array($status) || empty($status)){
            $status[] = 'accepted';
        }
		$sql	=	"SELECT COUNT(distinct(linkingEntityId)) as cnt FROM questionDiscussionLinkingTable qdlt, messageTable mt"
					." WHERE qdlt.linkedEntityId = ? AND qdlt.type = ? "
					." AND qdlt.status IN ('".implode("','", $status)."') AND qdlt.linkedEntityId = mt.threadId";
		if($threadType == 'question'){
			$sql .= " AND mt.fromOthers = 'user' AND mt.mainAnswerId = -1 AND mt.parentId = 0 ";
		}else{
			$sql .= " AND mt.fromOthers = 'discussion' AND  mt.mainAnswerId = 0 AND mt.parentId = mt.threadId";
		}
		$this->initiateModel('read');
		$resultSet	= $this->dbHandle->query($sql,array($threadId,$threadType))->row_array();
		error_log($sql);
		$result		= $resultSet['cnt'];
		return $result;
	}
	
	public function getThreadTitle($threadId, $threadType){
		if(empty($threadId) || $threadId < 1000000 || !in_array($threadType, array('question','discussion'))){
			return FALSE;
		}
		$sql	= "SELECT msgTxt FROM messageTable WHERE status IN ('live', 'closed') AND ";
		if($threadType == 'question'){
			$sql	.= " fromOthers = 'user' AND parentId = 0 AND mainAnswerId = -1 AND threadId = ?";
		}else{
			$sql	.= " fromOthers = 'discussion' AND parentId = threadId AND mainAnswerId = 0 AND threadId = ?";
		}
		$this->initiateModel('read');
		$resultSet	= $this->dbHandle->query($sql,array($threadId))->row_array();
		return $resultSet['msgTxt'];
	}
    
    public function checkIfLinkingThreadMappingExist($threadId, $linkingThreadId, $threadType){
        if(empty($threadId) || empty($linkingThreadId) || empty($threadType) || $threadId < 1000000 || $linkingThreadId < 1000000 || !in_array($threadType, array('question','discussion'))){
            return FALSE;
        }
        $sql    = "SELECT count(1) AS cnt FROM questionDiscussionLinkingTable WHERE status IN ('accepted', 'draft')"
                . " AND type = ? AND linkedEntityId = ? AND linkingEntityId = ?";
        $this->initiateModel('read');
        $resultSet  = $this->dbHandle->query($sql,array($threadType,$threadId,$linkingThreadId))->row_array();
        if($resultSet['cnt'] > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

        function getCountryTag($countryId){
                $this->initiateModel('read');
                $sql = "SELECT id, tags FROM tags t, countryTable c WHERE c.countryId = ? AND c.name = t.tags AND t.status = 'live' AND t.tag_entity IN ('Country','Country synonym') LIMIT 1";
                $countryTag = $this->dbHandle->query($sql, array($countryId))->result_array();
                return $countryTag;        
        }


    public function getQuestionsDetails($questionIdsForDetails, $loggedInUserId = 0,$ampFlag=false, $getRatings = true){

	$this->initiateModel('read');

	if($questionIdsForDetails == ''){
		return array();
	}

        //First fetch the latest answer of these Questions which have answers
        $sql = "SELECT MAX(m.msgId) as msgId, threadId
            FROM messageTable m
            WHERE m.status IN ('live','closed')
            AND m.parentId = m.threadId AND (select status from messageTable where msgId=m.threadId) IN ('live','closed')
            AND m.fromOthers='user' AND m.threadId IN ($questionIdsForDetails) GROUP BY m.threadId";
        $rows     = $this->dbHandle->query($sql)->result_array();

        //For each of these entities, now fetch the Details
        foreach ($rows as $row){
            $msgIds .= ($msgIds == '')?$row['msgId']:','.$row['msgId'];
	    	$threadIdArray[] = $row['threadId'];
        }
        if(empty($msgIds)){
			return array();
		}

        $answerDetailArray = array();
        $sql = "SELECT m.msgId, m.msgTxt, m.threadId as questionId, m.creationDate, m.userId, (SELECT digFlag FROM digUpUserMap WHERE productId=m.msgId AND userId=?  AND digUpStatus = 'live' LIMIT 1) hasUserVoted, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'question' AND status = 'follow' AND entityId = m.threadId) isUserFollowing, tuai.aboutMe, (SELECT count(*) FROM messageTable mt WHERE mt.mainAnswerId = m.msgId AND mt.status IN ('live','closed') AND mt.fromOthers = 'user' ) commentCount
            FROM messageTable m LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = m.userId)
            WHERE m.msgId IN ($msgIds) AND m.fromOthers='user'
            $sortByClause";
        $rows = $this->dbHandle->query($sql, array($loggedInUserId,$loggedInUserId))->result_array();

        foreach ($rows as $row){
            $questionIds .= ($questionIds == '')?$row['questionId']:','.$row['questionId'];
            $userIds .= ($userIds  == '')?$row['userId']:','.$row['userId'];
            $answerIds .= ($answerIds == '')?$row['msgId']:','.$row['msgId'];
            $answerId = $row['msgId'];
            $answerDetailArray[$answerId]['answerId'] = $answerId;
            $answerDetailArray[$answerId]['answerText'] = strip_tags(html_entity_decode($row['msgTxt']));
            $answerDetailArray[$answerId]['activityTime'] = makeRelativeTime($row['creationDate']);
            $answerDetailArray[$answerId]['answerOwnerUserId'] = $row['userId'];
            $answerDetailArray[$answerId]['questionId'] = $row['questionId'];
            $answerDetailArray[$answerId]['hasUserVotedUp'] = false;
            $answerDetailArray[$answerId]['hasUserVotedDown'] = false;
            $answerDetailArray[$answerId]['aboutMe'] = html_entity_decode($row['aboutMe']);
            $answerDetailArray[$answerId]['commentCount'] = $row['commentCount'];
            if($row['hasUserVoted'] == '1'){
                    $answerDetailArray[$answerId]['hasUserVotedUp'] = true;
            }
            else if($row['hasUserVoted'] == '0'){
                    $answerDetailArray[$answerId]['hasUserVotedDown'] = true;
            }
	    $answerDetailArray[$answerId]['answerText'] = sanitizeAnAMessageText($answerDetailArray[$answerId]['answerText'],'answer',$ampFlag);
        }
            
        //Get Questions details
        if($questionIds != ''){
	    $questionArray = explode(',',$questionIds);
            $questionDetailArrayInfo = $this->getQuestionsBasicDetails($questionArray);
        }

        //Get User Details
        if($userIds != ''){
            $userDetailArray = $this->getUserDetails($userIds);
        }
        
        //Get Upvotes & Downvotes details
        if($answerIds != '' && $getRatings){
	    $answerArray = explode(',',$answerIds);
            $ratingDetailArray = $this->getUpAndDownVotesOfEntities($answerArray);
        }

	//Check if there are any unanswered questions
	$unansweredQues = array_diff(explode(',',$questionIdsForDetails), $threadIdArray);
	if(is_array($unansweredQues) && count($unansweredQues)>0){
                $questionDetailArray = array();
		$questionMsgIds = implode(',',$unansweredQues);
                $sql = "SELECT m.msgId as questionId, m.msgTxt, m.creationDate, m.userId, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'question' AND status = 'follow' AND entityId = m.msgId) isUserFollowing , m.status, m.viewCount, tu.firstname, tu.lastname
                    FROM messageTable m, tuser tu WHERE m.userId = tu.userid AND m.msgId IN ($questionMsgIds)";
                $rows = $this->dbHandle->query($sql, array($loggedInUserId))->result_array();

                foreach ($rows as $row){
                    $questionIds .= ($questionIds == '')?$row['questionId']:','.$row['questionId'];
                    $userIds .= ($userIds  == '')?$row['userId']:','.$row['userId'];
                    $questionId = $row['questionId'];
                    $questionDetailArray[$questionId]['id'] = $questionId;
                    $questionDetailArray[$questionId]['title'] = strip_tags(html_entity_decode($row['msgTxt']));
                    $questionDetailArray[$questionId]['activityTime'] = makeRelativeTime($row['creationDate']);
                    $questionDetailArray[$questionId]['questionOwnerId'] = $row['userId'];
                    $questionDetailArray[$questionId]['answerCount'] = '0';
                    $questionDetailArray[$questionId]['viewCount'] = $row['viewCount'];
                    /// Change
                    $qDate = date('Y-m-d',strtotime($row['creationDate']));
                    $questionDetailArray[$questionId]['URL'] = getSeoUrl($questionId, 'question', $row['msgTxt'],array(),'NA',$qDate);
                    $questionDetailArray[$questionId]['isUserFollowing'] = false;
                    if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                            $questionDetailArray[$questionId]['isUserFollowing'] = true;
                    }
                    $questionDetailArray[$questionId]['isThreadOwner'] = ($row['userId'] == $loggedInUserId) ? true : false;
                    $questionDetailArray[$questionId]['threadStatus'] = $row['status'];
                    $questionDetailArray[$questionId]['questionOwnerName'] = $row['firstname'];
		    $questionDetailArray[$questionId]['title'] = sanitizeAnAMessageText($questionDetailArray[$questionId]['title'],'question',$ampFlag);
                }	
	}

        //Now, merge all the arrays
        $i = 0;
        foreach ($answerDetailArray as $entity){
            $questionId = $entity['questionId'];
	    $finalArray[$questionId] = $entity;
            $answerUserId = $entity['answerOwnerUserId'];
            $answerId = $entity['answerId'];

            if(isset($questionDetailArrayInfo[$questionId])){
                    $finalArray[$questionId] = array_merge($finalArray[$questionId],$questionDetailArrayInfo[$questionId]);
		    $finalArray[$questionId]['title'] = strip_tags(html_entity_decode($finalArray[$questionId]['msgTxt']));
		    unset($finalArray[$questionId]['msgTxt']);
                    $finalArray[$questionId]['isThreadOwner'] = ($questionDetailArrayInfo[$questionId]['threadOwnerUserId'] == $loggedInUserId) ? true : false;
            }
            if(isset($userDetailArray[$answerUserId])){
                    $finalArray[$questionId] = array_merge($finalArray[$questionId],$userDetailArray[$answerUserId]);
            }
            if(isset($ratingDetailArray[$answerId])){
                    $finalArray[$questionId]['likeCount'] = isset($ratingDetailArray[$answerId][1])?$ratingDetailArray[$answerId][1]:'0';
            }
            else{
                    $finalArray[$questionId]['likeCount'] = '0';
            }

            if(isset($ratingDetailArray[$answerId])){
                    $finalArray[$questionId]['dislikeCount'] = isset($ratingDetailArray[$answerId][0])?$ratingDetailArray[$answerId][0]:'0';
            }
            else{
                    $finalArray[$questionId]['dislikeCount'] = '0';
            }

            $i++;
        }

	$returnArray = array();
	foreach (explode(',',$questionIdsForDetails) as $questionId){
		if(isset($finalArray[$questionId]))
			$returnArray[$questionId] = $finalArray[$questionId];
		else if(isset($questionDetailArray[$questionId]))
                        $returnArray[$questionId] = $questionDetailArray[$questionId];
	}

	//Return both the answered and unanswered entities        
        return $returnArray;
    }
    

    function getUserDetails($userIds){
        $this->initiateModel('read');
        $this->load->helper('image');

        $userDetailArray = array();
        $userIds = explode(',', $userIds);
        $sql = "SELECT t.userid, firstname, lastname, displayname,avtarimageurl, (select upsm.levelName from userpointsystembymodule upsm where upsm.userId = t.userid and upsm.modulename = 'AnA') as levelName FROM tuser t WHERE t.userid IN (?) ";
        $user_rows = $this->dbHandle->query($sql,array($userIds))->result_array();
        foreach ($user_rows as $userDetails){
                 $userId = $userDetails['userid'];
                 $userDetailArray[$userId]['answerOwnerName'] = $userDetails['firstname']." ".$userDetails['lastname'];
                 $userDetailArray[$userId]['answerOwnerLevel'] = (isset($userDetails['levelName']) && $userDetails['levelName']!='')?$userDetails['levelName']:'Beginner-Level 1';
                 $userDetails['avtarimageurl'] = checkUserProfileImage($userDetails['avtarimageurl']);

                 $userDetailArray[$userId]['answerOwnerImage'] = $userDetails['avtarimageurl'];
        }
        return $userDetailArray;
    }

    function getCAInstituteId($userIds){

    	//Contract::mustBeNonEmptyArrayOfIntegerValues($userIds,'User ID');
    	$res = array();
    	if(!empty($userIds)){
    		$this->initiateModel('read');
	    	$sql = "SELECT userId, instituteId, si.name 
	    			from CA_ProfileTable CP 
	    			JOIN CA_MainCourseMappingTable cmct ON (cmct.caId = CP.id) 
	    			JOIN shiksha_institutes si ON si.listing_id = cmct.instituteId and si.status = 'live' 
	    			where CP.userId IN (?) and CP.profileStatus='accepted' and cmct.status='live' and badge='CurrentStudent'";
	    	$res = $this->dbHandle->query($sql,array($userIds))->result_array();
    	}
    	
    	return $res;

    }

    function getAllInstUniversity(){
    	$this->initiateModel('read');
    	$sql = "SELECT entity_id, entity_type, tag_id FROM tags_entity WHERE id >= 13602 AND entity_type in ('institute', 'National-University') AND status = 'live'";
    	return $this->dbHandle->query($sql)->result_array();
    }

    function getAllQuestionByCourse($courseArr){
    	if(count($courseArr)<=0){
    		return;
    	}
    	$courseStr = implode(',', $courseArr);
    	$this->initiateModel('read');
    	$sql = "SELECT courseId, messageId FROM questions_listing_response WHERE courseId in (?) AND creationTime >= '2016-03-01 00:00:00' AND status = 'live'";
    	return $this->dbHandle->query($sql,array($courseArr))->result_array();	
    }

    function pushData($data){
    	if(empty($data)>0){
    		return;
    	}
    	$this->initiateModel('write');
    	$this->dbHandle->insert('tags_content_mapping', $data);
    	return $this->dbHandle->insert_id();
    }

    function getExistTagsMapping($tagId,$msgId){
    	if(empty($tagId) || empty($msgId)){
    		return;
    	}
    	$this->initiateModel('read');
    	$sql = "SELECT id FROM tags_content_mapping 
    	        WHERE tag_id = ? AND content_id = ? 
    	        AND content_type = 'question' AND status = 'live'";
    	$res = $this->dbHandle->query($sql,array($tagId, $msgId))->result_array();
    	return ($res[0]['id']) ? true : false;
    }

    //get data of entities like question/answer/discusssiom/commnet/reply posted in given time period for auto moderation
	function getEntityForAutomoderationWithTimeRange(){
		$this->initiateModel();
		$result = array();
		$query = "SELECT msgId,msgTxt,fromOthers,parentId,mainAnswerId,mt.threadId 
				FROM messageTable mt 
				force index(creationDate) 
				WHERE status='live' AND parentId != 0 
				AND creationDate>='2017-04-01 00:00:00' 
				AND creationDate<='2017-09-12 23:59:59' 
				AND listingTypeId > 0
				AND fromOthers in ('user')";
		$result = $this->dbHandle->query($query)->result_array();
		return $result;
	}

	function getShikshaInternalWords(){
		$this->initiateModel('read');
	    $query = "select word from spellCheckWords where status = 'live'";
		$rs = $this->dbHandle->query($query)->result_array();
		foreach ($rs as $key => $value) {
			$wordList[] = $value['word'];
		}
		return $wordList; 
	}
   	
   	function getMsgTextById($msgId){
   		if(empty($msgId)){
   			return;
   		}
   		$this->initiateModel('read');
   		$query = "select msgTxt from messageTable where msgId = ?";
		//$query = "select msgId,msgTxt,fromOthers,parentId,mainAnswerId,mt.threadId, description from messageTable mt LEFT JOIN messageDiscussion md ON (mt.msgId = md.threadId) where status='live' and msgTxt != 'dummy' and listingTypeId = 0 and mt.msgId = ? and fromOthers in ('user','discussion')";
		$res = $this->dbHandle->query($query,array($msgId))->result_array();
		return $res[0];
   	}

   	function getTagsType($tag_ids){
   		if(empty($tag_ids)){
   			return;
   		}
   		$this->initiateModel('read');
    	$sql = "SELECT tag_id FROM tags_entity WHERE tag_id IN (?) AND entity_type in ('institute', 'National-University') AND status = 'live'";
    	return $this->dbHandle->query($sql,array($tag_ids))->result_array();
   	}

   	function removeListingTypeFromQuestion($msgId){
   		if(empty($msgId)){
            return;
        }
        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        $this->dbHandle->where(array('msgId'=>$msgId));
        $this->dbHandle->update('messageTable', array('listingTypeId'=>'0','listingType'=>''));

        $this->dbHandle->where(array('messageId'=>$msgId));
        $this->dbHandle->update('questions_listing_response', array('status'=>'deleted'));
       
        $this->dbHandle->where('msgId', $msgId);
      	$this->dbHandle->delete('AnARecommendation'); 
    
        $this->dbHandle->trans_complete();

        if ($this->dbHandle->trans_status() === FALSE) {
            # Something went wrong.
            $this->dbHandle->trans_rollback();
            return FALSE;
        }else{
            # Committing data to the database.
            $this->dbHandle->trans_commit();
            return TRUE;
        }
   	}

   	function getCollegeTagFromQuestion($msgId){
   		$resultArr = array();
   		if(empty($msgId)){
   			return;
   		}
   		$this->initiateModel('read');
    	$sql = "select c.tag_id, c.tag_type from tags_content_mapping c, tags t where c.tag_id=t.id and c.status='live' and t.tag_entity IN ('Colleges','University') and c.content_id = ?";
    	return $this->dbHandle->query($sql,array($msgId))->result_array();
   	}

   	function getCollegeParentTagFromQuestion($tagIdArr){
   		$parentTags = array();
   		if(empty($tagIdArr) || count($tagIdArr)<=0){
   			return;
   		}
   		$this->initiateModel('read');
    	$sql = "select parent_id from tags_parent where tag_id IN (?) and status = 'live'";
    	$result = $this->dbHandle->query($sql,array($tagIdArr))->result_array();
    	foreach ($result as $key => $value) {
                $parentTags[] = $value['parent_id'];    
        }
    	return $parentTags;
   	}

   	function removeCollegeTag($msgId, $tagIds, $tag_type, $parentTagArr, $parentTagType){
   		if(empty($msgId) || empty($tagIds) || empty($tag_type)){
   			return;
   		}
   			$this->initiateModel('write');
	   		$this->dbHandle->where_in('tag_id',$tagIds);
	   		$this->dbHandle->where_in('tag_type',$tag_type);
	    	$this->dbHandle->where(array('content_id'=>$msgId,'status'=>'live','content_type'=>'question'));
	        $this->dbHandle->update('tags_content_mapping', array('status'=>'deleted'));
	        $result = $this->dbHandle->affected_rows();
        if($result && !empty($parentTagArr) && !empty($parentTagType)){
        	$this->dbHandle->where_in('tag_id',$parentTagArr);
	   		$this->dbHandle->where_in('tag_type',$parentTagType);
	    	$this->dbHandle->where(array('content_id'=>$msgId,'status'=>'live','content_type'=>'question'));
	        $this->dbHandle->update('tags_content_mapping', array('status'=>'deleted'));
        }
        return $result;
   	}

   	function getDetailsForMultipleQuestions($quesIds){
	   		if(!(is_array($quesIds) && count($quesIds)>0)){
	        	return array();
	        }
   			$this->initiateModel('read');
   			$this->dbHandle->select('msgId, msgTxt as quesTxt, status, viewCount, creationDate, msgCount as answerCount, userId');
    		$this->dbHandle->from('messageTable');
   			$this->dbHandle->where_in('msgId',$quesIds);
	   		$this->dbHandle->where_in('status',array('live','closed'));
	   		return $this->dbHandle->get()->result_array();
   	}

   	function getUpvotedAnswerForMultipleQues($quesIds){	
   			if(!(is_array($quesIds) && count($quesIds)>0)){
	        	return array();
	        }
   			$this->initiateModel('read');
   			$this->dbHandle->select('mt.msgId,mt.msgTxt,mt.parentId,tuai.aboutMe, mt.userId');
   			$this->dbHandle->from('messageTable mt');
    		$this->dbHandle->join('tUserAdditionalInfo tuai','mt.userId = tuai.userId','left');
   			$this->dbHandle->where_in('mt.parentId',$quesIds);
	   		$this->dbHandle->where_in('mt.status',array('live'));
	   		$this->dbHandle->where('mt.mainAnswerId','0');
	   		$this->dbHandle->order_by('mt.digUp desc, mt.creationDate desc'); 

	   		$subQuery =  $this->dbHandle->_compile_select();
	   		$this->dbHandle->_reset_select();

	   		$sql = "SELECT * FROM (".$subQuery.") as temp GROUP BY parentId";
	   		$results = $this->dbHandle->query($sql)->result_array();

	   		$userId = $this->getColumnArray($results,'userId');
	   		
	   		$this->dbHandle->select('tu.displayName,tu.firstname,tu.lastname, tu.userid as userId,tu.avtarimageurl as picUrl,upsm.levelName');
	   		$this->dbHandle->join('userpointsystembymodule upsm','tu.userId = upsm.userId','left');
	   		$this->dbHandle->where_in('tu.userId',$userId);
	   		$this->dbHandle->where('upsm.modulename','AnA');
	   		$this->dbHandle->from('tuser tu');
	   		$userData =  $this->dbHandle->get()->result_array();

	   		$userDataMap = array();
	   		foreach ($userData as $user) {
	   			$userDataMap[$user['userId']] = $user;

	   		}
	   		$returnarray = array();
	   		foreach ($results as $key => $data) {
	   			$returnArray[$data['parentId']] = array('msgId'=>$data['msgId'],'ansTxt'=>$data['msgTxt'],'parentId'=>$data['parentId']);
	   			$returnArray[$data['parentId']]['userData'] = $userDataMap[$data['userId']];
	   			$returnArray[$data['parentId']]['userData']['aboutMe'] =  $data['aboutMe'];
	   		}

	   		return $returnArray;
   	}

   	function getDetailsForMultipleUnansweredQues($quesIds) {	
   			if(!(is_array($quesIds) && count($quesIds)>0)) {
	        	return array();
	        }
   			$this->initiateModel('read');
   			$this->dbHandle->select('mt.msgId, mt.msgTxt, mt.status ,mt.viewCount,mt.creationDate,t.id as tagId,t.tags as tagName,md.description,mt.userId,tu.firstname');
   			$this->dbHandle->from('messageTable mt');
   			$this->dbHandle->join('tuser tu',"tu.userid = mt.userId");
   			$this->dbHandle->join('messageDiscussion md',"md.threadId = mt.msgId",'left');
    		$this->dbHandle->join('tags_content_mapping tcm',"tcm.content_id = mt.msgId AND tcm.status = 'live' AND tcm.content_type = 'question' ",'left');
    		$this->dbHandle->join('tags t',"t.id = tcm.tag_id AND t.status = 'live' ",'left');
   			$this->dbHandle->where_in('mt.msgId',$quesIds);
	   		$this->dbHandle->where_in('mt.status',array('live', 'closed'));
	   		
	   		$result = $this->dbHandle->get()->result_array();

	   		$resultArray = array();
	   		foreach($result as $details){
	   			$resultArray[$details['msgId']]['quesDetails'] = array('msgId'=>$details['msgId'],'msgTxt'=>$details['msgTxt'], 'status'=>$details['status'],'viewCount'=>$details['viewCount'],'postedDate'=>$details['creationDate'],'userId'=>$details['userId'],'firstname'=>$details['firstname'],'description'=>$details['description']);
	   			$resultArray[$details['msgId']]['tags'][] = array('tagId'=>$details['tagId'],'tagName'=>$details['tagName']);
	   		}	
	   		return $resultArray;
   	}

   	function getTagDetailsForMultipleTag($tagIds){	
   			if(!(is_array($tagIds) && count($tagIds)>0)){
	        	return array();
	        }
   			$this->initiateModel('read');
   			$this->dbHandle->select('t.id, t.tags as tagName, count(DISTINCT tcm.content_id) as quesCount, sum(mt.msgCount) as ansCount');
   			$this->dbHandle->from('tags t');
    		$this->dbHandle->join('tags_content_mapping tcm','tcm.tag_id = t.id','inner');
    		$this->dbHandle->join('messageTable mt','mt.msgId = tcm.content_id','inner');
   			$this->dbHandle->where_in('t.id',$tagIds);
	   		$this->dbHandle->where('tcm.status','live');
	   		$this->dbHandle->where('t.status','live');
	   		$this->dbHandle->where_in('mt.status',array('live', 'closed'));
	   		$this->dbHandle->where('tcm.content_type','question');
	   		$this->dbHandle->group_by('t.id');  		
	   		$result = $this->dbHandle->get()->result_array();
	   		$resultArray = array();
	   		foreach($result as $details){
	   			$resultArray[$details['id']] = array('tagName'=>$details['tagName'],'quesCount'=>$details['quesCount'],'answerCount'=>$details['ansCount']);
	   		}

	   		return $resultArray;
   	}

   	function getFollowCountForMultipleTag($tagIds){
   		if(!(is_array($tagIds) && count($tagIds)>0)){
	        	return array();
	        }
	    $this->initiateModel('read');
   		$this->dbHandle->select('tft.entityId, count(tft.id) as followCount');
   		$this->dbHandle->from('tuserFollowTable tft');
   		$this->dbHandle->where_in('tft.entityId',$tagIds);
   		$this->dbHandle->where('tft.status','follow');
   		$this->dbHandle->where('tft.entityType','tag');
   		$this->dbHandle->group_by('tft.entityId');

   		$result = $this->dbHandle->get()->result_array();
   		foreach($result as $details){
   			$resultArray[$details['entityId']] = array('followCount'=>$details['followCount']);
   		}

   		return $resultArray;

   	}

   	function getQuestionsBasedOnQualityScore($tagIds){
   		if(!(is_array($tagIds) && count($tagIds)>0)){
	        	return array();
	    }

	    $this->initiateModel('read');
	    $sql = "select tcm.tag_id, substring_index(GROUP_CONCAT(distinct msgId order by tqt.qualityScore desc),',',2) tags from tags_content_mapping tcm join messageTable mt on (mt.msgId = tcm.content_id and mt.status='live' and mt.msgCount>0 and tcm.status = 'live') join threadQualityTable tqt on (tqt.threadId = mt.msgId) where tag_id IN (?) and tcm.content_type='question' group by tcm.tag_id";

	    $result = $this->dbHandle->query($sql,array($tagIds))->result_array();
	    $all_ques = array();
	    foreach($result as $key=>$val){
	    	$messageIds = explode(',',$val['tags']);
	    	$res[$val['tag_id']] = $messageIds;
	    	$all_ques = array_merge($all_ques, $messageIds);

	    }
	    
	   $finalArray = array();
	   if(!empty($all_ques)){
	   		$sql1 = "select msgId, msgTxt, creationDate as postedDate, viewCount from messageTable where msgId in (?) and status in ('live','closed')";

		   $ques_data = $this->dbHandle->query($sql1,array($all_ques))->result_array();
		   foreach($ques_data as $details){
		   		$data[$details['msgId']] = $details;
		   }
		   foreach ($res as $key => $questionsIds) {
		   		foreach ($questionsIds as $questionId) {
		   			$finalArray[$key][] = $data[$questionId];
		   		}
		   }
	   }
	   return $finalArray;
	}

	function getQuestionCountForTags($tagIds){
		if(!(is_array($tagIds) && count($tagIds)>0)){
	        	return array();
	    }

	    $this->initiateModel('read');

		$sql="SELECT tag_id, count(tag_id) as total_ques from tags_content_mapping tcm JOIN messageTable mt on (mt.msgId = tcm.content_id and mt.status in ('live','closed')) where tcm.tag_id in (?) and tcm.status = 'live' and tcm.content_type = 'question' group by tag_id";

		$result = $this->dbHandle->query($sql,array($tagIds))->result_array();
		foreach($result as $key=>$value){
			$finalArray[$value['tag_id']] = $value['total_ques'];
		}

		return $finalArray;

	}

   	function getOwnerIdOfAnswer($answerId){
   		if(empty($answerId)){
   			return;
   		}
		$this->initiateModel('read');
   		$this->dbHandle->select('userId');
   		$this->dbHandle->from('messageTable');
   		$this->dbHandle->where(array('msgId'=>$answerId));
   		$result = $this->dbHandle->get()->result_array();
   		return $result[0]['userId'];

   	}

   	function updateEditStatusOfAnswer($answerId){
   		if(empty($answerId)){
   			return;
   		}
   		$this->initiateModel('write');
        $queryCmd = "Update moderation_editRequests set editStatus = 'yes' where entityId = ? and status = 'live'";
        $query = $this->dbHandle->query($queryCmd, array($answerId));
        return true;
   	}

         function getAnAStats(){
            $finalArray = array();
            $this->initiateModel('read');

            //Find Answer Count
            $sql = "SELECT count(*) as answerCount FROM messageTable WHERE fromOthers='user' AND status='live' AND parentId=threadId";
            $rows = $this->dbHandle->query($sql)->result_array();
            $finalArray['answerCount'] = $rows[0]['answerCount'];

            //Find contributor Count
            $sql = "SELECT count(DISTINCT userId) as contributorCount FROM messageTable WHERE fromOthers='user' AND status='live' AND parentId=threadId";
            $rows = $this->dbHandle->query($sql)->result_array();
            $finalArray['contributorCount'] = $rows[0]['contributorCount'];

            //Find Topics Count
            $sql = "SELECT count(*) as topicCount FROM tags WHERE status = 'live'";
            $rows = $this->dbHandle->query($sql)->result_array();
            $finalArray['topicCount'] = $rows[0]['topicCount'];

            return $finalArray;
         }
	function getQuestionsDataFromSpecificDate($creationTime='2017-12-01 00:00:00',$endTime,$offset=0,$count = 500)
   	{
   		$this->initiateModel('read');
   		$query = "select m.msgId,m.msgTxt,qlr.instituteId from messageTable m LEFT JOIN questions_listing_response qlr	ON m.msgId = qlr.messageId where fromOthers = 'user' and parentId = 0 and m.status in ('live','closed') AND m.creationDate >= ? AND m.creationDate <= ? limit $offset,$count";
   		$result = $this->dbHandle->query($query,array($creationTime,$endTime))->result_array();
   		return $result;
   	}
   	function getQuestionsCountDataFromSpecificDate($creationTime,$endTime)
   	{
   		$this->initiateModel('read');
   		$query = "select count(1) as count from messageTable where fromOthers = 'user' AND status in ('live','closed') AND creationDate >= ? AND parentId = 0 AND creationDate <= ? ";
   		$result = $this->dbHandle->query($query,array($creationTime,$endTime))->result_array();
   		return $result[0]['count'];
   	}
   	function getQuestionsIdsFromSpecificDate($creationTime='2017-12-01 00:00:00',$endTime,$offset=0,$count = 500)
   	{
   		$this->initiateModel('read');
   		$query = "select distinct msgId from messageTable where fromOthers = 'user' and parentId = 0 and status in ('live','closed') AND creationDate >= ? AND creationDate <= ? limit $offset,$count";
   		$result = $this->dbHandle->query($query,array($creationTime,$endTime))->result_array();
   		return $result;
   	}
   	function insertFeedback($rating,$userId,$feedbackMessage,$questionId,$lastAnswerId,$numberOfAnswers)
   	{
   		        $this->initiateModel('write');
                $query = "INSERT IGNORE INTO qdpFeedbackLog (rating, userId, feedbackMessage, questionId, lastAnswerId, numberOfAnswers, time) VALUES (?,?,?,?,?,?,now())";
                $query = $this->dbHandle->query($query, array($rating,$userId,$feedbackMessage,$questionId,$lastAnswerId,$numberOfAnswers));
                return true;
   	}
   	function getFeedbackCount($questionId,$userId){
   		if(empty($questionId) || empty($userId)){
   			return;
   		}
		$this->initiateModel('read');
   		$this->dbHandle->select('count(*)  as count');
   		$this->dbHandle->from('qdpFeedbackLog');
   		$this->dbHandle->where(array('questionId'=>$questionId,'userId'=>$userId));
   		$result = $this->dbHandle->get()->result_array();
   		return $result[0]['count'];

   	}
   	function updateFeedbackLayerCount($userId,$questionId)
   	{
                if($userId > 0 && $questionId > 0){
                        $this->initiateModel('write');
                        $query = "INSERT INTO qdpFeedbackCount (userId, questionId,count) VALUES (?, ?,1) ON DUPLICATE KEY UPDATE count=count+1 ";;
                        $query = $this->dbHandle->query($query, array($userId, $questionId));
                        return true;
                }
                else{
                        return false;
                };   
   	}
   	function getFeedbackLayerDisplayCount($questionId,$userId){
   		if(empty($questionId) || empty($userId)){
   			return;
   		}
		$this->initiateModel('read');
   		$this->dbHandle->select('count');
   		$this->dbHandle->from('qdpFeedbackCount');
   		$this->dbHandle->where(array('questionId'=>$questionId,'userId'=>$userId));
   		$result = $this->dbHandle->get()->result_array();
   		return $result[0]['count'];

   	}
   	function getQuestionsAfterSpecificDate($date,$start,$count)
   	{
   		if(empty($date))
   			return;
   		$this->initiateModel('read');
   		$query = "select m.msgId,m.msgTxt,qlr.instituteId from messageTable m LEFT JOIN questions_listing_response qlr ON m.msgId = qlr.messageId AND qlr.status = 'live' where m.status = 'live' AND m.fromOthers = 'user' and m.parentId = 0 and m.creationDate >= ? limit $start,$count";
   		$result = $this->dbHandle->query($query,array($date))->result_array();
   		return $result;
   	}

   	function getAllChildCountBasedOnThreadId($threadIds){
   		$this->initiateModel('read');

   		$sql = "SELECT threadId as msgId,count(1) as msgCount FROM messageTable where threadId in (?) AND status = 'live' group by threadId";
   		$result = $this->dbHandle->query($sql,array($threadIds))->result_array();
   		$rs = array();
   		foreach ($result as $key => $value) {
   			$rs[] = array('msgId' => $value['msgId'],'msgCount' => $value['msgCount'] - 1);
   		}
   		return $rs;
   	}

   	function updateChildCountBasedOnThreadId($data){
   		$this->initiateModel('write');
   		$this->dbHandle->trans_start();
   		$this->dbHandle->update_batch('messageTable',$data,'msgId');
   		$this->dbHandle->trans_complete();
   		return ($this->dbHandle->trans_status() === FALSE) ? false : true;
   	}

    function getTopAnswerDetails($questionId){
        $this->initiateModel();
        $queryCmd = "select mt.msgId,mt.path,mt.abuse,mt.fromOthers as queryType, mt.msgTxt,mt.threadId, mt.userId, mt.parentId,mt.status, mt.creationDate, mt.digUp, mt.digDown, mt.msgCount as childCount from messageTable mt where mt.parentId = ? and mt.status in ('live','closed') and mt.mainAnswerId = 0 and mt.fromOthers = 'user' ORDER BY (digUp - digDown) DESC, creationDate DESC LIMIT 1";
		$answerDetails = $this->dbHandle->query($queryCmd, array($questionId))->result_array();

		//Get the Top Answer for each question
        $answeredUserIds = array();
        $finalAnswers = array();
        foreach ($answerDetails as $anskey => $ansalue) {
    		$userId = $ansalue['userId'];
            if(!empty($userId)){
				$userInfoResult = $this->getUserDetailsBasedOnIds(array($userId));
				$temp = array();
				$temp['msgId'] = $ansalue['msgId'];
				$temp['msgTxt'] = $ansalue['msgTxt'];
            	$temp['firstname'] = $userInfoResult[$userId]['firstname'];
                $temp['lastname'] = $userInfoResult[$userId]['lastname'];
                $temp['displayname'] = $userInfoResult[$userId]['displayname'];
    	        $temp['picUrl'] = $userInfoResult[$userId]['picUrl'];
            	$temp['aboutMe'] = $userInfoResult[$userId]['aboutMe'];
	            $temp['levelName'] = $userInfoResult[$userId]['levelName'];
				$finalAnswers[] = $temp;
            }
        }

		return $finalAnswers;
	}

    function isUserContributor($userId){
        $this->initiateModel();

	    $date = date("Y-m-d");
        $date = strtotime("-30 days",strtotime($date));
	    $date = date ( 'Y-m-d' , $date );

        $queryCmd = "SELECT count(*) as AnswerCount FROM messageTable WHERE status IN ('live','closed') AND parentId = threadId AND fromOthers='user' AND userId = ? AND creationDate >= ?";
        $query = $this->dbHandle->query($queryCmd, array($userId,$date));
        $result = $query->result_array();
        if($result[0]['AnswerCount'] >= 10){
			return true;
		}
		return false;
    }
}


?>
