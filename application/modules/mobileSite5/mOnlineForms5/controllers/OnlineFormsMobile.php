<?php

class OnlineFormsMobile extends ShikshaMobileWebSite_Controller {
	
	private $userStatus;
	
	function init($library=array('Online_form_client'),$helper=array('url','shikshautility')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		$this->load->config('mcommon5/mobi_config');
		$this->load->helper('mcommon5/mobile_html5');
		$this->userStatus = $this->checkUserValidation();
		$this->load->library('Online/courseLevelManager');
		$this->load->model('Online/onlineparentmodel');
		$this->load->model('Online/OnlineModel');
	}

	//Function to show the Online forms Homepage when someone has clicked from the Menu
	function showOnlineFormsHomepage($department = 'Management'){
		$this->init(array('Online_form_client'),array('url','shikshautility_helper'));
		
        // set cookie if coupon referral available
        if(isset($_GET['q']) && $_GET['q'] !='')
        {
                setcookie("referral_Coupon", $_GET['q'], time() + 3600 * 24 * 30,'/',COOKIEDOMAIN);
                $_COOKIE['referral_Coupon'] = $_GET['q'];
        }

        Modules::run('common/Redirection/validateRedirection',array('pageName'=>'onlineForm','oldUrl'=>"college-admissions-online-mba-application-forms",'oldDomainName'=>array(SHIKSHA_MANAGEMENT_HOME),'newUrl'=>SHIKSHA_HOME.'/mba/resources/application-forms','redirectRule'=>301));        

		//load the required library
		$this->load->library('studentFormsDashBoard/StudentDashboardClient');
		$this->load->library('studentFormsDashBoard/dashboardconfig');
		$onlineFormEngineerPageUrl= SHIKSHA_HOME.'/engineering/resources/application-forms';
		$onlineFormHomePageOldUrl = SHIKSHA_HOME.'/college-admissions-online-application-forms';
		$onlineFormHomePageNewUrl = SHIKSHA_HOME.'/mba/resources/application-forms';
		
		// Get the Institutes which needs to be displayed here
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
		global $onlineFormsDepartments;
		$displayData['onlineFormsDepartments'] = $onlineFormsDepartments;
		$this->courselevelmanager->setNewLevel($onlineFormsDepartments[$department]['level']);
		
		$onlineClient = new Online_form_client();

		//In case we are redirected from a Listing detail page, check if the user is logged.
		// If he is logged, redirect to the form page
		// If he is not, show the registration layer and the redirect to the form page

		$displayData['showRegistrationLayer'] = 'false';
		if(isset($_COOKIE['onlineCourseId']) && $_COOKIE['onlineCourseId']!=""){
		    if($displayData['userId']>0){
			  $onlineCourseId = $_COOKIE['onlineCourseId'];
			  setcookie("onlineCourseId", "", time() - 3600,'/',COOKIEDOMAIN);
			  $newLocation = SHIKSHA_HOME.'/Online/OnlineForms/showOnlineForms/'.$onlineCourseId;
				if( (strpos($newLocation, "http") === false) || (strpos($newLocation, "http") != 0) || (strpos($newLocation, SHIKSHA_HOME) === 0) || (strpos($newLocation,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($newLocation,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($newLocation,ENTERPRISE_HOME) === 0) ){
					header('LOCATION:'.$newLocation);
				}
				else{
				    header("Location: ".SHIKSHA_HOME,TRUE,301);
				}
			  exit;
		    }
		    else{	//Show the Login layer
			  $displayData['showRegistrationLayer'] = 'true';
			  $displayData['onlineCourseId'] = $_COOKIE['onlineCourseId'];
			  setcookie("onlineCourseId", "", time() - 3600,'/',COOKIEDOMAIN);
		    }
		}
		
		if($department=='Management'){
			$displayData['current_page_url'] = $onlineFormHomePageNewUrl;
		}
		if($department=='Engineering'){
			$displayData['current_page_url'] = $onlineFormEngineerPageUrl;
		}

		if($onlineFormHomePageOldUrl==$_SERVER['SCRIPT_URI'] && REDIRECT_URL=='live'){
			header("Location: $onlineFormHomePageNewUrl",TRUE,301);
			exit();
		}
		
		$displayData['department'] = $department;
		$showExternalForms = 'true';
		$of_institute_ids_array = json_decode($onlineClient->getInstitutesForOnlineHomepage($appId,$showExternalForms,array(),$department),true);

	        $of_institute_ids_array = array_merge(array_diff($of_institute_ids_array, array("28397")));

		// api return each and every details for a list of institute
		if(!empty($of_institute_ids_array) && is_array($of_institute_ids_array)){
		       //TO DO :: Uncomment below line
		      //$displayData['instituteList'] = $this->studentdashboardclient->renderInstituteListWithDetails($of_institute_ids_array);
		      $displayData['instituteList'] = $this->studentdashboardclient->renderInstituteListWithDetails($of_institute_ids_array, $department);
		      //var_dump($displayData['instituteList']);
		      $displayData['institute_features'] = json_decode($this->studentdashboardclient->returnOfInstitutesOfferandOtherDetails($of_institute_ids_array, $department),true);
		      //var_dump($displayData['institute_features']);
		      $displayData['config_array'] = DashboardConfig::$institutes_autorization_details_array;
		      $PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', $of_institute_ids_array);
		      $displayData['config_array'] += $PBTSeoData;
		}
        $displayData['of_institute_ids_array'] = $of_institute_ids_array;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_OnlineForm','entity_id'=>$department);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//$displayData['instituteTitlesList'] = $this->getInstituteTitles($department);
		$displayData['boomr_pageid'] = 'Online_form_homepage';

		//below code used for beacon tracking purpose
		$onlineFormUtilityLib = $this->load->library("Online/OnlineFormUtilityLib");
		$displayData['beaconTrackData'] = $onlineFormUtilityLib->prepareBeaconTrackData('onlineFormDashboard',$department);

		//below line is used for conversion tracking purpose
		$displayData['trackingPageKeyId']=359;
		$this->load->view('showOnlineHomepage',$displayData);

	}

	function redirect301($department = 'mba'){
		$url = SHIKSHA_HOME.'/'.$department.'/resources/application-forms'; // new url
		header("Location: $url",TRUE,301);
		exit;
	}


	function filterHomepageInstitutes($department = 'Management'){
		$this->init(array('Online_form_client'),array('url','shikshautility_helper'));
		
		//load the required library
		$this->load->library('studentFormsDashBoard/StudentDashboardClient');
		$this->load->library('studentFormsDashBoard/dashboardconfig');
		$onlineFormEngineerPageUrl= SHIKSHA_HOME.'/college-admissions-engineering-online-application-forms';
		$onlineFormHomePageOldUrl = SHIKSHA_HOME.'/college-admissions-online-application-forms';
		$onlineFormHomePageNewUrl = SHIKSHA_HOME.'/mba/resources/application-forms';
		
		// Get the Institutes which needs to be displayed here
		$appId = 12;
		$displayData = array();  
		           
		$displayData['course_pages_tabselected'] = 'ApplyOnline';
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
		$onlineClient = new Online_form_client();


		$filter = (isset($_POST['filter']))?$_POST['filter']:'All';
		$search = (isset($_POST['searchString']))?$_POST['searchString']:'';
		$instId = (isset($_POST['instId']))?$_POST['instId']:'';
		if($filter!='search'){
			$showExternalForms = 'true';
			$filterArray = array('filter'=>$filter, 'search'=>$search);
			$of_institute_ids_array = json_decode($onlineClient->getInstitutesForOnlineHomepage($appId,$showExternalForms,$filterArray,$department),true);
		}
		

		if(!empty($of_institute_ids_array) && is_array($of_institute_ids_array)){
		      $displayData['instituteList'] = $this->studentdashboardclient->renderInstituteListWithDetails($of_institute_ids_array, $department);		  
		      $displayData['institute_features'] = json_decode($this->studentdashboardclient->returnOfInstitutesOfferandOtherDetails($of_institute_ids_array, $department),true);
      		      $displayData['config_array'] = DashboardConfig::$institutes_autorization_details_array;
      		      $PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', $of_institute_ids_array);
      		      $displayData['config_array'] += $PBTSeoData;
      		}
                $displayData['of_institute_ids_array'] = $of_institute_ids_array;
		
		$displayData['instituteTitlesList'] = $this->getInstituteTitles($department);

		if(empty($of_institute_ids_array) || !is_array($of_institute_ids_array)){
			echo "<div style='font-size:19px;margin:20px;text-align:center;'>No matching forms found!</div>";
		}
		else{
 		        echo $this->load->view('showInstituteList',$displayData);
		}

	}

	function emailResults(){

				$fromWhere = (isset($_POST['fromWhere']))? $this->input->post('fromWhere') :'';

				if(ENVIRONMENT !='production' && $fromWhere == 'pwa'){  // used for pwa request

					switch (ENVIRONMENT) {
		                case 'development':
		                    $requestHeader = "http://localshiksha.com:3022";
		                    break;

		                case 'test1':
		                    $requestHeader = "https://testpwa.shiksha.com";
		                    break;

		                case 'production':
		                    $requestHeader = SHIKSHA_HOME;
		                    break;
		            }

					header("Access-Control-Allow-Origin: ".$requestHeader);
		            header("Access-Control-Allow-Credentials: true");
		            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
		            header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
		            header("Content-Type: application/json; charset=utf-8");	
				}
				
                $this->init(array('Online_form_client'),array('url','shikshautility_helper'));
                $signedInUser = $this->userStatus;
                $courseId = (isset($_POST['courseId']))?$this->input->post('courseId'):0;
                $instituteName = (isset($_POST['instituteName']))?$this->input->post('instituteName'):'';
                $isInternal = (isset($_POST['isInternal']))?$this->input->post('isInternal'):'true';
                if($courseId>0 && is_array($signedInUser)){
                        $cookieStr = $signedInUser[0]['cookiestr'];
                        $splitArray = explode('|',$cookieStr);
                        $email = $splitArray[0];
						$this->sendOnlineLinkEmail($courseId,$instituteName,$isInternal,$email);
                        echo 'Successful';
                }else{
                        echo 'Unsuccessful';
                }

	}

	function sendOnlineLinkEmail($courseId,$instituteName,$isInternal,$email){
                $this->load->library('mailerClient');
		$this->load->library('Alerts_client');
                $MailerClient = new MailerClient();
                $mail_client = new Alerts_client();
		if($isInternal=='true'){
			$urlOfLandingPage = SHIKSHA_HOME."/Online/OnlineForms/showOnlineForms/".$courseId.'?tracking_keyid=1111';
		}
		else{
			$urlOfLandingPage = SHIKSHA_HOME."/Online/OnlineFormConversionTracking/send/".$courseId.'/1111';
		}
                $link = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage);

                $subject = "Application for ".base64_decode($instituteName);
                $content = "<p>You have started your application for ".base64_decode($instituteName)." on Shiksha.com</p>";
                $content .= "<p>Please complete your application by clicking on the below link from a computer:</p>";
                $content .= "<p><a href='$link'>Complete Now</a></p>";
                $content .= "<p>Get free <img src='".SHIKSHA_HOME."/public/images/paytm.png' alt=''>
                				worth <strong>Rs 100</strong> for every successful <strong>application submission</strong> on Shiksha. <strong>Paytm Cashback </strong>will be posted on mobile number used for filling form within 15 days from submission date.
                				</p>";
                //$mail_client->externalQueueAdd("12","noreply@shiksha.com",$email,$subject,$content,$contentType="html");		
		Modules::run('systemMailer/SystemMailer/onlineFormMailers', $email, "form_CAF_link", $subject, $content);
	}


	function applyNowButton($courseId,$trackingPageKeyId='',$pageName)
	{
		$this->load->model("Online/onlineparentmodel");
		$this->load->model('Online/OnlineModel');
		$res = $this->OnlineModel->checkIfOnlineFormExists($courseId);
		if($res['hasOnlineForm'] == 1){
			$displayData = $res;
			$displayData['courseIdClicked'] = $courseId;
			$displayData['trackingPageKeyId'] = $trackingPageKeyId;
			if($pageName != 'COMPARE_PAGE_BOTTOM')
				$this->load->view('applyNowButton',$displayData);
			if($pageName == 'COMPARE_PAGE_BOTTOM')
				return $displayData;
		}
	}
}
