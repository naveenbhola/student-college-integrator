<?php
class ListingPage extends MX_Controller {
    //put your code here
    private $abroadListing;
    private function _init(& $displayData,$typeId,$type = 'course'){
        $this->load->builder('ListingBuilder','listing');
        $this->load->library('listing/AbroadListingCommonLib');
        $this->load->model('listing/abroadlistingmodel');

        $this->abroadListingCommonLib = new AbroadListingCommonLib();
        $this->abroadListingModel = new abroadlistingmodel();

        $this->load->library('responseAbroad/ResponseAbroadLib');
        $this->responseAbroadLib = new ResponseAbroadLib();
        $displayData['validateuser'] = $this->checkUserValidation();
        $displayData['listingType'] = $type;
        $displayData['listingTypeId'] = $typeId;

        $this->config->load('studyAbroadListingConfig');

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

    private function _validateEntityId($entityId) {
        if($entityId == "" || !is_numeric($entityId)) {
            show_404_abroad();
        }
    }

    private function _initCourse(&$displayData,$courseId)
    {
        $listingBuilder 			= new ListingBuilder;
        $this->abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
        $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();

        $courseData = $this->abroadCourseRepository->find($courseId);
        if(!is_object($courseData) || $courseData instanceof Course || $courseData->getId() == "")
        {
            show_404_abroad();
        }

        if($courseData->getId() == ""){
            show_404_abroad();
        }

        $displayData['courseObj'] 			= $courseData;
        $universityData 					= $this->abroadUniversityRepository->find($courseData->getUniversityId());
        $displayData['universityObj'] 		= $universityData;
        $dataArray 							= $this->abroadListingCommonLib->getCategoryOfAbroadCourse($courseId);
        $displayData["courseCategoryId"] 	= $dataArray['categoryId'];
        $displayData["courseSubCategoryId"] = $dataArray['subcategoryId'];
        $abroadCoursesArray 				= $this->abroadListingCommonLib->sortEligibilityExamForAbroadCourses(array($displayData['courseObj']->getId() => $displayData['courseObj']),$displayData["courseCategoryId"]);
        $displayData['courseObj'] 			= $abroadCoursesArray[$displayData['courseObj']->getId()];

        $seoRelatedRequireData = array(
            'courseName'		=> $displayData['courseObj']->getName(),
            'universityName'	=> $displayData['courseObj']->getUniversityName(),
            'courseHighlights'	=> $displayData['courseObj']->getCourseDescription()
        );
        $displayData['seoDescription'] = $this->abroadListing->getSeoDescription($seoRelatedRequireData,'course');
    }


    public function getCourseOverviewData($courseId, &$displayData){
        // get the fees of the course in indian currency
        $feesCurrency = $displayData['courseObj']->getFees()->getCurrency();
        $feesVal = $displayData['courseObj']->getFees()->getValue();
        $courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $feesVal);
        $displayData['courseFeesIndianAmount'] = $courseFees;
        if($courseFees){
            $courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 2);
        }
        $displayData['courseFeesDisplableAmount'] = $courseFees;

