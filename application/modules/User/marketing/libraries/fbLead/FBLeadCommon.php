<?php
 
class FBLeadCommon
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
    }

    public function getCourseAttributes($courseId = null, $courseObj=null){
        if(is_null($courseId) && is_null($courseObj)){
            return false;
        }

        if(is_null($courseObj)){
            $this->CI->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $courseRepo    = $courseBuilder->getCourseRepository();
            $courseObj = $courseRepo->find($courseId,array('basic', 'course_type_information', 'eligibility', 'location'),true);
        }

        if(!is_object($courseObj) || $courseObj->getId() ==''){
            return false;
        }

        $data = $this->_getClientCourseData($courseObj, array('primary_hierarchy', 'hierarhies', 'entryBaseCourse', 'executiveFlag', 'entryExams', 'level', 'credential', 'exams', 'locations', 'mode'));
        
        $streamIdArray = array();
        $streamIdArray = array_keys($data['primary_hierarchy']);
        $streamId    = $streamIdArray[0];
        if (empty($streamId)) {
            return false;
        }

        $hierarchies            = array();
        $hierarchies[$streamId] = $data['hierarhies'][$streamId];
        $data['hierarhies']     = $hierarchies;

        $data['mappedHierarchies'] = $this->_extractSpecializationsFromStreamSubStreamComb($data['hierarhies']);
        unset($data['hierarhies']);

        $data['baseCourse'] = $this->_getFilteredMappedBasedCourseByLevelAndCredential($data['mappedHierarchies'], $data['level'], $data['credential'], $data['baseCourse'], true);
        return $data;
    }

    private function _getClientCourseData($courseObj = null, $requiredList = array('hierarhies'))
    {
        $courseData = array();
        if (!is_object($courseObj) || $courseObj->getId() == '') {
            return false;
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

    private function _extractSpecializationsFromStreamSubStreamComb($hierarhies){
        foreach ($hierarhies as $streamId => $hierarhyData) {
            $stream = $streamId;
        }

        $mappedHierarchies = array();
        foreach ($hierarhies[$stream] as $subStreamId => $specializations) {
            $mappedHierarchies[$subStreamId] = array();
            foreach ($specializations as $key => $specId) {
                if(!empty($specId)){
                    $mappedHierarchies[$subStreamId][] = $specId;
                }
            }
        }

        $returnData['stream']      = $stream;
        $returnData['hierarchies'] = $mappedHierarchies;
        return $returnData;
    }

    private function _getFilteredMappedBasedCourseByLevelAndCredential($mappedHierarchies, $level, $credential, $baseCourse, $isFormHasMultipleStreams=false, $isExamResponse=false){
        $isValidMappedHierarchies = false;
        foreach ($mappedHierarchies['hierarchies'] as $key => $value) {
            if(!empty($key) || !empty($value)){
                $isValidMappedHierarchies = true;
                break;
            }
        }

        $params = array();
        //_p($mappedHierarchies);die;
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
        //echo 'ddddd';_p($params);die;
        $mappedBaseCourses = $baseCourses->getValues($params);
        //_p($mappedBaseCourses);die;
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

    public function sendMail($toMail, $subject, $errorContent=""){
        //load library to send mail
        $this->CI->load->library('alerts_client');
        $alertClient = new Alerts_client();
        //$subject     = "FB Lead Error";
        $toMail = empty($toMail)? 'teamldb@shiksha.com' : $toMail;
        $subject = empty($subject)? 'FB Lead Error' : $subject;

        $alertClient->externalQueueAdd("12", ADMIN_EMAIL, 'teamldb@shiksha.com', $subject, $errorContent, "html", '', 'n');
    }

    public function sendCurlRequest($url){
        $ch = curl_init();

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);

        $result      = curl_exec($ch);
        $culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data        = json_decode($result, true);
        return $data;
    }
}
