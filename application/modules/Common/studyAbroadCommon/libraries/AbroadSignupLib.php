<?php
class AbroadSignupLib{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->abroadSignupModel = $this->CI->load->model('studyAbroadCommon/abroadsignupmodel');
    }    
    
    public function getMISTrackingDetails($trackingPageKeyId){
        $MISTrackingDetails = array();
        if(empty($trackingPageKeyId) || $trackingPageKeyId <=0){
            return $MISTrackingDetails;
        }

        $MISTrackingDetails = $this->abroadSignupModel->getMISTrackingDetails($trackingPageKeyId);
        if(!empty($MISTrackingDetails)){
            return $MISTrackingDetails[0];
        }else{
            return array();
        }
    }

    public function getListingDataForResponseForm(&$data)
    {
        if(!($data['courseId']>0) && !($data['universityId']>0))
        {
            return false;
        }
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder                         = new ListingBuilder;
        $this->abroadCourseRepository           = $listingBuilder->getAbroadCourseRepository();
        if($data['universityId']>0)
        {
            $this->abroadUniversityRepository   = $listingBuilder->getUniversityRepository();
            $data['universityObj']              = $this->abroadUniversityRepository->find($data['universityId']);
            //_p($data['universityObj']);
            $this->abroadListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
            
            // get all courses of univ
            $courseList = $this->abroadListingCommonLib->getUniversityCourses($data['universityObj']->getId(), 'stream');
            $courseList = array_filter($courseList['stream']['course_ids']);

            if(count($courseList) > 0 )
            {
                $data['courses'] = $this->abroadCourseRepository->findMultiple($courseList);
            }
        }else{
            $data['courses'] = array($this->abroadCourseRepository->find($data['courseId']));
        }
    }

    public function prepareDataForLoggedInUser(& $data){
        if(!empty($data['userInfoArray'])){
            $this->_prepareUserEducationalDetails($data);
        }
        $this->responseAbroadLib =  $this->CI->load->library('responseAbroad/ResponseAbroadLib');
        $userStartTimePrefWithExamsTaken = $this->responseAbroadLib->getUserStartTimePrefWithExamsTaken($data['userDetails']);
        //_p($userStartTimePrefWithExamsTaken);die;
        $data['userCity']                   = $userStartTimePrefWithExamsTaken['userCity'];
        $data['passport']                   = $userStartTimePrefWithExamsTaken['passport'];
        $data['userPreferredDestinations']  = $userStartTimePrefWithExamsTaken['userPreferredDestinations'];
        if($data['singleSignUpFormType']=='registration'){
            $data['preferredCourse']     = $userStartTimePrefWithExamsTaken['desiredCourse'];
            $data['preferredSpecialisation']     = $userStartTimePrefWithExamsTaken['abroadSpecialization'];
            if(!empty($data['preferredCourse']) && !in_array($data['preferredCourse'], $data['abroadDesiredCourseIds'])){
                $data['preferredCourse']     = $this->abroadSignupModel->getCategoryIdForDesireCourse($data['preferredCourse']);
            }
        }
        $data['userShortlistedCourseIds']=array();
        // get user shortlisted courses if conversion is of type "course shortlist"
        if($data['MISTrackingDetails']['conversionType'] == "Course shortlist")
        {
            $shortlistListingLib = $this->CI->load->library ( 'listing/ShortlistListingLib' );
            $userShortlistedCourses = $shortlistListingLib->fetchIfUserHasShortListedCourses(array('userId' =>$data['userDetails'][0]['userid']));
            if($userShortlistedCourses['count']>0){
                $data['userShortlistedCourseIds'] = $userShortlistedCourses['courseIds'];
            }
        }
        /*if(!empty($data['preferredCourse'])){
            $this->_getSpecialisationForPreferredCourse(&$data);    
        }*/
    }

    /*
        Btech :
        curernt class       class x board       current school name     class xth CGPA

        MS:
        Graduation percentage       Graduation stream       work Experiance
    */
    private function _prepareUserEducationalDetails(&$data){
        //echo '<br>in _prepareUserEducationalDetails function<br>';_p($data);die;
        if(!empty($data['userInfoArray']['desiredCourse'])){
            $currentLevel = '';
            if($data['singleSignUpFormType'] == 'registration' || $data['singleSignUpFormType'] == 'scholarshipResponse'){
                //_p($data['userInfoArray']);die;
                $currentLevel = $this->_getCurrentLevelByLDBCourseIdForSA($data['userInfoArray']['desiredCourse']);
                //_p($currentLevel);die;
                $this->_getEducationDetails($data,$currentLevel);
            }else if($data['singleSignUpFormType'] == 'response'){
                if($data['courseId'] > 0 && $data['MISTrackingDetails']['conversionType'] == 'response'){
                    $courseLevel = $data['courses'][0]->getCourseLevel1Value();
                    $currentLevel = $this->_getCurrentLevelByCourseLevel($courseLevel);
                }
                $this->_getEducationDetails($data,$currentLevel);
            }
            unset($data['userInfoArray']);
            $data['currentLevel'] = $currentLevel;
        }
        //_p($data);die;
    }

    private function _getCurrentLevelByLDBCourseIdForSA($ldbCourseId=''){
        if(empty($ldbCourseId)){
            return '';
        }
        global $studyAbroadPopularCourseToLevelMapping;
        if(!is_null($studyAbroadPopularCourseToLevelMapping[$ldbCourseId])){
            return $studyAbroadPopularCourseToLevelMapping[$ldbCourseId];
        }
        $regModel = $this->CI->load->model('registration/registrationmodel');
        $courseName = $regModel->getCurrentLevelByLDBCourseIdForSA($ldbCourseId);
        $courseName = $courseName['CourseName'];
        return $this->_getCurrentLevelByCourseLevel($courseName);
    }

    private function _getCurrentLevelByCourseLevel($courseLevel){
        if(strpos($courseLevel, 'Bachelors') !== false){
            $currentLevel = 'Bachelors';
        }else if(strpos($courseLevel, 'Masters') !== false){
            $currentLevel = 'Masters';
        }else if(strpos($courseLevel, 'PhD') !== false){
            $currentLevel = 'PhD';
        }
        return $currentLevel;
    }

    /*
     * wrapper to use the functionality of finding current level, given ldbCourseId
     */
    public function getCurrentLevelByLDBCourseId($ldbCourseId)
    {
        return $this->_getCurrentLevelByLDBCourseIdForSA($ldbCourseId);
    }
    
    private function _getEducationDetails(&$data ,$currentLevel =''){
        if($data['universityId'] > 0){
            $data['educationDetails'] = array(
                'currentClass'      => $data['userInfoArray']['CurrentClass'],
                'tenthBoard'        => $data['userInfoArray']['tenthBoard'],
                'currentSchoolName' => $data['userInfoArray']['CurrentSchoolName'],
                'tenthMarks'        => $data['userInfoArray']['tenthMarks'],
                'graduationPercentage'      => $data['userInfoArray']['graduationPercentage'],
                'graduationStream'      => $data['userInfoArray']['graduationStream']
            );
        }else{
            if($currentLevel == 'Bachelors'){
                $data['educationDetails'] = array(
                    'currentClass'      => $data['userInfoArray']['CurrentClass'],
                    'tenthBoard'        => $data['userInfoArray']['tenthBoard'],
                    'currentSchoolName' => $data['userInfoArray']['CurrentSchoolName'],
                    'tenthMarks'        => $data['userInfoArray']['tenthMarks']
                );
            }else if($currentLevel == 'Masters' || $currentLevel == 'PhD'){
                $data['educationDetails'] = array(
                    'graduationPercentage'      => $data['userInfoArray']['graduationPercentage'],
                    'graduationStream'      => $data['userInfoArray']['graduationStream']
                );
            }
        }
    }
    
    /*
     * function that gets values from signUpFormParams cookie 
     */
    public function getSignupFormParams(&$data)
    {
        $cookieData = json_decode(base64_decode($_COOKIE['signUpFormParams']),true);
        if(is_null($cookieData))
        {
            return false;
        }
        $data['trackingPageKeyId'] = $this->CI->security->xss_clean($cookieData['tkey']);
        $data['customReferer'] = $this->CI->security->xss_clean($cookieData['rf']);
        $data['refererTitle'] = $this->CI->security->xss_clean(urldecode($cookieData['rftl']));
        $data['MISTrackingDetails'] = $this->abroadSignupModel->getMISTrackingDetails($data['trackingPageKeyId']);
        $data['MISTrackingDetails'] = $data['MISTrackingDetails'][0];
        // find intent / action taken using tracking page key id
        switch($data['MISTrackingDetails']['conversionType'])
        {
            case 'response': // dl brochure
                    $data['universityId'] = $this->CI->security->xss_clean($cookieData['uId']);
                    $data['courseId'] = $this->CI->security->xss_clean($cookieData['cId']);
                    $data['scholarshipId'] = $this->CI->security->xss_clean($cookieData['schrId']);
                    $data['sourcePage'] = $this->CI->security->xss_clean($cookieData['srcpg']);
                    $data['responseAction'] = $this->CI->security->xss_clean($cookieData['action']);
                    $data['widget'] = $this->CI->security->xss_clean($cookieData['wdgt']);
                    if(isMobileRequest())
                    {
                        $data['brochObj'] = $this->CI->security->xss_clean($cookieData['brochobj']);
                        if($data['brochObj']=="" && $data['universityId']!="")
                        {
                            $data['brochObj'] = $this->_prepareBrochureObj($data['universityId'],$data);
                        }
                    }
                    break;
            case 'Course shortlist': // shortlist
                    $data['courseId'] = $this->CI->security->xss_clean($cookieData['cId']);
                    $data['sourcePage'] = $this->CI->security->xss_clean($cookieData['srcpg']);
                    $data['universityId'] = $this->CI->security->xss_clean($cookieData['uId']);
                    break;
            case 'downloadGuide': // dl guide
                    $data['contentId'] = $this->CI->security->xss_clean($cookieData['contId']);
                    $data['contentType'] = $this->CI->security->xss_clean($cookieData['cType']);
                    $data['url'] = $this->CI->security->xss_clean($cookieData['url']);
                    break;
            case 'shipmentBooking': // dhl pickup
                    break;
            case 'compare': 
                    $data['compCourseId'] = $this->CI->security->xss_clean($cookieData['cId']);
                    $data['compSource'] = $this->CI->security->xss_clean($cookieData['srcno']);
                    break;
            case 'profileEvaluationCall': // gfpec
                    $data['url'] = $this->CI->security->xss_clean($cookieData['url']);
                    $data['examId'] = $this->CI->security->xss_clean($cookieData['eid']);
                    $data['counselorId'] = $this->CI->security->xss_clean($cookieData['cnslrId']);
                    break;
            case 'registration': // signup
                    // gfpec
                    if($data['MISTrackingDetails']['keyName'] == 'gfpecCTA'){
                        $data['url'] = $this->CI->security->xss_clean($cookieData['url']);
                    	$data['examId'] = $this->CI->security->xss_clean($cookieData['eid']);
                    	$data['counselorId'] = $this->CI->security->xss_clean($cookieData['cnslrId']);
                    }
                    break;
            case 'counselorReviewPost':
                    	$data['counselorId'] = $this->CI->security->xss_clean($cookieData['cnslrId']);
                    break;
        }
        return true;
        //_p($data);
    }
    
    private function _prepareBrochureObj($universityId,$data)
    {
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $universityRepository = $listingBuilder->getUniversityRepository();
        if($universityId>0)
        {
            $universityObj = $universityRepository->find($universityId);
            if($universityObj->getId()!=NULL){
                $brochureDataObj = array(
                    'trackingPageKeyId'=> $data['MISTrackingDetails']['id'],
                    'sourcePage'       => 'university',
                    'universityId'     => $universityObj->getId(),
                    'universityName'   => $universityObj->getName(),
                    'destinationCountryId'	=> $universityObj->getLocation()->getCountry()->getId(),
                    'destinationCountryName'=> $universityObj->getLocation()->getCountry()->getName(),
                    'mobile' => true  
                );
            }
        }
        return $brochureDataObj;
    }
    /*
     * beacon track data
     */
    public function prepareTrackingData($pageIdentifier,$data = null){
        if(is_null($data))
        {
            $extraData = null;
        }else{
            $extraData = array();
            if(is_object($data['universityObj']))
            {
                $extraData['cityId'] = $data['universityObj']->getMainLocation()->getCity()->getId();
                $extraData['stateId'] = $data['universityObj']->getMainLocation()->getState()->getId();
                $extraData['countryId'] = $data['universityObj']->getMainLocation()->getCountry()->getId();
            }else if(count($data['courses']) == 1){
                $courseObj = reset($data['courses']);
                $subcat = $courseObj->getCourseSubCategory();
                $LDBCourseId = $courseObj->getDesiredCourseId() > 0 ? $courseObj->getDesiredCourseId(): $courseObj->getLDBCourseId();
                //$extraData['categoryId'] =
                $extraData['subCategoryId'] = $subcat;
                $extraData['LDBCourseId'] = $LDBCourseId;
                $extraData['countryId'] = $courseObj->getCountryId();
                $extraData['courseLevel'] = $courseObj->getCourseLevel1Value();
            }
        }
        $beaconTrackData = array(
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId' => 0,
            'extraData' => $extraData
        );
        return $beaconTrackData;
    }

    public function prepareThankYouPageTrackingData(&$data){        
        $MISTrackingDetails = $data['MISTrackingDetails'];
        if($MISTrackingDetails['conversionType'] == 'downloadGuide'){
            $pageIdentifier = 'downloadGuideThankYouPage';
            $entityId = $data['contentId'];
        }else if($MISTrackingDetails['conversionType'] == 'response' && $MISTrackingDetails['keyName'] == 'downloadBrochure'){
            $pageIdentifier = 'downloadBrochureThankYouPage';
            $entityId = $data['courseIdForResponse'];
        }else if($MISTrackingDetails['conversionType'] == 'response' && ($MISTrackingDetails['keyName'] = 'scholarshipDownloadBrochure')){
            $pageIdentifier = 'scholarshipDownloadBrochureThankYouPage';
            $entityId = $data['scholarshipIdForResponse'];
        }

        $beaconTrackData = array(
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId'   => $entityId,
            'extraData'      => array()
        );
        $data['beaconTrackData'] = $beaconTrackData;
    }

    public function setSEODetailsForSignup(&$displayData)
    {
        $displayData['seoDetails']['title'] = 'Signup to get started on Shiksha';
    }
    /*
     * get seo details for thank you page
     */
    public function getSEOForThankYouPage(& $data)
    {
        $MISTrackingDetails = $data['MISTrackingDetails'];
        $seoDetails = array();
        if($MISTrackingDetails['conversionType'] == 'downloadGuide')
        {
            // SEO for download guide ty page
            $seoDetails['url'] = SHIKSHA_STUDYABROAD_HOME.'/thank-you-for-downloading-guide';
            $seoDetails['title'] = 'Thank you for Downloading Guide!';
            $seoDetails['description'] = 'The guide has been sent to your email address as an attachment. Please check your inbox. In case of any queries, please get in touch with Shiksha.';
            $data['downloadMessageType'] = 'downloadGuide';
            
        }else if($MISTrackingDetails['conversionType'] == 'response' && ($MISTrackingDetails['keyName'] == 'downloadBrochure'||$MISTrackingDetails['keyName'] == 'emailBrochure')){
            // SEO for download brochure ty page
            $seoDetails['url'] = SHIKSHA_STUDYABROAD_HOME.'/thank-you-for-downloading-brochure';
            $seoDetails['title'] = 'Thank you for Downloading Brochure!';
            $seoDetails['description'] = 'The brochure has been sent to your email address as an attachment. Please check your inbox. In case of any queries, please get in touch with Shiksha.';
            $data['downloadMessageType'] = 'downloadBrochure';
        }else if($MISTrackingDetails['conversionType'] == 'response' && ($MISTrackingDetails['keyName'] = 'scholarshipDownloadBrochure')){
            // SEO for scholarship download brochure ty page
            $seoDetails['url'] = SHIKSHA_STUDYABROAD_HOME.'/thank-you-for-downloading-scholarship-brochure';
            $seoDetails['title'] = 'Thank you for Downloading Scholarship Brochure!';
            $seoDetails['description'] = 'The brochure has been sent to your email address as an attachment. Please check your inbox. In case of any queries, please get in touch with Shiksha.';
            $data['downloadMessageType'] = 'scholarshipDownloadBrochure';
        }
        $data['seoDetails'] = $seoDetails;
    }
    
    /*
     * function to get download message along with other data like
     * - recommendation in case of dl brochure
     * - also viewed / popular guides in case of dl guide
     */
    public function getDownloadMessageWithData(& $data)
    {
        if($data['downloadMessageType'] == 'downloadBrochure')
        {
            $data['dlBrochureData'] = $this->_getDataForDLBrochureMessageWithReco($data);
        }
        else if($data['downloadMessageType'] == 'downloadGuide')
        {
            $data['dlGuideData'] = $this->_getDataForDLGuideMessage($data);
        }
        else if($data['downloadMessageType'] == 'scholarshipDownloadBrochure')
        {
            $data['dlScholarshipBrochureData'] = $this->_getDataForDLScholarshipBrochureMessage($data);
        }
    }

    /*
     * function to get dl scholarship brochure message , dl links
     */
    private function _getDataForDLScholarshipBrochureMessage($data)
    {
        if(is_null($data['scholarshipIdForResponse'])){
            return false;
        }
        $this->CI->load->builder('scholarshipsDetailPage/scholarshipBuilder');
        $this->scholarshipBuilder    = new scholarshipBuilder();
        $this->scholarshipRepository = $this->scholarshipBuilder->getScholarshipRepository();
        $dlScholarshipBrochureData = array();
        $sections = array('application'=>array('applyNowLink', 'scholarshipBrochureUrl'));
        $dlScholarshipBrochureData['scholarshipObj'] = $this->scholarshipRepository->find($data['scholarshipIdForResponse'], $sections);
        $dlScholarshipBrochureData['brochureURLSize'] = getRemoteFileSize($dlScholarshipBrochureData['scholarshipObj']->getApplicationData()->getBrochureUrl());
        return $dlScholarshipBrochureData;
    }

    /*
     * function to get dl brochure message , dl links, recommendations 
     */
    private function _getDataForDLBrochureMessageWithReco($data)
    {
        if(is_null($data['courseIdForResponse']))
        {
            return false;
        }
        // get Course obj
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder                         = new ListingBuilder;
        $this->abroadCourseRepository           = $listingBuilder->getAbroadCourseRepository();
        $dlBRochureData['courseObj'] = $this->abroadCourseRepository->find($data['courseIdForResponse']);
        if(!($dlBRochureData['courseObj'] instanceof AbroadCourse))
        {
            return false;
        }else{
            // get download links with file size...
            $this->abroadListingCommonLib   = $this->CI->load->library('listing/AbroadListingCommonLib');
            $dlBRochureData['brochureURL']  = $this->abroadListingCommonLib->getCourseBrochureUrl($dlBRochureData['courseObj']->getId());
            if($dlBRochureData['brochureURL'] != ''){
                $dlBRochureData['brochureURL'] = $dlBRochureData['brochureURL'];
            }
            $dlBRochureData['brochureURLSize'] = getRemoteFileSize($dlBRochureData['brochureURL']);
            // university brochure 
            $this->abroadUnivRepository = $listingBuilder->getUniversityRepository();
            $universityObj = $this->abroadUnivRepository->find($dlBRochureData['courseObj']->getUniversityId());
            $dlBRochureData['universityBrochureURL'] = $universityObj->getBrochureLink();
            if($dlBRochureData['universityBrochureURL'] != ''){
                $dlBRochureData['universityBrochureURL'] = $dlBRochureData['universityBrochureURL'];
                $dlBRochureData['UniversityBrochureURLSize'] = getRemoteFileSize($dlBRochureData['universityBrochureURL']);
            }
            // get recommendations for this course
            if(isMobileRequest()){
                $listingPage = $this->CI->load->module('listingPage/ListingPage');
                $customData = array('courseId'=>$dlBRochureData['courseObj']->getId(),
                    'trackingPageKeyId'=>1939,
                    'shortlistTrackingPageKeyId'=>1941,
                    'rmcRecoTrackingPageKeyId'=>1943,
                    'compareTrackingPageKeyId'=>1947,
                    'title'=>$data['refererTitle'],
                    'refererTitle'=>$data['refererTitle'],
                    'referer'=>$data['customReferer'],
                    'widget'=>'thankYouReco',
                    'sourcePage'=>'mobileSA');
                $dlBRochureData['reco'] = $listingPage->getAbroadRecommendations($customData);
                //_p($dlBRochureData['reco']);die;
            }else{
                $abroadListings = $this->CI->load->module('listing/abroadListings');
                $dlBRochureData['reco'] = $abroadListings->getAbroadRecommendations('alsoViewed',$dlBRochureData['courseObj']->getId(), '', '',1211,'',1212,1213,'signupFormThankYouPage','signupFormThankYouPage');
            }
            //$this->abroadListing->getAbroadRecommendations('alsoViewed', $courseId, '', '','', '','','','rateMyChancesSuccessPage','rateMyChancesSuccessPage',$displayData['returnPageTitle'])
            return $dlBRochureData;
        }
    }
    /*
     * function to get dl Guide message , dl links, also viewed guides
     */
    private function _getDataForDLGuideMessage(& $data)
    {
        $numResults = 9;
        // get content name
        switch($data['contentType'])
        {
            case 'guide':
            case 'examContent':
                $data['downloadControllerUrl'] = '/blogs/SAContent/downloadGuide/';
                // related guides
                $this->saContentLib = $this->CI->load->library('blogs/saContentLib');
                $relatedGuides = $this->saContentLib->getRecommendedContents($data['contentId'],array($data['contentType']),$numResults);
                break;
            case 'applyContent':
                $data['downloadControllerUrl'] = '/applyContent/applyContent/downloadGuide/';
                $this->applyContentLib = $this->CI->load->library('applyContent/ApplyContentLib');
                $applyContentTypeId = $this->applyContentLib->getApplyContentTypeIdById($data['contentId']);
                // related guides
                $relatedGuides = $this->applyContentLib->getRecommendedContents($data['contentType'],$applyContentTypeId[$data['contentId']],$data['contentId'],$numResults);
                for($i=0;$i<count($relatedGuides);$i++)
                {
                    $applyContentDownloadLinks[$relatedGuides[$i]['content_id']] = $this->applyContentLib->getGuideURL($relatedGuides[$i]['content_id']);
                }
                break;
            default : return false;
                break;
        }
        $guideIds = array_map(function($a){ return $a['content_id']; },$relatedGuides);
        $this->sacontentmodel = $this->CI->load->model('blogs/sacontentmodel');
        $downloadCounts = $this->sacontentmodel->downloadCountForGuide($guideIds);
        // get current content too
        $guideIds[] = $data['contentId'];
        $contentDetails = $this->sacontentmodel->getContentBasicDetails($guideIds);
        $data['contentData'] = $contentDetails[$data['contentId']];
        
        $guideUrl = $contentDetails[$data['contentId']]['download_link'];
        if($guideUrl == MEDIAHOSTURL) {
            if($data['url'] !=""){
                $guideUrl = base64_decode($data['url']);
            }else{
                $cookieData = json_decode(base64_decode($_COOKIE['signUpFormParams']),true);
                $guideUrl = base64_decode($cookieData['url']);
            }
        }

        $data['contentData']['download_size'] = getRemoteFileSize($guideUrl);
        $data['relatedGuides'] = array();
        foreach($relatedGuides as $guideInfo)
        {
            if(!is_null($applyContentDownloadLinks[$guideInfo['content_id']]))
            {
                $downloadLink = $applyContentDownloadLinks[$guideInfo['content_id']];
            }else{
                $downloadLink = $contentDetails[$guideInfo['content_id']]['download_link'];
            }
            $guideData = array('contentId'=>$guideInfo['content_id'],
                               'contentType'=>$contentDetails[$guideInfo['content_id']]['type'],
                               'contentUrl'=> $guideInfo['contentUrl'],
                               'strip_title'=> $contentDetails[$guideInfo['content_id']]['strip_title'],
                               'downloadCount'=> $downloadCounts[$guideInfo['content_id']],
                               'summary'=> strip_tags($contentDetails[$guideInfo['content_id']]['summary']),
                               'download_link'=> $downloadLink,
                               'contentImageURL'=>$contentDetails[$guideInfo['content_id']]['contentImageURL']);
            $data['relatedGuides'][] = $guideData;
        }
    }
    /*
     * check if given user has logged in within past X (default 21) days
     */
    public function checkIfUserVisitedInXDays($userId, $days = 21)
    {
        $this->abroadCommonLib = $this->CI->load->library('common/studyAbroadCommonLib');
        $lastVisitTime = $this->abroadCommonLib->getUserLastVisitTime($userId,'studyAbroad');
        if(is_null($lastVisitTime) || $lastVisitTime == ''){
            return false;
        }
        $lastVisitTime = date_create($lastVisitTime);
        $comparisonDate = date_create(date("Y-m-d H:i:s"));
        date_sub($comparisonDate, date_interval_create_from_date_string($days.' days'));
        if($lastVisitTime < $comparisonDate)
        {	// older 
            return true;
        }else{	// newer
            return false;
        }
    }
}
