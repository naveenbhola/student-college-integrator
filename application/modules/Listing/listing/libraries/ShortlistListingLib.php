<?php
/**
 *  library for shortlisted coures on abroad category pages.
 */

class ShortlistListingLib {
	private $CI;
	private $shortlistlistingmodel;
	private $courseRepository;
	private $instituteRepository;
	private $universityRepository;
	function __construct() {
		$this->CI = & get_instance ();
		$this->shortlistlistingmodel = $this->CI->load->model ( 'listing/shortlistlistingmodel' );
	}
	
	private function _loadAbroadListingDependency() {
		$this->CI->load->builder ( 'ListingBuilder', 'listing' );
		$listingBuilder = new ListingBuilder ();
		
		$this->universityRepository = $listingBuilder->getUniversityRepository ();
		
		$this->courseRepository = $listingBuilder->getAbroadCourseRepository ();
	}
	
	function updateShortListedCourse($data, $action) {
		return $this->shortlistlistingmodel->updateShortListedCourse ( $data, $action );
	}
	
	function fetchIfUserHasShortListedCourses($data) {
		if(!empty($data['userId']) && $data['userId'] > 0)
		{
		return $this->shortlistlistingmodel->fetchIfUserHasShortListedCourses ( $data );
		}
		else
		{
			$shortListedCourses = json_decode($_COOKIE["usc"]);
			if(!empty($shortListedCourses))
			{
				foreach($shortListedCourses as $key => $shortListedCourse)
				{
					$resultData ['courseIds'] [] = $shortListedCourse->csrId;
			
				}
				$resultData['count'] = count($resultData ['courseIds']);
			} else
			{
				$resultData['count'] = 0;
			}
			return $resultData;
		}
    }
	/*
	 * get only count of shortlisted courses
	 */
	function getShortlistedCoursesCount($data) {
		$totalCount = 0;
		if(!empty($data['userId'])) {
			$resultData = $this->shortlistlistingmodel->getShortlistedCoursesDetail ( $data );
			$totalCount =  $resultData['totalCount'];
		} else {
			$shortListedCourses = json_decode($_COOKIE["usc"]);
			$totalCount = count($shortListedCourses);
		}
		return $totalCount;
	}
	function getShortlistedCoursesDetail($data) {
		
		$courseIdarray = array ();
		if(!empty($data['userId'])) {
		 $resultData = $this->shortlistlistingmodel->getShortlistedCoursesDetail ( $data );
		 $result ['totalCount'] =  $resultData['totalCount'];
		 $result ['rowIdOfLastTuple'] = 0;
		 foreach ( $resultData['courseIdsArraySortedByTime'] as $row ) {
			$courseIdarray [] = $row ['course_id'];
			$result ['rowIdOfLastTuple'] = $row ['rowId'];
		 }
		} else {

			$shortListedCourses = json_decode($_COOKIE["usc"]);
			$result ['totalCount'] = count($shortListedCourses);
			if(!empty($shortListedCourses)) {
					usort($shortListedCourses, function($a1, $a2) {
					$v1 = strtotime($a1->shrtTm);
					$v2 = strtotime($a2->shrtTm);
					return $v2 - $v1;
					});
			    	foreach($shortListedCourses as $key => $shortListedCourse) {
					$courseIdarray [] = $shortListedCourse->csrId;
					}
			}
		}
		
		$universityIdarray = array ();
		
				
		if (count ( $courseIdarray ) > 0) {
			$this->_loadAbroadListingDependency ();
			$result ['courses'] = $this->courseRepository->findMultiple ( $courseIdarray );
			
			foreach ( $result ['courses'] as $courseId => $courseObj ) {
				if(!empty($courseObj) && $univeristyId = $courseObj->getUniversityId ())
				{
				$universityIdarray [] = $univeristyId;
				}
			}
			$universityIdarray = array_unique ( $universityIdarray );
			
			if (count ( $universityIdarray ) > 0) {
				$result ['universities'] = $this->universityRepository->findMultiple ( $universityIdarray );
			}
		}
		
		return $result;
	}
	