        // get the eligibility exam of the course according to the order given


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
        }

        $displayData["averageSalary"] = $averageSalaryFinal;

        //$this->_checkFirstTimeVisitor($displayData);

    }


    private function _validateUrl($entityUrl) {
        $userEnteredURL = getCurrentPageURLWithoutQueryParams();
        $userEnteredURL = trim($userEnteredURL);
        if($userEnteredURL != $entityUrl) {
            redirect($entityUrl, 'location', 301);
        }
    }

    function courseListing($courseId){
        $this->_validateEntityId($courseId);
        $displayData 					= array();
        $displayData['trackForPages'] = true; //For JSB9 Tracking
        $listingType 					= 'course';
        $this->abroadListing 			= Modules::load('listing/abroadListings');
        $this->_init($displayData,$courseId,$listingType);
        $this->_initCourse($displayData,$courseId);
        $this->_validateUrl($displayData['courseObj']->getUrl());
        $listingLib 					= $this->load->library('listingPage/listingPageLib');
        $listingLib->addCourseToRecentViewedCourses($courseId);
        $this->getCourseOverviewData($courseId,$displayData);

        $feeDetails = $this->abroadListingCommonLib->getCourseFeesDetails(array($displayData['courseObj']));
        $displayData = array_merge($displayData, $feeDetails[$courseId]);

        $this->abroadListingCommonLib->getPlacementTab($displayData);
        $displayData['seoData'] 			= $displayData['courseObj']->getMetaData();
        $displayData['seoData']['seoURL'] 	= $displayData['courseObj']->getUrl();
        $displayData['alsoViewedCourses'] 	= $this->abroadListing->getAbroadRecommendations('alsoViewed', $courseId, '', '','', '','','','',  'categoryPageMobileSA');
        //not in use//$displayData['hasCouncellor'] 	= $this->abroadListingCommonLib->checkIfUniversityHasCounsellor($displayData['courseObj']->getUniversityId());
        $userStartTimePrefWithExamsTaken 	= $this->responseAbroadLib->getUserStartTimePrefWithExamsTaken($displayData['validateuser']);

        //get data for application process widget
        $this->_prepareDataForApplicationProcessTab($displayData,'course',$displayData['courseObj']);
        $displayData['courseApplicationEligibilityDetails']=
            $this->abroadListingCommonLib->getCourseApplicationEligibilityData($displayData['courseObj']->getId());

        //get data for entry requirements exams subsection
        $this->getEntryRequirementsExamData($displayData);

        //Get the articles for the course page
        $displayData['popularArticles'] 	= $this->_getArticlesForCourse($displayData);

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
            'sourcePage'       => 'course',
            'courseId'         => $displayData['courseObj']->getId(),
            'courseName'       => $displayData['courseObj']->getName(),
            'universityId'     => $displayData['courseObj']->getUniversityId(),
            'universityName'   => $displayData['universityObj']->getName(),
            'userStartTimePrefWithExamsTaken' => $userStartTimePrefWithExamsTaken,
            'destinationCountryId'	=> $displayData['universityObj']->getLocation()->getCountry()->getId(),
            'destinationCountryName'	=> $displayData['universityObj']->getLocation()->getCountry()->getName(),
            'courseData'       => base64_encode(json_encode($courseData)), // send data now required for new single registration form
            'userDesiredCourse'=> $userStartTimePrefWithExamsTaken['desiredCourse'],
            'mobile'           => true
        );
        $displayData['brochureDataObj'] = $brochureDataObj;
        // Check if this course is shortlisted already!
        $userShortlistedCourses = Modules::run("categoryList/AbroadCategoryList/fetchIfUserHasShortListedCourses");
        $userShortlistedCourses = $userShortlistedCourses['courseIds'];
        if(in_array($courseId,$userShortlistedCourses)){
            $displayData['isShortlisted'] = true;
        }else{
            $displayData['isShortlisted'] = false;
        }

        $cookieToInitiateResponseCalls 				= $this->readRemoveCookieForBrochureDownload();
        $displayData['initiateBrochureDownload'] 	= $cookieToInitiateResponseCalls['initiateBrochureDownload'];
        $displayData['initiateCallback'] 			= $cookieToInitiateResponseCalls['initiateCallback'];
        // set variables that will decide whether viewed response would be created or not.
        $this->responseAbroadLib->checkAndSetDataForAutoResponse($displayData['courseObj'], $displayData);       
        $displayData['universityObj'] = $this->getMediaForAbroadListing($displayData['courseObj']->getUniversityId(),TRUE);
		
		$displayData['rateMyChanceCtlr'] = Modules::load('rateMyChancePage/rateMyChance');
		if($displayData['validateuser'] != "false"){
			$shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
			$displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
		}else{
			$displayData['userRmcCourses'] = array();
		}
		$displayData['compareCookiePageTitle'] = $displayData['courseObj']->getName();
		$displayData['compareLayerTrackingSource'] = 602;
		$displayData['compareButtonTrackingId'] = 660;
		$this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
		$displayData['userComparedCourses'] = $this->compareCourseLib->getUserComparedCourses();
		//tracking
		$this->_prepareTrackingData($displayData);
		$compareWidgetData = array(
		    'courseLevel' => $displayData['courseObj']->getCourseLevel1Value(),
		    'countryId' => $displayData['courseObj']->getCountryId(),
		    'courseCategoryId' => $displayData['courseCategoryId'],
		    'courseSubCategoryId' => $displayData['courseSubCategoryId'],
		    'isMobile' => true,
		    'courseId' => $courseId,
		    'courseData' => $displayData['alsoViewedCourses']
	    );
		$photos = $displayData['universityObj']->getPhotos();
		if(count($photos)>0){       
                        $displayData['imgURL'] = $photos[0]->getThumbURL('172x115');
                }
                else{
                        $displayData['imgURL'] = SHIKSHA_STUDYABROAD_HOME."/public/images/defaultCatPage1.jpg";
                }
	
		$displayData['compareData'] = $this->abroadListingCommonLib->getCompareCourseWidgetData($compareWidgetData);
		$displayData['recommendedCompareCourseData'] = array_values($displayData['compareData']['recommendedCompareCourseData'])[0];
		$displayData['scholarshipCardData'] = $this->abroadListingCommonLib->getScholarshipsWidgetData($displayData);
        $displayData['fatFooterData'] = $this->abroadListingCommonLib->getFatFooterWidgetData($displayData);
        $displayData['jqMobileFlag'] = true;
		$this->load->view('listingPage/courseOverview',$displayData);
    }

    private function _prepareScholarshipShortDescriptionDetails(& $displayData,$courseObj){

        $textLength = 100;
        $scholarshipShortDescription = '';
        $displayData['scholarshipShortFlag'] = true;
        if($courseObj->getScholarshipDescription() !='' && strlen($courseObj->getScholarshipDescription()) >$textLength)
        {
            $scholarshipShortDescription = formatArticleTitle($courseObj->getScholarshipDescription(),$textLength);
            $scholarshipShortDescriptionHeading = "Scholarship Description";
        }
        elseif($courseObj->getScholarshipDescription() !='' && strlen($courseObj->getScholarshipDescription()) < $textLength && $courseObj->getScholarshipEligibility()!='')
        {
            $scholarshipShortDescription = $courseObj->getScholarshipDescription();
            $scholarshipShortDescriptionHeading = "Scholarship Description";
        }
        elseif($courseObj->getScholarshipEligibility()!='' && strlen($courseObj->getScholarshipEligibility()) >$textLength)
        {
            $scholarshipShortDescription = formatArticleTitle($courseObj->getScholarshipEligibility(),$textLength);
            $scholarshipShortDescriptionHeading = "Scholarship Eligibility";
        }
        elseif($courseObj->getScholarshipEligibility()!='')
        {
            $scholarshipShortDescription = $courseObj->getScholarshipEligibility();
            $scholarshipShortDescriptionHeading = "Scholarship Eligibility";
        }else{
            $displayData['scholarshipShortFlag'] = false;
            return;
        }

        $displayData['scholarshipShortDescription'] = $scholarshipShortDescription;
        $displayData['scholarshipShortDescriptionHeading'] = $scholarshipShortDescriptionHeading;
    }

    private function _prepareTrackingData(&$displayData)
    {

        if($displayData['listingType']=='course')
        {
            $desiredCourseId = $displayData['courseObj']->getDesiredCourseId()?$displayData['courseObj']->getDesiredCourseId():0;
        }
        else
        {
            $desiredCourseId=0;
        }

        $displayData['googleRemarketingParams'] = array(
            "categoryId" => ($displayData['courseCategoryId'] == '' ? 0 : $displayData['courseCategoryId']),
            "subcategoryId" => ($displayData['courseSubCategoryId'] == '' ? 0 : $displayData['courseSubCategoryId']),
            "desiredCourseId" => $desiredCourseId,
            "countryId" => reset($displayData['universityObj']->getLocations())->getCountry()->getId(),
            "cityId" => reset($displayData['universityObj']->getLocations())->getCity()->getId(),

        );

        if($displayData['listingType']=='course')
        {
            $displayData['googleRemarketingParams']["universityId"] = $displayData['courseObj']->getUniversityId();
            $courseLevel =$displayData['courseObj']->getCourseLevel1Value();
        }


        $displayData['beaconTrackData'] = array(
            'pageIdentifier' =>  $displayData['listingType'].'Page',
            'pageEntityId' =>  $displayData['listingTypeId'],
            'extraData' => array(
                'categoryId' => $displayData['googleRemarketingParams']['categoryId'],
                'subCategoryId' => $displayData['googleRemarketingParams']['subcategoryId'],
                'LDBCourseId' => $displayData['googleRemarketingParams']['desiredCourseId'],
                'cityId' => $displayData['googleRemarketingParams']['cityId'],
                'stateId' => $state = reset($displayData['universityObj']->getLocations())->getState()->getId(),
                'countryId' => $displayData['googleRemarketingParams']['countryId'],
                'courseLevel' => $courseLevel
            )
        );
        //_p($displayData['beaconTrackData']);die;
    }

    public function _prepareDataForConsultantWidget(&$displayData,$listingType,$listingObj){
        $consultantPageLib = $this->load->library('consultantProfile/ConsultantPageLib');
        $displayData['currentRegion'] = $consultantPageLib->getRegionBasedOnIP();
        $consultantData = $this->abroadListingCommonLib->getConsultantData($listingType,$listingObj);
        $regionConsultantMapping = array();
        foreach($consultantData as $cData){
            foreach($cData['regions'] as $regionId=>$regionData){
                $regionConsultantMapping[$regionId]['consultantIds'][] = $cData['consultantId'];
                $regionConsultantMapping[$regionId]['regionName'] = $regionData['name'];
            }
        }
        $activeRegionData = $regionConsultantMapping[$displayData['currentRegion']['regionId']];
        if(empty($activeRegionData)){
            $activeRegionData = array($displayData['currentRegion']['regionId']=>array('consultantIds'=>array(),'regionName'=>$displayData['currentRegion']['regionName']));
            $regionConsultantMapping = $activeRegionData + $regionConsultantMapping;
        }else{
            $val = $regionConsultantMapping[$displayData['currentRegion']['regionId']];
            unset($regionConsultantMapping[$displayData['currentRegion']['regionId']]);
            $regionConsultantMapping = array($displayData['currentRegion']['regionId'] => $val) + $regionConsultantMapping;
        }
        // We also need to rotate the consultantIds within the regionConsultantMapping here so that it works.
        $regionConsultantMapping = $this->_rotateConsultantIds($regionConsultantMapping);
        $displayData['activeRegionData'] = $activeRegionData;
        $displayData['consultantData'] = $consultantData;
        $displayData['regionConsultantMapping'] = $regionConsultantMapping;
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

    private function readRemoveCookieForBrochureDownload(){
        $cookieVal = $_COOKIE['initiateBrochureDownload'];
        setcookie('initiateBrochureDownload', 0 , -1,'/',COOKIEDOMAIN);
        $_COOKIE['initiateBrochureDownload'] = 0;
        return json_decode($cookieVal,TRUE);
    }

    public function getMediaForAbroadListing($universityId,$returnUniversityObject = FALSE) {
        if(empty($universityId)){
            return;
        }
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $universityRepository = $listingBuilder->getUniversityRepository();
        $displayData = array();
        $displayData['universityObj'] = $universityRepository->find($universityId);
        if($returnUniversityObject){
            return $displayData['universityObj'];
        }
        $this->load->view('listingPage/widgets/coursePhotoVideo',$displayData);
    }
    /*
     *The main function for Study Abroad University Page on Mobile
    */
    public function universityListing($universityId){
        //echo 'in_mobile5_university_listing';
        $this->_validateEntityId($universityId);
        $displayData 			= array();
        $displayData['trackForPages'] = true; //For JSB9 Tracking
        $displayData['listingType'] 	= 'university';

        $this->_init($displayData, $universityId, $displayData['listingType']);
        $this->_initUniversity($displayData,$universityId);
        $this->abroadListing = Modules::load('listing/abroadListings');
        $listingLib = $this->load->library('listingPage/listingPageLib');

        $universityCourseBrowseSectionByStream = $this->abroadListing->getCourseBrowseSectionData($displayData['universityObj'],$this->abroadListingCommonLib);
        $displayData['universityCourseBrowseSectionByStream'] = $universityCourseBrowseSectionByStream;
        //This Zero reult condition is diffrent from desktop because on mobile we will not show snapshot courses
        if(empty($universityCourseBrowseSectionByStream['urls']['courses']) || count($universityCourseBrowseSectionByStream['urls']['courses'])==0){
            //Redirect to country page! if iniversity has no courses
            $this->load->library('categoryList/AbroadCategoryPageRequest');
            $abroadCategoryPageRequest = new AbroadCategoryPageRequest();
            redirect($abroadCategoryPageRequest->getURLForCountryPage($displayData['universityObj']->getLocation()->getCountry()->getId()),'location',301);
        }

        //prepare SEO description data
        $totalCoursesCount = count($universityCourseBrowseSectionByStream['urls']['courses']);
        $seoRelatedRequiredData = array('totalCourseCount' => $totalCoursesCount,'universityName' => $displayData['universityObj']->getName());
        $displayData['seoDescription'] = $this->abroadListing->getSeoDescription($seoRelatedRequiredData,'university');

        //prepare find course widget
        $displayData['findCourseWidgetData']  = $listingLib->prepareDataForUniversityFindCourseWidget($universityCourseBrowseSectionByStream['stream']);

        //prepare Cost of Living and Accommodation Widget Data
        $campusAccomodation = $this->abroadListingCommonLib->getUniversityCampusAccomodation(array($displayData['universityObj']));
        $displayData = array_merge($displayData, $campusAccomodation[$displayData['universityObj']->getId()]);

        //prepare University Contact Details Data
        //$this->_getUniversityContactInfo($displayData);
        $universityContactDetails = $this->abroadListingCommonLib->getUniversityContactInfo(array($displayData['universityObj']));
        $displayData = array_merge($displayData, $universityContactDetails[$displayData['universityObj']->getId()]);
        //preparing the popular courses widget
        $coursesByStreams = $displayData['findCourseWidgetData']['dataByStream'];
        $courseIds = array();
        foreach($coursesByStreams as $coursesByStream){
            $courseIds = array_merge($courseIds,$coursesByStream);
        }
        $referrerCourseId = $this->input->get("refCourseId");
        $referrerCourseId = $this->security->xss_clean($this->input->get("refCourseId"));
        $pos= strpos($referrerCourseId,'?'); // prevent exception for malformed url by sales
        if($pos !== false && is_numeric($pos))
        {
            $referrerCourseArr = explode('?',$referrerCourseId );
            $referrerCourseId = $referrerCourseArr[0];
        }

        $popularCoursesData = $this->abroadListingCommonLib->processPopularCourses($displayData['universityCourseBrowseSectionByStream']['courses'],$referrerCourseId,true);
        $displayData['popularCourses'] = $popularCoursesData['popularCourses'];
        $displayData['allCourseViewCounts'] = $popularCoursesData['allCourseViewCounts'];
        $displayData['abroadListingCommonLib'] = $this->abroadListingCommonLib;

        //get user preference, education data (to populate when do you plan to start?, exams taken)
//        $userStartTimePrefWithExamsTaken = $this->responseAbroadLib->getUserStartTimePrefWithExamsTaken($displayData['validateuser']);

        //prepare data for brochure download on downlod brochure buttons
        $brochureDataObj = array(
            'sourcePage'       => 'university',
            'universityId'     => $displayData['universityObj']->getId(),
            'universityName'   => $displayData['universityObj']->getName(),
            'mobile'           => true,
            'destinationCountryId'	=> $displayData['universityObj']->getLocation()->getCountry()->getId(),
            'destinationCountryName'=> $displayData['universityObj']->getLocation()->getCountry()->getName()
            //prepare courseData that will be used in inline download brochure form
            //'courseData'		=> base64_encode(json_encode($this->responseAbroadLib->getCourseDataForBrochureDownload($universityCourseBrowseSectionByStream['courses'],false,true))),
        );
        $displayData['brochureDataObj'] = $brochureDataObj;

        //People who viewed this university also viewed Widget Data
        $displayData['alsoViewedUniversityData'] = $this->abroadListingCommonLib->getDetailofAlsoViewedUniversityByUniversityId($universityId,$this->abroadUniversityRepository,4,true);

        $scholarshipParams = array('university'=>array($displayData['universityObj']->getId()),
            'type'=>'country','country'=>array($displayData['universityObj']->getLocation()->getCountry()->getId()),'countryName'=>array($displayData['universityObj']->getLocation()->getCountry()->getName()));
        $scholarshipCategoryPageLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageLib');
        $displayData['scholarshipCardData'] = $scholarshipCategoryPageLib->getScholarshipTupleDataForInterlinking($scholarshipParams,4,'ULP');
        $displayData['scholarshipCardData']['countryName'] = $displayData['universityObj']->getLocation()->getCountry()->getName();

        //prepare Google Marketing Params Data



        //tracking
        $this->_prepareTrackingData($displayData);

        $this->load->view('listingPage/universityOverview',$displayData);
    }

    private function _initUniversity(&$displayData,$universityId) {
        $listingBuilder = new ListingBuilder();
        $this->abroadUniversityRepository = $listingBuilder->getUniversityRepository();
        $universityData = $this->abroadUniversityRepository->find($universityId);
        if($universityData->getId() == ''){
            show_404_abroad();
        }
        $this->_validateUrl($universityData->getURL());
        $displayData['universityObj'] 	= $universityData;
    }

    private function _getArticlesForCourse($displayData){
        $articleCount = 4;
        $courseCategoryId = reset($this->abroadListingCommonLib->getCategoryOfAbroadCourse($displayData['courseObj']->getId()));
        $courseSubCategoryId = $displayData['courseObj']->getCourseSubCategory();
        $data = array($displayData['courseObj'], $courseCategoryId, $courseSubCategoryId, $articleCount,'includeGuides'=>true);
        $ref = Modules::run('studyAbroadArticleWidget/articleAbroadWidgets/getArticlesForCoursePage', $data);
        return reset(json_decode($ref));
    }

    public function getAbroadRecommendations($customDataForReco = array())
    {
        if(!empty($customDataForReco))
        {
            $courseId 								= $customDataForReco['courseId'];
            $displayData['widgetName'] 				= $customDataForReco['widget'];
            $displayData['source'] 	   				= $this->input->post('sourcePage');
            $displayData['isRecommendationPage'] 	= true;
            $displayData ['trackingPageKeyId'] 		= $customDataForReco['trackingPageKeyId'];
            $displayData ['shortlistTrackingPageKeyId'] 		= $customDataForReco['shortlistTrackingPageKeyId'];
            $displayData ['compareTrackingPageKeyId'] 		= $customDataForReco['compareTrackingPageKeyId'];
            $displayData ['rmcRecoTrackingPageKeyId'] 		= $customDataForReco['rmcRecoTrackingPageKeyId'];
            $displayData['catPageTitle'] 			= $customDataForReco['title'];
            $displayData['refererTitle']            = $customDataForReco['refererTitle'];
            $displayData['customReferer']            = $customDataForReco['referer'];
            $displayData['isThankYouPage'] 	= true;
        }else{
            $courseId 								= $this->input->post('courseId');
            $displayData['widgetName'] 				= $this->input->post('widgetName');
            $displayData['source'] 	   				= $this->input->post('sourcePage');
            $displayData['isRecommendationPage'] 	= true;
            $displayData ['trackingPageKeyId'] 		= $this->input->post('trackingPageKeyId');
            $displayData ['shortlistTrackingPageKeyId'] 		='';
            $displayData ['rmcRecoTrackingPageKeyId'] 		= '';
            $displayData['catPageTitle'] 			= $this->input->post('catPageTitle');
        }
        if($courseId >0){
            $this->abroadListing = Modules::load('listing/abroadListings');
            $displayData['alsoViewedCourses']   = $this->abroadListing->getAbroadRecommendations('alsoViewed', $courseId, '', '',$trackingPageKeyId,'',$displayData['shortlistTrackingPageKeyId'],$displayData['rmcRecoTrackingPageKeyId'],'', 'mobileSA');

            
            if(count($displayData['alsoViewedCourses']) >0){

                $listingLib = $this->load->library('listingPage/listingPageLib');
                $sourceWidegtData = $listingLib->getActionTypeForRecoLayerBySourcePage($displayData['source']);
                //_p($sourceWidegtData);
                if($sourceWidegtData['sourcePage']!='')
                {
                    $displayData['widgetName'] = $sourceWidegtData['widgetName'];
                    $displayData['source']     = $sourceWidegtData['sourcePage'];
                }else{
                    $displayData['widgetName'] = $sourceWidegtData['widgetName'];
                    $displayData['source']     = $sourceWidegtData['sourcePage'];
                }

                $this->load->builder('ListingBuilder','listing');
                $listingBuilder 				= new ListingBuilder;
                $abroadCourseRepository 		= $listingBuilder->getAbroadCourseRepository();
                $course 						= $abroadCourseRepository->find($courseId);
                $displayData['universityName'] 	= $course->getUniversityName();
                $displayData['courseName'] 		= $course->getName();

                $validateuser= $this->checkUserValidation();
                if (! (($validateuser == "false") || ($validateuser == ""))){
                    $cokkieStr = explode('|',$validateuser[0]['cookiestr']);
                    $displayData['email'] = $cokkieStr[0];

                    $data ['userId'] = $validateuser [0] ['userid'];
                    $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
                    $displayData['userShortlistedCourses'] = $shortlistListingLib->fetchIfUserHasShortListedCourses ( $data);
                    $displayData['userShortlistedCourses'] = $displayData['userShortlistedCourses']['courseIds'];
                }else{
                    echo "false";
                    return;
                }
                $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChancePage/rateMyChance');
                $displayData['validateuser'] 	 = $this->checkUserValidation();
                if($displayData['validateuser'] != "false"){
                    $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
                    $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
                }else{
                    $displayData['userRmcCourses'] = array();
                }
                if($displayData['isThankYouPage'] === true)
                {
                    $recoHTML = $this->load->view('listingPage/widgets/recommendations',$displayData,true);
                    return $recoHTML;
                }else{
                    $this->load->view('listingPage/widgets/recommendations',$displayData);
                }
            }else{
                if(!empty($customDataForReco) && $displayData['isThankYouPage'] == true)
                { return false; }
                else{ echo "false"; }
            }
        }
    }

    private function _prepareDataForApplicationProcessTab(& $displayData,$listingType,$listingObj){
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

    function getEntryRequirementsExamData(&$displayData)
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

    public function getListingPageUserProfileWidget(){
        if($this->input->is_ajax_request()){
            $courseIdArr = $this->input->post('courseIds', true);
            $fromPage    = $this->input->post('fromPage', true);
            $this->listingPageWidgetLib = $this->load->library('listingPage/listingPageWidgetLib');
            $userProfileData = $this->listingPageWidgetLib->getUserProfileWidgetData($courseIdArr, $fromPage);
            $displayData = array();
            if(count($userProfileData) < 3){
                echo '';
            }else{
                $displayData['userProfileData'] = $userProfileData;
                $applyHomeLib = $this->load->library('applyHome/ApplyHomeLib');
                $displayData['ratingData'] = $applyHomeLib->getStudyAbroadCounsellingRatingData();
                $displayData['starRatingWidth'] = $applyHomeLib->getStarRatingWidth(3.2,96,$displayData['ratingData']['overallRating']);
                echo $this->load->view('widgets/userProfileWidgetInnerData', $displayData, true);
            }
        }
    }
}
