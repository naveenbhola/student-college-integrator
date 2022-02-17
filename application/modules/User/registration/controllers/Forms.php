<?php
/**
 * File for handling different types of form
 */ 

/**
 * Registration forms controller
 * This class is used to generate different types of registration forms (MMP, LDB etc)
 */ 
class Forms extends MX_Controller
{
    /**
     * @var object customizemmp_model
     */ 
    private $mmpModel;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        $this->load->model('customizedmmp/customizemmp_model');
        $this->mmpModel = new customizemmp_model();
        
        $this->load->helper('string');
    }
    
    /**
     * Generate an MMP form by MMP form id (aka MMP page id)
     *
     * @param integer $mmpFormId
     * @param integer $includeHTMLDependencies
     * @param array $customFormData 
     */ 
    function MMP($mmpFormId,$includeHTMLDependencies = 0, $customFormData = array())
    {
        $mmpFormInt = intval($mmpFormId);
        $mmpFormIdLength = strlen($mmpFormId);
        $mmpFormIntLength = strlen($mmpFormInt);
		if($mmpFormInt <= 0 || $mmpFormIdLength != $mmpFormIntLength) {
            return;
		}
	
        $mmpFormId = intval($mmpFormId); 	
        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('mmpFormId' => $mmpFormId));
        $fields = $registrationForm->getFields();
        $customRules = $registrationForm->getRules();
        $loggedInUserData = $this->getLoggedInUserData();
		if(!empty($customFormData['regFormId'])) {
			$regFormId = $customFormData['regFormId'];
		} else{
			$regFormId = random_string('alnum', 6);
		}
        $registrationHelper = new \registration\libraries\RegistrationHelper($fields,$regFormId);
    
		$mmpData = $this->mmpModel->getMMPDetails($mmpFormId);
		
        $data = array();
        $data['fields'] = $fields;
        $data['customRules'] = json_encode($customRules);
        $data['regFormId'] = $regFormId;
        $data['registrationHelper'] = $registrationHelper;
        $data['formData'] = $registrationHelper->getFormData($loggedInUserData);
        $data['mmpData'] = $mmpData;
        $data['mmpFormId'] = $mmpFormId;
		$data['includeHTMLDependencies'] = $includeHTMLDependencies;
		$data['displayOnPage'] = $mmpData['display_on_page'];
		global $userid;
		$userid = $loggedInUserData['userId'];
			
		global $mmp_display_on_page_array;
		global $MMP_Tracking_keyId;
        // SA mobile MMP
		if($data['formData']['userId'] && isMobileRequest() && $data['mmpData']['display_on_page'] == 'newmmp'){
           $preFilledData = Modules::run('user/Userregistration/getUserInfo',$data['formData']['userId'], '' , false);
           $preFilledData['userId'] = $data['formData']['userId'];
           
           $this->load->model('user/usermodel');
           $dataPref = $this->usermodel->getAbroadShortRegistrationData($data['formData']['userId']);
            
            if($dataPref['whenPlanToGo'] != NULL){
               $planToGo = explode("-",$dataPref['whenPlanToGo']);
               $preFilledData['planToGo'] = $planToGo[0];
            }

           $data['formData'] = $preFilledData;
	   
        }

        if(!empty($mmpFormId)) {
            $mmpformisdCode = "91";
            $mmpformcountryId = "2";
            if(!empty($data['formData']['isdCode'])) {
               $mmpformisdCode = $data['formData']['isdCode'];
            }
            if(!empty($data['formData']['countryId'])) {
               $mmpformcountryId = $data['formData']['countryId'];
            }
            $data['formData']['isdCode'] = $mmpformisdCode.'-'.$mmpformcountryId;
        } else {
            $data['formData']['isdCode'] = $data['formData']['isdCode'].'-'.$data['formData']['countryId'];
        }
		
        if($data['formData']['userId'] && isMobileRequest() && $data['mmpData']['display_on_page'] == 'newmmp'){
            $data['formData']['isdCode'] = $preFilledData['isdCode'];
        }

		// reset password code add for marketing page
		$reset_password = trim(strip_tags($_REQUEST['resetpwd']));
		if($reset_password == 1) {
			$reset_password_token = trim(strip_tags($_REQUEST['uname']));
			$reset_usremail = trim(strip_tags($_REQUEST['usremail']));			
			$data['reset_password_token'] = $reset_password_token;
			$data['reset_usremail'] = $reset_usremail;				
		}
		
        $data['load_ga'] = TRUE;
        if(array_key_exists('load_ga',$customFormData)) {
			$data['load_ga'] = $customFormData['load_ga'];
		}
		
		$data['googleRemarketingParams'] = $this->_getMMPGoogleRemarketingParams($data['mmpData'], $fields);
		
        if(isMobileRequest()) {
            $data['trackingPageKeyId'] = $MMP_Tracking_keyId['mobile'][$mmpData['page_type']];
			if($mmpData['display_on_page'] == 'newmmp' || in_array($mmpData['display_on_page'],$mmp_display_on_page_array)) {
				
                if($mmpData['page_type'] == 'abroadpage'){  //Now mmp is in SA mobile
					
                    $this->load->view('registration/newmobilemmp/SA/form',$data);
					
                }else{
					//below line is used for conversion tracking purpose of download guide from categoryPage
                    if( ! empty($customFormData['trackingPageKeyId']))
                            $data['trackingPageKeyId'] = $customFormData['trackingPageKeyId'];

				    $this->load->view('registration/newmobilemmp/national/form',$data);
					
                }
				
			} else {
				
				$this->load->view('registration/mobilemmp/form',$data);
				
			}
        
		} else {
            $data['trackingPageKeyId'] = $MMP_Tracking_keyId['desktop'][$mmpData['page_type']];
			if(in_array($mmpData['display_on_page'],$mmp_display_on_page_array)) {
				
				if($mmpData['page_type'] == 'abroadpage') {
					
					$this->load->view('registration/NewMMP/abroad/form',$data);
					
				} else {
					
                    if( ! empty($customFormData['loadmmponpopup']))
                            $data['loadmmponpopup'] = $customFormData['loadmmponpopup'];
					$this->load->view('registration/NewMMP/national/form',$data);
					
				}
				
			} else {
			
				$this->load->view('registration/MMP/form',$data);
				
			}
			
        }
		
    }
    /**
     *Function to generate the Google Remarketing Params
     *
     * @param array $mmpData MMP Page Data
     * @param array $fields Array with form fields
     *
     * @return array $googleRemarketingParams
     * 
     */
	private function _getMMPGoogleRemarketingParams($mmpData, $fields)
	{
		if($mmpData['page_type'] != 'indianpage') {
			return array();
		}
		if(!$fields['desiredCourse']) {
			return array();
		}
		$preSelectedDesiredCourses = $fields['desiredCourse']->getPreSelectedValues();
		if(!is_array($preSelectedDesiredCourses) || count($preSelectedDesiredCourses) == 0) {
			return array();
		}
		$desiredCourse = $preSelectedDesiredCourses[0];
		$this->load->builder('LDBCourseBuilder','LDB');
		$ldbCourseBuilder = new LDBCourseBuilder;
		$ldbRepository = $ldbCourseBuilder->getLDBCourseRepository();
		$ldbCourseObj = $ldbRepository->find($desiredCourse);
		$subCatId = $ldbCourseObj->getSubCategoryId();
		$mainCategoryId = $ldbCourseObj->getCategoryId();
		$googleRemarketingParams = array(
			'categoryId' => $mainCategoryId,
			'subcategoryId' => $subCatId,
			'countryId' => 2
		);
		return $googleRemarketingParams;
	}
    
    /**
     * Function to generate the Template for MMP marketing form
     * @param integer $pageId
     *
     * @return array $templatizedMMP
     */
    public function templatizedMMP($pageId)
    {
        $isMobile = isMobileRequest();
		$header = $this->load->view('registration/MMP/templatizedMMPHeader',array('isMobile' => $isMobile),TRUE);
        $form = Modules::run('registration/Forms/MMP', $pageId);
        
        $templatizedMMP = array(
            '@MMPFORM' => $form,
            '@MMPHEADER' => $header
        );
        
        if($isMobile) {
            $mmpData = $this->mmpModel->getMMPDetails($pageId);
            if($mmpData['page_type'] == 'abroadpage') {
                $destinationCountryLayerMobile = $this->load->view('/muser5/destinationCountry',array(),TRUE);
                $templatizedMMP['@DESTINATIONCOUNTRYLAYERMOBILE'] = $destinationCountryLayerMobile;
            }
            else {
                $preferredLocationLayerMobile = $this->load->view('/muser5/preferredStudyLocation',array(),TRUE);
                $templatizedMMP['@PREFERREDLOCATIONLAYERMOBILE'] = $preferredLocationLayerMobile;
            }
        }
        
        return $templatizedMMP;
    }
    
    /**
     * Generate LDB form
     *
     * @param string $courseGroup e.g. nationalUG, nationalPG, localUG, studyAbroad etc.
     * @param string $context e.g. unified, registerFree etc.
     * @param array $customFormData
     */ 
    function LDB($courseGroup,$context = 'default',$customFormData = array())
    {
    	$data = array();
    	
    	$loggedInUserData = $this->getLoggedInUserData();
        if(!$context) {
            $context = 'studyAbroadRevamped';
        }
	   
        $userInfoArray['isdCode'] = \registration\libraries\RegistrationHelper::getUserCountryByIP();
	
    	$isFullRegisteredUser = null;
        
        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => $courseGroup,'context' => $context)); 
        $fields = $registrationForm->getFields($customFormData['showPassword']);

        $userPreferredCountries = array();
        //$contextGroup = array('mobile', 'registerResponse', 'registerResponseLPR', 'shortlistRegister', 'askQuestionBottom', 'rmcPage', 'rmcStudentProfile', 'mobileRmcPage', 'unifiedProfile', 'askQuestionBottomCompare','studyAbroadSingleRegistrationForm');
        if($loggedInUserData['userId'] && $context != 'unified') {
            
            $this->load->model('user/usermodel');
            $userModel = new UserModel;

    	    $userInfoArray = $this->getUserProfileInfo($loggedInUserData['userId'],'',$context);
    	    $isFullRegisteredUser = $this->isFullRegisteredUser($userInfoArray, $fields);
    	    $data['isFullRegisteredUser'] = $isFullRegisteredUser;
    	    
            $abroadShortRegistrationData = $userModel->getAbroadShortRegistrationData($loggedInUserData['userId']);
            $user = $userModel->getUserById($loggedInUserData['userId']);
            if(is_object($user)) {
                $locPreferences = $user->getLocationPreferences();
                if(is_object($user)) {
                    foreach ($locPreferences as $key=>$locPreference){
                        $userPreferredCountries[] = $locPreference->getCountryId();
                    }
                }
            }
    	}
    	$data['userPreferredDestinations'] = $userPreferredCountries;
        $customRules = $registrationForm->getRules();
	
