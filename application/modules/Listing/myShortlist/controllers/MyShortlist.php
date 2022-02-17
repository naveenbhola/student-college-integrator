<?php
class MyShortlist extends MX_Controller {
	
	/**
	* Purpose       : Method to load and initialize some common functionalities
	*/	
	private function _init(){
		$this->config->load('myShortlist/myShortlistConfig');
		$this->config->load('ranking_config');
		$this->load->library('listing/ShortlistListingLib');
		$this->load->library("listing/AbroadListingCommonLib");
		
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepository = $courseBuilder->getCourseRepository();
		
		$this->listingCommonLibObj = new AbroadListingCommonLib();
		$this->myshortlistmodel = $this->load->model("myshortlistmodel");
		$this->load->helper('my_shortlist', 'url');
	}
	
	/**
	* Purpose       : Main method of my-shortlist page
	*/	
	public function index() {
		// initialization
		$this->_init();

		$displayData['validateuser'] = $this->checkUserValidation();
		$displayData['trackForPages']= true;
		$this->checkQueryStringAndRedirectToBaseURL();
   	 	// to get location for find college by exam widget
		$registrationForm      = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB'); 
		$fields                = $registrationForm->getFields();
		$displayData['fields'] = $fields;

		if($displayData['validateuser'] !== 'false') {
			$this->getUserShortListedCoursesData($displayData);
			if(!empty($displayData['shortlistedCoursesIds'])  && !($displayData['coursesWithOnlineForm'])) {
				$displayData['coursesWithOnlineForm'] = $this->myshortlistmodel->findCoursesWithOnlineForm($displayData['shortlistedCoursesIds']);
			}
		}

		$displayData['listingCommonLibObj'] = $this->listingCommonLibObj;
		$displayData['shortlistListingLib'] = new ShortlistListingLib();
		
		//Tracking Code
		$this->shortlistLib = $this->load->library('myShortlist/MyShortlistLib');
		$displayData['beaconTrackData'] = $this->shortlistLib->prepareBeaconTrackData();
		$displayData['m_meta_title'] = "Shortlist Colleges Based on your Interest - Shiksha";
		$displayData['m_meta_description'] = "Not sure how to select a best college?. Shiksha's College shortlisting tool will help you to shortlist colleges based on your budget, eligibility and preferences.";
		$displayData['canonicalURL'] = SHIKSHA_HOME.'/resources/colleges-shortlisting';
		$displayData['questionTrackingPageKeyId'] = 529;
		$displayData['isShortlistPage'] = true;
		$displayData['suggestorPageName'] = 'all_tags';

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_MyShorlist');
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');
		// load view
		$this->load->view('myShortlist/myShortlistOverview',$displayData);
	}

	function redirect301(){
		$url = SHIKSHA_HOME.'/resources/colleges-shortlisting'; // new url
		header("Location: $url",TRUE,301);
		exit;
	}

	public function shortlistMyCourse(){
		if($this->input->is_ajax_request()){
			$validity = $this->checkUserValidation();
			$output = array();
			$courseId  = $this->input->post('courseId',true);

			if(empty($courseId) || !is_numeric($courseId)){
				echo json_encode(array('msg'=>'Invalid courseId'));die;
			}

			$pageType  = $this->input->post('pageType',true);
			$fromLogin = $this->input->post('fromLogin',true);
			$tracking_keyid = $this->input->post('trackingKeyId');
			$alteredPageType = $this->getAlteredPageType($pageType);
			if($validity == 'false'){
				$output = array('msg'=>'loggedOut', 'data'=>array('tracking_keyid'=>$tracking_keyid, 'pageType'=>$pageType, 'courseId'=>$courseId, 'alteredPageType'=>$alteredPageType));
				echo json_encode($output);die;
			}else{
				$userId = $validity[0]['userid'];
				$shortlistListingLib = $this->load->library('listing/ShortlistListingLib');
				$userShortlistedCourses = $this->getShortlistedCourse($userId);

				if(isset($userShortlistedCourses[$courseId]) && $fromLogin == 'yes'){
					$message = 'shortlisted';
					$output = array('msg'=>$message, 'data'=>array('courseId'=>$courseId, 'fromLogin'=>'yes'));
					echo json_encode($output);die;
				}
				$shortlistAction = 'add';
				$message = 'shortlisted';
				if(in_array($courseId, $userShortlistedCourses)){
					$shortlistAction = 'remove';
					$message = 'unshortlisted';
				}
				$data = array();
				$data['userId'] = $userId;
				$data['courseId'] = $courseId;
				$data['tracking_keyid'] = $tracking_keyid;
				$data['shortlistTime'] = date('Y-m-d H:i:s');
				$data['pageType'] = $pageType;
				$data['scope'] = 'national';
				$data['isResponseConverted'] = '1';
				$data['visitorSessionid'] = getVisitorSessionId();
				$sts = $shortlistListingLib->addRemoveCourseShortlist($data, $shortlistAction);
				if($sts){
					$output = array('msg'=>$message, 'data'=>array('courseId'=>$courseId));
				}else{
					$output = array('msg'=>'invalid requestsss');
				}
				echo json_encode($output);die;
			}
		}else{
			echo json_encode(array('msg'=>'session error'));die;
		}
	}

