<?php


/*

  Copyright 2007 Info Edge India Ltd

  $Rev::            $:  Revision of last commit
  $Author: raviraj $:  Author of last commit
  $Date: 2010/09/06 05:35:59 $:  Date of last commit

  $Id: shikshaDB.php,v 1.40.10.4 2010/09/06 05:35:59 raviraj Exp $:

 */

class shikshaDB extends MX_Controller {

    private $userStatus = 'false';

    function init() {
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client', 'LDB_Client', 'ajax', 'category_list_client'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        if ($this->userStatus[0]['usergroup'] != 'enterprise') {
            header("location:/enterprise/Enterprise/unauthorizedEnt");
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $headerTabs[0]['selectedTab'] = 31;
        $this->userStatus[0]['headerTabs'] = $headerTabs;
    }
    
    function getCityNameFronId($cityId){
	$this->dbLibObj = DbLibCommon::getInstance('User');
	$dbHandle = $this->_loadDatabaseHandle();
	$citiesName="";
	foreach($cityId as $key=>$val){
	    $query="SELECT `city_name` FROM `countryCityTable` WHERE `city_id` = ?";
	    $sql=$dbHandle->query($query, array($val));
	    $a=$sql->row();
	    if($citiesName!=""){
	    $citiesName.=", ";
	    }
	    $citiesName.=$a->city_name;
	    
	    }
	return $citiesName; 
    }
    
    function getLocalityNameFronId($LocId){
	$this->dbLibObj = DbLibCommon::getInstance('User');
	$dbHandle = $this->_loadDatabaseHandle();
	$citiesName="";
	foreach($LocId as $key=>$val){
	    $query="SELECT `localityName` FROM `localityCityMapping` WHERE `localityId` = ?";
	    $sql=$dbHandle->query($query, array($val));
	    $a=$sql->row();
	    if($citiesName!=""){
	    $citiesName.=", ";
	    }
	    $citiesName.=$a->localityName;
	    
	    }
	return $citiesName; 
    }
    

    function confirmShowContactDetails($userid) {
        $this->init();
        $response = $this->getCreditDetails($this->userStatus[0]['userid'], array($userid), 'view');
        echo json_encode($response);
    }

