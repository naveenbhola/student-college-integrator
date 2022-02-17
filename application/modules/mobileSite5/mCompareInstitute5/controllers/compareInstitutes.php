<?php
/**
 * Controller for Compare Institute tool
*/

class compareInstitutes extends ShikshaMobileWebSite_Controller {

	private $comparePageLib = '';
	private $maxCompares = '';
	private $currentCourseCount = 0;
	private $isStaticPage = '';

	public function __construct(){
		parent::__construct();
		$this->maxCompares = 2;
		$this->isStaticPage = false;
	}

		/**
		* Purpose       : Initialization method for Compare Institute tool
		* Params        : 1. Reference of display data array
		* To Do         : none
		* Author        : Ankur Gupta
		*/
		function _init(& $displayData, $library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
			if(is_array($helper)){
				$this->load->helper($helper);
			}
			if(is_array($library)){
				$this->load->library($library);
			}
			if(($this->userStatus == "")){
				$this->userStatus = $this->checkUserValidation();
			}
			
			$this->load->config('mcommon5/mobi_config');
			$this->load->helper('mcommon5/mobile_html5');
			//$this->load->helper('mcommon5/mobile');

			$displayData['validateuser'] = $this->checkUserValidation();
			$displayData['trackForPages'] = true;
			
			if($displayData['validateuser'] !== 'false') {
                                $this->load->model('user/usermodel');
                                $usermodel = new usermodel;

                                $userId = $displayData['validateuser'][0]['userid'];
                                if(isset($userId) && $userId>0)
                                {
                                        $user = $usermodel->getUserById($userId);
                                        if(!is_object($user) ||  empty($user))
                                        {
                                                $displayData['loggedInUserData'] = false;
                                        }
                                        else
                                        {
                                                $name = $user->getFirstName().' '.$user->getLastName();
                                                $email = $user->getEmail();
                                                $userFlags = $user->getFlags();
                                                $isLoggedInLDBUser = $userFlags->getIsLDBUser();
                                                $displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
                                        }
                                }
                        }
                        else {
                                $displayData['loggedInUserData'] = false;
                        }
	
		}

	private function init(&$displayData){
		if(!empty($this->logged_in_user_array) && is_array($this->logged_in_user_array)){
			$displayData['validateuser'][0] = $this->logged_in_user_array;
		}else{
			$displayData['validateuser'] = 'false';
		}
		$this->load->library(array('comparePage/comparePageLib'));
	}

	public function redirectToNewCompareUrl($urlStr){
		$newUrl = SHIKSHA_HOME.'/resources/college-comparison'.$urlStr;
		redirect($newUrl, 'location', 301);
		die;
	}
		
		
	/**
	* Purpose       : Main function for the Compare Institute tool
	* Params        : List of Course Ids which needs to be compared
	* To Do         : none
	* Author        : Virender Singh
	*/

