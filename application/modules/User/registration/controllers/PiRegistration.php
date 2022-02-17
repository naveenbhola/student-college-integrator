<?php
/**
 * File for handling user registration
 */ 

/**
 * This class is used to do user registration
 */ 
class PiRegistration extends MX_Controller
{
    /**
     * Register a new user using data submitted by user in a registration form
     */ 
    public function register()
    {
        $this->load->model('user/usermodel');
        $userModel = new usermodel;
        
        $this->load->library('user/UserLib');
        $userLib = new UserLib;
        
        $sdata = $this->getSalvage();
        $ct = 1;
        
        foreach($sdata as $data) {
            //$data = $_POST;
            //_p($data);
            $response = array();
            
            /**
             * Check if user with this email id already exists
             */ 
            if($user = $userModel->getUserByEmail($data['email'])) {
                $response['status'] = 'USER_ALREADY_EXISTS';
                $userFlags = $user->getFlags();
                $response['isLDBUser'] = $userFlags->getIsLDBUser();
            }
            else { 
                if($this->_validateData($data)) {    
                    try {
                        $data = $this->_preprocessRegistrationData($data);
                        if($data['desiredCourse'] || $data['fieldOfInterest']) {
                            $redirector = new \registration\libraries\PostRegistrationRedirector($data);
                            $redirectURL = $redirector->getRedirectionURL();
                            $response['redirectURL'] = $redirectURL;
                            $data['landingPage'] = $redirectURL;
                        }
                        
                        $user = $userLib->createUser($data); 
                        $userLib->loginUser($user);
                        $response['status'] = 'SUCCESS';
                        $response['userId'] = $user->getId();
                    }
                    catch(Exception $e) {
                        error_log($e->getMessage());
                        $response['status'] = 'FAIL';
                        $response['error'] = $e->getMessage();
                        //_p($e->getTrace());
                    }
                }
                else {
                    $response['status'] = 'FAIL';
                    $response['error'] = 'VALIDATION';
                }
            }
            echo $ct++." -- ".$data['email']." -- ".json_encode($response)."<br /><br />";
        }
    }
    
    /**
     * Update details of an existing user
     */ 
    public function updateUser()
    {
        $this->init();
       	$this->load->helper('security');
        $data = xss_clean($_POST);
        
        if(!$data['userId']) {
            $loggedInUserData = $this->getLoggedInUserData();
            $data['userId'] = $loggedInUserData['userId'];
        }
        
        if($this->_validateData($data)) {
            $this->load->library('user/UserLib');
            $userLib = new UserLib;
            try {
                $data = $this->_preprocessRegistrationData($data);
                $user = $userLib->updateUser($data);
                /**
                 * Invalidate user login cache
                 */ 
                $this->load->library('user/Login_client');
                $this->login_client->invalidateUserLoginCache();
                $userLib->loginUser($user);
                $redirector = new \registration\libraries\PostRegistrationRedirector($data);
                $redirectURL = $redirector->getRedirectionURL();
                echo json_encode(array('status' => 'SUCCESS','userId' => $data['userId'],'redirectURL' => $redirectURL));
            }
            catch(Exception $e) {
                echo json_encode(array('status' => 'FAIL', 'error' => $e->getMessage()));
            }
        }
        else {
            echo json_encode(array('status' => 'FAIL', 'error' => 'VALIDATION'));
        }
    }
    
    /**
     * Function to print the details of institute.
     */
    public function findInstitute()
    {
        $this->load->helper('security');
        $data = xss_clean($_POST);
        $data = $this->_preprocessRegistrationData($data);
        $redirector = new \registration\libraries\PostRegistrationRedirector($data);
        $redirectURL = $redirector->getRedirectionURL();
        $response = array();
        $response['status'] = 'SUCCESS';
        $response['redirectURL'] = $redirectURL;
        echo json_encode($response);
    }
    
