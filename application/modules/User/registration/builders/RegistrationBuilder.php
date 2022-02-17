<?php

/**
 * Registration builder
 * Get parameterized objects and config settings
 */ 
namespace registration\builders;

/**
 * Registration builder
 * Get parameterized objects and config settings
 */ 
class RegistrationBuilder
{
    /**
     * Get a registration form based on type and params
     * 
     * @param string $type form type identifier (LDB, MMP etc)
     * @param array $params additional parameters
     * @return object \registration\libraries\Forms\AbstractForm
     */
    public static function getRegistrationForm($type,$params = array())
    {
        if($type == 'MMP') {
            return self::getMMPForm($params);
        }
        else if($type == 'LDB') {
            return self::getLDBForm($params);
        }
        else if($type == 'SecondLayer') {
            return self::getSecondLayerForm($params);
        }
    }
    
    /**
     * Get MMP-type registration form based on params
     * 
     * @param array $params additional parameters
     * @return object \registration\libraries\Forms\AbstractForm
     */
    public static function getMMPForm($params)
    {
        $mmpFormId = (int) $params['mmpFormId'];
        if(!$mmpFormId) {
            throw new \Exception("MMP Form Id is invalid");
        }
        
        $desiredCourseId = (int) $params['desiredCourseId'];
        $courseGroupId = (int) $params['courseGroupId'];
        
        $CI = & get_instance();   
        $CI->load->model('customizedmmp/customizemmp_model');
        $mmpDetails = $CI->customizemmp_model->getMMPDetails($mmpFormId);
        
        if($mmpDetails['page_type'] == 'abroadpage') { 
            return new \registration\libraries\Forms\MMP\Abroad($mmpFormId);
        }
        else {
            $form = new \registration\libraries\Forms\MMP\National($mmpFormId,$desiredCourseId,$courseGroupId);
            $form->setMMPDetails($mmpDetails);
            return $form;
        }
    }
    
    /**
     * Get LDB-type registration form
     * 
     * @param array $params additional parameters
     * @return object \registration\libraries\Forms\AbstractForm
     */
    public static function getLDBForm($params)
    {

        $courseGroup = 'default';
        $context = 'default';
        
        if($params['courseGroup']) {
            $courseGroup = $params['courseGroup'];
        }
        else if($params['desiredCourseId']) {
            if($params['fieldOfInterest'] == 14) {
                $courseGroup = self::getCourseGroupForTestPrep($params['desiredCourseId']);
                error_log($courseGroup);
            }
            else {
                $CI = & get_instance();
                $CI->load->model('customizedmmp/customizemmp_model');
                $groupDetails = $CI->customizemmp_model->getGroupDataForCourse($params['desiredCourseId']);
                $courseGroup = $groupDetails['acronym'];
            }
        }
        
        if($params['context']) {
            $context = $params['context'];
        }
        
        return new \registration\libraries\Forms\LDB($courseGroup,$context);
    }
    
    /**
     * Get second layer form
     * 
     * @param array $params additional parameters
     * @return object \registration\libraries\Forms\AbstractForm
     */
    public static function getSecondLayerForm($params)
    {
        $CI = & get_instance();
        
        if($params['userId']) {
            $userRepository = \user\Builders\UserBuilder::getUserRepository();
            $user = $userRepository->find($params['userId']);
            
            if($user && $user->isLDB()) {
                
                $data = array();
                $params = array();
                
                $prefs = $user->getPreference();
                
                $desiredCourse = $prefs->getDesiredCourse();
                
                if($prefs->getExtraFlag() == 'studyabroad') {
                    if(STUDY_ABROAD_NEW_REGISTRATION) {
                        $courseGroup = 'studyAbroadRevamped';
                    }
                    else {
                        if($desiredCourseId) {
                            $CI->load->builder('LDBCourseBuilder','LDB');
                            $LDBCourseBuilder = new \LDBCourseBuilder;
                            $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
                            $LDBCourse = $LDBCourseRepository->find($desiredCourseId);
                            $LDBCourseLevel = strtoupper($LDBCourse->getCourseLevel1());
                            $courseGroup = 'studyAbroad'.$LDBCourseLevel;
                        }
                        else {
                            $courseGroup = 'studyAbroadPG';
                        }
                    }
                }
                else if($prefs->getExtraFlag() == 'testprep') {
                    $testPrepPrefs = $prefs->getTestPrepSpecializationPreferences();
                    $testPrepSpecialization = $testPrepPrefs[0]->getSpecializationId();
                    $courseGroup = self::getCourseGroupForTestPrep($testPrepSpecialization);
                }
                else {
                    $CI->load->model('customizedmmp/customizemmp_model');
                    $groupDetails = $CI->customizemmp_model->getGroupDataForCourse($params['desiredCourseId']);
                    $courseGroup = $groupDetails['acronym'];
                }
                
                return new \registration\libraries\Forms\SecondLayer($courseGroup);
            }
        }
    }
    