	public function mainComparePage($courseIds = ''){
		$displayData = array();
		$this->init($displayData);
		$displayData['compare_count_max'] = $this->maxCompares;
		$displayData['compareData'] = array();
		$displayData['currentCourseCount'] = $this->currentCourseCount = 0;
		$this->comparePageLib = new comparePageLib();
		$displayData['userComparedData'] = $this->comparePageLib->getComparedData('mobile'); // this is used to get tracking key for coruse		
		$this->ampRedirect(array_keys($displayData['userComparedData']));
		$showFeesDiscArr = array(28499,36321);  // To show fee disclaimer for Lovely Professional Univ Courses
		$courseIdArr = array();
        $courseIds = $this->security->xss_clean($courseIds);

        // Fixed VA Issue SHIK-783
        $userCompareUrl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$actualUrl  = SHIKSHA_HOME.'/resources/college-comparison'.$courseIds;
		if($actualUrl != $userCompareUrl && $actualUrl."?profiling=1" != $userCompareUrl){
			$this->redirectToNewCompareUrl($courseIds);
		}

		if($courseIds != ''){
			$processedInput = $this->comparePageLib->processInput($courseIds, $this->maxCompares, 'mobile');
			$courseIdArr = $processedInput['courseIdArr'];
			$this->isStaticPage = $displayData['isStaticPage'] = $processedInput['isStaticPage'];
		}
		if(($courseIds != '') && empty($courseIdArr)){ //Redirect to compare base url to prevent unwanted terms in url(VA Changes) 
			$this->redirectToNewCompareUrl();
		}
		//Load all resources inside this if
		if(!empty($courseIdArr)){
			$displayData['courseIdArr'] = $courseIdArr;
			$this->comparePageLib->getCourseObjectsData($displayData);
			$this->currentCourseCount = $displayData['currentCourseCount'];
			//If all courses are invalid
			if($this->currentCourseCount == 0){
				redirect(SHIKSHA_HOME.'/resources/college-comparison', 'location', 301);
				die;
			}
			$this->comparePageLib->getInstituteObjectsData($displayData);
			$this->comparePageLib->processAcademicUnitCookie($displayData);
			$this->comparePageLib->getDropdownCourseList($displayData);
			$this->comparePageLib->getComparisionData($displayData);
			foreach ($courseIdArr as $crsId) {
				$displayData['showFeeDisc'][$crsId] = in_array($displayData['instIdArr'][$crsId], $showFeesDiscArr) ? 1 : 0;
			}
			if($this->isStaticPage){
				$displayData['seoDetails'] = $this->getStaticURLDetailsFromDB($displayData['courseIdArr']);
			}
			$this->listingcommonlib = $this->load->library('listingCommon/ListingCommonLib');
			$displayData['checkForDownloadBrochure'] = $this->listingcommonlib->checkForDownloadBrochure($courseIdArr);
		}else{
			$this->comparePageLib->removeAcademicUnitCookie();
		}
		if($this->currentCourseCount > 0){
			$this->comparePageLib->getCourseAttributesData($displayData);
		}else{
			$displayData['courseAttributeData_fullTimeMba']['fullTimeMba'] = array('stream_id' => MANAGEMENT_STREAM, 'base_course_id' => MANAGEMENT_COURSE, 'education_type' => EDUCATION_TYPE);
			$displayData['courseAttributeData_beBtech']['beBtech'] = array('stream_id' => ENGINEERING_STREAM, 'base_course_id' => ENGINEERING_COURSE);
		}
		$displayData['boomr_pageid'] = "ComparePage";
		$displayData['compareHomePageKeyId']=613; 
		$this->_MISTrackingVariables($displayData);
		$beaconTrackData = $this->comparePageLib->prepareBeaconTrackData('comparePage',$displayData['courseIdArr'],$displayData['courseObjs']);
		$displayData['beaconTrackData'] = $beaconTrackData;
		$instForGTM = $this->comparePageLib->prepareInstArrayForCompareGTM($beaconTrackData);
		$displayData['gtmParams'] = $this->comparePageLib->getGTMArray($beaconTrackData, $instForGTM);

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_ComparePage','entity_id'=>'0','stream_id'=>$displayData['gtmParams']['stream'],'substream_id'=>$displayData['gtmParams']['substream'],'specialization_id'=>$displayData['gtmParams']['specialization'],'baseCourse'=>$displayData['gtmParams']['baseCourseId'],'cityId'=>$displayData['gtmParams']['cityId'],'stateId'=>$displayData['gtmParams']['stateId'],'educationType'=>$displayData['gtmParams']['educationType'],'deliveryMethod'=>$displayData['gtmParams']['deliveryMethod']);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        $displayData['noJqueryMobile'] = true;
		$this->load->view('compareHomepage',$displayData);
	}

	private function _MISTrackingVariables(&$displayData){
		$displayData['shortlistTrackingPageKeyId'] = 309;
		$displayData['emailTrackingPageKeyId']     = 310;
		$displayData['compareHomePageKeyId']       = 613;
	}

	    public function getPopularCoursesForComparision($baseEntityArray = array()){
			if(is_array($baseEntityArray) && count($baseEntityArray)){
				$this->comparePageLib = $this->load->library('comparePage/comparePageLib');
				$displayData = $this->comparePageLib->getPopularCoursesForComparision($baseEntityArray,'mobile');
				if($displayData){
					echo $this->load->view('popularInstituteComparision',$displayData,true);
				}
			}
	    }		

