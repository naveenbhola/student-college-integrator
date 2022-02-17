<?php

class OnlineFormConversionTracking extends MX_Controller
{
    function index()
    {
        $this->load->model('Online/onlineformtrackingmodel');
        error_log(print_r($_REQUEST,TRUE));
        error_log(print_r($_COOKIE,TRUE));
        
        $pixelId = intval($_REQUEST['id']);
        $page = $_REQUEST['page'];
        
        /**
         * Check if tracking cookie set
         */ 
        $trackingCookie = $_COOKIE['ofpixel_'.$pixelId];
        if($trackingCookie && ($page == 'landing' || $page == 'conversion')) {
            list($userId, $courseId) = explode(':', $trackingCookie);
            $this->onlineformtrackingmodel->trackByPixel($pixelId,$userId,$courseId,$page);
        }
        
        if($page == 'landing' || $page == 'conversion'){

            $loggedInUserInfo = $this->checkUserValidation();
            $loggedInUserId = NULL;
            if(is_array($loggedInUserInfo) && is_array($loggedInUserInfo[0]) && $loggedInUserInfo[0]['userid']) {
                $loggedInUserId = $loggedInUserInfo[0]['userid'];
            }

            $sessionId = getVisitorSessionId();
            $visitorId = getVisitorId();
            $ip_address = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));