    /**
     * Preprocess registration data so that it can be passed to user creation/updation module
     * @param array $data Array Holding the pre-processing data
     */ 
    private function _preprocessRegistrationData($data)
    {
        /**
         * Preferred study location is a mixed array of cities and states
         * e.g. ['C:45','C:110','S:2','S:45','C:12']
         * Extract cities and states into separate arrays
         */ 
        if($data['context'] == 'mobile') {
            if(count($data['preferredStudyLocation']) && (isset($data['preferredStudyLocation'][0]) && $data['preferredStudyLocation'][0]!='' ) ){
                $preferredStudyLocations = $data['preferredStudyLocation'];
                $preferredStudyLocations = explode(",",$preferredStudyLocations[0]);
            }
            else{
                unset($data['preferredStudyLocation']);
            }
            
            if(count($data['destinationCountry']) && (isset($data['destinationCountry'][0]) && $data['destinationCountry'][0]!='' )){
                $data['destinationCountry'] = explode(",",$data['destinationCountry'][0]);
            }
            else{
                unset($data['destinationCountry']);
            }
        }
        else{
            $preferredStudyLocations = $data['preferredStudyLocation'];
        }

        if(is_array($preferredStudyLocations) && count($preferredStudyLocations)) {
            $data['preferredStudyLocation']['states'] = array();
            $data['preferredStudyLocation']['cities'] = array();
            
            foreach($preferredStudyLocations as $location) {
                list($prefix,$locationId) = explode(':',$location);
                if($prefix == 'S') {
                    $data['preferredStudyLocation']['states'][] = $locationId;
                }
                else {
                    $data['preferredStudyLocation']['cities'][] = $locationId;
                }
            }
        }

        if($data['registrationType'] == 'short') {
            unset($data['desiredCourse']);
            unset($data['categoryId']);
            unset($data['studyAbroad']);
        }
        
        if($data['fieldOfInterest'] == 14) {
            $data['isTestPrep'] = 'yes';
        }
        return $data;
    }
    
    /**
     * Validate registration data
     * Data is validated based on the registration form type submitted
     *
     * @param array $data
     * @return bool
     */ 
    private function _validateData($data)
    {
        return $this->_getForm($data)->validate($data);
    }
    