	public function putShortListCouresFromCookieToDB ($data) {
		$shortListedCourses = json_decode($_COOKIE["usc"]);
		$shortListCourseDataToInsertIntoDB = array();
		$resultData = $this->shortlistlistingmodel->fetchIfUserHasShortListedCourses(array('userId' => $data ['userId']));
		
		if(!empty($shortListedCourses)) {
			foreach($shortListedCourses as $key => $shortListedCourse) {
				if(!in_array($shortListedCourse->csrId, $resultData['courseIds'])) {
				$shortListedCourse->shrtTm;
				$shortListCourseDataToInsertIntoDB [] = array (
						'userId' =>$data ['userId'],
						'status' =>$data ['status'],
						'sessionId' => sessionId (),
						'pageType' =>$data ['pageType'],
						'courseId' => $shortListedCourse->csrId,
						'shortListTime' => $shortListedCourse->shrtTm,
						'tracking_keyid' => $data['tracking_keyid'],
						'visitorSessionid' => getVisitorSessionId()

				);
				}
			}

		}
		if(!empty($data ['shortlistCourseIdInSignUpProcess']))
		{
			if(!in_array($data ['shortlistCourseIdInSignUpProcess'], $resultData['courseIds'])) {
				$shortListCourseDataToInsertIntoDB [] = array (
						'userId' =>$data ['userId'],
						'status' =>$data ['status'],
						'sessionId' => sessionId (),
						'pageType' =>$data ['pageType'],
						'courseId' => $data ['shortlistCourseIdInSignUpProcess'],
						'shortListTime' => date ( 'Y-m-d H:i:s' ),
						'tracking_keyid' => $data['tracking_keyid'],
						'visitorSessionid' => getVisitorSessionId()
				);

			}
		}

		$myBool = (empty($data['shortlistCourseIdInSignUpProcess'])?false:true);
		setcookie('usc','',time()-3600,'/',COOKIEDOMAIN); //reset Cookie
		if(!empty($shortListedCourses) || !empty($data ['shortlistCourseIdInSignUpProcess']))
		{ 
		return $this->shortlistlistingmodel->putShortListCouresFromCookieToDB($shortListCourseDataToInsertIntoDB,$myBool);
		} else {
			return ;
		}
	}
	
	public function checkIfCourseAlreadyShortlisted($data) {
		if(!empty($data['userId']) && $data['courseId']) {
			return $this->shortlistlistingmodel->checkIfCourseAlreadyShortlisted($data);
		}
	}
        
        function getRecommendationData($userValidationData) {
        
        $this->alsoviewed = $this->CI->load->library('recommendation/alsoviewed');
        $this->CI->load->library('listing/NationalCourseLib');
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $this->courseRepository = $listingBuilder->getCourseRepository();
        $this->instituteRepository = $listingBuilder->getInstituteRepository();
        $myshortlistmodel = $this->CI->load->model("myShortlist/myshortlistmodel");
        $this->nationalcourselib;
        if($userValidationData !== 'false')
            $userId = $userValidationData[0]['userid'];
        
        // if user is logged-in then get the shortlisted courses and all those FT MBA course on which user had made response
        if($userId) {
            // get the courses shortlisted by the user
            $shortlistedCourses = $myshortlistmodel->getUserShortListedCourses($userId);

            // get the FT-MBA(23) courses on which user made responses
            $courseOfResponses  = $myshortlistmodel->getCoursesOfResponses($userId, 23);

            // combine the shortlisted courses and FT-MBA courses on which response has been made
            $recommendationSeedCourses = array_merge($shortlistedCourses, $courseOfResponses);

            if(!empty($recommendationSeedCourses))
                    $recommendationSeedCourses = $this->filterCourseByDominantSubcategory($recommendationSeedCourses, 23);

            // get the also viewed recommendations for the computed courses-seed
            if(!empty($recommendationSeedCourses))
                    $recommendations = $this->alsoviewed->getAlsoViewedListings($recommendationSeedCourses, $maxRecommendations);
        }
        
        // get the courses-seed for recommendations from courses viewed by the user in the current session
        if(!$userId || empty($recommendations)) {
            $coursesViewed 	 = $myshortlistmodel->getCoursesViewedInSession(sessionId());
            $coursesViewed 	 = $this->filterCourseByDominantSubcategory($coursesViewed, 23);
            $recommendations     = $this->alsoviewed->getAlsoViewedListings($coursesViewed, $maxRecommendations);
        }
        
        return $recommendations;
    }
    
