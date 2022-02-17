<?php 
/**
* 
*/
class comparePage extends MX_Controller
{
	private $comparePageLib = '';
	private $maxCompares = '';
	private $currentCourseCount = 0;
	private $isStaticPage = '';

	public function __construct(){
		$this->maxCompares = 4;
		$this->isStaticPage = false;
	}
	private function init(&$displayData){
		$displayData['validateuser'] = $this->checkUserValidation();
		$this->load->library(array('comparePageLib'));
	}

	public function redirectToNewCompareUrl($urlStr){
		$newUrl = SHIKSHA_HOME.'/resources/college-comparison'.$urlStr;
		redirect($newUrl, 'location', 301);
	}

	public function mainComparePage($courseIds = ''){
		$displayData = array();
		$this->init($displayData);
		$displayData['maxCompares'] = $this->maxCompares;
		$displayData['compareData'] = array();
		$displayData['currentCourseCount'] = $this->currentCourseCount = 0;
		$this->comparePageLib = new comparePageLib();
		$this->listingcommonlib = $this->load->library('listingCommon/ListingCommonLib');
		$showFeesDiscArr = array(28499,36321); // To show fee disclaimer for Lovely Professional Univ Courses
		$displayData['userComparedData'] = $this->comparePageLib->getComparedData(); // this is used to get tracking key for coruse
        $courseIdArr = array();
        $courseIds = $this->security->xss_clean($courseIds);

        // Fixed VA Issue SHIK-783
        $userCompareUrl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$actualUrl  = SHIKSHA_HOME.'/resources/college-comparison'.$courseIds;
		if($actualUrl != $userCompareUrl && $actualUrl."?profiling=1" != $userCompareUrl){
			$this->redirectToNewCompareUrl($courseIds);
		}

		if($courseIds != ''){
			$processedInput = $this->comparePageLib->processInput($courseIds, $this->maxCompares);
			$courseIdArr = $processedInput['courseIdArr'];
			$this->isStaticPage = $displayData['isStaticPage'] = $processedInput['isStaticPage'];
		}

		if(($courseIds != '') && empty($courseIdArr)){ //Redirect to compare base url to prevent unwanted terms in url(VA Changes) 
			$this->redirectToNewCompareUrl();
		}
		//Load all resources inside this if
		if(!empty($courseIdArr)){
			$displayData['courseIdArr'] = $courseIdArr;
			$this->benchmark->mark('object_loading_start');
			$this->comparePageLib->getCourseObjectsData($displayData);
			$this->currentCourseCount = $displayData['currentCourseCount'];
			//If all courses are invalid
			if($this->currentCourseCount == 0){
				redirect(SHIKSHA_HOME.'/resources/college-comparison', 'location', 301);
				die;
			}
			$this->benchmark->mark('start2');
			$this->comparePageLib->getInstituteObjectsData($displayData);
			$this->benchmark->mark('start3');
			$this->comparePageLib->processAcademicUnitCookie($displayData);
			$this->benchmark->mark('object_loading_end');
			$this->benchmark->mark('start4');
			//$this->comparePageLib->getDropdownCourseList($displayData);
			$this->benchmark->mark('libcall_start');
			$this->comparePageLib->getComparisionData($displayData);
			$this->benchmark->mark('libcall_end');
			$this->benchmark->mark('start6');
			foreach ($courseIdArr as $crsId) {
				$displayData['showFeeDisc'][$crsId] = in_array($displayData['instIdArr'][$crsId], $showFeesDiscArr) ? 1 : 0;
			}
			if($this->isStaticPage){
				$displayData['seoDetails'] = $this->getStaticURLDetailsFromDB($displayData['courseIdArr']);
			}
			$this->benchmark->mark('start7');
			//$alsoViewedData = $this->comparePageLib->getAlsoViewedCourses($courseIdArr);
			$this->benchmark->mark('start8');
            //$displayData['showRecommendation'] = $alsoViewedData['showRecommendation'];
            //$displayData['instituteObjects']   = $alsoViewedData['instituteObjects'];
            //$displayData['courseInfo']   	   = $alsoViewedData['courseInfo'];
            $this->benchmark->mark('start9');
            $campusRepList = $this->comparePageLib->getCampusRepsForCompareTool($displayData);
            $this->benchmark->mark('start10');
    	    $displayData['campusRepList'] = $campusRepList;
    	    $flagForCampurRepExists = 0;
			for($i = 0; $i < $this->maxCompares ;$i++){
			  if(!empty($campusRepList[$i]['caInfo'])){
			    $flagForCampurRepExists++;
			  }
			}
			$displayData['flagForCampurRepExists'] = $flagForCampurRepExists;
			$this->benchmark->mark('start11');
			$displayData['checkForDownloadBrochure'] = $this->listingcommonlib->checkForDownloadBrochure($courseIdArr);
			$this->benchmark->mark('start12');
		}else{
			$this->comparePageLib->removeAcademicUnitCookie();
		}
		if($this->currentCourseCount > 0){
			$this->benchmark->mark('start13');
			$this->comparePageLib->getCourseAttributesData($displayData);
			$this->benchmark->mark('start14');
		}else{
			$displayData['courseAttributeData_fullTimeMba']['fullTimeMba'] = array('stream_id' => MANAGEMENT_STREAM, 'base_course_id' => MANAGEMENT_COURSE, 'education_type' => EDUCATION_TYPE);
			$displayData['courseAttributeData_beBtech']['beBtech'] = array('stream_id' => ENGINEERING_STREAM, 'base_course_id' => ENGINEERING_COURSE,  'education_type' => EDUCATION_TYPE);
		}
		$this->_MISTrackingVariables($displayData);
		
		$beaconTrackData = $this->comparePageLib->prepareBeaconTrackData('comparePage',$displayData['courseIdArr'],$displayData['courseObjs']);
		
		$instForGTM = $this->comparePageLib->prepareInstArrayForCompareGTM($beaconTrackData);
		$displayData['beaconTrackData'] =$beaconTrackData;
		$displayData['gtmParams'] = $this->comparePageLib->getGTMArray($beaconTrackData, $instForGTM);
		$displayData['suggestorPageName'] = "all_tags";

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_ComparePage','entity_id'=>'0','stream_id'=>$displayData['gtmParams']['stream'],'substream_id'=>$displayData['gtmParams']['substream'],'specialization_id'=>$displayData['gtmParams']['specialization'],'baseCourse'=>$displayData['gtmParams']['baseCourseId'],'cityId'=>$displayData['gtmParams']['cityId'],'stateId'=>$displayData['gtmParams']['stateId'],'educationType'=>$displayData['gtmParams']['educationType'],'deliveryMethod'=>$displayData['gtmParams']['deliveryMethod']);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		error_log(':::ComparePage:1::'.$this->benchmark->elapsed_time('start1', 'start2'));
		error_log(':::ComparePage:2::'.$this->benchmark->elapsed_time('start2', 'start3'));
		error_log(':::ComparePage:3::'.$this->benchmark->elapsed_time('start3', 'start4'));
		error_log(':::ComparePage:4::'.$this->benchmark->elapsed_time('start4', 'start5'));
		error_log(':::ComparePage:5::'.$this->benchmark->elapsed_time('start5', 'start6'));
		error_log(':::ComparePage:6::'.$this->benchmark->elapsed_time('start6', 'start7'));
		error_log(':::ComparePage:7::'.$this->benchmark->elapsed_time('start7', 'start8'));
		error_log(':::ComparePage:8::'.$this->benchmark->elapsed_time('start8', 'start9'));
		error_log(':::ComparePage:9::'.$this->benchmark->elapsed_time('start9', 'start10'));
		error_log(':::ComparePage:10::'.$this->benchmark->elapsed_time('start10', 'start11'));
		error_log(':::ComparePage:11::'.$this->benchmark->elapsed_time('start11', 'start12'));
		error_log(':::ComparePage:12::'.$this->benchmark->elapsed_time('start12', 'start13'));
		error_log(':::ComparePage:13::'.$this->benchmark->elapsed_time('start13', 'start14'));
		$this->load->view('mainComparePage', $displayData);
	}