	public function checkCourseForShortlist(){
		if($this->input->is_ajax_request()){
			
			$pageType  = $this->input->post('pageType',true);
			$pageType  = $this->security->xss_clean($pageType);
			$pageType  = htmlentities($pageType);

			if(!verifyCSRF() && $pageType !='courseHomePage') { return false; }

			$validity = $this->checkUserValidation();
			$output = array();
			
			$courseId  = $this->input->post('courseId',true);
			$fromLogin = $this->input->post('fromLogin',true);
			$tracking_keyid = $this->input->post('trackingKeyId',true);

			if(empty($courseId) || !is_numeric($courseId)){
				echo json_encode(array('msg'=>'invalid course'));die;
			}

			if(!empty($tracking_keyid) && !is_numeric($tracking_keyid)){
				echo json_encode(array('msg'=>'invalid tracking_keyid'));die;
			}

			$alteredPageType = $this->getAlteredPageType($pageType);
			if($validity == 'false'){
				$output = array('msg'=>'loggedOut', 'data'=>array('tracking_keyid'=>$tracking_keyid, 'pageType'=>$pageType, 'courseId'=>$courseId, 'alteredPageType'=>$alteredPageType));
				echo json_encode($output);die;
			}else{
				$userId = $validity[0]['userid'];
				$userShortlistedCourses = $this->getShortlistedCourse($userId);
				if(isset($userShortlistedCourses[$courseId])){
					$shortlistListingLib = $this->load->library('listing/ShortlistListingLib');
					$data = array();
					$data['userId']           = $userId;
					$data['courseId']         = $courseId;
					$data['shortlistTime']    = date('Y-m-d H:i:s');
					$data['visitorSessionid'] = getVisitorSessionId();
					$shortlistAction = 'remove';
					$sts = $shortlistListingLib->addRemoveCourseShortlist($data, $shortlistAction);
					$output = array('msg'=>'unshortlisted', 'data'=>array('courseId'=>$courseId));
						echo json_encode($output);die;
				}else{
					$isValidResponseUser = modules::run('registration/RegistrationForms/isValidUser', $courseId, $userId, false, true);
					if($isValidResponseUser == false && stripos($pageType, 'reco') !== false){
						$output = array('msg'=>'invalidUser', 'data'=>array('tracking_keyid'=>$tracking_keyid, 'pageType'=>$pageType, 'courseId'=>$courseId, 'alteredPageType'=>$alteredPageType, 'userAction'=>'shortlist'));
						echo json_encode($output);die;
					}else{
						$output = array('msg'=>'validUser', 'data'=>array('tracking_keyid'=>$tracking_keyid, 'pageType'=>$pageType, 'courseId'=>$courseId, 'alteredPageType'=>$alteredPageType, 'userAction'=>'shortlist'));
						echo json_encode($output);die;
					}
				}
			}
		}else{
			echo json_encode(array('msg'=>'invalid request'));die;
		}
	}

	private function getAlteredPageType($pageType){
		global $pageTypeResponseActionMapping;
		if(!empty($pageTypeResponseActionMapping[$pageType])) {
			return $pageTypeResponseActionMapping[$pageType];
		}
		return $pageType;
	}
	
	public function addToShortlist() {
		$this->_init();
		$shortlistListingLib = $this->load->library('listing/ShortlistListingLib');
		
		$action = $this->input->post('action');
		$data['courseId'] = $this->input->post('courseId');
		$validity = $this->checkUserValidation();
		
		if($validity == 'false') {
			echo json_encode(array('msg'=>'logged out')); return;
		}
		$data['userId'] = $validity[0]['userid'];
		$data['sessionId'] = sessionId();
		
		if($action == 'delete') {
			$data['status'] = 'deleted';
			$data['scope'] = 'national';
			$data['tracking_keyid'] = $this->input->post('tracking_keyid');
		} else if ($action == 'add') {
			$data['status'] = 'live';
			$data['pageType'] = $this->input->post('pageType');
			$data['tracking_keyid'] = $this->input->post('tracking_keyid');
			$data['shortListTime'] = date ( 'Y-m-d H:i:s' );
			$data['scope'] = 'national';
			if($shortlistListingLib->checkIfCourseAlreadyShortlisted($data)) {
				echo json_encode(array('msg'=>'shortlisted')); return;
			}
		}
		
		$numOfCoursesShortlisted = $shortlistListingLib->updateShortListedCourse($data, $action);
		$numOfMBACoursesShortlisted = $this->myshortlistmodel->getShortListedCourseCount($data['userId']);
		
		if($numOfMBACoursesShortlisted == 1 && $action == 'add') {
			$marketingData = array();
			$marketingData['firstname'] = $validity[0]['firstname'];
			$marketingData['lastname'] = $validity[0]['lastname'];
			$marketingData['mobile'] = $validity[0]['mobile'];
			$marketingData['courseId'] = $data['courseId'];
			$this->triggerMailForMarketingCall($marketingData);
		}
		if($numOfCoursesShortlisted > 0 && $action == 'add') {
			$this->triggerMailForMissingData($data);
		}
		echo json_encode(array('courses'=>$numOfCoursesShortlisted, 'action'=>$action)); return;
	}
	
	function triggerMailForMissingData($data) {
		$this->_init();
		$displayData = array();
		$displayData['courseId'] = $data['courseId'];
		$this->getUserShortListedCoursesData($displayData);
		$course = $displayData['shortlistedCourses'][$displayData['courseId']];
		$emptyFields = array();
		if($course){
			//checking eligibility
			$exams = $course->getEligibility();
			if(empty($exams)){
				$emptyFields[] = 'Eligibility';
			}
			
			//checking rank
			if(!isset($displayData['courseRank'][$data['courseId']])) {
				$emptyFields[] = 'Rank';
			}
			
			//checking salary

			if($course->getPlacements()!=''){
		        $avgSalary = $course->getPlacements()->getSalary();
		        $avgSalary = $avgSalary['avg'];
		        if($avgSalary <0){
		        	$emptyFields[] = 'Average salary';	
		        }
		    }else{
		    	$emptyFields[] = 'Average salary';
		    }
			
			//checking fees
			if($course->getFees()){
				if($course->getFees()->getFeesValue() == '') {
					$emptyFields[] = 'Total fees';
				}
			}else{
				$emptyFields[] = 'Total fees';
			}

			//checking application deadline
			$this->OnlineFormUtilityLib = $this->load->library('Online/OnlineFormUtilityLib');
			$OFData = $this->OnlineFormUtilityLib->getOAFBasicInfo($course->getId());
			if($OFData){
				$formSubmissionDate = $OFData[$course->getId()]['of_last_date'];
			}else{
				// get date from application submit date.
				$formSubmissionDate = '';
			}
			$formSubmissionDate = strtotime($course->formSubmissionDate);
			if(!$formSubmissionDate || $formSubmissionDate < 0){
				$emptyFields[] = 'Application deadline';
			}
		}
		
		//insert in table
		$params = array();
		$params['courseId'] = $data['courseId'];
		$params['emptyFields'] = $emptyFields;
		if(!empty($emptyFields)) {
			$result = $this->myshortlistmodel->addMissingField($params);
			
			//send mail
			if($result) {
				//prepare data
				$courseId = $data['courseId'];
				$courseName = $course->getName();
				$insttId = $course->getInstituteId();
				$insttName = $course->getInstituteName();
				$emptyFieldsString = implode(', ', $emptyFields);
				
				//load libraries to send mail
				$this->load->library('Alerts_client');
				$alertClient = new Alerts_client();
				
				$subject = "Missing fields in shortlisted courses";
				$content = "<p>Hi,</p>".
							"<p>Value for fields: ".$emptyFieldsString." is missing, </p>".
							"<p>for course: ".$courseName."(".$courseId."), </p>".
							"<p>of institute: ".$insttName."(".$insttId.").</p>".
							"<p>It has been requested by a user for shortlisting institutes.</p>".
							"<p>- Shiksha Dev</p>";
				
				$alertClient->externalQueueAdd("12", ADMIN_EMAIL, 'amritesh.parmar@shiksha.com', $subject, $content, "html", '', 'n');
			}
		}
	}
	
