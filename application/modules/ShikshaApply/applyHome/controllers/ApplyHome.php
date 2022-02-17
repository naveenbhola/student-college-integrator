<?php
   class ApplyHome extends MX_Controller{
      
      private $applyHomeLib;
	  private $validateuser;
      private $checkIfLDBUser;
      
      public function __construct()
	  {
         parent::__construct();
         $this->applyHomeLib = $this->load->library('applyHome/ApplyHomeLib');
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
	  
      private function _prepareTrackingData(&$displayData)   
      {      
         $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => 'applyHomePage',
                                              'pageEntityId' => '0',
                                              'extraData' => null
                                              );
      }
      
	  /*
	   * controller function for apply home pages
	   * @params : optional param -
	   * 	true if page is rendered below rmc success layer,
	   * 	false otherwise
	   */

		public function applyHomePage($onRMCSuccessFlag = false){
			// 404 condition ???
			if($onRMCSuccessFlag == false && getCurrentPageURLWithoutQueryParams() != SHIKSHA_STUDYABROAD_HOME.'/apply'){
				redirect(SHIKSHA_STUDYABROAD_HOME.'/apply', 'location');
			}
			$displayData = array();
			$displayData['profEvalCTAText'] = "Book a free profile evaluation call";
			$this->_initUser($displayData);
			$displayData['onRMCSuccessFlag'] = $onRMCSuccessFlag;
			$displayData['seoData']    = $this->applyHomeLib->getApplyHomeSeoData();
			$displayData['trackForPages'] = true;
			$this->_prepareTrackingData($displayData);
			GLOBAL $validateuser, $loggedInUserData, $checkIfLDBUser;

			//get Study abroad counselling service rating data
            $displayData['saCounsellingRatingData'] = $this->applyHomeLib->getStudyAbroadCounsellingRatingData();
            //get study abroad counselling reviews , user data, admitted university
            $displayData['counsellingReviewData'] = $this->applyHomeLib->getTopCounsellingReviews();
            //counselling review page link
            $displayData['saReviewPage'] = $this->applyHomeLib->getSACounsellingReviewPageLink();
            //get star width for apply home page rating
            $displayData['starRatingWidth'] = $this->applyHomeLib->getStarRatingWidth(3.2,96,$displayData['saCounsellingRatingData']['overallRating']);

			$displayData['successVideoArray'] 		= $this->applyHomeLib->getSuccessStoryWidgetDetails();
			$this->getCounselorWidgetData($displayData);
//			_p($displayData);die;
			if($onRMCSuccessFlag === true)
			{
				$render = $this->load->view('applyHome/applyHomeOverview',$displayData,true);
				echo $render;
			}else{
				$userId = (isset($displayData['validateuser'][0]['userid'])) ? $displayData['validateuser'][0]['userid'] : 0;
				$this->setBSBParam($userId);
				$this->load->view('applyHome/applyHomeOverview',$displayData);
			}
			return ;
		}

		private function getCounselorWidgetData(&$displayData){
			$displayData['counselorWidgetData'] = $this->applyHomeLib->getCounselorWidgetData(6);
			$counselorIds = array_map(function($a){return $a['counselorId'];},$displayData['counselorWidgetData']);
			$displayData['counselorReviewData'] = $this->applyHomeLib->getTopReviewsByCounselorIds($counselorIds);
			$reviewCountForCounselors = $this->applyHomeLib->getReviewsCountByCounselorIds($counselorIds);
			$displayData['studentInfo'] = $this->applyHomeLib->getStudentsInfo($displayData['counselorReviewData']);
			foreach ($displayData['counselorReviewData'] as $counselorId => &$value) {
				$value['reviewCount'] = $reviewCountForCounselors[$counselorId];
			}
		}

	  public function uploadExamScoreCard($fileName='examScoreFile'){
	  	$data = $this->applyHomeLib->examScoreCardUpload($fileName);
	  	echo json_encode($data);
	  }

	  public function saveExamScoreCard(){
	  	$userId = $this->input->post('userId');
	  	$getUserData = array();
	  	if($userId==''){
	  		$this->_initUser($getUserData);
	  		$userId  = $getUserData['validateuser'][0]['userid'];
	  	}
	  	$docPath = $this->input->post('docPath');
	  	$examId = $this->input->post('examId');
	  	$courseId = $this->input->post('courseId');
	  	$counselorId = base64_decode($this->input->post('counselorId'));
	  	$profileEvaluationTrackingId = $this->input->post('profileEvaluationTrackingId');
		
		$data = $this->applyHomeLib->saveExamScoreRecord($userId,$docPath,$examId,$profileEvaluationTrackingId,$courseId,$counselorId);
		
	  	echo json_encode($data);
	  }

	  public function removeUploadedFileByUser(){
	  	$docPath = $this->input->post('docPath');
	  	if($docPath !=''){
	  		$uploadScriptUrl = "http://".MEDIA_SERVER_IP."/applyHome/ApplyHome/removeFileByCurl";
			$post_array['docPath'] = $docPath;
            // The curl call to media server..
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $uploadScriptUrl);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c,CURLOPT_POSTFIELDS, $post_array);
            $output =  curl_exec($c);
            curl_close($c);
	  	}
	  }

	  public function removeFileByCurl()
	  {
	  		$docPath = $this->input->post('docPath');
	  		if($docPath !='')
	  		{
	            $docPathArr = explode('shikshaApply', $docPath);
		  		$relativePath  = MEDIA_BASE_PATH.'/shikshaApply'.$docPathArr[1];
		  		if(!is_dir($relativePath) && file_exists($relativePath)){
		  			unlink($relativePath);
		  		}
	  		}else{
	  			echo "something went wrong try again";
	  		}
	  }		
	  
	  /*
	   * function to get exam score upload form on desktop
	   */
	  public function getExamDocUploadLayer()
	  {
		 $displayData = array();
		 $abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
		 $displayData['examMasterList'] = $abroadListingCommonLib->getAbroadExamsMasterListFromCache(true);
		 $displayData['tracking_page_key'] = $this->input->post('tracking_page_key');
		 $this->load->view('widgets/examScoreUploadForm', $displayData);
	  }
	  /*
	   * fn to get thank you layer for profile evaluation call (desktop)
	   */
	  function getUploadThankYouLayer()
	  {
		 $counselorId = $this->input->post('counselorId');
		 //$counselorInfo = fn($counselorId);
		 $this->load->view('widgets/uploadExamDocThankYouLayer',array('counselorInfo'=>$counselorInfo));
	  }

	  /*
	   * function to get exam score upload warning message on desktop
	   */
	  public function getExamDocWarningMessageLayer()
	  {
		 $displayData = array();
		 $this->load->view('widgets/getExamDocWarningMessageLayer', $displayData);
	  }

	  private function setBSBParam($userId){
	  	$this->applyHomeLib->setBSBParam($userId, 'otherPage', 'desktop');
	  }
   }
