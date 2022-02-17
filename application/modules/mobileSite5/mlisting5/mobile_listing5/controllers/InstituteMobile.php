<?php 
class InstituteMobile extends ShikshaMobileWebSite_Controller{
    
    //initialize data
    private function _init(){
    
        $this->load->helper(array('mcommon5/mobile_html5'));
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->load->config('mcommon5/mobi_config');
        $this->load->helper(array('string','image'));
        $this->load->config('nationalInstitute/instituteSectionConfig');
	    $this->load->helper(array('image','shikshautility'));

        $this->load->config('CollegeReviewForm/collegeReviewConfig');

        $this->institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository(); 
        $this->load->helper('listingCommon/listingcommon');
		$this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');

        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();

    }

    /**
     * Method to show institute detail page on mobile
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2016-10-25
     * @param  [type]     $listingId   [institute/university id]
     * @param  [type]     $listingType [institute/university]
     * @return [type]                  [displays institute page]
     */
    function getInstituteDetailPage($listingId,$listingType,$ampViewFlag=false){
        $ampViewFlag = false; //disabling amp page
        $this->benchmark->mark('loading_dependencies_start');
        // show 404 if any of the mandatory input is empty
        if(empty($listingId) || empty($listingType)){
            show_404();
            exit(0);
        }

        // initialize things
        $this->_init();
        $this->load->config('nationalCategoryList/nationalConfig');
        $this->load->config('nationalInstitute/instituteStreams');
        $this->benchmark->mark('loading_dependencies_end');

        $displayData  = array();
        
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData['validateuser'] = $this->userStatus;

        $this->benchmark->mark('institute_find_and_redirects_start');
        // fetch institute object
        $instituteObj = $this->instituteRepo->find($listingId,'full');
        $listingType  = $instituteObj->getType();

        // check if institute is deleted and if it needs to be redirected

        $this->instituteDetailLib->_checkForCommonRedirections($instituteObj, $listingId, $listingType, $ampViewFlag);
        $this->benchmark->mark('institute_find_and_redirects_end');
        
        // prepare view data

        // get courses of institute/university
        $this->benchmark->mark('get_institute_courses_mapping_start');
        $displayData['courseIdsMapping'] = $this->instituteDetailLib->getAllCoursesForInstitutes($listingId);
        $this->benchmark->mark('get_institute_courses_mapping_end');

        $this->benchmark->mark('prepare_institute_data_start');
        $displayData  = $this->_prepareInstituteData($instituteObj, $displayData, $ampViewFlag);


        $displayData['instituteIsPaid'] = $displayData['coursesWidgetData']['instituteHasPaidCourse'];
        $this->benchmark->mark('prepare_institute_data_end');

        $this->benchmark->mark('get_institute_sponsored_widget_start');
        $displayData['sponsoredWidgetData'] = $this->instituteDetailLib->getSponsoredWidgetData($listingId, $displayData['coursesWidgetData']['instituteHasPaidCourse']);
        $this->benchmark->mark('get_institute_sponsored_widget_end');

        $displayData['m_canonical_url'] = $displayData['seo_url'];
        $displayData['listing_id'] = $listingId;
    
        $displayData['m_canonical_url'] = $this->instituteDetailLib->getCanonnicalUrl($displayData['listing_id'],$displayData['m_canonical_url']);
                   
        $displayData['listing_type'] = $listingType;
        $displayData['instituteObj'] = $instituteObj;
        $displayData['userId'] = $userId;
        //pageName identifier is used for to add div elements in footerDailogcode  file for Gallery 
        $displayData['boomr_pageid'] = 'mobilesite_LDP';

        //article widget
        $this->benchmark->mark('article_widget_start');
        $displayData['articleWidget'] = modules::run('mobile_listing5/InstituteMobile/getArticleWidget',$listingId,$listingType,$displayData['courseIdsMapping'], $displayData['seo_url'], $ampViewFlag);
        $this->benchmark->mark('article_widget_end');

        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($listingId, $listingType);
        $displayData['aggregateReviewsData'] = $aggregateReviews[$listingId];
        // _p($displayData['aggregateReviewsData']);die;

        //review widget
        $this->benchmark->mark('review_widget_start');
        $displayData['reviewWidget'] = modules::run('mobile_listing5/InstituteMobile/getReviewWidget',$listingId,$listingType,$displayData['courseIdsMapping'] , $displayData['seo_url'], $ampViewFlag, array('getCount' => 1,'aggregateReviewsData' => $displayData['aggregateReviewsData']),$instituteObj,false,$displayData['instituteIsPaid']);
        $this->benchmark->mark('review_widget_end');
        
        $reviewParams = array();
        $reviewParams['totalReviewCount'] = $displayData['reviewWidget']['count'];
        $reviewParams['aggregateRating'] = $displayData['aggregateReviewsData']['aggregateRating']['averageRating'];

        if($displayData['instiuteIsPaid'] && $reviewParams['aggregateRating']<3.5){
            $reviewParams = array();
        }


        $this->benchmark->mark('location_contact_details_widget_start');
        $displayData['contactWidget'] = modules::run('mobile_listing5/InstituteMobile/getLocationsContactWidget',$displayData['instituteObj'], $displayData['currentLocationObj'],$displayData['coursesWidgetData']['locationsMappedToCourse'], $ampViewFlag,$reviewParams);
        $this->benchmark->mark('location_contact_details_widget_end');

        $displayData['schemaContact'] = $displayData['contactWidget']['schema'];
        $displayData['contactWidget'] = $displayData['contactWidget']['contact'];


        $this->benchmark->mark('gallery_widget_start');
        $displayData['galleryWidget'] = modules::run('mobile_listing5/InstituteMobile/getGalleryWidget',$listingId,$listingType,$instituteObj->getPhotos(),$instituteObj->getVideos(),$displayData['currentLocationObj'], $ampViewFlag);
        $this->benchmark->mark('gallery_widget_end');

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

        if($ampViewFlag)
        {
            $displayData['videoExists'] = $displayData['galleryWidget']['videoExists'];
            $displayData['photosExist'] = $displayData['galleryWidget']['photosExist'];
            $displayData['galleryWidget'] = $displayData['galleryWidget']['galleryWidget'];
            //chp interlinking
            $this->benchmark->mark('chp_data_start');
            $chpLibObj = $this->load->library('chp/ChpClient');
            $result = $chpLibObj->getCHPInterLinking('UILP',array('sips'=>$dfpSteamIds,'bips'=>$dfpBaseCourse));
            $result = json_decode($result,true);
            $displayData['chpInterLinking']['links']   = $result['data']; //chp interlinking
            $displayData['chpInterLinking']['pageType']= 'AMP';
            $this->benchmark->mark('chp_data_end');
        }


        //get Admission and Exams Info
        $displayData['admissionInfo'] = $instituteObj->getAdmissionDetails();
        if(!empty($displayData['admissionInfo']))
        {
            $this->load->helper('html');    
            $displayData['admissionInfo'] = getTextFromHtml($displayData['admissionInfo'],500,array('table'));
        } 
        
        $displayData['examList'] = $this->getExamsMappedToUniversity($listingId);
        $displayData['viewAdmissionLink'] = $instituteObj->isAdmissionDetailsAvailable();
        $displayData['admissionPageUrl'] = $displayData['seo_url'].'/admission';

        //AnA widget
        $this->benchmark->mark('ana_widget_start');
        $displayData['anaWidget'] = modules::run('mobile_listing5/InstituteMobile/getAnAWidget',$listingId,$listingType,$displayData['courseIdsMapping'], $ampViewFlag, array('getCount' => 1));
        $this->benchmark->mark('ana_widget_end');

        $displayData['viewCountListingType'] = $listingType == 'university' ? 'university_national' : 'institute';

        //get all courses
        //$courseIdArray = $this->instituteRepo->getCoursesListForInstitutes(array($listingId));
        $courseIdArray[$listingId] = $displayData['courseIdsMapping']['courseIds'];
        if(!empty($courseIdArray)){
            $this->benchmark->mark('prepare_institute_courses_data_start');
            if(empty($displayData['coursesWidgetData']['allCourses']))
                $coursesInfo = $this->courseRepo->findMultiple($courseIdArray[$listingId]);
            else 
                $coursesInfo = $displayData['coursesWidgetData']['allCourses'];
            // $coursesInfo = $this->courseRepo->findMultiple($courseIdArray[$listingId]);
            $instituteCourses = array();
            $clientCoursesStreams = array();
            $displayData['isShowIcpBanner'] = false;
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
                $courseHierarchies = $courseHierarchies['entry_course']->getHierarchies();
                foreach ($courseHierarchies as $key => $value) {
                    $clientCoursesStreams[] = $value['stream_id'];
                }

                $mappedBaseCourseIds = $courseValue->getBaseCourse();

                $displayData['isShowIcpBanner'] = ($mappedBaseCourseIds['entry'] == ICP_BANNER_COURSE) ? true : $displayData['isShowIcpBanner'] ; 

                $instituteCourses[] = array('course_id' => $courseId,'course_name' => htmlentities($courseName)); 

            }

            //sort course alphabetically
             function course_sort($a,$b)
             {
                if ($a['course_name']==$b['course_name']) return 0;
                  return (strtolower($a['course_name'])<strtolower($b['course_name']))?-1:1;
             }

             usort($instituteCourses,"course_sort");
             
            $displayData['instituteCourses'] = $instituteCourses;

            $this->benchmark->mark('prepare_institute_courses_data_end');
        }


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

