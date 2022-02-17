<?php

/**
* 
*/
class FBLeadResponse
{
	/**
     * @var object CodeIgniter object
     */ 
	private $CI;

	/**
     * Constructor
     */ 
	function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->helper('string');
	}

	public function createFBLeadResponse($FBData, $FBLeadDataMapping){
		$_POST = array();
		$_COOKIE = array();

		global $visitSessionId;
    	$visitSessionId = null;
		
		// check if user exist
        $userModel = $this->CI->load->model('user/usermodel');
        $userObj = $userModel->getUserByEmail($FBData['email']);

        $isNewUser = false;

        if(!is_object($userObj)){
            $isNewUser = true;
        }
        
		// prepare data for response
		$this->_prepareDataForResponse($FBData, $FBLeadDataMapping, $userObj, $isNewUser);

		// add or update entry in OTPVerification table.
		$oldUserOTPData = $this->_updateOrAddInOTPVerificationTable($FBData, $FBLeadDataMapping, $isNewUser, $userObj);

		if($isNewUser == true){		
			$response = Modules::run('registration/Registration/register');
		}else{
			$response = Modules::run('registration/Registration/updateUser');
		}

		$response = json_decode($response);	
		$userId =$response->userId;
		if($response->status == 'SUCCESS'){
			$response = Modules::run('response/Response/createResponse');
			$response = json_decode($response);
			if($response->status == 'SUCCESS'){
				return array('status'=>'SUCCESS' , 'userId' => $userId);
			}else{
				$this->FBLeadCommonLib = $this->CI->load->library('marketing/fbLead/FBLeadCommon');
				
				//load library to send mail
	            $errorContent = print_r($_POST, true);
	            $errorContent .= " <br> Error Status".print_r($response,true);
				$this->FBLeadCommonLib->sendMail('teamldb@shiksha.com', "FB Lead Error in response API flow", $errorContent);
				return array('status'=>'FAIL');
			}
		}else{
			//rollback  otpverific
			if($isNewUser != true && !($userObj->getFlags()->getMobileVerified() == 1)){
				// update previous row of otptable
				$fbleadmodel = $this->CI->load->model('marketing/fbleadmodel');
				$fbleadmodel->updateOTPVerificationTable($oldUserOTPData, $FBData['email']);
			}

			$this->FBLeadCommonLib = $this->CI->load->library('marketing/fbLead/FBLeadCommon');
			//load library to send mail
            $errorContent = print_r($_POST, true);
            $errorContent .= " <br> Error Status".print_r($response,true);
			$this->FBLeadCommonLib->sendMail('teamldb@shiksha.com', "FB Lead Error in registration API", $errorContent);

			return array('status'=>'FAIL');
		}
	}

	private function _updateOrAddInOTPVerificationTable($FBData, $FBLeadDataMapping, $isNewUser, $userObj){
		$isMobileVerified = false;
		if($isNewUser != true){
			if($userObj->getFlags()->getMobileVerified() == 1){
				$isMobileVerified = true;
			}
		}

		if($isMobileVerified == false){
			$fbleadmodel = $this->CI->load->model('marketing/fbleadmodel');

			// select data first, in case of old user and not mobile verified
			if($isNewUser != true){
				$oldUserOTPData = $fbleadmodel->getOTPDataForOldUser($FBData['email']);
				$oldUserOTPData = $oldUserOTPData[0];
				unset($oldUserOTPData['id']);
				unset($oldUserOTPData['email']);
			}

			$OTP = Modules::run('userVerification/userVerification/generateOTP');
			
			$isdCode = explode("-", $FBLeadDataMapping['isdCode']);
			$isdCode = $isdCode[0];
			$data = array(
				'email'		=>	$FBData['email'],
				'isdCode'	=> 	$isdCode,
				'mobile'	=> 	$FBLeadDataMapping['mobile'],
				'OTP'		=>	$OTP,
				'attempts'	=> 	-1,
				'status'	=>	'verified',
				'Flag'		=>	$FBLeadDataMapping['Flag'],
				'GUID'		=> 	"fbuser"
			);
			$fbleadmodel->saveOTP($data);
		}
		return $oldUserOTPData;
	}

	private function _prepareDataForResponse($FBData, $FBLeadDataMapping, $userObj, $isNewUser){
		//_p($FBData);_p($FBLeadDataMapping);var_dump($isNewUser);die;
		$this->_prepareBasicInfo($FBData, $FBLeadDataMapping, $userObj, $isNewUser);

		//_p($FBData);_p($FBLeadDataMapping);var_dump($isNewUser);die;
		$this->_prepareUserBasicInfo($FBData, $FBLeadDataMapping, $userObj, $isNewUser);
		//echo "Post Data for response:<br>";_p($_POST);die;
		$this->_prepareCourseInfo($FBData, $FBLeadDataMapping, $userObj, $isNewUser);
	}

	private function _prepareBasicInfo($FBData, $FBLeadDataMapping, $userObj, $isNewUser){
		$_POST['context'] = $FBLeadDataMapping["context"];
		$_POST['tracking_keyid'] = $FBLeadDataMapping['tracking_keyId'];

		$_POST['regFormId'] = random_string('alnum', 6);
		$_POST['isFBCall'] = 1;
		$_POST['registrationSource'] = $FBLeadDataMapping['referral'];
	}
	
	private function _prepareCourseInfo($FBData, $FBLeadDataMapping, $userObj, $isNewUser){
		$_POST['email_id'] = $FBData['email'];
		$_POST['listing_type'] = 'course';
		$_POST['clientCourse'] = $FBLeadDataMapping['courseId'];
		$_POST['listing_id'] = $FBLeadDataMapping['courseId'];
		$_POST['clientCourseName'] = $FBData['course_name'];
		$_POST['prefCity'] = $FBLeadDataMapping['location'];

		if(!empty($FBLeadDataMapping['locationLocality'])){
			$_POST['prefLocality'] = 	(string)$FBLeadDataMapping['locationLocality'];
		}

		if(!($FBLeadDataMapping['workEx'] != 0 && empty($FBLeadDataMapping['workEx']))){
			$_POST['workExperience'] = $FBLeadDataMapping['workEx'];
		}

		$_POST['stream'] = (string)$FBLeadDataMapping['courseAttributes']['mappedHierarchies']['stream'];
		$_POST['level'] = (string)$FBLeadDataMapping['courseAttributes']['level']->getId();
		$_POST['credential'] = (string)$FBLeadDataMapping['courseAttributes']['credential']->getId();
		$_POST['baseCourses'] = array((string)$FBLeadDataMapping['courseAttributes']['baseCourse']);
		$_POST['educationType'] = array((string)$FBLeadDataMapping['courseAttributes']['mode']);

		
		$subStreamSpecMapping = array();
		$subStreams = array();
		$specializations = array();
		$hierarchies = $FBLeadDataMapping['courseAttributes']['mappedHierarchies']['hierarchies'];		
		if(count($hierarchies) ==1 && (is_array($hierarchies[0]) && count($hierarchies[0]) <=0)){
			$subStreamSpecMapping = new stdClass();
		}else{
			foreach ($FBLeadDataMapping['courseAttributes']['mappedHierarchies']['hierarchies'] as $subStream => $specialization) {
				$mappedSubStream = ($subStream ==0) ?"ungrouped":$subStream;
				$subStreams[] = $mappedSubStream;
				if(count($specialization) >0 ){
					$specializationArray = array_values($specialization);
					$specializations = array_merge($specializations,$specializationArray);
					$subStreamSpecMapping[$mappedSubStream] = $specializationArray;
				}else{
					$subStreamSpecMapping[$mappedSubStream] = array();
				}
			}
		}

		$_POST['subStreamSpecMapping'] = json_encode($subStreamSpecMapping);
		if(count($specializations) >0){
			$_POST['specializations'] = $specializations;	
		}
		
		if(count($subStreams) >0){
			$_POST['subStream'] = $subStreams;
		}

		$_POST['action_type'] = $FBLeadDataMapping["action_type"];
		$_POST['isResponseForm'] = "yes";
		$_POST['isMR'] = "YES";
	}

	private function _prepareUserBasicInfo($FBData, $FBLeadDataMapping, $userObj, $isNewUser){
		//_p($FBData);_p($FBLeadDataMapping);var_dump($isNewUser);die;
		if($isNewUser){
			$this->_prepareBasicInfoForNewUser($FBData, $FBLeadDataMapping, $userObj, $isNewUser);
		}else{
			$this->_prepareBasicInfoForOldUser($FBData, $FBLeadDataMapping, $userObj, $isNewUser);
		}
	}

	private function _prepareBasicInfoForNewUser($FBData, $FBLeadDataMapping, $userObj, $isNewUser){
		$_POST['email']                 = $FBData['email'];
		$_POST['firstName']             = $FBData['first_name'];
		$_POST['lastName']              = $FBData['last_name'];
		$_POST['isdCode']               = $FBLeadDataMapping['isdCode'];
		$_POST['mobile']                = $FBLeadDataMapping['mobile'];
		$_POST['residenceCityLocality'] = $FBLeadDataMapping['city'];
		$_POST['mobileVerified']		= 1;
		$_POST['usergroup'] 			= "fbuser";
		$_POST['password']				= 'Shiksha@'.rand(100001,999999);
	}

	private function _prepareBasicInfoForOldUser($FBData, $FBLeadDataMapping, $userObj, $isNewUser){
		$_POST['email']                 = $userObj->getEmail();
		$_POST['userId'] 				= $userObj->getId();

		$_POST['firstName']             = $userObj->getFirstName();
		$_POST['lastName']             = $userObj->getLastName();
		if(empty($_POST['firstName']) && empty($_POST['lastName'])){
			$_POST['firstName'] = $FBData['first_name'];
			$_POST['lastName'] = $FBData['last_name'];
		}

		$_POST['residenceCityLocality']             = $userObj->getCity();
		if(empty($_POST['residenceCityLocality'])){
			$_POST['residenceCityLocality'] = $FBLeadDataMapping['city'];
		}

		if($userObj->getFlags()->getMobileVerified() == 1){
			$oldIsdCode = $userObj->getISDCode();
			$oldCountryCode = $userObj->getCountry();
			if(!empty($oldIsdCode) && !empty($oldCountryCode)){
				$_POST['isdCode'] = $oldIsdCode.'-'.$oldCountryCode;
				$_POST['mobile']  = $userObj->getMobile();
			}else if(!empty($oldIsdCode) && empty($oldCountryCode)){
				mail('teamldb@shiksha.com','Country Id is blank and isdcode is not blank','FB Data : '.print_r($FBData,true));
				$userMobileNo  = $userObj->getMobile();
				if(empty($userMobileNo)){
					$_POST['isdCode']               = $FBLeadDataMapping['isdCode'];
					$_POST['mobile']                = $FBLeadDataMapping['mobile'];
					$_POST['mobileVerified']		= 1;
				}else{
					$userModel = $this->CI->load->model('user/usermodel');
					$countryId = $userModel->getCountryIdFromIsdCode($oldIsdCode);
					if($countryId >0){
						$_POST['isdCode'] = $oldIsdCode.'-'.$countryId;	
						$_POST['mobile']  = $userMobileNo;
					}else{
						mail('teamldb@shiksha.com','Not able to find mapping in DB.','FB Data : '.print_r($FBData,true));
						return false;
					}
				}
			}else{
				mail('teamldb@shiksha.com','Country Id and isdcode blank.','FB Data : '.print_r($FBData,true));
			}
		}else{
			$_POST['isdCode']               = $FBLeadDataMapping['isdCode'];
			$_POST['mobile']                = $FBLeadDataMapping['mobile'];
			$_POST['mobileVerified']		= 1;
		}
	}
}
?>