<?php 
/**
   Copyright 2007 Info Edge India Ltd

   $Rev::               $:  Revision of last commit
   $Author: raviraj $:  Author of last commit
   $Date: 2010/05/06 12:38:02 $:  Date of last commit
   This class provides the Blog Server Web Services.
   The blog_client.php makes call to this server using XML RPC calls.

   $Id: Userregistration.php,v 1.186 2010/05/06 12:38:02 raviraj Exp $:

 */
class Userregistration extends MX_Controller{
    
    /**
     * Function to load libraries and validate user
     *
     * @param $redirectUrl
     * @param $editform
     */
    function index($redirectUrl = '',$editform)
    {
        /*
            This function is Deprecated. see https://172.16.3.160/bugzilla/show_bug.cgi?id=48630
        */
        redirect('/', 'location', 301);
        exit;
        $this->load->library('ajax');
        $this->load->helper('url');
        $this->load->library('Register_client');
        $this->load->library('Category_list_client');
        $Validate = $this->checkUserValidation();
        $data['validateuser'] = $Validate;
        $url = '';
        if($Validate == "false"){
            $editform = 0;
        }
        else{
            $editform = 1;
            if($redirectUrl != '')
            {
                $fullUrl  = $_SERVER['REQUEST_URI'];
                $urlComponents = parse_url($fullUrl);
                $host = $urlComponents['host'];
                $pathParams = explode("/",$urlComponents['path']);
                $redirectUrlSegments = $pathParams[4];
                for($i = 5; $i <(count($pathParams)-1) ; $i++){
                    $redirectUrlSegments .= "/".$pathParams[$i];
                }
                $url = base64_decode($redirectUrlSegments);
            }
            else
            {
                $url = '/user/MyShiksha';
            }
        }

        $data['url'] = $url;
        $data['success'] = 'showRegistrationResponse';
        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree(1);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }
        $data['completeCategoryTree'] = json_encode($categoryForLeftPanel);
        $registerclient = new Register_client();
        if(!isset($editform))
            $data['editform'] = 0;
        else
            $data['editform'] = $editform;
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $subCategoryList = $categoryClient->getSubCategories(1,1);
        $data['subCategories'] = $subCategoryList;
        $this->load->view('user/Registration',$data);
    }

    /**
     * Function to load libraries and helpers
     */
    function init()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('ajax');
        $this->load->library('Register_client');
        $this->load->library('Category_list_client');
    }

    /**
     * Function to send the reminder
     */
    function sendReminder()
    {
        ini_set('memory_limit','256M');
        set_time_limit(0);
        $this->init();
        $login_client = new Register_client();
        $response = $login_client->sendReminder();
        echo $response;
    }

    /**
     * Function to change email id
     *
     * @param $email new email id
     * @param $secCode
     */
    function changeEmail($email,$secCode= '')
    {
        $this->init();
        $login_client = new Register_client();
        $appId = 1;
        $Validate = $this->checkUserValidation();
        if($Validate == "false"){
            echo 'Invalid user';
        }
        if(verifyCaptcha('seccodechangeemail',$secCode,1))
        {
            $response = $login_client->changeEmail($appId,$Validate[0]['userid'],$email);
            echo $response;
        }
        else
            echo 'code';
    }

    /**
     * Function called when forgot password is clicked
     *
     * @param $id
     * @param $isStudyAbroad flag to check if user is study abroad user
     */
    function Forgotpassword($id, $isStudyAbroad=0)
    {
        $this->init();
        if(isMobileRequest()){
            $link ='/user/Userregistration/ForgotpasswordNew/'.$id;

            redirect($link);
            return;
        }
        if(!$isStudyAbroad){
            $id = base64_decode($id);
            $uname_array = explode("||", $id);
            $id = $uname_array[0];
            $data['uname'] = $id;
            $email = base64_encode($uname_array[2]);
            $data['useremail'] = $email;

            $isValidEmail = validateEmailMobile('email', $uname_array[2]);
            if($isValidEmail) {
                $link = SHIKSHA_HOME."/shiksha/index?uname=$id&resetpwd=1&usremail=$email";  
            } else {
                $link = SHIKSHA_HOME;
            }
            redirect($link);
        }else{
            $data['hideTrackingFields'] = true;
			$data['hideLoginSignupBar'] = 'true';
			$data['loggedInUserData'] = false;
			$data['hideGNB']          = 'true';
			$data['hideHTML']		 = 'true';
            $data['logoCustomCSS']	 = 'text-align: center;width: 100%;';	
            $data['beaconTrackData'] = array(
                'pageIdentifier' => 'forgotPasswordPage',
                'pageEntityId' => 0,
                'extraData' => null
            );
            $data['uname'] = $id;
            $this->load->view('user/forgot',$data);
        }
    }

    /**
     * Function to send reset password mail
     *
     * @param $email email id on which reset password link has to be sent
     * @param $requested_page
     */
    function sendResetPasswordNewMail($email,$requested_page) {

        if(empty($email)){
            $email = $this->input->post('email');
        }
    	
        //$email =  'wdkjkjdjs@djnsjdsdnjsdnsd.com';
        //$requested_page = "http://localshikhsa.com/marketing/Marketing/form/pageID/230";
    	if(empty($requested_page)) {
    		$requested_page = $_SERVER["HTTP_REFERER"];
            if(($requested_page == SHIKSHA_HOME) || ($requested_page == SHIKSHA_HOME.'/') || ($requested_page == SHIKSHA_HOME.'/#')) {
                $requested_page = SHIKSHA_HOME."/shiksha/index/";
            } else if (strpos($requested_page, 'search') !== false) {
                // $exp_requested_page = explode("keyword=",$requested_page);
                // $exp_requested_page_1 = explode("&",$exp_requested_page[1]);
                // $keyword = $exp_requested_page_1[0];
                // $requested_page = SHIKSHA_HOME."/search/index/?keyword=".$keyword;
                $requested_page = SHIKSHA_HOME."/shiksha/index/";
            }
    	} else if($requested_page == 'homepage') {
             $requested_page = SHIKSHA_HOME."/shiksha/index/";
        }
        
    	$this->_sendResetPasswordNewMail($email,$requested_page);
    }

    /**
     * Function to send reset password mail
     *
     * @param $email email id on which reset password link has to be sent
     * @param $requested_page
     * @param $link link in the email to reset password
     * @param $is_mobile flag to check if user is mobile user
     */
    function _sendResetPasswordNewMail($email,$requested_page, $link = '', $is_mobile = 'N') {

    	$this->init();
    	$this->load->library('mail_client');
    	$mail_client = new Mail_client();
    	$appId = 1;
    	$register_client = new Register_client();
    	$response = $register_client->getUserIdForEmail($appId,$email);
        //headers to send mail in the correct format using mail()
    	$headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Shiksha <info@shiksha.com>' . "\r\n";
    	    	
    	if(!is_array($response)) {
    		echo 'false';
    	} else {
        	if($response[0]['ownershipchallenged'] == 1 || $response[0]['abused'] == 1)
        	{
        		if($response[0]['ownershipchallenged'] == '1' || $response[0]['abused'] == 1)
        		echo 'deleted';
        	}
        	else
        	{
        		$this->load->library('Alerts_client');
        		$alertClient = new Alerts_client();
        		$id = base64_encode($response[0]['id'].'||'.$requested_page.'||'.$email);
                if(!empty($link)) {
                    $resetlink = $link."/".$id;
                } else {
               		$resetlink = SHIKSHA_HOME . '/user/Userregistration/ForgotpasswordNew/'.$id;
                }             

                $mailerData = array();
                $mailerData['firstName'] = $response[0]['firstname'];
                $mailerData['resetlink'] = $resetlink;
                $mailerData['headers'] = $headers;
                $mailerData['mailer_name'] = 'ForgotPassword';
                
                $response = \Modules::run('systemMailer/SystemMailer/sendForgotPasswordMailer',$email,$mailerData);                

                echo $response;
        	}
        }

    }
    
    /**
     * Function called when forgot password is clicked
     *
     * @param $id
     */
    function ForgotpasswordNew($id)
    {
    	$this->init();

        $id = base64_decode($id);
        $uname_array = explode("||", $id);
        $uname = $uname_array[0];
        $link = $uname_array[1];
        $email= $uname_array[2];

        if($uname_array[3] !='' && isset($uname_array[3])){
            $usergroup = base64_encode($uname_array[3]);
        }else{
            $usergroup = base64_encode('user');
        }
        
        $email = base64_encode($email);
        
        $isValidEmail = validateEmailMobile('email', $uname_array[2]);
        if($isValidEmail) {    	
            if(strpos($link,'?') === false) {
        		$link .= "?uname=$uname&resetpwd=1&usremail=$email&usrgrp=$usergroup";
        	} else {
        		$link .= "&uname=$uname&resetpwd=1&usremail=$email&usrgrp=$usergroup";
        	}
        } else {
            $link = SHIKSHA_HOME;
        }
    	
    	redirect($link);
    }

    /**
     * Function to send reset password mail
     *
     * @param $email email id on which reset password link has to be sent
     */
    function sendResetPasswordMail($email, $isStudyAbroad = 0)
    { 
        if(empty($email)){
            $email = $this->input->post('email');
        }

        $this->init();
        $this->load->library('mail_client');
        $mail_client = new Mail_client();
        $appId = 1;
        $register_client = new Register_client();
        $response = $register_client->getUserIdForEmail($appId,$email);
        //headers to send mail in the correct format using mail()
        $headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <info@shiksha.com>' . "\r\n";
        if(!is_array($response))
        {
            echo 'false';
            exit;

        }
        if($response[0]['ownershipchallenged'] == 1 || $response[0]['abused'] == 1)
        {
            if($response[0]['ownershipchallenged'] == '1' || $response[0]['abused'] == 1)
                echo 'deleted';
        }
        else
        {
            if($_COOKIE['mobile_site_user'] == 'abroad' || $isStudyAbroad == 1){
                $resetlink = SHIKSHA_STUDYABROAD_HOME . '/user/Userregistration/Forgotpassword/'.$response[0]['id'].'/1';
            }else{
                $resetlink = SHIKSHA_HOME . '/user/Userregistration/ForgotpasswordNew/'.$response[0]['id'].'/1';
            }            

            $mailerData = array();
            $mailerData['firstName'] = $response[0]['firstname'];
            $mailerData['resetlink'] = $resetlink;
            $mailerData['headers'] = $headers;
            $mailerData['mailer_name'] = 'ForgotPassword';
            
            $response = \Modules::run('systemMailer/SystemMailer/sendForgotPasswordMailer',$email,$mailerData);                

            echo $response;
          
        }
    }

    /**
     * Function to reset password
     */
    function resetPassword()
    {
        $this->init();
        $name = $this->input->post('uname', true);
        $uname = $this->input->post('email', true);
        $password = $this->input->post('passwordr',true);
        $shaPassword = sha256($password);

        $remember = $this->input->post('remember', true);
        if(!($remember == "on"))
            $remember = "off";
        $appID = 1;

        $this->load->model('user/usermodel');
        $emailId = array($uname);
        $basicInfo = $this->usermodel->getUsersBasicInfoByEmail($emailId);
        foreach ($basicInfo as $value){
            $oldPassword = $value['password'];
        }
        $strcookie = $uname.'|'.$oldPassword;
        $key = "lu_".md5('validateuser'.$strcookie.'login');
        $cacheLib = $this->load->library('cacheLib');
        $cacheLib->clearCacheForKey($key);

        $Registerclient = new Register_client();
        $response = $Registerclient->resetPassword($appID,$name,$uname,$shaPassword,$password);
        $value = $uname.'|'.$shaPassword;
        if(is_array($response))
        {
            if($response[0]['abused'] == 1 || $response[0]['ownershipchallenged'] == 1)
            {
                echo 'invalid';
        exit;
            }
            else
            {
                if($response[0]['emailverified'] == 1)
                    $value .= "|verified";
                else
                {
                    if($response[0]['hardbounce'] == 1)
                        $value .= "|hardbounce";
                    if($response[0]['softbounce'] == 1)
                        $value .= "|softbounce";
                    if($response[0]['pendingverification'] == 1)
                        $value .= '|pendingverification';
                }
            }
           $userId = $response[0]['userId'];
            $response = 1;
            setcookie('user',$value,time() + 2592000,'/',COOKIEDOMAIN);

            if($userId > 0 && $userId != '') {                
                $this->usermodel->trackUserLogin($userId);
            }
        }

        echo $response;

    }

    /**
     * Function to populate localities for city
     *
     * @param $id city id
     */
    function populatelocalitycities($id)
    {
	$this->init();
	$this->load->library('category_list_client');
	$categoryClient = new Category_list_client();
        $appId = 1;
        $response = $categoryClient->getLocalitiesForCityId($appId,$id);
        echo json_encode($response);
    }
    
    /**
     * Function to populate colleges
     *
     * @param $city city id
     * @param $institute institute id
     */
    function populatecolleges($city,$institute)
    {
        $this->init();
        $city =  str_replace('shiksha123', '/', $city);

        $registerClient = new Register_client();
        $appId = 1;
        $response = $registerClient->populatecolleges($appId,$city,$institute);
        $cities = array('results'=>$response);
        echo json_encode($cities);
    }
    
    /**
     * For it marketing page
     *
     * @param string $secCodeSessionVar
     */
    function ITMarketingPage($secCodeSessionVar='seccodehome') {
	error_log('LDB start');
        $this->init();
        $registerClient = new Register_client();
        /* useless var. at least in this case !!! */
        $appId = 1;
        /* build an array for form values  in formatted manner */
        $finalArray = array();
        /* get user current status */
        $Validate = $this->checkUserValidation();
        /* boolen flag */
        $update_flag = $this->input->post('flag_marketing_overlay', true);
	/* pref id */
        $flag_prefId = trim($this->input->post('flag_prefId', true));
        /* flag that identify it_degree or it_courses */
        $mPageName = $this->input->post('mpagename', true);
	/* flag that tells page name */
	$marketingpagename = $this->input->post('marketingpagename', true);

        /* if request come from overlay wala form */
        if ((!empty($update_flag))&& ($update_flag == 'true')) {
            error_log('  LDBSERVER :: LDB call second overlay');
            $finalArray['referer'] = $_SERVER['HTTP_REFERER'];
            $finalArray['browser'] = $_SERVER['HTTP_USER_AGENT'];
            // user age
            $user_age = $this->input->post('quickage', true);
            if (!empty($user_age)) {
                $finalArray['age'] = $user_age;
            }
            // Female,Male
            $user_gender = $this->input->post('quickgender', true);
            if (!empty($user_gender)) {
                $finalArray['gender'] = $user_gender;
            }
            // other details text area
            $otherdetails = $this->input->post('otherdetails', true);
            if (!empty($otherdetails) && (trim($otherdetails) != 'Specify any other detail about your course and institute preference.')) {
                $finalArray['userDetail'] = $otherdetails;
            }
            // desired specialization id
            $desired_specialization_id = $this->input->post('specializationId', true);
            if (is_array($desired_specialization_id))
	    {
	      foreach ($desired_specialization_id as $value) {
		$finalArray['specializationId'][] = $value;
	      }
	    }
	    if ($mPageName != 'graduate_course' ) {
		// 10th Details
		$xii_passing_year = $this->input->post('10_com_year_year', true);
		$xii_percentage = $this->input->post('10_ug_detials_courses_marks', true);
		// 10th Stream [science_arts,science_commerce,science_stream]
		$xii_stream = $this->input->post('science_stream', true);
		if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
		    $finalArray['instituteId'][] = '';
		    $finalArray['marksType'][] = 'percentage';
		    $finalArray['level'][] = '12';
		    $finalArray['city'][] ='';
		    $finalArray['country'][] ='2';
		    if (!empty($xii_stream)) {
			if ($xii_stream == 'science_arts') {
			    $finalArray['name'][] = 'arts';
			} else if ($xii_stream == 'science_commerce') {
			    $finalArray['name'][] = 'commerce';
			} else {
			    $finalArray['name'][] = 'science';
			}
		    }else{
			$finalArray['name'][] = '';
		    }
		    if (!empty($xii_percentage)) {
			$finalArray['marks'][] = $xii_percentage;
			} else {
			$finalArray['marks'][] = '';
		    }
		    if(!empty($xii_passing_year)) {
		    $finalArray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
		    $finalArray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
		    } else {
		    $finalArray['courseCompletionDate'][] = '';
		    $finalArray['ongoingCompletedFlag'][] = '';
		    }
		}
	    }
            /* Three checkboxes */
            $newsletteremail =  $this->input->post('newsletteremail', true);
            $finalArray['newsletteremail'] = (!empty($newsletteremail))?1:0;
            $viaemail =  $this->input->post('viaemail', true);
            $finalArray['viaemail'] = (!empty($viaemail))?1:0;
            $viamobile = $this->input->post('viamobile', true);
            $finalArray['viamobile'] = (!empty($viamobile))?1:0;

            /* pick password */
            $password = $this->input->post('quickpassword');
            if (!empty($password)) {
                $finalArray['textPassword'] = $password;
                $finalArray['ePassword']  = sha256($ePassword);
            }
            if (!empty($flag_prefId)) {
                $finalArray['prefId'] = $flag_prefId;
            }
            /* already user is registered so pick userid */
            $userId = $Validate[0]['userid'];
            /* make life easier to call this API :) */
            error_log(print_r($finalArray,true)." LDBSERVER :: LDB update user api called");
            $registerClient->updateUser($appId,$finalArray,$userId);
            if (!empty($password)) {
		    $emailArr = explode("|",$Validate[0]['cookiestr']);
		    $emailArr[1] = $finalArray['password'];
		    $value = implode('|',$emailArr);
		    $this->cookie($value);
            }
            echo "update";
        // endif overlay form update
        } else {
            error_log(' LDBSERVER :: LDB call Insert start');

	    $finalArray['newsletteremail'] = 1;
            // other details text area
            $otherdetails = $this->input->post('otherdetails', true);
            if (!empty($otherdetails) && (trim($otherdetails) != 'Specify any other detail about your course and institute preference.')) {
                $finalArray['userDetail'] = $otherdetails;
            }
            /* Work Experience */
            $finalArray['experience'] = $this->input->post('ExperienceCombo', true);
	    if ($mPageName != 'graduate_course' ) {
		/* UG DETAILS */
		$ug_detials_courses =$this->input->post('ug_detials_courses', true);
		if (!empty($ug_detials_courses)) {
		    $finalArray['name'][] = $ug_detials_courses;
			    global $ug_course_mapping_array;
			    foreach($ug_course_mapping_array as $key => $value)
			    {
				    if($value == $ug_detials_courses)
				    {
					    $educationLevel = $key;
					    error_log($educationLevel.'EDUCATIONLEVEL');
					    break;
				    }
			    }
		    if ($this->input->post('Completed', true) == 'completed') {
			$marks = $this->input->post('ug_detials_courses_marks', true);
		    } else {
			$marks = '';
		    }

		    $schoolCombo = '';
		    $citiesofeducation = '';

		    $finalArray['marks'][] = $marks ;
		    $finalArray['instituteId'][] = $schoolCombo;
		    $finalArray['marksType'][] = 'percentage';
		    $finalArray['level'][] = 'UG';
		    $com_year_year = $this->input->post('com_year_year', true);
		    $com_year_month = $this->input->post('com_year_month', true);
		    if (!empty($com_year_year) && !empty($com_year_month)) {
			$finalArray['courseCompletionDate'][] = $this->input->post('com_year_year', true) . '-'.$this->input->post('com_year_month', true) .'-'. '1';
		    } else {
			$finalArray['courseCompletionDate'][] = '';
		    }
		    $finalArray['ongoingCompletedFlag'][] = $this->input->post('Completed', true) == 'completed'?"0":"1";
		    $finalArray['city'][] = $citiesofeducation;
		    $finalArray['country'][] ='2';
		    $finalArray['educationStatus'][] ='live';
		    /* added zone feature Start*/
		    if ($mPageName == 'it_courses') {
			$perferencelocality = $this->input->post('perferencelocality', true);
			$perferencecity = $this->input->post('perferencecity', true);
			$i = 0;
			foreach($perferencelocality as $locality_city_name) {
				if ($perferencelocality[$i] != "") {
				    if ($perferencelocality[$i] == '-1') {
					$newlist = explode(':',$perferencecity[$i]);
					if (in_array($newlist[2],$finalArray['cityId']) == false) {
					    $finalArray['countryId'][] = $newlist[0];
					    $finalArray['stateId'][] = $newlist[1];
					    $finalArray['cityId'][] = $newlist[2];
					    $finalArray['localityId'][] = 0;
					}
				    } else {
					$newlist = explode(':',$perferencelocality[$i]);
					if (in_array($newlist[3],$finalArray['localityId']) == false) {
					    $finalArray['countryId'][] = $newlist[0];
					    $finalArray['stateId'][] = $newlist[1];
					    $finalArray['cityId'][] = $newlist[2];
					    $finalArray['localityId'][] = $newlist[3];
					}
				    }
				}
			$i++;
			}
		    }
		    /* added zone feature End */
		}
	    }
	    if ($mPageName == 'graduate_course' ) {
		// 10th Details
		$xii_passing_year = $this->input->post('10_com_year_year', true);
		$xii_percentage = $this->input->post('10_ug_detials_courses_marks', true);
		// 10th Stream [science_arts,science_commerce,science_stream]
		$xii_stream = $this->input->post('science_stream', true);
		if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
		    $finalArray['instituteId'][] = '';
		    $finalArray['marksType'][] = 'percentage';
		    $finalArray['level'][] = '12';
		    $finalArray['city'][] ='';
		    $finalArray['country'][] ='2';
		    if (!empty($xii_stream)) {
			if ($xii_stream == 'science_arts') {
			    $finalArray['name'][] = 'arts';
			} else if ($xii_stream == 'science_commerce') {
			    $finalArray['name'][] = 'commerce';
			} else {
			    $finalArray['name'][] = 'science';
			}
		    }else{
			$finalArray['name'][] = '';
		    }
		    if (!empty($xii_percentage)) {
			$finalArray['marks'][] = $xii_percentage;
			} else {
			$finalArray['marks'][] = '';
		    }
		    if(!empty($xii_passing_year)) {
		    $finalArray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
		    $finalArray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
		    } else {
		    $finalArray['courseCompletionDate'][] = '';
		    $finalArray['ongoingCompletedFlag'][] = '';
		    }
		}
	    }
            /*modeOfEducation*/
	    if ($mPageName != 'it_courses') {
		$mode_preference  = $this->input->post('mode', true);
		if (is_array($mode_preference)) {
		    foreach ($mode_preference as $value) {
			switch($value) {
			    case 'full_time':
			    $finalArray['modeOfEducationFullTime'] = 'yes';
			    break;
			    case 'part_time':
			    $finalArray['modeOfEducationPartTime'] = 'yes';
			    break;
			    case 'distance':
			    $finalArray['modeOfEducationDistance']= 'yes';
			    break;
			}
		    }
		}
	    }
	    $finalArray['desiredCourse'] = $this->input->post('homesubCategories', true);
	    if ($this->checkDMBACourse($finalArray['desiredCourse']))
	    {
	      $finalArray['desiredCourse']=24;
	      $finalArray['specializationId'][]=$finalArray['desiredCourse'];
	    }
            /* when you plan to start study */
            $finalArray['timeOfStart'] = $this->input->post('plan', true);
            /*
		Residence Location added zone feature
		we need to check if residence city is not present then pass first location
		of preference for redirection url
	    */
	    $residenceLocation  = $this->input->post('citiesofquickreg', true);
	    if (!empty($residenceLocation)) {
		$finalArray['residenceCity']=$this->input->post('citiesofquickreg', true);
	    }

            /* Mobile */
            $finalArray['mobile']= $this->input->post('mobile', true);

            /* Email id */
            $finalArray['email']= $this->input->post('email', true);

            /* First Name */
            $finalArray['firstName']= $this->input->post('firstname', true);

            ##############################################
            /* Loop to check unique displayname START */
            $responseCheckAvail = 1;
            error_log('  LDBSERVER ::  LDB WHILE START'.microtime(true));
            while($responseCheckAvail == 1){
                $displayname = $finalArray['firstName'] . rand(1,100000);
                $responseCheckAvail = $registerClient->checkAvailability($appId,$displayname,'displayname');
            }
            error_log('  LDBSERVER ::  LDB WHILE END'.microtime(true));
            /* Loop to check unique displayname END */
            $finalArray['displayname']= $displayname;
            /* Text password */
            $finalArray['textPassword'] = 'shiksha@'. rand(1,1000000);
            /* MD5 password */
            $finalArray['ePassword']  = sha256($finalArray['textPassword']);
            ##############################################
            /* Preferred Study Location(s) START */
	    $new_CityList = "";
	    if ($mPageName != 'it_courses') {
		$mCityList = $this->input->post('mCityList', true);
		$mCityList = explode(',',$mCityList);
		foreach($mCityList as $value) {
		    if (!empty($value)) {
			$newlist = explode(':',$value);
			if (count($newlist) < 3 ) {
			    continue;
			}
			$finalArray['countryId'][] = $newlist[0];
			$finalArray['stateId'][] = $newlist[1];
			$finalArray['cityId'][] = $newlist[2];
			$finalArray['localityId'][] = 0;
			$new_CityList .= $newlist[2] . ",";
		    }
		}
	    }
            /* :( HACK to trim last , from city list */
            $new_CityList = substr($new_CityList, 0, -1);
            /* Preferred Study Location(s) END */
            /* Get all lead realted data */
            $finalArray['sourcename'] = 'MARKETING_FORM';
            $finalArray['referer'] = $this->input->post('refererreg', true);
            $finalArray['resolution']= $this->input->post('resolutionreg', true);
	    $subCategory = '';
	    if($marketingpagename == 'it') {
		$finalArray['category'] = 10;
	    } else if ($marketingpagename == 'animation') {
		$finalArray['category'] = 12;
	    } else if ($marketingpagename == 'hospitality') {
		$finalArray['category'] = 6;
	    } else if ($marketingpagename == 'science') {
		$finalArray['category'] = 2;
	    } else if ($marketingpagename == 'bba') {
		$finalArray['category'] = 3;
	    } else if ($marketingpagename == 'clinical_research') {
		$finalArray['category'] = 5;
		$subCategory = '154';
	    } else if ($marketingpagename == 'fashion_design') {
		$finalArray['category'] = 8;
		$subCategory = '119';
	    } else if ($marketingpagename == 'mass_communications') {
		$finalArray['category'] = 7;
		$subCategory = '';
	    }
            /* T USER SOURCE INFO */
            $finalArray['referer'] = $_SERVER['HTTP_REFERER'];
            $finalArray['landingpage'] = $this->input->post('loginactionreg', true);
            $finalArray['browser'] = $_SERVER['HTTP_USER_AGENT'];
            /* T USER SOURCE INFO */

	    $city = $finalArray['residenceCity'];
	    $preferredCity = $new_CityList;
            $mcourse = '';
            $city = $finalArray['residenceCity'];
            $firstName = $finalArray['firstName'];
            $mobile = $finalArray['mobile'];
            $age = '';
            $gender  = '';
            $email = $finalArray['email'];
            $displayname = $finalArray['displayname'];
            $mPageName = $this->input->post('mpagename', true);
            $ePassword = $finalArray['ePassword'];
            $password = $finalArray['textPassword'] ;

	    if ($mPageName == 'it_courses')
	    {
		    $perferencecity = array_unique($this->input->post('perferencecity', true));
		    foreach($perferencecity as $locality_city_name) {
			    if ($locality_city_name != "") {
				$newlist = explode(':',$locality_city_name);
				$new_CityList .= $newlist[2] . ",";
			    }
		    }
		    $new_CityList = substr($new_CityList, 0, -1);
	    }
		if ($marketingpagename == 'testprep')
		{
			$blogId = $this->input->post('testPrep_blogid', true);
			$this->load->library('category_list_client');
			$blog_acronym = $this->category_list_client->getBlogAcronym($blogId);
			$this->load->library('url_manager');
			if (isset($finalArray['cityId'][0])) {
				$cityname = $this->category_list_client->getCityName($finalArray['cityId'][0]);
			} else {
				$cityname = '';
			}
			$finalUrl = $this->url_manager->get_testprep_url('', $blog_acronym,$cityname,'', '');
			$finalArray['landingpage'] = $finalUrl;
			$finalArray['testPrep_blogid'] = $blogId;
			$finalArray['testPrep_status'] = 'live';
			$finalArray['testPrep_status'] = 'live';
			$finalArray['testPrep_updateTime'] = date("Y-m-d H:i:s");
			$finalArray['extraFlag'] = 'testprep';
		} else {
                    /* Added by Amit Singhal on 8th March 2011 for Catagory page Project for Ticket #171                     */
                    $urlsuffix = "";
                    if($finalArray['category'] == 10 && $finalArray['desiredCourse'] == "47"){
                        $urlsuffix = $this->getURLforCourses("MCA");
                    }
                    if($finalArray['category'] == 10 && $finalArray['desiredCourse'] == "46"){
                        $urlsuffix = $this->getURLforCourses("BCA");
                    }
                    if($finalArray['category'] == 2 && $finalArray['desiredCourse'] == "52"){
                        $urlsuffix = $this->getURLforCourses("Engineering");
                    }
                    if($finalArray['category'] == 6 && $finalArray['desiredCourse'] == "264"){
                        $urlsuffix = $this->getURLforCourses("BHM");
                    }
			$finalUrl =  $this->codeforlandingpage($new_CityList,$finalArray['category'],$subCategory,$city,$urlsuffix);
			$finalArray['landingpage'] = $finalUrl;
		}
	    //echo "<pre>";print_r($finalArray);echo "</pre>";exit;
            error_log('LDBSERVER :: LDB process complete');
            $email = $finalArray['email'];
            $mobile = $finalArray['mobile'];
            if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
                echo "Blank";
                exit;
            }
            if($finalArray['email'] == '')
            {
                echo "Blank" ;
                exit;
            }
            else
            {
                if(!isset($Validate[0]['userid'])) {
                error_log(' LDBSERVER :: LDB call user is not sign in and insertion start.');
                /* captcha will validate if user is not logged-in */
                $secCode = $this->input->post('homesecurityCode', true);
                    if(verifyCaptcha($secCodeSessionVar,$secCode1))
                    {
                        // save data here
                        $response = $registerClient->addUser($appId,$finalArray);
                        error_log(print_r($finalArray,true).' LDBSERVER :: FINAL ARRAY TO ADD USER');
                        if($response['status'] > 0)
                        {
                            //Set the cookie when the user has registered
                            $Validate = $this->checkUserValidation();
                            if(!isset($Validate[0]['userid'])){
                                $value = $email.'|'.$finalArray['password'];
                                $this->cookie($value);
                            }
                            
                            $this->load->library('user/UserLib');
                            $userLib = new UserLib;
                            $userLib->sendEmailsOnRegistration($response['status'], array(), $password);
                            $userLib->updateUserData($response['status']);
                            
                            $finalUrl = $this->addSearchTrackParmToUrl($finalUrl);
			    $response['finalUrl'] = $finalUrl;
			    $response['flagfirsttime'] = 'true';
			    echo json_encode($response);
                        }
                        else
                        {
                            // If the user already exists (based on email/displayname)
                            if($response['status'] == -1)
                            {
                                if($response['email'] == -1 && $response['displayname'] == -1)
                                    echo 'both';
                                else
                                {
                                    if($response['email'] == -1)
                                        echo 'email';
                                    if($response['displayname'] == -1)
                                        echo 'displayname';
                                }

                            }
                            else
                            {
                                echo 0;
                            }
                        }
                    }
                    else
                    {
                        echo "code";
                    }
                } // end if user logged-in check
                else
                {
                    error_log(' LDBSERVER :: LDB call user is logged-in and update start.');
                    if($this->input->post('mupdateflag', true) == "update")
                    {
                        /*
                        Remove few items from array as logged-in user submit data so we do'nt need to reset password and cookie
                        */
                        unset($finalArray['displayname']);
                        /* Text password */
                        unset($finalArray['textPassword']);
                        /* MD5 password */
                        unset($finalArray['password']);

                        $userId = $Validate[0]['userid'];
                        $response = $registerClient->updateUser($appId,$finalArray,$userId);
                    }
                    $finalUrl = $this->addSearchTrackParmToUrl($finalUrl);
                    $response['finalUrl'] = $finalUrl;
                    $response['flagfirsttime'] = 'false';
                    echo json_encode($response);
                }
            }
        } // end if of main form CRUD
    }
    
    /**
     * Function for test user on marketing page
     *
     * @param string $secCodeSessionVar
     */
    function TestuserMarketingPAge($secCodeSessionVar='seccodehome') {
        error_log('LDB start');
        $this->init();
        $registerClient = new Register_client();
        /* useless var. at least in this case !!! */
        $appId = 1;
        /* build an array for form values  in formatted manner */
        $finalArray = array();
        /* get user current status */
        $Validate = $this->checkUserValidation();
        /* boolen flag */
        $update_flag = $this->input->post('flag_marketing_overlay', true);
        $flag_prefId = trim($this->input->post('flag_prefId', true));
        /* flag that identify distance learning or MBA lead*/
        $mPageName = $this->input->post('mpagename', true);
	/* flag that tells main marketing page*/
	$marketingpagename = $this->input->post('marketingpagename', true);
        /* if request come from overlay wala form */
        if ((!empty($update_flag))&& ($update_flag == 'true')) {
            error_log('LDB call second overlay');
            $finalArray['referer'] = $_SERVER['HTTP_REFERER'];
            $finalArray['browser'] = $_SERVER['HTTP_USER_AGENT'];
            // user age
            $user_age = $this->input->post('quickage', true);
            if (!empty($user_age)) {
                $finalArray['age'] = $user_age;
            }
            // Female,Male
            $user_gender = $this->input->post('quickgender', true);
            if (!empty($user_gender)) {
                $finalArray['gender'] = $user_gender;
            }
            // other details text area
            $otherdetails = $this->input->post('otherdetails', true);
	    if ($mPageName == 'marketingPage') {
		if (!empty($otherdetails) && (trim($otherdetails) != 'Specify any other detail about your course and institute preference.')) {
		    $finalArray['userDetail'] = $otherdetails;
		}
	    } else {
		if (!empty($otherdetails) && (trim($otherdetails) != 'Please specify your budget and preferred institutes/colleges if any.')&&(trim($otherdetails) != 'Specify any other detail about your course and institute preference.')) {
		    $finalArray['userDetail'] = $otherdetails;
		}
	    }
            // desired specialization id
            $desired_specialization_id = $this->input->post('specializationId', true);
            if (is_array($desired_specialization_id))
	    {
	      foreach ($desired_specialization_id as $value) {
		$finalArray['specializationId'][] = $value;
	      }
	    }
            // 10th Details
            $xii_passing_year = $this->input->post('10_com_year_year', true);
            $xii_percentage = $this->input->post('10_ug_detials_courses_marks', true);
            // 10th Stream [science_arts,science_commerce,science_stream]
	    $xii_stream = $this->input->post('science_stream', true);
	    if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
		$finalArray['instituteId'][] = '';
		$finalArray['marksType'][] = 'percentage';
		$finalArray['level'][] = '12';
		$finalArray['city'][] ='';
		$finalArray['country'][] ='2';
		if (!empty($xii_stream)) {
		    if ($xii_stream == 'science_arts') {
			$finalArray['name'][] = 'arts';
		    } else if ($xii_stream == 'science_commerce') {
			$finalArray['name'][] = 'commerce';
		    } else {
			$finalArray['name'][] = 'science';
		    }
		}else{
		    $finalArray['name'][] = '';
		}
		if (!empty($xii_percentage)) {
		    $finalArray['marks'][] = $xii_percentage;
		    } else {
		    $finalArray['marks'][] = '';
		}
		if(!empty($xii_passing_year)) {
		$finalArray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
		$finalArray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
		} else {
		$finalArray['courseCompletionDate'][] = '';
		$finalArray['ongoingCompletedFlag'][] = '';
		}
	    }
            // make other exam. detail
            $array_other_exam_name = $this->input->post('other_exam_name', true);
            $array_other_exam_marks = $this->input->post('other_exam_marks', true);
            if (!empty($array_other_exam_name) || !empty($array_other_exam_marks)) {
                for ($i = 0; $i < count($array_other_exam_name);$i++) {
		if($array_other_exam_name[$i] == '') continue;
                    $finalArray['name'][] = $array_other_exam_name[$i];
                    $finalArray['marks'][] = $array_other_exam_marks[$i];
                    $finalArray['instituteId'][] = '';
		    if ((trim($array_other_exam_name[$i]) == "CAT")||(trim($array_other_exam_name[$i]) == "MAT")) {
		      $finalArray['marksType'][] = 'percentile';
		    } else {
		      $finalArray['marksType'][] = '';
		    }
                    $finalArray['level'][] = 'Competitive exam';
                    $finalArray['courseCompletionDate'][] = '';
                    $finalArray['ongoingCompletedFlag'][] ='';
                    $finalArray['city'][] ='';
                    $finalArray['country'][] ='';
                    $finalArray['educationStatus'][] ='live';
                }
            }
            /* Three checkboxes */
            $newsletteremail =  $this->input->post('newsletteremail', true);
            $finalArray['newsletteremail'] = (!empty($newsletteremail))?1:0;
            $viaemail =  $this->input->post('viaemail', true);
            $finalArray['viaemail'] = (!empty($viaemail))?1:0;
            $viamobile = $this->input->post('viamobile', true);
            $finalArray['viamobile'] = (!empty($viamobile))?1:0;

            /* pick password */
            $password = $this->input->post('quickpassword');
            if (!empty($password)) {
                $finalArray['textPassword'] = $password;
                $finalArray['password']  = md5($password);
            }
            if (!empty($flag_prefId)) {
                $finalArray['prefId'] = $flag_prefId;
            }
            /* already user is registered so pick userid */
            $userId = $Validate[0]['userid'];
            /* make life easier to call this API :) */
            error_log(print_r($finalArray,true)."LDB update user api called");
            $registerClient->updateUser($appId,$finalArray,$userId);
            if (!empty($password)) {
		    $emailArr = explode("|",$Validate[0]['cookiestr']);
		    $emailArr[1] = $finalArray['ePassword'];
		    $value = implode('|',$emailArr);
		    $this->cookie($value);
            }
            echo "update";
        // end if of overlay update
        } else {
            error_log('LDB call Insert start');
            // Again Last min. change
	    $finalArray['newsletteremail'] = 1;
            // other details text area
            $otherdetails = $this->input->post('otherdetails', true);
	    if ($mPageName == 'marketingPage') {
		if (!empty($otherdetails) && (trim($otherdetails) != 'Specify any other detail about your course and institute preference.')) {
		    $finalArray['userDetail'] = $otherdetails;
		}
	    } else {
		if (!empty($otherdetails) && (trim($otherdetails) != 'Please specify your budget and preferred institutes/colleges if any.')) {
		    $finalArray['userDetail'] = $otherdetails;
		}
	    }
            /* Work Experience */
            $finalArray['experience'] = $this->input->post('ExperienceCombo', true);

            /* CAT MAT like  EXAM Details */
            $flag_cat_mat_exm_marks = $this->input->post('ExamsTaken', true);
            foreach($flag_cat_mat_exm_marks as $exam_value) {
                $exm_marks = $this->input->post($exam_value .'_exm_marks', true);
                if((!empty($exam_value)) && ($exam_value != 'Percentile') && (($exam_value == 'cat' ||$exam_value == 'mat'))) {
                    $finalArray['name'][] = strtoupper($exam_value);
                    $finalArray['marks'][] = $exm_marks;
                    $finalArray['instituteId'][] = '';
                    $finalArray['marksType'][] = 'percentile';
                    $finalArray['level'][] = 'Competitive exam';
                    $finalArray['courseCompletionDate'][] = '';
                    $finalArray['ongoingCompletedFlag'][] ='';
                    $finalArray['city'][] ='';
                    $finalArray['country'][] ='';
                    $finalArray['educationStatus'][] ='live';
                }
            }

            /* UG DETAILS */
            $ug_detials_courses =$this->input->post('ug_detials_courses', true);
            if (!empty($ug_detials_courses)) {
                $finalArray['name'][] = $ug_detials_courses;
		error_log($ug_detials_courses.'EDUCATIONLEVEL');
			global $ug_course_mapping_array;
			foreach($ug_course_mapping_array as $key => $value)
			{
				if($value == $ug_detials_courses)
				{
					$educationLevel = $key;
			                error_log($educationLevel.'EDUCATIONLEVEL');
					break;
				}
			}
			error_log($educationLevel.'EDUCATIONLEVEL');
                if ($this->input->post('Completed', true) == 'completed') {
                    $marks = $this->input->post('ug_detials_courses_marks', true);
                } else {
                    $marks = '';
                }
		$schoolCombo = $this->input->post('schoolCombo', true);
		$citiesofeducation = $this->input->post('citiesofeducation', true);
		if (empty($schoolCombo)) { $schoolCombo = '';}
		if (empty($citiesofeducation)) { $citiesofeducation = '';}
                $finalArray['marks'][] = $marks ;
                $finalArray['instituteId'][] = $schoolCombo;
                $finalArray['marksType'][] = 'percentage';
                $finalArray['level'][] = 'UG';
                $finalArray['courseCompletionDate'][] = $this->input->post('com_year_year', true) . '-'.$this->input->post('com_year_month', true) .'-'. '1';
                $finalArray['ongoingCompletedFlag'][] = $this->input->post('Completed', true) == 'completed'?"0":"1";
                $finalArray['city'][] = $citiesofeducation;
                $finalArray['country'][] ='2';
                $finalArray['educationStatus'][] ='live';
            }

            /*Degree Preference*/
            $degree_preference  = $this->input->post('degree_preference', true);
            if (is_array($degree_preference)) {
                foreach ($degree_preference as $value) {
                    switch($value) {
                        case 'aicte_approved':
                        $finalArray['degreePrefAICTE'] = 'yes';
                        break;
                        case 'ugc_approved':
                        $finalArray['degreePrefUGC'] = 'yes';
                        break;
                        case 'international':
                        $finalArray['degreePrefInternational']= 'yes';
                        break;
                        case 'any':
                        $finalArray['degreePrefAny']= 'yes';
                        break;
                    }
                }
            }
            /*modeOfEducation*/
            $mode_preference  = $this->input->post('mode', true);
            if (is_array($mode_preference)) {
                foreach ($mode_preference as $value) {
                    switch($value) {
                        case 'full_time':
                        $finalArray['modeOfEducationFullTime'] = 'yes';
                        break;
                        case 'part_time':
                        $finalArray['modeOfEducationPartTime'] = 'yes';
                        break;
                        case 'distance':
                        $finalArray['modeOfEducationDistance']= 'yes';
                        break;
                    }
                }
            }
            /* specializationId */
            if ($mPageName == 'distancelearningmanagement') {
                $desired_specialization_id = $this->input->post('homesubCategories', true);
                if ($this->checkDMBACourse($desired_specialization_id))
		{
		  $finalArray['desiredCourse']=24;
		  $finalArray['specializationId'][]=$desired_specialization_id;
		}
		else
                {
                  /* we put this else condition BCZ !!!!!sometimes mpagename got worng !!! :-|
                   * so in this case NULL goes as desired course !!!
                   */
                  $finalArray['desiredCourse'] = $this->input->post('homesubCategories', true);
                }
            } elseif($mPageName == 'marketingPage') {
                $finalArray['desiredCourse'] = $this->input->post('homesubCategories', true);
            }
            /* when you plan to start study */
            $finalArray['timeOfStart'] = $this->input->post('plan', true);

            /* Residence Location */
            $finalArray['residenceCity']=$this->input->post('citiesofquickreg', true);

            /* Mobile */
            $finalArray['mobile']= $this->input->post('mobile', true);

            /* Email id */
            $finalArray['email']= $this->input->post('email', true);

            /* First Name */
            $finalArray['firstName']= $this->input->post('firstname', true);

            ##############################################
            /* Loop to check unique displayname START */
            $responseCheckAvail = 1;
            error_log(' LDB WHILE START'.microtime(true));
            while($responseCheckAvail == 1){
                $displayname = $finalArray['firstName'] . rand(1,100000);
                $responseCheckAvail = $registerClient->checkAvailability($appId,$displayname,'displayname');
            }
            error_log(' LDBSERVER WHILE END'.microtime(true));
            /* Loop to check unique displayname END */
            $finalArray['displayname']= $displayname;

            /* Text password */
            $finalArray['textPassword'] = 'shiksha@'. rand(1,1000000);
            /* MD5 password */
            $finalArray['password']  = md5($finalArray['textPassword']);
            ##############################################
            /* Preferred Study Location(s) START */
            $mCityList = $this->input->post('mCityList', true);
            $mCityList = explode(',',$mCityList);
            $new_CityList = "";
	    if($mPageName != "distancelearningmanagement") {
		foreach($mCityList as $value) {
		    if (!empty($value)) {
			$newlist = explode(':',$value);
			if (count($newlist) < 3 ) {
			    continue;
			}
			$finalArray['countryId'][] = $newlist[0];
			$finalArray['stateId'][] = $newlist[1];
			$finalArray['cityId'][] = $newlist[2];
			$new_CityList .= $newlist[2] . ",";
		    }
		}
	    }
	    /* add locality in distancelearning */
	    if($mPageName == "distancelearningmanagement") {
		$perferencelocality = $this->input->post('perferencelocality', true);
		$perferencecity = $this->input->post('perferencecity', true);
		$i = 0;
		foreach($perferencelocality as $locality_city_name) {
			if ($perferencelocality[$i] != "") {
			    if ($perferencelocality[$i] == '-1') {
				$newlist = explode(':',$perferencecity[$i]);
				if (in_array($newlist[2],$finalArray['cityId']) == false) {
				    $finalArray['countryId'][] = $newlist[0];
				    $finalArray['stateId'][] = $newlist[1];
				    $finalArray['cityId'][] = $newlist[2];
				    $finalArray['localityId'][] = 0;
				}
			    } else {
				$newlist = explode(':',$perferencelocality[$i]);
				if (in_array($newlist[3],$finalArray['localityId']) == false) {
				    $finalArray['countryId'][] = $newlist[0];
				    $finalArray['stateId'][] = $newlist[1];
				    $finalArray['cityId'][] = $newlist[2];
				    $finalArray['localityId'][] = $newlist[3];
				}
			    }
			}
		$i++;
		}
	        $perferencecity = array_unique($this->input->post('perferencecity', true));
		    foreach($perferencecity as $locality_city_name) {
			    if ($locality_city_name != "") {
				$newlist = explode(':',$locality_city_name);
				$new_CityList .= $newlist[2] . ",";
			    }
		    }
	    }
            /* :( HACK to trim last , from city list */
            $new_CityList = substr($new_CityList, 0, -1);
            /* Preferred Study Location(s) END */
            /* Get all lead realted data */
            $finalArray['sourcename'] = 'MARKETING_FORM';
            $finalArray['referer'] = $this->input->post('refererreg', true);
            $finalArray['resolution']= $this->input->post('resolutionreg', true);
            $finalArray['category'] = 3;
            /* T USER SOURCE INFO */
            $finalArray['referer'] = $_SERVER['HTTP_REFERER'];
            $finalArray['landingpage'] = $this->input->post('loginactionreg', true);
            $finalArray['browser'] = $_SERVER['HTTP_USER_AGENT'];
            /* T USER SOURCE INFO */
            /* hard coded management */
            $categories = 3;
            $subCategory = '';
            $preferredCity = $new_CityList;
            $mcourse = '';
            $city = $finalArray['residenceCity'];
            $firstName = $finalArray['firstName'];
            $mobile = $finalArray['mobile'];
            $age = '';
            $gender  = '';
//            $educationLevel = '';
            $email = $finalArray['email'];
            $displayname = $finalArray['displayname'];
            $mPageName = $this->input->post('mpagename', true);
            $mdpassword = $finalArray['password'];
            $password = $finalArray['textPassword'] ;

            $addReqInfo = array();
            $addReqInfo['email'] = $email;
            $addReqInfo['displayName'] = $displayname;
            $addReqInfo['residenceLoc'] = $city;
            $addReqInfo['age'] = 'NULL'; //
            $addReqInfo['gender'] = 'NULL';//
            $addReqInfo['highestQualification'] = $educationLevel;//
            $addReqInfo['flagRegistered'] = "true";
	    $addReqInfo['mobile'] = $mobile;
	    $addReqInfo['mPageName'] = $mPageName;
	    if($mPageName  == "marketingPage")
	    {
		    $addReqInfo['mPageName'] = 'management';
	    }

	    $addReqInfo['marketingFlag'] = 'false';// don't know ?
	    $addReqInfo['category'] = 3; //
            $addReqInfo['subCategory'] = $subCategory; //
            $addReqInfo['preferredcity'] = $new_CityList; //

            // Make array for LeadKeyValue pair
            $LeadInterest = array('category' =>3,'subCategory'=>$subCategory,'city'=>$new_CityList,'countries'=>'2');
        /* This code has been moved below as we would be requiring the city list for distnace MBA landing page url calclulations
          By Amit SInghal on 8th March 2001 | Category Page Revamp changes.
	    if($mPageName == "distancelearningmanagement")
	    {
		$mcourse = 'Correspondence,E-learning';
		$new_CityList = "";
	    }
        */
            error_log(' LDBSERVER old kabad start' . time());
            /* NEED TO CHECK THIS */
            $keyvalarray = $this->makeLeadKeyValue(3,$subCategory,$new_CityList,$mcourse);
            error_log(' LDBSERVER old kabad middle' . time());
            if (($marketingpagename == 'campaign2') || ($marketingpagename == 'campaign1')|| ($marketingpagename == 'campaign3')|| ($marketingpagename == 'campaign4')|| ($marketingpagename == 'campaign5')|| ($marketingpagename == 'campaign6')|| ($marketingpagename == 'campaign7')|| ($marketingpagename == 'campaign8')|| ($marketingpagename == 'campaign9'))
	    {
		 $this->load->library('MP_MarketingPage');
		 $uo = MP_MarketingPage::INIT("ManagementMarketingPage");
		 $finalUrl = $uo->getRedirectionUrl($marketingpagename);
	    } else {
                /* Added by Amit Singhal on 8th March 2011 for Catagory Page Revamp (CPR) Project for Ticket #171                     */
                $urlsuffix = "";
		if($mPageName == "distancelearningmanagement")
		{
            $urlsuffix = $this->getURLforCourses("Distance MBA");
            //$finalUrl = SHIKSHA_HOME .'/search/index?keyword=MBA+Correspondence&location=&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subLocation=-1&cityId=-1&cType=correspondence&courseLevel=-1&subType=0&showCluster=-1&channelId=home_page';
            $finalUrl =  $this->codeforlandingpage($new_CityList,3,$subCategory,$city,$urlsuffix); // Updated by AMit SInghal for CPR changes
            // Now assigning the values those were getting assigned earlier..
            $mcourse = 'Correspondence,E-learning';
            $new_CityList = "";
		} elseif($mPageName == "marketingPage") {
            // This condition has been added by AMit SInghal for CPR changes
           if($finalArray['modeOfEducationFullTime'] == "yes"){
               $urlsuffix = $this->getURLforCourses("MBA");
           }else{
               $urlsuffix = $this->getURLforCourses("Part Time MBA");
           }
           // Now getting the Landing URL..
		    $finalUrl =  $this->codeforlandingpage($new_CityList,3,$subCategory,$city,$urlsuffix);
		}
	    }
            $finalArray['landingpage'] = $finalUrl;
            error_log(' LDBSERVER old kabad end' . time());
            error_log('LDBSERVER process complete');
            $email = $finalArray['email'];
            $mobile = $finalArray['mobile'];
            if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
                echo "Blank" ;
                exit;
            }
            if($finalArray['email'] == '')
            {
                echo "Blank" ;
                exit;
            }
            else
            {
                if(!isset($Validate[0]['userid'])) {
                error_log('LDBSERVER call user is not sign in and insertion start.');
                /* captcha will validate if user is not logged-in */
                $secCode = $this->input->post('homesecurityCode', true);
                $pageReferer = $this->input->post('pageReferer', true);
                
                    if(isCaptchaFreeReferer($pageReferer) || verifyCaptcha($secCodeSessionVar,$secCode1))
                    {
                        // save data here
                        $response = $registerClient->addUser($appId,$finalArray);
                        error_log(print_r($finalArray,true).' LDBSERVER FINAL ARRAY TO ADD USER');
                        if($response['status'] > 0)
                        {
                            //Set the cookie when the user has registered
                            $Validate = $this->checkUserValidation();
                            if(!isset($Validate[0]['userid'])){
                                $value = $email.'|'.$finalArray['password'];
                                $this->cookie($value);
                            }
                            $this->load->library('user/UserLib');
                            $userLib = new UserLib;
                            $userLib->sendEmailsOnRegistration($response['status'], array(), $password);
                            $userLib->updateUserData($response['status']);

                            //  put the user data in lead log tables
			    if($mPageName != "homePage") {
				    $this->load->library('MarketingClient');
				    $marketingClientObj = new MarketingClient();
				    $addUser = $marketingClientObj->registerUserForLead(1,$response['status'],$addReqInfo,$LeadInterest,'',$keyvalarray,'add');
				    error_log(" LDBSERVER registerUserForLead API called". print_r($addUser,true));
			    }
                            $response['finalUrl'] = $finalUrl;
                            $response['flagfirsttime'] = 'true';
                            echo json_encode($response);
                        }
                        else
                        {
                            // If the user already exists (based on email/displayname)
                            if($response['status'] == -1)
                            {
                                if($response['email'] == -1 && $response['displayname'] == -1)
                                    echo 'both';
                                else
                                {
                                    if($response['email'] == -1)
                                        echo 'email';
                                    if($response['displayname'] == -1)
                                        echo 'displayname';
                                }

                            }
                            else
                            {
                                echo 0;
                            }
                        }
                    }
                    else
                    {
                        echo "code";
                    }
                } // end if user logged-in check
                else
                {
                    error_log('LDBSERVER call user is logged-in and update start.');
                    if($this->input->post('mupdateflag', true) == "update")
                    {
                        /*
                        Remove few items from array
                        as logged-in user submit data
                        so we do'nt need to reset password and cookie
                        */
                        unset($finalArray['displayname']);
                        /* Text password */
                        unset($finalArray['textPassword']);
                        /* MD5 password */
                        unset($finalArray['password']);

                        $userId = $Validate[0]['userid'];
                        $response = $registerClient->updateUser($appId,$finalArray,$userId);
                        /*
                            $value = $email.'|'.$finalArray['password'];
                            $this->cookie($value);
                        */
                    }
                    $this->load->library('MarketingClient');
                    $marketingClientObj = new MarketingClient();
                    $addUser = $marketingClientObj->registerUserForLead(1,$userId,$addReqInfo,$LeadInterest,'',$keyvalarray,'add');
                    error_log("LDBSERVER registerUserForLead api". print_r($addUser,true));
                    $response['finalUrl'] = $finalUrl;
                    $response['flagfirsttime'] = 'false';
                    echo json_encode($response);
                }
            }
        } // end if of main form CRUD
    }

    /**
     * Function for user from marketing page
     *
     * @param string $secCodeSessionVar
     */
    function userfromMarketingPage($secCodeSessionVar='seccodehome')
    {
        $this->init();
        $email = trim($this->input->post('homeemail', true));
        $password = trim($this->input->post('homepassword'));
        $country = $this->input->post('countryofresidence1', true);
        $city = $this->input->post('citiesofresidence1', true);
        $mobile = $this->input->post('homephone', true);
        $educationLevel = $this->input->post('homehighesteducationlevel', true);
        $age = $this->input->post('homeYOB', true);
        $categories = $this->input->post('board_id', true);
        $gender = $this->input->post('homegender', true);
        $mdpassword = sha256($password);
        $subCategory = $this->input->post('homesubCategories', true);
        $preferredCity = $this->input->post('mCityList', true);
        $preferredCity = (substr($preferredCity,strlen($preferredCity) -1 ,strlen($preferredCity)) == ',') ? substr($preferredCity,0,strlen($preferredCity) - 1) : $preferredCity;
        $preferredCityName = $this->input->post('mCityListName', true);
        $displayname = htmlentities(addslashes(trim($this->input->post('homename', true))));
        $firstName = htmlentities(addslashes(trim($this->input->post('homename', true))));
        $resolution = $this->input->post('resolutionreg', true);
        $sourceurl = $this->input->post('refererreg', true);
        $mPageName = $this->input->post('mPageName', true);
        $mcourse = $this->input->post('mcourse', true);
        $countries = $this->input->post('mCountryList', true);
        $modeoffinance = $this->input->post('sourceFunding', true);
        $nearestMetropolitanCity = $this->input->post('mCity', true);
        $courseStartTime= $this->input->post('plan', true);

        $countryList = explode(',',$countries);
        if($countryList[count($countryList)-1] == '')
        {
            unset($countryList[count($countryList)-1]);
        }
        $countries = implode(',',$countryList);

        // Check for the availability of the display name if not available generate it using ranndom key else use the name as display name
        $register_client = new Register_client();
        $appId = 1;
        $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
        while($responseCheckAvail == 1){
            $displayname = $this->input->post('homename', true) . rand(1,100000);
            $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
        }
        // Check availability code ends

        // By default viamobile,viamail,newslettermail to set as 1
        $viamobile = 1;
        $viamail = 1;
        $newsletteremail =1;
        $profession = '';
        if($educationLevel == "School")
        {
            $userstatus = "School";
            $educationLevel = "School";
        }
        else
            $userstatus = "College";
        $experience = null;

        $appID = 1;
        /* User array for marketing table */
        $addReqInfo = array();
        $Validate = $this->checkUserValidation();
        error_log(print_r($Validate,true));
        if(isset($Validate[0]['userid']))
        {
            $exploded = explode('|',$Validate[0]['cookiestr']);
            $email = $exploded[0];
            $password = $exploded[1];
        }
        $flagvalue = 'false';
        if($mPageName == "bcait" || $mPageName == "mcait")
        {
            $flagvalue = 'true';
            $subCategory = 131;
        }
        $addReqInfo['email'] = $email;
        $addReqInfo['displayName'] = $firstName;
        $addReqInfo['residenceLoc'] = $city;
        $addReqInfo['age'] = $age;
        $addReqInfo['gender'] = $gender;
        $addReqInfo['highestQualification'] = $educationLevel;
        $addReqInfo['flagRegistered'] = "true";
        $addReqInfo['mobile'] = $mobile;
        $addReqInfo['mPageName'] = $mPageName;
        $addReqInfo['marketingFlag'] = $flagvalue;
        $addReqInfo['category'] = $categories;
        $addReqInfo['subCategory'] = $subCategory;
        $addReqInfo['preferredcity'] = $preferredCity;


        // Make array for LeadKeyValue pair
        $LeadInterest = array('category' => $categories,'subCategory'=>$subCategory,'city'=>$preferredCity,'countries
                '=>'2');

        //Make array for keyvalue pair
        if($mcourse == "distancelearning")
            $mcourse = 'Correspondence,E-learning';
        if($mPageName == "studyAbroad")
        {
            $keyvalarray = $this->makeLeadKeyValueForStudyAbroad($categories,$countries,$nearestMetropolitanCity,$modeoffinance,$courseStartTime);
        }
        else
        {
            $keyvalarray = $this->makeLeadKeyValue($categories,$subCategory,$preferredCity,$mcourse);
        }
        error_log(print_r($keyvalarray,true));
        $finalUrl =  $this->codeforlandingpage($preferredCity,$categories,$subCategory,$city);
        if($mPageName == "studyAbroad")
        {
            $countriesSent = explode(",",$this->input->post('mCountryListName', true));
            error_log("ShirishSTA 12121".print_r($countriesSent,true));
            $index = rand(0,count($countriesSent)-1);
            $countrySelected = $countriesSent[$index];
            if($countrySelected == "")
            {
                $countrySelected = $countriesSent[$index-1];
            }
            global $countries;
            foreach($countries as $key=>$value)
            {
                if($value['name'] == $countrySelected)
                {
                    $countrySelected = $key;
                    break;
                }
            }

            //$countrySelected = "usa";
            $pageName = strtoupper('SHIKSHA_'. $countrySelected.'_HOME');
            $countryUrl = constant($pageName);
            if(strstr($countryUrl,"getCategoryPage") > -1) {
                $countryUrl= $countryUrl;
            }else {
                $countryUrl= $countryUrl.'/getCategoryPage/colleges/studyabroad/'.$countrySelected;
            }
            global $categoryMap;
            foreach($categoryMap as $key=>$value)
            {
                if($value['id'] == $categories)
                {
                    $categoryNameInUrl = $key;
                    break;
                }
            }
            $finalUrl = $countryUrl."/All/".$categoryNameInUrl."/All";
        }

        if($mPageName == "distancelearningmanagement")
        {
            $finalUrl = SHIKSHA_HOME .'/search/index?keyword=MBA+Correspondence&location=&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subLocation=-1&cityId=-1&cType=correspondence&courseLevel=-1&subType=0&showCluster=-1&channelId=home_page';
        }

        if($mPageName == "bcait")
        {
            $multiLoc =  $this->multipleLocation($preferredCity, $city);
            $finalUrl = SHIKSHA_HOME .'/search/index?keyword=bca&location='.urlencode($multiLoc).'&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subLocation=-1&cityId=-1&cType=-1&courseLevel=-1&subType=0&showCluster=-1&channelId=home_page';
        }
        if($mPageName == "mcait")
        {
            $multiLoc =  $this->multipleLocation($preferredCity, $city);
            $finalUrl = SHIKSHA_HOME .'/search/index?keyword=mca&location='.urlencode($multiLoc).'&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subLocation=-1&cityId=-1&cType=-1&courseLevel=-1&subType=0&showCluster=-1&channelId=home_page';
        }
        if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
            echo "Blank" ;
            exit;
        }
        if($email == '' || $password == '' || $displayname == '')
        {
            echo "Blank" ;
            exit;
        }
        else
        {
            $secCode = $this->input->post('homesecurityCode', true);
            if(verifyCaptcha($secCodeSessionVar,$secCode,1))
            {
                if(!isset($Validate[0]['userid'])){
                    /* User array for tuser table */
                    $sourcename = 'MARKETING_FORM';
                    $userarray['sourceurl'] = $sourceurl;
                    $userarray['sourcename'] = $sourcename;
                    $userarray['resolution'] = $resolution;
                    $userarray['appId'] = 1;
                    $userarray['email'] = $email;
                    $userarray['password'] = $password;
                    $userarray['mdpassword'] = $mdpassword;
                    $userarray['displayname'] = $displayname;
                    $userarray['country'] = $country;
                    $userarray['city'] = $city;
                    $userarray['age'] = $age;
                    $userarray['mobile'] = $mobile;
                    $userarray['educationLevel'] = $educationLevel;
                    $userarray['youare'] = $userstatus;
                    $userarray['firstname'] = $firstName;
                    $userarray['gender'] = $gender;
                    $userarray['categories'] = $categories;
                    $userarray['subcategories'] = $subCategory;
                    $userarray['quicksignupFlag'] = "marketingPage";
                    $userarray['preferredCityCsv'] = $preferredCity;
                    $userarray['usergroup'] = 'marketingPage';
		    $userarray['vianewsletteremail'] = 1;
                    /* User array for tuser table ends */

                    // Add new user
                    $addResult = $register_client->adduser_new($userarray);

                    if($addResult['status'] > 0)
                    {
                        //Set the cookie when the user has registered
                        $Validate = $this->checkUserValidation();
                        if(!isset($Validate[0]['userid'])){
                            $value = $email.'|'.$mdpassword;
                            $this->cookie($value);
                        }

                        $this->load->library('user/UserLib');
                        $userLib = new UserLib;
                        $userLib->sendEmailsOnRegistration($response['status'], array(), $password);
                        $userLib->updateUserData($response['status']);
                            
			//  put the user data in lead log tables
			if($mPageName != "homePage") {
				$this->load->library('MarketingClient');
				$marketingClientObj = new MarketingClient();
				$addUser = $marketingClientObj->registerUserForLead(1,$addResult['status'],$addReqInfo,$LeadInterest,'',$keyvalarray,'add');
			}
                        echo $finalUrl;


                    }
                    else
                    {
                        // If the user already exists (based on email/displayname)
                        if($addResult['status'] == -1)
                        {
                            if($addResult['email'] == -1 && $addResult['displayname'] == -1)
                                echo 'both';
                            else
                            {
                                if($addResult['email'] == -1)
                                    echo 'email';
                                if($addResult['displayname'] == -1)
                                    echo 'displayname';
                            }

                        }
                        else
                            echo 0;
                    }
                }
                else
                {
                    /* User array for updating tuser table */
                    $updateuserInfo = array('firstname' => $firstName,'mobile' => $mobile,'city' => $city,'age' => $age,'gender' => $gender,'educationlevel' => $educationLevel);
                    if(isset($preferredCity))
                    {
                        $prefcities = explode(',',$preferredCity);
                        for($i = 0;$i < count($prefcities);$i++)
                        {
                            $cityId = $prefcities[$i];
                            $appId = 1;
                            $this->load->library('Category_list_client');
                            $categoryClientObj= new Category_list_client();
                            $cityDetails = $categoryClientObj->getDetailsForCityId($appId, $cityId);
                            $updateuserInfo['cityId'][] = $cityDetails['city_id'];
                            $updateuserInfo['stateId'][] = $cityDetails['state_id'];
                            $updateuserInfo['countryId'][] = $cityDetails['countryId'];
                        }
                    }
                    error_log('OKUPDATETHIS');
                    $this->load->library('MarketingClient');
                    $marketingClientObj = new MarketingClient();
                    if(is_numeric($categories))
                    {
                        $response = $register_client->getspecializationid($categories);
                        $updateuserInfo['desiredCourse'] = $response;
                    }

                    if(trim($categories) == "Study Abroad")
                        $updateuserInfo['extraFlag'] = 'studyabroad';
                    if(trim($categories) == "Undecided")
                        $updateuserInfo['extraFlag'] = 'undecided';
                    global $ug_course_mapping_array;
                    global $pg_course_mapping_array;
                    if(isset($educationLevel))
                    {
                        error_log(array_key_exists($ug_course_mapping_array[$educationLevel]).'USERARRAY');
                        if(array_key_exists($educationLevel,$ug_course_mapping_array))
                        {
                            $updateuserInfo['name'][] =  $ug_course_mapping_array[$educationLevel];
                            $updateuserInfo['level'][] =  'UG';
                        }
                        if(array_key_exists($educationLevel,$pg_course_mapping_array))
                        {
                            $updateuserInfo['name'][] =  $pg_course_mapping_array[$educationLevel];
                            $updateuserInfo['level'][] =  'PG';
                        }
                        if(trim($educationLevel) == "Other")
                        {
                            $updateuserInfo['name'][] =  trim($educationLevel);
                            $updateuserInfo['level'][] =  '';
                        }
                        if(trim($educationLevel) == "School")
                        {
                            $updateuserInfo['name'][] =  trim($educationLevel);
                            $updateuserInfo['level'][] =  '10';
                        }
                    }

                    $addResult = $register_client->updateUser(12,$updateuserInfo,$Validate[0]['userid']);
		    if($mPageName != "homePage") {
			    $addUser = $marketingClientObj->registerUserForLead(1,$Validate[0]['userid'],$addReqInfo,$LeadInterest,$updateuserInfo,$keyvalarray,'update');
		    }

                    echo $finalUrl;
                }
            }
            else
                echo "code";
        }

    }

    /**
     * Function to make lead key value
     *
     * @param array $categories
     * @param array $countries
     * @param array $cities
     * @param array $course
     * @return array $LeadArray
     */
    function makeLeadKeyValue($categories,$subcategory,$city,$course)
    {
        $i = 0;
        $j = 0;
        $k = 0;
        $l = 0;
        $z = 0;

        $categories = explode(',',$categories);
        $subcategory = explode(',',$subcategory);
        $city = explode(',',$city);
        $course = explode(',',$course);
        $z = 0;
        for($i = 0;$i < count($categories);$i++)
        {
            for($j = 0;$j < count($subcategory) ; $j++)
            {
                for($k = 0;$k < count($course) ; $k++)
                {
                    for($l = 0;$l<count($city) ; $l++)
                    {
                        $LeadArray[$z]['category'] =  ($categories[$i] != '') ? $categories[$i] : 0;
                        $LeadArray[$z]['subcategory'] = ($subcategory[$j] != '') ? $subcategory[$j] : 0;
                        $LeadArray[$z]['city'] = ($city[$l] != '') ? $city[$l] : 0;
                        $LeadArray[$z]['course'] = ($course[$k] != '') ? $course[$k] : 0;
                        $LeadArray[$z]['country'] = 2;
                        $LeadArray[$z]['modeoffinance'] = 0;
                        $LeadArray[$z]['courseStartTime'] = 0;
                        $z++;
                    }
                }
            }
        }
        return $LeadArray;
    }
    
    /**
     * Function to make lead key value for study abroad
     *
     * @param array $categories
     * @param array $countries
     * @param array $cities
     * @param integer $modeoffinance
     * @param integer $courseStartTime
     * @return array $LeadArray
     */
    function makeLeadKeyValueForStudyAbroad($categories,$countries,$cities,$modeoffinance,$courseStartTime)
    {
        $i = 0;
        $j = 0;
        $k = 0;
        $categories = explode(',',$categories);
        $countries = explode(',',$countries);
        $cities = explode(',',$cities);
        $z = 0;
        for($i = 0;$i < count($categories);$i++)
        {
            for($j = 0;$j < count($countries) ; $j++)
            {
                for($k = 0;$k < count($cities) ; $k++)
                {
                        $LeadArray[$z]['category'] =  ($categories[$i] != '') ? $categories[$i] : 0;
                        $LeadArray[$z]['subcategory'] = 0;
                        $LeadArray[$z]['city'] = ($cities[$k] != '') ? $cities[$k] : 0;
                        $LeadArray[$z]['course'] = 0;
                        $LeadArray[$z]['country'] = ($countries[$j] != '') ? $countries[$j] : 0;
                        $LeadArray[$z]['modeoffinance'] = $modeoffinance;
                        $LeadArray[$z]['courseStartTime'] = $courseStartTime;
                        $z++;
                }
            }
        }
        return $LeadArray;
    }
    
    /**
     * Function for multiple location
     *
     * @param integer $preferredCity
     * @param integer $city
     * @return string $finalPreferredLocName
     */
    function multipleLocation($preferredCity, $city)
    {
    $preferredLoc = array();
    if($preferredCity != '')
    {
        $csvArr = split(",",$preferredCity);
        for($ijk = 0;$ijk < count($csvArr);$ijk++) {
            if(trim($csvArr[$ijk]) != ""){
                preg_match('/\d+/',$csvArr[$ijk],$matches);
                if(count($matches) >0) {
                    array_push($preferredLoc, $csvArr[$ijk]);
                }
            }
        }
        error_log("GHJ ".print_r($preferredLoc,true));
        $finalPreferredLoc = "";
        $finalPreferredLocName = "";
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId, 1);
        $cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
        $cityListTier3 = $categoryClient->getCitiesInTier($appId,3,2);
        for($klj = 0; $klj< count($preferredLoc);$klj++) {
            foreach($cityListTier1 as $cityTemp)
            {
                if(trim($preferredLoc[$klj]) == trim($cityTemp['cityId'])) {
                    if($klj==0)
                        $finalPreferredLocName .= $cityTemp['cityName'];
                    else
                        $finalPreferredLocName .= ','.$cityTemp['cityName'];

                }
            }
        }
        for($klj = 0; $klj< count($preferredLoc);$klj++) {
            foreach($cityListTier2 as $cityTemp)
            {
                if(trim($preferredLoc[$klj]) == trim($cityTemp['cityId'])) {
                    if($klj==0)
                        $finalPreferredLocName .= $cityTemp['cityName'];
                    else
                        $finalPreferredLocName .= ','.$cityTemp['cityName'];
                }
            }
        }
        for($klj = 0; $klj< count($preferredLoc);$klj++) {
            foreach($cityListTier3 as $cityTemp)
            {
                if(trim($preferredLoc[$klj]) == trim($cityTemp['cityId'])) {
                    if($klj==0)
                        $finalPreferredLocName .= $cityTemp['cityName'];
                    else
                        $finalPreferredLocName .= ','.$cityTemp['cityName'];
                }
            }
        }
        return $finalPreferredLocName;
    }
}

