<?php
class Login extends MX_Controller{

	/**
	 * Init Function to load the library
	 */
	function init()	{
		$this->load->library('Login_client');
	}

	/**
	 * Function submit
	 */
	function submit()	{ 

		$this->init();
		$veryQuickSignUp = isset($_POST['typeOfLoginMade'])?$_POST['typeOfLoginMade']:'normal';

		if(strcmp($veryQuickSignUp,'normal') === 0){
			$uname = $this->input->post('username');
			$ePassword = $this->input->post('mpassword');
			$remember = $this->input->post('remember');
		}else{
	        if(strcmp($veryQuickSignUp,'request') === 0)
	        {
	            $uname = $this->input->post('usernamerb');
	            $ePassword = $this->input->post('mpasswordrb');
	            $remember = $this->input->post('remember');
	        }
	        else
	        {
	            $uname = $this->input->post('username_ForAnA');
	            $ePassword = $this->input->post('mpassword_ForAnA');
	            $remember = $this->input->post('remember_ForAnA');
	        }
		}
		$ePassword = sha256($ePassword);
		$appID = 12;
		$strcookie = $uname.'|'.$ePassword;

		$login_client = new Login_client();
		$Validate = $login_client->validateuser($strcookie,'login');
		error_log($Validate.'VALIDATE');
		if($Validate != "false" && is_array($Validate))
		{
			$value = $Validate[0]['cookiestr'];
			$status = $Validate[0]['status'];
			$pendingverification = $Validate[0]['pendingverification'];
			$hardbounce = $Validate[0]['hardbounce'];
			$ownershipchallenged = $Validate[0]['ownershipchallenged'];
			$softbounce = $Validate[0]['softbounce'];
			$abused = $Validate[0]['abused'];
			$emailsentcount = $Validate[0]['emailsentcount'];
			if($abused == 1 || $ownershipchallenged == 1)
			{
				echo 'invalid';

			} else {
				if($Validate[0]['emailverified'] == 1) {
					$value .= "|verified";
				} else	{
						if($hardbounce == 1)
							$value .= "|hardbounce";
						if($softbounce == 1)
							$value .= "|softbounce";
						if($pendingverification == 1)
							$value .= '|pendingverification';
				}
				if(($remember == 'on' || $remember == '1') && $Validate[0]['usergroup'] !='sums' || 1){
					// setcookie('user',$value,time() + 2592000,'/',COOKIEDOMAIN);
					setUserCookie($value,'/',COOKIEDOMAIN);
				}else{
					// setcookie('user',$value,0,'/',COOKIEDOMAIN);
					setUserCookie($value,'/',COOKIEDOMAIN,1);
				}

				$_COOKIE['user'] = $value;

				/**
				 * Track user login time
				 */ 	
				$this->load->model('user/usermodel');
				$this->usermodel->trackUserLogin($Validate[0]['userid']);
				

				$this->load->library('common/CookieBannerTrackingLib');
				$this->cookieBanner = new CookieBannerTrackingLib();
				$this->cookieBanner->oldUserCookieSet();

				/**
				 * Put shortList Cookie data into table
				 * 
				 */
				if(!empty($Validate[0]['userid']))	{
					$data ['userId'] = $Validate[0]['userid'];
					$data ['status'] = 'live';
					$data ['sessionId'] = sessionId ();
					$data ['pageType'] = 'abroadCategoryPage';
					$shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
					$shortlistListingLib->putShortListCouresFromCookieToDB ( $data);
				}
				echo $Validate[0]['userid'];
			}
		} else {
			echo 0;
		}
	}

	function sendResetPasswordNewMail($email) {

		$requested_page = 'shiksha/index';
		$link = SHIKSHA_HOME . '/user/Userregistration/Forgotpassword';
		$is_mobile = 'Y';
		echo \Modules::run('user/Userregistration/_sendResetPasswordNewMail',$email, $requested_page, $link, $is_mobile);

	}

}
