<?php
class compareCoursesLib
{
    private $CI;
    private $comparecoursesmodel;
    private $abroadListingCommonLib;
    // constructor defined here just for the reason to get CI instances for loading different classes in Codeigniter to perform business logic
    public function __construct() {
        $this->CI = & get_instance();
        $this->_setDependencies();
    }
    
    // to get model instance.
    private function _setDependencies(){
        $this->CI->load->model('studyAbroadCommon/comparecoursesmodel');
        $this->comparecoursesmodel = new comparecoursesmodel();
       	$this->abroadListingCommonLib =$this->CI->load->library('listing/AbroadListingCommonLib');		
    }
	
	public function getUserComparedCourses($onlyCourses = false){
		$validateUser = $this->CI->checkUserValidation();
		if($validateUser === "false"){
			$cookieVal = $_COOKIE['compareCourses'];
			if(empty($cookieVal)){
				return array();
			}
			$cookieVal = json_decode($cookieVal,true);
			return array_keys($cookieVal);
		}else{
			$courseIds = $this->comparecoursesmodel->getUserComparedCourses($validateUser[0]['userid']);
			if($onlyCourses)
			{	// only need courseids in db 
				return $courseIds;
			}
			$cookieVal = array();
			foreach($courseIds as $courseId){
				$cookieVal[$courseId] = 1;
			}
			$cookieVal = json_encode($cookieVal);
			setCookie('compareCourses',$cookieVal,time()+60*60*12,"/",COOKIEDOMAIN);
			return $courseIds;
		}
	}
	
	public function trackCompareAdd($courseId,$userId,$source,$sessionId){
		$this->comparecoursesmodel->trackCompareAdd($courseId,$userId,$source,$sessionId);
	}
	
	public function trackCompareRemove($courseId,$userId,$source,$sessionId){
		$this->comparecoursesmodel->trackCompareRemove($courseId,$userId,$source,$sessionId);
	}
	