//    	if($context == 'registerResponse' || $context == 'registerResponseLPR' || $context == 'mobile' || $context == 'shortlistRegister' || $context == 'askQuestionBottom' || $context == 'askQuestionBottomCompare') {
//                if($isFullRegisteredUser === true){
//                    foreach($fields as $field) {
//                        $field->setVisible(false);
//                    }
//                }
//                else{
//                    $currentTime = date('Y-m-d H:i:s');
//                    $prefSubmitTime = $userInfoArray['prefSubmitTime'];
//                    $timeDiff = (strtotime($currentTime) - strtotime($prefSubmitTime)) / 2592000;
//                    if($timeDiff < 1){
//                        foreach($fields as $key=>$field) {
//                            if($userInfoArray[$key] && $field->isVisible()){
//                                $field->setVisible(false);
//                            }
//                        }
//                    }
//                }
//    	}
        
        $data['fields'] = $fields;
        $data['customRules'] = json_encode($customRules);
	
    	if($customFormData['regFormId']){
    	    $data['regFormId']=$customFormData['regFormId'];
    	}else{
    	    $data['regFormId'] = random_string('alnum', 6);
    	}
	
        $registrationHelper = new \registration\libraries\RegistrationHelper($fields,$data['regFormId']);
        $data['registrationHelper'] = $registrationHelper;
        
        $formData = $loggedInUserData;
        $data['formData'] = $userInfoArray;
        /*this sets the prefilled values */
//        if($context == 'mobileRegistrationNational'){
//            $data['customFormData'] = $customFormData;
//            $data['formData'] = $this->_formatPrefilledData($customFormData['customFields'], $userInfoArray);
//        }
		
        /**
         * If called from course page, the course page subcategory id will be passed
         */
        if($customFormData['coursePageSubcategoryId']) {
            $data['formData']['coursePageSubcategoryId'] = $customFormData['coursePageSubcategoryId'];
            $this->load->builder('CategoryBuilder','categoryList');
            $categoryBuilder = new CategoryBuilder;
            $categoryRepository = $categoryBuilder->getCategoryRepository();
            $subcategoryObj = $categoryRepository->find($customFormData['coursePageSubcategoryId']);
            $customFormData['preSelectedCategoryId'] = $subcategoryObj->getParentId();
        }
        if($customFormData['preSelectedCategoryId']) {
            $data['formData']['fieldOfInterest'] = $customFormData['preSelectedCategoryId'];
            if(array_key_exists('fieldOfInterest',$data['fields'])) {
                $data['fields']['fieldOfInterest']->setVisible(FALSE);
            }
        }
        if($customFormData['preSelectedDesiredCourse']) {
            $data['formData']['desiredCourse'] = $customFormData['preSelectedDesiredCourse'];
        }
        
