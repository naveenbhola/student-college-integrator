<?php
class AbroadSignup extends MX_Controller
{
	private $validateuser = NULL;
    public function __construct()
    {
        parent::__construct();
		$this->_initUser();
    }

    /*
	 * check user validation & other stuff
	 */
	private function _initUser(){
		$this->signUpFormLib = $this->load->library('AbroadSignupLib');
		$this->validateuser = $this->checkUserValidation();
	}
    
    /*
	 * landing ctrlr function for signup page
	 */
	public function abroadSignupForm()
	{
		$data = array();
		$data['hideGDPR']=true;
		// any data from cookie/any other source
        //$data['trackingPageKeyId'] = $this->security->xss_clean($cookieData['tkey']);
		if($_SERVER['HTTP_REFERER'] !='' && strpos($_SERVER['HTTP_REFERER'],SHIKSHA_STUDYABROAD_HOME)!== FALSE){ 
			$this->signUpFormLib->getSignupFormParams($data);
		}
		$data['hideTrackingFields'] = true;
		$data['universityId'] = (in_array($data['sourcePage'], array('university','university_ranking'/* mobile */,'country_university'/* mobile */,'university_rankingpage_abroad','abroadSearch','countryPage'))?$data['universityId']:0);
		$data['singleSignUpFormType'] = 'registration';
        $this->signUpFormLib->setSEODetailsForSignup($data);
		$this->_validateAndRedirect($data);
		if($data['scholarshipId'] > 0 && $data['MISTrackingDetails']['conversionType'] == 'response'){
			$data['singleSignUpFormType'] = 'scholarshipResponse';
			$data['responseSource'] = $data['sourcePage'];
		}
		if(($data['courseId'] > 0 && $data['MISTrackingDetails']['conversionType'] == 'response') || $data['universityId'] > 0){
			$data['singleSignUpFormType'] = 'response';
			if($data['MISTrackingDetails']['keyName']=='rateMyChance'){
        		$data['responseSource'] = 'rmcPage_'.$data['sourcePage'];
        	}
		}
		$abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
		$abroadDesiredCourses = $abroadCommonLib->getAbroadMainLDBCourses();
		$data['abroadDesiredCourseIds'] = array_map(function($a){return $a['SpecializationId'];},$abroadDesiredCourses);

		$data['userDetails'] = $this->validateuser;
		$this->signUpFormLib->getListingDataForResponseForm($data);				

		// prepare data for loggedin user
		if($this->validateuser !== 'false'){
			$this->signUpFormLib->prepareDataForLoggedInUser($data);
		}
		$data['beaconTrackData'] = $this->signUpFormLib->prepareTrackingData('AbroadSignup',$data);
		if((is_numeric($data['courseId']) || is_numeric($data['universityId'])) && ($data['courseId']>0 || $data['universityId']>0))
		{
			$responseAbroadLib = $this->load->library('responseAbroad/ResponseAbroadLib');
			$data['responseSource'] = $data['responseSource'] ? $data['responseSource'] : $responseAbroadLib->getResponseSource($data['sourcePage'],$data['widget']);
		}
		$data['newSAOverlay'] = true;

		if(isMobileRequest()){
			if($data['singleSignUpFormType'] == 'response' && $data['MISTrackingDetails']['keyName'] == 'rateMyChance') {
				$additionalDataForRMC = array('viaModuleRun'=>true,
					'referer'=>$data['customReferer'],
					'refererTitle'=>$data['refererTitle']
				);
				echo Modules::run('rateMyChancePage/rateMyChance/rateMyChancePage',	$data['courseId'], $additionalDataForRMC);
				return false;
			}else if($data['singleSignUpFormType'] == 'response' && $data['MISTrackingDetails']['keyName'] !== 'rateMyChance') {
				// set this data in post so that it can be reused in response abroad
				foreach($data['brochObj'] as $key=>$val)
				{
					$_POST[$key] = $val;
				}
				$response = Modules::run('responseAbroad/ResponseAbroad/getBrochureDownloadForm', $data);
				$data['mainContent'] = $response;
			}else{
				// load mobile view
				$response = Modules::run('commonModule/User/getAbroadRegistrationForm', $data);
				$data['mainContent'] = $response['html'];
			}
			$this->load->view('signupPage/signupOverview',$data);
		}else{
			echo $this->load->view('abroadSignup/abroadSignupMain',$data);
		}
	}