	private function _MISTrackingVariables(&$displayData){
		$displayData['emailTrackingPageKeyId'] = 211;
		$displayData['qtrackingPageKeyId']     = 212;
		$displayData['compareHomePageKeyId']   = 612;
	}

	public function getPopularCoursesForComparision($baseEntityArray = array()){
		$this->init();
		if(is_array($baseEntityArray) && count($baseEntityArray)){
			$this->comparePageLib = new comparePageLib();
			$displayData = $this->comparePageLib->getPopularCoursesForComparision($baseEntityArray);
			if($displayData){
				echo $this->load->view('popularInstituteComparision',$displayData,true);
			}
		}
    }

    // need to remove this function after migration script.  UGC-4403
    public function migrationOfStaticComparePage(){
		$this->load->model('comparepagemodel');
		$this->comparePageModel = new comparepagemodel;
		$subcategoryIdsArray = $this->comparePageModel->getSubcategoryIdsForStaticComparePageData();
		$subcategoryIds = array();
		foreach ($subcategoryIdsArray as $key => $value) {
			$subcategoryIds[] = $value['subcategory_id'];
		}
		unset($subcategoryIdsArray);
		if(is_array($subcategoryIds) && count($subcategoryIds)){
			$subCategoryToNewMappingData =  $this->comparePageModel->getNewMappingForSubcategoryId($subcategoryIds);
			if(is_array($subCategoryToNewMappingData) && count($subCategoryToNewMappingData) > 0){
				$this->comparePageModel->migrateSubcategoryToNewMapping($subCategoryToNewMappingData);
			}
		}
	}

