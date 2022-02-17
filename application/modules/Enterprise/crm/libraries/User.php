<?php

class User {

    private $leadid;
    private $first_name;
    private $last_name = "";
    private $mobile;
    private $email;
    private $is_ndnc;
    private $gender;
    private $age;
    private $registered_on;
    private $current_location;
    private $degree_preference;
    private $mode;
    private $xii_std;
    private $ug_details;
    private $coursename;
    private $specialization;
    private $plan_to_start;
    private $source_of_funding;
    private $desired_countries;
    private $preferred_time_to_call;
    private $work_experience;
    private $responses;
    private $exams_taken;
    private $CI;
    private $assigned_user_id;
    private $textpassword;
    private $logininfo;	

    public function User($userArr) {
        $this->__construct($userArr);
    }

    public function __construct($userArr = null) {
        if (!isset($userArr))
            return;
        error_log(print_r($userArr,true));
	$this->textpassword = $userArr["textpassword"];
        $this->leadid = $userArr["userid"];
        $this->first_name = ucwords(preg_replace("/[0-9]/", "", $userArr['displayname']));
        $this->mobile = $userArr["mobile"];
        $this->email = $userArr["email"];
        $this->is_ndnc = $userArr["isNDNC"];
        $this->gender = $userArr["gender"];
        $this->age = $userArr["age"];
        $this->registered_on = $userArr["CreationDate"];
        $this->current_location = $userArr["CurrentCity"];
        $this->work_experience = ($userArr["experience"] == "" || $userArr["experience"] <= 0) ? "No Experience" : ($userArr["experience"] . " years");

        $prefData = $userArr['PrefData'][0];
        $specializationPrefData = $prefData['SpecializationPref'][0];
        $educationData = $userArr['EducationData'];
	error_log(" !!! CRM b4 get exam" );
        $this->exams_taken = $this->getExamsTaken($educationData);

        $this->degree_preference = $this->getDegreePreferences($prefData);
        $this->plan_to_start = $this->getPlanToStart($prefData) . " (as on " . $userArr['CreationDate'] . " )";
        $this->coursename = $specializationPrefData['CourseName'];
        $this->specialization = $specializationPrefData['SpecializationName'];
        $this->mode = $this->getMode($prefData);
        $this->source_of_funding = $this->getFunds($prefData);
        $this->preferred_time_to_call = $this->getPreferredTimeOfCall($prefData);
        $this->xii_std = $this->getEducationDetailsFor($educationData, "12");
        $this->ug_details = $this->getEducationDetailsFor($educationData, "UG");
        $this->responses = $this->getPastResponses($this->leadid);
        
        $locationPrefData = $userArr['PrefData'][0]['LocationPref'];
        $this->assigned_user_id = $this->getAllocatedUser($locationPrefData);
	$this->logininfo = "Created:".$userArr["CreationDate"]."Logins:".$this->getLoginInfo($userArr["userid"]);
        $this->pref_location = $this->getPreferredLocations($locationPrefData);
        $this->desired_countries = $this->getDesiredCountries($prefData, $locationPrefData);
    }

    private function getExamsTaken($educationData) {
        $ret = "";
        error_log("Education data = " . json_encode($educationData));
        $i = 0;
        foreach ($educationData as $edu) {
            if ($edu["Level"] == "Competitive exam") {
                $ret .= (($i++ > 0) ? ", " : "") . ucwords($edu["Name"]);
                if (isset($edu["Marks"]) && !empty($edu["Marks"])) {
                    $ret .= " - " . $edu["Marks"];
                }
            }
        }
        return $ret;
    }

    private function ifYes($param, $yes = "Yes") {
        return $param == $yes;
    }

    private function getDegreePreferences($prefDetails) {
        $degreePrefArray = array();
        if ($prefDetails['DegreePrefAny'] == 'yes')
            array_push($degreePrefArray, "Any");
        if ($prefDetails['DegreePrefUGC'] == 'yes')
            array_push($degreePrefArray, "UGC approved");
        if ($prefDetails['DegreePrefAICTE'] == 'yes')
            array_push($degreePrefArray, "AICTE approved");
        if ($prefDetails['DegreePrefInternational'] == 'yes')
            array_push($degreePrefArray, "Internatonal");
        $return = implode(", ", $degreePrefArray);
        return $return;
    }

    private function getPlanToStart($prefDetails) {
        $datediff = datediff($prefDetails['TimeOfStart'], $prefDetails['SubmitDate']);
        return ($prefDetails['YearOfStart'] != '0000') ? (($datediff != 0) ? "Within " . $datediff : "Immediately") : "Not Sure";
    }

    private function getMode($prefData) {
        $return = "";
        $flag = false;
        if ($this->ifYes($prefData["ModeOfEducationFullTime"], "yes")) {
            $return = "Full-Time";
            $flag = true;
        }
        if ($this->ifYes($prefData["ModeOfEducationPartTime"], "yes")) {
            $return .= ($flag ? ", " : "") . "Part-Time";
            $flag = true;
        }
        if ($this->ifYes($prefData["ModeOfEducationDistance"], "yes"))
            $return .= ($flag ? ", " : "") . "Distance";
        return $return;
    }

    private function getFunds($prefData) {
        return $this->ifYes($prefData["UserFundsOwn"]) ? "Own" : ($this->ifYes($prefData["UserFundsBank"]) ? "Bank" : ($this->ifYes($prefData["UserFundsNone"]) ? "None" : ""));
    }

