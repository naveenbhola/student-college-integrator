<?php
class userVerification extends MX_Controller {
	
	private $email;
	private $mobile;
	private $completeISDCode;
	private $verification;
	private $isStudyAbroad;
	private $isStudyabroadReturning;
	private $chunkSize = 100;
	private $reasonCodeDescription = array('000' => 'Delivered', '001' => 'Invalid Number', '002' => 'Absent Subscriber', '003' => 'Memory Capacity Exceeded', '004' => 'Mobile Equipment Error', '005' => 'Network Error', '006' => 'Barring', '007' => 'Invalid Sender ID', '008' => 'Dropped', '009' => 'NDNC Failed', '100' => 'Misc. Error');

	function _init() {
		$this->load->model ( 'verificationmodel' );
		$this->verificationmodel = new verificationmodel();
		$this->load->model ( 'user/usermodel' );
		$this->usermodel = new usermodel ();
	}

	private function _extractPostInputs(){
		$this->email           = isset($_POST['email'])? $this->input->post ('email', true) : '0';
		$this->mobile          = isset($_POST['mobile'])? $this->input->post ('mobile', true) : '0';
		$this->completeISDCode = isset($_POST['isdCode'])? $this->input->post ('isdCode', true) : '91-2';
		$this->verification    = isset($_POST['verification'])? $this->input->post ('verification', true) : 'OTP';
		$this->isStudyAbroad   = $this->input->post ( 'isStudyAbroad' );
		$this->regFormId       = isset($_POST['regFormId'])? $this->input->post('regFormId', true) : '0';
		$this->trackingKeyId   = isset($_POST['trackingKeyId'])? $this->input->post('trackingKeyId', true) : '0';
		$this->isResend        = isset($_POST['isResend'])? $this->input->post('isResend', true) : '0';
		$this->isChangeNumber  = isset($_POST['isChangeNumber'])? $this->input->post('isChangeNumber', true) : '0';
	}

	private function _validateUserInput(){
		if(empty($this->email) || empty($this->mobile) || empty($this->completeISDCode)){
			return false;
		}

		return (validateEmailMobile('mobile', $this->mobile, $this->completeISDCode) && validateEmailMobile('email', $this->email));
	}


	private function _extractInputs($returningUserCall = array()){
		$isStudyabroadReturning = 0;
		if(!empty($returningUserCall)){
			$this->email                  = isset($returningUserCall['email'])? $returningUserCall['email']:'0';
			$this->mobile                 = isset($returningUserCall['mobile'])? $returningUserCall['mobile']:'0';
			$this->completeISDCode        = isset($returningUserCall['isdCode'])? $returningUserCall['isdCode']:'91-2';
			$this->verification           = isset($returningUserCall['verification'])? $returningUserCall['verification']:'OTP';
			$this->isStudyAbroad          = $returningUserCall['isStudyAbroad'];
			$this->isStudyabroadReturning = $returningUserCall['isStudyabroadReturning'];
			$this->trackingKeyId   		  = isset($returningUserCall['trackingKeyId'])? $returningUserCall['trackingKeyId'] : '0';
			$this->isResend       		  = isset($returningUserCall['isResend'])? $returningUserCall['isResend'] : '0';
			$this->isChangeNumber         = isset($returningUserCall['isChangeNumber'])? $returningUserCall['isChangeNumber'] : '0';
		}else{
		 	$this->_extractPostInputs();	   
		 	$this->isStudyabroadReturning = false;
		}
	}
	