	private function _validateAndRedirect(&$data){
		$validURL = SHIKSHA_STUDYABROAD_HOME.'/signup';
		if(getCurrentPageURLWithoutQueryParams()!= $validURL)
		{
			header('Location: '.$validURL,TRUE,301);
			exit();
		}
		$inputData['returnDataFlag'] = 1;		
		if(!empty($data['courseId'])){
			if(!empty($data['MISTrackingDetails']) && $data['MISTrackingDetails']['conversionType'] == 'response'){
				$inputData['listingTypeId'] = $data['courseId'];
			}
		}

		$showSignupFormData = $this->checkToShowSignupForAbroadUser($inputData);
		// except for response from univ page ... do not show signup page if user not supposed to see signup
		if(!isMobileRequest() && $showSignupFormData === false && (is_null($data['universityId']) || $data['universityId'] == '')){
			$this->_redirectToHome();
		}
		$data['userInfoArray'] = $inputData['userInfoArray'];
		$data['workExperience'] = $inputData['workExperience'];
	}

	/*
	 * function to check whether the signup form is to be shown to the current user or not.
	 * $data =array(
				'userId', 'courseId', 'courseGroup'='SASingleRegistrationForm', 'returnDataFlag' (0/1)
		)
	 * 
	 */
	public function checkToShowSignupForAbroadUser(&$data = array()){
            $isAJAX = $this->security->xss_clean($this->input->post('isAJAX'));
			$listingTypeId = $this->security->xss_clean($this->input->post('listingTypeId'));
			// this will be sent in case of any action taken via university (e.g. RMC on ULP)
			$listingType = $this->security->xss_clean($this->input->post('listingType'));
            $showSignupFormFlag = NULL;
            if($this->validateuser === 'false'){
                // user not logged in , therefore show the form
                $showSignupFormFlag = true;
            }
            else{
                $userId = $this->validateuser[0]['userid'];
                /* two things to check:
                 * 1. user is complete for current action
                 * might need $listingTypeId
                 */
                if(empty($data)){
					if($listingType == 'university')
					{
						// always need to show form in this case because user will have to select a course
						$showSignupFormFlag = true;
						if(!is_null($isAJAX ) && $isAJAX!=""){
							echo json_encode($showSignupFormFlag);
						}
						return $showSignupFormFlag;
					}
                    if($listingTypeId>0){
                            $checkIsValidResponseUser = Modules::run("registration/Forms/isValidAbroadUser",$userId,$listingTypeId);
                    }else{
                            $checkIsValidResponseUser = Modules::run("registration/Forms/isValidAbroadUser",$userId);
                    }
                }else{
                    $additionalDataWithParams = array('returnDataFlag'=>$data['returnDataFlag']);
                    $checkIsValidResponseUser = Modules::run("registration/Forms/isValidAbroadUser",
                                                                                                     $userId,
                                                                                                     $data['listingTypeId'],
                                                                                                     'SASingleRegistrationForm',
                                                                                                     $additionalDataWithParams);
                    if(!empty($checkIsValidResponseUser['userInfoArray'])){
                        if(!empty($checkIsValidResponseUser['userInfoArray']['desiredCourse'])){
                            $data['userInfoArray'] = $checkIsValidResponseUser['userInfoArray'];
                        }

                        if(is_numeric($checkIsValidResponseUser['userInfoArray']['workExperience'])){
                            $data['workExperience'] = $checkIsValidResponseUser['userInfoArray']['workExperience'];
                            unset($data['userInfoArray']['workExperience']);
                        }
                    }
                }
                if((is_array($checkIsValidResponseUser) && $checkIsValidResponseUser['isValidUser'] !== false) || (!is_array($checkIsValidResponseUser) && $checkIsValidResponseUser)){
                    /*
                     * 2. user hasn't visited for more than 21 days by any means
                     */
                    $profilePageShown  =$_COOKIE['profilePageShown'];
                    $profilePageShown = $this->security->xss_clean($profilePageShown);
                    if($profilePageShown == 1){
                            $showSignupFormFlag = false;
                    }else{
                            $showSignupFormFlag = $this->signUpFormLib->checkIfUserVisitedInXDays($userId, 21);
                    }
                }
                else{
                    $showSignupFormFlag = true;
                }
            }
            if(!is_null($isAJAX ) && $isAJAX!=""){
                echo json_encode($showSignupFormFlag);
            }else{
                return $showSignupFormFlag;
            }
	}	