    private function getEducationDetailsFor($educationData, $standard) {
        $ret = "";
        foreach ($educationData as $edu) {
            if ($edu["Level"] == $standard) {
                if ($edu["OngoingCompletedFlag"] == "0")
                    $ret = $edu["Marks"] . "% in " . ucwords($edu["Name"]);
                else
                    $ret = "Pursuing " . ucwords($edu["Name"]);
                if (isset($edu["Course_CompletionDate"]) && !empty($edu["Course_CompletionDate"])) {
                    $ret .= ", " . $edu["Course_CompletionDate"];
                }
            }
        }
        return $ret;
    }

    private function getPastResponses($userid) {
        $this->CI = &get_instance();
        $this->CI->load->library("CRM_Client");
        $responses = $this->CI->crm_client->getPastResponses($userid);
        $ret = "";
        foreach ($responses as $response) {
            $ret .= (($i++ > 0) ? "\n" : "") . $response["course"] . " - " . $response["institute"];
        }
        return $ret;
    }

    private function getAllocatedUser($locationPrefData) {
        $this->CI = &get_instance();
        $this->CI->load->library("CRM_Client");
        foreach($locationPrefData as $row){
            $prefArr = array($row["CountryId"],$row["StateId"],$row["CityId"],$row["LocalityId"]);
        }
        if(!isset($prefArr))return "";
        $response = $this->CI->crm_client->getAllocatedUser($prefArr);
        return $response;
    }

    private function getLoginInfo($userId) {
        $this->CI = &get_instance();
        $this->CI->load->library("CRM_Client");
        $response = $this->CI->crm_client->getLoginInfo(array($userId));
        return $response;
    }


    private function getPreferredTimeOfCall($prefData) {
        $preferenceCallTimeArray = array('0' => 'Do not call', '1' => 'Anytime', '2' => 'Morning', '3' => 'Evening');
        return $pref_time = (is_numeric($prefData['suitableCallPref'])) ? ($preferenceCallTimeArray[$prefData['suitableCallPref']]) : "";
    }

    private function getPreferredLocations($locationPrefData) {
        $cnt = count($locationPrefData);
        $pref_location = "";
        for ($i = 0; $i < $cnt; $i++) {
            if (!empty($locationPrefData[$i]['CityName']))
                $pref_location .= (($i > 0) ? ", " : "") . (!empty($locationPrefData[$i]['CityName']) ? $locationPrefData[$i]['CityName'] : "") . (!empty($locationPrefData[$i]['LocalityName']) ? "-" . $locationPrefData[$i]['LocalityName'] : "");
            else
                $pref_location .= (($i > 0) ? ", " : "") . "Anywhere in " . $locationPrefData[$i]['StateName'];
        }
        return $pref_location;
    }

    private function getDesiredCountries($prefData, $locationPrefData) {
        $cnt = count($locationPrefData);
        $desired_country = "";
        for ($i = 0; $i < $cnt; $i++) {
            if ($prefData['ExtraFlag'] == 'studyabroad')
                $desired_country .= (($i > 0) ? ", " : "") . $locationPrefData[$i]['CountryName'];
        }
        return $desired_country;
    }

    public function getArrayForSugar() {
        $parameters = array(
            array('name' => 'first_name', 'value' => $this->first_name),
            array('name' => 'last_name', 'value' => $this->last_name),
            array('name' => 'leadid_c', 'value' => $this->leadid),
            array('name' => 'phone_mobile', 'value' => $this->mobile),
            array('name' => 'email1', 'value' => $this->email),
            array('name' => 'is_ndnc_c', 'value' => $this->is_ndnc),
            array('name' => 'gender_c', 'value' => $this->gender),
            array('name' => 'age_c', 'value' => $this->age),
            array('name' => 'registered_on_c', 'value' => $this->registered_on),
            array('name' => 'current_location_c', 'value' => $this->current_location),
            array('name' => 'pref_location_c', 'value' => $this->pref_location),
            array('name' => 'coursename_c', 'value' => $this->coursename),
            array('name' => 'plan_to_start_c', 'value' => $this->plan_to_start),
            array('name' => 'mode_c', 'value' => $this->mode),
            array('name' => 'source_of_funding_c', 'value' => $this->source_of_funding),
            array('name' => 'desired_countries_c', 'value' => $this->desired_countries),
            array('name' => 'preferred_time_to_call_c', 'value' => $this->preferred_time_to_call),
            array('name' => 'specialization_c', 'value' => $this->specialization),
            array('name' => 'xii_std_c', 'value' => $this->xii_std),
            array('name' => 'ug_details_c', 'value' => $this->ug_details),
            array('name' => 'responses_c', 'value' => $this->responses),
            array('name' => 'work_experience_c', 'value' => $this->work_experience),
            array('name' => 'degree_preference_c', 'value' => $this->degree_preference),
            array('name' => 'exams_taken_c', 'value' => $this->exams_taken),
            array('name' => 'assigned_user_id', 'value' => $this->assigned_user_id),
	    array('name' => 'textpassword_c', 'value' => $this->textpassword),
	    array('name' => 'logininfo_c', 'value' => $this->logininfo),
        );


        return $parameters;
    }

}