	// need to validate email, mobile, isdCode
	function verifyUser($returningUserCall = array(),$isPWACall=false) {

		if(!$isPWACall){
			if(!verifyCSRF()) { return false; }
		}
		$this->_init ();

		$this->_extractInputs($returningUserCall);
		$trackingParams                   = array();
		$trackingParams['regFormId']      = $this->regFormId;
		$trackingParams['isResend']       = $this->isResend;
		$trackingParams['isChangeNumber'] = $this->isChangeNumber;
		$trackingParams['trackingKeyId']  = $this->trackingKeyId;
		
		$showOnlyOTP = $this->input->post('showOnlyOTP',true);
		$isNewUser   = $this->input->post('isNewUser',true);;

		if($showOnlyOTP == 'yes' && (empty($this->email) || empty($this->mobile) || empty($this->completeISDCode))){
			$userDetails           = $this->checkUserValidation();
			$userDetails           = $userDetails[0];
			$cookiestr             = explode('|', $userDetails['cookiestr']);
			$this->email           = trim($cookiestr[0]);
			$this->mobile          = $userDetails['mobile'];
			$this->completeISDCode = $userDetails['isdCode'].'-'.$userDetails['mobile'];
		}
		
		$trackingParams['email']          = $this->email;
		$trackingParams['mobile']         = $this->mobile;

		if(!$this->_validateUserInput()){
			echo 'failed';
			return;
		}

        if(empty($trackingParams['regFormId'])){
            $trackingParams['regFormId'] = 'regIdMissing';
            mail('aman.varshney@shiksha.com,naveen.bhola@shiksha.com','RegformId empty : '.date('Y-m-d H:i:s'), 'Data: '.print_r($returningUserCall,true).'<br/> POST Data: '.print_r($_POST,true)."        ".$_SERVER['SCRIPT_URI']." http referer : ". $_SERVER['HTTP_REFERER']);
        }

		// Web Bot handlings
		if(!$isPWACall && !$this->isStudyabroadReturning){
			if(!$this->input->is_ajax_request()){ 		
				echo "yes";
				return;
			}
		}

		$isdCode                   = explode('-', $this->completeISDCode);
		$isdCode                   = $isdCode[0];
		$trackingParams['isdCode'] = $isdCode;

		$flag = $this->isStudyAbroad == 1 ? 'Abroad' : 'National';
		$trackingParams['site_source'] = $flag;

		if(isset($this->email) && $this->email != ''){
			$userId = $this->usermodel->getUserIdByEmail ($this->email,False,'write');
		}		
		
		// BYPASS OTP verification for script and local testing
		$queryString = parse_url ( $_SERVER ['HTTP_REFERER'], PHP_URL_QUERY );
		parse_str ( $queryString, $URLParams );
		
		if (OTP_VERIFICATION == FALSE || $URLParams ['disableOTPVerification'] == 'e2b22fef40f50e9588431cf86f32406a') {
			$this->usermodel->updateMobileVerifiedFlagforUser($userId,'1');
			$this->createCookie();
			echo 'skip';
			return;
		}
		
		/*
		*Check's OTP verification for returning user.
		*returns true only if user is verified
		*the second if check, if user is logged in then don't show login layer
		*/
		
		// call to isUserVerified 
		if ($userId > 0) {
			$isOtpVerified                 = $this->verificationmodel->isUserVerified( $this->email, $this->mobile, $isdCode);
			$trackingParams['is_new_user'] = 'no';
			if($this->isStudyAbroad == 0 && $isNewUser == 'true'){
				$trackingParams['is_new_user'] = 'yes';
			}
		} else {
			$trackingParams['is_new_user'] = 'yes';
		}

		$trackingParams['visitor_session_id'] = getVisitorSessionId();

		if(($isOtpVerified || $userId) && !isset($_COOKIE['user']) ){
			echo 'exists';
			return;
		}else if($isOtpVerified && isset($_COOKIE['user'])){
			$this->createCookie();
		    echo "skip";
			return;
		}

		if ($this->verification == 'OTP') {
			$existingOTPData = $this->verificationmodel->doesOTPExist( $this->email );
			
			if ($existingOTPData === false) {
				$OTP = $this->generateOTP ();
				$id  = $this->verificationmodel->saveOTP( $this->email, $this->mobile, $OTP, $flag, $isdCode );

				// code for tracking OTP Calls
				$trackingParams['otp_verification_id'] = $id;
				$trackingParams['OTP']                 = $OTP;
				$trackingParams['otp_status']          = 'pending';
				$trackId                               = $this->verificationmodel->trackOTP($trackingParams);

			} else {
				$id           = $existingOTPData ['id'];
				$attempts     = $existingOTPData ['attempts'];
				$isdCodeExist = $existingOTPData ['isdCode'];
				$mobileExist  = $existingOTPData ['mobile'];
				$flagExist    = $existingOTPData ['flag'];
				$OTP          = $existingOTPData ['OTP'];
				
				$newOTP = '';
				if ($this->mobile != $mobileExist || $isdCode != $isdCodeExist || strlen($OTP) > 4) {
					$OTP = $this->generateOTP ();
				}

				if($attempts < 4){

					if (($this->mobile != $mobileExist) || ($isdCode != $isdCodeExist) || ($flagExist != $flag) || (strlen($existingOTPData['OTP']) > 4)) {
						$this->verificationmodel->updateOTPStatus ( $id, 'changeValue', $this->mobile, $flag, $OTP, $isdCode);
					} else {
						$this->verificationmodel->updateOTPStatus ( $id, 'resend');
					}
					
				}

				$trackingParams['otp_verification_id'] = $id;
				$trackingParams['OTP']                 = $OTP;
				$trackingParams['otp_status']          = 'pending';
				$trackId                               = $this->verificationmodel->trackOTP($trackingParams);
				
				if ($attempts >= 4 && $this->isStudyabroadReturning == 0) {
					echo 'failed';
					return;
				}else if($attempts >= 4){
					return 'failed';
				}
			}

			$isSent = modules::run ( 'SMS/sms_server/sendOTP', $trackingParams, $trackId );

			if($this->isStudyabroadReturning){
			    return;
			}
			
			echo $isSent;
			return;
		}
	}
	
