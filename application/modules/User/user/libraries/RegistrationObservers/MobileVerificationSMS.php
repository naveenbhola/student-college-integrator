<?php
/**
 * Registration observer to send mobile verification SMS
 */ 
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to send mobile verification SMS
 */ 
class MobileVerificationSMS extends AbstractObserver
{
	/**
	 * Constructor
	 */
    function __construct()
    {
        parent::__construct();
    }
    
	/**
	 * Update the observer
	 *
	 * @param object $user \user\Entities\User
	 */ 
    public function update(\user\Entities\User $user)
    {
		$userId = $user->getId();
		$mobile = $user->getMobile();
		$email = $user->getEmail();
		$isdCode = $user->getISDCode();
		
		if($mobile) {
			
			$this->CI->load->model('userVerification/verificationmodel');
			$verificationmodel = new \verificationmodel();
			
			if($verificationmodel->isUserVerified($email,$mobile, $isdCode)) {
				
				$this->CI->load->model('user/usermodel');
				$usermodel = new \usermodel();	
				$usermodel->updateMobileVerifiedFlagforUser($userId,'1');
					
				$this->CI->load->library('user/UserLib');
				$userLib = new \UserLib;				
				$userLib->updateUserData($userId);	
							
			} else {
				$message = "Your number has been successfully verified";
				$this->CI->load->model('smsModel');
				$Isregistration = 'Yes';
				$this->CI->smsModel->addSmsQueueRecord('',$mobile,$message,$userId,'0000-00-00 00:00:00',"",$Isregistration);
				
		   }
	    }
    }
}
