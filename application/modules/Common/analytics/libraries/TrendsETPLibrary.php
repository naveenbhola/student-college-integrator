<?php

/**
 * Class TrendsETPLibrary
 * Library for shiksha analytics/trends home
 * @date    2017-10-09
 * @author  Romil Goel
 * @todo    none
 *
 */
class TrendsETPLibrary
{
    function __construct(){

        $this->CI = & get_instance();

        // get the trend repo
        $this->CI->load->builder("analytics/TrendsBuilder");
        $trendsBuilder = new TrendsBuilder();
        $this->trendsRepo = $trendsBuilder->getTrendsRepository();
        $this->CI->load->config('nationalInstitute/instituteStaticAttributeConfig');
        $this->trendsmodel = $this->CI->load->model("trendsmodel");
    }

    function getPageData($entityType, $entityId){

        $data = array();

        switch ($entityType) {
            case 'institute':
            case 'university':
                $data = $this->_getListingData($entityType, $entityId);
                break;

            case 'base_course':
                $data = $this->_getBaseCourseData($entityType, $entityId);
                break;

            case 'specialization':
                $data = $this->_getSpecializationData($entityType, $entityId);
                break;

            case 'exam':
                $data = $this->_getExamData($entityType, $entityId);
                break;

            case 'stream':
                $data = $this->_getStreamData($entityType, $entityId);
                break;

            case 'substream':
                $data = $this->_getSubStreamData($entityType, $entityId);
                break;

            default:
                show_404();
                break;
        }

        $this->_getSeoData($data);

        return $data;
    }

    function _getListingData($entityType, $entityId){
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        if($entityId == 0) {show_404();}
        $listingObj = $instituteRepo->find($entityId, array('basic', 'media'));

        // Redirecting all URL's to 404 which are for the wrong listing type
        $listing_type = $listingObj->getType();
        if($listing_type != $entityType)
            show_404();

        $data = array();

        $data['instituteObj'] = $listingObj;
        if($listingObj->getId() == "")
            show_404();

        $headerImage = $listingObj->getHeaderImage();

        if($headerImage && $headerImage->getUrl()){
            $data['headerImage']['url'] = $headerImage->getUrl();
            $data['headerImage']['url'] = getImageVariant($data['headerImage']['url'], 6);
            $data['headerImage']['title'] = $listingObj->getName();
        }
        else{
            $data['headerImage']['url'] = MEDIAHOSTURL."/public/images/instDefault.png";
            $data['headerImage']['title'] = $listingObj->getName();
        }

        $data['entityName'] = $listingObj->getName();

        $mainLocation = $listingObj->getMainLocation();
        $locationName = $mainLocation->getCityName();
        if($mainLocation->getLocalityName())
            $locationName = $mainLocation->getLocalityName().", ".$locationName;

        $data['locationName'] = $locationName;

        $ownership   = $listingObj->getOwnership();
        $studentType = $listingObj->getStudentType();
        $estbYear    = $listingObj->getEstablishedYear();

        $inlineData = array();
        $static_data = $this->CI->config->item('static_data');

        if($ownership){
            foreach ($static_data['ownernship'] as $value) {
                if($value['value'] == $ownership)
                    $inlineData['ownership'] = $value['label'];
            }
        }

        if($estbYear){
            if($isMobile)
                $inlineData['estbYear'] = $estbYear;
            else
                $inlineData['estbYear'] = 'Established '.$estbYear;
        }

        if($studentType && $studentType != 'co-ed'){
            foreach ($static_data['student_type'] as $value) {
                if($value['value'] == $studentType)
                    $inlineData['studentType'] = $value['label'];
            }
        }

        $inlineData = implode(" <span>|</span> ", $inlineData);
        $data['inlineText'] = $inlineData;
        $data['entityListingType'] = 'institute';
        if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
            $data['brochureTrackingId'] = $entityType == 'institute' ? 1343 : 1341;
            $data['shortlistTrackingId'] = $entityType == 'institute' ? 1347 : 1345;
            $data['compareTrackingId'] = $entityType == 'institute' ? 1351 : 1349;
        }
        else{
            $data['brochureTrackingId'] = $entityType == 'institute' ? 1342 : 1340;
            $data['shortlistTrackingId'] = $entityType == 'institute' ? 1346 : 1344;
            $data['compareTrackingId'] = $entityType == 'institute' ? 1350 : 1348;
        }

        $this->instituteDetailLib = $this->CI->load->library("nationalInstitute/InstituteDetailLib");
        $courseData     = $this->instituteDetailLib->getInstituteCourseIds($entityId, $entityType);
        $allCourseList  = $courseData['courseIds'];

        $this->CI->load->builder("nationalCourse/CourseBuilder");
        $builder          = new CourseBuilder();
        $courseRepository = $builder->getCourseRepository();
        
        // getting all courses because of all base course/stream section
        if($allCourseList)
            $allCourses  = $courseRepository->findMultiple($allCourseList, array('basic'), false, false);
        sort($allCourseList);

        $data['coursesWidgetData']['allCourses'] = $allCourses;
        
        $this->_getCommonWidgetData($entityType, $entityId, $data);

