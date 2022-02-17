<?php

class ExamPageMain extends ShikshaMobileWebSite_Controller {

    private $examRepository;
    private $userStatus;

    private $removeExamPageIds = array(13198,13647,9180,13081,13073,327,5822,13621,13627,13553,13716,13130,13082,13625,13103,13106,13069,6401,10002,13102,5944,5945,9208,13615,12971,13634,9238,13105,13686,9983,306,13685,9249,13723,9252,3275,9886,13745,9643,13110,13109,13116,13063,13614,13549,13049,307,13094,13580,13114,13639,10637,11438,10692,13624,13057,13120,13122,309);
	
	function _init() {
		$this->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $this->examRepository = $examBuilder->getExamRepository();
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->examPageLib = $this->load->library('examPages/ExamPageLib');
	}

    function getExamPageByNewURl($isAmp){
    
        if(stripos($_SERVER[REQUEST_URI],'exams/amp')){
            $urlString = explode('amp', $_SERVER[REQUEST_URI]);
            $urlString = ltrim($urlString[1],'/');
            $urlString = explode('/', $urlString);
            $examName  = $urlString[0];
            $sectionName  = $urlString[1];
        }else{
            $urlString   = explode('-exam', $_SERVER[REQUEST_URI]);
            $examName    = end(explode('/', $urlString[0]));
            $sectionName = ltrim($urlString[1],'-');
            $sectionName = explode('?',$sectionName);
            $sectionName = $sectionName[0];
        }

        if(!empty($isAmp)){
            $isAmp = true;
        }
        $this->getExamPage($examName, $sectionName, $isAmp);
    }
    
