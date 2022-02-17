<?php

/**
 * 
 * @author rahul
 *
 */
class CADiscussionHelper
{

		function __construct()
		{
				$this->CI = & get_instance();
		}		

		
		function rearrangeQnA($data,$questionType = 'All',$qnaRelatedData = array()) {
			$questionArray = array();
			foreach ($data as $index => $value) {
				$answerArray = array();
				if($value["mainAnswerId"] == -1) {
					$questionData = array();
					$questionData["msgId"] = $value["msgId"];
					$questionData["threadId"] = $value["threadId"];
					$questionData["parentId"] = $value["parentId"];
					$questionData["title"] = $value["msgTxt"];
					$questionData["digUp"] = $value["digUp"];
					$questionData["digdown"] = $value["digDown"];
					$questionData["userId"] = $value["userId"];
					$questionData["displayname"] = $value["displayname"];
					$questionData["firstname"] = $value["firstname"];
					$questionData["lastname"] = $value["lastname"];
					$questionData["creationDate"] = $value["creationDate"];
					$questionData["status"] = $value["status"];
					$questionData["reportedAbuse"] = $value["reportedAbuse"];
					$questionData['msgCount'] = $value['msgCount'] ? $value['msgCount'] : 0;
					$questionData['viewCount'] = $value['viewCount'] ? $value['viewCount'] : 0;
					$questionData["q_url"] = getSeoUrl($value["msgId"],'question',$value["msgTxt"],'','',$value["creationDate"]);
					if(in_array($value['msgId'], $qnaRelatedData['userHasAnsweredThreadIds']))
						$questionData['hasUserAnswered'] = 'true';
					if(!empty($qnaRelatedData['loggedInUserId']) && $qnaRelatedData['loggedInUserId'] == $value['userId'])
					{
						$questionData['isThreadOwner'] = 'true';
					}
					if(in_array($value['msgId'], $qnaRelatedData['isFollow']))
						$questionData['isUserFollowing'] = 'true';
					else
						$questionData['isUserFollowing'] = 'false';
					$questionData['followCount'] = $qnaRelatedData['followCount'][$value['msgId']]?$qnaRelatedData['followCount'][$value['msgId']]:0;
					if(!empty($qnaRelatedData['tags'][$value['msgId']]))
					{
						$i =0;
						foreach ($qnaRelatedData['tags'][$value['msgId']] as $tagKey => $tagValue) {
							$questionData['tags'][$i]['name'] = $tagValue['tags'];
							$questionData['tags'][$i++]['url'] = getSeoUrl($tagValue['id'], 'tag', $tagValue['tags']);
						}
					}
					$questionArray[$value["msgId"]]['data'] = $questionData;
				}else if ($value["mainAnswerId"] == 0) {
					$answerData = array();
					$answerData["msgId"] = $value["msgId"];
					$answerData["threadId"] = $value["threadId"];
					$answerData["parentId"] = $value["parentId"];
					$answerData["title"] = $value["msgTxt"];
					$answerData["digUp"] = $value["digUp"];
					$answerData["digdown"] = $value["digDown"];
					$answerData["questionId"] = $value["threadId"];
					$answerData["userId"] = $value["userId"];
					$answerData["displayname"] = $value["displayname"];
					$answerData["firstname"] = $value["firstname"];
					$answerData["lastname"] = $value["lastname"];		
					$answerData["creationDate"] = $value["creationDate"];
					$answerData["status"] = $value["status"];
					$answerData["reportedAbuse"] = $value["reportedAbuse"];
					$answerData['userLevel'] = $value['userLevel'];
					$answerData['userPicUrl'] = $value['avtarimageurl'];
					$answerData['userProfileUrl'] = $value['userProfileUrl'];
					$answerData['hasUserVotedUp'] = $value['hasUserVotedUp'] ? $value['hasUserVotedUp'] : 'false';
					$answerData['hasUserVotedDown'] = $value['hasUserVotedDown'] ? $value['hasUserVotedDown'] : 'false';
					$answerData['aboutMe'] = $value['aboutMe'];
					if($qnaRelatedData['isMobile'])
					{
						$questionArray[$value["threadId"]]["answers"] = $answerData;		
					}
					else
						$questionArray[$value["threadId"]]["answers"][$value["msgId"]]["data"] = $answerData;
				}else if ($value["mainAnswerId"] > 0) {
					$commentData = array();
					$commentData["msgId"] = $value["msgId"];
					$commentData["threadId"] = $value["threadId"];
					$commentData["parentId"] = $value["parentId"];
					$commentData["title"] = $value["msgTxt"];
					$commentData["digUp"] = $value["digUp"];
					$commentData["digdown"] = $value["digDown"];
					$commentData["questionId"] = $value["threadId"];
					$commentData["answerId"] = $value["parentId"];
					$commentData["userId"] = $value["userId"];
					$commentData["displayname"] = $value["displayname"];
					$commentData["firstname"] = $value["firstname"];
					$commentData["lastname"] = $value["lastname"];
					$commentData["creationDate"] = $value["creationDate"];
					$commentData["status"] = $value["status"];
					$commentData["reportedAbuse"] = $value["reportedAbuse"];
					$questionArray[$value["threadId"]]["answers"][$value["mainAnswerId"]]["comments"][] = $commentData;
				}
			}

			
			foreach ($questionArray as $questionId => $questionData) {
				if(!empty($questionData["data"])) {
					$questionArrayNew[$questionId] = $questionData;
				}
			}
				
			$questionArray = $questionArrayNew;
				
			if($questionType == 'Unanswered') {
				$formatedQuestions = array();
				foreach ($questionArray as $questionId => $questionData) {
					if(empty($questionData['answers'])) {
						$formatedQuestions[$questionId] = $questionData;
					}
				}
				$questionArray = $formatedQuestions;
			}
			
			$returnData["count"] = count($questionArray);
			$returnData["data"] = $questionArray;
			
			return $returnData;
		}
		
