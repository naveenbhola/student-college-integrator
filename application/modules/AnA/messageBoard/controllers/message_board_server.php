<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev:: 411           $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/07/14 05:27:16 $:  Date of last commit


This class provides the Message Board Server Web Services.
The message_board_client.php makes call to this server using XML RPC calls.

*/

class Message_board_server extends MX_Controller {

/**
 *	index function to recieve the incoming request
 */
    function init() {
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('messageboardconfig');
        $this->load->library('alerts_client');
        $this->load->helper('url');
        $this->load->helper('shikshautility');
        $this->load->helper('CA/cr');

        $this->dbLibObj = DbLibCommon::getInstance('AnA');

        return true;
    }

    function index() {

    //load XML RPC Libs
        $this->init();

        //Define the web services method
        $config['functions']['getSomeDetailsForGoogleResults'] = array('function' => 'Message_board_server.getSomeDetailsForGoogleResults');

        $config['functions']['getDescriptionForQuestion'] = array('function' => 'Message_board_server.getDescriptionForQuestion');

        $config['functions']['getMsgTree'] = array('function' => 'Message_board_server.getMsgTree');

        $config['functions']['getPopularTopics'] = array('function' => 'Message_board_server.getPopularTopics');

        $config['functions']['getUnansweredTopics'] = array('function' => 'Message_board_server.getUnansweredTopics');

        $config['functions']['getRecentPostedTopics'] = array('function' => 'Message_board_server.getRecentPostedTopics');

        $config['functions']['getMyTopics'] = array('function' => 'Message_board_server.getMyTopics');

        $config['functions']['addTopic'] = array('function' => 'Message_board_server.addTopic');

        $config['functions']['postReply'] = array('function' => 'Message_board_server.postReply');

        $config['functions']['postExpertReply'] = array('function' => 'Message_board_server.postExpertReply');

        $config['functions']['reportAbuse'] = array('function' => 'Message_board_server.reportAbuse');

        $config['functions']['getMostContributingUser'] = array('function' => 'Message_board_server.getMostContributingUser');

        $config['functions']['getMessageBoardFeeds'] = array('function' => 'Message_board_server.getMessageBoardFeeds');

        $config['functions']['getMessageBoardCommentFeeds'] = array('function' => 'Message_board_server.getMessageBoardCommentFeeds');

        $config['functions']['getTopicsForHomePageS'] = array('function' => 'Message_board_server.getTopicsForHomePageS');

        $config['functions']['updateViewCount'] = array('function' => 'Message_board_server.updateViewCount');

        $config['functions']['deleteTopic'] = array('function' => 'Message_board_server.deleteTopic');

        $config['functions']['deleteTopicFromCMS'] = array('function' => 'Message_board_server.deleteTopicFromCMS');

        $config['functions']['deleteCommentFromCMS'] = array('function' => 'Message_board_server.deleteCommentFromCMS');

        $config['functions']['getDetailsForSearch'] = array('function' => 'Message_board_server.getDetailsForSearch');

        $config['functions']['updateTopic'] = array('function' => 'Message_board_server.updateTopic');

        $config['functions']['closeDiscussion'] = array('function' => 'Message_board_server.closeDiscussion');

        $config['functions']['getBoardForIndex'] = array('function' => 'Message_board_server.getBoardForIndex');

        $config['functions']['getTopicsForListing'] = array('function' => 'Message_board_server.getTopicsForListing');

        $config['functions']['getTopicsForGroups'] = array('function' => 'Message_board_server.getTopicsForGroups');

        $config['functions']['getNewReplyCount'] = array('function' => 'Message_board_server.getNewReplyCount');

        $config['functions']['getUserQuestions'] = array('function' => 'Message_board_server.getUserQuestions');

        $config['functions']['getUserAnswers'] = array('function' => 'Message_board_server.getUserAnswers');

        $config['functions']['getNewReplyCountForQuestions'] = array('function' => 'Message_board_server.getNewReplyCountForQuestions');

        $config['functions']['updateCountry'] = array('function' => 'Message_board_server.updateCountry');

        $config['functions']['updateDigVal'] = array('function' => 'Message_board_server.updateDigVal');

        $config['functions']['setBestAnsForThread'] = array('function' => 'Message_board_server.setBestAnsForThread');

        $config['functions']['closeQestionCron'] = array('function' => 'Message_board_server.closeQestionCron');

        $config['functions']['getQuestionAnswersForHome'] = array('function' => 'Message_board_server.getQuestionAnswersForHome');

        $config['functions']['getQuestionFromQuestionCategories'] = array('function' => 'Message_board_server.getQuestionFromQuestionCategories');

        $config['functions']['getQuestionForActivityLandingPages'] = array('function' => 'Message_board_server.getQuestionForActivityLandingPages');

        $config['functions']['getInfoForThreads'] = array('function' => 'Message_board_server.getInfoForThreads');

        $config['functions']['getMsgDetails'] = array('function' => 'Message_board_server.getMsgDetails');

        $config['functions']['editMsgDetails'] = array('function' => 'Message_board_server.editMsgDetails');

        $config['functions']['getDataForRelatedQuestions'] = array('function' => 'Message_board_server.getDataForRelatedQuestions');

        $config['functions']['updateEditorialBin'] = array('function' => 'Message_board_server.updateEditorialBin');

        $config['functions']['getQnAForEditorialBin'] = array('function' => 'Message_board_server.getQnAForEditorialBin');

        $config['functions']['getLastQnAOfUser'] = array('function' => 'Message_board_server.getLastQnAOfUser');

        $config['functions']['getUserInfoForLeaderBaord'] = array('function' => 'Message_board_server.getUserInfoForLeaderBaord');

        $config['functions']['getDataForInformationWidgetInAnA'] = array('function' => 'Message_board_server.getDataForInformationWidgetInAnA');

        $config['functions']['getEditorialQuestionsForHomePages'] = array('function' => 'Message_board_server.getEditorialQuestionsForHomePages');

        $config['functions']['getAverageTimeForAswer'] = array('function' => 'Message_board_server.getAverageTimeForAswer');

        $config['functions']['getReportAbuseForm'] = array('function' => 'Message_board_server.getReportAbuseForm');

        $config['functions']['getUserLevel'] = array('function' => 'Message_board_server.getUserLevel');

        $config['functions']['setAbuseRecord'] = array('function' => 'Message_board_server.setAbuseRecord');

        $config['functions']['getUserDetails'] = array('function' => 'Message_board_server.getUserDetails');

        $config['functions']['getMsgText'] = array('function' => 'Message_board_server.getMsgText');

        $config['functions']['getAbuseUsersDetails'] = array('function' => 'Message_board_server.getAbuseUsersDetails');

        $config['functions']['getUserFlag'] = array('function' => 'Message_board_server.getUserFlag');

        $config['functions']['getUserVCardDetails'] = array('function' => 'Message_board_server.getUserVCardDetails');

        $config['functions']['setUserVCardDetails'] = array('function' => 'Message_board_server.setUserVCardDetails');

        $config['functions']['getVCardStatus'] = array('function' => 'Message_board_server.getVCardStatus');

        $config['functions']['setUserVCardParam'] = array('function' => 'Message_board_server.setUserVCardParam');

        $config['functions']['sendMailForVCard'] = array('function' => 'Message_board_server.sendMailForVCard');

        $config['functions']['getTopContributors'] = array('function' => 'Message_board_server.getTopContributors');

        $config['functions']['getMailDataOnCommentPosting'] = array('function' => 'Message_board_server.getMailDataOnCommentPosting');

        $config['functions']['getWallData'] = array('function' => 'Message_board_server.getWallData');

        $config['functions']['getWallDataForListings'] = array('function' => 'Message_board_server.getWallDataForListings');

        $config['functions']['sendMailToAbusePeople'] = array('function' => 'Message_board_server.sendMailToAbusePeople');

        $config['functions']['getTopAnswersWall'] = array('function' => 'Message_board_server.getTopAnswersWall');

        $config['functions']['getCategoryCountry'] = array('function' => 'Message_board_server.getCategoryCountry');

        $config['functions']['getCommentSection'] = array('function' => 'Message_board_server.getCommentSection');

        $config['functions']['getEntityComments'] = array('function' => 'Message_board_server.getEntityComments');

        $config['functions']['showOtherRating'] = array('function' => 'Message_board_server.showOtherRating');

        $config['functions']['getProfileData'] = array('function' => 'Message_board_server.getProfileData');

        $config['functions']['getUserProfileDetails'] = array('function' => 'Message_board_server.getUserProfileDetails');

        $config['functions']['getFollowUser'] = array('function' => 'Message_board_server.getFollowUser');

        $config['functions']['calculateRankByRepuationPoints'] = array('function' => 'Message_board_server.calculateRankByRepuationPoints');

        $config['functions']['setFollowUser'] = array('function' => 'Message_board_server.setFollowUser');

        $config['functions']['advisoryBoard'] = array('function' => 'Message_board_server.advisoryBoard');

        $config['functions']['getBestAnswerMailData'] = array('function' => 'Message_board_server.getBestAnswerMailData');

        $config['functions']['getParentComments'] = array('function' => 'Message_board_server.getParentComments');

        $config['functions']['getHomepageData'] = array('function' => 'Message_board_server.getHomepageData');

        $config['functions']['getTopicDetailForEdit'] = array('function' => 'Message_board_server.getTopicDetailForEdit');

        $config['functions']['updateCafePost'] = array('function' => 'Message_board_server.updateCafePost');

        $config['functions']['getCountCommentsToBeDisplayed'] = array('function' => 'Message_board_server.getCountCommentsToBeDisplayed');

        //$config['functions']['setFBSessionKey'] = array('function' => 'Message_board_server.setFBSessionKey');
        //$config['functions']['getFBSessionKey'] = array('function' => 'Message_board_server.getFBSessionKey');
        $config['functions']['setFBWallLog'] = array('function' => 'Message_board_server.setFBWallLog');

        $config['functions']['getDataForFacebook'] = array('function' => 'Message_board_server.getDataForFacebook');
        $config['functions']['getUserReputationPoints'] = array('function' => 'Message_board_server.getUserReputationPoints');


        $config['functions']['tenDaysInactivityMailer'] = array('function' => 'Message_board_server.tenDaysInactivityMailer');
        $config['functions']['tenDaysInactiveDiscussionAnnoucement'] = array('function' => 'Message_board_server.tenDaysInactiveDiscussionAnnoucement');
        $config['functions']['getDataUser'] = array('function' => 'Message_board_server.getDataUser');
        $config['functions']['tuserReputationPreviousPointEntry'] = array('function' => 'Message_board_server.tuserReputationPreviousPointEntry');

        $config['functions']['updateTitle'] = array('function' => 'Message_board_server.updateTitle');
        $config['functions']['updateTitleForCMS'] = array('function' => 'Message_board_server.updateTitleForCMS');
        $config['functions']['calViewAnswerComment'] = array('function' => 'Message_board_server.calViewAnswerComment');
        $config['functions']['checkInQuestionLog'] = array('function' => 'Message_board_server.checkInQuestionLog');
        //Added by Ankur for Homepage-Rehash
        $config['functions']['getHomepageCafeWall'] = array('function' => 'Message_board_server.getHomepageCafeWall');
        $config['functions']['checkBestAnswer'] = array('function' => 'Message_board_server.checkBestAnswer');
        $config['functions']['getMasterListSitemap'] = array('function' => 'Message_board_server.getMasterListSitemap');
        $config['functions']['updateQnAMasterListTable'] = array('function' => 'Message_board_server.updateQnAMasterListTable');
        $config['functions']['getUserInNetwork'] = array('function' => 'Message_board_server.getUserInNetwork');
        $config['functions']['getMentionMailersData'] = array('function' => 'Message_board_server.getMentionMailersData');
        $config['functions']['linkQuestionResult'] = array('function' => 'Message_board_server.linkQuestionResult');
        $config['functions']['getRelatedSearchDiscussion'] = array('function' => 'Message_board_server.getRelatedSearchDiscussion');
        $config['functions']['checkForDiscussionStatus'] = array('function' => 'Message_board_server.checkForDiscussionStatus');
        $config['functions']['getLinkedDiscussion'] = array('function' => 'Message_board_server.getLinkedDiscussion');
	$config['functions']['getRelatedDiscussions'] = array('function' => 'Message_board_server.getRelatedDiscussions');
	$config['functions']['setExpertData'] = array('function' => 'Message_board_server.setExpertData');
	$config['functions']['getLatestPostedQuestions'] = array('function' => 'Message_board_server.getLatestPostedQuestions');
 
       
        //initialize
		
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

/* To incorporate seo changes
 * i.e added case 'seo' in seoUrl in url.helper*/

    function seo_update($creationDate) {
        return false;
    }

    function check_legacy_seo_update($creationDate,$description) {
        $start = strtotime($creationDate);
        $end = strtotime('2011-03-18');
        //If the creation date is before 18 March i.e this is a legacy question and the title is available for this question, then we will return true
        if( $end-$start > 0 && $description!='' )
            return true;	//Depicts that we have to create the URL from the description
        else
            return false;	//Depicts that we have to create the URL from the Title
    }

    function getSomeDetailsForGoogleResults($request) {
        $parameters = $request->output_parameters();
        $IdArray = $parameters['0'];
	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle();
        
            $googleResCatCountry = array();
            $googleResCreationDate = array();
            $googleResBestAnswerFlag = array();
            foreach($IdArray as $Id){
             $category= '';
             $country = '';
             $time = '';
             $catCmd = "SELECT cbt.name category from categoryBoardTable cbt INNER JOIN messageCategoryTable mct on (cbt.boardId = mct.categoryId) WHERE mct.threadId = ? AND cbt.parentId = 1";
             //error_log(print_r($catCmd,true),3,'/home/aakash/Desktop/aakash.log');
             $query = $dbHandle->query($catCmd,array($Id));
             foreach($query->result_array() as $row){
                $category = $row['category'];
             }
             $countryCmd = "SELECT ct.name country from countryTable ct INNER JOIN messageCountryTable mct on (ct.countryId = mct.countryId) WHERE mct.threadId = ? AND ct.countryId>1";
             //error_log(print_r($countryCmd,true),3,'/home/aakash/Desktop/aakash.log');
             $query = $dbHandle->query($countryCmd,array($Id));
             foreach($query->result_array() as $row){
                $country = $row['country'];
            }
            $timeCmd = "SELECT creationDate time FROM messageTable where msgId = ?";
            //error_log(print_r($timeCmd,true),3,'/home/aakash/Desktop/aakash.log');
            $query = $dbHandle->query($timeCmd,array($Id));
            foreach($query->result_array() as $row) {
                $time = $row['time'];
            }
            if($category!='' && $country!='')
                $googleResCatCountry[] = $category."-".$country;
            $googleResCreationDate[] = $time;
        }
        $mainArray = array();
        $mainArray[] = $googleResCatCountry;
        $mainArray[] = $googleResCreationDate;
        //error_log(print_r($mainArray,true),3,'/home/aakash/Desktop/aakash.log');
        $mainArray = json_encode($mainArray);
        //error_log(print_r($mainArray,true),3,'/home/aakash/Desktop/aakash.log');
        $response = array($mainArray,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getDescriptionForQuestion($request) {
        $parameters = $request->output_parameters();
        $topicId = $parameters['0'];
        $description = '';
	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle($topicId);
        $queryCmd = "SELECT description from messageDiscussion WHERE threadId = ?";

        $query = $dbHandle->query($queryCmd,array($topicId));
        foreach($query->result_array() as $row) {
            $description = $row['description'];
        }

        $response = array($description,'string');
        return $this->xmlrpc->send_response($response);
    }

    function calculateRankByRepuationPoints($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
	$dbHandle = $this->_loadDatabaseHandle();

        $this->load->model('UserPointSystemModel');
        $result  = $this->UserPointSystemModel->calculateRankByRepuationPoints($dbHandleSent,$userId);
        $response = array($result,'int');
        return $this->xmlrpc->send_response($response);
    }

    function tuserReputationPreviousPointEntry($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
	$dbHandle = $this->_loadDatabaseHandle();
        $this->load->model('UserPointSystemModel');
        $res = $this->UserPointSystemModel->tuserReputationPreviousPointEntry($userID, $dbHandle);
        $response =$res;
        return $this->xmlrpc->send_response($response);
    }

    function updateQnAMasterListTable($request) {
    //$this->init();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
	$dbHandle = $this->_loadDatabaseHandle('write');
            /*$createQuery = "CREATE TABLE IF NOT EXISTS `qnaMasterQuestionTable` ( `msgId` bigint(20) NOT NULL,  PRIMARY KEY (`msgId`) ) ENGINE=MyISAM";
            $cQuery = $dbHandle->query($createQuery);

            $mQuery = "SELECT * from qnaMasterQuestionTable";
            $mResult = $dbHandle->query($mQuery);
            $mArray =  array();
            $mArray[] = 0;
            foreach($mResult->result_array() as $row){
                $mArray[] = $row['msgId'];
            }
            $mArray = implode(",",$mArray);
            $sQuery = "(SELECT distinct mtb.threadId as msgId FROM messageTableBestAnsMap mtb INNER JOIN messageTable mt0 ON (mtb.threadId = mt0.msgId )WHERE mtb.threadId NOT IN ($mArray) AND mt0.status IN ('live','closed') AND ((mt0.listingTypeId = 0)OR(mt0.listingTypeId != 0 AND char_length(mt0.msgTxt)<141)))UNION (SELECT mt.msgId msgId from messageTable mt INNER JOIN  (SELECT temp1.parentId msgId FROM (SELECT m1.parentId, count(*) AS total FROM messageTable m1 WHERE  m1.parentId NOT IN ($mArray) and m1.mainAnswerId = 0 AND m1.parentId !=0 and m1.status IN ('live','closed')  AND ((m1.listingTypeId = 0)OR(m1.listingTypeId != 0 AND char_length(m1.msgTxt)<141)) GROUP BY m1.parentId )temp1 WHERE temp1.total >1 ) temp ON(mt.msgId = temp.msgId) WHERE mt.viewCount>20)UNION (SELECT temp1.parentId FROM (SELECT m1.parentId, count(*) AS total FROM messageTable m1 WHERE  m1.parentId NOT IN ($mArray) and m1.mainAnswerId = 0 AND m1.parentId !=0 and m1.status IN ('live','closed')  AND ((m1.listingTypeId = 0)OR(m1.listingTypeId != 0 AND char_length(m1.msgTxt)<141)) GROUP BY m1.parentId )temp1 WHERE temp1.total >2 ) UNION(SELECT temp2.parentId FROM (SELECT m2.parentId,SUM(digUp) likes from messageTable m2 WHERE m2.parentId NOT IN ($mArray) and m2.mainAnswerId = 0 and m2.parentId !=0 and m2.status IN ('live','closed') AND ((m2.listingTypeId = 0)OR(m2.listingTypeId != 0 AND char_length(m2.msgTxt)<141)) GROUP BY parentId) temp2 WHERE temp2.likes>2)UNION(SELECT m1.threadId msgId from messageTable m1 INNER JOIN tuser lm ON (m1.userId = lm.userid)  WHERE m1.threadId NOT IN($mArray) AND m1.mainAnswerId = 0 AND  m1.listingTypeId!=0 AND lm.userGroup='enterprise' AND m1.status IN ('live','closed') and m1.fromOthers='user')";
            $sResult = $dbHandle->query($sQuery);
            foreach($sResult->result_array() as $row){
            $val = $row['msgId'];
            $insertQuery = "INSERT INTO qnaMasterQuestionTable (msgId) values($val)";
            $iResult = $dbHandle->query($insertQuery);
            }*/

        //Now, run the query to fetch all the Master list questions from DB Tables
        $sQuery = "(SELECT temp1.parentId msgId FROM (SELECT m1.parentId, count(*) AS total FROM    messageTable m1 WHERE m1.mainAnswerId = 0 AND m1.parentId !=0 and m1.status IN ('live','closed')  AND char_length(m1.msgTxt)>=40 GROUP BY m1.parentId )temp1 WHERE temp1.total >2 ) UNION
		      (SELECT mt.msgId msgId from messageTable mt WHERE mt.viewCount>20 and mt.msgCount >= 2 and mt.status IN ('live','closed') ) UNION
		      (SELECT temp2.parentId msgId FROM (SELECT m2.parentId,SUM(digUp) likes, SUM(digDown) dislikes from messageTable m2 WHERE m2.mainAnswerId = 0 and m2.parentId !=0 and m2.status IN ('live','closed') GROUP BY parentId) temp2 WHERE (temp2.likes-temp2.dislikes)>2) UNION
		      (SELECT m1.threadId msgId from messageTable m1 INNER JOIN tuser lm ON (m1.userId = lm.userid)  WHERE m1.mainAnswerId = 0 AND  m1.listingTypeId!=0 AND lm.userGroup='enterprise' AND m1.status IN ('live','closed') and m1.fromOthers='user' and lm.userid IN (select * from (select username from listings_main lm, messageTable m1 where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1 ) as temp3 ))";
        $sResult = $dbHandle->query($sQuery);

        //Now, for each question, check if it is present in the Master list. If yes, set its modified time to NOW, else insert it in the Table
        foreach($sResult->result_array() as $row) {
            $val = $row['msgId'];
            $insertQuery = "INSERT INTO qnaMasterQuestionTable (msgId,modifiedDate,status) values (?,now(),'live') ON DUPLICATE KEY update modifiedDate = now()";
            $iResult = $dbHandle->query($insertQuery, array($val));
        }

        //After this, set all the questions whose modified time is before today to Deleted.
        $mQuery = "Update qnaMasterQuestionTable set status = 'deleted' where modifiedDate < DATE_SUB(NOW(),INTERVAL 1 DAY)";
        $mResult = $dbHandle->query($mQuery);

        return 1;

    }

	/* updateEditorialBin web service add or remove the question from editorial bin
	*/

    function updateEditorialBin($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $productId=$parameters['1'];
        $productType=$parameters['2'];
        $userId=$parameters['3'];
        $action=$parameters['4'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "select * from tuser where userId = ?";
        $result = $dbHandle->query($queryCmd,array($userId));
        $row = $result->row();
        $usergroup = $row->usergroup;

        //This is applied for the cms user.
        if($usergroup !== 'cms') {
            $response = array(array('Result' => array('Action not permitted','string')),'struct');
            return $this->xmlrpc->send_response($response);
        }
        if($action === 'add') {
            $queryCmd = "insert into editorPick values(?,?,?,now(),'live') on duplicate key update status = 'live' ";
            $result = $dbHandle->query($queryCmd,array($productId,$productType,$userId));
            $response = array(array('Result' => array('added','string')),'struct');
        }else if($action === 'delete') {
                $queryCmd = "update editorPick set status = 'deleted' where ProductId = ? and ProductType = ?";
                $result = $dbHandle->query($queryCmd,array($productId,$productType));
                $response = array(array('Result' => array('deleted','string')),'struct');
            }
        return $this->xmlrpc->send_response($response);
    }
     /*   function getUserIdAboveAdvisor($request){
                 $parameters = $request->output_parameters();
            $appID=$parameters['0'];
            $dbConfig = array( 'hostname'=>'localhost');
	    $this->messageboardconfig->getDbConfig($appID,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);

        } */

        function tenDaysInactiveDiscussionAnnoucement($request){

            $parameters = $request->output_parameters();
            $appID=$parameters['0'];
	    $dbHandle = $this->_loadDatabaseHandle();
            $resArray = array();
            $date = date("Y-m-d");
            $date = strtotime("-10 days",strtotime($date));
            $date = date ( 'Y-m-j' , $date );

            $queryCmd = "select distinct(mt1.userId) as userId,mt1.threadId as threadId,mt1.msgId as msgId from messageTable as mt1 join messageDiscussion as md on (md.threadId=mt1.msgId) where mt1.status IN ('live','closed') and mt1.fromOthers in ('discussion','announcement') and mt1.mainAnswerId=0  and mt1.creationDate < ? order by creationDate desc";
            $query = $dbHandle->query($queryCmd,array($date));
            foreach($query->result_array() as $row){
                $queryCmd = "select mt1.userId as userId from messageTable as mt1 where mt1.userId!='' and mt1.threadId=$row[threadId] and mt1.status IN ('live','closed') and mt1.fromOthers in ('discussion','announcement') and mt1.mainAnswerId not in ('0','-1') and mt1.creationDate < ? group by threadId,userId  order by creationDate desc";
                $query = $dbHandle->query($queryCmd,array($date));
                //$res = $query->row();
                $numRow = $query->num_rows();

                if($numRow==1){
                    $queryCmd = "select mt1.userId as userId ,mt1.msgId as msgId  from messageTable as mt1 where mt1.userId!='' and mt1.threadId=$row[threadId] and mt1.status IN ('live','closed') and mt1.fromOthers in ('discussion','announcement') and mt1.mainAnswerId not in ('0','-1') and mt1.creationDate < ? group by threadId,userId having count(mt1.threadId)=1 order by creationDate desc";
                    $query = $dbHandle->query($queryCmd,array($date));
                    $res = $query->row();
                    if($res->userId==$row[userId]){
                        $resArray[]=$row[msgId];
                        $this->deleteTenDaysDiscussionAndAnnouncement($row[threadId]);
                    }

                }elseif($numRow==0){
                        $resArray[]=$row[msgId];
                        $this->deleteTenDaysDiscussionAndAnnouncement($row[threadId]);
                }


            }

          /*  $queryCmd = "select distinct(mt1.userId) as userId,mt1.threadId as threadId,mt1.msgId as msgId from messageTable as mt1 where mt1.status IN ('live','closed') and mt1.fromOthers in ('discussion','announcement') and mt1.mainAnswerId=0  and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`creationDate`)<864000) order by creationDate desc";
            $query = $dbHandle->query($queryCmd);

            foreach ($query->result_array() as $row){
                $queryCmd1 = "select distinct(mt1.msgId) as msgId from messageTable as mt1 where  mt1.threadId=$row[threadId] and mt1.status IN ('live','closed') and mt1.fromOthers in ('discussion','announcement') and mt1.mainAnswerId not in ('0','-1') and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`creationDate`)<864000) group by threadId,userId having count(mt1.threadId)>0 order by creationDate desc";
                $query1 = $dbHandle->query($queryCmd1);
                $numRow1 = $query1->num_rows();


                $queryCmd = "select mt1.msgId as msgId from messageTable as mt1 where mt1.userId=$row[userId] and mt1.threadId=$row[threadId] and mt1.status IN ('live','closed') and mt1.fromOthers in ('discussion','announcement') and mt1.mainAnswerId not in ('0','-1') and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`creationDate`)<864000) group by threadId having count(mt1.threadId)>0 order by creationDate desc";
                $query = $dbHandle->query($queryCmd);
                $res = $query->row();
                $numRow = $query->num_rows();

                $queryCmd2 = "select mt1.msgId as msgId from messageTable as mt1 where mt1.userId!=$row[userId] and mt1.threadId=$row[threadId] and mt1.status IN ('live','closed') and mt1.fromOthers in ('discussion','announcement') and mt1.mainAnswerId not in ('0','-1') and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`creationDate`)<864000) group by threadId having count(mt1.threadId)>0 order by creationDate desc";
                $query2 = $dbHandle->query($queryCmd2);
                $res2 = $query2->row();
                $numRow2 = $query2->num_rows();

                if($numRow && $numRow1>=1){
                     $resArray[]=$res->msgId;
                     $this->deleteTenDaysDiscussionAndAnnouncement($res->msgId);
                }elseif($numRow==0 && $numRow2==0){
                    $resArray[]=$row[msgId];
                    $this->deleteTenDaysDiscussionAndAnnouncement($row[msgId]);

                }

            }
            */
        $response = json_encode(array($resArray,'array'));
        return $this->xmlrpc->send_response($response);

    }

    function deleteTenDaysDiscussionAndAnnouncement($threadId) {
        $appID =1;
	$dbHandle = $this->_loadDatabaseHandle('write');
        //soft delete from messageboard
        $queryCmd="update messageTable set status='deleted' where threadId= ? and mainAnswerId IN ('0','-1')";
        $query = $dbHandle->query($queryCmd,array($threadId));

        $queryToGetUserId = "select userId from messageTable where msgId = ?";
        $Result = $dbHandle->query($queryToGetUserId,array($threadId));
        $row = $Result->row();
        $userId = $row->userId;

        // update User point system
        $this->load->model('UserPointSystemModel');
        $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'deleteAnnouncement/deleteDiscussion');

	//Added by Ankur on 30 Nov to delete sticky discussion in case it is deleted
	$this->deleteStickyDiscussion($threadId);

        $queryCmd="update messageExpertTable set status='deleted' where threadId = ?";
        $query = $dbHandle->query($queryCmd,array($threadId));
    }


    function tenDaysInactivityMailer($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
	$dbHandle = $this->_loadDatabaseHandle();
        $resArray = array();
		//Get the list of users who are having Advisor or Above level
        $queryCmd = "SELECT tu.userid FROM `tuser` as tu  join `userpointsystembymodule` as upsm on (tu.userid = upsm.userId and upsm.levelId>10 and upsm.modulename='AnA') order by tu.userid asc";
        $query = $dbHandle->query($queryCmd);

        foreach ($query->result_array() as $row) {
			$userId = $row['userid'];
			$resArray['tendayswithoutcheck'][] = $row['userid'];

			//Now, we will send mailers to all the users with Advisor or above level
			//Check if this user is an Expert
			//$queryE = "select * from expertOnboardTable where userId = '".$row['userid']."' and status = 'Live'";
	        //$queryR = $dbHandle->query($queryE);
            //$countE = $queryR->num_rows();
			//if($countE>0)
					$resArray['isAnAExpert'][$userId] = 'true';

            //$queryData = "select userId from userpointsystemlog where userId='".$row['userid']."' and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`timestamp`)<864000) and action in ('choosenbestAnswer','receiveThumpDownAnswer','receiveThumpUpAnswer','republishAnswer','answerabusefromfront') and action not in('selectBestAnswer','reportAbuseAnA','rateThumpDown','rateThumpUp','ansQuestion','firstAnswer','askQuestion')";

			//Find the last activity done by this user and the time of that activity
            $queryData = "select DATE(timestamp) as timestamp, DATE(now()) as timestampNow from userpointsystemlog where userId=? and action in('selectBestAnswer','reportAbuseAnA','rateThumpDown','rateThumpUp','ansQuestion','firstAnswer','askQuestion') order by timestamp desc limit 1";
            $query1 = $dbHandle->query($queryData, array($row['userid']));
	        $activityR = $query1->row();
            $timeStamp=$activityR->timestamp;

            //Check if the timestamp is less than 20th June, set it to 20th June.
            $checkDate = strtotime('2012-06-20');
            $lastDate = strtotime($timeStamp);
            $diff = $checkDate-$lastDate;
            if($diff>0) 
		$timeStamp = '2012-06-20 00:00:00';
            
            $timeStampNow=$activityR->timestampNow;

			//Based on the last activity timestamp of this user, we will decide the Reputation index decay for this user
			//Find the difference between the current date and user last activity time
			$noOfDays = (strtotime($timeStampNow) - strtotime($timeStamp)) / (60 * 60 * 24);
			$percentRIDecay = $this->calculateRIDecay($noOfDays);
			if($percentRIDecay>0){
                $resArray['tendayscheck'][] = $row['userid'];
				$resArray['RIDecay'][$userId] = $percentRIDecay;
            }
        }

        $response = json_encode(array($resArray,'array'));
        return $this->xmlrpc->send_response($response);
    }

	function calculateRIDecay($noOfDays){
		if($noOfDays<=10) return '0';
		else if($noOfDays>10 && $noOfDays<=20) return '2.5';
		else if($noOfDays>20 && $noOfDays<=30) return '3.0';
		else if($noOfDays>30 && $noOfDays<=40) return '3.5';
		else if($noOfDays>40 && $noOfDays<=50) return '4.0';
		else if($noOfDays>50 && $noOfDays<=60) return '4.5';
		else if($noOfDays>60 && $noOfDays<=70) return '5.0';
		else if($noOfDays>70 && $noOfDays<=80) return '5.5';
		else if($noOfDays>80 && $noOfDays<=90) return '6.0';
		else if($noOfDays>90 && $noOfDays<=100) return '6.5';
		else if($noOfDays>100 && $noOfDays<=110) return '7.0';
		else if($noOfDays>110 && $noOfDays<=120) return '7.5';
		else if($noOfDays>120 && $noOfDays<=130) return '7.0';
		else if($noOfDays>130 && $noOfDays<=140) return '6.5';
		else if($noOfDays>140 && $noOfDays<=150) return '6.0';
		else if($noOfDays>150 && $noOfDays<=160) return '5.5';
		else if($noOfDays>160 && $noOfDays<=170) return '5.0';
		else if($noOfDays>170 && $noOfDays<=180) return '4.5';
		else if($noOfDays>180 && $noOfDays<=190) return '4.0';
		else if($noOfDays>190 && $noOfDays<=200) return '3.5';
		else if($noOfDays>200) return '3.0';
	}

    function getNumberOfThumbsUpDownBestFollowerUser($userID) {
        $appID =1;
	$dbHandle = $this->_loadDatabaseHandle();

        $queryreceiveThumpDownAnswer = "select userId from userpointsystemlog where userId= ? and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`timestamp`)<864000) and action='receiveThumpDownAnswer'";
        $query1 = $dbHandle->query($queryreceiveThumpDownAnswer,array($userID));
        $exereceiveThumpDownAnswer = $query1->num_rows();
        $queryreceiveThumpUpAnswer = "select userId from userpointsystemlog where userId = ? and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`timestamp`)<864000) and action='receiveThumpUpAnswer'";
        $query2 = $dbHandle->query($queryreceiveThumpUpAnswer,array($userID));
        $exereceiveThumpUpAnswer = $query2->num_rows();
        $querychoosenbestAnswer = "select userId from userpointsystemlog where userId = ? and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`timestamp`)<864000) and action='choosenbestAnswer'";
        $query3 = $dbHandle->query($querychoosenbestAnswer,array($userID));
        $exechoosenbestAnswer = $query3->num_rows();
        $res = array();
        if($exereceiveThumpDownAnswer) {
            $res['receiveThumpDownAnswer'] = $exereceiveThumpDownAnswer;
        }else {
            $res['receiveThumpDownAnswer'] ='0';
        }
        if( $exereceiveThumpUpAnswer) {
            $res['receiveThumpUpAnswer']   = $exereceiveThumpUpAnswer;
        }else {
            $res['receiveThumpUpAnswer'] ='0';
        }
        if($exechoosenbestAnswer) {
            $res['choosenbestAnswer']      = $exechoosenbestAnswer;
        }else {
            $res['choosenbestAnswer']='0';
        }
        return $res;

    }

    function getMailFollowUSer($userId) {
	$dbHandle = $this->_loadDatabaseHandle();
        // $queryCmd = "SELECT ID from followUser where followedUserId = ?";
        $queryCmd = "SELECT id from tuserFollowTable where entityId = ? and status = 'follow'";
        $query = $dbHandle->query($queryCmd,array($userId));
        $execFollowUserCount = $query->num_rows();

        // $queryCmdTime = "SELECT ID from followUser where followedUserId = ? and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`followUserTime`)<864000)";
        $queryCmdTime = "SELECT id from tuserFollowTable where entityId = ? and status = 'follow' and (UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(`modificationTime`)<864000)";
        $queryTime = $dbHandle->query($queryCmdTime,array($userId));
        $execFollowUserCountTime = $queryTime->num_rows();
        $res = array();
        $res['number']= $execFollowUserCount;
        $res['numberWithTime']= $execFollowUserCountTime;
        return $res;
    }

    function getInactiveInterval($userID) {
        $appID =1;
	$dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "select timestamp from userpointsystemlog where userId = ? and action in('selectBestAnswer','reportAbuseAnA','rateThumpDown','rateThumpUp','ansQuestion','firstAnswer','askQuestion') order by timestamp desc limit 0,1";
        $query = $dbHandle->query($queryCmd,array($userID));
        $rowNo = $query->num_rows();
        $expdate = $query->row();
        if($rowNo) {
            $temp1=explode(" ",$expdate->timestamp);
            $temp=explode("-",$temp1[0]);
            $year = $temp[0];
            $month= $temp[1];
            $day = $temp[2];
            $temp2=explode(":",$temp1[1]);
            $hour = $temp2[0];
            $minute = $temp2[1];
            $second = $temp2[2];
            $countdown_date = mktime($hour, $minute, $second, $month, $day, $year);
            $today = time();
            $diff = $countdown_date - $today;
            $dl = floor($diff/60/60/24);
        }//else{
        // $dl = '0';
        // }
        return $dl;

    }

    function getDataUser($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        if($parameters['2']) {
            $userID=$parameters['2'];
        }
        $alluserID=$parameters['1'];
		$percentage=$parameters['3'];
	$dbHandle = $this->_loadDatabaseHandle();
        $days = $this->getInactiveInterval($alluserID);
        $this->load->model('UserPointSystemModel');
        if($userID) {
            $action1= $this->UserPointSystemModel->tuserReputationPointEntry($userID, 1,'tenDaysInactive',$dbHandle,$percentage);
        }
        $userQuestion = array();
        $userQuestion = $this->UserPointSystemModel->getCategoryBasedQuestion($alluserID,$dbHandle);
        $userReputation = $this->UserPointSystemModel->getUserReputationPoints($dbHandle,$alluserID);

        $queryCmd = "SELECT tu.email,tu.displayname,upsm.userpointvaluebymodule, upsm.levelName as designation FROM `tuser` as tu  join `userpointsystembymodule` as upsm on (tu.userid = upsm.userId) where tu.userid = ? and upsm.modulename='AnA'";
        // $queryCmd = "SELECT tu.email,tu.displayname,upsm.userpointvaluebymodule FROM `tuser` as tu  join `userpointsystembymodule` as upsm on (tu.userid = upsm.userId) where  upsm.modulename='AnA'";
        $query = $dbHandle->query($queryCmd,array($alluserID));
        $resArray = array();
        $resArray = $query->result_array();

        $points = $this->getNumberOfThumbsUpDownBestFollowerUser($alluserID);
        $resArray[0]['Points'] = $points;
        $followUser = $this->getMailFollowUSer($alluserID);
        $resArray[0]['followUser'] = $followUser;
        $resArray[0]['userQuestion'] = $userQuestion;
        $resArray[0]['days'] = $days;
        $resArray[0]['rnr']  = $userReputation;
        $response = json_encode(array($resArray,'array'));
        return $this->xmlrpc->send_response($response);

    }
    /**
     * getQnAForEditorialBin gives the data for the question in the editorial bin.
     */
    function getQnAForEditorialBin($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $countryId=$parameters['4'];
        $userId=$parameters['5'];
        $userId=($userId!='')?$userId:0;

        $selectForCategoryAndCountry = "";
        $fromForCategoryAndCountry = "";
        $conditionForCategoryAndCountry = "";
        if($categoryId != 1) {
            $selectForCategoryAndCountry = ", m2.*";
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and m2.categoryId in (".$categoryId.") and ";
        }
        if($countryId != 1) {
            $selectForCategoryAndCountry .= ", (select group_concat(countryTable.name) from countryTable where countryTable.countryId=m3.countryId) as countryName";
            $fromForCategoryAndCountry .= ", messageCountryTable m3";
            $conditionForCategoryAndCountry .= " m1.threadId = m3.threadId and m3.countryId in (".$countryId.") and ";
        }

        // 0 flagForAnswer is included in query becuase cmsuser can not aswer the question.

        //$vcardStatusQuery = " ,ifnull((select 1 from VCardInfo vci where vci.userId = t1.userid),0) vcardStatus";
		$vcardStatusQuery = " ,0 vcardStatus";
        $queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery, m1.msgCount answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level ,0 flagForAnswer,1 editorPickFlag from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA'),editorPick edp where $conditionForCategoryAndCountry  m1.userId=t1.userId and m1.msgId = edp.ProductId and edp.status = 'live' and m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 order by m1.creationDate desc LIMIT $startFrom,$count";
        error_log_shiksha('getQnAForEditorialBin query cmd is '.$queryCmd,'qna');

        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        $threadIdCsv = '';
        foreach ($query->result_array() as $row) {
            if($this->seo_update($row['creationDate'])) {
                $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
            }
            else {
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
            }
            $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];
            array_push($msgArray,array($row,'struct'));
        }

        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $dbHandle->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
            $totalRows = $row->totalRows;
        }
        $this->load->model('QnAModel');
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdCsv);
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalCount'=>array($totalRows,'string'),
            'categoryCountry'=>array($msgArrayCatCountry,'struct'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * getDataForRelatedQuestions function for getting the information for threadIds.
     */
    function getDataForRelatedQuestions($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadIdCsv=$parameters['1'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $this->load->model('QnAModel');
        $msgArray = $this->QnAModel->getPopularAnswersForQuestions($dbHandle,$threadIdCsv,true,true);

        $response = array(
            array('Results'=>array($msgArray,'struct')
            ),'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * getInfoForThreads function for getting the information for threadIds.
     */
    function getInfoForThreads($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadIdCsv=$parameters['1'];
        $fieldsRequired=$parameters['2'];
        $userId=$parameters['3']; // send only if the user specific data is required.
        $userId=($userId != "")?$userId:0;
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $selectFields = "";
        if(array_key_exists('answerCount',$fieldsRequired) && ($fieldsRequired['answerCount'] == 1)) {
            $selectFields = " , (select count(*) from messageTable M1 where M1.threadId=m1.threadId and M1.fromOthers = 'user' and M1.parentId = M1.threadId and M1.status not in ('deleted','abused')) answerCount";
        }
        if(array_key_exists('flagForAnswer',$fieldsRequired) && ($fieldsRequired['flagForAnswer'] == 1)) {
            $selectFields .= " , ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.parentId = M1.threadId and M1.userId = ".$userId." and  m1.userId != ".$userId." and M1.status in ('live','closed') and M1.fromOthers = 'user'),0) flagForAnswer";
        }
        $queryCmd = "select m1.* $selectFields , t1.userid, t1.displayName from messageTable m1,tuser t1 where  m1.userId = t1.userid and m1.threadId in (?) and m1.status in ('live','closed') group by threadId";
        error_log_shiksha('getInfoForThreads get threadId for best answer query cmd is :: '.$queryCmd,'qna');
        $threadIdCsvArray = explode(',', $threadIdCsv);
        $result = $dbHandle->query($queryCmd,array($threadIdCsvArray));
        $msgArray = array();
        $rowArray = array();
        foreach ($result->result_array() as $row) {
            $msgArray[$row['threadId']] = array($row,'struct');
        }
        if(array_key_exists('getPopularAnswers',$fieldsRequired) && ($fieldsRequired['getPopularAnswers'] == 1)) {
            $this->load->model('QnAModel');
            $popularAnswers = $this->QnAModel->getPopularAnswersForQuestions($dbHandle,$threadIdCsv);
        }
        $response = array(
            array(
            'Results'=>array($msgArray,'struct'),
            'popularAnswers'=>array($popularAnswers,'struct')
            ),'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * closeQestionCron function close the functions for which the best answer is selected but their status is live.
     */
   /*
    function closeQestionCron($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = "select group_concat(m1.msgId) as threadIds from messageTable m1 , messageTableBestAnsMap m2 where m1.threadId = m2.threadId and m2.creation_time < DATE_SUB(NOW(),INTERVAL 3 DAY) and m1.parentId = 0 and m1.status in ('live')";
		error_log_shiksha('closeQestionCron get threadId for best answer query cmd is :: '.$queryCmd,'qna');
		$result = $dbHandle->query($queryCmd);
		$threadIds = '';
		foreach ($result->result_array() as $row){
			$threadIds = $row['threadIds'];
		}
		error_log_shiksha('closeQestionCron threadId :: '.$threadIds,'qna');
		$numRowsAffected = 0;
		if($threadIds != ''){
			$threadIds = trim($threadIds,",");
			$queryCmd = "update messageTable set status = 'closed' where msgId in (".$threadIds.")";
			error_log_shiksha('closeQestionCron get threadId for best answer query cmd is :: '.$queryCmd,'qna');
			$result = $dbHandle->query($queryCmd);
			$numRowsAffected = $dbHandle->affected_rows();
		}

        $response = array(
            array('toClose'=>array($threadIds),
            'numberUpdated'=>array($numRowsAffected)
            ),'struct');
        return $this->xmlrpc->send_response($response);
    }

   */
    /**
     * This method is absolete now.No more in used.
     */
    function getQuestionFromQuestionCategories($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
        $catIds=$parameters['2'];
        $start=$parameters['3'];
        $count=$parameters['4'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        if($msgId != -1) {
            $queryCmd = "select m1.*,m2.CategoryId,t1.displayName from messageTable m1, tuser t1, messageCategoryTable m2  where m2.CategoryId in (select group_concat(CategoryId) from messageCategoryTable M1 where M1.threadId = ? and M1.CategoryId <> 1) and m1.threadId = m2.threadId and m1.parentId = 0 and m1.userId = t1.userId and m1.fromOthers='user' and m1.status in ('live','closed') and m1.msgId <> ? group by m1.msgId limit $start,$count";
            error_log_shiksha('for msgId getQuestionFromQuestionCategories query cmd is '.$queryCmd,'qna');
            $result = $dbHandle->query($queryCmd,array($msgId,$msgId));
        }elseif($catIds != -1) {
            $queryCmd = "select m1.*,t1.displayName,t1.userid from messageTable m1,messageCategoryTable m2,tuser t1 where fromOthers='user' and status in ('live','closed') and m2.CategoryId in (?) and m1.threadId = m2.threadId and m1.parentId = 0 and m1.userId = t1.userId and m1.userId=t1.userId and m1.msgId <> ? group by m1.msgId order by msgCount asc ,creationDate desc limit ".$start.",".$count;
            error_log_shiksha('for categories getQuestionFromQuestionCategories query cmd is '.$queryCmd,'qna');
            $catIdsArray = explode(',', $catIds);
            $result = $dbHandle->query($queryCmd, array($catIdsArray,$msgId));
        }
        $msgArray = array();
        foreach ($result->result_array() as $row) {
            $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * Update the digup/digdoen value of answer/comment.
     * Takes userId threadId msgId digVal as parameter
     * Once the user has done the digup/digdown we wont allow him to change it.
     * User can do only one thing (either digup or digdown on particular comment or answer)
     */
    function updateDigVal($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $msgId=$parameters['2'];
        $digVal=$parameters['3'];
        $product=$parameters['4'];
        $fromWhere = $parameters['5'];
        //below line ois used for to store tracking page key id into the database
        $tracking_keyid=$parameters['6'];
        $isLoginFlow = $parameters['7'];
        $sessionidTracking=getVisitorSessionId();
        if($isLoginFlow == 'true'){
        	$isLoginFlow = TRUE;
        }else{
        	$isLoginFlow = FALSE;
        }

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
                 /***********************************************************************************************/
        ////QnA Rehash Phase-2 Start to stop thumb up and thumb down having RI less than and equal to zero
                /************************************************************************************************/
        $points = $this->getReputationPoint($userId);
        $errorString = -1;
        if($points<= 0 && $points!='9999999') {
            $errorString = 'NOREP'; // if zero reputation point then no aanouncement .
        }
        if($errorString != -1) {
            $response = array(
                array('Result'=>array($errorString)
                ),'struct');
            return $this->xmlrpc->send_response($response);
        }
                 /***********************************************************************************************/
        ////QnA Rehash Phase-2 End to stop thumb up and thumb down having RI less than and equal to zero
                /***********************************************************************************************/
        $queryToExe = "select userId,fromOthers,mainAnswerId,threadId from messageTable where msgId = ?";
        $Result = $dbHandle->query($queryToExe,array($msgId));
        $row = $Result->row();
        $commentUerId = $row->userId;
        $mainAnswerId = $row->mainAnswerId;
        $threadId = $row->threadId;
	$entityTypeRated = $row->fromOthers;
        if($commentUerId == $userId) {
            $response = array(
                array('Result'=>array('SUCE','string') //Same user comment error.
                ),'struct');
            return $this->xmlrpc->send_response($response);
        }

       $qNaModel = $this->load->model('QnAModel');
       $entityStatus = $qNaModel->deleteDigVal($appID,$userId,$msgId,$digVal,$isLoginFlow);
       if($entityStatus['Result'] == 'success' ){
        $response = array(array('Result'=>array('change')),'struct');
        return $this->xmlrpc->send_response($response);
       
       }
       else if($entityStatus['Result'] == 'na' ){
        $response = array(array('Result'=>array('na')),'struct');
        return $this->xmlrpc->send_response($response);
    }

        //Check if user has already given the rating. Only if he has, we will revert it back.
     /*   $queryToExe = "SELECT userId FROM digUpUserMap WHERE userId = ? AND productId = ? AND product = 'qna' AND digUpStatus = 'live'";
        $Result = $dbHandle->query($queryToExe,array($userId,$msgId));
        $rows = $Result->num_rows();
        if($rows > 0) {
	        $response = array(array('Result'=>array('na')),'struct');
                return $this->xmlrpc->send_response($response);
        }
*/
        $queryCmd = "insert into digUpUserMap(userId,productId,digFlag,product,RatingVal,digTime,tracking_keyid,visitorsessionid) values(?,?,?,?,0,now(),?,?)";
        error_log_shiksha( 'updateDigVal digUpUserMap insert query cmd is '.$queryCmd,'qna');
        $result = $dbHandle->query($queryCmd,array($userId,$msgId,$digVal,$product,$tracking_keyid,$sessionidTracking, $msgId));
        $numRowsAffected = $dbHandle->affected_rows();
        $flag='na';
        if($numRowsAffected == 1) {
            if($digVal == 0) {
                $queryCmd = "update messageTable set digDown = (select count(*) from  digUpUserMap where productId = ? and product = 'qna' and digFlag = 0 and digUpStatus = 'live') where msgId = ?";
            }else {
                $queryCmd = "update messageTable set digUp = (select count(*) from  digUpUserMap where productId = ? and product = 'qna' and digFlag = 1 and digUpStatus = 'live') where msgId = ?";
            }
            error_log_shiksha( 'updateDigVal messageTable update query cmd is '.$queryCmd,'qna');
            $result = $dbHandle->query($queryCmd,array($msgId,$msgId));
            $numRowsAffected = $dbHandle->affected_rows();
            if($numRowsAffected > 0) {
                $this->load->model('UserPointSystemModel');
                //pranjul start
                if($digVal == 0) {
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rateThumpDown',$msgId,$threadId);
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUerId,'receiveThumpDownAnswer',$msgId,$threadId);
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rateThumpDown',$msgId);
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUerId,'receiveThumpDownAnswer',$msgId);
                 $entityType = '';
            if($entityTypeRated == "user"){
                        $entityType = 'question';
                        //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUerId,'receiveThumpUpAnswer',$msgId);
                        $this->UserPointSystemModel->updateUserReputationPoint($userId,'receiveThumpDownAnswer',$msgId);

            }
            else{
                      $entityType = 'discussion';
                        //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUerId,'receiveThumpUpComment',$msgId);
                        $this->UserPointSystemModel->updateUserReputationPoint($userId,'receiveThumpDownComment',$msgId);
            }
                }else {
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rateThumpUp',$msgId,$threadId);
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUerId,'receiveThumpUpAnswer',$msgId,$threadId);
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'thumbUpBonus',$msgId);

		    //Check if this is a Rating on Answer or Discussion Comment
                    $entityType = '';
		    if($entityTypeRated == "user"){
                        $entityType = 'question';
                        $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUerId,'receiveThumpUpAnswer',$msgId);
                        $this->UserPointSystemModel->updateUserReputationPoint($userId,'receiveThumpUpAnswer',$msgId);

            }
            else{
                      $entityType = 'discussion';
                        $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUerId,'receiveThumpUpComment',$msgId);
                        $this->UserPointSystemModel->updateUserReputationPoint($userId,'receiveThumpUpComment',$msgId);
            }
                    //pranjul end

                    //Also, send mail to the owner of the answer who has received the Thumb up
		    //Send this Mailer only if the Rating is not from Mobile App
	            global $isMobileApp;
                global $isWebAPICall;

                // get the thread owner-id in case of mobile app hit only(for MAB-1492)
                $threadOwnerId = 0;
                if($isMobileApp && !$isWebAPICall){
                    $queryCmd = "SELECT userId FROM messageTable WHERE msgId = ?";
                    $query = $dbHandle->query($queryCmd,array($threadId));
                    $row = $query->row();
                    $threadOwnerId = $row->userId;
                }

                    $queryCmd = "SELECT t1.displayname,t1.firstname,t1.lastname,t1.email,m1.threadId,m1.msgTxt,m1.mainAnswerId,t1.userid,(select displayname from tuser, messageTable m2 where tuser.userid=m2.userId and m2.msgId = (select threadId from messageTable where msgId=?)) quesDisplayname,(select displayname from tuser where userid=?) raterDisplayname,(select firstname from tuser where userid=?) raterFirstName from tuser t1, messageTable m1 where m1.userId=t1.userid and m1.msgId=? and m1.status IN ('live','closed')";
                    $query = $dbHandle->query($queryCmd,array($msgId,$userId,$userId,$msgId));
                    $row = $query->row();

		    //Check if the Answer owner is active on App. We will send mailer only if he is not active.
		    $commonAPILib = $this->load->library('common_api/APICommonLib');
   	 	    if(! $commonAPILib->isUserActiveOnApp($row->userid)){
		    
                    $threadId = $row->threadId;
                    $mainAnswerId = $row->mainAnswerId;
                    $email = $row->email;
                    $fromMail = "noreply@shiksha.com";
                    $ccmail = "";
                    $raterName = ($row->raterFirstName=='')?$row->raterDisplayname:$row->raterFirstName;
                    $subject = $raterName." gave a thumbs-up to your answer.";
                    $this->load->library('mailerClient');
                    $MailerClient = new MailerClient();
                    $mail_client = new Alerts_client();
                    $urlOfLandingPage = SHIKSHA_ASK_HOME;
                    $contentArr['username'] = ($row->firstname=='')?$row->displayname:$row->firstname;
                    $contentArr['questionUsername'] = $row->quesDisplayname;
                    $contentArr['raterDisplayName'] = $raterName;
                    $contentArr['receiverId'] = $row->userid;
                    $contentArr['link'] = SHIKSHA_ASK_HOME;
                    $contentArr['linkDetailPage'] = SHIKSHA_ASK_HOME.'/getTopicDetail/'.$row->threadId;
                    

                    if($fromWhere == "discussion"){
                        $contentArr['linkDetailPage'] = SHIKSHA_ASK_HOME.'/-dscns-'.$row->threadId;
                    }
                    
                    $contentArr['answer'] = strlen($row->msgTxt)>300?substr($row->msgTxt,0,297).'...'.'<a href="'.$contentArr['linkDetailPage'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$row->msgTxt;
                    $contentArr['type'] = ($fromWhere == 'discussion') ? 'discussionThumbMail' :'thumbUpMail';
                    
                    if($fromWhere == 'discussion'){
                       $contentArr['discussionTopic'] =  $this->_getDiscussionTopic($threadId); 
                    }
                    //$content = $this->load->view("search/searchMail",$contentArr,true);
                    //$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
                    Modules::run('systemMailer/SystemMailer/cafeThumbUpToAnswer', $email, $contentArr);
                    
                    //End Code for send mail on thumb up
		    }
		
                    
                    //Add notification of APP in redis
                    $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
                    if($entityType == 'question'){
                          $this->appNotification->addNotificationForAppInRedis('upvote_answer',$threadId,'question',$userId,'answer',$msgId);  
                    }else{
                          $this->appNotification->addNotificationForAppInRedis('upvote_on_comment',$threadId,'discussion',$userId,'comment',$msgId);
                    }
		    
                }
                
                //Add the entry in Redis for Personalized HomePage
                
                    if($mainAnswerId == 0){
                        $entityType= 'question';
                    }else{
                        $entityType = 'discussion';
                    }
                    
                    $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
                    if($digVal == 1){
                        $this->userinteractioncachestoragelibrary->storeUserActionUpvote($userId, $threadId, $entityType,$msgId);
                    }else{
                        $this->userinteractioncachestoragelibrary->sotreUserActionDownvote($userId, $threadId, $entityType,$msgId);
                    }
                    
                                        
                $flag='success';
            }else {
                $flag='failed';
            }
        }

        $response = array(
            array('Result'=>array($flag), 'threadOwnerId' => $threadOwnerId
            ),'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * Set the Answer chosen by user as the best answer.
     * Takes userId threadId msgId commentUerId and close flag as parameter
     * Once the user has chosen the best answer depending upon his selection the question is closed or kept live for 3 days.
     * This maintains the mapping of question id and best answer id in messageTableBestAnsMap.
     * Also the user whose answer is chosen as the best answer is given the 50 points.
     */
    /*
    function setBestAnsForThread($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $threadId=$parameters['2'];
        $msgId=$parameters['3'];
        $commentUserId=$parameters['4'];
        $doClose=$parameters['5'];
        error_log_shiksha("setBestAnsForThread :: ".$userId."   ".$threadId."   ".$msgId."    ".$commentUserId."    ".$doClose);
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "select count(msgId) chkUser,(select msgTxt from messageTable where msgId = ?) msgTxt  from messageTable where userId = ? and msgId = ? and ((select count(msgId) from messageTable where msgId = ? and parentId = ?) > 0) ";
        error_log_shiksha('setBestAnsForThread select queryCmd :: '.$queryCmd,'qna');
        $result = $dbHandle->query($queryCmd,array($threadId,$userId,$threadId,$msgId,$threadId));
        $seoUrl = '';
        $msgTxt = '';
        foreach ($result->result_array() as $row) {
            $chkUser = $row['chkUser'];
            $seoUrl = getSeoUrl($threadId,'question',$row['msgTxt']);
            $msgTxt = $row['msgTxt'];
        }

        $flag = 'na';
        if($chkUser > 0) {
            $isAlreadySelected = false;
            $queryCmd = "select * from messageTableBestAnsMap where threadId = ?";
            $result = $dbHandle->query($queryCmd,array($threadId));
            foreach ($result->result_array() as $row) {
                $isAlreadySelected = true;
            }
            if($isAlreadySelected) {
                $flag = 'failed';
            }
            else {
                $queryCmd = "insert into messageTableBestAnsMap (threadId,bestAnsId,creation_time) values(?,?,now()) on duplicate key update bestAnsId = ?";
                error_log_shiksha( 'setBestAnsForThread insert query cmd is '.$queryCmd,'qna');
                $result = $dbHandle->query($queryCmd,array($threadId,$msgId,$msgId));
                $numRowsAffected = $dbHandle->affected_rows();
                $doCloseFlag = 'na';
                if($numRowsAffected > 0) {
                    $this->load->model('UserPointSystemModel');
                    //pranjul start
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUserId,'choosenbestAnswer',$msgId,$commentUserId);
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'selectBestAnswer',$msgId,$commentUserId);
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$commentUserId,'choosenbestAnswer',$msgId);
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'selectBestAnswer',$msgId);
                    //pranjul end
                    if($doClose == 1) {
                        $queryCmd = 'update messageTable set status = "closed" where msgId = ?';
                        error_log_shiksha( 'update messageTable setBestAnsForThread query cmd is ' . $queryCmd,'qna');
                        $Result = $dbHandle->query($queryCmd,array($threadId));
                        $doCloseFlag = 'success';
                        if(!$Result) {
                            $doCloseFlag = 'failed';
                            error_log_shiksha( 'update messageTable setBestAnsForThread query cmd failed' . $queryCmd,'qna');
                        }
                    }
                    $flag = 'success';
                }else {
                    $flag = 'failed';
                }
            }
        }
        $response = array(
            array(
            'Result'=>array($flag,'string'),
            'closeFlag'=>array($doCloseFlag,'string'),
            'seoUrl'=>array($seoUrl,'string'),
            'msgTxt'=>array($msgTxt,'string')
            ),'struct');
        error_log_shiksha("setBestAnsForThread :: ".print_r($response,true),'qna');
        return $this->xmlrpc->send_response($response);
    }
*/
    /**
     * delete a topic from the message board
     * Takes threadId as parameter
     * If any post is posted under this thread, we do not allow the user to delete.
     * Only soft delete the thread
     */
    function deleteTopic($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadId=$parameters['1'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        //check if any topic is posted on this topic or not?

        $queryCmd="select msgCount from messageTable where threadId=?";
        error_log_shiksha('deleteTopic  query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($threadId));
        $msgArray = array();
        $count=0;
        foreach ($query->result() as $row) {
            $count=$row->msgCount;
        }
        if($count>0) {
            $response = array(array('Result'=>array('You can not delete this topic')),'struct');
            return $this->xmlrpc->send_response($response);
        }else {
        //soft delete from messageboard
            $queryCmd="update messageTable set status='deleted' where msgId=?";
            error_log_shiksha('debug', 'deleteTopic query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd,array($threadId));

            $queryToGetUserId = "select userId from messageTable where msgId = ?";
            $Result = $dbHandle->query($queryToGetUserId,array($threadId));
            $row = $Result->row();
            $userId = $row->userId;

            // update User point system
            $this->load->model('UserPointSystemModel');
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'deleteQuestion',$threadId);

	    //Added by Ankur on 30 Nov to delete sticky discussion in case it is deleted
	    $this->deleteStickyDiscussion($threadId);

            $queryCmd="update messageExpertTable set status='deleted' where threadId=?";
            error_log_shiksha('debug', 'delete messageExpertTable query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd,array($threadId));
            //Ankur: After the Question for the Listing is deleted in the DB, then also delete this in the Related questions table also
            $queryToGetListingId = "select listingTypeId from messageTable where msgId = ?";
            $ResultL = $dbHandle->query($queryToGetListingId,array($threadId));
            $rowL = $ResultL->row();
            $listingTypeId = $rowL->listingTypeId;
            if($listingTypeId>0) {
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $ListingClientObj->updateListingQuestions($appID,$listingTypeId,$threadId,'RemoveQ');
            }
            //End Modifications

            //Modified by Ankur on 8 March. If a question is deleted or abused, we need to change the count in Cache also
            $queryCmd = "select categoryId, countryId, fromOthers from messageCategoryTable mct, messageCountryTable mct1, messageTable mt where mct.threadId = ? and mct1.threadId = ? and mt.msgId = ? and categoryId > 1 and categoryId < 20 and countryId > 1";
            $query = $dbHandle->query($queryCmd,array($threadId,$threadId,$threadId));
            $row = $query->row();
            $categoryId = $row->categoryId;
            $countryId = $row->countryId;
            $fromOthers = $row->fromOthers;
            
            
        //rebuild Homepage cafe widget cache
        $this->load->model('QnAModel');
        $this->QnAModel->rebuildHomepageCafeWidgetCache($threadId);

            $response = array(
                array(
                'Result'=>array('deleted'),
                'categoryId'=>array($categoryId),
                'fromOthers'=>array($fromOthers),
                'countryId'=>array($countryId)),
                'struct');
            return $this->xmlrpc->send_response($response);
        }
    }

    /**
     * delete topic from cms
     * If any topic is deleted from CMS, we just soft delete it.
     * Takes threadID as parameter
     */
    function deleteTopicFromCMS($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadId=$parameters['1'];
        $status = $parameters['2'];

	$withoutPenalty = false;
	if($status == "abusedWithoutPenalty"){
		$status = "abused";
		$withoutPenalty = true;
	}

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryCmd="update messageTable set status= ? where msgId=?";
        error_log_shiksha( 'deleteTopicFromCMS query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($status,$threadId));
        $numRowsAffected = $dbHandle->affected_rows();

	//Added by Ankur on 30 Nov to delete sticky discussion in case it is deleted
	if($status == 'deleted' || $status == 'abused')
	    $this->deleteStickyDiscussion($threadId);

        $queryCmd="update messageExpertTable set status=? where threadId=?";
        error_log_shiksha('debug', 'delete messageExpertTable query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($status,$threadId));
        $queryToGetUserId = "select userId,fromOthers from messageTable where msgId = ?";
        $Result = $dbHandle->query($queryToGetUserId,array($threadId));
        $row = $Result->row();
        $userId = $row->userId;
        $fromOthers = $row->fromOthers;
        if(($fromOthers == 'user') && ($numRowsAffected > 0) && $status == 'deleted') {
        // update User point system
            $this->load->model('UserPointSystemModel');
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'deleteQuestion',$threadId);
        }
	else if(($fromOthers == 'user') && ($numRowsAffected > 0) && $status == 'abused'){
            $this->load->model('UserPointSystemModel');
        $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'deleteQuestion',$threadId);
        $this->UserPointSystemModel->updateUserReputationPoint($userId,'deleteQuestion',$threadId);

        if(!$withoutPenalty){
                $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'reportAbuseAccepted',$threadId);
                $this->UserPointSystemModel->updateUserReputationPoint($userId,'reportAbuseAccepted',$threadId);
        }
    }
        
        //After the Question for the Listing is deleted in the DB, then also delete this in the Related questions table also
            $queryToGetListingId = "select listingTypeId from messageTable where msgId = ?";
            $ResultL = $dbHandle->query($queryToGetListingId,array($threadId));
            $rowL = $ResultL->row();
            $listingTypeId = $rowL->listingTypeId;
            if($listingTypeId>0) {
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $ListingClientObj->updateListingQuestions($appID,$listingTypeId,$threadId,'RemoveQ');
            }
            
        $this->updatePopularityView($threadId);
        
        
        //rebuild Homepage cafe widget cache
        $this->load->model('QnAModel');
        $this->QnAModel->rebuildHomepageCafeWidgetCache($threadId);
        
        if($fromOthers == 'user'){
            $entityType = 'question';
            modules::run('search/Indexer/addToQueue', $threadId, 'question', 'delete');
            //modules::run('search/Indexer/addToQueue', $threadId, 'question');
        }
        
        //Add the entry in Redis for Personalized Homepage
        $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
        $this->userinteractioncachestoragelibrary->deleteEntity($userId,$threadId,$entityType);
        
        //Modified by Ankur on 8 March. If a question is deleted or abused, we need to change the count in Cache also
        $queryCmd = "select categoryId, countryId, fromOthers from messageCategoryTable mct, messageCountryTable mct1, messageTable mt where mct.threadId = ? and mct1.threadId = ? and mt.msgId = ? and categoryId > 1 and categoryId < 20 and countryId > 1";
        $query = $dbHandle->query($queryCmd,array($threadId,$threadId,$threadId));
        $row = $query->row();
        $categoryId = $row->categoryId;
        $countryId = $row->countryId;
        $fromOthers = $row->fromOthers;

        $response = array(
            array(
            'Result'=>array('deleted'),
            'categoryId'=>array($categoryId),
            'fromOthers'=>array($fromOthers),
            'countryId'=>array($countryId)),
            'struct');
        //End Modifications by Ankur
        return $this->xmlrpc->send_response($response);
    }

    /**
     * delete a comment from cms
     * This method is used to delete a particular comment from a CMS
     * We also penalize the user by deducting 20 points from User Point system
     * Finally the view count is updated.
     * takes msgId, threadId and userId as input
     */
    function deleteCommentFromCMS($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
        $threadId=$parameters['2'];
        $user_id=$parameters['3'];
        $status = $parameters['4'];

	$withoutPenalty = false;
        if($status == "abusedWithoutPenalty"){
                $status = "abused";
                $withoutPenalty = true;
        }

	//Add Delete Entity Log
        $userStatus = $this->checkUserValidation();
        $userIdOfDeleter = (isset($userStatus[0]['userid']))?$userStatus[0]['userid']:0;
        $currentTime = date('Y-m-d H:i:s');
        $referrer = $_SERVER["HTTP_REFERER"];
        error_log("\r\nAnswer Deleted:::: Function = message_board_server/deleteCommentFromCMS, AnswerId = $msgId, Time = $currentTime, Deleted By = $userIdOfDeleter, Referrer = $referrer", 3, "/tmp/deleteAnswer.log");


        //connect DB
	$entityType = "";
        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd="update messageTable set status=? where msgId=?";
        error_log_shiksha( 'deleteCommentFromCMS query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($status,$msgId));

        $numRowsAffected = $dbHandle->affected_rows();
        if($numRowsAffected > 0) { //For decreasing msgCount if the answer is deleted.
            $queryCmd="select threadId,parentId,fromOthers,mainAnswerId from messageTable where msgId=?";
            $Result = $dbHandle->query($queryCmd,array($msgId));
            $row = $Result->row();
            $answerParentId = $row->parentId;
            $mainAnswerId = $row->mainAnswerId;
            $fromOthers = $row->fromOthers;
            
            if($answerParentId == $threadId ||  $mainAnswerId>0) /*$mainAnswerId == $answerParentId*/
                $this->updateMsgCount($threadId,$answerParentId,-1);
            //for decreasing commentCount if the comment is deleted
            //In case of discussion, announcement or review, delete the parent entity also
            if(($answerParentId == $row->threadId) && ($row->fromOthers == 'discussion' || $row->fromOthers == 'announcement' || $row->fromOthers == 'review' || $row->fromOthers == 'eventAnA')) {
		$entityType = $row->fromOthers;
                $queryCmdDel="update messageTable set status= ? where msgId=?";
                $query = $dbHandle->query($queryCmdDel,array($status,$threadId));

		//Added by Ankur on 30 Nov to delete sticky discussion in case it is deleted
		if($status == 'deleted' || $status == 'abused')
		    $this->deleteStickyDiscussion($threadId);

            }
            
            //Add the entry in Redis for Personalized Homepage
            if($fromOthers == 'discussion' && $mainAnswerId == -1){
                $entityId = 0;
                $entityType = 'discussion';
            }else{
                $entityId = $threadId;
            }
            
            if($answerParentId == $threadId){
                $entityType = 'answer';
            }else if($mainAnswerId == $answerParentId){
                $entityType = 'comment';
            }else{
                $entityType = 'reply';
            }

	    if($fromOthers == 'user' || $fromOthers == 'question'){
		$type = 'question';
	    }
	    else if($fromOthers == 'discussion'){
		$type = 'discussion';
	    }
            
            $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
	    if($fromOthers == 'discussion' && $mainAnswerId <= 0){	//In case of Delete discussion
		$this->userinteractioncachestoragelibrary->deleteEntity($user_id,$threadId,$fromOthers);
	    }
	    else if ($fromOthers == 'discussion' && $mainAnswerId == $answerParentId){
		$this->userinteractioncachestoragelibrary->deleteEntity($user_id,$threadId,$fromOthers,'comment');
	    }
	    else if($mainAnswerId <= 0){		
            	$this->userinteractioncachestoragelibrary->deleteEntity($user_id,$threadId,'question','answer');
	    }
        }
        $queryToGetUserId = "select userId,fromOthers,mainAnswerId from messageTable where msgId = ?";
        $Result = $dbHandle->query($queryToGetUserId,array($msgId));
        $row = $Result->row();
        $fromOthers = $row->fromOthers;
        $mainAnswerIdAns = $row->mainAnswerId;
        if(($numRowsAffected > 0)) {
            $this->load->model('UserPointSystemModel');
            switch($fromOthers) {
                case 'user':
            if($status=='deleted' && $mainAnswerIdAns=='0'){    //In case of Answer delete
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'deleteAnswer',$msgId);
            }
            else if($status=='abused'){ //In case of Report abuse on Answer/Comment
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'deleteAnswer',$msgId);
            $this->UserPointSystemModel->updateUserReputationPoint($userId,'deleteAnswer',$msgId);

            if(!$withoutPenalty){
                $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'reportAbuseAccepted',$msgId);
                 $this->UserPointSystemModel->updateUserReputationPoint($userId,'reportAbuseAccepted',$msgId);
            }
            }
                    break;
                case 'event':
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'deleteEventComment');
                    break;
                case 'blog':
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'deleteArticleComment');
                    break;
                case 'discussion':
            if($status == "deleted" && $entityType=='discussion'){  //In case of Discussion delete
                $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'deleteDiscussion',$msgId);
                $this->UserPointSystemModel->updateUserReputationPoint($userId,'deleteDiscussion',$msgId);
            }
            else if($status=='abused'){ //In case of Report abuse on Discussison/Comment/Reply
                $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'deleteDiscussion',$msgId);
                if(!$withoutPenalty){
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'reportAbuseAccepted',$msgId);
                     $this->UserPointSystemModel->updateUserReputationPoint($userId,'reportAbuseAccepted',$msgId);
                }
            }
                    break;
                case 'announcement':
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'deleteAnnouncement');

                    break;
            }

            //Ankur: After the Question for the Listing is deleted in the DB, then also delete this in the Related questions table also
            $queryToGetListingId = "select listingTypeId from messageTable where msgId = ?";
            $ResultL = $dbHandle->query($queryToGetListingId,array($threadId));
            $rowL = $ResultL->row();
            $listingTypeId = $rowL->listingTypeId;
            if($listingTypeId>0) {
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $ListingClientObj->updateListingQuestions($appID,$listingTypeId,$threadId,'RemoveQ');
            }
        //End Modifications
        }

        /*For indexing purpose*/
        if($entityType == 'answer' && $fromOthers == 'discussion'){ //In case Discussion is Deleted
            modules::run('search/Indexer/addToQueue', $threadId, 'discussion', 'delete');
            //modules::run('search/Indexer/addToQueue', $threadId, 'discussion');
        } else if($fromOthers == 'discussion'){ //In case Discussion Comment/Reply is deleted
            modules::run('search/Indexer/addToQueue', $threadId, 'discussion');
        }
        else if($fromOthers == 'user'){ //In case Answer / Answer comment is deleted
            modules::run('search/Indexer/addToQueue', $threadId, 'question');
        }
        
        $response = array(
            array(
            'Result'=>array('deleted')),
            'struct');

        //update PopularityView
        if(($answerParentId == $row->threadId) && ($row->fromOthers == 'discussion' || $row->fromOthers == 'announcement' || $row->fromOthers == 'review' || $row->fromOthers == 'eventAnA'))
            $this->updatePopularityView($threadId);
            
        $this->load->model('QnAModel');
        $this->QnAModel->rebuildHomepageCafeWidgetCache($threadId);
        
        return $this->xmlrpc->send_response($response);
    }


    /**
     * cloase a  topic from the message board. It closes a thread so that no new comment can be posted.
     */
    function closeDiscussion($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadId=$parameters['1'];
        $userId=$parameters['2'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        $data =array(
            'status'=>'closed'
        );

        $dbHandle->where(array('msgId' => $threadId ));
        $dbHandle->update($this->messageboardconfig->messageTable,$data);

        $queryCmd="update messageExpertTable set status='closed' where threadId = ?";
        error_log_shiksha('debug', 'close messageExpertTable query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($threadId));
        
        //Add notification of APP in redis
        $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
        $this->appNotification->addNotificationForAppInRedis('close_question',$threadId,'question',$userId);
        
        $response = array(
            array(
            'Result'=>array('Updated')),
            'struct');
        return $this->xmlrpc->send_response($response);


    }

    /**
     * Update the board view count from beacon. When ever a thread is viewed, a beacon is called to update the popularity view
     * it calls beaconUpdate() to update viewCount.
     */
    function updateViewCount($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
        $userId=$parameters['2'];
        $this->load->model('Viewcountmodel');
        $this->Viewcountmodel->updateViewCounts($request,"qna");
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryCmd = 'update messageTable set viewCount=viewCount+1 where msgId=?';
        if(!$dbHandle->query($queryCmd,array($msgId))) {
            $response = array(array('error'=>'UpdateViewCount for message board Query Failed','struct'));
            return $this->xmlrpc->send_response($response);
        }

        //check if threadId belongs to userId
        $queryCmd = "select count(*) count from messageTable where threadId=? and userId=?";
        $query = $dbHandle->query($queryCmd,array($msgId,$userId));
        $msgCount=0;
        foreach ($query->result_array() as $row) {
            $msgCount  = $row['count'];
        }

        if($msgCount>0) {
            $this->beaconUpdate($msgId,$userId);
        }

        //update PopularityView
        $this->updatePopularityView($msgId);

        $response = array('updated','struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Update Beacon Count.
     * There is a feature to update new replies on the thread which user has posted or commented. When ever he views it we have to
     * remove the new replies count. This method keeps track of this.
     */
    function beaconUpdate($msgId,$userId) {
        $viewCount=1;
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "insert into messageTableBeacon (threadId,userId,viewCount,lastReadDate) values(?,?,?,now()) on duplicate key update lastReadDate=now(),viewCount=viewCount+1";

        if(!$dbHandle->query($queryCmd,array($msgId,$userId,$viewCount))) {
            $response = array(array('error'=>'UpdateViewCount for message board Query Failed','struct'));
            return $this->xmlrpc->send_response($response);
        }

    }

    /**
     *	Get the popular topics across all board's for a given country
     * Populate the popular topics, used to fill Popular tab
     */
    function getPopularTopics($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $countryId=$parameters['4'];
        $userId=$parameters['5'];
        $userGroup=$parameters['6'];
        $showQuestionsWithNoAnswers=$parameters['7'];
        $userId=($userId!='')?$userId:0;
        $userGroup=($userGroup!='')?$userGroup:0;

        $selectForCategoryAndCountry = "";
        $fromForCategoryAndCountry = "";
        $conditionForCategoryAndCountry = "";
        if($categoryId != 1) {
            $selectForCategoryAndCountry = ", m2.*";
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and m2.categoryId in (".$categoryId.") and ";
        }
        if($countryId != 1) {
            $selectForCategoryAndCountry .= ", (select group_concat(countryTable.name) from countryTable where countryTable.countryId=m3.countryId) as countryName";
            $fromForCategoryAndCountry .= ", messageCountryTable m3";
            $conditionForCategoryAndCountry .= " m1.threadId = m3.threadId and m3.countryId in (".$countryId.") and ";
        }

        $answerCondition = '';
        if($showQuestionsWithNoAnswers == 0) {
            $answerCondition = ' having  answerCount > 0 ';
        }
        //$vcardStatusQuery = " ,ifnull((select 1 from VCardInfo vci where vci.userId = t1.userid),0) vcardStatus";
		$vcardStatusQuery = " ,0 vcardStatus";
        $commentCountQuery = ",ifnull((select count(*) from messageTable MT where MT.threadId = m1.msgId and MT.fromOthers = 'user' and MT.parentId !=0 and MT.mainAnswerId != 0 and MT.status IN ('live','closed')),0) commentCount";
        $messageDiscussionQuery = ",ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD ";
        error_log_shiksha( 'getPopularTopics query cmd is ' . $queryCmd,'qna');

        //Modified by Ankur on 8 March for Shiksha Performance
        //In case, we are calling this function for Weekly Mailer data, we do not require the total count. Hence, we can change the query
        $totalRows = 0;
        if($userGroup=='normal' && $showQuestionsWithNoAnswers == 0) {
            $popularity = 20;
            $x=0;
            do {
                $popularity = $popularity - $x;
                $queryCmd = "select m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1)) listingTitle,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where $conditionForCategoryAndCountry m1.userId=t1.userId and m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and m1.popularityView > '".$popularity."' $answerCondition order by m1.popularityView desc, creationDate desc LIMIT $startFrom,$count";
                $query = $dbHandle->query($queryCmd);
                $x += 2;
                if($x>22) break;
            }while(count($query->result_array())<$count);
        }
        else {
            $queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery,m1.msgCount answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage, t1.displayname,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') limit 1)) listingTitle,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA')  where $conditionForCategoryAndCountry m1.userId=t1.userId and m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 $answerCondition order by m1.popularityView desc, creationDate desc LIMIT $startFrom,$count";
            error_log_shiksha( 'getPopularTopics query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd);

            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $dbHandle->query($queryCmdTotal);
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
            }
        }

        $msgArray = array();
        $threadIdCsv = '';
        $i=0;
        foreach ($query->result_array() as $row) {
            if($this->seo_update($row['creationDate'])) {
                $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
            }
            else {
            //$row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
                if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD']))
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['descriptionD'],'','',$row['creationDate']);
                else
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
            }
            $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];
            //array_push($msgArray,array($row,'struct'));
            $msgArray[$i] = $row;
            $i++;
        }

        $this->load->model('QnAModel');
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdCsv,true,true,true);
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();

        $mainArr = array();
		/*array_push($mainArr,array(
				array(
					'results'=>array($msgArray,'struct'),
					'totalCount'=>array($totalRows,'string'),
					'categoryCountry'=>array($msgArrayCatCountry,'struct'),
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');*/
        $mainArr[0]['results'] = $msgArray;
        $mainArr[0]['totalCount'] = $totalRows;
        $mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the answered topics across all board's for a given country
     * Give me unasnwered threads.
     */
    function getUnansweredTopics($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $countryId=$parameters['4'];
        $isSetCount=$parameters['5'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $selectForCategoryAndCountry = "";
        $fromForCategoryAndCountry = "";
        $conditionForCategoryAndCountry = "";
        $havingMisc = '';
        if($categoryId == 0){
            $selectForCategoryAndCountry = ", m2.*";
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and ";
            $havingMisc = "group by m2.threadId having count(m2.threadId) =1 or m2.categoryId =0";
        }
        if($categoryId != 1 && $categoryId != 0) {
            $selectForCategoryAndCountry = ", m2.*";
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and m2.categoryId in (".$categoryId.") and ";
        }
        if($countryId != 1) {
            $selectForCategoryAndCountry .= ", (select group_concat(countryTable.name) from countryTable where countryTable.countryId=m3.countryId) as countryName";
            $fromForCategoryAndCountry .= ", messageCountryTable m3";
            $conditionForCategoryAndCountry .= " m1.threadId = m3.threadId and m3.countryId in (".$countryId.") and ";
        }

        $vcardStatusQuery = " ,0 vcardStatus";
        $messageDiscussionQuery = ",ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD ";
        $totalRows = 0;

        $mergedQnA = array();
        if($isSetCount=='false') {

            $courseMigrationDate = SHIKSHA_COURSE_QUESTIONS_MIGRATION_DATE;
            //$queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery,0 answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where $conditionForCategoryAndCountry m1.userId=t1.userId and m1.status in ('live') and m1.fromOthers='user' and m1.parentId=0 and m1.msgCount = 0 order by m1.creationDate desc LIMIT $startFrom,$count";
	   		$queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery,0 answerCount from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry  m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and m1.msgCount = 0 and m1.listingTypeId=0 ".$havingMisc.
	 	   				" UNION ".
	 	   				"select  m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery,0 answerCount from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry  m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and m1.msgCount = 0 and m1.listingTypeId > 0 and m1.listingType = 'institute' and DATE(m1.creationDate) < $courseMigrationDate ".$havingMisc.
	 	   				" order by creationDate desc LIMIT $startFrom,$count";
	   		
            error_log_shiksha( 'getUnansweredTopics query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd);

            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $dbHandle->query($queryCmdTotal);
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
            }
            
            $mergedQnA = $query->result_array();
        }
        else {

            $courseMigrationDate = SHIKSHA_COURSE_QUESTIONS_MIGRATION_DATE;
            $date = date("Y-m-d");
            if($categoryId > 4){
                $x = 180;
            }
            else{
                $x = 60;
            }

            $startDate = date("2012-01-01");
            $today = date("Y-m-d");
            $tomorrow = strtotime("+1 days",strtotime($today));
            $tomorrow = date ('Y-m-j' , $tomorrow);

            do {
                $date = strtotime("-".$x." days",strtotime($date));
                $date = date ( 'Y-m-j' , $date );
                //$queryCmd = "select m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery,0 answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where $conditionForCategoryAndCountry m1.userId=t1.userId and m1.status in ('live') and m1.fromOthers='user' and m1.parentId=0 and m1.msgCount = 0 and m1.creationDate > '".$date."' order by m1.creationDate desc LIMIT $startFrom,$count";
				/*$queryCmd = "select m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery,0 answerCount from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and m1.msgCount = 0 and m1.creationDate > '".$date."' and m1.listingTypeId=0  " . 
							" UNION ".
							"select m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery,0 answerCount from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and m1.msgCount = 0 and m1.creationDate > '".$date."' and m1.listingTypeId > 0 and m1.listingType = 'institute' and DATE(m1.creationDate) < '".$courseMigrationDate."'  ".
							" order by creationDate desc LIMIT $startFrom,$count";*/
                $queryCmd = "select m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery, 0 answerCount from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and m1.msgCount = 0 and ((m1.creationDate > '".$date."' and m1.listingTypeId=0 and m1.creationDate<'".$tomorrow."') OR (m1.creationDate > '".$date."' and m1.listingTypeId>0 and m1.creationDate<'".$courseMigrationDate."')) " .$havingMisc. " order by creationDate desc LIMIT $startFrom,$count";
		$query = $dbHandle->query($queryCmd);
                $x = $x + ceil($startFrom/50);
            }while(count($query->result_array())<$count && ($date>$startDate) );
            
           $mergedQnA = $query->result_array() ;
        }
        
        $msgArray = array();
        $totalAnswered = 0;
        $threadIdCsv = '';
        foreach ($mergedQnA as $row) {
        
	    //If the cache is not set, we will have to fetch the User info and Level also. 
	    //This is done to reduce the query execution time so that the user info and level information is not fetched in the query.
	    $queryCmdT = "select t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,upv.levelName as level, t1.firstname, t1.lastname  from tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where t1.userid = ?";
	    $queryT = $dbHandle->query($queryCmdT, array($row['userId']));
	    $rowT = $queryT->row();
	    $row['lastlogintime'] = $rowT->lastlogintime;
	    $row['userImage'] = $rowT->userImage;
	    $row['displayname'] = $rowT->displayname;
	    $row['level'] = $rowT->level;
	    $row['userName'] = $rowT->firstname.' '.$rowT->lastname;

            if($this->seo_update($row['creationDate'])) {
                $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
            }
            else {
            //$row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
                if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD']))
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['descriptionD'],'','',$row['creationDate']);
                else
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
            }
            $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];
            //Start: Code added by Ankur on 10 March to make the institute names as URL in SHiksha Cafe
            $row['instituteurl'] = '';
            if($row['listingTypeId']>0) {
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $row['instituteurl'] = $ListingClientObj->getCorrectSeoURL($appID,$row['listingTypeId'],'institute');
                $queryCmdL = "select lm.listing_title  from  listings_main lm where lm.listing_type_id = ? and lm.listing_type = ? and lm.status IN ('live','abused')";
                $queryL = $dbHandle->query($queryCmdL, array($row['listingTypeId'],$row['listingType']));
                $rowL = $queryL->row();
                $row['listingTitle'] = $rowL->listing_title;
            }
            //End: Code added by Ankur on 10 March to make the institute names as URL in SHiksha Cafe

            array_push($msgArray,array($row,'struct'));
        }

		/*if($totalRows==0){
			$queryCmd = "select count(*) count, m1.* $selectForCategoryAndCountry,0 answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where $conditionForCategoryAndCountry m1.userId=t1.userId and m1.status in ('live') and m1.fromOthers='user' and m1.parentId=0 and m1.msgCount > 0 order by m1.creationDate desc LIMIT $startFrom,$count";
			$query = $dbHandle->query($queryCmd);
			$obj = $query->row();
			$totalAnswered = $obj['count'];
		}*/

        $this->load->model('QnAModel');
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdCsv);
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalCount'=>array($totalRows,'string'),
            'totalAnswered'=>array($totalAnswered,'string'),
            'categoryCountry'=>array($msgArrayCatCountry,'struct'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the recent topics across all board's for a given country
     * give me recent replied comments
     */
    function getRecentPostedTopics($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $countryId=$parameters['4'];
        $userId=$parameters['5'];
        $userGroup=$parameters['6'];
        $isSetCount=$parameters['7'];
        $userId=($userId!='')?$userId:0;
        $userGroup=($userGroup!='')?$userGroup:'normal';

        $selectForCategoryAndCountry = "";
        $fromForCategoryAndCountry = "";
        $threadIdCsv = '';
        $conditionForCategoryAndCountry = "";
        $havingMisc = '';
        if($categoryId == 0){
            $selectForCategoryAndCountry = ", m2.*";
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and ";
            $havingMisc = "group by m2.threadId having count(m2.threadId) =1 or m2.categoryId =0";
        }

        if($categoryId != 1 && $categoryId != 0) {
            $selectForCategoryAndCountry = ", m2.*";
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and m2.categoryId in (".$categoryId.") and ";
        }
        if($countryId != 1) {
        //The country name field is not used anymore. Hence, removing it
        //$selectForCategoryAndCountry .= ", (select group_concat(countryTable.name) from countryTable where countryTable.countryId=m3.countryId) as countryName";
            $fromForCategoryAndCountry .= ", messageCountryTable m3";
            $conditionForCategoryAndCountry .= " m1.threadId = m3.threadId and m3.countryId in (".$countryId.") and ";
        }
        //VCard image is not displayed anymore.
        //$vcardStatusQuery = " ,ifnull((select 1 from VCardInfo vci where vci.userId = t1.userid),0) vcardStatus";
        $vcardStatusQuery = ", 0 vcardStatus";
        $commentCountQuery = ",ifnull((select count(*) from messageTable MT where MT.threadId = m1.msgId and MT.fromOthers = 'user' and MT.parentId !=0 and MT.mainAnswerId != 0 and MT.status IN ('live','closed')),0) commentCount";
        //$messageDiscussionQuery = ",ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId),'') descriptionD ";
        $messageDiscussionQuery = " , '' descriptionD ";
        //Modified by Ankur on 8 March for SHiksha cafe performance
        //If the count is not set, then proceed as earlier. If it is set, then only find recent questions for the last few days
        $totalRows = 0;
        $likeQuery = " ,ifnull((select SUM(digUp) from messageTable mx where mx.parentId = m1.msgId and status IN ('live','closed')),0) likes ";
        $dislikeQuery = " ,ifnull((select SUM(digDown) from messageTable mx where mx.parentId = m1.msgId and status IN ('live','closed')),0) dislikes ";

        $mergedQnA = array();
        if($isSetCount=='false') {
        	$courseMigrationDate = SHIKSHA_COURSE_QUESTIONS_MIGRATION_DATE;
            //$queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where $conditionForCategoryAndCountry m1.userId=t1.userId and m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 order by m1.creationDate desc LIMIT $startFrom,$count";
	  	 //$queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry where $conditionForCategoryAndCountry  m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 AND m1.listingTypeId =0 ".$havingMisc.
	    	//		" UNION ".
	    	//		"select  m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry where $conditionForCategoryAndCountry  m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 AND m1.listingTypeId > 0 and DATE(m1.creationDate) < $courseMigrationDate ".$havingMisc.
	    	//		" order by creationDate desc LIMIT $startFrom,$count";

		$queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery, m1.msgCount answerCount ,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry where $conditionForCategoryAndCountry  m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 AND m1.listingTypeId =0 ".$havingMisc." order by creationDate desc LIMIT $startFrom,$count";
	    
            //error_log_shiksha('getRecentPostedTopics query cmd is ' . $queryCmd,'qna');
            
            $query = $dbHandle->query($queryCmd);

            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $dbHandle->query($queryCmdTotal);
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
            }
            
            $mergedQnA = $query->result_array() ;
        }
        else {
            $courseMigrationDate = SHIKSHA_COURSE_QUESTIONS_MIGRATION_DATE;
            $date = date("Y-m-d");
            if($categoryId > 4 && $countryId > 2){
                $x = 180;
            }
            else{
                $x = 10;
            }
            $startDate = date("2012-05-01");

            $today = date("Y-m-d");
            $tomorrow = strtotime("+1 days",strtotime($today));
            $tomorrow = date ('Y-m-j' , $tomorrow);

            do {
                $date = strtotime("-".$x." days",strtotime($date));
                $date = date ( 'Y-m-j' , $date );
                //$queryCmd = "select m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where $conditionForCategoryAndCountry m1.userId=t1.userId and m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and m1.creationDate > '".$date."' order by m1.creationDate desc LIMIT $startFrom,$count";
		/* 
			$queryCmd = "select m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 AND m1.listingTypeId =0 and m1.creationDate > '".$date."' " .
							" UNION ".
							"select m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 AND m1.listingTypeId > 0 and m1.listingType = 'institute' and m1.creationDate > '".$date."' and DATE(m1.creationDate) < '".$courseMigrationDate."'".
							" order by creationDate desc LIMIT $startFrom,$count";
		*/
		//$queryCmd = "select m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and ((m1.creationDate > '".$date."' and m1.listingTypeId=0 and m1.creationDate<'".$tomorrow."') OR (m1.creationDate > '".$date."' and m1.listingTypeId>0 and m1.creationDate<'".$courseMigrationDate."')) " .$havingMisc. " order by creationDate desc LIMIT $startFrom,$count";
		$queryCmd = "select m1.* $selectForCategoryAndCountry $vcardStatusQuery $messageDiscussionQuery, m1.msgCount answerCount ,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry  where $conditionForCategoryAndCountry m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and m1.creationDate > '".$date."' and m1.listingTypeId=0 and m1.creationDate<'".$tomorrow."' " .$havingMisc. " order by creationDate desc LIMIT $startFrom,$count";

                $query = $dbHandle->query($queryCmd);
                $x = $x + ceil($startFrom/80);
            }while(count($query->result_array())<$count && ($date>$startDate) );

            $mergedQnA = $query->result_array();
        }

        $msgArray = array();
        $i=0;
        foreach ($mergedQnA as $row) {
            $queryCmdD = "select ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId  = ? LIMIT 1),'') discussionD";
            $queryD = $dbHandle->query($queryCmdD, array($row['msgId']));
            $rowD = $queryD->row();
            $row['descriptionD'] = $rowD->discussionD;

	    //If the cache is not set, we will have to fetch the User info and Level also. 
	    //This is done to reduce the query execution time so that the user info and level information is not fetched in the query.
	    //if($isSetCount=='false') {
		  $queryCmdT = "select t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname, t1.firstname, t1.lastname,  upv.levelName as level from tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where t1.userid = ?";
		  $queryT = $dbHandle->query($queryCmdT, array($row['userId']) );
		  $rowT = $queryT->row();
		  $row['lastlogintime'] = $rowT->lastlogintime;
		  $row['userImage'] = $rowT->userImage;
		  $row['displayname'] = $rowT->displayname;
		  $row['level'] = $rowT->level;
          $row['userName'] = $rowT->firstname.' '.$rowT->lastname;
          $row['lastname'] = $rowT->lastname;
	    //}


            if($this->seo_update($row['creationDate'])) {
                $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
            }
            else {
                if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD']))
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['descriptionD'],'','',$row['creationDate']);
                else
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
            }
            $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];
            //Start: Code added by Ankur on 10 March to make the institute names as URL in SHiksha Cafe
            $row['instituteurl'] = '';
            if($row['listingTypeId']>0) {
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $row['instituteurl'] = $ListingClientObj->getCorrectSeoURL($appID,$row['listingTypeId'],'institute');
                $queryCmdL = "select lm.listing_title  from  listings_main lm where lm.listing_type_id = ? and lm.listing_type = ? and lm.status IN ('live','abused')";
                $queryL = $dbHandle->query($queryCmdL, array($row['listingTypeId'],$row['listingType']) );
                $rowL = $queryL->row();
                $row['listingTitle'] = $rowL->listing_title;
            }
        //    array_push($msgArray,array($row,'struct'));
            $msgArray[$i] = $row;
            $i++;
        //End: Code added by Ankur on 10 March to make the institute names as URL in SHiksha Cafe
        }

        $this->load->model('QnAModel');
        $msgArrayCatCountry = array();
        if($threadIdCsv!='') {
            $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdCsv,true,true,true);
        }
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
        $mainArr = array();
		/*array_push($mainArr,array(
				array(
					'results'=>array($msgArray,'struct'),
					'totalCount'=>array($totalRows,'string'),
					'categoryCountry'=>array($msgArrayCatCountry,'struct'),
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');*/
        $mainArr[0]['results'] = $msgArray;
        $mainArr[0]['totalCount'] = $totalRows;
        $mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the count of new replies for a thread on topics and comments posted by me
     */
    function getNewReplyCount($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $userId=$parameters['2'];
        $countryId=$parameters['3'];


        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "select sum(newMsgCount) totalNewMsgCount from (select (select count(*) from messageTable where threadId=m1.threadId and status in ('live','closed') and creationDate > (select lastReadDate from messageTableBeacon where userId=? and threadId=m1.threadId)) newMsgCount from messageTable m1, messageCategoryTable m2, messageCountryTable m3 where m1.threadId=m2.threadId and m1.threadId=m3.threadId and m1.userId=? and m2.categoryId in (?) and m3.countryId in (?)  and m1.status in ('live','closed') and m1.fromOthers='user' group by m1.threadId) as tempTable";
        error_log_shiksha( 'getNewReplyCount query cmd is ' . $queryCmd,'qna');

        $categoryIdArray = explode(',', $categoryId);
        $countryIdArray = explode(',', $countryId);
        $query = $dbHandle->query($queryCmd,array($userId,$userId,$categoryIdArray,$countryIdArray));
        $newReplyCount=0;
        foreach ($query->result() as $row) {
            $newReplyCount=$row->totalNewMsgCount;
        }

        $response = array($newReplyCount,'int');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * This method returns a new reply count for a logged in user.
     * This method gets a array of threadId's
     */
    function getNewReplyCountForQuestions($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $questionIds=$parameters['2'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = "select threadId,count(*) as replyCount from messageTable m1 where m1.threadId in (?) and m1.status in ('live','closed') and m1.creationDate > (select lastReadDate from messageTableBeacon where userId = ? and threadId = m1.threadId) group by m1.threadId";
        error_log_shiksha( 'getNewReplyCountForQuestions query cmd is ' . $queryCmd,'qna');
        $questionIdsArray = explode(',', $questionIds);
        $query = $dbHandle->query($queryCmd,array($questionIdsArray,$userId));
        $newRepliesArray = array();
        foreach ($query->result_array() as $row) {
            $newRepliesArray[$row['threadId']] = $row['replyCount'];
        }

        $msgArray = array();
        $newReplyCountArray=array();
        $threadIdArray = explode(",",$questionIds);
        foreach($threadIdArray as $temp) {
            if(array_key_exists($temp,$newRepliesArray))
                $newReplyCountArray[$temp] = array($newRepliesArray[$temp],'int');
            else
                $newReplyCountArray[$temp] = array(0,'int');
        }
        array_push($msgArray,array($newReplyCountArray,'struct'));

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the topics and comments posted by me
     *
     */
    function getMyTopics($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $userId=$parameters['2'];
        $startFrom=$parameters['3'];
        $count=$parameters['4'];
        $countryId=$parameters['5'];


        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $threadIdCsv = '';
        $queryCmd = "select distinct(threadId) from messageTable where userId = ? and status not in ('deleted','abused') and fromOthers = 'user'";
        $query = $dbHandle->query($queryCmd,array($userId));
        foreach ($query->result_array() as $row) {
            $threadIdCsv .= ($threadIdCsv=='')?$row['threadId']:','.$row['threadId'];
        }

        if($threadIdCsv!='') {
            $queryCmd ="select SQL_CALC_FOUND_ROWS m1.*, t1.displayname from messageTable m1 INNER JOIN tuser t1 ON (m1.userId = t1.userId) where  m1.parentId = 0 and m1.status not in ('deleted','abused') and m1.fromOthers = 'user' and m1.threadId in ($threadIdCsv) order by creationDate desc LIMIT $startFrom,$count";
            error_log_shiksha( 'getMyTopics query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd);
        }
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
            array_push($msgArray,array($row,'struct'));
        }

        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $dbHandle->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
            $totalRows = $row->totalRows;
        }
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalCount'=>array($totalRows,'string'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the questions posted by a given user. This method is called from User Q & A pages
     */
    function getUserQuestions($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $countFlag=$parameters['4'];
        $loggedInUserId = $parameters['5'];
        $loggedInUserId = ($loggedInUserId == '')?0:$loggedInUserId;

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryForLoggedIUser = "";
        if($loggedInUserId > 0) {
            $queryForLoggedIUser = " , ifnull((select count(*) from messageTable M2 where M2.threadId = m1.threadId and M2.parentId = M2.threadId and M2.status not in('deleted','abused') and M2.fromOthers = 'user' and M2.userId = ".$dbHandle->escape($loggedInUserId)."),0) userAnswerFlag";
        }else {
            $queryForLoggedIUser = " , 0 userAnswerFlag";
        }
        $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,ifnull((select count(*) from messageTable M1 where M1.parentId = M1.threadId and M1.threadId = m1.threadId and M1.status not in ('deleted','abused') and M1.fromOthers = 'user'),0) answerCount $queryForLoggedIUser from messageTable m1  where parentId = 0 and userId = ? and m1.status in ('live','closed') and m1.fromOthers = 'user' order by m1.creationDate desc LIMIT $startFrom,$count";
        error_log_shiksha( 'getMyTopics query cmd is ' . $queryCmd,'qna');

        $query = $dbHandle->query($queryCmd,array($userId));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
            array_push($msgArray,array($row,'struct'));
        }

        $totalQuestions = 0;
        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $dbHandle->query($queryCmd);
        $totalQuestions = 0;
        foreach ($query->result() as $row) {
            $totalQuestions  = $row->totalRows;
        }

        $totalAnswers = 0;
        if(strcmp($countFlag,'true')==0) {
            $queryCmd = "select count(*) as totalRows from messageTable m1,tuser t1 where m1.userId = ? and m1.status in ('live','closed') and m1.userId = t1.userid and m1.parentId != 0 and m1.fromOthers = 'user'";
            $query = $dbHandle->query($queryCmd,array($userId));
            foreach ($query->result() as $row) {
                $totalAnswers  = $row->totalRows;
            }
        }

        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalQuestions'=>array($totalQuestions,'string'),
            'totalAnswers'=>array($totalAnswers,'string'),
            'totalQuestionAsked'=>array($totalQuestions,'string')
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the questions posted by user. Used in User Q n A page
     */
    function getUserAnswers($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $countFlag=$parameters['4'];
        $loggedUserId=$parameters['5'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        if($loggedUserId > 0)
            $getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=m1.msgId and ral.userId=".$dbHandle->escape($loggedUserId)."),0) reportedAbuse ";

        $queryCmd = "select SQL_CALC_FOUND_ROWS msgId,threadId,parentId,userId,m1.status,msgTxt,(select creationDate from messageTable where threadId=m1.threadId and parentId=0) creationDate ".$getReportedAbuse." from messageTable m1  where userId = ? and fromOthers='user' and m1.status in ('live','closed') and parentId != 0 and mainAnswerId=0 order by creationDate desc LIMIT ".$startFrom." , ".$count;
        $resultSet = $dbHandle->query($queryCmd, array($userId));
        $tempArray = array();
        $idString = -1;
        foreach ($resultSet->result_array() as $rowQ) {
            $tempArray[$rowQ['threadId']] = $rowQ;
            if($idString == -1)
                $idString = $rowQ['threadId'];
            else
                $idString .= ",".$rowQ['threadId'];
        }
        $totalQuestionsAnswered = 0;
        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $dbHandle->query($queryCmd);
        $totalQuestions = 0;
        foreach ($query->result() as $rowT) {
            $totalQuestionsAnswered  = $rowT->totalRows;
        }
        $queryCmd = "select msgId,msgTxt,threadId,creationDate,msgCount,viewCount,m1.status,t1.userid,t1.displayname questionOwner,upv.levelName as level from messageTable m1,tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where m1.userId = t1.userid and threadId in (".$idString.") and parentId = 0 order by creationDate desc";
        error_log_shiksha( 'getMyTopics query cmd is ' . $queryCmd,'qna');

        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        $i = 0;
        foreach ($query->result_array() as $row) {
            $tempMsgArray = array();
            $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
            $tempMsgArray['question']=array($row,'struct');
            $tempMsgArray['answer']=array($tempArray[$row['threadId']],'struct');
            array_push($msgArray,array($tempMsgArray,'struct'));
            $i++;
        }

        $totalQuestions = 0;
        $totalAnswers = 0;
        if(strcmp($countFlag,'true')==0) {
            $queryCmd = "select count(*) as totalRows from messageTable m1,tuser t1 where m1.userId = ? and m1.status in ('live','closed') and m1.userId = t1.userid and m1.parentId != 0 and m1.fromOthers = 'user' ";
            $query = $dbHandle->query($queryCmd,array($userId));
            foreach ($query->result() as $row) {
                $totalAnswers  = $row->totalRows;
            }

            $queryCmd = "select count(*) as totalRows from messageTable m1 where parentId = 0 and userId = ? and m1.status in ('live','closed') and m1.fromOthers = 'user'";
            $query = $dbHandle->query($queryCmd,array($userId));
            foreach ($query->result() as $row) {
                $totalQuestions  = $row->totalRows;
            }
        }

        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalQuestions'=>array($totalQuestions,'string'),
            'totalAnswers'=>array($totalAnswers,'string'),
            'totalQuestionAnswered'=>array($totalQuestionsAnswered,'string')
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     This function returns the question and answers for ask and answer home page.
     userId is the id os logged in user.
     flagForList have values question/answer/bestanswer for different list.

     */
    function getQuestionAnswersForHome($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $categoryId=$parameters['2'];
        $countryId=$parameters['3'];
        $startFrom=$parameters['4'];
        $count=$parameters['5'];
        $flagForList=$parameters['6'];
        $countFlag=$parameters['7'];
        $isSetCount=$parameters['8'];

        $fromForCategoryAndCountry = "";
        $conditionForCategoryAndCountry = "";
        $threadIdCsv = '';
        if($categoryId != 1) {
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and m2.categoryId in (".$categoryId.") and ";
        }
        if($countryId != 1) {
            $fromForCategoryAndCountry .= ", messageCountryTable m3";
            $conditionForCategoryAndCountry .= " m1.threadId = m3.threadId and m3.countryId in (".$countryId.") and ";
        }
        $msgArray = array();
        $msgArray3 = array();
        $answerIdString = '';

        $this->load->model('UserPointSystemModel');
        $res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$userId);

        //if($res[reputationPoints]>50){
        //     $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,ifnull((select count(*) from messageTable M1  where parentId = threadId and M1.threadId = m1.threadId and M1.status not in ('deleted','abused') and M1.fromOthers = 'user'),0) answerCount,t1.lastlogintime,(t1.avtarimageurl)userImage, t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA'),  (select m1.* from messageTable m1 join qnaMasterQuestionTable qmqt on (qmqt.msgId=m1.msgId) where length(m1.msgTxt)>140 ) untitledQuestion where m1.parentId = 0 and  m1.userId = t1.userid and $conditionForCategoryAndCountry m1.status not in ('deleted','abused') and m1.fromOthers = 'user' order by  m1.creationDate desc LIMIT ".$startFrom.",".$count;
        // }else{
        //    $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,ifnull((select count(*) from messageTable M1  where parentId = threadId and M1.threadId = m1.threadId and M1.status not in ('deleted','abused') and M1.fromOthers = 'user'),0) answerCount,(select count(*) from messageTable where threadId=m1.threadId and creationDate > (select lastReadDate from messageTableBeacon where userId=".$userId." and threadId=m1.threadId)) newMsgCount,t1.lastlogintime,(t1.avtarimageurl)userImage, t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA')  where m1.parentId = 0 and  m1.userId = t1.userid and $conditionForCategoryAndCountry m1.userId = ".$userId." and m1.status not in ('deleted','abused') and m1.fromOthers = 'user' order by newMsgCount desc , m1.creationDate desc LIMIT ".$startFrom.",".$count;
        //   }
        //  $query = $dbHandle->query($queryCmd);

        //$rowNo = $query->num_rows();

        $totaluntitledQuestions = 0;

        $queryCmdUserGrp = "select usergroup from tuser where userid=?";
        $queryUserGrp = $dbHandle->query($queryCmdUserGrp,array($userId));
        $resUserGrp = $queryUserGrp->row();


        error_log("value of usergroup is ".print_r($resUserGrp,true));
        $storeCountInCache = 0;
        $callCountQuery = true;
        if(($flagForList == 'untitledQuestion') || ($countFlag)) {
	    //Added to exclude Institute questions and default questions from the Untitled question list
	    $excludeQuestionString = " AND m1.msgTxt!='Type your education related question in this box. Your questions will be answered by Shiksha Counselors, Experts, College Alumni and Students.' AND m1.listingTypeId=0 ";
            if(($res[reputationPoints]>25 && $res[reputationPoints]!=9999999) || $resUserGrp->usergroup=='cms') {
            //Modified by Ankur on 1 June. In case the Count was stored in Cache, do not get the count again from query
                if($isSetCount=='false') {
                    $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,tu.email,tu.usergroup,ifnull((select count(*) from messageTable M1  where parentId = threadId and M1.threadId = m1.threadId and M1.status not in ('deleted','abused') and M1.fromOthers = 'user'),0) answerCount,tu.lastlogintime,(tu.avtarimageurl)userImage, tu.displayname, tu.firstname, tu.lastname from messageTable m1 right join qnaMasterQuestionTable qmqt  on (m1.msgId=qmqt.msgId ) left join tuser tu on (tu.userid=m1.userId) $fromForCategoryAndCountry where m1.msgId not in (select distinct(msgId) from questionEditTitleLog where `flag`!='deleted') and qmqt.status = 'live' and length( REPLACE( REPLACE( REPLACE(msgTxt, '&gt;','1') , '&quot;','1'), '&amp;','1') )>140 and $conditionForCategoryAndCountry m1.status not in ('deleted','abused') ".$excludeQuestionString." and m1.fromOthers = 'user' order by  m1.creationDate desc LIMIT ".$startFrom.",".$count;
                }
                else { //In this case, add a check for Creation date of the question
                    $callCountQuery = false;
                    $date = date("Y-m-d");
                    $x=1500000;
                    do {
                        $queryCmd = "select m1.*,tu.email,tu.usergroup,m1.msgCount answerCount,tu.lastlogintime,(tu.avtarimageurl)userImage, tu.displayname, tu.firstname, tu.lastname from messageTable m1 ,qnaMasterQuestionTable qmqt, tuser tu $fromForCategoryAndCountry  where m1.msgId=qmqt.msgId and tu.userid=m1.userId and qmqt.msgId not in (select distinct(msgId) from questionEditTitleLog where `flag`!='deleted') and qmqt.status = 'live' and length( REPLACE( REPLACE( REPLACE(m1.msgTxt, '&gt;','1') , '&quot;','1'), '&amp;','1') )>140 and $conditionForCategoryAndCountry m1.status not in ('deleted','abused') and qmqt.msgId > '$x' and m1.parentId = 0 ".$excludeQuestionString." and m1.fromOthers = 'user' order by  qmqt.msgId desc LIMIT ".$startFrom.",".$count;
                        $resultSet = $dbHandle->query($queryCmd);
                        $x = $x - 50000;
                    }while(count($resultSet->result_array())<$count && $x>0);
                }
                $storeCountInCache = 1;
            //End modifications
            }else {
                $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,ifnull((select count(*) from messageTable M1  where parentId = threadId and M1.threadId = m1.threadId and M1.status not in ('deleted','abused') and M1.fromOthers = 'user'),0) answerCount,tu.lastlogintime,(tu.avtarimageurl)userImage, tu.displayname, tu.firstname, tu.lastname from messageTable m1 right join qnaMasterQuestionTable qmqt  on (m1.msgId=qmqt.msgId ) left join tuser tu on (tu.userid=".$dbHandle->escape($userId).") $fromForCategoryAndCountry where m1.msgId not in (select distinct(msgId) from questionEditTitleLog where `flag`!='deleted') and m1.userId=".$dbHandle->escape($userId)." and qmqt.status = 'live' and length( REPLACE( REPLACE( REPLACE(msgTxt, '&gt;','1') , '&quot;','1'), '&amp;','1') )>140 and $conditionForCategoryAndCountry m1.status not in ('deleted','abused') ".$excludeQuestionString." and m1.fromOthers = 'user' order by  m1.creationDate desc LIMIT ".$startFrom.",".$count;
            }
            //Modified by Ankur on 1 June. In case the Count was stored in Cache, do not get the count again from query
            if($callCountQuery) {
                $resultSet = $dbHandle->query($queryCmd);
                $countQuery = 'SELECT FOUND_ROWS() as totalRows';
                $result = $dbHandle->query($countQuery);
                $totaluntitledQuestions = 0;
                foreach ($result->result() as $row) {
                    $totaluntitledQuestions  = $row->totalRows;
                }
            }
            //End Modifications
            if($flagForList == 'untitledQuestion') {
                foreach ($resultSet->result_array() as $row) {
                    if($this->seo_update($row['creationDate'])) {
                        $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
                    }
                    else {
                        $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
                    }
                    $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];
                    array_push($msgArray,array($row,'struct'));
                }
            }
        }
        $totalQuestions = 0;
        if(($flagForList == 'question') || ($countFlag)) {
            $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,ifnull((select count(*) from messageTable M1  where parentId = threadId and M1.threadId = m1.threadId and M1.status not in ('deleted','abused') and M1.fromOthers = 'user'),0) answerCount,(select count(*) from messageTable where threadId=m1.threadId and creationDate > (select lastReadDate from messageTableBeacon where userId=? and threadId=m1.threadId)) newMsgCount,t1.lastlogintime,(t1.avtarimageurl)userImage, t1.displayname,t1.firstname,t1.lastname,upv.levelName as level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA')  where m1.parentId = 0 and  m1.userId = t1.userid and $conditionForCategoryAndCountry m1.userId = ? and m1.status not in ('deleted','abused') and m1.fromOthers = 'user' order by newMsgCount desc , m1.creationDate desc LIMIT ".$startFrom.",".$count;
            error_log_shiksha('getQuestionAnswersForHome question querycmd is '.$queryCmd,'qna');
            $resultSet = $dbHandle->query($queryCmd, array($userId, $userId) );
            $countQuery = 'SELECT FOUND_ROWS() as totalRows';
            $result = $dbHandle->query($countQuery);
            $totalQuestions = 0;
            foreach ($result->result() as $row) {
                $totalQuestions  = $row->totalRows;
            }
            if($flagForList == 'question') {
                foreach ($resultSet->result_array() as $row) {
                    if($this->seo_update($row['creationDate'])) {
                        $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
                    }
                    else {
                        $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
                    }
                    $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];
                    array_push($msgArray,array($row,'struct'));
                }
            }
        }

        $totalQuestionsAnswered = 0;
        if(($flagForList == 'answer') || ($countFlag)) {
            $queryCmd = "select SQL_CALC_FOUND_ROWS res.* from (select m1.msgId,m1.threadId,m1.parentId,m1.msgCount,m1.viewCount,m1.msgTxt,m1.userId,m1.status,m1.digUp,m1.digDown,(select count(*) from messageTable M1 where M1.mainAnswerId = m1.msgId and M1.threadId = m1.threadId and M1.fromOthers = 'user' and M1.status not in ('deleted','abused')) repliesToAnswerCount,(select count(*) from messageTable where threadId=m1.threadId and creationDate > (select lastReadDate from messageTableBeacon where userId=? and threadId=m1.threadId)) newMsgCount,(select creationDate from messageTable where threadId=m1.threadId and parentId=0) creationTime,t1.lastlogintime,(t1.avtarimageurl)userImage, t1.displayname,t1.firstname,t1.lastname,  ifnull((select 1 from messageTableBestAnsMap mbp where mbp.threadId = m1.threadId and mbp.bestAnsId = m1.msgId),0) bestAnsFlag from messageTable m1,tuser t1 $fromForCategoryAndCountry  where m1.userId = ?  and  $conditionForCategoryAndCountry m1.userId = t1.userid and fromOthers='user' and m1.status in ('live','closed') and parentId <> 0  and m1.parentId = m1.threadId order by bestAnsFlag desc,m1.creationDate desc) res group by res.threadId order by res.newMsgCount desc,res.creationTime desc LIMIT ".$startFrom.",".$count;
            error_log_shiksha('getQuestionAnswersForHome answers for user answers querycmd is '.$queryCmd,'qna');
            $resultSet = $dbHandle->query($queryCmd, array($userId, $userId));
            $countQuery = 'SELECT FOUND_ROWS() as totalRows';
            $query = $dbHandle->query($countQuery);
            foreach ($query->result() as $row) {
                $totalQuestionsAnswered  = $row->totalRows;
            }

            //The above query get executed to get the count.
            if($flagForList == 'answer') {
                $msgArray = array();
                $tempArray = array();
                $idString = -1;
                foreach ($resultSet->result_array() as $rowQ) {
                    $tempArray[$rowQ['threadId']] = $rowQ;
                    if($idString == -1) {
                        $idString = $rowQ['threadId'];
                    }else {
                        $idString .= ",".$rowQ['threadId'];
                    }
                    $answerIdString .= ($answerIdString=='')?$rowQ['msgId']:','.$rowQ['msgId'];
                }

                $queryCmd = "select res.* from (select m1.msgId,m1.threadId,m1.parentId,m1.msgCount,m1.viewCount,m1.msgTxt,m1.userId,m1.status,ifnull((select count(*) from messageTable M1 where M1.parentId = M1.threadId and M1.threadId = m1.threadId and M1.status not in ('deleted','abused') and M1.fromOthers = 'user'),0) answerCount,(select creationDate from messageTable where threadId=m1.threadId and parentId=0) creationDate,(select count(*) from messageTable where threadId=m1.threadId and creationDate > (select lastReadDate from messageTableBeacon where userId=? and threadId=m1.threadId)) newMsgCount,t1.lastlogintime,(t1.avtarimageurl)userImage, t1.displayname, t1.firstname, t1.lastname,upv.levelName as level from messageTable m1 $fromForCategoryAndCountry, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where m1.userId = t1.userid and $conditionForCategoryAndCountry m1.threadId in(".$idString.") and m1.fromOthers='user' and parentId = 0) res group by res.threadId order by res.newMsgCount desc,res.creationDate desc";
                error_log_shiksha('getQuestionAnswersForHome question for user answers querycmd is '.$queryCmd,'qna');
                $resultSet = $dbHandle->query($queryCmd, array($userId));
                foreach ($resultSet->result_array() as $row) {
                    $tempMsgArray = array();
                    if($this->seo_update($row['creationDate'])) {
                        $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
                    }
                    else {
                        $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
                    }
                    $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];
                    $tempMsgArray['question']=array($row,'struct');
                    $tempMsgArray['answer']=array($tempArray[$row['threadId']],'struct');
                    array_push($msgArray,array($tempMsgArray,'struct'));
                }
                //Code Start to get the Comments corresponding to all the answers retrived
                if($answerIdString != '') {
                    $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.mainAnswerId in (".$answerIdString.") and status IN ('live','closed') order by m1.creationDate ASC";
                    $Result = $dbHandle->query($queryCmd);
                    foreach($Result->result_array() as $row) {
                        array_push($msgArray3 ,array($row,'struct'));
                    }
                }
            //Code End to get the Comments corresponding to all the answers retrived

            }
        }
        $totalBestAnswers = 0;
    /*    if(($flagForList == 'bestanswer') || ($countFlag)) {

            $queryCmd = "select m1.msgId,m1.threadId,m1.path,m1.parentId,m1.msgCount,m1.viewCount,m1.msgTxt,m1.userId,m1.status,m1.digUp,m1.digDown,(select creationDate from messageTable where threadId=m1.threadId and parentId=0) creationDate,(select count(*) from messageTable where threadId=m1.threadId and creationDate > (select lastReadDate from messageTableBeacon where userId=? and threadId=m1.threadId)) newMsgCount,t1.lastlogintime,(t1.avtarimageurl)userImage, t1.displayname,(select count(*) from messageTable M1 where M1.mainAnswerId = m1.msgId and M1.threadId = m1.threadId and M1.fromOthers = 'user' and M1.status not in ('deleted','abused')) repliesToAnswerCount from messageTable m1,tuser t1, messageTableBestAnsMap mbp $fromForCategoryAndCountry  where m1.userId = ? and $conditionForCategoryAndCountry  m1.userId = t1.userid and fromOthers='user' and m1.status in ('live','closed') and parentId <> 0  and m1.parentId = m1.threadId and m1.threadId = mbp.threadId and m1.msgId = mbp.bestAnsId order by newMsgCount,creationDate desc LIMIT ".$startFrom.",".$count;
            error_log_shiksha('getQuestionAnswersForHome question for user answers querycmd is '.$queryCmd,'qna');
            $resultSet = $dbHandle->query($queryCmd, array($userId, $userId));
            $countQuery = 'SELECT FOUND_ROWS() as totalRows';
            $query = $dbHandle->query($countQuery);
            foreach ($query->result() as $row) {
                $totalBestAnswers  = $row->totalRows;
            }
            //The above query get executed to get the .
            if($flagForList == 'bestanswer') {
                $msgArray = array();
                $tempArray = array();
                $idString = -1;
                foreach ($resultSet->result_array() as $rowQ) {
                    $tempArray[$rowQ['threadId']] = $rowQ;
                    if($idString == -1) {
                        $idString = $rowQ['threadId'];
                    }else {
                        $idString .= ",".$rowQ['threadId'];
                    }
                    $answerIdString .= ($answerIdString=='')?$rowQ['msgId']:','.$rowQ['msgId'];
                }

                $queryCmd = "select m1.msgId,m1.threadId,m1.path,m1.parentId,m1.msgCount,m1.viewCount,m1.msgTxt,m1.creationDate,m1.userId,m1.status,ifnull((select count(*) from messageTable M1	where M1.parentId = M1.threadId and M1.threadId = m1.threadId and M1.status not in ('deleted','abused') and M1.fromOthers = 'user'),0) answerCount,(select count(*) from messageTable where threadId=m1.threadId and creationDate > (select lastReadDate from messageTableBeacon where userId=? and threadId=m1.threadId)) newMsgCount,t1.lastlogintime,(t1.avtarimageurl)userImage, t1.displayname,upv.levelName as level from messageTable m1 $fromForCategoryAndCountry , tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA')  where m1.threadId in (".$idString.") and $conditionForCategoryAndCountry  m1.userId = t1.userid and fromOthers='user' and m1.status in ('live','closed') and parentId = 0 order by newMsgCount desc,m1.creationDate desc ";
                $resultSet = $dbHandle->query($queryCmd, array($userId));
                $msgArray = array();
                foreach ($resultSet->result_array() as $row) {
                    $tempMsgArray = array();
                    if($this->seo_update($row['creationDate'])) {
                        $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
                    }
                    else {
                        $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
                    }
                    $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];
                    $tempMsgArray['question']=array($row,'struct');
                    $tempMsgArray['answer']=array($tempArray[$row['threadId']],'struct');
                    array_push($msgArray,array($tempMsgArray,'struct'));
                }
                //Code Start to get the Comments corresponding to all the answers retrived
                if($answerIdString != '') {
                    $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.mainAnswerId in (".$answerIdString.") and status IN ('live','closed') order by m1.creationDate ASC";
                    $Result = $dbHandle->query($queryCmd);
                    foreach($Result->result_array() as $row) {
                        array_push($msgArray3 ,array($row,'struct'));
                    }
                }
            //Code End to get the Comments corresponding to all the answers retrived

            }
        }*/

        $this->load->model('QnAModel');
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdCsv);
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();

        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalQuestions'=>array($totalQuestions,'string'),
            'totalQuestionsAnswered'=>array($totalQuestionsAnswered,'string'),
            'totalBestAnswers'=>array($totalBestAnswers,'string'),
            'categoryCountry'=>array($msgArrayCatCountry,'struct'),
            'answerComments'=>array($msgArray3,'struct'),
            'totaluntitledQuestion' =>array($totaluntitledQuestions,'stuct'),
            'storeCountInCache'=>array($storeCountInCache,'string')
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);

    }


    /**
     *	Get the category list for a given parent category.
     */
    function getMostContributingUser($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $count=$parameters['1'];
        $start = $parameters['2'];
        $count1 = $parameters['3'];
        $this->load->model('QnAModel');
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $queryCmd="select upsm.userpointvaluebymodule userPointValue ,t1.*,(select level from userPointLevelByModule upl where minLimit<=upsm.userpointvaluebymodule and  upl.module = 'AnA' limit 1) level,(select count(*) from messageTable M1 where M1.threadId = M1.parentId and M1.fromOthers='user' and M1.status in('live','closed') and M1.userId = t1.userid) answerCount,0 bestAnswerCount,ted.EducationLevel,ted.Options from userpointsystembymodule upsm INNER JOIN tuser t1 ON (upsm.userId = t1.userid and upsm.modulename='AnA') LEFT JOIN  tEducationLevel ted ON (t1.educationlevel = ted.EducationId) where  upsm.modulename = 'AnA' and t1.usergroup != 'cms' and t1.userid > 1000 and t1.userid not in (1130, 1156, 3895, 1014, 1020, 249142, 1045, 1318, 15, 23, 343294, 333758, 345634, 341660, 344301, 345646, 345642, 344016, 344227, 345632, 370572, 371307, 371451, 371380, 369633, 364773, 369446, 356573, 351009, 370533, 356504, 356441, 368464, 369661, 339598, 369214, 369687, 370418, 356444, 345634, 370543, 370471, 369596, 371346, 368423, 356620, 351011, 365579, 368466, 351005, 1130, 370454, 370523, 370372, 358765, 368594, 370398, 351002, 1156, 344301, 1045, 345646, 355361, 355354, 370560, 368460, 369443, 365245, 370440, 370480, 370561, 345642, 371471, 370525, 371393, 371410, 358820, 369506, 369662, 370552, 369466, 370461, 370570, 371415, 371337, 371325, 356437, 345632, 369460)  order by upsm.userpointvaluebymodule desc limit ?";
        error_log_shiksha( 'getMostContributingUser query cmd is ' . $queryCmd,'qna');

        $query = $dbHandle->query($queryCmd,array($count));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }
        $resArray = $this->QnAModel->getExpertUsers($dbHandle,$start,$count1);
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'mostcontributing'=>array($msgArray,'struct'),
            'expertUsers'=>array($resArray['userdata'],'struct'),
            'numOfExpertUsers'=>array($resArray['numOfExpertUsers'],'string')
            ),'struct')
        );//close array_push
        $response=array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the category list for a given parent category.
     */
    function getTopContributors($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $count=$parameters['1'];
        $weekly = $parameters['2'];
        $start = $parameters['3'];
        $tc = $parameters['4'];
        $tp = $parameters['5'];
        $catId = $parameters['6'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        if($weekly==0) {
            if($tc=="1")
            $queryCmd="select upsm.userpointvaluebymodule userPointValue ,t1.*,upsm.levelName as level,ted.EducationLevel,ted.Options from userpointsystembymodule upsm INNER JOIN tuser t1 ON (upsm.userId = t1.userid and upsm.modulename='AnA') LEFT JOIN  tEducationLevel ted ON (t1.educationlevel = ted.EducationId) where  upsm.modulename = 'AnA' and t1.usergroup != 'cms' and t1.userid > 1000 and t1.userid not in (1130, 1156, 3895, 1014, 1020, 249142, 1045, 1318, 15, 23, 343294, 333758, 345634, 341660, 344301, 345646, 345642, 344016, 344227, 345632, 370572, 371307, 371451, 371380, 369633, 364773, 369446, 356573, 351009, 370533, 356504, 356441, 368464, 369661, 339598, 369214, 369687, 370418, 356444, 345634, 370543, 370471, 369596, 371346, 368423, 356620, 351011, 365579, 368466, 351005, 1130, 370454, 370523, 370372, 358765, 368594, 370398, 351002, 1156, 344301, 1045, 345646, 355361,355354, 370560, 368460, 369443, 365245, 370440, 370480, 370561, 345642, 371471, 370525, 371393, 371410, 358820, 369506, 369662, 370552, 369466, 370461, 370570, 371415, 371337, 371325, 356437, 345632, 369460,762290,1544835,1459752,1478915)  order by upsm.userpointvaluebymodule desc limit $start, $count";
            
            if($tp=="1")
              $queryCmdParticipate="select upsm.userpointvaluebymodule userPointValue ,t1.*,upsm.levelName as level,ted.EducationLevel,ted.Options from userpointsystembymodule upsm INNER JOIN tuser t1 ON (upsm.userId = t1.userid and upsm.modulename='Participate') LEFT JOIN  tEducationLevel ted ON (t1.educationlevel = ted.EducationId) where  upsm.modulename = 'Participate' and t1.usergroup != 'cms' and t1.userid > 1000 and t1.userid not in (1130, 1156, 3895, 1014, 1020, 249142, 1045, 1318, 15, 23, 343294, 333758, 345634, 341660, 344301, 345646, 345642, 344016, 344227, 345632, 370572, 371307, 371451, 371380, 369633, 364773, 369446, 356573, 351009, 370533, 356504, 356441, 368464, 369661, 339598, 369214, 369687, 370418, 356444, 345634, 370543, 370471, 369596, 371346, 368423, 356620, 351011, 365579, 368466, 351005, 1130, 370454, 370523, 370372, 358765, 368594, 370398, 351002,1156, 344301, 1045, 345646, 355361, 355354, 370560, 368460, 369443, 365245, 370440, 370480, 370561, 345642, 371471, 370525, 371393, 371410, 358820, 369506, 369662, 370552, 369466, 370461, 370570, 371415, 371337, 371325, 356437, 345632, 369460,762290,1544835,1459752,1478915)  order by upsm.userpointvaluebymodule desc limit $start, $count";
                
        }
        else {
            $today = date("Y-m-d");
            $week = date("Y-m-d", strtotime("-7 day"));
            if($tc=="1") {
                if($catId == 1) {
                    $queryCmd="select t1.*,upsm.levelName as level, ups.userId, SUM(ups.pointvalue) userPointValue, ifnull((select count(msgId) from messageTable where userId = t1.userid and parentId != 0 and mainAnswerId=0 and status IN ('live','closed') and fromOthers='user'),0) totalAnswers from userpointsystemlog ups, tuser t1,userpointsystembymodule upsm where ups.timestamp >= '".$week."' and ups.action != 'Register' and ups.module='AnA' and t1.userid=ups.userId and upsm.userId = t1.userid and upsm.modulename='AnA' and upsm.userpointvaluebymodule>700 and t1.usergroup != 'cms' and t1.userid > 1000 and t1.userid not in (1130, 1156, 3895, 1014, 1020, 249142, 1045, 1318, 15, 23, 343294, 333758, 345634, 341660, 344301, 345646, 345642, 344016, 344227, 345632, 370572, 371307, 371451, 371380, 369633, 364773, 369446, 356573, 351009, 370533, 356504, 356441, 368464, 369661, 339598, 369214, 369687, 370418, 356444, 345634,370543, 370471, 369596, 371346, 368423, 356620, 351011, 365579, 368466, 351005, 1130, 370454, 370523, 370372, 358765, 368594, 370398, 351002, 1156, 344301, 1045, 345646, 355361, 355354, 370560, 368460, 369443, 365245, 370440, 370480, 370561, 345642, 371471, 370525, 371393, 371410, 358820, 369506, 369662, 370552, 369466, 370461, 370570, 371415, 371337, 371325, 356437, 345632, 369460,762290,1544835,1459752,1478915) group by ups.userId order by userPointValue desc limit ".$start.",".$count;
                }
                else {
		    // Category Wise show Top Contributor Query;
                    $filterUserByCategory = array();
                    //$queryByCategory = "SELECT count(*)ank, m.userId FROM tuser t, messageTable m, messageCategoryTable mct WHERE t.userId = m.userId AND m.mainAnswerId = '0' AND m.fromOthers = 'user' AND m.status IN('live', 'closed') AND m.threadId = mct.threadId AND mct.categoryId = '".$catId."' GROUP BY m.userId ORDER BY ank DESC LIMIT 0, 10";

                    //Modified by Ankur. We will show the leader board only from Users with Level Advisor or above
                    $expertUsers = '';

		    //Added for excluding Shiksha Experts and counsellors from Cafe Star widget
		    $excludedUserString = ' AND t.userid > 1000 AND t.userid not in (1130, 1156, 3895, 1014, 1020, 249142, 1045, 1318, 343294, 333758, 345634, 341660, 344301, 345646, 345642, 344016, 344227, 345632, 370572, 371307, 371451, 371380, 369633, 364773, 369446, 356573, 351009, 370533, 356504, 356441, 368464, 369661, 339598, 369214, 369687, 370418, 356444, 345634, 370543, 370471, 369596, 371346, 368423, 356620, 351011, 365579, 368466, 351005, 1130, 370454, 370523, 370372, 358765, 368594, 370398, 351002, 1156, 344301, 1045, 345646, 355361, 355354, 370560, 368460, 369443, 365245, 370440, 370480, 370561, 345642, 371471, 370525, 371393, 371410, 358820, 369506, 369662, 370552, 369466, 370461, 370570, 371415, 371337, 371325, 356437, 345632, 369460,762290,1544835,1459752,1478915) ';
                    //$queryForLevel = "select userId from userpointsystembymodule upsm where upsm.userpointvaluebymodule >= 1000 and upsm.modulename = 'AnA'";
                    $queryForLevel = "SELECT DISTINCT ups.userId FROM userpointsystemlog ups, tuser t WHERE ups.module = 'AnA'  and ups.entityId is not NULL and ups.entityId!=0 AND ups.timestamp>'".$week."' AND ups.userId = t.userid and t.usergroup != 'cms' ".$excludedUserString." AND ups.entityId IN (select mm.msgId from messageTable mm, messageCategoryTable mmc where mmc.threadId = mm.threadId and mmc.categoryId = ? and mm.msgId = ups.entityId)";
                    $queryResForLevel = $dbHandle->query($queryForLevel, array($catId));
                    foreach ($queryResForLevel->result_array() as $rowU) {
                        $expertUsers .= ($expertUsers=='')?$rowU['userId']:','.$rowU['userId'];
                    }
                    $weekCheck = true;
                    if($queryResForLevel->num_rows() < 30 && $catId>0) {
                        //If enough contributors are not found, get the users for the past 3 months
                        $timeperiod = date("Y-m-d", strtotime("-90 day"));
                        $queryForLevel = "SELECT DISTINCT ups.userId FROM userpointsystemlog ups, tuser t WHERE ups.module = 'AnA'  and ups.entityId is not NULL and ups.entityId!=0 AND ups.timestamp>'".$timeperiod."' AND ups.userId = t.userid and t.usergroup != 'cms' ".$excludedUserString." AND ups.entityId IN (select mm.msgId from messageTable mm, messageCategoryTable mmc where mmc.threadId = mm.threadId and mmc.categoryId = ? and mm.msgId = ups.entityId) LIMIT 1000";
                        $queryResForLevel = $dbHandle->query($queryForLevel,  array($catId));
                        $weekCheck = false;
                        $expertUsers = '';
                        foreach ($queryResForLevel->result_array() as $rowU) {
                            $expertUsers .= ($expertUsers=='')?$rowU['userId']:','.$rowU['userId'];
                        }

                    }

                    //Modified by Ankur. While finding the experts, we will also check if they have done any activity in the same category this week. Also, add the check for expertUsers level
                    //$queryByCategory = "SELECT count(*)ank, m.userId FROM tuser t, messageTable m, messageCategoryTable mct, userpointsystemlog ups WHERE t.userId = m.userId AND t.userId = ups.userId AND ups.module = 'AnA' AND ups.timestamp>'".$week."' AND m.mainAnswerId = '0' AND m.fromOthers = 'user' AND m.status IN('live', 'closed') AND m.threadId = mct.threadId AND mct.categoryId = '".$catId."' GROUP BY m.userId ORDER BY ank DESC LIMIT 0, 10";
                    //$checkForActivityInCategory = " ups.entityId IN (select mm.msgId from messageTable mm, messageCategoryTable mmc where mmc.threadId = mm.threadId and mmc.categoryId = '$catId' and mm.msgId = ups.entityId) AND ";
		    $checkForActivityInCategory = " ";
                    
                    if($expertUsers)
                    {
                        //$queryByCategory = "SELECT count(*)ank, m.userId FROM messageTable m, messageCategoryTable mct WHERE m.mainAnswerId = '0' AND m.fromOthers = 'user' AND m.status IN('live', 'closed') AND m.threadId = mct.threadId AND mct.categoryId = '".$catId."' and m.userId IN ($expertUsers) GROUP BY m.userId ORDER BY ank DESC LIMIT ".$start.",".$count;
                        $queryByCategory = "SELECT t.userid userId, ifnull((select count(*) FROM messageTable m, messageCategoryTable mct WHERE m.mainAnswerId = '0' AND m.fromOthers = 'user' AND m.status IN('live', 'closed') AND m.threadId = mct.threadId AND mct.categoryId = ? and m.userId = t.userid group by m.userId),0) ank from tuser t where t.userId IN ($expertUsers)  ORDER BY ank DESC LIMIT 0,30";
                        $queryRunByCategory = $dbHandle->query($queryByCategory,  array($catId));
                        foreach ($queryRunByCategory->result_array() as $rowCategroy) {
                            $filterUserByCategory[] = $rowCategroy['userId'];
                        }
                    }
                    
                    $comma_separated_userIds = implode(",", $filterUserByCategory);
                    $weeklyPointQuery = " , ifnull((select SUM(ups1.pointvalue) from userpointsystemlog ups1 where ups1.timestamp >= '".$week."' and ups1.userId = t1.userid AND ups1.action != 'Register' AND ups1.module='AnA'),0) userPointValue ";
                    $weekCheckQ = '';
                    if($weekCheck) $weekCheckQ = " ups.timestamp >= '".$week."' AND ";
                    if($count==3 && $comma_separated_userIds!='') {
                        $queryCmd="SELECT t1.*, upsm.levelName as level, ups.userId, SUM(ups.pointvalue) userPointValueCat $weeklyPointQuery, ifnull((select count(msgId) FROM messageTable WHERE userId = t1.userid AND parentId != 0 AND mainAnswerId=0 AND status IN ('live','closed') AND fromOthers='user'),0) totalAnswers FROM userpointsystemlog ups, tuser t1,userpointsystembymodule upsm  WHERE $weekCheckQ $checkForActivityInCategory ups.action != 'Register' AND ups.module='AnA' AND t1.userid=ups.userId AND upsm.userId = t1.userid AND upsm.modulename='AnA' AND t1.usergroup != 'cms' AND t1.userid IN(".$comma_separated_userIds.") group by ups.userId order by userPointValueCat desc limit ".$start.",".$count;
                    }
                    else if($comma_separated_userIds!='') {
                            $queryCmd="SELECT t1.*,upsm.levelName as level, ups.userId, SUM(ups.pointvalue) userPointValueCat $weeklyPointQuery , ifnull((select count(msgId) FROM messageTable WHERE userId = t1.userid AND parentId != 0 AND mainAnswerId=0 AND status IN ('live','closed') AND fromOthers='user'),0) totalAnswers FROM userpointsystemlog ups, tuser t1,userpointsystembymodule upsm  WHERE $weekCheckQ $checkForActivityInCategory ups.action != 'Register' AND ups.module='AnA' AND t1.userid=ups.userId AND upsm.userId = t1.userid AND upsm.modulename='AnA' AND t1.usergroup != 'cms' AND t1.userid IN(".$comma_separated_userIds.")  group by ups.userId order by userPointValueCat desc limit ".$start.",".$count;
                        }
                        else
                            $queryCmd = '';
                }
            }
            if($tp=="1") {
                $queryCmdParticipate="select t1.*,upsm.levelName as level, ups.userId, SUM(ups.pointvalue) userPointValue, ifnull((select count(*) from messageTable where userId = t1.userid and fromOthers = 'discussion' and status IN ('live','closed') and parentId=0),0) discussionPosts, ifnull((select count(*) from messageTable where userId = t1.userid and fromOthers = 'announcement' and status IN ('live','closed') and parentId=0),0) announcementPosts, ifnull((select userpointvaluebymodule from userpointsystembymodule where userId = t1.userid and modulename='Participate'),0) totalUserPointValue from userpointsystemlog ups, tuser t1,userpointsystembymodule upsm  where ups.timestamp >= '".$week."' and ups.action != 'Register' and ups.module='Participate' and t1.userid=ups.userId and upsm.userId = t1.userid and upsm.modulename='Participate' and t1.usergroup != 'cms' and t1.userid > 1000 and t1.userid not in (1130, 1156, 3895, 1014, 1020, 249142, 1045, 1318, 15, 23, 343294, 333758, 345634, 341660, 344301, 345646, 345642, 344016, 344227, 345632, 370572, 371307, 371451, 371380, 369633, 364773, 369446, 356573, 351009, 370533, 356504, 356441, 368464, 369661, 339598, 369214, 369687, 370418, 356444, 345634, 370543, 370471, 369596, 371346, 368423, 356620, 351011, 365579, 368466, 351005, 1130, 370454, 370523, 370372, 358765, 368594, 370398, 351002, 1156, 344301, 1045, 345646, 355361, 355354, 370560, 368460, 369443, 365245, 370440, 370480, 370561, 345642, 371471, 370525, 371393, 371410, 358820, 369506, 369662, 370552, 369466, 370461, 370570, 371415, 371337, 371325, 356437, 345632, 369460,762290,1544835,1459752,1478915) group by ups.userId order by userPointValue desc limit ".$start.",".$count;
            }
        }

        error_log_shiksha( 'getTopContributors query cmd is ' . $queryCmd,'qna');
        $msgArray = array();
        $msgArrayParticipate = array();
        if($tc=="1" && $queryCmd!='') {
            $query = $dbHandle->query($queryCmd);
            foreach ($query->result_array() as $row) {
                $tcUserId = $row['userid'];

             // $bestAnswers = 0;
                $rPoint = 0;

            /*    $queryCmdBA = "select count(m1.threadId) bestAnswers from messageTableBestAnsMap m1, messageTable m2 where m1.bestAnsId = m2.msgId and  m2.userId = ? and m2.status IN ('live','closed')";
                $queryBA = $dbHandle->query($queryCmdBA, array($tcUserId) );
                foreach ($queryBA->result_array() as $rowBA) {
                    $bestAnswers = $rowBA['bestAnswers'];
                }
                $row['bestAnswers'] = $bestAnswers;
            */
                
                //$query = "SELECT tp.points, upsm.userpointvaluebymodule totalPoints FROM tuserReputationPoint tp, userpointsystembymodule upsm  WHERE tp.userId=$tcUserId and tp.userId = upsm.userId AND upsm.modulename='AnA'";
                $query = "SELECT upsm.userpointvaluebymodule totalPoints FROM userpointsystembymodule upsm  WHERE upsm.userId=? AND upsm.modulename='AnA'";
                $result = $dbHandle->query($query,array($tcUserId));
                foreach ($result->result_array() as $rows) {
                    $totalPoint = $rows['totalPoints'];
                }
                $row['totalPoints'] = $totalPoint;
                if($count==3) {
                    $query = "SELECT count(*) likes FROM digUpUserMap d1, messageTable m1  WHERE m1.userId=? and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId = d1.productId and d1.digFlag = 1 and d1.digUpStatus='live'";
                    $result = $dbHandle->query($query,array($tcUserId));
                    foreach ($result->result_array() as $rows) {
                        $likes = $rows['likes'];
                    }
                    $row['likes'] = $likes;

                    //Get the User expertise categories from his answers
                    //$queryCmd = "select count(*) answerCount, c1.name, c1.boardId from messageTable m1, messageCategoryTable mc1, categoryBoardTable c1 where m1.userId=$tcUserId and m1.fromOthers='user' and m1.status IN ('live','closed') and m1.threadId = mc1.threadId and m1.parentId!=0 and m1.mainAnswerId=0 and mc1.categoryId=c1.boardId and c1.parentId = 1  group by c1.name order by answerCount desc limit 2";
		    $year = date("Y-m-d", strtotime("-365 day"));
		    $queryCmd = "select count(*) answerCount, mc1.categoryId from messageTable m1, messageCategoryTable mc1 where m1.userId=? and m1.fromOthers='user' and m1.status IN ('live','closed') and m1.threadId = mc1.threadId and m1.parentId!=0 and m1.mainAnswerId=0 and mc1.categoryId IN (2,3,4,5,6,7,8,9,10,11,12,13,14) and m1.creationDate > '".$year."' group by mc1.categoryId order by answerCount desc limit 2";
                    $query = $dbHandle->query($queryCmd, array($tcUserId));
                    $expertize = '';
                    foreach ($query->result_array() as $rowTemp) {
			$queryCmdName = "select name from categoryBoardTable c1 where boardId = ?";
			$queryName = $dbHandle->query($queryCmdName, array($rowTemp['categoryId']));
			$rowName = $queryName->row();
			$name = $rowName->name;
                        $expertize .= ($expertize=='')?$name:", ".$name;
                    }
                    $row['expertize'] = $expertize;

                    //Get the User's VCard Information
                    //$queryV = "select v.* from VCardInfo v where v.userid = $tcUserId";
                    $queryV = "select v.* from expertOnboardTable v where v.userid = ? and v.status = 'Live'";
                    $queryR = $dbHandle->query($queryV, array($tcUserId));
                    foreach ($queryR->result_array() as $rowTempV) {
                        $row['userName'] = $rowTempV['userName'];
                        $row['designation'] = $rowTempV['designation'];
                        $row['instituteName'] = $rowTempV['instituteName'];
                        $row['highestQualification'] = $rowTempV['highestQualification'];
                        $row['imageURL'] = $rowTempV['imageURL'];
                        $row['aboutMe'] = $rowTempV['aboutMe'];
                        $row['aboutCompany'] = $rowTempV['aboutCompany'];
                    }

                }
                array_push($msgArray,array($row,'struct'));
            }
        }
        if($tp=="1") {
            $queryParticipate = $dbHandle->query($queryCmdParticipate);
            foreach ($queryParticipate->result_array() as $row) {
                array_push($msgArrayParticipate,array($row,'struct'));
            }
        }
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'mostcontributing'=>array($msgArray,'struct'),
            'mostcontributingParticipate'=>array($msgArrayParticipate,'struct'),
            ),'struct')
        );//close array_push
        $response=array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	get tree reply for a given topic id for a board id $appID,$board_id,$topic_id Used in detail page
     */
    function getMsgTree($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadNo=$parameters['1'];
        //connect DB
        //$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle($threadNo);
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $giveMeQuestion=$parameters['4'];
        $userId = $parameters['5'];
        $userGroup = $parameters['6'];
        $filter = $parameters['7'];
        $pageName = $parameters['8'];
        $referenceEntityId = $parameters['9'];

        if($threadNo <= 0 || $threadNo == ''){
                $response = array(array('Result'=>'Failed'),'struct');
                return $this->xmlrpc->send_response($response);
        }

        $mainArr = array();
        $alreadyAnsweredQuery = "";
        $pickedAsEditorial = "";
        $tempArr = array();
        $newProductArr = array('discussion','announcement','review','eventAnA');
        if(($userId > 0) && ($startFrom == 0)) {
            $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$userId." and m2.threadId = ".$threadNo." limit 1),0) alreadyAnswered";
            if($userGroup == 'cms') {
                $pickedAsEditorial = " , ifnull((select 1 from editorPick ed where ed.ProductId = m1.msgId and ed.ProductType = 'qna' and ed.status = 'live'),0) editorPickFlag";
            }
        }
        //Get message tree
        if($userId > 0)
            $getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=m1.msgId and ral.userId=".$userId."),0) reportedAbuse ";

        //Get message tree

        //This query gets the main question
        $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') LIMIT 1)) listingTitle,t.displayname, t.firstname,t.lastname,  t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery $pickedAsEditorial $getReportedAbuse from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId=".$threadNo;
        error_log_shiksha( 'getMsgTree query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        $mainAnswerUserIdCsv = '';
        $isAnACall = false;
        $product = 'user';
        $i=0;
        foreach ($query->result_array() as $row) {
            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$row['userId']:','.$row['userId'];
            $product = $row['fromOthers'];
            //Start: Code added by Ankur on 10 March to make the institute names as URL in SHiksha Cafe
            $row['instituteurl'] = '';
            if($row['listingTypeId']>0) {
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $row['instituteurl'] = $ListingClientObj->getCorrectSeoURL($appID,$row['listingTypeId'],'institute');
            }
            //End: Code added by Ankur on 10 March to make the institute names as URL in SHiksha Cafe
            if($product == 'user') $isAnACall = true;
            //array_push($msgArray,array($row,'struct'));
            $msgArray[$i] = $row;
            $i++;
        }

        if(!in_array($product,$newProductArr) && $pageName != 'questionDetailPage') {
            if($startFrom == 0) {
                $count++;
            }else {
                $startFrom++;
            }
        }
        else{	//In case of discussion, annoucnements, the count of comments will be 20.
            if($pageName != 'questionDetailPage')
            $count = 20;
        }

        if($pageName != 'discussionDetailPage' && $pageName != 'questionDetailPage')
        {
        //This query gets the answers to the question
        if(in_array($product,$newProductArr)){
            //$queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused'))) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery $pickedAsEditorial,if(m1.msgId=".$threadNo.",200,if((m1.userId = ".$userId." ),0,ifnull((select 180 from listings_main lmain where m1.userId = lmain.username and lmain.listing_type_id IN (select listingTypeId from messageTable mT where mT.threadId=".$threadNo." and mT.parentId = 0) and lmain.listing_type IN (select listingType from messageTable mTb where mTb.threadId=".$threadNo." and mTb.parentId = 0) and lmain.status IN ('live')),ifnull((select 100 from messageTableBestAnsMap mbam where mbam.threadId = m1.threadId and m1.msgId = mbam.bestAnsId ),ifnull((select 90 from tuser T1 where T1.userid = m1.userId and T1.usergroup = 'experts'),ifnull((select (m1.digUp - m1.digDown) from messageTable mtd where mtd.threadId=$threadNo and mtd.msgId=m1.msgId and mtd.parentId!=0),0)))))) sortFlag,ifnull((select 1 from messageTableBestAnsMap mbam where mbam.threadId = m1.threadId and m1.msgId = mbam.bestAnsId),0) bestAnsFlag, ifnull((select description from messageDiscussion md1, messageTable md where md.msgId = md1.threadId and md.msgId = m1.msgId),'') description ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.threadId=".$threadNo." and ((parentId = threadId) OR (parentId = 0)) order by sortFlag desc,m1.creationDate desc , path asc";
           $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,t.displayname, t.firstname,t.lastname, t.lastlogintime,t.userid userId,t.avtarimageurl userImage,if(m1.msgId=?,200,if((m1.userId = ? ),0,ifnull((select 90 from tuser T1 where T1.userid = m1.userId and T1.usergroup = 'experts'),0))) sortFlag,0 bestAnsFlag, ifnull((select description from messageDiscussion md1, messageTable md where md.msgId = md1.threadId and md.msgId = m1.msgId),'') description ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.threadId=? and ((parentId = threadId) OR (parentId = 0)) order by sortFlag desc,m1.creationDate desc , path asc";
	   $query = $dbHandle->query($queryCmd, array($threadNo,$userId,$threadNo));
           $tempArr = $query->result_array();
	}
        else {
        //Changing the answer query as per the reputation of the user
        //$queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused'))) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery $pickedAsEditorial,if(m1.msgId=".$threadNo.",200,if((m1.userId = ".$userId." ),0,ifnull((select 180 from listings_main lmain where m1.userId = lmain.username and lmain.listing_type_id IN (select listingTypeId from messageTable mT where mT.threadId=".$threadNo." and mT.parentId = 0) and lmain.listing_type IN (select listingType from messageTable mTb where mTb.threadId=".$threadNo." and mTb.parentId = 0) and lmain.status IN ('live')),ifnull((select 100 from messageTableBestAnsMap mbam where mbam.threadId = m1.threadId and m1.msgId = mbam.bestAnsId ),ifnull((select 90 from tuser T1 where T1.userid = m1.userId and T1.usergroup = 'experts'),ifnull((select (m1.digUp - m1.digDown) from messageTable mtd where mtd.threadId=$threadNo and mtd.msgId=m1.msgId and mtd.parentId!=0),0)))))) sortFlag,ifnull((select 1 from messageTableBestAnsMap mbam where mbam.threadId = m1.threadId and m1.msgId = mbam.bestAnsId),0) bestAnsFlag ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.threadId=".$threadNo." and ((parentId = threadId) OR (parentId = 0)) order by sortFlag desc,m1.creationDate desc , path asc LIMIT ".$startFrom." , ".$count;
            if($filter == 'reputation')
                $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') LIMIT 1)) listingTitle,t.displayname, t.firstname,t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery $pickedAsEditorial,
				      if(m1.msgId=".$threadNo.",200000, ifnull((select 180000 from listings_main lmain where m1.userId = lmain.username and lmain.listing_type_id IN (select listingTypeId from messageTable mT where mT.threadId=".$threadNo." and mT.parentId = 0) and lmain.listing_type IN (select listingType from messageTable mTb where mTb.threadId=".$threadNo." and mTb.parentId = 0) and lmain.status IN ('live')),
				      ifnull((select 90000 from tuser T1 where T1.userid = m1.userId and T1.usergroup = 'experts'),   ifnull((select points from messageTable mtd, tuserReputationPoint tp where mtd.threadId=$threadNo and mtd.msgId=m1.msgId and mtd.parentId!=0 and mtd.userId=tp.userId) ,10 )))) sortFlag,
				      ifnull( (select (m1.digUp - m1.digDown) from messageTable mtd where mtd.threadId=$threadNo and mtd.msgId=m1.msgId and mtd.parentId!=0) ,0) ansRating,
				      ifnull( (select points from messageTable mtd, tuserReputationPoint tp where mtd.threadId=$threadNo and mtd.msgId=m1.msgId and mtd.parentId!=0 and mtd.userId=tp.userId) ,10) reputation,
				      0 bestAnsFlag ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.threadId=? and m1.status IN ('live','closed') and ((parentId = threadId) OR (parentId = 0)) order by sortFlag desc, ansRating desc, m1.creationDate desc , path asc LIMIT $startFrom,$count";
            else if($filter == 'freshness')
                    $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') LIMIT 1)) listingTitle,t.displayname, t.firstname,t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery $pickedAsEditorial,if(m1.msgId=".$threadNo.",200000,ifnull((select 180000 from listings_main lmain where m1.userId = lmain.username and lmain.listing_type_id IN (select listingTypeId from messageTable mT where mT.threadId=".$threadNo." and mT.parentId = 0) and lmain.listing_type IN (select listingType from messageTable mTb where mTb.threadId=".$threadNo." and mTb.parentId = 0) and lmain.status IN ('live')),ifnull((select 90000 from tuser T1 where T1.userid = m1.userId and T1.usergroup = 'experts'),0)))) sortFlag,ifnull((select 1 from messageTableBestAnsMap mbam where mbam.threadId = m1.threadId and m1.msgId = mbam.bestAnsId),0) bestAnsFlag, ifnull( (select points from messageTable mtd, tuserReputationPoint tp where mtd.threadId=$threadNo and mtd.msgId=m1.msgId and mtd.parentId!=0 and mtd.userId=tp.userId) ,10) reputation ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.threadId=? and m1.status IN ('live','closed') and ((parentId = threadId) OR (parentId = 0)) order by sortFlag desc,m1.creationDate desc , path asc LIMIT $startFrom,$count";
                else if($filter == 'rating')
                        $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') LIMIT 1)) listingTitle,t.displayname, t.firstname,t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery $pickedAsEditorial,if(m1.msgId=".$threadNo.",200000,ifnull((select 180000 from listings_main lmain where m1.userId = lmain.username and lmain.listing_type_id IN (select listingTypeId from messageTable mT where mT.threadId=".$threadNo." and mT.parentId = 0) and lmain.listing_type IN (select listingType from messageTable mTb where mTb.threadId=".$threadNo." and mTb.parentId = 0) and lmain.status IN ('live')),ifnull((select 90000 from tuser T1 where T1.userid = m1.userId and T1.usergroup = 'experts'),ifnull((select (m1.digUp - m1.digDown) from messageTable mtd where mtd.threadId=$threadNo and mtd.msgId=m1.msgId and mtd.parentId!=0),0))))) sortFlag,ifnull((select 1 from messageTableBestAnsMap mbam where mbam.threadId = m1.threadId and m1.msgId = mbam.bestAnsId),0) bestAnsFlag, ifnull((select points from messageTable mtd, tuserReputationPoint tp where mtd.threadId=$threadNo and mtd.msgId=m1.msgId and mtd.parentId!=0 and mtd.userId=tp.userId) ,10) reputation ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.threadId=? and m1.status IN ('live','closed') and ((parentId = threadId) OR (parentId = 0)) order by sortFlag desc,m1.creationDate desc , path asc LIMIT $startFrom,$count";

	    $query = $dbHandle->query($queryCmd, array($threadNo));
            $tempArr = $query->result_array();
        }
        }else{
            $this->load->library('v1/AnACommonLib');
            $anaCommonLib = new AnACommonLib();
            if($pageName == 'discussionDetailPage')
            {
                $ana_api_data = $anaCommonLib->formatDiscussionDetailPageData($threadNo,$userId,$startFrom,$count,$filter,$referenceEntityId);
                $msgArray[] = $ana_api_data['entityDetails'];
            }else if($pageName == 'questionDetailPage')
            {
                $ana_api_data = $anaCommonLib->formatQuestionDetailPageData($userId,$threadNo,$startFrom,$count,$filter,$referenceEntityId);
                foreach($ana_api_data['childDetails'] as $val)
                {
                    $msgArray[] = $val;
                }
            }
        }
        error_log_shiksha( 'getMsgTree query cmd is ' . $queryCmd,'qna');
        $mainAnswerIdCsv = '';
        if($pageName != 'discussionDetailPage' && $pageName != 'questionDetailPage')
        {
            foreach ($tempArr as $row) {
                if(in_array($product,$newProductArr)){
                    $msgIdExpected = array(intval($row['threadId'])+1,intval($row['threadId'])+2,intval($row['threadId'])+3);
                    if($row['parentId']!=0 && !in_array($row['msgId'],$msgIdExpected) ){
                            continue;
                    }
                }
    
                if($row['parentId'] != 0) {
                    $mainAnswerIdCsv .= ($mainAnswerIdCsv == '')?$row['msgId']:','.$row['msgId'];
                    //array_push($msgArray,array($row,'struct'));
                    $msgArray[$i] = $row;
                    $i++;
                }
                if($row['userId']!='')
                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$row['userId']:','.$row['userId'];
            }
        }else{
            foreach ($msgArray as $row) {
                if(in_array($product,$newProductArr)){
                    $msgIdExpected = array(intval($row['threadId'])+1,intval($row['threadId'])+2,intval($row['threadId'])+3);
                    if($row['parentId']!=0 && !in_array($row['msgId'],$msgIdExpected) ){
                            continue;
                    }
                }
                
                if($row['parentId'] != 0) {
                    $mainAnswerIdCsv .= ($mainAnswerIdCsv == '')?$row['msgId']:','.$row['msgId'];
                }
                if($row['userId']!='')
                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$row['userId']:','.$row['userId'];
            }
        }

        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $dbHandle->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
            $totalRows = $row->totalRows;
        }
	
	//For discussions and announcements, we need the Hide/unhide functionality for CMS users. For this, we will also fetch the Deleted comments
	$statusToExclude = '"deleted","abused"';
        $this->load->library('AnAConfig');
        if(in_array($userId,AnAConfig::$userIds)){
		$statusToExclude = '"abused"';
	}
	
        $totalComments = 0;
        if(!($isAnACall)) {
            $queryCmd = 'SELECT count(*) totalComments from messageTable where threadId = ? and status NOT IN ('.$statusToExclude.') and parentId != 0';
            $query = $dbHandle->query($queryCmd, array($threadNo));
            foreach ($query->result() as $row) {
                $totalComments = $row->totalComments;
            }
        }else {
            $queryCmd = 'SELECT count(*) totalComments from messageTable where threadId = ? and status NOT IN ("deleted","abused") and parentId = threadId';
            $query = $dbHandle->query($queryCmd, array($threadNo));
            foreach ($query->result() as $row) {
                $totalComments = $row->totalComments;
            }
        }
        $msgArray3 = array();
        if($mainAnswerIdCsv != '') {
            if(in_array($product,$newProductArr))
                $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,t.displayname, t.firstname,t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage, (select t2.displayname from tuser t2, messageTable m2 where m2.userId = t2.userid and m1.parentId = m2.msgId) parentDisplayName, (select t2.firstname from tuser t2, messageTable m2 where m2.userId = t2.userid and m1.parentId = m2.msgId) parentFirstName, (select t2.lastname from tuser t2, messageTable m2 where m2.userId = t2.userid and m1.parentId = m2.msgId) parentLastName, (select t2.userid from tuser t2, messageTable m2 where m2.userId = t2.userid and m1.parentId = m2.msgId) parentDisplayId ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.threadId = ? and  m1.mainAnswerId in (".$mainAnswerIdCsv.") and m1.status NOT IN (".$statusToExclude.") order by m1.creationDate ASC LIMIT $startFrom , $count";
            else
                $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,t.displayname, t.firstname,t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage, (select t2.displayname from tuser t2, messageTable m2 where m2.userId = t2.userid and m1.parentId = m2.msgId) parentDisplayName, (select t2.firstname from tuser t2, messageTable m2 where m2.userId = t2.userid and m1.parentId = m2.msgId) parentFirstName, (select t2.lastname from tuser t2, messageTable m2 where m2.userId = t2.userid and m1.parentId = m2.msgId) parentLastName, (select t2.userid from tuser t2, messageTable m2 where m2.userId = t2.userid and m1.parentId = m2.msgId) parentDisplayId ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.threadId = ? and  m1.mainAnswerId in (".$mainAnswerIdCsv.") and m1.status IN ('live','closed') order by m1.creationDate ASC";
error_log("9april:::".$queryCmd);

            $Result = $dbHandle->query($queryCmd, array($threadNo));
            $i=0;
            foreach($Result->result_array() as $row) {
            //$msgArray3[$row['mainAnswerId']] = array($row,'struct');
            //array_push($msgArray3 ,array($row,'struct'));
                $msgArray3[$i] = $row;
                $i++;
                if($row['userId']!='')
                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$row['userId']:','.$row['userId'];
            }

            if(in_array($product,$newProductArr)) {
                $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
                $query = $dbHandle->query($queryCmd);
                foreach ($query->result() as $row) {
                    $totalRows = $row->totalRows;
                }
            }

        }

        $this->load->model('QnAModel');
        $msgArrayLevelVcard = array();
        $msgArrayLevelVcard = $this->QnAModel->getLevelAndVCardStausForUser($dbHandle,$mainAnswerUserIdCsv,true,true,true);
        $msgArrayLevelVcard = is_array($msgArrayLevelVcard)?$msgArrayLevelVcard:array();
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadNo,true,true,true);
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
        $msgArrayExpert = array();
        $msgArrayExpert = $this->QnAModel->checkIfAnAExpert($dbHandle,$mainAnswerUserIdCsv,true);
        $msgArrayExpert = is_array($msgArrayExpert)?$msgArrayExpert:array();
        $answerSuggestions = $this->QnAModel->getSuggestedInstitutes($mainAnswerIdCsv,true,$dbHandle); 
        $answerSuggestions = is_array($answerSuggestions)?$answerSuggestions:array(); 
        
        $queryCmd = "select count(msgId) mainAnsCount,(select group_concat(categoryId) from messageCategoryTable mcat where mcat.threadId = ?) CategoryIds from messageTable M1 where M1.threadId = ? and M1.parentId =  ? and M1.fromOthers = 'user'";
        error_log_shiksha( 'getMsgTree MainAnsCount query cmd is ' . $queryCmd,'qna');
        $Result = $dbHandle->query($queryCmd,array($threadNo,$threadNo,$threadNo));
        $mainAnsCount = 0;
        foreach($Result->result_array() as $row) {
            $mainAnsCount = $row['mainAnsCount'];
            $CategoryIds = explode(',',$row['CategoryIds']);
        }
        sort($CategoryIds,SORT_NUMERIC);
        $CategoryIds =  array_slice($CategoryIds,1,count($CategoryIds)-1);
        $msgArray1 = array();
        if($giveMeQuestion == 1) {
            $queryCmd = "select m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') LIMIT 1)) listingTitle,ifnull((select 1 from messageTableBestAnsMap where threadId = ? LIMIT 1),0) bestAnsFlag,t1.displayname, t1.firstname,t1.lastname,t1.lastlogintime,t1.userid userid,t1.avtarimageurl userImage from messageTable m1, tuser t1 where m1.msgId = ? and m1.parentId = 0 and m1.fromOthers = 'user' and m1.status in('live','closed') and m1.userId = t1.userid ";
            error_log_shiksha( 'getMsgTree give query cmd is ' . $queryCmd,'qna');
            $Result = $dbHandle->query($queryCmd,array($threadNo,$threadNo));
            $i=0;
            foreach ($Result->result_array() as $row) {
            //array_push($msgArray1,array($row,'struct'));
                $msgArray1[$i] = $row;
                $i++;
            }
        }

	//The code for the QnAMasterList table needs to be executed only in case of question detail page
	if(!in_array($product,$newProductArr)){
        //code to check the presence in qnaMasterListTable
        $queryCmd = "SELECT count(*) as total from qnaMasterQuestionTable where msgId = ? and status = 'live'";
        $query =  $dbHandle->query($queryCmd,array($threadNo));
        $total ='0';
        foreach($query->result_array() as $row) {
            $total = $row['total'];
        }
                /***********************************************************************************************/
        ////QnA Rehash Phase-2 Start code to check entry in questionEditTileLog table for showing Edit
        //title link on question detail page
                /***********************************************************************************************/
        $showEditLink = '';
        $showEditLinkOnQDetailQuery = "select * from messageTable as m1,qnaMasterQuestionTable qmqt where m1.msgId not in (select distinct(msgId) from questionEditTitleLog where `flag`!='deleted') and length( REPLACE( REPLACE( REPLACE(msgTxt, '&gt;','1') , '&quot;','1'), '&amp;','1') )>140 and m1.msgId=? and (m1.msgId=qmqt.msgId and qmqt.status='live' )";
        $queryR =  $dbHandle->query($showEditLinkOnQDetailQuery,array($threadNo));
        $rowNum     = $queryR->num_rows();
        if($rowNum>0) {
            $showEditLink = 'true';
        }
        else {
            $showEditLink = 'false';
        }
	}
	else{
	    $showEditLink = 'false';
	}
                /***********************************************************************************************/
        ////QnA Rehash Phase-2 End code to check entry in questionEditTileLog table for showing Edit title
        //  link on question detail page
                /***********************************************************************************************/
        //Modified for Shiksha performance task on 8 March
		/*array_push($mainArr,array(
									array(
											'MsgTree'=>array($msgArray,'struct'),
											'Replies'=>array($msgArray3,'struct'),
											'MainQuestion'=>array($msgArray1,'struct'),
											'mainAnsCount' => array($mainAnsCount,'int'),
											'CategoryIds' => array($CategoryIds,'struct'),
											'totalComments' => array($totalComments,'string'),
											'totalRows' => array($totalRows,'string'),
											'levelVCard' => array($msgArrayLevelVcard,'struct'),
											'categoryCountry' => array($msgArrayCatCountry,'struct'),
                                                                                        'isMasterList' => array($total,'string')
										 ),
							'struct'));//close array_push
		$response = array($mainArr,'struct');*/
        $mainArr = array();
                /***********************************************************************************************/
        ////QnA Rehash Phase-2 Start code Status for showing Edit title link on question detail page
                /***********************************************************************************************/
        $mainArr[0]['showEditLink'] = $showEditLink;
                /***********************************************************************************************/
        ////QnA Rehash Phase-2 End code Status for showing Edit title link on question detail page
                /***********************************************************************************************/
        $mainArr[0]['MsgTree'] = $msgArray;
        
        if($pageName == 'discussionDetailPage'){ // prepare reply from ana discussion api
            $mainArr[0]['Replies'] = $ana_api_data['childDetails'];    
        }else{
            $mainArr[0]['Replies'] = $msgArray3;
        }
        
        $mainArr[0]['MainQuestion'] = $msgArray1;
        $mainArr[0]['mainAnsCount'] = $mainAnsCount;
        $mainArr[0]['CategoryIds'] = $CategoryIds;
        $mainArr[0]['totalComments'] = $totalComments;
        $mainArr[0]['totalRows'] = $totalRows;
        $mainArr[0]['levelVCard'] = $msgArrayLevelVcard;
        $mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
        $mainArr[0]['isMasterList'] = $total;
        $mainArr[0]['expertArray'] = $msgArrayExpert;
        $mainArr[0]['answerSuggestions'] = $answerSuggestions; 

        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }
    /**
     * Edit the comment details.
     */
    function editMsgDetails($request) {
        $parameters = $request->output_parameters();
        $appID=1;
        $appID=$parameters['0'];
        $detailArray=$parameters['1'];
        $userId=$parameters['2'];
        //connect DB
        $this->load->model('qnamodel');
        $dbHandle = $this->_loadDatabaseHandle('write');
	
	//Do not check Campus Rep feature and conditions in case of Mobile App
        global $isMobileApp;
        if($isMobileApp){
		$checkCR = 0;
        }
	else{
	        $checkCR = $this->qnamodel->checkIfUserIsRegisteredCampusRep($userId);
        	$queryToExe = "select userId,digUp,digDown,0 answerId,ifnull((select count(*) from messageTable where parentId = ? and fromOthers='user' and status not in ('deleted','abused')),0) noOfRepliefToAnswer from messageTable where msgId = ?";
	        $Result = $dbHandle->query($queryToExe, array ($detailArray['msgId'],$detailArray['msgId']) );
        	$row = $Result->row();
	        if($checkCR != 1 && (($row->userId != $userId) || ($row->digUp > 0) || ($row->digDown > 0) || ($row->answerId > 0) || ($row->noOfRepliefToAnswer > 0))) {
        	    if($row->userId != $userId) {
                	$response = array(array('Result' => 'OUCE'),'struct'); //Other user comment error.
	            }elseif(($row->digUp > 0) || ($row->digDown > 0)) {
        	        $response = array(array('Result' => 'ARAE'),'struct'); //Already rated answer error.
	            }elseif($row->answerId > 0) {
        	        $response = array(array('Result' => 'SABA'),'struct'); //Selected as a best answer.
	            }elseif($row->noOfRepliefToAnswer > 0) {
        	        $response = array(array('Result' => 'ARPAE'),'struct'); //Already replied answer error.
	            }
        	    return $this->xmlrpc->send_response($response);
	        }else if($checkCR != 1){
        	    if($row->userId != $userId) {
                	$response = array(array('Result' => 'OUCE'),'struct');//Other user comment error.
	                return $this->xmlrpc->send_response($response);
        	        
	            }elseif(($row->digUp > 0) || ($row->digDown > 0)) {
        	        $response = array(array('Result' => 'ARAE'),'struct'); //Already rated answer error.
                	return $this->xmlrpc->send_response($response);
	                
        	    }elseif($row->answerId > 0) {
                	$response = array(array('Result' => 'SABA'),'struct'); //Selected as a best answer.
	                return $this->xmlrpc->send_response($response);
        	    }
        	}
	}
        //Get message tree
        $msgTxt = $detailArray['msgTxt'];
        $dataToUpdate = array('msgTxt' =>htmlspecialchars($msgTxt),'requestIP'=>$detailArray['requestIP']);
        $where = "msgId = ".$detailArray['msgId'];
        $query = $dbHandle->update_string('messageTable',$dataToUpdate,$where);
        $dbHandle->query($query);
        $listingTypeID=$this->qnamodel->getListingTypeId($userId,$detailArray['msgId']);
        $CRWalletStatus = $this->qnamodel->getStatusFromWalletTable($userId,$detailArray['msgId']);
        if($checkCR==1 && $CRWalletStatus[0]['status'] != 'earned' && $listingTypeID[0]['listingTypeId'] > 0 ){
            //Insert a new entry in Wallet Log table with the Edited answer amount
            $createDate =  $this->qnamodel->getQuestionCreationDate($detailArray['msgId']);
            $timeDiff = (strtotime(date('Y-m-d H:i:s')) - strtotime($createDate));
            $this->load->library('CA/Mywallet');
            $isCaEng = $this->mywallet->makeIncentive($userId);
            $money = getCREarning($timeDiff,$isCaEng[$userId]);
            $reward = $money['money'];
            if($CRWalletStatus[0]['status'] !='delete'){
                $this->qnamodel->updateCRWalletStatus($userId,$detailArray['msgId'],$reward);
            }else{
                $this->qnamodel->addInWallet($userId,$detailArray['msgId'],$reward);
            }
            //Update the status of this answer to Draft so that it is again sent for Moderation
            $this->qnamodel->updateDisapprovedAnswerStatus($detailArray['msgId']);
        }
        
        //Add notification of APP in redis
        //$this->load->model('messageBoard/AnAModel');
        //$moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);
                
        //if($user_id == $moderatorUserId){
                /*$this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
                $this->appNotification->addNotificationForAppInRedis('edit_answer',$detailArray['threadId'],'question',$userId,'answer',$detailArray['msgId']);
                */
        //}
        
        $response = array(array('Result'=>'Edited'),'struct'); //comment edited.
        return $this->xmlrpc->send_response($response);
    }

    /**
     * This give the details of comment/message.
     */
    function getMsgDetails($request) {
        $parameters = $request->output_parameters();
        $appID=1;
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $mainArr = array();

        //Get message tree
        $queryCmd = "select * from messageTable where msgId = ?";
        error_log_shiksha( 'getMsgDetails query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($userId));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }

        array_push($mainArr,array(
            array(
            'MsgDetails'=>array($msgArray,'struct')
            ),
            'struct'));//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }
	/*
	* this method gives feed to alert. Gives alert feed to alert module when a new thread in a category is inserted.
	* They call this method by cron to get feeds
	*/
    function getMessageBoardFeeds($request) {
        $parameters = $request->output_parameters();
        $appID=1;
        $startDate=$parameters['0'];
        $endDate=$parameters['1'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = "select m1.msgTxt as msgTitle,m1.threadId,m2.categoryId,m3.countryId from messageTable m1, messageCategoryTable m2, messageCountryTable m3 where m1.threadId=m2.threadId and m1.threadId=m3.threadId and m1.status in ('live','closed') and m1.parentId=0 and m1.creationDate>=? and m1.creationDate<=?";

        error_log_shiksha( 'getMessageBoardFeeds query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($startDate,$endDate));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTitle']);
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }


	/*
	* this method gives comment feed to alert. Gives comment feed to alert.
	*/
    function getMessageBoardCommentFeeds($request) {
        $parameters = $request->output_parameters();
        $appID=1;
        $startDate=$parameters['0'];
        $endDate=$parameters['1'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = "select m1.threadId,m1.msgTxt,m2.msgTxt as msgTitle from messageTable m1, (select msgTxt,threadId from messageTable where fromOthers='user' and parentId=0 and status in ('live','closed')) m2 where m1.threadId=m2.threadId and m1.parentId = m1.threadId and m1.status in ('live','closed') and m1.creationDate>=? and m1.creationDate<=?";

        error_log_shiksha( 'getMessageBoardCommentFeeds query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($startDate,$endDate));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTitle']);
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

	/*
	* update countries for given threads. Not used anymore
	*/
    function updateCountry($request) {
        $parameters = $request->output_parameters();
        $updateArray=json_decode($parameters['0'],true);
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        //update country
        for($i = 0 ; $i < count($updateArray); $i++) {
            $queryCmd = 'insert into messageCountryTable (threadId,countryId) values (?,?) on duplicate key update messageCountryTable.threadId = ?';
            $query = $dbHandle->query($queryCmd,array($updateArray[$i]['threadId'],$updateArray[$i]['countryId'],$updateArray[$i]['threadId']));
        }
        return $this->xmlrpc->send_response(array('1','string'));
    }


    function checkInQuestionLog($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
	$dbHandle = $this->_loadDatabaseHandle();

        //$queryCmdDesc = "select md.description,qetg.msgTitle from messageDiscussion as md ,questionEditTitleLog as qetg where md.threadId = qetg.msgId and md.threadId=$msgId";
        $queryCmdDesc = "select qetg.msgDesc,qetg.msgTitle from questionEditTitleLog as qetg where qetg.msgId=? and `flag`!='deleted'";
        $query = $dbHandle->query($queryCmdDesc,array($msgId));
        $resCmdDesc = $query->row();
        $rowNum     = $query->num_rows();
        $msgArr = array();
        if($rowNum) {
            $msgArr[msgTitle] = $resCmdDesc->msgTitle;
            $msgArr[description] = $resCmdDesc->msgDesc;
        }else {
            $msgArr[msgTitle] = '';
            $msgArr[description] = '';
        }

        $response = json_encode(array($msgArr,'struct'));
        return $this->xmlrpc->send_response($response);

    }


    function updateTitle($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $msgId=$parameters['2'];
        $questionUserId=$parameters['3'];
        $msgTitle=addslashes($parameters['4']);
        $msgDescription=$parameters['5'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        //  $this->load->model('UserPointSystemModel');
        // $res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$userId);
        $queryCmdUserGrp = "select usergroup from tuser where userid=?";
        $queryUserGrp = $dbHandle->query($queryCmdUserGrp, array($userId));
        $resUserGrp = $queryUserGrp->row();
        $queryCmdDesc = "select qetg.msgTitle from questionEditTitleLog as qetg where qetg.msgId=?";
        $query = $dbHandle->query($queryCmdDesc, array($msgId));
        $rowNum     = $query->num_rows();

        if($resUserGrp->usergroup == 'cms') {
            if(!$rowNum) {
                $queryCmd1 = 'insert into questionEditTitleLog (`msgId`,`msgTitle`,`userId`,`questionUserId`,`flag`,`msgDesc`)values (?,?,?,?,"live",?)';
                $query1 = $dbHandle->query($queryCmd1,array($msgId,$msgTitle,$userId,$questionUserId,$msgDescription));

            }else {
            //$queryCmd1 = 'update questionEditTitleLog set `msgTitle`="'.$msgTitle.'",`userId`="'.$userId.'",`flag`="live",`editDate`= NOW() where `msgId`="'.$msgId.'"';
            //$queryCmd1 = 'update questionEditTitleLog set `msgTitle`="'.$msgTitle.'",`flag`="live",`editDate`= NOW() where `msgId`="'.$msgId.'"';
            //$query1 = $dbHandle->query($queryCmd1);
                $queryCmd5 = "select msgId from questionEditTitleLog where `msgId`=? and `flag`='deleted'";
                $query5 = $dbHandle->query($queryCmd5,array($msgId));
                $rowNo = $query5->num_rows();
                if($rowNo) {
                    $queryCmd4 = 'update questionEditTitleLog set `msgTitle`=?,`flag`="live",`editDate`= NOW() where `msgId`=?';
                    $query4 = $dbHandle->query($queryCmd4,array($msgTitle,$msgId));
                //$queryCmd6 = 'update messageDiscussion set `description`="'.$msgDescription.'" where `threadId`='.$msgId;
                //$query6 = $dbHandle->query($queryCmd6);
                //error_log("query 6===".print_r($queryCmd6,true));
                }else {
                    $response = json_encode(array('haveValue','struct'));
                    return $this->xmlrpc->send_response($response);
                }

            }
            $queryCmd2 = 'update messageTable set msgTxt=? where msgId=?';
            $query2 = $dbHandle->query($queryCmd2,array($msgTitle,$msgId));
            $queryCmd3 = 'insert into messageDiscussion (`threadId`,`description`)values (?,?)';
            $query3 = $dbHandle->query($queryCmd3, array($msgId,$msgDescription));
                   /* if(!$rowNum){
                    $queryCmd3 = 'insert into messageDiscussion (`threadId`,`description`)values ("'.$msgId.'","'.$msgDescription.'")';
                    $query3 = $dbHandle->query($queryCmd3);
                    }*/

        }else {
            if(!$rowNum) {
                $queryCmd1 = 'insert into questionEditTitleLog (`msgId`,`msgTitle`,`userId`,`questionUserId`,`msgDesc`) values (?,?,?,?,?)';
                $query1 = $dbHandle->query($queryCmd1,array($msgId,$msgTitle,$userId,$questionUserId,$msgDescription));
            }else {
                $queryCmd5 = "select msgId from questionEditTitleLog where `msgId`=? and `flag`='deleted'";
                $query5 = $dbHandle->query($queryCmd5,array($msgId));
                $rowNo = $query5->num_rows();
                if($rowNo) {
                    $queryCmd4 = 'update questionEditTitleLog set `msgTitle`= ?,`flag`="draft",`editDate`= NOW() where `msgId`=?';
                    $query4 = $dbHandle->query($queryCmd4,array($msgTitle,$msgId));
                }else {
                    $response = json_encode(array('haveValue','struct'));
                    return $this->xmlrpc->send_response($response);
                }

            }
        //$queryCmd2 = 'insert into messageDiscussion (`threadId`,`description`)values ("'.$msgId.'","'.$msgDescription.'")';
        // $query2 = $dbHandle->query($queryCmd2);



        }
        //error_log( 'query cmd is ' . $queryCmd1,'qna');
        //$query = $dbHandle->query($queryCmd);
/*
                $queryCmdNew = "select email,displayname from tuser where userid=$userId";
                error_log("query cmd =========================".print_r($queryCmdNew,true));
                $queryNew = $dbHandle->query($queryCmdNew);
                $resNew = $queryNew->row();
*/
        $response = json_encode(array('noValue','struct'));
        return $this->xmlrpc->send_response($response);

    }




    function calViewAnswerComment($request) {
        $parameters = $request->output_parameters();
        $updateArray=json_decode($parameters['0'],true);
        $topicId = $parameters['1'];
        $bestAns = $parameters['2'];
        $googleSearch = $parameters['3'];
        $type = $parameters['4'];
        $appID=12;
	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle($topicId);

        $questionInfo = array();

        if($googleSearch==1) {
            $countRes = count($updateArray['title']);
            $fromOther = "";
        }else {
            $countRes = count($updateArray['title'])-1;
            $fromOther = "and MT.fromOthers = 'user' ";
        }

        for($i=0;$i< $countRes;$i++) {
            
            if($type =='discussion'){
                $tmp = explode("/",$updateArray[title][$i][tmp]);
                if(!in_array('getTopicDetail',$tmp)){
                    $tmp = explode("-",$updateArray[title][$i][U]);
                    $countTmp = count($tmp);
                    $tmp[4] = $tmp[$countTmp-1];
                }
                $questionInfo['link'][$i] = $updateArray[title][$i][tmp];
            }
            else{
                $tmp = explode("/",$updateArray[title][$i][U]);
                if(!in_array('getTopicDetail',$tmp)){
                    $tmp = explode("-",$updateArray[title][$i][U]);
                    $countTmp = count($tmp);
                    $tmp[4] = $tmp[$countTmp-1];
                }
                $questionInfo['link'][$i] = $updateArray[title][$i][U];
            }
           
            /*if( strpos($questionInfo['link'][$i],'style')!== false && strpos($questionInfo['link'][$i],'color')!== false ){
                $tmp = explode('/',$questionInfo['link'][$i]);
                $questionInfo['link'][$i] = $tmp[0].'/'.$tmp[1].'/'.$tmp[2].'/'.$tmp[3].'/'.$tmp[4];
            }*/

            if( ($topicId!=$tmp[4] || $topicId=='') && (is_numeric($tmp[4])) ) {
                $questionInfo['title'][$i] = $updateArray[title][$i][T];

                $questionInfo['description'][$i] = $updateArray[title][$i][S];
                $queryCmd = "select count(*) as comment from messageTable MT where MT.threadId =? $fromOther and MT.parentId !=0 and MT.mainAnswerId != 0 and MT.status IN ('live','closed')";
                $query = $dbHandle->query($queryCmd, array($tmp[4]));
                $res = $query->result_array();
                $questionInfo['comments'][$i] = $res[0]['comment'];

                /*$queryCmd1 = "select count(*) as answer from messageTable MT where MT.threadId =$tmp[4] $fromOther and MT.parentId!=0 and MT.mainAnswerId = 0 and MT.status IN ('live','closed')";
                $query1 = $dbHandle->query($queryCmd1);
                $res1 = $query1->result_array();
                $questionInfo['answers'][$i] = $res1[0]['answer'];*/

                $queryCmd2 = "select viewCount,msgCount as answer from messageTable MT where MT.threadId =? $fromOther and MT.parentId=0 and MT.mainAnswerId = -1 and MT.status IN ('live','closed')";
                $query2 = $dbHandle->query($queryCmd2, array($tmp[4]));
                $res2 = $query2->result_array();
                $questionInfo['viewCount'][$i] = $res2[0]['viewCount'];
		$questionInfo['answers'][$i] = $res2[0]['answer'];
                if($bestAns=='true') {
                    $queryCmd2 = "SELECT count(*) as bestAns FROM messageTableBestAnsMap WHERE threadId = ?";
                    $query2 = $dbHandle->query($queryCmd2, array($tmp[4]));
                    $res2 = $query2->result_array();
                    $flag = ($res2[0]['bestAns']==0)?0:1;
                    $questionInfo['bestAnsFlag'][$i] = $flag;
                }

                $queryCmd4 = "select m1.msgTxt,m1.creationDate,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId=?";
                $query4 = $dbHandle->query($queryCmd4, array($tmp[4]));
                $res4 = $query4->result_array();
                if($res4[0]['msgTxt']=='dummy') {
                    $queryCmd7 = "select m1.msgTxt from messageTable m1 where  m1.threadId=? and m1.mainAnswerId=0";
                    $query7 = $dbHandle->query($queryCmd7, array($tmp[4]));
                    $res7 = $query7->result_array();
                    $questionInfo['title'][$i] = $res7[0]['msgTxt'];
                    $questionInfo['description'][$i] = $res7[0]['msgTxt'];
                }else {
                    $questionInfo['title'][$i] = $res4[0]['msgTxt'];
                    $questionInfo['description'][$i] = $res4[0]['msgTxt'];
                }

            }
            ////QnA Rehash Phase-2 Start to show search result by google
            if($googleSearch==1 && (is_numeric($tmp[4]))) {
                $queryCmd6  = "select categoryId from messageCategoryTable mct LEFT JOIN categoryBoardTable cbt ON cbt.boardId = mct.categoryId where mct.threadId = ? and cbt.parentId = 1";
                $query6 = $dbHandle->query($queryCmd6, array($tmp[4]));
                $res6 = $query6->result_array();
                $questionInfo['categoryId'][$i] = $res6[0]['categoryId'];
                $queryCmd3 = "select name from categoryBoardTable ct where ct.boardId='{$res6[0]['categoryId']}'";

                $query3 = $dbHandle->query($queryCmd3);
                $res3 = $query3->result_array();
                $questionInfo['category'][$i] = $res3[0]['name'];

                $queryCmd4 = "select m1.msgTxt,m1.creationDate,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId=?";
                $query4 = $dbHandle->query($queryCmd4, array($tmp[4]));
                $res4 = $query4->result_array();
                $questionInfo['creationDate'][$i] = $res4[0]['creationDate'];
                $questionInfo['displayname'][$i] = $res4[0]['displayname'];
                $questionInfo['lastlogintime'][$i] = $res4[0]['lastlogintime'];
                $questionInfo['userId'][$i] = $res4[0]['userId'];
                $questionInfo['userImage'][$i] = $res4[0]['userImage'];
                if($res4[0]['msgTxt']=='dummy') {
                    $queryCmd7 = "select m1.msgTxt from messageTable m1 where  m1.threadId=? and m1.mainAnswerId=0";
                    $query7 = $dbHandle->query($queryCmd7, array($tmp[4]));
                    $res7 = $query7->result_array();
                    $questionInfo['msgTitle'][$i] = $res7[0]['msgTxt'];
                }else {

                    $questionInfo['msgTitle'][$i] = $res4[0]['msgTxt'];
                }
                $queryCmd5 = "SELECT ct.countryId,ct.name country from countryTable ct INNER JOIN messageCountryTable mct on (ct.countryId = mct.countryId) WHERE mct.threadId = ? AND ct.countryId>1";
                $query5 = $dbHandle->query($queryCmd5, array($tmp[4]));
                $res5 = $query5->result_array();
                $questionInfo['countryName'][$i] = $res5[0]['country'];
                $questionInfo['countryId'][$i] = $res5[0]['countryId'];
                if($res4[0]['msgTxt']=='dummy') {
                    $queryCmd8 = "select md.description from messageDiscussion md WHERE md.threadId = (select mt.msgId from messageTable mt where mt.parentId= ? LIMIT 1)";
                }else {
                    $queryCmd8 = "select md.description from messageDiscussion md WHERE md.threadId = ?";
                }

                $query8 = $dbHandle->query($queryCmd8, array($tmp[4]));
                $res8 = $query8->result_array();
                $numRows=$query8->num_rows();
                if($numRows) {
                    $questionInfo['descriptionFromDB'][$i] = $res8[0]['description'];
                    $questionInfo['status'][$i] = 'true';
                }else {
                    $questionInfo['status'][$i] = 'false';
                }
            }
        ////QnA Rehash Phase-2 End to show search result by google
        }

        $questionInfo = json_encode($questionInfo);
        $response = array($questionInfo,'string');
        return $this->xmlrpc->send_response($response);
	    /*$mainArr = array();
	    $mainArr[0] = $questionInfo;
	    $responseString = base64_encode(gzcompress(json_encode($mainArr)));
	    $response = array($responseString,'string');
	    return $this->xmlrpc->send_response($response);*/

    }
	/*
	* Start a new thread. Update user point system and update viewCount. Also notfify search
	* update group if a thread is posted from a group page.
	*/
    function getReputationPoint($user_id) {
        $this->load->library('messageboardconfig');
	$dbHandle = $this->_loadDatabaseHandle();
        $sqlRep = "select points from tuserReputationPoint where userId=?";
        $queryRep = $dbHandle->query($sqlRep, array($user_id));
        $rowRep = $queryRep->row();
        $rowRepNo = $queryRep->num_rows();
        if($rowRepNo) {
            $points  = $rowRep->points;
        }else {
            $points  = '9999999';
        }
        return $points;
    }
    function addTopic($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $user_id=$parameters['1'];
        $mesgTxt=$parameters['2'];
        $categoryCSV=$parameters['3'];
        $requestIP=$parameters['4'];
        $fromOthers=$parameters['5'];
        $listingTypeId=$parameters['6'];
        $listingType=$parameters['7'];
        $toBeinddex = $parameters['8'];
        $displayName=$parameters['9'];
        $countryId=$parameters['10'];
        $extraParamCsv=$parameters['11'];
        $courseId = $parameters['12'];

        //below line is used for conversion tracking purpose
        $tracking_keyid=$parameters['13'];
        $sessionidTracking=getVisitorSessionId();
        $source = $parameters['14'];
        
        $contentArr['page_name'] = isset($_POST['page_name'])?$this->input->post('page_name'):'';
        $newEntityArray = array('discussion','announcement','review','eventAnA');
        $msgId = 0; $ansId = 0;
        //Add the main entity ie. question in case of AnA and dummy entry in other cases
                /***********************************************************************************************/
        ////QnA Rehash Phase-2 Start code to check Reputation point for Question,Answer and Discussion
                /***********************************************************************************************/
        $points = $this->getReputationPoint($user_id);
        if($fromOthers == 'discussion' || $fromOthers == 'announcement' || $fromOthers=='user') {
            $errorString = -1;
            if($points<= 0 && $points!='9999999') {
                if($fromOthers == 'discussion')
                    $errorString = 'NOREPD'; // if zero reputation point then no discussion .
                if($fromOthers == 'announcement')
                    $errorString = 'NOREPA'; // if zero reputation point then no aanouncement .
                if($fromOthers == 'user')
                    $errorString = 'NOREPQ'; // if zero reputation point then no aanouncement .
            }
            if($errorString != -1) {
                $response = array($errorString,'string');
                return $this->xmlrpc->send_response($response);
            }
        }
                /***********************************************************************************************/
        ////////QnA Rehash Phase-2 End code to check Reputation point for Question,Answer and Discussion
                /***********************************************************************************************/

        $queryCmdCat = "select parentId from categoryBoardTable where boardId IN (?)";
        $categoryArray = explode(',', $categoryCSV);
        $queryCat = $dbHandle->query($queryCmdCat,array($categoryArray));
        $rowCat = $queryCat->row();
        $catId = $rowCat->parentId;
        $contentArr['catId']=$catId;

        //Here, check if the user has already posted a Question with the same text in the last 1 day. If yes, we will return the Id of the Question already posted
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        // $queryCmdCheck = "SELECT msgId FROM messageTable mt, tuser t WHERE mt.userId = ? AND mt.msgTxt = ? AND mt.creationDate > '$yesterday' AND mt.parentId = 0 and mt.userId = t.userid and t.usergroup NOT IN ('cms','enterprise','experts','privileged','saAdmin','saCMS','saContent','saSales','sums')";
        // $queryCheck = $dbHandle->query($queryCmdCheck,array($user_id,htmlspecialchars($mesgTxt)));

        $queryCmdCheck = "SELECT mt.msgTxt, mt.msgId FROM messageTable mt, tuser t WHERE mt.userId = ? AND mt.creationDate > '$yesterday' AND mt.parentId = 0 and mt.userId = t.userid and t.usergroup NOT IN ('cms','enterprise','experts','privileged','saAdmin','saCMS','saContent','saSales','sums')";
        $queryCheck = $dbHandle->query($queryCmdCheck,array($user_id))->result_array();
        $msgTxt = md5($mesgTxt);
        foreach ($queryCheck as $key => $value) {
            if(md5($value['msgTxt']) == $msgTxt){
                $response = array(
			array(
                        'ThreadID'=>array($value['msgId']),
                        'categoryID'=>array($catId),
                        'isDuplicate'=>array(1)),
                        'struct');
                return $this->xmlrpc->send_response($response);
            }
        }
        // if($queryCheck->num_rows()>0){
        //         $rowCheck = $queryCheck->row();
        //         $msgId = $rowCheck->msgId;
        //         $response = array(
        //             array(
        //             'ThreadID'=>array($msgId),
        //             'categoryID'=>array($catId)),
        //             'struct');
        //         return $this->xmlrpc->send_response($response);
        // }

        // To pass these values in case of mailers to CA
        $instituteId = $listingTypeId;
        $instituteType = $listingType;

        //Incase of courseId>0, listingTypeId should get updated in messageTable. Otherwise not.
        $listingTypeId = ($courseId>0 && $fromOthers == 'user')?$listingTypeId:0;
        $listingType = ($listingTypeId>0)?$listingType:'';
        if($fromOthers == 'user' || $fromOthers == 'blog' || $fromOthers == 'event' || $fromOthers == 'group') {
            $data =array( 'userId'=>$user_id, 'msgTxt'=>htmlspecialchars($mesgTxt),	'requestIP'=>$requestIP, 'fromOthers'=>$fromOthers, 'listingTypeId'=>$listingTypeId, 'listingType'=>$listingType, 'mainAnswerId'=>-1,'tracking_keyid'=>$tracking_keyid,'visitorsessionid'=>$sessionidTracking);
        }
        else if(in_array($fromOthers,$newEntityArray)) {
                $data =array( 'userId'=>$user_id, 'msgTxt'=>'dummy', 'requestIP'=>$requestIP, 'fromOthers'=>$fromOthers, 'listingTypeId'=>$listingTypeId, 'listingType'=>$listingType, 'mainAnswerId'=>-1);
            }

        $queryCmd = $dbHandle->insert_string($this->messageboardconfig->messageTable,$data);
        error_log_shiksha( 'addTopic query cmd is ' . $queryCmd,'qna');

        $query = $dbHandle->query($queryCmd);
        $msgId=$dbHandle->insert_id();

        $response = array(
            array(
            'ThreadID'=>array($msgId),
            'categoryID'=>array($catId)),
            'struct');

        $queryCmd = 'update messageTable set path=messageTable.msgId, threadId=messageTable.msgId where msgId=?';
        error_log_shiksha( 'query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($msgId));
        if($fromOthers == 'user') {
            $description = $extraParamCsv;
            $data =array( 'threadId'=>$msgId, 'description'=>htmlspecialchars($description));
            $queryCmd = $dbHandle->insert_string($this->messageboardconfig->messageDiscussion,$data);
            $query = $dbHandle->query($queryCmd);
        }
        //Add the main entity in case of discussion etc
        if(in_array($fromOthers,$newEntityArray)) {
            $data =array( 'userId'=>$user_id, 'msgTxt'=>htmlspecialchars($mesgTxt), 'requestIP'=>$requestIP, 'fromOthers'=>$fromOthers, 'listingTypeId'=>$listingTypeId, 'listingType'=>$listingType, 'mainAnswerId'=>0, 'parentId'=>$msgId, 'threadId'=>$msgId,'tracking_keyid'=>$tracking_keyid,'visitorsessionid'=>$sessionidTracking);
            $queryCmd = $dbHandle->insert_string($this->messageboardconfig->messageTable,$data);
            error_log_shiksha( 'addTopic query cmd is ' . $queryCmd,'qna');

            $query = $dbHandle->query($queryCmd);
            $ansId=$dbHandle->insert_id();

            $queryCmd = 'update messageTable set path="'.$msgId.".".$ansId.'" where msgId=?';
            error_log_shiksha( 'query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd, array($ansId));

            //Also, in case of discussion, announcements, add the extra piece of information in the DB
            if($extraParamCsv!='') {
            //$extraArray = explode(",", $extraParamCsv);
                if($fromOthers == 'discussion' || $fromOthers == 'announcement') {
                //$description = $extraArray[0];
                    $description = $extraParamCsv;
                    $data =array( 'threadId'=>$ansId, 'description'=>htmlspecialchars($description));
                    $queryCmd = $dbHandle->insert_string($this->messageboardconfig->messageDiscussion,$data);
                    $query = $dbHandle->query($queryCmd);
                }
            }
        }

        // update category table
        $this->updateCategoryTable($categoryCSV,$msgId,$source);
        if(in_array($fromOthers,$newEntityArray)) {
            $this->updateCategoryTable($categoryCSV,$ansId,$source);
        }

        //update country
        $this->updateCountryTable($countryId,$msgId);
        if(in_array($fromOthers,$newEntityArray)) {
            $this->updateCountryTable($countryId,$ansId);
        }

        //Add the entry in Redis for Personalized Homepage
        $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
	$entityType = $fromOthers;
        if($fromOthers=='user'){  //In case of question
		$entityType = 'question';
        }
	$this->userinteractioncachestoragelibrary->storeUserActionPost($user_id, $msgId, $entityType);
        //Add entry in Redis Ends

        //In case of questions, update the point system and send the mail to the owner and update the expert table
        if($fromOthers=='user') {
        // update User point system
            $this->load->model('UserPointSystemModel');
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'postQuestion',$msgId);
            //update messageExpertTable
            $queryCmd="insert into messageExpertTable(threadId,passPhrase) values(?,FLOOR(RAND()*10000))";
            error_log_shiksha( 'addTopic query cmd is ' . $queryCmd,'qna');

            if(!$dbHandle->query($queryCmd,array($msgId))) {
                error_log_shiksha( 'addTopic query cmd failed' . $queryCmd,'qna');
            }
        }

        if($fromOthers=='discussion') {
        // update User point system
            $this->load->model('UserPointSystemModel');
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'postDiscussion', $msgId);
        }

        if($fromOthers=='announcement') {
        // update User point system
            $this->load->model('UserPointSystemModel');
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$user_id,'postAnnouncement');
        }

        //Also, send the mail to the owner of the question/discussion/announcement
        if(($fromOthers=="user" || $fromOthers=="discussion" || $fromOthers=="announcement") && ($source!="mobileApp")) {
            if($fromOthers=="user") {
                $queryCmd = "SELECT m1.msgTxt,t1.displayname,t1.userid,t1.firstname,t1.lastname,t1.email,cbt.name category,cbt.boardId subcatId, ct.name country from tuser t1, messageTable m1, categoryBoardTable cbt, countryTable ct where m1.userId=t1.userid and m1.msgId=$msgId and cbt.boardId IN (select mct_sub.categoryId from messageCategoryTable mct_sub LEFT JOIN categoryBoardTable cbt_sub ON cbt_sub.boardId = mct_sub.categoryId where mct_sub.threadId=?) and ct.countryId IN (select countryId from messageCountryTable where threadId =? and countryId!=1) order by subcatId desc limit 1";
            }
            else {
                $queryCmd = "SELECT m1.msgTxt,t1.displayname,t1.userid,t1.firstname,t1.lastname,t1.email,cbt.name category,cbt.boardId subcatId, ct.name country from tuser t1, messageTable m1, categoryBoardTable cbt, countryTable ct where m1.userId=t1.userid and m1.threadId=$msgId and m1.parentId=$msgId and cbt.boardId IN (select mct_sub.categoryId from messageCategoryTable mct_sub LEFT JOIN categoryBoardTable cbt_sub ON cbt_sub.boardId = mct_sub.categoryId where mct_sub.threadId=?) and ct.countryId IN (select countryId from messageCountryTable where threadId = ? and countryId!=1) order by subcatId desc limit 1";
            }
            $query = $dbHandle->query($queryCmd, array($msgId, $msgId));
            $row = $query->row();
            $email = $row->email;
            $receiverId = $row->userid;
            $fromMail = "noreply@shiksha.com";
            $ccmail = "";
            $courseId = ($courseId>0 && $fromOthers == 'user')?$courseId:0;
             if($fromOthers=="user" && $instituteType=="institute") {
               $qNaModel = $this->load->model('QnAModel');
               $qNaModel->sendMailToCampusReps($courseId,$instituteId,$msgId,$row);
               
            }
             
            if($fromOthers=="user") {
                $subject = "Your question has been posted successfully on Shiksha Ask & Answer";
                $contentArr['type'] = 'askQuestion';
                $this->load->model('QnAModel');
           
                if(isset($listingTypeId) && $listingTypeId != '' && $listingTypeId>0 && isset($courseId) && $courseId>0){
                        $this->load->builder('nationalInstitute/InstituteBuilder');
                        $instituteBuilder = new InstituteBuilder();      
                        $instituteRepository = $instituteBuilder->getInstituteRepository();
                        $result = $instituteRepository->find($listingTypeId);
                        $contentArr['instituteName'] = $result->getName();
                }
                
                if($contentArr['page_name'] =='myShortlist_Ana'){                
                    $contentArr['campusRepIds'] = $this->QnAModel->getCampusRepOnListing($listingTypeId);
                    $contentArr['campusRepName'] = $this->QnAModel->getCampusRepNameEmail($contentArr['campusRepIds']);
        
                }
               
                //Added by Ankur for sending Links to Discussion posts
                $contentArr['sendLinks'] = 'false';
                $tempArray = explode(",", $categoryCSV);
                foreach($tempArray as $temp) {
                    if($temp != '' && $temp=='3')
                        $contentArr['sendLinks'] = 'true';
                }
                if($row->category == "Management, Business") {
                    $contentArr['sendLinks'] = 'true';
                }
            //End modifications by Ankur
            }
            else {
                $subject = "Your $fromOthers has been submitted successfully on Shiksha Ask & Answer";
                $contentArr['type'] = 'postTopic';
                $contentArr['msgTxt'] = $row->msgTxt;
                $contentArr['entity'] = $fromOthers;
            }
            $contentArr['name'] = ($row->firstname=='')?$row->displayname:$row->firstname;
            $contentArr['lastname'] = ($row->lastname=='')?$row->displayname:$row->lastname;
            $contentArr['category'] = $row->category;
            $contentArr['country'] = $row->country;
            //$this->load->library('mailerClient');
            //$MailerClient = new MailerClient();
            $urlOfLandingPage = SHIKSHA_ASK_HOME;
            $this->load->model('UserPointSystemModel');
            $res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$user_id);

            if($res['reputationPoints']>25 && $res['reputationPoints']!=9999999) {
                $contentArr['urlOfUntitledQues'] = $urlOfLandingPage.'/1/4/1/untitledQuestion';
            }

            $contentArr['url'] = $urlOfLandingPage;
            $contentArr['mail_subject'] = $subject;
            $contentArr['receiverId'] = $receiverId;
            $contentArr['userId'] = $user_id;
            //$content = $this->load->view("search/searchMail",$contentArr,true);
            //$mail_client = new Alerts_client();
            //$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
            Modules::run('systemMailer/SystemMailer/questionDiscussionAnnouncementPost', $email, $contentArr);
        }
        //Update network news
        //Removed for removal of groups
		/* if(($displayName!="") && (strcmp($fromOthers,"group")==0 || strcmp($fromOthers,"TestPreparation") == 0)){
			$this->updateNews($listingTypeId,$listingType,$msgId,$user_id,$mesgTxt,$displayName,'starttopic');
		} */
        //Update Beacon Table
        $this->beaconUpdate($msgId,$user_id);
        
		/**
		 * Update userMailerSentCount table to reset triggers of product mailers when a question is Posted
		 */
		$this->load->library('mailer/ProductMailerEventTriggers');
		$this->productmailereventtriggers->resetMailerTriggers($user_id, 'questionPosted');
		
        return $this->xmlrpc->send_response($response);
    }

    function updateCategoryTable($categoryCSV,$msgId,$source="Desktop") {
    //connect DB
        $this->load->library('messageboardconfig');
        $dbHandle = $this->_loadDatabaseHandle('write');
        $msgId = $dbHandle->escape($msgId);
        $categoryArray = array();
        $tempArray = explode(",", $categoryCSV);
        foreach($tempArray as $temp) {
            if($temp != '')
                array_push($categoryArray,$temp);
        }
	if($source!="mobileApp"  && $source != "mobilesiteapicall"){
        	array_push($categoryArray,'1');
	}
        $queryCmd = 'insert into messageCategoryTable (threadId,categoryId) values ';
        $commaCount=0;
        foreach ($categoryArray as $boardId) {
            if($commaCount>0) {
                $queryCmd.=",";
            }
            $queryCmd.="($msgId,$boardId)";
            if($boardId>1) {
                $queryCmd.=",($msgId,(select parentId from categoryBoardTable where boardId= $boardId))";
            }
            $commaCount++;
        }
        $queryCmd.= " on duplicate key update messageCategoryTable.threadId = $msgId ";
        error_log_shiksha( 'addTopic query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd);

    }

    function updateCountryTable($countryId,$msgId) {
        //connect DB
        $this->load->library('messageboardconfig');
        $dbHandle = $this->_loadDatabaseHandle('write');
        $msgId = $dbHandle->escape($msgId);
        $countryArray = array();
        $tempCountryArray = explode(",", $countryId);
        foreach($tempCountryArray as $temp) {
            if($temp != '')
                array_push($countryArray,"'".$temp."'");
        }
        array_push($countryArray,'1');
        $queryCmd = 'insert into messageCountryTable (threadId,countryId) values ';
        $commaCount=0;
        foreach ($countryArray as $conId) {
            if($commaCount>0) {
                $queryCmd.=",";
            }
            $queryCmd.="($msgId,$conId)";
            $commaCount++;
        }
        $queryCmd.= " on duplicate key update messageCountryTable.threadId = $msgId ";
        error_log_shiksha( 'addTopic messageCountryTable query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd);
    }

	/*
	*	update thread if a edit is done from GUI
	*/
    function updateTopic($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
        $mesgTxt=$parameters['2'];
        $requestIP=$parameters['3'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');


        //check if any topic is posted on this topic or not?
        $queryCmd="select msgCount from messageTable where msgId=? and status in ('live','closed')";
        error_log_shiksha( 'updateTopic query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($msgId));
        $msgArray = array();
        $count=0;
        foreach ($query->result() as $row) {
            $count=$row->msgCount;
        }
        if($count>0) {
            $response = array(array('Result'=>'You can not edit this topic'),'struct');
            return $this->xmlrpc->send_response($response);
        }else {
            error_log_shiksha( 'updateTopic update query ','qna');
            $data =array(
                'msgTxt'=>htmlspecialchars($mesgTxt),
                'requestIP'=>$requestIP
            );
            //$dbHandle->insert_string($this->messageboardconfig->messageTable,$data);

            $dbHandle->where(array('msgId' => $msgId ));
            $dbHandle->update($this->messageboardconfig->messageTable,$data);

            $response = array(array('Result'=>'Topic Edited'),'struct');
            return $this->xmlrpc->send_response($response);
        }
    }

    function checkDiscussionAndAnnoucement($userId,$threadId,$fromOthers) {
        $appID =1;
	$dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = "select userId from messageTable where threadId = ? and status IN ('live','closed') and fromOthers = ? and parentId!=0 order
by creationDate desc limit 1, 1";

        $query = $dbHandle->query($queryCmd,array($threadId,$fromOthers));
        $res   = $query->row();
        $rowNo = $query->num_rows();

        if($res->userId == $userId) {
            return 0;
        }else {
            return 1;

        }

    }

	/*
	*	post reply to a given message in a particular board $appID,$parentThread_id
	* insert a new reply and update viewcount and user point system
	* Also email to the posted of the thread if this answer is the first answer of the thread.
	*/
    function postReply($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $msgTxt=$parameters['2'];
        $threadId=$parameters['3'];
        $parentId=$parameters['4'];
        $requestIP=$parameters['5'];
        $fromOthers=$parameters['6'];
        $displayName=$parameters['7'];
        $mainAnswerId=$parameters['8'];
        //below line is used for storing the tracking key id value in database
        $tracking_keyid=$parameters['9'];
        $sessionidTracking=getVisitorSessionId();

        error_log_shiksha( 'postReply query cmd is ' . $parentId,'qna');

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        $sqlRep = "select points from tuserReputationPoint where userId=?";
        $queryRep = $dbHandle->query($sqlRep,array($userId));
        $rowRep = $queryRep->row();
        $rowRepNo = $queryRep->num_rows();
        if($rowRepNo) {
            $points  = $rowRep->points;
        }else {
            $points  = '9999999';
        }
        $newEntityArray = array('discussion','announcement','review','eventAnA');
        if($row->fromOthers == "group") $fromOthers = "group";

	$this->benchmark->mark('check_answer_start');
        $sql = "select (select count(*) from messageTable where threadId = ? and userId = ? and parentId = ? and status IN ('live','closed')) noOfUserComments,msgCount,listingTypeId,listingType,fromOthers,userId,parentId from messageTable where msgId = ?";
        $query1 = $dbHandle->query($sql,array($threadId,$userId,$threadId,$parentId));
        $row = $query1->row();
	if($fromOthers!='blog')
	 $fromOthers = $row->fromOthers;
        $questionAksedBy = $row->userId;
        $listingTypeId = $row->listingTypeId;
        $listingType = $row->listingType;
        $parentIdOfParent = $row->parentId;
        $noOfUserComments = $row->noOfUserComments;
        $noOfComments = $row->msgCount;
        
        if($mainAnswerId>0){
            $sql = "Update messageTable SET msgCount =? + 1 Where msgId = ? and status in ('live','closed') ";
            $query2 = $dbHandle->query($sql,array($noOfComments,$parentId));
             
        }
        $this->benchmark->mark('check_answer_end');
        if($fromOthers == 'user') {
            $errorString = -1;
            if(($questionAksedBy == $userId) && ($parentId == $threadId)) {
                $errorString = 'SUQ'; // same user's question.
            }elseif(($noOfUserComments > 0) && ($parentId == $threadId)) {
                $errorString = 'MTOA'; // more than one answer.
            //}elseif(($questionAksedBy == $userId) && ($parentIdOfParent == $threadId)){
            //	$errorString = 'RTOA'; // reply to own answer.
            }elseif($points<= 0 && $points!='9999999') {
                $errorString = 'NOREP'; // if zero reputation point then no answer .
            }

            //Check that Institute owner should not be allowed to Answer if Campus rep is available
            //Get the Course Id of the Answer/Comment
	    $this->benchmark->mark('check_answer_second_start');
            $this->load->model('qnamodel');
            $courseid = $this->qnamodel->getAnswerCourseId($userId,$threadId);
            $courseid = isset($courseid[0]['courseId'])?$courseid[0]['courseId']:0;
            if($courseid>0){
                //Get the Owner of this Course.
                $sqlOwner = "SELECT username FROM listings_main WHERE listing_type_id=? AND listing_type='course' AND status='live' LIMIT 1";
                $queryOwner = $dbHandle->query($sqlOwner,array($courseid));
                $rowOwner = $queryOwner->row();
                $ownerId = $rowOwner->username;

                //Check if this course has any Campus rep.
                $this->load->library('CA/CADiscussionHelper');
                $caDiscussionHelper =  new CADiscussionHelper();
                $campusRepExists = $caDiscussionHelper->checkIfCampusRepExistsForCourse(array($courseid));
                $campusRepExists  = $campusRepExists[$courseid];

                //Now, if the Course has Campus rep and the answerer is same as Owner, return an Error.
                if($userId==$ownerId && $campusRepExists=='true'){
                        $errorString = 'CAMPUSREPEXISTS';
                }
            }
            //Code ends for Campus rep and Institute owner check
	    $this->benchmark->mark('check_answer_second_end');

            if($errorString != -1) {
                $response = array($errorString,'string');
                return $this->xmlrpc->send_response($response);
            }
        }
        $data =array(
            'userId'=>$userId,
            'msgTxt'=>htmlspecialchars($msgTxt),
            'threadId'=>$threadId,
            'parentId'=>$parentId,
            'requestIP'=>$requestIP,
            'fromOthers'=>$fromOthers,
            'listingTypeId'=>$listingTypeId,
            'listingType'=>$listingType,
            'mainAnswerId'=>$mainAnswerId,
            'tracking_keyid'=>$tracking_keyid,
            'visitorsessionid'=>$sessionidTracking
        );

        $queryCmd = $dbHandle->insert_string($this->messageboardconfig->messageTable,$data);
        $query = $dbHandle->query($queryCmd);
        $msgId=$dbHandle->insert_id();

        error_log_shiksha( 'postReply query last inserted id is ' . $msgId,'qna');

        //get path from the parent thread
        $dbHandle->select('path')->from('messageTable')->where(array('msgId'=>$parentId));
        $query = $dbHandle->get();
        $path='';
        foreach ($query->result() as $row) {
            $path=$row->path;
        }

        error_log_shiksha( 'postReply path is ' . $path,'qna');
        $paddedMsgId=$msgId;
        $queryCmd = 'update messageTable set path=concat(\''.$path.'\',\'.\','.$paddedMsgId.') where msgId=?';
        error_log_shiksha( 'postReply query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($msgId));
        $response = array($msgId,'int');

        //pranjul start
              /*  $sqlNew = "select userId from messageTable where msgId = ".$parentId;
		$queryNew = $dbHandle->query($sqlNew);
		$rowNew = $queryNew->row();
                error_log("the query is ".print_r($sqlNew,true));
                error_log("the user id is ".print_r($rowNew,true)); */
        ////pranjul end
        //update PopularityView
	$this->benchmark->mark('update_popularity_start');
        $this->updatePopularityView($threadId,$fromOthers);

	$this->benchmark->mark('update_popularity_end');
        $this->load->model('qnamodel');
        $this->load->model('CA/crdashboardmodel');
        $this->load->library('CA/Mywallet');
	$this->benchmark->mark('check_cr_start');
        $courseIds = $this->crdashboardmodel->getAllCourseIdFromCR($userId);
        $crCourseArray=explode(',', $courseIds);
        $courseid = $this->qnamodel->getAnswerCourseId($userId,$threadId);
	//Now, also insert an entry in case of Answer in Approved/Disapproved table
	if($threadId == $parentId && $listingTypeId != 0 && in_array($courseid[0]['courseId'],$crCourseArray)){
               $checkCR = $this->qnamodel->checkIfUserIsRegisteredCampusRep($userId);
                if($checkCR==1){
                    $createDate =  $this->qnamodel->getQuestionCreationDate($msgId);
                    $timeDiff = (strtotime(date('Y-m-d H:i:s')) - strtotime($createDate));
                    $isCaEng = $this->mywallet->makeIncentive($userId);
                    $money = getCREarning($timeDiff,$isCaEng[$userId]);
                    $reward = $money['money'];
                    $this->qnamodel->addInWallet($userId,$msgId,$reward);
                    $status = isset($_POST['status'])?$this->input->post('status'):'draft';
                    $reason = isset($_POST['reason'])?$this->input->post('reason'):'';
                    $result = $this->qnamodel->addAnswerStatus($userId,$msgId,$status,$reason);
                }  
                
	}
        $this->benchmark->mark('check_cr_end');
        
        if($threadId == $parentId){
            $entityType='Answer';
        }else if($mainAnswerId == $parentId){
            $entityType='comment';
        }else{
            $entityType='reply';
        }
        
	$this->benchmark->mark('mobile_tracking_start');
        $source = ($_COOKIE['ci_mobile'] !='') ? 'mobile' : 'desktop';
        if($threadId == $parentId || $mainAnswerId == $parentId){
            $result = $this->qnamodel->insertAnAMobileTracking($userId,$entityType,$source);
//            $userArray  = $this->QnAModel->getcourseAndUseridForNotification($threadId);
//            $checkForShortlist = $this->QnAModel->checkForShortlistCourse($userArray[0]['courseId']);
//            if($mainAnswerId == $parentId){
//                    $answerOwnerArray = $this->QnAModel->getDetailForAnswerOwnerNotification($threadId,$mainAnswerId);
//                    $checkAnswerOwnerShortlist = $this->QnAModel->checkForShortlistCourse($answerOwnerArray[0]['courseId']);
//            }
//            
//            if($checkForShortlist != '' || $checkAnswerOwnerShortlist!=''){
//                    $this->load->builder('ListingBuilder','listing');
//		    $listingBuilder = new ListingBuilder;
//		    $instituteRepository = $listingBuilder->getInstituteRepository();
//		    $result = $instituteRepository->find($listingTypeId);
//		    $instituteName = $result->getName();
//                    if($mainAnswerId == $parentId){
//                       $body="<a href='".SHIKSHA_HOME."/my-shortlist#nav-".$userArray[0]['courseId']."-ask' linkId='nav-".$userArray[0]['courseId']."-ask'>A new comment has been posted about $instituteName.</a>"; 
//                    }else{
//                       $body="<a href='".SHIKSHA_HOME."/my-shortlist#nav-".$userArray[0]['courseId']."-ask' linkId='nav-".$userArray[0]['courseId']."-ask'>Your question about $instituteName has been answered.</a>"; 
//                    }
//                    $creationDate=date('Y-m-d H:i:s');
//                    if($checkAnswerOwnerShortlist != ''){
//                        $result1 = $this->QnAModel->insertInNotificationTable($answerOwnerArray[0]['userId'],$body,$creationDate);
//                    }
//        
//                    $result = $this->QnAModel->insertInNotificationTable($userArray[0]['userId'],$body,$creationDate);
//                    
//            }
        }
	$this->benchmark->mark('mobile_tracking_end');
	$this->benchmark->mark('redis_tracking_start');
        //Add the entry in Redis for Personalized Homepage
        $this->load->model('UserPointSystemModel');
        $userLevel = $this->UserPointSystemModel->getLevel($userId);
        $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
        if($fromOthers=='user' && $parentId == $threadId){  //In case of Answer
            $this->userinteractioncachestoragelibrary->storeUserActionAnswer($userId, $threadId, 'question', $msgId, $userLevel);
        }
        else if($fromOthers=='discussion' && $parentId == $mainAnswerId){ //In case of Discussion comment
            $this->userinteractioncachestoragelibrary->storeUserActionComment($userId, $threadId, 'discussion', $msgId, $userLevel);
        }
	$this->benchmark->mark('redis_tracking_end');
	//Add entry in Redis Ends
        
        //Add notification of APP in redis
	$this->benchmark->mark('add_notification_start');
        $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
        if($fromOthers=='user' && $parentId == $threadId){
                $this->appNotification->addNotificationForAppInRedis('new_answer_on_question',$threadId,'question',$userId,'answer',$msgId);
        }else if($fromOthers=='user' && $parentId == $mainAnswerId){
                $this->appNotification->addNotificationForAppInRedis('comment_on_answer',$threadId,'question',$userId,'answer',$parentId);
        }else if($fromOthers=='discussion' && $parentId == $mainAnswerId){
                $this->appNotification->addNotificationForAppInRedis('new_comment_on_discussion',$threadId,'discussion',$userId,'answer',$msgId);
        }else if($fromOthers=='discussion' && $parentId != $mainAnswerId && $mainAnswerId>0){
                $this->appNotification->addNotificationForAppInRedis('reply_on_comment',$threadId,'discussion',$userId,'comment',$parentId);
        }
	$this->benchmark->mark('add_notification_end');

        global $isMobileApp;
        global $isWebAPICall;
	//In case of Answer on Question, we need to check if the user is active on App. Only if he is not active, we will send the mailer.
	//In rest of the cases i.e. Answer comment, Discussion comment and Reply, we will Sending mail for Web AnA ,  blocking only for Mobile App
	$this->benchmark->mark('send_email_start');
	if(($fromOthers == 'user') && ($mainAnswerId == 0)) {
		$this->sendEmailForAnswerAndReply($msgId,$threadId,$userId,$fromOthers,$mainAnswerId,$parentId);
	}
        else if(!$isMobileApp || $isWebAPICall){
		$this->sendEmailForAnswerAndReply($msgId,$threadId,$userId,$fromOthers,$mainAnswerId,$parentId);
        }
	$this->benchmark->mark('send_email_end');
        $this->benchmark->mark('user_points_update_start');
        $this->load->model('UserPointSystemModel');
        switch($fromOthers) {
            case 'user':
            ////pranjul start
                if(($noOfComments > 0) && $parentId == $threadId) {
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'ansQuestion',$msgId,$rowNew->userId);
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'ansQuestion',$msgId);
                }else if($parentId == $threadId) {
                    //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'firstAnswer',$msgId,$rowNew->userId);
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'ansQuestion',$msgId);
                }
                ////pranjul end
                break;
            case 'event':
                $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'addEventComment');
                break;
            case 'blog':
                $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'addArticleComment');
                break;
            case 'discussion':
                if($this->checkDiscussionAndAnnoucement($userId,$threadId,'discussion')) {
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'postDiscussionComment',$threadId);
                }
                break;
            case 'announcement':
                if($this->checkDiscussionAndAnnoucement($userId,$threadId,'announcement')) {
                    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'postAnnouncementComment',$threadId);
                }
                break;
        }
	$this->benchmark->mark('user_points_update_end');
        //update beacon
        $this->beaconUpdate($threadId,$userId);

        return $this->xmlrpc->send_response($response);

    }

    function updateMsgCount($threadId,$parentId,$increment) {
    //connect DB
	$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "select count(*) msgCount from messageTable where threadId = ? and status in ('live','closed') and fromOthers IN ('user','discussion') and parentId = ?";
        $result = $dbHandle->query($queryCmd,array($threadId,$parentId));
        $commentCount = 'msgCount = (msgCount + ('.$increment.'))';
        foreach ($result->result_array() as $row) {
            $msgCount = $row['msgCount'];
            $commentCount = 'msgCount = ('.$msgCount.')';
        }
        $queryCmd="update messageTable set ".$commentCount." where msgId=?";
        error_log_shiksha( 'updateMsgCount query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($parentId));
        return 1;
    }

    function sendEmailForAnswerAndReply($msgId,$threadId,$userId,$fromOthers,$mainAnswerId,$parentId) {
        $this->load->library('mailerClient');
        $AlertClientObj = new Alerts_client();
        $MailerClient = new MailerClient();
        $fromAddress=SHIKSHA_ADMIN_MAIL;
        //connect DB
        $appID = 1;
	    $dbHandle = $this->_loadDatabaseHandle('write');
        $contentArray = array();
        $Result = null;
        $urlOfLandingPage = "";
        $bccEmail = "";
        $nameOfUser = "";
        $msgId = $dbHandle->escape($msgId);
        if(($fromOthers == 'user') && ($mainAnswerId == 0)) {//In case of Answer to a question
	    $this->benchmark->mark('send_mail_question_owner_start');
            //$contentArr['course_id'] = isset($_POST['course_id'])?$this->input->post('course_id'):'';
            //$contentArr['institute_Id'] = isset($_POST['institute_id'])?$this->input->post('institute_id'):'';
            //$contentArr['page_name'] = isset($_POST['page_name'])?$this->input->post('page_name'):'';
            //if($contentArr['page_name'] == 'myShortlistAnA_anwser'){
            //    $contentArr['campusRepName'] = $this->QnAModel->getCampusRepNameEmail($userId);
            //    $contentArr['user_Id'] = $this->QnAModel->getUserDetailsForAnswerMailer($threadId);
            //    $contentArr['userDetail'] = $this->QnAModel->getCampusRepNameEmail($contentArr['user_Id']);
            //}
            $sendOwnerMail = true;
            //$queryToCheckAlert = "select count(*) alert_set from alert_user_preference alp , messageTable m1  where alertType = 'byComment' and alp.user_id = m1.userId and m1.msgId = ? and alert_value_id = ? and mail = 'on' and STATE = 'on'";
            //$ResultOfAlert = $dbHandle->query($queryToCheckAlert, array($threadId,$threadId));
            //$row = $ResultOfAlert->row();
            //if($row->alert_set <= 0) {
            //    $sendOwnerMail = false;
            //}
            //Send mail to the Question Owner
            $queryCmd = "select m1.threadId,m1.msgTxt,m1.parentId,t1.displayname,t1.userId userId,t1.email,m1.msgTxt,t1.firstname from messageTable m1,tuser t1 where m1.msgId IN ($threadId,$msgId) and m1.fromOthers  = 'user' and m1.userId = t1.userid and m1.status not in ('deleted','abused')";
            //$queryArray = array($threadId,$msgId);
            $Result = $dbHandle->query($queryCmd);
            foreach($Result->result_array() as $row) {
                if($row['parentId']==0) {
                    $questionUserId = $row['userId'];
                    $questionText = $row['msgTxt'];
                    $questionOwnerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                    $questionOwnerMail = $row['email'];
                }
                else {
                    $commenterUserId = $row['userId'];
                    $answerText = $this->changeTextForAtMention($row['msgTxt']);
                    $answerOwnerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                }
            }
            $toEmail = $questionOwnerMail;
            $contentArr['receiverId'] = $questionUserId;
            //if($contentArr['page_name'] == 'myShortlistAnA_anwser'){
            //    $navigateTo = 'nav-'.$contentArr['course_id'].'-ask';
            //    $urlOfLandingPage = SHIKSHA_HOME.'/my-shortlist#'.$navigateTo;
            //}else{
                $urlOfLandingPage = getSeoUrl($threadId,'question',$questionText);
            //}
            $urlToBeSentInMail = $urlOfLandingPage;

            $this->load->model('UserPointSystemModel');
            if($sendOwnerMail) {
                $subject = $answerOwnerName." posted an answer to your question.";
                $fromAddress="noreply@shiksha.com";
                $contentArr['type'] = "new-answer-to-question-user";
                $userEmail = $toEmail;
                $contentArr['seoUrl'] = $urlToBeSentInMail;
                //$contentArr['ratingUrl'] = $MailerClient->generateAutoLoginLink(1,$userEmail,SHIKSHA_HOME.'/messageBoard/MsgBoard/mailAction/rating/'.$questionUserId."/".$threadId."/".$msgId);
                $contentArr['ratingUrl'] = SHIKSHA_HOME.'/messageBoard/MsgBoard/mailAction/rating/'.$questionUserId."/".$threadId."/".$msgId;
                //$contentArr['bestAnswerUrl'] = $MailerClient->generateAutoLoginLink(1,$userEmail,SHIKSHA_HOME.'/messageBoard/MsgBoard/mailAction/bestAnswer/'.$questionUserId."/".$threadId."/".$msgId."/".$commenterUserId);
                $contentArr['bestAnswerUrl'] = SHIKSHA_HOME.'/messageBoard/MsgBoard/mailAction/bestAnswer/'.$questionUserId."/".$threadId."/".$msgId."/".$commenterUserId;
                $contentArr['urlOfUntitledQues']='';
                //$res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$questionUserId);
                //if((int)$res['reputationPoints']>25 && (int)$res['reputationPoints']!=9999999) {

                    //$urlOfLandingPageUQ = SHIKSHA_ASK_HOME;
                    //$contentArr['urlOfUntitledQues'] = $urlOfLandingPageUQ.'/messageBoard/MsgBoard/discussionHome/1/4/1/untitledQuestion';
                //}
                $contentArr['NameOfUser'] = $questionOwnerName;
                $contentArr['msgTxt'] = strlen($answerText)>300?substr($answerText,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$answerText;
                $contentArr['questionOwnerName'] = $questionOwnerName;
                $contentArr['questionText'] = $questionText;
                $contentArr['answerOwnerName'] = $answerOwnerName;
                //$content=$this->load->view("search/searchMail",$contentArr,true);
                //$AlertClientObj = new Alerts_client();
                //$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");

                //Check if the Question owner is active on App. We will send mailer only if he is NOT active.
		$sendMailer = true;
                $commonAPILib = $this->load->library('common_api/APICommonLib');
                if($questionUserId > 0 && $commonAPILib->isUserActiveOnApp($questionUserId)){
                	$sendMailer = false;
                }
        $contentArr['userId'] = $userId;
		if($sendMailer){
                       Modules::run('systemMailer/SystemMailer/answerPostToQuestion', $userEmail, $contentArr);
		}
		$this->benchmark->mark('send_mail_question_owner_end');
		$this->benchmark->mark('send_mail_question_followers_start');
                if($questionUserId > 0 && $userId>0){
                    //Mail to users on answer posting, who have followed a question.
                    $queryCmd = "select tuft.userId, tu.firstname, tu.lastname, tu.email from tuserFollowTable tuft ,tuser tu where tuft.userId = tu.userid and entityId = ? and entityType = 'question' and tuft.status = 'follow' and tuft.userId NOT IN (?)";
                    $queryArray = array($questionUserId ,$userId);
                    $Result = $dbHandle->query($queryCmd,array($threadId,$queryArray));
                    $contentArr['subject'] = $answerOwnerName.' posted an answer to the question you follow.';
                    foreach($Result->result_array() as $row) {
                        $userEmail = $row['email'];
                        $contentArr['receiverId'] = $row['userId'];
                        $fromAddress="noreply@shiksha.com";
                        $contentArr['followedUserName'] = trim($row['firstname']);
                        //Check if the Question follower is active on App. We will send mailer only if he is NOT active.
    	               $sendMailer = true;
            		    if($commonAPILib->isUserActiveOnApp($row['userId'])){
            			$sendMailer = false;
            		    }
                        $contentArr['userId'] = $row['userId'];
            		    if($sendMailer){
                                	Modules::run('systemMailer/SystemMailer/AnswerPostToQuestionFollowed', $userEmail, $contentArr);
            		    }

                    }
                }
		$this->benchmark->mark('send_mail_question_followers_end');
            }
            //Send mail to all the other users who answered, commented or gave thumb up to any answer
            //$queryCmd = "(select t1.displayname,t1.userId userId,t1.email,t1.firstname from messageTable m1,tuser t1 where m1.threadId IN ('$threadId') and m1.fromOthers  = 'user' and m1.userId = t1.userid and m1.status not in ('deleted','abused') and m1.parentId!=0 and m1.msgId!=$msgId) UNION (select t1.displayname,t1.userid userId,t1.email,t1.firstname from digUpUserMap d1, tuser t1 where d1.productId IN (select msgId from messageTable where threadId=$threadId) and d1.product = 'qna' and d1.userId=t1.userid and d1.userId NOT IN ('$questionUserId','$commenterUserId'))";
	    //We need to split this query to improve its execution time
	    /***
        // Commenting this code as we need to send New Answer mail only to question owner as of now(MAB-1507)
        $queryCmd1 = "select msgId from messageTable where threadId=?";
	    $queryRes=$dbHandle->query($queryCmd1, array($threadId));
	    $msgIdsList="";
	    foreach ($queryRes->result_array() as $msgElement) {
		    $msgIdsList .= ($msgIdsList!="")?",".$msgElement['msgId']:$msgElement['msgId'];
	    }
            $queryCmd = "(select t1.displayname,t1.userId userId,t1.email,t1.firstname,t1.lastname from messageTable m1,tuser t1 where m1.threadId IN ('$threadId') and m1.fromOthers  = 'user' and m1.userId = t1.userid and m1.status not in ('deleted','abused') and m1.parentId!=0 and m1.msgId!=$msgId)";
	    //End Modifications
            $contentArr['page_name'] = isset($_POST['page_name'])?$this->input->post('page_name'):'';
            $Result = $dbHandle->query($queryCmd);
            $userIdList = array();
            foreach($Result->result_array() as $row) {
                if((!(in_array($row['userId'],$userIdList))) && ($commenterUserId!=$row['userId'])) {
                    array_push($userIdList, $row['userId']);
                    $mailerUserId = $row['userId'];
                    $mailerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                    $mailerLname = (trim($row['lastname']) != '')?$row['lastname']:'';
                    $toEmail = $row['email'];
                    $urlToBeSentInMail = $urlOfLandingPage;
                    $subject = $answerOwnerName." posted an answer to $questionOwnerName's question.";
                    $fromAddress="noreply@shiksha.com";
                    $contentArr['type'] = "new-answer-to-all-user";
                    $userEmail = $toEmail;
                    $res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$mailerUserId);
                    $contentArr['urlOfUntitledQues']='';
                    if((int)$res['reputationPoints']>25 && (int)$res['reputationPoints']!=9999999) {
                        $urlOfLandingPageUQ = SHIKSHA_ASK_HOME;
                        $contentArr['urlOfUntitledQues'] = $urlOfLandingPageUQ.'/messageBoard/MsgBoard/discussionHome/1/4/1/untitledQuestion';
                    }
                    $contentArr['seoUrl'] = $urlToBeSentInMail;
                    $contentArr['ratingUrl'] = SHIKSHA_HOME.'/messageBoard/MsgBoard/mailAction/rating/'.$mailerUserId."/".$threadId."/".$msgId;
                    $contentArr['NameOfUser'] = $mailerName;
                    $contentArr['lastNameofUser'] = $mailerLname;
                    $contentArr['msgTxt'] = strlen($answerText)>300?substr($answerText,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$answerText;
                    $contentArr['questionOwnerName'] = $questionOwnerName;
                    $contentArr['questionText'] = $questionText;
                    $contentArr['answerOwnerName'] = $answerOwnerName;
                    $contentArr['receiverId'] = $mailerUserId;
                    //$content=$this->load->view("search/searchMail",$contentArr,true);
                    //$AlertClientObj = new Alerts_client();
                    //$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
                    Modules::run('systemMailer/SystemMailer/answerPostToQuestionAllUser', $userEmail, $contentArr);
                }
            }
            */
            return;

        }elseif(($fromOthers == 'user') && ($mainAnswerId > 0)) {	//In case of a comment to an answer
        //$queryCmd = "(select m1.threadId,m1.msgTxt,m1.msgId,m1.parentId,t1.displayname,t1.userId userId,t1.email,m1.msgTxt,t1.firstname,'msg' tableFrom  from messageTable m1,tuser t1 where (m1.msgId IN ('".$threadId."','".$mainAnswerId."') OR m1.mainAnswerId = '".$mainAnswerId."') and m1.fromOthers  = 'user' and m1.userId = t1.userid and m1.status not in ('deleted','abused')) UNION (select m1.threadId,m1.msgTxt,m1.msgId,m1.parentId,t1.displayname,t1.userId userId,t1.email,m1.msgTxt,t1.firstname,'dig' tableFrom from messageTable m1,tuser t1, digUpUserMap d1 where m1.fromOthers  = 'user' and m1.msgId = d1.productId and m1.status not in ('deleted','abused') and d1.productId = $mainAnswerId and d1.userId=t1.userid and d1.digFlag=1 and d1.product='qna')";
            //$contentArr['course_id'] = isset($_POST['course_id'])?$this->input->post('course_id'):'';
            //$contentArr['institute_Id'] = isset($_POST['institute_id'])?$this->input->post('institute_id'):'';
            $contentArr['page_name'] = isset($_POST['page_name'])?$this->input->post('page_name'):'';
//                if($contentArr['page_name'] == 'myShortlistAnA_comment')
//                    {
//                        $this->load->builder('ListingBuilder','listing');
//			$listingBuilder = new ListingBuilder;
//			$instituteRepository = $listingBuilder->getInstituteRepository();
//			$result = $instituteRepository->find($contentArr['institute_Id']);
//			$contentArr['instituteName'] = $result->getName();
//                    }
            $queryCmd = "(select m1.threadId,m1.msgTxt,m1.msgId,m1.parentId,t1.displayname,t1.userId userId,t1.email,m1.msgTxt,t1.lastname,t1.firstname,'msg' tableFrom  from messageTable m1,tuser t1 where (m1.msgId IN (?) ) and m1.fromOthers  = 'user' and m1.userId = t1.userid and m1.status not in ('deleted','abused') AND m1.msgId != m1.threadId)
				UNION (select m1.threadId,m1.msgTxt,m1.msgId,m1.parentId,t1.displayname,t1.userId userId,t1.email,m1.msgTxt,t1.lastname,t1.firstname,'msg' tableFrom  from messageTable m1,tuser t1 where ( m1.mainAnswerId = ?) and m1.fromOthers  = 'user' and m1.userId = t1.userid and m1.status not in ('deleted','abused'))";
                $queryArray = array($threadId,$mainAnswerId);
            $Result = $dbHandle->query($queryCmd, array($queryArray,$mainAnswerId));
            foreach($Result->result_array() as $row) {
                if($row['parentId']==$row['threadId'] && $row['tableFrom']=='msg') {	//If it is the answer, get the answer text, name
                    $answerUserId = $row['userId'];
                    $answerText = $this->changeTextForAtMention($row['msgTxt']);
                    $answerOwnerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                }
                else if($row['msgId']==$msgId && $row['tableFrom']=='msg') {	//If this is the same comment, get the commenter name, comment text
                        $commenterUserId = $row['userId'];
                        $commentTxt = $this->changeTextForAtMention($row['msgTxt']);
                        $commenterName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                        $commenterlastName = (trim($row['lastname']) != '')?$row['lastname']:'';
                        
                    }
                    else if($row['parentId']==0) {
                            $questionOwnerId = $row['userId'];
                            $questionText = $row['msgTxt'];
                        }
            }
            $this->load->model('UserPointSystemModel');
            //$res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$questionOwnerId );
            $userIdList = array();
            foreach($Result->result_array() as $row) {
                if((!(in_array($row['userId'],$userIdList)))&&($row['userId']!=$userId)) {
                    array_push($userIdList, $row['userId']);
                    $toEmail = $row['email'];
                    $contentArr['receiverId'] = $row['userId'];
                    //if($contentArr['page_name']=='myShortlistAnA_comment'){
                    //    $navigateTo = 'nav-'.$contentArr['course_id'].'-ask';
                    //    $urlOfLandingPage = SHIKSHA_HOME.'/my-shortlist#'.$navigateTo;
                    //}else{
                        $urlOfLandingPage = getSeoUrl($threadId,'question',$questionText);
                    //}
                    $urlToBeSentInMail = $urlOfLandingPage;
                    if($row['userId'] == $answerUserId) {
                        $subject = $commenterName." commented on your answer.";
                        $contentArr['answerOwnerName'] = "your";
                    }
                    else {
                        $subject = $commenterName." commented on ".$answerOwnerName."'s answer.";
                        $contentArr['answerOwnerName'] = $answerOwnerName."'s";
                    }
                    if($answerUserId==$commenterUserId) {
                        $subject = $commenterName." commented on their own answer.";
                        $contentArr['answerOwnerName'] = "their own";
                        $contentArr['answerDisplayName'] = $answerOwnerName."'s";
                    }
                    //$res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$row['userId']);
                    $contentArr['urlOfUntitledQues'] ='';
                    //if($res['reputationPoints']>25 && $res['reputationPoints']!=9999999) {
                        //error_log("88888888888888");
                        //$urlOfLandingPageUQ = SHIKSHA_ASK_HOME;
                        //$contentArr['urlOfUntitledQues'] = $urlOfLandingPageUQ.'/messageBoard/MsgBoard/discussionHome/1/4/1/untitledQuestion';
                    //}
                    $fromAddress="noreply@shiksha.com";
                    $contentArr['type'] = "new-reply-to-answer";
                    $userEmail = $row['email'];
                    $contentArr['seoUrl'] = $urlToBeSentInMail;
                    $contentArr['ratingUrl'] = SHIKSHA_HOME.'/messageBoard/MsgBoard/mailAction/rating/'.$row['userId']."/".$threadId."/".$mainAnswerId;
                    $contentArr['NameOfUser'] = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                    $contentArr['lastNameofUser'] = (trim($row['lastname']) != '')?$row['lastname']:'';
                    $contentArr['msgTxt'] = strlen($answerText)>300?substr($answerText,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$answerText;
                    $contentArr['commenterName'] = $commenterName;
                    $contentArr['commentTxt'] = strlen($commentTxt)>300?substr($commentTxt,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$commentTxt;
                    $contentArr['mail_subject'] = $subject;
                    //$content=$this->load->view("search/searchMail",$contentArr,true);
                    //$AlertClientObj = new Alerts_client();
                    //$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
                    Modules::run('systemMailer/SystemMailer/commentPostOnAnswer', $userEmail, $contentArr);
                }
            }
            return;
        }
        else if(($fromOthers == 'discussion' || $fromOthers == 'announcement' || $fromOthers == 'review' || $fromOthers == 'eventAnA') && ($mainAnswerId == $parentId)) {	//In case of Comment to a Post
                $sendOwnerMail = true;
                //$queryToCheckAlert = "select count(*) alert_set from alert_user_preference alp , messageTable m1  where alertType = 'byComment' and alp.user_id = m1.userId and m1.msgId = ? and alert_value_id = ? and mail = 'on' and STATE = 'on'";
                //$ResultOfAlert = $dbHandle->query($queryToCheckAlert,array($threadId,$threadId));
                //$row = $ResultOfAlert->row();
                //if($row->alert_set <= 0) {
                    //$sendOwnerMail = false;
                //}
                //Send mail to the Post Owner
                $queryCmd = "select m1.threadId,m1.msgTxt,m1.parentId,m1.mainAnswerId,t1.displayname,t1.userId userId,t1.email,m1.msgTxt,t1.firstname from messageTable m1,tuser t1 where m1.msgId IN (?) and m1.fromOthers  = ? and m1.userId = t1.userid and m1.status not in ('deleted','abused')";
                $queryArray = array($threadId,$msgId,$mainAnswerId);
                $Result = $dbHandle->query($queryCmd,array($queryArray,$fromOthers));
                foreach($Result->result_array() as $row) {
                    if($row['parentId']==$row['threadId']) {
                        $questionUserId = $row['userId'];
                        $questionText = $this->changeTextForAtMention($row['msgTxt']);
                        $questionOwnerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                        $questionOwnerMail = $row['email'];
                    }
                    else if($row['parentId']==$row['mainAnswerId']) {
                            $commenterUserId = $row['userId'];
                            $answerText = $this->changeTextForAtMention($row['msgTxt']);
                            $answerOwnerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                        }
                }
                $contentArr['page_name'] = isset($_POST['page_name']) ? $this->input->post('page_name'):'';
                $toEmail = $questionOwnerMail;
                $urlOfLandingPage = getSeoUrl($threadId,$fromOthers,$questionText);
                $urlToBeSentInMail = $urlOfLandingPage;
                $fromAddress="noreply@shiksha.com";
                $contentArr['entityType'] = $fromOthers;
                $contentArr['questionOwnerName'] = $questionOwnerName;
                $contentArr['questionText'] = $questionText;
                $contentArr['answerOwnerName'] = $answerOwnerName;
                if($sendOwnerMail && ($questionUserId != $commenterUserId) ) {
                    $subject = $answerOwnerName." commented on your $fromOthers.";
                    $contentArr['type'] = "new-comment-to-post";
                    $userEmail = $toEmail;
                    $contentArr['owner'] = true;
                    $contentArr['seoUrl'] = $urlToBeSentInMail;
                    $contentArr['NameOfUser'] = $questionOwnerName;
                    $contentArr['msgTxt'] = strlen($answerText)>300?substr($answerText,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$answerText;
                    $contentArr['receiverId'] = $questionUserId;
                    $contentArr['mail_subject'] = $subject;

                    Modules::run('systemMailer/SystemMailer/commentPostOnEntity', $userEmail, $contentArr);
                }

                //Send mail to all the other users who commented on the Post
                $queryCmd = "select t1.displayname,t1.userId userId,t1.email,t1.firstname from messageTable m1,tuser t1 where m1.threadId = ? and m1.fromOthers  = ? and m1.userId = t1.userid and m1.status not in ('deleted','abused') and m1.parentId!=0 and m1.msgId!= ?";
                $Result = $dbHandle->query($queryCmd,array($threadId,$fromOthers,$msgId));
                $userIdList = array();
                foreach($Result->result_array() as $row) {
                    if((!(in_array($row['userId'],$userIdList))) && ($commenterUserId!=$row['userId']) && ($questionUserId!=$row['userId'])) {
                        array_push($userIdList, $row['userId']);
                        $mailerUserId = $row['userId'];
                        $mailerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                        $toEmail = $row['email'];
                        $urlToBeSentInMail = $urlOfLandingPage;
                        $subject = $answerOwnerName." commented on $questionOwnerName's $fromOthers.";
                        $contentArr['type'] = "new-comment-to-post";
                        $userEmail = $toEmail;
                        $contentArr['owner'] = false;
                        $contentArr['seoUrl'] = $urlToBeSentInMail;
                        $contentArr['NameOfUser'] = $mailerName;
                        $contentArr['msgTxt'] = strlen($answerText)>300?substr($answerText,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$answerText;
                        $contentArr['mail_subject'] = $subject;
                        $contentArr['receiverId'] = $mailerUserId;
                        Modules::run('systemMailer/SystemMailer/commentPostOnEntity', $userEmail, $contentArr);
                    }
                }

                return;

            }
            else if(($fromOthers == 'discussion' || $fromOthers == 'announcement' || $fromOthers == 'review' || $fromOthers == 'eventAnA') && ($mainAnswerId != $parentId)) {	//In case of Reply to a Comment on Post
                //Send mail to the Comment Owner
                    $queryCmd = "select m1.msgId, m1.threadId,m1.msgTxt,m1.parentId,m1.mainAnswerId,t1.displayname,t1.userId userId,t1.email,m1.msgTxt,t1.firstname from messageTable m1,tuser t1 where m1.msgId IN (?) and m1.fromOthers  = ? and m1.userId = t1.userid and m1.status not in ('deleted','abused')";
                    $queryArray = array($threadId,$msgId,$mainAnswerId,$parentId);
                    $Result = $dbHandle->query($queryCmd,array($queryArray,$fromOthers));
                    foreach($Result->result_array() as $row) {
                        if($row['msgId']==$parentId) {	//Get the details of the Comment and its owner
                            $commenterUserId = $row['userId'];
                            $answerText = $this->changeTextForAtMention($row['msgTxt']);
                            $answerOwnerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                            $commenterOwnerMail = $row['email'];
                        }
                        else if($row['msgId']==$msgId) {	//Get the details of the Reply and its owner
                                $replyUserId = $row['userId'];
                                $replyText = $this->changeTextForAtMention($row['msgTxt']);
                                $replyOwnerName = (trim($row['firstname']) != '')?$row['firstname']:$row['displayname'];
                            }
                    }
                    $toEmail = $commenterOwnerMail;
                    $urlOfLandingPage = getSeoUrl($threadId,$fromOthers,$questionText);
                    //$urlToBeSentInMail = $MailerClient->generateAutoLoginLink(1,$toEmail,$urlOfLandingPage);
                    $fromAddress="noreply@shiksha.com";
                    $userEmail = $toEmail;
                    $subject = $replyOwnerName." replied to your comment.";

                    $contentArr['entityType'] = $fromOthers;
                    $contentArr['commenterOwnerName'] = $answerOwnerName;
                    $contentArr['replyOwnerName'] = $replyOwnerName;
                    $contentArr['type'] = "new-reply-to-post";
                    $contentArr['seoUrl'] = $urlOfLandingPage;
                    $contentArr['commentTxt'] = strlen($answerText)>300?substr($answerText,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'">more</a>':$answerText;
                    $contentArr['replyTxt'] = strlen($replyText)>300?substr($replyText,0,297).'...'.'<a href="'.$contentArr['seoUrl'].'<!-- #AutoLogin --><!-- AutoLogin# -->">more</a>':$replyText;
                    $contentArr['receiverId'] = $commenterUserId;
                    $contentArr['mail_subject'] = $subject;

                    Modules::run('systemMailer/SystemMailer/replyPostOnComment', $toEmail, $contentArr);
                    return;
                    //$content=$this->load->view("search/searchMail",$contentArr,true);
                    //$AlertClientObj = new Alerts_client();
                    //$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
                    //return;

                }elseif(($fromOthers == 'blog') && ($mainAnswerId == 0)) {	//In case of Comment on Article
                //In case of Comment, we will also send a mail to the Article owner
                    $queryCmd = "select m1.threadId,t1.displayname,t1.email,t1.firstname,m1.msgTxt,m1.userId,bt.blogId,bt.blogType,bt.blogTitle,(select tu.displayName from tuser tu where userid = ?) commentUserDisplayName from messageTable m1,tuser t1, blogTable bt where m1.threadId = bt.discussionTopic and m1.msgId = ? and m1.fromOthers  = 'blog' and bt.userId = t1.userid and m1.status not in ('deleted','abused') and bt.status in ('live')";
                    $Result = $dbHandle->query($queryCmd, array($userId,$msgId));
                    $row = $Result->row();
                    $toEmail = $row->email;
                    $contentArr = array();
                    $contentArr['ownerName'] = $row->displayname;
                    $contentArr['commenterName'] = $row->commentUserDisplayName;
                    $contentArr['blogTitle'] = $row->blogTitle;;
                    $subject = $row->commentUserDisplayName." commented on your article.";
                    $urlOfLandingPage = getSeoUrl($row->blogId,'blog',$blogTitle) ;
                    $urlToBeSentInMail = $MailerClient->generateAutoLoginLink(1,$toEmail,$urlOfLandingPage);
                    $contentArr['url'] = $urlToBeSentInMail;
                    $contentArr['type'] = "sendCommentMailOwner";
                    $contentArr['headingText'] = $row->commentUserDisplayName." has posted a new comment on your article posted on Shiksha.com.";
                    $contentArr['entityType'] = 'comment';
                    $htmlContent = $this->load->view("search/searchMail",$contentArr,true);
                    $alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,$toEmail,$subject,$htmlContent,"html",time(),"n",array(),"",$bccEmail);
                    //End mail for Article owner

                    $typeOfEmail = "comment-on-article";
                    $queryCmd = "select m1.threadId,t1.displayname,t1.email,t1.firstname,m1.msgTxt,m1.userId,bt.blogId,bt.blogType,bt.blogTitle from messageTable m1,tuser t1, blogTable bt where m1.threadId = bt.discussionTopic and m1.msgId = ? and m1.fromOthers  = 'blog' and m1.userId = t1.userid and m1.status not in ('deleted','abused') and bt.status in ('live')";
                    $Result = $dbHandle->query($queryCmd,array($msgId));
                    $row = $Result->row();
                    $toEmail = "manisha.verma@naukri.com";
                    $blogTitle = $row->blogTitle;
                    $urlOfLandingPage = getSeoUrl($row->blogId,'blog',$blogTitle) ;
                    $urlToBeSentInMail = $MailerClient->generateAutoLoginLink(1,$toEmail,$urlOfLandingPage);
                    $subject = "An article has received a comment/reply.";
                    $this->sendInternalArticleReplyMailer('comment',$contentArr['blogTitle'],$urlOfLandingPage);
                }elseif(($fromOthers == 'blog') && ($mainAnswerId > 0)) {
                //In case of Article replies, we will send mail to the Article owner, Comment owner and all the reply owners
                    $userIdList = array();
                    //$queryCmd = "select m1.threadId,m1.parentId,t1.displayname,t1.email,t1.firstname,m1.msgTxt,m1.userId,bt.blogId,bt.blogType,bt.blogTitle,(select tu.displayName from tuser tu where userid = ".$userId.") commentUserDisplayName from messageTable m1,tuser t1, blogTable bt where m1.threadId = bt.discussionTopic and (m1.msgId = ".$parentId." OR m1.mainAnswerId = ".$parentId." OR m1.msgId = ".$threadId.") and m1.fromOthers  = 'blog' and m1.userId = t1.userid and t1.userid != ".$userId." and m1.status not in ('deleted','abused') and bt.status in ('live')";
		    $queryCmd = "(select m1.threadId,m1.parentId,t1.displayname,t1.email,t1.firstname,m1.msgTxt,m1.userId,bt.blogId,bt.blogType,bt.blogTitle,(select tu.displayName from tuser tu where userid = ?) commentUserDisplayName from messageTable m1,tuser t1, blogTable bt where m1.threadId = bt.discussionTopic and (m1.msgId = ?) and m1.fromOthers  = 'blog' and m1.userId = t1.userid and t1.userid != ? and m1.status not in ('deleted','abused') and bt.status in ('live')) UNION (select m1.threadId,m1.parentId,t1.displayname,t1.email,t1.firstname,m1.msgTxt,m1.userId,bt.blogId,bt.blogType,bt.blogTitle,(select tu.displayName from tuser tu where userid = ?) commentUserDisplayName from messageTable m1,tuser t1, blogTable bt where m1.threadId = bt.discussionTopic and (m1.mainAnswerId = ?) and m1.fromOthers  = 'blog' and m1.userId = t1.userid and t1.userid != ? and m1.status not in ('deleted','abused') and bt.status in ('live')) UNION (select m1.threadId,m1.parentId,t1.displayname,t1.email,t1.firstname,m1.msgTxt,m1.userId,bt.blogId,bt.blogType,bt.blogTitle,(select tu.displayName from tuser tu where userid = ?) commentUserDisplayName from messageTable m1,tuser t1, blogTable bt where m1.threadId = bt.discussionTopic and (m1.msgId = ?) and m1.fromOthers  = 'blog' and m1.userId = t1.userid and t1.userid != ? and m1.status not in ('deleted','abused') and bt.status in ('live'));";
                    $Result = $dbHandle->query($queryCmd, array($userId,$parentId,$userId,$userId,$parentId,$userId,$userId,$threadId,$userId));
                    //Send mail to all the Reply owner and the comment owner
                    foreach($Result->result_array() as $row) {
                        if(!(in_array($row['userId'],$userIdList))) {
                            array_push($userIdList, $row['userId']);
                            $contentArr = array();
                            $contentArr['ownerName'] = $row['displayname'];
                            $contentArr['commenterName'] = $row['commentUserDisplayName'];
                            $contentArr['type'] = "sendCommentMailOwner";
                            $contentArr['entityType'] = 'reply';
                            $toEmail = $row['email'];
                            $contentArr['blogTitle'] = $row['blogTitle'];
                            $urlOfLandingPage = getSeoUrl($row['blogId'],'blog',$row['blogTitle']) ;
                            $urlToBeSentInMail = $MailerClient->generateAutoLoginLink(1,$toEmail,$urlOfLandingPage);
                            $contentArr['url'] = $urlToBeSentInMail;
                            if($row['parentId'] != 0) {
                                $contentArr['headingText'] = $row['commentUserDisplayName']." has posted a new reply on the comments you posted on Article below on Shiksha.com.";
                                $subject = $row['commentUserDisplayName']." replied to your comment on Shiksha.com";
                            }
                            else {
                                $contentArr['headingText'] = $row['commentUserDisplayName']." has posted a new reply to a comment posted on your article on Shiksha.com.";
                                $subject = $row['commentUserDisplayName']." replied to a comment on your Article.";
                            }
                            $content = $this->load->view("search/searchMail",$contentArr,true);
                            $response= $AlertClientObj->externalQueueAdd("12",$fromAddress,$toEmail,$subject,$content,"html",time(),"n",array(),"",$bccEmail);
                        }
                    }
                    $this->sendInternalArticleReplyMailer('reply',$contentArr['blogTitle'],$urlOfLandingPage);
                    return;
                }
        if(($Result == null) || ($urlToBeSentInMail == "")) {
            return;
        }

        foreach ($Result->result_array() as $row) {
            $row['urlOfLandingPage'] = $urlOfLandingPage;
            $row['urlToBeSentInMail'] = $urlToBeSentInMail;
            $row['nameOfUser']=$nameOfUser;
            array_push($contentArray,$row);
        }

        $htmlContent = $this->load->view("search/searchMail",array('contentArray' => $contentArray,'type' => $typeOfEmail),true);
        $alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,$toEmail,$subject,$htmlContent,"html",time(),"n",array(),"",$bccEmail);
        return;
    }

	/*
	*	send email to the user who posted it. Second argument tells if the mail is sent from counseller
	*/
    function sendEmail($threadId,$fromExpert) {
        $AlertClientObj = new Alerts_client();
        $fromAddress=ADMIN_EMAIL;
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        //get the to email from threadId
        $toEmail='';
        $displayName='';
        $msgTxt='';
        $msgCount='';
        $origMsgTxt="";
        $email='';
        $queryCmd = "select tuser.email,tuser.displayname,tuser.email,messageTable.msgTxt,messageTable.msgCount,messageTable.fromOthers from tuser, messageTable where tuser.userid = messageTable.userId and msgId=?";
        error_log_shiksha( 'postReply query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($threadId));
        $passPhrase='';
        foreach ($query->result() as $row) {
            $toEmail=$row->email;
            $displayName=$row->displayname;
            $msgTxt=$row->msgTxt;
            $origMsgTxt=$row->msgTxt;
            $msgCount=$row->msgCount;
            $email=$row->email;
            $fromOthers=$row->fromOthers;
        }

        if(($msgCount!=1 && $fromExpert==0) || strcmp($fromOthers,"user")!=0) {
            return;
        }
        if($fromExpert==0) {
            $subject='Your Question has been answered';
            $data['displayname'] = $displayName;
            $data['level'] = 'Member';
            $data['question'] = $origMsgTxt;
            $data['url']=getSeoUrl($threadId,'question',$msgTxt);
            $data['useremail']=$email;
            $content=$this->load->view('messageBoard/replyMail',$data,true);
        }else {
            $subject='Your Question has been answered by Expert';
            $data['displayname'] = $displayName;
            $data['level'] = 'Expert';
            $data['question'] = $origMsgTxt;
            $data['url']=getSeoUrl($threadId,'question',$msgTxt);
            $data['useremail']=$email;
            $content=$this->load->view('messageBoard/replyMail',$data,true);
        }
        $alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,$toEmail,$subject,$content,"html");
        return;
    }

	/*
	* Insert related colleges in to DB
	*/
    function insertRelatedData() {
        $appID=1;
	$dbHandle = $this->_loadDatabaseHandle('write');
        $this->load->library('messageboardconfig');
        $getProductNameQuery="select * from product where product_id=?";

        $queryCmd = "update messageTable set relatedContent=? where msgId = ?";
        $query = $dbHandle->query($queryCmd,array($this->input->post('relatedData'),$this->input->post('threadId')));
    }

	/*
	*	post Expert reply to a given question in a particular board $appID,$parentThread_id
	*	Done for ASK Naukri Integration
	*	User Id is of the Expert
	*       There is hack here, all expert reply is inserted at top
	*/
    function postExpertReply($request) {
        $parameters = $request->output_parameters();
        $appID=1;
        $userId=$parameters['0'];
        $msgTxt=$parameters['1'];
        $threadId=$parameters['2'];
        $requestIP=$parameters['3'];
        $categoryList=$parameters['4'];
        $tags=$parameters['5'];
        $fromOthers='user';


        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        /*
		$queryCmd = "select passPhrase from messageExpertTable where threadId=$threadId";
		error_log_shiksha( 'postReply query cmd is ' . $queryCmd,'qna');
		$query = $dbHandle->query($queryCmd);
		$passPhrase='';
		foreach ($query->result() as $row){
			$passPhrase=$row->passPhrase;
		}
        */
        //check if pass phrase is correct
        //$computedPassPhrase=md5($threadId.'AskShiksha@2008'.$passPhrase);
        //error_log_shiksha('postReply can not create db handle '.$computedPassPhrase,'qna' );
		/*if($computedPassPhrase!=$passKeyPhrase){
			$response = array(array('Result'=>array('You can not post reply to this topic')),'struct');
			return $this->xmlrpc->send_response($response);

		}
		*/
        $path="$threadId.0";
        $data =array(
            'userId'=>$userId,
            'msgTxt'=>$msgTxt,
            'threadId'=>$threadId,
            'parentId'=>$threadId,
            'requestIP'=>$requestIP,
            'fromOthers'=>$fromOthers,
            'path'=>$path
        );

        $queryCmd = $dbHandle->insert_string($this->messageboardconfig->messageTable,$data);
        $query = $dbHandle->query($queryCmd);

        $msgId=$dbHandle->insert_id();

        error_log_shiksha( 'postReply query last inserted id is ' . $msgId,'qna');


        $response = array($msgId,'int');

        //update PopularityView
        $this->updatePopularityView($threadId);

        //send email when ever expert reply to your message

        //update messageExpertTable
        $queryCmd = "update messageExpertTable set passPhrase=FLOOR(RAND()*10000), replyDate=now(), categoryTags= ? where threadId=?";
        error_log_shiksha( 'postReply query cmd is ' . $queryCmd,'qna');

        if(!$dbHandle->query($queryCmd,array($tags,$threadId))) {
            error_log_shiksha( 'postReply failed' . $queryCmd,'qna');
        }
        return $this->xmlrpc->send_response($response);

    }


	/*
	 * this function updates the replared college. Not used any where
	 */
    function updateRelatedCollege($threadIdArr) {
	$dbHandle = $this->_loadDatabaseHandle('write');
        //$threadStr = implode(",", $threadIdArr);
        $queryCmd = "select threadId,msgId,msgTxt from messageTable where threadId in (?) and status in ('live','closed')";
        $msgIdArr = array();
        $query = $dbHandle->query($queryCmd,array($threadIdArr));
        $count=0;
        $viewCount=0;
        $totText = "";
        foreach ($query->result() as $row) {
            $text =$row->msgTxt;
            $totText .= " ".$text;
        }
    }

	/*
	*	//Popularity Count Formular
	*	//$popularityCount = (0.75 * $count) + (0.25*$viewCount);
	*	Update popularity View for the thread
	*
	*/
    function updatePopularityView($threadId,$fromOthers) {
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');
        //$queryCmd = "select (count(*)-1) count, viewCount, ifnull((1/datediff(now(), creationDate ) + log(1000,viewCount)*log(10,(count(*)-1))/10 ),0) as popularityCount  from messageTable where threadId=$threadId and status in ('live','closed') group by threadId ";
        //$queryCmd = "select (count(*)-1) count, viewCount, ifnull((viewCount*msgCount/pow(datediff(now(),creationDate),1.3)),0) as popularityCount  from messageTable where threadId=$threadId and status in ('live','closed') group by threadId ";
        $initialBias = 25;
        $answersBias = 5;
        $viewsBias = 1;
        $stdTenure = 20;
        if($fromOthers == "blog"){
            $queryCmd = "select (count(*)) count, viewCount, ifnull(($initialBias + messageTable.msgCount*$answersBias + messageTable.viewCount*$viewsBias )/(1+exp(2*datediff(now(), messageTable.creationDate )/$stdTenure)),0) as popularityCount  from messageTable where threadId=? AND mainAnswerId > -1 AND status in ('live','closed') group by threadId ";
        }
        else{
            $queryCmd = "select (count(*)) count, viewCount, ifnull(($initialBias + messageTable.msgCount*$answersBias + messageTable.viewCount*$viewsBias )/(1+exp(2*datediff(now(), messageTable.creationDate )/$stdTenure)),0) as popularityCount  from messageTable where threadId=? and parentId=? and status in ('live','closed') group by threadId ";    
        }
        
        error_log_shiksha( 'postReply query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($threadId, $threadId));
        $count=0;
        $viewCount=0;
        foreach ($query->result() as $row) {
            $count=$row->count;
            $viewCount=$row->viewCount;
            $popularityCount = $row->popularityCount;
        }
        //Popularity Count Formular
        $popularityCount = (0.75 * $count) + (0.25*$viewCount);
        $queryCmd = "update messageTable set msgCount=$count, popularityView=$popularityCount where msgId=?";
        error_log_shiksha( 'updatePopularityView query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($threadId));
        return 1;

    }
    //getreputationpoints
    function getUserReputationPoints($request) {
	$dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $this->load->model('UserPointSystemModel');
        $res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$userId);
        $response = json_encode(array($res,'array'));
        return $this->xmlrpc->send_response($response);

    }
    //

	/*
	*	Report Abuse for a given primary key in message table $appID,$board_id,$thread_id
	*/
    function reportAbuse($request) {
    //update messageTable set abuse=messageTable.abuse+1 where boardId=1 and msgId=1;
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
        $userId=$parameters['2'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryToExe = 'select ifnull((select count(msgId) from messageTable where msgId = ? and userId = ?),0) userCommentFlag ';
        $Result = $dbHandle->query($queryToExe, array($msgId,$userId));
        $row = $Result->row();
        if($row->userCommentFlag != 0) {
            $response = array('SUCE','string'); //Same user comment error.
            return $this->xmlrpc->send_response($response);
        }

        $queryCmd = 'update messageTable set abuse=messageTable.abuse+1 where msgId=?';

        error_log_shiksha( 'reportAbuse query cmd is ' . $queryCmd,'qna');

        $query = $dbHandle->query($queryCmd, array($msgId));
        $affectedRows=$dbHandle->affected_rows();
        $response = array($affectedRows,'int');
        return $this->xmlrpc->send_response($response);

    }

	/*
	*	Get details for search
	*/
    function getDetailsForSearch($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $reqArray=$parameters['1'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = "select m1.*,m2.*,(select group_concat(countryTable.name) from countryTable where countryTable.countryId=m3.countryId) as countryName,t1.displayName,t1.lastlogintime,t1.avtarimageurl userImage,t1.profession,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1 left join messageCategoryTable m2 on m1.threadId=m2.threadId , messageCountryTable m3 , tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where m1.parentId=0 and m1.status in ('live','closed') and m1.userId=t1.userid and m1.threadId = m3.threadId";

        $i=0;
        foreach ($reqArray as $threadId) {
            if($i>0) {
                $queryCmd .= ' or ' ;
            }
            $i++;
            $queryCmd .= " (m1.threadId=".$dbHandle->escape($threadId).") ";
        }
        $queryCmd .= ' ) ';
        error_log_shiksha( 'getDetailsForSearch query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($threadId));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

	/*
	 * This function is called by search to index the threads
	 */
    function getBoardForIndex($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadId=$parameters['1'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryCmd = "select m1.listingType, listingTypeId ,m1.msgId, m1.msgTxt, m1.msgCount, m1.viewCount, m1.creationDate,m1.fromOthers , tuser.* ,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level, GROUP_CONCAT(DISTINCT messageCountryTable.countryId SEPARATOR ',') countryId from messageTable m1, tuser LEFT JOIN userpointsystembymodule upv ON (tuser.userid = upv.userId and upv.modulename='AnA') , messageCountryTable  where m1.status in ('live','closed') and m1.threadId=? and m1.userId=tuser.userid and messageCountryTable.threadId = m1.threadId group by m1.msgId order by path limit 2";

        error_log_shiksha('getBoardForIndex query is '.$queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($threadId));
        $msgArray = array();
        $resultArray=$query->result_array();
        $question=array();
        if(count($resultArray)==1) {
            foreach ($resultArray as $question) {
                $queryCmd = "select m2.* from messageCategoryTable m2 where m2.threadId=?";
                error_log_shiksha('getBoardForIndex query is '.$queryCmd,'qna');
                $catResult=$dbHandle->query($queryCmd, array($question['msgId']));
                $catList="";
                foreach ($catResult->result_array() as $catElement) {
                    if(isset($catList)&&$catList!="") {
                        $catList=$catList.",".$catElement['categoryId'];
                    }
                    else {
                        $catList=$catElement['categoryId'];
                    }
                }
                $question['categoryList']=$catList;
                $displayType = $question['fromOthers'];
                if($displayType == 'user')
                 $displayType = 'question';
                $question['url'] = getSeoUrl($question['msgId'],$displayType,$question['msgTxt']);
                $question['questionUserData'] = serialize(array('id'=>$question['userid'],'displayname'=>$question['displayname'],'userImage'=>$question['avtarimageurl'],'userLevel'=>$question['level'],'profession'=>$question['profession']));
                $question['answer']= "";
                $question['answerUserData'] = serialize(array());
                //cbhushan-- changes to add instituteName and location in indexing.

                if(($question['listingTypeId'] != null) && ($question['listingType'] == 'institute')) {
                    $queryCmd = "select listing_title,countryCityTable.city_name as city_name from listings_main,institute_location_table,countryCityTable where listing_type_id=? and listings_main.status='live' and listing_type_id=institute_location_table.institute_id and institute_location_table.city_id=countryCityTable.city_id and listing_type='institute' and institute_location_table.status='live'";
                    $institute = $dbHandle->query($queryCmd, array($question['listingTypeId']));
                    foreach($institute->result_array() as $instituteElement) {
                        $question['instituteName'] = $instituteElement['listing_title'];
                        $question['location']      = $instituteElement['city_name'];
                    }
                    //	$question['docBoost'] = true;
                }


                array_push($msgArray,array($question,'struct'));
            }
        }
        else {
            $question=$resultArray[0];
            $answer=$resultArray[1];
            $queryCmd = "select m2.* from messageCategoryTable m2 where m2.threadId=?";
            error_log_shiksha('getBoardForIndex query is '.$queryCmd,'qna');
            $catResult=$dbHandle->query($queryCmd, array($question['msgId']));
            $catList="";
            foreach ($catResult->result_array() as $catElement) {
                if(isset($catList)&&$catList!="") {
                    $catList=$catList.",".$catElement['categoryId'];
                }
                else {
                    $catList=$catElement['categoryId'];
                }
            }
            $question['categoryList']=$catList;
            $displayType = $question['fromOthers'];
            if($displayType == 'user')
             $displayType = 'question';
            $question['url'] = getSeoUrl($question['msgId'],$displayType,$question['msgTxt']);
            $question['questionUserData'] = serialize(array('id'=>$question['userid'],'displayname'=>$question['displayname'],'userImage'=>$question['avtarimageurl'],'userLevel'=>$question['level'],'profession'=>$question['profession']));
            $question['answer']= $answer['msgTxt'];
            $question['answerUserData'] = serialize(array('id'=>$answer['userid'],'displayname'=>$answer['displayname'],'userImage'=>$answer['avtarimageurl'],'userLevel'=>$answer['level'],'profession'=>$answer['profession']));
            $question['answerTime']=$answer['creationDate'];
            error_log_shiksha("Shirish".print_r($answer,true),'qna');
            if(($question['listingTypeId'] != null) && ($question['listingType'] == 'institute')) {
                $queryCmd = "select listing_title,countryCityTable.city_name from listings_main,institute_location_table,countryCityTable where listing_type_id= ?  and listings_main.status='live' and listing_type_id=institute_location_table.institute_id and institute_location_table.city_id=countryCityTable.city_id and listing_type='institute' and institute_location_table.status='live' ";
                $institute = $dbHandle->query($queryCmd, array($question['listingTypeId']));
                foreach($institute->result_array() as $instituteElement) {
                    $question['instituteName'] = $instituteElement['listing_title'];
                    $question['location']      = $instituteElement['city_name'];
                }
                $queryCmd = 'select COUNT(msgId) numInstituteReplies from messageTable,listings_main where listings_main.listing_type_id=messageTable.listingTypeId and messageTable.threadId=? and listings_main.username=messageTable.userId';
                $query    = $dbHandle->query($queryCmd, array($threadId));
                foreach($query->result_array() as $instituteRepliesCount) {
                    if($instituteRepliesCount['numInstituteReplies'] > 0)
                        $question['docBoost'] = true;
                }

            }
            array_push($msgArray,array($question,'struct'));
        }
        if($question['listingTypeId'] != null) {
            $jsonObj = json_encode(array('numOfResults'=>'1','keyword'=>'something','start'=>'0','rows'=>'1','listingType'=>'question','outputType'=>'json','resultList'=>array(array('typeId'=>$question['msgId'],'type'=>'question','title'=>$question['msgTxt'],'url'=>$question['url'],'shortContent'=>"",'noOfComments'=>$question['msgCount'],'viewCount'=>$question['viewCount'],'creationDate'=>$question['creationDate'],'questionUserInfo'=>$question['questionUserData'],'answerUserInfo'=>$question['answerUserInfo'],'answer'=>$question['answer'],'answerTime'=>$question['answerTime']))));
            $appID = 12;
            $productName = $resultArray[0]['listingType'];
            $productId = $resultArray[0]['listingTypeId'];
            $relatedProduct = 'quesSameListing';
            error_log_shiksha("Shirish1223123 ; ".$appID.":".$productName.":".$productId.":".$relatedProduct.":".$jsonObj,'qna');
            $this->load->library('relatedClient');
            $RelatedClient = new RelatedClient();
            $relatedContent = $RelatedClient->mergeRelatedData($appID, $productName,$productId,$relatedProduct,$jsonObj);
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * Used in category pages to get the topics for a category
     */
    /* Not in use */
    /*
    function getTopicsForHomePageS($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $keyValue=$dbHandle->escape($parameters['2']);
        $startFrom=$parameters['3'];
        $count=$parameters['4'];
        $countryId=$parameters['5'];

        if($categoryId == '') {
            $categoryId = 1;
        }
        if($countryId == '') {
            $countryId = 1;
        }

        $messageDiscussionQuery = ",ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.threadId LIMIT 1),'') descriptionD ";
        $queryCmd = "select m1.*,m2.* $messageDiscussionQuery ,(select group_concat(countryTable.name) from countryTable where countryTable.countryId=m3.countryId) as countryName,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1, messageCategoryTable m2, messageCountryTable m3,tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA'), PageMsgBoardDb pdb where pdb.KeyId=".$keyValue." and CURDATE() >= pdb.StartDate and CURDATE() <= pdb.EndDate and m1.threadId=pdb.TopicId and m1.threadId=m2.threadId and m1.threadId=m3.threadId and m1.userId=t1.userId and m1.status in ('live','closed') and m1.fromOthers='user' and m2.categoryId in ($categoryId) and m3.countryId in (".$countryId.") and m1.parentId=0 order by m1.popularityView desc , m1.creationDate desc LIMIT $startFrom,$count";
        error_log_shiksha( 'getTopicsForHomePageS CMS query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd);
        //what are CMS Rows
        $cmsRows=$query->num_rows();
        //init the response array
        $msgArray = array();
        $threadIds="";
        //First Poupulate the array with what ever we can get CMS
        if($cmsRows >= 1) {
            foreach ($query->result_array() as $row) {
                if(strlen($threadIds)>0) {
                    $threadIds .= ' , ';
                }
                $threadIds .= $row['threadId'];
                $displayType = $row['fromOthers'];
                if($displayType == 'user')
                 $displayType = 'question';
                if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD']))
                    $row['url'] = getSeoUrl($row['threadId'],$displayType,$row['descriptionD'],'','',$row['creationDate']);
                else
                    $row['url'] = getSeoUrl($row['threadId'],$displayType,$row['msgTxt'],'','',$row['creationDate']);
                array_push($msgArray,array($row,'struct'));
            }
        }

        //Remaining get it from DB using some algo
        if(strlen($threadIds)==0) {
            $threadIds = "''";
        }
        if($cmsRows < $count) {
            $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,m2.* $messageDiscussionQuery ,(select group_concat(countryTable.name) from countryTable where countryTable.countryId=m3.countryId) as countryName,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1, messageCategoryTable m2, messageCountryTable m3,tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where m1.threadId=m2.threadId and m1.threadId=m3.threadId and m1.userId=t1.userId and m1.threadId not in ($threadIds) and m1.status in ('live','closed') and m1.fromOthers='user' and m2.categoryId in ($categoryId) and m3.countryId in (".$countryId.") and m1.parentId=0 order by m1.popularityView desc , m1.creationDate desc LIMIT $startFrom,".($count-$cmsRows);
            error_log_shiksha( 'getTopicsForHomePageS query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd);
            $DbRows=$query->num_rows();
            //push it to response array
            foreach ($query->result_array() as $row) {
                if(strlen($threadIds)>0) {
                    $threadIds .= ' , ';
                }
                $threadIds .= $row['threadId'];
                $displayType = $row['fromOthers'];
                if($displayType == 'user')
                 $displayType = 'question';
                if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD']))
                    $row['url'] = getSeoUrl($row['threadId'],$displayType,$row['descriptionD'],'','',$row['creationDate']);
                else
                    $row['url'] = getSeoUrl($row['threadId'],$displayType,$row['msgTxt'],'','',$row['creationDate']);
                array_push($msgArray,array($row,'struct'));
            }
            $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
            $query = $dbHandle->query($queryCmd);
            $totalRows = 0;
            foreach ($query->result() as $row) {
                $totalRows = $row->totalRows;
            }
        }

        //if still left get it from parent Category phew!
        $totalRowsLeft=$DbRows+$cmsRows;
        if($totalRowsLeft < $count) {
            $queryCmd = "select m1.*,m2.* $messageDiscussionQuery,(select group_concat(countryTable.name) from countryTable where countryTable.countryId=m3.countryId) as countryName,t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable m1, messageCategoryTable m2, messageCountryTable m3,tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where m1.threadId=m2.threadId and m1.threadId=m3.threadId and m1.userId=t1.userId and m1.threadId not in ($threadIds) and m1.status in ('live','closed') and m1.fromOthers='user' and m2.categoryId in (select parentId from categoryBoardTable where categoryBoardTable.boardId in ($categoryId)) and m3.countryId in (".$countryId.") and m1.parentId=0 order by m1.popularityView desc , m1.creationDate desc LIMIT $startFrom,".($count-$totalRowsLeft);
            error_log_shiksha( 'getTopicsForHomePageS query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd);
            $DbRows=$query->num_rows();
            //push it to response array
            foreach ($query->result_array() as $row) {
                $displayType = $row['fromOthers'];
                if($displayType == 'user')
                 $displayType = 'question';
                if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD']))
                    $row['url'] = getSeoUrl($row['threadId'],$displayType,$row['descriptionD'],'','',$row['creationDate']);
                else
                    $row['url'] = getSeoUrl($row['threadId'],$displayType,$row['msgTxt'],'','',$row['creationDate']);
                array_push($msgArray,array($row,'struct'));
            }
        }


        //Send the response back
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalCount'=>array($totalRows,'string'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }
    */

    /**
     * Get topics posted for a group
     */
    function getTopicsForGroups($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $response = array(array(),'struct');
        return $this->xmlrpc->send_response($response);

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $listingTypeId=$dbHandle->escape($parameters['1']);
        $listingType=$dbHandle->escape($parameters['2']);
        $startFrom=$parameters['3'];
        $count=$parameters['4'];
        $queryCmd =  "select x.*,IFNULL(MAX(messageTable.creationdate),x.creationDate) as lastdate,(select displayname from tuser where userId = IFNULL(messageTable.userId,x.userId))username from (select m1.msgTxt,m1.threadId,m1.viewCount,m1.msgCount,m1.creationDate,m1.userId from messageTable m1 where m1.parentId=0 and m1.status in ('live') and m1.listingTypeId=".$listingTypeId." and m1.listingType=".$listingType." and m1.fromOthers =".$listingType." order by creationDate desc LIMIT $startFrom,$count)x LEFT join messageTable on(messageTable.parentId = x.threadId) group by x.threadId order by x.creationDate desc";

        error_log_shiksha( 'getTopicsForListing CMS query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd);
        //what are listing Rows
        $listingRows=$query->num_rows();

        //init the response array
        $msgArray = array();
        $threadIds="";

        $queryCmd1 = "select count(*) as totalRows from messageTable m1 where m1.parentId=0 and m1.status in ('live') and m1.listingTypeId=".$listingTypeId." and m1.listingType=".$listingType." and m1.fromOthers =".$listingType." order by creationDate desc";
        $query1 = $dbHandle->query($queryCmd1);
        $row1 = $query1->row();
        $totalRows = $row1->totalRows;
        //First Poupulate the array with what ever we can get CMS
        foreach ($query->result_array() as $row) {

            $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
            $row['totalRows'] = $totalRows;
            array_push($msgArray,array($row,'struct'));
        }

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * Get topics posted by a institute listing page
     * This function is obsolete and not used anymore
     */
    /* Not in use */
    /*
    function getTopicsForListing($request) {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryCSV=$parameters['1'];
        $listingTypeId=$parameters['2'];
        $listingType=$parameters['3'];
        $startFrom=$parameters['4'];
        $count=$parameters['5'];
        $countryId=$parameters['6'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = "select m1.msgTxt,m1.threadId from messageTable m1 where m1.parentId=0 and m1.status in ('live') and m1.listingTypeId=? and m1.listingType=? and m1.fromOthers ='user' order by m1.msgCount asc LIMIT $startFrom,$count";

        error_log_shiksha( 'getTopicsForListing CMS query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd,array($listingTypeId,$listingType));
        //what are listing Rows
        $listingRows=$query->num_rows();
        //init the response array
        $msgArray = array();
        $threadIds="";
        //First Poupulate the array with what ever we can get CMS
        if($listingRows >= 1) {
            foreach ($query->result_array() as $row) {

                if(strlen($threadIds)>0) {
                    $threadIds .= ' , ';
                }
                $threadIds .= $row['threadId'];
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
                array_push($msgArray,array($row,'struct'));
            }
        }

        //Remaining get it from DB using some algo
        if(strlen($threadIds)==0) {
            $threadIds = "''";
        }
        if($listingRows < $count) {
            $queryCmd = "select distinct(m1.threadId),m1.msgTxt from messageTable m1, messageCategoryTable m2, messageCountryTable m3 where m1.threadId=m2.threadId and m1.threadId=m3.threadId and m1.threadId not in ($threadIds) and m1.parentId=0 and m1.status in ('live') and m2.categoryId in ($categoryCSV)  and m3.countryId in (".$countryId.")  and m1.fromOthers ='user' order by m1.msgCount asc LIMIT $startFrom,".($count-$listingRows);
            error_log_shiksha( 'getTopicsForListing query cmd is ' . $queryCmd,'qna');
            $query = $dbHandle->query($queryCmd);
            //push it to response array
            foreach ($query->result_array() as $row) {
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
                array_push($msgArray,array($row,'struct'));
            }
        }

        //Send the response back
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }
    */

    /**
     * This method is used to fetch the question for the activity landing page.(Question/Answer posted Page)
     * This function is obsolete and not used anymore
     *
     */
    function getQuestionForActivityLandingPages($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $threadId=$parameters['2'];
        $start=$parameters['3'];
        $count=$parameters['4'];
        $noOfAnswerUpperLimit = $parameters['5'];
        $catId=$dbHandle->escape($parameters['6']);
        $this->load->library('category_list_client');
        $Category_list_client = new Category_list_client();
        $Res = $Category_list_client->getSubToParentCategoryMapping(1);
        $catMapping = $Res['Mapping'];
        $parentCatId = -1;
        if($catId != -1) {
            $parentCatId = $catMapping[$catId]['parentId'];
        }else {
            $catArray = array();
            $queryCmd = "select categoryId from messageCategoryTable where threadId = ? and categoryId != 1";
            $result = $dbHandle->query($queryCmd, array($threadId));
            foreach ($result->result_array() as $row) {
                $categoryId = $row['categoryId'];
                if(isset($catMapping[$categoryId]) && is_array($catMapping[$categoryId]) && ($catId <= 0)) {
                    $catId = $categoryId;
                }elseif($parentCatId == -1) {
                    $parentCatId = $categoryId;
                }
                if(($catId >= 0) && ($parentCatId != -1)) {
                    break;
                }
            }

            if($catId > 0) {
                $parentCatId = $catMapping[$catId]['parentId'];
            }
        }
        $totalCount = 0;
        if($start == 0) {
            $queryCmd = "select count(*) totalCount from (select m1.msgId,m2.CategoryId,ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.status not in('deleted','abused') and M1.parentId = M1.threadId and M1.fromOthers = 'user'),0) ansCount, ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.parentId = m1.threadId and M1.userId = ? and M1.status in ('live','closed') and M1.fromOthers = 'user'),0) flagForAnswer from messageTable m1, messageCategoryTable m2 where m1.threadId = m2.threadId and m1.userId <> ? and m1.status not in ('deleted','abused') and m1.fromOthers = 'user' and m1.parentId = 0 and m2.categoryId = ?) Res  where Res.ansCount <= 4 and Res.flagForAnswer <= 0 ";
            $result = $dbHandle->query($queryCmd, array($userId,$userId,$parentCatId));
            foreach ($result->result_array() as $row) {
                $totalCount = $row['totalCount'];
            }
        }

        $userId = $dbHandle->escape($userId);
        $msgArray = array();
        if($catId != -1) {
            $queryCmd = "select SQL_CALC_FOUND_ROWS *,(10*(4-ansCount)/LOG10(10+TIMESTAMPDIFF(MINUTE,creationDate,now()))) weightOfQuestion from (select m1.*,m2.CategoryId,t1.displayName,t1.lastlogintime,t1.firstname,ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.status not in('deleted','abused') and M1.parentId = M1.threadId and M1.fromOthers = 'user'),0) ansCount , ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.parentId = m1.threadId and M1.userId = ".$userId." and M1.status in ('live','closed') and M1.fromOthers = 'user'),0) flagForAnswer from messageTable m1, messageCategoryTable m2,tuser t1 where m1.threadId = m2.threadId and m1.userId <> ".$userId." and m1.userId = t1.userId and m1.status not in ('deleted','abused') and m1.fromOthers = 'user' and m1.parentId = 0 and m2.categoryId = ".$catId.") Res  where Res.ansCount <= 4 and Res.flagForAnswer <= 0 order by weightOfQuestion desc limit  ".$start.",".$count;
            error_log_shiksha('for msgId getQuestionForActivityLandingPages query cmd is '.$queryCmd,'qna');
            $result = $dbHandle->query($queryCmd);
            foreach ($result->result_array() as $row) {
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
                array_push($msgArray,array($row,'struct'));
            }
            $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
            $query = $dbHandle->query($queryCmd);
            $totalNumberOfSubCategoryQuestions = 0;
            foreach ($query->result() as $row) {
                $totalNumberOfSubCategoryQuestions = $row->totalRows;
            }
            $tempVar = ($start+$count) - $totalNumberOfSubCategoryQuestions;
        }else {
            $tempVar = 0;
        }
        //This condition for parent category.This will come if the enough records are not found in subcategory.

        if($tempVar >= 0) {
            if($tempVar < $count) {
                $startParent = 0;
                $limitParent = $tempVar;
            }else {
                $startParent = $tempVar - $start;
                $limitParent = $count;
            }
            $queryCmd = "select *,(10*(4-ansCount)/LOG10(10+TIMESTAMPDIFF(MINUTE,creationDate,now()))) weightOfQuestion from (select m1.*,m2.CategoryId,t1.displayName,t1.lastlogintime,t1.firstname,ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.status not in('deleted','abused') and M1.parentId = M1.threadId and M1.fromOthers = 'user'),0) ansCount , ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.parentId = m1.threadId and M1.userId = ".$userId." and  m1.userId != ".$userId." and M1.status in ('live','closed') and M1.fromOthers = 'user'),0) flagForAnswer from messageTable m1, messageCategoryTable m2,tuser t1 where m1.threadId = m2.threadId and m1.userId <> ".$userId." and m1.userId = t1.userId and m1.status not in ('deleted','abused') and m1.fromOthers = 'user' and m1.parentId = 0 and m2.categoryId = ".$parentCatId." and m1.threadId not in (select threadId from messageCategoryTable mc2 where mc2.threadId = m1.threadId and mc2.categoryId = ".$catId.")) Res  where Res.ansCount <= 4 and Res.flagForAnswer <= 0 order by weightOfQuestion desc limit ".$startParent.",".$limitParent;
            error_log_shiksha('for categories getQuestionForActivityLandingPages query cmd is '.$queryCmd,'qna');
            $result = $dbHandle->query($queryCmd);
            foreach ($result->result_array() as $row) {
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
                array_push($msgArray,array($row,'struct'));
            }
        }

        //This condition is for all categories.It will never come in practical situations.
        if(count($msgArray) <= 0) {
            $queryCmd = "select SQL_CALC_FOUND_ROWS *,(10*(4-ansCount)/LOG10(10+TIMESTAMPDIFF(MINUTE,creationDate,now()))) weightOfQuestion from (select m1.*,t1.displayName,t1.lastlogintime,t1.firstname,ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.status not in('deleted','abused') and M1.parentId = M1.threadId and M1.fromOthers = 'user'),0) ansCount , ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.parentId = m1.threadId and M1.userId = ".$userId." and  m1.userId != ".$userId." and M1.status in ('live','closed') and M1.fromOthers = 'user'),0) flagForAnswer from messageTable m1,tuser t1 where m1.userId = t1.userId and m1.userId <> ".$userId." and m1.status not in ('deleted','abused') and m1.fromOthers = 'user' and m1.parentId = 0) Res  where Res.ansCount <= 4 and Res.flagForAnswer <= 0 order by weightOfQuestion desc limit  ".$start.",".$count;
            error_log_shiksha('for msgId getQuestionForActivityLandingPages query cmd is '.$queryCmd,'qna');
            $result = $dbHandle->query($queryCmd);
            foreach ($result->result_array() as $row) {
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
                array_push($msgArray,array($row,'struct'));
            }
            $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
            $query = $dbHandle->query($queryCmd);
            $totalRows = 0;
            foreach ($query->result() as $row) {
                $totalCount = $row->totalRows;
            }
        }
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalCount'=>array($totalCount,'string'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * getLastQnAOfUser gives the latest question OR answer posted by the user.
     */
    function getLastQnAOfUser($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $action=$parameters['2'];
        $userId=($userId!='')?$userId:0;
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();
        if((strcmp($action,'question') === 0) && ($userId !=0 )) {
            $queryCmd = "select m1.*,t1.displayname,t1.lastlogintime,t1.email,t1.avtarimageurl,t1.firstname,(select count(*) from messageTable M1 where M1.threadId = M1.parentId and M1.fromOthers='user' and M1.status not in ('deleted','abused') and M1.threadId = m1.threadId) answerCount from messageTable m1, tuser t1 where m1.userId = ? and t1.userid = m1.userId and m1.fromOthers = 'user' and m1.status not in ('deleted','abused') and m1.parentId = 0 order by creationDate desc limit 1";
            $query = $dbHandle->query($queryCmd, array($userId));
            foreach ($query->result_array() as $row) {
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
                array_push($msgArray,array($row,'struct'));
            }
        }elseif((strcmp($action,'answer') === 0) && ($userId !=0 )) {
            $queryCmd = "select m1.* from messageTable m1 where m1.userId = ? and m1.fromOthers = 'user' and m1.status not in ('deleted','abused') and m1.parentId = m1.threadId order by creationDate desc limit 1";
            $ResTemp = $dbHandle->query($queryCmd, array($userId));
            foreach ($ResTemp->result_array() as $row) {
                $msgArray[0] = array();
                $msgArray[0][0]['answer'] = array($row,'struct');
                $msgArray[0][1] = 'struct';
                $threadId = $row['threadId'];
            }
            $queryCmd = "select m1.*,t1.displayname,t1.lastlogintime,t1.email,t1.avtarimageurl,t1.firstname,(select count(*) from messageTable M1 where M1.threadId = M1.parentId and M1.fromOthers='user' and M1.status not in ('deleted','abused') and M1.threadId = m1.threadId) answerCount from messageTable m1, tuser t1 where m1.msgId =  ? and m1.userId = t1.userid and  m1.fromOthers = 'user' and m1.status not in ('deleted','abused') and m1.parentId = 0";
            $ResTemp = $dbHandle->query($queryCmd, array($threadId));
            foreach ($ResTemp->result_array() as $row) {
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
                $msgArray[1] = array();
                $msgArray[1][0]['question'] = array($row,'struct');
                $msgArray[1][1] = 'struct';
            }
        }

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * getUserInfoForLeaderBaord gives the user's answer and question count and user's level and points.
     */
    function getUserInfoForLeaderBaord($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $userId=($userId!='')?$userId:0;
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();
        if($userId !=0 ) {
        //Get the other details like user Creation Date, User upgradation time, Likes, Total points
        //Modified by Ankur on 8 March for Shiksha cafe performance
            $userAnswerIds = '';
            $queryCmdUser = "select m1.msgId from messageTable m1 where m1.userId=? and m1.fromOthers='user' and m1.parentId!=0 and m1.mainAnswerId=0 and m1.status IN ('live','closed')";
            $queryUser = $dbHandle->query($queryCmdUser, array($userId));
            foreach ($queryUser->result_array() as $rowTemp) {
                $userAnswerIds .= ($userAnswerIds=='')?$rowTemp['msgId']:",".$rowTemp['msgId'];
            }
            $likeQuery = "";
        
            $queryCmd = "select t1.displayname,t1.firstname,t1.lastname,t1.avtarimageurl,DATE_FORMAT(t1.usercreationDate, '%b %Y') creationDate,ifnull(upv.userpointvaluebymodule,0) userPointValue,(select count(*) from messageTable M1 where M1.threadId = M1.parentId and M1.fromOthers = 'user' and M1.status not in('deleted','abused') and M1.userId = ?) answerCount, ifnull((select ups.levelName from userpointsystembymodule ups where ups.modulename = 'AnA' and ups.userId = ? LIMIT 1),'Beginner-Level 1') level, (select count(m1.threadId) from messageTableBestAnsMap m1, messageTable m2 where m1.bestAnsId = m2.msgId and m2.userId = ? and m2.status IN ('live','closed')) bestAnswers $likeQuery from  tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where  t1.userid = ?";
            $query = $dbHandle->query($queryCmd, array($userId,$userId, $userId, $userId));
            foreach ($query->result_array() as $rowTemp) {
		if($userAnswerIds!=''){
		    $likeQuery = "SELECT count(*) likes FROM digUpUserMap d1 where d1.product='qna' and d1.digFlag = '1' and d1.digUpStatus='live' and d1.productId IN ($userAnswerIds) ";
		    $queryT = $dbHandle->query($likeQuery);
		    $rowT = $queryT->row();
		    $rowTemp['likes'] = $rowT->likes;
		}
                else{
                    $rowTemp['likes'] = 0;
		}
                $rowTemp['questionCount'] = 0;
				//31 May: Check if the User is an AnA Expert. Based on this, Flag will be shown on Leader board
				$this->load->model('QnAModel');
				$rowTemp['isAnAExpert'] = $this->QnAModel->checkIfAnAExpert($dbHandle,$userId);
                array_push($msgArray,array($rowTemp,'struct'));
            }

            $otherUserDetails = array();
            $today = date("Y-m-d");
            $week = date("Y-m-d", strtotime("-7 day"));

            //Queries to get the total discussion posts, total announcement posts, total participation points and weekly points
            //$queryCmd = "select upsm.userpointvaluebymodule totalParticipatePoints, (select count(*) from messageTable where userId = $userId and fromOthers = 'discussion' and status IN ('live','closed') and parentId=0) discussionPosts, (select count(*) from messageTable where userId = $userId and fromOthers = 'announcement' and status IN ('live','closed') and parentId=0) announcementPosts from userpointsystembymodule upsm where upsm.modulename = 'Participate' and upsm.userId = $userId limit 1;";
	    $queryCmd = "select upsm.userpointvaluebymodule totalParticipatePoints, (select count(*) from messageTable where userId = ? and fromOthers = 'discussion' and status IN ('live','closed') and parentId=0) discussionPosts, (select count(*) from messageTable where userId = ? and fromOthers = 'announcement' and status IN ('live','closed') and parentId=0) announcementPosts from tuser t1 LEFT JOIN userpointsystembymodule upsm ON (t1.userid=upsm.userId and upsm.modulename = 'Participate') where t1.userid = ? and t1.usergroup != 'cms' limit 1";
            $query = $dbHandle->query($queryCmd, array($userId,$userId,$userId));
            foreach ($query->result_array() as $rowTemp)
                array_push($otherUserDetails,array($rowTemp,'struct'));

            //Query for Weekly points
            $queryCmd = "select SUM(ups.pointvalue) weeklyPoints from tuser t1, userpointsystemlog ups where t1.userid = ? and ups.action != 'Register' and ups.module='AnA' and ups.timestamp >= ? and t1.userid=ups.userId and t1.usergroup != 'cms' group by ups.userId limit 1";
            $query = $dbHandle->query($queryCmd, array($userId, $week));
            foreach ($query->result_array() as $rowTemp)
                array_push($otherUserDetails,array($rowTemp,'struct'));

            //Query for Weekly points in Participation
            $queryCmd = "select SUM(ups.pointvalue) weeklyParticipatePoints from tuser t1, userpointsystemlog ups where t1.userid = ? and ups.action != 'Register' and ups.module='Participate' and ups.timestamp >= ? and t1.userid=ups.userId and t1.usergroup != 'cms' group by ups.userId limit 1";
            $query = $dbHandle->query($queryCmd, array($userId,$week));
            foreach ($query->result_array() as $rowTemp)
                array_push($otherUserDetails,array($rowTemp,'struct'));

        }
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'msgArray'=>array($msgArray,'struct'),
            'otherUserDetails'=>array($otherUserDetails,'struct')
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * getUserInfoForLeaderBaord gives the user's answer and question count and user's level and points.
     */
    function getDataForInformationWidgetInAnA($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();
        $creationDateCheck = "and m1.creationDate > DATE_SUB(NOW(),INTERVAL 15 DAY)";
        $queryCmd = "(select creationDate actionValue,'lastQuestionTime' actionType from messageTable m1 where m1.fromOthers='user' and m1.parentId = 0 and m1.status not in('deleted','abused') $creationDateCheck order by msgId desc limit 1) UNION (select count(*),'totalNumberOfQuestions'  from messageTable m1 where m1.fromOthers='user' and m1.parentId = 0 and m1.status not in('deleted','abused') and creationDate < now() and creationDate > DATE_SUB(NOW(),INTERVAL 1 HOUR)) UNION (select creationDate,'lastAnswerTime' from messageTable m1 where m1.fromOthers='user' and m1.parentId = m1.threadId and m1.status not in('deleted','abused') $creationDateCheck order by msgId desc limit 1) ";
        $Result = $dbHandle->query($queryCmd);
        foreach ($Result->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     * getEditorialQuestionsForHomePages gives the questions and answer for the home page widget.
     */
    /* Not in use */
    /*
    function getEditorialQuestionsForHomePages($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$dbHandle->escape($parameters['1']);
        $countryId=$dbHandle->escape($parameters['2']);
        $userId=$parameters['3'];
        $start=$parameters['4'];
        $count=$parameters['5'];
        $useRandom=$parameters['6'];
        $userId = ($userId != '')?$userId:0;
        $fromCategory = '';
        $catCondition = '';
        if($categoryId != 1) {
            $fromCategory = ', messageCategoryTable mct';
            $catCondition = 'and m1.threadId = mct.threadId and mct.categoryId = '.$categoryId;
        }
        $fromCountry = '';
        $conCondition = '';
        if($countryId != 1) {
            $fromCountry = ' , messageCountryTable mcon';
            $conCondition = 'and m1.threadId = mcon.threadId and mcon.countryId = '.$countryId;
        }
        $randNum = 0;
        $currentShown = 0;
        if($start == 0) {
            $countQuery = "select count(Res.msgId) totalRows from (select m1.msgId,(select count(*) from messageTable M2 where M2.threadId = m1.threadId and M2.threadId = M2.parentId and M2.fromOthers = 'user' and M2.status not in ('deleted','abused')) noOfAnswer from messageTable m1 , editorPick edp $fromCategory $fromCountry where edp.ProductId = m1.threadId and edp.ProductType = 'qna' $catCondition $conCondition and m1.fromOthers = 'user' and m1.parentId = 0 and m1.status not in ('deleted','abused') and edp.status not in('deleted') having noOfAnswer > 0) Res";
            $Result = $dbHandle->query($countQuery);
            foreach ($Result->result_array() as $row) {
                $totalRows = $row['totalRows'];
            }
        }
        if($useRandom == 1) {
            $randNum = rand(0,($totalRows-2));
            $currentShown = 0;
            $start =  $randNum;
            $count = 1;
        }
        $msgArray = array();
        $messageDiscussionQuery = ",ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD ";
        $queryCmd = "select m1.threadId,m1.msgId,m1.msgTxt,m1.creationDate,m1.status $messageDiscussionQuery ,ifnull((select 1 from messageTable M1 where M1.fromOthers = 'user' and M1.threadId = m1.threadId and M1.userId = ? and M1.threadId = M1.parentId and M1.status not in('deleted','abused')),0) flagForAnswer,m1.msgCount noOfAnswer from messageTable m1 , editorPick edp $fromCategory $fromCountry where edp.ProductId = m1.threadId and edp.ProductType = 'qna' $catCondition $conCondition and m1.fromOthers = 'user' and m1.parentId = 0 and m1.status not in ('deleted','abused') and edp.status not in('deleted') having noOfAnswer > 0 order by m1.creationDate limit ".$start." , ".$count;
        $Result = $dbHandle->query($queryCmd, array($userId));
        $csvThreadIds = '';
        $tempArray = array();
        foreach ($Result->result_array() as $row) {
        //$row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
            if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD']))
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['descriptionD'],'','',$row['creationDate']);
            else
                $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
            $csvThreadIds .= ($csvThreadIds == '')?$row['threadId']:(','.$row['threadId']);
            $tempArray[$row['threadId']] = $row;
        }

        $this->load->model('QnAModel');
        $tempArray1 = $this->QnAModel->getPopularAnswersForQuestions($dbHandle,$csvThreadIds,false);
        $tempArray1 = is_array($tempArray1)?$tempArray1:array();

        foreach($tempArray as $key => $temp) {
            $tempMsgArray = array();
            $tempMsgArray['question']=array($tempArray[$key],'struct');
            if(array_key_exists($key,$tempArray1)) { //to check if the answer for question exists.
                $tempMsgArray['answer']=array($tempArray1[$key],'struct');
            }else {
                $tempMsgArray['answer']=array(array(),'struct');
            }
            array_push($msgArray,array($tempMsgArray,'struct'));
        }
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'randNum'=>array($randNum,'int'),
            'currentShown'=>array($currentShown,'int'),
            'lowerLimit'=>array($start,'int'),
            'totalRows'=>array($totalRows,'int')
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }
    */

	/*
	* This function sends the user whose answer/comment has recieveed digUp-digDown in odd multiples of 5.
	*/
    function sendMailForDigActivity($applyTimeLimit=1,$sendForMultipleOfCount=1) {
        echo "\n\n\n cron to send mails for questions for listing start at.\n".date("Y-m-d H:i:s")."\n\n\n";
        $this->init();
        $fromAddress=SHIKSHA_ADMIN_MAIL;
	$dbHandle = $this->_loadDatabaseHandle('write');
        $timeCheck = "";
        if($applyTimeLimit == 1) {
            $startTime=date ("Y-m-d", time()-(DAILY_MAILER_USER_SET_START_TIME*24*60*60));
            $endTime=date ("Y-m-d", time()-(DAILY_MAILER_USER_SET_END_TIME*24*60*60));
            $timeCheck = " and d1.digTime >= '".$startTime."' and d1.digTime  <= '".$endTime."' ";
        }
        $this->load->library('Alerts_client');
        $alertClient = new Alerts_client();
        $subject = "Congratulations! You answer has been appreciated by the Ask & Answer community.";
        $queryCmd = "select m1.*,d1.productId digUpProductId,t1.email,t1.displayname,t1.firstname,m2.* from messageTable m1 LEFT JOIN mailSentFlagTable m2 ON (m1.msgId = m2.mailProductId and m2.mailProduct in ('qna')), digUpUserMap d1 , tuser t1  where m1.userId = t1.userid and d1.productId = m1.msgId and d1.product in ('qna') and d1.digUpStatus='live' $timeCheck and m1.fromOthers  = 'user' and m1.status not in ('deleted','abused') and (m1.digUp-m1.digDown) >= 5 group by d1.productId";

        $Result = $dbHandle->query($queryCmd);
        $row['mailFlag'] = ($row['mailFlag'] != null)?$row['mailFlag']:0;
        foreach($Result->result_array() as $row) {
            $digDff = $row['digUp'] - $row['digDown'];
            $mailSentFlag = $digDff;
            if($sendForMultipleOfCount == 1) {
                $nextOddMultiple = $this->getNextOddMultiple($row['mailFlag']);
                $mailSentFlag = $nextOddMultiple;
                if($digDff < $nextOddMultiple) {
                    continue;
                }
            }
            $row['urlForQuestion'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
            $toEmail = $row['email'];
            $htmlContent = $this->load->view("search/searchMail",array('contentArray' => $row,'type'=>'digActivityMail'),true);
            $response=$alertClient->externalQueueAdd("12",$fromAddress,$toEmail,$subject,$htmlContent,"html",time(),"n",array());
            if(trim($row['mailProductId']) != "") {
                $flagQuery = "update mailSentFlagTable set mailFlag = ".$mailSentFlag." where mailProductId = ? and mailProduct = 'qna'";
            }else {
                $flagQuery = "insert into mailSentFlagTable(mailProductId,mailProduct,mailUserId,emailType,mailFlag) values (?,'qna',".$row['userId'].",'digActivityMail',".$mailSentFlag.")";
            }
            $dbHandle->query($flagQuery, array($row['msgId']));
        }
        echo "cron to send mails for questions for listing ends at.\n".date("Y-m-d H:i:s")."\n\n\n";
    }

    private function getNextOddMultiple($offsetVal) {
        $count = 5;
        $multiple  = floor($offsetVal/$count);
        $multiple = (($multiple % 2) == 0)?($multiple+1):($multiple+2);
        return ($multiple*$count);
    }
    function getAverageTimeForAswer($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $date = date("Y-m-d");
	$date = strtotime("-7 days",strtotime($date));
	$date = date ( 'Y-m-j' , $date );
        $queryCmd = "select Res.*,TIMESTAMPDIFF(MINUTE,Res.creationDate,Res.firstAnswerCreationDate) diff from (select m1.threadId , m1.creationDate , (select creationDate from messageTable M2 where M2.parentId = M2.threadId and M2.status not in ('deleted','abused') and M2.fromOthers = 'user' and M2.threadId = m1.threadId order by msgId asc limit 1) firstAnswerCreationDate from messageTable m1 where m1.parentId = 0 and m1.fromOthers = 'user' and m1.status not in ('deleted','abused') and  m1.creationDate > DATE_SUB(now(),INTERVAL 30 day) and msgCount > 0 and creationDate > ? order by msgId desc limit 100) Res order by diff asc";
        $Result = $dbHandle->query($queryCmd, array($date));
        $resultArray = $Result->result_array();
        $count = count($resultArray);
        $middlePosition = ceil(count($resultArray)/2);
        if(($count % 2) == 0) {
            $avgTimeToAnswer = floor(($resultArray[$middlePosition-1]['diff']+$resultArray[$middlePosition]['diff'])/2);
        }else {
            $avgTimeToAnswer = $resultArray[$middlePosition-1]['diff'];
        }

        $response = array($avgTimeToAnswer,'int');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the Report Abuse form fields.
     */
    function getReportAbuseForm($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $category=$parameters['1'];
	$dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "select ID, Title, Content from tReportAbuseForm where Module = ? order by ID asc";
        $query = $dbHandle->query($queryCmd,array($category));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the Report Abuse form fields.
     */
    function getUserLevel($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userID=$parameters['1'];
        $moduleName=$parameters['2'];

	$dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "SELECT levelName as Level, levelId FROM userpointsystembymodule WHERE modulename = ? and userId = ?";
        $query = $dbHandle->query($queryCmd,array($moduleName,$userID));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Set the Report Abuse log and also set the abuse total points and abuse flag
     */
    function setAbuseRecord($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userID=$parameters['1'];
        $userLevel=$parameters['2'];
        $abuseRating=$parameters['3'];
        $threadId=$parameters['4'];
        $reasonIdList=$parameters['5'];
        $entityType=$parameters['6'];
        $topEntityId=$parameters['7'];
        $otherReason=$parameters['8'];
        $tracking_keyid=$parameters['9'];
        $sessionidTracking=getVisitorSessionId();

	$dbHandle = $this->_loadDatabaseHandle('write');
        if($entityType!="Event"){
            $queryToExe = 'select ifnull((select count(msgId) from messageTable where msgId = ? and userId = ?),0) userCommentFlag ';
	    $Result = $dbHandle->query($queryToExe, array($threadId,$userID));
	}
        else{
            $queryToExe = 'select ifnull((select count(event_id) from event where event_id = ? and user_id = ?),0) userCommentFlag ';
	    $Result = $dbHandle->query($queryToExe, array($topEntityId,$userID));
	}

        $row = $Result->row();
        if($row->userCommentFlag != 0) {
            $mainArr = array();
            array_push($mainArr,array(
                array(
                'results'=>array('SUCE','string'),
                'delete'=>array('0','int')
                ),'struct')
            );
            $response = array($mainArr,'struct');
            return $this->xmlrpc->send_response($response);
        }

	if($entityType=='0' && $threadId=='0'){
	        $mainArr = array();
        	array_push($mainArr,array(
	            array(
        	    'results'=>array('1','int'),
	            'delete'=>array(0,'int')
        	    ),'struct')
	        );
        	$response = array($mainArr,'struct');
	        return $this->xmlrpc->send_response($response);
	}

        $queryCmd = "INSERT INTO tReportAbuseLog (`userId`, `userLevel`, `entityType`, `entityId`, `abuseReason`, `pointsAdded`, `threadId`, `status`, `otherReason`,`tracking_keyid`,`visitorsessionid`) VALUES (?,?,?,?,?,?,?,'Live',?,?,?) ON DUPLICATE KEY UPDATE entityId=?";
        $query = $dbHandle->query($queryCmd,array($userID,$userLevel,$entityType,$threadId,$reasonIdList,$abuseRating,$topEntityId,$otherReason,$tracking_keyid,$sessionidTracking,$threadId));
		/*$queryToExe = 'select ifnull((select totalAbusePoints from tReportAbuseCheck where entityId = '.$threadId.' ),0) userAbusePoint ';
		$Result = $dbHandle->query($queryToExe);
		$row = $Result->row();
		if($row->userAbusePoint == 0){
		    $abuseFlag = ($abuseRating <= 5)?0:1;
		    $queryCmd = "INSERT INTO tReportAbuseCheck (entityId, totalAbusePoints, abuseFlag) VALUES ('".$threadId."', '".$abuseRating."', '".$abuseFlag."');";
		    $query = $dbHandle->query($queryCmd);
		}
		else
		{
		    $abuseFlag = (($abuseRating+$row->userAbusePoint) <= 5)?0:1;
		    $queryCmd = 'update tReportAbuseCheck set totalAbusePoints=tReportAbuseCheck.totalAbusePoints+'.$abuseRating.',abuseFlag='.$abuseFlag.' where entityId='.$threadId;
		    $query = $dbHandle->query($queryCmd);
		}*/
        $queryToExe = 'select ifnull((select SUM(`pointsAdded`) from tReportAbuseLog where entityId = ? and entityType = ? and status="Live"),0) totalAbusePoint';
        $Result = $dbHandle->query($queryToExe,array($threadId,$entityType));
        $row = $Result->row();
        $abuseFlag = ($row->totalAbusePoint <= 5)?0:1;

        if($abuseFlag)	//if the entity needs to be deleted, then set its status to Removed
        {
            $queryToExe = "Update tReportAbuseLog set status = 'Removed' where entityId = ? and entityType = ? and status='Live'";
            $Result = $dbHandle->query($queryToExe,array($threadId,$entityType));
        }
        //After setting the Abuse record, assign 5 points to the user who has reported abuse
        $this->load->model('UserPointSystemModel');
        if(strstr( $entityType, "Event" )){
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userID,'reportAbuseEvent');
	}
        else if(strstr( $entityType, "Blog" )){
            $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userID,'reportAbuseBlog');
	}
        //else if(strstr( $entityType, "discussion" )){
            //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userID,'reportAbuseDiscussion');
	//}
        //else if(strstr( $entityType, "announcement" )){
            //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userID,'reportAbuseAnnouncement');
	//}
        else {
            //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userID,'reportAbuseAnA',$threadId,$userID);
            //$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userID,'reportAbuseAnA',$threadId);
	    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userID,'reportAbuseBonus',$threadId);
        }
        //End code for assigning 5 points to user
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array('1','int'),
            'delete'=>array($abuseFlag,'int')
            ),'struct')
        );
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the user name and email.
     */
    function getUserDetails($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userID=$parameters['1'];

	$dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "SELECT firstname,displayname,email FROM tuser WHERE userid = ?";
        $query = $dbHandle->query($queryCmd,array($userID));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the msg text
     */
    function getMsgText($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgID=$parameters['1'];
        $type=$parameters['2'];

	$dbHandle = $this->_loadDatabaseHandle();
        if($type!='Event')
            $queryCmd = "SELECT msgTxt,threadId FROM messageTable WHERE msgId = ?";
        else
            $queryCmd = "SELECT event_title as msgTxt,event_id as threadId FROM event WHERE event_id = ?";

        $query = $dbHandle->query($queryCmd,array($msgID));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            $row['msgTxt'] = $this->changeTextForAtMention($row['msgTxt']);
            if($type == "Blog Comment")
                $row['url'] = SHIKSHA_HOME."/getArticleDetail/".$row['threadId'];
            else if($type == "Event" || $type == "Event Comment")
                    $row['url'] = SHIKSHA_HOME."/getEventDetail/1/".$row['threadId'];
            else if($type == "discussion" || $type == "discussion Comment" || $type == "discussion Reply")
                $row['url'] = SHIKSHA_ASK_HOME."/-dscns-".$row['threadId'];                
            else
                $row['url'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$row['threadId'];
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the user name and email.
     */
    function getAbuseUsersDetails($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $entityId=$parameters['1'];
        $entityType=$parameters['2'];

	$dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "SELECT t1.firstname,t1.displayname,t1.email,tr1.threadId,t1.userid FROM tuser t1,tReportAbuseLog tr1 WHERE tr1.entityId=? and tr1.entityType=? and tr1.userId=t1.userid";
        $query = $dbHandle->query($queryCmd,array($entityId,$entityType));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
            if($entityType == "Blog Comment")
                $row['url'] = SHIKSHA_HOME."/getArticleDetail/".$row['threadId'];
            else if($entityType == "Event" || $entityType == "Event Comment")
                $row['url'] = SHIKSHA_HOME."/getEventDetail/1/".$row['threadId'];
            else if($entityType == "discussion" || $entityType == "discussion Comment" || $entityType == "discussion Reply")
                $row['url'] = SHIKSHA_ASK_HOME."/-dscns-".$row['threadId'];                
            else
                $row['url'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$row['threadId'];


            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the user name and email.
     */
    function getUserFlag($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $userGroup=$parameters['2'];
        $threadIdList=$parameters['3'];

        $dbHandle = $this->_loadDatabaseHandle();
	$msgArray = array();
        $userFlagForAnswer = "";
        $editorPickFlagQuery = "";
        if($userId > 0 && $threadIdList!='') {
            $threadIdArr = explode(",",$threadIdList);
            for($i=0;$i<count($threadIdArr);$i++) {
                $userFlagForAnswer = " ifnull((select count(*) from messageTable M1 where M1.threadId = m1.threadId and M1.parentId = m1.threadId and M1.userId = ".$userId." and  m1.userId != ".$userId." and M1.status in ('live','closed') and M1.fromOthers = 'user'),0) flagForAnswer";
                if($userGroup == 'cms') {
                    $editorPickFlagQuery = " , ifnull((select 1 from editorPick ed where ed.ProductId = m1.msgId and ed.ProductType = 'qna' and ed.status = 'live'),0) editorPickFlag";
                    $userFlagForAnswer = " 0 flagForAnswer";
                }
                $queryCmd = "select msgId, ".$userFlagForAnswer.$editorPickFlagQuery." from messageTable m1 where m1.msgId  = ?";
                $query = $dbHandle->query($queryCmd, array($threadIdArr[$i]));
                foreach ($query->result_array() as $row) {
                    array_push($msgArray,array($row,'struct'));
                }
            }
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *	Get the details of the user for the v card.
     */
    function getUserVCardDetails($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $page=$parameters['2'];
        $status=$parameters['3'];
        $msgArray = array();
        $msgArray = $this->getUserVCardDetailsDB($appId,$userId,$page,"true",$status);
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getUserVCardDetailsDB($appId,$userId,$page,$getExtraInfo="false",$status='Live') {
	$dbHandle = $this->_loadDatabaseHandle();

        //$queryCmd = "SELECT vci.* ,ifnull((select upl.Level from userPointLevelByModule upl, userpointsystembymodule ups where upl.module = ups.moduleName and upl.module = 'AnA' and ups.userId = ".$userId." and ups.userPointValueByModule >= upl.minLimit LIMIT 1),'Beginner') ownerLevel ,ifnull((select upl.Level from userPointLevelByModule upl, userpointsystembymodule ups where upl.module = ups.moduleName and upl.module = 'Participate' and ups.userId = ".$userId." and ups.userPointValueByModule >= upl.minLimit LIMIT 1),'Beginner') ownerLevelP,ifnull((select count(threadId) from messageTableBestAnsMap where bestAnsId IN (select msgId from messageTable where userId = ".$userId." and status IN ('live','closed'))),0) bestAnswers,ifnull((select count(msgId) from messageTable where userId = ".$userId." and parentId != 0 and mainAnswerId=0 and status IN ('live','closed') and fromOthers='user'),0) totalAnswers, t1.displayname, t1.lastlogintime FROM VCardInfo vci, tuser t1 WHERE vci.userId = ".$userId." and vci.userId = t1.userid";
		$statusCheck = " and vci.status='Live' ";
		if($status!='Live') $statusCheck = '';
		$queryCmd = "SELECT vci.* ,ifnull((select ups.levelName from userpointsystembymodule ups where ups.modulename = 'AnA' and ups.userId = ? LIMIT 1),'Beginner-Level 1') ownerLevel ,ifnull((select upl.Level from userPointLevelByModule upl, userpointsystembymodule ups where upl.module = ups.moduleName and upl.module = 'Participate' and ups.userId = ? and ups.userPointValueByModule >= upl.minLimit LIMIT 1),'Beginner-Level 1') ownerLevelP,ifnull((select count(msgId) from messageTable where userId = ? and parentId != 0 and mainAnswerId=0 and status IN ('live','closed') and fromOthers='user'),0) totalAnswers, t1.firstname, t1.lastname, t1.displayname, t1.lastlogintime FROM expertOnboardTable vci, tuser t1 WHERE vci.userId = ? and vci.userId = t1.userid ".$statusCheck;
        $query = $dbHandle->query($queryCmd, array($userId, $userId ,$userId, $userId));
        $msgArray = array();
        foreach ($query->result_array() as $row) {
			//31 May: Check if the User is an AnA Expert. Based on this, flag will be shown on VCard and User Profile page
			$this->load->model('QnAModel');
			$row['isAnAExpert'] = $this->QnAModel->checkIfAnAExpert($dbHandle,$userId);
            array_push($msgArray,array($row,'struct'));
        }
        if(count($msgArray)==0) {
            $queryCmd = "select UserId, Name, Level from tUserEducation where UserId = ?";
            $query = $dbHandle->query($queryCmd, array($userId));
            $previousEducationLevel = 0;
            $highestEducation = '';
            foreach($query->result_array() as $row) {
                $educationLevels = array(
                    1 => '10',
                    2 => '12',
                    3 => 'UG',
                    4 => 'PG'
                );
                $currentEducationLevel = array_search($row['Level'], $educationLevels);
                if($previousEducationLevel < $currentEducationLevel) {
                    $highestEducation = $row['Name'];
                    $previousEducationLevel = $currentEducationLevel;
                }
            }
            $queryCmd = "SELECT t1.firstname,t1.lastname,t1.userid userId, t1.displayname, t1.avtarimageurl imageURL,t1.lastlogintime, ifnull((select ups.levelName from userpointsystembymodule ups where ups.modulename = 'AnA' and ups.userId = ? LIMIT 1),'Beginner-Level 1') ownerLevel,ifnull((select count(msgId) from messageTable where userId = ? and parentId != 0 and mainAnswerId=0 and fromOthers='user' and status IN ('live','closed')),0) totalAnswers FROM tuser t1 WHERE t1.userid = ?";
            $query = $dbHandle->query($queryCmd, array($userId, $userId, $userId));
            foreach ($query->result_array() as $row) {
		$row['userName'] = $row['firstname'];
                $row['highestQualification'] = $highestEducation;
				//31 May: Check if the User is an AnA Expert. Based on this, flag will be shown on VCard and User Profile page
				$this->load->model('QnAModel');
				$row['isAnAExpert'] = $this->QnAModel->checkIfAnAExpert($dbHandle,$userId);
                array_push($msgArray,array($row,'struct'));
            }
        }

        //Get the other information for the user only in case of VCard display
        if($getExtraInfo=="true") {
            $today = date("Y-m-d");
            $week = date("Y-m-d", strtotime("-7 day"));
            $otherUserDetails = array();
            $participateUserDetails = array();

            //Get the other details like user Creation Date, User upgradation time, Likes, Total points
            //Modified by Ankur on 8 March for Shiksha cafe performance
            $userAnswerIds = '';
            $queryCmdUser = "select m1.msgId from messageTable m1 where m1.userId=? and m1.fromOthers='user' and m1.parentId!=0 and m1.mainAnswerId=0 and m1.status IN ('live','closed')";
            $queryUser = $dbHandle->query($queryCmdUser, array($userId));
            foreach ($queryUser->result_array() as $rowTemp) {
                $userAnswerIds .= ($userAnswerIds=='')?$rowTemp['msgId']:",".$rowTemp['msgId'];
            }
            $likeQuery = "";
            if($userAnswerIds!='')
                $likeQuery = ", (SELECT count(*) FROM digUpUserMap d1 where d1.product='qna' and d1.digFlag = '1' and d1.digUpStatus='live' and d1.productId IN ($userAnswerIds)) likes";
            $queryCmd = "select upsm.userpointvaluebymodule totalPoints $likeQuery from tuser t1 LEFT JOIN userpointsystembymodule upsm ON (t1.userid=upsm.userId and upsm.modulename = 'AnA') where t1.userid = ? and t1.usergroup != 'cms' limit 1";
            $query = $dbHandle->query($queryCmd, array($userId));
            foreach ($query->result_array() as $rowTemp) {
                if($userAnswerIds=='')
                    $rowTemp['likes'] = 0;
                array_push($otherUserDetails,array($rowTemp,'struct'));
            }

            //Query for Weekly points
            $queryCmd = "select SUM(ups.pointvalue) weeklyPoints from tuser t1, userpointsystemlog ups where t1.userid = ? and ups.action != 'Register' and ups.module='AnA' and ups.timestamp >= ? and t1.userid=ups.userId and t1.usergroup != 'cms' group by ups.userId limit 1";
            $query = $dbHandle->query($queryCmd, array($userId, $week));
            foreach ($query->result_array() as $rowTemp)
                array_push($otherUserDetails,array($rowTemp,'struct'));

            //Queries to get the total discussion posts, total announcement posts, total participation points and weekly points
	    $queryCmd = "select upsm.userpointvaluebymodule totalParticipatePoints, (select count(*) from messageTable where userId = ? and fromOthers = 'discussion' and status IN ('live','closed') and parentId=0) discussionPosts, (select count(*) from messageTable where userId = ? and fromOthers = 'announcement' and status IN ('live','closed') and parentId=0) announcementPosts from tuser t1 LEFT JOIN userpointsystembymodule upsm ON (t1.userid=upsm.userId and upsm.modulename = 'Participate') where t1.userid = ? and t1.usergroup != 'cms' limit 1";
            //$queryCmd = "select upsm.userpointvaluebymodule totalParticipatePoints, (select count(*) from messageTable where userId = $userId and fromOthers = 'discussion' and status IN ('live','closed') and parentId=0) discussionPosts, (select count(*) from messageTable where userId = $userId and fromOthers = 'announcement' and status IN ('live','closed') and parentId=0) announcementPosts from userpointsystembymodule upsm where upsm.modulename = 'Participate' and upsm.userId = $userId limit 1;";
            $query = $dbHandle->query($queryCmd, array($userId,$userId,$userId));
            foreach ($query->result_array() as $rowTemp)
                array_push($participateUserDetails,array($rowTemp,'struct'));

            //Query for Weekly points in Participation
            $queryCmd = "select SUM(ups.pointvalue) weeklyParticipatePoints from tuser t1, userpointsystemlog ups where t1.userid = ? and ups.action != 'Register' and ups.module='Participate' and ups.timestamp >= ? and t1.userid=ups.userId and t1.usergroup != 'cms' group by ups.userId limit 1";
            $query = $dbHandle->query($queryCmd, array($userId,$week));
            foreach ($query->result_array() as $rowTemp)
                array_push($participateUserDetails,array($rowTemp,'struct'));

            $mainArr = array();
            array_push($mainArr,array(
                array(
                'VCardDetails'=>array($msgArray,'struct'),
                'otherUserDetails'=>array($otherUserDetails,'struct'),
                'participateUserDetails'=>array($participateUserDetails,'struct'),
                ),'struct')
            );//close array_push
            return $mainArr;
        }
        else {
            return $msgArray;
        }
    }

    /**
     *	Set the details of the user for the v card.
     */
    function setUserVCardDetails($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $name=$parameters['2'];
        $designation=$parameters['3'];
        $institute=$parameters['4'];
        $qualification=$parameters['5'];
        $imageURL=$parameters['6'];
        $blogURL=$parameters['7'];
        $brijjURL=$parameters['8'];
        $linkedInURL=$parameters['9'];
        $twitterURL=$parameters['10'];
        $youtubeURL=$parameters['11'];
        $aboutMe = $parameters['12'];
        $aboutCompany = $parameters['13'];

	$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "INSERT INTO `shiksha`.`expertOnboardTable` (`userId` ,`userName` ,`designation` ,`instituteName` ,`highestQualification` ,`imageURL` ,`blogURL` ,`facebookURL` ,`linkedInURL` ,`twitterURL` ,`youtubeURL`,`aboutMe`,'aboutCompany')
			    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result = $dbHandle->query($queryCmd,array($userId,$name,$designation,$institute,$qualification,$imageURL,$blogURL,$facebookURL,$linkedInURL,$twitterURL,$youtubeURL,$aboutMe,$aboutCompany));
        $response = array(array('Result' => array('added','string')),'struct');
        return $this->xmlrpc->send_response($response);
    }

    //Get the VCard status of the User logged In
    function getVCardStatus($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $cardStatus = '0';
        if($userId!=0) {
	    $dbHandle = $this->_loadDatabaseHandle();
            //$queryCmd = "select userName from VCardInfo where userId = ".$userId;
			$queryCmd = "select userName from expertOnboardTable where status = 'Live' and userId = ?";
            $query = $dbHandle->query($queryCmd,array($userId));
            foreach ($query->result() as $row) {
                if($row->userName && $row->userName!='')
                    $cardStatus = '1';
            }

        }
        $response = array(
            array(
            'status'=>array($cardStatus,'string')),
            'struct');
        return $this->xmlrpc->send_response($response);

    }

    /**
     *	Set the details of the user for the v card in a particular field.
     */
    function setUserVCardParam($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $type=$parameters['2'];
        $value=$parameters['3'];
	$dbHandle = $this->_loadDatabaseHandle('write');
        if($value=='empty') $value = '';
        //$queryCmd = "UPDATE VCardInfo SET ".$type." = '".$value."' WHERE userId = '".$userId."'" ;
		$queryCmd = "UPDATE expertOnboardTable SET ".$type." = '".$dbHandle->escape($value)."' WHERE userId = ?" ;
        $result = $dbHandle->query($queryCmd, array($userId));
        $response = array(array('Result' => array('updated','string')),'struct');
        return $this->xmlrpc->send_response($response);
    }

    function sendMailForVCard($once=0) {
        echo "\n\n\n cron to send mails for VCard start at.\n".date("Y-m-d H:i:s")."\n\n\n";
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library(array('messageboardconfig','Alerts_client','MailerClient'));
        $this->load->helper('date');
        $fromAddress=SHIKSHA_ADMIN_MAIL;
	$dbHandle = $this->_loadDatabaseHandle();
        $alertClient = new Alerts_client();
        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        $fromMail = "noreply@shiksha.com";
        $subject = "Connect and collaborate with more people now.";
        $contentArr = array();
        $contentArr['type'] = 'vcardMail';
        $MailerClient = new MailerClient();
        $urlOfLandingPage = SHIKSHA_HOME."/messageBoard/MsgBoard/vcardForm";

        //Get the Advisor users from the DB
        if($once==1)
            $queryCmd = "select t1.firstname username, t1.displayname, t1.email from tuser t1, userpointsystembymodule ups where ups.userpointvaluebymodule >= 1000 and ups.modulename = 'AnA' and ups.userId = t1.userid";
        else
            $queryCmd = "select t1.firstname username, t1.displayname, t1.email, ups.userid,ups.userpointvaluebymodule,(select sum(pointvalue) from userpointsystemlog where timestamp >= ? and module = 'AnA' and userid = t1.userid group by userId) points from tuser t1, userpointsystembymodule ups where ups.modulename = 'AnA' and ups.userId = t1.userid having  ups.userpointvaluebymodule<(1000+points) and ups.userpointvaluebymodule>=1000";

        echo "\nVCard query is: ".$queryCmd;
        $result = $dbHandle->query($queryCmd,array($yesterday));
        foreach ($result->result_array() as $row) {
            $contentArr['name'] = ($row['username']=='')?$row['username']:$row['displayname'];
            $email = $row['email'];
            $autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage);
            $contentArr['link'] = $autoLoginUrl;
            $contentArr['newAdvisor'] = ($once==1)?false:true;
            $content = $this->load->view("search/searchMail",$contentArr,true);
            $response= $alertClient->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",'0000-00-00 00:00:00');
        }
        echo "cron to send mails for V-Card ends at.\n".date("Y-m-d H:i:s")."\n\n\n";
    }

    function getMailDataOnCommentPosting($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $answerId=$parameters['1'];
        $userId=$parameters['2'];

        $dbHandle = $this->_loadDatabaseHandle();
	$queryCmd = "SELECT t1.displayname,t1.email,m1.msgTxt,m1.userId,m1.creationDate,m1.parentId,m1.threadId FROM messageTable m1, tuser t1 WHERE ((m1.mainAnswerId = ? ) OR (m1.msgId IN (select threadId from messageTable where msgId = ?)) OR (m1.msgId= ? )) and m1.userId=t1.userid and m1.status IN ('live','closed') and m1.fromOthers = 'user' and m1.userId != ?";

        $result = $dbHandle->query($queryCmd, array($answerId, $answerId, $answerId ,$userId));
        $returnArr = array();
        foreach ($result->result_array() as $row) {
            $row['type'] = 'Comment';
            if($row['parentId'] == 0)
                $row['type'] = 'Question';
            else if($row['parentId'] == $questionId)
                    $row['type'] = 'Answer';
            array_push($returnArr,array($row,'struct'));
        }

        $response = array($returnArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getWallData($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $start=$parameters['2'];
        $count=$parameters['3'];
        $categoryId=$parameters['4'];
        $countryId=$parameters['5'];
        $threadIdCsv=$parameters['6'];
	$threadIdCsv=implode(",", array_map("intval", explode(",", $threadIdCsv)));
        $lastTimeStamp=$parameters['7'];

	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle();
        //Get the latest threadId which fits into the wall
        $result = '';
        $date = date("Y-m-d");
        $i=1;
        $categoryCheck ="";
        if($categoryId != 0){
            $categoryCheck = "m2.categoryId = ".$dbHandle->escape($categoryId)." and ";
        }

        if($categoryId == 0){
            $selectForCategoryAndCountry = " mct.categoryId, ";
            $fromForCategoryAndCountry = " messageCategoryTable mct, ";
            $condition = " m1.threadId = mct.threadId and ";
            $conditionSecond = " mta.threadId = mct.threadId and ";
            $havingMisc = " group by mct.threadId having count(mct.threadId) =1 or mct.categoryId =0 ";
        }
        
        do {
            $date = strtotime("-".$i." days",strtotime($date));
            $date = date ( 'Y-m-j' , $date );
            $queryCmd = "select res.* from (select * from ((select ".$selectForCategoryAndCountry." m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey,m1.fromOthers product from".$fromForCategoryAndCountry." messageTable m1 where $condition m1.fromOthers IN ('user','discussion','review','announcement') and m1.status in ('live','closed') and m1.creationDate <= ? and m1.creationDate > ? and m1.msgTxt != 'dummy' and m1.listingTypeId = 0 and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed'))))".$havingMisc.") UNION (select ".$selectForCategoryAndCountry." d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.threadId sortingKey, mta.fromOthers product from".$fromForCategoryAndCountry." digUpUserMap d1, messageTable mta where".$conditionSecond." d1.product='qna' and d1.digFlag = 1 and d1.digUpStatus='live' and d1.digTime <= ? and d1.digTime > ? and d1.productId = mta.msgId and mta.status IN ('live','closed') and mta.fromOthers IN ('user') and mta.listingTypeId = 0 and (select status from messageTable pp1 where pp1.msgId = mta.threadId) IN ('live','closed')".$havingMisc.")) result order by result.sortingTime desc) res , messageCategoryTable m2, messageCountryTable m3 where res.sortingKey = m2.threadId and ".$categoryCheck."res.sortingKey = m3.threadId and m3.countryId = ? and res.sortingKey NOT IN (?) group by res.sortingKey order by res.sortingTime DESC limit 0,".$count;
            $threadIdCsvArray = explode(',', $threadIdCsv);
        if($categoryId != 0){
            $result = $dbHandle->query($queryCmd, array($lastTimeStamp,$date,$lastTimeStamp,$date,$countryId,$threadIdCsvArray));
        }
        else{
            $result = $dbHandle->query($queryCmd, array($lastTimeStamp,$date,$lastTimeStamp,$date,$countryId,$threadIdCsvArray));
        }
            $i+=7;
            if($i>30) break;
        }while(count($result->result_array())<$count);
        $msgIdsToCheckRatingStatus = '';
        //Now, get the data for these Id's based on their type and product
        $wallData = $this->getDataForWall($appId, "true", $userId, $result);
        $mainAnswerUserIdCsv = $wallData[0]['mainAnswerUserIdCsv'];
               
               foreach($wallData[0]['returnArr'] as $key => $row){
                foreach($row as $key1 => $row1){
                    if(is_numeric($key1) && isset($row1['mainAnswerId']) && $row1['mainAnswerId'] == 0){
                        
                        $msgIdsToCheckRatingStatus .= ($msgIdsToCheckRatingStatus=='')?$row1['msgId']:','.$row1['msgId'];
                    }
                }
               }

           if($msgIdsToCheckRatingStatus!='')
           {
            $this->load->model('QnAModel');
            $ifUserHasRatedStatus =  $this->QnAModel->checkIfUserHasRatedAnswer($userId, $msgIdsToCheckRatingStatus);
           }

        $threadIdList = $wallData[0]['threadIdList'];
        $returnArr = $wallData[0]['returnArr'];
        
        //Query to get the Level advance of the users
        $levelAdvanceArr = array();
        $levelAdvanceQuery = "select ups.lastLevelUpgradedTime sortingTime,t1.displayname,t1.firstName, t1.lastName, ups.userId,t1.email,'level' type, levelName as level from userpointsystembymodule ups, tuser t1 where t1.userid=ups.userId and ups.modulename = 'AnA' and ups.lastLevelUpgradedTime >= ? and ups.lastLevelUpgradedTime < ? and ups.userpointvaluebymodule >= 25 order by sortingTime desc limit 0,$count";
        $query = $dbHandle->query($levelAdvanceQuery, array($date,$lastTimeStamp));
        $i=0;
        foreach ($query->result_array() as $rowTemp) {
            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
            //array_push($levelAdvanceArr,array($rowTemp,'struct'));
            $levelAdvanceArr[$i] = $rowTemp;
            $i++;
        }
        //Query ends for level advance
        $this->load->model('QnAModel');
        $this->load->model('AnAModel');
        $msgArrayLevelVcard = array();
        $msgArrayLevelVcard = $this->QnAModel->getLevelAndVCardStausForUser($dbHandle,$mainAnswerUserIdCsv,true,true,true);
        $msgArrayLevelVcard = is_array($msgArrayLevelVcard)?$msgArrayLevelVcard:array();
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdList,true,true,true);
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
        //$response = array($returnArr,'struct');

        $mainAnswerIdCsv = $wallData[0]['mainAnswerIdCsv'];
        $answerSuggestions = $this->QnAModel->getSuggestedInstitutes($mainAnswerIdCsv,true,$dbHandle); 
        $answerSuggestions = is_array($answerSuggestions)?$answerSuggestions:array(); 

        $mainArr = array();

        $mainArr[0]['ratingStatusOfLoggedInUser'] = $ifUserHasRatedStatus;   
        //Modified for Shiksha performance task on 8 March
		/*array_push($mainArr,array(
				array(
					'results'=>array($returnArr,'struct'),
					'levelAdvance'=>array($levelAdvanceArr,'struct'),
					'categoryCountry'=>array($msgArrayCatCountry,'struct'),
					'levelVCard'=>array($msgArrayLevelVcard,'struct'),
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');*/
        $mainArr[0]['results'] = $returnArr;
        $mainArr[0]['levelAdvance'] = $levelAdvanceArr;
        $mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
        $mainArr[0]['levelVCard'] = $msgArrayLevelVcard;
        $mainArr[0]['answerSuggestions'] = $answerSuggestions;

        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getDataForWall($appId, $IsHomepage, $userId, $result) {
        $this->init();
	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle();

        $returnArr = array();
        $alreadyAnsweredQuery = "";
        $mainAnswerUserIdCsv = '';
        $answerIdCsv = '';
        $threadIdList = '';
        $j=0;
        if($userId > 0)
            $getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=m1.msgId and ral.userId=".$dbHandle->escape($userId)."),0) reportedAbuse ";
        $messageDiscussionQuery = ",ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.threadId LIMIT 1),'') descriptionD ";

        foreach ($result->result_array() as $row) {
            $type = '';
            $detailReturnArr = array();
            $i=0;
            //Now, we have the top 10 list of items which needs to be displayed on the Wall.
            //Depending on the type of the entity, we will retrieve the information from DB
            if($row['type']=="bestanswer")	//In case of Best Answers
            {
                $type = 'bestanswer';
                if($userId > 0)
                    $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = ".$dbHandle->escape($row['msgId'])." limit 1),0) alreadyAnswered";
                $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$dbHandle->escape($row['threadId'])." and mt1.fromOthers IN ('user','discussion') and mt1.status != 'deleted'),0) commentCount ";
                $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.* $messageDiscussionQuery ,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.msgId=?";
                $query = $dbHandle->query($queryCmd, array($row['msgId']));
                foreach ($query->result_array() as $rowTemp) {
                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                    //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt']);
                    if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                    else
                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                    $detailReturnArr[$i] = $rowTemp;
                    $i++;
                }
                $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers IN ('user','discussion') and m1.status IN ('live','closed') and m1.msgId = ?";
                $query = $dbHandle->query($queryCmd, array($row['threadId']));
                foreach ($query->result_array() as $rowTemp) {
                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                    $answerIdCsv .= ($answerIdCsv == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                    $detailReturnArr[$i] = $rowTemp;
                    $i++;
                }
            }
            else if($row['type']=="rating")	//In case of Rating, we will get the question, answer and the rater information
                //Also, in case of review college, we will have to get the information
                {
                    if($row['product']=='user') {
                        $type = 'rating';
                        if($userId > 0)
                            $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = (select threadId from messageTable where msgId= ".$dbHandle->escape($row['msgId']).") limit 1),0) alreadyAnswered";
                        $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$dbHandle->escape($row['msgId'])." and mt1.fromOthers IN ('user','discussion') and mt1.status != 'deleted'),0) commentCount ";
                        $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.* $messageDiscussionQuery,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1 )) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= (select threadId from messageTable where msgId= ?)";
                        $query = $dbHandle->query($queryCmd, array($row['msgId']));
                        foreach ($query->result_array() as $rowTemp) {
                            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                            $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                            //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt']);
                            if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                                $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                            else
                                $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                            //array_push($detailReturnArr,array($rowTemp,'struct'));
                            $detailReturnArr[$i] = $rowTemp;
                            $i++;
                        }
                        $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level,  m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1 )) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers IN ('user','discussion') and m1.status IN ('live','closed') and m1.msgId = ?";
                        $query = $dbHandle->query($queryCmd, array($row['msgId']));
                        foreach ($query->result_array() as $rowTemp) {
                            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                            $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                            $answerIdCsv .= ($answerIdCsv == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                            //array_push($detailReturnArr,array($rowTemp,'struct'));
                            $detailReturnArr[$i] = $rowTemp;
                            $i++;
                        }
                        $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, t.displayname,t.firstname, t.lastname, t.lastlogintime,t.userid userId,t.avtarimageurl userImage from tuser t where t.userid = ?";
                        $query = $dbHandle->query($queryCmd, array($row['mainAnswerId']));
                        foreach ($query->result_array() as $rowTemp) {
                            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                            $answerIdCsv .= ($answerIdCsv == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                            //array_push($detailReturnArr,array($rowTemp,'struct'));
                            $detailReturnArr[$i] = $rowTemp;
                            $i++;
                        }
                    }
                    else if($row['product']=='review' || $row['product']=='announcement') {
                            $type = $row['product'].'rating';
                            if($userId > 0)
                                $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = (select threadId from messageTable where msgId= ".$dbHandle->escape($row['msgId']).") limit 1),0) alreadyAnswered";
                            //$commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$row['msgId']." and mt1.fromOthers IN ('user','discussion') and mt1.status != 'deleted'),0) commentCount ";
                            $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.*,md1.description,'' listingTitle,t.displayname,t.firstname, t.lastname, t.lastlogintime,t.userid userId,t.avtarimageurl userImage from messageDiscussion md1, messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= (select threadId from messageTable where msgId= ?) and md1.threadId = m1.msgId";
                            $query = $dbHandle->query($queryCmd, array($row['msgId']));
                            foreach ($query->result_array() as $rowTemp) {
                                $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],$row['product'],$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                //array_push($detailReturnArr,array($rowTemp,'struct'));
                                $detailReturnArr[$i] = $rowTemp;
                                $i++;
                            }
                            $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1 )) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers IN ('user','discussion','announcement','review') and m1.status IN ('live','closed') and  m1.msgId = ?";
                            $query = $dbHandle->query($queryCmd, array($row['msgId']));
                            foreach ($query->result_array() as $rowTemp) {
                                $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                //array_push($detailReturnArr,array($rowTemp,'struct'));
                                $detailReturnArr[$i] = $rowTemp;
                                $i++;
                            }
                            $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, t.displayname,t.firstname, t.lastname, t.lastlogintime,t.userid userId,t.avtarimageurl userImage from tuser t where t.userid = ?";
                            $query = $dbHandle->query($queryCmd, array($row['mainAnswerId']));
                            foreach ($query->result_array() as $rowTemp) {
                                $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                //array_push($detailReturnArr,array($rowTemp,'struct'));
                                $detailReturnArr[$i] = $rowTemp;
                                $i++;
                            }
                        }
                }
                else if(($row['type']=="message") && ($row['mainAnswerId']==0))	//In case of Answers, we will get the question, and the answer
                    //Also, in case of discussion, review, announcements, this is the entity
                    {
                        if($row['product']=='user') {
                            $type = 'answer';
                            $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$row['msgId']." and mt1.fromOthers='user' and mt1.status != 'deleted'),0) commentCount ";
                            if($userId > 0)
                                $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = ".$dbHandle->escape($row['threadId'])." limit 1),0) alreadyAnswered";
                            $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.* $messageDiscussionQuery,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= ?";
                            $query = $dbHandle->query($queryCmd, array($row['threadId']));
                            foreach ($query->result_array() as $rowTemp) {
                                $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt']);
                                if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                                    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                                else
                                    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                //array_push($detailReturnArr,array($rowTemp,'struct'));
                                $detailReturnArr[$i] = $rowTemp;
                                $i++;
                            }
                            $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level,  m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId = ?";
                            $query = $dbHandle->query($queryCmd, array($row['msgId']));
                            foreach ($query->result_array() as $rowTemp) {
                                $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                $answerIdCsv .= ($answerIdCsv == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                //array_push($detailReturnArr,array($rowTemp,'struct'));
                                $detailReturnArr[$i] = $rowTemp;
                                $i++;
                            }
                        }
                        else if($row['product']=='discussion' || $row['product']=='announcement') {
                                $type = $row['product'];
                                //$commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$row['msgId']." and mt1.fromOthers='user' and mt1.status != 'deleted'),0) commentCount ";
                                $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.*,md.description,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused') order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageDiscussion md, messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= ? and m1.msgId=md.threadId";
                                $query = $dbHandle->query($queryCmd, array($row['msgId']));
                                foreach ($query->result_array() as $rowTemp) {
                                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],$row['product'],$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                                    
				    //Now, also find if this discussion has been linked with another discussion. If yes, we will not allow adding comments on this discussion
				    if($row['product']=='discussion'){
					$queryCmd = "select * from questionDiscussionLinkingTable where linkedEntityId = ? and status = 'accepted' and type='discussion'";
					$query = $dbHandle->query($queryCmd, array($rowTemp['threadId']));
					if($query->num_rows() > 0){	//Meaning discussion is Linked
						$rowTemp['linkedDiscussion'] = 'true';
					}
				    }
				    //End for Linked discussion

                                    $detailReturnArr[$i] = $rowTemp;
                                    $i++;
                                }
				    /*$queryCmd = "select m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused'))) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'discussion' and m1.status IN ('live','closed') and m1.msgId = ".$row['msgId'];
				    $query = $dbHandle->query($queryCmd);
				    foreach ($query->result_array() as $rowTemp){
					    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
					    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
					    array_push($detailReturnArr,array($rowTemp,'struct'));
				    }*/
                            }
                    }
                    else if($row['type']=='message' && $row['msgId']==$row['threadId'])	//In case of question, we will get the question
                        {
                            if($row['product']=='user') {
                                $type = 'question';
                                if($userId > 0)
                                    $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = ".$dbHandle->escape($row['threadId'])." limit 1),0) alreadyAnswered";
                                $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.* $messageDiscussionQuery,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= ?";
                                $query = $dbHandle->query($queryCmd, array($row['threadId']));
                                foreach ($query->result_array() as $rowTemp) {
                                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                    //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt']);
                                    if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                                    else
                                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                                    $detailReturnArr[$i] = $rowTemp;
                                    $i++;
                                }
                            }
				/*else if($row['product']=='discussion'){
				    $type = 'discussion';
				    $queryCmd = "select m1.*,md.description,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused'))) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageDiscussion md,messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= ".$row['threadId']." and m1.msgId = md.threadId";
				    $query = $dbHandle->query($queryCmd);
				    foreach ($query->result_array() as $rowTemp){
					    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
					    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
					    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt']);
					    array_push($detailReturnArr,array($rowTemp,'struct'));
				    }
				}*/
                        }
                        else if($row['type']=='message')	//In case of a Comment, we will get the question, answer and the comments
                            //Also, in case of discussion, review, announcements, we will get all the information
                            {
                                if($row['product']=='user') {
                                    $type = 'comment';
                                    $mainAnswerIdCsv = '';
                                    $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$row['mainAnswerId']." and mt1.fromOthers='user' and mt1.status NOT IN ('deleted','abused')),0) commentCount ";
                                    if($userId > 0)
                                        $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = ".$dbHandle->escape($row['threadId'])." limit 1),0) alreadyAnswered";

                                    $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.* $messageDiscussionQuery,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= ?";
                                    $query = $dbHandle->query($queryCmd, array($row['threadId']));
                                    foreach ($query->result_array() as $rowTemp) {
                                        $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                        $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                        //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt']);
                                        if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                                            $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                                        else
                                            $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                        //array_push($detailReturnArr,array($rowTemp,'struct'));
                                        $detailReturnArr[$i] = $rowTemp;
                                        $i++;
                                    }
                                    $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level,  m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId = ?";
                                    $query = $dbHandle->query($queryCmd, array($row['mainAnswerId']));
                                    foreach ($query->result_array() as $rowTemp) {
                                        $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                        $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                        $mainAnswerIdCsv = ($mainAnswerIdCsv == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                        $answerIdCsv .= ($answerIdCsv == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                        //array_push($detailReturnArr,array($rowTemp,'struct'));
                                        $detailReturnArr[$i] = $rowTemp;
                                        $i++;
                                    }
                                    $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.*,t.displayname, t.firstname, t.lastname, t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.mainAnswerId IN (".$mainAnswerIdCsv.") order by m1.creationDate";
                                    $query = $dbHandle->query($queryCmd);
                                    foreach ($query->result_array() as $rowTemp) {
                                        $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                        //array_push($detailReturnArr,array($rowTemp,'struct'));
                                        $detailReturnArr[$i] = $rowTemp;
                                        $i++;
                                    }
                                }
                                if($row['product']=='discussion' || $row['product']=='announcement') {
                                    $type = $row['product'].'comment';
                                    if(substr_count($row['path'], '.') > 2){
                                        $subtype = 'reply';
                                    }else{
                                        $subtype = 'comment';
                                    }
                                    $mainAnswerIdCsv = '';
                                    $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$dbHandle->escape($row['mainAnswerId'])." and mt1.fromOthers='user' and mt1.status != 'deleted'),0) commentCount ";

                                    $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level,  m1.*,md.description,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','closed','abused'))) listingTitle,t.displayname, t.firstname, t.lastname, t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageDiscussion md,messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= ? and m1.msgId=md.threadId and m1.status IN ('live','closed')";
                                    $query = $dbHandle->query($queryCmd, array($row['mainAnswerId']));
                                    foreach ($query->result_array() as $rowTemp) {
                                        $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                        $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],$row['product'],$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);

				    if($row['product']=='discussion'){
					$queryCmd = "select * from questionDiscussionLinkingTable where linkedEntityId = ? and status = 'accepted' and type='discussion'";
					$query = $dbHandle->query($queryCmd, array($rowTemp['threadId']));
					if($query->num_rows() > 0){	//Meaning discussion is Linked
						$rowTemp['linkedDiscussion'] = 'true';
					}
				    }

                                        //array_push($detailReturnArr,array($rowTemp,'struct'));
                                        $detailReturnArr[$i] = $rowTemp;
                                        $i++;
                                    }
				    /*$queryCmd = "select m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused'))) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId = ".$row['mainAnswerId'];
				    $query = $dbHandle->query($queryCmd);
				    foreach ($query->result_array() as $rowTemp){
					    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
					    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
					    $mainAnswerIdCsv = ($mainAnswerIdCsv == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
					    array_push($detailReturnArr,array($rowTemp,'struct'));
				    }*/
                                    $queryCmd = "select count(*) commentT from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers IN ('user','discussion','announcement') and m1.status IN ('live','closed') and m1.mainAnswerId IN (".$row['mainAnswerId'].")";
                                    $query = $dbHandle->query($queryCmd);
                                    $commentCountTotal = 0;
                                    foreach ($query->result_array() as $rowTemp) {
                                        $commentCountTotal = $rowTemp['commentT'];
                                    }
                                    $limitClause = '';
                                    if($commentCountTotal>20) {
                                        $tempCount = $commentCountTotal-11;
                                        $limitClause = "limit $tempCount,11";
                                    }
                                    $queryCmd = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level, m1.*,t.displayname,t.firstname, t.lastname, t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers IN ('user','discussion','announcement') and m1.status IN ('live','closed') and m1.mainAnswerId IN (".$row['mainAnswerId'].") order by m1.creationDate $limitClause";
                                    $query = $dbHandle->query($queryCmd);
                                    foreach ($query->result_array() as $rowTemp) {
                                        $commentParentId = $rowTemp['parentId'];
                                        //Code start to get the parent of the comment posted
                                        $pDIsplayName = '';
                                        if($commentParentId != $rowTemp['mainAnswerId']) {
                                            $queryCmdDisplay = "select ifnull((select levelName from userpointsystembymodule where userId=t.userid and moduleName='AnA'),'Beginner-Level 1') level,  t.displayname, t.firstname, t.lastname  from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers IN ('user','discussion','announcement') and m1.status IN ('live','closed') and m1.msgId IN (".$commentParentId.") order by m1.creationDate";
                                            $queryDisplay = $dbHandle->query($queryCmdDisplay);
                                            foreach ($queryDisplay->result_array() as $rowTempDisplay) {
                                                $pDIsplayName = $rowTempDisplay['displayname'];
                                            }
                                        }
                                        $rowTemp['parentDisplayName'] = $pDIsplayName;
                                        $rowTemp['commentCountTotal'] = $commentCountTotal;
                                        //Code ends to get the parent of the comment posted
                                        $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                        //array_push($detailReturnArr,array($rowTemp,'struct'));
                                        $detailReturnArr[$i] = $rowTemp;
                                        $i++;
                                    }
                                }
                            }

            $detailReturnArr['type'] = $type;
            $detailReturnArr['subtype'] = $subtype;
            $detailReturnArr['sortingTime'] = $row['sortingTime'];
            //array_push($returnArr,array($detailReturnArr,'struct'));
            $returnArr[$j] = $detailReturnArr;
            $j++;
        }
        $mainArr = array();
		/*array_push($mainArr,array(
				array(
					'returnArr'=>array($returnArr,'struct'),
					'mainAnswerUserIdCsv'=>array($mainAnswerUserIdCsv,'string'),
					'threadIdList'=>array($threadIdList,'string'),
				),'struct')
		);//close array_push
		*/
        $mainArr[0]['returnArr'] = $returnArr;
        $mainArr[0]['mainAnswerUserIdCsv'] = $mainAnswerUserIdCsv;
        $mainArr[0]['threadIdList'] = $threadIdList;
        $mainArr[0]['mainAnswerIdCsv'] = $answerIdCsv;
        
        return $mainArr;
    }

    function getWallDataForListings($request) {
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$dbHandle->escape($parameters['1']);
        $start=$parameters['2'];
        $count=$parameters['3'];
        $categoryId=$parameters['4'];
        $countryId=$parameters['5'];
        $threadIdCsv=$parameters['6'];
        $lastTimeStamp=$parameters['7'];
        $questionIds = $parameters['8'];
        $type = $parameters['9'];
        $instituteId = $parameters['10'];
       
        if(!empty($questionIds) || !empty($questionIds[0])) {
            $questionIds = implode(",",$questionIds);
        }
        if(!empty($categoryId) && !empty($categoryId[0])) {
            //$categoryId = implode(",",$categoryId);
        }else {
            $categoryId = 1;
        }
        $lastTimeStamp = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
        $lastTimeStamp = date('Y-m-d',$lastTimeStamp);
        $result = '';
        $date = date("Y-m-d");
        $fromForCategoryAndCountry = '';
        $conditionForCategory = '';
        $conditionForCountry = '';
        $i=1;
        $countryIdArray = explode(',',$countryId);
        $threadIdArray = explode(',',$threadIdCsv);
        if(empty($questionIds) && ($type!='INSTITUTE') ) {
            $count1 = '10';
            do {
                $date = strtotime("-".$i." days",strtotime($date));
                $date = date ( 'Y-m-j' , $date );
                $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey from messageTable m1 where m1.fromOthers='user' and m1.status in ('live','closed') and m1.creationDate <= ? and m1.creationDate > ? and m1.listingTypeId = 0 and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed'))))) UNION (select d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.threadId sortingKey from digUpUserMap d1, messageTable mta where d1.product='qna' and d1.digFlag = 1 and d1.digUpStatus='live' and d1.digTime <= ? and d1.digTime > ? and d1.productId = mta.msgId and mta.status IN ('live','closed') and mta.listingTypeId = 0 and (select status from messageTable pp1 where pp1.msgId = mta.threadId) IN ('live','closed')) UNION (select b1.creation_time sortingTime,b1.threadId,b1.bestAnsId,'0' Id,'bestanswer' type,b1.threadId sortingKey from messageTableBestAnsMap b1, messageTable mt where b1.creation_time <= ? and b1.creation_time > ? and (b1.threadId = mt.threadId) and (b1.bestAnsId = mt.msgId) and mt.status IN ('live','closed') and mt.listingTypeId = 0 and (select status from messageTable pp2 where pp2.msgId = mt.threadId) IN ('live','closed'))) result order by result.sortingTime desc) res , messageCategoryTable m2, messageCountryTable m3 where res.sortingKey = m2.threadId and m2.categoryId in (?) and res.sortingKey = m3.threadId and m3.countryId in (?) and res.sortingKey NOT IN (?) group by res.sortingKey order by res.sortingTime DESC limit 0,$count1";
                    
                $result = $dbHandle->query($queryCmd, array($lastTimeStamp,$date,$lastTimeStamp,$date,$lastTimeStamp,$date,$categoryId,$countryIdArray,$threadIdArray));
                $i+=7;
                if($i>30) break;
            }while(count($result->result_array())<$count1);
        }
        else if( empty($questionIds) && ($type=='INSTITUTE') ){
            $categoryId = 1;
            if($count==6){  //In case of AnA Widget, we just need latest questions or answers
            $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey from messageTable m1 where m1.fromOthers='user' and m1.status in ('live','closed') and m1.creationDate <= '".$lastTimeStamp."'  and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and m1.mainAnswerId<=0 and m1.listingTypeId = $instituteId and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed')))))) result order by result.sortingTime desc) res , messageCategoryTable m2, messageCountryTable m3 where res.sortingKey = m2.threadId and m2.categoryId in (?) and res.sortingKey = m3.threadId and m3.countryId in (?) and res.sortingKey NOT IN (?)  group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
            }
            else{
            $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey from messageTable m1 where m1.fromOthers='user' and m1.status in ('live','closed') and m1.creationDate <= '".$lastTimeStamp."'  and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and m1.listingTypeId = $instituteId and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed'))))) UNION (select d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.threadId sortingKey from digUpUserMap d1, messageTable mta where mta.listingTypeId = $instituteId and d1.product='qna' and d1.digFlag = 1 and d1.digUpStatus='live' and d1.digTime <= '".$lastTimeStamp."' and  d1.productId = mta.msgId and mta.status IN ('live','closed') and (select status from messageTable pp1 where pp1.msgId = mta.threadId)
                        IN ('live','closed')) UNION (select b1.creation_time sortingTime,b1.threadId,b1.bestAnsId,'0' Id,'bestanswer' type,b1.threadId sortingKey from messageTableBestAnsMap b1, messageTable mt where mt.listingTypeId = $instituteId and b1.creation_time <= '".$lastTimeStamp."' and (b1.threadId = mt.threadId) and (b1.bestAnsId = mt.msgId) and mt.status IN ('live','closed') and (select status from messageTable pp2 where pp2.msgId = mt.threadId) IN ('live','closed'))) result order by result.sortingTime desc) res , messageCategoryTable m2, messageCountryTable m3 where res.sortingKey = m2.threadId and m2.categoryId in (?) and res.sortingKey = m3.threadId and m3.countryId in (?) and res.sortingKey NOT IN (?)  group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
            }
        }
        else if( !empty($questionIds) && ($type=='RELATED') ){
            $questionIds = trim($questionIds,',');
            $categoryId = 1;
            $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey from messageTable m1 where m1.fromOthers='user' and m1.status in ('live','closed') and m1.creationDate <= '".$lastTimeStamp."'  and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and m1.threadId IN ($questionIds) and m1.listingTypeId != $instituteId and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed'))))) UNION (select d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.threadId sortingKey from digUpUserMap d1, messageTable mta where mta.threadId IN ($questionIds) and mta.listingTypeId != $instituteId and d1.product='qna' and d1.digFlag = 1 and d1.digUpStatus='live' and d1.digTime <= '".$lastTimeStamp."' and  d1.productId = mta.msgId and mta.status IN ('live','closed') and
            (select status from messageTable pp1 where pp1.msgId = mta.threadId) IN ('live','closed')) UNION (select b1.creation_time sortingTime,b1.threadId,b1.bestAnsId,'0' Id,'bestanswer' type,b1.threadId sortingKey from messageTableBestAnsMap b1, messageTable mt where mt.threadId IN ($questionIds) and mt.listingTypeId != $instituteId and b1.creation_time <= '".$lastTimeStamp."' and (b1.threadId = mt.threadId) and (b1.bestAnsId = mt.msgId) and mt.status IN ('live','closed') and (select status from messageTable pp2 where pp2.msgId = mt.threadId) IN ('live','closed'))) result order by result.sortingTime desc) res , messageCategoryTable m2, messageCountryTable m3 where res.sortingKey = m2.threadId and m2.categoryId in (?) and res.sortingKey = m3.threadId and m3.countryId in (?) and res.sortingKey NOT IN (?)  group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
        }
        else{
            $questionIds = trim($questionIds,',');
            $categoryId = 1;
            $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey from messageTable m1 where m1.fromOthers='user' and m1.status in ('live','closed') and m1.creationDate <= '".$lastTimeStamp."'  and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and m1.threadId IN ($questionIds) and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed'))))) UNION (select d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.threadId sortingKey from digUpUserMap d1, messageTable mta where mta.threadId IN ($questionIds) and d1.product='qna' and d1.digFlag = 1 and d1.digUpStatus='live' and d1.digTime <= ? and  d1.productId = mta.msgId and mta.status IN ('live','closed') and mta.listingTypeId = 0 and (select status from messageTable pp1 where pp1.msgId =
                                    mta.threadId) IN ('live','closed')) UNION (select b1.creation_time sortingTime,b1.threadId,b1.bestAnsId,'0' Id,'bestanswer' type,b1.threadId sortingKey from messageTableBestAnsMap b1, messageTable mt where mt.threadId IN ($questionIds) and b1.creation_time <= '".$lastTimeStamp."' and (b1.threadId = mt.threadId) and (b1.bestAnsId = mt.msgId) and mt.status IN ('live','closed') and mt.listingTypeId = 0 and (select status from messageTable pp2 where pp2.msgId = mt.threadId) IN ('live','closed'))) result order by result.sortingTime desc) res , messageCategoryTable m2, messageCountryTable m3 where res.sortingKey = m2.threadId and m2.categoryId in (?) and res.sortingKey = m3.threadId and m3.countryId in (?) and res.sortingKey NOT IN (?)  group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
        }
        $result = $dbHandle->query($queryCmd,array($categoryId,$countryIdArray,$threadIdArray));

        $returnArr = array();
        $alreadyAnsweredQuery = "";
        $mainAnswerUserIdCsv = '';
        $threadIdList = '';
        $y = 0;
        if($userId > 0)
            $getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=m1.msgId and ral.userId=".$dbHandle->escape($userId)."),0) reportedAbuse ";
        $messageDiscussionQuery = ",ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.threadId LIMIT 1),'') descriptionD ";

        foreach ($result->result_array() as $row) {
            $type = '';
            $detailReturnArr = array();
            $x = 0;
            //Now, we have the top 10 list of items which needs to be displayed on the Wall.
            //Depending on the type of the entity, we will retrieve the information from DB
            if($row['type']=="bestanswer")	//In case of Best Answers
            {
                $type = 'bestanswer';
                if($userId > 0)
                    $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = ".$row['msgId']." limit 1),0) alreadyAnswered";
                $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$row['threadId']." and mt1.fromOthers='user' and mt1.status != 'deleted'),0) commentCount ";
                $queryCmd = "select m1.* $messageDiscussionQuery ,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.msgId=?";
                $query = $dbHandle->query($queryCmd, array($row['msgId']));
                foreach ($query->result_array() as $rowTemp) {
                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                    if($this->seo_update($rowTemp['creationDate'])) {
                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'seo',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                    }
                    else {
                    //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                        if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                            $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                        else
                            $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                    }
                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                    $detailReturnArr[$x] = $rowTemp;
                    $x++;
                }
                $queryCmd = "select m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId = ?";
                $query = $dbHandle->query($queryCmd, array($row['threadId']));
                foreach ($query->result_array() as $rowTemp) {
                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                    $detailReturnArr[$x] = $rowTemp;
                    $x++;
                }
            }
            else if($row['type']=="rating")	//In case of Rating, we will get the question, answer and the rater information
                {
                    $type = 'rating';
                    if($userId > 0)
                        $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = (select threadId from messageTable where msgId= ".$row['msgId'].") limit 1),0) alreadyAnswered";
                    $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$row['msgId']." and mt1.fromOthers='user' and mt1.status != 'deleted'),0) commentCount ";
                    $queryCmd = "select m1.* $messageDiscussionQuery ,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= (select threadId from messageTable where msgId= ?)";
                    $query = $dbHandle->query($queryCmd, array($row['msgId']));
                    foreach ($query->result_array() as $rowTemp) {
                        $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                        $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                        if($this->seo_update($rowTemp['creationDate'])) {
                            $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'seo',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                        }
                        else {
                        //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                            if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                                $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                            else
                                $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                        }
                        //array_push($detailReturnArr,array($rowTemp,'struct'));
                        $detailReturnArr[$x] = $rowTemp;
                        $x++;
                    }
                    $queryCmd = "select m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId = ?";
                    $query = $dbHandle->query($queryCmd, array($row['msgId']));
                    foreach ($query->result_array() as $rowTemp) {
                        $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                        $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                        //array_push($detailReturnArr,array($rowTemp,'struct'));
                        $detailReturnArr[$x] = $rowTemp;
                        $x++;
                    }
                    $queryCmd = "select t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage from tuser t where t.userid = ?";
                    $query = $dbHandle->query($queryCmd, array($row['mainAnswerId']));
                    foreach ($query->result_array() as $rowTemp) {
                        $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                        //array_push($detailReturnArr,array($rowTemp,'struct'));
                        $detailReturnArr[$x] = $rowTemp;
                        $x++;
                    }
                }
                else if(($row['type']=="message") && ($row['mainAnswerId']==0))	//In case of Answers, we will get the question, and the answer
                    {
                        $type = 'answer';
                        $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$row['msgId']." and mt1.fromOthers='user' and mt1.status != 'deleted'),0) commentCount ";
                        if($userId > 0)
                            $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = ".$row['threadId']." limit 1),0) alreadyAnswered";
                        $queryCmd = "select m1.* $messageDiscussionQuery ,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= ?";
                        $query = $dbHandle->query($queryCmd, array($row['threadId']));
                        foreach ($query->result_array() as $rowTemp) {
                            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                            $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                            if($this->seo_update($rowTemp['creationDate'])) {
                                $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'seo',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                            }
                            else {
                            //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                                    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                                else
                                    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                            }
                            //array_push($detailReturnArr,array($rowTemp,'struct'));
                            $detailReturnArr[$x] = $rowTemp;
                            $x++;
                        }
                        $queryCmd = "select m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId = ?";
                        $query = $dbHandle->query($queryCmd, array($row['msgId']));
                        foreach ($query->result_array() as $rowTemp) {
                            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                            $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                            //array_push($detailReturnArr,array($rowTemp,'struct'));
                            $detailReturnArr[$x] = $rowTemp;
                            $x++;
                        }
                    }
                    else if($row['type']=='message' && $row['msgId']==$row['threadId'])	//In case of question, we will get the question
                        {
                            $type = 'question';
                            if($userId > 0)
                                $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = ".$row['threadId']." limit 1),0) alreadyAnswered";
                            $queryCmd = "select m1.* $messageDiscussionQuery ,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId= ?";
                            $query = $dbHandle->query($queryCmd, array($row['threadId']));
                            foreach ($query->result_array() as $rowTemp) {
                                $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                if($this->seo_update($rowTemp['creationDate'])) {
                                    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'seo',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                }
                                else {
                                //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                    if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                                    else
                                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                }
                                //array_push($detailReturnArr,array($rowTemp,'struct'));
                                $detailReturnArr[$x] = $rowTemp;
                                $x++;
                            }
                        }
                        else if($row['type']=='message')	//In case of a Comment, we will get the question, answer and the comments
                            {
                                $type = 'comment';
                                $mainAnswerIdCsv = '';
                                $commentCountQuery = ", ifnull((select count(*) from messageTable mt1 where mt1.mainAnswerId = ".$row['mainAnswerId']." and mt1.fromOthers='user' and mt1.status != 'deleted'),0) commentCount ";
                                if($userId > 0)
                                    $alreadyAnsweredQuery = " , ifnull((select 1 from messageTable m2 where m2.parentId = m2.threadId  and m2.userId = ".$dbHandle->escape($userId)." and m2.threadId = ".$row['threadId']." limit 1),0) alreadyAnswered";

                                $queryCmd = "select m1.* $messageDiscussionQuery ,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $alreadyAnsweredQuery from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.msgId=?";
                                $query = $dbHandle->query($queryCmd, array($row['threadId']));
                                foreach ($query->result_array() as $rowTemp) {
                                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                    if($this->seo_update($rowTemp['creationDate'])) {
                                        $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'seo',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                    }
                                    else {
                                    //$rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                        if($this->check_legacy_seo_update($rowTemp['creationDate'],$rowTemp['descriptionD']))
                                            $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['descriptionD'],'','',$rowTemp['creationDate']);
                                        else
                                            $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],'question',$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
                                    }
                                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                                    $detailReturnArr[$x] = $rowTemp;
                                    $x++;
                                }
                                $queryCmd = "select m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse $commentCountQuery from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.msgId = ?";
                                $query = $dbHandle->query($queryCmd, array($row['mainAnswerId']));
                                foreach ($query->result_array() as $rowTemp) {
                                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                    $threadIdList .= ($threadIdList == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                    $mainAnswerIdCsv = ($mainAnswerIdCsv == '')?$rowTemp['msgId']:','.$rowTemp['msgId'];
                                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                                    $detailReturnArr[$x] = $rowTemp;
                                    $x++;
                                }
                                $queryCmd = "select m1.*,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage $getReportedAbuse from messageTable m1,tuser t where m1.userId = t.userid and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.mainAnswerId IN (".$mainAnswerIdCsv.") order by m1.creationDate";
                                $query = $dbHandle->query($queryCmd);
                                foreach ($query->result_array() as $rowTemp) {
                                    $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                                    //array_push($detailReturnArr,array($rowTemp,'struct'));
                                    $detailReturnArr[$x] = $rowTemp;
                                    $x++;
                                }
                            }

			$detailReturnArr['type'] = $type;
			$detailReturnArr['sortingTime'] = $row['sortingTime'];
			//array_push($returnArr,array($detailReturnArr,'struct'));
			$returnArr[$y] = $detailReturnArr;
			$y++;
		}
                //Query to get the Level advance of the users
		$levelAdvanceArr = array();
		//$levelAdvanceQuery = "select (select timestamp from userpointsystemlog where timestamp >= '".$date."' and timestamp < '".$lastTimeStamp."' and module = 'AnA' and userid = t1.userid order by timestamp desc limit 1) sortingTime, t1.displayname, ups.userid userId, ups.userpointvaluebymodule,'level' type, (select sum(pointvalue) from userpointsystemlog where timestamp >= '".$date."' and timestamp < '".$lastTimeStamp."' and module = 'AnA' and userid = t1.userid group by userId) points, (select level from userPointLevelByModule  where minLimit<= ifnull(ups.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from tuser t1 LEFT JOIN userpointsystembymodule ups ON (t1.userid = ups.userId and ups.modulename='AnA') where ups.modulename = 'AnA' and ups.userId = t1.userid having  (ups.userpointvaluebymodule<(250+points) and ups.userpointvaluebymodule>=250) OR (ups.userpointvaluebymodule<(1000+points) and ups.userpointvaluebymodule>=1000) OR (ups.userpointvaluebymodule<(2500+points) and ups.userpointvaluebymodule>=2500) OR (ups.userpointvaluebymodule<(5000+points) and ups.userpointvaluebymodule>=5000) OR (ups.userpointvaluebymodule<(10000+points) and ups.userpointvaluebymodule>=10000) OR (ups.userpointvaluebymodule<(20000+points) and ups.userpointvaluebymodule>=20000) order by sortingTime desc limit 0,$count";
		/*$levelAdvanceQuery = "select ups.lastLevelUpgradedTime sortingTime,t1.displayname,ups.userId,t1.email,'level' type, (select level from userPointLevelByModule  where minLimit<= ifnull(ups.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from userpointsystembymodule ups, tuser t1 where t1.userid=ups.userId and ups.modulename = 'AnA' and ups.lastLevelUpgradedTime >= '".$date."' and ups.lastLevelUpgradedTime < '".$lastTimeStamp."' and ups.userpointvaluebymodule >= 250 order by sortingTime desc limit 0,$count";
		$query = $dbHandle->query($levelAdvanceQuery);
		$z = 0;
		foreach ($query->result_array() as $rowTemp){
			$mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
			//array_push($levelAdvanceArr,array($rowTemp,'struct'));
			$levelAdvanceArr[$z] = $rowTemp;
			$z++;
		}*/
		//Query ends for level advance
		$this->load->model('QnAModel');
 		$msgArrayLevelVcard = array();
 		if($mainAnswerUserIdCsv!=''){
			$msgArrayLevelVcard = $this->QnAModel->getLevelAndVCardStausForUser($dbHandle,$mainAnswerUserIdCsv,true,true,true);
		}
 		$msgArrayLevelVcard = is_array($msgArrayLevelVcard)?$msgArrayLevelVcard:array();
 		$msgArrayCatCountry = array();
		if($threadIdList!=''){
	 		$msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdList,true,true,true);
		}
 		$msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();

		//$response = array($returnArr,'struct');
		$mainArr = array();
		/*array_push($mainArr,array(
				array(
					'results'=>array($returnArr,'struct'),
					'levelAdvance'=>array($levelAdvanceArr,'struct'),
					'categoryCountry'=>array($msgArrayCatCountry,'struct'),
					'levelVCard'=>array($msgArrayLevelVcard,'struct'),
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');*/
        $mainArr[0]['results'] = $returnArr;
        $mainArr[0]['levelAdvance'] = $levelAdvanceArr;
        $mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
        $mainArr[0]['levelVCard'] = $msgArrayLevelVcard;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function sendMailToAbusePeople($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
	$dbHandle = $this->_loadDatabaseHandle();
        $subject = "Your abuse report has been accepted";
        $fromMail = "info@shiksha.com";
        $ccmail = "sales@shiksha.com";
        $contentArr['subject'] = $subject;
        $contentArr['fromMail'] = $fromMail;
        $contentArr['ccmail'] = $ccmail;
        $contentArr['type'] = 'abusePeopleMail';
        $queryCmd = "select tu.email, tu.firstname, tu.displayname,tu.userid,tra.entityType, DATE_FORMAT(tra.creationDate, '%e %M, %Y') creationDate from tuser tu, tReportAbuseLog tra where tra.userId = tu.userid and tra.entityId = ?";
        $Result = $dbHandle->query($queryCmd,array($msgId));
        foreach($Result->result_array() as $row) {
            $contentArr['name'] = ($row['firstname']=='')?$row['displayname']:$row['firstname'];
            $contentArr['date'] = $row['creationDate'];
            $contentArr['receiverId'] = $row['userid'];
            $entityType = getAbuseEntityName($row['entityType']);
            $contentArr['entityType'] = $entityType;
            $email = $row['email'];
            //$content = $this->load->view("search/searchMail",$contentArr,true);
            //$mail_client = new Alerts_client();
            //$response= $mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
            Modules::run('systemMailer/SystemMailer/abusePeopleMail', $email, $contentArr);
        }
        return;
    }

    function getTopAnswersWall($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadNo=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $displayedAnswerId=$parameters['4'];
        $userId = $parameters['5'];
        $userGroup = $parameters['6'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();
        if($userId > 0)
            $getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=m1.msgId and ral.userId=".$dbHandle->escape($userId)."),0) reportedAbuse ";
        //This query gets the answers to the question
        $queryCmd = "select m1.*,if((m1.listingTypeId = 0),'',(select lm.listing_title  from  listings_main lm where lm.listing_type_id = m1.listingTypeId and lm.listing_type = m1.listingType and lm.status IN ('live','abused')  order by lm.listing_id desc limit 1)) listingTitle,t.displayname,t.firstname, t.lastname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage,if((m1.userId = ".$dbHandle->escape($userId)." ),0,ifnull((select 100 from messageTableBestAnsMap mbam where mbam.threadId = m1.threadId and m1.msgId = mbam.bestAnsId ),ifnull((select 90 from tuser T1 where T1.userid = m1.userId and T1.usergroup = 'experts'),ifnull((select (m1.digUp - m1.digDown) from messageTable mtd where mtd.threadId=? and mtd.msgId=m1.msgId and mtd.parentId!=0),0)))) sortFlag,ifnull((select 1 from messageTableBestAnsMap mbam where mbam.threadId = m1.threadId and m1.msgId = mbam.bestAnsId),0) bestAnsFlag, ifnull((select count(*) from messageTable mCC where mCC.threadId = ? and mCC.status IN ('live','closed') and mCC.mainAnswerId = m1.msgId),0) commentCount ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where  m1.threadId=? and parentId = threadId and msgId != ? and status IN ('live','closed') order by sortFlag desc,m1.creationDate desc , path asc LIMIT $startFrom,$count";
        error_log_shiksha( 'getTopAnswersWall query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($threadNo,$threadNo,$threadNo,$displayedAnswerId));
        $mainAnswerIdCsv = '';
        $mainAnswerUserIdCsv = '';
        foreach ($query->result_array() as $row) {
            $mainAnswerIdCsv .= ($mainAnswerIdCsv == '')?$row['msgId']:','.$row['msgId'];
            array_push($msgArray,array($row,'struct'));
            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$row['userId']:','.$row['userId'];
        }

        $this->load->model('QnAModel');
        $msgArrayLevelVcard = array();
        $msgArrayLevelVcard = $this->QnAModel->getLevelAndVCardStausForUser($dbHandle,$mainAnswerUserIdCsv);
        $msgArrayLevelVcard = is_array($msgArrayLevelVcard)?$msgArrayLevelVcard:array();

        $answerSuggestions = $this->QnAModel->getSuggestedInstitutes($mainAnswerIdCsv,false); 
        $answerSuggestions = is_array($answerSuggestions)?$answerSuggestions:array(); 

        $mainArr = array();
        array_push($mainArr,array(
            array(
            'MsgTree'=>array($msgArray,'struct'),
            'levelVCard' => array($msgArrayLevelVcard,'struct'),
            'answerSuggestions' => array($answerSuggestions,'struct'),
            ),
            'struct'));//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    //Get the Category and Country for the Questions
    function getCategoryCountry($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $relatedQuesCsv=$parameters['1'];
        $cardStatus = '0';
	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle();
        $this->load->model('QnAModel');
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$relatedQuesCsv);
        $response = array($msgArrayCatCountry,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getCommentSection($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $answerId=$parameters['1'];
        $userId = $parameters['2'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();
        if($userId > 0){
            $getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=m1.msgId and ral.userId=".$dbHandle->escape($userId)."),0) reportedAbuse ";
	}
        //This query gets the comments to the answer
	if($answerId>0){
	        $queryCmd = "select m1.*,t1.displayname,t1.firstname, t1.lastname, t1.lastlogintime,t1.userid userId,t1.avtarimageurl userImage $getReportedAbuse from messageTable m1, tuser t1 where m1.userId=t1.userid and m1.status IN ('live','closed') and m1.fromOthers = 'user' and m1.mainAnswerId = ?";
        	error_log_shiksha( 'getCommentSection query cmd is ' . $queryCmd,'qna');
	        $query = $dbHandle->query($queryCmd, array($answerId));
        	foreach ($query->result_array() as $row) {
	            array_push($msgArray,array($row,'struct'));
        	}
	}
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'commentTree'=>array($msgArray,'struct'),
            ),
            'struct'));//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getEntityComments($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadNo=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $userId = $parameters['4'];
        $mainAnswerUserIdCsv = '';
        $mainArr = array();
        if($userId > 0)
            $getReportedAbuse = ", ifnull((select pointsAdded from tReportAbuseLog ral where ral.entityId=m1.msgId and ral.userId=".$dbHandle->escape($userId)."),0) reportedAbuse ";

        //This query gets the comments to the entity
        $queryCmd = "select SQL_CALC_FOUND_ROWS m1.*,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage,(select mtb.status from messageTable mtb where mtb.msgId = ?) blogStatus ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.threadId = ? and m1.parentId = ? and m1.status NOT IN ('deleted','abused') order by m1.creationDate DESC limit $startFrom,$count";
        error_log_shiksha( 'getEntityComments query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($threadNo, $threadNo,$threadNo));
        $mainAnswerIdCsv = '';
        $msgArray = array();
	$i=0; 

        $childDetailsType = "";

        foreach ($query->result_array() as $row) {
            //array_push($msgArray,array($row,'struct'));
            $msgArray[$i] = $row;
            $childDetailsType = $row['fromOthers'];
	    $i++;
            $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$row['userId']:','.$row['userId'];
            $mainAnswerIdCsv .= ($mainAnswerIdCsv == '')?$row['msgId']:','.$row['msgId'];
        }

        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $dbHandle->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
            $totalRows = $row->totalRows;
        }
        $commentArray = array();
	$j=0;
        //This query gets the replies to the comments
	if($threadNo!='' && $mainAnswerIdCsv!=''){
	    $queryCmd = "select m1.*,t.displayname,t.lastlogintime,t.userid userId,t.avtarimageurl userImage,if((m1.mainAnswerId<=0),true,(select displayname from tuser tp,messageTable mp where tp.userid=mp.userId and mp.msgId = m1.parentId))parentDisplayName ".$getReportedAbuse." from messageTable m1 LEFT JOIN  tuser t ON (t.userId=m1.userId) where m1.threadId = ? and m1.parentId != ? and m1.parentId > 0 and m1.mainAnswerId IN (".$mainAnswerIdCsv.") and m1.status NOT IN ('deleted','abused') order by m1.creationDate DESC";
	    error_log_shiksha( 'getEntityComments query cmd is ' . $queryCmd,'qna');
	    $query = $dbHandle->query($queryCmd, array($threadNo,$threadNo));
	    foreach ($query->result_array() as $row) {
		//array_push($commentArray,array($row,'struct'));
		$commentArray[$j] = $row;
		$j++;
		$mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$row['userId']:','.$row['userId'];
	    }
	}
        $this->load->model('QnAModel');
        $msgArrayLevelVcard = array();
	if($mainAnswerUserIdCsv!=''){
	    //$msgArrayLevelVcard = $this->QnAModel->getLevelAndVCardStausForUser($dbHandle,$mainAnswerUserIdCsv);
	    $msgArrayLevelVcard = is_array($msgArrayLevelVcard)?$msgArrayLevelVcard:array();
	}

	/*
        array_push($mainArr,array(
            array(
            'MsgTree'=>array($msgArray,'struct'),
            'Replies'=>array($commentArray,'struct'),
            'totalRows' => array($totalRows,'string'),
            'levelVCard' => array($msgArrayLevelVcard,'struct')
            ),
            'struct'));//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
	*/
        $mainArr = array();
        if($childDetailsType == "blog"){
            //AND fromOthers = 'blog'
            $queryCmd1 = "SELECT msgCount from messageTable where msgId = ? AND status = 'live'";
            $rs = $dbHandle->query($queryCmd1,array($threadNo))->result_array();
            $childCount = $rs[0]['msgCount'];
            $mainArr[0]['commentReplyCount'] = $childCount;
        }
        $mainArr[0]['MsgTree'] = $msgArray;
        $mainArr[0]['Replies'] = $commentArray;
        $mainArr[0]['totalRows'] = $totalRows;
	$mainArr[0]['levelVCard'] = $msgArrayLevelVcard;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function showOtherRating($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $answerId=$parameters['1'];
        $shownUserId=$parameters['2'];
        $loggedUserId=$parameters['3'];
	$dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();$mainArr = array();
        if($loggedUserId!=0)
            // $followData = ", ifnull((select 1 from followUser where followingUserId=$loggedUserId and followedUserId=t1.userid),0) isFollowing";
            $followData = ", ifnull((select 1 from tuserFollowTable where status = 'follow' and userId=$loggedUserId and entityId=t1.userid),0) isFollowing";
        $queryCmd = "select t1.displayname,t1.lastlogintime,t1.userid userId,t1.avtarimageurl userImage,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level $followData from digUpUserMap d1, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where d1.userId=t1.userid and d1.product IN ('qna') and d1.digFlag = '1' and d1.digUpStatus='live' and d1.productId = ? and d1.userId != ?";
        $query = $dbHandle->query($queryCmd, array($answerId,$shownUserId));
        foreach ($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getProfileData($request) {
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $start=$parameters['2'];
        $count=$parameters['3'];
        $threadIdCsv=$parameters['4'];
        $lastTimeStamp=$dbHandle->escape($parameters['5']);
        $entityType=$parameters['6'];
        $viewedUserId=$dbHandle->escape($parameters['7']);
        $product=$parameters['8'];

        $result = '';
        $date = date("Y-m-d");
        $i=10;
        $threadIdArray = explode(',',$threadIdCsv);
        do {
            $date = strtotime("-".$i." days",strtotime($date));
            $date = date ( 'Y-m-j' , $date );
            if($entityType!='' && $entityType!='Question' && $entityType!='Answer' && $entityType!='Comment') //For discussion/review/announcement
                $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey,m1.fromOthers product from messageTable m1 where m1.fromOthers IN ('$entityType') and m1.status in ('live','closed') and m1.userId = ".$viewedUserId." and m1.creationDate < '".$lastTimeStamp."' and m1.creationDate > '".$date."' and m1.msgTxt != 'dummy' and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed')))))) result order by result.sortingTime desc) res where res.sortingKey NOT IN (?) group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
            else if($entityType=='Question')
                    $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey,m1.fromOthers product from messageTable m1 where m1.fromOthers='user' and m1.parentId = 0 and m1.userId = ".$viewedUserId." and m1.status in ('live','closed') and m1.creationDate < '".$lastTimeStamp."' and m1.creationDate > '".$date."' and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed')))))) result order by result.sortingTime desc) res where res.sortingKey NOT IN (?) group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
                else if($entityType=='Answer')
                        $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey,m1.fromOthers product from messageTable m1 where m1.fromOthers='user' and m1.parentId != 0 and m1.mainAnswerId = 0 and m1.userId = ".$viewedUserId." and m1.status in ('live','closed') and m1.creationDate < '".$lastTimeStamp."' and m1.creationDate > '".$date."' and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed')))))) result order by result.sortingTime desc) res where res.sortingKey NOT IN (?) group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
                    else if($entityType=='Comment')
                            $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey,m1.fromOthers product from messageTable m1 where m1.fromOthers='user' and m1.parentId != 0 and m1.mainAnswerId != 0 and m1.userId = ".$viewedUserId." and m1.status in ('live','closed') and m1.creationDate < '".$lastTimeStamp."' and m1.creationDate > '".$date."' and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed')))))) result order by result.sortingTime desc) res where res.sortingKey NOT IN (?) group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
                        else	//For all the entities
                            //$queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey,m1.fromOthers product from messageTable m1 where m1.fromOthers IN ('user','discussion','review','announcement') and m1.status in ('live','closed') and m1.creationDate <= '".$lastTimeStamp."' and m1.creationDate > '".$date."' and m1.msgTxt != 'dummy' and m1.userId = ".$viewedUserId." and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed'))))) UNION (select d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.threadId sortingKey, mta.fromOthers product from digUpUserMap d1, messageTable mta where d1.product='qna' and d1.digFlag = 1 and d1.digTime < '".$lastTimeStamp."' and d1.digTime > '".$date."' and d1.productId = mta.msgId and mta.fromOthers IN ('user') and  mta.status IN ('live','closed') and (mta.userId=".$viewedUserId." OR d1.userId=".$viewedUserId.") and (select status from messageTable pp1 where pp1.msgId = mta.threadId) IN ('live','closed')) UNION (select b1.creation_time sortingTime,b1.threadId,b1.bestAnsId,'0' Id,'bestanswer' type,b1.threadId sortingKey,mt.fromOthers product from messageTableBestAnsMap b1, messageTable mt where b1.creation_time < '".$lastTimeStamp."' and b1.creation_time > '".$date."' and (b1.threadId = mt.threadId) and (b1.bestAnsId = mt.msgId) and mt.status IN ('live','closed') and mt.userId=".$viewedUserId." and (select status from messageTable pp2 where pp2.msgId = mt.threadId) IN ('live','closed'))) result order by result.sortingTime desc) res where res.sortingKey NOT IN (".$threadIdCsv.") group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
			    $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.path,m1.threadId sortingKey,m1.fromOthers product from messageTable m1 where m1.fromOthers IN ('user','discussion','review','announcement') and m1.status in ('live','closed') and m1.creationDate <= '".$lastTimeStamp."' and m1.creationDate > '".$date."' and m1.msgTxt != 'dummy' and m1.userId = ".$viewedUserId." and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed'))))) UNION (select d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.path,mta.threadId sortingKey, mta.fromOthers product from digUpUserMap d1, messageTable mta where d1.product='qna' and d1.digFlag = 1 and d1.digUpStatus='live' and d1.digTime < '".$lastTimeStamp."' and d1.digTime > '".$date."' and d1.productId = mta.msgId and mta.fromOthers IN ('user') and  mta.status IN ('live','closed') and mta.userId=".$viewedUserId." and (select status from messageTable pp1 where pp1.msgId = mta.threadId) IN ('live','closed')) UNION (select d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.path,mta.threadId sortingKey, mta.fromOthers product from digUpUserMap d1, messageTable mta where d1.product='qna' and d1.digFlag = 1 and d1.digUpStatus='live' and d1.digTime < '".$lastTimeStamp."' and d1.digTime > '".$date."' and d1.productId = mta.msgId and mta.fromOthers IN ('user') and  mta.status IN ('live','closed') and d1.userId=".$viewedUserId." and (select status from messageTable pp1 where pp1.msgId = mta.threadId) IN ('live','closed')) UNION (select b1.creation_time sortingTime,b1.threadId,b1.bestAnsId,'0' Id,'bestanswer' type,mt.path,b1.threadId sortingKey,mt.fromOthers product from messageTableBestAnsMap b1, messageTable mt where b1.creation_time < '".$lastTimeStamp."' and b1.creation_time > '".$date."' and (b1.threadId = mt.threadId) and (b1.bestAnsId = mt.msgId) and mt.status IN ('live','closed') and mt.userId=".$viewedUserId." and (select status from messageTable pp2 where pp2.msgId = mt.threadId) IN ('live','closed'))) result order by result.sortingTime desc) res where res.sortingKey NOT IN (?) group by res.sortingKey order by res.sortingTime DESC limit 0,$count";

            $result = $dbHandle->query($queryCmd,array($threadIdArray));
            $i+=40;
            if($i>120) break;
        }while(count($result->result_array())<$count);
        //Now, get the data for these Id's based on their type and product
        $wallData = $this->getDataForWall($appId, "false", $userId, $result);
        $mainAnswerUserIdCsv = $wallData[0]['mainAnswerUserIdCsv'];
        $threadIdList = $wallData[0]['threadIdList'];
        $returnArr = $wallData[0]['returnArr'];

        //Query to get the Level advance of the users
        $levelAdvanceArr = array();
        if(!($entityType=='Question' || $entityType=='Answer' || $entityType=='Comment' || $entityType=='Announcement' || $entityType=='Discussion')) {
            $levelAdvanceQuery = "select ups.lastLevelUpgradedTime sortingTime,t1.displayname,ups.userId,t1.email,'level' type, (select level from userPointLevelByModule  where minLimit<= ifnull(ups.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from userpointsystembymodule ups, tuser t1 where t1.userid=ups.userId and ups.userId = ? and ups.modulename = 'AnA' and ups.lastLevelUpgradedTime >= ? and ups.lastLevelUpgradedTime < '".$lastTimeStamp."' and ups.userpointvaluebymodule >= 250 order by sortingTime desc limit 0,$count";
            $query = $dbHandle->query($levelAdvanceQuery, array($viewedUserId, $date));
            $i=0;
            foreach ($query->result_array() as $rowTemp) {
                $mainAnswerUserIdCsv .= ($mainAnswerUserIdCsv == '')?$rowTemp['userId']:','.$rowTemp['userId'];
                //array_push($levelAdvanceArr,array($rowTemp,'struct'));
                $levelAdvanceArr[$i] = $rowTemp;
                $i++;
            }
        }
        //Query ends for level advance
        $this->load->model('QnAModel');
        $msgArrayLevelVcard = array();
        if($mainAnswerUserIdCsv!='') {
            $msgArrayLevelVcard = $this->QnAModel->getLevelAndVCardStausForUser($dbHandle,$mainAnswerUserIdCsv,true,true,true);
            $msgArrayLevelVcard = is_array($msgArrayLevelVcard)?$msgArrayLevelVcard:array();
        }
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdList,true,true,true);
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
        //$response = array($returnArr,'struct');

        $mainAnswerIdCsv = $wallData[0]['mainAnswerIdCsv'];
        $answerSuggestions = $this->QnAModel->getSuggestedInstitutes($mainAnswerIdCsv); 
        $answerSuggestions = is_array($answerSuggestions)?$answerSuggestions:array(); 

        $mainArr = array();
        //Modified for Shiksha performance task on 8 March
		/*array_push($mainArr,array(
				array(
					'results'=>array($returnArr,'struct'),
					'levelAdvance'=>array($levelAdvanceArr,'struct'),
					'categoryCountry'=>array($msgArrayCatCountry,'struct'),
					'levelVCard'=>array($msgArrayLevelVcard,'struct'),
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');*/
        $mainArr[0]['results'] = $returnArr;
        $mainArr[0]['levelAdvance'] = $levelAdvanceArr;
        $mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
        $mainArr[0]['levelVCard'] = $msgArrayLevelVcard;
        $mainArr[0]['answerSuggestions'] = $answerSuggestions;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getUserProfileDetails($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $page = '';
        $msgArray = $this->getUserVCardDetailsDB($appId,$userId,$page);

        //Now, get the other information which is required on the page
        $dbHandle = $this->_loadDatabaseHandle();
        $otherUserDetails = array();
        $participateUserDetails = array();

        //Get the other details like user Creation Date, User upgradation time, Likes, Total points
        $today = date("Y-m-d");
        $week = date("Y-m-d", strtotime("-7 day"));
        //Modified by Ankur on 8 March for Shiksha cafe performance
        $userAnswerIds = '';
        $queryCmdUser = "select m1.msgId from messageTable m1 where m1.userId=? and m1.fromOthers='user' and m1.parentId!=0 and m1.mainAnswerId=0 and m1.status IN ('live','closed')";
        $queryUser = $dbHandle->query($queryCmdUser, array($userId));
        foreach ($queryUser->result_array() as $rowTemp) {
            $userAnswerIds .= ($userAnswerIds=='')?$rowTemp['msgId']:",".$rowTemp['msgId'];
        }
        $likeQuery = "";
        if($userAnswerIds!='')
            $likeQuery = ", (SELECT count(*) FROM digUpUserMap d1 where d1.product='qna' and d1.digFlag = '1' and d1.digUpStatus='live' and d1.productId IN ($userAnswerIds)) likes";
        $queryCmd = "select t1.userid,t1.avtarimageurl, DATE_FORMAT(t1.usercreationDate, '%b %Y') creationDate,DATE_FORMAT(upsm.lastLevelUpgradedTime, '%b %Y') upgradeDate, upsm.userpointvaluebymodule totalPoints $likeQuery from tuser t1 LEFT JOIN userpointsystembymodule upsm ON (t1.userid=upsm.userId and upsm.modulename = 'AnA') where t1.userid = ? and t1.usergroup != 'cms' limit 1";
        $query = $dbHandle->query($queryCmd, array($userId));
        foreach ($query->result_array() as $rowTemp) {
            if($userAnswerIds=='')
                $rowTemp['likes'] = 0;
            array_push($otherUserDetails,array($rowTemp,'struct'));
        }

        //Query for Weekly points
        $queryCmd = "select t1.userid,SUM(ups.pointvalue) weeklyPoints from tuser t1, userpointsystemlog ups where t1.userid = ? and ups.action != 'Register' and ups.module='AnA' and ups.timestamp >= '".$week."' and t1.userid=ups.userId and t1.usergroup != 'cms' group by ups.userId limit 1";
        $query = $dbHandle->query($queryCmd, array($userId));
        foreach ($query->result_array() as $rowTemp)
            array_push($otherUserDetails,array($rowTemp,'struct'));

	//Get the User expertise categories from his answers
	$year = date("Y-m-d", strtotime("-365 day"));
	$queryCmd = "select count(*) answerCount, mc1.categoryId boardId from messageTable m1, messageCategoryTable mc1 where m1.userId=? and m1.fromOthers='user' and m1.status IN ('live','closed') and m1.threadId = mc1.threadId and m1.parentId!=0 and m1.mainAnswerId=0 and mc1.categoryId IN (2,3,4,5,6,7,8,9,10,11,12,13,14) and m1.creationDate>'".$year."' group by mc1.categoryId order by answerCount desc limit 2";
	$query = $dbHandle->query($queryCmd, array($userId));
        $userExpertize = array();
	foreach ($query->result_array() as $rowTemp) {
	    $queryCmdName = "select name from categoryBoardTable c1 where boardId = ?";
	    $queryName = $dbHandle->query($queryCmdName, array($rowTemp['boardId']));
	    $rowName = $queryName->row();
	    $rowTemp['name'] = $rowName->name;
            array_push($userExpertize,array($rowTemp,'struct'));
	}

        //Queries to get the total discussion posts, total announcement posts, total participation points and weekly points
        //$queryCmd = "select upsm.userpointvaluebymodule totalParticipatePoints, (select count(*) from messageTable where userId = $userId and fromOthers = 'discussion' and status IN ('live','closed') and parentId=0) discussionPosts, (select count(*) from messageTable where userId = $userId and fromOthers = 'announcement' and status IN ('live','closed') and parentId=0) announcementPosts from userpointsystembymodule upsm where upsm.modulename = 'Participate' and upsm.userId = $userId limit 1;";
	$queryCmd = "select upsm.userpointvaluebymodule totalParticipatePoints, (select count(*) from messageTable where userId = ? and fromOthers = 'discussion' and status IN ('live','closed') and parentId=0) discussionPosts, (select count(*) from messageTable where userId = ? and fromOthers = 'announcement' and status IN ('live','closed') and parentId=0) announcementPosts from tuser t1 LEFT JOIN userpointsystembymodule upsm ON (t1.userid=upsm.userId and upsm.modulename = 'Participate') where t1.userid = ? and t1.usergroup != 'cms' limit 1";
        $query = $dbHandle->query($queryCmd, array($userId,$userId,$userId));
        foreach ($query->result_array() as $rowTemp)
            array_push($participateUserDetails,array($rowTemp,'struct'));

        //Query for Weekly points in Participation
        $queryCmd = "select t1.userid,SUM(ups.pointvalue) weeklyParticipatePoints from tuser t1, userpointsystemlog ups where t1.userid = ? and ups.action != 'Register' and ups.module='Participate' and ups.timestamp >= '".$week."' and t1.userid=ups.userId and t1.usergroup != 'cms' group by ups.userId limit 1";
        $query = $dbHandle->query($queryCmd, array($userId));
        foreach ($query->result_array() as $rowTemp)
            array_push($participateUserDetails,array($rowTemp,'struct'));

        $mainArr = array();
        array_push($mainArr,array(
            array(
            'VCardDetails'=>array($msgArray,'struct'),
            'otherUserDetails'=>array($otherUserDetails,'struct'),
            'participateUserDetails'=>array($participateUserDetails,'struct'),
            'userExpertize'=>array($userExpertize,'struct'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function setFollowUser($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $followingUserId=$parameters['1'];
        $followedUserId=$parameters['2'];
	$dbHandle = $this->_loadDatabaseHandle('write');
       // $this->load->model('UserPointSystemModel');
        //$this->UserPointSystemModel->followUserReputationPoints($dbHandle,$followedUserId,$followingUserId,'followUser');
        if($dbHandle == '') {
            error_log_shiksha('setFollowUser can not create db handle','qna');
        }
        if($followingUserId == $followedUserId)
            return $this->xmlrpc->send_response("SAME");
        // $queryCmd = "select * from `followUser` where `followingUserId` = ? and `followedUserId` = ?";
        $queryCmd = "select * from tuserFollowTable where userId = ? and entityId = ? and status = 'follow'";
        $query = $dbHandle->query($queryCmd, array($followingUserId,$followedUserId));
        if($query->num_rows()>0)
            return $this->xmlrpc->send_response("FOLLOWING");
        // $queryCmd = "INSERT INTO `shiksha`.`followUser` (`ID` ,`followingUserId` ,`followedUserId`)VALUES (NULL , ?, ?);";
        $queryCmd = "INSERT into tuserFollowTable (userId,entityId,entityType,followType,status,creationTime,modificationTime) values (?,?,'user','|explicit|','follow',now(),now())";

        $query = $dbHandle->query($queryCmd, array($followingUserId,$followedUserId));
        $response = "1";
        return $this->xmlrpc->send_response($response);
    }

    function getFollowUser($request) {
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $followingUserId=$parameters['1'];
        $followedUserIdList=$parameters['2'];
        $userData = array();
        if($followingUserId != ''){
            // $queryCmd = "select fu.*,t1.userid userId,t1.displayname,t1.firstname, t1.lastname, t1.avtarimageurl,t1.lastlogintime,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from followUser fu, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where fu.followingUserId = t1.userid and fu.followedUserId IN ($followedUserIdList) and fu.followingUserId =".$followingUserId;

            //$queryCmd = "select fu.id as ID,fu.userId as followingUserId, fu.entityId as followedUserId, fu.creationTime as followUserTime,t1.userid userId,t1.displayname,t1.firstname, t1.lastname, t1.avtarimageurl,t1.lastlogintime,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from tuserFollowTable fu, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where fu.userID = t1.userid and fu.status = 'follow' and fu.entityId IN ($followedUserIdList) and fu.userId=".$followingUserId;
	    $queryCmd = "select fu.id as ID,fu.userId as followingUserId, fu.entityId as followedUserId, fu.creationTime as followUserTime,t1.userid userId,t1.displayname,t1.firstname, t1.lastname, t1.avtarimageurl,t1.lastlogintime,ifnull((select levelName from userpointsystembymodule where userId=fu.userId and modulename = 'AnA' limit 1),'Beginner-Level 1') level from tuserFollowTable fu, tuser t1 where fu.userID = t1.userid and fu.status = 'follow' and fu.entityId IN ($followedUserIdList) and fu.userId=".$followingUserId;
	}
        else{
            // $queryCmd = "select fu.*,t1.userid userId,t1.displayname,t1.firstname, t1.lastname, t1.avtarimageurl,t1.lastlogintime,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level, ifnull((select 1 from followUser where followingUserId=$followedUserIdList and followedUserId=fu.followingUserId limit 1),0) isFollowing from followUser fu, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where fu.followingUserId = t1.userid and fu.followedUserId IN ($followedUserIdList)";
            //$queryCmd = "select fu.id as ID,fu.userId as followingUserId, fu.entityId as followedUserId, fu.creationTime as followUserTime,t1.userid userId,t1.displayname,t1.firstname, t1.lastname, t1.avtarimageurl,t1.lastlogintime,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level, ifnull((select 1 from tuserFollowTable where status = 'follow' and userId=$followedUserIdList and entityId=fu.userId limit 1),0) isFollowing from tuserFollowTable fu, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where fu.status = 'follow' and fu.userId = t1.userid and fu.entityId IN ($followedUserIdList)";
	    $queryCmd = "select fu.id as ID,fu.userId as followingUserId, fu.entityId as followedUserId, fu.creationTime as followUserTime,t1.userid userId,t1.displayname,t1.firstname, t1.lastname, t1.avtarimageurl,t1.lastlogintime,ifnull(u.levelName,'Beginner-Level 1') level, ifnull((select 1 from tuserFollowTable where status = 'follow' and userId=$followedUserIdList and entityId=fu.userId limit 1),0) isFollowing from tuserFollowTable fu, tuser t1 left join userpointsystembymodule u on (u.userId=t1.userId and u.modulename = 'AnA') where fu.status = 'follow' and fu.userId = t1.userid and fu.entityId IN ($followedUserIdList) AND fu.entityType='user'";
	}

        $query = $dbHandle->query($queryCmd);
        $i=0;
        foreach ($query->result_array() as $rowTemp) {
        //array_push($userData,array($rowTemp,'struct'));
            $userData[$i] = $rowTemp;
            $i++;
        }
        //$response = array($userData,'struct');
        $responseString = base64_encode(gzcompress(json_encode($userData)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function advisoryBoard($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters[0];
        $level = $parameters[1];
        $start = $parameters[2];
        $count = $parameters[3];

	$dbHandle = $this->_loadDatabaseHandle();
        // All User Levels and User Level Total Points
        $countArray = array();
        $queryCmdAll = "select count(*) as count, levelName as Level from userpointsystembymodule where modulename='AnA' and levelId >= 11 group by levelName order by userpointvaluebymodule desc";
        //$queryCmdAll = "select count(*) count,ifnull((select upl.Level from userPointLevelByModule upl, userpointsystembymodule ups where upl.module = ups.moduleName and upl.module = 'AnA' and ups.userId = upsm.userId and ups.userPointValueByModule >= upl.minLimit LIMIT 1),'Beginner') Level from userpointsystembymodule upsm where upsm.userpointvaluebymodule>=1000 and upsm.modulename='AnA' group by Level order by upsm.userpointvaluebymodule desc";
        $queryAll = $dbHandle->query($queryCmdAll);
        foreach ($queryAll->result_array() as $row) {
            array_push($countArray,array($row,'struct'));
        }
        if($level=='All') {
            $queryCmd = "select *, levelName as Level from userpointsystembymodule where levelId >= 11 and modulename = 'AnA' order by userpointvaluebymodule desc limit $start, $count";
            //$queryCmd = "select *,ifnull((select upl.Level from userPointLevelByModule upl, userpointsystembymodule ups where upl.module = ups.moduleName and upl.module = 'AnA' and ups.userId = upsm.userId and ups.userPointValueByModule >= upl.minLimit LIMIT 1),'Beginner') Level from userpointsystembymodule upsm, expertOnboardTable et where upsm.userpointvaluebymodule>=1000 and upsm.modulename = 'AnA' and upsm.userId=et.userId and et.status = 'Live' order by upsm.userpointvaluebymodule desc limit ".$start.", ".$count;
        } else {
            $queryCmd = "select *, levelName as Level from userpointsystembymodule where levelId >= 11 and modulename = 'AnA' and levelName = '".$level."' order by userpointvaluebymodule desc limit $start, $count";
            //$queryCmd = "select *,ifnull((select upl.Level from userPointLevelByModule upl, userpointsystembymodule ups where upl.module = ups.moduleName and upl.module = 'AnA' and ups.userId = upsm.userId and ups.userPointValueByModule >= upl.minLimit LIMIT 1),'Beginner') Level from userpointsystembymodule upsm, expertOnboardTable et where upsm.userpointvaluebymodule>=".$levelArr[$level]['min']." AND upsm.userpointvaluebymodule<".$levelArr[$level]['max']." and upsm.modulename = 'AnA' and upsm.userId=et.userId and et.status = 'Live' order by upsm.userpointvaluebymodule desc limit ".$start.", ".$count;
        }

        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        $userIdList = '';
        foreach ($query->result_array() as $row) {
            $userIdList .= ($userIdList == '')?$row['userId']:",".$row['userId'];
            array_push($msgArray,array($row,'struct'));
        }
        $userDetails = array();
        if($userIdList != '')
            $userDetails = $this->getExpertPanelData($appId,$userIdList);
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'count'=>array($countArray,'struct'),
            'filterArray'=>array($msgArray,'struct'),
            'userDetails'=>array($userDetails,'struct')
            ),'struct')
        );
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getExpertPanelData($appId, $userIdList) {
	$dbHandle = $this->_loadDatabaseHandle();
        $vcardArray = array();
        $userDetailsArray = array();
        $userExpertize = array();
        $tempWeekPoints = array();
        $today = date("Y-m-d");
        $week = date("Y-m-d", strtotime("-7 day"));
        $page = '';
        $userIdArr = split(",", $userIdList);
        //Get the VCard details
        for($i=0;$i<count($userIdArr);$i++) {
            $msgArray = array();
            $msgArray = $this->getUserVCardDetailsDB($appId,$userIdArr[$i],$page);
            array_push($vcardArray,array($msgArray,'struct'));

            //Get the other details like user Creation Date, User upgradation time, Likes, Weekly points
            $otherUserDetails = array();
            $queryCmd = "select t1.userid, DATE_FORMAT(t1.usercreationDate, '%b %Y') creationDate,DATE_FORMAT(upsm.lastLevelUpgradedTime, '%b %Y') upgradeDate, upsm.userpointvaluebymodule totalPoints,(SELECT count(*) FROM digUpUserMap d1, messageTable m1 where d1.product='qna' and d1.digFlag = '1' and d1.digUpStatus='live' and d1.productId = m1.msgId and m1.userId=? and m1.fromOthers='user' and m1.parentId!=0 and m1.mainAnswerId=0 and m1.status IN ('live','closed')) likes from tuser t1 LEFT JOIN userpointsystembymodule upsm ON (t1.userid=upsm.userId and upsm.modulename = 'AnA') where t1.userid = ? limit 1";
            $query = $dbHandle->query($queryCmd, array($userIdArr[$i],$userIdArr[$i]));
            foreach ($query->result_array() as $rowTemp)
                array_push($otherUserDetails,array($rowTemp,'struct'));
            array_push($userDetailsArray,array($otherUserDetails,'struct'));

            //Get the User expertise categories from user answers
            $tempExpertize = array();
            $queryCmd = "select count(*) answerCount, c1.name, c1.boardId,m1.userId from messageTable m1, messageCategoryTable mc1, categoryBoardTable c1 where m1.userId IN ($userIdArr[$i]) and m1.fromOthers='user' and m1.status IN ('live','closed') and m1.threadId = mc1.threadId and m1.parentId!=0 and m1.mainAnswerId=0 and mc1.categoryId=c1.boardId and c1.parentId = 1 group by c1.name order by answerCount desc limit 2";
            $query = $dbHandle->query($queryCmd);
            foreach ($query->result_array() as $rowTemp)
                array_push($tempExpertize,array($rowTemp,'struct'));
            array_push($userExpertize,array($tempExpertize,'struct'));

            $queryCmd = "select t1.userid,SUM(ups.pointvalue) weeklyPoints from tuser t1, userpointsystemlog ups where t1.userid = ? and ups.action != 'Register' and ups.module='AnA' and ups.timestamp >= '".$week."' and t1.userid=ups.userId group by ups.userId limit 1";
            $query = $dbHandle->query($queryCmd, array($userIdArr[$i]));
            foreach ($query->result_array() as $rowTemp)
                array_push($tempWeekPoints,array($rowTemp,'struct'));
        }

        $mainArr = array();
        array_push($mainArr,array(
            array(
            'VCardDetails'=>array($vcardArray,'struct'),
            'otherUserDetails'=>array($userDetailsArray,'struct'),
            'userExpertize'=>array($userExpertize,'struct'),
            'weeklyPoints'=>array($tempWeekPoints,'struct')
            ),'struct')
        );//close array_push
        return $mainArr;
    }

    function getBestAnswerMailData($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $threadId=$parameters['1'];
        $msgId=$parameters['2'];
        $commenterUserId=$parameters['3'];
	$dbHandle = $this->_loadDatabaseHandle();
        $userData = array();
        $queryCmd = "select m1.userId, m1.msgTxt,m1.parentId,m1.msgId,t1.firstname,t1.displayname,t1.email from messageTable m1, tuser t1 where m1.threadId=? and m1.fromOthers='user' and m1.status IN ('live','closed') and m1.userId=t1.userid";
        $query = $dbHandle->query($queryCmd, array($threadId));
        foreach ($query->result_array() as $rowTemp)
            array_push($userData,array($rowTemp,'struct'));
        $userRatingData = array();
        $queryCmd = "select t1.userid userId,t1.firstname,t1.displayname,t1.email from digUpUserMap d1, tuser t1, messageTable m1 where d1.productId = m1.msgId and m1.threadId=? and d1.product = 'qna' and d1.digFlag='1' and d1.digUpStatus='live' and d1.userId=t1.userid";
        $query = $dbHandle->query($queryCmd, array($threadId));
        foreach ($query->result_array() as $rowTemp)
            array_push($userRatingData,array($rowTemp,'struct'));

        $mainArr = array();
        array_push($mainArr,array(
            array(
            'mtData'=>array($userData,'struct'),
            'ratingData'=>array($userRatingData,'struct')
            ),'struct')
        );
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getParentComments($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
	$dbHandle = $this->_loadDatabaseHandle();

        //get the path, threadId and AnswerId of the msgId
        $msgPath = '';$threadId='';$mainAnsId='';
        $queryCmd = "select m1.path,m1.threadId, m1.mainAnswerId from messageTable m1 where m1.msgId=? and m1.status IN ('live','closed')";
        $query = $dbHandle->query($queryCmd, array($msgId));
        foreach ($query->result_array() as $rowTemp) {
            $msgPath = $rowTemp['path'];
            $threadId = $rowTemp['threadId'];
            $mainAnsId = $rowTemp['mainAnswerId'];
        }
        $pathArr = explode(".", $msgPath);
        for($i=0;$i<count($pathArr);$i++) {
            if(($pathArr[$i] != $threadId) && ($pathArr[$i] != $mainAnsId)  && ($pathArr[$i] != $msgId)) {
                $pathString .= ($pathString=='')?$pathArr[$i]:",".$pathArr[$i];
            }
        }

        $parentCommentData = array();
        $queryCmd = "select t1.userid userId,t1.displayname,t1.firstname,t1.lastname,t1.email,m1.msgTxt,m1.msgId,m1.status,DATE_FORMAT(m1.creationDate, '%m-%d-%Y, %h:%i %p') creationDateVal ,ifnull((select t2.displayname from tuser t2, messageTable m2 where t2.userid = m2.userId and m1.parentId = m2.msgId and m1.mainAnswerId!=m1.parentId and m2.status IN ('live','closed','abused','deleted')),'') parentDisplayName, (select t2.firstname from tuser t2, messageTable m2 where t2.userid = m2.userId and m1.parentId = m2.msgId and m1.mainAnswerId!=m1.parentId and m2.status IN ('live','closed','abused','deleted')) parentFirstName, (select t2.lastname from tuser t2, messageTable m2 where t2.userid = m2.userId and m1.parentId = m2.msgId and m1.mainAnswerId!=m1.parentId and m2.status IN ('live','closed','abused','deleted')) parentLastName from tuser t1, messageTable m1 where m1.userId=t1.userid and m1.msgId IN ($pathString) and m1.status IN ('live','closed','deleted','abused') order by creationDate desc";
        $query = $dbHandle->query($queryCmd);
        foreach ($query->result_array() as $rowTemp)
            array_push($parentCommentData,array($rowTemp,'struct'));

        $response = array($parentCommentData,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *   Function to Get the Homepage data for Discussion/Announcements category wise
     *   Parameters: Entity type, CategoryId , Country, start, count, userId , order by (date/number)
     *   Output: The details of all the posts, their last comment, Vcard of the users, Level of the users and total count
     **/
    function getHomepageData($request) {
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $entityType=$parameters['1'];
        $categoryId=$parameters['2'];
        $countryId=$parameters['3'];
        $start=$parameters['4'];
        $count=$parameters['5'];
        $userId=$parameters['6'];
        $orderBy=$parameters['7'];

        $stickyArray=array();
        //To get the latest discussions or the latest commented discussions
        $selectForOrdering = ",(select max(creationDate)  from messageTable mo where mo.threadId = mt.threadId) entityCreationDate";
        $orderByString = ($orderBy=='')?"order by entityCreationDate desc":$orderBy;
        $orderByLinkedString = ($orderBy=='')?"order by linked asc,entityCreationDate desc":$orderBy;

        $categoryId = ($categoryId=='')?"1":$categoryId;
        $countryId = ($countryId=='')?"1":$countryId;

        $countryCheck = ''; $countryCheck1 = ''; $countryTable = ''; $countryTable1 = ''; $countryCheckTmp = '';
        if($countryId>1) {
            $countryCheck = " and cmct.threadId=mt2.msgId and cmct.countryId = '$countryId' ";
            $countryCheck1 = " and mct2.threadId=mt.msgId and mct2.countryId = '$countryId' ";
	    $countryCheckTmp = " and mct2.threadId=m1.msgId and mct2.countryId = '$countryId' ";
            $countryTable = ", messageCountryTable cmct ";
            $countryTable1 = ", messageCountryTable mct2 ";
        }

	$msgData = array();
        $threadIdCsv = '';
	$mainAnswerIdCsv = '';
	$userIdCsv = '';
	$i=0;

        //If we have to get the data for all the categories (get the top 5 entries for all categories)
        if(($categoryId=='All' || $categoryId == '' || $categoryId=='1')) {
	    //Code change to set the entities for Management fixed
            if($entityType=='discussion' && ($countryId == 1 || $countryId == 2)) {
                $categoryPref = array('3','2','12','10','4','6','5','7','9','8','11','149');
            }
            else
                $categoryPref = array('3','2','12','10','4','6','5','7','9','8','11','149');
            //End code for Management categories entities

	    //Get the Sticky discussion / announcement list so that these entities could be shown first
	    $tmp2='';
	    $j=0;
	    $queryCheckList = "select distinct stickythreadId from stickyDiscussionAndAnnoucementTable where `type`=? and `status`='live' order by createdDate desc";
	     $query = $dbHandle->query($queryCheckList, array($entityType));
	     foreach ($query->result_array() as $rowTemp) {
	    if($j==0){ $tmp2 = "'".$rowTemp['stickythreadId']."'";}else{ $tmp2 .= ",'".$rowTemp['stickythreadId']."'";}
	    $j++;
	    }

             /*
              * Changed by Vikas
              * Get all main categories from database
              */
              
              $query = $dbHandle->query("SELECT boardId FROM categoryBoardTable WHERE parentId = 1 and flag IN ('national','testprep') ");
              $categoryPref = array();
              
              foreach($query->result() as $row)
              {
                  $categoryPref[] = $row->boardId; 
              }

            //Now, get the count of each Category
            //$queryCmdCount = "select count(*) totalCount, mmct.categoryId from messageTable mt2, messageCategoryTable mmct, categoryBoardTable cbt $countryTable where mt2.fromOthers IN ('$entityType') and mt2.status IN ('live','closed') and mt2.parentId!=0 and mt2.parentId=mt2.threadId and mmct.threadId=mt2.msgId and cbt.boardId = mmct.categoryId AND cbt.parentId = 1 $countryCheck group by mmct.categoryId";
	    $queryCmdCount = "select count(*) totalCount, mmct.categoryId from messageTable mt2, messageCategoryTable mmct $countryTable where mt2.fromOthers IN ('$entityType') and mt2.status IN ('live','closed') and mt2.parentId!=0 and mt2.parentId=mt2.threadId and mt2.mainAnswerId=0 and mmct.threadId=mt2.msgId and mmct.categoryId IN (2,3,4,5,6,7,9,10,11,12,13,14) $countryCheck group by mmct.categoryId";
             error_log("totalcount===".print_r($queryCmdCount,true));
             $queryCount = $dbHandle->query($queryCmdCount);
             $countArray = array();
             foreach ($queryCount->result_array() as $rowTempCount) {
                     $catId = $rowTempCount['categoryId'];
                     $countArray[$catId] = $rowTempCount['totalCount'];
            }

            for($x=0;$x<count($categoryPref);$x++) {
		//$totalCountQuery = ", (select count(*) from messageTable mt2, messageCategoryTable mmct $countryTable where mt2.fromOthers IN ('$entityType') and mt2.status IN ('live','closed') and mt2.parentId!=0 and mt2.parentId=mt2.threadId and mmct.threadId=mt2.msgId and mmct.categoryId = '$categoryPref[$i]' $countryCheck) totalCount";
                $commentCountQuery = ",(select count(*) from messageTable mt1 where mt1.parentId=mt.msgId and status IN ('live','closed')) commentCount";
                //$queryCmd .= "(select mt.*,md.description,t.displayname,t.avtarimageurl,mct1.categoryId,cbt.name $commentCountQuery $totalCountQuery $selectForOrdering from messageTable mt,messageDiscussion md,tuser t,messageCategoryTable mct1 $countryTable1 , categoryBoardTable cbt where mct1.threadId = mt.msgId and mct1.categoryId = cbt.boardId and mct1.categoryId = '$categoryPref[$i]' $countryCheck1 and t.userid=mt.userId and mt.msgId=md.threadId and mt.fromOthers IN ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 $orderByString limit 5)";

		//Get the discussion/announcements which have been made Sticky in this Category
                $numRow=0;
		$stickyDiscussionsCategory = '';
                if($tmp2!=''){
		    //$queryCmd1 = "(select  mt.*,md.description,t.displayname,t.avtarimageurl,mct1.categoryId,cbt.name $commentCountQuery $totalCountQuery $selectForOrdering from messageTable mt,messageDiscussion md,tuser t,messageCategoryTable mct1 $countryTable1 , categoryBoardTable cbt where mct1.threadId = mt.msgId and mct1.categoryId = cbt.boardId and mct1.categoryId = '$categoryPref[$x]' $countryCheck1 and t.userid=mt.userId and mt.msgId=md.threadId and mt.fromOthers IN ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId in ($tmp2) $orderByString)";
		    $queryCmd1 = "(select  mt.msgId,mct1.categoryId, mt.threadId from messageTable mt,messageCategoryTable mct1 $countryTable1  where mct1.threadId = mt.msgId and mct1.categoryId = '$categoryPref[$x]' $countryCheck1 and mt.fromOthers IN ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId in ($tmp2) )";
		    $query = $dbHandle->query($queryCmd1);
		    $numRow = $query->num_rows();
		    //Also, currently we have the complete Sticky discussions list in the tmp2 variable. However, we only need to check for the Sticky discussions in this Category.
		    // So, we will change the tmp2 so that it will contain a max of 5 sticky discussions in this category
		    if($numRow>0){
			    foreach ($query->result_array() as $rowTemp)
				$stickyDiscussionsCategory .= ($stickyDiscussionsCategory=='')?"'".$rowTemp['threadId']."'":",'".$rowTemp['threadId']."'";
		    }
                }
                
		//Now, in case the Sticky discussion/announcement are not 5 in this category, we need to find the latest unique threads on which any activity has occured
		$latestThreadsStr = '';
                /*if($numRow<5) {
		      $date = date("Y-m-d");
		      $days=7;
                      $checkQ = '';
                      if($stickyDiscussionsCategory!='') $checkQ = "and mt.threadId NOT IN ($stickyDiscussionsCategory)";
		      do {
			  $date = strtotime("-".$days." days",strtotime($date));
			  $date = date ( 'Y-m-j' , $date );
			  $queryCmdDate = "select DISTINCT mt.threadId from messageTable mt,messageCategoryTable mct1 $countryTable1 , categoryBoardTable cbt where mct1.threadId = mt.threadId and mct1.categoryId = cbt.boardId and mct1.categoryId = '$categoryPref[$i]' $countryCheck1 and mt.fromOthers IN ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.creationDate > '".$date."' $checkQ and mt.threadId IN (select ank.threadId from messageTable ank where ank.msgId = mt.threadId and ank.status IN ('live','closed') ) order by mt.msgId desc limit 5";
			  $resultDate = $dbHandle->query($queryCmdDate);
			  $days+=10;
			  if($days>30) break;
		      }while( count($resultDate->result_array()) < (5-$numRow) );
		      if(count($resultDate->result_array()) >= (5-$numRow)){	//Only if the count required is acheived, we will set the thread Id list
			  foreach ($resultDate->result_array() as $rowTemp) {
				  $latestThreadsStr .= ($latestThreadsStr=='')?"'".$rowTemp['threadId']."'":",'".$rowTemp['threadId']."'";
			  }
		      }
		      $latestThreadsStr = ($latestThreadsStr!='')?" and mt.threadId IN ($latestThreadsStr) ":"";
		}*/

		//Create the query to get the Latest discussion/announcements. We will first fetch the Sticky ones and if they are less than 5, we will get the latest ones
                // new order for discussions. sticky first, if less than 5 sticky discussion found then non-linked latest discussions and then linked discussions
                // here it is an assumption that linked discussions will not be made sticky.
                $numRes = 5-$numRow;
            
                if($numRow>0) { 
                    $queryCmd .= "(select mt.*,md.description,t.displayname,t.firstname, t.lastname, t.avtarimageurl,mct1.categoryId,cbt.name $commentCountQuery $totalCountQuery $selectForOrdering ,if(qdlt.linkedEntityId is null,0,1) linked from messageTable mt left join questionDiscussionLinkingTable qdlt on mt.threadid = qdlt.linkedEntityId AND qdlt.STATUS =  'accepted' AND qdlt.TYPE = ('$entityType'),messageDiscussion md,tuser t,messageCategoryTable mct1 $countryTable1 , categoryBoardTable cbt where mct1.threadId = mt.msgId and mct1.categoryId = cbt.boardId and mct1.categoryId = '$categoryPref[$x]' $countryCheck1 and t.userid=mt.userId and mt.msgId=md.threadId and mt.fromOthers IN ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId in ($stickyDiscussionsCategory)  $orderByLinkedString limit $numRow)";
                    if($numRow<5) {
                       $queryCmd .= " UNION (select mt.*,md.description,t.displayname,t.firstname, t.lastname, t.avtarimageurl,mct1.categoryId,cbt.name $commentCountQuery ,tmp.entityCreationDate,tmp.linked from messageTable mt,messageDiscussion md,tuser t,messageCategoryTable mct1 $countryTable1 ,categoryBoardTable cbt,(select m1.threadId,max(creationDate) entityCreationDate,if(qdlt.linkedEntityId is null,0,1) linked from messageTable m1 left join questionDiscussionLinkingTable qdlt on m1.threadId = qdlt.linkedEntityId AND qdlt.STATUS =  'accepted' AND qdlt.TYPE = ('$entityType'),messageCategoryTable mc1 $countryTable1 where m1.threadId not in ($stickyDiscussionsCategory) and m1.fromOthers= ('$entityType') and m1.status IN ('live','closed') and parentId!=0 and m1.threadId=mc1.threadId and mc1.categoryId='$categoryPref[$x]' $countryCheckTmp group by m1.threadId order by linked asc, max(creationDate) desc limit $numRes) tmp where mct1.threadId = mt.msgId and mct1.categoryId = cbt.boardId and mct1.categoryId = '$categoryPref[$x]' $countryCheck1  and t.userid=mt.userId and mt.msgId=md.threadId and mt.fromOthers = ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId  = tmp.threadId and mt.parentId = mt.threadId)";
                    } 
                }
                else {
                    $queryCmd .= "(select mt.*,md.description,t.displayname,t.firstname, t.lastname, t.avtarimageurl,mct1.categoryId,cbt.name $commentCountQuery ,tmp.entityCreationDate,tmp.linked from messageTable mt,messageDiscussion md,tuser t,messageCategoryTable mct1 $countryTable1 ,categoryBoardTable cbt,(select m1.threadId,max(creationDate) entityCreationDate,if(qdlt.linkedEntityId is null,0,1) linked from messageTable m1 left join questionDiscussionLinkingTable qdlt on m1.threadId = qdlt.linkedEntityId AND qdlt.STATUS =  'accepted' AND qdlt.TYPE = ('$entityType'),messageCategoryTable mc1 $countryTable1 where  m1.fromOthers= ('$entityType') and m1.status IN ('live','closed') and parentId!=0 and m1.threadId=mc1.threadId and mc1.categoryId='$categoryPref[$x]' $countryCheckTmp group by m1.threadId order by linked asc, max(creationDate) desc limit 5) tmp where mct1.threadId = mt.msgId and mct1.categoryId = cbt.boardId and mct1.categoryId = '$categoryPref[$x]' $countryCheck1  and t.userid=mt.userId and mt.msgId=md.threadId and mt.fromOthers = ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId  = tmp.threadId and mt.parentId = mt.threadId)";
               }
                
  
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result_array() as $rowTemp) {
		    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],$entityType,$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
		    if(($categoryId=='All' || $categoryId == '' || $categoryId=='1')) {
			$catId = $rowTemp['categoryId'];
			$rowTemp['totalCount'] = $countArray[$catId];
		    }
		    $mainAnswerIdCsv .= ($mainAnswerIdCsv=='')?$rowTemp['msgId']:",".$rowTemp['msgId'];
		    $userIdCsv .= ($userIdCsv=='')?$rowTemp['userId']:",".$rowTemp['userId'];
		    $msgData[$i] = $rowTemp;
		    $i++;
		}
		$queryCmd = '';
            }

        }
        //In case, we have to get the data for a specific category/country
        else {
            $categoryCheckmct1 ='';
            $selectCategory = '';
            $categoryCheckmc1 ='';
            $havingMisc ='';
            if($categoryId != 0)
            {
                $categoryCheckmct1 = " and mct1.categoryId IN ('$categoryId') ";
                $categoryCheckmc1 = " and mc1.categoryId IN ('$categoryId') ";
            }
            if($categoryId == 0)
            {
                $selectCategory = " mct1.categoryId, ";
                $groupByHavingMisc = " group by mct1.threadId having count(mct1.threadId) =1 or mct1.categoryId = 0";
            }



         $commentCountQuery = ",(select count(*) from messageTable mt1 where mt1.mainAnswerId=mt.msgId and status IN ('live','closed')) commentCount";
	    $s=0;
	    $tmp1=''; 
	    //$queryCheck = "select distinct stickythreadId from stickyDiscussionAndAnnoucementTable where `type`='".$entityType."' and `status`='live' and categoryId='".$categoryId."'";
            $queryCheck = "select distinct stickythreadId from stickyDiscussionAndAnnoucementTable sd, messageTable mt where `type`=? and sd.status='live' and categoryId=? and mt.status IN ('live','closed') and sd.stickythreadId = mt.threadId and mt.parentId=mt.threadId ";
            $query = $dbHandle->query($queryCheck, array($entityType,$categoryId));
            $numRows = $query->num_rows();
            if($categoryId != 0)
            {
                $limitClause = "limit ".$start.",".$numRows;
            }
	    foreach ($query->result_array() as $rowTemp) { 
                if($s==0){ $tmp1 = "'".$rowTemp['stickythreadId']."'";}else{ $tmp1 .= ",'".$rowTemp['stickythreadId']."'";}
                $s++;
		} 
	        $str1=''; 
                if($tmp1!='')
                {
                    $str1 = "and m1.threadId not in ($tmp1)";
                    $str2 = "and m1.threadId in ($tmp1)";
                }
		if($start==5) {
                    $start=5-$numRows;
                    $queryCmd = "select  SQL_CALC_FOUND_ROWS mt.*, mct1.categoryId, md.description,t.displayname,t.firstname, t.lastname, t.avtarimageurl $commentCountQuery ,tmp.entityCreationDate,tmp.linked from messageTable mt,messageDiscussion md, messageCountryTable mct2 ,tuser t,messageCategoryTable mct1,(select m1.threadId,max(creationDate) entityCreationDate,if(qdlt.linkedEntityId is null,0,1) linked from messageTable m1 left join questionDiscussionLinkingTable qdlt on m1.threadId = qdlt.linkedEntityId AND qdlt.STATUS =  'accepted' AND qdlt.TYPE = ('$entityType'),messageCategoryTable mc1 ,messageCountryTable mct2 where  mct2.threadId=m1.msgId and mct2.countryId IN ('$countryId') and  m1.fromOthers=('$entityType')  $str1 and m1.status IN ('live','closed') and parentId!=0 and m1.threadId=mc1.threadId ".$categoryCheckmc1." group by m1.threadId order by linked asc, max(creationDate) desc limit $start,$count) tmp where mct1.threadId = mt.msgId ".$categoryCheckmct1." and t.userid=mt.userId and mt.msgId=md.threadId and mct2.threadId=mt.msgId and mct2.countryId IN ('$countryId') and mt.fromOthers = ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId  = tmp.threadId and mt.parentId = mt.threadId".$groupByHavingMisc;
                }else
                {
                    $queryCount="select".$selectCategory." mt.msgId from messageTable mt,messageCategoryTable mct1, messageCountryTable mct2, messageDiscussion md where mct1.threadId = mt.msgId ".$categoryCheckmct1." and mct2.threadId=mt.msgId and mct2.countryId IN ('$countryId') and mt.fromOthers IN ('$entityType') and mt.status IN ('live','closed') and parentId!=0 and mt.mainAnswerId=0 and mt.msgId = md.threadId".$groupByHavingMisc;
                    
                    $query = $dbHandle->query($queryCount);
                    $totalRowsFinal = $query->num_rows();

                    if($categoryId != 0){
                    $queryCmd = "  (select  mt.*,md.description,t.displayname,t.firstname, t.lastname, t.avtarimageurl,mct1.categoryId $commentCountQuery ,tmp.entityCreationDate,tmp.linked from messageTable mt,messageDiscussion md, messageCountryTable mct2 ,tuser t,messageCategoryTable mct1,(select m1.threadId,max(creationDate) entityCreationDate,if(qdlt.linkedEntityId is null,0,1) linked from messageTable m1 left join questionDiscussionLinkingTable qdlt on m1.threadId = qdlt.linkedEntityId AND qdlt.STATUS =  'accepted' AND qdlt.TYPE = ('$entityType'),messageCategoryTable mc1 where m1.fromOthers=('$entityType')  $str2 and m1.status IN ('live','closed') and parentId!=0 and m1.threadId=mc1.threadId and mc1.categoryId IN ('$categoryId') group by m1.threadId order by linked asc, max(creationDate) desc limit $start,$numRows) tmp where mct1.threadId = mt.msgId and mct1.categoryId IN ('$categoryId') and t.userid=mt.userId and mt.msgId=md.threadId and mct2.threadId=mt.msgId and mct2.countryId IN ('$countryId') and mt.fromOthers = ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId  = tmp.threadId and mt.parentId = mt.threadId)";
                         
                    $queryCmd .= " union (select  mt.*,md.description,t.displayname,t.firstname, t.lastname, t.avtarimageurl,mct1.categoryId $commentCountQuery ,tmp.entityCreationDate,tmp.linked from messageTable mt,messageDiscussion md, messageCountryTable mct2 ,tuser t,messageCategoryTable mct1,(select m1.threadId,max(creationDate) entityCreationDate,if(qdlt.linkedEntityId is null,0,1) linked from messageTable m1 left join questionDiscussionLinkingTable qdlt on m1.threadId = qdlt.linkedEntityId AND qdlt.STATUS =  'accepted' AND qdlt.TYPE = ('$entityType'),messageCategoryTable mc1 ,messageCountryTable mct2 where  mct2.threadId=m1.msgId and mct2.countryId IN ('$countryId') and  m1.fromOthers= ('$entityType')  $str1 and m1.status IN ('live','closed') and parentId!=0 and m1.threadId=mc1.threadId and mc1.categoryId IN ('$categoryId') group by m1.threadId order by linked asc, max(creationDate) desc limit $start,$count) tmp where mct1.threadId = mt.msgId and mct1.categoryId IN ('$categoryId') and t.userid=mt.userId and mt.msgId=md.threadId and mct2.threadId=mt.msgId and mct2.countryId IN ('$countryId') and mt.fromOthers = ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId  = tmp.threadId and mt.parentId = mt.threadId)";
                }
                else{
                $queryCmd = "select mct1.categoryId, mt.*,md.description, t.firstname, t.lastname, t.displayname,t.avtarimageurl $commentCountQuery ,tmp.entityCreationDate,tmp.linked from messageTable mt,messageDiscussion md, messageCountryTable mct2 ,tuser t,messageCategoryTable mct1,(select mc1.categoryId,m1.threadId,max(creationDate) entityCreationDate,if(qdlt.linkedEntityId is null,0,1) linked from messageTable m1 left join questionDiscussionLinkingTable qdlt on m1.threadId = qdlt.linkedEntityId AND qdlt.STATUS =  'accepted' AND qdlt.TYPE = ('$entityType'),messageCategoryTable mc1 ,messageCountryTable mct2 where  mct2.threadId=m1.msgId and mct2.countryId IN (".$countryId.") and m1.fromOthers= ('$entityType') and m1.status IN ('live','closed') and parentId!=0 and m1.threadId=mc1.threadId  group by m1.threadId having (count(mc1.threadId)=1 or mc1.categoryId=0) order by linked asc, max(creationDate) desc limit $start,$count) tmp where mct1.threadId = mt.msgId and t.userid=mt.userId and mt.msgId=md.threadId and mct2.threadId=mt.msgId and mct2.countryId IN ('$countryId') and mt.fromOthers = ('$entityType') and mt.status IN ('live','closed') and mt.parentId!=0 and mt.threadId  = tmp.threadId and mt.parentId = mt.threadId";
            }

               }

		$query = $dbHandle->query($queryCmd);
		foreach ($query->result_array() as $rowTemp) {
		    $rowTemp['url'] = getSeoUrl($rowTemp['threadId'],$entityType,$rowTemp['msgTxt'],'','',$rowTemp['creationDate']);
		    if(($categoryId=='All' || $categoryId == '' || $categoryId=='1')) {
			$catId = $rowTemp['categoryId'];
			$rowTemp['totalCount'] = $countArray[$catId];
		    }
		    $mainAnswerIdCsv .= ($mainAnswerIdCsv=='')?$rowTemp['msgId']:",".$rowTemp['msgId'];
                    $threadIdCsv .= ($threadIdCsv=='')?$rowTemp['threadId']:",".$rowTemp['threadId'];
		    $userIdCsv .= ($userIdCsv=='')?$rowTemp['userId']:",".$rowTemp['userId'];
		    $msgData[$i] = $rowTemp;
		    $i++;
		}
                
        }


        $totalRows = 0;
        if(!($categoryId=='All' || $categoryId == '' || $categoryId=='1')) {
            /*$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
            $query = $dbHandle->query($queryCmd);
            $totalRows = 0;
            foreach ($query->result() as $row) {
                $totalRows = $row->totalRows;
            }*/
            //$totalRows = $query->num_rows();
        }
        //Get the last comment for each of the Post
        $commentData = array();
        if($mainAnswerIdCsv!='') {
	    //Rewrite the query to improve its performance...
            //$queryCmd = "select t1.userid userId,t1.displayname,t1.email,t1.avtarimageurl, m1.msgTxt,m1.msgId,m1.creationDate,m1.threadId, m1.mainAnswerId from tuser t1, messageTable m1 LEFT JOIN messageTable m2 ON (m1.mainAnswerId=m2.mainAnswerId and m1.creationDate<m2.creationDate) where m2.creationDate IS NULL and m1.userId=t1.userid and m1.mainAnswerId IN ($mainAnswerIdCsv) and m1.status IN ('live','closed') order by creationDate";
	    $queryCmd = "select t1.userid userId,t1.displayname,t1.firstname, t1.lastname, t1.email,t1.avtarimageurl, m1.msgTxt,m1.msgId,m1.creationDate,m1.threadId, m1.mainAnswerId from tuser t1, messageTable m1 INNER JOIN ( select max(m2.msgId) maxId from messageTable m2 where m2.parentId IN ($mainAnswerIdCsv) and m2.status IN ('live','closed') group by m2.mainAnswerId ) tmp on m1.msgId = tmp.maxId where  m1.userId=t1.userid";
            $query = $dbHandle->query($queryCmd);
            $i=0;
            foreach ($query->result_array() as $rowTemp) {
                $userIdCsv .= ($userIdCsv=='')?$rowTemp['userId']:",".$rowTemp['userId'];
                //array_push($commentData,array($rowTemp,'struct'));
                $commentData[$i] = $rowTemp;
                $i++;
            }
        }

        $this->load->model('QnAModel');
        $msgArrayLevelVcard = array();
        $msgArrayLevelVcard = $this->QnAModel->getLevelAndVCardStausForUser($dbHandle,$userIdCsv,true,true,true);
        $msgArrayLevelVcard = is_array($msgArrayLevelVcard)?$msgArrayLevelVcard:array();

        $mainArr = array();
        //Modified for Shiksha performance task on 8 March
		/*array_push($mainArr,array(
				array(
					'msgData'=>array($msgData,'struct'),
					'commentData'=>array($commentData,'struct'),
					'levelVCard'=>array($msgArrayLevelVcard,'struct'),
					'totalTopicCount'=>array($totalRows,'string')
				),'struct')
		);
		$response = array($mainArr,'struct');*/
        $queryCmd = "(select distinct stickythreadId,status from stickyDiscussionAndAnnoucementTable where `type`=?)";
        $query = $dbHandle->query($queryCmd, array($entityType));
        $j = 0;
        foreach ($query->result_array() as $rowTemp) {
            $stickyArray[] = $rowTemp;
            $j++;
        }
        $mainArr[0]['stickyArray'] = $stickyArray;
        $mainArr[0]['msgData'] = $msgData;
        $mainArr[0]['commentData'] = $commentData;
        $mainArr[0]['levelVCard'] = $msgArrayLevelVcard;
        $mainArr[0]['totalTopicCount'] = $totalRowsFinal;
        //$mainArr[0]['stickyArray'] = $stickyArray;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    //Functions Start for Edit Post functionality
    /**
     *   Function to get the Details of a topic to be displayed in the Edit form
     *   Parameters: EntityType (question, discussion etc), Id and userId
     *   Output: Title, description, Category, Country of the topic
     **/
    function getTopicDetailForEdit($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $entityType=$parameters['1'];
        $topicId=$parameters['2'];
        $userId=$parameters['3'];
	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle($topicId);
        if($entityType=='question'||$entityType=='user') {
            $queryCmd = "select m1.msgTxt,m1.threadId, m1.mainAnswerId,m1.userId from messageTable m1 where m1.threadId=? and m1.status IN ('live','closed') order by creationDate";
        }
        else {
            $queryCmd = "select m1.msgTxt,m1.threadId, m1.mainAnswerId,m1.userId,m2.description from messageTable m1, messageDiscussion m2 where m1.threadId=? and m1.status IN ('live','closed') and m1.msgId=m2.threadId and m1.parentId!=0 and m1.fromOthers='$entityType' order by creationDate";
        }
        $query = $dbHandle->query($queryCmd, array($topicId));
        $topicData = array();
        foreach ($query->result_array() as $rowTemp) {
            array_push($topicData,array($rowTemp,'struct'));
        }
        $queryCmd = "select categoryId, countryId from messageCategoryTable m1, messageCountryTable m2 where m1.threadId=? and m2.threadId=? and m1.categoryId!=1 and m2.countryId!=1";
        $query = $dbHandle->query($queryCmd, array($topicId,$topicId));
        $CatCounData = array();
        foreach ($query->result_array() as $rowTemp) {
            array_push($CatCounData,array($rowTemp,'struct'));
        }
        $queryCmd = "SELECT count(*) total FROM messageDiscussion where threadId = ?";
        $query = $dbHandle->query($queryCmd, array($topicId));
        $count=0;
        foreach($query->result_array() as $row) {
            $count = $row['total'];
        }

        if($count==1) {
            $description = array();
            $queryCmd = "SELECT description FROM messageDiscussion where threadId = ?";
            $query = $dbHandle->query($queryCmd, array($topicId));
            foreach($query->result_array() as $row) {
                array_push($description,array($row,'struct'));
            //$description[]=$row['description'];
            }
        }
        //array_push($description,array($description,'struct'));
        $mainArr = array();
        if(isset($description)) {
            array_push($mainArr,array(
                array(
                'topicData'=>array($topicData,'struct'),
                'catCounData'=>array($CatCounData,'struct'),
                'description'=>array($description,'struct')
                ),'struct')
            );
        }else {
            array_push($mainArr,array(
                array(
                'topicData'=>array($topicData,'struct'),
                'catCounData'=>array($CatCounData,'struct')
                ),'struct')
            );
        }
        $response = array($mainArr,'struct');
        return $this->xmlrpc->send_response($response);
    }

    /**
     *   Function to Edit the Post/question
     *   Parameters: EntityType (question, discussion etc), Id, userId, Title text, description text, categoryIds, country Id, display name
     *   Output: Success/Failure message
     **/
    function updateCafePost($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $msgId=$parameters['1'];
        $user_id=$parameters['2'];
        $mesgTxt=$parameters['3'];
        $categoryCSV=$parameters['4'];
        $requestIP=$parameters['5'];
        $fromOthers=$parameters['6'];
        $listingTypeId=$parameters['7'];
        $listingType=$parameters['8'];
        $toBeinddex = $parameters['9'];
        $displayName=$parameters['10'];
        $countryId=$parameters['11'];
        $extraParamCsv=$parameters['12'];
        $questionMoveToIns=$parameters['13'];
        $courseId=$parameters['14'];
        $instId=$parameters['15'];
        $isPaid =$parameters['16'];
        $questionMoveToCafe = $parameters['17'];
	$source = $parameters['18'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $newEntityArray = array('discussion','announcement','review','eventAnA');
        $ansId = 0;

        //check if any topic is posted on this topic or not?
	$msgArray = array();
	$count=0;
       	$queryCmd="select msgCount,msgId,parentId,threadId from messageTable where threadId=? and status in ('live','closed')";
	error_log_shiksha( 'updateTopic query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($msgId));
       	foreach ($query->result() as $row) {
            if($row->parentId != 0) {
               	$count=$count+$row->msgCount;
       	       // $ansId = $row->msgId;
            }
       	    if($row->parentId == $row->threadId){
              	$ansId = $row->msgId;
       	    }
       	}

        /*if($count>0 && $source!='mobileApp') {
            $response = array(array('Result'=>'You can not edit this topic'),'struct');
            return $this->xmlrpc->send_response($response);
        }else {*/
        //Update the main entity ie. question in case of AnA only for the question entity
            if($fromOthers == 'user') {
                if($questionMoveToIns=='on'){
                           $data =array('listingTypeId'=>$instId,'listingType'=>'institute', 'requestIP'=>$requestIP      );
                           $dbHandle->where(array('threadId' => $msgId ));
                           $dbHandle->update($this->messageboardconfig->messageTable,$data);
   
                           $data =array('description'=>'');
                           $dbHandle->where(array('threadId' => $msgId ));
                           $dbHandle->update($this->messageboardconfig->messageDiscussion,$data);
   
                           $queryCmd = "select tu.mobile,tu.email,tu.userId,tu.displayName as dName from tuser tu inner join messageTable mt on (mt.userId=tu.userId) where msgId = ?";
                           $query = $dbHandle->query($queryCmd,array($msgId));
                           $row = $query->row();
                           $mobile = $row->mobile;
                           $email = $row->email;
                           $userId = $row->userId;
                           $dName = $row->dName;
   
                           $queryCmd = "insert into questions_listing_response (courseId,instituteId,messageId,creationTime,userId) values (?,?,?,now(),?)";
                           $query = $dbHandle->query($queryCmd,array($courseId,$instId,$msgId,$userId));
   
                           if($isPaid=='1'){
                                   $queryCmd = "insert into tempLMSTable (userId,displayName,contact_cell,listing_type,listing_type_id,submit_date,action,email) values (?,?,?,?,?,now(),?,?)";
                                   $query = $dbHandle->query($queryCmd,array($userId,$dName,$mobile,'course',$instId,'Asked_Question_On_Listing',$email));
                           }
                           $extraParamCsv = '';
                    }
                    if($questionMoveToCafe=='on'){
                        $data =array('listingTypeId'=>'0','listingType'=>'NULL', 'requestIP'=>$requestIP      );
                        $dbHandle->where(array('threadId' => $msgId ));
                        $dbHandle->update($this->messageboardconfig->messageTable,$data);

                        $queryCmd = "select tu.mobile,tu.email,tu.userId,tu.displayName as dName from tuser tu inner join messageTable mt on (mt.userId=tu.userId) where msgId = ?";
                        $query = $dbHandle->query($queryCmd,array($msgId));
                        $row = $query->row();
                        $mobile = $row->mobile;
                        $email = $row->email;
                        $userId = $row->userId;
                        $dName = $row->dName;

                        $queryCmd = "select courseId,instituteId from questions_listing_response where userId=? and messageId=? and status=?";
                        $query = $dbHandle->query($queryCmd,array($userId,$msgId,'live'));
                        $row = $query->row();
                        $courseId = $row->courseId;
                        $instId = $row->instituteId;


                        $queryCmd = "update questions_listing_response set status='deleted' where courseId=? and instituteId=? and messageId=? and userId=?";
                        $query = $dbHandle->query($queryCmd,array($courseId,$instId,$msgId,$userId));

                        $queryCmd = "update tempLMSTable set action=? where userId=? and displayName=? and listing_type_id=? and email=? AND listing_subscription_type='paid'";
                        $query = $dbHandle->query($queryCmd,array('Viewed_Listing',$userId,$dName,$courseId,$email));
                }
                $data =array('msgTxt'=>htmlspecialchars($mesgTxt), 'requestIP'=>$requestIP	);
                $dbHandle->where(array('msgId' => $msgId ));
                $dbHandle->update($this->messageboardconfig->messageTable,$data);
            }
            //Update the main entity in case of discussion etc
            else if(in_array($fromOthers,$newEntityArray)) {
                    $data =array('msgTxt'=>htmlspecialchars($mesgTxt), 'requestIP'=>$requestIP	);
                    $dbHandle->where(array('msgId' => $ansId));
                    $dbHandle->update($this->messageboardconfig->messageTable,$data);

                    //Also, in case of discussion, announcements, update the extra piece of information in the DB
                    if($extraParamCsv!='') {
                    //$extraArray = explode(",", $extraParamCsv);
                        if($fromOthers == 'discussion' || $fromOthers == 'announcement') {
                        //$description = $extraArray[0];
                            $description = $extraParamCsv;
                            $data =array('description'=>htmlspecialchars($description));
                            $dbHandle->where(array('threadId' => $ansId));
                            $dbHandle->update($this->messageboardconfig->messageDiscussion,$data);
                        }
                    }
                }

            // update category table
            $this->updateCategoryTableEdit($categoryCSV,$msgId,$source);
            if(in_array($fromOthers,$newEntityArray)) {
                $this->updateCategoryTableEdit($categoryCSV,$ansId,$source);
            }
            //update country table
            $this->updateCountryTableEdit($countryId,$msgId);
            if(in_array($fromOthers,$newEntityArray)) {
                $this->updateCountryTableEdit($countryId,$ansId);
            }
            //update messageDiscussionTable in case of questions if and only if description exists there STARTS
            $queryCmd = "SELECT count(*) total FROM messageDiscussion WHERE threadId = ?";

            $query = $dbHandle->query($queryCmd, array($msgId));
            $count=0;
            foreach($query->result_array() as $row) {
                $count = $row['total'];
            }

            if($count==1) {
                $data =array( 'description'=>htmlspecialchars($extraParamCsv)  );
                $dbHandle->where( array( 'threadId' => $msgId ) );
                $dbHandle->update($this->messageboardconfig->messageDiscussion,$data);
            }

            //update messageDiscussionTable in case of questions if and only if description exists there ENDS
            
            //Add notification of APP in redis
                //$this->load->model('messageBoard/AnAModel');
                //$moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($user_id);
                
               // if($user_id == $moderatorUserId){
                    /*$this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
                    
                    if($fromOthers == 'user'){
                           $this->appNotification->addNotificationForAppInRedis('edit_question_by_moderator',$msgId,'question',$user_id); 
                    }else{
                            $this->appNotification->addNotificationForAppInRedis('edit_discussion_by_moderator',$msgId,'discussion',$user_id); 
                    }*/
                //}
                        

            $response = array(array('Result'=>'Topic Edited'),'struct');
            return $this->xmlrpc->send_response($response);
        //}
    }

    /**
     *   Function to Edit the Categories of the Post/question in the DB
     *   Parameters: CategoryId and msgId
     *   Output: None
     **/
    function updateCategoryTableEdit($categoryCSV,$msgId,$source="Desktop") {
    //connect DB
        $this->load->library('messageboardconfig');
	$dbHandle = $this->_loadDatabaseHandle('write');

        $categoryArray = array();
        $tempArray = explode(",", $categoryCSV);
        foreach($tempArray as $temp) {
            if($temp != '')
                array_push($categoryArray,$temp);
        }

	//Update the Sub-Category if not from Mobile App i.e. only in case of Web
	if($source!="mobileApp"  && $source != "mobilesiteapicall"){
        	$queryCmd = "update messageCategoryTable mct, categoryBoardTable cbt set mct.categoryId = '$categoryArray[0]' where mct.threadId = ? and mct.categoryId = cbt.boardId and cbt.parentId > 1";
	        $query = $dbHandle->query($queryCmd, array($msgId));
                //If no Sub cat is found, insert the sub-category in the Table
                if($dbHandle->affected_rows() <= 0){
                        $queryCmdCheck = "SELECT * FROM messageCategoryTable WHERE threadId = ? AND categoryId = ?";
                        $queryCheck = $dbHandle->query($queryCmdCheck, array($msgId,$categoryArray[0]));
			if($queryCheck->num_rows()==0){
	                        $queryCmd = "insert into messageCategoryTable (threadId,categoryId) values (?,?)";
        	                $query = $dbHandle->query($queryCmd, array($msgId,$categoryArray[0]));
			}
                }
	}
	
	//Updating Category Information
	if($source!="mobileApp" && $source != "mobilesiteapicall"){	//If the call is from Web, we will udpate the Cat info. But, if Cat info was not available, we need to insert it
        	$queryCmd = "update messageCategoryTable mct, categoryBoardTable cbt set mct.categoryId = (select cbt_sub.parentId from categoryBoardTable cbt_sub where cbt_sub.boardId= '$categoryArray[0]') where mct.threadId = ? and mct.categoryId = cbt.boardId and cbt.parentId = 1";
                $query = $dbHandle->query($queryCmd, array($msgId));
                //If no Category is found, insert the Category in the Table
                if($dbHandle->affected_rows() <= 0){
                        $queryCmdCheck = "SELECT * FROM messageCategoryTable WHERE threadId = ? AND categoryId = (select cbt_sub.parentId from categoryBoardTable cbt_sub where cbt_sub.boardId= ?)";
                        $queryCheck = $dbHandle->query($queryCmdCheck, array($msgId,$categoryArray[0]));
                        if($queryCheck->num_rows()==0){			
                        	$queryCmd = "insert into messageCategoryTable (threadId,categoryId) values (?,(select cbt_sub.parentId from categoryBoardTable cbt_sub where cbt_sub.boardId= '$categoryArray[0]'))";
                        	$query = $dbHandle->query($queryCmd, array($msgId));
			}
                }
	}
	else if($categoryArray[0] > 1){ //Only if CategoryId is greater than 1 will we update the categoryId
                $queryCmd = "update messageCategoryTable mct, categoryBoardTable cbt set mct.categoryId = '$categoryArray[0]' where mct.threadId = ? and mct.categoryId = cbt.boardId and cbt.parentId = 1";
                $query = $dbHandle->query($queryCmd, array($msgId));
                //If no Category is found, insert the Category in the Table
                if($dbHandle->affected_rows() <= 0){
                        $queryCmdCheck = "SELECT * FROM messageCategoryTable WHERE threadId = ? AND categoryId = ?";
                        $queryCheck = $dbHandle->query($queryCmdCheck, array($msgId,$categoryArray[0]));
                        if($queryCheck->num_rows()==0){
                        	$queryCmd = "insert into messageCategoryTable (threadId,categoryId) values (?,?)";
	                        $query = $dbHandle->query($queryCmd, array($msgId,$categoryArray[0]));
			}
                }
	}
    }

    /**
     *   Function to Edit the Country of the Post/question in the DB
     *   Parameters: CountryId and msgId
     *   Output: None
     **/
    function updateCountryTableEdit($countryId,$msgId) {
	//connect DB
	$dbHandle = $this->_loadDatabaseHandle('write');
        $this->load->library('messageboardconfig');
        $queryCmd = "Update messageCountryTable set countryId = $countryId where threadId = ? and countryId > 1";
        $query = $dbHandle->query($queryCmd, array($msgId));
    }
    //Functions End for Edit Post functionality

    function getCountCommentsToBeDisplayed($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $topicId=$parameters['1'];
        //connect DB
        $this->load->library('messageboardconfig');
	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle($topicId);
        $queryCmd = "select fromOthers from messageTable where msgId = ?";
        error_log_shiksha( 'getCountCommentsToBeDisplayed query cmd is ' . $queryCmd,'qna');
        $query = $dbHandle->query($queryCmd, array($topicId));
        $fromOthers = 'user';
        $count = 10;
        foreach ($query->result() as $row) {
            $fromOthers=$row->fromOthers;
        }
        if($fromOthers == 'discussion' || $fromOthers == 'announcement' || $fromOthers == 'review') {
            $count = 20;
        }
        $type = $fromOthers;
        if($type=='user'){
          $type = 'question';
        }
        $response = array(array('count'=>$count,'type'=>$type),'struct');
        return $this->xmlrpc->send_response($response);
    }

	/*
	function setFBSessionKey($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$sessionKey=$parameters['2'];
		//connect DB
       		$this->load->library('messageboardconfig');
		$dbConfig = array( 'hostname'=>'localhost');
		$this->messageboardconfig->getDbConfig($appID,$dbConfig);
		$dbHandle = $this->load->database($dbConfig,TRUE);
		if($dbHandle == ''){
			error_log_shiksha('setFBSessionKey can not create db handle','qna');
		}
		$queryCmd = "INSERT INTO `shiksha`.`messageTableFacebookSessionKey` (`userId` ,`sessionKey`)VALUES ('$userId', '$sessionKey');";
		$query = $dbHandle->query($queryCmd);
		$response = "1";
		return $this->xmlrpc->send_response($response);
	}

	function getFBSessionKey($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		//connect DB
       		$this->load->library('messageboardconfig');
		$dbConfig = array( 'hostname'=>'localhost');
		$this->messageboardconfig->getDbConfig($appID,$dbConfig);
		$dbHandle = $this->load->database($dbConfig,TRUE);
		if($dbHandle == ''){
			error_log_shiksha('getFBSessionKey can not create db handle','qna');
		}
		$queryCmd = "select sessionKey, creationDate from messageTableFacebookSessionKey where userId = ?";
		$query = $dbHandle->query($queryCmd, array($userId));
		$sessionData = array();
 		foreach ($query->result_array() as $rowTemp){
		      array_push($sessionData,array($rowTemp,'struct'));
		}
		$mainArr = array();
		array_push($mainArr,array(
				array(
					'sessionData'=>array($sessionData,'struct')
				),'struct')
		);
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);
	}
	*/

    function setFBWallLog($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $sessionKey=$parameters['2'];
        $content=$parameters['3'];
        $ipAddress=$parameters['4'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $this->load->library('messageboardconfig');
        $content = str_replace("'", "", $content);
        $queryCmd = "INSERT INTO `shiksha`.`messageTableFacebookLog` (`userId` ,`sessionKey`,`content`,`ipAddress`)VALUES (?,?,?,?);";
        $query = $dbHandle->query($queryCmd,array($userId,$sessionKey,$content,$ipAddress));
        $response = "1";
        return $this->xmlrpc->send_response($response);
    }

    function getDataForFacebook($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$dbHandle->escape($parameters['1']);
        $type=$parameters['2'];
        $parentId=$dbHandle->escape($parameters['3']);
        $mainAnswerId=$dbHandle->escape($parameters['4']);
        $this->load->library('messageboardconfig');
        if($type=="Post") { //This could be for a question, discussion or announcement
            $queryCmd = "select fromOthers from messageTable where userId = ".$userId." and parentId = 0 and status IN ('live','closed') and fromOthers IN ('user','discussion','announcement') order by creationDate desc limit 1";
            $query = $dbHandle->query($queryCmd);
            $fromOthers = '';
            foreach ($query->result() as $row) {
                $fromOthers=$row->fromOthers;
            }
            if($fromOthers == 'discussion' || $fromOthers == 'announcement' || $fromOthers == 'review') {
                $queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,mct.categoryId,ct.name,'post' type, ifnull((select md.description from messageDiscussion md where m1.msgId=md.threadId),'') postDesc from messageTable m1,messageCategoryTable mct, categoryBoardTable ct where m1.parentId=m1.threadId and m1.status IN ('live','closed') and m1.userId=".$userId." and m1.fromOthers IN ('discussion','announcement') and mct.threadId = m1.threadId and ct.boardId = mct.categoryId and ct.parentId = 1 order by m1.creationDate desc limit 1";
            }
            else if($fromOthers == 'user') {
                    $queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,mct.categoryId,ct.name,'question' type from messageTable m1,messageCategoryTable mct, categoryBoardTable ct where m1.parentId=0 and m1.status IN ('live','closed') and m1.userId=".$userId." and m1.fromOthers = 'user' and mct.threadId = m1.threadId and ct.boardId = mct.categoryId and ct.parentId = 1 order by m1.creationDate desc limit 1";
                }
        }
        else if($type=="Answer") {
                $queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,mct.categoryId,ct.name,'answer' type, (select msgTxt from messageTable m2 where m2.msgId = m1.threadId and status IN ('live','closed')) questionText, (SELECT Level FROM userPointLevelByModule upl, userpointsystembymodule ups WHERE upl.module = ups.moduleName and upl.module = 'AnA' and ups.userId = ".$userId." and ups.userPointValueByModule > upl.minLimit LIMIT 1) level from messageTable m1,messageCategoryTable mct, categoryBoardTable ct where m1.parentId=m1.threadId and m1.parentId = ".$parentId." and m1.status IN ('live','closed') and m1.userId=$userId and m1.fromOthers IN ('user','discussion','announcement') and mct.threadId = m1.threadId and ct.boardId = mct.categoryId and ct.parentId = 1 order by m1.creationDate desc limit 1";
            }
            else if($type=="Comment") { //This could be a comment on Answer, discussion comment or an announcement comment
                    $queryCmd = "select fromOthers from messageTable where userId = ".$userId." and mainAnswerId = ".$mainAnswerId." and status IN ('live','closed') and fromOthers IN ('user','discussion','announcement')  order by creationDate desc limit 1";
                    $query = $dbHandle->query($queryCmd);
                    $fromOthers = '';
                    foreach ($query->result() as $row) {
                        $fromOthers=$row->fromOthers;
                    }
                    $catIdQuery = " ,(select categoryId from messageCategoryTable mct LEFT JOIN categoryBoardTable cbt1 ON cbt1.boardId = mct.categoryId where mct.threadId = m1.threadId and cbt1.parentId = 1 ) categoryId ";
                    $catNameQuery = " ,(select name from categoryBoardTable ct, messageCategoryTable mct where mct.threadId = m1.threadId and ct.boardId = mct.categoryId and ct.parent = 1 ) name ";
                    if($fromOthers == 'discussion' || $fromOthers == 'announcement' || $fromOthers == 'review') {
                        $queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,'postcomment' type, (select msgTxt from messageTable m2 where m2.msgId = m1.mainAnswerId) postText $catIdQuery  $catNameQuery from messageTable m1 where m1.mainAnswerId=".$mainAnswerId." and m1.status IN ('live','closed') and m1.userId=".$userId." and m1.fromOthers IN ('discussion','announcement') order by m1.creationDate desc limit 1";
                    }
                    else if($fromOthers == 'user') {
                            $queryCmd = "select m1.msgTxt,m1.fromOthers,m1.threadId,'comment' type, (select msgTxt from messageTable m2 where m2.msgId = m1.threadId) questionText $catIdQuery  $catNameQuery from messageTable m1 where m1.mainAnswerId=".$mainAnswerId." and m1.status IN ('live','closed') and m1.userId=".$userId." and m1.fromOthers = 'user' order by m1.creationDate desc limit 1";
                        }
                }

        $fbData = array();
        $query = $dbHandle->query($queryCmd);
        foreach ($query->result_array() as $rowTemp)
            array_push($fbData,array($rowTemp,'struct'));

        $response = array($fbData,'struct');
        return $this->xmlrpc->send_response($response);
    }

    //Added by Ankur for Homepage-Rehash
    function getHomepageCafeWall($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $userId=$parameters['2'];
        $start=$parameters['3'];
        $count=$parameters['4'];
        $countryId=$parameters['5'];

	//$dbHandle = $this->_loadDatabaseHandle();
        $dbHandle = $this->returnDBHandle();
        //Get the latest threadId which fits into the wall
        $result = '';
        $date = date("Y-m-d");
        $i=1;
        do {
            $date = strtotime("-".$i." days",strtotime($date));
            $date = date ( 'Y-m-j' , $date );
            $queryCmd = "select res.* from (select * from ((select m1.creationDate sortingTime,m1.msgId,m1.threadId,m1.mainAnswerId, 'message' type,m1.threadId sortingKey,m1.fromOthers product from messageTable m1 where m1.fromOthers IN ('user','discussion','review','announcement') and m1.status in ('live','closed') and m1.creationDate > '".$date."' and m1.msgTxt != 'dummy' and m1.listingTypeId = 0 and (select status from messageTable pp where pp.msgId = m1.threadId) IN ('live','closed') and (if((m1.mainAnswerId<=0),true,((select status from messageTable mtbp where mtbp.msgId = m1.mainAnswerId) IN ('live','closed'))))) UNION (select d1.digTime sortingTime, d1.productId,d1.digFlag,d1.userId, 'rating' type,mta.threadId sortingKey, mta.fromOthers product from digUpUserMap d1, messageTable mta where d1.product='qna' and d1.digFlag = 1 and d1.digUpStatus='live' and d1.digTime > '".$date."' and d1.productId = mta.msgId and mta.status IN ('live','closed') and mta.fromOthers IN ('user') and mta.listingTypeId = 0 and (select status from messageTable pp1 where pp1.msgId = mta.threadId) IN ('live','closed')) UNION (select b1.creation_time sortingTime,b1.threadId,b1.bestAnsId,'0' Id,'bestanswer' type,b1.threadId sortingKey,mt.fromOthers product from messageTableBestAnsMap b1, messageTable mt where b1.creation_time > '".$date."' and (b1.threadId = mt.threadId) and (b1.bestAnsId = mt.msgId) and mt.status IN ('live','closed') and mt.listingTypeId = 0 and (select status from messageTable pp2 where pp2.msgId = mt.threadId) IN ('live','closed'))) result order by result.sortingTime desc) res , messageCategoryTable m2, messageCountryTable m3 where res.sortingKey = m2.threadId and m2.categoryId in (".$categoryId.") and res.sortingKey = m3.threadId and m3.countryId in (".$countryId.") group by res.sortingKey order by res.sortingTime DESC limit 0,$count";
            $result = $dbHandle->query($queryCmd);
            $i+=7;
            if($i>30) break;
        }while(count($result->result_array())<$count);

        //Now, get the data for these Id's based on their type and product
        $wallData = $this->getDataForWall($appId, "true", $userId, $result);
        $mainAnswerUserIdCsv = $wallData[0]['mainAnswerUserIdCsv'];
        $threadIdList = $wallData[0]['threadIdList'];
        $returnArr = $wallData[0]['returnArr'];
        
        $this->load->model('QnAModel');
        $msgArrayLevelVcard = array();
        $msgArrayLevelVcard = $this->QnAModel->getLevelAndVCardStausForUser($dbHandle,$mainAnswerUserIdCsv,true,true,true);
        $msgArrayLevelVcard = is_array($msgArrayLevelVcard)?$msgArrayLevelVcard:array();
        $msgArrayCatCountry = array();
        $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdList,true,true,true);
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
        
        $response = array($returnArr,'struct');

        $mainArr = array();
        $mainArr[0]['results'] = $returnArr;
        $mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
        $mainArr[0]['levelVCard'] = $msgArrayLevelVcard;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function checkBestAnswer($request) {
        $parameters = $request->output_parameters();
        $topicId = $parameters['0'];
        $flag = 0;
	$dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "SELECT count(*) as total FROM messageTableBestAnsMap WHERE threadId = ?";
        $query = $dbHandle->query($queryCmd, array($topicId));
        foreach($query->result_array() as $row) {

            $count = $row['total'];
            $flag = ($count==0)?0:1;
        }

        $response = array($flag,'string');
        return $this->xmlrpc->send_response($response);
    }

	/**************************
	Name: getMasterListSitemap
	Purpose: To get the list of Questions and their URL's from the Masterlist
	Parameters: AppId and Number of questions required
	Return: Encoded array of questions data
	**************************/
    function getMasterListSitemap($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $count = $parameters['1'];
	$dbHandle = $this->_loadDatabaseHandle();

        $messageDiscussionQuery = ",ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD ";
        $queryCmd = "select m1.msgId, m1.msgTxt, m1.creationDate, m1.threadId, qm.modifiedDate $messageDiscussionQuery from messageTable m1, qnaMasterQuestionTable qm where m1.msgId=qm.msgId and m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 and qm.status = 'live' order by m1.creationDate desc LIMIT $count";
        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        $i=0;
        foreach($query->result_array() as $row) {
        //Create the URL of the question
            if($this->seo_update($row['creationDate'])) {
                $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
            }
            else {
                if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD'])) {
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['descriptionD'],'','',$row['creationDate']);
                }
                else {
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
                }
            }
            $msgArray[$i] = $row;
            $i++;
        }
        $mainArr = array();
        $mainArr[0]['results'] = $msgArray;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getUserInNetwork($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $userId = $parameters['1'];
	$dbHandle = $this->_loadDatabaseHandle();
        // $queryCmd = "SELECT DISTINCT displayname from tuser t1, followUser WHERE (t1.userid = followingUserId and followedUserId = ?) OR (t1.userid = followedUserId and followingUserId = ?)";
	$queryCmd = "SELECT DISTINCT displayname from tuser t1,  tuserFollowTable t2 WHERE (t1.userid = t2.userId and t2.entityId = ? and t2.status = 'follow') OR (t1.userid = t2.entityId and t2.userId = ? and t2.status = 'follow')";
        error_log($queryCmd);
        $query = $dbHandle->query($queryCmd, array($userId,$userId));
        $msgArray = array();
        foreach($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getMentionMailersData($request) {
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $nameString = $parameters['1'];
        $msgId = $parameters['2'];
        $userId = $parameters['3'];
        $type = $parameters['4'];
        $nameString = trim($nameString,',');
        $queryCmd = "select email,displayname,userid from tuser where displayname IN ($nameString) ";
        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        foreach($query->result_array() as $row) {
            array_push($msgArray,array($row,'struct'));
        }

        $queryCmd = "select displayname from tuser where userid = ?";
        $query = $dbHandle->query($queryCmd,array($userId));
        $row = $query->row();
        $displayname = $row->displayname;

		 //Now get the URL of the main entity
		 $url = '';
		 if($type=='discussion' || $type=='discussionComment'){
		      $queryCmd = "select msgTxt, threadId, creationDate, '' descriptionD from messageTable where threadId IN (select threadId from messageTable where msgId = $msgId) and fromOthers = 'discussion' and status IN ('live','closed') and mainAnswerId = 0";
                      $displayString = 'discussion';
		 }
		 else{
		      $queryCmdT = "select threadId from messageTable where msgId = ?";
		      $queryT = $dbHandle->query($queryCmdT,array($msgId));
		      $rowT = $queryT->row();
		      $threadIdT = $rowT->threadId;
		      $queryCmd = "select m1.msgTxt, m1.threadId, m1.creationDate,ifnull((select description from messageDiscussion where threadId = m1.msgId),'') descriptionD from messageTable m1 where m1.msgId IN ('$threadIdT') and m1.fromOthers = 'user' and m1.status IN ('live','closed') and m1.mainAnswerId = -1";
                      $displayString = 'question';
		 }

        $query = $dbHandle->query($queryCmd);
        $row = $query->row();
        $msgTxt = $row->msgTxt;
        $id = $row->threadId;
        $creationDate = $row->creationDate;
        $descriptionD = $row->descriptionD;
        if($this->check_legacy_seo_update($creationDate,$descriptionD))
            $url = getSeoUrl($id,$displayString,$descriptionD,'','',$creationDate);
        else
            $url = getSeoUrl($id,$displayString,$msgTxt,'','',$creationDate);

        $mainArr = array();
        $mainArr[0]['emails'] = $msgArray;
        $mainArr[0]['displayname'] = $displayname;
        $mainArr[0]['URL'] = $url;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

	/***********************
	Function: changeTextForAtMention
	Purpose: Remove special characters at the places where @Mention is used
	Input: Text of the answer/comment
	Output: Text for answer/comment with special characters removed
	************************/
    function changeTextForAtMention($msgTxt) {
        $newMsgTxt = '';
        do {
            if(strpos($msgTxt,('@||')) !== false || strpos($msgTxt,('@||')) === 0) {
                $newMsgTxt .= substr($msgTxt , 0 ,strpos($msgTxt,('@||'))) . '@';
                $msgTxt = substr($msgTxt, strpos($msgTxt,('@||'))+3);
                $newMsgTxt .= substr( $msgTxt, 0, strpos($msgTxt,('||') ));
                $msgTxt = substr($msgTxt, strpos($msgTxt,('||'))+2);
            }
        }while(strpos($msgTxt,('@||')) !== false);
        $newMsgTxt .= $msgTxt;
        return $newMsgTxt;
    }

    function linkQuestionResult($request) {
        $parameters = $request->output_parameters();
        $appId = 1;
        $topicId = $parameters[0];
	$dbHandle = $this->_loadDatabaseHandle();
        $i=0;
        $msgArr = array();
        $finalArray = array();
        $queryCmd = "select DISTINCT linkingEntityId from questionDiscussionLinkingTable where linkedEntityId=?  and status='accepted' order by createdDate desc";
        $query = $dbHandle->query($queryCmd, array($topicId));
        foreach($query->result_array() as $row) {
            $queryCmd = "select msgTxt,msgId,fromOthers from messageTable where msgId=?";
            $query = $dbHandle->query($queryCmd, array($row['linkingEntityId']));
            $res = $query->result_array();
            if($res[0]['fromOthers']=='user')
             $res[0]['fromOthers'] = 'question';
            $msgArr[title][$i][U]=getSeoUrl($row['linkingEntityId'],$res[0]['fromOthers'],$res[0]['msgTxt']);
            $msgArr[title][$i][S]= $res[0]['msgTxt'];
            //$msgArr[title][$i][S]= $res[0]['msgTxt'];
            $i++;
        //array_push($msgArr,array($res,'struct'));
        }
        $responseString = base64_encode(gzcompress(json_encode($msgArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getRelatedSearchDiscussion($request) {
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appId = 1;
        $topicId = $dbHandle->escape($parameters[0]);
        $str = '';
        if($topicId!=''){
            $str = "and mt.threadId!=".$topicId;
        }
        $search = array ('in,the,what,where,who,how,was,were');
        $discussionTxt= str_replace("&quot;","",preg_replace("/'./","",$parameters[1]));
	$searchType = $parameters[2];
        $pattern = array('/\\bpresent\\b/i','/\\bfuture\\b/i','/\\bpast\\b/i','/(\?)/i','/\\bis\\b/i','/\\bdetails\\b/i','/\\bdetail\\b/i','/\\bprovide\\b/i','/\\bme\\b/i','/\\bdo\\b/i','/\\bwant\\b/i','/\\bi\\b/i','/\\ba\\b/i','/\\babout\\b/i','/\\babove\\b/i','/\\bacross\\b/i','/\\bafter\\b/i','/\\bafterwards\\b/i','/\\bagain\\b/i','/\\bagainst\\b/i','/\\ball\\b/i','/\\balmost\\b/i','/\\balone\\b/i','/\\balong\\b/i','/\\balready\\b/i','/\\balso\\b/i','/\\balthough\\b/i','/\\balways\\b/i','/\\bam\\b/i','/\\bamong\\b/i','/\\bamongst\\b/i','/\\bamoungst\\b/i','/\\bamount\\b/i','/\\ban\\b/i','/\\band\\b/i','/\\banother\\b/i','/\\bany\\b/i','/\\banyhow\\b/i','/\\banyone\\b/i','/\\banything\\b/i','/\\banyway\\b/i','/\\banywhere\\b/i','/\\bare\\b/i','/\\baround\\b/i','/\\bas\\b/i','/\\bat\\b/i','/\\bback\\b/i','/\\bbe\\b/i','/\\bbecame\\b/i','/\\bbecause\\b/i','/\\bbecome\\b/i','/\\bbecomes\\b/i','/\\bbecoming\\b/i','/\\bbeen\\b/i','/\\bbefore\\b/i','/\\bnow\\b/i' ,'/\\bnowhere\\b/i','/\\bof\\b/i','/\\boff\\b/i','/\\boften\\b/i','/\\bon\\b/i','/\\bonce\\b/i','/\\bonly\\b/i','/\\bonto\\b/i','/\\bor\\b/i','/\\bother\\b/i','/\\bothers\\b/i','/\\botherwise\\b/i','/\\bour\\b/i','/\\bours\\b/i','/\\bourselves\\b/i','/\\bout\\b/i','/\\bover\\b/i','/\\bown\\b/i','/\\bper\\b/i','/\\bperhaps\\b/i','/\\bplease\\b/i','/\\bput\\b/i','/\\brather\\b/i','/\\bre\\b/i','/\\bsame\\b/i','/\\bsee\\b/i','/\\bseem\\b/i','/\\bseemed\\b/i','/\\bseeming\\b/i','/\\bseems\\b/i','/\\bserious\\b/i','/\\bseveral\\b/i','/\\bshe\\b/i','/\\bshould\\b/i','/\\bshow\\b/i','/\\bside\\b/i','/\\bsince\\b/i','/\\bsincere\\b/i','/\\bsir\\b/i','/\\bso\\b/i','/\\bsome\\b/i','/\\bsomehow\\b/i','/\\bsomeone\\b/i','/\\bsomething\\b/i','/\\bsometime\\b/i','/\\bsometimes\\b/i','/\\bsomewhere\\b/i','/\\bstill\\b/i','/\\bsuch\\b/i','/\\btake\\b/i','/\\bthan\\b/i','/\\bthat\\b/i','/\\bthe\\b/i','/\\btheir\\b/i','/\\bthem\\b/i','/\\bthemselves\\b/i','/\\bthen\\b/i','/\\bthence\\b/i','/\\bthere\\b/i','/\\bthereafter\\b/i','/\\bthereby\\b/i','/\\btherefore\\b/i','/\\btherein\\b/i','/\\bthereupon\\b/i','/\\bthese\\b/i','/\\bthey\\b/i','/\\bthick\\b/i','/\\bthin\\b/i','/\\bthird\\b/i','/\\bthis\\b/i','/\\bthose\\b/i','/\\bthough\\b/i','/\\bthree\\b/i','/\\bthrough\\b/i','/\\bthroughout\\b/i','/\\bthru\\b/i','/\\bthus\\b/i','/\\bto\\b/i','/\\btogether\\b/i','/\\btoo\\b/i','/\\btop\\b/i','/\\btoward\\b/i','/\\btowards\\b/i',
            '/\\bun\\b/i','/\\bunder\\b/i','/\\buntil\\b/i','/\\bup\\b/i','/\\bupon\\b/i','/\\bus\\b/i','/\\bvery\\b/i','/\\bvia\\b/i','/\\bwas\\b/i','/\\bwe\\b/i','/\\bwell\\b/i','/\\bwere\\b/i','/\\bwhat\\b/i','/\\bwhatever\\b/i','/\\bwhen\\b/i','/\\bwhence\\b/i','/\\bwhenever\\b/i','/\\bwhere\\b/i','/\\bwhereafter\\b/i','/\\bwhereas\\b/i','/\\bwhereby\\b/i','/\\bwherein\\b/i','/\\bwhereupon\\b/i','/\\bwherever\\b/i','/\\bwhether\\b/i','/\\bwhich\\b/i','/\\bwhile\\b/i','/\\bwhither\\b/i','/\\bwho\\b/i','/\\bwhoever\\b/i','/\\bwhole\\b/i','/\\bwhom\\b/i','/\\bwhose\\b/i','/\\bwhy\\b/i','/\\bwill\\b/i','/\\bwith\\b/i','/\\bwithin\\b/i','/\\bwithout\\b/i','/\\bwould\\b/i','/\\byet\\b/i','/\\byou\\b/i','/\\byour\\b/i','/\\byours\\b/i','/\\byourself\\b/i','/\\byourselves\\b/i','/\\bof\\b/i','/\\bable\\b/i','/\\babout\\b/i','/\\babove\\b/i','/\\baccording\\b/i','/\\baccordingly\\b/i','/\\bacross\\b/i','/\\bactually\\b/i','/\\bafterwards\\b/i','/\\bagain\\b/i','/\\bagainst\\b/i','/\\bain\\b/i','/\\ball\\b/i','/\\ballow\\b/i','/\\ballows\\b/i','/\\balmost\\b/i','/\\balone\\b/i','/\\balong\\b/i','/\\balready\\b/i','/\\balso\\b/i','/\\balthough\\b/i','/\\balways\\b/i','/\\bamong\\b/i','/\\bamongst\\b/i','/\\banother\\b/i','/\\bany\\b/i','/\\banybody\\b/i','/\\banyhow\\b/i','/\\banyone\\b/i','/\\banything\\b/i','/\\banyway\\b/i','/\\banyways\\b/i','/\\banywhere\\b/i','/\\bapart\\b/i','/\\bappear\\b/i','/\\bappreciate\\b/i','/\\bappropriate\\b/i','/\\baren\\b/i','/\\baround\\b/i','/\\baside\\b/i','/\\bask\\b/i','/\\basking\\b/i','/\\bavailable\\b/i','/\\baway\\b/i','/\\bawfully\\b/i','/\\bbecame\\b/i','/\\bbecause\\b/i','/\\bbecome\\b/i','/\\bbecomes\\b/i','/\\bbecoming\\b/i','/\\bbeen\\b/i','/\\bbefore\\b/i','/\\bbeforehand\\b/i','/\\bbehind\\b/i','/\\bbeing\\b/i','/\\bbelieve\\b/i','/\\bbelow\\b/i','/\\bbeside\\b/i','/\\bbesides\\b/i','/\\bbest\\b/i','/\\bbetter\\b/i','/\\bbetween\\b/i','/\\bbeyond\\b/i','/\\bboth\\b/i','/\\bbrief\\b/i','/\\bcame\\b/i','/\\ban\\b/i','/\\bcannot\\b/i','/\\bcant\\b/i','/\\bcause\\b/i','/\\bcauses\\b/i','/\\bcertain\\b/i','/\\bcertainly\\b/i','/\\bchanges\\b/i','/\\bclearly\\b/i','/\\bcome\\b/i','/\\bcomes\\b/i','/\\bconcerning\\b/i','/\\bconsequently\\b/i','/\\bconsider\\b/i','/\\bconsidering\\b/i','/\\bcontain\\b/i','/\\bcontaining\\b/i','/\\bcontains\\b/i','/\\bcorresponding\\b/i','/\\bcould\\b/i','/\\bcouldnot\\b/i','/\\bcourse\\b/i','/\\bdefinitely\\b/i','/\\bdescribed\\b/i','/\\bdespite\\b/i','/\\bdid\\b/i','/\\bdidnot\\b/i','/\\bdifferent\\b/i','/\\bdoes\\b/i','/\\bdoesnot\\b/i','/\\bdoing\\b/i','/\\bdonot\\b/i','/\\bdone\\b/i','/\\bdown\\b/i','/\\bdownwards\\b/i','/\\bduring\\b/i','/\\beach\\b/i','/\\beither\\b/i','/\\belse\\b/i','/\\belsewhere\\b/i','/\\benough\\b/i','/\\bentirely\\b/i','/\\bespecially\\b/i','/\\betc\\b/i','/\\beven\\b/i','/\\bever\\b/i','/\\bevery\\b/i','/\\beverybody\\b/i',
            '/\\beveryone\\b/i','/\\beverything\\b/i','/\\beverywhere\\b/i','/\\bexactly\\b/i','/\\bexample\\b/i','/\\bexcept\\b/i','/\\bfar\\b/i','/\\bfew\\b/i','/\\bfollowed\\b/i','/\\bfollowing\\b/i','/\\bfollows\\b/i','/\\bformer\\b/i','/\\bformerly\\b/i',
            '/\\bforth\\b/i','/\\bfour\\b/i','/\\bfrom\\b/i','/\\bfurther\\b/i','/\\bfurthermore\\b/i','/\\bget\\b/i','/\\bgets\\b/i','/\\bgetting\\b/i','/\\bgiven\\b/i','/\\bgives\\b/i','/\\bgoes\\b/i','/\\bgoing\\b/i','/\\bgone\\b/i','/\\bgot\\b/i','/\\bgotten\\b/i','/\\bgreetings\\b/i','/\\bhad\\b/i','/\\bhadnot\\b/i','/\\bhappens\\b/i','/\\bhardly\\b/i','/\\bhas\\b/i','/\\bhasnot\\b/i','/\\bhave\\b/i','/\\bhavenot\\b/i','/\\bhaving\\b/i','/\\bhello\\b/i','/\\bhence\\b/i','/\\bher\\b/i','/\\bhere\\b/i','/\\bhereafter\\b/i','/\\bhereby\\b/i','/\\bherein\\b/i','/\\bhereupon\\b/i','/\\bhers\\b/i','/\\bherself\\b/i','/\\bhim\\b/i','/\\bhimself\\b/i','/\\bhis\\b/i','/\\bhither\\b/i','/\\bhopefully\\b/i','/\\bhow\\b/i','/\\bhowbeit\\b/i','/\\bhowever\\b/i','/\\bignored\\b/i','/\\bimmediate\\b/i','/\\binasmuch\\b/i','/\\bindeed\\b/i','/\\bindicate\\b/i','/\\bindicated\\b/i','/\\bindicates\\b/i','/\\binner\\b/i','/\\binsofar\\b/i','/\\binstead\\b/i','/\\binto\\b/i','/\\binward\\b/i','/\\bisnot\\b/i','/\\bits\\b/i','/\\bitself\\b/i','/\\bjust\\b/i','/\\bkeep\\b/i','/\\bkeeps\\b/i','/\\bkept\\b/i','/\\bknow\\b/i','/\\bknows\\b/i','/\\bknown\\b/i','/\\blast\\b/i','/\\blately\\b/i','/\\blater\\b/i','/\\blatter\\b/i','/\\blatterly\\b/i','/\\bleast\\b/i','/\\bless\\b/i','/\\blest\\b/i','/\\blet\\b/i','/\\blike\\b/i','/\\bliked\\b/i','/\\blikely\\b/i','/\\blittle\\b/i','/\\blook\\b/i','/\\blooking\\b/i','/\\blooks\\b/i','/\\bmainly\\b/i','/\\bmany\\b/i','/\\bmay\\b/i','/\\bmaybe\\b/i','/\\bmean\\b/i','/\\bmeanwhile\\b/i','/\\bmerely\\b/i','/\\bmight\\b/i','/\\bmore\\b/i','/\\bmoreover\\b/i','/\\bmost\\b/i','/\\bmostly\\b/i','/\\bmuch\\b/i','/\\bmust\\b/i','/\\bmyself\\b/i','/\\bname\\b/i','/\\bnamely\\b/i','/\\bnear\\b/i','/\\bnearly\\b/i','/\\bnecessary\\b/i','/\\bneed\\b/i','/\\bneeds\\b/i','/\\bneither\\b/i','/\\bnever\\b/i','/\\bnevertheless\\b/i','/\\bnext\\b/i','/\\bnobody\\b/i','/\\bnone\\b/i','/\\bnoone\\b/i','/\\bnormally\\b/i','/\\bnot\\b/i','/\\bnothing\\b/i','/\\bnow\\b/i','/\\bnowhere\\b/i','/\\bobviously\\b/i','/\\boff\\b/i','/\\boften\\b/i','/\\boh\\b/i','/\\bok\\b/i','/\\bokay\\b/i','/\\bonce\\b/i','/\\bones\\b/i','/\\bonly\\b/i','/\\bonto\\b/i','/\\bother\\b/i','/\\bothers\\b/i','/\\botherwise\\b/i','/\\bought\\b/i','/\\bour\\b/i','/\\bours\\b/i','/\\bourselves\\b/i','/\\boutside\\b/i','/\\boverall\\b/i','/\\bown\\b/i','/\\bparticular\\b/i','/\\bparticularly\\b/i','/\\bperhaps\\b/i','/\\bplaced\\b/i','/\\bplease\\b/i','/\\bpossible\\b/i','/\\bpresumably\\b/i','/\\bprobably\\b/i','/\\bprovides\\b/i','/\\bquite\\b/i','/\\bqv\\b/i','/\\brather\\b/i','/\\breally\\b/i','/\\breasonably\\b/i','/\\bregarding\\b/i','/\\bregardless\\b/i','/\\bregards\\b/i','/\\brelatively\\b/i','/\\brespectively\\b/i','/\\bin\\b/i','/\\bhe\\b/i');
        $count=0;
        $replace ='';
        //$dTxt =  trim(preg_replace($pattern, $replace, $discussionTxt, -1 , $count));
        $dTxt1 =  trim(preg_replace($pattern, $replace, $discussionTxt, -1 , $count));
        $dTxt =  trim(preg_replace('/(\s)+/', ' ', $dTxt1, -1 , $count));
        $txt = explode(' ',$dTxt);
	$and = '';$newStr = '';
	for($i=0;$i<count($txt);$i++){
		if($i>0) $and = ' and ';
		if($searchType=='full')
		    $newStr  .= " $and mt.msgTxt like '% $txt[$i] %'";	
		else
		    $newStr  .= " $and mt.msgTxt like '%$txt[$i]%'";	
	} 
        $discussionTxt = preg_replace('\' \'', '%', $dTxt);
        $msgArr = array();
        $total=10;
        $totalNumOfSearch = count($txt);
        $queryCmd = "select mt.msgId,mt.msgTxt,mt.threadId,mt.creationDate from messageTable mt WHERE $newStr  and mt.fromOthers='discussion'and mt.status in ('live','closed') and mt.mainAnswerId=0 $str limit 0,$total";
        $query = $dbHandle->query($queryCmd);
        $numOfRows = $query->num_rows();
        $difference = $total-$numOfRows;

        
        $k=0;
        $tmp = '';
        foreach($query->result_array() as $row) {
           // if($topicId!=$row['threadId']) {
               if($k==0){ $tmp = "'".$row['msgId']."'";}else{ $tmp .= ",'".$row['msgId']."'";}
               // $msgArr['msgId'][$k] = $row['msgId'];
                //$msgArr['msgTxt'][$k] = $row['msgTxt'];
                $msgArr[title][$k][U]=getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','',$row['creationDate']);
                $msgArr[title][$k][tmp]=getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','','2011-02-10 21:00:00');
                $msgArr[title][$k][S]= $row['msgTxt'];
                $k++;
            //}
        }
        if($difference>=0){
            for($i=0;$i<$totalNumOfSearch;$i++){
                if($difference>0){
                if($tmp!='') $str = "and mt.msgId not in ($tmp)";else $str='';
		if($searchType=='full')
		    $queryCmd1 = "select mt.msgId,mt.msgTxt,mt.threadId,mt.creationDate from messageTable mt WHERE mt.msgTxt like '% $txt[$i] %' and mt.fromOthers='discussion' and mt.status in ('live','closed') and mt.mainAnswerId=0 and mt.threadId!=? $str limit 0,$difference";
		else
		    $queryCmd1 = "select mt.msgId,mt.msgTxt,mt.threadId,mt.creationDate from messageTable mt WHERE mt.msgTxt like '%$txt[$i]%' and mt.fromOthers='discussion' and mt.status in ('live','closed') and mt.mainAnswerId=0 and mt.threadId!=? $str limit 0,$difference";
                $query1 = $dbHandle->query($queryCmd1, array($topicId));
                $numOfRows1 = $query1->num_rows();
                $difference -= $numOfRows1;
                foreach($query1->result_array() as $row) {
                    if($k==0){ $tmp = "'".$row['msgId']."'";}else{ $tmp .= ",'".$row['msgId']."'";}
                    //$msgArr['msgId'][$k] = $row['msgId'];
                   // $msgArr['msgTxt'][$k] = $row['msgTxt'];
                      $msgArr[title][$k][U]=getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','',$row['creationDate']);
                      $msgArr[title][$k][tmp]=getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','','2011-02-10 21:00:00');
                      $msgArr[title][$k][S]= $row['msgTxt'];
                      $k++;
                    }
                 }
               }
        }
        $responseString = base64_encode(gzcompress(json_encode($msgArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }


    function checkForDiscussionStatus($request) {
        $parameters = $request->output_parameters();
        $appId = 1;
        $topicId = $parameters[0];
	$dbHandle = $this->_loadDatabaseHandle();
        $queryCmd="select status from questionDiscussionLinkingTable where `linkedEntityId`= ? and `type`='discussion' and  `status`!='deleted'";
        $query = $dbHandle->query($queryCmd,array($topicId));
        $res = $query->row();
        $numOfRows = $query->num_rows();
        if($numOfRows>0){
            $result = $res->status;
        }else{
            $result = 'Blank';
        }
        $response = array(
            array(
            'result'=>$result,
             ),
            'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getLinkedDiscussion($request) {
        $parameters = $request->output_parameters();
        $appId = 1;
        $topicId = $parameters[0];
	$dbHandle = $this->_loadDatabaseHandle();
        $msgArr = array();
        $queryCmd = "select mt.msgId,mt.msgTxt,mt.threadId,mt.creationDate from messageTable mt WHERE mt.fromOthers='discussion' and mt.mainAnswerId=0 and threadId=(select linkingEntityId from questionDiscussionLinkingTable where linkedEntityId= ? and `status`='accepted' limit 1)";
        $query = $dbHandle->query($queryCmd,array($topicId));
        foreach($query->result_array() as $row) {
                $msgArr[tmpurl]=getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','','2011-02-10 21:00:00');
                $msgArr[discussionText]= $row['msgTxt'];
                $msgArr[url]=getSeoUrl($row['threadId'],'discussion',$row['msgTxt'],'','',$row['creationDate']);
             } 
        $responseString = base64_encode(gzcompress(json_encode($msgArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    //Added by Ankur on 30 Nov to delete sticky discussion in case it is deleted
    function deleteStickyDiscussion($msgId){
        $appId = 1;
	$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "update stickyDiscussionAndAnnoucementTable set status = 'deleted' where stickymsgId = ? OR stickythreadId = ?";
        $query = $dbHandle->query($queryCmd,array($msgId,$msgId));
        return 1;
    }

    function getRelatedDiscussions($request){
$parameters = $request->output_parameters();
$appId = 1;
$categoryId = $parameters[0];
$subCategoryId = $parameters[1];
$countryId = $parameters[2];
$dbHandle = $this->_loadDatabaseHandle();
$this->load->model('QnAModel');
$results = array();
$results = $this->QnAModel->getRelatedDiscussions($dbHandle,$categoryId,$subCategoryId,$countryId);
$responseString = base64_encode(gzcompress(json_encode($results)));
$response = array($responseString,'string');
return $this->xmlrpc->send_response($response);
}


    /**
     *	Set the details of the user for the Expert table.
     */
    function setExpertData($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $data=json_decode($parameters['1'],true);
        $userId=$parameters['2'];
        $expertId=$parameters['3'];
        $username = $data['quickfirstname_ForAnA'].' '.$data['quicklastname_ForAnA'];
	$dbHandle = $this->_loadDatabaseHandle('write');

		//Check if the Social links have http in them. If not, add the same in the links.
		if(isset($data['blogURL']) && $data['blogURL']!='' && strpos($data['blogURL'],'http')===false){
			$data['blogURL'] = 'http://'.$data['blogURL'];
		}
		if(isset($data['facebookURL']) && $data['facebookURL']!='' && strpos($data['facebookURL'],'http')===false){
			$data['facebookURL'] = 'http://'.$data['facebookURL'];
		}
		if(isset($data['linkedInURL']) && $data['linkedInURL']!='' && strpos($data['linkedInURL'],'http')===false){
			$data['linkedInURL'] = 'http://'.$data['linkedInURL'];
		}
		if(isset($data['twitterURL']) && $data['twitterURL']!='' && strpos($data['twitterURL'],'http')===false){
			$data['twitterURL'] = 'http://'.$data['twitterURL'];
		}
		if(isset($data['youtubeURL']) && $data['youtubeURL']!='' && strpos($data['youtubeURL'],'http')===false){
			$data['youtubeURL'] = 'http://'.$data['youtubeURL'];
		}

		$data['signature'] = (isset($data['signature']))?$data['signature']:'';
		//Check the points of the user. If they are greater than 1000, we will directly make him an expert.
		$queryCmd = "SELECT userPointValueByModule points FROM userpointsystembymodule WHERE moduleName = 'AnA' and userId = ?";
	        $query = $dbHandle->query($queryCmd, array($userId));
        	$resPoints = $query->row();
		$data['status'] = 'Draft';
		if($resPoints->points >= 1000)
			$data['status'] = 'Live';

		$queryCmd = "select * from expertOnboardTable where userId = ?";
		$query = $dbHandle->query($queryCmd,array($userId));
		if($query->num_rows()<=0){
				//This is the case of Insert
				$insertData = array(
					'username' => $username,
					'userId' => $userId,
					'designation' => $data['designation'],
					'instituteName' => $data['instituteName'],
					'highestQualification' => $data['highestQualification'],
					'imageURL' => $data['profileImage'],
					'blogURL' => $data['blogURL'],
					'facebookURL' => $data['facebookURL'],
					'linkedInURL' => $data['linkedInURL'],
					'twitterURL' => $data['twitterURL'],
					'youtubeURL' => $data['youtubeURL'],
					'aboutMe' => $data['aboutMe'],
					'signature' => $data['signature'],
					'aboutCompany' => $data['aboutCompany'],
					'status' => $data['status']
				);
				$dbHandle->insert('expertOnboardTable',$insertData);
		}
		else{
			//This is the case of Edit.
		        $res = $query->row();

			//Check if the Image URL is present and has some value. If not, we will not update the Image URL.
			if(isset($data['profileImage']) && $data['profileImage']=='')
				  $data['profileImage'] = $res->imageURL;
			else if(!isset($data['profileImage']))
				  $data['profileImage'] = $res->imageURL;

			//Also, check the status of the user. If it is Live or Draft, we will not do anything. If it is Dormant, Rejected or Deleted, we will change the status to Draft.
			if($resPoints->points >= 1000)
				$data['status'] = 'Live';
			else if($res->status == 'Dormant' || $res->status == 'Deleted' || $res->status == 'Rejected')
				  $data['status'] = 'Draft';
			else
				  $data['status'] = $res->status;

			$queryCmd = "UPDATE expertOnboardTable SET userName= ? , designation = ?, instituteName=?, highestQualification = ? , imageURL = ?, blogURL = ?, facebookURL = ?, linkedInURL = ? , twitterURL = ?, youtubeURL = ?, aboutMe = ?, aboutCompany = ?, signature= ?, status = ? WHERE userId = ? ";
			$query = $dbHandle->query($queryCmd,array($username, $data['designation'], $data['instituteName'], $data['highestQualification'], $data['profileImage'], $data['blogURL'], $data['facebookURL'], $data['linkedInURL'], $data['twitterURL'], $data['youtubeURL'], $data['aboutMe'], $data['aboutCompany'], $data['signature'],$data['status'],$userId));
		}
        $response = array(array('Result' => array('added','string')),'struct');
        return $this->xmlrpc->send_response($response);
    }

    function sendInternalArticleReplyMailer($entity,$blogTitle,$blogURL){
        $this->load->library('alerts_client');
        $AlertClientObj = new Alerts_client();
        $fromAddress=SHIKSHA_ADMIN_MAIL;
        $toEmail = "surya.prakash@shiksha.com";
        //$ccEmail = "mohammad.khan@shiksha.com,aru.chopra@shiksha.com,kaur.manmeet@shiksha.com,ankur.gupta@shiksha.com";
        $subject = "A new $entity on a Shiksha Article.";
        $htmlContent = "<p>Dear User,</p><p>There's a $entity posted on this article. Please check and take appropriate action on it.<br/><a href='$blogURL'>$blogTitle</a></p><p>Regards<br/>Shiksha.com</p>";
        $alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,$toEmail,$subject,$htmlContent,"html",time(),"n",array(),'mohammad.khan@shiksha.com');
        $alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,'neda.ishtiaq@shiksha.com',$subject,$htmlContent,"html",time(),"n",array(),'');

    }
    

    function returnDBHandle($topicId=0){
        if(isset($_COOKIE['latestThreadId']) && $_COOKIE['latestThreadId']>0){
                if($topicId>0 && $_COOKIE['latestThreadId']==$topicId){
                    return $this->_loadDatabaseHandle('write');
                }
                else if($topicId==0){
                    return $this->_loadDatabaseHandle('write');
                }
                else{
                    return $this->_loadDatabaseHandle();
                }
        }
        else{
                return $this->_loadDatabaseHandle();
        }
    }
    
    function getAllCAAnswers(){
	$this->validateCron();
        $this->load->model('CA/crdashboardmodel');
        $this->load->model('qnamodel');
        $this->load->helper('CA/cr');
        $this->load->library('CA/Mywallet');
        $AllAnswersArray =  $this->crdashboardmodel->getAllCrAnswers();
        
       foreach($AllAnswersArray as $key=>$value){
                $userId = $value['userId'];
                $threadId = $value['threadId'];
                $msgId = $value['msgId'];
                $AnswerCreationDate = $value['creationDate'];
                $courseIds = $this->crdashboardmodel->getAllCourseIdFromCR($userId);
                $crCourseArray=explode(',', $courseIds);
                
                $courseid = $this->qnamodel->getAnswerCourseId($userId,$threadId);
                //Now, also insert an entry in case of Answer in Approved/Disapproved table
                if( in_array($courseid[0]['courseId'],$crCourseArray) ){
                             //Check if the entry is available in Wallet Table. If not, execute the below statement
                            $AnswerExistence = $this->crdashboardmodel->checkAnswerExistence($userId,$msgId);
                            if($AnswerExistence ==''){
                                error_log('Answers not exist in CA_wallet table :::'.$msgId);
                                $createDate =  $this->qnamodel->getQuestionCreationDate($msgId);
                                $timeDiff = (strtotime($AnswerCreationDate) - strtotime($createDate));
                                $isCaEng = $this->mywallet->makeIncentive($userId);
                                $money = getCREarning($timeDiff,$isCaEng[$userId]);
                                $reward = $money['money']; 
                                $this->qnamodel->addInWallet($userId,$msgId,$reward);
                            }
                            $status = isset($_POST['status'])?$this->input->post('status'):'draft';
                            $reason = isset($_POST['reason'])?$this->input->post('reason'):'';
                            
                            $returnData = $this->crdashboardmodel->checkExistenceinAnswerStatus($userId,$msgId);
                            //Check if the entry is available in Answer Status Table. If not, execute the below statement
                            if($returnData == ''){
                                error_log('Answers not exist in CA_answerStatus table :::'.$msgId);
                                $result = $this->qnamodel->addAnswerStatus($userId,$msgId,$status,$reason);
                            }
        
                        }
                }
        
    }
    
    //added by akhter
    //use in discussion comment upvote mailer
    function _getDiscussionTopic($thredId){
        $dbHandle = $this->_loadDatabaseHandle('read');
        $queryCmd = "SELECT msgTxt FROM `messageTable` WHERE `parentId` = ? and `fromOthers` = 'discussion' and status = 'live'";
        $query = $dbHandle->query($queryCmd,array($thredId));
        $row   = $query->row();
        return $row->msgTxt;
    }

    /**
     *	Get the recent topics across all board's for a given country
     * give me recent replied comments
     */
    function getLatestPostedQuestions($request) {
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $userId=$parameters['4'];
        $inputDate=$parameters['5'];
        $userId=($userId!='')?$userId:0;

        $selectForCategoryAndCountry = "";
        $fromForCategoryAndCountry = "";
        $threadIdCsv = '';
        $conditionForCategoryAndCountry = "";
        $havingMisc = '';

        if($categoryId == 0){
            $selectForCategoryAndCountry = ", m2.*";
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and ";
            $havingMisc = "group by m2.threadId having count(m2.threadId) =1 or m2.categoryId =0";
        }
        else if($categoryId != 1 && $categoryId != 0) {
            $selectForCategoryAndCountry = ", m2.*";
            $fromForCategoryAndCountry = ", messageCategoryTable m2";
            $conditionForCategoryAndCountry = " m1.threadId = m2.threadId and m2.categoryId in (".$categoryId.") and ";
        }

        $vcardStatusQuery = ", 0 vcardStatus";
        $commentCountQuery = ",ifnull((select count(*) from messageTable MT where MT.threadId = m1.msgId and MT.fromOthers = 'user' and MT.parentId !=0 and MT.mainAnswerId != 0 and MT.status IN ('live','closed')),0) commentCount";
        $messageDiscussionQuery = " , ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD ";

        //If the count is not set, then proceed as earlier. If it is set, then only find recent questions for the last few days
        $totalRows = 0;
        $likeQuery = " ,ifnull((select SUM(digUp) from messageTable mx where mx.parentId = m1.msgId and status IN ('live','closed')),0) likes ";
        $dislikeQuery = " ,ifnull((select SUM(digDown) from messageTable mx where mx.parentId = m1.msgId and status IN ('live','closed')),0) dislikes ";

        $mergedQnA = array();
        $courseMigrationDate = SHIKSHA_COURSE_QUESTIONS_MIGRATION_DATE;

        if($inputDate == '') {  //This is the call for past 30 days data
            $today = date("Y-m-d");
            $lastDate = strtotime("-30 days",strtotime($today));
            $lastDate = date ('Y-m-d' , $lastDate);
            $queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry where $conditionForCategoryAndCountry  m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 AND m1.listingTypeId =0 AND m1.creationDate >= '$lastDate' ".$havingMisc.
	    		" order by creationDate desc LIMIT $startFrom,$count";            
        }
        else {  //This is the call for a specific day's data
            $prevDate = strtotime("+1 days",strtotime($inputDate));
            $prevDate = date ('Y-m-d' , $prevDate);
            $queryCmd = "select SQL_CALC_FOUND_ROWS m1.* $selectForCategoryAndCountry $vcardStatusQuery $commentCountQuery $messageDiscussionQuery, m1.msgCount answerCount $likeQuery $dislikeQuery,if((m1.listingTypeId = 0),'','') listingTitle from messageTable m1 $fromForCategoryAndCountry where $conditionForCategoryAndCountry  m1.status in ('live','closed') and m1.fromOthers='user' and m1.parentId=0 AND m1.listingTypeId =0 AND m1.creationDate >= '$inputDate' AND m1.creationDate < '$prevDate' ".$havingMisc.
	    		" order by creationDate desc LIMIT $startFrom,$count";
        }

        $query = $dbHandle->query($queryCmd);

        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $dbHandle->query($queryCmdTotal);
        foreach ($queryTotal->result() as $rowTotal) {
            $totalRows = $rowTotal->totalRows;
        }
        
        $mergedQnA = $query->result_array() ;

        $msgArray = array();
        $i=0;
        foreach ($mergedQnA as $row) {
            $queryCmdT = "select t1.lastlogintime,(t1.avtarimageurl)userImage,t1.displayname, t1.firstname, t1.lastname,  upv.levelName as level from tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where t1.userid = ?";
            $queryT = $dbHandle->query($queryCmdT, array($row['userId']) );
            $rowT = $queryT->row();
            $row['lastlogintime'] = $rowT->lastlogintime;
            $row['userImage'] = $rowT->userImage;
            $row['displayname'] = $rowT->displayname;
            $row['level'] = $rowT->level;
            $row['userName'] = $rowT->firstname.' '.$rowT->lastname;
            $row['lastname'] = $rowT->lastname;


            if($this->seo_update($row['creationDate'])) {
                $row['url'] = getSeoUrl($row['threadId'],'seo',$row['msgTxt'],'','',$row['creationDate']);
            }
            else {
                if($this->check_legacy_seo_update($row['creationDate'],$row['descriptionD']))
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['descriptionD'],'','',$row['creationDate']);
                else
                    $row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt'],'','',$row['creationDate']);
            }
            $threadIdCsv .= ($threadIdCsv=='')?$row['msgId']:",".$row['msgId'];

            //Start: Code added by Ankur on 10 March to make the institute names as URL in SHiksha Cafe
            $row['instituteurl'] = '';
            if($row['listingTypeId']>0) {
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $row['instituteurl'] = $ListingClientObj->getCorrectSeoURL($appID,$row['listingTypeId'],'institute');
                $queryCmdL = "select lm.listing_title  from  listings_main lm where lm.listing_type_id = ? and lm.listing_type = ? and lm.status IN ('live','abused')";
                $queryL = $dbHandle->query($queryCmdL, array($row['listingTypeId'],$row['listingType']) );
                $rowL = $queryL->row();
                $row['listingTitle'] = $rowL->listing_title;
            }
            //End: Code added by Ankur on 10 March to make the institute names as URL in SHiksha Cafe

            $msgArray[$i] = $row;
            $i++;
        }

        $this->load->model('QnAModel');
        $msgArrayCatCountry = array();
        if($threadIdCsv!='') {
            $msgArrayCatCountry = $this->QnAModel->getCategoryCountry($dbHandle,$threadIdCsv,true,true,true);
        }
        $msgArrayCatCountry = is_array($msgArrayCatCountry)?$msgArrayCatCountry:array();
        $mainArr = array();
        $mainArr[0]['results'] = $msgArray;
        $mainArr[0]['totalCount'] = $totalRows;
        $mainArr[0]['categoryCountry'] = $msgArrayCatCountry;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }
    
}
?>
