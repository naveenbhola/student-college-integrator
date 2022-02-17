<?php
class CompareCourses extends MX_Controller
{
    private $compareCoursesLib;
    private $maxCoursesToShow;
    public function __construct()
    {
        parent::__construct();
        $this->compareCoursesLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
        $this->maxCoursesToShow = 2;
    }

    public function initComparePage($entityIds='')
    { 
    	$displayData = array();
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
		}
	    }
	    else {
		$displayData['loggedInUserData'] = false;
	    }
	  
	  	if(isset($_COOKIE['compareCourses']) && $displayData['loggedInUserData'] === false) {
	    	$userId = -1;
    		$cookie = $_COOKIE['compareCourses'];
	    	$cookie = array_keys((array)(json_decode($cookie)));
	    	$this->trackCompareAddRemoveMultipleCourses($cookie,'remove',$userId);
	    	setcookie("compareCourses", '{}', time()-3600);
    	}
    	$entityIdArray = explode("-", $entityIds);
    	$entityIdArray= array_filter($entityIdArray);
    	if(empty($entityIdArray) || $entityIdArray==false)
    	{
    		  redirect(SHIKSHA_STUDYABROAD_HOME);
    	}
    	//no more than 2 courses are allowed
    	if(count($entityIdArray)>$this->maxCoursesToShow)
    	{
    		$entityIdArray = array_slice($entityIdArray, 0, $this->maxCoursesToShow);
    	}
    	$this->compareCoursesLib->validateEntityId($entityIdArray[0] ,$entityIdArray,0);
    	if($entityIdArray[1]!='')$this->compareCoursesLib->validateEntityId($entityIdArray[1],$entityIdArray,1);
    	
    	if(empty($entityIdArray) || $entityIdArray==false)
    	{
    		redirect(SHIKSHA_STUDYABROAD_HOME);
    	}
    	//validate url
    	$this->compareCoursesLib->validateUrl();

    	$this->load->builder('ListingBuilder','listing');
    	$listingBuilder 						= new ListingBuilder;
	    $this->abroadCourseRepository 			= $listingBuilder->getAbroadCourseRepository();
	    $this->abroadInstituteRepository 		= $listingBuilder->getAbroadInstituteRepository();
	    $this->abroadUniversityRepository 		= $listingBuilder->getUniversityRepository();
	    $this->load->library('listing/AbroadListingCommonLib');		
	    $this->abroadListingCommonLib 	= new AbroadListingCommonLib();

    	$displayData['trackForPages'] = true;

    	$this->compareCoursesLib->setPageReferrer($displayData);

    	$this->getComparePageDetails($displayData,$entityIdArray);
    }


    public function getComparePageDetails(& $displayData,$entityIdArray)
    {
    	$courseData 		= $this->abroadCourseRepository->findMultiple($entityIdArray);

    	foreach ($courseData as $key => $course)
    	{
		    if(!($course instanceof AbroadCourse)) { unset($courseData[$key]); }
		    if($course->getId() == "")	  {	unset($courseData[$key]); }
		}
    	$coursesCount = count($courseData);

    	if($coursesCount < 1) {	redirect(SHIKSHA_STUDYABROAD_HOME);  }
    	

    	$universityIds 		= array();
    	$courseIds 			= array();
    	foreach ($courseData as $key => $course)
    	{
		  $universityIds[] = $course->getUniversityId();
		  $courseIds[] 	   = $course->getId();
		}
		$universityIds 	= array_unique($universityIds);
		$universityData = $this->abroadUniversityRepository->findMultiple($universityIds);
	    $this->compareCoursesLib->prepareApplicationProcessDetails($displayData,$courseIds);
        $this->compareCoursesLib->getCourseApplicationEligibilityData($displayData,$courseIds);
        $displayData['firstYearFees'] = $this->abroadListingCommonLib->getCourseFeesDetails($courseData);
		$this->compareCoursesLib->prepareCoursesExamDetails($displayData,$courseData);
	    $displayData['universityContactDetails'] = $this->abroadListingCommonLib->getUniversityContactInfo($universityData);
    	$displayData['courseRankDetails'] = $this->abroadListingCommonLib->populateRankInfo($courseData,'course');
		$displayData['comparedCourseIds'] = $entityIdArray;
    	$displayData['coursesCount'] 	= $coursesCount;
    	$displayData['courseDataObjs'] 	= $courseData;
    	$displayData['univDataObjs'] 	= $universityData;
    	$displayData['recommendedCourses'] = array();
		$displayData['recommendedCourses']  = $this->compareCoursesLib->getRecommendationsForComparedCourses(array_slice($entityIdArray,0,2),true);
		$displayData['allCoursesByUnivs']  = $this->compareCoursesLib->getCoursesByUnivIds($universityIds);
		
		$this->compareCoursesLib->setRowFlagsForDisplay($displayData, $courseData);
    	$this->_prepareTrackingData($displayData,$courseIds);
    	if($_SERVER['HTTP_REFERER'] === NULL){	//If the user has come to this page directly, track it
    		$this->trackCompareButtonClick(659,$courseIds,getCurrentPageURLWithoutQueryParams());
    	}
    	$this->load->view('studyAbroadCommon/compareCourses/compareCoursesOverview',$displayData);
    }
   

    private function _prepareTrackingData(&$displayData,$courseIds) 
	{
	
		$displayData['beaconTrackData'] = array(
												 'pageIdentifier' => 'compareCoursesPage',
                                              	 'pageEntityId' => '0',
                                              	 'extraData' => array('compareCourseIds'=> $courseIds)
												);
	}

	/*
	 * function to get recommended courses for courses being compared(compare functionality)
	 * Note: called via AJAX, all parameters must be sent via POST
	 */
	public function getRecommendationsForComparedCourses()
	{
		// get post data
		$courseList = $this->input->post('courseList');
		$recommendedCourses = $this->compareCoursesLib->getRecommendationsForComparedCourses($courseList,true); // true indicates mobile
		$this->load->view('commonModule/compareCourses/widgets/recommendedCourseListForCompare',array('recommendedCourses'=>$recommendedCourses));
	}

	public function trackCompareAddRemoveMultipleCourses($courseIds,$action,$userId)
	{
		if(count($courseIds)>0){
			$sessionId = getVisitorSessionId();
			if($action == "add"){
				$this->compareCoursesLib->trackCompareAdd($courseIds,$userId,null,$sessionId);
			}elseif($action == "remove"){
				$this->compareCoursesLib->trackCompareRemove($courseIds,$userId,null,$sessionId);
			}
		}
		return;
	}

	public function trackCompareAddRemove(){
		$courseId = $this->input->post('courseId');
		if(empty($courseId)){
			echo json_encode(array('error'=>1,'errorMsg'=>'Server Error : Course Id not found. Please try again later.'));
			return false;
		}

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
		$courseObj = $abroadCourseRepository->find($courseId);
		if(!($courseObj instanceof AbroadCourse && (integer)$courseObj->getId() > 0)){
			echo json_encode(array('error'=>1,'errorMsg'=>'Course could not be found! Please try again later.'));
			return false;
		}
		$source = (integer)$this->input->post('source'); 	// 	this is now the trackingId
		$action = $this->input->post('action');
		$validateUser = $this->checkUserValidation();
		if($validateUser === "false"){
			$userId = -1;
		}else{
			$userId = $validateUser[0]['userid'];
		}
		$sessionId = getVisitorSessionId();
		
		if($action == "add"){
			$this->compareCoursesLib->trackCompareAdd($courseId,$userId,$source,$sessionId);
		}elseif($action == "remove"){
			$this->compareCoursesLib->trackCompareRemove($courseId,$userId,$source,$sessionId);
		}else{
			echo json_encode(array('error'=>1,'errorMsg'=>'Server Error : Illegal action found. Please try again later.'));
		}
	}
	
	public function putAbroadComparedCoursesFromCookieToDB(){
		$this->compareCoursesLib->putAbroadComparedCoursesFromCookieToDB(getVisitorSessionId());
		$this->compareCoursesLib->migrateCompareButtonClickTracking(getVisitorSessionId());
	}
	
	public function getDataForCompareLayer(){
		$courseIds = $this->compareCoursesLib->getUserComparedCourses();
                $courseIds = array_filter($courseIds,"isValidId");
		$displayData = array();
		if(!empty($courseIds)){
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder 			= new ListingBuilder;
			$courseRepository 			= $listingBuilder->getAbroadCourseRepository();
			$courseObjs = $courseRepository->findMultiple($courseIds);
			if(sizeof(array_keys($courseObjs)) != sizeof($courseIds)){
				$missingCourses = array_diff($courseIds,array_keys($courseObjs));
				if(!empty($missingCourses)){
					$this->compareCoursesLib->markDeletedComparedCourses($missingCourses);
				}
			}
			if(count($courseObjs) == 0){
				echo json_encode(array('html'=>''));
				return true;
			}
			if(count($courseObjs) == 3){	// if the user is coming from the desktop
				$courseObjs = array_slice($courseObjs,0,2);
			}
			$dataArray = array();
			foreach($courseObjs as $courseId=>$courseObj){
				$dataArray[$courseId] = array(
					'id'				=>$courseObj->getId(),
					'universityName'	=>$courseObj->getUniversityName(),
					'countryName' 		=>$courseObj->getCountryName(),
					'courseName'		=>$courseObj->getName()
				);
			}
			$displayData['courseData'] = $dataArray;
		}
		$html = $this->load->view('commonModule/compareBottomLayer',$displayData,true);
		echo json_encode(array('html'=>$html));
	}

	public function trackCompareButtonClick($trackingId,$courseIds,$pageUrl){
		if(!((integer)$trackingId > 0)){
			return false;
		}
		if(empty($courseIds)){
			$courseIds = array_keys(json_decode($_COOKIE['compareCourses'],true));
		}
		if(empty($courseIds)){
			return true;
		}
		$validateUser = $this->checkUserValidation();
		if($validateUser === "false"){
			$userId = -1;
		}else{
			$userId = $validateUser[0]['userid'];
		}
		$sessionId = getVisitorSessionId();
		if(empty($pageUrl)){
			$pageUrl = $_SERVER['HTTP_REFERER'];
		}
		$addedOnDate = date('Y-m-d');
		$addedOnTime = date('H:i:s');
		$insertionData = array();
		foreach($courseIds as $courseId){
			$insertionData[] = array(
					'trackingId' => $trackingId,
					'pageUrl' => $pageUrl,
					'courseId' => $courseId,
					'userId' => $userId,
					'visitorSessionId' => $sessionId,
					'addedOnDate' => $addedOnDate,
					'addedOnTime' => $addedOnTime
				);
		}
		$this->compareCoursesLib->trackCompareButtonClick($insertionData);
		return 1;
	}
	public function getUniversityCoursesForComparison()
	{
		$courseId = $this->input->post("courseId");
		$univId = $this->input->post("univId");
		if(!($courseId>0) || !($univId>0))
		{
			echo "error";
			return;
		}else{
			$returnData = $this->compareCoursesLib->getUniversityCoursesForComparison($univId, $courseId);
			if($returnData === false) // deleted univ case | still available in solr
			{
				echo "error";
				return;
			}
			if(is_null($returnData['matched']))
			{
				$returnData['univBlockHTML'] = $this->load->view("/commonModule/compareCourses/widgets/universitySelectionCompareBlock",
																 array('univDataObj'=>$returnData['univ']),
																 true);
				$returnData['courseDropdownHTML'] = $this->load->view('commonModule/compareCourses/widgets/compareCourseDropdown',
																	  array('courseList'=>$returnData['courses'], "ajaxFlag"=>true),
																	  true);
				unset($returnData['univ']);
				unset($returnData['courseObjs']);
			}
			echo json_encode($returnData);
			return;
		}
	}
} ?>