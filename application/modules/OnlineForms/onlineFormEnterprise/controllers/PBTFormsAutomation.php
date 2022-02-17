<?php 
class PBTFormsAutomation extends MX_Controller
{
	function __construct(){
		parent::__construct('OnlineForms');
	}

	private function init(){
		$this->load->model('pbtautomationmodel');
		$this->load->helper(array('pbtautomation', 'url'));
		$this->load->config('onlineFormConfig');
	}

	public function enablePBTForm(){
		$this->load->view('PBT/enablingPBTForms');
	}

	public function getPBTDetailsOfCollege(){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		if($this->input->is_ajax_request() && $cmsUserInfo['usergroup'] == 'cms'){
			$output = array();
			$courseData = array();
			$subCatName = array();
			$subCatDepttIds = array();
			$res = array();
			$instDetailIds = array();
			$instDetailIdsStreamWise = array();
			$courseCatData = array();
			$courseIds = array();
			$instDetailData = array();
			$instConfigData = array();
			$pbtautomationmodel = new pbtautomationmodel();
			$clgId = (isset($_POST['clgId']) && $_POST['clgId'] != '' && $_POST['clgId'] > 0)?$this->input->post('clgId'):0;
			$instituteName = '';
			if($clgId > 0){
				$this->load->builder("nationalInstitute/InstituteBuilder");
	    		$instituteBuilder = new InstituteBuilder();
	    		$instituteRepository = $instituteBuilder->getInstituteRepository();
				$instObj = $instituteRepository->find($clgId);
				$instituteName = $instObj->getName();
				
				$pbtDetails = $pbtautomationmodel->getPBTFormsForTheCollege($clgId);
				$depttToStreamMap = $this->config->item('depttToStreamMap');

				$this->load->builder("nationalCourse/CourseBuilder");
				$builder = new CourseBuilder();
				$courseRepository = $builder->getCourseRepository();
				
				$this->load->builder('ListingBaseBuilder', 'listingBase');
        		$listingBaseBuilder   = new ListingBaseBuilder();
        		$basecourseRepository = $listingBaseBuilder->getBaseCourseRepository();
        		$streamRepository     = $listingBaseBuilder->getStreamRepository();
				//$courseRepository = $listingBuilder->getCourseRepository();
				$allCourseIds = array();
				foreach ($pbtDetails as $val) {
					$allCourseIds[] = $val['course_id'];
				}
				$courseObjs = '';
				if(!empty($allCourseIds)){
					$courseObjs = $courseRepository->findMultiple($allCourseIds);
				}
				$streamsArr = array();
				$baseCourseBucket = array();
				$courseBucket = array();
				foreach ($pbtDetails as $key => $value) {
					$baseCourseObj = 0;
					$courseTypeInformation = $courseObjs[$value['course_id']]->getCourseTypeInformation();
					$courseTypeInformation = $courseTypeInformation['entry_course'];
					$baseCourseId = $courseTypeInformation->getBaseCourse();
					if(!empty($baseCourseId) && $baseCourseId > 0){
						$baseCourseObj = $basecourseRepository->find($baseCourseId);
					}
					
					$primaryHierarchy = $courseObjs[$value['course_id']]->getPrimaryHierarchy();
					$streamObj = $streamRepository->find($primaryHierarchy['stream_id']);
					//_p($streamObj->getName());
					if(!in_array($baseCourseId, $baseCourseBucket[$primaryHierarchy['stream_id']])){
						$baseCourseBucket[$primaryHierarchy['stream_id']][] = $baseCourseId;
						if(is_object($baseCourseObj)){
							$streamsArr[$primaryHierarchy['stream_id']][] = array('baseCourseId'=>$baseCourseId, 'baseCourseName'=>$baseCourseObj->getName(), 'streamName'=>$streamObj->getName());
						}else{
							$streamsArr[$primaryHierarchy['stream_id']][] = array('streamName'=>$streamObj->getName());
						}
					}
					if($value['instDetailId'] > 0){
						$instDetailIds[$primaryHierarchy['stream_id']][$baseCourseId] = array('instDetailId' => $value['instDetailId'], 'baseCourseId' => $baseCourseId);
						$instDetailIdsStreamWise[$primaryHierarchy['stream_id']] = array('instDetailId' => $value['instDetailId'], 'baseCourseId' => $baseCourseId);
						$courseCatData[$primaryHierarchy['stream_id']][$baseCourseId] = array('mainCrs'=>$value['course_id'], 'crsName'=>$value['courseTitle']);
					}
					$value['baseCourseId'] = $baseCourseId;
					$courseBucket[$primaryHierarchy['stream_id']][$baseCourseId][] = $value['course_id'];
					$courseData[$primaryHierarchy['stream_id']][] = $value;
					$streamDepttIds[$primaryHierarchy['stream_id']] = getDepttIdOnStream($primaryHierarchy['stream_id'], $depttToStreamMap);

				}
				if(!empty($allCourseIds)){
					$instDetailData = $pbtautomationmodel->getPBTInstDetailsForCourses($allCourseIds);
				}
				$pbtCourseBucket = array();
				$iter = 1;
				foreach ($instDetailData as $mainCourseId => $ofData) {
					$courseTypeInformation = $courseObjs[$mainCourseId]->getCourseTypeInformation();
					$courseTypeInformation = $courseTypeInformation['entry_course'];
					$baseCourseId = $courseTypeInformation->getBaseCourse();
					$streamId = $depttToStreamMap[$ofData['departmentId']]['streamId'];
					$pbtCourseBucket[$streamId][$baseCourseId][] = $ofData['courseId'];
					if($ofData['otherCourses'] != ''){
						$otherCourses = explode(',', $ofData['otherCourses']);
						foreach ($otherCourses as $val) {
							$pbtCourseBucket[$streamId][$baseCourseId][] = $val;
						}
					}
					$iter++;
				}
			}
			$courseCount = array();
			foreach ($courseBucket as $streamId => $baseCourses) {
				foreach ($baseCourses as $baseCourseId => $courses) {
					$courseCount[$streamId][$baseCourseId] = count($courses);
				}
			}
			$pbtCourseCount = array();
			foreach ($pbtCourseBucket as $streamId => $baseCourses) {
				foreach ($baseCourses as $baseCourseId => $courses) {
					$pbtCourseCount[$streamId][$baseCourseId] = count($courses);
				}
			}

			$selectAllData = array();
			foreach ($courseCount as $stream => $countData) {
				$sum1 = array_sum($countData);
				if(count($pbtCourseCount[$stream]) == 1){
					$sum2 = array_sum($pbtCourseCount[$stream]);
					if($sum2 == $sum1){
						$selectAllData[$stream] = 'all';
					}else{
						$selectAllData[$stream] = 'bc';
					}
				}
			}
			$output['streamsArr']      = $streamsArr;
			$output['instituteName']   = $instituteName;
			$output['subCatDepttIds']  = $subCatDepttIds;
			$output['streamDepttIds']  = $streamDepttIds;
			$output['subCatName']      = $subCatName;
			$output['courseData']      = $courseData;
			$output['instDetailIds']   = $instDetailIds;
			$output['instDetailData']  = $instDetailData;
			$output['instConfigData']  = $instConfigData;
			$output['courseCatData']   = $courseCatData;
			//$output['pbtCourseBucket'] = $pbtCourseBucket;
			//$output['courseBucket']    = $courseBucket;
			$output['selectAllData']  = $selectAllData;
			$output['instDetailIdsStreamWise'] = $instDetailIdsStreamWise;
			echo json_encode($output);
		}else{
			$error = array('status'=>'unauthorized');
			echo json_encode($error);
		}
	}

