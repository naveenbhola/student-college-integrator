<?php
/**
 * User Class
 * This is the class for all the APIs elated to User like Login, Register, Forgot Password, Rest Password
 * @date    2015-07-16
 * @author  Ankur Gupta
 * @todo    none
*/

class User extends APIParent {

	private $validationObj;
	private $userCommonLib;

	function __construct() {
		parent::__construct();
		$this->load->library(array('UserValidationLib', 'UserCommonLib'));
		$this->validationObj = new UserValidationLib();
        $this->userCommonLib = new UserCommonLib();
	}

	/**
	 * @desc API to check Login details of the user. It will receive Email and password and check if they are correct
	 * @param POST value of email
	 * @param POST value of password
	 * @return JSON string with HTTP Code 200 and Message Success if it is OK
	 * @return JSON string with HTTP Code 404 and Message Failure if Email and password do not match or Email does not exists
	 * @date 2015-07-15
	 * @author Ankur Gupta
	 */
    function login(){

            //Step 1: Fetch the Input from Get/POST
            $password = $this->input->post('password');
            $email = $this->input->post('email');
            //$email = 'cmsadmin@shiksha.com';
            //$password = 'shiksha';
            if(!$this->validationObj->validateLogin($this->response, array('email'=>$email,'password'=>$password))){
                return;
            }
            $ePassword = sha256($password);
            $this->loginCheck($email,$ePassword);
    }