	function triggerMailForMarketingCall($data) {
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$courseRepository = $courseBuilder->getCourseRepository();
		
		$this->load->library('Alerts_client');
    	$alertClient = new Alerts_client();
		
		$courseObj = $courseRepository->find($data['courseId'],array('basic'));
		
		$emailIdarray = array('neelankshi.w@shiksha.com', 'naziash.m@shiksha.com');
		$subject = 'Institute shortlisted by the user';
		$content = "User name: ".$data['firstname']." ".$data['lastname']."<br>".
					"Mobile: ".$data['mobile']."<br>".
					"Institute - course shortlisted: ".$courseObj->getInstituteName()." - ".$courseObj->getName()."[".$data['courseId']."]<br>".
					"Date of shortlist: ".date("Y-m-d H:i:s");
		
		//headers to send mail in the correct format using mail()
    	$headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <info@shiksha.com>' . "\r\n";
		
		foreach($emailIdarray as $key=>$emailId) {
			$val = mail($emailId, $subject, $content, $headers); //to send mail immediately
			if($val == 'TRUE') {
				$response = $alertClient->externalQueueAdd("12",ADMIN_EMAIL,$emailId,$subject,$content,$contentType="html",date("Y-m-d H:i:s"),$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null,$fromUserName="Shiksha.com",$isSent="sent");
			} else {
				$response = $alertClient->externalQueueAdd("12",ADMIN_EMAIL,$emailId,$subject,$content,$contentType="html",$sendTime="0000-00-00 00:00:00",$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null,$fromUserName="Shiksha.com",$isSent="unsent");
			}
			unset($val);
		}
	}
	
	function getInstituesDataByExamName(){
		$this->_init();
		$displayData['examName']      =  $this->input->post('examName');
		$displayData['cutOff']        =  $this->input->post('cutOff');
		$displayData['location']      =  $this->input->post('location');
		$displayData['tracking_keyid'] = DESKTOP_NL_SHORTLIST_HOME_FINDBYEXAM_SHORTLIST;
		$displayData['validateuser'] = $this->checkUserValidation();
		
		$shortlistListingLib         = new ShortlistListingLib();

		$categoryPageInstitutes = $shortlistListingLib->getInstituteDataFilterWise($displayData);
			
		$courseIds = array();
		foreach($categoryPageInstitutes as $res){
			$course 	= $res->getFlagshipCourse();
			if($course)
			{
				$courseIds[] 	= $course->getId();
				$courseObject[] = $course;
			}
			if($instituteID = $res->getId()) {
			$instituteIds[] = $instituteID;
			}
		}

		//getNaukriData
		$naukriData                           = $shortlistListingLib->getInstitutesNaukriData($instituteIds);
		
		if(empty($categoryPageInstitutes)){
		return ;
		}
		$displayData['courseObject']          = $courseObject;
		$displayData['naukriData']            = $naukriData;
		
		// get the ranks of the courses
		$myshortlistmodel                     = $this->load->model("myshortlistmodel");
		$courseRanks                          = $myshortlistmodel->getCoursesRank($courseIds);
		$courseShortListCounts                = $myshortlistmodel->getShortlistCountOfCourses($courseIds);
		
		$displayData['courseShortListCounts'] = $courseShortListCounts;
		$displayData['courseRanks']           = $courseRanks;
		
		$displayData['listingCommonLibObj']   = $this->listingCommonLibObj;

		$displayData['shortlistListingLib'] = $shortlistListingLib;
		
		
		$this->toPrepareCollegeListingBox($displayData);	
		
		exit;
	}

	function toPrepareCollegeListingBox($displayData){
		$htmlTest = $this->load->view('myShortlist/recommendedShortlistSec',$displayData,TRUE);
		echo $htmlTest;
		exit;
	}

	/**
	 * to fetch the cutoff exam wise
	 * @return string
	 */
	function getCutOffByExam(){
		// get global variables 
		global $appliedFilters;
		global $MBA_SCORE_RANGE;
		global $MBA_SCORE_RANGE_CMAT;
		global $MBA_SCORE_RANGE_GMAT;
		global $MBA_PERCENTILE_RANGE_MAT;
		global $MBA_PERCENTILE_RANGE_XAT;
		global $MBA_PERCENTILE_RANGE_NMAT;
		global $MBA_EXAMS_REQUIRED_SCORES;
		global $MBA_NO_OPTION_EXAMS;

		$examName      =  $this->input->post('examName');


			// if(in_array($examName, $MBA_NO_OPTION_EXAMS)){
			// 	$examScoreContDisplayStyle = "display:none;";
			// }
			
			$placeHolderText = "Cut-off percentile";
			$defaultDropdownValue = "Any";

			if(in_array($examName, $MBA_EXAMS_REQUIRED_SCORES)){
				$placeHolderText = "Cut-off score";
			}

			$scoreRanges = $MBA_SCORE_RANGE;
			if($examName == "CMAT"){
				$scoreRanges = $MBA_SCORE_RANGE_CMAT;
			} else if($examName == "GMAT"){
				$scoreRanges = $MBA_SCORE_RANGE_GMAT;
			} else if($examName == "MAT"){
				$scoreRanges = $MBA_PERCENTILE_RANGE_MAT;
			} else if($examName == "XAT") {
				$scoreRanges = $MBA_PERCENTILE_RANGE_XAT; 
			} else if($examName == "NMAT"){
				$scoreRanges = $MBA_PERCENTILE_RANGE_NMAT;
			}
		
			$cutOffOptionHtml .= "<option value='0'>$placeHolderText</option>";

			foreach($scoreRanges as $key => $value){
				$cutOffOptionHtml .="<option value='$value'>$key</option>";
			}
 		
	 		if(empty($cutOffOptionHtml)) {
	        	$cutOffOptionHtml = "NO CutOff FOUND";
	        }
	        echo  $cutOffOptionHtml;
	}

