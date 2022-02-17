<?php  
   /*

   Copyright 2007 Info Edge India Ltd
   This is file for shiksha help pages.
   All the static pages related to help and static pages like "about us" can be included over here.

   */
class ShikshaHelp extends MX_Controller {
	private $userStatus = 'false';
	function init(){
		$this->load->helper(array('url','form'));	
		$this->load->library(array('ajax','alerts_client'));
	    $this->load->library('category_list_client');

		$this->userStatus = $this->checkUserValidation();
	}
	function upsInfo(){
		$this->init();
		$data = array();
		$data['validateuser'] = $this->userStatus;
		$data['canonicalurl'] = SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/upsInfo';
		//below code used for beacon tracking
		$data['trackingpageIdentifier'] = 'userPointSystemInfoPage';
		$data['trackingcountryId']=2;


		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($data);

		$this->load->view('shikshaHelp/upsInfo',$data);
	}	
	function sendFeedBack(){
		$this->init();
		$appId = 12;
		$result = '';
		$feedBackResult = 'false';	
		$captchaResult = 0;
		$alertClient = new Alerts_client();
		$toEmail = ADMIN_EMAIL;
		$ccEmail = 'site.feedback@shiksha.com';
		$fromEmail = ADMIN_EMAIL;
		$subject = "Feedback for Shiksha by : ".$this->input->post('userFeedbackEmail');
		$content = $this->input->post('feedBackContent');
		$secCode = $this->input->post('feedbackSecCode');
		if(verifyCaptcha('feedbackSecurityCode',$secCode))
		{
			$result = $alertClient->externalQueueAdd($appId,$fromEmail,$toEmail,$subject,$content);
			$result = $alertClient->externalQueueAdd($appId,$fromEmail,$ccEmail,$subject,$content);
			$feedBackResult = 'true';	
			$captchaResult = 1;
		}
		echo json_encode(array('feedBackResult' => $feedBackResult,'captchaResult' => $captchaResult));
	}
	
	function aboutus(){
		$this->init();
		$data = array();
		$data['validateuser'] = $this->userStatus;
		$data['current_page_url'] = SHIKSHA_ABOUTUS_HOME;
        $data['searchEnable'] = true;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AboutUs');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        
		$this->load->view('shikshaHelp/aboutUs',$data);
	}

	function call(){
		$this->init();
		$data['validateuser'] = $this->userStatus;
		if (isset($data) && is_array($data['validateuser'])) {
			$arrTmp = explode('|',$this->userStatus[0]['cookiestr']);
			$data['email'] = $arrTmp[0];
		}
		$this->load->view('shikshaHelp/callcenter',$data);
	}

	function callSubmit(){
		$appId = 1;
		$this->init();
		$this->load->library('register_client');
		$register_client = new Register_client();

		$addReqInfo = array();
		if(is_array($this->userStatus))
		{
			$signedInUser = $this->userStatus;
			$email = explode('|',$signedInUser[0]['cookiestr']);
			$addInfo['displayName'] = $signedInUser[0]['displayname'];
			$addInfo['contact_cell'] = $this->input->post('contactno',true);
			$updatedStatus = $register_client->updateUserAttribute($appId,$signedInUser[0]['userid'],'mobile',$addInfo['contact_cell']);
			$addInfo['user_id'] = $signedInUser[0]['userid'];
			$reqInfoStatus = $register_client->requestCall(1,$addInfo);
			//print_r($reqInfoStatus);
		}
		else
		{
			$appId = 1;
			error_log_shiksha('controller');
			$displayname = $this->input->post('firstname').$this->input->post('lastname');
			$responseCheckAvail = $register_client->checkAvailability($appId,$displayname,"displayname");
			while($responseCheckAvail == 1)
			{
				$displayname = $this->input->post('firstname') . rand(1,100000);
				$responseCheckAvail = $register_client->checkAvailability($appId,$displayname,"displayname");
			}
			$email = $this->input->post('email');
			$password = rand(1,1000000);
			$ePassword = sha256($password);
			$userdetail = '';
			$city = '';$country = "";
			$mobile = $this->input->post('contactno');
			$viamobile = 1;
			$viamail = 1;
			$newsletteremail =1;
			$profession = '';
			$educationLevel = 1;
			$experience = 0;
			$appID = 1;
			$dob= "";
			$institute = "";
			$youare="";$gradYear="null";$userstatus="";
			$firstname = $this->input->post('firstname');
			$lastname = $this->input->post('lastname');
			$addResult = $register_client->adduser($email,$password,$ePassword,$displayname,$profession,$country,$city,$mobile,$viamobile,$viamail, $newsletteremail,$appID,$educationLevel,$experience,$dob,$institute,$youare,$gradYear,"callcounsellor",$firstname,$lastname);

			//print_r($addResult);
			if($addResult['status'] > 0)
			{
				$addInfo = array();
				$addInfo['displayName'] = $displayname;
				$addInfo['contact_cell'] = $mobile;
				$addInfo['user_id'] = $addResult['status'];
				$reqInfoStatus = $register_client->requestCall(1,$addInfo);
				$this->load->library('alerts_client');
				$mail_client = new Alerts_client();
				//$mailContent =  "Your Shiksha Account Info: <br/>Login : $email <br/>Password : $password <br/><a href = 'http://".THIS_IP."/'>shiksha</a>";
				//$sendMailRes = $mail_client->externalQueueAdd($appId,ADMIN_EMAIL,$email,'Your Shiksha Account Info!',$mailContent);
			}
			else
			{
				echo 'false';
			}
		}
		$this->load->view('shikshaHelp/callSubmit');
	}
	
