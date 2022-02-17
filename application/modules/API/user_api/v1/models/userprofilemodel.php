<?php
/**
 * User Profile model class file
 */

class UserProfileModel extends MY_Model
{
    function __construct()
    {
        parent::__construct('User');
    }

    /**
     * Initiate the model
     *
     * @param string $operation
     */
    private function initiateModel($operation = 'read')
    {
                if($operation=='read'){
                        $this->dbHandle = $this->getReadHandle();
                }
                else{
                $this->dbHandle = $this->getWriteHandle();
                }
    }

    function findFollowingTags($userId, $start = 0, $count = 10, $loggedInUserId = 0){
        $this->initiateModel();
        $rs = $this->dbHandle->query("SELECT entityId FROM tuserFollowTable WHERE userId = ? AND status = 'follow' AND entityType = 'tag' ORDER BY creationTime DESC LIMIT $start, $count", array($userId));
        $tagArray = array();
        foreach ($rs->result_array() as $tagD){
                $tagArray[] = $tagD['entityId'];
        }

        $finalArray = array();
        if(count($tagArray) > 0){
                $tagString = implode(",",$tagArray);
                $i = 0;
                $rs = $this->dbHandle->query("SELECT count(*) followerCount, tags.id, tags.tags FROM tuserFollowTable use index (idx_composite), tags WHERE entityId IN ($tagString) AND tuserFollowTable.status = 'follow' AND entityType = 'tag' AND tags.id=entityId AND tags.status='live' GROUP BY entityId ORDER BY FIELD (tags.id,$tagString)");
                $tagDetailArray = $rs->result_array();

                $this->load->model('TagsModel');
                $isFollowingArray = $this->TagsModel->isUserFollowingTag($loggedInUserId, $tagArray, $entityType = 'tag');

                foreach ($tagDetailArray as $tagD){
                        $tagId = $tagD['id'];
                        $finalArray[$i]['tagId'] = $tagId;
                        $finalArray[$i]['tagName'] = $tagD['tags'];
                        //$finalArray[$i]['isUserFollowing'] = true;
			$finalArray[$i]['isUserFollowing'] = isset($isFollowingArray[$tagId])?$isFollowingArray[$tagId]:false;                
                        $finalArray[$i]['followerCount'] = $tagD['followerCount'];
                        $i++;
                }
        }
        return $finalArray;
    }

    function getUsersIAmFollowing($userId, $loggedInUserId, $start = 0, $count = 10){
        $this->initiateModel();
        $this->load->helper('image');
        $rs = $this->dbHandle->query("SELECT entityId FROM tuserFollowTable WHERE userId = ? AND status = 'follow' AND entityType = 'user' ORDER BY creationTime DESC LIMIT $start, $count", array($userId));
        $entityArray = array();
        foreach ($rs->result_array() as $entityD){
                $entityArray[] = $entityD['entityId'];
        }

        $finalArray = array();
        if(count($entityArray) > 0){
                $entityString = implode(",",$entityArray);
                $i = 0;
                $rs = $this->dbHandle->query("SELECT firstname, lastname, t.userid, t.avtarimageurl, a.aboutMe, u.levelName FROM tuser t LEFT JOIN tUserAdditionalInfo a ON (t.userid = a.userId) LEFT JOIN userpointsystembymodule u ON (t.userid = u.userId AND u.moduleName = 'AnA') WHERE t.userid IN ($entityString) GROUP BY userid ORDER BY FIELD (t.userid,$entityString)");
                $entityDetailArray = $rs->result_array();

                foreach ($entityDetailArray as $entityD){
                        $userIds .= ($userIds=="")?$entityD['userid']:",".$entityD['userid'];
                }
                $userIdArray = explode(',',$userIds);
                $this->load->model('TagsModel');
                $isFollowingArray = $this->TagsModel->isUserFollowingTag($loggedInUserId, $userIdArray, $entityType = 'user');

                foreach ($entityDetailArray as $entityD){
                        $userId = $entityD['userid'];
                        $finalArray[$i]['userId'] = $userId;
                        $finalArray[$i]['userName'] = $entityD['firstname']." ".$entityD['lastname'];
			$finalArray[$i]['isUserFollowing'] = isset($isFollowingArray[$userId])?$isFollowingArray[$userId]:false;
                        $finalArray[$i]['avtarimageurl'] = checkUserProfileImage($entityD['avtarimageurl']);
                        $finalArray[$i]['aboutMe'] = $entityD['aboutMe'];
                        $finalArray[$i]['levelName'] = $entityD['levelName'];
                        $i++;
                }
        }
        return $finalArray;
    }