		function getQuestionIds($questions) {
		
			$questionIds = array();
		
			foreach ($questions as $index => $data) {
				$questionIds[] = $data["msgId"];
			}
			
			return $questionIds;
		}

		function getQuestionIdsFromAnswer($answers){
			$questionIds = array();
		
			foreach ($answers as $index => $data) {
				$questionIds[] = $data["threadId"];
			}
			
			return $questionIds;
		}
		
		function formatCAData($caArray,$repsCount = 3,$campusInstituteId) {
				$count = 0;
				$formattedCAArray = array();
				foreach($caArray as $index => $caData['data']) {	
					foreach($caData['data'] as $i => $data1 ) {
					 
						foreach($data1 as $i => $data ) {
						
						if(!empty($data)) {
								if($data['badge'] == 'CurrentStudent' && $count < $repsCount) {
									$formattedCArray[] = $data;
									$count++;
								}
						}
						}
					}
				}
				
				foreach($caArray as $index => $caData['data']) {

					foreach($caData['data'] as $i => $data1 ) { 

						foreach($data1 as $i => $data ) {
						if(!empty($data)) {
							if($data['officialBadge'] == 'Official' && $count < $repsCount ) {
									$formattedCArray[] = $data;
									$count++;
								}
						}
						}
					}
				}
				
				foreach($caArray as $index => $caData['data']) {
				
					foreach($caData['data'] as $i => $data1 ) { 
		
						foreach($data1 as $i => $data ) {
						if(!empty($data)) {
								if($data['badge'] == 'Alumni' && $count < $repsCount) {
									$formattedCArray[] = $data;
									$count++;
								}
						}
						}
					}
				}
				return $formattedCArray;

		}
		
		
		/**
		 * Get badges for CA as a map of UserId => Badge
		 * takes input as campus rep data array indexed as 'data'
		 * @returns an array of data
		 */
		function getBadgesForCA($campusConnectData) {

			$userIdVsBadge = array();
			
			foreach ($campusConnectData as $index => $data) {
				$userIdVsBadge[$data['userId']] = $data['badge'];
			}
			
			return $userIdVsBadge;
		}
		
		/*
		function getCARank($caId,$caData,$caAnsweredCount) {
			
			$rank = 0 ;
			foreach ($caData as $id => $data) {
				$tempCAId = $data["caId"];
				$tempCaAnsweredCount = $data["answeredCount"];
				if( ($caId != $tempCAId) && ($tempCaAnsweredCount > $caAnsweredCount) ) {
					$rank++;
				}
			}
			return $rank;
		}
		
		function getCaContentForMailer($caData, $allCaData) {
			
			$returnData = array();
			$retunData["answers"] = $caData["ansered"];
// 			$returData[""]
		}
	*/
		
		
		/**
		 * 
		 * @param array indexed with course name $caArray
		 * @param integer $repsCount
		 * sorts the campus reps for different courses in order CurrentStudent , Official  ,  Alumni
		 * @return array of campus reps
		 */
		function formatCADataForListing($caArray,$repsCount = 3) {
			$count = 0;
			$formattedCAArray = array();
			
			$caData = $caArray['caInfo'];
			
			foreach ($caData as $course => $courseData) {
				foreach ($courseData as $index => $data) {
					if(!empty($data)) {
						if($data['badge'] == 'CurrentStudent') {
							$formattedCArray[] = $data;
							$count++;
						}
			
					}
				}
			}
			
			foreach ($caData as $course => $courseData) {
				foreach ($courseData as $index => $data) {
					if(!empty($data)) {
						if($data['badge'] == 'Official' ) {
							$formattedCArray[] = $data;
							$count++;
						}
			
					}
				}
			}
			
			foreach ($caData as $course => $courseData) {
				foreach ($courseData as $index => $data) {
					if(!empty($data)) {
						if($data['badge'] == 'Alumni') {
							$formattedCArray[] = $data;
							$count++;
						}
			
					}
				}
			}
			
			$userVsBadgeMap = array();			
			$returnArray = array();
			
			foreach ($formattedCArray as $index => $caData ) {
				$include = false;
				if(!empty($userVsBadgeMap)) {
					if( !(!empty($userVsBadgeMap[$caData['userId']]) && $userVsBadgeMap[$caData['userId']] == $caData['badge']) ){
						$include = true;	
					}
				}else {
					$include = true;
				}
				if($include) {
					$returnArray[] = $formattedCArray[$index];
					$userVsBadgeMap[$caData['userId']] = $caData['badge'];
				}
			}
			
			$returnArray = array_slice($returnArray, 0,$repsCount);
			return $returnArray;

       }
       
