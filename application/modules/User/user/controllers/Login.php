<?php
class Login extends MX_Controller{
/**
 * Index Function
 */
function index()
{
	$redirectUrl = '/user/MyShiksha/index';
	if(isset($_REQUEST['redirectUrl']) && $_REQUEST['redirectUrl'] !='') {
		$redirectUrl = $_REQUEST['redirectUrl'];
	}
	
	$Validate = $this->checkUserValidation();

if($Validate == "false" || $Validate == '') {
		$displayData['redirectUrl'] = $redirectUrl;
		//$displayData['userdetails'] = $Validate;
		$this->load->view('user/loginpage', $displayData);
	} else {	
		if( (strpos($redirectUrl, "http") === false) || (strpos($redirectUrl, "http") != 0) || (strpos($redirectUrl, SHIKSHA_HOME) === 0) || (strpos($redirectUrl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($redirectUrl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($redirectUrl,ENTERPRISE_HOME) === 0) ){
			header('Location: '. $redirectUrl);
		}
		else{
		    header("Location: ".SHIKSHA_HOME,TRUE,301);
		}
	}

}
function ajax_user()
{
    echo json_encode($this->checkUserValidation());
}
/**
	 * Function to login user and redirect it
	 * @param string $url
	 */
function userlogin($url = '')
{
$redirectUrl = '';
if($url != '')
$redirectUrl = base64_decode($url);
$displayData['redirectUrl'] = $redirectUrl;
$this->load->view('user/loginpage', $displayData);
}

/**
 * Init Function to load the library
 */
function init()
{
$this->load->library('Login_client');
}
/**
	 * Function submit
	 */
function submit()
{ 
	if(!verifyCSRF()) { return false; }

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

		}
		else
		{
			if($Validate[0]['emailverified'] == 1)
				$value .= "|verified";
			else
			{
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
				//setcookie('fbSessionKey',$Validate[0]['fbSessionKey'],time() + 2592000,'/',COOKIEDOMAIN);
			}
			else
			{
				// setcookie('user',$value,0,'/',COOKIEDOMAIN);
				setUserCookie($value,'/',COOKIEDOMAIN,1);
				//setcookie('fbSessionKey',$Validate[0]['fbSessionKey'],0,'/',COOKIEDOMAIN);
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
			if(!empty($Validate[0]['userid']))
			{
			$data ['userId'] = $Validate[0]['userid'];
			$data ['status'] = 'live';
			$data ['sessionId'] = sessionId ();
			$data ['pageType'] = 'abroadCategoryPage';
			$shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
			$shortlistListingLib->putShortListCouresFromCookieToDB ( $data);
			$saABTracking = $this->input->post('saABTracking');
			if($saABTracking=='yes'){
				$tracking_page_key = $this->input->post('tracking_page_key');
	    		$page_referrer = $this->input->post('page_referrer');
				$signUpFormOptionLib = $this->load->library('studyAbroadCommon/signUpFormOptionLib' );
				$signUpFormOptionLib->updateAlreadyRegisteredUserData($Validate[0]['userid'],$tracking_page_key,$page_referrer);
				}
			}
			echo $Validate[0]['userid'];
		}
	}
	else
	{
		echo 0;
	}
}

function signOutPWA(){
    $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
	$this->signOut();
}

/**
 * Function to signout the user
 * @param string $redirectUrl
 */
function signOut($redirectUrl = ''){
    $this->init();
    $login_client = new Login_client();
    $strcookie = $_COOKIE['user'];
    $appId = 1;
    $response = $login_client->logOffUser($strcookie,$appId);
	//error_log("VAN: ".print_r($response,true));
	/**
	 * Track user logout time
	 */
	if($strcookie) {
		$userCookieParts = explode('|',$strcookie);
		$userEmail = $userCookieParts[0];
		if($userEmail) {
			$this->load->model('user/usermodel');
			$userId = $this->usermodel->getUserIdByEmail($userEmail);
			if($userId) {
				$this->usermodel->trackUserLogout($userId);
			}
		}
	}

    // setcookie('user','',time() - 864000,'/',COOKIEDOMAIN);
    setUserCookie($value,'/',COOKIEDOMAIN,"",time()-864000);
    setcookie('fbSessionKey','',time() - 864000,'/',COOKIEDOMAIN);
    if(isset($_COOKIE['is_unified_overlay1_clicked'])) {
    	setcookie('is_unified_overlay1_clicked','',time() - 864000,'/',COOKIEDOMAIN);
    }
    if(isset($_COOKIE['is_unified_overlay2_clicked'])) {
    	setcookie('is_unified_overlay2_clicked','',time() - 864000,'/',COOKIEDOMAIN);
    }
    if(isset($_COOKIE['is_unified_overlay3_clicked'])) {
    	setcookie('is_unified_overlay3_clicked','',time() - 864000,'/',COOKIEDOMAIN);
    }
	if(isset($_COOKIE['shutUnified'])) {
    	setcookie('shutUnified','',time() - 864000,'/',COOKIEDOMAIN);
    }

	$allCookies = $_COOKIE;
	$prefix = 'collegepredictor_search_';
	$examprefix = 'allExamGuide_';
	foreach($allCookies as $cookieId => $cookieValue) {
		if(strpos($cookieId,'applied_') === 0) {
			setcookie($cookieId,'',time() - 864000,'/',COOKIEDOMAIN);
		}
		if(preg_match('/^'.$prefix.'/', $cookieId) == 1){
            setcookie($cookieId,'',time() - 864000,'/',COOKIEDOMAIN);
        }
        if(preg_match('/^'.$examprefix.'/', $cookieId) == 1){
            setcookie($cookieId,'',time() - 864000,'/',COOKIEDOMAIN);
        }
	} 
	
	setcookie('recommendation_applied','',time() - 864000,'/',COOKIEDOMAIN);	
	
	setcookie('SMARTInterfaceMode','',time() - 864000,'/',COOKIEDOMAIN);
	setcookie('SMARTDualInterface','',time() - 864000,'/',COOKIEDOMAIN);	
	setcookie('comparedCourses','',time() - 864000,'/',COOKIEDOMAIN);
	setcookie('instContResp','',time() - 864000,'/',COOKIEDOMAIN);
	setcookie('courContResp','',time() - 864000,'/',COOKIEDOMAIN);
	setcookie('examGuide','',time() - 864000,'/',COOKIEDOMAIN);
	setcookie('examSubscribe','',time() - 864000,'/',COOKIEDOMAIN);
	if(isset($_COOKIE['signUpFormParams'])) {
    	setcookie('signUpFormParams','',time() - 864000,'/',COOKIEDOMAIN);
    }

    if(isset($_COOKIE['clientAutoLogin'])) {
    	setcookie('clientAutoLogin','',time() - 864000,'/',COOKIEDOMAIN);
    }

    $url = '';
    echo 1;
    exit;
}

	function superUserLogin(){
		global $superUserEmail;
		$this->userStatus = $this->checkUserValidation();
		$data['data'] = $this->userStatus;
        if($this->userStatus!='false'){
       	    $emailId = explode('|',$this->userStatus[0]['cookiestr']);
       	    $data['email'] = $emailId[0];
       	    if(in_array($emailId[0],$superUserEmail)){
       	    	$msg=$this->input->get("msg");
       	    	$data['msg']=$msg;
       	    	$this->load->view('user/superUserLogin',$data);
       	    }else{
				header("location:/enterprise/Enterprise/disallowedAccess");
		    	exit();
		    }
		}else{
			header("location:/enterprise/Enterprise/disallowedAccess");
		    exit();
		}
	}

	function loginAsSuperUser(){
		$this->load->model('user/usermodel');
		$this->load->model('smart/smartmodel');
		$email = $this->input->post('user_email',true);
		$email = "'".$email."'";		 		
		$userBasicInfo = $this->usermodel->getUsersBasicInfoByEmail(array($email));
		$userId = array_keys($userBasicInfo);
		$data = array();
		$getDetail = explode('|',$_COOKIE['user']);
		
		$data['loginById'] = $this->usermodel->getUserIdByEmail($getDetail[0]);
		if(isset($userBasicInfo) && !empty($userBasicInfo)){
			//$_COOKIE['user1'] = $userBasicInfo[$userId]['email'].'|'.$userBasicInfo[$userId]['password'].'|pendingverification';
			$value = $userBasicInfo[$userId[0]]['email'].'|'.$userBasicInfo[$userId[0]]['password'].'|pendingverification';
			setcookie('user',$value,time() + 2592000,'/',COOKIEDOMAIN);
			$data['loginToId'] = $userId[0];
			$this->smartmodel->insertClientLoginDetails($data);

			setcookie('clientAutoLogin',$getDetail[0],time() + 2592000,'/',COOKIEDOMAIN);
			redirect(SHIKSHA_HOME);
		} else {
			redirect('/user/Login/superUserLogin?msg=This email does not exist');
		}
	}
}
