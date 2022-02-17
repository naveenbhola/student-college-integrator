<?php
/**
 * Registration observer file to send welcome mailer
 */
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to send welcome mailer
 */ 
class WelcomeSMS extends AbstractObserver
{
	
	/**
	 * Constructor
	 */
    function __construct()
    {
        parent::__construct();
        $this->CI->load->library('cacheLib');
		$this->cacheLib = $this->CI->cachelib;
		
		
    }
    
	/**
	 * Update the observer
	 *
	 * @param object $user \user\Entities\User
	 * @param array $data
	 */ 
    public function update(\user\Entities\User $user,$data = array())
    {
		$prefs   = $user->getPreference();     
        $isdCode = $user->getISDCode();

        if($prefs->getExtraFlag() != 'studyabroad' && $isdCode == 91){
            $userId    = $user->getId();
            $mobile    = $user->getMobile();
            $email     = $user->getEmail();
            $userGroup = $user->getUserGroup();

            $uniqueCode = $this->_getUniqueCode($userId,$email);

            $autoLoginUrl = SHIKSHACLIENTIP.'/r/s/'.$uniqueCode;
            $this->CI->load->model('smsModel');
            $this->CI->load->config('registration/registrationFormConfig');
            if($userGroup == 'fbuser'){
            	$smsText  = $this->CI->config->item('successTextMsgForFBUSER');                    	
            }else{
            	$smsText  = $this->CI->config->item('successTextMsg');                    	
            }
            $smsText  = str_replace("<Auto-Login>", $autoLoginUrl, $smsText);

            $this->CI->smsModel->addSmsQueueRecord('',$mobile,$smsText,$userId,'0000-00-00 00:00:00',"");            
        }
	}

	public function sendWelcomeSMS($userId,$email,$mobile){		
		$uniqueCode = $this->_getUniqueCode($userId,$email);	

        $autoLoginUrl = SHIKSHACLIENTIP.'/r/s/'.$uniqueCode;
        $this->CI->load->model('smsModel');
        $this->CI->load->config('registration/registrationFormConfig');
        $smsText  = $this->CI->config->item('successTextMsg');                    	        
        $smsText  = str_replace("<Auto-Login>", $autoLoginUrl, $smsText);
        
        $this->CI->smsModel->addSmsQueueRecord('',$mobile,$smsText,$userId,'0000-00-00 00:00:00',"");            
	}

	private function _getUniqueCode($userId,$email){		
			$whileCounter = 0;
			$responseCheckAvail = 1;
			while($responseCheckAvail == 1){
				$uniqueCode = $this->_generateUniqueCode(5);
				$responseCheckAvail = $this->checkUniqueCodeExist($uniqueCode);
				if($whileCounter > 0){
					mail('aman.varshney@shiksha.com','Regenerating unique Code at '.date('Y-m-d H:i:s'), 'Regenerating unique Code: <br/>'.$uniqueCode. " UserId ".$userId);							
				}
				$whileCounter = $whileCounter + 1;
			}

		$this->_storeUniqueCodeInCache($uniqueCode,array('email'=>$email,'userId'=>$userId));
		return $uniqueCode;		
	}

	private function checkUniqueCodeExist($uniqueCode){		
		if($this->cacheLib->get('userUniqueCode_'.$uniqueCode) != 'ERROR_READING_CACHE') { 
			return 1;
		}

		return 0;		
	}

	private function _storeUniqueCodeInCache($uniqueCode,$userData){
		$this->cacheLib->store('userUniqueCode_'.$uniqueCode,json_encode($userData), 86400);
		if(ENVIRONMENT == 'production'){
			error_log($uniqueCode." : ".$userData['userId']." : ".date('Y-m-d H:i:s')."\n", 3, '/data/app_logs/userUniqueCode');			
		}else{
			error_log($uniqueCode." : ".$userData['userId']." : ".date('Y-m-d H:i:s')."\n", 3, '/tmp/userUniqueCode');			
		}
	}

	private function _generateUniqueCode($length){
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet); // edited

		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[mt_rand(0, $max-1)];
		}

		return $token;
	}
}