	/**
	* Purpose       : Method to get the recommendations for myshortlist page.
	* Logic		: 1. Seed for the recommendations should be of FT-MBA subcategory.
	* 		  2. All the recommendations shown should be of FT-MBA subcategory.
	* 		  3. First recommendations are to be fetched based on "shortlisted" + "Courses on which responses are made". If no recommendations are found based on this criteria then only we'll fall-back to the criteria of "viewed courses".
	* Params        : 1. displayData(view array) - Array
	* Author        : Romil Goel
	*/	
	function getRecommedationsData(&$displayData){
		$this->_init();
		$maxRecommendations = $this->config->item("MS_MAX_RECOMMENDATIONS");

		$this->load->library('myShortlist/MyShortlistLib');
		$this->shortlistLib = new MyShortlistLib();
		$this->shortlistLib->prepareDataForRecommedations($displayData,$maxRecommendations);
		$this->load->library('listing/ShortlistListingLib');
		$displayData['shortlistListingLib'] = new ShortlistListingLib();
		$displayData['tracking_keyid'] = DESKTOP_NL_SHORTLIST_HOME_RECO_BOTTOM_SHORTLIST;
		if($displayData['courseObject'])
			echo $this->load->view('myShortlist/recommendedShortlistSec', $displayData, TRUE);
		else
			echo "No Data Found";
	}
        
    function getUserShortListedCoursesData(&$displayData) {
		$userId = 0;
		$this->_init();
		if(empty($displayData['validateuser'])) {
			$displayData['validateuser'] = $this->checkUserValidation();
		} else {
			$displayData['validateuser'] = $this->checkUserValidation();
		}
		
		if($displayData['validateuser'] !== 'false') {
			$this->load->library('myShortlist/MyShortlistLib');
			$this->shortlistLib = new MyShortlistLib();
			$ShortlistListingLib = new ShortlistListingLib();

			$displayData['listingCommonLibObj'] = $this->listingCommonLibObj;
			$myshortlistmodel  = $this->load->model("myshortlistmodel");
			if(empty($displayData['courseId'])) {
				$shortlistedCoursesIds = $myshortlistmodel->getUserShortListedCourses($displayData['validateuser'][0]['userid']);
			} else {
				$shortlistedCoursesIds = array($displayData['courseId']);
			}
			$displayData['shortlistedCoursesIds'] = $shortlistedCoursesIds;

			if(!is_array($shortlistedCoursesIds)){
				$shortlistedCoursesIds = array($shortlistedCoursesIds);
			}
			if(!empty($shortlistedCoursesIds)) {
				$this->rankingLib = $this->load->library('rankingV2/RankingCommonLibv2');
			    $displayData['courseRank'] = $this->rankingLib->getCourseRankBySource($shortlistedCoursesIds);
			    $displayData['courseRank'] = $this->shortlistLib->processCourseRankData($displayData['courseRank']);
				$this->listingcommonlib              = $this->load->library('listingCommon/ListingCommonLib');
                $displayData['checkForDownloadBrochure']    = $this->listingcommonlib->checkForDownloadBrochure($shortlistedCoursesIds);

				//get dashboardconfig for online form
				$this->OnlineFormUtilityLib = $this->load->library('Online/OnlineFormUtilityLib');
				$displayData['onlineApplicationCoursesUrl'] = $this->OnlineFormUtilityLib->getOnlineFormAllCourses();
				if(!empty($displayData['shortlistedCoursesIds']) && !($displayData['coursesWithOnlineForm'])) {
						$displayData['coursesWithOnlineForm'] = $this->myshortlistmodel->findCoursesWithOnlineForm($displayData['shortlistedCoursesIds']);
				}
				$shortlistedCourses = array();
			
				$shortlistedCourses = $this->courseRepository->findMultiple($shortlistedCoursesIds,array('basic','course_type_information','location','eligibility','placements_internships'));
			}
			$this->load->library('nationalCourse/CourseDetailLib');
	    	$courseDetailLib = new CourseDetailLib;
			foreach ($shortlistedCourses as $course) {
				$course->currentLocation = $courseDetailLib->getCourseCurrentLocation($course,'','');
				$allExams = $course->getEligibilityMappedExams();
				foreach ($allExams as $key => $examName) {
					$course->allExams[$examName] = $examName;
				}
				$OFData = $this->OnlineFormUtilityLib->getOAFBasicInfo($course->getId());
				$course->formSubmissionDate = '';
				if($OFData){
					$course->formSubmissionDate = $OFData[$course->getId()]['of_last_date'];
					$course->formSubmissionDate = date('d M `y',strtotime($course->formSubmissionDate));
				}else{
					// get date from application submit date.
					$dateArray = $courseDetailLib->getImportantDatesData($course,false);
					$applicationDate = $this->_processApplicationDate($dateArray);
					if(!empty($applicationDate)){
						$course->formSubmissionDate = $applicationDate;
					}
				}
			}
			$downloadEBrochureApplied = array();
			foreach($shortlistedCourses as $courseId => $course) {
				$shortlistedInstAndCourseIds[$courseId] = $course->getInstituteId();
				if($instId = $course->getInstituteId()) {
					$instituteIds[] = $instId;
					// check user clicked on download e brochure
					if(isset($_COOKIE['applied_'.$courseId]) && $_COOKIE['applied_'.$courseId] == 1) {
						$downloadEBrochureApplied[] = $courseId;
					}
				}
			}

			
			$displayData['downloadEBrochureApplied'] = $downloadEBrochureApplied;
			$displayData['shortlistedCourses'] = $shortlistedCourses;
			$displayData['shortlistedInstAndCourseIds'] = $shortlistedInstAndCourseIds;
			$displayData = $this->populateExamsForSorting($displayData);
			//$displayData = $this->fillSalaryDataInCourseObject($displayData);  // remove it
			$displayData['sortField'] = $this->input->post('sortField');
			$displayData['order'] = $this->input->post('order');
			$displayData['examName'] = $this->input->post('examName');
			$sortedCoursesArray = $this->sortShortlistedCourses($displayData);
			$sortedCoursesArrayCount = count($sortedCoursesArray);
			$shortlistedCoursesCount = count($shortlistedCourses);
			if($sortedCoursesArrayCount == $shortlistedCoursesCount) {
				$shortlistedCourses = $sortedCoursesArray;
			}

			$displayData['shortlistedCourses'] = $shortlistedCourses;
			return $displayData;
		}else {
			return false;
		}
    }