    function getUsersFollowingMe($userId, $loggedInUserId, $start = 0, $count = 10){
        $this->initiateModel();
        $this->load->helper('image');
        $rs = $this->dbHandle->query("SELECT userId FROM tuserFollowTable WHERE entityId = ? AND status = 'follow' AND entityType = 'user' LIMIT $start, $count", array($userId));
        $entityArray = array();
        foreach ($rs->result_array() as $entityD){
                $entityArray[] = $entityD['userId'];
        }

        $finalArray = array();
        if(count($entityArray) > 0){
                $entityString = implode(",",$entityArray);
                $i = 0;
                $rs = $this->dbHandle->query("SELECT firstname, lastname, t.userid, t.avtarimageurl, a.aboutMe, u.levelName FROM tuser t LEFT JOIN tUserAdditionalInfo a ON (t.userid = a.userId) LEFT JOIN userpointsystembymodule u ON (t.userid = u.userId AND u.moduleName = 'AnA') WHERE t.userid IN ($entityString) GROUP BY userid ORDER BY FIELD (t.userid,$entityString)");
                $entityDetailArray = $rs->result_array();
                
                foreach ($entityDetailArray as $entityD){
                        $userIds .= ($userIds=="")?$entityD['userid']:",".$entityD['userid'];
                }
                $userIdArray = explode(',',$userIds);
                $this->load->model('TagsModel');
                $isFollowingArray = $this->TagsModel->isUserFollowingTag($loggedInUserId, $userIdArray, $entityType = 'user');
                
                foreach ($entityDetailArray as $entityD){
                        $userId = $entityD['userid'];
                        $finalArray[$i]['userId'] = $userId;
                        $finalArray[$i]['userName'] = $entityD['firstname']." ".$entityD['lastname'];
                        $finalArray[$i]['isUserFollowing'] = isset($isFollowingArray[$userId])?$isFollowingArray[$userId]:false;
                        $finalArray[$i]['avtarimageurl'] = checkUserProfileImage($entityD['avtarimageurl']);
                        $finalArray[$i]['aboutMe'] = $entityD['aboutMe'];
                        $finalArray[$i]['levelName'] = $entityD['levelName'];
                        $i++;
                }
        }
        return $finalArray;
    }

    function getUsersPrivacySettings($userId){
        $this->initiateModel();

        $this->load->config('v1/UserProfileBuilderConfig');

        $configData = $this->config->item('profileBuilderData');
        $privateFieldstoDBMapping = $configData['privateFieldstoDBMapping'];
        $reversePrivateFieldstoDBMapping = array();
        foreach ($privateFieldstoDBMapping as $key => $value) {
            foreach ($value as $field) {
                $reversePrivateFieldstoDBMapping[$field] = $key;
            }
            
        }

        $rs = $this->dbHandle->query("SELECT fieldId FROM tUserDataPrivacySettings WHERE userId = ? AND status = 'live'", array($userId));
        
        $privateFields = array();

        foreach ($rs->result_array() as $value){

            if($reversePrivateFieldstoDBMapping[$value['fieldId']])
                $privateFields[] = $reversePrivateFieldstoDBMapping[$value['fieldId']];
            else
                $privateFields[] = $value['fieldId'];
        }
        
        return $privateFields;
    }

