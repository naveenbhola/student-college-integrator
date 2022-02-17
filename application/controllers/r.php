<?php
// +----------------------------------------------------------------------+
// | Authors : Original Author Pranjul.Raizada                      |
// |                                         |
// +----------------------------------------------------------------------+
//
class r extends MX_Controller {
	/*this function to get the user autologin and direct the user to recomendation mailer page when user clicks on short url in sms*/	
	function a($uniqueCode) {
		$this->load->model('user/usermodel');
		$result = $this->usermodel->getDataForSMSRecomendation($uniqueCode);
		if(empty($result)){
			show_404();
		}
		$email  = $result['email'];
		$mailId = $result['mailid'];
		$date = date('Ymd',strtotime($result['creationtime']));
		$encodedEmail = $this->usermodel->getEncodedEmail($email);					
		$this->usermodel->storeTrackingSMSRecomendationData($result);					
		$viewMailerURL = SHIKSHA_HOME.'/mailer/Mailer/viewMailer/'.$mailId.'/'.$date;
		$autoLoginData = 'email~'.$encodedEmail.'_url~'.base64_encode($viewMailerURL);
		$autoLoginURL = SHIKSHA_HOME.'/mailer/Mailer/autoLogin/'.$autoLoginData;
		header('Location: '.$autoLoginURL);
	}
	
	function c($encodedCouponCode) {
		$url = SHIKSHA_HOME.'/mba/resources/application-forms?q='.$encodedCouponCode;
		header('Location: '.$url);
	}

	function s($uniqueCode) {
		$this->load->model('user/usermodel');
		$this->load->library('common/cacheLib');
		$this->cacheLib = $this->cachelib;

		$response = $this->cacheLib->get('userUniqueCode_'.$uniqueCode);
		if($response == 'ERROR_READING_CACHE' || !$response){
			// redirect to home page
			header('Location: '.SHIKSHA_HOME.'/?utm_source=shiksha&utm_medium=sms&utm_campaign=welcome_sms_gt24');
			return;
		}
		
		$userData = json_decode($response,true);		
		$encodedEmail  = $this->usermodel->getEncodedEmail($userData['email']);
		$autoLoginData = 'email~'.$encodedEmail.'_url~'.base64_encode(SHIKSHA_HOME.'/?utm_source=shiksha&utm_medium=sms&utm_campaign=welcome_sms');
		$autoLoginURL  = SHIKSHA_HOME.'/mailer/Mailer/autoLogin/'.$autoLoginData;
		header('Location: '.$autoLoginURL);
	}
}
?>