	function generateOTP() {
		mt_srand ( microtime ( true ) );
		return mt_rand (1000,9999);
	}
	
	function verifyOTP() {
		
		$this->_init();

		$this->_extractInputs();
		
		if(!$this->_validateUserInput()){
			echo 'no';
			return;
		}

		$OTP = $this->input->post('OTP', true);
		
		$existingOTPdata = $this->verificationmodel->doesOTPExist( $this->email );		

		$isdCode = explode('-', $this->completeISDCode);
		$isdCode = $isdCode[0];

		$isverified = $this->verificationmodel->checkOTP( $this->email, $this->mobile, $OTP, $this->isStudyAbroad, $isdCode );

		// change mobile number in tuser if a logged in user change's its mobile number
		if($existingOTPdata['mobile'] != $this->mobile && $isverified == 'yes'){
		    //$this->load->model('User/user/usermodel');
		    $userId = $this->usermodel->getUserIdByEmail($this->email,False,'write');
		    $this->usermodel->updateMobileByUserId($userId, $this->mobile);
		}
		 
		unset($existingOTPdata); 
		
		// setting cookie to bypass check of OTP verification for logged in user
		if($isverified == 'yes'){
			if($this->isStudyAbroad == 0){
				$userId = $this->usermodel->getUserIdByEmail($this->email,False,'write');
				//$this->usermodel->updateMobileByUserId($userId, $this->mobile);
			}
			$this->createCookie();
			
			if($this->isStudyAbroad == 0){
				if(empty($userId) || $userId == null || is_null($userId) || $userId<=0 ){
					$userData = $this->checkUserValidation();
					//mail('teamldb@shiksha.com','checkUserValidation VERIFYOTP FLOW',print_r($userData, true).' '.$this->email.'==userid=='.var_dump($userId));
					if($userData[0]['userid'] >0){
						$userId = $userData[0]['userid'];
					}else{
						mail('teamldb@shiksha.com','Issue in VERIFYOTP FLOW',print_r($userData,true).'  '.$this->email);
						echo 'yes';return;
					}
				}

				$this->usermodel->updateMobileByUserId($userId, $this->mobile);

				$response = $this->isTestUser(array("mobile" => $this->mobile));
				$tUserFlagData['isTestUser']  = 'NO';
				if($response == true){
					$tUserFlagData['isTestUser']  = 'YES';
				}
				$tUserFlagData['mobileverified']  = '1';

				$this->load->library('cacheLib');
            	$this->cacheLib = new cacheLib();
	            $key = "lu_".md5('validateuser'.$_COOKIE['user'].'on');
				$this->cacheLib->clearCacheForKey($key);

				// send welcome SMS
				$welComeSMSObj = new \user\libraries\RegistrationObservers\WelcomeSMS;
				$welComeSMSObj->sendWelcomeSMS($userId,$this->email,$this->mobile);

				// check for exclusion list
				if($this->completeISDCode != INDIA_ISD_CODE){
					$this->usermodel->addUserToLDBExclusionList($userId, 'International User',$isdCode);
					$tUserFlagData['isLDBUser']  = 'NO';
	            }

	            // update tuserflag table
				$this->updateTUserFlagData($userId, $tUserFlagData);//updateTUserFlag

				//add user to  tuserIndexingQueue
        		$this->usermodel->addUserToIndexingQueue($userId);
            }else{
            	$this->updateTUserFlag($userId);
            }

		}
		
		echo $isverified;
		return;
	}
	
