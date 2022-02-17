<?php

class CourseDetailPage extends MX_Controller {

    private function _init(){
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }

        $this->coursedetailmodel = $this->load->model('nationalCourse/coursedetailmodel');
 
        $this->load->helper('listingCommon/listingcommon');
	    $this->load->helper(array('string','image'));

        $this->load->config('CollegeReviewForm/collegeReviewConfig');

        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();   
        $this->load->config('nationalCourse/courseConfig'); 
        $this->load->config('CollegeReviewForm/collegeReviewConfig'); 

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();  

        $this->load->library('nationalCourse/CourseDetailLib');
        $this->courseDetailLib = new CourseDetailLib; 
    }


    /**
     *
     * @author Aman Varshney <aman.varshney@shiksha.com>
     * @date   2016-11-02
     * iim id 164638
     * @param  integer $courseId unique id of course listing page
     */
    public function getCourseDetailPage($courseId){

        if(empty($courseId) || !is_numeric($courseId)){
            show_404();
            exit(0);
        }
        $this->startTime = microtime(true);
        $this->benchmark->mark('loading_dependencies_start');
        $this->_init();
        $this->benchmark->mark('loading_dependencies_end');
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;    
        //ini_set("memory_limit", "3000M");


        //////////////////////
        //get course object //
        //////////////////////

        $courseObj = $this->courseRepo->find($courseId,'full');

        ////////////////////////////////////////////////////////////////////////////////////////////////uteuteinst////////////////////////////////////////////////////
        //If course id does'nt exist, check whether the status of course is deleted,if yes then 301 redirect to migrated course page Or show 404 and check if url is different from original url, 301 redirect to main url and Redirect to disabled url //
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if(ENVIRONMENT != 'development') {
            $this->courseDetailLib->checkForCommonRedirections($courseObj, $courseId);
        }
        
        if(!empty($courseObj)){
            // prepare view data
            $displayData = $this->courseDetailLib->prepareCourseData($courseObj,$this->userStatus);

            

            $displayData['validateuser']      = $this->userStatus;
            $displayData['userId']            = $userId;
            $displayData['courseObj']         = $courseObj;
            $navigationSection                = $this->config->item('navigationSection');
            $displayData['navigationSection'] = $navigationSection['default'];
            $displayData['suggestorPageName'] = "all_tags";

            $this->benchmark->mark('dfp_data_start');
            $dfpObj   = $this->load->library('common/DFPLib');
            $dpfParam = array('courseObj'=>$courseObj,'courseLocation'=>$displayData['currentLocationObj'],'parentPage'=>'DFP_CourseDetailPage','pageType'=>'homepage');
            $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
            $this->benchmark->mark('dfp_data_end');

            $this->benchmark->mark('media_data_start');
            $displayData['galleryWidget']     = modules::run('nationalInstitute/InstituteDetailPage/getGalleryWidget',$courseObj->getId(),'course',$courseObj->getPhotos(),$courseObj->getVideos(),$displayData['currentLocationObj']);
            $this->benchmark->mark('media_data_end');

            $this->benchmark->mark('review_data_start');
            $displayData['reviewWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getReviewWidget',$courseId,'course',array(),$displayData['instituteURL'], array('getCount' => 1,'aggregateReviewsData' => $displayData['aggregateReviewsData']),$courseObj,false,$courseObj->isPaid());
            $this->benchmark->mark('review_data_end');

            //Aggregate Review Data 
            $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
            $courseReviewData = $collegeReviewLib->getAggregateReviewsForListing($displayData['courseId'],'course');
            $displayData['courseWidgetData'] = $courseReviewData; 
            $displayData['aggregateReviewsData'] = $displayData['courseWidgetData'][$courseObj->getId()];

            //_p($displayData['currentLocationObj']);die;
            $this->benchmark->mark('review_data_start');
            $displayData['reviewWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getReviewWidget',$courseId,'course',array(),$displayData['instituteURL'], array('getCount' => 1,'aggregateReviewsData' => $displayData['aggregateReviewsData'],'showRatingFilterUrl' => true),$courseObj,false,$courseObj->isPaid());
            $this->benchmark->mark('review_data_end');

            $reviewParams['totalReviewCount'] =$displayData['reviewWidget']['count'];
            $reviewParams['aggregateRating'] = $courseReviewData[$displayData['courseId']]['aggregateRating']['averageRating'];
            if($courseObj->isPaid() && $reviewParams['aggregateRating']<3.5){
                $reviewParams = array();
            }

            $this->benchmark->mark('contact_data_start');
            $displayData['contactWidget']     = modules::run('nationalInstitute/InstituteDetailPage/getLocationsContactWidget',$courseObj, $displayData['currentLocationObj'], false, null, array('affiliatedUniversityName' => $displayData['affiliatedUniversityName'], 'instituteNameWithLocation'=>$displayData['instituteName'],'reviewParams' => $reviewParams));
            $this->benchmark->mark('contact_data_end');

            $displayData['schemaContact'] = $displayData['contactWidget']['schema'];
            $displayData['contactWidget'] = $displayData['contactWidget']['contact'];

            /**
             * Hardcoding with the following values: listing id as 467.
             */
            $this->benchmark->mark('ana_data_start');
            $displayData['anaWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getAnAWidget',$courseId,'course', null, array('getCount' => 1));
            
            $this->benchmark->mark('ana_data_end');

            //GA user tracking details
            if($userId > 0){
                  $displayData['GA_userLevel'] = 'Logged In';
            }else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }
            $displayData['GA_currentPage'] = "COURSE DETAIL PAGE";
            $displayData['mmpData']       = $this->getMMPDetails($courseObj);
            $displayData['trackForPages'] = true;

            $this->benchmark->mark('breadcrumb_start');
            $this->_prepareBreadcrumb($displayData);
            $this->benchmark->mark('breadcrumb_end');
            
            $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
            
            //prepare meta description
            $this->intitutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
            
            $seoData = $this->courseDetailLib->getSeoData($courseObj, $displayData['instituteObj'], $displayData['anaWidget']['count'], $displayData['reviewWidget']['count']);

            $displayData['seoTitle'] = $seoData['title'];
            $displayData['metaDescription'] = $seoData['description'];
            $displayData['canonicalURL']  = $seoData['canonicalURL'];
            $displayData['canonicalURL'] = $this->intitutedetaillib->getCanonnicalUrl($displayData['instituteObj']->getId(),$displayData['canonicalURL']);
            $search = array('/course/','/college/','/university/');
            $replace  = array('/course/amp/','/college/amp/','/university/amp/');
            if(0) {
                $displayData['amphtmlUrl'] = str_replace($search,$replace, $displayData['canonicalURL']);
            }

            
            //SRM MESSEGE
            global $COURSE_MESSAGE_KEY_MAPPING,$MESSAGE_MAPPING;
            $displayData['SRM_DATA']=  $MESSAGE_MAPPING[$COURSE_MESSAGE_KEY_MAPPING[$courseId]];

            if(!empty($displayData['SRM_DATA'])){
                $displayData['showToastMsg'] = true;
            }

            //chp interlinking
            $this->benchmark->mark('chp_data_start');
            $chpLibObj = $this->load->library('chp/ChpClient');
            $result = $chpLibObj->getCHPInterLinking('CLP',array('courseObj'=>$courseObj,'limit'=>20));
            $result = json_decode($result,true);
            $displayData['chpInterLinking']['links']  = $result['data'];
            $displayData['chpInterLinking']['gaPage'] = 'CLP';
            $this->benchmark->mark('chp_data_end');
        
            $this->load->view('CoursePage/CourseMainPage',$displayData);
        }
    }


    public function getMMPDetails($courseObj){
        
        $baseCourse  = $courseObj->getBaseCourse();
        global $mbaBaseCourse;
        global $managementStreamMR;
        $mmpData = array();
       //$baseCourse['entry'] = $mbaBaseCourse;
        if($baseCourse['entry'] == $mbaBaseCourse){
           $this->load->library('customizedmmp/customizemmp_lib');
           $customizemmp_lib    = new customizemmp_lib();
           $mmpType             = 'newmmpcourse';
           $isLoggedIn          = true;
           if($this->userStatus == "false"){
               $isLoggedIn      = false;
           }
           //$mmpData             = $customizemmp_lib->seoMMPLayerFromOrganicTraffic($mmpType, $isLoggedIn);	
           $res['baseCourseId'] = $mbaBaseCourse;
           $res['stream']       = $managementStreamMR;
           $this->load->library('common/RecatCommonLib');
           $regFormPrefillValue = $this->recatcommonlib->getRegistrationFormPopulationDataByParams($res);
           $mmpData['regFormPrefillValue'] = $regFormPrefillValue;
        }
        
        return $mmpData;
    }
    
    public function getFeesBasedOnSelection(){
        $this->_init();
        $courseId         = $this->input->post('courseId');
        $selectedCategory = $this->input->post('selectedCategory');
        $result           = $this->courseDetailLib->getFeesBasedOnSelection($courseId, $selectedCategory);

        echo $result;
        exit;
    }

    public function getImportantDatesData($courseIdOrObj,&$data){
        $this->_init();
        $courseId = $courseIdOrObj;
        $courseObj = $this->courseRepo->find($courseId,array('eligibility'));
        $data = array();

        $this->courseDetailLib->getImportantDates($courseId,$data);
        
        if($this->isAJAXCall()){
            $html = $this->load->view('nationalCourse/CoursePage/CourseImportantDates',$data,true);
            echo json_encode($html);
            exit;
        }
        else{
            return $data;
        }
    }

    public function isAJAXCall(){
        return $this->input->is_ajax_request();
    }

    function getInstituteScholarship() {
        $instituteId = $this->input->post('instituteId');
        // $instituteId = 47223;
        $arr['html'] = 'No data found';
        if($instituteId && $instituteId > 0) {
            $this->_init();
            $this->load->helper(array('shikshautility'));
            $InstituteObj = $this->instituteRepo->find($instituteId, array('scholarship'));
            $scholarshipData = $this->courseDetailLib->prepareCourseScholarship($InstituteObj);
            $displayData = array();
            $displayData['scholarships'] = $scholarshipData;
            $arr['html'] = $this->load->view('nationalCourse/CoursePage/ScholarshipWidget',$displayData, true);
            $arr['heading'] = 'Scholarships';
            // _p($InstituteObj->getScholarships()); die;
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
            $html = $this->load->view('CoursePage/CourseEligibilityTableWidget',array('eligibility'=>$data),true);
            echo json_encode($html);
            exit;
        }else{
            return $data;
        }
    }

    private function _prepareBreadcrumb(& $displayData){
        $breadcrumbOptions = array(
                                'generatorType'  => 'CoursePage',
                                'options' => array(
                                                    'displayData' => & $displayData,
                                                    'pageType'    => 'coursePage'
                                                    )
                                );
        $BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
        $displayData['breadcrumbHTML'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();
    }
}