    function showContactDetails($userIdToView, $ExtraFlag='false', $actual_course_id = '') {
        $this->init();
        $this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);
        $ldbObj = new LDB_client();
        $viewAbleList = $ldbObj->getViewableUsers(12, array('0' => $userIdToView), $this->userStatus[0]['userid'], $ExtraFlag, TRUE, $actual_course_id);
        if(count($viewAbleList) == 0) {
        	$responseUserDetails = array('result' => 'noview');
        }
        else {
        	$responseUserDetails = $this->consumeLDBSubscription($this->userStatus[0]['userid'], array('0' => $userIdToView), 'view', $ExtraFlag, $actual_course_id);
        }
        echo json_encode($responseUserDetails);
    }

    function showContactDetailsForActivity($clientId, $activityId) {
        $this->init();
        $responseUserDetails = $this->consumeCreditsForActivity($clientId, $activityId);
        echo json_encode($responseUserDetails);
    }

    function sendEmailtoSelectedContacts() {
        $userIdCSV = $_POST['userIdCSV'];
        $subject = isset($_POST['subject']) ? $_POST ['subject'] : "(no subject)";
        $content = isset($_POST ['content']) ? $_POST ['content'] : "no content";
        $fromEmail = (isset($_POST ['fromEmail']) && ($_POST ['fromEmail'] != "")) ? $_POST ['fromEmail'] : ADMIN_EMAIL;
        $this->init();
        $ldbObj = new LDB_client();
        $responseUserDetails = $this->consumeLDBSubscription($this->userStatus[0]['userid'], explode(",", $userIdCSV), 'email');
        $this->load->library('alerts_client');
        $alertClientObj = new Alerts_client();
        foreach ($responseUserDetails['result'] as $row) {
            $response = $alertClientObj->externalQueueAdd("12", $fromEmail, $row[0]['email'], $subject, $content, $contentType = "text");
        }
        echo json_encode($responseUserDetails);
    }

    function sendEmailForActivity($clientId, $activityId, $subject="(no subject)", $content="no content", $fromEmail=ADMIN_EMAIL,$fromSenderName="SHIKSHA.COM", $displayData = array()) {
        $this->init();
        $responseUserDetails = $this->consumeCreditsForActivity($clientId, $activityId, $displayData);
        $this->load->library('alerts_client');
        $alertClientObj = new Alerts_client();
        foreach ($responseUserDetails['result'] as $row) {
            $response = $alertClientObj->externalQueueAdd("12", $fromEmail, $row[0]['email'], $subject, $content, $contentType = "text","0000-00-00 00:00:00",'n',array(),null,null,$fromSenderName);
        }
        $ldbObj = new LDB_client();
        $ldbObj->updateActivityStatus(1, $responseUserDetails['activityId'], 'done');
        echo json_encode($responseUserDetails);
    }

    function sendSMStoSelectedContacts() {
        $this->init();
        $userIdCSV = $_POST['userIdCSV'];
        $content = isset($_POST ['content']) ? $_POST ['content'] : "no content";
        $ldbObj = new LDB_client();
        $responseUserDetails = $this->consumeLDBSubscription($this->userStatus[0]['userid'], explode(",", $userIdCSV), 'sms');
        $this->load->library('alerts_client');
        $alertClientObj = new Alerts_client();
        foreach ($responseUserDetails['result'] as $row) {
            $alertResponse = $alertClientObj->addSmsQueueRecord(12, $row[0]['mobile'], $content, $this->userStatus[0]['userid'], "", "user-defined");
        }
        echo json_encode($responseUserDetails);
    }

    function sendSmsForActivity($clientId, $activityId, $content="no content", $displayData = array()) {
        $this->init();
        $responseUserDetails = $this->consumeCreditsForActivity($clientId, $activityId, $displayData);
        $this->load->library('alerts_client');
        $alertClientObj = new Alerts_client();
        foreach ($responseUserDetails['result'] as $row) {
            $alertResponse = $alertClientObj->addSmsQueueRecord(12, $row[0]['mobile'], $content, $this->userStatus[0]['userid'], "", "user-defined");
        }
        $ldbObj = new LDB_client();
        $ldbObj->updateActivityStatus(1, $responseUserDetails['activityId'], 'done');
        echo json_encode($responseUserDetails);
    }

    function downloadCSVForActivity($clientId, $activityId, $csvType, $filename, $inputData, $displayData, $inputDataMR) {
        $this->init();
        $trackingParams = array();
	
        if (isset($inputData)) {
                $inputArray = json_decode(base64_decode($inputData), true);
        } 
        else {
                $inputArray = array();
        }
        if (isset($displayData)) {
            $displayArray = json_decode(base64_decode($displayData), true);
        } 
        else {
            $displayArray = array();
        }
		
        Global $managementStreamMR;
        Global $engineeringtStreamMR;
		
        if(isset($displayArray['DesiredCourse']) && $displayArray['DesiredCourse'] != '' && in_array($displayArray['stream'],array($managementStreamMR, $engineeringtStreamMR))){
            require APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
            $displayArray['stream'] = $coursesList[$displayArray['DesiredCourse']]['stream_id'];
            $displayArray['actual_course_id'] = $coursesList[$displayArray['DesiredCourse']]['actual_course_id'];
            $displayArray['ProfileType'] = 'implicit';
        } else if ($displayArray['ExtraFlag'] != 'studyabroad') {
            // $displayArray['ProfileType'] = 'explicit';
        }
        
		if ($displayArray['ExtraFlag'] != 'studyabroad'){
		    $responseUserDetails = $this->consumeCreditsForActivity($clientId, $activityId, $displayArray);
        } else {
            $csvType = 'studyabroad';
            $responseUserDetails = $this->consumeCreditsForActivity($clientId, $activityId, array());
        }
        
        //Part: to exclude response on consultant
        $this->consultantmodel = $this->load->model('consultantProfile/consultantmodel');
        $userToBeExcluded = $this->consultantmodel->fetchUserResponseOnConsultant();

        $finalResult = array();

        $userIDArray = explode(",", $responseUserDetails['UserIdList']);

        
        foreach ($userIDArray as $key) {
            if(!in_array($key, $userToBeExcluded)){
               $finalResultArray[] =  $key;
            }
        }

        $finalResult = implode(",", $finalResultArray);
        $responseUserDetails['UserIdList'] = $finalResult;

        // Part: ends
        if($responseUserDetails['UserIdList'] !='') {

    	    $leads = array();
            $ldbObj = new LDB_client();

            if($csvType == 'national' || $csvType == 'nationalMR') {
                $trackingParams['product']  = 'Lead';                    
                if($csvType == 'nationalMR'){
                    $trackingParams['product']          = 'MR';                    
                }
                if($csvType == 'national'){
                    $displayArray['excludeMRPRofile'] = true;
                }
                
                $UserDataArray = Modules::run('MIS/SADownloadleads/getLeadDataFromSolr', $finalResultArray, $displayArray,true);
                
                $orderUserData = array();
                foreach ($UserDataArray as $key => $userData) {
                    $orderUserData[$userData['userId']] = $userData;
                }
                $newUserDataArray = array();
                foreach ($finalResultArray as $userId) {
                    $newUserDataArray[] = $orderUserData[$userId];
                }
                $UserDataArray = $newUserDataArray;
                
                if($csvType == 'national') {
                    
                    if($displayArray['stream'] == $managementStreamMR || $displayArray['stream'] == $engineeringtStreamMR){
                        // $UserDataArray = Modules::run('enterprise/enterpriseSearch/filterUserFullTimeMode', $UserDataArray);
                        $UserDataArray = Modules::run('enterprise/enterpriseSearch/filterMrProfiles', $inputArray, $UserDataArray);
                    }
                }
                
                if (!empty($inputDataMR)) {
                    $dataArrayMR = json_decode(base64_decode($inputDataMR), true);
                }
                
                if(count($dataArrayMR['courses']) && !empty($dataArrayMR['startDate']) && !empty($dataArrayMR['endDate'])) {        
                    $responseData = modules::run('lms/lmsServer/getMatchedResponses', $dataArrayMR['courses'], array(), $dataArrayMR['startDate'], $dataArrayMR['endDate'], FALSE);
                    $matchedCourses = array_keys($responseData['courses']);

                    foreach ($UserDataArray as $key => $userData) {
                        
                        $responseDetails = $userData['responseCourse'];
                        
                        foreach($responseDetails as $courseId) {
                            $clientCourseId = $responseData['courses'][$courseId][0];

                            if($clientCourseId){
                                $responseTimeArray = explode('T', $userData['response_time_'.$courseId]);
                                $UserDataArray[$key]['Response Date'] = $responseTimeArray[0];
                                $UserDataArray[$key]['Matched Response For'] = $displayArray['matchedCourses'][$clientCourseId].', '.$displayArray['matchedCoursesInstitute'][$clientCourseId];
                            }
                        }

                    }

                }

                $trackingParams = $this->formatEnterpriseTrackingData($inputArray, $displayArray, $csvType);
                $trackingParams['records_fetched']  = count($UserDataArray);
                $enterpriseTrackingLib = $this->load->library('enterprise/enterpriseDataTrackingLib');
                $enterpriseTrackingLib->trackEnterpriseData($trackingParams);
            }
            else {
                $UserDetailsArray = $ldbObj->sgetUserDetails(1, $responseUserDetails['UserIdList']);
                $UserDataArray = $this->createUserDataArray(json_decode($UserDetailsArray, true),$responseUsers,$displayArray);
            }


            $leads = $UserDataArray;

            $ColumnList = $this->getColumnList($csvType);
			
            $csv = '';
            foreach ($ColumnList as $ColumnName) {
                
                if($ColumnName == 'Creation Date'){
                    $ColumnName ='Lead Processed Date';
                }
                $csv .= '"' . $ColumnName . '",';
            }
            $csv .= "\n";
            foreach ($leads as $lead) {
                foreach ($ColumnList as $ColumnName) {
                    $csv .= '"' . $lead[$ColumnName] . '",';
                }
                $csv .= "\n";
            }
            $ldbObj->updateActivityStatus(1, $responseUserDetails['activityId'], 'done');
            return $csv;
        } else {
            return 'No_data_found';
        }
    }

    function createUserDataArray($UserDetailsArray, $responseUsers=NULL, $displayArray=NULL) {
        $LocalCourseArray = array();
		
		$this->load->model('LDB/leadsearchmodel');
        $leadSearchModel = new LeadSearchModel;
		$responseLocations = $leadSearchModel->getResponseLocations(array_keys($UserDetailsArray));
		
        foreach ($UserDetailsArray as $userDetails) {
            $formattedUserDetails = array();
	    $countries_array = array();
            $exam_taken = array();
	    $displayMatchedCourses = array();
			$formattedUserDetails['Name'] = $userDetails['firstname']." ".$userDetails['lastname'];
            $formattedUserDetails['First Name'] = $userDetails['firstname'];
            $formattedUserDetails['Last Name'] = $userDetails['lastname'];
            $formattedUserDetails['Gender'] = $userDetails['gender'];
            $formattedUserDetails['Age'] = $userDetails['age'];
            $formattedUserDetails['ISD Code'] = $userDetails['isdCode'];
            $formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName'];
            //For Study Abroad Desired Course is Desired Course Level
            $formattedUserDetails['Desired Course Level'] = $formattedUserDetails['Desired Course'];
	    if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') {
		    $courseLevel = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseLevel1'];
		    if($courseLevel == 'UG') {
			    $formattedUserDetails['Desired Course Level'] = 'Bachelors';
		    }
		    if($courseLevel == 'PG') {
			    $formattedUserDetails['Desired Course Level'] = 'Masters';
		    }
	    }
            $formattedUserDetails['Field of Interest'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CategoryName'];
            if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') {
		global $studyAbroadPopularCourses;
		if($formattedUserDetails['Field of Interest'] == "All") {
		    $desiredCourseId = $userDetails["PrefData"][0]['DesiredCourse'];
		    if(array_key_exists($desiredCourseId,$studyAbroadPopularCourses)) {
		        $formattedUserDetails['Field of Interest'] = $studyAbroadPopularCourses[$desiredCourseId];
		    }

		}
	    }
	    $formattedUserDetails['Desired Specialization'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['SpecializationName'];
            for($i = 1;$i<count($userDetails['PrefData'][0]['SpecializationPref']);$i++)
                $formattedUserDetails['Desired Specialization'] .= ", ".$userDetails['PrefData'][0]['SpecializationPref'][$i]['SpecializationName'];
            $formattedUserDetails['Mode'] = ($userDetails['PrefData'][0]['ModeOfEducationFullTime'] == "yes") ? "Full Time" : (($userDetails['PrefData'][0]['ModeOfEducationPartTime'] == "yes") ? "Part Time" : "");
            $prefDetails = $userDetails['PrefData'][0];
            $datediff = datediff($prefDetails['TimeOfStart'], $prefDetails['SubmitDate']);
	    $formattedUserDetails['Plan to start'] = ($prefDetails['YearOfStart'] != '0000') ? (($datediff != 0) ? "Within " . $datediff : "Immediately") : "Not Sure";
            if($prefDetails['ExtraFlag'] == 'studyabroad') {
    		    if($prefDetails['YearOfStart'] == date('Y')){
    			    $formattedUserDetails['Plan to start'] = 'Current Year';
                }
    		    else if($prefDetails['YearOfStart'] == date('Y')+1){
    			    $formattedUserDetails['Plan to start'] = 'Next Year';
                }
    		    else if($prefDetails['YearOfStart'] > date('Y')+1){
    			    $formattedUserDetails['Plan to start'] = 'Not Sure';
                }
                else {
                    $formattedUserDetails['Plan to start'] = 'Not Sure';
                }
    	    }
	    $formattedUserDetails['Plan to go'] = $formattedUserDetails['Plan to start'];
            $formattedUserDetails['Work Experience'] = ($userDetails['experience'] > 0) ? ($userDetails['experience'] . " Years") : (($userDetails['experience'] === "0") ? "Less Than 1 Year" : "No Experience");
            $formattedUserDetails['Current Location'] = $userDetails['CurrentCity'];
	    $formattedUserDetails['Current Locality'] = $userDetails['localityName'];
            $formattedUserDetails['Is in NDNC list'] = $userDetails['isNDNC'];
	    $userBudget = (int)$userDetails['PrefData'][0]['program_budget'];
	    global $budgetToTextArray;
	    if(key_exists($userBudget, $budgetToTextArray)) {
		    $userBudget = $budgetToTextArray[$userBudget];
	    }
	    $formattedUserDetails['Budget'] = $userBudget;
	    $formattedUserDetails['Valid Passport'] = $userDetails['passport'];
	    
            $sourceoffunding = array();
            if ($userDetails['PrefData'][0]['UserFundsOwn'] == "yes") {
                $sourceoffunding[] = "Own";
            }
            if ($userDetails['PrefData'][0]['UserFundsBank'] == "yes") {
                $sourceoffunding[] = "Bank";
            }
            if ($userDetails['PrefData'][0]['UserFundsNone'] == "yes") {
                $sourceoffunding[] = "Other:" . $userDetails['PrefData'][0]['otherFundingDetails'];
            }
            $formattedUserDetails['Source of Funding'] = implode("/", $sourceoffunding);
            $preferenceCallTimeArray = array('0' => 'Do not call', '1' => 'Anytime', '2' => 'Morning', '3' => 'Evening');
            $formattedUserDetails['Preferred Time to call'] = (is_numeric($prefDetails['suitableCallPref'])) ? ($preferenceCallTimeArray[$prefDetails['suitableCallPref']]) : "";

            foreach ($userDetails['EducationData'] as $educationData) {
                if ($educationData['Level'] == 'UG') {
                    $formattedUserDetails['Graduation Status'] = ($educationData['OngoingCompletedFlag'] == 1) ? "Pursuing" : "Completed";
                    $formattedUserDetails['Graduation Course'] = $educationData['Name'];
                    $formattedUserDetails['Graduation Marks'] = ($educationData['OngoingCompletedFlag'] == 1) ? "" : $educationData['Marks'];
                    list($formattedUserDetails['Graduation Month'], $formattedUserDetails['Graduation Year']) = split(" ", $educationData['Course_CompletionDate']);
                } else if ($educationData['Level'] == '12') {
                    $formattedUserDetails['Std XII Stream'] = $educationData['Name'];
                    $formattedUserDetails['Std XII Marks'] = $educationData['Marks'];
                    $XIICompletionDate = split(" ", $educationData['Course_CompletionDate']);
                    $formattedUserDetails['XII Year'] = $XIICompletionDate[1];
                } else if ($educationData['Level'] == 'Competitive exam') {
					if($prefDetails['ExtraFlag'] == 'studyabroad') {
						$examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($educationData['Name'],$educationData);
						$exam_taken[] = $examObj->displayExam();//$educationData['Name'] . (($educationData['Marks'] != 0) ? "(" . $educationData['Marks'] . ")" : "");
					}
					else {
						$examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($educationData['Name'],$educationData);
						if($formattedUserDetails['Exams Taken']) {
							$formattedUserDetails['Exams Taken'] .= ', ';
						}
						$formattedUserDetails['Exams Taken'] .= $examObj->displayExam();
					}
                }
            }
            $locationPrefData = $userDetails['PrefData'][0]['LocationPref'];
            $formattedUserDetails['Preferred Locations'] = '';
            $formattedUserDetails['Desired Countries'] = '';
            $cities_array = array();
            $states_array = array();
            $contries_array = array();
            for ($i = 0; $i < count($locationPrefData); $i++) {
                $key = 'Location Preference ' . ($i + 1);
                $formattedUserDetails[$key] = $locationPrefData[$i]['CityName'] . "-" . $locationPrefData[$i]['LocalityName'];
                if($locationPrefData[$i]['CityId'] != 0 && !array_key_exists($locationPrefData[$i]['CityId'],$cities_array))
                    $cities_array[$locationPrefData[$i]['CityId']] = $locationPrefData[$i]['CityName'];
                if($locationPrefData[$i]['CityId'] == 0 && $locationPrefData[$i]['StateId'] != 0 && !array_key_exists($locationPrefData[$i]['StateId'],$states_array))
                    $states_array[$locationPrefData[$i]['StateId']] = "Anywhere in ".$locationPrefData[$i]['StateName'];
                if(!array_key_exists($locationPrefData[$i]['CountryId'],$countries_array))
                    $countries_array[$locationPrefData[$i]['CountryId']] = $locationPrefData[$i]['CountryName'];
            }

            $formattedUserDetails['Preferred Locations'] = implode(", ",array_values($cities_array));
            $formattedUserDetails['Preferred Locations'] .= (count($cities_array)>0 && count($states_array)>0)?", ":"";
            $formattedUserDetails['Preferred Locations'] .= implode(", ",array_values($states_array));
            $formattedUserDetails['Desired Countries'] = implode(", ",array_values($countries_array));
            if($prefDetails['ExtraFlag'] == 'studyabroad') {
                $formattedUserDetails["Exams Taken"] = implode(", ",array_values($exam_taken));
            }
            $formattedUserDetails['User Comments'] = $userDetails['PrefData'][0]['UserDetail'];
            

            $formattedUserDetails['Creation Date'] = $userDetails['CreationDate'];
            
            if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') {
                $formattedUserDetails['Creation Date'] = date("jS M Y",strtotime($prefDetails['SubmitDate']));
            }


            $formattedUserDetails['Email'] = $userDetails['email'];
            $formattedUserDetails['Mobile'] = $userDetails['mobile'];
	        $formattedUserDetails['Response Date'] = ($responseUsers[$userDetails['userid']]['submitDate']) ? date('d M Y',strtotime($responseUsers[$userDetails['userid']]['submitDate'])) : "";

            $matchedForCourseId = ($responseUsers[$userDetails['userid']]['matchedFor']) ? ($responseUsers[$userDetails['userid']]['matchedFor']) : '';

            if(!empty($matchedForCourseId)){
                
                $matchedCourses[$matchedForCourseId]['CourseName'] = $displayArray['matchedCourses'][$matchedForCourseId];
                $matchedCourses[$matchedForCourseId]['InstituteName'] = $displayArray['matchedCoursesInstitute'][$matchedForCourseId];
                
                error_log("###matchedCourses ".print_r($matchedCourses,true));
                foreach($matchedCourses as $matchedCourseId=>$matchedCourse) {
                    $displayMatchedCourses[] = $matchedCourse['CourseName'].', '.$matchedCourse['InstituteName'];
                }
                $formattedUserDetails['Matched Response For'] = implode('; ',array_values($displayMatchedCourses));
            }

			if($displayArray['MRLocation']) {
				$selectedMRLocations = explode(",", $displayArray['MRLocation']);
				for($i=0;$i<count($selectedMRLocations);$i++) {
					$selectedMRLocations[$i] = trim($selectedMRLocations[$i]);
				}
				$userResponseLocations = $responseLocations[$userDetails['userid']];
				$matchingLocations = array_intersect($selectedMRLocations, $userResponseLocations);
				$formattedUserDetails['Preferred Study Locations'] = implode(", ", $matchingLocations);
			}
			else {
				$formattedUserDetails['Preferred Study Locations'] = "NA";
			}
			
    	   //  $responseDetails = ($responseUsers[$userDetails['userid']]['matchedFor']) ? ($responseUsers[$userDetails['userid']]['matchedFor']) : '';
    	   //  if(!empty($responseDetails)){
        // 		foreach($responseDetails as $courseId) {
        // 		    $matchedCourses[$courseId]['CourseName'] = $displayArray['matchedCourses'][$courseId];
        // 		    $matchedCourses[$courseId]['InstituteName'] = $displayArray['matchedCoursesInstitute'][$courseId];
        // 		}
        // 		foreach($matchedCourses as $matchedCourseId=>$matchedCourse) {
        // 		    $displayMatchedCourses[] = $matchedCourse['CourseName'].', '.$matchedCourse['InstituteName'];
        // 		}
    		  // $formattedUserDetails['Matched Response For'] = implode('; ',array_values($displayMatchedCourses));

	       //  }
            
            $LocalCourseArray[] = $formattedUserDetails;
        }
        
        return $LocalCourseArray;
    }

    function createUserDataArrayForResponses($UserDetailsArray,$extraFlag) {

        $LocalCourseArray = array();
        foreach ($UserDetailsArray as $userDetails) {
            $formattedUserDetails = array();
	    $countries_array = array();
            $exam_taken = array();
			$formattedUserDetails['Name'] = $userDetails['firstname']." ".$userDetails['lastname'];
            $formattedUserDetails['First Name'] = $userDetails['firstname'];
            $formattedUserDetails['Last Name'] = $userDetails['lastname'];
            $formattedUserDetails['Gender'] = ($userDetails['gender']) ? ($userDetails['gender']) : 0;
            $formattedUserDetails['Age'] = ($userDetails['age']) ? ($userDetails['age']) : 0;
            $formattedUserDetails['ISD Code'] = $userDetails['isdCode'];
            $formattedUserDetails['Desired Course'] = ($userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName']) ? ($userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName']) : '';
            $formattedUserDetails['Desired Course Level'] = ($userDetails['PrefData'][0]['SpecializationPref'][0]['CourseLevel1']) ? ($userDetails['PrefData'][0]['SpecializationPref'][0]['CourseLevel1']) : '';
	    if ($extraFlag == 'studyabroad') {
		    $courseLevel = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseLevel1'];
		    if($courseLevel == 'UG') {
			    $formattedUserDetails['Desired Course Level'] = 'Bachelors';
		    }
		    if($courseLevel == 'PG') {
			    $formattedUserDetails['Desired Course Level'] = 'Masters';
		    }
	    }
            $formattedUserDetails['Field of Interest'] = ($userDetails['PrefData'][0]['SpecializationPref'][0]['CategoryName']) ? ($userDetails['PrefData'][0]['SpecializationPref'][0]['CategoryName']) : '';
            if ($extraFlag == 'studyabroad') {
		global $studyAbroadPopularCourses;
		if($formattedUserDetails['Field of Interest'] == "All") {
		    $desiredCourseId = $userDetails["PrefData"][0]['DesiredCourse'];
		    if(array_key_exists($desiredCourseId,$studyAbroadPopularCourses)) {
		        $formattedUserDetails['Field of Interest'] = $studyAbroadPopularCourses[$desiredCourseId];
		    }
		}
	    }
	    $formattedUserDetails['Desired Specialization'] = ($userDetails['PrefData'][0]['SpecializationPref'][0]['SpecializationName']) ? ($userDetails['PrefData'][0]['SpecializationPref'][0]['SpecializationName']) : '';
	    for($i = 1;$i<count($userDetails['PrefData'][0]['SpecializationPref']);$i++)
		    $formattedUserDetails['Desired Specialization'] .= ", ".($userDetails['PrefData'][0]['SpecializationPref'][$i]['SpecializationName']) ? ($userDetails['PrefData'][0]['SpecializationPref'][$i]['SpecializationName']) : '';
		    
	    $formattedUserDetails['Mode'] = ($userDetails['PrefData'][0]['ModeOfEducationFullTime'] == "yes") ? "Full Time" : (($userDetails['PrefData'][0]['ModeOfEducationPartTime'] == "yes") ? "Part Time" : "");
            $prefDetails = $userDetails['PrefData'][0];
            $datediff = datediff($prefDetails['TimeOfStart'], $prefDetails['SubmitDate']);
	    $yearOfStart = ($prefDetails['YearOfStart']) ? ($prefDetails['YearOfStart']) : '';
	    if($extraFlag == 'studyabroad' && $yearOfStart) {
		if($yearOfStart == date('Y'))
		    $formattedUserDetails['Plan to start'] = 'Current Year';
		else if($yearOfStart == date('Y')+1)
		    $formattedUserDetails['Plan to start'] = 'Next Year';
		else if($yearOfStart > date('Y')+1)
		    $formattedUserDetails['Plan to start'] = 'Not Sure';
	    }
	    else {
		if(!$yearOfStart)
		    $formattedUserDetails['Plan to start'] = '';
		else
		    $formattedUserDetails['Plan to start'] = ($yearOfStart != '0000') ? (($datediff != 0) ? "Within " . $datediff : "Immediately") : "Not Sure";
	    }
            
	    $formattedUserDetails['Plan to go'] = $formattedUserDetails['Plan to start'];
            $formattedUserDetails['Work Experience'] = ($userDetails['experience'] > 0) ? ($userDetails['experience'] . " Years") : (($userDetails['experience'] === "0") ? "Less Than 1 Year" : "No Experience");
            $formattedUserDetails['Current Location'] = ($userDetails['CurrentCity']) ? ($userDetails['CurrentCity']) : '';
	    $formattedUserDetails['Current Locality'] = ($userDetails['localityName']) ? ($userDetails['localityName']) : '';
            $formattedUserDetails['Is in NDNC list'] = ($userDetails['isNDNC']) ? ($userDetails['isNDNC']) : '';
	    $userBudget = ($userDetails['PrefData'][0]['program_budget']) ? (int)$userDetails['PrefData'][0]['program_budget'] : '';
	    global $budgetToTextArray;
	    if(key_exists($userBudget, $budgetToTextArray)) {
		    $userBudget = $budgetToTextArray[$userBudget];
	    }
	    $formattedUserDetails['Budget'] = $userBudget;
	    $formattedUserDetails['Valid Passport'] = ($userDetails['passport']) ? ($userDetails['passport']) : '';
	    
            $sourceoffunding = array();
            if ($userDetails['PrefData'][0]['UserFundsOwn'] == "yes") {
                $sourceoffunding[] = "Own";
            }
            if ($userDetails['PrefData'][0]['UserFundsBank'] == "yes") {
                $sourceoffunding[] = "Bank";
            }
            if ($userDetails['PrefData'][0]['UserFundsNone'] == "yes") {
                $sourceoffunding[] = "Other:" . $userDetails['PrefData'][0]['otherFundingDetails'];
            }
            $formattedUserDetails['Source of Funding'] = ($sourceoffunding) ? implode("/", $sourceoffunding) : '';
            $preferenceCallTimeArray = array('0' => 'Do not call', '1' => 'Anytime', '2' => 'Morning', '3' => 'Evening');
            $formattedUserDetails['Preferred Time to call'] = (is_numeric($prefDetails['suitableCallPref'])) ? ($preferenceCallTimeArray[$prefDetails['suitableCallPref']]) : "";

            foreach ($userDetails['EducationData'] as $educationData) {
                if ($educationData['Level'] == 'UG') {
                    $formattedUserDetails['Graduation Status'] = ($educationData['OngoingCompletedFlag'] == 1) ? "Pursuing" : "Completed";
                    $formattedUserDetails['Graduation Course'] = $educationData['Name'];
                    $formattedUserDetails['Graduation Marks'] = ($educationData['OngoingCompletedFlag'] == 1) ? "" : $educationData['Marks'];
                    list($formattedUserDetails['Graduation Month'], $formattedUserDetails['Graduation Year']) = split(" ", $educationData['Course_CompletionDate']);
                } else if ($educationData['Level'] == '12') {
                    $formattedUserDetails['Std XII Stream'] = $educationData['Name'];
                    $formattedUserDetails['Std XII Marks'] = $educationData['Marks'];
                    $XIICompletionDate = split(" ", $educationData['Course_CompletionDate']);
                    $formattedUserDetails['Std XII Year'] = $XIICompletionDate[1];
                } else if ($educationData['Level'] == 'Competitive exam') {
		    if($extraFlag == 'studyabroad') {
			    $examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($educationData['Name'],$educationData);
			    $exam_taken[] = $examObj->displayExam();
		    }
		    else {
			    $examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($educationData['Name'],$educationData);
			    if($formattedUserDetails['Exams Taken']) {
				    $formattedUserDetails['Exams Taken'] .= ', ';
			    }
			    $formattedUserDetails['Exams Taken'] .= $examObj->displayExam();
		    }
                }
            }
            $locationPrefData = $userDetails['PrefData'][0]['LocationPref'];
            $formattedUserDetails['Preferred Locations'] = '';
            $formattedUserDetails['Desired Countries'] = '';
            $cities_array = array();
            $states_array = array();
            $contries_array = array();
            for ($i = 0; $i < count($locationPrefData); $i++) {
                $key = 'Location Preference ' . ($i + 1);
                $formattedUserDetails[$key] = $locationPrefData[$i]['CityName'] . "-" . $locationPrefData[$i]['LocalityName'];
                if($locationPrefData[$i]['CityId'] != 0 && !array_key_exists($locationPrefData[$i]['CityId'],$cities_array))
                    $cities_array[$locationPrefData[$i]['CityId']] = $locationPrefData[$i]['CityName'];
                if($locationPrefData[$i]['CityId'] == 0 && $locationPrefData[$i]['StateId'] != 0 && !array_key_exists($locationPrefData[$i]['StateId'],$states_array))
                    $states_array[$locationPrefData[$i]['StateId']] = "Anywhere in ".$locationPrefData[$i]['StateName'];
                if(!array_key_exists($locationPrefData[$i]['CountryId'],$countries_array))
                    $countries_array[$locationPrefData[$i]['CountryId']] = $locationPrefData[$i]['CountryName'];
            }

            $formattedUserDetails['Preferred Locations'] = implode(", ",array_values($cities_array));
            $formattedUserDetails['Preferred Locations'] .= (count($cities_array)>0 && count($states_array)>0)?", ":"";
            $formattedUserDetails['Preferred Locations'] .= implode(", ",array_values($states_array));
            $formattedUserDetails['Desired Countries'] = implode(", ",array_values($countries_array));
            if($extraFlag == 'studyabroad') {
                $formattedUserDetails["Exams Taken"] = implode(", ",array_values($exam_taken));
            }
            $formattedUserDetails['User Comments'] = $userDetails['PrefData'][0]['UserDetail'];
            $formattedUserDetails['Created On'] = $userDetails['CreationDate'];
            $formattedUserDetails['Email'] = $userDetails['email'];
            $formattedUserDetails['Mobile'] = $userDetails['mobile'];
            $LocalCourseArray[] = $formattedUserDetails;
        }
        return $LocalCourseArray;
    }
    

    function getCreditCount($userIdCSV, $viewType) {
        $this->init();
        $response = $this->getCreditDetails($this->userStatus[0]['userid'], explode(",", $userIdCSV), $viewType);
        echo json_encode($response);
    }

    function callAjax($type, $data) {
        $this->init();
        $appId = 1;
        $ldbObj = new LDB_client();
        if ($type == '1') {
            $response = $ldbObj->sgetSpecializationListByParentId($appId, $data);
        } elseif ($type == '2') {
            $this->load->library('category_list_client');
            $categoryClient = new Category_list_client();
            $appId = 1;
            $response = $categoryClient->getLocalitiesForCityId($appId, $data);
        }
        echo json_encode($response);
    }

    function _when_you_plan_start($selected = NULL, $flag = FALSE) {
        $array = array(
            date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1)) => 'This Year’s Academic Season',
            date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 2)) => 'Next Year’s Academic Season',
            //date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)) => 'Within next two years',
            '0000-00-00 00:00:00' => 'Not Sure',
        );
        $string = '';
        foreach ($array as $key => $value) {
            if ($selected != NULL) {
                if ($selected == $key) {
                    $selected_string = "selected";
                } else {
                    $selected_string = "";
                }
            }
            $string .='<option ' . $selected_string . ' value="' . $key . '">' . $value . '</option>';
        }
        if ($flag) {
            return $array;
        } else {
            return $string;
        }
    }

    function _select_city_list() {
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
        $this->load->library('MY_sort_associative_array');
        $sorter = new MY_sort_associative_array;
        $finalArray = array();
        foreach ($cityListTier1 as $list) {
            if ($list['stateId'] == '-1') {
                $finalArray['virtualCity'][] = json_decode($categoryClient->getCitiesForVirtualCity(1, $list['cityId']), true);
		$finalArray['metroCity'][] = $list;
            } else {
                $finalArray['metroCity'][] = $list;
            }
        }
        $string = '';
        $finalArray['virtualCity'] = $sorter->sort_associative_array($finalArray['virtualCity'], 'city_name');
        foreach ($finalArray['virtualCity'] as $list) {
            foreach ($list as $key) {
                if ($key['virtualCityId'] == $key['city_id']) {
                    $string .='<OPTGROUP LABEL="' . $key['city_name'] . '">';
                    foreach ($finalArray['virtualCity'] as $list1) {
                        $list1 = $sorter->sort_associative_array($list1, 'city_name');
                        foreach ($list1 as $key1) {
                            if ($key1['virtualCityId'] != $key1['city_id']) {
                                if ($key['city_id'] == $key1['virtualCityId']) {
                                    $string .='<OPTION ddid="' . $key1['city_id'] . '"  title="' . $key1['city_name'] . '" value="' . base64_encode(json_encode(array('cityId' => $key1['city_id'], 'stateId' => $key1['state_id'], 'countryId' => 2))) . '">' . $key1['city_name'] . '</OPTION>';
                                }
                            }
                        }
                    }
                }
            }
        }
        $string .='<OPTGROUP LABEL="Metro Cities">';
        $finalArray['metroCity'] = $sorter->sort_associative_array($finalArray['metroCity'], 'cityName');
        foreach ($finalArray['metroCity'] as $key1) {
            $string .='<OPTION ddid="' . $key1['cityId'] . '"  title="' . $key1['cityName'] . '" value="' . base64_encode(json_encode(array('cityId' => $key1['cityId'], 'stateId' => $key1['stateId'], 'countryId' => 2))) . '">' . $key1['cityName'] . '</OPTION>';
        }
        $ldbObj = new LDB_Client();
        $listing_client = new listing_client();
        $country_state_city_list = json_decode($ldbObj->sgetCityStateList(12), true);
        foreach ($country_state_city_list as $list) {
            if ($list['CountryId'] == 2) {
                foreach ($list['stateMap'] as $list2) {
                    $string .='<OPTGROUP LABEL="' . $list2['StateName'] . '">';
                    foreach ($list2['cityMap'] as $list3) {
                        $string .='<OPTION ddid="' . $list3['CityId'] . '" title="' . $list3['CityName'] . '" value="' . base64_encode(json_encode(array('cityId' => $list3['CityId'], 'stateId' => $list2['StateId'], 'countryId' => 2))) . '">' . $list3['CityName'] . '</OPTION>';
                    }
                }
            }
        }
        return $string;
    }

    function ajax_preference_locality($id, $type) {
        $response = array();
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $appId = 1;
        if (empty($id)) {
            echo "[]";
            exit;
        }
        if ($type == '1') {
            $response = json_decode($categoryClient->getZonesForCityId($appId, $id), true);
        } else {
            $response = json_decode($categoryClient->getLocalitiesForZoneId($appId, $id), true);
        }
        echo json_encode($response);
    }
	
    function ajax_add_preference_locality_block($blockNum,$isMMM)
    {
	$this->load->helper(array('url', 'form'));
	$this->load->library(array('ajax', 'enterprise_client', 'LDB_Client', 'ajax', 'category_list_client','listing_client'));
	$data = array();
	$data['select_city_list'] = $this->_select_city_list();
	$data['blockNum'] = $blockNum;
	$data['isMMM'] = $isMMM;
	$this->load->view('enterprise/currentLocalities',$data);
    }

    private function showFormForStudyabroad(& $data) {
        $this->load->library('Category_list_client');
        $category_list_client = new Category_list_client();
        $categoryList = $category_list_client->getCategoryList(1, 1,'studyabroad');
        $catgeories = array();
        foreach ($categoryList as $category) {
            $categories[$category['categoryID']] = $category['categoryName'];
        }
        asort($categories);
        $data['categories'] = $categories;

        $regions = json_decode($category_list_client->getCountriesWithRegions(1), true);
        $data['regions'] = $regions;
    }

    function restrictTabs(&$cmsUserInfo, $selectedTab, $restrictedTabs = array(12,7,6,36))
    {
	if($cmsUserInfo['usergroup'] == "enterprise") { 
	    $smartModel = $this->load->model('smart/smartmodel');
	    $clientType = $smartModel->getClientType($cmsUserInfo['userid']);
	    $clientType = "Abroad";
	    if($clientType == "Abroad") {       
	    foreach($cmsUserInfo['headerTabs'][0]['tabs'] as $tabArray) {           
		if(!in_array($tabArray['tabId'], $restrictedTabs)) {
		$finalTabs[] = $tabArray;
		}
	    }
	    $cmsUserInfo['headerTabs'][0]['tabs'] = $finalTabs;
	    $cmsUserInfo['headerTabs'][0]['selectedTab'] = $selectedTab;
	    $cmsUserInfo['clientType'] = $clientType;
	    }
	}
    }
    
    function index() {
        
        $this->init();
	
	    $configFile = APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
	    require $configFile;
	   
	    // $data['validateuser'] = $this->userStatus;
	    // $clientId = $data['validateuser'][0]['userid'];
        
        $courseName = isset($_REQUEST['course_name']) ? $_REQUEST['course_name'] : 'National Courses';
        $actual_course_name = isset($_REQUEST['course_name']) ? $_REQUEST['course_name'] : 'National Courses';
        $search_category_id = isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId'] : '';
        if($courseName == 'Study Abroad') {
            $search_category_id = '';
        }

        $boolen_flag_to_apply_hack_on_mba_courses = 'true';
	    /**
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Aircraft Maintenance Engineering')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Advanced Technical Courses')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'B.E./B.Tech')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'B.Sc')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Distance B.Sc')) {
    	    $courseName = 'IT Courses';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Distance B.Tech')) {
    	    $courseName = 'IT Courses';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Distance M.Sc')) {
    	    $courseName = 'IT Courses';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Engineering Diploma')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Engineering Distance Diploma')) {
    	    $courseName = 'IT Courses';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'M.E./M.Tech')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'M.Sc')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Marine Engineering')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Science & Engineering Degrees')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Science & Engineering PHD')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Distance/Correspondence MBA')) {
    	    $courseName = 'IT Courses';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Certifications')) {
    	    $courseName = 'IT Courses';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Online MBA')) {
    	    $courseName = 'IT Courses';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Part-time MBA')) {
    	    $courseName = 'IT Courses';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Executive MBA')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'BBA/BBM')) {
    	    $courseName = 'IT Degrees';
    	}    
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Integrated MBA Courses') && ($_REQUEST['categoryId'] != '2')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Integrated MBA Courses') && ($_REQUEST['categoryId'] != '2')) {
    	  	$courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Other Management Degrees')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Distance BCA/MCA')) {
                $courseName = 'IT Courses';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Distance B.A./M.A.')) {
    	    $courseName = 'IT Courses';
    	}
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Test Prep')) {
            $courseName = 'IT Courses';
        }
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Animation Degrees')) {
    	    $courseName = 'IT Degrees';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Animation Courses')) {
    	    $courseName = 'IT Courses';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Hospitality, Aviation & Tourism Courses')) {
    	    $courseName = 'IT Courses';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Hospitality, Aviation & Tourism Degrees')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Media, Films & Mass Communication Courses')) {
    	    $courseName = 'IT Courses';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Media, Films & Mass Communication Degrees')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Arts, Law, Languages and Teaching Courses')) {
    	    $courseName = 'IT Courses';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Arts, Law, Languages and Teaching Degrees')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Banking & Finance Courses')) {
    	    $courseName = 'IT Courses';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Banking & Finance Degrees')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Design Courses')) {
    	    $courseName = 'IT Courses';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Design Degrees')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Medicine, Beauty & Health Care Courses')) {
    	    $courseName = 'IT Courses';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Medicine, Beauty & Health Care Degrees')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Retail Degrees')) {
    	    $courseName = 'IT Degrees';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Test Prep (International Exams)')) {
    	    $courseName = 'IT Courses';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Foreign Language Courses')) {
    	    $courseName = 'IT Courses';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Medicine, Beauty & Health Care Degrees')&&($_REQUEST['categoryId'] == '2')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Marine Engineering')&&($_REQUEST['categoryId'] == '2')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}
    	if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Integrated MBA Courses')&&($_REQUEST['categoryId'] == '2')) {
    	    $courseName = 'IT Degrees';
    	    $boolen_flag_to_apply_hack_on_mba_courses = 'false';
    	}

        */
	       
        $clientHaveAuthorizationForLDBSearchTabs = $this->_checkClientHaveAccessToLdbSearchTabs();			
	    if($clientHaveAuthorizationForLDBSearchTabs){

            if($courseName == 'Study Abroad') {
                $searchFormViewFile = 'enterprise/searchFormByCourse';
            }
            // if(array_key_exists($_REQUEST['course_name'],$coursesList) || array_key_exists($courseName,$coursesList)){
            //     $searchFormViewFile = 'searchFormMatchedResponses';
            // }
            else {
                $data = modules::run('userSearchCriteria/searchCriteria/getDataForSearchForm');
                // $htmlContent = modules::run('userSearchCriteria/searchCriteria/addNewGenieInLDBSearch');
                $searchFormViewFile = 'userSearchCriteria/searchGenie';
            }
	        
	    }
	    else{ 
    	    // $htmlContent = $this->load->view('enterprise/unauthorizedLDBTabs');
            $searchFormViewFile = 'enterprise/unauthorizedLDBTabs';
	    }

        $data['validateuser'] = $this->userStatus;
        $clientId = $data['validateuser'][0]['userid'];
        $data['viewFile'] = $searchFormViewFile;

    	$smartModel = $this->load->model('smart/smartmodel');
    	$clientType = $smartModel->getClientType($cmsUserInfo['userid']);
    	if($clientType == "Abroad") {
    	    $cmsUserData = $this->cmsUserValidation();
    	    $this->restrictTabs($cmsUserData , 14, array(12,7,6,36));
    	    $data['headerTabs'] = $cmsUserData['headerTabs'];
    	}
    	else{
    	    $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
            // $data['htmlContent'] = $htmlContent;
    	}
    	
        $entObj = new Enterprise_client();
        $ldbObj = new LDB_client();

        $data['searchTabs'] = array(
            array('ctab_name' =>'Study Abroad','course_name' => 'Study Abroad', 'tab_type' => 'studyAbroad'),
            array('ctab_name' =>'Domestic Leads','course_name' => 'National Courses', 'tab_type' => 'national'),
            array('ctab_name' =>'Domestic MR','course_name' => 'MBA (Full Time)', 'tab_type' => 'national'),
            array('ctab_name' =>'Domestic MR','course_name' => 'B.E./B.Tech (Full Time)', 'tab_type' => 'national')
        );

    	// $data['searchTabs'] = array(
    	//     array('ctab_name' =>'Study Abroad','course_name' => 'Study Abroad', 'tab_type' => 'studyAbroad'),
    	//     array('ctab_name' =>'Management','course_name' => 'BBA/BBM', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Management','course_name' => 'Full Time MBA/PGDM', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Management','course_name' => 'Distance/Correspondence MBA', 'tab_type' => 'local'),
    	//     array('ctab_name' =>'Management','course_name' => 'Executive MBA', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Management','course_name' => 'Full-time MBA', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Management','course_name' => 'Integrated MBA Courses', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Management','course_name' => 'Management Certifications', 'tab_type' => 'local'),
    	//     array('ctab_name' =>'Management','course_name' => 'Online MBA', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Management','course_name' => 'Other Management Degrees', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Management','course_name' => 'Part-time MBA', 'tab_type' => 'local'),	  
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Aircraft Maintenance Engineering', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Advanced Technical Courses', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'B.E./B.Tech', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'B.Sc', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Distance B.Sc', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Distance B.Tech', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Distance M.Sc', 'tab_type' =>'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Engineering Diploma', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Engineering Distance Diploma','tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Integrated MBA Courses','tab_type' =>'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'M.E./M.Tech', 'tab_type' =>'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'M.Sc', 'tab_type' =>'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Marine Engineering', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Medicine, Beauty & Health Care Degrees','tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Science & Engineering Degrees', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Science & Engineering PHD', 'tab_type' => 'national'),
    	//     array('ctab_name' =>'Science & Engineering','course_name' => 'Integrated MBA Courses','tab_type' => 'national'),
    	//     array('ctab_name' =>'IT','course_name' => 'Distance BCA/MCA','tab_type' =>'local'),
    	//     array('ctab_name' =>'IT','course_name' => 'IT Courses','tab_type' =>'local'),
    	//     array('ctab_name' =>'IT','course_name' => 'IT Degrees','tab_type' =>'national'),
    	//     array('ctab_name' =>'Animation','course_name' => 'Animation Courses','tab_type' =>'local'),
    	//     array('ctab_name' =>'Animation','course_name' => 'Animation Degrees','tab_type' =>'national'),
    	//     array('ctab_name' =>'Hospitality','course_name' => 'Hospitality, Aviation & Tourism Courses','tab_type'=>'local'),
    	//     array('ctab_name' =>'Hospitality','course_name' => 'Hospitality, Aviation & Tourism Degrees','tab_type'=>'national'),
    	//     array('ctab_name' =>'Media','course_name' => 'Media, Films & Mass Communication Courses','tab_type'=>'local'),
    	//     array('ctab_name' =>'Media','course_name' => 'Media, Films & Mass Communication Degrees','tab_type'=>'national'),
    	//     array('ctab_name' =>'Test Preparation','course_name' => 'Test Prep','tab_type' =>'local'),
    	//     array('ctab_name' =>'Test Preparation','course_name' => 'Test Prep (International Exams)','tab_type'=>'local'),
    	//     array('ctab_name' =>'Others','course_name' => 'Arts, Law, Languages and Teaching Courses','tab_type'=>'local'),
    	//     array('ctab_name' =>'Others','course_name' => 'Arts, Law, Languages and Teaching Degrees','tab_type'=>'national'),
    	//     array('ctab_name' =>'Others','course_name' => 'Banking & Finance Courses','tab_type' =>'local'),
    	//     array('ctab_name' =>'Others','course_name' => 'Banking & Finance Degrees','tab_type'=>'national'),
    	//     array('ctab_name' =>'Others','course_name' => 'Design Courses', 'tab_type' =>'local'),
    	//     array('ctab_name' =>'Others','course_name' => 'Design Degrees', 'tab_type' =>'national'),
    	//     array('ctab_name' =>'Others','course_name' => 'Distance B.A./M.A.', 'tab_type'=>'local'),
    	//     array('ctab_name' =>'Others','course_name' => 'Foreign Language Courses','tab_type'=>'national'),
    	//     array('ctab_name' =>'Others','course_name' => 'Medicine, Beauty & Health Care Courses','tab_type'=>'local'),
    	//     array('ctab_name' =>'Others','course_name' => 'Medicine, Beauty & Health Care Degrees','tab_type'=>'national'),
    	//     array('ctab_name' =>'Others','course_name' => 'Retail Degrees','tab_type'=>'national'),
    	// );

        $data['prodId'] = 31;
        $data['when_you_plan_start'] = $this->_when_you_plan_start();
        $data['course_name'] = $courseName;
        $data['actual_course_name'] = $actual_course_name;
        $data['search_category_id'] = $search_category_id;

        //Code for MR

        /**
	    if(array_key_exists($_REQUEST['course_name'],$coursesList) || array_key_exists($courseName,$coursesList)){
    	    $course = ($_REQUEST['course_name']) ? ($_REQUEST['course_name']) : $courseName;
    	    $matchedResponsesData = $this->getDataForMatchedResponses($clientId, $course, $coursesList);
    	    if($clientHaveAuthorizationForLDBSearchTabs){
    		    $data['viewFile'] = $matchedResponsesData['viewFile'];
    	    }
    	    $data['message'] = $matchedResponsesData['message'];
    	    $data['actual_course_id'] = $matchedResponsesData['actual_course_id'];
    	    $data['course_level'] = $matchedResponsesData['course_level'];
    	    $data['instituteList'] = array();
    	    $data['instituteList'] = $matchedResponsesData['instituteList'];
        }
        */

        //Code Not Required Start
        
	    
        $this->load->model('LDB/ldbmodel');
        $data['courseDetails'] = $this->ldbmodel->getLDBCourseByName($actual_course_name,$search_category_id);
        
        $examsFieldValueSource = new \registration\libraries\FieldValueSources\Exams;
        $data['examValues'] = $examsFieldValueSource->getAllValue(array('desiredCourse' => $data['courseDetails']['SpecializationId']));


        //Code Not Required End

        
        if ($courseName == 'Study Abroad') {
             $Validity = $this->checkUserValidation();
             $clientId = $Validity[0]['userid'];

            $this->load->library('category_list_client');
            $categoryClient = new Category_list_client();
            $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
            $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
            $data['cityList'] = array_merge($cityListTier1, $cityListTier2);
            $data['cityList_tier1'] = $cityListTier1;
            $data['cityList_tier2'] = $cityListTier2;
            $data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12), true);

            $this->load->library('SearchAgents_client');
            $categoryClient = new SearchAgents_client();
            $appId = 1;
            $search_agents_array = $categoryClient->getAllSearchAgents($appId, $clientId);
            $data['search_agents_array'] = $search_agents_array;
            $this->showFormForStudyabroad($data);
            $cmsuserinfo = $this->cmsUserValidation();
            $data['myProducts'] = $cmsuserinfo['myProducts'];
            $data['usergroup'] = 'enterprise';
    	    $sa_course = $_REQUEST['sa_course'];
    	    $sa_course_type = $_REQUEST['sa_course_type'];

	        if($sa_course_type !== 'all'){
        		$this->load->model('LDB/ldbmodel');
        		$data['studyAbroadSpecializations'] = $this->ldbmodel->getStudyAbroadSpecializationsByName($sa_course,$sa_course_type);
	        }
    	    $examsFieldValueSource = new \registration\libraries\FieldValueSources\ExamsAbroad;
    	    $data['examValues'] = $examsFieldValueSource->getValues();
	    
    	    //$budgetFieldValueSource = new \registration\libraries\FieldValueSources\Budget;
    	    //$data['budgetValues'] = $budgetFieldValueSource->getValues();
    	    
    	    $data['sa_course'] = $sa_course;
    	    $data['sa_course_type'] = $sa_course_type;
	        
            $this->load->view('enterprise/studentSearchForm', $data);

        }
	    else {

            /**
            $data['select_city_list'] = $this->_select_city_list();
	        $courseNameToGetSpec = isset($_REQUEST['course_name']) ? $_REQUEST['course_name'] : 'Full Time MBA/PGDM';
	        $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, $courseNameToGetSpec, "india"), true);
    	    if ( $_REQUEST['course_name'] == 'Engineering Diploma') {
    		    $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, 'Diploma',"india"), true);
    	    }
    	    else if ($_REQUEST['course_name'] == 'Engineering Distance Diploma') {
    		    $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, 'Distance Diploma',"india"), true);
    	    }
    	    else if ($_REQUEST['course_name'] ==  'Science & Engineering PHD') {
    		    $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, 'Phd',"india"), true);
    	    }
    	    else if ( $_REQUEST['course_name']  == 'Management Certifications') {
    		    $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, 'Certifications',"india"), true);
    	    }

      	    $categoryClient = new Category_list_client();
            $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
            $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
            $data['cityList'] = array_merge($cityListTier1, $cityListTier2);
            $data['cityList_tier1'] = $cityListTier1;
            $data['cityList_tier2'] = $cityListTier2;
            $categoryClient = new Category_list_client();

            if (isset($_REQUEST['course_name']) && (($_REQUEST['course_name'] == 'BBA/BBM') || ($_REQUEST['course_name'] == 'Integrated MBA Courses') || ($_REQUEST['course_name'] == 'Other Management Degrees'))  && ($_REQUEST['categoryId'] == '3')) {
		        $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1,3),true);
	        }
            else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Animation Degrees')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 12), true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Animation Courses')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 12), true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Hospitality, Aviation & Tourism Courses')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 6), true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Hospitality, Aviation & Tourism Degrees')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 6), true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Science & Engineering'|| $_REQUEST['course_name'] == 'Aircraft Maintenance Engineering' || $_REQUEST['course_name'] == 'Advanced Technical Courses' || $_REQUEST['course_name'] == 'B.E./B.Tech' || $_REQUEST['course_name'] == 'B.Sc'  ||$_REQUEST['course_name'] == 'Distance B.Sc' ||$_REQUEST['course_name'] == 'Distance B.Tech' ||$_REQUEST['course_name'] == 'Distance M.Sc'  ||$_REQUEST['course_name'] == 'M.E./M.Tech'||$_REQUEST['course_name'] == 'M.Sc'|| $_REQUEST['course_name'] == 'Marine Engineering'|| $_REQUEST['course_name'] ==  'Science & Engineering Degrees' || $_REQUEST['course_name'] ==  'Medicine, Beauty & Health Care Degrees'|| $_REQUEST['course_name'] ==  'Integrated MBA Courses') && ($_REQUEST['categoryId'] == '2')) {    
		        $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 2), true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Medicine, Beauty & Health Care Courses' || $_REQUEST['course_name'] == 'Medicine, Beauty & Health Care Degrees')&& ($_REQUEST['categoryId'] != '2')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 5), true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Media, Films & Mass Communication Courses' || $_REQUEST['course_name'] == 'Media, Films & Mass Communication Degrees')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 7),true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Arts, Law, Languages and Teaching Courses' || $_REQUEST['course_name'] == 'Arts, Law, Languages and Teaching Degrees' || $_REQUEST['course_name'] == 'Foreign Language Courses')) {
		        $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 9),true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Design Courses' || $_REQUEST['course_name'] == 'Design Degrees')) {
		        $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 13),true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Banking & Finance Courses' || $_REQUEST['course_name'] == 'Banking & Finance Degrees')) {
		        $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 4),true);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Retail Degrees')) {
		        $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 11),true);
	        }
	        else if ((isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Test Prep')) || (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Test Prep (International Exams)'))) {
                $data['itcourseslist'] = $categoryClient->getTestPrepCoursesList(1);
	        }
	        else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Distance B.A./M.A.')) {
		        $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 9),true);
            }
	        else {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 10), true);
            }

            $Validity = $this->checkUserValidation();
            $clientId = $Validity[0]['userid'];
            
            */
            $this->load->library('SearchAgents_client');
            $categoryClient = new SearchAgents_client();
            $appId = 1;
            $search_agents_array = $categoryClient->getAllSearchAgents($appId, $clientId);
            $data['search_agents_array'] = $search_agents_array;
	        
            // Start Online form change by pranjul 13/10/2011
            $this->load->library('OnlineFormEnterprise_client');
            $ofObj = new OnlineFormEnterprise_client();
            $data['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($clientId);
            // End Online form change by pranjul 13/10/2011
            
            $cmsuserinfo = $this->cmsUserValidation();
            $data['myProducts'] = $cmsuserinfo['myProducts'];
            $data['usergroup'] = 'enterprise';
	        
            // $data['boolen_flag_to_apply_hack_on_mba_courses'] = $boolen_flag_to_apply_hack_on_mba_courses;
	    
    	    // $this->load->builder("registration/RegistrationBuilder");
            // $registrationBuilder = new registration\builders\RegistrationBuilder;
    	    // $data['registrationBuilder'] = $registrationBuilder;

    	    $this->load->view('enterprise/studentSearchForm', $data);

        }

    }
    
    function getDataForMatchedResponses($userid, $course, $coursesList, $isallCourses = 'N', $is_selected_courses = 'N'){
	    
	    $this->load->model('listing/listingmodel');
	    $this->load->model('listing/institutemodel');
	    $this->national_course_lib = $this->load->library('listing/NationalCourseLib');
	    
	    $returnData = array();
	    if($isallCourses == 'N' && $is_selected_courses == 'N') {
		    foreach($coursesList as $name => $details){
			    if($course == $name){
				    $returnData['actual_course_id'] = $details['actual_course_id'];
				    $returnData['course_level'] = $details['course_level'];
				    $subCategoryId = $details['subcategory_id'];
			    }
		    }
	    }
	    $listingIds = $this->listingmodel->getActiveLisitingsForagroupOfOwner($userid);
	    $instituteList = array();
	    foreach ($listingIds as $key => $listing) {
    		if($listing['listing_type'] == 'institute') {
    		    $instituteList[$listing['listing_type_id']] = $listing;
    		}
	    }
        
	    $instituteCourseMap = $this->institutemodel->getCoursesForInstitutes(array_keys($instituteList),'ALL');
	    foreach ($instituteCourseMap as $instituteId => $instituteCourseData) {
    		foreach($instituteCourseData['course_title_list'] as $courseId => $courseTitle){
    			if($isallCourses == 'Y') {
    			    $returnData['instituteList'][$instituteId]['instituteData'] = $instituteList[$instituteId];
    			    $returnData['instituteList'][$instituteId]['courseList'][] = array('id' => $courseId, 'name' => $courseTitle);		
    			} else {
    				$mappedLDBCourseIds = $this->listingmodel->getLDBCoursesForClientCourse($courseId);
    				if(!empty($mappedLDBCourseIds)) {
    					if($is_selected_courses == 'N') {
    						$count = $this->listingmodel->getLDBCoursesCountForSubCategory($mappedLDBCourseIds, $subCategoryId, $returnData['actual_course_id']);
    						if($count > 0 || in_array($returnData['actual_course_id'],$mappedLDBCourseIds)) {
    							$returnData['instituteList'][$instituteId]['instituteData'] = $instituteList[$instituteId];
    							$returnData['instituteList'][$instituteId]['courseList'][] = array('id' => $courseId, 'name' => $courseTitle);
    						}
    					} else {
    						foreach($coursesList as $name => $details) {
    							$count = $this->listingmodel->getLDBCoursesCountForSubCategory($mappedLDBCourseIds, $details['subcategory_id'], $details['actual_course_id']);
    							if($count > 0 || in_array($details['actual_course_id'],$mappedLDBCourseIds)) {
    								$returnData['instituteList'][$instituteId]['instituteData'] = $instituteList[$instituteId];
    								$returnData['instituteList'][$instituteId]['courseList'][] = array('id' => $courseId, 'name' => $courseTitle);
    							}
    						}
    					}
    				}
    				else {
    					continue;
    				}
    			}
    		}
	    }
	    if(!empty($returnData['instituteList'])){
		    $returnData['viewFile'] = 'searchFormMatchedResponses';
	    }
	    else{
    		$returnData['viewFile'] = 'unauthorizedLDBTabs';
    		$returnData['message'] = 'No course exists for '.$course;
	    }
	    return $returnData;
    }
    
    private function _checkClientHaveAccessToLdbSearchTabs() {
    	$data['validateuser'] = $this->userStatus;
    	$clientId = $data['validateuser'][0]['userid'];
    	/**
    	 * this api gets the LDB Search Access Tabs Set for the Client
    	 * 
    	 * @return array
    	 */
    	$this->load->model('enterprise/ldbsearchtabsaccessrightsmodel');
    	$ldbSearchTabsAccessRightsModel = new ldbsearchtabsaccessrightsmodel();
    	$ldbTabsAccessSet = $ldbSearchTabsAccessRightsModel->getClientLDBSearchAccessTabs($clientId);
    	/**
    	 * this logic checks if LDB Search Access Tabs already Set for the Client otherwise make all tabs accesible
    	 *  
    	 */
    	require FCPATH.'globalconfig/LDBSearchTabsCoursesList.php';
    	foreach($LDBCourseList as $category=>$categoryWiseData){
    	    $catgoryId = $categoryWiseData['id'];
    	    $courses = $categoryWiseData['courses'];
    	    if($categoryWiseData['name'] == 'Study Abroad') {
        		$courses = array();
        		$courses[] = 'All';
        		$courses = array_merge($courses, $categoryWiseData['courses']['popular'], $categoryWiseData['courses']['category']);
    	    }    
    	    foreach($courses as $key=>$course){
    		    $ldbSearchTabs[$catgoryId][$course]  = true;
    	    }
    	}
    	foreach($other_array as $course=>$catgoryId){	
    	    $ldbSearchTabs[$catgoryId][$course]  = true;
    	}
    	if(!empty($ldbTabsAccessSet)){
    	    $accesibleTabs = $ldbTabsAccessSet; 
    	}else{
    	    $accesibleTabs = $ldbSearchTabs;
    	}
    	/**
    	 * Code To show accesible based on above parameters set for this respective client
    	 */
    	$clientHaveAuthorizationForLDBSearchTabs = true;
    	if($_REQUEST['course_name'] != '') {
    	    $clientHaveAuthorizationForLDBSearchTabs = false;
    	    if(isset($accesibleTabs[$_REQUEST['categoryId']][$_REQUEST['course_name']] )) {
    		    $clientHaveAuthorizationForLDBSearchTabs = true;
    	    }
    	    else if(isset($accesibleTabs[99][$_REQUEST['sa_course']]) && $_REQUEST['course_name'] == 'Study Abroad') {
    		    $clientHaveAuthorizationForLDBSearchTabs = true;
    	    }		
    	}
    	else if($_REQUEST['course_name'] == '') {
    	    if(!isset($accesibleTabs[3]['Full Time MBA/PGDM'])) {
    		    $clientHaveAuthorizationForLDBSearchTabs = false;
    	    }	
    	}
    	return $clientHaveAuthorizationForLDBSearchTabs;
    }
    
    /* API used for intermediate search to get query string from hidden field */
	function searchResults() {
        //error_log("Ravzzi: PostArray ".print_r($_POST,true));
        $Validity = $this->checkUserValidation();
        $ClientId = $Validity[0]['userid'];
        if (isset($_POST['inputData'])) {
            $inputArray = json_decode(base64_decode($_POST['inputData']), true);
        } else {
            $inputArray = array();
        }
        if (isset($_POST['displayData'])) {
            $displayArray = json_decode(base64_decode($_POST['displayData']), true);
        } else {
            $displayArray = array();
        }
	$inputArray['underViewedLimitFlagSet'] = false;
	    
	if($_POST['ldb_underLimit_flag']){
	    $inputArray['underViewedLimitFlagSet'] = true;	    
	}
	
	$start = isset($_POST['startOffSetSearch']) ? $_POST['startOffSetSearch'] : 0;
        $rows = isset($_POST['countOffsetSearch']) ? $_POST['countOffsetSearch'] : 50;
        if (isset($_POST['filterOverride'])) {
            $inputArray['DateFilter'] = $_POST['filterOverride'];
            $displayArray['DateFilter'] = $_POST['filterOverride'];
        }
        if (isset($_POST['requestTime']) && $_POST['requestTime'] != "") {
            $inputArray['requestTime'] = $_POST['requestTime'];
        }
        if (isset($_POST['ldb_unviewed_flag']) && (!empty($_POST['ldb_unviewed_flag'])) && ($_POST['ldb_unviewed_flag'] == '1')) {
            $inputArray['DontShowViewed'] = '1';
            unset($inputArray['Viewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Emailed']);
            unset($inputArray['Smsed']);
        } else if (isset($_POST['ldb_viewed_flag']) && (!empty($_POST['ldb_viewed_flag'])) && ($_POST['ldb_viewed_flag'] == '1')) {
            $inputArray['Viewed'] = $ClientId;
            unset($inputArray['DontShowViewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Emailed']);
            unset($inputArray['Smsed']);
        } else if (isset($_POST['ldb_emailed_flag']) && (!empty($_POST['ldb_emailed_flag'])) && ($_POST['ldb_emailed_flag'] == '1')) {
            $inputArray['Emailed'] = $ClientId;
            unset($inputArray['DontShowViewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Viewed']);
            unset($inputArray['Smsed']);
        } else if (isset($_POST['ldb_smsed_flag']) && (!empty($_POST['ldb_smsed_flag'])) && ($_POST['ldb_smsed_flag'] == '1')) {
            $inputArray['Smsed'] = $ClientId;
            unset($inputArray['DontShowViewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Viewed']);
            unset($inputArray['Emailed']);
        } else {
            unset($inputArray['Smsed']);
            unset($inputArray['DontShowViewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Viewed']);
            unset($inputArray['Emailed']);
        }
        //error_log("Ravzzi: passtoQuery ".print_r($inputArray,true));
        
	if (!empty($_POST['inputDataMR'])) {
            $dataArrayMR = json_decode(base64_decode($_POST['inputDataMR']), true);
	    $this->displayResultsMR($inputArray, $displayArray, $start, $rows, $dataArrayMR);
        } else {
	    $search_key = getTempUserData('search_key');
            $this->displayResults($inputArray, $displayArray, $start, $rows, $search_key);
        }
    }

    // API that render search Result as AJAX response
    function displayResults($inputArray, $displayArray, $start, $rows,$search_key) {

        $this->init();
        $data['validateuser'] = $this->userStatus;
        $Validity = $this->checkUserValidation();
        $ClientId = $Validity[0]['userid'];
        $data['ClientId'] = $ClientId;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $data['searchTabs'] = array(array('course_name' => 'MBA (Full Time)'));
        $data['prodId'] = 31;
        $data['course_name'] = $inputArray['tab_course_name'];
        $data['actual_course_name'] = $inputArray['actual_course_name'];
        
        if($inputArray['DesiredCourse'] == 'Engineering Diploma') {
            $inputArray['DesiredCourse'] = 'Diploma';
        }
        if($inputArray['DesiredCourse'] == 'Engineering Distance Diploma') {
            $inputArray['DesiredCourse'] = 'Distance Diploma';
        }
        if($inputArray['DesiredCourse'] == 'Management Certifications') {
            $inputArray['DesiredCourse'] = 'Certifications';
        }
        if($inputArray['DesiredCourse'] == 'Science & Engineering PHD') {
            $inputArray['DesiredCourse'] = 'PHD';
        }
    	
    	if(isset($_SESSION['DateFilterFrom'])){
    	    $inputArray['DateFilterFrom'] = $_SESSION['DateFilterFrom'];
    	}
    	if(isset($_SESSION['DateFilterTo'])){
    	    $inputArray['DateFilterTo'] = $_SESSION['DateFilterTo'];
    	}
        /*
         * If test prep, don't pass category id
         * as we have not yet migrated testprep to categories
         */ 
        if($inputArray['search_category_id'] == 14) {
            unset($inputArray['search_category_id']);
        }
	
        /*hard coding for study abroad. Since it is used for study abroad only*/
        $inputArray['DesiredCourse'] = 'Study Abroad';

	    $ldbObj = new LDB_client();
	    $inputArray['groupId'] = $this->_getGroupForAcourse($inputArray['DesiredCourse']); 
		 
	    $groupData = $ldbObj->getCreditConsumedByGroup($inputArray['groupId']); 
	    foreach($groupData as $limitData) { 
    		if($limitData['actionType'] == 'view_limit') { 
    		    $inputArray['groupViewLimit'] = $limitData['deductcredit']; 
    		}     
	    }

    	$response = $this->searchLDBSolr($inputArray,$start,$rows,$this->userStatus[0]['userid']);

        $result_user_ids = array_keys($response['result']);

        $data['DontShowViewed'] = $inputArray['DontShowViewed']; 

        $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, $courseName), true);
        $data['resultResponse'] = $response;
        $data['displayArray'] = $displayArray;
        $data['inputData'] = base64_encode(json_encode($inputArray));
        $data['displayData'] = base64_encode(json_encode($displayArray));
        $data['flag_manage_page'] = $inputArray['flag_manage_page'];
        //error_log("Ravi: Data ".print_r($data,true));
        $data['underViewedLimitFlagSet'] = $inputArray['underViewedLimitFlagSet'];
        
        $this->load->model('ldbmodel');
        $responses = $this->ldbmodel->getResponsesForClient($ClientId, $result_user_ids);
        $data['responses'] = $responses;
        
        unset($result_user_ids);

        
        $todayDate = date('Y-m-d');
        $data['clientAccess'] = true;
        $clientAccessData = $this->ldbmodel->getManualLDBAccessData($ClientId, $todayDate);
        if(empty($clientAccessData)) {
            $data['clientAccess'] = false;
        }

        $data['groupId'] = $inputArray['groupId'];        
        $this->load->view("enterprise/searchResult", $data);
    }

    function searchLDBSolr($inputArray,$resultOffset,$numResults,$clientUserId,$isMMM=FALSE)
    {   
        ini_set('memory_limit','512M');
	    $start = microtime(true);
	    
	    $this->load->library('LDB/searcher/LeadSearcher');
	    $leadSearcher = new LeadSearcher;
	   
        $inputArray['resultOffset'] = $resultOffset;
        //$inputArray['numResults'] = $inputArray['resultOffset'] + $numResults;
        $inputArray['numResults'] = $numResults;

	    $inputArray['clientUserId'] = $clientUserId;
        $leads = $leadSearcher->search($inputArray,$isMMM);
	  

        if($inputArray['countFlag'] == true){
            $totalResults = $leads;
        } else{
	       $totalResults = count(array_unique($leads));
           //$totalResults = $leadsData['numFound'];

        }
	    
	    if($totalResults == 0 && !$inputArray['countFlag']) {
		    return array('error' => 'No Results Found For Your Query');
	    }
	    
        $leads = array_unique($leads);
        if(!$isMMM){
            $leads = array_slice($leads,$resultOffset,$numResults);
        }
	   
	    $end = microtime(true);

	    $makeResultStart = microtime(TRUE);	

        $userIds = array_keys($leads);


        if($isMMM) {
            $return = array(
                'requestTime' => date("Y-m-d h:m:s",$start),
                'totalRows' => $totalResults,
                'userIds' => $userIds
            );
        } else {
            
            //$userDetails = $this->getUserDetails($userIds,$clientUserId,$leads);

            $return = array(
                'requestTime' => ($end-$start),
                'numrows' => $totalResults,
                'result' => $leadSearcher->getUserDetails($leads,$clientUserId)
            );
        }

	    $makeResultEnd = microtime(TRUE);		
	    error_log("SolrSearch:: MakeResultTime:: ".($makeResultEnd-$makeResultStart));
        
	    return $return;
    }
    
	public function getUserDetails($userIds,$clientId,$leadData)
    {   
        /**
         * Get latest view counts for each users
         */
        if(!empty($userIds)) {
            $this->load->model('LDB/leadsearchmodel');
            $this->leadSearchModel = new LeadSearchModel;

            $LDBViewCountArray = array();
            $LDBViewCountArray = $this->leadSearchModel->getLeadViewCountArray($userIds);
            $LeadContactedData = $this->leadSearchModel->getLeadContactedData($userIds,$clientId);
            
            foreach ($leadData as $userId => $data) {
                
                $userData = json_decode($data['displayData'],TRUE);
                $userData['ViewCountArray'] = $LDBViewCountArray[$userId];
                $userData['ContactData'] = $LeadContactedData[$userId]['ContactType'];

                foreach ($data as $dataKey => $dataValue) {
                    $userData[$dataKey] = $dataValue;
                }

                unset($userData['displayData']);
                
                /*$userData['streamId'] = $data['streamId'];
                $userData['subStreamId'] = $data['subStreamId'];
                $userData['specialization'] = $data['specialization'];
                $userData['mode'] = $data['mode'];
                $userData['shikshaCourse'] = $data['shikshaCourse'];
                $userData['shikshaCourse'] = $data['shikshaCourse'];*/

                $userDetails[$userId] = $userData;
            }            

            return $userDetails;
        }
    }

	//deleted searchKLDB function
	
	function getSolrDate($date)
	{
		$date = date('Y-m-d H:i:s',strtotime($date));
		$dateParts = explode(' ',$date);
		return $dateParts[0].'T'.$dateParts[1].'Z';
	}

	

    function remove_array_empty_values($array, $remove_null_number = false) {
        $new_array = array();

        $null_exceptions = array();

        foreach ($array as $key => $value) {
            $value = trim($value);

            if ($remove_null_number) {
                $null_exceptions[] = '0';
            }

            if (!in_array($value, $null_exceptions) && $value != "") {
                $new_array[] = $value;
            }
        }
        return $new_array;
    }

    private function getSourcesOfFundingForStudyAbroad(&$displayArray, &$postArray) {
        $sources = array('UserFundsOwn' => 'Own', 'UserFundsBank' => 'Bank Loan', 'UserFundsNone' => 'Others');
        $fundingSources = array();
        foreach ($sources as $source => $sourceLabel) {
            if (isset($_POST[$source]) && !empty($_POST[$source])) {
                $fundingSources[] = $sourceLabel;
                $postArray[$source] = $_POST[$source];
            }
        }
        if (!empty($fundingSources)) {
            $displayArray['sourcesOfFunding'] = trim(implode(', ', $fundingSources), ', ');
        }
    }

    private function getDesiredCoursesForStudyAbroad(&$inputArray, & $postArray) {
		$categoryId = array();
		
		$sa_course = $_POST['sa_course'];
		$sa_course_type = $_POST['sa_course_type'];
		
		$this->load->model('LDB/ldbmodel');
		
		if($sa_course_type == 'popular') {
			$desiredCourseId =  $this->ldbmodel->getPopularAbroadLDBCourseIdByName($sa_course);
			$inputArray['DesiredCourseName'][] = $sa_course;
			$postArray['DesiredCourseId'][] = $desiredCourseId;
			$inputArray['DesiredCourseId'][] = $desiredCourseId;
		}
                else if($sa_course_type == 'all'){
                    $desiredCourseIds = $this->ldbmodel->getAbroadCategoryIds();
                    //$desiredCourseLevels = array('UG', 'PG','PhD');
		    $desiredCourseLevels = $_POST['desiredCourseLevel'];
                    $ldbClientObj = new LDB_Client();
		    if (empty($desiredCourseLevels)) {
			    $desiredCourseLevels = array('UG', 'PG','PhD');
		    } else {
			    $inputArray['DesiredCourseLevels'] = $desiredCourseLevels;
		    }
                global $studyAbroadLevelWiseDesiredCourses;
                foreach ($desiredCourseLevels as $desiredCourseLevel) {
                    foreach ($desiredCourseIds as $category) {
                            $desiredCourseDetails = array_pop(json_decode($ldbClientObj->getCourseForCriteria($appId, $category, 'abroad', $desiredCourseLevel), true));
                            $inputArray['DesiredCourseName'][] = $sa_course;
                            $inputArray['DesiredCourseId'][] = $desiredCourseDetails['SpecializationId'];
                            $postArray['DesiredCourseId'][] = $desiredCourseDetails['SpecializationId'];
                    }

        			if($desiredCourseLevel == 'UG'){
        			    $popularCoursesArray = $studyAbroadLevelWiseDesiredCourses['UG']; //array('1510');
        			    foreach($popularCoursesArray as $popularCourse){
        				$inputArray['DesiredCourseId'][] = $popularCourse;
        				$postArray['DesiredCourseId'][] = $popularCourse;
        			    }
        			}
        			if($desiredCourseLevel == 'PG'){
        			    $popularCoursesArray = $studyAbroadLevelWiseDesiredCourses['PG']; //array('1508', '1509');
        			    foreach($popularCoursesArray as $popularCourse){
        				$inputArray['DesiredCourseId'][] = $popularCourse;
        				$postArray['DesiredCourseId'][] = $popularCourse;
        			    }
        			}
                }
            } else {
			$boardId = $this->ldbmodel->getAbroadCategoryIdByName($sa_course);
			$categoryId[$boardId] = $boardId;
			$desiredCourseLevels = $_POST['desiredCourseLevel'];
			$this->load->library('LDB_Client');
			$ldbClientObj = new LDB_Client();
			$this->load->library('category_list_client');
			$category_list_client = new Category_list_client();
			$categoryList = $category_list_client->getCategoryList(1, 1,'studyabroad');
			if (empty($desiredCourseLevels)) {
				$desiredCourseLevels = array('UG', 'PG','PhD');
			} else {
				$inputArray['DesiredCourseLevels'] = $desiredCourseLevels;
			}
			foreach ($desiredCourseLevels as $desiredCourseLevel) {
				if (empty($categoryId)) {
					$categoryId = array();
					foreach ($categoryList as $category) {
						$categoryId[$category['categoryID']] = $category['categoryName'];
					}
				}
				if (in_array('-1', $categoryId)) {
					$keyForAll = array_search('-1', $categoryId);
					unset($categoryId[$keyForAll]);
				}
				if (is_array($categoryId)) {
					foreach ($categoryId as $category => $categoryName) {
						$desiredCourseDetails = array_pop(json_decode($ldbClientObj->getCourseForCriteria($appId, $category, 'abroad', $desiredCourseLevel), true));
						error_log("LDBQUERY: ".print_r($desiredCourseDetails,true));
						$inputArray['DesiredCourseName'][] = $sa_course;
						$inputArray['DesiredCourseId'][] = $desiredCourseDetails['SpecializationId'];
						$postArray['DesiredCourseId'][] = $desiredCourseDetails['SpecializationId'];
					}
				} else {
					$desiredCourseDetails = array_pop(json_decode($ldbClientObj->getCourseForCriteria($appId, $categoryId, 'abroad', $desiredCourseLevel), true));
					foreach ($categoryList as $categoryDetails) {
						if ($categoryDetails['categoryID'] == $categoryId) {
							$inputArray['DesiredCourseName'][] = $categoryDetails['categoryName'];
							break;
						}
					}
					$inputArray['DesiredCourseId'][] = $desiredCourseDetails['SpecializationId'];
					$postArray['DesiredCourseId'][] = $desiredCourseDetails['SpecializationId'];
				}
			}
		}
    }

    private function getExamDataForStudyAbroad(& $displayArray, & $examArray) {
        $exams = array('TOEFL', 'IELTS', 'SAT', 'GMAT', 'GRE');
        foreach ($exams as $examName) {
            if (isset($_POST['exam_' . $examName])) {
                $examTaken = $examName;
                $minScore = $examArray[$examName]['min'] = (is_numeric($_POST[$examName . '_min_score'])) ? $_POST[$examName . '_min_score'] : "";
                $maxScore = $examArray[$examName]['max'] = (is_numeric($_POST[$examName . '_max_score'])) ? $_POST[$examName . '_max_score'] : "";
                $examTaken .= $minScore == '' ? '' : ' : ' . $minScore;
                if ($maxScore != '') {
                    $examTaken .= $minScore != '' ? ' - ' . $maxScore : ' : < ' . $maxScore;
                }
                $displayArray['examTaken'] .= ($displayArray['examTaken'] != "") ? "," . $examTaken : $examTaken;
            }
        }
    }

    // ajax function for rendering result page
    function formSubmit() {
		
        global $examGrades;
	    $LDBSearchStart = microtime(TRUE);
        $this->load->library('LDB_Client');
        $ldbObj = new LDB_client();
		$this->load->model('LDB/ldbmodel');
        $postArray = array();
        $displayArray = array();
        /* course name */
        $course_name = $_POST['search_form_course_name'];
        $actual_course_name = $_POST['actual_course_name'];
        $search_category_id = $_POST['search_category_id'];
        $postArray['actual_course_name'] = $actual_course_name;
        $postArray['tab_course_name'] = $course_name;
        $postArray['DesiredCourse'] = isset($_POST['desiredCourse']) ? $_POST['desiredCourse'] : '';
        $postArray['search_category_id'] = $search_category_id;
        $displayArray['DesiredCourse'] = isset($_POST['desiredCourse']) ? $_POST['desiredCourse'] : '';
        $displayArray['isActiveUser'] = $this->input->post('includeActiveUsers');
                
	    if ($course_name == 'Study Abroad') {
            $postArray['ExtraFlag'] = 'studyabroad';
            $displayArray['ExtraFlag'] = 'studyabroad';
            $this->getDesiredCoursesForStudyAbroad($displayArray, $postArray);
            $this->getSourcesOfFundingForStudyAbroad($displayArray, $postArray);
			
			$abroadSpecializations = $_POST['sa_specialization_id'];
			if(count($abroadSpecializations) > 0) {
                            if (in_array("-1", $abroadSpecializations)) {
                                $postArray['abroadSpecializations'] = '';
                                $displayArray['abroadSpecializations'][] = "All";
                            }else{
                                $postArray['abroadSpecializations'] = $abroadSpecializations;
                            }
                            
                            $abroad_specialization_list = $this->ldbmodel->getAbroadSpecializationNames($abroadSpecializations);
                            foreach ($abroad_specialization_list as $list){
                                $displayArray['abroadSpecializations'][] = $list;
                            }
			}
        }
        
        $postArray['Specialization'] = isset($_POST['course_specialization']) ? $_POST['course_specialization'] : '';
        $displayArray['Specialization'] = "";
        if (in_array("-1", $postArray['Specialization'])) {
            $postArray['Specialization'] = '';
            $displayArray['Specialization'] = "All";
        }
        if (isset($_POST['course_specialization']) && $_POST['course_specialization'] != "") {
	        $course_specialization_list = json_decode($ldbObj->sgetSpecializationList(12, $course_name), true);
            foreach ($_POST['course_specialization'] as $value) {
                foreach ($course_specialization_list as $list) {
                    if ($value == $list['SpecializationId']) {
                        if ($displayArray['Specialization'] == "") {
                            $displayArray['Specialization'] = $list['SpecializationName'];
                        } else {
                            $displayArray['Specialization'] .= ", " . $list['SpecializationName'];
                        }
                    }
                }
            }
        }
	if(isset($_POST['passport'])) {
		$postArray['passport'] = $_POST['passport'];
	    	$displayArray['passport'] = $_POST['passport'];
	}
	if ($course_name == 'Study Abroad') {
	    if (isset($_POST['prefLocArr'])) {
		$postArray['PreferredLocation'] = $this->remove_array_empty_values($_POST['prefLocArr']);
		 if ($course_name == 'Distance/Correspondence MBA' || $course_name == 'Online MBA' || $course_name == 'Part-time MBA' || $course_name == 'Certifications') {				
				$prefLocalityNumBlocks = intval($_POST['prefLocalityNumBlocks']);
				$postArray['Locality'] = array();
				for($i=1;$i<=$prefLocalityNumBlocks;$i++) {
					$postArray['Locality'] = array_merge($postArray['Locality'],$this->remove_array_empty_values($_POST['LocalityArr_'.$i]));
				}
				$displayArray['prefLocation'] = $this->_getDisplayPrefLocation();
            } else {
                $displayArray['prefLocation'] = $this->_getDisplayPrefLocation();
            }
        }
	}
	//if(is_array($_POST['budget']) && count($_POST['budget']) > 0) {
		//$postArray['budget'] = $_POST['budget'];
		//
		//$budgetFieldValueSource = new \registration\libraries\FieldValueSources\Budget;
		//$budgetValues = $budgetFieldValueSource->getValues();
		//
		//foreach($_POST['budget'] as $selectedBudget) {
		//	$displayArray['budget'][] = $budgetValues[$selectedBudget];
		//}
	//}

	if(is_array($_POST['planToStart']) && count($_POST['planToStart']) > 0) {
		$postArray['planToStart'] = $_POST['planToStart'];
	    $displayArray['planToStart'] = $_POST['planToStart'];
	}
	
	$displayArray['Mode'] = "";
        if (isset($_POST['full_time_mode'])) {
            $postArray['ModeFullTime'] = 'yes';
            if ($displayArray['Mode'] == "") {
                $displayArray['Mode'] = "Full-time";
            } else {
                $displayArray['Mode'] .= ", Full-time";
            }
        }

        if (isset($_POST['part_time_mode'])) {
            $postArray['ModePartTime'] = 'yes';
            if ($displayArray['Mode'] == "") {
                $displayArray['Mode'] = "Part-time";
            } else {
                $displayArray['Mode'] .= ", Part-time";
            }
        }

        if (isset($_POST['distance_mode'])) {
            $postArray['ModeDistance'] = 'yes';
            if ($displayArray['Mode'] == "") {
                $displayArray['Mode'] = "Distance";
            } else {
                $displayArray['Mode'] .= ", Distance";
            }
        }

        if (isset($_POST['CurLocArr']) && !empty($_POST['CurLocArr'])) {
	
            $this->load->builder('LocationBuilder','location');
		$locationBuilder = new \LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		
		$cityObjs = $locationRepository->findMultipleCities($_POST['CurLocArr']);
		foreach($cityObjs as $cityId => $cityObj) {
		    if($cityObj->isVirtualCity()) {
			$citiesByVitualCity = $locationRepository->getCitiesByVirtualCity($cityId);
			foreach($citiesByVitualCity as $city) {
			    $_POST['CurLocArr'][] = $city->getId();
			}
		    }
		}
		
		$postArray['CurrentLocation'] = array_unique($_POST['CurLocArr']);
		if (isset($_POST['hiddenCurrentCity'])) {
		    $displayArray['currentLocation'] = implode(', ', $_POST['hiddenCurrentCity']);
		}
        }
        if (isset($_POST['prefLocClause'])) {
            if ($_POST['prefLocClause'] == 'or') {
                $postArray['LocationAndOr'] = 1;
            }
        }
	   
        if (isset($_POST['currLocArr'])) {
	    	$postArray['CurrentLocality'] = $this->remove_array_empty_values($_POST['currLocArr']);
            if ($course_name == 'Distance/Correspondence MBA' || $course_name == 'Online MBA' || $course_name == 'Part-time MBA' || $course_name == 'Certifications') {				
				$prefLocalityNumBlocks = intval($_POST['prefLocalityNumBlocks']);
				$postArray['Locality'] = array();
				for($i=1;$i<=$prefLocalityNumBlocks;$i++) {
					$postArray['Locality'] = array_merge($postArray['Locality'],$this->remove_array_empty_values($_POST['LocalityArr_'.$i]));
				}
				$displayArray['currLocation'] = $this->_getDisplayCurrLocation();
            } else {
                $displayArray['currLocation'] = $this->_getDisplayCurrLocation();
            }
        }
		
        /* IT Courses and IT Degree FIXES START */
	if ($course_name == 'IT Courses' || $course_name == 'Science & Engineering Degrees'||
	  $course_name == 'Aircraft Maintenance Engineering' || $course_name == 'Marine Engineering' || $course_name ==
	  'Medicine, Beauty & Health Care Degrees' || $course_name == 'Integrated MBA Courses' ) {            
			// TODO: flag_select_all  this flag can be used as "SELECT ALL" localities issue
			$prefLocalityNumBlocks = intval($_POST['prefLocalityNumBlocks']);
			$postArray['Locality'] = array();
			for($i=1;$i<=$prefLocalityNumBlocks;$i++) {
				$postArray['Locality'] = array_merge($postArray['Locality'],$this->remove_array_empty_values($_POST['LocalityArr_'.$i]));
			}
			unset($postArray['Specialization']);
            if($course_name == 'IT Courses'){
               // unset($postArray['CurrentLocation']);
            }
            if (in_array("-1", $this->remove_array_empty_values($_POST['course_specialization']))) {
                unset($_POST['course_specialization'][0]);
                /* :( we need to assign all to display array as now we treat specialization as desired course */
                $displayArray['Specialization'] = "All";
            }
            $postArray['DesiredCourse'] = $this->remove_array_empty_values($_POST['course_specialization']);
            if ($_POST['prefLocClause'] == 'or') {
                $postArray['LocationAndOr'] = 1;
            }
            // $course_specialization = array_slice($_POST['course_specialization'], 0, 50);
            $course_specialization = $_POST['course_specialization'];

	    $displayArray['currLocation'] = $this->_getDisplayCurrLocation();
	    $displayArray['DesiredCourse'] = implode(",&nbsp;<wbr/>", $course_specialization);
        }
        if ($course_name == 'IT Degree') {
            if (in_array("-1", $this->remove_array_empty_values($_POST['desiredCourse']))) {
                unset($_POST['desiredCourse'][0]);
                $displayArray['desiredCourse'] = "All";
            }
            if (!empty($_POST['desiredCourse'])) {
                $postArray['DesiredCourse'] = $this->remove_array_empty_values($_POST['desiredCourse']);
            }
            if ($_POST['prefLocClause'] == 'or') {
                $postArray['LocationAndOr'] = 1;
            }
            $DesiredCourse = array_slice($_POST['desiredCourse'], 0, 50);
            $displayArray['DesiredCourse'] = implode(",", $DesiredCourse);
        }
        /* IT Courses and IT Degree FIXES END */
        /* TEST PREP START */
        $flag_test_prep = $_POST['flag_test_prep'];
        if ($flag_test_prep == 'testprep') {
            if (in_array("-1", $this->remove_array_empty_values($_POST['testPrep_blogid']))) {
                unset($_POST['testPrep_blogid'][0]);
                // hack to display results
                $displayArray['Specialization'] = "All";
            }
            $postArray['ExtraFlag'] = 'testprep';
            $displayArray['ExtraFlag'] = 'testprep';
            $postArray['testPrep_blogid'] = $this->remove_array_empty_values($_POST['testPrep_blogid']);
            $course_specialization = array_slice($postArray['testPrep_blogid'], 0, 50);
            $this->load->library('category_list_client');
            $categoryClient = new Category_list_client();
            $displayArray['DesiredCourse'] = $categoryClient->getblogNameCsvFromBlogIdCsv(1, implode(',', $postArray['testPrep_blogid']));
        }
        /* TEST PREP END */
        $displayArray['DegreePref'] = "";
        if (isset($_POST['any_deg_pref'])) {
            $postArray['DegreePrefAny'] = 'yes';
            if ($displayArray['DegreePref'] == '') {
                $displayArray['DegreePref'] = 'Any';
            } else {
                $displayArray['DegreePref'] .= ', Any';
            }
        }
        if (isset($_POST['aicte_deg_pref'])) {
            $postArray['DegreePrefAICTE'] = 'yes';
            if ($displayArray['DegreePref'] == '') {
                $displayArray['DegreePref'] = 'AICTE Approved';
            } else {
                $displayArray['DegreePref'] .= ', AICTE Approved';
            }
        }
        if (isset($_POST['ugc_deg_pref'])) {
            $postArray['DegreePrefUGC'] = 'yes';
            if ($displayArray['DegreePref'] == '') {
                $displayArray['DegreePref'] = 'UGC Approved';
            } else {
                $displayArray['DegreePref'] .= ', UGC Approved';
            }
        }
        if (isset($_POST['international_deg_pref'])) {
            $postArray['DegreePrefInternational'] = 'yes';
            if ($displayArray['DegreePref'] == '') {
                $displayArray['DegreePref'] = 'International';
            } else {
                $displayArray['DegreePref'] .= ', International';
            }
        }

        if (isset($_POST['ug_course'])) {
            $postArray['UGCourse'] = $_POST['ug_course'];
            $displayArray['UGCourse'] = implode(", ", $_POST['ug_course']);
        }

        if (isset($_POST['UGlistingIdCSV'])) {
            if (trim($_POST['UGlistingIdCSV']) != "") {
                $postArray['UGInstitute'] = explode(',', $_POST['UGlistingIdCSV']);
            }
        }
        if (isset($_POST['hiddenSchoolList'])) {
            $displayArray['instituteList'] = implode(', ', $_POST['hiddenSchoolList']);
        }

	/*
        $displayArray['graduationDate'] = "";
        if ($_POST['gradStartYear'] != "") {
			$gradStartMonth = '01';
			if($_POST['gradStartMonth']) {
				$gradStartMonth = intval($_POST['gradStartMonth']) > 9 ? $_POST['gradStartMonth'] : '0'.$_POST['gradStartMonth'];
			}
            $postArray['GraduationCompletedFrom'] = $_POST['gradStartYear'] . "-" . $gradStartMonth . "-01 00:00:00";
            $displayArray['graduationDate'] = "After " . $gradStartMonth . "-" . $_POST['gradStartYear'];
        }

        if ($_POST['gradEndYear'] != "") {
			$gradEndMonth = '12';
			if($_POST['gradEndMonth']) {
				$gradEndMonth = intval($_POST['gradEndMonth']) > 9 ? $_POST['gradEndMonth'] : '0'.$_POST['gradEndMonth'];
			}
			
            $result = strtotime("{$_POST['gradEndYear']}-{$gradEndMonth}-01");
            $result = strtotime('-1 second', strtotime('+1 month', $result));
            $finaldate = $result . " 23:59:59";
            $finaldate = date("Y-m-d H:i:s", $finaldate);

            $postArray['GraduationCompletedTo'] = $finaldate;
            if ($displayArray['graduationDate'] == "") {
                $displayArray['graduationDate'] = "Before " . date("m-Y", $result);
            } else {
                $displayArray['graduationDate'] .= " and before " . date("m-Y", $result);
            }
        }
	    */
	
	$displayArray['graduationDate'] = '';
	    $displayArray['XIIGraduationDate'] = '';
	    if ($_POST['gradStartYear'] != '' || $_POST['XIIStartYear'] != '') {
		$startYear = $_POST['gradStartYear'] != '' ? $_POST['gradStartYear'] : $_POST['XIIStartYear'];
		$gradStartMonth = '01';
		
		if($_POST['gradStartYear'] != '') {
		    $postArray['GraduationCompletedFrom'] = $startYear . "-" . $gradStartMonth . "-01 00:00:00";
		    $displayArray['graduationDate'] = "After " . $gradStartMonth . "-" . $startYear;
		}
		else if($_POST['XIIStartYear'] != '') {
		    $postArray['XIICompletedFrom'] = $startYear . "-" . $gradStartMonth . "-01 00:00:00";
		    $displayArray['XIIGraduationDate'] = "After " . $gradStartMonth . "-" . $startYear;
		}
	    }
	    
	    if ($_POST['gradEndYear'] != "" || $_POST['XIIEndYear'] != '') {
		$endYear = $_POST['gradEndYear'] != '' ? $_POST['gradEndYear'] : $_POST['XIIEndYear'];
		$gradEndMonth = '12';
		
		$result = strtotime("{$endYear}-{$gradEndMonth}-01");
		$result = strtotime('-1 second', strtotime('+1 month', $result));
		$finaldate = $result . " 23:59:59";
		$finaldate = date("Y-m-d H:i:s", $finaldate);
		
		if($_POST['gradEndYear'] != '') {
		    $postArray['GraduationCompletedTo'] = $finaldate;
		    
		    if ($displayArray['graduationDate'] == "") {
			$displayArray['graduationDate'] = "Before " . date("m-Y", $result);
		    }
		    else {
			$displayArray['graduationDate'] .= " and before " . date("m-Y", $result);
		    }
		}
		else if($_POST['XIIEndYear'] != '') {
		    $postArray['XIICompletedTo'] = $finaldate;
		    
		    if ($displayArray['XIIGraduationDate'] == "") {
			$displayArray['XIIGraduationDate'] = "Before " . date("m-Y", $result);
		    }
		    else {
			$displayArray['XIIGraduationDate'] .= " and before " . date("m-Y", $result);
		    }
		}
	    }
	
        if (isset($_POST['ug_marks'])) {
            if ($_POST['ug_marks'] != "") {
                $postArray['MinGradMarks'] = $_POST['ug_marks'];
                $displayArray['graduationMarks'] = "Greater than " . $_POST['ug_marks'];
                if (isset($_POST['gradResultAwaited'])) {
                    $postArray['IncludeResultsAwaited'] = array('1');
                    $displayArray['graduationMarks'] .= " and include students with results awaited";
                }
            }
        }
        if (isset($_POST['marks_twelve'])) {
            $postArray['MinXIIMarks'] = $_POST['marks_twelve'];
            $displayArray['Min12Marks'] = $_POST['marks_twelve'];
        }
        if (isset($_POST['12_stream'])) {
            $postArray['XIIStream'] = $_POST['12_stream'];
            $displayArray['12Stream'] = implode(", ", $_POST['12_stream']);
        }

        if (isset($_POST['user_gender'])) {
            $postArray['Gender'] = $_POST['user_gender'];
            $displayArray['Gender'] = implode(", ", $_POST['user_gender']);
        }
        $displayArray['age'] = "";
        if (isset($_POST['age_min'])) {
            if (is_numeric($_POST['age_min'])) {
                $postArray['MinAge'] = $_POST['age_min'];
                $displayArray['age'] = ">" . $_POST['age_min'];
            }
        }
        if (isset($_POST['age_max'])) {
            if (is_numeric($_POST['age_max'])) {
                $postArray['MaxAge'] = $_POST['age_max'];
                if ($displayArray['age'] == "") {
                    $displayArray['age'] = "<" . $_POST['age_max'];
                } else {
                    $displayArray['age'] .= " and <" . $_POST['age_max'];
                }
            }
        }
	
        $displayArray['workex'] = "";
        if (isset($_POST['min_workex'])) {
            if (is_numeric($_POST['min_workex'])) {
                $postArray['MinExp'] = $_POST['min_workex'];
                $displayArray['workex'] = "greater than " . $_POST['min_workex'] . " Year";
            }
        }
        if (isset($_POST['max_workex'])) {
            if (is_numeric($_POST['max_workex'])) {
                $postArray['MaxExp'] = $_POST['max_workex'];
                if ($displayArray['workex'] == "") {
                    $displayArray['workex'] = "less than " . $_POST['max_workex'] . " Year";
                } else {
                    $displayArray['workex'] .= " and less than " . $_POST['max_workex'] . " Year";
                }
            }
        }
        
        $examArray = array();
        $displayArray['examTaken'] = "";
        $examDisplayArray = array();
        
        $exams = $_POST['exams'];
        if(is_array($exams) && count($exams) > 0) {
            foreach($exams as $examId) {
                
                $examArray[$examId] = array();
                
                if($_POST[$examId.'_min_score'] && trim($_POST[$examId.'_min_score']) != 'Min') {
                    $examArray[$examId]['min'] = $_POST[$examId.'_min_score'];
                }
                if($_POST[$examId.'_max_score'] && trim($_POST[$examId.'_max_score']) != 'Max') {
                    $examArray[$examId]['max'] = $_POST[$examId.'_max_score'];
                }
                if($_POST[$examId.'_year']) {
                    $examArray[$examId]['year'] = $_POST[$examId.'_year'];
                }        
                $examDisplay = $examId == 'NOEXAM' ? 'No Exam Required' : $_POST[$examId.'_displayname'];
                if($examArray[$examId]['min'] || $examArray[$examId]['max'] || $examArray[$examId]['year']) {
                    $examDisplay .= ': ';
                }
                if($examArray[$examId]['min'] || $examArray[$examId]['max']) {
		    if($examGrades[$examId]) {
			    $examDisplay .= $examGrades[$examId][$examArray[$examId]['min']]." - ".$examGrades[$examId][$examArray[$examId]['max']];
		    }
		    else {
			    $examDisplay .= $examArray[$examId]['min']." - ".$examArray[$examId]['max'];
		    }
                }
                if($examArray[$examId]['year']) {
                    if($examId == 'MAT') {
                        $examDisplay .= ' in '.date('M Y',strtotime($examArray[$examId]['year']));
                    }
                    else {
                        $examDisplay .= ' in '.date('Y',strtotime($examArray[$examId]['year']));    
                    }
                }
                
                $examDisplayArray[] = $examDisplay;
            }
            
            $displayArray['examTaken'] = implode(", ",$examDisplayArray);
        }        
        $this->getExamDataForStudyAbroad($displayArray, $examArray);
        $postArray['ExamScore'] = $examArray;
        /*
          if(isset($_POST['noShowContact']))
          {
          $postArray['DontShowContacted'] = 1;
          }
         */
        $postArray['DontShowViewed'] = '1';
        
        /* LDB 2.0 END */

		if ( isset($_POST['timefilter']['from']) && isset($_POST['timefilter']['to']) ){
			$postArray['DateFilterFrom'] = $_POST['timefilter']['from'];
			$displayArray['DateFilterFrom'] = $_POST['timefilter']['from'];
			$postArray['DateFilterTo'] = $_POST['timefilter']['to'].' 23:59:59';
			$displayArray['DateFilterTo'] = $_POST['timefilter']['to'].' 23:59:59';
		}
		
		$postArray['includeActiveUsers'] = $_POST['includeActiveUsers'];
		$_SESSION['DateFilterFrom'] = $postArray['DateFilterFrom'];
		$_SESSION['DateFilterTo'] = $postArray['DateFilterTo'];
	
	
	$this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);
	
	$prefLocalityNumBlocks=$this->input->post('prefLocalityNumBlocks');
	
	$loc=array();
	for($i=1;$i<=$prefLocalityNumBlocks;$i++){
	$loc1[$i-1]=$this->input->post('LocalityArr_'.$i);
	
	}
	for($i=1;$i<=$prefLocalityNumBlocks;$i++){
	$curr1[$i-1]=$this->input->post('currLocArr_'.$i);
	}
	$postArray['currentLocalities']=$loc1;
	
	$current_City=array();
	$i=0;
	foreach($curr1 as $k=>$v){
	    foreach($v as $curCityCode=>$currCities){
	$current_City[$i]=json_decode(base64_decode($currCities));
	$current_City[$i]=$current_City[$i]->cityId;
	$i++;
	    }
	}
	
	$CurrCity=$this->getCityNameFronId($current_City);
	$displayArray['CurrentCity']=$CurrCity;
	$localities="";
	
	$virtualCityArray=array();
	$checkVirtualCity=array();
		foreach($current_City as $key=>$val){
		    if($val){
			$checkVirtualCity[]=$val;
		    }
		}
	if(!empty($checkVirtualCity)){
	    
	    $this->load->builder('LocationBuilder','location');
		$locationBuilder = new \LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		
		$cityObjs = $locationRepository->findMultipleCities($checkVirtualCity);
		foreach($cityObjs as $cityId => $cityObj) {
		    if($cityObj->isVirtualCity()) {
			$citiesByVitualCity = $locationRepository->getCitiesByVirtualCity($cityId);
			foreach($citiesByVitualCity as $city) {
			    $virtualCityArray[] = $city->getId();
			}
		    }
		}
	}
	
	$current_City = array_merge($current_City, $virtualCityArray);
	
	foreach($loc1 as $k=>$v){
	$localities.=$this->getLocalityNameFronId($v);
	}
	
	$displayArray['CurrentLocalities']=$localities;
	$checkArray=array_filter($current_City);
	if(!empty($checkArray)){
	$postArray['CurrentCities']=$current_City;
	}
	
	if($course_name != 'Study Abroad'){
	    unset($postArray['Locality']);
	}
	
        $this->displayResults($postArray, $displayArray, 0, 100,$search_key);
	$LDBSearchEnd = microtime(TRUE);
	error_log("SolrSearch:: LDBSearchTime:: ".($LDBSearchEnd - $LDBSearchStart));
    }

    private function _getDisplayCurrLocation()
    {
	    $hiddencurrentCity = $_POST['hiddencurrentCity'];
	    $hiddenpreferedMainCity = $_POST['hiddencurrentMainCity'];
	    $displayCurrLocation = array();
	    
	    for($i=0;$i<count($hiddencurrentCity);$i++) {
		    if($hiddencurrentCity[$i]) {
			    $displayCurrLocation[] = $hiddencurrentCity[$i];
		    }
		    else {
			    $displayCurrLocation[] = $hiddencurrentMainCity[$i];
		    }
	    }
	    
	    $displayCurrLocation = implode(', ',$displayCurrLocation);
	    return $displayCurrLocation;
    }
    
       private function _getDisplayPrefLocation()
    {
	    $hiddenpreferedCity = $_POST['hiddenpreferedCity'];
	    $hiddenpreferedMainCity = $_POST['hiddenpreferedMainCity'];
	    $displayPrefLocation = array();
	    
	    for($i=0;$i<count($hiddenpreferedCity);$i++) {
		    if($hiddenpreferedCity[$i]) {
			    $displayPrefLocation[] = $hiddenpreferedCity[$i];
		    }
		    else {
			    $displayPrefLocation[] = $hiddenpreferedMainCity[$i];
		    }
	    }
	    
	    $displayPrefLocation = implode(', ',$displayPrefLocation);
	    return $displayPrefLocation;
    }
	
    function displayoverlay($type='form', $overlay='SendMail', $filter='unviewed', $selected='0', $totalCount, $maxlimitTextBox='500', $msg=NULL, $ReqCredits=NULL, $AvlCredits=NULL, $countNdnc=NULL, $nonviewable=NULL, $inputData=NULL, $displayData=NULL, $inputDataMR=NULL) {
        
		$data['filter']          = $filter;
        $data['selected']        = $selected;
        $data['totalCount']      = $totalCount;
        $data['maxlimitTextBox'] = $maxlimitTextBox;
        $data['msg']             = base64_decode($msg);
        $data['ReqCredits']      = $ReqCredits;
        $data['AvlCredits']      = $AvlCredits;
        $data['countNdnc']       = $countNdnc;
        $data['type']            = $type;
        $data['overlay']         = $overlay;
        $data['nonviewable']     = $nonviewable;
        $data['inputData']       = $inputData;
        $data['inputDataMR']     = $inputDataMR;			
        $data['displayData']     = $displayData;
        echo $this->load->view("enterprise/ldb_search_overlay", $data);
    }

    /* API used for AJAX & add testprep hack */
    function ajax_submit_input_form() {
        $clientId                    = $_POST['clientid'];
        $ExtraFlag                   = $_POST['ExtraFlag'];
        $userIdList                  = isset($_POST['userIdList']) ? $_POST['userIdList'] : '';
        $contactType                 = $_POST['contactType'];
        $tabFlag                     = $_POST['tabFlag'];
        $usersCreditToBeDeductedInfo = json_decode($_POST['usersCreditToBeDeductedInfo'], true);
        $searchParameters            = json_decode(base64_decode($_POST['searchParameters']), true);
        $inputData                   = json_decode(base64_decode($_POST['inputData']), true);
        $searchParametersMR          = json_decode(base64_decode($_POST['searchParametersMR']), true);
        $actual_course_id            = $_POST['actual_course_id'];
        $start                       = $_POST['start'];
        $end                         = $_POST['end'];
        $checkbox_filter = isset($_POST['checkbox_filter']) ? $_POST['checkbox_filter'] : '';
        if (($checkbox_filter == 'SendSMS') && (empty($actual_course_id))) {
            $searchParameters = array_merge($searchParameters, array('DontShowSmsed' => '1'));
        }
        if (($checkbox_filter == 'SendEmail') && (empty($actual_course_id))) {
            $searchParameters = array_merge($searchParameters, array('DontShowEmailed' => '1'));
        }
        if (isset($tabFlag) && $tabFlag != '' && !empty($searchParameters)){
            unset($searchParameters['DontShowViewed']);
            $searchParameters[$tabFlag] = '1';
            $inputData[$tabFlag]        = '1';
        }
        
		$Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        echo json_encode($this->getCreditDetailsForDisplay($clientId, $userIdList, $contactType, $searchParameters, $start, $end, $ExtraFlag, $searchParametersMR, $actual_course_id, $usersCreditToBeDeductedInfo, $inputData));
    }

    function ajax_submit_sms_activity() {
        $this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);
        $activityId = $_POST['activityId'];
        $content = base64_decode($_POST['content']);
        $postDisplayData = $_POST['displayData'];
        if (isset($postDisplayData)) {
            $displayData = json_decode(base64_decode($postDisplayData), true);
        } else {
            $displayData = array();
        }

        if(isset($displayData['DesiredCourse']) && $displayData['DesiredCourse'] != '' && $displayArray['stream'] == ''){
            require APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
            $displayData['stream'] = $coursesList[$displayData['DesiredCourse']]['stream_id'];
            $displayData['actual_course_id'] = $coursesList[$displayData['DesiredCourse']]['actual_course_id'];
        }

        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        $this->sendSmsForActivity($clientId, $activityId, $content, $displayData);
    }

    function ajax_submit_email_activity() {
        $this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);
        $activityId = $_POST['activityId'];
        $content = base64_decode($_POST['content']);
        $subject = base64_decode($_POST['subject']);
        $fromEmail = $_POST['fromEmail'];
	    $fromSenderName = $_POST['fromSender'];
        $postDisplayData = $_POST['displayData'];
        if (isset($postDisplayData)) {
            $displayData = json_decode(base64_decode($postDisplayData), true);
        } else {
            $displayData = array();
        }

        if(isset($displayData['DesiredCourse']) && $displayData['DesiredCourse'] != '' && $displayArray['stream'] == ''){
            require APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
            $displayData['stream'] = $coursesList[$displayData['DesiredCourse']]['stream_id'];
            $displayData['actual_course_id'] = $coursesList[$displayData['DesiredCourse']]['actual_course_id'];
        }

        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        $this->sendEmailForActivity($clientId, $activityId, $subject, $content, $fromEmail,$fromSenderName, $displayData);
    }

    function ajax_submit_csv_activity($activityId, $csvType, $filename, $inputData, $displayData, $inputDataMR) {
        $this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        $mime = 'text/x-csv';
        $filename = $filename . ".csv";
        $data = $this->downloadCSVForActivity($clientId, $activityId, $csvType, $filename, $inputData, $displayData, $inputDataMR);
        if (strlen($data) < 200) {
            echo "<script>alert('An error occurred,please try again later.');</script>";
            exit;
        }
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($data));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($data));
        }
        echo ($data);
    }

    function ajax_submit_csv_activity_post() {
        
        $activityId = $this->input->post('csvActivationIdUserActions',true);
        $csvType = $this->input->post('csvldb_course_type_csv_download',true);
        $filename = $this->input->post('csvfilename_download',true);
        $inputData = $this->input->post('csvinputData',true);
        $displayData = $this->input->post('csvdisplayData',true);
        $inputDataMR = $this->input->post('csvinputDataMR',true);
        $csvTabFlag = $this->input->post('csvTabFlag',true);
        // $currentURL = $this->input->post('currentURL',true);
        
        $this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        $mime = 'text/x-csv';

        if (isset($displayData)) {
            $displayArray = json_decode(base64_decode($displayData), true);
            if (isset($csvTabFlag) && $csvTabFlag != ''){
                $displayArray[$csvTabFlag] = '1';
            }
            
            if(isset($displayArray['DesiredCourse']) && $displayArray['DesiredCourse'] != '' && $displayArray['stream'] == ''){
                require APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
                $displayArray['stream'] = $coursesList[$displayArray['DesiredCourse']]['stream_id'];
                $displayArray['actual_course_id'] = $coursesList[$displayArray['DesiredCourse']]['actual_course_id'];
            }

        }

        if(isset($displayArray['stream']) && trim($displayArray['stream']) != ''){
            $this->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new \ListingBaseBuilder();
            $HierarchyRepository = $listingBase->getHierarchyRepository();
            $streamObj = $HierarchyRepository->findStream($displayArray['stream']);
            $streamData = $streamObj->getObjectAsArray();
            $streamName = $streamData['name']; 
            $filename = $streamName.'_'.$filename;
        }
        
		$displayData = base64_encode(json_encode($displayArray));
        $filename = $filename . ".csv";

        $data = $this->downloadCSVForActivity($clientId, $activityId, $csvType, $filename, $inputData, $displayData, $inputDataMR);

        if($data == 'No_data_found'){
            echo "<script>alert('No users found. Please go back and try again.');</script>";
            // echo "<script> window.location = '". $currentURL ."'; </script>";
            exit;
        }
        if (strlen($data) < 200) {
            echo "<script>alert('An error occurred,please try again later.');</script>";
            exit;
        }
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($data));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($data));
        }
        echo ($data);
    }

    function getCreditDetails($clientId, $userIdList, $action, $ExtraFlag='false', $actual_course_id = '') {
        $this->load->library('sums_product_client');
        $objSumsProduct = new Sums_Product_client();
        $SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1, array('userId' => $clientId));
        $objLDBClient = new LDB_Client();
        $creditConsumeArray1 = $objLDBClient->sgetCreditToConsume($appID, $userIdList, $action, $clientId, $ExtraFlag,TRUE, $actual_course_id);
        error_log("creditConsumeArray is as " . print_r($creditConsumeArray1, true));
	
        $countNdnc = 0;
        $countToConsume = 0;
        $creditConsumeArray = array();
        foreach($creditConsumeArray1 as $key => $creditConsumex){
            $countNdnc += (array_key_exists("countNdnc", $creditConsumex)&&isset($creditConsumex['countNdnc'])&&!empty($creditConsumex['countNdnc']))?$creditConsumex['countNdnc']:0;
            $countToConsume += $creditConsumex[$key];
            $creditConsumeArray[$key] = $creditConsumex[$key];
        }

        error_log("countToConsume is as " . $countToConsume);
        $creditCount = 0;
        $subscriptionDetailMapping = array();
        foreach ($creditConsumeArray as $userId => $userCreditdeduct) {
            foreach ($SubscriptionArray as $subscription) {
                if ($subscription['BaseProdCategory'] == 'Lead-Search') {
					
                    if(!empty($subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed'])) {
							$countLeft = $subscription['BaseProdRemainingQuantity'] - $subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed'];
					} else {
						    $countLeft = $subscription['BaseProdRemainingQuantity'];
					}                  
					
                    if ($userCreditdeduct <= $countLeft) {
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['BaseProdRemainingQuantity'] = $subscription['BaseProdRemainingQuantity'];
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['BaseProductId'] = $subscription['BaseProductId'];
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['countLeft'] = $countLeft;
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed'] += $userCreditdeduct;
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['userList'][$userId] = $userCreditdeduct;
                        break;
                    }
                    
                }
            }
        }
        $sumConsumed = 0;
        $sumTotal = 0;
        foreach ($subscriptionDetailMapping as $subscriptionId => $details) {
            $subscriptionConsumptionArray[] = array('subscriptionId' => $subscriptionId, 'creditToConsume' => $details['countConsumed'], 'BaseProductId' => $details['BaseProductId'], 'userList' => $details['userList']);
            $sumConsumed+=$details['countConsumed'];
        }
        foreach ($SubscriptionArray as $subscription) {
            if ($subscription['BaseProdCategory'] == 'Lead-Search')
                $sumTotal+=$subscription['BaseProdRemainingQuantity'];
        }
        foreach ($subscriptionDetailMapping as $subscriptionId => $details) {
            $subid = $subscriptionId;
        }
        if ($countToConsume > $sumTotal) {
            $resultText = "You don't have sufficient Credits for this action";
             if(count($subscriptionDetailMapping) == 0) {
                $sumTotal = 0;
             }
            return array('result' => $resultText, 'subArray' => array(), 'CreditCount' => $sumTotal, 'CreditsForAction' => $countToConsume, 'countNdnc' => $countNdnc);
        } else {
            $resultText = "You have $sumTotal Credits. $sumConsumed credits will be used for this action.";
            return array('subid' => $subid, 'result' => $resultText, 'subArray' => $subscriptionConsumptionArray, 'CreditCount' => $sumTotal, 'CreditsForAction' => $sumConsumed, 'countNdnc' => $countNdnc);
        }
    }

    function checkConsumptionInfoValidity($clientId, $consumptionInfo) {
        $this->load->library('sums_product_client');
        $objSumsProduct = new Sums_Product_client();
        $SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1, array('userId' => $clientId));
        foreach ($SubscriptionArray as $subscription) {
            if ($subscription['SubscriptionId'] == $consumptionInfo['subscriptionId']) {
                if ($subscription['BaseProdRemainingQuantity'] < $consumptionInfo['subscriptionId']) {
                    return false;
                }
            }
        }

        return true;
    }

    /* private API when confirmation overlay called */
    function getCreditDetailsForDisplay($clientId, $userIdList, $contactType, $searchParameters, $start, $end, $ExtraFlag='false', $searchParametersMR = '', $actual_course_id = '', $usersCreditToBeDeductedInfo = array(), $inputData = array()) {
        $this->load->library(array('LDB_Client'));
        $ldbObj = new LDB_Client();
        $this->load->model('ldbmodel');
        global $userGlobalViewLimit;
        global $MRPricingArray;
        
        error_log("Shivam basic para ClientId: " . $clientId . " ||userIdList: " . $userIdList . " ||contactType: " . $contactType . " ||searchPara: " . print_r($searchParameters, true) . " || start: " . $start . " ||end: " . $end);	

        if($actual_course_id == 2 || $actual_course_id == 52) {
            $flagTypeArray = array('LDB_MR','SA_MR');
        } else {
            $flagTypeArray = array('LDB','SA');
        }

        $nonViewAbleList = array();
        $userDataArray = array();

        if($userIdList != '' && !empty($inputData)){ 

            unset($inputData['DontShowViewed']);
            // $inputData[$tabFlag] = '1';
            if($inputData['streamId']) {
                $inputData['stream'] = $inputData['streamId'];
                unset($inputData['streamId']);
            }
            
            $userIdsArray = explode(",", $userIdList);
            
            if(!empty($inputData)) {
                $userDataArray = Modules::run('MIS/SADownloadleads/getLeadDataFromSolr', $userIdsArray, $inputData, TRUE);
            }

            foreach ($userDataArray as $userData) {

                if($actual_course_id == 2 || $actual_course_id == 52) {

                    switch ($contactType) {
                        case 'view':
                            $usersCreditToBeDeductedInfo[$userData['userid']]['view'] = $MRPricingArray[$userData['StreamId']]['view'] ;
                            break;
                        
                        case 'email':
                            $usersCreditToBeDeductedInfo[$userData['userid']]['email'] = $MRPricingArray[$userData['StreamId']]['email'] ;
                            break;

                        case 'sms':
                            $usersCreditToBeDeductedInfo[$userData['userid']]['sms'] = $MRPricingArray[$userData['StreamId']]['SMS'] ;
                            break;
                    }

                } else {

                    switch ($contactType) {
                        case 'view':
                            $usersCreditToBeDeductedInfo[$userData['userid']]['view'] = $userData['View Credit'];
                            break;
                        
                        case 'email':
                            $usersCreditToBeDeductedInfo[$userData['userid']]['email'] = $userData['Email Credit'];
                            break;

                        case 'sms':
                            $usersCreditToBeDeductedInfo[$userData['userid']]['sms'] = $userData['Sms Credit'];
                            break;
                    }

                }
                
				$userViewData = Modules::run('searchAgents/searchAgents_Server/getUserViewCount', $userData['userid']);
                if(!$userData['SubStreamId']){
                    $substreamId = 0;
                }
                else {
                    $substreamId = $userData['SubStreamId'];
                }
                $count = ($userViewData[$userData['StreamId']][$substreamId]) ? ($userViewData[$userData['StreamId']][$substreamId]) : 0;
                
                $contactCount = $this->ldbmodel->getUserContactCount($userData['userid'], $clientId, $contactType);
                
                if($contactCount == 0){
                    if(($userViewData['totalViewCount'] >= $userGlobalViewLimit) || ($count >= $userData['View Count'])){
                        $nonViewAbleList[] = $userData['userid'];
                    }
                }

            }

        }

		if((!empty($actual_course_id)) && (!empty($searchParameters))) {
			if(count($searchParametersMR['courses'] && !empty($searchParametersMR['startDate']) && !empty($searchParametersMR['endDate']))) {

				$responseData = modules::run('lms/lmsServer/getMatchedResponses', $searchParametersMR['courses'], array(), $searchParametersMR['startDate'], $searchParametersMR['endDate'], FALSE);
                $matchedCourses = array_keys($responseData['courses']);
                
				if(count($matchedCourses)) {

					$this->load->model('ldbmodel');	

                    $searchParameters['responseSubmitDateStart'] = $searchParametersMR['startDate'].' 00:00:00';
                    $searchParameters['responseSubmitDateEnd'] = $searchParametersMR['endDate'];
                    $searchParameters['matchedCourses'] = $matchedCourses;
                    $searchParameters['resultOffset'] = $start;
                    $searchParameters['numResults'] = $end;
                    
                    // changed for viewed/emailed/smsed

                    if($searchParameters['Viewed'] || $searchParameters['Emailed'] || $searchParameters['Smsed']) {
                        $searchResult = Modules::run('enterprise/enterpriseSearch/searchMR', $searchParameters, $clientId, $start, $end);
                    }
                    else {
                        $searchResult = Modules::run('enterprise/enterpriseSearch/searchMRSolr', $searchParameters, $clientId);
                    }

                    $userIds =  array_keys($searchResult['leadData']);
                    if(!empty($searchResult['leadData'])) {
                        foreach ($searchResult['leadData'] as $userId => $userDetails) {
                            switch ($contactType) {
                                case 'view':
                                    $usersCreditToBeDeductedInfo[$userId]['view'] = $MRPricingArray[$userDetails['streamId']]['view'] ;
                                    break;
                                
                                case 'email':
                                    $usersCreditToBeDeductedInfo[$userId]['email'] = $MRPricingArray[$userDetails['streamId']]['email'] ;
                                    break;

                                case 'sms':
                                    $usersCreditToBeDeductedInfo[$userId]['sms'] = $MRPricingArray[$userDetails['streamId']]['SMS'] ;
                                    break;
                            }

                            $userViewData = Modules::run('searchAgents/searchAgents_Server/getUserViewCount', $userId);
                            if(!$userDetails['subStreamId']){
                                $substreamId = 0;
                            }
                            else {
                                $substreamId = $userDetails['subStreamId'];
                            }
                            $count = ($userViewData[$userDetails['streamId']][$substreamId]) ? ($userViewData[$userDetails['streamId']][$substreamId]) : 0;

                            $contactCount = $this->ldbmodel->getUserContactCount($userId, $clientId, $contactType);
                            
                            if($contactCount == 0){

                                if(($userViewData['totalViewCount'] >= $userGlobalViewLimit) || ($count >= $userDetails['ViewCount'])){
                                    $nonViewAbleList[] = $userId;
                                }

                            }

                        }
                    }

                    $searchUsers = implode(',', $userIds);
                    $userIdList = (empty($userIdList)) ? $searchUsers : $userIdList . "," . $searchUsers;

				}

			}

		} 
        else if (!empty($searchParameters)) {
			
			if($searchParameters['ExtraFlag']) {
                $key = getTempUserData('search_key');
                $search_key = md5(json_encode($searchParameters)."-".$key);
                if($searchParameters['DesiredCourse'] == 'Study Abroad'){
                    unset($searchParameters['DesiredCourse']);
                }
                $searchResult = $ldbObj->searchLDB($appID, $searchParameters, $clientId, $start, $end,$search_key);
            }
            else {

                if($searchParameters['Viewed'] || $searchParameters['Emailed'] || $searchParameters['Smsed']) {
                    $searchResult = Modules::run('enterprise/enterpriseSearch/searchLDB', $searchParameters, $start, $end, $clientId);
                }
                else {
                    $searchResult = Modules::run('enterprise/enterpriseSearch/searchLDBSolr', $searchParameters, $start, $end, $clientId);
                }
                foreach ($searchResult['result'] as $userId => $userDetails) {
                    switch ($contactType) {
                        case 'view':
                            $usersCreditToBeDeductedInfo[$userId]['view'] = $userDetails['ViewCredit'];
                            break;
                        
                        case 'email':
                            $usersCreditToBeDeductedInfo[$userId]['email'] = $userDetails['EmailCredit'];
                            break;

                        case 'sms':
                            $usersCreditToBeDeductedInfo[$userId]['sms'] = $userDetails['SmsCredit'];
                            break;
                    }
                    
                    $userViewData = Modules::run('searchAgents/searchAgents_Server/getUserViewCount', $userId);
                    if(!$userDetails['subStreamId']){
                        $substreamId = 0;
                    }
                    else {
                        $substreamId = $userDetails['subStreamId'];
                    }
                    $count = ($userViewData[$userDetails['streamId']][$substreamId]) ? ($userViewData[$userDetails['streamId']][$substreamId]) : 0;

                    $contactCount = $this->ldbmodel->getUserContactCount($userId, $clientId, $contactType);
                    
                    if($contactCount == 0){
                        
                        if(($userViewData['totalViewCount'] >= $userGlobalViewLimit) || ($count >= $userDetails['ViewCount'])){
                            $nonViewAbleList[] = $userId;
                        }

                    }

                }
            }
			$searchUsers = implode(',', $searchResult['userIds']);
            if($searchUsers != '')
			    $userIdList = (empty($userIdList)) ? $searchUsers : $userIdList . "," . $searchUsers;

		}
		
		$viewAbleList = array();
		if(!empty($userIdList)) {
			$olduserIdList = explode(",", $userIdList);
			if($contactType == 'view') {
				$tempViewAbleList = $ldbObj->getViewableUsers(12, $olduserIdList, $clientId, $ExtraFlag,TRUE, $actual_course_id);
                $viewAbleList = array_diff($tempViewAbleList, $nonViewAbleList);
                $userIdList = implode(",", $viewAbleList);
			}
			else {
				$viewAbleList = $olduserIdList;
			}
		}
        if(count($viewAbleList)) {
			
            $CreditConsumeInformation = Modules::run('enterprise/enterpriseSearch/getCreditDetails', $clientId, $viewAbleList, $contactType, $ExtraFlag, $actual_course_id, $usersCreditToBeDeductedInfo);
            error_log("CreditConsumeInformation is as " . print_r($CreditConsumeInformation, true));
            if(count($viewAbleList) != count($olduserIdList)) {
            	$CreditConsumeInformation['nonviewable'] = count($olduserIdList) - count($viewAbleList);
            }

        }
        else {
        	$resultText = "No lead is downloadable";
        	$CreditConsumeInformation = array('result' => $resultText, 'nonviewable' => 'all', 'subArray' => array(), 'CreditCount' => 0, 'CreditsForAction' => 0, 'countNdnc' => 0);
        }

        error_log($contactType . "userIdList is as " . print_r($userIdList, true));
        $activityResponse = $ldbObj->recordLDBActivity($appID, $CreditConsumeInformation, $clientId, $userIdList, $contactType, 'prompt');
        $CreditConsumeInformation['activityId'] = $activityResponse;
        return $CreditConsumeInformation;

    }

    function remove_element($arr, $val) {
        foreach ($arr as $key => $value) {
            if ($arr[$key] == $val) {
                unset($arr[$key]);
            }
        }
        return $arr = array_values($arr);
    }

    function consumeCreditsForActivity($clientId, $activityId, $displayData=array()) {
        $this->load->library(array('LDB_Client'));
        $ldbObj = new LDB_Client();
        $ActivityDetails = $ldbObj->getActivityDetails(1, $activityId);
        $contactType = $ActivityDetails[0]['Action'];
        $subscriptionList = json_decode($ActivityDetails[0]["SubscriptionInfo"], true);
        $_CreditsForAction = $subscriptionList['CreditsForAction'];
        if ($ActivityDetails[0]['Status'] != 'prompt') {
            return array('result' => 'This activity is already done/in progress');
        }
        $ldbObj->updateActivityStatus(1, $ActivityDetails[0]['Id'], 'inprogress_started');
        if ((!$this->checkConsumptionInfoValidity($clientId, $subscriptionList['subArray'])) && ($_CreditsForAction > 0)) {
            $ldbObj->updateActivityStatus(1, $ActivityDetails[0]['Id'], 'inprogress_subscription_validity_falied');
            $subscriptionList = $this->getCreditDetails($clientId, explode(",", $ActivityDetails[0]['UserIdList']), $ActivityDetails[0]['Action']);
        }
        if (count($subscriptionList['subArray']) != 0 || $_CreditsForAction <= 0) {
            $ldbObj->updateActivityStatus(1, $ActivityDetails[0]['Id'], 'inprogress_subscription_validity_checked');
            $returnArray = array();
            $this->load->library(array('subscription_client'));
            $subsObj = new Subscription_client();
            $ldbObj = new LDB_Client();
            $start = 0;
            foreach ($subscriptionList['subArray'] as $subscription) {
                error_log("LLDBA::" . print_r($_CreditsForAction, true));
                error_log("LLDBA::" . $subscription['subscriptionId'] . "==" . $subscription['creditToConsume'] . "==" . $clientId);
                if ($_CreditsForAction > 0) {
                    error_log("LLDBA::" . print_r($clientId, true));
                    $returnval = $subsObj->consumeLDBCredits(12, $subscription['subscriptionId'], $subscription['creditToConsume'], $clientId, $clientId);
                }
            }
            $ldbObj->updateActivityStatus(1, $ActivityDetails[0]['Id'], 'inprogress_subscription_credit_deduction_done');
            
            foreach ($subscriptionList['subArray'] as $subscription) {
                foreach ($subscription['userList'] as $userId => $creditdeduct) {

                    $userIdArray = array();
                    $userDataArray = array();
                    $userIdArray[] = $userId;
                    if(!empty($displayData)) {
                        $userDataArray = Modules::run('MIS/SADownloadleads/getLeadDataFromSolr', $userIdArray, $displayData, TRUE);
                    }

                    if($displayData['actual_course_id'] == 2 || $displayData['actual_course_id'] == 52) 
                        $flagType = 'LDB_MR';
                    else 
                        $flagType = 'LDB';

                    $returnval = $ldbObj->sUpdateContactViewed(12, $clientId, $userId, $contactType, $subscription['subscriptionId'], $creditdeduct, $flagType, '', $userDataArray);
                    $returnArrayElement = json_decode($returnval, true);
                    $returnArray[] = $returnArrayElement;
                    if ($_CreditsForAction > 0) {
                        $returnval = $subsObj->updateSubscriptionLog(12, $subscription['subscriptionId'], $creditdeduct, $clientId, $clientId, $subscription['BaseProductId'], $returnArrayElement[0]['insertId'], $contactType, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
                    }
                }
            }
            $ldbObj->updateActivityStatus(1, $ActivityDetails[0]['Id'], 'inprogress_subscription_deduction_snippet_created');
            return array('result' => $returnArray, 'CreditsForAction' => $subscriptionList['CreditsForAction'], 'CreditCount' => ($subscriptionList['CreditCount'] - $subscriptionList['CreditsForAction']),
                'UserIdList' => $ActivityDetails[0]['UserIdList'], 'activityId' => $ActivityDetails[0]['Id']);
        }
        return array('result' => "You don't have sufficient credit to perform the given action");
    }

    function consumeLDBSubscription($clientId, $userIds, $contactType, $ExtraFlag='false', $actual_course_id = '') {
        $count = count($userIds);
        $subscriptionList = $this->getCreditDetails($clientId, $userIds, $contactType, $ExtraFlag, $actual_course_id);
        if (count($subscriptionList['subArray']) != 0) {
            $returnArray = array();
            $this->load->library(array('subscription_client', 'LDB_Client'));
            $subsObj = new Subscription_client();
            $ldbObj = new LDB_Client();
            $start = 0;
            foreach ($subscriptionList['subArray'] as $subscription) {
                $returnval = $subsObj->consumeLDBCredits(12, $subscription['subscriptionId'], $subscription['creditToConsume'], $clientId, $clientId);
                foreach ($subscription['userList'] as $userId => $creditdeduct) {
                    $returnval = $ldbObj->sUpdateContactViewed(12, $clientId, $userId, $contactType, $subscription['subscriptionId'], $creditdeduct, 'LDB', $actual_course_id);
                    $returnArrayElement = json_decode($returnval, true);
                    $returnArray[] = $returnArrayElement;
                    $returnval = $subsObj->updateSubscriptionLog(12, $subscription['subscriptionId'], $creditdeduct, $clientId, $clientId, $subscription['BaseProductId'], $returnArrayElement[0]['insertId'], $contactType, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
                }
            }
            return array('result' => $returnArray, 'CreditsForAction' => $subscriptionList['CreditsForAction'], 'CreditCount' => ($subscriptionList['CreditCount'] - $subscriptionList['CreditsForAction']));
        } else {
            return array('result' => 'You dont have sufficient credit to perform the given action');
        }
    }

    function addCoursesToGroups($groupId='') {
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client', 'LDB_Client', 'ajax', 'category_list_client'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $ldbObj = new LDB_client();
        $data['groupList'] = $ldbObj->sgetGroupList(1);
        $data['courseGroupMapping'] = $ldbObj->sgetCourseListByGroup(1, '', $groupId);
        $categoryClient = new Category_list_client();
        $data['groupId'] = $groupId;
        $data['testprepcourseslist'] = $categoryClient->getTestPrepCoursesList(1);
        $data['getCourseListByGroupTestPrep'] = $ldbObj->getCourseListByGroupTestPrep(1, $groupId);
        $this->load->view("enterprise/CourseToGroupMapping", $data);
    }

    function addCoursesToGroup() {
        $groupId = '';
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client', 'LDB_Client', 'ajax', 'category_list_client'));
        $this->userStatus = $this->checkUserValidation();
        $ExtraFlag = 'false';
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        if (is_array($_POST['testprepcourseId']) && count($_POST['testprepcourseId']) > 0) {
            $ExtraFlag = array();
            foreach ($_POST['courseId'] as $value) {
                $ExtraFlag[] = 'false';
            }
            foreach ($_POST['testprepcourseId'] as $value) {
                $ExtraFlag[] = 'true';
            }
            if (is_array($_POST['courseId']) && count($_POST['courseId']) > 0) {
                $courseids = array_merge($_POST['courseId'], $_POST['testprepcourseId']);
            } else {
                $courseids = $_POST['testprepcourseId'];
            }
        } else {
            $courseids = $_POST['courseId'];
        }
        $ldbObj = new LDB_client();
        $categoryClient = new Category_list_client();
        $ldbObj->sAddCoursesToGroup(1, $_POST['groupId'], $courseids, $ExtraFlag);
        $data['groupList'] = $ldbObj->sgetGroupList(1);
        $data['courseGroupMapping'] = $ldbObj->sgetCourseListByGroup(1);
        $data['testprepcourseslist'] = $categoryClient->getTestPrepCoursesList(1);
        $data['getCourseListByGroupTestPrep'] = $ldbObj->getCourseListByGroupTestPrep(1, $groupId);
        $categoryClient = new Category_list_client();
        $this->load->view("enterprise/CourseToGroupMapping", $data);
    }

    function removeCoursesFromGroup() {
        $groupId = '';
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client', 'LDB_Client', 'ajax', 'category_list_client'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        if (is_array($_POST['testprepcourseId']) && count($_POST['testprepcourseId']) > 0) {
            $ExtraFlag = array();
            foreach ($_POST['courseId'] as $value) {
                $ExtraFlag[] = 'false';
            }
            foreach ($_POST['testprepcourseId'] as $value) {
                $ExtraFlag[] = 'true';
            }
            if (is_array($_POST['courseId']) && count($_POST['courseId']) > 0) {
                $courseids = array_merge($_POST['courseId'], $_POST['testprepcourseId']);
            } else {
                $courseids = $_POST['testprepcourseId'];
            }
        } else {
            $courseids = $_POST['courseId'];
        }

	$ldbObj = new LDB_client();

        $categoryClient = new Category_list_client();
        $ldbObj->sRemoveCoursesFromGroup(1, $_POST['groupId'], $courseids, $ExtraFlag);
        $data['groupList'] = $ldbObj->sgetGroupList(1);
        $data['courseGroupMapping'] = $ldbObj->sgetCourseListByGroup(1);
        $data['testprepcourseslist'] = $categoryClient->getTestPrepCoursesList(1);
        $data['getCourseListByGroupTestPrep'] = $ldbObj->getCourseListByGroupTestPrep(1, $groupId);
        $this->load->view("enterprise/CourseToGroupMapping", $data);
    }

    function groupCreditConsumptionPolicyDisplay() {
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client', 'LDB_Client', 'ajax', 'category_list_client'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $ldbObj = new LDB_client();
        $data['groupList'] = $ldbObj->sgetGroupList(1);
        $this->load->view("enterprise/GroupCreditConsumptionPolicyView", $data);
    }

    function updateGroupCreditConsumptionPolicy() {
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client', 'LDB_Client', 'ajax', 'category_list_client'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $ldbObj = new LDB_client();
        $ldbObj->saddGroupCreditConsumptionPolicy(1, $_POST['groupId'], $_POST['view'], $_POST['email'], $_POST['sms'], $_POST['shared_view_limit'], $_POST['premimum_view_cr'], $_POST['premimum_view_limit'], $_POST['email_limit'], $_POST['sms_limit'], $_POST['view_limit']);
        $data['groupList'] = $ldbObj->sgetGroupList(1);
        $this->load->view("enterprise/GroupCreditConsumptionPolicyView", $data);
    }

    function getColumnList($courseType) {
        $columnListArray = array();
        if ($courseType == 'local') {

			$columnListArray[] = 'Name';
            $columnListArray[] = 'First Name';
            $columnListArray[] = 'Last Name';
            $columnListArray[] = 'Desired Course';
            $columnListArray[] = 'Graduation Year';
            $columnListArray[] = 'XII Year';
            $columnListArray[] = 'Current Location';
	        $columnListArray[] = 'Current Locality';
            $columnListArray[] = 'Creation Date';
	        $columnListArray[] = 'Is in NDNC list';
            $columnListArray[] = 'Email';
            $columnListArray[] = 'ISD Code';
            $columnListArray[] = 'Mobile';

        } 
        else if ($courseType == 'nationalMR') {

			$columnListArray[] = 'First Name';
            $columnListArray[] = 'Last Name';
            $columnListArray[] = 'Full Name';
            $columnListArray[] = 'Response Date';
            $columnListArray[] = 'Email';
            $columnListArray[] = 'ISD Code';
            $columnListArray[] = 'Mobile';
            $columnListArray[] = 'NDNC Status';
            $columnListArray[] = 'Matched Response For';
            $columnListArray[] = 'Exams Taken';
            $columnListArray[] = 'Work Experience';
            $columnListArray[] = 'Current City';
			$columnListArray[] = 'Preferred Study Locations';

        } 
        else if ($courseType == 'national') {

			$columnListArray[] = 'First Name';
            $columnListArray[] = 'Last Name';
            $columnListArray[] = 'Full Name';
            $columnListArray[] = 'Date Of Interest';
            $columnListArray[] = 'Email';
            $columnListArray[] = 'ISD Code';
            $columnListArray[] = 'Mobile';
            $columnListArray[] = 'NDNC Status';
            $columnListArray[] = 'Stream';
            $columnListArray[] = 'Sub Stream';
            $columnListArray[] = 'Specialization';
            $columnListArray[] = 'Course';
            $columnListArray[] = 'Mode';
            $columnListArray[] = 'Exams Taken';
            $columnListArray[] = 'Work Experience';
            $columnListArray[] = 'Current Country';
            $columnListArray[] = 'Current City';
	        $columnListArray[] = 'Current Locality';
            // $columnListArray[] = 'Preferred Study Locations';

        } 
        else if ($courseType == 'studyAbroad' || $courseType == 'studyabroad') {

			$columnListArray[] = 'Name';            
			$columnListArray[] = 'First Name';
            $columnListArray[] = 'Last Name';
            $columnListArray[] = 'Email';
            $columnListArray[] = "ISD Code";
            $columnListArray[] = 'Mobile';
            $columnListArray[] = 'Field of Interest';
            $columnListArray[] = 'Desired Course Level';
	        $columnListArray[] = 'Desired Specialization';
            $columnListArray[] = 'Desired Countries';
            $columnListArray[] = 'Plan to go';
            $columnListArray[] = 'Exams Taken';
            $columnListArray[] = 'Valid Passport';
            $columnListArray[] = 'Current Location';
            $columnListArray[] = 'Creation Date';
            $columnListArray[] = 'Is in NDNC list';
            
        }
        
        return $columnListArray;
    }

    function getCreditRequiredForConsumption($clientId, $userIdCSV, $ExtraFlag='false', $actual_course_id = '') {
        $this->init();

        if($actual_course_id == 2 || $actual_course_id == 52){
           $creditConsumeArray = $this->getCreditRequiredForConsumptionMR($clientId, $userIdCSV,$actual_course_id);
           return json_encode($creditConsumeArray);
           
        }

        $objLDBClient = new LDB_Client();
        $userIDArray = explode(",", $userIdCSV);

        $creditConsumeArray = array();
        $retArr['view'] = $objLDBClient->sgetCreditToConsume(1, $userIDArray, 'view', $clientId, $ExtraFlag, '', $actual_course_id);
        $retArr['email'] = $objLDBClient->sgetCreditToConsume(1, $userIDArray, 'email', $clientId, $ExtraFlag, '', $actual_course_id);
        $retArr['sms'] = $objLDBClient->sgetCreditToConsume(1, $userIDArray, 'sms', $clientId, $ExtraFlag, '', $actual_course_id);

        $creditdetailUser = array();
        $userDCnotMapped = array(); //array to store user whose desired course is not mapped to any course
        foreach ($retArr as $key => $arr) {
            foreach ($arr as $key1=>$val) {
                $creditConsumeArray[$key1][$key] = $val;
            }
        }

        foreach($creditConsumeArray as $row=>$val){
            $creditdetailUser[] = $row;
        }

        foreach($userIDArray as $row=>$val){
            if(!in_array($val, $creditdetailUser)){
                $userDCnotMapped[] = $val;
                $creditConsumeArray[$val] = 'contact support';
            }
        }

        if(!empty($userDCnotMapped) && SEND_MAIL_FOR_UNMAPPED_COURSES == TRUE){
            $pageRefererMail =$_SERVER['HTTP_REFERER'];
            Modules::run('mailer/Mailer/sendContactIssueRequest',$userDCnotMapped,$ExtraFlag,$pageRefererMail);
        }

        error_log("final creditConsumeArray ".print_r($creditConsumeArray,true));
        return json_encode($creditConsumeArray);
    }

    function getCreditConsumed($userIdCSV) {
        error_log("CSV = " . $userIdCSV);
        $this->init();
        $objLDBClient = new LDB_Client();
        $userIDArray = explode(",", $userIdCSV);
        
        $creditConsumeArray = array();
        foreach($userIDArray as $userid){
            $creditConsumeArray[$userid]['view'][0]['CreditConsumed'] = null;
        }
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        error_log($clientId . 'R2R');
        $retArray = $objLDBClient->sgetCreditConsumedForAction($clientId, $userIdCSV, 'view');
        foreach ($retArray as $userIdArr) {
            $creditConsumeArray[$userIdArr['userid']]['view'][0]['CreditConsumed'] = $userIdArr['CreditConsumed'];
        }
        return json_encode($creditConsumeArray);
    }

    function ajax_getCreditConsumed($userIdCSV) {
        $ExtraFlag = $_POST['ExtraFlag'];
        echo $this->getCreditConsumed($userIdCSV);
    }

    /* add testprep hack */
    function ajax_getCreditRequiredForConsumption($userIdCSV, $actual_course_id = '') {
        $ExtraFlag = $_POST['ExtraFlag'];
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        echo $this->getCreditRequiredForConsumption($clientId, $userIdCSV, $ExtraFlag, $actual_course_id);
    }

    function display_static_overlay($no) {
        $data = array();
        if ($no == 1) {
            echo $this->load->view("enterprise/display_static_overlay", $data);
        }
    }
/*
    function runSearchAgent($searchagentid, $flag_manage_page) {
        $this->init();
        $this->load->library('SearchAgents_client');
        $SAClientObj = new SearchAgents_client();
        $appId = 1;
        $search_agents_display_array = $SAClientObj->getSADisplayData($appId, $searchagentid);
        $inputArray = json_decode(base64_decode($search_agents_display_array[0]['inputdata']), true);
        $displayArray = json_decode(base64_decode($search_agents_display_array[0]['displaydata']), true);
        
        $inputArray['flag_manage_page'] = $flag_manage_page;
        $displayArray['flag_manage_page'] = $flag_manage_page;
        $this->displayResults($inputArray, $displayArray, 0, 50);
    }
*/

    function runSearchAgent($searchagentid, $flag_manage_page) {
        ini_set('memory_limit', '512M');
        
        $this->init();
        $this->load->library('SearchAgents_client');
        $SAClientObj = new SearchAgents_client();
        $appId = 1;
        $inputArray_searchAgent = array();
        $displayArray_searchAgent = array();
        $search_agents_display_array = $SAClientObj->getSADisplayData($appId, $searchagentid);
        $inputArray = json_decode(base64_decode($search_agents_display_array[0]['inputdata']), true);
        $displayArray = json_decode(base64_decode($search_agents_display_array[0]['displaydata']), true);
        
        $inputArray['flag_manage_page'] = $flag_manage_page;
        $displayArray['flag_manage_page'] = $flag_manage_page;
        // $value = "";
        // $darray = array();
        // if (!isset($inputArray['ExtraFlag'])) {
	       //  $dataArray  =  $SAClientObj->getAllMultiValuesSearchAgent('1',$searchagentid);
        //     foreach ($dataArray as $data) {
	       //      if($data['keyname'] == 'Specialization') {
        //     	    $value1 = 'Specialization';
        //     	    $darray1[] = $data['SpecializationId'];
        //     	    $string1 .= $data['SpecializationName'] . ",";
        //     	}
	       //      if (($data['keyname'] == 'desiredcourse') && ($data['SpecializationId'] != '2')&& ($data['SpecializationId'] != '24')) {
        //         	$value2 = 'desiredcourse';
        //         	$darray2[] = $data['CourseName'];
        //         	$string2 .= $data['CourseName'] . ",";
        //         }
        //     }
        //     if ($value1 == 'Specialization') {
        //     	$displayArray['Specialization'] = substr($string1, 0, -1);
        //     	$inputArray['Specialization'] = $darray1;
        //     }
        //     if ($value2 == 'desiredcourse') {
        //     	$inputArray['DesiredCourse'] = $darray2;
        //     	$displayArray['DesiredCourse'] = substr($string2, 0, -1);
        //     }
        // }
      
        $inputArray['DontShowViewed'] = '1';
        unset($inputArray['Viewed']);
        unset($inputArray['DontShowEmailed']);
        unset($inputArray['DontShowSmsed']);
        unset($inputArray['Emailed']);
        unset($inputArray['Smsed']);

        $displayArray['ldbAccessMsg'] = 'This is a real-time preview of your Lead Genie. The functionality to download leads manually is not available any more.';
        
        if(count($inputArray['course_id'])) {
            Global $managementStreamMR;
            Global $engineeringtStreamMR;

            /*below code is tohandle migration issue for old search agents*/
            if($inputArray['actual_course_name'] == 'Full Time MBA/PGDM' && empty($inputArray['streamId']) ){
                $inputArray['streamId'] = $managementStreamMR;
            } else if ( $inputArray['actual_course_name'] == 'B.E./B.Tech' && empty($inputArray['streamId']) ) {
                $inputArray['streamId'] = $engineeringtStreamMR;
            }
            
            $inputArray['resultOffset']=0;
            $inputArray['numResults']=100;

        	$dataArrayMR = array();
        	$dataArrayMR['courses'] = $inputArray['course_id'];
        	$dataArrayMR['startDate'] = date('Y-m-d',strtotime('-1 Month'));
        	$dataArrayMR['endDate'] = date('Y-m-d 23:59:59');
        	
        	unset($inputArray['course_id']);
        	unset($inputArray['underViewedLimitFlagSet']);
        	unset($inputArray['DateFilter']);
        	
            $returnView = Modules::run('enterprise/enterpriseSearch/displayResultsMR', $inputArray, $displayArray, 0, 100, $dataArrayMR);
            echo $returnView;
        }
        else {
        	$inputArray['DateFilterTo'] = date('Y-m-d 23:59:59');
        	$inputArray['DateFilterFrom'] = date('Y-m-d',strtotime('-1 Month'));
        	
        	$this->load->helper("string_helper");
        	$search_key = random_string("alnum", 32).time();
        	storeTempUserData('search_key',$search_key);
            
            if (isset($inputArray['ExtraFlag']) && $inputArray['ExtraFlag'] == 'studyabroad') {
        	   $this->displayResults($inputArray, $displayArray, 0, 100,$search_key);
            }
            else {
                $returnView = Modules::run('enterprise/enterpriseSearch/displayResults', $inputArray, $displayArray, 0, 100, $search_key);
                echo $returnView;
            }

        }
    
    }
    
    function downloadStudyAbroadLeadsCSV()
    {
		return;
	    $this->dbLibObj = DbLibCommon::getInstance('User');
	    $dbHandle = $this->_loadDatabaseHandle('write');

	    $sql = "select distinct(tuser.userid) from tUserPref,tuser,tUserLocationPref,tuserflag where tUserPref.UserId=tuser.userid and tUserPref.Status='live' and tuserflag.hardbounce='0' and tuserflag.ownershipchallenged='0' and tuserflag.abused='0' and tuserflag.softbounce='0' and tuserflag.isTestUser='NO' and tUserPref.UserId=tuserflag.userId and tuserflag.unsubscribe='0' and tUserLocationPref.UserId=tuser.userid and tUserLocationPref.PrefId=tUserPref.PrefId and tUserLocationPref.Status='live' and  (tUserPref.ExtraFlag='studyabroad' ) and  (tUserPref.DesiredCourse='357' OR tUserPref.DesiredCourse='358' OR tUserPref.DesiredCourse='359' OR tUserPref.DesiredCourse='361' OR tUserPref.DesiredCourse='362' OR tUserPref.DesiredCourse='364' OR tUserPref.DesiredCourse='365' OR tUserPref.DesiredCourse='367' OR tUserPref.DesiredCourse='363' OR tUserPref.DesiredCourse='369' OR tUserPref.DesiredCourse='370' OR tUserPref.DesiredCourse='371' OR tUserPref.DesiredCourse='373' OR tUserPref.DesiredCourse='374' OR tUserPref.DesiredCourse='376' OR tUserPref.DesiredCourse='377' OR tUserPref.DesiredCourse='379' OR tUserPref.DesiredCourse='375' ) and  (tuser.city='64' OR tuser.city='174' OR tuser.city='278' OR tuser.city='702' OR tuser.city='10223' OR tuser.city='10224' OR tuser.city='39' OR tuser.city='40' OR tuser.city='703' OR tuser.city='94' OR tuser.city='702' OR tuser.city='10178' OR tuser.city='998' OR tuser.city='699' OR tuser.city='136' OR tuser.city='159' OR tuser.city='160' OR tuser.city='713' OR tuser.city='197' OR tuser.city='212' OR tuser.city='213' OR tuser.city='214' OR tuser.city='63' OR tuser.city='10200' OR tuser.city='100' OR tuser.city='776' OR tuser.city='10199' OR tuser.city='775' OR tuser.city='186' OR tuser.city='10505' OR tuser.city='770' OR tuser.city='10637' OR tuser.city='278' OR tuser.city='49' OR tuser.city='58' OR tuser.city='796' OR tuser.city='10509' OR tuser.city='808' OR tuser.city='10510' OR tuser.city='806' OR tuser.city='78' OR tuser.city='10511' OR tuser.city='92' OR tuser.city='10190' OR tuser.city='815' OR tuser.city='280' OR tuser.city='103' OR tuser.city='124' OR tuser.city='143' OR tuser.city='261' OR tuser.city='153' OR tuser.city='727' OR tuser.city='807' OR tuser.city='728' OR tuser.city='812' OR tuser.city='10191' OR tuser.city='10596' OR tuser.city='62' OR tuser.city='10513' OR tuser.city='10512' OR tuser.city='10514' OR tuser.city='125' OR tuser.city='127' OR tuser.city='131' OR tuser.city='133' OR tuser.city='10193' OR tuser.city='787' OR tuser.city='10192' OR tuser.city='10194' OR tuser.city='2326' OR tuser.city='10196' OR tuser.city='757' OR tuser.city='10515' OR tuser.city='1059' OR tuser.city='31' OR tuser.city='822' OR tuser.city='823' OR tuser.city='45' OR tuser.city='821' OR tuser.city='112' OR tuser.city='129' OR tuser.city='848' OR tuser.city='142' OR tuser.city='151' OR tuser.city='156' OR tuser.city='847' OR tuser.city='849' OR tuser.city='158' OR tuser.city='846' OR tuser.city='174' OR tuser.city='839' OR tuser.city='10640' OR tuser.city='833' OR tuser.city='835' OR tuser.city='190' OR tuser.city='838' OR tuser.city='840' OR tuser.city='2825' OR tuser.city='37' OR tuser.city='48' OR tuser.city='864' OR tuser.city='1546' OR tuser.city='111' OR tuser.city='139' OR tuser.city='2113' OR tuser.city='2144' OR tuser.city='10506' OR tuser.city='865' OR tuser.city='10631' OR tuser.city='916' OR tuser.city='33' OR tuser.city='1113' OR tuser.city='10630' OR tuser.city='1293' OR tuser.city='10649' OR tuser.city='1313' OR tuser.city='1351' OR tuser.city='109' OR tuser.city='110' OR tuser.city='118' OR tuser.city='132' OR tuser.city='10650' OR tuser.city='178' OR tuser.city='10207' OR tuser.city='10208' OR tuser.city='10507' OR tuser.city='202' OR tuser.city='64' OR tuser.city='67' OR tuser.city='82' OR tuser.city='1820' OR tuser.city='1827' OR tuser.city='999' OR tuser.city='870' OR tuser.city='141' OR tuser.city='155' OR tuser.city='162' OR tuser.city='10209' OR tuser.city='184' OR tuser.city='193' OR tuser.city='194' OR tuser.city='915' OR tuser.city='196' OR tuser.city='211' OR tuser.city='73' OR tuser.city='1651' OR tuser.city='2179' OR tuser.city='901' OR tuser.city='182' OR tuser.city='10248' ) and (((tUserLocationPref.CountryId=5 and tUserLocationPref.StateId=0 and tUserLocationPref.CityId=0)) OR ((tUserLocationPref.CountryId=8 and tUserLocationPref.StateId=0 and tUserLocationPref.CityId=0)) OR ((tUserLocationPref.CountryId=7 and tUserLocationPref.StateId=0 and tUserLocationPref.CityId=0)) OR ((tUserLocationPref.CountryId=6 and tUserLocationPref.StateId=0 and tUserLocationPref.CityId=0)) OR ((tUserLocationPref.CountryId=4 and tUserLocationPref.StateId=0 and tUserLocationPref.CityId=0)) ) and  (tUserPref.submitDate>= '2013-10-07') and tuser.usergroup!='sums' and tuser.usergroup!='enterprise' and tuser.usergroup!='cms'";
	    $query = $dbHandle->query($sql);
	    $results = $query->result_array();
	    $userids = array();

	    foreach($results as $result) {
		    $userids[] = $result['userid'];
	    }

	    $sql = "select distinct UserId from LDBLeadViewCount where UserId in (".implode(',',$userids).") and ViewCount > 0";
	    $query = $dbHandle->query($sql);
	    $rows = $query->result_array();
	    $finaluserids = array();

	    foreach($rows as $result) {
		    $finaluserids[] = $result['UserId'];
	    }
	    
	    $userIdList = implode(',',$finaluserids);
	    $csv = $this->_getCSVForLeads($userIdList,'studyabroad');
	    echo $csv;
    }
	
	public function downloadIIPMLeads()
	{ return;
		$this->dbLibObj = DbLibCommon::getInstance('User');
		$dbHandle = $this->_loadDatabaseHandle();
		
		$this->load->library('LDB_Client');
		
		$startDate = date('Y-m-d H:i:s',strtotime('-12 hours'));
		$endDate = date('Y-m-d H:i:s');
		
		$leadExports = array(
			'MBA' => array(
				'sql' => "select distinct(tuser.userid) from tCourseSpecializationMapping,tUserPref,tuser,tuserflag where
						    tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse
						    and tCourseSpecializationMapping.Status='live'
						    and tUserPref.UserId=tuser.userid
						    and tUserPref.Status='live'
						    and tuserflag.isTestUser='NO'
						    and tuserflag.hardbounce='0'
						    and tuserflag.ownershipchallenged='0'
						    and tuserflag.abused='0'
						    and tuserflag.softbounce='0'
						    and tUserPref.UserId=tuserflag.userId
						    and tuserflag.mobileverified='1'
						    and tCourseSpecializationMapping.CourseName='Full Time MBA/PGDM'
						    and tCourseSpecializationMapping.categoryId='3'
						    and tUserPref.submitDate >= ? 
						    and tUserPref.submitDate < ? 
						    and tuser.usergroup!='sums'
						    and tuser.usergroup!='enterprise'
						    and tuser.usergroup!='cms'",
				'subject' => "Shiksha ~NUMLEADS~ MBA Leads (Not Specific to IIPM) - ".date('jS M Y')
			),
			'BBA' => array(
				'sql' => "select distinct(tuser.userid) from tCourseSpecializationMapping,tUserPref,tuser,tuserflag where
						    tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse
						    and tCourseSpecializationMapping.Status='live'
						    and tUserPref.UserId=tuser.userid
						    and tUserPref.Status='live'
						    and tuserflag.isTestUser='NO'
						    and tuserflag.hardbounce='0'
						    and tuserflag.ownershipchallenged='0'
						    and tuserflag.abused='0'
						    and tuserflag.softbounce='0'
						    and tUserPref.UserId=tuserflag.userId
						    and tuserflag.mobileverified='1'
						    and tCourseSpecializationMapping.CourseName='BBA/BMS/BBM/BBS'
						    and tCourseSpecializationMapping.categoryId='3'
						    and tUserPref.submitDate >= ?  
						    and tUserPref.submitDate < ? 
						    and tuser.usergroup!='sums'
						    and tuser.usergroup!='enterprise'
						    and tuser.usergroup!='cms'",
				'subject' => "Shiksha ~NUMLEADS~ BBA Leads (Not Specific to IIPM) - ".date('jS M Y')
			)
		);
		foreach($leadExports as $course_name=>$leadExportData) {
			$sql = $leadExportData['sql'];
			$query = $dbHandle->query($sql, array($startDate, $endDate));
			$results = $query->result_array();
			$userids = array();
		       	
			foreach($results as $result) {
				$userids[] = $result['userid'];
			}
			$userIdList = '';
			if(count($userids) > 0) {
				$userIdList = implode(',',$userids);
				$csv = $this->_getCSVForLeads($userIdList,'national');

				$to = 'varun.jindal@iipm.edu,shikha.ghosh@planmanconsulting.com,iipm.shiksha@gmail.com';
				$name = "";
				$from = "leads@shiksha.com";
				$cc = "mahesh.mahajan@shiksha.com,mahajan.mahesh83@gmail.com";
				$filename = "Leads.csv";
				$subject = str_replace('~NUMLEADS~',count($userids),$leadExportData['subject']);
                $this->ldb_client->sendCSVMail($csv,$name,$from,$to,$cc,$filename,$subject);			
			}
			$script_tracking_data = array();
			$script_tracking_data['script_name'] = 'IIPMLeads-'.$course_name;
			$script_tracking_data['user_ids'] = $userIdList;
			$script_tracking_data['users_count'] = count($userids);
			$script_tracking_data['porting_type'] = 'email';
			$script_tracking_data['data_type'] = 'lead';
			$this->load->model('LDB/leaddashboardmodel');				
			$this->leaddashboardmodel->manual_script_tracking($script_tracking_data);
		}
		echo 'SCRIPT FINISH';
	}
	
	function downloadYoungBuzzLeads()
	{ return;
		$this->dbLibObj = DbLibCommon::getInstance('User');
		$dbHandle = $this->_loadDatabaseHandle();
		$this->load->library('LDB_Client');
		
		$genieIds = array(347, 371, 6129, 9490);
		$startDate = date('Y-m-d',strtotime('-1 day'));
		$endDate = date('Y-m-d');

		$sql = "select distinct(leadid) from SALeadMatchingLog WHERE searchAgentid in (".implode(',',$genieIds).") AND matchingTime > ? AND matchingTime < ?";

		$query = $dbHandle->query($sql, array($startDate, $endDate));
		$results = $query->result_array();
		$userids = array();

		foreach($results as $result) {
			$userids[] = $result['leadid'];
		}
		
		if(count($userids) > 0) {
			$userIdList = implode(',',$userids);
			$csv = $this->_getCSVForLeads($userIdList,'national');
			
			$to = 'yb@youngbuzz.com';
			$name = "";
			$from = "leads@shiksha.com";
			//$cc = "abhishek.jain@naukri.com";
			$filename = "Leads.csv";
			$subject = str_replace('~NUMLEADS~',count($userids),$leadExportData['subject']);
			$this->ldb_client->sendCSVMail($csv,$name,$from,$to,$cc,$filename,$subject);
		}
	}
	
	public function getCommonCSVForLeads($userIds, $csvType, $leads_data=array())
	{
	    return $this->_getCSVForLeads($userIds, $csvType, $leads_data);
	}
	
	private function _getCSVForLeads($userIdList, $csvType, $leads_data=array())
	{
		$this->load->library('LDB_Client');
		$ldbObj = new LDB_client();
        $leads = array();
        if(empty($leads_data)){
            if(is_array($userIdList)) {
    			$UserDetailsArray = $ldbObj->sgetUserDetails(1, implode(",",$userIdList));
            } else {
                $UserDetailsArray = $ldbObj->sgetUserDetails(1, $userIdList);
            }
		    $UserDataArray = $this->createUserDataArray(json_decode($UserDetailsArray, true));
            $leads = $UserDataArray;
        } else {
            foreach ($leads_data as $key => $userData) {
                $leads[$userData['leadid']] = $userData['UserData'];
            }
        }
        
		$ColumnList = $this->getColumnList($csvType);
       
		$csv = '';
		foreach ($ColumnList as $ColumnName) {
			$csv .= '"' . $ColumnName . '",';
		}
		$csv .= "\n";
		foreach ($leads as $lead) {
			foreach ($ColumnList as $ColumnName) {
				$csv .= '"' . $lead[$ColumnName] . '",';
			}
			$csv .= "\n";
		}
		return $csv;
	}
	
	public function getCommonCSVForResponses($userIds, $responses)
	{
      
		$this->load->library('LDB_Client');
        $ldbObj = new LDB_client();
		
		// $courseRepository = $listingBuilder->getCourseRepository();
        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder    = new CourseBuilder();
        $courseRepository = $courseBuilder->getCourseRepository();

        $this->load->builder('ListingBuilder','listing');
        $listingBuilder            = new ListingBuilder;
		$abroadCourseRepository    = $listingBuilder->getAbroadCourseRepository();
		$abroadInstituteRepository = $listingBuilder->getAbroadInstituteRepository();
		$universityRepository      = $listingBuilder->getUniversityRepository();
		
		$userIdList = array_unique($userIds);
		
		foreach($userIdList as $userId){
		    $UserDetailsArray[$userId] = $ldbObj->sgetUserDetails(1, $userId);
		}
		
		$course_ids = array();
		$institute_ids = array();
		$university_ids = array();
		$listing_ids = array();
		$abroadCoursesCount = array();
		foreach($userIdList as $userId){
		    $abroadCoursesCount[$userId] = 0;
		}
		
		foreach($responses as $tempId => $data){

		    if($data['listing_type'] == "course"){

    			$course_ids[] = $data['listing_type_id'];
    			$isAbroadCourse = $this->coursemodel->isStudyAboradListing($data['listing_type_id'], 'course');
    			if($isAbroadCourse) {
    			    $abroadCoursesCount[$data['userid']]++;
    			}

		    }

		}
		$extraFlag = array();
		    
		foreach($UserDetailsArray as $userId => $UserDetails){

		    if(count($abroadCoursesCount[$userId])>0) {
			    $extraFlag[$userId] = 'studyabroad';
		    }

		    $UserDataArray[$userId] = $this->createUserDataArrayForResponses(json_decode($UserDetails, true),$extraFlag[$userId]);

		}

		$listingData = array();
		$listingCountry = array();
		$listingLocation = array();
		
		$abroadCourseIds = array();
		if(count($course_ids)>0) {

		    foreach($course_ids as $course_id){
			
    			$isAbroadCourse = $this->coursemodel->isStudyAboradListing($course_id, 'course');
    			
    			if($isAbroadCourse) {

    			    if(!in_array($course_id, $abroadCourseIds)) {
    				    $abroadCourseIds[] = $course_id;
    			    }
    			    $coursesData[$course_id]          = $abroadCourseRepository->find($course_id);
    			    $universityId[$course_id]         = $coursesData[$course_id]->getUniversityId();
    			    $courseUniversityName[$course_id] = html_entity_decode($coursesData[$course_id]->getUniversityName());
    			    $courseName[$course_id]           = $coursesData[$course_id]->getName();
    			    $universityObj                    = $universityRepository->find($universityId[$course_id]);
    			    $cityId                           = $universityObj->getLocation()->getCity()->getId();
    			    $cityName                         = $universityObj->getLocation()->getCity()->getName();
    			    $countryId                        = $universityObj->getLocation()->getCity()->getCountryId();
    			    $countryName                      = $universityObj->getLocation()->getCountry()->getName();
    			    
    			    $listingCountry[$course_id]       = $countryName;
    			    $listingLocation[$course_id]      = 'abroad';

    			} else {
    			    
                    $coursesData[$course_id]     = $courseRepository->find($course_id,array('location'));
    			    $listingEntity               = $coursesData[$course_id];
                    $courseLocations             = $coursesData[$course_id]->getLocations();

    			    $listingCountry[$course_id]  = 'India';
    			    $listingLocation[$course_id] = 'India'; 

    			}

		    }

		    $listing_ids = $course_ids;
		}
		
		$totalResponses = array();
		$i=0;

        foreach($responses as $tempId => $data){

            $response_usr_id = $data['userid'];
            
            if(in_array($response_usr_id, array_keys($UserDataArray))) {

    		    $resultResponse = array();
    		    $temp_data  = $UserDataArray[$response_usr_id];
    		    $submitDate = ($responses[$tempId]['submit_date']);

    			$resultResponse['Name']       = ($temp_data[0]['Name']) ? ($temp_data[0]['Name']) : ' ' ;
    		    $resultResponse['First Name'] = ($temp_data[0]['First Name']) ? ($temp_data[0]['First Name']) : ' ' ;
                $resultResponse['Last Name']  = ($temp_data[0]['Last Name']) ? ($temp_data[0]['Last Name']) : ' ' ;
    		    $resultResponse['Gender']     = ($temp_data[0]['Gender']) ? ($temp_data[0]['Gender']) : ' ';
    		    $resultResponse['Age']        = ($temp_data[0]['Age']) ? ($temp_data[0]['Age']) : ' ';
                $resultResponse['ISD Code']   = $temp_data[0]['ISD Code'];

    		    if($data['listing_type'] == "course"){

        			if(in_array($data['listing_type_id'], $abroadCourseIds)) {

        			    $resultResponse['University Name'] = $courseUniversityName[$data['listing_type_id']];

        			} else {

        			    $resultResponse['Institute Name'] = $coursesData[$data['listing_type_id']]->getInstituteName();
                        $locationObj                      = !empty($courseLocations[$data['instituteLocationId']]) ? $courseLocations[$data['instituteLocationId']] : $coursesData[$course_id]->getMainLocation();
                        $resultResponse['City']           = $locationObj->getCityName();
                        $resultResponse['Locality']       = $locationObj->getLocalityName();

        			}

        			$resultResponse['Response to'] = $coursesData[$data['listing_type_id']]->getName();
        			$resultResponse['Location']    = $coursesData[$data['listing_type_id']]->getMainLocation();

    		    }

    		    $resultResponse['Response Date']        = date("d M Y",strtotime($submitDate));
    		    $resultResponse['Source']               = ($responses[$tempId]['action']) ? ($responses[$tempId]['action']) : ' '; 
    		    $resultResponse['Source of Funding']    = ($temp_data[0]['Source of Funding']) ? ($temp_data[0]['Source of Funding']) : ' ';
    		    $resultResponse['Email']                = ($temp_data[0]['Email']) ? ($temp_data[0]['Email']) : ' ';
    		    $resultResponse['Mobile']               = ($temp_data[0]['Mobile']) ? ($temp_data[0]['Mobile']) : ' ';
    		    $resultResponse['IsNDNC']               = ($temp_data[0]['Is in NDNC list']) ? ($temp_data[0]['Is in NDNC list']) : ' ';
    		    $resultResponse['Current Location']     = ($temp_data[0]['Current Location']) ? ($temp_data[0]['Current Location']) : ' ';
    		    $resultResponse['Current Locality']     = ($temp_data[0]['Current Locality']) ? ($temp_data[0]['Current Locality']) : ' ';
    		    $resultResponse['Valid Passport']       = ($temp_data[0]['Valid Passport']) ? ($temp_data[0]['Valid Passport']) : ' ';
    		    $resultResponse['Budget']               = ($temp_data[0]['Budget']) ? ($temp_data[0]['Budget']) : ' ';
    		    $resultResponse['Education Interest']   = ($temp_data[0]['Field of Interest']) ? ($temp_data[0]['Field of Interest']) : ' ';
    		    $resultResponse['Specialization']       = ($temp_data[0]['Desired Specialization']) ? ($temp_data[0]['Desired Specialization']) : ' ';
    		    $resultResponse['Mode']                 = ($temp_data[0]['Mode']) ? ($temp_data[0]['Mode']) : ' ';
    		    $resultResponse['Preferred Locations']  = ($temp_data[0]['Preferred Locations']) ? ($temp_data[0]['Preferred Locations']) : ' ';
    		    $resultResponse['Preferred Localities'] = ($temp_data[0]['Preferred Localities']) ? ($temp_data[0]['Preferred Localities']) : ' ';
    		    $resultResponse['Degree Preference']    = ($temp_data[0]['Degree Preference']) ? ($temp_data[0]['Degree Preference']) : ' ';
    		    $resultResponse['Plan to Start']        = ($temp_data[0]['Plan to start']) ? ($temp_data[0]['Plan to start']) : ' ';
    		    $resultResponse['Creation Date']        = ($temp_data[0]['Created On']) ? date("d M Y",strtotime($temp_data[0]['Created On'])) : ' ';
    		    $resultResponse['Work Experience']      = ($temp_data[0]['Work Experience']) ? ($temp_data[0]['Work Experience']) : ' ';
    		    $resultResponse['XII Details']          = $temp_data[0]['Std XII Stream'].' '.$temp_data[0]['Std XII Marks'].' '.$temp_data[0]['Std XII Year'];
    		    $resultResponse['XII Year']             = ($temp_data[0]['Std XII Year']) ? date("Y", strtotime($temp_data[0]['Std XII Year'])) : ' ';
    		    $resultResponse['UG Details']           = $temp_data[0]['Graduation Course'].' '.$temp_data[0]['Graduation Marks'].' '.$temp_data[0]['Graduation Year'].' '.$temp_data[0]['Graduation Month'];
    		    $resultResponse['Graduation Year']      = ($temp_data[0]['Graduation Year']) ? date("Y", strtotime($temp_data[0]['Graduation Year'])) : '';

    		    if(!$temp_data[0]['Exams Taken']){
        			$j=1;
        			while($temp_data[0]['Exams Taken '. $j]){
        			    $examTakenAbroad = $temp_data[0]['Exams Taken '. $j].' ';
        			    $j++;
        			}
    		    }

    		    $resultResponse['Exams Taken']            = ($temp_data[0]['Exams Taken']) ? ($temp_data[0]['Exams Taken']) : $examTakenAbroad;
    		    $resultResponse['Preferred Country']      = ($temp_data[0]['Desired Countries']) ? ($temp_data[0]['Desired Countries']) : ' ';
    		    $resultResponse['Preferred Time of Call'] = ($temp_data[0]['Preferred Time to call']) ? ($temp_data[0]['Preferred Time to call']) : ' ';
    		    $resultResponse['Desired Course Level']   = ($temp_data[0]['Desired Course Level']) ? ($temp_data[0]['Desired Course Level']) : ' ';

    		    $totalResponses[] = $resultResponse;
    		    $i++;

            }

	    }
	    
	    if(count($abroadCourseIds) > 0) {
		    $location = 'abroad';
        } else {
		    $location = 'India';
        }
	    
	    if($location == "India"){

    		$csvFields = array(
    			
    			'firstname'      => 'First Name',
                'lastname'       => 'Last Name',
                'name'           => 'Full Name',
                'submit_date'    => 'Response Date',
                'email'          => 'Email',
                'ISD Code'       => 'ISD Code',
                'mobile'         => 'Mobile',
                'isNDNC'         => 'NDNC Status',
                'institute_name' => 'Institute/University Name',
                'listing_title'  => 'Response to',
    			'exams_taken'    => 'Exams Taken',
                'CurrentCity'    => 'Current City',
                'localityName'   => 'Current Locality',
                'action'         => 'Source',
                'city'           => 'City',
                'locality'       => 'Locality'

    		);

	    } else {

    		$csvFields = array(

    			'firstname'            => 'First Name',
                'lastname'             => 'Last Name',
                'name'                 => 'Full Name',
                'submit_date'          => 'Response Date',
                'email'                => 'Email',
                'ISD Code'             => 'ISD Code',
                'mobile'               => 'Mobile',
                'isNDNC'               => 'NDNC Status',
                'university_name'       => 'University Name',
                'listing_title'        => 'Response to',
                'field_of_interest'    => 'Field of Interest',
                'desired_course_level' => 'Desired Course Level',
                'Specialization'       => 'Specialization',
                'exams_taken'          => 'Exams Taken',
    			'preferred_country'    => 'Preferred Country',
                'CurrentCity'          => 'Current Location',
                'plan_to_start'        => 'Plan to Start',
                'student_passport'     => 'Student Passport',
                'action'               => 'Source'

			);

	    }
	    
	    $csv = '';
	    foreach($csvFields as $csvField) {
		    $csv .= '"'. $csvField .'",';
	    }

	    for($i=0; $i<count($totalResponses); $i++) {
		
    		$csv .= "\n";
    		foreach ($csvFields as $csvFieldId => $csvField){

    		    $val = '';
    		    switch($csvFieldId) {

        			case 'institute_name': 
                        $val = $totalResponses[$i]['Institute Name']; 
                        break;

        			case 'university_name': 
                        $val = $totalResponses[$i]['University Name']; 
                        break;
        			
                    case 'email': 
                        $val = $totalResponses[$i]['Email']; 
                        break;

        			case 'mobile': 
                        $val = $totalResponses[$i]['Mobile']; 
                        break;

        			case 'name': 
                        $val = $totalResponses[$i]['Name']; 
                        break;

        			case 'firstname': 
                        $val = $totalResponses[$i]['First Name']; 
                        break;

                    case 'lastname': 
                        $val = $totalResponses[$i]['Last Name']; 
                        break;

        			case 'listing_title': 
                        $val = $totalResponses[$i]['Response to']; 
                        break;

        			case 'submit_date': 
                        $val = $totalResponses[$i]['Response Date']; 
                        break;

        			case 'action': 
                        $val = $totalResponses[$i]['Source']; 
                        break;

        			case 'CurrentCity': 
                        $val = $totalResponses[$i]['Current Location']; 
                        break;

        			case 'localityName': 
                        $val = $totalResponses[$i]['Current Locality']; 
                        break;

        			case 'age': 
                        $val = $totalResponses[$i]['Age']; 
                        break;

        			case 'isNDNC': 
                        $val = $totalResponses[$i]['IsNDNC']; 
                        break;

        			case 'field_of_interest': 
                        $val = $totalResponses[$i]['Education Interest'];
                        break;

        			case 'Specialization': 
                        $val = $totalResponses[$i]['Specialization'];
                        break;

        			case 'plan_to_start': 
                        $val = $totalResponses[$i]['Plan to Start'];
                        break;

        			case 'exams_taken': 
                        $val = $totalResponses[$i]['Exams Taken'];
                        break;

        			case 'preferred_country': 
                        $val = $totalResponses[$i]['Preferred Country'];
                        break;

        			case 'desired_course_level': 
                        $val = $totalResponses[$i]['Desired Course Level'];
                        break;

        			case 'city': 
                        $val = $totalResponses[$i]['City'];
                        break;

        			case 'locality': 
                        $val = $totalResponses[$i]['Locality'];
                        break;

        			case 'student_passport': 
                        $val = $totalResponses[$i]['Valid Passport']; 
                        break;

                    case 'ISD Code': 
                        $val = $totalResponses[$i]['ISD Code']; 
                        break;

    		    }
    		    $csv .= '"'.$val.'",';
                
    		}

	    }

	    $returnArray = array();
	    $returnArray['Total Responses'] = $totalResponses;
	    $returnArray['CSV'] = $csv;

	    if($export) {
    		header("Content-type: text/x-csv");
    		header("Content-Disposition: attachment; filename=". $filename .".csv");
    		echo $returnArray;
	    } else {
		    return $returnArray;
	    }

	}
	
	private function _getGroupForAcourse($course_name) { 
                $ldbObj = new LDB_client(); 
                if($course_name=='Study Abroad') { 
                        $result = $ldbObj->getGroupForAcourse(357,'course'); 
                } else if($course_name=='Full Time MBA/PGDM') { 
                        $result = $ldbObj->getGroupForAcourse(2,'course'); 
                } else if($course_name=='Distance/Correspondence MBA') { 
                        $result = $ldbObj->getGroupForAcourse(24,'course'); 
                } else if($course_name=='Part-time MBA') { 
                        $result = $ldbObj->getGroupForAcourse(710,'course'); 
                } else if($course_name=='Certifications') { 
                        $result = $ldbObj->getGroupForAcourse(712,'course'); 
                } else if($course_name=='') { 
                        $result = $ldbObj->getGroupForAcourse(711,'course'); 
                } else if($course_name=='IT Courses') { 
                        $result = $ldbObj->getGroupForAcourse(162,'course'); 
                } else if($course_name=='IT Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(49,'course'); 
                }else if($course_name=='Distance BCA/MCA') { 
                        $result = $ldbObj->getGroupForAcourse(188,'course'); 
                }else if($course_name=='Animation, Multimedia Courses') { 
                        $result = $ldbObj->getGroupForAcourse(213,'course'); 
                }else if($course_name=='Animation, Multimedia Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(190,'course'); 
                }else if($course_name=='Hospitality,Tourism Courses') { 
                        $result = $ldbObj->getGroupForAcourse(291,'course'); 
                }else if($course_name=='Hospitality,Tourism Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(257,'course'); 
                }else if($course_name=='Science & Engineering') { 
                        $result = $ldbObj->getGroupForAcourse(52,'course'); 
                }else if($course_name=='BBA') { 
                        $result = $ldbObj->getGroupForAcourse(381,'course'); 
                }else if($course_name=='BBA/BBM' || $course_name =='BBA/BBM/BBS' || $course_name == 'BBA/BMS/BBM/BBS') { 
                        $result = $ldbObj->getGroupForAcourse(781,'course'); 
                }else if($course_name=='Test Prep') { 
                        $result = $ldbObj->getGroupForAcourse(465,'testprep'); 
                }else if($course_name=='Medicine, Health & Beauty Courses') { 
                        $result = $ldbObj->getGroupForAcourse(396,'course'); 
                }else if($course_name=='Medicine, Health & Beauty Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(384,'course'); 
                }else if($course_name=='Design Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(400,'course'); 
                }else if($course_name=='Media, FIlms & Mass Communication Courses') { 
                        $result = $ldbObj->getGroupForAcourse(447,'course'); 
                }else if($course_name=='Media, FIlms & Mass Communication Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(422,'course'); 
                }else if($course_name=='BBA/BBM' || $course_name =='BBA/BBM/BBS' || $course_name == 'BBA/BMS/BBM/BBS') { 
                        $result = $ldbObj->getGroupForAcourse(781,'course'); 
                }else if($course_name=='Distance/Correspondence MBA') { 
                        $result = $ldbObj->getGroupForAcourse(24,'course'); 
                }else if($course_name=='Executive MBA') { 
                        $result = $ldbObj->getGroupForAcourse(13,'course'); 
                }else if($course_name=='Integrated MBA Courses') { 
                        $result = $ldbObj->getGroupForAcourse(782,'course'); 
                }else if($course_name=='Management Certifications') { 
                        $result = $ldbObj->getGroupForAcourse(712,'course'); 
                }else if($course_name=='Online MBA') { 
                        $result = $ldbObj->getGroupForAcourse(711,'course'); 
                }else if($course_name=='Other Management Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(1305,'course'); 
                }else if($course_name=='Part-time MBA') { 
                        $result = $ldbObj->getGroupForAcourse(710,'course'); 
                }else if($course_name=='Aircraft Maintenance Engineering') { 
                        $result = $ldbObj->getGroupForAcourse(352,'course'); 
                }else if($course_name=='Advanced Technical Courses') { 
                        $result = $ldbObj->getGroupForAcourse(352,'course'); 
                }else if($course_name=='B.E./B.Tech') { 
                        $result = $ldbObj->getGroupForAcourse(52,'course'); 
                }else if($course_name=='B.Sc') { 
                        $result = $ldbObj->getGroupForAcourse(645,'course'); 
                }else if($course_name=='Distance B.Sc') { 
                        $result = $ldbObj->getGroupForAcourse(655,'course'); 
                }else if($course_name=='Distance B.Tech') { 
                        $result = $ldbObj->getGroupForAcourse(1371,'course'); 
                }else if($course_name=='Distance M.Sc') { 
                        $result = $ldbObj->getGroupForAcourse(660,'course'); 
                }else if($course_name=='Engineering Diploma') { 
                        $result = $ldbObj->getGroupForAcourse(610,'course'); 
                }else if($course_name=='Engineering Distance Diploma') { 
                        $result = $ldbObj->getGroupForAcourse(1369,'course'); 
                }else if($course_name=='M.E./M.Tech') { 
                        $result = $ldbObj->getGroupForAcourse(53,'course'); 
                }else if($course_name=='M.Sc') { 
                        $result = $ldbObj->getGroupForAcourse(650,'course'); 
                }else if($course_name=='Science & Engineering Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(356,'course'); 
                }else if($course_name=='Science & Engineering PHD') { 
                        $result = $ldbObj->getGroupForAcourse(1378,'course'); 
                }else if($course_name=='Distance BCA/MCA') { 
                        $result = $ldbObj->getGroupForAcourse(188,'course'); 
                }else if($course_name=='IT Courses') { 
                        $result = $ldbObj->getGroupForAcourse(56,'course'); 
                }else if($course_name=='IT Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(47,'course'); 
                }else if($course_name=='Animation Courses') { 
                        $result = $ldbObj->getGroupForAcourse(212,'course'); 
                }else if($course_name=='Animation Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(190,'course'); 
                }else if($course_name=='Hospitality, Aviation & Tourism Courses') { 
                        $result = $ldbObj->getGroupForAcourse(291,'course'); 
                }else if($course_name=='Hospitality, Aviation & Tourism Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(256,'course'); 
                }else if($course_name=='Media, Films & Mass Communication Courses') { 
                        $result = $ldbObj->getGroupForAcourse(447,'course'); 
                }else if($course_name=='Media, Films & Mass Communication Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(420,'course'); 
                }else if($course_name=='Test Prep (International Exams)') { 
                        $result = $ldbObj->getGroupForAcourse(310,'testprep'); 
                }else if($course_name=='Arts, Law, Languages and Teaching Courses') { 
                        $result = $ldbObj->getGroupForAcourse(494,'course'); 
                }else if($course_name=='Arts, Law, Languages and Teaching Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(459,'course'); 
                }else if($course_name=='Banking & Finance Courses') { 
                        $result = $ldbObj->getGroupForAcourse(516,'course'); 
                }else if($course_name=='Banking & Finance Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(506,'course'); 
                }else if($course_name=='Design Courses') { 
                        $result = $ldbObj->getGroupForAcourse(549,'course'); 
                }else if($course_name=='Design Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(400,'course'); 
                }else if($course_name=='Distance B.A./M.A.') { 
                        $result = $ldbObj->getGroupForAcourse(456,'course'); 
                }else if($course_name=='Foreign Language Courses') { 
                        $result = $ldbObj->getGroupForAcourse(496,'course'); 
                }else if($course_name=='Medicine, Beauty & Health Care Courses') { 
                        $result = $ldbObj->getGroupForAcourse(396,'course'); 
                }else if($course_name=='Medicine, Beauty & Health Care Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(383,'course'); 
                }else if($course_name=='Retail Degrees') { 
                        $result = $ldbObj->getGroupForAcourse(801,'course'); 
                }else { 
                        $result = $ldbObj->getGroupForAcourse(2,'course'); 
                } 
                 
                return $result['0']['groupId']; 
        } 
	
	public function verifiedLeadPilotData($startDate, $endData) {
		$this->dbLibObj = DbLibCommon::getInstance('User');
		$dbHandle = $this->_loadDatabaseHandle();
                
		$sql = "SELECT DISTINCT tuser.userid, tuser.usercreationDate, tUserLocationPref.cityid, tUserLocationPref.stateid, tuser.city
			FROM tCourseSpecializationMapping,tUserLocationPref,tuser,tUserPref,tuserflag
			WHERE tCourseSpecializationMapping.SpecializationId = tUserPref.DesiredCourse 
			AND tCourseSpecializationMapping.Status = 'live'
			AND tUserPref.UserId = tuser.userid 
			AND tUserPref.Status = 'live'
			AND tuserflag.isTestUser = 'NO'
			AND tuserflag.hardbounce = '0'
			AND tuserflag.ownershipchallenged = '0'
			AND tuserflag.abused = '0'
			AND tuserflag.softbounce = '0'
			AND tUserPref.UserId = tuserflag.userId
			AND tuserflag.mobileverified = '1'
			AND tUserLocationPref.UserId = tuser.userid
			AND tUserLocationPref.PrefId = tUserPref.PrefId
			AND tUserLocationPref.Status = 'live'
			AND tCourseSpecializationMapping.CourseName = 'Full Time MBA/PGDM'
			AND tCourseSpecializationMapping.categoryId = '3'
			AND tUserLocationPref.CountryId = 2
			AND tuser.usergroup != 'sums'
			AND tuser.usergroup != 'enterprise'
			AND tuser.usergroup != 'cms'
			AND DATE(tuser.usercreationDate) BETWEEN ? AND ? 
			ORDER BY tuser.userid DESC";
                $query = $dbHandle->query($sql, array($startDate, $endDate));
		$results = $query->result_array();
		$userData = array();
		$cities = array();
		$cityStateMapping = array();
		
		foreach($results as $result) {
			$user = isset($userData[$result['userid']]) ? $userData[$result['userid']] : array();
			
			if(empty($user)) {
				$user['CreatedDate'] = $result['usercreationDate'];
				$user['ResidentCity'] = $result['city'];
			}
			
			$user['PreferredLocations'][] = array('city' => $result['cityid'], 'state' => $result['stateid']);
			
			$userData[$result['userid']] = $user;
			
			if(is_numeric($result['city'])) {
				$cities[] = $result['city'];
			}
		}
		
		$sql = "SELECT city_id, city_name FROM countryCityTable WHERE city_id IN ( ? )";
                $query = $dbHandle->query($sql, array($cities));
		$results = $query->result_array();
		foreach($results as $result){
			$cityStateMapping[$result['city_id']]['name'] = $result['city_name'];
		}
		
		$sql = "SELECT countryCityTable.city_id, stateTable.state_name FROM countryCityTable, stateTable WHERE countryCityTable.state_id = stateTable.state_id AND countryCityTable.city_id IN ( ? )";
                $query = $dbHandle->query($sql, array($cities));
		$results = $query->result_array();
		foreach($results as $result){
			$cityStateMapping[$result['city_id']]['state_name'] = $result['state_name'];
		}
		
		echo '<table border="1"><tr><th>User ID</th><th>Pune</th><th>Bangalore</th><th>Mumbai</th><th>Other City</th><th>Lead Created On</th><th>Resident City</th><th>Resident State</th></tr>';
		
		foreach($userData as $userId => $user) {
			$isPune = 'no';
			$isBangalore = 'no';
			$isMumbai = 'no';
			$otherCity = 'no';
			foreach($user['PreferredLocations'] as $location) {
				if($location['city'] == 174) {
					$isPune = 'yes';
				}
				if($location['city'] == 278 || $location['city'] == 10596) {
					$isBangalore = 'yes';
				}
				if($location['city'] == 151 || $location['city'] == 10224) {
					$isMumbai = 'yes';
				}
				if($location['city'] > 0 && $isPune == 'no' && $isBangalore == 'no' && $isMumbai == 'no') {
				    $otherCity = 'yes';
				}
			}
			
			if(is_numeric($user['ResidentCity'])) {
				$residentCity = $cityStateMapping[$user['ResidentCity']]['name'];
				$residentState = $cityStateMapping[$user['ResidentCity']]['state_name'];
			}
			else {
				$residentCity = $user['ResidentCity'];
			}
			
			echo '<tr><td>'.$userId.'</td><td>'.$isPune.'</td><td>'.$isBangalore.'</td><td>'.$isMumbai.'</td><td>'.$otherCity.'</td><td>'.$user['CreatedDate'].'</td><td>'.$residentCity.'</td><td>'.$residentState.'</td></tr>';
		}
		
		echo '</table>';
	}
	
	public function formSubmitMR() {
		ini_set('memory_limit','300M');
	    $postArray = array();
	    $displayArray = array();
	    $dataArrayMR = array();
	    
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new \LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		
	    //matched response search ldb course
	    $displayArray['DesiredCourse'] = isset($_POST['desiredCourse']) ? $_POST['desiredCourse'] : '';
	    $postArray['actual_course_name'] = $_POST['actual_course_name'];
	    
	    //get course ids for matching
	    $courseIds = array();
	    $courseNames = array();
	    $displayArray['matchedCourses'] = '';
	    if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
		$courses = $this->input->post('course_id');
		foreach($courses as $course) {
		    $courseData = explode('|',$course);
		    if($course > 0) {
			$courseIds[] = $courseData[0];
			$courseNames[$courseData[0]] = $courseData[1];
			$instituteNames[$courseData[0]] = $courseData[2];
		    }
		}
		$dataArrayMR['courses'] = $courseIds;
		$displayArray['matchedCourses'] = $courseNames;
		$displayArray['matchedCoursesInstitute'] = $instituteNames;
	    }
		
	    if(isset($_POST['totalCities'])){
		$totalCurrLocation = $_POST['totalCities'];
		$allCurrLocArray = count($_POST['CurLocArr']);
	    }
	     
	    if(isset($totalCurrLocation) && $allCurrLocArray < $totalCurrLocation ){
		$ALLcoursesSelected = 1;
	    }else{
		$ALLcoursesSelected = 0;
	    }
	    
	    //get current locations
	    if (isset($_POST['CurLocArr']) && !empty($_POST['CurLocArr']) && ($ALLcoursesSelected)){
		$cityObjs = $locationRepository->findMultipleCities($_POST['CurLocArr']);
		foreach($cityObjs as $cityId => $cityObj) {
		    if($cityObj->isVirtualCity()) {
			$citiesByVitualCity = $locationRepository->getCitiesByVirtualCity($cityId);
			foreach($citiesByVitualCity as $city) {
			    $_POST['CurLocArr'][] = $city->getId();
			}
		    }
		}
		
		$postArray['CurrentLocation'] = array_unique($_POST['CurLocArr']);
		if (isset($_POST['hiddenCurrentCity'])) {
		    $displayArray['currentLocation'] = implode(', ', $_POST['hiddenCurrentCity']);
		}
	    }
	    
		/***
		 * Preferred Locations for MR
		 ***/
		if(isset($_POST['MRTotalCities'])) {
			$totalMRLocations = $_POST['MRTotalCities'];
			$numMRLocationsSelected = count($_POST['MRLocArr']);
	    }
	    
		$allMRLocationsSelected = ($numMRLocationsSelected == $totalMRLocations) ? TRUE : FALSE;
		
	    if (isset($_POST['MRLocArr']) && !empty($_POST['MRLocArr']) && !$allMRLocationsSelected) {
			$cityObjs = $locationRepository->findMultipleCities($_POST['MRLocArr']);
			foreach($cityObjs as $cityId => $cityObj) {
				if($cityObj->isVirtualCity()) {
					$citiesByVitualCity = $locationRepository->getCitiesByVirtualCity($cityId);
					foreach($citiesByVitualCity as $city) {
						$_POST['MRLocArr'][] = $city->getId();
					}
				}
			}
		
			$postArray['MRLocation'] = array_unique($_POST['MRLocArr']);
			if (isset($_POST['hiddenMRCity'])) {
				$displayArray['MRLocation'] = implode(', ', $_POST['hiddenMRCity']);
			}
	    }
		
		unset($locationRepository);
		unset($cityObjs);
		
	    //get exams
	    $exams = $_POST['exams'];
	    if(is_array($exams) && count($exams) > 0) {
		$examArray = array();
		$displayArray['examTaken'] = '';
		$examDisplayArray = array();
		
		foreach($exams as $examId) {
		    $examArray[$examId] = array();
		    
		    if($_POST[$examId.'_min_score'] && trim($_POST[$examId.'_min_score']) != 'Min') {
			$examArray[$examId]['min'] = $_POST[$examId.'_min_score'];
		    }
		    if($_POST[$examId.'_max_score'] && trim($_POST[$examId.'_max_score']) != 'Max') {
			$examArray[$examId]['max'] = $_POST[$examId.'_max_score'];
		    }
		    if($_POST[$examId.'_year']) {
			$examArray[$examId]['year'] = $_POST[$examId.'_year'];
		    }
		    
		    $examDisplay = $examId == 'NOEXAM' ? 'No Exam Required' : $_POST[$examId.'_displayname'];
		    
		    if($examArray[$examId]['min'] || $examArray[$examId]['max'] || $examArray[$examId]['year']) {
			$examDisplay .= ': ';
		    }
		    if($examArray[$examId]['min'] || $examArray[$examId]['max']) {
			if($examGrades[$examId]) {
			    $examDisplay .= $examGrades[$examId][$examArray[$examId]['min']]." - ".$examGrades[$examId][$examArray[$examId]['max']];
			}
			else {
			    $examDisplay .= $examArray[$examId]['min']." - ".$examArray[$examId]['max'];
			}
		    }
		    if($examArray[$examId]['year']) {
			if($examId == 'MAT') {
			    $examDisplay .= ' in '.date('M Y',strtotime($examArray[$examId]['year']));
			}
			else {
			    $examDisplay .= ' in '.date('Y',strtotime($examArray[$examId]['year']));    
			}
		    }
		    
		    $examDisplayArray[] = $examDisplay;
		}
		
		$displayArray['examTaken'] = implode(", ",$examDisplayArray);
		$postArray['ExamScore'] = $examArray;
	    }
	    
	    
	    //get graduation start/end year
	    $displayArray['graduationDate'] = '';
	    $displayArray['XIIGraduationDate'] = '';
	    if ($_POST['gradStartYear'] != '' || $_POST['XIIStartYear'] != '') {
		$startYear = $_POST['gradStartYear'] != '' ? $_POST['gradStartYear'] : $_POST['XIIStartYear'];
		$gradStartMonth = '01';
		
		if($_POST['gradStartYear'] != '') {
		    $postArray['GraduationCompletedFrom'] = $startYear . "-" . $gradStartMonth . "-01 00:00:00";
		    $displayArray['graduationDate'] = "After " . $gradStartMonth . "-" . $startYear;
		}
		else if($_POST['XIIStartYear'] != '') {
		    $postArray['XIICompletedFrom'] = $startYear . "-" . $gradStartMonth . "-01 00:00:00";
		    $displayArray['XIIGraduationDate'] = "After " . $gradStartMonth . "-" . $startYear;
		}
	    }
	    
	    if ($_POST['gradEndYear'] != "" || $_POST['XIIEndYear'] != '') {
		$endYear = $_POST['gradEndYear'] != '' ? $_POST['gradEndYear'] : $_POST['XIIEndYear'];
		$gradEndMonth = '12';
		
		$result = strtotime("{$endYear}-{$gradEndMonth}-01");
		$result = strtotime('-1 second', strtotime('+1 month', $result));
		$finaldate = $result . " 23:59:59";
		$finaldate = date("Y-m-d H:i:s", $finaldate);
		
		if($_POST['gradEndYear'] != '') {
		    $postArray['GraduationCompletedTo'] = $finaldate;
		    
		    if ($displayArray['graduationDate'] == "") {
			$displayArray['graduationDate'] = "Before " . date("m-Y", $result);
		    }
		    else {
			$displayArray['graduationDate'] .= " and before " . date("m-Y", $result);
		    }
		}
		else if($_POST['XIIEndYear'] != '') {
		    $postArray['XIICompletedTo'] = $finaldate;
		    
		    if ($displayArray['XIIGraduationDate'] == "") {
			$displayArray['XIIGraduationDate'] = "Before " . date("m-Y", $result);
		    }
		    else {
			$displayArray['XIIGraduationDate'] .= " and before " . date("m-Y", $result);
		    }
		}
	    }
	    
	    
	    //get 12th completion year
	    
	    
	    //get time filter
	    $startDate = null;
	    $endDate = null;
	    if (isset($_POST['timefilter']['from']) && isset($_POST['timefilter']['to']) ) {
		$startDate = $_POST['timefilter']['from'];
		$endDate = $_POST['timefilter']['to'];
		$endTime = '';
		$date = date('Y-m-d');
		
		$displayArray['DateFilterFrom'] = $_POST['timefilter']['from'];
		
		if($startDate > $endDate) {
		    return;
		}
		else if($endDate >= $date) {
		    $endDate = $date;
		    $displayArray['DateFilterTo'] = date('Y-m-d H:i:s', strtotime('-30 min'));
		}
		else {
		    $displayArray['DateFilterTo'] = $endDate.' 23:59:59';
		}
		
		$dataArrayMR['startDate'] = $startDate;
		$dataArrayMR['endDate'] = $endDate;
	    }
	    
	    $postArray['DontShowViewed'] = '1';
	    
	    $this->displayResultsMR($postArray, $displayArray, 0, 50, $dataArrayMR);
	}
    
    function displayResultsMR($inputArray, $displayArray, $start, $rows, $dataArrayMR) {
		
	    $this->init();
        $data['validateuser'] = $this->userStatus;
        $Validity = $this->checkUserValidation();
        $ClientId = $Validity[0]['userid'];
        $data['ClientId'] = $ClientId;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $data['searchTabs'] = array(array('course_name' => 'Full Time MBA/PGDM'));
        $data['prodId'] = 31;
        $data['course_name'] = $inputArray['tab_course_name'];
        $data['actual_course_name'] = $inputArray['actual_course_name'];
	    $data['DontShowViewed'] = $inputArray['DontShowViewed'];
	    $data['flag_manage_page'] = $inputArray['flag_manage_page'];
        $data['displayArray'] = $displayArray;
        $data['inputData'] = base64_encode(json_encode(array_merge(array('course_id' => $dataArrayMR['courses']), $inputArray)));
        $data['displayData'] = base64_encode(json_encode($displayArray));
    	$data['inputDataMR'] = base64_encode(json_encode($dataArrayMR));
    	$data['resultResponse'] = array();
    	$data['responses'] = array();
    	$activateSOLRSearch = true;

        //to exclude credit deduction, when MR is itself a response for client
        $this->load->model('ldbmodel');
        $responses = $this->ldbmodel->getResponsesForClient($ClientId);
        $data['responses'] = $responses; 
    	unset($responses);
    	
    	if(count($dataArrayMR['courses']) && !empty($dataArrayMR['startDate']) && !empty($dataArrayMR['endDate'])) {	    
    	    $responseData = modules::run('lms/lmsServer/getMatchedResponses', $dataArrayMR['courses'], array(), $dataArrayMR['startDate'], $dataArrayMR['endDate'], FALSE);
			//_p($responseData);
    	    $responseUsers = $responseData['users'];
    	    $matchedCourses = $responseData['courses'];
    	    unset($responseData);
    	    if(count($responseUsers)) {
        		$this->load->model('ldbmodel');
        		
        		if($activateSOLRSearch === true) {
        		    $inputArray['responseSubmitDateStart'] = $dataArrayMR['startDate'].' 00:00:00';
        		    $inputArray['responseSubmitDateEnd'] = $dataArrayMR['endDate'].' 00:00:00';
        		    $inputArray['matchedCourses'] = $matchedCourses;
        		    
        		    $searchResult = $this->searchMRSolr($inputArray, $this->userStatus[0]['userid']);
        		    
        		    $userIds = array_intersect(array_keys($responseUsers), $searchResult);
        		    $totalRows = count($userIds);
        		}
        		else {
        		    $searchResult = $this->ldbmodel->searchLeadsMR($inputArray, $this->userStatus[0]['userid'], array_keys($responseUsers));
        		    $userIds = $searchResult['userIds'];
        		    $totalRows = $searchResult['totalRows'];
        		    
        		}
        		
        		unset($searchResult);
        		
        		if (count($userIds) == 0) {
        		    $responseArray = array('error' => 'No Results Found For Your Query');
        		}
        		else {
                     $userToBeExcluded = $this->ldbmodel->getExcludedList();

                     foreach ($userIds as $userId) {
                         if(!in_array($userId, $userToBeExcluded)){
                             $excludedUserId[]=$userId;
                         }
                     }

                     $userIds = $excludedUserId;

                     unset($excludedUserId);
                     unset($userToBeExcluded);

        		    $finalUsers = array();
        		    
        		    foreach($userIds as $index => $userId) {
        				unset($userIds[$index]);
        				$userIds[$responseUsers[$userId]['responseId']] = $userId;
        		    }
        		    
        		    krsort($userIds);
        		    $userIds = array_values($userIds);
        		    
        		    if (empty($rows)) {
        				$finalUsers = $userIds;
        		    }
        		    else {
        				for($i = $start; $i < $start + $rows && $i < count($userIds); $i++) {
        					$finalUsers[$i-$start] = $userIds[$i];
        				}
        		    }
        		    
        		    if($activateSOLRSearch === true) {
        				
        				$this->load->library('LDB/searcher/LeadSearcher');
        				$leadSearcher = new LeadSearcher;				
        				$resultSet = $leadSearcher->getUserDetails($finalUsers, $this->userStatus[0]['userid']);
        				
        		    }
        		    
        		    else {
        				
        				$resultSet = modules::run('LDB/LDB_Server/createResultSet', $finalUsers, $this->userStatus[0]['userid']);
        		    }
        		    
        		    $responseArray = array(
        		    'numrows' => $totalRows,
        		    'result' => $resultSet
        		    );
        		    
        		    unset($resultSet);
        		    $data['matchedResponseData'] = array();
        		    
        		    foreach($finalUsers as $userId) {
        				$data['matchedResponseData'][$userId]['date'] = $responseUsers[$userId]['submitDate'];				
        				$responseDetails = $responseUsers[$userId]['matchedFor'];
        				foreach($responseDetails as $courseId) {
        					$data['matchedResponseData'][$userId]['matchedCourses'][$courseId]['CourseName'] = $displayArray['matchedCourses'][$courseId];
        					$data['matchedResponseData'][$userId]['matchedCourses'][$courseId]['InstituteName'] = $displayArray['matchedCoursesInstitute'][$courseId];
        				}
        		    }
        		}

    			$data['resultResponse'] = $responseArray;
                $todayDate = date('Y-m-d');
                $data['clientAccess'] = true;
                $clientAccessData = $this->ldbmodel->getManualLDBAccessData($ClientId, $todayDate);
                if(empty($clientAccessData)) {
                    $data['clientAccess'] = false;
                }
    	    }
	    }
	    unset($finalUsers);
	    unset($responseArray);
	    unset($matchedCourses);
	    unset($responseUsers);
	     
		require_once APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
		$data['coursesList'] = $coursesList;
		$data['actual_course_id'] = $coursesList[$displayArray['DesiredCourse']]['actual_course_id'];
		$this->load->view("enterprise/searchResult", $data);
    }
    
    function searchMRSolr($inputArray,$clientUserId,$responseUsers)    {
	$start = microtime(true);
	
	$this->load->library('LDB/searcher/LeadSearcher');
	$leadSearcher = new LeadSearcher;
	
	$inputArray['clientUserId'] = $clientUserId;
	$userIds = $leadSearcher->search($inputArray);
	
	return $userIds;
    }
	
	function clientCourseMatchingCriteria() {
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client', 'ajax'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $this->load->view("enterprise/clientCourseMatchingCriteria", $data);
    }

	function clientCourseMatchingCriteriaForm(){
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $data = array();
        $client_id = $this->input->post('clientid');
		$data['client_id'] = $client_id;
		
	    $isallCourses = 'N';$is_selected_courses = 'Y';
		require_once APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
	    $matchedResponsesData = $this->getDataForMatchedResponses($client_id, '', $coursesList, $isallCourses, $is_selected_courses);
		if(!empty($matchedResponsesData['instituteList'])) {
			$data['actual_course_id'] = $matchedResponsesData['actual_course_id'];
			$data['instituteList'] = array();
			$data['instituteList'] = $matchedResponsesData['instituteList'];
	
			$this->load->model('matchingcriteriamodel');
			$matchingcriteriaobj = new MatchingCriteriaModel();
			$data['clientallcriteria'] = $matchingcriteriaobj->getClientAllCriteria($client_id);
			foreach($data['clientallcriteria'] as $course_id=>$criteria) {
				$course_ids[] = $course_id;			
			}
			if(!empty($course_ids)) {
				$this->load->model('listing/listingmodel');
				$courses = $this->listingmodel->getCourseNameAndInstituteNameOfCourses($course_ids);
				foreach($courses as $coursesdata) {
					$all_courses_data[$coursesdata['course_id']] = $coursesdata;				
				}
				$data['all_courses_data'] = $all_courses_data;
			}
			$this->load->view("enterprise/clientCourseMatchingCriteriaForm", $data);
		} else {
			$output = "no_courses_found";
			echo $output;
		}
    }
	
	function saveClientCourseMatchingCriteria() {
		$this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
		$client_id = $this->input->post('client_id');
		$course_details = $this->input->post('course_id');
		$qualitypercentage = $this->input->post('qualitypercentage');
		$this->load->model('matchingcriteriamodel');
		$matchingcriteriaobj = new MatchingCriteriaModel();
		$result = $matchingcriteriaobj->getClientAllCriteria($client_id);
		foreach($course_details as $course_detail) {
			$exp_course_detail = explode("|",$course_detail);
			$course_id = $exp_course_detail[0];
			if($course_id > 0) {
				if(empty($result[$course_id])) {
					$matchingcriteriaobj->saveMatchingCriteria($client_id, $course_id, $qualitypercentage);			
				} else{
					$matchingcriteriaobj->updateMatchingCriteria($client_id, $course_id, $qualitypercentage);			
				}
			}
		}
		
		
		header('location:/enterprise/shikshaDB/clientCourseMatchingCriteria');
        exit();
	}
	
	
	function getMatchedResponsesCount(){
        $client_id = $this->input->post('clientid');
		$qualitypercentage = $this->input->post('qualitypercentage');
		$course_ids = explode(",",$this->input->post('course_ids'));
		$formatted_course_ids = array();
		foreach($course_ids as $course_id) {
			$formatted_course_ids[$course_id] = $qualitypercentage;			
		}
		$startDate = date('Y-m-d H:i:s', strtotime('-30 days'));
		$endDate = date('Y-m-d');		
		
		$matchedResponses = modules::run('lms/lmsServer/getMatchedResponses', $course_ids, $formatted_course_ids, $startDate, $endDate, FALSE);
		$count = count($matchedResponses['users']);
		echo $count;
    }
	
    function repostExceedingDayTemp(){
        $this->init();
        $numDays = $this->input->post('numDays',true);
        
        $userId = $this->userStatus[0]['userid'];
        $referer = $_SERVER['HTTP_REFERER'];
        $message = $userId." , ".$numDays." Days , ".date("F j, Y, g:i a")." , ".$referer;

        $file = '/tmp/userSearchDateRangeReport.txt';
        $current = file_get_contents($file);
        $current .= $message."\n";
        file_put_contents($file, $current);

        echo 1;
        return;
    }

    function manageManualLDBAccess() {
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $data['prodId'] = 1020;

        $this->load->model('LDB/ldbmodel');
        $ldbAccessData = $this->ldbmodel->getManualLDBAccessData();

        $data['ldbAccessData'] = $ldbAccessData;
        $this->load->view("enterprise/manageManualLDBAccess", $data);
    }
    
    public function manageManualLDBAccessByStream() {
				
		$this->userStatus = $this->checkUserValidation();
		if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        
        $this->load->library(array('enterprise_client'));
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $data['prodId'] = 1020;
        
        $this->load->model('LDB/ldbmodel');
        $ldbAccessData = $this->ldbmodel->getManualLDBAccessDataByStream();
        $data['ldbAccessData'] = $ldbAccessData;                
        
        $this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new \ListingBaseBuilder();
		$HierarchyRepository = $listingBase->getHierarchyRepository();		
		$streamsArray = $HierarchyRepository->getAllStreams();
		foreach($streamsArray as $value) {
			$stream_list[$value['id']] = $value['name']; 				
		}
		
		asort($stream_list);
		
		if(is_array($stream_list) && count($stream_list)>0) {			
			
			$data['stream_list'] = $stream_list;
			$access_data_array = array();			
			
			foreach($data['ldbAccessData'] as $access_data) {
				$access_data['stream_name']	 = $stream_list[$access_data['streamId']];
				$access_data_array[] = $access_data;
			}
			
			$data['ldbAccessData'] = $access_data_array;		
		}
		
        $this->load->view("enterprise/manageManualLDBAccessByStream", $data);
        		
	}
	
	function saveManualLDBAccessDataByStream() {
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }

        $client_id = $this->input->post('client_id',true);
        $streams = $this->input->post('streams',true);

        $this->load->model('user/usermodel');
        $clientData = $this->usermodel->getNameByUserId($client_id);
        if(empty($clientData)) {
            header('location:/enterprise/shikshaDB/manageManualLDBAccessByStream?error=1');
            exit();
        }
        
        $data = array();
        if(is_array($streams) && count($streams)>0) {
			foreach($streams as $stream) {
				if($stream>0) {
				   $data[] = array('client_id'=>$client_id,'status'=>'live','streamId'=>$stream); 						
				}
			}
		}
        
        if(count($data)>0) {
			$this->load->model('LDB/ldbmodel');
			$this->ldbmodel->saveManualLDBAccessDataByStream($client_id,$data);        
		}
        
        header('location:/enterprise/shikshaDB/manageManualLDBAccessByStream');
        exit();
    }
        
    function saveManualLDBAccessData() {
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }

        $client_id = $this->input->post('client_id');
        $ended_on = $this->input->post('timerangeTill');

        $this->load->model('user/usermodel');
        $clientData = $this->usermodel->getNameByUserId($client_id);
        if(empty($clientData)) {
            header('location:/enterprise/shikshaDB/manageManualLDBAccess?error=1');
            exit();
        }

        $this->load->model('LDB/ldbmodel');
        $ldbAccessData = $this->ldbmodel->getManualLDBAccessData($client_id);
        if(!empty($ldbAccessData)) {
            header('location:/enterprise/shikshaDB/manageManualLDBAccess?error=2');
            exit();
        }

        $data = array();
        $data['client_id'] = $client_id;
        $data['ended_on'] = $ended_on;

        $this->load->library('sums_product_client');
        $objSumsProduct = new Sums_Product_client();
        $salesUserInfo = $objSumsProduct->getSalesDataByClientId(1, $client_id);

        if(!empty($salesUserInfo)) {
            $salesUserData = $this->usermodel->getNameByUserId($salesUserInfo['userId']);

            $data['salesUserId'] = $salesUserInfo['userId'];
            $data['salesUserName'] = $salesUserData[0]['firstname'].' '.$salesUserData[0]['lastname'];
        }

        $this->ldbmodel->saveManualLDBAccessData($data);        
        
        header('location:/enterprise/shikshaDB/manageManualLDBAccess');
        exit();
    }

  
    function deleteManualLDBAccessByStream($id) {		
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }        
                
        $this->load->model('LDB/ldbmodel');
        $this->ldbmodel->deleteManualLDBAccessDataByStream($id);
        
    }
   
    function deleteManualLDBAccess($id) {
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('ajax', 'enterprise_client', 'ajax'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];

        $this->load->model('LDB/ldbmodel');
        $this->ldbmodel->deleteManualLDBAccessData($id);

        header('location:/enterprise/shikshaDB/manageManualLDBAccess');
        exit();
    }

    function dumpAllExistingSubscriptionClientDetails () {
        $this->load->library('sums_product_client');
        $objSumsProduct = new Sums_Product_client();
        $salesUserInfo = $objSumsProduct->getOldActiveSubscriptionClients(1);


        $this->load->model('user/usermodel');
        $this->load->model('LDB/ldbmodel');

        foreach($salesUserInfo as $client_id=>$salesUser) {
            $salesUserData = array();
            $data = array();
            $salesUserData = $this->usermodel->getNameByUserId($salesUser['salesUserId']);

            $data['client_id'] = $client_id;
            $data['ended_on'] = $salesUser['SubscriptionEndDate'];
            $data['salesUserId'] = $salesUser['salesUserId'];
            $data['salesUserName'] = $salesUserData[0]['firstname'].' '.$salesUserData[0]['lastname'];

            $this->ldbmodel->saveManualLDBAccessData($data);        

        }
        echo count($salesUserInfo).' clients inserted. Script Completed';
    }

    function formSubmitNonMR(){
         
        exit;
        $data = array();
        global $examGrades;
        
        $this->load->library('LDB_Client');
        $ldbObj = new LDB_client();
        $this->load->model('LDB/ldbmodel');
        $postArray = array();
        $displayArray = array();
        
        $postArray['stream'] = $_POST['stream_1'];
        $postArray['substream'] = $_POST['substream_1'];
        $postArray['specializationId'] = $_POST['specialization_1'];
        $postArray['courseIds'] = $_POST['courses_1'];
        $postArray['mode'] = $_POST['mode_1'];
        $postArray['exams'] = $_POST['exams_1'];
        $postArray['CurrentCities'] = $_POST['city_1'];
        $postArray['currentLocalities'][0] = $_POST['locality_1'];

        $displayArray['stream'] = $_POST['stream_1'];
        $displayArray['substream'] = $_POST['substream_1'];
        $displayArray['specializationId'] = $_POST['specialization_1'];
        $displayArray['courseIds'] = $_POST['courses_1'];
        $displayArray['mode'] = $_POST['mode_1'];
        $displayArray['exams'] = $_POST['exams_1'];
        $displayArray['CurrentCities'] = $_POST['city_1'];
        $displayArray['currentLocalities'][0] = $_POST['locality_1'];

        $_POST['timefilter']['from'] = $_POST['timeRangeDurationFrom'];
        $_POST['timefilter']['to'] = $_POST['timeRangeDurationTo'];

        unset($_POST['timeRangeDurationFrom']);
        unset($_POST['timeRangeDurationTo']);

        $displayArray['workex'] = "";
        if (isset($_POST['minExp_1'])) {
            if (is_numeric($_POST['minExp_1'])) {
                $postArray['MinExp'] = $_POST['minExp_1'];
                $displayArray['workex'] = "greater than " . $_POST['minExp_1'] . " Year";
            }
        }
        if (isset($_POST['maxExp_1'])) {
            if (is_numeric($_POST['maxExp_1'])) {
                $postArray['MaxExp'] = $_POST['maxExp_1'];
                if ($displayArray['workex'] == "") {
                    $displayArray['workex'] = "less than " . $_POST['maxExp_1'] . " Year";
                } else {
                    $displayArray['workex'] .= " and less than " . $_POST['maxExp_1'] . " Year";
                }
            }
        }

        if ( isset($_POST['timefilter']['from']) && isset($_POST['timefilter']['to']) ){
            $postArray['DateFilterFrom'] = $_POST['timefilter']['from'];
            $displayArray['DateFilterFrom'] = $_POST['timefilter']['from'];
            $postArray['DateFilterTo'] = $_POST['timefilter']['to'].' 23:59:59';
            $displayArray['DateFilterTo'] = $_POST['timefilter']['to'].' 23:59:59';
        }
        
        $postArray['includeActiveUsers'] = $_POST['includeActiveUsers'];
        $_SESSION['DateFilterFrom'] = $postArray['DateFilterFrom'];
        $_SESSION['DateFilterTo'] = $postArray['DateFilterTo'];

        $this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);
        $this->displayResults($postArray, $displayArray, 0, 100,$search_key);

    }

    function getCreditRequiredForConsumptionMR($clientId, $userIdCSV,$actual_course_id){
        global $MRPricingArray;
        global $managementStreamMR;
        global $engineeringtStreamMR;


        if($actual_course_id == 2){
            $pricingArray = $MRPricingArray[$managementStreamMR];
        }else if($actual_course_id == 52){
            $pricingArray = $MRPricingArray[$engineeringtStreamMR];
        }

        $userIDArray = explode(",", $userIdCSV);

        foreach ($userIDArray as $userId) {
            $returnArray[$userId]['view'][$userId] = $pricingArray['view'];
            $returnArray[$userId]['email'][$userId] = $pricingArray['email'];
            $returnArray[$userId]['sms'][$userId] = $pricingArray['SMS'];

        }

        return $returnArray;
    }


    private function formatEnterpriseTrackingData($inputArray, $displayArray, $csvType){
        $trackingParams = array();

        if(isset($displayArray['DontShowViewed'])){
            $trackingParams['page_tab']         = 'CreateGenieSearch_Unviewed';                
        }elseif (isset($displayArray['Viewed'])) {
            $trackingParams['page_tab']         = 'CreateGenieSearch_Viewed';
        }elseif (isset($displayArray['Emailed'])) {
            $trackingParams['page_tab']         = 'CreateGenieSearch_Emailed';
        }elseif (isset($displayArray['Smsed'])) {
            $trackingParams['page_tab']         = 'CreateGenieSearch_Smsed';
        }elseif (isset($displayArray['All'])) {
            $trackingParams['page_tab']         = 'CreateGenieSearch_All';
        }    
        
        $trackingParams['cta']              = 'Download';
    
        $trackingParams['product']  = 'Lead';                    
        if($csvType == 'nationalMR'){
            $trackingParams['product']          = 'MR';                    
        }

        $tracking_ids = $inputArray;
        unset($tracking_ids['streamData']);
        unset($tracking_ids['subStreamData']);
        unset($tracking_ids['specializationData']);
        unset($tracking_ids['courseData']);
        unset($tracking_ids['attributesValueNamePair']);
        unset($tracking_ids['modeDataDisplay']);
        unset($tracking_ids['groupViewLimit']);

        $tracking_names = $displayArray;

        unset($tracking_names['stream']);
        unset($tracking_names['subStream']);
        unset($tracking_names['specializationId']);
        unset($tracking_names['subStreamSpecializationMapping']);
        unset($tracking_names['courseId']);
        unset($tracking_names['attributeIds']);
        unset($tracking_names['attributeValues']);
        unset($tracking_names['attributesValueNamePair']);
        unset($tracking_names['CurrentCities']);
        unset($tracking_names['currentLocalities']);

        $tracking_data['tracking_ids'] = json_encode($tracking_ids);
        $tracking_data['tracking_names'] = json_encode($tracking_names);


        unset($tracking_ids);
        unset($tracking_names);

        $trackingParams['search_criteria'] =json_encode($tracking_data);
        unset($tracking_data);

  	return $trackingParams;
    }
}
?>
