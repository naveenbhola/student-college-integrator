<?php
class AnAMobile extends ShikshaMobileWebSite_Controller{
    
    //constructor
    public function _construct(){
        
    }
    
    //initialize data
    private function _init(){
    	$this->load->library(array('message_board_client'));
        $this->load->helper(array('mAnA5/ana','image'));
        $this->load->helper(array('mcommon5/mobile_html5'));
        $this->msgbrdClient = new Message_board_client();
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->load->config('mcommon5/mobi_config');
        $this->load->model('QnAModel');
        $this->load->config('mAnA5/MobileSiteTracking');
    }
    
    /*
     *function Name: getDetailPage
     *parameters: questionId
    */
    public function getHomepage($pageType = 'home'){

        $currentUrl = getCurrentPageURLWithoutQueryParams();
        if($currentUrl == SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome/1/0/1/answer/"){
                $url = SHIKSHA_ASK_HOME."/";
                header("Location: $url",TRUE,301);
                exit;
        }
        else if($currentUrl == SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome/1/6/1/answer/"){
                $url = SHIKSHA_ASK_HOME_URL."/discussions";
                header("Location: $url",TRUE,301);
                exit;
        }
        else if($currentUrl == SHIKSHA_ASK_HOME_URL."/messageBoard/MsgBoard/discussionHome/1/3/1/answer/"){
                $url = SHIKSHA_ASK_HOME_URL."/unanswers";
                header("Location: $url",TRUE,301);
                exit;
        }

        
        $this->_init();
        $seoDetails = $this->getSeoDetails($pageType);
        if($seoDetails['canonicalURL'] != $currentUrl && $pageType != "home"){
            $url = $seoDetails['canonicalURL'];
            header("Location: $url",TRUE,301);
            exit;
        }

		//Get the Input variables from Post/Get

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $displayData = array();
        $displayData['userStatus'] = $this->userStatus;
        $displayData['userIdOfLoginUser']    = $userId;
        $displayData['userGroup']            = $userGroup;
		$displayData['baseUrl']		     = SHIKSHA_HOME."/mAnA5/AnAMobile/getHomepage";
        $displayData['pageType']            = $pageType;
        $displayData['boomr_pageid'] = 'mobilesite_AnA_homePage';

        if($userId > 0){
              $displayData['GA_userLevel'] = 'Logged In';
        }else{
              $displayData['GA_userLevel'] = 'Non-Logged In';
        }

        /*$start=0;
        $count=10;

      /*  $startCount                 = isset($_POST['startCount'])?$this->input->post('startCount'):$start;
        $offsetCount                = isset($_POST['offsetCount'])?$this->input->post('offsetCount'):$count;
        $displayData['startCount']  = $startCount;
        $displayData['offsetCount'] = $offsetCount;*/

        $startPage = 0;
        $startPaginationIndex = 0;

        $currentPageNo = isset($_POST['nextPageNo'])?$this->input->post('nextPageNo'):$startPage;
        $currentPaginationIndex = isset($_POST['nextPaginationIndex'])?$this->input->post('nextPaginationIndex'):$startPaginationIndex;

        $callType = isset($_POST['callType'])?$this->input->post('callType'):'NONAJAX';
        $displayData['callType'] = $callType;

		//Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        //$APIClient->setRequestData(array("text"=>"mba", "type" => "tag" ,"count" => 10));
        $jsonDecodedData =$APIClient->getAPIData("AnA/getHomepageData/".$currentPaginationIndex."/".$currentPageNo."/".$pageType);

	    $displayData['data'] = $jsonDecodedData;
        if(!empty($displayData['data']['homepage']))
        {
        foreach($displayData['data']['homepage'] as $k=>$hpd)
        {
            foreach($hpd['tags'] as $x=>$y)
            {
                $tagsArr[$y['tagId']]=$y['tagName'];
            }
        }
            $this->load->library('messageBoard/TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping(); 

            $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($tagsArr);
            //_p($tagUrlInfo);die;
            foreach($displayData['data']['homepage'] as $k=>$hpd)
            {
                foreach($hpd['tags'] as $x=>$y)
                {
                    if(is_array($tagUrlInfo[$y['tagId']]))
                    {
                        $url = $tagUrlInfo[$y['tagId']]['url'];
                        $displayData['data']['homepage'][$k]['tags'][$x]['type']=$tagUrlInfo[$y['tagId']]['type'];
                    }
                    else
                    {
                        $url = getSeoUrl($y['tagId'], 'tag', $y['tagName']);    
                    }
                    $displayData['data']['homepage'][$k]['tags'][$x]['url']=$url;
                }
            }
        }
        
        $displayData['nextPaginationIndex'] = $jsonDecodedData['nextPaginationIndex'];
        $displayData['nextPageNo']  = $currentPageNo + 1;

	//Get SEO Details for the Page
	        
	if(is_array($seoDetails)){
		$displayData['m_meta_title'] = $seoDetails['title'];
		$displayData['m_meta_description'] = $seoDetails['description'];
                $displayData['m_meta_keywords']      = ' ';
		$displayData['m_canonical_url'] = $seoDetails['canonicalURL'];
	}

        $displayData['pagination_end_msg'] = "";
	
        //for beacon tracking purpose
        if($pageType == "home"){
            $displayData['trackingpageIdentifier']= MQnA_PAGEVIEW;    
            $displayData['qstickyTrackingPageKeyId'] = MQnA_ASK_QUE_STICKY;
            $displayData['ctrackingPageKeyId'] = MQnA_DCOMMENT_POST_WIDGET;
            $displayData['atrackingPageKeyId'] = MQnA_ANSWER_POST_WIDGET;
            $displayData['tupdctrackingPageKeyId'] = MQnA_TUP_DCOMMENT_TUPLE;
            $displayData['tdowndctrackingPageKeyId'] = MQnA_TDOWN_DCOMMENT_TUPLE;
            $displayData['tupatrackingPageKeyId'] = MQnA_TUP_ANSWER_TUPLE;
            $displayData['tdownatrackingPageKeyId'] = MQnA_TDOWN_ANSWER_TUPLE;
            $displayData['qfollowTrackingPageKeyId'] = MQnA_FOLLOW_QUES;
            $displayData['dfollowTrackingPageKeyId'] = MQnA_FOLLOW_DISC;
            $displayData['tfollowTrackingPageKeyId'] = MQnA_FOLLOW_TAGS_RECOMMENDATION;
            $displayData['ufollowTrackingPageKeyId'] = MQnA_FOLLOW_USER_RECOMMENDATION;
            $displayData['pagination_end_msg'] = "No more stories for you.";
        }
        else if($pageType == "discussion"){
            $displayData['trackingpageIdentifier']= MDISC_PAGEVIEW; 
            $displayData['dstickyTrackingPageKeyId']    = MDISC_POST_DISC_STICKY;
            $displayData['ctrackingPageKeyId'] = MDISC_DCOMMENT_POST_WIDGET;
            $displayData['tupdctrackingPageKeyId'] = MDISC_TUP_DCOMMENT_TUPLE;
            $displayData['tdowndctrackingPageKeyId'] = MDISC_TDOWN_DCOMMENT_TUPLE;
            $displayData['dfollowTrackingPageKeyId'] = MDISC_FOLLOW_DISC;
            $displayData['pagination_end_msg'] = "No more discussions for you.";
        }
        else if($pageType == "unanswered"){
            $displayData['trackingpageIdentifier']= MUANS_PAGEVIEW;   
            $displayData['qfollowTrackingPageKeyId'] = MUANS_FOLLOW_QUES;
            $displayData['atrackingPageKeyId'] = MUANS_ANSWER_POST_WIDGET;
            $displayData['pagination_end_msg'] = "No more unanswered questions for you.";
        }


        $displayData['gtmParams'] = array(
                        "pageType"    => $displayData['trackingpageIdentifier'],
                        "countryId"     => 2,
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }
        
        $displayData['trackingcountryId']=2;
        $displayData['viewTrackParams'] = $jsonDecodedData['viewTrackParams'];

        //below line is used to store inforamtion in beacon variable for tacking purpose
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($displayData);

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AnAHome','pageType'=>$pageType);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

	//Check if the Top card needs to be displayed
	$displayData['displayTopCard'] = false;
	if($pageType == 'home' && $userId == 0 && $currentPageNo == 0 && $currentPaginationIndex == 0){
		//1. The card will be displayed for 3 sessions. This will be maintained by a cookie ana_noOfSess
		//2. The card will be displayed 3 times in a session ana_timesInSess
		global $isNewSession;
		if (!isset($_COOKIE['ana_noOfSess'])){	//This is the First session. Set both cookies to 1.
			setcookie('ana_noOfSess',1,time() + 15552000,'/',COOKIEDOMAIN);
			setcookie('ana_timesInSess', 1 ,time() + 15552000,'/',COOKIEDOMAIN);
			$displayData['displayTopCard'] = true;
		}
		else if(isset($_COOKIE['ana_noOfSess']) && $_COOKIE['ana_noOfSess'] > 3){	//We have already displayed in 3 sessions. No need to display now.
			$displayData['displayTopCard'] = false;
		}
		else if($isNewSession){	//This is a new session. In this case, increment the value and set no of times in session as 1.
			if($_COOKIE['ana_noOfSess'] == 3){
				$displayData['displayTopCard'] = false;
			}
			else{
				$noOfSess = $_COOKIE['ana_noOfSess'];
				setcookie('ana_noOfSess', ($noOfSess+1) ,time() + 15552000,'/',COOKIEDOMAIN);
				setcookie('ana_timesInSess', 1 ,time() + 15552000,'/',COOKIEDOMAIN);
				$displayData['displayTopCard'] = true;
			}
		}
		else if(isset($_COOKIE['ana_timesInSess']) && $_COOKIE['ana_timesInSess'] < 3){	//This is the same session. In this scenario, check the no of times in session cookie
                        $noOfTimes = $_COOKIE['ana_timesInSess'];
                        setcookie('ana_timesInSess', ($noOfTimes+1) ,time() + 15552000,'/',COOKIEDOMAIN);
                        $displayData['displayTopCard'] = true;
                }
	}
        
        $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','mobile');
        
        if($callType=='AJAX'){
            echo $this->load->view('mobile/homepageContent',$displayData);
        }else{
            $this->load->view('mobile/homepage',$displayData);
        }
    }


    function getSeoDetails($pageType){
	$displayData = array();
	switch($pageType){
		case 'home': 
			$displayData['title'] = "Ask and Answers | Shiksha.com";
			$displayData['description']   = "Join Shiksha's education and career community to ask and answer questions on career, colleges, admission, exams, courses, current education trends etc.";
			$displayData['canonicalURL']      = SHIKSHA_ASK_HOME;
			break;
		case 'discussion':
                        $displayData['title'] = "Recent Discussions | Shiksha.com";
                        $displayData['description']   = "View all recent discussions about education and career. Join this community to connect with thousands of career experts, counsellors, and students online.";
                        $displayData['canonicalURL']      = SHIKSHA_ASK_HOME_URL."/discussions";
                        break;
		case 'unanswered':
                        $displayData['title'] = "Unanswered Questions | Shiksha.com";
                        $displayData['description']   = "View list of unanswered questions that have been asked about colleges, courses, exams, career, and current education trends in India and Abroad.";
                        $displayData['canonicalURL']      = SHIKSHA_ASK_HOME_URL."/unanswers";
                        break;
	}
	return $displayData;
    }
    
    /**
     * Purpose: To Convert newly registered user's visitor profile into user profile
     */
    public function convertVisitorProfileToUserProfile(){
    	$this->userStatus = $this->checkUserValidation();
    	if(!(isset($this->userStatus[0]['userid']) && $this->userStatus[0]['userid'] > 0)){
    		echo -1;
    	}
    	$visitorId = getVisitorId();
    	$this->load->library("common/personalization/PersonalizationLibrary");
    	$this->personalizationlibrary->setUserId($this->userStatus[0]['userid']);
    	$this->personalizationlibrary->setVisitorId($visitorId);
    	if($this->personalizationlibrary->convertVisitorProfileToUserProfile()){
    		echo 1;
    	}else{
    		echo -1;
    	}
    }

    /*
     *function Name: getQuestionDiscussionDetailPage
     *parameters: questionId
    */
    public function getQuestionDiscussionDetailPage($entityId,$entityType){
        
        $this->_init();

        //Get the Input variables from Post/Get

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData = array();
        $displayData['userIdOfLoginUser']    = $userId;

        if($userId > 0){
              $displayData['GA_userLevel'] = 'Logged In';
        }else{
              $displayData['GA_userLevel'] = 'Non-Logged In';
        }

        if($entityType == 'discussion'){
                $displayData['boomr_pageid'] = 'mobilesite_AnA_DDP';
        }else{
                $displayData['boomr_pageid'] = 'mobilesite_AnA_QDP';
        }
        $displayData['noJqueryMobile'] = true; 
        $displayData['pageName'] = "mobilesite_AnA_QDP";  
        $displayData['currentUrlWithParams'] = SHIKSHA_HOME.parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        $referenceId = $this->input->get('referenceEntityId', true);
        /*if(isset($_GET['referenceEntityId']) &&  $_GET['referenceEntityId']!= ''){
            $referenceId = $_GET['referenceEntityId'];
        }else{
            $referenceId = 0;
        }*/
        if (empty($referenceId)) {
            $referenceId = 0;
        }

        if($entityType == '' && $entityId != ''){
            $this->load->model('messageBoard/AnAModel');
            $typeOfEntity = $this->AnAModel->getEntityType($entityId);
            if($typeOfEntity == 'user'){
                $entityType = 'question';
            }else{
                $entityType = $typeOfEntity; 
            }

         }

        //In case of Announcements, display a 410 Error
        if($entityType == "announcement"){
                show_410();
                exit;
        }

        $start= 0;

        if($entityType == 'question'){
            $sortCriteria = 'Upvotes';
            $count = 5;
        }else if($entityType == 'discussion'){
             $sortCriteria = 'Latest';
             $count = 10;
        }
        
        $entityId = isset($_POST['entityId'])?$this->input->post('entityId'):$entityId;
        $start = isset($_POST['start'])?$this->input->post('start', true):$start;
        $count = isset($_POST['count'])?$this->input->post('count', true):$count;
        $sortOrder = isset($_POST['sortOrder'])?$this->input->post('sortOrder', true):$sortCriteria;
        $referenceAnswerId = isset($_POST['referenceAnswerId'])?$this->input->post('referenceAnswerId', true):$referenceId;
        $entityType = isset($_POST['entityType'])?$this->input->post('entityType', true):$entityType;

        /* Redirect to homepage incase entity is empty*/
        if ($entityId=='' || $entityId==0) {
            $url = SHIKSHA_ASK_HOME;
            header("Location: $url",TRUE,301);
            exit;
         }


        $callType = isset($_POST['callType'])?$this->input->post('callType', true):'NONAJAX';
        $displayData['callType'] = $callType;
        $displayData['start'] = $start;
        $displayData['count'] = $count;
        $displayData['entityId'] = $entityId;
        $displayData['sortOrder'] = $sortOrder;
        $displayData['referenceAnswerId'] = $referenceAnswerId;
        $displayData['entityType'] = $entityType;

        //Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");

        if($entityType == 'question'){
            $displayData['trackingpageIdentifier'] = MQDP_PAGEVIEW;
            $displayData['atrackingPageKeyId'] = MQDP_ANSWER_POST_WIDGET;
            $displayData['qtrackingPageKeyId'] = MQDP_ASK_QUES_ASKNOW_BOTTOM_WIDGET;
            $displayData['ctrackingPageKeyId'] = MQDP_COMMENT_POST_WIDGET;
            //$displayData['newctrackingPageKeyId'] = MQDP_COMMENT_POST_WIDGET;
            $displayData['tupatrackingPageKeyId'] = MQDP_TUP_ANSWER_TUPLE;
            $displayData['tdownatrackingPageKeyId'] = MQDP_TDOWN_ANSWER_TUPLE;
            $displayData['raTrackingPageKeyId'] = MQDP_ABUSE_QUES_TUPLE;
            $displayData['raaTrackingPageKeyId'] = MQDP_ABUSE_ANSWER_TUPLE;
            $displayData['qfollowTrackingPageKeyId'] = MQDP_FOLLOW_QUES;
            $displayData['alTrackingPageKeyId'] = MQDP_ANSWER_LATER;
            $apiUrl = "AnA/getQuestionDetailWithAnswers/";
            $urlContent = 'qna';  
        }else if($entityType == 'discussion'){
            $displayData['trackingpageIdentifier'] = MDDP_PAGEVIEW;
            $displayData['qtrackingPageKeyId'] = MDDP_ASK_QUES_ASKNOW_BOTTOM_WIDGET;
            $displayData['ctrackingPageKeyId'] = MDDP_DCOMMENT_POST_WIDGET;
            $displayData['rtrackingPageKeyId'] = MDDP_REPLY_POST_WIDGET;
            //$displayData['newrtrackingPageKeyId'] = 1018;
            $displayData['tupdctrackingPageKeyId'] = MDDP_TUP_DCOMMENT_TUPLE;
            $displayData['tdowndctrackingPageKeyId'] = MDDP_TDOWN_DCOMMENT_TUPLE;
            $displayData['raTrackingPageKeyId'] = MDDP_ABUSE_DISC_TUPLE;
            $displayData['racTrackingPageKeyId'] = MDDP_ABUSE_DCOMMENT_TUPLE;
            $displayData['dfollowTrackingPageKeyId'] = MDDP_FOLLOW_DISC;
            $displayData['clTrackingPageKeyId'] = MDDP_COMMENT_LATER;
            $apiUrl = "AnA/getDiscussionDetailWithComments/";
            $urlContent = 'dscns'; 
        }
        else{
            $url = SHIKSHA_ASK_HOME;
            header("Location: $url",TRUE,301);
            exit;
        }

        //In case of Questions/discussions, get the URL from the function getSeoUrl
        //Then, check if the entered URL is same as this one. If yes, then OK. If no, then perform a 301 redirect to the correct one
        //P.S. This will be done only in case of no pagination i.e. for the first page only.
        if ($start == 0 && $callType != 'AJAX'){
            $url = getSeoUrl($entityId,$entityType);
            $enteredURL = getCurrentPageURLWithoutQueryParams();
            if($url!='' && $url!=$enteredURL){
                 if($_SERVER['QUERY_STRING']!='' && $_SERVER['QUERY_STRING']!=NULL){
                     $url = $url."?".$_SERVER['QUERY_STRING'];
                    if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($url,ENTERPRISE_HOME) === 0) ){
                        header("Location: $url",TRUE,301);
                    }
                    else{
                        header("Location: ".SHIKSHA_HOME,TRUE,301);
                    }
                 }
                 else{
                    if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($url,ENTERPRISE_HOME) === 0) ){
                        header("Location: $url",TRUE,301);
                    }
                    else{
                        header("Location: ".SHIKSHA_HOME,TRUE,301);
                    }
                 }
                 exit;
            }
        }
        //End code for Checking URL
        $jsonDecodedData =$APIClient->getAPIData($apiUrl.$entityId."/".$start."/".$count.'/'.$sortOrder.'/'.$referenceAnswerId);

            foreach($jsonDecodedData['entityDetails']['tagsDetail'] as $k=>$v)
            {
                $tagIdsForUrl[$v['tagId']]=$v['tagName'];
            }
            $this->load->library('messageBoard/TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping(); 

            //getting chp url for tagIds exist on hierarchy combination
            $this->load->library('messageBoard/AnALibrary');
            $chpTagUrls = $this->analibrary->getChpUrlForTagsExistOnHierarchies(array_keys($tagIdsForUrl));

            $tagTypeInfo = $tagUrlMappingObj->getTagsContentType($tagIdsForUrl,$jsonDecodedData['entityDetails']['msgId']);

            $notExistChpUrlTags = array();
            foreach ($tagIdsForUrl as $key => $value) {
                if(!array_key_exists($key, $chpTagUrls))
                {
                    $notExistChpUrlTags[$key] = $value;
                }
            }

            if(!empty($notExistChpUrlTags)) {
                $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($notExistChpUrlTags);
            }
            //_p($tagUrlInfo);die;
            foreach($jsonDecodedData['entityDetails']['tagsDetail'] as $k=>$v)
            {
                if(array_key_exists($v['tagId'], $chpTagUrls)) {
                    $turl = $chpTagUrls[$v['tagId']]['url'];
                    $jsonDecodedData['entityDetails']['tagsDetail'][$k]['type']=$chpTagUrls[$v['tagId']]['type'];
                }
                else {
                    $turl = $tagUrlInfo[$v['tagId']]['url'];
                    $jsonDecodedData['entityDetails']['tagsDetail'][$k]['type']=$tagUrlInfo[$v['tagId']]['type'];
                }
                $jsonDecodedData['entityDetails']['tagsDetail'][$k]['url']=$turl;
                
                $jsonDecodedData['entityDetails']['tagsDetail'][$k]['tag_type'] = $tagTypeInfo[$jsonDecodedData['entityDetails']['msgId']][$v['tagId']];
            }
            $jsonDecodedData['entityDetails']['tagsDetail'] = TagUrlMapping::sortTags($jsonDecodedData['entityDetails']['tagsDetail']);

        $displayData['data'] = $jsonDecodedData;

        //below lines used for to show icp banner
        $this->load->model('Tagging/taggingmodel');
        $icpTagIds = $this->taggingmodel->getChildTagIds(422);
        $icpTagIds[] = 422;

        $displayData['isShowIcpBanner'] = false;

        foreach ($jsonDecodedData['entityDetails']['tagsDetail'] as $tkey => $tvalue) {
            if(in_array($tvalue['tagId'], $icpTagIds))
            {
                $displayData['isShowIcpBanner'] = true;
                break;
            }
        }

        if($entityType == 'question'){
            if(!empty($jsonDecodedData['entityDetails']['description'])){
                $metaDescription = substr($jsonDecodedData['entityDetails']['description'],0,150);
            }else{
                $metaDescription = 'Read all answers to question:'.substr($jsonDecodedData['entityDetails']['title'],0,150);
            }
        }else if($entityType == 'discussion'){
            if(!empty($jsonDecodedData['entityDetails']['description'])){
                $metaDescription = substr($jsonDecodedData['entityDetails']['description'],0,150).'.'.'Join this education community for any queries on careers, colleges, placements, admission, cut-offs, fees etc.';
            }else{
                 $metaDescription = substr($jsonDecodedData['entityDetails']['title'],0,150).'.'.'Join this education community for any queries on careers, colleges, placements, admission, cut-offs, fees etc.';
            }
        }

        /* Redirect to homepage incase entity does'nt exist*/
        if (empty($jsonDecodedData['entityDetails'])) {
            $url = SHIKSHA_ASK_HOME;
            header("Location: $url",TRUE,301);
            exit;
         }

         /*get Linked Discussions incase of discussion*/
         if($entityType == 'discussion' && $callType!='AJAX'){
            $displayData['linkedDiscussions'] = $this->getRelatedLinkedQuestions($jsonDecodedData['entityDetails']['threadId'],$entityType);
         }

        
        /* Seo url Details */
        //$this->load->library('Seo_client');
        //$Seo_client = new Seo_client();
        //$flag_seo_url = $Seo_client->getSeoUrlNewSchema($entityId,$entityType);
        //$title = $flag_seo_url[1];
	$title = $jsonDecodedData['entityDetails']['title'];

        $title = seo_url_lowercase($title,'-','150');
        $metaTitle = seo_url_lowercase($title,' ','150');
        $displayData['m_meta_title'] = $metaTitle." - Shiksha.com";
        $displayData['m_meta_description'] = htmlentities($metaDescription);
        $displayData['m_meta_keywords']      = ' ';
        $displayData['m_canonical_url'] = SHIKSHA_ASK_HOME."/".$title."-".$urlContent."-".$entityId;

        $displayData['app_linking']['android_facebook']['app_name']        = "Shiksha Ask & Answer";
        $displayData['app_linking']['android_facebook']['package']         = "com.shiksha.android";
        $displayData['app_linking']['android_facebook']['should_fallback'] = "true";
        $displayData['app_linking']['android_facebook']['url']             = $displayData['m_canonical_url'];
        
        $displayData['app_linking']['android_twitter']['app_name']         = "Shiksha Ask & Answer";
        $displayData['app_linking']['android_twitter']['app_id']           = "com.shiksha.android";
        $displayData['app_linking']['android_twitter']['url']              = $displayData['m_canonical_url'];

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_QuestionDetailPage','entity_id'=>$entityId,'pageType'=>$entityType,'anaTags'=>array_values($tagIdsForUrl));
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

	    $displayData['alternate'] = "android-app://com.shiksha.android/https/".$_SERVER['SERVER_NAME']."/".seo_url_lowercase($title,"-",'','110')."-$urlContent-" . $entityId;        

        $displayData['gtmParams'] = array(
                        "pageType"    => $displayData['trackingpageIdentifier'],
                        "countryId"     => 2,
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }
        $this->load->library('Tagging/TaggingLib'); 
        $taggingLib = new TaggingLib();
        //below line is used for beacon tracking purpose
        $tracking = $this->load->library('common/trackingpages');
        $displayData['trackingpageNo'] = $entityId;
        $tracking->_pagetracking($displayData);
        $displayData['pageType'] = $entityType;
        $displayData['viewTrackParams'] = $jsonDecodedData['viewTrackParams'];
        $displayData['validateuser']['userid'] = $userId;
        $displayData['callType'] = $callType;
        foreach ($jsonDecodedData['entityDetails']['tagsDetail'] as $key => $value) {
            $tagIds[] = $value['tagId'];
        }
        $displayData['tagIds'] = $tagIds;
        if($entityType == 'question'){
            $displayData['tagRHSwidgetData'] =  $taggingLib->checkTagTypeToShowRHSWidget($tagIds);
        }
        $answerIdToEdit = !empty($_GET['answerId']) ? $this->input->get('answerId') : 0;
        $displayData['answerIdToEdit'] = $answerIdToEdit;
        $openAnsBox = !empty($_GET['openAnsBox']) ? $this->input->get('openAnsBox') : 0;
        $displayData['openAnsBox'] = $openAnsBox;

		$displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','mobile');
        $displayData['showFeedbackLayer']=0;
        if($userId > 0 && $entityType == 'question' && $displayData['userIdOfLoginUser']==$displayData['data']['entityDetails']['userId'])
        {
            $this->load->model('messageBoard/AnAModel');
            $feedbackCount = $this->AnAModel->getFeedbackCount($displayData['entityId'],$userId);
            if($feedbackCount==0)
            {
                $feedbackLayerDisplayCount = $this->AnAModel->getFeedbackLayerDisplayCount($displayData['entityId'],$userId);
                if(($displayData['userIdOfLoginUser']==$displayData['data']['entityDetails']['userId']) &&
                ($displayData['data']['entityDetails']['childCount']>0) && ($feedbackLayerDisplayCount<3))
                {
                    $displayData['showFeedbackLayer']=1;
                    $displayData['lastAnswerId'] = $displayData['data']['childDetails'][0]['msgId'];
                    $displayData['numberOfAnswers']=$displayData['data']['entityDetails']['childCount'];
                }
            }
        }
        if($callType=='AJAX'){
            echo $this->load->view('mobile/answerCommentDetailContent',$displayData);
        }else{
            $this->load->view('mobile/entityDetailPage',$displayData);
        }
    }


    function getRelatedLinkedQuestions($entityId,$entityType){

        $this->_init();

    	//Get the Input variables from Post/Get

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData = array();
        $displayData['userIdOfLoginUser']    = $userId;

        $entityId = isset($_POST['entityId'])?$this->input->post('entityId'):$entityId;
        $type = isset($_POST['type'])?$this->input->post('type'):$entityType;
        $callType = isset($_POST['callType'])?$this->input->post('callType'):'';
    

        $displayData['entityId'] = $entityId;
        $displayData['type'] = $type;
        if($userId > 0){
              $displayData['GA_userLevel'] = 'Logged In';
        }else{
              $displayData['GA_userLevel'] = 'Non-Logged In';
        }

    	//Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $jsonDecodedData =$APIClient->getAPIData("Search/getLinkedAndRelatedThread/".$entityId."/".$type."/true");

        if(!empty($jsonDecodedData['linkedEntities'])){
            foreach($jsonDecodedData['linkedEntities'] as $key=>$linkedEntity){
                $jsonDecodedData['linkedEntities'][$key]['url'] = getSeoUrl($linkedEntity['threadId'], $type, $linkedEntity['title'], array(), "NA", $linkedEntity['creationDate']);
            }
        }

        if(!empty($jsonDecodedData['related'])){
            foreach($jsonDecodedData['related'] as $key=>$relatedEntity){
                $jsonDecodedData['related'][$key]['url'] = getSeoUrl($relatedEntity['threadId'], $type, $relatedEntity['title'], array(), "NA", $relatedEntity['creationDate']);
            }
        }

        $displayData['data'] = $jsonDecodedData;
   
        if($callType == 'AJAX'){
        echo $this->load->view('mobile/relatedOrLinkedEntityWidget',$displayData);
        }else{
            return $jsonDecodedData;
        }

    }

    function getCommentDetails($parentId){
        $this->_init();

        //Get the Input variables from Post/Get

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData = array();
        $displayData['userIdOfLoginUser']    = $userId;
        
        if($userId > 0){
              $displayData['GA_userLevel'] = 'Logged In';
        }else{
              $displayData['GA_userLevel'] = 'Non-Logged In';
        }

        $startIndex = 0;
        $countIndex = 10;

        $parentId = isset($_POST['parentId'])?$this->input->post('parentId'):$parentId;
        $startIndex = isset($_POST['startIndex'])?$this->input->post('startIndex'):$startIndex;
        $countIndex = isset($_POST['countIndex'])?$this->input->post('countIndex'):$countIndex;
        $entityType = isset($_POST['entityType'])?$this->input->post('entityType'):'question';

        if($entityType == 'question'){
            $childType = 'Comment'; 
            //$displayData['ctrackingPageKeyId'] = 1016;
            $displayData['raTrackingPageKeyId'] = MQDP_ABUSE_COMMNET_TUPLE;
            $apiUrl = "AnA/getCommentDetails/";
        }else{
            $childType = 'Reply';
            //$displayData['rtrackingPageKeyId'] = 1017;
            $displayData['raTrackingPageKeyId'] = MDDP_ABUSE_REPLY_TUPLE;
            $apiUrl = "AnA/getReplyDetails/";
        }

        $displayData['pageNo'] = ($startIndex/$countIndex)+1;
        $displayData['startIndex'] = $startIndex;
        $displayData['countIndex'] = $countIndex;
        $displayData['parentId'] = $parentId;
        $displayData['childType'] = $childType;
        $displayData['entityType'] = $entityType;

        //Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");

        $jsonDecodedData =$APIClient->getAPIData($apiUrl.$parentId.'/'.$startIndex.'/'.$countIndex);

        $displayData['data'] = $jsonDecodedData;
        
        echo $this->load->view('mobile/commentDetailLayer',$displayData);

    }

	// Follow tag/user/dicussion/question
    public function followEntity(){
    	$this->_init();
    	$userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    	// check to avoid call for non-logged-in users
    	if($userId == 0){
    		echo 'FAIL';
    		return ;
    	}
    	$entityType	= $this->input->post('entityType');
    	$entityId	= (int) $this->input->post('entityId');
    	$action		= $this->input->post('action');
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
    	
    	// check to avoid invalid actions
    	if(!in_array($action, array('follow','unfollow'))){
    		echo 'FAIL';
    		return ;
    	}
    	// check to avoid invalid entities
    	if(!in_array($entityType, array('user','tag','question','discussion'))){
    		echo 'FAIL';
    		return ;
    	}
    	// check to avoid invalid entity ID
    	if(!is_int($entityId)){
    		echo 'FAIL';
    		return ;
    	}
    	
    	$APIClient = $this->load->library("APIClient");
    	$APIClient->setUserId($userId);
    	$APIClient->setVisitorId(getVisitorId());
    	$APIClient->setRequestType("post");
    	$APIClient->setRequestData(array("entityId"	=> $entityId, "entityType"	=> $entityType , "action"	=> $action,"trackingPageKeyId" => $trackingPageKeyId));
    	$jsonDecodedData =$APIClient->getAPIData("Universal/followEntity/");
    	
    	if($jsonDecodedData['responseCode'] == 0){
    		$response = array('response' => 'SUCCESS' , 'message' => 'SUCCESS');
    		echo json_encode($response);
    		return ;
    	}else {
    		$response = array('response' => 'FAIL' , 'message' => $jsonDecodedData['responseMessage']);
    		echo json_encode($response);
    		return ;
    	}
    	
    }
    
    public function rateEntity(){
    	$this->_init();
    	$userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    	// check to avoid call for non-logged-in users
    	if($userId == 0){
    		echo 'FAIL';
    		return ;
    	}
    	
    	$entityType	= $this->input->post('entityType');
    	$entityId	= (int) $this->input->post('entityId');
    	$action		= $this->input->post('action');
    	$isLoginFlow= $this->input->post('isLoginFlow');
    	$isLoginFlow= isset($isLoginFlow)?$isLoginFlow:'false';
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';
    	 
    	// check to avoid invalid actions
    	if(!in_array($action, array('upvote','downvote','cancelupvote','canceldownvote'))){
    		echo 'FAIL';
    		return ;
    	}
    	// check to avoid invalid entities
    	if(!in_array($entityType, array('answer','comment'))){
    		echo 'FAIL';
    		return ;
    	}
    	// check to avoid invalid entity ID
    	if(!is_int($entityId)){
    		echo 'FAIL';
    		return ;
    	}
    	
    	$APIClient = $this->load->library("APIClient");
    	$APIClient->setUserId($userId);
    	$APIClient->setVisitorId(getVisitorId());
    	$APIClient->setRequestType("post");
    	if($action == 'upvote' || $action == 'cancelupvote'){
    		$digVal = 1;
    	}elseif ($action == 'downvote' || $action == 'canceldownvote'){
    		$digVal = 0;
    	}
    	$APIClient->setRequestData(array("entityId"	=> $entityId, "entityType"	=> $entityType , "digVal"	=> $digVal,"isLoginFlow" => $isLoginFlow ,"trackingPageKeyId" =>$trackingPageKeyId));
    	
    	if($action == 'upvote' || $action == 'downvote'){
    		$jsonDecodedData = $APIClient->getAPIData("AnAPost/setEntityRating/");
    		if($jsonDecodedData['responseCode'] == 0){
    			$response = array('response' => 'SUCCESS' , 'message' => 'SUCCESS');
    			echo json_encode($response);
    		}else{
    			$response = array('response' => 'FAIL' , 'message' => $jsonDecodedData['responseMessage']);
    			echo json_encode($response);
    		}
    		return ;
    	}elseif ($action == 'cancelupvote' || $action == 'canceldownvote'){
    		$jsonDecodedData = $APIClient->getAPIData("AnAPost/deleteEntityRating/");
    		if($jsonDecodedData['responseCode'] == 0){
    			$response = array('response' => 'SUCCESS' , 'message' => 'SUCCESS');
    			echo json_encode($response);
    		}else{
    			$response = array('response' => 'FAIL' , 'message' => $jsonDecodedData['responseMessage']);
    			echo json_encode($response);
    		}
    		return;
    	}
    }

    public function shareEntityLog(){
	$this->_init();

	//Get Post variables
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $entityId = isset($_POST['entityId'])?$this->input->post('entityId'):0;
        $type = isset($_POST['entityType'])?$this->input->post('entityType'):'question';
        $destination = isset($_POST['destination'])?$this->input->post('destination'):'';

        //Make a CURL call to post the data
	$apiUrl = "AnAPost/shareEntity";
        $APIClient = $this->load->library("APIClient");
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
	$APIClient->setRequestData(array("entityId"=>$entityId, "entityType" => $type ,'destination' => $destination));
        $jsonDecodedData = $APIClient->getAPIData($apiUrl);

    }

    /**
     * @desc Function to shortlist an entity by the user. Called when user clicks on Answer Later/Comment Later on QDP/DDP
     * @param POST param entityId which is the Id of the Question/Discussion
     * @param POST param entityType which is the entity type. This can be question/discussion
     * @return String - Success/Fail 
     * @date 2016-04-28
     * @author Ankur Gupta
     */
    public function markLater(){
        $this->_init();
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        // check to avoid call for non-logged-in users
        if($userId == 0){
                echo 'FAIL';
                return ;
        }
        $entityType     = $this->input->post('entityType');
        $entityId       = (int) $this->input->post('entityId');
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';

        // check to avoid invalid entities
        if(!in_array($entityType, array('question','discussion'))){
                echo 'FAIL';
                return ;
        }
        // check to avoid invalid entity ID
        if(!is_int($entityId)){
                echo 'FAIL';
                return ;
        }

        $APIClient = $this->load->library("APIClient");
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("entityId"     => $entityId, "entityType"      => $entityType ,"trackingPageKeyId" => $trackingPageKeyId));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/shortlistEntity/");
        if($jsonDecodedData['responseMessage'] == 'Success'){
                echo 'SUCCESS';
                return ;
        }else {
                echo 'FAIL';
                return ;
        }
    }


    /**
     * @desc Function to get list of users who have performed an action like follow/upvote on an entity 
     * @param POST param entityId which is the Id of the entity on which was performed
     * @param POST param actionType which is the type of action like follow/upvote
     * @param POST param actionType which is the type of action like follow/upvote
     * @param POST param entityType which is the type of entity.This can be question/discussion.
     * @return String - Success/Fail 
     * @date 2016-05-02
     * @author Yamini Bisht
     */

	public function getListOfUsersBasedOnAction(){
        $this->_init();

        //Get Post variables
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'0';
        $entityId = isset($_POST['entityId'])?$this->input->post('entityId'):'0';
        $start = isset($_POST['start'])?$this->input->post('start'):'0';
        $count = isset($_POST['count'])?$this->input->post('count'):'10';
        $entityType = isset($_POST['entityType'])?$this->input->post('entityType'):'question';
        $actionType = isset($_POST['actionType'])?$this->input->post('actionType'):'follow';

        //Make a CURL call to post the data

        $APIClient = $this->load->library("APIClient");
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $apiUrl = "AnA/getListOfUsersBasedOnAction";

        $jsonDecodedData =$APIClient->getAPIData($apiUrl."/".$entityId."/".$start."/".$count."/".$actionType."/".$entityType);

        $displayData['loggedInUser'] =  $userId ;        
        $displayData['threadType'] = $entityType;
        $displayData['startIndex'] = $start;
        $displayData['countIndex'] = $count;
        $displayData['actionType'] = $actionType;
        $displayData['pageNoUserList'] = ($start/$count)+1;
        if($entityType == 'question')
        {
            if($actionType =='upvote' )
            {
                $displayData['followTrackingPageKeyId'] = MQDP_FOLLOW_USER_UPVOTERS_LIST;
            }
            elseif ($actionType == 'follow') {
                $displayData['followTrackingPageKeyId'] = MQDP_FOLLOW_USER_FOLLOWER_LIST;   
            }
        }
        elseif ($entityType == 'discussion') {
            if($actionType =='upvote' )
            {
                $displayData['followTrackingPageKeyId'] = MDDP_FOLLOW_USER_UPVOTERS_LIST;
            }
            elseif ($actionType == 'follow') {
                $displayData['followTrackingPageKeyId'] = MDDP_FOLLOW_USER_FOLLOWER_LIST;   
            }   
        }
        
        $displayData['userListDetails'] = array();

        if(isset($jsonDecodedData['users'])){
            $displayData['userListDetails'] = $jsonDecodedData['users'];
        }
        echo $this->load->view('mobile/listOfUserLayer',$displayData);

    }

    /**
     * @desc Function to load question posting AMP page
     * @date 2017-04-17
     * @author Yamini Bisht
     */

    public function getQuestionPostingAmpPage(){
        $this->_init();

        $courseId = !empty($_GET['courseId'])?$this->input->get('courseId'):'';
        $instituteId = !empty($_GET['listingId'])?$this->input->get('listingId'):'';
        $fromwhere = !empty($_GET['fromwhere'])?$this->input->get('fromwhere'):'';
        $cityId = !empty($_GET['city'])?$this->input->get('city'):'';
        $localityId = !empty($_GET['locality'])?$this->input->get('locality'):'';
        $examId = !empty($_GET['examId'])?$this->input->get('examId'):'';
        $groupId = !empty($_GET['groupId'])?$this->input->get('groupId'):'';

        $examPageType = '';
        if($fromwhere == 'examPagePWA' || $fromwhere == 'examPageAMP'){
            $examPageType = !empty($_GET['examPageType'])?$this->input->get('examPageType'):'';
            $this->load->config('examPages/examPageTrackingIdConfig');
            $examPageTrackingKeys = $this->config->item('examPageTrackingKeys');
        }

        if(!(is_numeric($instituteId) && is_numeric($instituteId) > 0) || empty($fromwhere))
        {
            $instituteId = '';
            //$fromwhere = '';
        }

        if(!(is_numeric($courseId) && is_numeric($courseId) > 0 && $fromwhere=='coursepage'))
        {
            $courseId = '';
        }

        $displayData['userStatus'] = $this->userStatus;
        $displayData['userIdOfLoginUser']    = $userId;
        $displayData['userGroup']            = $userGroup;
        $displayData['gtmParams'] = array(
                        "pageType"    => $displayData['trackingpageIdentifier'],
                        "countryId"     => 2,
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        $displayData['ampPageFlag'] = true;

        if(in_array($fromwhere,array('coursepage','institute','university')))
        {   
            $this->load->config('common/misTrackingKey');
            $keyIds = $this->config->item($fromwhere); 
            $displayData['trackingKeyId'] =  $keyIds['ASK_WIDGET'];

            if($fromwhere != 'coursepage'){
                if(!empty($instituteId)){

                    //get course list for an institute/university
                    $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
               
                    $courseIdsMapping = $this->instituteDetailLib->getAllCoursesForInstitutes($instituteId);
                    if($fromwhere == 'university'){
                        // get universities college data
                        $data['collegesWidgetData']   = $this->instituteDetailLib->getUniversityPageCollegesWidgetData($instituteId,3, $courseIdsMapping);
                    }

                    $this->load->builder("nationalInstitute/InstituteBuilder");
                    $instituteBuilder = new InstituteBuilder();
                    $this->instituteRepo = $instituteBuilder->getInstituteRepository(); 
                    $instituteObj = $this->instituteRepo->find($instituteId,'full');

		    $idCheck = $instituteObj->getId();
		    if(empty($idCheck)){
			show_404();
			exit;
		    }

                    $currentLocationObj          = $this->instituteDetailLib->getInstituteCurrentLocation($instituteObj,$cityId,$localityId);

                    $instituteLocations  = $instituteObj->getLocations();
                    $isMultilocation    = count($instituteLocations) > 1 ? true : false;   
                    $coursesWidgetData   = $this->instituteDetailLib->getInstitutePageCourseWidgetData($instituteObj, $currentLocationObj, $isMultilocation, 2, 2000, 'mobile',$courseIdsMapping);

                    $courseIdArray[$instituteId] = $courseIdsMapping['courseIds'];
                    if(!empty($courseIdArray)){
                        if(empty($coursesWidgetData['allCourses'])){
                            $this->load->builder("nationalCourse/CourseBuilder");
                            $courseBuilder = new CourseBuilder();
                            $this->courseRepo = $courseBuilder->getCourseRepository();
                            $coursesInfo = $this->courseRepo->findMultiple($courseIdArray[$instituteId]);
                        }else{
                            $coursesInfo = $coursesWidgetData['allCourses'];
                        } 
                                    
                        $instituteCourses = array();
                        foreach ($coursesInfo as $courseKey => $courseValue){
                                    $courseId = $courseValue->getId();
                                    $courseName = $courseValue->getName();
                                    
                            $instituteName = $courseValue->getOfferedByShortName();
                                    $instituteName = $instituteName ? $instituteName : $instituteObj->getShortName();
                                    $instituteName = $instituteName ? $instituteName : $instituteObj->getName();
                            if($fromwhere == 'university'){
                                $courseName .= ", ".$instituteName;
                            }
                            $instituteCourses[] = array('course_id' => $courseId,'course_name' => htmlentities($courseName)); 
                            
                        }
                         //sort courses alphabetically
                         function course_sort($a,$b)
                         {
                            if ($a['course_name']==$b['course_name']) return 0;
                              return (strtolower($a['course_name'])<strtolower($b['course_name']))?-1:1;
                         }

                        usort($instituteCourses,"course_sort");
                     
                        $displayData['instituteCourses'] = $instituteCourses;

                    }
                $courseId = '';
                }   
            }
        }


        if($fromwhere == 'exampage' || $fromwhere == 'examPagePWA' || $fromwhere == 'examPageAMP'){
            $displayData['trackingKeyId'] = 1299;
            $displayData['actionType'] = 'exam_ask_question';
        }else{
            $displayData['trackingKeyId'] = 714;
            $displayData['actionType'] = 'Asked_Question_On_Listing_MOB';
        }

        if($fromwhere == 'examPagePWA' && $examPageType != ''){
            $displayData['actionType'] = 'exam_ask_question';
            $displayData['trackingKeyId'] = $examPageTrackingKeys[$examPageType]['PWA']['askNow'] > 0 ? $examPageTrackingKeys[$examPageType]['PWA']['askNow'] : 1299;
        }else if($fromwhere == 'examPageAMP' && $examPageType != ''){
            $displayData['actionType'] = 'exam_ask_question';
            $displayData['trackingKeyId'] = $examPageTrackingKeys[$examPageType]['AMP']['askNow'] > 0 ? $examPageTrackingKeys[$examPageType]['AMP']['askNow'] : 1299;
        }

        if($fromwhere == 'chpAskBottom'){
            $displayData['actionType'] = 'chp_ask_question';
            $displayData['trackingKeyId'] = 3327;
        }
        if($fromwhere == 'chpAskQuestion'){
            $displayData['actionType'] = 'chp_ask_question';
            $displayData['trackingKeyId'] = 1955;
        }
                   
        $displayData['trackingcountryId']=2;
        $displayData['viewTrackParams'] = $jsonDecodedData['viewTrackParams'];

        $displayData['courseId'] = $courseId;
        $displayData['instituteId'] = $instituteId;
        $displayData['fromwhere'] = $fromwhere;
        $displayData['examId'] = $examId;
        $displayData['groupId'] = $groupId;

        $this->load->view('/mAnA5/mobile/questionPostingAMPPage',$displayData);
    }

      public function clientWidgetAnA($tags){
         if(is_array($tags) && count($tags)>0){
            $this->load->library('messageBoard/AnALibrary');
            $this->analibrary->clientWidgetAnA($tags, $device = 'mobile');
         }
      }

      public function articleWidgetAnA(){
         $this->load->library('messageBoard/AnALibrary');
         $this->analibrary->articleWidgetAnA($device = 'mobile');
      }

        public function saveQdpFeedback(){
        $this->_init();
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        // check to avoid call for non-logged-in users
        if($userId == 0){
            echo 'FAIL';
            return ;
        }
        $questionId = $this->input->post('questionId');
        $rating = $this->input->post('rating');
        $feedbackMessage = $this->input->post('feedbackMessage');
        $lastAnswerId = $this->input->post('lastAnswerId');
        $numberOfAnswers = $this->input->post('numberOfAnswers');
        $APIClient = $this->load->library("APIClient");
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("questionId" => $questionId,"rating"=>$rating,"feedbackMessage"=>$feedbackMessage,"lastAnswerId"=>$lastAnswerId,'numberOfAnswers'=>$numberOfAnswers));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/saveQdpFeedback");
        if($jsonDecodedData['responseCode'] == 0)
        {
            $response = array('response' => 'SUCCESS' , 'message' => 'SUCCESS');
            echo json_encode($response);
            return ;
        }else 
        {
            $response = array('response' => 'FAIL' , 'message' => $jsonDecodedData['responseMessage']);
            echo json_encode($response);
            return ;
        }   
        }
        public function updateFeedbackLayerShownCount(){
        $this->_init();
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        // check to avoid call for non-logged-in users
        if($userId == 0){
            echo 'FAIL';
            return ;
        }
        $questionId = $this->input->post('questionId');
        
        $APIClient = $this->load->library("APIClient");
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("post");
        $APIClient->setRequestData(array("questionId" => $questionId));
        $jsonDecodedData =$APIClient->getAPIData("AnAPost/updateFeedbackLayerShownCount");
        // $jsonDecodedData =$APIClient->getAPIData("Search/getLinkedAndRelatedThread/".$entityId."/".$type);
        print_r($jsonDecodedData);die;
        if($jsonDecodedData['responseCode'] == 0){
            $response = array('response' => 'SUCCESS' , 'message' => 'SUCCESS');
            echo json_encode($response);
            return ;
        }else {
            $response = array('response' => 'FAIL' , 'message' => $jsonDecodedData['responseMessage']);
            echo json_encode($response);
            return ;
        }
        
    }
    
    function getMoreReply(){
        $threadId = $this->input->post('threadId'); // topicId
        $mainAnswerId = $this->input->post('mainAnswerId'); // comment msgId
        $start    = $this->input->post('startPage');

        if(is_numeric($threadId) && $threadId>0 && is_numeric($mainAnswerId) && $mainAnswerId>0 && is_numeric($start)){
            $helper=array('image','shikshautility','utility_helper','string');
            if(is_array($helper)){
                $this->load->helper($helper);
            }
            $qnaModelObj = $this->load->model('QnAModel');      
            $mainAnswerIds[]  = $mainAnswerId;
            $reply = $qnaModelObj->getReplyOnComment($threadId, $mainAnswerIds, $start);
            $data['replyArr'] = $reply;
            $data['showTimeTextHide'] = true;
            $this->load->view('mAnA5/replySnippet',$data);
        }
    }

    function getMoreComment(){
        $threadId = $this->input->post('threadId'); // topicId
        $start    = $this->input->post('startPage');
        $commentLimit    = $this->input->post('commentLimit');
        $replyTrackingKey = $this->input->post('rtKeyId');
        $page = $this->input->post('page');

        if(is_numeric($threadId) && $threadId>0 && is_numeric($start) && $start>0 && is_numeric($commentLimit) && $commentLimit>0){
            $this->load->helper('string');
            $this->userStatus = $this->checkUserValidation();
            $commentLib  = $this->load->library('mAnA5/AnACommentLib');
            $result = $commentLib->getCommentEntity($threadId, $start, $commentLimit);
            $validateuser = $this->userStatus;
            $data['commentLimit'] = $commentLimit;
            $data['userId'] = $validateuser[0]['userid'];
            $data['topicId'] = $result['topicId'];
            $data['commentCountForTopic'] = $result['commentCountForTopic'];
            $data['closeDiscussion'] = $result['closeDiscussion'];
            $data['topic_messages'] = $result['topic_messages'];
            $data['topic_replies'] = $result['topic_replies'];
            $data['userProfile'] = site_url('getUserProfile').'/';
            $data['replyTrackingKey'] = $replyTrackingKey;
            $data['page'] = $page;
            $data['fromOthers'] = 'blog';
            $data['userImageURLDisplay'] = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable_v1.gif';
            $data['displayName'] = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
            $userGroup = isset($validateuser[0]['usergroup']) ? $validateuser[0]['usergroup'] : 'normal';
            $data['userGroup'] = ($userGroup == 'cms') ? 'normal' : $userGroup;
            $this->load->view('mAnA5/innerCommentReplyThread',$data);
        }   
    }
}

?>
