<?php

class MarketingFormProcessor extends MX_Controller
{
    private $model;
    
    function __construct()
    {
        $this->load->model('mailer/marketingformmailermodel');
        $this->model = new MarketingFormMailerModel;
    }
    
    function processForm()
    {
        /**
         * Fetch mailer form id
         */ 
        $mfid = $this->input->post('mfid');
        $email = $this->input->post('email');
        $mobile = $this->input->post('mobile');
        
        if(!$mfid || !$email || !$mobile) {
            header('Location: '.SHIKSHA_HOME);
            exit();
        }
        
        
        $this->_doRegistration();
    }
    
    function _doRegistration()
    {
	setcookie('ci_mobile','desktop',0,'/',COOKIEDOMAIN);

        $logId = $this->model->logFormData('registration',$_POST,$this->input->post('email'));
        $uniqId = $this->model->getUniqueLogId($logId);
        
        $redirection = trim(SHIKSHA_HOME,'/').'/shiksha/index?mfmrg='.$uniqId;
        $registerFailRedirection = $redirection;
        if($this->input->post('destinationCountry')) {
            $registerFailRedirection = trim(SHIKSHA_STUDYABROAD_HOME,'/').'/studyAbroadHome/studyAbroadHome/homepage?mfmrg='.$uniqId;
        }
        
        if(!$this->_validateForm()) {
            $this->model->updateLogStatus($logId,'failed');
            header("Location: ".$registerFailRedirection);
            exit();
        }
        
        if(!$this->input->post('desiredCourse') && !$this->input->post('desiredGraduationLevel')) {
            $_POST['registrationType'] = 'short';
        }
        
        if($_POST['destinationCountry']) {
            $_POST['isStudyAbroad'] = 'yes';
        }
        
        $response = Modules::run('registration/Registration/validateRegistrationData');
        
        if($response == 'USER_ALREADY_EXISTS') {
            $this->model->updateLogStatus($logId,'user_exists');
        }
        else if($response == 'FAIL') {
            $this->model->updateLogStatus($logId,'failed');
            $redirection = $registerFailRedirection;
        }
        else if($response == 'SUCCESS') {
            $this->model->updateLogStatus($logId,'otp');
        }
        
        header("Location: ".$redirection);
        exit();
    }
    
    function _validateForm()
    {
        /**
         * Validate email
         */ 
	$regex = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
	if (!preg_match($regex, $this->input->post('email'))) {
            return false;	
	}
 
        /**
         * Validate mobile
         */
        if(array_key_exists('mobile',$_POST)) {
            $mobile = $this->input->post('mobile');
            if(strlen($mobile) != 10) {
                return false;
            }
            if(!ctype_digit($mobile)) {
                return false;
            }
            if(!in_array($mobile[0],array('7','8','9'))) {
                return false;
            }
        }
        
        /**
         * Validate first name
         */ 
        if(array_key_exists('firstName',$_POST)) {
            
            $_POST['firstName'] = preg_replace('[(\n)\r\t\"\']',' ',$_POST['firstName']);
            $_POST['firstName'] = preg_replace('[^\x20-\x7E]','',$_POST['firstName']);
                        
            if(!$this->_validateName($_POST['firstName'])) {
                return false;
            }
        }
        
        /**
         * Validate last name
         */
        if(array_key_exists('lastName',$_POST)) {
            
            $_POST['lastName'] = preg_replace('[(\n)\r\t\"\']',' ',$_POST['lastName']);
            $_POST['lastName'] = preg_replace('[^\x20-\x7E]','',$_POST['lastName']);
            
            if(!$this->_validateName($_POST['lastName'])) {
                return false;
            }
        }
        
        return true;
    }
    
    function _validateName($name)
    {
        $defValues = array('name','your name','first name','your first name','last name','your last name');
        
        if($name == '' || in_array(strtolower($name),$defValues)) {
            return false;
        }
        
        if(!preg_match("/^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/",$name)) {
            return false;
        }
        
        return true;
    }
    
    function processLandingPageForm()
    {
        $loggedInUser = $this->checkUserValidation();
        if(is_array($loggedInUser) && is_array($loggedInUser[0]) && $loggedInUser[0]['userid']) {
            list($email,) = explode('|',$loggedInUser[0]['cookiestr']);
            $this->doMISLogging($email);
        }
        else {
            /**
             * If user is not logged in
             * just log the data
             */
            $this->doMISLogging('');
        }
    }
    
    function doMISLogging($email)
    {
        if(!empty($_POST)) {
            $logId = $this->model->logFormData('mis',$_POST,$email);
            
    		$loggedInUser = $this->checkUserValidation();
    		if(is_array($loggedInUser) && is_array($loggedInUser[0]) && $loggedInUser[0]['userid']) {
    			$this->model->logMIS($loggedInUser[0]['userid'],$_POST);
    		}
    		else {
    			$this->model->logMIS(0,$_POST);
    		}
        }
		
        if($_POST['redirectURL'] != '') {
            /* if( (strpos($_POST['redirectURL'], SHIKSHA_HOME) === false) && (strpos($_POST['redirectURL'],SHIKSHA_ASK_HOME_URL) === false) && (strpos($_POST['redirectURL'],SHIKSHA_STUDYABROAD_HOME) === false) && (strpos($_POST['redirectURL'],ENTERPRISE_HOME) === false) ){
                header("Location: ".$_POST['redirectURL']);
            }
            else{
                header("Location: ".SHIKSHA_HOME,TRUE,301);
            } */
            header("Location: ".$_POST['redirectURL']);
            exit();
        }
        else {
            header("Location: ".SHIKSHA_HOME);
            exit();
        }
    }
}