    function getUserStats($userId){
        $this->initiateModel();
        
        //Initialize the final array
        $finalArray = array(
                        'answerLaterCount' => "0",
                        'commentLaterCount' => "0",
                        'questionCount' => "0",
                        'answerCount' => "0",
                        'discussionCount' => "0",
                        'commentCount' => "0",
                        'questionsFollowedCount' => "0",
                        'discussionFollowedCount' => "0",
                        'userFollowersCount' => "0",
                        'userFollowingCount' => "0",
                        'tagsFollowingCount' => "0",
                        'answerUpvoteCount' => "0",
                        'commentUpvoteCount' => "0",
                        'HQAnswerCount' => "0",
                        'HQCommentCount' => "0",
                    );
        
        //Answer Later & Comment Later
	$this->benchmark->mark('answer_later_start');
        $rs = $this->dbHandle->query("SELECT count(*) as laterCount, entityType FROM entityShortlist e, messageTable m WHERE e.userId = ? AND e.status = 'live' AND e.entityType IN ('question','discussion') AND e.entityId = m.msgId AND m.status IN ('live','closed') GROUP BY entityType", array($userId));
        foreach ($rs->result_array() as $row){
            if($row['entityType'] == 'question'){
                $finalArray['answerLaterCount'] = $row['laterCount'];
            }
            else if($row['entityType'] == 'discussion'){
                $finalArray['commentLaterCount'] = $row['laterCount'];
            }
        }
	$this->benchmark->mark('answer_later_end');
	$this->benchmark->mark('content_count_start');
        //Question and Discussions Count
        $rs = $this->dbHandle->query("SELECT count(*) as Count, fromOthers FROM messageTable WHERE userId = ? AND status IN ('live','closed') AND fromOthers IN ('user','discussion') AND parentId = 0 GROUP BY fromOthers", array($userId));
        foreach ($rs->result_array() as $row){
            if($row['fromOthers'] == 'user'){
                $finalArray['questionCount'] = $row['Count'];
            }
            else if($row['fromOthers'] == 'discussion'){
                $finalArray['discussionCount'] = $row['Count'];
            }
        }
	$this->benchmark->mark('content_count_end');
	$this->benchmark->mark('answer_count_start');
        //Answer Count
        $rs = $this->dbHandle->query("SELECT count(*) as Count FROM messageTable m , messageTable m1 WHERE m.userId = ? AND m.status = 'live' AND m.fromOthers = 'user' AND m.parentId = m.threadId AND m1.msgId = m.threadId AND m1.status IN ('live','closed')", array($userId));
        foreach ($rs->result_array() as $row){
            $finalArray['answerCount'] = $row['Count'];
        }
	$this->benchmark->mark('answer_count_end');
	$this->benchmark->mark('comment_count_start');
        //Comment Count
        $rs = $this->dbHandle->query("SELECT count(*) as Count FROM messageTable m, messageTable m1 WHERE m.userId = ? AND m.status = 'live' AND m.fromOthers = 'discussion' AND m.parentId = m.mainAnswerId AND m1.msgId = m.threadId AND m1.status IN ('live','closed')", array($userId));
        foreach ($rs->result_array() as $row){
            $finalArray['commentCount'] = $row['Count'];
        }
        $this->benchmark->mark('comment_count_end');
	$this->benchmark->mark('follower_count_start');
        //Following Counts for Content
        $rs = $this->dbHandle->query("SELECT count(*) as Count, entityType FROM tuserFollowTable t, messageTable m WHERE t.userId = ? AND t.status = 'follow' AND t.entityType IN ('question','discussion') AND t.entityId = m.msgId and m.status IN ('live','closed') GROUP BY entityType", array($userId));
        foreach ($rs->result_array() as $row){
            if($row['entityType'] == 'question'){
                $finalArray['questionsFollowedCount'] = $row['Count'];
            }
            else if($row['entityType'] == 'discussion'){
                $finalArray['discussionFollowedCount'] = $row['Count'];
            }
        }
	$this->benchmark->mark('follower_count_end');
	$this->benchmark->mark('following_count_start');
        //Following Counts for User
        $rs = $this->dbHandle->query("SELECT count(*) as Count, entityType FROM tuserFollowTable t WHERE t.userId = ? AND t.status = 'follow' AND t.entityType IN ('user') GROUP BY entityType", array($userId));
        foreach ($rs->result_array() as $row){
            $finalArray['userFollowingCount'] = $row['Count'];
        }
	$this->benchmark->mark('following_count_end');
	$this->benchmark->mark('tag_count_start');
        //Following Counts for Tags
        $rs = $this->dbHandle->query("SELECT count(*) as Count, t.entityType FROM tuserFollowTable t, tags tg WHERE t.userId = ? AND t.status = 'follow' AND t.entityType IN ('tag') AND t.entityId = tg.id AND tg.status = 'live' GROUP BY t.entityType", array($userId));
        foreach ($rs->result_array() as $row){
            $finalArray['tagsFollowingCount'] = $row['Count'];
        }
	$this->benchmark->mark('tag_count_end');
	$this->benchmark->mark('user_following_start');
        //User follower Count
        $rs = $this->dbHandle->query("SELECT count(*) as Count FROM tuserFollowTable WHERE entityId = ? AND status = 'follow' AND entityType = 'user'", array($userId));
        foreach ($rs->result_array() as $row){
            $finalArray['userFollowersCount'] = $row['Count'];
        }
        $this->benchmark->mark('user_following_end');
	$this->benchmark->mark('answer_upvote_start');
        //Answer's Upvote Count
        $rs = $this->dbHandle->query("SELECT count(*) as Count FROM messageTable m, digUpUserMap d, messageTable m1 WHERE m.userId = ? AND m.status = 'live' AND m.parentId = m.threadId AND m.fromOthers = 'user' AND m.msgId = d.productId AND d.digFlag = 1 AND d.digUpStatus = 'live' AND m1.msgId = m.threadId AND m1.status IN ('live','closed')", array($userId));
        foreach ($rs->result_array() as $row){
            $finalArray['answerUpvoteCount'] = $row['Count'];
        }
	$this->benchmark->mark('answer_upvote_end');
	$this->benchmark->mark('comment_upvote_start');
        //Comment's Upvote Count
        $rs = $this->dbHandle->query("SELECT count(*) as Count FROM messageTable m, digUpUserMap d, messageTable m1 WHERE m.userId = ? AND m.status = 'live' AND m.parentId = m.mainAnswerId AND m.fromOthers = 'discussion' AND m.msgId = d.productId AND d.digFlag = 1 AND d.digUpStatus = 'live' AND m1.msgId = m.threadId AND m1.status IN ('live','closed')", array($userId));
        foreach ($rs->result_array() as $row){
            $finalArray['commentUpvoteCount'] = $row['Count'];
        }
	$this->benchmark->mark('comment_upvote_end');
	$this->benchmark->mark('hq_answer_upvote_start');
        //HQ Answer Count
        $rs = $this->dbHandle->query("SELECT count(*) as upvoteCount, m.msgId FROM messageTable m, digUpUserMap d, messageTable m1 WHERE m.userId = ? AND m.status = 'live' AND m.parentId = m.threadId AND m.fromOthers = 'user' AND m.msgId = d.productId AND d.digFlag = 1 AND d.digUpStatus = 'live' AND m1.msgId = m.threadId AND m1.status IN ('live','closed') GROUP BY m.msgId HAVING upvoteCount >= 25", array($userId));
        $rows = $rs->result_array();
        if(is_array($rows) && count($rows)>0){
            $finalArray['HQAnswerCount'] = count($rows);
        }
	$this->benchmark->mark('hq_answer_upvote_end');
	$this->benchmark->mark('hq_comment_upvote_start');
        //HQ Comment Count
        $rs = $this->dbHandle->query("SELECT count(*) as upvoteCount, m.msgId FROM messageTable m, digUpUserMap d, messageTable m1 WHERE m.userId = ? AND m.status = 'live' AND m.parentId = m.mainAnswerId AND m.fromOthers = 'discussion' AND m.msgId = d.productId AND d.digFlag = 1 AND d.digUpStatus = 'live' AND m1.msgId = m.threadId AND m1.status IN ('live','closed') GROUP BY m.msgId HAVING upvoteCount >= 25", array($userId));
        $rows = $rs->result_array();
        if(is_array($rows) && count($rows)>0){
            $finalArray['HQCommentCount'] = count($rows);
        }
        $this->benchmark->mark('hq_comment_upvote_end');
	$returnArray = array(
				array('id'=>0,'lable'=>'Answer Later','value'=>$finalArray['answerLaterCount']),
                                array('id'=>1,'lable'=>'Comment Later','value'=>$finalArray['commentLaterCount']),
                                array('id'=>2,'lable'=>'Questions Asked','value'=>$finalArray['questionCount']),
                                array('id'=>3,'lable'=>'Answers','value'=>$finalArray['answerCount']),
                                array('id'=>4,'lable'=>'Discussions Started','value'=>$finalArray['discussionCount']),
                                array('id'=>5,'lable'=>'Discussion Comments','value'=>$finalArray['commentCount']),
                                array('id'=>6,'lable'=>'Questions Followed','value'=>$finalArray['questionsFollowedCount']),
                                array('id'=>7,'lable'=>'Discussions Followed','value'=>$finalArray['discussionFollowedCount']),
                                array('id'=>8,'lable'=>'Followers','value'=>$finalArray['userFollowersCount']),
                                array('id'=>9,'lable'=>'Following','value'=>$finalArray['userFollowingCount']),
                                array('id'=>10,'lable'=>'Tags Followed','value'=>$finalArray['tagsFollowingCount']),
                                array('id'=>11,'lable'=>'Upvotes on Answers','value'=>$finalArray['answerUpvoteCount']),
                                array('id'=>12,'lable'=>'Upvotes on Comments','value'=>$finalArray['commentUpvoteCount']),
                                array('id'=>13,'lable'=>'HQ Answers','value'=>$finalArray['HQAnswerCount']),
                                array('id'=>14,'lable'=>'HQ Comments','value'=>$finalArray['HQCommentCount'])
			);
	 
        return $returnArray;
    }

