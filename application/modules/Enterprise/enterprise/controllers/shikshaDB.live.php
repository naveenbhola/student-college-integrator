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

    function confirmShowContactDetails($userid) {
        $this->init();
        $response = $this->getCreditDetails($this->userStatus[0]['userid'], array($userid), 'view');
        echo json_encode($response);
    }

    function showContactDetails($userIdToView, $ExtraFlag='false') {
        $this->init();
        $responseUserDetails = $this->consumeLDBSubscription($this->userStatus[0]['userid'], array('0' => $userIdToView), 'view', $ExtraFlag);
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

    function sendEmailForActivity($clientId, $activityId, $subject="(no subject)", $content="no content", $fromEmail=ADMIN_EMAIL) {
        $this->init();
        $responseUserDetails = $this->consumeCreditsForActivity($clientId, $activityId);
        $this->load->library('alerts_client');
        $alertClientObj = new Alerts_client();
        foreach ($responseUserDetails['result'] as $row) {
            $response = $alertClientObj->externalQueueAdd("12", $fromEmail, $row[0]['email'], $subject, $content, $contentType = "text");
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

    function sendSmsForActivity($clientId, $activityId, $content="no content") {
        $this->init();
        $responseUserDetails = $this->consumeCreditsForActivity($clientId, $activityId);
        $this->load->library('alerts_client');
        $alertClientObj = new Alerts_client();
        foreach ($responseUserDetails['result'] as $row) {
            $alertResponse = $alertClientObj->addSmsQueueRecord(12, $row[0]['mobile'], $content, $this->userStatus[0]['userid'], "", "user-defined");
        }
        $ldbObj = new LDB_client();
        $ldbObj->updateActivityStatus(1, $responseUserDetails['activityId'], 'done');
        echo json_encode($responseUserDetails);
    }

    function downloadCSVForActivity($clientId, $activityId, $csvType, $filename) {
        /*
          :-( it failed @ PROD ...
          $url = $_SERVER['SERVER_NAME'] .':'. $_SERVER['SERVER_PORT'];
          $http_code = get_http_code($url);
          if ($http_code != '200') {
          echo "<script>alert('An error occurred,please try again later.');</script>";
          exit;
          }
         */
        $this->init();
        $responseUserDetails = $this->consumeCreditsForActivity($clientId, $activityId);
        $ldbObj = new LDB_client();
        $UserDetailsArray = $ldbObj->sgetUserDetails(1, $responseUserDetails['UserIdList']);
        $UserDataArray = $this->createUserDataArray(json_decode($UserDetailsArray, true));
        $leads = $UserDataArray;
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
        $ldbObj->updateActivityStatus(1, $responseUserDetails['activityId'], 'done');
        return $csv;
    }

    function createUserDataArray($UserDetailsArray) {
        $LocalCourseArray = array();
        foreach ($UserDetailsArray as $userDetails) {
            $formattedUserDetails = array();
            $formattedUserDetails['Name'] = $userDetails['displayname'];
            $formattedUserDetails['Gender'] = $userDetails['gender'];
            $formattedUserDetails['Age'] = $userDetails['age'];
            $formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName'];
            //For Study Abroad Desired Course is Desired Course Level
            $formattedUserDetails['Desired Course Level'] = $formattedUserDetails['Desired Course'];
            $formattedUserDetails['Field of Interest'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CategoryName'];
            $formattedUserDetails['Desired Specialization'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['SpecializationName'];
            $formattedUserDetails['Mode'] = ($userDetails['PrefData'][0]['ModeOfEducationFullTime'] == "yes") ? "Full Time" : (($userDetails['PrefData'][0]['ModeOfEducationPartTime'] == "yes") ? "Part Time" : "");
            $prefDetails = $userDetails['PrefData'][0];
            $datediff = $this->datediff($prefDetails['TimeOfStart'], $prefDetails['SubmitDate']);
            $formattedUserDetails['Plan to start'] = ($prefDetails['YearOfStart'] != '0000') ? (($datediff != 0) ? "Within " . $datediff : "Immediately") : "Not Sure";
            $formattedUserDetails['Plan to go'] = $formattedUserDetails['Plan to start'];
            $formattedUserDetails['Work Experience'] = ($userDetails['experience'] > 0) ? ($userDetails['experience'] . " Years") : (($userDetails['experience'] === "0") ? "Less Than 1 Year" : "No Experience");
            $formattedUserDetails['Current Location'] = $userDetails['CurrentCity'];
            $formattedUserDetails['Is in NDNC list'] = $userDetails['isNDNC'];
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
            $i = 0;
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
                    $i++;
                    $key = "Exam Taken " . $i;
                    $formattedUserDetails[$key] = $educationData['Name'] . (($educationData['Marks'] != 0) ? "(" . $educationData['Marks'] . ")" : "");
                }
            }
            $locationPrefData = $userDetails['PrefData'][0]['LocationPref'];
            $formattedUserDetails['Preferred Locations'] = '';
            $formattedUserDetails['Desired Countries'] = '';
            for ($i = 0; $i < count($locationPrefData); $i++) {
                $key = 'Location Preference ' . ($i + 1);
                $formattedUserDetails[$key] = $locationPrefData[$i]['CityName'] . "-" . $locationPrefData[$i]['LocalityName'];
                $formattedUserDetails['Preferred Locations'].= (($i > 0) ? "," : "") . $locationPrefData[$i]['CityName'];
                $formattedUserDetails['Desired Countries'] .= (($i > 0) ? "," : "") . $locationPrefData[$i]['CountryName'];
            }
            $formattedUserDetails['User Comments'] = $userDetails['PrefData'][0]['UserDetail'];
            $formattedUserDetails['Date of Registration'] = $userDetails['CreationDate'];
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
        $this->init();
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

    private function showFormForStudyabroad(& $data) {
        $this->load->library('Category_list_client');
        $category_list_client = new Category_list_client();
        $categoryList = $category_list_client->getCategoryList(1, 1);
        $catgeories = array();
        foreach ($categoryList as $category) {
            $categories[$category['categoryID']] = $category['categoryName'];
        }
        asort($categories);
        $data['categories'] = $categories;

        $regions = json_decode($category_list_client->getCountriesWithRegions(1), true);
        $data['regions'] = $regions;
    }

    function index() {
      /* NEED TO RE FACTOR THIS API */
      
        $this->init();
        $courseName = isset($_REQUEST['course_name']) ? $_REQUEST['course_name'] : 'Full Time MBA/PGDM';
        $actual_course_name = isset($_REQUEST['course_name']) ? $_REQUEST['course_name'] : 'Full Time MBA/PGDM';
        $boolen_flag_to_apply_hack_on_mba_courses = 'true';
        
	/* Added New Management Courses START RAVI RAJ */
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
	  $courseName = 'IT Degree';
	  $boolen_flag_to_apply_hack_on_mba_courses = 'false';
	}
	/* Added New Management Courses END RAVI RAJ */
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Distance MCA/BCA')) {
            $courseName = 'IT Courses';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Animation, Multimedia Courses')) {
            $courseName = 'IT Courses';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Animation, Multimedia Degrees')) {
            $courseName = 'IT Degree';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Hospitality,Tourism Courses')) {
            $courseName = 'IT Courses';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Hospitality,Tourism Degrees')) {
            $courseName = 'IT Degree';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Science & Engineering')) {
            $courseName = 'IT Degree';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Medicine, Health & Beauty Courses')) {
            $courseName = 'IT Courses';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Medicine, Health & Beauty Degrees')) {
            $courseName = 'IT Degree';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Design Degrees')) {
            $courseName = 'IT Degree';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Media, FIlms & Mass Communication Courses')) {
            $courseName = 'IT Courses';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Media, FIlms & Mass Communication Degrees')) {
            $courseName = 'IT Degree';
        }
        if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Test Preps')) {
            $courseName = 'IT Courses';
        }
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $entObj = new Enterprise_client();
        $ldbObj = new LDB_client();
        $data['searchTabs'] = array(
            array('course_name' => 'Study Abroad', 'tab_type' => 'studyAbroad'),
            array('course_name' => 'Full Time MBA/PGDM', 'tab_type' => 'national'),
            array('course_name' => 'Distance/Correspondence MBA', 'tab_type' => 'local'),
            array('course_name' => 'Certifications', 'tab_type' => 'local'),
            array('course_name' => 'Online MBA', 'tab_type' => 'local'),
            array('course_name' => 'Part-time MBA', 'tab_type' => 'local'),
            array('course_name' => 'Executive MBA', 'tab_type' => 'national'),
            array('course_name' => 'IT Courses', 'tab_type' => 'local'),
            array('course_name' => 'IT Degree', 'tab_type' => 'national'),
            array('course_name' => 'Distance MCA/BCA', 'tab_type' => 'local'),
            array('course_name' => 'Animation, Multimedia Courses', 'tab_type' => 'local'),
            array('course_name' => 'Animation, Multimedia Degrees', 'tab_type' => 'national'),
            array('course_name' => 'Hospitality,Tourism Courses', 'tab_type' => 'local'),
            array('course_name' => 'Hospitality,Tourism Degrees', 'tab_type' => 'national'),
            array('course_name' => 'Science & Engineering', 'tab_type' => 'national'),
            array('course_name' => 'Test Preps', 'tab_type' => 'local'),
            array('course_name' => 'Medicine, Health & Beauty Courses', 'tab_type' => 'local'),
            array('course_name' => 'Medicine, Health & Beauty Degrees', 'tab_type' => 'national'),
            array('course_name' => 'Design Degrees', 'tab_type' => 'national'),
            array('course_name' => 'Media, FIlms & Mass Communication Courses', 'tab_type' => 'local'),
            array('course_name' => 'Media, FIlms & Mass Communication Degrees', 'tab_type' => 'national')
        );
        $data['prodId'] = 31;
        $data['when_you_plan_start'] = $this->_when_you_plan_start();
        $data['course_name'] = $courseName;
        $data['actual_course_name'] = $actual_course_name;
        $this->load->library('category_list_client');
        $categoryClient = new Category_list_client();
        $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
        $data['cityList'] = array_merge($cityListTier1, $cityListTier2);
        $data['cityList_tier1'] = $cityListTier1;
        $data['cityList_tier2'] = $cityListTier2;
        $data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12), true);

        if ($courseName == 'Study Abroad') {
            $Validity = $this->checkUserValidation();
            $clientId = $Validity[0]['userid'];
            $this->load->library('SearchAgents_client');
            $categoryClient = new SearchAgents_client();
            $appId = 1;
            $search_agents_array = $categoryClient->getAllSearchAgents($appId, $clientId);
            $data['search_agents_array'] = $search_agents_array;
            $this->showFormForStudyabroad(& $data);
            $cmsuserinfo = $this->cmsUserValidation();
            $data['myProducts'] = $cmsuserinfo['myProducts'];
            $data['usergroup'] = 'enterprise';
            $this->load->view('enterprise/studentSearchForm', $data);
        } else {
            $data['select_city_list'] = $this->_select_city_list();
	    $courseNameToGetSpec = isset($_REQUEST['course_name']) ? $_REQUEST['course_name'] : 'Full Time MBA/PGDM';
	    $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, $courseNameToGetSpec,
"india"), true);
	    //echo "<pre>";print_r($data['course_specialization_list']);echo "</pre>";
	    $categoryClient = new Category_list_client();
            $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
            $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
            $data['cityList'] = array_merge($cityListTier1, $cityListTier2);
            $data['cityList_tier1'] = $cityListTier1;
            $data['cityList_tier2'] = $cityListTier2;
            $categoryClient = new Category_list_client();
            if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Animation, Multimedia Courses')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 12), true);
            } else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Animation, Multimedia Degrees')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 12), true);
            } else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Hospitality,Tourism Courses')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 6), true);
            } else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Hospitality,Tourism Degrees')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 6), true);
            } else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Science & Engineering')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 2), true);
            } else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Medicine, Health & Beauty Courses' || $_REQUEST['course_name'] == 'Medicine, Health & Beauty Degrees')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 5), true);
            } else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Design Degrees')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 13), true);
            } else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Media, FIlms & Mass Communication Courses' || $_REQUEST['course_name'] == 'Media, FIlms & Mass Communication Degrees')) {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 7), true);
            } else if (isset($_REQUEST['course_name']) && ($_REQUEST['course_name'] == 'Test Preps')) {
                $data['itcourseslist'] = $categoryClient->getTestPrepCoursesList(1);
            } else {
                $data['itcourseslist'] = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1, 10), true);
            }
            $Validity = $this->checkUserValidation();
            $clientId = $Validity[0]['userid'];
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
	    $data['boolen_flag_to_apply_hack_on_mba_courses'] = $boolen_flag_to_apply_hack_on_mba_courses;
            // echo '<pre>';print_r($data);echo '</pre>';
            $this->load->view('enterprise/studentSearchForm', $data);
        }
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
        $this->displayResults($inputArray, $displayArray, $start, $rows);
    }

    // API that render search Result as AJAX response
    function displayResults($inputArray, $displayArray, $start, $rows) {
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
        $ldbObj = new LDB_client();
        $response = $ldbObj->s_submitSearchQuery('12', $inputArray, $this->userStatus[0]['userid'], $this->userStatus[0]['usergroup'], $start, $rows);
        //error_log("Ravi: Response ".print_r($response,true));
        $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, $courseName), true);
        $data['resultResponse'] = $response;
        $data['displayArray'] = $displayArray;
        $data['inputData'] = base64_encode(json_encode($inputArray));
        $data['displayData'] = base64_encode(json_encode($displayArray));
        $data['flag_manage_page'] = $inputArray['flag_manage_page'];
        //error_log("Ravi: Data ".print_r($data,true));
        $this->load->view("enterprise/searchResult", $data);
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
        $i = 0;
        foreach ($_POST['board_id'] as $temp) {
            $categoryId[$temp] = $temp;
            $i++;
        }
        $desiredCourseLevels = $_POST['desiredCourseLevel'];
        $this->load->library('LDB_Client');
        $ldbClientObj = new LDB_Client();
        $this->load->library('category_list_client');
        $category_list_client = new Category_list_client();
        $categoryList = $category_list_client->getCategoryList(1, 1);
        if (empty($desiredCourseLevels)) {
            $desiredCourseLevels = array('UG', 'PG');
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
                    foreach ($categoryList as $categoryDetails) {
                        if (in_array($categoryDetails['categoryID'], $categoryId)) {
                            $inputArray['DesiredCourseName'][] = $categoryDetails['categoryName'];
                        }
                    }
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

    // ajax funxtion for rendering result page

    function formSubmit() {
        $this->load->library('LDB_Client');
        $ldbObj = new LDB_client();
        $postArray = array();
        $displayArray = array();
        /* course name */
        $course_name = $_POST['search_form_course_name'];
        $actual_course_name = $_POST['actual_course_name'];
        $postArray['actual_course_name'] = $actual_course_name;
        $postArray['tab_course_name'] = $course_name;
        $postArray['DesiredCourse'] = isset($_POST['desiredCourse']) ? $_POST['desiredCourse'] : '';
        $displayArray['DesiredCourse'] = isset($_POST['desiredCourse']) ? $_POST['desiredCourse'] : '';
        if ($course_name == 'Study Abroad') {
            $postArray['ExtraFlag'] = 'studyabroad';
            $displayArray['ExtraFlag'] = 'studyabroad';
            $this->getDesiredCoursesForStudyAbroad(&$displayArray, &$postArray);
            $this->getSourcesOfFundingForStudyAbroad(&$displayArray, &$postArray);
        }
        $postArray['Specialization'] = isset($_POST['course_specialization']) ? $_POST['course_specialization'] : '';
        $displayArray['Specialization'] = "";
        if (in_array("-1", $postArray['Specialization'])) {
            $postArray['Specialization'] = '';
            $displayArray['Specialization'] = "All";
        }
        if (isset($_POST['course_specialization']) && $_POST['course_specialization'] != "") {
            $course_specialization_list = json_decode($ldbObj->sgetSpecializationList(12, $displayArray['DesiredCourse']), true);
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
            /* if(trim($_POST['CurLocCSV']) != "")
              {
              $postArray['CurrentLocation'] = explode(',',$_POST['CurLocCSV']);
              } */
            $postArray['CurrentLocation'] = $_POST['CurLocArr'];
            if (isset($_POST['hiddenCurrentCity'])) {
                $displayArray['currentLocation'] = implode(', ', $_POST['hiddenCurrentCity']);
            }
        }
        if (isset($_POST['prefLocClause'])) {
            if ($_POST['prefLocClause'] == 'or') {
                $postArray['LocationAndOr'] = 1;
            }
        }

        if (isset($_POST['prefLocArr'])) {
            $postArray['PreferredLocation'] = $this->remove_array_empty_values($_POST['prefLocArr']);
            if ($course_name == 'Distance/Correspondence MBA' || $course_name == 'Online MBA' || $course_name ==
'Part-time MBA' || $course_name == 'Certifications') {
                $postArray['Locality'] = $this->remove_array_empty_values($_POST['LocalityArr']);
                $hiddenpreferedCity = array_slice($_POST['hiddenpreferedCity'], 0, 5);
                if (empty($hiddenpreferedCity[0])) {
                    $displayArray['prefLocation'] = $_POST['hiddenpreferedMainCity'];
                } else {
                    $displayArray['prefLocation'] = implode(', ', $hiddenpreferedCity);
                }
            } else {
                if (isset($_POST['hiddenpreferedCity'])) {
                    $displayArray['prefLocation'] = implode(', ', $_POST['hiddenpreferedCity']);
                }
            }
        }
        /* IT Courses and IT Degree FIXES START */
        if ($course_name == 'IT Courses') {
            // TODO: flag_select_all  this flag can be used as "SELECT ALL" localities issue
            $postArray['Locality'] = $this->remove_array_empty_values($_POST['LocalityArr']);
            unset($postArray['Specialization']);
            unset($postArray['CurrentLocation']);
            if (in_array("-1", $this->remove_array_empty_values($_POST['course_specialization']))) {
                unset($_POST['course_specialization'][0]);
                /* :( we need to assign all to display array as now we treat specialization as desired course */
                $displayArray['Specialization'] = "All";
            }
            $postArray['DesiredCourse'] = $this->remove_array_empty_values($_POST['course_specialization']);
            if ($_POST['prefLocClause'] == 'or') {
                $postArray['LocationAndOr'] = 1;
            }
            $course_specialization = array_slice($_POST['course_specialization'], 0, 5);
            $hiddenpreferedCity = array_slice($_POST['hiddenpreferedCity'], 0, 5);
            if (empty($hiddenpreferedCity[0])) {
                $displayArray['prefLocation'] = $_POST['hiddenpreferedMainCity'];
            } else {
                $displayArray['prefLocation'] = implode(', ', $hiddenpreferedCity);
            }
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
            $DesiredCourse = array_slice($_POST['desiredCourse'], 0, 5);
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
            $course_specialization = array_slice($postArray['testPrep_blogid'], 0, 5);
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

        $displayArray['graduationDate'] = "";
        if ($_POST['gradStartYear'] != "" && $_POST['gradStartMonth'] != "") {
            $postArray['GraduationCompletedFrom'] = $_POST['gradStartYear'] . "-" . $_POST['gradStartMonth'] . "-01 00:00:00";
            $displayArray['graduationDate'] = "After " . $_POST['gradStartMonth'] . "-" . $_POST['gradStartYear'];
        }

        if ($_POST['gradEndYear'] != "" && $_POST['gradEndMonth'] != "") {
            $result = strtotime("{$_POST['gradEndYear']}-{$_POST['gradEndMonth']}-01");
            $result = strtotime('-1 second', strtotime('+1 month', $result));
            $finaldate = $result . " 23:59:59";
            $finaldate = date("Y-m-d H:i:s", $finaldate);

            $postArray['GraduationCompletedTo'] = $finaldate;
            if ($displayArray['graduationDate'] == "") {
                //$displayArray['graduationDate'] = "Before ".$_POST['gradEndMonth']."-".$_POST['gradEndYear'];
                $displayArray['graduationDate'] = "Before " . date("m-Y", $result);
            } else {
                //$displayArray['graduationDate'] .= " and before ".$_POST['gradEndMonth']."-".$_POST['gradEndYear'];
                $displayArray['graduationDate'] .= " and before " . date("m-Y", $result);
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
        if (isset($_POST['exam_cat'])) {
            if (is_numeric($_POST['cat_min_score'])) {
                $examArray['CAT']['min'] = $_POST['cat_min_score'];
            } else {
                $examArray['CAT']['min'] = "";
            }
            if (is_numeric($_POST['cat_max_score'])) {
                $examArray['CAT']['max'] = $_POST['cat_max_score'];
            } else {
                $examArray['CAT']['max'] = "";
            }
            $displayArray['examTaken'] = "CAT: " . $examArray['CAT']['min'] . " - " . $examArray['CAT']['max'];
        }
        if (isset($_POST['exam_mat'])) {
            if (is_numeric($_POST['mat_min_score'])) {
                $examArray['MAT']['min'] = $_POST['mat_min_score'];
            } else {
                $examArray['MAT']['min'] = "";
            }
            if (is_numeric($_POST['mat_max_score'])) {
                $examArray['MAT']['max'] = $_POST['mat_max_score'];
            } else {
                $examArray['MAT']['max'] = "";
            }
            if ($displayArray['examTaken'] != "") {
                $displayArray['examTaken'] .= ", MAT: " . $examArray['MAT']['min'] . " - " . $examArray['MAT']['max'];
            } else {
                $displayArray['examTaken'] = "MAT: " . $examArray['MAT']['min'] . " - " . $examArray['MAT']['max'];
            }
        }
        $i = 1;
        while (isset($_POST['other_exam_' . $i])) {
            if ($_POST['other_exam_' . $i] != "") {
                $otherExamName = $_POST['other_exam_' . $i];
                if (is_numeric($_POST['other_exam_' . $i . '_min'])) {
                    $examArray[$_POST['other_exam_' . $i]]['min'] = $_POST['other_exam_' . $i . '_min'];
                } else {
                    $examArray[$_POST['other_exam_' . $i]]['min'] = "";
                }
                if (is_numeric($_POST['other_exam_' . $i . '_max'])) {
                    $examArray[$_POST['other_exam_' . $i]]['max'] = $_POST['other_exam_' . $i . '_max'];
                } else {
                    $examArray[$_POST['other_exam_' . $i]]['max'] = "";
                }
                if ($displayArray['examTaken'] != "") {
                    $displayArray['examTaken'] .= ", " . $_POST['other_exam_' . $i] . ": " . $examArray[$_POST['other_exam_' . $i]]['min'] . " - " . $examArray[$_POST['other_exam_' . $i]]['max'];
                } else {
                    $displayArray['examTaken'] = $_POST['other_exam_' . $i] . ": " . $examArray[$_POST['other_exam_' . $i]]['min'] . " - " . $examArray[$_POST['other_exam_' . $i]]['max'];
                }
            }
            $i++;
        }
        $this->getExamDataForStudyAbroad(& $displayArray, &$examArray);
        $postArray['ExamScore'] = $examArray;
        /*
          if(isset($_POST['noShowContact']))
          {
          $postArray['DontShowContacted'] = 1;
          }
         */
        $postArray['DontShowViewed'] = 1;
        /* LDB 2.0 END */
        if (isset($_POST['filterResultSetOption']) && $_POST['filterResultSetOption'] != "1 month") {
            $postArray['DateFilter'] = $_POST['filterResultSetOption'];
            $displayArray['DateFilter'] = $_POST['filterResultSetOption'];
        } else {
            $postArray['DateFilter'] = '1 month';
            $displayArray['DateFilter'] = '1 month';
        }
        /*
          echo "<b>post</b>";
          echo "<pre>";print_r($_POST);echo"</pre>";
          echo "<b>post array</b>";
          echo "<pre>";print_r($postArray);echo"</pre>";
          echo "<b>display array</b>";
          echo "<pre>";print_r($displayArray);echo"</pre>";
         */
        // changed it to 50
        $this->displayResults($postArray, $displayArray, 0, 50);
    }

    function displayoverlay($type='form', $overlay='SendMail', $filter='unviewed', $selected='0', $totalCount, $maxlimitTextBox='500', $msg=NULL, $ReqCredits=NULL, $AvlCredits=NULL, $countNdnc=NULL) {
        error_log("pankaj tharejaaaaaa" . $type);
        $data['filter'] = $filter;
        $data['selected'] = $selected;
        $data['totalCount'] = $totalCount;
        $data['maxlimitTextBox'] = $maxlimitTextBox;
        $data['msg'] = base64_decode($msg);
//	    if($overlay=='Send SMS'){
//	    $ReqCredits = $ReqCredits-$countNdnc;
//	    }
        $data['ReqCredits'] = $ReqCredits;
        $data['AvlCredits'] = $AvlCredits;
        $data['countNdnc'] = $countNdnc;
        $data['type'] = $type;
        $data['overlay'] = $overlay;
        echo $this->load->view("enterprise/ldb_search_overlay", $data);
    }

    /* API used for AJAX & add testprep hack */

    function ajax_submit_input_form() {
        $clientId = $_POST['clientid'];
        $ExtraFlag = $_POST['ExtraFlag'];
        $userIdList = isset($_POST['userIdList']) ? $_POST['userIdList'] : '';
        $contactType = $_POST['contactType'];
        $searchParameters = json_decode(base64_decode($_POST['searchParameters']), true);
        $start = $_POST['start'];
        $end = $_POST['end'];
        $checkbox_filter = isset($_POST['checkbox_filter']) ? $_POST['checkbox_filter'] : '';
        if ($checkbox_filter == 'SendSMS') {
            $searchParameters = array_merge($searchParameters, array('DontShowSmsed' => '1'));
        }
        if ($checkbox_filter == 'SendEmail') {
            $searchParameters = array_merge($searchParameters, array('DontShowEmailed' => '1'));
        }
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        //echo "<pre>";print_r($searchParameters);echo "</pre>";exit;
        echo json_encode($this->getCreditDetailsForDisplay($clientId, $userIdList, $contactType, $searchParameters, $start, $end, $ExtraFlag));
    }

    function ajax_submit_sms_activity() {
        $activityId = $_POST['activityId'];
        $content = base64_decode($_POST['content']);
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        $this->sendSmsForActivity($clientId, $activityId, $content);
    }

    function ajax_submit_email_activity() {
        $activityId = $_POST['activityId'];
        $content = base64_decode($_POST['content']);
        $subject = base64_decode($_POST['subject']);
        $fromEmail = $_POST['fromEmail'];
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        $this->sendEmailForActivity($clientId, $activityId, $subject, $content, $fromEmail);
    }

    function ajax_submit_csv_activity($activityId, $csvType, $filename) {
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        $mime = 'text/x-csv';
        //$filename =preg_replace('/[^A-Za-z0-9]/', '',$filename).".csv";
        $filename = $filename . ".csv";
        $data = $this->downloadCSVForActivity($clientId, $activityId, $csvType, $filename);
        if (strlen($data) < 500) {
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

    function getCreditDetails($clientId, $userIdList, $action, $ExtraFlag='false') {
        $this->load->library('sums_product_client');
        $objSumsProduct = new Sums_Product_client();
        $SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1, array('userId' => $clientId));
        $objLDBClient = new LDB_Client();
        $creditConsumeArray = $objLDBClient->sgetCreditToConsume($appID, $userIdList, $action, $clientId, $ExtraFlag);
        error_log("creditConsumeArray is as " . print_r($creditConsumeArray, true));
        $countNdnc = $creditConsumeArray['countNdnc'];
        unset($creditConsumeArray['countNdnc']);
        $countToConsume = array_sum($creditConsumeArray);
        error_log("countToConsume is as " . $countToConsume);
        $creditCount = 0;
        $subscriptionDetailMapping = array();
        foreach ($creditConsumeArray as $userId => $userCreditdeduct) {
            foreach ($SubscriptionArray as $subscription) {
                if ($subscription['BaseProdCategory'] == 'Lead-Search') {
                    $subscriptionDetailMapping[$subscription['SubscriptionId']]['BaseProdRemainingQuantity'] = $subscription['BaseProdRemainingQuantity'];
                    $subscriptionDetailMapping[$subscription['SubscriptionId']]['BaseProductId'] = $subscription['BaseProductId'];
                    $subscriptionDetailMapping[$subscription['SubscriptionId']]['countLeft'] = $subscription['BaseProdRemainingQuantity'] - $subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed'];
                    if ($userCreditdeduct <= $subscriptionDetailMapping[$subscription['SubscriptionId']]['countLeft']) {
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed']+=$userCreditdeduct;
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
            $resultText = "You dont have sufficient Credits for this action";
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

    function getCreditDetailsForDisplay($clientId, $userIdList, $contactType, $searchParameters, $start, $end, $ExtraFlag='false') {
        $this->load->library(array('LDB_Client'));
        $ldbObj = new LDB_Client();
        error_log("Shivam basic para ClientId: " . $clientId . " ||userIdList: " . $userIdList . " ||contactType: " . $contactType . " ||searchPara: " . print_r($searchParameters, true) . " || start: " . $start . " ||end: " . $end);
        //echo "Shivam 1";
        if (!empty($searchParameters)) {
            //echo "Shivam 2";
            $searchResult = $ldbObj->searchLDB($appID, $searchParameters, $clientId, $start, $end);
            $searchUsers = implode(',', $searchResult['userIds']);
            $userIdList = (empty($userIdList)) ? $searchUsers : $userIdList . "," . $searchUsers;
        }
        //echo "Shivam 3";
        $CreditConsumeInformation = $this->getCreditDetails($clientId, explode(",", $userIdList), $contactType, $ExtraFlag);
        error_log("CreditConsumeInformation is as " . print_r($CreditConsumeInformation, true));
        //echo "Shivam 4";
        $actionResult = "prompt";
        if ($CreditConsumeInformation['result'] == "You dont have sufficient Credits for this action") {
            $actionResult = "insufficient";
        }
        error_log($contactType . "userIdList is as " . print_r($userIdList, true));
        /*
          if($contactType=='sms'){
          $ndncUsers=$ldbObj->removeNdncUsers($userIdList);
          error_log("ndncUsers are as ".print_r($ndncUsers,true));
          $userIdListTemp=explode(",",$userIdList);
          error_log("userIdListTemp is as ".print_r($userIdListTemp,true));
          $userIdListNonNdnc=array();
          foreach($userIdListTemp as $user)
          {
          if(!in_array($user,$ndncUsers))
          {
          error_log("users are as ".$user);
          array_push($userIdListNonNdnc,$user);
          }
          }
          $userIdList=implode(",",$userIdListNonNdnc);
          } */
        error_log("userIdList is as " . print_r($userIdList, true));
        $activityResponse = $ldbObj->recordLDBActivity($appID, $CreditConsumeInformation, $clientId, $userIdList, $contactType, 'prompt');
        //echo "Shivam 5";
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

    function consumeCreditsForActivity($clientId, $activityId) {
        $this->load->library(array('LDB_Client'));
        $ldbObj = new LDB_Client();
        $ActivityDetails = $ldbObj->getActivityDetails(1, $activityId);
        //error_log("LLDBA::".print_r($ActivityDetails,true));
        $contactType = $ActivityDetails[0]['Action'];
        //error_log("LLDBA::".print_r($contactType,true));
        $subscriptionList = json_decode($ActivityDetails[0]["SubscriptionInfo"], true);
        //error_log("LLDBA  subscriptionList ::".print_r($subscriptionList,true));
        $_CreditsForAction = $subscriptionList['CreditsForAction'];
        if ($ActivityDetails[0]['Status'] != 'prompt') {
            return array('result' => 'This activity is already done/in progress');
        }
        $ldbObj->updateActivityStatus(1, $ActivityDetails[0]['Id'], 'inprogress_started');
        if ((!$this->checkConsumptionInfoValidity($clientId, $subscriptionList['subArray'])) && ($_CreditsForAction > 0)) {
            $ldbObj->updateActivityStatus(1, $ActivityDetails[0]['Id'], 'inprogress_subscription_validity_falied');
            $subscriptionList = $this->getCreditDetails($clientId, explode(",", $ActivityDetails[0]['UserIdList']), $ActivityDetails[0]['Action']);
        }
        //error_log("LLDBA::".print_r($subscriptionList,true));
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
                    $returnval = $ldbObj->sUpdateContactViewed(12, $clientId, $userId, $contactType, $subscription['subscriptionId'], $creditdeduct);
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
        return array('result' => 'You dont have sufficient credit to perform the given action');
    }

    function consumeLDBSubscription($clientId, $userIds, $contactType, $ExtraFlag='false') {
        $count = count($userIds);
        //error_log($count);
        $subscriptionList = $this->getCreditDetails($clientId, $userIds, $contactType, $ExtraFlag);
        //error_log(print_r($subscriptionList,true));
        if (count($subscriptionList['subArray']) != 0) {
            //error_log("qweqawedawe121321");
            $returnArray = array();
            $this->load->library(array('subscription_client', 'LDB_Client'));
            $subsObj = new Subscription_client();
            $ldbObj = new LDB_Client();
            $start = 0;
            foreach ($subscriptionList['subArray'] as $subscription) {
                $returnval = $subsObj->consumeLDBCredits(12, $subscription['subscriptionId'], $subscription['creditToConsume'], $clientId, $clientId);
                //error_log("qweqawedawe121321");
                foreach ($subscription['userList'] as $userId => $creditdeduct) {
                    //error_log("qweqawedawe12132112121".$userId." ".$creditdeduct);
                    $returnval = $ldbObj->sUpdateContactViewed(12, $clientId, $userId, $contactType, $subscription['subscriptionId'], $creditdeduct);
                    $returnArrayElement = json_decode($returnval, true);
                    $returnArray[] = $returnArrayElement;
                    //error_log("qweqawedawe12132112121121".print_r($returnArrayElement,true));
                    $returnval = $subsObj->updateSubscriptionLog(12, $subscription['subscriptionId'], $creditdeduct, $clientId, $clientId, $subscription['BaseProductId'], $returnArrayElement[0]['insertId'], $contactType, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
                    //error_log("qweqawedawe12132112121");
                }
            }
            //error_log("qweqawedawe");
            return array('result' => $returnArray, 'CreditsForAction' => $subscriptionList['CreditsForAction'], 'CreditCount' => ($subscriptionList['CreditCount'] - $subscriptionList['CreditsForAction']));
        } else {
            //error_log("1231231312");
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
        //echo "<pre>";print_r($_POST);echo "</pre>";echo "<hr>";
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
// 	echo "<pre>";print_r($_POST['groupId']);echo "</pre>";
//         echo "<pre>";print_r($courseids);echo "</pre>";
// 	echo "<pre>";print_r($ExtraFlag);echo "</pre>";
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
        //$headerTabs[0]['selectedTab'] = 31;
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        //echo print_r($headerTabs,true);
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        //echo "<pre>".print_r($_POST,true)."</pre>";
        $ldbObj = new LDB_client();
        $data['groupList'] = $ldbObj->sgetGroupList(1);
        // echo "<pre>".print_r($data,true)."</pre>";
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
        //$headerTabs[0]['selectedTab'] = 31;
        $this->userStatus[0]['headerTabs'] = $headerTabs;
        //echo print_r($headerTabs,true);
        $data['validateuser'] = $this->userStatus;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        // echo "<pre>".print_r($_POST,true)."</pre>";
        $ldbObj = new LDB_client();
        $ldbObj->saddGroupCreditConsumptionPolicy(1, $_POST['groupId'], $_POST['view'], $_POST['email'], $_POST['sms'], $_POST['shared_view_limit'], $_POST['premimum_view_cr'], $_POST['premimum_view_limit'], $_POST['email_limit'], $_POST['sms_limit']);
        $data['groupList'] = $ldbObj->sgetGroupList(1);
        // echo "<pre>".print_r($data,true)."</pre>";
        $this->load->view("enterprise/GroupCreditConsumptionPolicyView", $data);
    }

    function getColumnList($courseType) {
        $columnListArray = array();
        if ($courseType == 'local') {
            $columnListArray[] = 'Name';
            $columnListArray[] = 'Gender';
            $columnListArray[] = 'Age';
            $columnListArray[] = 'Desired Course';
            $columnListArray[] = 'Plan to start';
            $columnListArray[] = 'Graduation Status';
            $columnListArray[] = 'Graduation Course';
            $columnListArray[] = 'Graduation Marks';
            $columnListArray[] = 'Graduation Month';
            $columnListArray[] = 'Graduation Year';
            $columnListArray[] = 'Std XII Stream';
            $columnListArray[] = 'Std XII Marks';
            $columnListArray[] = 'Std XII Year';
            $columnListArray[] = 'Location Preference 1';
            $columnListArray[] = 'Location Preference 2';
            $columnListArray[] = 'Location Preference 3';
            $columnListArray[] = 'Location Preference 4';
            $columnListArray[] = 'User Comments';
            $columnListArray[] = 'Date of Registration';
            $columnListArray[] = 'Is in NDNC list';
            $columnListArray[] = 'Email';
            $columnListArray[] = 'Mobile';
        } else if ($courseType == 'national') {
            $columnListArray[] = 'Name';
            $columnListArray[] = 'Gender';
            $columnListArray[] = 'Age';
            $columnListArray[] = 'Desired Course';
            $columnListArray[] = 'Desired Specialization';
            $columnListArray[] = 'Mode';
            $columnListArray[] = 'Plan to start';
            $columnListArray[] = 'Work Experience';
            $columnListArray[] = 'Graduation Status';
            $columnListArray[] = 'Graduation Course';
            $columnListArray[] = 'Graduation Marks';
            $columnListArray[] = 'Graduation Month';
            $columnListArray[] = 'Graduation Year';
            $columnListArray[] = 'Std XII Stream';
            $columnListArray[] = 'Std XII Marks';
            $columnListArray[] = 'Std XII Year';
            $columnListArray[] = 'Current Location';
            $columnListArray[] = 'Preferred Locations';
            $columnListArray[] = 'User Comments';
            $columnListArray[] = 'Date of Registration';
            $columnListArray[] = 'Is in NDNC list';
            $columnListArray[] = 'Email';
            $columnListArray[] = 'Mobile';
        } else if ($courseType == 'studyAbroad' || $courseType == 'studyabroad') {
            $columnListArray[] = 'Name';
            $columnListArray[] = 'Gender';
            $columnListArray[] = 'Age';
            $columnListArray[] = 'Desired Course Level';
            $columnListArray[] = 'Field of Interest';
            $columnListArray[] = 'Desired Countries';
            $columnListArray[] = 'Plan to go';
            $columnListArray[] = 'Source of Funding';
            $columnListArray[] = 'Graduation Status';
            $columnListArray[] = 'Graduation Course';
            $columnListArray[] = 'Graduation Marks';
            $columnListArray[] = 'Graduation Month';
            $columnListArray[] = 'Graduation Year';
            $columnListArray[] = 'Std XII Stream';
            $columnListArray[] = 'Std XII Marks';
            $columnListArray[] = 'Std XII Year';
            $columnListArray[] = 'Exam Taken 1';
            $columnListArray[] = 'Exam Taken 2';
            $columnListArray[] = 'Exam Taken 3';
            $columnListArray[] = 'Preferred Time to call';
            $columnListArray[] = 'Current Location';
            $columnListArray[] = 'User Comments';
            $columnListArray[] = 'Date of Registration';
            $columnListArray[] = 'Is in NDNC list';
            $columnListArray[] = 'Email';
            $columnListArray[] = 'Mobile';
        }

        return $columnListArray;
    }

    function getCreditRequiredForConsumption($clientId, $userIdCSV, $ExtraFlag='false') {
        $this->init();
        $objLDBClient = new LDB_Client();
        $userIDArray = explode(",", $userIdCSV);
        $creditConsumeArray = array();
        foreach ($userIDArray as $userId) {
            $creditConsumeArray[$userId]['view'] = $objLDBClient->sgetCreditToConsume(1, array($userId), 'view', $clientId, $ExtraFlag);
            $creditConsumeArray[$userId]['email'] = $objLDBClient->sgetCreditToConsume(1, array($userId), 'email', $clientId, $ExtraFlag);
            $creditConsumeArray[$userId]['sms'] = $objLDBClient->sgetCreditToConsume(1, array($userId), 'sms', $clientId, $ExtraFlag);
        }
        return json_encode($creditConsumeArray);
    }

    function getCreditConsumed($userIdCSV) {
        $this->init();
        $objLDBClient = new LDB_Client();
        $userIDArray = explode(",", $userIdCSV);
        $creditConsumeArray = array();
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        error_log($clientId . 'R2R');
        foreach ($userIDArray as $userId) {
            $creditConsumeArray[$userId]['view'] = $objLDBClient->sgetCreditConsumedForAction($clientId, $userId, 'view');
        }
        return json_encode($creditConsumeArray);
    }

    function ajax_getCreditConsumed($userIdCSV) {
        $ExtraFlag = $_POST['ExtraFlag'];
        echo $this->getCreditConsumed($userIdCSV);
    }

    /* add testprep hack */

    function ajax_getCreditRequiredForConsumption($userIdCSV) {
        $ExtraFlag = $_POST['ExtraFlag'];
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        echo $this->getCreditRequiredForConsumption($clientId, $userIdCSV, $ExtraFlag);
    }

    function display_static_overlay($no) {
        $data = array();
        if ($no == 1) {
            echo $this->load->view("enterprise/display_static_overlay", $data);
        }
    }

    function runSearchAgent($searchagentid, $flag_manage_page) {
        $this->init();
        $this->load->library('SearchAgents_client');
        $SAClientObj = new SearchAgents_client();
        $appId = 1;
        $search_agents_display_array = $SAClientObj->getSADisplayData($appId, $searchagentid);
        $inputArray = json_decode(base64_decode($search_agents_display_array[0]['inputdata']), true);
        $displayArray = json_decode(base64_decode($search_agents_display_array[0]['displaydata']), true);
        /* FLAG FOR MANAGE SEARCH AGENT */
        $inputArray['flag_manage_page'] = $flag_manage_page;
        $displayArray['flag_manage_page'] = $flag_manage_page;
        $this->displayResults($inputArray, $displayArray, 0, 50);
    }

}

?>