    /**
	* Purpose       : Method to filter out the courses that do not have specified subcategory as dominant subcategory
	* Params        : 1. course-ids - Array
	* 		  2. Dominant subcategory-id - Integer
	* Author        : Romil Goel
	*/
	function filterCourseByDominantSubcategory($courseIds, $dominantSubcategory)
	{
		$outputCourses = array();
		
		if(empty($courseIds))
			return $outputCourses;
		
		$nationalCourseLib = new NationalCourseLib();
		
		// get subcategory-ids of the courses provided
		$categoryListByCourse 	= $this->instituteRepository->getCategoryIdsOfListing($courseIds, 'course', 'true', TRUE);
		
		// get the dominant subcategories of each course
		foreach($courseIds as $courseid)
		{
			$dominantSubCategoriesOfCourses = $nationalCourseLib->getDominantSubCategoryForCourse($courseid, $categoryListByCourse[$courseid]);
			if(is_array($dominantSubCategoriesOfCourses) && $dominantSubCategoriesOfCourses['dominant'] == $dominantSubcategory)
				$outputCourses[] = $courseid;
		}
		
		return $outputCourses;
	}
        
        function getInstitutesNaukriData($instituteIds) {
		
            $this->CI->load->model('listing/institutemodel');
            $course_model      = $this->CI->load->model('listing/coursemodel');  

            if(empty($instituteIds))
            return array();

            $institutemodel    = new institutemodel;
            $data              = array();	   
            $salaryDataResults = $institutemodel->getNaukriSalaryData($instituteIds, 'multiple');
            $instituteWiseNaukriData = array();

            // get the naukri salary data
            foreach($salaryDataResults as $naukriDataRow)
            {
                    if($naukriDataRow['exp_bucket'] == '2-5')
                            $instituteWiseNaukriData[$naukriDataRow['institute_id']] = $naukriDataRow;

                    $totalEmployees[$naukriDataRow['institute_id']] += $naukriDataRow['tot_emp'];
            }

            // unset the naukri data for institutes whose employee count is less than 30
            foreach($totalEmployees as $instituteId => $employeeCount)
            {
                    if($employeeCount < 30)
                            unset($instituteWiseNaukriData[$instituteId]);
            }

            return $instituteWiseNaukriData;
	}

	public function getInstituteDataFilterWise($data){
		/* Logic to get Institute data according to the filters set in request  start */
	
		$examName = $data['examName'];
		$cutOff   = $data['cutOff'];
		$location = $data['location'];
		$pageNumber = $data['pageNumber'];

		$this->CI->load->builder('CategoryPageBuilder','categoryList');
		$categoryPageBuilder                 = new CategoryPageBuilder();
		$request                             = $categoryPageBuilder->getRequest();
		$request->setData(array(
								'categoryId'         => 3,
								'subCategoryId'      => 23,
								'countryId'          => 2,
								'cityId'             => $location,
								'examName'           => $examName,
								'otherExamScoreData' => array(
															  array($examName,$cutOff)
															  ),
								'pageNumber'         => $pageNumber
 								)
		);

		$request->setSortingCriteria(array(
											'sortBy' =>'examscore',
											'params' => array(
															  'order' =>'DESC',
															  'exam'  =>$examName
															  )
										   )
		);

		$request->setAppliedFilters(array(
										  'courseexams' => array($examName."_".$cutOff)
										 )
		);

		if(isset($data['pageType']) && $data['pageType'] == 'myShortlistMobile'){
			$request->setCustomNoOfResultPerPage(5);
		}

		// Set cookie for current request for pageKey
		// $this->setCookieForCurrentRequest($request);
		$categoryPage 			= $categoryPageBuilder->getCategroyPageSolr();
		$categoryPageInstitutes = $categoryPage->getInstitutes();
	/* Logic to get Institute data according to the filters set in request  end */

		return $categoryPageInstitutes;
	}

	public function addRemoveCourseShortlist($data, $shortlistAction){
		if($shortlistAction == 'add'){
			$data['status'] = 'live';
			if($data['tracking_keyid'] > 0 && $data['courseId'] > 0 && $data['userId'] > 0){
				$this->shortlistlistingmodel->addCourseToUserShortlistList($data);
				return true;
			}else{
				return false;
			}
		}else if($shortlistAction == 'remove'){
			$userId     = $data['userId'];
			$courseId   = $data['courseId'];
			$updateData = array();
			$updateData['status']           = 'deleted';
			$updateData['visitorSessionid'] = $data['visitorSessionid'];
			//$updateData['pageType']         = $data['pageType'];
			$updateData['shortlistTime']    = $data['shortlistTime'];
			$this->shortlistlistingmodel->removeCourseFromUserShortlistList($userId, $courseId, $updateData);
			return true;
		}
	}
}
