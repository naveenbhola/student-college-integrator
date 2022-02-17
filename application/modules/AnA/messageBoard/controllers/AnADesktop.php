<?php

class AnADesktop extends MX_Controller {
	
	private $dateCheckForAnAStart       = '2008-05-12';
    private $dateCheckForDiscussionStart= '2010-12-08';

    private function _init(){

        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->load->helper(array('mAnA5/ana','image','shikshautility'));
        $this->load->config('DesktopSiteTracking');
    }

    public function getHomePage($pageType = 'home'){
        $this->_init();
        $currentUrl = getCurrentPageURLWithoutQueryParams();
        $APIClient = $this->load->library("APIClient");
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $displayData['userStatus'] = $this->userStatus;
        $displayData['userIdOfLoginUser']    = $userId;
        $displayData['userGroup']            = $userGroup;
        $displayData['validateuser'] = $this->userStatus;
        $startPage = 0;
        $startPaginationIndex = 0;

        /**
         * XSS Clean of all POST and GET data
         */
        $pageType = $this->security->xss_clean($pageType);

        //Fixed VA issue SHIK-787
        if(!in_array($pageType, array('home','discussion','unanswered'))){
            $askHomeUrl = SHIKSHA_ASK_HOME;
            header("Location: $askHomeUrl",TRUE,301);exit;
        }

        $nextPageNo = $this->input->post('nextPageNo', true);
        $nextPaginationIndex = $this->input->post('nextPaginationIndex', true);
        $callType = $this->input->post('callType', true);

        //$currentPageNo = isset($nextPageNo)?$nextPageNo:$startPage;
        //$currentPaginationIndex = isset($nextPaginationIndex)?$nextPaginationIndex:$startPaginationIndex;
        $currentPageNo = isset($_POST['nextPageNo'])?$this->input->post('nextPageNo', true):$startPage;
        $currentPaginationIndex = isset($_POST['nextPaginationIndex'])?$this->input->post('nextPaginationIndex', true):$startPaginationIndex;

        $callType = isset($callType)?$callType:'NONAJAX';
        $displayData['callType'] = $callType;

        $APIClient->setUserId($userId);

        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        //$APIClient->setRequestData(array("text"=>"mba", "type" => "tag" ,"count" => 10));
        $jsonDecodedData =$APIClient->getAPIData("AnA/getHomepageData/".$currentPaginationIndex."/".$currentPageNo."/".$pageType);
        $displayData['data'] = $jsonDecodedData;        

        $displayData['data'] = $jsonDecodedData;
        
        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AnAHome','pageType'=>$pageType);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        if(!empty($displayData['data']['homepage']))
        {
        foreach($displayData['data']['homepage'] as $k=>$hpd)
        {
            foreach($hpd['tags'] as $x=>$y)
            {
                $tagsArr[$y['tagId']]=$y['tagName'];
            }
            $qids[]=$hpd['questionId'];
        }
            $this->load->library('messageBoard/TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping(); 

            $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($tagsArr);
            // $tagTypeInfo = $tagUrlMappingObj->getTagsContentType($tagsArr,$qids);
            //_p($tagUrlInfo);die;
            foreach($displayData['data']['homepage'] as $k=>$hpd)
            {
                foreach($hpd['tags'] as $x=>$y)
                {
                    if(is_array($tagUrlInfo[$y['tagId']]))
                    {
                        $url = $tagUrlInfo[$y['tagId']]['url'];
                    }
                    else
                    {
                        $url = getSeoUrl($y['tagId'], 'tag', $y['tagName']);    
                    }
                    $displayData['data']['homepage'][$k]['tags'][$x]['type']=$tagUrlInfo[$y['tagId']]['type'];
                    // $displayData['data']['homepage'][$k]['tags'][$x]['tag_type'] = $tagTypeInfo[$hpd['questionId']][$y['tagId']];
                    $displayData['data']['homepage'][$k]['tags'][$x]['url']=$url;
                }
            }
        }


        //_p($displayData['data']['homepage']);die;

        $displayData['nextPaginationIndex'] = $jsonDecodedData['nextPaginationIndex'];
        $displayData['nextPageNo']  = $currentPageNo + 1;
        $displayData['pageType']  = $pageType;
        $seoDetails = $this->_getSeoDetails($pageType);

        $displayData['seoTitle'] = $seoDetails['title'];
        $displayData['metaDescription'] = $seoDetails['description'];
        $displayData['canonicalURL'] = $seoDetails['canonicalURL'];
        $displayData['suggestorPageName'] = "all_tags";
        $displayData['viewTrackParams'] = $jsonDecodedData['viewTrackParams'];
        $displayData['showRightRegirationWidget'] = true;
        $displayData['showExpertsPanelLink'] = true;

        //for beacon tracking purpose
        if($pageType == "home"){
            $displayData['trackingpageIdentifier']= D_QnA_PAGEVIEW;    
        }
        else if($pageType == "discussion"){
            $displayData['trackingpageIdentifier']= D_DISC_PAGEVIEW; 
        }
        else if($pageType == "unanswered"){
            $displayData['trackingpageIdentifier']= D_UANS_PAGEVIEW;   
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
        
        //below line is used for pageview tracking purpose
        $this->load->library('common/trackingpages');
        $this->trackingpages->_pagetracking($displayData);

        $this->getMISGATrackingDetails($displayData,'homePage',$pageType);
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';
	    $displayData['pageName'] = 'ANAHomepage';

        $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
        if($callType == 'AJAX'){
            $this->load->view('desktopNew/homepageTabsData',$displayData);
        }else {
            $this->load->view('desktopNew/homepage',$displayData);
        }
        
    }

    private function _getSeoDetails($pageType, $pageNumber, $date){
        $displayData = array();
        $dateTimeObject = DateTime::createFromFormat('Y-m-d', $date);
        switch($pageType){
        	case	'home'					:	$displayData['title'] = " Shiksha Ask and Answer: Education and Career Community";
												$displayData['description']   = "Connect with thousands of career experts, counsellors, and students online. Ask & answer questions on careers, colleges, exams, courses, education trends etc.";
												$displayData['canonicalURL']      = SHIKSHA_ASK_HOME;
												break;
	        case	'discussion'			:	$displayData['title'] = " Shiksha Ask and Answer: Education and Career Community";
												$displayData['description']   = "Connect with thousands of career experts, counsellors, and students online. Ask & answer questions on careers, colleges, exams, courses, education trends etc.";
												$displayData['canonicalURL']      = SHIKSHA_ASK_HOME_URL."/discussions";
												break;
	        case	'unanswered'			:	$displayData['title'] = " Shiksha Ask and Answer: Education and Career Community";
												$displayData['description']   = "Connect with thousands of career experts, counsellors, and students online. Ask & answer questions on careers, colleges, exams, courses, education trends etc.";
												$displayData['canonicalURL']      = SHIKSHA_ASK_HOME_URL."/unanswers";
												break;
            case	'ALL_QUESTION_PAGE'		:	if(empty($date)){
            										$displayData['title']		= "All Questions on Education and Career - Shiksha.com";
            										$displayData['description'] = "Explore latest questions on education and career. Join this community to connect with thousands of career experts, counsellors, and students online.";
									            }else{
									            	$displayData['title']		= "All Questions asked on ".$dateTimeObject->format('jS F, Y')." on Education and Career - Shiksha.com";
									            	$displayData['description'] = "Questions asked on ".$dateTimeObject->format('jS F, Y').". Explore latest questions on education and career. Join this community to connect with thousands of career experts, counsellors, and students online.";
									            }
									            if($pageNumber > 1){
									            	$displayData['title']		= "Page ".$pageNumber." - ".$displayData['title'];
									            	$displayData['description'] = "Page ".$pageNumber." - ".$displayData['description'];
									            }
												break;
            case	'ALL_DISCUSSION_PAGE'	:	if(empty($date)){
									            	$displayData['title']		= "All Discussions on Education and Career - Shiksha.com";
									            	$displayData['description'] = "View all discussions related to education, career guidance, colleges/universities, courses, exams, admission, placements, and more.";
									            }else{
									            	$displayData['title']		= "All Discussions started on ".$dateTimeObject->format('jS F, Y')." on Education and Career - Shiksha.com";
									            	$displayData['description'] = "Discussions started on ".$dateTimeObject->format('jS F, Y').". View all discussions related to education, career guidance, colleges/universities, courses, exams, admission, placements, and more.";
									            }
									            if($pageNumber > 1){
									            	$displayData['title']		= "Page ".$pageNumber." - ".$displayData['title'];
									            	$displayData['description'] = "Page ".$pageNumber." - ".$displayData['description'];
									            }
												break;
        }
        return $displayData;
    }

	function getQuestionDiscussionDetailPage($entityId,$entityType,$pagenum){
        $this->benchmark->mark('loading_dependencies_start');
        $this->_init();
        $this->benchmark->mark('loading_dependencies_end');

        $this->benchmark->mark('redirection_logic_start');
        $urlseg = $this->uri->segment(1);
        $url_segments = explode("-", $urlseg);
        if ($url_segments[0] != 'getTopicDetail') {
                $i = 0;
                $value = 1;
                foreach ($url_segments as $arr)
                {
                    if($arr == 'dscns')
                    {
                        $entityType == 'discussion';
                    }
                    if ($arr == 'qna' || $arr == 'dscns' || $arr == 'ancmt')
                    {
                            $value = $i;
                    }
                    $i++;
                }

	        //In case of Announcements, display a 410 Error
        	if($url_segments[$value] == "ancmt"){
                	show_410();
	                exit;
        	}

            $topicId        =   $url_segments[($value)+1];
            $seoDetails     =   $url_segments[($value)+2];
            $srcPage        =   $url_segments[($value)+3];
            $actionDone     =   $url_segments[($value)+4];
            $parmeterValues =   $url_segments[($value)+5];
            $start          =   $url_segments[($value)+6];
            $count          =   $url_segments[($value)+7];
            $filter         =   $url_segments[($value)+8];
            $redirectUrl = SHIKSHA_ASK_HOME_URL."/".implode("-",array_slice($url_segments, 0,$value+2));
        
        }else if($url_segments[0] == 'getTopicDetail'){
            $topicId = $this->uri->segment(2);
            $title = $this->uri->segment(3);
            $seoDetails = $this->uri->segment(5);
            $start  =   $this->uri->segment(7);
            $count  =   $this->uri->segment(8);
            $filter  =   $this->uri->segment(9);
            $entityType = '';
            $redirectUrl = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$topicId.'/'.$title;
        }

        if($start!='' && $count!=''){
            $pagenum = ceil($start/$count)+1;
            $entityId = $topicId;
        }
        if(isset($seoDetails) && $seoDetails!=''){

            if(isset($pagenum) && $pagenum > 1){
                $url = $redirectUrl.'/'.$pagenum;
            }else{
                $url = $redirectUrl;
            }
            header("Location: $url",TRUE,301);
            exit;
        }

        if(isset($pagenum) && ($pagenum == 1 || $pagenum <=0)){
            header("Location: $redirectUrl",TRUE,301);
            exit;
        }else if(isset($pagenum) && !is_numeric($pagenum)){
            header("Location: $redirectUrl",TRUE,301);
            exit;
        }

        $this->benchmark->mark('redirection_logic_end');

        if($pagenum <= 0){
            $pagenum = 1;
        }

        $displayData = array();
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $displayData['userStatus'] = $this->userStatus;
        $displayData['userIdOfLoginUser']    = $userId;
        $displayData['userGroup']            = $userGroup;
        $displayData['validateuser'] = $this->userStatus;

        if(isset($_GET['referenceEntityId']) &&  $_GET['referenceEntityId']!= ''){
            $referenceEntityId = $_GET['referenceEntityId'];
        }else{
            $referenceEntityId = 0;
        }

        $this->load->model('messageBoard/AnAModel');
        if($entityType == '' && $entityId != ''){
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


        if($entityType == 'question'){
            $sortCriteria = 'Upvotes'; 
            $count = 10;        
            $displayData['trackingpageIdentifier'] 		= D_QDP_PAGEVIEW;
            $displayData['qtrackingPageKeyId'] 			= D_QDP_ASK_QUES_ASKNOW_RIGHT_WIDGET;
            $displayData['raPTrackingPageKeyId'] 		= D_QDP_ABUSE_QUESTION;
            $displayData['raCTrackingPageKeyId'] 		= D_QDP_ABUSE_ANSWER;
            $displayData['atrackingPageKeyId']			= D_QDP_ANSWER_POST_WIDGET;
            $displayData['tupatrackingPageKeyId'] 		= D_QDP_TUP_ANSWER_TUPLE;
            $displayData['tdownatrackingPageKeyId'] 	= D_QDP_TDOWN_ANSWER_TUPLE;
            $displayData['ctrackingPageKeyId'] 			= D_QDP_COMMENT_POST_WIDGET;
            $displayData['qfollowTrackingPageKeyId'] 	= D_QDP_FOLLOW_QUES;
            $displayData['alTrackingPageKeyId'] 		= D_QDP_ANSWER_LATER;
            $displayData['flistfollowTrackingPageKeyId']= D_QDP_FOLLOW_USER_FOLLOWER_LIST;
            $displayData['uplistfollowTrackingPageKeyId']= D_QDP_FOLLOW_USER_UPVOTERS_LIST;
            $displayData['fuRightActiveTrackingPageKeyId']= D_QDP_FOLLOW_USER_RIGHT_MOST_ACTIVE;
            $displayData['fuRightTopTrackingPageKeyId']   = D_QDP_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI;
	    $displayData['regRightTrackingPageKeyId']   = D_QDP_RIGHT_REGISTRATION;

            $displayData['GA_currentPage']              = 'QUESTION DETAIL PAGE';
            $displayData['GA_Tap_On_Right_All_Ques']    = 'ALLQUESTIONS_RHSWIDGET';
            $displayData['GA_Tap_On_Right_All_Disc']    = 'ALLDISCUSSIONS_RHSWIDGET';
            $displayData['GA_Tap_On_Right_All_Tags']    = 'ALLTAGS_RHSWIDGET';
            $displayData['GA_Tap_On_Right_Community']   = 'COMMUNITYGUIDELINES_RHSWIDGET';
            $displayData['GA_Tap_On_Right_User_Point']  = 'USERPOINT_RHSWIDGET';
            $displayData['GA_Tap_On_What_Question']     = 'ASK_WIDGET';
	    $displayData['GA_Tap_On_Right_Reg_CTA']	= 'RIGHT_REGN';

            $paginationPage = 'qdp';

            $this->load->config('messageBoard/MessageBoardInternalConfig');
            $moderatorUserIds = $this->config->item('moderatorUserIds');
            $isUserModerator = false;

            if(in_array($userId, $moderatorUserIds))
            {
                $isUserModerator = true;
            }
            $displayData['isUserModerator'] = $isUserModerator;


        }else if($entityType == 'discussion'){
             $sortCriteria = 'Latest';
             $count = 20;
             $displayData['trackingpageIdentifier'] 		= D_DDP_PAGEVIEW;
             $displayData['qtrackingPageKeyId'] 			= D_DDP_ASK_QUES_ASKNOW_BOTTOM_WIDGET;
             $displayData['raPTrackingPageKeyId'] 			= D_DDP_ABUSE_DISCUSSION;
             $displayData['raCTrackingPageKeyId'] 			= D_DDP_ABUSE_DISCCOMMENT;
             $displayData['dctrackingPageKeyId'] 			= D_DDP_DCOMMENT_POST_WIDGET;
             $displayData['tupdctrackingPageKeyId'] 		= D_DDP_TUP_DCOMMENT_TUPLE;
             $displayData['tdowndctrackingPageKeyId'] 		= D_DDP_TDOWN_DCOMMENT_TUPLE;
             $displayData['drtrackingPageKeyId'] 			= D_DDP_REPLY_POST_WIDGET;
             $displayData['dfollowTrackingPageKeyId'] 		= D_DDP_FOLLOW_DISC;
             $displayData['dclTrackingPageKeyId'] 			= D_DDP_COMMENT_LATER;
             $displayData['flistfollowTrackingPageKeyId'] 	= D_DDP_FOLLOW_USER_FOLLOWER_LIST;
             $displayData['uplistfollowTrackingPageKeyId'] 	= D_DDP_FOLLOW_USER_UPVOTERS_LIST;
             $displayData['fuRightActiveTrackingPageKeyId']	= D_DDP_FOLLOW_USER_RIGHT_MOST_ACTIVE;
             $displayData['fuRightTopTrackingPageKeyId']    = D_DDP_FOLLOW_USER_RIGHT_SIDE_TOP_CONTRI;
	     $displayData['regRightTrackingPageKeyId']   = D_DDP_RIGHT_REGISTRATION;

            $displayData['GA_currentPage']              = 'DISCUSSION DETAIL PAGE';
            $displayData['GA_Tap_On_Right_All_Ques']    = 'ALLQUESTIONS_RHSWIDGET';
            $displayData['GA_Tap_On_Right_All_Disc']    = 'ALLDISCUSSIONS_RHSWIDGET';
            $displayData['GA_Tap_On_Right_All_Tags']    = 'ALLTAGS_RHSWIDGET';
            $displayData['GA_Tap_On_Right_Community']   = 'COMMUNITYGUIDELINES_RHSWIDGET';
            $displayData['GA_Tap_On_Right_User_Point']  = 'USERPOINT_RHSWIDGET';
            $displayData['GA_Tap_On_What_Question']     = 'ASK_WIDGET';
	    $displayData['GA_Tap_On_Right_Reg_CTA']     = 'RIGHT_REGN';

            $paginationPage = 'ddp';
        }

        $start= ($pagenum-1)*($count);
        
        $callType = isset($_POST['callType'])?$this->input->post('callType'):'NONAJAX';
        $entityId = isset($_POST['entityId'])?$this->input->post('entityId'):$entityId;
        $start = isset($_POST['start'])?$this->input->post('start'):$start;
        $count = isset($_POST['count'])?$this->input->post('count'):$count;
        $entityType = isset($_POST['entityType'])?$this->input->post('entityType'):$entityType;
        $pagenum = isset($_POST['pagenum'])?$this->input->post('pagenum'):$pagenum;        


        if(isset($_POST['sortOrder'])){
            $sortOrder = $this->input->post('sortOrder');
        }else if(isset($_GET['sortOrder'])){
             $sortOrder = $_GET['sortOrder'];
        }else if(isset($filter) && $filter!=''){
             $sortOrder = $filter;
        }else{
            $sortOrder = $sortCriteria;
         }
         

        /* Redirect to homepage incase entity is empty*/
        if ($entityId=='' || $entityId==0) {
            $url = SHIKSHA_ASK_HOME;
            if(strpos($url, "https") === 0){
                header("Location: $url",TRUE,301);
            }
            exit;
         }

      
        $displayData['callType'] = $callType;
        $displayData['start'] = $start;
        $displayData['count'] = $count;
        $displayData['entityId'] = $entityId;
        $displayData['sortOrder'] = $sortOrder;
        $displayData['referenceAnswerId'] = $referenceEntityId;
        $displayData['entityType'] = $entityType;
        $displayData['suggestorPageName'] = "all_tags";

        $this->benchmark->mark('Api_call_start');

        //Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        

        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        
        if($entityType == 'question'){
            $apiUrl = "AnA/getQuestionDetailWithAnswers/";
            $urlContent = 'qna'; 
            //$moderatorUserId = $this->AnAModel->checkIfUserisPowerUser($userId);
            //if(($userId == $moderatorUserId && $moderatorUserId != '') || ($userId == 11)){
            if($userId == 11){
                $displayData['isModerator'] = true;
            }
        }else if($entityType == 'discussion'){
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
        if ($pagenum <= 1 && $callType != 'AJAX'){
	    $url = getSeoUrl($entityId,$entityType);
            $enteredURL = getCurrentPageURLWithoutQueryParams();
        
            if($url!='' && $url!=$enteredURL){
                $queryStr = $this->input->server('QUERY_STRING',true);
                 if($queryStr!='' && $queryStr!=NULL){
                     $url = $url."?".$queryStr;
                     header("Location: $url",TRUE,301);
                 }
                 else
                     header("Location: $url",TRUE,301);
                 exit;
            }
        }
        //End code for Checking URL


        $jsonDecodedData =$APIClient->getAPIData($apiUrl.$entityId."/".$start."/".$count.'/'.$sortOrder.'/'.$referenceEntityId);

            foreach($jsonDecodedData['entityDetails']['tagsDetail'] as $k=>$v)
            {
                $tagIdsForUrl[$v['tagId']]=$v['tagName'];
            }        
            $this->load->library('TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping(); 

            //getting chp url for tagIds exist on hierarchy combination
            $this->load->library('AnALibrary');
            $chpTagUrls = $this->analibrary->getChpUrlForTagsExistOnHierarchies(array_keys($tagIdsForUrl));

            $notExistChpUrlTags = array();
            foreach ($tagIdsForUrl as $key => $value) {
                if(!array_key_exists($key, $chpTagUrls))
                {
                    $notExistChpUrlTags[$key] = $value;
                }
            }
            if(!empty($notExistChpUrlTags))
            {
                $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($notExistChpUrlTags);    
            }
            
            $tagTypeInfo = $tagUrlMappingObj->getTagsContentType($tagIdsForUrl,$jsonDecodedData['entityDetails']['msgId']);

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

            //_p($jsonDecodedData['entityDetails']['tagsDetail']);
            $jsonDecodedData['entityDetails']['tagsDetail'] = TagUrlMapping::sortTags($jsonDecodedData['entityDetails']['tagsDetail']);

        $displayData['data'] = $jsonDecodedData;

         $this->benchmark->mark('Api_call_end');

        if($entityType == 'question'){
            if(!empty($jsonDecodedData['entityDetails']['description'])){
                $metaDescription = substr($jsonDecodedData['entityDetails']['description'],0,150);
            }else{
                $metaDescription = 'Read all answers to question:'.substr($jsonDecodedData['entityDetails']['title'],0,150);
            }
            
            //check if question has college/university tag
            $questAllTags = $jsonDecodedData['entityDetails']['tagsDetail'];

            foreach($questAllTags as $k=>$v)
            {
                $tagIds[] = $v['tagId'];
                $dfpTags[] = $v['tagName'];
            }
            $this->load->library('TagUrlMapping');
            $tagUrlMappingObj = new TagUrlMapping(); 

            $tagUrlInfo = $tagUrlMappingObj->getTagsUrl($tagIds);

            foreach($jsonDecodedData['entityDetails']['tagsDetail'] as $k=>$v)
            {
                if(is_array($tagUrlInfo[$v['tagId']]))
                {
                    $url = $tagUrlInfo[$v['tagId']]['url'];
                }
                else
                {
                    $url = getSeoUrl($v['tagId'], 'tag', $v['tagName']);    
                }
                $jsonDecodedData['entityDetails']['tagsDetail'][$k]['url']=$url;
            }
            if(count($questAllTags)>0){
                foreach ($questAllTags as $key => $value) {
                    $allTags[] = $value['tagId'];    
                }
                $tagArr = $this->AnAModel->getTagsType($allTags);
                $displayData['isCollegeTagRemoved'] = ($tagArr[0]['tag_id']) ? true : false;
            }

            $displayData['GA_currentPage'] = 'QUESTION DETAIL PAGE';
            $displayData['GA_commonCTA_name'] = '_QUESTIONDETAIL_DESKAnA';
        }else if($entityType == 'discussion'){
            if(!empty($jsonDecodedData['entityDetails']['description'])){
                $metaDescription = substr($jsonDecodedData['entityDetails']['description'],0,150).'.'.'Join this education community for any queries on careers, colleges, placements, admission, cut-offs, fees etc.';
            }else{
                 $metaDescription = substr($jsonDecodedData['entityDetails']['title'],0,150).'.'.'Join this education community for any queries on careers, colleges, placements, admission, cut-offs, fees etc.';
            }
            $displayData['GA_currentPage'] = 'DISCUSSION DETAIL PAGE';
            $displayData['GA_commonCTA_name'] = '_DISCUSSIONDETAIL_DESKAnA';
        }

        /* Redirect to homepage incase entity does'nt exist*/
       
        if (empty($jsonDecodedData['entityDetails']) || !array_key_exists('msgId',$jsonDecodedData['entityDetails'])){
            $url = SHIKSHA_ASK_HOME;
            header("Location: $url",TRUE,301);
            exit;
         }

         $this->benchmark->mark('get_related_linked_entity_start');
         /*get Linked Discussions incase of discussion*/
         if($callType == "NONAJAX"){
            $displayData['linkedData'] = $this->getRelatedLinkedQuestions($jsonDecodedData['entityDetails']['threadId'],$entityType,'NONAJAX');
         }
          $this->benchmark->mark('get_related_linked_entity_end');
         /* Get ACL status whether user has rights to edit link thread */
         if ($callType=='NONAJAX'){ // Needs to be computed at time of page rendering.
         	$displayData['showLinkThread'] = FALSE;
         	$userRightsToCheck = array('LinkQuestion','DelinkQuestion','LinkDiscussion','DelinkDiscussion');
         	foreach ($userRightsToCheck as $value){
         		$displayData['ACLStatus'][$value] = 'False';
         	}
         	if($userId > 0){
         		$this->load->model('AnAModel');
                 $this->benchmark->mark('get_linked_thread_count_start');
         		//$linkQuestionDiscussionCount= $this->AnAModel->getLinkedThreadsCount($entityId, $entityType, array('accepted'));
         		$linkQuestionDiscussionCount = 0;
         		
                 $this->benchmark->mark('get_linked_thread_count_end');
         		if(($entityType == 'discussion' && $linkQuestionDiscussionCount < 1) || ($entityType == 'question' && $linkQuestionDiscussionCount < 10)){
         			//$this->load->library('acl_client');
         			//$displayData['ACLStatus']	=	$this->acl_client->checkUserRight($userId,$userRightsToCheck);
                                $this->load->model('acl_model');
                                //$displayData['ACLStatus'] = $this->acl_model->checkUserRight($userId,$userRightsToCheck);
                    $displayData['ACLStatus'] = array();

                    /*
         			$displayData['showLinkThread'] = TRUE;
         			if($entityType == 'question' && $displayData['ACLStatus']['LinkQuestion'] == 'live'){
         				$displayData['data']['entityDetails']['overflowTabs'][]	=	array('id' => 201, 'label' => 'Link Question');
         			}elseif($entityType == 'discussion' && $displayData['ACLStatus']['LinkDiscussion'] == 'live'){
         				$displayData['data']['entityDetails']['overflowTabs'][]	=	array('id' => 201, 'label' => 'Link Discussion');
         			}
         			*/
         		}
         	}
         }

         /*get pagination view*/
         $referenceString=($referenceEntityId>0)?'referenceEntityId='.$referenceEntityId:'';

         if($entityType == 'question' && $sortOrder == 'Upvotes'){
                $sortstring = '';
         }else if($entityType == 'discussion' && $sortOrder == 'Latest'){
                $sortstring = '';
         }else{
                $sortstring = 'sortOrder='.$sortOrder;
         }

         if($referenceString > 0 && $sortstring != ''){
            $queryString = '?'.$sortstring.'&'.$referenceString;
         }else if($referenceString > 0){
            $queryString = '?'.$referenceString;
         }else if($sortstring != ''){
            $queryString = '?'.$sortstring;
         }
        
         $baseUrl = $jsonDecodedData['entityDetails']['shareUrl'];
         $currentUrl = $baseUrl.'/@pagenum@'.$queryString;

         $totalCount = $jsonDecodedData['entityDetails']['childCount'];

         $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';
         
         $displayData['paginationHtml'] = doPagination_AnA($totalCount,$currentUrl,$pagenum,$count,$numPages=10,$paginationPage,$displayData['GA_userLevel']);

        
        /* Seo url Details */
        //$this->load->library('Seo_client');
        //$Seo_client = new Seo_client();
        //$flag_seo_url = $Seo_client->getSeoUrlNewSchema($entityId,$entityType);
        //$title = $flag_seo_url[1];
	$title = $jsonDecodedData['entityDetails']['title'];

        $title = seo_url_lowercase($title,'-','150');
        $metaTitle = seo_url_lowercase($title,' ','150');
        if($pagenum > 1){
            $pageString = Page.' '.$pagenum.' - ';
        }
        $displayData['metaTitle'] = $pageString.$metaTitle." - Shiksha.com";
        $displayData['metaDescription'] =$pageString.htmlentities($metaDescription);
        $displayData['metKeywords']      = ' ';
        if ($url_segments[0] != 'getTopicDetail')
        {
            $displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/".$title."-".$urlContent."-".$entityId;
	    $displayData['alternate'] = "android-app://com.shiksha.android/https/".$_SERVER['SERVER_NAME']."/".$title."-".$urlContent."-".$entityId;
        }else{
            $displayData['canonicalURL'] = SHIKSHA_ASK_HOME."/getTopicDetail/".$entityId."/".$title;
	    $displayData['alternate'] = "android-app://com.shiksha.android/https/".$_SERVER['SERVER_NAME']."/getTopicDetail/".$entityId."/".$title;
        }
        
        $displayData['app_linking']['android_facebook']['app_name']        = "Shiksha Ask & Answer";
        $displayData['app_linking']['android_facebook']['package']         = "com.shiksha.android";
        $displayData['app_linking']['android_facebook']['should_fallback'] = "true";
        $displayData['app_linking']['android_facebook']['url']             = $displayData['canonicalURL'];
        
        $displayData['app_linking']['android_twitter']['app_name']         = "Shiksha Ask & Answer";
        $displayData['app_linking']['android_twitter']['app_id']           = "com.shiksha.android";
        $displayData['app_linking']['android_twitter']['url']              = $displayData['canonicalURL'];
       

        $totalpages = ceil($totalCount/$count);
        if($pagenum<=1){
            if($totalpages >1){
                $displayData['nextURL'] = $baseUrl.'/'.($pagenum+1);
            }
        }else{
            if($pagenum < $totalpages){
                $displayData['nextURL'] = $baseUrl.'/'.($pagenum+1);
                $displayData['prevURL'] = $baseUrl.'/'.($pagenum-1);
                $displayData['canonicalURL'] = $baseUrl;
            }else if($pagenum = $totalpages){
                $displayData['prevURL'] = $baseUrl.'/'.($pagenum-1);
                $displayData['canonicalURL'] = $baseUrl;
            }
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

        //below line is used for pageview tracking purpose
        $tracking = $this->load->library('common/trackingpages');
        $displayData['trackingpageNo'] = $entityId;

        $tracking->_pagetracking($displayData);
        $displayData['pageType'] = $entityType;
        $displayData['viewTrackParams'] = $jsonDecodedData['viewTrackParams'];                
        $displayData['showRightBanner'] = true;
        $displayData['pageName'] = "QDP";

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_QuestionDetailPage','entity_id'=>$entityId,'pageType'=>$entityType,'anaTags'=>$dfpTags);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        //get popular tags
        $this->benchmark->mark('get_tags_start');
        $this->load->library('Tagging/TaggingLib'); 
        $taggingLib = new TaggingLib(); 
        $tagsResult = $taggingLib->getTagsFromSolr(5);
        $this->benchmark->mark('get_tags_end');
        foreach ($tagsResult as $key => $value) {
            $url = getSeoUrl($value['tag_id'], 'tag', $value['tag_name']);    
            $tagsResult[$key]['url'] = $url;
        }
        foreach ($jsonDecodedData['entityDetails']['tagsDetail'] as $key => $value) {
            $tagIds[] = $value['tagId'];
        }
        $displayData['tags'] = $tagsResult;
        $displayData['tagIds'] = $tagIds;
        if($entityType == 'question'){
            $displayData['tagRHSwidgetData'] =  $taggingLib->checkTagTypeToShowRHSWidget($tagIds);
        }

        $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');

        $answerIdToEdit = !empty($_GET['answerId']) ? $this->input->get('answerId') : 0;
        $openAnsBox = !empty($_GET['openAnsBox']) ? $this->input->get('openAnsBox') : 0;
        $displayData['answerIdToEdit'] = $answerIdToEdit;
        $displayData['openAnsBox'] = $openAnsBox;
        $displayData['showFeedbackLayer']=0;
        if($userId > 0 && $entityType == 'question' && $displayData['userIdOfLoginUser']==$displayData['data']['entityDetails']['userId'])
        {
            $this->load->model('messageBoard/AnAModel');
            $feedbackCount = $this->AnAModel->getFeedbackCount($displayData['entityId'],$userId);
            $displayData['showFeedbackLayer']=0;
            if($feedbackCount==0)
            {
                $feedbackLayerDisplayCount = $this->AnAModel->getFeedbackLayerDisplayCount($displayData['entityId'],$userId);
                if(($displayData['userIdOfLoginUser']==$displayData['data']['entityDetails']['userId']) &&
                ($displayData['data']['entityDetails']['childCount']>0) && ($feedbackLayerDisplayCount<3))
                {
                    $displayData['showFeedbackLayer']=1;
                    if($pagenum==1)
                    {
                        $displayData['lastAnswerId'] = $displayData['data']['childDetails'][0]['msgId'];
                    }
                    else
                    {
                        $displayData['lastAnswerId'] = 0;
                    }
                    $displayData['numberOfAnswers']=$displayData['data']['entityDetails']['childCount'];
                }
            }
        }
        if($callType=='AJAX'){
            echo $this->load->view('desktopNew/answerCommentDetailContent',$displayData);
        }else{
            $this->load->view('desktopNew/entityDetailPage',$displayData);
        }
        
    }

    /*function getRelatedLinkedQuestions($entityId,$entityType,$callType ='moduleRun'){

        $this->_init();
        //Get the Input variables from Post/Get

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData = array();
        $displayData['userIdOfLoginUser']    = $userId;

        $displayData['entityId'] = $entityId;
        $displayData['entityType'] = $entityType;

        //Make a CURL call to fetch the data
        $APIClient = $this->load->library("APIClient");        
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        
        $jsonDecodedData =$APIClient->getAPIData("Search/getLinkedAndRelatedThread/".$entityId."/".$entityType);

        if(!empty($jsonDecodedData['linkedEntities'])){
            foreach($jsonDecodedData['linkedEntities'] as $key=>$linkedEntity){
                $jsonDecodedData['linkedEntities'][$key]['url'] = getSeoUrl($linkedEntity['threadId'], $entityType, $linkedEntity['title'], array(), "NA", $linkedEntity['creationDate']);
            }
        }

        if(!empty($jsonDecodedData['related'])){
            foreach($jsonDecodedData['related'] as $key=>$relatedEntity){
                $jsonDecodedData['related'][$key]['url'] = getSeoUrl($relatedEntity['threadId'], $entityType, $relatedEntity['title'], array(), "NA", $relatedEntity['creationDate']);
            }
        }

        $displayData['data'] = $jsonDecodedData;
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
        if($callType == 'moduleRun'){
            $this->load->view('desktopNew/linkedAndRelatedEntityTuple',$displayData);
        }else{
            return $jsonDecodedData;
        }

    }
*/
    /**
     * @desc API to Log share entity in the DB
     * @param POST parameters userId, entityId, entityType, destination (FB/Twitter/Google)
     * @return TRUE
     * @date 2016-06-10
     * @author Ankur Gupta
     */
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

        return true;
    } 

	/**
    @ Below Function is used for getting the list of Users those are upvoted or followed on particular entityId
    @ POST Param: EntityId : this is the id of entity type on which user performed action like upvote/follow
    @ POST Param: actionType: specify getting list of Upvoters/Followers Users
    @ POST Param: entityType which is the type of entity.This can be question/discussion.
    @ return result - If Success send list of users in array format/Fail send an empty string
    */
    function getListOfUsersUpvotedFollowed()
    {
        $this->_init();
        //Post Params
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'0';
        $entityId     = isset($_POST['entityId'])?$this->input->post('entityId'):'0';
        $start        = isset($_POST['start'])?$this->input->post('start'):'0';
        $count        = isset($_POST['count'])?$this->input->post('count'):'10';
        $entityType   = isset($_POST['entityType'])?$this->input->post('entityType'):'question';
        $actionType   = isset($_POST['actionType'])?$this->input->post('actionType'):'follow';
        $trackingPageKeyId = isset($_POST['trackingPageKeyId'])?$this->input->post('trackingPageKeyId'):'';

        //CURL call to API for getting data
        $this->load->library("APIClient");
        $APIClient = new APIClient();
        $APIClient->setUserId($userId);
        
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $apiUrl = "AnA/getListOfUsersBasedOnAction";
        $jsonDecodedData = $APIClient->getAPIData($apiUrl."/".$entityId."/".$start."/".$count."/".$actionType."/".$entityType);

        $displayData['loggedInUser'] =  $userId ;        
        $displayData['threadType']   = $entityType;
        $displayData['startIndex'] 	 = $start;
        $displayData['countIndex'] 	 = $count;
        $displayData['actionType'] 	 = $actionType;
        $displayData['pageNoUserList'] = ($start/$count)+1;
        $displayData['entityId'] 	 = $entityId;
        $displayData['trackingPageKeyId'] = $trackingPageKeyId;
        $displayData['GA_userLevel']  = $userId > 0 ? 'Logged In':'Non-Logged In';
        if(isset($jsonDecodedData['users']))
        {
            $displayData['userList'] = $jsonDecodedData['users'];
        }
        echo $this->load->view('desktopNew/listOfUsers',$displayData);
    }
    
    public function getCommentDetails($parentId){
    	$this->_init();
    	$userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    	$displayData = array();
    	
    	$startIndex = 0;
    	$countIndex = 10;
    	$parentId	= ($this->input->post('parentId') != '')?$this->input->post('parentId'):$parentId;
    	$startIndex	= ($this->input->post('startIndex') != '')?$this->input->post('startIndex'):$startIndex;
    	$countIndex	= ($this->input->post('countIndex') != '')?$this->input->post('countIndex'):$countIndex;
    	$entityType	= ($this->input->post('entityType') != '')?$this->input->post('entityType'):'question';
    	if($entityType == 'question'){
    		$childType = 'Comment';
    		$apiUrl = "AnA/getCommentDetails/";
            $raTrackingPageKeyId = D_QDP_ABUSE_COMMENT;
    	}else if($entityType == 'discussion'){
    		$childType = 'Reply';
    		$apiUrl = "AnA/getReplyDetails/";
            $raTrackingPageKeyId = D_DDP_ABUSE_DISCREPLY;
    	}else{
    		echo 'FAIL';
    		return;
    	}
    	
    	//Make a CURL call to fetch the data
    	$APIClient = $this->load->library("APIClient");
    	$APIClient->setUserId($userId);
    	$APIClient->setVisitorId(getVisitorId());
    	$APIClient->setRequestType("get");
    	
    	$jsonDecodedData =$APIClient->getAPIData($apiUrl.$parentId.'/'.$startIndex.'/'.$countIndex);
    	
    	$displayData = array();
    	$displayData['data'] = $jsonDecodedData;
    	$displayData['startIndex']	= $startIndex;
    	$displayData['parentId']	= $parentId;
    	$displayData['entityType']	= $entityType;
        $displayData['raTrackingPageKeyId'] = $raTrackingPageKeyId;
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
    	echo $this->load->view('desktopNew/commentReplyLayer',$displayData);
    }
    
    public function getAllQuestionDiscussionPage($pageType, $param1 = 0, $param2 = 0){
    	/* echo '<br/> pageType: '.$pageType;
    	echo '<br/> param1: '.$param1;
    	echo '<br/> param2: '.$param2;
    	die; */
    	$this->_init();
        $this->load->config("apiConfig");
    	if(!in_array($pageType, array('question','discussion'))){
    		show_404();
    		exit(0);
    	}
    	
    	$redirectToDefaultDetailPage = FALSE;
    	$pageNumber		= 1;
    	$dateForWhichThreadsToBePicked = date('Y-m-d');
    	$addDateInMetaInfo = FALSE;
    	if($pageType == 'question'){
    		$threadType = 'user';
    		$paginationURL = "/questions/";
    	}else{
    		$threadType = 'discussion';
    		$paginationURL = "/all-discussions/";
    	}
    	if( empty($param1) || (is_numeric($param1) && $param1 <= 0) ){
    		$param1 = 1;
    	}
    	$param1Length	= strlen($param1);
    	$param2Length	= strlen($param2);
        $startDateCheckForThreadPostingEpoch = 0;
        if($pageType == 'question'){
            $startDateCheckForThreadPostingEpoch = strtotime($this->dateCheckForAnAStart);
        }else {
            $startDateCheckForThreadPostingEpoch = strtotime($this->dateCheckForDiscussionStart);
        }
    	if($param1 > 0){
    		if($param1Length == 8){
    			$inputDate = DateTime::createFromFormat('dmY', $param1)->format('Y-m-d');
    			//$dateCheckForAnAStart = strtotime($this->dateCheckForAnAStart);
                if(strtotime($inputDate) < $startDateCheckForThreadPostingEpoch){
    				$redirectToDefaultDetailPage = TRUE;
    			}else{
    				$dateForWhichThreadsToBePicked = $inputDate;
    				if(strtotime($dateForWhichThreadsToBePicked) != strtotime(date('Y-m-d'))){
    					$addDateInMetaInfo = TRUE;
    				}
    				$paginationURL .= DateTime::createFromFormat('Y-m-d', $dateForWhichThreadsToBePicked)->format('dmY').'/';
    				if(empty($param2) || !ctype_digit($param2) || $param2 <= 0){
    					$pageNumber = 1;
    				}else{
    					$pageNumber = $param2;
    				}
    			}
    		}else{
    			if($param1 > 999999){
    				$redirectToDefaultDetailPage = TRUE;
    			}elseif ($param1 < 0){
    				$pageNumber = 1;
    			}else{
    				$pageNumber	= $param1;
    			}
    		}

        }
        else if(is_string($param1)){    //This is Category. In this case, redirect it to Tag detail page
            $categoryForLeftPanel = $this->getCategories();
            $categoryName = $param1;
            $categoryId = 0;
            foreach ($categoryForLeftPanel as $key => $value){
                    if( seo_url_lowercase($value[0],"-") == $categoryName){
                            $categoryId = $key;
                            $fullCategoryName = $value[0];
                    }
            }
            $this->config->load('messageBoard/CategoryTagMappingConfig');
            $categoryTag = $this->config->item('categoryTag');
            if(isset($categoryTag[$categoryId])){
                    $tagId = $categoryTag[$categoryId]['tagId'];
                    $tagName = $categoryTag[$categoryId]['tagName'];
                    $url = getSeoUrl($tagId, 'tag', $tagName);
	            header("Location: $url",TRUE,301);
        	    exit;
            }
            else{
                    $redirectToDefaultDetailPage = TRUE;
            }
    	}else{
    		$redirectToDefaultDetailPage = TRUE;
    	}
    	
    	$displayData	= array();
    	if($pageType == 'question'){
    		$displayData['pageType'] = 'ALL_QUESTION_PAGE';
            $displayData['trackingpageIdentifier'] = D_ALL_QUES_PAGEVIEW;
            $dpfParam = array('parentPage'=>'DFP_AllQuestion');
    	}else {
    		$displayData['pageType'] = 'ALL_DISCUSSION_PAGE';
            $displayData['trackingpageIdentifier'] = D_ALL_DISC_PAGEVIEW;
            $dpfParam = array('parentPage'=>'DFP_AllDiscussion');
    	}
    	
    	if($addDateInMetaInfo){
    		$seoURL	= $this->_getSeoURL($displayData['pageType'], $pageNumber, $dateForWhichThreadsToBePicked);
    	}else{
    		$seoURL	= $this->_getSeoURL($displayData['pageType'], $pageNumber);
    	}
    	if($redirectToDefaultDetailPage === TRUE || getCurrentPageURLWithoutQueryParams() != $seoURL){
    		header("Location: ".$seoURL,TRUE,301);
    		exit(0);
    	}

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
    	
    	$paginationURL	.= "@pagenum@";
    	$resultPerPage	= 20;
    	//echo ' pagination URL pattern : '.$paginationURL;
    	//echo '<br/ > dateForWhichThreadsToBePicked : '.$dateForWhichThreadsToBePicked." :: ".DateTime::createFromFormat('Y-m-d', $dateForWhichThreadsToBePicked)->format('dmY');
    	$this->load->library('AnALibrary');
    	$data = $this->analibrary->getRecentThreadsForAllQuestionDiscussion($this->userStatus[0]['userid'], $threadType, $dateForWhichThreadsToBePicked, $startDateCheckForThreadPostingEpoch, $pageNumber, $resultPerPage);
    	if((!is_array($data['homepage']) || count($data['homepage']) == 0)){ // In case of zero result page redirect to base page for given date
    		if($pageNumber > 1){
    			if($addDateInMetaInfo){
    				$seoURL	= $this->_getSeoURL($displayData['pageType'], 1, $dateForWhichThreadsToBePicked);
    			}else{
    				$seoURL	= $this->_getSeoURL($displayData['pageType'], 1);
    			}
    			header("Location: ".$seoURL,TRUE,301);
    			exit(0);
    		}else{
    			if($displayData['pageType'] == 'ALL_QUESTION_PAGE'){
    				$displayData['data']['zeroResultPageResponse'] = 'No Questions';
    			}else{
    				$displayData['data']['zeroResultPageResponse'] = 'No Discussions';
    			}
    		}
    	}
    	$displayData['data']['homepage']	= $data['homepage'];
    	if(is_array($this->userStatus) && $this->userStatus[0]['userid'] > 0){
    		$displayData['data']['userDetails'] = $this->analibrary->getUserDetails($this->userStatus[0]['userid']);
            $displayData['GA_userLevel'] = 'Logged In';
    	}
        else
            $displayData['GA_userLevel'] = 'Non-Logged In';
    	$displayData['datePagination']		= $data['datePagination'];
    	if($addDateInMetaInfo){
    		$seoDetails = $this->_getSeoDetails($displayData['pageType'], $pageNumber, $dateForWhichThreadsToBePicked);
    	}else{
    		$seoDetails = $this->_getSeoDetails($displayData['pageType'], $pageNumber);
    	}
        $displayData['userStatus'] = $this->userStatus;
    	$displayData['validateuser']	= $this->userStatus;
    	$displayData['seoTitle']		= $seoDetails['title'];
    	$displayData['metaDescription']	= $seoDetails['description'];
    	$displayData['canonicalURL']	= $this->_getSeoURLCanonical($displayData['pageType']);
    	$displayData['paginationURLPattern']= $paginationURL;
    	$displayData['suggestorPageName'] = "all_tags";
    	$displayData['totalRecords']		= $data['totalRecords'];
    	$displayData['resultPerPage']		= $resultPerPage;
    	$displayData['currentPage']			= $pageNumber;
        $displayData['showExpertsPanelLink'] = true;
        $displayData['showRightRegirationWidget'] = true;

        // set view count duration for question/answer tracking
        $displayData['viewTrackParams'] = array("question" => QUESTION_VIEW_DURATION, "answer" => ANSWER_VIEW_DURATION, "trackDuration" => VIEWCOUNT_TRACKING_INTERVAL, "trackingPageType" => "allThreadPage");

    	if(count($displayData['data']['homepage']) == 0){
    		$displayData['noIndexFollow']	= TRUE;
    	}
    	if($pageNumber > 1){
    		$displayData['previousURL']	= SHIKSHA_ASK_HOME_URL.str_replace("@pagenum@", ((($pageNumber - 1) == 1)?'':$pageNumber - 1), $paginationURL);
    	}
    	$displayData['nextURL']	= SHIKSHA_ASK_HOME_URL.str_replace("@pagenum@", ($pageNumber + 1), $paginationURL);
    	if(is_array($data['datePagination'])){
    		if(isset($data['datePagination']['nextDayPagination']['url'])){
    			//$displayData['nextURL'] = SHIKSHA_ASK_HOME_URL.$data['datePagination']['nextDayPagination']['url'];
    			$displayData['previousURL']	= SHIKSHA_ASK_HOME_URL.$data['datePagination']['nextDayPagination']['url'];
    		}
    		if(isset($data['datePagination']['previousDayPagination']['url'])){
    			//$displayData['previousURL'] = SHIKSHA_ASK_HOME_URL.$data['datePagination']['previousDayPagination']['url'];
    			$displayData['nextURL']	= SHIKSHA_ASK_HOME_URL.$data['datePagination']['previousDayPagination']['url'];
    		}
    	}
    	$displayData['previousURL'] = rtrim($displayData['previousURL'],'/');
    	$displayData['nextURL'] = rtrim($displayData['nextURL'],'/');
    	
        $this->getMISGATrackingDetails($displayData,'allPage',$pageType);

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

        //belo lines are used for beacon tracking purpose
        $this->load->library('common/trackingpages');
        $this->trackingpages->_pagetracking($displayData);

        $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');

        $this->load->view('desktopNew/homepage',$displayData);
    }
    
    private function _getSeoURL($pageType, $pageNumber, $date){
    	if(!empty($date)){
    		$dateTimeObject = DateTime::createFromFormat('Y-m-d', $date);
    	}
    	$url = "";
    	switch ($pageType){
    		case	'ALL_QUESTION_PAGE'		:	if(empty($date)){
									    			$url = SHIKSHA_ASK_HOME_URL."/questions";
									    		}else{
									    			$url = SHIKSHA_ASK_HOME_URL."/questions/".$dateTimeObject->format('dmY');
									    		}
									    		if($pageNumber > 1){
									    			$url = $url."/".$pageNumber;
									    		}
    											break;
    		case	'ALL_DISCUSSION_PAGE'	:	if(empty($date)){
									    			$url = SHIKSHA_ASK_HOME_URL."/all-discussions";
									    		}else{
									    			$url = SHIKSHA_ASK_HOME_URL."/all-discussions/".$dateTimeObject->format('dmY');
									    		}
									    		if($pageNumber > 1){
									    			$url = $url."/".$pageNumber;
									    		}
    											break;
    	}
    	return $url;
    }

    private function _getSeoURLCanonical($pageType) {
        if($pageType == "ALL_QUESTION_PAGE"){
                return SHIKSHA_ASK_HOME_URL."/questions";
        }
        else if($pageType == "ALL_DISCUSSION_PAGE"){
                return SHIKSHA_ASK_HOME_URL."/all-discussions";
        }
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
        $entityType = $this->input->post('entityType');
        $entityId   = (int) $this->input->post('entityId');
        $action     = $this->input->post('action');
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
        $APIClient->setRequestData(array("entityId" => $entityId, "entityType"  => $entityType , "action"   => $action,"trackingPageKeyId" => $trackingPageKeyId));
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
        
        $entityType = $this->input->post('entityType');
        $entityId   = (int) $this->input->post('entityId');
        $action     = $this->input->post('action');
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
        $APIClient->setRequestData(array("entityId" => $entityId, "entityType"  => $entityType , "digVal"   => $digVal,"isLoginFlow" => $isLoginFlow ,"trackingPageKeyId" =>$trackingPageKeyId));
        
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

    public function trackThreadView(){

        $this->_init();
        $userId         = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $visitorId      = getVisitorId();
        $threadViewData = $this->input->post('threadViewData');
        $pageSource     = $this->input->post('pageSource');
        $pageType       = $this->input->post('pageType');

        $pageSource = $pageSource ? $pageSource : 'desktopsite';

        $threadViewData = explode(",", $threadViewData);
        $threadViewData = array_filter($threadViewData);

        if(!is_array($threadViewData)){
            echo 'FAIL';
            return;
        }
        $this->apicommonLib = $this->load->library('common_api/APICommonLib');
        $result = $this->apicommonLib->trackThreadView($userId, $visitorId, $threadViewData, $pageType, $pageSource);
        if($result){
            echo 'SUCCESS';
        }else{
            echo 'FAIL';
        }
        return;
    }

    /**
     * @desc Function to shortlist an entity by the user. Called when user clicks on Answer Later/Comment Later on QDP/DDP
     * @param POST param entityId which is the Id of the Question/Discussion
     * @param POST param entityType which is the entity type. This can be question/discussion
     * @return String - Success/Fail
     * @date 2016-06-28
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

    private function _getHomeRightHandWidgetData(&$displayData){

        $rightData = array();
        $rightData['topHeading1'] = "Most active users on Ask & Answer";
        $rightData['topHeading2'] = "";
        $rightData['subHeading'] = "Based on last week activity";

        $displayData['rightData'] = $rightData;
    }

    function getAnAMostActiveUsers($trackingPageKeyId=''){
        $this->_init();

        $APIClient = $this->load->library("APIClient");
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $jsonDecodedData =$APIClient->getAPIData("AnA/getAnAMostActiveUsers");

        $displayData = array();
        $this->_getHomeRightHandWidgetData($displayData);
        $displayData['userId'] = $userId;
        $displayData['rightWidgetUserData'] = $jsonDecodedData['mostActiveUsers'];
        $displayData['trackingPageKeyId']   = $trackingPageKeyId;
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';
        $this->load->view("messageBoard/desktopNew/widgets/topContributorWidgetList", $displayData);
    }

    function getThreadMostActiveUsers($threadId, $threadType,$trackingPageKeyId, $activeTrackingPageKeyId){
        $this->_init();

        $APIClient = $this->load->library("APIClient");
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $APIClient->setUserId($userId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $jsonDecodedData =$APIClient->getAPIData("AnA/getThreadsMostActiveUser/".$threadId."/".$threadType);

        $displayData = array();
        if($jsonDecodedData['activeUsersData']){
            $displayData['topContributors'] = $jsonDecodedData['activeUsersData']['topContributors'];
            $displayData['tcTagName'] = $jsonDecodedData['activeUsersData']['tcTagName'];
            $displayData['trackingPageKeyId'] = $trackingPageKeyId;
            $this->_getQuesDiscPageRightHandWidgetData($displayData);
        }
        else{
            $APIClient = new APIClient();
            $APIClient->setUserId($userId);
            $APIClient->setVisitorId(getVisitorId());
            $APIClient->setRequestType("get");
            $jsonDecodedData =$APIClient->getAPIData("AnA/getAnAMostActiveUsers");

            $this->_getHomeRightHandWidgetData($displayData);
            $displayData['rightWidgetUserData'] = $jsonDecodedData['mostActiveUsers'];
            $displayData['trackingPageKeyId'] = $activeTrackingPageKeyId;
        }
        $displayData['userId'] = $userId;
        
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';
        $this->load->view("messageBoard/desktopNew/widgets/topContributorWidgetList", $displayData);
    }

    private function _getQuesDiscPageRightHandWidgetData(&$displayData){

        $rightData = array();
        $rightData['topHeading1'] = "Most active users";
        $rightData['topHeading2'] = $displayData['tcTagName'];
        $rightData['subHeading'] = "Based on last week activity";
        $rightData['showRightRegirationWidget'] = true;

        $mostActiveUsers = array();
        $i = 0;
        if($displayData['topContributors']){
            foreach ($displayData['topContributors'] as &$value) {
                $value['followers']   = $value['followerCount'];
                $value['isFollowing'] = $value['isUserFollowing'];
                unset($value['followerCount']);
                unset($value['isUserFollowing']);
            }
        }
        $displayData['rightWidgetUserData'] = $displayData['topContributors'];
        // _p($displayData['data']['topContributors']);die;

        $displayData['rightData'] = $rightData;
    }
    function getExpertsPanel($level='All')
    {
        if($level != 'All')
        {
            $newLevel = explode('-', $level);
            $level = $newLevel[1];
            if(!( (int) $level == $level && (int) $level >= 11))
            {
                redirect(SHIKSHA_ASK_HOME_URL.'/experts', 'location', 301);
                exit;
            }

        }

        $this->_init();

        //no of rows to be displayed on Experts Panel Page
        $limitRowsPerPage = 4;     

        //no of user cards to be displayed per row
        $limitUsersPerRow = 3;
        $displayData = array();

        //fetching logged in user information
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';

        $displayData['userStatus']           = $this->userStatus;
        $displayData['userIdOfLoginUser']    = $userId;
        $displayData['userGroup']            = $userGroup;
        $displayData['validateuser']         = $this->userStatus;

        //fetching post varaibles
        $start              = isset($_POST['expertPanelPageNo'])?$this->input->post('expertPanelPageNo'):1;
        $expertsViewedCount = isset($_POST['expertsViewedCount'])?$this->input->post('expertsViewedCount'):0;
        $level              = isset($_POST['levelName'])?$this->input->post('levelName'):$level;
        $callType           = isset($_POST['callType'])?$this->input->post('callType'):'NONAJAX';

        if($callType == 'NONAJAX')
        {
            $seoURL = $this->getExpertsPanelSEOUrl($level);
            if(getCurrentPageURLWithoutQueryParams() != $seoURL){
                header("Location: ".$seoURL,TRUE,301);
                exit;
            }    
        }
        
        //fetch no of users availbale for every level (i.e levelId >= 11)
        $this->load->model('messageBoard/AnAModel');
        $expertCountByLevel = $this->AnAModel->getExpertsCountByLevel();

        $expertsLevelRows = array();
        $expertCountPerLevel = array();
        $totalExpertsCount = 0;

        //calculating no of rows to be displayed for every level
        foreach ($expertCountByLevel as $keyName => $keyValue) {
            $expertCountPerLevel[$keyValue['Level']] = array('count' =>$keyValue['count'] , 'levelName' => $keyValue['levelName']);
            if($level == 'All')
            {
                $totalExpertsCount += $keyValue['count'];
                $expertsLevelRows[$keyValue['Level']] = ceil($keyValue['count'] / $limitUsersPerRow);
            }
            elseif($keyValue['Level'] == $level)
            {
                $totalExpertsCount += $keyValue['count'];
                $expertsLevelRows[$keyValue['Level']] = ceil($keyValue['count'] / $limitUsersPerRow);
            }
            
        }

        if(empty($expertsLevelRows[$level]) && $level != 'All')
        {
            redirect(SHIKSHA_ASK_HOME_URL.'/experts', 'location', 301);
            exit;
        }
        $maxLevelId = 0;
        $viewedRows = (($start-1) * $limitRowsPerPage);
	$userStart = 0;
        $userListEnd = 0;
        //below logic will be used for how many rows of particular level as displayed and how many rows are left
        foreach ($expertsLevelRows as $key => $value) {
            $viewedRows -= $value;
            if($viewedRows < 0)
            {
                if($maxLevelId == 0)
                {
                    $maxLevelId = $key;
                    $userStart = ($value + $viewedRows) * $limitUsersPerRow;
                    $userListEnd = $expertCountPerLevel[$key]['count']-$userStart;

                }
                if($viewedRows < -1 * $limitRowsPerPage )
                {
                    if($maxLevelId == $key)
                        $userListEnd = 0;
                    //$userListEnd += ($value + $viewedRows + $limitRowsPerPage) * $limitUsersPerRow;   
                    $extraValue = $value + $viewedRows;
                 
                    if($extraValue < 0)
                        $extraValue = $limitRowsPerPage + $extraValue;
                    else
                        $extraValue = $limitRowsPerPage;
                    
                    $userListEnd += $extraValue * $limitUsersPerRow;
                     break;
                }
                else if($key != $maxLevelId)
                {
                    $userListEnd += $expertCountPerLevel[$key]['count'];

                }
                if($viewedRows == -1 * $limitRowsPerPage)
                {
                    break;
                } 

            }
        }
	if($userStart == 0 && $userListEnd == 0)
		echo " ";
        //getting experts information like points,levelId,levelName
        $this->load->model('messageBoard/AnAModel');
        $expertsInformation = $this->AnAModel->getExpertsInfo($level,$maxLevelId,$userStart,$userListEnd);
        $expertsInfoUserWise = array();
        $expertUserIds = array();
        $i = 0;
        foreach ($expertsInformation as $key => $value) {
            if($userId == $value['userId']){
                        $userProfileUrl = SHIKSHA_HOME.'/userprofile/edit';
                    }else{
                        $userProfileUrl = SHIKSHA_HOME.'/userprofile/'.$value['userId'];
                    }
            $expertsInfoUserWise[$value['levelId']][] = array('points' => $value['points'],'userId' => $value['userId'],'levelName' => $value['levelName'],'userProfileUrl'=>$userProfileUrl);
            $expertUserIds[$i++] = $value['userId'];
        }

        //getting basic information of expert users
        $UserCommonLib = $this->load->library('v1/UserCommonLib');
        //$expertsBasicInfo = $UserCommonLib->getAnAUserData($expertUserIds,array("basicDetails","answercount","followercount","isUserFollowing","upvotescount","aboutMe","designation","higherEducation","socialProfile"),$userId);
        $cacheLib = $this->load->library('cacheLib');
        $key = md5('expertPanelData'.implode('_',$expertUserIds));
        $expertsInfo = $cacheLib->get($key);
        if($expertsInfo != 'ERROR_READING_CACHE'){
                //Get Data for all info except Answer count and Upvote count
                $expertsBasicInfo = $UserCommonLib->getAnAUserData($expertUserIds,array("basicDetails","isUserFollowing","aboutMe","designation","higherEducation","socialProfile"),$userId);
                //Now, merge the data fetched from cache and from DB
                foreach ($expertsInfo as $expertCacheInfo){
                    $userIdKey = $expertCacheInfo['userId'];
                    $expertsBasicInfo[$userIdKey]['answerCount'] = $expertCacheInfo['answerCount'];
                    $expertsBasicInfo[$userIdKey]['upvoteCount'] = $expertCacheInfo['upvoteCount'];
		    $expertsBasicInfo[$userIdKey]['followers'] = $expertCacheInfo['followers'];
                }
        }
        else{
                $expertsBasicInfo = $UserCommonLib->getAnAUserData($expertUserIds,array("basicDetails","answercount","followercount","isUserFollowing","upvotescount","aboutMe","designation","higherEducation","socialProfile"),$userId);
                $cacheLib->store($key,$expertsBasicInfo,86400,'misc');
        }

        $displayData['expertsBasicInfo'] = $expertsBasicInfo;
        $displayData['expertsInfoUserWise'] = $expertsInfoUserWise;
        $displayData['expertPanelPageNo'] = $start;
        $displayData['expertsCountPerLevel'] = $expertCountPerLevel;
        $displayData['expertsLevelPage'] = $level;
        $displayData['totalExperts'] = $totalExpertsCount;
        $displayData['expertsViewedCount'] = $expertsViewedCount+$userListEnd;
        $displayData['inculdeLevelHeading'] = (($userStart == 0) ? true : false);
        $displayData['loggedInUser'] = $userId;
        $displayData['canonicalURL'] = getCurrentPageURLWithoutQueryParams();
        $displayData['trackingPageKeyId'] = D_EP_FOLLOW_USER;

        $displayData['GA_userLevel']    = $userId > 0 ? 'Logged In':'Non-Logged In';

        if($level == 'All'){
            $displayData['seoTitle']       = 'Education and Career Community Experts - Shiksha.com';
            $displayData['seoDescription'] = 'Meet our education and career community experts who are actively participating in community to help students with answers to their critical career related queries.';
        } else {
            $displayData['trackingpageNo'] = $level;
            $displayData['seoTitle']       = "Level $level - Education and Career Community Experts - Shiksha.com";
            $displayData['seoDescription'] = "View list of level $level education and career community experts to help the students on Shiksha.com";
        }

        $displayData['trackingpageIdentifier'] = 'advisoryBoardPage';
        $displayData['trackingcountryId']=2;

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

        //below line is used for pageview tracking purpose
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($displayData);

        $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
        
        if($callType == 'AJAX')
            $this->load->view('desktopNew/expertsPanelUsers',$displayData);
        else
            $this->load->view('desktopNew/expertsPanel',$displayData);
    }
    function getExpertsPanelSEOUrl($level)
    {
        if($level == 'All')
        {
            return SHIKSHA_ASK_HOME_URL.'/experts';
        }
        else
        {
            return SHIKSHA_ASK_HOME_URL.'/experts/Level-'.$level;
        }
    }
    function getMISGATrackingDetails(&$displayData,$pageName='',$tabName='')
    {
        if($pageName == 'homePage')
        {
                $displayData['GA_currentPage']              = 'HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Tag_Reco']          = 'TAG_RECO_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Profile_User_Reco'] = 'PROFILE_RECO_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Follow_Tag_Reco']   = 'FOLLOW_TAG_RECO_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Follow_User_Reco']  = 'FOLLOW_USER_RECO_HOMEPAGE_DESKAnA';
                

                $displayData['GA_Tap_On_Right_Reg_CTA']     = 'REGISTER_RHSWIDGET_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Right_Exp_Panel_Link']  = 'EXPERTSPANEL_RHSWIDGET_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Right_All_Ques']    = 'ALLQUESTIONS_RHSWIDGET_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Right_All_Disc']    = 'ALLDISCUSSIONS_RHSWIDGET_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Right_All_Tags']    = 'ALLTAGS_RHSWIDGET_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Right_Community']   = 'COMMUNITYGUIDELINES_RHSWIDGET_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_Right_User_Point']  = 'USERPOINT_RHSWIDGET_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_What_Question']     = 'QUESTION_CTA_HOMEPAGE_DESKAnA';
                $displayData['GA_Tap_On_What_Discussion']   = 'DISCUSSION_CTA_DISC_DESKAnA';

                $displayData['regRightTrackingPageKeyId']   = D_QnA_RIGHT_REG_WIDGET;
            switch($tabName)
            {
                case 'discussion':
                    $displayData['GA_Tap_On_ViewMore_Com']  = 'VIEWMORE_COMMENT_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Com_CTA']       = 'WRITECOMMENT_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Comment']       = 'COMMENT_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_Disc']   = 'FOLLOW_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Share_Disc']    = 'SHARE_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Owner_Com']     = 'PROFILE_COMMENT_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Tag_Disc']      = 'TAG_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Disc']          = 'DISCTITLE_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Upovte_Com']    = 'UPVOTE_COMMENT_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Downvote_Com']  = 'DOWNVOTE_COMMENT_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_List_DISC']   = 'FOLLOWERLIST_DISC_HOMEPAGE_DESKAnA';

                    $displayData['qtrackingPageKeyId']      = D_DISC_ASK_QUES_WIDGET;
                    $displayData['dtrackingPageKeyId']      = D_DISC_POST_DISC_WIDGET;
                    $displayData['ctrackingPageKeyId']      = D_DISC_DCOMMENT_POST_WIDGET;
                    $displayData['tupdctrackingPageKeyId']  = D_DISC_TUP_DCOMMENT_TUPLE;
                    $displayData['tdowndctrackingPageKeyId'] = D_DISC_TDOWN_DCOMMENT_TUPLE;
                    $displayData['dfollowTrackingPageKeyId'] = D_DISC_FOLLOW_DISC;
                    $displayData['flistfollowTrackingPageKeyId'] = D_DISC_FOLLOW_USER_FOLLOWER_LIST;
                    $displayData['fuRightActiveTrackingPageKeyId'] = D_DISC_FOLLOW_USER_RIGHT_MOST_ACTIVE;
                        break;
                case 'unanswered':
                    $displayData['GA_Tap_On_Answer_CTA_UN']    = 'WRITEANSWER_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_QUES_UN']   = 'FOLLOW_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Share_Ques_UN']    = 'SHARE_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Tag_Ques_UN']      = 'TAG_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Ques_UN']          = 'QUESTTITLE_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_List_QUES_UN']   = 'FOLLOWERLIST_UNANQUEST_HOMEPAGE_DESKAnA';

                    $displayData['qtrackingPageKeyId']       = D_UANS_ASK_QUES_WIDGET;
                    $displayData['qfollowTrackingPageKeyId'] = D_UANS_FOLLOW_QUES;
                    $displayData['atrackingPageKeyId']       = D_UANS_ANSWER_POST_WIDGET;
                    $displayData['flistfollowTrackingPageKeyId'] = D_UANS_FOLLOW_USER_FOLLOWER_LIST;
                    $displayData['fuRightActiveTrackingPageKeyId'] = D_UANS_FOLLOW_USER_RIGHT_MOST_ACTIVE;
                    break;
                case 'home':
                    $displayData['GA_Tap_On_Answer_CTA']    = 'WRITEANSWER_QUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Answer']        = 'ANSWER_QUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_ViewMore_Ans']  = 'VIEWMORE_ANSWER_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_ViewMore_Com']  = 'VIEWMORE_COMMENT_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_QUES']   =  'FOLLOW_QUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Share_Ques']    = 'SHARE_QUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Owner_Ans']     = 'PROFILE_ANSWER_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Tag_Ques']      = 'TAG_QUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Ques']          = 'QUESTTITLE_QUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Upovte_Ans']    = 'UPVOTE_ANSWER_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Downvote_Ans']  = 'DOWNVOTE_ANSWER_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Com_CTA']       = 'WRITECOMMENT_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Comment']       = 'COMMENT_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_Disc']   = 'FOLLOW_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Share_Disc']    = 'SHARE_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Owner_Com']     = 'PROFILE_COMMENT_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Tag_Disc']      = 'TAG_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Disc']          = 'DISCTITLE_DISC_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Upovte_Com']    = 'UPVOTE_COMMENT_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Downvote_Com']  = 'DOWNVOTE_COMMENT_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_List_QUES']   = 'FOLLOWERLIST_QUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_List_DISC']   = 'FOLLOWERLIST_DISC_HOMEPAGE_DESKAnA';

                    $displayData['GA_Tap_On_Answer_CTA_UN']    = 'WRITEANSWER_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_QUES_UN']   = 'FOLLOW_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Share_Ques_UN']    = 'SHARE_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Tag_Ques_UN']      = 'TAG_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Ques_UN']          = 'QUESTTITLE_UNANQUEST_HOMEPAGE_DESKAnA';
                    $displayData['GA_Tap_On_Follow_List_QUES_UN']   = 'FOLLOWERLIST_UNANQUEST_HOMEPAGE_DESKAnA';

                    $displayData['qtrackingPageKeyId']      = D_QnA_ASK_QUES_WIDGET;
                    $displayData['ctrackingPageKeyId']      = D_QnA_DCOMMENT_POST_WIDGET;
                    $displayData['atrackingPageKeyId']      = D_QnA_ANSWER_POST_WIDGET;
                    $displayData['tupdctrackingPageKeyId']  = D_QnA_TUP_DCOMMENT_TUPLE;
                    $displayData['tdowndctrackingPageKeyId'] = D_QnA_TDOWN_DCOMMENT_TUPLE;
                    $displayData['tupatrackingPageKeyId']   = D_QnA_TUP_ANSWER_TUPLE;
                    $displayData['tdownatrackingPageKeyId'] = D_QnA_TDOWN_ANSWER_TUPLE;
                    $displayData['qfollowTrackingPageKeyId'] = D_QnA_FOLLOW_QUES;
                    $displayData['dfollowTrackingPageKeyId'] = D_QnA_FOLLOW_DISC;
                    $displayData['tfollowTrackingPageKeyId'] = D_QnA_FOLLOW_TAGS_RECOMMENDATION;
                    $displayData['ufollowTrackingPageKeyId'] = D_QnA_FOLLOW_USER_RECOMMENDATION;
                    $displayData['flistfollowTrackingPageKeyId'] = D_QnA_FOLLOW_USER_FOLLOWER_LIST;
                    $displayData['fuRightActiveTrackingPageKeyId'] = D_QnA_FOLLOW_USER_RIGHT_MOST_ACTIVE;
                    break;
            }
        }
        elseif($pageName == 'allPage')
        {
            switch ($tabName) {
                case 'question':
                        $displayData['GA_currentPage']          = 'ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_What_Question'] = 'QUESTION_CTA_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Answer_CTA']    = 'WRITEANSWER_QUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Answer']        = 'ANSWER_QUEST_HOMEPAGE_DESKAnA';
                        $displayData['GA_Tap_On_ViewMore_Ans']  = 'VIEWMORE_ANSWER_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_ViewMore_Com']  = 'VIEWMORE_COMMENT_HOMEPAGE_DESKAnA';
                        $displayData['GA_Tap_On_Follow_QUES']   =  'FOLLOW_QUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Share_Ques']    = 'SHARE_QUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Owner_Ans']     = 'PROFILE_ANSWER_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Tag_Ques']      = 'TAG_QUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Ques']          = 'QUESTTITLE_QUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Upovte_Ans']    = 'UPVOTE_ANSWER_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Downvote_Ans']  = 'DOWNVOTE_ANSWER_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Follow_List_QUES']   = 'FOLLOWERLIST_QUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Right_All_Ques']    = 'ALLQUESTIONS_RHSWIDGET_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Right_All_Disc']    = 'ALLDISCUSSIONS_RHSWIDGET_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Right_All_Tags']    = 'ALLTAGS_RHSWIDGET_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Right_Community'] = 'COMMUNITYGUIDELINES_RHSWIDGET_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Right_User_Point']  = 'USERPOINT_RHSWIDGET_ALLQUEST_DESKAnA';

                        $displayData['GA_Tap_On_Right_Reg_CTA']     = 'REGISTER_RHSWIDGET_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Right_Exp_Panel_Link']  = 'EXPERTSPANEL_RHSWIDGET_ALLQUEST_DESKAnA';

                        $displayData['GA_Tap_On_Answer_CTA_UN']    = 'WRITEANSWER_UNANQUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Follow_QUES_UN']   = 'FOLLOW_UNANQUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Share_Ques_UN']    = 'SHARE_UNANQUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Tag_Ques_UN']      = 'TAG_UNANQUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Ques_UN']          = 'QUESTTITLE_UNANQUEST_ALLQUEST_DESKAnA';
                        $displayData['GA_Tap_On_Follow_List_QUES_UN']   = 'FOLLOWERLIST_UNANQUEST_ALLQUEST_DESKAnA';
                                        

                        $displayData['qtrackingPageKeyId'] = D_ALL_QUES_ASK_QUE_STICKY;
                        $displayData['atrackingPageKeyId']       = D_ALL_QUES_ANSWER_POST_WIDGET;
                        $displayData['tupatrackingPageKeyId']    = D_ALL_QUES_TUP_ANSWER_TUPLE;
                        $displayData['tdownatrackingPageKeyId']  = D_ALL_QUES_TDOWN_ANSWER_TUPLE;
                        $displayData['qfollowTrackingPageKeyId'] = D_ALL_QUES_FOLLOW_QUES;
                        $displayData['flistfollowTrackingPageKeyId'] = D_ALL_QUES_FOLLOW_USER_FOLLOWER_LIST;
                        $displayData['fuRightActiveTrackingPageKeyId'] = D_ALL_QUES_FOLLOW_USER_RIGHT_MOST_ACTIVE;
                        $displayData['regRightTrackingPageKeyId']   = D_ALL_QUES_RIGHT_REG_WIDGET;
                        break;
                case 'discussion':
                    $displayData['GA_currentPage']          = 'ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_What_Question'] = 'QUESTION_CTA_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_What_Discussion'] = 'DISCUSSION_CTA_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_ViewMore_Com']  = 'VIEWMORE_COMMENT_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Com_CTA']       = 'WRITECOMMENT_DISC_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Comment']       = 'COMMENT_DISC_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Follow_Disc']   = 'FOLLOW_DISC_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Share_Disc']    = 'SHARE_DISC_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Owner_Com']     = 'PROFILE_COMMENT_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Tag_Disc']      = 'TAG_DISC_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Disc']          = 'DISCTITLE_DISC_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Upovte_Com']    = 'UPVOTE_COMMENT_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Downvote_Com']  = 'DOWNVOTE_COMMENT_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Follow_List_DISC']   = 'FOLLOWERLIST_DISC_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Right_Reg_CTA']     = 'REGISTER_RHSWIDGET_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Right_Exp_Panel_Link']  = 'EXPERTSPANEL_RHSWIDGET_ALLDISC_DESKAnA';

                    $displayData['GA_Tap_On_Right_All_Ques']    = 'ALLQUESTIONS_RHSWIDGET_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Right_All_Disc']    = 'ALLDISCUSSIONS_RHSWIDGET_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Right_All_Tags']    = 'ALLTAGS_RHSWIDGET_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Right_Community'] = 'COMMUNITYGUIDELINES_RHSWIDGET_ALLDISC_DESKAnA';
                    $displayData['GA_Tap_On_Right_User_Point']  = 'USERPOINT_RHSWIDGET_ALLDISC_DESKAnA';

                    $displayData['dtrackingPageKeyId']  = D_ALL_DISC_POST_DISC_STICKY;
                    $displayData['qtrackingPageKeyId'] = D_ALL_DISC_ASK_QUE_STICKY;
                    $displayData['ctrackingPageKeyId']        = D_ALL_DISC_DCOMMENT_POST_WIDGET;
                    $displayData['tupdctrackingPageKeyId']    = D_ALL_DISC_TUP_DCOMMENT_TUPLE;
                    $displayData['tdowndctrackingPageKeyId']  = D_ALL_DISC_TDOWN_DCOMMENT_TUPLE;
                    $displayData['dfollowTrackingPageKeyId']  = D_ALL_DISC_FOLLOW_DISC;
                    $displayData['flistfollowTrackingPageKeyId'] = D_ALL_DISC_FOLLOW_USER_FOLLOWER_LIST;
                    $displayData['fuRightActiveTrackingPageKeyId'] = D_ALL_DISC_FOLLOW_USER_RIGHT_MOST_ACTIVE;
                    $displayData['regRightTrackingPageKeyId']   = D_ALL_DISC_RIGHT_REG_WIDGET;
                    break;
            }

        }
    }

	public function getQuestionDataFromSearchPart(){
    	$this->_init();
    	$userId			= isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    	$threadId		= $this->input->post('entityId');
    	$threadType		= $this->input->post('entityType');
    	$threadTitle	= $this->input->post('title');
    	$loadOnlyTuples	= $this->input->post('loadOnlyTuples');
    	$result	= array();
    	$displayData	= array();
    	if($userId == 0 || $threadId <= 1000000 || !in_array($threadType, array('question','discussion'))){
    		$result	=	"<p>FAIL</p>";
    		echo $result;
    		exit(0);
    	}
    	$inputParams	= array();
    	$displayData['threadType']		= $threadType;
    	$this->load->library('v1/SearchRelatedEntities');
    	$SearchRelatedEntities = new SearchRelatedEntities();
    	if($threadId){
    		$inputParams['excludeThreads'] = array($threadId);
    	}
    	$inputParams['threadType']	= $threadType;
    	if(empty($threadTitle)){
    		$this->load->model('messageBoard/AnAModel');
    		$threadTitle	= $this->AnAModel->getThreadTitle($threadId, $displayData['threadType']);
    	}
    	$displayData['threadTitle']		= $threadTitle;
    	$displayData['threadId']		= $threadId;
    	$displayData['response']		= $SearchRelatedEntities->getRelatedThreadByText($threadTitle, $inputParams, 10);
		/* Set View Count for Question/Discussion Objects*/
		$displayData['response']['content']		= $SearchRelatedEntities->setViewCountDetail($displayData['response']['content'], $threadType);
    	if(isset($loadOnlyTuples) && $loadOnlyTuples == "TRUE"){
    		$displayData['loadOnlyTuples']	= TRUE;
    	}else {
    		$displayData['loadOnlyTuples']	= FALSE;
    	}

        $displayData['GA_userLevel']    = $userId > 0 ? 'Logged In':'Non-Logged In';

    	$result	= $this->load->view('desktopNew/linkThreadOverlay', $displayData, TRUE);
    	echo $result;
    	exit(0);
    }

    private function getCategories(){
        $appId = 12;
        $this->_init();
	$this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId);
        $others = array();
        $categoryForLeftPanel = array();
        foreach($categoryList as $temp)
        {
                if((stristr($temp['categoryName'],'Others') == false))
                {
                $categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
                }
                else
                {
                $others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
                }
        }
        foreach($others as $key => $temp)
        {
                $categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
        }
        return $categoryForLeftPanel;
     }

         function getRelatedLinkedQuestions($id=3345620,$type='question',$callType='moduleRun'){
            
            if($id == 0)
                return;
                $this->load->library('v1/SearchRelatedEntities');
                $this->load->model("messageBoard/anamodel");
                $relatedEntities = new SearchRelatedEntities();
                $anamodel        = new anamodel();

                $s = microtime(true);
                // get linked threads
                /*
                $this->benchmark->mark('get_linked_entity_from_db_start');
                if(in_array($type, array("question", "discussion")))
                    $linkedEntities  = $anamodel->getLinkedQuestionDiscussionDetails($id, $type, 5);
                $this->benchmark->mark('get_linked_entity_from_db_end');
                */
                if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Stage 1 :: ".$type."_".$id." = ".(microtime(true)-$s), 3, '/tmp/perfLogs.log');
                
                
                if(empty($linkedEntities)){
                        $linkedEntities = NULL;
                }
                
                // get linked thread-ids
                $linkedThreads = array();
                /*foreach($linkedEntities as $linkedThread){
                    $linkedThreads[] = $linkedThread['threadId'];
                }*/
                
                for($i=0; $i<count($linkedEntities); $i++)
                {
                        $linkedThreads[] = $linkedEntities[$i]['threadId'];
                        $linkedEntities[$i]['title'] = strip_tags(html_entity_decode($linkedEntities[$i]['title']));
                        $linkedEntities[$i]['creationDate'] = date("Y-m-d", strtotime($linkedEntities[$i]['creationDate']));
                }

                // determine the algorithm to serve
                $algorithm = 2;
                // if(rand(0, 10)%2 == 0)
                //     $algorithm = 1;
                
                // get related threads excluding linked threads
                $inputParams              = array();
                $inputParams['text']      = $this->input->post("threadTitle");
                $inputParams['algorithm'] = $algorithm;

             $this->benchmark->mark('get_related_entity_start');
            $relatedEntities = $relatedEntities->getRelatedEntity($id, $type, $linkedThreads, 5, $inputParams);

            $relatedEntities = $this->isYearContains($relatedEntities);

            $this->benchmark->mark('get_related_entity_end');
                if(empty($relatedEntities)){
                        $relatedEntities = NULL;
                }
                $jsonDecodedData['linkedEntities'] = $linkedEntities;
                $jsonDecodedData['related'] = $relatedEntities;

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
                $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
                if($callType == 'moduleRun'){
                    $this->load->view('desktopNew/linkedAndRelatedEntityTuple',$displayData);
                }else{
                    return $displayData;
                }
     }


     function checkAndReturnCADetails() {
        $userIdsString = $this->input->post('userIdsString');
        $userIds = explode(',', $userIdsString);
        $this->load->model('messageBoard/AnAModel');
        $caDetails = $this->AnAModel->getCAInstituteId(array_filter($userIds));        
        echo json_encode($caDetails);
     }

      public function clientWidgetAnA($tags){
         if(is_array($tags) && count($tags)>0){
            $this->load->library('AnALibrary');
            $this->analibrary->clientWidgetAnA($tags, $device = 'desktop');
         }
      }

      public function articleWidgetAnA(){
         $this->load->library('messageBoard/AnALibrary');
         $this->analibrary->articleWidgetAnA();
      }

    function getYearBuckets(){
        $yearFrom = 2008;
        $yearTo = (int)date('Y') - 1;
        $yearBuckets = array();
        for($i=$yearFrom;$i<=$yearTo;$i++)
        {
            $yearBuckets[] = $i;
        }
        return $yearBuckets;
    }

    // remove msg from buckets if contains shiksha years from 2008 - (Y-1)
    function isYearContains($resultArr){
        $yearBuckets = $this->getYearBuckets();
        foreach ($resultArr as $key => $value) {
            $string = $value['title'];
            $outPut = findMultipleValues($string);
            $matchResult = array_intersect($yearBuckets, $outPut);
            if(count($matchResult)){
                $unsetKeys[] = $key;
            }
        }
        foreach ($unsetKeys as $key => $value) {
            unset($resultArr[$value]);
        }
        unset($unsetKeys);
        return $resultArr;
    }

}
?>
