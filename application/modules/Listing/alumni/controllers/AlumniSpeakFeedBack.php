<?php 
   /*

   Copyright 2007 Info Edge India Ltd
   This is file for shiksha help pages.
   All the static pages related to help and static pages like "about us" can be included over here.

   */
class AlumniSpeakFeedBack extends MX_Controller {
	private $userStatus = 'false';
	function init(){
		$this->load->helper(array('url','form'));	
		$this->load->library(array('ajax','alerts_client'));
		$this->load->library('alumniSpeakClient');
		$this->load->library('listing_client');
		$this->load->library('MailerClient');
		$this->userStatus = $this->checkUserValidation();
	}
	
	function index($key, $mailerId = 99, $templateId =11) {
        //added by aman story Id LF-6458

        show_404();
        exit(0);

        $appId = 1;
		$this->init();
		$data = array();
		$data['validateuser'] = $this->userStatus;
        $objAlumniSpeakClientObj = new AlumniSpeakClient();
        $criterias = $objAlumniSpeakClientObj->getFeedBackCriterias($appId);
        $data['criterias'] = $criterias;
        $key = strtr($key,'~_&','+/=');
        $decryptedKey = $this->encodeDecodeKey($key,'decrypt');
        list($email,$instituteId,$instituteName) = explode('#',$decryptedKey);
        
        
        // _p($instituteCourses); die;
        $data['instituteCourses'] = $this->getCoursesOfInstitute($instituteId);
        $data['email'] = $email;
        $data['instituteId'] = $instituteId;
        $data['instituteName'] = $instituteName;
        $data['mailerId'] = $mailerId;
        $data['templateId'] = $templateId;
		 $this->load->view('alumni/alumniSpeakFeedbackForm',$data);
    }
    
    function getCoursesOfInstitute($instituteId) {
	$this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $instituteRepository = $listingBuilder->getInstituteRepository();
        $coursesInfo = $instituteRepository->getCoursesOfInstitutes(array($instituteId));
	$courseIdsArray = explode(",",$coursesInfo[$instituteId]['courseList']);	
	$courseModel = $this->load->model('listing/coursemodel');
	$courseListArray = $courseModel->getDataForMultipleCourses($courseIdsArray, array('general'));
	foreach($courseListArray as $courseId => $courseInfo) {
		$instituteCourses[$courseId] = $courseInfo['general']['courseTitle'];
	}
	return $instituteCourses;
    }

    function postFeedback($inviteToken = false) {
        $appId = 1;
        $this->init();
	if($inviteToken === false) {
		$AlumniSpeakFeedback = new AlumniSpeakClient();
		$feedback = array();
		foreach($_POST['criteriaId'] as $criteriaIndex=> $criteriaId) {
			$feedback[$criteriaId]['criteriaRating'] = $_POST['criteriaRating'][$criteriaIndex];
			$feedback[$criteriaId]['criteriaDescription'] = $_POST['criteria_description'][$criteriaIndex];
		}
		$requestArray['name'] = $_POST['name'];
		// $requestArray['course_completed'] = $_POST['course_completed'];
		if($_POST['institute_courses'] == -1) {
			$requestArray['course_completed'] = $_POST['course_completed'];
			$requestArray['course_id'] = '-1';
		} else {
			$requestArray['course_completed'] = '';
			$requestArray['course_id'] = $_POST['institute_courses'];
		}
		
		$requestArray['course_comp_year'] = $_POST['course_comp_year'];
		$requestArray['organisation'] = $_POST['organisation'];
		$requestArray['designation'] = $_POST['designation'];
		$requestArray['email'] = $_POST['email'];
		$requestArray['instituteId'] = $_POST['instituteId'];
		$requestArray['instituteName'] = $_POST['instituteName'];
		$requestArray['feedback'] = $feedback;
		$requestArray['legalFlag'] = $_POST['legalFlag'] == 1 ? 'yes' : 'no';
		$requestArray['showOnShikshaFlag'] = $_POST['showOnShikshaFlag'] != 1 ? 'yes' : 'no';
		$feedbackStatus = $AlumniSpeakFeedback->insertAlumnusFeedBack($appID,base64_encode(json_encode($requestArray)));
		$requestArray['mailerId'] = $_POST['mailerId'];
		$requestArray['templateId'] = $_POST['templateId'];
		$requestArray['feedbackStatus'] = $feedbackStatus;
	} else {
		$inviteRequest = explode("@@@", base64_decode($inviteToken));
		$requestArray['email'] = $inviteRequest[0];
		$requestArray['templateId'] = $inviteRequest[1];
		$requestArray['mailerId'] = $inviteRequest[2];
		$requestArray['instituteId'] = $inviteRequest[3];
		$requestArray['instituteName'] = $inviteRequest[4];
		$requestArray['feedbackStatus'] = '';
	}
        //print_r($requestArray);
	$this->load->view('alumni/alumniThanks',$requestArray);
    }