    /**
     * Get an LDB context object based on context id
     * 
     * @param string $contextId
     * @return object \registration\libraries\Form\Contexts\AbstractContext
     */ 
    public static function getContext($contextId)
    {
        switch($contextId) {
            case 'unified':
                return new \registration\libraries\Forms\Contexts\Unified;
            case 'registerFree':
                return new \registration\libraries\Forms\Contexts\RegisterFree;
            case 'shortRegistration':
                return new \registration\libraries\Forms\Contexts\ShortRegistration;
            case 'findInstitute':
                return new \registration\libraries\Forms\Contexts\FindInstitute;
            case 'nonLDBLoggedInUser':
                return new \registration\libraries\Forms\Contexts\NonLDBLoggedInUser;
            case 'twoStepRegister':
                return new \registration\libraries\Forms\Contexts\twoStepRegister;
            case 'OnlineForm':
                return new \registration\libraries\Forms\Contexts\OnlineForm;
            case 'ExamPages':
                return new \registration\libraries\Forms\Contexts\ExamPages;
            case 'shortlistRegister':
                return new \registration\libraries\Forms\Contexts\ShortlistRegister;
            default:
                return new \registration\libraries\Forms\Contexts\DefaultContext;
        }
    }
    
    /**
     * Get master field settings. Master settings are immutable settings and are common for all course groups
     * e.g. label, caption, id, validations
     * 
     * @return array
     */
    public static function getMasterFieldSettings()
    {
        require APPPATH.'modules/User/registration/config/fieldSettings/master.php';
        return $masterFieldSettings;
    }
    
    /**
     * Get field settings for a course group. These include mutable settings e.g. visibility, mandate etc.
     * 
     * @param string $courseGroup (e.g. nationalUG, localPG etc.)
     * @param string $formType Form for which settings are required (e.g. LDB, SecondLayer). Default is LDB
     * @return array
     */
    public static function getFieldSettingsForCourseGroup($courseGroup,$formType = 'LDB')
    {
        $settingsFile = APPPATH.'modules/User/registration/config/fieldSettings/'.$formType.'/'.$courseGroup.'.php';        
        if(!file_exists($settingsFile)) {
            // throw new \Exception("The course group (".$courseGroup.") is invalid");
            error_log("==LDBExceptions== The course group (".$courseGroup.") is invalid");
        }else{
            require $settingsFile;
        }
        
        //require $settingsFile;
        return $fieldSettings;
    }
    
    /**
     * Get master rules to be applied on fields of the form
     * @param $params array
     * @return array
     */
    public static function getMasterRules($params = array())
    {
        if($params['mmpId']) {
            $masterRuleFile = APPPATH.'modules/User/registration/config/rules/masterMMP.php';
        }else{
            $masterRuleFile = APPPATH.'modules/User/registration/config/rules/master.php';
        }
        
        $ruleFile = '';
        /**
         * Check if custom rule file exists for current MMP page
         */ 
        if($params['mmpId']) {
            $ruleFile = APPPATH.'modules/User/registration/config/rules/MMP/'.$params['mmpId'].'.php';
        }
        
        /**
         * If not, check if custom rule file exists for current course group
         */ 
        
        
        if((!$ruleFile || !file_exists($ruleFile)) && $params['courseGroup'] && $params['mmpId']){
            $ruleFile = APPPATH.'modules/User/registration/config/rules/CourseGroupsMMP/'.$params['courseGroup'].'.php';
        }else if((!$ruleFile || !file_exists($ruleFile)) && $params['courseGroup']) {
            $ruleFile = APPPATH.'modules/User/registration/config/rules/CourseGroups/'.$params['courseGroup'].'.php';
        }
        
        if($ruleFile && file_exists($ruleFile)) {
            require $ruleFile;
        }
        else {
            require $masterRuleFile;
        }
    
        return $masterRules;
    }

