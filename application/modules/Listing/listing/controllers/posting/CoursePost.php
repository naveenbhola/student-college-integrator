<?php

require APPPATH.'/modules/Listing/listing/controllers/posting/AbstractListingPost.php';

class CoursePost extends AbstractListingPost
{
    private $coursePostModel;
    
    function __construct()
    {
		parent::__construct();
		
		$this->load->library('Subscription_client');
        $subsciptionConsumer = new Subscription_client();
		
        $this->load->model('listing/posting/coursepostmodel');
        $this->coursePostModel = new CoursePostModel($subsciptionConsumer);
    }
    
    function post()
    {
		$startTime = microtime(true);
		$cmsUserInfo = $this->cmsUserValidation();
        
		if($_POST['flow'] == 'edit' || $_POST['flow'] == 'upgrade') {
			$postedCourseId = $this->input->post('courseId');
			$postedInstituteId = $this->input->post('instituteId');
			$clientId = $this->input->post('clientId');
			
			if($postedCourseId == ''){
				echo json_encode(array("Success" => "/enterprise/Enterprise/disallowedAccess"));
				exit();
			}
			if($postedInstituteId == ''){
				echo json_encode(array("Success" => "/enterprise/Enterprise/disallowedAccess"));
				exit();
			}
			
			$this->load->library('cacheLib');
			$cacheLib = new CacheLib;
			$this->load->model('listing/posting/coursedetailsmodel');
			$courseDetailsModel = new CourseDetailsModel($cacheLib);
			$listingData = $courseDetailsModel->getCourseDetails($postedCourseId);
			
			if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
				show_404();
			}
			
			$instituteUser = $this->_userDetails($listingData[0]['userId']);

			$flag = 'disallow';
			if($cmsUserInfo['usergroup'] == 'cms'){
				if($instituteUser['usergroup'] == 'cms'){
					$flag = 'allow';
				}
				else{
					if(!isset($clientId) || $clientId == '') {
						$clientId = $instituteUser['userid'];
					}
					$flag = 'allow';
				}
			}
			else {
				if($cmsUserInfo['userid'] == $instituteUser['userid']){
					$flag = 'allow';
					$clientId = $cmsUserInfo['userid'];
				}
			}
			
			if($flag != 'allow'){
				echo json_encode(array("Success" => "/enterprise/Enterprise/disallowedAccess"));
				exit();
			}
        }
		
        $data = array();
		$data = $this->_prepareCourseData();
        
        // Validate all the locations now for this course..
        $locArray['institute_location_ids'] =  $data['institute_location_ids'];
        $locArray['head_ofc_location_id'] =  $data['head_ofc_location_id'];
        
		$validateLocationFlag = $this->validateLocationInfo($locArray, 'course');
        if($validateLocationFlag[0] == 0) {
            $logoPanelRespArray["Fail"]['location_issue'] = $validateLocationFlag[1];
            echo json_encode($logoPanelRespArray);
            exit;
        }
        
        $data['editedBy'] = $cmsUserInfo['userid'];
        $data['group_to_be_checked'] = $cmsUserInfo['usergroup'];
        
        /*
		 * Lets check if the subscription selected has not been consumed while posting this listing..
		 * This is to ensure that expiry_date should get filled properly (at Listing Publishing) in listtings_main table.
		 */
		$validateSubscriptionFlag = $this->validateSubscriptionInfo($data['group_to_be_checked'], $data['onBehalfOf'], $data['subscriptionId'], $data['clientId']);
        if($validateSubscriptionFlag[0] == 0) {
            $logoPanelRespArray["Fail"]['subscription_issue'] = $validateSubscriptionFlag[1];
			$logoPanelRespArray['subscription_id'] = $validateSubscriptionFlag[2];
        }
        
        $logoArr = array();
		if ($this->input->post('applicationForm') == '') {
			$data['form_upload'] = '';
			$data['ApplicationDocArr']['url'] = '';
		} else {
			$data['form_upload'] = $this->input->post('applicationForm');
			$data['ApplicationDocArr'] = $this->_uploadCourseApplicationDoc();
		}
		