    function loginCheck($email,$ePassword,$cacheDisable=false){
        $this->load->helper('image');
                //Step 3: Fetch the Data from DB + Logic
                $this->load->library('user/Login_client');
                $login_client = new Login_client();
        
                $strcookie = $email.'|'.$ePassword;
                //_P($strcookie); die;
                $Validate = $login_client->validateuser($strcookie,'login',$cacheDisable);
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
                        $cookieData = 
                            array(
                            'cookieName' =>'user',
                            'time' => time() + 2592000,
                            'value' => $value
                            );    

                        
                    }
                }        

                if($Validate != "false" && is_array($Validate))
                {
                        $ownershipchallenged = $Validate[0]['ownershipchallenged'];
                        $abused = $Validate[0]['abused'];
                        if($abused == 1 || $ownershipchallenged == 1)
                        {
                                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                                $this->response->setResponseMsg("Invalid User");
                        }
                        else
                        {
                                /**
                                * Track user login time
                                */
                                $this->load->model('user/usermodel');
                                $this->usermodel->trackUserLogin($Validate[0]['userid']);

                                // reset user Log-out in passwordChangedTracking
                                $apicommonmodel = $this->load->model("common_api/apicommonmodel");
                                $apicommonmodel->resetUserLogOutFlag($Validate[0]['userid']);

                //Check whether to Show Intermediate Page on the App or NOT
                
                $showIntermediatePage = SHOW_INTERMEDIATE_PAGE;
                

                if(SHOW_INTERMEDIATE_PAGE == TRUE){
                    $showIntermediatePage = $this->userCommonLib->showIntermediatePageOrNot($Validate[0]['userid']);
                }

                $shortRegistration = $this->userCommonLib->isUserHasProfile($Validate[0]['userid']);;

        
                $userArray = array(
                            'userId' => $Validate[0]['userid'],
                            'firstName' => $Validate[0]['firstname'],
                            'lastName' => $Validate[0]['lastname'],
                            'picUrl' => checkUserProfileImage($Validate[0]['avtarurl']),
                            'displayName' => $Validate[0]['displayname'],
                            'email' => $email,
                            'cookies' => array($cookieData),
                            'showIntermediate' => $showIntermediatePage,
                            'shortRegistration' => $shortRegistration
                        );

                        $this->response->setBody($userArray);


                                
                unset($userArray['cookies']);                
                unset ($userArray['picUrl']);
                unset ($userArray['showIntermediate']);
                $this->response->setAuthChecksum($Validate[0]['userid'], $email, $Validate[0]['firstname'], $Validate[0]['lastname'],$Validate[0]['displayname']);
                        }
                }
                else
                {
            $this->response->setStatusCode(STATUS_CODE_FAILURE);
            $this->response->setResponseMsg("Invalid Email / Password");
                }
        
                //Step 4: Return the Response
                $this->response->output();
        }





        function getDataFromCheckSum(){
            $checkSum = $this->input->post('checkSum');
            if(!$checkSum){
                return;
            }
            $apiSecurityLib = $this->load->library("common_api/APISecurityLib");
            $this->userDetails = $this->apiSecurityLib->decrypt($checkSum);
            $this->userDetails = json_decode($this->userDetails,true);            
            $this->load->model('user/usermodel');
            $userId = $this->userDetails['userId'];
            if(is_nan($userId) || $userId<=0){
                return;
            }
            $data = $this->userCommonLib->getUserDetailById($userId);
            if(empty($data)){
                return;
            }   

            $this->loginCheck($data['email'],$data['ePassword'],true);
        }    



        /**
         * @desc API to Reset the password of a user
         * @param POST value of email
         * @param POST value of password
	 * @param POST value of uname (this will be a unique random key stored in tuser. This is used to check the validity of the user)
         * @return JSON string with HTTP Code 200 and Message Success if it is OK
         * @return JSON string with HTTP Code 404 and Message Failure if Email does not exists OR user ownership has been challenged or he is not a verified user
         * @date 2015-07-15
         * @author Ankur Gupta
         */
        function resetPassword(){
        
                //Step 1: Fetch the Input from Get/POST
                $identifier = $this->input->post('identifier');
                $password = $this->input->post('password');
                


                //$identifier="ODQ3YTFiN2YwMjBiNzc4OTg5M2Y0OTFjMGVlN2JhYjh8fGh0dHA6Ly93d3cuc2hpa3NoYS5jb20vc2hpa3NoYS9pbmRleC98fHBpc2Nlcy5hbmt1ckBnbWFpbC5jb20=";
                //$password="P@ssw0rd";


                /*
                $identifier = base64_decode($identifier);
                $uname_array = explode("||", $identifier);
                if(is_array($uname_array)){
                    $uname = $uname_array[0];
                    $email= $uname_array[2];
                }
                */

                $dataArr = explode("_",$identifier);
                $finalData = array();
                for($i = 0 ; $i < count($dataArr); $i++) {
                    $tempArr = explode("~",$dataArr[$i]);
                    $finalData[$tempArr[0]] = $tempArr[1];
                }

                //Fetch uname from URL
                if($finalData['url']) {
                    $finalData['url'] = str_replace(' ','+',$finalData['url']);
                    $url = base64_decode($finalData['url']);
                    $redirectUrl = $url;

                    //Now find the UName from URL
                    $tempArray = explode("?",$redirectUrl);
                    $tempArray = explode("/",$tempArray[0]);
                    $id = $tempArray[count($tempArray) - 1];
                    $id = base64_decode($id);
                    $uname_array = explode("||", $id);
                    $uname = $uname_array[0];
                }

                //Fetch Email
                $email = isset($finalData['email'])?$finalData['email']:'';
                if($email!='') {
                    if($finalData['mailer']) {
                        $this->load->model('user/usermodel');
                        $email = $this->usermodel->getDecodedEmail($email);
                    }
                }
                
                
                //Step 2: Validate all the fields
                if(! $this->validationObj->validateResetPassword($this->response, array('uname'=>$uname,
		                                                                'email'=>$email,
                		                                                'password'=>$password))){
                    return;                                                
                }

                //Step 3: Fetch the Data from DB + Logic
                $this->load->library('user/Register_client');        
                $shaPassword = sha256($password);

                $appID = 1;
                $Registerclient = new Register_client();
                $response = $Registerclient->resetPassword($appID,$uname,$email,$shaPassword,$password);

                if(is_array($response))
                {
                    if($response[0]['abused'] == 1 || $response[0]['ownershipchallenged'] == 1)
                    {
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Invalid User");
                    }
                    else
                    {
                        /**
                        * Track user login time
                        */
                        $this->load->model('user/usermodel');
                        $this->usermodel->trackUserLogin($response[0]['userId']);

                        //Check whether to Show Intermediate Page on the App or NOT
                        $showIntermediatePage = SHOW_INTERMEDIATE_PAGE;
                        if(SHOW_INTERMEDIATE_PAGE == TRUE){
                                  $showIntermediatePage = $this->userCommonLib->showIntermediatePageOrNot($response[0]['userId']);
                        }

			$userData = $this->usermodel->getUserBasicInfoById($response[0]['userId']);
                        $userArray = array(
                                                   'userId'      => $userData['userid'],
                                                   'firstName'   => $userData['firstname'],
                                                   'lastName'    => $userData['lastname'],
                                                   'picUrl'   => $userData['avtarimageurl'],
                                                   'displayName' => $userData['displayname'],
                                                   'email'       => $userData['email'],	
                                                   'showIntermediate' => $showIntermediatePage
                                           );
                         $this->response->setBody($userArray);
                         unset ($userArray['picUrl']);
                         unset ($userArray['showIntermediate']);
                         $this->response->setAuthChecksum($userData['userid'], $email, $userData['firstname'], $userData['lastname'],$userData['displayname']);

                        // reset user Log-out in passwordChangedTracking
                        $apicommonmodel = $this->load->model("common_api/apicommonmodel");
                        $apicommonmodel->resetUserLogOutFlag($userData['userid']);
                    }
                }
                else{
			$this->response->setStatusCode(STATUS_CODE_FAILURE);
			$this->response->setResponseMsg("Something went wrong. Please try again.");                
                }
        
                //Step 4: Return the Response
                $this->response->output();
        }

        /**
         * @desc API to send a mailer to user in case he has forgotten his password
         * @param POST value of email
         * @return JSON string with HTTP Code 200 and Message Success if it is OK
         * @return JSON string with HTTP Code 404 and Message Failure if Email does not exists OR he is not a verified user
         * @date 2015-07-15
         * @author Ankur Gupta
         */
        function forgotPassword(){
        
                //Step 1: Fetch the Input from Get/POST
                $email = $this->input->post('email');

                //Step 2: Validate all the fields
                if(! $this->validationObj->validateForgotPassword($this->response, array('email'=>$email)) ){
                    return;                                                
                }

                //Step 3: Fetch the Data from DB + Logic
                $appID = 1;
		$response = Modules::run('user/Userregistration/sendResetPasswordNewMail',$email,SHIKSHA_HOME."/shiksha/index/");
		if($response == 'false' || $response == 'deleted'){
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Invalid User");
                }
		else{
			$this->response->setResponseMsg("Forgot password mail sent successfully.");
		}
		/*
                $this->load->library('user/Register_client');        
                $Registerclient = new Register_client();
                $responseArray = $Registerclient->getUserIdForEmail($appID,$email);


                if(!is_array($responseArray))
                {
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Invalid User");
                }
                else if($responseArray[0]['ownershipchallenged'] == 1 || $responseArray[0]['abused'] == 1)
                {
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Invalid User");
                }
                else
                {
                        $userPrefDetails = $Registerclient->getPreferencesForUser($appId,$responseArray[0]['userId']);
                        $DesiredCourse = $userPrefDetails[0]['DesiredCourse'];
                        if($DesiredCourse == 2) {
                            $this->userCommonLib->sendForgotPasswordFullTimeMBA($responseArray, $email);
                        }
                        else{
                            $this->userCommonLib->sendForgotPasswordNewEmail($responseArray, $email);
                        }

                        $this->response->setResponseMsg("Forgot password mail sent successfully.");
                }
                */

                //Step 4: Return the Response
                $this->response->output();
        }
        
        /**
         * @desc API to Register a user on Shiksha
         * @param POST value of email
         * @param POST value of password
    	 * @param POST value of firstName
    	 * @param POST value of lastName
         * @return JSON string with HTTP Code 201 and Message Success if the user is successfully registered
         * @return JSON string with HTTP Code 404 and Message Failure if Email is already registered/User can not use the name with word 'Shiksha'
         * @date 2015-07-15
         * @author Ankur Gupta
         */
        function register(){
                //Step 1: Fetch the Input from Get/POST
                $email = $this->input->post('email');
                $firstName = $this->input->post('firstName');
                $lastName = $this->input->post('lastName');
                $password = $this->input->post('password');

                //Step 2: Validate all the fields
                if(! $this->validationObj->validateRegister($this->response, array('email'=>$email, 
										'firstName'=>$firstName, 
										'lastName'=>$lastName, 
										'password'=>$password)) ){
                    return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $userDataArr = array("email" => $email,
                                     "firstName" => $firstName,
                                     "lastName" => $lastName,
                                     "password" => $password
                                     );

                // register user
                if(!$this->userCommonLib->registerUser($userDataArr, $this->response)){
                    return;
                }

                // track data
                $this->response->_setCommonParams();
                $body = $this->response->getBody();
                $this->_trackAPI($body, "register");

                //Step 4: Return the Response
                $this->response->output();

        }
	
	
	
	        /**
         * @desc API to insert userProfile data in DB.
         * @param POST value of userType,name of organisation/
         * @return JSON string with HTTP Code 200 and Message Success if it is OK
         * @date 2015-07-21
         * @author Yamini Bisht
         */
        function insertUserProfileData(){
        
                //Step 1: Fetch the Input from Get/POST
		$expertType = (isset($_POST['expertType']) && $_POST['expertType']!='') ? $this->input->post('expertType') : '';
		$organisationName =  (isset($_POST['organisationName']) && $_POST['organisationName']!='') ? $this->input->post('organisationName') : '';
		
		$userProfileType = "Consumer";
		if($expertType != ''){
			$userProfileType = "Producer";
		}
                $Validate = $this->getUserDetails();

		$userId = isset($Validate['userId'])?$Validate['userId']:'';

                //Step 2: Validate all the fields
                if( ! $this->validationObj->validateUserProfileData($this->response,  array('userId'=>$userId,
												'expertType'=>$expertType,
											       'organisationName'=>$organisationName)) ){
                    return;
                }

		//Step 3: Insert user data in DB
		$this->load->model('user/usermodel');
		
		$ifAlreadyExistUser = $this->usermodel->showIntermediatePageOrNot($Validate['userId']);
	
		if(!empty($ifAlreadyExistUser)){
			
			$this->usermodel->updateUserProfileData($Validate['userId'],$userProfileType,$expertType,$organisationName);
			
		}else{
		
			$this->usermodel->insertAppUserProfileData($Validate['userId'],$userProfileType,$expertType,$organisationName);
		}
		
		//Step 3.1 : send mail of new contributor to internal team
		if($userProfileType == 'Producer'){
                        $this->usermodel->sendMailForContributorToInternalTeam($userId);
		}
		
		
		//Step 4: Return the Response
		$this->response->output();
        }

        /**
         * @desc API to enable user login using fb crentials. Firstly, it will be registering the user to shiksha.
         * @param POST value of facebookAuthToken
         * @param POST value of email
         * @param POST value of firstName
         * @param POST value of lastName
         * @param POST value of facebookPicUrl
         * @param POST value of facebookUserId
         * @return JSON string containing user details 
         * @author Romil Goel
         */
        function fbLogin(){
            $usermodel = $this->load->model('user/usermodel');
            $userapimodel = $this->load->model('userapimodel');
            

            //Step 1: Fetch the Input from Get/POST
            $facebookAccessToken = $this->input->post('facebookAccessToken');
            $email               = $this->input->post('email');
            $firstName           = $this->input->post('firstName');
            $lastName            = $this->input->post('lastName');
            $facebookPicUrl      = $this->input->post('facebookPicUrl');
            $facebookUserId      = $this->input->post('facebookUserId');

            //Step 2: Validate all the fields
            if(! $this->validationObj->validateFBLogin($this->response, array('facebookAccessToken'=>$facebookAccessToken, 
                                        'email'=>$email, 
                                        'firstName'=>$firstName, 
                                        'lastName'=>$lastName, 
                                        'facebookPicUrl'=>$facebookPicUrl,
                                        'facebookUserId'=>$facebookUserId)) ){
                    return;
            }
            
            //Step 3: Fetch the Data from DB + Logic
            //// check if the coming user email-id is already registered or not 
            // 1. if registered then go to login flow + update the fbauthtoken + verify the details from 
            //    fb and if email-id matches then do the login of the user(also update the user profile pic and name)
            // 2. if not registered then 
            //      - get data from fb using authtoken
            //      - verify the email id coming, if not matches then throw the error response
            //      - if matches then register the user and get  a user id 
            //      - generate the authchecksum for this user
            //      - return the response


            // check if email-id is registered or not
            $userId = $usermodel->getUserIdByEmail($email);
            if(!empty($userId)){

                // verify details from facebook
                $response = $this->userCommonLib->getFBUserDetailsByAccessToken($facebookAccessToken);
                if(array_key_exists("error", $response)){
                    $this->response->setStatusCode(STATUS_CODE_FAILURE);
                    $this->response->setResponseMsg($response['error']['message']);
                    $this->response->output();
                    return;
                }

                // if email doesn't matches with the given email then throw error
                if($response['email'] != $email){
                    $this->response->setStatusCode(STATUS_CODE_FAILURE);
                    $this->response->setResponseMsg("Log-in Failed. Email-id didn't match.");
                    $this->response->output();
                    return;
                }

                // get the user data
                // $userData = $usermodel->getUserBasicInfoById($userId);
                $userDataObj               = $usermodel->getUserById($userId);

                $userData                  = array();
                $userData['userid']        = $userDataObj->getId();
                $userData['email']         = $userDataObj->getEmail();
                $userData['firstname']     = $userDataObj->getFirstName();
                $userData['lastname']      = $userDataObj->getLastName();
                $userData['displayname']   = $userDataObj->getDisplayName();
                $userData['avtarimageurl'] = $userDataObj->getAvatarImageURL();
                $userData['userFlags']     = $userDataObj->getFlags();

                // if user is marked as abused then, don't allow login
                if($userData['userFlags'] && $userData['userFlags']->getAbused() == '1'){
                    $this->response->setStatusCode(STATUS_CODE_FAILURE);
                    $this->response->setResponseMsg("Invalid User");
                    $this->response->output();
                    return;
                }

                // track user-login
                $this->usermodel->trackUserLogin($userId);

                // update the fb token here
                $userapimodel->updateFBAccessToken($userId, $facebookAccessToken, $response['id']);

                // set authchecksum
                $this->response->setAuthChecksum($userData['userid'], $userData['email'], $userData['firstname'], $userData['lastname'], $userData['displayname']);

                // update user's profile pic
                $image = $this->userCommonLib->updateUserAvtarImage($userId, $facebookPicUrl, $userData['avtarimageurl']);

                // check for intermediate page
                $showIntermediatePage = SHOW_INTERMEDIATE_PAGE;
                if(SHOW_INTERMEDIATE_PAGE == TRUE){
                    $showIntermediatePage = $this->userCommonLib->showIntermediatePageOrNot($userId);
                }

                // reset user Log-out in passwordChangedTracking
                $apicommonmodel = $this->load->model("common_api/apicommonmodel");
                $apicommonmodel->resetUserLogOutFlag($userId);

                $responseBody = array("userId"           => $userData['userid'],
                                      "email"            => $userData['email'],
                                      "firstName"        => $userData['firstname'],
                                      "lastName"         => $userData['lastname'],
                                      "picUrl"           => $image,
                                      "showIntermediate" => $showIntermediatePage );

                $this->response->setBody($responseBody);
            }
            // new user
            else{
                    // get details from fb
                    $response = $this->userCommonLib->getFBUserDetailsByAccessToken($facebookAccessToken);

                    // if email doesn't matches with the given email then throw error
                    if($response['email'] != $email){
                        $this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("Email-id didn't match !!!");
                        $this->response->output();
                        return;
                    }

                    // register the user
                    $userDataArr = array("email"      => $email,
                                         "firstName"  => $firstName,
                                         "lastName"   => $lastName,
                                         "password"   => ""
                                     );

                    // register user
                    if(!$this->userCommonLib->registerUser($userDataArr, $this->response)){
                        return;
                    }

                    // get the user details
                    $responseArr = $this->response->getBody();

                    // insert the fb accesstoken for this user
                    $userapimodel->insertFBAccessToken($responseArr['userId'], $facebookAccessToken, $response['id']);

                    // upload facebook pic image to shiksha server and make their variants(_s, _m, _t)
                    $profilePic = $this->userCommonLib->uploadUserFBProfilePic($responseArr['userId'], $facebookPicUrl);
                    
                    // update user's profile pic url
                    $userapimodel->updateUserAvatarUrl($responseArr['userId'], $profilePic);

                    // insert into redis queue to send notifications to his/her friends about his joining
                    $this->load->library("Notifications/NotificationContributionLib");
                    $notificationContributionLib = new NotificationContributionLib();
                    if($responseArr['userId'])
                        $notificationContributionLib->addFBFriendNotificationsToRedis($responseArr['userId']);

                    $this->response->addBodyParam("picUrl", $profilePic);
                    $this->response->addBodyParam("showIntermediate", SHOW_INTERMEDIATE_PAGE);
            }

            //Step 4: Return the Response
            $this->response->output();
        }
	
	
	function getUserFeedBackData(){
		
		//step 1:Fetch the Input from GET/POST
		$usermodel = $this->load->model('user/usermodel');
		
		$Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';
		$feedbackText = isset($_POST['feedbackText'])?$this->input->post('feedbackText'):'';
		$usefulness = isset($_POST['usefulness'])?$this->input->post('usefulness'):0;
		$easeOfUser = isset($_POST['easeOfUser'])?$this->input->post('easeOfUser'):0;
		$lookAndFeel = isset($_POST['lookAndFeel'])?$this->input->post('lookAndFeel'):0;
		
		//step 2:validate all the fields
		if(! $this->validationObj->validateFeedBackFormData($this->response, array('userId'=>$userId,
											   'feedbackText'=>$feedbackText,
											   'usefulness'=>$usefulness,
											   'easeOfUser'=>$easeOfUser,
											   'lookAndFeel'=>$lookAndFeel))){
                        
                        return;
                }
		
		//step 3:insert feedBack into DB
		if($feedbackText != '' || ($usefulness != 0) || $easeOfUser != 0 || $lookAndFeel != 0){
			$usermodel->insertUserFeedBack($userId,$feedbackText,$usefulness,$easeOfUser,$lookAndFeel);
            $this->userCommonLib->sendInterFeedbackMailers($userId,$feedbackText,$usefulness,$easeOfUser,$lookAndFeel);
			$this->response->setResponseMsg("Thanks for your feedback. We promise we will come back stronger."); 
		}else{
			$this->response->setStatusCode(STATUS_CODE_FAILURE);
                        $this->response->setResponseMsg("One of the field is mandatory."); 
                        $this->response->output();
                        return;	
		}
		
		//Step 4: Return the Response
                $this->response->output();
		
	}


        /**
         * @desc API to logout user
         * @date 2015-10-29
         * @author Romil Goel
         */
        function logout(){
            //Fetch the Input from Get/POST
            $userId   = $this->input->post("userId");
            $deviceId = $this->getDeviceUID();

            if(empty($userId)){
                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                $this->response->setResponseMsg("Please specify user-id");
                $this->response->output();
                return;
            }

            // Remove GCM id from devicesAuthKey table
            $apicommonmodel = $this->load->model("apicommonmodel");
            $apicommonmodel->updateDeviceGCMDetails($deviceId, null, null);

            $usermodel = $this->load->model("user/usermodel");
            $usermodel->trackLogout($userId);

            //Return the Response
            $this->response->output();
        }
}
?>

