<?php

/**
 * Class RegistrationAPIs 
 * This is the class for maintaining wrapper APIs related to Registration/Response for PWA
 *
 * @author Mansi Gupta
 */

class RegistrationAPIs extends MX_Controller
{
	public function __construct(){
		
		$requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
		header("Access-Control-Allow-Origin: ".$requestHeader);
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
		header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
		header("Content-Type: application/json; charset=utf-8");

	}

	public function getLoggedInUserDetails() {

		$validationData = $this->getLoggedInUserData();
		
		if(!empty($validationData)) {

			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $validationData;
			$returnResponse         = json_encode($apiResponse);

		} else {

			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'User not logged in';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);

		}
		echo $returnResponse;

	}

	public function showResponseForm() {
		
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$data = array();
        $data['clientCourseId'] = $this->input->get('clientCourseId', true);
        
		if(empty($data['clientCourseId']) || $data['clientCourseId'] <= 0){
            return false;
        }
		
		$data['listingType']   = $this->input->get('listingType', true);
		
		$userDetails = $this->getLoggedInUserData();
		$userId      = $userDetails['userId'];

		if (!empty($userId)) {

            $data['userDetails'] = Modules::run('registration/RegistrationForms/_getUserDetails', $userId, true);

            if (!empty($data['userDetails']['isdCode']) && !empty($data['userDetails']['country'])) {
	            $data['userDetails']['isdCode'] = $data['userDetails']['isdCode'] . '-' . $data['userDetails']['country'];
	            unset($data['userDetails']['country']);
	        }else{
	            unset($data['userDetails']['isdCode']);
	        }
	        
        }

		$data['formType'] = 'response';

		$isdCode     = new \registration\libraries\FieldValueSources\IsdCode;
		$isdCodeData = $isdCode->getValues(array('source'=>'DB'));

		$isdCodeValues = array();
		foreach ($isdCodeData as $key => $value) {
			$isdCodeValues['isdCodeList'][$key] = $value['shiksha_countryName'].' (+'.$value['isdCode'].')';
			$isdCodeValues['isdCodeMap'][$key]  = $value['abbreviation'];
		}
		$data['isdCodeValues'] = $isdCodeValues;

		$paramsForCityList                        = array();
		$paramsForCityList['cities']              = TRUE;
		$paramsForCityList['removeVirtualCities'] = 'yes';
		
		$residenceCityValues = new \registration\libraries\FieldValueSources\ResidenceCityLocality;
		$values              = $residenceCityValues->getValues($paramsForCityList);
		$data['cityList']    = $values;
		$cityArr             = array();
		foreach ($values['metroCities'] as $city) {
			$cityArr['Popular Cities'][$city['cityId']] = $city['cityName'];
		}
		
		foreach ($values['stateCities'] as $stateCitiesData) {
			foreach ($stateCitiesData['cityMap'] as $city) {
				$cityArr[$stateCitiesData['StateName']][$city['CityId']] = $city['CityName'];
			}
		}
		$data['cities'] = $cityArr;

		if($data['listingType'] != 'course') {
			//$data['instituteCourses'] = Modules::run('registration/RegistrationForms/_extract_courses_from_institute', $data['clientCourseId'], $data['listingType'],true);
			$data['instituteCourses'] = $this->getBaseCourseAndClientCourseByInstituteId($data['clientCourseId']);
        }

		$prefYearValues            = new \registration\libraries\FieldValueSources\PrefYear;
		$data['prefYearValues']    = $prefYearValues->getValues();
		$data['prefYearMandatory'] = (PREF_YEAR_MANDATORY == 1) ? true : false;
		$data['prefYearHidden']    = (PREF_YEAR_HIDDEN == 1) ? true : false;
		$data['countryName']       = strtoupper($_SERVER['GEOIP_COUNTRY_NAME']);
		
		if(!empty($data)) {

			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);

		} else {

			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting response form data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);

		}
		echo $returnResponse;
        
    }

    public function getBaseCourseAndClientCourseByInstituteId ($instituteId) {
    	
    	$this->load->config("registrationFormConfig");
   		$apiUrl = $this->config->item('base_courses_by_institute_id_api');
		$apiData = array('instituteId'=>$instituteId);
		$headers = array('AUTHREQUEST:INFOEDGE_SHIKSHA');

		$this->ChpClient = $this->load->library('chp/ChpClient');			
        $result = $this->ChpClient->makeCURLCall('GET',$apiUrl, $apiData, $headers);
       // _p($result);exit;
        $apiResult = json_decode($result, true);

        if(!empty($apiResult)) {
        	$i= 0;
        	if(!empty($apiResult['data']['baseCourseIds'])) {
		       	$baseCourseIds = $apiResult['data']['baseCourseIds'];
		        $baseCourseObjects = $apiResult['data']['baseCourseObjects'];
		        foreach($baseCourseIds as $baseCourseId) {
		        	$instituteCourses[$i]['id'] = 'bc_'.$baseCourseId.'_'.$instituteId;
		        	$instituteCourses[$i]['type'] = 'basecourse';
		        	$instituteCourses[$i]['name'] = $baseCourseObjects[$baseCourseId]['name'];
		        	$i++;
		        }
	    	}

			if(!empty($apiResult['data']['nonBaseCourseCLP'])) {
		       	$clientCourseIds = $apiResult['data']['nonBaseCourseCLP'];
		        $clientCourseObjects = $apiResult['data']['nonBaseCourseCLPObjects'];
		        $clientCourseData = array();	
		        foreach($clientCourseIds as $clientCourseId) {
		        	$instituteCourses[$i]['id'] = 'cc_'.$clientCourseId.'_'.$instituteId;
		      		$instituteCourses[$i]['type'] = 'clientcourse';
		        	$instituteCourses[$i]['name'] = $clientCourseObjects[$clientCourseId]['name'];
		        	$i++;
		        }
	    	}
    	}
    	return $instituteCourses;
    }

    public function getFormByClientCourse() {

    	parse_str($_SERVER['QUERY_STRING'], $_GET);
    	$clientCourse = $this->input->get('clientCourse');
    	$clientCourseBaseCourse = split('_',$clientCourse);

    	if($clientCourseBaseCourse[0]=='bc'){

    		$this->load->config("registrationFormConfig");
       		$apiUrl = $this->config->item('getBIPResponseCourse');
			$apiData = array('instituteId'=>$clientCourseBaseCourse[2],'baseCourseId'=>$clientCourseBaseCourse[1]);
			$headers = array('AUTHREQUEST:INFOEDGE_SHIKSHA');

			$this->ChpClient = $this->load->library('chp/ChpClient');			
	        $result = $this->ChpClient->makeCURLCall('GET',$apiUrl, $apiData, $headers);
	        $apiResult = json_decode($result, true);
	        $clientCourse = $apiResult['data'];
    	}
    	else if($clientCourseBaseCourse[0]=='cc')
    	{
    		$clientCourse = $clientCourseBaseCourse[1];
    	}
		
        if (empty($clientCourse)) {
            return false;
        }

		$data = Modules::run('registration/RegistrationForms/_getClientCourseData', $clientCourse, array('primary_hierarchy', 'hierarhies', 'entryBaseCourse', 'executiveFlag', 'level', 'credential', 'exams', 'locations', 'mode'));
		
		$streamIdArray = array();
        $streamIdArray = array_keys($data['primary_hierarchy']);
        $streamId      = $streamIdArray[0];
        if (empty($streamId)) {
            return false;
        }
		$hierarchies            = array();
		$hierarchies[$streamId] = $data['hierarhies'][$streamId];
		$data['hierarhies']     = $hierarchies;

		$data['mappedHierarchies'] = Modules::run('registration/RegistrationForms/_extractSpecializationsFromStreamSubStreamComb', $data['hierarhies']);
		
		$data['baseCourse']        = Modules::run('registration/RegistrationForms/_getFilteredMappedBasedCourseByLevelAndCredential', $data['mappedHierarchies'], $data['level'], $data['credential'], $data['baseCourse'], true);
        
        global $managementStreamMR;
        global $postGrad;
        global $certificateCredential;

        if(is_object($data['level'])) {
        	$data['level'] = $data['level']->getId();
        }

        if(is_object($data['credential'])) {
        	$data['credential'] = $data['credential']->getId();
        }

        if (!empty($data['isExecutive']) || ($streamId == $managementStreamMR && $data['credential'] == $certificateCredential) || $data['level'] == $postGrad) {

            $workExFieldValues  = new \registration\libraries\FieldValueSources\WorkExperience;
            $data['workExList'] = $workExFieldValues->getValues();

        }

        /* Ask exam only for Full time BTECH/MBA */
        if(!(($data['baseCourse'] == MANAGEMENT_COURSE || $data['baseCourse'] == ENGINEERING_COURSE) && $data['mode'] == '20')){
            unset($data['examList']);
        }

        if(!empty($data)) {
        	$data['clientCourse'] = $clientCourse;
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);

		} else {

			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting course data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);

		}
		echo $returnResponse;

    }

    public function getLocalities()
    {
		$data        = array();
		$cityId      = $this->input->post('cityId', true);
		$baseCourses = $this->input->post('baseCourses', true);
		
		if (empty($cityId) || $cityId <= 0 || !is_array($baseCourses)) {
            return false;
        }

        foreach ($baseCourses as $key => $value) {
            if ($value <= 0) {
                unset($baseCourses[$key]);
            }
        }

        if (empty($baseCourses)) {
            return false;
        }

        $this->load->library('user/UserLib');
        $userLib = new UserLib;
        
        /*Checking for the hyper local courses */
        $hyperLocalData = $userLib->getHyperAndNonhyperCoursesCount($baseCourses);
        
        if ($hyperLocalData['hyperlocal'] >= 1) {

	        $params           = array();
			$params['cityId'] = $cityId;
			
			$localityValues = new \registration\libraries\FieldValueSources\ResidenceLocality;
			$localities     = $localityValues->getValues($params);
			$localitiesArr  = array();

		    foreach($localities as $zoneId => $localitiesInZone) {
				$firstLocality                             = reset($localitiesInZone);
				$localitiesArr[$firstLocality['zoneName']] = array();

		        foreach($localitiesInZone as $locality) {

		            $localitiesArr[$firstLocality['zoneName']][$locality['localityId']] = $locality['localityName'];

		        }

		    }

		    if(!empty($localitiesArr)){

				$data['localitiesMap'] = $localitiesArr;
				$data['noLocalities']  = false;

		    } else {

		    	$data['noLocalities'] = true;

		    }

	    } else {

	    	$data['noLocalities'] = true;
	    	
	    }

        if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting locality data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function checkForExistingUser() {
    	
    	$data                   = array();
		$data['isExistingUser'] = Modules::run('userVerification/userVerification/checkIfUserExist',array(),true);
    	
    	if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting verification data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;
    	
    }

    public function verifyUserForOTP() {
    	
		$data                = array();
		$data['otpResponse'] = Modules::run('userVerification/userVerification/verifyUser',array(),true);
    	
    	if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting verification data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function verifyOTPCall() {
    	
		$data               = array();
		$data['isVerified'] = Modules::run('userVerification/userVerification/verifyOTP');
    	
    	if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting OTP verification data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function register() {

		$data                     = array();
		$registerResponse         = Modules::run('registration/Registration/register');
		$data['registerResponse'] = json_decode($registerResponse,true);

		if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting registration data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function updateUser() {

		$data                       = array();
		$updateUserResponse         = Modules::run('registration/Registration/updateUser', true);
		$data['updateUserResponse'] = json_decode($updateUserResponse,true);

		if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting updateUser data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function createResponse() {

		$data                   = array();
		$courseResponse         = Modules::run('response/Response/createResponse');
		$data['courseResponse'] = json_decode($courseResponse,true);

		if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting create response data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function isValidResponseUser() {

		$data                = array();
		$data['isValidUser'] = Modules::run('registration/RegistrationForms/isValidUser');
		
		if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting valid user data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function storeResponseDataForUnverifiedUser() {

		$userDetails              = $this->getLoggedInUserData();
		$userId                   = $userDetails['userId'];
		$params                   = array();
		$params['clientCourse']   = $this->input->post('clientCourse', true);
		$params['listing_type']   = $this->input->post('listing_type', true);
		$params['tracking_keyid'] = $this->input->post('tracking_keyid', true);
		$params['action_type']    = $this->input->post('action_type', true);
		$params['regFormId']      = $this->input->post('regFormId', true);

    	$this->load->library('user/UserLib');
		$userLib              = new UserLib;
		$data                 = array();
		$data['lastInsertId'] = $userLib->storeTempResponseInterestData($params, $userId);
		
		if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error storing response data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function trackFieldData() {

    	$data                  = array();
		$data['trackDataFlag'] = Modules::run('registration/RegistrationForms/trackInvalidFieldData');
		
		if(!empty($data)) {
        	
			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);
			
		} else {
			
			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting track field data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);
			
		}
		echo $returnResponse;

    }

    public function showExamResponseForm() {
		
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$data = array();
        $data['examGroupId'] = $this->input->get('examGroupId', true);
        
		if(empty($data['examGroupId']) || $data['examGroupId'] <= 0){
            return false;
        }
		
		$userDetails = $this->getLoggedInUserData();
		$userId      = $userDetails['userId'];

		if (!empty($userId)) {

            $data['userDetails'] = Modules::run('registration/RegistrationForms/_getUserDetails', $userId, true);

            if (!empty($data['userDetails']['isdCode']) && !empty($data['userDetails']['country'])) {
	            $data['userDetails']['isdCode'] = $data['userDetails']['isdCode'] . '-' . $data['userDetails']['country'];
	            unset($data['userDetails']['country']);
	        }else{
	            unset($data['userDetails']['isdCode']);
	        }
	        
        }

		$isdCode     = new \registration\libraries\FieldValueSources\IsdCode;
		$isdCodeData = $isdCode->getValues(array('source'=>'DB'));

		$isdCodeValues = array();
		foreach ($isdCodeData as $key => $value) {
			$isdCodeValues['isdCodeList'][$key] = $value['shiksha_countryName'].' (+'.$value['isdCode'].')';
			$isdCodeValues['isdCodeMap'][$key]  = $value['abbreviation'];
		}
		$data['isdCodeValues'] = $isdCodeValues;

		$paramsForCityList                        = array();
		$paramsForCityList['cities']              = TRUE;
		$paramsForCityList['removeVirtualCities'] = 'yes';
		
		$residenceCityValues = new \registration\libraries\FieldValueSources\ResidenceCityLocality;
		$values              = $residenceCityValues->getValues($paramsForCityList);
		$data['cityList']    = $values;
		$cityArr             = array();
		foreach ($values['metroCities'] as $city) {
			$cityArr['Popular Cities'][$city['cityId']] = $city['cityName'];
		}
		
		foreach ($values['stateCities'] as $stateCitiesData) {
			foreach ($stateCitiesData['cityMap'] as $city) {
				$cityArr[$stateCitiesData['StateName']][$city['CityId']] = $city['CityName'];
			}
		}
		$data['cities'] = $cityArr;

		$prefYearValues            = new \registration\libraries\FieldValueSources\PrefYear;
		$data['prefYearValues']    = $prefYearValues->getValues();
		$data['prefYearMandatory'] = (PREF_YEAR_MANDATORY == 1) ? true : false;
		$data['prefYearHidden']    = (PREF_YEAR_HIDDEN == 1) ? true : false;
		$data['countryName']       = strtoupper($_SERVER['GEOIP_COUNTRY_NAME']);
		$data['formType']          = 'examResponse';

		$this->load->builder('ExamBuilder','examPages');
        $examBuilder    = new ExamBuilder();
        $examRepository = $examBuilder->getExamRepository();
        $examGroupObj   = $examRepository->findGroup($data['examGroupId']);
        if(empty($examGroupObj)){
            return false;
        }
        $examId       = $examGroupObj->getExamId();
        $examBasicObj = $examRepository->find($examId);
        if(empty($examBasicObj)){
            return false;
        }
        $groupMappedExams = $examBasicObj->getGroupMappedToExam();
        if(count($groupMappedExams) > 1){
            $data['groupMappedExams'] = $groupMappedExams;
        }
        $data['examGroupData'] = $this->getFormByExamGroup($data['examGroupId']);
		
		if(!empty($data)) {

			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);

		} else {

			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting exam response form data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);

		}
		echo $returnResponse;
        
    }

    public function getFormByExamGroup($examGroup) {

    	parse_str($_SERVER['QUERY_STRING'], $_GET);
    	$examGroupId = isset($examGroup) ? $examGroup : $this->input->get('examGroupId');
		
		if (empty($examGroupId)) {
            return false;
        }

		$data = Modules::run('registration/RegistrationForms/_getExamGroupData', $examGroupId, array('primary_hierarchy', 'hierarchies', 'baseCourse', 'mode', 'level'));
		
		$streamIdArray = array();
        $streamIdArray = array_keys($data['primary_hierarchy']);
        $streamId      = $streamIdArray[0];
        if (empty($streamId)) {
            return false;
        }
		if(!empty($data['hierarchies'])){
            $hierarchies            = array();
            $hierarchies[$streamId] = $data['hierarchies'][$streamId];
            $data['hierarchies']    = $hierarchies;
        } else {
            $data['hierarchies']    = $data['primary_hierarchy'];
        }

		$data['mappedHierarchies'] = Modules::run('registration/RegistrationForms/_extractSpecializationsFromStreamSubStreamComb', $data['hierarchies']);
		
		if(count($data['level']) == 1){
            $key   = array_keys($data['level']);
            $level = $key[0];
            $data['baseCourse'] = Modules::run('registration/RegistrationForms/_getFilteredMappedBasedCourseByLevelAndCredential', $data['mappedHierarchies'], $level, '', $data['baseCourse'], false, true);
        }

        if(isset($examGroup) && $examGroup > 0) {
        	return $data;
        }

        if(!empty($data)) {

			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $data;
			$returnResponse         = json_encode($apiResponse);

		} else {

			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting exam group data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);

		}
		echo $returnResponse;

    }

    public function filterBaseCoursesByLevel() {

    	parse_str($_SERVER['QUERY_STRING'], $_GET);
    	$examGroupId = $this->input->get('examGroupId');
        $levelId     = $this->input->get('levelId');
        
        if (empty($examGroupId) || empty($levelId)) {
            return false;
        }

        $requiredList = array();
        $requiredList = array('primary_hierarchy', 'hierarchies', 'baseCourse');
        
		$data          = array();
		$data          = Modules::run('registration/RegistrationForms/_getExamGroupData', $examGroupId, $requiredList);
		$streamIdArray = array();
		$streamIdArray = array_keys($data['primary_hierarchy']);
		$streamId      = $streamIdArray[0];
        if (empty($streamId)) {
            return false;
        }
		$hierarchies               = array();
		$hierarchies[$streamId]    = $data['hierarchies'][$streamId];
		$data['hierarchies']       = $hierarchies;
		$data['mappedHierarchies'] = Modules::run('registration/RegistrationForms/_extractSpecializationsFromStreamSubStreamComb', $data['hierarchies']);
		
		$baseCourses = Modules::run('registration/RegistrationForms/_getFilteredMappedBasedCourseByLevelAndCredential', $data['mappedHierarchies'], $levelId, '', $data['baseCourse'], false, true);

        if(!is_array($baseCourses)){
            $baseCourses = array($baseCourses);
        }
		$returnData               = array();
		$returnData['baseCourse'] = $baseCourses;

        if(!empty($returnData)) {

			$apiResponse['status']  = 'success';
			$apiResponse['message'] = null;
			$apiResponse['data']    = $returnData;
			$returnResponse         = json_encode($apiResponse);

		} else {

			$apiResponse['status']  = 'failed';
			$apiResponse['message'] = 'error getting exam group data';
			$apiResponse['data']    = null;
			$returnResponse         = json_encode($apiResponse);

		}
		echo $returnResponse;
        
    }

    public function isValidExamResponse($examGroupId, $userId = null, $isViewedCall = false)
    {
        if (empty($userId)) {
            $userDetails = $this->getLoggedInUserData();
            $userId      = $userDetails['userId'];
        }

        if (empty($examGroupId)) {
            $examGroupId = $this->input->post('examGroupId', true);
        }
        
        if(!empty($_POST['isViewedCall']) && $_POST['isViewedCall'] == 'yes'){
            $isViewedCall = true;
        }

        $skipUserInterestDetails = false;
        if(!empty($examGroupId)){
            $skipUserInterestDetails = true;
        }

        $userDetails = Modules::run('registration/RegistrationForms/_getUserDetails', $userId, $skipUserInterestDetails);


        if($isViewedCall == true){
            if($userDetails['mobileVerified'] != 1){
                $apiResponse['status']  = 'success';
				$apiResponse['message'] = null;
				$apiResponse['data']    = "mobile_not_verified";
				$returnResponse         = json_encode($apiResponse);
				echo $returnResponse;
				return;
            }
        }
        
        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB', array('courseGroup' => 'nationalDefault', 'context' => 'default'));
        
        $fields = $registrationForm->getFields();

        //Skipping check of residence city/locality for international user
        if ($userDetails['country'] != '2') {
            unset($fields['residenceCityLocality']);
        }

        /* As virtual cities only exist in SA site  */
        $virtualCities = array('12292', '10223', '10224');
        if(in_array($userDetails['residenceCityLocality'], $virtualCities)){
        	$apiResponse['message'] = null;
			$apiResponse['data']    = false;
			$returnResponse         = json_encode($apiResponse);
			echo $returnResponse;
			return;
        }

        $skipFields = array('password', 'residenceLocality');

        if ($isViewedCall==true)
        {
            $skipFields[] = 'prefYear'; 
        }

        if(!empty($examGroupId)){
            $skipFields = array_merge($skipFields, array('stream', 'educationType', 'baseCourses', 'subStreamSpecializations'));
        }

        foreach ($skipFields as $key => $value) {
            unset($fields[$value]);
        }

        foreach ($fields as $key => $field) {
            if (!$userDetails[$key] && $field->isMandatory()) {
            	$apiResponse['message'] = null;
				$apiResponse['data']    = false;
				$returnResponse         = json_encode($apiResponse);
				echo $returnResponse;
				return;
            }
        }

        if(!empty($examGroupId)){

            $this->load->builder('ExamBuilder','examPages');
            $examBuilder     = new ExamBuilder();
            $examRepository  = $examBuilder->getExamRepository();
            $examGroupObj    = $examRepository->findGroup($examGroupId);
            if(!is_object($examGroupObj)) {
            	$apiResponse['message'] = null;
				$apiResponse['data']    = false;
				$returnResponse         = json_encode($apiResponse);
				echo $returnResponse;
				return;
            }
            $examId          = $examGroupObj->getExamId();
            $examBasicObj    = $examRepository->find($examId);
            if(!is_object($examBasicObj)) {
            	$apiResponse['message'] = null;
				$apiResponse['data']    = false;
				$returnResponse         = json_encode($apiResponse);
				echo $returnResponse;
				return;
            }
            $groupMappedArr  = $examBasicObj->getGroupMappedToExam();
            if(count($groupMappedArr) > 1){
            	$apiResponse['message'] = null;
				$apiResponse['data']    = false;
				$returnResponse         = json_encode($apiResponse);
				echo $returnResponse;
				return;
            }
            
            $examGroupData = array();
            $requiredList  = array('baseCourse', 'level');
            $examGroupData =Modules::run('registration/RegistrationForms/_getExamGroupData', $examGroupId, $requiredList);
            
            if(count($examGroupData['level']) > 1){
            	$apiResponse['message'] = null;
				$apiResponse['data']    = false;
				$returnResponse         = json_encode($apiResponse);
				echo $returnResponse;
				return;
            }

            /* check for hyper local courses */
            if(!empty($examGroupData['baseCourse'])){

                if(!isset($userDetails['residenceLocality'])){
                    
                    $this->load->library('user/UserLib');
                    $userLib = new UserLib;
                    error_log("####examGroupData baseCourses ".print_r($examGroupData['baseCourse'],true));
                    $hyperLocalData = $userLib->getHyperAndNonhyperCoursesCount($examGroupData['baseCourse']);
                    
                    if ($hyperLocalData['hyperlocal'] > 0) {

                        if(!empty($userDetails['residenceCityLocality'])){

                            $registrationForm = new \registration\libraries\Forms\LDB;
                            $localityField    = $registrationForm->getField('preferredStudyLocality');
                            $localities       = $localityField->getValues(array('cityId' => $userDetails['residenceCityLocality']));
                            
                            if(!empty($localities)){
                            	$apiResponse['message'] = null;
								$apiResponse['data']    = false;
								$returnResponse         = json_encode($apiResponse);
								echo $returnResponse;
								return;
                            }

                        }
                        
                    }

                }

            }

        }

        if($userDetails['mobileVerified'] != 1){
            $apiResponse['status']  = 'success';
				$apiResponse['message'] = null;
				$apiResponse['data']    = "mobile_not_verified";
				$returnResponse         = json_encode($apiResponse);
				echo $returnResponse;
				return;
        }

        $apiResponse['status']  = 'success';
		$apiResponse['message'] = null;
		$apiResponse['data']    = true;
		$returnResponse         = json_encode($apiResponse);
		echo $returnResponse;
		return;
    }

    public function reportErrorOnForm(){
    	Modules::run('registration/RegistrationForms/reportError');
    }

}

?>