	/*
	 *checkAttempts function check attempts made by a user while verifing OTP
	 */
	
	function checkAttempts(){
		
		$this->load->model ( 'verificationmodel' );
		$this->verificationmodel = new verificationmodel ();
		
		$email = $this->input->post('email');

		//Exception Handling
		if(empty($email) || !validateEmailMobile('email', $email)){
			echo 'remaining';
			return;
		}

		$OTP = $this->verificationmodel->doesOTPExist($email);
		if($OTP['attempts'] >= 5){
			echo 'exceed';
			return;
		}else{
			echo 'remaining';
			return;
		}
	}
	
	/*
	 * create's cookie, to bypass database check for verified number
	 * @params: email id and mobile number
	 */
	function createCookie(){
		unset($_COOKIE['nregv']);
		$cookie_name = "nregv";
		$cookie_value = $this->email."|".$this->completeISDCode.$this->mobile;
		setcookie($cookie_name, $cookie_value, time() + (86400), "/"); // 1 day
	}

	/* update mobileverified in tuserflag to '1' if loggedIn user update his number
	* @params :  email id 
	*/
	private function updateTUserFlag($userId){
		$this->_init();
		// $userId = $this->usermodel->getUserIdByEmail ($email);
		if(!empty($userId)){
			$this->usermodel->updateMobileVerifiedFlagforUser($userId,'1');
		}
	}

	private function updateTUserFlagData($userId, $tUserFlagData){
		$this->_init();
		// $userId = $this->usermodel->getUserIdByEmail ($email);
		if(!empty($userId)){
			$this->usermodel->updateTUserFlagData($userId, $tUserFlagData);
		}
	}
	

	public function doesOTPVerified(){
		$email = isset($_POST['email'])? $this->input->post('email', true):false;

		if(!empty($email) && validateEmailMobile('email', $email)){
			$this->load->model( 'verificationmodel' );
			$this->verificationmodel = new verificationmodel();

			echo $this->verificationmodel->doesOTPVerified($email);
			return;
		}
		echo 'false';
		return;
	}

	function checkSMSStatusFromVendor(){
		
		$this->validateCron();
		$this->_init();
		
		$lastProcessedId = $this->verificationmodel->getLastProcessedTrackingId('OTP_STATUS_TRACKING');

		$processCount = $this->verificationmodel->getNumberAndMaxIdToBeProcessed($lastProcessedId);
		
		$noOfRecords  = $processCount[0]['number'];
		$maxId        = $processCount[0]['maxId'];

		if($maxId == $lastProcessedId || $noOfRecords == 0){
			return false;
		}
		
		$loopCount     = (int)($noOfRecords/$this->chunkSize);
		$lastChunkSize = $noOfRecords % $this->chunkSize;
		$this->load->helper('sms');
		$this->config->load('sms_settings');
			
		for($i = 0; $i <= $loopCount; $i++){

			$count = $this->chunkSize;
			if($i == $loopCount){
				$count = $lastChunkSize;
			}
			$records = $this->verificationmodel->getRecordsToBeProcessed($lastProcessedId, $count);
			
			if(!empty($records)) {

				$xmlGUIDTag = '';
				$GUIDMap    = array();
				foreach ($records as $id => $trackData) {
					if($trackData['GUID'] == ''){
						continue;
					}
					$GUIDMap[$trackData['GUID']]['otp_tracking_id'] = $trackData['id'];
					$GUIDMap[$trackData['GUID']]['sent_time']       = $trackData['sent_time'];
					
					$xmlGUIDTag .= '<GUID GUID="'.$trackData['GUID'].'" ><STATUS SEQ="1" /></GUID>';
				}
				unset($records);

				$credentials = $this->config->item('OTP');

		        $xmlMessage = '<?xml version="1.0" encoding="ISO-8859-1"?>
		        <!DOCTYPE STATUSREQUEST SYSTEM "http://127.0.0.1:80/psms/dtd/requeststatusv12.dtd">
		        <STATUSREQUEST VER="1.2">
		            <USER USERNAME="'.$credentials['username'].'" PASSWORD="'.$credentials['PASSWORD'].'"/>'.
		            $xmlGUIDTag
		        .'</STATUSREQUEST>';
		        
		        $response    = makeVerificationSmsCurl($xmlMessage);
		        $xmlObj      = simplexml_load_string($response);
		        $xmlJson     = json_encode($xmlObj);
		        $xmlArr      = json_decode($xmlJson, true);
		        
		        unset($response);
		        unset($xmlObj);
		        unset($xmlJson);
		        
				$dataToInsert = array();

				foreach ($xmlArr['GUID'] as $key => $statusArr) {
					$insertArray                    = array();
					$insertArray['GUID']            = (string)$statusArr['@attributes']['GUID'];
					$insertArray['otp_tracking_id'] = $GUIDMap[$insertArray['GUID']]['otp_tracking_id'];
					$insertArray['sent_time']       = $GUIDMap[$insertArray['GUID']]['sent_time'];
					$insertArray['err_code']        = (int)$statusArr['STATUS']['@attributes']['ERR'];
					$insertArray['response_time']   = (string)$statusArr['STATUS']['@attributes']['DONEDATE'];
					$insertArray['reason_code']     = (string)$statusArr['STATUS']['@attributes']['REASONCODE'];
					$insertArray['failure_reason']  = $this->reasonCodeDescription[$insertArray['reason_code']];
					if($insertArray['reason_code'] == '000'){
						$insertArray['vendor_status'] = 'delivered';
					} else{
						$insertArray['vendor_status'] = 'failed';
					}
					$lastInsertedId = $insertArray['otp_tracking_id'];
					$dataToInsert[] = $insertArray;
				}
				unset($xmlArr);

				$this->verificationmodel->insertResponseInBatch($dataToInsert);
				$this->verificationmodel->updateLastProcessedTrackingId($lastInsertedId, 'OTP_STATUS_TRACKING');
				
				$lastProcessedId = $lastInsertedId;
			
			}

		}

    }

