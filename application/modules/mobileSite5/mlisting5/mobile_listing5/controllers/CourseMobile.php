<?php
class CourseMobile extends ShikshaMobileWebSite_Controller{

	private function _init(){
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->coursedetailmodel = $this->load->model('nationalCourse/coursedetailmodel');
 
        $this->load->helper('listingCommon/listingcommon');
	    $this->load->helper(array('string','image'));

        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();   
        $this->load->config('nationalCourse/courseConfig'); 

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();  

        $this->load->library('nationalCourse/CourseDetailLib');
        $this->load->config('CollegeReviewForm/collegeReviewConfig'); 

        $this->courseDetailLib = new CourseDetailLib; 

        $this->load->config('common/misTrackingKey');
        $this->ampKeys = $this->config->item('coursepage');
    }

    public function getCourseDetailPage($courseId,$ampViewFlag=false){
        $ampViewFlag = false; //disabling amp page
    	if(empty($courseId) || (integer)$courseId <= 0){
            show_404();
            exit(0);
        }
        $this->benchmark->mark('loading_dependencies_start');
        $this->_init();
        $this->benchmark->mark('loading_dependencies_end');
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;    
        $courseObj = $this->courseRepo->find($courseId,'full');
        // _p($courseObj); die;
        if(ENVIRONMENT != 'development'){
        	   $this->courseDetailLib->checkForCommonRedirections($courseObj, $courseId,$ampViewFlag);
        }
        if(!empty($courseObj)){
            // prepare view data
            $displayData = $this->courseDetailLib->prepareCourseData($courseObj,$this->userStatus,array('ampViewFlag' => $ampViewFlag));

            //check for organic traffic
            $mmpData = $this->courseDetailLib->seoFormOrganicTraffic();
            if(!empty($mmpData)){
                $displayData['mmpData'] = $mmpData;
            }

            $displayData['validateuser']      = $this->userStatus;
            $displayData['userId']            = $userId;
            $displayData['courseObj']         = $courseObj;

            $displayData['courseId'] = $courseId;  
            $displayData['instituteId'] =  $courseObj->getInstituteId();   

            $navigationSection                = $this->config->item('navigationSection');
            $displayData['navigationSection'] = $navigationSection['default'];
            
            $this->benchmark->mark('media_data_start');
            $displayData['galleryWidget']     = modules::run('mobile_listing5/InstituteMobile/getGalleryWidget',$courseObj->getId(),'course',$courseObj->getPhotos(),$courseObj->getVideos(),$displayData['currentLocationObj'],$ampViewFlag);

            if($ampViewFlag)
            {
                $displayData['videoExists'] = $displayData['galleryWidget']['videoExists'];
                $displayData['photosExist'] = $displayData['galleryWidget']['photosExist'];
                $displayData['galleryWidget'] = $displayData['galleryWidget']['galleryWidget'];
            }
            $this->benchmark->mark('media_data_end');
            
            //pageName identifier is used for to add div elements in footerDailogcode  file for Gallery 
            $displayData['boomr_pageid'] = 'mobilesite_LDP';

            //Aggregate Review Data 
            $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
            $courseReviewData = $collegeReviewLib->getAggregateReviewsForListing($displayData['courseId'],'course');
            $displayData['courseWidgetData'] = $courseReviewData; 
            $displayData['aggregateReviewsData'] = $displayData['courseWidgetData'][$courseObj->getId()];


            $this->benchmark->mark('review_data_start');
            $displayData['reviewWidget'] = modules::run('mobile_listing5/InstituteMobile/getReviewWidget',$courseId,'course',array(),$displayData['instituteURL'], $ampViewFlag, array('getCount' => 1,'aggregateReviewsData' => $displayData['aggregateReviewsData']),$courseObj,false,$courseObj->isPaid());
            $this->benchmark->mark('review_data_end');


            $reviewParams['totalReviewCount'] =$displayData['reviewWidget']['count'];
            $reviewParams['aggregateRating'] = $courseReviewData[$displayData['courseId']]['aggregateRating']['averageRating'];

             if($courseObj->isPaid() && $reviewParams['aggregateRating']<3.5){
                $reviewParams = array();
            }

            $this->benchmark->mark('contact_data_start');
            $contactWidget     = modules::run('mobile_listing5/InstituteMobile/getLocationsContactWidget',$courseObj, $displayData['currentLocationObj'],'',$ampViewFlag, array('affiliatedUniversityName' => $displayData['affiliatedUniversityName'], 'instituteNameWithLocation'=>$displayData['instituteName'],'reviewParams' => $reviewParams));
            $this->benchmark->mark('contact_data_end');
            $displayData['contactWidget'] = $contactWidget['contact'];
            $displayData['schemaContact'] = $contactWidget['schema'];

            $this->benchmark->mark('ana_data_start');
            $displayData['anaWidget'] = modules::run('mobile_listing5/InstituteMobile/getAnAWidget',$courseId,'course', null, $ampViewFlag, array('getCount' => 1));
            $this->benchmark->mark('ana_data_end');

            
            $displayData['brochureDownloaded'] = $this->_checkIfBrochureDownloaded($courseId);
            
            if($userId > 0){
                  $displayData['GA_userLevel'] = 'Logged In';
            }else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }

             if($ampViewFlag)
            {
                $displayData['gaPageName'] = "AMP COURSE DETAIL PAGE";            
                $displayData['gaCommonName'] = '_AMP_COURSE_DETAIL_MOBILE';
                $displayData['ampCourseViewedTrackingPageKeyId'] = 1207;
                $displayData['naukriData'];
                $displayData['ifNaukriDataExists'] = 0;
                $naukriData =  Modules::run('listing/Naukri_Data_Integration_Controller/getDataForNaukriSalaryWidget',$displayData['instituteId'],$courseId, '', 5 ,5, 1, 'courseDetailPage', true);
                if($naukriData['salary_total_employee'] >30 || $naukriData['totalNaukriEmployees'] >30){
                    $displayData['ifNaukriDataExists'] = 1;
                    $displayData['naukriData'] =  $naukriData;
                }
                $displayData['specMappingAMP'] = $this->config->item('naukriSpecMappingAMP');
               
            }
            else
            {
                $displayData['GA_currentPage'] = "COURSE DETAIL MOBILE PAGE";                
            }
            
            $displayData['trackForPages'] = true;

            $this->intitutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
            //prepare meta description
            $seoData = $this->courseDetailLib->getSeoData($courseObj,$displayData['instituteObj'], $displayData['anaWidget']['count'], $displayData['reviewWidget']['count']);
            $displayData['seoTitle'] = $seoData['title'];
            $displayData['metaDescription'] = $seoData['description'];
            $displayData['canonicalURL']      = $seoData['canonicalURL'];

            $displayData['canonicalURL'] = $this->intitutedetaillib->getCanonnicalUrl($displayData['instituteObj']->getId(),$displayData['canonicalURL']);


            $search = array('/course/','/college/','/university/');
            $replace  = array('/course/amp/','/college/amp/','/university/amp/');
            $displayData['amphtmlUrl'] = str_replace($search,$replace, $displayData['canonicalURL']);
            //which cta function has to trigger after pageload based on get parameter


            $this->benchmark->mark('dfp_data_start');
            $dfpObj   = $this->load->library('common/DFPLib');
            $dpfParam = array('courseObj'=>$courseObj,'courseLocation'=>$displayData['currentLocationObj'],'parentPage'=>'DFP_CourseDetailPage','pageType'=>'homepage');
            $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
            $this->benchmark->mark('dfp_data_end');

            if($ampViewFlag)
            {
                $ampJsArray = array('carousel','form','lightbox');

                if(!empty($displayData['placements']))
                {
                    $placementData = $displayData['placements']->getSalary();
                }

                if(!empty($placementData['min']) || !empty($placementData['median']) || !empty($placementData['avg']) || !empty($placementData['max']) || ($displayData['naukriData']['salary_data_count'] >0 && $naukriData['salary_total_employee'] >30)){
                        array_push($ampJsArray, 'iframe');
                }
                if($displayData['videoExists'])
                {
                    array_push($ampJsArray, 'youtube');
                }
                if($displayData['photosExist'])
                {
                    array_push($ampJsArray, 'image-lightbox');
                }

                $displayData['ampJsArray'] = $ampJsArray;
                $displayData['beaconData'] = array('productId'=>'0010004','pageEntityId'=>$displayData['courseId'],'pageIdentifier'=>'course');

                //chp interlinking
                $this->benchmark->mark('chp_data_start');
                $chpLibObj = $this->load->library('chp/ChpClient');
                $result = $chpLibObj->getCHPInterLinking('CLP',array('courseObj'=>$courseObj,'limit'=>20));
                $result = json_decode($result,true);
                $displayData['chpInterLinking']['links']   = $result['data']; //chp interlinking
                $displayData['chpInterLinking']['pageType']= 'AMP';
                $this->benchmark->mark('chp_data_end');

                $this->load->view('course/AMP/courseMainPage',$displayData);
            }
            else
            {
                $actionType = !empty($_GET['actionType'])?$this->input->get('actionType'):'';
                $fromwhere  = !empty($_GET['fromwhere'])?$this->input->get('fromwhere'):'';
                $pos        = !empty($_GET['pos'])?$this->input->get('pos'):'';

                if(!empty($actionType))
                    $displayData['actionType'] = $actionType;

                if(!empty($fromwhere))
                {
                    $displayData['fromwhere'] = $fromwhere;
                }
                if(!empty($pos))
                {
                    $displayData['pos'] = $pos;   
                }

                $queryParams = array();
                $queryParams = $_GET;

                $this->removeUselessQueryParams($queryParams,$displayData);
                $displayData['ampKeys'] = $this->ampKeys;

                $mappedBaseCourseIds = $courseObj->getBaseCourse();
                $coursePrimaryHierarchy =  $courseObj->getPrimaryHierarchy();
                $coursePrimaryStreamId = $coursePrimaryHierarchy['stream_id'];
                $collegePredBannerDetails = getAndShowCollegePredBanner($coursePrimaryStreamId, $mappedBaseCourseIds['entry']);
                if(!empty($collegePredBannerDetails)){
                    $displayData['predBannerStream'] = $collegePredBannerDetails['predStream'];
                    $displayData['isShowIcpBanner'] = true;
                }else {
                    $displayData['isShowIcpBanner'] = false;
                }

           //     $displayData['isShowIcpBanner'] = $this->isShowIcpBanner($mappedBaseCourseIds, $coursePrimaryStreamId);
                $this->load->view('mobile_listing5/course/courseDetailPage',$displayData);    
            }
            
        }
    }
    function removeUselessQueryParams($queryParams,&$displayData)
    {
            if(array_key_exists('actionType', $queryParams) || array_key_exists('fromwhere', $queryParams) || array_key_exists('pos', $queryParams))
            {
                if(array_key_exists('actionType', $queryParams))
                {
                    unset($queryParams['actionType']);
                }
                if(array_key_exists('fromwhere', $queryParams))
                {
                    unset($queryParams['fromwhere']);
                }
                if(array_key_exists('pos', $queryParams))
                {
                    unset($queryParams['pos']);
                }
                if(!empty($queryParams) && count($queryParams) > 0)
                {
                    $displayData['replaceStateUrl'] = $displayData['courseObj']->getURL().'?'.http_build_query($queryParams);
                }
                else{
                    $displayData['replaceStateUrl'] = $displayData['courseObj']->getURL();
                }    
            }
    }

    public function getImportantDatesData(){
        $courseId = $this->input->post('courseId');
        if(empty($courseId) || (integer)$courseId <= 0){
            exit(0);
        }
        $this->_init();
        // $courseId = $courseIdOrObj;
        $courseObj = $this->courseRepo->find($courseId, array('eligibility'));
        // _p($courseObj);
        $data = array();

        $data = $this->courseDetailLib->getImportantDates($courseObj);
        $data['showImportantViewMore'] = false;
        
        // if($this->isAJAXCall()){
            $response = array();
            $response['heading'] = 'Important Dates';
            $selectedExamName = $this->input->post('selectedExamName');
            $response['selectedExamName'] = (!empty($selectedExamName)) ? $selectedExamName : 'All';
            $response['content'] = $this->load->view('mobile_listing5/course/widgets/courseImportantDatesLayer',$data,true);
            echo json_encode($response);
            exit;
        // }
        // else{
        //     return $data;
        // }
    }

    public function getFeesBasedOnSelection(){
        $this->_init();
        $courseId         = $this->input->post('courseId');
        $selectedCategory = $this->input->post('selectedCategory');
        $result           = $this->courseDetailLib->getFeesBasedOnSelection($courseId, $selectedCategory);

        echo $result;
        exit;
    }

    // Not used
    function getInstituteScholarship($instId='',$AMPFlag=false) {
        $instituteId = (isset($_POST['instituteId']) && !empty($_POST['instituteId']))?$this->input->post('instituteId'):$instId;
        // $instituteId = 47223;
        $arr['html'] = 'No data found';
        if($instituteId && $instituteId > 0) {
            $this->_init();
            $this->load->helper(array('shikshautility'));
            $InstituteObj = $this->instituteRepo->find($instituteId, array('scholarship'));
            $scholarshipData = $this->courseDetailLib->prepareCourseScholarship($InstituteObj);
            $displayData = array();
            $displayData['scholarships'] = $scholarshipData;
            if($AMPFlag){
                echo $this->load->view('mobile_listing5/course/AMP/Widgets/courseScholarshipLayer',$displayData, true);
                return;
            }else{
                $arr['html'] = $this->load->view('mobile_listing5/course/widgets/courseScholarshipLayer',$displayData, true);
                $arr['heading'] = 'Scholarships';
                
            }           
        }   
        echo json_encode($arr);     
        // echo 'Invalid Institute ID';
    }

    public function getEligibilityData($courseId,$category='general',$returnHTML = false){
        $this->_init();
        $this->load->helper(array('shikshautility'));
        $selectedCategory = $this->input->post('selectedCategory');
        
        if($selectedCategory){
            $courseId   = $this->input->post('courseId');
            $returnHTML = true;
            $category   = $selectedCategory; 
        }

        $data = $this->courseDetailLib->getCourseEligibilityDataWithCache($courseId,$category);
        if($returnHTML){
            $html = $this->load->view('course/widgets/courseEligibilityTableWidget',array('eligibility'=>$data),true);
            echo json_encode($html);
            exit;
        }else{
            return $data;
        }
    }

    function _checkIfBrochureDownloaded($courseId){
        $brochuresMailed = $_COOKIE['applied_courses'];
        if(empty($brochuresMailed)){
            $brochuresMailed = array();
        }else{
            $brochuresMailed = json_decode(base64_decode($brochuresMailed));
        }
        if(in_array($courseId, $brochuresMailed)){
            return true;
        }
        return false;
    }
    /**
    getting course detail page data for AMP Page
    */
    function ampCourseDetailPage($courseId)
    {   
        Modules::run('muser5/UserActivityAMP/validateBrowser', 'coursepage', $courseId);
        $this->getCourseDetailPage($courseId,true);
    }

    //Desc - this is used to create viewed reponse from AMP page
    function ampCreateViewedResponse($courseid, $tracking_keyid, $responseType = 'course'){
        if(empty($courseid) || empty($tracking_keyid) || !is_numeric($courseid) || !is_numeric($tracking_keyid)){
            return;
        }
        $_POST['listing_id']       = $courseid;     
        $_POST['tracking_keyid']   = $tracking_keyid;
        if($responseType == 'course'){
            $_POST['action_type']      = 'mobile_viewedListing';
        }else{
            $_POST['action_type']      = 'exam_viewed_response';
        }
        
        $_POST['listing_type']     = $responseType;
        $_POST['isViewedResponse'] = 'yes';
        modules::run('response/Response/createResponse');
    }
    function checkUserIsValidForCourse($courseId)
    {
        $courseId = !empty($_GET['courseId'])?$this->input->get('courseId'):$courseId;

        if(empty($courseId))
        {
            return false;  
        }

        $validuser = modules::run('registration/RegistrationForms/isValidUser',$courseId,null,true);

        return $validuser;
    }

    function getAlumniStatsChartAmp($chartData){
        $displayData['chartData'] = ($chartData) ? $chartData :0 ;
        $this->load->view('course/AMP/Widgets/courseAlumniEmploymentWidgetAMP', $displayData);
    
    }

    function getNaukriPlacementChartsAMP($placementData){
        $displayData['placementData'] = json_decode($placementData, true);
        $this->load->view('course/AMP/Widgets/ampNaukriChartFrame', $displayData);
    
    }
    function checkCourseAddedToCompare($courseId)
    {
        $courseId = !empty($_GET['courseId'])?$this->input->get('courseId'):$courseId;

        if(empty($courseId))
        {
            return false;  
        }
        $cmpLib = $this->load->library('comparePage/comparePageLib');
        $courseIds = $cmpLib->getComparedData('mobile');
        if(array_key_exists($courseId, $courseIds))
        {
            return true;
        }
        return false;
    }
    function showContactInfoToUser($listingId,$listingType = 'course')
    {   
        if(empty($listingId))        
        {
            return false;
        }
        $listingType = in_array($listingType, array('course','institute')) ? $listingType : 'course';
        if($listingType == 'course')
        {
            $cookieName = 'courContResp';
        }
        else
        {
            $cookieName = 'instContResp';   
        }
        $cookieArray = $_COOKIE[$cookieName];
        $cookieArray = explode(',', $cookieArray);
        return in_array($listingId, $cookieArray) ? true : false;
    }
    function isShowIcpBanner($baseCourseIds)
    {
        if($baseCourseIds['entry'] == ICP_BANNER_COURSE)
        {
            return true;
        }
        return false;
    }
  
}