    function alumniFeedbackCompletion(){
        $this->init();
        $AlumniSpeakFeedback = new AlumniSpeakClient();
        /*print_r($_POST);
        echo '<br/>';
         */
        $appID = 1;
        $user = 1;
        $userGroup  = 'cms';
        $instituteId = $_POST['instituteId'];
        $instituteName = $_POST['instituteName'];
        $fromEmail = $_POST['fromEmail'];
        $templateId = $_POST['templateId'];
        $mailerId= $_POST['mailerId'];
	$inviteToken = base64_encode($fromEmail .'@@@'. $templateId .'@@@'. $mailerId .'@@@'. $instituteId .'@@@'. $instituteName);
        if(isset($_POST['inviteEmails']) && !empty($_POST['inviteEmails'])) {
            $emails = explode(',',str_replace(';',',',$_POST['inviteEmails']));
            $csvArr = array();
            $repos = array();
            foreach($emails as $email) {
                $email = trim($email);
                if($email == '') continue;
                $text = $email .'#'. $instituteId .'#'. $instituteName;
                $encryptedKey = $this->encodeDecodeKey($text, 'encrypt');
                $encryptedKey = strtr($encryptedKey ,'+/=','~_&');
                $csvArr['email'][] = $email;
                $csvArr['intituteId'][] = $instituteId;
                $csvArr['instituteName'][] = $instituteName;
                $csvArr['fromEmail'][] = $fromEmail;
                $csvArr['mailUrl'][] = $encryptedKey;
            }
            if(count($emails) > 0 && count($csvArr['email']) > 0) {
                $this->load->library('mailerClient');
                $mailerClientObj = new MailerClient();
                $reponse = $mailerClientObj->checkTemplateCsv($appID, $templateId, $csvArr, $user, $userGroup);
                //print_r($reponse);
                if(is_array($reponse)) {
                    $listId = $reponse[0]['id'];
                    $reponse = $mailerClientObj->addListInMailer($appID, $mailerId, $listId);
                    if($reponse ==1) {
//                        echo 'The email has been sent successfully to the recipients.';
                    }
                }
            }
        }
        $requestArray =array('numEmailsSent' => count($emails), 'inviteToken' => $inviteToken);
        $this->load->view('alumni/alumniFinal',$requestArray);
    }


