<?php

class tagsmodel extends MY_Model
{
    private $CI;
    function __construct()
    {
    	parent::__construct('User');
		$this->db = $this->getReadHandle();
		$this->CI = &get_instance();
		$this->CI->load->helper('messageBoard/ana');
    }

    function getTagDetails($tagId){
	$finalArray = array();
	$dbHandle = $this->getReadHandle();

	//Fing Tag Name, description, tag type
	$sql = "SELECT tags, tag_entity, description FROM tags WHERE id = ? AND status = 'live'";
	$rows = $dbHandle->query($sql, array($tagId))->result_array();
	if(is_array($rows) && count($rows)>0){
		$finalArray['tagName'] = $rows[0]['tags'];
		$finalArray['tagType'] = $rows[0]['tag_entity'];
		$finalArray['description'] = $rows[0]['description'];
	}
	else{
		return $finalArray;
	}

	//Find the total number of Content attached to it
	$finalArray['questionCount'] = 0;
	$finalArray['discussionCount'] = 0;
        $sql = "SELECT count(DISTINCT content_id) contentCount, content_type FROM tags_content_mapping t, messageTable m WHERE tag_id = ? AND t.status = 'live' AND t.content_id = m.msgId AND m.status IN ('live','closed') GROUP BY content_type";
        $rows = $dbHandle->query($sql, array($tagId))->result_array();
	foreach ($rows as $contentType){
		$contentName = $contentType['content_type'];
		if($contentName == 'question' || $contentName == 'discussion'){
			$finalArray[$contentName.'Count'] = $contentType['contentCount'];
		}
	}

	//Find the number of Followers of this Tag
        $sql = "SELECT count(*) followerCount FROM tuserFollowTable use index (idx_composite) WHERE entityId = ? AND status = 'follow' AND entityType = 'tag'";
        $rows = $dbHandle->query($sql, array($tagId))->result_array();
        if(is_array($rows) && count($rows)>0){
                $finalArray['followerCount'] = $rows[0]['followerCount'];
        }
        else{
                $finalArray['followerCount'] = 0;
        }

	//Find the no. of Experts who have answered/commented on this Tag in Past 6 months
	$fromDate = date("Y-m-d", strtotime("-6 months"));
	$sql = "SELECT count(DISTINCT userId) as expertCount FROM messageTable m, tags_content_mapping tc WHERE m.threadId = tc.content_id AND tc.tag_id = ? AND m.status = 'live' AND ((m.parentId = m.threadId AND m.fromOthers = 'user') OR (m.fromOthers='discussion' AND m.mainAnswerId = m.parentId)) AND m.creationDate >= '$fromDate' AND tc.status = 'live'";
        $rows = $dbHandle->query($sql, array($tagId))->result_array();
        if(is_array($rows) && count($rows)>0){
                $finalArray['expertCount'] = $rows[0]['expertCount'];
        }
        else{
                $finalArray['expertCount'] = 0;
        }

	return $finalArray;
	
    }

    function isUserFollowingTag($userId, $tagArray, $entityType = 'tag'){
	$dbHandle = $this->getReadHandle();
	$finalArray = array();
	$tagString = implode(",",array_filter($tagArray));
	
	if(!empty($tagString)){
	        //Find if the user is following this Tag
        	$sql = "SELECT entityId as tagId, 1 as following FROM tuserFollowTable WHERE userId = ? AND status = 'follow' AND entityType = ? AND entityId IN (?)";
	        $rows = $dbHandle->query($sql, array($userId,$entityType,$tagArray))->result_array();
                $finalArray = array();
                foreach ($tagArray as $tag){
                        $entryFound = false;
                        foreach ($rows as $row){
                                if($tag == $row['tagId']){
                                        $finalArray[$tag] = 'true';
                                        $entryFound = true;
                                }
                        }
                        if(!$entryFound){
                                $finalArray[$tag] = 'false';
                        }
                }
                return $finalArray;
	}
	return array();
    }

    function entityFollowerCount($entityArray, $type = "tag"){
	$dbHandle = $this->getReadHandle();
	$entityString = implode(",",array_filter($entityArray));
	if(!empty($entityString)){
        	$sql = "SELECT count(*) followerCount, entityId FROM tuserFollowTable USE INDEX (idx_composite) WHERE entityId IN (?) AND status = 'follow' AND entityType = ? GROUP BY entityId";
	        $rows = $dbHandle->query($sql, array($entityArray,$type))->result_array();
		$finalArray = array();
		foreach ($entityArray as $entity){
			$entryFound = false;
			foreach ($rows as $row){
				if($entity == $row['entityId']){
					$finalArray[$entity] = $row['followerCount'];
					$entryFound = true;
				}
			}
			if(!$entryFound){
				$finalArray[$entity] = 0;
			}
		}
		return $finalArray;
	}
	return array();
    }

    function getDetailPageContent($tagId, $contentType, $start, $count, $sorting, $loggedInUserId){

	$finalArray = array();
        $dbHandle = $this->getReadHandle();
	$this->load->helper('image');
	global $isMobileApp;
	
        $sortByClause = "ORDER BY msgId DESC";
	
	//Set the Query by Content Type
	if($contentType == "discussion"){
	    
	    //Find the Latest Discussions/Comments
	    $sql = "SELECT MAX(m.msgId) as msgId  
		FROM messageTable m, tags_content_mapping t use index (tag_id) 
		WHERE m.threadId = t.content_id AND t.status = 'live' AND m.status IN ('live','closed')
		AND t.content_type = 'discussion' AND t.tag_id = ? AND (m.parentId = m.threadId OR m.parentId = m.mainAnswerId) AND (select status from messageTable where msgId=m.threadId) IN ('live','closed') 
		AND m.fromOthers='discussion' GROUP BY m.threadId 
		$sortByClause LIMIT $start,$count";
	    $rows     = $dbHandle->query($sql, array($tagId))->result_array();

	    //For each of these entities, now fetch the Details
            foreach ($rows as $row){
		$msgIds .= ($msgIds == '')?$row['msgId']:','.$row['msgId'];
            }
            if($msgIds == ""){
                return array();
            }

	    $disDetailArray = array();
            $sql = "SELECT msgId, threadId, mainAnswerId, parentId, creationDate, m.userId, msgTxt, (SELECT digFlag FROM digUpUserMap WHERE productId=msgId AND userId=? AND digUpStatus = 'live' LIMIT 1) hasUserVoted, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'discussion' AND status = 'follow' AND entityId = threadId) isUserFollowing ,tuai.aboutMe, (SELECT count(*) FROM messageTable mt WHERE mt.fromOthers='discussion' AND mt.status IN ('live','closed') AND mt.parentId = m.msgId ) commentCount  
                FROM messageTable m LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = m.userId)
                WHERE m.msgId IN ($msgIds) AND m.fromOthers='discussion' ORDER BY creationDate DESC";
            $rows     = $dbHandle->query($sql, array($loggedInUserId,$loggedInUserId))->result_array();

            //For each of these entities, now fetch the Details
            foreach ($rows as $row){
                $discussionIds .= ($discussionIds == '')?$row['threadId']:','.$row['threadId'];
                $userIds .= ($userIds  == '')?$row['userId']:','.$row['userId'];
                $discussionId = $row['threadId'];
                if ($row['parentId'] == $row['mainAnswerId']){  //This is a comment
                        $commentIds .= ($commentIds == '')?$row['msgId']:','.$row['msgId'];
                        $commentId = $row['msgId'];
                        $disDetailArray[$discussionId]['answerId'] = $commentId;
                        $disDetailArray[$discussionId]['answerText'] = strip_tags(html_entity_decode($row['msgTxt']));
                        $disDetailArray[$discussionId]['answerOwnerUserId'] = $row['userId'];
                        $disDetailArray[$discussionId]['hasUserVotedUp'] = false;
                        $disDetailArray[$discussionId]['hasUserVotedDown'] = false;
			$disDetailArray[$discussionId]['aboutMe'] = html_entity_decode($row['aboutMe']);
			$disDetailArray[$discussionId]['commentCount'] = $row['commentCount'];
                        if($row['hasUserVoted'] == '1'){
                                $disDetailArray[$discussionId]['hasUserVotedUp'] = true;
                        }
                        else if($row['hasUserVoted'] == '0'){
                                $disDetailArray[$discussionId]['hasUserVotedDown'] = true;
                        }
			
			if($isMobileApp){
				$disDetailArray[$discussionId]['answerText'] = sanitizeAnAMessageText($disDetailArray[$discussionId]['answerText'],'answer');
			}
                }
                else if($row['parentId'] == $row['threadId']){  //This is a discussion
                        $disDetailArray[$discussionId]['discussionUserId'] = $row['userId'];
                }
                $disDetailArray[$discussionId]['discussionId'] = $row['threadId'];
                $disDetailArray[$discussionId]['activityTime'] = makeRelativeTime($row['creationDate']);
                $disDetailArray[$discussionId]['isUserFollowing'] = false;
                if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                        $disDetailArray[$discussionId]['isUserFollowing'] = true;
                }
                $disDetailArray[$discussionId]['isThreadOwner'] = ($row['userId'] == $loggedInUserId) ? true : false;

            }

