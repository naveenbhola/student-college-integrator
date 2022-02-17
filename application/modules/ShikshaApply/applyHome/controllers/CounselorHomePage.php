<?php
class CounselorHomePage extends MX_Controller{
      
   private $applyHomeLib;
   
   public function __construct()
   {
	  parent::__construct();
	  $this->applyHomeLib = $this->load->library('applyHome/ApplyHomeLib');
          $this->load->config('applyHome/counselorReviewConfig');
   }
   
   private function _prepareTrackingData(&$displayData, $entityId)   
   {      
	  $displayData['beaconTrackData'] = array(
										   'pageIdentifier' => 'counselorPage',
										   'pageEntityId' => $entityId,
										   'extraData' => null
										   );
   }
   

   private function _initUser(& $displayData){
		$displayData['validateuser'] = $this->checkUserValidation();
		if($displayData['validateuser'] !== 'false') {
			$this->load->model('user/usermodel');
			$usermodel = new usermodel;
			$userId = $displayData['validateuser'][0]['userid'];
			$user = $usermodel->getUserById($userId);
			if (!is_object($user)) {
				$displayData['loggedInUserData'] = false;
			}else {
				$name = $user->getFirstName().' '.$user->getLastName();
				$email = $user->getEmail();
				$userFlags = $user->getFlags();
				$isLoggedInLDBUser = $userFlags->getIsLDBUser();
				$displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
				$displayData['checkIfLDBUser'] 		= $usermodel->checkIfLDBUser($userId);
				$pref = $user->getPreference();
				$loc = $user->getLocationPreferences();
				$isLocation = count($loc);
				if(is_object($pref)){
					$desiredCourse = $pref->getDesiredCourse();
				}else{
					$desiredCourse = null;
				}
				$displayData['loggedInUserData']['desiredCourse'] = $desiredCourse;
				$displayData['loggedInUserData']['isLocation'] = $isLocation;
				$this->applyHomeLib->getSignupRedirectParams($displayData);
			}
	    }
	    else {
			$displayData['loggedInUserData'] = false;
			$displayData['checkIfLDBUser'] = "NO";
	    }
	}

	
   /*
	* controller function for counselor pages
	* @params : counselor id derived from url
	*/
   public function counselorHomePage($counsellorName,$pageEntityId){
	  $displayData = array();
	  $counsellorData = $this->applyHomeLib->validateUrl();
//	  $this->_initUser($displayData);
      $displayData['validateUser']  = $this->checkUserValidation();
	  $displayData['seoData']       = $this->applyHomeLib->getCounselorHomeSeoData($counsellorData);
	  $displayData['trackForPages'] = true;
	  $this->_prepareTrackingData($displayData,$pageEntityId);
      $counsellorData['counselorImageUrl']= $this->applyHomeLib->getCounsellorCMSImageUrlById($counsellorData['counsellor_id']);
	  $displayData['counselorId']       = $counsellorData['counsellor_id'];
	  $displayData['counsellorInfo']    = $counsellorData;
	  $displayData['counselorRatings'] 	= reset($this->applyHomeLib->getRatingInfoByCounselorIds(array($displayData['counselorId'])));
	  $displayData['reviewPerPageLimit']= REVIEW_PER_PAGE;
	  $reviewResult                     = $this->applyHomeLib->getReviewByCounselorId($displayData['counselorId'],$displayData['reviewPerPageLimit'],true);
      $displayData['studentInfo']       = $this->applyHomeLib->getStudentsInfo($reviewResult['result']);
	  $displayData['reviewInfo']        = $reviewResult['result'];
      foreach($displayData['reviewInfo'] as $review){
          $displayData['userEligibleForReviewDeletion'][$review['id']] = $this->applyHomeLib->checkUserEligibilityForReviewDeletion($displayData['validateUser'],$review);
      }
	  $displayData['totalReviewCount']  = $reviewResult['totalReviewCount'];
	  $displayData['reviewPostTrackingKey'] = 1361;
	  //$displayData['newSAOverlay'] = true;
	  $this->_checkIfPopUpTobeTriggered($displayData);
      $displayData['firstFoldCssPath'] = '/applyHome/css/counselorReviewPageFirstFoldCss';
	  $this->load->view('applyHome/counselorHomePageOverview',$displayData);
	  return;
   }
	