//        if($context == 'mobile'){
//            $data['allCustomFormData'] = $customFormData;
//        }
        
        $data['context'] = $context;
        $data['courseGroup'] = $courseGroup;
        if(is_array($customFormData)) {
            foreach($customFormData as $customFormDataKey => $customFormDataValue) {
                if(!array_key_exists($customFormDataKey,$data)) {
                    $data[$customFormDataKey] = $customFormDataValue;
                }
            }
        }
	
	if(is_array($customFormData) && $customFormData['examPageValue'] !='' && $customFormData['examPageCategory']!=''){
	    $data['examPageValue']    = $customFormData['examPageValue'];
	    $data['examPageCategory'] = $customFormData['examPageCategory'];
	}
    	if(!empty($abroadShortRegistrationData)) {
    	    $data['abroadShortRegistrationData'] = $abroadShortRegistrationData;
    	}
    	
    	if(is_array($customFormData) && $customFormData['rpExamName'] !=''){   // added by akhter, prepare registration form for rankPredictor
    	    $rpConfig = $customFormData['rpConfig'];
    	    $data['examName'] = $customFormData['rpExamName'];
    	    $data['rpConfig'] = $rpConfig;
    	}
    	if(isset($customFormData['replyMsg']) && $context == 'registerFree'){
    	    $data['replyContext'] = $customFormData['replyContext'];
    	    
    	}
       $data['trackingPageKeyId'] = $customFormData['trackingPageKeyId'];
       if(!isset($data['trackingPageKeyId']))
       {
            $data['trackingPageKeyId'] = $customFormData['tracking_keyid'];
       }
       $data['consultantTrackingPageKeyId'] = $customFormData['consultantTrackingPageKeyId'];

        $this->load->view('registration/'.$context.'/form',$data); 
    }
    
    /**
     * Function for offlineLDB
     * 
     * @param array $userData
     * @param string $courseGroup
     * @param string $context
     * @param array $customFormData
     */
    function offlineLDB($userData, $courseGroup, $context = 'default', $customFormData = array()){
        $userRepository = \user\Builders\UserBuilder::getUserRepository();
        $userInfoArray = $userData;//array();
        
        if($userInfoArray['userId']) {
            $this->load->model('user/usermodel');
            $userModel = new UserModel;
            if($user = $userModel->getUserById($userInfoArray['userId'])) {
                $userFlags = $user->getFlags();
                if($userFlags->getIsLDBUser() == 'NO') {
		    if($courseGroup == 'studyAbroadRevamped') {
			$abroadShortRegistrationData = $userModel->getAbroadShortRegistrationData($userInfoArray['userId']);
		    }
                    if($courseGroup != 'studyAbroadRevamped') {
                        $context = 'nonLDBLoggedInUser';
                    }
                }else{
                    if($courseGroup != 'studyAbroadRevamped') {
                        $context = 'nonLDBLoggedInUser';
                    }
                }
            }
        }
        
        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => $courseGroup,'context' => $context)); 
        $fields = $registrationForm->getFields();
        $customRules = $registrationForm->getRules();
        $data = array();
        $data['userId'] = $userData['userId'];
        $data['userIdOffline'] = $userData['userId'];
        $data['fields'] = $fields;
        $data['customRules'] = json_encode($customRules);
        
        $data['regFormId'] = random_string('alnum', 6);
        
        $registrationHelper = new \registration\libraries\RegistrationHelper($fields,$data['regFormId']);
        $data['registrationHelper'] = $registrationHelper;
        
        $formData = $userInfoArray;
        $data['formData'] = $formData;
        $data['context'] = $context;
        $data['courseGroup'] = $courseGroup;
        
        if(!empty($abroadShortRegistrationData)) {
	       $data['abroadShortRegistrationData'] = $abroadShortRegistrationData;
	    }
	
        $this->load->view('registration/'.$context.'/form',$data);
    }

    /**
     * Function for Second Layer
     */
    function secondLayer()
    {
        $userData = $this->getLoggedInUserData();
        $form = \registration\builders\RegistrationBuilder::getRegistrationForm('SecondLayer',$userData);
        
        if($form) {
            $fields = $form->getFields();
            $userRepository = \user\Builders\UserBuilder::getUserRepository();
            $user = $userRepository->find($userData['userId']);
            $education = $user->getEducation();
            
            if(count($education) > 0) {
                foreach($education as $educationLevel) {
                    if($educationLevel->getLevel() == '12') {
                        $fields['xiiStream']->setVisible(FALSE);
                        $fields['xiiYear']->setVisible(FALSE);
                        $fields['xiiMarks']->setVisible(FALSE);
                    }
                }
            }
            $prefs = $user->getPreference();
            $desiredCourse = $prefs->getDesiredCourse();
            
            $data['fields'] = $fields;
            $data['regFormId'] = random_string('alnum', 6);
            
            $registrationHelper = new \registration\libraries\RegistrationHelper($fields,$data['regFormId']);
            $data['registrationHelper'] = $registrationHelper;
            
            $formData = $userData;
            $data['formData'] = $formData;
            
            $data['desiredCourse'] = $desiredCourse;
            
            $data['redirectURL'] = $this->input->post('redirectURL');
            $this->load->view('registration/secondLayer/form',$data);
        }
    }
    
    /**
     * Load registration form by AJAX when desired course is changed
     */ 
    public function getFormByDesiredCourse()
    {
		$data = array();
		$isFullRegisteredUser = null;
		$fieldOfInterest = $this->input->post('fieldOfInterest');
		$desiredCourseId = $this->input->post('desiredCourse');
        $mmpFormId = $this->input->post('mmpFormId');
        $regFormId = $this->input->post('regFormId');
        $context = $this->input->post('context');
        $fieldsView = $this->input->post('fieldsView');
		$widget = $this->input->post('widget');
        $display_on_page = $this->input->post('display_on_page');
		$isdCode = $this->input->post('isdCode');
	
		$showPassword = false;
		if($widget == 'onlineForm') {
		    $showPassword = true;
		}
	
        if(!$fieldsView && $context!='mobile') {
            $fieldsView = 'default';
        }
		if($context=='mobile' || $context=='mobileRegistrationNational'){
	    	$fieldsView = $context;
        }
        global $mmp_display_on_page_array;
		
        if($mmpFormId) { 
            $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('mmpFormId' => $mmpFormId,'desiredCourseId' => $desiredCourseId));
			if(in_array($display_on_page,$mmp_display_on_page_array)) {
				$view = 'registration/fields/NewMMP/variable/'.$fieldsView;
			} else {
				$view = 'registration/fields/MMP/variable/'.$fieldsView;
			}
        }
        else {
            $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('desiredCourseId' => $desiredCourseId,'context' => $context,'fieldOfInterest' => $fieldOfInterest));
            $view = 'registration/fields/LDB/variable/'.$fieldsView;
        }
        

        $fields = $registrationForm->getFields($showPassword);
        $customRules = $registrationForm->getRules();
        
        // global INDIA_ISD_CODE;
        if($isdCode != INDIA_ISD_CODE){
            if(isset($fields['residenceCity'])){
                unset($fields['residenceCity']);
            }else if(isset($fields['residenceCityLocality'])){
                unset($fields['residenceCityLocality']);
                unset($fields['residenceLocality']);
            }   
        }
        
        $registrationHelper = new \registration\libraries\RegistrationHelper($fields,$regFormId);
        
        /**
         * Filter POST array to get form data
         * Used to show pre-selected values/retain user-selected/entered values after AJAX refresh
         */
		$this->load->helper('security');
        $post_data = xss_clean($_POST);
        $formData = $registrationHelper->getFormData($post_data);
        
	//get loggedin userdata
        $loggedInUserData = $this->getLoggedInUserData();
        if($loggedInUserData['userId'] > 0) {
	    $userInfoArray = $this->getUserProfileInfo($loggedInUserData['userId']);
        $userInfoArray['isdCode'] = $formData['isdCode'];
	    $userInfoArray['mobile'] = $formData['mobile'];
	    $userInfoArray['firstName'] = $formData['firstName'];
	    $userInfoArray['lastName'] = $formData['lastName'];
	    $userInfoArray['fieldOfInterest'] = $fieldOfInterest;
	    $userInfoArray['desiredCourse'] = $desiredCourseId;
	    $userInfoArray['widget'] = $widget;
	    
	    $isFullRegisteredUser = $this->isFullRegisteredUser($userInfoArray, $fields); 
	    if($context == 'registerResponse' || $context == 'registerResponseLPR' || $context == 'askQuestionBottom' || $context == 'askQuestionBottomCompare' || ($context == 'mobile' && (empty($mmpFormId))) || $context == 'shortlistRegister') {
		$data['isFullRegisteredUser'] = $isFullRegisteredUser;
		
		if($isFullRegisteredUser === true){
		    foreach($fields as $fieldId => $field) {
			$field->setVisible(false);
		    }
		}
		else{
		    $currentTime = date('Y-m-d H:i:s');
		    $prefSubmitTime = $userInfoArray['prefSubmitTime'];
		    $timeDiff = (strtotime($currentTime) - strtotime($prefSubmitTime)) / 2592000;
		    
		    if($timeDiff < 1){
			foreach($fields as $key=>$field) {
			    if($userInfoArray[$key] && $field->isVisible()){
				$field->setVisible(false);
			    }
			}
		    }
		}
	    }
	}
	    //check when the user is coming from search exam widget registration
        if($this->input->post('reg_action') == 'registrationHookFromSearch' && $context == 'mobile') {
            $fields['exams']->setVisible(false);
            $formData['exams'][] = $this->input->post('examName');
        }
        $data['regFormId'] = $regFormId;       
        $data['fields'] = $fields;
        $data['registrationHelper'] = $registrationHelper;
        $data['formData'] = $formData;
        $data['context'] = $context;
        $data['mmpFormId'] = $mmpFormId;
	    $data['desiredCourse'] = $desiredCourseId;
        if($context=='mobile'){
                $data['step'] = '2';    
        }
        
        $modified_view = str_replace("registration/fields/","/var/www/html/shiksha/application/modules/User/registration/views/fields/",$view);
        $modified_view = $modified_view.".php";
        
        if(!file_exists($modified_view)) {
				echo json_encode(array());
				return;
		}
                
        $form = $this->load->view($view,$data,TRUE);
        echo json_encode(array(
                                'form' => $form,
                                'formData' => ($loggedInUserData['userId'] > 0)?$userInfoArray:$formData,
                                'customRules' => $customRules,
                                'defaultFieldStates' => $registrationHelper->getDefaultFieldStates(),
                                'fieldList' => array_keys($fields),
                                'isFullRegisteredUser' => $isFullRegisteredUser,
				'context' => $context
                            ));
    }

    public function getResidenceCity(){
        $regFormId = $this->input->post('regFormId');
        $isdCode = $this->input->post('isdCode');

        $data['regFormId'] = $regFormId;
        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'nationalDefault')); 
        $data['fields']['residenceCityLocality'] = $registrationForm->getField('residenceCityLocality');
        $data['registrationHelper'] = new \registration\libraries\RegistrationHelper($data['fields'], $data['regFormId']);

        echo $this->load->view('registration/fields/LDB/variable/residenceCityLocality',$data);
    }
    
    /**
     * Get desired courses by ajax when category (education interest) is changed
     *
     * @param integer $categoryId 
     */ 
    public function getDesiredCourses($categoryId)
    {
        $registrationForm = new \registration\libraries\Forms\LDB;
        $desiredCourseField = $registrationForm->getField('desiredCourse');
        
        $coursePageSubcategoryId = $this->input->post('coursePageSubcategoryId');
        
        if($coursePageSubcategoryId) {
            $desiredCourses = $desiredCourseField->getValues(array('coursePageSubcategoryId' => $coursePageSubcategoryId));
            $desiredCourses = $desiredCourses[0];
        }
        else {
            $desiredCourses = $desiredCourseField->getValues(array('categoryId' => $categoryId));
            if($categoryId == 3) {
                $desiredCourses = $desiredCourses[0];
            }
        }
        echo json_encode($desiredCourses);
    }
    
    /**
     * Get localities by ajax when city is changed
     *
     * @param integer $cityId
     */ 
    public function getLocalities($cityId)
    {
	$cityId = trim($cityId);
	
	if(empty($cityId)){
	    echo 0;
	    return;
	}
	
        $this->init();
        $registrationForm = new \registration\libraries\Forms\LDB; 
        $localityField = $registrationForm->getField('preferredStudyLocality');
        $response = array();
        
        $localities = $localityField->getValues(array('cityId' => $cityId));
        $response['localities'] = $this->load->view('registration/common/dropdowns/preferredLocality',array('localities' => $localities),TRUE);
        
        $cityGroup = $localityField->getValues(array('cityId' => $cityId,'cityGroup' => TRUE));
        $response['cityGroup'] = $this->load->view('registration/common/dropdowns/preferredLocalityCityGroup',array('cityGroup' => $cityGroup),TRUE);
        echo json_encode($response);
    }
    
    /**
     * Get All Customized citiesId by Modules::run, for special courses like SMU
     *
     * @return JSON $customCourses
     *
     */
	
    public function getAllCustomCourse(){
	$this->load->model('registration/registrationmodel');
	$result = $this->registrationmodel->getAllCustomCourse();
	$customCourses= array();
	foreach($result as $k=>$v){
	    $customCourses[] = $v['course_id'];
	}
	
	return json_encode($customCourses);
	
    }
    
     /**
     * Function to get Customized cities and localities for special courses like SMU
     *
     * @param integer $listingId
     * @return array $customCities on success(if city found) else 0
     *
     */
    public function getCustomizedCityLocList($listingId){
	
	//$listingId = $this->input->post('listingId');
	$this->load->model('registrationmodel');
	$customCity = $this->registrationmodel->checkCustomizedCity($listingId);
	
	if($customCity){
	    $customCities = $this->_getAllCustomizedCityAndLocalities($listingId);
	    return $customCities;
	}else{
	    echo 0;
	}
    }
    
    /**
     * Function to get Custom locality list based on the custom course list
     *
     * @param array $CustomCourseList
     * @return JSON $newArr
     *
     */
    public function allCustomLocCityList($CustomCourseList){
	$CustomCourseList = json_decode($CustomCourseList);
	
	$newArr = array();
	foreach($CustomCourseList as $key=>$courseId){
	    $newArr[$courseId] = $this->getCustomizedCityLocList($courseId);
	}
	return json_encode($newArr);
	
    }
    /**
     * Get Customized citiesId by ajax call, for special courses like SMU
     *
     */
     
    public function getCustomizedCities(){
	
	$listingId = $this->input->post('listingId',true);
	$listingId = trim($listingId);
	if(empty($listingId)){
	   echo 0;
	   return;
	}
	
	$this->load->model('registrationmodel');
	$customCity = $this->registrationmodel->checkCustomizedCity($listingId);
	if($customCity){
	    $customCities = $this->_getAllCustomizedCityAndLocalities($listingId);
	    echo json_encode($customCities);
	}else{
	    echo 0;
	}
    }
    
    
    /**
     * Get Customized cities and localities by ajax call, for special courses like SMU
     *
     * @param integer $listingId
     * @return array $cityLocalityArray
     */
    
    private function _getAllCustomizedCityAndLocalities($listingId){
	
	$this->load->model('registrationmodel');
	$customLocalities = $this->registrationmodel->getCustomizedLocalitiesId($listingId);
	$cityLocalityArray = array();
	foreach($customLocalities as $k=>$v){
	    $cityLocalityArray[$v['City']][] = $v['Locality'];
	}
	
	return $cityLocalityArray;
    }
    
    /**
     * Get localities by ajax when city is changed
     *
     * @param integer $cityId
     */ 
    public function getLocalitiesMMP($cityId)
    {

        if($cityId <= 0) {
		return json_encode(array());
        } 
        $this->init();
        $registrationForm = new \registration\libraries\Forms\LDB; 
        $localityField = $registrationForm->getField('preferredStudyLocality');
        $response = array();
        
        $localities = $localityField->getValues(array('cityId' => $cityId));
        
        $localitiesArr = array();
        foreach($localities as $zoneId => $localitiesInZone) {
            $firstLocality = reset($localitiesInZone);
            $localitiesArr[$firstLocality['zoneName']] = array();
            foreach($localitiesInZone as $locality) {
                $localitiesArr[$firstLocality['zoneName']][$locality['localityId']] = $locality['localityName'];
            }
        }
        if(count($localitiesArr)) {
            $response['localities'] = $localitiesArr;
        }
        
        $cityGroup = $localityField->getValues(array('cityId' => $cityId,'cityGroup' => TRUE));
        $cityArr = array();
        foreach($cityGroup as $city) {
            $cityArr[$city['city_id']] = $city['city_name'];
        }
        
        if(count($cityArr)) {
            $response['cityGroup'] = $cityArr;
        }
        echo json_encode($response);
    }
     
    /**
     * Function to Show unified registration layer
     */ 
    public function showUnifiedLayer()
    {
        $userData = $this->getLoggedInUserData();
        $data['userData'] = $userData;
        $data['categoryId'] = $this->input->post('categoryId');
        $data['desiredCourse'] = $this->input->post('desiredCourse');
        $data['studyAbroad'] = $this->input->post('studyAbroad');
        $data['registrationMessage'] = lang($this->input->post('registrationSource'));
        $data['layerTitle'] = trim($this->input->post('layerTitle'));
        $data['layerHeading'] = trim($this->input->post('layerHeading'));
        $data['showBothIndiaAbroadForms'] = trim($this->input->post('showBothIndiaAbroadForms'));
        $this->load->view('unified',$data);
    }
    
    /**
     * Function to Show register free layer
     */ 
    public function showRegisterFreeLayer()
    {
		$formCustomData = array();
        $userData = $this->getLoggedInUserData();
        $data['userData'] = $userData;
        $data['studyAbroad'] = $this->input->post('studyAbroad');
        $data['layerTitle'] = trim($this->input->post('layerTitle'));
        $data['layerHeading'] = trim($this->input->post('layerHeading'));
	    $data['submitText'] = trim($this->input->post('submitText'));
        $data['hasCallback'] = trim($this->input->post('hasCallback'));
	    $data['hideLoginLink'] = trim($this->input->post('hideLoginLink'));
	    $comparePageLogin = trim($this->input->post('comparePageLogin')); // only call on compare button
	    $collegePredictor = trim($this->input->post('collegePredictor'));
        $isCustomForm = $this->input->post('isCustomForm', true);
        $replyContext = trim($this->input->post('replyContext',true));
        $threadId = trim($this->input->post('threadId',true));
	    $rankPredictor = trim($this->input->post('prepareInlineRPForm'));
	    $menteeForm = trim($this->input->post('prepareInlineMentee'));
        $trackingPageKeyId=trim($this->input->post('trackingPageKeyId'));
        
        //checking existence of trackingPageKeyId
        if(isset($trackingPageKeyId))
            $data['trackingPageKeyId']=$trackingPageKeyId;
	
        if($this->input->post('isCompareEmail') == true) {
    		$data['isCompareEmail'] = true;
    	}
    	else {
    		$data['isCompareEmail'] = false;
    	}
    		
    	$courseId = intval($this->input->post('courseId'));
    	if($courseId > 0) {
    		$this->load->library('listing/NationalCourseLib');
    		$nationalCourseLib = new NationalCourseLib();
    		$courseInfo = $nationalCourseLib->getDominantDesiredCourseForClientCourses(array($courseId));
    		$formCustomData['preSelectedCategoryId'] = $courseInfo[$courseId]['categoryId'];
    		$formCustomData['preSelectedDesiredCourse'] = $courseInfo[$courseId]['desiredCourse'];
    	}else if(isset($collegePredictor) && $collegePredictor == 'yes'){ // College Predictor is for BE/BTECH only.
            $formCustomData['customReferer'] =$_SERVER['HTTP_REFERER'];
            $formCustomData['preSelectedCategoryId'] = trim($this->input->post('selectedFOI'));
            $formCustomData['preSelectedDesiredCourse'] = trim($this->input->post('selectedDesiredCourse'));
        }
        
            if($registrationSource = trim($this->input->post('source'))) {
                $formCustomData['customRegistrationSource'] = $registrationSource;
            }
            if($referer = trim($this->input->post('referer'))) {
                $formCustomData['customReferer'] = $referer;
            }
    		$data['regFormId'] = random_string('alnum', 6);
            $data['formCustomData'] = $formCustomData;

            if(isset($replyContext) && $replyContext != ''){
               $data['customReferer'] = $_SERVER["HTTP_REFERER"]; 
            }
            
    	if(isset($comparePageLogin) && $comparePageLogin == 'yes')  // load view for compare button login layer
    	{
    	    $this->load->view('comparePageRegister',$data);
    	}else if(isset($collegePredictor) && $collegePredictor == 'yes'){
    	    $this->load->view('collegePredictor',$data);
    	}else if(isset($rankPredictor) && $rankPredictor == 'yes'){
    	    $data['rpExamName'] = isset($_POST['rpExamName']) ? trim($this->input->post('rpExamName')) : 'jee-main';
    	    $this->load->view('rankPredictor',$data);
    	}else if(isset($menteeForm) && $menteeForm == 'yes'){
    	    $data['regFormId'] = trim($this->input->post('regFrmId'));
    	    $this->load->view('menteeForm',$data);
    	}else if(isset($isCustomForm) && $isCustomForm == 'yes'){
            $data['customData'] =  json_decode($this->input->post('customData'));
            $formCustomData['trackingPageKeyId'] = $this->input->post('trackingPageKeyId');
            $formCustomData['regFormId']=$data['regFormId'];
            $data['formCustomData'] =$formCustomData;

            $this->load->view('customRegisterFree',$data);
        }else{
            $data['replyContext'] = isset($replyContext) && $replyContext != '' ? $replyContext :'no';
            $data['threadId'] = isset($threadId) && $threadId != '' ? $threadId : '0';
    	    $this->load->view('registerFree',$data);
    	}
    }
    
    /**
     * Function to Show register free layer
     * @param array $data
     */ 
    public function showResponseRegisterFreeLayer($data = array())
    {
        $userData = $this->getLoggedInUserData();
        $data['userData'] = $userData;
        $data['layerTitle'] = $this->input->post('layerTitle');
        $data['buttonText'] = $this->input->post('buttonText');
        $data['tracking_keyid'] = $this->input->post('tracking_keyid');
//        $data['layerHeading'] = trim($this->input->post('layerHeading'));
//        $data['hasCallback'] = trim($this->input->post('hasCallback'));
        
        $formCustomData = array();
        if($registrationSource = trim($this->input->post('source'))) {
            $formCustomData['customRegistrationSource'] = $registrationSource;
        }
        if($referer = trim($this->input->post('referer'))) {
            $formCustomData['customReferer'] = $referer;
        }
	$data['regFormId'] = random_string('alnum', 6);
        $formCustomData['userData'] = $userData;
        $data['formCustomData'] = $formCustomData;
        $this->load->view('registration/registerResponse',$data);
    }
    
    /**
     * Function to Show new abroad registration form
     */
    public function showTwoStepLayer(){
        $userData = $this->getLoggedInUserData();
        $data['userData'] = $userData;
        $data['registrationDomain'] = $this->input->post('registrationDomain');
	
	$heading = $this->input->post('layerHeading');
	if(isset($heading)) {
	    $data['layerHeading'] = $heading;
	}
        $data['hasCallback'] = trim($this->input->post('hasCallback'));
        
        $this->load->view('twoStepRegister',$data);
    }
    
    /**
     * Function to  sShow new abroad registration form
     */
    public function showOneStepLayer(){
        $userData = $this->getLoggedInUserData();
        $data['userData'] = $userData;
        $data['registrationDomain'] = $this->input->post('registrationDomain');
	
		$heading = $this->input->post('layerHeading');
		if(isset($heading)) {
			$data['layerHeading'] = $heading;
		}
        $data['hasCallback'] = trim($this->input->post('hasCallback'));
        $data['trackingPageKeyId'] = $this->input->post('trackingPageKeyId');
        $data['fileName'] = $this->input->post('fileName');// profile evaluation call
        $data['fileSize'] = $this->input->post('fileSize');// profile evaluation call
        
        $this->load->view('oneStepRegister',$data);
    }
    
    /**
     * Function to Show new abroad downloadEbrochure form
     * @param string $registrationDomain
     * @param array $data
     */
    public function showDownloadEbrochureLayer($registrationDomain, $data){
        
		if(is_array($data['userData']) && isset($data['userData'][0])){
			$data['userCity'] = $data['userData'][0]['city'];
		}
        $userData                   = $this->getLoggedInUserData();
        $data['userData']           = $userData;
        $data['registrationDomain'] = $registrationDomain;
        //$data['hasCallback'] = trim($this->input->post('hasCallback'));

    	//send's otp message to returning user
    	if($data['OTPforReturningUser']){
            $generateOTP['email']                  = $data['userData']['email'];
            $generateOTP['mobile']                 = $data['userData']['mobile'];
            $generateOTP['verification']           = 'OTP';
            $generateOTP['isStudyAbroad']          = 1;
            $generateOTP['isStudyabroadReturning'] = 1;
            $generateOTP['trackingKeyId']          = $data['trackingPageKeyId'] ? $data['trackingPageKeyId'] : 0;
            $generateOTP['isResend']               = 0;
            $generateOTP['isChangeNumber']         = 0;
    	    echo Modules::run('userVerification/verifyUser', $generateOTP);
    	}
    	if($registrationDomain == 'studyAbroadMobile')
    	{
    	    // mobileSiteSA
    	    $this->load->view('registration/mobileResponseAbroad',$data);
    	}
            else{
    	    $this->load->view('registration/downloadEbrochureSA',$data);
    	}
    }

    /**
     * Function to Show second layer
     */ 
    public function showSecondLayer()
    {
        $userData = $this->getLoggedInUserData();
        $data['userData'] = $userData;
        $data['redirectURL'] = $this->input->post('redirectURL');
        $this->load->view('secondLayer',$data);
    }

    public function showSecondLayerMobile(){
        $this->load->view('registration/common/conversionTrackingMobile');   
    }
    
    /**
     * Function to Show login layer
     */ 
    public function showLoginLayer()
    {
		$this->load->helper('security');
        $data = xss_clean($_POST);

	if($data['isStudyAbroadPage'] == 1){
		$this->load->view('loginStudyAbroad',$data);
        }else{
		if($data['email'] && $data['email']!='undefined') {
		    $this->load->view('loginWithEmail',$data);
		}else {
		$this->load->view('login',$data);    
		}
	}
    }
    
    /**
     * Function to show user verfication layer
     */
    public function userVerificationLayer(){
		$this->load->helper('security');
        $postData = xss_clean($_POST);

	 $Data['regFormId']=$postData['regData'];
	 $Data['mobile']=$Data['userData']['mobile'] = $postData['mobile'];
	 if($postData['isStudyAboad']){
	    $Data['defaultDisplay']=1;
	    $this->load->view('common/OTP/abroadOtpLayer',$Data);
	 }else{
	 $Data['showVerificationLayer']=$postData['showVerificationLayer'];
	 $Data['changeMobileLink'] = $postData['changeMobileLink'];
	
	    $this->load->view('common/OTP/userVerificationLayer',$Data);
	 }
    }
    
    /**
     * Function to Show forgot password layer
     */ 
    public function showForgotPasswordLayer()
    {
        $data = array('defaultView' => 'forgotPassword');
        $this->load->view('login',$data);    
    }
    
    /**
     * Test Function
     */ 
    function test()
    {
        $this->load->view('main');
    }
    
    /**
     * Function to Show reset password layer
     *
     * @param String $id
     * @param String $useremail
     * @param String $registration_context
     */
    public function showResetPasswordLayer($id,$useremail,$registration_context) {
	$data['uname'] = $id;
	$data['useremail'] = base64_decode($useremail);
	$data['registration_context'] = $registration_context;
    	$this->load->view('resetPassword',$data);
    }
    
    /**
     * Function to Show reset password thanks layer
     *
     * @param string $context
     */
    public function showResetPasswordThanksLayer ($context) {
    	$data['context'] = $context;
    	$this->load->view('resetPasswordThanks',$data);  		   							
    }
    
    /**
     * Function to Show Existing User layer
     *
     * @param interger $userId
     * @param String $useremail
     * @param String $registration_context
     */
    public function showExisitingUserLayer($userId, $userEmailId, $registrationContext)
    {
	$data['userId'] = $userId;
	$data['userEmailId'] = $userEmailId;
	$data['registrationContext'] = $registrationContext;
    	$this->load->view('existingUserLayer',$data);
    }
    
    public function showUserPreferenceLayer() {
		$this->load->helper('security');
        $data = xss_clean($_POST);
		$this->load->view('userPreferenceLayer',$data);
    }
    
    /**
     * Function to get User Profile Information
     *
     * @param String $userId
     * @param String $emailId
     * @param String $context
     *
     * @return array $userInfoArray
     */
    function getUserProfileInfo($userId = '',$emailId = '',$context='') {
        $userRepository = \user\Builders\UserBuilder::getUserRepository();
        $usermodel = $this->load->model('user/usermodel');
        
        $userInfoArray = array();
        
        if(empty($userId) && !empty($emailId)) {
            $userId = $usermodel->getUserIdByEmail($emailId);
        }
        
	    $userId = intval($userId);
	
        if(empty($userId)) {
	     return $userInfoArray;
        }else {
	       $userInfo = $userRepository->find($userId);
            if(empty($userInfo)){
                return $userInfoArray;
            }
            $userPreference = $userInfo->getPreference();
            $userEducation = $userInfo->getEducation();
            $userFlags = $userInfo->getFlags();
	   }
        
        //is LDB user
        $userInfoArray['isLDBUser'] = $userFlags->getIsLDBUser();
        
        
        //get basic user info
	    $userInfoArray['userId'] = $userId;
        $userInfoArray['email'] = $userInfo->getEmail();
	    $userInfoArray['name'] = $userInfo->getFirstName();
        $userInfoArray['firstName'] = $userInfo->getFirstName();
        $userInfoArray['lastName'] = $userInfo->getLastName();
        $userInfoArray['mobile'] = $userInfo->getMobile();
        $userInfoArray['country'] = $userInfo->getCountry();
        $userInfoArray['isdCode'] = $userInfo->getISDCode().'-'.$userInfo->getCountry();
        
	
	   //get usergroup
	   $userInfoArray['usergroup'] = $userInfo->getUserGroup();
        
	
    	//return for short user
    	if(!is_object($userPreference)) {
    	    return $userInfoArray;
    	}
	
	
        //get user desired course
    	$extraFlag = $userPreference->getExtraFlag();
    	$userInfoArray['extraFlag'] = $extraFlag;
            
        if($extraFlag == 'testprep') {
            $testPrepSpecializationPreferences = $userPreference->getTestPrepSpecializationPreferences();
            if(is_object($testPrepSpecializationPreferences[0])){
                $userInfoArray['desiredCourse'] = $testPrepSpecializationPreferences[0]->getSpecializationId();
            }
        }
        else if($extraFlag == 'studyabroad') {
            global $studyAbroadPopularCourses;
            $specializationId = $userPreference->getDesiredCourse();
            
            if(!empty($specializationId) && array_key_exists($specializationId, $studyAbroadPopularCourses)) {
                $userInfoArray['desiredCourse'] = $specializationId;
            }
            else {
                $desiredCourseForStudyAbroad = $usermodel->getDesiredCourseForStudyAbroad($specializationId);
                $userInfoArray['fieldOfInterest'] = $desiredCourseForStudyAbroad['fieldOfInterest'];
                $userInfoArray['desiredGraduationLevel'] = $desiredCourseForStudyAbroad['desiredGraduationLevel'];
            }
        }
        else {
            $userInfoArray['desiredCourse'] = $userPreference->getDesiredCourse();
        }
        
		//We need this data in case of userProfileUpdate studyAbroad
		if($context == 'abroadUserSetting'){
			$userLocationPreferences = $userInfo->getLocationPreferences();
			foreach($userLocationPreferences as $location) {
				$countryId = $location->getCountryId();
				if($countryId != 0) {
						$userInfoArray['destinationCountry'][] = $countryId;
					}
			}
		}	
        
        //get user education info
        $userInfoArray['exams'] = array();
        foreach($userEducation as $education) {
            $level = $education->getLevel();
            $marks = $education->getMarks();
            $name = $education->getName();
            $completionDate = $education->getCourseCompletionDate();
            
            if(!empty($completionDate) && gettype($completionDate) == 'object') {
                $year = intval($completionDate->format('Y'));
            }
            
            if($level == 'UG') {
                $userInfoArray['graduationCompletionYear'] = $year;
            }
            else if($level == 'Competitive exam') {
                $userInfoArray['exams'][] = $name;
                $userInfoArray[$name.'_score'] = $marks;
            }
            else if($level == '12') {
                $userInfoArray['xiiYear'] = $year;
            }
        }
        
        /* Add fields for SA Shiksha Apply Form*/
        if($context == 'SAapply'){
             foreach($userEducation as $education) {
                if($education->getName() == '10'){
                    $userInfoArray['tenthBoard'] = $education->getBoard();
                    $userInfoArray['CurrentStream'] = $education->getStream();
                    $userInfoArray['tenthMarks'] = $education->getMarks();
                }else if($education->getName() == 'Graduation'){
                    $userInfoArray['workExperience'] = $userInfo->getExperience();
                    $userInfoArray['graduationStream'] = $education->getStream();
                }   
            }
        }

        //get residence info
    	$city = $userInfo->getCity();
    	if($city > 0) {
    	    $this->load->builder('LocationBuilder','location');
    	    $locationBuilder = new \LocationBuilder;
    	    $locationRepository = $locationBuilder->getLocationRepository();
    	    $localities = $locationRepository->getLocalitiesByCity($city);
    	    
    	    $cityObj = $locationRepository->findCity($city);
    	    if(!$cityObj->isVirtualCity()) {	    
    		if(count($localities) == 0) {
    		    $userInfoArray['residenceCity'] = $city;
    		    $userInfoArray['residenceCityLocality'] = $city;
    		    $userInfoArray['residenceLocality'] = true;
    		}
    		else {
    		    $userInfoArray['residenceCity'] = $city;
    		    
    		    $locality =  $userInfo->getLocality();
    		    if($locality > 0) {
    			$userInfoArray['residenceCityLocality'] = $city;
    			$userInfoArray['residenceLocality'] = $locality;
    		    }
    		}
    	    }
    	    else{
    		    $userInfoArray['residenceCity'] = $city;
    		    $userInfoArray['residenceCityLocality'] = $city;
    		    $userInfoArray['residenceLocality'] = true;
    		
    	    }
    	}
            
            //get pref submit time
            $prefSubmitTime = $userPreference->getSubmitDate();
    	if(is_object($prefSubmitTime) && !empty($prefSubmitTime)) {
    	    $userInfoArray['prefSubmitTime'] = $prefSubmitTime->format('Y-m-d H:i:s');
    	}
        
        //get pref submit time
        $prefSubmitTime = $userPreference->getSubmitDate();
	if(is_object($prefSubmitTime) && !empty($prefSubmitTime)) {
	    $userInfoArray['prefSubmitTime'] = $prefSubmitTime->format('Y-m-d H:i:s');
	}
    
        unset($userInfo);
        unset($userId);
        return $userInfoArray;
    }
    
    /**
     * Function to check if user is Full Registered User
     *
     * @param array $userdata
     * @param array $fields
     * @param boolean $ignoreSubmitDate
     *
     * @return boolean
     */
    function isFullRegisteredUser($userData, $fields, $ignoreSubmitDate = false) {
        
        //Skipping check of residentcity/locality for international user
        if($userInfoArray['country'] != '2'){
               if(isset($fields['residenceCity'])){
                unset($fields['residenceCity']);
               }else if(isset($fields['residenceCityLocality'])){
                unset($fields['residenceCityLocality']);
                unset($fields['residenceLocality']);
                }   
            }

        foreach($fields as $key => $field){
            if(!$userData[$key] && $key!='fieldOfInterest' && $field->isMandatory()){
                return false;
            }
        }
	
	if($ignoreSubmitDate == true) {
	    return true;
	}
	
	$currentTime = date('Y-m-d H:i:s');
	$prefSubmitTime = $userData['prefSubmitTime'];
	$timeDiff = (strtotime($currentTime) - strtotime($prefSubmitTime)) / 2592000;
	
	if($timeDiff < 1) {
	    return true;
	}
	else {
	    return false;
	}
    }
    
    /**
     * Function to check if the user is 
     *
     * @param integer $courseId
     * @param integer $userId
     * @param boolean $ignoreSubmitDate
     *
     * @return boolean $isFullRegisteredUser
     *
     */
    
    public function isValidResponseUser($courseId, $userId, $ignoreSubmitDate = false)
    {

    	$isFullRegisteredUser = null;
    	if($this->input->is_ajax_request()) {
    	    $courseId = $this->input->post('courseId');
    	    
    	    $userId = $this->input->post('userId');
    	    
    	    $ignoreSubmitDate = $this->input->post('ignoreSubmitDate');
    	    if(empty($ignoreSubmitDate)) {
    		$ignoreSubmitDate = false;
    	    }
    	}
    	
    	if(empty($userId)) {
    	    $loggedInUserData = $this->getLoggedInUserData();
    	    if($loggedInUserData['userId'] > 0) {
    		$userId = $loggedInUserData['userId'];
    	    }
    	    else {
    		$userId = 0;
    	    }
    	}
    	
    	if($courseId == '' || $courseId == 0 || !($userId > 0)) {
    	    if($this->input->is_ajax_request()) {
    		echo $isFullRegisteredUser;
    		return;
    	    }
    	    else {
    		return $isFullRegisteredUser;
    	    }
    	}
    	
    	$national_course_lib = $this->load->library('listing/NationalCourseLib');
    	$dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses(array($courseId));

    	$fieldOfInterest = $dominantDesiredCourseData[$courseId]['categoryId'];
    	$desiredCourseId = $dominantDesiredCourseData[$courseId]['desiredCourse'];
    	$context = 'registerResponse';
    	
        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('desiredCourseId' => $desiredCourseId,'context' => $context,'fieldOfInterest' => $fieldOfInterest));
        
        $fields = $registrationForm->getFields();
            
    	//get loggedin userdata
            
        if($userId > 0) {
    	    if($_POST['shortlistCron'] == 1){
    		$userInfoArray = $this->getUserProfileInfo($userId,'','shortlistCron');
    	    }else if ($_POST['preRegistrationCron'] == 1){
                $userInfoArray = $this->getUserProfileInfo($userId,'','preRegistrationCron');
            }
    	    else{
    		$userInfoArray = $this->getUserProfileInfo($userId);
    	    }

    	    $isFullRegisteredUser = $this->isFullRegisteredUser($userInfoArray, $fields, $ignoreSubmitDate);
    	}
    	unset($fields);
    	if($this->input->is_ajax_request()) {
    	    echo $isFullRegisteredUser;
    	    return;
    	}
    	else {
    	    return $isFullRegisteredUser;
    	}
    }
	
    /**
     * Show register layer for shortlist
     */ 
    public function showShortlistRegistrationLayer() {
		$formCustomData = array();
        $userData = $this->getLoggedInUserData();
        $data['userData'] = $userData;
        $data['layerTitle'] = trim($this->input->post('layerTitle'));
        $data['layerHeading'] = trim($this->input->post('layerHeading'));
		$data['submitText'] = trim($this->input->post('submitText'));
        $data['tracking_keyid'] = trim($this->input->post('tracking_keyid'));
        
		$courseId = intval($this->input->post('courseId'));
		if($courseId > 0) {
			$this->load->library('listing/NationalCourseLib');
			$nationalCourseLib = new NationalCourseLib();
			$courseInfo = $nationalCourseLib->getDominantDesiredCourseForClientCourses(array($courseId));
			$formCustomData['preSelectedCategoryId'] = $courseInfo[$courseId]['categoryId'];
			$formCustomData['preSelectedDesiredCourse'] = $courseInfo[$courseId]['desiredCourse'];
			
			$formCustomData['preSelectedDesiredCourse'] = $courseInfo[$courseId]['desiredCourse'];
		}
        
        
        if($referer = trim($this->input->post('referer'))) {
            $formCustomData['customReferer'] = $referer;
        } else {
			$formCustomData['customReferer'] = $_SERVER['HTTP_REFERER'];
			if(empty($formCustomData['customReferer'])) {
				$formCustomData['customReferer'] = 'www.shiksha.com';
			}
		}
		
		if($registrationSource = trim($this->input->post('source'))) {
            $formCustomData['customRegistrationSource'] = $registrationSource;
			$formCustomData['customReferer'] = $formCustomData['customReferer'].'#'.$registrationSource;
        }
		
		$data['regFormId'] = random_string('alnum', 6);
		$formCustomData['regFormId'] = $data['regFormId'];
        $data['formCustomData'] = $formCustomData;
        $this->load->view('shortlistRegister',$data);
    }
	
	/**
	 * FUnction to load MMP Form on Popup
	 */
	public function loadmmponpopup() {
		
		$data = array();		
		$data['isUserLoggedIn'] = $this->input->post('isUserLoggedIn');
		$data['displayName'] = $this->input->post('displayName');
		$data['user_id'] = $this->input->post('user_id');
		$data['showpopup'] = $this->input->post('showpopup');
		
		$mmp_display_on_page = $this->input->post('mmp_display_on_page');
		$mmp_form_heading = $this->input->post('mmp_form_heading');
		$mmp_id = (int)$this->input->post('mmp_id');

        if($mmp_id <= 0) {
            return;
        }

		if(empty($mmp_form_heading)) {
			if($mmp_display_on_page == 'newmmpcourse' || $mmp_display_on_page == 'newmmpinstitute') {
				$mmp_form_heading = 'Register & Download Brochure of top MBA colleges in India in your Inbox';
			} elseif($mmp_display_on_page == 'newmmparticle') {
				$mmp_form_heading = 'Register & stay updated with all important notifications this MBA season with Shiksha';
			} elseif($mmp_display_on_page == 'newmmpranking') {
				$mmp_form_heading = 'Register & Get the list of top 100 MBA colleges in India in your Inbox';
			} elseif($mmp_display_on_page == 'newmmpcategory') {
				$mmp_form_heading = 'There are 6 steps to choose the right MBA college. Register & get started!';
			} elseif($mmp_display_on_page == 'newmmpexam') {
				$mmp_form_heading = 'Register and stay updated with all that is happening around '.$this->input->post('exam_name');
			}
		}
		$data['mmp_form_heading'] = $mmp_form_heading;
		
		$regFormId = random_string('alnum', 6);
		$customFormData = array();
		$customFormData['regFormId'] = $regFormId;
		$customFormData['load_ga'] = false;
		$customFormData['loadmmponpopup'] = 'YES';
		$formHTML = Modules::run('registration/Forms/MMP', $mmp_id,0, $customFormData);

		$data['formHTML'] = $formHTML;
		$data['regFormId'] = $regFormId;
		
		$this->load->view('mmpform', $data);
		
	}

    public function loadANALeadForm(){
        $context = $this->input->post('context',true);
        $trackingPageKeyId = $this->input->post('trackingPageKeyId',true);
    	if(isset($trackingPageKeyId)){
	     	$customFormData['trackingPageKeyId']=$trackingPageKeyId;
			$this->LDB(NULL,$context, $customFormData);
		}else{
        	$this->LDB(NULL,$context, array());
		}
    }

     /*
     * Function to check user for abroad forms
     * @params : $userId => User Id (tuser),courseId,coursegroup,reference to an array
     * if it contains a value returnDataFlag set to 1, all information is sent back in this array
     */
    public function isValidAbroadUser($userId, $courseId, $courseGroup='studyAbroadRevamped', $additionalData = array()){
        // Fetch desired course and preferred locations to identify the user
        if($additionalData['returnDataFlag'] == 1){
            $workExperienceRequired = 1;
        }
        $userInfoArray = $this->getAbroadUserInfo($userId, $context,$workExperienceRequired);
        if(!$userInfoArray['desiredCourse'] || !$userInfoArray['isLocation'] ){
            if($additionalData['returnDataFlag'] == 1){
                $additionalData['userInfoArray'] = $userInfoArray;
                $additionalData['isValidUser'] = false;
                return $additionalData;
            }else{
                return false;
            }
        }else{
			$this->load->model('registration/registrationmodel');
			$regModel = new registrationmodel;
			if(!empty($courseId)){
				$courseLevel = $regModel->getCourseLevelByClientCourseId($courseId);
			}
			else if(!empty ($userInfoArray['desiredCourse'])){
				$courseLevel = $regModel->getCourseLevelByLDBCourseId($userInfoArray['desiredCourse']);
			}
			// masters,phd =>PG
			// bachelors =>UG
			if($courseLevel == "UG")
			{
				$isValidSAapplyUser = (!empty($userInfoArray['tenthBoard']) && $courseLevel == 'UG');
			}else{
			 	$isValidSAapplyUser = (!empty($userInfoArray['graduationStream']) && $courseLevel == 'PG');
			}
			if($additionalData['returnDataFlag'] === 1){
				$additionalData['userInfoArray'] = $userInfoArray;
				$additionalData['userInfoArray']['courseLevel'] = $courseLevel;
			}
			if($isValidSAapplyUser){
                if($additionalData['returnDataFlag'] == 1){
                    $additionalData['isValidUser'] = true;
                    return $additionalData;
                }else{
                    return true;    
                }
				
			}else{
                if($additionalData['returnDataFlag'] == 1){
                    $additionalData['isValidUser'] = false;
                    return $additionalData;
                }else{
                    return false;
                }
			}
        }
    }
    
    /*
     * Function to fetch desiredCourse and preferred location (destination countries) of abroad user
     * @params : $userId => User Id (tuser)
     */
    function getAbroadUserInfo($userId, $context='studyAbroadRevamped',$workExperienceRequired =0){
        
        $data = array();

        //setting default values
        $data['desiredCourse'] = false;
        $data['isLocation'] = 0;

        //get the user model
        $this->load->model('user/usermodel');
        $userModel = new UserModel;
        
        // load user object using userid
        if($user = $userModel->getUserById($userId))
        {
            // object for tUserPref data
            $pref = $user->getPreference();
            if(is_object($pref)){
                $desiredCourse = $pref->getDesiredCourse();
            }else{
                $desiredCourse = null;
                return $data;
            }

            $loc = $user->getLocationPreferences();
            $isLocation = count($loc);   
            
            /* Get Fields value for SA Shiksha Apply*/
            $userAdditionalInfo = $user->getUserAdditionalInfo();
            if($userEducation = $user->getEducation()){
                foreach($userEducation as $education) {
                    if($education->getName() == '10'){
                        if(is_object($userAdditionalInfo)){
							$data['CurrentClass'] = $userAdditionalInfo->getCurrentClass();
							$data['CurrentSchoolName'] = $userAdditionalInfo->getCurrentSchool();
                        }
                        $data['EduName'] = '10';
                        $data['tenthBoard'] = $education->getBoard();
                        $data['CurrentSubjects'] = $education->getSubjects();
                        $data['tenthMarks'] = $education->getMarks();
                    }else if($education->getName() == 'Graduation'){
                        $data['EduName'] = 'Graduation';
                        $data['workExperience'] = $user->getExperience();
                        $data['graduationStream'] = $education->getSubjects();
                        $data['graduationPercentage'] = $education->getMarks();
                    }   
                }
            }
			if($workExperienceRequired == 1){
				$data['workExperience'] = $user->getExperience();    
			}
        }
        
        
        $data['desiredCourse'] = $desiredCourse;
        $data['isLocation'] = $isLocation;
        return $data;
    }

    /*
     * Function to provide fields of SA Apply based on the ldbCourse (For Ajax request)
     * @params : $ldbCourseId (POST)
     * @returns : Values of SA Apply fields
     */
    function loadSAFieldsByLDBCourse($dataArray=array()){
		if(empty($dataArray)){
			$ldbCourseId = isset($_POST['ldbCourseId']) ? $this->input->post('ldbCourseId', true) : NULL;
			$courseLevel = isset($_POST['courseLevel']) ? $this->input->post('courseLevel', true) : NULL;
			$context = isset($_POST['context']) ? $this->input->post('context', true) : NULL;
			$regFormId = isset($_POST['regFormId']) ? $this->input->post('regFormId', true) : NULL;
            $courseGroup = isset($_POST['courseGroup']) ? $this->input->post('courseGroup', true) : 'SAapply';
		}else{
			$ldbCourseId = $dataArray['ldbCourseId'];
			$courseLevel = $dataArray['courseLevel'];
			$context = $dataArray['context'];
			$regFormId = $dataArray['regFormId'];
            $courseGroup = isset($dataArray['courseGroup']) ? $dataArray['courseGroup'] : 'SAapply';
		}
        
        
        if( ($ldbCourseId == NULL && $courseLevel == NULL) || $regFormId == NULL){
            return json_encode(array('0'));
        }

         /* Level to SA shiksha Apply fields Mapping */
        $levelMapping = array(
            'UG' => array('tenthBoard', 'tenthmarks', 'CurrentSubjects','currentClass','currentSchool'),
            'PG' => array('graduationStream', 'graduationMarks','workExperience')
        );

        $this->load->model('registration/registrationmodel');
        $regModel = new registrationmodel;

        /* $courseLevel = UG/PG */
        if($courseLevel == NULL){
            $courseLevel = $regModel->getCourseLevelByLDBCourseId($ldbCourseId);
        }

        $registrationForm = new \registration\libraries\Forms\LDB($courseGroup);

        $fields = array();
        foreach($levelMapping[$courseLevel] as $index=>$value){
            $fields[$courseLevel][$value] = $registrationForm->getField($value);
        }

        $registrationHelper = new \registration\libraries\RegistrationHelper($fields[$courseLevel],$regFormId);
        $data['registrationHelper'] = $registrationHelper;
        
        $data['regFormId'] = $regFormId; 
        $data['fieldObj'] = $fields;
        echo $this->load->view('registration/fields/LDB/variable/'.$context.'EducationFields', $data);    
        return;
        
    }   

    /*
    * Function to get tenth marks according to the board
    * @input : $tenthBoard => Tenth Board
    */
    function getTenthMarksAccToBoard($coursegroup = 'SAapply'){
        
        $tenthBoard = isset($_POST['tenthBoard']) ? $this->input->post('tenthBoard', true) : NULL;

        if($tenthBoard == NULL){
            echo json_encode(array('0'=>'0'));
            return false;
        }

        $registrationForm = new \registration\libraries\Forms\LDB($coursegroup);

        $values = $registrationForm->getField('tenthmarks')->getValues();

        echo json_encode($values[$tenthBoard]);
        return true;
    } 

    function getEducationalFields(){
        $level = $_POST['level'] ? $this->input->post('level', true) : NULL;

        if($level == NULL){
            return;
        }
        $data['level'] = $level;
        echo $this->load->view('registration/fields/LDB/variable/unifiedProfileEducationFields',$data);
    }

    function changeStudyAbroadForm(){

        $regFormId = isset($_POST['regFormId'])? $this->input->post('regFormId', true) : NULL;
        $ISDCode = isset($_POST['isdCode'])? $this->input->post('isdCode', true) : NULL;
        $context = isset($_POST['context'])? $this->input->post('context', true) : NULL;
        $mmpFormId = isset($_POST['mmpFormId'])? $this->input->post('mmpFormId', true) : 0;
        $display_on_page = isset($_POST['display_on_page'])? $this->input->post('display_on_page', true) : 0;

        if($mmpFormId > 0){
            if($ISDCode != INDIA_ISD_CODE){
               $data['fields']['emptyResidenceCity'] = "emptyResidenceCity";
               return;
            }else{
                global $mmp_display_on_page_array;

                $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('MMP',array('mmpFormId' => $mmpFormId,'desiredCourseId' => $desiredCourseId));
                if($context == 'mobileRegistrationAbroad'){
                    $context = 'New_MMP_Mobile';
                }else{
                    if(!$display_on_page){
                        $context = 'Old_MMP';
                    }else{
                        $context = 'New_MMP';
                    }
                }

                $data['fields']['residenceCity'] = $registrationForm->getField('residenceCity');
                $registrationHelper = new \registration\libraries\RegistrationHelper($data['fields'],$regFormId);
                $data['registrationHelper'] = $registrationHelper;
                $data['regFormId'] = $regFormId;

            }
        }else{
            // global INDIA_ISD_CODE;
            if($ISDCode != INDIA_ISD_CODE){
               $data['fields']['emptyResidenceCity'] = "emptyResidenceCity";
            }else{

                $registrationForm = new \registration\libraries\Forms\LDB('studyAbroadRevamped');
                $data['fields']['residenceCity'] = $registrationForm->getField('residenceCity');
                $registrationHelper = new \registration\libraries\RegistrationHelper($data['fields'],$regFormId);
                $data['registrationHelper'] = $registrationHelper;
                $data['regFormId'] = $regFormId;
            }
        }

       switch($context){
            case 'mobileRegistrationAbroad':
                echo $this->load->view('registration/fields/LDB/variable/onchangeFieldsAbroadMobile', $data);
            break;

            case 'mobileRmcPage':
                echo $this->load->view('registration/fields/LDB/variable/onchangeFieldsRMCMobile',$data);           
            break;
           
           case 'abroadUserSetting':
                echo $this->load->view('registration/fields/LDB/variable/onchangeAbroadSetting',$data);           
            break;

            case 'New_MMP_Mobile':
                echo $this->load->view('registration/fields/MMP/variable/onchangeMobileField',$data);           
            break;

            case 'New_MMP':
                echo $this->load->view('registration/fields/NewMMP/variable/changeFieldsNewMMP',$data);           
            break;

            case 'Old_MMP':
                echo $this->load->view('registration/fields/NewMMP/variable/changeFieldsOldMMP',$data);           
            break;
            
           default:
                echo $this->load->view('registration/fields/LDB/variable/onchangeFieldsAbroad',$data);           
           break;

       }
  
    }

     public function openNewRegistrationLayerForMobile(){
        $data = array();
        $returnData = array();

        $data['customFields'] = $this->input->post('customFields', true);
        $data['registrationTitle'] = $this->input->post('registrationTitle', true);
        $data['callbackFunction'] = $this->input->post('callbackFunction', true);
        $data['callbackFunctionParams'] = $this->input->post('callbackFunctionParams', true);
        $data['registrationIdentifier'] = $this->input->post('registrationIdentifier', true);

        if(empty($data['registrationTitle'])){
            $data['registrationTitle'] = 'Join Shiksha Now';
        }
        $returnData['registrationHTML'] = $this->load->view('mobileRegistrationNational',$data, true);
        $returnData['jsPath'] = '/public/mobile5/js/'.getJSWithVersion("userRegistrationMobile","nationalMobile");
        echo json_encode($returnData);
        return;
    }

    public function mobileRegistrationLogin() {
        $data = array();
        $regFormId = $this->input->post('regFormId', true);
        $userEmailId = $this->input->post('userEmailId', true);
        $data['regFormId'] = $regFormId;
        $data['userEmailId'] = $userEmailId;
        $this->load->view('mobileRegistrationLogin',$data);
    }    

    public function showForgotPassword() {
        $data = array();
        $regFormId = $this->input->post('regFormId');
        
        $data['regFormId'] = $regFormId;
        $this->load->view('mobileForgotPassword',$data);
    }

    function mobileOTPVerification(){
        $data = array();
        $data['regFormId']= $this->input->post('regFormId');
        $data['mobile']= $this->input->post('mobile');
        
        $this->load->view('common/OTP/OTPVerificationForLayeredForm',$data);        
    }

    /*
     * Function to set custom params in form data format(prefilled data)
     * @Params: $customFormData array in the format 
     *              $customFormData = array('key' => array('value'=>'', 'hidden'=>'', 'disabled'=>''))
     * @Params: $userInfoArray is an array containing logged-in user data
     * @Return: $formData => array('key'=>'value')
     */
    private function _formatPrefilledData($customFormData, $userInfoArray){
        $formData = array();
        foreach($customFormData as $key=>$value){
            if(empty($userInfoArray[$key]) || $key == 'isdCode'){
                $formData[$key] = $value['value'];
            }else{
                $formData[$key] = $userInfoArray[$key];
            }
        }

        return $formData;

    }
    
}