        return $data;
    }

    function _getBaseCourseData($entityType, $entityId){

        $data = array();

        $entityData                   = $this->trendsmodel->getLabelsForCourseEntities('baseCourse', array($entityId));
        $data['entityName']           = str_replace("&", "and", $entityData[$entityId]['name']);
        
        if(empty($data['entityName'])) { 
            error_log("Error Occured : No Exam Object Exists");
            show_404();exit(0);
        }  

        $this->_getCommonWidgetData($entityType, $entityId, $data);

        return $data;
    }

    function _getSpecializationData($entityType, $entityId){

        $data = array();

        $entityData                   = $this->trendsmodel->getLabelsForCourseEntities('specialization', array($entityId));
        $data['entityName']           = str_replace("&", "and", $entityData[$entityId]['name']);
        if(empty($data['entityName'])) { 
            error_log("Error Occured : No Exam Object Exists");
            show_404();exit(0);
        } 
        $this->_getCommonWidgetData($entityType, $entityId, $data);

        return $data;
    }

    function _getExamData($entityType, $entityId){

        $data = array();

        $entityData                   = $this->trendsmodel->getLabelsForExams(array($entityId));
        $data['entityName']           = array_search($entityId, $entityData['mapping']);
        $data['entityName']           = $data['entityName'] ? $data['entityName'] : "";

        $this->CI->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $this->examRepository = $examBuilder->getExamRepository();
        $examBasicObj = $this->examRepository->find($entityId);
        if(empty($examBasicObj)){
            error_log("Error Occured : No Exam Object Exists");
            show_404();exit(0);
        }

        $primaryGroup = $examBasicObj->getPrimaryGroup();
        $primaryGroupId = $primaryGroup['id'];
        if(empty($primaryGroupId)){
            error_log("Error Occured : No Primary group id exists");
            show_404();exit(0);
        }

        if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
            $data['guideTrackingId'] = 1353;
            
        }
        else{
            $data['guideTrackingId'] = 1352;
        }
        $data['examBasicObj'] = $examBasicObj;       
        $data['examId'] = $entityId;
        $data['groupId'] = $primaryGroupId;

        $this->examPageLib = $this->CI->load->library("examPages/ExamPageLib");

        $data['guideDownloaded'] = $this->examPageLib->checkActionPerformedOnGroup($primaryGroupId,'examGuide');
        
        $this->_getCommonWidgetData($entityType, $entityId, $data);

        return $data;   
    }

    function _getStreamData($entityType, $entityId){
        $data = array();

        $entityData                   = $this->trendsmodel->getLabelsForCourseEntities('stream', array($entityId));
        $data['entityName']           = str_replace("&", "and", $entityData[$entityId]['name']);
        
        if(empty($data['entityName'])) { 
            error_log("Error Occured : No Exam Object Exists");
            show_404();exit(0);
        }  

        $this->_getCommonWidgetData($entityType, $entityId, $data);
        return $data;   
    }

    function _getSubStreamData($entityType, $entityId){

        $data = array();

        $entityData                   = $this->trendsmodel->getLabelsForCourseEntities('substream', array($entityId));
        $data['entityName']           = str_replace("&", "and", $entityData[$entityId]['name']);
        if(empty($data['entityName'])) { 
            error_log("Error Occured : No Exam Object Exists");
            show_404();exit(0);
        } 
        
        $this->_getCommonWidgetData($entityType, $entityId, $data);
        return $data;   
    }

    private function _getCommonWidgetData($entityType, $entityId, &$data){

        $data['entityType'] = $entityType;
        $data['entityId']   = $entityId;

        $data['popular_institutes'] = $this->trendsRepo->getPopularInstitutesData(null, null, 1, $entityType, $entityId);
        // get interest chart data
        $data['interest_data']          = $this->trendsRepo->getInterestByTimeData($entityType, $entityId);
        $data['region_data']            = $this->trendsRepo->getInterestByRegion($entityType, $entityId);
        if(!in_array($entityType, array("exam"))){

            $data['popular_courses']        = $this->trendsRepo->getPopularCoursesData(null, null, 1, $entityType, $entityId);
            $data['popular_specialization'] = $this->trendsRepo->getPopularSpecializationsData(null, 1, $entityType, $entityId);
            if(!empty($data['popular_specialization']['specialization'])){
                foreach ($data['popular_specialization']['specialization'] as $key => &$value) {
                    $value['text'] = str_replace("&", "and", $value['text']);
                }
            }

            if(!empty($data['popular_specialization']['streams'])){
                foreach ($data['popular_specialization']['streams'] as $key => &$value) {
                    $value = str_replace("&", "and", $value);
                }
            }

            $data['popular_specialization']['fullwidthview'] = true;
        }
        $data['popularityIndex']        = $this->trendsRepo->getPopularityIndex($entityType, $entityId);
        $data['popular_questions']      = $this->trendsRepo->getPopularQuestionsData($entityType, $entityId);
        $data['popular_articles']       = $this->trendsRepo->getPopularArticlesData($entityType, $entityId);

        if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
            $data['linkTarget'] = "";
        }
        else{
            $data['linkTarget'] = "_blank";
        }

        return $data;
    }

    private function _getSeoData(&$data){

        $data['seoTitle'] = "Trends - ".$data['entityName'];
        $data['seoDesc'] = "Trends - ".$data['entityName'];
    }
}
?>