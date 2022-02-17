<?php
class UserActivityAMP extends MX_Controller{

	/**
	 * Init Function to load the library
	 */
	function init()	{
		$this->userStatus = $this->checkUserValidation();
        if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid=$this->userStatus[0]['userid'];
        } else {
            $this->userid=-1;
        }
	}

	function getLoginAmpPage(){
		$this->init();
		$this->load->helper('string');
        $fromWhere = isset($_GET['fromwhere'])?$this->input->get('fromwhere',true):$fromWhere;

        $displayData['regFormId'] = random_string('alnum', 6);
        
        $displayData['HTTP_REFERER'] = $this->prepareNonAmpPageUrl();

        if($fromWhere == 'articleDetailPage'){
            $displayData['HTTP_REFERER'] = getAmpPageURL('blog',$displayData['HTTP_REFERER']);
        }
        if($fromWhere == 'pwa'){
            $displayData['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
	}

	if($fromWhere == 'pwaResponseLogin'){

            $separator = '?';
            if(strpos($_SERVER['HTTP_REFERER'],"?") !== false) $separator = '&';
            $displayData['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'].$separator.$_SERVER['QUERY_STRING'];
        }

        //$displayData['loggedInUrl'] = $_SERVER['HTTP_REFERER'];

        $displayData['beaconTrackData'] = array(
                                    'pageIdentifier' => 'loginPage',
                                    'extraData'      => array('isAmpPage' => true)
                                );
	 	$displayData['gtmParams'] = array(
                    "pageType"    => 'loginAMPPage',
                    "countryId"     => 2
            );

        
		if($this->userid > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        if($this->userid > 0){
			$displayData['userId'] = $this->userid;
		}
        $displayData['trackingKeyId'] = 1217; // AMP hamburger Registraion key
        $displayData['trackingcountryId']=2;
        $this->load->view('/muser5/loginLayerAmpPage',$displayData);
	}

	function getRegistrationAmpPage(){
		$this->init();

        $listingId = isset($_GET['listingId'])?$this->security->xss_clean($this->input->get('listingId')):'';
        $listingType = isset($_GET['listingType'])?$this->security->xss_clean($this->input->get('listingType')):'course';
        $actionType = isset($_GET['actionType'])?$this->security->xss_clean($this->input->get('actionType')):'';
        $fromwhere = isset($_GET['fromwhere'])?$this->security->xss_clean($this->input->get('fromwhere')):'';
        $regType = isset($_GET['regType'])?$this->security->xss_clean($this->input->get('regType')):'';
        $position = isset($_GET['pos'])?$this->security->xss_clean($this->input->get('pos')):'';
	    $widget = isset($_GET['widget'])?$this->security->xss_clean($this->input->get('widget')):'';
        $referer = isset($_GET['referer'])?$this->input->get('referer',true):'';
        $trackData = isset($_GET['trackData'])?$this->input->get('trackData',true):'';
        $widgetType = isset($_GET['widgetType'])?$this->input->get('widgetType',true):'';

        //list of action types avaliable in shiksha for response form
        $actionTypes = array('applynow');
        $ampPages = array('coursepage');

        $HTTP_REFERER = $this->prepareNonAmpPageUrl();

        if($fromwhere == 'articleDetailPage'){
            $HTTP_REFERER = getAmpPageURL('blog',$HTTP_REFERER);
        }

        $displayData['regType'] = $regType;
        $displayData['customFields'] = [];

        if(!empty($listingId) && in_array($actionType, $actionTypes)){
            $displayData['HTTP_REFERER'] =$HTTP_REFERER.'?actionType='.$actionType.'&fromwhere=response&pos=top';
        }else{
            $displayData['HTTP_REFERER'] = $HTTP_REFERER;
        }

        if($fromwhere == 'articleDetailPage'){
            $displayData['HTTP_REFERER'] = getAmpPageURL('blog',$displayData['HTTP_REFERER']);
        }

		if($fromwhere == 'pwa'){
            $displayData['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
        }
        if($regType == 'shortRegistration'){
            $displayData['HTTP_REFERER'] =  $HTTP_REFERER;  
        }

        if($fromwhere == 'CHP' && $actionType=='downloadGuide'){
            $attribute = isset($_GET['attribute'])?$this->input->get('attribute',true):'';
            $attribute = json_decode(base64_decode($attribute),true);
            $url = SHIKSHA_HOME.base64_decode($referer).'?actionType=downloadGuide';
            $displayData['HTTP_REFERER']  = $url;
            $displayData['fromwhere'] = 'CHP';
            $data = Array();
            if($attribute['streamId']){
                $data['stream'] = array('value'=>$attribute['streamId'],'hidden'=>1,'disabled'=>0);
            }
            if($attribute['baseCourse']){
                $data['baseCourses'] = array('value'=>$attribute['baseCourse'],'hidden'=>1,'disabled'=>0);   
            }
            if($attribute['substreamId']){
                $data['subStreamSpec'] = array('value'=> array($attribute['substreamId'] => array($attribute['specializationId']),'ungrouped'=>array()), 'hidden'=>1,'disabled'=>0);   
            }
            if($attribute['substreamId'] == '' && $attribute['specializationId']){
                $data['subStreamSpec'] = array('value'=> array('ungrouped'=>array($attribute['specializationId'])), 'hidden'=>1,'disabled'=>0);   
            }
            $customAttr = json_encode($data);
            $displayData['customFields'] = $customAttr;
        }

        if($fromwhere == 'allCollegePredictor' && $actionType=='finalStep'){
            $data = Array();
            $url = SHIKSHA_HOME.base64_decode($referer);
            $displayData['HTTP_REFERER']  = $url;
            $displayData['fromwhere'] = 'allCollegePredictor';
            $data['stream'] = array('value'=>2,'hidden'=>1,'disabled'=>0);
            $data['baseCourses'] = array('value'=>10,'hidden'=>1,'disabled'=>0);   
            $data['subStreamSpec'] = array('hidden'=>1,'disabled'=>0);
            $data['educationType'] = array('value'=>20,'hidden'=>1,'disabled'=>0);        
            $customAttr = json_encode($data);
            $displayData['customFields'] = $customAttr;
            $displayData['trackData'] = $trackData;
            
        }
        if($fromwhere == 'feedback'){
            $url = base64_decode($referer);
            $displayData['HTTP_REFERER']  = $url;
            $displayData['fromwhere'] = 'feedback';
            $displayData['trackData'] = $trackData;
        }

        if(!($fromwhere == 'androidAppWalkThrough' || $fromwhere == 'androidAppLoginPage')){
            $displayData['beaconTrackData'] = array(
                'pageIdentifier' => 'registrationPage',
                'extraData'      => array('isAmpPage' => true)
            );
        }

        $displayData['gtmParams'] = array(
            "pageType"    => 'registrationAMPPage',
            "countryId"     => 2
        );
            

		if($this->userid > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        if($this->userid > 0){
			$displayData['userId'] = $this->userid;
		}

        if(!empty($fromwhere))
        {
            $this->load->config('common/misTrackingKey');
            $ampPageKeyArr = $this->config->item($fromwhere); 

            if(isset($widget) && !empty($widget)){
                $displayData['trackingKeyId'] = $ampPageKeyArr[$widget];
            }
            else if($actionType == 'applynow'){
                $displayData['trackingKeyId'] = ($position == 'top')?$ampPageKeyArr['APPLY_NOW_TOP_WIDGET']:$ampPageKeyArr['APPLY_NOW_WIDGET'];
            }
            else if($actionType == 'registration'){
                if($fromwhere == 'androidAppLoginPage'){
                    $displayData['trackingKeyId'] = 599;
                }else if($fromwhere == 'androidAppWalkThrough'){
                    $displayData['trackingKeyId'] = 3631;
                }else{
                    $displayData['trackingKeyId'] = ($position == 'top')?$ampPageKeyArr['REGISTRATION_WIDGET_TOP']:$ampPageKeyArr['REGISTRATION_WIDGET'];    
                }
                
            }else if($fromwhere == 'CHP' && $actionType=='downloadGuide'){
                $displayData['trackingKeyId'] = ($widgetType == 'bottomSticky') ? 3329 : 1895;
            }else if($fromwhere == 'allCollegePredictor'){
                $displayData['trackingKeyId'] = 3217;
            }else if($fromwhere == 'feedback'){
                $displayData['trackingKeyId'] = 3635;
            }else{
                $displayData['trackingKeyId'] = $ampPageKeyArr['HAMBUERGER_REGISTRATION_KEY'];
            }
            
        }
        $displayData['trackingcountryId']=2;
        //_p($displayData);die;
        $this->load->view('/registration/registrationFormAmp',$displayData);
	}






	function getResponseAmpPage(){
		$this->init();
		
		//$displayData['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
        $listingId = isset($_GET['listingId'])?$this->security->xss_clean($this->input->get('listingId')):'';
        $listingType = isset($_GET['listingType'])?$this->security->xss_clean($this->input->get('listingType')):'course';
        $actionType = isset($_GET['actionType'])?$this->security->xss_clean($this->input->get('actionType')):'download brochure';
        $fromwhere = isset($_GET['fromwhere'])?$this->security->xss_clean($this->input->get('fromwhere')):'';
        $position = isset($_GET['pos'])?$this->security->xss_clean($this->input->get('pos')):'';
        $fromFeeDetails = isset($_GET['fromFeeDetails'])?$this->security->xss_clean($this->input->get('fromFeeDetails')):'';

        //list of action types avaliable in shiksha for response form
        $actionTypes = array('compare','contact','shortlist','brochure','applynow','intern','placement','checked_fee_details');
        $ampPages = array('coursepage', 'university', 'institute','articleDetailPage');
        if(!in_array($fromwhere, $ampPages) || !in_array($actionType, $actionTypes) || !(is_numeric($listingId) && $listingId > 0 ))
        {
            show_404();
            exit(0);
        } 

        $displayData['beaconTrackData'] = array(
                                    'pageIdentifier' => 'responsePage',
                                    'extraData'      => array('isAmpPage' => true)
                                );
	 	$displayData['gtmParams'] = array(
                    "pageType"    => 'responseAMPPage',
                    "countryId"     => 2
            );
        
		if($this->userid > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }

        if(!empty($fromwhere))
        {
            $this->load->config('common/misTrackingKey');
            $displayData['ampKeys'] = $this->config->item($fromwhere);    
        }
        if($this->userid > 0){
			$displayData['userId'] = $this->userid;
		}
        
        $displayData['trackingcountryId']=2;

        $displayData['redirectUrl'] = $this->prepareNonAmpPageUrl();
        //$displayData['redirectUrl'] = 'http://localshiksha.com:3022/mba/course/post-graduate-diploma-in-management-asia-pacific-institute-of-management-delhi-jasola-vihar-122193';
        if($fromwhere == 'articleDetailPage'){
            $displayData['redirectUrl'] = getAmpPageURL('blog',$displayData['redirectUrl']);
        }
        $displayData['HTTP_REFERER'] = $displayData['redirectUrl'];
        
        $displayData['fromwhere'] = $fromwhere;
        $displayData['position'] = $position;
        $displayData['actionType'] = $actionType;
        $displayData['listingId'] =$listingId;
        $displayData['listingType'] = $listingType;
        $displayData['fromFeeDetails'] = $fromFeeDetails;
        $this->load->view('/responseFormAmp',$displayData);
	}

    function prepareNonAmpPageUrl($fromWhere='', $listingId='',$entityId = null,$entityType = null){
        $fromWhere = isset($_GET['fromwhere'])?$this->input->get('fromwhere',true):$fromWhere;
        $listingId = isset($_GET['listingId'])?$this->input->get('listingId',true):$listingId;
        $entityId = isset($_GET['entityId'])?$this->security->xss_clean($this->input->get('entityId')):'';
        $entityType = isset($_GET['entityType'])?$this->security->xss_clean($this->input->get('entityType')):'';

        if($fromWhere == 'coursepage' && $listingId){
            $this->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $courseRepo = $courseBuilder->getCourseRepository(); 
            $courseObj  = $courseRepo->find($listingId,array('basic')); 
            $url = $courseObj->getURL();
        }else if(($fromWhere == 'institute' || $fromWhere == 'university') && $listingId){
            $this->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $instituteRepo = $instituteBuilder->getInstituteRepository();      
            $instituteObj  = $instituteRepo->find($listingId,array('basic')); 
            $url = $instituteObj->getURL();
        }
        else if($fromWhere == 'exampage' && $listingId)
        {
            $this->load->builder('ExamBuilder','examPages');
            $examBuilder          = new ExamBuilder();
            $examRepository = $examBuilder->getExamRepository();
            $groupObj = $examRepository->findGroup($listingId);
            $examId = $groupObj->getExamId();
            if(!empty($examId))
            {
                $examObj = $examRepository->find($examId);    
            }
            if(!empty($examObj))
            {
                $url = $examObj->getUrl();
            }
        }
        else if($fromWhere == 'articleDetailPage'){
            if($entityType == 'blog'){
                $this->load->library('common/Seo_client');
                $Seo_client = new Seo_client();
                $dbURL = $Seo_client->getURLFromDB($entityId,'blog');
                $url = addingDomainNameToUrl(array('url' => $dbURL['URL'],'domainName' => SHIKSHA_HOME));
            }
        }
        return ($url) ? $url : SHIKSHA_HOME;
    }

    function validateBrowser($fromWhere, $listingId,$entityId = null,$entityType = null){
        $url = $this->prepareNonAmpPageUrl($fromWhere, $listingId,$entityId,$entityType);
        $browserList = array('UCBrowser');// these browser does not support AMP, so we redirect 302 to non amp page
        foreach($browserList as $os)
        {
            if(isset($_SERVER["HTTP_USER_AGENT"]) && (strstr($_SERVER["HTTP_USER_AGENT"], $os) && strstr($_SERVER["HTTP_USER_AGENT"], 'Microsoft') && $os == 'Windows')) {
                redirect($url, 'location', 302);exit();
            }else if(isset($_SERVER["HTTP_USER_AGENT"]) && strstr($_SERVER["HTTP_USER_AGENT"], $os)){
                redirect($url, 'location', 302);exit();
            }
        }
    }

    function getResponseExamAmpPage(){
        $this->init();
        
        //$displayData['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
        $examGroupId = isset($_GET['examGroupId'])?$this->security->xss_clean($this->input->get('examGroupId')):'';
        $actionType = isset($_GET['actionType'])?$this->security->xss_clean($this->input->get('actionType')):'download guide';
        $responseType = isset($_GET['responseType'])?$this->security->xss_clean($this->input->get('responseType')):'exam';
        $fromwhere = isset($_GET['fromwhere'])?$this->security->xss_clean($this->input->get('fromwhere')):'';
        $sectionName = isset($_GET['sectionName'])?$this->security->xss_clean($this->input->get('sectionName')):'';
        $position = isset($_GET['pos'])?$this->security->xss_clean($this->input->get('pos')):'';
        $fileNo = isset($_GET['fileNo'])?$this->security->xss_clean($this->input->get('fileNo')):'';
        $instituteName = isset($_GET['instituteName'])?$this->security->xss_clean($this->input->get('instituteName')):'';
        $courseId = isset($_GET['courseId'])?$this->security->xss_clean($this->input->get('courseId')):'';
        $isInternal = isset($_GET['isInternal'])?$this->security->xss_clean($this->input->get('isInternal')):'';
        $clickId = isset($_GET['clickId'])?$this->security->xss_clean($this->input->get('clickId')):'';

        //list of action types avaliable in shiksha for response form
        $actionTypes = array('exam_download_guide','exam_download_sample_paper','exam_download_prep_guide','exam_subscribe_to_latest_updates','exam_download_application_form','exam_apply_online','exam_download_prep_tip');
        $ampPages = array('exampage');
    
        if(!in_array($fromwhere, $ampPages) || !in_array($actionType, $actionTypes) || !(is_numeric($examGroupId) && $examGroupId > 0 ))
        {
            show_404();
            exit(0);
        } 

        $displayData['beaconTrackData'] = array(
                                    'pageIdentifier' => 'responseExamPage',
                                    'extraData'      => array('isAmpPage' => true)
                                );
        $displayData['gtmParams'] = array(
                    "pageType"    => 'responseExamAMPPage',
                    "countryId"     => 2
            );
        
        if($this->userid > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }
        $sectionName = ($sectionName) ? $sectionName : 'homepage';
        if(!empty($fromwhere))
        {
            $this->load->config('examPages/examPageTrackingIdConfig');
            $ampTrackKeys = $this->config->item('examPageTrackingKeys');    
            $displayData['trackingKeyId'] = $ampTrackKeys[$sectionName]['AMP'][$clickId];
        }
        if($this->userid > 0){
            $displayData['userId'] = $this->userid;
        }
        
        $displayData['trackingcountryId']=2;

        $displayData['redirectUrl'] = $this->prepareNonAmpExamPageUrl();
        $displayData['HTTP_REFERER'] = $displayData['redirectUrl'];
        
        $displayData['fromwhere'] = $fromwhere;
        $displayData['actionType'] = $actionType;
        $displayData['examGroupId'] = $examGroupId;
        $displayData['responseType'] = $responseType;
        $displayData['position'] = $position;
        $displayData['fileNo'] = $fileNo;
        $displayData['instituteName'] = $instituteName;
        $displayData['courseId'] = $courseId;
        $displayData['isInternal'] = $isInternal;
        $displayData['clickId'] = $clickId;
        $displayData['sectionName'] = $sectionName;
        $this->load->view('/responseFormAmp',$displayData);
    }

    function prepareNonAmpExamPageUrl($fromWhere='', $examGroupId='',$sectionName=''){
        $fromWhere = isset($_GET['fromwhere'])?$this->input->get('fromwhere',true):$fromWhere;
        $examGroupId = isset($_GET['examGroupId'])?$this->input->get('examGroupId',true):$examGroupId;
        $sectionName = isset($_GET['sectionName'])?$this->input->get('sectionName',true):$sectionName;

        if(!empty($examGroupId) && preg_match('/^\d+$/', $examGroupId)){
            $examGroupId = $examGroupId;
        }
        else
        {
            return SHIKSHA_HOME;
        }

        $this->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $this->examRepository     = $examBuilder->getExamRepository();
        $groupObj  = $this->examRepository->findGroup($examGroupId);
        $examId = $groupObj->getExamId();
        $examBasicObj = $this->examRepository->find($examId, false);
        if(empty($examBasicObj)){
            return;
        }

        $primaryGroup   = $examBasicObj->getPrimaryGroup();
        $primaryGroupId = $primaryGroup['id'];
        $examName = $examBasicObj->getName();

        $this->examRequest        = $this->load->library('examPages/ExamPageRequest');
        $this->examRequest->setExamName($examName);
        $examPageUrl = $this->examRequest->getUrl($sectionName,true, false);
        return ($examPageUrl) ? $examPageUrl : SHIKSHA_HOME;
    }
}