/**
 * This function has been added by Amit Singhal on 8th March 2011 for Catagory Page Revamp (CPR) Project for Ticket #171
 *
 * @param string $course
 * @return string
 */
function getURLforCourses($course){

    if($course == "MBA" || $course == "BCA" || $course == "Engineering" || $course == "BHM"){
        return "--DOMAIN--";
    }elseif($course == "Part Time MBA"){
        return "/Degree/Post Graduate/Part Time/0/30";
    }elseif($course == "Distance MBA"){
        return "/Degree/Post Graduate/Correspondence/0/30";
    }elseif($course == "MCA"){
        return "/Degree/Post Graduate/All/0/30";
    }else{
        return "";
    }
}

    /**
     * Function for landing page
     *
     * @param integer $preferredCity
     * @param integer $categories
     * @param integer $subCategory
     * @param integer $city
     * @param string $urlsuffix
     * @return string $finalUrl
     */
    function codeforlandingpage($preferredCity,$categories,$subCategory,$city,$urlsuffix="")
    {
	$this->load->library('categoryList/categoryPageRequest');
	$requestURL = new CategoryPageRequest();
	if($categories != ""){
	  $requestURL->setData(array('categoryId'=>$categories));
	}else{
	  return "https://www.shiksha.com";
	}
	if($preferredCity != ""){
	  $requestURL->setData(array('cityId'=>$preferredCity));
	}elseif($city != ""){
	  $requestURL->setData(array('cityId'=>$city));
	}else{
	  $requestURL->setData(array('cityId'=>"1"));
	}
	$finalUrl = $requestURL->getURL();
	error_log (" NAKUSA final url". $finalUrl);
	return $finalUrl;
/*
    $preferredLoc = array();
    if($preferredCity != '')
    {
        $csvArr = split(",",$preferredCity);
        for($ijk = 0;$ijk < count($csvArr);$ijk++) {
            if(trim($csvArr[$ijk]) != ""){
                preg_match('/\d+/',$csvArr[$ijk],$matches);
                if(count($matches) >0) {
                    array_push($preferredLoc, $csvArr[$ijk]);
                }
            }
        }
        $finalUrl = "";
        $categoryUrl = "";
        $categoryUrlForCPR = ""; // Added by Amit Singhal for CPR changes.
        global $categoryMap;
        foreach ($categoryMap as $categoryName=>$categoryData) {
            if($categoryData['id'] == $categories) {
                $pageName = strtoupper('SHIKSHA_'. $categoryName .'_HOME');
                $categoryUrlForCPR  = constant($pageName);
                $tempCategory = $categoryName;
            }
        }
        if(strstr($categoryUrlForCPR,"getCategoryPage") > -1) {
            $categoryUrl = $categoryUrlForCPR;
        } else {
            $categoryUrl = $categoryUrlForCPR.'/getCategoryPage/colleges/'.$tempCategory;
        }
        $finalUrl .=$categoryUrl."/India/";
        $finalPreferredLoc = "";
        $finalPreferredLocName = "";
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId, 1);
        $cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
        $cityListTier3 = $categoryClient->getCitiesInTier($appId,3,2);
        $subCategoriesAll = $categoryClient->getSubCategories($appId,$categories);
        $subCategoryName  = '';
        foreach ($subCategoriesAll as $key=>$value) {
            if($value['boardId'] == $subCategory) {
                $subCategoryName = $value['urlName'];
            }
        }
        if($subCategoryName == '')
        $subCategoryName = 'All';
        //India/278/Creative-Arts-Commercial-Arts-Performing-Arts/Bangalore
        //Logic : If only one location is selected in preferred location that location will be taken
        //In case of mulitple locations : If the preferred location has his city of residence then take his city of residence.
        //Else any tier 1 city is selected
        //If no tier one city then tier 2 city
        // If no tier 2 city then ??
        if(count($preferredLoc) == 1) {
            $finalPreferredLoc = trim($preferredLoc[0]);
        } else {
            for($klj = 0; $klj< count($preferredLoc);$klj++) {
                if($preferredLoc[$klj] == $city) {
                    $finalPreferredLoc=trim($preferredLoc[$klj]);
                }
            }
            if($finalPreferredLoc == "") {
                for($klj = 0; $klj< count($preferredLoc);$klj++) {
                    foreach($cityListTier1 as $cityTemp)
                    {
          //              error_log("GHJ In teir One Check ".$preferredLoc[$klj]." ".$cityTemp['cityId']);
                        if(trim($preferredLoc[$klj]) == trim($cityTemp['cityId'])) {
                        $finalPreferredLocName = $cityTemp['cityName'];
                        $finalPreferredLoc=trim($preferredLoc[$klj]);
                        break;
                        }
                    }
                    if($finalPreferredLoc != "") {
                        break;
                    }
                }
            }
            if($finalPreferredLoc == "") {
                for($klj = 0; $klj< count($preferredLoc);$klj++) {
                    foreach($cityListTier2 as $cityTemp)
                    {
            //            error_log("GHJ In teir One Check ".$preferredLoc[$klj]." ".$cityTemp['cityId']);

                        if(trim($preferredLoc[$klj]) == trim($cityTemp['cityId'])) {
                            $finalPreferredLocName = $cityTemp['cityName'];
                            $finalPreferredLoc=trim($preferredLoc[$klj]);
                            break;
                        }
                    }
                    if($finalPreferredLoc != "") {
                        break;
                    }
                }
            }
            if($finalPreferredLoc == "") {
              //  error_log("GHJ teir One Check Failed");
                $finalPreferredLoc = trim($preferredLoc[0]);
            }
        }
        //error_log("GHJ Loc10".$finalPreferredLoc);
        if($finalPreferredLocName == "") {
            foreach($cityListTier1 as $cityTemp)
            {
                if($finalPreferredLoc == $cityTemp['cityId']) {
                    $finalPreferredLocName = $cityTemp['cityName'];
                    break;
                }
            }
        }
        if($finalPreferredLocName == "") {
            foreach($cityListTier2 as $cityTemp)
            {
          //      error_log("GHJ ".$cityTemp['cityId']." ".$finalPreferredLoc);
                if($finalPreferredLoc == $cityTemp['cityId']) {
                    $finalPreferredLocName = $cityTemp['cityName'];
                    break;
                }
            }
        //    error_log("GHJ In tier 2 city if");
        }
        if($finalPreferredLocName == "") {
            foreach($cityListTier3 as $cityTemp)
            {
          //      error_log("GHJ ".$cityTemp['cityId']." ".$finalPreferredLoc);
                if($finalPreferredLoc == $cityTemp['cityId']) {
                    $finalPreferredLocName = $cityTemp['cityName'];
                    break;
                }
            }
            //error_log("GHJ In tier 2 city if");
        }
        //error_log("GHJ Loc1 ".$finalPreferredLoc);
        //error_log("GHJ Name1 ".$finalPreferredLocName);
        $finalPreferredLocName = str_replace("/","-",$finalPreferredLocName);
        /* The following condition has been added by Amit Singhal on 8th March 2011 for Catagory page Project for Ticket #171     */
        /*if($urlsuffix == ""){ // The defualt case goes for LDB team..
            $finalUrl .= $finalPreferredLoc."/".$subCategoryName."/".$finalPreferredLocName;
        }elseif($urlsuffix == "--DOMAIN--"){ // CPR changes: if Course URL is Domain name...
            $finalUrl = $categoryUrlForCPR;
        }else{ // CPR changes: if Course URL is other than Domain name...
             $finalUrl .= $finalPreferredLoc."/All/".$finalPreferredLocName."/categorypages".$urlsuffix;
        }
        $this->load->library('marketingClient');
        //error_log("111111111111111KLJ");
	error_log(' LDBSERVER :: Find URL END NAKUSA ' . microtime(true). $finalUrl);
    }
    return $finalUrl;
*/
}
    /**
     * Function for user from home
     *
     * @param string $secCodeSessionVar
     */
    function userfromhome($secCodeSessionVar='seccodehome')
    {
        $this->init();
        $email = trim($this->input->post('homeemail', true));
        $password = trim($this->input->post('homepassword'));
        $country = $this->input->post('countryofresidence1', true);
        $city = $this->input->post('citiesofresidence1', true);
        $mobile = $this->input->post('homephone', true);
        $educationLevel = $this->input->post('homehighesteducationlevel', true);
        $age = $this->input->post('homeYOB', true);
        $categories = $this->input->post('board_id', true);
        $gender = $this->input->post('homegender', true);
        $mdpassword = sha256($password);
        $register_client = new Register_client();
        $appId = 1;
        $displayname = htmlentities(addslashes(trim($this->input->post('homename', true))));
        $firstName = $this->input->post('homename', true);
        $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
        error_log($displayname."    ");
        error_log("dispname".$displayname."    ");
        while($responseCheckAvail == 1){
            $displayname = $this->input->post('homename', true) . rand(1,1000);
            $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
        }
        $viamobile = 1;
        $viamail = 1;
        $newsletteremail =1;
        $profession = '';
        if($educationLevel == "School")
        {
            $userstatus = "School";
            $educationLevel = "School";
        }
        else
            $userstatus = "College";
        $experience = null;

        $appID = 1;
        $resolution = $this->input->post('resolutionreg', true);
        $sourceurl = $this->input->post('refererreg', true);
        $sourcename = 'HOME_REGISTRATION_FORM';
        $userarray['sourceurl'] = $sourceurl;
        $userarray['sourcename'] = $sourcename;
        $userarray['resolution'] = $resolution;
        $userarray['appId'] = 1;
        $userarray['email'] = $email;
        $userarray['password'] = $password;
        $userarray['mdpassword'] = $mdpassword;
        $userarray['displayname'] = $displayname;
        $userarray['country'] = $country;
        $userarray['city'] = $city;
        $userarray['age'] = $age;
        $userarray['mobile'] = $mobile;
        $userarray['educationLevel'] = $educationLevel;
        $userarray['youare'] = $userstatus;
        $userarray['usergroup'] = 'requestinfouser';
        $userarray['firstname'] = $firstName;
        $userarray['gender'] = $gender;
        $userarray['categories'] = $categories;
        $userarray['quicksignupFlag'] = "requestinfouser";
        if($email == '' || $displayname == '' || $password == '')
        {
            echo "Blank" ;
            exit;
        }
        else
        {
            $userarray['usergroup'] = 'requestinfouser';
            $secCode = $this->input->post('homesecurityCode', true);
            if(verifyCaptcha($secCodeSessionVar,$secCode1))
            {
                $addResult = $register_client->adduser_new($userarray);
                if($addResult['status'] > 0)
                {
                    $Validate = $this->checkUserValidation();
                    if(!isset($Validate[0]['userid'])){
                        $value = $email.'|'.$mdpassword;
                        $this->cookie($value);
                    }

                    $this->load->library('user/UserLib');
                    $userLib = new UserLib;
                    $userLib->sendEmailsOnRegistration($addResult['status'], array(), $password);
                    $userLib->updateUserData($addResult['status']);
                    
                    echo $password;

                }
                else
                {
                    if($addResult['status'] == -1)
                    {
                        if($addResult['email'] == -1 && $addResult['displayname'] == -1)
                            echo 'both';
                        else
                        {
                            if($addResult['email'] == -1)
                                echo 'email';
                            if($addResult['displayname'] == -1)
                                echo 'displayname';
                        }

                    }
                    else
                        echo 0;
                }
            }
            else
                echo "code";
        }
    }

    /**
     * Function for very quick registration for AnA
     */
    function veryQuickRegistrationForAnA(){
        $this->init();
        $userarray['email'] = trim($this->input->post('quickemail_ForAnA'));
        $userarray['password'] = addslashes($this->input->post('quickpassword_ForAnA'));
        $confirmpassword = addslashes($this->input->post('quickconfirmpassword_ForAnA'));
        $userarray['ePassword'] = sha256($userarray['password']);
        $userarray['firstname'] = htmlentities(addslashes(trim($this->input->post('quickfirstname_ForAnA'))));
        $userarray['lastname'] = htmlentities(addslashes(trim($this->input->post('quicklastname_ForAnA'))));
        $userarray['displayname'] = htmlentities(addslashes(trim($this->input->post('quickfirstname_ForAnA'))));
        $userarray['coordinates'] = $this->input->post('coordinates_ForAnA');
        $userarray['resolution'] = $this->input->post('resolution_ForAnA');
        $userarray['sourceurl'] = $this->input->post('referer_ForAnA');
        $userarray['sourcename'] = $this->input->post('loginproductname_ForAnA');
        //Add Mobile in the Quick registration form
        $quickmobile_ForAnA = $this->input->post('quickmobile_ForAnA', true);
        if(isset($quickmobile_ForAnA))
            $userarray['mobile'] = $this->input->post('quickmobile_ForAnA');

        $userarray['quicksignupFlag'] = 'veryshortregistration';
        $userarray['usergroup'] = 'veryshortregistration';
        $secCodeIndex = 'secCodeForAnAReg';
        $secCode = $this->input->post('securityCode_ForAnA');
		$this->load->model('UserPointSystemModel');
		echo $this->UserPointSystemModel->doQuickRegistration($userarray,$secCode,$secCodeIndex);
    }

    /**
     * Function for quick signup
     */
    function quicksignup()
    {
        $this->init();
        $userarray['email'] = trim($this->input->post('quickemail'));
        $userarray['password'] = addslashes($this->input->post('quickpassword'));
        $confirmpassword = addslashes($this->input->post('quickconfirmpassword'));
        $userarray['ePassword'] = sha256($userarray['password']);
        $userarray['displayname'] = htmlentities(addslashes(trim($this->input->post('quickfirstname'))));
        $quickeducation = $this->input->post('quickeducation', true);
        $userarray['educationLevel']= isset($quickeducation)?htmlentities(addslashes(trim($this->input->post('quickeducation')))):'';
        $countryofquickreg = $this->input->post('countryofquickreg', true);
        $userarray['country'] = isset($countryofquickreg) ? htmlentities(addslashes(trim($this->input->post('countryofquickreg')))) : '';
        $citiesofquickreg = $this->input->post('citiesofquickreg', true);
        $userarray['city'] = isset($citiesofquickreg) ? htmlentities(addslashes(trim($this->input->post('citiesofquickreg')))) : '';
        $userarray['coordinates'] = $this->input->post('coordinates');
        $userarray['resolution'] = $this->input->post('resolution');
        $register_client = new Register_client();
        $userarray['firstname'] = htmlentities(addslashes(trim($this->input->post('quickfirstname'))));
        $userarray['lastname'] = htmlentities(addslashes(trim($this->input->post('quicklastname'))));
        $quickgender = $this->input->post('quickgender', true);
        $userarray['gender'] = isset($quickgender) ? $this->input->post('quickgender') : '';
        $quicklandlineno = $this->input->post('quicklandlineno', true);
        $landline = isset($quicklandlineno) ? $this->input->post('quicklandlineno') : '';
        $quicklandlineext = $this->input->post('quicklandlineext', true);
        $ext = isset($quicklandlineext) ? $quicklandlineext : '';
        $userarray['landline'] = $ext.$landline;
        $quickmobileno = $this->input->post('quickmobileno', true);
        $userarray['mobile'] = isset($quickmobileno) ? $this->input->post('quickmobileno') : '';
        $quickinterest = $this->input->post('quickinterest', true);
        $userarray['categories'] = isset($quickinterest) ? $this->input->post('quickinterest') : '';
        $quickage = $this->input->post('quickage', true);
        $userarray['age'] = isset($quickage) ? $this->input->post('quickage') : '';
        $userarray['sourceurl'] = $this->input->post('referer');
        $userarray['sourcename'] = $this->input->post('loginproductname');
        $userarray['quicksignupFlag'] = 'quicksignupuser';
        $userarray['usergroup'] = 'quicksignupuser';
        $secCodeIndex = 'seccode1';
        $secCode = $this->input->post('securityCode');
		$this->load->model('UserPointSystemModel');
		echo $this->UserPointSystemModel->doQuickRegistration($userarray,$secCode,$secCodeIndex);
    }

    /**
     * Function to submit
     */
    function submit()
    {
        error_log(print_r($_POST,true).'POSTDATA');
        $tempuser = $this->input->post('tempvalue');
        $resolution = $this->input->post('resolutionreg');
        $sourceurl = $this->input->post('refererreg');
        $sourcename = 'MAIN_REGISTRATION_FORM';
        if($tempuser != 1)
        {
            $imageUrl = $this->input->post('avtar');
        }
        else
            $imageUrl = "i";
        if($imageUrl == "upload" && $_FILES['myImage']['tmp_name'][0] == '')
            echo 'No Photo';
        else{
            $url = $this->input->post('avtar1');
            $this->init();
            $usergroup = $this->input->post('usergroup');
            $editform = $this->input->post('editform');
            $requestinfo = $this->input->post('requestinfo');
            if($editform == 0)
            {
                $email = trim($this->input->post('email'));
                $displayname = htmlentities(addslashes(trim($this->input->post('displayname'))));
                if($email == '' || $displayname == '')
                {
                    echo "Blank";
                    exit;
                }

            }
            if($editform == 0)
            {
                $password = addslashes($this->input->post('passwordr'));
                $confirmpassword = addslashes($this->input->post('confirmpassword'));
                $mdpassword = sha256($password);
                $bypass = $this->input->post('b_1_p');
                if($password == '')
                {
                    echo "Blank";
                    exit;
                }
            }
            $userdetail = $this->input->post('detail');
            error_log($requestinfo.'REQUESTINFO');
            error_log($tempuser.'TEMPUSER');
            if($requestinfo != 1 && $tempuser != 1)
            {
                $firstname = htmlentities(addslashes($this->input->post('firstname')));
                $lastname = htmlentities(addslashes($this->input->post('lastname')));
            }
            $institute = $this->input->post('schoolCombo');
            $otherinsti = $this->input->post('otherinstitute');
            if($otherinsti != '')
                $institute = $otherinsti;
            $countryofedu = $this->input->post('countryofeducation');
            $cityofedu = $this->input->post('citiesofeducation');
            if($countryofedu == "Other")
            {
                $countryofedu = $this->input->post('countryofeducation_other');
                $cityofedu = $this->input->post('citiesofeducation_other');
                $institute = htmlentities(addslashes(trim($this->input->post('otherinstitute'))));
            }
            if($cityofedu == "-1")
            {
                $cityofedu = $this->input->post('citiesofeducation_other');
                $institute = htmlentities(addslashes(trim($this->input->post('otherinstitute'))));
            }
            if($institute == "Other")
                $institute = htmlentities(addslashes(trim($this->input->post('otherinstitute'))));
            $categories = $this->input->post('categoryPlace');
            $cityofhigheducation = $this->input->post('citiesofhighereducation');
            $countrieshigheredu  =  $this->input->post('countryofhighereducation');
            if($requestinfo != 1)
            {
                $country = trim($this->input->post('countryofresidence'));
                if($country == "Other")
                {
                    $country = htmlentities(addslashes(trim($this->input->post('countryofresidence_other'))));
                    $city = htmlentities(addslashes(trim($this->input->post('citiesofresidence_other'))));
                }
                else
                {
                    $city = trim($this->input->post('citiesofresidence'));
                    if($city == "-1")
                    {
                        $city = htmlentities(addslashes(trim($this->input->post('citiesofresidence_other'))));

                    }
                }
            }

            $gradYear = $this->input->post('GradYear');
            if($requestinfo != 1 && $tempuser != 1)
            {
                $mobile = trim($this->input->post('mobile'));
                $landline = trim($this->input->post('landlineext')).trim($this->input->post('landlineno'));
                $usergender = trim($this->input->post('usergender'));
            }
            $collegeId = 1;
            if($tempuser != 1)
            {
                $viamobile = $this->input->post('viamobile');
                $viamail = $this->input->post('viaemail');
                $DOB = $this->input->post('DOB');;
                $newsletteremail = $this->input->post('newsletteremail');

                if($viamobile == "mobile")
                    $viamobile = 1;
                else
                    $viamobile = 0;

                if($viamail == "email")
                    $viamail = 1;
                else
                    $viamail = 0;

                if($newsletteremail == "newsletteremail")
                    $newsletteremail = 1;
                else
                    $newsletteremail = 0;
            }
            $youare = $this->input->post('status');
            $profession = $this->input->post('profession');
            $educationLevel = $this->input->post('EducationCombo');
            $experience = $this->input->post('ExperienceCombo');
            error_log($experience.'EXPERIENCE');
            if($educationLevel == "Other")
                $educationLevel = $this->input->post('otherEducation');

            if($educationLevel == '')
                $educationLevel = 0;
            if($youare == "School")
            {
                $profession = "";
                $educationLevel = "School";
                $experience = null;
            }

            if($youare == "College")
            {
                $profession = "";
                $experience = null;
            }

            $appID = 1;
            $register_client = new Register_client();
            $userarray['appId'] = 1;
            $userarray['sourceurl'] = $sourceurl;
            $userarray['sourcename'] = $sourcename;
            $userarray['resolution'] = $resolution;
            if($ediform == 0)
            {
                $userarray['password'] = $password;
                $userarray['mdpassword'] = $mdpassword;
            }
            if($editform == 0)
            {
                $userarray['email'] = $email;
                $userarray['displayname'] = $displayname;
            }
            else
            {
                $Validate = $this->checkUserValidation();
                if(isset($Validate[0]['userid']))
                    $userarray['userid'] = $Validate[0]['userid'];
            }
            if($requestinfo != 1 && $tempuser != 1)
            {
                $userarray['country'] = $country;
                $userarray['city'] = $city;
                $userarray['mobile'] = $mobile;
                $userarray['landline'] = $landline;
                $userarray['gender'] = $usergender;
            }
            if($tempuser != 1)
            {
                $userarray['viamobile'] = $viamobile;
                $userarray['viamail'] = $viamail;
                $userarray['vianewsletteremail'] = $newsletteremail;
                $userarray['DOB'] = $DOB;
            }
            $userarray['educationLevel'] = $educationLevel;
            $userarray['experience'] = $experience;
            $userarray['institute'] = $institute;
            $userarray['youare'] = $youare;
            $userarray['gradYear'] = $gradYear;
            $userarray['usergroup'] = 'user';
            if($requestinfo != 1 && $tempuser != 1)
            {
                $userarray['firstname'] = $firstname;
                $userarray['lastname'] = $lastname;
            }
            $userarray['categories'] = $categories;
            $userarray['cityofhighereducation'] = $cityofhigheducation;
            $userarray['countries'] = $countrieshigheredu;
            $userarray['countryofeducation'] = $countryofedu;
            $userarray['cityofeducation'] = $cityofedu;

            $secCode = $this->input->post('securityCode');
            if(verifyCaptcha('seccode1',$secCode,1))
            {
                if($editform == 0)
                {
                    $addResult = $register_client->adduser_new($userarray);
                    if($addResult['status'] > 0)
                    {
                        $Validate = $this->checkUserValidation();
                        if(!isset($Validate[0]['userid'])){
                            $value = $email.'|'.$mdpassword;
                            $this->cookie($value);
                        }

                        $this->load->library('user/UserLib');
                        $userLib = new UserLib;
                        $userLib->sendEmailsOnRegistration($addResult['status'], array(), $password);
                        $userLib->updateUserData($addResult['status']);

                        if($imageUrl != '')
                        {
                            if($imageUrl == "profilepic")
                            {     $response = $this->updateUserAttribute($appId, $addResult['status'], 'userImage',$url);
                            }
                            else
                            {
                                $this->load->library('Upload_client');

                                $uploadClient = new Upload_client();
                                $upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$addResult['status'],"user", 'myImage');
                                if(is_array($upload))
                                {
                                    $response = $this->updateUserAttribute($appId, $addResult['status'], 'userImage',$upload[0]['imageurl']);
                                }
                                else
                                {
                                    $response = $this->updateUserAttribute($appId, $addResult['status'], 'userImage','/public/images/photoNotAvailable.gif');
                                    $this->load->library('Mail_client');
                                    $mail_client = new Mail_client();
                                    $receiverIds = array();
                                    array_push($receiverIds,$addResult['status']);
                                    $subject = "Image Upload Failed while Registration";
                                    $body = "Hello ".$displayname." <br/> Your image uploading failed at the time of Registration.Please go to Accounts and Settings in Shiksha to upload your photo";

                                    $content = 0;
                                    $sendmail = $mail_client->send($appId,1,$receiverIds,$subject,$body,$content);

                                }
                                echo $addResult['status'];
                            }
                        }

                    }
                    else
                    {
                        if($addResult['status'] == -1)
                        {
                            if($addResult['email'] == -1 && $addResult['displayname'] == -1)
                                echo 'both';
                            else
                            {
                                if($addResult['email'] == -1)
                                    echo 'email';
                                if($addResult['displayname'] == -1)
                                    echo 'displayname';
                            }

                        }
                        else
                            echo 0;
                    }
                }
                else
                {
                    if($requestinfo == 1)
                        $userarray['requestuser'] = "requestinfo";
                    if($tempuser == 1)
                        $userarray['requestuser'] = "tempuser";
                    $values = explode("|",$_COOKIE['user']);
                    $uname = $values[0];
                    $addResult = $register_client->updateuserinfo($userarray);
                    if($addResult['status'] > 0)
                    {
                        if($imageUrl != '' && !$tempuser)
                        {
                            if($imageUrl == "profilepic")
                            {     $response = $this->updateUserAttribute($appId, $addResult['status'], 'userImage',$url);
                            }
                            else
                            {
                                $this->load->library('Upload_client');

                                $uploadClient = new Upload_client();
                                $upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$addResult['status'],"user", 'myImage');
                                if(is_array($upload))
                                {
                                    $response = $this->updateUserAttribute($appId, $addResult['status'], 'userImage',$upload[0]['imageurl']);
                                }
                                else
                                {
                                    $response = $this->updateUserAttribute($appId, $addResult['status'], 'userImage','/public/images/photoNotAvailable.gif');
                                    $this->load->library('Mail_client');
                                    $mail_client = new Mail_client();
                                    $receiverIds = array();
                                    array_push($receiverIds,$addResult['status']);
                                    $subject = "Image Upload Failed while Registration";
                                    $body = "Hello ".$displayname." <br/> Your image uploading failed at the time of Registration.Please go to Accounts and Settings in Shiksha to upload your photo";

                                    $content = 0;
                                    $sendmail = $mail_client->send($appId,1,$receiverIds,$subject,$body,$content);

                                }
                                echo $addResult['status'];
                            }
                        }
                        echo $addResult;
                    }
                }
            }
        }


    }
    
    /**
     * Function to update user attribute
     *
     * @param integer $appId
     * @param integer $userId
     * @param integer $attribute
     * @param integer $attributeValue
     * @return integer
     */
    private function updateUserAttribute($appId, $userId, $attribute, $attributeValue){
        $this->load->library('Register_client');
        $userFieldsMap = array(
                'emailId' => 'email',
                'displayName' => 'displayname',
                'mobile' => 'mobile',
                'userCity' => 'city',
                'userProfession' => 'profession',
                'contactByMobile' => 'viamobile',
                'contactByEmail' => 'viaemail',
                'userImage' => 'avtarimageurl',
                'sendNewsLetterByEmail' => 'newsletteremail'
                );
        if(!array_key_exists($attribute, $userFieldsMap)) {
            return '0';
        }
        $columnName = $userFieldsMap[$attribute];
        $registerClient = new Register_client();
        return $registerClient->updateUserAttribute($appId, $userId, $columnName, $attributeValue,'',1);
    }

    /**
     * Function to set cookie
     *
     * @param integer $value
     */
    function cookie($value)
    {
        $value .= '|pendingverification';
        setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
    }

    /**
     * Function to send verification email
     *
     * @param string $secCode
     */
    function sendverificationEmail($secCode = '')
    {
        $this->init();
        $Validate = $this->checkUserValidation();
        if($Validate == "false"){
            echo 'Invalid user';
        }
        if(verifyCaptcha('seccodesendemail',$secCode,1))
        {
            $Register_client = new Register_client();
            $appId = 1;
            $response = $Register_client->sendverificationMail($appId,$Validate[0]['userid']);
            echo 1;
        }
        else
            echo 'code';
    }

    /**
     * Function to send user response
     *
     * @param string $key 
     * @param string $verifyflag
     */
    function senduserResponse($key,$verifyflag)
    {
        $this->init();
        $this->load->library('Register_client');
        $RegisterClient = new Register_client();
        $appId = 1;
        $cookie = isset($_COOKIE['user']) ? $_COOKIE['user'] : '';
        $response = $RegisterClient->senduserResponse($appId,$key,$verifyflag,$cookie);
        echo $response;
    }

    /**
     * Function to validate captcha
     *
     * @param string $secCode
     * @param string $secvariable
     */
    function validateCaptcha($secCode,$secvariable)
    {
        $this->init();
        $appId = 12;
        $captchResult = "";

        if(isset($secvariable))
        {
            if(verifyCaptcha($secvariable,$secCode))
                $captchResult = "successful";
        }
        else
        {
            if(verifyCaptcha('security_code',$secCode))   //security code in the file displaying the security code.
                $captchResult =  "successful";
        }
        $result = array('captchResult'=> $captchResult);
        echo json_encode($result);

    }
    
    /**
     * Function to check availability
     *
     * @param string $name
     * @param string $type
     */
    function checkAvailability($name,$type)
    {
        if(empty($name) || empty($type)) {
            echo '';
        } else {
            $this->init();
            $appId = 1;
            $Register_client = new Register_client();
            $response = $Register_client->checkAvailability($appId,trim($name),$type);
            if($response == 1)
                echo 1;
            else
                echo '';
        }
    }
    
    /**
     * Function to update user point system
     *
     * @param integer $userId
     * @param string $points
     * @return integer $response
     */
    function updateuserPointSystem($userId,$points)
    {
        $this->init();
        $appId = 1;
        $Register_client = new Register_client();
        $response = $Register_client->updateuserPointSystem($appId,$points,$userId);
        return $response;
    }
    
    /**
     * Function to add user
     */
    function _addUser()
    {
        $this->init();
        $appId = 1;
        $Register_client = new Register_client();
        $userDetailsBasic = array(
                'email'=>'a23by11@b1by.c1m',
                'displayname' => 'a11456ybaby',
                'mobile' => '92120120292',
                'textpassword' => 'ashish',
                'residenceCountry' => 2,
                'residenceCity' => 151,
                'firstname' => 'Aby134',
                'lastname' => 'Babb224y',
                'contactViaMobile' => 1,
                'contactViaEmail' => 0,
                'contactViaNewsLetter' => 1,
                'userGroup' => 'user',
                'age' => '22',
                'gender' => 'Female',
                'locality' => 'Noida',
                'phone'=>'01203424943',
                'sourcename' => 'LISTING_DETAIL_BOTTOM_GET_FREE_ALERTS__COURSE'
            );
        $userDetails = array(
                'email'=>'aqwpz@b1by.c1pm',
                'displayname' => 'abzaeqxx34yp',
                'mobile' => '92120154392',
                'password' => 'ashish',
                'textpassword' => 'ashish',
                'residenceCountry' => 2,
                'residenceCity' => 151,
                'firstname' => 'Aby4',
                'lastname' => 'Babb24y',
                'contactViaMobile' => 1,
                'contactViaEmail' => 0,
                'contactViaNewsLetter' => 1,
                'userGroup' => 'user',
                'age' => '22',
                'gender' => 'Female',
                'locality' => 'Noida',
                'phone'=>'01203424943',
                'sourcename' => 'LISTING_DETAIL_BOTTOM_GET_FREE_ALERTS__COURSE',


                'degreePrefAICTE'=>'yes',
                                'degreePrefUGC'=>'no',
                                'degreePrefInternational'=>'yes',
                                'degreePrefAny'=>'yes',
                                'modeOfEducationFullTime'=>'no',
                                'modeOfEducationPartTime'=>'no',
                                'modeOfEducationDistance'=>'yes',
                                'timeOfStart'=> '2009-09-22',
                                'userDetail'=>'ASHish writing something',
                                'sourceInfo' => 'registration',

                'countryId' => array(2,3),
                'stateId' => array(71,72),
                'cityId' => array(10224, 10323),


                'name' => array('CAT','','',''),
                'instituteId' => array('','551','559','226'),
                'level' => array('Competitive exam','12','PG','UG'),
                'marks' => array('98','99','88','87'),
                'marksType' => array('percentile','percentage','percentage','percentage')

            );
        $response = $Register_client->addUser($appId,$userDetails);
        print_r($response);
    }
    
    /**
     * Function to update user
     */
    function _updateUser()
    {
        $this->init();
        $appId = 1;
        $Register_client = new Register_client();
        $userDetails = array(
                'email'=>'aqwpz@b1by.c1pm',
                'displayname' => 'abzaeqxx34yp',
                'mobile' => '92120154392',
                'textpassword' => 'ashish1',
                'residenceCountry' => 2,
                'residenceCity' => 1511,
                'firstname' => 'Aby4',
                'lastname' => 'Babb24y',
                'contactViaMobile' => 1,
                'contactViaEmail' => 0,
                'contactViaNewsLetter' => 1,
                'userGroup' => 'user',
                'age' => '24',
                'gender' => 'Female',
                'locality' => 'delhi/ncr',
                'phone'=>'01203424',
                'sourcename' => 'LISTING_DETAIL_BOTTOM_GET_FREE_ALERTS__COURSE',

                'degreePrefAICTE'=>'no',
                                'degreePrefUGC'=>'no',
                                'degreePrefInternational'=>'no',
                                'degreePrefAny'=>'yes',
                                'modeOfEducationFullTime'=>'no',
                                'modeOfEducationPartTime'=>'no',
                                'modeOfEducationDistance'=>'no',
                                'timeOfStart'=> '2009-09-22',
                                'userDetail'=>'ASHish writing something for details',
                                'sourceInfo' => 'registration',

                'countryId' => array(2,3),
                'stateId' => array(71,72),
                'cityId' => array(10224, 10323),


                'name' => array('CAT','','',''),
                'instituteId' => array('','551','559','226'),
                'level' => array('Competitive exam','12','PG','UG'),
                'marks' => array('98','99','88','87'),
                'marksType' => array('percentile','percentage','percentage','percentage'),
                'educationStatus' => array('deleted','live','live','live')

// For Education details of user pass an extra field for 'educationStatus' as 'deleted'
            );
    //294603 = $userId
        $response = $Register_client->updateUser($appId,$userDetails, 294063);
        print_r($response);
    }

    /**
     * Function to migrate user to new schema
     *
     * @param integer $userId
     */
    function migrateUserToNewSchema($userId)
    {
        $this->init();
        $finalArray = array();
        $appId =12;
        // Get the user details related to highest education,user state pref,specializationid


        $Register_client = new Register_client();

        $prefDetails = $Register_client->getPreferencesForUser($appId , $userId);
        if(count($prefDetails) < 1)
        {
            $response = $Register_client->getUserDetailForMigration($appId,$userId);
            $userarray = $response[0];
            print_r($userarray);
            global $ug_course_mapping_array;
            global $pg_course_mapping_array;
            error_log(print_r($userarray,true).'USERARRAY');
            if(isset($userarray['category']) && !empty($userarray['category']))
            {
                foreach(explode(",",$userarray['category']) as $value)
                {
                    echo "asdasdsadsas   ".$value;
                    if($value == "Study Abroad")
                    {
                        $finalArray['extraFlag'] = 'studyabroad';
                    }
                    else
                    {
                        $desiredCourse = $Register_client->getSpecializationForCategory($value);
                        if($desiredCourse != "" && is_numeric($desiredCourse))
                        {
                            $finalArray['desiredCourse'][] = $desiredCourse;
                        }
                        else
                        {
                            $finalArray['desiredCourse'][] = '';
                        }
                    }
                }
            }


            if(isset($userarray['graduationyear']) && !empty($userarray['graduationyear']))
            {
                if($userarray['graduationyear'] >= date('Y'))
                {
                    $finalArray['ongoingCompletedFlag'][] = '1';
                }
                else
                {
                    $finalArray['ongoingCompletedFlag'][] = '0';
                }
            }

            if(isset($userarray['educationLevel']) && !empty($userarray['educationLevel']))
            {
                error_log(array_key_exists($ug_course_mapping_array[$userarray['educationLevel']]).'USERARRAY');
                if(array_key_exists($userarray['educationLevel'],$ug_course_mapping_array))
                {
                    $finalArray['name'][] =  $ug_course_mapping_array[$userarray['educationLevel']];
                    $finalArray['level'][] =  'UG';
                }
                if(array_key_exists($userarray['educationLevel'],$pg_course_mapping_array))
                {
                    $finalArray['name'][] =  $pg_course_mapping_array[$userarray['educationLevel']];
                    $finalArray['level'][] =  'PG';
                }
            }

            if(isset($userarray['countryofEducation']) && !empty($userarray['countryofEducation']))
            {
                if(is_numeric($userarray['countryofEducation']))
                {
                    $finalArray['country'][] = $userarray['countryofEducation'];
                }
                else
                {
                    $countryDetails = $Register_client->getIdForCityName($userarray['cityofEducation']);
                    if(isset($countryDetails[0]['countryId']))
                    {
                        $finalArray['country'][] = $countryDetails[0]['countryId'];
                    }
                }
            }
            if(isset($userarray['cityofEducation']) && !empty($userarray['cityofEducation']))
            {
                if(is_numeric($userarray['cityofEducation']))
                {
                    $finalArray['city'][] = $userarray['cityofEducation'];
                }
                else
                {
                    $cityDetails = $Register_client->getIdForCityName($userarray['cityofEducation']);
                    if(isset($cityDetails[0]['city_id']))
                    {
                        $finalArray['city'][] = $cityDetails[0]['city_id'];
                    }
                }
            }
            if(isset($userarray['graduationyear']) && ($userarray['graduationyear'] != 0) && ($userarray['graduationyear'] != ""))
            {
                $finalArray['courseCompletionDate'][] = date('Y/j/n',strtotime('1/1/'.$userarray['graduationyear']));
            }
            if(isset($userarray['institute']) && is_numeric($userarray['institute']))
            {
                $finalArray['institute'] = $userarray['institute'];
                if(is_numeric($userarray['institute']) || $userarray['userstatus'] != 'School')
                    $finalArray['instituteId'][] = $userarray['institute'];
            }
            if(isset($userarray['city']) && !empty($userarray['city']))
            {
                foreach(explode(",",$userarray['city']) as $value)
                {
                    if($value != "")
                    {
                        if(is_numeric($value))
                        {
                            $finalArray['cityId'][] = $value;
                            $finalArray['stateId'][] = 0;
                            $finalArray['countryId'][] = 2;
                        }
                        else
                        {
                            $cityDetails = $Register_client->getIdForCityName($value);
                            if(isset($cityDetails[0]['city_id']))
                            {
                                $finalArray['cityId'][] = $cityDetails[0]['city_id'];
                                $finalArray['stateId'][] =  $cityDetails[0]['state_id'];
                                $finalArray['countryId'][] = $cityDetails[0]['countryId'];
                            }
                        }
                    }
                }
            }
            if(isset($userarray['country']) && !empty($userarray['country']))
            {
                foreach(explode(",",$userarray['country']) as $value)
                {
                    $finalArray['cityId'][] = 0;
                    $finalArray['stateId'][] = 0;
                    if(is_numeric($value))
                    {
                        if($value != 2)
                        {
                            $finalArray['extraFlag'] = 'studyabroad';
                        }
                        $finalArray['countryId'][] = $value;
                    }
                    else
                    {
                        global $countryArrayForMigration;
                        if(isset($countryArrayForMigration[$value]))
                        {
                            $finalArray['extraFlag'] = 'studyabroad';
                            $finalArray['countryId'][] = $countryArrayForMigration[$value];
                        }
                        else
                        {
                            $finalArray['countryId'][] = $value;
                        }
                    }
                }
            }


            $response = $Register_client->updateUser($appId, $finalArray, $userId);

            echo "<pre>";

            print_r($response);

            print_r($finalArray);
            echo "</pre>";
            exit(1);
        }

    }

    /**
     * cookie for registration
     *
     * @param array $userarray
     */