            $this->load->model('messageBoard/AnAModel');
            $discussionArray = explode(",",$discussionIds);
            //Get Tags on Questions
            $contentTags = $this->AnAModel->getContentTags($discussionArray, "discussion");
   	    $contentTags = $this->formatContentTags($contentTags, $tagId);
            //Get Follower count on Questions
            $contentFollowers = $this->entityFollowerCount($discussionArray, "discussion");

            //Get Questions details
            if($discussionIds != ''){
                $discussionDetailArray = $this->getDiscussionDetails($discussionIds);
            }

            //Get User Details
            if($userIds != ''){
                $userDetailArray = $this->getUserDetails($userIds);
            }

            //Get Upvotes details
            if($commentIds != ''){
                $ratingDetailArray = $this->getUpvotes($commentIds);
            }

	    //Get Upvotes details
            if($commentIds != ''){
                $ratingDownvotesDetailArray = $this->geDownvotes($commentIds);
            }

            //Now, merge all the arrays
            $i = 0;
            foreach ($disDetailArray as $entity){
                $finalArray[$i] = $entity;
                $finalArray[$i]['type'] = 'D';
		$finalArray[$i]['uniqueId'] = '2';
                $discussionId = $entity['discussionId'];
		
                $userId = isset($entity['answerOwnerUserId'])?$entity['answerOwnerUserId']:$entity['discussionUserId'];
                $commentId = isset($entity['answerId'])?$entity['answerId']:'';

                if(isset($discussionDetailArray[$discussionId])){
                        $finalArray[$i] = array_merge($finalArray[$i],$discussionDetailArray[$discussionId]);
                }
                if(isset($userDetailArray[$userId])){
                        $finalArray[$i] = array_merge($finalArray[$i],$userDetailArray[$userId]);
                }
                if(isset($contentTags[$discussionId])){
                        $finalArray[$i]['tags'] = $contentTags[$discussionId];
                }
                if(isset($contentFollowers[$discussionId])){
                        $finalArray[$i]['followerCount'] = $contentFollowers[$discussionId];
                }
                else{
                        $finalArray[$i]['followerCount'] = '0';
                }

                if(isset($ratingDetailArray[$commentId])){
                        $finalArray[$i]['likeCount'] = $ratingDetailArray[$commentId]['likes'];
                }
                else{
                        $finalArray[$i]['likeCount'] = '0';
                }

                if(isset($ratingDownvotesDetailArray[$commentId])){
                        $finalArray[$i]['dislikeCount'] = $ratingDownvotesDetailArray[$commentId]['dislikes'];
                }
                else{
                        $finalArray[$i]['dislikeCount'] = '0';
                }
		unset ($finalArray[$i]['discussionId']);

                $i++;
            }
            return $finalArray;

	}
	else if($contentType == "unanswered"){
            $questionDetailArray = array();
            $sql = "SELECT m.msgId as questionId, m.msgTxt, m.creationDate, m.userId, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'question' AND status = 'follow' AND entityId = m.msgId) isUserFollowing , m.status, m.viewCount, tu.firstname, tu.lastname 
                FROM messageTable m, tuser tu, tags_content_mapping t  
                WHERE m.userId=tu.userid AND m.msgId = t.content_id AND t.status = 'live' AND m.status IN ('live','closed') AND
                t.content_type = 'question' AND t.tag_id = ? AND m.parentId = 0 AND m.fromOthers='user' AND m.msgCount = 0 
                ORDER BY t.content_id DESC LIMIT $start,$count";
            $rows = $dbHandle->query($sql, array($loggedInUserId,$tagId))->result_array();

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

                $qDate = date('Y-m-d',strtotime($row['creationDate']));
                $questionDetailArray[$questionId]['URL'] = getSeoUrl($questionId, 'question', $row['msgTxt'],array(),'NA',$qDate);
                $questionDetailArray[$questionId]['isUserFollowing'] = false;
                if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                        $questionDetailArray[$questionId]['isUserFollowing'] = true;
                }
                $questionDetailArray[$questionId]['isThreadOwner'] = ($row['userId'] == $loggedInUserId) ? true : false;
                $questionDetailArray[$questionId]['threadStatus'] = $row['status'];
                $questionDetailArray[$questionId]['questionOwnerName'] = $row['firstname'];
		
		if($isMobileApp){
			$questionDetailArray[$questionId]['title'] = sanitizeAnAMessageText($questionDetailArray[$questionId]['title'],'question');
		}
            }

            $this->load->model('messageBoard/AnAModel');
            $questionArray = explode(",",$questionIds);
            //Get Tags on Questions
            $contentTags = $this->AnAModel->getContentTags($questionArray, "question");
	    $contentTags = $this->formatContentTags($contentTags, $tagId);

            //Get Follower count on Questions
            $contentFollowers = $this->entityFollowerCount($questionArray, "question");

            //Now, merge all the arrays
            $i = 0;
            foreach ($questionDetailArray as $entity){
                $finalArray[$i] = $entity;
                $finalArray[$i]['type'] = 'Q';
		$finalArray[$i]['uniqueId'] = '1';
                $questionId = $entity['id'];

                if(isset($contentTags[$questionId])){
                        $finalArray[$i]['tags'] = $contentTags[$questionId];
                }
                if(isset($contentFollowers[$questionId])){
                        $finalArray[$i]['followerCount'] = $contentFollowers[$questionId];
                }
		else{
			$finalArray[$i]['followerCount'] = '0';
		}
                $i++;
            }
            return $finalArray;

	}
        else if($contentType == "question"){

            //Find the Latest Answers
            $sql = "SELECT MAX(m.msgId) as msgId
                FROM messageTable m, tags_content_mapping t use index (tag_id) 
                WHERE m.threadId = t.content_id AND t.status = 'live' AND m.status = 'live' 
                AND t.content_type = 'question' AND t.tag_id = ? AND m.parentId = m.threadId AND (select status from messageTable where msgId=m.threadId) IN ('live','closed') 
                AND m.fromOthers='user' GROUP BY m.threadId
                $sortByClause LIMIT $start,$count";
            $rows     = $dbHandle->query($sql, array($tagId))->result_array();
            //For each of these entities, now fetch the Details
            foreach ($rows as $row){
                $msgIds .= ($msgIds == '')?$row['msgId']:','.$row['msgId'];
            }

	    if($msgIds == ""){
		return array();
	    }
	    $answerDetailArray = array();
	    $sql = "SELECT m.msgId, m.msgTxt, m.threadId as questionId, m.creationDate, m.userId, (SELECT digFlag FROM digUpUserMap WHERE productId=m.msgId AND userId=?  AND digUpStatus = 'live' LIMIT 1) hasUserVoted, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'question' AND status = 'follow' AND entityId = m.threadId) isUserFollowing, tuai.aboutMe, (SELECT count(*) FROM messageTable mt WHERE mt.mainAnswerId = m.msgId AND mt.status IN ('live','closed') AND mt.fromOthers = 'user' ) commentCount 
		FROM messageTable m LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = m.userId)
		WHERE m.msgId IN ($msgIds) AND m.fromOthers='user' 
		$sortByClause";
	    $rows = $dbHandle->query($sql, array($loggedInUserId,$loggedInUserId))->result_array();
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
                $answerDetailArray[$answerId]['isUserFollowing'] = false;
                if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                        $answerDetailArray[$answerId]['isUserFollowing'] = true;
                }
		
		if($isMobileApp){
			$answerDetailArray[$answerId]['answerText'] = sanitizeAnAMessageText($answerDetailArray[$answerId]['answerText'],'answer');
		}

	    }

            $this->load->model('messageBoard/AnAModel');
	    $questionArray = explode(",",$questionIds);
	    //Get Tags on Questions
            $contentTags = $this->AnAModel->getContentTags($questionArray, "question");
	    $contentTags = $this->formatContentTags($contentTags, $tagId);
	    //Get Follower count on Questions
            $contentFollowers = $this->entityFollowerCount($questionArray, "question");

	    //Get Questions details
	    if($questionIds != ''){
		$questionDetailArray = $this->getQuestionDetails($questionIds);
	    }
        // has user answered
        $answeredQuestions = array();
        if($questionIds != '' && $loggedInUserId > 0){
            $answeredQuestions = $this->getQuestionsAnsweredByUser($questionIds, $loggedInUserId);
        }
	    //Get User Details
	    if($userIds != ''){
		$userDetailArray = $this->getUserDetails($userIds);
	    }
	    //Get Upvotes details
            if($answerIds != ''){
		$ratingDetailArray = $this->getUpvotes($answerIds);
            }

	    //Get Upvotes details
            if($answerIds != ''){
		$ratingDownvotesDetailArray = $this->geDownvotes($answerIds);

		//Get list of Suggested Institues for the Answers
                $this->load->library("common_api/APICommonLib");
                $apiCommonLib = new APICommonLib();
                $suggestedInstitutes = $apiCommonLib->getSuggestedInstitutes($answerIds);
            }
	    //Now, merge all the arrays
	    $i = 0;
	    foreach ($answerDetailArray as $entity){
		$finalArray[$i] = $entity;
		$finalArray[$i]['type'] = 'Q';
		$finalArray[$i]['uniqueId'] = '1';
		$questionId = $entity['questionId'];
		$answerUserId = $entity['answerOwnerUserId'];
		$answerId = $entity['answerId'];
        $finalArray[$i]['hasUserAnswered'] = in_array($entity['questionId'], $answeredQuestions) ? true : false;

		if(isset($questionDetailArray[$questionId])){
			$finalArray[$i] = array_merge($finalArray[$i],$questionDetailArray[$questionId]);
            $finalArray[$i]['isThreadOwner'] = ($questionDetailArray[$questionId]['threadOwnerUserId'] == $loggedInUserId) ? true : false;
		}
		if(isset($userDetailArray[$answerUserId])){
			$finalArray[$i] = array_merge($finalArray[$i],$userDetailArray[$answerUserId]);
		}
		if(isset($contentTags[$questionId])){
			$finalArray[$i]['tags'] = $contentTags[$questionId];
		}
                if(isset($contentFollowers[$questionId])){
                        $finalArray[$i]['followerCount'] = $contentFollowers[$questionId];
                }
		else{
			$finalArray[$i]['followerCount'] = '0';
		}
		if(isset($ratingDetailArray[$answerId])){
			$finalArray[$i]['likeCount'] = $ratingDetailArray[$answerId]['likes'];
		}
		else{
			$finalArray[$i]['likeCount'] = '0';
		}

		if(isset($ratingDownvotesDetailArray[$answerId])){
			$finalArray[$i]['dislikeCount'] = $ratingDownvotesDetailArray[$answerId]['dislikes'];
		}
		else{
			$finalArray[$i]['dislikeCount'] = '0';
		}

                if(isset($suggestedInstitutes[$answerId]) && count($suggestedInstitutes[$answerId])>0){
                        $html = $apiCommonLib->getSuggestedInstituteHTML($suggestedInstitutes[$answerId]);
                        $finalArray[$i]['answerText'] = $finalArray[$i]['answerText']." ".$html;
                }
		
		$i++;
	    }
	    return $finalArray;
        }
        else if($contentType == "all"){

	    $topTags = array('20','17','422','4','385218','413','387237','18','421','9','3','430824','14','15','16','10','21','387254','417','365','407','387255','24','430369','409','25','391');
	    $dateContent = "";
	    if(in_array($tagId, $topTags)){
		$dateLimit = (($start/10)*7)+7;
		$dateContent = date("Y-m-d",strtotime("-$dateLimit days"));
		$dateContent = "AND t.creationTime > '$dateContent'";
	    }

            //Find the Latest Discussions/Comments
            $sql = "SELECT MAX(m.msgId) as msgId, m.fromOthers, MAX(m.parentId) as parentId, m.threadId, MAX(m.mainAnswerId) as mainAnswerId 
                FROM messageTable m, tags_content_mapping t 
                WHERE m.threadId = t.content_id AND t.status = 'live' AND m.status IN ('live','closed')
                AND t.content_type IN ('discussion','question') AND t.tag_id = ? AND
                ((m.parentId = 0 AND m.fromOthers='user') OR (m.parentId = m.threadId AND m.fromOthers IN ('user','discussion')) OR (m.parentId = m.mainAnswerId AND m.fromOthers='discussion'))
                AND (select status from messageTable where msgId=m.threadId) IN ('live','closed') $dateContent 
                GROUP BY m.threadId
                $sortByClause LIMIT $start,$count";
            $rows     = $dbHandle->query($sql, array($tagId))->result_array();

            //First we need to identify each entity (Question/Asnwer/Discussion/Discussion comment)
            //Basis its type, we have to find details about the entity
            $finalArray = array();
            foreach ($rows as $row){
                if($row['fromOthers'] == 'user'){
                    if($row['parentId'] == 0){
                        $questionMsgIds .= ($questionMsgIds == '')?$row['msgId']:','.$row['msgId'];
                        $finalArray[$i]['type'] = 'question';
                    }
                    else{
                        $answerMsgIds .= ($answerMsgIds == '')?$row['msgId']:','.$row['msgId'];
                        $finalArray[$i]['type'] = 'answer';
                    }
		    $finalArray[$i]['msgId'] = $row['msgId'];
                }
                else if($row['fromOthers'] == 'discussion'){
                    $finalArray[$i]['type'] = 'discussion';
                    $msgIds .= ($msgIds == '')?$row['msgId']:','.$row['msgId'];
		    $finalArray[$i]['msgId'] = $row['threadId'];
                }
                $i++;
            }
            
            if($questionMsgIds == "" && $answerMsgIds == "" && $msgIds == ""){
                return array();
            }

            //First find all the details about Discussions and their comments
            if($msgIds != ""){
                $disDetailArray = array();
                $sql = "SELECT msgId, threadId, mainAnswerId, parentId, creationDate, m.userId, msgTxt, (SELECT digFlag FROM digUpUserMap WHERE productId=msgId AND userId=? AND digUpStatus = 'live' LIMIT 1) hasUserVoted, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'discussion' AND status = 'follow' AND entityId = threadId) isUserFollowing ,tuai.aboutMe, (SELECT count(*) FROM messageTable mt WHERE mt.fromOthers='discussion' AND mt.status IN ('live','closed') AND mt.parentId = m.msgId ) commentCount
                    FROM messageTable m LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = m.userId)
                    WHERE m.msgId IN ($msgIds) AND m.fromOthers='discussion' ORDER BY creationDate DESC";
                $rows     = $dbHandle->query($sql, array($loggedInUserId,$loggedInUserId))->result_array();
    
                //For each of these entities, now fetch the Details
                foreach ($rows as $row){
                    $discussionIds .= ($discussionIds == '')?$row['threadId']:','.$row['threadId'];
                    $userIds .= ($userIds  == '')?$row['userId']:','.$row['userId'];
                    $discussionId = $row['threadId'];
                    if ($row['parentId'] == $row['mainAnswerId']){  //This is a comment
                            $commentIds .= ($commentIds == '')?$row['msgId']:','.$row['msgId'];
                            $commentId = $row['msgId'];
                            $disDetailArray[$discussionId]['answerId'] = $commentId;
                            $disDetailArray[$discussionId]['answerText'] = strip_tags(html_entity_decode($row['msgTxt']));
                            $disDetailArray[$discussionId]['answerOwnerUserId'] = $row['userId'];
                            $disDetailArray[$discussionId]['hasUserVotedUp'] = false;
                            $disDetailArray[$discussionId]['hasUserVotedDown'] = false;
                            $disDetailArray[$discussionId]['aboutMe'] = html_entity_decode($row['aboutMe']);
                            $disDetailArray[$discussionId]['commentCount'] = $row['commentCount'];
                            if($row['hasUserVoted'] == '1'){
                                    $disDetailArray[$discussionId]['hasUserVotedUp'] = true;
                            }
                            else if($row['hasUserVoted'] == '0'){
                                    $disDetailArray[$discussionId]['hasUserVotedDown'] = true;
                            }
    
                            if($isMobileApp){
                                    $disDetailArray[$discussionId]['answerText'] = sanitizeAnAMessageText($disDetailArray[$discussionId]['answerText'],'answer');
                            }
                    }
                    else if($row['parentId'] == $row['threadId']){  //This is a discussion
                            $disDetailArray[$discussionId]['discussionUserId'] = $row['userId'];
                    }
                    $disDetailArray[$discussionId]['discussionId'] = $row['threadId'];
                    $disDetailArray[$discussionId]['activityTime'] = makeRelativeTime($row['creationDate']);
                    $disDetailArray[$discussionId]['isUserFollowing'] = false;
                    if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                            $disDetailArray[$discussionId]['isUserFollowing'] = true;
                    }
                    $disDetailArray[$discussionId]['isThreadOwner'] = ($row['userId'] == $loggedInUserId) ? true : false;
    
                }
    
                $this->load->model('messageBoard/AnAModel');
                $discussionArray = explode(",",$discussionIds);
                //Get Tags on Questions
                $contentTags = $this->AnAModel->getContentTags($discussionArray, "discussion");
                $contentTags = $this->formatContentTags($contentTags, $tagId);
                //Get Follower count on Questions
                $contentFollowers = $this->entityFollowerCount($discussionArray, "discussion");
    
                //Get Questions details
                if($discussionIds != ''){
                    $discussionDetailArray = $this->getDiscussionDetails($discussionIds);
                }
    
                //Get User Details
                if($userIds != ''){
                    $userDetailArray = $this->getUserDetails($userIds);
                }
    
                //Get Upvotes details
                if($commentIds != ''){
                    $ratingDetailArray = $this->getUpvotes($commentIds);
                }
    
                //Get Upvotes details
                if($commentIds != ''){
                    $ratingDownvotesDetailArray = $this->geDownvotes($commentIds);
                }
            }


            //Now, fetch the details about the Answers
            if($answerMsgIds != ""){
                $answerDetailArray = array();
                $sql = "SELECT m.msgId, m.msgTxt, m.threadId as questionId, m.creationDate, m.userId, (SELECT digFlag FROM digUpUserMap WHERE productId=m.msgId AND userId=?  AND digUpStatus = 'live' LIMIT 1) hasUserVoted, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'question' AND status = 'follow' AND entityId = m.threadId) isUserFollowing, tuai.aboutMe, (SELECT count(*) FROM messageTable mt WHERE mt.mainAnswerId = m.msgId AND mt.status IN ('live','closed') AND mt.fromOthers = 'user' ) commentCount
                    FROM messageTable m LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = m.userId)
                    WHERE m.msgId IN ($answerMsgIds) AND m.fromOthers='user'
                    $sortByClause";
                $rows = $dbHandle->query($sql, array($loggedInUserId,$loggedInUserId))->result_array();
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
                    $answerDetailArray[$answerId]['isUserFollowing'] = false;
                    if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                            $answerDetailArray[$answerId]['isUserFollowing'] = true;
                    }
    
                    if($isMobileApp){
                            $answerDetailArray[$answerId]['answerText'] = sanitizeAnAMessageText($answerDetailArray[$answerId]['answerText'],'answer');
                    }
    
                }
    
                $this->load->model('messageBoard/AnAModel');
                $questionArray = explode(",",$questionIds);
                //Get Tags on Questions
                $contentTagsQ = $this->AnAModel->getContentTags($questionArray, "question");
                $contentTagsQ = $this->formatContentTags($contentTagsQ, $tagId);
                //Get Follower count on Questions
                $contentFollowersQ = $this->entityFollowerCount($questionArray, "question");
    
                //Get Questions details
                if($questionIds != ''){
                    $questionDetailArrayForAnswer = $this->getQuestionDetails($questionIds);
                }
    
                // has user answered
                $answeredQuestions = array();
                if($questionIds != '' && $loggedInUserId > 0){
                    $answeredQuestions = $this->getQuestionsAnsweredByUser($questionIds, $loggedInUserId);
                }
                
                //Get User Details
                if($userIds != ''){
                    $userDetailArrayQ = $this->getUserDetails($userIds);
                }
                //Get Upvotes details
                if($answerIds != ''){
                    $ratingDetailArrayQ = $this->getUpvotes($answerIds);
                }
    
                //Get Upvotes details
                if($answerIds != ''){
                    $ratingDownvotesDetailArrayQ = $this->geDownvotes($answerIds);
    
                    //Get list of Suggested Institues for the Answers
                    $this->load->library("common_api/APICommonLib");
                    $apiCommonLib = new APICommonLib();
                    //$suggestedInstitutes = $apiCommonLib->getSuggestedInstitutes($answerIds);
		    $suggestedInstitutes = array();
                }

            }
            
            
            //Now, fetch the details about the unanswered questions
            if($questionMsgIds != ""){
                $questionDetailArray = array();
                $sql = "SELECT m.msgId as questionId, m.msgTxt, m.creationDate, m.userId, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'question' AND status = 'follow' AND entityId = m.msgId) isUserFollowing , m.status, m.viewCount, tu.firstname, tu.lastname
                    FROM messageTable m, tuser tu WHERE m.userId = tu.userid AND m.msgId IN ($questionMsgIds)";
                $rows = $dbHandle->query($sql, array($loggedInUserId))->result_array();
    
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
    
                    if($isMobileApp){
                            $questionDetailArray[$questionId]['title'] = sanitizeAnAMessageText($questionDetailArray[$questionId]['title'],'question');
                    }
                }
    
                $this->load->model('messageBoard/AnAModel');
                $questionArray = explode(",",$questionIds);
                //Get Tags on Questions
                $contentTagsQues = $this->AnAModel->getContentTags($questionArray, "question");
                $contentTagsQues = $this->formatContentTags($contentTagsQues, $tagId);
    
                //Get Follower count on Questions
                $contentFollowersQues = $this->entityFollowerCount($questionArray, "question");
            }

            //Now, merge all the arrays
            $i = 0;
            $returningArray = array();
            foreach ($finalArray as $finalEntity){
            
                if($finalEntity['type'] == 'discussion'){
                    foreach ($disDetailArray as $key=>$entity){
                        if($key == $finalEntity['msgId']){
                                $returningArray[$i] = $entity;
                                $returningArray[$i]['type'] = 'D';
                                $returningArray[$i]['uniqueId'] = '2';
                                $discussionId = $entity['discussionId'];
                
                                $userId = isset($entity['answerOwnerUserId'])?$entity['answerOwnerUserId']:$entity['discussionUserId'];
                                $commentId = isset($entity['answerId'])?$entity['answerId']:'';
                
                                if(isset($discussionDetailArray[$discussionId])){
                                        $returningArray[$i] = array_merge($returningArray[$i],$discussionDetailArray[$discussionId]);
                                }
                                if(isset($userDetailArray[$userId])){
                                        $returningArray[$i] = array_merge($returningArray[$i],$userDetailArray[$userId]);
                                }
                                if(isset($contentTags[$discussionId])){
                                        $returningArray[$i]['tags'] = $contentTags[$discussionId];
                                }
                                if(isset($contentFollowers[$discussionId])){
                                        $returningArray[$i]['followerCount'] = $contentFollowers[$discussionId];
                                }
                                else{
                                        $returningArray[$i]['followerCount'] = '0';
                                }
                
                                if(isset($ratingDetailArray[$commentId])){
                                        $returningArray[$i]['likeCount'] = $ratingDetailArray[$commentId]['likes'];
                                }
                                else{
                                        $returningArray[$i]['likeCount'] = '0';
                                }
                
                                if(isset($ratingDownvotesDetailArray[$commentId])){
                                        $returningArray[$i]['dislikeCount'] = $ratingDownvotesDetailArray[$commentId]['dislikes'];
                                }
                                else{
                                        $returningArray[$i]['dislikeCount'] = '0';
                                }
                                unset ($returningArray[$i]['discussionId']);
                
                                $i++;
                        }
                    }
                }
                else if($finalEntity['type'] == 'answer'){

                    foreach ($answerDetailArray as $key=>$entity){
                        if($key == $finalEntity['msgId']){
                                $returningArray[$i] = $entity;
                                $returningArray[$i]['type'] = 'Q';
                                $returningArray[$i]['uniqueId'] = '1';
                                $questionId = $entity['questionId'];
                                $answerUserId = $entity['answerOwnerUserId'];
                                $answerId = $entity['answerId'];
                                $returningArray[$i]['hasUserAnswered'] = in_array($entity['questionId'], $answeredQuestions) ? true : false;
                
                                if(isset($questionDetailArrayForAnswer[$questionId])){
                                        $returningArray[$i] = array_merge($returningArray[$i],$questionDetailArrayForAnswer[$questionId]);
                                        $returningArray[$i]['isThreadOwner'] = ($questionDetailArrayForAnswer[$questionId]['threadOwnerUserId'] == $loggedInUserId) ? true : false;
                                }
                                if(isset($userDetailArrayQ[$answerUserId])){
                                        $returningArray[$i] = array_merge($returningArray[$i],$userDetailArrayQ[$answerUserId]);
                                }
                                if(isset($contentTagsQ[$questionId])){
                                        $returningArray[$i]['tags'] = $contentTagsQ[$questionId];
                                }
                                if(isset($contentFollowersQ[$questionId])){
                                        $returningArray[$i]['followerCount'] = $contentFollowersQ[$questionId];
                                }
                                else{
                                        $returningArray[$i]['followerCount'] = '0';
                                }
                                if(isset($ratingDetailArrayQ[$answerId])){
                                        $returningArray[$i]['likeCount'] = $ratingDetailArrayQ[$answerId]['likes'];
                                }
                                else{
                                        $returningArray[$i]['likeCount'] = '0';
                                }
                
                                if(isset($ratingDownvotesDetailArrayQ[$answerId])){
                                        $returningArray[$i]['dislikeCount'] = $ratingDownvotesDetailArrayQ[$answerId]['dislikes'];
                                }
                                else{
                                        $returningArray[$i]['dislikeCount'] = '0';
                                }
                
                                if(isset($suggestedInstitutes[$answerId]) && count($suggestedInstitutes[$answerId])>0){
                                        $html = $apiCommonLib->getSuggestedInstituteHTML($suggestedInstitutes[$answerId]);
                                        $returningArray[$i]['answerText'] = $returningArray[$i]['answerText']." ".$html;
                                }
                
                                $i++;
                            }
                        }
                }
                else if($finalEntity['type'] == 'question'){

                    foreach ($questionDetailArray as $key=>$entity){
                        if($key == $finalEntity['msgId']){
                                $returningArray[$i] = $entity;
                                $returningArray[$i]['type'] = 'Q';
                                $returningArray[$i]['uniqueId'] = '1';
                                $questionId = $entity['id'];
                
                                if(isset($contentTagsQues[$questionId])){
                                        $returningArray[$i]['tags'] = $contentTagsQues[$questionId];
                                }
                                if(isset($contentFollowersQues[$questionId])){
                                        $returningArray[$i]['followerCount'] = $contentFollowersQues[$questionId];
                                }
                                else{
                                        $returningArray[$i]['followerCount'] = '0';
                                }
                                $i++;
                            }
                        }
                }
            }
            
            return $returningArray;


        }

        return array();
    }

    function getQuestionDetails($questionIds){
        $dbHandle = $this->getReadHandle();
        $this->load->helper('image');

        $questionDetailArray = array();
        if($questionIds != ''){
                $sql = "SELECT m.msgId, m.msgTxt, m.creationDate,msgCount,viewCount,m.userId,m.status, tu.firstname,tu.lastname FROM messageTable m JOIN tuser tu ON (m.userId=tu.userid) WHERE m.msgId IN ($questionIds) AND m.status IN ('live','closed') AND m.parentId = 0 AND m.fromOthers='user' ORDER BY FIELD (m.msgId, $questionIds)";
                $question_rows = $dbHandle->query($sql)->result_array();
		
		global $isMobileApp;
                foreach ($question_rows as $qDetails){
                        $qId                                               = $qDetails['msgId'];
                        $questionDetailArray[$qId]['id']                   = $qDetails['msgId'];
                        $questionDetailArray[$qId]['title']                = strip_tags(html_entity_decode($qDetails['msgTxt']));
                        $questionDetailArray[$qId]['answerCount']          = $qDetails['msgCount'];
                        $questionDetailArray[$qId]['viewCount']            = $qDetails['viewCount'];
                        $questionDetailArray[$qId]['questionCreationDate'] = makeRelativeTime($qDetails['creationDate']);
                        $qDate = date('Y-m-d',strtotime($qDetails['creationDate']));
                        $questionDetailArray[$qId]['URL']                  = getSeoUrl($qDetails['msgId'], 'question', $qDetails['msgTxt'],array(),'NA',$qDate);
                        $questionDetailArray[$qId]['threadOwnerUserId']    = $qDetails['userId'];
                        $questionDetailArray[$qId]['threadStatus']         = $qDetails['status'];
                        $questionDetailArray[$qId]['questionOwnerName']         = $qDetails['firstname'];
			
			if($isMobileApp){
			    
			    $questionDetailArray[$qId]['title'] = sanitizeAnAMessageText($questionDetailArray[$qId]['title'],'question');
			}
                }
         }
	 return $questionDetailArray;
    }

    function getDiscussionDetails($discussionIds){
        $dbHandle = $this->getReadHandle();
        $this->load->helper('image');
	global $isMobileApp;
	
        $discussionDetailArray = array();
        if($discussionIds != ''){
                $sql = "SELECT m.msgId, (SELECT msgTxt FROM messageTable WHERE parentId = threadId AND threadId = m.msgId LIMIT 1) msgTxt, m.creationDate,(SELECT count(*) FROM messageTable WHERE mainAnswerId>0 AND threadId = m.msgId AND status='live' AND mainAnswerId=parentId) msgCount,viewCount FROM messageTable m WHERE m.msgId IN ($discussionIds) AND m.status IN ('live','closed') AND m.parentId = 0 AND m.fromOthers='discussion' ORDER BY FIELD (m.msgId, $discussionIds) ";
                $discussion_rows = $dbHandle->query($sql)->result_array();
                foreach ($discussion_rows as $qDetails){
                        $qId = $qDetails['msgId'];
                        $discussionDetailArray[$qId]['id'] = $qDetails['msgId'];
                        $discussionDetailArray[$qId]['title'] = strip_tags(html_entity_decode($qDetails['msgTxt']));
                        $discussionDetailArray[$qId]['answerCount'] = $qDetails['msgCount'];
                        $discussionDetailArray[$qId]['viewCount'] = $qDetails['viewCount'];
                        $qDate = date('Y-m-d',strtotime($qDetails['creationDate']));
                        $discussionDetailArray[$qId]['URL'] = getSeoUrl($qDetails['msgId'], 'discussion', $qDetails['msgTxt'],array(),'NA',$qDate);
			
			if($isMobileApp){
			    $discussionDetailArray[$qId]['title'] = sanitizeAnAMessageText($discussionDetailArray[$qId]['title'],'discussion');
			}
                }
         }
	 return $discussionDetailArray;
    }
    
    function getUserDetails($userIds){
	$dbHandle = $this->getReadHandle();
	$this->load->helper('image');

        $userDetailArray = array();
        $sql = "SELECT t.userid, firstname, lastname, displayname,avtarimageurl, (select upsm.levelName from userpointsystembymodule upsm where upsm.userId = t.userid and upsm.modulename = 'AnA') as levelName FROM tuser t WHERE t.userid IN (?) ";
	$userArr = explode(',',$userIds);
        $user_rows = $dbHandle->query($sql, array($userArr))->result_array();
        foreach ($user_rows as $userDetails){
                 $userId = $userDetails['userid'];
                 $userDetailArray[$userId]['answerOwnerName'] = $userDetails['firstname']." ".$userDetails['lastname'];
                 $userDetailArray[$userId]['answerOwnerLevel'] = (isset($userDetails['levelName']) && $userDetails['levelName']!='')?$userDetails['levelName']:'Beginner-Level 1';
		 $userDetails['avtarimageurl'] = checkUserProfileImage($userDetails['avtarimageurl']);	 
		 
                 $userDetailArray[$userId]['answerOwnerImage'] = $userDetails['avtarimageurl'];
        }
	return $userDetailArray;
    }

    function getUpvotes($answerIds){
        $dbHandle = $this->getReadHandle();
        $ratingDetailArray = array();
        $sql = "SELECT productId, count(*) as Upvotes FROM digUpUserMap WHERE productId IN (?) AND product = 'qna' AND digFlag = 1 AND digUpStatus = 'live' GROUP BY productId";
	$answerArr = explode(',',$answerIds);
        $rating_rows = $dbHandle->query($sql, array($answerArr))->result_array();
        foreach ($rating_rows as $rDetails){
                 $answerId = $rDetails['productId'];
                 $ratingDetailArray[$answerId]['likes'] = $rDetails['Upvotes'];
        }
	return $ratingDetailArray;
    }

    function geDownvotes($answerIds){
        $dbHandle = $this->getReadHandle();
        $ratingDetailArray = array();
        $sql = "SELECT productId, count(*) as Downvotes FROM digUpUserMap WHERE productId IN (?) AND product = 'qna' AND digFlag = 0 AND digUpStatus = 'live' GROUP BY productId";
	$answerArr = explode(',',$answerIds);
        $rating_rows = $dbHandle->query($sql, array($answerArr))->result_array();
        foreach ($rating_rows as $rDetails){
                 $answerId = $rDetails['productId'];
                 $ratingDetailArray[$answerId]['dislikes'] = $rDetails['Downvotes'];
        }
	return $ratingDetailArray;
    }

    function getTagsParent($tags){

    	$finalResult = array();
        $dbHandle = $this->getReadHandle();
    	if(empty($tags)){
    		return $finalResult;
    	}
    	$tags_query = implode(",", $tags);
    	$sql = "SELECT tg.id as tag_id, tg.tags, tg.tag_entity FROM tags_parent tp, tags tg WHERE tp.tag_id IN (?) AND tp.parent_id = tg.id AND tg.status = 'live' AND tp.status = 'live'";
    	$query = $dbHandle->query($sql, array($tags));
    	$result = $query->result_array();
    	$i = 0;
    	$tagArray = array();
    	foreach ($result as $value) {
    			$tagArray[$i] = $value;
    		$i++;
    	}
    	return $tagArray;	
    }
    
    function topContributorsDetails($users, $loggedInUserId){
        $dbHandle = $this->getReadHandle();
        $result = array();

        $userIds = implode(", ", $users);
	
    	if($userIds != ""){
    	    //Find Answer count
    	    // $sql = "SELECT count(*) answerCount, userId FROM messageTable use index (userId) WHERE userId IN ($userIds) AND fromOthers='user' AND parentId = threadId AND status IN ('live','closed') GROUP BY userId";
            $sql = "SELECT 
                        m.userId as userId, count(*) as answerCount
                    FROM
                        messageTable m use index (userId),
                        messageTable m1
                    WHERE
                        m.userId IN ($userIds) AND m.status IN ('live' , 'closed') AND m.fromOthers = 'user' AND m.parentId = m.threadId AND m1.msgId = m.threadId AND m1.status IN ('live' , 'closed') group by m.userId";
    	    $rows = $dbHandle->query($sql)->result_array();
    	    foreach ($rows as $row){
    		$userId = $row['userId'];
    		if($userId > 0){
    		    $result[$userId]['answerCount'] = $row['answerCount'];
    		}
    	    }

    	    //Find Discussion count
    	    // $sql = "SELECT count(*) commentCount, userId FROM messageTable use index (userId) WHERE userId IN ($userIds) AND fromOthers='discussion' AND parentId = mainAnswerId AND status IN ('live','closed') GROUP BY userId";
            $sql = "SELECT m.userId as userId, count(*) as commentCount FROM messageTable m use index (userId), messageTable m1 WHERE m.userId IN ($userIds) AND m.status IN ('live','closed') AND m.fromOthers = 'discussion' AND m.parentId = m.mainAnswerId AND m1.msgId = m.threadId AND m1.status IN ('live','closed') group by m.userId";
    	    $rows = $dbHandle->query($sql)->result_array();
    	    foreach ($rows as $row){
    		$userId = $row['userId'];
    		if($userId > 0){
    		    $result[$userId]['commentCount'] = $row['commentCount'];
    		}
    	    }

    	    //Find follower count
    	    $sql = "SELECT count(*) followerCount, entityId FROM tuserFollowTable USE INDEX (idx_composite) WHERE entityId IN ($userIds) AND entityType = 'user' AND status = 'follow' GROUP BY entityId";
    	    $rows = $dbHandle->query($sql)->result_array();
    	    foreach ($rows as $row){
    		$userId = $row['entityId'];
    		if($userId > 0){
    		    $result[$userId]['followerCount'] = $row['followerCount'];
    		}
    	    }

            global $isWebAPICall;
            global $webAPISource;
            if($isWebAPICall == 1 && $webAPISource=='desktop'){
                //Answer's Upvote Count
                $rows = $dbHandle->query("SELECT m.userId, count(*) as Count FROM messageTable m use index (userId), digUpUserMap d, messageTable m1 WHERE m.userId IN ($userIds) AND m.status IN ('live','closed') AND m.parentId = m.threadId AND m.fromOthers = 'user' AND m.msgId = d.productId AND d.digFlag = 1 AND d.digUpStatus = 'live' AND m1.msgId = m.threadId AND m1.status IN ('live','closed') GROUP BY m.userId")->result_array();
                foreach ($rows as $row){
                    $userId = $row['userId'];
                    if($userId > 0){
                        $result[$userId]['answerUpvotes'] = $row['Count'];
                    }
                }

                //Comment's Upvote Count
                $rows = $dbHandle->query("SELECT m.userId, count(*) as Count FROM messageTable m use index (userId), digUpUserMap d, messageTable m1 WHERE m.userId IN ($userIds) AND m.status IN ('live','closed') AND m.parentId = m.mainAnswerId AND m.fromOthers = 'discussion' AND m.msgId = d.productId AND d.digFlag = 1 AND d.digUpStatus = 'live' AND m1.msgId = m.threadId AND m1.status IN ('live','closed') GROUP BY m.userId")->result_array();
                foreach ($rows as $row){
                    $userId = $row['userId'];
                    if($userId > 0){
                        $result[$userId]['commentUpvotes'] = $row['Count'];
                    }
                }
            }

    	    //Find details of each user
    	    $this->load->helper('image');
    	    $sql = "SELECT t.userId, firstname, lastname, avtarimageurl, tuai.aboutMe, levelName FROM tuser t LEFT JOIN userpointsystembymodule u ON (t.userid = u.userId AND u.moduleName='AnA') LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = t.userid) WHERE t.userid IN ($userIds)";
    	    $rows = $dbHandle->query($sql)->result_array();
    	    foreach ($rows as $row){
    		$userId = $row['userId'];
    		$result[$userId]['userId'] = $row['userId'];
    		$result[$userId]['name'] = $row['firstname']." ".$row['lastname'];
    		$result[$userId]['avtarimageurl'] = checkUserProfileImage($row['avtarimageurl']);
    		$result[$userId]['levelName'] = $row['levelName'];
		$result[$userId]['aboutMe'] = html_entity_decode($row['aboutMe']);
    	    }

    	    $isFollowingArray = $this->isUserFollowingTag($loggedInUserId, $users, $entityType = 'user');
    	    
    	    //Now, sort the userIds as per the original order
    	    $finalArray = array();
    	    $i = 0;
    	    foreach($users as $userId){
    		if($userId > 0){
    		    $finalArray[$i] = $result[$userId];
    		    $finalArray[$i]['answerCount'] = isset($result[$userId]['answerCount'])?$result[$userId]['answerCount']:'0';
    		    $finalArray[$i]['commentCount'] = isset($result[$userId]['commentCount'])?$result[$userId]['commentCount']:'0';
    		    $finalArray[$i]['followerCount'] = isset($result[$userId]['followerCount'])?$result[$userId]['followerCount']:'0';
                if($isWebAPICall == 1 && $webAPISource=='desktop'){
                    $finalArray[$i]['upvoteCount'] = isset($result[$userId]['answerUpvotes']) ? $result[$userId]['answerUpvotes']+$result[$userId]['commentUpvotes']:'0';
                }
    		    $finalArray[$i]['isUserFollowing'] = isset($isFollowingArray[$userId])?$isFollowingArray[$userId]:'false';
    		    $i++;
    		}
    	    }
    	    return $finalArray;
    	}
	
	return $result;
	
    }

	function getTagsDetailsById($tagIds){
		$tagIds = array_filter($tagIds);
    	if(empty($tagIds))
    		return array();

		$finalArray = array();
		$dbHandle = $this->getReadHandle();

		$sql = "SELECT id, tags, tag_entity FROM tags WHERE id IN (".implode(',', $tagIds).") AND status = 'live'";

		$rows = $dbHandle->query($sql, array($tagId))->result_array();

		foreach($rows as $row){
			$finalArray[$row['id']] = $row;
		}

		return $finalArray;
	}

	function getTagsOfThreads($threadIds){

    	if(empty($threadIds))
    		return array();

		$finalArray = array();
		$dbHandle = $this->getReadHandle();

		$sql = "SELECT content_id, tag_id from tags_content_mapping where content_type IN ('discussion','question') and content_id IN(".implode(',', $threadIds).") and status = 'live' order by FIELD(tag_type, 'objective', 'manual', 'background', 'objective_parent', 'manual_parent', 'background_parent') asc";

		$rows = $dbHandle->query($sql)->result_array();

		foreach($rows as $row){
			$finalArray[$row['content_id']][] = $row['tag_id'];
		}

		return $finalArray;
	}
	
	function formatContentTags($contentArray, $tagId){
		$finalArray = array();
		foreach ($contentArray as $key=>$value){
			if(is_array($value)){
				//Here, Sort the Tag array so that the one with the TagId (i.e. current page Tag) should appear first
				//Also, if any University/College tag is found, it should be displayed second.
				$value = $this->sortTagArray($value, $tagId);
				$tmp = array_slice($value,0,2);
			}
			$finalArray[$key] = $tmp;
		}
		return $finalArray;
	}

	function sortTagArray($value, $tagId){
		$i = 0;
		$finalArray = array();
		foreach ($value as $tag){
			if($tag['tagId'] == $tagId){
				$finalArray[$i] = $tag; 
			}
		}
                foreach ($value as $tag){
                        if( ($tag['tagType'] == "University" || $tag['tagType'] == "Colleges") && ($tag['tagId'] != $tagId) ){
				$i++;
                                $finalArray[$i] = $tag;
                        }
                }
                foreach ($value as $tag){
                        if($tag['tagId'] != $tagId && $tag['tagType'] != "University" && $tag['tagType'] != "Colleges"){
				$i++;
                                $finalArray[$i] = $tag;
                        }
                }
		return $finalArray;
	}

    function isUserFollowingEntity($userId, $entityIds, $entityType = array('tag')){
        $dbHandle = $this->getReadHandle();
        $finalArray = array();
        $entityString = implode(",",$entityIds);
        
        if(!empty($entityString)){
                $sql = "SELECT entityId as following FROM tuserFollowTable WHERE userId = ? AND status = 'follow' AND entityType IN ('".implode("','", $entityType)."') AND entityId IN ($entityString)";
                $rows = $dbHandle->query($sql, array($userId))->result_array();
                $finalArray = array();
                
                foreach ($rows as $value) {
                    $finalArray[] = $value['following'];
                }
                return $finalArray;
        }
        return array();
    }

    function getQuestionsAnsweredByUser($questionIds, $loggedInUserId){
        $dbHandle = $this->getReadHandle();

        $questionDetailArray = array();
        if($questionIds != ''){
                $sql = "SELECT distinct m.threadId FROM messageTable m WHERE m.threadId = m.parentId AND m.threadId IN ($questionIds) AND m.status IN ('live','closed') AND m.fromOthers='user' and m.userId = ?";
                $question_rows = $dbHandle->query($sql, array($loggedInUserId))->result_array();
                foreach ($question_rows as $qDetails){
                        $questionDetailArray[] = $qDetails['threadId'];
                }
         }
     return $questionDetailArray;
    }

    function getThreadTagsWithType($threadId, $threadType, $tagTypes = array("objective","manual")){

        $result = array();
        if(empty($threadId) || empty($threadType))
            return $result;

        $dbHandle = $this->getReadHandle();

        $sql = "SELECT t.id, t.tag_entity, t.tags FROM tags_content_mapping tcm INNER JOIN tags t ON(t.id = tcm.tag_id AND t.status='live') WHERE tcm.content_id = ? AND tcm.content_type = ? AND tcm.status='live' AND tag_type IN ('".implode("','", $tagTypes)."') ORDER by t.id asc";

        $rows = $dbHandle->query($sql, array($threadId, $threadType))->result_array();

        foreach($rows as $row){
            $result[$row['tag_entity']][] = $row;
        }

        return $result;
    }

    function getContentDetails($contentIds, $contentType, $start, $count, $loggedInUserId){

	$finalArray = array();
        $dbHandle = $this->getReadHandle();
	$this->load->helper('image');
	global $isMobileApp;
	
        $sortByClause = "ORDER BY msgId DESC";
        $tagId = 0;
        
	//Set the Query by Content Type
	if($contentType == "discussion"){
	    
	    //Find the Latest Discussions/Comments
	    $sql = "SELECT MAX(m.msgId) as msgId  
		FROM messageTable m  
		WHERE m.status IN ('live','closed')
		AND (m.parentId = m.threadId OR m.parentId = m.mainAnswerId) AND (select status from messageTable where msgId=m.threadId) IN ('live','closed') 
		AND m.fromOthers='discussion' AND m.threadId IN ($contentIds) GROUP BY m.threadId 
		$sortByClause LIMIT $start,$count";
	    $rows     = $dbHandle->query($sql)->result_array();

	    //For each of these entities, now fetch the Details
            foreach ($rows as $row){
		$msgIds .= ($msgIds == '')?$row['msgId']:','.$row['msgId'];
            }
            if($msgIds == ""){
                return array();
            }

	    $disDetailArray = array();
            $sql = "SELECT msgId, threadId, mainAnswerId, parentId, creationDate, m.userId, msgTxt, (SELECT digFlag FROM digUpUserMap WHERE productId=msgId AND userId=? AND digUpStatus = 'live' LIMIT 1) hasUserVoted, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'discussion' AND status = 'follow' AND entityId = threadId) isUserFollowing ,tuai.aboutMe, (SELECT count(*) FROM messageTable mt WHERE mt.fromOthers='discussion' AND mt.status IN ('live','closed') AND mt.parentId = m.msgId ) commentCount  
                FROM messageTable m LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = m.userId)
                WHERE m.msgId IN ($msgIds) AND m.fromOthers='discussion' ORDER BY creationDate DESC";
            $rows     = $dbHandle->query($sql, array($loggedInUserId,$loggedInUserId))->result_array();

            //For each of these entities, now fetch the Details
            foreach ($rows as $row){
                $discussionIds .= ($discussionIds == '')?$row['threadId']:','.$row['threadId'];
                $userIds .= ($userIds  == '')?$row['userId']:','.$row['userId'];
                $discussionId = $row['threadId'];
                if ($row['parentId'] == $row['mainAnswerId']){  //This is a comment
                        $commentIds .= ($commentIds == '')?$row['msgId']:','.$row['msgId'];
                        $commentId = $row['msgId'];
                        $disDetailArray[$discussionId]['answerId'] = $commentId;
                        $disDetailArray[$discussionId]['answerText'] = strip_tags(html_entity_decode($row['msgTxt']));
                        $disDetailArray[$discussionId]['answerOwnerUserId'] = $row['userId'];
                        $disDetailArray[$discussionId]['hasUserVotedUp'] = false;
                        $disDetailArray[$discussionId]['hasUserVotedDown'] = false;
			$disDetailArray[$discussionId]['aboutMe'] = html_entity_decode($row['aboutMe']);
			$disDetailArray[$discussionId]['commentCount'] = $row['commentCount'];
                        if($row['hasUserVoted'] == '1'){
                                $disDetailArray[$discussionId]['hasUserVotedUp'] = true;
                        }
                        else if($row['hasUserVoted'] == '0'){
                                $disDetailArray[$discussionId]['hasUserVotedDown'] = true;
                        }			
			$disDetailArray[$discussionId]['answerText'] = sanitizeAnAMessageText($disDetailArray[$discussionId]['answerText'],'answer');
                }
                else if($row['parentId'] == $row['threadId']){  //This is a discussion
                        $disDetailArray[$discussionId]['discussionUserId'] = $row['userId'];
                }
                $disDetailArray[$discussionId]['discussionId'] = $row['threadId'];
                $disDetailArray[$discussionId]['activityTime'] = makeRelativeTime($row['creationDate']);
                $disDetailArray[$discussionId]['isUserFollowing'] = false;
                if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                        $disDetailArray[$discussionId]['isUserFollowing'] = true;
                }
                $disDetailArray[$discussionId]['isThreadOwner'] = ($row['userId'] == $loggedInUserId) ? true : false;

            }

            $this->load->model('messageBoard/AnAModel');
            $discussionArray = explode(",",$discussionIds);
            //Get Tags on Questions
            $contentTags = $this->AnAModel->getContentTags($discussionArray, "discussion");
   	    $contentTags = $this->formatContentTags($contentTags, $tagId);
            
            //Get Follower count on Questions
            $contentFollowers = $this->entityFollowerCount($discussionArray, "discussion");

            //Get Questions details
            if($discussionIds != ''){
                $discussionDetailArray = $this->getDiscussionDetails($discussionIds);
            }

            //Get User Details
            if($userIds != ''){
                $userDetailArray = $this->getUserDetails($userIds);
            }

            //Get Upvotes details
            if($commentIds != ''){
                $ratingDetailArray = $this->getUpvotes($commentIds);
            }

	    //Get Upvotes details
            if($commentIds != ''){
                $ratingDownvotesDetailArray = $this->geDownvotes($commentIds);
            }

            //Now, merge all the arrays
            $i = 0;
            foreach ($disDetailArray as $entity){
                $finalArray[$i] = $entity;
                $finalArray[$i]['type'] = 'D';
		$finalArray[$i]['uniqueId'] = '2';
                $discussionId = $entity['discussionId'];
		
                $userId = isset($entity['answerOwnerUserId'])?$entity['answerOwnerUserId']:$entity['discussionUserId'];
                $commentId = isset($entity['answerId'])?$entity['answerId']:'';

                if(isset($discussionDetailArray[$discussionId])){
                        $finalArray[$i] = array_merge($finalArray[$i],$discussionDetailArray[$discussionId]);
                }
                if(isset($userDetailArray[$userId])){
                        $finalArray[$i] = array_merge($finalArray[$i],$userDetailArray[$userId]);
                }
                if(isset($contentTags[$discussionId])){
                        $finalArray[$i]['tags'] = $contentTags[$discussionId];
                }
                if(isset($contentFollowers[$discussionId])){
                        $finalArray[$i]['followerCount'] = $contentFollowers[$discussionId];
                }
                else{
                        $finalArray[$i]['followerCount'] = '0';
                }

                if(isset($ratingDetailArray[$commentId])){
                        $finalArray[$i]['likeCount'] = $ratingDetailArray[$commentId]['likes'];
                }
                else{
                        $finalArray[$i]['likeCount'] = '0';
                }

                if(isset($ratingDownvotesDetailArray[$commentId])){
                        $finalArray[$i]['dislikeCount'] = $ratingDownvotesDetailArray[$commentId]['dislikes'];
                }
                else{
                        $finalArray[$i]['dislikeCount'] = '0';
                }
		//unset ($finalArray[$i]['discussionId']);

                $i++;
            }
            $finalArray = $this->sortAsOriginal($contentIds,$finalArray,'discussion');
            return $finalArray;

	}
	else if($contentType == "unanswered"){
            $questionDetailArray = array();
            $sql = "SELECT m.msgId as questionId, m.msgTxt, m.creationDate, m.userId, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'question' AND status = 'follow' AND entityId = m.msgId) isUserFollowing , m.status, m.viewCount, tu.firstname, tu.lastname 
                FROM messageTable m, tuser tu   
                WHERE m.userId=tu.userid AND m.threadId IN ($contentIds) AND m.status IN ('live','closed') AND
                m.parentId = 0 AND m.fromOthers='user' AND m.msgCount = 0 
                $sortByClause LIMIT $start,$count";
            $rows = $dbHandle->query($sql, array($loggedInUserId))->result_array();

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

                $qDate = date('Y-m-d',strtotime($row['creationDate']));
                $questionDetailArray[$questionId]['URL'] = getSeoUrl($questionId, 'question', $row['msgTxt'],array(),'NA',$qDate);
                $questionDetailArray[$questionId]['isUserFollowing'] = false;
                if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                        $questionDetailArray[$questionId]['isUserFollowing'] = true;
                }
                $questionDetailArray[$questionId]['isThreadOwner'] = ($row['userId'] == $loggedInUserId) ? true : false;
                $questionDetailArray[$questionId]['threadStatus'] = $row['status'];
                $questionDetailArray[$questionId]['questionOwnerName'] = $row['firstname'];
		
		$questionDetailArray[$questionId]['title'] = sanitizeAnAMessageText($questionDetailArray[$questionId]['title'],'question');
            }

            $this->load->model('messageBoard/AnAModel');
            $questionArray = explode(",",$questionIds);
            //Get Tags on Questions
            $contentTags = $this->AnAModel->getContentTags($questionArray, "question");
	    $contentTags = $this->formatContentTags($contentTags, $tagId);

            //Get Follower count on Questions
            $contentFollowers = $this->entityFollowerCount($questionArray, "question");

            //Now, merge all the arrays
            $i = 0;
            foreach ($questionDetailArray as $entity){
                $finalArray[$i] = $entity;
                $finalArray[$i]['type'] = 'Q';
		$finalArray[$i]['uniqueId'] = '1';
                $questionId = $entity['id'];

                if(isset($contentTags[$questionId])){
                        $finalArray[$i]['tags'] = $contentTags[$questionId];
                }
                if(isset($contentFollowers[$questionId])){
                        $finalArray[$i]['followerCount'] = $contentFollowers[$questionId];
                }
		else{
			$finalArray[$i]['followerCount'] = '0';
		}
                $i++;
            }
            $finalArray = $this->sortAsOriginal($contentIds,$finalArray,'unanswered');
            return $finalArray;

	}
        else if($contentType == "question"){

            //Find the Latest Answers
            $sql = "SELECT MAX(m.msgId) as msgId
                FROM messageTable m 
                WHERE m.status IN ('live','closed')
                AND m.parentId = m.threadId AND (select status from messageTable where msgId=m.threadId) IN ('live','closed') 
                AND m.fromOthers='user' AND m.threadId IN ($contentIds) GROUP BY m.threadId
                $sortByClause LIMIT $start,$count";
            $rows     = $dbHandle->query($sql)->result_array();
            //For each of these entities, now fetch the Details
            foreach ($rows as $row){
                $msgIds .= ($msgIds == '')?$row['msgId']:','.$row['msgId'];
            }

	    if($msgIds == ""){
		return array();
	    }
	    $answerDetailArray = array();
	    $sql = "SELECT m.msgId, m.msgTxt, m.threadId as questionId, m.creationDate, m.userId, (SELECT digFlag FROM digUpUserMap WHERE productId=m.msgId AND userId=?  AND digUpStatus = 'live' LIMIT 1) hasUserVoted, (SELECT 1 FROM tuserFollowTable WHERE userId = ? AND entityType = 'question' AND status = 'follow' AND entityId = m.threadId) isUserFollowing, tuai.aboutMe, (SELECT count(*) FROM messageTable mt WHERE mt.mainAnswerId = m.msgId AND mt.status IN ('live','closed') AND mt.fromOthers = 'user' ) commentCount 
		FROM messageTable m LEFT JOIN tUserAdditionalInfo tuai ON (tuai.userId = m.userId)
		WHERE m.msgId IN ($msgIds) AND m.fromOthers='user' 
		$sortByClause";
	    $rows = $dbHandle->query($sql, array($loggedInUserId,$loggedInUserId))->result_array();
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
                $answerDetailArray[$answerId]['isUserFollowing'] = false;
                if(isset($row['isUserFollowing']) && $row['isUserFollowing']==1){
                        $answerDetailArray[$answerId]['isUserFollowing'] = true;
                }
		
		$answerDetailArray[$answerId]['answerText'] = sanitizeAnAMessageText($answerDetailArray[$answerId]['answerText'],'answer');

	    }

            $this->load->model('messageBoard/AnAModel');
	    $questionArray = explode(",",$questionIds);
	    //Get Tags on Questions
            $contentTags = $this->AnAModel->getContentTags($questionArray, "question");
	    $contentTags = $this->formatContentTags($contentTags, $tagId);
	    //Get Follower count on Questions
            $contentFollowers = $this->entityFollowerCount($questionArray, "question");

	    //Get Questions details
	    if($questionIds != ''){
		$questionDetailArray = $this->getQuestionDetails($questionIds);
	    }
        // has user answered
        $answeredQuestions = array();
        if($questionIds != '' && $loggedInUserId > 0){
            $answeredQuestions = $this->getQuestionsAnsweredByUser($questionIds, $loggedInUserId);
        }
	    //Get User Details
	    if($userIds != ''){
		$userDetailArray = $this->getUserDetails($userIds);
	    }
	    //Get Upvotes details
            if($answerIds != ''){
		$ratingDetailArray = $this->getUpvotes($answerIds);
            }

	    //Get Upvotes details
            if($answerIds != ''){
		$ratingDownvotesDetailArray = $this->geDownvotes($answerIds);

		//Get list of Suggested Institues for the Answers
                $this->load->library("common_api/APICommonLib");
                $apiCommonLib = new APICommonLib();
                $suggestedInstitutes = $apiCommonLib->getSuggestedInstitutes($answerIds);
            }
	    //Now, merge all the arrays
	    $i = 0;
	    foreach ($answerDetailArray as $entity){
		$finalArray[$i] = $entity;
		$finalArray[$i]['type'] = 'Q';
		$finalArray[$i]['uniqueId'] = '1';
		$questionId = $entity['questionId'];
		$answerUserId = $entity['answerOwnerUserId'];
		$answerId = $entity['answerId'];
        $finalArray[$i]['hasUserAnswered'] = in_array($entity['questionId'], $answeredQuestions) ? true : false;

		if(isset($questionDetailArray[$questionId])){
			$finalArray[$i] = array_merge($finalArray[$i],$questionDetailArray[$questionId]);
            $finalArray[$i]['isThreadOwner'] = ($questionDetailArray[$questionId]['threadOwnerUserId'] == $loggedInUserId) ? true : false;
		}
		if(isset($userDetailArray[$answerUserId])){
			$finalArray[$i] = array_merge($finalArray[$i],$userDetailArray[$answerUserId]);
		}
		if(isset($contentTags[$questionId])){
			$finalArray[$i]['tags'] = $contentTags[$questionId];
		}
                if(isset($contentFollowers[$questionId])){
                        $finalArray[$i]['followerCount'] = $contentFollowers[$questionId];
                }
		else{
			$finalArray[$i]['followerCount'] = '0';
		}
		if(isset($ratingDetailArray[$answerId])){
			$finalArray[$i]['likeCount'] = $ratingDetailArray[$answerId]['likes'];
		}
		else{
			$finalArray[$i]['likeCount'] = '0';
		}

		if(isset($ratingDownvotesDetailArray[$answerId])){
			$finalArray[$i]['dislikeCount'] = $ratingDownvotesDetailArray[$answerId]['dislikes'];
		}
		else{
			$finalArray[$i]['dislikeCount'] = '0';
		}

                if(isset($suggestedInstitutes[$answerId]) && count($suggestedInstitutes[$answerId])>0){
                        $html = $apiCommonLib->getSuggestedInstituteHTML($suggestedInstitutes[$answerId]);
                        $finalArray[$i]['answerText'] = $finalArray[$i]['answerText']." ".$html;
                }
		
		$i++;
	    }
            $finalArray = $this->sortAsOriginal($contentIds,$finalArray,'question');
	    return $finalArray;
        }

        return array();
    }

    function sortAsOriginal($contentIds,$finalArray,$type){
        $returnArray = array();
        foreach (explode(',',$contentIds) as $contentId){
                foreach ($finalArray as $entity){
                    if($type=='discussion'){
                        if($entity['discussionId'] == $contentId){
                            $returnArray[] = $entity;
                        }
                    }
                    else if($type=='unanswered'){
                        if($entity['id'] == $contentId){
                            $returnArray[] = $entity;
                        }
                    }
                    else if($type=='question'){
                        if($entity['questionId'] == $contentId){
                            $returnArray[] = $entity;
                        }
                    }                    
                }
        }
        return $returnArray;
    }
}