    function getUserActivity($userId, $loggedInUserId, $start, $count){
        $this->initiateModel();
        $this->load->helper('image');
        $finalArray = array();

        $contentQuery = "(SELECT creationDate AS activityTime, msgId, threadId, mainAnswerId, parentId, fromOthers, 'content' type FROM messageTable m use index (idx_composite_userId_status_type) WHERE fromOthers IN ('user','discussion') AND status IN ('live','closed') AND msgTxt != 'dummy' AND  userId = ? AND (SELECT status FROM messageTable pp WHERE pp.msgId = m.threadId) IN ('live','closed') AND (if((mainAnswerId<=0),true,((SELECT status FROM messageTable mtbp WHERE mtbp.msgId = m.parentId) IN ('live','closed')))))";

        $upvoteQuery = "(SELECT digTime AS activityTime, productId, mta.threadId, 0, 0, mta.fromOthers, 'upvote' type FROM digUpUserMap d1, messageTable mta, messageTable mta1 WHERE d1.digFlag = 1 AND d1.productId = mta.msgId AND mta.fromOthers IN ('user','discussion') AND mta.status IN ('live','closed') AND d1.userId = ? AND d1.digUpStatus = 'live' AND mta1.msgId = mta.threadId AND mta1.status IN ('live','closed'))";

        $followContentQuery = "(SELECT creationTime AS activityTime, msgId, threadId, mainAnswerId, parentId, fromOthers, 'followContent' type FROM tuserFollowTable t, messageTable m WHERE t.entityType IN ('question','discussion') AND t.userId = ? AND entityId = m.msgId AND m.status IN ('live','closed') AND t.status = 'follow')";

        $followTagQuery = "(SELECT t.creationTime AS activityTime, entityId, 0, 0, 0, '', 'followTag' type FROM tuserFollowTable t, tags m WHERE t.entityType IN ('tag') AND t.userId = ? AND t.entityId = m.id AND m.status = 'live' AND t.status = 'follow')";

        $followUserQuery = "(SELECT creationTime AS activityTime, entityId, 0, 0, 0, '', 'followUser' type FROM tuserFollowTable t WHERE t.entityType IN ('user') AND t.userId = ? AND t.status = 'follow')";
	$this->benchmark->mark('activity_query_start');
        $rs = "SELECT * FROM ($contentQuery UNION $upvoteQuery UNION $followContentQuery UNION $followTagQuery UNION $followUserQuery ) result ORDER BY result.activityTime DESC LIMIT $start, $count";
        $result = $this->dbHandle->query($rs, array($userId, $userId, $userId, $userId, $userId));
	$this->benchmark->mark('activity_query_end');
        //Now, fetch details for each Row
        $this->load->model('TagsModel');
        $i = 0;
        foreach ($result->result_array() as $row){
                switch ($row['type']){
                        case 'content':
                        case 'upvote':
                        case 'followContent':
                                        if($row['fromOthers'] == 'user'){
                                            $questionId = $row['threadId'];
                                            $finalArray[$i]['type'] = 'Q';
					    $finalArray[$i]['question']['activityTime'] = makeRelativeTime($row['activityTime']);					    
                                            if($row['parentId'] == 0 && $row['type'] == "content"){
                                                $finalArray[$i]['question']['activity'] = "Asked";
                                            }
                                            else if($row['parentId'] == $row['threadId'] && $row['type'] == "content"){
                                                $finalArray[$i]['question']['activity'] = "Answered";
                                            }
                                            else if($row['type'] == "content"){
                                                $finalArray[$i]['question']['activity'] = "Commented";
                                            }

                                            if($row['type'] == "upvote"){
                                                $finalArray[$i]['question']['activity'] = "Upvoted";
                                            }
                                            else if($row['type'] == "followContent"){
                                                $finalArray[$i]['question']['activity'] = "Following this Question";
                                            }

                                            $questionArray = $this->TagsModel->getQuestionDetails($questionId);
                                            if(is_array($questionArray) && count($questionArray)>0){
                                                $finalArray[$i]['question']['id'] = $questionId;
                                                $finalArray[$i]['question']['title'] = $questionArray[$questionId]['title'];
                                                $finalArray[$i]['question']['answerCount'] = $questionArray[$questionId]['answerCount'];
                                                $finalArray[$i]['question']['viewCount'] = $questionArray[$questionId]['viewCount'];
                                            }
                                        }
                                        else if($row['fromOthers'] == 'discussion'){
                                            $discussionId = $row['threadId'];
                                            
                                            $finalArray[$i]['type'] = 'D';
                                            $finalArray[$i]['discussion']['activityTime'] = makeRelativeTime($row['activityTime']);
 
                                            if($row['type'] == "upvote"){
                                                $finalArray[$i]['discussion']['activity'] = "Upvoted";
                                            }
                                            else if($row['type'] == "followContent"){
                                                $finalArray[$i]['discussion']['activity'] = "Following this Discussion";
                                            }
                                            else if($row['parentId'] == $row['threadId']){
                                                $finalArray[$i]['discussion']['activity'] = "Posted";
                                            }
                                            else if($row['parentId'] == $row['mainAnswerId']){
                                                $finalArray[$i]['discussion']['activity'] = "Commented";
                                            }
                                            else{
                                                $finalArray[$i]['discussion']['activity'] = "Replied";
                                            }
                                            
                                            $discussionArray = $this->TagsModel->getDiscussionDetails($discussionId);
                                            if(is_array($discussionArray) && count($discussionArray)>0){
                                                $finalArray[$i]['discussion']['id'] = $discussionId;
                                                $finalArray[$i]['discussion']['title'] = $discussionArray[$discussionId]['title'];
                                                $finalArray[$i]['discussion']['answerCount'] = $discussionArray[$discussionId]['answerCount'];
                                                $finalArray[$i]['discussion']['viewCount'] = $discussionArray[$discussionId]['viewCount'];
                                            }
                                        }
                                        break;
                        case 'followTag':
                                        $tagId = $row['msgId'];
                                        $finalArray[$i]['type'] = 'T';
                                        $finalArray[$i]['tag']['activity'] = "Following this Tag";
					$finalArray[$i]['tag']['activityTime'] = makeRelativeTime($row['activityTime']);
                                        

                                        $finalArray[$i]['tag']['tagId'] = $row['msgId'];
                                        $queryToCheckLiveTag = 'SELECT id, tags FROM tags WHERE id=? AND status="live"';

                                        $rs = $this->dbHandle->query($queryToCheckLiveTag , array($tagId));
                                        $liveTag = $rs->result_array();
                                        $tagName = "";
                                        foreach ($liveTag as $key => $tagRes) {
                                            $tagName =  $tagRes['tags'];
                                            $liveTagId = $tagRes['id'];
                                        }

                                        $tagDetailArray = array();
                                        if(!empty($liveTagId)){
                                            $queryToGetFollowerCount = 'SELECT count(*) as followerCount from tuserFollowTable WHERE entityId = ? AND status = "follow" AND entityType = "tag"';    
                                            $rs = $this->dbHandle->query($queryToGetFollowerCount , array($liveTagId));
                                            $tagDetailArray = $rs->result_array();
                                        }
                                       foreach ($tagDetailArray as $tagD){
                                                $finalArray[$i]['tag']['tagName'] = $tagName;
                                                $finalArray[$i]['tag']['followerCount'] = $tagD['followerCount'];
                                        }
                                        /*$rs = $this->dbHandle->query("SELECT count(*) followerCount, tags.id, tags.tags FROM tuserFollowTable, tags WHERE entityId = ? AND tuserFollowTable.status = 'follow' AND entityType = 'tag' AND tags.id=entityId AND tags.status='live' GROUP BY entityId", array($tagId));
                                        $tagDetailArray = $rs->result_array();
                                        foreach ($tagDetailArray as $tagD){
                                                $finalArray[$i]['tag']['tagName'] = $tagD['tags'];
                                                $finalArray[$i]['tag']['followerCount'] = $tagD['followerCount'];
                                        }*/
                                                                                
                                        break;
                        case 'followUser':
                                        $userId = $row['msgId'];
                                        $finalArray[$i]['type'] = 'U';
                                        $finalArray[$i]['user']['activity'] = "Following this User";
					$finalArray[$i]['user']['activityTime'] = makeRelativeTime($row['activityTime']);
                                        $finalArray[$i]['user']['userId'] = $row['msgId'];
                                        $rs = $this->dbHandle->query("SELECT firstname, lastname, t.userid, t.avtarimageurl, a.aboutMe, u.levelName FROM tuser t LEFT JOIN tUserAdditionalInfo a ON (t.userid = a.userId) LEFT JOIN userpointsystembymodule u ON (t.userid = u.userId AND u.moduleName = 'AnA') WHERE t.userid = ? GROUP BY userid", array($userId));
                                        $entityDetailArray = $rs->result_array();
                        
                                        foreach ($entityDetailArray as $entityD){
                                                $finalArray[$i]['user']['userName'] = $entityD['firstname']." ".$entityD['lastname'];
                                                $finalArray[$i]['user']['avtarimageurl'] = checkUserProfileImage($entityD['avtarimageurl']);
                                                $finalArray[$i]['user']['aboutMe'] = $entityD['aboutMe'];
                                                $finalArray[$i]['user']['levelName'] = $entityD['levelName'];
                                        }
                                        
                                        break;
                }
                $i++;
        }
        return $finalArray;
    }

