<?php

/*
 Copyright 2007 Info Edge India Ltd
 $Rev::               $:  Revision of last commit
 $Author:
 $Date: 2014-04-29
*/

class SAContent extends MX_Controller
{
    private $userStatus	= '';
    private $saContentLib;
    function init() {
        $this->SAContentModel = $this->load->model('sacontentmodel');
        $this->AbroadListingModel = $this->load->model('listing/abroadlistingmodel');
        $this->load->helper(array('shikshautility','article'));
        $this->load->library(array('MailerClient'));
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->load->helper('blogs/sacontent');
        $this->saContentLib = $this->load->library('saContentLib');
    }

    function index() {
        $this->init();
        $this->load->view('blogs/saContent/SAContentMain');
    }


    function getContentDetails($contentId) {

        $this->init();
        $contentDetails = $this->_checkContentRedirection($contentId);
        $this->saContentLib->prepareDownloadGuideWidget($contentDetails);
        //Old Exam guide to New Exam Page Mapping and 301 redirection
        $this->config->load('abroadExamPageConfig');
        $oldExamGuideToNewExamPageMappingIds = $this->config->item('abroad_oldExam_to_newExamPage_idMapping');
        if(array_key_exists($contentId,$oldExamGuideToNewExamPageMappingIds)){
            $contentId = $oldExamGuideToNewExamPageMappingIds[$contentId];
        }

        //load spamKeywords Config for filtering spam comments on article guide and exam page comment section
        //$this->config->load('studyAbroadSpamKeywordsConfig');
        //$spamKeywordsList 				= $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

        $displayData 							= array();
        $displayData['validateuser'] 			= $this->userStatus;
        $displayData['scholarshipCardData'] 	= $contentDetails['scholarshipCardData'];
        $displayData['scholarshipSliderTitle'] 	= $contentDetails['scholarshipSliderTitle'];
        $displayData['scholarshipsToDisplay'] 	= 3;
        unset($contentDetails['scholarshipCardData']);
        unset($contentDetails['scholarshipSliderTitle']);
        $displayData['content'] 				= $contentDetails;
        $displayData['spamKeywordsList']		= json_encode($spamKeywordsList);
        //changing the algorithm for recommendation
        $contentType = array('article','guide');
        $displayData['relatedGuideArticles']		= $this->saContentLib->getRecommendedContents($contentId,$contentType,3);

        //$displayData['relatedGuideArticles'] 	= $this->SAContentModel->getRelatedGuideArticles($contentId);
        if(!empty($contentDetails['data']['contentURL']) && $contentDetails['data']['contentURL']!=getCurrentPageURLWithoutQueryParams()){
            $url = $contentDetails['data']['contentURL'];
            header("Location: $url",TRUE,301);
            exit;
        }

        $this->load->library('messageBoard/AnAConfig');
        $author_details_array = AnAConfig::$author_details_array;

        if(isset($author_details_array[$contentDetails['data']['created_by']])){
            $userUrl						='/author/'.$contentDetails['data']['displayName'];
            $displayData['authoruserName'] 	= $contentDetails['data']['username'];
            $displayData['authorDataUser'] 	= $author_details_array[$contentDetails['data']['created_by']];
            $displayData['displayname'] 	= $contentDetails['data']['displayName'];
            $displayData['authorUrl'] 		= $userUrl;
            $displayData['avatarimageurl'] 	= $contentDetails['data']['avatarimageurl'];
            // get 18 blogs of author for slider
            //$articlesList                     = $this->SAContentModel->getUserContent($authorId,0,18,$contentId);
            //$displayData['articlesList'] 	= $articlesList;
        }
        $popularArticleDetails 				= $this->saContentLib->getPopularArticlesLastNnoOfDays($contentId);
        $displayData['popularArticles'] 	= $popularArticleDetails;
        $commentsArray 						= $this->getComments($contentId);
        $commentsArrayTemp = array();
        foreach($commentsArray['data'] as $key=>$comment1)
        {
            $comment = $comment1['data'];
            $commentsArrayTemp[$comment['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>$authorId,'userId'=>$comment['userId'],'emailId'=>$comment['emailId']));
            if(count($comment1['replies'])>0){
                foreach($comment1['replies'] as $reply){
                    $commentsArrayTemp[$reply['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>$authorId,'userId'=>$reply['userId'],'emailId'=>$reply['emailId']));
                }
            }
        }
        $displayData['deletionPermissionArray'] = $commentsArrayTemp;
        $displayData['totalGuideDownloded'] = $this->SAContentModel->totalGuideDownloded($contentId);
        $displayData['rating'] 				= $this->SAContentModel->getRating($contentId);
        $displayData['comments'] 			= $commentsArray['data'];
        $displayData['totalComments'] 		= $commentsArray['total'];
        $displayData['commentsUserData'] 	= $commentsArray['userData'];
        $displayData['userStatus'] 			= $this->userStatus;
        $displayData['is_downloadable'] 	= $contentDetails['data']['is_downloadable'];

        //if($contentDetails['data']['is_downloadable'] == 'yes')
        //{
        //    $displayData['size'] =  getRemoteFileSize($contentDetails['data']['download_link'],true);
        //}

        $displayData['trackForPages'] = true;
        $values = $this->AbroadListingModel->getUniversityCategorySubcategoryOfContent($contentId);
        $displayData['content']['data']['universityId'] = $values['universityId'];

        //tracking
        $this->_prepareTrackingData($displayData,$values);
        $this->contentPageLib = $this->load->library('contentPage/ContentPageLib');
        $displayData['browsewidget'] 	= $this->contentPageLib->getBrowseWidgetData($contentId);
        //$displayData['validateuser'] = $this->checkUserValidation();

        // if($displayData['validateuser'] !== 'false') {
        //     $this->load->model('user/usermodel');
        //     $usermodel 		= new usermodel;
        //     $userId 			= $displayData['validateuser'][0]['userid'];
        //     $user 			= $usermodel->getUserById($userId);
        //     if (!is_object($user)) {
        //         $displayData['loggedInUserData'] = false;
        //     }
        //     else
        //     {
        //         $name 								= $user->getFirstName().' '.$user->getLastName();
        //         $email 								= $user->getEmail();
        //         $userFlags 							= $user->getFlags();
        //         $isLoggedInLDBUser 					= $userFlags->getIsLDBUser();
        //         $displayData['loggedInUserData'] 	= array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
        //         $displayData['checkIfLDBUser'] 		= $usermodel->checkIfLDBUser($userId);
        //         $pref 								= $user->getPreference();
        //         $loc 								= $user->getLocationPreferences();
        //         $isLocation 						= count($loc);
        //         if(is_object($pref)){
        //             $desiredCourse = $pref->getDesiredCourse();
        //         }else{
        //             $desiredCourse = null;
        //         }
        //         $displayData['loggedInUserData']['desiredCourse'] 	= $desiredCourse;
        //         $displayData['loggedInUserData']['isLocation'] 		= $isLocation;
        //     }
        // }
        // else {
        //     $displayData['loggedInUserData'] = false;
        //     $displayData['checkIfLDBUser'] = 'NO';
        // }
        //_p(array_keys($displayData));die;

        $levelTwoNavBarData = $this->contentpagelib->getLevelTwoNavBarLinksByContentId($contentId);
        if($levelTwoNavBarData !== false)
        {
            $displayData['levelTwoNavBarData'] = $levelTwoNavBarData;
        }
        $this->load->view('blogs/saContent/SAContentMain',$displayData);
    }

    private function _checkContentRedirection($contentId){
        $this->saContentLib->checkMigratedExamContentRedirection(getCurrentPageURLWithoutQueryParams());
        if($contentId=='' || !is_numeric($contentId)){
            show_404_abroad();
        }
        $this->config->load('abroadApplyContentConfig');
        $mappedContent = $this->config->item('articleGuideToApplyContentMapping');
        if(!empty($mappedContent[$contentId])){
            $typeId = $mappedContent[$contentId];
            $contentId = substr($typeId,1);
            $url = $this->SAContentModel->getURLForContent($contentId);
            if(!empty($url)){
                header("Location:$url ",TRUE,301);
                exit;
            }else{
                show_404_abroad();
            }
        }
        $contentDetails = array();
        $contentDetails = $this->SAContentModel->getContentDetails($contentId);
        if(empty($contentDetails)) {
            $url = SHIKSHA_STUDYABROAD_HOME;
            header("Location:$url ",TRUE,301);
            exit;
        }
        $contentPageLib = $this->load->library('contentPage/ContentPageLib');
        $contentPageLib->formatContentSectionDetails($contentDetails, 'desktop');
        $contentPageLib->checkNGetScholarshipsMapped($contentDetails);
        return $contentDetails;
    }

    private function _prepareTrackingData(&$displayData,$values)
    {
        $displayData['googleRemarketingParams'] = array(
            "categoryId" => ($values['categoryId'] == '')?0:$values['categoryId'],
            "subcategoryId" => ($values['subcatId'] == '')?0:$values['subcatId'],
            "desiredCourseId" => 0,
            "countryId" => $displayData['content']['data']['countryId'],
            "cityId" => ($values['city_id']=='')?0:$values['city_id']
        );
        $displayData['beaconTrackData'] = array(
            'pageIdentifier' => $displayData['content']['data']['type'].'Page',
            'pageEntityId' => $displayData['content']['data']['content_id'],
            'extraData' => array(
                'categoryId' => $displayData['googleRemarketingParams']['categoryId'],
                'subCategoryId' => $displayData['googleRemarketingParams']['subcategoryId'],
                'LDBCourseId' => $values['ldb_course_id'],
                'cityId' => $displayData['googleRemarketingParams']['cityId'],
                //'stateId' => '',
                'countryId' => $displayData['googleRemarketingParams']['countryId'],
                //'courseLevel' => ''
            )
        );
        //_p($displayData['beaconTrackData']);die;
    }

    function downloadGuide($guide_url,$contentId,$trackingPageKeyId,$pageUrl=''){
        $this->init();
        $this->load->helper('download');
        downloadFileInChunks(base64_decode($guide_url), 400000);
        if(isset($this->userStatus[0]['userid'])){
            $userId = $this->userStatus[0]['userid'];
        }else{
            $userId =0;
        }
        if(empty($pageUrl)){
            $pageUrl = base64_encode($_SERVER['HTTP_REFERER']);
        }else{
            $pageUrl = urldecode($pageUrl);
        }
        $result = $this->SAContentModel->trackDownloadGuide($contentId,$userId,$pageUrl,$trackingPageKeyId);
        return $result;
    }

    function addComment() {
        $this->init();
        $displayData = array();
        $postArray = getPostData($_POST,$this->userStatus);
        $postArray = $this->security->xss_clean($postArray);
        $saveAsDraft = $this->input->post('saveAsDraft',true);
        //check if the value of saveAsDraft is correct
        $captchaRes = 1;
        if(!$saveAsDraft){
            $secCodeIndex = 'secCodeForContentComment';
//                    $captcha = $_REQUEST['captcha'];
            $captcha = $this->input->post('captcha',true);
        }
        //check if comment was a spam comment before saving
        //$this->config->load('studyAbroadSpamKeywordsConfig');
        //$spamKeywordsList = $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

        $commentText = $postArray['commentText'];
        for($i=0; $i< count($spamKeywordsList); $i++)
        {
            $pos = strpos($commentText, $spamKeywordsList[$i]);   ////haystack,needle
            if($pos!==false)
            {
                echo json_encode(array("status"=>"spam"));
                return;
            }
        }

        if(!empty($captcha)) {
//			   $secCode = $this->input->post('captcha',true);
            $captchaRes = verifyCaptcha($secCodeIndex, $captcha);
        }
        if($captchaRes) {
            $postArray['source'] = (isMobileRequest()?'mobile':'desktop');
            $commentId = $this->SAContentModel->addComment($postArray,$saveAsDraft);
            if($this->userStatus !== 'false' && (is_null($postArray['userName']) || $postArray['userName'] =='' || is_null($postArray['emailId']) || $postArray['emailId'] == ''))
            {
                echo json_encode(array('status'=>'missinguser'));
                return false;
            }
            $displayData = $postArray;
            $displayData['commentId'] = $commentId;
//			   $displayData['parentId'] = $_REQUEST['parentId'];
            $displayData['parentId'] = $this->input->post('parentId',true);
            $email = "";

            if($this->userStatus != 'false') {
                $cookieStr =  $this->userStatus[0]['cookiestr'];
                $cookieStrArray = explode('|',$cookieStr);
                $email = $cookieStrArray[0];
                // remove cookie once a user has logged in
                if(!empty($_COOKIE['sacontent_userName'])){
                    setcookie('sacontent_userName' ,'', time()-60*60*24*30,'/',COOKIEDOMAIN);
                    setcookie('sacontent_userEmail','', time()-60*60*24*30,'/',COOKIEDOMAIN);
                }
            }
            $displayData['email'] = $email;
            $displayData['userStatus'] = $this->userStatus;
//			   $displayData['type'] = $_REQUEST['type'];
            $displayData['type'] = $this->input->post('type',true);
            if(!$saveAsDraft){
                $this->sendMailersForCommentOrReply($displayData,$postArray);
                $commentsArrayTemp[$commentId]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>$validateuser[0]['userid'],'userId'=>$validateuser[0]['userid'],'emailId'=>$email));
                $displayData['deletionPermissionArray'] = $commentsArrayTemp;
                $displayData['replyData']['commentId']  =  $commentId;
                //                    $displayData['commentData']['data']['contentId']  =  (int)$_REQUEST['contentId'];
                $displayData['commentData']['data']['contentId']  =  (int)$this->input->post('contentId',true);
                if(!is_null($postArray['userId']) && $postArray['userId']>0){
                    $contentPageLib = $this->load->library('contentPage/ContentPageLib');
                    $userData = $contentPageLib->getUserInfoForComments(array($postArray['userId']));
                    $displayData['commentsUserData'] = reset($userData);
                }

                $html = $this->load->view('blogs/saContent/commentHtml',$displayData,true);
                echo json_encode(array('status'=>'success','html'=>$html));
            }
            else{
                echo json_encode(array('status'=>"draft",'commentInsertId'=>$commentId));
            }
        }
        else{
            echo json_encode(array("status"=>"InvadlidCaptcha"));
        }
    }

    public function updateDraftComment(){
        $this->init();
        if($this->userStatus!='false'){
            $commentId= $this->input->post('commentId',true);
            $userId   = $this->userStatus[0]['userid'];
            $userName = $this->userStatus[0]['firstname'].' '.$this->userStatus[0]['lastname'];
            $email    = reset(explode("|",$this->userStatus[0]['cookiestr']));
            $contentId= $this->input->post('contentId',true);
            $commentType= $this->input->post('commentType',true);
            $this->SAContentModel->updateComment($commentId,$userId,$userName,$email,$contentId,$commentType);
        }
    }

    public function updateDraftQuestion(){
        $this->init();
        if($this->userStatus!='false'){
            $questionId= $this->input->post('questionId',true);
            $userId   = $this->userStatus[0]['userid'];            
            $this->SAContentModel->updateQuestion($questionId,$userId);        
        }
    }

    
    function loadComments() {
        $contentId = $_POST['content'];
        $pageNo = $_POST['pageNo'];
        $displayData['validateuser'] 			= $this->checkUserValidation();
        $commentsArray = $this->getComments($contentId,$pageNo);
        $commentsArrayTemp = array();
        $userIds = array();
        foreach($commentsArray['data'] as $key=>$comment1)
        {
            $comment = $comment1['data'];
            $commentsArrayTemp[$comment['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>'','userId'=>$comment['userId'],'emailId'=>$comment['emailId']));
            if($comment['userId']>0){ // comment userIds
                $userIds[] = $comment['userId'];
            }
            if(count($comment1['replies'])>0){
                foreach($comment1['replies'] as $reply){
                    $commentsArrayTemp[$reply['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>'','userId'=>$reply['userId'],'emailId'=>$reply['emailId']));
                    if($reply['userId']>0){ // comment userIds
                        $userIds[] = $reply['userId'];
                    }
                }
            }
        }
        if(count($userIds)>0)
        {
            $contentPageLib = $this->load->library('contentPage/ContentPageLib');
            $displayData['commentsUserData'] = $contentPageLib->getUserInfoForComments(array_unique($userIds));
        }
        $displayData['deletionPermissionArray'] = $commentsArrayTemp;
        $displayData['comments'] = $commentsArray['data'];
        echo $this->load->view('blogs/saContent/commentsInner',$displayData);
    }

    function getComments($contentId,$pageNo = 0){
        $this->init();
        $returnArray = array();
        $commentsArray = array();
        $replyArray = array();
        $pageStart = $pageNo*50;
        $comments = $this->SAContentModel->getComments($contentId,'comment',array(),$pageStart);
        $commentsArray  =  $comments['data'];
        $commentIds = getCommentIds($commentsArray);

        if(!empty($commentIds)) {
            $userIds = array_map(function($a){ return $a['userId']; },$comments['data']);
            $replies = $this->SAContentModel->getComments($contentId,'reply',$commentIds);
            $replyArray = $replies['data'];
            if(count($replies['data'])>0)
            {
                // collect userIds of commentors
                $userIds = array_unique(array_merge($userIds, array_map(function($a){ return $a['userId']; },$replies['data'])));
            }
            $contentPageLib = $this->load->library('contentPage/ContentPageLib');
            $returnArray['userData'] = $contentPageLib->getUserInfoForComments(array_unique($userIds));
        }

        if(!empty($commentsArray) && !empty($replyArray)) {
            $commentsArray  = array_merge($commentsArray,$replyArray);
        }else if(!empty($commentsArray)) {
            $commentsArray  = $commentsArray;
        }else {
            $commentsArray  = $replyArray;
        }

        if(!empty($commentsArray)){
            $commentsArray = rearrangeComments($commentsArray);
        }


        $returnArray['data'] = $commentsArray;
        $returnArray['total'] = $comments['total'];

        return $returnArray;
    }

    function deleteComment(){
        $this->init();
        $commentId = $_POST['commentId'];
        $this->SAContentModel->deleteComment($_POST['type'], $commentId,$_POST['contentId']);
    }

    function checkForUserStatus () {
        $this->init();
        $status = 1 ;
        if((!is_array($this->userStatus)) && ($this->userStatus == "false")){
            $status = 0;
        }
        if(isset($_COOKIE['sacontent_userName']) && !empty($_COOKIE['sacontent_userName']) ) {
            $status = 1;
        }
        echo $status;
    }

    function saveFeedbackForAticles(){
        $this->init();
        $contentArray 	= array();

        $email  			=  base64_decode($this->input->post('email'));
        $contentUrl  			=  base64_decode($this->input->post('contentUrl'));

        $contentArray['name'] 	= base64_decode($this->input->post('name'));
        $contentArray['toEmail'] 	= base64_decode($this->input->post('email'));
        $contentArray['articleTitle'] = base64_decode($this->input->post('stripTitle'));
        $contentArray['contentId'] 	= $this->input->post('contentId');

        $data['rating'] 		= $this->input->post('rating');
        $data['contentId'] 		= $this->input->post('contentId');
        $data['type'] 		= $this->input->post('contentType');
        $data['source']		='desktop';

        if (isMobileSite() != false )
        {
            $data['source']='mobile';
        }

        $data['userId'] = 0;

        if(isset($this->userStatus) && is_array($this->userStatus))
        {
            $signedInUser = $this->userStatus;
            $data['userId'] = $signedInUser[0]['userid'];
        }

        if(isset($_POST['feedbackId']))
        {
            $data['feedbackId']= $this->input->post('feedbackId');
        }

        $result = $this->SAContentModel->saveFeedback($data);

        $contentArray['rating'] = $data['rating'];


        $MailerClient = new MailerClient();
        $autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$email,$contentUrl);
        $contentArray['articleUrl'] = $autoLoginUrl;


        Modules::run('systemMailer/SystemMailer/articleFeedbackMail',$email,$contentArray);
        echo $result;

    }


    function sendMailersForCommentOrReply($displayData,$postArray){
        $this->init();
        $MailerClient = new MailerClient();
        $userInfo = $this->SAContentModel->getAuthorInfo($postArray);
        if($displayData['type']== 'comment')
        {
            $emailIdarray=array('harleen.bedi@shiksha.com','swameeka.medhi@shiksha.com','soma.chaturvedi@shiksha.com','shruti.maheshwari@shiksha.com','k.akhil@shiksha.com',$userInfo[0]['email']);
        }
        else
        {
            $replyUserInfoArray = $this->SAContentModel->getCommentUserInfo($displayData['parentId']);
            $emailIdarray=array('harleen.bedi@shiksha.com','soma.chaturvedi@shiksha.com','swameeka.medhi@shiksha.com','shruti.maheshwari@shiksha.com','k.akhil@shiksha.com',$replyUserInfoArray[0]['emailId']);
        }
        $emailIdarray = array_unique($emailIdarray);
        $emailArray = array();
        $contentArray = array();
        $resultArray = $this->SAContentModel->getContentUrlAndTitleForMailer($postArray['contentId']);
        foreach($emailIdarray as $key=>$emailId)
        {
            $contentArray['articleType'] = $resultArray[0]['type'];
            if($displayData['type']== 'comment')
            {
                $contentArray['articleTitle'] = $resultArray[0]['strip_title'];
                $email = $userInfo[0]['email'];
                $firstName = $userInfo[0]['firstname'];
                $contentArray['name'] = $firstName;
                $contentArray['toEmail'] = $emailId;
                $autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$emailId,$resultArray[0]['contentURL']);
                $contentArray['articleUrl'] = $autoLoginUrl;
                $contentArray['userName'] = $postArray['userName'];
                $contentArray['commentText'] = $postArray['commentText'];
                $contentArray['contentId'] = $postArray['contentId'];
                Modules::run('systemMailer/SystemMailer/articleCommentMail',$emailId,$contentArray);
            }
            else
            {
                $replyUserInfoArray = $this->SAContentModel->getCommentUserInfo($displayData['parentId']);
                $contentArray['articleTitle'] = $resultArray[0]['strip_title'];
                $email = $userInfo[0]['email'];
                $firstName = $userInfo[0]['firstname'];
                $contentArray['name'] = $firstName;
                $contentArray['toEmail'] = $emailId;
                error_log("replyuser".$replyUserInfoArray[0]['userId']);
                if($replyUserInfoArray[0]['userId'] != 0 || $replyUserInfoArray[0]['emailId']!= $emailId)
                {
                    $MailerClient = new MailerClient();
                    $autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$emailId,$resultArray[0]['contentURL']);
                }
                else
                {
                    $autoLoginUrl = $resultArray[0]['contentURL'];
                }
                // when mail is going to author
                if($replyUserInfoArray[0]['emailId'] != $emailId){
                    $contentArray['replyUserName'] = $firstName;
                    $contentArray['type'] = 'author';
                    $contentArray['userName'] = $postArray['userName'];
                }
                // when mail goes to the user
                else{
                    $contentArray['userName'] =  $postArray['userName'];
                    $contentArray['type'] = '';
                    $contentArray['replyUserName'] = $replyUserInfoArray[0]['userName'];
                }
                $contentArray['articleUrl'] = $autoLoginUrl;
                $contentArray['commentText'] = $postArray['commentText'];
                $contentArray['contentId'] = $postArray['contentId'];
                $contentArray['replyText'] = $replyUserInfoArray[0]['commentText'];
                Modules::run('systemMailer/SystemMailer/articleReplyMail',$emailId,$contentArray);
            }
        }

    }

    function checkIfLDBUser(){
        $this->init();
        if(isset($this->userStatus[0]['userid'])){
            $userId = $this->userStatus[0]['userid'];
        }
        $this->load->model('user/usermodel');
        $usermodel = new usermodel;
        $checkIfLDBUser = $usermodel->checkIfLDBUser($userId);
        echo $checkIfLDBUser;
    }

    function totalGuideDownloaded(){
        $this->init();
        $contentId = $this->input->post('contentId');
        error_log("contentId====".$contentId);
        $totalGuideDownloded = $this->SAContentModel->totalGuideDownloded($contentId);
        echo $totalGuideDownloded;
    }

    public function calculateAbroadContentPopularity() {
        $this->validateCron(); // prevent browser access
        $model = $this->load->model('sacontentmodel');
        $contentData = $model->getAllContentDataToRaisePopularity();

        foreach($contentData as $key => $contentInfo) {
            $popularityCount[$contentInfo['content_id']] = array(
            'updated' => $contentInfo['updated'],
            'popularity' => $this->_getContentPopularity($contentInfo)
            );
        }

        $model->updateContentPopularity($popularityCount);
    }


    /*
    * Algo is: (Average view count) + (Average comment count X 250)
    Where:
       Average view count = View count / (no of days since creation date) Average comment count = Comment count / (no of days since creation date)
    */

    private function _getContentPopularity($contentInfo){
        $popularityCount = $contentInfo['viewCount']+ 250*$contentInfo['commentCount'] ;
        $dateDifference = floor((strtotime(date("M d Y ")) - (strtotime($contentInfo['created'])))/3600/24);
        if($dateDifference!=0){
            $popularityCount = $popularityCount / $dateDifference;
        }
        else{
            $popularityCount = 0;
        }
        // echo "<br> Artcile id: ".$contentInfo['content_id'].", popularityCount = ".$popularityCount.", date diff = ".$dateDifference;
        return $popularityCount;
    }
    /*  This is old algo.
    private function _getContentPopularity($contentInfo){

   /*
    * Algo is: 5000 * ( 5+(viewCount-10)*3+(commentCount-3)*1.5 ) / POWER( 2+now()-created , 4.5 )
    */
    /*$popularityCount = 5000 * (5 + ($contentInfo['viewCount'] - 10) * 3 + ($contentInfo['commentCount'] - 3) * 1.5);
    $dateDifference = floor((strtotime(date("M d Y ")) - (strtotime($contentInfo['created'])))/3600/24);
    $powerNumber = pow((2 + $dateDifference), 4.5);
    $popularityCount = $popularityCount / $powerNumber;
    // echo "<br> Artcile id: ".$contentInfo['content_id'].", popularityCount = ".$popularityCount.", date diff = ".$dateDifference;
    return $popularityCount;
    }
    */

    /*
     * function to launch article pages in a different way..
     * behold.. SA-Tabloid
     */
    function launchTabloid($contentId) {
        $this->init();
        $contentDetails = $this->_checkContentRedirection($contentId);

        //Old Exam guide to New Exam Page Mapping and 301 redirection
        $this->config->load('abroadExamPageConfig');
        $oldExamGuideToNewExamPageMappingIds = $this->config->item('abroad_oldExam_to_newExamPage_idMapping');
        if(array_key_exists($contentId,$oldExamGuideToNewExamPageMappingIds)){
            $contentId = $oldExamGuideToNewExamPageMappingIds[$contentId];
        }

        //load spamKeywords Config for filtering spam comments on article guide and exam page comment section
        //$this->config->load('studyAbroadSpamKeywordsConfig');
        //$spamKeywordsList 				= $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();


        $displayData 							= array();
        $displayData['validateuser'] 			= $this->checkUserValidation();
        $displayData['content'] 				= $contentDetails;
        $displayData['spamKeywordsList']		= json_encode($spamKeywordsList);
        //changing the algorithm for recommendation
        $contentType = array('article','guide');
        $displayData['relatedGuideArticles']		= $this->saContentLib->getRecommendedContents($contentId,$contentType,3);

        //$displayData['relatedGuideArticles'] 	= $this->SAContentModel->getRelatedGuideArticles($contentId);

        if(!empty($contentDetails['data']['contentURL']) && $contentDetails['data']['contentURL']!=getCurrentPageURLWithoutQueryParams()){
            $url = $contentDetails['data']['contentURL'];
            //header("Location: $url",TRUE,301);
            //exit;
        }

        $this->load->library('messageBoard/AnAConfig');
        $author_details_array = AnAConfig::$author_details_array;

        foreach($author_details_array as $authorId=>$authorData){
            if($authorId==$contentDetails['data']['created_by']){
                $userUrl						='/author/'.$contentDetails['data']['displayName'];
                $displayData['authoruserName'] 	= $contentDetails['data']['username'];
                $displayData['authorDataUser'] 	= $authorData;
                $displayData['displayname'] 		= $contentDetails['data']['displayName'];
                $displayData['authorUrl'] 		= $userUrl;
                // get 18 blogs of author for slider
                $articlesList						= $this->SAContentModel->getUserContent($authorId,0,18,$contentId);
                $displayData['avatarimageurl'] 	= $contentDetails['data']['avatarimageurl'];
                //$displayData['articlesList'] 	= $articlesList;
            }
        }
        $popularArticleDetails 				= $this->SAContentModel->getPopularArticlesLastNnoOfDays($contentId,30,6,'article');
        $displayData['popularArticles'] 	= $popularArticleDetails;
        //_p($displayData['popularArticles']);
        for($i=0;$i<count($displayData['popularArticles']);$i++)
        {
            $displayData['popularArticles'][$i]['strip_title'] = htmlspecialchars(htmlentities($displayData['popularArticles'][$i]['strip_title']));
            $displayData['popularArticles'][$i]['contentUrl'] = str_replace('articlepage','articletabloid',$displayData['popularArticles'][$i]['contentUrl']);
        }
        $commentsArray 						= $this->getComments($contentId);
        $commentsArrayTemp = array();
        foreach($commentsArray['data'] as $key=>$comment1)
        {
            $comment = $comment1['data'];
            $commentsArrayTemp[$comment['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>$authorId,'userId'=>$comment['userId'],'emailId'=>$comment['emailId']));
            if(count($comment1['replies'])>0){
                foreach($comment1['replies'] as $reply){
                    $commentsArrayTemp[$reply['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>$authorId,'userId'=>$reply['userId'],'emailId'=>$reply['emailId']));
                }
            }
        }
        $displayData['deletionPermissionArray'] = $commentsArrayTemp;
        $displayData['totalGuideDownloded'] = $this->SAContentModel->totalGuideDownloded($contentId);
        $displayData['rating'] 				= $this->SAContentModel->getRating($contentId);
        $displayData['comments'] 			= $commentsArray['data'];
        $displayData['totalComments'] 		= $commentsArray['total'];
        $displayData['userStatus'] 			= $this->userStatus;
        $displayData['is_downloadable'] 	= $contentDetails['data']['is_downloadable'];

        if($contentDetails['data']['is_downloadable'] == 'yes')
        {
            $displayData['size'] =  getRemoteFileSize($contentDetails['data']['download_link'],true);
        }

        $displayData['trackForPages'] = true;
        $values = $this->AbroadListingModel->getUniversityCategorySubcategoryOfContent($contentId);
        $displayData['content']['data']['universityId'] = $values['universityId'];

        //tracking
        $this->_prepareTrackingData($displayData,$values);


        $displayData['validateuser'] = $this->checkUserValidation();

        if($displayData['validateuser'] !== 'false') {
            $this->load->model('user/usermodel');
            $usermodel 		= new usermodel;
            $userId 			= $displayData['validateuser'][0]['userid'];
            $user 			= $usermodel->getUserById($userId);
            if (!is_object($user)) {
                $displayData['loggedInUserData'] = false;
            }
            else
            {
                $name 								= $user->getFirstName().' '.$user->getLastName();
                $email 								= $user->getEmail();
                $userFlags 							= $user->getFlags();
                $isLoggedInLDBUser 					= $userFlags->getIsLDBUser();
                $displayData['loggedInUserData'] 	= array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
                $displayData['checkIfLDBUser'] 		= $usermodel->checkIfLDBUser($userId);
                $pref 								= $user->getPreference();
                $loc 								= $user->getLocationPreferences();
                $isLocation 						= count($loc);
                if(is_object($pref)){
                    $desiredCourse = $pref->getDesiredCourse();
                }else{
                    $desiredCourse = null;
                }
                $displayData['loggedInUserData']['desiredCourse'] 	= $desiredCourse;
                $displayData['loggedInUserData']['isLocation'] 		= $isLocation;
            }
        }
        else {
            $displayData['loggedInUserData'] = false;
            $displayData['checkIfLDBUser'] = 'NO';
        }
        $tabloidLib = $this->load->library('tabloidLib');
        $displayData['headerComponents'] = $tabloidLib->prepareHeaderComponents($displayData['content']);
        $displayData['loadedForTabloid'] = $this->input->post('ajax');
        if($displayData['loadedForTabloid'] ==1){
            $this->load->view('blogs/saContent/tabloid/tabloidTemplate',$displayData);
        }
        else{
            $this->load->view('blogs/saContent/tabloidMain',$displayData);
        }
    }

    public function migrateCommentsToHTTPS(){
        echo "Since this is a migration script, it has not been \"fixed\" as per new content schema.";
        return false;
        /*$tableName = 'study_abroad_comment_details';
        $primaryColumnName = 'id';
        $contentColumnName = 'commentText';
        $contentObj = $this->load->library('common/httpContent');
        $contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName);
        $contentObj->findHttpInContent('study_abroad_content','id','summary');
        $contentObj->findHttpInContent('examContent','id','description2');
        $contentObj->findHttpInContent('study_abroad_content_sections','id','details');
        $contentObj->findHttpInContent('applyContent','id','description2');
        */
    }
}
?>