	public function addNewPBTForm(){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$instDetails = array();
		$instConfig  = array();
		$pbtautomationmodel = new pbtautomationmodel();
		$key = 'OnlineFormsPBTSeoDetails';
		$cacheLib = $this->load->library('cacheLib');
		//case for edit existing PBT form
		if((isset($_POST['instDetailId']) && $_POST['instDetailId']!='' && $_POST['instDetailId'] > 0) && $cmsUserInfo['usergroup'] == 'cms')
		{
			$depttToStreamMap = $this->config->item('depttToStreamMap');
			$instDetailId = isset($_POST['instDetailId']) && $_POST['instDetailId']!='' && $_POST['instDetailId'] > 0 ? $this->input->post('instDetailId') : 0;
			$instConfigId = isset($_POST['instConfigId']) && $_POST['instConfigId']!='' && $_POST['instConfigId'] > 0 ? $this->input->post('instConfigId') : 0;
			
			$instDetails['instituteId']          = isset($_POST['collegeId']) && $_POST['collegeId']!='' && $_POST['collegeId'] > 0 ? $this->input->post('collegeId') : 0;
			$instDetails['courseId']             = isset($_POST['mainCourseId']) && $_POST['mainCourseId']!='' && $_POST['mainCourseId'] > 0 ? $this->input->post('mainCourseId') : 0;
			$instDetails['courseCode']           = '';
			$instDetails['formIdMinRange']       = '';
			$instDetails['formIdMaxRange']       = '';
			$instDetails['instituteDisplayText'] = '';
			$instDetails['fees']                 = isset($_POST['formFee']) && $_POST['formFee']!='' && $_POST['formFee'] > 0 ? $this->input->post('formFee') : 0;
			$instDetails['creationDate']         = date('Y-m-d H:i:s');
			$instDetails['status']               = 'live';
			$instDetails['logoImage']            = '';
			$instDetails['basicInformation']     = '';
			$instDetails['discount']             = isset($_POST['formFeeDiscount']) && $_POST['formFeeDiscount']!='' && $_POST['formFeeDiscount'] > 0 ? $this->input->post('formFeeDiscount') : 0;
			$instDetails['last_date']            = isset($_POST['lastDate']) && $_POST['lastDate']!='' ? $this->input->post('lastDate') : '';
			$instDetails['min_qualification']    = isset($_POST['minQualification']) && $_POST['minQualification']!='' ? $this->input->post('minQualification') : '';
			$instDetails['exams_required']       = isset($_POST['qualifyingExams']) && $_POST['qualifyingExams']!='' ? $this->input->post('qualifyingExams') : '';
			$instDetails['courses_available']    = '';
			$instDetails['departmentId']         = isset($_POST['depttId']) && $_POST['depttId']!='' && $_POST['depttId'] > 0 ? $this->input->post('depttId') : 0;
			$instDetails['departmentName']       = isset($depttToStreamMap[$instDetails['departmentId']]['depttName']) ? $depttToStreamMap[$instDetails['departmentId']]['depttName'] : '';
			$instDetails['instituteEmailId']     = '';
			$instDetails['instituteAddress']     = '';
			$instDetails['imageSpecifications']  = 'Passport size colour photograph (4.5 X 3.5 cm)';
			$instDetails['documentsRequired']    = '';
			$instDetails['instituteMobileNo']    = '';
			$instDetails['instituteLandline']    = '';
			$instDetails['demandDraftInFavorOf'] = '';
			$instDetails['demandDraftPayableAt'] = '';
			$instDetails['sessionYear']          = isset($_POST['sessionYear']) && $_POST['sessionYear']!='' && $_POST['sessionYear'] > 0 ? $this->input->post('sessionYear') : date('Y');
			$instDetails['otherCourses']         = isset($_POST['otherCourses']) && $_POST['otherCourses']!='' ? trim($this->input->post('otherCourses')) : '';
			$instDetails['externalURL']          = isset($_POST['landingPageUrl']) && $_POST['landingPageUrl']!='' ? trim($this->input->post('landingPageUrl')) : '';

			if($instDetailId > 0){
				$currData = $pbtautomationmodel->getPBTInstDetails($instDetailId);
				$isModified = compareDataBeforeUpdate($currData, $instDetails);
				if($isModified == true){
					//update OF_InstituteDetails table, mark record as 'history'
					$sts = $pbtautomationmodel->editInstDetailsForPBT(array('status'=>'history'), $instDetailId);
					if($sts == true){
						//insert new record into OF_InstituteDetails table 
						$pbtautomationmodel->addInstDetailsForPBT($instDetails);
						$cacheLib->clearCacheForKey($key);
					}
				}
			}
			if($instConfigId > 0){
				//update OF_PBTSeoDetails table
			}
			if($isModified == true){
				$this->setCookieAndRedirect(2);
			}else{
				$this->setCookieAndRedirect(3);
			}
		}
		//case for adding new PBT form
		else if(isset($_POST['mainCourseId']) && $cmsUserInfo['usergroup'] == 'cms')
		{
			$depttToStreamMap = $this->config->item('depttToStreamMap');
			$collegeName    = isset($_POST['collegeName']) && $_POST['collegeName']!='' ? trim($this->input->post('collegeName')) : '';
			$mainCourseName = isset($_POST['mainCourseName']) && $_POST['mainCourseName']!='' ? trim($this->input->post('mainCourseName')) : '';
			$instDetails['instituteId']          = isset($_POST['collegeId']) && $_POST['collegeId']!='' && $_POST['collegeId'] > 0 ? $this->input->post('collegeId') : 0;
			$instDetails['courseId']             = isset($_POST['mainCourseId']) && $_POST['mainCourseId']!='' && $_POST['mainCourseId'] > 0 ? $this->input->post('mainCourseId') : 0;
			$instDetails['courseCode']           = '';
			$instDetails['formIdMinRange']       = '';
			$instDetails['formIdMaxRange']       = '';
			$instDetails['instituteDisplayText'] = '';
			$instDetails['fees']                 = isset($_POST['formFee']) && $_POST['formFee']!='' && $_POST['formFee'] > 0 ? $this->input->post('formFee') : 0;
			$instDetails['creationDate']         = date('Y-m-d H:i:s');
			$instDetails['status']               = 'live';
			$instDetails['logoImage']            = '';
			$instDetails['basicInformation']     = '';
			$instDetails['discount']             = isset($_POST['formFeeDiscount']) && $_POST['formFeeDiscount']!='' && $_POST['formFeeDiscount'] > 0 ? $this->input->post('formFeeDiscount') : 0;
			$instDetails['last_date']            = isset($_POST['lastDate']) && $_POST['lastDate']!='' ? $this->input->post('lastDate') : '';
			$instDetails['min_qualification']    = isset($_POST['minQualification']) && $_POST['minQualification']!='' ? $this->input->post('minQualification') : '';
			$instDetails['exams_required']       = isset($_POST['qualifyingExams']) && $_POST['qualifyingExams']!='' ? $this->input->post('qualifyingExams') : '';
			$instDetails['courses_available']    = '';
			$instDetails['departmentId']         = isset($_POST['depttId']) && $_POST['depttId']!='' && $_POST['depttId'] > 0 ? $this->input->post('depttId') : 0;
			$instDetails['departmentName']       = isset($depttToStreamMap[$instDetails['departmentId']]['depttName']) ? $depttToStreamMap[$instDetails['departmentId']]['depttName'] : '';
			$instDetails['instituteEmailId']     = '';
			$instDetails['instituteAddress']     = '';
			$instDetails['imageSpecifications']  = 'Passport size colour photograph (4.5 X 3.5 cm)';
			$instDetails['documentsRequired']    = '';
			$instDetails['instituteMobileNo']    = '';
			$instDetails['instituteLandline']    = '';
			$instDetails['demandDraftInFavorOf'] = '';
			$instDetails['demandDraftPayableAt'] = '';
			$instDetails['sessionYear']          = isset($_POST['sessionYear']) && $_POST['sessionYear']!='' && $_POST['sessionYear'] > 0 ? $this->input->post('sessionYear') : date('Y');
			$instDetails['otherCourses']         = isset($_POST['otherCourses']) && $_POST['otherCourses']!='' ? trim($this->input->post('otherCourses')) : '';
			$instDetails['externalURL']          = isset($_POST['landingPageUrl']) && $_POST['landingPageUrl']!='' ? trim($this->input->post('landingPageUrl')) : '';
			$pbtautomationmodel->addInstDetailsForPBT($instDetails);

			$instConfig['seoTitle']          = '<collegeName> <courseName> Application Form - Dates, Fee';
			$instConfig['seoDescription']    = '<collegeName> application form - Apply online for admission in <courseName> offered by <collegeName>';

			$this->load->builder("nationalInstitute/InstituteBuilder");
	    	$instituteBuilder = new InstituteBuilder();
	    	$instituteRepo = $instituteBuilder->getInstituteRepository();
	    	$instituteObj  = $instituteRepo->find($instDetails['instituteId'], array('location'));
			$instMainLocality = $instituteObj->getMainLocation()->getLocalityName();
			$instMainCity = $instituteObj->getMainLocation()->getCityName();
			
			if(strpos($collegeName, $instMainCity) === false){
				$collegeName .= '-'.$instMainLocality.'-'.$instMainCity;
			}else{
				$collegeName .= '-'.$instMainLocality;
			}
			
			$instConfig['seoUrl']            = getSeoUrl($instDetails['courseId'], 'applicationform', $collegeName, array('departmentName'=>$instDetails['departmentName']));
			$instConfig['altImageHeader']    = $collegeName;
			$instConfig['instituteId']       = $instDetails['instituteId'];
			$instConfig['courseId']          = $instDetails['courseId'];
			$instConfig['status']            = 'live';
			$instConfig['externalUrl']       = 'yes';
			$pbtautomationmodel->addInstConfigDetailsForPBT($instConfig);
			$cacheLib->clearCacheForKey($key);
			$this->setCookieAndRedirect(1);
		}else{
			redirect('enterprise/Enterprise/disallowedAccess', 'location', 301);
		    exit();
		}
	}

