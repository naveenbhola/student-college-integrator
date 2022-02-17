<?php

class SecurityCheck extends MX_Controller
{
    function verify()
    {
        $this->load->library('BotDetector');
        $botDetector = new BotDetector();
        
        require_once APPPATH.'/third_party/recaptcha/autoload.php';
        
        $recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_SECRET_KEY);
        
        $captchaResponse = $this->input->post('g-recaptcha-response');
        $return = base64_decode($this->input->post('returnPage'));
        $sessionId = $this->input->post('sessionId');
        
        $resp = $recaptcha->verify($captchaResponse);
        if ($resp->isSuccess()) {
            //echo "Verified";
            /**
             * Validate the session
             */ 
            $botDetector->validateCaptcha($sessionId);
            header("Location: ".$return);
            exit();
        } 
        else {
            $errors = $resp->getErrorCodes();
            //_p($errors);
        }
    }
    
    function index()
    {
        $returnPageURL = $_REQUEST['return'];
        $sessionId = $_REQUEST['sessionId'];
        
        $data = array(
            'returnPageURL' => $returnPageURL,
            'sessionId' => $sessionId
        );

        if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')) {
            $this->load->view('securityCheckMobile', $data);
        }
        else{
            $this->load->view('securityCheck', $data);
        }
        
        
    }
}