	// get new CSRF token
    function getAT(){
        $this->load->library('security');
        $this->security->setCSRFToken();
        echo $this->security->csrf_hash;
    }

    public function doesMCVerified(){
    	if(!verifyCSRF()) { return false; }

		$mobile = $this->input->post('mobile', true);
		$email = $this->input->post('email', true);
		$isErrInMCVerification = $this->input->post('isErrInMCVerification', true);
		if($isErrInMCVerification == 'yes'){
			//error_log("ERROR_IN_MCVerification_AJAX_CALL. Post data : ".print_r($_POST,true));
			//mail('naveen.bhola@infoedge.com,praveen.singhal@99acres.com', "Ajax fail In MCVerification", print_r($_POST,true).'    '.print_r($_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS'],true));
		}
		if(!empty($mobile)){
			$this->load->model('verificationmodel');
			$verificationmodel = new verificationmodel();
			$isMcVerified = $verificationmodel->doesMCVerified($mobile);	
			if($isMcVerified  == 'yes'){
				$this->load->model ('user/usermodel');
				$usermodel = new usermodel();
				$userId    = $usermodel->getUserIdByEmail($email,False,'write');

				//update MC Tracking and OTPVerification
				$completedProcess = $verificationmodel->updateMCTrackingAndVerification($mobile,$email);			
				if($completedProcess['status'] == true){
					if(empty($userId) || $userId == null || is_null($userId) || $userId<=0 ){
						$userData = $this->checkUserValidation();
						//mail('teamldb@shiksha.com','checkUserValidation MISSED CALL VERIFICATION FLOW',print_r($userData, true).' '.$email.'==userid=='.var_dump($userId));
						if($userData[0]['userid'] >0){
							$userId = $userData[0]['userid'];
						}else{
							mail('teamldb@shiksha.com','Issue in MISSED CALL VERIFICATION FLOW',print_r($userData,true).'  '.$email);
							echo 'yes';return;
						}
					}
					$usermodel->updateMobileByUserId($userId, $completedProcess['mobile']);
					$this->updateTUserFlag($userId);
			
					$this->load->library('cacheLib');
	            	$this->cacheLib = new cacheLib();
		            $key = "lu_".md5('validateuser'.$_COOKIE['user'].'on');
					$this->cacheLib->clearCacheForKey($key);

					// send welcome SMS
					$welComeSMSObj = new \user\libraries\RegistrationObservers\WelcomeSMS;
					$welComeSMSObj->sendWelcomeSMS($userId,$email,$completedProcess['mobile']);

					
					//add user to  tuserIndexingQueue
	        		$usermodel->addUserToIndexingQueue($userId);
            
					echo 'yes';
					return;
				}
			}			
		}

		echo 'no';
		return;	
	}