    function getQuestionsByCategory($userId, $loggedInUserId, $category, $start, $count){
        $this->initiateModel();
        $finalArray = array();        
        switch ($category){

            case 'answerLater':
                                $rs = $this->dbHandle->query("SELECT entityId as questionId FROM entityShortlist e, messageTable m WHERE e.userId = ? AND e.status = 'live' AND e.entityType = 'question' AND e.entityId = m.msgId AND m.status IN ('live','closed') ORDER BY e.creationTime DESC LIMIT $start, $count", array($userId));
                                break;

            case 'questionsAsked':
                                $rs = $this->dbHandle->query("SELECT msgId as questionId FROM messageTable WHERE userId = ? AND status IN ('live','closed') AND fromOthers = 'user' AND parentId = 0 ORDER BY creationDate DESC LIMIT $start, $count", array($userId));
                                break;

            case 'answers':
                                $rs = $this->dbHandle->query("SELECT DISTINCT threadId as questionId FROM messageTable m use index (userId) WHERE userId = ? AND status IN ('live','closed') AND fromOthers = 'user' AND parentId = threadId AND (SELECT status FROM messageTable pp WHERE pp.msgId = m.threadId) IN ('live','closed') ORDER BY creationDate DESC LIMIT $start, $count", array($userId));    
                                break;

            case 'questionsFollowed':
                                $rs = $this->dbHandle->query("SELECT entityId as questionId FROM tuserFollowTable t, messageTable m WHERE t.userId = ? AND t.status = 'follow' AND t.entityType = 'question' AND t.entityId = m.msgId AND m.status IN ('live','closed') ORDER BY creationTime DESC LIMIT $start, $count", array($userId));
                                break;

            case 'answerUpvotedQuestions': 
                                $rs = $this->dbHandle->query("SELECT DISTINCT threadId as questionId FROM messageTable m use index (userId), digUpUserMap d WHERE m.userId = ? AND status IN ('live','closed') AND fromOthers = 'user' AND productId = msgId AND digFlag = 1 AND digUpStatus = 'live' AND m.parentId = m.threadId AND (SELECT status FROM messageTable pp WHERE pp.msgId = m.threadId) IN ('live','closed') ORDER BY d.digTime DESC LIMIT $start, $count", array($userId));
                                break;

            case 'HQAnswerQuestions':
                                $rs = $this->dbHandle->query("SELECT count(*) as upvoteCount, msgId, threadId as questionId FROM messageTable m use index (userId), digUpUserMap d WHERE m.userId = ? AND status IN ('live','closed') AND fromOthers = 'user' AND productId = msgId AND digFlag = 1 AND digUpStatus = 'live' AND m.parentId = m.threadId AND (SELECT status FROM messageTable pp WHERE pp.msgId = m.threadId) IN ('live','closed') GROUP BY m.msgId HAVING upvoteCount >= 25 ORDER BY d.digTime DESC LIMIT $start, $count", array($userId));
                                break;
        }

        $questionArray = array();
	$returnArr = array();
        if(isset($rs)){
            foreach ($rs->result_array() as $row){
                $questionArray[] = $row['questionId'];
            }        
        }
        if(is_array($questionArray) && count($questionArray) > 0){
            $this->load->model('TagsModel');
	    $questionIds = implode(",",$questionArray);
            $finalArray = $this->TagsModel->getQuestionDetails($questionIds);
	    foreach ($finalArray as $row){
		$returnArr[] = $row;
	    }
        }
        
        return $returnArr;
    }