	public function putAbroadComparedCoursesFromCookieToDB($sessionId){
		$validateUser = $this->CI->checkUserValidation();
		if($validateUser === "false"){
			echo json_encode(array('error'=>1,'errorMsg'=>'User ID Not Found! Could not port compared Courses!'));
			return false;
		}
		$userId = $validateUser[0]['userid'];
		$cookieVal = $_COOKIE['compareCourses'];
		if(empty($cookieVal)){
			return true;
		}
		$cookieVal = array_keys(json_decode($cookieVal,true));
		$this->comparecoursesmodel->putAbroadComparedCoursesFromCookieToDB($sessionId,$userId,$cookieVal);
	}
	/*
	 * function to get recommended courses for courses being compared(compare functionality)
	 */
	public function getRecommendationsForComparedCourses($courseList = array(), $isMobile = false)
	{
		if(count($courseList) == 0 || count($courseList)>2)
		{
			$courseList = $this->getUserComparedCourses();
		}
		if(count($courseList) == 0 || count($courseList)>2)
		{
			return false;
		}
		// in case of mobile we need only one id
		if($isMobile === true && count($courseList)>=2)
		{
			return false;
		}
		// load dependencies
		$this->CI->load->builder('ListingBuilder','listing');
		$this->CI->load->builder('LocationBuilder','location');
		$listingBuilder 			= new ListingBuilder;
		$locationBuilder 			= new LocationBuilder;
		$abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
		$abroadInstituteRepository 	= $listingBuilder->getAbroadInstituteRepository();
		$abroadUniversityRepository = $listingBuilder->getUniversityRepository();
		$locationRepository 		= $locationBuilder->getLocationRepository();
				
		$this->categorypagerecommendations = $this->CI->load->library('categoryList/CategoryPageRecommendations');
		// get recommendations for all courses
		$fullRecommendationSet = array(); // this will contain one course per university
		$flatRecommendationArray = array();
		//_p($courseList);
		$courseListObjs = $abroadCourseRepository->findMultiple($courseList);
		foreach($courseList as $index=>$courseId)
		{
			// get the recommendations for given courseId
			$recommendedCourses = $this->categorypagerecommendations->getAbroadAlsoViewedInstitutes($courseId);
			
			if(count($recommendedCourses)>0)
			{	// get their objects
				$recommendedCourseObjs = $abroadCourseRepository->findMultiple($recommendedCourses);
				// loop over each recommended course's object & pick the first one from each university (whose courses are recommended)
				foreach($recommendedCourseObjs as $recommendedCourseObj)
				{	// if the recommended course is already in the given course list,
					// OR... a course from same univ has already been recommended ...SKIP !
					if(in_array($recommendedCourseObj->getId(),$courseList)!==false ||
					   in_array($recommendedCourseObj->getUniversityId(),array_map(function($a){return $a->getUniversityId();},$courseListObjs))!== false ||
					   in_array($recommendedCourseObj->getUniversityId(),$flatRecommendationArray)!== false
					   )
					{
						continue;
					}
					if(count($fullRecommendationSet[$index][$recommendedCourseObj->getUniversityId()])==0)
					{
						$fullRecommendationSet[$index][$recommendedCourseObj->getUniversityId()] = $recommendedCourseObj;//->getId();
						$flatRecommendationArray[] = $recommendedCourseObj->getUniversityId();
					}
				}
			}
		}
		//_p($fullRecommendationSet); 
		/* checkpoint: the code above this comment works when count(courseList)>2, the code below doesn't */
		$finalRecommendationSet = array();
		// check if there is one course or more
		if(count($fullRecommendationSet) > 0)
		{
			// only one course's recommendations were found, use them, exactly 3
			if(count($fullRecommendationSet)==1)
			{
				if($fullRecommendationSet[0])
				{
					$finalRecommendationSet = array_slice($fullRecommendationSet[0],0,($isMobile?2:3));
				}
				else{
					$finalRecommendationSet = array_slice($fullRecommendationSet[1],0,($isMobile?2:3));
				}
			}
			else{ // NOTE: this block disregards presence of more than 2 courses (considers first two)
				
				$finalRecommendationSet1 = array_slice($fullRecommendationSet[0],0,2);
				// if there were 2 available from 1st course's recommnedation, 
				if(count($finalRecommendationSet1)==2)
				{	// take 3rd from 2nd course's recommnedation
					$finalRecommendationSet2 = array_slice($fullRecommendationSet[1],0,1);
				}
				else // otherwise there would be only one in first set...
				{	// so take 2 from next
					$finalRecommendationSet2 = array_slice($fullRecommendationSet[1],0,2);
				}
				//_p($finalRecommendationSet1);
				//_p($finalRecommendationSet2);
				$finalRecommendationSet = array_merge($finalRecommendationSet1, $finalRecommendationSet2);
			}
		}
		
		// return...
		//_p(array_map(function($a){return $a->getId();},$finalRecommendationSet));
		$finalRecommendationSet = array_map(function($a){return array('courseId'=>$a->getId(),
																  'universityId'=>$a->getUniversityId(),
																  'universityName'=>$a->getUniversityName(),
																  'countryName'=>$a->getCountryName());},$finalRecommendationSet);
		return $finalRecommendationSet;
	}
	
	public function markDeletedComparedCourses($missingCourses){
		$this->comparecoursesmodel->markDeletedComparedCourses($missingCourses);
	}
	
	public function clearCompareSelection($courseIds,$userId,$sessionId){
		$this->comparecoursesmodel->clearCompareSelection($courseIds,$userId,$sessionId);
	}
	
	public function validateEntityId($entityId,& $entityIdArray,$index)
    {
		if(!is_numeric($entityId))
		{
    		unset($entityIdArray[$index]);
		}
	}

	public function validateUrl()
    {
    	//fetch user entered url
		$userEnteredURL = trim(getCurrentPageURLWithoutQueryParams());
		$entityIds = explode("-", $userEnteredURL);
		unset($entityIds[0]);
		if(count($entityIds)< 1)
	    {
	    	redirect(SHIKSHA_STUDYABROAD_HOME);
	    }
	}

