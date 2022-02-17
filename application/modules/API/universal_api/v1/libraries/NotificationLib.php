<?php
/**
 * NotificationLib Class.
 * Common Library for all notification related operations
 * @date    2015-11-02
 * @author  Romil Goel
 * @todo    none
*/

class NotificationLib {

    /**
     * constructor
     */
    public function __construct(){
        $this->CI = &get_instance();
    }

    public function isUserEligibleForGCMNotification($userId){

        $notificationinfomodel = $this->CI->load->model("v1/notificationinfomodel");
        $isEligible            = $notificationinfomodel->getUserGCMIds($userId);

        if(empty($isEligible))
            return false;
        else
            return true;
    }

    public function isUserEligibleForInAppNotification($userId){

        $notificationinfomodel = $this->CI->load->model("v1/notificationinfomodel");
        $isEligible            = $notificationinfomodel->getUserIdsForInAppNotification($userId);

        if(empty($isEligible))
            return false;
        else
            return true;
    }

    public function isGCMNotificationCountExceedLimit($userId, $day_limit_flag = false ,$actionKey = "", $primaryId = 0, $secondaryId = 0) {
        $notificationinfomodel = $this->CI->load->model("v1/notificationinfomodel");
        $this->CI->load->config('NotificationConfig');

        $flag = false;
        if(!$day_limit_flag){
            $count = $notificationinfomodel->fetchNotificationCountForUser($userId);
            if($count >= TOTAL_LIMIT)
                $flag = true;
        }
        else{
            $alreadyExists = $notificationinfomodel->checkIfNotificationsAlreadyExistsForToday($userId,$actionKey, $primaryId, $secondaryId);
            if($alreadyExists){
                $count = $notificationinfomodel->fetchNotificationCountForUser($userId);
                if($count >= TOTAL_LIMIT)
                    $flag = true;       
            }
        }

        return $flag;
    }