    /**
     * Main function
     * @param  string $param string from url
     */
    function getExamPage($param, $sectionName, $isAmp = false) {

        if($param !='' && $param == 'gmat'){
          $abroadUrl = SHIKSHA_STUDYABROAD_HOME.'/exams/gmat';
          header("Location: $abroadUrl",TRUE,301);exit;
        }

    	if(!empty($sectionName) && !is_numeric($sectionName)){
    		$param = $param.'@'.$sectionName;
    	}
    	// initialize resources
	$this->benchmark->mark('init_start');
	$this->_init();		
	$this->benchmark->mark('init_end');

		//validate exam url's
	$this->benchmark->mark('validation_start');
	$response    = $this->examPageLib->examUrlRequest($param, $isAmp);
	$examId      = $response['examId'];
	$sectionKeyName = $response['sectionName'];
	$this->benchmark->mark('validation_end');

	$this->benchmark->mark('get_exam_repo_start');
        $examBasicObj = $this->examRepository->find($examId, false);
        if(empty($examBasicObj)){
        	show_404();exit();
        }

        if($isAmp) {
            $examPageRequest = $this->load->library('examPages/examPageRequest');
            $examPageRequest->setExamName($response['examName']);
            $url = $examPageRequest->getUrl($response['sectionName'],true);
            if(!empty($url)){
                header("Location: ".$url, TRUE, 301);
            }
            else{
                show_404();
            }
            exit;
        }

	$this->benchmark->mark('get_exam_repo_end');

	$this->benchmark->mark('get_prim_gp_start');

	    $primaryGroup   = $examBasicObj->getPrimaryGroup();
	    $primaryGroupId = $primaryGroup['id'];
	    if(empty($primaryGroupId)){
	    	show_404();exit();
	    }

	$course = (isset($_GET['course']) && $_GET['course']>0)?$this->input->get('course'):0;
    	if($course>0 && is_numeric($course)){
        	$groupId = $course;
        }else{
	    	$groupId = $primaryGroupId;
      	}
	$this->benchmark->mark('get_prim_gp_end');

        $displayData = array(); 
        $displayData['examBasicObj'] = $examBasicObj;       
        $displayData['examId'] = $examId;
        $displayData['groupId'] = $groupId;
        $displayData['examName'] = htmlentities($examBasicObj->getName());
        $displayData['examFullName'] = htmlentities($examBasicObj->getFullName());
        $displayData['isRootUrl'] = $examBasicObj->isRootUrl();

	    $displayData['userStatus'] 	 = $this->userStatus;
		$displayData['userId']       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

        if($displayData['userId'] > 0)
        {
            $displayData['GA_userLevel'] = 'Logged In';
        }
        else{
              $displayData['GA_userLevel'] = 'Non-Logged In';
        }

        $displayData['sectionName'] = $sectionName;
        $displayData['primaryGroupId'] = $primaryGroupId;       
        $displayData['course'] = $course;   
        if($isAmp)
            $displayData['redirectOriginalUrl'] = $examBasicObj->getAmpURL();
        else
            $displayData['redirectOriginalUrl'] = $examBasicObj->getUrl();

        $displayData['currentUrl'] = $examBasicObj->getUrl();
        if($course>0){
            $displayData['currentUrlWithParams'] = $displayData['currentUrl'].'?course='.$course;
        }else{
            $displayData['currentUrlWithParams'] = $displayData['currentUrl'];
        }
        
        $displayData['seoSection'] = $sectionKeyName; 
        $displayData['pageName'] = "mobileExamPage";        
        $this->load->config('examPages/examPageConfig');
        $displayData['instituteAccptLimit'] = M_instituteAcceptingLimit;
        $displayData['similarExamLimit'] = M_similarExamsLimit;
        $displayData['updatesLimit'] = M_updatesCount;
        $displayData['isMobile'] = true;
        $displayData['isAmp']    = $isAmp;
        $displayData['showAllUpdate'] = (isset($_GET['showAllUpdate']) && $_GET['showAllUpdate'])?$this->input->get('showAllUpdate'):false;
		
		// seo detail
	$this->benchmark->mark('prep_exam_data_start');
    	$this->examPageLib->prepareExamPageData($displayData);
	$this->benchmark->mark('prep_exam_data_end');

    // breadcrumb
    $displayData['examBreadCrumb'] = $this->examPageLib->prepareBreadCrumb($this->examRepository, $examBasicObj, $examId, $primaryGroupId, $sectionKeyName);

    	$displayData['m_meta_title']       = $displayData['titleText'];	
    	$displayData['m_meta_description'] = $displayData['metaDescription'];
	$displayData['m_meta_keywords']    = $displayData['keywords'];
	$displayData['m_canonical_url']    =  $displayData['canonicalurl'];
	$displayData['boomr_pageid'] = "Mobile5ExamPage";
	$displayData['trackForPages'] = true;

    $this->benchmark->mark('dfp_data_start');
    $dfpObj   = $this->load->library('common/DFPLib');
    $groupObj = $this->examRepository->findGroup($groupId);
    
    $dpfParam = array('groupObj'=>$groupObj,'parentPage'=>'DFP_ExamPage','pageType'=>$sectionKeyName,'examId'=>$examId,'groupId'=>$groupId,'conductedBy'=>$displayData['conductedBy']);

    $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
    $this->benchmark->mark('dfp_data_end');

        $displayData['noJqueryMobile'] = true;
		$this->load->helper('examPages/exam');  
	unset($displayData['titleText'], $displayData['metaDescription'], $displayData['canonicalurl']);

	//Change to add DFP Banner on JEE Main EP
	if($examId == '6244'){
                $displayData['jeeMainExam'] = true;
        }

        if($isAmp)
        {
            $displayData['ampJsArray'] = array('carousel','form','lightbox','iframe','youtube','socialShare');
            $displayData['applyNowDetails'] = $this->getApplyOnline($examId,$groupId,$isAmp);
            $this->load->config('examPages/examPageTrackingIdConfig');
            $ampTrackKeys = $this->config->item('examPageTrackingKeys');   
            $childPage = ($displayData['activeSectionName']) ? $displayData['activeSectionName'] : 'homepage'; 
            $displayData['ampExamViewedTrackingPageKeyId'] = $ampTrackKeys[$childPage]['AMP']['viewedResponse'];

            $displayData['examPageUrl'] = ($displayData['m_canonical_url']) ? $displayData['m_canonical_url'] : $displayData['currentUrl'];

            if($displayData['isHomePage'])
            {
                $displayData['gaPageName']  = 'AMP EXAM PAGE'; 
                $displayData['gaCommonName'] = '_EXAM_PAGE_AMP';   
            }
            else
            {
                $displayData['gaPageName']  = 'AMP EXAM '.strtoupper($displayData['sectionNameMapping'][$displayData['activeSectionName']]).' PAGE'; 
                $displayData['gaCommonName'] = '_EXAM_'.str_replace(' ', '_', strtoupper($displayData['sectionNameMapping'][$displayData['activeSectionName']])).'_PAGE_AMP';
            }
            $displayData['anaWidget'] =  Modules::run('mobile_examPages5/ExamPageMain/prepareAnAWidget',$examId, $examName, 'amp', 'data');
            $this->load->view("mobile_examPages5/newExam/AMP/examHomePage",$displayData);
        }
        else
        {
            $actionType = !empty($_GET['actionType'])?$this->input->get('actionType'):'';
            $fromwhere  = !empty($_GET['fromwhere'])?$this->input->get('fromwhere'):'';
            $pos        = !empty($_GET['pos'])?$this->input->get('pos'):'';
            $fileNo     = !empty($_GET['fileNo'])?$this->input->get('fileNo'):'';
            $instituteName     = !empty($_GET['instituteName'])?$this->input->get('instituteName'):'';
            $courseId     = !empty($_GET['courseId'])?$this->input->get('courseId'):'';
            $isInternal     = !empty($_GET['isInternal'])?$this->input->get('isInternal'):'';

            if(!empty($actionType))
                $displayData['actionType'] = $actionType;

            if(!empty($fromwhere))
            {
                $displayData['fromwhere'] = $fromwhere;
            }
            if(!empty($pos))
            {
                $displayData['pos'] = $pos;   
            }
            if(!empty($fileNo))
            {
                $displayData['fileNo'] = $fileNo;   
            }
            if(!empty($instituteName))
            {
                $displayData['instituteName'] = $instituteName;   
            }
            if(!empty($courseId))
            {
                $displayData['courseId'] = $courseId;   
            }
            if(!empty($isInternal))
            {
                $displayData['isInternal'] = $isInternal;   
            }
          
            $queryParams = array();
            $queryParams = $_GET;
            $this->removeUselessQueryParams($queryParams,$displayData);
			$displayData['trackingKeys'] = $this->config->item('msTrackingKeys');
             $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','mobile');
            //$html = $this->load->view("mobile_examPages5/newExam/examHomePage",$displayData,true);
            //echo html_compress($html);
	    $this->benchmark->mark('load_view_start');
            $displayData['anaWidget'] =  Modules::run('mobile_examPages5/ExamPageMain/prepareAnAWidget',$examId, $examName, 'mobile', 'data');
	    $this->load->view("mobile_examPages5/newExam/examHomePage",$displayData);
	    $this->benchmark->mark('load_view_end');
        }
    }