	function getStaticURLDetailsFromDB($courseIdArr){
			$courseId1 = isset($courseIdArr[0]) ? $courseIdArr[0] : 0;
			$courseId2 = isset($courseIdArr[1]) ? $courseIdArr[1] : 0;
			$courseId3 = isset($courseIdArr[2]) ? $courseIdArr[2] : 0;
			$courseId4 = isset($courseIdArr[3]) ? $courseIdArr[3] : 0;
			$this->load->model('comparepagemodel');
			$this->comparePageModel = new comparepagemodel;
			$seoData = array();
			if(!is_numeric($courseId1) && !is_numeric($courseId2)){	//Case of 2 Courses
				$courseId1 = $courseId3;
				$courseId2 = $courseId4;
				$courseId3 = 0;
				$courseId4 = 0;
			}
			else if(!is_numeric($courseId1)){	//Case of 3 COurses
                                $courseId1 = $courseId2;
                                $courseId2 = $courseId3;
                                $courseId3 = $courseId4;
                                $courseId4 = 0;
			}
			$seoData=$this->comparePageModel->getStaticURLDetailsFromDB($courseId1,$courseId2,$courseId3,$courseId4);

			if(count($seoData)>=1){
				$data = $seoData[0];
				if($data['course1_location']!=''){					
					$course1Location = " ".$data['course1_location'];
				}
				if($data['course2_location']!=''){					
					$course2Location = " ".$data['course2_location'];
				}

                                if(isset($data['course3_location']) && $data['course3_location']!=''){
                                        $course3Location = " ".$data['course3_location'];
                                }
                                if(isset($data['course4_location']) && $data['course4_location']!=''){
                                        $course4Location = " ".$data['course4_location'];
                                }

				if($data['course4_id']==0 && $data['course3_id']==0){
					$seoData['title'] = "Compare ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
					$seoData['description'] = "Compare ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." on courses, fees, placements, faculty, infrastructure, alumni and other details";
					$seoData['heading'] = "College Comparison: ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
					$seoData['canonical'] = SHIKSHA_HOME."/resources/college-comparison-".str_replace(' ','-',strtolower($data['course1_name']))."-".str_replace(' ','-',strtolower($data['course1_location']))."-vs-".str_replace(' ','-',strtolower($data['course2_name']))."-".str_replace(' ','-',strtolower($data['course2_location']))."-".$data['course1_id']."-".$data['course2_id'];
					$seoData['breadcrumb'] = $data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
				}
				else if($data['course4_id']==0){
        	                        $seoData['title'] = "Compare - ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location;
                	                $seoData['description'] = "Compare ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." on courses, fees, placements, faculty, infrastructure, alumni and other details";
                        	        $seoData['heading'] = "College Comparison: ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location;
                                	$seoData['canonical'] = SHIKSHA_HOME."/resources/comparison-of-".str_replace(' ','-',strtolower($data['course1_name']))."-".str_replace(' ','-',strtolower($data['course1_location']))."-vs-".str_replace(' ','-',strtolower($data['course2_name']))."-".str_replace(' ','-',strtolower($data['course2_location']))."-vs-".str_replace(' ','-',strtolower($data['course3_name']))."-".str_replace(' ','-',strtolower($data['course3_location']))."-".$data['course1_id']."-".$data['course2_id']."-".$data['course3_id'];
                                	$seoData['breadcrumb'] = $data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location;
				}
				else{
                                        $seoData['title'] = "Compare - ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." Vs ".$data['course4_name'].$course4Location;
                                        $seoData['description'] = "Compare ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." Vs ".$data['course4_name'].$course4Location." on courses, fees, placements, faculty, infrastructure, alumni and other details";
                                        $seoData['heading'] = "College Comparison: ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." Vs ".$data['course4_name'].$course4Location;
                                        $seoData['canonical'] = SHIKSHA_HOME."/resources/comparison-of-".str_replace(' ','-',strtolower($data['course1_name']))."-".str_replace(' ','-',strtolower($data['course1_location']))."-vs-".str_replace(' ','-',strtolower($data['course2_name']))."-".str_replace(' ','-',strtolower($data['course2_location']))."-vs-".str_replace(' ','-',strtolower($data['course3_name']))."-".str_replace(' ','-',strtolower($data['course3_location']))."-vs-".str_replace(' ','-',strtolower($data['course4_name']))."-".str_replace(' ','-',strtolower($data['course4_location']))."-".$data['course1_id']."-".$data['course2_id']."-".$data['course3_id']."-".$data['course4_id'];
                                        $seoData['breadcrumb'] = $data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." Vs ".$data['course4_name'].$course4Location;

				}
			}
			return $seoData;			
	}