private function cookForRegistration(& $userarray) {
    $homeemail = $this->input->post('homeemail', true);
    if(isset($homeemail))
        $userarray['email'] =  trim($homeemail);
        
    if(isset($_POST['homepassword'])) {
        $userarray['textPassword'] =  trim($_POST['homepassword']);
        $userarray['password'] = md5($userarray['textPassword']);
    }
    
    $countryofresidence1 = $this->input->post('countryofresidence1', true);
    if(isset($countryofresidence1))
        $userarray['residenceCountry'] =  trim($countryofresidence1);
        
    $citiesofresidence1 = $this->input->post('citiesofresidence1', true);
    if(isset($citiesofresidence1))
        $userarray['residenceCity'] =  trim($citiesofresidence1);
        
    $homephone = $this->input->post('homephone', true);
    if(isset($homephone))
        $userarray['mobile'] =  trim($homephone);
        
    $homehighesteducationlevel = $this->input->post('homehighesteducationlevel', true);
    if(isset($homehighesteducationlevel))
        $userarray['educationlevel'] =  trim($homehighesteducationlevel);
        
    $homeYOB = $this->input->post('homeYOB', true);
    if(isset($homeYOB))
        $userarray['age'] =  trim($homeYOB);
        
    $board_id = $this->input->post('board_id', true);
    if(isset($board_id))
        $userarray['categories'] =  trim($board_id);
        
    $gender = $this->input->post('gender', true);
    if(isset($gender))
        $userarray['gender'] =  trim($gender);
        
    $homesubCategories = $this->input->post('homesubCategories', true);
    if(isset($homesubCategories))
        $userarray['subCategories'] =  trim($homesubCategories);
        
    $firstname = $this->input->post('firstname', true);
    if(isset($firstname)) {
        $userarray['displayname'] = $userarray['firstName'] = htmlentities(addslashes(trim($firstname)));
    }
    
    $lastname = $this->input->post('lastname', true);
    if(isset($lastname)) {
        $userarray['lastName'] = htmlentities(addslashes(trim($lastname)));
    }
    
    $resolutionreg = $this->input->post('resolutionreg', true);
    if(isset($resolutionreg))
        $userarray['resolution'] = trim($resolutionreg);
        
    $refererreg = $this->input->post('refererreg', true);
    if(isset($refererreg))
        $userarray['sourceurl'] = trim($refererreg);
        
    $plan = $this->input->post('plan', true);
    if(isset($plan))
        $userarray['timeOfStart'] = trim($plan);
        
    $viamobile = $this->input->post('viamobile', true);
    if(isset($viamobile))
        $userarray['viamobile'] = $viamobile != '' ? $viamobile : '0';
    
    $viamail = $this->input->post('viamail', true);
    if(isset($viamail))
        $userarray['viamail'] =  $viamail != '' ? $viamail : '0';
        
    $vianewsletteremail = $this->input->post('vianewsletteremail', true);
    if(isset($vianewsletteremail))
        $userarray['vianewsletteremail'] =  $vianewsletteremail != '' ? $vianewsletteremail : '0';
        
    $age = $this->input->post('age', true);
    if(isset($age))
        $userarray['age']= $age;
        
    $DOB = $this->input->post('DOB', true);
    if(isset($DOB))
        $userarray['age'] = $DOB;
        
    $gender = $this->input->post('gender', true);
    if(isset($gender))
        $userarray['gender']=$gender;
        
    $landline = $this->input->post('landline', true);
    if(isset($landline))
        $userarray['phone']=$landline;
        
    $experience = $this->input->post('experience', true);
    if(isset($experience))
        $userarray['experience']=$experience;

    // 12th Details
    $xii_passing_year = $this->input->post('10_com_year_year');
    $xii_percentage = $this->input->post('10_ug_detials_courses_marks');
    // 12th Stream [science_arts,science_commerce,science_stream]
    $xii_stream = $this->input->post('science_stream');
    if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
        $educationlevel = 'School';
        $userarray['instituteId'][] = '';
        $userarray['marksType'][] = 'percentage';
        $userarray['level'][] = '12';
        $userarray['name'][] =(!empty($xii_stream)) ? $xii_stream : '';
        $userarray['marks'][] = (!empty($xii_percentage))? $xii_percentage : '';
        if(!empty($xii_passing_year)) {
            $userarray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
            $userarray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
        } else {
            $userarray['courseCompletionDate'][] = '';
            $userarray['ongoingCompletedFlag'][] = '';
        }
    }

    $ug_detials_courses =$this->input->post('ug_detials_courses');
    if (!empty($ug_detials_courses)) {
        $userarray['name'][] = $ug_detials_courses;
        $userarray['level'][] = 'UG';
        global $ug_course_mapping_array;
        foreach($ug_course_mapping_array as $key => $value) {
            if($value == $ug_detials_courses) {
                $educationLevel = $key;
                break;
            }
        }

        $com_year_year = $this->input->post('com_year_year');
        $com_year_month = $this->input->post('com_year_month');
        if (!empty($com_year_year) && !empty($com_year_month)) {
            $userarray['courseCompletionDate'][] = $this->input->post('com_year_year') . '-'.$this->input->post('com_year_month') .'-'. '1';
        } else {
            $userarray['courseCompletionDate'][] = '';
        }
        $userarray['ongoingCompletedFlag'][] = $this->input->post('Completed') == 'completed'?"0":"1";
        if ($this->input->post('Completed') == 'completed') {
            $marks = $this->input->post('ug_detials_courses_marks');
        } else {
            $marks = '';
        }
        $userarray['marks'][] = $marks;
        $userarray['marksType'][] = 'percentage';
    }
    $userarray['educationlevel'] = $educationLevel;
    $userarray['youare'] = $educationLevel;
}