            $beaconPageName = $listingType.'ListingPage';
            
        $displayData['beaconTrackData'] = array(
                                        'pageIdentifier' => "UILP",
                                        'pageEntityId' => $instituteObj->getId(),
                                        'extraData' => array("childPageIdentifier"=>$beaconPageName,'url'=>get_full_url())
                                    );
        if($ampViewFlag){
            $displayData['beaconTrackData']['extraData']['isAmpFlag'] = $ampViewFlag;
        }
        if($displayData['currentLocationObj']){
            $displayData['beaconTrackData']['extraData']['cityId'] = $displayData['currentLocationObj']->getCityId();
            $displayData['beaconTrackData']['extraData']['stateId'] = $displayData['currentLocationObj']->getStateId();
            $displayData['beaconTrackData']['extraData']['countryId'] = 2;
        }

        $displayData['trackForPages'] = true;
        $displayData['mobilePageName'] = 'm'.ucfirst($listingType).'DetailPage';
        $this->load->config('common/misTrackingKey');
        $this->ampKeys = $this->config->item($displayData['listing_type']);
        $displayData['ampKeys'] = $this->ampKeys;
        
        //prepare meta description
        $seoData = $this->instituteDetailLib->getSeoData($instituteObj, $displayData['anaWidget']['count'], $displayData['reviewWidget']['count'], $displayData['coursesWidgetData']['totalCourseCount'], $displayData['currentLocationObj']);
        $displayData['m_meta_description'] = $seoData['description'];
        $displayData['m_meta_title'] = $seoData['title'];
        $displayData['m_meta_keywords'] = $seoData['keywords'];
    
    
    $this->allCoursesPageLib = $this->load->library('nationalCategoryList/AllCoursesPageLib');
        // load institute detail page view with prepared data
    if($ampViewFlag)
        {
            $ampJsArray = array('carousel','form','lightbox');
                if($displayData['videoExists'])
                {
                    array_push($ampJsArray, 'youtube');
                }
                if($displayData['photosExist'])
                {
                    array_push($ampJsArray, 'image-lightbox');
                }
        if(!empty($displayData['examList']) || !empty($displayData['admissionInfo'])){
            array_push($ampJsArray,'iframe');
        }
                $displayData['ampJsArray'] = $ampJsArray;
        $displayData['mostViewedCollegeList'] = $this->getAllInstituteLayerData($listingId,'colleges',true);
        $displayData['affiliatedcollegeList'] = $this->getAllInstituteLayerData($listingId,'affiliation',true);
       // _P($displayData['mostViewedCollegeList']);die();

        $displayData['gaPageName'] = "AMP ".strtoupper($listingType)." DETAIL PAGE";
        $displayData['gaCommonName'] = '_AMP_'.strtoupper($listingType).'_DETAIL_MOBILE';

        $this->benchmark->mark('Time_to_load_view_start');
        $this->load->view('institute/AMP/instituteDetailPage',$displayData);
        $this->benchmark->mark('Time_to_load_view_end');
    }else{
                $actionType = !empty($_GET['actionType'])?$this->input->get('actionType'):'';
                $fromwhere  = !empty($_GET['fromwhere'])?$this->input->get('fromwhere'):'';
                $pos        = !empty($_GET['pos'])?$this->input->get('pos'):'';
                $courseId   = !empty($_GET['courseId'])?$this->input->get('courseId'):'';

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
                if(!empty($courseId))
                {
                    $displayData['courseId'] = $courseId;
                }

                $queryParams = array();
                $queryParams = $_GET;
                $this->removeUselessQueryParams($queryParams,$displayData);
               
            
            $this->benchmark->mark('Time_to_load_view_start');
        	$this->load->view("mobile_listing5/institute/instituteDetailPage", $displayData);
            $this->benchmark->mark('Time_to_load_view_end');
	}
    }

     function removeUselessQueryParams($queryParams,&$displayData)
    {
            if(array_key_exists('actionType', $queryParams) || array_key_exists('fromwhere', $queryParams) || array_key_exists('pos', $queryParams) || array_key_exists('courseId', $queryParams))
            {
                if(array_key_exists('actionType', $queryParams))
                {
                    unset($queryParams['actionType']);
                }

                if(array_key_exists('courseId', $queryParams))
                {
                    unset($queryParams['courseId']);
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
                    $displayData['replaceStateUrl'] = $displayData['instituteObj']->getURL().'?'.http_build_query($queryParams);
                }
                else{
                    $displayData['replaceStateUrl'] = $displayData['instituteObj']->getURL();
                }    
            }
    }

    private function _prepareInstituteData($instituteObj, $data, $ampViewFlag=false){

        $userId                     = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $data['userIdOfLoginUser']  = $userId;
        $data['seo_url']            = $instituteObj->getURL();
        $data['allCoursePageUrl']   = $data['seo_url']."/courses";
        // $data['m_meta_description'] = $instituteObj->getSeoDescription();
        $data['m_meta_keywords']    = " ";
        
        $currentLocationObj          = $this->instituteDetailLib->getInstituteCurrentLocation($instituteObj);
        $data['currentLocationObj']  = $currentLocationObj;
        $data['instituteLocations']  = $instituteObj->getLocations();
        $data['isMultilocation']     = count($data['instituteLocations']) > 1 ? true : false;

        if($instituteObj->getType() == 'university'){
            // get universities college data
            $data['collegesWidgetData']   = $this->instituteDetailLib->getUniversityPageCollegesWidgetData($instituteObj->getId(),3, $data['courseIdsMapping']);
            $data['collegesWidgetData']['aggregateReviewsData']= array();
            $data['collegesWidgetData']['reviewCount']= array();

            $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
            $ids = array_keys($data['collegesWidgetData']['topInstituteData']);
            $listingType= 'institute';

            // get aggregate reviews of top institutes in universities
            $data['collegesWidgetData']['aggregateReviewsData']=$collegeReviewLib->getAggregateReviewsForListing($ids,$listingType);
            //_p($data['collegesWidgetData']['aggregateReviewsData']);die();
            // get total reviewCount
            $preFetchedCourseIds=$this->instituteDetailLib->getAllCoursesForMultipleInstitutes($ids);
            $this->load->library('ContentRecommendation/ReviewRecommendationLib');
            $data['collegesWidgetData']['reviewCount']=$this->reviewrecommendationlib->getInstituteReviewCounts($ids,$preFetchedCourseIds);
        }

        $data['coursesWidgetData']   = $this->instituteDetailLib->getInstitutePageCourseWidgetData($instituteObj, $currentLocationObj, $data['isMultilocation'], 2, 5, 'mobile');
       
        $data['instiuteIsPaid'] = $data['coursesWidgetData']['instituteHasPaidCourse'];

        // in case of zero(0) courses on this institute show 404
        if(count($data['coursesWidgetData']['allCourses']) < 1){
            show_404();exit(0);
        }
        
        $data['mbaCourseExistsFlag'] = empty($data['coursesWidgetData']['mbaCourseIds']) ? false : true;
        if($data['isMultilocation']){
            $data['seeAllBranchesData'] = $this->instituteDetailLib->formatLocationForMultilocationLayer($data['instituteLocations'], $data['coursesWidgetData']['locationsMappedToCourse']);
        }

        $data['topCardData']   = $this->instituteDetailLib->getInstitutePageTopCardData($instituteObj, $data['coursesWidgetData']['courseViewCount'], $data['coursesWidgetData']['allCourses'], true, $data['currentLocationObj'], $ampViewFlag);

        $data['cutOffData'] = $this->instituteDetailLib->getCollegeCutOffData($instituteObj,$data['topCardData']['instituteParentData']);
        $data['facilities']    = $this->arrangeFacilitiesInOrder($instituteObj->getFacilities());
        $data['highlights']    = $instituteObj->getUSP();
        $data['events']        = $instituteObj->getEvents();
        $data['scholarships']  = $instituteObj->getScholarships();
        $data['mediaPhotos']   = $instituteObj->getPhotos();
        $data['instituteName'] = $instituteObj->getName();

        $instituteObj->addCourses($data['coursesWidgetData']['allCourses']);
        
        $flagshipCourseId = $data['coursesWidgetData']['flaghshipCourse'];

        
        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();

        $courseIds = $data['coursesWidgetData']['coursesOfferedList'];  // get all course ids to pass into getAggregateReviewsForListing as listing id
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $courseReviewData = $collegeReviewLib->getAggregateReviewsForListing($courseIds,'course');
        
        foreach($courseReviewData as $courseId => $review){
                $data['courseWidgetData']['courseReviewRatingData'][$courseId] = $review; // add course review rating data to course widget data for each course id
        }
        
        if($flagshipCourseId){
            $flagshipCourse = $this->courseRepo->find($flagshipCourseId, array('location'));

            $data['course']         = $flagshipCourse;

            $this->_populateCurrentLocation($data,$instituteObj,$flagshipCourse);

            // send institute digest mail if user is logged in
            $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0 && !in_array($this->userStatus[0]['usergroup'],array("enterprise","cms","experts","sums","listingAdmin"))){
                $this->instituteDetailLib->sendInstituteDigestMailForUser($userId, $instituteObj->getId());
            }

            // Check and set the values is displayData array necessary for making the response eg. Institute_viewed
            $this->_checkAndSetDataForAutoResponseForInstitutePage($flagshipCourse, $data);
        }

        if($userId > 0){
            $data['GA_userLevel']         = 'Logged In';
            $data['shortlistedCourseIds'] = Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $userId, 'national');
        }else{
            $data['GA_userLevel'] = 'Non-Logged In';
        }

        return $data;
    }

    public function getArticleWidget($listingId,$listingType, $prefetchedCourseIds = array(),$InstUrl='',$ampViewFlag=false){

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
            $articleArray = $this->articlerecommendationlib->forUniversity($listingId,$stickyArticle,$count,0,'RELEVANCY', $prefetchedCourseIds);
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
                                                'url'=>addingDomainNameToUrl(array('url' => $article->getUrl() , 'domainName' =>SHIKSHA_HOME)),
                                                'blogTitle'=>$article->getTitle(),
                                                'blogImageURL'=>$article->getBlogImageURL(),
                                                'summary'=>$article->getSummary(),
                                                'lastModifiedDate'=>$article->getLastModifiedDate()
                                                );
                     }else{
                         $id = $article->getId();
                         $articleData[$id] = array('id'=>$id,
                            'url'=>addingDomainNameToUrl(array('url' => $article->getUrl() , 'domainName' =>SHIKSHA_HOME)),
                            'blogTitle'=>$article->getTitle(),
                            'blogImageURL'=>$article->getBlogImageURL(),
                            'summary'=>$article->getSummary(),
                            'lastModifiedDate'=>$article->getLastModifiedDate()
                            );
                         }
                     }

                     $result = array();
                     foreach($articleArray['topContent'] as $val){
                        $result[] = $articleData[$val];
                     }
		    if(($listingType == 'institute' || $listingType == 'university') && !empty($InstUrl) && $ampViewFlag){
             	    	$displayData['all_article_url'] =  $InstUrl.'/articles';
            	    }else{
			$displayData['all_article_url'] =  $currentUrl.'/articles';
           	    }
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
		    if($ampViewFlag){
                    	echo $this->load->view('mobile_listing5/institute/AMP/Widgets/articles',$displayData); 
		    }else{
			echo $this->load->view('mobile_listing5/institute/widgets/articles',$displayData);
		    }
                }
        }else{
            return;
        }
        
        
    }
    /**
    * Function is used for get facility widget on listing detail pages
    * @author : Nithish Reddy
    * @param [type:object] $facilitiesObj [institute/university facilities obj]
    */
	function arrangeFacilitiesInOrder($facilitiesObj)
    {
        $facilitiesData = $this->instituteDetailLib->prepareFacilitiesInformation($facilitiesObj);

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

        $listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';

        $viewFacilitiesList = !empty($_POST['viewDetailSort'])?$this->input->post('viewDetailSort'):'';

        $viewFacilitiesListArray = explode(',', $viewFacilitiesList);


        if(empty($listingId) || count($viewFacilitiesListArray) == 0)
        {
            return ;
        }


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
        echo $this->load->view('mobile_listing5/institute/viewFacilityDetailsLayer',$displayData);
    }

    function facilityViewDetailsLayerAMP($listingId = 0, $viewFacilitiesListArray = array())
    {
        $this->_init();

        //$viewFacilitiesListArray = explode(',', $viewFacilitiesList);


        if(empty($listingId) || count($viewFacilitiesListArray) == 0)
        {
            return ;
        }


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
        //_p($displayData);
        echo $this->load->view('mobile_listing5/institute/AMP/Widgets/viewFacilityDetailsLayer',$displayData);
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

    function getGalleryWidget($listingId,$listingType,$photosObj,$videosObj,$currentLocationObj,$ampViewFlag=false)
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
            $displayData['media'] = $media;
            $displayData['listing_id'] = $listingId;
            $displayData['listing_type'] = $listingType;
            $displayData['currentCityId'] = $currentLocationObj->getCityId();
            $displayData['currentLocalityId'] = $currentLocationObj->getLocalityId();

            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }
            //_p($displayData);die;
            if($ampViewFlag)
            {
				 $videoExists = (!empty($media['videos']['Videos']) && count($media['videos']['Videos']) > 0 ) ? true : false;
                 $photosExist = (!empty($media['photos']['order']) && count($media['photos']['order']) > 0 ) ? true : false;

                 $displayData['photosExist'] = $photosExist;

                 $galleryWidget = $this->load->view('mobile_listing5/institute/AMP/Widgets/galleryWidget',$displayData,true);
                 return array('galleryWidget' => $galleryWidget,'videoExists' => $videoExists,'photosExist' => $photosExist );
            }
            else
            {
                echo $this->load->view('mobile_listing5/institute/widgets/GalleryWidget',$displayData);
            }
            
        }
        else
            return;
        
    }

    /**
    *   function is used for open Gallery View List Layer for listing Id
    *   @author : Nithish Reddy
    */

    function getGalleryViewListLayer()
    {
        $listingId   = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
        $listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'institute';
        $cityId      = !empty($_POST['cityId'])?$this->input->post('cityId'):'';
        $localityId  = !empty($_POST['localityId'])?$this->input->post('localityId'):'';

        if(empty($listingId))
            return;
        
        $this->_init();
        $displayData = array();

        if($listingType == 'course'){
            $listingObj = $this->courseRepo->find($listingId,array('location','media'));

            $this->load->library('nationalCourse/CourseDetailLib');
            $this->courseDetailLib = new CourseDetailLib; 
            $currentLocationObj = $this->courseDetailLib->getCourseCurrentLocation($listingObj,array($cityId),array($localityId));                        
            $displayData['listingGallerytext'] = $listingObj->getName();
        }else{
            $listingObj = $this->instituteRepo->find($listingId,array('location','media'));//,array('media','location')
            $institute_name                    = $listingObj->getName();
            $abbreviation                      = $listingObj->getAbbreviation();
            $displayData['listingGallerytext'] = !empty($abbreviation) ?$abbreviation : $institute_name;                            
            $currentLocationObj = $this->instituteDetailLib->getInstituteCurrentLocation($listingObj,$cityId,$localityId);
        }
        $media = $this->instituteDetailLib->prepareGalleryData($listingObj->getPhotos(),$listingObj->getVideos(),$currentLocationObj);
        if((!empty($media['photos']['order']) && count($media['photos']['order']) > 0) || !empty($media['videos']['Videos']) && count($media['videos']['Videos']) > 0)
        {
            $displayData['media']             = $media;
            $displayData['listing_id']        = $listingObj->getId();
            $displayData['listing_type']      = $listingObj->getType();           
            $displayData['currentCityId']     = $currentLocationObj->getCityId();
            $displayData['currentLocalityId'] = $currentLocationObj->getLocalityId();

            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }

            echo $this->load->view('mobile_listing5/institute/GalleryViewListLayer',$displayData);    
        }
        else
            echo '';
    }

    /**
    *   function is used for open Gallery Detail Layer for listing Id
    *   @author : Nithish Reddy
    */

    function getGalleryDetailLayer()
    {
        $listingId   = !empty($_POST['listingId'])?$this->input->post('listingId'):'';
        $listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'institute';
        $mediaId     = !empty($_POST['mediaId'])?$this->input->post('mediaId'):'';
        $tagName     = !empty($_POST['tagName'])?$this->input->post('tagName'):'';
        $cityId      = !empty($_POST['cityId'])?$this->input->post('cityId'):'';
        $localityId  = !empty($_POST['localityId'])?$this->input->post('localityId'):'';        

        if(empty($listingId) || empty($mediaId) || empty($tagName))
            return;
        
        $this->_init();
        $displayData = array();

        if($listingType == 'course'){
            $listingObj = $this->courseRepo->find($listingId,array('location','media'));
            $this->load->library('nationalCourse/CourseDetailLib');
            $this->courseDetailLib = new CourseDetailLib; 
            $currentLocationObj = $this->courseDetailLib->getCourseCurrentLocation($listingObj,array($cityId),array($localityId));                        
        }else{
            $listingObj = $this->instituteRepo->find($listingId,array('location','media'));//,array('media','location')
            $currentLocationObj = $this->instituteDetailLib->getInstituteCurrentLocation($listingObj,$cityId,$localityId);
        }

        $media = $this->instituteDetailLib->prepareGalleryData($listingObj->getPhotos(),$listingObj->getVideos(),$currentLocationObj);
        if((!empty($media['photos']['order']) && count($media['photos']['order']) > 0) || !empty($media['videos']['Videos']) && count($media['videos']['Videos']) > 0)
        {
            $displayData['media']          = $media;
            $displayData['institute_name'] = $listingObj->getName();
            $displayData['listing_id']     = $listingId;
            $displayData['listing_type']   = $listingObj->getType();
            $displayData['tagName']        = $tagName;
            $displayData['mediaId']        = $mediaId;
            if($listingType != 'course')
            $displayData['abbreviation']   = $listingObj->getAbbreviation();
            else
            $displayData['abbreviation']   = '';

            $displayData['currentCityId'] = $cityId;
            $displayData['currentLocalityId'] = $localityId;

            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }

            echo $this->load->view('mobile_listing5/institute/GalleryDetailLayer',$displayData);    
        }
        else
            echo '';
    }

	public function getLocationsContactWidget($instituteObj,$currentLocationObj,$locationsMappedToCourse,$ampViewFlag=false, $customParams){
        $this->_init();

        $locationObj        = $instituteObj->getLocations();
        $cityName           = $currentLocationObj->getCityName();
        $localityName       = $currentLocationObj->getLocalityName();
        $locationContactObj = $currentLocationObj->getContactDetail();
        $stateName          = $currentLocationObj->getStateName(); 

        if(!empty($locationContactObj)){
            $contactDetails = array(
                    'locality_name'            =>$localityName,
                    'city_name'                =>$cityName,
                    'state_name'               => $stateName,
                    'generic_contact_number'   =>$locationContactObj->getGenericContactNumber(),
                    'generic_email'            =>$locationContactObj->getGenericEmail(),
                    'address'                  =>$locationContactObj->getAddress(),
                    'admission_contact_number' =>$locationContactObj->getAdmissionContactNumber(),
                    'admission_email'          =>$locationContactObj->getAdmissionEmail(),
                    'latitude'                 =>$locationContactObj->getLatitude(),
                    'longitude'                =>$locationContactObj->getLongitude(),  
                    );

            $website_url                    = $locationContactObj->getWebsiteUrl();
            $contactDetails['website_url']  = prep_url($website_url);
            
            $displayData['contactDetails']      = $contactDetails;
            $displayData['listing_type']        = $instituteObj->getType();
            $displayData['listing_location_id'] = $currentLocationObj->getLocationId();
            $displayData['contact_listing_id']  = $instituteObj->getId();

            $displayData['state_name']    = $stateName;
            $displayData['city_name']     = $cityName;
            $displayData['locality_name'] = $localityName;
            if($displayData['listing_type'] == 'course')
            {
                $displayData['showAllBranches'] = (count($locationObj)>1)?TRUE:FALSE;
                $actualLocationId               = $locationContactObj->getActualListingLocationId();
                $displayData['instituteName_contact'] = $instituteObj->getInstituteName();
                $displayData['affiliatedUniversityName'] = $customParams['affiliatedUniversityName'];
                $displayData['instituteNameWithLocation'] = $customParams['instituteNameWithLocation'];

                if(!empty($actualLocationId))
                {
                    $result = $this->institutedetailmodel->getListingLocationInfo($actualLocationId);   
                    $displayData['contactDetails']['locality_name'] = $result['locality_name'];
                    $displayData['contactDetails']['city_name']     = $result['city_name'];
                    $displayData['contactDetails']['state_name']    = $result['state_name'];
                }
                $displayData['listing_seo_url'] = $instituteObj->getURL();
            }
            else
            {
                $displayData['showAllBranches'] = (count($locationsMappedToCourse)>1)?TRUE:FALSE;
            }

            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }

            $displayData['name'] = $instituteObj->getName();
            $displayData['listingId'] = $instituteObj->getId();
            $displayData['userId'] = $userId;
            $return = array();

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

            $return['schema'] = $this->load->view('nationalInstitute/InstitutePage/SchemaFile',$displayData,true); 
            if($ampViewFlag){
                $return['contact'] = $this->load->view('course/AMP/Widgets/contactWidget',$displayData,true);
            }else{
                $return['contact'] = $this->load->view('mobile_listing5/institute/widgets/contactDetails',$displayData,true);
            }        
            
            return $return;
        }  

    }


    public function getRecommendedListingWidget($listingId,$listingType,$widgetType,$prefetchedCourseIds=array(),$AmpPageFlag){
        $this->_init();
        $this->load->library('recommendation/alsoviewed');
        $prefetchedCourseIdMapping = array( $listingId => $prefetchedCourseIds);


        switch ($listingType) {
            case 'course':
                if($widgetType == 'similar'){
                    $this->load->library('recommendation/similar');
                    $results = $this->similar->getSimilarCourses(array($listingId), '16');
                    $widgetHeading = 'Other Similar Courses';
                    $widgetTrackingKeyId = 1124;
                }else{
                    $this->load->library('recommendation/alsoviewed');
                    $results = $this->alsoviewed->getAlsoViewedCourses(array($listingId), '16');            
                    $widgetHeading = 'Students who viewed this course also viewed';
                    $widgetTrackingKeyId = 1123;
                }
                $pageType = 'courseDetailPage';
                break;
            case 'institute':
                if($widgetType == 'similar'){
                    $results = $this->_getGroupRecommendationData($listingId);
                    if(!empty($results)){
                        $widgetHeading = $results['title'];
                        $results = $results['colleges'];
                    }
                    $widgetTrackingKeyId = 1126;
                }else{
                    $results = $this->alsoviewed->getAlsoViewedInstitutes(array($listingId),'16');
                    $widgetTrackingKeyId = 1125;
                }
                $pageType = 'instituteDetailPage';
                break;
            case 'university':
                if($widgetType == 'similar'){
                    $results = $this->_getGroupRecommendationData($listingId);
                    if(!empty($results)){
                        $widgetHeading = $results['title'];
                        $results = $results['colleges'];
                    }
                    $widgetTrackingKeyId = 1128;
                }else{
                    $results = $this->alsoviewed->getAlsoViewedUniversities(array($listingId),'16', array(), $prefetchedCourseIdMapping);
                    $widgetHeading      = 'Students who viewed this university also viewed the following';
                    $widgetTrackingKeyId = 1127;
                }
                $pageType = 'universityDetailPage';
                break;
        }
        
        //setting tracking key ids
        $displayData['widgetTrackingKeyId'] = $widgetTrackingKeyId;
        if($listingType == 'course'){
            $GA_Tap_On_Reco = ($widgetType == 'similar') ? 'SIMILAR_RECO_LISTING_COURSEDETAIL_MOBILE' : 'ALSO_VIEWED_RECO_LISTING_COURSEDETAIL_MOBILE';
        }
        else{
            $GA_Tap_On_Reco = ($widgetType == 'similar') ? 'SIMILAR_RECO_LISTING' : 'ALSO_VIEWED_RECO_LISTING';
        }

        $displayData['GA_Tap_On_Reco'] = $GA_Tap_On_Reco;
        $displayData['GA_deb_attr'] = 'DBROCHURE_STICKY';
        $displayData['pageType'] = $pageType;

        if(empty($widgetHeading)){
            $widgetHeading = ($widgetType == 'similar') ? 'Recommended Colleges' : "Students who viewed this ".$listingType." also viewed the following";
        }
        
        if(!empty($results)){
            if($listingType == 'course'){
                $courseList = array();
                $instituteList = array();
                foreach ($results as $key => $data) {
                    $courseList[] = $data['courseId'];
                }

                $courseObj = $this->courseRepo->findMultiple($courseList, '', true);
                foreach($courseObj as $courseId =>$course) {
                    if($course->getId()){
                        $id                                      = $course->getId();
                        $instituteId                             = $course->getInstituteId();
                        $courseInfo[$instituteId]['course_name'] = $course->getName();
                        $courseInfo[$instituteId]['course_url']  = $course->getURL();
                        $courseInfo[$instituteId]['course_id']   = $course->getId();
                        $instituteList[]                         = $instituteId;
                    }
                }

                if(is_array($instituteList) && !empty($instituteList)){
                     $instituteObj = $this->instituteRepo->findMultiple($instituteList,array('basic','media'),true);
                }
                   
            }else{
                $instituteObj = $this->instituteRepo->findMultiple($results,array('basic','media'),true);                
            }

            foreach($instituteObj as $instituteId =>$institute){
                $id = $institute->getId();
                if(!empty($id)){
                    $result[] = $this->prepareRecommendedCollegeData($institute, $courseInfo);
                }
            }

            $displayData['RecommendedListingData'] = $result;
            $displayData['widgetType']             = $widgetType;
            $displayData['listing_type']           = $listingType;
	        $displayData['widgetHeading']          = $widgetHeading;

            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($userId > 0){
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else{
                  $displayData['GA_userLevel'] = 'Non-Logged In';
            }
            
            if($AmpPageFlag){
                echo $this->load->view('course/AMP/Widgets/recommendationWidget',$displayData);
            }else{
                echo $this->load->view('mobile_listing5/institute/widgets/recommendation',$displayData);
            }
            
        }
        
    }

    function prepareRecommendedCollegeData($instituteObj, $courseDetails){
        $mainLocationObj = $instituteObj->getMainLocation();
        if(!empty($mainLocationObj)){
            $main_location = $instituteObj->getMainLocation()->getCityName();
        }

        $headerImage = $instituteObj->getHeaderImage();
        // if header image exists get its variant otherwise use default image
        if($headerImage && $headerImage->getUrl()){
            $imageLink = $headerImage->getUrl();
            $imageUrl = getImageVariant($imageLink,6);
        }
        else{
            $imageUrl = MEDIAHOSTURL."/public/mobile5/images/recommender_dummy.png";
        }
        $result = array(
                'institute_name'                 =>$instituteObj->getName(),
                'institute_id'                   =>$instituteObj->getId(),
                'institute_url'              =>$instituteObj->getURL(),
                'image_url'            =>$imageUrl,
                'main_location'        =>$main_location,
                'establish_year'       =>$instituteObj->getEstablishedYear(),
                'is_autonomous'        =>$instituteObj->isAutonomous(),
                'isNationalImportance' =>$instituteObj->isNationalImportance(),                
                'course_name'          =>   $courseDetails[$instituteObj->getId()]['course_name'],
                'course_url'           =>   $courseDetails[$instituteObj->getId()]['course_url'],
                'course_id'           =>   $courseDetails[$instituteObj->getId()]['course_id'],
                'listingType'          =>   $instituteObj->getType() 
                );

        return $result;
    }

    public function populateAnAProposition($listingId,$listingType,$ampViewFlag=false){
            $this->_init();

            $listingId = !empty($_POST['listingId']) ? $this->input->post('listingId') : $listingId ;
            $listingType = !empty($_POST['listingType']) ? $this->input->post('listingType') : $listingType ;
	    
	    if(!is_numeric($listingId)){
                return;
            }

	    $showCampusReps = isset($_POST['showCampusReps'])?$this->input->post('showCampusReps'):false;

            $this->load->model('CA/cadiscussionmodel');
            $this->CADiscussionModel  = new CADiscussionModel();
            if((isset($listingId) && $listingId>0) && ($listingType == 'institute' || $listingType == 'university')){
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
            $displayData['listing_id'] = $listingId;

            if($listingType == 'course')
            {
                $courseObj = $this->courseRepo->find($listingId, array('basic'));
                $displayData['listing_parent_id'] = $courseObj->getInstituteId();
            }

            if(isset($courseIdArray['courseIds']) && count($courseIdArray['courseIds'])>0){
                $campusRepData = $this->cadiscussionmodel->getCampusRepInfoForCourse($courseIdArray['courseIds'], $listingType ,$instituteId, 50, true,$getCaAnsCount = false);
                if(is_array($campusRepData) && isset($campusRepData['caInfo']) && count($campusRepData) > 0){  //Campus Rep found
                    $displayData['campusReps'] = array();
		    $usersAdded = array();
                    foreach ($campusRepData['caInfo'] as $courseReps){
                        foreach ($courseReps as $rep){
                                if(!in_array($rep['userId'],$usersAdded)){
                                        $displayData['campusReps'][] = $rep;
                                        array_push($usersAdded,$rep['userId']);
                                }
                        }
                    }
            $displayData['showCampusReps'] = $showCampusReps;
		    $displayData['requestTrackingKeyId'] = (!empty($_POST['trackingKeyId'])) ? $_POST['trackingKeyId'] : 0;

                if($ampViewFlag)
                {
                    $this->load->view('mobile_listing5/institute/AMP/Widgets/CampusAmbassadorWidget',$displayData);
                }
                else
                {
                    $this->load->view('mobile_listing5/institute/widgets/CampusAmbassadorWidget',$displayData);
                }
                    return;
                }
            }
	    if(!$showCampusReps && !$ampViewFlag){
            	$this->load->view('mobile_listing5/institute/widgets/expertGuidance',$displayData);
	    }
        else
        {
            $this->load->view('mobile_listing5/institute/AMP/Widgets/askQuestionWidget',$displayData);
        }
     }

     public function getReviewWidget($listingId,$listingType,$prefetchedCourseIds=array(),$InstUrl='',$ampViewFlag=false, $customParams = array(),$listingObj = NULL, $allCoursePage=false,$isPaid = false){
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
            if($listingType == 'course' && !empty($InstUrl)){
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

            // _p($displayData);die;

	        if($ampViewFlag){
                if($customParams['getCount']) {
                	$reviewWidgetHtml = $this->load->view('mobile_listing5/course/AMP/Widgets/aggregateReviewWidget', $displayData, true);
                    return array('html' => $reviewWidgetHtml, 'count' => $displayData['totalReviews']);
                }
                else {
                    echo $this->load->view('mobile_listing5/course/AMP/Widgets/aggregateReviewWidget', $displayData);
                }
            }else{
                if($customParams['getCount']) {
            	   $reviewWidgetHtml = $this->load->view('mobile_listing5/institute/widgets/aggregateReviewWidget',$displayData, true);
                   return array('html' => $reviewWidgetHtml, 'count' => $displayData['totalReviews']);
                }
                else {
                   echo $this->load->view('mobile_listing5/institute/widgets/aggregateReviewWidget',$displayData); 
                }
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
            $result['title'] = "Other ".$recommentationGroup['group_name']." ".$recommentationGroup['supporting_text'];
        }
        
        return $result;
    }

    public function getAnAWidget($listingId,$listingType,$prefetchedCourseIds=array(),$ampViewFlag=false, $customParams = array()) {

        $this->_init();

        //get Recommended QNA for Listing
        $count = '2';

        //Fetch the Recommended Questions from the API
        $this->load->library('ContentRecommendation/AnARecommendationLib');
        $seoListingId   = $listingId;
        $seoListingType = $listingType;

        if($listingType == 'institute' && $listingId > 0){
            $questionIds = $this->anarecommendationlib->forInstitute($listingId, array(), $count, 0,'question');
        } else if($listingType == 'course' && $listingId > 0){
            $courseObj = $this->courseRepo->find($listingId);
            $courseId = $listingId;
            $seoListingId = $courseObj->getInstituteId();
            $seoListingType = $courseObj->getInstituteType();
            $questionIds = $this->anarecommendationlib->forCourse($listingId, array(), $count, 0,'RELEVANCY');
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

            $this->load->model("messageBoard/anamodel");
            $questionsDetail = $this->anamodel->getQuestionsDetails($questionIds, $userId,$ampViewFlag, false);

            if(is_array($questionsDetail)){
                //Now, load the view file to create UI
                $displayData['totalNumber'] = $totalNumber;
                $displayData['questionsDetail'] = $questionsDetail;
                $displayData['loggedInUser'] = $userId;
            }
        }
            if($userId > 0)
            {
                $displayData['GA_userLevel'] = 'Logged In';
            }
            else
            {
                $displayData['GA_userLevel'] = 'Non-Logged In';
            }
            $displayData['listing_type'] = $listingType;

        if(($seoListingType == 'institute' || $seoListingType == 'course' || $seoListingType == 'university') && $seoListingId > 0 ) {
            $displayData['allQuestionURL'] = getSeoUrl($seoListingId,'all_content_pages','',array('typeOfPage'=>'questions','typeOfListing'=>$seoListingType, 'courses' => array($courseId)));
	        if($ampViewFlag){
                if($customParams['getCount']) {
                    $anaWidgetHtml = $this->load->view('mobile_listing5/course/AMP/Widgets/QnAWidget', $displayData, true);
                    return array('html' => $anaWidgetHtml, 'count' => $totalNumber,'allQuestionURL'=>$displayData['allQuestionURL']);
                }
                else {
                    echo $this->load->view('mobile_listing5/course/AMP/Widgets/QnAWidget', $displayData);
                }
            }else{
                if($customParams['getCount']) {
                    $anaWidgetHtml = $this->load->view('mobile_listing5/institute/widgets/qna', $displayData, true);
                    return array('html' => $anaWidgetHtml, 'count' => $totalNumber);
                }
                else {
                    echo $this->load->view('mobile_listing5/institute/widgets/qna', $displayData);
                }
            }
            return;
        }
    }
    
    /**
    * @param: $listingId : university id 
    * @return : list of exams directly mapped to university listing with sorted order
    */
    function getExamsMappedToUniversity($listingId)
    {
        $this->benchmark->mark('get_university_exams_start');
        $this->_init();
        $examList = $this->institutedetailmodel->getExamsMappedToUniversity($listingId);

        function examSort($a,$b)
                 {
                    if ($a['name']==$b['name']) return 0;
                      return (strtolower($a['name'])<strtolower($b['name']))?-1:1;
                 }

        usort($examList,"examSort");
        $this->benchmark->mark('get_university_exams_end');
        return $examList;

    }

    function getAllInstituteLayerData($universityId,$listType, $ampViewFlag=false){
        $this->benchmark->mark('get_institute_layer_data'.$listType.'_start');
        $this->_init();
        $universityId = isset($_POST['universityId'])?$this->input->post("universityId"):$universityId;
        $listType = isset($_POST['listType'])?$this->input->post("listType"):$listType;

        $displayData = array();

        if($universityId){

            if($listType == 'colleges'){
                $displayData['heading'] = "Colleges / Departments";
                $institutesData = $this->instituteDetailLib->getAllInstitutesOfUniversity($universityId);
            }else{
                $displayData['heading'] = "Affiliated Colleges";
                $institutesData = $this->instituteDetailLib->getInstitutesAffiliatedToUniversity($universityId);
            }
          //  _p($institutesData);die();

            if(empty($institutesData)){
                return "";exit(0);
            }else{
                foreach($institutesData['institutes'] as $key=>$obj){
                    $instituteName = $obj->getShortName();
                    $listingId=$obj->getId();
                    $instituteName = $instituteName ? $instituteName : $obj->getName();
                    $instituteList[] = array('id'=>$obj->getURL(),'name'=>$instituteName,'listingId'=>$listingId);

                }
            }
        }
        $this->benchmark->mark('get_institute_layer_data'.$listType.'_end');
        
        
        $displayData['institutesData'] = $instituteList;
        $ids = $institutesData['instituteIds'];
        $listingType = 'institute';
        $displayData['aggregateReviewsData'] = array();
        $displayData['reviewCount'] = array();

        // get aggregate reviews of all institutes in universities
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $displayData['aggregateReviewsData']=$collegeReviewLib->getAggregateReviewsForListing($ids,$listingType);

        // get total reviewCount fo all institutes of university
        $preFetchedCourseIds=$this->instituteDetailLib->getAllCoursesForMultipleInstitutes($ids);
        $this->load->library('ContentRecommendation/ReviewRecommendationLib');
        $displayData['reviewCount']=$this->reviewrecommendationlib->getInstituteReviewCounts($ids,$preFetchedCourseIds);

	if($ampViewFlag){
		return $displayData;
	}else{
	        echo json_encode($displayData);
	}
    }

    public function getMultiLocationLayer($listingId, $listingType, $instituteObj, $instituteCurrentLocation = '',$prefetchedCourseIds = array(), $ampViewFlag=false){
        $this->_init();

        $displayData = array();

        // get all courses
        if(empty($prefetchedCourseIds))
            $courseList = $this->instituteDetailLib->getInstituteCourseIds($listingId, $listingType);
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

        $eligibleLocationCourses = $this->institutedetailsmodel->getCoursesHavingLocations($courseList, $instituteLocationId);
        $locationsMappedToCourse = $this->institutedetailsmodel->getUniqueCoursesLocations($courseList);

        // $courseWiseLocations = $this->institutedetailsmodel->getCoursesLocations($courseList);
        // foreach ($courseWiseLocations as $courseId => $locationIds) {
        //     if(in_array($instituteLocationId, $locationIds)){
        //         $eligibleLocationCourses[] = $courseId;
        //     }
        //     $locationsMappedToCourse = array_merge($locationsMappedToCourse, $locationIds);
        // }

        $locationsMappedToCourse = array_values(array_unique($locationsMappedToCourse));

        $displayData['instituteObj'] = $instituteObj;
        $displayData['heading'] = $instituteObj->getName();
        $displayData['masterUrl'] = $instituteObj->getUrl();
        $displayData['seeAllBranchesData'] = $this->instituteDetailLib->formatLocationForMultilocationLayer($instituteLocations, $locationsMappedToCourse);

        // load the view
        if($ampViewFlag)
        {
            $this->load->view("institute/AMP/Widgets/locationLayer", $displayData);
        }else{
            $this->load->view("institute/widgets/locationLayer", $displayData);
        }
    }

    public function getMultiLocationLayerForCourse($courseObj, $currentLocation = '',$ampViewFlag=false){
        $this->_init();

        $displayData = array();

        $locations = $courseObj->getLocations();
        
        $instituteLocationId     = $currentLocation->getLocationId();
        $eligibleLocationCourses = array();


        $locationsMappedToCourse = array();
        foreach ($locations as $locObj) {
            $locationsMappedToCourse[] = $locObj->getLocationId();
        }
        

        $displayData['instituteObj'] = $courseObj;
        $displayData['heading'] = $courseObj->getName();
        $displayData['masterUrl'] = $courseObj->getUrl();
        $displayData['seeAllBranchesData'] = $this->instituteDetailLib->formatLocationForMultilocationLayer($locations, $locationsMappedToCourse);

        // load the view
        if($ampViewFlag)
        {
            $this->load->view("institute/AMP/Widgets/locationLayer", $displayData);
        }
        else
        {
            $this->load->view("institute/widgets/locationLayer", $displayData);    
        }
        
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

    private function _checkAndSetDataForAutoResponseForInstitutePage($course, &$displayData) {
        $validateuser = $displayData['validateuser'];
        
        $this->load->model('qnAmodel');
        $this->qnamodel = new QnAModel();
        $validResponseUser = 0;
        if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && (!($this->qnamodel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != ""))
        {
            $validResponseUser = 1;
            $displayData['validResponseUser'] = $validResponseUser;
        }
        $displayData['viewedResponseAction'] = 'MOB_Institute_Viewed';

        $displayData['instituteViewedTrackingPageKeyId'] = 1102;
    }
    
    function getCountOfUgcWidgets($listingId,$listingType)
    {
        $instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
        
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
            $preFetchedCourseIds = $instituteDetailLib->getInstituteCourseIds($listingId,'university');    
        }
        else
        {
            $allInstitutesCourses = $instituteDetailLib->getAllCoursesForInstitutes($listingId);
        }
        $data = array();

        if(in_array('qna', $content))
        {
            $data['qna'] = $instituteDetailLib->getCountofQuestionsForListings($listingId,$listingType,$prefetchedCourseIds,$allInstitutesCourses);    
        }
        if(in_array('reviews', $content))
        {

            $data['reviews'] =  $instituteDetailLib->getCountOfReviewsForListings($listingId,$listingType,$prefetchedCourseIds,$allInstitutesCourses);    
        }
        if(in_array('articles', $content))
        {
            $data['articles'] =  $instituteDetailLib->getArticleCoutForListing($listingId,$listingType,$prefetchedCourseIds,$allInstitutesCourses);    
        }
        
        echo json_encode($data);die;
            
    }   

    /**************AMP Page Institute Detail Page*************************************
     * @param  [type]     $listingId   [institute/university id]
     * @param  [type]     $listingType [institute/university]
    */
    function ampInstituteDetailPage($listingId,$listingType)
    {
	   Modules::run('muser5/UserActivityAMP/validateBrowser', $listingType, $listingId);

       global $removeInsAmpPages;

       if(0 && in_array($listingId, $removeInsAmpPages)) {

            
           $this->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $instituteRepo = $instituteBuilder->getInstituteRepository(); 

            $instituteObj = $instituteRepo->find($listingId,array('basic'));
            if(!empty($instituteObj) && !empty($instituteObj->getUrl())) {
                header("Location: ".$instituteObj->getUrl(),TRUE,301);
            }else{
                show_404();
            }
            exit;
       }
	   $this->getInstituteDetailPage($listingId,$listingType,true);
    }

    /*********************************************************************************/

    function getAdmissionInfo($listingId){
    if($listingId > 0){
    	$this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository(); 
        $instituteObj = $this->instituteRepo->find($listingId,array('basic'));
        $displayData['admissionInfo'] = $instituteObj->getAdmissionDetails();
        if(!empty($displayData['admissionInfo']))
        {
            $this->load->helper('html');    
            $displayData['admissionInfo'] = getTextFromHtml($displayData['admissionInfo'],500,array('table'));
        }    
        $displayData['admissionPageUrl'] = $instituteObj->getURL().'/admission';
    	$this->load->view('mobile_listing5/institute/AMP/Widgets/admissionWidgetiframe',$displayData);
        }
    }
}

?>
