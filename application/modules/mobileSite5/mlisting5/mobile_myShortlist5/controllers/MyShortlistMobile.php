<?php
class MyShortlistMobile extends ShikshaMobileWebSite_Controller {
	
	private $userStatus;

	function __construct(){
		parent::__construct();
	}
	
	function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		$this->userStatus = $this->checkUserValidation();
		$this->homepageUrl = '/my-shortlist-home';
		// $this->load->model('CA/campusconnectmodel');
		// $this->campusconnect = new CampusConnectModel();
	}

	/**
	 *
	 * Show My ShortList Homepage
	 * @param    None
	 * @return   View with the Homepage
	 *
	 */
	function myShortlist($params){
		$isRedirected = $this->redirectToCourseDetailsTabs($params);
		$this->checkQueryStringAndRedirectToBaseURL();

		if($isRedirected) {
			return;
		}
		
		$this->init();
		$displayData = array();
		if($this->input->get('addMoreColg')) {
			$displayData['addMoreColg']	= 1;
		}
		$displayData['userStatus'] 	 = $this->userStatus;
		$displayData['userId']       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['boomr_pageid'] = 'MobileMyShortlistHomepage';
		$displayData['homepageUrl']  = $this->homepageUrl;
		
		// to get location for find college by exam widget
		$registrationForm      = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB'); 
		$fields                = $registrationForm->getFields();
		$displayData['fields'] = $fields;
		
		$displayData['shortlistedCoursesIds'] = array();
		if($displayData['userStatus'] !== 'false') {
			$data = array();
			$data = modules::run('myShortlist/MyShortlist/getUserShortListedCoursesData');
			$displayData = array_merge($displayData, $data);
		} else {
			$displayData['addMoreColg']	= 0;
		}

		//Tracking Code
		$this->shortlistLib = $this->load->library('myShortlist/MyShortlistLib');
		$displayData['coursesWithPlacementData'] = $this->shortlistLib->checkPlacementDataForShortlist($displayData['shortlistedInstAndCourseIds']);
		$displayData['beaconTrackData'] = $this->shortlistLib->prepareBeaconTrackData();
		$displayData['isMyShortlistHomePage']    = 1;
		//$displayData['tracking_keyid'] = MOBILE_NL_SHORTLISTPAGE_SEARCHCOLLEGEADDMORE_COURSESHORTLIST;
		$displayData['tracking_keyid'] = 1109;
		$displayData['m_meta_title'] = "Shortlist Colleges Based on your Interest - Shiksha";
		$displayData['m_meta_description'] = "Not sure how to select a best college?. Shiksha's College shortlisting tool will help you to shortlist colleges based on your budget, eligibility and preferences.";
		$displayData['canonicalURL'] = SHIKSHA_HOME.'/resources/colleges-shortlisting';
		$this->load->view('mobile_myShortlist5/myShortlistHomepage',$displayData);
	}

	function redirect301(){
		$url = SHIKSHA_HOME.'/resources/colleges-shortlisting'; // new url
		header("Location: $url",TRUE,301);
		exit;
	}

    private function _parseURL($params) {
    	if(empty($params)) {
    		return;
    	}

    	$urlData = explode('-',$params);
    	if(!empty($urlData[0]) && is_numeric($urlData[0]) && !empty($urlData[1]) && in_array($urlData[1], array('notes','ask','reviews','placement'))) {
    		$urlParam['courseId'] = $urlData[0];
    		$urlParam['tab'] = $urlData[1];
    	}
    	return $urlParam;
    }

    private function redirectToCourseDetailsTabs($params){
    	$urlParam = $this->_parseURL($params);
    	if(empty($urlParam)) {
    		return false;
    	}

    	$this->showCourseDetailsTabs($urlParam['courseId'],$urlParam['tab']);
    	return true;
    }

	/**
	 * To show Institute Listing based on search criteria(exam, cutoff, location)
	 * @author   Aman Varshney <aman.varshney@shiksha.com>
	 * @date     2015-03-23
	 */
    function myShortlistInstituteSearch(){
    	$pageIdentifierInfo                = $this->setPageIdentifier();

		$this->config->load('ranking_config');
		// to load libraries 
		$this->init(array('listing/ShortlistListingLib','listing/AbroadListingCommonLib'),array('myShortlist/my_shortlist'));
		
		$displayData['userStatus']         = $this->userStatus;
		$displayData['userId']       	   = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['homepageUrl']  = $this->homepageUrl;
		
		$selectedCriteria                  = json_decode($_COOKIE['mobileMyshortlistSearchCriteria'],true);
		$displayData['examCutOffSelected'] = $selectedCriteria['examCutOffSelected'];
		$displayData['locationText']       = $selectedCriteria['locationText'];
		$displayData['examName']           =  $pageIdentifierInfo['examName'];
		$displayData['cutOff']             =  $pageIdentifierInfo['cutOff'];
		$displayData['location']           =  $pageIdentifierInfo['location'];
		$displayData['pageType']           = 'myShortlistMobile';
		$displayData['boomr_pageid']       = 'MobileMyShortlistSearchByExam';

		$type                              =  $_REQUEST['type'];
		$pageNumber                        =  $_REQUEST['start'];
		if($pageNumber                     != '' && $pageNumber > 1){
			$displayData['pageNumber']         = $pageNumber;
		}else{
			$displayData['pageNumber']         = 1;
		}
		
		// method to set the request and get data based on request from solr
		$shortlistListingLib               = new ShortlistListingLib();		
		$categoryPageInstitutes            = $shortlistListingLib->getInstituteDataFilterWise($displayData);
		
		$courseIds              = array();
		foreach($categoryPageInstitutes as $res){
			$course 	= $res->getFlagshipCourse();
			if($course){
				$courseIds[] 	= $course->getId();
				$courseObject[] = $course;
			}
			if($instituteID = $res->getId()) {
				$instituteIds[] = $instituteID;
			}
		}

		//getNaukriData
		$naukriData                           = $shortlistListingLib->getInstitutesNaukriData($instituteIds);
		
		
		$displayData['courseObject']          = $courseObject;
		$displayData['naukriData']            = $naukriData;
		
		// get the ranks of the courses
		$myshortlistmodel                     = $this->load->model("myShortlist/myshortlistmodel");
		$courseRanks                          = $myshortlistmodel->getCoursesRank($courseIds);
		
		if($displayData['userId']) {
			$displayData['shortlistedCoursesIds'] = $myshortlistmodel->fetchShortlistedCoursesOfAUser($displayData['userId']);
		} else {
			$displayData['shortlistedCoursesIds'] = array();
		}
		$courseShortListCounts                = $myshortlistmodel->getShortlistCountOfCourses($courseIds);
		
		$displayData['courseShortListCounts'] = $courseShortListCounts;
		$displayData['courseRanks']           = $courseRanks;


		$this->listingCommonLibObj = new AbroadListingCommonLib();
		$displayData['listingCommonLibObj']   = $this->listingCommonLibObj;
		$displayData['shortlistListingLib']   = $shortlistListingLib;

		$displayData['isCourseSearchPage']    = 1;

		$displayData['tracking_keyid'] = MOBILE_NL_SHORTLISTPAGE_FINDCOLLEGEBYEXAMADDMORE_COURSESHORTLIST;

		//Tracking Code
		$displayData['beaconTrackData'] = array(
		    'pageIdentifier' => 'shortlistPage',
		    'pageEntityId' => 'instituteSearchPage',
		    'extraData' => array('url'=>get_full_url())
		);

		if(isset($type) && $type == 'fetchAjax'){
			if(count($categoryPageInstitutes) <= 0){
				echo "noresults";
			}else{
				$result = $this->load->view('mobile_myShortlist5/myShortlistSearchByExamResultSnippets',$displayData,TRUE);
				echo $result;				
			}
		}else{
			$this->load->view('mobile_myShortlist5/myShortlistSearchListing',$displayData);
		}
	}

	/**
	 * function to split page identifier
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-03-23
	 */
	private function setPageIdentifier(){

		$examName =  $this->input->get("exam");
    	$cutOff   =  $this->input->get("cutoff");
    	$location =  (int)$this->input->get("location");
		
		global $myShortlistStaticExamList;
 		if(in_array($examName, $myShortlistStaticExamList)){
 			$isCorrectExam  = 1;
 		}else{
 			$isCorrectExam  = 0;
 		}

 		$isCorrectCutOff = 0;
 		if(preg_match("/^(\d+-?)+\d+$/", $cutOff)){
 			$dashCount = substr_count($cutOff, '-');
 			if($dashCount == 1 || $dashCount == 0){
 				$isCorrectCutOff = 1;
 			}
 		}elseif(is_numeric($cutOff)){
 			$isCorrectCutOff = 1;
 		}	

    	if(empty($examName) || $cutOff == '' || empty($location) || empty($isCorrectExam) || empty($isCorrectCutOff)){
    		show_404();
    	}else{
       		$pageIdentifierArray['examName'] = $examName;
			$pageIdentifierArray['cutOff']   = $cutOff;
			$pageIdentifierArray['location'] = $location;
			return $pageIdentifierArray;	
    	}		
	}    

    function getRecommedationsData(&$displayData) {
		$this->init();
		$this->load->helper('myShortlist/my_shortlist', 'url');
		$courseId 		=  $this->input->post('courseId');
        $instituteName 		= base64_decode($this->input->post('instituteName'));
        if($courseId != 0 && $instituteName != '') {
            $this->showSimilarInstitutes($courseId,$instituteName);
        }
        $this->load->library('listing/ShortlistListingLib');
        $displayData['shortlistListingLib'] = new ShortlistListingLib();
		$maxRecommendations = 15;
		$this->load->library('myShortlist/MyShortlistLib');
		$this->shortlistLib = new MyShortlistLib();
		$this->shortlistLib->prepareDataForRecommedations($displayData,$maxRecommendations,'mobile');
   		// Pass the tracking key to the data storing logic
		$displayData['recommendedCourseWidgetKey'] = MOBILE_NL_SHORTLISTPAGE_SHORTLISTRECOCOURSEDETAIL_COURSESHORTLIST;	
		if($displayData['courseObject'])
			echo $this->load->view('mobile_myShortlist5/myShortlistRecommendationWidget', $displayData, true);
		else
			echo "No Data Found";
                exit;
	}
        
    function showSimilarInstitutes($courseId,$instituteName) {
        $recommendedCoursesIds 	= array();
        $instituteIds 		= array();            
        $maxRecommendations 	= 15;
        $courses                    = array($courseId);

        $this->load->library('myShortlist/MyShortlistLib');
		$this->shortlistLib = new MyShortlistLib();
		$this->shortlistLib->showSimilarInstitutes($displayData,$courses,$maxRecommendations);

        $displayData['validateuser'] = $userValidationData = $this->userStatus;
        $this->load->library('listing/ShortlistListingLib');
        $displayData['shortlistListingLib'] = new ShortlistListingLib();
        $displayData['isRecommendationsFlag'] 	= 1;
        $displayData['instituteName']   = $instituteName;
        $displayData['recommendedCourseWidgetKey'] = MOBILE_NL_SHORTLISTPAGE_SHORTLISTRECOCOURSEDETAIL_COURSESHORTLIST;
        if($displayData['courseObject'])
                echo $this->load->view('mobile_myShortlist5/myShortlistRecommendationWidget', $displayData, true);
        else
                echo "No Data Found";
        exit;
    }
        
        function getMyShortlistWalkthrough() {
            $this->load->view('mobile_myShortlist5/myShortlistWalkthrough');
        }

        /**
         * Method to show shortlisted courses detail-page including different tabs(placement,ask,reviews,notes)
         * @author Romil Goel <romil.goel@shiksha.com>
         * @date   2015-03-18
         * @param  integer    $courseId [description]
         * @return [type]               [description]
         */
        function showCourseDetailsTabs($courseId = false, $tabName = 'placements'){

        	if(empty($courseId))
        		show_404();

		$displayData                            = array();
        	$this->init(array('listing/ShortlistListingLib','listing/AbroadListingCommonLib'),array('myShortlist/my_shortlist'));
        	$displayData['userStatus']              = $this->userStatus;
		$displayData['userId']                  = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			
		if(!$displayData['userId']) {
			header("Location: ".$this->homepageUrl);
			die();
		}
			$myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");
			$this->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder = new CourseBuilder();
			$this->courseRepository = $courseBuilder->getCourseRepository();
        	$courseObj        = $this->courseRepository->find($courseId,array('basic', 'eligibility', 'course_type_information','location','placements_internships'));

        	$this->rankingLib = $this->load->library('rankingV2/RankingCommonLibv2');
			$displayData['courseRank'] = $this->rankingLib->getCourseRankBySource(array($courseObj->getId()));
			$this->shortlistLib = $this->load->library('myShortlist/MyShortlistLib');
			$displayData['courseRank'] = $this->shortlistLib->processCourseRankData($displayData['courseRank']);
			if($courseObj->getId() == "")
				show_404();

			$shortlistedCoursesIds = $myshortlistmodel->getUserShortListedCourses($displayData['userId']);

			$this->listingCommonLibObj              = new AbroadListingCommonLib();
			$shortlistListingLib                    = new ShortlistListingLib();		
			$displayData['listingCommonLibObj']     = $this->listingCommonLibObj;
			$displayData['shortlistListingLib']     = $shortlistListingLib;
			$displayData['boomr_pageid']            = 'MobileMyShortlistInstituteDetailsTabs';
			$displayData['courseObject'][0]         = $courseObj;
			$displayData['isCourseDetailsTabsPage'] = 1;
			$displayData['tab_name']					= $tabName;
			$displayData['homepageUrl']  = $this->homepageUrl;
			$displayData['shortlistedCoursesIds'] = $shortlistedCoursesIds;
			
			if($displayData['userId']) {
				$displayData['shortlistedCoursesIds'] = $myshortlistmodel->fetchShortlistedCoursesOfAUser($displayData['userId']);
			} else {
				$displayData['shortlistedCoursesIds'] = array();
			}
			foreach($shortlistedCoursesIds as $courseId) {
					// check user clicked on download e brochure
				if(isset($_COOKIE['applied_'.$courseId]) && $_COOKIE['applied_'.$courseId] == 1) {
					$downloadEBrochureApplied[] = $courseId;
				}
				
			}
			$shortlistedInstAndCourseIds[$courseObj->getId()] = $courseObj->getInstituteId();
			$displayData['coursesWithPlacementData'] = $this->shortlistLib->checkPlacementDataForShortlist($shortlistedInstAndCourseIds);
			$displayData['downloadEBrochureApplied'] = $downloadEBrochureApplied;
			
			//Tracking Code
			$displayData['beaconTrackData'] = array(
			    'pageIdentifier' => 'shortlistPage',
			    'pageEntityId' => 'courseDetailPage-'.$courseId,
			    'extraData' => array('url'=>get_full_url())
			);

			$displayData['qtrackingPageKeyId'] =530;

        	$this->load->view('mobile_myShortlist5/myShortlistInstituteTabs',$displayData);
        }

    /**
     * Method to show course snapshot
     * @author Aman Varshney <aman.varshney@shiksha.com>
     * @date   2015-03-23
     * @param  Interger     $courseId course Id
     */
	function myShortlistCourseSnapshot($courseId){
		
	 	if(empty($courseId))
    		show_404();

	 	$this->init(array('listing/NationalCourseLib','listing/ShortlistListingLib'));


		$national_course_lib = new NationalCourseLib();
		$shortlistListingLib = new ShortlistListingLib();		


	 	$listingBuilder   = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		$courseObj        = $courseRepository->find($courseId);

		if($courseObj->getId() == "")
			show_404();



		$displayData =  array();
		$instituteId   = $courseObj->getInstId();
		$displayData['courseId'] = $courseId;
		$displayData['courseDetails'] = $courseObj;
    

    	/*********** logic to get Course Affiliation  start ***************/
		$affiliationList                = $courseObj->getAffiliations();
		$affiliationData                = $this->getAffiliationsData($affiliationList);
		$displayData['affiliationData'] = $affiliationData;
		/*********** logic to get Course Affiliation  end ***************/

        $courseReviews = $national_course_lib->getCourseReviewsData(array($courseId));
        $courseReviews = $national_course_lib->getCollegeReviewsByCriteria($courseReviews);
        $displayData['courseReviewsOverallRating'] = $courseReviews[$courseId]['overallRating'];

		
		$course_model = $this->load->model('listing/coursemodel');  
	
		/*********** logic to get naukri salary data start ***************/
		$this->load->model('listing/institutemodel');
		$institutemodel              = new institutemodel;
		$data                        = array();	   
		$salaryDataResults           = $institutemodel->getNaukriSalaryData($instituteId, 'single');
		$instituteNaukriData         = array();

		// get the naukri salary data
		foreach($salaryDataResults as $naukriDataRow)
		{
			if($naukriDataRow['exp_bucket'] == '2-5')
				$instituteNaukriData = $naukriDataRow;
			
			$totalEmployees = $naukriDataRow['tot_emp'];
		}

		// unset the naukri data for institutes whose employee count is less than 30
		if($totalEmployees < 30)
			unset($instituteNaukriData);

		$displayData['instituteNaukriData'] = $instituteNaukriData;
		/*********** logic to get naukri salary data end ***************/
	

		$this->load->model('CA/cadiscussionmodel');
		$this->CADiscussionModel            = new CADiscussionModel();
		$historyData                        = array();
		$historyData                        = array("instituteId" => $instituteId,'courseId' => $courseId);
		$displayData['userStatus']          = $this->userStatus;
		$displayData['userId']              = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['homepageUrl']  = $this->homepageUrl;
		
		$myshortlistmodel                     = $this->load->model("myShortlist/myshortlistmodel");
		if($displayData['userId']) {
			$displayData['shortlistedCoursesIds'] = $myshortlistmodel->fetchShortlistedCoursesOfAUser($displayData['userId']);
		} else {
			$displayData['shortlistedCoursesIds'] = array();
		}
		
		$questionDataReturned               = $this->CADiscussionModel->getQnAMyShortlistMobile($historyData,1,'question','',0,4,'ALL',$displayData['userId']);
		$displayData['questionData']        =  $questionDataReturned;
		
		
		$displayData['shortlistListingLib'] = $shortlistListingLib;
		$displayData['isCourseSnapshotPage']    = 1;
		$displayData['boomr_pageid']       = 'MobileMyShortlistCourseSnapshot';
		//Tracking Code
		$displayData['beaconTrackData'] = array(
			    'pageIdentifier' => 'shortlistPage',
			    'pageEntityId' => 'courseSnapshotPage-'.$courseId,
			    'extraData' => array('url'=>get_full_url())
		);
	
        $this->load->view('mobile_myShortlist5/myShortlistCourseSnapshot', $displayData);
            
    }

        function getAffiliationsData($affiliationList){
        	$affiliationDataArr                            = array();
			$affiliationDataArr['completeAffiliationData'] = $affiliationList ;
			$affiliationDataArr['indianForeignFlag']       = 0;
			$affiliationDataArr['deemedAutonomousFlag']    = 0;
			foreach($affiliationList as $affiliation)
			{
			    if(in_array($affiliation[0],array('indian','foreign')))
			    {   //univ name
			    if($affiliationDataArr['closedText']==""){
			        $affiliationDataArr['closedText'] = $affiliation[1];
			        $affiliationDataArr['indianForeignFlag']++;
			    }
			    //break;//breaking here because preference is to be given to indian/foreign univ
			    }
			    else if(in_array($affiliation[0],array('deemed','autonomous')))
			    {   //mention deemed/autonomous univ
			    if($affiliationDataArr['closedText']==""){
			        if($affiliation[0]=="autonomous"){
			        $affiliationDataArr['closedText'] = ucfirst($affiliation[0])." Institute";
			        }
			        else{
			        $affiliationDataArr['closedText'] = ucfirst($affiliation[0])." University";
			        }
			    }
			    $affiliationDataArr['deemedAutonomousFlag']++;
			    //break;
			    }
			}
			$widgetData['affiliation']      = $affiliationDataArr;


			$affiliationData = '';

           foreach($widgetData['affiliation']['completeAffiliationData'] as $k=>$univs){
            if($univs[1]!=""){
                $affiliationData .= $univs[1];
            }
            if(in_array($univs[0],array('deemed','autonomous'))){
                if($univs[0] == 'deemed'){
                $affiliationData .= ucfirst($univs[0])." University";
                }
                else{
                $affiliationData .= ucfirst($univs[0])." Institute";
                }
            }
            if($k!=(count($widgetData['affiliation']['completeAffiliationData'])-1))
            $affiliationData .= ' <span class = "pipe">|</span> ';
            } 

            return $affiliationData;
        }

  function getCourseReviewsData($courseId,$pageNo = 0) {
  		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepository = $courseBuilder->getCourseRepository();
        $displayData = array();
        $courseObj = $this->courseRepository->find($courseId);
		$displayData['course'] = $courseObj;
		$displayData['viewData'] = Modules::run('CollegeReviewForm/CollegeReviewController/getAverageRatingAndCountByCourseId',$courseId);
		$filterArr = array();
		$filterArr['typeOfListing'] = 'institute';
		$filterArr['typeOfPage'] = 'reviews';
		$filterArr['courses'] = array($courseId);
		$displayData['reviewURL'] =  getSeoUrl($courseObj->getInstituteId(),'all_content_pages',$title="",$filterArr, $flagDbhit='NA',$creationDate='');
        //Tracking Code
		$displayData['beaconTrackData'] = array(
		    'pageIdentifier' => 'shortlistPage',
		    'pageEntityId' => 'courseReviewPage-'.$courseId,
		    'extraData' => array('url'=>get_full_url())
		);
        if(!$isAjax)
            $this->load->view('mobile_myShortlist5/widgets/reviewTab',$displayData);	
        else
        	$this->load->view("widgets/reviewTabDetails", $displayData);
        }

		/**
         * Method to show question Detail page for an institute
         * @author Romil Goel <romil.goel@shiksha.com>
         * @date   2015-03-18
         * @param  integer    $courseId [description]
         * @return [type]               [description]
         */
        function showQuestionDetailPage($courseId, $questionId){

        	if(empty($courseId))
        		show_404();

			$displayData                = array();
			$this->init(array('listing/ShortlistListingLib','messageBoard/message_board_client'),array('myShortlist/my_shortlist','mAnA5/ana','shikshautility'));
			$displayData['userStatus']  = $this->userStatus;
			$displayData['userId']      = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			$displayData['userGroup']   = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
			$displayData['homepageUrl'] = $this->homepageUrl;
			
			if(!$displayData['userId']) {
				header("Location: ".$this->homepageUrl);
				die();
			}
        	$myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");

			$this->load->library('CA/CADiscussionHelper');
			$caDiscussionHelper =  new CADiscussionHelper();
			$msgbrdClient       = new Message_board_client();

        	$this->load->builder('ListingBuilder','listing');
			$listingBuilder               = new ListingBuilder();
			$courseRepository             = $listingBuilder->getCourseRepository();
			$courseObj                    = $courseRepository->find($courseId);

			if(!$courseObj->getId())
				show_404();
			
			$instituteId                  = $courseObj->getInstId();
			$instituteRepository          = $listingBuilder->getInstituteRepository();
			$instituteObj                 = $instituteRepository->find($instituteId);
			$displayData['courseObj']     = $courseObj;
			$displayData['insObj']        = $instituteObj;
			$institute_id				  = $instituteObj->getId();

			$this->load->model('CA/cadiscussionmodel');
			$this->CADiscussionModel = new CADiscussionModel();
			$questionDataReturned    = $this->CADiscussionModel->getQnA(array('courseId' => $courseId, "instituteId" => $institute_id),$all = 1,'question','',$pageStart =0,$pageSize = -1,$questionType = 'All',$displayData['userId']);
			$totalQuestions          = $questionDataReturned["total"];
			$questions               = $questionDataReturned['data'];
			$questionIds             = $caDiscussionHelper->getQuestionIds($questions);

			if(!in_array($questionId, $questionIds))
				show_404();

			$answersAnsComments = $this->CADiscussionModel->getQnA(array('courseId' => $courseId, "instituteId" => $institute_id),$all = 1,'answer',$questionIds,'','','',$displayData['userId']);

			$answers                        = $answersAnsComments['data'];
			$allMessages                    = array_merge($questions,$answers);
			$formatedData                   = $caDiscussionHelper->rearrangeQnA($allMessages);
			$campusRepData                  = $this->CADiscussionModel->getCampusReps($courseId, $instituteId);
			$displayData['formattedCAData'] = formatCAData($campusRepData);

            //DO not show Answer form to the Institute owner if Campus rep is available
			$ownerId                           = $courseObj->getClientId();
			$displayData['doNoShowAnswerForm'] = false;
			if(is_array($campusRepData) && is_array($campusRepData['data']) && count($campusRepData['data'])>0 && $ownerId==$displayData['userId']){
                $displayData['doNoShowAnswerForm'] = true;
            }

			$prevQuestion = 0;
			$nextQuestion = 0;
			foreach ($questionIds as $key=>$value) {
				if($value == $questionId){
					$prevQuestion = $questionIds[$key-1];
					$nextQuestion = $questionIds[$key+1];
				}
			}

			$questionThreadData              = $msgbrdClient->getMsgTree($appId,$questionId,0,3,1,$displayData['userId'],$displayData['userGroup'],'reputation');
			$displayData['detailPageData']   = formatQuestionThreadData($questionThreadData);
			$displayData['totalAnswerCount'] = $questionThreadData[0]['mainAnsCount'];
			$displayData['boomr_pageid']     = 'MobileMyShortlistQuestionDetailPage';
			$displayData['totalQuestions']   = $totalQuestions;
			$displayData['formatedData']     = $formatedData;
			$displayData['prevQuestion']     = $prevQuestion;
			$displayData['nextQuestion']     = $nextQuestion;
			$displayData['questionId']       = $questionId;
			$displayData['courseId']         = $courseId;

			if($displayData['userId']) {
				$displayData['shortlistedCoursesIds'] = $myshortlistmodel->fetchShortlistedCoursesOfAUser($displayData['userId']);
			} else {
				$displayData['shortlistedCoursesIds'] = array();
			}

			//Tracking Code
			$displayData['beaconTrackData'] = array(
			    'pageIdentifier' => 'shortlistPage',
			    'pageEntityId' => 'courseQuestionDetailPage-'.$courseId,
			    'extraData' => array('url'=>get_full_url())
			);	
        	$this->load->view('mobile_myShortlist5/myShortlistQuestionDetailPage',$displayData);
        }

    /**
     * Method to show notes tab for myshortlist institute detail page
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-03-18
     * @param  integer    $courseId [description]
     * @return [type]               [description]
     */
    function showNotesTab(){

    		//get user id, course id, page no.
			$validity = $this->checkUserValidation();
			if($validity == 'false') {
				echo 'logged out'; return;
			}
			$userId   = $validity[0]['userid'];
			$courseId = $this->input->post('courseId');
			$page     = $this->input->post('page');

			$isAjax                               = $this->input->post("isAjax");
			$pageOffset                           = $this->input->post("pageOffset");
			$pageOffset                           = $pageOffset ? $pageOffset : 0; 
			
			$displayData['pageNo']                = $pageNo;
			$displayData['manageReviewDataCount'] = ($pageNo == 0) ? 1 : ($pageNo+1);
			$displayData['totalReviewsCount']     = count($displayData['reviewData'][$courseId]['reviews']);
			$displayData['course_id']             = $courseId;
			$displayData['pageOffset']            = $pageOffset;


			//load myshortlist model
			$this->myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");
			
			//get notes for this user and course
			$displayData['userId'] = $userId;
			$displayData['courseId'] = $courseId;
			$displayData['page'] = $page;
			$displayData['batchSize'] = 3;
			$result = $this->myshortlistmodel->getUserCourseNotesData($userId, $courseId, $page, $displayData['batchSize']);
		 	$noteIdArray = array();
			foreach($result['notes'] as $key => $note) {
				
				$result['notes'][$key]['last_updated_time_text'] = $this->getTimeTextForDisplay($note['last_updated']);
				if(!empty($note['reminder_date'])) {
					$reminderDateObj = new DateTime($note['reminder_date']);
					$result['notes'][$key]['reminder_date_time_text'] = $reminderDateObj->format('d/m/Y');
				}
				$noteIdArray[] = $note['note_id'];
			} 
			
			$displayData['notes'] = $result['notes'];
			$displayData['totalNotesCount'] = $result['count'];
			//Tracking Code
			$displayData['beaconTrackData'] = array(
			    'pageIdentifier' => 'shortlistPage',
			    'pageEntityId' => 'courseNotes-'.$courseId,
			    'extraData' => array('url'=>get_full_url())
			);
            if(!$isAjax)
	            $this->load->view('mobile_myShortlist5/widgets/notesTab',$displayData);	
	        else
	        	$this->load->view("mobile_myShortlist5/widgets/notesTabDetails", $displayData);
    }


    	/**
         * Action layer to show on notes setting
         * @author Vinay Airan <vinay.airan@shiksha.com>
         * @date   2015-04-30
         * @return
         */

	    function loadNotesSettingLayer() {
	    	$noteId = $this->input->post('noteId',true);
	    	$this->myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");
	    	$data['noteId'] = $noteId; 
	    	$data['noteData'] = $this->myshortlistmodel->getNoteData($noteId);
	    	$data['reminderDate'] = $data['noteData']['reminder_date'];
	    	if(!($data['reminderDate'] == '0000-00-00 00:00:00' || empty($data['reminderDate']))) {
	    		$reminderDateObj = new DateTime($data['reminderDate']);
				$data['reminderDate'] = $reminderDateObj->format('d/m/Y');
			} else {
				$data['reminderDate'] = '';
			}
	    	echo $this->load->view("mobile_myShortlist5/notesSettingLayer",$data,true);
	    }

        /**
         * Action layer to show on shortlisted colleges
         * @author Aman Varshney <aman.varshney@shiksha.com>
         * @date   2015-04-03
         * @return
         */
        function showSettingLayer(){

			$courseId                      =  $this->input->post('courseId',TRUE);
			$instituteId                   =  $this->input->post('instituteId',TRUE);
			$instituteName                 =  base64_decode($this->input->post('instituteName',TRUE));
			$shiksha_site_current_url      =  $this->input->post('shiksha_site_current_url',TRUE);
			$shiksha_site_current_refferal =  $this->input->post('shiksha_site_current_refferal',TRUE);
			$tracking_keyid				   =  $this->input->post('tracking_keyid',TRUE);

			if(empty($courseId) || empty($instituteId)){
				echo "No Data Found";
				exit;
			}

			//Check for Request E-Brochure from Also on Shiksha institutes. From here, the referral URL will be the current URL
	        if(strpos($shiksha_site_current_url,'alsoOnShiksha')!==false){
	                $shiksha_site_current_url = $shiksha_site_current_refferal;
	        }

			$addReqInfoVars = serialize(array($courseId));
			$addReqInfoVars = base64_encode($addReqInfoVars);

			$data                                  = array();
			$data['courseId']                      = $courseId;
			$data['instituteId']                   = $instituteId;
			$data['instituteName']                 = $instituteName;
			$data['addReqInfoVars']                = $addReqInfoVars;
			$data['shiksha_site_current_url']      = $shiksha_site_current_url;
			$data['shiksha_site_current_refferal'] = $shiksha_site_current_refferal;
			$data['tracking_keyid'] = $tracking_keyid;
			$this->listingcommonlib              = $this->load->library('listingCommon/ListingCommonLib');
                        $data['checkForDownloadBrochure']    = $this->listingcommonlib->checkForDownloadBrochure(array($courseId));
			$this->myshortlistmodel              = $this->load->model("myShortlist/myshortlistmodel");
			$data['courseWithOnlineForm']        = $this->myshortlistmodel->findCourseWithOnlineFormData($courseId);		
        		echo $this->load->view("mobile_myShortlist5/settingLayer", $data,true);
        		exit;
        }

    public function fetchUserNotificationsData() {
		$validity = $this->checkUserValidation();
		
		if(isset($validity[0]['userid'])) {
			
			$userId = $validity[0]['userid'];
			$this->myshortlistmodel  = $this->load->model("myShortlist/myshortlistmodel");
			$resultdata = $this->myshortlistmodel->fetchUserNotifications($userId, 'mobile');
			
			$isAllNotificationSeen = true;
			foreach($resultdata as $key=>$result) {
				if(empty($result['is_seen']) || $result['is_seen'] == "") {
					$isAllNotificationSeen = false;
				}
				
				$resultdata[$key]['timeText'] = $this->getTimeTextForDisplay($result['updated']);
			}
			$notificationData['resultdata'] = $resultdata;
			$notificationData['isAllNotificationSeen'] = $isAllNotificationSeen;
			return $notificationData;
		}	
	}

	function getTimeTextForDisplay($timeStamp) {
		$datetime1 = new DateTime();
		$datetime2 = new DateTime($timeStamp);
		
		$timeDiff = $datetime1->diff($datetime2);
		$timeText = "Just now";
		if($timeDiff->m > 0 || $timeDiff->y > 0 || $timeDiff->d > 0 || $timeDiff->h > 0) {
			if( $timeDiff->y > 0) {
				$timeText = ($timeDiff->y > 1 ? $timeDiff->y." years" : $timeDiff->y." year")." ago"; 
			} else if($timeDiff->m > 0) {
				$timeText = ($timeDiff->m > 1 ? $timeDiff->m." months" : $timeDiff->m." month")." ago";
			} else if($timeDiff->d > 0) {
				$timeText = ($timeDiff->d > 1 ? $timeDiff->d." days" : $timeDiff->d." day")." ago";
			} else if($timeDiff->h > 0) {
				$timeText = ($timeDiff->h > 1 ? $timeDiff->h." hrs" : $timeDiff->h." hr")." ago";
			}
		}
		
		return $timeText;
	}

	function setMobileNotificationAsSeen(){
		$notificationId = $this->input->post("notificationId");

		$myshortlistmodel  = $this->load->model("myShortlist/myshortlistmodel");
		$myshortlistmodel->setMobileNotificationAsSeen($notificationId);
	}

	function addEditRemoveUserCourseNote() {
		$validity = $this->checkUserValidation();
		if($validity == 'false') {
			echo 'logged out'; return;
		}
		$data['userId'] = $validity[0]['userid'];		
		$data['courseId'] = $this->input->post('courseId');
		$data['action'] = $this->input->post('action');
		$data['noteId'] = $this->input->post('noteId');
		$data['noteTitle'] = $this->input->post('noteTitle');
		$data['noteBody'] = $this->input->post('noteBody');
		$data['submitDate'] = $this->input->post('submitDate');
		$data['reminderDate'] = $this->input->post('reminderDate');
		
		//load myshortlist model
		$this->myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");
		$this->myshortlistmodel->addEditRemoveUserCourseNotes($data);
		
		echo 'done'; return;
	}

	function checkQueryStringAndRedirectToBaseURL(){
   	 $this->load->library("security");
	 $queryString = $_SERVER['QUERY_STRING'];
	 if($queryString != 'addMoreColg=1'){
		$queryString = $this->security->xss_clean($queryString);
		$scriptURL =$_SERVER['SCRIPT_URL'];
	   	if($queryString != ''){
		     redirect(SHIKSHA_HOME.$scriptURL, 'location', 301);
	         exit();
		}
 	}  	
  }
}
