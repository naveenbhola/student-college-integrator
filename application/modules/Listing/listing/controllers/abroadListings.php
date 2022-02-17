<?php
class abroadListings extends MX_Controller
{
    private $courses,$userRmcCourses=NULL,$LDBDetails = NULL;
    function _init(& $displayData,$typeId,$type = 'course')
    {
        $this->load->builder('ListingBuilder','listing');
        $this->load->library('listing/AbroadListingCommonLib');
        $this->load->model('listing/abroadlistingmodel');

        $this->abroadListingCommonLib = new AbroadListingCommonLib();
        $this->abroadListingModel = new abroadlistingmodel();

        $this->load->library('responseAbroad/ResponseAbroadLib');
        $this->responseAbroadLib = new ResponseAbroadLib();

        // define("PAGETRACK_BEACON_FLAG",false);
        $displayData['listingType'] = $type;
        $displayData['listingTypeId'] = $typeId;
        $displayData['trackForPages'] = true;

        $this->config->load('studyAbroadListingConfig');
    }
    function _initLoggedInUserData( & $displayData)
    {
        $displayData['validateuser'] = $this->checkUserValidation();
        if($displayData['validateuser'] !== 'false') {
            $this->load->model('user/usermodel');
            $usermodel = new usermodel;

            $userId = $displayData['validateuser'][0]['userid'];
            $user = $usermodel->getUserById($userId);

            if (!is_object($user)) {
                $displayData['loggedInUserData'] = false;
            }
            else {
                $name = $user->getFirstName().' '.$user->getLastName();
                $email = $user->getEmail();
                $userFlags = $user->getFlags();
                $isLoggedInLDBUser = $userFlags->getIsLDBUser();
                $displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);

                $pref = $user->getPreference();
                $loc = $user->getLocationPreferences();
                $isLocation = count($loc);
                if(is_object($pref)){
                    $desiredCourse = $pref->getDesiredCourse();
                }else{
                    $desiredCourse = null;
                }
                $displayData['loggedInUserData']['desiredCourse'] = $desiredCourse;
                $displayData['loggedInUserData']['isLocation'] = $isLocation;
            }
        }
        else {
            $displayData['loggedInUserData'] = false;
        }
    }

    /**
     * Method to initialize the common functionalities for Abroad Course Listing Detail Page
     */
    private function _initCourse(&$displayData,$courseId)
    {
        $listingBuilder 						= new ListingBuilder;
        $this->abroadCourseRepository 			= $listingBuilder->getAbroadCourseRepository();
        $this->abroadInstituteRepository 		= $listingBuilder->getAbroadInstituteRepository();
        $this->abroadUniversityRepository 		= $listingBuilder->getUniversityRepository();
        $this->rmcPostingLib 					= $this->load->library('shikshaApplyCRM/rmcPostingLib');
        $courseData 			= $this->abroadCourseRepository->find($courseId);

        if(!is_object($courseData) || $courseData instanceof Course || $courseData->getId() == "")
        {
            show_404_abroad();
        }

        //its a valid course object
        $displayData['courseObj'] = $courseData;
        $this->_validateUrl($displayData['courseObj']->getUrl());
        if($courseData->getInstId())
        {
            $departmentData = $this->abroadInstituteRepository->find($courseData->getInstId());
            $displayData['departmentObj'] 	= $departmentData;
        }
        if($courseData->getUniversityId())
        {
            $universityData = $this->abroadUniversityRepository->find($courseData->getUniversityId());
            $displayData['universityObj'] 	= $universityData;
        }
        //prepare data for university contact info and details
        $universityContactDetails = $this->abroadListingCommonLib->getUniversityContactInfo(array($displayData['universityObj']));
        $displayData = array_merge($displayData, $universityContactDetails[$displayData['universityObj']->getId()]);
        //_p($displayData);die;
        $shrtListDataArray = $this->fetchIfUserHasShortListedCourses();
        $displayData["isShortListedCoursePage"] = in_array($courseData->getId(), $shrtListDataArray['courseIds']) ? true : false;

        $dataArray = $this->abroadListingCommonLib->getCategoryOfAbroadCourse($courseId);
        $displayData["courseCategoryId"] 	= $dataArray['categoryId'];
        $displayData["courseSubCategoryId"] = $dataArray['subcategoryId'];

        $seoRelatedRequireData = array('courseName'		=> $displayData['courseObj']->getName(),
            'universityName'	=> $displayData['courseObj']->getUniversityName(),
            'courseHighlights'	=> $displayData['courseObj']->getCourseDescription()
        );

        $displayData['seoDescription'] = $this->getSeoDescription($seoRelatedRequireData,'course');
    }

    private function _initUniversity(&$displayData, $universityId)
    {
        $listingBuilder 			= new ListingBuilder;
        //$this->abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
        //$this->abroadInstituteRepository 	= $listingBuilder->getAbroadInstituteRepository();
        $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
        $universityData 			= $this->abroadUniversityRepository->find($universityId);
        $university_id 			= $universityData->getId();
        if(empty($university_id)) {
            show_404_abroad();
        }

        $displayData['universityObj'] 	= $universityData;
        $displayData['visaGuide'] 		= $this->abroadListingCommonLib->getVisaGuideDetailForCountry(reset($displayData['universityObj']->getLocations())->getCountry()->getId());
        $displayData['visaGuideFlag'] 	= (trim($displayData['visaGuide']['summary']) != '' && $displayData['visaGuide'] != 'NO_GUIDE_FOUND')? true:false;

    }

    private function _initDepartment(&$displayData, $deptId) {
        $listingBuilder = new ListingBuilder;
        $this->abroadInstituteRepository 	= $listingBuilder->getAbroadInstituteRepository();
        $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();

        //get department object
        $departmentData = $this->abroadInstituteRepository->find($deptId);
        if(!is_object($departmentData) || $departmentData instanceof Institute || $departmentData->getId() == "") {
            show_404_abroad();
        }

        $displayData['departmentObj'] 	= $departmentData;

        //show 404 if department obj not found
        if(!$displayData['departmentObj']->getId())
            show_404_abroad();

        //get university object
        $universityData = $this->abroadUniversityRepository->find($departmentData->getUniversityId());
        $displayData['universityObj'] 	= $universityData;

        // If user tries to open the Dummy dept or actual dept, redirect him/her to the University page..
        if(true || !$displayData['departmentObj']->isDisplayable()) {
            redirect($displayData['universityObj']->getUrl(), 'location', 301);die;
        }

        $seoRelatedRequireData = array('departmentName'		=>$displayData['departmentObj']->getName(),
            'universityName'		=>$displayData['departmentObj']->getUniversityName(),
            'departmentHighlights'	=>$displayData['departmentObj']->getDescription()
        );
        $displayData['seoDescription'] = $this->getSeoDescription($seoRelatedRequireData,'department');

    }

    private function _validateEntityId($entityId) {
        if($entityId == "" || !is_numeric($entityId)) {
            show_404_abroad();
        }
    }

    private function _validateUrl($entityUrl) {
        $userEnteredURL = getCurrentPageURLWithoutQueryParams();
        $userEnteredURL = trim($userEnteredURL);
        if($userEnteredURL != $entityUrl) {
            redirect($entityUrl, 'location', 301);
        }
    }

    function courseListing($courseId)
    {
        $this->_validateEntityId($courseId);
        $type = 'course';
        $this->_init($displayData,$courseId,$type);
        $this->_initLoggedInUserData($displayData);
        $this->_initCourse($displayData,$courseId);

        //prepare course overview tab data
        $this->getCourseOverviewData($courseId,$displayData);

        //prepare similar courses right side tab data
        $displayData['otherCoursesArr'] = $this->abroadListingCommonLib->getOtherCoursesOfUniversity($displayData['universityObj'],$displayData['courseObj'],$displayData['courseCategoryId'],$displayData['courseSubCategoryId']);

        //prepare fee tab data
        $feeDetails = $this->abroadListingCommonLib->getCourseFeesDetails(array($displayData['courseObj']));
        $displayData = array_merge($displayData, $feeDetails[$courseId]);

        /* this information is not rendered on course page, but is required on univ page, therefore commenting this here
        $contactDetails = $this->abroadListingCommonLib->getContactNumberForAbroadListing($displayData['courseObj']->getUniversityId(), 'university', FALSE);
       $displayData['university_contact_number'] = $contactDetails['contact_number'];*/
        //prepare placement tab data
        $this->abroadListingCommonLib->getPlacementTab($displayData);

        //prepare more info links right side tab data
        $this->getCourseMoreInfoTabDetails($displayData);

        //prepare Application Process tab data
        $this->_prepareDataForApplicationProcessTab($displayData,'course',$displayData['courseObj']);
        $displayData['courseApplicationEligibilityDetails']=$this->abroadListingCommonLib->getCourseApplicationEligibilityData($displayData['courseObj']->getId());

        //prepare Scholarship Process tab data
        // $this->abroadListingCommonLib->prepareDataForScholarshipTab($displayData,$displayData['courseObj']);

        //prepare entry requirements tab data
        $this->getEntryRequirementsData($displayData);

        // get user preference, education data (to populate when do you plan to start?, exams taken)
        $userStartTimePrefWithExamsTaken = $this->responseAbroadLib->getUserStartTimePrefWithExamsTaken($displayData['validateuser']);
        $this->LDBDetails = $userStartTimePrefWithExamsTaken['LDBDetails'];

        /* prepare data now required for new single registration form */
        $courseData = array( $displayData['courseObj']->getId() => array(
            'desiredCourse' => ($displayData['courseObj']->getDesiredCourseId()?$displayData['courseObj']->getDesiredCourseId():$displayData['courseObj']->getLDBCourseId()),
            'paid'		=> $displayData['courseObj']->isPaid(),
            'name'		=> $displayData['courseObj']->getName(),
            'subcategory'	=> $displayData['courseSubCategoryId']
        )
        );
        /* END: prepare data now required for new single registration form */

        $brochureDataObj = array(
            'sourcePage'       				=> 'course',
            'courseId'         				=> $displayData['courseObj']->getId(),
            'courseName'       				=> $displayData['courseObj']->getName(),
            'universityId'     				=> $displayData['courseObj']->getUniversityId(),
            'universityName'   				=> $displayData['universityObj']->getName(),
            'userStartTimePrefWithExamsTaken' 		=> $userStartTimePrefWithExamsTaken,
            'destinationCountryId'			=> $displayData['universityObj']->getLocation()->getCountry()->getId(),
            'destinationCountryName'			=> $displayData['universityObj']->getLocation()->getCountry()->getName(),
            'courseData'       				=> base64_encode(json_encode($courseData)), // send data now required for new single registration form
            'userDesiredCourse'				=> $userStartTimePrefWithExamsTaken['desiredCourse'],
        );
        $displayData['brochureDataObj'] 			= $brochureDataObj;
        // this controller is loaded for creating the view of rate my chance button in case we have rmc counsellor mapped to this shiksha apply enabled course
        // and the course also has a counsellor mapped to its university
        $displayData['rateMyChanceCtlr'] 			= Modules::load('rateMyChances/rateMyChances');

        if($displayData['validateuser'] != "false")
        {
            $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            $this->userRmcCourses=$displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
        }
        else
        {
            $this->userRmcCourses = $displayData['userRmcCourses'] = array();
        }
        $cookieToInitiateResponseCalls 			= $this->readRemoveCookieForBrochureDownload();
        $displayData['initiateBrochureDownload']= $cookieToInitiateResponseCalls['initiateBrochureDownload'];
        $displayData['initiateCallback'] 		= $cookieToInitiateResponseCalls['initiateCallback'];
        $extraData['departmentObj'] = $displayData['departmentObj'];
        $extraData['universityObj'] = $displayData['universityObj'];
        $extraData['courseSubCategoryId'] = $displayData['courseSubCategoryId'];
        $extraData['courseCategoryId'] = $displayData['courseCategoryId'];

        $breadCrumbArray = $this->_getBreadcrumbs($displayData['courseObj'], 'course', $extraData);
        $displayData['breadcrumbData'] = $breadCrumbArray['breadcrumbData'];
        $displayData['studentGuide'] = $this->abroadListingCommonLib->getStudentGuides($displayData['courseObj']->getCountryId());
        $classProfiles = $this->abroadListingCommonLib->getCourseClassProfile(array($displayData['courseObj']));
        $displayData = array_merge($displayData,$classProfiles[$courseId]);

        // Check and set the values is displayData array necessary for making the response eg. Viewed_listing
        $this->_checkAndSetDataForAutoResponse($displayData['courseObj'], $displayData);

        // get last 6 months response count for course
        $displayData['responseCountForCourse'] = $this->abroadListingModel->getLastXMonthsResponseCount($displayData['courseObj']->getId(),3);

        $this->_prepareTrackingData($displayData);

        $displayData['MISTrackingDetails'] = $this->abroadListingCommonLib->getInlineSignupFormTrackingParams(39); // tracking key id for inline brochure form
        $displayData['abroadSignupLib'] = $this->load->library('studyAbroadCommon/AbroadSignupLib');


        $this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
        $displayData['userComparedCourses'] = $this->compareCourseLib->getUserComparedCourses();

        $displayData['compareCookiePageTitle'] = htmlentities($displayData['courseObj']->getName());
        $displayData['compareOverlayTrackingKeyId'] = 588;
        $displayData['compareButtonTrackingId'] = 654;
        // This is so that the recommendation widget is present at page load now.
        // @RAHUL
        $this->pageTitle = $displayData['courseObj']->getName();
        $displayData['alsoViewedRecommendationResponse'] = $this->getAbroadRecommendations('alsoViewed',$displayData['courseObj']->getId());

        $photos = $displayData['universityObj']->getPhotos();
        if(count($photos)>0){
            $displayData['imgURL'] = $photos[0]->getThumbURL('172x115');
        }
        else{
            $displayData['imgURL'] = SHIKSHA_STUDYABROAD_HOME."/public/images/defaultCatPage1.jpg";
        }
        $compareWidgetData = array(
            'courseLevel' => $displayData['courseObj']->getCourseLevel1Value(),
            'countryId' => $displayData['courseObj']->getCountryId(),
            'courseCategoryId' => $displayData['courseCategoryId'],
            'courseSubCategoryId' => $displayData['courseSubCategoryId'],
            'isMobile' => false,
            'courseId' => $courseId,
            'courseData' => $displayData['alsoViewedRecommendationResponse']['courseData']
        );
        $displayData['compareData'] = $this->abroadListingCommonLib->getCompareCourseWidgetData($compareWidgetData);
        $displayData['scholarshipCardData'] = $this->abroadListingCommonLib->getScholarshipsWidgetData($displayData, 8);
        $displayData['fatFooterData'] = $this->abroadListingCommonLib->getFatFooterWidgetData($displayData, 8);

        $this->load->view('listing/abroad/courseOverview', $displayData);
    }


    private function _prepareTrackingData(&$displayData)
    {
        $displayData['googleRemarketingParams'] = array(
            "categoryId" => ($displayData['courseCategoryId'] == '' ? 0 : $displayData['courseCategoryId']),
            "subcategoryId" => ($displayData['courseSubCategoryId'] == '' ? 0 : $displayData['courseSubCategoryId']),
            "desiredCourseId" => 0,
            "countryId" => reset($displayData['universityObj']->getLocations())->getCountry()->getId(),
            "cityId" => reset($displayData['universityObj']->getLocations())->getCity()->getId(),
        );
        if($displayData['listingType']=='course')
        {
            $displayData['googleRemarketingParams']["universityId"] = $displayData['courseObj']->getUniversityId();
            $courseLevel =$displayData['courseObj']->getCourseLevel1Value();
            $LDBCourseId = $displayData['courseObj']->getDesiredCourseId();
        }
        else if($displayData['listingType']=='university')
        {
            $displayData['googleRemarketingParams']["universityId"] = $displayData['universityObj']->getId();
        }
        $displayData['beaconTrackData'] = array(
            'pageIdentifier' => $displayData['listingType'].'Page',
            'pageEntityId' => $displayData['listingTypeId'],
            'extraData' => array(
                'categoryId' => $displayData['googleRemarketingParams']['categoryId'],
                'subCategoryId' => $displayData['googleRemarketingParams']['subcategoryId'],
                'LDBCourseId' => $LDBCourseId,
                'cityId' => $displayData['googleRemarketingParams']['cityId'],
                'stateId' => $state = reset($displayData['universityObj']->getLocations())->getState()->getId(),
                'countryId' => $displayData['googleRemarketingParams']['countryId'],
                'courseLevel' => $courseLevel
            )
        );
        //_p($displayData['beaconTrackData']);die;
    }

    function trackSnapshotRecord(){
        $sessionId = sessionId();
        $userId = $this->input->post('userId');
        $userId = ($userId == 0)?-1:$userId;
        $snapshotCourseId = $this->input->post('snapshotCourseId');
        $this->_init($displayData,$snapshotCourseId,'snapshotCourse');
        $result = $this->abroadListingCommonLib->addSnapshotCourseRequest($sessionId,$userId,$snapshotCourseId);
        echo json_encode($result);
    }

    private function _getBreadcrumbs($entityObj, $entityType, $extraData = array()) {

        $categoryPageRequest = $this->load->library('categoryList/AbroadCategoryPageRequest');
        $countryHomeLib	= $this->load->library('countryHome/CountryHomeLib');
        $otherBreadcrumbData[] = $breadcrumbData[] = array('title' => 'Home', 'url' => SHIKSHA_STUDYABROAD_HOME);

        switch($entityType) {
            case 'course':
                $countryObj = $entityObj->getMainLocation()->getCountry();
                $countryPageUrl = $categoryPageRequest->getURLForCountryPage($countryObj->getId());
                $countryHomeUrl = $countryHomeLib->getCountryHomeUrl($countryObj);
                $otherBreadcrumbData[] = $breadcrumbData[] = array('title' => 'Study in '.$countryObj->getName(),'url' => $countryHomeUrl);
                $otherBreadcrumbData[] = $breadcrumbData[] = array('title' => 'Universities in '.$countryObj->getName(), 'url' => $countryPageUrl);
                if(is_object($extraData['universityObj'])) {
                    $otherBreadcrumbData[] = $breadcrumbData[] = array('title' => $extraData['universityObj']->getName(), 'url' => $extraData['universityObj']->getUrl());
                }

                $otherBreadcrumbData[] = $breadcrumbData[] = array('title' => $entityObj->getName(), 'url' => '');
                $mainBreadcrumbData = $breadcrumbData;
                unset($breadcrumbData);
                $breadcrumbData['breadcrumbData'] = $mainBreadcrumbData;
                $breadcrumbData['otherBreadcrumbData'] = $otherBreadcrumbData;
                break;
            case 'department':
                // $breadcrumbData = array();
                $locaObj = reset($entityObj->getLocations());
                $countryPageUrl = $categoryPageRequest->getURLForCountryPage($locaObj->getCountry()->getId());
                $countryHomeUrl = $countryHomeLib->getCountryHomeUrl($locaObj->getCountry());
                $breadcrumbData[] = array('title' => 'Study in '.$locaObj->getCountry()->getName(),'url' => $countryHomeUrl);
                $breadcrumbData[] = array('title' => 'Universities in '.$locaObj->getCountry()->getName(), 'url' => $countryPageUrl);
                if(is_object($extraData['universityObj'])) {
                    $breadcrumbData[] = array('title' => $extraData['universityObj']->getName(), 'url' => $extraData['universityObj']->getUrl());
                }
                $breadcrumbData[] = array('title' => $entityObj->getName(), 'url' => '');
                break;

            case 'university':
                $locaObj = reset($entityObj->getLocations());
                $countryPageUrl = $categoryPageRequest->getURLForCountryPage($locaObj->getCountry()->getId());
                $countryHomeUrl = $countryHomeLib->getCountryHomeUrl($locaObj->getCountry());
                $breadcrumbData[] = array('title' => 'Study in '.$locaObj->getCountry()->getName(), 'url' => $countryHomeUrl);
                $breadcrumbData[] = array('title' => 'Universities in '.$locaObj->getCountry()->getName(), 'url' => $countryPageUrl);
                $breadcrumbData[] = array('title' => $entityObj->getName(), 'url' => '');
                $breadcrumbData['countryPageUrl'] = $countryPageUrl;
                break;
        }
        return $breadcrumbData;

    }

    function departmentListing($deptId)
    {
        $this->_validateEntityId($deptId);
        $type = 'department';

        $this->_init($displayData, $deptId, $type);
        $this->_initDepartment($displayData, $deptId);
        $this->_initLoggedInUserData($displayData);
        $this->_validateUrl($displayData['departmentObj']->getUrl());
        //prepare data for university contact info and details
        $universityContactDetails = $this->abroadListingCommonLib->getUniversityContactInfo(array($displayData['universityObj']));
        $displayData = array_merge($displayData, $universityContactDetails[$displayData['universityObj']->getId()]);

        $campusAccomodation = $this->abroadListingCommonLib->getUniversityCampusAccomodation(array($displayData['universityObj']));
        $displayData = array_merge($displayData, $campusAccomodation[$displayData['universityObj']->getId()]);
        /* this information is not rendered on course page, but is required on univ page, therefore commenting this here
        $contactDetails = $this->abroadListingCommonLib->getContactNumberForAbroadListing($displayData['departmentObj']->getUniversityId(), 'university', FALSE);
        $displayData['university_contact_number'] = $contactDetails['contact_number'];*/

        $extraData['universityObj'] = $displayData['universityObj'];
        $displayData['breadcrumbData'] = $this->_getBreadcrumbs($displayData['departmentObj'], 'department', $extraData);

        $courses = $this->abroadListingCommonLib->getDepartmentCourses($deptId);
        // prepare courselist for brochure dropdown
        //$displayData['courseList'] = $this->responseAbroadLib->processDepartmentCoursesForBrochureDropdownOnDeptPage($courses);
        if(empty($courses)){
            redirect($displayData['universityObj']->getUrl(), 'location', 301);
        }

//	    $this->_prepareDataForConsultantTab($displayData,'department',$displayData['departmentObj']);

        $displayData['coursesByCategory'] = $courses;


        //tracking data
        $this->_prepareTrackingData($displayData);

        /*********** prepare data for brochure download *********/
        //get user preference, education data (to populate when do you plan to start?, exams taken)
        //************* download brochure not available on dept anymore ****************************
        $userStartTimePrefWithExamsTaken = $this->responseAbroadLib->getUserStartTimePrefWithExamsTaken($displayData['validateuser']);

        $brochureDataObj = array(
            'sourcePage'       => 'department',
            'departmentId'     => $displayData['departmentObj']->getId(),
            'departmentName'   => $displayData['departmentObj']->getName(),
            'userStartTimePrefWithExamsTaken' => $userStartTimePrefWithExamsTaken,
            'destinationCountryId'	=> $displayData['universityObj']->getLocation()->getCountry()->getId(),
            'destinationCountryName'=> $displayData['universityObj']->getLocation()->getCountry()->getName(),
            //prepare courseData that will be used in inline download brochure form
            'courseData'		=> base64_encode(json_encode($this->responseAbroadLib->getCourseDataForBrochureDownload($displayData['courseList']))),
            'userDesiredCourse'	=> $userStartTimePrefWithExamsTaken['desiredCourse'],
        );
        $displayData['brochureDataObj'] = $brochureDataObj;
        $cookieToInitiateResponseCalls = $this->readRemoveCookieForBrochureDownload();
        $displayData['initiateBrochureDownload'] = $cookieToInitiateResponseCalls['initiateBrochureDownload'];
        $displayData['initiateCallback'] = $cookieToInitiateResponseCalls['initiateCallback'];
        /************* download brochure not available on dept anymore ****************************/
        $displayData['otherDepartments'] = $this->abroadListingModel->getOtherDepartments($displayData['departmentObj']);

        //Consultant Data for Inline Download Brochure form
//	    $displayData['consultantRelatedData'] = array();
//	    $param = array(
//			   'sourcePage' => 'department',
//			   'departmentId' => $displayData['departmentObj']->getId(),
//			   'consultantData'=>$displayData['consultantData']
//			   );
//	    if(!$displayData['consultantSelectedFlag']){
//		$displayData['consultantRelatedData'] = $this->responseAbroadLib->getConsultantData($param,$displayData['validateuser']);
//	    }

        // will show the gutter help text for request callback only if the user is first time visitor else not..
        $this->_checkFirstTimeVisitor($displayData, FALSE);

        $this->load->view('listing/abroad/deptOverview',$displayData);
    }
    /*
     *This is the main function to populate -univlisting-univID page on Desktop
     *params: universityID
    */

    function universityListing($universityId)
    {
        $this->_validateEntityId($universityId);
        $type = 'university';
        $this->_init($displayData, $universityId, $type);
        $this->_initLoggedInUserData($displayData);
        $this->_initUniversity($displayData, $universityId);
        $this->_validateUrl($displayData['universityObj']->getUrl());
        $referrerCourse = $this->security->xss_clean($this->input->get("refCourseId"));
        $pos= strpos($referrerCourse,'?'); // prevent exception for malformed url by sales
        if($pos !== false && is_numeric($pos))
        {
            $referrerCourseArr = explode('?',$referrerCourse );
            $referrerCourse = $referrerCourseArr[0];
        }
        $referrerCourse = (int)$referrerCourse;
        //prepare data for university contact info and details
        $universityContactDetails = $this->abroadListingCommonLib->getUniversityContactInfo(array($displayData['universityObj']));
        $displayData = array_merge($displayData, $universityContactDetails[$displayData['universityObj']->getId()]);

        //fetch and prepare data for cost and living expenses
        $campusAccomodation = $this->abroadListingCommonLib->getUniversityCampusAccomodation(array($displayData['universityObj']));
        $displayData = array_merge($displayData, $campusAccomodation[$displayData['universityObj']->getId()]);

        // below function is required on university page only to get univ contact number
        $contactDetails = $this->abroadListingCommonLib->getContactNumberForAbroadListing(array('listingType'=> 'university','listingObj'=>$displayData['universityObj']), FALSE);
        $displayData['university_contact_number'] = $contactDetails['contact_number'];
        //prepare breadcrumb data
        $displayData['breadcrumbData'] = $this->_getBreadcrumbs($displayData['universityObj'], 'university');
        // in breadcrumb data we get country page url for 1st level escalated redirection(as in breadcrumb heirarchy)
        $displayData['countryPageUrl'] = $displayData['breadcrumbData']['countryPageUrl'];
        // clean the main breadcrumb data
        unset($displayData['breadcrumbData']['countryPageUrl']);

        $universityCourseBrowseSectionByStream 	= $this->getCourseBrowseSectionData($displayData['universityObj']);
        // these course objs can be used later by any code anytime
        $displayData['universityCourseBrowseSectionByStream'] 	= $universityCourseBrowseSectionByStream;
        $displayData['courses'] = $universityCourseBrowseSectionByStream['courses'] ;
        unset($universityCourseBrowseSectionByStream['courses']);
        //prepare seo details
        $totalCoursesCount                  = count($universityCourseBrowseSectionByStream['urls']['courses']);
        $seoRelatedRequiredData 			= array('totalCourseCount' => $totalCoursesCount,'universityName' => $displayData['universityObj']->getName());
        $displayData['seoDescription'] 		= $this->getSeoDescription($seoRelatedRequiredData,'university');

        $cleanArray = array_filter($displayData['universityCourseBrowseSectionByStream'],'ARRAY_FILTER');
        if(empty($cleanArray)){
            //Redirect to country page!
            redirect($displayData['countryPageUrl'],'location',301);
        }


        // university's highest rank
        $universityRank = $this->abroadListingCommonLib->getHighestRankOfListing($universityId,$type);
        $universityRank = $universityRank[$universityId];

        if($universityRank)
        {
            $displayData['universityRank'] 		= $universityRank['rank'];
            $this->load->builder('RankingPageBuilder', 'abroadRanking');
            $this->rankingPageBuilder 			= new RankingPageBuilder;
            $this->rankingLib 					= $this->rankingPageBuilder->getRankingLib();
            $this->rankingPageRepository 		= $this->rankingPageBuilder->getRankingPageRepository($this->rankingLib);
            // passing true as last parameter in the following line ensures that we are not fetching actual listing objects of ranking page's ranked listings
            $rankingPageObject 					= $this->rankingPageRepository->find($universityRank['rankPageId'], true);
            $rankingPageObject 					= reset($rankingPageObject);
            $displayData['universityRankName']	= $rankingPageObject->getTitle();
            $displayData['universityRankURL']	= $this->rankingLib->getRankingUrl($rankingPageObject);
//            _p($rankingPageObject);
        }


        $listingLib = $this->load->library('listingPage/listingPageLib');
        $displayData['findCourseWidgetData']  = $listingLib->prepareDataForUniversityFindCourseWidget($universityCourseBrowseSectionByStream['stream']);
        $displayData['courseList'] 	= $this->responseAbroadLib->processStreamCoursesForBrochureDropdown($universityCourseBrowseSectionByStream);
        // we pass true at the end below, to get view counts for all courses which were being computed in this function anyways
        $popularCoursesData = $this->abroadListingCommonLib->processPopularCourses($displayData['courses'],$referrerCourse,true);
        $displayData['popularCourses'] = $popularCoursesData['popularCourses'];
        $displayData['allCourseViewCounts'] = $popularCoursesData['allCourseViewCounts'];
        $displayData['abroadListingCommonLib'] = $this->abroadListingCommonLib;

        //get user preference, education data (to populate when do you plan to start?, exams taken)
        $userStartTimePrefWithExamsTaken = $this->responseAbroadLib->getUserStartTimePrefWithExamsTaken($displayData['validateuser']);

        $displayData['studentGuide'] = $this->abroadListingCommonLib->getStudentGuides($displayData['universityObj']->getLocation()->getCountry()->getId());

        //People who viewed this university also viewed Widget Data

        $displayData['alsoViewedUniversityData'] = $this->abroadListingCommonLib->getDetailofAlsoViewedUniversityByUniversityId($universityId,$this->abroadUniversityRepository,15);
        // commenting below code as product do not want to make it live(in last moment) in current sprint (12May-25May'2015 Sprint)
        //$displayData['topUniversities'] = $this->abroadListingCommonLib->getPopularUniversities($displayData['universityObj']->getLocation()->getCountry()->getId(),9,5);
        $scholarshipParams = array('university'=>array($displayData['universityObj']->getId()),
            'type'=>'country','country'=>array($displayData['universityObj']->getLocation()->getCountry()->getId()),'countryName'=>array($displayData['universityObj']->getLocation()->getCountry()->getName()));
        $scholarshipCategoryPageLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageLib');
        $displayData['scholarshipCardData'] = $scholarshipCategoryPageLib->getScholarshipTupleDataForInterlinking($scholarshipParams,4,'ULP');
        $displayData['scholarshipCardData']['countryName'] = $displayData['universityObj']->getLocation()->getCountry()->getName();
//        _p($displayData['scholarshipCardData']);die;

        //prepare data for brochure download on downlod brochure buttons
        $brochureDataObj = array(
            'sourcePage'       => 'university',
            'universityId'     => $displayData['universityObj']->getId(),
            'universityName'   => $displayData['universityObj']->getName(),
            'userStartTimePrefWithExamsTaken' => $userStartTimePrefWithExamsTaken,
            'destinationCountryId'	=> $displayData['universityObj']->getLocation()->getCountry()->getId(),
            'destinationCountryName'=> $displayData['universityObj']->getLocation()->getCountry()->getName(),
            //prepare courseData that will be used in inline download brochure form
            'courseData'		=> base64_encode(json_encode($this->responseAbroadLib->getCourseDataForBrochureDownload($displayData['courses'],false,true))),
            'userDesiredCourse'	=> $userStartTimePrefWithExamsTaken['desiredCourse']
        );
        $displayData['brochureDataObj'] = $brochureDataObj;

        $cookieToInitiateResponseCalls = $this->readRemoveCookieForBrochureDownload();
        $displayData['initiateBrochureDownload'] = $cookieToInitiateResponseCalls['initiateBrochureDownload'];
        $displayData['initiateCallback'] = $cookieToInitiateResponseCalls['initiateCallback'];

        //tracking
        $this->_prepareTrackingData($displayData);

        //find dept for the university
        $displayData['showDepts']  = $this->input->get('showDepts');

        $displayData['MISTrackingDetails'] = $this->abroadListingCommonLib->getInlineSignupFormTrackingParams(2); // tracking key id for inline brochure form
        $displayData['abroadSignupLib'] = $this->load->library('studyAbroadCommon/AbroadSignupLib');
        //_p(array_keys($displayData));

        // will show the gutter help text for request callback only if the user is first time visitor else not..
        $this->_checkFirstTimeVisitor($displayData, FALSE);

        $this->load->view('listing/abroad/universityOverview',$displayData);

    }

    public function getCourseBrowseSectionData($universityObj,$abroadListingCommonLib) {
        if(!($this->abroadListingCommonLib instanceof AbroadListingCommonLib) && $abroadListingCommonLib instanceof AbroadListingCommonLib){
            $this->abroadListingCommonLib = $abroadListingCommonLib;
        }
        //get main courses
        $universityCourseBrowseSectionByStream = $this->abroadListingCommonLib->getUniversityCourses($universityObj->getId(), 'stream');
        $courses = $universityCourseBrowseSectionByStream['courses'];
        $returnData = $this->sortUniversityCoursesBrowseSection($universityCourseBrowseSectionByStream);
        $returnData['courses'] = $courses;
        return $returnData;
    }

    public function sortUniversityCoursesBrowseSection($universityCourses) {
        $courseIdsByStream = $universityCourses['stream']['course_ids'];
        $snapshotCourseIdsByStream = $universityCourses['stream']['snapshot_course_ids'];
        $universityCourses['stream'] = $this->formatCoursesData($universityCourses['stream']);
        return $universityCourses;
    }

    public function formatCoursesData($universityCourses){
        global $certificateDiplomaLevels;
        foreach($universityCourses['courses'] as $category => $courseByType) {
            foreach($courseByType as $courseType => $course) {
                if(in_array($courseType, $certificateDiplomaLevels)){
                    $courseType = 'Certificate - Diploma';
                    if(count($returnData[$category][$courseType]['courses'])>0)
                    {
                        $returnData[$category][$courseType]['courses'] = $returnData[$category][$courseType]['courses']+$course;
                    }
                    else{
                        $returnData[$category][$courseType]['courses'] = $course;
                    }
                }
                else{
                    $returnData[$category][$courseType]['courses'] = $course;
                }
            }
        }
        return $returnData;
    }

    public function getCourseOverviewData($courseId, &$displayData)
    {
        $examOrder = $this->config->item("ENT_SA_EXAM_ORDER");

        // get the highest rank of the course
        $rankInfo = $this->abroadListingCommonLib->populateRankInfo(array($displayData['courseObj']),'course');
        $displayData['courseRank'] 		= $rankInfo[$courseId]['rank'];
        $displayData['courseRankName'] 	= $rankInfo[$courseId]['rankName'];
        $displayData['courseRankURL'] 	= $rankInfo[$courseId]['rankURL'];

        //get the total fees of the course (shown at the right side)
        $feesCurrency 		= $displayData['courseObj']->getTotalFees()->getCurrency();
        $feesVal 			= $displayData['courseObj']->getTotalFees()->getValue();
        $courseFees 		= $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $feesVal);
        if($courseFees)
        {
            $courseFees 	= $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 2);