/**
 * Cookie for study abroad
 * @param array $inputArr
 */
private function cookForStudyAbroad(& $inputArr) {
    $appId = 1;
    $categoryId = trim($this->input->post('board_id'));
    $desiredCourseLevel = trim($this->input->post('desiredCourseLevel'));
    $suitableCallPref = trim($this->input->post('suitableCallPref'));
    $inputArr['suitableCallPref'] = $suitableCallPref;
    $inputArr['userFundsNone'] = $this->input->post('UserFundsNone');
    $inputArr['userFundsOwn'] = $this->input->post('UserFundsOwn');
    $inputArr['userFundsBank'] = $this->input->post('UserFundsBank');
    $inputArr['otherFundingDetails'] = $this->input->post('otherFundingDetails');
    $this->load->library('LDB_Client');
    $ldbClientObj = new LDB_Client();
    $desiredCourseDetails = array_pop(json_decode($ldbClientObj->getCourseForCriteria($appId, $categoryId, 'abroad', $desiredCourseLevel), true));
    $inputArr['desiredCourse'] = $desiredCourseDetails['SpecializationId'];
    $compExams = $this->input->post('compExam');
    foreach($compExams as $compExam) {
        $inputArr['name'][] = $compExam;
        $inputArr['marks'][] = $this->input->post($compExam .'_score');
        $inputArr['level'][] = 'Competitive exam';
    }
    $inputArr['countryId'] =  explode(',',trim($this->input->post('mCountryList'), ','));
    $inputArr['cityId'] =  $inputArr['stateId'] =  array_fill(0,count($inputArr['countryId']),0);
    $inputArr['extraFlag'] = 'studyabroad';
}