		function getInstituteCoursesAjax(){
			$instituteId = $this->input->post('instituteId');
			$listingType = $this->input->post('listingType');
			$firstInstituteId = $this->input->post('firstInstituteId');
			$firstlistingType = $this->input->post('firstlistingType');
			$firstSelectedCourseId = $this->input->post('firstCourseId');
			$secondSelectedCourseId = $this->input->post('secondCourseId');

			//Check if any of the Institute Course lies in same sub-cat as First course. If yes, simply return this course and then we can redirect to the new page
			if($firstSelectedCourseId !='' && $firstSelectedCourseId>0){
				$comparePageLib = $this->load->library('comparePage/comparePageLib');
				$courseOnLevel  = $comparePageLib->getCourseListOnLevel($firstSelectedCourseId, $instituteId, $listingType);
			}

			foreach ($courseOnLevel as $courseVal){
				if($courseVal['course_id'] != $firstSelectedCourseId && $courseVal['course_id'] != $secondSelectedCourseId){
					echo $courseVal['course_id'];
					return;
				}
			}

			//3- get all client course of institute
			$courseList = array();
	        $this->load->library("nationalInstitute/InstituteDetailLib");
        	$lib = new InstituteDetailLib();
        	$result = $lib->getInstituteCourseIds($instituteId, $listingType);
        	$courseList = $result['courseIds'];

			$instituteArr = array($instituteId=>$courseList);

			if($firstInstituteId != '' && $firstInstituteId != '0'){
				$courseListPrev = array();
		        $this->load->library("nationalInstitute/InstituteDetailLib");
	        	$lib = new InstituteDetailLib();
	        	$result = $lib->getInstituteCourseIds($firstInstituteId, $firstlistingType);
	        	$courseListPrev = $result['courseIds'];
				$instituteArr = array($firstInstituteId=>$courseListPrev, $instituteId=>$courseList);
			}

			// load the institute builder
		    $this->load->builder("nationalInstitute/InstituteBuilder");
		    $instituteBuilder = new InstituteBuilder();
		    $instituteRepo = $instituteBuilder->getInstituteRepository();

			$institute = $instituteRepo->findWithCourses($instituteArr);
			
			if((is_array($institute) && count($institute)<0) || $institute ==''){
				return '';
			}
			
			$displayData = array();
			$displayData['institutes'] = $institute;
			$displayData['compare_count_max'] = 2;
			$displayData['filled_compares'] = count($instituteArr);
			$this->load->view('autoSuggestorInstituteView',$displayData);
			$this->load->view('autoSuggestorCourseListView',$displayData);
		}

		function getStaticURLDetailsFromDB($courseIdArr){
			$courseId1 = isset($courseIdArr[0]) ? $courseIdArr[0] : 0;
			$courseId2 = isset($courseIdArr[1]) ? $courseIdArr[1] : 0;
			$this->load->model('comparePage/comparepagemodel');
			$this->comparePageModel = new comparepagemodel;
			$seoData = array();
			$seoData = $this->comparePageModel->getStaticURLDetailsFromDB($courseId1,$courseId2);
			if(count($seoData)>=1){
				$data = $seoData[0];
				if($data['course1_location']!=''){					
					$course1Location = " ".$data['course1_location'];
				}
				if($data['course2_location']!=''){					
					$course2Location = " ".$data['course2_location'];
				}
				$seoData['title'] = "Compare ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
				$seoData['description'] = "Compare ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." on courses, fees, placements, faculty, infrastructure, alumni and other details";
				$seoData['heading'] = "College Comparison: ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
				$seoData['canonical'] = SHIKSHA_HOME."/resources/college-comparison-".str_replace(' ','-',strtolower($data['course1_name']))."-".str_replace(' ','-',strtolower($data['course1_location']))."-vs-".str_replace(' ','-',strtolower($data['course2_name']))."-".str_replace(' ','-',strtolower($data['course2_location']))."-".$data['course1_id']."-".$data['course2_id'];
				$seoData['breadcrumb'] = $data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
			}
			return $seoData;			
		}
		
		private function _checkMBATemplateEligibility($courseCategory, $course) {
			$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
			$flag = $this->national_course_lib->checkForMBATemplateEligibility($courseCategory, $course);
			return $flag;
		}