    function getDiscussionsByCategory($userId, $loggedInUserId, $category, $start, $count){
        $this->initiateModel();
        $finalArray = array();
        switch ($category){

            case 'commentLater':
                                $rs = $this->dbHandle->query("SELECT entityId as discussionId FROM entityShortlist e, messageTable m WHERE e.userId = ? AND e.status = 'live' AND e.entityType = 'discussion' AND e.entityId = m.msgId AND m.status IN ('live','closed') ORDER BY e.creationTime DESC LIMIT $start, $count", array($userId));
                                break;

            case 'discussionsPosted':
                                $rs = $this->dbHandle->query("SELECT msgId as discussionId FROM messageTable WHERE userId = ? AND status IN ('live','closed') AND fromOthers = 'discussion' AND parentId = 0 ORDER BY creationDate DESC LIMIT $start, $count", array($userId));
                                break;

            case 'comments':
                                $rs = $this->dbHandle->query("SELECT DISTINCT threadId as discussionId FROM messageTable m use index (userId) WHERE userId = ? AND status IN ('live','closed') AND fromOthers = 'discussion' AND parentId = mainAnswerId AND (SELECT status FROM messageTable pp WHERE pp.msgId = m.threadId) IN ('live','closed') ORDER BY creationDate DESC LIMIT $start, $count", array($userId));
                                break;

            case 'discussionsFollowed':
                                $rs = $this->dbHandle->query("SELECT entityId as discussionId FROM tuserFollowTable t, messageTable m WHERE t.userId = ? AND t.status = 'follow' AND t.entityType = 'discussion' AND t.entityId = m.msgId AND m.status IN ('live','closed') ORDER BY creationTime DESC LIMIT $start, $count", array($userId));
                                break;

            case 'commentUpvotedDiscussions':
                                $rs = $this->dbHandle->query("SELECT DISTINCT threadId as discussionId FROM messageTable m use index (userId), digUpUserMap d WHERE m.userId = ? AND status IN ('live','closed') AND fromOthers = 'discussion' AND productId = msgId AND digFlag = 1 AND digUpStatus = 'live' AND m.parentId = m.mainAnswerId AND (SELECT status FROM messageTable pp WHERE pp.msgId = m.threadId) IN ('live','closed') ORDER BY d.digTime DESC LIMIT $start, $count", array($userId));
                                break;

            case 'HQCommentDiscussions':
                                $rs = $this->dbHandle->query("SELECT count(*) as upvoteCount, msgId, threadId as discussionId FROM messageTable m use index (userId), digUpUserMap d WHERE m.userId = ? AND status IN ('live','closed') AND fromOthers = 'discussion' AND productId = msgId AND digFlag = 1 AND digUpStatus = 'live' AND m.parentId = m.mainAnswerId AND (SELECT status FROM messageTable pp WHERE pp.msgId = m.threadId) IN ('live','closed') GROUP BY m.msgId HAVING upvoteCount >= 25 ORDER BY d.digTime DESC LIMIT $start, $count", array($userId));
                                break;
        }

        $discussionArray = array();
        $returnArr = array();
        if(isset($rs)){
            foreach ($rs->result_array() as $row){
                $discussionArray[] = $row['discussionId'];
            }
        }
        if(is_array($discussionArray) && count($discussionArray) > 0){
            $this->load->model('TagsModel');
            $discussionIds = implode(",",$discussionArray);
            $finalArray = $this->TagsModel->getDiscussionDetails($discussionIds);
            foreach ($finalArray as $row){
                $returnArr[] = $row;
            }
        }

        return $returnArr;
    }