//            $courseFees = str_replace('Thousand', 'K', $courseFees);
        }
        $displayData['courseFeesDisplableAmount'] = $courseFees;

        // get the eligibility exam of the course according to the order given (shown at the right side)
        $courseObjectArrayExamSorted = $this->abroadListingCommonLib->sortEligibilityExamForAbroadCourses(array($displayData['courseObj']));
        $courseObjectArrayExamSorted = $this->abroadListingCommonLib->rearrangeCustomExams($courseObjectArrayExamSorted);
        $displayData['courseObj'] = $courseObjectArrayExamSorted[0];
        $exams = $displayData['courseObj']->getEligibilityExams();
        if($exams[0] instanceof AbroadExam){
            $displayData['courseExamName'] 	= $exams[0]->getName();
            $displayData['courseExamScore'] = $exams[0]->getCutOff();
        }

        // get the average salary of the course
        $jobProfile 		= $displayData['courseObj']->getJobProfile();
        $averageSalaryFinal 	= "";
        if($jobProfile->getAverageSalary() != "")
        {
            $averageSalaryVal   = $jobProfile->getAverageSalary();
            $averageSalaryCurr  = $jobProfile->getAverageSalaryCurrencyId();
            $averageSalaryFinal = $this->abroadListingCommonLib->convertCurrency($averageSalaryCurr, 1, $averageSalaryVal);

            //Added at this place to avoid adding Indian Display  Amount
            $displayData["fromAverageSalary"] = $this->abroadListingCommonLib->formatMoneyAmount($averageSalaryVal , $averageSalaryCurr);
            $displayData["toAverageSalary"] = $this->abroadListingCommonLib->formatMoneyAmount(round($averageSalaryFinal) , 1);
            if($averageSalaryCurr == '1'){
                $displayData["isIndianCurr"] = true;
            }
            $averageSalaryFinal = $this->abroadListingCommonLib->getIndianDisplableAmount($averageSalaryFinal, 2);
//            $averageSalaryFinal = str_replace('Thousand', 'K', $averageSalaryFinal);
        }

        $displayData["averageSalary"] = $averageSalaryFinal;

        $this->_checkFirstTimeVisitor($displayData);

    }
    /*
     * this function will show the gutter help text only if the user is first time visitor else not..
     * it includes :-
     * 1.) default flag for case of course page, where help arrows appear for left nav bar
     * 2.) help arrows for request callback on listing pages
     */
    private function _checkFirstTimeVisitor(&$displayData, $setCourseVisited = true)
    {
        $cookie_val = json_decode($_COOKIE['SACPFirstTimeVisitor'],TRUE);
        // if the cookie exists ...
        if($cookie_val != "")
        {
            // check if default visitor flag is set
            if($cookie_val['SACPFirstTimeVisitor'] == 1)
            {
                $displayData["showGutterHelpText"] = 0;
            }
            else
            {
                $displayData["showGutterHelpText"] = 1;
                $cookie_val['SACPFirstTimeVisitor'] = 1;
                setcookie("SACPFirstTimeVisitor", json_encode($cookie_val), time()+60*60*24*30, "/", COOKIEDOMAIN);
            }
        }
        else
        {
            $cookie_val = array();
            if($setCourseVisited)
            {
                $cookie_val['SACPFirstTimeVisitor'] = $displayData["showGutterHelpText"] = 1;
            }
            setcookie("SACPFirstTimeVisitor", json_encode($cookie_val), time()+60*60*24*30, "/", COOKIEDOMAIN);
        }
    }

    public function _populateCampusAddress(& $displayData)
    {
        $univObj = $displayData['universityObj'];
        $campuses = $univObj->getCampuses();
        if($campuses) {
            $displayData['campuses'] = $campuses;
            $campusFinalWebsite = array();
            foreach($campuses as $key=>$campus) {
                $campusFinalWebsite[$key] = $this->abroadListingCommonLib->parseWebsiteLinkForView($campus->getWebsiteURL());
            }
            $displayData['campusFinalWebsite'] = $campusFinalWebsite;
        }
    }

    public function increaseContactNumberViewCount($callType = "ajax") {
        $this->load->model('listing/abroadlistingmodel');
        $abroadListingModel = new abroadlistingmodel();
        $listingPageType 	= $this->input->post('listingPageType');
        $listingId			= $this->input->post('listingTypeId');
        $widgetName 		= $this->input->post('widgetName');
        $contactNumber 		= $this->input->post('contactNumber');
        if(!empty($listingPageType) && !empty($listingId) && !empty($widgetName)  && !empty($contactNumber)) {
            $params 	= array();
            $validity 	= $this->checkUserValidation();
            if(!empty($validity) && $validity != 'false'){
                $params['userid']  	= $validity[0]['userid'];
            }
            $sessionId = sessionId();
            if(!empty($sessionId)) {
                $params['session_id']  =  $sessionId;
            }
            $params['listing_type'] 		= $listingPageType;
            $params['listing_id'] 			= $listingId;
            $params['widget_name'] 			= $widgetName;
            $params['contact_number'] 		= $contactNumber;
            $id = $abroadListingModel->increaseContactNumberViewCount($params);
            if($callType == "ajax"){
                echo json_encode(array("success"=>"true"));
                return;
            } else {
                return $id;
            }
        }
    }

    function getMediaForAbroadListing($universityId,$listingType = 'course',$isCountryPage = 0){
        if(!(is_numeric($universityId) && $universityId > 0))
        {
            return false;
        }
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder 			= new ListingBuilder;
        $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
        $displayData['universityObj'] = $this->abroadUniversityRepository->find($universityId);
        $displayData['isCountryPage'] = $isCountryPage;
        if($listingType == 'university')
        {
            $this->load->view('listing/abroad/widget/universityMedia',$displayData);
        } else {
            $this->load->view('listing/abroad/widget/courseMedia',$displayData);
        }

    }

    function getAdmittedStudentCards($universityOrCourseId,$listingType = 'course')
    {
        if(!(is_numeric($universityOrCourseId) && $universityOrCourseId > 0))
        {
            return false;
        }
        $this->abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
        $displayData = array();
        $displayData['listingType'] = $listingType;
        $displayData['usersData'] = $this->abroadListingCommonLib->getDataForAdmittedUserByUnivOrCourse($universityOrCourseId,$listingType);
        if(!$displayData['usersData'])
        {
            return false;
        }
        $applyHomeLib = $this->load->library('applyHome/ApplyHomeLib');
        $displayData['ratingData'] = $applyHomeLib-> getStudyAbroadCounsellingRatingData();
        $displayData['starRatingWidth'] = $applyHomeLib->getStarRatingWidth(3.2,96,$displayData['ratingData']['overallRating']);

        $this->load->view('listing/abroad/widget/userAdmittedCards',$displayData);
    }

    public function getAdmittedStudentCardsAPI($universityOrCourseId,$listingType = 'course'){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");

        $dataToReturn = array();
        if((is_numeric($universityOrCourseId) && $universityOrCourseId > 0))
        {
            $this->abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
            $formattedUsersData = array();
            $count=0;
            $usersData                     = $this->abroadListingCommonLib->getDataForAdmittedUserByUnivOrCourse($universityOrCourseId,$listingType);
            $applyHomeLib = $this->load->library('applyHome/ApplyHomeLib');
            foreach ($usersData as $userId => $userData) {
                if(empty($userData['education']['educationLevel'])||($userData['education']['educationLevel']==10)){
                        $graduationOrXthHeading ='Class X Board';
                        $graduationOrXthContent =empty($userData['education']['board'])?'None':$userData['education']['board'];
                        $workOrXthScoreHeading = empty($userData['education']['educationMarksType'])?'CGPA':$userData['education']['educationMarksType'];
                        $workOrXthScoreContent = empty($userData['education']['educationMarks'])?'None':$userData['education']['educationMarks'];

                }else{
                        $graduationOrXthHeading = 'Graduation Percentage';
                        $graduationOrXthContent = empty($userData['education']['educationMarks'])?'None':$userData['education']['educationMarks'];
                        $workOrXthScoreHeading = 'Work Experience';
                        $workOrXthScoreContent = empty($userData['education']['workex'])?'None':$userData['education']['workex'];
                }
                $examScore = (empty($userData['exam']) ||empty($userData['exam']['examName']))?'None': (empty($userData['exam']['educationMarks'])
                        ?$userData['exam']['examName'].': None':$userData['exam']['examName'].': '.$userData['exam']['educationMarks']);


                $formattedUsersData[$count]['name']            = empty($userData['name'])?'-':$userData['name'];
                $formattedUsersData[$count]['userImage']      = $userData['image'];
                $formattedUsersData[$count]['course']          = empty($userData['admissionData']['courseName'])?'-':$userData['admissionData']['courseName'];
                $formattedUsersData[$count]['universityName'] = empty($userData['admissionData']['univName'])?'-':$userData['admissionData']['univName'];
                $formattedUsersData[$count]['examScore']      = $examScore;
                $formattedUsersData[$count]['educationLabel'] = $graduationOrXthHeading;
                $formattedUsersData[$count]['educationData']  = $graduationOrXthContent;
                $formattedUsersData[$count]['workOrTenthLabel']      = $workOrXthScoreHeading;
                $formattedUsersData[$count]['workOrTenthData']       = $workOrXthScoreContent;
                $formattedUsersData[$count]['profileLink']     = str_replace(SHIKSHA_STUDYABROAD_HOME,'',$userData['profileLink']);
                $count++;
            }
            $ratingData = $applyHomeLib-> getStudyAbroadCounsellingRatingData();
            unset($usersData);

            echo  json_encode(array('data'=>$formattedUsersData, 'ratingData'=>$ratingData));
        }else{
            echo  json_encode(array('data'=>null));
        }
        exit;
    }

    public function getAlsoViewedUniversitiyAPI($universityId){
        if((is_numeric($universityId) && $universityId > 0)){
            $this->load->builder('ListingBuilder','listing');
            $listingBuilder             = new ListingBuilder;
            $abroadUniversityRepository = $listingBuilder->getUniversityRepository();
            $abroadListingCommonLib     = $this->load->library('listing/AbroadListingCommonLib');
            $alsoViewedUniversityData   = $abroadListingCommonLib->getDetailofAlsoViewedUniversityByUniversityId($universityId,$abroadUniversityRepository,15);

            foreach ($alsoViewedUniversityData as $key => $value) {
                $urlParts = parse_url($value['url']);
                $alsoViewedUniversityData[$key]['url'] = $urlParts['path'];
            }
            if(count($alsoViewedUniversityData) > 0){
                echo  json_encode(array('data'=>$alsoViewedUniversityData));
            }else{
                echo  json_encode(array('data'=>null));
            }
        }else{
            echo  json_encode(array('data'=>null));
        }

        exit;


    }

    public function getSimilarCourseOfUniversity(&$displayData, $universityId, $categoryId, $courseType = 'regular')
    {
        $displayData["similarCoursesData"] = $this->abroadListingCommonLib->getSimilarCourses($universityId, $categoryId, $displayData['courseObj'], $this->abroadCourseRepository, $courseType);
        $univUrl = $displayData['universityObj']->getURL();
        if($univUrl)
            $displayData["universityCourseSectionUrl"] = $univUrl."#coursesOfUniversitySec";
    }

    public function getCourseMoreInfoTabDetails(&$displayData)
    {
        $moreinfotab['facultyInfoLink'] 	= $displayData['courseObj']->getFacultyInfoLink();
        $moreinfotab['coursefaqLink'] 		= $displayData['courseObj']->getCourseFaqLink();
        $moreinfotab['alumniInfoLink']		= $displayData['courseObj']->getAlumniInfoLink();

        //since we need to show affiliation n accreditation also, add them to the check
        //$courseAttr 						= $displayData['courseObj']->getAttributes();
        //$moreinfotab['affiliation']			= $courseAttr['AffiliatedTo'];
        //$moreinfotab['accreditation']		= $courseAttr['courseAccreditation'];

        if(!$displayData['isPlacementFlag'])
        {
            $moreinfotab['careerServicesLink'] 	= $displayData['courseObj']->getJobProfile()->getCareerServicesLink();
        }

        $isMoreInfoTabFlag = 0;
        foreach($moreinfotab as $infolink)
        {
            if($infolink)
            {
                $isMoreInfoTabFlag = 1;
            }
        }

        $displayData['moreinfotab']			= $moreinfotab;
        $displayData['isMoreInfoTabFlag']	= $isMoreInfoTabFlag;
    }

    /*
     * Function for Brochure Download and tracking
     */
    public function downLoadAbroadPDFbrochure($url='',$courseId='',$source='', $tempLmsTableId=0)
    {
        $this->_init($displayData,$courseId,'course');
        $url = base64_decode($url);
        if($url == '' || $courseId == ''|| $displayData['validateuser'] == false || $displayData['validateuser'] == 'false'){
            return ;
        }

        $this->load->model('listing/coursemodel');
        $courseModel	= new coursemodel();
        $trackInfo = array("course_id"		=>	$courseId,
            "user_id"		=>	$displayData['validateuser']['0']['userid'],
            "downloadedFrom"	=>	$source,
            "brochureUrl"	=>	$url,
            "session_id"		=>	"",
            "tempLmsTableId"	=>	$tempLmsTableId
        );

        $downloadTrackingId = $courseModel->trackFreeBrochureDownload($trackInfo);
        $trackInfo['downloadTrackingId'] = $downloadTrackingId ;
        // update the download tracking id in email tracking table
        $courseModel->updateBrochureEmailTracking($trackInfo);
        $this->load->helper('download');
        downloadFileInChunks($url, 400000);
    }
    /*
     * Function for abroad listings Brochure Download and tracking
     */
    public function downloadAbroadListingsBrochure($url,$listingType,$listingTypeId,$source,$tempLmsTableId,$brochureEmailInsertId)
    {

        $this->_init($displayData,$listingTypeId,$listingType);
        $this->_initLoggedInUserData($displayData);
        $url = base64_decode($url);
        if($url == '' || $listingTypeId == ''|| $displayData['validateuser'] == false || $displayData['validateuser'] == 'false'){
            return ;
        }

        $this->load->model('listing/abroadlistingmodel');
        $abroadListingModel	= new abroadlistingmodel();
        $trackInfo = array("listing_type"	=>	$listingType,
            "listing_type_id"	=>	$listingTypeId,
            "user_id"		=>	$displayData['validateuser']['0']['userid'],
            "downloadedFrom"	=>	$source,
            "brochureUrl"	=>	$url,
            "session_id"		=>	getVisitorSessionId(),
            "tempLmsTableId"	=>	$tempLmsTableId,
            "brochureEmailInsertId"=>    $brochureEmailInsertId
        );

        $downloadTrackingId = $abroadListingModel->trackAbroadListingsBrochureDownload($trackInfo);
        $trackInfo['downloadTrackingId'] = $downloadTrackingId ;
        // update the download tracking id in email tracking table
        $abroadListingModel->updateAbroadBrochureDownloadInEmailTracking($trackInfo);
        $this->load->helper('download');
        downloadFileInChunks($url, 400000);
    }
    /*
     * to download the abroad listing brochure when redirected from mail link
     */
    public function downloadAbroadListingsBrochureFromMailLink($encodedMsg, $source)
    {
        // decode data from msg
        $abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
        $dataArr 		= $abroadListingCommonLib->getDecodedMsgForBrochureDownloadURL( $encodedMsg );
        $user_id		= $dataArr['userId'];
        $listing_type		= $dataArr['listingType'];
        $listing_type_id	= $dataArr['listingTypeId'];
        //_p($listing_type_id);die;
        if($listing_type_id !='' && is_numeric($listing_type_id))
        {
            if($listing_type == 'course')
            {
                $brochureUrl = $abroadListingCommonLib->getCourseBrochureUrl($listing_type_id);
            }else if($listing_type == 'scholarship'){
                $this->load->builder('scholarshipsDetailPage/scholarshipBuilder');
                $scholarshipBuilder = new scholarshipBuilder();
                $scholarshipRepository = $scholarshipBuilder->getScholarshipRepository();
                $sections = array('basic'=>array('scholarshipId','name','subscriptionType'),'application'=>array('scholarshipBrochureUrl'));
                $scholarshipObj = $scholarshipRepository->find($listing_type_id,$sections);
                $brochureUrl = $scholarshipObj->getApplicationData()->getBrochureUrl();
            }else{
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $universityRepository = $listingBuilder->getUniversityRepository();
                $universityObj = $universityRepository->find($listing_type_id);
                $brochureUrl = $universityObj->getBrochureLink();
                _p($brochureUrl);
            }
        }
        // return if empty url
        if(empty($brochureUrl))
        {
            //_p("Wrong URL");
            return ;
        }
        // prepare tracking data
        $userInfo = array( "listing_type"	=>	$listing_type,
            "listing_type_id"	=>	$listing_type_id,
            "user_id"		=>	$user_id,
            "downloadedFrom"	=>	$source,
            "brochureUrl"	=>	$brochureUrl,
            "session_id"		=>	getNewSessionId(),
            "tempLmsTableId"	=>	$tempLmsTableId
        );

        // prevent more than 5 downloads
        $previousDownloadCount = $this->getAbroadListingsDownloadCountForSession($userInfo);
        if( $previousDownloadCount >= 5 )
        {
            _p('<div style="font-family:Tahoma, Geneva, sans-serif"><div style="font-weight:bold;font-size:18px;">Download Brochure</div><p style="margin-bottom:1px;">You have downloaded this brochure <span style="color:#f27427; font-weight:bold;">5 times</span>.</p><p style="margin-bottom:1px;">Further Downloads are currently not allowed.</p></div>');
            return;
        }
        else
        {   // track download
            $abroadListingModel = $this->load->model('listing/abroadlistingmodel');
            $downloadTrackingId = $abroadListingModel->trackAbroadListingsBrochureDownload($userInfo);
            //download the file
            $this->load->helper('download');
            downloadFileInChunks($brochureUrl, 400000);
        }
    }
    /*
     * Function to get the count of downloads happened for the given user, same univ/course and same user session
     */
    private function getAbroadListingsDownloadCountForSession( $userInfo )
    {
        $modelObj= $this->load->model('listing/abroadlistingmodel');
        $count = $modelObj->getBrochureDownloadCountForSession($userInfo);
        return ( empty($count)?0:$count );
    }
    /*
     * to download the brochure when redirected from mail link
     */
    function downloadBrochureFromMailLink($encodedMsg, $source)
    {
        // decode data from msg
        $national_course_lib = $this->load->library('listing/NationalCourseLib');
        $dataArr 	= $national_course_lib->getDecodedMsgForBrochureURL( $encodedMsg );
        $user_id	= $dataArr[0];
        $course_id	= $dataArr[1];

        //get course brochure url
        $abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
        $course_reb_url = $abroadListingCommonLib->getCourseBrochureUrl($course_id);
        // return if empty url
        if(empty($course_reb_url) || count($dataArr) != 2 )
        {
            //_p("Wrong URL");
            return ;
        }
        // prepare tracking data
        $userInfo 			= array();
        $userInfo['user_id'] 		= $user_id;
        $userInfo['course_id'] 		= $course_id;
        $userInfo['brochureUrl'] 	= $course_reb_url;
        $userInfo['session_id'] 	= getNewSessionId();
        $userInfo['downloadedFrom']	= $source;
        // prevent more than 5 downloads
        $previousDownloadCount = $this->getPreviousDownloadCountForSession($user_id, $course_id, $userInfo['session_id']);
        if( $previousDownloadCount >= 5 )
        {
            _p('<div style="font-family:Tahoma, Geneva, sans-serif"><div style="font-weight:bold;font-size:18px;">Download Brochure</div><p style="margin-bottom:1px;">You have downloaded this brochure <span style="color:#f27427; font-weight:bold;">5 times</span>.</p><p style="margin-bottom:1px;">Further Downloads are currently not allowed.</p></div>');
            return;
        }
        else
        {   // track download
            $this->load->model('listing/coursemodel');
            $courseModel = new coursemodel();
            $courseModel->trackFreeBrochureDownload($userInfo);
            //download the file
            $this->load->helper('download');
            downloadFileInChunks($course_reb_url, 200000);
        }
    }

    /**
     * Function to get the count of downloads happened for the given user, same course and same user session
     **/
    private function getPreviousDownloadCountForSession( $userId, $courseId, $sessionId )
    {
        $this->load->model('listing/coursemodel');
        $courseModel = new coursemodel();
        $count = $courseModel->getPreviousDownloadCount($userId, $courseId, $sessionId );

        if( empty($count) )
            return 0;

        return $count;
    }

    public function test12(){
        $this->_init();
        $a = $this->abroadListingCommonLib->getViewCountForCourseListByDays(array(196919, 196246, 121),21); _p($a);
    }
    /*	This is a common function to generate recommendations on study abroad website.The input includes :
            getAbroadRecommendations
             $recommendationType ='alsoViewed',   //it is either also viewed or similar insititues
             $courseId =$courseId,				//course for which to generate the recommendations
             $countryId ='',   					//required for for similar institutes type recommendations
             $exclusionList='',   				//required for similar institutes type recommendations
             $trackingPageKeyId,
             $consultantTrackingPageKeyId,
             $shortlistTrackingPageKeyId,
             $widgetFlag='rateMyChancesSuccessPage',   		//for handling the checks based on your widget
             $sourcePage ='', 								//not required if the view is different else to handle different checks on view
             $pageTitle = ''  							//set for brochure data object for rmc button on recommendation tuples so that could be shown on their thank you page go back link
    */

    public function getAbroadRecommendations($recommendationType, $courseId, $countryId, $exclusionList,$trackingPageKeyId,$consultantTrackingPageKeyId,$shortlistTrackingPageKeyId,$rmcRecoTrackingPageKeyId, $widgetFlag='',  $sourcePage ='',$pageTitle = '') {
        if(empty($pageTitle)){
            if(!empty($this->pageTitle)){
                $pageTitle = $this->pageTitle;
            }else{
                $pageTitle = $this->input->post("pageTitle");
                if(!empty($pageTitle)){
                    $pageTitle = base64_decode($pageTitle);
                }
            }
        }
        $this->load->helper('string');
        $this->load->library('categoryList/CategoryPageRecommendations');
        $this->load->library('listing/AbroadListingCommonLib');
        $this->load->builder('ListingBuilder','listing');
        $this->load->builder('LocationBuilder','location');
        $this->load->builder('LDBCourseBuilder','LDB');
        $this->load->builder('CategoryBuilder','categoryList');
        $this->load->library('responseAbroad/ResponseAbroadLib');

        $responseAbroadLib 		= new ResponseAbroadLib();
        $abroadListingCommonLib 	= new AbroadListingCommonLib();
        $listingBuilder 		= new ListingBuilder;
        $locationBuilder 		= new LocationBuilder;
        $LDBCourseBuilder		= new LDBCourseBuilder;
        $categoryBuilder 		= new CategoryBuilder;

        $abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
        $abroadInstituteRepository 	= $listingBuilder->getAbroadInstituteRepository();
        $abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
        $locationRepository 		= $locationBuilder->getLocationRepository();
        $LDBCourseRepository 		= $LDBCourseBuilder->getLDBCourseRepository();
        $categoryRepository 		= $categoryBuilder->getCategoryRepository();

        $validateUser 				= $this->checkUserValidation();
        $recommendedInstitutes 		= array();
        $recommendedCourses 		= array();
        $courseData 				= array();
        $displayData 				= array();
        $recommendatioWithDataObj	= array();

        

        //Get also viewed and similar institute recommendations
        if($recommendationType == 'alsoViewed') {
            $recommendations = $this->categorypagerecommendations->getAbroadAlsoViewedInstitutes(intval($courseId));
        }
        else if($recommendationType == 'similarInstitutes') {

            //Commented as per requirement of story LF-1785
            //   $recommendations = $this->categorypagerecommendations->getAbroadSimilarInstitutes(intval($courseId), $countryId, $exclusionList);
        }
        $requestedCourseId = $courseId;
        /****************************remove the courses mapped to consultants incase of rmc*********************/
        if($widgetFlag =="rateMyChancesSuccessPage")
        {
            $recommendedUnivIds = array();
            $recommendedCoursesRMC = array();
            $univCourseMapping = array();
            foreach($recommendations as $recommendedInstituteId => $recommendedCourseId)
            {
                $recommendedCoursesRMC[] = $recommendedCourseId;
            }
            if(count($recommendedCoursesRMC)) {
                $courses = $abroadCourseRepository->findMultiple($recommendedCoursesRMC);
                foreach($courses as $course)
                {
                    $recommendedUnivIds[] = $course->getUniversityId();
                    $univCourseMapping[$course->getId()] =   $course->getUniversityId();
                }
            }
            array_unique($recommendedUnivIds);
            $consultantData 	= $abroadListingCommonLib->checkIfUniversityHasConsultants($recommendedUnivIds);
            if($consultantData!="false")
            {
                //process data if consultant as it has all the consultant -university mappings with live status with subscription details
                $consultantUnivs = array_keys($consultantData);
                foreach ($consultantUnivs as $univId)
                {
                    $courseId = array_search($univId, $univCourseMapping);
                    if ($courseId!=false)
                    {
                        $key = array_search($courseId, $recommendations);
                        unset($recommendations[$key]);
                    }
                }
            }
        }

    
        /******************************************************************************************************/
        $recommendations = array_slice($recommendations, 0, 9, true);
        if($widgetFlag === 'signupFormThankYouPage' || ($sourcePage == 'mobileSA' && $rmcRecoTrackingPageKeyId == 1943)){
            //get top 3 main institute listings according to desired course + subcategory or parent category +subcategory
            $milRecommendation = $this->categorypagerecommendations->getMILByCourseId(intval($courseId));
            $displayData['milRecommendation']    = $milRecommendation;
            $recommendations = $milRecommendation + $recommendations;    
        }
        
      
        foreach($recommendations as $recommendedInstituteId => $recommendedCourseId) {
            $recommendedInstitutes[] = $recommendedInstituteId;
            $recommendedCourses[] = $recommendedCourseId;
        }
        if(count($recommendedCourses)) {
            $courseCats = $abroadListingCommonLib->getCategoryOfAbroadCourse($recommendedCourses);
            $courses = $abroadCourseRepository->findMultiple($recommendedCourses);

            foreach($courses as $course)
            {
                //Get data for recommended course
                $courseId = $course->getId();
                $courseName = $course->getName();
                $courseURL = $course->getURL();
                $universityId = $course->getUniversityId();
                $universityName = $course->getUniversityName();
                $instId = $course->getInstId();
                $countryId = $course->getCountryId();
                $courseLevel = $course->getCourseLevel1Value();

                $fees = $course->getTotalFees()->getValue();
                if($fees){
                    $feesCurrency = $course->getTotalFees()->getCurrency();
                    $courseFees = $abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
                    $courseFees = $abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
                }

                $courseArr = $abroadListingCommonLib->sortEligibilityExamForAbroadCourses(array($course->getId() => $course));
                $course = $courseArr[$course->getId()];
                $mobileSiteAbroadCounter = 0;
                $exams = $course->getEligibilityExams();
                $courseExam = '';
                foreach($exams as $examObj) {
                    if($examObj->getId() == -1) {
                        continue;
                    }
                    else {
                        if($recommendationType == 'alsoViewed' && $sourcePage == 'categoryPageMobileSA'){
                            $courseExam .= $examObj->getName().': '.(($examObj->getCutoff()=="N/A")?"Accepted":$examObj->getCutoff()).'|';
                            if(++$mobileSiteAbroadCounter == 2){
                                break;
                            }
                        }else{
                            $courseExam = $examObj->getName().': '.(($examObj->getCutoff()=="N/A")?"Accepted":$examObj->getCutoff());
                            break;
                        }
                    }
                }


                //Get university data for respective course
                $university = $abroadUniversityRepository->find($universityId);
                $cityName = $university->getLocation()->getCity()->getName();
                $countryName = $university->getLocation()->getCountry()->getName();
                $universityLocation = $cityName.', '.$countryName;
                $universityType = $university->getTypeOfInstitute();
                $universityScholarship = $course->isOfferingScholarship();
                $universityHasCampus = $university->hasCampusAccommodation();
                $universitySeoUrl = $university->getURL();
                $universityPhotos = $university->getPhotos();
                if(count($universityPhotos)) {
                    $universityImageURL = $universityPhotos['0']->getThumbURL('172x115');
                } else {
                    $universityImageURL = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
                }
                //create array of course data
                $courseData[$courseId] = array (
                    'courseName' 		=> $courseName,
                    'courseURL' 		=> $courseURL,
                    'courseFees' 		=> $courseFees,
                    'courseExam' 		=> $courseExam,
                    'courseDuration'	=> $course->getDuration()->getDisplayValue(),
                    'courseLevel'		=> $courseLevel,
                    'countryId'				=> $countryId,
                    'countryName'		=>	$countryName,
                    'universityId' 		=> $universityId,
                    'universityName' 	=> $universityName,
                    'universityImageURL' 	=> $universityImageURL,
                    'universityURL'			=> $universitySeoUrl,
                    'universityLocation' 	=> $universityLocation,
                    'universityType' 		=> $universityType,
                    'universityScholarship' => $universityScholarship,
                    'universityHasCampus' 	=> $universityHasCampus,
                    'institute_id'			=> $instId,
                    'paid' 					=> $course->isPaid(),
                    'desiredCourse' 		=> ($course->getDesiredCourseId()?$course->getDesiredCourseId():$course->getLDBCourseId()),
                    'parentcategory' 		=> $courseCats[$course->getId()]['categoryId'],
                    'subcategory' 			=> $courseCats[$course->getId()]['subcategoryId'],
                    'showRmcButton' 		=> $course->showRmcButton()
                );

                if($sourcePage=='mobileSA'){
                    $recommendatioWithDataObj[$courseId] = array(
                        'courseObj'		=> $course,
                        'universityObj'	=> $university,
                        'mil'           => false
                    );
                    if(isset($displayData['milRecommendation']) && in_array($courseId, $displayData['milRecommendation'])){                        
                        $recommendatioWithDataObj[$courseId]['mil'] = true;
                    }
                }
            }
        }
        /*******************return course data in case of study abroad mobile************************************/
        if($recommendationType == 'alsoViewed' && $sourcePage == 'categoryPageMobileSA'){
            return $courseData;
        }elseif($sourcePage=='mobileSA'){
          //  $recommendatioWithDataObj['milRecommendation']=$displayData['milRecommendation'];
            return $recommendatioWithDataObj;
        }
        $displayData['validateUser'] = $validateUser;
        $displayData['recommendationType'] = $recommendationType;
        $displayData['numberOfRecommendations'] = count($recommendedCourses);
        $displayData['numberOfSlides'] = ceil(count($recommendedCourses) / 3);
        $displayData['uniqId'] = random_string('alnum', 6);
        $displayData['courseData'] = $courseData;
        if(is_null($this->LDBDetails)){
            $displayData['userShortRegistrationData'] = $responseAbroadLib->getUserStartTimePrefWithExamsTaken($validateUser);
        }else{
            $displayData['userShortRegistrationData'] = $this->LDBDetails;
        }
        $displayData['requestedCourseId'] = $requestedCourseId;

        //get extra data for similar institute widget heading
        if($recommendationType == 'similarInstitutes') {
            $country = $locationRepository->findCountry($countryId);
            $countryName = $country->getName();
            $displayData['countryName'] = $countryName;

            $course = $abroadCourseRepository->find($courseId);
            $LDBCourseId = $course->getLDBCourseId();
            $LDBObj = $LDBCourseRepository->find($LDBCourseId);
            if($LDBObj->getCategoryId()>0){
                $categoryObj = $categoryRepository->find($LDBObj->getCategoryId());
                $displayData['LDBCourseName'] = $LDBObj->getCourseName().' in '.$categoryObj->getName();
            }

            $displayData['sourcePage'] = 'LP_Reco_SimilarInstiLayer';
        }
        else {
            $displayData['sourcePage'] = 'LP_Reco_AlsoviewLayer';
        }

        if (! (($validateUser == "false") || ($validateUser == ""))) {
            $data ['userId'] = $validateUser[0]['userid'];
        }

        $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChances/rateMyChances');
        if(is_null($this->userRmcCourses)) // get the user's rmc courses if not available yet
        {
            if($validateUser != "false")
            {
                $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
                $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($data['userId']);
            }else{
                $displayData['userRmcCourses'] = array();
            }
        }else{ // case of also viewed widget on course page
            $displayData['userRmcCourses'] = $this->userRmcCourses;
        }
        /******set page title for data brochure objects on recommendation tuples for rmc button*******/
        $displayData['pageTitle'] = $pageTitle;
        /**************** Download Brochure Overlay  also viewed Suggestions *****************************************/
        if($widgetFlag == 'downloadBrochureLayer'){
            if(count($recommendedCourses)){
                $data = array();
                //library to get list of courses alerady shortlisted
                $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
                $displayData['userShortlistedCourseIds']    = $shortlistListingLib->fetchIfUserHasShortListedCourses($data);
                $displayData['sourcePage'] 				    = $sourcePage;
                $displayData['trackingPageKeyId'] 		    = $trackingPageKeyId;
                $displayData['consultantTrackingPageKeyId'] = $consultantTrackingPageKeyId;
                $displayData['shortlistTrackingPageKeyId']  = $shortlistTrackingPageKeyId;
                $displayData['rmcRecoTrackingPageKeyId']  = $rmcRecoTrackingPageKeyId;
                $resultHTML = $this->load->view('listing/abroad/widget/alsoViewedRecommendations', $displayData, TRUE);
                return $resultHTML ;
            }else{
                return '';
            }
        }
        /*************************************Rate my chance also viewed recommendations****************************/
        if($widgetFlag == 'rateMyChancesSuccessPage'){
            if(count($recommendedCourses)){
                $data = array();
                //library to get list of courses alerady shortlisted
                $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
                $displayData['userShortlistedCourseIds']    = $shortlistListingLib->fetchIfUserHasShortListedCourses($data);
                $this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
                $displayData['userComparedCourses'] = $this->compareCourseLib->getUserComparedCourses();
                $resultHTML = $this->load->view('listing/abroad/widget/rmcSuccessPageRecommendation', $displayData, TRUE);
                return $resultHTML ;
            }else{
                return '';
            }
        }
        /************************************* new signup form thank you page (response) recommendations****************************/
        if($widgetFlag == 'signupFormThankYouPage'){
            return $this->_getSignupThankYouPageRecoHTML($recommendedCourses,$trackingPageKeyId,$shortlistTrackingPageKeyId,$rmcRecoTrackingPageKeyId,$data, $displayData);
        }
        /*******************************************************************************************************/
        $recommendationHTML = $this->load->view('listing/abroad/widget/recommendations', $displayData, TRUE);
        $response = array(
            'recommendationHTML' => $recommendationHTML,
            'recommendedInstitutes' => $recommendedInstitutes,
            'courseData' => $displayData['courseData']
        );
        if($this->input->is_ajax_request()){
            header('X-Robots-Tag: none');
            echo json_encode($response);
        }else{
            return $response;
        }


    }
    /*
	 * get download brochure's thank you page recommendation for download brochure
	 */
    private function _getSignupThankYouPageRecoHTML($recommendedCourses,$trackingPageKeyId,$shortlistTrackingPageKeyId,$rmcRecoTrackingPageKeyId,$data, $displayData)
    {
        if(count($recommendedCourses))
        {
            //library to get list of courses alerady shortlisted
            $displayData['trackingPageKeyId'] 		    = $trackingPageKeyId;
            $displayData['shortlistTrackingPageKeyId']  = $shortlistTrackingPageKeyId;
            $displayData['rmcRecoTrackingPageKeyId']  	= $rmcRecoTrackingPageKeyId;
            $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
            $displayData['userShortlistedCourseIds']    = $shortlistListingLib->fetchIfUserHasShortListedCourses($data);
            $resultHTML = $this->load->view('listing/abroad/widget/signupThankYouPageRecommendation', $displayData, TRUE);
            return $resultHTML ;
        }else{
            return '';
        }
    }

     public function checkForAutoResponse(){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
        $courseId = $this->input->post('courseId', true);
        
        if(empty($courseId)){
           echo json_encode(array('status'=>false));
           die;
        }
        $validateuser = $this->checkUserValidation();
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder                         = new ListingBuilder;
        $this->abroadCourseRepository           = $listingBuilder->getAbroadCourseRepository();
        $course                                 = $this->abroadCourseRepository->find($courseId);
        if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums","saAdmin","saCMS"))) && $course->isPaid() && !empty($validateuser[0]["mobile"]) && !empty($validateuser[0]["lastname"]))
        {
            $makeAutoResponse = true;

            $user_id = $validateuser[0]['userid'];
            $flag = $this->_isLastDayLeadExist($user_id, $course->getId(), 'course' );
            if( $flag ){
                $reponse_already_created = true;
            }else{
                $reponse_already_created = false;
                $this->load->library('responseAbroad/ResponseAbroadLib');
                $responseAbroadLib = new ResponseAbroadLib();
                $userStartTimePrefWithExamsTaken = $responseAbroadLib->getUserStartTimePrefWithExamsTaken($validateuser);
                $LDBDetails = $userStartTimePrefWithExamsTaken['LDBDetails'];

                if(!is_null($LDBDetails) && is_array($LDBDetails))
                {
                    $user = $LDBDetails;
                }else{
                    $this->load->model('user/usermodel');
                    $usermodel = new usermodel;
                    $user = $usermodel->getLDBuserDetails($user_id);
                }
                if($user['isLDBUser'] == 'NO'){
                    $makeAutoResponse = false;
                }
            }           
            if($makeAutoResponse && !$reponse_already_created){
                echo json_encode(array('status'=>true));                
                exit;
            }
        }else{
                echo json_encode(array('status'=>false));                                
                exit;
        }
    }
    /**
     * Purpose : Function to check and set the values is displayData array necessary for making the response eg. Viewed_listing
     **/
    private function _checkAndSetDataForAutoResponse($course, &$displayData)
    {
        // get user validation data of user
        $validateuser 			= $displayData['validateuser'];

        // set the makeAutoResponse if following conditions are made
        if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums","saAdmin","saCMS"))) && $course->isPaid() && !empty($validateuser[0]["mobile"]) && !empty($validateuser[0]["lastname"]))
        {
            $displayData['makeAutoResponse'] = true;

            $user_id = $validateuser[0]['userid'];
            $flag = $this->_isLastDayLeadExist( $user_id, $course->getId(), 'course' );

            if( $flag ){
                $displayData['reponse_already_created'] = true;
            }else{
                $displayData['reponse_already_created'] = false;
                if(!is_null($this->LDBDetails) && is_array($this->LDBDetails))
                {
                    $user = $this->LDBDetails;
                }else{
                    $this->load->model('user/usermodel');
                    $usermodel = new usermodel;
                    $user = $usermodel->getLDBuserDetails($user_id);
                }
                if($user['isLDBUser'] == 'NO'){
                    $displayData['makeAutoResponse'] = false;
                }
            }
        }
    }

    /**
     * Function to check for response made for the given user,course for last 24 hours
     **/
    private function _isLastDayLeadExist( $userId, $course_id, $listing_type )
    {
        $this->load->model('listing/listingmodel');
        $row = $this->listingmodel->getLastDayLead($userId, $course_id, $listing_type );

        if( !empty($row) )
            return true;
        else
            return false;
    }

    public function fetchIfUserHasShortListedCourses(){
        $validity = $this->checkUserValidation ();
        $data = array();
        if (! (($validity == "false") || ($validity == ""))) {
            $data ['userId'] = $validity [0] ['userid'];
        }
        $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
        return $shortlistListingLib->fetchIfUserHasShortListedCourses ( $data);

    }

    public function updateShortListedCourse($courseId, $action) {
        $validity = $this->checkUserValidation ();
        $data['sessionId'] = sessionId();
        $data['userId'] = (($validity == 'false') || ($validity==''))? -1 : $validity[0]['userid'];
        if ($action == 'delete') {
            $data['courseId'] = $courseId;
            $data['status'] = 'deleted';
        } else {
            $data['status'] = 'live';
            $data['courseId'] = $courseId;
            $data['sessionId'] = sessionId ();
            $pageType = $this->input->post("pageType");
            if($pageType){
                $data['pageType'] = $pageType;
            }else{
                $data['pageType'] = 'courseListingPage';
            }
            $data['shortListTime'] = date ( 'Y-m-d H:i:s' );
        }
        $data['tracking_keyid'] = $this->input->post('trackingPageKeyId');
        $data['visitorSessionid'] = getVisitorSessionId();
        $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
        echo $shortlistListingLib->updateShortListedCourse ( $data, $action );
    }

    public function putShortListCouresFromCookieToDB ($shortlistCourseIdInSignUpProcess) {
        $validity = $this->checkUserValidation ();
        if (! (($validity == "false") || ($validity == ""))) {
            $data ['userId'] = $validity [0] ['userid'];
            $data ['status'] = 'live';
            $data ['sessionId'] = sessionId ();
            $data ['pageType'] = 'courseListingPage';
            $data ['shortlistCourseIdInSignUpProcess'] = $shortlistCourseIdInSignUpProcess;
            $data['tracking_keyid'] = $this->input->post('trackingPageKeyId');
            if($data['tracking_keyid'] == 1729){
                $data ['pageType'] = 'abroadSearchV2';
            }else if($data['tracking_keyid']>0){
                // take page type from db for given tracking key id
                $signupLib = $this->load->library('studyAbroadCommon/AbroadSignupLib');
                $MISTrackingDetails = $signupLib->getMISTrackingDetails($data['tracking_keyid']);
                $data ['pageType'] = $MISTrackingDetails['page'];
            }
            $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
            echo $shortlistListingLib->putShortListCouresFromCookieToDB ( $data);
        }

    }
    public function getFeedbackForm()
    {
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['source_page_url'] = $this->input->post('url');
        $data['session_id'] = getNewSessionId();
        echo $this->load->view('listing/abroad/feedbackLayer',$data);
    }

    public function submitFeedback()
    {
        $feedback_email 	= $this->input->post('feedback_email'   );
        $feedback_mobile 	= $this->input->post('feedback_mobile'  );
        $feedback_comments 	= trim($this->input->post('feedback_comments'));
        $session_id 	  	= $this->input->post('session_id'       );
        $source_page_url   	= $this->input->post('source_page_url'  );
        $feedbacktype 		= '';
        $feedbacktype   	= $this->input->post('feedbacktype'  );
        $courseID = '';
        $courseID   	= $this->input->post('courseID'  );

        $source_page_url = str_replace(array('Eligibilitytab','Feetab'), array('EntryRequirements','Fees&Expenses'), $source_page_url);

        $feedbackData  = array( 'email'   			=> $feedback_email 	,
            'mobile' 			=> $feedback_mobile ,
            'feedback_comment'	=> $feedback_comments ,
            'session_id' 	  	=> $session_id 	,
            'submit_time'	  	=> date("Y-m-d H:i:s"),
            'source_page_url'  	=> $source_page_url 	);

        $abroadListingModel = $this->load->model('abroadlistingmodel');
        $result  = $abroadListingModel->saveFeedbackData($feedbackData);

        /**************** send  mail to user & admin ********************/
        $this->load->library('alerts_client');
        $alerts_client = new Alerts_client();
        $mailerData = array();
        $mailerData['fromEmail'] = SA_ADMIN_EMAIL;

        if($feedbacktype == 'reportIncorrect')
        {
            $feedbackData['courseID'] = $courseID;
            $email_content = $this->_emailContentReportIncorrect($feedbackData);
            $mailerData['toEmail'] = 'joydeep.biswas@naukri.com';
            $mailerData['bccMail'] = 'simrandeep.singh@shiksha.com';
            //$mailerData['ccMail'] = 'shamender.srivastav@shiksha.com';
            $mailerData['emailSubject'] = $email_content['subject'];
            $mailerData['emailContent'] = $email_content['content'];
           /* $response	= $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$email,$subject,$content,"html",'','n',array(), $ccEmail, $bccEmail);*/
        }
        else{
            // send the mail to admin
            //prepare email content
            $feedbackData['for'] = 'admin';
            $email_content = $this->_emailContentFeedback($feedbackData);
            //set subject, content, to email
            $mailerData['toEmail'] = SA_ADMIN_EMAIL;
            $mailerData['emailSubject'] = $email_content['subject'];
            $mailerData['emailContent'] = $email_content['content'];
            Modules::run('systemMailer/SystemMailer/studyAbroadFeedback',$mailerData);
            //$response	= $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$email,$subject,$content,"html",'');

            // send the mail to user
            //prepare email content
            $feedbackData['for'] = 'user';
            $email_content = $this->_emailContentFeedback($feedbackData);
            //set subject, content, to email
            $mailerData['toEmail'] = $feedbackData['email'];
            $mailerData['emailSubject'] = $email_content['subject'];
            $mailerData['emailContent'] = $email_content['content'];

            //$response	= $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$email,$subject,$content,"html",'');
            /**************** END : send  mail to user & admin ********************/
        }
        Modules::run('systemMailer/SystemMailer/studyAbroadFeedback',$mailerData);
        echo json_encode($result);
    }
    private function _emailContentFeedback($params)
    {
        $response_array = array();
        if($params['for'] == 'admin')
        {
            $response_array['subject'] = "New contact me request";
        }
        else
        {
            $response_array['subject'] = "Thank you for contacting us";
        }
        $response_array['content'] = $this->load->view('user/shiksha_feedback_mail_to_'.$params['for'],$params,true);
        return $response_array;
    }


    function generatePdf($courseId){

        $this->benchmark->mark('code_start');
        if($courseId == "") {
            die("No listingTypeId defined");
        }

        $listingebrochuregenerator = $this->load->library('listing/ListingEbrochureGenerator');
        $urlArray = $listingebrochuregenerator->genearteAbroadCourseEbrochure($courseId);

        $this->benchmark->mark('code_end');
        echo "<br> Total time = ".$this->benchmark->elapsed_time('code_start', 'code_end');
        _p($urlArray); die;

        if($urlArray['RESPONSE'] == 'BROCHURE_FOUND') {
            $fileName = explode("listingsBrochures/", $urlArray['BROCHURE_URL']);
            $filePath = "/var/www/html/shiksha/mediadata/listingsBrochures/".$fileName[1];
            header('Content-disposition: attachment; filename='.$fileName[1]);
            header('Content-type: application/pdf');
            readfile($filePath);
        }
    }


    public function readRemoveCookieForBrochureDownload(){
        $cookieVal = $_COOKIE['initiateBrochureDownload'];
        setcookie('initiateBrochureDownload', 0 , -1,'/',COOKIEDOMAIN);
        $_COOKIE['initiateBrochureDownload'] = 0;
        return json_decode($cookieVal,TRUE);
    }

    public function getSeoDescription($mandatoryData=array(),$listingType = ''){
        $seoDescription = '';
        switch($listingType){
            case	'university'	:	$seoDescription = 'View '.(($mandatoryData['totalCourseCount']>0)?$mandatoryData['totalCourseCount']:'').(($mandatoryData['totalCourseCount']>1)?' courses':' course').' offered by '.$mandatoryData['universityName'].'. See fees, exam cutoffs, scholarships and admissions process.';
                break;
            case	'department'	:	$mandatoryData['departmentHighlights'] = preg_replace('/[^(\x20-\x7F)]*/','',strip_tags($mandatoryData['departmentHighlights']));
                $seoDescription = $mandatoryData['departmentName'].' in '.$mandatoryData['universityName'].' - '.$mandatoryData['departmentHighlights'];
                break;
            case	'course'	:	$seoDescription = "View details for ".htmlentities($mandatoryData['courseName'])." at ".htmlentities($mandatoryData['universityName']).". See details like fees, admissions, scholarship and others.";
                break;
        }
        $seoDescription = substr($seoDescription,0,250);
        return $seoDescription;
    }

    public function getContentOrgData(){
        $lib = $this->load->library('abroadContentOrg/abroadContentOrgLib');
        return $lib->getUrlsForWidget();
    }

    public function getFormForIncorrectInformation()
    {
        $data = array();
        $validity = $this->checkUserValidation();
        if (! (($validity == "false") || ($validity == ""))) {
            $data ['userId'] = $validity [0] ['userid'];
            $user_data = $validity[0];
            $cookie_str = $user_data['cookiestr'];
            $cookie_str_array  = explode("|", $cookie_str);
            $data['email'] = $cookie_str_array[0];
        }else{
            $data['email'] = '';
        }

        $data['entityType'] = $this->input->post('entityType');
        $data['session_id'] = sessionId();
        //_p($data);
        echo $this->load->view('listing/abroad/widget/reportIncorrect',$data);
    }

    private function _emailContentReportIncorrect($params)
    {
        $response_array = array();
        $response_array['subject'] = "A user has reported incorrect information on Course id ".$params['courseID'];
        $response_array['content'] = $this->load->view('listing/abroad/widget/reportIncorrectMailAdmin',$params,true);
        error_log('data_is'.print_r($response_array,true));
        return $response_array;
    }

    /*
     * required in indexer's code, calls library to get category, subcategory of given course id.
     */
    public function getCategoryOfAbroadCourse($courseId)
    {
        $this->load->library('listing/AbroadListingCommonLib');
        $this->abroadListingCommonLib = new AbroadListingCommonLib();
        return $this->abroadListingCommonLib->getCategoryOfAbroadCourse($courseId);
    }

    public function _prepareDataForConsultantTab(& $displayData,$listingType,$listingObj)
    {
        $consultantPageLib 			= $this->load->library('consultantProfile/ConsultantPageLib');
        $displayData['currentRegion'] 	= $consultantPageLib->getRegionBasedOnIP();
        $consultantData 			= $this->abroadListingCommonLib->getConsultantData($listingType,$listingObj);
        $regionConsultantMapping = array();
        foreach($consultantData as $cData)
        {
            foreach($cData['regions'] as $regionId=>$regionData)
            {
                $regionConsultantMapping[$regionId]['consultantIds'][] 	= $cData['consultantId'];
                $regionConsultantMapping[$regionId]['regionName'] 		= $regionData['name'];
            }
        }
        $activeRegionData 		= $regionConsultantMapping[$displayData['currentRegion']['regionId']];
        if(empty($activeRegionData))
        {
            $activeRegionData 	 = array($displayData['currentRegion']['regionId']=>array('consultantIds'=>array(),'regionName'=>$displayData['currentRegion']['regionName']));
            $regionConsultantMapping = $activeRegionData + $regionConsultantMapping;
        }
        else
        {
            $val = $regionConsultantMapping[$displayData['currentRegion']['regionId']];
            unset($regionConsultantMapping[$displayData['currentRegion']['regionId']]);
            $regionConsultantMapping = array($displayData['currentRegion']['regionId'] => $val) + $regionConsultantMapping;
        }
        // We also need to rotate the consultantIds within the regionConsultantMapping here so that it works.
        $regionConsultantMapping 			= $this->_rotateConsultantIds($regionConsultantMapping);
        $displayData['activeRegionData'] 		= $activeRegionData;
        $displayData['consultantData'] 		= $consultantData;
        $displayData['regionConsultantMapping'] 	= $regionConsultantMapping;
    }
    /*
    	* This function prepares application data for the course which is shiksha apply enabled
    	* it also takes care if the course is mapped to a shiksha apply enabled univerity
    	* and sets applicationProcessDataFlag as 1
     	* else sets applicationProcessDataFlag as 0
    */
    public function _prepareDataForApplicationProcessTab(& $displayData,$listingType,$listingObj){

        $applicationProcessData = $this->abroadListingCommonLib->getApplicationProcessData($listingObj->getId());
        if(count($applicationProcessData['submissionDateData'])>0)
        {
            $tempsubmissionDateDataArray = $applicationProcessData['submissionDateData'];
        }
        $applicationProcessData =  reset($applicationProcessData);
        if(count($tempsubmissionDateDataArray)>0)
        {
            $applicationProcessData['submissionDateData'] = $tempsubmissionDateDataArray;
        }

        if(!empty($applicationProcessData) && count($applicationProcessData)>0){

            $displayData['applicationProcessDataFlag'] = 1;

            $stepByStepFlag = array('step1'=>false,'step2'=>false,'step3'=>false,'step4'=>false,'step5'=>false);

            if($applicationProcessData['sopComments']!='' || $applicationProcessData['lorComments']!='' || $applicationProcessData['essayComments']!='' || $applicationProcessData['cvComments']!='')
            {
                $stepByStepFlag['step1'] = true;
            }
            if($applicationProcessData['allDocuments']!=''){
                $stepByStepFlag['step2'] = true;
            }
            if($applicationProcessData['applyNowLink']!=''){
                $stepByStepFlag['step3'] = true;
            }
            if($applicationProcessData['applicationFeeDetail']==1){
                $stepByStepFlag['step4'] = true;
                $feeData = $this->abroadListingCommonLib->convertCurrency($applicationProcessData['currencyId'], 1, $applicationProcessData['feeAmount']);
                if($feeData)
                {
                    //$applicationProcessData['convertedFeeDetail'] = $feeData;
                    $applicationProcessData['convertedFeeDetail'] = $this->abroadListingCommonLib->getIndianDisplableAmount($feeData, 2);
                }

                //_p($nFees);die;
            }elseif($applicationProcessData['feeDetails'] !=''){
                $stepByStepFlag['step4'] = true;
            }elseif($applicationProcessData['isCreditCardAccepted'] || $applicationProcessData['isDebitCardAccepted'] || $applicationProcessData['iswiredMoneyTransferAccepted'] || $applicationProcessData['isPaypalAccepted']){
                $stepByStepFlag['step4'] = true;
            }
            if(count($applicationProcessData['submissionDateData'])>0){
                $stepByStepFlag['step5'] = true;
            }
            $displayData['stepByStepFlag'] = $stepByStepFlag;
            $displayData['applicationProcessData'] = $applicationProcessData;

            //prepare application tab right side widget data
            $data =$this->abroadListingCommonLib->getApplicationProcessRightWidgetData();
            if($data!=false)
            {
                $displayData['applicationProcessRightWidgetData'] = $data;
            }
        }else{
            $displayData['applicationProcessDataFlag'] = 0;
        }
    }

    private function _rotateConsultantIds($regionData){
        $curTime = intval(date('H'))*60+intval(date('i'));
        $rotationCount = intval(($curTime)/15);
        foreach($regionData as $regionId=>$data){
            $tConIds = $data['consultantIds'];
            $tRotationCount = $rotationCount % count($tConIds);
            for($i = 0; $i < $tRotationCount; $i++){
                $ele = array_pop($tConIds);
                $tConIds = array_merge(array($ele), $tConIds);
            }
            $regionData[$regionId]['consultantIds'] = $tConIds;
        }
        return $regionData;
    }


    public function consultantCatPageOverlayData()
    {
        $displayData 		= array();
        $listingType 		= $this->input->post('listingType');
        $listingId 		= $this->input->post('listingId');
        $courseId 		= $this->input->post('courseId');
        $this->_init($displayData, $listingId, $listingType);
        $listingBuilder 			= new ListingBuilder;
        $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
        $listingObj 				= $this->abroadUniversityRepository->find($listingId);
        $this->_prepareDataForConsultantTab($displayData,$listingType,$listingObj);
        //send lisitng ID as university Id to the excluded courses function then unset all the consultants which have excluded courses

        $this->abroadListingCommonLib->checkForConsUnivExcludedCourses($listingId,$displayData);
        $displayData['courseId'] = $courseId;
        //print_r($displayData);
        echo $this->load->view('/consultantEnquiry/ConsultantCatPageHelp',$displayData);
    }

    function getEntryRequirementsData(&$displayData)
    {
        //data based on shiksha apply is already fetched
        //fetch data for eligible exams in sorted order as defined sortEligibilityExamForAbroadCourses();
        $this->AbroadListingCommonLib 	 = $this->load->library("listing/abroadListingCommonLib");
        $tempCourseObj 					 = reset($this->abroadListingCommonLib->sortEligibilityExamForAbroadCourses(array($displayData['courseObj'])));
        $tempCourseObj 					 = reset($this->abroadListingCommonLib->rearrangeCustomExams(array($tempCourseObj)));
        //get Exam Which are availble with Exam guide
        $displayData['eligibilityExams'] =  $tempCourseObj->getEligibilityExams();
        $examIds = array_filter(array_map(function($e){ $id = $e->getId(); if($id>0){ return $id; } },$displayData['eligibilityExams']));
        $displayData['examWithGuide'] 	 = $this->abroadListingModel->getEligibleExamWithGuide($examIds);
        unset($tempCourseObj);
    }

    public function abroadListingPage()
    {
        $this->abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
        $currentUrl = getCurrentPageURLWithoutQueryParams();
        $currentUrl = trim($currentUrl,'/');
        $getArr = array('refCourseId', 'showDepts','profiling','scrollTo');
        $flag = false;
        $finalGet = array();
        foreach ($_GET as $key => $value) {
            if(in_array($key, $getArr)){
                $finalGet[] = $key.'='.$this->input->get($key, true);
            }else{
                $flag = true;
            }
        }
        if($flag){
            redirect($currentUrl.'?'.implode('&', $finalGet), 'location');
            die;
        }
        $currentUrl = str_replace(SHIKSHA_STUDYABROAD_HOME, '', $currentUrl);
        $listingData = $this->abroadListingCommonLib->getListingIdByUrl($currentUrl);
        if($listingData['error']!=''){
            show_404_abroad();
        }else if(count($listingData)>0 && $listingData['listing_type']=='course')
        {
            $this->courseListing($listingData['listing_type_id']);
        }else if(count($listingData)>0 && $listingData['listing_type']=='university')
        {
            $this->universityListing($listingData['listing_type_id']);
        }
    }



}
