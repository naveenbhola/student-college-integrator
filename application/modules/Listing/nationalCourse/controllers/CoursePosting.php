<?php

class CoursePosting extends MX_Controller{
	function __construct(){
		parent::__construct();
		// global $checkNewMessageFlagForUser, $checkNewMessageCountForUsers;		
		// $checkNewMessageFlagForUser = false;
		$validity = $this->checkUserValidation();
		if(($validity == "false" )||($validity == "")) {
		    header('location:/enterprise/Enterprise/loginEnterprise');
		    exit();
		}
		else{
			$usergroup = $validity[0]['usergroup'];
			if($usergroup !="cms" && $usergroup !="listingAdmin")
			{
			    header("location:/enterprise/Enterprise/unauthorizedEnt");
			    exit();
			}
			$this->userId = $validity[0]['userid'];
			$this->userGroup = $usergroup;
		}

		$this->coursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');
	}

	private function _initUploadClient() {
		if(!$this->UploadClient) {
			$this->load->library('upload_client');
			$this->UploadClient = new Upload_client();
		}
	}

	public function index(){
		$this->load->view('enterprise/adminBase/adminLayout');
	}

	public function init(){
		$this->coursePostingLib = $this->load->library('nationalCourse/CoursePostingLib');
		$this->institutedetailsmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
		$this->coursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');
	}

	public function getUserData(){
		$validity = $this->checkUserValidation();
		if($validity){
			list($email) = explode('|',$validity[0]['cookiestr']);
			$returnData = array('userId'=>$validity[0]['userid'],'userGroup'=>$validity[0]['usergroup'],'name'=>$validity[0]['displayname'],'isLoggedIn' => true,'email' => $email);
			echo json_encode(array('data'=>$returnData));
		}
		else{
			echo json_encode(array('data'=>array('isLoggedIn' => false)));
		}
	}

	public function getStaticAttribute(){
		$this->init();
		$staticAttribute                              = array();
		$dynamicAttributeList = $this->coursePostingLib->prepareDynamicAttribute();
		if(is_array($dynamicAttributeList)) {
			$staticAttribute['static_data'] = $dynamicAttributeList;		
		}

		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder();
		$locationRepository = $locationBuilder->getLocationRepository();		

		$locationData = array();
		$nationalState = $locationRepository->getStatesByCountry(2);
		foreach ($nationalState as $key => $stateObj) {
			$locationData[] = array('value'=>$stateObj->getId(),
										  'label'=>$stateObj->getName());
		}
		$staticAttribute['static_data']['locationData'] = $locationData;
		$staticAttribute['static_data']['media_server_domain'] = MEDIA_SERVER;
	
		$data                                         = array('data' => $staticAttribute);
		
		echo json_encode($data);die;		
	}

	public function saveCourse($request_body = ""){
		$this->init();
		if($request_body == ""){
		    $request_body = file_get_contents('php://input');
		}
		$data = json_decode($request_body,true);
		$data = $data['courseObj'];

		$data['userId'] = $this->userId;
		$courseId = $this->coursePostingLib->formatCourseData($data);
		if($data['saveAs'] == 'live') {
			$this->coursecache->removeCoursesCache(array($courseId));

			// purge html cache
			$shikshamodel = $this->load->model("common/shikshamodel");
			$arr = array("cache_type" => "htmlpage", "entity_type" => "course", "entity_id" => $courseId, "cache_key_identifier" => "");
			$shikshamodel->insertCachePurgingQueue($arr);
		}
		if(empty($courseId)){
			$returnData = array('message' => 'Invalid Course Id. Please Check','status' => 'fail');
		}
		else{
			$returnData = array('course_id'=>$courseId,'message'=>'Added successfully with course Id: '.$courseId,'status'=>'success');
			$this->coursecache->removeLockOnCourseForEdit($courseId);
			$this->invalidateAndRebuildListingsCache(array($courseId));
		}
		
		

		echo json_encode(array('data'=>$returnData));
	}


	public function getCourseData(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$courseId = $data['courseId'];

		$open = $this->coursecache->canOpenCourseInEdit($courseId,$this->userId);
		if($open['open']){
			$courseData = $this->coursePostingLib->getCourseData($courseId);
			$this->coursecache->lockCourseForEdit($courseId,$this->userId);
			echo json_encode(array('data'=>array('course_data'=>$courseData,'status'=>'success')));		
		}
		else{
			echo json_encode(array('data'=>array('status'=>'fail','message'=>'Another person(userId: '.$open['userId'].') is currently editing the course.Please open after some time')));
		}
	}

	public function cloneCourse(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);

		$courseId = $data['courseId'];
		$sections = $data['sections'];