        $data['deleteBrochureFlag'] = $this->input->post('request_brochure_link_delete');
	
        $course_request_broucher = $this->_uploadCourseBrochure();
		if(array_key_exists("Fail", $course_request_broucher)) {
			echo json_encode($course_request_broucher);
			exit;
		}
        
        $data['course_request_brochure_link'] = $course_request_broucher;
		$applicationDocResponse= array();
		if(isset($data['ApplicationDocArr']['error'])) {
			$applicationDocResponse["Fail"]['applicationDoc'] = $data['ApplicationDocArr']['error'];
			echo json_encode($applicationDocResponse);
			exit;
		}
        
        $showDetailsFlags = $this->_getDetailPageFlags($data['subscriptionId']);
		$data = array_merge($data,$showDetailsFlags);
		
        $data['crawled'] = "noncrawled";
		if ($data['dataFromCMS']=="1") {
			$data['moderated'] = "moderated";
			$data['status'] = 'draft';
		} else {
			$data['moderated'] = "unmoderated";
			$data['status'] = 'draft';
		}
		if (!isset($data['packType'])) {
			$data['packType'] = '-10';
		}
		$data['version'] = 1;
		$data['showWiki'] =  isset($data['showWiki'])?$data['showWiki']:'yes';
		$data['showMedia'] =  isset($data['showMedia'])?$data['showMedia']:'yes';
        
        //Intermediate Course Duration
        $tempDuration = preg_replace('/[^A-Za-z0-9\-\/\.]/', ' ', $data['duration']);
        $data['intermediateDuration'] = exec("./duration.sh '".$tempDuration."'");
        if(strlen($data['intermediateDuration']) <= 0){
            $data['intermediateDuration'] = $data['duration'];
        }
        
		if($_POST['flow'] == 'edit' || $_POST['flow'] == 'upgrade') {
            $data['courseId'] = $_POST['courseId'];
        }
		
		/**
		 * Check for duplicate institute posting
		 */
		if($_POST['flow'] != 'edit' && $_POST['flow'] != 'upgrade' && $this->coursePostModel->isDuplicateCoursePosting($data)) {
			echo json_encode(array('Fail' => array('common' => 'Duplicate Listing !!!')));
			return;
		}
		
		try {
			$courseId = $this->coursePostModel->saveCourse($data);
			
			/**
			 * Add topic and event
			 */ 
			$this->load->library('message_board_client');
			$messageBoardClient = new Message_board_client();
			
			$topicDescription = "You can discuss on this event below";
			$requestIp = S_REMOTE_ADDR;
			
			$topicResult = $messageBoardClient->addTopic(1,1,$topicDescription,($data['category_id'] == "" ? 1 : $data['category_id']),$requestIp,'event');
			
			if(isset($topicResult['ThreadID'])){
			   $data['threadId'] = $topicResult['ThreadID'];
			   $this->load->library('event_cal_client');
			   $eventCalClient = new Event_cal_client();
			   $eventCalClient->addEventsForListing('1',$data,$courseId);
			}
			
			if(isset($_POST['previewAction']) && ($this->input->post('previewAction') == 1)) {		
			   echo json_encode(array("Success" => "/enterprise/ShowForms/showPreviewPage/".$data['institute_id']."/course/".$courseId));
			}
			else {
				if(isset($_POST['nextAction']) && ($this->input->post('nextAction') == 1)) {
					echo json_encode(array("Success" => "/enterprise/ShowForms/showCourseForm/".$data['institute_id']."/2"));
				}
				else {
					echo json_encode(array("Success" => "/enterprise/ShowForms/showMediaInstituteForm/institute/".$data['institute_id']."/2"));
				}
			}
		}
		catch(Exception $e) {
			echo json_encode(array('Fail' => array('common' => $e->getMessage())));
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }
    