       function checkIfCampusRepExistsForCourse($courseIds,$fromPage){
			$result = array();
			if(empty($courseIds)){
				return $result;	
			}
			$this->CI->load->model('CA/camodel');
                        $this->CI->CAModel = new CAModel();
			$result  = $this->CI->CAModel->checkIfCampusRepForCourses($courseIds,$fromPage);
			return $result;
      }
      
      /***
	 * functionName : _separateCampusRepData
	 * functionType : no return type
	 * param        : $campusRepData(array), $instituteId, $pageType(institute/course)
	 * desciption   : separate campus rep data for mba/be/b.tech widget on the institute page / course page 
	 * @author      : akhter
	 * @team        : UGC
	***/
      function _separateCampusRepData($campusRepData, $instituteId, $pageType, $allowSubCatArr)
      {
        $repData = array();
	    $campusConnectCourses = array();
	    $numOfCACourses = array();
	    $existBeUser = array();$existUser = array();
        $this->CI->load->model('CA/cadiscussionmodel');
        $this->CI->CADiscussionModel = new CADiscussionModel();
	    array_push($allowSubCatArr,'23');
	    $resultData = $this->CI->CADiscussionModel->_checkCategoryOnCourse(array_keys($campusRepData['caInfo']), $allowSubCatArr);

	    foreach($campusRepData['caInfo'] as $courseId=>$value)
	    {
			foreach($value as $val)
			{
				if(in_array($resultData[$courseId],$allowSubCatArr)){
					//check duplicate user entry in mba array 
					if(!in_array($val['userId'],$existUser)){
							$repData['repInfo'][] = $val;
							$existUser[] = $val['userId'];
					}
					if(!in_array($courseId,$numOfCACourses['mbaCourse']))
					{
							$numOfCACourses['mbaCourse'][] = $courseId;
							$campusConnectCourses['mbaCourseObj'][] = $this->getRepCourseObject($courseId);
					}
				}	
			}
		
	    }
	    
	    $repData['repInfo']['totalRep'] = (count($repData['repInfo'])>0) ? count($repData['repInfo']) : 0;
	    
	    $repData['numberOfCACourses'] = $numOfCACourses;
	    $repData['campusConnectCourses'] = $campusConnectCourses;
	    
	    if(count($repData['repInfo'])>0 && $pageType == 'institute')
	    {
		foreach($repData['repInfo'] as $mba)
		{
		    if($mba['userId'] !='')
		    {
			$mbaUserIds[] = $mba['userId'];
		    }
		}
		if(count($mbaUserIds)>0){
		    $mbaRes = $this->CI->CADiscussionModel->_getCampusRepAnswerCount($instituteId,'institute',$mbaUserIds,$numOfCACourses['mbaCourse']);
		}
			$repData['repInfo']['commentCount'] = ($mbaRes['commentCount']>0) ? $mbaRes['commentCount']: 0;
	    }else if(count($repData['repInfo'])>0 && $pageType == 'course'){
			$repData['repInfo']['commentCount'] = ($campusRepData['commentCount']>0) ? $campusRepData['commentCount']: 0;
	    }
	    $displayData['repData'] = $repData;
	    return $displayData;
     }
     
     /**
      *use : using in _separateCampusRepData() function for campus rep     
      *@author : akhter
      *@team : UGC
      **/
     function getRepCourseObject($courseId) {
	    $this->CI->load->builder('ListingBuilder','listing');
	    $this->CI->listingBuilder = new ListingBuilder;
	    $this->courseRepository = $this->CI->listingBuilder->getCourseRepository();
	    $course = $this->courseRepository->find($courseId);
	    return $course;
     }

     function getAllCampusReps($courseIdString){
     	$this->CI->load->model('CA/cadiscussionmodel');
        $CADiscussionModel = new CADiscussionModel();
        $result = $CADiscussionModel->_isCAOnCourses($courseIdString);
        foreach ($result as $k => $val) {
        	foreach ($val as $key => $value) {
        		$caDetails[$value['courseId']][$key] = $value['userId'];
        	}
        }
        return $caDetails;
     }

}
?>