    private function _processApplicationDate($dateArray){
    	$dateString = '';
    	$this->load->helper('listingCommon/listingcommon');
    	foreach ($dateArray as $key => $eventDetails) {
    		if($eventDetails['event_name'] == 'Application Submit Date'){
    			if(!empty($eventDetails['end_year'])){
    				$date = array(
    					'date' => $eventDetails['end_date'],
    					'month' => $eventDetails['end_date'],
    					'year' => $eventDetails['end_date']
    					);
    			}else if(!empty($eventDetails['start_year'])){
    				$date = array(
    					'date' => $eventDetails['start_date'],
    					'month' => $eventDetails['start_month'],
    					'year' => $eventDetails['start_year']
    					);
    			}
    			$dateString = '';
				$dateString = date('`y',strtotime($date['year']."-01-01"));
				if(!empty($date['month'])){
					$dataForMonth = $date['year']."-".$date['month']."-01";
					$dateString = date('M',strtotime($dataForMonth)).' '.$dateString;
				}
				if(!empty($date['date'])){
					$dataForDay = $date['year']."-01-".$date['date'];
					$dateString = date('d',strtotime($dataForDay)).' '.$dateString;
				}
    		}
    	}
    	return $dateString;
    }
	
	public function refreshShortlistedTuples($displayData) {
		$this->_init();
		$this->getUserShortListedCoursesData($displayData);
		$displayData['questionTrackingPageKeyId'] = 529;
		if(!empty($displayData['shortlistedCourses'])) {
			echo json_encode(array('html'=>$this->load->view('shortlistedInstitutes', $displayData, true)));
		} else {
			echo json_encode(array('html'=>''));
		}
		return;
	}

    function sortShortListedCourses($data) {
        $sortField = $data['sortField'];
        $order = $data['order'];
        $examName = $data['examName'];

        $shortlistedCourses = $data['shortlistedCourses'];
        $arrayToSort = array();
        switch($sortField) {
            case 'salary'           : $arrayToSort = $this->extractSalaryDataToSort($data['shortlistedCourses'],$data['institutesWithSalaryData']);
                                      break;
            case 'fees'             : $arrayToSort = $this->extractFeesDataToSort($data['shortlistedCourses']);
                                      break;
            case 'form_submission'  : $arrayToSort = $this->extractFormSubmissionDataToSort($data['shortlistedCourses']);
                                      break;
            case 'eligibility'      : $arrayToSort = $this->extractExamsDataToSort($data['shortlistedCourses']);
                                      break;
        }

        if($order == 'asc') {
            asort($arrayToSort);
        }
        else {
            arsort($arrayToSort);
        }
        $sortedCoursesArray = array();
        foreach($arrayToSort as $id => $val) {
            $sortedCoursesArray[$id] = $shortlistedCourses[$id];
            unset($shortlistedCourses[$id]);
        }

        foreach($shortlistedCourses as $courseId => $course) {
            $sortedCoursesArray[$courseId] = $shortlistedCourses[$courseId];
            unset($shortlistedCourses[$courseId]);
       }
        return $sortedCoursesArray;
    }
        
    function extractSalaryDataToSort($shortlistedCourses,$institutesWithSalaryData) {
        $sortedArray = array();
        foreach($shortlistedCourses as $courseId => $course) {
        	if($course->getPlacements()!=''){
		        $avgSalary = $course->getPlacements()->getSalary();
		        $avgSalary = $avgSalary['avg'];
		        if(isset($avgSalary) == 1)
		        	$sortedArray[$courseId] = $avgSalary;
		    }
        }
        return $sortedArray;
    }

    function extractFeesDataToSort($shortlistedCourses) {
        $sortedArray = array();
        foreach($shortlistedCourses as $courseId => $course) {
        	if($course->getFees()){
	            if($course->getFees()->getFeesValue() != '') {
	                $sortedArray[$courseId] = $course->getFees()->getFeesValue();
	           	}
            }
       }
        return $sortedArray;
    }
    
    function extractFormSubmissionDataToSort($shortlistedCourses) {
        $sortedArray = array();
        $shortlistCourseArr = array_keys($shortlistedCourses);
        $this->OnlineFormUtilityLib = $this->load->library('Online/OnlineFormUtilityLib');
		$OFData = $this->OnlineFormUtilityLib->getOAFBasicInfo($shortlistCourseArr);
        foreach($shortlistedCourses as $courseId => $course) {
            if(isset($OFData[$courseId]['of_last_date']) && $OFData[$courseId]['of_last_date'] != ''){
            	$sortedArray[$courseId] = strtotime($OFData[$courseId]['of_last_date']);
            }else{
                $sortedArray[$courseId] = 0;
            }
        }
        return $sortedArray;
    }
    
    function extractExamsDataToSort($shortlistedCourses) {
        $sortedArray = array();
        foreach($shortlistedCourses as $courseId => $course) {
            $exams = $course->getEligibility(array('general'));
            $exams = $exams['general'];
            if(!empty($exams)) {
                foreach($exams as $exam) {
                    if ($exam->getValue() > 0) {
                        if($exam->getExamName() == $this->input->post('examName'))
                            $sortedArray[$courseId] = $exam->getValue();
                   }
               }
           }
       }
        return $sortedArray;
    }

    function populateExamsForSorting($displayData) {
        foreach($displayData['shortlistedCourses'] as $courseId => $course) {
            $exams = $course->getEligibility(array('general'));
            if(!empty($exams)){
                foreach($exams['general'] as $exam) {
                    if ($exam->getValue() > 0) {
                        $displayData['examForSorting'][$exam->getExamName()][$courseId] = $exam->getValue();
                   }
               }
           }
       }
        $displayData['examNamesForSortLayer'] = array_keys($displayData['examForSorting']);
        return $displayData;
   	}