    /**
     * Get form based on data submitted
     *
     * @param array $data
     * @return object \registration\libraries\Forms\AbstractForm
     */ 
    private function _getForm($data)
    {
        $mmpFormId = (int) $data['mmpFormId'];
        $desiredCourse = $data['desiredCourse'];
        $context = $data['context'];
        $registrationType = $data['registrationType'];
        $secondLayer = $data['secondLayer'];
        $fieldOfInterest = $data['fieldOfInterest'];
        
        if($secondLayer == 1) {
            $userData = $this->getLoggedInUserData();
            return \registration\builders\RegistrationBuilder::getRegistrationForm('SecondLayer',$userData);
        }
        
        if($registrationType == 'short') {
            return \registration\builders\RegistrationBuilder::getLDBForm(array('context' => 'shortRegistration'));        
        }
        
        if($mmpFormId) {
            return \registration\builders\RegistrationBuilder::getMMPForm(array('mmpFormId' => $mmpFormId,'desiredCourseId' => $desiredCourse));
        }
        
        if($data['isStudyAbroad'] == 'yes') {
            return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => 'studyAbroad','context' => $context));
        }
        
        return \registration\builders\RegistrationBuilder::getLDBForm(array('desiredCourseId' => $desiredCourse,'context' => $context,'fieldOfInterest' => $fieldOfInterest));
    }
    
     /**
     * Validate registration captcha
     *
     * @param string $securityCode Captcha as filled by user
     */ 
    public function validateSecurityCode()
    {
        $securityCodeVar = $this->input->post('securityCodeVar');
        $securityCode = $this->input->post('securityCode');
        
        if(verifyCaptcha($securityCodeVar,$securityCode)) {
            echo "correct";
        }
        else {
            echo "incorrect";
        }
    }
    
    /**
     * Get category page landing URL for an LDB user
     *
     * @param integer $userId
     */
    public function getUserCategoryLandingPage($userId)
    {
        $this->load->library('user/UserLib');
        $userLib = new UserLib;
        echo $userLib->getLandingPageURL($userId);
    }

    /**
     * Function to add Data(URL) based on the exam name
     */
    public function addExamScore()
    {
        $loggedInUserData = $this->getLoggedInUserData();
        if($loggedInUserData['userId']) {
            $this->load->model('user/usermodel');
            $this->usermodel->addUserEducation($loggedInUserData['userId'],$this->input->post('exam_name', true),$this->input->post('exam_score', true),$this->input->post('exam_score_type', true));
        }
        
        $redirectionMap = array(
            'CMAT' => '/top-mba-colleges-in-india-accepting-cmat-score-rankingpage-2-2-0-0-5822',
            'NMAT' => '/top-mba-colleges-in-india-accepting-nmat-score-rankingpage-2-2-0-0-3275',
            'MAT'  => '/top-mba-colleges-in-india-accepting-mat-score-rankingpage-2-2-0-0-306',
            'CAT'  => '/top-mba-colleges-in-india-accepting-cat-score-rankingpage-2-2-0-0-305',
            'XAT'  => '/top-mba-colleges-in-india-accepting-xat-score-rankingpage-2-2-0-0-309',
            'SNAP'  => '/top-mba-colleges-in-india-accepting-snap-score-rankingpage-2-2-0-0-307'
        );
        
        header('Location: '.$redirectionMap[$this->input->post('exam_name', true)]);
    }
    
    /**
     * Function to load the Download Comfirmation Form
     *
     * @param int $mmpId Marketing Form Id
     */
    public function downloadConfirmation($mmpId)
    {
        $this->load->model('customizedmmp/customizemmp_model');
        $mmpMailerDetails = $this->customizemmp_model->getMMPMailer($mmpId);
        if(trim($mmpMailerDetails['download_confirmation_message'])) {
            $this->load->view('registration/downloadConfirmationMessage',array('msg' => trim($mmpMailerDetails['download_confirmation_message'])));
        }
    }
    
    /**
     *
     */
    public function getSalvage()
    {
        $keys = array('desiredCourse','whenPlanToStart','graduationStatus','graduationDetails','graduationMarks','graduationCompletionYear','xiiYear','xiiMarks','preferredStudyLocalityCity','preferredStudyLocality','workExperience','firstName','lastName','email','mobile','residenceCity','securityCode','securityCodeVar','regFormId','mmpFormId','isStudyAbroad','isTestPrep','userId','registrationSource','referrer','pageReferer','fieldsView','resolution','coordinates');    

        $lines = file('/home/vikas/Desktop/leads_friday.txt');
        $allData = array();
        $emailData = array();
        $k = 1;
        foreach($lines as $line) {
            
            $postData = array();
            
            list(,$postDataLine) = explode('Post:"Array',$line);
            $postDataLine = str_replace('\n','',$postDataLine);
            //echo $k++." -- ".$postDataLine."<br /><br />";
            
            for($j=0;$j<count($keys);$j++) {
                $key = $keys[$j];
                list(,$value) = explode('['.$key.'] => ',$postDataLine);
                
                $minStrPos = 100000;
                foreach($keys as $rkey) {
                    
                    $keyPos = intval(strpos($value,'['.$rkey.'] => '));
        
                    if($keyPos > 0 && $keyPos < $minStrPos) {
                        $minStrPos = $keyPos;
                        $nextKey = $rkey;
                    }
                }
                
                list($mainValue,) = explode('['.$nextKey.']',$value);
                $mainValue = trim($mainValue);
                
                if($key == 'coordinates') {
                    list($mainValue,) = explode(')',$mainValue);
                }
                else if($key == 'preferredStudyLocalityCity' || $key == 'preferredStudyLocality') {
                    $mainValue = $this->text_to_array($mainValue);
                }
                
                $postData[$key] = $mainValue;
            }
            //echo "<pre>".print_r($postData,TRUE)."</pre>";
            //exit();
            
            $allData[] = $postData;
            $emailData[$postData['email']]++;
            //break;
        }
        
        //echo "<pre>".print_r($emailData,TRUE)."</pre>";
        //echo count($emailData);
        //exit();
        
        return $allData;
    }
    
    /**
     * Function to convert the input text to array
     * @param string $str Input String
     *
     * @return array $output Output Converted Array
     */
    function text_to_array($str) {
        //Initialize arrays
        $keys = array();
        $values = array();
        $output = array();
    
        //Is it an array?
        if( substr($str, 0, 5) == 'Array' ) {
    
            //Let's parse it (hopefully it won't clash)
            $array_contents = substr($str, 7, -2);
            $array_contents = str_replace(array('[', ']', '=>'), array('#!#', '#?#', ''), $array_contents);
            $array_fields = explode("#!#", $array_contents);
    
            //For each array-field, we need to explode on the delimiters I've set and make it look funny.
            for($i = 0; $i < count($array_fields); $i++ ) {
    
                //First run is glitched, so let's pass on that one.
                if( $i != 0 ) {
    
                    $bits = explode('#?#', $array_fields[$i]);
                    if( $bits[0] != '' ) $output[$bits[0]] = trim($bits[1]);
    
                }
            }
            //Return the output.
            return $output;
        } else {
            //Duh, not an array.
            //echo 'The given parameter is not an array.';
            return array();
        }
    }
}