    function _prepareCourseData()
    {
		$salientFeatures = base64_decode($this->input->post('c_salientFeatures'));
		$cmsUserInfo = $this->cmsUserValidation();
		$flagArray = $this->setFlags($cmsUserInfo);
		$ListingClientObj = new Listing_client();
		
		$data = array();
		parse_str($salientFeatures,$data);
		
		$data['dataFromCMS'] = $flagArray['moderation'];
		$data['packType'] = $flagArray['packType'];
		$data['username'] = $flagArray['userid'];
		$data['institute_location'] = $this->input->post('instituteLocation');
		$data['institute_name'] = $this->input->post('instituteName');
		$data['date_form_submission_older'] = $this->input->post('dateFormSubmissionOlder');
		$data['date_result_declaration_older'] = $this->input->post('dateResultDeclarationOlder');
		$data['date_course_comencement_older'] = $this->input->post('dateCourseComencementOlder');
		$data['subscriptionId'] = $this->input->post('selectedSubs');
		$data['clientId'] = $this->input->post('clientId');
        $data['onBehalfOf'] = $this->input->post('onBehalfOf');
		$data['upgradeCourseForm'] = $this->input->post('upgradeCourseForm');
		
		//Basic Course details
		$data['institute_id'] = $this->input->post('instituteId');
        $data['courseTitle'] = html_entity_decode($this->input->post('c_course_title'));

		$data['institute_location_ids'] = $this->input->post('locationInfoHiddenVar');
		$data['head_ofc_location_id'] = $this->input->post('headOfclocationIdHiddenVar');
		$data['important_date_info_location'] = $this->input->post('important_date_info_location');
		$data['course_contact_details_locationwise'] = $this->input->post('course_contact_details_locationwise');
		
		//mandatory comments	
		$data['mandatory_comments'] = $this->input->post('mandatory_comments');
		$data['cmsTrackUserId'] = $this->input->post('cmsTrackUserId');
		$data['cmsTrackListingId'] = $this->input->post('cmsTrackListingId');
		$data['cmsTrackTabUpdated'] = $this->input->post('cmsTrackTabUpdated');
        $data['course_request_brochure_link_year'] =   $this->input->post('c_brochure_panel_year',true);
		$data['request_brochure_link_delete'] = $this->input->post('request_brochure_link_delete',true);		
		$innerSeparatorChar = "|=#=|";
		$outerSeparatorChar = "||++||";

		$tmpLocIdArray = explode(", ", $this->input->post('locationInfoHiddenVar'));

		$locationFeeInfoArray = explode($outerSeparatorChar, $this->input->post('locationFeeInfoHiddenVar'));
		$locationFeeIDArray = explode($outerSeparatorChar, $this->input->post('locationFeeIDHiddenVar'));

		$multilocationCount = count($tmpLocIdArray);

		for($i = 0; $i < $multilocationCount; $i++) {
			
			$locID = $tmpLocIdArray[$i];
			$key = array_search($locID, $locationFeeIDArray);
			if($key === FALSE) {
				continue;
			}

			$locationFeeInfoSubArray[$i] = explode($innerSeparatorChar, $locationFeeInfoArray[$key]);
			$data['locationFeeInfo'][$locID]['fee_value'] = $locationFeeInfoSubArray[$i][0];
			$data['locationFeeInfo'][$locID]['fee_unit'] = $locationFeeInfoSubArray[$i][1];
			$data['locationFeeInfo'][$locID]['show_fee_disclaimer'] = $locationFeeInfoSubArray[$i][2];
		}

		$approvedBy = $this->input->post('c_approvedBy');
		if(is_array($approvedBy)) {
			$data['approvedBy'] = implode(',', $approvedBy);
		}
		$data['courseType'] = $this->input->post('c_modeOfLearning');

		$source_type = trim($this->input->post('c_source_type', true));
		$source_name = trim($this->input->post('c_source_name', true));
	
		$flow_check = trim($this->input->post('flow',true));
		if((!empty($source_type) && !empty($source_name)) || ($flow_check=='edit' && empty($source_type) && empty($source_name))) {
			$data['source_name'] = $source_name;
			$data['source_type'] = $source_type;
		}	

		//Seo Fields
		$data['listing_seo_url'] = $this->input->post('listing_seo_url');
		$data['listing_seo_title'] = $this->input->post('listing_seo_title');
		$data['listing_seo_description'] = $this->input->post('listing_seo_description');
		$data['listing_seo_keywords'] = $this->input->post('listing_seo_keywords');
		
		//Course Level different cases
		//TEST PREP FOR COURSE
		$tests_prep = $this->input->post('examPrepRelatedExams',true);
		$testsprepArray = array();
		$j = 0;
		$flag_test_prep_other = 0;
		
		$data['tests_preparation_other'] = '';
		$data['tests_required_other'] = '';
		for ($i=0;$i<count($tests_prep);$i++) {
			if ($tests_prep[$i] != "-1") {
				$testsprepArray[$j] = $tests_prep[$i];
				$j++;
			}
			else {
				$flag_test_prep_other = 1;
			}
		}
		$data['tests_preparation'] = implode(",",$tests_prep);
		if ($flag_test_prep_other == 1) {
			$data['tests_preparation_other'] = 'true';
			$data['tests_preparation_exam_name'] =  $this->input->post('examPrepRelatedExamsOther');
		}
		
		//TEST PREP FOR COURSE
		$data['courseLevel'] = $this->input->post('course_level');
		switch(strtolower($data['courseLevel'])) {
			case 'dual degree':
				$data['courseLevel_1'] = $this->input->post('degree_1');
				$data['courseLevel_2'] = $this->input->post('degree_2');
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'degree':
				$data['courseLevel_1'] = $this->input->post('degree');
				$data['courseLevel_2'] = '';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'diploma':
				$data['courseLevel_1'] = $this->input->post('diploma');
				$data['courseLevel_2'] = '';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'certification':
				$data['courseLevel_1'] = '';
				$data['courseLevel_2'] = '';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'vocational':
				$data['courseLevel_1'] = '';
				$data['courseLevel_2'] = '';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			default:
				$data['courseLevel_1'] = '';
				$data['courseLevel_2'] = '';
				break;
		}
		
		if(strtolower($data['courseLevel']) == "exam preparation") {
			$data['practiceTestsOffered1'] = $this->input->post('c_practiceTestsOffered_1');
			$data['practiceTestsOffered2'] = $this->input->post('c_practiceTestsOffered_2');
			$data['practiceTestsOffered3'] = $this->input->post('c_practiceTestsOffered_3');
			$data['practiceTestsOffered4'] = $this->input->post('c_practiceTestsOffered_4');
			$data['practiceTestsOffered5'] = $this->input->post('c_practiceTestsOffered_5');
			$morningClasses = $this->input->post('c_morningClasses');
			$data['morningClasses'] = $morningClasses[0];
			$eveningClasses = $this->input->post('c_eveningClasses');
			$data['eveningClasses'] = $eveningClasses[0];
			$weekendClasses = $this->input->post('c_weekendClasses');
			$data['weekendClasses'] = $weekendClasses[0];
		}

		$data['category_id'] = "";
		$catLength = count($this->input->post('c_categories'));
		$catArray = $this->input->post('c_categories');
		for($i=0; $i < $catLength; $i++) {
			if($catArray[$i] == 0) {
				continue;
			}
			if($data['category_id'] == "") {
				$data['category_id'] = $catArray[$i];
			}
			else {
				$data['category_id'] .= ", ".$catArray[$i];
			}
		}

		$data['duration_value'] = $this->input->post('duration_val');
		$data['duration_unit'] = $this->input->post('duration_type');
		$data['duration'] = $data['duration_value']." ".$data['duration_unit'];
		$data['fees_value'] = $this->input->post('c_fees_amount');
		$data['fees_unit'] = $this->input->post('c_fees_currency');
		$data['fees'] = $data['fees_value']." ".$data['fees_unit'];
		$data['show_fees_disclaimer'] = intval($this->input->post('c_fees_disclaimer')); // LF-4327
		$data['seats_total'] = $this->input->post('seats_total');
		$data['seats_general'] = $this->input->post('seats_general');
		$data['seats_reserved'] = $this->input->post('seats_reserved');
		$data['seats_management'] = $this->input->post('seats_management');
		$data['date_form_submission'] = $this->input->post('date_form_submission');
		$data['date_result_declaration'] = $this->input->post('date_result_declare');
		$data['date_course_comencement'] = $this->input->post('date_course_commence');
		
		$data['flow'] = $this->input->post('flow');
		if($cmsUserInfo['usergroup'] == 'cms'){
			$data['hiddenTags'] = $this->input->post('i_tags');
		}

		//Contact Info
		$data['contact_details_id'] = $this->input->post('contact_details_id');
		$data['contact_name'] = $this->input->post('contact_name');
		$data['contact_main_phone'] = $this->input->post('contact_phone');
		$data['contact_cell'] = $this->input->post('contact_mobile');
		$data['contact_email'] = $this->input->post('contact_email');

		//Wiki Section additions
		$wikiData = $ListingClientObj->getWikiFields('1','course');
		foreach($wikiData as $wikiField){
			$wikiField_key_name = trim($this->input->post($wikiField['key_name']));
			if($data['flow'] == 'edit'){
				if(empty($wikiField_key_name)){
					$wikiField_key_name = ' ';
				}	
			}
			if(strlen($wikiField_key_name) > 0 ){
				$data['wiki'][$wikiField['key_name']] = is_text_in_html_string($wikiField_key_name)?trimmed_tidy_repair_string($wikiField_key_name):'';
			}
		}
		$userCreatedWikisCaptions = $this->input->post('wikkicontent_main');
		$userCreatedWikisDetails = $this->input->post('wikkicontent_detail');

		$data['wiki']['user_fields'] = array();
		for($i = 0; $i < count($userCreatedWikisCaptions); $i++) {
			if(strlen($userCreatedWikisCaptions[$i]) >0) {
				if (( $userCreatedWikisCaptions[$i] != 'Enter Title' ) && ( $userCreatedWikisDetails[$i] != 'Enter Description' ) ) {
					$data['wiki']['user_fields'][$i]['caption'] = trimmed_tidy_repair_string($userCreatedWikisCaptions[$i]);
					$data['wiki']['user_fields'][$i]['caption'] = str_replace("\n", " ", $data['wiki']['user_fields'][$i]['caption']);
					$data['wiki']['user_fields'][$i]['value'] = is_text_in_html_string($userCreatedWikisDetails[$i]) ?trimmed_tidy_repair_string($userCreatedWikisDetails[$i]):'';
				}
			}
		}
		
		//New Additions
		for($i=1; $i<=LDB_COURSE_MAPPING_LIMIT; $i++) {
			$data['courseMapId_'.$i] = $this->input->post('courseMapId_'.$i);
			$data['courseMap_'.$i] = $this->input->post('courseMap_'.$i);
		}
		$data['courseSubcatMap']   = $this->input->post('courseSubcatMap');
		$data['courseCategoryMap'] = $this->input->post('c_categories');
		
		$data['courseSubmitDate'] = $this->input->post('courseSubmitDate');
		$data['courseViewCount'] = $this->input->post('courseViewCount');
		$data['no_Of_Past_Paid_Views'] = $this->input->post('no_Of_Past_Paid_Views');
		$data['no_Of_Past_Free_Views'] = $this->input->post('no_Of_Past_Free_Views');
		
		$affiliatedTo = $this->input->post('c_affiliatedTo');
		if(is_array($affiliatedTo)){
			$data['affiliatedTo'] = implode(',', $affiliatedTo);
		}
		
		$data['affiliatedToIndianUniName'] = $this->input->post('c_affiliatedToIndianUniName');
		$data['affiliatedToForeignUniName'] = $this->input->post('c_affiliatedToForeignUniName');
		$data['accreditedBy'] = $this->input->post('c_accreditedBy');
		$data['entranceExam1'] = $this->input->post('c_entranceExam_1');
		$data['entranceExam2'] = $this->input->post('c_entranceExam_2');
		$data['entranceExam3'] = $this->input->post('c_entranceExam_3');
		$data['entranceExam4'] = $this->input->post('c_entranceExam_4');
		$data['entranceExam5'] = $this->input->post('c_entranceExam_5');
		$data['entranceExamMarks1'] = $this->input->post('c_entranceExamMarks_1');
		$data['entranceExamMarks2'] = $this->input->post('c_entranceExamMarks_2');
		$data['entranceExamMarks3'] = $this->input->post('c_entranceExamMarks_3');
		$data['entranceExamMarks4'] = $this->input->post('c_entranceExamMarks_4');
		$data['entranceExamMarks5'] = $this->input->post('c_entranceExamMarks_5');
		$data['entranceExamMarks5'] = $this->input->post('c_entranceExamMarks_5');
		$data['entranceExamMarksType1'] = $this->input->post('c_entranceExamMarksType_1');
		$data['entranceExamMarksType2'] = $this->input->post('c_entranceExamMarksType_2');
		$data['entranceExamMarksType3'] = $this->input->post('c_entranceExamMarksType_3');
		$data['entranceExamMarksType4'] = $this->input->post('c_entranceExamMarksType_4');
		$data['entranceExamMarksType5'] = $this->input->post('c_entranceExamMarksType_5');
		
		$data['otherEligibilityCriteria'] = $this->input->post('c_otherEligibilityCriteria');
		
		if($this->input->post('c_max_salary') != ''){
			$data['maxSalary'] = $this->input->post('c_max_salary');
		}
		if($this->input->post('c_avg_salary')){
			$data['avgSalary'] = $this->input->post('c_avg_salary');
		}
		if($this->input->post('c_min_salary')){
			$data['minSalary'] = $this->input->post('c_min_salary');
		}

		$data['SalaryCurrency'] = $this->input->post('c_min_salary_currency');

		if($this->input->post('c_freeLaptop')!=''){
			$data['freeLaptop'] = $this->input->post('c_freeLaptop');
		}
		if($this->input->post('c_foreignStudy')){
			$data['foreignStudy'] = $this->input->post('c_foreignStudy');
		}
		if($this->input->post('c_studyExchange')!=''){
			$data['studyExchange'] = $this->input->post('c_studyExchange');
		}
		if($this->input->post('c_jobAssurance')){
			$data['jobAssurance'] = $this->input->post('c_jobAssurance');
		}
		if($this->input->post('c_dualDegree')){
			$data['dualDegree'] = $this->input->post('c_dualDegree');
		}
		if($this->input->post('c_hostel')){
			$data['hostel'] = $this->input->post('c_hostel');
		}
		if($this->input->post('c_transport')){
			$data['transport'] = $this->input->post('c_transport');
		}
		if($this->input->post('c_freeTraining')){
			$data['freeTraining'] = $this->input->post('c_freeTraining');
		}
		if($this->input->post('c_wifi')){
			$data['wifi'] = $this->input->post('c_wifi');
		}
		if($this->input->post('c_acCampus')){
			$data['acCampus'] = $this->input->post('c_acCampus');
		}
		$data['cType'] = $this->input->post('courseType');
		$c_feestypes = $this->input->post('c_feestypes',true) ? $this->input->post('c_feestypes',true) : array();
                $data['useStoredSeoDataFlag'] = ($this->input->post('useStoredSeoDataFlag') == 'on') ? '1' : '0';
        $data['feestypes'] = implode(",", $c_feestypes);
		return $data;
	}
    