	public function setPageReferrer(& $displayData)
	{       

         $cookieVal['compareRefTitle'] = base64_decode($_COOKIE['compareRefTitle']);
         $cookieVal['compareRefUrl'] = base64_decode($_COOKIE['compareRefUrl']);
         if(!$displayData['loggedInUserData'] && !$_SERVER['HTTP_REFERER']){
        	$displayData['referrerPageTitle'] = "Home";
            $displayData['referrerPageURL'] = SHIKSHA_STUDYABROAD_HOME;
            return;
         }
        
		// _p($_SERVER['HTTP_REFERER']);
		// die;
		//if referrer is rmc success page
		else if(!empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'],"submission-success") != false))
        {
        	
            $displayData['referrerPageTitle'] = "Home";
            $displayData['referrerPageURL'] = SHIKSHA_STUDYABROAD_HOME;
        }


		//if its the first time hit to the page store the referrer
		if(!empty($cookieVal))
		{
			if(!empty($_SERVER['HTTP_REFERER']))
			{
				if(strpos($_SERVER['HTTP_REFERER'],"comparepage") != false || strpos($_SERVER['HTTP_REFERER'],"login") != false || strpos($_SERVER['HTTP_REFERER'],"signup") != false)
				{
					$displayData['referrerPageTitle'] =  $cookieVal['compareRefTitle'];
					$displayData['referrerPageURL'] = $cookieVal['compareRefUrl'];
				}
				else
				{
					$displayData['referrerPageTitle'] =  $cookieVal['compareRefTitle'];
					$displayData['referrerPageURL'] = $_SERVER['HTTP_REFERER'];
					//create a cookie for 5 mins
					setcookie("compareRefUrl", base64_encode($displayData['referrerPageURL']), time()+600); 
				}
			}
			else
			{
				$displayData['referrerPageTitle'] = "Home";
             	$displayData['referrerPageURL'] = SHIKSHA_STUDYABROAD_HOME;
			}
		}
         else{ //else in all other cases

             $displayData['referrerPageTitle'] = "Home";
             $displayData['referrerPageURL'] = SHIKSHA_STUDYABROAD_HOME;
         }
    }

    /*
	 * set flag for each row. If data is not available, no need to show the row itself
	 * 
	 */
	public function setRowFlagsForDisplay(&$displayData, $courseData)
	{
		$applicationDeadline = $admissionType = $otherExpense = $Marks12th = $MarksGrad = $MarksPostGrad = $workXP = $exams = array();
		$sop = $lor = $cv = $essay = $applicationFees = $yearEstd = $accreditation = $accomodation = $scholarship = $classProfile = $ranks = $media = $univEmail = $intlStudents = $fbLink = array();
		
		foreach($courseData as $courseId=>$courseObj)
		{
			// application deadline
			$universityCourseProfileId = $displayData['applicationProcessData']['courseProcessData'][$courseObj->getId()]['universityCourseProfileId'];
            $tempArray =  $displayData['applicationProcessData']['submissionDateData'][$universityCourseProfileId];
			if(!empty($tempArray))
			{
				$applicationDeadline[] = $tempArray;
			}
			// admission type
			$tmp = $displayData['applicationProcessData']['courseProcessData'][$courseObj->getId()]['admissionType'];
			if(!empty($tmp)){
				$admissionType[] = $tmp;
			}
			// 1st year other expenses
			if(!empty($displayData['firstYearFees'][$courseObj->getId()]['customFeesIndianDisplayableFormat']))
			{
				$otherExpense[] = $displayData['firstYearFees'][$courseObj->getId()]['customFeesIndianDisplayableFormat'];
			}
			// marks
			$tmp =$displayData['courseEligibilityData'][$courseObj->getId()]['12thCutoff'];
			if(!empty($tmp) && $tmp!="0")
			{
				$Marks12th[] = $tmp;
			}
			$bachelorCutoff = $displayData['courseEligibilityData'][$courseObj->getId()]['bachelorCutoff'];
			if(!empty($bachelorCutoff) && $bachelorCutoff!="0.00"){
				$MarksGrad[] = $bachelorCutoff;
			}
			$pgCutoff =  $displayData['courseEligibilityData'][$courseObj->getId()]['pgCutoff'];
			if(!empty($pgCutoff) && $pgCutoff!=""){
				$MarksPostGrad[] = $pgCutoff;
			}
			$workExperienceValue =  $displayData['courseEligibilityData'][$courseObj->getId()]['workExperniceValue'];
			
                        if(!empty($workExperienceValue) && $workExperienceValue!="0")
			{
				$workXP[] = $workExperienceValue;
			}
			// EXAM
			$courseAttr = $courseObj->getAttributes();
			$examInfo   = $courseAttr['examRequired'];
			
			if($displayData['applicationProcessData']['courseProcessData'][$courseObj->getId()]['additionalRequirement']!="" &&  $applicationProcessDataFlag){
				$exams[] = $displayData['applicationProcessData']['courseProcessData'][$courseObj->getId()]['additionalRequirement'];
			}
            else if(!$displayData['applicationProcessDataFlag'] && $examInfo!=""){
                $exams[] = $examInfo->getValue();
            }
            // apply fields
			if($displayData['applicationProcessData']['courseProcessData'][$courseObj->getId()]['sopRequired']=='1')
			{
				$sop[] = true;
			}
			if($displayData['applicationProcessData']['courseProcessData'][$courseObj->getId()]['lorRequired']=='1')
			{
				$lor[] = true;
			}
			if($applicationProcessData['courseProcessData'][$courseObj->getId()]['essayRequired']=='1')
			{
				$essay[] = true;
			}
			if($displayData['applicationProcessData']['courseProcessData'][$courseObj->getId()]['cvRequired']=='1')
			{
				$cv[] = true;
			}
			if($displayData['applicationProcessData']['courseProcessData'][$courseObj->getId()]['applicationFeeDetail']=='1')
			{
				$applicationFees[] = true;
			}
			if(count($sop)==0 &&count($lor)==0 &&count($essay)==0 &&count($cv)==0 &&count($applicationFees)==0)
			{
				$displayData['applicationProcessDataFlag'] = 0;
			}
			// univ
            $establishedYear = $displayData['univDataObjs'][$courseObj->getUniversityId()]->getEstablishedYear();
			if(!empty($establishedYear))
			{
				$yearEstd[] = $establishedYear;
			}
			$univAccreditation = $displayData['univDataObjs'][$courseObj->getUniversityId()]->getAccreditation();
			if(!empty($univAccreditation))
			{
				$accreditation[] = $univAccreditation;
			}
			$campusAccomodation = $displayData['univDataObjs'][$courseObj->getUniversityId()]->getCampusAccommodation();
			if(!empty($campusAccomodation))
			{
				$accomodation[] = $campusAccomodation;
			}
			if($courseObj->getScholarshipLinkCourseLevel()!=''|| $courseObj->getScholarshipLinkDeptLevel()!='' || $courseObj->getScholarshipLinkUniversityLevel()!='' )
			{
				$scholarship[] = true;
			}
			// misc
			$classProfileObject = $courseObj->getClassProfile();
            $getAverageWorkExperience= $classProfileObject->getAverageWorkExperience();
            $getAverageGPA= $classProfileObject->getAverageGPA();                                                                
            $getAverageXIIPercentage= $classProfileObject->getAverageXIIPercentage();                                                                
            $getAverageGMATScore= $classProfileObject->getAverageGMATScore();                                                                
            $getAverageAge= $classProfileObject->getAverageAge();                                                                
            $getPercenatgeInternationalStudents= $classProfileObject->getPercenatgeInternationalStudents();                                                                
			if(!(empty($getAverageWorkExperience) && empty($getAverageGPA) && empty($getAverageXIIPercentage) && empty($getAverageGMATScore) && empty($getAverageAge) && empty($getPercenatgeInternationalStudents)))
			{
				$classProfile[] = true;
			}
			if(!empty($displayData['courseRankDetails'][$courseObj->getId()]['rank'])) {
				$ranks[] = $courseRankDetails[$courseObj->getId()]['rank'];
			}
			//photovideo
			if(count($displayData['univDataObjs'][$courseObj->getUniversityId()]->getPhotos())>0 &&
			   count($displayData['univDataObjs'][$courseObj->getUniversityId()]->getVideos())>0)
			{
				$media[] = true;
			}
			// contact email
			if(!empty($displayData['universityContactDetails'][$courseObj->getUniversityId()]['universityEmail'])) {
				$univEmail[] = true;
			}
			// international students link
			$internationalPageLink = $displayData['univDataObjs'][$courseObj->getUniversityId()]->getInternationalStudentsPageLink();
			if(!empty($internationalPageLink))
			{
				$intlStudents[] = true;
			}
			// fb link
			$facebooklink = $displayData['univDataObjs'][$courseObj->getUniversityId()]->getFacebookPage();
			if(!empty($facebooklink))
			{
				$fbLink[] = true;
			}
		}
		//setting the flags in the display data for showing the tuples to be shown
		$displayData['applicationDeadlineFlag'] = count($applicationDeadline)>0?true:false;
		$displayData['admissionTypeFlag'	  ] = count($admissionType)>0?true:false;
		$displayData['otherExpenseFlag'		  ] = count($otherExpense)>0?true:false;
		$displayData['Marks12thFlag'		  ] = count($Marks12th)>0?true:false;
		$displayData['MarksGradFlag'		  ] = count($MarksGrad)>0?true:false;
		$displayData['MarksPostGradFlag'	  ] = count($MarksPostGrad)>0?true:false;
		$displayData['workXPFlag'			  ] = count($workXP)>0?true:false;
		$displayData['examsFlag'			  ] = count($exams)>0?true:false;
		$displayData['sopFlag'				  ] = count($sop)>0?true:false;
		$displayData['lorFlag'				  ] = count($lor)>0?true:false;
		$displayData['essayFlag'			  ] = count($essay)>0?true:false;
		$displayData['cvFlag'				  ] = count($cv)>0?true:false;
		$displayData['applicationFeesFlag'	  ] = count($applicationFees)>0?true:false;
		$displayData['yearEstdFlag'			  ] = count($yearEstd)>0?true:false;
		$displayData['accreditationFlag'	  ] = count($accreditation)>0?true:false;
		$displayData['accomodationFlag'		  ] = count($accomodation)>0?true:false;
		$displayData['scholarshipFlag'		  ] = count($scholarship)>0?true:false;
		$displayData['classProfileFlag'		  ] = count($classProfile)>0?true:false;
		$displayData['rankFlag'		  		  ] = count($ranks)>0?true:false;
		$displayData['mediaFlag'		  	  ] = count($media)>0?true:false;
		$displayData['univEmailFlag'		  ] = count($univEmail)>0?true:false;
		$displayData['intlStudentsFlag'		  ] = count($intlStudents)>0?true:false;
		$displayData['fbLinkFlag'		  	  ] = count($fbLink)>0?true:false;
	}
	

	 /*
    	* This function prepares application data for the course which is shiksha apply enabled
    	* it also takes care if the course is mapped to a shiksha apply enabled univerity
    	* and sets applicationProcessDataFlag as 1 
     	* else sets applicationProcessDataFlag as 0
    */
    public function prepareApplicationProcessDetails(& $displayData,$courseIds)
    {
      	$applicationProcessData = $this->abroadListingCommonLib->getApplicationProcessData($courseIds);

    	if(!empty($applicationProcessData) && count($applicationProcessData)>0){
			$displayData['applicationProcessDataFlag'] = 1;

			//restructure the data for submission dates
    		$tempSubmissionDateArray = array();
	    	if(!empty($applicationProcessData['submissionDateData']))
	    	{
	    		foreach ($applicationProcessData['submissionDateData'] as $key => $value) {
	    			$tempSubmissionDateArray[$value['applicationProfileId']][] = $value;
	    		}
	    		
	    	}
	    	unset($applicationProcessData['submissionDateData']);
	    	
	    	$processDataArray = array();
	    	//restructure the data index it on course ids
	    	if(!empty($applicationProcessData))
	    	{
	    		foreach ($applicationProcessData as $key => $value) 
	    		{
	    			$processDataArray[$value['courseId']] =$value;
	    		}
	    	}
	    	//resset again
	    	unset($applicationProcessData);
	    	$applicationProcessData['courseProcessData'] 	= $processDataArray;
	    	$applicationProcessData['submissionDateData'] 	= $tempSubmissionDateArray;
	    

			foreach ($applicationProcessData['courseProcessData'] as $key => $value) 
			{
				if($value['applicationFeeDetail']==1){			
					$feeData = $this->abroadListingCommonLib->convertCurrency($value['currencyId'], 1, $value['feeAmount']);
					if($feeData)
					{
						//$applicationProcessData['convertedFeeDetail'] = $feeData;
						$applicationProcessData['courseProcessData'][$key]['convertedFeeDetail'] = $this->abroadListingCommonLib->getIndianDisplableAmount($feeData, 2);
//						$applicationProcessData['courseProcessData'][$key]['convertedFeeDetail'] = str_replace(array('Thousand','Lakhs','Lakh'), array('K','Lacs','Lac'), $applicationProcessData['courseProcessData'][$key]['convertedFeeDetail']);
					}
				}
			}
			
			$displayData['applicationProcessData'] = $applicationProcessData;
		}else{
			$displayData['applicationProcessDataFlag'] = 0;
		}
	
    }
    
    public function getCourseApplicationEligibilityData(&$displayData,$courseIds){
         $courseApplicationEligibilityData=$this->abroadListingCommonLib->getCourseApplicationEligibilityData($courseIds);
         foreach ($courseApplicationEligibilityData as $courseEligibilityData){
             $displayData['courseEligibilityData'][$courseEligibilityData['courseId']]=$courseEligibilityData;
         }
    }

    public function prepareCoursesExamDetails(& $displayData,$courseData)
    {
		$this->CI->load->library('listing/AbroadListingCommonLib');		
	    $this->abroadListingCommonLib 	= new AbroadListingCommonLib();
    	$tempCourseDataArray = $this->abroadListingCommonLib->sortEligibilityExamForAbroadCourses($courseData);
    	$tempCourseDataArray = $this->abroadListingCommonLib->rearrangeCustomExams($tempCourseDataArray);
    	$eligibilityExamsArray = array();
    	foreach ($tempCourseDataArray as $courseObj) {
    		$eligibilityExamsArray[$courseObj->getId()] =  $courseObj->getEligibilityExams();
    	}
    	
    	$displayData['eligibilityExamsArray'] = $eligibilityExamsArray;
    	unset($tempCourseDataArray);
    }

    public function trackCompareButtonClick($insertionData){
    	$this->comparecoursesmodel->trackCompareButtonClick($insertionData);
    }

    public function migrateCompareButtonClickTracking($sessionId){
    	$validateUser = $this->CI->checkUserValidation();
		if($validateUser === "false"){
			echo json_encode(array('error'=>1,'errorMsg'=>'User ID Not Found! Could not port compared Courses!'));
			return false;
		}
		$userId = $validateUser[0]['userid'];
		$this->comparecoursesmodel->migrateCompareButtonClickTracking($userId,$sessionId);
    }
	/*
	 * gets all courses of multiple universty ids (used to populate course dropdowns) 
	 */
	public function getCoursesByUnivIds($univIds =array())
	{
		if(count($univIds)==0)
		{
			return false;
		}
		$abroadCourseFinderModel = $this->CI->load->model('listing/abroadcoursefindermodel');
		$courseData = $abroadCourseFinderModel->getCoursesOfferedByMultipleUniversities($univIds,'ALL');
		$returnData = array();
		foreach($courseData['courses'] as $row)
		{
			$returnData[$row['universityID']][] = array('courseId'=>$row['courseID'],
																				'courseName'=>$row['courseName']);
		}
		return $returnData;
	}
	/*
	 * function that returns courses of a university selected by user for comparison with another course-univ
	 * accepts a univId & course subjected to comparison
	 * returns courses of the university including the one that is supposed to be chosen by default.
	 */
	public function getUniversityCoursesForComparison($univId, $courseId)
	{
		$abroadCourseFinderModel = $this->CI->load->model('listing/abroadcoursefindermodel');
		$courseData = $abroadCourseFinderModel->getCoursesOfferedByUniversity($univId,'list');
		$courseIds = $courseData['course_ids'];
		if(count($courseIds) == 0)
		{
			return false;
		}else{
			$abrodListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
			$courseViewCounts = $abrodListingCommonLib->getViewCountForListingsByDays($courseIds,'course',21);
			arsort($courseViewCounts);
			//$displayData['popularCourses'] = $this->abroadListingCommonLib->processPopularCourses(array_map(function($ele){return $ele['course_id'];},$displayData['courseList']),$referrerCourse);
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$this->abroadCourseRepository 			= $listingBuilder->getAbroadCourseRepository();
			$coursesToBeFound = array_merge(array_keys($courseViewCounts),array($courseId));
			$allCourseObjs = $this->abroadCourseRepository->findMultiple($coursesToBeFound);
			$comparedCourseObj = $allCourseObjs[$courseId];
			unset($allCourseObjs[$courseId]);
			$matchedCourseId = $this->_getMatchingCourse($allCourseObjs, $comparedCourseObj);
			$returnData = array('courses'=>array());
			
			// if the compared course is compared against its own university, we need to remove it from the list of returned courses
			unset($allCourseObjs[$courseId]);
			
			foreach($allCourseObjs as $courseId=>$course)
			{
				$returnData['courses'][$courseId] = array('courseName'=>$course->getName(),'courseId'=>$courseId);
			}
			if(!is_null($matchedCourseId))
			{
				$returnData['matched'] = $matchedCourseId;
			}else{
				$this->abroadUniversityRepository 		= $listingBuilder->getUniversityRepository();
				$returnData['univ'] = $this->abroadUniversityRepository->find($univId);
				$returnData['univName'] = htmlentities(formatArticleTitle($returnData['univ']->getName(),25));
				$location = $returnData['univ']->getLocation();
				$returnData['location'] = htmlentities($location->getCity()->getName()).", ".htmlentities($location->getCountry()->getName());
			}

			return $returnData;
		}
	}
	/*
	 * get matching course for another course among the list of courses from which a match is to be found
	 */
	private function _getMatchingCourse($courseObjs, $comparedCourseObj)
	{
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		$abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
		$desiredCourses = $abroadCommonLib->getAbroadMainLDBCourses();
		$desiredCourses = array_map(function($a){ return $a['SpecializationId']; },$desiredCourses);
		$comparedLDBCourseId = $comparedCourseObj->getDesiredCourseId();
		if(in_array($comparedLDBCourseId,$desiredCourses))
		{
			$compareBy = "desiredCourse";
		}else{
			$compareBy = "category";
			$comparedSubcatObj = $categoryRepository->find($comparedCourseObj->getCourseSubCategory());
		}
		foreach($courseObjs as $courseId=>$courseObj)
		{
			if($compareBy == "desiredCourse")
			{
				if($courseObj->getDesiredCourseId() ==$comparedLDBCourseId)
				{
					$matchedCourseId = $courseId;
					break;
				}
			}else{
				$courseSubcatObj = $categoryRepository->find($courseObj->getCourseSubCategory());
				if($courseObj->getCourseLevel1Value() ==$comparedCourseObj->getCourseLevel1Value()   &&
				   $courseSubcatObj->getParentId() == $comparedSubcatObj->getParentId())
				{
					$matchedCourseId = $courseId;
					break;
				}
			} //end else
		}
		return $matchedCourseId;
	}
}