	/**
	* Purpose       : Ajax API for showing similar courses for a given course
	* Params        : 1. course-id - Integer
	* Author        : Romil Goel
	*/
	function showSimilarInstitutes($courseId){
		// load required files
		// initialization
		$this->_init();
		$displayData['tracking_keyid'] = DESKTOP_NL_SHORTLIST_HOME_SIMILAR_SHORTLIST;
		$courseId 		=  $this->input->post('courseId');
		$sectionName 		=  $this->input->post('sectionName');
		$maxRecommendations 	= $this->config->item("MS_MAX_RECOMMENDATIONS");
		$recommendedCoursesIds 	= array();
		$instituteIds 		= array();
		$courses 		= array($courseId);

		// get the user-data
		$displayData['validateuser'] 	= $this->checkUserValidation();
		$this->load->library('listing/ShortlistListingLib');
		$displayData['shortlistListingLib'] = new ShortlistListingLib();

		$this->load->library('myShortlist/MyShortlistLib');
		$this->shortlistLib = new MyShortlistLib();
		$this->shortlistLib->showSimilarInstitutes($displayData,$courses,$maxRecommendations);


		$displayData['isRecommendationsFlag'] 	= ($sectionName == 'recommendation' || $sectionName == 'shortlistedTuples' || $sectionName == 'find_college' ) ? 1 : 0;

		// get the view
 		$this->toPrepareCollegeListingBox($displayData);
	}

	/**
	* Purpose       :  To update shortlist count on Course page Tab
	* Params        : none
	* Author        : Vinay Airan
	*/

	function getShortlistedCoursesCount() {
		$validity = $this->checkUserValidation();
		
		if(isset($validity[0]['userid'])) {
		$userId = $validity[0]['userid'];
		} else {
			$userId = 0;
		}
		
		if($userId > 0) {
			$this->myshortlistmodel  = $this->load->model("myshortlistmodel");
			$count = $this->myshortlistmodel->getShortListedCourseCount($userId);
			$count = $count > 0 ? $count : 0;
			echo $count ;
		} else {
			echo 0;
		}
	}
	
	/**
	 * Purpose       :  To check if course is shortlisted for a user or not
	 * Params        : User Id, Course Id
	 * Author        : Vinay Airan
	 */
	
	function checkIfCourseIsShortlistedForAUser($userId,$courseId) {
		if($userId > 0) {
			$this->myshortlistmodel  = $this->load->model("myshortlistmodel");
			$count = $this->myshortlistmodel->checkIfCourseIsShortlistForAUser($userId,$courseId);
			echo $count ;
		} else {
			echo 0;
		}
	}
        
    function getCourseReviewsData($courseId,$pageNo = 0) {
        $this->_init();
        $displayData = array();

        if(empty($courseId) || !preg_match('/^\d+$/',$courseId)){
			return;
		}
		
        $courseObj = $this->courseRepository->find($courseId);
        		$displayData['course'] = $courseObj;
        		$displayData['viewData'] = Modules::run('CollegeReviewForm/CollegeReviewController/getAverageRatingAndCountByCourseId',$courseId);
        			$filterArr = array();
        		$filterArr['typeOfListing'] = 'institute';
        		$filterArr['typeOfPage'] = 'reviews';
        		$filterArr['courses'] = array($courseId);
        		$displayData['reviewURL'] =  getSeoUrl($courseObj->getInstituteId(),'all_content_pages',$title="",$filterArr, $flagDbhit='NA',$creationDate='');
        $this->load->view('myShortlist/shortlistedCollegeReviews',$displayData);
    }

	function reportIncorrectData() {
		$this->_init();
		$validity = $this->checkUserValidation ();
		if($validity == 'false') {
			echo json_encode(array('msg'=>'logged out')); return;
		}
		$data['userId'] 	= $validity[0]['userid'];
		$data['courseId'] = $this->input->post('courseId');
		$data['text'] = trim($this->input->post('text'));
		if(empty($data['text'])) {
			echo json_encode(array('msg'=>'empty text field')); return;
		}
		if(strlen($data['text']) > 500) {
			echo json_encode(array('msg'=>'text too long')); return;
		}
		$course = $this->courseRepository->find($data['courseId']);
		$data['courseName'] = $course->getName();
		$data['insttId'] 	= $course->getInstituteId();
		$data['insttName'] = $course->getInstituteName();
		
		$result = $this->myshortlistmodel->reportIncorrectData($data);
		if($result) {
			$this->triggerMailForIncorrectData($data);
		}
		
		echo 1;
	}
	
	function triggerMailForIncorrectData($data) {
		//load libraries to send mail
		$this->load->library('Alerts_client');
		$alertClient = new Alerts_client();
		
		$subject = "Incorrect fields reported by user in shortlisted courses";
		$content = "<p>Hi,</p>".
					"<p>Following has been reported incorrect by a user for shortlisting institutes,</p>".
					"<p>Course: ".$data['courseName']."(".$data['courseId']."), </p>".
					"<p>Institute: ".$data['insttName']."(".$data['insttId'].").</p>".
					"<p>Text: ".$data['text']."</p>".
					"<p>- Shiksha Dev</p>";
		
		$alertClient->externalQueueAdd("12", ADMIN_EMAIL, 'amritesh.parmar@shiksha.com', $subject, $content, "html", '', 'n');
	}

  	public function fetchShortlistedCoursesOfAUser($userId) {
		$this->myshortlistmodel  = $this->load->model("myshortlistmodel");
	    return $this->myshortlistmodel->fetchShortlistedCoursesOfAUser($userId);
	}
	
	public function fetchUserNotifications() {
		$validity = $this->checkUserValidation();
		
		if(isset($validity[0]['userid'])) {
			
			$userId = $validity[0]['userid'];
			$this->myshortlistmodel  = $this->load->model("myshortlistmodel");
			$resultdata = $this->myshortlistmodel->fetchUserNotifications($userId);
			
			$isAllNotificationSeen = true;
			foreach($resultdata as $key=>$result) {
				if(empty($result['is_seen']) || $result['is_seen'] == "") {
					$isAllNotificationSeen = false;
				}
				
				$resultdata[$key]['timeText'] = $this->getTimeTextForDisplay($result['updated']);
			}
			$notificationData['resultdata'] = $resultdata;
			$notificationData['isAllNotificationSeen'] = $isAllNotificationSeen;
			return $notificationData;
		}
	}
	