    private function _uploadCourseApplicationDoc()
	{
		$logoArr = array();
		if ($this->input->post('applicationForm') == 'upload') {
			$this->load->library('upload_client');
			$uploadClient = new Upload_client();
			$inst_logo = "applicationDoc";
			if(isset($_FILES['course_app_form']['tmp_name'][0]) && ($_FILES['course_app_form']['tmp_name'][0] != '')) {
				$i_upload_logo = $uploadClient->uploadFile($appId,'pdf',$_FILES,array(),"-1","course",'course_app_form');
				if($i_upload_logo['status'] == 1) {
					for($k = 0;$k < $i_upload_logo['max'] ; $k++) {
						$tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
						list($width, $height, $type, $attr) = $tmpSize;
						$logoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
						$logoArr['url']=$i_upload_logo[$k]['imageurl'];
						$logoArr['title']=$i_upload_logo[$k]['title'];
						$logoArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];
						$logoArr['width']=$width;
						$logoArr['height']=$height;
						$logoArr['type']=$type;
					}
				}
				else {
					$logoArr['error'] = $i_upload_logo;
					$logoArr['url'] = "";
				}
			}
			else if(!(isset($_FILES['course_app_form']['tmp_name'][0]) && ($_FILES['course_app_form']['tmp_name'][0] != '')) && ($this->input->post('applicationForm_removed')==1)) {
				$logoArr['url'] = "";
			}
		}
		else if($this->input->post('applicationForm') == 'url') {
			$course_form_url = $this->input->post('course_form_url');
			$logoArr['url'] = $course_form_url;
		}
		return $logoArr;
	}
    
    private function _uploadCourseBrochure()
    {
		// check if institute brochure has been uploaded
		if(array_key_exists('c_brochure_panel', $_FILES) && !empty($_FILES['c_brochure_panel']['tmp_name'][0])) {
			$return_response_array = array();
			// load client library
			$this->load->library('upload_client');
			$uploadClient = new Upload_client();
			// get file data and type check
			$type_doc = $_FILES['c_brochure_panel']['type']['0'];
			$type_doc = explode("/", $type_doc);
			$type_doc = $type_doc['0'];
			$type = explode(".",$_FILES['c_brochure_panel']['name'][0]);
			$type = strtolower($type[count($type)-1]);
			// display error if type doesn't match with the required file types
			if(!in_array($type, array('pdf','jpeg','doc','jpg'))) {
				$return_response_array['Fail']['c_brochure_panel'] = "Only document of type .pdf,.doc and .jpeg allowed";
				return $return_response_array;
			}
			// all well, upload now
			if($type_doc == 'image') {
				$upload_array = $uploadClient->uploadFile($appId,'image',$_FILES,array(),"-1","course",'c_brochure_panel');
			}
			else {
				$upload_array = $uploadClient->uploadFile($appId,'pdf',$_FILES,array(),"-1","course",'c_brochure_panel');
			}
			// check the response from upload library
			if(is_array($upload_array) && $upload_array['status'] == 1) {
				$return_response_array = $upload_array[0]['imageurl'];
			}
			else {
                if($upload_array == 'Size limit of 25 Mb exceeded') {
					$upload_array = "Please upload a brochure less than 25 MB in size";	
                }
				$return_response_array['Fail']['c_brochure_panel'] = $upload_array;
			}
			return $return_response_array;
		}
		else {
			return "";
		}
	}
    
    function _getDetailPageFlags($subscriptionId=0)
    {
		if($subscriptionId == 0 || (strlen($subscriptionId) <=0)) {
			$flags = array();
			$flags['showWiki'] = 'yes';
			$flags['showMedia'] = 'yes';
			return $flags;
		}
		
		$this->load->library('Subscription_client');
		$subsObj = new Subscription_client();
		$features = $subsObj->getFeaturesForSubscription($subscriptionId);
		$flags = array();
		$flags['showWiki'] = strtolower($features['subsFeatures']['Property']['wiki_system']);
		$flags['showMedia'] = strtolower($features['subsFeatures']['Property']['media_display']);
		return $flags;
	}
	
	function _userDetails($userId)
	{
		$this->load->library('register_client');
		$regObj = new Register_client();
		$arr = $regObj->userdetail(1,$userId);
		$userDetails = $arr[0];
		return $userDetails;
	}
}