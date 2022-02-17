<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Desc   	: Prepare DoubleClick for Publishers (DFP) data for page wise
$params : array
$user   : $this->userStatus
@uthor  : akhter
*/
class DFPLib {

	private $CI;
	function __construct()
    {
        $this->CI =& get_instance();
        $this->usermodel = $this->CI->load->model('user/UserModel');
    }

    function getDFPData($user, $params){
        $d = Array();
        if(($user == "false" ) || empty($user)) {
            $d['userLoggedIn'] = 'No';
        }else{
            $userId     = ($user[0]['userid']) ? $user[0]['userid'] : $user['userid'];
            $userCity   = ($user[0]['city']) ? $user[0]['city'] : $user['city'];
            $userWorkEx = ($user[0]['experience']) ? $user[0]['experience'] : $user['experience'];
            $userDetails= $this->getUserInterest($userId);
            $d['userLoggedIn']   = 'Yes';
            $d['userCity']       = $userCity;
            $d['userLocality']   = $userDetails['residenceLocality'];
            $d['userStream']     = $userDetails['stream'];
            $d['userBaseCourse'] = $userDetails['baseCourses'];
            $d['userSubstream']  = $userDetails['substream'];
            $d['userSpecialization'] = $userDetails['specialization'];
            $d['userEducationType']  = $userDetails['educationType']; //including deliveryMethod
            $d['userWorkEx'] = $userWorkEx;
            $d['userMarks']  = '';
            $d['userPercentile'] = '';
        }

        if(!empty($params['courseObj']) && is_object($params['courseObj'])){
            $courseObj = $params['courseObj'];
            $hierarchy  = $courseObj->getPrimaryHierarchy();
            $baseCourse = $courseObj->getBaseCourse();
            $baseCourse = $baseCourse['entry'];
            $instituteId = $courseObj->getInstituteId();

            $this->instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');

            $isHierarchyPaid = $this->instituteDetailLib->getInstitutePaidStatus($instituteId);

            $client = '0';
            if(!empty($isHierarchyPaid) && $isHierarchyPaid[$instituteId]['isHierarchyPaid']){
                $client = 1;                
            }else {
                $client          = $this->instituteDetailLib->isPaidInstitute(array($instituteId));
                if($client[$instituteId]) {
                    $client = 1;
                }
            }

            if($courseObj->getAllEligibilityExams()){
                $ExamsAcceptedByInstitute = array_values($courseObj->getAllEligibilityExams());
            }

            if($courseObj->getEducationType()){
                $educationType = $courseObj->getEducationType()->getId();
            }

            if($courseObj->getDeliveryMethod()){
                $deliveryMethod = $courseObj->getDeliveryMethod()->getId();
            }

            $courseTypeInfo =       $courseObj->getCourseTypeInformation();
            if(!empty($courseTypeInfo) && !empty($courseTypeInfo['entry_course'])){
                $courseLevel = ($courseTypeInfo['entry_course']->getCourseLevel()) ? $courseTypeInfo['entry_course']->getCourseLevel()->getId() : '';
                $courseCredential   = ($courseTypeInfo['entry_course']->getCredential()) ? $courseTypeInfo['entry_course']->getCredential()->getId() : '';
            }

            $duration = $courseObj->getDuration();
            $duration = $duration['value'].' '.$duration['unit'];
            $entity_id = $courseObj->getId();
            $city  = ($params['city']) ? $params['city'] : '';
            $state = ($params['state']) ? $params['state'] : '';
            if(!empty($params['courseLocation'])){
                $city  = $params['courseLocation']->getCityId();
                $state = $params['courseLocation']->getStateId();
            }
        }else if(!empty($params['instituteObj']) && is_object($params['instituteObj'])){
            $instituteObj = $params['instituteObj'];
            $entity_id = $instituteObj->getId();
            $ownership = ($instituteObj->getOwnership()) ? $instituteObj->getOwnership() : '';
            if(!empty($params['instituteLocationObj'])){
                $city  = $params['instituteLocationObj']->getCityId();
                $state = $params['instituteLocationObj']->getStateId();
            }
            $this->instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
            $isHierarchyPaid = $this->instituteDetailLib->getInstitutePaidStatus($entity_id);
            $client = '0';
            if(!empty($isHierarchyPaid) && $isHierarchyPaid[$entity_id]['isHierarchyPaid']){
                $client = 1;                
            }
        }else if(!empty($params['groupObj']) && is_object($params['groupObj'])){
            $groupObj    = $params['groupObj'];
            $entity_id   = $params['examId'];
            $examGroupId = $params['groupId'];   

            $client = '0';
            if(is_array($params['conductedBy']) && $params['conductedBy']['instituteId']){
                $this->instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
                $isHierarchyPaid = $this->instituteDetailLib->getInstitutePaidStatus($params['conductedBy']['instituteId']);
            }
            if(!empty($isHierarchyPaid) && $isHierarchyPaid[$params['conductedBy']['instituteId']]['isHierarchyPaid']){
                $client = 1;                
            }

            $forceClient = array(9180,13552,9179,13541,13549,12850,10306,3275); //MAB-5163
            if(in_array($entity_id, $forceClient)){
                $client = 1;   
            }

            $conductedBy = is_array($params['conductedBy']) ? $params['conductedBy']['name'] : $params['conductedBy'];

            $conductedBy =  str_ireplace("'",  "&apos;", $conductedBy);
            $conductedBy =  str_ireplace("\\", "&bsol;", $conductedBy);
            $conductedBy =  str_ireplace('"',  "&quot;", $conductedBy);

            $mapping     = $groupObj->getEntitiesMappedToGroup();
            $baseCourse  = $mapping['course'];
            $this->CI->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new ListingBaseBuilder();
            $hierarchyRepo = $listingBase->getHierarchyRepository();
            $hierarchyData = $hierarchyRepo->getBaseEntitiesByHierarchyId($mapping['primaryHierarchy'][0]);
            $hierarchy = $hierarchyData[$mapping['primaryHierarchy'][0]];
        }

        $d['isDomestic'] = 'Yes';
        $d['parentPage'] = $params['parentPage'];
        $d['pageType']   = $params['pageType']; // Page Type (different for child pages)
        $d['stream']     = ($hierarchy['stream_id']) ? $hierarchy['stream_id'] : $params['stream_id'];
        $d['substream']  = ($hierarchy['substream_id']) ? $hierarchy['substream_id'] : $params['substream_id'];
        $d['specialization']     = ($hierarchy['specialization_id']) ? $hierarchy['specialization_id'] : $params['specialization_id'];
        $d['baseCourse']         = ($baseCourse) ? $baseCourse : $params['baseCourse'];
        $d['examConductingBody'] = ($conductedBy) ? $conductedBy : '';
        $d['ownership']  = ($params['ownership']) ? $params['ownership'] : $ownership;
        $d['client']     = ($client) ? $client : '0';
        $d['educationType']  = ($educationType) ? $educationType : $params['educationType'];
        $d['deliveryMethod'] = ($deliveryMethod) ? $deliveryMethod : $params['deliveryMethod'];
        $d['listingLocationCity']  = ($city) ? $city : $params['cityId'] ;
        $d['listingLocationState'] = ($state) ? $state : $params['stateId'] ;
        $d['countryId']  = 2;
        $d['examsAcceptedByInstitute'] = $ExamsAcceptedByInstitute; // from course only
        $d['courseLevel']      = $courseLevel;
        $d['courseCredential'] = ($courseCredential) ? $courseCredential : $params['courseCredential'];
        $d['courseDuration']   = $duration;
        $d['examGroupId']      = ($examGroupId) ? $examGroupId : '';
        $d['pageId']     = ($params['entity_id']) ? $params['entity_id'] :  $entity_id;
        $d['anaTags']    = $params['anaTags']; // should be array if more than one tag
        $d['examName']   = $params['examName']; // for College Predictor only
        $d['inputRank']  = $params['inputRank']; // for College Predictor only
        $d['iimPercentile']  = $params['iimPercentile']; // for IIM Call Predictor only
        $d['addMoreDfpSlot'] = $params['addMoreDfpSlot']; // for add more defineSlot (optional)
        return $d;
    }

    private function getUserInterest($userId){
        $userDetails = array();
        if(empty($userId)){
            return $userDetails;
        }
        $userObj     = $this->usermodel->getUserById($userId, false);
        if(empty($userObj)){
            return $userDetails;
        }
        /*User Study Preference Data*/
        $userinterest = $userObj->getUserInterest();
        if (!empty($userinterest) && is_object($userinterest)) {
            $stream = 0;
            $baseCourses     = array();
            $subStreamSpec = array();
            $educationMode   = array();

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
            $userDetails['baseCourses']     = $stringyBaseCourse;
            $userDetails['subStreamSpec']   = $subStreamSpec;
            $userDetails['educationType']   = $stringyfyEducationMode;
            $userDetails['residenceLocality']     = $userObj->getLocality();

            foreach ($userDetails['subStreamSpec'] as $substr => $value) {
                if($substr != 'ungrouped'){
                    $substream[] = $substr;
                }
                foreach ($value as $key => $v) {
                    $spec[] = $v;
                }
            }
            $userDetails['substream'] = $substream;
            $userDetails['specialization'] = $spec;
            unset($userDetails['subStreamSpec']);
        }
        return $userDetails;
    }
}
?>
