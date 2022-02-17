<?php
/*

   Copyright 2013-14 Info Edge India Ltd

   $Author: Pranjul

   $Id: CampusAmbassor.php

 */

class CampusAmbassador extends MX_Controller
{

        function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array(  $helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();

	}

	/*
	 * Loads the Profile for CA.
	 * Gets the data from model and with the help of library , formats the data for profile. 
	 */
	function getCAProfileForm($programName, $progId){
		if(!is_numeric($progId)  || $progId <= 0){
			show_404();exit();
		}
		else if($progId == 1 || $progId == 11){
			$this->getCAProfileFormForMBA($progId);
		}
		else{
			$this->getCAProfileFormAllCategories($progId);
		}
		
	}

	function getCAProfileFormAllCategories($progId){
		$this->init();
		$this->load->library('CAEnterprise/CAUtilityLib');
		$caUtilityLib =  new CAUtilityLib;
		
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
	
		// Get CA data using UserId
		$this->load->model('camodel');
		$this->CAModel = new CAModel();
		//Check if Campus Connect Program is enabled, if yes then get entityType and Id of the program
		$programData = $this->CAModel->getCCProgramDetails($progId);
		if(empty($programData)){
			show_404();exit();
		}
		$result = $this->CAModel->getAllCADetails($userId);
		// Formatting CA data to get institute,course and location name
		$resultCA = $caUtilityLib->formatCAData($result);
		$userData = $resultCA[0]['ca'];
		$userData['crIdExist'] = (is_array($userData) && !empty($userData))?'yes':'no';
		$userData['validateuser'] = $this->userStatus;
		$userData["userId"] = $userId;
		$isdCode = new \registration\libraries\FieldValueSources\IsdCode;
		$userData['isdCode'] = $isdCode->getValues();
		if(!empty($resultCA)) {
			$courseId = $resultCA[0]['ca']['mainEducationDetails'][0]['courseId'];
                        $landingPageUrl = Modules::run('CA/CADiscussions/getCourseUrl',$courseId);
			$userData["landingPageUrl"] = $landingPageUrl;
			$userData["edit"] = 1;
		}else {
			$userData["edit"] = 0;
		}
		$userData['examsTaken'] = array('JEE','AUEEE','AEEE','AISECT Joint Entrance','BITSAT','UPSEE','UPES-EAT','AP EAMCET','TS EAMCET','KCET','PESSAT','VITEEE','NATA','WBJEE','RPET','COMEDK','CUSAT','TNEA','SRMJEE','LPU-NEST','KEAM','KIITEE','JCECE','MU-OET','GCET', "Haven't taken any exam");
		$mentorQues = $this->CAModel->getMentorQuestions($progId);
		$mentorAns = $this->CAModel->getMentorAnswers($userId);
		$quesIdArr = array();
		foreach($mentorQues as $ques)
		{
			$quesIdArr[] = $ques['qid'];
		}
		$quesIds = implode(',', $quesIdArr);
		if(!empty($quesIds)){
			$mentorSampleAns = $this->CAModel->getMentorSampleAnswers($quesIds);
		}
		$userData['mentorQues'] = $mentorQues;
		$userData['mentorAns'] = $mentorAns;
		$userData['mentorSampleAns'] = $mentorSampleAns;
		//code added for tracking purpose
		$userData['programId'] = $progId;
		$userData['entityId'] = $programData[0]['entityId'];
		$userData['entityName'] = $programData[0]['entityType'];  
		$userData['programName'] = $programData[0]['programName']; 
		
		//below line is used for conversion tracking purpose
		$userData['trackingPageKeyId']=169;

		//below lines used for beacon tracking purpose
		$CAHelper = $this->load->library('CA/CAHelper');
		$userData['beaconTrackData'] = $CAHelper->prepareBeaconTrackData($programData[0],$progId,$userId);

		if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='accepted'){
			header("Location:".CAMPUS_REP_DASHBOARD_URL,TRUE,301);
                        exit;
		}
		else if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='draft'){
			$userData['showLayer'] = 'YES';
			$userData['status'] = 'draft';
			$userData['redirectionURL'] = SHIKSHA_HOME.$programData[0]['redirectURL'];
			$this->load->view('mentorship/CAMentorshipProfileForm',$userData);	
		}
		else if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='deleted'){
			$userData['showLayer'] = 'YES';
			$userData['status'] = 'rejected';
			$this->load->view('mentorship/CAMentorshipProfileForm',$userData);	
		}
        else if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='removed'){
                $userData['showLayer'] = 'YES';
                $userData['status'] = 'deleted';
                $this->load->view('mentorship/CAMentorshipProfileForm',$userData);
        }
		else{
			$this->load->view('mentorship/CAMentorshipProfileForm',$userData);	
		}
	}

	function getCAProfileFormForMBA($progId){
		$this->init();
		$this->load->library('CAEnterprise/CAUtilityLib');
		$caUtilityLib =  new CAUtilityLib;
		$instSuggVal = true;		

		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		
		// Get CA data using UserId
		$this->load->model('camodel');
		$this->CAModel = new CAModel();
		$programData = $this->CAModel->getCCProgramDetails($progId);
		if(empty($programData)){
			show_404();exit();
		}
		if($userId > 0 && !empty($userId)){
			$result = $this->CAModel->getAllCADetails($userId);

			$resultCA = $caUtilityLib->formatCAData($result);		// Formatting CA data to get institute,course and location name
		}else{
			$resultCA = array();
		}	
		$userData = $resultCA[0]['ca'];
		$userData['crIdExist'] = (is_array($userData) && !empty($userData))?'yes':'no';
		$userData['validateuser'] = $this->userStatus;
		$userData["userId"] = $userId;
		$isdCode = new \registration\libraries\FieldValueSources\IsdCode;
		$userData['isdCode'] = $isdCode->getValues();
		$userCountry = \registration\libraries\RegistrationHelper::getUserCountryByIP(); 
		$userData['countryISD'] = $userCountry;
		$userData['programId'] = $progId;
		$userData['programName'] = $programData[0]['programName'];
		$userData['entityId'] = $programData[0]['entityId'];
		$userData['entityName'] = $programData[0]['entityType'];
		
		// beacon tracking purpose
		$CAHelper = $this->load->library('CA/CAHelper');
		$userData['beaconTrackData'] = $CAHelper->prepareBeaconTrackData($programData[0],$progId,$userId);
		
		//below line i sused for conversiontracking purpose
		$userData['trackingPageKeyId']=168;

		if(!empty($resultCA)) {
                       $courseId = $resultCA[0]['ca']['mainEducationDetails'][0]['courseId'];
                       $landingPageUrl = Modules::run('CA/CADiscussions/getCourseUrl',$courseId);

			$userData["landingPageUrl"] = $landingPageUrl;
			$userData["edit"] = 1;
		}else {
			$userData["edit"] = 0;
		}

		if($userId > 0 && !empty($userId)){
			$this->load->model('CollegeReviewForm/collegereviewmodel');
			$collegereviewmodel = new CollegeReviewModel;
			$reviewStatus = $collegereviewmodel->getStatusOfReview($userId);
		}	
		
		$loadForm2 = false;
		error_log("CR Form Section: User Id = ".$userId.' | '."CR Id exist =".$userData['crIdExist'].' | '." CR Profile Status =".$userData['profileStatus'].' | '."CR Review Status =".$reviewStatus."\n", 3, LOG_CR_FORM_FILE_NAME);

		if($userData['entityName'] == 'generic'){
			$mainEducationDetails = reset($userData['mainEducationDetails']);
			$genericData['insName'] = $mainEducationDetails['insName'];
			$genericData['courseId'] = $mainEducationDetails['courseId'];
			$genericData['yearOfGrad'] = $mainEducationDetails['yearOfGrad'];
			$genericData['locationId'] = $mainEducationDetails['locationId'];
			$genericData['instituteId'] = $mainEducationDetails['instituteId'];
			$genericData['stateId'] = $mainEducationDetails['stateId'];
			$genericData['cityId'] = $mainEducationDetails['cityId'];
			$genericData['localityId'] = $mainEducationDetails['localityId'];
			$instSuggVal = false;
			$isShikshaInstitute = 'YES';

		}
		if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='draft' && $reviewStatus=="")
		{
			error_log("==============Condition 1");
			
			$ratingParam = modules::run('CollegeReviewForm/CollegeReviewForm/getDefaultReviewRatingForm');	//will get default form MBA form
			$motivationFactor = modules::run('CollegeReviewForm/CollegeReviewForm/getDefaultMotivationList');
			$this->load->view('CollegeReviewForm/campusRepReviewForm',array('userId'=>$userId,'validateuser'=>$userData['validateuser'],'landingPageUrl'=>$landingPageUrl,'ratingParam'=>$ratingParam, 'motivationFactor'=>$motivationFactor, 'reviewFormName'=>'', 'entityName'=>$programData[0]['entityType'], 'entityId'=>$programData[0]['entityId'], 'pageType' => 'letsIntern', 'genericData' => $genericData, 'instSuggVal' => $instSuggVal, 'isShikshaInstitute' => $isShikshaInstitute));

		}
		else if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='accepted'){
			error_log("==============Condition 2");
			header("Location:".CAMPUS_REP_DASHBOARD_URL,TRUE,301);
                        exit;
		}
		else if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='draft'){
			error_log("==============Condition 3");
			$userData['showLayer'] = 'YES';
			$userData['status'] = 'draft';
			$userData['redirectionURL'] = SHIKSHA_HOME.$programData[0]['redirectURL'];
			$this->load->view('CA/CANewProfileForm',$userData);	
		}
		else if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='deleted'){
			error_log("==============Condition 4");
			$userData['showLayer'] = 'YES';
			$userData['status'] = 'rejected';
			$this->load->view('CA/CANewProfileForm',$userData);	
		}
        else if($userId>0 && $userData['crIdExist']=='yes' && $userData['profileStatus']=='removed'){
			error_log("==============Condition 5");
			$userData['showLayer'] = 'YES';
            $userData['status'] = 'deleted';
            $this->load->view('CA/CANewProfileForm',$userData);
                }
		else{
			error_log("==============Condition 6");
			$this->load->view('CA/CANewProfileForm',$userData);	
		}
	}
	
	function getMentorshipProfileForm($programName, $programId){
		$url = SHIKSHA_HOME."/CA/CampusAmbassador/getCAProfileForm/".$programName."/".$programId;
		redirect($url,'location',301);
	}

	function index(){

	}
	
	/**
	 * Get the CA Profile Data from Repository
	 */
	function getCAProfileData(){
		$this->load->builder('CABuilder','CA');
		$caBuilder = new CABuilder;
		$this->caRepository = $caBuilder->getCARepository();
		$caDetails = $this->caRgetCAProfileFormepository->getCADetails($courseId);
		return $caDetails;
	}
	
	/**
	 * Get locations for Institute using institute Id
	 * Constructs the locations using location name , city name, state and country 
	 */
	function getLocationForInstitute(){
		$instituteDetailLib   = $this->load->library('nationalInstitute/InstituteDetailLib');
		$institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');

		$instituteId                = $this->input->post('instituteId');
		// get all courses attached to given institute Id
		$courseList                 = $instituteDetailLib->getAllCoursesForInstitutes($instituteId);
		$courseList                 = $courseList['courseIds'];

		$courseWiseLocations 		= $institutedetailmodel->getCoursesLocations($courseList);

		$locationWiseCourse = array();
		foreach ($courseWiseLocations as $courseId => $locationIds) {
			foreach ($locationIds as $key => $locationId) {			
				$locationWiseCourse[$locationId][] = $courseId;
			}
		}

		$locationsMappedToCourse    = array_values(array_keys($locationWiseCourse));

		if($instituteId > 0){
			$this->load->builder("nationalInstitute/InstituteBuilder");
			$instituteBuilder   = new InstituteBuilder();
			$instituteRepo      = $instituteBuilder->getInstituteRepository();
			$result             = $instituteRepo->find($instituteId,array('location'));
			$instituteLocations = $result->getLocations();
			$data               = array();
			//_p($instituteLocations);die;
			foreach($instituteLocations as $key => $location){
				if(!in_array($location->getLocationId(), $locationsMappedToCourse))
					continue;

				$locationId = $location->getLocationId();
				//prepare location String
				$data[$locationId] = $this->prepareLocationString($location);
				$data[$locationId]['courses'] = $this->prepareCourseString($locationWiseCourse[$locationId]);
			}
			echo json_encode($data);
		}else{
            echo "";
        }		
	}	

	public function prepareLocationString($locationObj){
		$locationStr   = "";
		$locationData  = array();
		$locationArray = array();
		if($locationObj->getLocalityName()){
			$locationData[] = $locationObj->getLocalityName();				
		}

		if($locationObj->getCityName()){
			$locationData[] = $locationObj->getCityName();
		}

		if($locationObj->getStateName()){
			$locationData[] = $locationObj->getStateName();
		}			
		$locationData[] = 'India';

		if(count($locationData) > 0){
			$locationStr                    = implode(", ", $locationData);
			$locationArray['location_id']   = $locationObj->getLocationId();
			$locationArray['location_name'] = $locationStr;
		}

		return $locationArray;
	}

	public function prepareCourseString($courseList){
		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepo    = $courseBuilder->getCourseRepository();
        $courseObjects = $courseRepo->findMultiple($courseList,array("basic"),true);
        $courseData    = array();
        foreach ($courseObjects as $courseId => $course) {
        	$courseData[$courseId]['id'] = $courseId;
        	$courseData[$courseId]['course_name'] = $course->getName();
        }
        return $courseData;
	}
	

	function getAllCoursesByInstitute(){
		$instituteDetailLib   = $this->load->library('nationalInstitute/InstituteDetailLib');
		$institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');	
		$instituteId                = $this->input->post('instituteId');

		$results = $institutedetailmodel->getInstituteNameById($instituteId);

		$name = array();

		foreach ($results as $result) {
			$name[] = $result['name'];
		}

		$instituteIdsArray = $institutedetailmodel->getAllInstituteBySameName($name);
		
		$instituteIds = array();
		foreach ($instituteIdsArray as $institute) {
			$instituteIds[] = $institute['listing_id'];
		}

		// get all courses attached to given institute Id
		$courseListForAllInstitute                 = $instituteDetailLib->getAllCoursesForInstitutes($instituteIds);

		if(count($instituteIds)>1)
		{
			$courseList = array();
			foreach ($courseListForAllInstitute as $key => $value) {
				if(!empty($value['courseIds']))
				{
					foreach ($value['courseIds'] as $course) {
					$courseList[] = $course;
					}
				}
			}
		}
		else
		{
			$courseList = $courseListForAllInstitute['courseIds'];
		}

		
		$courseWiseLocations 		= $institutedetailmodel->getCoursesLocations($courseList);
		

		$locationWiseCourse = array();
		foreach ($courseWiseLocations as $courseId => $locationIds) {
			foreach ($locationIds as $key => $locationId) {			
				$locationWiseCourse[$locationId][] = $courseId;
			}
		}

		$locationsMappedToCourse    = array_values(array_keys($locationWiseCourse));
		if($instituteId > 0){
			$listingLocInfo = $institutedetailmodel->getMultipleListingLocationInfo($locationsMappedToCourse);
			$locationsMap    = $stateIds = $cityIds = $localityIds = array();
			foreach ($listingLocInfo as $key => $val) {
				$mapId                           = $val['state_id']."_".$val['city_id']."_".$val['locality_id'];
				if(isset($locationsMap[$mapId])){
					$locationsMap[$mapId]['courses'] = array_merge($locationsMap[$mapId]['courses'],$locationWiseCourse[$val['listing_location_id']]);					
				}else{
					$locationsMap[$mapId]['courses'] = $locationWiseCourse[$val['listing_location_id']];					
				}
				
				$stateIds[$val['state_id']]      = 1;
				$cityIds[$val['city_id']]        = 1;
				
				if($val['locality_id'])
					$localityIds[$val['locality_id']] = 1;
			}


			$this->load->builder('LocationBuilder','location');
			$locationBuilder    = new LocationBuilder;
			$locationRepository = $locationBuilder->getLocationRepository();
			$stateObjs = $cityObjs = $localityObjs = '';

			if($stateIds)
				$stateObjs          = $locationRepository->findMultipleStates(array_keys($stateIds));
			
			if($cityIds)
				$cityObjs           = $locationRepository->findMultipleCities(array_keys($cityIds));
			
			if($localityIds)
				$localityObjs       = $locationRepository->findMultipleLocalities(array_keys($localityIds));


			foreach ($locationsMap as $locMap => $courses) {
				$data[$locMap]['location_name'] = $this->prepareLocationStringV1($locMap,$stateObjs,$cityObjs,$localityObjs);
				$data[$locMap]['courses'] = $this->prepareCourseStringV1($courses['courses'],$instituteId);
			}	

			echo json_encode($data);
		}else{
			echo "";			
		}
		die;
	}	


	public function prepareLocationStringV1($locMap,$stateObjs,$cityObjs,$localityObjs){
		$locationStr   = "";		
		$locationData  = array();

		list($stateId,$cityId,$localityId) = explode("_", $locMap);
		if($localityId && is_object($localityObjs[$localityId])){
			$locationData[]  = $localityObjs[$localityId]->getName();			
		}

		if($cityId && is_object($cityObjs[$cityId])){
			$locationData[]  = $cityObjs[$cityId]->getName();			
		}

		if($stateId && is_object($stateObjs[$stateId])){
			$locationData[]  = $stateObjs[$stateId]->getName();			
		}

		if(count($locationData) > 0){
			$locationStr                    = implode(", ", $locationData);
		}

		return $locationStr;
	}

	public function prepareCourseStringV1($courseList,$instituteId){
		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepo    = $courseBuilder->getCourseRepository();
        $courseObjects = $courseRepo->findMultiple($courseList,array("basic"),true);
        $courseData    = array();       

        foreach ($courseObjects as $courseId => $course) {
			$courseData[$courseId]['id']          = $courseId;
			$courseData[$courseId]['course_name'] = $course->getName().(($course->getOfferedById() != '' && $instituteId != $course->getOfferedById() && $course->getOfferedByShortName() != '') ? ', '.$course->getOfferedByShortName() : '');			
        }
        return $courseData;
	}
	
	/**
	 * Get Courses using institute and location id.
	 */
	function getCoursesForInstituteAndLocation() {

		$instId = $this->input->post('instituteId');//_p($instId);
		$locationId = $this->input->post('locationId');//_p($locationId);die;
		$formName = $this->input->post('formName');
		$entityName = $this->input->post('entityName');//_p($instId);
		$entityId = $this->input->post('entityId');//_p($instId);

		if(empty($instId) || empty($locationId) || !is_numeric($instId)){
			echo json_encode('instituteId or instituteLocationId is empty');
			exit;
		}

		$courseMappingArray = array();

		$this->load->library('nationalCourse/CourseDetailLib');
	    $courseDetailLib = new CourseDetailLib;
	    $courseList = $courseDetailLib->getCourseForInstituteLocationWise($instId, array($locationId));

		$courseList = array_unique($courseList[$locationId]);

		$filteredCourseList = array();
		if(!empty($courseList) && $formName != 'collegeReviewRating'){
			if(empty($entityId) || empty($entityName)){
				echo json_encode('entityId or entityName is empty');
				exit;
			}
			$courseAttributeData = $this->_getCourseTypeInformation($courseList);
			switch ($entityName) {
				case 'stream':
					$filteredCourseList = $this->_filterCoursesOnStream($courseAttributeData, $entityId);
					break;
				case 'substream':
					$filteredCourseList = $this->_filterCoursesOnSubstream($courseAttributeData, $entityId);
					break;
				case 'baseCourse':
					$filteredCourseList = $this->_filterCoursesOnBaseCourse($courseAttributeData, $entityId);
					break;
			}
		}
		if(empty($filteredCourseList)){
			$filteredCourseList = $courseList;
		}

		$this->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
		$courseRepository = $builder->getCourseRepository();
		if(is_array($filteredCourseList) && count($filteredCourseList)>0){
			$courses = $courseRepository->findMultiple($filteredCourseList);
			foreach ($courses as $courseId => $courseObj) {
				$courseArray = array();
				$courseArray['courseId'] = $courseId;
				$courseArray['courseName'] = $courseObj->getName();
				if(!empty($courseArray['courseName'])){
					if($courseObj->getOfferedById() != '' && $courseObj->getOfferedById() != $instId && $courseObj->getOfferedByShortName() != ''){
						$courseArray['courseName'] .= ', '.$courseObj->getOfferedByShortName();
					}
					$courseMappingArray[] = $courseArray;
				}
			}
		}
		echo json_encode($courseMappingArray);
	}

	private function _filterCoursesOnStream($courseAttributeData, $entityId){
		$filteredList = array();
		foreach ($courseAttributeData as $courseId => $courseAttributes) {
			if($entityId == $courseAttributes['stream_id']){
				$filteredList[] = $courseId;
			}
		}
		return $filteredList;
	}

	private function _filterCoursesOnSubstream($courseAttributeData, $entityId){
		$filteredList = array();
		foreach ($courseAttributeData as $courseId => $courseAttributes) {
			if($entityId == $courseAttributes['substream_id']){
				$filteredList[] = $courseId;
			}
		}
		return $filteredList;
	}

	private function _filterCoursesOnBaseCourse($courseAttributeData, $entityId){
		$filteredList = array();
		foreach ($courseAttributeData as $courseId => $courseAttributes) {
			if($entityId == $courseAttributes['base_course_id']){
				$filteredList[] = $courseId;
			}
		}
		return $filteredList;
	}

	private function _getCourseTypeInformation($courseList){
		$this->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
		$repo = $builder->getCourseRepository();
		$courseObjs = $repo->findMultiple($courseList);
		$courseAttributeData = array();
		foreach ($courseObjs as $courseId => $courseObj) {
			$courseTypeInfo = $courseObj->getCourseTypeInformation();
			$courseTypeInfo = $courseTypeInfo['entry_course'];
			if(!empty($courseTypeInfo) && is_object($courseTypeInfo)){
				//get only the primary hierarchy
				foreach ($courseTypeInfo->getHierarchies() as $key => $value) {
					if($value['primary_hierarchy'] == 1){
						$courseAttributeData[$courseId] = array(
											'stream_id' => $value['stream_id'],
											'substream_id' => $value['substream_id']
											);
						break;
					}
				}
				$courseAttributeData[$courseId]['base_course_id'] = $courseTypeInfo->getBaseCourse();
			}
		}
		return $courseAttributeData;
	}
	
	function array_flatten($array) {
 		 if (!is_array($array)) {
		    return FALSE;
		  }
		  $result = array();
		  foreach ($array as $key => $value) {
		  	if (is_array($value)) {
		      		$result = array_merge($result, array_flatten($value));
			}
		    	else {
			      $result[$key] = $value;
		    	}
		  }
		  return $result;
	}
		
	function checkApplicationStatus($userId){
		$this->load->model('camodel');
		$this->CAModel = new CAModel();
		$returnData = $this->CAModel->checkApplicationStatus($userId);
		return $returnData;
	}
	/**
	 * Submits the CA form.
	 * Stores different data for edit and new form.
	 * Logs in the user by setting cookie
	 * Stores the images .
	 */
	function submitCAprofileData() {
		$this->init();
		$appId = 12;
		$this->load->helper('security');
        $data = xss_clean($_POST);
		$userArray = array();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		if($userId!=0){
			$editStatus = $this->checkApplicationStatus($userId);
		}else{
			$editStatus = 0;
		}
		
		$allCheck = true;
		// Checking for file Validations
 		if(isset($_FILES) && is_array($_FILES) && !empty($_FILES['userApplicationfile']['name']) ){
			error_log('UGC460:: upload file inside if condition');
			$returnData = $this->uploadMedia();
			if(is_array($returnData) && isset($returnData['fileUrl'])){
				$data['profileImage'] = $returnData['fileUrl'];
			}
			else if(is_array($returnData) && isset($returnData['error'])){
			error_log('UGC460:: upload file inside else if condition');
				if( ($editStatus!= 0 || $editStatus!= 0) && $returnData['error'] == 'Please select a file to upload' ){
					$allCheck = true;
				}
				else{
					$allCheck = false;
					echo "image_error|".$returnData['error'];
					return;
				}
			}
		}
		
		if($userId<=0){
			error_log('UGC460::enter into if condition');
			$userArray['email'] = trim($data['quickemail']);
            $userArray['password'] = 'shiksha@'.rand(1,1000000);
			$userArray['ePassword'] = sha256($userArray['password']);
			$userArray['firstname'] = trim($data['quickfirstname_ForCA']);
			$userArray['lastname'] = trim($data['quicklastname_ForCA']);
			$userArray['displayname'] = trim($data['quickfirstname_ForCA']);
			$userArray['coordinates'] = '';
			$userArray['resolution'] = '';
			$userArray['sourceurl'] = SHIKSHA_ASK_HOME_URL."/CA/CampusAmbassador/getCAProfileForm";
			$userArray['sourcename'] = '';
			$isdCode = $data['isdCode'];
        	$isdCode = explode('-', $isdCode);
        	$userArray['IsdCode'] = $isdCode[0];
			$userArray['country'] = $isdCode[1];
			$userArray['mobile'] = trim($this->input->post('quickMobile_ForCA'));
			$userArray['quicksignupFlag'] = 'veryshortregistration';
			$userArray['usergroup'] = 'veryshortregistration';
			$secCodeIndex = 'secCodeForCAReg';
			$secCode = $this->input->post('securityCode_ForCA');
			$this->load->model('UserPointSystemModel');
			$captchValidation = true;
			if($editStatus == 0){
				$captchValidation = false;
			}
			// Regestering user 
			error_log('UGC460::before doQuickRegistration function');
			$addResult = $this->UserPointSystemModel->doQuickRegistration($userArray,$secCode,$secCodeIndex,$captchValidation);
			error_log('UGC460::after doQuickRegistration function');
			error_log('UGC460::addResult'.print_r($addResult,true));
			if($addResult > 0) {
			error_log('UGC460:: if addResult > 0');
				//Set the cookie when theinstitutes user has registered
				$Validate = $this->checkUserValidation();
				if(!isset($Validate[0]['userid'])){
					$value = $userArray['email'].'|'.$userArray['ePassword'];
					$value .= '|pendingverification';
					setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
					$userId = $addResult;
				} else {
					$userId = $Validate[0]['userid'];
				}
			}
			else if($addResult == "Blank" || $addResult == "email" || $addResult == "displayname" || $addResult == "both" || $addResult == "code"){	//In case of some error in user registration
				error_log('UGC460:: if addResult have some error=='.print_r($addResult,true));
				echo $addResult;
				return;
			}
		}		

		$data['userId'] = $userId;

		//Check to exclude international user from LDB
		if($isdCode[0] != '91'){
			
			$this->load->model('user/usermodel');
	        $userModel = new usermodel;

	        $this->usermodel->addUserToLDBExclusionList($userId, 'International User');
			
		}
		error_log('UGC460:: before validation check');		
		error_log('UGC460:: before validation check==data=='.print_r($data,true).', allCheck==='.$allCheck);
		//If no validation issues are found, only then proceed with the submittion of form
		if(is_array($data) && $allCheck){	
		error_log('UGC460:: inside if condition after validation check');	
			// calls library function to rearrnge Data for model
			$this->load->library('CA/CAHelper');
			$caHelper = new CAHelper();
			if($editStatus!= 0) {
				error_log('UGC460:: if edit=!0 reArrangeData');
				$data['uniqueId'] = $editStatus;
				$userData = $caHelper->reArrangeData($data,true);
				error_log('UGC460:: if edit=!0, reArrangeData='.print_r($data,true).', uniqueId='.$data['uniqueId']);
			}else {
				error_log('UGC460:: if edit==0');
				$userData = $caHelper->reArrangeData($data);
				error_log('UGC460:: if edit==0, reArrangeData='.print_r($data,true));
			}

			// stroing the data in db
			$this->load->model('camodel');
			$this->CAModel = new CAModel();
			$userData['CA_ProfileTable']['hasASmartphone'] = (isset($_POST['has_a_smartphone']) && $_POST['has_a_smartphone']!='')?$this->input->post('has_a_smartphone'):0;
			$userData['CA_ProfileTable']['programId'] = (isset($_POST['programId']) && $_POST['programId']!='')?$this->input->post('programId'):0;
			if($editStatus!= 0) {
				error_log('UGC460:: if editStatus!= 0 store data');
				$returnData = $this->CAModel->saveCAData($userData,true,$userId);
				error_log('UGC460:: if editStatus!= 0 store data=='.print_r($returnData,true));
				echo "CAModified";
			}else {
				$returnData = $this->CAModel->saveCAData($userData);
				$courseIds = array();
				$instituteIds = array();
				if(!empty($userData['CA_ProfileTable']['officialCourseId']) && $userData['CA_ProfileTable']['officialCourseId'] != 0){
					$courseIds[] = $userData['CA_ProfileTable']['officialCourseId'];
					$instituteIds[] = $userData['CA_ProfileTable']['officialInstituteId'];
				}
				if(!empty($userData['CA_MainCourseMappingTable'])) {
					foreach ($userData['CA_MainCourseMappingTable'] as $index => $data) {
						$courseIds[] = $data['courseId'];
						$instituteIds[] = $data['instituteId'];
					}
				}
				
				if(!empty($courseIds)) {
						$this->load->library('listing/cache/ListingCache');
						$ListingCacheObj 	= new ListingCache();
						foreach($courseIds as $cid){
								$ListingCacheObj->deleteCampusRepCourseData($cid);
						}
				}
				if(!empty($instituteIds)){
						$this->load->library('listing/cache/ListingCache');
						$ListingCacheObj 	= new ListingCache();
						foreach($instituteIds as $instiId){
								$ListingCacheObj->deleteCampusRepInstituteData($instiId);
						}
				}

				
				error_log('UGC460:: if editStatus== 0 store data=='.print_r($returnData,true));
				echo "CAAdded";
			}

			$firstName = trim($this->input->post('quickfirstname_ForCA'));
			$lastName = trim($this->input->post('quicklastname_ForCA'));
			$mobile = trim($this->input->post('quickMobile_ForCA'));
			
			$this->load->library('Register_client');
			$registerClient = new Register_client();
			// Checking and Updating User attributes in tuser 
			if(isset($data['profileImage']) && $data['profileImage']!=''){
			 	error_log('UGC460:: update avtarimageurl'.print_r($data['profileImage'],true));
				$registerClient->updateUserAttribute($appId, $userId, 'avtarimageurl', $data['profileImage'],'');
			}
			if($this->userStatus!='false'){
				if($firstName != $this->userStatus[0]['firstname']){
			 	error_log('UGC460:: update firstname'.print_r($firstName,true));
					$registerClient->updateUserAttribute($appId,$userId,'firstname',$firstName);
				}
				if($lastName != $this->userStatus[0]['lastname']){
			 	error_log('UGC460:: update firstname'.print_r($lastName,true));
					$registerClient->updateUserAttribute($appId,$userId,'lastname',$lastName);
				}
			
				if($mobile != $this->userStatus[0]['mobile']) {
			 	error_log('UGC460:: update firstname'.print_r($mobile,true));
					$registerClient->updateUserAttribute($appId,$userId,'mobile',$mobile);
				}
			}
			//$this->sendNewCampusRepRegistrationMailToInternalTeam();
			error_log('UGC460:: at the end before return');
			return;
		}
	}
	
	function submitMentorprofileData()
	{
		$this->init();
		$appId = 12;
		$data = $_POST;
		$userArray = array();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		if($userId!=0){
			$editStatus = $this->checkApplicationStatus($userId);
		}else{
			$editStatus = 0;
		}
		
		$allCheck = true;
		// Checking for file Validations
 		if(isset($_FILES) && is_array($_FILES) && !empty($_FILES['userApplicationfile']['name']) ){
			error_log('UGC460:: upload file inside if condition');
			$returnData = $this->uploadMedia();
			if(is_array($returnData) && isset($returnData['fileUrl'])){
				$data['profileImage'] = $returnData['fileUrl'];
			}
			else if(is_array($returnData) && isset($returnData['error'])){
			error_log('UGC460:: upload file inside else if condition');
				if( ($editStatus!= 0 || $editStatus!= 0) && $returnData['error'] == 'Please select a file to upload' ){
					$allCheck = true;
				}
				else{
					$allCheck = false;
					echo "image_error|".$returnData['error'];
					return;
				}
			}
		}
		
		if($userId<=0){
			error_log('UGC460::enter into if condition');
			$userArray['email'] = trim($this->input->post('quickemail'));
			$userArray['password'] = 'shiksha@'.rand(1,1000000);
			$userArray['ePassword'] = sha256($userArray['password']);
			$userArray['firstname'] = trim($this->input->post('quickfirstname_ForCA'));
			$userArray['lastname'] = trim($this->input->post('quicklastname_ForCA'));
			$userArray['displayname'] = trim($this->input->post('quickfirstname_ForCA'));
			$userArray['coordinates'] = '';
			$userArray['resolution'] = '';
			$userArray['sourceurl'] = SHIKSHA_ASK_HOME_URL."/CA/CampusAmbassador/getMentorshipProfileForm";
			$userArray['sourcename'] = '';
			$isdCode = $data['isdCode'];
        	$isdCode = explode('-', $isdCode);
        	$userArray['IsdCode'] = $isdCode[0];
			$userArray['country'] = $isdCode[1];
			$userArray['mobile'] = trim($this->input->post('quickMobile_ForCA'));
			$userArray['quicksignupFlag'] = 'veryshortregistration';
			$userArray['usergroup'] = 'veryshortregistration';
			$secCodeIndex = 'secCodeForCAReg';
			$secCode = $this->input->post('securityCode_ForCA');
			$this->load->model('UserPointSystemModel');
			$captchValidation = true;
			if($editStatus == 0){
				$captchValidation = false;
			}
			// Regestering user 
			error_log('UGC460::before doQuickRegistration function');
			$addResult = $this->UserPointSystemModel->doQuickRegistration($userArray,$secCode,$secCodeIndex,$captchValidation);
			error_log('UGC460::after doQuickRegistration function');
			error_log('UGC460::addResult'.print_r($addResult,true));
			if($addResult > 0) {
			error_log('UGC460:: if addResult > 0');
				//Set the cookie when theinstitutes user has registered
				$Validate = $this->checkUserValidation();
				if(!isset($Validate[0]['userid'])){
					$value = $userArray['email'].'|'.$userArray['ePassword'];
					$value .= '|pendingverification';
					setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
					$userId = $addResult;
				}
			}
			else if($addResult == "Blank" || $addResult == "email" || $addResult == "displayname" || $addResult == "both" || $addResult == "code"){	//In case of some error in user registration
				error_log('UGC460:: if addResult have some error=='.print_r($addResult,true));
				echo $addResult;
				return;
			}
		}		

		$data['userId'] = $userId;

		error_log('UGC460:: before validation check');		
		error_log('UGC460:: before validation check==data=='.print_r($data,true).', allCheck==='.$allCheck);
		//If no validation issues are found, only then proceed with the submittion of form
		if(is_array($data) && $allCheck){	
		error_log('UGC460:: inside if condition after validation check');	
			// calls library function to rearrnge Data for model
			$this->load->library('CA/CAHelper');
			$caHelper = new CAHelper();
			if($editStatus!= 0) {
				error_log('UGC460:: if edit=!0 reArrangeData');
				$data['uniqueId'] = $editStatus;
				$userData = $caHelper->reArrangeData($data,true);
				error_log('UGC460:: if edit=!0, reArrangeData='.print_r($data,true).', uniqueId='.$data['uniqueId']);
			}else {
				error_log('UGC460:: if edit==0');
				$userData = $caHelper->reArrangeData($data);
				error_log('UGC460:: if edit==0, reArrangeData='.print_r($data,true));
			}

			// stroing the data in db
			$this->load->model('camodel');
			$this->CAModel = new CAModel();
			$userData['CA_ProfileTable']['hasASmartphone'] = (isset($_POST['has_a_smartphone']) && $_POST['has_a_smartphone']!='')?$this->input->post('has_a_smartphone'):0;
			$userData['CA_ProfileTable']['programId'] = (isset($_POST['programId']) && $_POST['programId']!='')?$this->input->post('programId'):0;
			
			if($editStatus!= 0) {
				error_log('UGC460:: if editStatus!= 0 store data');
				$returnData = $this->CAModel->saveCAData($userData,true,$userId);
				error_log('UGC460:: if editStatus!= 0 store data=='.print_r($returnData,true));
				echo "CAModified";
			}else {
				$returnData = $this->CAModel->saveCAData($userData);
				$courseIds = array();
				$instituteIds = array();
				if(!empty($userData['CA_ProfileTable']['officialCourseId']) && $userData['CA_ProfileTable']['officialCourseId'] != 0){
					$courseIds[] = $userData['CA_ProfileTable']['officialCourseId'];
					$instituteIds[] = $userData['CA_ProfileTable']['officialInstituteId'];
				}
				if(!empty($userData['CA_MainCourseMappingTable'])) {
					foreach ($userData['CA_MainCourseMappingTable'] as $index => $data) {
						$courseIds[] = $data['courseId'];
						$instituteIds[] = $data['instituteId'];
					}
				}
				
				if(!empty($courseIds)) {
						$this->load->library('listing/cache/ListingCache');
						$ListingCacheObj 	= new ListingCache();
						foreach($courseIds as $cid){
								$ListingCacheObj->deleteCampusRepCourseData($cid);
						}
				}
				if(!empty($instituteIds)){
						$this->load->library('listing/cache/ListingCache');
						$ListingCacheObj 	= new ListingCache();
						foreach($instituteIds as $instiId){
								$ListingCacheObj->deleteCampusRepInstituteData($instiId);
						}
				}

				
				error_log('UGC460:: if editStatus== 0 store data=='.print_r($returnData,true));
				echo "CAAdded";
			}

			$firstName = trim($this->input->post('quickfirstname_ForCA'));
			$lastName = trim($this->input->post('quicklastname_ForCA'));
			$mobile = trim($this->input->post('quickMobile_ForCA'));
			
			$this->load->library('Register_client');
			$registerClient = new Register_client();
			// Checking and Updating User attributes in tuser 
			if(isset($data['profileImage']) && $data['profileImage']!=''){
			 	error_log('UGC460:: update avtarimageurl'.print_r($data['profileImage'],true));
				$registerClient->updateUserAttribute($appId, $userId, 'avtarimageurl', $data['profileImage'],'');
			}
			if($this->userStatus!='false'){
				if($firstName != $this->userStatus[0]['firstname']){
			 	error_log('UGC460:: update firstname'.print_r($firstName,true));
					$registerClient->updateUserAttribute($appId,$userId,'firstname',$firstName);
				}
				if($lastName != $this->userStatus[0]['lastname']){
			 	error_log('UGC460:: update firstname'.print_r($lastName,true));
					$registerClient->updateUserAttribute($appId,$userId,'lastname',$lastName);
				}
			
				if($mobile != $this->userStatus[0]['mobile']) {
			 	error_log('UGC460:: update firstname'.print_r($mobile,true));
					$registerClient->updateUserAttribute($appId,$userId,'mobile',$mobile);
				}
			}
			//$this->sendNewCampusRepRegistrationMailToInternalTeam();
			//add mentor answers to db
			error_log('UGC2749:: add mentor answers to db');
			$mentorAnswers = $this->input->post('mentorAnswer');
			if($editStatus != 0)
			{
				//for now, edit case is not there.
			}
			else
			{
				$this->CAModel->saveMentorAnswers($userId, $mentorAnswers);
			}
			error_log('UGC460:: at the end before return');
			return;
		}
	}
	
	/**
	 * Uploads image after checking validations
	 */	
	function uploadMedia() {
		$this->init();
		$appId = 1;
		$ListingClientObj = new Listing_client();
		$displayData = array();
		$displayData['error'] = 'Please select a file to upload';
		if(isset($_FILES['userApplicationfile']['name']) && $_FILES['userApplicationfile']['name']!=''){
			$type = $_FILES['userApplicationfile']['type'];
			$size = $_FILES['userApplicationfile']['size'];
			if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png" || $type== "image/pjpeg" || $type== "image/x-png" || $type== "image/pjpg"))
			{
				$displayData['error'] = 'Please upload only jpeg,png,jpg';
			}
			else if($size>1048576)
			{
				$displayData['error'] = 'Size limit of 1 Mb exceeded';
			}
			else{
				$fileName = explode('.',$_FILES['userApplicationfile']['name']);
				$fileNameToBeAdded = $fileName[0];
				$fileCaption= $fileNameToBeAdded;
				$fileExtension = $fileName[count($fileName) - 1];
				$fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;
	
				$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;;
	
				$this->load->library('upload_client');
				$uploadClient = new Upload_client();
	
				$fileType = explode('/',$_FILES['userApplicationfile']['type']);
				$mediaDataType = ($fileType[0]=='image')?'image':'pdf';
	
				//$FILES = $_FILES;
				$FILES = array();
				$FILES['userApplicationfile']['type'][0] = $_FILES['userApplicationfile']['type'];
				$FILES['userApplicationfile']['name'][0] = $_FILES['userApplicationfile']['name'];
				$FILES['userApplicationfile']['tmp_name'][0] = $_FILES['userApplicationfile']['tmp_name'];
				$FILES['userApplicationfile']['error'][0] = $_FILES['userApplicationfile']['error'];
				$FILES['userApplicationfile']['size'][0] = $_FILES['userApplicationfile']['size'];
	
				//Before uploading the file, check the Size and type of file. Only if they are valid, will we proceed with the uploading
	
	
 				$upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$FILES,array($fileCaption),$userId, 'user','userApplicationfile');
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
	
	function sendNewCampusRepRegistrationMailToInternalTeam()
	{
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$this->load->model('camodel');
		$this->CAModel = new CAModel();
		$result = $this->CAModel->getAllCADetails($userId);

		$this->load->library('CAEnterprise/CAUtilityLib');
		$caUtilityLib =  new CAUtilityLib;
		$resultCA = $caUtilityLib->formatCAData($result);
		$userData = $resultCA[0]['ca'];
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		if($userData['mainEducationDetails'][0]['courseId']!='' && $userData['mainEducationDetails'][0]['courseId']>0)
		{
			$courseObj = $courseRepository->find($userData['mainEducationDetails'][0]['courseId']);
		
			$courseName = $courseObj->getName();
			$cityName = $courseObj->getMainLocation()->getCity()->getName();
			$stateName = $courseObj->getMainLocation()->getState()->getName();
			$countryName = $courseObj->getMainLocation()->getCountry()->getName();
			$instName = $courseObj->getInstituteName();
		}
		$locationStr = (($cityName!='')?', '.$cityName:'').(($stateName!='')?', '.$stateName:'').', '.$countryName;
		$instStr = $instName.$locationStr;
		$userName = $userData['firstname'].' '.$userData['lastname'];
		$userEmail = $userData['email'];
		$userMobile = $userData['mobile'];
		$this->load->library('alerts_client');
                $alertClient = new Alerts_client();
		echo $subject = "New review submission from $userName, $instName";
		$emails = array('neda.ishtiaq@shiksha.com', 'soma.chaturvedi@shiksha.com', 'saurabh.gupta@shiksha.com');
		echo $content = '<p>Hi,</p>
		<p>A new submission for Campus Connect program has been made by :</p>
		<p>&nbsp;</p>
		<div>
		<table>
		<tr>
			<td>Name</td>
			<td>'.$userName.'</td>
		</tr>
		<tr>
			<td>Email</td>
			<td>'.$userEmail.'</td>
		</tr>
		<tr>
			<td>Mobile</td>
			<td>'.$userMobile.'</td>
		</tr>
		<tr>
			<td>Institute</td>
			<td>'.$instStr.'</td>
		</tr>
		<tr>
			<td>Course</td>
			<td>'.$courseName.'</td>
		</tr>
		</table>
		</div>
		<p>&nbsp;</p>
		<p>Best wishes,</p>
		<p>Shiksha.com</p>';
		for($i=0; $i<count($emails) && $userId!='' && $userId>0; $i++)
		{
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emails[$i], $subject, $content, "html", '');
		}
	}
	
	/**
	 * Get url for AnA tab
	 * Gets the url using the helper .
	 * Passes params to helper to get url/s
	 */
	function getListingAnaUrl($instituteId='') {
		
		$instId = isset($_POST['instituteId'])?$this->input->post('instituteId'):$instituteId;
		$params = array();
		
		// get institute details using institute id
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$result = $instituteRepository->find($instId);
		
		$params = array(
					'instituteId'=>$instId,
					'instituteName'=>$result->getName(),
					'type'=>'institute',
					'abbrevation'=>$result->getAbbreviation()
				   );
		// get AnA url from institute_id and abbrevation
                if($_REQUEST['city']){
                        $additionalURLParams = "?city=".$_REQUEST['city'];
                        if($_REQUEST['locality']){
                                $additionalURLParams .= "&locality=".$_REQUEST['locality'];
                        }
                }

		$url = listing_detail_ask_answer_url($params) . $additionalURLParams;
		if($instituteId!=''){
			return $url;
		}else{
		echo $url;
	}
	}
	
	function getCADetailsForMarketingPage($startFrom=''){
		$this->init();
		if($startFrom == '0' || $startFrom == '1' || (!is_numeric ($startFrom) && $startFrom!='')){
			header("Location:".SHIKSHA_HOME."/mba/resources/ask-current-mba-students",TRUE,301);
                        exit;
		}
		$Validate = $this->userStatus;
		$this->load->model('camodel');
		$this->CAModel = new CAModel();
		$instName = '';
		if(isset($_COOKIE['instSDetails'])){
			$instSDetails = explode('#',$_COOKIE['instSDetails']);
			$instId = $instSDetails[0];
			$instName = $instSDetails[1];
			setcookie("instSDetails", "", time()-3600, "/", COOKIEDOMAIN);
			unset($_COOKIE['instSDetails']);
		}else{
			$instId = isset($_POST['instituteId'])?$this->input->post('instituteId'):0;
		}
		$type = isset($_POST['type'])?$this->input->post('type'):'NoneAjax';
		$rows='30';

		if($startFrom>1){
			$start = ($startFrom-1)*$rows;
		}else{
			$start = '0';
		}
		//$date = date('Y-m-d');
                $this->load->library('cacheLib');
                $cacheLib = new cacheLib();
		if(empty($startFrom)){
                        $startFrom = 0;
                }
		$keyCA = md5('caMarketingPage_'.$startFrom);
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$data['msgFlag'] = 0;
		if($instId=='0'){
			if($cacheLib->get($keyCA)!="ERROR_READING_CACHE" && $cacheLib->get($keyCA)!='' && $cacheLib->get($keyCA)!='No_Search_Data'){
				$caDetailsForMarketingPage = $cacheLib->get($keyCA);
				$caTopDetailsForMarketingPage = $cacheLib->get(md5('caMarketingPage_0'));
				$flag=1;
			}else{
				$caDetailsForMarketingPage = $this->CAModel->getCADetailsForMarketingPage($start,$rows,$instId);
				if($startFrom==0){
                                        $minCount = 0;
                                        $maxCount = $rows;
                                        $caDetailsForMarketingPage['caDetails'] = array_splice($caDetailsForMarketingPage['caDetails'],$minCount,$rows);
                                }else{
                                        $minCount = ($startFrom-1)*$rows;
				
                                        $caDetailsForMarketingPage['caDetails'] = array_splice($caDetailsForMarketingPage['caDetails'],$minCount,$rows);
                                }
				foreach($caDetailsForMarketingPage['caDetails'] as $key=>$value){
					$courseObj = $courseRepository->find($value['courseId']);
                                        if($courseObj->getInstId()<=0 || $courseObj->getInstId() == ''){
                                                continue;
                                        }
					$insObj = $instituteRepository->find($courseObj->getInstId());
					$caDetailsForMarketingPage['caDetails'][$key]['landingPageUrl'] = Modules::run('CA/CADiscussions/getCourseUrl',$value['courseId']);
					$caDetailsForMarketingPage['caDetails'][$key]['courseName'] = $courseObj->getName();
					$caDetailsForMarketingPage['caDetails'][$key]['insName'] = $courseObj->getInstituteName();
					$caDetailsForMarketingPage['caDetails'][$key]['insLogoImage'] = $insObj->getLogo();
					$locationArr = $insObj->getLocations();
					if(is_object($locationArr[$value['insLocId']]))
						$caDetailsForMarketingPage['caDetails'][$key]['insLocation'] = $locationArr[$value['insLocId']]->getCity()->getName(); 
				}
			    		$cacheLib->store($keyCA,$caDetailsForMarketingPage,86400);
					//$caTopDetailsForMarketingPage = $caDetailsForMarketingPage;
					$caTopDetailsForMarketingPage = $cacheLib->get(md5('caMarketingPage_0'));
			}
		}else{
			$caDetailsForMarketingPage = $this->CAModel->getCADetailsForMarketingPage($start,$rows,$instId);
			if($caDetailsForMarketingPage['totalCount']==0 ){
				if($type=='ajax'){
					echo "NoResult";
					exit;
				}else{
					$caDetailsForMarketingPage = $cacheLib->get(md5('caMarketingPage_0'));
					$data['msgFlag'] = 1;
				}
			}
			if($startFrom==0){
                                        $minCount = 0;
                                        $maxCount = $rows;
                                        $caDetailsForMarketingPage['caDetails'] = array_splice($caDetailsForMarketingPage['caDetails'],$minCount,$rows);
                       }else{
                                        $minCount = (($startFrom-1)*$rows)-1;
                                        $caDetailsForMarketingPage['caDetails'] = array_splice($caDetailsForMarketingPage['caDetails'],$minCount,$rows);
                       }
			foreach($caDetailsForMarketingPage['caDetails'] as $key=>$value){
				$courseObj = $courseRepository->find($value['courseId']);
				$insObj = $instituteRepository->find($courseObj->getInstId());
				$caDetailsForMarketingPage['caDetails'][$key]['landingPageUrl'] = Modules::run('CA/CADiscussions/getCourseUrl',$value['courseId']);
				$caDetailsForMarketingPage['caDetails'][$key]['courseName'] = $courseObj->getName();
				$caDetailsForMarketingPage['caDetails'][$key]['insName'] = $courseObj->getInstituteName();
				$caDetailsForMarketingPage['caDetails'][$key]['insLogoImage'] = $insObj->getLogo();
				$locationArr = $insObj->getLocations();
				if(is_object($locationArr[$value['insLocId']]))
					$caDetailsForMarketingPage['caDetails'][$key]['insLocation'] = $locationArr[$value['insLocId']]->getCity()->getName();
			}
			$caTopDetailsForMarketingPage = $cacheLib->get(md5('caMarketingPage_0'));
		}

		$totalCount = $caDetailsForMarketingPage['totalCount'];
		$maxPageNo = ceil($totalCount/30);
		if($startFrom > $maxPageNo){
			header("Location:".SHIKSHA_HOME."/mba/resources/ask-current-mba-students",TRUE,301);
                        exit;
		}
		$paginationURL = site_url('mba/resources/ask-current-mba-students')."-"."@start@";
		$data['paginationHTML'] = doPaginationForArticleAuthor($totalCount,$paginationURL,$start,$rows,3);
 		$data['caDetailsForMarketingPage'] = $caDetailsForMarketingPage;
 		$data['caTopDetailsForMarketingPage'] = $caTopDetailsForMarketingPage;
		$data['validateuser'] = $Validate;
		$data['searchInsName'] = $instName;
		if($type=='NoneAjax'){
			$this->load->view('CA/caMarketingPage',$data);
		}else{
			echo $this->load->view('CA/caMarketingPageInner',$data);
		}
	}
	
	function weeklyCronToGetCampusRepActivityDetails(){
		$this->validateCron();
		$this->load->model('camodel');
                $this->CAModel = new CAModel();
                $result = $this->CAModel->campusRepActivityDetails();

		$this->load->library('common/PHPExcel');
				/** Errors report */
//		error_reporting(E_ALL);
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		 
		//We add contents
		//Warning, a utf8_encode() is necessary for the character like 'é', 'è', ..
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Questions Asked (Institutes with CR)');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45); 
		
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Season(From 1st Sep,2013)');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '');

		$objPHPExcel->getActiveSheet()->getStyle('B1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('B1:C1');	

		$objPHPExcel->getActiveSheet()->getStyle('B2:C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('B2:C2');	

		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Last 7- Days');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '');

		$objPHPExcel->getActiveSheet()->getStyle('D1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->mergeCells('D1:E1');

                $objPHPExcel->getActiveSheet()->getStyle('D2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('D2:E2');

                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);

		$objPHPExcel->getActiveSheet()->setCellValue('B3', 'CR');
		$objPHPExcel->getActiveSheet()->setCellValue('C3', 'All');
		$objPHPExcel->getActiveSheet()->setCellValue('D3', 'CR');
		$objPHPExcel->getActiveSheet()->setCellValue('E3', 'All');

		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Questions Answered');
		$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Questions Answered (with Thumbs Up)');
		$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Percentage Questions Answered');
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Percentage Questions Answered (with Thumbs Up)');

		$result['percentageQuesAnsByCA'] = ($result['questionAnsweredByCRFullDuration']/$result['totalQuestionCount'])*100;
		$result['percentageQuesAnsByCAInWeek'] = ($result['questionAnsweredByCRWeekly']/$result['QuestionCountInWeek'])*100;
		$result['percentageQuesAnsByAll'] = ($result['questionAnsweredByAllFullDuration']/$result['totalQuestionCount'])*100;
		$result['percentageQuesAnsByAllInWeek'] = ($result['questionAnsweredByAllWeekly']/$result['QuestionCountInWeek'])*100;
		$result['percentageQuesAnsByCAWithDigUp'] = ($result['questionAnsweredByCRFullDurationWithDigUp']/$result['totalQuestionCount'])*100;
                $result['percentageQuesAnsByCAInWeekWithDigUp'] = ($result['questionAnsweredByCRWeeklyWithDigUp']/$result['QuestionCountInWeek'])*100;
                $result['percentageQuesAnsByAllWithDigUp'] = ($result['questionAnsweredByAllFullDurationWithDigUp']/$result['totalQuestionCount'])*100;
                $result['percentageQuesAnsByAllInWeekWithDigUp'] = ($result['questionAnsweredByAllWeeklyWithDigUp']/$result['QuestionCountInWeek'])*100;
		
		$objPHPExcel->getActiveSheet()->setCellValue('B2', $result['totalQuestionCount']);
		$objPHPExcel->getActiveSheet()->setCellValue('D2', $result['QuestionCountInWeek']);

                $objPHPExcel->getActiveSheet()->setCellValue('B4', $result['questionAnsweredByCRFullDuration']);
                $objPHPExcel->getActiveSheet()->setCellValue('C4', $result['questionAnsweredByAllFullDuration']);
                $objPHPExcel->getActiveSheet()->setCellValue('D4', $result['questionAnsweredByCRWeekly']);
                $objPHPExcel->getActiveSheet()->setCellValue('E4', $result['questionAnsweredByAllWeekly']);

                $objPHPExcel->getActiveSheet()->setCellValue('B5', $result['questionAnsweredByCRFullDurationWithDigUp']);
                $objPHPExcel->getActiveSheet()->setCellValue('C5', $result['questionAnsweredByAllFullDurationWithDigUp']);
                $objPHPExcel->getActiveSheet()->setCellValue('D5', $result['questionAnsweredByCRWeeklyWithDigUp']);
                $objPHPExcel->getActiveSheet()->setCellValue('E5', $result['questionAnsweredByAllWeeklyWithDigUp']);

                $objPHPExcel->getActiveSheet()->setCellValue('B6', round($result['percentageQuesAnsByCA']));
                $objPHPExcel->getActiveSheet()->setCellValue('C6', round($result['percentageQuesAnsByAll']));
                $objPHPExcel->getActiveSheet()->setCellValue('D6', round($result['percentageQuesAnsByCAInWeek']));
                $objPHPExcel->getActiveSheet()->setCellValue('E6', round($result['percentageQuesAnsByAllInWeek']));

                $objPHPExcel->getActiveSheet()->setCellValue('B7', round($result['percentageQuesAnsByCAWithDigUp']));
                $objPHPExcel->getActiveSheet()->setCellValue('C7', round($result['percentageQuesAnsByAllWithDigUp']));
                $objPHPExcel->getActiveSheet()->setCellValue('D7', round($result['percentageQuesAnsByCAInWeekWithDigUp']));
                $objPHPExcel->getActiveSheet()->setCellValue('E7', round($result['percentageQuesAnsByAllInWeekWithDigUp']));

		$documentName = "Campus_Reps_weekly_Data_".date('Y_m_d').'.xlsx';
		$documentURL = "/tmp/".$documentName;
	
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($documentURL);

                $this->load->library('alerts_client');
                $alertClient = new Alerts_client();
                $documentContent = base64_encode(file_get_contents($documentURL));
		$this->load->library('Ldbmis_client');
	        $misObj = new Ldbmis_client();
		$content = "<p>Hi,</p> <p>Please find the attached report for Campus Reps. </p><p>- Shiksha Tech.</p>";
		$email   = array('pranjul.raizada@shiksha.com','ankur.gupta@shiksha.com','prateek.malpani@shiksha.com','saurabh.gupta@shiksha.com');
		for($i=0;$i<count($email);$i++){
	    	      	$type_id = $misObj->updateAttachment(1);
			$attachmentId = $alertClient->createAttachment("12",$type_id,'COURSE','E-Brochure',$documentContent,'Campus_Reps_weekly_Data_'.date('Y_m_d').'.xlsx','doc','true');
			$attachment = array($attachmentId);
			$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$email[$i],'Campus Rep Weekly Data on '.date('Y-m-d'),$content,"html",'','y',$attachment);
		}
	}
	
	/**
	 * This function get the Campus Rep TAT data for previous 30 days.
	 * This function is not public and only used by Product team
	 * @Author Virender Singh
	 * @Date 2015-08-12
	 * @Modified 2015-10-06
	 * @Param none
	 * @Return
	 */
	function campusRepTATDataOnDateRange()
	{
		$startDate = date('Y-m-d', strtotime('-30 days')).' 00:00:01';
		$endDate   = date('Y-m-d', strtotime('-1 days')).' 23:59:59';
		$this->load->model('camodel');
                $this->CAModel = new CAModel();
		$caTATData = $this->CAModel->getAllTATDataForCA($startDate, $endDate);//die;
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=monthlyTATforCA_'.date('d_m_Y', strtotime('-1 days')).'.csv');
		$output = fopen('php://output', 'w');
		fputcsv($output, array('CA Name', 'Date of joining', 'Total Questions', 'Total Answered by CA', 'TAT'));
		foreach($caTATData as $userId => $data)
		{
			$TAT = 'NA';
			if(is_array($data['responseTime']) && count($data['responseTime']) > 0)
			{
				$TAT = number_format((array_sum($data['responseTime']) / $data['ansCount']), 2) .' Hrs';
			}
			$row = array($data['caName'], date('j F, Y', strtotime($data['dateOfJoining'])), $data['queCount'], $data['ansCount'], $TAT);
			fputcsv($output, $row);
		}
	}
	
	function weeklyCronToGetCampusMarketingActivites(){
		$initialMemory = memory_get_usage();
		$this->benchmark->mark('controller_start');
		error_log(':::CAMarketingReport:::'.'start');
		$this->load->model('camodel');
                $this->CAModel = new CAModel();
		
		$this->load->library('CACronsLib');
		$CACronLib = new CACronsLib();
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$InstituteRepository = $listingBuilder->getInstituteRepository();
		$subcatArrays = array('23','56');
		$subcatString  = implode(',',$subcatArrays);
		
		
		/*****
		MBA/Engg. CR level metrics sheet
		
		****/
		error_log(':::CAMarketingReport:::'.'call to campusRepMarketingReport model');
		$this->benchmark->mark('code_start');
		$memoryBefore = memory_get_usage();
		$result = $this->CAModel->campusRepMarketingReport($subcatString);
		$memoryAfter = memory_get_usage();
		$this->benchmark->mark('code_end');
		error_log(':::CAMarketingReport:::'.'return from campusRepMarketingReport model');
		error_log(':::CAMarketingReport:::'.'memory usage by campusRepMarketingReport = '.($memoryAfter-$memoryBefore).'bytes');
		error_log(':::CAMarketingReport:::'.'execution time of campusRepMarketingReport model'.$this->benchmark->elapsed_time('code_start', 'code_end'));
		$caCourseMap = array();
		$joiningDate = array();
		foreach($result as $courseid=>$cadata){
			foreach($cadata as $key=>$val)
			{
				$caCourseMap[$val['userId']][] = $courseid;
				$joiningDate[$val['userId']] = $val['creationDate'];
			} 
		}
		
		error_log(':::CAMarketingReport:::'.'call to getOutstandingAmountData model');
		$this->benchmark->mark('payment_start');
		$caPaymentData = $this->CAModel->getOutstandingAmountData();
		$this->benchmark->mark('payment_end');
		error_log(':::CAMarketingReport:::'.'return from getOutstandingAmountData model');
		error_log(':::CAMarketingReport:::'.'execution time of getOutstandingAmountData model'.$this->benchmark->elapsed_time('payment_start', 'payment_end'));
		$CAids = array();
		$CAPaymentSheet = array();
		foreach($result as $CAdata)
		{
			foreach($CAdata as $ca)
			{
				if(!in_array($ca['userId'], $CAids[$ca['category_id']]))
				{
					$CAids[$ca['category_id']][] = $ca['userId'];
					$amountToBePaid = (isset($caPaymentData['walletData'][$ca['userId']])?$caPaymentData['walletData'][$ca['userId']]:0) - (isset($caPaymentData['payoutData'][$ca['userId']])?$caPaymentData['payoutData'][$ca['userId']]:0);
					$CAPaymentSheet[$ca['category_id']][] = array('userId'=>$ca['userId'], 'caName'=>$ca['caName'], 'amountToBePaid'=>$amountToBePaid);
				}
			}
		}
		
		$QuesAnsTAT = array();
		error_log(':::CAMarketingReport:::'.'start of TAT queries');
		$this->benchmark->mark('TAT_start');
		foreach($caCourseMap as $userId => $userData){
			
			$courseString = implode(',',$userData);
			$QuesAnsTATDoj[$userId] = $this->CAModel->getDiffBtweenQuesAns($courseString,$userId,$joiningDate[$userId],'All');
			$QuesAnsTATWeek[$userId] = $this->CAModel->getDiffBtweenQuesAns($courseString,$userId,$joiningDate[$userId],'Week');
			$QuesAnsTAT15Day[$userId] = $this->CAModel->getDiffBtweenQuesAns($courseString,$userId,$joiningDate[$userId],'15Day');
		}
		$this->benchmark->mark('TAT_end');
		error_log(':::CAMarketingReport:::'.'end of TAT queries');
		error_log(':::CAMarketingReport:::TAT calculation time:::'.$this->benchmark->elapsed_time('TAT_start', 'TAT_end'));
		foreach($QuesAnsTATDoj as $userId => $userData){
			
			$totalCount = count($userData);
			$averageTATDoj[$userId]['totalDiff'] = 0;
			for($i=0;$i<$totalCount;$i++){
				
				$averageTATDoj[$userId]['totalDiff'] += $userData[$i]['difference'];	
				
			}
			$averageTATDoj[$userId]['averageTAT'] = $averageTATDoj[$userId]['totalDiff']/($totalCount*60*60);
			
		}
		
		foreach($QuesAnsTATWeek as $userId => $userData){
			
			$totalCount = count($userData);
			$averageTATWeek[$userId]['totalDiff'] = 0;
			for($i=0;$i<$totalCount;$i++){
				
				$averageTATWeek[$userId]['totalDiff'] += $userData[$i]['difference'];	
				
			}
			$averageTATWeek[$userId]['averageTAT'] = $averageTATWeek[$userId]['totalDiff']/($totalCount*60*60);
			
		}
		error_log(':::CAMarketingReport:::'.'get inst name and course name from repo - start');
		foreach($QuesAnsTAT15Day as $userId => $userData){
			
			$totalCount = count($userData);
			$averageTAT15Day[$userId]['totalDiff'] = 0;
			for($i=0;$i<$totalCount;$i++){
				
				$averageTAT15Day[$userId]['totalDiff'] += $userData[$i]['difference'];	
				
			}
			$averageTAT15Day[$userId]['averageTAT'] = $averageTAT15Day[$userId]['totalDiff']/($totalCount*60*60);
			
		}
		error_log(':::CAMarketingReport:::'.'get inst name and course name from repo - start');
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		foreach($result as $courseId => $courseData){
			$courseObj = $courseRepository->find($courseId);
			for($i=0;$i<count($courseData); $i++){
				$result[$courseId][$i]['instituteName']=$courseObj->getInstituteName();
				$result[$courseId][$i]['courseName']=$courseObj->getName();
			}
		}
		error_log(':::CAMarketingReport:::'.'get inst name and course name from repo - end');
		$finalArrayInfo=array();
		foreach($result as $courseId => $courseData){
			for($i=0;$i<count($courseData);$i++)
			  $finalArrayInfo[]=$result[$courseId][$i];
		}
		$this->load->library('common/PHPExcel');
		$objPHPExcel = new PHPExcel();
		
		$j=0;
		error_log(':::CAMarketingReport:::'.'start of CR level sheet');
		foreach($subcatArrays as $subcatArray){
			
			if($subcatArray == 23){		
				$title = 'CR Name';
				
			}else if($subcatArray == 56){
				$title = 'Mentor Name';
			}
			
			$objWorkSheet = $objPHPExcel->createSheet($j);

			// Set the active Excel worksheet
			
			$objPHPExcel->setActiveSheetIndex($j);  
			
			$objPHPExcel->getActiveSheet()->setCellValue('A2', '');
			$objPHPExcel->getActiveSheet()->setCellValue('B2', '');
			$objPHPExcel->getActiveSheet()->setCellValue('C2', '');
			$objPHPExcel->getActiveSheet()->setCellValue('D2', '');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', '');
			$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Last 30 days');
			$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->mergeCells('F2:I2');
			
			$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Last 7 days');
			$objPHPExcel->getActiveSheet()->getStyle('J2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('J2:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A3:R3')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->mergeCells('J2:M2');
			
			$objPHPExcel->getActiveSheet()->setCellValue('N2', 'Last 15 days');
			$objPHPExcel->getActiveSheet()->getStyle('N2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('N2:Q2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A3:R3')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->mergeCells('N2:Q2');
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25); 
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue('A3', $title);
			$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Date of joining');
			$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Institute Name');
			$objPHPExcel->getActiveSheet()->setCellValue('D3', 'Course Name');
			$objPHPExcel->getActiveSheet()->setCellValue('E3', 'Total Pending Moderation');
			$objPHPExcel->getActiveSheet()->setCellValue('F3', 'Questions Asked');
			$objPHPExcel->getActiveSheet()->setCellValue('G3', 'Question Answered');
			$objPHPExcel->getActiveSheet()->setCellValue('H3', 'Question Unanswered');
			$objPHPExcel->getActiveSheet()->setCellValue('I3', 'TAT');
			$objPHPExcel->getActiveSheet()->setCellValue('J3', 'Questions Asked');
			$objPHPExcel->getActiveSheet()->setCellValue('K3', 'Question Answered');
			$objPHPExcel->getActiveSheet()->setCellValue('L3', 'Question Unanswered');
			$objPHPExcel->getActiveSheet()->setCellValue('M3', 'TAT');
			$objPHPExcel->getActiveSheet()->setCellValue('N3', 'Questions Asked');
			$objPHPExcel->getActiveSheet()->setCellValue('O3', 'Question Answered');
			$objPHPExcel->getActiveSheet()->setCellValue('P3', 'Question Unanswered');
			$objPHPExcel->getActiveSheet()->setCellValue('Q3', 'TAT');
			
			$i=4;

			//used in institute level sheet
			$courseString = $this->CAModel->getCourseIdsCampusRep();
			//indexed on courseId
			$pastCampusRepDetails = $this->CAModel->getPastCampusRepDetailsOfACourse($courseString);
			$CRDetailsFormatted = $CACronLib->getCampusRepDetails($finalArrayInfo,$pastCampusRepDetails);
			$campusRepIds = $CRDetailsFormatted['campusRepIds'];
			$campusRepInstituteMapping = $CRDetailsFormatted['campusRepInstituteMapping'];
			$CRPendingModerationTotal = $this->CAModel->getPendingModerationCountForCRs($subcatString,'all','',$CRDetailsFormatted['campusRepIdsPastExcluded']);
			foreach($finalArrayInfo as $key=>$result){
				
				if($result['category_id'] == $subcatArray){
					$nameOfRep= $result['caName'];
					$dateOfJoining= date('j F, Y', strtotime($result['creationDate']));
					$badge= $result['badge'];
					$instituteName= $result['instituteName'];
					$questionDoj= $result['totalQuestionSinceDOJ'];
					$answersDoj=$result['totalQuestionsAnsweredsinceDOJ'];
					$unansweredDoj= $result['unansweredSinceDOJ'];
					$questionWeek=$result['totalQuestionThisWeek'];
					$answersWeek=$result['totalQuestionAnsweredThisWeek'];
					$unansweredWeek= $result['unansweredSinceWeek'];
					$courseName=$result['courseName'];
					$answersOnQuestionsAskedAfterDoj = $result['answersOnQuestionsAskedAfterDoj'];
					$answersOnQuestionsAskedLastWeek = $result['answersOnQuestionsAskedLastWeek'];
					$pendingModerationCountTotal = isset($CRPendingModerationTotal[$result['instituteId']][$result['category_id']][$result['userId']]['ansCount']) ? $CRPendingModerationTotal[$result['instituteId']][$result['category_id']][$result['userId']]['ansCount'] : 0;
					$totalQuestionIn15Days = $result['totalQuestionIn15Days'];
					$totalQuestionAnsweredIn15Days = $result['totalQuestionAnsweredIn15Days'];
					$answersOnQuestionsAskedIn15Days = $result['answersOnQuestionsAskedIn15Days'];
					$unansweredIn15Days = $result['unansweredIn15Days'];
					
					if($averageTATDoj[$result['userId']]['averageTAT'] > 24){
						
						$TATDoj = round(($averageTATDoj[$result['userId']]['averageTAT'] / 24),2).' Days';
						
					}else if(empty($averageTATDoj[$result['userId']]['averageTAT'])){
						
						$TATDoj = 'NA';
						
					}else{
						$TATDoj = round($averageTATDoj[$result['userId']]['averageTAT'],2).' Hrs';
						
					}
					
					if($averageTATWeek[$result['userId']]['averageTAT'] > 24){
						
						$TATWeek = round(($averageTATWeek[$result['userId']]['averageTAT'] / 24),2).' Days';
						
					}else if(empty($averageTATWeek[$result['userId']]['averageTAT'])){
						
						$TATWeek = 'NA';
						
					}else{
						$TATWeek = round($averageTATWeek[$result['userId']]['averageTAT'],2).' Hrs';
						
					}
					
					if($averageTAT15Day[$result['userId']]['averageTAT'] > 24){
						
						$TAT15Day = round(($averageTAT15Day[$result['userId']]['averageTAT'] / 24),2).' Days';
						
					}else if(empty($averageTAT15Day[$result['userId']]['averageTAT'])){
						
						$TAT15Day = 'NA';
						
					}else{
						$TAT15Day = round($averageTAT15Day[$result['userId']]['averageTAT'],2).' Hrs';
						
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $nameOfRep);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $dateOfJoining);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $instituteName);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $courseName);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $pendingModerationCountTotal);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $questionDoj);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $answersOnQuestionsAskedAfterDoj);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $unansweredDoj);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $TATDoj);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $questionWeek);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $answersOnQuestionsAskedLastWeek);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $unansweredWeek);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $TATWeek);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $totalQuestionIn15Days);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $answersOnQuestionsAskedIn15Days);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $unansweredIn15Days);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $TAT15Day);
					$i++;
				
				}
				
			// Rename sheet
			if($subcatArray == 23){		
				$objPHPExcel->getActiveSheet()->setTitle('MBA CR level');
				
			}else if($subcatArray == 56){
				$objPHPExcel->getActiveSheet()->setTitle('Engg. Mentor metrics');
			}
				
			}
			$j++;
			
		}
		error_log(':::CAMarketingReport:::'.'end of CR level sheet');
		/*****
		MBA/Engg. institute level sheet
		
		****/
		error_log(':::CAMarketingReport:::'.'start of institute level queries');
		$resQuesAskedArrayAll = $this->CAModel->campusRepReportQuesAskedInstBased($subcatString,'month',$courseString);
		$quesAskedArrayAll = $resQuesAskedArrayAll['result'];
		$msgIdsAllArray = $resQuesAskedArrayAll['messageIds'];
		$resQuesAskedArrayAll = '';
		$msgIdsAll = $CACronLib->getMessageIdsQuestions($msgIdsAllArray);
		$resQuesAskedArrayWeek = $this->CAModel->campusRepReportQuesAskedInstBased($subcatString,'week',$courseString);
		$quesAskedArrayWeek = $resQuesAskedArrayWeek['result'];
		$msgIdsArrayWeek = $resQuesAskedArrayWeek['messageIds'];
		$resQuesAskedArrayWeek = '';
		$msgIdsWeek = $CACronLib->getMessageIdsQuestions($msgIdsArrayWeek);
		$quesAnsweredArrayAll = $this->CAModel->campusRepReportQuesAnsweredInstBased($subcatString,'month',$courseString);
		$quesAnsweredArrayWeek = $this->CAModel->campusRepReportQuesAnsweredInstBased($subcatString,'week',$courseString);
		if(!empty($campusRepIds)){
			if(!empty($msgIdsAll)){
				$quesAnsweredByCRArrayAll = $this->CAModel->campusRepReportQuesAnsweredByCRInstBased($msgIdsAll,$campusRepIds,$subcatString);
			}
			if(!empty($msgIdsWeek)){
				$quesAnsweredByCRArrayWeek = $this->CAModel->campusRepReportQuesAnsweredByCRInstBased($msgIdsWeek,$campusRepIds,$subcatString);
			}
		}
		// _p($quesAnsweredByCRArrayAll);
		// _p($quesAnsweredByCRArrayWeek);
		$resultMsgIdsAll = $CACronLib->getMessageIdsAnswersAndIndexedArray($quesAnsweredByCRArrayAll,$campusRepInstituteMapping);
		$resultMsgIdsWeek = $CACronLib->getMessageIdsAnswersAndIndexedArray($quesAnsweredByCRArrayWeek,$campusRepInstituteMapping);
		$messageIdAnswerAll = $resultMsgIdsAll['messageIdAnswer'];
		$messageIdAnswerWeek = $resultMsgIdsWeek['messageIdAnswer'];
		$countAnsweredCRAll = $resultMsgIdsAll['countAnswered'];
		$countAnsweredCRWeek = $resultMsgIdsWeek['countAnswered'];
		$resultMsgIdsAll = '';
		$resultMsgIdsWeek = '';
		// $pendingAnswersMonth = $this->CAModel->getPendingAnswersInstituteWise($subcatString,'month');
		// $pendingAnswersWeek = $this->CAModel->getPendingAnswersInstituteWise($subcatString,'week');
		$pendingAnswersCRMonth = $this->CAModel->getPendingModerationCountForCRs($subcatString,'month',$messageIdAnswerAll);
		$pendingAnswersCRWeek = $this->CAModel->getPendingModerationCountForCRs($subcatString,'week',$messageIdAnswerWeek);
		error_log(':::CAMarketingReport:::'.'end of institute level queries');
		
		error_log(':::CAMarketingReport:::'.'end of institute level queries');
		$finalArrayInfo2 = $CACronLib->formatCAMarketingReportData($quesAskedArrayAll,$quesAskedArrayWeek,$quesAnsweredArrayAll,$quesAnsweredArrayWeek,$countAnsweredCRAll,$countAnsweredCRWeek,$pendingAnswersCRMonth,$pendingAnswersCRWeek);
		foreach($finalArrayInfo2 as $instituteId => $instituteData){
			$instObj = $InstituteRepository->find($instituteId);
			for($i=0;$i<count($instituteData); $i++){
				$finalArrayInfo2[$instituteId]['instituteName']=$instObj->getName();
			}
		}
		
		error_log(':::CAMarketingReport:::'.'start of institute level sheet');
		foreach($subcatArrays as $subcatArray){
			
			$objWorkSheet = $objPHPExcel->createSheet($j);

			// Set the active Excel worksheet
			
			$objPHPExcel->setActiveSheetIndex($j);  
			
			$objPHPExcel->getActiveSheet()->setCellValue('A2', '');
			$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Last 30 days');
			$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->mergeCells('C2:G2');
			
			$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Last 7 days');
			$objPHPExcel->getActiveSheet()->getStyle('H2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H2:L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A3:L3')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->mergeCells('H2:L2');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25); 
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Institute ID');
			$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Institute Name');
			$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Questions Asked');
			$objPHPExcel->getActiveSheet()->setCellValue('D3', 'Questions Answered By CR');
			$objPHPExcel->getActiveSheet()->setCellValue('E3', 'Questions Answered Total');
			$objPHPExcel->getActiveSheet()->setCellValue('F3', 'Questions unanswered by CRs');
			$objPHPExcel->getActiveSheet()->setCellValue('G3', 'Pending Moderation CR');
			// $objPHPExcel->getActiveSheet()->setCellValue('H3', 'Pending Moderation');
			$objPHPExcel->getActiveSheet()->setCellValue('H3', 'Questions Asked');
			$objPHPExcel->getActiveSheet()->setCellValue('I3', 'Questions Answered By CR');
			$objPHPExcel->getActiveSheet()->setCellValue('J3', 'Questions Answered Total');
			$objPHPExcel->getActiveSheet()->setCellValue('K3', 'Questions unanswered by CRs');
			$objPHPExcel->getActiveSheet()->setCellValue('L3', 'Pending Moderation CR');
			// $objPHPExcel->getActiveSheet()->setCellValue('N3', 'Pending Moderation');
			
			$i=4;
			
			foreach($finalArrayInfo2 as $key=>$result){
				
				if($result[$subcatArray]['category_id'] == $subcatArray){
					$instituteName= $result['instituteName'];
					$questionAskedMonth= $result[$subcatArray]['questionAskedMonth'];
					$questionAnsweredMonth=$result[$subcatArray]['questionAnsweredMonth'];
					$questionAnsweredCRMonth=$result[$subcatArray]['questionAnsweredByCRMonth'];
					$questionUnansweredMonth= $result[$subcatArray]['questionUnansweredMonth'];
					$questionAskedWeek=$result[$subcatArray]['questionAskedWeek'];
					$questionAnsweredWeek=$result[$subcatArray]['questionAnsweredWeek'];
					$questionAnsweredCRWeek=$result[$subcatArray]['questionAnsweredByCRWeek'];
					$questionUnansweredWeek= $result[$subcatArray]['questionUnansweredWeek'];
					$pendingAnsInsCRMonth = $result[$subcatArray]['pendingAnsCountCRMonth'];
					$pendingAnsInsCRWeek = $result[$subcatArray]['pendingAnsCountCRWeek'];
					$instituteId = $result[$subcatArray]['instituteId'];
					
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $instituteId);	
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $instituteName);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $questionAskedMonth);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $questionAnsweredCRMonth);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $questionAnsweredMonth);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $questionUnansweredMonth);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $pendingAnsInsCRMonth);
					// $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $pendingAnsInsMonth);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $questionAskedWeek);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $questionAnsweredCRWeek);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $questionAnsweredWeek);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $questionUnansweredWeek);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $pendingAnsInsCRWeek);
					// $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $pendingAnsInsWeek);
					$i++;
				
				}
			
			// Rename sheet
			if($subcatArray == 23){		
				$objPHPExcel->getActiveSheet()->setTitle('MBA - institute level');
			
			}else if($subcatArray == 56){
				$objPHPExcel->getActiveSheet()->setTitle('Engg. - institute level');
			}
				
			}
			$j++;
			// _p($finalArrayInfo2);
		}
		error_log(':::CAMarketingReport:::'.'end of institute level sheet');
		
		
		//MBA CR payment sheet
		$objWorkSheet = $objPHPExcel->createSheet($j);
		$objPHPExcel->setActiveSheetIndex($j);
		$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CA Name');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Outstanding amount');
		$i = 2;
		foreach($CAPaymentSheet['23'] as $val)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $val['caName']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $val['amountToBePaid']);
			$i++;
		}
		$objPHPExcel->getActiveSheet()->setTitle('MBA CR Payments');
		$j++;
		//Mentor payment sheet
		$objWorkSheet = $objPHPExcel->createSheet($j);
		$objPHPExcel->setActiveSheetIndex($j);
		$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Mentor Name');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Outstanding amount');
		$i = 2;
		foreach($CAPaymentSheet['56'] as $val)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $val['caName']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $val['amountToBePaid']);
			$i++;
		}
		$objPHPExcel->getActiveSheet()->setTitle('Mentor Payments');
		$j++;
		
		$documentName = "Campus_Reps_weekly_Data_Marketing_Report_".date('Y_m_d').'.xlsx';
                $documentURL = "/tmp/".$documentName;
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($documentURL);
		$this->load->library('alerts_client');
                $alertClient = new Alerts_client();
                $documentContent = base64_encode(file_get_contents($documentURL));
                $this->load->library('Ldbmis_client');
                $misObj = new Ldbmis_client();
                $type_id = $misObj->updateAttachment(1);

                $attachmentId = $alertClient->createAttachment("12",$type_id,'COURSE','E-Brochure',$documentContent,'Campus_Reps_weekly_Data_Marketing_Report'.date('Y_m_d').'.xlsx','doc','true');
                $attachment = array($attachmentId);
                $content = "<p>Hi,</p> <p>Please find  attached the marketing report for Campus Reps. </p><p>- Shiksha Tech.</p>";
		//$emailIdarray=array('soumendu.g@naukri.com','nasr.khan@shiksha.com','anurag.jain@shiksha.com','prateek.malpani@shiksha.com','prakash.sangam@naukri.com','surya.prakash@shiksha.com','puja.kamath@shiksha.com','ankur.gupta@shiksha.com','hari.vasudevan@shiksha.com');
		
		$emailIdarray = array('aneeket.barua@shiksha.com','ambreen.fatma@shiksha.com','saurabh.gupta@shiksha.com','campusconnect3@shiksha.com');
		foreach($emailIdarray as $key=>$emailId){
			$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$emailId,'Campus Rep Data Marketing Report on '.date('Y-m-d'),$content,"html",'','y',$attachment);
		}
		error_log(':::CAMarketingReport:::'.'end');
		$this->benchmark->mark('controller_end');
		$finalMemory = memory_get_usage();
		error_log(':::CAMarketingReport:::'.'overall memory usage = '.($finalMemory-$initialMemory).'bytes');
		error_log(':::CAMarketingReport:::'.'overall execution time = '.$this->benchmark->elapsed_time('controller_start', 'controller_end'));
	}
	
}
?>