		$combinedSections = array('courseExamCutOff'=>array('course12thCutOff'));
		foreach ($combinedSections as $key => $value) {
			if(in_array($key,$sections)){
				foreach ($value as $section) {
					$sections[] = $section;
				}
			}
		}
		// _p($sections);die;
		$courseData = $this->coursePostingLib->getCourseData($courseId);
		// _p($courseData);die;
		foreach ($courseData as $section => $sectionData) {
			if(!in_array($section,$sections)){
				switch($section){
					case 'courseStructureForm':
						$courseData[$section] = array();
						break;
					case 'extraData':
					// case 'isDisabled':
					case 'course_type':
						break;
					case 'clientId':
						$courseData[$section] = $this->userId;
						break;
					default:
						unset($courseData[$section]);
				}
			}
			else{
				switch($section){
					case 'courseTypeForm':
						foreach ($courseData[$section]['mapping_info'] as $index => $value) {
							unset($courseData[$section]['mapping_info'][$index]['hierarchyMapping']);
							unset($courseData[$section]['mapping_info'][$index]['courseMapping']);
						}
						break;
					case 'courseBasicInfoForm':
						unset($courseData[$section]['course_name']);
						break;
				}
			}
		}

		$dependentSections = array('courseEligibilityForm'=>array('courseSeats','courseExamCutOff'));
		foreach ($dependentSections as $key => $value) {
			if(!in_array($key,$sections)){
				foreach ($value as $section) {
					if(in_array($section,$sections)){
						switch($section){
							case 'courseSeats':
								if(!empty($courseData[$section]['seats_by_entrance_exam'])){
									unset($courseData[$section]['seats_by_entrance_exam']);
								}
								break;
							case 'courseExamCutOff':
								if(!empty($courseData[$section])){
									unset($courseData[$section]);
								}
								break;
						}
					}
				}
				$sections = array_diff($sections, $value);
			}
		}
		//set disabled of course if parent is disabled
		$parentData = $this->coursePostingLib->getBasicCourseAndParentData($courseId);
		$parentData = $parentData['parent_data'];
		if(!empty($parentData['disabled_url'])){
			$courseData['isDisabled'] = $parentData['disabled_url'];
		}
		echo json_encode(array('data'=>$courseData));
	}

	public function getBasecoursesByMultipleBaseEntities(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$hierarchyArr = $data['hierarchyArr'];
		$courseData = $this->coursePostingLib->getBasecoursesByMultipleBaseEntities($hierarchyArr);
		echo json_encode(array('data'=>$courseData));
	}

	public function getBasecoursesByHirarchyWithFilter(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$hierarchyArr = $data['hierarchyArr'];
		$credential = $data['credential'];
		$courseLevel = $data['course_level'];
		$courseData = $this->coursePostingLib->getBasecoursesByHirarchyWithFilter($hierarchyArr, $courseLevel, $credential);
		echo json_encode(array('data'=>$courseData));
	}

	public function getSpecializationsByBaseCourses(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$baseCourseArr = $data['baseCourseArr'];
		$courseData = $this->coursePostingLib->getSpecializationsByBaseCourses($baseCourseArr);
		echo json_encode(array('data'=>$courseData));
	}

	function getInstituteParentHierarchy() {
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		$institutePostingType = !empty($_POST['institute_posting_type'])?$this->input->post('institute_posting_type'):'';
		$postingListingType = !empty($_POST['postingListingType'])?strtolower($this->input->post('postingListingType')):'institute';
		$this->init();
		$hierarchyData = $this->institutePostingLib->getInstituteParentHierarchy($id, $type, $institutePostingType);
		

		echo json_encode($hierarchyData);
	}

	function uploadCourseDocument() {
		$response['data'] = array('error' => array('msg' => 'Unable to upload file due to incorrect data'));
		
		if($_FILES['uploads']) {
			$response = array();
			$response = $this->_prepareAndUploadCourseDocument($_FILES['uploads']);
			// if(!is_array($response)) {
				$response = array('data' => $response);
			// }
		}
		echo json_encode($response);
	}

	private function _prepareAndUploadCourseDocument($uploadArrData) {
		// check if institute brochure has been uploaded
		if(!empty($uploadArrData['tmp_name'][0])) {
			$return_response_array = array();
			$this->_initUploadClient();
			// get file data and type check
			$type_doc = $uploadArrData['type']['0'];
			$type_doc = explode("/", $type_doc);
			$type_doc = $type_doc['0'];
			$type = explode(".",$uploadArrData['name'][0]);
			$type = strtolower($type[count($type)-1]);
			// display error if type doesn't match with the required file types
			if(!in_array($type, array('pdf','jpeg','doc','jpg'))) {
				$return_response_array['error']['msg'] = "Only document of type .pdf,.doc and .jpeg allowed";
				return $return_response_array;
			}
			// all well, upload now
			if($type_doc == 'image') {
				$upload_array = $this->UploadClient->uploadFile($appId,'image',array('i_brochure_panel' => $uploadArrData),array(),"-1","institute",'i_brochure_panel');
			}
			else {
				$upload_array = $this->UploadClient->uploadFile($appId,'pdf',array('i_brochure_panel' => $uploadArrData),array(),"-1","institute",'i_brochure_panel');
			}
			// var_dump($upload_array);die; 
			// check the response from upload library
			if(is_array($upload_array) && $upload_array['status'] == 1) {
				$return_response_array = $upload_array[0]['imageurl'];
			}
			else {
				// _p($upload_array);
				if($upload_array == 'Size limit of 25 Mb exceeded') {
					$upload_array = "Please upload a brochure less than 25 MB in size";	
				}
				$return_response_array['error']['msg'] = $upload_array;
			}
			return $return_response_array;
		}
		else {
			return "";
		}
	}

	function testCourse($courseId) {
		if(empty($courseId) || (int) $courseId != $courseId) {
			$courseId = '250162';
		}
		$courseId = explode(',',$courseId);
		$this->load->builder("nationalCourse/CourseBuilder");
    	$builder = new CourseBuilder();
    	$repo = $builder->getCourseRepository();
    	if($_REQUEST['disableCaching']) {
    		$repo->disableCaching();
    	}
    	// $courses = $repo->find($courseId, 'full');
    	$this->benchmark->mark('find_start');
    	$courses = $repo->findMultiple($courseId, 'full');
    	$this->benchmark->mark('find_end');
    	echo 'execution time '.$this->benchmark->elapsed_time('find_start', 'find_end');
    	// _p(serialize($courses));
    	_p($courses);
    	die('exit');
	}

	function testInstitute($instituteId) {
		if(empty($instituteId) || (int) $instituteId != $instituteId) {
			$instituteId = '250162';
		}
		$instituteId = explode(',',$instituteId);
		$this->load->builder("nationalInstitute/InstituteBuilder");
    	$builder = new InstituteBuilder();
    	$repo = $builder->getInstituteRepository();
    	if($_REQUEST['disableCaching']) {
    		$repo->disableCaching();
    	}
    	
    	$this->benchmark->mark('find_start');
    	$institutes = $repo->findMultiple($instituteId, 'full');
    	$this->benchmark->mark('find_end');
    	echo 'execution time '.$this->benchmark->elapsed_time('find_start', 'find_end');
    	// var_dump($courses->isPaid());
    	_p($institutes);
    	// die('exit');
	}

	public function getCourseLocations(){
		
		$instituteId = $this->input->post('instituteId');
		$this->load->builder("nationalInstitute/InstituteBuilder");
		$builder   = new InstituteBuilder();
		$repo      = $builder->getInstituteRepository();
		$repo->disableCaching();
		$data      = $repo->getInstituteLocations(array($instituteId));
		$locationData = array();
		
		foreach ($data[$instituteId] as $key => $location) {
			if($location->getLocalityName()){
				$locationData[$key]['city_name'] = $location->getCityName()." - ".$location->getLocalityName();	
			}else{
				$locationData[$key]['city_name'] = $location->getCityName();	
			}
			
			$locationData[$key]['city_id'] = $location->getCityId();
			$locationData[$key]['is_main'] = $location->isMainLocation();
		}

		echo json_encode($locationData);
		exit;
	}

	function getListOfCoursesBasedOnFilters()
	{
		$this->init();
		$request_body  = file_get_contents('php://input');
		$data          = json_decode($request_body,true);

		$instituteId   = !empty($data['instituteId'])?$data['instituteId']:'';
		$courseIds     = !empty($data['courseId'])?array($data['courseId']):'';
		$universityId  = !empty($data['universityId'])?$data['universityId']:'';
		$status        = !empty($data['status'])?$data['status']:'';
		$pageNumber    = !empty($data['pageNumber'])?$data['pageNumber']:1;
		$startOffset   = ($pageNumber - 1) * 25;

		$openSearch  = !empty($data['openSearch'])? $data['openSearch'] : '';
		
		if(empty($openSearch))
		{
			//check for autosuggestor
			$instituteId  = empty($instituteId) ? (!empty($data['institute'])?$data['institute']:'') : $instituteId;
			$universityId = empty($universityId) ? (!empty($data['university'])?$data['university']:'') : $universityId;
		}else{
			//get all listing id based on open search
			$searchListing = $this->coursepostingmodel->getListingIdsBasedOnText($openSearch);

			if(!empty($searchListing)){
				$courseIdsByInstituteHierarchy = array();
				foreach ($searchListing as $listingId => $value) {
					$courseIdsByInstituteHierarchy[] = $this->getAllCourseIdbyInstituteHierarchy($listingId,$value['type']);
				}
				foreach ($courseIdsByInstituteHierarchy as $key => $value) {
					foreach ($value as $key => $val) {
						$courseIds[] = $val;
					}				
				}							
			}else{
					echo json_encode(array('totalCount'=> 0, 'data' => array()));
					exit;				
			}
		}
		
		if($instituteId || $universityId){
			$InstituteModel = $this->load->model("nationalInstitute/institutepostingmodel");
			if(!empty($instituteId)){
				$listingData = $InstituteModel->getListingType($instituteId);
				if($listingData[$instituteId] == 'institute') {
					$courseIds = $this->getAllCourseIdbyInstituteHierarchy($instituteId,'institute');
				}
			}
			else if(!empty($universityId)){
				$listingData = $InstituteModel->getListingType($universityId);
				if($listingData[$universityId] == 'university') {
					$courseIds = $this->getAllCourseIdbyInstituteHierarchy($universityId,'university');
				}
			}
			if(empty($courseIds)){
					echo json_encode(array('totalCount'=> 0 , 'data' => array()));
					exit;						
			}
		}

		$result                  = $this->coursepostingmodel->getSearchResultsForTable($courseIds,$status,$startOffset);

		$result['paginationNum'] = $pageNumber;
		$result['userGroup'] = $this->userGroup;
		$data                    =  array('data' => $result);
		// _p($data); die;
		
		echo json_encode($data);die;
	}

	public function getAllCourseIdbyInstituteHierarchy($listingId,$type){
		$data = array();
		$this->load->library("nationalInstitute/InstituteDetailLib");
		$lib = new InstituteDetailLib();
    	$instituteData = $lib->getDescendantInstitutes($listingId, $type, array(),false);
    	$courseIds = $this->coursepostingmodel->getAllCoursesOfInstitutes($instituteData['institutes'],array('live','draft'));
		return $courseIds;
	}

	function validateCourseForDeletion(){

		$this->init();
        if((empty($this->userId)) || ($this->userGroup !='listingAdmin')) {
        	echo "failure";
            return;
        }

		$request_body = file_get_contents('php://input');
		$data         = json_decode($request_body,true);

		$deleted_course_migrate_id = $data['courseId'];

        if(empty($deleted_course_migrate_id)) {
            $deleted_course_migrate_id = -1;
        }


        $result_array = $this->coursepostingmodel->checkListingStatus($deleted_course_migrate_id);
        
        if(count($result_array) > 0) {
            echo json_encode(array('data'=>array('courseName'=>$result_array['courseName'],'message'=>'Entered course is valid.','status'=>'success')));
        } else {
            echo json_encode(array('data'=>array('message'=>'Entered course not found. Please enter a valid course ID','status'=>'fail')));
        }

    }

    function deleteCourse()
    {
    	$this->init();
       
		// $user_id      = $validateuser[0]['userid'];
		$request_body                 = file_get_contents('php://input');
		$data                         = json_decode($request_body,true);
		
		$courseId                     = $data['courseId'];
		$newCourseId                  = $data['migrationId'];
		$migrateCriteria              = array();
		$migrateCriteria 			  = $data;
		// $migrateCriteria['reviews']   = $data['reviews'];
		// $migrateCriteria['crs']       = $data['crs'];
		// $migrateCriteria['questions'] = $data['questions'];
		// _p($migrateCriteria); die;
		if($courseId){
			$open = $this->coursecache->canOpenCourseInEdit($courseId,$this->userId);
			if($open['open']){

				//check if course has online form mapped to it
		        $this->load->library('Online_form_client');
			    $onlineClient = new Online_form_client();
		        $listingHasOnlineForm = $onlineClient->checkIfListingHasOnlineForm('course',$courseId);
		        if($listingHasOnlineForm) {
		            echo json_encode(array('data'=>array('message'=>'you cannot delete this listing as onlineform is available.','status'=>'fail')));
		            return;
		        }

				
				$this->load->builder("nationalCourse/CourseBuilder");
				$builder = new CourseBuilder();
	    		$repo = $builder->getCourseRepository();
	    		$newInstituteId = null;
	    		$courseObj = $repo->find($courseId);
	    		$instituteId = $courseObj->getInstituteId();
		    	if($newCourseId){
		    		$courseObj1 = $repo->find($newCourseId);		    		
		    		$newInstituteId = $courseObj1->getInstituteId();
		    	}
		    	$noticationMailerData = array();
		    	if($courseObj->isPaid()){
		    		$noticationMailerData['listing_id'] = $courseId;
		    		$noticationMailerData['listing_name'] = $courseObj->getName(); 
		    		$noticationMailerData['listing_type'] = 'course'; 
		    		$noticationMailerData ['action_type'] = 'delete';
		    		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		    		$noticationMailerData ['user_id'] = $cmsUserInfo['userid'];
		    		$noticationMailerData ['user_name'] = $cmsUserInfo['validity'][0]['displayname']; 
		    	}
		    	$migrateCriteria['updateLastModified'] = true;
		    	$migrateCriteria['userId'] = $this->userId;
				$courseOrderUpdateCourseId = $this->coursepostingmodel->getCourseInfoForCourseOrder($courseId); 
				
				$deleteStatus              = $this->coursepostingmodel->deleteCourse($courseId, $instituteId, $newCourseId, $courseOrderUpdateCourseId, $newInstituteId, $migrateCriteria,$noticationMailerData);

				//deleting course amp cache from Google CDN
				$this->deleteCoursePageOnGoogleCDNcacheAMP(array($courseId));

				writeToLog('Step 1. inside course delete flow for course id : '. $courseId, COURSE_DELETION_LOG_FILE);
				$this->courseNotificationMailerData('delete',array('courseId'=>$courseId,'userId' => $this->userId));

				$this->invalidateAndRebuildListingsCache(array($courseId));				
	        	echo json_encode(array('data'=>array('message'=>'Course deleted successfully with details as '. implode('\n,', $deleteStatus['msg']),'status'=>$deleteStatus['status'])));
				
					
				$this->coursecache->removeCoursesCache(array($courseId));
			}
			else{
				echo json_encode(array('data'=>array('status'=>'fail','message'=>'Another person(userId: '.$open['userId'].') is currently editing the course.Please open after some time')));
			}
			
    	}
    	else {
    		echo json_encode(array('data'=>array('message'=>'Empty Course Id.','status'=>'fail')));
    	}
    }

    public function invalidateAndRebuildListingsCache($courseIds){
        if(empty($courseIds)){
            return;
        }

        $instituteIds = array();
        $coursedetailmodel = $this->load->model("nationalCourse/coursedetailmodel"); 
        $hierarchyInstitutes = $coursedetailmodel->getCourseInstituteHeirarchy($courseIds);
       
        $affiliatedUniversity = $coursedetailmodel->getAffiliatedUniversityOfCourse($courseIds);
        
        

        $instituteIds = array_merge($hierarchyInstitutes,$affiliatedUniversity);
       
        $instituteIds = array_unique($instituteIds);
       	
        $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');

        $shikshamodel = $this->load->model("common/shikshamodel");
        $listingsCronsLib = $this->load->library('nationalInstitute/ListingsCronsLib');
        foreach ($instituteIds as  $value) {
        	if(empty($value)){
        		continue;
        	}

            $this->nationalinstitutecache->invalidateCourseWidgetCache($value);
            $listingsCronsLib->populateCourseWidgetCacheForInstitute($value);
            $this->nationalinstitutecache->invalidateAdmissionCoursesCache($value);
            $listingsCronsLib->populateAdmissionPageCoursesData($value);
            $arr = array("cache_type" => "htmlpage", "entity_type" => "institute", "entity_id" => $value, "cache_key_identifier" => "");
            $shikshamodel->insertCachePurgingQueue($arr);
        }
   }

    function disableCourse()
    {
    	$this->init();
        
		// $user_id      = $validateuser[0]['userid'];
		$request_body = file_get_contents('php://input');
		$data         = json_decode($request_body,true);

		$courseId    = $data['courseId'];
		$url    = $data['url'];
		
        if($courseId){
        	$open = $this->coursecache->canOpenCourseInEdit($courseId,$this->userId);
        	if($open['open']){
        		/*update status as disable in all corresponding tables*/
	       		$disableStatus = $this->coursepostingmodel->updateCourseDisableUrl(array($courseId),$url,'course');
	       		$this->coursecache->removeCoursesCache(array($courseId));
	       		$this->courseNotificationMailerData('disable',array('courseId'=>$courseId,'userId' =>$this->userId));
	        	echo json_encode(array('data'=>array('message'=>'Course disable successfully.','status'=>'success')));
        	}
        	else{
        		echo json_encode(array('data'=>array('status'=>'fail','message'=>'Another person(userId: '.$open['userId'].') is currently editing the course.Please open after some time')));
        	}
    	}
    	else{
    		echo json_encode(array('data'=>array('message'=>'something went wrong.','status'=>'fail')));
    	}
 
    }

    function enableCourse()
    {
    	$this->init();
        
		$user_id      = $validateuser[0]['userid'];
		$request_body = file_get_contents('php://input');
		$data         = json_decode($request_body,true);

		$courseId    = $data['courseId'];
		$status      = $data['status'];
		
        if($courseId){
        	$open = $this->coursecache->canOpenCourseInEdit($courseId,$this->userId);
        	if($open['open']){

        		$data = $this->coursePostingLib->getBasicCourseAndParentData($courseId);
        		if(!empty($data['parent_data']['disabled_url'])){
        			echo json_encode(array('data'=>array('message'=>'Cannot enable the course because its parent ('.$data['parent_type'].'Id: '.$data['parent_id'].' ) is disabled','status'=>'fail')));die;
        		}

		    	/*update status as disable in all corresponding tables*/
		       	$enableStatus = $this->coursepostingmodel->updateCourseDisableUrl(array($courseId));
        		$this->coursecache->removeCoursesCache(array($courseId));
		       	$this->courseNotificationMailerData('enable',array('courseId'=>$courseId,'userId' =>$this->userId));
		        echo json_encode(array('data'=>array('message'=>'Course enable successfully.','status'=>'success')));die;
		    }
		    else{
		    	echo json_encode(array('data'=>array('status'=>'fail','message'=>'Another person(userId: '.$open['userId'].') is currently editing the course.Please open after some time')));die;
		    }
    	}
    	else{
    		echo json_encode(array('data'=>array('message'=>'something went wrong.','status'=>'fail')));die;
    	}
 
    }

    function makeCourseLive(){
    	$this->init();
        
		$user_id      = $validateuser[0]['userid'];
		$request_body = file_get_contents('php://input');
		$data         = json_decode($request_body,true);
		
		$courseId     = $data['courseId'];
		$status       = $data['status'];

		$open = $this->coursecache->canOpenCourseInEdit($courseId,$this->userId);
		if($open['open']){
			$response = $this->coursepostingmodel->makeCourseLive($courseId,$this->userId);
			if($response){
				$this->coursecache->removeCoursesCache(array($courseId));
				echo json_encode(array('data'=> array('msg'=> 'course made as live','status'=>'success')));die;
			}
			else{
				echo json_encode(array('data'=> array('msg'=> 'Failed to made Live','status'=>'fail')));die;
			}
		}
		else{
			echo json_encode(array('data'=>array('status'=>'fail','msg'=>'Another person(userId: '.$open['userId'].') is currently editing the course.Please open after some time')));
		}
    }


    function makeLiveAndEnableCourseListing(){
    	$this->init();
        
		$user_id      = $validateuser[0]['userid'];
		$request_body = file_get_contents('php://input');
		$data         = json_decode($request_body,true);
		
		$courseId     = $data['courseId'];
		$status       = $data['status'];

		$open = $this->coursecache->canOpenCourseInEdit($courseId,$this->userId);
		if($open['open']){

			$data = $this->coursePostingLib->getBasicCourseAndParentData($courseId);
			if(!empty($data['parent_data']['disabled_url'])){
				echo json_encode(array('data'=>array('msg'=>'Cannot enable the course because its parent ('.$data['parent_type'].'Id: '.$data['parent_id'].' ) is disabled','status'=>'fail')));die;
			}

			$this->coursepostingmodel->updateCourseDisableUrl(array($courseId));
			$response = $this->coursepostingmodel->makeCourseLive($courseId);
			if($response){
				$this->coursecache->removeCoursesCache(array($courseId));
				echo json_encode(array('data'=> array('msg'=> 'course made as live','status'=>'success')));die;
			}
			else{
				echo json_encode(array('data'=> array('msg'=> 'Failed to made Live','status'=>'fail')));die;
			}
		}
		else{
			echo json_encode(array('data'=>array('status'=>'fail','msg'=>'Another person(userId: '.$open['userId'].') is currently editing the course.Please open after some time')));
		}
    }


    public function getRecruitingCompaniesMappedToInstitute(){
		$this->init();		
		$instituteId = $this->input->post('instituteId');
		$data = $this->institutedetailsmodel->getRecruitingCompanies($instituteId);
		$formatData = array();
		foreach ($data as $key => $value) {
			$formatData[$value['company_id']] = $value;
		}
		echo json_encode($formatData);
		exit;
	}

	public function getInstituteMedia(){
		$this->init();		
		$instituteId = $this->input->post('instituteId');
		$data = $this->institutedetailsmodel->getInstituteMedia($instituteId);
		foreach ($data as $key => $media) {
			$data[$key]['media_thumb_url'] = addingDomainNameToUrl(array('url' => $media['media_thumb_url'] , 'domainName' =>MEDIA_SERVER));;
			$data[$key]['media_url'] = addingDomainNameToUrl(array('url' => $media['media_url'] , 'domainName' =>MEDIA_SERVER));
		}
		$formatData = array();
		foreach ($data as $key => $value) {
			$formatData[] = $value;			
		}
		echo json_encode($formatData);
		exit;	
	}

	function getCoursePostingComments(){

		$this->init();
		$courseId = $this->input->post('courseId');
		$data = array();
		if(!empty($courseId)){
			$data = $this->coursepostingmodel->getCoursePostingComments($courseId);
		}

		
		$commentData = array();
		foreach ($data as $key=>$row) {
			$tempData = array();
			$tempData['name'] = $row['firstname']." ".$row['lastname'];
			$tempData['name'] = ucwords(trim($tempData['name']));
			$tempData['comment'] = $row['comments'];
			$tempData['userId']  = $row['userId'];
			$tempData['addTime'] = date("F j, Y, g:i a", strtotime($row['updatedAt']));
			$tempData['key']     = $key+1;

			$commentData[] = $tempData;
		}
		echo json_encode($commentData);
		exit(0);
	}

	public function releaseLockOnCourse(){
		$courseId = $this->input->post('courseId');
		$this->coursecache->removeLockOnCourseForEdit($courseId);
		echo json_encode(array('data' => array('status'=>'success','message'=>'Lock released successfully')));
	}
	function deleteCoursePageOnGoogleCDNcacheAMP($courseIds = array())
	{
		if(!is_array($courseIds) && count($courseIds) == 0)
			return;

		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();
    	foreach ($courseIds as $key => $courseId) {
    		$courseObj = $this->courseRepo->find($courseId,array('basic'));
    		if(!empty($courseObj))
    		{
    			$ampURL = $courseObj->getAmpURL();
    			if(!empty($ampURL))
    			{
    				updateGoogleCDNcacheForAMP($ampURL,true);
    				error_log('deleted Google CDN Cache For Course = '.$courseId);	
    			}
    			
    		}
    	}
	}

	public function removeCourseCache($courseId){
		$courseId = explode(',',$courseId);
		if(!empty($courseId)){
			$this->coursecache->removeCoursesCache($courseId);
		}
		_p('Done');
	}

	public function removeCourseInterLinkingCache($courseIds){
		$courseIds = explode(',',$courseIds);
		if(!empty($courseIds)){
			$this->coursecache->removeCourseInterLinking($courseIds);
		}
		_p('Done');
	}

	public function migrateContentTablesByConfig(){
		// update all tables where both courseid and instituteId are present 
		$this->load->config('nationalCourse/duplicateListingConfig');
		$this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
		$this->load->builder('nationalInstitute/InstituteBuilder');
		$instituteBuilder = new InstituteBuilder();
		$this->instituteRepo = $instituteBuilder->getInstituteRepository();

		$oldToNewInstituteMapping = $this->config->item('oldToNewInstituteMapping');
		if(empty($oldToNewInstituteMapping)){
			return;
		}

		$allCoursesData = $this->instituteDetailLib->getAllCoursesForMultipleInstitutes(array_keys($oldToNewInstituteMapping));
		// _p($allCoursesData);die;
		$instituteWiseCourses = array();
		$instituteWiseHierarchy = array();
		foreach ($allCoursesData as $instituteId => $coursesData) {
			if(!empty($coursesData['courseIds'])){
				$instituteWiseCourses[$instituteId] = $coursesData['courseIds'];
			}
			foreach ($coursesData['type'] as $hierarchyId => $type) {
				if($hierarchyId != $instituteId){
					$instituteWiseHierarchy[$instituteId][] = $hierarchyId;
				}
			}
		}

		if(!empty($instituteWiseCourses)){
			foreach ($instituteWiseCourses as $instituteId => $courseIds) {
				_p('The following courses are still mapped to old institute hierarchy '.$instituteId.": ".implode(',',$courseIds));
				_p('Please map them to new institute/university: '. $oldToNewInstituteMapping[$instituteId] . ' hierarchy');
			}
			die;
		}
		if(!empty($instituteWiseHierarchy)){
			foreach ($instituteWiseHierarchy as $instituteId => $instituteIds) {
				_p('The following institutes are still mapped to old institute hierarchy '.$instituteId.": ".implode(',',$instituteIds));
				_p('Please map them to new institute/university: '. $oldToNewInstituteMapping[$instituteId] . ' hierarchy');
			}
			die;
		}

		$instituteDetailsMapping = array();
		$instituteIds = array_merge(array_keys($oldToNewInstituteMapping), array_values($oldToNewInstituteMapping));
		$instituteObjs = $this->instituteRepo->findMultiple($instituteIds);
		foreach ($instituteObjs as $instId => $instituteObj) {
			$mainLocation = $instituteObj->getMainLocation();
			$instituteDetailsMapping[$instId] = array('listingLocationId' => $mainLocation->getLocationId(),'stateId' => $mainLocation->getStateId(),'cityId' => $mainLocation->getCityId(),'localityId' => $mainLocation->getLocalityId(),'type' => $instituteObj->getType());
		}

		// _p($instituteDetailsMapping);die('aaaa');
		// remaining 
		// online forms,popularity,naukri data,tags mappings, response migration
		// questions not needed

		$this->RankingCommonLibv2 = $this->load->library('rankingV2/RankingCommonLibv2');
		$this->LDBClienLib = $this->load->library('LDB/clients/LDBMigrationLib');
		$this->institutePostingLib = $this->load->library('nationalInstitute/InstitutePostingLib');
		$this->institutepostingmodel = $this->load->model('nationalInstitute/institutepostingmodel');

		// $newToOldMapping = array_flip($oldToNewInstituteMapping);

		// all those tables having both courseId and instituteId
		/*$allCoursesData = $this->instituteDetailLib->getAllCoursesForMultipleInstitutes(array_values($oldToNewInstituteMapping));
		foreach ($allCoursesData as $coursesData) {
			foreach ($coursesData['instituteWiseCourses'] as $instituteId => $courseIds) {
				// migrate rankings
				$this->RankingCommonLibv2->updateInstituteForCourseIds($courseIds,$instituteId);
			}
		}*/

		// all those tables having both instituteId only
		foreach ($oldToNewInstituteMapping as $oldId => $newId) {
			// update Rankings
			$this->RankingCommonLibv2->updateInstituteId($oldId,$newId);
			// migrate reviews
			$this->LDBClienLib->updateDataForInstituteDeletion($oldId,$newId);

			// migrate articles,exam mappings, CR
			$this->institutePostingLib->migrateUGCModules($oldId,$newId);

			// migrate view count
			$viewCountModel = $this->load->model('common/viewcountmodel');
			$viewCountModel->updateNewInstituteId($oldId,$newId);

			/*update AnARecommendation table*/
			$AnARecomodel = $this->load->model("ContentRecommendation/anarecommendationmodel");
			$AnARecomodel->updateInstituteIdAnARecommendation($oldId,$newId);

			// seo redirection
			$this->InstitutePostingLib = $this->load->library('nationalInstitute/InstitutePostingLib');
			$this->institutePostingLib->migrateDeletedInstituteData($oldId,$newId,$instituteDetailsMapping[$oldId]['type']);

			// indexing of new institutes
			$this->institutepostingmodel->updateIndexLog(array($newId),$instituteDetailsMapping[$newId]['type'],'index');

			//updating LDB Tables
			$this->LDBClienLib->migrateInstituteScript($oldId, $newId, $instituteDetailsMapping);

            //Deleting Institute amp cache from Google CDN
            Modules::run('nationalInstitute/InstitutePosting/deleteInstituteUniveristyPageOnGoogleCDNcacheAMP',array($oldId,$newId));

            //Migrate Tag, Naukri Data, ANA tables
            $this->institutepostingmodel->migrateOtherUGCModules($oldId,$newId);

		}

		// refresh ranking page cache
		Modules::run('rankingV2/RankingMain/populateNonZeroRankingData');
	}

	public function test(){
		$this->load->builder('LocationBuilder','location');
		$locationBuilder                                = new LocationBuilder;
		$this->locationRepo                       = $locationBuilder->getLocationRepository();
		echo "start";
		$data = $this->locationRepo->getCitiesByVirtualCity(10223);
		// $data = $this->locationRepo->getCitiesByMultipleTiers(array(1,2),2);
		// $data = $this->locationRepo->findMultipleStates(array(109,110));
		// $data = $this->locationRepo->findState(109);
		// $data = $this->locationRepo->findMultipleCities(array(30,31));
		// $data = $this->locationRepo->findCity(10223);
		// $data = $this->locationRepo->findMultipleLocalities(array(80,81,82));
		_p($data);die;

		$this->load->builder('ListingBaseBuilder', 'listingBase');
        $this->ListingBaseBuilder    = new ListingBaseBuilder();
        $streamRepo = $this->ListingBaseBuilder->getStreamRepository();
		$data = $streamRepo->find(2);
		_p($data);
		$substreamRepo = $this->ListingBaseBuilder->getSubstreamRepository();
		$data = $substreamRepo->find(1);
		_p($data);
		$specRepo = $this->ListingBaseBuilder->getSpecializationRepository();
		$data = $specRepo->find(1);
		_p($data);
		$baseCourseRepo = $this->ListingBaseBuilder->getBaseCourseRepository();
		$data = $baseCourseRepo->find(1);
		_p($data);
		$hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
		$data = $hierarchyRepo->getBaseEntitiesByHierarchyId(array(1,2));
		_p($data);
	}

	public function removeCacheForBaseEntities(){
		$this->load->builder('ListingBaseBuilder', 'listingBase');
        $this->ListingBaseBuilder    = new ListingBaseBuilder();
        $streamRepo = $this->ListingBaseBuilder->getStreamRepository();
        $streamRepo->getCache()->removeCacheForBaseEntities();
        _p('DONE');
	}
}