            $this->onlineformtrackingmodel->trackPBTConversion($pixelId,$page,$sessionId,$visitorId,$loggedInUserId,$ip_address);
            error_log("PBTconversion : pixelId : ".$pixelId." and sessionId : ".$sessionId." and visitorId : ".$visitorId." and loggedInUserId : ".$loggedInUserId." and ip_address : ".$ip_address." and action : ".$action);
        }
        
        header('Content-Type: image/gif');
        header("Cache-Control: private, no-cache, no-store, must-revalidate");
        echo file_get_contents('public/images/blankImg.gif');
    }
    
    function ofexception()
    {
        error_log(print_r($_REQUEST,TRUE));
    }
    
    function send($courseId,$trackingPageKeyId='')
    {
		$this->load->library('studentFormsDashBoard/dashboardconfig');
	
    	try {
    		$this->trackUserPBT($courseId);	
    	} catch (Exception $E){
			if($E->getMessage() == 'UserNotLoggedIn') {
				$OFData = DashboardConfig::$institutes_autorization_details_array;
                $PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', array(), array($courseId));
                $OFData += $PBTSeoData;
                $courseURL = $OFData[$courseId]['seo_url'];
				header("Location: ".$courseURL);
				exit();
			}
			else {
				header("Location: /mba/resources/application-forms");
				exit();
			}
    	}
    	
		$loggedInUserInfo = $this->checkUserValidation();
        $form = $this->onlineformtrackingmodel->getFormByCourse($courseId);
        if(empty($trackingPageKeyId) || $trackingPageKeyId == 0){
            $trackingPageKeyId = 1263; //default tracking id for online form.
        }
    	$this->_captureResponse($loggedInUserInfo,$courseId,$trackingPageKeyId);
    	header("Location: ".$form['url']);
    	exit();
    }
    
    /*	Function trackUserPBT(): This function writes cookie when a user visits Institute listing or course listing
     * 
     */
    
    function trackUserPBT($courseId, $isListingView='no')
    {	
    	$this->load->model('Online/onlineformtrackingmodel');
    
    	$loggedInUserInfo = $this->checkUserValidation();
    	$loggedInUserId = 0;
    
    	if(is_array($loggedInUserInfo) && is_array($loggedInUserInfo[0]) && $loggedInUserInfo[0]['userid']) {
    		$loggedInUserId = $loggedInUserInfo[0]['userid'];
    	}
		
		if(!$loggedInUserId) {
			throw new exception ("UserNotLoggedIn");
		}
    
    	$courseId = intval($courseId);
    
    	if(!$courseId) {
    		throw new exception ("CourseNotSpecified");
    	}
    
    	$form = $this->onlineformtrackingmodel->getFormByCourse($courseId);
    
    	if(!$form['id'] || !$form['pixel_id'] || !$form['url']) {
    		
    		throw new exception ("form ID, pixel ID, URL not found");
    	}
    
        if($isListingView == 'no') {
    	    $this->onlineformtrackingmodel->track($courseId,$loggedInUserId,'sent');
        }
 
    	/**
    	 * Set tracking cookie
    	*/
    	header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    	setcookie('ofpixel_'.$form['pixel_id'],$loggedInUserId.':'.$courseId,time() + 3600 * 24 * 30, '/', COOKIEDOMAIN);
    }
    /**
     * Capture user as a response
     */ 
    private function _captureResponse($loggedInUserInfo,$courseId,$trackingPageKeyId='')
    {
  	$responseData['listing_id'] = $courseId;
        $responseData['listing_type'] = 'course';
        $responseData['action_type'] = 'Online_Application_Started';
        $responseData['tracking_keyid'] = $trackingPageKeyId;
        Modules::run("response/Response/createResponseByParams", $responseData);      
    }
    
    function admin()
    {
        $numResults = intval($_REQUEST['num']) ? intval($_REQUEST['num']) : 5;
        $currentPage = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$editPixelId = intval($_REQUEST['edit']) ? intval($_REQUEST['edit']) : 0;
		
		$this->load->model('Online/onlineformtrackingmodel');
		$data = array();
		
		if($editPixelId) {
			$form = $this->onlineformtrackingmodel->getFormByPixel($editPixelId);
			$data['form'] = $form;
		}
		else {
			$forms = $this->onlineformtrackingmodel->getForms($numResults,$currentPage);
			$numForms = $this->onlineformtrackingmodel->getFormCount();
		
			$numPages = ceil($numForms/$numResults);
			
			
			$data['forms'] = $forms;
			$data['numResults'] = $numResults;
			$data['currentPage'] = $currentPage;
			$data['numPages'] = $numPages;
		}
        $this->load->view('conversionTrackingAdmin',$data);
    }
    
    function pixelCode()
    {
        $pixelId = intval($_REQUEST['id']);
        $this->load->model('Online/onlineformtrackingmodel');
        $form = $this->onlineformtrackingmodel->getFormByPixel($pixelId);
		
		$scheme = 'http';
		
		$landingPageURL = $form['url'];
		if(substr($landingPageURL, 0, 8) == 'https://') {
			$scheme = 'https';
		}
		
        $data = array();
        $data['form'] = $form;
		$data['scheme'] = $scheme;
        $this->load->view('conversionTrackingPixelCode',$data);
    }
    
    function addCourse()
    {
        $courses = trim($this->input->post('courseIds'));
        $OFURL = trim($this->input->post('OFURL'));
		$pixelId = intval(trim($this->input->post('pixelId')));
		
        if(!$courses || !$OFURL) {
            header("Location: /enterprise/Enterprise/index/817/admin");
            exit();
        }
        
		$courseIds = array();
		if($courses) {
			$courseIds = explode(',', $courses);
			$courseIds = array_map('trim', $courseIds);
		}
		
        $this->load->model('Online/onlineformtrackingmodel');
        $this->onlineformtrackingmodel->saveForm($courseIds,$OFURL,$pixelId);
        
        header("Location: /enterprise/Enterprise/index/817/admin");
        exit();
    }
	
	function checkCourses()
	{
		$courses = trim($this->input->post('courseIds'));
		$pixelId = trim($this->input->post('pixelId'));
		
		$courseIds = array();
		if($courses) {
			$courseIds = explode(',', $courses);
			$courseIds = array_map('trim', $courseIds);
		}
		
		$this->load->model('Online/onlineformtrackingmodel');
        $existingCourses = $this->onlineformtrackingmodel->getExistingCourseIds($courseIds, $pixelId);
		
		
		$data = array();
		$data['existingCourses'] = $existingCourses;
		
		echo json_encode($data);
	}

    // added by akhter
    // to check course is paid/free for made response on PBT form
    private function _isCoursePaid($courseId){
        $this->load->builder('CourseBuilder','nationalCourse');
        $courseBuilder = new CourseBuilder;
        $courseRepo = $courseBuilder->getCourseRepository();
        $courseObj = $courseRepo->find($courseId);
        $isPaid=$courseObj->isPaid();
        return $isPaid;
    }

}