	function errorPage()
	{
		$this->init();
		$data['errorPageFlag'] = 'true';
		//below code used for beacon tracking
		$data['trackingpageIdentifier'] = '404Page';
		$data['trackingcountryId']=2;


		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($data);
		$this->load->view('shikshaHelp/404Page',$data);
	}

	function errorPageAbroad()
	{
		$this->init();
		$data['errorPageFlag'] = 'true';
		$data['beaconTrackData'] = array(
						            'pageIdentifier' => '404Page',
						            'pageEntityId' => 0,
						            'extraData' => null
						            );
		$userEmail = $this->input->post("userEmail");
		if(empty($userEmail)){
			$data['validateuser'] = "false";
			$data['loggedInUserData'] = false;
			$this->load->view('shikshaHelp/404PageAbroad',$data);
			return;
		}
		$this->load->model('user/usermodel');
		$usermodel = new usermodel;
		$user 	= $usermodel->getUserByEmail($userEmail);
		if(is_object($user) && $user->getId() > 0) {
			if(!is_object($user))
			{
			 $data['loggedInUserData'] = false;
			}
			else
			{
			$name = $user->getFirstName().' '.$user->getLastName();
			$email = $user->getEmail();
			$userFlags = $user->getFlags();
			$isLoggedInLDBUser = $userFlags->getIsLDBUser();
			$data['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
					
			$pref = $user->getPreference();
			$loc = $user->getLocationPreferences();
			$isLocation = count($loc);
			if(is_object($pref)){
				$desiredCourse = $pref->getDesiredCourse();
			}else{
				$desiredCourse = null;
			}
			$data['loggedInUserData']['desiredCourse'] = $desiredCourse;
			$data['loggedInUserData']['isLocation'] = $isLocation;
			}
			
		}
		else {
			$data['validateuser'] = "false";
			$data['loggedInUserData'] = false;
		}
		$this->load->view('shikshaHelp/404PageAbroad',$data);
	}
	
	function cookie($value){
		setcookie('user',$value,time() + 2592000 ,'/');
	}
	
	function siteMap(){
		$this->init();
		global $countries;
		global $categoryParentMap;
		$data = array();
		$data['countries'] = $countries;
		$data['categoryParentMap'] = $categoryParentMap;
		$data['validateuser'] = $this->userStatus;			
		$this->load->view('shikshaHelp/siteMap',$data);
	}

	function contactUs(){
		$this->init();		
		$data['validateuser'] = $this->userStatus;
		$data['current_page_url'] = SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/contactUs';
                $data['searchEnable'] = true;
         //below code used for beacon tracking
		$data['trackingpageIdentifier'] = 'contactUsPage';
		$data['trackingcountryId']=2;
		$data['suggestorPageName'] = "all_tags";
		$data['qtrackingPageKeyId'] = 1378;

		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($data);

		$data['GA_userLevel'] = $this->userStatus[0]['userid'] > 0 ? 'Logged In' : 'Non-Logged In';

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_ContactUs');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('shikshaHelp/contactUs',$data);
	}

	function getCatTree(){
		$categoryClient = new Category_list_client();
		$categoryList = $categoryClient->getCategoryTree($appId);
            foreach($categoryList as $temp)
            {
                $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
            }
            //$displayData['completeCategoryTree'] = json_encode($categoryForLeftPanel);
			//$displayData['categoryList'] = json_encode($categoryList );
			return $categoryList;
	}
	function browseByCategory($maincat,$country,$subcat,$city,$cityName){
		$this->init();
		global $countries;
		global $categoryParentMap;
		$data = array();
		$data['countries'] = $countries;
		$data['categoryParentMap'] = $categoryParentMap;
		$data['validateuser'] = $this->userStatus;
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();	
		$categoryList = $categoryClient->getCategoryTree($appId);
		foreach($categoryList as $temp) {
			$categoryMap[$temp['categoryID']] = array('categoryUrlName'=>$temp['urlName'], 'categoryName'=>$temp['categoryName'], 'parentId'=>$temp['parentId']);
		}
		$data['categoryMap'] = $categoryMap;
		$data['maincat'] = $maincat;
		$data['country'] = $country;
		$data['subcat'] = $subcat;
		$data['city'] = $city;
		$data['cityName'] = $cityName;
		$data['categoryList'] = $this->getCatTree();
		$this->load->view('shikshaHelp/browebyCategory',$data);
	}
	function browseByCountry($maincat,$country,$subcat,$city,$cityName){
		$this->init();
		global $countries;
		global $categoryParentMap;
		$data = array();
		$data['countries'] = $countries;
		$data['categoryParentMap'] = $categoryParentMap;
		$data['validateuser'] = $this->userStatus;
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();	
		$categoryList = $categoryClient->getCategoryTree($appId);
		foreach($categoryList as $temp) {
			$categoryMap[$temp['categoryID']] = array('categoryUrlName'=>$temp['urlName'], 'categoryName'=>$temp['categoryName'], 'parentId'=>$temp['parentId']);
		}
		$data['categoryMap'] = $categoryMap;		
		$data['maincat'] = $maincat;
		$data['country'] = $country;
		$data['subcat'] = $subcat;
		$data['city'] = $city;
		$data['cityName'] = $cityName;
		$this->load->view('shikshaHelp/browebyCountry',$data);
	}
	function browseByColleges($maincat,$country,$subcat,$city,$cityName){
		$this->init();
		global $countries;
		global $categoryParentMap;
		$data = array();
		$data['countries'] = $countries;
		$data['categoryParentMap'] = $categoryParentMap;
		$data['validateuser'] = $this->userStatus;
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();	
		$categoryList = $categoryClient->getCategoryTree($appId);
		foreach($categoryList as $temp) {
			$categoryMap[$temp['categoryID']] = array('categoryUrlName'=>$temp['urlName'], 'categoryName'=>$temp['categoryName'], 'parentId'=>$temp['parentId']);
		}
		$data['categoryMap'] = $categoryMap;		
		$data['maincat'] = $maincat;
		$data['country'] = $country;
		$data['subcat'] = $subcat;
		$data['city'] = $city;
	    $data['cityName'] = $cityName;
	    //below code used for beacon tracking
		$data['trackingpageIdentifier'] = 'browseByCollegesPage';
		$data['trackingcountryId']=2;
		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($data);


		$this->load->view('shikshaHelp/broweByColleges',$data);
	}
	function privacyPolicy()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/privacyPolicy', $data);

	}
	function termCondition()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$data['canonicalURL'] = SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/termCondition';
		$this->load->view('shikshaHelp/termCondition',$data);
	}
	function help()
	{
		$this->init();
		$data['current_page_url'] = SHIKSHA_FAQ_HOME;
		$data['validateuser'] = $this->userStatus;
                $data['searchEnable'] = true;
        $this->load->view('shikshaHelp/help', $data);
	}
	function kumkum()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		//below code used for beacon tracking
        $data['trackingpageIdentifier'] = 'kumkumProfilePage';
        $data['trackingcountryId']=2;


        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);

		$this->load->view('shikshaHelp/kumkum', $data);
	}

    function showPopUp(){
		$this->load->view('shikshaHelp/popup');
    }
	function communityGuideline()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/communityGuideline',$data);
	}

	function enterprise1()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/enterprise1',$data);
	}

	function enterprise2()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/enterprise2',$data);
	}

	function enterprise3()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/enterprise3',$data);
	}
	function enterprise4()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/enterprise4',$data);
	}
	function privacyPolicyTVC()
	{
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/privacyPolicyTVC', $data);
	}

        function summonsNotices() {
		$this->init();
		$data['current_page_url'] = SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/summonsNotices';
		$data['validateuser'] = $this->userStatus;
                $data['searchEnable'] = true;

        //below code used for beacon tracking
		$data['trackingpageIdentifier'] = 'shikshaComplaintPage';
		$data['trackingcountryId']=2;


		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($data);

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_SummonsNotices');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('shikshaHelp/summonsNotices', $data);
        }

        function grievances() {
		$this->init();
		$data['current_page_url'] = SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/grievances';
		$data['validateuser'] = $this->userStatus;
        $data['searchEnable'] = true;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_Grievances');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('shikshaHelp/grievances', $data);
        }
	
	function campusRepGuideline()
	{		
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/campusRepGuideline',$data);
	}
	
	function earningGuidelines()
	{   
		header("Location:".CAMPUS_REP_DASHBOARD_URL,TRUE,301);
        exit;
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/earningGuidelines',$data);
		
	}
	
	function earningMentorGuidelines()
	{   
		header("Location:".CAMPUS_REP_DASHBOARD_URL,TRUE,301);
        exit;
		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/earningMentorGuidelines',$data);
		
	}

	function mba6StepsMarketingPage()
	{
                $this->init();
                $data['validateuser'] = $this->userStatus;
                $this->load->view('shikshaHelp/stepsMarketingPage',$data);	
	}


        function checkAkamaiHeaders(){
                echo "<pre>";
                echo "<strong>Akamai Headers:::</strong><br/>";
                echo "Is Mobile = ".$_SERVER["HTTP_X_MOBILE"]."<br/>";
                if(isset($_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS'])){
                        //echo "Device Characteristics are as following: ";
                        $tempArray = explode(";",$_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS']);
                        foreach($tempArray as $feature){
                                $featureSplit = explode("=",$feature);
                                if($featureSplit[0]){
                                        $keyName = $featureSplit[0];
                                        echo "$keyName = ".$featureSplit[1]."<br/>";
                                }
                        }
                        //var_dump($_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS']);
                }
                else{
                        echo "Device characteristics = NOT SET<br/>";
                }
                //var_dump($_SERVER);
                echo "</pre>";

                echo "<br/><br/>";
                echo "<pre>";
                echo "<strong>WURFL Capabilities:::</strong><br/>";
                if(isset($_COOKIE['ci_mobile_capbilities']) && $_COOKIE['ci_mobile_capbilities']!=""){
                        //var_dump(json_decode($_COOKIE['ci_mobile_capbilities'],true));
                        $wurflChar = json_decode($_COOKIE['ci_mobile_capbilities'],true);
                        foreach ($wurflChar as $key => $value){
                                echo "$key = ".$value."<br/>";
                        }
                }
                else{
                        global $ci_mobile_capbilities;
                        if(isset($ci_mobile_capbilities) && $ci_mobile_capbilities!=""){
                                //var_dump($ci_mobile_capbilities);
                                $wurflChar = $ci_mobile_capbilities;
                                foreach ($wurflChar as $key => $value){
                                        echo "$key = ".$value."<br/>";
                                }
                        }
                }
                echo "</pre>";

        }

    function cookiePolicy()
    {
    		$this->init();
		$data['validateuser'] = $this->userStatus;
		$this->load->view('shikshaHelp/cookiePolicy', $data);
    }
}
?>
