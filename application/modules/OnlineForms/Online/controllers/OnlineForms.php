<?php 

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/07/16 09:29:49 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.

$Id: MsgBoard.php,v 1.205 2010/07/16 09:29:49 ankurg Exp $:

*/
class OnlineForms extends MX_Controller {

	var $userStatus = '';
	
	function init($library=array('Online_form_client','category_list_client','register_client','alerts_client','ajax','LDB_Client'),$helper=array('url','image','shikshautility','validate','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
		$this->load->library('common/Personalizer');
		$this->load->library('Online/courseLevelManager');
		$this->load->model('onlineparentmodel');
        $this->load->model('OnlineModel');
        $this->load->library('nationalCourse/CourseDetailLib');
        $this->load->helper(array('shikshautility_helper','image','listingCommon/listingcommon'));
		
		
	}

	//Function to show the Online forms Homepage when someone has clicked from the Menu
	function showOnlineFormsHomepage($department='Management'){
		
		$this->init(array('Online_form_client'),array('url','shikshautility_helper'));
		
		Modules::run('common/Redirection/validateRedirection',array('pageName'=>'onlineForm','oldUrl'=>"college-admissions-online-mba-application-forms",'oldDomainName'=>array(SHIKSHA_MANAGEMENT_HOME),'newUrl'=>SHIKSHA_HOME.'/mba/resources/application-forms','redirectRule'=>301));

		// set cookie if coupon referral available
		/*if(isset($_GET['q']) && $_GET['q'] !='')
		{
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			$getOwnCode = '';
			if(isset($userId) && $userId>0)
			{
				$couponLib = $this->load->library('common/couponLib');
				$getOwnCode = $couponLib->getUserCoupon($userId);	
			}
			if($getOwnCode =='')
			{
				setcookie("referral_Coupon", $_GET['q'], time() + 3600 * 24 * 30,'/',COOKIEDOMAIN);
				$_COOKIE['referral_Coupon'] = $_GET['q'];	
			}else{
				setcookie("referral_Coupon", "", time() - 3600 * 24 * 30,'/',COOKIEDOMAIN);
				$_COOKIE['referral_Coupon'] = "";	
			}
		}*/
		
		//load the required library
		$this->load->library('StudentDashboardClient');
		$this->load->library('dashboardconfig');
		$onlineFormEngineerPageUrl= SHIKSHA_HOME.'/engineering/resources/application-forms';
		$onlineFormHomePageOldUrl = SHIKSHA_HOME.'/college-admissions-online-application-forms';
		$onlineFormHomePageNewUrl = SHIKSHA_HOME.'/mba/resources/application-forms';
		
		// Get the Institutes which needs to be displayed here
		$appId = 12;
		$displayData = array();  
		$displayData['CourseDetailLib'] = new CourseDetailLib;

		if($department == 'Management'){   
			               
			$this->personalizer->triggerPersonalization(23,'onlineforms');
			$displayData['subcat_id_course_page'] = 23;
			$displayData['tab_to_hide'] = 'Engineering';
		}
		
		if($department == 'Engineering'){
			
			$this->personalizer->triggerPersonalization(2,'onlineforms');
			$displayData['subcat_id_course_page'] = 56;
			$displayData['tab_to_hide'] = 'Management';
		}
			
		$displayData['course_pages_tabselected'] = 'ApplyOnline';
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
		global $onlineFormsDepartments;
		$this->courselevelmanager->setNewLevel($onlineFormsDepartments[$department]['level']);
		
		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_OnlineForm','entity_id'=>$department);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//Check if the user has visited Online forms earlier and tried to fill a form
		//If yes, we will take him directly to the Student dashboard
		$onlineClient = new Online_form_client();
		if( !(isset($_COOKIE['onlineCourseId']) && $_COOKIE['onlineCourseId']!="") && $displayData['userId']>0){
			 //$hasUserVisited = $onlineClient->checkIfUserCameOnOnlineForms($displayData['userId']);

			$location = '/studentFormsDashBoard/StudentDashBoard/index';
			if( (strpos($location, "http") === false) || (strpos($location, "http") != 0) || (strpos($location, SHIKSHA_HOME) === 0) || (strpos($location,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($location,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($location,ENTERPRISE_HOME) === 0) ){
				header('LOCATION:'.$location);
			}
			else{
			    header("Location: ".SHIKSHA_HOME,TRUE,301);
			}
			exit;
		}//In case we are redirected from a Listing detail page, check if the user is logged.
		// If he is logged, redirect to the form page
		// If he is not, show the registration layer and the redirect to the form page
		$displayData['showRegistrationLayer'] = 'false';
		if(isset($_COOKIE['onlineCourseId']) && $_COOKIE['onlineCourseId']!=""){
		    if($displayData['userId']>0){
			  $onlineCourseId = $_COOKIE['onlineCourseId'];
			  setcookie("onlineCourseId", "", time() - 3600,'/',COOKIEDOMAIN);
			  $newLocation = SHIKSHA_HOME.'/Online/OnlineForms/showOnlineForms/'.$onlineCourseId;
			  header('LOCATION:'.$newLocation);
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

        /*if($onlineFormEngineerPageUrl==$_SERVER['SCRIPT_URI']){
                $displayData['current_page_url'] = $onlineFormEngineerPageUrl;
        }
        if($onlineFormHomePageNewUrl==$_SERVER['SCRIPT_URI']){
                $displayData['current_page_url'] = $onlineFormHomePageNewUrl;
        }*/

		if($onlineFormHomePageOldUrl==$_SERVER['SCRIPT_URI'] && REDIRECT_URL=='live'){
			header("Location: $onlineFormHomePageNewUrl",TRUE,301);
			exit();
		}
		
		$displayData['department'] = $department;
		$showExternalForms = 'true';
		
		$of_institute_ids_array = json_decode($onlineClient->getInstitutesForOnlineHomepage($appId,$showExternalForms,array(),$department),true);
		
	    $of_institute_ids_array = array_merge(array_diff($of_institute_ids_array, array("28397")));
		// api return each and every details for a list of institute
        $displayData['of_institute_ids_array'] = $of_institute_ids_array;
        /* code addded for pagiantion */
		$displayData['page_type_for_identification'] = "ONLINE_HOME_PAGE";
		$displayData['total_number_pages'] = 0;
		$displayData['count_result'] = count($of_institute_ids_array);
		if($displayData['count_result']%INSTITUTE_PER_PAGE == 0) {
			$displayData['total_number_pages'] = (int)($displayData['count_result']/INSTITUTE_PER_PAGE);
		} else {
			$displayData['total_number_pages'] = (int)($displayData['count_result']/INSTITUTE_PER_PAGE)+1;
		}
		foreach ($displayData['instituteList'] as $inst_id1=>$instituteList_object1):
		$inst_id1_arry[] = $inst_id1;
		endforeach;
		$displayData['inst_id1_arry'] = $inst_id1_arry;
		$current_page = strip_tags($_REQUEST['start']);
		$current_page = intval($current_page,10);
		if(!empty($current_page)) {
			$current_page_new = ($current_page/INSTITUTE_PER_PAGE)+1;
		} else {
			$current_page_new = 1;
		}
		
		$paginationHTML = doPagination($displayData['count_result'],SHIKSHA_HOME."/".$onlineFormsDepartments[$department]['url']."?start=@start@&num=@count@",$current_page,INSTITUTE_PER_PAGE,$displayData['total_number_pages']);
		$displayData['paginationHTML'] = $paginationHTML;
		$offset = ($current_page_new-1)*INSTITUTE_PER_PAGE;
		$return_array = $this->studentdashboardclient->handlePagination($of_institute_ids_array,$displayData['total_number_pages'],$current_page_new,INSTITUTE_PER_PAGE, $department) ;
		if(!empty($return_array) && count($return_array)>0) {
			$displayData['instituteList'] = $return_array['instituteList'];
			$displayData['institute_features'] = $return_array['institute_features'];
		}
		/* code ended for pagination */

		// $displayData['instituteTitlesList'] = $this->getInstituteTitles($department);


	
		//below code used for beacon tracking
		$onlineFormUtilityLib = $this->load->library("Online/OnlineFormUtilityLib");
		$displayData['beaconTrackData'] = $onlineFormUtilityLib->prepareBeaconTrackData('onlineFormDashboard',$department);
		//below line is used for conversion tracking purpose
		$displayData['trackingPageKeyId']=167;
		$displayData['regTrackingPageKeyId']=449;
		$displayData['bottomregTrackingPageKeyId']=450;

		//preparing breadcrumbs
		$breadcrumbOptions = array('generatorType' 	=> 'OnlineFormsPage',
									'options' 		=> array('department'	=>	$department));
		$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
		$displayData['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();

		$displayData['config_array'] = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', $of_institute_ids_array,1);
		$displayData['config_array'] += $PBTSeoData;

		$this->load->view('Online/showOnlineHomepage',$displayData);

	}

	function redirect301($department = 'mba'){
		$url = SHIKSHA_HOME.'/'.$department.'/resources/application-forms'; // new url
		header("Location: $url",TRUE,301);
		exit;
	}

	//Function to show the Registration page in case the user is not logged in
	function showRegistrationPage($courseId=0,$trackingPageKeyId='',$isOtherCourse=0, $isValidResponseUser='no'){
		$this->init(array('Online_form_client'),array('url'));
		$this->load->library("Online/OnlineFormUtilityLib");
		$onlineFormUtilityLib = new OnlineFormUtilityLib();
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$validresponseCategoryFilter = array('yes', 'no');
		if(empty($isValidResponseUser) || !in_array($isValidResponseUser, $validresponseCategoryFilter)){
			$tempData = $this->_isValidResponseUser($displayData['userId'], $courseId);
			$isValidResponseUser = $tempData['isValidResponseUser'];
		}
		$displayData['isValidResponseUser'] = $isValidResponseUser;

		if($courseId>0) {
            $courseId = intval($courseId,10);
            $res = $this->online_form_client->formHasExpired($courseId);
			$res = json_decode($res,true);
			if($res[$courseId] ==1) {
				$displayData['form_is_expired'] = "expired";
			} else {
                		$displayData['form_is_expired'] = "notexpired";
             		}
			$displayData['urlToRedirect'] = '/Online/OnlineForms/showOnlineForms/'.$courseId;
			$displayData['courseId'] = $courseId;
			$ResultOfDetails = $this->online_form_client->getOnlineInstituteInfo($appId,$courseId,$isOtherCourse);
			$displayData['PBTSEOData'] = $this->getPBTSEOData($ResultOfDetails, $courseId);
			if( !isset($ResultOfDetails[0]['instituteInfo']) || count($ResultOfDetails[0]['instituteInfo'])<=0 )
			{
				//If the Institute is not found, redirect the user to Online Homepage
	           $url='/mba/resources/application-forms';
	           header("Location: $url",TRUE,301);
	           exit;
			}
			foreach($ResultOfDetails[0]['instituteInfo'] as $key=>$value){
                        	if($value['courseId']==$courseId){
                                	$displayData['courseCode'] 	= $value['courseCode'];
                                        $displayData['courseName'] 	= $value['courseTitle'];
                                        $department 			= $value['departmentName'];
                                        $displayData['departmentType']  = $department;
                                        $displayData['institute_id']    = $value['institute_id'];
                               }
                        }
			if(isset($ResultOfDetails) && is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseTitle']) ){
				$displayData['instituteInfo'] = $ResultOfDetails;
				/*if(is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseCode'])){
					$displayData['courseCode'] = $ResultOfDetails[0]['instituteInfo'][0]['courseCode'];
					$displayData['courseName'] = $ResultOfDetails[0]['instituteInfo'][0]['courseTitle'];
				}*/
			}

			$of_institute_ids_array = array($ResultOfDetails[0]['instituteInfo'][0]['institute_id']);
			//$displayData['institute_id'] = $ResultOfDetails[0]['instituteInfo'][0]['institute_id'];
			//$department = $ResultOfDetails[0]['instituteInfo'][0]['departmentName'];
			//$displayData['departmentType'] = $department;
			
			if(!empty($of_institute_ids_array) && is_array($of_institute_ids_array)){
				//load the required library
				$this->load->library('StudentDashboardClient');
				$this->load->library('dashboardconfig');
				//set the data
				$displayData['instituteList'] = $this->studentdashboardclient->renderInstituteListWithDetails($of_institute_ids_array, $department);
				$displayData['institute_features'] = json_decode($this->studentdashboardclient->returnOfInstitutesOfferandOtherDetails($of_institute_ids_array, $department),true);

				//get dashboard config for online form
			    $displayData['config_array'] = $onlineFormUtilityLib->getOnlineFormAllCourses();

			    //$displayData['config_array'] = DashboardConfig::$institutes_autorization_details_array;
			}

			$this->load->builder('CourseBuilder','nationalCourse');
            $courseBuilder = new CourseBuilder;
            $courseRepository = $courseBuilder->getCourseRepository();
            $displayData['courseObject'] = $courseRepository->find($courseId,array('eligibility'));
			
				//$dominantDesiredCourseData = $onlineFormUtilityLib->getDominantDesiredCourseForClientCourses(array($courseId));

			/*foreach ($dominantDesiredCourseData as $key => $value) {
			    $dominantDesiredCourseData[$key]['name'] = $coursesListData[$key];
			}*/

			// need to verify by QA THIS_CODE_NEEDS_TO_BE_REMOVE_AFTER_RECAT_GOES_LIVE
			/*$displayData['instituteCoursesLPR']             = $dominantDesiredCourseData;
			$displayData['defaultCourse']                   = $dominantDesiredCourseData[$courseId]['desiredCourse'];
			$displayData['defaultCategory']                 = $dominantDesiredCourseData[$courseId]['categoryId'];*/

			$displayData['defaultCourseId']                 = $courseId;
			$displayData['courseIdSelected']                = $courseId;
		}else{
			$displayData['urlToRedirect'] = '/Online/OnlineForms/showOnlineForms';
		}
			$googleRemarketingParams = array(
					"categoryId" 	=> $dominantDesiredCourseData[$courseId]['categoryId'],
					"subcategoryId" => $dominantDesiredCourseData[$courseId]['categoryId'] == 2 ? 56 : 23,
					"countryId" => 2
				); 
			$displayData['googleRemarketingParams'] = $googleRemarketingParams;
		
            //Code added by Ankur for GA Custom variable tracking
            $displayData['subcatNameForGATracking'] = "Full Time MBA/PGDM";
            $displayData['pageTypeForGATracking'] = "ONLINE_FORM_REGISTRATION_PAGE";

            //below code used for beacon tracking
			$onlineFormUtilityLib = 	$this->load->library("Online/OnlineFormUtilityLib");
			$displayData['beaconTrackData'] = $onlineFormUtilityLib->prepareBeaconTrackDataForCourse('onlineApplicationForm',$courseId,$displayData['courseObject']);
			//_p($displayData['beaconTrackData']);die;

			$this->_getCouponRelatedData($displayData,$displayData['userId']);

			//below line is used for conversion tracking purpose
			if($trackingPageKeyId!='')
			{
				$displayData['trackingPageKeyId']=$trackingPageKeyId;
			}
			elseif( ! empty($_GET['tracking_keyid']))
			{
				$displayData['trackingPageKeyId']=$this->input->get('tracking_keyid');	
			}

			//preparing breadcrumbs
			$breadcrumbOptions = array('generatorType' 	=> 'ApplicationFormsPage',
										'options' 		=> array('instituteId'	=>	$ResultOfDetails[0]['instituteInfo'][0]['institute_id']));
			$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
			$displayData['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();

			$courseLevel = "PG";
			if(!empty($displayData['courseObject']))
			{
				$entry_course = $displayData['courseObject']->getCourseTypeInformation()['entry_course'];
				if(!empty($entry_course))
				{
					$courseLevel = $entry_course->getCourseLevel()->getName();
				}
			}
			
			$this->courselevelmanager->setNewLevel($courseLevel);
            if($this->isExternalForm($displayData['courseId']) || $isOtherCourse==1) {
                $this->load->view('Online/externalForm',$displayData);
            }else {
                $this->load->view('Online/showLoginFormWithHeader',$displayData);
            }
	}	

	/*
	 * Check's if user is valid to make response on a course or not.
	 * @Params: [int] $userId
	 *			[int] $courseId
	 * @return: [array] => [bool] key='showregistrationForm'
	 *						[string] key='isValidResponseUser'
	 */
	private function _isValidResponseUser($userId=0, $courseId=0){
		$data = array();
		if($userId && $courseId){
	        $isValidResponseUser = modules::run('registration/RegistrationForms/isValidUser', $courseId, $userId);
	        if(!$isValidResponseUser){
	        	$data['showregistrationForm'] = true;
	        	$data['isValidResponseUser'] = 'no';
			}else{
				$data['showregistrationForm'] = false;
				$data['isValidResponseUser'] = 'yes';
			}       

        }else{
	        	$data['showregistrationForm'] = true;
	        	$data['isValidResponseUser'] = 'no';
        }

        return $data;
	}

	private function getPBTSEOData($instInfo, $courseId){
		//$courseId = $instInfo[0]['instituteInfo'][0]['courseId'];
		$allFormData = $this->checkIfCoursesHaveOnlineForm(array($courseId));
		if(isset($allFormData[$courseId]['mainCourseId']) && $allFormData[$courseId]['mainCourseId'] > 0){
			$mainCourseId = $allFormData[$courseId]['mainCourseId'];
		}else{
			$mainCourseId = $courseId;
		}
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', array(), array($mainCourseId));
		$PBTSeoData= $PBTSeoData[$mainCourseId];

		if(empty($PBTSeoData)){
			$this->load->library('dashboardconfig');
			$displayData['config_array'] = DashboardConfig::$institutes_autorization_details_array;
			$displayData['config_array'] = $displayData['config_array'][$courseId];
			$seoDetails = array(
				'seoTitle'=>$displayData['config_array']['seo_title'], 
				'seoDesc'=>$displayData['config_array']['seo_description'],
				'canonicalUrl' => SHIKSHA_HOME.$displayData['config_array']['seo_url']
				);
		}else{
			foreach ($instInfo[0]['instituteInfo'] as $key => $value) {
				if($value['courseId']==$courseId){
					$courseName = $value['courseTitle'];
					$instName   = $value['institute_name'];
				}
			}
			$partialUrlString = seo_url($courseName);
			$seoDetails = array(
				'seoTitle'=>str_replace(array('<collegeName>','<courseName>'), array($instName,$courseName), $PBTSeoData['seo_title']),
				'seoDesc'=>str_replace(array('<collegeName>','<courseName>'), array($instName,$courseName), $PBTSeoData['seo_description']),
				'canonicalUrl' => str_replace('<courseName>',$partialUrlString,SHIKSHA_HOME.$PBTSeoData['seo_url'])
				);
		}
		
        $seoDetails['canonicalUrl'] .= $trackingPageKeyId;
		return $seoDetails;
	}

	function redirectApplicationForm($courseId = 0,$deprtment =''){

		if(!preg_match('/^\d+$/',$courseId) || $courseId <= 0){
			show_404();die();
		}

		if(!empty($_GET['tracking_keyid']) && $_GET['tracking_keyid']>0 && preg_match('/^\d+$/',$_GET['tracking_keyid'])){
        	$trackingPageKeyId=$this->input->get('tracking_keyid');
        }

		$this->load->library("Online/OnlineFormUtilityLib");
		$onlineFormUtilityLib = new OnlineFormUtilityLib();
		if($courseId==129908){
			$seoUrl = $onlineFormUtilityLib->createSeoUrl(150856,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
		}else if($courseId==134345){
			$seoUrl = $onlineFormUtilityLib->createSeoUrl(134338,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
        }else if($courseId==1354 || $courseId==159378){
        	$seoUrl = $onlineFormUtilityLib->createSeoUrl(159341,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
        }else if($courseId==126875){
        	$seoUrl = $onlineFormUtilityLib->createSeoUrl(124261,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
        }else if($courseId==191289){
        	$seoUrl = $onlineFormUtilityLib->createSeoUrl(176990,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
        }

        $seoUrl = $onlineFormUtilityLib->createSeoUrl($courseId,$trackingPageKeyId, $deprtment);
        header("Location: $seoUrl",TRUE,301);
		exit();
	}

	//Main function to display the Online forms to the user for this Course.
	// This will display any page of the form including the Base pages, custom pages, payment or Dashboard
	function showOnlineForms($courseId=0,$edit=0,$pageNumber=0,$action=''){
		$this->init();

		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		if(!preg_match('/^\d+$/',$courseId) || $courseId <= 0 && $edit ==0){
			show_404();die();
		}
		$flagCourseIsZero =0;
		if($courseId ==0){
			$flagCourseIsZero=1;
		}
		//below line  is used for conversion tracking purpose
        if(!empty($_GET['tracking_keyid']) && $_GET['tracking_keyid']>0 && preg_match('/^\d+$/',$_GET['tracking_keyid'])){
        	$trackingPageKeyId = $this->input->get('tracking_keyid');
        }

		$this->load->library("Online/OnlineFormUtilityLib");
		$onlineFormUtilityLib = new OnlineFormUtilityLib();

        $courseChk = $onlineFormUtilityLib->isCourseIdValid($courseId);

        if(!$courseChk && $action != 'editProfile'){
        	show_404();
        	die();
        }
        if($displayData['validateuser'] == 'false' && $action == 'editProfile'){
        	header('Location: /mba/resources/application-forms');
	        exit();
        }

		if($courseId==129908){
			$seoUrl = $onlineFormUtilityLib->createSeoUrl(150856,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
		}else if($courseId==134345){
			$seoUrl = $onlineFormUtilityLib->createSeoUrl(134338,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
        }else if($courseId==1354 || $courseId==159378){
        	$seoUrl = $onlineFormUtilityLib->createSeoUrl(159341,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
        }else if($courseId==126875){
        	$seoUrl = $onlineFormUtilityLib->createSeoUrl(124261,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
        }else if($courseId==191289){
        	$seoUrl = $onlineFormUtilityLib->createSeoUrl(176990,$trackingPageKeyId);
	        header("Location: $seoUrl",TRUE,301);
			exit();
        }else{
        	if($courseId > 0){
        		if($edit == 0){
        			$onlineFormUtilityLib->redirectionRule($courseId,$trackingPageKeyId);		
        		}
        		
        	}
        }

		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['CourseDetailLib'] = new CourseDetailLib;

		$onlineClient = new Online_form_client();		
		$this->load->library('dashboardconfig');
		$this->load->library('structuredconfig');

		$onlineModel =  $this->load->model('OnlineModel');
		$newOnlineModel = $this->load->model('newOnlineModel');
		
		if($flagCourseIsZero !=1){
			$OF_Details = $onlineModel->checkIfOtherCourse($courseId);
		}
        if(empty($OF_Details)){
        	$isOtherCourse = 1;	
        }
        else{
        	$isOtherCourse=0;
        }
        if ($isOtherCourse ==1 && $flagCourseIsZero !=1)
        {
        	$OF_Details = $newOnlineModel->getAsOtherCourse($courseId);
        }
        
        /*Code to check to show registration form to the user */ 
		$userValidationData = $this->_isValidResponseUser($displayData['userId'], $courseId);

		$userAlreadyMadeResponseFlag = 0;
        
        if(isset($_COOKIE[$courseId."oaf"]) && $_COOKIE[$courseId."oaf"]==$displayData['userId']){
        	$userAlreadyMadeResponseFlag = 1;
        }
        if($displayData['userId']>0 && $userValidationData['isValidResponseUser']=='yes' && $userAlreadyMadeResponseFlag==0){
        	$_POST['listing_id'] = $courseId;
        	$_POST['action_type'] = 'Online_Application_Started';
        	$_POST['listing_type'] = 'course';
        	$tracking_key_id  = ($trackingPageKeyId > 0) ? $trackingPageKeyId : 167;
        	$_POST['tracking_keyid'] = $tracking_key_id;
        	Modules::run('response/Response/createResponse');
        	$_POST = array();
        }

		//In case the user is not logged or in complete user, take him to the Online form Homepage
		if(($displayData['userId'] == 0 || $userValidationData['isValidResponseUser']=='no') && $edit==0 && $userAlreadyMadeResponseFlag==0){
		    $this->showRegistrationPage($courseId,$trackingPageKeyId,$isOtherCourse, $userValidationData['isValidResponseUser']);

		}else{	//Else, show the user the pages of the form
			if($courseId>0){
				//$courseId = intval($courseId,10);
        	    if(empty($OF_Details)) {   //Form has expired and redirect him to Dashboard
                	header('Location: /studentFormsDashBoard/StudentDashBoard/index');
	                exit();
		    	}
        	}
            $appId = 12;
            // check if course is main or other
           
            $ResultOfDetails = $this->online_form_client->getOnlineInstituteInfo($appId,$courseId,$isOtherCourse);
            foreach ($ResultOfDetails[0]['instituteInfo'] as $key => $value ) {
            	if ($value['courseId'] == $courseId){
            		$ResultOfDetails[0]['instituteInfo'][0] = $value;
        	    }
        	}     //handling external forms
            if($flagCourseIsZero !=1 && $this->isExternalForm($courseId,$OF_Details)) {
                $this->showRegistrationPage($courseId,'',$isOtherCourse, $userValidationData['isValidResponseUser']);
                return;
            }

		    //To show the Online form, we will have to get the following things ==>

			if($edit == 1) {
				$canEditThisPage = false;
				$pagesUserHasFilled = $newOnlineModel->getPagesUserHasFilled($displayData['userId'],0,$courseId);

				if(is_array($pagesUserHasFilled) && is_array($pagesUserHasFilled[0]) && is_array($pagesUserHasFilled[0]['data'])) {
					$maxPageData = end($pagesUserHasFilled[0]['data']);
					$maxPageFilled = (int) $maxPageData['pageOrder'];

					if($pageNumber <= $maxPageFilled) {
						$canEditThisPage = true;
					}
					if(empty($pagesUserHasFilled[0]['data'])){
						$canEditThisPage =true;
					}
				}
				
				if(!$canEditThisPage && $courseId>0) {
					header('Location: /Online/OnlineForms/showOnlineForms/'.$courseId);
					exit();
				}
				
				if($courseId > 0) {
					$currentFormIsValid = FALSE;
					$currentFormData = $pagesUserHasFilled[0]['data'];
					if($currentFormData && is_array($currentFormData) && is_array($currentFormData[0])) {
						$currentFormStatus = $currentFormData[0]['status'];
						
						if($currentFormStatus == 'started' || $currentFormStatus == 'uncompleted' || $currentFormStatus == 'completed') {
							$currentFormIsValid = TRUE;
						}

						if($action == 'updateScore'){
							$currentFormIsValid = TRUE;
							if(in_array($currentFormStatus, array('started','uncompleted','completed'))) {
								$currentFormIsValid = FALSE;
							}
						}
					}
					
					if(!$currentFormIsValid) {
						header('Location: /studentFormsDashBoard/StudentDashBoard/index');
						exit();
					}
				}
			}
			
		    // Step 1. The FormId, Institute Name, course name, Institute logo Image, Form fee (if any), Institute display text (if any)
		    if($courseId>0){

				if(isset($ResultOfDetails) && is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseTitle']) ){
					$displayData['instituteInfo'] = $ResultOfDetails;
					if(is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseCode'])){ 
						$displayData['courseCode'] = $ResultOfDetails[0]['instituteInfo'][0]['courseCode'];
						$displayData['courseName'] = $ResultOfDetails[0]['instituteInfo'][0]['courseTitle'];
					}
				}
				else{	//IN case the Course info is not available, we will treat it as a Master form
					$courseId = 0;
				}
		    }

		    //In case of Edit, we will get the PageId from the pageNumber/pageOrder. This way we do not need to send the pageId in the Edit URL
		    
		    if($pageNumber>0){
			    $displayData['pageOrder'] = $pageNumber;
		    }

		    // Step 2. We will have to check which PAGE the user will have to fill. Whichever page info user has already filled, we will take him to the next page of that.
		    // If he has filled all the pages, we will take him to the Payment page
		    // If he has even paid us the money for that, we will take him to his Dashboard.
		    // If the courseId is set, it means he needs to fill the form for this course. Get PageName and template based on userId and courseId
		    // If the courseId is not set, it means he needs to fill his Master form.
		    if($edit==0 || $edit==''){	//If this is new form. Get the User info from tuser* tables
			    $pageResult = $onlineClient->getInfoForPageToBeDisplayed($appId,$displayData['userId'],$courseId);
			   
			    if(is_array($pageResult)){
				  if(isset($pageResult[0]['pageData'][0]) && ($pageResult[0]['pageData'][0]=='DASHBOARD' || $pageResult[0]['pageData'][1]=='DASHBOARD') ){
					//Redirect the user to the Dashboard
					//echo "Redirecting to dashboard";
					header('Location: /studentFormsDashBoard/StudentDashBoard/index');
					exit;
				  }
				  else if(isset($pageResult[0]['pageData'][0]) && ($pageResult[0]['pageData'][0]=='PAYMENT' || $pageResult[0]['pageData'][1]=='PAYMENT')){
					//Redirect the user to the PAYMENT page
					//$this->showPaymentPage($courseId);
					
					if($action == 'editForm') {
						header('Location: /Online/OnlineForms/showOnlineForms/'.$courseId.'/1/1');
						exit;
					}
					else {
						$displayData['showPaymentPage'] = 'true';
						$displayData['pageOrder'] = 5;
                        $this->load->model('onlineparentmodel');
                        $this->load->model('OnlineModel');
                        $displayData['amount'] = $this->OnlineModel->getFeesForInstitute($courseId,$displayData['userId'],$ResultOfDetails[0]['instituteInfo'][0]['actualFees']);                                                }
				  }
				  else if(isset($pageResult[0]['pageData'][0]['pageOrder']) && $pageResult[0]['pageData'][0]['pageOrder']!=''){
					$displayData['formId'] = $pageResult[0]['pageData'][0]['formId'];
					$displayData['pageId'] = $pageResult[0]['pageData'][0]['pageId'];
					$displayData['templatePath'] = $pageResult[0]['pageData'][0]['templatePath'];
					$displayData['pageOrder'] = $pageResult[0]['pageData'][0]['pageOrder'];
					$displayData['pageType'] = $pageResult[0]['pageData'][0]['pageType'];
				  }
			    }
		    }
		    else{
		    	if ($flagCourseIsZero !=1){
		    		$pagesDetails = $pagesUserHasFilled[0]['data'];
		    		foreach ($pagesDetails as $pageData){
		    			if ($pageData['pageOrder'] == $pageNumber){
		    				$pageId = $pageData['pageId'];
		    			}
		    		}
		    	}
		    	else{
		    		$pageId = $pageNumber;
		    	}	

			    $displayData['pageId'] = $pageId;
			    $pageListData = $newOnlineModel->getTemplatePathAndType($pageId);

			    $displayData['templatePath'] = $pageListData['templatePath'];
			    $displayData['onlineFormId'] = $pagesUserHasFilled[0]['data'][0]['onlineFormId'];
			    $displayData['pageArray'] = $pagesUserHasFilled;
			    $displayData['pageType'] = $pageListData['pageType'];
		    }
			
			
			//_p($currentFormData);
			//if($courseId && $this->onlinemodel->checkThirdPageProfile($displayData['userId'],$courseId) && $this->courselevelmanager->getCurrentLevel() == "PG" && $displayData['pageId'] > 3){
				//error_reporting(E_ALL);
				//header("Location: /Online/OnlineForms/showOnlineForms/".$courseId."/1/3/");
				//exit();
			//	}

		    // Step 3. If the user has started filling the form for the first time, i.e. he is on the first page, then store an empty row in FormSubmit tables with Status = started
		    // This will provide the OnlineFormId for the form 
		    if(isset($displayData['pageOrder']) && $edit==0){
			  $displayData['onlineFormId'] = $onlineClient->setUserStartingForm($appId,$displayData['userId'],$courseId,$displayData['formId']);
		    }


		    // Step 4. In case of Edit, we will also get the data for the page and display it in the form. 
		    // In case of new form, we will get the user data from tuser* tables and display it
		    if($edit==0 || $edit==''){	//If this is new form. Get the User info from tuser* tables
			$ldbObj = new LDB_Client();
			$userId = $displayData['userId'];
			$userCompleteDetails = $ldbObj->sgetUserDetails($appId,$userId);
			$userData = json_decode($userCompleteDetails,true);

			//echo "<pre>"; var_dump($userData);
			if(is_array($userData)){

			      $userDetails = $userData[$userId];
			      $displayData['firstName'] = isset($userDetails['firstname'])?strtoupper($userDetails['firstname']):'';
			      $displayData['lastName'] = isset($userDetails['lastname'])?strtoupper($userDetails['lastname']):'';
			      $displayData['gender'] = isset($userDetails['gender'])?$userDetails['gender']:'';
			      $displayData['landline'] = isset($userDetails['landline'])?$userDetails['landline']:'';
			      $displayData['mobileISDCode'] = isset($userDetails['isdCode'])?$userDetails['isdCode']:'';
			      $displayData['mobileNumber'] = isset($userDetails['mobile'])?$userDetails['mobile']:'';
			      $displayData['email'] = isset($userDetails['email'])?strtoupper($userDetails['email']):'';
			      $displayData['altEmail'] = isset($userDetails['secondaryemail'])?strtoupper($userDetails['secondaryemail']):'';
			      
			      if(isset($userDetails['dateofbirth']) && $userDetails['dateofbirth']!='' && $userDetails['dateofbirth']!='0000-00-00'){
				   		$dob = strtotime($userDetails['dateofbirth']);
					    if($dob !== false){
						  $displayData['dateOfBirth'] = date("d/m/Y", strtotime($userDetails['dateofbirth']) );
						  //$month = date('m',$dob);
						  //$year = date('Y',$dob);
					    }
			      }

			      $displayData['city'] = isset($userDetails['city'])?$userDetails['city']:'';
			      $displayData['country'] = isset($userDetails['country'])?$userDetails['country']:'';
			      $displayData['locality'] = isset($userDetails['Locality'])?strtoupper($userDetails['Locality']):'';
					//_p($userDetails['EducationData']);
			      if(is_array($userDetails['EducationData'])){
				    foreach ($userDetails['EducationData'] as $eduArray){
					  switch($eduArray['Level']){
						case '10': $displayData['class10ExaminationName'] = isset($eduArray['Name'])?strtoupper($eduArray['Name']):'';
							    $displayData['class10Percentage'] = isset($eduArray['Marks'])?$eduArray['Marks']:'';
							    $displayData['class10School'] = isset($eduArray['institute_name'])?strtoupper($eduArray['institute_name']):'';
							    $displayData['class10Year'] = isset($eduArray['CourseCompletionDate'])?date('Y',strtotime($eduArray['CourseCompletionDate'])):'';
							    break;
						case '12': $displayData['class12ExaminationName'] = isset($eduArray['Name'])?strtoupper($eduArray['Name']):'';
							    $displayData['class12Percentage'] = isset($eduArray['Marks'])?$eduArray['Marks']:'';
							    $displayData['class12School'] = isset($eduArray['institute_name'])?strtoupper($eduArray['institute_name']):'';
							    $displayData['class12Year'] = isset($eduArray['CourseCompletionDate'])?date('Y',strtotime($eduArray['CourseCompletionDate'])):'';
							    break;
						case 'UG': $displayData['graduationExaminationName'] = isset($eduArray['Name'])?strtoupper($eduArray['Name']):'';
							    $displayData['graduationPercentage'] = isset($eduArray['Marks'])?$eduArray['Marks']:'';
							    $displayData['graduationSchool'] = isset($eduArray['institute_name'])?strtoupper($eduArray['institute_name']):'';
							    $displayData['graduationYear'] = isset($eduArray['CourseCompletionDate'])?date('Y',strtotime($eduArray['CourseCompletionDate'])):'';
							    break;
						case 'Competitive exam':
							    if(isset($eduArray['Name'])){
									$compExamName = strtolower($eduArray['Name']);
							    	if($eduArray['MarksType']=='percentile') {
										$displayData[$compExamName.'Percentile'] = isset($eduArray['Marks'])?$eduArray['Marks']:'';
									}
								    else {
										$displayData[$compExamName.'Score'] = isset($eduArray['Marks'])?$eduArray['Marks']:'';
									}						      
							    }
							    break;
					  }
				    }
			      }
			}
		    }

		    else{	//This is a case of Edit. Get the page values from DB
				$displayData['edit'] = 'true';
		    }

			$result = $newOnlineModel->getPageDataForEdit($courseId,$displayData['userId'],array($displayData['pageId'],1,3));
			//_P($result);die; 	
            $ResultOfDetails1 = array();
            $ResultOfDetails = array();
            $educationDetails = array();
            foreach ($result as $formData){
            	if ($formData['pageId'] ==1){
            		$ResultOfDetails1[] = $formData;
            	}
            	if($formData['pageId']==3){
            		$educationDetails[] =$formData;
            	}
            	$ResultOfDetails[] = $formData;
            }
			if(is_array($ResultOfDetails1)){
				foreach($ResultOfDetails1 as $field1) {
					if($field1['fieldName'] == 'profileImage') {
						$displayData['profileImage'] = $field1['value'];
						break;
					}
				}
			}
			if(is_array($ResultOfDetails)){
			      foreach ($ResultOfDetails as $fieldData){
				    $fieldName = $fieldData['fieldName'];
				    $fieldValue = $fieldData['value'];
					
					//-- If this value has been set above i.e. courseCode, courseName
                    if((!$fieldValue && isset($displayData[$fieldName])) || ($fieldName=='courseName') ) {
						$fieldValue = $displayData[$fieldName];
					}
					
				    $displayData[$fieldName] = addslashes($fieldValue);
			    }
			}
		    $displayData['courseId'] = $courseId;
		    $categoryClient = new Category_list_client();
		    $displayData['country_list'] = $categoryClient->getCountries('1');
		    $displayData['config_array'] = DashboardConfig::$institutes_autorization_details_array;
		    $PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
		    $displayData['config_array'] += $PBTSeoData;

			$this->load->library('StudentDashboardClient');
			$displayData['myDocuments'] = json_decode($this->studentdashboardclient->getDocumentDetails('all',$displayData['userId']),true);
			$myAttachedDocuments = $onlineClient->getAttachedDocuments($displayData['userId'],$displayData['onlineFormId']);
			
			if(is_array($myAttachedDocuments) && count($myAttachedDocuments))
			{
				$myAttachedDocumentIds = array();
				foreach($myAttachedDocuments as $myAttachedDocument)
				{
					$myAttachedDocumentIds[] = $myAttachedDocument['documentId'];
				}
				$displayData['myAttachedDocuments'] = $myAttachedDocumentIds;
			}
			
			$displayData['action'] = $action;
			
			if($displayData['pageType'] == 'custom') {
				if(is_array($educationDetails)) {
					$displayData['educationDetails'] = $educationDetails[0]['pageData'];
				}
				$basicInformation = $ResultOfDetails1;
				if(is_array($basicInformation)) {
					$displayData['basicInformation'] = $basicInformation[0]['pageData'];
				}

				$data = $onlineClient->getFormListForUser($displayData['userId'],$displayData['onlineFormId']);
				if(is_array($data) && is_array($data[0])) {
					$displayData['preferredGDPILocation'] = $data[0]['preferredGDPILocation'];
				}
				
				$gdpiLocations = $onlineClient->getGDPILocations($appId,$courseId);
				$displayData['gdpiLocations'] = $gdpiLocations;
			}
			
			$displayData['NAText'] = 'If this is not applicable in your case, just enter &quot;NA&quot;.';
			$displayData['nationalities'] = DashboardConfig::$nationalities;

			//Fetch the structured arrays from Config			
			$displayData['religion_array'] = StructuredConfig::$religion_array;
			$displayData['board_array'] = StructuredConfig::$board_array;
			$displayData['universities_array'] = StructuredConfig::$univerisities_array;
			$displayData['graduation_exam_array'] = StructuredConfig::$graduation_exam_array;

			//Code added by Ankur for GA Custom variable tracking
	                $displayData['subcatNameForGATracking'] = "Full Time MBA/PGDM";
        	        $displayData['pageTypeForGATracking'] = "ONLINE_FORM_PAGE";
			
			/**
			 * Google remarketing params
			 */ 
			if(intval($courseId)>0) {
				//			$this->load->library('listing/NationalCourseLib');
				//$dominantDesiredCourseData = $this->nationalcourselib->getDominantDesiredCourseForClientCourses(array($courseId));
				foreach ($dominantDesiredCourseData as $key => $value) {
				    $dominantDesiredCourseData[$key]['name'] = $coursesListData[$key];
				}	
			
			}

			if($displayData['pageOrder'] == 2) {
				$isdCode     = new \registration\libraries\FieldValueSources\IsdCode;
				$displayData['isdCodeData'] = $isdCode->getValues(array('source'=>'DB'));
			}

			//below code used for beacon tracking
			$onlineFormUtilityLib = 	$this->load->library("Online/OnlineFormUtilityLib");
			$displayData['beaconTrackData'] = $onlineFormUtilityLib->prepareBeaconTrackDataForCourse('onlineApplicationForm',$courseId);
			if($displayData['pageOrder'] > 0){
				$displayData['beaconTrackData']['extraData']['onlineFormStep'] = $this->_getStepName($displayData['pageOrder']);
			}
		    // Step 5. Load the online form view. This view will show the online page to user. 
		    // Basically, show him the header, institute header (if he is filling a course form), load the template, footer
		    // _P($displayData);die;

		    $this->load->view('Online/showOnlinePage',$displayData);
		}
	}

	function _getStepName($pageOrder){
		switch ($pageOrder) {
			case '1':
				return 'basicInformation';

			case '2':
				return 'personalInformation';

			case '3':
				return 'educationInformation';

			case '4':
				return 'additionalInformation';

			case '5':
				return 'paymentInformation';
		}
	}

	function addOfflinePayment()
	{
		$this->init();
		$userStatus = $this->userStatus;
		$onlineFormId = (int) $this->input->post('onlineFormId');
		
		$this->load->library('payment/PaymentRequest');
		$paymentRequest = new PaymentRequest($userStatus,$onlineFormId);
		
		if(!$paymentRequest->isValid())
		{
			echo $paymentRequest->getErrorMsg(); return;
		}
	
		$this->load->library('payment/PaymentProcessor');
		$paymentProcessor = new PaymentProcessor();
		$paymentProcessor->setPaymentRequest($paymentRequest);
		
		if(!$paymentProcessor->addOfflinePayment())
		{
			echo $paymentProcessor->getErrorMsg();
		}
		header('Location: /studentFormsDashBoard/StudentDashBoard/index');
	}
	
	function addOnlinePayment($onlineFormId)
	{
		$this->init();
		$userStatus = $this->userStatus;
		$onlineFormId = (int) $onlineFormId;
		
		$this->load->library('payment/PaymentRequest');
		$paymentRequest = new PaymentRequest($userStatus,$onlineFormId);
		
		if(!$paymentRequest->isValid())
		{
			echo $paymentRequest->setErrorFormat('json')->getErrorMsg(); return;
		}
		
		$this->load->library('payment/PaymentProcessor');
		$paymentProcessor = new PaymentProcessor();
		$paymentProcessor->setPaymentRequest($paymentRequest);
		
		if(!$result = $paymentProcessor->addOnlinePayment())
		{
			echo json_error($paymentProcessor->getErrorMsg());
		}
		else
		{
			echo $result;
		}
	}

	function processOnlinePayment()
	{
		$this->init();
		
		$userStatus = $this->checkUserValidation();
		
		if(!is_array($userStatus)  || !intval($userStatus[0]['userid']))
		{
			echo 'User is not logged in';
			return;
		}

		$userId = (int) $userStatus[0]['userid'];

		$onlineClient = new Online_form_client();

		$this->load->library('payment/PaymentProcessor');
		$paymentProcessor = new PaymentProcessor();
		$status = $paymentProcessor->processOnlinePayment($userId);

		global $usersForPaytmTesting;
		if(OF_PAYTM_INTEGRATION_FLAG == 1 || in_array($userId,$usersForPaytmTesting)){
			$this->_transferPaytmCredits($userId);
			header('Location: /Online/OnlineForms/showOFConfirmationPage');
		}
		else{
			header('Location: /studentFormsDashBoard/StudentDashBoard/index');
		}
	}

	/**
	* Purpose : Method to transfer Paytm credits to user applying with a valid coupon code(and transfer credits to referrer in case of referral code)
	* Params  : 1. $userId - User id
	*           2. Post Parameters from ccavenues like onlineFormId, userId, CouponCode
	* Author  : Romil
	*/	
	private function _transferPaytmCredits($userId){
		
		// load files
		$this->load->model('OnlineModel');
		$this->load->library('common/CouponLib');

		$couponLib 		= new CouponLib();
		$orderId 		= explode("-",$_POST['Order_Id']);
		$returnPaymentId 	= $orderId[0];
		$returnUserId 		= $orderId[1];
		$returnFormId 		= $orderId[2];
		$userCouponCode		= $this->input->post('Merchant_Param');

		// get paytm payment row-id(if any) and get the status of the online form applied
		$paytmPaymentRowId 	= $this->OnlineModel->checkPaytmPaymentStatus($returnFormId, $returnUserId);
		$onlineFormStatus 	= $this->OnlineModel->getOnlineFormStatusVal($returnFormId);

		/**
		 * Check following conditions before making paytm payment : 
		*   1. check if logged-in user's id is same as the one returned by ccavenues
		*   2. check if this online form status is paid or not
		*   3. check if paytm payment has been done for this online form or not
		*   4. check if user has used a valid coupon code or not : this is handled inside integratePaytm() call
		*/
		if( $returnUserId == $userId 	&&
		    $onlineFormStatus == 'paid' &&
		    empty($paytmPaymentRowId) )
		{
			// transfer the paytm credits
			$couponLib->integratePaytm($returnFormId, $userId, $userCouponCode);
		}
		else{
			// in case any of the above condition fails, log the variables in the onlineFormCouponActivityLog table
			$data 			= array();
			$data['formId'] 	= $returnFormId;
			$data['userId'] 	= $userId;
			$data['status'] 	= 'completed';
			$data['couponCode'] 	= $userCouponCode;
			$data['action'] 	= 'ReturnUserId : '.$returnUserId.', UserId : '.$userId.', OnlineFormStatus : '.$onlineFormStatus.', PaytmPaymentRowId : '.$paytmPaymentRowId.'. PayTM credits not transferred.';
			$couponLib->logOnlineFormCouponSystem($data);
		}
	}
	
	function userDocuments($onlineFormId)
	{
		//Check that the User should be the owner of the formId. If not, redirect him to Student dashboard
		$this->onlinesecurity = new \onlineFormEnterprise\libraries\OnlineFormSecurity();
		if(isset($onlineFormId) && $onlineFormId>0){
			if(!$this->onlinesecurity->checkForm($onlineFormId)){
			        header('Location: /studentFormsDashBoard/StudentDashBoard/index');
			        exit();
			}
		}

		$this->init();
		$userStatus = $this->userStatus;
		$userId = is_array($userStatus)?$userStatus[0]['userid']:0;
		$onlineClient = new Online_form_client();
		$this->load->library('StudentDashboardClient');
		$displayData = array();
		$displayData['myDocuments'] = json_decode($this->studentdashboardclient->getDocumentDetails('all',$userId),TRUE);
		
		$myAttachedDocuments = $onlineClient->getAttachedDocuments($userId,$onlineFormId);
			
		if(is_array($myAttachedDocuments) && count($myAttachedDocuments))
		{
			$myAttachedDocumentIds = array();
			foreach($myAttachedDocuments as $myAttachedDocument)
			{
				$myAttachedDocumentIds[] = $myAttachedDocument['documentId'];
			}
			$displayData['myAttachedDocuments'] = $myAttachedDocumentIds;
		}
		
		echo $this->load->view('myDocuments',$displayData,TRUE);
	}

	function storeApplicationForm($action = ""){
		$this->init();
		
		// Verify CSRF Token
		if(!$this->security->csrf_verify(TRUE)) {
			//echo json_encode(array('errors'=>array(array('invalidToken','This form has expired. Please refresh your browser and fill the form again.'))));
			//return;
		}
		$_POST = $this->convertForStructuredData($_POST);	
		$data = $_POST;

	    $onlineClient = new Online_form_client();
	    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	    // When the form is submitted, we will first get the validations required on the form fields
	    // For each key of the form, get its validation from the DB
		
	    if(is_array($data)){
			//Create a list of Form fields
			$formFieldName = '';
			foreach ($data as $key=>$value){
				if($key!=''){
					$formFieldName .= ($formFieldName=="")? "'".addslashes($key)."'":",'".addslashes($key)."'";
				}
			}
			$formFieldName = trim($formFieldName,',');
			
			$validateResultArr = array();
			//Now, call the BE function to retrieve the validations for the form fields
			$Result = $onlineClient->getFieldValidations($appId,$formFieldName);
			// _P($Result);die;
			
			$country = $this->input->post('country');
			$Ccountry = $this->input->post('Ccountry');
			$mobileISDCode = $this->input->post('mobileISDCode');

			$validation_config = $Result[0]['validateData'];

			$isNationalNumber = true;
			foreach ($validation_config as $key => $config) {
				if( ($config['name'] == 'pincode'  && $country==2) || ($config['name'] == 'Cpincode' && $Ccountry==2) ){
					$Result[0]['validateData'][$key]['minCharactersAllowed'] = 6;
					$Result[0]['validateData'][$key]['maxCharactersAllowed'] = 6;
				}
				if($config['name'] == 'mobileNumber' && $mobileISDCode != '+91') {
					$Result[0]['validateData'][$key]['minCharactersAllowed'] = 6;
					$Result[0]['validateData'][$key]['maxCharactersAllowed'] = 20;	
					$isNationalNumber = false;
				}
			}

			unset($validation_config);

			//After the validations have arrived, we will validate the form fields

			if(is_array($Result) && isset($Result[0]['validateData'])){
				foreach ($Result[0]['validateData'] as $validateField){
					$fieldId = $validateField['fieldId'];
					$requiredField = $validateField['required'];
					$allowNA = FALSE;
					$other = $validateField['other'];
					if($other) {
						$otherParams = explode('|',$other);
						foreach($otherParams as $otherParam){
							list($otherParamName,$otherParamValue) = explode(':',$otherParam);
							if($otherParamName == 'allowNA' && $otherParamValue == 'yes'){
								$allowNA = TRUE;
							}
						}
					}
					
					if($requiredField == 'true'){
						$fieldName = $validateField['name'];
						$validateType = $validateField['validationType'];
						$caption = (isset($validateField['caption']) && $validateField['caption']!='')?$validateField['caption']:'';
						$minLength = (isset($validateField['minCharactersAllowed']) && $validateField['minCharactersAllowed']!='')?$validateField['minCharactersAllowed']:'';
						$maxLength = (isset($validateField['maxCharactersAllowed']) && $validateField['maxCharactersAllowed']!='')?$validateField['maxCharactersAllowed']:'';
						
						if($allowNA && trim($_POST[$fieldName]) == 'NA') {
							//-- do not validate further
						}
						else {
							if($minLength=='' && $maxLength=='' && $caption==''){
								$res = call_user_func($validateType, $_POST[$fieldName]);
							}
							else if($minLength=='' && $maxLength==''){
								$res = call_user_func($validateType, $_POST[$fieldName], $caption);
							}
							else{
								$res = call_user_func($validateType, $_POST[$fieldName], $caption, $maxLength, $minLength, true, $isNationalNumber);
							}
							if($res !== true){
								array_push($validateResultArr,array($fieldName,$res));
							}
						}
					}
				}
			}
		}
		
		if(count($validateResultArr) > 0) {
			$errors = array('errors' => $validateResultArr);
			echo json_encode($errors);
			return;
		}

		
	    //If some document/image has been uploaded, we need to upload it in the Media data folder
	    // Also, we need to validate the Uploaded files
	    $allCheck = true;
	    $numberFiles = count($_FILES['userApplicationfile']['name']);
	    if($numberFiles>1)
	    {
	    	foreach ($_FILES['userApplicationfile'] as $key => $value) {
	    		$temp = $value[0];
	    		$tempNext = $value[1];
	    		for($i=0;$i<$numberFiles;$i++)
	    		{
	    			if($i==0)
	    			{
	    				$_FILES['userApplicationfile'][$key][$i] = $value[$numberFiles-1];
	    			}
	    			else
	    			{
	    				$tempNext = $value[$i];
	    				$_FILES['userApplicationfile'][$key][$i] = $temp;
	    				$temp = $tempNext;
	    			}
	    		}
	    	}
	    }
	   // _P($_FILES);die;

	    //_P($data);die;
	    
	    if(isset($_FILES) && is_array($_FILES) && isset($data['profileImageValid']) && !empty($_FILES['userApplicationfile']['name'][0]) && !isset($data['profileImageValidAdditional'])){
			$returnData = $this->uploadMedia($data['profileImageValid']);
			if(is_array($returnData) && isset($returnData['fileUrl'])){
				$data['profileImage'] = $returnData['fileUrl'];
			}
			else if(is_array($returnData) && isset($returnData['error'])){
				if( ($data['edit']=='true' || $data['edit']==true) && $returnData['error'] == 'Please select a file to upload' ){
				  $allCheck = true;
				}
				else{
					$allCheck = false;
					//echo "profileImage---".$returnData['error'];
				  
					echo json_encode(array('errors'=>array(array('profileImage',$returnData['error']))));
					return;
				}
			}
	    }


	    	// file index will be shifted by 1 so that profile pic can be at index 0. Also, add your code above profile image uploading code so that error can be shown in sequence


	    // this check is for non-mandatory file upload

	    /*if($returnData['error'] == 'Please select a file to upload')
		{
			$allCheckS = true;
		}*/

		// pass 1 as third parameter in uploadMedia function if you want to allow pdf,doc and docx upload also.


	    $allCheckS = true;

	    $this->config->load('Online/uploadconfig');
		$uploadVariables = $this->config->item('uploadVariables');

	    foreach ($uploadVariables as $uploadVariable)
	    {
	    	if($uploadVariable['mandatory'] == 'yes')
	    	{
	    		$returnValue = $this->uploadCommonFunction($uploadVariable['validVariable'],$uploadVariable['fileVariable'],$uploadVariable['index'],$uploadVariable['uploadPdf'],$data,$allCheckS);
	    	}
	    	else if($uploadVariable['mandatory'] == 'no')
	    	{
	    		$returnValue = $this->uploadCommonFunctionForNonMandatoryFields($uploadVariable['validVariable'],$uploadVariable['fileVariable'],$uploadVariable['index'],$uploadVariable['uploadPdf'],$data,$allCheckS);
	    	}

	    	if($returnValue!=1 && $allCheckS!=true)
		     {
		     	echo json_encode(array('errors'=>array(array($uploadVariable['fileVariable'],$returnValue))));
						 return;
		     }
	    }

	    if(isset($_FILES) && is_array($_FILES) && isset($data['profileImageValid']) && isset($data['profileImageValidAdditional']) && ($data['profileImageValidAdditional'] == "validate")){

                $returnData = $this->uploadMedia($data['profileImageValid'],0);

			if(is_array($returnData) && isset($returnData['fileUrl'])){
				$data['profileImage'] = $returnData['fileUrl'];
			}
			else if(is_array($returnData) && isset($returnData['error'])){
				if( ($data['edit']=='true' || $data['edit']==true) && $returnData['error'] == 'Please select a file to upload' ){
				  $allCheck = true;
				}
				else{
					$allCheck = false;
					//echo "profileImage---".$returnData['error'];
				  
					echo json_encode(array('errors'=>array(array('profileImage',$returnData['error']))));
					return;
				}
			}
	    }
	    
            //Change weTill dates to current Dates if the user has selected I currently work here
            if(isset($data['weTill']) && isset($data['weTimePeriod'][0])){
                    if($data['weTill']=='' && $data['weTimePeriod'][0]=='I currently work here'){
                        $data['weTill'] = date('d/m/Y');
                    }
            }
            if(isset($data['weTill_mul_1']) && isset($data['weTimePeriod_mul_1'][0])){
                    if($data['weTill_mul_1']=='' && $data['weTimePeriod_mul_1'][0]=='I currently work here'){
                        $data['weTill_mul_1'] = date('d/m/Y');
                    }
            }
            if(isset($data['weTill_mul_2']) && isset($data['weTimePeriod_mul_2'][0])){
                    if($data['weTill_mul_2']=='' && $data['weTimePeriod_mul_2'][0]=='I currently work here'){
                        $data['weTill_mul_2'] = date('d/m/Y');
                    }
            }

	    //If no validation issues are found, only then proceed with the submittion of form
	    if(count($validateResultArr)<=0 && is_array($data) && $allCheck && $allCheckS){
			//If the fields are validated, we will store the forms fields in the DB. Also, change the status in OF_UserForms table and add mapping in OF_FilledPageMappingInForm table
			//Else, we will return the appropriate error message

			if(($data['edit']=='true' || $data['edit']==true) && isset($data['onlineFormId'])){
			  
				$pageId = $data['pageId'];
				$onlineFormId = $data['onlineFormId'];
			  
				$dataToSend = json_encode($data);
				$Result = $onlineClient->updateFormData($appId,$dataToSend,$userId,$onlineFormId,$pageId,$action);
			}
			else if(isset($data['onlineFormId'])){
			  $dataToSend = json_encode($data);
			  $Result = $onlineClient->setFormData($appId,$dataToSend,$userId);
			}
		
			/*
			 * Document attachment
			 */
			if($this->input->post('hasDocuments'))
			{
				$onlineFormId = $this->input->post('onlineFormId');
				$onlineClient->deletePreviousAttachments($userId,$onlineFormId);
				$attachedDocuments = $this->input->post('attachedDocuments');
				if(is_array($attachedDocuments) && count($attachedDocuments))
				{
					$onlineClient->attachDocuments($userId,$onlineFormId,implode(',',$attachedDocuments));
				}
			}
		
			/*
			 * Notifications for incomplete profile/submission
			 */ 
			  
			$saveExit = $this->input->post('saveExit');
			if($saveExit == 1 && $userId)
			{
				$onlineFormId = $this->input->post('onlineFormId');
				$formDetails = $onlineClient->getFormData($userId,$onlineFormId);
				
				if(is_array($formDetails))
				{
					$formDetails = $formDetails[0];
					if(is_array($formDetails))
					{
						$courseId = (int) $formDetails['courseId'];
						$instituteId = (int) $formDetails['instituteId'];
						$status = $formDetails['status'];
						
						if($status == 'started' || $status == 'uncompleted')
						{
							$msgId = $courseId > 0?36:35;
							$onlineClient->addNotification($onlineFormId,$userId,$instituteId,$msgId,'Viewed');		
						}
					}
				}
			}
			
			/*
			 * Update GD/PI Location
			 */
			
			if(isset($data['preferredGDPILocation']) && !empty($data['preferredGDPILocation'])) {
				$onlineFormId = $this->input->post('onlineFormId');
				$gdpiLocation = $this->input->post('preferredGDPILocation');
				$onlineClient->updateGDPILocation($onlineFormId,$userId,$gdpiLocation);
			}
			
			echo 1;
	    }
	}


	function uploadMedia($validation,$index=0,$format=0) {
	    $this->init();
	    $appId = 1;
	    $ListingClientObj = new Listing_client();
	    $displayData['error'] = 'Please select a file to upload';
	    if(isset($_FILES['userApplicationfile']['name'][$index]) && preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $_FILES['userApplicationfile']['name'][$index]))
	    {
	    	$displayData['error'] = 'Please remove special characters from file name';
	    }
	    else if(isset($_FILES['userApplicationfile']['name'][$index]) && $_FILES['userApplicationfile']['name'][$index]!=''){

		  $type = $_FILES['userApplicationfile']['type'][$index];
		  $size = $_FILES['userApplicationfile']['size'][$index];
		  if($type=="")
		  {
		  	$displayData['error'] = 'This file seems to be corrupted. Please upload another file.';
		  }
		  else if($format==0 && !($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png" || $type== "image/pjpeg" || $type== "image/x-png" || $type== "image/pjpg"))
		  {
		      $displayData['error'] = 'Please upload only jpeg,png,jpg';
		  }
		  else if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png" || $type== "image/pjpeg" || $type== "image/x-png" || $type== "image/pjpg" || $type=="application/pdf"  || $type=="application/msword" || $type=="application/vnd.openxmlformats-officedocument.wordprocessingml.document"))
		  {
		  		$displayData['error'] = 'Please upload only jpeg,png,jpg,pdf,doc,docx';
		  }
		  else if($size>2097152)
		  {
		      $displayData['error'] = 'Please upload a file of max 2 MB only';
		  }
		  else{
		      $fileName = explode('.',$_FILES['userApplicationfile']['name'][$index]);
		      $fileNameToBeAdded = $fileName[0];
		      $fileCaption= $fileNameToBeAdded;
		      $fileExtension = $fileName[count($fileName) - 1];
		      $fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;

		      $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;;

		      $this->load->library('upload_client');
		      $uploadClient = new Upload_client();

		      $fileType = explode('/',$_FILES['userApplicationfile']['type'][$index]);
		      $mediaDataType = ($fileType[0]=='image')?'image':'pdf';

		      //$FILES = $_FILES;
              $FILES = array();
              $FILES['userApplicationfile']['type'][0] = $_FILES['userApplicationfile']['type'][$index];
              $FILES['userApplicationfile']['name'][0] = $_FILES['userApplicationfile']['name'][$index];
              $FILES['userApplicationfile']['tmp_name'][0] = $_FILES['userApplicationfile']['tmp_name'][$index];
              $FILES['userApplicationfile']['error'][0] = $_FILES['userApplicationfile']['error'][$index];
              $FILES['userApplicationfile']['size'][0] = $_FILES['userApplicationfile']['size'][$index];

		      //Before uploading the file, check the Size and type of file. Only if they are valid, will we proceed with the uploading


		      $upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$FILES,array($fileCaption),$userId, 'userApplicationfile','userApplicationfile');
		      //error_log(print_r($upload_forms,true));

		      if(is_array($upload_forms)) {
			  $displayData['fileId'] = $upload_forms[0]['mediaid'];
			  $displayData['fileName'] = $fileCaption;
			  $displayData['mediaType'] = $mediaDataType;
			  $displayData['fileUrl'] = $upload_forms[0]['imageurl'];
		      } else {
			  $displayData['error'] = $upload_forms;
		      }
		  }
	    }
	    return $displayData;
	}

	// In this function, we have to show the form data for user to review. 
	// For this, we would require the 
	// - CourseId (for which course user has applied. In case of Master profile form, this will be 0)
	// - UserId (which user has filled the form. We will get the Logged in user for this)
	function showFormData($courseId=0){

		$this->init();
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$onlineClient = new Online_form_client();

		// Step 1. The FormId, Institute Name, course name, Institute logo Image, Form fee (if any), Institute display text (if any)
		if($courseId>0){
		    $ResultOfDetails = $onlineClient->getOnlineInstituteInfo($appId,$courseId);
		    if(isset($ResultOfDetails) && is_array($ResultOfDetails)){
			$displayData['instituteInfo'] = $ResultOfDetails;
			if(is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseCode'])){
				$displayData['courseCode'] = $ResultOfDetails[0]['instituteInfo'][0]['courseCode'];
				$displayData['courseName'] = $ResultOfDetails[0]['instituteInfo'][0]['courseTitle'];
			}
		    }
		}

		$onlineClient = new Online_form_client();
		//$formData = $onlineClient->getFormData($courseId, $displayData['userId']);
		if(is_array($formData)){

		}
		// Step 3. Load the online form view. This view will show the form data to user.
		$this->load->view('Online/showFormDataToUser',$displayData);
	}

	function getStateForCity(){
		$this->init();
		$appId = 12;
		$categoryClientObj= new Category_list_client();
		$cityId = $this->input->post('cityId');
		$stateName = '';
		if($cityId!=0 && $cityId!=''){
			$cityDetails = $categoryClientObj->getDetailsForCityId($appId, $cityId);
			if(isset($cityDetails['state_name']))
			    $stateName = $cityDetails['state_name'];
		}
		echo $stateName;
	}

	function displayForm($courseId = 0,$sample = 0,$loggedInUserId = 0,$generatePDF = ""){
		//Check if the courseId is a valid courseId and Online form has been applied on this course
		$this->load->model('onlineparentmodel');
		$newOnlineModel = $this->load->model('newOnlineModel');
		$this->onlinesecurity = new \onlineFormEnterprise\libraries\OnlineFormSecurity();

		$instituteInfo = $newOnlineModel->checkValidCourse($courseId,'false','true');
		if($courseId>0){
			if(count($instituteInfo) <= 0){
			        header('Location: /studentFormsDashBoard/StudentDashBoard/index');
			        exit();
			}	
		}

		$courseId = $instituteInfo[0]['courseId'];
		
		$this->init();
		$appId = 12;
		if($loggedInUserId!=0 || $loggedInUserId!=''){
			$loggedInUserId = decode($loggedInUserId);
		}

		 $displayData = array();
		if($loggedInUserId!=0)
                        $displayData['validateuser'] = 'true';
                else
                        $displayData['validateuser'] = $this->userStatus;

                if($loggedInUserId!=0){
                        $displayData['userId'] = $loggedInUserId;
                        $displayData['generate'] = 'pdf';
                }
                else{
                        $displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
                }

		$displayData['courseId'] = $courseId;
		$displayData['generate'] = $generatePDF;
		if($sample) {
			$displayData['showEdit'] = 'false';
			$displayData['sample'] = 1;
		}
		else {
			
			$onlineClient = new Online_form_client();
	
			// Step 1. The FormId, Institute Name, course name, Institute logo Image, Form fee (if any), Institute display text (if any)
			if($courseId>0){
				$ResultOfDetails = $onlineClient->getOnlineInstituteInfo($appId,$courseId);
				//var_dump($ResultOfDetails);
				if(isset($ResultOfDetails) && is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseTitle']) ){
					$displayData['instituteInfo'] = $ResultOfDetails;
					if(is_array($ResultOfDetails) && isset($ResultOfDetails[0]['instituteInfo'][0]['courseCode'])){
						$displayData['courseCode'] = $ResultOfDetails[0]['instituteInfo'][0]['courseCode'];
						$displayData['courseName'] = $ResultOfDetails[0]['instituteInfo'][0]['courseTitle'];
					}
					
					// Step 2. Get the User filled data for this form
					$displayData['profile_data'] = array();					
					$userFormAndFormUserData  = $newOnlineModel->getFormCompleteData($displayData['userId'],$courseId);
					$ResultOfDetails = $userFormAndFormUserData['formUserData'];
					$ResultOfDetails['profileImage'] = MEDIA_SERVER.$ResultOfDetails['profileImage'];
					if(is_array($ResultOfDetails) && count($ResultOfDetails)>0){
							$keyArray = array_keys($ResultOfDetails);
							foreach($keyArray as $fieldData){
								$displayData[$fieldData] = htmlentities($ResultOfDetails[$fieldData],ENT_NOQUOTES);
								$displayData['profile_data'][$fieldData] = htmlentities($ResultOfDetails[$fieldData],ENT_NOQUOTES);
						}
					}
					
					//$currentFormData = $onlineClient->getFormDataByCourseId($displayData['userId'],$courseId);
					$currentFormData = $userFormAndFormUserData['userFormData'];
					if(is_array($currentFormData) && is_array($currentFormData[0])) {
						$displayData['formStatus'] = $currentFormData[0]['status'];
						$displayData['onlineFormId'] = $currentFormData[0]['onlineFormId'];
						$displayData['instituteSpecId'] = isset($currentFormData[0]['instituteSpecId'])?$currentFormData[0]['instituteSpecId']:'';
					}
					
					$data = $onlineClient->getFormListForUser($displayData['userId'],$displayData['onlineFormId']);
					if(is_array($data) && is_array($data[0])) {
						$displayData['preferredGDPILocation'] = $data[0]['preferredGDPILocation'];
						$displayData['gdpiLocation'] = $data[0]['GDPILocation'];
					}
					
					$gdpiLocations = $onlineClient->getGDPILocations($appId,$courseId);
					$displayData['gdpiLocations'] = $gdpiLocations;
					
					//-- Payment details
					$paymentDetails = $onlineClient->getPaymentDetailsByUserId($displayData['userId'],$displayData['onlineFormId']);
					if(is_array($paymentDetails)) {
						$displayData['paymentDetails'] = $paymentDetails[0];
					}
					
					$todayDate = date('Y-m-d');
					foreach ($instituteInfo as $institute){
						$time = date('Y-m-d',strtotime($institute['last_date']));
						
						if ($time >= $todayDate){
							$displayData['showEdit'] = 'true';
							break;
						}
					}

					
				}
			}
		}

		//below code used for beacon tracking
		$onlineFormUtilityLib = 	$this->load->library("Online/OnlineFormUtilityLib");
		$displayData['beaconTrackData'] = $onlineFormUtilityLib->prepareBeaconTrackDataForCourse('onlineDisplayFormPage',$courseId);
		//_p($displayData['beaconTrackData']);die;
		// Display the data
		$this->load->view('Online/displayForm',$displayData);
	}

	function getFormListForInstitute(){ error_log("ssssssss");
            //$this->init(array('Online_form_client')); error_log("ssssssss1");
	    $appId = 12; error_log("ssssssss2");
		$instituteId= isset($_POST['instituteId'])?$this->input->post('instituteId'):0; error_log("ssssssss3");
		$entityId = isset($_POST['id'])?$this->input->post('id'):0; error_log("ssssssss4");
		$type = isset($_POST['type'])?$this->input->post('type'):'';
		$onlineClient = new Online_form_client();
		$formData = $onlineClient->getFormListForInstitute($appId,$instituteId,$entityId,$type);
    }
			
	function sendDeadlineNotifications()
	{
		$this->validateCron();
		$this->init();
		$onlineClient = new Online_form_client();
		$users = $onlineClient->getUsersForDeadlineNotifications();

		if(is_array($users) && count($users) >0) {
			$this->load->library('Online/Online_form_mail_client');
			$emailClient = new Online_form_mail_client;
			
			foreach($users as $user){
				$emailClient->run($user['userId'],$user['onlineFormId'],'form_partially_filled');
			}	
		}
	}
	
	
	/** Amit Singhal : This Cron runs ever 72 Hours, sends mails to all the people who have started
	  * but not submitted thier forms.
	  * This Mailer stops if form is about to expire in 2 days as $this->sendDeadlineNotifications will take care of that.
	  **/
	function sendIncompleteFormsAutoMailer(){
		set_time_limit(0);
		$this->load->model('onlineparentmodel');
        $this->load->model('OnlineModel');
		$mailerDetails = $this->OnlineModel->getDataForIncompleteFormsAutoMailer();
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		//$this->instituteRepository = $listingBuilder->getInstituteRepository();
		$this->courseRepository = $listingBuilder->getCourseRepository();
		
		$output = fopen("php://output",'w') or die("Can't open php://output");
		header("Content-Type:application/csv"); 
		header("Content-Disposition:attachment;filename=IncompleteFormsAutoMailer.csv"); 
		fputcsv($output, array('email','name','course_name','institute_name','course_url','online_form_url'));
		foreach($mailerDetails as $mailer) {
			$tempMailer = array();
			$course = $this->courseRepository->find($mailer['courseId']);
			$tempMailer['email'] = $mailer['email'];
			$tempMailer['name'] = $mailer['firstname']." ".($mailer['middlename']?$mailer['middlename']." ":'').$mailer['lastname'];
			$tempMailer['course_name'] = $course->getName();
			$tempMailer['institute_name'] = $course->getInstituteName();
			$tempMailer['course_url'] = $course->getURL();
			if(array_key_exists('seo_url', $online_form_institute_seo_url[$course->getInstId()])) {$seo_url = SHIKSHA_HOME.$online_form_institute_seo_url[$course->getInstId()]['seo_url'];} else {$seo_url = SHIKSHA_HOME."/Online/OnlineForms/showOnlineForms/".$course->getId();}
			$tempMailer['online_form_url'] = $seo_url;
			fputcsv($output, $tempMailer);
		}
		fclose($output) or die("Can't close php://output");
		//_p($mailerDetails);
	}
	
	
	
        function  renderCrousel($index = 0) {
		$this->load->library('Online_form_client');
		$this->load->library('dashboardconfig');
		$of_institute_ids_array = json_decode($this->online_form_client->getInstitutesForOnlineHomepage($appId),true);
		$inst_id = $of_institute_ids_array[$index];
		if(!empty($inst_id)) {
			$config_array = DashboardConfig::$institutes_autorization_details_array;
			$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', array($inst_id));
			$config_array += $PBTSeoData;
			$arrray_return = $config_array[$inst_id];
                        $arrray_return = json_encode($arrray_return);
                        echo $arrray_return;
                         
		} else {
			echo "";
		}
	}
         function  renderTestimony($index = 0) {
		$this->load->library('dashboardconfig');
		$testimony_config = DashboardConfig::$testimonials_details_array[$index];
		$testimony_config = json_encode($testimony_config);
		echo $testimony_config;
	}
        
/*************************************/
//Code to generate PDF from HTML Start
/*************************************/
        function createPDFFORUser($userType,$cId){
                $this->init();
                $loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
                createPDF($userType,'0','0',$cId,$loggedInUserId);
        }
/*************************************/
//Code to generate PDF from HTML End
/*************************************/

/*************************************/
//Cron to get Daily Data Start
/*************************************/
	function cronToGetDailyInformation($time='hourly'){
	    $this->validateCron();
            error_log("The value of time is ===".$time);
	        $this->init();
            $onlineClient = new Online_form_client();
            $information = $onlineClient->cronToGetDailyInformation($time);
            $filename = date(Ymdhis).'data.csv';
            if($time!='hourly'){
                $filename = date("Ymd", time() - 60 * 60 * 24).'data.csv';
            }
            $mime = 'text/x-csv';
            $columnListArray = array();
            $columnListArray[]='Assigned to';
            $columnListArray[]='Status';
            $columnListArray[]='Course Title';
            $columnListArray[]='Institute Name';
            $columnListArray[]='First Name';
            $columnListArray[]='Last Name';
            $columnListArray[]='Email';
            $columnListArray[]='MobileNo';
            $columnListArray[]='CAT Roll No';
            $columnListArray[]='MAT Roll No';
            $columnListArray[]='Graduation Course';
            $columnListArray[]='GraduationBoard';
            $columnListArray[]='GraduationPercentage';
            $columnListArray[]='GraduationInstitute';
            $columnListArray[]='GraduationYear';
            $columnListArray[]='Fillup Time';
            $columnListArray[]='User Id';
            $columnListArray[]='Form Id';
	    $columnListArray[]='Institute Spec Id';
            $columnListArray[]='Comment';
		
	    $ColumnList = $columnListArray;
            $csv = '';

            foreach ($ColumnList as $ColumnName){
                $csv .= '"'.$ColumnName.'",';
            }
                    $csv .= "\n";

          foreach ($information as $info){
		   if($info['instituteName']==''){$instituteName = 'NULL';}else{$instituteName = $info['instituteName'];}
           $info['FirstName'] = ($info['FirstName']=='')?$info['tuserFirstName']:$info['FirstName'];
           $info['LastName'] = ($info['LastName']=='')?$info['tuserLastName']:$info['LastName'];
           $info['Email'] = ($info['Email']=='')?$info['tuserEmail']:$info['Email'];
           $info['MobileNo'] = ($info['MobileNo']=='')?$info['tuserMobile']:$info['MobileNo'];
		   if($info['courseTitle']==''){$courseTitle = 'NULL';}else{$courseTitle = $info['courseTitle'];}
                   $csv .= "Shiksha".",".$info['status'].",".str_replace(",","",$courseTitle).",".str_replace(",","",$instituteName).",".$info['FirstName'].",".$info['LastName'].",".$info['Email'].",".$info['MobileNo'].",".$info['CATRegNo'].",".$info['MATRegNo'].",".str_replace(",","",$info['GraduationName']).",".str_replace(",","",$info['GraduationBoard']).",".$info['GraduationPercentage'].",".str_replace(",","",$info['GraduationInstitute']).",".$info['GraduationYear'].",".$info['creationDate'].",".$info['userId'].",".$info['onlineFormId'].",".$info['instituteSpecId'].","."";
                $csv .= "\n";
           }

            $filenameP = date(Ymdhis).'paymentData.csv';
            if($time!='hourly'){
                 $filenameP = date("Ymd", time() - 60 * 60 * 24).'paymentData.csv';
            }

            $information = $onlineClient->cronToGetDailyPaidInformation($time);
            $columnListArray = array();
            $columnListArray[]='Assigned to';
            $columnListArray[]='Form Status';
            $columnListArray[]='Payment Status';
            $columnListArray[]='Mode of Payment';
            $columnListArray[]='Order ID';
            $columnListArray[]='Payment Date';
            $columnListArray[]='Amount';
            $columnListArray[]='Bank Name';
            $columnListArray[]='Draft Number';
            $columnListArray[]='Draft Date';
            $columnListArray[]='Course Title';
            $columnListArray[]='Institute Name';
            $columnListArray[]='First Name';
            $columnListArray[]='Last Name';
            $columnListArray[]='Email';
            $columnListArray[]='MobileNo';
            $columnListArray[]='CAT Roll No';
            $columnListArray[]='MAT Roll No';
            $columnListArray[]='Graduation Course';
            $columnListArray[]='GraduationBoard';
            $columnListArray[]='GraduationPercentage';
            $columnListArray[]='GraduationInstitute';
            $columnListArray[]='GraduationYear';
            $columnListArray[]='Fillup Time';
            $columnListArray[]='User Id';
            $columnListArray[]='Form Id';
            $columnListArray[]='Institute Spec Id';
            $columnListArray[]='Comment';
		
	    $ColumnList = $columnListArray;
            $csvP = '';

            foreach ($ColumnList as $ColumnName){
                $csvP .= '"'.$ColumnName.'",';
            }
                    $csvP .= "\n";

          foreach ($information as $info){
		   if($info['instituteName']==''){$instituteName = 'NULL';}else{$instituteName = $info['instituteName'];}
		   if($info['courseTitle']==''){$courseTitle = 'NULL';}else{$courseTitle = $info['courseTitle'];}
                   $csvP .= "Shiksha".",".$info['formStatus'].",".$info['status'].",".$info['mode'].",".$info['orderId'].",".$info['date'].",".$info['amount'].",".$info['bankName'].",".$info['draftNumber'].",".$info['draftDate'].",".str_replace(",","",$courseTitle).",".str_replace(",","",$instituteName).",".$info['FirstName'].",".$info['LastName'].",".$info['Email'].",".$info['MobileNo'].",".$info['CATRegNo'].",".$info['MATRegNo'].",".str_replace(",","",$info['GraduationName']).",".str_replace(",","",$info['GraduationBoard']).",".$info['GraduationPercentage'].",".str_replace(",","",$info['GraduationInstitute']).",".$info['GraduationYear'].",".$info['creationDate'].",".$info['userId'].",".$info['onlineFormId'].",".$info['instituteSpecId'].","."";
                $csvP .= "\n";
           }

	   $this->load->library('alerts_client');
           $alertClientObj = new Alerts_client();
           $type_id = time();
           $date = date("Y-m-d h:i:s");
           $subject=$date .' Online Form Report';
           if($time!='hourly'){
                 $date = date("Y-m-d", time() - 60 * 60 * 24);
                 $subject=$date .' Consolidated Online Form Report';
           }

           $content = "<p>Hi, </p><p>Please find the attached report for Online forms filled till $date on Shiksha. </p><p>- Shiksha Tech.</p>";
           $email   = array('ankur.gupta@shiksha.com','pranjul.raizada@shiksha.com','pratibha.bhatia@shiksha.com','neelankshi.w@shiksha.com','anupama.m@shiksha.com','saurabh.gupta@shiksha.com');
	   $attachmentArray = array();
	   for($i=0;$i<count($email);$i++){
		if(count($attachmentArray)==0){
		   $attachmentResponse = $alertClientObj->createAttachment("12",$type_id,'COURSE','E-Brochure',$csv,$filename,'text');
		   $attachmentId = $attachmentResponse;
		   $attachmentArray=array();
		   array_push($attachmentArray,$attachmentId);

		   $type_idP = time()+1;
		   $attachmentResponse = $alertClientObj->createAttachment("12",$type_idP,'COURSE','E-Brochure',$csvP,$filenameP,'text');
		   $attachmentId = $attachmentResponse;
		   array_push($attachmentArray,$attachmentId);
		}
		$response = $alertClientObj->externalQueueAdd("12","info@shiksha.com",$email[$i],$subject,$content,$contentType="html",'','y',$attachmentArray);
	  }

      //Check if the time is after 12 midnight, we will send a consolidated report of the day
      if($time=='hourly'){
            $hour = date("H",time());
            $min = date("i",time());
            if($hour=='00' && $min>=0 && $min<30 ){
                $this->cronToGetDailyInformation('daily');
            }
      }
	}
/*************************************/
//Code to get Daily Data End
/*************************************/
//*************************************/
	//Cron to get every fifteenth Day Data Start
	/*************************************/
	function cronToGetEveryFifteentDayInformation(){
		$this->validateCron();
		try {
			$this->init();
			$onlineClient = new Online_form_client();
			$information = $onlineClient->cronToGetEveryFifteentDayInformation();
			if(!empty($information) && count($information)>0) {
				$filename = date(Ymdhis).'data.csv';
				$mime = 'text/x-csv';
				$columnListArray = array();
                                $columnListArray[]='FormId';
				$columnListArray[]='Reciept Date';
				$columnListArray[]='Transaction id';
				$columnListArray[]='Client/Student';
				$columnListArray[]='University';
				$columnListArray[]='Course Form';
				$columnListArray[]='Sale Amount';
				$columnListArray[]='Transaction date';
				$columnListArray[]='Sale Executive';
				$columnListArray[]='Invoice no.';
				$columnListArray[]='Sale Type';
				$columnListArray[]='Cheque No';
				$columnListArray[]='Cheque City';
				$columnListArray[]='Cheque Bank';
				$columnListArray[]='Cheque Date';
				$columnListArray[]='Cheque Amount';
				$columnListArray[]='TDS_Amount';
				$columnListArray[]='Payment_Mode';
				$columnListArray[]='Payment Gateway';
				$columnListArray[]='Deposited_By';
				$columnListArray[]='Deposited_Branch';
				$columnListArray[]='Payment Status';
				$columnListArray[]='Sale Branch';
				$columnListArray[]='Currency Code';
				$ColumnList = $columnListArray;
				$csv = '';
				foreach ($ColumnList as $ColumnName){
					$csv .= '"'.$ColumnName.'",';
				}
				$csv .= "\n";

				foreach ($information as $info){
					$csv .= $info['onlineFormId'].",".'"'.$info['date'].'","'.$info['orderId'].'","'.$info['firstName']." ".$info['middleName']." ".$info['lastName'].'","'.$info['institute_name'].'","'.$info['courseTitle'].'","'.$info['amount'].'","'.$info['date'].'","'." ".'","'.$info['paymentId'].'","'.$info['mode'].'","'.$info['draftNumber'].'","'." ".'","'.$info['bankName'].'","'.$info['draftDate'].'","'.$info['amount'].'","'." ".'","'.$info['mode'].'"'.","."".","."".","."".",".$info['trans_status'].","."".","."INR";
					$csv .= "\n";
				}
	   $this->load->library('alerts_client');
	   $alertClientObj = new Alerts_client();
	   $type_id = time();
	   $date = date("d-m-Y");
	   $content = "<p>Hi,</p> <p>Please find the attached report for Online forms filled in last 15 days on Shiksha. </p><p>- Shiksha Tech.</p>";
	   $subject = "";
	   $subject .=$date .' Online Form Report for last 15 days';
	   $email   = array('saurabh.shrivastava@shiksha.com','daneesh.sarbhoy@shiksha.com','ambrish@shiksha.com','nishant.pandey@naukri.com','neha.maurya@shiksha.com','prabhat.sachan@shiksha.com','shyam@naukri.com','akanksha.sharma@shiksha.com','piyush.kumar@shiksha.com ');
	   $attachmentArray = array();
	   for($i=0;$i<count($email);$i++){
		   if(count($attachmentArray)==0){
			   $attachmentResponse = $alertClientObj->createAttachment("12",$type_id,'COURSE','E-Brochure',$csv,$filename,'text');
			   //_p($attachmentResponse);
	           error_log("Create attachment response is==".$attachmentResponse);
			   //$attachmentidresponse = $alertClientObj->getAttachmentId("12",$type_id,'COURSE','E-Brochure',$filename);
			   //$attachmentId = $attachmentidresponse[0]['id'];
	           $attachmentId = $attachmentResponse;
			   $attachmentArray=array();
			   array_push($attachmentArray,$attachmentId);
			}
		   $response = $alertClientObj->externalQueueAdd("12","info@shiksha.com",$email[$i],$subject,$content,$contentType="html",'','y',$attachmentArray);
	   }
			} else {
				error_log ("OF_MIS_CRON_15_DAYS_NO_DATA_FOUND");
			}
	 } catch(Exception $e) {
	 	error_log ("OF_MIS_CRON_15_DAYS_ERROR".print_r($e));
	 }
	}
	//*************************************/
	//Cron to get every fifteenth Day Data End
	/*************************************/
        /*************************************/
	//Cron to handle failed transaction Starts
	/********************************************/
	function cronToHandleFailedTransactions(){
		$this->validateCron();
		try {
			$this->init();
			$onlineClient = new Online_form_client();
			$form_list = $onlineClient->cronToHandleFailedTransactions();
			if(!empty($form_list) && is_array($form_list) && count($form_list)>0){
				$this->load->library('online_form_mail_client');
				foreach($form_list as $form){
					$this->online_form_mail_client->run($form['userId'],$form['onlineFormId'],"online_payment_interrupted");
				}
			} else {
				error_log('NO DATA FOUND FOR THE CRON THAT_HANDLES_FAILED_TRANSACTIONS');
			}
		} catch(Exception $e) {
			error_log ("OF_CRON THAT_HANDLES_FAILED_TRANSACTIONS_ERROR".print_r($e));
		}
	}
	//*******************************************/
	//Cron to handle failed transaction Ends
	/********************************************/
	
	//*************************************************/
	//Code to check online Form Status Start By Pranjul
	/**************************************************/
	
	function checkOnlineFormExpiredStatus(){
                $this->init(array('Online_form_client'));
                $courseId = (int) $this->input->post('courseId');
                $res = $this->online_form_client->formHasExpired($courseId);
                $res = json_decode($res,true);
                if($res[$courseId] ==1) {
                      echo  "expired";
                 } else if($res[$courseId]>1) {
                       echo $res[$courseId];
                 } else {
                       echo "notexpired";
                 }

        }
	
	//*************************************************/
	//Code to check online Form Status Start By End
	/**************************************************/

	function convertForStructuredData($post){
		$arrayOfFields = array('religion','class10Board','class12Board','graduationBoard','graduationBoard_mul_1','graduationBoard_mul_2','graduationBoard_mul_3','graduationBoard_mul_4','graduationExaminationName');
		foreach ($arrayOfFields as $fieldName){
			$otherFieldName = $fieldName."_other";
			if(isset($post[$otherFieldName]) && $post[$otherFieldName]!='' && ($post[$fieldName]=='OTHER' || $post[$fieldName]=='')){
				$post[$fieldName] = $post[$otherFieldName];
			}
			unset($post[$otherFieldName]);
		}
		return $post;
	}

	function showPage($pageUrl){
		$displayData = array();
		$displayData['url'] = base64_decode($pageUrl,true);
		$this->load->view('Online/showPage',$displayData);
	}	

	function filterHomepageInstitutes($department){
		$this->init(array('Online_form_client'),array('url','shikshautility_helper'));
		//load the required library
		$this->load->library('StudentDashboardClient');
		$this->load->library('dashboardconfig');
                $onlineClient = new Online_form_client();
        $this->load->library('nationalCourse/CourseDetailLib');
		// Get the Institutes which needs to be displayed here
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$filter = (isset($_POST['filter']))?$this->input->post('filter'):'All';
		$search = (isset($_POST['searchString']))?$this->input->post('searchString'):'';
		$instId = (isset($_POST['instId']))?$this->input->post('instId'):'';
		if($filter!='search'){
			$showExternalForms = 'true';
			$filterArray = array('filter'=>$filter, 'search'=>$search);
			$of_institute_ids_array = json_decode($onlineClient->getInstitutesForOnlineHomepage($appId,$showExternalForms,$filterArray,$department),true);
		}
                else if($instId>0){
                        $of_institute_ids_array = array($instId);
                }
		else{
                	$of_institute_ids_array = json_decode($this->studentdashboardclient->getTheIdsOfInstituteHavingOF($search,1,$department),true);
		}
		if(!empty($of_institute_ids_array) && is_array($of_institute_ids_array)){
		      $displayData['instituteList'] = $this->studentdashboardclient->renderInstituteListWithDetails($of_institute_ids_array, $department);		  
		      $displayData['institute_features'] = json_decode($this->studentdashboardclient->returnOfInstitutesOfferandOtherDetails($of_institute_ids_array, $department),true);
      		      $displayData['config_array'] = DashboardConfig::$institutes_autorization_details_array;
      		      $PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', $of_institute_ids_array);
      		      $displayData['config_array'] += $PBTSeoData;
      		}
                $displayData['of_institute_ids_array'] = $of_institute_ids_array;

                /* code addded for pagiantion */
		$displayData['page_type_for_identification'] = "ONLINE_HOME_PAGE";
		$displayData['total_number_pages'] = 0;
		$displayData['trackingPageKeyId']=167;
		$displayData['count_result'] = count($displayData['instituteList']);
		if($displayData['count_result']%INSTITUTE_PER_PAGE == 0) {
			$displayData['total_number_pages'] = (int)($displayData['count_result']/INSTITUTE_PER_PAGE);
		} else {
			$displayData['total_number_pages'] = (int)($displayData['count_result']/INSTITUTE_PER_PAGE)+1;
		}
		foreach ($displayData['instituteList'] as $inst_id1=>$instituteList_object1):
			$inst_id1_arry[] = $inst_id1;
		endforeach;
		$displayData['inst_id1_arry'] = $inst_id1_arry;
		$current_page = strip_tags($_REQUEST['start']);
		$current_page = intval($current_page,10);
		if(!empty($current_page)) {
			$current_page_new = ($current_page/INSTITUTE_PER_PAGE)+1;
		} else {
			$current_page_new = 1;
		}
		$paginationHTML = doPagination($displayData['count_result'],$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']."?start=@start@&num=@count@",$current_page,INSTITUTE_PER_PAGE,$displayData['total_number_pages']);
		$displayData['paginationHTML'] = $paginationHTML;
		$offset = ($current_page_new-1)*INSTITUTE_PER_PAGE;
		$return_array = $this->studentdashboardclient->handlePagination($inst_id1_arry,$displayData['total_number_pages'],$current_page_new,INSTITUTE_PER_PAGE, $department) ;
		if(!empty($return_array) && count($return_array)>0) {
			$displayData['instituteList'] = $return_array['instituteList'];
			$displayData['institute_features'] = $return_array['institute_features'];
		}
		/* code ended for pagination */
		$displayData['CourseDetailLib'] = new CourseDetailLib;

		if(empty($of_institute_ids_array) || !is_array($of_institute_ids_array)){
			echo "<div style='font-size:19px;margin-top:20px;color:#FF0000;'>No matching forms found!</div>";
		}
		else{
			if($paginationHTML!='') 
				echo "<div><div class='pagingID txt_align_r' id='paginataionPlace1' style='line-height:23px;'>&nbsp;".$paginationHTML."</div></div>";
 		        echo $this->load->view('studentFormsDashBoard/common_template_across',$displayData);
		}
	}

	function getInstituteTitles($department){
                $this->init();
                $appId = 12;
                $this->load->model('onlineparentmodel');
                $this->load->model('OnlineModel');
                $response = $this->OnlineModel->getOnlineInstituteTitles($department);
                return $response;
	}


	function cronForLIBA(){
                $this->init();
                $this->load->library('Online_form_client');
                $onlineClient = new Online_form_client();
                $this->load->library('alerts_client');
                $courseId = '187300';
                $this->load->model('OnlineModel');
                $response = $this->OnlineModel->cronForLIBA($courseId);
                foreach($response as $key=>$value){
                         $data['ResultOfDetails'] = $onlineClient->getFormCompleteData('12',$value['userId'],$courseId);
                         $data['form_details'] = $onlineClient->getFormListForUser($value['userId'],$value['onlineFormId']);
                         $payment_array = $onlineClient->getPaymentDetailsByUserId($value['userId'],$value['onlineFormId']);
                         $payment_array = $payment_array['0'];
                         $data['payment_mode'] = $payment_array['mode'];
                         $data['order_value'] = $payment_array['amount'];
                         $data['transaction_date'] = $payment_array['date'];
                         $data['transaction_id'] = $payment_array['orderId'];
                         $data['payment_status'] = $payment_array['status'];
                         $data['creationDate'] = $form_details[0]['creationDate'];
                         $content = $this->load->view('Online/Mail/liba_payment_advice',$data,true);
			 			 $toEmail = $data['ResultOfDetails']['email'];
                         $subject = "LIBA Payment Advice Mail";
                         $response = $this->alerts_client->externalQueueAdd("12", "Help@shiksha.com", $toEmail, $subject, $content, $contentType = "html");
                }
        }
	
	function getRegistrationFormHTML() {
		$this->init();
		$catId = isset($_GET['catId'])?$this->input->get('catId'):'23';
		$trackingPageKeyId = isset($_GET['tracking_keyid'])?$this->input->get('tracking_keyid'):'';
		echo Modules::run('registration/Forms/LDB',NULL,'OnlineForm',array('coursePageSubcategoryId' => $catId,'trackingPageKeyId' => $trackingPageKeyId));
	}
	
	function script_for_update_actiontype () {
		$this->init();
		$this->load->model('OnlineModel');
		$response_ids = $this->OnlineModel->update_response_action_type();
		echo "Response ids updated are : ".$response_ids;
	}
	
	// validate coupon
	function getCouponStatus()
	{
		$coupon =  isset($_POST['coupon']) ? $this->input->post('coupon') : 0;
		$couponLib = $this->load->library('common/couponLib');
		$valid = $couponLib->validateAndGetCouponType($coupon);
		
		echo json_encode($valid);
	}
	
	//apply coupon
	function applyCoupon() {
		$this->userStatus = $this->checkUserValidation();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
		if($userId>0){
		$coupon = $this->input->post("coupon");
		$courseId = $this->input->post("courseId");
		$isCouponExists = $this->input->post("isCouponExists");
		$couponLib = $this->load->library('common/couponLib');
		
		//check if no coupon is applied
		if($isCouponExists === "false") {
			echo json_encode(array('isValid'=> 1)); 
			$couponLib->trackUserAppliedCoupon(0,$userId,$courseId); // update coupon entry to history 
			exit();
		} else {  // if coupon code is applied then validate and apply it.
			
			$valid = $couponLib->validateAndGetCouponType($coupon);
			if(is_array($valid) && $valid['isValid']) {
			$couponLib->trackUserAppliedCoupon($coupon,$userId,$courseId);
			}
			echo json_encode($valid);
			}
		} 
	}
	
	/**
	* Purpose : Method to show confirmation message after payment of the online form is completed/ thank you page
	* Params  : 1. $showThankYouMsg - Flag to show thank you message of online form submission with reference id
	*           2. $showPaytmAmt - Show paytm amount transferred
	* Author  : Romil
	*/	
	function showOFConfirmationPage($showThankYouMsg = 1, $showPaytmAmt = 1){
		
		// check user's logged-in status
		$userStatus = $this->checkUserValidation();
		
		// redirect the user to online form dashboard/homepage if not logged-in
		if($userStatus == 'false')
		{
			header('Location: /studentFormsDashBoard/StudentDashBoard/index');
		}
		
		$userId = $userStatus[0]['userid'];
		$this->load->model('onlineparentmodel');
		$this->load->model('OnlineModel');
		$this->load->library("common/CouponLib");
		$couponLib = new CouponLib();
		
		$appRefId 	= $this->OnlineModel->getUsersLatestOFApplicationNumber($userId);
		// get user's coupon code
		$couponCode 	= $couponLib->getUserCoupon($userId);
		//getting shortlink of coupon code
        $encodedUniqueCode = $couponLib->encodeCouponCode($couponCode);
        $shortLink = SHIKSHA_HOME."/r/c/".$encodedUniqueCode;
		// redirect the user to online form dashboard/homepage if no application reference id exists
		if((empty($appRefId) && $showThankYouMsg == 1) || (empty($couponCode) || $couponCode == null))
		{
			header('Location: /studentFormsDashBoard/StudentDashBoard/index');
		}
		
		$data['validateuser'] 	= $userStatus;
		$data['appRefId'] 	= $appRefId;
		$data['couponCode'] 	= $couponCode;
		$data['showThankYouMsg']= $showThankYouMsg;
		$data['showPaytmAmt']	= $showPaytmAmt;
		$data['shortLink']	= $shortLink;
		
		// render the html
		$this->load->view("confirmationPage", $data);
	}
	
	
	// @akhter
	// if $isShowWidget is true, then show view widget
	// if $isShowWidget is false, then it returns you to data in json
	// paytm display message on the top of the page
	function paytmWidget($isShowWidget=true, $isMobile=false)
	{
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['isMobile'] = $isMobile;

		$this->_getCouponRelatedData($displayData,$userId);
		
		if($isShowWidget)
		{
			if($isMobile){
				$this->load->view('Online/payTmOfferMsgMobile',$displayData);
			}
			else{
				$this->load->view('Online/payTmOfferMsg',$displayData);
			}
		}else{
			return $displayData;
		}
	}

	
	/**
	 *  Get coupon related data - default, referal or own code.
	 */
	
	private function _getCouponRelatedData(&$displayData,$userId) {
		$couponLib = $this->load->library('common/couponLib');
		if(isset($_COOKIE['referral_Coupon']) && $_COOKIE['referral_Coupon'] !=''){
			$getDetail = $couponLib->getCouponUserData($_COOKIE['referral_Coupon'], 1);
			if($getDetail['msg'] === 'VALID COUPON'){
				$displayData['referralName'] = ucfirst($getDetail['firstName']);
				$displayData['coupon'] = $getDetail['couponCode'];
			}
		}else if(isset($userId) && $userId>0){
			$getOwnCode = $couponLib->getUserCoupon($userId);
			$displayData['referralName'] = 'ownCode';
			if($getOwnCode !=''){
				$displayData['coupon'] = $getOwnCode;
			}
		}else{
			$displayData['referralName'] = 'default';
			$displayData['coupon'] = 'SHK101';
		}
		
	}
	
	
	/**
	 *  Paytm Widget Overlay For External Forms
	 *
	 */
	function paytmOverLayForExternalForms(){
		$this->userStatus = $this->checkUserValidation();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData = array();
		if($userId>0){
			$this->_getCouponRelatedData($displayData,$userId); //to make paytm widget view
		}
		
		$courseId = $this->input->post('courseId');
		$displayData['courseId'] = $courseId;
		if($courseId) {
			$couponLib = $this->load->library('common/couponLib');
			$couponCode = $couponLib->fetchAppliedCouponForACourse($courseId,$userId); // fetch coupon code if user has applied before.
		}
		
		//Deciding which coupon code will be shown prefilled.
		if(isset($couponCode)) {
			$displayData['preFilledCoupon'] = $couponCode; 
		} else {
			
			$displayData['preFilledCoupon'] = isset($displayData['coupon']) ? $displayData['coupon'] : 'SHK101' ;
		}
				
		$displayData['viewType'] = 'Overlay';
		$this->load->view('Online/payTmOfferMsgOverlay',$displayData);
	}
	
	
	
	function startPaytmProcess()
	{
		$formId = isset($_POST['form_id']) ? $this->input->post('form_id') : ''; 
		if(isset($formId) && $formId !='')
		{
			$data = array();
			$this->init();
			$couponLib = $this->load->library('common/couponLib');
			$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			
			$data['formId'] = $formId;
			$data['userId'] = $userId;
			$data['status'] = 'initiated';
			$data['action'] = 'Shiksha payment process started.';
			$couponLib->logOnlineFormCouponSystem($data);
		}
	}
        
        function isExternalForm($courseId) {
            $this->load->library("Online/OnlineFormUtilityLib");
			$onlineFormUtilityLib = new OnlineFormUtilityLib();
			$OFInstituteDetails = $onlineFormUtilityLib->getOnlineFormAllCourses();
            if($OFInstituteDetails[$courseId]['external'] == 'yes') {

                return true;
            }
            else {
                return false;
            }
        }
        
        //THIS_CODE_NEEDS_TO_BE_REMOVE_AFTER_RECAT_GOES_LIVE
        function getCanonicalUrl($courseId) {
        	//get dashboard config for online form
			$this->load->library('listing/NationalCourseLib');
			$OFInstituteDetails = $this->nationalcourselib->getOnlineFormAllCourses();
            if($OFInstituteDetails[$courseId]['seo_url'] != '') {
                return SHIKSHA_HOME.$OFInstituteDetails[$courseId]['seo_url'];
            }
            else 
                return false;
        }

	/**
	* Purpose : Method to make paytm payment of all pending External Online forms
	* Params  : none
	* Author  : Romil Goel
	*/
	function makeExternalOnlineFormPaytmPayment(){
		$this->validateCron();
	
		// load files	
		$this->load->model('onlineparentmodel');
		$this->load->model('OnlineModel');
		$this->load->library('Alerts_client');
		$this->load->model("user/usermodel");

		$couponLib 				= $this->load->library('common/couponLib');
		$alertClient 			= new Alerts_client();
		$usermodel_object   	= new usermodel();
		$alertEmails 			= array("romil.goel@shiksha.com", "pankaj.meena@shiksha.com","vinay.airan@shiksha.com","aman.varshney@shiksha.com","g.ankit@shiksha.com","Nikita.Jain@shiksha.com","saurabh.gupta@shiksha.com");
		$sameIPOnlineFormLimit 	= 5;
		
		// pick the unpaid external OF coupons
		$unpaidCoupons = $this->OnlineModel->getExternalOFUnpaidCoupons();
		
		$uniQueUsers = array();
		foreach ($unpaidCoupons as $unpaidCouponsRow) {
			$uniQueUsers[] = $unpaidCouponsRow['userId'];
		}
		$uniQueUsers = array_unique($uniQueUsers);
		
		if(!empty($uniQueUsers))
			$usersDetail = $usermodel_object->getUsersBasicInfo($uniQueUsers);

		// Start:For each unpaid coupon DO ->
		foreach($unpaidCoupons as $unpaidCouponsRow)
		{
			/* ******** Security Step 1 ***********
			 * check for how many times a user has been paid for the same course-id
			 * and it should not be more than noOfAttempts
			 */
			// get submit pixel for this course and user
			$pixelInfo = $this->OnlineModel->getExternalFormPixelInfo($unpaidCouponsRow['courseId'], $unpaidCouponsRow['userId'], 'submitted');

			// if submission pixel is not found, then do not make the paytm payment
			if(empty($pixelInfo))
				continue;

			// get number of times paytm payment has been made to an External form for a user
			$countOfAlreadyPaidExternalOF = $this->OnlineModel->getPaytmPaidExternalFormCount($unpaidCouponsRow['courseId'], $unpaidCouponsRow['userId']);
			
			if($countOfAlreadyPaidExternalOF >= $pixelInfo['attempt'])
			{
				// TODO : send an email alert
				$mailText = "Security Step 1 <br/>Attempts for Paytm payment are <b>exceeding</b> Number of pixel Submitted.<br/>Course Id : ".$unpaidCouponsRow['courseId']." and User Id : ".$unpaidCouponsRow['userId'];
				foreach ($alertEmails as $alertEmailsRow) 
					$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $alertEmailsRow, "External Form Paytm Payment Alert", $mailText, "html", '', 'n');

				// do not pay
				continue;
			}

			/* ****** Security Step 2 ******
			 * Get the IP of submittion pixel of user+course and check that today how many times this IP has
			 * submitted the External form. If it exceeds a defined threshold limit,
			 * then do not make the paytm payment(as it might be the case of possible hacking). And send an alert for this case.
			 */
			$onlineFormCountWithSameIP = $this->OnlineModel->getPixelCountByIPAddress('submitted', $pixelInfo['ip_address']);

			if($onlineFormCountWithSameIP > $sameIPOnlineFormLimit){
				// send a email alert
				$mailText = "Security Step 2 <br/>External Online Form submitted from same IP address exceeds the threshold of ".$sameIPOnlineFormLimit.", hence Paytm Payment for this form is on hold.<br/>Course Id : ".$unpaidCouponsRow['courseId']." and User Id : ".$unpaidCouponsRow['userId']." and IP Address : ".$pixelInfo['ip_address'];
				foreach ($alertEmails as $alertEmailsRow) 
					$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $alertEmailsRow, "External Form Paytm Payment Alert", $mailText, "html", '', 'n');
				// hold the payment
				$this->OnlineModel->updateExternalOFPaymentStatus($unpaidCouponsRow['courseId'], $unpaidCouponsRow['userId'], 'unpaid', 'onhold');

				// do not pay
				continue;
			}

			/* ****** Security Step 3 *******
			* Hold the Paytm payment for the Online form which has been submitted twice with same email-id.
			*/
			$userEmail 	= $usersDetail[$unpaidCouponsRow['userId']]['email'];
			$data 		= $this->OnlineModel->getPaidOnlineFormsByUser('email', $userEmail, $unpaidCouponsRow['courseId']);
			if($data)
			{
				// send a email alert
				$mailText = "Security Step 3 <br/>Same External Online form has been submitted twice with same email-id, hence Paytm Payment for this form is on hold.<br/>Course Id : ".$unpaidCouponsRow['courseId']." and User Id : ".$unpaidCouponsRow['userId']."and User Email : ".$userEmail;
				foreach ($alertEmails as $alertEmailsRow) 
					$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $alertEmailsRow, "External Form Paytm Payment Alert", $mailText, "html", '', 'n');
				// hold the payment
				$this->OnlineModel->updateExternalOFPaymentStatus($unpaidCouponsRow['courseId'], $unpaidCouponsRow['userId'], 'unpaid', 'onhold');

				// do not pay
				continue;
			}

			/* ****** Security Step 4 *******
			* Hold the Paytm payment for the Online form which has been submitted twice with same mobile number.
			*/
			$userMobile = $usersDetail[$unpaidCouponsRow['userId']]['mobile'];
			$data 		= $this->OnlineModel->getPaidOnlineFormsByUser('mobile', $userMobile, $unpaidCouponsRow['courseId']);
			if($data)
			{
				// send a email alert
				$mailText = "Security Step 4 <br/>Same External Online form has been submitted twice with same mobile number, hence Paytm Payment for this form is on hold.<br/>Course Id : ".$unpaidCouponsRow['courseId']." and User Id : ".$unpaidCouponsRow['userId']."and User Mobile : ".$userMobile;
				foreach ($alertEmails as $alertEmailsRow) 
					$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $alertEmailsRow, "External Form Paytm Payment Alert", $mailText, "html", '', 'n');
				// hold the payment
				$this->OnlineModel->updateExternalOFPaymentStatus($unpaidCouponsRow['courseId'], $unpaidCouponsRow['userId'], 'unpaid', 'onhold');

				// do not pay
				continue;
			}

			/* ****** Security Step 5 *******
			* Send a mail alert for the case when an Online form is submitted by a user(email-id) who have already filled another online form.
			*/
			$userEmail 	= $usersDetail[$unpaidCouponsRow['userId']]['email'];
			$data 		= $this->OnlineModel->getPaidOnlineFormsByUser('email', $userEmail);
			if($data)
			{
				// send a email alert
				$mailText = "Security Step 5 <br/>Same External Online form has been submitted twice with same email-id.<br/>Course Id : ".$unpaidCouponsRow['courseId']." and User Id : ".$unpaidCouponsRow['userId']."and User Email : ".$userEmail;
				foreach ($alertEmails as $alertEmailsRow) 
					$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $alertEmailsRow, "External Form Paytm Payment Alert", $mailText, "html", '', 'n');
			}

			/* ****** Security Step 6 *******
			* Send a mail alert for the case when an Online form is submitted by a user(mobile) who have already filled another online form.
			*/
			$userMobile = $usersDetail[$unpaidCouponsRow['userId']]['mobile'];
			$data 		= $this->OnlineModel->getPaidOnlineFormsByUser('mobile', $userMobile);
			if($data)
			{
				// send a email alert
				$mailText = "Security Step 5 <br/>Same External Online form has been submitted twice with same mobile number.<br/>Course Id : ".$unpaidCouponsRow['courseId']." and User Id : ".$unpaidCouponsRow['userId']."and User Mobile : ".$userMobile;
				$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $email, "External Form Paytm Payment Alert", $mailText, "html", '', 'n');
			}

			// make the paytm payment
			$couponLib->integratePaytm($unpaidCouponsRow['courseId'], $unpaidCouponsRow['userId'], $unpaidCouponsRow['couponCode'], 1);
			
			// update the payment status of the coupon used
			$this->OnlineModel->updateExternalOFPaymentStatus($unpaidCouponsRow['courseId'], $unpaidCouponsRow['userId'], 'unpaid', 'paid');
		}
		// End:For
	}
        
        function checkUserPaymentStatusPaytm() {
            $this->userStatus = $this->checkUserValidation();
            $courseId = $this->input->post('courseId');
            $this->load->model('onlineparentmodel');
            $this->load->model('onlinemodel');
            $OnlineModel = new OnlineModel();
            if($OnlineModel->getUserPaymentStatusPaytm($this->userStatus[0]['userid'],$courseId)) {
                echo 1;
            }
            else {
                echo 0;
            }
        }

	// one time cron function need to remove after that THIS_CODE_NEEDS_TO_BE_REMOVE_AFTER_RECAT_GOES_LIVE        
	function updatePBTSeoDetailsTable(){
		// get all data
		$this->load->model('onlineparentmodel'); 
		$this->load->model('onlinemodel');
        $onlineModel = new OnlineModel();
		$result = $onlineModel->getPBTSeoDetails();
		
		//$result = array_slice($result,110);
		//_p($result);die;
		if(is_array($result) && count($result) > 0){
			$newDepartmentMapping = array(
				'me' => 'engineering',
				'mca'	=> 'it',
				'masscomm' => 'mass-communication',
				'fashion' => 'design'
				);

			$courseDetails = array();
			$instituteIdsArray = array();
			foreach ($result as $key => $value) {
				$params = explode('-',$value['seoUrl']);
				$length = count($params);
				$departmentName = $params[$length-2];
				$departmentName = ($newDepartmentMapping[$departmentName]!='') ?$newDepartmentMapping[$departmentName] : $departmentName;
				$courseDetails[$value['courseId']] = array(
					'departmentName' => $departmentName,
					'instituteId' => $value['instituteId']
					);
				$instituteIdsArray[$value['instituteId']] = 0;
			}

			$index = 0;
			$maxCount = 10;
			$instituteIds = array();
			foreach ($instituteIdsArray as $instituteId => $instituteDetail) {
				$instituteIds[] = $instituteId;
				$index ++;
				if($index % $maxCount == 0){
					$this->_getInstituteDetails($instituteIds,$instituteIdsArray);
					$instituteIds = array();
				}
			}

			if(is_array($instituteIds) && count($instituteIds) > 0){
				$this->_getInstituteDetails($instituteIds,$instituteIdsArray);
				$instituteIds = array();
			}
			//_p($instituteIdsArray);die;
			foreach ($courseDetails as $courseId => $courseDetail) {
				$seoTitle = $instituteIdsArray[$courseDetail['instituteId']]['seoTitle'];
				$seoUrl = $instituteIdsArray[$courseDetail['instituteId']]['seoUrl'];
				$seoUrl = '/college/'. $seoUrl.'/'.$courseDetails[$courseId]['departmentName'].'-application-form-'.$courseId;
				$seoDescription = 'Apply online for admission to '.strtoupper($courseDetail['departmentName']).' program in '.$instituteIdsArray[$courseDetail['instituteId']]['collegeName'];

				$courseDetails[$courseId] = array(
					'seoTitle' => $seoTitle,
					'seoDescription' => $seoDescription,
					'seoUrl' => $seoUrl
					);
				$seoUrl ='';
				$seoDescription = '';
				$seoUrl = '';
			}
			unset($instituteIdsArray);
			// update table with new seo details 
			$onlineModel->updatePBTSeoDetailsTable($courseDetails);
		}
	}

	// one time cron function need to remove after that THIS_CODE_NEEDS_TO_BE_REMOVE_AFTER_RECAT_GOES_LIVE
	private function _getInstituteDetails($instituteIds,&$instituteIdsArray){
		if(is_array($instituteIds) && count($instituteIds) > 0){
			$this->load->builder("nationalInstitute/InstituteBuilder");
			$instituteBuilder = new InstituteBuilder();
			$instituteRepo = $instituteBuilder->getInstituteRepository();
			$instituteObjs = $instituteRepo->findMultiple($instituteIds);
			foreach ($instituteObjs as $listingObj) {
				$listingName = '';
				$listingNameAppend = '';
				$cityName = '';				
				$cityAppend = '';
				$localityName = '';
				$localityAppend = '';
				$hasCityNameInCollegeName = 0;

				$listingName = $listingObj->getName();
				$listingNameAppend = seo_url($listingName,"-","200",true);				


				if($listingObj->getMainLocation()){
					$cityName = $listingObj->getMainLocation()->getCityName();	
				}else{
					$cityName = '';
				}
				if(stripos($listingName, $cityName) === FALSE && $cityName != ''){
					$hasCityNameInCollegeName = 1;
		            $cityAppend = '-'.seo_url($cityName,"-","150",true);
		        }

		        if($listingObj->getMainLocation()){
					$localityName = $listingObj->getMainLocation()->getLocalityName();
				}else{
					$localityName = '';
				}

		        if(isset($localityName) && $localityName != '' && !empty($localityName)){	
					$localityAppend = '-'.seo_url($localityName,"-","150",true);				
				}
				$url = $listingNameAppend.$localityAppend.$cityAppend;
				$seoTitle = 'Online Application Form - '.$listingName;
				if($hasCityNameInCollegeName ==1){
					$seoTitle .= ', '.$cityName;
				}

				$instituteIdsArray[$listingObj->getId()] = array(
					'seoUrl' =>$url,
					'seoTitle' => $seoTitle,
					'collegeName' => $listingName
					);
			}
		}
	}

	function uploadCommonFunction($validation,$errorDivId,$index,$extraFormatSupport=0,&$data,&$allCheckS)
	{
		if(isset($_FILES) && is_array($_FILES) && isset($data[$validation])){
			$returnData = $this->uploadMedia($data[$validation],$index,$extraFormatSupport);
		
			if(is_array($returnData) && isset($returnData['fileUrl'])){
				$data[$errorDivId] = $returnData['fileUrl'];
			}
			else if(is_array($returnData) && isset($returnData['error'])){
				
				if( ($data['edit']=='true' || $data['edit']==true) && $returnData['error'] == 'Please select a file to upload' ){

				  $allCheckS = true;
				  return 1;
				}
				else{
					$allCheckS = false;
					return $returnData['error'];
				}
			}
	    }
	}

	function uploadCommonFunctionForNonMandatoryFields($validation,$errorDivId,$index,$extraFormatSupport=0,&$data,&$allCheckS)
	{
		if(isset($_FILES) && is_array($_FILES) && isset($data[$validation])){
			$returnData = $this->uploadMedia($data[$validation],$index,$extraFormatSupport);
		
			if(is_array($returnData) && isset($returnData['fileUrl'])){
				$data[$errorDivId] = $returnData['fileUrl'];
			}
			else if(is_array($returnData) && isset($returnData['error'])){
				if($returnData['error'] == 'Please select a file to upload')
				{
					$allCheckS = true;
					return 1;
				}
				else if( ($data['edit']=='true' || $data['edit']==true) && $returnData['error'] == 'Please select a file to upload' ){

				  $allCheckS = true;
				  return 1;
				}
				else{
					$allCheckS = false;
					return $returnData['error'];
				}
			}
	    }
	}

	// Function to get institute details of a online form by primary id column
	function getOtherCoursesDetails() {

		$instituteDetailId = (int) $this->input->post('instituteDetailId');
		$pageType = $this->input->post('pageType');

		if(empty($instituteDetailId)) { 
			return;
		}

		$this->load->model('onlineparentmodel');
		$onlineModel =  $this->load->model('Online/onlineModel');
		$instituteDetail = $onlineModel->getInstituteDetailsById($instituteDetailId, 'true');

		if(!empty($instituteDetail['otherCourses'])) {

			$otherCourses = explode(",",$instituteDetail['otherCourses']);

			if(count($otherCourses) > 0) {

				$this->load->builder("nationalCourse/CourseBuilder");
				$builder = new CourseBuilder();
				$courseRepository = $builder->getCourseRepository();

				$courseDetails = $courseRepository->findMultiple($otherCourses, array('eligibility'));
				if(!empty($courseDetails)) {
					$displayData = array();
					$displayData['courseDetails'] = $courseDetails;
					$displayData['pageType'] = $pageType;
				    $this->load->view('Online/otherCoursesTupple',$displayData);

				}

			}
		}

	}
}
?>

