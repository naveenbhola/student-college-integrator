<?php

include_once '../WidgetsAggregatorInterface.php';

class RegistrationWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
		$this->_CI->load->library('recommendation/recommendation_front_lib');
		$this->_CI->load->library('listing/NationalCourseLib');
		$this->_CI->load->helper('listing');
		$this->_CI->load->helper('string');
		$this->_CI->load->builder('ListingBuilder','listing');
		$this->_CI->load->builder('LDBCourseBuilder','LDB');
		$this->_CI->load->builder('LocationBuilder','location');
	}
	
	public function getWidgetData() {
		if($this->_params["userInfo"] == 'false') {
			return array('key'=>'registrationWidget','data'=> array('YES',$this->_params['categoryId']));
		}
		return array('key'=>'recommendationsWidget','data'=>(array()));
		
		/*
		 *If logged in get data for recommendations widget
		*/
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$LDBCourseBuilder = new LDBCourseBuilder;
		$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		
		$data = array();
		$courseData = array();
		$recommendedCourses = array();
		$coursesInReco = array();
		$algoForRecommendation = array();
		$recommendations = array();
		$recommendationsApplied = array();
		$userInfo = $this->_params["userInfo"];
		$userId = $userInfo[0]["userid"];
		$subCategoryId = $this->_params["courseHomePageId"];
		$categoryId = NULL;
		$ldbCourseIds = array();
		global $COURSE_PAGES_SUB_CAT_ARRAY;
		
		$ldbCoursesForSubCat = $LDBCourseRepository->getLDBCoursesForSubCategory($subCategoryId);
		//$categoryId = $ldbCoursesForSubCat[0]->getCategoryId();
		foreach($ldbCoursesForSubCat as $ldbCourse) {
			$ldbCourseIds[] = $ldbCourse->getId();
		}
		
		$recommendations = $this->_CI->recommendation_front_lib->getRecommendations(array($userId), array(), 'no', 9, array($categoryId => $subCategoryId), $ldbCourseIds);
		$recommendations = json_decode(gzuncompress(base64_decode($recommendations)));
		$recommendations = $recommendations->recommendations;
		
		foreach($recommendations as $user) {
			$user = $user->recommendations;
			foreach($user as $category){
				$category = $category->recommendations;
				foreach($category as $algo => $results) {
					foreach($results as $result) {
						$recommendedCourses[$result->institute_id] = array($result->course_id);
						$algoForRecommendation[$result->institute_id] = $algo;
					}
				}
			}
		}
		
		$recommendations = $instituteRepository->findWithCourses($recommendedCourses);	
		
		foreach($recommendations as $institute) {
			$course = $institute->getFlagshipCourse();
			$instituteId = $institute->getId();
			$courseID = $course->getId();
			$courseData[$instituteId]['courseId'] = $course->getId();
			$courseData[$instituteId]['isPaid'] = $course->isPaid();
			$courseData[$instituteId]['instituteFullName'] = $institute->getName();
			$courseData[$instituteId]['courseFullName'] = $course->getName();
			$courseData[$instituteId]['instituteName'] = strlen($institute->getName()) > 40 ? substr($institute->getName(), 0, 40).'...' : $institute->getName();
			$courseData[$instituteId]['courseName'] = strlen($course->getName()) > 45 ? substr($course->getName(), 0, 45).'...' : $course->getName();
			$courseData[$instituteId]['instituteHeaderImage'] = $institute->getMainHeaderImage();
			$courseData[$instituteId]['instituteThumbURL'] = $institute->getMainHeaderImage()->getThumbURL();
			$courseData[$instituteId]['courseURL'] = $course->getURL();
			
			$mainLocation = $course->getMainLocation();
			$mainLocationId = $mainLocation->getLocationId();
			$mainCity = $mainLocation->getCity();
			$mainCityId = $mainCity->getId();
			$mainLocalityId = $mainLocality ? $mainLocality->getId() : 0;
			
			$courseData[$instituteId]['mainCityName'] = $mainCity->getName();
			$courseData[$instituteId]['mainCityId'] = $mainCityId;
			$courseData[$instituteId]['mainLocalityId'] = $mainLocalityId;
			
			$exams = $course->getEligibilityExams();
			$cutoff = '';
			foreach($exams as $exam) {
			$examName = $exam->getAcronym();
			$marks = $exam->getMarks();
			if($marks != 0) {
				$cutoff .= $examName.' ('.$marks.'), ';
			}
			}
			$cutoff = substr($cutoff, 0, -2);
			
			$courseData[$instituteId]['eligibility'] = $cutoff;
			$courseData[$instituteId]['fees'] = $course->getFees($mainLocationId)->__toString();
			$courseData[$instituteId]['ranking'] = $course->getRanking()->__toString();
			$courseData[$instituteId]['duration'] = $course->getDuration()->__toString();
			$courseData[$instituteId]['mode'] = $course->getCourseType();
			$courseData[$instituteId]['algo'] = $algoForRecommendation[$instituteId];
                        $customParams = base64_encode(json_encode(array('institute_id' => $instituteId, 'course_id'=>$courseID, 'sourcePage'=>'CoursePage_Reco','clickedCourseId'=>'0','clickedInstituteId'=>'0','algo'=>$algoForRecommendation[$instituteId])));
                        $courseData[$instituteId]['onReqBroClickAction'] = 'onclick="makeResponse('.$instituteId.', \''.base64_encode($institute->getName()).'\', '.$courseID.', \''.base64_encode($course->getName()).'\', \'recoLayerCallback\', \'CoursePage_Reco\', \'similar_institutes\', \''.$customParams.'\',\''.DESKTOP_NL_COURSE_HOME_PAGE_RIGHT_DEB.'\');"';
			//$courseData[$instituteId]['onReqBroClickAction'] = 'onclick="doAjaxApply('.$instituteId.', '.$courseID.', '.$mainCityId.', '.$mainLocalityId.', '.$mainCityId.', \'CoursePage_Reco\', 0, 0, \''.$algoForRecommendation[$instituteId].'\');"';
			$courseData[$instituteId]['onActivityTrackAction'] = 'onclick="processActivityTrack(0, '.$courseID.', 0, \'CoursePage_Reco_Viewed\', \'CoursePage_Reco\', \''.$algoForRecommendation[$instituteId].'\', \''.$courseData[$instituteId]['courseURL'].'\', event);"';
			$courseData[$instituteId]['paramsForApply'] = getParametersForApply($userInfo,$course,$mainCityId,$mainLocalityId);
			
			if(isset($_COOKIE['applied_'.$courseID]) && $_COOKIE['applied_'.$courseID] == 1) {
				$recommendationsApplied[] = $courseID;
			}
			
			$coursesInReco[] = $courseID;
		}
		
		$numberOfRecommendations = count($recommendedCourses) > 9 ? 9 : count($recommendedCourses);
		$widgetHeading = '';
		$widgetHeading .= $COURSE_PAGES_SUB_CAT_ARRAY[$subCategoryId]['Name'];
		$widgetHeading .= ' Institutes Recommended For You';
		
		$data['recommendationsExist'] = count($recommendedCourses) > 0 ? 1 : 0;
		$data['numOfSlides'] = ceil($numberOfRecommendations / 3);
		$data['recommendationsApplied'] = $recommendationsApplied;
		$data['brochureURL'] = $this->_CI->nationalcourselib->getMultipleCoursesBrochure($coursesInReco);
		$data['uniqId'] = random_string('alnum', 6);
		$data['widgetHeading'] = $widgetHeading;
		$data['courseData'] = $courseData;
		
		return array('key'=>'recommendationsWidget','data'=>($data));
	}
}