	/*
	 * parses url to check for query string so that we can trigger popup err msg later
	 */
	private function _checkIfPopUpTobeTriggered(& $displayData)
	{
		parse_str($this->input->server('QUERY_STRING',true),$output);
		$output = json_decode(base64_decode($output['q']));
		$displayData['triggerReviewCheck'] = $output->reviewAllowed===false;
	}
   public function deleteReview(){
       $reviewId = $this->input->post('reviewId',true);
       if(!empty($reviewId) && is_numeric($reviewId)){
           $validateUser = $this->checkUserValidation();
           echo $this->applyHomeLib->deleteReview($validateUser,$reviewId);
       }else{
           echo 'success';
       }
       
   }
   public function getMoreReview(){
   		$counselorId = base64_decode($this->input->post('counselorId',true));
   		$limit = $this->input->post('limit',true);
   		$lastReviewId = $this->input->post('lastReviewId',true);
   		if($counselorId == '' || $limit==''){
   			return false;
   		}
        $reviewResult 	= $this->applyHomeLib->getReviewByCounselorId($counselorId,$limit,false,$lastReviewId);
        $displayData['studentInfo']       = $this->applyHomeLib->getStudentsInfo($reviewResult['result']);
        $displayData['reviewInfo']        = $reviewResult['result'];
        $displayData['validateUser']      = $this->checkUserValidation();
        foreach($displayData['reviewInfo'] as $review){
          $displayData['userEligibleForReviewDeletion'][$review['id']] = $this->applyHomeLib->checkUserEligibilityForReviewDeletion($displayData['validateUser'],$review);
        }
        $html = "";
   		foreach ($reviewResult['result'] as $key => $value) {
   			$html = $html.$this->load->view('applyHome/widgets/reviewTuple',array('value'=>$value,
                                                                    'studentInfo'=>$displayData['studentInfo'],
                                                                    'validateUser'=>$displayData['validateUser'],
                                                                    'userEligibleForReviewDeletion'=>$displayData['userEligibleForReviewDeletion']
                                                                    ),true);
   		}
        $lastReview = end($reviewResult['result']);
        $lastReviewId = $lastReview['id'];
        echo json_encode(array('html'=>$html,'lastReviewId'=>$lastReviewId));
   }

   private function _initCounselorReviewPostingPage(&$displayData){
   		$this->load->config('applyHome/counselorReviewConfig');
   		$displayData['counselorRelatedQuestions']   = $this->config->item('counselorRelatedQuestions');
   		$displayData['counsellingServiceQuestions'] = $this->config->item('counsellingServiceQuestions');
   		$counselorIds = $this->applyHomeLib->getUserRelatedCounsellors($displayData['validateuser'][0]['userid']);
   		$displayData['userRelatedCounselors'] = $this->applyHomeLib->getCounselorDetails($counselorIds);
   }

   private function _getSEODetails(&$displayData){
   		$seoDetails = array();
   		$seoDetails['canonical'] = SHIKSHA_STUDYABROAD_HOME.'/apply/counselors/feedback-form';
   		$seoDetails['title'] = 'Shiksha Study Abroad Counseling Service Feedback Form';
   		$seoDetails['description'] = 'Give us your feedback about Shiksha Study Abroad Counseling Service. Your ratings and reviews are valuable to us as we endeavour to improve the counseling services we offer to students.';
   		$displayData['seoDetails'] = $seoDetails;
   }

   private function _checkRedirectionFromCounselorPage(&$displayData){
   		$queryString = $this->input->server('QUERY_STRING',true);
   		$displayData['trackingKeyId'] = (isMobileRequest()===TRUE ? 1360 : 1359);
   		if($queryString!=''){
	   		$data = array();
			parse_str($queryString, $data); 
			$data = json_decode(base64_decode($data['q']),true);
			if(!empty($data['counselorId']))
                $displayData['userCounselorId'] = $data['counselorId'];
   			$displayData['trackingKeyId'] = $data['trackingKeyId'];
   			return true;
   		}
   }

   public function counselorReviewPostingPage(){
   		$displayData = array();
   		$displayData['newSAOverlay'] = true; 
   		$displayData['validateuser'] = $this->checkUserValidation();
   		$this->_initCounselorReviewPostingPage($displayData);
   		$this->_getSEODetails($displayData);
     $displayData['beaconTrackData'] = array('pageIdentifier' => 'counselorReviewPage');

      $counselorIds = array_keys($displayData['userRelatedCounselors']);
   		if($this->_checkRedirectionFromCounselorPage($displayData)){
	   		if(empty($displayData['trackingKeyId']) || empty($counselorIds)){
	   			$displayData['showErrorPopupFlag'] = 1;
	   		}
	   		if(!empty($displayData['userCounselorId']) && !in_array($displayData['userCounselorId'], $counselorIds)){
                $displayData['showErrorPopupFlag'] = 1;
            }
   		}
      $stageId = $this->applyHomeLib->userStageCheck($displayData['validateuser'][0]['userid']);
      if($stageId && count($counselorIds)==0){
        $displayData['showErrorPopupFlag'] = 2;
      }
       $displayData['isSAGlobalNavSticky'] = false;
   		$displayData['firstFoldCssPath'] = 'applyHome/css/counselorReviewPostingFirstFoldCss';
   		$this->load->view('applyHome/counselorReviewPosting/counselorReviewPostingOverview', $displayData);
   }

