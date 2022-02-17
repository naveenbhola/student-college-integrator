<?php
/**
 * searchCriteria Class
 *
 * @author
 * @package Mailer
 *
 */

class searchCriteria extends MX_Controller
{
	var $appId = 1;


    /**
     * Calling Function for New User set Form Creation For India
     *
     * @param string $userset_type
     */ 
	public function addNewUserset($userset_type) {
		$viewFile = 'searchCriteria';
		$criteriaNo = 0;

		if($userset_type == 'activity') {
			$this->addNewUsersetActivity($viewFile, 1, $userset_type);
		}
		else {
			$this->addNewUsersetIndia($viewFile, $criteriaNo, $userset_type);
		}

	}

    /**
     * New User set Form Creation For India
     *
     * @param string $submitURL 
     * @param string $userset_type
     */ 
	public function addNewUsersetIndia($viewFile, $criteriaNo = 1, $userset_type = 'profile_india') {

		$this->cmsUserValidation();
		$data = $this->getDataForSearchForm($criteriaNo, $userset_type);
		echo $this->load->view('userSearchCriteria/'.$viewFile,$data);

	}

	/**
     * New User Set Form Creation For Activity-Based Search
     *
     * @param string $viewFile 
     * @param string $criteriaNo 
     * @param string $userset_type
     */ 
	public function addNewUsersetActivity($viewFile, $criteriaNo = 1, $userset_type = 'activity') {

		$this->cmsUserValidation();
		$data = $this->getDataForSearchForm($criteriaNo, $userset_type);
		echo $this->load->view('userSearchCriteria/'.$viewFile,$data);

	}

