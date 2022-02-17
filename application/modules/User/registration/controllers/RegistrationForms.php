<?php

/**
 * Class RegistrationForms Responsible for handling all the registration form related activity
 *
 * @author Shiksha LDB Team
 */

class RegistrationForms extends MX_Controller
{
    public function _init()
    {

    }

    /* API to load registration form fields according to the context and coursegroup
     * @Params: $courseGroup:   Key to identify the type of registration form. It seperates all the fields and rules around the fields in the registration form.
     *          $context:   Key to load required View.
     *          $customFormData:    Array having all the required data(Pre-filled data) which needs to be sent in the registration view.
     */
    private function _getLDBFields($courseGroup = 'nationalDefault', $context = 'default', $customFormData = array())
    {
        $data                = array();
        $data['context']     = $context;
        $data['courseGroup'] = $courseGroup;

        if(empty($customFormData['regFormId'])){
            $this->load->helper('string');
            global $mmpFormsForCaching;
            if(!empty($customFormData['customFields']['mmpFormId']) && in_array($customFormData['customFields']['mmpFormId'], $mmpFormsForCaching)){
                $data['regFormId'] = "mp".$customFormData['customFields']['mmpFormId'];
            }
            else{
                $data['regFormId'] = random_string('alnum', 6);
            }
        }else{
            $data['regFormId'] = $customFormData['regFormId'];
        }

        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB', array('courseGroup' => $courseGroup, 'context' => $context));

        $data['fields'] = $registrationForm->getFields();

        $data['customRules'] = json_encode($registrationForm->getRules());

        $data['registrationHelper'] = new \registration\libraries\RegistrationHelper($data['fields'], $data['regFormId']);

        $data['customFormData'] = $customFormData;

        $skipInterestDetails = true;
        if($customFormData['showInterestPrefilled'] == 'yes'){
            $skipInterestDetails = false;
        }

        $isMMP = false;
        // if($customFormData['customFields']['isMMP'] == 'yes'){
        //     $isMMP = true;
        // }

        $loggedInUserDetails = $this->getLoggedInUserDetails($skipInterestDetails, $isMMP);
        
        if (empty($customFormData['customFields'])) {
            $customFormData['customFields'] = array();
        }

        if (!empty($loggedInUserDetails)) {
            $customFormData['customFields']['isUserLoggedIn'] = 'yes';
        }
        
        $data['customFormData']['customFields'] = array_merge($customFormData['customFields'], $loggedInUserDetails);

        if(empty($data['customFormData']['customFields']['isdCode']['value'])){
            $data['customFormData']['customFields']['isdCode']['value'] = \registration\libraries\RegistrationHelper::getUserCountryByIP();
        }

        $data['trackingKeyId'] = $customFormData['trackingKeyId'];

        if (!empty($customFormData['customFields']['responseAction'])) {
            $data['responseAction'] = $customFormData['customFields']['responseAction'];
        }

        if($customFormData['showOTPOnly'] == 'yes' && $customFormData['clientCourseId'] > 0){
            $resTempData                   = array();
            $resTempData['clientCourse']   = $customFormData['clientCourseId'];
            $resTempData['listing_type']   = $customFormData['listingType'];
            $resTempData['prefCity']       = null;
            $resTempData['prefLocality']   = null;
            $resTempData['tracking_keyid'] = $customFormData['trackingKeyId'];
            $resTempData['action_type']    = $customFormData['customFields']['actionType'];
            $resTempData['regFormId']      = $data['regFormId'];

            $loggedInUserData = $this->checkUserValidation();
            $userId           = $loggedInUserData[0]['userid'];
            $this->load->library('user/UserLib');
            $userLib                       = new UserLib;
            $userLib->storeTempResponseInterestData($resTempData,$userId);
        }
        return $data;
    }

    /* Function to get the HTML of registration form based on the context and form type(Only for Desktop)
     * @Params: $courseGroup:    Key to identify the type of registration form. It seperates all the fields and rules around the fields in the registration form.
     *            $context:    Key to load required View.
     *            $customFormData:    Array having all the required data(Pre-filled data) which needs to be sent in the registration view.
     */
    public function LDB($courseGroup = 'nationalDefault', $context = 'default', $customFormData = array())
    {
        if($context == 'signUp' && $customFormData['customFields']['stream']['value'] > 0 && (!empty($customFormData['customFields']['baseCourses']['value'])) && (!empty($customFormData['customFields']['educationType']['value'])) && $customFormData['customFields']['stream']['hidden'] == 1 && $customFormData['customFields']['baseCourses']['hidden'] == 1 && $customFormData['customFields']['educationType']['hidden'] == 1 && $customFormData['customFields']['subStreamSpec']['hidden'] == 1) {

            if((!is_array($customFormData['customFields']['baseCourses']['value'])) && $customFormData['customFields']['baseCourses']['value'] >0) {
                $customFormData['customFields']['baseCourses']['value'] = array($customFormData['customFields']['baseCourses']['value']);
            }
            if((!is_array($customFormData['customFields']['educationType']['value'])) && $customFormData['customFields']['educationType']['value'] >0) {
                $customFormData['customFields']['educationType']['value'] = array($customFormData['customFields']['educationType']['value']);
            }

            if($customFormData['customFields']['baseCourses']['value'][0] > 0 && $customFormData['customFields']['educationType']['value'][0] > 0) {

                $data = $this->_getLDBFields($courseGroup, $context, $customFormData);
                $this->load->view('registration/signUpDirectSecondScreen/form', $data);
            } else {

                $data = $this->_getLDBFields($courseGroup, $context, $customFormData);
                $this->load->view('registration/' . $context . '/form', $data);
            }

        } else {
            $data = $this->_getLDBFields($courseGroup, $context, $customFormData);
            $this->load->view('registration/' . $context . '/form', $data);
        }
        
    }

    /* Function to get the HTML of registration form based on the context and form type(Only for Domestic Mobile Site)
     * @Params: $courseGroup:   Key to identify the type of registration form. It seperates all the fields and rules around the fields in the registration form.
     *          $context:   Key to load required View.
     *          $customFormData:    Array having all the required data(Pre-filled data) which needs to be sent in the registration view.
     */
    public function mobileLDB($courseGroup = 'nationalDefault', $context = 'default', $formData = array())
    {
        $data = $this->_getLDBFields($courseGroup, $context, $formData['formCustomData']);
        if (!empty($formData['submitButtonText'])) {
            $data['submitButtonText'] = $formData['submitButtonText'];
        }

        if (!empty($formData['httpReferer'])) {
            $data['httpReferer'] = $formData['httpReferer'];
        }

        if (!empty($formData['formHeading'])) {
            $data['formHeading'] = $formData['formHeading'];
        }

        if (!empty($formData['courseDDLabel'])) {
            $data['courseDDLabel'] = $formData['courseDDLabel'];
        }

        $data['customHelpText']           = $formData['customHelpText'];
        $data['registrationShikshaStats'] = $formData['registrationShikshaStats'];

        if($formData['hideCss']){
            $data['hideCss'] = true;
        }
        $data['countryName'] = strtoupper($_SERVER['GEOIP_COUNTRY_NAME']);

        $returnData                 = array();
        if($context == 'signUpMobileNew') {
            $data['registrationHelper']       = new \registration\libraries\RegistrationHelper($data['fields'], $data['regFormId']);
            $returnData['directSecondScreen'] = $this->load->view('registration/' . $context . '/directSecondScreenForm', $data, true);
            $returnData['regFormId']          = $data['regFormId'];
        } else {
            $returnData['firstScreen']  = $this->load->view('registration/' . $context . '/firstScreenForm', $data, true);
            $returnData['secondScreen'] = $this->load->view('registration/' . $context . '/secondScreenForm', $data, true);
        }

        echo json_encode($returnData);
    }

