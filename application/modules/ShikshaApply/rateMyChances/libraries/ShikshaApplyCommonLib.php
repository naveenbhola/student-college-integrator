<?php
class ShikshaApplyCommonLib {

	private $CI;
	
	function __construct() {
		$this->CI =& get_instance();
		$this->_setDependecies();
	}
	
	function _setDependecies()
    {
        // load models & stuff that will be used by this library
		$this->rateMyChanceModel = $this->CI->load->model('rateMyChances/ratemychancemodel');
    }
	
	/*
	 * get RMC page url from course
	 */
	public function getRMCUrlFromCourse($abroadCourse)
	{
		if(!($abroadCourse instanceof AbroadCourse)){
            show_404_abroad();
        }
		/*$url = $abroadCourse->getURL();
        $chunk = str_replace(SHIKSHA_STUDYABROAD_HOME."/",'',$url);
        $seoUrl = SHIKSHA_STUDYABROAD_HOME."/rate-my-chances-in-".$chunk;
        $seoUrl = str_replace('-courselisting-','-rmc-',$seoUrl);*/
		
		$url = seo_url_lowercase($abroadCourse->getName())."-rmc-".$abroadCourse->getId();
		$seoUrl = SHIKSHA_STUDYABROAD_HOME."/rate-my-chances-in-".$url;
		return $seoUrl;
	}
	/*
     * get courses on which rate my chance response was made by given user
     * @params : array() of course ids
     */
    public function getRMCCoursesAndUniversitiesByUser($userId,$limitOffset = 0)
    {
        $data = $this->rateMyChanceModel->getRMCCoursesByUser($userId,$limitOffset);
		$courseIds = $data['courseIds'];
		if(count($courseIds) == 0)
		{
			return array();
		}
		// load builders & repos
		$this->CI->load->builder ( 'ListingBuilder', 'listing' );
		$listingBuilder = new ListingBuilder ();
		$this->universityRepository = $listingBuilder->getUniversityRepository ();
		$this->courseRepository = $listingBuilder->getAbroadCourseRepository ();
		// get course 
		$courses = $this->courseRepository->findMultiple ( $courseIds );
		if(count(array_keys($courses)) >0){
		// get university ids
		$universityIds 	= array_map(function($a){return $a->getUniversityId();},$courses);
		// .. & then univ objects
		$universities  	= $this->universityRepository->findMultiple ( $universityIds );
		}
		return array('courses'=>$courses, 'universities'=>$universities,'totalCount' => $data['totalCount']);
    }
	
	public function recordResponse($data){
		$this->rateMyChanceModel->recordResponse($data);
	}
	
	public function getAbroadExamsForUserByUserObject($userObj){
		if(!is_object($userObj)){
			return array();
		}
		$lib = $this->CI->load->library("listingPosting/AbroadCommonLib");
		$res = $lib->getAbroadExamsMasterList();
		$abroadExamMasterList = array_map(function($ele){return $ele['exam'];},$res);
		$educationData = $userObj->getEducation();
		$finalArray = array();
		foreach($educationData as $education){
			if(in_array($education->getName(),$abroadExamMasterList)){
				$finalArray[] = $education->getName();
			}
		}
		return $finalArray;
	}
	
	/*
	 * This function will first check if a user already has a stage.
	 * If not, it will insert the user at stage 1.
	 */
	public function checkAndAddUserStage($userId){
		$this->rateMyChanceModel->checkAndAddUserStage($userId);
	}
	
	public function addNewUserRating($userId,$courseId){
		$this->rateMyChanceModel->addNewUserRating($userId,$courseId);
	}
	
	/*
	 * Purpose: This function gets the courses that the user has rated his chance on, as well as the rating values.
	 * Return : An array containing courseIds as keys and ratings as values.
	 * Author : Rahul Bhatnagar
	 * Params :
	 * UserId : The Id of the user
	 */
	public function getUserRmcRatings($userId){
		return $this->rateMyChanceModel->getUserRatings((integer)$userId);
	}
	
	public function getUserRatingsWithNoRatingGivenAsWell($userId){
		return $this->rateMyChanceModel->getUserRatingsWithNoRatingGivenAsWell((integer)$userId);
	}
	
	public function getUserStage($userId){
		return $this->rateMyChanceModel->getUserStage((integer)$userId);
	}

	 public function saveUserEnrolmentForCounseling($userId,$courseId)
    {
    	//save data in activity log for users
    	$rmcPostingLib = $this->CI->load->library("shikshaApplyCRM/rmcPostingLib");
    	$historyData = array(
                            'userId' =>$userId, 
                            'message'=>"Student has clicked 'Yes,I want to enrol'", 
                            'addedBy' => $userId, 
                            'activityType' => 'enrolledForCounseling', 
                            'messageType' => "systemGenerated", 
                            'isThisUpdateForStudent' =>0,
							'courseId' => $courseId
                        );

		$rmcPostingLib->logRmcActivityForUser($historyData);
        return $this->rateMyChanceModel->saveUserEnrolmentForCounseling($userId,$courseId);
    }

    public function getRMCCount($userId){
        $rateMyChanceModel = $this->CI->load->model('rateMyChances/ratemychancemodel');
        return $rateMyChanceModel->getRMCCountByUser($userId);
    }

    public function checkUserIsCandidate($candidateId)
    {
         return  $this->rateMyChanceModel->checkUserIsCandidate($candidateId);
    }
}
?>