    public function updatePrivacySettings($userId,$field,$isPrivate){
        $this->initiateModel('read');
        $sql = "SELECT * FROM tUserDataPrivacySettings where userId = ? AND fieldId = ? AND status = 'live'";

        $query = $this->dbHandle->query($sql, array($userId,$field));
        $cnt = 0;
        $cnt = $query->num_rows();
        $this->initiateModel('write');

        if($cnt > 0){
            
            if($isPrivate == 'NO'){
                $sql = "UPDATE tUserDataPrivacySettings set status  = 'history' where userId = ? AND fieldId = ? AND status = 'live'";
                $this->dbHandle->query($sql, array($userId,$field));    
            }
            
        }
        else{
            
            if($isPrivate == 'YES'){
                $sql = "INSERT INTO tUserDataPrivacySettings(userId,fieldId) VALUES(?,?)";
                $this->dbHandle->query($sql,array($userId,$field));    
            }            
        }
        

        return true;
    }

	function getUsersSectionwiseFollowedTags($userId){

        $this->initiateModel();
        $rs = $this->dbHandle->query("SELECT entityId, followType FROM tuserFollowTable WHERE userId = ? AND entityType = 'tag' AND status = 'follow' AND !ISNULL(followType)", array($userId));

        $sectionWiseFollowedTags = array();
        foreach ($rs->result_array() as $value){
                $followType = explode("|",$value['followType']);
                foreach ($followType as $followVal) {
                    $followVal = trim($followVal, "|");
                    if($followVal && !in_array($value['entityId'], $sectionWiseFollowedTags[$followVal]))
                        $sectionWiseFollowedTags[$followVal][] = $value['entityId'];
                }
        }
        return $sectionWiseFollowedTags;
    }

    function resetUserWorkExperienceSettings($userId=0){
        $this->initiateModel('write');
        $sql = "update tUserDataPrivacySettings set status = 'history' where userId = ? and (fieldId LIKE '%EmployerworkExp%' OR fieldId LIKE '%DesignationworkExp%' OR fieldId LIKE '%DepartmentworkExp%' OR fieldId LIKE '%CurrentJobworkExp%')";

        $query = $this->dbHandle->query($sql,array($userId));
    }

}

