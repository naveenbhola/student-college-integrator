<?php
class AnAController extends ShikshaMobileWebSite_Controller{
    
    //constructor
    public function _construct(){
        
    }
    
    //initialize data
    private function _init(){
	$this->load->library(array('message_board_client'));
        $this->load->helper(array('mAnA5/ana'));
        $this->msgbrdClient = new Message_board_client();
        if($this->userStatus == ""){
	    $this->userStatus = $this->checkUserValidation();
        }
        $this->load->config('mcommon5/mobi_config');
        $this->load->model('QnAModel');
    }
    
    /*
     *function Name: getDetailPage
     *parameters: questionId
    */
    public function getDetailPage($questionId){
        $this->_init();
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup    = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $questionId   = isset($_POST['questionId'])?$this->input->post('questionId'):$questionId;
        if(!preg_match('/^\d+$/', $questionId) || $questionId <= 0){
            show_404();
        }
        $displayData = array();
        $displayData['userStatus'] = $this->userStatus;
        $start=0;$count=3;$filter='reputation';
        $startCount                 = isset($_POST['startCount'])?$this->input->post('startCount'):$start;
        $offsetCount                = isset($_POST['offsetCount'])?$this->input->post('offsetCount'):$count;
        $displayData['startCount']  = $startCount;
        $displayData['offsetCount'] = $offsetCount;
        $callType = isset($_POST['callType'])?$this->input->post('callType'):'NONAJAX';
        $displayData['callType'] = $callType;
        $courseId = $this->QnAModel->getCourseIdOfQuestion($questionId);
        if($questionId>0){
            if($courseId==0){
                setcookie('showAnAQDPOnMobile','YES',0,'/',COOKIEDOMAIN);
                if( (strpos($_SERVER['REQUEST_URI'], "http") === false) || (strpos($_SERVER['REQUEST_URI'], "http") != 0) || (strpos($_SERVER['REQUEST_URI'], SHIKSHA_HOME) === 0) || (strpos($_SERVER['REQUEST_URI'],SHIKSHA_ASK_HOME_URL) === 0) || (strpos($_SERVER['REQUEST_URI'],SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($_SERVER['REQUEST_URI'],ENTERPRISE_HOME) === 0) ){
                    header("Location: ".$_SERVER['REQUEST_URI'],TRUE,301);
                }
                else{
                    header("Location: ".SHIKSHA_HOME,TRUE,301);
                }
                exit;
            }
            $questionThreadData            = $this->msgbrdClient->getMsgTree($appId,$questionId,$startCount,$offsetCount,1,$userId,$userGroup,$filter);
            $formattedData                 = formatQuestionThreadData($questionThreadData);
            $displayData['detailPageData'] = $formattedData;
            $displayData['questionId'] = $questionId;
            if($courseId>0){              
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder();
                $courseRepository = $listingBuilder->getCourseRepository();
                $courseObj = $courseRepository->find($courseId);
                
                $instituteId = $courseObj->getInstId();
                if($instituteId=='' || $instituteId==0 || !is_object($courseObj) || empty($courseObj)){
                      show_404();
                }

                $instituteRepository = $listingBuilder->getInstituteRepository();
                $instituteObj = $instituteRepository->find($instituteId);
                if($instituteObj->getId()=='' || $instituteObj->getId()==0){
                      show_404();
                }
                $currentLocation = $courseObj->getMainLocation();
                $displayData['courseObj']         = $courseObj;
                $displayData['insObj']            = $instituteObj;
                $displayData['instituteName']     = formatInstituteName($instituteObj, $currentLocation);
                $displayData['totalAnswerCount']  = $questionThreadData[0]['mainAnsCount'];
                $campusRepData = array();
                $this->cadiscussionmodel                = $this->load->model('CA/cadiscussionmodel');
		$campusRepData                          = $this->cadiscussionmodel->getCampusReps($courseId, $instituteId);
                $displayData['formattedCAData']         = formatCAData($campusRepData);
                $displayData['courseId']         = $courseId;

		//DO not show Answer form to the Institute owner if Campus rep is available
                $ownerId = $courseObj->getClientId();
                $displayData['doNoShowAnswerForm'] = false;
		if(is_array($campusRepData) && is_array($campusRepData['data']) && count($campusRepData['data'])>0 && $ownerId==$userId){
                        $displayData['doNoShowAnswerForm'] = true;
                }

            }
        }
        
        $seoTitle = seo_url($formattedData['question'][$questionId]['msgTxt']," ");
        $displayData['m_meta_title']         = $seoTitle;
        $displayData['m_meta_description']   = 'Read all answers to question: '.substr($seoTitle, 0, 120);
        $displayData['m_meta_keywords']      = ' ';
        $displayData['m_canonical_url']      = getSeoUrl($questionId,'question',$formattedData['question'][$questionId]['msgTxt'],'','',$formattedData['question'][$questionId]['msgTxt']["creationDate"]);
        $displayData['userIdOfLoginUser']    = $userId;
        $displayData['userGroup']            = $userGroup;

        
        //below lines used for conversion tracking purpose
            $displayData['ctrackingPageKeyId']=341;
            $displayData['atrackingPageKeyId']=340;
            $displayData['qtrackingPageKeyId']=342;

        //for beacon tracking purpose
        $displayData['trackingpageIdentifier']='questionDetailPage';
        $displayData['trackingpageNo'] = $questionId;
        $displayData['trackingcountryId']=2;

        //below line is used to store inforamtion in beacon variable for tacking purpose
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($displayData);



        if($callType=='AJAX'){
            echo $this->load->view('answerCommentSection',$displayData);
        }else{
            if(MENTORSHIP_PROGRAM_FLAG == 'true'){
                $this->load->library(array('listing/NationalCourseLib'));
                $nationalCourseLib = new NationalCourseLib();
                $subCatIdInfo = $nationalCourseLib->getCourseDominantSubCategoryDB(array($courseId));
                $subCatId = $subCatIdInfo['subCategoryInfo'][$courseId]['dominant'];
                $displayData['subCatId'] = $subCatId;
            }
            $this->load->view('anaWrapper',$displayData);
        }
    }
        
    /*
     *function Name: loadOtherQuestionSectionForCourse
     *parameters: courseId, questionId
    */
    function loadOtherQuestionSectionForCourse($courseId, $questionId){
        $this->load->model('CA/cadiscussionmodel');
	$this->cadiscussionmodel = new CADiscussionModel();
        $this->load->library('CA/CADiscussionHelper');
        $caDiscussionHelper =  new CADiscussionHelper();
        $qna = array();
        $qna = $this->cadiscussionmodel->getQnA(array('courseId' => $courseId),1,'question','',0,5,'all',0 ,array($questionId));
        $displayData['totalQuesCount'] = $qna['total'];
        $qna = $caDiscussionHelper->rearrangeQnA($qna['data']);
        $displayData['qna'] = $qna['data'];
        $pageData['showLoadMoreLink'] = true;
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder();
        $courseRepository = $listingBuilder->getCourseRepository();
        $courseObj = $courseRepository->find($courseId);
        $displayData['courseObj']         = $courseObj;
        $this->load->view('otherQuestionSectionForCourse',$displayData);
    }
 
public function postQuestion()
    {
	$this->_init();
	
	//set cookie for referral url
	$positionOfShiksha = ($_SERVER['HTTP_REFERER'] !='') ? strpos($_SERVER['HTTP_REFERER'],'shiksha') : '';
	
	if(is_int($positionOfShiksha) && $_COOKIE['post_referral'] == ''){
	   setcookie('post_referral',base64_encode($_SERVER['HTTP_REFERER'].'#ca_aqf'),0,'/',COOKIEDOMAIN); 
	}

	if(isset($_POST) && count($_POST) == 0 && empty($_POST) && $_COOKIE['post_referral'] == '')
	{
	    redirect(SHIKSHA_HOME);
	    
	}else if(count($_POST) == 0 && $_COOKIE['post_referral'] !='')
	{
	    redirect(base64_decode($_COOKIE['post_referral']));
	}
	$displayData['instituteId'] = $_POST['instituteId'];
	$displayData['locationId'] = $_POST['locationId'];
	$displayData['courseId'] = $_POST['courseId'];
	if($displayData['courseId'] !='' && $displayData['courseId'] >0){
	    $this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder();
	    $courseRepository = $listingBuilder->getCourseRepository();
	    $courseObj = $courseRepository->find($displayData['courseId']);
	    $displayData['coursePageURL'] = $courseObj->getURL();
	}
	$displayData['categoryId'] = $_POST['categoryId'];
	$displayData['getmeCurrentCity'] = $_POST['getmeCurrentCity'];
	$displayData['getmeCurrentLocaLity'] = $_POST['getmeCurrentLocaLity'];
	$displayData['pageName'] = 'postQuestion';
	$displayData['userStatus'] = $this->userStatus;
	
	//for beacon tracking purpose
    $displayData['trackingpageIdentifier']='coursePageQuestionPosting';
    $displayData['trackingcountryId']=2;
    $displayData['trackingcatID']=$_POST['categoryId'];

    //below line is used to store inforamtion in beacon variable for tacking purpose
    $this->tracking=$this->load->library('common/trackingpages');
    $this->tracking->_pagetracking($displayData);

    //below line is used for conversion tracking purpose
    $trackingPageKeyId=$this->input->post('tracking_keyid');
    if(isset($trackingPageKeyId))
    {
        $displayData['trackingPageKeyId']=$trackingPageKeyId;
    }
	
	$this->load->view('postQuestionPage',$displayData);
    }

    public function postQuestionFromMyShortlist($instituteId, $courseId,$trackingPageKeyId='')
    {
        $this->_init();
        
        $displayData['instituteId'] = $instituteId;
        $displayData['courseId'] = $courseId;

        if($displayData['courseId'] !='' && $displayData['courseId'] >0){
            $this->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder();
            $courseRepository = $listingBuilder->getCourseRepository();
            $courseObj = $courseRepository->find($displayData['courseId']);
            $displayData['coursePageURL'] = $courseObj->getURL();

            $instituteRepository = $listingBuilder->getInstituteRepository();
            $instObj = $instituteRepository->find($displayData['instituteId']);
            $displayData['locationId'] = $instObj->getMainLocation()->getCountry()->getId();

            $categories = $instituteRepository->getCategoryIdsOfListing($courseId,'course');
            $categoryId = 0;
              if(count($categories) > 0 && is_array($categories) ){
                $categoryId = $categories[0];
            }
            $displayData['currentLocation'] = $courseObj->getMainLocation();
        }
        $displayData['categoryId'] = $categoryId;
        $displayData['userStatus'] = $this->userStatus;
        $displayData['qtrackingPageKeyId'] = $trackingPageKeyId;
        
        echo $this->load->view('mobile_myShortlist5/widgets/ask_course_form',$displayData, true);
    }
    
    public function trackThreadView(){

        $this->_init();
    	$userId			= isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    	$visitorId		= getVisitorId();
    	$threadViewData = $this->input->post('threadViewData');
    	$pageSource		= $this->input->post('pageSource');
    	$pageType		= $this->input->post('pageType');

        $isMobileRequest = 0;
        if($_COOKIE['ci_mobile_js_support'] == 'yes' || $GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'){
            $isMobileRequest = 1;
        }
        $pageSource = ($isMobileRequest == 1) ? 'mobilesite' : '';

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

}

?>