	public function checkToShowSignupForAbroadUserAPI(){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
        $this->checkToShowSignupForAbroadUser();
    }	

	/*
	 * landing controller for thank you page
	 */
	public function thankYouPage()
	{
		$displayData = array();
		// only logged in users should see this
		if($this->validateuser === 'false')
		{
			// redirect to home
			$this->_redirectToHome();
		}

		// first we read and destroy signupformparams cookie info
		$displayData = $this->_validateRemoveSignupFormParams();
		// now that we have signup params, get the seo stuff & download message type : downloadGuide/downloadBrochure
		$this->signUpFormLib->getSEOForThankYouPage($displayData);
		$this->signUpFormLib->prepareThankYouPageTrackingData($displayData);
		// validate & redirect url
		$this->_validateURL($displayData['seoDetails']);
		// get details to be shown on page
		$this->signUpFormLib->getDownloadMessageWithData($displayData);
		
		$displayData['validateuser'] = $this->validateuser; // needed in header to initizalize isuserloggedin
		// user email
		$cookieStr = explode('|',$this->validateuser[0]['cookiestr']);
		$displayData['email'] = $cookieStr[0];
		// load view
		if(isMobileRequest()){
			$this->load->view('signupPage/thankYouPageOverview', $displayData);
		}else{
			$this->load->view('abroadSignup/thankYouPageMain', $displayData);
		}
	}
	
	/*
	 * validate & remove signUpFormParams cookie
	 * check if user came via appropriate flow
	 * we need to check both referrer and cookie values for this
	 * redirect to home if not found
	 */
	private function _validateRemoveSignupFormParams()
	{
		$data = array();
		// read
		$res = $this->signUpFormLib->getSignupFormParams($data);
		if($res === false)
		{
			$this->_redirectToHome();
		}
		$entityInfo = json_decode(base64_decode($this->security->xss_clean($this->input->get('c'))),true);
		
		if(is_null($entityInfo))
                {
                        show_404_abroad();
                }

		if($entityInfo['listingTypeId'] != '')
		{
			$data['listingTypeForResponse'] = $entityInfo['listingType'];
			if($data['listingTypeForResponse'] == 'scholarship'){
				$data['scholarshipIdForResponse'] = $entityInfo['listingTypeId'];
			}else{
				$data['courseIdForResponse'] = $entityInfo['listingTypeId'];
			}
			$data['brochureEmailInsertId'] = $entityInfo['brochureEmailInsertId'];
			$data['tempLmsTableId'] = $entityInfo['tempLmsTableId'];
			$data['downloadedFrom'] = ($entityInfo['source'] == ""?$entityInfo['responseSource']:$entityInfo['source']);
		}else if($entityInfo['contentId'] != '')
		{
			$data['contentId'] = $entityInfo['contentId'];
			$data['url'] = $entityInfo['url'];
		}
		// check if user came from a valid flow
		if(is_null($data['trackingPageKeyId']) ||
		   !is_numeric($data['trackingPageKeyId']) ||
		   is_null($data['customReferer'])
		   )
		{
			// send home if not
			$this->_redirectToHome();
		}
		// remove
		setcookie('signUpFormParams', '' , time()-60,'/');
		// return whatever data was found
		return $data;
	}
	
	/*
	 * validate url
	 */
	public function _validateURL($seoData)
	{
		if(getCurrentPageURLWithoutQueryParams() != $seoData['url'])
		{
			header('Location: '.$seoData['url'],TRUE,301);
			exit(0);
		}
	}

	/*
	 * simply redirects to home page (move temporarily)
	 */
	private function _redirectToHome()
	{
		header('Location: '.SHIKSHA_STUDYABROAD_HOME,TRUE,302);
        exit();
	}
}