	/*
	 * Output : time in the form of text. eg. '2 days ago', 'Just now'
	 * Input format of timestamp: Y-m-d H:i:s
	 */
	function getTimeTextForDisplay($timeStamp) {
		$datetime1 = new DateTime();
		$datetime2 = new DateTime($timeStamp);
		
		$timeDiff = $datetime1->diff($datetime2);
		$timeText = "Just now";
		if($timeDiff->m > 0 || $timeDiff->y > 0 || $timeDiff->d > 0 || $timeDiff->h > 0) {
			if( $timeDiff->y > 0) {
				$timeText = ($timeDiff->y > 1 ? $timeDiff->y." years" : $timeDiff->y." year")." ago"; 
			} else if($timeDiff->m > 0) {
				$timeText = ($timeDiff->m > 1 ? $timeDiff->m." months" : $timeDiff->m." month")." ago";
			} else if($timeDiff->d > 0) {
				$timeText = ($timeDiff->d > 1 ? $timeDiff->d." days" : $timeDiff->d." day")." ago";
			} else if($timeDiff->h > 0) {
				$timeText = ($timeDiff->h > 1 ? $timeDiff->h." hrs" : $timeDiff->h." hr")." ago";
			}
		}
		
		return $timeText;
	}
	
	function setNotificationAsSeen($notificationId) {
		$this->myshortlistmodel  = $this->load->model("myshortlistmodel");
		$resultdata = $this->myshortlistmodel->setNotificationAsSeen($notificationId);
		}
		
	function getUserCourseNotesData() {
		//get user id, course id, page no.
		$validity = $this->checkUserValidation();
		if($validity == 'false') {
			echo json_encode(array('msg'=>'logged out')); return;
		}
		$userId = $validity[0]['userid'];
		$courseId = $this->input->post('courseId');
		$page = $this->input->post('page');
		
		//load myshortlist model
		$this->myshortlistmodel = $this->load->model("myshortlistmodel");
		
		//get notes for this user and course
		$displayData['userId'] = $userId;
		$displayData['courseId'] = $courseId;
		$displayData['page'] = $page;
		$displayData['batchSize'] = 2;
		$result = $this->myshortlistmodel->getUserCourseNotesData($userId, $courseId, $page);
		 $noteIdArray = array();
		foreach($result['notes'] as $key => $note) {
			
			$result['notes'][$key]['last_updated_time_text'] = $this->getTimeTextForDisplay($note['last_updated']);
			if(!empty($note['reminder_date'])) {
				$reminderDateObj = new DateTime($note['reminder_date']);
				$result['notes'][$key]['reminder_date_time_text'] = $reminderDateObj->format('d/m/Y');
			}
			$noteIdArray[] = $note['note_id'];
		} 
		
		$displayData['notes'] = $result['notes'];
		$displayData['totalNotesCount'] = $result['count'];
		
		echo json_encode(array('html'=>$this->load->view('myShortlistNotes', $displayData, true),'noteIds'=>json_encode($noteIdArray)));
	}
	
	function addEditRemoveUserCourseNote() {
		
		if(!verifyCSRF()) { return false; }		

		$validity = $this->checkUserValidation();
		if($validity == 'false') {
			echo json_encode(array('msg'=>'logged out')); return;
		}
		$data['userId'] = $validity[0]['userid'];		
		$data['courseId'] = $this->input->post('courseId');
		$data['action'] = $this->input->post('action');
		$data['noteId'] = $this->input->post('noteId');
		$data['noteTitle'] = $this->input->post('noteTitle');
		$data['noteBody'] = $this->input->post('noteBody');
		$data['submitDate'] = $this->input->post('submitDate');
		$data['reminderDate'] = $this->input->post('reminderDate');
		
		//load myshortlist model
		$this->myshortlistmodel = $this->load->model("myshortlistmodel");
		$this->myshortlistmodel->addEditRemoveUserCourseNotes($data);
		
		echo json_encode(array('msg'=>'done')); return;
	}

	/**
	 * [editReminderForNote Edit Reminder for a note]
	 * @author Vinay
	 * @date   2015-03-10
	 * @return [type]     [description]
	 */
	function editReminderForNote(){
		$validity = $this->checkUserValidation();
		if($validity == 'false') {
			echo json_encode(array('msg'=>'logged out')); return;
		}

		$date = $this->input->post('date');
        $noteId = $this->input->post('noteId');
        $this->myshortlistmodel = $this->load->model("myshortlistmodel");
		$status = $this->myshortlistmodel->editReminderForNote($noteId,$date);
		echo json_encode(array('msg'=>$status)); return;
	}


	/**
	 * [removeReminderForNote Remove Reminder for a note]
	 * @author Vinay
	 * @date   2015-03-10
	 * @return [type]     [description]
	 */
	function removeReminderForNote(){
		$validity = $this->checkUserValidation();
		if($validity == 'false') {
			echo json_encode(array('msg'=>'logged out')); return;
		}

		$noteId = $this->input->post('noteId');
        $this->myshortlistmodel = $this->load->model("myshortlistmodel");
		$status = $this->myshortlistmodel->removeReminderForNote($noteId);
		echo json_encode(array('msg'=>$status)); return;
	}

	function trackMyShortListSearch(){

		$userInfo = $this->checkUserValidation();

		$postParams = $this->input->post("postParams");

		//get all variables
		$data                     = array();
		$data['courseId']         = $postParams['courseId'];
		$data['instituteId']      = $postParams['instituteIdSearched'];
		$data['textEntered']      = $postParams['textEntered'];
		$data['zeroResult']       = $postParams['zeroResult'];
		$data['suggestionsCount'] = $postParams['suggestionsCount'];
		$data['source']           = $postParams['source'];
		
		//get user id in case of logged in user
		$data['userId']           = ($userInfo != 'false') ? $userInfo[0]['userid'] : 'NULL';
		
		//call to db
		$this->myshortlistmodel  = $this->load->model("myshortlistmodel");
		$this->myshortlistmodel->trackMyShortListSearch($data);
	}
	
