<?php
 
class FBLeadDataValidation
{
    /**
     * @var object CodeIgniter object
     */ 
    private $CI;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        $this->CI = & get_instance();
        $this->FBLeadModel = $this->CI->load->model('marketing/fbleadmodel');
    }

    /*
        Email First Name Last Name Isd Code + Mobile User City
        course Course location Work Exp
    */
    public function validateFBData($FBData = array()){
        $isValidFBData = true;
        $exceptionList = array();
        $FBLeadDataMapping = array();
        
        // check if campaign exist
        $campaignDetails = $this->FBLeadModel->getCampaignDetails($FBData['fb_form_id']);
        if(count($campaignDetails) > 0){
            $campaignDetails = $campaignDetails[0];

            // validate email
            if($this->_validateEmail($FBData['email']) == false){
                $isValidFBData = false;
                $exceptionList["email"] = $FBData['email'];
            }

            // validate mobile
            $response = $this->_validateMobile($FBData['phone_number']);
            if($response == false){
                $isValidFBData = false;
                $exceptionList["mobile"] = $FBData['phone_number'];
            }else{
                $FBLeadDataMapping = array_merge($FBLeadDataMapping, $response['FBLeadDataMapping']);
            }

            // validate first name
            if($this->_validateName($FBData['first_name']) == false){
                $isValidFBData = false;
                $exceptionList["first_name"] = $FBData['first_name'];
            }

            // validate last name
            if($this->_validateName($FBData['last_name']) == false){
                $isValidFBData = false;
                $exceptionList["last_name"] = $FBData['last_name'];
            }

            // validate user city
            $response = $this->_validateUserCity($FBData['city']);
            if($response == false){
                $isValidFBData = false;
                $exceptionList["city"] = $FBData['city'];
            }else{
                $FBLeadDataMapping['city'] = $response;
            }

            if(empty($FBData['course_name'])){
                $isValidFBData = false;
                $exceptionList["course_name_blank"] = $FBData['course_name'];
            }else{
                $response = $this->_validateCourseData($FBData, $campaignDetails);
                if($response['response'] == false){
                    $isValidFBData = false;
                    $exceptionList =array_merge($exceptionList, $response['exception']);
                }else{
                    $FBLeadDataMapping = array_merge($FBLeadDataMapping, $response['FBLeadDataMapping']);
                }
            }
        }else{
            $isValidFBData = false;
            $exceptionList["campaign_not_exist"] = $FBData['fb_form_id'];
        }

        $response = array();
        $response['validFBData'] = $isValidFBData;
        if($isValidFBData == false){
            $response['exceptionList'] = $exceptionList;
        }else{
            $response['FBLeadDataMapping'] = $FBLeadDataMapping;    
        }
        return $response;
    }

    private function _validateCourseData($FBData, $campaignDetails){
        $response = array();
        $response['response'] = true;

        $courseIds = $campaignDetails['course_ids'];
        $courseIds = explode("|", $courseIds);
        $courseObj = $this->_filterCourseObjForCourseName($courseIds, $FBData['course_name']);
        if(empty($courseObj) || $courseObj->getId() == ''){
            $response['response'] = false;
            $response['exception']["course_not_exist"] = $FBData['course_name'];
        }else{
            // check if course is hyper local course or not
            $isHyperLocalCourse = $this->checkIfHyperLocalCourse($courseObj);
            if($isHyperLocalCourse == false || $isHyperLocalCourse['hyperlocal'] ==1 ){
                $response['response'] = false;
                $response['exception']["course_locality"] = "";
            }else{
                $courseAttributes = $isHyperLocalCourse['courseAttributes'];
                $validCourseAttribute = true;
                if(empty($courseAttributes['baseCourse']) || $courseAttributes['baseCourse'] <=0){
                    $response['response'] = false;
                    $response['exception']["base_course"] = $courseAttributes['baseCourse'];
                    $validCourseAttribute = false;
                }

                if(empty($courseAttributes['mode']) || $courseAttributes['mode'] <=0){
                    $response['response'] = false;
                    $response['exception']["mode"] = $courseAttributes['mode'];
                    $validCourseAttribute = false;
                }

                if(empty($courseAttributes['mappedHierarchies']) || empty($courseAttributes['mappedHierarchies']['stream']) || $courseAttributes['mappedHierarchies']['stream'] <=0 ){
                    $response['response'] = false;
                    $response['exception']["stream"] = $courseAttributes['mappedHierarchies']['stream'];
                    $validCourseAttribute = false;
                }

                if($validCourseAttribute == true){
                    $response['FBLeadDataMapping']['courseAttributes'] =$isHyperLocalCourse['courseAttributes'];
                }
            }

            $validCourseLocation = $this->_validateCourseLocation($FBData, $courseObj, $campaignDetails);
            if($validCourseLocation == false){
                $response['response'] = false;
                $response['exception']["location"] = $FBData['course_location'];
            }else{
                $response['FBLeadDataMapping']['location'] = $validCourseLocation['courseLocation'];
                $response['FBLeadDataMapping']['locationLocality'] = $validCourseLocation['courseLocationLocality'];
            }

            $validWorkEx = $this->_validateWorkEx($FBData, $courseObj, $campaignDetails);

            if($validWorkEx == false && $validWorkEx !== 0){
                $response['response'] = false;
                $response['exception']["work_ex"] = $FBData['work_ex'];
            }else{
                
                if(($validWorkEx === -1) || ($validWorkEx ===1) || ($validWorkEx !== true)){
                    $response['FBLeadDataMapping']['workEx'] = (string)$validWorkEx;
                }
            }

            if($response['response'] == true){
                $response['FBLeadDataMapping']['courseId'] = $courseObj->getId();
            }
        }

        return $response;
    }

    public function checkIfHyperLocalCourse($courseObj){
        $FBLeadCommonLib = $this->CI->load->library("marketing/fbLead/FBLeadCommon");
        $data = $FBLeadCommonLib->getCourseAttributes($courseObj->getId(),$courseObj);
        if($data == false){
            return false;
        }
        $this->CI->load->library('user/UserLib');
        $userLib = new UserLib;
        $hyperLocalData = $userLib->getHyperAndNonhyperCoursesCount(array($data['baseCourse']));
        if($hyperLocalData['hyperlocal']>0){
            return array("hyperlocal" =>1);
        }else{
            return array("hyperlocal" =>0, 'courseAttributes' => $data);
        }
    }

    private function _validateWorkEx($FBData, $courseObj, $campaignDetails){
        $validWorkEx = false;
        $workExRequired = $this->getIfWorkExRequired($courseObj);
        if(empty($FBData['work_ex'])){
            $validWorkEx = ($workExRequired == false) ? true : false;
        }else{
            if($FBData['work_ex'] == "N.A." && $workExRequired == false){
                $validWorkEx = true;
            }else if($workExRequired == true){
                $workExFieldValues  = new \registration\libraries\FieldValueSources\WorkExperience;
                $workExList         = $workExFieldValues->getValues();

                $workExList = array_flip($workExList);
                if($workExList[$FBData['work_ex']] !== NULL){
                    $validWorkEx = $workExList[$FBData['work_ex']];
                }
            }
        }

        return $validWorkEx;
    }

    private function _validateCourseLocation($FBData, $courseObj, $campaignDetails){
        $validCourseLocation = false;
        $courseLocations                            = $courseObj->getLocations();
        if(!empty($FBData['course_location']) && $campaignDetails['fb_form_type'] == "location"){
            $localityCount = 0;
            //_p($courseLocations);die;
            foreach ($courseLocations as $locationId => $location) {
                if($location->getCityName() == $FBData['course_location']){
                    $courseLocation = $location->getCityId();;
                    $localityId = $location->getLocalityId();
                    $localityCount ++;
                }
            }

            if($localityCount == 1){
                $validCourseLocation['courseLocation'] = $courseLocation;
                if($localityId >0){
                    $validCourseLocation['courseLocationLocality'] = $localityId;    
                }
            }
        }else if($campaignDetails['fb_form_type'] == "without_location"){
            if($campaignDetails['city_id'] != -1){
                $localityCount = 0;
                //_p($courseLocations);die;
                foreach ($courseLocations as $locationId => $location) {
                    if($location->getCityId() == $campaignDetails['city_id']){
                        $courseLocation = $location->getCityId();
                        $localityId = $location->getLocalityId();
                        $localityCount ++;
                    }
                }

                if($localityCount ==1){
                    $validCourseLocation = array();
                    $validCourseLocation['courseLocation'] = $courseLocation;
                    if($localityId >0){
                        $validCourseLocation['courseLocationLocality'] = $localityId;    
                    }
                    
                }
            }else{
                if(count($courseLocations)==1){
                    foreach ($courseLocations as $locationId => $location) {
                        $validCourseLocation['courseLocation'] = $location->getCityId();
                        if($location->getLocalityId() >0){
                            $validCourseLocation['courseLocationLocality'] = $localityId;    
                        }
                    }
                }
            }
        }
        //var_dump($validCourseLocation);die;
        return $validCourseLocation;
    }

    private function _validateMobile($mobile){
        $response = false;
        $pattren = "/^([+][9][1])([6-9]{1})([0-9]{9})$/i";
        if(preg_match($pattren, $mobile)){
            $response['FBLeadDataMapping']['isdCode'] = INDIA_ISD_CODE;
            $response['FBLeadDataMapping']['mobile'] = str_replace("+91", "", $mobile);
        }

        return $response;
    }

    private function _validateEmail($email){
        if(strlen($email) <1 || strlen($email) > 125){
            return false;
        }
        
        $emailPattern = "/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/";
        if(!preg_match($emailPattern, $email)){
            return false;
        }

        return true;
    }

    private function _validateName($name){
        // validate first name
        if($this->validateProfanity($name) == false){
            return false;
        }

        $name = $this->_removeSpecialCharacters($name);
        if($this->_validateDisplayName($name, 50, 1) == false){
            return false;
        }

        return true;
    } 


    private function _removeSpecialCharacters($displayName) {        
        $displayName = preg_replace("/[(\n)\r\t\"\'\\\]/", " ", $displayName);
        $displayName = preg_replace("/[^\x20-\x7E]/", "", $displayName);

        return $displayName;
    }   

    private function _validateDisplayName($displayName, $maxLength, $minLength){
        $displayName = trim(stripcslashes($displayName));
        if($displayName == "" || in_array($displayName, array("Your Name", "Your First Name", "Your Last Name"))){
            return false;
        }

        if(strlen($displayName) < $minLength || strlen($displayName) > $maxLength){
            return false;
        }

        $pattern = "/^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/";
        if(preg_match($pattern, $displayName) == false){
            return false;
        }
        
        $displayName = preg_replace("/[(\n)\r\t\"\']/", " ", $displayName);
        $displayName = preg_replace("/[^\x20-\x7E]/", "", $displayName);
        $displayName = strtolower($displayName);

        $blacklistWords = file_get_contents("public/blacklisted.txt");
        $blacklistWords = explode(",", $blacklistWords);
        foreach ($blacklistWords as $blacklistWord) {
            $blacklistWord = trim($blacklistWord,"'");
            $flag = strpos($displayName, strtolower($blacklistWord));
            if($flag !== false &&$flag >=0){
                return false;
            }
        }

        return true;
    }

    function validateProfanity($displayName){
        $displayName = preg_replace("/[(\n)\r\t\"\']/", " ", $displayName);
        $displayName = preg_replace("/[^\x20-\x7E]/", "", $displayName);
        $response = $this->isProfane($displayName);
        return $response;
    }        

    function checkDisAllowedWord($string){
        $string =  preg_replace("/[^\x20-\x7E]/", "", $string);
        $disallowdWordsList = base64_decode("bWVyYWNhcmVlcmd1aWRlfG1lcmFjYXJlZXJndWlkfHJlYWNoQGluZHJhaml0LmlufHd3dy5pbmRyYWppdC5pbnwwOTgxMDIyNTExNA==");
        $disallowdWordsList = explode("|", $disallowdWordsList);
        foreach ($disallowdWordsList as $$disallowdWord) {
            if(strpos(strtolower($blacklistWord), $string) !== false){
                return false;
            }
        }        
        return true; 
    }

    function isProfane($string) {
        /* code start to avoid dissallowed chars */
        if($this->checkDisAllowedWord($string) == false){
            return false;
        }

        ob_start();
        include 'public/profanity/profanity.php';
        $profanceWords = ob_get_clean();
        $profanceWords = json_decode(base64_decode($profanceWords));
        
        $words = explode(" ", $string);
        foreach ($words as $word) {
            if(in_array(strtolower($word), $profanceWords)){
                return false;
            }
        }   
        
        return true;
    }

    private function _validateUserCity($city){
        $residenceCityLib = new  \registration\libraries\FieldValueSources\ResidenceCityLocality;
        $residenceCity = $residenceCityLib->getValues(array('removeVirtualCities'=>true));
        $response = $this->_checkIfFBCityExist($residenceCity, $city);
        if($response == false){
            $result = $this->FBLeadModel->checkCityInFBFieldMapping($city);
            if(count($result) >0 && !empty($result[0]['corrected_value'])){
                $response = $result[0]['corrected_value'];
            }
        }

        return $response;
    }

    private function _checkIfFBCityExist($residenceCity, $FBCity){
        $FBCity = strtolower($FBCity);
        foreach($residenceCity['stateCities'] as $statesData) {
            foreach($statesData['cityMap'] as $city) {
                if(strtolower($city['CityName']) == $FBCity){
                    return $city['CityId'];
                }
            }
        }
        return false;
    }

    private function _filterCourseObjForCourseName($courseIds, $courseName){

        $this->CI->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepo    = $courseBuilder->getCourseRepository();
        $courseObjects = $courseRepo->findMultiple($courseIds,array('basic', 'course_type_information', 'eligibility', 'location'),true);
        foreach ($courseObjects as $courseId => $course) {
            if($course->getName() == $courseName){
                return $course;
            }
        }
        return false;
    }

    function getIfWorkExRequired($course){
        $isExecutive                                = $course->isExecutive();
        $courseTypeInfo                             = $course->getCourseTypeInformation();
        $streamId                                   = '';
        if(is_object($courseTypeInfo['entry_course'])){
            $courseHierarchies = $courseTypeInfo['entry_course']->getHierarchies();
            $credential        = $courseTypeInfo['entry_course']->getCredential();
            $level             = $courseTypeInfo['entry_course']->getCourseLevel();
            foreach ($courseHierarchies as $key => $value) {
                if($value['primary_hierarchy'] == 1){
                    $streamId = $value['stream_id'];                        
                }
            }
        }

        global $managementStreamMR;
        global $postGrad;
        global $certificateCredential;
        if (!empty($isExecutive) || ($streamId == $managementStreamMR && $credential->getId() == $certificateCredential) || $level->getId() == $postGrad) {
            return true;
        }else{
            return false;
        }
    }
}
