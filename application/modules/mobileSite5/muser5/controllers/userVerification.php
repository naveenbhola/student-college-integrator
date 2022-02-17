<?php
class userVerification extends MX_Controller
{
    static $returnurl = '/muser5/ODBVerification/getMisData';
    static $callUrl = 'www.valuecallz.com/utils/sendVoice.php?cid=804710';
    
    function _init() {
        $this->load->model('verificationmodel');
		$this->verificationmodel = new verificationmodel();
		$this->load->model('user/usermodel');
		$this->usermodel = new usermodel();
    }
    
    function index()
    {
	$this->load->view('odb');
    }
    
    function verifyUser() {
		
		if(!verifyCSRF()) { return false; }
		
        $this->_init();
       
        $email = $this->input->post('email');
        $mobile = $this->input->post('mobile');
        $verification = $this->input->post('verification');
	$userId = $this->usermodel->getUserIdByEmail($email);
	if($userId > 0) {
	    echo 'exists';
	    return;
	}
        
	$isMobileTest = $this->isTestUser($mobile); // verify mobile is valid or not
	
	$checkAttemp  = $this->verificationmodel->getAttempByUserEmail($email);
	
        if($verification == 'ODB' && $checkAttemp <= 5) {
	    $ODB = $this->verificationmodel->doesODBExist($email,$mobile);
	    
	    if($ODB === false) {
		$id = $this->verificationmodel->saveODB($email, $mobile);
	    } else {
		$id = $ODB['id'];
		$mobile = $ODB['mobile'];
	    }
	    $response = $this->callUser($mobile); // calling here
	    if($response){
		$exp_response = explode(",",$response);
		$call_id = $exp_response[0];
		$status_code = $exp_response[1];
		$this->verificationmodel->updatecallresponse($id, $call_id, $status_code);
		echo $status_code;
	    } else {
		echo 'no response';
	    }
	    return;
        }else{
	    echo 'test mobile number';
	}
    }
    
    function checkUserResponse() {
        $this->_init();
	$email = $this->input->post('email');
        $mobile = $this->input->post('mobile');
	
	$result = $this->verificationmodel->checkUserResponse($email, $mobile);
        
	if($result['status'] != 'sent') {
	    if(($result['response_code'] == '0x10') && ($result['response_captured'] == 1) && ($result['status'] == 'registrationVerified')) {
		$status = "verified";                
	    } else if(($result['response_code'] == '0x10') && ($result['response_captured'] != 1) && ($result['status'] == 'callVerified')){
	        $status = "not verified";  	
	    } else if($result['response_text'] == 'Switch Off' || $result['response_text'] == 'Invalid Number'){
		$status = "not verified";  
	    } else {
		$status = "retry"; 
	    }
	} else {
		$status = "response not received";
	}
	echo $status;return;
    }
    
    function callUser($mobile) {
	$returnurl = SHIKSHA_HOME.self::$returnurl;
	$url = self::$callUrl."&tonumber=".$mobile."&returnurl=".$returnurl;
	error_log('==userInfo=='.'url==='.$url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,30); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $output = curl_exec($ch);
        curl_close($ch);      
	return $output;
    }
    
    function isTestUser($mobile)
    {  
        require FCPATH.'globalconfig/testUserConfig.php';
        if($mobile) {
            if(is_array($testUserMobileNumbers) && in_array($mobile,$testUserMobileNumbers)){
                return 1;
            }
        }
        return 0;
    }
    
    function getExistUser(){
	$this->_init();
        $email = $this->input->post('email');
	$userId = $this->usermodel->getUserIdByEmail($email);
	if($userId > 0) {
	    echo 'exists';return;
	}
    }
}