	function sendReminderToUser() {
		$this->validateCron();

		//load myshortlist model
		$this->myshortlistmodel = $this->load->model("myshortlistmodel");
		
		//get all notes with reminder
		$reminderNotes = $this->myshortlistmodel->getAllReminderNotes();
		
		foreach($reminderNotes as $key=>$noteData) {
			$userIds[] = $noteData['user_id'];
			$noteIds[] = $noteData['note_id'];
		}
		$userIds = array_unique($userIds);
		
		if(!empty($userIds)) {
			//load libraries to send mail
			$this->load->library('Alerts_client');
			$alertClient = new Alerts_client();
			
			//getting user details
			$this->load->model("user/usermodel");
			$usermodel_object = new usermodel();
			$userDetails = $usermodel_object->getUsersBasicInfo($userIds);
			
			//send mail to users
			foreach($reminderNotes as $key => $note) {
				$name 					= $userDetails[$note['user_id']]['firstname'];
				$email 					= $userDetails[$note['user_id']]['email'];
				$encodedEmail           = $usermodel_object->getEncodedEmail($email);
				//$redirectURL            = SHIKSHA_HOME.'/my-shortlist?c='.$note['course_id'].'&n='.$note['note_id'];
				$redirectURL            = SHIKSHA_HOME.'/my-shortlist-nav-'.$note['course_id'].'-notes'.'#nav-'.$note['course_id'].'-notes';
				$myshortlistAutoLoginLink = SHIKSHA_HOME.'/index.php/mailer/Mailer/autoLogin/email~'.$encodedEmail.'_url~'.base64_encode($redirectURL);
				
				$reminderDateObj = new DateTime($note['reminder_date']);
				$reminderDateText = $reminderDateObj->format('d M\'y');
				$data['name'] = $name;
				$data['note']['title'] = $note['title'];
				$data['reminderDateText'] = $reminderDateText;
				$data['myshortlistAutoLoginLink'] = $myshortlistAutoLoginLink;
				$data['userId'] = $note['user_id'];
				Modules::run('systemMailer/SystemMailer/shortlistReminderMailer', $email, $subject, $data);
			}
			
			//change status of reminder entries to reminded
			$this->myshortlistmodel->updateReminderNotesStatus($noteIds);
		}
	}

	function checkQueryStringAndRedirectToBaseURL(){
   	 $this->load->library("security");
	 $queryString = $_SERVER['QUERY_STRING'];
	 $queryString = $this->security->xss_clean($queryString);
	 $scriptURL =$_SERVER['SCRIPT_URL'];
	 //allowing for front-end url checking script
   	 if($queryString != '' && strpos($queryString, 'autoUrl=default') === false){
	     redirect(SHIKSHA_HOME.$scriptURL, 'location', 301);
         exit();
	 }
   }

  	////
	// Desc : get shortlisted course by user
	////
	function getShortlistedCourse($userId, $scope='national'){
		$userId = (integer) $userId;
		if(empty($userId) || $userId<=0){
			return array();
		}
		$this->myshortlistmodel = $this->load->model("myshortlistmodel");
		$courseList = $this->myshortlistmodel->getShortlistedCourse($userId, $scope);		
		$courseArr = array();
		foreach ($courseList as $key => $value) {
			$courseArr[$value['courseId']] = $value['courseId'];
		}
		return $courseArr;
	}

	public function getCourseListToShortlist(){
		if($this->input->is_ajax_request()){
			$userData    = $this->checkUserValidation();
			$listingId   = $this->input->post('listingId');
			$listingType = $this->input->post('listingType');
			$this->load->library('nationalInstitute/InstituteDetailLib');
    		$instituteDetailLib = new InstituteDetailLib();
    		$courseList = $instituteDetailLib->getInstituteCourseIds($listingId, $listingType);

    		$coursesToExclude = array();
    		if($userData != 'false'){
    			$userId = $userData[0]['userid'];
    			$coursesToExclude = $this->getShortlistedCourse($userId);
    			if(is_array($coursesToExclude)){
    				$courseList['courseIds'] = array_diff($courseList['courseIds'], $coursesToExclude);
    			}
    		}
    		$this->load->builder("nationalInstitute/InstituteBuilder");
	    	$instituteBuilder = new InstituteBuilder();
	    	$instituteRepo = $instituteBuilder->getInstituteRepository();
	    	$instituteWithCourses = $instituteRepo->findWithCourses(array($listingId => $courseList['courseIds']), array('basic'));
	    	$courseObjs = array();
	    	if(is_object($instituteWithCourses[$listingId])){
	    		$courseObjs = $instituteWithCourses[$listingId]->getCourse();
	    	}
	    	$finalList = array();
	    	foreach ($courseObjs as $obj) {
	    		$offeredBy = '';
	    		if($listingType == 'university'){
					$offeredBy =( $obj->getOfferedByShortName() != '' ? $obj->getOfferedByShortName() :$obj->getOfferedByName());
	    		}
	    		$finalList[] = array('id'=>$obj->getId(), 'name'=>$obj->getName().(($offeredBy!='')?' ('.'offered by '.$offeredBy.')':''));
	    	}
    		echo json_encode(array('courseList' => $finalList));
    		die;
		}
		echo json_encode(array('error'));
	}

	public function getShortlistAskTab(){
		$this->load->helper('/shikshautility_helper');
		$pageNo = !empty($_POST['page_no'])?$this->input->post('page_no',true):0;
		$courseId = isset($_POST['course_id'])?$this->input->post('course_id',true):$courseId;
		$instituteId = isset($_POST['institute_id'])?$this->input->post('institute_id',true):$instituteId;
		$anaRecommendLib = $this->load->library('ContentRecommendation/AnARecommendationLib');
		$myshortlistmodel = $this->load->model("myshortlistmodel");

		$offset = ($pageNo * 8);
		if(!empty($courseId) && preg_match('/^\d+$/',$courseId)){
			$pageData = $anaRecommendLib->forCourse($courseId, array(), 8, $offset);
		}else{
			echo 'Something went wrong, please try again.';return;
		}	
		
		if(!empty($pageData['topContent'])){
			$questionsIds = implode(",", $pageData['topContent']);
			$pageData['qna'] = $myshortlistmodel->getQuestionDetailsForShortlist($questionsIds);
		}

		if(!empty($courseId) && preg_match('/^\d+$/',$courseId)){
			$this->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder = new CourseBuilder();
			$courseRepository = $courseBuilder->getCourseRepository();
	        $pageData['course'] = $courseRepository->find($courseId);
		}
        $pageData['instituteId'] = $instituteId;
        $pageData['courseId'] = $courseId;
        $pageData['pageNo'] = $pageNo;
        $pageData['pageSize'] = 8;
        $pageData['total'] = $pageData['numFound'];
        $pageData['trackingPageKeyId'] = 529;
		echo $this->load->view('CA/myShortlistAnAPage',$pageData);

	}
}
