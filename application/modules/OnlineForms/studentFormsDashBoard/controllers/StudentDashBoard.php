<?php
/**
 * Main class for user dash board, it will load the main dashboard template.
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
include_once 'ManageBreadCrumb.php';
class StudentDashBoard extends MX_Controller {
	// it stores user details of the logged in user
	private $_validateuser;
	// it holdes the reference of breadcrumb library
	private $_manageBreadCrumb_object;
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	private function _init() {
		//set user details
		$this->load->library('Online/courseLevelManager');
		$this->_validateuser = $this->checkUserValidation();
		if(($this->_validateuser == "false" )||($this->_validateuser == "")) {
			header('location:'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']);exit();
		}
		//load the required library
		$this->load->library('StudentDashboardClient');
		$this->load->library('dashboardconfig');
		$this->load->library('recommendation_front_lib');
		$this->load->library('Online_form_client');
		$this->load->library('OnlineFormEnterprise_client');
 		$this->load->library('nationalCourse/CourseDetailLib');
        $this->load->helper(array('shikshautility_helper','image','listingCommon/listingcommon'));
		//instantiate object of bread crumb
		$this->_manageBreadCrumb_object = new ManageBreadCrumb();
		
	}
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function index() {
		// call init method to set basic objects
		$this->_init();
		// set data needs to be send to template
		$data['validateuser'] = $this->_validateuser;
		$data['breadCrumbHTML'] = $this->_manageBreadCrumb_object->renderBredCrumbDetails();
		if($this->_validateuser == 'false') return;
		$form_list = $this->online_form_client->getFormListForUser($this->_validateuser[0]['userid'],'');
                $i=0;
		foreach($form_list as $form) {
			$array['instituteDetails'][0][$i]['onlineFormId'] = $form['onlineFormId'];
			$array['instituteDetails'][0][$i]['userId'] = $this->_validateuser[0]['userid'];
			$i++;
		}

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_OnlineForm','pageType'=>'Dashboard');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$alert = $this->onlineformenterprise_client->getAllAlerts($array,'onlineFormUser');
		$alert = json_decode($alert['alerts'],true);
		$alert = $alert['instituteDetails'][0];
		$count = count($alert);
		for($i=0;$i<$count;$i++) {
			if($form_list[$i]['onlineFormId'] == $alert[$i]['onlineFormId']) {
				$form_list[$i]['alerts'] = 	$alert[$i];
			}
		}
		
		$data['form_list'] = $form_list;
		$data['userId'] = $this->_validateuser[0]['userid'];
		$data['of_departmentName'] = $this->courselevelmanager->getCurrentDepartment();
		//recommendation starts here
		$of_institute_ids_array = json_decode($this->online_form_client->getInstitutesForOnlineHomepage(1,'true',array(),$this->courselevelmanager->getCurrentDepartment()));
		foreach ($form_list as $form) {
			$applied[] = $form['courseId'];
		}
		if(!empty($of_institute_ids_array) && is_array($of_institute_ids_array)){
			$instituteList = $this->studentdashboardclient->renderInstituteListWithDetails($of_institute_ids_array,$data['of_departmentName']);
			foreach ($instituteList as $key=>$inst) {

				$instCourses = $inst->getCourses();

				foreach ($instCourses as $course) {
					if(!in_array($course->getId(),$applied)) {
						$arraya[$key] = $inst;
					}
				}

			}			
			$data['instituteList'] = $arraya;
			$data['institute_features'] = json_decode($this->studentdashboardclient->returnOfInstitutesOfferandOtherDetails($of_institute_ids_array,$data['of_departmentName']),true);
			$data['config_array'] = DashboardConfig::$institutes_autorization_details_array;
			$data['nonEditableForms'] = DashboardConfig::$nonEditableForms;
			$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', $of_institute_ids_array);
			$data['config_array'] += $PBTSeoData;
		}
                /* code addded for pagiantion */
		$data['page_type_for_identification'] = "STUDENT_HOME_PAGE";
		$data['total_number_pages'] = 0;
		$data['count_result'] = count($data['instituteList']);
		if($data['count_result']%INSTITUTE_PER_PAGE == 0) {
			$data['total_number_pages'] = (int)($data['count_result']/INSTITUTE_PER_PAGE);
		} else {
			$data['total_number_pages'] = (int)($data['count_result']/INSTITUTE_PER_PAGE)+1;
		}
		foreach ($data['instituteList'] as $inst_id1=>$instituteList_object1):
		$inst_id1_arry[] = $inst_id1;
		endforeach;
		$data['inst_id1_arry'] = $inst_id1_arry;
		$current_page = strip_tags($_REQUEST['start']);
		$current_page = intval($current_page,10);
		if(!empty($current_page)) {
			$current_page_new = ($current_page/INSTITUTE_PER_PAGE)+1;
		} else {
			$current_page_new = 1;
		}
		$paginationHTML = doPagination($data['count_result'],SHIKSHA_HOME."/studentFormsDashBoard/StudentDashBoard/index?start=@start@&num=@count@",$current_page,INSTITUTE_PER_PAGE,$data['total_number_pages']);
		$data['paginationHTML'] = $paginationHTML;
		$offset = ($current_page_new-1)*INSTITUTE_PER_PAGE;
		$return_array = $this->studentdashboardclient->handlePagination($inst_id1_arry,$data['total_number_pages'],$current_page_new,INSTITUTE_PER_PAGE,$data['of_departmentName']) ;
		if(!empty($return_array) && count($return_array)>0) {
			$data['instituteList'] = $return_array['instituteList'];
			$data['institute_features'] = $return_array['institute_features'];
		}
		/* code ended for pagination */
		// load required view

		//below code used for beacon tracking
		$data['trackingPageKeyId']=167;
		$data['CourseDetailLib'] = new CourseDetailLib;

		//loading library to use store beacon traffic inforamtion
		$data['beaconTrackData'] = $this->studentdashboardclient->prepareBeaconTrackData('home');
		$this->load->view('studentFormsDashBoard/dashboardHomepage',$data);
	}
	/**
	 * renders individula form details and communication history
	 *
	 * @param none
	 * @return void
	 */
	function studentCommunicationHistoryDashboard($userId,$formId,$alert){
                //Check that the User should be the owner of the formId. If not, redirect him to Student dashboard
                $this->onlinesecurity = new \onlineFormEnterprise\libraries\OnlineFormSecurity();
		if (isset($_REQUEST['formId']) && $_REQUEST['formId']!='') {
	                if(!$this->onlinesecurity->checkForm($_REQUEST['formId'])){
        	                header('Location: /studentFormsDashBoard/StudentDashBoard/index');
                	        exit();
	                }
		}

		// call init method to set basic objects
		$this->_init();

		// set data needs to be send to template
		$data['nonEditableForms'] = DashboardConfig::$nonEditableForms;
		$data['validateuser'] = $this->_validateuser;
		$data['form_details'] = $this->online_form_client->getFormListForUser($this->_validateuser[0]['userid'],$_REQUEST['formId']);
		$res = $this->online_form_client->formHasExpired($data['form_details'][0]['courseId']);
		$res = json_decode($res,true);
		if($res[$data['form_details'][0]['courseId']] ==1) {
			$data['form_is_expired'][$data['form_details'][0]['courseId']] = "expired";
		} else {
			$data['form_is_expired'][$data['form_details'][0]['courseId']] = "notexpired";
		}
		global $onlineFormsDepartments;
		$data['gdPiName'] = $onlineFormsDepartments[$data['form_details'][0]['departmentName']]['gdPiName'];
		$array['instituteDetails'][0][0]['onlineFormId'] = $_REQUEST['formId'];
		$array['instituteDetails'][0][0]['userId'] = $_REQUEST['userId'];
		$array['instituteDetails'][0][0]['GDPILocation'] = $data['form_details'][0]['GDPILocation'];
		$array['instituteDetails'][0][0]['GDPIDate'] = $data['form_details'][0]['GDPIDate'];
		$array['instituteDetails'][0][0]['onlineFormId'] = $_REQUEST['formId'];
		$array['instituteDetails'][0][0]['course_name'] = $data['form_details'][0]['courseTitle'];
		$array['instituteDetails'][0][0]['institute_name'] = $data['form_details'][0]['institute_name'];
		$array['instituteDetails'][0][0]['imageSpecifications'] = $data['form_details'][0]['imageSpecifications'];
		$array['instituteDetails'][0][0]['documentsRequired'] = $data['form_details'][0]['documentsRequired'];
		$array['instituteDetails'][0][0]['instituteAddress'] = $data['form_details'][0]['instituteAddress'];
                $array['instituteDetails'][0][0]['instituteEmailId'] = $data['form_details'][0]['instituteEmailId'];
		$alert = $this->onlineformenterprise_client->getAllAlerts($array,'userAll');
		$alert = json_decode($alert['alerts'],true);
		$alert = $alert['instituteDetails'][0];
		$data['form_list'] = $alert;
		$data['inst_id'] = $data['form_details'][0]['instituteId'];
		$data['inst_addrs'] = $data['form_details'][0]['instituteAddress'];
		$data['inst_email'] = $data['form_details'][0]['instituteEmailId'];
		$data['inst_mobile'] = $data['form_details'][0]['instituteMobileNo'];
		$data['instituteLandline'] = $data['form_details'][0]['instituteLandline'];
                $data['profile_data'] = $this->online_form_client->getFormCompleteData(1, $data['validateuser'][0]['userid'],$data['form_details'][0]['courseId']);
		// load payment library
		$data['payment_array'] = $this->online_form_client->getPaymentDetailsByUserId($_REQUEST['userId'],$_REQUEST['formId']);	
		$data['beaconTrackData'] = array(
			'pageIdentifier' 	=> 'studentCommunicationHistoryPage',
			'pageEntityId'	=> $data['form_details'][0]['onlineFormId'],
			'extraData'			=> array(
				'countryId' => 2,
				)
			);
		$this->load->view('studentFormsDashBoard/formdetails',$data);
	}
	/**
	 * Renders user profile details
	 *
	 * @param none
	 * @return void
	 */
	function myProfile($param,$form_id){
		// call init method to set basic objects
		$this->_init();
		$data['breadCrumbHTML'] = $this->_manageBreadCrumb_object->renderBredCrumbDetails();
		// set data needs to be send to template
		$data['validateuser'] = $this->_validateuser;
		$data['form_details'] = $this->online_form_client->getFormListForUser($this->_validateuser[0]['userid'],$form_id);
		if($form_id) {
			$data['courseId'] = $data['form_details'][0]['courseId'];
		} else {
			$data['courseId'] = 0;
		}
		$data['profile_data'] = $this->online_form_client->getFormCompleteData(1, $data['validateuser'][0]['userid'],$data['courseId']);
		$profile_data_keys = array_keys($data['profile_data']); 
		$wecompany_array = $exam_array = array();
		foreach($profile_data_keys as $profile_key) {
			$postition1 = strpos($profile_key,"weCompanyName_mul");
			$postition2 = strpos($profile_key,"graduationExaminationName_mul");
			if($postition1 !==false) {
				$wecompany_array[$profile_key] = $profile_key;	
			} elseif($postition2!==false) {
				$exam_array[$profile_key] = $profile_key;
			}
		}

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_OnlineForm','pageType'=>'Dashboard');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$data['wecompany_array'] = $wecompany_array;
		$data['exam_array'] =  $exam_array;
		if(is_array($data['profile_data']) && count($data['profile_data'])>0){
			$keyArray = array_keys($data['profile_data']);
			foreach($keyArray as $fieldData){
				$data[$fieldData] = $data['profile_data'][$fieldData];
			}
		}
		if(in_array($data['form_details'][0]['status'],array('started','uncompleted','completed'))) {
			$data['showEdit'] ='true';
		}
		if($_REQUEST['preview'] || $_REQUEST['print']) {
			$data['showEdit'] ='';
		}
		if($param != 'formpreview') {
			$field_list= $this->online_form_client->getPageFieldList(array('1','2','3'));
			foreach ($field_list as $field) {
				if(!in_array($field['name'],array('mobileSTDCode','courseCode','courseName'))) {
					$field_array[$field['name']] = $field['name'];
				}
			}
			$percentComplete = 0;
			foreach ($data['profile_data'] as $key=>$value) {
				if(!in_array($key,array('percentComplete','mobileSTDCode','courseCode','courseName','age'))) {
					if(strpos($key,'_mul_')===false) {
						if(!empty($value)) {
							$prof_array[$key] = $key;
							if($this->courselevelmanager->getCurrentLevel() == "UG"){
								$percentComplete = $percentComplete+1.905;
							}else{
								$percentComplete = $percentComplete+1.68;
							}
							
						}
					}
				}
			}
			$data['profile_data']['percentComplete'] = min((int)$percentComplete,100);
			asort($prof_array);
			if(array_key_exists('weTimePeriod', $prof_array)) {
				$field_array1 = $field_array;
				unset($field_array);
				foreach ($field_array1 as $key=>$value) {
					if($key != 'weTill') {
						$field_array[$key] = $key;
					}
				}
			}
          		if(array_key_exists('weTill', $prof_array)) {
				$field_array1 = $field_array;
				unset($field_array);
				foreach ($field_array1 as $key=>$value) {
					if($key != 'weTimePeriod') {
						$field_array[$key] = $key;
					}
				}
			}
			$diff_array = array_diff($field_array, $prof_array);
			$data['diff_array'] = array_unique($diff_array);
		}

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_OnlineForm','pageType'=>'My Profile');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below code used for beacon tracking
        $data['beaconTrackData'] = $this->studentdashboardclient->prepareBeaconTrackData('myProfile');
		if($param == 'formpreview') {
			$this->load->view('studentFormsDashBoard/formpreview',$data);
		} else {
			$this->load->view('studentFormsDashBoard/myprofile',$data);
		}
	}
	/**
	 * This method cancels payment
	 *
	 * @param none
	 * @return void
	 */
	function cancelPaymentRequest($form_id,$instituteId){
		$this->_init();
		$userAndFormIds['information'] = array(array('userid'=>$this->_validateuser[0]['userid'],'formid'=>$form_id));
		$userAndFormIds = json_encode($userAndFormIds);
		$result = $this->onlineformenterprise_client->sendAlertFromEnterpriseToUser($userAndFormIds,22,$instituteId,'');
		$this->load->library('online_form_mail_client');
		$this->online_form_mail_client->run($this->_validateuser[0]['userid'],$form_id,"form_cancellation");

	}
       /**
	 * This method updates the score
	 *
	 * @param none
	 * @return void
	 */
	function updateScore($form_id,$instituteId,$courseId,$userId){
		$this->_init();
		$userAndFormIds['information'] = array(array('userid'=>$userId,'formid'=>$form_id));
		$userAndFormIds = json_encode($userAndFormIds);
		$result = $this->onlineformenterprise_client->sendAlertFromEnterpriseToUser($userAndFormIds,70,$instituteId,'');
		$result = $this->onlineformenterprise_client->sendAlertFromEnterpriseToUser($userAndFormIds,71,$instituteId,'');
		$this->load->library('online_form_mail_client');
		$this->online_form_mail_client->run($userId,$form_id,"update_score_template");

		$insertEditData = array(array('userId'=>$userId,'onlineFormId'=>$form_id));
		$onlinepaymentmodel = $this->load->model('Online/onlinepaymentmodel');
		$onlinepaymentmodel->insertData($insertEditData);

	}
}
?>