     function removeUselessQueryParams($queryParams,&$displayData)
    {
            if(array_key_exists('actionType', $queryParams) || array_key_exists('fromwhere', $queryParams) || array_key_exists('pos', $queryParams) || array_key_exists('courseId', $queryParams))
            {
                if(array_key_exists('actionType', $queryParams))
                {
                    unset($queryParams['actionType']);
                }

                if(array_key_exists('fromwhere', $queryParams))
                {
                    unset($queryParams['fromwhere']);
                }
                if(array_key_exists('pos', $queryParams))
                {
                    unset($queryParams['pos']);
                }
                if(array_key_exists('fileNo', $queryParams))
                {
                    unset($queryParams['fileNo']);
                }
                if(array_key_exists('instituteName', $queryParams))
                {
                    unset($queryParams['instituteName']);
                }
                if(array_key_exists('courseId', $queryParams))
                {
                    unset($queryParams['courseId']);
                }
                if(array_key_exists('isInternal', $queryParams))
                {
                    unset($queryParams['isInternal']);
                }
                if(!empty($queryParams) && count($queryParams) > 0)
                {
                    $displayData['replaceStateUrl'] = getCurrentPageURLWithoutQueryParams().'?'.http_build_query($queryParams);
                }
                else{
                    $displayData['replaceStateUrl'] = getCurrentPageURLWithoutQueryParams();
                }    
            }
    }