/**
 * Function for marketing page user operation
 */
function marketingPageUserOperation() {
    $this->init();
    $appId = 1;

    // Check for the availability of the display name if not available generate it using ranndom key else use the name as display name
    $register_client = new Register_client();
    $displayname = $this->input->post('homename', true);
    $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
    while($responseCheckAvail == 1){
        $displayname = $this->input->post('homename') . rand(1,100000);
        $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
    }
    $userarray = array();
    $this->cookForRegistration($userarray);
    $userarray['displayname'] = $displayname;

    $preferredCity = $this->input->post('mCityList');
    $preferredCity =  trim($preferredCity,',');
    $preferredCityName = $this->input->post('mCityListName');
    $mPageName = $this->input->post('mPageName');
    $mcourse = $this->input->post('mcourse');
    $countries = trim($this->input->post('mCountryList'), ',');
    $modeoffinance = $this->input->post('sourceFunding');
    $nearestMetropolitanCity = $this->input->post('mCity');


    // Check availability code ends

    // By default viamobile,viamail,newslettermail to set as 1
    $viamobile = 1;
    $viamail = 1;
    $newsletteremail =1;
    /* User array for marketing table */
    $addReqInfo = array();
    $Validate = $this->checkUserValidation();
    //error_log(print_r($Validate,true));
    if(isset($Validate[0]['userid'])) {
        $exploded = explode('|',$Validate[0]['cookiestr']);
        $email = $exploded[0];
        $password = $exploded[1];
    }
    $flagvalue = 'false';

    $addReqInfo['email'] = $userarray['email'];
    $addReqInfo['displayName'] = $userarray['displayname'];
    $addReqInfo['residenceLoc'] = $userarray['residenceCity'];
    $addReqInfo['age'] = $userarray['age'];
    $addReqInfo['gender'] = $userarray['gender'];
    $addReqInfo['highestQualification'] = $userarray['educationlevel'];
    $addReqInfo['flagRegistered'] = "true";
    $addReqInfo['mobile'] = $userarray['mobile'];
    $addReqInfo['mPageName'] = $mPageName;
    $addReqInfo['marketingFlag'] = $flagvalue;
    $addReqInfo['category'] = $userarray['categories'];
    $addReqInfo['subCategory'] = $userarray['subCategories'];
    $addReqInfo['preferredcity'] = $preferredCity;


    // Make array for LeadKeyValue pair
    $LeadInterest = array('category' => $categories,'subCategory'=>$subCategory,'city'=>$preferredCity,'countries'=> $countries);

    //Make array for keyvalue pair
    if($mPageName == "studyAbroad") {
        $nearestMetropolitanCity = $userarray['residenceCity'];
        $courseStartTime = $userarray['plan'];
        $modeoffinance = '';
        if(!empty($userarray['userFundsNone'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'None';
        }
        if(!empty($userarray['userFundsBank'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'Bank Funds';
        }
        if(!empty($userarray['userFundsOwn'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'Own Funds';
        }
        $keyvalarray = $this->makeLeadKeyValueForStudyAbroad($categories,$countries,$nearestMetropolitanCity,$modeoffinance,$courseStartTime);
    } else {
        $keyvalarray = $this->makeLeadKeyValue($categories,$subCategory,$preferredCity,$mcourse);
    }
    //error_log(print_r($keyvalarray,true));
    $finalUrl =  $this->codeforlandingpage($preferredCity,$categories,$subCategory,$city);
    if($mPageName == "studyAbroad") {
        $countriesSent = explode(",",$this->input->post('mCountryListName'));
        error_log("ShirishSTA 12121".print_r($countriesSent,true));
        $index = rand(0,count($countriesSent)-1);
        $countrySelected = $countriesSent[$index];
        if($countrySelected == "") {
            $countrySelected = $countriesSent[$index-1];
        }
        $countrySelected = str_replace(' ','',$countrySelected);
        global $countries;
        foreach($countries as $key=>$value) {
            if($value['name'] == $countrySelected) {
                $countrySelected = $key;
                break;
            }
        }

        //$countrySelected = "usa";
        $pageName = strtoupper('SHIKSHA_'. $countrySelected.'_HOME');
        $countryUrl = constant($pageName);
        if(strstr($countryUrl,"getCategoryPage") > -1) {
            $countryUrl= $countryUrl;
        } else {
            $countryUrl= $countryUrl.'/getCategoryPage/colleges/studyabroad/'.$countrySelected;
        }
        global $categoryMap;
        foreach($categoryMap as $key=>$value) {
            if($value['id'] == $categories) {
                $categoryNameInUrl = $key;
                break;
            }
        }
        $finalUrl = $countryUrl."/All/".$categoryNameInUrl."/All";
        
        
        $this->load->library('categoryList/categoryPageRequest');
	$requestURL = new CategoryPageRequest();

        error_log("LDBX ".print_r($_POST,true));
        $xdata = array();
        $xdata["categoryId"] = $this->input->post("board_id");
        
        $regions = trim($this->input->post('mRegions'), '#');
        if(!empty($regions)){
            $regionsArr = explode("#",$regions);
            $choice = rand()%count($regionsArr);
            $region = $regionsArr[$choice];
            $xdata["regionId"] = $region;
            error_log("LDBX ".print_r($xdata,true));
            $requestURL->setData($xdata);
            $finalUrl = $requestURL->getURL();
        }
        else{
            $xcountries = explode(",",trim($this->input->post('mCountryList'), ','));
            $this->load->library('categoryList/clients/CategoryPageClient');
            $testReq = new CategoryPageClient();

            $testObj = new CategoryPageRequest();
            $testObj->setData(array("categoryId"=>$xdata["categoryId"] ));
            $xArray = $testReq->getDynamicLocationList($testObj);
            error_log("LDBX ".print_r(array_unique($xArray["countries"]),true));

            $random = rand()%count($xcountries);
            $xdata["countryId"] = $xcountries[$random];
            $requestURL->setData($xdata);
            error_log("LDBX ".print_r($requestURL,true));

            $finalUrl = $requestURL->getURL();
            error_log("LDBX ".$finalUrl);
        }
    }
    $email = $userarray['email'];
    $mobile = $userarray['mobile'];
    if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
        echo "Blank" ;
        exit;
    }
    if($email == '' || $displayname == '') {
        echo "Blank" ;
        exit;
    } else {
        $secCodeSessionVar = 'seccodehome';
        $secCode = $this->input->post('homesecurityCode');
        $mPageName = 'StudyAbroad';
        call_user_func(array($this, 'cookFor'. $mPageName), $userarray);
        if(verifyCaptcha($secCodeSessionVar,$secCode1)) {
            if(!isset($Validate[0]['userid'])){
                $sourcename = 'MARKETING_FORM';
                $userarray['sourcename'] = $sourcename;
                $userarray['quicksignupFlag'] = "marketingPage";
                $userarray['usergroup'] = 'marketingPage';
                if(!array_key_exists('password', $userarray) ) {
                    $password = 'shiksha@'. rand(1,1000000);
                    $mdpassword = sha256($password);
                    $userarray['textPassword'] =  $password;
                    $userarray['password'] = $mdpassword;
                }

                // Add new user
                $userarray['referer'] = $_SERVER['HTTP_REFERER'];
		$userarray['browser'] = $_SERVER['HTTP_USER_AGENT'];
                if($this->input->post("SA_resolution") != "")
                        $userarray["resolution"] = $this->input->post("SA_resolution");
                $addResult = $register_client->addUser(1,$userarray);
                if($addResult['status'] > 0) {
                    //Set the cookie when the user has registered
                    $Validate = $this->checkUserValidation();
                    if(!isset($Validate[0]['userid'])){
                        $value = $email.'|'.$mdpassword;
                        $this->cookie($value);
                    }

                    $this->load->library('user/UserLib');
                    $userLib = new UserLib;
                    $userLib->sendEmailsOnRegistration($addResult['status'], array(), $password);
                    $userLib->updateUserData($addResult['status']);
                    
                    //  put the user data in lead log tables
                    if($mPageName != "homePage") {
                        $this->load->library('MarketingClient');
                        $marketingClientObj = new MarketingClient();
                        $addUser = $marketingClientObj->registerUserForLead(1,$addResult['status'],$addReqInfo,$LeadInterest,'',$keyvalarray,'add');
                    }
                    //echo $finalUrl;
                $addResult['finalUrl'] = $finalUrl;
                $addResult['flagfirsttime'] = 'true';
                    echo json_encode($addResult);
                } else {
                    // If the user already exists (based on email/displayname)
                    if($addResult['status'] == -1) {
                        if($addResult['email'] == -1 && $addResult['displayname'] == -1)
                            echo 'both';
                        else {
                            if($addResult['email'] == -1)
                                echo 'email';
                            if($addResult['displayname'] == -1)
                                echo 'displayname';
                        }
                    } else {
                        echo 0;
                    }
                }
            } else {
                /* User array for updating tuser table */

                $addResult = $register_client->updateUser(12,$userarray,$Validate[0]['userid']);
                if($mPageName != "homePage") {
                    $addUser = $marketingClientObj->registerUserForLead(1,$Validate[0]['userid'],$addReqInfo,$LeadInterest,$userarray,$keyvalarray,'update');
                }
                $addResult['finalUrl'] = $finalUrl;
                $addResult['flagfirsttime'] = 'false';
                echo json_encode($addResult);
            }
        }
        else
            echo "code";
    }
}
         /**
	 * this method registers users of multiple marketing page
	 *
	 * @access	public
	 * @param 
	 * @return	void
	 */
        function MultipleMarketingPage($secCodeSessionVar='seccodehome') {
		error_log('LDB start');
		$this->init();
    
		$registerClient = new Register_client();
		/* useless var. at least in this case !!! */
		$appId = 1;
		/* build an array for form values  in formatted manner */
		$finalArray = array();
		/* get user current status */
		$Validate = $this->checkUserValidation();
		/* boolen flag */
		$update_flag = $this->input->post('flag_marketing_overlay');
		/* pref id */
		$flag_prefId = trim($this->input->post('flag_prefId'));
		/* flag that identify it_degree or it_courses */
		$mPageName = $this->input->post('mpagename');
		/* flag that tells page name */
		$marketingpagename = $this->input->post('marketingpagename');
        //echo $mPageName;
		/* if request come from overlay wala form */
		if ((!empty($update_flag))&& ($update_flag == 'true')) {
			error_log('  LDBSERVER :: LDB call second overlay');
			$finalArray['referer'] = $_SERVER['HTTP_REFERER'];
			$finalArray['browser'] = $_SERVER['HTTP_USER_AGENT'];
			// user age
			$user_age = $this->input->post('quickage');
			if (!empty($user_age)) {
				$finalArray['age'] = $user_age;
			}
			// Female,Male
			$user_gender = $this->input->post('quickgender');
			if (!empty($user_gender)) {
				$finalArray['gender'] = $user_gender;
			}
			// other details text area
			$otherdetails = $this->input->post('otherdetails');
			if (!empty($otherdetails) && (trim($otherdetails) != 'Specify any other detail about your course and institute preference.')) {
				$finalArray['userDetail'] = $otherdetails;
			}
			// desired specialization id
			$desired_specialization_id = $this->input->post('specializationId');
			if (is_array($desired_specialization_id))
			{
			  foreach ($desired_specialization_id as $value) {
				  $finalArray['specializationId'][] = $value;
			  }
			}
			
                        /*
			 * FIXED PR 56518
			$desired = $this->input->post('desired');
                        if ($this->checkDMBACourse($desired))
			{
			  $finalArray['desiredCourse']=24;
			  $finalArray['specializationId'][]=$desired;
			}
			*/
			
			if ($mPageName != 'graduate_course' ) {
				// 10th Details
				$xii_passing_year = $this->input->post('10_com_year_year');
				$xii_percentage = $this->input->post('10_ug_detials_courses_marks');
				// 10th Stream [science_arts,science_commerce,science_stream]
				$xii_stream = $this->input->post('science_stream');
				if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
					$finalArray['instituteId'][] = '';
					$finalArray['marksType'][] = 'percentage';
					$finalArray['level'][] = '12';
					$finalArray['city'][] ='';
					$finalArray['country'][] ='2';
					if (!empty($xii_stream)) {
						if ($xii_stream == 'science_arts') {
			    $finalArray['name'][] = 'arts';
						} else if ($xii_stream == 'science_commerce') {
			    $finalArray['name'][] = 'commerce';
						} else {
			    $finalArray['name'][] = 'science';
						}
					}else{
						$finalArray['name'][] = '';
					}
					if (!empty($xii_percentage)) {
						$finalArray['marks'][] = $xii_percentage;
					} else {
						$finalArray['marks'][] = '';
					}
					if(!empty($xii_passing_year)) {
		    $finalArray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
		    $finalArray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
					} else {
		    $finalArray['courseCompletionDate'][] = '';
		    $finalArray['ongoingCompletedFlag'][] = '';
					}
				}
			}
			/* Three checkboxes */
			$newsletteremail =  $this->input->post('newsletteremail');
			$finalArray['newsletteremail'] = (!empty($newsletteremail))?1:0;
			$viaemail =  $this->input->post('viaemail');
			$finalArray['viaemail'] = (!empty($viaemail))?1:0;
			$viamobile = $this->input->post('viamobile');
			$finalArray['viamobile'] = (!empty($viamobile))?1:0;

			/* pick password */
			$password = $this->input->post('quickpassword');
			if (!empty($password)) {
				$finalArray['textPassword'] = $password;
				$finalArray['password']  = md5($password);
			}
			if (!empty($flag_prefId)) {
				$finalArray['prefId'] = $flag_prefId;
			}
			/* already user is registered so pick userid */
			$userId = $Validate[0]['userid'];
			/* make life easier to call this API :) */
			error_log(print_r($finalArray,true)." LDBSERVER :: LDB update user api called");
			$registerClient->updateUser($appId,$finalArray,$userId);
			if (!empty($password)) {
				$emailArr = explode("|",$Validate[0]['cookiestr']);
				$emailArr[1] = $finalArray['password'];
				$value = implode('|',$emailArr);
				$this->cookie($value);
			}
			echo "update";
			// endif overlay form update
		} else {
			error_log(' LDBSERVER :: LDB call Insert start');
            $finalArray['desiredCourse'] = $this->input->post('desiredCourse');
            if ($this->checkDMBACourse($finalArray['desiredCourse']))
	    {
	      $finalArray['desiredCourse']=24;
              $finalArray['specializationId']=$this->input->post('desiredCourse');
            }

            /* Code added for testprep marketing pages where we asked 12th detail */
	    if ($mPageName != 'graduate_course' ) {
	      // 10th Details
	      $xii_passing_year = $this->input->post('10_com_year_year');
	      $xii_percentage = $this->input->post('10_ug_detials_courses_marks');
	      // 10th Stream [science_arts,science_commerce,science_stream]
	      $xii_stream = $this->input->post('science_stream');
	      if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
		$finalArray['instituteId'][] = '';
		$finalArray['marksType'][] = 'percentage';
		$finalArray['level'][] = '12';
		$finalArray['city'][] ='';
		$finalArray['country'][] ='2';
		if (!empty($xii_stream)) {
		  if ($xii_stream == 'science_arts') {
		    $finalArray['name'][] = 'arts';
	      } else if ($xii_stream == 'science_commerce') {
		    $finalArray['name'][] = 'commerce';
	      } else {
		$finalArray['name'][] = 'science';
	      }
	    }else{
	      $finalArray['name'][] = '';
	    }
	    if (!empty($xii_percentage)) {
		    $finalArray['marks'][] = $xii_percentage;
	    } else {
	      $finalArray['marks'][] = '';
	    }
	    if(!empty($xii_passing_year)) {
		    $finalArray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
		    $finalArray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
	    } else {
	      $finalArray['courseCompletionDate'][] = '';
	      $finalArray['ongoingCompletedFlag'][] = '';
	    }
		}
	}
	/* Code added for testprep marketing pages where we asked 12th detail */
            $finalArray['newsletteremail'] = 1;
			// other details text area
			$otherdetails = $this->input->post('otherdetails');
			if (!empty($otherdetails) && (trim($otherdetails) != 'Specify any other detail about your course and institute preference.')) {
				$finalArray['userDetail'] = $otherdetails;
			}
			if ($mPageName == 'it_degree' ) {
			 /* CAT MAT like  EXAM Details */
            $flag_cat_mat_exm_marks = $this->input->post('ExamsTaken');
            if(!empty($flag_cat_mat_exm_marks)) {
            foreach($flag_cat_mat_exm_marks as $exam_value) {
                $exm_marks = $this->input->post($exam_value .'_exm_marks');
                if((!empty($exam_value)) && ($exam_value != 'Percentile') && ($exam_value != 'Score') && (($exam_value == 'cat' ||$exam_value == 'mat' ||$exam_value == 'cmat'))) {
                    $finalArray['name'][] = strtoupper($exam_value);
                    $finalArray['marks'][] = $exm_marks;
                    $finalArray['instituteId'][] = '';
                    $finalArray['marksType'][] = $exam_value == 'cmat' ? NULL : 'percentile';
                    $finalArray['level'][] = 'Competitive exam';
                    $finalArray['courseCompletionDate'][] = '';
                    $finalArray['ongoingCompletedFlag'][] ='';
                    $finalArray['city'][] ='';
                    $finalArray['country'][] ='';
                    $finalArray['educationStatus'][] ='live';
                }
            }
            }
		 /*Degree Preference*/
            $degree_preference  = $this->input->post('degree_preference');
            if (is_array($degree_preference)) {
                foreach ($degree_preference as $value) {
                    switch($value) {
                        case 'aicte_approved':
                        $finalArray['degreePrefAICTE'] = 'yes';
                        break;
                        case 'ugc_approved':
                        $finalArray['degreePrefUGC'] = 'yes';
                        break;
                        case 'international':
                        $finalArray['degreePrefInternational']= 'yes';
                        break;
                        case 'any':
                        $finalArray['degreePrefAny']= 'yes';
                        break;
                    }
                }
            }
		}
			/* Work Experience */
			$finalArray['experience'] = $this->input->post('ExperienceCombo');
			if ($mPageName != 'graduate_course' ) {
				/* UG DETAILS */
				$ug_detials_courses =$this->input->post('ug_detials_courses');
				if (!empty($ug_detials_courses)) {
					$finalArray['name'][] = $ug_detials_courses;
					global $ug_course_mapping_array;
					foreach($ug_course_mapping_array as $key => $value)
					{
						if($value == $ug_detials_courses)
						{
							$educationLevel = $key;
							error_log($educationLevel.'EDUCATIONLEVEL');
							break;
						}
					}
					if ($this->input->post('Completed') == 'completed') {
						$marks = $this->input->post('ug_detials_courses_marks');
					} else {
						$marks = '';
					}

					$schoolCombo = '';
					$citiesofeducation = '';

					$finalArray['marks'][] = $marks ;
					$finalArray['instituteId'][] = $schoolCombo;
					$finalArray['marksType'][] = 'percentage';
					$finalArray['level'][] = 'UG';
					$com_year_year = $this->input->post('com_year_year');
					$com_year_month = $this->input->post('com_year_month');
					if (!empty($com_year_year) && !empty($com_year_month)) {
						$finalArray['courseCompletionDate'][] = $this->input->post('com_year_year') . '-'.$this->input->post('com_year_month') .'-'. '1';
					} else {
						$finalArray['courseCompletionDate'][] = '';
					}
					$finalArray['ongoingCompletedFlag'][] = $this->input->post('Completed') == 'completed'?"0":"1";
					$finalArray['city'][] = $citiesofeducation;
					$finalArray['country'][] ='2';
					$finalArray['educationStatus'][] ='live';

				}
					/* added zone feature Start*/
					if ($mPageName == 'it_courses') {
						$perferencelocality = $this->input->post('perferencelocality', true);
						$perferencecity = $this->input->post('perferencecity', true);
						if ((count($perferencelocality) > 0) && (count($perferencecity) > 0)) {
						$i = 0;
						foreach($perferencelocality as $locality_city_name) {
							if ($perferencelocality[$i] != "") {
				    if ($perferencelocality[$i] == '-1') {
				    	$newlist = explode(':',$perferencecity[$i]);
				    	if (in_array($newlist[2],$finalArray['cityId']) == false) {
				    		$finalArray['countryId'][] = $newlist[0];
				    		$finalArray['stateId'][] = $newlist[1];
				    		$finalArray['cityId'][] = $newlist[2];
				    		$finalArray['localityId'][] = 0;
				    	}
				    } else {
				    	$newlist = explode(':',$perferencelocality[$i]);
				    	if (in_array($newlist[3],$finalArray['localityId']) == false) {
				    		$finalArray['countryId'][] = $newlist[0];
				    		$finalArray['stateId'][] = $newlist[1];
				    		$finalArray['cityId'][] = $newlist[2];
				    		$finalArray['localityId'][] = $newlist[3];
				    	}
				    }
							}
							$i++;
						}
					}
					}
					/* added zone feature End */
			}
			if ($mPageName == 'graduate_course' ) {
				// 10th Details
				$xii_passing_year = $this->input->post('10_com_year_year');
				$xii_percentage = $this->input->post('10_ug_detials_courses_marks');
				// 10th Stream [science_arts,science_commerce,science_stream]
				$xii_stream = $this->input->post('science_stream');
				if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
					$finalArray['instituteId'][] = '';
					$finalArray['marksType'][] = 'percentage';
					$finalArray['level'][] = '12';
					$finalArray['city'][] ='';
					$finalArray['country'][] ='2';
					if (!empty($xii_stream)) {
						if ($xii_stream == 'science_arts') {
			    $finalArray['name'][] = 'arts';
						} else if ($xii_stream == 'science_commerce') {
			    $finalArray['name'][] = 'commerce';
						} else {
			    $finalArray['name'][] = 'science';
						}
					}else{
						$finalArray['name'][] = '';
					}
					if (!empty($xii_percentage)) {
						$finalArray['marks'][] = $xii_percentage;
					} else {
						$finalArray['marks'][] = '';
					}
					if(!empty($xii_passing_year)) {
		    $finalArray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
		    $finalArray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
					} else {
		    $finalArray['courseCompletionDate'][] = '';
		    $finalArray['ongoingCompletedFlag'][] = '';
					}
				}
			}
			/*modeOfEducation*/
			if ($mPageName != 'it_courses') {
				$mode_preference  = $this->input->post('mode');
				if (is_array($mode_preference)) {
					foreach ($mode_preference as $value) {
						switch($value) {
			    case 'full_time':
			    	$finalArray['modeOfEducationFullTime'] = 'yes';
			    	break;
			    case 'part_time':
			    	$finalArray['modeOfEducationPartTime'] = 'yes';
			    	break;
			    case 'distance':
			    	$finalArray['modeOfEducationDistance']= 'yes';
			    	break;
						}
					}
				}
			}
			//$finalArray['desiredCourse'] = $this->input->post('homesubCategories');
			/* when you plan to start study */
			$finalArray['timeOfStart'] = $this->input->post('plan');
			/*
			 Residence Location added zone feature
			 we need to check if residence city is not present then pass first location
			 of preference for redirection url
			 */
			$residenceLocation  = $this->input->post('citiesofquickreg');
			if (!empty($residenceLocation)) {
				$finalArray['residenceCity']=$this->input->post('citiesofquickreg');
			}

			/* Mobile */
			$finalArray['mobile']= $this->input->post('mobile');

			/* Email id */
			$finalArray['email']= $this->input->post('email');

			/* First Name */
			$finalArray['firstName']= $this->input->post('firstname');

			/* Last Name */
			$finalArray['lastName']= $this->input->post('lastname');

			##############################################
			/* Loop to check unique displayname START */
			$responseCheckAvail = 1;
			error_log('  LDBSERVER ::  LDB WHILE START'.microtime(true));
			while($responseCheckAvail == 1){
				$displayname = $finalArray['firstName'] . rand(1,100000);
				$responseCheckAvail = $registerClient->checkAvailability($appId,$displayname,'displayname');
			}
			error_log('  LDBSERVER ::  LDB WHILE END'.microtime(true));
			/* Loop to check unique displayname END */
			$finalArray['displayname']= $displayname;
			/* Text password */
			$finalArray['textPassword'] = 'shiksha@'. rand(1,1000000);
			/* MD5 password */
			$finalArray['password']  = md5($finalArray['textPassword']);
			##############################################
			/* Preferred Study Location(s) START */
			$new_CityList = "";
			$new_StateList = "";
			if ($mPageName != 'it_courses') {
				$mCityList = $this->input->post('mCityList');
				$mCityList = explode(',',$mCityList);
				foreach($mCityList as $value) {
					if (!empty($value)) {
						$newlist = explode(':',$value);
						if (count($newlist) < 3 ) {
			    continue;
						}
						$finalArray['countryId'][] = $newlist[0];
						$finalArray['stateId'][] = $newlist[1];
						$finalArray['cityId'][] = $newlist[2];
						$finalArray['localityId'][] = 0;
						$new_CityList .= $newlist[2] . ",";
						$new_StateList .= $newlist[1] . ",";
					}
				}
			}
			/* :( HACK to trim last , from city list */
			$new_CityList = substr($new_CityList, 0, -1);
			$new_StateList = substr($new_StateList, 0, -1);
			/* Preferred Study Location(s) END */
			/* Get all lead realted data */
			$finalArray['sourcename'] = 'MARKETING_FORM';
			$finalArray['referer'] = $this->input->post('refererreg');
			$finalArray['resolution']= $this->input->post('resolutionreg');
			$subCategory = $this->input->post('subCategoryId');
			$finalArray['category'] = $this->input->post('categoryId');
			/* T USER SOURCE INFO */
			$finalArray['referer'] = $_SERVER['HTTP_REFERER'];
			$finalArray['landingpage'] = $this->input->post('loginactionreg');
			$finalArray['browser'] = $_SERVER['HTTP_USER_AGENT'];
			/* T USER SOURCE INFO */

			$city = $finalArray['residenceCity'];
			$preferredCity = $new_CityList;
			$mcourse = '';
			$city = $finalArray['residenceCity'];
			$firstName = $finalArray['firstName'];
			$mobile = $finalArray['mobile'];
			$age = '';
			$gender  = '';
			$email = $finalArray['email'];
			$displayname = $finalArray['displayname'];
			$mPageName = $this->input->post('mpagename');
			$mdpassword = $finalArray['password'];
			$password = $finalArray['textPassword'] ;

			if ($mPageName == 'it_courses')
			{
                                /* Work Experience */
			        $finalArray['experience'] = $this->input->post('ExperienceCombo');
				$perferencecity = array_unique($this->input->post('perferencecity', true));
				foreach($perferencecity as $locality_city_name) {
					if ($locality_city_name != "") {
						$newlist = explode(':',$locality_city_name);
						$new_CityList .= $newlist[2] . ",";
						$new_StateList .= $newlist[1] . ",";
					}
				}
				$new_CityList = substr($new_CityList, 0, -1);
				$new_StateList = substr($new_StateList, 0, -1);
			}
			$finalUrl = base64_decode($this->input->post('redirectionURL'));
			if(empty($finalUrl) && $marketingpagename!='testpreppage'){
			//$finalUrl =  $this->codeforlandingpage($new_CityList,$finalArray['category'],$subCategory,$city);
                            $finalUrl =  $this->getRedirectedListingPage($city,$new_CityList,$new_StateList,$finalArray['desiredCourse']);
			}
			$finalArray['landingpage'] = $finalUrl;
			if ($marketingpagename == 'testpreppage')
			{
				$blogId = $this->input->post('testPrep_blogid');
				$finalArray['desiredCourse'] = 0;
				$this->load->library('category_list_client');
				$blog_acronym = $this->category_list_client->getBlogAcronym($blogId);
				$this->load->library('url_manager');
				if (isset($finalArray['cityId'][0])) {
					$cityname = $this->category_list_client->getCityName($finalArray['cityId'][0]);
				} else {
					$cityname = '';
				}

				if(empty($finalUrl)){
				$finalUrl = $this->url_manager->get_testprep_url('', $blog_acronym,$cityname,'', '');
				}
				$finalArray['landingpage'] = $finalUrl;
				$finalArray['testPrep_blogid'] = $blogId;
				$finalArray['testPrep_status'] = 'live';
				$finalArray['testPrep_status'] = 'live';
				$finalArray['testPrep_updateTime'] = date("Y-m-d H:i:s");
				$finalArray['extraFlag'] = 'testprep';
			}
			error_log('LDBSERVER :: LDB process complete');
            $email = $finalArray['email'];
            $mobile = $finalArray['mobile'];
            if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
                echo "Blank" ;
                exit;
            }
			if($finalArray['email'] == '')
			{
				echo "Blank" ;
				exit;
			}
			else
			{
				if(!isset($Validate[0]['userid'])) {
					error_log(' LDBSERVER :: LDB call user is not sign in and insertion start.');
					/* captcha will validate if user is not logged-in */
					$secCode = $this->input->post('homesecurityCode');
                    $pageReferer = $this->input->post('pageReferer');
                    error_log("MMPREFERER: ".$pageReferer);
					if(isCaptchaFreeReferer($pageReferer) || verifyCaptcha($secCodeSessionVar,$secCode,1))
					{
						// save data here
						$response = $registerClient->addUser($appId,$finalArray);
						error_log(print_r($finalArray,true).' LDBSERVER :: FINAL ARRAY TO ADD USER');
						if($response['status'] > 0)
						{
							//Set the cookie when the user has registered
							$Validate = $this->checkUserValidation();
							if(!isset($Validate[0]['userid'])){
								$value = $email.'|'.$finalArray['password'];
								$this->cookie($value);
							}
                            
                            $this->load->library('user/UserLib');
                            $userLib = new UserLib;
                            $userLib->sendEmailsOnRegistration($response['status'],array('mmpFormId' => $this->input->post('mmpPageId')), $password);
                            $userLib->updateUserData($response['status']);
                            $finalUrl = $this->addSearchTrackParmToUrl($finalUrl);            
                            $response['finalUrl'] = $finalUrl;
                            $response['flagfirsttime'] = 'true';
                            echo json_encode($response);
						}
						else
						{
							// If the user already exists (based on email/displayname)
							if($response['status'] == -1)
							{
								if($response['email'] == -1 && $response['displayname'] == -1)
								echo 'both';
								else
								{
									if($response['email'] == -1)
									echo 'email';
									if($response['displayname'] == -1)
									echo 'displayname';
								}

							}
							else
							{
								echo 0;
							}
						}
					}
					else
					{
						echo "code";
					}
				} // end if user logged-in check
				else
				{
					error_log(' LDBSERVER :: LDB call user is logged-in and update start.');
					if($this->input->post('mupdateflag') == "update")
					{
						/*
						 Remove few items from array as logged-in user submit data so we do'nt need to reset password and cookie
						 */
						unset($finalArray['displayname']);
						/* Text password */
						unset($finalArray['textPassword']);
						/* MD5 password */
						unset($finalArray['password']);

						$userId = $Validate[0]['userid'];
						$response = $registerClient->updateUser($appId,$finalArray,$userId);
					}
                    $finalUrl = $this->addSearchTrackParmToUrl($finalUrl);
					$response['finalUrl'] = $finalUrl;
					$response['flagfirsttime'] = 'false';
					echo json_encode($response);
				}
			}
		} // end if of main form CRUD
	}

    /**
     * Function to check desired MBA course
     *
     * @param integer $did desired course id
     */
    function checkDMBACourse($did){
	$os = array(25,26,27,28,29,30,31,32,33,34,727,728,729,730,731,732,733,734);
	if (in_array($did, $os))
	{
	  error_log(" LDB check desired course TRUE :::" . $did);
	  return true;
	}
	else
	{
	  error_log(" LDB check desired course FALSE :::" . $did);
	  return false;
	}
}

/**
 * Function for MMP user operation
 */
function MultipleMarketingPageUserOperation() {
    $this->init();
    $appId = 1;

    // Check for the availability of the display name if not available generate it using ranndom key else use the name as display name
    $register_client = new Register_client();
    $firstname = $this->input->post('firstname', true);
    $homename = $this->input->post('homename', true);
    if(empty($firstname) && !empty($homename)) {
        $_POST['firstname'] = $this->input->post('homename', true);
    }
    $displayname = $this->input->post('firstname', true);
    $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
    while($responseCheckAvail == 1){
        $displayname = $this->input->post('firstname') . rand(1,100000);
        $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
    }
    $userarray = array();
    $this->cookForRegistration($userarray);
    $userarray['displayname'] = $displayname;

    $preferredCity = $this->input->post('mCityList');
    $preferredCity =  trim($preferredCity,',');
    $preferredCityName = $this->input->post('mCityListName');
    $mPageName = $this->input->post('mPageName');
    $mcourse = $this->input->post('mcourse');
    $countries = trim($this->input->post('mCountryList'), ',');
    $modeoffinance = $this->input->post('sourceFunding');
    $nearestMetropolitanCity = $this->input->post('mCity');


    // Check availability code ends

    // By default viamobile,viamail,newslettermail to set as 1
    $viamobile = 1;
    $viamail = 1;
    $newsletteremail =1;
    /* User array for marketing table */
    $addReqInfo = array();
    $Validate = $this->checkUserValidation();
    //error_log(print_r($Validate,true));
    if(isset($Validate[0]['userid'])) {
        $exploded = explode('|',$Validate[0]['cookiestr']);
        $email = $exploded[0];
        $password = $exploded[1];
    }
    $flagvalue = 'false';

    $addReqInfo['email'] = $userarray['email'];
    $addReqInfo['displayName'] = $userarray['displayname'];
    $addReqInfo['residenceLoc'] = $userarray['residenceCity'];
    $addReqInfo['age'] = $userarray['age'];
    $addReqInfo['gender'] = $userarray['gender'];
    $addReqInfo['highestQualification'] = $userarray['educationlevel'];
    $addReqInfo['flagRegistered'] = "true";
    $addReqInfo['mobile'] = $userarray['mobile'];
    $addReqInfo['mPageName'] = $mPageName;
    $addReqInfo['marketingFlag'] = $flagvalue;
    $addReqInfo['category'] = $userarray['categories'];
    $addReqInfo['subCategory'] = $userarray['subCategories'];
    $addReqInfo['preferredcity'] = $preferredCity;


    // Make array for LeadKeyValue pair
    $LeadInterest = array('category' => $categories,'subCategory'=>$subCategory,'city'=>$preferredCity,'countries'=> $countries);

    //Make array for keyvalue pair
    if($mPageName == "studyAbroad") {
        $nearestMetropolitanCity = $userarray['residenceCity'];
        $courseStartTime = $userarray['plan'];
        $modeoffinance = '';
        if(!empty($userarray['userFundsNone'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'None';
        }
        if(!empty($userarray['userFundsBank'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'Bank Funds';
        }
        if(!empty($userarray['userFundsOwn'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'Own Funds';
        }
        $keyvalarray = $this->makeLeadKeyValueForStudyAbroad($categories,$countries,$nearestMetropolitanCity,$modeoffinance,$courseStartTime);
    } else {
        $keyvalarray = $this->makeLeadKeyValue($categories,$subCategory,$preferredCity,$mcourse);
    }
    //error_log(print_r($keyvalarray,true));
    $destination_url = $this->input->post('destination_url');
    if(!empty($destination_url))
    $finalUrl = $destination_url;
    else
    $finalUrl =  $this->codeforlandingpage($preferredCity,$categories,$subCategory,$city);
    if($mPageName == "studyAbroad") {
        $countriesSent = explode(",",$this->input->post('mCountryListName'));
        error_log("ShirishSTA 12121".print_r($countriesSent,true));
        $index = rand(0,count($countriesSent)-1);
        $countrySelected = $countriesSent[$index];
        if($countrySelected == "") {
            $countrySelected = $countriesSent[$index-1];
        }
        $countrySelected = str_replace(' ','',$countrySelected);
        global $countries;
        foreach($countries as $key=>$value) {
            if($value['name'] == $countrySelected) {
                $countrySelected = $key;
                break;
            }
        }

        //$countrySelected = "usa";
        $pageName = strtoupper('SHIKSHA_'. $countrySelected.'_HOME');
        $countryUrl = constant($pageName);
        if(strstr($countryUrl,"getCategoryPage") > -1) {
            $countryUrl= $countryUrl;
        } else {
            $countryUrl= $countryUrl.'/getCategoryPage/colleges/studyabroad/'.$countrySelected;
        }
        global $categoryMap;
        foreach($categoryMap as $key=>$value) {
            if($value['id'] == $categories) {
                $categoryNameInUrl = $key;
                break;
            }
        }
         if(!empty($destination_url))
         $finalUrl = $destination_url;
         else
         $finalUrl = $countryUrl."/All/".$categoryNameInUrl."/All";
         
         
        $this->load->library('categoryList/categoryPageRequest');
	$requestURL = new CategoryPageRequest();

        error_log("LDBX ".print_r($_POST,true));
        $xdata = array();
        $xdata["categoryId"] = $this->input->post("board_id");
        
        $regions = trim($this->input->post('mRegions'), '#');
        if(!empty($regions)){
            $regionsArr = explode("#",$regions);
            $choice = rand()%count($regionsArr);
            $region = $regionsArr[$choice];
            $xdata["regionId"] = $region;
            $xdata["countryId"] = 1;
            error_log("LDBX ".print_r($xdata,true));
            $requestURL->setData($xdata);
            $finalUrl = $requestURL->getURL();
        }
        else{
            $xcountries = explode(",",trim($this->input->post('mCountryList'), ','));
            $this->load->library('categoryList/clients/CategoryPageClient');
            $testReq = new CategoryPageClient();

            $testObj = new CategoryPageRequest();
            $testObj->setData(array("categoryId"=>$xdata["categoryId"] ));
            $xArray = $testReq->getDynamicLocationList($testObj);
            error_log("LDBX ".print_r(array_unique($xArray["countries"]),true));

            $random = rand()%count($xcountries);
            $xdata["countryId"] = $xcountries[$random];
            $requestURL->setData($xdata);
            error_log("LDBX ".print_r($requestURL,true));

            $finalUrl = $requestURL->getURL();
            error_log("LDBX ".$finalUrl);
        }
    }
    $email = $userarray['email'];
    $mobile = $userarray['mobile'];
    if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
        echo "Blank" ;
        exit;
    }
    if($email == '' || $displayname == '') {
        echo "Blank" ;
        exit;
    } else {
        $secCodeSessionVar = 'seccodehome';
        $secCode = $this->input->post('homesecurityCode');
        $pageReferer = $this->input->post('pageReferer');
        $mPageName = 'StudyAbroad';
        call_user_func(array($this, 'cookFor'. $mPageName), $userarray);
        if(isCaptchaFreeReferer($pageReferer) || verifyCaptcha($secCodeSessionVar,$secCode,1)) {
            if(!isset($Validate[0]['userid'])){
                $sourcename = 'MARKETING_FORM';
                $userarray['sourcename'] = $sourcename;
                $userarray['quicksignupFlag'] = "marketingPage";
                $userarray['usergroup'] = 'marketingPage';
                if(!array_key_exists('password', $userarray) ) {
                    $password = 'shiksha@'. rand(1,1000000);
                    $mdpassword = md5($password);
                    $userarray['textPassword'] =  $password;
                    $userarray['password'] = $mdpassword;
                }

                // Add new user
                $addResult = $register_client->addUser(1,$userarray);
                if($addResult['status'] > 0) {
                    //Set the cookie when the user has registered
                    $Validate = $this->checkUserValidation();
                    if(!isset($Validate[0]['userid'])){
                        $value = $email.'|'.$mdpassword;
                        $this->cookie($value);
                    }

                    $this->load->library('user/UserLib');
                    $userLib = new UserLib;
                    $userLib->sendEmailsOnRegistration($addResult['status'], array(), $password);
                    $userLib->updateUserData($addResult['status']);
                    
                    //  put the user data in lead log tables
                    if($mPageName != "homePage") {
                        $this->load->library('MarketingClient');
                        $marketingClientObj = new MarketingClient();
                        $addUser = $marketingClientObj->registerUserForLead(1,$addResult['status'],$addReqInfo,$LeadInterest,'',$keyvalarray,'add');
                    }
                    //echo $finalUrl;
                $addResult['finalUrl'] = $finalUrl;
                $addResult['flagfirsttime'] = 'true';
                    echo json_encode($addResult);
                } else {
                    // If the user already exists (based on email/displayname)
                    if($addResult['status'] == -1) {
                        if($addResult['email'] == -1 && $addResult['displayname'] == -1)
                            echo 'both';
                        else {
                            if($addResult['email'] == -1)
                                echo 'email';
                            if($addResult['displayname'] == -1)
                                echo 'displayname';
                        }
                    } else {
                        echo 0;
                    }
                }
            } else {
                /* User array for updating tuser table */

                $addResult = $register_client->updateUser(12,$userarray,$Validate[0]['userid']);
                if($mPageName != "homePage") {
                    $addUser = $marketingClientObj->registerUserForLead(1,$Validate[0]['userid'],$addReqInfo,$LeadInterest,$userarray,$keyvalarray,'update');
                }
                $addResult['finalUrl'] = $finalUrl;
                $addResult['flagfirsttime'] = 'false';
                echo json_encode($addResult);
            }
        }
        else
            echo "code";
    }
}
        /**
	 * this method registers users of multiple marketing page
	 *
	 * @param string $secCodeSessionVar
	 * @access	public
	 * @return	void
	 */
        function homepageUserRegistration($secCodeSessionVar='seccodehome') {
		$this->init();
		$registerClient = new Register_client();
		/* useless var. at least in this case !!! */
		$appId = 1;
		/* build an array for form values  in formatted manner */
		$finalArray = array();
		/* get user current status */
		$Validate = $this->checkUserValidation();
		/* boolen flag */
		$update_flag = $this->input->post('flag_marketing_overlay');
		/* pref id */
		$flag_prefId = trim($this->input->post('flag_prefId'));
		/* flag that identify it_degree or it_courses */
		$mPageName = $this->input->post('mpagename');
		//echo "$mPageName".$mPageName;
		/* flag that tells page name */
		$marketingpagename = $this->input->post('marketingpagename');

			error_log(' LDBSERVER :: LDB call Insert start');
            $finalArray['desiredCourse'] = $this->input->post('desiredCourse');
            //echo "desired course".$finalArray['desiredCourse'];
            if($finalArray['desiredCourse']>=25 && $finalArray['desiredCourse']<=34){
                  $finalArray['desiredCourse']=24;
                  $finalArray['specializationId']=$this->input->post('desiredCourse');
            }
			//echo "aasaa".$finalArray['desiredCourse'];
            $finalArray['newsletteremail'] = 1;
			// other details text area
			$otherdetails = $this->input->post('otherdetails');
			if (!empty($otherdetails) && (trim($otherdetails) != 'Specify any other detail about your course and institute preference.')) {
				$finalArray['userDetail'] = $otherdetails;
			}
			if ($mPageName == 'it_degree' ) {
			 /* CAT MAT like  EXAM Details */
            $flag_cat_mat_exm_marks = $this->input->post('ExamsTaken');
            if(!empty($flag_cat_mat_exm_marks)) {
            foreach($flag_cat_mat_exm_marks as $exam_value) {
                $exm_marks = $this->input->post($exam_value .'_exm_marks');
                if((!empty($exam_value)) && ($exam_value != 'Percentile') && (($exam_value == 'cat' ||$exam_value == 'mat'))) {
                    $finalArray['name'][] = strtoupper($exam_value);
                    $finalArray['marks'][] = $exm_marks;
                    $finalArray['instituteId'][] = '';
                    $finalArray['marksType'][] = 'percentile';
                    $finalArray['level'][] = 'Competitive exam';
                    $finalArray['courseCompletionDate'][] = '';
                    $finalArray['ongoingCompletedFlag'][] ='';
                    $finalArray['city'][] ='';
                    $finalArray['country'][] ='';
                    $finalArray['educationStatus'][] ='live';
                }
            }
            }
		 /*Degree Preference*/
            $degree_preference  = $this->input->post('degree_preference');
            if (is_array($degree_preference)) {
                foreach ($degree_preference as $value) {
                    switch($value) {
                        case 'aicte_approved':
                        $finalArray['degreePrefAICTE'] = 'yes';
                        break;
                        case 'ugc_approved':
                        $finalArray['degreePrefUGC'] = 'yes';
                        break;
                        case 'international':
                        $finalArray['degreePrefInternational']= 'yes';
                        break;
                        case 'any':
                        $finalArray['degreePrefAny']= 'yes';
                        break;
                    }
                }
            }
		}
			/* Work Experience */
			$finalArray['experience'] = $this->input->post('ExperienceCombo');
			if ($mPageName != 'graduate_course' ) {
				/* UG DETAILS */
				$ug_detials_courses =$this->input->post('ug_detials_courses');
				if (!empty($ug_detials_courses)) {
					$finalArray['name'][] = $ug_detials_courses;
					global $ug_course_mapping_array;
					foreach($ug_course_mapping_array as $key => $value)
					{
						if($value == $ug_detials_courses)
						{
							$educationLevel = $key;
							error_log($educationLevel.'EDUCATIONLEVEL');
							break;
						}
					}
					if ($this->input->post('Completed') == 'completed') {
						$marks = $this->input->post('ug_detials_courses_marks');
					} else {
						$marks = '';
					}

					$schoolCombo = '';
					$citiesofeducation = '';

					$finalArray['marks'][] = $marks ;
					$finalArray['instituteId'][] = $schoolCombo;
					$finalArray['marksType'][] = 'percentage';
					$finalArray['level'][] = 'UG';
					$com_year_year = $this->input->post('com_year_year');
					$com_year_month = $this->input->post('com_year_month');
					if (!empty($com_year_year) && !empty($com_year_month)) {
						$finalArray['courseCompletionDate'][] = $this->input->post('com_year_year') . '-'.$this->input->post('com_year_month') .'-'. '1';
					} else {
						$finalArray['courseCompletionDate'][] = '';
					}
					$finalArray['ongoingCompletedFlag'][] = $this->input->post('Completed') == 'completed'?"0":"1";
					$finalArray['city'][] = $citiesofeducation;
					$finalArray['country'][] ='2';
					$finalArray['educationStatus'][] ='live';
					/* added zone feature Start*/
					if ($mPageName == 'it_courses') {
						$perferencelocality = $this->input->post('perferencelocality', true);
						$perferencecity = $this->input->post('perferencecity', true);
						$i = 0;
						foreach($perferencelocality as $locality_city_name) {
							if ($perferencelocality[$i] != "") {
				    if ($perferencelocality[$i] == '-1') {
				    	$newlist = explode(':',$perferencecity[$i]);
				    	if (in_array($newlist[2],$finalArray['cityId']) == false) {
				    		$finalArray['countryId'][] = $newlist[0];
				    		$finalArray['stateId'][] = $newlist[1];
				    		$finalArray['cityId'][] = $newlist[2];
				    		$finalArray['localityId'][] = 0;
				    	}
				    } else {
				    	$newlist = explode(':',$perferencelocality[$i]);
				    	if (in_array($newlist[3],$finalArray['localityId']) == false) {
				    		$finalArray['countryId'][] = $newlist[0];
				    		$finalArray['stateId'][] = $newlist[1];
				    		$finalArray['cityId'][] = $newlist[2];
				    		$finalArray['localityId'][] = $newlist[3];
				    	}
				    }
							}
							$i++;
						}
					}
					/* added zone feature End */
				}
			}
			if ($mPageName == 'graduate_course' ) {
				// 10th Details
				$xii_passing_year = $this->input->post('10_com_year_year');
				$xii_percentage = $this->input->post('10_ug_detials_courses_marks');
				// 10th Stream [science_arts,science_commerce,science_stream]
				$xii_stream = $this->input->post('science_stream');
				if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
					$finalArray['instituteId'][] = '';
					$finalArray['marksType'][] = 'percentage';
					$finalArray['level'][] = '12';
					$finalArray['city'][] ='';
					$finalArray['country'][] ='2';
					if (!empty($xii_stream)) {
						if ($xii_stream == 'science_arts') {
			    $finalArray['name'][] = 'arts';
						} else if ($xii_stream == 'science_commerce') {
			    $finalArray['name'][] = 'commerce';
						} else {
			    $finalArray['name'][] = 'science';
						}
					}else{
						$finalArray['name'][] = '';
					}
					if (!empty($xii_percentage)) {
						$finalArray['marks'][] = $xii_percentage;
					} else {
						$finalArray['marks'][] = '';
					}
					if(!empty($xii_passing_year)) {
		    $finalArray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
		    $finalArray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
					} else {
		    $finalArray['courseCompletionDate'][] = '';
		    $finalArray['ongoingCompletedFlag'][] = '';
					}
				}
			}
			/*modeOfEducation*/
			if ($mPageName != 'it_courses') {
				$mode_preference  = $this->input->post('mode');
				if (is_array($mode_preference)) {
					foreach ($mode_preference as $value) {
						switch($value) {
			    case 'full_time':
			    	$finalArray['modeOfEducationFullTime'] = 'yes';
			    	break;
			    case 'part_time':
			    	$finalArray['modeOfEducationPartTime'] = 'yes';
			    	break;
			    case 'distance':
			    	$finalArray['modeOfEducationDistance']= 'yes';
			    	break;
						}
					}
				}
			}
			//$finalArray['desiredCourse'] = $this->input->post('homesubCategories');
			/* when you plan to start study */
			$finalArray['timeOfStart'] = $this->input->post('plan');
			/*
			 Residence Location added zone feature
			 we need to check if residence city is not present then pass first location
			 of preference for redirection url
			 */
			$residenceLocation  = $this->input->post('citiesofquickreg');
			if (!empty($residenceLocation)) {
				$finalArray['residenceCity']=$this->input->post('citiesofquickreg');
			}

			/* Mobile */
			$finalArray['mobile']= $this->input->post('mobile');

			/* Email id */
			$finalArray['email']= $this->input->post('email');

			/* First Name */
			$finalArray['firstName']= $this->input->post('firstname');

			##############################################
			/* Loop to check unique displayname START */
			$responseCheckAvail = 1;
			error_log('  LDBSERVER ::  LDB WHILE asd START'.microtime(true));
			while($responseCheckAvail == 1){
				$displayname = $finalArray['firstName'] . rand(1,100000);
				$responseCheckAvail = $registerClient->checkAvailability($appId,$displayname,'displayname');
			}
			error_log('  LDBSERVER ::  LDB WHILE END'.microtime(true));
			/* Loop to check unique displayname END */
			$finalArray['displayname']= $displayname;
			/* Text password */
			$finalArray['textPassword'] = 'shiksha@'. rand(1,1000000);
			/* MD5 password */
			$finalArray['password']  = md5($finalArray['textPassword']);
			##############################################
			/* Preferred Study Location(s) START */
			$new_CityList = "";
			$new_StateList = "";
			if ($mPageName != 'it_courses') {
				$mCityList = $this->input->post('mCityList');
				$mCityList = explode(',',$mCityList);
				foreach($mCityList as $value) {
					if (!empty($value)) {
						$newlist = explode(':',$value);
						if (count($newlist) < 3 ) {
			    continue;
						}
						$finalArray['countryId'][] = $newlist[0];
						$finalArray['stateId'][] = $newlist[1];
						$finalArray['cityId'][] = $newlist[2];
						$finalArray['localityId'][] = 0;
						$new_CityList .= $newlist[2] . ",";
						$new_StateList .= $newlist[1] . ",";
					}
				}
			}
			/* :( HACK to trim last , from city list */
			$new_CityList = substr($new_CityList, 0, -1);
			$new_StateList = substr($new_StateList, 0, -1);
			/* Preferred Study Location(s) END */
			/* Get all lead realted data */
			$finalArray['sourcename'] = 'MARKETING_FORM';
			$finalArray['referer'] = $this->input->post('refererreg');
			$finalArray['resolution']= $this->input->post('resolutionreg');
			$subCategory = $this->input->post('subCategoryId');
			$finalArray['category'] = $this->input->post('categoryId');
			/* T USER SOURCE INFO */
			//$finalArray['referer'] = $_SERVER['HTTP_REFERER'];
			$finalArray['referer'] = 'https://shiksha.com#home';
			$finalArray['landingpage'] = $this->input->post('loginactionreg');
			$finalArray['browser'] = $_SERVER['HTTP_USER_AGENT'];
			/* T USER SOURCE INFO */

			$city = $finalArray['residenceCity'];
			$preferredCity = $new_CityList;
			$mcourse = '';
			$city = $finalArray['residenceCity'];
			$firstName = $finalArray['firstName'];
			$mobile = $finalArray['mobile'];
			$age = '';
			$gender  = '';
			$email = $finalArray['email'];
			$displayname = $finalArray['displayname'];
			$mPageName = $this->input->post('mpagename');
			$mdpassword = $finalArray['password'];
			$password = $finalArray['textPassword'] ;

			if ($mPageName == 'it_courses')
			{
                                /* Work Experience */
			        $finalArray['experience'] = $this->input->post('ExperienceCombo');
				$perferencecity = array_unique($this->input->post('perferencecity', true));
				foreach($perferencecity as $locality_city_name) {
					if ($locality_city_name != "") {
						$newlist = explode(':',$locality_city_name);
						$new_CityList .= $newlist[2] . ",";
						$new_StateList .= $newlist[1] . ",";
					}
				}
				$new_CityList = substr($new_CityList, 0, -1);
				$new_StateList = substr($new_StateList, 0, -1);
			}
                        error_log("before CALLING getRedirectedListingPage");
			if($marketingpagename!='testpreppage'){
			//$finalUrl =  $this->codeforlandingpage($new_CityList,$finalArray['category'],$subCategory,$city);
                            error_log("CALLING getRedirectedListingPage");
			$finalUrl =  $this->getRedirectedListingPage($city,$new_CityList,$new_StateList,$finalArray["desiredCourse"]);
			}
			if($this->input->post('desiredCourse')>=25 && $this->input->post('desiredCourse')<=34) {
		    $finalUrl = SHIKSHA_HOME .'/search/index?keyword=MBA+Correspondence&location=&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subLocation=-1&cityId=-1&cType=correspondence&courseLevel=-1&subType=0&showCluster=-1&channelId=home_page';
		    }
			$finalArray['landingpage'] = $finalUrl;
			if ($marketingpagename == 'testpreppage')
			{
				$blogId = $this->input->post('desiredCourse');
                                //echo "blog id is".$blogId;
				$finalArray['desiredCourse'] = 0;
				$this->load->library('category_list_client');
				$blog_acronym = $this->category_list_client->getBlogAcronym($blogId);
                                //echo "blog id is".$blog_acronym;
				$this->load->library('url_manager');
				if (isset($finalArray['cityId'][0])) {
					$cityname = $this->category_list_client->getCityName($finalArray['cityId'][0]);
				} else {
					$cityname = '';
				}

				if(empty($finalUrl)){
				$finalUrl = $this->url_manager->get_testprep_url('', $blog_acronym,$cityname,'', '');
				}
				$finalArray['landingpage'] = $finalUrl;
				$finalArray['testPrep_blogid'] = $blogId;
				$finalArray['testPrep_status'] = 'live';
				$finalArray['testPrep_status'] = 'live';
				$finalArray['testPrep_updateTime'] = date("Y-m-d H:i:s");
				$finalArray['extraFlag'] = 'testprep';
			}
            $email = $finalArray['email'];
            $mobile = $finalArray['mobile'];
			error_log('LDBSERVER :: LDB process complete');
            if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
                echo "Blank" ;
                exit;
            }
			if($finalArray['email'] == '')
			{
				echo "Blank" ;
				exit;
			}
			else
			{
				if(!isset($Validate[0]['userid'])) {
					error_log(' LDBSERVER :: LDB call user is not sign in and insertion start.');
					/* captcha will validate if user is not logged-in */
					$secCode = $this->input->post('homesecurityCode_mp');
					if(verifyCaptcha('seccodehome_mp',$secCode,1))
					{
						// save data here
						$response = $registerClient->addUser($appId,$finalArray);
						error_log(print_r($finalArray,true).' LDBSERVER :: FINAL ARRAY TO ADD USER');
						if($response['status'] > 0)
						{
							//Set the cookie when the user has registered
							$Validate = $this->checkUserValidation();
							if(!isset($Validate[0]['userid'])){
								$value = $email.'|'.$finalArray['password'];
								$this->cookie($value);
							}
							$this->load->library('user/UserLib');
                            $userLib = new UserLib;
                            $userLib->sendEmailsOnRegistration($response['status'], array(), $password);
                            $userLib->updateUserData($response['status']);
			                $response['finalUrl'] = $finalUrl;
			                $response['flagfirsttime'] = 'true';
			                echo json_encode($response);

						}
						else
						{
							// If the user already exists (based on email/displayname)
							if($response['status'] == -1)
							{
								if($response['email'] == -1 && $response['displayname'] == -1)
								echo 'both';
								else
								{
									if($response['email'] == -1)
									echo 'email';
									if($response['displayname'] == -1)
									echo 'displayname';
								}

                    }
                    else
                    {
                        echo 0;
                    }
                }
                }
                else
                {
                echo "code";
                }
            } // end if user logged-in check
            else
            {

//             error_log(' LDBSERVER :: LDB call user is logged-in and update start.');
//             if($this->input->post('mupdateflag') == "update")
//             {
//                         /*
//                          Remove few items from array as logged-in user submit data so we do'nt need to reset password and cookie
//                          */
//             unset($finalArray['displayname']);
//             /* Text password */
//             unset($finalArray['textPassword']);
//             /* MD5 password */
//             unset($finalArray['password']);
//
//             $userId = $Validate[0]['userid'];
//             $response = $registerClient->updateUser($appId,$finalArray,$userId);
//             //}
//             $response['finalUrl'] = $finalUrl;
//             $response['flagfirsttime'] = 'false';
//             echo json_encode($response);
            }
        }
        // end if of main form CRUD
    }
    
    /**
     * Function for home page user operation
     */
    function homePageUserOperation() {
        $this->init();
        $appId = 1;

    // Check for the availability of the display name if not available generate it using ranndom key else use the name as display name
    $register_client = new Register_client();
    $displayname = $this->input->post('homename', true);
    $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
    while($responseCheckAvail == 1){
        $displayname = $this->input->post('homename') . rand(1,100000);
        $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
    }
    $userarray = array();
    $this->cookForRegistration($userarray);
    $userarray['displayname'] = $displayname;

    $preferredCity = $this->input->post('mCityList');
    $preferredCity =  trim($preferredCity,',');
    $preferredCityName = $this->input->post('mCityListName');
    $mPageName = $this->input->post('mPageName');
    $mcourse = $this->input->post('mcourse');
    $countries = trim($this->input->post('mCountryList'), ',');
    $modeoffinance = $this->input->post('sourceFunding');
    $nearestMetropolitanCity = $this->input->post('mCity');


    // Check availability code ends

    // By default viamobile,viamail,newslettermail to set as 1
    $viamobile = 1;
    $viamail = 1;
    $newsletteremail =1;
    /* User array for marketing table */
    $addReqInfo = array();
    $Validate = $this->checkUserValidation();
    //error_log(print_r($Validate,true));
    if(isset($Validate[0]['userid'])) {
        $exploded = explode('|',$Validate[0]['cookiestr']);
        $email = $exploded[0];
        $password = $exploded[1];
    }
    $flagvalue = 'false';

    $addReqInfo['email'] = $userarray['email'];
    $addReqInfo['displayName'] = $userarray['displayname'];
    $addReqInfo['residenceLoc'] = $userarray['residenceCity'];
    $addReqInfo['age'] = $userarray['age'];
    $addReqInfo['gender'] = $userarray['gender'];
    $addReqInfo['highestQualification'] = $userarray['educationlevel'];
    $addReqInfo['flagRegistered'] = "true";
    $addReqInfo['mobile'] = $userarray['mobile'];
    $addReqInfo['mPageName'] = $mPageName;
    $addReqInfo['marketingFlag'] = $flagvalue;
    $addReqInfo['category'] = $userarray['categories'];
    $addReqInfo['subCategory'] = $userarray['subCategories'];
    $addReqInfo['preferredcity'] = $preferredCity;


    // Make array for LeadKeyValue pair
    $LeadInterest = array('category' => $categories,'subCategory'=>$subCategory,'city'=>$preferredCity,'countries'=> $countries);

    //Make array for keyvalue pair
    if($mPageName == "studyAbroad") {
        $nearestMetropolitanCity = $userarray['residenceCity'];
        $courseStartTime = $userarray['plan'];
        $modeoffinance = '';
        if(!empty($userarray['userFundsNone'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'None';
        }
        if(!empty($userarray['userFundsBank'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'Bank Funds';
        }
        if(!empty($userarray['userFundsOwn'])) {
            $modeoffinance .= $modeoffinance == '' ? '' : ',';
            $modeoffinance .= 'Own Funds';
        }
        $keyvalarray = $this->makeLeadKeyValueForStudyAbroad($categories,$countries,$nearestMetropolitanCity,$modeoffinance,$courseStartTime);
    } else {
        $keyvalarray = $this->makeLeadKeyValue($categories,$subCategory,$preferredCity,$mcourse);
    }
    //error_log(print_r($keyvalarray,true));
    $finalUrl =  $this->codeforlandingpage($preferredCity,$categories,$subCategory,$city);
    if($mPageName == "studyAbroad") {
        $countriesSent = explode(",",$this->input->post('mCountryListName'));
        error_log("ShirishSTA 12121".print_r($countriesSent,true));
        $index = rand(0,count($countriesSent)-1);
        $countrySelected = $countriesSent[$index];
        if($countrySelected == "") {
            $countrySelected = $countriesSent[$index-1];
        }
        $countrySelected = str_replace(' ','',$countrySelected);
        global $countries;
        foreach($countries as $key=>$value) {
            if($value['name'] == $countrySelected) {
                $countrySelected = $key;
                break;
            }
        }

        //$countrySelected = "usa";
        $pageName = strtoupper('SHIKSHA_'. $countrySelected.'_HOME');
        $countryUrl = constant($pageName);
        if(strstr($countryUrl,"getCategoryPage") > -1) {
            $countryUrl= $countryUrl;
        } else {
            $countryUrl= $countryUrl.'/getCategoryPage/colleges/studyabroad/'.$countrySelected;
        }
        global $categoryMap;
        foreach($categoryMap as $key=>$value) {
            if($value['id'] == $categories) {
                $categoryNameInUrl = $key;
                break;
            }
        }
        $finalUrl = $countryUrl."/All/".$categoryNameInUrl."/All";
        
        $this->load->library('categoryList/categoryPageRequest');
	$requestURL = new CategoryPageRequest();

        error_log("LDBX ".print_r($_POST,true));
        $xdata = array();
        $xdata["categoryId"] = $this->input->post("board_id");
        
        $regions = trim($this->input->post('mRegions'), '#');
        if(!empty($regions)){
            $regionsArr = explode("#",$regions);
            $choice = rand()%count($regionsArr);
            $region = $regionsArr[$choice];
            $xdata["regionId"] = $region;
            error_log("LDBX ".print_r($xdata,true));
            $requestURL->setData($xdata);
            $finalUrl = $requestURL->getURL();
        }
        else{
            $xcountries = explode(",",trim($this->input->post('mCountryList'), ','));
            $this->load->library('categoryList/clients/CategoryPageClient');
            $testReq = new CategoryPageClient();

            $testObj = new CategoryPageRequest();
            $testObj->setData(array("categoryId"=>$xdata["categoryId"] ));
            $xArray = $testReq->getDynamicLocationList($testObj);
            error_log("LDBX ".print_r(array_unique($xArray["countries"]),true));

            $random = rand()%count($xcountries);
            $xdata["countryId"] = $xcountries[$random];
            $requestURL->setData($xdata);
            error_log("LDBX ".print_r($requestURL,true));

            $finalUrl = $requestURL->getURL();
            error_log("LDBX ".$finalUrl);
        }
    }
    $email = $userarray['email'];
    $mobile = $userarray['mobile'];
    if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
        echo "Blank" ;
        exit;
    }
    if($email == '' || $displayname == '') {
        echo "Blank" ;
        exit;
    } else {
        $secCodeSessionVar = 'seccodehome_sb';
        $secCode = $this->input->post('homesecurityCode_sb');
        $mPageName = 'StudyAbroad';
        $userarray['referer'] = 'https://shiksha.com#home';
	$userarray['landingpage'] = $_SERVER['HTTP_REFERER'];
	$userarray['browser'] = $_SERVER['HTTP_USER_AGENT'];
        call_user_func(array($this, 'cookFor'. $mPageName), $userarray);
        if(verifyCaptcha($secCodeSessionVar,$secCode,1)) {
            if(!isset($Validate[0]['userid'])){
                //$sourcename = 'MARKETING_FORM';
                $userarray['sourcename'] = 'https://shiksha.com#home';
                $userarray['quicksignupFlag'] = "marketingPage";
                $userarray['usergroup'] = 'marketingPage';
                if(!array_key_exists('password', $userarray) ) {
                    $password = 'shiksha@'. rand(1,1000000);
                    $mdpassword = sha256($password);
                    $userarray['textPassword'] =  $password;
                    $userarray['password'] = $mdpassword;
                }

                // Add new user
                $addResult = $register_client->addUser(1,$userarray);
                if($addResult['status'] > 0) {
                    //Set the cookie when the user has registered
                    $Validate = $this->checkUserValidation();
                    if(!isset($Validate[0]['userid'])){
                        $value = $email.'|'.$mdpassword;
                        $this->cookie($value);
                    }

                    $this->load->library('user/UserLib');
                    $userLib = new UserLib;
                    $userLib->sendEmailsOnRegistration($addResult['status'], array(), $password);
                    $userLib->updateUserData($addResult['status']);
                    
                    //  put the user data in lead log tables
                    if($mPageName != "homePage") {
                        $this->load->library('MarketingClient');
                        $marketingClientObj = new MarketingClient();
                        $addUser = $marketingClientObj->registerUserForLead(1,$addResult['status'],$addReqInfo,$LeadInterest,'',$keyvalarray,'add');
                    }
                    //echo $finalUrl;
                $addResult['finalUrl'] = $finalUrl;
                $addResult['flagfirsttime'] = 'true';
                    echo json_encode($addResult);
                } else {
                    // If the user already exists (based on email/displayname)
                    if($addResult['status'] == -1) {
                        if($addResult['email'] == -1 && $addResult['displayname'] == -1)
                            echo 'both';
                        else {
                            if($addResult['email'] == -1)
                                echo 'email';
                            if($addResult['displayname'] == -1)
                                echo 'displayname';
                        }
                    } else {
                        echo 0;
                    }
                }
            } else {
                /* User array for updating tuser table */

                $addResult = $register_client->updateUser(12,$userarray,$Validate[0]['userid']);
                if($mPageName != "homePage") {
                    $this->load->library('MarketingClient');
                    $marketingClientObj = new MarketingClient();
                    $addUser = $marketingClientObj->registerUserForLead(1,$Validate[0]['userid'],$addReqInfo,$LeadInterest,$userarray,$keyvalarray,'update');
                }
                $addResult['finalUrl'] = $finalUrl;
                $addResult['flagfirsttime'] = 'false';
                echo json_encode($addResult);
            }
            }
            else
            echo "code";
        }
    }
    /**
     * this method registers users for unified registration from  different widgets on the site
     *
     * @param string $secCodeSessionVar
     * @access	public
     * @return	void
     */
    function UnifiedRegistrationUserOperation($secCodeSessionVar='seccodehome') {
        error_log('LDB start');
        $this->init();
        $registerClient = new Register_client();
        /* useless var. at least in this case !!! */
        $appId = 1;
        /* build an array for form values  in formatted manner */
        $finalArray = array();
        /* get user current status */
        $Validate = $this->checkUserValidation();
        /* already user is registered so pick userid */
        if(is_array($Validate)) {
            $userId = $Validate[0]['userid'];
        }
        $local_course_type = $this->input->post('local_course_type');
        /* flag that identify it_degree or it_courses */
        $mPageName = $this->input->post('mpagename');
        /* flag that tells page name */
        $marketingpagename = $this->input->post('marketingpagename');
        //start making user array to update or insert
        // set desired course
        $finalArray['desiredCourse'] = $this->input->post('desiredCourse');
        if($finalArray['desiredCourse']>=25 && $finalArray['desiredCourse']<=34){
            $finalArray['desiredCourse']=24;
            $finalArray['specializationId']=$this->input->post('desiredCourse');
        }
        $finalArray['newsletteremail'] = 1;
        // Set form input values for national pg
        if ($mPageName == 'it_degree' ) {
            /* CAT MAT like  EXAM Details */
            $flag_cat_mat_exm_marks = $this->input->post('ExamsTaken');
            if(!empty($flag_cat_mat_exm_marks)) {
                foreach($flag_cat_mat_exm_marks as $exam_value) {
                    $exm_marks = $this->input->post($exam_value .'_exm_marks');
                    if((!empty($exam_value)) && ($exam_value != 'Percentile') && (($exam_value == 'cat' ||$exam_value == 'mat'))) {
                        $finalArray['name'][] = strtoupper($exam_value);
                        $finalArray['marks'][] = $exm_marks;
                        $finalArray['instituteId'][] = '';
                        $finalArray['marksType'][] = 'percentile';
                        $finalArray['level'][] = 'Competitive exam';
                        $finalArray['courseCompletionDate'][] = '';
                        $finalArray['ongoingCompletedFlag'][] ='';
                        $finalArray['city'][] ='';
                        $finalArray['country'][] ='';
                        $finalArray['educationStatus'][] ='live';
                    }
                }
            }
            /*Degree Preference*/
            $degree_preference  = $this->input->post('degree_preference');
            if (is_array($degree_preference)) {
                foreach ($degree_preference as $value) {
                    switch($value) {
                    case 'aicte_approved':
                        $finalArray['degreePrefAICTE'] = 'yes';
                        break;
                    case 'ugc_approved':
                        $finalArray['degreePrefUGC'] = 'yes';
                        break;
                    case 'international':
                        $finalArray['degreePrefInternational']= 'yes';
                        break;
                    case 'any':
                        $finalArray['degreePrefAny']= 'yes';
                        break;
                    }
                }
            }
        }
        if ($mPageName != 'graduate_course' ) {
            /* UG DETAILS */
            $ug_detials_courses =$this->input->post('ug_detials_courses');
            if (!empty($ug_detials_courses)) {
                $finalArray['name'][] = $ug_detials_courses;
                global $ug_course_mapping_array;
                foreach($ug_course_mapping_array as $key => $value)
                {
                    if($value == $ug_detials_courses)
                    {
                        $educationLevel = $key;
                        error_log($educationLevel.'EDUCATIONLEVEL');
                        break;
                    }
                }
                if ($this->input->post('Completed') == 'completed') {
                    $marks = $this->input->post('ug_detials_courses_marks');
                } else {
                    $marks = '';
                }

                $schoolCombo = '';
                $citiesofeducation = '';

                $finalArray['marks'][] = $marks ;
                $finalArray['instituteId'][] = $schoolCombo;
                $finalArray['marksType'][] = 'percentage';
                $finalArray['level'][] = 'UG';
                $com_year_year = $this->input->post('com_year_year');
                $com_year_month = $this->input->post('com_year_month');
                if (!empty($com_year_year) && !empty($com_year_month)) {
                    $finalArray['courseCompletionDate'][] = $this->input->post('com_year_year') . '-'.$this->input->post('com_year_month') .'-'. '1';
                } else {
                    $finalArray['courseCompletionDate'][] = '';
                }
                $finalArray['ongoingCompletedFlag'][] = $this->input->post('Completed') == 'completed'?"0":"1";
                $finalArray['city'][] = $citiesofeducation;
                $finalArray['country'][] ='2';
                $finalArray['educationStatus'][] ='live';
                /* added zone feature Start*/
                if ($mPageName == 'it_courses') {
                    $perferencelocality = $this->input->post('perferencelocality', true);
                    $perferencecity = $this->input->post('perferencecity', true);
                    $i = 0;
                    foreach($perferencelocality as $locality_city_name) {
                        if ($perferencelocality[$i] != "") {
                            if ($perferencelocality[$i] == '-1') {
                                $newlist = explode(':',$perferencecity[$i]);
                                if (in_array($newlist[2],$finalArray['cityId']) == false) {
                                    $finalArray['countryId'][] = $newlist[0];
                                    $finalArray['stateId'][] = $newlist[1];
                                    $finalArray['cityId'][] = $newlist[2];
                                    $finalArray['localityId'][] = 0;
                                }
                            } else {
                                $newlist = explode(':',$perferencelocality[$i]);
                                if (in_array($newlist[3],$finalArray['localityId']) == false) {
                                    $finalArray['countryId'][] = $newlist[0];
                                    $finalArray['stateId'][] = $newlist[1];
                                    $finalArray['cityId'][] = $newlist[2];
                                    $finalArray['localityId'][] = $newlist[3];
                                }
                            }
                        }
                        //added below condition for bugzilla 40299
//                         else if(trim($perferencecity[$i]) && !trim($perferencelocality[$i])) {
//                             $newlist = explode(':',$perferencecity[$i]);
//                             $finalArray['countryId'][] = $newlist[0];
//                             $finalArray['stateId'][] = $newlist[1];
//                             $finalArray['cityId'][] = $newlist[2];
//                             $finalArray['localityId'][] = null;
//                         }
                        $i++;
                    }
                }
                /* added zone feature End */
            }
        }
        if ($mPageName == 'graduate_course'  || $local_course_type == 'ug') {
            // 10th Details
            $xii_passing_year = $this->input->post('10_com_year_year');
            $xii_percentage = $this->input->post('10_ug_detials_courses_marks');
            // 10th Stream [science_arts,science_commerce,science_stream]
            $xii_stream = $this->input->post('science_stream');
            if (!empty($xii_stream) || (!empty($xii_passing_year))||(!empty($xii_percentage))) {
                $finalArray['instituteId'][] = '';
                $finalArray['marksType'][] = 'percentage';
                $finalArray['level'][] = '12';
                $finalArray['city'][] ='';
                $finalArray['country'][] ='2';
                if (!empty($xii_stream)) {
                    if ($xii_stream == 'science_arts') {
                        $finalArray['name'][] = 'arts';
                    } else if ($xii_stream == 'science_commerce') {
                        $finalArray['name'][] = 'commerce';
                    } else {
                        $finalArray['name'][] = 'science';
                    }
                }else{
                    $finalArray['name'][] = '';
                }
                if (!empty($xii_percentage)) {
                    $finalArray['marks'][] = $xii_percentage;
                } else {
                    $finalArray['marks'][] = '';
                }
                if(!empty($xii_passing_year)) {
                    $finalArray['courseCompletionDate'][] = $xii_passing_year . '-01-01';
                    $finalArray['ongoingCompletedFlag'][] =$xii_passing_year > date('Y') ? '1' :'0';
                } else {
                    $finalArray['courseCompletionDate'][] = '';
                    $finalArray['ongoingCompletedFlag'][] = '';
                }
            }
        }
        if ($mPageName != 'it_courses') {
            $mode_preference  = $this->input->post('mode');
            if (is_array($mode_preference)) {
                foreach ($mode_preference as $value) {
                    switch($value) {
                    case 'full_time':
                        $finalArray['modeOfEducationFullTime'] = 'yes';
                        break;
                    case 'part_time':
                        $finalArray['modeOfEducationPartTime'] = 'yes';
                        break;
                    case 'distance':
                        $finalArray['modeOfEducationDistance']= 'yes';
                        break;
                    }
                }
            }
        }
        /* when you plan to start study */
        $finalArray['timeOfStart'] = $this->input->post('plan');
    /*
     Residence Location added zone feature
     we need to check if residence city is not present then pass first location
     of preference for redirection url
     */
        $residenceLocation  = $this->input->post('citiesofquickreg');
        if (!empty($residenceLocation)) {
            $finalArray['residenceCity']=$this->input->post('citiesofquickreg');
        }

        /* Mobile */
        if($this->input->post('mobile')) {
            $finalArray['mobile']= $this->input->post('mobile');
        }
        if($this->input->post('email')) {
            /* Email id */
            $finalArray['email']= $this->input->post('email');
        }
        if($this->input->post('firstname')) {
            /* First Name */
            $finalArray['firstName']= $this->input->post('firstname');
        }
        if($this->input->post('lastname')) {
            /* First Name */
            $finalArray['lastName']= $this->input->post('lastname');
        }

        ##############################################
        /* Loop to check unique displayname START */
        if(isset($finalArray['firstName'])) {
            $responseCheckAvail = 1;
            error_log('  LDBSERVER ::  LDB WHILE START'.microtime(true));
            while($responseCheckAvail == 1){
                $displayname = $finalArray['firstName'] . rand(1,100000);
                $responseCheckAvail = $registerClient->checkAvailability($appId,$displayname,'displayname');
            }
            error_log('  LDBSERVER ::  LDB WHILE END'.microtime(true));
            /* Loop to check unique displayname END */
            $finalArray['displayname']= $displayname;
            /* Text password */
            $finalArray['textPassword'] = 'shiksha@'. rand(1,1000000);
            /* MD5 password */
            $finalArray['ePassword']  = sha256($finalArray['textPassword']);
        }
        ##############################################
        /* Preferred Study Location(s) START */
        $new_CityList = "";
        if ($mPageName != 'it_courses') {
            $mCityList = $this->input->post('mCityList');
            $mCityList = explode(',',$mCityList);
            foreach($mCityList as $value) {
                if (!empty($value)) {
                    $newlist = explode(':',$value);
                    if (count($newlist) < 3 ) {
                        continue;
                    }
                    $finalArray['countryId'][] = $newlist[0];
                    $finalArray['stateId'][] = $newlist[1];
                    $finalArray['cityId'][] = $newlist[2];
                    $finalArray['localityId'][] = 0;
                    $new_CityList .= $newlist[2] . ",";
                }
            }
        }
        /* :( HACK to trim last , from city list */
        $new_CityList = substr($new_CityList, 0, -1);
        /* Preferred Study Location(s) END */
        /* Get all lead realted data */
        $finalArray['sourcename'] = 'MARKETING_FORM';
        $finalArray['referer'] = $this->input->post('refererreg');
        $finalArray['resolution']= $this->input->post('resolutionreg');
        $subCategory = $this->input->post('subCategoryId');
        $finalArray['category'] = $this->input->post('categoryId');
         /* T USER SOURCE INFO */
        $widget_key = $this->input->post('input_widget_identifier_unified');
        $finalArray['referer'] = 'https://www.shiksha.com#'.$widget_key;
        $finalArray['landingpage'] = $_SERVER['HTTP_REFERER'];
        $finalArray['browser'] = $_SERVER['HTTP_USER_AGENT'];
        /* T USER SOURCE INFO */

        $city = $finalArray['residenceCity'];
        $preferredCity = $new_CityList;
        $mcourse = '';
        $city = $finalArray['residenceCity'];
        $firstName = $finalArray['firstName'];
        $lastName = $finalArray['lastName'];
        $mobile = $finalArray['mobile'];
        $age = '';
        $gender  = '';
        $email = $finalArray['email'];
        $displayname = $finalArray['displayname'];
        $mPageName = $this->input->post('mpagename');
        $mdpassword = $finalArray['ePassword'];
        $password = $finalArray['textPassword'] ;
        /* added zone feature Start*/
                if ($mPageName == 'it_courses') {
                    $perferencelocality = $this->input->post('perferencelocality', true);
                    $perferencecity = $this->input->post('perferencecity', true);
                    $i = 0;
                    foreach($perferencelocality as $locality_city_name) {
                        if ($perferencelocality[$i] != "") {
                            if ($perferencelocality[$i] == '-1') {
                                $newlist = explode(':',$perferencecity[$i]);
                                if (in_array($newlist[2],$finalArray['cityId']) == false) {
                                    $finalArray['countryId'][] = $newlist[0];
                                    $finalArray['stateId'][] = $newlist[1];
                                    $finalArray['cityId'][] = $newlist[2];
                                    $finalArray['localityId'][] = 0;
                                }
                            } else {
                                $newlist = explode(':',$perferencelocality[$i]);
                                if (in_array($newlist[3],$finalArray['localityId']) == false) {
                                    $finalArray['countryId'][] = $newlist[0];
                                    $finalArray['stateId'][] = $newlist[1];
                                    $finalArray['cityId'][] = $newlist[2];
                                    $finalArray['localityId'][] = $newlist[3];
                                }
                            }
                        }
                        //added below condition for bugzilla 40299
//                         else if(trim($perferencecity[$i]) && !trim($perferencelocality[$i])) {
//                             $newlist = explode(':',$perferencecity[$i]);
//                             $finalArray['countryId'][] = $newlist[0];
//                             $finalArray['stateId'][] = $newlist[1];
//                             $finalArray['cityId'][] = $newlist[2];
//                             $finalArray['localityId'][] = null;
//                         }
                        $i++;
                    }
                }
                /* added zone feature End */
        if ($mPageName == 'it_courses')
        {
            /* Work Experience */
            $perferencecity = array_unique($this->input->post('perferencecity', true));
            foreach($perferencecity as $locality_city_name) {
                if ($locality_city_name != "") {
                    $newlist = explode(':',$locality_city_name);
                    $new_CityList .= $newlist[2] . ",";
                }
            }
            $new_CityList = substr($new_CityList, 0, -1);
        }
        if ($marketingpagename == 'testpreppage')
        {
            $blogId = $this->input->post('desiredCourse');
            $finalArray['desiredCourse'] = 0;
            $this->load->library('category_list_client');
            $blog_acronym = $this->category_list_client->getBlogAcronym($blogId);
            $this->load->library('url_manager');
            if (isset($finalArray['cityId'][0])) {
                $cityname = $this->category_list_client->getCityName($finalArray['cityId'][0]);
            } else {
                $cityname = '';
            }
            $finalArray['testPrep_blogid'] = $blogId;
            $finalArray['testPrep_status'] = 'live';
            $finalArray['testPrep_status'] = 'live';
            $finalArray['testPrep_updateTime'] = date("Y-m-d H:i:s");
            $finalArray['extraFlag'] = 'testprep';
        }
        // user age
        $user_age = $this->input->post('quickage');
        if (!empty($user_age)) {
            $finalArray['age'] = $user_age;
        }
        // Female,Male
        $user_gender = $this->input->post('quickgender');
        if (!empty($user_gender)) {
            $finalArray['gender'] = $user_gender;
        }
        if(!isset($Validate[0]['userid'])) {
            error_log(' LDBSERVER :: LDB call Insert start');
            if(array_key_exists('email', $_POST) && $finalArray['email'] == '')
            {
                echo "Blank" ;
                exit;
            }
            $email = $finalArray['email'];
            $mobile = $finalArray['mobile'];
            if ((validateEmailMobile('email',$email) == false) && (validateEmailMobile('mobile',$mobile) == false)) {
                echo "Blank" ;
                exit;
            }
            error_log(' LDBSERVER :: LDB call user is not sign in and insertion start.');
            /* captcha will validate if user is not logged-in */
            $secCode = $this->input->post('homesecurityCode');

            if(isset($_POST['tracking_keyid'])){
                $finalArray['tracking_keyid']= $this->input->post('tracking_keyid', true);
            }
            $finalArray['sessionid']=  sessionId();
            if(verifyCaptcha($secCodeSessionVar,$secCode,1))
            {
                // save data here
                $response = $registerClient->addUser($appId,$finalArray);
                error_log(print_r($finalArray,true).' LDBSERVER :: FINAL ARRAY TO ADD USER');
                if($response['status'] > 0)
                {
                    //Set the cookie when the user has registered
                    $Validate = $this->checkUserValidation();
                    if(!isset($Validate[0]['userid'])){
                        $value = $email.'|'.$finalArray['ePassword'];
                        $this->cookie($value);
                    }
                    
                    $this->load->library('user/UserLib');
                    $userLib = new UserLib;
                    $userLib->sendEmailsOnRegistration($response['status'], array(), $password);
                    $userLib->updateUserData($response['status']);
                    
                    $response['flagfirsttime'] = 'true';
                    $response['userDetailsStr'] = json_decode(base64_decode($response['userDetailsStr']),true);
                    echo json_encode($response);
                }
                else
                {
                    // If the user already exists (based on email/displayname)
                    if($response['status'] == -1)
                    {
                        if($response['email'] == -1 && $response['displayname'] == -1)
                            echo 'both';
                        else
                        {
                            if($response['email'] == -1)
                                echo 'email';
                            if($response['displayname'] == -1)
                                echo 'displayname';
                        }

                    }
                    else
                    {
                        echo 0;
                    }
                }
            }
            else
            {
                echo "code";
            }
        } // end if user logged-in check
        else
        {
            error_log(' LDBSERVER :: LDB call user is logged-in and update start.');
            if(isset($Validate[0]['userid']))
            {
            /*
             Remove few items from array as logged-in user submit data so we do'nt need to reset password and cookie
             */
                unset($finalArray['displayname']);
                /* Text password */
                unset($finalArray['textPassword']);
                /* MD5 password */
                unset($finalArray['ePassword']);

                $userId = $Validate[0]['userid'];
                $finalArray['prefId'] = $this->input->post('input_prefid_unified');
                $response = $registerClient->updateUser($appId,$finalArray,$userId);
            }
            $response['flagfirsttime'] = 'false';
            echo json_encode($response);
        }
    }
    /**
     * this method updates user details for study abroad pages of unified registration
     *
     * @access	public
     * @return	void
     */
    function UnifiedRegistrationUserOperationAbroad() {
        $this->init();
        $appId = 1;
        $register_client = new Register_client();
        $userarray = array();
        $this->cookForRegistration($userarray);
        $preferredCity = $this->input->post('mCityList');
        $preferredCity =  trim($preferredCity,',');
        $preferredCityName = $this->input->post('mCityListName');
        $mPageName = $this->input->post('mPageName');
        $mcourse = $this->input->post('mcourse');
        $countries = trim($this->input->post('mCountryList'), ',');
        $modeoffinance = $this->input->post('sourceFunding');
        $nearestMetropolitanCity = $this->input->post('mCity');
        // By default viamobile,viamail,newslettermail to set as 1
        $viamobile = 1;
        $viamail = 1;
        $newsletteremail =1;
        /* User array for marketing table */
        $addReqInfo = array();
        $Validate = $this->checkUserValidation();
        //error_log(print_r($Validate,true));
        if(isset($Validate[0]['userid'])) {
            $exploded = explode('|',$Validate[0]['cookiestr']);
            $email = $exploded[0];
            $password = $exploded[1];
        }
        $flagvalue = 'false';

        $addReqInfo['residenceLoc'] = $userarray['residenceCity'];
        $addReqInfo['age'] = $userarray['age'];
        $addReqInfo['gender'] = $userarray['gender'];
        $addReqInfo['highestQualification'] = $userarray['educationlevel'];
        $addReqInfo['flagRegistered'] = "true";
        //$addReqInfo['mobile'] = $userarray['mobile'];
        $addReqInfo['mPageName'] = $mPageName;
        $addReqInfo['marketingFlag'] = $flagvalue;
        $addReqInfo['category'] = $userarray['categories'];
        $addReqInfo['subCategory'] = $userarray['subCategories'];
        $addReqInfo['preferredcity'] = $preferredCity;
        // user age
        $user_age = $this->input->post('quickage');
        if (!empty($user_age)) {
            $userarray['age'] = $user_age;
        }
        // Female,Male
        $user_gender = $this->input->post('quickgender');
        if (!empty($user_gender)) {
            $userarray['gender'] = $user_gender;
        }

        // Make array for LeadKeyValue pair
        $LeadInterest = array('category' => $categories,'subCategory'=>$subCategory,'city'=>$preferredCity,'countries'=> $countries);

        //Make array for keyvalue pair
        if($mPageName == "studyAbroad") {
            $nearestMetropolitanCity = $userarray['residenceCity'];
            $courseStartTime = $userarray['plan'];
            $modeoffinance = '';
            if(!empty($userarray['userFundsNone'])) {
                $modeoffinance .= $modeoffinance == '' ? '' : ',';
                $modeoffinance .= 'None';
            }
            if(!empty($userarray['userFundsBank'])) {
                $modeoffinance .= $modeoffinance == '' ? '' : ',';
                $modeoffinance .= 'Bank Funds';
            }
            if(!empty($userarray['userFundsOwn'])) {
                $modeoffinance .= $modeoffinance == '' ? '' : ',';
                $modeoffinance .= 'Own Funds';
            }
            $keyvalarray = $this->makeLeadKeyValueForStudyAbroad($categories,$countries,$nearestMetropolitanCity,$modeoffinance,$courseStartTime);
        }
        if($mPageName == "studyAbroad") {
            $countriesSent = explode(",",$this->input->post('mCountryListName'));
            error_log("ShirishSTA 12121".print_r($countriesSent,true));
            $index = rand(0,count($countriesSent)-1);
            $countrySelected = $countriesSent[$index];
            if($countrySelected == "") {
                $countrySelected = $countriesSent[$index-1];
            }
            $countrySelected = str_replace(' ','',$countrySelected);
            global $countries;
            foreach($countries as $key=>$value) {
                if($value['name'] == $countrySelected) {
                    $countrySelected = $key;
                    break;
                }
            }

            //$countrySelected = "usa";
            $pageName = strtoupper('SHIKSHA_'. $countrySelected.'_HOME');
            $countryUrl = constant($pageName);
            if(strstr($countryUrl,"getCategoryPage") > -1) {
                $countryUrl= $countryUrl;
            } else {
                $countryUrl= $countryUrl.'/getCategoryPage/colleges/studyabroad/'.$countrySelected;
            }
            global $categoryMap;
            foreach($categoryMap as $key=>$value) {
                if($value['id'] == $categories) {
                    $categoryNameInUrl = $key;
                    break;
                }
            }
        }
        call_user_func(array($this, 'cookFor'. $mPageName), $userarray);
        if(isset($Validate[0]['userid'])){
            $widget_key = $this->input->post('input_widget_identifier_unified');
            $userarray['sourcename'] = 'https://www.shiksha.com#'.$widget_key;
            $userarray['referer'] = 'https://www.shiksha.com#'.$widget_key;
            $userarray['quicksignupFlag'] = "marketingPage";
            $userarray['usergroup'] = 'marketingPage';
            $userarray['prefId'] = $this->input->post('input_prefid_unified');
            /* User array for updating tuser table */
            $addResult = $register_client->updateUser(12,$userarray,$Validate[0]['userid']);
            if($mPageName != "homePage") {
                $this->load->library('MarketingClient');
                $marketingClientObj = new MarketingClient();
                $addUser = $marketingClientObj->registerUserForLead(1,$Validate[0]['userid'],$addReqInfo,$LeadInterest,$userarray,$keyvalarray,'update');
            }
            $addResult['flagfirsttime'] = 'false';
            echo json_encode($addResult);
        }
    }

    /**
     * Function for very quick registration for online forms
     */
    function veryQuickRegistrationForOnlineForms(){
        $this->init();
        $userarray['email'] = trim($this->input->post('quickemail'));
        $userarray['password'] = addslashes($this->input->post('quickpassword_ForAnA'));
        $confirmpassword = addslashes($this->input->post('quickconfirmpassword_ForAnA'));
        $userarray['ePassword'] = sha256($userarray['password']);
        $userarray['firstname'] = addslashes(trim($this->input->post('quickname_ForAnA')));
        $userarray['lastname'] = addslashes(trim($this->input->post('quicklastname_ForAnA')));
        $userarray['displayname'] = addslashes(trim($this->input->post('quickname_ForAnA')));
        $userarray['coordinates'] = $this->input->post('coordinates_ForAnA');
        $userarray['resolution'] = $this->input->post('resolution_ForAnA');
        $userarray['sourceurl'] = $this->input->post('referer_ForAnA');
        $userarray['sourcename'] = $this->input->post('loginproductname_ForAnA');
        $userarray['mobile'] = $this->input->post('quickMobile_ForAnA');

        $userarray['quicksignupFlag'] = 'veryshortregistration';
        $userarray['usergroup'] = 'veryshortregistration';
        $secCodeIndex = 'secCodeForAnAReg';
        $secCode = $this->input->post('securityCode_ForAnA');
        $this->load->model('UserPointSystemModel');
        $addResult = $this->UserPointSystemModel->doQuickRegistration($userarray,$secCode,$secCodeIndex);
        if($addResult > 0) {
            //Set the cookie when the user has registered
            $Validate = $this->checkUserValidation();
            if(!isset($Validate[0]['userid'])) {
                $this->load->model('usermodel');
                $userId = $this->usermodel->getUserIdByEmail($userarray['email']);
                $this->usermodel->trackUserLogin($userId);
                
                $value = $userarray['email'].'|'.$userarray['mdpassword'];
                error_log($value);
                $this->cookie($value);
            }
        }
        echo $addResult;
    }
    
    /**
     * Function to add search track parameter to url
     *
     * @param string $url
     * @return $url
     */
    function addSearchTrackParmToUrl($url){
        error_log("PAnKY In addSearchTrackParmToUrl url : " . $url);
        try {
            $url_suffix = "/search/index";
            $string_needs_to_be_present = $url_suffix;
            $pos = stripos($url, $string_needs_to_be_present);
            if($pos != false){
                $url .= "&tsr=mp";
            }    
        } catch(Exception $e) {
            error_log("tracksearch : error occured while adding track param to url");
        }
        error_log("PAnKY In addSearchTrackParmToUrl final url : " . $url);
        return $url;
    }
   
   /**
    * Function to get redirected listing page
    *
    * @param integer $city
    * @param array $preferred_cities
    * @param array $preferred_states
    * @param integer $LDBCourse
    * @return string $finalUrl
    */
    function getRedirectedListingPage($city,$preferred_cities,$preferred_states,$LDBCourse){
        error_log("LDBX ".$LDBCourse);
        $pCities = explode(",",$preferred_cities);
	$pStates = explode(",",$preferred_states);
        
        $onlyStates = array();
        for($i=0;$i<count($pStates);$i++){
            if($pCities[$i] == 0)$onlyStates[] = $pStates[$i];
        }
	$arrForRequest = array();
    if($city) {
        $arrForRequest[] = $city;
    }
	foreach($pCities as $y){
	    if($y != 0 && !in_array($y,$arrForRequest))$arrForRequest[] = $y;
	}
        $this->load->library('location/clients/LocationClient');
        $LocationClient = new LocationClient();
	$details = $LocationClient->getMultipleCity($arrForRequest);
        
        $state = $details[$city]["state_id"];
        $vCity = $details[$city]["virtualCityId"];
        
        $this->load->library('categoryList/categoryPageRequest');
	$this->load->library('categoryList/clients/CategoryPageClient');
	
        $data = array();
        $data["LDBCourseId"] = $LDBCourse;
        
        
        $flag = false;
        $pxCity = 0;
        foreach($details as $arr){
            if(in_array($arr["city_id"],$pCities) && $city == $arr["virtualCityId"]){
                if(!$flag){
                    $pxCity = $arr["city_id"];
                    $flag = true;
                }
                else if($flag && rand()%2 == 0){
                    $pxCity = $arr["city_id"];
                }
            }
        }
        
        if(in_array($vCity,$pCities)){
            $data["cityId"] = $vCity;
        }
        else if($pxCity != 0){
            $data["cityId"] = $pxCity;
        }
        else if(in_array($city,$pCities)){
            $data["cityId"] = $city;
        }
        else if($state > 0 && in_array($state,$onlyStates)){
            $data["stateId"] = $state;
        }
        else{
            $testReq = new CategoryPageClient();
            $testObj = new CategoryPageRequest();
            $testObj->setData(array("countryId"=>2, "LDBCourseId"=>$LDBCourse));
            $xArray = $testReq->getDynamicLocationList($testObj);
            $available_in_cities = array_unique($xArray["cities"]);

            $topCity = $pCities[0];$topTier = 1000;
            foreach($pCities as $pCity){
                if($pCity == 0)continue;
                if(!in_array($pCity, $available_in_cities)){
                    continue;
                }
                if($topTier > $details[$pCity]["tier"]){
                    $topCity = $pCity;
                    $topTier = $details[$pCity]["tier"];
                }
                else if($topTier == $details[$pCity]["tier"]){
                    if(rand()%2 == 0)continue;
                    $topCity = $pCity;
                    $topTier = $details[$pCity]["tier"];
                }
            }
            if($topCity == 0){
                $random = rand()%count($pStates);
                $data["stateId"] = $pStates[$random];
            }
            else{
                $data["cityId"] = $topCity;
            }
        }
        $requestURL = new CategoryPageRequest();
	$requestURL->setData($data);
	$finalUrl = $requestURL->getURL();
        error_log("LDBX ".$finalUrl);
	return $finalUrl;
    }
    
    /**
     * Function to check if email id exists
     *
     * @param string $userEmailId email id to check
     */
    function checkExistingEmail($userEmailId) {
        $this->init();
        $this->load->model('user/usermodel');
        $userId = $this->usermodel->getUserIdByEmail($userEmailId);
        
        if($userId) {
            $userInfo = array();
            $userInfo['userId'] = $userId;
            $userInfo['userEmailId'] = $userEmailId;
            echo json_encode($userInfo);
        }
        else {
            echo 'false';
        }
    }
    
    /**
     * Function to get LDB user's details
     *
     * @param string $user_id
     * @param string $email_id
     * @param string $is_ajax_call
     * @return array $response_array LDB user's details
     */
     function getLDBUserDetails($user_id = '',$email_id = '',$is_ajax_call = 'yes') {
    	$response_array = array();
	if(empty($user_id) && empty($email_id)) {
            $response_array['isLDBUser'] = 'NO';
            $response_array['userData'] = array();
	}
        
	$usr_model = $this->load->model('user/usermodel');
        if(empty($user_id) && !empty($email_id)) {
	    $user_id = $usr_model->getUserIdByEmail($email_id);
	}
        
        if(!empty($user_id)) {
	    $response_array = $usr_model->getLDBuserDetails($user_id);							
	}
        
        if($is_ajax_call) {
            echo json_encode($response_array);
            return;
	} else {
	    return $response_array;
	}
    }
    
    /**
     * Function to get the User Info
     *
     * @param string $userId
     * @param string $emailId
     * @param string $isAjaxCall
     * @return array $userInfoArray user info
     */
    function getUserInfo($userId = '',$emailId = '',$isAjaxCall = true) {
        $userRepository = \user\Builders\UserBuilder::getUserRepository();
        $usermodel = $this->load->model('user/usermodel');
        
        $userInfoArray = array();
        
        if(empty($userId) && !empty($emailId)) {
            $userId = $usermodel->getUserIdByEmail($emailId);
        }
        
        if(!empty($userId) && is_numeric($userId)) {
			
            $courseGroup = $usermodel->getCourseGroupByUserId($userId);
            $userInfo = $userRepository->find($userId);
            
            if(!is_object($userInfo)) {
				//return;
				if($isAjaxCall) {
					return json_encode($userInfoArray);
				} else {
					return $userInfoArray;
				}
			}
			
            $userPreference = $userInfo->getPreference();
            $userLocationPreferences = $userInfo->getLocationPreferences();
            $userEducation = $userInfo->getEducation();
        }
        
       
        if(!is_object($userInfo) || !is_object($userPreference)) {
                                //return;
        	if($isAjaxCall) {
                	return json_encode($userInfoArray);
                } else {
                        return $userInfoArray;
               }
        }      
 
 
        $userInfoArray['email'] = $userInfo->getEmail();
        $userInfoArray['firstName'] = $userInfo->getFirstName();
        $userInfoArray['lastName'] = $userInfo->getLastName();
        $userInfoArray['isdCode'] = $userInfo->getISDCode().'-'.$userInfo->getCountry();
        $userInfoArray['mobile'] = $userInfo->getMobile();
        
        $extraFlag = $userPreference->getExtraFlag();
        if($extraFlag == 'testprep') {
            $testPrepSpecializationPreferences = $userPreference->getTestPrepSpecializationPreferences();
            $userInfoArray['desiredCourse'] = $testPrepSpecializationPreferences[0]->getSpecializationId();
        }
        else if($extraFlag == 'studyabroad') {
            global $studyAbroadPopularCourses;
            $specializationId = $userPreference->getDesiredCourse();
            
            if(!empty($specializationId) && array_key_exists($specializationId, $studyAbroadPopularCourses)) {
                $userInfoArray['desiredCourse'] = $specializationId;
            }
            else {
                $desiredCourseForStudyAbroad = $usermodel->getDesiredCourseForStudyAbroad($specializationId);
                $userInfoArray['fieldOfInterest'] = $desiredCourseForStudyAbroad['fieldOfInterest'];
                $userInfoArray['desiredGraduationLevel'] = $desiredCourseForStudyAbroad['desiredGraduationLevel'];
            }
            
            $abroadSpecialization = $userPreference->getAbroadSpecialization();
            if(!empty($abroadSpecialization)) {
                $userInfoArray['abroadSpecialization'] = $abroadSpecialization;
            }
            
            $userInfoArray['budget'] = $userPreference->getBudget();
            $userInfoArray['passport'] = $userInfo->getPassport();
            $userInfoArray['workExperience'] = $userInfo->getExperience();
        }
        else {
            $userInfoArray['desiredCourse'] = $userPreference->getDesiredCourse();
            $userSpecializationPref = $userPreference->getSpecializationPreferences();
            if(is_object($userSpecializationPref[0]) && $userSpecializationPref[0]->getSpecializationId()) {
                $userInfoArray['specialization'] = $userSpecializationPref[0]->getSpecializationId();
            }
        }
        $addiotionalInfo = $userInfo->getUserAdditionalInfo();
        if(is_object($addiotionalInfo)){
           $userInfoArray['bookedExamDate'] = $addiotionalInfo->getBookedExamDate();
           $userInfoArray['currentClass']   = $addiotionalInfo->getCurrentClass();
           $userInfoArray['currentSchool']  = $addiotionalInfo->getCurrentSchool();
        }
        
        $userInfoArray['preferredStudyLocation'] = array();
        $userInfoArray['preferredStudyLocality'] = array();
        $userInfoArray['destinationCountry'] = array();
        foreach($userLocationPreferences as $location) {
            $localityId = $location->getLocalityId();
            $cityId = $location->getCityId();
            $stateId = $location->getStateId();
            $countryId = $location->getCountryId();
            
            if($courseGroup['acronym'] == 'localMBA' || $courseGroup['acronym'] == 'localUG' || $courseGroup['acronym'] == 'localPG' || $courseGroup['acronym'] == 'localUGSpecial' || $extraFlag == 'testprep') {
                if($cityId != 0) {
                    $userInfoArray['preferredStudyLocality'][] = $cityId.'+'.($localityId == 0 ? -1 : $localityId);
                }
            }
            
            if($courseGroup['acronym'] == 'fullTimeMBACourse' || $courseGroup['acronym'] == 'fullTimeMBA' || $courseGroup['acronym'] == 'BEBTech' || $courseGroup['acronym'] == 'nationalUG' || $courseGroup['acronym'] == 'nationalPG') {
                if($localityId != 0) {
                    $userInfoArray['preferredStudyLocation'][] = 'L:'.$localityId;
                }
                else if($cityId != 0) {
                    $userInfoArray['preferredStudyLocation'][] = 'C:'.$cityId;
                }
                else if($stateId != 0) {
                    $userInfoArray['preferredStudyLocation'][] = 'S:'.$stateId;
                }
            }
            
            if($extraFlag == 'studyabroad') {
                if($countryId != 0) {
                    $userInfoArray['destinationCountry'][] = $countryId;
                }
            }
        }
        if(empty($userInfoArray['preferredStudyLocation'])) {
            unset($userInfoArray['preferredStudyLocation']);
        }
        if(empty($userInfoArray['preferredStudyLocality'])) {
            unset($userInfoArray['preferredStudyLocality']);
        }
        if(empty($userInfoArray['destinationCountry'])) {
            unset($userInfoArray['destinationCountry']);
        }
        
        
        $userInfoArray['exams'] = array();
        foreach($userEducation as $education) {
            $level          = $education->getLevel();
            $marks          = $education->getMarks();
            $name           = $education->getName();
            $completionDate = $education->getCourseCompletionDate();
            
            if(!empty($completionDate) && gettype($completionDate) == 'object') {
                $year = intval($completionDate->format('Y'));
                $completionDate = $completionDate->format('Y-m-d');
            }
            
            if($level == 'PG'){
                $userInfoArray['levelPG']            = 'Masters';
                $userInfoArray['graduationStream'] = $education->getSubjects();
                $userInfoArray['graduationMarks']  = $marks;
            }
            else if($level == 'UG') {
                if($marks > 0) {
                    $userInfoArray['graduationStatus'] = 'Completed';
                }
                else {
                    $userInfoArray['graduationStatus'] = 'Pursuing';
                }
                $userInfoArray['graduationDetails'] = $name;
                $userInfoArray['graduationMarks'] = $marks;
                $userInfoArray['graduationCompletionYear'] = $year;
            }
            else if($level == 'Competitive exam') {
                $userInfoArray['exams'][] = $name;
                $userInfoArray[$name.'_score'] = $marks;
                if($name == 'MAT' || $name == 'CMAT') {
                    $userInfoArray[$name.'_year'] = $completionDate;
                }
                else {
                    $userInfoArray[$name.'_year'] = $year.'-01-01';
                }
            }
            else if($level == '12') {
                $userInfoArray['xiiMarks'] = $marks;
                $userInfoArray['xiiYear'] = $year;
                $userInfoArray['xiiStream'] = $name;
            }
            else if($level == '10') {
                $userInfoArray['levelUG']      = 'Bachelors';
                $board = $education->getBoard();
                $userInfoArray['tenthBoard'] = $board;

                if($board == 'CBSE') {
                    global $Reverse_CBSE_Grade_Mapping;
                    $marks = $Reverse_CBSE_Grade_Mapping[$marks];
                } else if($board == 'IGCSE') {
                    global $Reverse_IGCSE_Grade_Mapping;
                    $marks = $Reverse_IGCSE_Grade_Mapping[$marks];
                }
                $userInfoArray['tenthmarks'] = $marks;
            }
        }
        if(empty($userInfoArray['exams'])) {
            unset($userInfoArray['exams']);
        }
        
        
        if($courseGroup['acronym'] == 'fullTimeMBACourse' || $courseGroup['acronym'] == 'fullTimeMBA' || $courseGroup['acronym'] == 'localMBA') {
            $userInfoArray['workExperience'] = $userInfo->getExperience();
        }
        
        
       // if($courseGroup['acronym'] == 'fullTimeMBACourse' || $courseGroup['acronym'] == 'fullTimeMBA' || $courseGroup['acronym'] == 'BEBTech' || $courseGroup['acronym'] == 'nationalUG' || $courseGroup['acronym'] == 'nationalPG' || $extraFlag == 'studyabroad') {
            $userInfoArray['residenceCity'] = $userInfo->getCity();
            $userInfoArray['residenceLocality'] = $userInfo->getLocality();
      //  }
        
        
        if($courseGroup['acronym'] == 'fullTimeMBA' || $courseGroup['acronym'] == 'nationalUG'  || $courseGroup['acronym'] == 'nationalPG') {
            $userInfoArray['degreePreference'] = array();
            $userInfoArray['degreePreference'][] = $userPreference->getDegreePrefAICTE() == 'yes' ? 'aicte' : null;
            $userInfoArray['degreePreference'][] = $userPreference->getDegreePrefUGC() == 'yes' ? 'ugc' : null;
            $userInfoArray['degreePreference'][] = $userPreference->getDegreePrefInternational() == 'yes' ? 'international' : null;
            $userInfoArray['degreePreference'][] = $userPreference->getDegreePrefAny() == 'yes' ? 'any' : null;
        }
        
        
        $startDate = $userPreference->getTimeOfStart();
	if(isset($startDate) && gettype($startDate) == 'object') {
		if(intval($startDate->format('Y')) == -1 ) {
		    $startDate = '0000-00-00 00:00:00';
		}
		else {
		    $startDate = $startDate->format('Y-m-d H:i:s');
		}
	}

        $creationDate = $userInfo->getUserCreationDate();
        if(!empty($creationDate) && gettype($creationDate) == 'object') {
            $createYear = intval($creationDate->format('Y'));
            $createMonth = intval($creationDate->format('m'));
            $createDay = intval($creationDate->format('d'));
        }
        
        if((empty($userInfoArray['desiredCourse']) && empty($userInfoArray['fieldOfInterest'])) && (!empty($userInfoArray['exams']) || $startDate != '0000-00-00 00:00:00')) {
            $extraFlag = 'studyabroad';
        }
        
        if($extraFlag == 'studyabroad') {
            $whenFlag = 'whenPlanToGo';
            $whenStartMapping = array(
                                        'thisYear'  =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth,$createDay,$createYear)),
                                        'in1Year'   =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth,$createDay,$createYear+1)),
                                        'in2Years'  =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth,$createDay,$createYear+2)),
                                        'later'     =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth,$createDay,$createYear+3))
                                    );
        }
        else if($courseGroup['acronym'] == 'localMBA' || $courseGroup['acronym'] == 'localUG' || $courseGroup['acronym'] == 'localPG' || $courseGroup['acronym'] == 'localUGSpecial' || $extraFlag == 'testprep') {
            $whenFlag = 'whenPlanToStart';
            $whenStartMapping = array(
                                        'immediately'   =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth,$createDay,$createYear)),
                                        'within2Months' =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth + 2,$createDay,$createYear)),
                                        'within3Months' =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth + 3,$createDay,$createYear)),
                                        'notSure'       =>  '0000-00-00 00:00:00'
                                    );
        }
        else {
            $whenFlag = 'whenPlanToStart';
            $whenStartMapping = array(
                                        'thisYear'      =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth,$createDay,$createYear)),
                                        'nextYear'      =>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth,$createDay,$createYear+1)),
                                        'nextToNextYear'=>  date ('Y-m-d H:i:s', mktime(0, 0, 0, $createMonth,$createDay,$createYear+2)),
                                        'notSure'       =>  '0000-00-00 00:00:00'
                                    );
        }
        
        foreach($whenStartMapping as $key => $date) {
            if($startDate == $date) {
                $userInfoArray[$whenFlag] = $key;
                break;
            }
        }
        
        
        if($courseGroup['acronym'] == 'fullTimeMBA' || $courseGroup['acronym'] == 'BEBTech' || $courseGroup['acronym'] == 'nationalUG' || $courseGroup['acronym'] == 'nationalPG') {
            $userInfoArray['mode'] = array();
            if($userPreference->getModeOfEducationFullTime() == 'yes') {
                $userInfoArray['mode'][] = 'fullTime';
            }
            if($userPreference->getModeOfEducationPartTime() == 'yes') {
                $userInfoArray['mode'][] = 'partTime';
            }
        }
        
	
        if($isAjaxCall) {
            echo json_encode($userInfoArray);
        } else {
            return $userInfoArray;
        }
    }
    
    function UpdateUserBounce($email,$userid) {
		
		if(empty($email)) {
				echo "Enter a valid email id";
				exit;
		}
	 
        if($userid<1){
            echo "Enter a valid email id";
                return;   
        }
        $this->load->model('user/usermodel');
        $this->usermodel->addUserToIndexingQueue($userid);

        $user_response_lib = $this->load->library('response/userResponseIndexingLib');                    
        $extraData = "{'personalInfo:true'}";
        $user_response_lib->insertInIndexingQueue($userid, $extraData);

	    $this->load->model('usermodel');
	    $this->usermodel->UpdateUserBounce($email,$userid);
	    echo "Updated successfully for email".$email;		   
	}
}
?>