	function trackMCAction(){
        if(!verifyCSRF()) { return false; }
        
        $this->_init ();

		$email                                = $this->input->post ('email', true);
		$mobile                               = $this->input->post ('mobile', true);
		$regFormId                            = $this->input->post('regFormId', true);
		$isStudyAbroad                        = $this->input->post ('isStudyAbroad');
		$trackingKeyId                        = $this->input->post('trackingKeyId', true);
		$isdCode                              = $this->input->post('isdCode', true);
		$completeISDCode                      = isset($isdCode)? $isdCode : '91-2';
		$retry                                = $this->input->post('retry', true);
		
		$trackingParams                       = array();
		//fetch otp verification Id 
		$otpData 							  = $this->verificationmodel->doesOTPExist($email);
		if(empty($otpData['id'])){			
			mail('aman.varshney@shiksha.com','Entry not found in OTPVerification '.date('Y-m-d H:i:s'), 'EMail: <br/>'.$email. " mobile : ".$mobile." regFormId : ".$regFormId." Current Url : ".$_SERVER['SCRIPT_URI']." http referer : ". $_SERVER['HTTP_REFERER']);							
			echo 'failed';return;
		}
		$trackingParams['otp_verification_id']= $otpData['id'];
		$trackingParams['reg_form_id']        = isset($regFormId)? $regFormId : '0';
		$trackingParams['email']              = isset($email)? $email : '0';
		$isdCode                              = explode('-', $completeISDCode);
		$isdCode                              = $isdCode[0];
		$trackingParams['isdCode']            = $isdCode;
		$trackingParams['mobile']             = isset($mobile)? $mobile : '0';
		$trackingParams['site_source']        = $isStudyAbroad == 1 ? 'Abroad' : 'National';
		$validateuser 						  = $this->checkUserValidation();
		if($validateuser == 'false'){
			$trackingParams['is_new_user']        = 'yes';			
		}else{
			$trackingParams['is_new_user']        = 'no';			
		}
		$trackingParams['tracking_key_id']    = isset($trackingKeyId)? $trackingKeyId : '0';
		$trackingParams['visitor_session_id'] = getVisitorSessionId();
		$trackingParams['missed_call_status'] = 'waiting';
		$trackingParams['retry']              = isset($retry)?$retry:0;
		$trackingParams['mobilewithisd']      = $trackingParams['isdCode'].$trackingParams['mobile'];

		$missedCallTrackingId = $this->verificationmodel->trackMC($trackingParams);
    }

    function trackMCActionError(){
    	$data = array(
    		'Post Data' => $_POST,
    		'Server fields' => $_SERVER
    		);
    	mail('teamldb@shiksha.com', "Data missing in trackMCAction api", print_r($data,true));
    }


    function checkIfUserExist(){
    	$email = $this->input->post('email',true);
    	if(empty($email)){
    		echo 'false';return;
    	}
    	$usermodel = $this->load->model ( 'user/usermodel' );
    	$userId = $usermodel->getUserIdByEmail($email);
    	if($userId >0){
    		echo 'true';return;
    	}
    	echo 'false';return;
    }

    public function isTestUser($data = array()){
        require FCPATH.'globalconfig/testUserConfig.php';

        if(!empty($data['name'])){
            if($name = $data['name']) {
                $filter = '/^testuser[ ]*[0-9]+$/i';
                if(preg_match($filter,$name)) {
                    return TRUE;
                }
            }
        }
            
        if(!empty($data['email'])){
            if($email = $data['email']) {
                $emailDomain = end(explode('@',$email));
                if(is_array($testUserDomainsForEmail) && in_array($emailDomain,$testUserDomainsForEmail)) {
                    return TRUE;
                }
            }
        }
            
        if(!empty($data['mobile'])){
            if($mobile = $data['mobile']) {
                if(is_array($testUserMobileNumbers) && in_array($mobile,$testUserMobileNumbers)) {
                    return TRUE;
                }    
            }
        }
        return FALSE;
    }
}