	// desc : redirect 301 only for old mba exam page url like (mba/exam/cat) to new exam url
	function redirectMBA301($examName, $subCategory, $sectionName){
		$examRedirect = $this->load->library('examPages/ExamRedirect');
		$examRedirect->redirectMBAExam($examName, $subCategory, $sectionName);
	}

	// desc : redirect 301 for old other exam page like (jee-mains-exampage) to new url
	function redirectOther301($param){
		$examRedirect = $this->load->library('examPages/ExamRedirect');
		$examRedirect->redirectOtherExam($param);
	}
    /**
    * below function is used for AMP Exam Detail Page
    */
    function getAmpExamPage($param, $sectionName)
    {
        $this->getExamPage($param, $sectionName,true);        
    }
	
    function getAllAnnouncements(){
        $examId = (isset($_POST['examId']) && $_POST['examId']>0)?$this->input->post('examId'):0;
        $groupId = (isset($_POST['groupId']) && $_POST['groupId']>0)?$this->input->post('groupId'):0;

        $examModel = $this->load->model('examPages/exampagemodel');
        $displayData['updates'] = $examModel->getUpdateDetails($examId, $groupId);
        $displayData['updateClass'] = 'update-layer-list';
        $html = $this->load->view('mobile_examPages5/newExam/widgets/announcementList',$displayData,true);
        echo json_encode(array('html' => $html));
    }
    function getSimilarExamsLayer()
    {
        $examId = !empty($_POST['examId']) ? $this->input->post('examId') : '';
        $groupId = !empty($_POST['groupId']) ? $this->input->post('groupId') : '';

        if(empty($examId) || empty($groupId))
            return;
        
      $examPageLib = $this->load->library('examPages/ExamPageLib');     
        $showExamList = array();
        $showExamList['similarExams'] = $examPageLib->getSimilarExamWidget($examId,$groupId,10,true);
        $showExamList['examId'] = $examId;
        $showExamList['groupId'] = $groupId;
        $result = $this->load->view('mobile_examPages5/newExam/widgets/similarExamData',$showExamList,true);
        echo json_encode(array('result'=> $result));
    }

    function prepareArticleWidget($examId = 0, $examName = '', $view = 'mobile')
    {
	$this->benchmark->mark('article_widget_start');
        $examPageLib = $this->load->library('examPages/ExamPageLib');
        $displayData['examName'] = $examName;

        if(!empty($examId) && $examId!=0 && is_numeric($examId)){
                //Get articles mapped to Exam
                $examPageModel = $this->load->model('examPages/exampagemodel');
                $blogIdsArr = $examPageModel->getAllBlogIdsMappedToExamId($examId);

                //Get blogIds
                if(!empty($blogIdsArr)){
                        $blogIdsCsv = $examPageLib->getBlogIdsCsv($blogIdsArr);
                }

                //Get details of Articles mapped to Exam
                if($blogIdsCsv != ''){
                        $displayData['examPageArticles']        = $examPageLib->getExamArticles('articles',$blogIdsCsv,10,0);
                        $displayData['examPageArticles']        = $displayData['examPageArticles']['articles'];
                }

                //Load the view file
                if($view == 'mobile'){
                        $this->load->view("mobile_examPages5/newExam/widgets/relatedArticle",$displayData);
                }
                else if($view == 'amp'){
                        $this->load->view("mobile_examPages5/newExam/AMP/relatedArticle",$displayData);
                }
        }
	$this->benchmark->mark('article_widget_end');
    }