    /* API to load Registration form through ajax with all form customizations
     * @Params:
     * customHelpText  [POST] : [Array] having communication of the registration form
     * trackingKeyId   [POST] : [INT] having tracking key id
     * customFields    [POST] : [Array] having all the possible customizations of the registration form
     * callbackFunction[POST] : [String] name of the js call back funtion
     * callbackFunctionParams[POST] : [Array] having parameters of the callback function
     * showFormWithoutHelpText[POST]: [String] a unique key to identify form on a page
     */
    public function showRegisterFreeLayer()
    {
        $data = array();
        if(!empty($_SERVER['QUERY_STRING'])){
            return false;
        }

        $customHelpText = $this->input->post('customHelpText', true);
        if (is_array($customHelpText)) {
            $data['customHelpText'] = $customHelpText;
        } else {
            $data['customHelpText'] = json_decode($customHelpText, true);
        }

        $data['customHelpText'] = $this->_getCustomHelpText($data['customHelpText']);

        $data['formCustomData']['trackingKeyId']           = $this->input->post('trackingKeyId', true);
        $data['formCustomData']['customFields']            = $this->input->post('customFields', true);
        $data['formCustomData']['customFields']            = $data['formCustomData']['customFields'] != 'null' ? $data['formCustomData']['customFields'] : array();
        $data['formCustomData']['callbackFunction']        = $this->security->xss_clean($this->input->post('callbackFunction', true));
        $data['formCustomData']['callbackFunctionParams']  = $this->input->post('callbackFunctionParams', true);
        $data['formCustomData']['customFieldValueSource']  = $this->input->post('customFieldValueSource', true);
        $data['formCustomData']['registrationIdentifier']  = $this->security->xss_clean($this->input->post('registrationIdentifier', true));
        $data['formCustomData']['showFormWithoutHelpText'] = $this->input->post('showFormWithoutHelpText', true);
        $data['formCustomData']['showOTPOnly']             = $this->input->post('showOTPOnly', true); // this flag is for desktop only
        $data['showFormWithoutHelpText']                   = $this->input->post('showFormWithoutHelpText', true);
        $data['submitButtonText']                          = $this->input->post('submitButtonText', true);
        $data['httpReferer']                               = $this->security->xss_clean($this->input->post('httpReferer', true));
        $data['registrationShikshaStats']                  = $this->loadRegistrationStatLayer();
        $data['countryName']                               = strtoupper($_SERVER['GEOIP_COUNTRY_NAME']);

        if(!empty($data['formCustomData']['customFields']['isMMP']) && $data['formCustomData']['customFields']['isMMP'] == 'yes'){
            // $data['formCustomData']['showInterestPrefilled'] = 'yes';
        }

        if($data['formCustomData']['customFields']['isMMP']){
            $data['hideCss'] = true;
        }

        if(PREF_YEAR_HIDDEN) {
            $data['formCustomData']['customFields']['prefYear']['hidden'] = 1;
        } else {
            $data['formCustomData']['customFields']['prefYear']['hidden'] = 0;
        }
        
        $data['formCustomData']['customFieldValueSource'] = json_decode($data['formCustomData']['customFieldValueSource'], true);
        if (isMobileRequest()) {

            if($data['formCustomData']['customFields']['stream']['value'] > 0 && (!empty($data['formCustomData']['customFields']['baseCourses']['value'])) && (!empty($data['formCustomData']['customFields']['educationType']['value'])) && $data['formCustomData']['customFields']['stream']['hidden'] == 1 && $data['formCustomData']['customFields']['baseCourses']['hidden'] == 1 && $data['formCustomData']['customFields']['educationType']['hidden'] == 1 && $data['formCustomData']['customFields']['subStreamSpec']['hidden'] == 1) {

                if((!is_array($data['formCustomData']['customFields']['baseCourses']['value'])) && $data['formCustomData']['customFields']['baseCourses']['value'] >0) {
                    $data['formCustomData']['customFields']['baseCourses']['value'] = array($data['formCustomData']['customFields']['baseCourses']['value']);
                }
                if((!is_array($data['formCustomData']['customFields']['educationType']['value'])) && $data['formCustomData']['customFields']['educationType']['value'] >0) {
                    $data['formCustomData']['customFields']['educationType']['value'] = array($data['formCustomData']['customFields']['educationType']['value']);
                }

                if($data['formCustomData']['customFields']['baseCourses']['value'][0] > 0 && $data['formCustomData']['customFields']['educationType']['value'][0] > 0) {
                    echo $this->mobileLDB('nationalDefault', 'signUpMobileNew', $data);
                } else {
                    echo $this->mobileLDB('nationalDefault', 'signUpMobile', $data);
                }

            } else {
                echo $this->mobileLDB('nationalDefault', 'signUpMobile', $data);
            }
        } else {
            echo $this->load->view('signUp', $data);
        }
        return;
    }

    /* API to load Response form through ajax with all form customizations
     * @Params:
     * customHelpText  [POST] : [Array] having communication of the registration form
     * trackingKeyId   [POST] : [INT] having tracking key id
     * customFields    [POST] : [Array] having all the possible customizations of the registration form
     * callbackFunction[POST] : [String] name of the js call back funtion
     * callbackFunctionParams[POST] : [Array] having parameters of the callback function
     * showFormWithoutHelpText[POST]: [String] a unique key to identify form on a page
     */
    public function showResponseForm()
    {
        #removing strips tags from Post data
        foreach ($_POST as $key => $value) {
            if ($key = 'submitButtonText'){
                continue;
            }
            else{
                $stripValue = strip_tags($value);
                if ($_POST[$key] != $stripValue)
                    return false;
            }
        }
        $data = array();

        $data['clientCourseId'] = $this->input->post('clientCourseId', true);
        if(!is_numeric($data['clientCourseId'])){
            return false;
        }

        $customHelpText = $this->input->post('customHelpText', true);
        if (is_array($customHelpText)) {
            $data['customHelpText'] = $customHelpText;
        } else {
            $data['customHelpText'] = json_decode($customHelpText, true);
        }

        $data['customHelpText'] = $this->_getCustomHelpText($data['customHelpText']);
        
        $data['actionType']                               = $this->input->post('actionType', true);

        $len = strlen($data['actionType']);

        $data['actionType'] = strip_tags($data['actionType']);

        if($len!==strlen($data['actionType']) || preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $data['actionType']))
        {
            return;
        }
        $data['listingType']                              = $this->input->post('listingType', true);
        $data['formCustomData']['mappedFieldValues']      = $this->input->post('mappedFieldValues', true);
        $data['formCustomData']['trackingKeyId']          = $this->input->post('trackingKeyId', true);

        if(empty($data['formCustomData']['trackingKeyId']) || $data['formCustomData']['trackingKeyId'] == ''){
            mail('mansi.gupta@shiksha.com','Tracking Key ID empty','All POST Data : '.print_r($_POST, true));
            mail('naveen.bhola@shiksha.com','Tracking Key ID empty','All POST Data : '.print_r($_POST, true));
        }
        $data['formCustomData']['customFields']           = $this->input->post('customFields', true);
        $data['formCustomData']['callbackFunction']       = $this->input->post('callbackFunction', true);
        $data['formCustomData']['callbackFunctionParams'] = $this->input->post('callbackFunctionParams', true);
        $data['formCustomData']['registrationIdentifier'] = $this->input->post('registrationIdentifier', true);
        $data['formCustomData']['showOTPOnly']            = $this->input->post('showOTPOnly', true);
        $data['showFormWithoutHelpText']                  = $this->input->post('showFormWithoutHelpText', true);
        $data['submitButtonText']                         = $this->input->post('submitButtonText', true);
        $data['httpReferer']                              = $this->input->post('httpReferer', true);
        $data['formHeading']                              = $this->input->post('formHeading', true);
        $data['courseDDLabel']                            = $this->input->post('courseDDLabel', true);
        $data['formCustomData']['listingType']            = $data['listingType'];
        $data['countryName']                              = strtoupper($_SERVER['GEOIP_COUNTRY_NAME']);

        $userDetails    = $this->getLoggedInUserData();
        $data['userId'] = $userDetails['userId'];
        $data['formCustomData']['customFields']['responseAction'] = $data['actionType'];
        $data['formCustomData']['customFields']['actionType']     = $data['actionType'];
        $data['registrationShikshaStats']                 = $this->loadRegistrationStatLayer();

        if(PREF_YEAR_HIDDEN) {
            $data['formCustomData']['customFields']['prefYear']['hidden'] = 1;
        } else {
            $data['formCustomData']['customFields']['prefYear']['hidden'] = 0;
        }