	/**
	* Purpose       : prepare course list from institute search
	* Author        : Akhter
	**/
	function getInstituteCoursesList()
	{
			$instituteId = $this->input->post('instituteId');
			$listingType = $this->input->post('listingType');
			$firstSelectedCourseId  = $this->input->post('firstCourseId');
			$secondSelectedCourseId = $this->input->post('secondCourseId');
			$thirdSelectedCourseId  = $this->input->post('thirdCourseId');

			//Check if any of the Institute Course lies in same level as First course. If yes, simply return this course and then we can redirect to the new page
			if($firstSelectedCourseId !='' && $firstSelectedCourseId>0){
				$comparePageLib = $this->load->library('comparePageLib');
				$courseOnLevel  = $comparePageLib->getCourseListOnLevel($firstSelectedCourseId, $instituteId, $listingType);
			}

			foreach ($courseOnLevel as $courseVal){
				if($courseVal['course_id'] != $firstSelectedCourseId && $courseVal['course_id'] != $secondSelectedCourseId && $courseVal['course_id'] != $thirdSelectedCourseId){
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

        	// load the institute builder
		    $this->load->builder("nationalInstitute/InstituteBuilder");
		    $instituteBuilder = new InstituteBuilder();
		    $instituteRepo = $instituteBuilder->getInstituteRepository();

			$institute = $instituteRepo->findWithCourses(array($instituteId=>$courseList)); 
			
			if((is_array($institute) && count($institute)<0) || empty($institute)){
				return '';
			}
			
			$displayData['institute'] = $institute[$instituteId];
			$this->load->view('autoSuggestorInstituteView',$displayData);
			$this->load->view('autoSuggestorCourseListView',$displayData);
	}
	
	// this function is used to prepare compare bottom sticky for all pages
	function prepareCompare(){
		$cmplibObj      = $this->load->library('comparePageLib');
		$courseIdBucket = $cmplibObj->getComparedData();
		$courseIdArr    = array_keys($courseIdBucket);
		if(count($courseIdArr)>0){	
			// load the course builder
		    $this->load->builder("nationalCourse/CourseBuilder");
			$builder = new CourseBuilder();
			$courseRepo = $builder->getCourseRepository();
			$courseObj = $courseRepo->findMultiple($courseIdArr); 
			$displayData['courseObj'] = $courseObj;
		}
		$displayData['courseBucket'] = $courseIdBucket;
		$html = $this->load->view('comparePage/innerHtmlSticky',$displayData,true);
		echo json_encode(array('html'=>$html));
	}

	public function generateCollegeCompareTool(){
		$this->load->view('comparePage/collegeCompareSticky');
    }

    function trackComparePage($randNum,$pageType='dynamic',$source='desktop',$courseString=''){
			$userInfo = $this->checkUserValidation();
			if($userInfo == 'false') {
			    $userId = 0;
			}
			else {
			    $userId = $userInfo[0]['userid'];
			}	    

			$courseArr = explode('|', $courseString);

			for($i = 0; $i<count($courseArr); $i++){
				if($courseArr[$i] != ''){ 
		            $strArr      = explode('::',$courseArr[$i]); //course::tracking_key::instituteId
		            if(is_numeric($strArr[0])){
		            	$courseBucket[$strArr[0]] = $strArr[1]; 	
		            }
		        }
			}
			
			$this->comparePageModel = $this->load->model('comparepagemodel');
			$this->comparePageModel->trackComparePage($pageType,$source,$courseBucket,$userId);
		}
	
	//After user has loggedin,update userId for made response
	function getLoggedInUserForMadeResponse()
	{
		$userId = $this->input->post('userId');
		if($userId)
		{
			$this->comparePageModel = $this->load->model('comparePage/comparepagemodel');
			$this->comparePageModel->updateUserIdForMadeResponse($userId);
		}
	}

	public function getCollegeRecommendations(){
		$this->benchmark->mark('total1');
		if($this->input->is_ajax_request()){
			$courses = $this->input->post('courses');
			if(count($courses) > 0){
				$this->load->library(array('comparePageLib'));
				$comparePageLib = new comparePageLib();
				$this->benchmark->mark('also1');
				$alsoViewedData = $comparePageLib->getAlsoViewedCourses($courses);
				$this->benchmark->mark('also2');
		        $displayData['instituteObjects']   = $alsoViewedData['instituteObjects'];
		        $displayData['courseInfo']   	   = $alsoViewedData['courseInfo'];
		        $displayData['keyVal']   	       = count($courses) + 1;
		        $html = $this->load->view('receommendations', $displayData, true);
		        echo json_encode(array('html'=>$html, 'showRecommendation'=>($alsoViewedData['showRecommendation']?1:0)));
			}else{
				echo '';
			}
		}
		$this->benchmark->mark('total2');
		error_log(':::AlsoViewed:1::'.$this->benchmark->elapsed_time('also1', 'also2'));
		error_log(':::AlsoViewed:2::'.$this->benchmark->elapsed_time('total1', 'total2'));
	}

	public function getDropdownCourseListOfInstitutes($courseIdArr){
		$this->load->library(array('comparePageLib'));
		$this->comparePageLib = new comparePageLib();
		$displayData = array();
		//$displayData['courseIdArr'] = array(269960, 119554);
		//$displayData['courseIdArr'] = $this->input->post('courses',true);
		$courseIdArr = $this->input->post('courses',true);
		foreach ($courseIdArr as $key => $courseid) {
			if(!empty($courseid) && preg_match('/^\d+$/',$courseid)){
				$tempCourseArr[] = $courseid;
			}
		}
		$displayData['courseIdArr'] = $tempCourseArr;
		unset($tempCourseArr);

		if(empty($displayData['courseIdArr'])){
			echo '';die;
		}
		if(!is_array($displayData['courseIdArr']) && count($displayData['courseIdArr']) < 1){
			echo '';die;
		}

		$this->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
		$repo = $builder->getCourseRepository();
		$courseObjs = $repo->findMultiple($displayData['courseIdArr']);
		$courseObjs_setOrder = array();
		foreach ($displayData['courseIdArr'] as $courseId) {
			$tempCourseId = 0;
			if(is_object($courseObjs[$courseId])){
				$tempCourseId = $courseObjs[$courseId]->getId();
				$displayData['courseNameArr'][$courseId] = $courseObjs[$courseId]->getName();
			}
			if(empty($tempCourseId)){
				echo '';die;
			}
			$courseObjs_setOrder[$courseId] = $courseObjs[$courseId];
		}
		$courseObjs = $courseObjs_setOrder;
		unset($courseObjs_setOrder);
		$displayData['courseObjs'] = $courseObjs;

		foreach ($courseObjs as $crsId => $crsObj) {
			$displayData['instIdArr'][$crsId]   = $crsObj->getInstituteId();
			$displayData['instNameArr'][$crsId] = $crsObj->getInstituteName();
			$displayData['instTypeArr'][$displayData['instIdArr'][$crsId]] = $crsObj->getInstituteType();
		}

		$this->comparePageLib->processAcademicUnitCookie($displayData);
		$this->comparePageLib->getDropdownCourseList($displayData);
		$response = array();
		$j = 1;
		foreach ($displayData['courseIdArr'] as $courseId) {
			$displayData['courseId'] = $this->security->xss_clean($courseId);
			$displayData['j'] = $j;
			$response[$j++] = $this->load->view('sections/compareCourseDropDownAjax', $displayData, true);
		}
		echo json_encode($response);
	}
}