    function dailyActivitySummaryCron(){

        $notificationinfomodel = $this->CI->load->model("v1/notificationinfomodel");
        $userWiseDetails = array();

        // fetch all the answers posted today
        $answers = $notificationinfomodel->getTodaysAnswers();

        $replies = $notificationinfomodel->getTodaysRepliesOnAnswers();

        $questionIds = array();

        foreach ($answers as $value) {
            $questionIds[] = $value['threadId'];
        }

        $questionsQwner = $notificationinfomodel->getQuestionsOwner($questionIds);

        // count of answers and questions
        foreach ($answers as $value) {

            $owner = $questionsQwner[$value['threadId']]['userId'];

            $userWiseDetails[$owner]['activity']['new_answers'][] = $value['msgId'];
            $userWiseDetails[$owner]['mycontent']['questions'][]   = $value['threadId'];
        }

        $answerIds = array();
        foreach ($replies as $value) {
            $answerIds[] = $value['parentId'];
        }

        $answersOwner = $notificationinfomodel->getAnswerOwners($answerIds);

        // count of replies and replied answers
        foreach ($replies as $value) {
            
            $owner = $answersOwner[$value['parentId']]['userId'];

            $userWiseDetails[$owner]['activity']['replies'][]         = $value['msgId'];
            $userWiseDetails[$owner]['mycontent']['answers'][] = $value['parentId'];
        }

        // get todays all upvotes
        $upvotes = $notificationinfomodel->getTodaysUpvotes();
        $upvotesThreads = array();
        foreach ($upvotes as $value) {
            $upvotesThreads[] = $value['productId'];
        }

        $upvotesAnswers = $notificationinfomodel->filterAnswers($upvotesThreads);
        $upvotesComments = $notificationinfomodel->filterComments($upvotesThreads);

        // upvotes answers and commments
        foreach ($upvotes as $value) {
            
            if($upvotesAnswers[$value['productId']]){
                $owner = $upvotesAnswers[$value['productId']]['userId'];

                $userWiseDetails[$owner]['activity']['upvotes_answers'][] = $value['userId']."_".$value['productId'];
                $userWiseDetails[$owner]['mycontent']['answers'][] = $value['productId'];
            }

            else if($upvotesComments[$value['productId']]){
                $owner = $upvotesComments[$value['productId']]['userId'];

                $userWiseDetails[$owner]['activity']['upvotes_comments'][] = $value['userId']."_".$value['productId'];
                $userWiseDetails[$owner]['mycontent']['comments'][] = $value['productId'];
            }
        }

        $discussionComments          = array();
        $discussionCommentsThreadIds = array();
        $discussionReplies           = array();
        $discussionRepliesCommentIds = array();
        $discussionCommentsAndReplies = $notificationinfomodel->getTodaysDiscussionCommentsAndReplies();

        foreach ($discussionCommentsAndReplies as $value) {
            
            if($value['pathCount'] == 3){
                $discussionComments[] = $value;
                $discussionCommentsThreadIds[] = $value['parentId'];
            }
            else if($value['pathCount'] == 4){
                $discussionReplies[] = $value;
                $discussionRepliesCommentIds[] = $value['parentId'];
            }
        }

        $discussionOwners        = $notificationinfomodel->getDiscussionsEntityOwner($discussionCommentsThreadIds);
        $discussionCommentOwners = $notificationinfomodel->getDiscussionsEntityOwner($discussionRepliesCommentIds);

        foreach ($discussionComments as $value) {
            
            $owner = $discussionOwners[$value['parentId']]['userId'];

            $userWiseDetails[$owner]['activity']['new_comments'][] = $value['msgId'];
            $userWiseDetails[$owner]['mycontent']['discussion'][] = $value['parentId'];
        }

        foreach ($discussionReplies as $value) {
            
            $owner = $discussionCommentOwners[$value['parentId']]['userId'];

            $userWiseDetails[$owner]['activity']['new_replies'][] = $value['msgId'];
            $userWiseDetails[$owner]['mycontent']['comments'][] = $value['parentId'];
        }


        $usersNotificationMsg = array();
        foreach ($userWiseDetails as $userId => $value) {

            if(!$this->isUserEligibleForGCMNotification($userId)) {
                continue;
            }
            /*if($userId != "3392259"){
                continue;
            }*/
            $activityText = array();

            if($userWiseDetails[$userId]['activity']['new_answers']){
                $count = count(array_unique($userWiseDetails[$userId]['activity']['new_answers']));
                if($count > 1)
                    $activityText[] = $count." new answers on your questions";
                else
                    $activityText[] = $count." new answer on your question";
            }

            if($userWiseDetails[$userId]['activity']['replies']){
                $count = count(array_unique($userWiseDetails[$userId]['activity']['replies']));
                if($count > 1)
                    $activityText[] = $count." new comments on your answers";
                else
                    $activityText[] = $count." new comment on your answer";
            }

            if($userWiseDetails[$userId]['activity']['upvotes_answers']){
                $count = count(array_unique($userWiseDetails[$userId]['activity']['upvotes_answers']));
                if($count > 1)
                    $activityText[] = $count." new upvotes on your answer".(count(array_unique($userWiseDetails[$userId]['mycontent']['answers'])) > 1 ? 's' : '');
                else
                    $activityText[] = $count." new upvote on your answer".(count(array_unique($userWiseDetails[$userId]['mycontent']['answers'])) > 1 ? 's' : '');
            }

            if($userWiseDetails[$userId]['activity']['new_comments']){
                $count = count(array_unique($userWiseDetails[$userId]['activity']['new_comments']));
                if($count > 1)
                    $activityText[] = $count." new comments on your discussions";
                else
                    $activityText[] = $count." new comment on your discussion";
            }

            if($userWiseDetails[$userId]['activity']['new_replies']){
                $count = count(array_unique($userWiseDetails[$userId]['activity']['new_replies']));
                if($count > 1)
                    $activityText[] = $count." new replies on your comment".(count(array_unique($userWiseDetails[$userId]['mycontent']['comments'])) > 1 ? 's' : '');
                else
                    $activityText[] = $count." new reply on your comment".(count(array_unique($userWiseDetails[$userId]['mycontent']['comments'])) > 1 ? 's' : '');
            }

            if($userWiseDetails[$userId]['activity']['upvotes_comments']){
                $count = count(array_unique($userWiseDetails[$userId]['activity']['upvotes_comments']));
                if($count > 1)
                    $activityText[] = $count." new upvotes on your comment".(count(array_unique($userWiseDetails[$userId]['mycontent']['comments'])) > 1 ? 's' : '');
                else
                    $activityText[] = $count." new upvote on your comment".(count(array_unique($userWiseDetails[$userId]['mycontent']['comments'])) > 1 ? 's' : '');
            }

            $activityText = implode(", ", $activityText);

            // $contentText = array();
            // if($userWiseDetails[$owner]['mycontent']['questions']){
            //     $count = count(array_unique($userWiseDetails[$owner]['mycontent']['questions']));
            //     if($count > 1)
            //         $contentText[] = $count." Questions";
            //     else
            //         $contentText[] = $count." Question";
            // }

            // if($userWiseDetails[$owner]['mycontent']['answers']){
            //     $count = count(array_unique($userWiseDetails[$owner]['mycontent']['answers']));
            //     if($count > 1)
            //         $contentText[] = $count." Answers";
            //     else
            //         $contentText[] = $count." Answer";
            // }

            // if($userWiseDetails[$owner]['mycontent']['discussion']){
            //     $count = count(array_unique($userWiseDetails[$owner]['mycontent']['discussion']));
            //     if($count > 1)
            //         $contentText[] = $count." Discussions";
            //     else
            //         $contentText[] = $count." Discussion";
            // }

            // if($userWiseDetails[$owner]['mycontent']['comments']){
            //     $count = count(array_unique($userWiseDetails[$owner]['mycontent']['comments']));
            //     if($count > 1)
            //         $contentText[] = $count." Discussion Comments";
            //     else
            //         $contentText[] = $count." Discussion Comment";
            // }

            // $contentText = implode(", ", $contentText);

            // $usersNotificationMsg[$userId] = $activityText." on ".$contentText." you had posted.";
            $usersNotificationMsg[$userId] = "Last 24 hours activity stats : <b>".$activityText."</b>.";
        }


        $this->CI->load->model("apicronmodel");
        $this->CI->load->config("NotificationConfig");
        $this->CI->load->config("apiConfig");
        $this->CI->load->model('Notifications/notificationmodel');

        $apicronmodel = new apicronmodel();
        $notificationmodel = new NotificationModel();
        $totalCount  = 0;
        $globalCountArray = array();
        foreach ($usersNotificationMsg as $userId => $text) {

            $data                     = array();
            $data['notificationType'] = INAPP_NOTIFICATION;
            $data['userId']           = $userId;
            $data['title']            = SHIKSHA_APP_NAME;
            $data['message']          = strip_tags($text);
            $data['primaryId']        = 0;
            $data['primaryIdType']    = 'notification';
            $data['landing_page']     = APP_INAPP_NOTIFICATION_PAGE;
            $globalCountArray[] = $userId;
            $apicronmodel->insertGCMNotification($data);
            $time = date('Y-m-d H:i:s');
            $secondaryData = array();
            $notificationmodel->insertDataInNotificationInAppQueue($userId,'DAILY_SUMMARY','Daily Summary Notification',$text,$time,'unread',$userId,'user',$secondaryData,APP_USER_PROFILE_PAGE,USER_PROFILE_PAGE_DEFAULT);

            $totalCount++;




        }
        if(!empty($globalCountArray)){
            $redisLib = PredisLibrary::getInstance();//$this->CI->load->library('common/PredisLibrary');    

            $cnt = $notificationmodel->fetchUnreadNotificationCount($globalCountArray);

            foreach ($cnt as $key => $value) {
                $redisLib->addMembersToHash("notificationsCount:inapp",array($value['userId']=>$value['count']));
            }
            $globalCountArray = array();
        }

        return $totalCount;
    }

}