        if($data['listingType'] == 'exam'){

            $this->load->builder('ExamBuilder','examPages');
            $examBuilder    = new ExamBuilder();
            $examRepository = $examBuilder->getExamRepository();
            $examGroupObj   = $examRepository->findGroup($data['clientCourseId']);
            if(empty($examGroupObj)){
                return false;
            }
            $examId       = $examGroupObj->getExamId();
            $examBasicObj = $examRepository->find($examId);
            if(empty($examBasicObj)){
                return false;
            }
            $groupMappedArr = $examBasicObj->getGroupMappedToExam();
            if(count($groupMappedArr) > 1){
                $data['formCustomData']['groupMappedArr'] = $groupMappedArr;
            }
            
            $data['formCustomData']['clientCourseId'] = $data['clientCourseId'];
            $data['formType']                         = 'examResponse';
            
            if(isMobileRequest()){
                 echo $this->mobileLDB('nationalDefault', 'nationalExamResponseMobile', $data);
            }else{
                echo $this->load->view('nationalExamResponse', $data);
            }

        } else { 

            if($data['listingType'] != 'course') {
                $data['formCustomData']['institute_courses'] = $this->_extract_courses_from_institute($data['clientCourseId'], $data['listingType']);
            }
            
            $data['formCustomData']['clientCourseId'] = $data['clientCourseId'];
            $data['formType'] = 'response';


            if(isMobileRequest()){
                echo $this->mobileLDB('nationalDefault', 'nationalResponseMobile', $data);
            }else{
                echo $this->load->view('nationalResponse', $data);
            }

        }

    }

    public function _extract_courses_from_institute($institute_id, $listingType = 'institute', $isPWACall = false)
    {
        $data = array();

        if($listingType != 'university'){
            $listingType = 'institute';
        }
        $this->load->library("nationalInstitute/InstituteDetailLib");
        $lib                   = new InstituteDetailLib();
        $universityHierarchies = $lib->getInstituteCourseIds($institute_id, $listingType, "all", array(), false);
        $courseList            = array();
        $instituteList         = array();

        foreach($universityHierarchies['instituteWiseCourses'] as $institute=>$courses){
            $instituteList[] = $institute;
            $courseList = array_merge($courseList, $courses);
        }
        
        $this->load->model('registrationmodel');
        $courseDetails    = $this->registrationmodel->getClientCourseNameById($courseList);
        $InstituteDetails = $this->registrationmodel->getInstituteNameById($instituteList);
        
        $this->load->library("listingCommon/ListingCommonLib");
        $listingCommonLib   = new ListingCommonLib();
        $instituteViewCount = $listingCommonLib->listingViewCount('institute', $instituteList);
        
        // for pwa keys cannot be id of course/institute because that breaks the sorting order in JSON response of Ajax call
        if(!$isPWACall) {
            $immediateRelation  = array();
            
            if(!empty($instituteViewCount)) {

                foreach ($instituteViewCount as $instituteId => $viewCount) {
                    
                    $immediateRelation[$instituteId]['name'] = $InstituteDetails[$instituteId];
                    $courses                                 = $universityHierarchies['instituteWiseCourses'][$instituteId];
                    $courseViewCount                         = $listingCommonLib->listingViewCount('course', $courses);

                    if(!empty($courseViewCount)) {
                        foreach($courseViewCount as $courseId => $courseVC){
                            $immediateRelation[$instituteId]['courses'][$courseId] = $courseDetails[$courseId];
                        }
                    } else {
                        foreach($courses as $key => $value){
                            $immediateRelation[$instituteId]['courses'][$value] = $courseDetails[$value];
                        }
                    }

                }

            } else {

                foreach($universityHierarchies['instituteWiseCourses'] as $institute => $courses){

                    $immediateRelation[$institute]['name'] = $InstituteDetails[$institute];
                    
                    foreach($courses as $key => $value){
                        $immediateRelation[$institute]['courses'][$value] = $courseDetails[$value];
                    }

                }

            }
            
            return $immediateRelation;
            
        } else {

            $orderedArray       = array();
            $counter            = 0;

            if(!empty($instituteViewCount)) {

                foreach ($instituteViewCount as $instituteId => $viewCount) {
                    
                    $courses                        = $universityHierarchies['instituteWiseCourses'][$instituteId];
                    $courseViewCount                = $listingCommonLib->listingViewCount('course', $courses);
                    $orderedArray[$counter]['id']   = $instituteId;
                    $orderedArray[$counter]['name'] = $InstituteDetails[$instituteId];
                    $courseCounter                  = 0;
                    
                    if(!empty($courseViewCount)) {
                        foreach($courseViewCount as $courseId => $courseVC){
                            $orderedArray[$counter]['courses'][$courseCounter]['id']   = $courseId;
                            $orderedArray[$counter]['courses'][$courseCounter]['name'] = $courseDetails[$courseId];
                            $courseCounter++;
                        }
                    } else {
                        foreach($courses as $key => $value){
                            $orderedArray[$counter]['courses'][$courseCounter]['id']   = $value;
                            $orderedArray[$counter]['courses'][$courseCounter]['name'] = $courseDetails[$value];
                            $courseCounter++;
                        }
                    }

                    $counter++;

                }

            } else {

                foreach($universityHierarchies['instituteWiseCourses'] as $institute => $courses){

                    $orderedArray[$counter]['id']   = $institute;
                    $orderedArray[$counter]['name'] = $InstituteDetails[$instituteId];
                    $courseCounter = 0;
                    
                    foreach($courses as $key => $value){
                        $orderedArray[$counter]['courses']['id']   = $value;
                        $orderedArray[$counter]['courses']['name'] = $courseDetails[$value];
                        $courseCounter++;
                    }

                    $counter++;

                }

            }
            
            return $orderedArray;

        }

    }

    /**
     * Function to Show reset password layer
     *
     * @param String $id
     * @param String $useremail
     * @param String $registration_context
     */
    public function showResetPasswordLayer($id, $useremail, $usergroup)
    {

        $data['uname']     = $id;
        $data['useremail'] = base64_decode($useremail);
        $data['fbuser'] = false;

        if(base64_decode($usergroup) == 'fbuser'){
            $data['fbuser'] = true;
        }

        $this->load->view('resetPassword', $data);

    }

    public function showResetPasswordLayerMobile()
    {
        $data['uname']     = $this->input->post('uname');
        $data['useremail'] = base64_decode($this->input->post('useremail'));
        $data['usergroup'] = base64_decode($this->input->post('usergroup'));
        echo $this->load->view('muser5/forgot', $data);
    }

    public function showOTPLayerMobile()
    {
        $data['regFormId']        = $this->input->post('regformid',true);
        $data['isNewUser']        = $this->input->post('isNewUser',true);
        $data['formHeading']      = $this->input->post('formHeading',true);
        
        $data['showOnlyOTP']      = $this->input->post('showOnlyOTP',true);
        $data['dumpResponseData'] = $this->input->post('dumpResponseData',true);
        
        $data['mobile']           = $this->input->post('mobile',true);
        $data['isdCode']          = $this->input->post('isdCode',true);
        $data['isdCode']          = explode('-', $data['isdCode']);
        $data['isdCode']          = $data['isdCode'][0];
        $data['email']            = $this->input->post('email',true);
        $data['trackingKeyId']    = $this->input->post('trackingKeyId',true);

        if($data['showOnlyOTP'] == 'yes'){
            $userDetails     = $this->checkUserValidation();
            $userDetails     = $userDetails[0];

            if(empty($data['mobile']) || empty($data['isdCode']) || empty($data['email'])) {
                $data['mobile']  = $userDetails['mobile'];
                $data['isdCode'] = $userDetails['isdCode'];
                $cookiestr       = explode("|", $userDetails['cookiestr']);
                $data['email']   = $cookiestr[0];
            }

            $field = new \registration\libraries\RegistrationFormField('IsdCode');
            $valueSource = \registration\builders\RegistrationBuilder::getFieldValueSource('IsdCode');
            $field->setValueSource($valueSource);
            $isdCodeMapping = $field->getValues(array('source'=>'DB'));
            $data['isdCodeMapping'] = $isdCodeMapping[$userDetails['isdCode'].'-'.$userDetails['country']];

            if($data['dumpResponseData'] == 'yes') {
                // dump temp response data
                $response_data = $this->input->post('responseDataToDump',true);
                if(is_array($response_data) && count($response_data) >0){
                    $response_data['user_id'] = $userDetails['userid'];
                    $response_data['visitor_session_id'] = getVisitorSessionId();

                    $this->load->model('response/responsemodel');
                    $responseModel = new ResponseModel();
                    $responseModel->storeResponseInterestData($response_data);
                }
            }

        }

        $returnData = array();
        $returnData['showMisdCallVfyLayer'] =  false;

        $this->load->config('registration/registrationFormConfig');
        if(USE_MISSED_CALL_VERIFICATION){
            $returnData['showMisdCallVfyLayer'] =  true;
            global $missedCallNumber;
            $data['missedCallNumber'] = $missedCallNumber;

            global $missedCallParams;
            $missedCallParams = $missedCallParams['moblie'];

            $returnData['missCallLayer'] = $this->load->view('common/OTP/missCallMobileLayer', $data,true);
            $returnData['otpSuccessLayer'] = $this->load->view('common/OTP/otpSuccessLayer', $data,true);
            $returnData['misdCallFields'] = array('totalTimeForMisdCall' => $missedCallParams['totalTimeForMisdCall'], 'interval' => $missedCallParams['waitingTimeInterval'], 'MisdCallVfyCallInterval'=>$missedCallParams['MisdCallVfyCallInterval']);
        }
        $returnData['otpLayer'] = $this->load->view('common/OTP/otpVerificationMobile', $data,true);
        echo json_encode($returnData);
        return;
    }

    public function showLayerForMobChange()
    {
        $data['regFormId'] = $this->input->post('regformid');
        echo $this->load->view('common/OTP/changeMobileForOTP', $data);
    }

    /**
     * Function to Show reset password thanks layer
     *
     * @param string $context
     */
    public function showResetPasswordThanksLayer()
    {
        $this->load->view('resetPasswordThanks', $data);
    }

    public function getFormByStream()
    {
        $streamId = $this->input->post('streamId');
        $this->load->model('registrationmodel');

        $flow = $this->registrationmodel->getRegistrationStreamFlow($streamId);

        $this->loadFieldsAccordingToFlow($flow, $streamId);
    }

    public function loadFieldsAccordingToFlow($flow, $streamId)
    {

        if (isset($_POST['flow'])) {
            $flow = $this->input->post('flow');
        }

        if (isset($_POST['streamId'])) {
            $streamId = $this->input->post('streamId');
        }

        if (empty($flow)) {
            $flow = 'both';
        }

        $data              = array();
        $data['flow']      = $flow;
        $data['streamId']  = $streamId;
        $data['regFormId'] = $this->security->xss_clean($this->input->post('regFormId'));

        if (empty($data['streamId']) || $data['streamId'] <= 0) {
            return false;
        }

        $customFieldValueSource = $this->input->post('customFieldValueSource');
        if(!empty($customFieldValueSource)){
            $data['customFieldValueSource'] = $customFieldValueSource;
        }

        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB', array('courseGroup' => 'nationalLead'));
        $data['fields']   = $registrationForm->getFields();

        // $this->load->model('registrationmodel');
        // $data['isSpecMand'] = $this->registrationmodel->getSubStreamSpecFieldsRule($streamId); // isSpecMand is 'no' for all streams; hence this query isn't needed
        // if (empty($data['isSpecMand'])) {
            $data['isSpecMand'] = 'no';
        // }

        $data['registrationHelper'] = new \registration\libraries\RegistrationHelper($data['fields'], $data['regFormId']);

        if (isMobileRequest()) {
            $viewPath = 'registration/fields/mobile/variable/';
        } else {
            $viewPath = 'registration/fields/LDB/';
        }
        global $streamWiseFlowSplit;
        foreach ($streamWiseFlowSplit[$flow] as $key => $viewName) {
            echo $this->load->view($viewPath . $viewName, $data, true);
        }
    }

    public function getSubStreamSpecFieldValues()
    {
        $data['streamId']    = $this->input->post('streamId',true);
        $data['baseCourses'] = $this->input->post('baseCourses',true);
        $data['regFormId']   = $this->input->post('regFormId',true);
        $data['customFieldValueSource']   = $this->input->post('customFieldValueSource');

        if ( !is_numeric($data['streamId'])|| empty($data['streamId']) || $data['streamId'] <= 0 || !is_array($data['baseCourses'])) {
            return false;
        }
        
        foreach ($data['baseCourses'] as $key => $value) {
            if ($value <= 0) {
                unset($data['baseCourses'][$key]);
            }
            if (!is_numeric($value)){
                return false;
            }
        }

        if (empty($data['baseCourses'])) {
            return false;
        }

        /*removin dummy courses from the courses recieved */
        $this->load->library('RegistrationLib');
        $registrationLib     = new RegistrationLib();
        $data['baseCourses'] = $registrationLib->filterDummyBaseCourses($data['baseCourses']);

        $data['baseCourses'] = $data['baseCourses']['baseCourses'];

        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB', array('courseGroup' => 'nationalLead'));

        $data['fields'] = $registrationForm->getFields();

        if (isMobileRequest()) {
            $viewPath = 'registration/fields/mobile/variable/';
        } else {
            $viewPath = 'registration/fields/LDB/variable/';
        }

        echo $this->load->view($viewPath . 'subStreamSpecField', $data, true);
        return;
    }

    public function getBaseCoursesValues()
    {
        $data['streamId']            = $this->input->post('streamId');

        if(empty($data['streamId'])){
            echo false;
            return false;
        }
        
        $data['regFormId']           = $this->input->post('regFormId');
        $data['selectedHierarchies'] = $this->input->post('selectedHierarchies');
        $data['customFieldValueSource'] = $this->input->post('customFieldValueSource');

        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB', array('courseGroup' => 'nationalLead'));

        $data['fields'] = $registrationForm->getFields();

        if (isMobileRequest()) {
            $viewPath = 'registration/fields/mobile/variable/';
        } else {
            $viewPath = 'registration/fields/LDB/variable/';
        }

        echo $this->load->view($viewPath . 'baseCourseField', $data, true);
        return;
    }

    public function getEducationTypeOnCourseChange($notDirectAjax = false)
    {
        $data                = array();
        $data['baseCourses'] = $this->input->post('baseCourses');

        if (!is_array($data['baseCourses'])) {
            return false;
        }

        foreach ($data['baseCourses'] as $key => $value) {
            if ($value <= 0) {
                unset($data['baseCourses'][$key]);
            }
        }

        if (empty($data['baseCourses'])) {
            return false;
        }

        $registrationForm  = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB', array('courseGroup' => 'nationalLead'));
        $data['fields']    = $registrationForm->getFields();
        $data['regFormId'] = $this->input->post('regFormId');

        if (isMobileRequest()) {
            $viewPath = 'registration/fields/mobile/variable/';
        } else {
            $viewPath = 'registration/fields/LDB/variable/';
        }

        if ($notDirectAjax) {
            return $this->load->view($viewPath . 'educationTypeFields', $data, true);
        } else {
            echo $this->load->view($viewPath . 'educationTypeFields', $data, true);
        }
        return;
    }

    public function getLocalities($notDirectAjax = false)
    {
        $data = array();

        $data['cityId']      = $this->input->post('cityId');
        $data['baseCourses'] = $this->input->post('baseCourses');

        if (empty($data['cityId']) || $data['cityId'] <= 0 || !is_array($data['baseCourses'])) {
            return false;
        }

        foreach ($data['baseCourses'] as $key => $value) {
            if(!is_numeric($value))
            {
                return;
            }
            if ($value <= 0) {
                unset($data['baseCourses'][$key]);
            }
        }

        if (empty($data['baseCourses'])) {
            return false;
        }

        $this->load->library('user/UserLib');
        $userLib     = new UserLib;
        $userDetails = $this->getLoggedInUserData();
        $userId      = $userDetails['userId'];
        if(!empty($userId)){
            $data['isUserLoggedIn'] = 'yes';
        }

        /*Checking for the hyper local courses */
        $hyperLocalData = $userLib->getHyperAndNonhyperCoursesCount($data['baseCourses']);
        if ($hyperLocalData['hyperlocal'] < 1) {
            return false;
        }

        $registrationForm   = new \registration\libraries\Forms\LDB('nationalDefault');
        $localityField      = $registrationForm->getField('residenceLocality');
        $data['localities'] = $localityField->getValues(array('cityId' => $data['cityId']));
        $data['regFormId']  = $this->input->post('regFormId');
        if (empty($data['localities'])) {
            return false;
        } else {
            if ($notDirectAjax) {
                return $this->load->view('registration/fields/LDB/variable/residentLocality', $data, true);
            } else {
                echo $this->load->view('registration/fields/LDB/variable/residentLocality', $data, true);
            }
        }
        return;
    }

    public function getBaseCourseDependents()
    {
        $returnData                  = array();
        $returnData['educationType'] = $this->getEducationTypeOnCourseChange(true);
        $returnData['localities']    = $this->getLocalities(true);

        echo json_encode($returnData);
        return;
    }

    public function showLoginLayer()
    {
        $data = array();

        $customHelpText = $this->input->post('customHelpText', true);
        if (is_array($customHelpText)) {
            $data['customHelpText'] = $customHelpText;
        } else {
            $data['customHelpText'] = json_decode($customHelpText, true);
        }

       $data['customHelpText'] = $this->_getCustomHelpText($data['customHelpText']);

        $data['regFormId'] = $this->input->post('regFormId');
        if(empty($data['regFormId'])){
            $this->load->helper('string');
            global $mmpFormsForCaching;
            if(!empty($customFormData['customFields']['mmpFormId']) && in_array($customFormData['customFields']['mmpFormId'], $mmpFormsForCaching)){
                $data['regFormId'] = "mp".$customFormData['customFields']['mmpFormId'];
            }
            else{
                $data['regFormId'] = random_string('alnum', 6);
            }
        }

        $data['formCustomData']['trackingKeyId']          = $this->input->post('trackingKeyId', true);
        $data['formCustomData']['customFields']           = $this->input->post('customFields', true);
        $data['formCustomData']['callbackFunction']       = $this->input->post('callbackFunction', true);
        $data['formCustomData']['callbackFunctionParams'] = $this->input->post('callbackFunctionParams', true);
        $data['formCustomData']['registrationIdentifier'] = $this->input->post('registrationIdentifier', true);
        $data['showFormWithoutHelpText']                  = $this->input->post('showFormWithoutHelpText', true);
        $data['directFlow']                  = $this->input->post('directFlow', true);
        $data['registrationShikshaStats']                 = $this->loadRegistrationStatLayer();

        if (isMobileRequest()) {
            echo $this->load->view('muser5/loginLayer', $data);
        } else {
            echo $this->load->view('loginLayer', $data);
        }
        return;
    }

    public function showRegistrationWelcomeScreen()
    {
        $data                   = array();
        $data['customHelpText'] = $this->input->post('customHelpText', true);
        if (is_array($data['customHelpText'])) {
            $data['customHelpText'] = $data['customHelpText'];
        } else {
            $data['customHelpText'] = json_decode($data['customHelpText'], true);
        }

        $data['customHelpText'] = $this->_getCustomHelpText($data['customHelpText']);

        echo $this->load->view('signUpMobile', $data);
    }

    function _getUserDetails($userId = 0, $skipInterestDetails = false, $skipExamsDetails = false)
    {

        $userId = intval($userId);

        if (empty($userId)) {
            return array();
        }

        $usermodel = $this->load->model('user/usermodel');

        $userDetails = array();
        $userObj     = $usermodel->getUserById($userId, false);

        /*In case of slave lag, pull data using write handle */
        if (empty($userObj) && !is_object($userObj)) {
            $userObj = $usermodel->getUserById($userId, true);
            if (empty($userObj) && !is_object($userObj)) {
                return array();
            }
        }

        /*User Basic Details */
        $userDetails['email']                 = $userObj->getEmail();
        $userDetails['isdCode']               = $userObj->getISDCode();
        $userDetails['mobile']                = $userObj->getMobile();
        $userDetails['firstName']             = $userObj->getFirstName();
        $userDetails['lastName']              = $userObj->getLastName();
        $userDetails['residenceCityLocality'] = $userObj->getCity();
        $userDetails['residenceLocality']     = $userObj->getLocality();
        $userDetails['experience']            = $userObj->getExperience();
        $userDetails['country']               = $userObj->getCountry();
        $userDetails['mobileVerified']        = $userObj->getFlags()->getMobileVerified();


        $userPreference = $userObj->getPreference();
        if(is_object($userPreference)){
            $flow = $userPreference->getFlow();
            $userDetails['prefYear'] = $userPreference->getPrefYear();
        }
        // if(is_object($prefSubmitTime) && !empty($prefSubmitTime)) {
        //     $userDetails['prefSubmitTime'] = $prefSubmitTime->format('Y-m-d H:i:s');
        // }

        // $userObj->addUserPref();

        /*User EXAM details */
        if (!$skipExamsDetails) {

            $userEducation = $userObj->getEducation();
            if (is_object($userEducation)) {

                foreach ($userEducation as $education) {
                    $level = $education->getLevel();
                    $marks = $education->getMarks();
                    $name  = $education->getName();

                    if ($level == 'Competitive exam') {
                        $userDetails['exams'][]                     = $name;
                        $userDetails['examScore'][$name . '_score'] = $marks;
                    }

                }
            }
        }

        if(is_object($userPreference)){
            if($userPreference->getExtraFlag() == 'studyabroad'){
                $skipInterestDetails = true;
            }
        }
        
        if ($skipInterestDetails) {
        
            return $userDetails;
        }

        /*User Study Preference Data*/
        $userinterest = $userObj->getUserInterest();
        if (is_object($userinterest)) {
            $stream        = 0;
            $baseCourses   = array();
            $subStreamSpec = array();
            $educationMode = array();

            foreach ($userinterest as $interest) {

                $interestStatus = $interest->getStatus();
                if ($interestStatus != 'history') {
                    $stream = $interest->getStreamId();

                    $substreamId = $interest->getSubStreamId();

                    if (empty($substreamId)) {
                        $substreamId = 'ungrouped';
                    }

                    $subStreamSpec[$substreamId] = array();
                    $userCourseSpec = $interest->getUserCourseSpecialization();
                    $specializations = array();
                    foreach ($userCourseSpec as $courseSpec) {
                        /*Getting specializations */
                        if($courseSpec->getSpecializationId() > 0){
                            $specializations[$courseSpec->getSpecializationId()] = $courseSpec->getSpecializationId();
                        }
                        
                        /*Getting base course */
                        if($courseSpec->getBaseCourseId() > 0){
                            $baseCourses[$courseSpec->getBaseCourseId()] = $courseSpec->getBaseCourseId();
                        }
                    }
                    if(!empty($specializations)){
                        foreach ($specializations as $key => $value) {
                            if(!empty($key)){
                                $subStreamSpec[$substreamId][] = (string)$key;
                            }
                        }
                    }

                    
                    if(empty($subStreamSpec[$substreamId])){

                        $subStreamSpecializations  = new \registration\libraries\FieldValueSources\SubStreamSpecializations;
                        if($flow == 'course'){
                            $fieldQuery = array('streamIds'=>array($stream), 'baseCourseIds'=>$baseCourses);
                        }else{
                            $fieldQuery = array('streamIds'=>array($stream));
                        }
                        $subStreamSpecializations = $subStreamSpecializations->getValues($fieldQuery);
                        $subStreamSpecializations = $subStreamSpecializations[$stream]['substreams'][$substreamId]['specializations'];
                        foreach($subStreamSpecializations as $specId=>$specData){
                            $subStreamSpec[$substreamId][] = (string)$specId;
                        }

                        if(empty($subStreamSpec['ungrouped'])){
                            unset($subStreamSpec['ungrouped']);
                        }

                    }

                    $attributeObj = $interest->getUserAttributes();
                    foreach ($attributeObj as $attrObj) {
                        $educationMode[$attrObj->getAttributeValue()] = $attrObj->getAttributeValue();
                    }
                }
            }

            $stringyBaseCourse = array();
            foreach($baseCourses as $key=>$value){
                $stringyBaseCourse[] = (string)$key;
            }

            $stringyfyEducationMode = array();
            foreach($educationMode as $key=>$value){
                $stringyfyEducationMode[] = (string)$key;
            }

            $userDetails['stream']          = $stream;
            $userDetails['flow']            = $flow;
            $userDetails['baseCourses']     = $stringyBaseCourse;
            $userDetails['subStreamSpec']   = $subStreamSpec;
            $userDetails['educationType']   = $stringyfyEducationMode;

        }
                  
        return $userDetails;

    }

    public function isValidRegUser() {

        if(!verifyCSRF()) { return false; }

        $userData              = $this->getLoggedInUserData();
        $userId                = $userData['userId'];
        $userDetails           = $this->_getUserDetails($userId);
        $returnArray           = array();
        $returnArray['userId'] = $userId;

        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB', array('courseGroup' => 'nationalDefault', 'context' => 'default'));
        $fields           = $registrationForm->getFields();

        if ($userDetails['country'] != '2') {
            unset($fields['residenceCityLocality']);
        }

        $virtualCities = array('12292', '10223', '10224');
        if(in_array($userDetails['residenceCityLocality'], $virtualCities)){
            $returnArray['isValidUser'] = 'false';
            echo json_encode($returnArray);
            return false;
        }

        $skipFields = array('password', 'residenceLocality', 'flowChoice');
        $skipFields = array_merge($skipFields, array('stream', 'educationType', 'baseCourses', 'subStreamSpecializations')); //skipping interest fields so that abroad users will be treated as valid users

        foreach ($skipFields as $key => $value) {
            unset($fields[$value]);
        }

        foreach ($fields as $key => $field) {
            if (!$userDetails[$key] && $field->isMandatory()) {
                $returnArray['isValidUser'] = 'false';
                echo json_encode($returnArray);
                return false;
            }
        }

        if($userDetails['mobileVerified'] != 1) {
            $returnArray['isValidUser'] = 'mobile_not_verified';
            echo json_encode($returnArray);
            return false;
        }
		
        $returnArray['isValidUser'] = 'true';
        echo json_encode($returnArray);
        return true;

    }

    public function isValidUser($clientCourseId, $userId = null, $checkPrefFields = false, $isViewedCall = false)
    {   
        $isAjaxCall = $this->input->post('isAjax', true);
        $isPWACall  = $this->input->post('isPWACall', true);
        if ($isAjaxCall) {
            if(!verifyCSRF()) { return false; }
        }
        
        // to handle cases where $userId was coming as 'null' (string)
        if (empty($userId) || $userId == 'null' || is_null($userId)) {
            $userDetails = $this->getLoggedInUserData();
            $userId      = $userDetails['userId'];
        }

        if (empty($clientCourseId)) {
            $clientCourseId = $this->input->post('clientCourseId', true);
            
            if(!is_numeric($clientCourseId))
            {
                // Invalid ClientCourseID
                return false;    
            }
        }

        if(!empty($_POST['isViewedCall']) && $_POST['isViewedCall'] == 'yes'){
            $isViewedCall = true;
        }

        if(!empty($_POST['checkPrefFields']) && $_POST['checkPrefFields'] == 'yes'){
            $checkPrefFields = true;
        }

        $skipUserInterestDetails = false;
        if(!empty($clientCourseId)){
            $skipUserInterestDetails = true;
        }
        $userDetails = $this->_getUserDetails($userId, $skipUserInterestDetails);

        if($isViewedCall == true && $userDetails['mobileVerified'] != 1) {
            if($isPWACall) {
                echo 'mobile_not_verified';
                return 'mobile_not_verified';
            } else {
                echo 'mobile_not_verified';
                return false;
            }
        }

        $registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB', array('courseGroup' => 'nationalDefault', 'context' => 'default'));
        $fields = $registrationForm->getFields();

        //Skipping check of residentcity/locality for international user
        if ($userDetails['country'] != '2') {
            unset($fields['residenceCityLocality']);
        }

        /*As virtual cities only exist in SA site  */
        $virtualCities = array('12292', '10223', '10224');
        if(in_array($userDetails['residenceCityLocality'], $virtualCities)){
            echo 'false';
            return false;
        }

        $skipFields = array('password', 'residenceLocality', 'flowChoice');

        if ($isViewedCall== true)
        {
            $skipFields[] = 'prefYear'; 
        }

        if(!empty($clientCourseId)){
            $skipFields = array_merge($skipFields, array('stream', 'educationType', 'baseCourses', 'subStreamSpecializations'));
        }

        foreach ($skipFields as $key => $value) {
            unset($fields[$value]);
        }

        foreach ($fields as $key => $field) {
            if (!$userDetails[$key] && $field->isMandatory()) {
                echo 'false';
                return false;
            }
        }

        if(!empty($clientCourseId)){
            //executive, prefered, btech
            $clientCourseFields = array();
            $clientCourseData = $this->_getClientCourseData($clientCourseId, array('hierarhies', 'executiveFlag', 'locations', 'exams', 'entryBaseCourse', 'mode', 'level', 'credential'));

            /*Case of multi stream */
            $isMultiStream = false;
            if (count($clientCourseData['hierarhies']) > 1) {

                $multiStreamData              = $this->_getClientCourseData($clientCourseId, array('primary_hierarchy', 'level', 'credential', 'mode'));
        
                $streamIdArray = array_keys($multiStreamData['primary_hierarchy']);
                $streamId = $streamIdArray[0];
                $hierarchies[$streamId] = $multiStreamData['hierarhies'][$streamId];
                $multiStreamData['hierarhies']     = $hierarchies;

                $multiStreamData['mappedHierarchies'] = $this->_extractSpecializationsFromStreamSubStreamComb($multiStreamData['hierarhies']);

                $clientCourseData['baseCourse'] = $this->_getFilteredMappedBasedCourseByLevelAndCredential($multiStreamData['mappedHierarchies'], $multiStreamData['level'], $multiStreamData['credential'], $clientCourseData['baseCourse'], true);

                // echo 'false';
                // return false;
            }

            if(!($streamId > 0)){
                $streamIdArray = array_keys($clientCourseData['hierarhies']);
                $streamId = $streamIdArray[0];
            }

            global $managementStreamMR;
            global $postGrad;
            global $certificateCredential;
            /*If course is executive then we need to check work exp field */
            global $managementStreamMR;
            if (!$isViewedCall && (!empty($clientCourseData['isExecutive']) || ($streamId == $managementStreamMR && is_object($clientCourseData['credential']) && $clientCourseData['credential']->getId() == 11) || (is_object($clientCourseData['level']) && $clientCourseData['level']->getId() == 15))) {
                $clientCourseFields[] = 'experience';
            }

            /* check for hyper local courses */
            if(!$isViewedCall && (!empty($clientCourseData['baseCourse']))) {
                $this->load->library('user/UserLib');
                $userLib = new UserLib;
                $hyperLocalData = $userLib->getHyperAndNonhyperCoursesCount(array($clientCourseData['baseCourse']));
                
                if ($hyperLocalData['hyperlocal'] > 0) {
                    if(!empty($userDetails['residenceCityLocality'])){
                        $registrationForm   = new \registration\libraries\Forms\LDB;
                        $localityField      = $registrationForm->getField('preferredStudyLocality');
                        $localities = $localityField->getValues(array('cityId' => $userDetails['residenceCityLocality']));
                        if(!empty($localities)){
                            $clientCourseFields[] = 'residenceLocality';
                        }
                    }
                }
            }

            /*Need to check id client course is mapped to FT-MBA or FT-BTECH, then check for exams field */
            if (!(($clientCourseData['baseCourse'] != MANAGEMENT_COURSE || $clientCourseData['baseCourse'] != ENGINEERING_COURSE) && $clientCourseData['mode'] == '20') && !empty($clientCourseData['exams'])) {
               $clientCourseFields[] = 'exams';
            }

            if($checkPrefFields){

                $localities = array();
                $locations = $clientCourseData['locations'];
                if(!empty($locations) && count($locations) == 1){ 
                    foreach ($locations as $key => $value) {
                        $localities =   $value['localities'];
                    }
                } 

                $hasPrefLocations = (!empty($locations) && count($locations) > 1) || (!empty($localities) && count($localities) > 1);
                
                if($hasPrefLocations){
                    echo 'false';
                    return false;
                }
            }
            
            foreach ($clientCourseFields as $key => $value) {
                if(!isset($userDetails[$value])){
                    echo 'false';
                    return false;
                }
            }
        }

        if($userDetails['mobileVerified'] != 1){
            if($isPWACall) {
                echo 'mobile_not_verified';
                return 'mobile_not_verified';
            } else {
                echo 'mobile_not_verified';
                return false;
            }
        }

        echo 'true';
        return true;
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
        $userDetails = $this->_getUserDetails($userId, $skipUserInterestDetails);

        if($isViewedCall == true){
            if($userDetails['mobileVerified'] != 1){
                echo 'mobile_not_verified';
                return false;
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
            echo 'false';
            return false;
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
                echo 'false';
                return false;
            }
        }

        if(!empty($examGroupId)){

            $this->load->builder('ExamBuilder','examPages');
            $examBuilder     = new ExamBuilder();
            $examRepository  = $examBuilder->getExamRepository();
            $examGroupObj    = $examRepository->findGroup($examGroupId);
            if(!is_object($examGroupObj)) {
                return false;
            }
            $examId          = $examGroupObj->getExamId();
            $examBasicObj    = $examRepository->find($examId);
            if(!is_object($examBasicObj)) {
                return false;
            }
            $groupMappedArr  = $examBasicObj->getGroupMappedToExam();
            if(count($groupMappedArr) > 1){
                echo 'false';
                return false;
            }
            
            $examGroupData = array();
            $requiredList  = array('baseCourse', 'level');
            $examGroupData = $this->_getExamGroupData($examGroupId, $requiredList);
            
            if(count($examGroupData['level']) > 1){
                echo 'false';
                return false;
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
                                echo 'false';
                                return false;
                            }

                        }
                        
                    }

                }

            }

        }

        if($userDetails['mobileVerified'] != 1){
            echo 'mobile_not_verified';
            return false;
        }

        echo 'true';
        return true;
    }

    public function getFormByClientCourse()
    {
        
        foreach ($_POST as $key => $value) {
            if (strip_tags($value) != $_POST[$key])
                return false;
        }
        $clientCourse = $this->input->post('clientCourse',true);
        $regFormId    = $this->input->post('regFormId',true);
        
        if (empty($clientCourse) || !is_numeric($clientCourse)) {
            return false;
        }

        $data              = $this->_getClientCourseData($clientCourse, array('primary_hierarchy', 'hierarhies', 'entryBaseCourse', 'executiveFlag', 'entryExams', 'level', 'credential', 'exams', 'locations', 'mode'));
        $data['regFormId'] = $regFormId;

        if(is_numeric($_POST['workExperience'])){
            $data['workExperience'] = $this->input->post('workExperience');
        }
        
        $streamIdArray = array();
        $streamIdArray = array_keys($data['primary_hierarchy']);
        $streamId    = $streamIdArray[0];
        if (empty($streamId)) {
            return false;
        }
        $hierarchies = array();
        $hierarchies[$streamId] = $data['hierarhies'][$streamId];
        $data['hierarhies']     = $hierarchies;

        $data['mappedHierarchies'] = $this->_extractSpecializationsFromStreamSubStreamComb($data['hierarhies']);
        
        $data['baseCourse'] = $this->_getFilteredMappedBasedCourseByLevelAndCredential($data['mappedHierarchies'], $data['level'], $data['credential'], $data['baseCourse'], true);
        
        global $managementStreamMR;
        global $postGrad;
        global $certificateCredential;
        if (!empty($data['isExecutive']) || ($streamId == $managementStreamMR && $data['credential']->getId() == $certificateCredential) || $data['level']->getId() == $postGrad) {
            $workExFieldValues  = new \registration\libraries\FieldValueSources\WorkExperience;
            $data['workExList'] = $workExFieldValues->getValues();
        }

        /* Ask exam only for Full time BTECH/MBA */
        if(!(($data['baseCourse'] == MANAGEMENT_COURSE || $data['baseCourse'] == ENGINEERING_COURSE) && $data['mode'] == '20')){
            unset($data['examList']);
        } else if(!empty($data['examList'])){
            $userDetails = $this->getLoggedInUserData();
            $userId      = $userDetails['userId'];
            $data['userDetails'] = $this->_getUserDetails($userId, true);
        }

        $data['registrationHelper'] = new \registration\libraries\RegistrationHelper($data['fields'], $data['regFormId']);

        // $userDetails = $this->getLoggedInUserData();
        // $userId      = $userDetails['userId'];

        // $data['userDetails'] = $this->_getUserDetails($userId, true);

        $module = 'LDB';
        if(isMobileRequest()){
            $module = 'mobile';
        }
        
        echo $this->load->view('registration/fields/'.$module.'/response/responseFirstScreen', $data);

    }

    public function getFormByExamGroup(){

        $examGroup = $this->input->post('examGroup');
        $regFormId = $this->input->post('regFormId');

        $len = strlen($regFormId);

        $regFormId = strip_tags($regFormId);

        if($len!==strlen($regFormId) || $len>6 || preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $regFormId))
        {
            return;
        }
        
        if (empty($examGroup)) {
           return false;
        }
        
        $requiredList = array();
        $requiredList = array('primary_hierarchy', 'hierarchies', 'baseCourse', 'mode', 'level');
        
        $data              = array();
        $data              = $this->_getExamGroupData($examGroup, $requiredList);
        $data['regFormId'] = $regFormId;
        
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

        $data['mappedHierarchies'] = $this->_extractSpecializationsFromStreamSubStreamComb($data['hierarchies']);
        
        if(count($data['level']) == 1){
            $key   = array_keys($data['level']);
            $level = $key[0];
            $data['baseCourse'] = $this->_getFilteredMappedBasedCourseByLevelAndCredential($data['mappedHierarchies'], $level, '', $data['baseCourse'], false, true);
        }

        $userDetails = $this->getLoggedInUserData();
        $userId      = $userDetails['userId'];
        if(!empty($userId)){
            $data['userDetails'] = $this->_getUserDetails($userId, true);
        }

        if(isMobileRequest()){ 
            echo $this->load->view('registration/fields/mobile/examResponse/groupDependentFields', $data);
        }else{
            echo $this->load->view('registration/fields/LDB/examResponse/groupDependentFields', $data); 
        }
    }

    public function filterBaseCoursesByLevelAndHierarchy() {

        $examGroup = $this->input->post('examGroup');
        $levelId   = $this->input->post('level');
        $regFormId = $this->input->post('regFormId');
        
        if (empty($examGroup) || empty($levelId)) {
            return false;
        }
        
        $requiredList = array();
        $requiredList = array('primary_hierarchy', 'hierarchies', 'baseCourse');
        
        $data              = array();
        $data              = $this->_getExamGroupData($examGroup, $requiredList);
        $data['regFormId'] = $regFormId;
        
        $streamIdArray = array();
        $streamIdArray = array_keys($data['primary_hierarchy']);
        $streamId      = $streamIdArray[0];
        if (empty($streamId)) {
            return false;
        }
        $hierarchies            = array();
        $hierarchies[$streamId] = $data['hierarchies'][$streamId];
        $data['hierarchies']    = $hierarchies;

        $data['mappedHierarchies'] = $this->_extractSpecializationsFromStreamSubStreamComb($data['hierarchies']);
        
        $baseCourses = $this->_getFilteredMappedBasedCourseByLevelAndCredential($data['mappedHierarchies'], $levelId, '', $data['baseCourse'], false, true);
        if(!is_array($baseCourses)){
            $baseCourses = array($baseCourses);
        }
        echo json_encode($baseCourses);
        
    }

    /* */
    function _extractSpecializationsFromStreamSubStreamComb($hierarhies)
    {

        foreach ($hierarhies as $streamId => $hierarhyData) {
            $stream = $streamId;
        }

        // $subStreamSpec     = new \registration\libraries\FieldValueSources\SubStreamSpecializations;
        // $subStreamSpecData = $subStreamSpec->getValues(array('streamIds' => array($stream)));
        
        // $subStreamSpecValues = array();
        // foreach ($subStreamSpecData[$stream]['substreams'] as $subStreamId => $specializations) {
        //     $subStreamSpecValues[$subStreamId] = array_keys($specializations['specializations']);
        // }
        
        $mappedHierarchies = array();
        foreach ($hierarhies[$stream] as $subStreamId => $specializations) {
            $mappedHierarchies[$subStreamId] = array();
            foreach ($specializations as $key => $specId) {

                // if (empty($specId) && !empty($subStreamId)) {
                //     $mappedHierarchies[$subStreamId] = $subStreamSpecValues[$subStreamId];
                //     break;
                // } else if(!empty($specId)){
                if(!empty($specId)){
                    $mappedHierarchies[$subStreamId][] = $specId;
                }
            }
        }

        $returnData['stream']      = $stream;
        $returnData['hierarchies'] = $mappedHierarchies;
        return $returnData;
    }

    function _getFilteredMappedBasedCourseByLevelAndCredential($mappedHierarchies, $level, $credential, $baseCourse, $isFormHasMultipleStreams=false, $isExamResponse=false)
    {

        $isValidMappedHierarchies = false;
        foreach ($mappedHierarchies['hierarchies'] as $key => $value) {
            if(!empty($key) || !empty($value)){
                $isValidMappedHierarchies = true;
                break;
            }
        }
        $params = array();
        if (count($mappedHierarchies['hierarchies']) > 0 && $isValidMappedHierarchies) {
            foreach ($mappedHierarchies['hierarchies'] as $subStreamId => $specializations) {
                if(!empty($specializations)){
                    foreach ($specializations as $key => $specializationId) {
                        $params['baseEntityArr'][] = array('streamId' => $mappedHierarchies['stream'], 'substreamId' => $subStreamId, 'specializationId' => $specializationId);
                    }
                }else{
                     $params['baseEntityArr'][] = array('streamId' => $mappedHierarchies['stream'], 'substreamId' => $subStreamId, 'specializationId' => 'any');
                }
            }
        } else {
            $params['baseEntityArr'][] = array('streamId' => $mappedHierarchies['stream'], 'substreamId' => 'any', 'specializationId' => 'any');
        }

        $params['isResponseCall']     = 'yes';
        $params['requiredLevel']      = $level;
        $params['requiredCredential'] = $credential;
        
        $baseCourses       = new \registration\libraries\FieldValueSources\BaseCourses;
        $mappedBaseCourses = $baseCourses->getValues($params);
        
        if($isFormHasMultipleStreams && !empty($baseCourse)){
            $baseCourse = intval($baseCourse);
            if(in_array($baseCourse, $mappedBaseCourses['courseList'])){
                return $baseCourse;
            }
        }
        
        if($isExamResponse && !empty($baseCourse)){
            $returnBaseCourseArray = array();
            foreach ($baseCourse as $key => $value) {
                if(in_array($value, $mappedBaseCourses['courseList'])){
                    $returnBaseCourseArray[] = $value;
                }
            }
            if(!empty($returnBaseCourseArray)){
                return $returnBaseCourseArray;
            }
        }

        return $mappedBaseCourses['dummyCourse'];
    }

    function _getClientCourseData($clientCourse, $requiredList = array('hierarhies'))
    {
        $courseData = array();

        $this->load->builder("nationalCourse/CourseBuilder");
        $builder   = new CourseBuilder();
        $repo      = $builder->getCourseRepository();
        $courseObj = $repo->find($clientCourse, array('basic', 'course_type_information', 'eligibility', 'location'));
        
        if (!is_object($courseObj) || $courseObj->getId() == '') {
            return array();
        }

        foreach ($requiredList as $key => $value) {
            switch ($value) {
                case 'hierarhies':
                    $courseTypeInfo = $courseObj->getCourseTypeInformation();
                    if(is_object($courseTypeInfo['entry_course'])){
                        $courseHierarchies = $courseTypeInfo['entry_course']->getHierarchies();
                        foreach ($courseHierarchies as $key => $value) {
                            $courseData['hierarhies'][$value['stream_id']][$value['substream_id']][] = $value['specialization_id'];
                        }
                    }
                    break;

                case 'primary_hierarchy':
                    $courseTypeInfo = $courseObj->getCourseTypeInformation();
                    if(is_object($courseTypeInfo['entry_course'])){
                        $courseHierarchies = $courseTypeInfo['entry_course']->getHierarchies();
                        foreach ($courseHierarchies as $key => $value) {
                            if($value['primary_hierarchy'] == 1){
                                $courseData['primary_hierarchy'][$value['stream_id']][$value['substream_id']][] = $value['specialization_id'];
                            }
                        }
                    }
                    break;

                case 'entryBaseCourse':
                    $courseTypeInfo = $courseObj->getCourseTypeInformation();
                    if(is_object($courseTypeInfo['entry_course'])){
                        $courseData['baseCourse'] = $courseTypeInfo['entry_course']->getBaseCourse();
                    }
                    break;

                case 'executiveFlag':
                    $courseData['isExecutive'] = $courseObj->isExecutive();
                    break;

                case 'level':
                    $courseTypeInfo      = $courseObj->getCourseTypeInformation();
                    if(is_object($courseTypeInfo['entry_course'])){
                        $courseData['level'] = $courseTypeInfo['entry_course']->getCourseLevel();
                    }
                    break;

                case 'credential':
                    $courseTypeInfo           = $courseObj->getCourseTypeInformation();
                    if(is_object($courseTypeInfo['entry_course'])){
                        $courseData['credential'] = $courseTypeInfo['entry_course']->getCredential();
                    }
                    break;

                case 'mode':
                    $delivery = $courseObj->getDeliveryMethod();
                    $mode = $courseObj->getEducationType();
                    if(is_object($mode) && $mode->getId() != FULL_TIME_MODE && !empty($delivery)){
                        $mode = $delivery;
                    }
                    
                    if(is_object($mode)){
                        $courseData['mode'] = $mode->getId();
                    }
                    
                    break;

                case 'exams':
                    $eligibility = $courseObj->getEligibility();
                    $eligibility = $eligibility['general'];
                    foreach ($eligibility as $eligibilityObj) {
                        if(is_object($eligibilityObj)){
                            $courseData['examList'][$eligibilityObj->getExamId()] = $eligibilityObj->getExamName();
                        }
                    }
                    ksort($courseData['examList']);
                    break;

                case 'locations':
                    $locations    = array();
                    $locationObjs = $courseObj->getLocations();

                    foreach ($locationObjs as $locationObj) {
                        if(is_object($locationObj)){
                            $locations[$locationObj->getCityId()]['cityName'] = $locationObj->getCityName();
                            $localities                                       = $locationObj->getLocalityId();
                            if (!empty($localities)) {
                                $locations[$locationObj->getCityId()]['localities'][$locationObj->getLocalityId()] = $locationObj->getLocalityName();
                            }
                        }
                    }
                    $courseData['locations'] = $locations;
                    break;
            }
        }

        return $courseData;
    }

    function _getExamGroupData($examGroup, $requiredList = array('hierarchies'))
    {
        $examGroupData = array();

        $this->load->builder('ExamBuilder','examPages');
        $examBuilder    = new ExamBuilder();
        $examRepository = $examBuilder->getExamRepository();
        $examGroupObj   = $examRepository->findGroup($examGroup);
        if(!is_object($examGroupObj)) {
            return array();
        }
        
        $entitiesMappedArr  = $examGroupObj->getEntitiesMappedToGroup();
        
        foreach ($requiredList as $key => $value) {
            
            switch ($value) {
                case 'hierarchies':
                    $hierarchyData = $examGroupObj->getHierarchy();
                    foreach ($hierarchyData as $key => $value) {
                        if(empty($value['substream'])){
                            $value['substream'] = 0;
                        }
                        if(empty($value['specialization'])){
                            $value['specialization'] = 0;
                        }
                        $examGroupData['hierarchies'][$value['stream']][$value['substream']][] = $value['specialization'];
                    }
                    break;

                case 'primary_hierarchy':
                    $primaryHierarchyId = $entitiesMappedArr['primaryHierarchy'][0];
                    $hierarchyData = $examGroupObj->getHierarchy();
                    
                    if(empty($hierarchyData[$primaryHierarchyId]['substream'])){
                        $hierarchyData[$primaryHierarchyId]['substream'] = 0;
                    }
                    if(empty($hierarchyData[$primaryHierarchyId]['specialization'])){
                        $hierarchyData[$primaryHierarchyId]['specialization'] = 0;
                    }
                    $examGroupData['primary_hierarchy'][$hierarchyData[$primaryHierarchyId]['stream']][$hierarchyData[$primaryHierarchyId]['substream']][] = $hierarchyData[$primaryHierarchyId]['specialization'];
                    break;

                case 'baseCourse':
                    $examGroupData['baseCourse'] = $entitiesMappedArr['course'];
                    break;

                case 'mode':
                    $examGroupData['mode'] = $entitiesMappedArr['otherAttribute'];
                    break;

                case 'level':
                    $this->load->builder('ListingBaseBuilder','listingBase');
                    $listingBaseBuilder   = new \ListingBaseBuilder();
                    $baseCourseRepository = $listingBaseBuilder->getBaseCourseRepository();
                    $this->load->library('listingBase/BaseAttributeLibrary');
                    $baseAttributeLibrary = new BaseAttributeLibrary();
                    $baseCoursesObj       = $baseCourseRepository->findMultiple($entitiesMappedArr['course']);
                    foreach ($baseCoursesObj as $baseCourseId => $baseCourseObj) {
                        $levels[$baseCourseObj->getLevel()] = 1;
                    }
                    $examGroupData['level'] = $baseAttributeLibrary->getValueNameByValueId(array_keys($levels));
                    break;

            }
        }

        return $examGroupData;

    }

    public function getResponseFieldsByStreamId()
    {
        $clientCourse = $this->input->post('clientCourse');
        if (empty($clientCourse)) {
            return false;
        }

        $data     = array();
        $streamId = $this->input->post('streamId');

        $data                   = $this->_getClientCourseData($clientCourse, array('hierarhies', 'entryBaseCourse', 'entryExams', 'level', 'credential', 'exams', 'mode'));
        $hierarchies            = array();
        $hierarchies[$streamId] = $data['hierarhies'][$streamId];
        $data['hierarhies']     = $hierarchies;
        $data['regFormId']      = $this->input->post('regFormId');
        $data['isMultipleStreamCase'] = 'yes';

        $data['mappedHierarchies'] = $this->_extractSpecializationsFromStreamSubStreamComb($data['hierarhies']);
        unset($data['hierarhies']);

        $data['baseCourse'] = $this->_getFilteredMappedBasedCourseByLevelAndCredential($data['mappedHierarchies'], $data['level'], $data['credential'], $data['baseCourse'], true);
        global $managementStreamMR;
        global $postGrad;
        global $certificateCredential;
        if (!empty($data['isExecutive']) || ($streamId == $managementStreamMR && $data['credential']->getId() == $certificateCredential) || $data['level']->getId() == $postGrad) {
            $workExFieldValues  = new \registration\libraries\FieldValueSources\WorkExperience;
            $data['workExList'] = $workExFieldValues->getValues();
        }

        /* Ask exam only for Full time BTECH/MBA */
        if(!(($data['baseCourse'] == MANAGEMENT_COURSE || $data['baseCourse'] == ENGINEERING_COURSE) && $data['mode'] == '20')){
            unset($data['examList']);
        }else if(!empty($data['examList'])){
            $userDetails = $this->getLoggedInUserData();
            $userId      = $userDetails['userId'];

            $data['userDetails'] = $this->_getUserDetails($userId, true);
        }

        $data['registrationHelper'] = new \registration\libraries\RegistrationHelper($data['fields'], $data['regFormId']);

        $module = 'LDB';
        if(isMobileRequest()){
            $module = 'mobile';
        }
        echo $this->load->view('registration/fields/'.$module.'/response/responseFirstScreen', $data);
    }

    public function getLoggedInUserDetails($skipInterestDetails = true, $isMMP = false)
    {
        $userDetails = $this->getLoggedInUserData();
        $userId      = $userDetails['userId'];

        if (empty($userId)) {
            return array();
        }

        $userDetails = $this->_getUserDetails($userId, $skipInterestDetails, true);

        $returnData = array();
        foreach ($userDetails as $key => $value) {
            if (!isset($value)) {
                continue;
            }

            $returnData[$key] = array(
                'value'    => $value,
                'hidden'   => '1',
                'disabled' => '1',
            );
        }
        
        if (!empty($userDetails['isdCode']) && !empty($userDetails['country'])) {
            $returnData['isdCode']['value'] = $userDetails['isdCode'] . '-' . $userDetails['country'];
            unset($returnData['country']);
        }else{
            unset($returnData['isdCode']['value']);
        }

        if (!empty($returnData['residenceCity'])) {
            $returnData['residenceCityLocality'] = $returnData['isdCode'];
            unset($returnData['residenceCity']);
        }
        
        if($isMMP){
            $returnData = $this->_setVisibilityOfInterestFields($returnData);
        }

        return $returnData;
    }

    public function getResponseData($data){
        $returnData = array();

        $returnData['mappedHierarchies'] = $this->_extractSpecializationsFromStreamSubStreamComb($data['hierarhies']);
        if(($data['multipleStreams'] || empty($data['baseCourse'])) && !empty($returnData['mappedHierarchies']['hierarchies'])) {
            $returnData['baseCourse'] = $this->_getFilteredMappedBasedCourseByLevelAndCredential($returnData['mappedHierarchies'], $data['level'], $data['credential'], $data['baseCourse'], $data['multipleStreams']);
        }else{
            $returnData['baseCourse'] = $data['baseCourse'];
        }

        return $returnData;
    }

    public function getExamResponseData($examGroupId, $requiredList){
        
        $data = $this->_getExamGroupData($examGroupId, $requiredList);
        
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
        $data['mappedHierarchies'] = $this->_extractSpecializationsFromStreamSubStreamComb($data['hierarchies']);
        
        if(count($data['level']) == 1){
            $key   = array_keys($data['level']);
            $level = $key[0];
            $data['baseCourse'] = $this->_getFilteredMappedBasedCourseByLevelAndCredential($data['mappedHierarchies'], $level, '', $data['baseCourse'], false, true);
        }else{
            $data['baseCourse'] = $data['baseCourse'];
        }

        return $data;
    }

    private function _getCustomHelpText($customHelpText){
        if (empty($customHelpText)) {
            $customHelpText = \registration\libraries\RegistrationHelper::getDefaultHelptextForRegistrationLayer();
        }else {
            $customText = \registration\libraries\RegistrationHelper::getDefaultHelptextForRegistrationLayer();
            $formattedCustomHelpText = array();
            foreach ($customHelpText as $key => $customTextData) {
                if(isset($customTextData['heading']) && !empty($customTextData['heading']) && isset($customTextData['body']) && !empty($customTextData['body'])){
                    $formattedCustomHelpText[$key]['heading'] = $customTextData['heading'];
                    $formattedCustomHelpText[$key]['body'] = $customTextData['body'];
                }
            }
            if(empty($formattedCustomHelpText)){
                $customHelpText = $customText;
            }else{
                $customHelpText = $formattedCustomHelpText;
            }            
        }

        return $customHelpText;
    }

    private function _setVisibilityOfInterestFields($customFields){
        $interestFields = array('stream', 'subStreamSpec', 'baseCourses', 'educationType');
        foreach ($interestFields as $key => $value) {
            if(!empty($customFields[$value])){
                $customFields[$value]['hidden'] = 0;
                $customFields[$value]['disabled'] = 0;
            }
        }

        return $customFields;
    }

    private function loadRegistrationStatLayer(){
        $this->load->helper('mAnA5/ana');
        $cacheLib = $this->load->library('common/cacheLib');
        
        $cntKey = md5('nationalHomepageCounters_json');
        $shikshaStats = $cacheLib->get($cntKey);

        if($shikshaStats != 'ERROR_READING_CACHE'){
            $shikshaStatsDecoded = json_decode($shikshaStats, true);

            $shikshaStatsDecoded['national']['instCount']                = formatNumber($shikshaStatsDecoded['national']['instCount']);
            $shikshaStatsDecoded['national']['examCount']                = formatNumber($shikshaStatsDecoded['national']['examCount']);
            $shikshaStatsDecoded['national']['reviewsCount']             = formatNumber($shikshaStatsDecoded['national']['reviewsCount']);
            $shikshaStatsDecoded['national']['questionsAnsweredCount']   = formatNumber($shikshaStatsDecoded['national']['questionsAnsweredCount']);
            return $this->load->view('registrationShikshaStats',$shikshaStatsDecoded,true);
        }
        

    }

    public function trackInvalidFieldData() {

        $data = array();
        $data['regFormId']  = $this->input->post('regFormId', true);
        if (empty($data['regFormId'])) {
            return false;
        }
        $data['fieldName']        = $this->input->post('fieldName', true);
        $data['fieldValue']       = $this->input->post('fieldValue', true);
        $data['visitorSessionId'] = getVisitorSessionId();
        $data['referer']          = $_SERVER['HTTP_REFERER'];
        
        $this->load->model('registrationmodel');
        $insertFlag = $this->registrationmodel->saveInvalidFieldData($data);
        return $insertFlag;

    }

    public function reportError(){
        $loggedInUserData = $this->checkUserValidation();
        $userId           = $loggedInUserData[0]['userid'];
        if(empty($userId) || $userId<1){
            return;
        }
        $url = $this->input->post('url',true);
        $error = $this->input->post('error',true);

        $this->load->model('registrationmodel');
        $insertFlag = $this->registrationmodel->reportError($url,$error);
    }

}
?>