    private function _validateUserStatus(&$displayData){ 
        $displayData['validateuser'] = $this->checkUserValidation(); 
        $displayData['saveReview'] = 'pass';
        if($displayData['validateuser'] == 'false'){ 
             $displayData['saveReview'] = 'fail';
    	}else{
    		$displayData['stageId'] = $this->applyHomeLib->userStageCheck($displayData['validateuser'][0]['userid']);
    		if(!$displayData['stageId']){
    			$displayData['saveReview'] = 'fail';
    		}else{
    			$counselorIds = $this->applyHomeLib->getUserRelatedCounsellors($displayData['validateuser'][0]['userid']); 
    			$counselorId = $this->input->post('userCounselorId', true);
    			if(!$counselorId || !in_array($counselorId, $counselorIds)){
    				$displayData['saveReview'] = 'fail';
    			}
    		}
    	}
    }

   public function saveCounselorReview(){
   		$displayData = array();
   		$this->_validateUserStatus($displayData); 
		if($displayData['saveReview']==='pass'){ 
			$postData = array();
	   		$displayData['stageName'] = $this->applyHomeLib->getStageName($displayData['stageId']);
	   		$this->applyHomeLib->getReviewPOSTData($postData);
	   		$validateFlag = $this->applyHomeLib->validatePOSTData($postData);
	   		if($validateFlag){
		   		$saveSts = $this->applyHomeLib->saveReviewPOSTData($postData, $displayData['validateuser']);
		   		if($saveSts){
		   			$this->_setToastMsgCookie('Thank You! Your Review has been submitted successfully.');
		   			$counselorData = $this->applyHomeLib->getCounselorDetails(array($postData['userCounselorId']));
            $row = array();
            $row['guidanceRating'] = $postData['userRating1'];
            $row['knowledgeRating'] = $postData['userRating2'];
            $row['responseRating'] = $postData['userRating3'];
            $postData['overAllRating'] = $this->applyHomeLib->getUserOverallRating($row);
		   			$this->_sendCounselorReviewFeedbackMail($postData,$counselorData,$displayData);
		   			redirect($counselorData[$postData['userCounselorId']]['counselorPageUrl'], 'location');
		   		}
	   		}else{
	   			$this->_setToastMsgCookie('Insufficient or incorrect data. Please try again.');
	   			redirect('/apply', 'location');
	   		}
		}else{
   			$this->_setToastMsgCookie('Insufficient or incorrect data. Please try again.');
   			redirect('/apply', 'location');
   		}
   }

   private function _setToastMsgCookie($msg){
   		setcookie("tM", base64_encode($msg), time()+60*2, "/", COOKIEDOMAIN); //set for 2 minutes
   }

    private function _sendCounselorReviewFeedbackMail(&$postData,&$counselorData,&$displayData){
   		$userId = $postData['userCounselorId'];
   		$commonStudyAbroadLib = $this->load->library('common/studyAbroadCommonLib'); 
   		$subject = 'New counselor review for '.$counselorData[$userId]['counsellor_name'];
   		if($postData['anonymousFlag']==="yes"){
   			$msg .= "Student name: Anonymous <br/>";
   		}else{
   			$msg .= "Student name: ".$displayData['validateuser'][0]['firstname']." ".$displayData['validateuser'][0]['lastname']."<br/>";
   		}
   		$msg .= "Counselor name: ".$counselorData[$userId]['counsellor_name']."<br/>";
   		$msg .= "Rating: ". $postData['overAllRating'] ."/10<br/>";
   		$msg .= "Review: ".htmlentities($postData['counselorReviewText'])."<br/>";
   		$msg .= "Current stage of user: ". $displayData['stageId'] . "(".$displayData['stageName'].")<br/>";
	   	if(ENVIRONMENT === 'production'){
        $receivers['to'] = 'shiksharmsteam@naukri.com';
        $receivers['bcc'] = 'satech@shiksha.com';
        $email = $this->load->library('email');
        $email->clear(TRUE);
        $config['mailtype'] = 'html';
        $email->initialize($config);
        $email->from(SA_ADMIN_EMAIL, 'shiksha');
        $email->to($receivers['to']);
        $email->cc($receivers['cc']);
        $email->bcc($receivers['bcc']);
        $email->subject($subject);
        $email->message($msg);
        $email->send();
		  }
    }

	/*
	 * called via ajax from counselor page's 'write a review' link
	 */
	public function checkIfUserEligibleToWriteReview()
	{
		$validateUser = $this->checkUserValidation();
		$counselorId = $this->input->post('counselorId',true);
        $trackingKey = $this->input->post('trackingKey',true);
		$result = $this->applyHomeLib->checkIfUserEligibleToWriteReview($validateUser, $counselorId,$trackingKey);
		echo json_encode($result);
	}

	public function checkUserEligibilityToReviewCounselor(){
		$validateUser = $this->checkUserValidation();
		$result = $this->applyHomeLib->userStageCheck($validateUser[0]['userid']);
		echo json_encode($result);
	}
}