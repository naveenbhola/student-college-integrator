<?php
/**
 * User Class
 * This is the class for all the APIs elated to User like Login, Register, Forgot Password, Rest Password
 * @date    2015-07-16
 * @author  Ankur Gupta
 * @todo    none
*/

class UserProfile extends APIParent {

	private $validationObj;
	private $userCommonLib;

	function __construct() {
		parent::__construct();
		$this->load->library(array('UserProfileValidationLib', 'UserCommonLib'));
		$this->validationObj = new UserProfileValidationLib();
	        $this->userCommonLib = new UserCommonLib();
		$this->userprofilemodel = $this->load->model('userprofilemodel');
	}


        /**
         * @desc API to get User profile Data (Top Card details + tab(about me or activity track) details)
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @return JSON string with HTTP Code 200 and list of Tags
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-13
         * @author Romil Goel
         */
        function getUserData($userId, $viewType = ''){

            //Step 1: Fetch the Input from Get/POST
            
            //Step 2: Validate all the fields
            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId)) ){
                    return;
            }

            //Step 3: Fetch the Data from DB + Logic
            $loggedInUserDetails = $this->getUserDetails();

            // get user's basic details
            $data                = $this->userCommonLib->getUserDetails($userId, $loggedInUserDetails['userId'], $viewType);

            if(empty($data) || ($data['userFlags'] && $data['userFlags']->getAbused() == '1')){
                $this->response->setStatusCode(STATUS_CODE_FAILURE);
                $this->response->setResponseMsg("User Not Found");
                $this->response->output();
                exit(0);
            }
            else{
                unset($data['userFlags']);
            }

            // get the tab details
            if(($userId == $loggedInUserDetails['userId'] || (!in_array("activitystats", $data['privateFields']) && $viewType == 'public')) && $viewType != 'public'){
                $data['selectedTab']   = "activityStats";
                $activityArray         = $this->userCommonLib->getUserActivitiesAndStats($userId, $loggedInUserDetails);
                $data['activityStats'] = $activityArray;
            }
            else{
                $data['selectedTab'] = "aboutMe";
                $data['aboutMeTab']  = $this->userCommonLib->getUserSectionDetails($userId, $loggedInUserDetails['userId'], '', array("personal", "education", "work", "expertise", "eduPref"), $viewType);
            }

            $this->response->setBody($data);

            //Step 4: Return the Response
            $this->response->output();
        }

        /**
         * @desc API to get User's section-wise data
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param sectionName is the section whose data needs to be fetched(leave it empty in case every section's data is needed)
         * @return JSON string with HTTP Code 200 and list of Tags
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-14
         * @author Romil Goel
         */
        function getUserSectionwiseDetails($userId, $sectionName = ''){

            //Step 1: Fetch the Input from Get/POST
            
            //Step 2: Validate all the fields
            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId)) ){
                    return;
            }

            //Step 3: Fetch the Data from DB + Logic
            $loggedInUserDetails = $this->getUserDetails();
            
            if($sectionName){
                $data = $this->userCommonLib->getUserSectionDetails($userId, $loggedInUserDetails['userId'], "", array($sectionName));
            }
            else{
                $data = $this->userCommonLib->getUserSectionDetails($userId, $loggedInUserDetails['userId']);
            }

            $this->response->setBody($data);

            //Step 4: Return the Response
            $this->response->output();
        }

        /**
         * @desc API to get Data for Personal Profile Form
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-14
         * @author Ankit Bansal
         */  
        public function getPersonalProfileFormData(){
            $data = array();

        $loggedInUserDetails = $this->getUserDetails();

        $data = $this->_personalData($loggedInUserDetails);
        
        $this->response->setBody($data);
        $this->response->output();
    }


    private function _personalData($loggedInUserDetails){
        
        $personalData = $this->userCommonLib->getUserSectionDetails($loggedInUserDetails['userId'], $loggedInUserDetails['userId'], "", array('personal'));
        $objn = new \registration\libraries\FieldValueSources\IsdCode;
        $isdCodes = $objn->getValues();
        $isdCodesFormatted = array();
        $cnt = 0;
        foreach ($isdCodes as $key => $value) {
            $isdCodesFormatted[$cnt]['key'] = $key;
            $isdCodesFormatted[$cnt]['value'] = $value;
            $cnt++;
        }
        //8776249 
       
    
        $data['email']        = $personalData['personalInfo']['email'];
        $data['mobile']       = $personalData['personalInfo']['mobile'];
        $data['dob']          = $personalData['personalInfo']['dob'];
        $data['isdCode']      = $personalData['personalInfo']['isdCode'];
        $data['levelId']      = $personalData['personalInfo']['levelId'];
        $data['socialLinks']  = $personalData['personalInfo']['socialLinks'];
        $data['shortbio']     = $personalData['personalInfo']['shortbio'];
        $data['aboutMe']      = $personalData['personalInfo']['aboutMe'];
        $data['isdCodesList'] = $isdCodesFormatted;
        $data['firstName']    = $personalData['personalInfo']['firstName'];
        $data['lastName']     = $personalData['personalInfo']['lastName'];

        $data['showCountry'] = false;
        if($data['isdCode'] != "91-2"){
            $data['showCountry'] = true;
        }
        $data['countryName'] = "";

        if(array_key_exists($data['isdCode'], $isdCodes)){

            list($c_name) = explode("(",$isdCodes[$data['isdCode']]);
            $data['countryName'] = trim($c_name);
        }

        /*$data['cityList'] = array(
                                    'popular' => $popularCityList,
                                    'others' => $otherCityList
                                );*/
        $data['selectedCity'] = array('cityId' => null,'cityName' => null);

        if($personalData['personalInfo']['cityId']){
            $data['selectedCity'] = array(
                                    'cityId' => $personalData['personalInfo']['cityId'],
                                    'cityName' => $personalData['personalInfo']['cityName']
                                    );
        }
        return $data;
    }

    public function submitPersonalFormData(){
        //var_dump($_POST);
        $loggedInUserDetails = $this->getUserDetails();
        $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';

        if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                return;
        }
        $email = $loggedInUserDetails['email'];
        $displayName = $loggedInUserDetails['displayName'];
        $dataToBeValidated = array();
        $mobile = trim($this->input->post('mobile'));
        $residenceCityLocality = trim($this->input->post('residenceCityLocality'));
        $dob = trim($this->input->post('dob'));
        $bio = trim($this->input->post('bio'));
        $facebookId = trim($this->input->post('facebookId'));
        $twitterId = trim($this->input->post('twitterId'));
        $linkedinId = trim($this->input->post('linkedinId'));
        $youtubeId = trim($this->input->post('youtubeId'));
        $personalURL = trim($this->input->post('personalURL'));
        $isd = trim($this->input->post('isdCode'));
        $aboutMe = trim($this->input->post('aboutMe'));
        $firstName = trim($this->input->post('firstName'));
        $lastName = trim($this->input->post('lastName'));
        

        if($mobile != ""){
            $dataToBeValidated['mobile'] = $mobile;
            $dataToBeValidated['isdCode'] = $isd;
        }        
        if($residenceCityLocality != ""){
            $dataToBeValidated['residenceCityLocality'] = $residenceCityLocality;
        }
        if($dob != ""){
            $dataToBeValidated['dob'] = $dob;   
        }
        else {
            unset($_POST['dob']);
        }
        if($bio != ""){
            $dataToBeValidated['bio'] = $bio;   
        }

        if($aboutMe != ""){
            $dataToBeValidated['aboutMe'] = $aboutMe;   
        }
        
         if($facebookId != ""){
            $dataToBeValidated['facebookId'] = $facebookId;   
        }
         if($twitterId != ""){
            $dataToBeValidated['twitterId'] = $twitterId;   
        }
         if($linkedinId != ""){
            $dataToBeValidated['linkedinId'] = $linkedinId;   
        }
         if($youtubeId != ""){
            $dataToBeValidated['youtubeId'] = $youtubeId;   
        }

        $dataToBeValidated['firstName'] = $firstName;
        $dataToBeValidated['lastName'] = $lastName;

        
        if(!empty($dataToBeValidated) && !$this->validationObj->validateUserPersonalDetails($this->response,$dataToBeValidated)){
            return;
        }
        
        $_POST['tracking_keyid'] = 697;
        Modules::run('registration/Registration/updateUser');
        $this->response->setAuthChecksum($userId, $email, $firstName, $lastName, $displayName);
        $data = $this->_personalData($loggedInUserDetails);
        $data['isdCodesList'] = null;
        $data['cityName'] = $data['selectedCity']['cityName'];

        $this->response->setBody($data);
        $this->response->setStatusCode(STATUS_CODE_SUCCESS);
        $this->response->setResponseMsg("Success");
        $this->response->output();
    }

        /**
         * @desc API to get tags followed by user
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param start value with the Starting counter of followed Tags
         * @param count value with the number of Tags followed
         * @return JSON string with HTTP Code 200 and list of Tags
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-14
         * @author Ankur Gupta
         */
        function getTagsFollowed($profileUserId, $start = 0, $count = 10){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                //step 2:validate all the fields
                if(! $this->validationObj->validateUserProfilePage($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'profileUserId'=>$profileUserId))){
                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
		$finalArray = array();
                $finalArray['tags'] = $this->userprofilemodel->findFollowingTags($profileUserId, $start, $count, $userId);
                $finalArray['rowsCount'] = $count;

                $this->response->setBody($finalArray);

                //Step 4: Return the Response
                $this->response->output();
        }


        /**
         * @desc API to get users I am following
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param start value with the Starting counter of followed Tags
         * @param count value with the number of Tags followed
         * @return JSON string with HTTP Code 200 and list of Users
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-14
         * @author Ankur Gupta
         */
        function getUsersIAmFollowing($profileUserId, $start = 0, $count = 10){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                //step 2:validate all the fields
                if(! $this->validationObj->validateUserProfilePage($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'profileUserId'=>$profileUserId))){
                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $finalArray = array();
                $finalArray['users'] = $this->userprofilemodel->getUsersIAmFollowing($profileUserId, $userId, $start, $count);
                $finalArray['rowsCount'] = $count;

                $this->response->setBody($finalArray);

                //Step 4: Return the Response
                $this->response->output();
        }


        /**
         * @desc API to get users following me
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param start value with the Starting counter of followed Tags
         * @param count value with the number of Tags followed
         * @return JSON string with HTTP Code 200 and list of Users
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-14
         * @author Ankur Gupta
         */
        function getUsersFollowingMe($profileUserId, $start = 0, $count = 10){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                //step 2:validate all the fields
                if(! $this->validationObj->validateUserProfilePage($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'profileUserId'=>$profileUserId))){
                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $finalArray = array();
                $finalArray['users'] = $this->userprofilemodel->getUsersFollowingMe($profileUserId, $userId, $start, $count);
                $finalArray['rowsCount'] = $count;

                $this->response->setBody($finalArray);

                //Step 4: Return the Response
                $this->response->output();
        }

        /**
         * @desc API to Data for Education Details Form
         * @param AuthChecksum containing the logged-in user details
         * @return JSON string with HTTP Code 200 and list of levels and Educational Details
         * @date 2015-10-14
         * @author Ankit Bansal
         */
        public function getEducationDetailsFormData($mode='add'){
            $data = array();
            $loggedInUserDetails = $this->getUserDetails();

            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';

            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                    return;
            }

            $educationalData = $this->userCommonLib->getUserSectionDetails($loggedInUserDetails['userId'], $loggedInUserDetails['userId'], "", array('education'));
           
        
            $this->load->config('UserProfileBuilderConfig');

            $configData = $this->config->item('profileBuilderData');
            $tempArray = $configData['userProfileCustomCourseLevel'];
           
            if($mode == "add"){

                $tempData = $educationalData['educationalInfo'];
                $levelFilled = array();
                foreach ($tempData as $key => $value) {
                    $levelFilled[] = $value['levelText'];
                }

                $levelActual = array_keys($tempArray);

                foreach ($levelActual as $key => $value) {
                    if(in_array($value, $levelFilled)){
                        unset($tempArray[$value]);
                    }
                }    
            }
            
            

            $levelArray = array();
            $tenthBoardArray = array();
            foreach ($tempArray as $key => $value) {
                $levelArray[] = array(
                                    'key' => $key,
                                    'value' => $value
                                    );
            }

            $tempBoardsArray = $configData['tenthBoard'];
            foreach ($tempBoardsArray as $key => $value) {
                $tenthBoardArray[] = array(
                                    'key' => $key,
                                    'value' => $value
                                    );
            }
            $data['levels'] = $levelArray;
            $data['tenthBoard'] = $tenthBoardArray;
            $data['educationDetails'] = $educationalData['educationalInfo'];

            $this->response->setBody($data);

            //Step 4: Return the Response
            $this->response->output();

        }

        /**
         * @desc API to get User activity Stats
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param start value with the Starting counter of followed Tags
         * @param count value with the number of Tags followed
         * @return JSON string with HTTP Code 200 and list of Activities and Stats
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-14
         * @author Ankur Gupta
         */
        function getUserActivitiesAndStats($profileUserId, $start = 0, $count = 10){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                //step 2:validate all the fields
                if(! $this->validationObj->validateUserProfilePage($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'profileUserId'=>$profileUserId))){
                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $activityArray = $this->userCommonLib->getUserActivitiesAndStats($profileUserId, $userId, $start, $count);
                $activityArray['rowsCount'] = $count;

                $this->response->setBody($activityArray);

                //Step 4: Return the Response
                $this->response->output();
        }

        public function submitEducationDetailsFormData(){
            $data = (array)json_decode($_POST['educationInfo']);
            $formattedData = (array)$data['educationDetails'];
            $allArrays = array();
            foreach ($formattedData as $key => $value) {                
                
                    $level = trim($key);
                    $_POST['EducationBackground'][] = $level;
                    
                    if($value->delete === false){
                        switch ($level) {

                            case 'xth':                                
                                $_POST['xthSchool'] = $value->instituteName;
                                $_POST['xthCompletionYear'] = $value->courseCompletionYear;                                
                                break;

                            case 'xiith':                                
                                $_POST['xiithSchool'] = $value->instituteName;
                                $_POST['xiiSpecialization'] = $value->specialization;
                                $_POST['xiiYear'] = $value->courseCompletionYear;                                
                                break;

                            case 'bachelors':                                
                                $_POST['bachelorsDegree'] = $value->degreeName;                                
                                $_POST['bachelorsCollege'] = $value->instituteName;                               
                                $_POST['bachelorsSpec'] = $value->specialization;
                                $_POST['graduationCompletionYear'] = $value->courseCompletionYear;                                
                                break;  

                            case 'masters':                                
                                $_POST['mastersDegree'] = $value->degreeName;                                
                                $_POST['mastersCollege'] = $value->instituteName;                                
                                $_POST['mastersSpec'] = $value->specialization;
                                $_POST['mastersCompletionYear'] = $value->courseCompletionYear;                                
                                break;

                            case 'phd':                                
                                $_POST['phdDegree'] = $value->degreeName;                            
                                $_POST['phdCollege'] = $value->instituteName;                            
                                $_POST['phdSpec'] = $value->specialization;
                                $_POST['phdCompletionYear'] = $value->courseCompletionYear;                                
                                break;

                            default:
                                # code...
                                break;
                        }
                        unset($_POST['educationInfo']);


                    }else {
                        $allArrays[] = $level;
                    } 
            }
            $_POST['isUnifiedProfile'] = 'yes';
            
            $data = array();
            $loggedInUserDetails = $this->getUserDetails();
            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';

            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                    return;
            }

            $educationDetailsArray = array_filter($this->input->post('EducationBackground'));

            if(! $this->validationObj->validateUserLevelDetails($this->response, array('EducationBackground' => $educationDetailsArray))) {
                    return;
            }

            $_POST['tracking_keyid'] = 697;
            
            Modules::run('registration/Registration/updateUser');
           


            $this->load->config('UserProfileBuilderConfig');
            $configData = $this->config->item('profileBuilderData');            
            
            $possibleValues = $configData['privateFieldstoDBMapping'];
            
            $finalArray = $allArrays;
            $publicArray = array();

            if(!empty($finalArray)){
                foreach ($finalArray as $value) {
                    $temp = $possibleValues[$value];
                    $publicArray = array_merge($publicArray,$temp);
                }
                
                $_POST['fieldIds'] = $publicArray;
                $_POST['userId'] = $userId;

                Modules::run('userProfile/UserProfileController/setUserFieldPublic');
            }

            

            $eduData = $this->userCommonLib->getUserSectionDetails($loggedInUserDetails['userId'], $loggedInUserDetails['userId'], "", array('education'));
            $data['educationDetails'] = $eduData['educationalInfo'];
            $data['privateFields'] = $this->userprofilemodel->getUsersPrivacySettings($userId);
            $this->response->setStatusCode(STATUS_CODE_SUCCESS);
            $this->response->setBody($data);
            $this->response->setResponseMsg("Success");
            $this->response->output();

        }


        /**
         * @desc API to get Question Information
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param start value with the Starting counter
         * @param count value with the number
         * @return JSON string with HTTP Code 200 and list of Questions
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-14
         * @author Ankur Gupta
         */
        function getQuestionsByCategory($profileUserId, $category, $start = 0, $count = 10){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                //step 2:validate all the fields
                if(! $this->validationObj->validateUserProfileContentPage($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'profileUserId'=>$profileUserId, 'category'=>$category))){
                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $finalArray = array();
                $finalArray['questions'] = $this->userprofilemodel->getQuestionsByCategory($profileUserId, $userId, $category, $start, $count);
                $finalArray['rowsCount'] = $count;

                $this->response->setBody($finalArray);

                //Step 4: Return the Response
                $this->response->output();
        }

        /**
         * @desc API to get Discussion Information
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param start value with the Starting counter
         * @param count value with the number
         * @return JSON string with HTTP Code 200 and list of Discussions
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-14
         * @author Ankur Gupta
         */
        function getDiscussionsByCategory($profileUserId, $category, $start = 0, $count = 10){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId = isset($Validate['userId'])?$Validate['userId']:'';

                //step 2:validate all the fields
                if(! $this->validationObj->validateUserProfileContentPage($this->response, array('userId'=>$userId, 'start'=>$start, 'count'=>$count,'profileUserId'=>$profileUserId, 'category'=>$category))){
                        return;
                }

                //Step 3: Fetch the Data from DB + Logic
                $finalArray = array();
                $finalArray['discussions'] = $this->userprofilemodel->getDiscussionsByCategory($profileUserId, $userId, $category, $start, $count);
                $finalArray['rowsCount'] = $count;

                $this->response->setBody($finalArray);

                //Step 4: Return the Response
                $this->response->output();
        }

        /**
         * @desc API to update user's privacy settings
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param entityType value containing string - tag
         * @param followType value containing string specifies which entity is getting followed
         * @param status value containing string (action which in this case is follow)
         * @param entityId value containing integer - tag id
         * @return JSON string with HTTP Code 200 with success message
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-16
         * @author Ankit Bansal
         */
        public function updateFollowFieldsForUser() {

            $this->universalmodel = $this->load->model('common/UniversalModel');
            $loggedInUserDetails = $this->getUserDetails();
            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';

            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                    return;
            }
            /*
                Stream / Field - stream
                Country - country
                Course Level - course_level
                Stream Of Interest - stream_interest
                Spec -  specialization
                Degree / Diploma --- degree
                Countries Of Interest - countries_interest
                Cities Interest India - cities_interest
             */

            $entityType = trim($this->input->post('entityType'));   // tag
            $followType = trim($this->input->post('followType'));  
            
            $status = trim($this->input->post('status')); // follow
            $entityIds = trim($this->input->post('entityIds')); // tag_id

            if(! $this->validationObj->validateFollowFieldsForProfile($this->response, array('entityType'=>$entityType,'followType'=>$followType,'status'=>$status,'entityIds'=>$entityIds))) {
                    return;
            }

            $entityIds = explode(",", $entityIds);
            foreach ($entityIds as $entityId) {
                $this->universalmodel->followEntity($userId,$entityId,$entityType,$status,$followType);   
            }

            if($followType == 'stream_interest' && $status == 'follow'){
                $this->userCommonLib->sendStreamDigestMailByTagIds($userId,$entityIds);
            }

            $this->response->setStatusCode(STATUS_CODE_SUCCESS);
            $this->response->setResponseMsg("Success");
            $this->response->output();

        }

         /**
         * @desc API to update user's privacy settings
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param field value containing string whose settings needs to be changed
         * @param isPrivate value containing string - YES / NO
         * @return JSON string with HTTP Code 200 with success message
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-16
         * @author Ankit Bansal
         */
        public function setDataPrivacySettings(){

            $loggedInUserDetails = $this->getUserDetails();
            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';

            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                    return;
            }

            $field = trim($this->input->post('field'));
            $isPrivate = trim($this->input->post('isPrivate'));
            $order = trim($this->input->post('order'));

        
            if(! $this->validationObj->validateUserPrivacyBasicDetails($this->response, array('field'=>$field,'isPrivate' =>$isPrivate))) {
                    return;
            }

            $_POST['userId'] = $userId;
            $fields = array();

            if($field == "workexperience") {
                $tempFields = array();
                $tempFields[] = "EmployerworkExp".$order;
                $tempFields[] = "DesignationworkExp".$order;
                $tempFields[] = "DepartmentworkExp".$order;
                $tempFields[] = "CurrentJobworkExp".$order;
            }
            else {
                        $this->load->config('UserProfileBuilderConfig');
                        $configData = $this->config->item('profileBuilderData');            
                        $allowedPrivacyFieldArray = $configData['privateFieldstoDBMapping'];
                        $tempFields = $allowedPrivacyFieldArray[$field];
                
            }
            if(is_array($tempFields))
            {
                $_POST['fieldIds'] = $tempFields;
                if($isPrivate == 'YES'){
                    Modules::run('userProfile/UserProfileController/setUserFieldPrivate');
                }else{
                    Modules::run('userProfile/UserProfileController/setUserFieldPublic');
                }    
            } 
            else {
                $this->userprofilemodel->updatePrivacySettings($userId,$field,$isPrivate);  
            }

            $this->response->setStatusCode(STATUS_CODE_SUCCESS);
            if($isPrivate == 'NO'){
                $this->response->setResponseMsg("This field is now visible to everyone.");    
            }
            else{
                $this->response->setResponseMsg("This field is now visible only to you.");    
            }
            $this->response->output();


        }

        /**
         * @desc API to update user's about me details
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param aboutMe value containing string having user's about me details
         * @return JSON string with HTTP Code 200 with success message
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-16
         * @author Romil Goel
         */
        public function updateUsersAboutMeData(){
            
            $loggedInUserDetails = $this->getUserDetails();
            
            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';
            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                    return;
            }

            $dataToBeValidated = array();
            $aboutMe = trim($this->input->post('aboutMe'));
            
            // update user's details
            Modules::run('registration/Registration/updateUser');
            $this->response->setStatusCode(STATUS_CODE_SUCCESS);
            $this->response->setResponseMsg("Success");
            $this->response->output();
        }

        /**
         * @desc API to upload User's profile pic
         * @param AuthChecksum containing the logged-in user details
         * @return JSON string with HTTP Code 200 with success message
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-19
         * @author Romil Goel
         */
        public function uploadProfilePhoto(){

            $loggedInUserDetails = $this->getUserDetails();

            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';
            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                    return;
            }

            $errorOccured = 0;
            $errorMsg = "Uploading Failed";
            if($_FILES['myImage']['tmp_name'][0] == ''){
                $errorOccured = 1;
            }
            else{
                $this->load->library('Upload_client');
                $uploadClient = new Upload_client();    
                $upload = $uploadClient->uploadFile(1,'image',$_FILES,array(),$userId,"user", 'myImage');

                if(!is_array($upload)) {
                    $errorOccured = 1;
                    $errorMsg = $upload;
                }
                else{
                    $_POST['avtarimageurl'] = $upload[0]['imageurl'];
                    // update user's details
                    Modules::run('registration/Registration/updateUser');
                }

                // re-index user
                $this->load->model('user/usermodel');
                $usermodel = new usermodel();
                $usermodel->addUserToIndexingQueue($userId);
            }

            if($errorOccured){
                $response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_NOT_FOUND);
                $response->setResponseMsg($errorMsg);
                $response->output();
                exit(0);
            }
            else{
                $this->response->setBody(array("url" => $upload[0]['imageurl']));
            }

            $this->response->setResponseMsg("Profile Pic Changed Successfully.");
            $this->response->output();
        }

        /**
         * @desc API to update user's about me details
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param workExperience value containing integer user's total work experience
         * @return JSON string with HTTP Code 200 with success message
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-16
         * @author Ankit Bansal
         */        
        public function updateTotalWorkExperienceInYears(){
            $loggedInUserDetails = $this->getUserDetails();
            
            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';
            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                    return;
            }

            $workExperience = trim($this->input->post('workExperience'));
            if(! $this->validationObj->validateTotalExpericeYears($this->response, array('workExperience'=>$workExperience))) {
                    return;
            }

            $_POST['tracking_keyid'] = 697;
            Modules::run('registration/Registration/updateUser');
            $this->response->setStatusCode(STATUS_CODE_SUCCESS);
            $this->response->setResponseMsg("Success");
            $this->response->output();
        }

         /**
         * @desc API to add/update user's work experience details
         * @param AuthChecksum containing the logged-in user details
         * @param userId value with the user who's profile I am viewing
         * @param employer value containing array of employer details
         * @param designation value containing array of designation details
         * @param department value containing arary of department details
         * @param currentJob value containing array of Is current job details
         * @param workExp value containing array denoting size of total companies
         * @return JSON string with HTTP Code 200 with success message
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2015-10-16
         * @author Ankit Bansal
         */    
        public function addDetailWorkExperience(){

            $loggedInUserDetails = $this->getUserDetails();
             
            $userId = isset($loggedInUserDetails['userId'])?$loggedInUserDetails['userId']:'';
            if(! $this->validationObj->validateUserBasicDetails($this->response, array('userId'=>$userId))) {
                    return;
            }

            if(isset($_POST['mode']) && ($_POST['mode'] == 'add')){
               
                $this->_addNewWorkExp();
            } else {
                $this->_editWorkExp($userId);
            }
            
            $workData = $this->userCommonLib->getUserSectionDetails($loggedInUserDetails['userId'], $loggedInUserDetails['userId'], "", array('work'));

            $data['workExperienceInfo'] = $workData['workExperienceInfo'];
            $this->response->setStatusCode(STATUS_CODE_SUCCESS);
            $this->response->setResponseMsg("Success");
            $this->response->setBody($data);
            $this->response->output();

        }

        /**
        * Helper functions for Add New work experience
        */
        private function _addNewWorkExp(){


            $workExperienceInfo = (array)json_decode($_POST['workExperienceInfo']);
            

            $_POST['isNewWorkExp'] = 1;

            $employer = array();
            $designation = array();
            $department = array();
            $currentJob = array();
            $workExp = array();
            

            foreach ($workExperienceInfo['workExp'] as $key => $individualWorkExpInfo) {
                $employer[] = $individualWorkExpInfo->employer;
                $designation[] = $individualWorkExpInfo->designation;
                $department[] = $individualWorkExpInfo->department;
                $currentJob[] = $individualWorkExpInfo->isCurrentJob;
                
                $workExp[] = 1;               
            }

            $_POST['employer'] = $employer;
            $_POST['designation'] = $designation;
            $_POST['department'] = $department;
            $_POST['currentJob'] = $currentJob;
            $_POST['workExp'] = $workExp;

            unset($_POST['workExperienceInfo']);
            
            $employer = $this->input->post('employer');
            $designation = $this->input->post('designation');
            $department = $this->input->post('department');
            $currentJob = $this->input->post('currentJob');
            $workExp = $this->input->post('workExp');

            $dataToBeValidated['employer'] = array_filter($employer);    
            $dataToBeValidated['currentJob'] = $currentJob;
            $dataToBeValidated['workExp'] = array_filter($workExp);

            if(! $this->validationObj->validateWorkExpAddDetails($this->response,$dataToBeValidated)) {
                    die;
            }
            $_POST['tracking_keyid'] = 697;
            Modules::run('registration/Registration/updateUser');
           
        }

        /**
        * Helper functions for Edit Work Experience for User
        */
        private function _editWorkExp($userId){
        
             $workExperienceInfo = (array)json_decode($_POST['workExperienceInfo']);
           
            $employer = array();
            $designation = array();
            $department = array();
            $currentJob = array();
            $workExp = array();
            $isPrivateArray = array();
            $flag = false;
            $this->userprofilemodel->resetUserWorkExperienceSettings($userId);

            foreach ($workExperienceInfo['workExp'] as $key => $individualWorkExpInfo) {
                $employer[] = $individualWorkExpInfo->employer;
                $designation[] = $individualWorkExpInfo->designation;
                $department[] = $individualWorkExpInfo->department;
                $currentJob[] = $individualWorkExpInfo->isCurrentJob;
                $isPrivateArray[] = $individualWorkExpInfo->isPrivate;
                $workExp[] = 1;               
                $flag = true;
            }
            if(!$flag){
                $workExp[] = 1;
                $_POST['workExp'] = $workExp;
            } else{
                $_POST['employer'] = $employer;
                $_POST['designation'] = $designation;
                $_POST['department'] = $department;
                $_POST['currentJob'] = $currentJob;
                $_POST['workExp'] = $workExp;    
            }

            

            unset($_POST['workExperienceInfo']);
          

            $employer = $this->input->post('employer');   

            $currentJob = $this->input->post('currentJob');
            $workExp = $this->input->post('workExp');
           if( is_array($employer) && count($employer) != 0){
                $dataToBeValidated['employer'] = array_filter($employer);    
            }
    
            $dataToBeValidated['currentJob'] = $currentJob;
            $dataToBeValidated['workExp'] = array_filter($workExp);

            if(! $this->validationObj->validateWorkExpAddDetails($this->response,$dataToBeValidated)) {
                    die;
            }
            $_POST['tracking_keyid'] = 697;
            Modules::run('registration/Registration/updateUser');

            $fields = array();

            foreach ($isPrivateArray as $key => $value) {
                if($value == "YES") {
                    $cnt = $key+1;
                    $fields[] = "EmployerworkExp".$cnt;
                    $fields[] = "DesignationworkExp".$cnt;
                    $fields[] = "DepartmentworkExp".$cnt;
                    $fields[] = "CurrentJobworkExp".$cnt;
                }   
            } // foreach

            if(!empty($fields)){
                $_POST['userId'] = $userId;
                $_POST['fieldIds'] = $fields;    
                Modules::run('userProfile/UserProfileController/setUserFieldPrivate');
            }
            
            
        }


        public function citiesList(){
            $responseData = array();
            $responseData['data'] = array();

            $objn = new \registration\libraries\FieldValueSources\ResidenceCity;
            $cityList = $objn->getValues();
            $citiesByStates = $cityList['citiesByStates'];

            $popularCityList = array();
            $otherCityList = array();

            $count = 0;
            $listSimplified = array();
            foreach ($cityList['tier1Cities'] as $key => $value) {
               $popularCityList[] = array(
                                                'id' => $value['cityId'],
                                                'label' => $value['cityName']
                                                );
               $listSimplified[] = $value['cityId'];
            }

        
             
           foreach($citiesByStates as $list) {
                foreach($list['cityMap'] as $city) {
                    
                    if(!in_array($city['CityId'], $listSimplified)){
                        $arr_Sort[] = $city['CityName'];    
                        $otherCityList[] = array(
                                                'id' => $city['CityId'],
                                                'label' => $city['CityName']
                                                );
                    }
                    
                }
            }
            
            array_multisort($arr_Sort,SORT_ASC,$otherCityList);   
            unset($arr_Sort);
            $responseData['data'][0] = array(
                    'header' => 'POPULAR CITIES',
                    'searchItems' => $popularCityList

                );
            $responseData['data'][1] = array(
                    'header' => 'OTHER CITIES',
                    'searchItems' => $otherCityList

                );
            $this->response->setBody($responseData);
            $this->response->output();
        }


}

?>