		function getCollegeReviewsForCourse($courseIdString, $fromPage){
			if($this->input->is_ajax_request()){
				isset($_POST['courseId']) ? $courseIdString = $this->input->post('courseId',true) : $courseIdString = '';
				isset($_POST['instituteId']) ? $instituteId = $this->input->post('instituteId',true) : $instituteId = '';
				isset($_POST['start']) ? $start = $this->input->post('start',true) : $start = 0;
				isset($_POST['count']) ? $count = $this->input->post('count',true) : $count = 5;
				isset($_POST['subCatId']) ? $subCatIdString = $this->input->post('subCatId',true) : $subCatIdString = '';
				isset($_POST['fromPage']) ? $fromPage = $this->input->post('fromPage',true) : $fromPage = '';
				isset($_POST['instituteName']) ? $instituteNames = $this->input->post('instituteName',true) : $instituteNames = '';
				isset($_POST['courseName']) ? $courseNames = $this->input->post('courseName',true) : $courseNames = '';
				isset($_POST['isLoadMore']) ? $isLoadMore = $this->input->post('isLoadMore',true) : $isLoadMore = '';
				isset($_POST['locationName']) ? $locationName = $this->input->post('locationName',true) : $locationName = '';
				$instituteIdArr = json_decode($instituteId,true);
			}

			if($courseIdString == ''){
				echo 'CourseId could not be blank';return;
			}

			$isCreateView = 0;
			$displayData = array();

			// load the institute builder
		    $this->load->builder("nationalInstitute/InstituteBuilder");
		    $instituteBuilder = new InstituteBuilder();
		    $instituteRepo = $instituteBuilder->getInstituteRepository();

			$this->load->model('CollegeReviewForm/collegereviewmodel','reviewObj');
			$courseIdArray = explode(',', $courseIdString);

			foreach ($instituteIdArr as $key => $instituteId) {
				if(!empty($instituteId) && preg_match('/^\d+$/',$instituteId)){
					$tmpInstArr[$key] = $instituteId;
				}
			}
			$instituteIdArr = $tmpInstArr;
			unset($tmpInstArr);
	
			if(!empty($instituteIdArr)){
				$instObjs = $instituteRepo->findMultiple($instituteIdArr);
			}
			$result = $this->reviewObj->getAllReviewsByCourse($courseIdArray,1);
			foreach ($courseIdArray as $key => $courseId) {
				if(!preg_match('/^\d+$/',$courseId)){
					continue;
				}
				$displayData['collegeReviewData'][$courseId] = $result[$courseId];	
				if(is_object($instObjs[$instituteIdArr[$courseId]])){
					$displayData['collegeReviewData'][$courseId]['url'] = $instObjs[$instituteIdArr[$courseId]]->getURL().'/reviews?course='.$courseId;	
				}else{
					$displayData['collegeReviewData'][$courseId]['url'] = '';
				}
				if(!empty($displayData['collegeReviewData'][$courseId]['reviews'])){
					$isCreateView ++;
				}
			}

			if($isCreateView == 0){
				echo 'No Data';return;
			}

			$displayData['frompage'] = $fromPage;
			if($fromPage == 'mobile'){
				$this->load->view('collegeReviewsInner',$displayData);
			}else{
				$this->load->view('comparePage/collegeReviewsInner',$displayData);
			}
		}
		
		function getCampusRepsForAskNowCompare(){
			if(isset($_POST['courseId']))
			{
				$courseIdString = $this->input->post('courseId');
			}
			$courseId = explode(",", $courseIdString);
			$i = 0;
			foreach($courseId as $key=>$value){
			$this->load->model('CA/cadiscussionmodel');
	     	$this->cadiscussionmodel = new CADiscussionModel();
	     	$fullRepsData = $this->cadiscussionmodel->getCampusReps($value);
			$finalRepData[$value] = $fullRepsData;
			$i++;
		   }
			echo json_encode($finalRepData);
		}

		function getAlsoViewed(){
			$courseIdArr = $this->input->post('courseStr');
			if(!empty($courseIdArr)){
				$this->comparePageLib = $this->load->library('comparePage/comparePageLib');
				$alsoViewedData = $this->comparePageLib->getAlsoViewedCourses(explode(',',$courseIdArr));
		        $displayData['showRecommendation'] = $alsoViewedData['showRecommendation'];
		        $displayData['instituteObjects']   = $alsoViewedData['instituteObjects'];
		        $displayData['courseInfo']   	   = $alsoViewedData['courseInfo'];
		        $html = $this->load->view('alsoViewed',$displayData,true);
		        echo json_encode(array('htm'=>$html));
			}else{
				echo json_encode(array('htm'=>""));
			}
		}

		function ampRedirect($courses){
			$fromAmp = ($_GET['fromamp'] == 1) ? 1 : 0;
			if($fromAmp && count($courses)>0){
				$url = SHIKSHA_HOME.'/resources/college-comparison-'.implode('-',$courses);
				redirect($url);exit();
			}
		}

	} 

?>

