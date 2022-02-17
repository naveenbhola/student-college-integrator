<?php 

class InstituteDetailPage extends MX_Controller {

    private function _init(){
        /*testing */
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
		$this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
    $this->load->config('CollegeReviewForm/collegeReviewConfig');
        $this->load->helper('listingCommon/listingcommon');
        $this->load->config('nationalInstitute/instituteStaticAttributeConfig');
        $this->load->config('nationalInstitute/instituteSectionConfig');
	    $this->load->helper(array('string','image','listingcommon','shikshautility'));

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();

        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();
    }

     /**
     * Method to show institute detail page on desktop
     * @author Yamini Bisht
     * @date   2016-10-10
     * @param  [type]     $listingId   [institute/university id]
     * @param  [type]     $listingType [institute/university]
     * @return [type]                  [displays institute page]
     */

    public function getInstituteDetailPage($listingId,$listingType){
    
        $this->benchmark->mark('loading_dependencies_start');
        $this->_init();
        $this->load->config('nationalCategoryList/nationalConfig');
        $this->load->config('nationalInstitute/instituteStreams');
        $this->load->config('CollegeReviewForm/collegeReviewConfig');
        $this->benchmark->mark('loading_dependencies_end');

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData['validateuser'] = $this->userStatus;

        if(empty($listingId) || empty($listingType)){
            show_404();
            exit(0);
        }

        $this->instituteDetailLib->getCanonnicalUrl($listingId);

        $this->benchmark->mark('institute_find_and_redirects_start');
        $instituteObj = $this->instituteRepo->find($listingId,'full');
       
        if($instituteObj->getType())
            $listingType  = $instituteObj->getType();

        // check if institute is deleted and if it needs to be redirected
        $this->instituteDetailLib->_checkForCommonRedirections($instituteObj, $listingId, $listingType);
        $this->benchmark->mark('institute_find_and_redirects_end');

        if(!empty($instituteObj)){

            // get courses of institute/university
            $this->benchmark->mark('get_institute_courses_mapping_start');
            $displayData['courseIdsMapping'] = $this->instituteDetailLib->getAllCoursesForInstitutes($listingId);
            $this->benchmark->mark('get_institute_courses_mapping_end');

            // prepare view data
            $this->benchmark->mark('prepare_institute_data_start');
            $displayData = $this->prepareInstituteData($instituteObj,$displayData);

            $displayData['instituteIsPaid'] = $displayData['coursesWidgetData']['instituteHasPaidCourse'];  
            $this->benchmark->mark('prepare_institute_data_end');

            $this->benchmark->mark('get_institute_sponsored_widget_start');
            $displayData['sponsoredWidgetData'] = $this->instituteDetailLib->getSponsoredWidgetData($listingId, $displayData['coursesWidgetData']['instituteHasPaidCourse']);
            $this->benchmark->mark('get_institute_sponsored_widget_end');
            //check for organic traffic
            $this->benchmark->mark('mmp_form_start');
            $this->load->library('customizedmmp/customizemmp_lib');
            $this->customizedMMPLib = new customizemmp_lib();
            $mmpType = 'newmmpinstitute';
            $isLoggedIn = ($userId>0)?true:false;
            $mmpData = $this->customizedMMPLib->seoMMPLayerFromOrganicTraffic($mmpType, $isLoggedIn);
            $this->benchmark->mark('mmp_form_end');

            $displayData['facilities']    = $this->arrangeFacilitiesInOrder($instituteObj->getFacilities());
            $displayData['canonicalURL']  = $displayData['seo_url'];
            $displayData['listing_id']    = $listingId;
            $displayData['listing_type']  = $listingType;
            $displayData['instituteObj']  = $instituteObj;
            $displayData['listingString'] = getInstituteNameWithCityLocality($displayData['instituteName'],$listingType,$displayData['mainCity'],$displayData['mainLocality']);

            //article widget
            $this->benchmark->mark('article_widget_start');
            $displayData['articleWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getArticleWidget',$listingId,$listingType,$displayData['courseIdsMapping']);
            $this->benchmark->mark('article_widget_end');


            $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
            $aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($listingId, $listingType);
            $displayData['aggregateReviewsData'] = $aggregateReviews[$listingId];

            //review widget

            $this->benchmark->mark('review_widget_start');

            $displayData['reviewWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getReviewWidget',$listingId,$listingType,$displayData['courseIdsMapping'], '', array('getCount' => 1,'aggregateReviewsData' => $displayData['aggregateReviewsData'], 'showRatingFilterUrl' => true),$instituteObj,false,$displayData['instituteIsPaid']);

            $this->benchmark->mark('review_widget_end');

            $reviewParams = array();
            $reviewParams['totalReviewCount'] = $displayData['reviewWidget']['count'];
            $reviewParams['aggregateRating'] = $displayData['aggregateReviewsData']['aggregateRating']['averageRating'];

            if($displayData['instiuteIsPaid'] && $reviewParams['aggregateRating']<3.5){
                $reviewParams = array();
            }

            $this->benchmark->mark('location_contact_details_widget_start');
            $displayData['contactWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getLocationsContactWidget',$displayData['instituteObj'], $displayData['currentLocationObj'],false,$displayData['coursesWidgetData']['locationsMappedToCourse'],$reviewParams);
            $this->benchmark->mark('location_contact_details_widget_end');

            $displayData['schemaContact'] = $displayData['contactWidget']['schema'];
            $displayData['contactWidget'] = $displayData['contactWidget']['contact'];

            //gallery Widget
            $this->benchmark->mark('gallery_widget_start');
            $displayData['galleryWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getGalleryWidget',$listingId,$listingType,$instituteObj->getPhotos(),$instituteObj->getVideos(),$displayData['currentLocationObj']);
            $this->benchmark->mark('gallery_widget_end');


            //get Admission and Exams Info
            $displayData['admissionInfo'] = $instituteObj->getAdmissionDetails();
            if(!empty($displayData['admissionInfo']))
            {
                $this->load->helper('html');
                $displayData['admissionInfo'] = getTextFromHtml($displayData['admissionInfo'],2000,array('table'));

            }

            $displayData['examList'] = $this->getExamsMappedToUniversity($listingId);
            $displayData['viewAdmissionLink'] = $instituteObj->isAdmissionDetailsAvailable();
            $displayData['admissionPageUrl'] = $displayData['seo_url'].'/admission';

            //AnA widget
            $this->benchmark->mark('ana_widget_start');
            $displayData['anaWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getAnAWidget',$listingId,$listingType,$displayData['courseIdsMapping'], array('getCount' => 1));
            $this->benchmark->mark('ana_widget_end');
        
            //_P($displayData['anaWidget']);die;
            $displayData['suggestorPageName'] = "all_tags";
            $displayData['viewCountListingType'] = $listingType == 'university' ? 'university_national' : 'institute';
            $displayData['currentCityId']        = !empty($_GET['city']) ? $this->input->get("city") : 0;
            $displayData['currentLocalityId']    = !empty($_GET['locality']) ? $this->input->get("locality") : 0;

            //get all courses
            //$courseIdArray = $this->instituteRepo->getCoursesListForInstitutes(array($listingId));
            $this->benchmark->mark('prepare_institute_courses_data_start');
            $courseIdArray[$listingId] = $displayData['courseIdsMapping']['courseIds'];
            if(!empty($courseIdArray)){
                if(empty($displayData['coursesWidgetData']['allCourses']))
                    $coursesInfo = $this->courseRepo->findMultiple($courseIdArray[$listingId]);
                else
                    $coursesInfo = $displayData['coursesWidgetData']['allCourses'];
                $instituteCourses = array();
                $clientCoursesStreams = array();
                foreach ($coursesInfo as $courseKey => $courseValue){
                    $courseId = $courseValue->getId();
                    $courseName = $courseValue->getName();
                    $instituteName = $courseValue->getOfferedByShortName();
                    $instituteName = $instituteName ? $instituteName : $instituteObj->getShortName();
                    $instituteName = $instituteName ? $instituteName : $instituteObj->getName();
                    if($listingType == 'university'){
                        $courseName .= ", ".$instituteName;
                    }
                    //$baseCourse = $courseValue->getCourseTypeInformation()['entry_course']->getBaseCourse();
                    $courseHierarchies = $courseValue->getCourseTypeInformation();

                    if(!empty($courseHierarchies['entry_course'])){
                        $courseHierarchies = $courseHierarchies['entry_course']->getHierarchies();
                        foreach ($courseHierarchies as $key => $value) {
                            $clientCoursesStreams[] = $value['stream_id'];
                        }

                    }

                    $instituteCourses[] = array('course_id' => $courseId,'course_name' => htmlentities($courseName));
                }

                if(!empty($mmpData)){
                    $displayData['mmpData'] = $mmpData;
                    $clientCoursesStreams = array_unique($clientCoursesStreams);
                    if(!empty($clientCoursesStreams)){
                        foreach($clientCoursesStreams as $streams){
                            $streamIdsArray[$streams] = array();
                        }
                        $displayData['streamIds'] = $streamIdsArray;
                    }

                }
                
                //sort course alphabetically
                if(!function_exists('course_sort')){
                 function course_sort($a,$b)
                 {
                    if ($a['course_name']==$b['course_name']) return 0;
                      return (strtolower($a['course_name'])<strtolower($b['course_name']))?-1:1;
                     }
                 }

                 usort($instituteCourses,"course_sort");

                $displayData['instituteCourses'] = $instituteCourses;
            }
            $this->benchmark->mark('prepare_institute_courses_data_end');

            //GA user tracking details
            if($userId > 0){
                  $displayData['GA_userLevel'] = 'Logged In';
            }else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }

            $displayData['GA_currentPage'] = ($listingType == 'university') ? 'UNIVERSITY DETAIL PAGE' : 'INSTITUTE DETAIL PAGE';


            $displayData['gtmParams'] = array(
                        "pageType"    => ($listingType == 'university') ? 'universityDetailPage' : 'instituteDetailPage',
                         "instituteId" => $listingId,
                         "cityId" => $displayData['currentLocationObj']->getCityId(),
                        "stateId" => $displayData['currentLocationObj']->getStateId(),
                        "countryId"     => 2
                );
            $mappedStream = $this->config->item($listingId,'streamMapping');
            if(!empty($mappedStream)){
                $displayData['gtmParams']["streamId"] = $mappedStream;
            }

            if($userId > 0)
            {
                $userWorkExp = $this->userStatus[0]['experience'];
                if($userWorkExp >= 0)
                    $displayData['gtmParams']['workExperience'] = $userWorkExp;
            }
            if(!empty($displayData['examList']))
            {
                $examScanArray = array();
                foreach ($displayData['examList'] as $key => $value) {
                    $examScanArray[] = $value['name'];
                }
                $displayData['gtmParams']['exams'] = $examScanArray;
            }

            if(count($displayData['coursesWidgetData']['baseCourseIds']) == 1)
            {
                $displayData['gtmParams']['baseCourseId'] = $displayData['coursesWidgetData']['baseCourseIds'][0];
            }
            if(count($displayData['coursesWidgetData']['baseStreamIds']) == 1)
            {
                $displayData['gtmParams']['stream'] = $displayData['coursesWidgetData']['baseStreamIds'][0];
            }

            $displayData['trackForPages'] = true;

            $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');

            //prepare meta description
            $seoData = $this->instituteDetailLib->getSeoData($instituteObj, $displayData['anaWidget']['count'], $displayData['reviewWidget']['count'], $displayData['coursesWidgetData']['totalCourseCount'],$displayData['currentLocationObj']);
            $displayData['metaDescription'] = $seoData['description'];
            $displayData['seoTitle'] = $seoData['title'];
            $displayData['metaKeywords'] = $seoData['keywords'];

            $this->benchmark->mark('dfp_data_start');
            $dfpObj   = $this->load->library('common/DFPLib');
           
            foreach ($displayData['coursesWidgetData']['streamObjects'] as $key => $streamObj) {
                $dfpSteamIds[] = $streamObj->getId();
            }
            foreach ($displayData['coursesWidgetData']['baseCourseObjects'] as $key => $baseCourseObj) {
                $dfpBaseCourse[] = $baseCourseObj->getId();
            }

            $dpfParam = array('instituteObj'=>$instituteObj,'instituteLocationObj'=>$displayData['currentLocationObj'],'parentPage'=>'DFP_InstituteDetailPage','pageType'=>'homepage','stream_id'=>$dfpSteamIds,'baseCourse'=>$dfpBaseCourse);
            $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
            $this->benchmark->mark('dfp_data_end');

            //chp interlinking
            $this->benchmark->mark('chp_data_start');
            $chpLibObj = $this->load->library('chp/ChpClient');
            $result = $chpLibObj->getCHPInterLinking('UILP',array('sips'=>$dfpSteamIds,'bips'=>$dfpBaseCourse));
            $result = json_decode($result,true);
            $displayData['chpInterLinking']['links']  = $result['data'];
            $displayData['chpInterLinking']['gaPage'] = 'IULP';
            $this->benchmark->mark('chp_data_end');

            //loading all coursespagelib
            $this->allCoursesPageLib = $this->load->library('nationalCategoryList/AllCoursesPageLib');

            $displayData['canonicalURL'] = $this->instituteDetailLib->getCanonnicalUrl($displayData['listing_id'],$displayData['canonicalURL']);

            global $removeInsAmpPages;
            if(0 && !in_array($listingId, $removeInsAmpPages)){
                $search = array('/course/','/college/','/university/');
                $replace  = array('/course/amp/','/college/amp/','/university/amp/');
                $displayData['amphtmlUrl'] = str_replace($search,$replace, $displayData['canonicalURL']);
            }
            
            global $MESSAGE_MAPPING,$INSTITUTE_MESSAGE_KEY_MAPPING;
            $displayData['SRM_DATA'] = $MESSAGE_MAPPING[$INSTITUTE_MESSAGE_KEY_MAPPING[$displayData['listing_id']]];

            if(!empty($displayData['SRM_DATA'])){
                $displayData['showToastMsg'] = true;
            }
            
            $displayData['sharethumbnailUrl'] = addingDomainNameToUrl(array('url' => $displayData['topCardData']['headerImage']['url'],'domainName' => IMGURL_SECURE));
            
            $this->benchmark->mark('Time_to_load_view_start');
            $this->load->view('InstitutePage/InstituteMainPage',$displayData);
            $this->benchmark->mark('Time_to_load_view_end');
        }
    }


    private function prepareInstituteData(&$instituteObj,$data){

        $data['instituteName']   = $instituteObj->getName();
        $data['seo_url']         = $instituteObj->getURL();
        $data['allCoursePageUrl']= $data['seo_url']."/courses";
        $data['disabled_url']    = $instituteObj->getDisableUrl();
        $mainLocation            = $instituteObj->getMainLocation();

        if(!empty($mainLocation)){
            $data['mainCity']     = $mainLocation->getCityName();
            $data['mainLocality'] = $mainLocation->getLocalityName();
        }

        $currentLocationObj         = $this->instituteDetailLib->getInstituteCurrentLocation($instituteObj);
        $data['currentLocationObj'] = $currentLocationObj;
        $data['instituteLocations'] = $instituteObj->getLocations();
        $data['isMultilocation']    = count($data['instituteLocations']) > 1 ? true : false;

        if($instituteObj->getType() == 'university'){
            // get universities college data
            $data['collegesWidgetData']   = $this->instituteDetailLib->getUniversityPageCollegesWidgetData($instituteObj->getId(), 6, $data['courseIdsMapping']);
            $data['collegesWidgetData']['aggregateReviewsData']= array();
            $data['collegesWidgetData']['reviewCount']= array();

            $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
            $ids = array_keys($data['collegesWidgetData']['topInstituteData']);
            $listingType= 'institute';

            // get aggregate reviews of top institutes in universities
            $data['collegesWidgetData']['aggregateReviewsData']=$collegeReviewLib->getAggregateReviewsForListing($ids,$listingType);

            // get total reviewCount
            $preFetchedCourseIds=$this->instituteDetailLib->getAllCoursesForMultipleInstitutes($ids);
            $this->load->library('ContentRecommendation/ReviewRecommendationLib');
            $data['collegesWidgetData']['reviewCount']=$this->reviewrecommendationlib->getInstituteReviewCounts($ids,$preFetchedCourseIds);
            // _p($ids);
            //  die();
        }
        $data['coursesWidgetData']   = $this->instituteDetailLib->getInstitutePageCourseWidgetData($instituteObj, $currentLocationObj, $data['isMultilocation'],6,5,'desktopp');


        $data['instiuteIsPaid'] = $data['coursesWidgetData']['instituteHasPaidCourse'];
        // in case of zero(0) courses on this institute show 404
        if(count($data['coursesWidgetData']['allCourses']) < 1){
            show_404();exit(0);
        }

        $data['mbaCourseExistsFlag'] = empty($data['coursesWidgetData']['mbaCourseIds']) ? false : true;

        if($data['isMultilocation']){
            $data['seeAllBranchesData'] = $this->instituteDetailLib->formatLocationForMultilocationLayer($data['instituteLocations'], $data['coursesWidgetData']['locationsMappedToCourse']);
        }


        $data['topCardData']          = $this->instituteDetailLib->getInstitutePageTopCardData($instituteObj, $data['coursesWidgetData']['courseViewCount'], $data['coursesWidgetData']['allCourses'], false, $data['currentLocationObj']);
        $baseCourseNames = array();
        foreach ($data['coursesWidgetData']['baseCourseObjects'] as $key => $value) {
             $baseCourseNames[] = $value -> getName();
        }
        $data['cutOffData'] = $this->instituteDetailLib->getCollegeCutOffData($instituteObj,$data['topCardData']['instituteParentData'], $data['topCardData']['affiliationData'], $baseCourseNames);
        $data['instituteToolTipData'] = $this->config->item('instituteToolTipData');
        $data['highlights']           = $instituteObj->getUSP();
        $data['events']               = $instituteObj->getEvents();
        $data['scholarships']         = $instituteObj->getScholarships();
        $data['mediaPhotos']          = $instituteObj->getPhotos();


        $instituteObj->addCourses($data['coursesWidgetData']['allCourses']);
        
        $courseIds = $data['coursesWidgetData']['coursesOfferedList'];  // get all course ids to pass into getAggregateReviewsForListing as listing id
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $courseReviewData = $collegeReviewLib->getAggregateReviewsForListing($courseIds,'course');
        
        foreach($courseReviewData as $courseId => $review){
                $data['courseWidgetData']['courseReviewRatingData'][$courseId] = $review; // add course review rating data to course widget data for each course id
        }

        $flagshipCourseId = $data['coursesWidgetData']['flaghshipCourse'];

        if( $flagshipCourseId){
            $flagshipCourse = $this->courseRepo->find($flagshipCourseId, array('location'));    
            $data['course']         = $flagshipCourse;

            $this->_populateCurrentLocation($data,$instituteObj,$flagshipCourse);

            // send institute digest mail if user is logged in
            $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0 && !in_array($this->userStatus[0]['usergroup'],array("enterprise","cms","experts","sums","listingAdmin"))){
                $this->instituteDetailLib->sendInstituteDigestMailForUser($userId, $instituteObj->getId());
            }

            // Check and set the values is displayData array necessary for making the response eg. Institute_viewed
            $this->_checkAndSetDataForAutoResponseForInstitutePage($flagshipCourse, $data, $instituteObj->getType());
        }

        $listingType = $instituteObj->getType();

        $data['beaconTrackData'] = array(
                                        'pageIdentifier' => "UILP",
                                        'pageEntityId' => $instituteObj->getId(),
                                        'extraData' => array("childPageIdentifier"=>$listingType.'ListingPage','url'=>get_full_url())
                                    );
        if($data['currentLocation']){
            $data['beaconTrackData']['extraData']['cityId'] = $data['currentLocation']->getCityId();
            $data['beaconTrackData']['extraData']['stateId'] = $data['currentLocation']->getStateId();
            $data['beaconTrackData']['extraData']['countryId'] = 2;
        }

        return $data;
    }

    public function getArticleWidget($listingId,$listingType,$prefetchedCourseIds=array()){

        $this->_init();

        $currentUrl = getCurrentPageURLWithoutQueryParams();
        //check for sticky article
        $stickyArticleId = $this->institutedetailmodel->checkForStickyArticle($listingId,$listingType);
        $stickyArticle = array();
        $totalArticles = 0;
        if(!empty($stickyArticleId)){
            $stickyArticle = array($stickyArticleId);
            $totalArticles += 1;
        }

        //get Recommended articles for an institute
        $count = (!empty($stickyArticle))?'2':'3';
        $this->load->library('ContentRecommendation/ArticleRecommendationLib');

        if($listingType == 'institute'){
            $articleArray = $this->articlerecommendationlib->forInstitute($listingId,$stickyArticle,$count);

        }else if($listingType == 'university'){
            $articleArray = $this->articlerecommendationlib->forUniversity($listingId,$stickyArticle,$count,0,'RELEVANCY',$prefetchedCourseIds);
        }

        if(!empty($stickyArticle)){
            $finalArray = array_merge($stickyArticle,$articleArray['topContent']);
        }else{
            $finalArray = $articleArray['topContent'];
        }

        //get article details from article repository
        if(!empty($finalArray)){
            $totalArticles += $articleArray['numFound'];

             $this->load->builder('ArticleBuilder','article');
             $this->articleBuilder = new ArticleBuilder;
             $this->articleRepository = $this->articleBuilder->getArticleRepository();
             $articleObj = $this->articleRepository->findMultiple($finalArray);
            if(!empty($articleObj)){
                foreach($articleObj as $key =>$article){
                    if(!empty($stickyArticle) && $article->getId() == $stickyArticleId){
                        $stickyArticledata[] = array('id'=>$article->getId(),
                                                'url'=>$article->getUrl(),
                                                'blogTitle'=>$article->getTitle(),
                                                'summary'=>$article->getsummary()
                                                );
                     }else{
                         $id = $article->getId();
                         $articleData[$id] = array('id'=>$id,
                            'url'=>$article->getUrl(),
                            'blogTitle'=>$article->getTitle(),
                            'summary'=>$article->getsummary()
                            );

                         }
                 }

				 $result= array();
                 foreach($articleArray['topContent'] as $val){
                    $result[] = $articleData[$val];
                 }

                $displayData['all_article_url'] =  $currentUrl.'/articles';
                $displayData['totalArticles'] = $totalArticles;
                $displayData['articleInfo'] = (!empty($stickyArticle))?array_merge($stickyArticledata,$result):$result;
                $displayData['listing_type'] = $listingType;

                $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
                if($userId > 0){
                    $displayData['GA_userLevel'] = 'Logged In';
                }
                else{
                      $displayData['GA_userLevel'] = 'Non-Logged In';
                }

                return array(
                        'html'=>$this->load->view('nationalInstitute/InstitutePage/ArticlesWidget',$displayData,true),
                        'totalCount' => $displayData['totalArticles']
                    );
            }
        }else{
            return "";
        }


    }

    public function getRecommendedListingWidget($listingId,$listingType,$widgetType,$prefetchedCourseIds=array()){
        $this->_init();

        $this->load->library('recommendation/alsoviewed');
        $widgetHeading = 'Students who viewed this college also viewed the following colleges';

        $prefetchedCourseIdMapping = array( $listingId => $prefetchedCourseIds);


        switch ($listingType) {
            case 'course':
                if($widgetType == 'similar'){
                    $this->load->library('recommendation/similar');
                    $results = $this->similar->getSimilarCourses(array($listingId), '16');
                    $widgetHeading = 'You may be interested in the following similar courses';
                    $widgetTrackingKeyId = 227;
                }else{
                    $this->load->library('recommendation/alsoviewed');
                    $results = $this->alsoviewed->getAlsoViewedCourses(array($listingId), '16');
                    $widgetHeading = 'Students who viewed this course also viewed the following courses';
                    $widgetTrackingKeyId = 226;
                }
                $dBrochureRecoLayer = 1011;
                $compareRecoLayer   = 1012;
                $applyNowRecoLayer  = 1013;
                $shortlistRecoLayer = 1014;
                break;
            case 'institute':
                if($widgetType == 'similar'){
                    $results = $this->_getGroupRecommendationData($listingId);
                    if(!empty($results)){
                        $widgetHeading = $results['title'];
                        $results = $results['colleges'];
                    }
                    $widgetTrackingKeyId = 1120;
                }else{
                    $results = $this->alsoviewed->getAlsoViewedInstitutes(array($listingId),'16');
                    $widgetTrackingKeyId = 1119;
                }
                $dBrochureRecoLayer = 995;
                $compareRecoLayer   = 996;
                $applyNowRecoLayer  = 998;
                $shortlistRecoLayer = 997;
                # code...
                break;
            case 'university':
                if($widgetType == 'similar'){
                    $results = $this->_getGroupRecommendationData($listingId);
                    if(!empty($results)){
                        $widgetHeading = $results['title'];
                        $results = $results['colleges'];
                    }
                    $widgetTrackingKeyId = 1122;
                }else{
                    $results = $this->alsoviewed->getAlsoViewedUniversities(array($listingId),'16', array(), $prefetchedCourseIdMapping);
                    $widgetTrackingKeyId = 1121;
                    $widgetHeading      = 'Students who viewed this university also viewed the following';
                }
                $dBrochureRecoLayer = 1005;
                $compareRecoLayer   = 1001;
                $applyNowRecoLayer  = 1006;
                $shortlistRecoLayer = 1009;
                break;
        }

        //setting tracking key ids
        $displayData['widgetTrackingKeyId'] = $widgetTrackingKeyId;
        $displayData['dBrochureRecoLayer']  = $dBrochureRecoLayer;
        $displayData['compareRecoLayer']    = $compareRecoLayer;
        $displayData['applyNowRecoLayer']   = $applyNowRecoLayer;
        $displayData['shortlistRecoLayer']  = $shortlistRecoLayer;

        $courseInfo = array();
        if(!empty($results)){
            if($listingType == 'course'){
                $courseList = array();
                $instituteList = array();
                foreach ($results as $key => $data) {
                    $courseList[] = $data['courseId'];
                }

                $courseObj = $this->courseRepo->findMultiple($courseList,'',true);
                foreach($courseObj as $courseId =>$course) {
                    if($course->getId()){
                        $id                                      = $course->getId();
                        $instituteId                             = $course->getInstituteId();
                        $courseInfo[$instituteId]['course_id']   = $course->getId();
                        $courseInfo[$instituteId]['course_name'] = $course->getName();
                        $courseInfo[$instituteId]['course_url']  = $course->getURL();
                        $instituteList[]                         = $instituteId;
                    }
                }

                if(is_array($instituteList) && !empty($instituteList)){
                     $instituteObj = $this->instituteRepo->findMultiple($instituteList,array('basic','media'),true);
                }

            }else{
                $instituteObj = $this->instituteRepo->findMultiple($results,array('basic','media'),true);
            }

            foreach($instituteObj as $instituteId =>$institute) {
                $id = $institute->getId();
                if(!empty($id)){
                     $result[] = $this->prepareRecommendedCollegeData($institute,$courseInfo);
                }
            }
            $displayData['RecommendedListingData'] = $result;
            $displayData['widgetType']             = $widgetType;
            $displayData['widgetHeading']          = $widgetHeading;

            $displayData['listing_type'] = $listingType;
            $displayData['fromPage']     = $listingType.'DetailPage';

            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }

            if($widgetType == 'similar'){
                $displayData["GA_Tap_On_Reco"] = 'SIMILAR_RECO_LISTING';
            }
            else{
                $displayData["GA_Tap_On_Reco"] = 'ALSO_VIEWED_RECO';
            }
            echo $this->load->view('nationalInstitute/InstitutePage/RecommendedCollegeWidget',$displayData);
        }

    }

    function prepareRecommendedCollegeData($instituteObj,$courseDetails){

        $mainLocationObj = $instituteObj->getMainLocation();
        if(!empty($mainLocationObj)){
            $main_location = $mainLocationObj->getCityName();
        }

        $headerImage = $instituteObj->getHeaderImage();

        // if header image exists get its variant otherwise use default image
        if($headerImage && $headerImage->getUrl()){
            $imageLink = $headerImage->getUrl();
            $imageUrl = getImageVariant($imageLink,6);
        }
        else{
            $imageUrl = MEDIAHOSTURL."/public/images/recommend_dummy.png";
        }

        $result = array(
                        'institute_name'       =>   $instituteObj->getName(),
                        'institute_id'         =>   $instituteObj->getId(),
                        'institute_url'        =>   $instituteObj->getURL(),
                        'image_url'            =>   $imageUrl,
                        'main_location'        =>   $main_location,
                        'establish_year'       =>   $instituteObj->getEstablishedYear(),
                        'is_autonomous'        =>   $instituteObj->isAutonomous(),
                        'isNationalImportance' =>   $instituteObj->isNationalImportance(),
                        'course_name'          =>   $courseDetails[$instituteObj->getId()]['course_name'],
                        'course_url'           =>   $courseDetails[$instituteObj->getId()]['course_url'],
                        'course_id'            =>   $courseDetails[$instituteObj->getId()]['course_id'],
                        'listingType'          =>   $instituteObj->getType()
                );

        return $result;
    }

    public function getLocationsContactWidget($listingObj,$currentLocationObj, $getDataOnly = false,$locationsMappedToCourse, $customParams){

        $this->_init();

        $locationObj       = $listingObj->getLocations();
        $cityName          = $currentLocationObj->getCityName();
        $localityName      = $currentLocationObj->getLocalityName();
        $locationContactObj= $currentLocationObj->getContactDetail();
        $stateName         = $currentLocationObj->getStateName();


        if(!empty($locationContactObj)){
            $contactDetails = array(
                'locality_name'            => $localityName,
                'city_name'                => $cityName,
                'state_name'               => $stateName,
                'generic_contact_number'   => $locationContactObj->getGenericContactNumber(),
                'generic_email'            => $locationContactObj->getGenericEmail(),
                'address'                  => $locationContactObj->getAddress(),
                'admission_contact_number' => $locationContactObj->getAdmissionContactNumber(),
                'admission_email'          => $locationContactObj->getAdmissionEmail(),
                'latitude'                 => $locationContactObj->getLatitude(),
                'longitude'                => $locationContactObj->getLongitude(),
                'google_url'               => $locationContactObj->getGoogleStaticMap()
            );

            $website_url = $locationContactObj->getWebsiteUrl();
            $contactDetails['website_url'] = prep_url($website_url);

            $displayData['contactDetails'] = $contactDetails;


            $displayData['listing_type'] = $listingObj->getType();
			$displayData['contact_listing_id'] = $listingObj->getId();

            $displayData['state_name']    = $stateName;
            $displayData['city_name']     = $cityName;
            $displayData['locality_name'] = $localityName;

            if($displayData['listing_type'] == 'course')
            {
                $displayData['instituteName_contact'] = $listingObj->getInstituteName();
                $displayData['showAllBranches'] = (count($locationObj)>1)?TRUE:FALSE;
                $displayData['affiliatedUniversityName'] = $customParams['affiliatedUniversityName'];
                $displayData['instituteNameWithLocation'] = $customParams['instituteNameWithLocation'];

		$actualLocationId = $locationContactObj->getActualListingLocationId();

                if(!empty($actualLocationId))
                {
                    $result = $this->institutedetailmodel->getListingLocationInfo($actualLocationId);
                    $displayData['contactDetails']['locality_name'] = $result['locality_name'];
                    $displayData['contactDetails']['city_name'] = $result['city_name'];
                    $displayData['contactDetails']['state_name'] = $result['state_name'];
                }
            }
            else
            {
                $displayData['instituteName_contact'] = $listingObj->getName();
                $displayData['showAllBranches'] = (count($locationsMappedToCourse)>1)?TRUE:FALSE;
            }
            $displayData['listing_location_id'] = $currentLocationObj->getLocationId();

            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }
            if($getDataOnly) {
                return $displayData;
            }
            $displayData['name'] = $listingObj->getName();

            $displayData['userId'] = $userId;

            //Parsing aggregate Review Data to the Schema File for Markup.
            if($displayData['listing_type'] == 'course'){
                if(!empty($customParams['reviewParams'])){
                    $displayData['aggregateReviewData'] = $customParams['reviewParams'];
                }
            }
            else{
                if(!empty($customParams)){
                    $displayData['aggregateReviewData'] = $customParams;
                }
            }
           
            $return = array();

    	    $return['schema'] = $this->load->view('InstitutePage/SchemaFile',$displayData,true);

            $return['contact'] = $this->load->view('InstitutePage/ContactDetailsWidget',$displayData,true);
            return $return;
        }else{
            return;
        }
    }

    public function getMultiLocationLayer($listingId, $listingType, $instituteObj, $instituteCurrentLocation = '', $prefetchedCourseIds = array()){
        $this->_init();

        $displayData = array();
        // get all courses
        if(empty($prefetchedCourseIds))
            $courseList = $this->instituteDetailLib->getAllCoursesForInstitutes($listingId);
        else
            $courseList = $prefetchedCourseIds;

        $courseList    = $courseList['courseIds'];
        $allCourseList = $courseList;

        if(empty($instituteObj)){
            $instituteObj = $this->instituteRepo->find($listingId,array('location'));
        }

        $instituteLocations = $instituteObj->getLocations();
        if(empty($instituteCurrentLocation))
            $instituteCurrentLocation = $instituteObj->getMainLocation();

        $instituteLocationId     = $instituteCurrentLocation->getLocationId();
        $eligibleLocationCourses = array();
        $locationsMappedToCourse = array();

        $eligibleLocationCourses     = $this->institutedetailsmodel->getCoursesHavingLocations($courseList, $instituteLocationId);
        $locationsMappedToCourse = $this->institutedetailsmodel->getUniqueCoursesLocations($courseList);

        // $courseWiseLocations = $this->institutedetailsmodel->getCoursesLocations($courseList);

        // foreach ($courseWiseLocations as $courseId => $locationIds) {
        //     if(in_array($instituteLocationId, $locationIds)){
        //         $eligibleLocationCourses[] = $courseId;
        //     }
        //     $locationsMappedToCourse = array_merge($locationsMappedToCourse, $locationIds);
        // }

        $locationsMappedToCourse = array_values(array_unique($locationsMappedToCourse));

        $displayData['obj'] = $instituteObj;
        $displayData['heading'] = $instituteObj->getName();
        $displayData['masterUrl'] = $instituteObj->getUrl();
        $displayData['seeAllBranchesData'] = $this->instituteDetailLib->formatLocationForMultilocationLayer($instituteLocations, $locationsMappedToCourse);

        // load the view
        $this->load->view("InstitutePage/multilocationLayer", $displayData);
    }

    public function getMultiLocationLayerForCourse($courseObj, $currentLocation = ''){
        $this->_init();

        $displayData = array();

        $locations = $courseObj->getLocations();

        $instituteLocationId     = $currentLocation->getLocationId();
        $eligibleLocationCourses = array();


        $locationsMappedToCourse = array();
        foreach ($locations as $locObj) {
            $locationsMappedToCourse[] = $locObj->getLocationId();
        }


        $displayData['obj'] = $courseObj;
        $displayData['heading'] = $courseObj->getName();
        $displayData['masterUrl'] = $courseObj->getUrl();
        $displayData['seeAllBranchesData'] = $this->instituteDetailLib->formatLocationForMultilocationLayer($locations, $locationsMappedToCourse);

        // load the view
        $this->load->view("InstitutePage/multilocationLayer", $displayData);
    }

	    /**
    * Function is used for get facility widget on listing detail pages
    * @author : Nithish Reddy
    * @param [type:object] $facilitiesObj [institute/university facilities obj]
    */

    function arrangeFacilitiesInOrder($facilitiesObj)
    {
        $facilitiesData = $this->instituteDetailLib->prepareFacilitiesInformation($facilitiesObj);

        //change this array to config
        /*$displayOrder = array('Moot Court (Law)', 'Design Studio','Library', 'Cafeteria', 'Hostel', 'Sports Complex', 'Gym', 'Hospital / Medical Facilities', 'Wi-Fi Campus', 'Shuttle Service', 'Auditorium', 'Music Room', 'Dance Room', 'A/C Classrooms', 'Convenience Store');*/
        $facilities = array();
        $viewFacilities = array();
        $displayOrder = $this->config->item('facilities_display');
        if(!empty($facilitiesData))
        {
            foreach ($displayOrder as $key => $value) {
            if(!empty($facilitiesData[$value]) && !empty($facilitiesData[$value]['has_facility']))
                {
                    $facilities[$key] = $value;
                    if(!empty($facilitiesData[$value]['description']))
                    {
                        $viewFacilities[] = $value;
                    }
                    elseif(!empty($facilitiesData[$value]['childFacility']) && count($facilitiesData[$value]['childFacility']) > 0)
                    {
                         if($value == 'Hostel')
                            {
                                foreach ($facilitiesData[$value]['childFacility'] as $childKey => $childValue) {

                                    if(!empty($childValue['has_facility']))
                                    {
                                        $viewFacilities[] = $value;
                                        break;
                                    }
                                /*if(!empty($childValue) && !empty($childValue['has_facility']) && ((!empty($childValue['additionalInfo']) && count($childValue) > 0) || !empty($childValue['description'])))
                                {
                                    $viewFacilities[] = $value;
                                }*/
                                }

                            }
                            else
                            {
                                $viewFacilities[] = $value;
                            }
                    }
                    unset($facilitiesData[$value]);
                }

            }
            foreach ($facilitiesData as $key => $value) {
                if($value['facility_id'] == 22 && $value['parent_facility_name'] == 'Others')
                {
                    $facilities['others'][] = $key;
                }
                else if($value['facility_id'] != 22 && !empty($value['has_facility']))
                {
                    $facilities[$value['facility_id']] = $key;
                }
                if(!empty($value['description']) || !empty($value['childFacility']))
                {
                    $viewFacilities[] = $key;
                }

            }
        }
        $viewFacilities = array_unique($viewFacilities);
        return array('facilities' => $facilities,'viewFacilities' => json_encode($viewFacilities));
    }

    /**
    * Function is used for open facility Details layer on listing detail pages
    * @author : Nithish Reddy
    */
    function openFacilityViewDetailsLayer()
    {
        $this->_init();
        $listingId = !empty($_POST['listing_id'])?$this->input->post('listing_id'):50304;
        $infraFacilityName = !empty($_POST['contentName'])?$this->input->post('contentName'):'Hostel';
        $viewFacilitiesList = !empty($_POST['viewDetailSort'])?$this->input->post('viewDetailSort'):'';

        $viewFacilitiesListArray = explode(',', $viewFacilitiesList);



        if(empty($infraFacilityName) || count($viewFacilitiesListArray) == 0)
            return ;

        $instituteObj = $this->instituteRepo->find($listingId,array('facility'));

        $instituteFacilityObj = $instituteObj->getFacilities();

        $facilityInfo = $this->instituteDetailLib->prepareFacilitiesInformation($instituteFacilityObj,$viewFacilitiesListArray);
        $facilityInfoSort = array();

        foreach ($viewFacilitiesListArray as $fKey => $fValue) {
            $facilityInfoSort[$fValue] = $facilityInfo[$fValue];
        }
        $displayData = array();
        $displayData['facilityInfo'] = $facilityInfoSort;
        $displayData['listingId'] = $listingId;
        $displayData['viewFacilitiesListArray'] = $viewFacilitiesListArray;
        $displayData['infraFacilityName'] = $viewFacilitiesListArray[$facilityPosition];
        echo $this->load->view('InstitutePage/viewFacilityDetailLayer',$displayData);
    }


    public function populateAnAProposition(){
        $this->_init();
        $listingId = $this->input->post('listingId');
        if(!is_numeric($listingId)){
            return;
        }

        $pageType    = $this->input->post('pageType');
        $listingType = $this->input->post('listingType');

        $limit = 6;
        if($pageType == 'allContent') {
            $limit = 50;
        }

        $this->load->model('CA/cadiscussionmodel');
        $this->CADiscussionModel  = new CADiscussionModel();
        if((isset($listingId) && $listingId>0) && $listingType == 'institute' || $listingType == 'university'){
            $instituteObj = $this->instituteRepo->find($listingId);
            $instituteId = $instituteObj->getId();
	        $courseIdArray = $this->instituteDetailLib->getAllCoursesForInstitutes($listingId);
        }
        else if($listingType == 'course'){
       	    $courseIdArray['courseIds'] = array($listingId);
        }

         //for GA Tracking purpose
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        if($userId > 0){
            $displayData['GA_userLevel'] = 'Logged In';
        }
        else{
              $displayData['GA_userLevel'] = 'Non-Logged In';
        }

        $displayData['listing_type'] = $listingType;

        if(isset($courseIdArray['courseIds']) && count($courseIdArray['courseIds'])>0){
            $campusRepData = $this->cadiscussionmodel->getCampusRepInfoForCourse($courseIdArray['courseIds'], $listingType ,$instituteId, $limit, true,$getCaAnsCount = false);
            if(is_array($campusRepData) && isset($campusRepData['caInfo']) && count($campusRepData) > 0){  //Campus Rep found
    		    $displayData['campusReps'] = array();
    		    $usersAdded = array();
                $campusRepCount = 0;
                $campusRepPrimaryCourseIds = array();
    		    foreach ($campusRepData['caInfo'] as $courseReps){
        			foreach ($courseReps as $rep){
        				if(!in_array($rep['userId'],$usersAdded)){
                            if($campusRepCount >= $limit) {
                                break;
                            }
                            $displayData['campusReps'][] = $rep;
                            if($rep['isPrimaryCourse']) {
                                $campusRepPrimaryCourseIds[] = $rep['courseId'];
                            }
                            $campusRepCount++;
        					array_push($usersAdded,$rep['userId']);
        				}
                    }
                    if($campusRepCount >= $limit) {
                        break;
                    }
    		    }

                if(!empty($campusRepPrimaryCourseIds) && $pageType != 'allContent') {
                    $campusRepPrimaryCourseIds = array_unique($campusRepPrimaryCourseIds);
                    $displayData['courseData'] = $this->courseRepo->findMultiple($campusRepPrimaryCourseIds);
                }

    		    if(isset($_POST['pageType']) && $pageType == 'allContent'){
    			    $this->load->view("AllContentPage/widgets/campusRepWidget",$displayData);
    		    }
    		    else{
    	            $this->load->view('InstitutePage/CampusAmbassadorWidget',$displayData);
    		    }
                return;
            }
        }

	    if(!(isset($_POST['pageType']) && $pageType == 'allContent')){
	            $this->load->view('InstitutePage/CampusAmbassadorWidget',$displayData);
	    }
     }

	    /**
    * Function is used for get gallery widget on listing pages
    * @author : Nithish Reddy
    * @param [type: integer]    $listingId  [instituteId/universityId]
    * @param [type: string]     $listingType [institute/university]
    * @param [type: object]     $photosObj    [institute/university photos obj]
    * @param [type:object]      $videosObj    [institute/university videos obj]
    * @param [type:object]      $currentLocationObj [institute/university currentlocationobj]
    */

    function getGalleryWidget($listingId,$listingType,$photosObj,$videosObj,$currentLocationObj)
    {
        $this->_init();
        $displayData = array();
        $media = $this->instituteDetailLib->prepareGalleryData($photosObj,$videosObj,$currentLocationObj);
        if((!empty($media['photos']['order']) && count($media['photos']['order']) > 0) || !empty($media['videos']['Videos']) && count($media['videos']['Videos']) > 0)
        {
            if($listingType == 'course'){
                $courseObj                         = $this->courseRepo->find($listingId,array('basic'));
                $displayData['listingGallerytext'] = $courseObj->getName();
            }else{
                $instituteObj                      = $this->instituteRepo->find($listingId,array('basic'));
                $institute_name                    = $instituteObj->getName();
                $abbreviation                      = $instituteObj->getAbbreviation();
                $displayData['listingGallerytext'] = !empty($abbreviation) ?$abbreviation : $institute_name;
            }
            $displayData['media']          = $media;
            $displayData['listing_id']     = $listingId;
            $displayData['listing_type']   = $listingType;

            $displayData['currentCityId']     = $currentLocationObj->getCityId();
            $displayData['currentLocalityId'] = $currentLocationObj->getLocalityId();

            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }

            return array(
                        'html'       =>$this->load->view('nationalInstitute/InstitutePage/GalleryWidget',$displayData,true),
                        'totalCount' =>$media['photos']['totalPhotos'] + count($media['videos']['Videos'])
                    );

        }
        else
            return "";

    }
    /**
    *   function is used for open Gallery Layer for listing Id
    *   @author : Nithish Reddy
    */

    function openGalleryLayer()
    {
        $this->_init();
        $listingId   = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
        $listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'';
        $tagName     = !empty($_POST['tagName'])?$this->input->post('tagName'):'';
        $media_id    = !empty($_POST['media_id'])?$this->input->post('media_id'):'';
        $cityId      = !empty($_POST['cityId'])?$this->input->post('cityId'):'';
        $localityId  = !empty($_POST['localityId'])?$this->input->post('localityId'):'';


        if(!is_numeric($listingId)) {
            error_log("------------- Listing Empty with InstituteId: $listingId  ------------------".print_r($_POST, true));
        }
        if(empty($listingId) || empty($tagName) || !is_numeric($listingId))
            return;

        if($listingType == 'course'){
             $listingObj = $this->courseRepo->find($listingId,array('location','media'));

             $this->load->library('nationalCourse/CourseDetailLib');
             $this->courseDetailLib = new CourseDetailLib;
             $currentLocationObj = $this->courseDetailLib->getCourseCurrentLocation($listingObj,array($cityId),array($localityId));
        }else{
            $listingObj = $this->instituteRepo->find($listingId,array('location','media'));//,array('media','location')
            $currentLocationObj = $this->instituteDetailLib->getInstituteCurrentLocation($listingObj,$cityId,$localityId);
        }
        if(empty($currentLocationObj)){
            error_log("------------- Location Empty with InstituteId: $listingId  ------------------");
            return;
        }
        $media = $this->instituteDetailLib->prepareGalleryData($listingObj->getPhotos(),$listingObj->getVideos(),$currentLocationObj);

        $displayData = array();
        $displayData['institute_name'] = $listingObj->getName();
        $displayData['media']          = $media;
        $displayData['listingId']      = $listingId;
        $displayData['tagName']        = $tagName;
        $displayData['media_id']       = $media_id;
        $displayData['listingType'] = $listingType;
        if($listingType != 'course')
            $displayData['abbreviation']   = $listingObj->getAbbreviation();
        else
            $displayData['abbreviation']   = '';

        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        if($userId > 0){
            $displayData['GA_userLevel'] = 'Logged In';
        }
        else{
              $displayData['GA_userLevel'] = 'Non-Logged In';
        }

        echo $this->load->view('InstitutePage/viewGalleryLayer',$displayData);
    }


    public function getAnAWidget($listingId,$listingType,$prefetchedCourseIds=array(), $customParams = array()){

        $this->_init();

        //get Recommended QNA for Listing
        $count = '3';
        //Fetch the Recommended Questions from the API
        $this->load->library('ContentRecommendation/AnARecommendationLib');
        $seoListingId   = $listingId;
        $seoListingType = $listingType;
        if($listingType == 'institute' && $listingId > 0){
	        //Fetch the Recommended Questions from the API
            $this->load->library('ContentRecommendation/AnARecommendationLib');
            $questionIds = $this->anarecommendationlib->forInstitute($listingId, array(), $count, 0,'question');
        } else if($listingType == 'course' && $listingId > 0){
            $courseObj = $this->courseRepo->find($listingId);
            $courseId = $listingId;
            $seoListingId = $courseObj->getInstituteId();
            $seoListingType = $courseObj->getInstituteType();
            $this->benchmark->mark('courseanasort_start');
            $questionIds = $this->anarecommendationlib->forCourse($listingId, array(), $count, 0,'RELEVANCY');
            $this->benchmark->mark('courseanasort_end');
        } else if($listingType == 'university' && $listingId > 0){
            $questionIds = $this->anarecommendationlib->forUniversity($listingId, array(), $count, 0,'question','RELEVANCY',$prefetchedCourseIds);
        }


	if(is_array($questionIds) && isset($questionIds['topContent'])){
            $finalArray = $questionIds['topContent'];
            $totalNumber = $questionIds['numFound'];
	}

        //Get questions details from DB
	$displayData = array();
        if(!empty($finalArray)){
            $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	    $questionIds = implode(',',$finalArray);

            $this->benchmark->mark('anadetailsquery_start');
            $this->load->model("messageBoard/anamodel");
            $questionsDetail = $this->anamodel->getQuestionsDetails($questionIds, $userId, false, false);
	    $this->benchmark->mark('anadetailsquery_end');
            if(is_array($questionsDetail)){
		//Now, load the view file to create UI
		$displayData['totalNumber'] = $totalNumber;
		$displayData['questionsDetail'] = $questionsDetail;
		$displayData['loggedInUser'] = $userId;
            }

            $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
            $displayData['listing_type'] = $listingType;

            if( ($seoListingType == 'institute' || $seoListingType == 'course' || $seoListingType == 'university') && $seoListingId > 0 ) {
	           $displayData['allQuestionURL'] = getSeoUrl($seoListingId,'all_content_pages','',array('typeOfPage'=>'questions','typeOfListing'=>$seoListingType, 'courses' => array($courseId)));
                if($customParams['getCount']) {
                    $anaWidgetHtml = $this->load->view('nationalInstitute/InstitutePage/ANAWidget', $displayData, true);
                    return array('html' => $anaWidgetHtml, 'count' => $totalNumber,'allQuestionURL'=>$displayData['allQuestionURL']);
                }
                else {
                    echo $this->load->view('nationalInstitute/InstitutePage/ANAWidget', $displayData);
                }

                return;
            }
        }
        else {
            if($customParams['getCount']) {
                $anaWidgetHtml = $this->load->view('nationalInstitute/InstitutePage/ANAWidget', $displayData, true);
                return array('html' => $anaWidgetHtml, 'count' => $totalNumber);
            }
        }
    }

   function getMultiANAWidget($page, $listingIds, $listingType, $count = 3, $showCR = false, $prefetchedCourseIds = array(), $customParams = array()){

      $this->_init();

      if(!is_array($listingIds)){
         return;
      }
      if(count($listingIds)<=0){
         return;
      }

      $this->load->library('ContentRecommendation/AnARecommendationLib');
      $questionIds = $this->anarecommendationlib->forCourses($listingIds, array(), $count, 0,'RELEVANCY');

      if(is_array($questionIds) && isset($questionIds['topContent'])){
         $finalArray = $questionIds['topContent'];
	 $totalNumber = $questionIds['numFound'];
      }

      //Get questions details from DB
      $displayData = array();
      if(!empty($finalArray)){
         $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
         $questionIds = implode(',',$finalArray);

         $this->load->model("messageBoard/anamodel");
         $questionsDetail = $this->anamodel->getQuestionsDetails($questionIds, $userId, false, false);

         if(is_array($questionsDetail)){
            //Now, load the view file to create UI
            $displayData['questionsDetail'] = $questionsDetail;
	    $displayData['totalNumber'] = $totalNumber;
	    $displayData['count'] = $count;
	    $displayData['showCR'] = $showCR;
	    $displayData['page'] = $page;
            $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
            $displayData['listing_type'] = $listingType;

            $courseObj = $this->courseRepo->find($listingIds[0]);
            $seoListingId = $courseObj->getInstituteId();
            $seoListingType = $courseObj->getInstituteType();
            $displayData['allQuestionURL'] = getSeoUrl($seoListingId,'all_content_pages','',array('typeOfPage'=>'questions','typeOfListing'=>$seoListingType));

            $this->load->view('nationalInstitute/InstitutePage/ANAWidget', $displayData);
         }
      }
      else {
         return;
      }
   }

    /**
    * Purpose : Function to check and set the values is displayData array necessary for making the response for institute page eg. institute_viewed
    *
    **/
    private function _checkAndSetDataForAutoResponseForInstitutePage($course, &$displayData, $listingType)
    {
        $this->benchmark->mark('auto_response_start');
        $validateuser = $displayData['validateuser'];

        $this->load->model('qnAmodel');
        $this->qnamodel = new QnAModel();
        $validResponseUser = 0;
        if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && (!($this->qnamodel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != ""))
        {
            $validResponseUser = 1;
            $displayData['validResponseUser'] = $validResponseUser;
        }
        $displayData['viewedResponseAction'] = 'Institute_Viewed';

        if($listingType == 'institute') {
            $displayData['instituteViewedTrackingPageKeyId'] = DESKTOP_NL_INSTITUTE_VIEWED;
        } else {
            $displayData['instituteViewedTrackingPageKeyId'] = DESKTOP_NL_UNIVERSITY_VIEWED;
        }
        $this->benchmark->mark('auto_response_end');
    }

    private function _populateCurrentLocation(& $displayData, $institute,$course,$pageType = 'institute'){

        $multiple_locations = array();
        if($course){
            $locations = $course->getLocations();
            $currentLocation = $course->getMainLocation();
        }else{
            $locations = $institute->getLocations();
            $currentLocation = $institute->getMainLocation();
        }
        foreach($locations as $location){
            $localityId = $location->getLocalityId()?$location->getLocalityId():0;
            if($_REQUEST['city'] == $location->getCityId()){
                if((!array_key_exists('locality',$_REQUEST) || empty($_REQUEST['locality'])) && $location->isHeadOffice()) {
                    $currentLocation = $location;
                    break;
                }
                if($_REQUEST['locality'] == $localityId){
                    $currentLocation = $location;
                    break;
                }
                $matched_city_array[] = $location;
                $currentLocation = $matched_city_array[0];
            }
        }
        $displayData['currentLocation'] = $currentLocation;
        return $currentLocation;
    }

	public function getReviewWidget($listingId,$listingType,$prefetchedCourseIds=array(),$InstUrl='', $customParams = array(),$listingObj = NULL, $allCoursePage=false ,$isPaid =false){
        $this->_init();

        $this->load->library('ContentRecommendation/ReviewRecommendationLib');

        $count = 4;

        if($allCoursePage == true){
            $count = 15;
            $data = $this->reviewrecommendationlib->forCourse($prefetchedCourseIds,array(),$count,0,'GRADUATION_YEAR','');
            if(count($data['topContent'])>4){
                $randomKeys=array_rand($data['topContent'],4);
                $reviewIds = array();
                foreach($randomKeys as $key){
                    $reviewIds[] = $data['topContent'][$key];
                }
                $data['topContent'] = $reviewIds;
            }
            if($listingObj!=NULL){
               $instituteObj = $listingObj;
            }else{
               $instituteObj = $this->instituteRepo->find($listingId);
            }
            $instituteName = getInstituteNameWithCityLocality($instituteObj->getName(),'institute',$instituteObj->getMainLocation()->getCityName());
            $displayData['listingName'] = $instituteName['instituteString'];
            $displayData['listingUrl'] = $instituteObj->getURL();

        }
        else{
               //get reviews for an institute
               if($listingType == 'institute'){
                   $data = $this->reviewrecommendationlib->forInstitute($listingId,array(),$count,0,'GRADUATION_YEAR');
                   if($listingObj!=NULL){
                       $instituteObj = $listingObj;
                   }else{
                       $instituteObj = $this->instituteRepo->find($listingId);
                   }
                   $instituteName = getInstituteNameWithCityLocality($instituteObj->getName(),'institute',$instituteObj->getMainLocation()->getCityName());
                   $displayData['listingName'] = $instituteName['instituteString'];
                   $displayData['listingUrl'] = $instituteObj->getURL();
               }
               else if($listingType == 'course'){
                   $this->benchmark->mark('coursereviewsort_start');
                   if($listingObj!=NULL){
                       $courseObj = $listingObj;
                   }else{
                       $courseObj = $this->courseRepo->find($listingId);
                   }
                   $data = $this->reviewrecommendationlib->forCourse($listingId,array(),$count,0,'GRADUATION_YEAR', $courseObj);
                   $this->benchmark->mark('coursereviewsort_end');

                   $displayData['listingName'] = $courseObj->getName();
                   $displayData['listingUrl'] = $courseObj->getURL();

                   $instituteObj = $this->instituteRepo->find($courseObj->getInstituteId());
                   $instituteName = getInstituteNameWithCityLocality($instituteObj->getName(),'institute',$instituteObj->getMainLocation()->getCityName());
                   $displayData['instituteName'] = $instituteName['instituteString'];
                   $displayData['instituteUrl'] = $instituteObj->getURL();
               }
               else if($listingType == 'university'){
                   $data = $this->reviewrecommendationlib->forUniversity($listingId,array(),$count,0,'GRADUATION_YEAR',$prefetchedCourseIds);
                   if($listingObj!=NULL){
                       $instituteObj = $listingObj;
                   }else{
                       $instituteObj = $this->instituteRepo->find($listingId);
                   }
                   $instituteName = getInstituteNameWithCityLocality($instituteObj->getName(),'institute',$instituteObj->getMainLocation()->getCityName());
                   $displayData['listingName'] = $instituteName['instituteString'];
                   $displayData['listingUrl'] = $instituteObj->getURL();
               }
        }

        $reviewIdsArray = $data['topContent'];
        $courseIds = array();
        //get review details
        if(!empty($reviewIdsArray)){
            $collegereviewmodel = $this->load->model('CollegeReviewForm/collegereviewmodel');
            $this->benchmark->mark('reviewdetailsquery_start');
            $reviewData = $collegereviewmodel->getReviewsDetails($reviewIdsArray);
            $reviewRating = $collegereviewmodel->getRatingMultipleReviews($reviewIdsArray);
            $this->benchmark->mark('reviewdetailsquery_end');

            if($reviewData && $reviewData['reviews']){
                $reviewData = $reviewData['reviews'];
            }
            foreach($data['topContent'] as $val){
                $reviewDetails[$val] = $reviewData[$val];
            }
            foreach ($reviewData as $key => $reviewRow) {
                $courseIds[] = $reviewRow['courseId'];
            }
            if($listingType == 'course' && !empty($InstUrl) ){
                $displayData['all_review_url'] =  $InstUrl.'/reviews?course='.$listingId;
            }else{
                $displayData['all_review_url'] =  $displayData['listingUrl'].'/reviews';
            }
            $displayData['totalReviews'] = $data['numFound'];
            $displayData['allCoursePage'] = $allCoursePage;
            $displayData['reviewRating'] = $reviewRating;
            $displayData['reviewsData'] = $reviewDetails;
            $displayData['listing_type'] = $listingType;
            $displayData['isPaid'] = $isPaid;

            $displayData['reviewShowing'] = count($reviewIdsArray);
            $displayData['courseInfo'] =  array();
            $displayData['ratingDisplayOrder'] =  array('Placements','Infrastructure','Faculty & Course Curriculum','Crowd & Campus Life','Value for Money');
            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }

            if(!empty($courseIds)){
                $courseObjs = $this->courseRepo->findMultiple($courseIds);
            }
            foreach ($courseObjs as $key => $courseObj) {
                $displayData['courseInfo'][$key]['courseName']   = $courseObj->getName();
                $institeteName= $courseObj->getInstituteName();
                $offeredByShortName= $courseObj->getOfferedByShortName();
                $offeredByName= $courseObj->getOfferedByName();
                if($displayData['courseInfo'][$key]['courseName']){
                    $courseNameSuffix="";
                    $courseOfferedByShortName = trim($offeredByShortName);
                    $courseOfferedByName = trim($offeredByName);
                    $courseInstituteFullName=trim($institeteName);
                    if($listingType=="university"){
                        if($courseOfferedByShortName!=""){
                            if($courseOfferedByShortName!=$displayData['listingName']){
                                $courseNameSuffix=$courseOfferedByShortName;
                            }
                        }
                        elseif($courseOfferedByName!=""){
                            if($courseOfferedByName!=$displayData['listingName']){
                                $courseNameSuffix=$courseOfferedByName;
                            }
                        }
                        elseif ($courseInstituteFullName!="") {
                            if($courseInstituteFullName!=$displayData['listingName']){
                                $courseNameSuffix=$courseInstituteFullName;
                            }
                        }
                        if($courseNameSuffix!=""){
                            $courseNameSuffix=", ".htmlentities($courseNameSuffix);
                        }
                        else{
                            $courseNameSuffix="";
                        }
                    }
                }
                $displayData['courseInfo'][$key]['courseNameSuffix'] = $courseNameSuffix;
                if($allCoursePage ){
                    $displayData['courseInfo'][$key]['course_review_url'] = $displayData['listingUrl'].'/reviews?course='.$key;
                }
            }

            $displayData['aggregateReviewsData'] = $customParams['aggregateReviewsData'];
            $displayData['intervalsDisplayOrder'] = $this->config->item("intervalsDisplayOrder");
            $displayData['aggregateRatingDisplayOrder'] = $this->config->item('aggregateRatingDisplayOrder');

            $ratingIdToDisplayNameMapping = array();
            $crMasterMappingToName = $this->config->item('crMasterMappingToName');
            foreach ($crMasterMappingToName as $ratingId => $ratingName) {
                $ratingIdToDisplayNameMapping[$ratingId] = $displayData['aggregateRatingDisplayOrder'][$ratingName];
            }
            $displayData['ratingIdToDisplayNameMapping'] = $ratingIdToDisplayNameMapping;
            $displayData['crMasterMappingToName'] = $crMasterMappingToName;
            $displayData['showRatingFilterUrl'] = $customParams['showRatingFilterUrl'];

            if($customParams['getCount']) {
                $reviewWidgetHtml = $this->load->view('nationalInstitute/InstitutePage/aggregateReviewWidget',$displayData, true);
                return array('html' => $reviewWidgetHtml, 'count' => $displayData['totalReviews']);
            }
            else {
                echo $this->load->view('nationalInstitute/InstitutePage/aggregateReviewWidget',$displayData);
            }

        }else{
            return;
        }


    }

    private function _getGroupRecommendationData($listingId){

        $this->load->config('nationalInstitute/groupRecoConfig');
        $recommendationData = $this->config->item("recommendationData");

        $result = array();
        $limit  = 12;

        $instituteId = $listingId;

        $recommentationGroup = array();
        foreach ($recommendationData as $value) {
            if(in_array($instituteId, $value['colleges'])){
                $recommentationGroup = $value;
                break;
            }
        }

        if($recommentationGroup){

            $recommendedColleges = $recommentationGroup['colleges'];

            // remove current institute
            if(($key = array_search($instituteId, $recommendedColleges)) !== false) {
                unset($recommendedColleges[$key]);
            }
            $recommendedColleges = array_values($recommendedColleges); // reset the keys

            if(empty($recommendedColleges)){
                return $result;
            }

            $viewCount = $this->instituteDetailLib->getInstituteViewCount($recommendedColleges);

            $recommendedColleges = array_keys($viewCount);

            $recommendedColleges = array_slice($recommendedColleges, 0 , $limit, true);
            $result['colleges'] = $recommendedColleges;
            $result['title'] = "You may also be interested in following ".$recommentationGroup['group_name']." ".$recommentationGroup['supporting_text'];
        }

        return $result;
    }

    function checkIfUserShortlistedCourse($courseId,$ampViewFlag = false){

        $this->_init();

        $errorMsg = 0;
        $error    = 0;
        $courseAlreadyExists = 0;
        $userId   = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userId   = (int)$userId;

        if($userId === 0){
            if($ampViewFlag)
            {
                return false;
            }
            $result = array('courseAlreadyExists' => 0, 'error' => 0, 'errorMsg' => '');
            echo json_encode($result);
            exit(0);
        }

        if(empty($courseId)){
            $error = 1;
            $errorMsg = 'Something went wrong !!!';
            if($ampViewFlag)
            {
                return false;
            }
            echo json_encode(array('error' => $error, 'errorMsg' => $errorMsg));exit(0);
        }

        // get all shortlisted courses of user
        $courseIds = Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $userId, 'national');

        if(!is_array($courseIds)){
            $error = 1;
            $errorMsg = $courseIds;
            if($ampViewFlag)
            {
                return false;
            }
            echo json_encode(array('error' => $error, 'errorMsg' => $errorMsg));exit(0);
        }

        // check if user has already shortlisted the course
        if(in_array($courseId, $courseIds)){
            $courseAlreadyExists = 1;
        }
        else{
            $courseAlreadyExists = 0;
        }

        if($ampViewFlag && $courseAlreadyExists)
        {
            return true;
        }
        elseif ($ampViewFlag && !$courseAlreadyExists) {
            return false;
        }

        $result = array('courseAlreadyExists' => $courseAlreadyExists, 'error' => $error, 'errorMsg' => $errorMsg);

        echo json_encode($result);exit(0);
    }
	/**
    * @param: $listingId : university id
    * @return : list of exams directly mapped to university listing with sorted order
    */
    function getExamsMappedToUniversity($listingId)
    {
        $this->_init();
        $examList = $this->institutedetailmodel->getExamsMappedToUniversity($listingId);

        function examSort($a,$b)
                 {
                    if ($a['name']==$b['name']) return 0;
                      return (strtolower($a['name'])<strtolower($b['name']))?-1:1;
                 }

        usort($examList,"examSort");
        return $examList;

    }

    function getAllInstituteLayerData(){

        $this->_init();
        $universityId = $this->input->get("universityId");

        $displayData = array();
        if($universityId){
            $institutesData = $this->instituteDetailLib->getAllInstitutesOfUniversity($universityId);
        }

        $displayData['heading'] = "Colleges / Departments under ".(($institutesData['universityObj']->getAbbreviation()) ? $institutesData['universityObj']->getAbbreviation() : $institutesData['universityObj']->getName());
        $displayData['searchBoxText'] = "Type the name of a college or department...";
        $displayData['institutesData'] = $institutesData;
        
        $ids = $institutesData['instituteIds'];
        $listingType = 'institute';
        $displayData['institutesData']['aggregateReviewsData'] = array();
        $displayData['institutesData']['reviewCount'] = array();

        // get aggregate reviews of all institutes in universities
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $displayData['institutesData']['aggregateReviewsData']=$collegeReviewLib->getAggregateReviewsForListing($ids,$listingType);

        // get total reviewCount fo all institutes of university
        $preFetchedCourseIds=$this->instituteDetailLib->getAllCoursesForMultipleInstitutes($ids);
        $this->load->library('ContentRecommendation/ReviewRecommendationLib');
        $displayData['institutesData']['reviewCount']=$this->reviewrecommendationlib->getInstituteReviewCounts($ids,$preFetchedCourseIds);
      //  _p($displayData['institutesData']['aggregateReviewsData']);die();
        $this->load->view("nationalInstitute/InstitutePage/allInstituteLayer", $displayData);
    }

    function checkUniversityProvidesAffiliation(){

        $this->institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
        $universityId = $this->input->get("universityId");

        $affiliationMapping = $this->institutedetailmodel->getAffiatedCoursesOfUniversity($universityId);

        if(empty($affiliationMapping)){
            echo 0;exit(0);
        }
        echo 1;
    }

    function getAffiliationLayerData(){

        $this->_init();
        $universityId = $this->input->get("universityId");

        $displayData = array();
        if($universityId){
            $institutesData = $this->instituteDetailLib->getInstitutesAffiliatedToUniversity($universityId);

            if(empty($institutesData)){
                return "";exit(0);
            }
        }
        if(!empty($institutesData['universityObj']))
        {
            $displayData['heading'] = "Affiliated Colleges of ".(($institutesData['universityObj']->getAbbreviation()) ? $institutesData['universityObj']->getAbbreviation() : $institutesData['universityObj']->getName());
            $displayData['searchBoxText'] = "Type the name of an affiliated college...";

            $displayData['institutesData'] = $institutesData;

            $this->load->view("nationalInstitute/InstitutePage/allInstituteLayer", $displayData);
        }
        else
        {
            echo "fail";exit(0);
        }

    }

    function getCountOfUgcWidgets($listingId,$listingType)
    {
        $this->_init();

        $listingId   = !empty($_POST['listingId']) ? $this->input->post('listingId'):'';
        $listingType = !empty($_POST['listingType']) ? $this->input->post('listingType'):'';
        $content     = !empty($_POST['content']) ? $this->input->post('content'):'';

        $content = explode(',', $content);

        if(empty($listingId) || empty($listingType) || (empty($content) && count($content) == 0))
        {
            return;
        }
        if($listingType == 'university')
        {
            $preFetchedCourseIds = $this->institutedetaillib->getInstituteCourseIds($listingId,'university');
        }
        else
        {
            $allInstitutesCourses = $this->institutedetaillib->getAllCoursesForInstitutes($listingId);
        }

        $data = array();

        if(in_array('ana', $content))
        {
            $data['ana'] = $this->instituteDetailLib->getCountofQuestionsForListings($listingId,$listingType,$prefetchedCourseIds,$allInstitutesCourses);
        }
        if(in_array('review', $content))
        {

            $data['review'] =  $this->instituteDetailLib->getCountOfReviewsForListings($listingId,$listingType,$prefetchedCourseIds,$allInstitutesCourses);
        }
        if(in_array('articles', $content))
        {
            $data['articles'] =  $this->instituteDetailLib->getArticleCoutForListing($listingId,$listingType,$prefetchedCourseIds,$allInstitutesCourses);
        }

        echo json_encode($data);die;

    }

    function getAllCourseForUniversity(){
        $this->benchmark->mark('institute_start');
        $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
        $instituteId = $this->input->post('instituteId', true);
        $instituteType = $this->input->post('instituteType', true);
        $insttWisecourses = $this->instituteDetailLib->getAllCoursesForInstitutes($instituteId);

        $this->load->builder('nationalInstitute/InstituteBuilder');
        $instituteBuilder = new InstituteBuilder();
        $instituteRepository = $instituteBuilder->getInstituteRepository();

        $instituteIds = array_keys($insttWisecourses['instituteWiseCourses']);
        if(empty($instituteIds)) {
            return;
        }
        $instituteObjs = $instituteRepository->findMultiple($instituteIds);
        $this->load->builder('nationalCourse/CourseBuilder');
        $courseBuilder = new CourseBuilder();
        $courseRepository = $courseBuilder->getCourseRepository();

        $courseObjs = $courseRepository->findMultiple($insttWisecourses['courseIds']);

        foreach ($insttWisecourses['instituteWiseCourses'] as $instituteId => $courses) {
            $instituteObj=$instituteObjs[$instituteId];
            foreach ($courses as $key => $courseId) {
                $courseObj=$courseObjs[$courseId];
                $courseName = $courseObj->getName();
                $instituteName = $courseObj->getOfferedByShortName();
                $instituteName = $instituteName ? $instituteName : $instituteObj->getShortName();
                $instituteName = $instituteName ? $instituteName : $instituteObj->getName();

                $courseName = $courseObj->getName();
                $courseName .= ", ".$instituteName;
                $courseList[] = array('course_id' => $courseId, 'course_name' => $courseName);
            }
        }
        $this->benchmark->mark('institute_end');
        echo json_encode($courseList);
    }

    function sendInstituteDigest($instituteId){
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
         $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0 && !in_array($this->userStatus[0]['usergroup'],array("enterprise","cms","experts","sums","listingAdmin"))){
                $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
                $this->instituteDetailLib->sendInstituteDigestMailForUser($userId, $instituteId);
            _P('mailed success');
            }

    }

    // this is used form autosuggestor result click
    function getInstituteURL(){
        $instituteId = $this->input->post('instituteId',true);
        if(empty($instituteId) || !is_numeric($instituteId)){
            return;
        }
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();

        // get institute repository with all dependencies loaded
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $ilpData = $instituteRepo->find($instituteId);
        if(empty($ilpData) || empty($ilpData->getURL()) || empty($ilpData->getId())){
            return;
        }
        echo $ilpData->getURL();exit;
    }

}

?>