    /**
     * purpose: To get ANA widget data
     * @param  examId, examName
     */
    function prepareAnAWidget($examId = 0, $examName = '', $view = 'mobile', $type='view')
    {
	$this->benchmark->mark('ana_widget_start');
        $this->load->helper(array('image','string'));
        $examPageLib = $this->load->library('examPages/ExamPageLib');

        if(!empty($examId) && $examId!=0 && is_numeric($examId)){

            $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            $isAmpFlag = ($view == 'amp') ? true : false;
            $displayData = $examPageLib->getAnAWidgetData($examId, $userId, $count = 2,$isAmpFlag);
            $displayData['loggedInUser'] = $userId;
            $displayData['examName'] = $examName;
            $displayData['listing_type'] = 'ExamPage';
	    $displayData['maxCount'] = 2;

            //Load the view file
            if(isset($displayData['questionsDetail']) && count($displayData['questionsDetail'])>1){
                if($type=='data'){
                    return $displayData;
                }
                if($view == 'mobile'){
                        $this->load->view("mobile_listing5/institute/widgets/qna",$displayData);
                }
                else if($view == 'amp'){
			         $this->load->view("mobile_listing5/course/AMP/Widgets/QnAWidget", $displayData);
                }
            }
        }
	$this->benchmark->mark('ana_widget_end');
    }

    function getContentDeliveryData($examId, $groupId, $view = 'mobile'){
	$this->benchmark->mark('cd_widget_start');
        $examPageLib = $this->load->library('examPages/ExamPageLib');
        $displayData['info'] = $examPageLib->getContentDeliveryData($examId, $groupId);
        if(empty($displayData['info'])){
                return '';
        }
	if($view == 'mobile'){
        	$this->load->view('mobile_examPages5/newExam/widgets/contentDelivery',$displayData);
	}
	else if($view == 'amp'){
		$this->load->view("mobile_examPages5/newExam/AMP/contentDelivery",$displayData);
	}
	$this->benchmark->mark('cd_widget_end');
    }

     function getFeaturedCollege($examId, $groupId, $view = 'mobile'){
	$this->benchmark->mark('fc_widget_start');
        $examPageLib = $this->load->library('examPages/ExamPageLib');
        $displayData['info'] = $examPageLib->getFeaturedCollegeData($examId, $groupId);
                if(empty($displayData['info'])){
                return '';
        }
        shuffle($displayData['info']);
	if($view=='mobile'){
        	$this->load->view('mobile_examPages5/newExam/widgets/featuredCollege',$displayData);
	}else if($view == 'amp'){
		$this->load->view("mobile_examPages5/newExam/AMP/widgets/featuredCollege",$displayData);
	}
	$this->benchmark->mark('fc_widget_end');
    }

    function getGuideSuccess()
    {
        echo json_encode(array('status' => 'success'));die;
    }

    // Exampage Apply Online CTA
    function getApplyOnline($examId,$groupId,$isAmp = false){
        $examId  = (isset($_POST['examId']) && $_POST['examId']>0)?$this->input->post('examId'):$examId;
        $groupId = (isset($_POST['groupId']) && $_POST['groupId']>0)?$this->input->post('groupId'):$groupId;
        $examPageLib = $this->load->library('examPages/ExamPageLib');
        $applyOnlineData = $examPageLib->getApplyOnline($examId, $groupId,true);
        $applyOnlineData['of_creationDate'] = date('M d, Y',strtotime($applyOnlineData['of_last_date']));
        $applyOnlineData['isInternal'] = $applyOnlineData['isExternal'] ? 0 : $applyOnlineData['isExternal'];
        if($isAmp){
            return $applyOnlineData;
        }else{
            echo json_encode($applyOnlineData);
        }
        
    }

    function checkUserIsValidForExam(){
        $courseId = !empty($_GET['courseId'])?$this->input->get('courseId'):$courseId;

        if(empty($courseId))
        {
            return false;  
        }

        $validuser = modules::run('registration/RegistrationForms/isValidExamResponse',$courseId,null,true);
        

        return $validuser;
    }
    function checkActionPerformedOnexamPage($groupId,$action)
    {
        if(empty($groupId) || empty($action))
        {
            return false;
        }
        $examPageLib = $this->load->library('examPages/ExamPageLib');
        return $examPageLib->checkActionPerformedOnGroup($groupId,$action);
    }
}
?>
