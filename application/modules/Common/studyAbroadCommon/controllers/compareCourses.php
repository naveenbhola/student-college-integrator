<?php
class compareCourses extends MX_Controller
{
    private $compareCoursesLib;
    private $maxCoursesToShow;
    public function __construct()
    {
        parent::__construct();
        $this->compareCoursesLib = $this->load->library('compareCoursesLib');
        $this->maxCoursesToShow = 3;
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
    	//no more than 3 courses are allowed
    	if(count($entityIdArray)>$this->maxCoursesToShow)
    	{
    		$entityIdArray = array_slice($entityIdArray, 0, $this->maxCoursesToShow);
    	}
    	$this->compareCoursesLib->validateEntityId($entityIdArray[0] ,$entityIdArray,0);
    	if($entityIdArray[1]!='')$this->compareCoursesLib->validateEntityId($entityIdArray[1],$entityIdArray,1);
    	if($entityIdArray[2]!='')$this->compareCoursesLib->validateEntityId($entityIdArray[2],$entityIdArray,2);
    	
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
    
	public function trackCompareAddRemoveMultipleCourses($courseIds,$action,$userId,$source)
	{
		if(count($courseIds)>0){
			$sessionId = getVisitorSessionId();
			if($action == "add"){
				$this->compareCoursesLib->trackCompareAdd($courseIds,$userId,$source,$sessionId);
			}elseif($action == "remove"){
				$this->compareCoursesLib->trackCompareRemove($courseIds,$userId,null,$sessionId);
			}
		}
		return;
	}
	
	/*
	 * function that perform addition of one course & removal of another together 
	 */
	public function trackCompareSimultaneousAddRemove()
	{
		$data = $this->input->post('data');
		$source = $this->input->post('source');
		$validateUser = $this->checkUserValidation();
		if($validateUser === "false"){
			$userId = -1;
		}else{
			$userId = $validateUser[0]['userid'];
		}
		$sessionId = getVisitorSessionId();
		$this->compareCoursesLib->trackCompareRemove($data['removedCourse'],$userId,$source,$sessionId);
		$this->compareCoursesLib->trackCompareAdd($data['addedCourse'],$userId,$source,$sessionId);
		
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
		
    	$displayData['coursesCount'] 	= $coursesCount;
    	$displayData['courseDataObjs'] 	= $courseData;
    	$displayData['univDataObjs'] 	= $universityData;
		$displayData['recommendedCourses']  = $this->compareCoursesLib->getRecommendationsForComparedCourses(array_slice($entityIdArray,0,2));
    	$displayData['recommendedCoursesTrackingId'] = 596;
		$this->compareCoursesLib->setRowFlagsForDisplay($displayData, $courseData);
    	$this->_prepareTrackingData($displayData,$courseIds);
    	if($_SERVER['HTTP_REFERER'] === NULL){	//If the user has come to this page directly, track it
    		$this->trackCompareButtonClick(653,$courseIds,getCurrentPageURLWithoutQueryParams());
    	}
    	$this->load->view('studyAbroadCommon/compareCourses/compareCoursesOverview',$displayData);
    }
    private function _prepareTrackingData(&$displayData,$courseIds) 
	{
		$displayData['beaconTrackData'] = array(
												 'pageIdentifier' => 'compareCoursesPage',
                                              	 'pageEntityId' => '0',
                                              	 'extraData' => array('courseIds'=>$courseIds)
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
		$trackingKeyId = $this->input->post('trackingKeyId');
		if(!is_array($courseList)){
			return;
		}
		$courseList = array_filter($courseList,is_numeric);
		$recommendedCourses = $this->compareCoursesLib->getRecommendationsForComparedCourses($courseList);
		$this->load->view('compareCourses/widgets/recommendedCoursesForCompare',array('recommendedCourses'=>$recommendedCourses, 'recommendedCoursesTrackingId'=>$trackingKeyId));
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
	/*
	 * called via ajax upon adding a course from compare page to check if there are already 3 courses compared by user then we need not add another to the cookie
	 */
	public function getUserComparedCourses()
	{
		$courseIds = $this->compareCoursesLib->getUserComparedCourses(true);
		echo json_encode($courseIds);
	}
	public function getDataForCompareLayer(){
		$courseIds = $this->compareCoursesLib->getUserComparedCourses();
		$trackingId = $this->input->get('trackingKeyId',true);
		//To handle default case
		if(empty($trackingId)){
			$trackingId = -1;
		}
		$displayData['trackingId'] = $trackingId;
		if(!empty($courseIds)){
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder 						= new ListingBuilder;
			$courseRepository 			= $listingBuilder->getAbroadCourseRepository();
			$universityRepository 		= $listingBuilder->getUniversityRepository();
			$courseObjs = $courseRepository->findMultiple($courseIds);
			if(sizeof(array_keys($courseObjs)) != sizeof($courseIds)){
				$missingCourses = array_diff($courseIds,array_keys($courseObjs));
				if(!empty($missingCourses)){
					$this->compareCoursesLib->markDeletedComparedCourses($missingCourses);
				}
			}
			$univIds = array_map(function($ele){return $ele->getUniversityId();},$courseObjs);
			if(!empty($univIds)){
				$univObjs = $universityRepository->findMultiple($univIds);
				$dataArray = array();
				foreach($courseObjs as $courseId=>$courseObj){
					$dataArray[$courseId] = array(
						'id'				=>$courseObj->getId(),
						'universityName'	=>$courseObj->getUniversityName(),
						'countryName' 		=>$courseObj->getCountryName()
					);
					$photo = reset($univObjs[$courseObj->getUniversityId()]->getPhotos());
					if(!empty($photo)){
						$dataArray[$courseId]['photoURL'] = $photo->getURL();
					}else{
						$dataArray[$courseId]['photoURL'] = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
					}
					
				}
				if(sizeof($dataArray) == 1){
					$dataArray[0] = array();
				}
				$displayData['courseData'] = $dataArray;
			}
		}
		$html = $this->load->view("studyAbroadCommon/compareCourses/widgets/compareCourseBottomLayer",$displayData,true);
		echo json_encode(array('html'=>$html));
	}
	
	public function clearCompareSelection(){
		$courseIds = array_keys(json_decode($_COOKIE['compareCourses'],true));
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
		$this->compareCoursesLib->clearCompareSelection($courseIds,$userId,$sessionId);
	}

	public function trackCompareButtonClick($trackingId,$courseIds,$pageUrl){
		if(!((integer)$trackingId > 0)){
			return false;
		}

		if($this->input->post('courseIds')){
			$courseIds = array_filter(explode("-", $this->input->post('courseIds')));
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
			if(strpos($_SERVER['HTTP_REFERER'],"login") != false || strpos($_SERVER['HTTP_REFERER'],"signup") != false){
				$pageUrl = base64_decode($_COOKIE['compareRefUrl']);
			}
			else{
				$pageUrl = $_SERVER['HTTP_REFERER'];
			}
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

}
?>