    /**
     * Get course group for a test prep course
     * 
     * @param integer $testPrepCourseId
     * @return string course group
     */ 
    public static function getCourseGroupForTestPrep($testPrepCourseId)
    {
        $groupMapping = array(  
                                9208=>'UG',
                                6619=>'UG',
                                6614=>'UG',
                                6615=>'UG',
                                6616=>'UG',
                                6617=>'UG',
                                6618=>'UG',
                                6490=>'UG',
                                6504=>'UG',
                                6401=>'UG',
                                6214=>'UG',
                                6218=>'UG',
                                6219=>'UG',
                                6227=>'UG',
                                6611=>'UG',
                                6612=>'UG',
                                6613=>'UG',
                                6246=>'UG',
                                6554=>'UG',
                                6610=>'UG',
                                6245=>'UG',
                                6244=>'UG',
                                3280=>'UG',
                                330=>'UG',
                                3298=>'PG',
                                3299=>'UG',
                                302=>'UG',
                                303=>'UG',
                                3274=>'PG',
                                3275=>'PG',
                                305=>'PG',
                                306=>'PG',
                                307=>'PG',
                                309=>'PG',
                                2334=>'PG',
                                1402=>'PG',
                                3273=>'PG',
                                3300=>'UG',
                                310=>'PG',
                                410=>'UG',
                                418=>'UG',
                                2494=>'PG',
                                465=>'UG',
                                3043=>'UG',
                                3044=>'UG',
                                3041=>'UG',
                                3042=>'UG',
                                5822=>'PG',
                                5944=>'PG',
                                5945=>'PG',
                                6017=>'PG',
                                6740=>'UG',
                                9166=>'PG',
                                9169=>'UG',
                                9170=>'PG',
                                9171=>'PG',
                                9172=>'PG',
                                9173=>'PG',
                                9174=>'PG',
                                9175=>'UG',
                                9176=>'UG',
                                9177=>'UG',
                                9178=>'UG',
                                9179=>'UG',
                                9180=>'PG',
                                9181=>'UG',
                                9182=>'UG',
                                9183=>'UG',
                                9184=>'UG',
                                9185=>'UG',
                                9186=>'UG',
                                9187=>'UG',
                                9188=>'UG',
                                9189=>'UG',
                                9190=>'UG',
                                9191=>'UG',
                                9192=>'UG',
                                9193=>'UG',
                                9194=>'PG',
                                9195=>'UG',
                                9196=>'UG',
                                9197=>'UG',
                                9198=>'UG',
                                9199=>'UG',
                                9200=>'UG',
                                9201=>'UG',
                                9202=>'UG',
                                9204=>'PG',
                                9205=>'UG',
                                9206=>'PG',
                                9207=>'UG',
                                9028=>'PG',
                                9290=>'PG',
                                9209=>'PG',
                                9210=>'UG',
                                9211=>'UG',
                                9212=>'PG',
                                9213=>'UG',
                                9214=>'UG',
                                9237=>'UG',
                                9238=>'PG',
                                9239=>'UG',
                                9240=>'UG',
                                9241=>'UG',
                                9242=>'UG',
                                9243=>'UG',
                                9244=>'UG',
                                9245=>'UG',
                                9246=>'UG',
                                9247=>'UG',
                                9248=>'PG',
                                9249=>'PG',
                                9250=>'UG',
                                9251=>'UG',
                                9252=>'PG',
                                9253=>'UG',
                                9254=>'UG',
                                9255=>'UG',
                                9256=>'UG',
                                9257=>'UG',
                                9258=>'PG',
                                9259=>'UG',
                                9260=>'PG',
                                9261=>'UG',
                                9262=>'PG',
                                9263=>'PG',
                                9264=>'UG',
                                9265=>'UG',
                                6740=>'UG',
                                9266=>'PG',
                                9267=>'PG',
                                9268=>'UG',
                                9269=>'UG',
                                9270=>'UG',
                                9271=>'UG',
                                9272=>'PG',
                                9273=>'UG',
                                9274=>'UG',
                                9275=>'UG',
                                9276=>'PG',
                                9277=>'PG',
                                9278=>'UG',
                                9279=>'PG',
                                9280=>'UG',
                                9281=>'UG',
                                9282=>'PG',
                                9283=>'UG',
                                9284=>'PG',
                                9285=>'UG',
                                9286=>'UG',
                                9286=>'UG',
                                9287=>'UG',
                                9288=>'UG',
                                9289=>'UG',
                                4655=>'PG'
                        );

        if(empty($groupMapping[$testPrepCourseId])){
            // throw new \Exception("Intentional exception: To find out Non-Mapped test-prep courses. testPrepCourseId = ".$testPrepCourseId);
            error_log("==LDB== Intentional exception: To find out Non-Mapped test-prep courses. testPrepCourseId = ".$testPrepCourseId);
            return 'localUG';
        }

        return 'local'.$groupMapping[$testPrepCourseId];
    }
    
    /** 
     * Get value source of a registration form field
     * 
     * @param string $fieldId
     * @return object \registration\libraries\FieldValueSources\AbstractValueSource
     */ 
    public static function getFieldValueSource($fieldId)
    {
        if(file_exists(APPPATH.'modules/User/registration/libraries/FieldValueSources/'.ucfirst($fieldId).'.php')) {
            $valueSourceClass = "registration\\libraries\\FieldValueSources\\".ucfirst($fieldId);
            return new $valueSourceClass();
        }
    }
    
    /** 
     * Get validator of a registration form field
     * 
     * @param string $fieldId
     * @return object \registration\libraries\FieldValueSources\AbstractValidator
     */ 
    public static function getFieldValidator($fieldId)
    {
        if(file_exists(APPPATH.'modules/User/registration/libraries/FieldValidators/'.ucfirst($fieldId).'.php')) {
            $validatorClass = "registration\\libraries\\FieldValidators\\".ucfirst($fieldId);
            return new $validatorClass();
        }
    }
    
    /**
     * Function to get the list of competitive Exams
     * 
     * @param integer $examId
     * @param array $userExamData
     *
     * @return object \registration\libraries\CompetitiveExam
     */
    public static function getCompetitiveExam($examId,$userExamData = array())
    {
        require APPPATH.'modules/User/registration/config/examConfig.php';
        $exam = new \registration\libraries\CompetitiveExam($examId,$examList[$examId]);
        $exam->setData($userExamData);
        return $exam;
    }
}