	private function setCookieAndRedirect($cookieVal){
		setcookie('pbtAdd', $cookieVal, time() + 60, '/', COOKIEDOMAIN);
		redirect('enterprise/Enterprise/onlineFormTracking/admin/extForm', 'location', 301);
		exit();
	}
	public function getExternalFormConfigDetails($instIdArr = array(), $crsIdArr = array(), $resetCache = 0){
		$this->init();
		$data = array();
		$cacheLib = $this->load->library('cacheLib');
		$key = 'OnlineFormsPBTSeoDetails';
		$data = $cacheLib->get($key);
		if($data == 'ERROR_READING_CACHE' || $resetCache == 1){
			$pbtautomationmodel = new pbtautomationmodel();
			$data = $pbtautomationmodel->getExternalFormConfigDetails();
			$data = formatDataAccordingToOldConfig($data);
			$cacheLib->store($key, $data, 0);
		}
		$finalData = array();
		if(!empty($instIdArr)){
			foreach ($data as $crsId => $value) {
				if(in_array($value['instituteId'], $instIdArr)){
					$finalData[$crsId] = $value;
				}
			}
		}
		if(!empty($crsIdArr)){
			foreach ($data as $crsId => $value) {
				if(in_array($crsId, $crsIdArr)){
					$finalData[$crsId] = $value;
				}
			}
		}
		if(empty($finalData)){
			return $data;
		}else{
			return $finalData;
		}
	}
}