    public function encodeDecodeKey($text, $operation = 'decrypt') {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_OFB);
        #$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $key = "Shiksh@A1UMSpeek";
        if($operation === 'encrypt') {
            return base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_OFB));
        } else {
            return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, base64_decode($text), MCRYPT_MODE_OFB);
        }
    }

    function uploadEmailCsv() {
        echo "<html><title>Upload Email CSV</title><body>";
        echo "<form action='sendEmailsToAlumniByCSV' method='post' enctype='multipart/form-data'><input type='file' name='c_csv' /><input type='submit' value='Go'/></form>";
        echo "</body></html>";
    }


 
    function sendEmailsToAlumniByCSV() {
        header("Content-type: text/x-csv");
        header("Content-Disposition: attachment; filename=\"my-data.csv\"");
        $stock= $this->buildCVSArray($_FILES['c_csv']['tmp_name']);
        $newCsvFile = 'email,instituteId,instituteName,mailUrl,fromEmail';
        for($i=0;$i<count($stock['email']); $i++) {
            $email = $stock['email'][$i];
            $instituteId = $stock['instituteId'][$i];
            $instituteName = $stock['instituteName'][$i];
            $text = $email .'#'. $instituteId .'#'. $instituteName;
            $encryptedKey = $this->encodeDecodeKey($text, 'encrypt');
            $encryptedKey = strtr($encryptedKey ,'+/=','~_&');
            $mailUrl =  $encryptedKey;
            $newCsvFile .= "\n". $email .','. $instituteId .','. $instituteName .','. $encryptedKey.', invite@shiksha.com';
        }
        echo  $newCsvFile;
        //print_r($stock);
    }

	private function buildCVSArray($File) {
		$handle = fopen($File, "r");
		$fields = fgetcsv($handle, 1000, ",");
		while($data = fgetcsv($handle, 1000, ",")) {
			$detail[] = $data;
		}
		$x = 0;
		foreach($fields as $z) {
			foreach($detail as $i) {
				$stock[$z][] = $i[$x];
			}
			$x++;
        }
		return $stock;
    }

	function cmsUserValidation()
    {
        $validity = $this->checkUserValidation();
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        if(($validity == "false" )||($validity == "")) {
            $logged = "No";
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        } else {
            $logged = "Yes";
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
            if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser") {
                header("location:/enterprise/Enterprise/migrateUser");
                exit;
            }
            if( !(($usergroup == "cms")) ){
                header("location:/enterprise/Enterprise/unauthorizedEnt");
                exit();
            }
        }
        $this->load->library('enterprise_client');
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']);
        $this->load->library('sums_product_client');
        $objSumsProduct =  new Sums_Product_client();
        $myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));
        $returnArr['userid']=$userid;
        $returnArr['usergroup']=$usergroup;
        $returnArr['logged'] = $logged;
        $returnArr['thisUrl'] = $thisUrl;
        $returnArr['validity'] = $validity;
        $returnArr['headerTabs'] = $headerTabs;
        $returnArr['myProducts'] = $myProductDetails;
        return $returnArr;
    }

    function showAlumFeedBacks(){
        $appId = 1;
        $this->init();
        $requestArray = $this->getCMSInfo();
        $objAlumniSpeakClientObj= new AlumniSpeakClient();
        if(isset($_REQUEST['criteriaValue']) && 
                !empty($_REQUEST['criteriaValue']) && 
                isset($_REQUEST['criteriaName']) && 
                !empty($_REQUEST['criteriaName']) ) {
            $criteriaValue = $_REQUEST['criteriaValue'];
            $criteriaName = strtolower($_REQUEST['criteriaName']);
		// error_log("AMIT: criteriaName : ".$criteriaName.", criteriaValue = ".$criteriaValue.", isCourseSelected = ".$_REQUEST['isCourseSelected']);
	    $courseId = "";
	    if($criteriaName == 'institute_id' && $_REQUEST['isCourseSelected'] == 1) {
		$courseId = $_REQUEST['institute_courses'];
		// error_log("AMIT: cid in showAlumFeedBacks if : ".$courseId);
	    } else {
		// error_log("AMIT: cid in showAlumFeedBacks ELSE : ".$courseId);
	    }			
	    	    
            $criteria = array($criteriaName => $criteriaValue);
	    
        } else {
            $criteriaValue = '';
            $criteriaName = '';
            $criteria = array();
        }

        $sortOrder = (isset($_REQUEST['sortOrder']) && !empty($_REQUEST['sortOrder'])) ? strtolower($_REQUEST['sortOrder']) : '';
        $sortOrder = $sortOrder == 'up' ? 'desc' : 'asc';
        $sort = (isset($_REQUEST['sortBy']) && !empty($_REQUEST['sortBy'])) ? array($_REQUEST['sortBy'] .' '. $sortOrder) : array('unpublished desc');
        $pageNum = (isset($_REQUEST['pageNum']) && !empty($_REQUEST['pageNum'])) ? array($_REQUEST['pageNum']) : 0;
        $numRecords = (isset($_REQUEST['numRecords']) && !empty($_REQUEST['numRecords'])) ? $_REQUEST['numRecords'] : 20000;
        $response = $objAlumniSpeakClientObj->getFeedbackList($appId, json_encode($criteria), json_encode($sort), $pageNum, $numRecords, $courseId);
        // _p($response); die;
        $requestArray['criteriaName'] = $criteriaName;
        $requestArray['criteriaValue'] = $criteriaValue;
        $sortArr = split(' ', $sort[0]);
	if($criteriaName == 'institute_id' && (count($response) || $courseId != "") && is_numeric($criteriaValue)) {
		$requestArray['instituteCourses'] = $this->getCoursesOfInstitute($criteriaValue);
	}
        $requestArray['sort'] = $sortArr[0];
        $requestArray['sortOrder'] = $sortArr[1];
        $requestArray['pageNum'] = $pageNum;
        $requestArray['numRecords'] = $numRecords;
        $requestArray['feedbacks'] = $response;
	$requestArray['selectedCourseId'] = $courseId;

        $this->load->view('alumni/moderationHomePage',$requestArray);
	}

    function getInstituteFeedBacks($instituteId, $courseId=""){
	$this->init();
        $appId = 1;
        $requestArray = $this->getCMSInfo();
        $objAlumniSpeakClientObj= new AlumniSpeakClient();
        if(empty($instituteId)) die("Nothing to show!");
        $sortOrder = (isset($_REQUEST['sortOrder']) && !empty($_REQUEST['sortOrder'])) ? strtolower($_REQUEST['sortOrder']) : '';
        $sortOrder = $sortOrder == 'up' ? 'desc' : 'asc';
        $sort = (isset($_REQUEST['sortBy']) && !empty($_REQUEST['sortBy'])) ? array($_REQUEST['sortBy'] .' '. $sortOrder) : array('feedbackTime desc');
        $pageNum = (isset($_REQUEST['pageNum']) && !empty($_REQUEST['pageNum'])) ? array($_REQUEST['pageNum']) : 0;
        $numRecords = (isset($_REQUEST['numRecords']) && !empty($_REQUEST['numRecords'])) ? $_REQUEST['numRecords'] : 20000;
        // $response = $objAlumniSpeakClientObj->getFeedbacksForInstitute($appId, $instituteId, json_encode($sort),0, $pageNum, $numRecords);
	$response = $objAlumniSpeakClientObj->getFeedbacksForInstitute($appId, $instituteId, json_encode($sort),$courseId, $pageNum, $numRecords);
        $ListingClientObj = new Listing_client();
	$responseCourse = $ListingClientObj->getCourseList('1',$instituteId,'"live","draft"');
	$selectedCourseName = "";
	if($courseId != "" && $courseId != 0) {
		foreach($responseCourse as $key => $courseArray) {
			if($courseArray['courseID'] == $courseId) {
				$selectedCourseName = $courseArray['courseName'];
				break;
			}
		}
	}
	// _p($response); die;
	// _p($responseCourse);
        $sortArr = split(' ', $sort[0]);
        $requestArray['sort'] = $sortArr[0];
        $requestArray['sortOrder'] = $sortArr[1];
        $requestArray['pageNum'] = $pageNum;
        $requestArray['numRecords'] = $numRecords;
        $requestArray['feedbacks'] = $response;
        $requestArray['courses'] = $responseCourse;
        $requestArray['instituteId'] = $instituteId;
	$requestArray['selectedCourseName'] = $selectedCourseName;
        $this->load->view('alumni/moderateFeedBackPage',$requestArray);
	}

    private function getCMSInfo(){
		$cmsUserInfo = $this->cmsUserValidation();
		$userid = $cmsUserInfo['userid'];
        if($userid != 34) {
            header('location:/enterprise/Enterprise');
        }
		$usergroup = $cmsUserInfo['usergroup'];
		$thisUrl = $cmsUserInfo['thisUrl'];
		$validity = $cmsUserInfo['validity'];
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $userid;
		$cmsPageArr['usergroup'] = $usergroup;
		$cmsPageArr['thisUrl'] = $thisUrl;
		$cmsPageArr['validateuser'] = $validity;
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['prodId'] = $this->prodId;
		$cmsPageArr['templateType'] = "mail";
        return $cmsPageArr;
    }

    function updateReviewStatus() {
		$this->init();
        $appId = 1;
        $instituteId = $_POST['instituteId'];
        $criteriaId = $_POST['criteriaId'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $objAlumniSpeakClientObj= new AlumniSpeakClient();
	if($status=="published")
	{
	    $response = $objAlumniSpeakClientObj->checkReviewStatusMail($appId, $instituteId,$status);
        $lastMailSendTimeDiff = '';
        if(is_array($response)) {
	        $lastMailSendTimeDiff = $response[0]['diff'];
        }
	    //error_log(print_r("Time::".$lastMailSendTimeDiff,true));
	    if($lastMailSendTimeDiff == '' || $lastMailSendTimeDiff > 1440)
		$this->sendInstituteFeedbackMail($appId, $instituteId);
	}
        $response = $objAlumniSpeakClientObj->updateReviewStatus($appId, $instituteId,$criteriaId, $email,$status);
	
	///Code start by Ankur for HTML caching
	//After publishing/unpublishing the alumni review, delete the institute HTML cache, so that new HTML pages will be created
	/*$this->load->library('cacheLib');
	$cacheLib = new cacheLib();
	$key = md5('listingCache_'.$instituteId."_institute");
	$cacheLib->store($key,'false',86400,'misc');
	//Also, clear the cache for this institutes courses since alumni review section will also affect their Overview pages
	$key = md5('listingCache_'.$instituteId."_course");
	$cacheLib->store($key,'false',86400,'misc');*/
	
	/*
	$this->load->library('listing_client');
        $ListingClientObj = new Listing_client();
	$key = "listingCache_".$instituteId."_institute";
	$ListingClientObj->setListingCacheValue($appId, $key,'false');
	$key = "listingCache_".$instituteId."_course";
	$ListingClientObj->setListingCacheValue($appId, $key,'false');
	//After setting the value in DB, also delete the HTML files from all the Frontend servers
	$ListingClientObj->deleteListingCacheHTMLFile($instituteId,"institute");
	$ListingClientObj->deleteListingCacheHTMLFile($instituteId,"course");
	///Code End by Ankur for HTML caching
	*/

        echo $response;

    }
    function getExcludedCourses() {
        $this->init();
        $appId = 1;
        $instituteId = $_POST['instituteId'];
        $criteriaId = $_POST['criteriaId'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $objAlumniSpeakClientObj= new AlumniSpeakClient();
        $response = $objAlumniSpeakClientObj->getExcludedCourses($appId, $instituteId,$criteriaId, $email,$status);
        //error_log(print_r($response,true));
        $courseList = '';
        if(is_array($response)) {
            $courseList = $response[0]['excluded_course_id'];
        }
        echo $courseList;
    }

    function setExcludedCourses() {
		$this->init();
        $appId = 1;
        $instituteId = $_POST['instituteId'];
        $criteriaId = $_POST['criteriaId'];
        $email = $_POST['email'];
        $courseList = $_POST['courses'];
        $objAlumniSpeakClientObj= new AlumniSpeakClient();
        $response = $objAlumniSpeakClientObj->setExcludedCourses($appId, $instituteId,$criteriaId, $email,$courseList);
	//error_log(print_r($response,true));
        echo $response;
    }

    function sendInstituteFeedbackMail($appId, $instituteId){
        error_log_shiksha("CONTROLLER sendInstituteFeedbackMail APP ID=> $appId :: $instituteId");
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails($appId,$instituteId,"institute");
        $details = $listingDetails[0];
        $email = $details['contact_email'];
        $fromMail = "enterprise@shiksha.com";
        $ccmail = "sales@shiksha.com";
        $mail_client = new Alerts_client();
        $subject = "New Alumni feedback about your institute";
        $contentArr['name'] = $details['contact_name'];
        $MailerClient = new MailerClient();
        $urlOfLandingPage = SHIKSHA_HOME."/getListingDetail/".$instituteId."/institute";
        $autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage);
        $contentArr['listingUrl'] = $autoLoginUrl;
        $contentArr['type'] = 'alumFeedbackMail';
        $content = $this->load->view("search/searchMail",$contentArr,true);
        $response= $mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
        error_log(print_r($response),true);

    }

}
?>