    /**
     * Get all SubStream & Specialization by Stream
     */ 
	public function getSubStreamSpecializationByStream() {

		$this->cmsUserValidation();

		$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'nationalLeadSearch' ,'context' => 'search'));

        $fields = $registrationForm->getFields();
        $params = array();
        $streamId = $this->input->post('streamId', true);
        $params['streamIds'] = array($streamId);
        $subStreamSpecializations = $fields['subStreamSpecializations']->getValues($params);

        $data = array();
        $data['subStreamSpecializations'] = $subStreamSpecializations[$streamId];
        $data['criteriaNo'] = $this->input->post('criteriaNo', true);

        if(!empty($data['subStreamSpecializations']['substreams']) || !empty($data['subStreamSpecializations']['specializations'])) {
			$this->load->view('userSearchCriteria/searchWidgets/substreamSpecialization',$data);
		}

	}

	/**
     * Get all Courses by Stream, SubStream & Specialization
     */ 
	public function getCoursesByStreamSubStreamSpecialization() {

		$this->cmsUserValidation();

	    $streamId = $this->input->post('streamId', true);
        $selectedSubStreams = (array)json_decode($this->input->post('selectedSubStreams', true));
        $selectedSpecializations = json_decode($this->input->post('selectedSpecializations', true));
        $selectedFullSubStreams = (array)json_decode($this->input->post('selectedFullSubStreams', true));

		$this->load->library('userSearchCriteria/UserSearchCriteria');
		$searchCriteria = new UserSearchCriteria();
		$baseEntityArr = $searchCriteria->formatStreamSubStreamSpecialization($streamId, $selectedSubStreams, $selectedSpecializations, $selectedFullSubStreams);

        if(!empty($baseEntityArr)) {

        	$hyperlocal = $this->input->post('hyperlocal', true);
        	
        	$params = array('arrangeInAlpha'=>'yes');
	        $params['baseEntityArr'] = $baseEntityArr;
	        if($hyperlocal == 'Yes' || $hyperlocal == 'No') {
	        	$params['isHyperLocal'] = $hyperlocal;
	    	}

			$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'nationalLeadSearch' ,'context' => 'search'));
	        $fields = $registrationForm->getFields();

	        $courses = $fields['baseCourses']->getValues($params);
	        $popularCourses = array();
	        $popularCourses['PopularCourses'] = $courses['Popular Courses'];
	        unset($courses['Popular Courses']);

	        $data = array();
	        $data['courses'] = array_merge($popularCourses, $courses);	        
	        $data['criteriaNo'] = $this->input->post('criteriaNo', true);

	        if(!empty($data['courses'])) {
				$this->load->view('userSearchCriteria/searchWidgets/courses',$data);
			}

		}

	}


	/**
     * Get all Exams by Stream, SubStream & Specialization
     */ 
	public function getExamsByAllCombinations() {

		$this->cmsUserValidation();

	    $streamId = $this->input->post('streamId', true);
        $selectedSubStreams = (array)json_decode($this->input->post('selectedSubStreams', true));
        $selectedSpecializations = json_decode($this->input->post('selectedSpecializations', true));

		$this->load->library('userSearchCriteria/UserSearchCriteria');
		$searchCriteria = new UserSearchCriteria();
		$baseEntityArr = $searchCriteria->formatStreamSubStreamSpecialization($streamId, $selectedSubStreams, $selectedSpecializations);

        if(!empty($baseEntityArr)) {

			$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'nationalLeadSearch' ,'context' => 'search'));
	        $fields = $registrationForm->getFields();	 

	    	$params = array();
	 	    $params['baseEntityArr'] = $baseEntityArr;
	        $params['courseIds'] = json_decode($this->input->post('selectedCourses', true));
	        $params['national'] = 'yes';
	        
			$data = array();
	        $data['exams'] = $fields['exams']->getValues($params);
	        $data['criteriaNo'] = $this->input->post('criteriaNo', true);

	        if(!empty($data['exams'])) {
				$this->load->view('userSearchCriteria/searchWidgets/exams',$data);
			}

		}

	}


    /**
     * Get all Localities by city
     */ 
	public function getLocalitiesByCity() {
		
		$this->cmsUserValidation();

        $cityId = $this->input->post('cityId', true);
        $cityName = $this->input->post('cityName', true);
        $isVirtualCity = $this->input->post('isVirtualCity', true);
        $criteriaNo = $this->input->post('criteriaNo', true);
        $virtualCities = (array) json_decode($this->input->post('virtualCities', true));
        $alreadyExistCityIds = (array) json_decode($this->input->post('alreadyExistCityIds', true));

        $citiesArray = array();$cities = array();
        if(($isVirtualCity == '1') && (!empty($virtualCities))) {

			$i=0;
			foreach($virtualCities as $virtualCityDetail) {
				if(!in_array($virtualCityDetail->city_id, $alreadyExistCityIds)) {
					$cities[$i]['city_id'] = $virtualCityDetail->city_id;
					$cities[$i]['city_name'] = $virtualCityDetail->city_name;

					$citiesArray[] = $virtualCityDetail->city_id;
					$i++;
				}
			}			

			if(empty($cities)) {
        		$isVirtualCity = 0;
        	}
        } else {
        	$isVirtualCity = 0;
        }

        if($isVirtualCity == 0) {
        	$citiesArray = array($cityId);

        	$cities[0]['city_id'] = $cityId;
        	$cities[0]['city_name'] = $cityName;
        } 

        $params = array();
        $params['criteriaNo'] = $criteriaNo;
        $params['cities'] = $cities;
        $params['citiesArray'] = $citiesArray;

		$this->displayLocality($params);

	}

 	/**
     * Display City Localities
     */ 
	public function displayLocality($params) {

		if(empty($params['citiesArray'])) {
			return;
		}

		$this->cmsUserValidation();

		$this->load->library('userSearchCriteria/UserSearchCriteria');
		$searchCriteria = new UserSearchCriteria();
		$citiesLocalities = $searchCriteria->getCityLocalities($params['citiesArray']);

		$data = array();
		$data['criteriaNo'] = $params['criteriaNo'];
		$data['cities'] = $params['cities'];
		$data['citiesLocalities'] = $citiesLocalities;

		$this->load->view('userSearchCriteria/searchWidgets/locality',$data);

	}

 	/**
     * Get all Localities by all cities
     */ 
	public function getLocalitiesByAllCities() {

		$this->cmsUserValidation();

        $cityDetails = json_decode($this->input->post('cityDetails', true));
        $virtualCities = json_decode($this->input->post('virtualCities', true));
        $isVirtualCity = $this->input->post('isVirtualCity', true);
        $criteriaNo = $this->input->post('criteriaNo', true);
        $alreadyExistCityIds = (array) json_decode($this->input->post('alreadyExistCityIds', true));

        $citiesArray = array();$cities = array(); $cityIds = '';$i=0;

        if($isVirtualCity == '1') {
        	if(!empty($virtualCities)) {	
				foreach($virtualCities as $virtualCityDetail) {
					$virtualCityDetail = (array)$virtualCityDetail;
					foreach($virtualCityDetail as $virtualCity) {
						if(!in_array($virtualCity->city_id, $alreadyExistCityIds)) {
							$cities[$i]['city_id'] = $virtualCity->city_id;
							$cities[$i]['city_name'] = $virtualCity->city_name;

							$citiesArray[] = $virtualCity->city_id;
							$i++;
						}
					}
				}
			}			
        } 

		foreach($cityDetails as $cityDetailId=>$cityDetailName) {
			if(!in_array($cityDetailId, $alreadyExistCityIds)) {
				$citiesArray[] = $cityDetailId;

				$cities[$i]['city_id'] = $cityDetailId;
				$cities[$i]['city_name'] = $cityDetailName;
				$i++;
			}
		}

		if(!empty($citiesArray)) {
	        $params = array();
	        $params['criteriaNo'] = $criteriaNo;
	        $params['cities'] = $cities;
	        $params['citiesArray'] = $citiesArray;
			$this->displayLocality($params);
		}
	}

 	/**
     * Add new User Set in DB
     */ 
	public function saveUsersetCriteria() {
		
		$cmsUserInfo = $this->cmsUserValidation();
		
		$userid = $cmsUserInfo['userid'];
		$groupId = $cmsUserInfo['userGroupData']['group_id'];
		$usersetname = $this->input->post('usersetname',true);
		$usersettype = $this->input->post('usersettype',true);		

		$criteriaJSON = $this->formatSearchCriteria($usersettype);
	
		$this->load->model('mailer/mailermodel');
		$mailermodelObj = new mailermodel();
		$usersetId = $mailermodelObj->saveUserSearchCriteria($usersetname, $criteriaJSON, $usersettype, $userid, $groupId);
		
		echo $usersetId;

	}

 	/**
     * Validate User and get User related Information
     */ 
	function cmsUserValidation() {            

		$validity = $this->checkUserValidation();
              
		global $logged;
		global $userid;
		global $usergroup;
		$thisUrl = $_SERVER['REQUEST_URI'];
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		} else {
			$logged = "Yes";
			$userid = $validity[0]['userid'];
			$usergroup = $validity[0]['usergroup'];

			if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser") {
				header("location:/enterprise/Enterprise/migrateUser");
				exit;
			}

			$mailerModel = $this->load->model('mailer/mailermodel');			
    		$userGroupData = $mailerModel->getUserGroupInfo($userid);
			if($usergroup == "cms") {
        		if(empty($userGroupData)) {
					header("location:/enterprise/Enterprise/unauthorizedEnt");
					exit();
				}
			} 			
		}

		$data = array();
		$data['userid']=$userid;
		$data['usergroup']=$usergroup;
		$data['logged'] = $logged;
		$data['thisUrl'] = $thisUrl;
		$data['validity'] = $validity;
		$data['userGroupData'] = $userGroupData;

		return $data;
	}

 	/**
     * Validate User and get User related Information
     * 
     * @param string userset Type
     *
     */ 
	function formatSearchCriteria($usersettype, $countFlag = 'false', $needEachCriteriaCount = 'false') {

		if((empty($_POST)) || ($usersettype == '')) { return; }

		$targetDB = $this->input->post('targetDB',true);

		$postData = array();

		if($targetDB == 'all') {

			$postData['usersettype'] = $usersettype;
			$postData['targetDB'] = $targetDB;

			$timeRange = $this->input->post('timeRange',true);
			$postData['timeRange'] = $timeRange;

			$timeRangeDurationFrom = $this->input->post('timeRangeDurationFrom',true);
			if(!empty($timeRangeDurationFrom)) {
				$postData['timeRangeDurationFrom'] = $timeRangeDurationFrom;
			}
			$timeRangeDurationTo = $this->input->post('timeRangeDurationTo',true);
			if(!empty($timeRangeDurationTo)) {
				$postData['timeRangeDurationTo'] = $timeRangeDurationTo;
			}
			$timeRangeIntervalDays = $this->input->post('timeRangeIntervalDays',true);
			if(!empty($timeRangeIntervalDays)) {
				$postData['timeRangeIntervalDays'] = $timeRangeIntervalDays;
			}

			$includeActiveUsers = $this->input->post('includeActiveUsers',true);
			$postData['includeActiveUsers'] = $includeActiveUsers;

		} else {
			$postData = $_POST;
			$postData['needEachCriteriaCount'] = $needEachCriteriaCount;
		}

		if($countFlag == 'true') {
			$postData['countFlag'] = $countFlag;
		}
		$criteriaJSON = json_encode($postData);

		return $criteriaJSON;
	}

	function getUserCountInSearchCriteria() {

		$cmsUserInfo = $this->cmsUserValidation();

		$usersettype = $this->input->post('usersettype',true);		
		$targetDB = $this->input->post('targetDB',true);		
		$criteriaJSON = $this->formatSearchCriteria($usersettype, false, true);

		$this->load->library('mailer/MailerFactory');
		$mailerCriteriaEvaluatorService = MailerFactory::getMailerCriteriaEvaluatorService();
		$response = $mailerCriteriaEvaluatorService->getUserListByCriteria($criteriaJSON,$usersettype);
		$response =  sizeof($response);
		if($response != '' || $response >= 0) {
			if($targetDB == 'all' || $usersettype == 'Activity') {
				echo $response;
			} else {
				echo json_encode($response);
			}
		} 
	}

	function getDataForSearchForm($criteriaNo = 1, $userset_type = 'profile_india') {

		$this->load->library('userSearchCriteria/UserSearchCriteria');
		$searchCriteria = new UserSearchCriteria();
		$fieldsData = $searchCriteria->getFieldsData($userset_type);
		
		$data = array();
		$data['userset_type'] = $userset_type;
		if($userset_type=='profile_india'){
			$data['streams'] = $fieldsData['streams'];
			$data['workExperience'] = $fieldsData['workExperience'];
			$data['exams'] = $fieldsData['exams'];
			$data['mode'] = $fieldsData['mode'];
		}
		if($userset_type=='exam'){
			$data['streams'] = $fieldsData['streams'];
		}
		
		$data['virtualCities'] = $fieldsData['virtualCities'];
		$data['stateCities'] = $fieldsData['stateCities'];
		$data['virtualCitiesParentChildMapping'] = $fieldsData['virtualCitiesParentChildMapping'];
		$data['virtualCitiesChildParentMapping'] = $fieldsData['virtualCitiesChildParentMapping'];
		$data['citiesHavingLocalities'] = $fieldsData['citiesHavingLocalities'];
		$data['criteriaNo'] = $criteriaNo;

		
		$data['zoneToStateMapping'] = $fieldsData['zoneToStateMapping'];
		$data['allZonesInMetroCity'] = $fieldsData['allZonesInMetroCity'];
		$data['zoneIdMapping'] = $fieldsData['zoneIdMapping'];

		return $data;
	}

	public function getExamListFromStreamBaseCourse(){
		$baseCourseIds = $_POST['baseCourseIds'];
		$streamId = $_POST['streamId'];
		$this->load->library('userSearchCriteria/UserSearchCriteria');
		$searchCriteria = new UserSearchCriteria();
		$result = $searchCriteria->getExamListFromStreamBaseCourse($baseCourseIds,$streamId);
		// _P($result);die;
		echo json_encode($result);
	}
}
