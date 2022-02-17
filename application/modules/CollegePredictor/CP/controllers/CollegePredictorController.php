<?php
/*
   Copyright 2014 Info Edge India Ltd

   $Author: Pranjul

   $Id: CollegePredictorController.php

 */

class CollegePredictorController extends MX_Controller
{
	private $instituteRepository;
        function init($examName){
			$library=array('ajax');
			$helper=array('url','image','shikshautility','utility_helper');
			if(is_array($helper)){
				$this->load->helper($helper);
			}

			if(is_array($library)){
				$this->load->library($library);
			}
			if(($this->userStatus == "")){
				$this->userStatus = $this->checkUserValidation();
			}

			$this->load->helper('CP/collegepredictor');
			$this->load->helper('coursepages/course_page');
			
			$this->load->builder("nationalCourse/CourseBuilder");
			    $courseBuilder = new CourseBuilder();
			    $this->courseRepository = $courseBuilder->getCourseRepository();
			

			$this->load->builder("nationalInstitute/InstituteBuilder");
		    $instituteBuilder = new InstituteBuilder();
		    $this->instituteRepository = $instituteBuilder->getInstituteRepository();
			//$this->instituteRepository = $listingBuilder->getInstituteRepository();         // this is use for compare tool only
			
			$this->load->builder('BranchBuilder','CP');
			$this->branchBuilder = new BranchBuilder;
			$this->branchRepository = $this->branchBuilder->getBranchRepository();		
	
			$this->institutes = $this->getInstituteData($examName);
			$this->states = $this->getStates();
			$this->branches = $this->getBranches($examName);
			//$this->instituteGroups = $this->getInstituteGroups($examName);
			
			$this->commonData = array();
			$this->commonData['institutes'] = $this->institutes;
			$this->commonData['states'] = $this->states;
			$this->commonData['branches'] = $this->branches;
			// $this->commonData['instituteGroups'] = $this->instituteGroups;
			
			//$this->load->library('CP/CollegePredictorConfig');
			//$this->settingArray = CollegePredictorConfig::$settings;
			$this->load->config('CP/CollegePredictorConfig',TRUE);
			$this->settingArray = $this->config->item('settings','CollegePredictorConfig');
			$this->cpUrlMapping = $this->config->item('cpUrlMapping','CollegePredictorConfig');
			$this->collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');

	}

	function loadRankTab($examName = 'JEE-Mains', $folderName = 'b-tech'){
		$this->collegepredictorredirectionrules = $this->load->library('CollegePredictorRedirectionRules');
		$this->init($examName);
        $this->collegepredictorredirectionrules->redirectionRule($examName, $folderName, 'rank');
		$callType = isset($_REQUEST['callType'])?$this->input->post('callType'):'';
		$isPrint = (isset($_REQUEST['export']) && $_REQUEST['export'] == 'print')?true:false;
		$examNameNew = strtoupper($examName);
		if(!isset($this->settingArray[$examNameNew]) || empty($this->settingArray[$examNameNew]))
		{
			show_404();
		}
		$sortStatus = $this->settingArray[$examNameNew]['showSorting'];
		$filterStatus = $this->settingArray[$examNameNew]['showFilters'];
		$examinationName = $this->settingArray[$examNameNew]['examName'];
		$examYear = $this->settingArray[$examNameNew]['examYear'];
		 
		if($sortStatus=='YES'){
			$sortKey                = (isset($_REQUEST['sort_keyname_'.$examinationName]) && $_REQUEST['sort_keyname_'.$examinationName] != '') ? $_REQUEST['sort_keyname_'.$examinationName] : "rank";
			$sortOrder              = (isset($_REQUEST['sort_order_'.$examinationName]) && $_REQUEST['sort_order_'.$examinationName] != '') ? $_REQUEST['sort_order_'.$examinationName] : "asc";
		}
		if($filterStatus=='YES'){
			$filterTypeValueData    = (isset($_REQUEST['filterTypeValue_'.$examinationName]) && $_REQUEST['filterTypeValue_'.$examinationName] != '') ? $_REQUEST['filterTypeValue_'.$examinationName] : "";
		}
		$type    = (isset($_REQUEST['type']) && $_REQUEST['type'] != '') ? $this->input->post('type') : "";
		
		$linkUrl = false;
		$defaultView = true;
		$start = 0 ;
		$cookieData = array();
		if($callType == 'Ajax'  || $isPrint) {
			$inputData = array();
			$inputData = getInputFromPostData($_REQUEST,'rank',$examName);
			$inputData['tabType'] = 'rank';
			$cookieData = $inputData;
			if(isset($_REQUEST['start']))
				$start = $_REQUEST['start'];
			
			if($start == 0 ){
				setcookie('collegepredictor_search_desktop_'.$examinationName,json_encode($cookieData),time() + 2592000,'/',COOKIEDOMAIN);
				if($filterStatus=='YES'){
					setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,json_encode($filterTypeValueData),time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,'',time() - 3600,'/',COOKIEDOMAIN);
				}
				if($sortStatus=='YES'){
					setcookie('collegepredictor_sortData_desktop_'.$examinationName,$sortKey.'::::'.$sortOrder,time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_sortData_desktop_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
				}
			}
			$defaultView = false;
		}else {
			$inputData = $this->settingArray['defaultTabInfo']['DESKTOP'][$examNameNew];
			
			
			if(!empty($_REQUEST) && !empty($_REQUEST['tabType']) && ($_REQUEST['tabType'] == 'rank')) {
				$defaultView = false;
				$linkUrl = true;
				$inputData = array();
				$inputData = getInputFromPostData($_REQUEST,'rank',$examName);
				setcookie('collegepredictor_search_desktop_'.$examinationName,json_encode($inputData),time() + 86400,'/',COOKIEDOMAIN);
				if($filterStatus=='YES'){
					setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,json_encode($_GET['filterTypeValue_'.$examinationName]),time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
				}
				if($sortStatus=='YES'){
					setcookie('collegepredictor_sortData_desktop_'.$examinationName,$_GET['sort_keyname_'.$examinationName].'::::'.$_GET['sort_order_'.$examinationName],time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_sortData_desktop_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
				}
				if($filterStatus=='YES'){
					setcookie('collegepredictor_showFilters_'.$examinationName,$_GET['collegepredictor_showFilters_'.$examinationName],time() + 2592000,'/',COOKIEDOMAIN);
					setcookie('COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName,$_GET['COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName],time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_showFilters_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
					setcookie('COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
				}
				$url = $_SERVER['SCRIPT_URI'];
				header("Location: $url",TRUE,301);
				exit();
			}else {
				if(isset($_COOKIE['collegepredictor_search_desktop_'.$examinationName])) {
					$data = json_decode($_COOKIE['collegepredictor_search_desktop_'.$examinationName],true);
					if($data['tabType'] != 'rank') {
						//setcookie('collegepredictor_search_desktop',json_encode($cookieData),time() + 86400,'/',COOKIEDOMAIN);
					}else {
						$defaultView = false;
						$inputData = $data;
					}
				}else {
					$inputData['tabType'] = 'rank';
					
				}
			}
			
			
			
		} 	

		$displayData = array();
		$displayData = $this->commonData;
		$displayData['tabType'] = 'rank';
		$displayData['tab'] = '1';

		$displayData['courseRepository'] = $this->courseRepository;
		$displayData['instituteRepository'] = $this->instituteRepository;  // this is used only for compare tool to get headerimage of institue
		// _p($inputData);die;
		$branchObj = $this->branchRepository->findMultiple($inputData);
		// _p($branchObj); die;
		$branchObjData = $branchObj->getPageData();
		setcookie('COLLEGE_PREDICTOR_TOTAL_RESULT_'.$examinationName,count($branchObjData),time() + 2592000,'/',COOKIEDOMAIN);

		$i=0;$j=0;
		foreach($branchObjData as $key=>$value){
			$md5Data = md5($value->getInstituteId().$value->getCollegeName().$value->getCityName().$value->getStateName());
			if(!in_array($md5Data,$tmp['str'])){
				$displayData['institutesFilter'][$i]['id'] =  $value->getInstituteId();
				$displayData['institutesFilter'][$i]['collegeName'] =  $value->getCollegeName();
				$displayData['institutesFilter'][$i]['cityName'] =  $value->getCityName();
				$displayData['institutesFilter'][$i]['stateName'] =  $value->getStateName();
				$tmp['str'][] = md5($value->getInstituteId().$value->getCollegeName().$value->getCityName().$value->getStateName());
				$i++;
			}
			
			if(!in_array($value->getCollegeGroupName(),$temp) && $value->getCollegeGroupName()!=''){
				$displayData['instituteGroupsFilter'][$j]['collegeGroupName'] =  $value->getCollegeGroupName();
				$temp[] = $value->getCollegeGroupName();
				$j++;
			}
		
		}
			
		if(!$defaultView && $type=='' && $filterStatus=='YES'){
			$filterTypeValueData = json_decode($_COOKIE['collegepredictor_filterTypeValueData_desktop_'.$examinationName],true);
			$filters = $this->_getFilters($filterTypeValueData,$inputData,$branchObj);
			$displayData['collegePredictorFilters'] = $filters['collegePredictorFilters'];
			$displayData['defaultCollegePredictorFilters'] = $filters['defaultCollegePredictorFilters'];
			$displayData['filterInputData'] = $filters['filterInputData'];
		}
		if($filterStatus=='YES'){
		$displayData['filterTypeValueData'] = $filterTypeValueData;
		}
		if($sortStatus=='YES'){
			if($_REQUEST['sort_order_'.$examinationName]=='' && $_REQUEST['sort_keyname_'.$examinationName]=='' && $type!='search'  && $_COOKIE['collegepredictor_sortData_desktop_'.$examinationName]!='::::'  && !empty($_COOKIE['collegepredictor_sortData_desktop_'.$examinationName])){
				$sortData = explode('::::',$_COOKIE['collegepredictor_sortData_desktop_'.$examinationName]);
			$sortKey  = $sortData[0];
			$sortOrder= $sortData[1];
			}
		}
		$defaultCollegePredictorFilters = '';
		$collegePredictorFilters        = '';
		$displayData['mainObj'] = $branchObj->getPageData();
		if($filterStatus=='YES'){
		$arr = $this->_getFilterInputData($filterTypeValueData, $inputData);
		$this->CollegePredictorFilterManager = $this->branchBuilder->getFilterManager();
		$this->CollegePredictorFilterManager->applyFilters($branchObj, $arr);
		}
		
		if($sortStatus=='YES'){
		$this->CollegePredictorSorterManager = $this->branchBuilder->getSorterManager();
		$sortRequestObject      = $this->CollegePredictorSorterManager->getSortRequestObject($sortKey, $sortOrder, $sortKeyValue);
		$this->CollegePredictorSorterManager->applySorter($branchObj, $sortRequestObject);
		$displayData['sorter']         = $sortRequestObject;
		}
		$data = $branchObj->getPageData();
		$displayData['total'] = count($data);
		$count = COLLEGE_PREDICTOR_COUNT_OFFSET;
		if($isPrint) {
			$count = $displayData['total'];
		}
		$result = createPagesForCollegePredictor($data,$start,$count);

		$seoDetails = getSeoDataForPages(1,strtoupper($examNameNew),'',0,$this->settingArray);
		if(is_array($seoDetails)){
                        $displayData['m_meta_title'] = $seoDetails['title'];
                        $displayData['m_meta_description'] = $seoDetails['description'];
                        $displayData['canonicalURL'] = $seoDetails['canonicalURL'];
                }

                $displayData['objAfterAppliedFilter'] = $data;
                $displayData['linkUrl'] = $linkUrl;
                if($linkUrl) {
                	$displayData['rank'] = $inputData['rank'];
                }       
		$displayData['branchInformation'] = $result;
		$displayData['start'] = $start;
		$displayData['count'] = $count;
		$displayData['print'] = $isPrint;	
		$displayData['validateuser'] = $this->userStatus;
		$displayData['examName'] = $examName;
		$displayData['examYear'] = $examYear;
		$displayData['inputData'] = $inputData;
		// Hard code for engineering
		$subcatId = "56";
		$displayData['tab_required_course_page'] = checkIfCourseTabRequired($subcatId);
		$displayData['subcat_id_course_page'] = $subcatId;
		$displayData['course_pages_tabselected'] = 'CollegePredictor';
		$configData = $this->getConfigData($examNameNew,'rank');
		foreach($configData as $key=>$value){
			$displayData[$key]  = $value;
		}
		
		if($callType == 'Ajax' || $isPrint) {
			if($isPrint)
			$this->makeLogEntry($inputData,$start,$displayData['total'],'Print');
			else 
			$this->makeLogEntry($inputData,$start,$displayData['total'],'');
		}

	
		$displayData['showCustomizedGNB'] = true;
		$displayData['callType'] = $callType;

			//conversion tracking
			$displayData['trackingPageKeyId']=176;
			$displayData['invitetrackingPageKeyId']=182;
			$displayData['emailtrackingPageKeyId']=179;
			$displayData['loadtrackingPageKeyId']=452;
			$displayData['comparetrackingPageKeyId'] = 505;

		//$this->collegepredictordownloadbrochure = $this->load->library('CP/CollegePredictorDownloadBrochure');	
		//$displayData['checkForDownloadBrochure']= $this->collegepredictordownloadbrochure->checkBrochureOnCourseInstituteAndUniversity($displayData['mainObj']);	


		$beaconTrackData = $this->collegepredictorlibrary->prepareBeaconTrackData('collegePredictor',$examName,'college');
		$gtmParams = $this->collegepredictorlibrary->getGTMArray($beaconTrackData['pageIdentifier'], $inputData);		
		$displayData['gtmParams'] = $gtmParams;
		if($callType == 'Ajax') {
			$displayData['defaultView'] = 0;
			if($start) {
				echo $this->load->view('collegePredInner',$displayData);
			}else {
				$displayData['text'] = $this->getSearchText($inputData,$displayData['tabType'],$displayData['total']);
				$this->load->view('searchResultsCP',$displayData);
			}
		}else {
			$displayData['text'] = $this->getSearchText($inputData,$displayData['tabType'],$displayData['total'],$defaultView);
			$displayData['defaultView'] = $defaultView;
			//Code added by Ankur for GA Custom variable tracking
			$displayData['subcatNameForGATracking'] = 'B.E./B.Tech';
			$displayData['pageTypeForGATracking'] = 'COLLEGE_PREDICTOR_DETAIL';			
			$displayData['beaconTrackData'] = $beaconTrackData;
			$this->collegepredictorlibrary->getCollegePredictorTrackingKeys($displayData, $examNameNew, 'rank','desktop');
			$this->load->view('collegePredictorMain',$displayData);
		}
						
	}

	private function _getFilterInputData($filterTypeValueData, $inputData){
		$arr = array();
		if(!empty($filterTypeValueData)){
				foreach($filterTypeValueData as $key=>$filterTypeValue){
					$filterTypeValueArr = explode('::::',$filterTypeValue);
					$filterType = $filterTypeValueArr[0];
					$filterValueArr = explode(':',$filterTypeValueArr[1]);
					if($filterType=='locationFilter'){
						if($inputData['rankType']=='Other' || $inputData['rankType']=='KCETGeneral'){
							$arr[$filterType]['state'] = $filterValueArr;
						}else{
							$arr[$filterType]['city']  = $filterValueArr;
						}
					}else if($filterType=='collegeFilter'){
						$formatedData = getFormatedData($filterValueArr);
						$collegeGroupName  = $formatedData['collegeGroupName'];
						$instIdNotHavingGroupName = $formatedData['collegeId'];
			
						$instIdsHavingGroupName = $this->cpmodel->getInstituteIdForGroupName($collegeGroupName,$inputData['examName']);
						$filterValueArr  = array_merge($instIdNotHavingGroupName,$instIdsHavingGroupName);
						$arr[$filterType]= $filterValueArr;
					}else{
						$arr[$filterType]= $filterValueArr;
					}
				}
			}
		return $arr;
	}
		
	function loadBranchTab($examName = 'JEE-Mains', $folderName = 'b-tech') {
		$url = SHIKSHA_HOME.'/'.$folderName.'/resources/'.$examName.'-college-predictor';
        redirect($url, 'location', 301);
		exit;
		$this->collegepredictorredirectionrules = $this->load->library('CollegePredictorRedirectionRules');
		$this->init($examName);
        $this->collegepredictorredirectionrules->redirectionRule($examName, $folderName, 'branch');
		$callType = isset($_REQUEST['callType'])?$_REQUEST['callType']:'';
		$isPrint = (isset($_REQUEST['export']) && $_REQUEST['export'] == 'print')?true:false;
		$examNameNew = strtoupper($examName);
		if(!isset($this->settingArray[$examNameNew]) || empty($this->settingArray[$examNameNew]) || $examNameNew=='MAHCET')
		{
			show_404();
		}
		$sortStatus = $this->settingArray[$examNameNew]['showSorting'];
		$filterStatus = $this->settingArray[$examNameNew]['showFilters'];
		$examinationName = $this->settingArray[$examNameNew]['examName'];
		$examYear = $this->settingArray[$examNameNew]['examYear'];
		
		if($sortStatus=='YES'){
			$sortKey                = (isset($_REQUEST['sort_keyname_'.$examinationName]) && $_REQUEST['sort_keyname_'.$examinationName] != '') ? $_REQUEST['sort_keyname_'.$examinationName] : "rank";
			$sortOrder              = (isset($_REQUEST['sort_order_'.$examinationName]) && $_REQUEST['sort_order_'.$examinationName] != '') ? $_REQUEST['sort_order_'.$examinationName] : "asc";
		}
		if($filterStatus=='YES'){
			$filterTypeValueData    = (isset($_REQUEST['filterTypeValue_'.$examinationName]) && $_REQUEST['filterTypeValue_'.$examinationName] != '') ? $_REQUEST['filterTypeValue_'.$examinationName] : "";
		}
		$type    = (isset($_REQUEST['type']) && $_REQUEST['type'] != '') ? $_REQUEST['type'] : "";

		if($isPrint && isset($_GET['collegepredictor_showFilters_'.$examinationName])){
			setcookie('collegepredictor_showFilters_'.$examinationName,$_GET['collegepredictor_showFilters_'.$examinationName],time() + 2592000,'/',COOKIEDOMAIN);
		}
		$linkUrl = false;
		$defaultView = true;
		$start = 0 ;
		$cookieData = array();
		if($callType == 'Ajax' || $isPrint ) {
			$inputData = array();
			$inputData = getInputFromPostData($_REQUEST,'branch',$examName);
			$inputData['tabType'] = 'branch';
			$inputData['tabName'] = 'branch';
			$cookieData = $inputData;
			if(isset($_REQUEST['start']))
				$start = $_REQUEST['start'];
			if($start == 0 || !$isPrint){
				setcookie('collegepredictor_search_desktop_'.$examinationName,json_encode($cookieData),time() + 2592000,'/',COOKIEDOMAIN);
				if($filterStatus=='YES'){
					setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,json_encode($filterTypeValueData),time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,'',time() - 3600,'/',COOKIEDOMAIN);
				}
				if($sortStatus=='YES'){
					setcookie('collegepredictor_sortData_desktop_'.$examinationName,$sortKey.'::::'.$sortOrder,time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_sortData_desktop_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
				}	
			}
			$defaultView = false;
		}else {
			$inputData = $this->settingArray['defaultTabInfo']['DESKTOP'][$examNameNew];
			if(!empty($_GET) && !empty($_GET['tabType']) && ($_GET['tabType'] == 'branch')) {
				$defaultView = false;
				$inputData = array();
				$linkUrl = true;
				$inputData = getInputFromPostData($_GET,'branch',$examName);

				setcookie('collegepredictor_search_desktop_'.$examinationName,json_encode($inputData),time() + 86400,'/',COOKIEDOMAIN);
				if($filterStatus=='YES'){
					setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,json_encode($_GET['filterTypeValue_'.$examinationName]),time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
				}
				if($sortStatus=='YES'){
					setcookie('collegepredictor_sortData_desktop_'.$examinationName,$_GET['collegepredictor_sortData_desktop_'.$examinationName],time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_sortData_desktop_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
				}
				if($filterStatus=='YES'){
					setcookie('collegepredictor_showFilters_'.$examinationName,$_GET['collegepredictor_showFilters_'.$examinationName],time() + 2592000,'/',COOKIEDOMAIN);
					setcookie('COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName,$_GET['COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName],time() + 2592000,'/',COOKIEDOMAIN);
				}else{
					setcookie('collegepredictor_showFilters_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
					setcookie('COLLEGE_PREDICTOR_TOP_FILTER_'.$examinationName,'',time() -3600,'/',COOKIEDOMAIN);
				}
				//setcookie('COLLEGE_PREDICTOR_TOTAL_RESULT_'.$examinationName,$_GET['COLLEGE_PREDICTOR_TOTAL_RESULT_'.$examinationName],time() + 2592000,'/',COOKIEDOMAIN);
				
				$url = $_SERVER['SCRIPT_URI'];
				header("Location: $url",TRUE,301);
				exit;
			}else {
				if(isset($_COOKIE['collegepredictor_search_desktop_'.$examinationName])) {
					//_p($_REQUEST);
					$data = json_decode($_COOKIE['collegepredictor_search_desktop_'.$examinationName],true);
					if($data['tabType'] != 'branch') {
						if(!empty($data)) {
							if(!empty($inputData['branchAcronym'])) {
								$data['tabType'] = 'branch';
								setcookie('collegepredictor_search_desktop_'.$examinationName,json_encode($data),time() + 86400,'/',COOKIEDOMAIN);
							}
						}
					}else {
						$defaultView = false;
						$inputData = $data;
					}
				}else {
					if(!empty($inputData['branchAcronym'])) {
						$inputData['tabType'] = 'branch';
						setcookie('collegepredictor_search_desktop_'.$examinationName,json_encode($inputData),time() + 86400,'/',COOKIEDOMAIN);
					}
				}
								
			}
			
		}
		
		$displayData = array();
		$displayData = $this->commonData;
		$displayData['tabType'] = 'branch';
		$displayData['tab'] = '3';
		$displayData['type'] = $type;
		$displayData['courseRepository'] = $this->courseRepository;
		
		$displayData['instituteRepository'] = $this->instituteRepository;  // this is used only for compare tool to get headerimage of institue
		
		$branchObj = $this->branchRepository->findMultiple($inputData);	
		$branchObjData = $branchObj->getPageData();
		setcookie('COLLEGE_PREDICTOR_TOTAL_RESULT_'.$examinationName,count($branchObjData),time() + 2592000,'/',COOKIEDOMAIN);
		$i=0;$j=0;
		if($filterStatus=='YES'){
		foreach($branchObjData as $key=>$value){
			$md5Data = md5($value->getInstituteId().$value->getCollegeName().$value->getCityName().$value->getStateName());
			if(!in_array($md5Data,$tmp['str'])){
				$displayData['institutesFilter'][$i]['id'] =  $value->getInstituteId();
				$displayData['institutesFilter'][$i]['collegeName'] =  $value->getCollegeName();
				$displayData['institutesFilter'][$i]['cityName'] =  $value->getCityName();
				$displayData['institutesFilter'][$i]['stateName'] =  $value->getStateName();
				$tmp['str'][] = md5($value->getInstituteId().$value->getCollegeName().$value->getCityName().$value->getStateName());
				$i++;
			}
			
			if(!in_array($value->getCollegeGroupName(),$temp) && $value->getCollegeGroupName()!=''){
				$displayData['instituteGroupsFilter'][$j]['collegeGroupName'] =  $value->getCollegeGroupName();
				$temp[] = $value->getCollegeGroupName();
				$j++;
			}
		
		}
		}
		$displayData['mainObj'] = $branchObj->getPageData();
		if(!$defaultView && $type=='' && $filterStatus=='YES'){
			$filterTypeValueData = json_decode($_COOKIE['collegepredictor_filterTypeValueData_desktop_'.$examinationName],true);
			$filters = $this->_getFilters($filterTypeValueData,$inputData,$branchObj);
			$displayData['collegePredictorFilters'] = $filters['collegePredictorFilters'];
			$displayData['defaultCollegePredictorFilters'] = $filters['defaultCollegePredictorFilters'];
			$displayData['filterInputData'] = $filters['filterInputData'];
		}
		if($sortStatus=='YES'){
			if($_REQUEST['sort_order_'.$examinationName]=='' && $_REQUEST['sort_keyname_'.$examinationName]=='' && $type!='search'  && $_COOKIE['collegepredictor_sortData_desktop_'.$examinationName]!='::::'  && !empty($_COOKIE['collegepredictor_sortData_desktop_'.$examinationName])){
				$sortData = explode('::::',$_COOKIE['collegepredictor_sortData_desktop_'.$examinationName]);
			$sortKey  = $sortData[0];
			$sortOrder= $sortData[1];
		}
		}
		if($filterStatus=='YES'){
		$arr = $this->_getFilterInputData($filterTypeValueData, $inputData);
		$this->CollegePredictorFilterManager = $this->branchBuilder->getFilterManager();
		$this->CollegePredictorFilterManager->applyFilters($branchObj, $arr);
		$displayData['filterTypeValueData'] = $filterTypeValueData;
		}
		if($sortStatus=='YES'){
		$this->CollegePredictorSorterManager = $this->branchBuilder->getSorterManager();	
		$sortRequestObject 	= $this->CollegePredictorSorterManager->getSortRequestObject($sortKey, $sortOrder, $sortKeyValue);
		$this->CollegePredictorSorterManager->applySorter($branchObj, $sortRequestObject);
		$displayData['sorter']         = $sortRequestObject;
		}
		$data = $branchObj->getPageData();
		$displayData['total'] = count($data);
		$count = COLLEGE_PREDICTOR_COUNT_OFFSET;
		if($isPrint) {
			$count = $displayData['total'];
		}
		$result = createPagesForCollegePredictor($data,$start,$count);
		$seoData['seo_title_exam_name'] = $this->settingArray[$examNameNew]['seo_title_exam_name'];
		$seoData['seo_description_exam_name'] = $this->settingArray[$examNameNew]['seo_description_exam_name'];
		$seoData['examName'] = $examName;
		$seoDetails = getSeoDataForPages(3,strtoupper($examNameNew),'',0,$this->settingArray);
                if(is_array($seoDetails)){
                        $displayData['m_meta_title'] = $seoDetails['title'];
                        $displayData['m_meta_description'] = $seoDetails['description'];
                        $displayData['canonicalURL'] = $seoDetails['canonicalURL'];
                }

	    $displayData['objAfterAppliedFilter'] = $data;
        $displayData['linkUrl'] = $linkUrl;
        if($linkUrl) {
        	$displayData['branches'] = $inputData['branchAcronym'];
        	$displayData['rank'] = $inputData['rank'];
        }        
                
		$displayData['branchInformation'] = $result;
		$displayData['start'] = $start;
		$displayData['count'] = $count;
		
		$displayData['print'] = $isPrint;
		$displayData['validateuser'] = $this->userStatus;
		$displayData['examName'] = $examName;
		$displayData['examYear'] = $examYear;
		$displayData['inputData'] = $inputData;
		
		// Hard code for engineering
		$subcatId = "56";
		$displayData['tab_required_course_page'] = checkIfCourseTabRequired($subcatId);
		$displayData['subcat_id_course_page'] = $subcatId;
		$displayData['course_pages_tabselected'] = 'CollegePredictor';
		
		$displayData['showCustomizedGNB'] = true;
		
		$configData = $this->getConfigData($examNameNew,'branch');
		foreach($configData as $key=>$value){
			$displayData[$key]  = $value;
		}
		
		
		if($callType == 'Ajax' || $isPrint) {
			if($isPrint)
			$this->makeLogEntry($inputData,$start,$displayData['total'],'Print');
			else 
			$this->makeLogEntry($inputData,$start,$displayData['total'],'');
		}
		
		
		
 		 //conversion tracking
                      $displayData['trackingPageKeyId']=177;
                      $displayData['invitetrackingPageKeyId']=183;
			$displayData['emailtrackingPageKeyId']=180;
			$displayData['loadtrackingPageKeyId']=454;
			$displayData['comparetrackingPageKeyId'] = 507;
			//$this->collegepredictordownloadbrochure = $this->load->library('CP/CollegePredictorDownloadBrochure');
            //$displayData['checkForDownloadBrochure']= $this->collegepredictordownloadbrochure->checkBrochureOnCourseInstituteAndUniversity($displayData['mainObj']);
	    $beaconTrackData = $this->collegepredictorlibrary->prepareBeaconTrackData('collegePredictor',$examName,'Branch');
        $gtmParams = $this->collegepredictorlibrary->getGTMArray($beaconTrackData['pageIdentifier'], $inputData);		
		$displayData['gtmParams'] = $gtmParams;
		
		if($callType == 'Ajax') {
			$displayData['defaultView'] = 0;
			if($start) {
				echo $this->load->view('collegePredInner',$displayData);
			}else {
				$displayData['text'] = $this->getSearchText($inputData,$displayData['tabType'],$displayData['total']);
				$this->load->view('searchResultsCP',$displayData);
			}
		}else {
			$displayData['text'] = $this->getSearchText($inputData,$displayData['tabType'],$displayData['total'],$defaultView);			
			$displayData['defaultView'] = $defaultView;
                        //Code added by Ankur for GA Custom variable tracking
                        $displayData['subcatNameForGATracking'] = 'B.E./B.Tech';
                        $displayData['pageTypeForGATracking'] = 'COLLEGE_PREDICTOR_DETAIL';

			//below code used for beacon tracking
            $displayData['beaconTrackData'] = $beaconTrackData;
            $this->collegepredictorlibrary->getCollegePredictorTrackingKeys($displayData, $examNameNew, 'branch','desktop');
			$this->load->view('collegePredictorMain',$displayData);
		}
		
	}
	private function _getFilters($filterTypeValueData, $inputData ,$branchObj){
		$this->init();
		if($inputData['rankType']=='Other' || $inputData['rankType']=='KCETGeneral'){
			$defaultArr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('state'=>''));
		}else{
			$defaultArr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('city'=>''));
		}

		if(!empty($filterTypeValueData)){
			foreach($filterTypeValueData as $key=>$filterTypeValue){
				$filterTypeValueArr = explode('::::',$filterTypeValue);
				$filterType = $filterTypeValueArr[0];
				$filterValueArr = explode(':',$filterTypeValueArr[1]);
				if($filterType=='locationFilter'){
					if($inputData['rankType']=='Other' || $inputData['rankType']=='KCETGeneral'){
						$arr[$filterType]['state'] = $filterValueArr;
					}else{
						$arr[$filterType]['city']  = $filterValueArr;
					}
				}else{
					$arr[$filterType]= $filterValueArr;
				}
			}
		}else{
			if($inputData['rankType']=='Other' || $inputData['rankType']=='KCETGeneral'){
				$arr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('state'=>''));
			}else{
				$arr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('city'=>''));
			}
		}


		$this->CollegePredictorFilterManager = $this->branchBuilder->getFilterManager();
		$defaultCollegePredictorFilters    = $this->CollegePredictorFilterManager->getFilters($branchObj, $defaultArr,$inputData);

		$collegePredictorFilters    = $this->CollegePredictorFilterManager->getFilters($branchObj, $arr,$inputData);
		$displayData['collegePredictorFilters'] = $collegePredictorFilters;
		$displayData['defaultCollegePredictorFilters'] = $defaultCollegePredictorFilters;
		$displayData['filterInputData'] = $arr;
		return $displayData;
	}
	
	function getInstituteData($examName='JEE-Mains'){
		$this->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$instituteData = $this->cpmodel->getInstiuteData($examName);
		return $instituteData;
	}

	function getInstituteGroups($examName='JEE-Mains'){
		$this->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$instituteGroupData = $this->cpmodel->getInstiuteGroups($examName);
		return $instituteGroupData;
	}
	
	
	function getStates(){
		$this->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$stateData = $this->cpmodel->getStates();
		return $stateData;
	}
	
	function getBranches($examName='JEE-Mains'){
		$this->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$branchData = $this->cpmodel->getBranches($examName);
		return $branchData;
	}

	
	function getSearchText($data,$tabType,$count,$defaultView = false) {

		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$this->settingArray = $this->config->item('settings','CollegePredictorConfig');
		$examNameNew = strtoupper($data['examName']);
		$examName = $this->settingArray[$examNameNew]['examNameForResultText'];

		$text = '';
		if($count ==1)
			$options = 'Option';
		else
			$options ='Options';
		if($data['round']=='all'){
			$round = 'All';
                }else{
			$round = $data['round'];
		}
		
		if(!$defaultView) {
			if($count ==1)
				$options = 'Result Found';
			else
				$options = 'Results Found';
			$rankType = $data['rankType'];
			$quotaText = '';
			if($data['rankType']=='Other'){
				$rankType = 'AIR';	
			}else{
				if(!empty($data['stateName'])){
					$quotaText = " availing  state quota for <span>".$data['stateName'].'</span>';
				}
			}
			// _p($data); die;
			if($examNameNew == 'MAHCET'){
				$text .= '<span>'.$count.' '.$options.': </span>'.' '.$examName.' Rank <span>'. $data['rank']."</span>, Rank Type <span>" . $rankType. '</span>'.$quotaText.', Category <span>'. $data['categoryName'] .'</span>';
			/*if($tabType == 'rank' || $tabType == 'branch') {
				}else {
					$text .= '<span>'.$count. '</span> '.$options.' ::::Category <span>'.$data['categoryName'].'</span>'.$quotaText;
				}*/
			}
			if($examNameNew == 'JEE-MAINS'){
				$text .="JEE-Main Rank <b>".$data['rank']."</b>, <b>AIR</b>, <b>".$data['categoryName']."</b>";
			}
			if($examNameNew == 'KCET'){
				$categoryData = $this->settingArray[$examNameNew]['categoryData'];
					// $text .= '<span>'.$count. '</span> '.$options.' ::::<span>' . $examName . '</span> Rank <span>' . $data['rank']."</span>, Category <span>". $categoryData[$data['categoryName']] .'</span>';
					$text .= '<span>'.$count.' '.$options.': </span>'.' '.$examName.' Rank <span>'. $data['rank']."</span>, ".' Category <span>'. $data['categoryName'] .'</span>';
				/*if($tabType == 'rank' || $tabType == 'branch') {
				}else {
					$text .= '<span>'.$count. '</span> '.$options.' ::::Available Branches for Category <span>'.$categoryData[$data['categoryName']].'</span>';
				}*/
			}
			if($examNameNew == 'COMEDK' || $examNameNew == 'KEAM' || $examNameNew == 'WBJEE' || $examName == 'MPPET' || $examName == 'CGPET' || $examName == 'PTU' || $examName == 'UPSEE' || $examName == 'MHCET' || $examName == 'HSTES' || $examName == 'AP-EAMCET' || $examName == 'TS-EAMCET' || $examName == 'OJEE'  || $examName == 'GGSIPU' || $examName == 'GUJCET' || $examName == 'NCHMCT' || $examName == 'NIFT' || $examName =='CLAT'){
					$text .= '<span>'.$count. ' '.$options.': </span> '.$examName.' Rank <span>'.$data['rank']. '</span>';
				/*if($tabType == 'rank' || $tabType == 'branch') {
					
				}else {
					$text .= '<span>'.$count. '</span> '.$options.' ::::Available Branches for your selection';
	 			}*/
			}
			if($examNameNew == 'TNEA' || $examName == 'BITSAT' ){
					$text .= '<span>'.$count. ' '.$options.': </span> '.$examName.' Score <span>'.$data['rank']. '</span>';
				/*if($tabType == 'rank' || $tabType == 'branch') {
					$text .= '<span>'.$count. '</span> '.$options.' ::::<span>' . $examName . '</span> Score <span>' . $data['rank'].
							 "</span>";
				}else {
					$text .= '<span>'.$count. '</span> '.$options.' ::::Available Branches for your selection';
	 			}*/
			}
		}else {
			if($examNameNew == 'TNEA' ){
				$text = "Closing Score of <span>Top Engineering Colleges</span>";
			} else {
				$resultTitle = 'Engineering';
				$settings = $this->config->item('settings','CollegePredictorConfig');
				if($settings['CPEXAMS'][$examNameNew]['resultTitle']) {
					$resultTitle = $settings['CPEXAMS'][$examNameNew]['resultTitle'];
				}
				$text = "Closing Rank of <span>Top ".$resultTitle." Colleges</span>"; 
		}
		}	
		// _p($text); die;
		return $text;
	} 
	
	function loadInstitutePage($collegeName,$instituteAndExamName = '', $folderName){
			$parameterArray = explode('-',$instituteAndExamName);
			$count = count($parameterArray);
			$instituteId = $parameterArray[$count-1];
			$instituteIds = array($instituteId);
			$institutesOptions = '';
			array_pop($parameterArray);
			$examName = implode('-', $parameterArray);
			$url = SHIKSHA_HOME.'/'.$folderName.'/resources/'.$examName.'-college-predictor';
            redirect($url, 'location', 301);
            exit;

	}

	function getRankOverlayData($examName){
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$this->settingArray = $this->config->item('settings','CollegePredictorConfig');
		
		$data['examName'] = $examName;
		$this->load->view('listing/national/widgets/listingsOverlay');
		$examinationName = strtoupper($examName);
		$overDetails = $this->settingArray[$examinationName]['overDetails'];
		$data['overDetails'] = $overDetails;
		$this->load->view('rankPredictorHTML',$data);
	}
	
	function makeLogEntry($parameters,$start=0,$totalResults=0,$activity = ''){
		$this->init();
		$data = array();
		$this->load->model('CP/cpmodel');
		//Fetch the Activity
		if($start==0){
			$data['activityType'] = "Search";
		}
		else if($start>0){
			$data['activityType'] = "LoadMore";
		}
		
		if(!empty($activity)) 
			$data['activityType'] = $activity;
			
		
		//Fetch the User Id
			if(isset($this->userStatus) && is_array($this->userStatus)){
			$signedInUser = $this->userStatus;
			$data['userId'] = $signedInUser[0]['userid'];
		}
		else{
			$data['userId'] = 0;
		}
		$parameters['start'] = $start;
		$data['source'] = "Desktop";
		$data['url'] = $_SERVER['HTTP_REFERER'];
		$data['value'] = json_encode($parameters);
		$data['resultsFound'] = $totalResults;
		$this->cpmodel->insertActivityLog($data);
	}
	
	function inviteFriendsOverlay(){
		$this->load->view('inviteFriendsHtml');
	}

	function sendInvitationMailer($examName) {
		$this->init();
		$examNameNew = strtoupper($examName);
		$predictorText = $this->settingArray[$examNameNew]['mailerSubject'];
		//$predictorText = 'JEE Mains 2014 College Predictor';
		$emailIds = $this->input->post('emailIds');
		if((!is_array($this->userStatus)) && ($this->userStatus == "false")){
			$userId = '';
			echo '1';
		}else{
			$userId = $this->userStatus[0]['userid'];
			$firstName = $this->userStatus[0]['firstname'];
			$contentArray['firstName'] = $firstName;
			$inputArray = array();
			$inputArray['emailIds'] = $emailIds;
			$this->makeLogEntry($inputArray,0,0,'Invite');
			$emailIds = explode(',',$emailIds);
			
			foreach($emailIds as $key=>$email){
				$email = trim($email);
				$contentArray['text'] = $predictorText;
				$contentArray['tab1Link'] = '/jee-mains-college-predictor';
				$contentArray['tab2Link'] ='/jee-mains-cut-off-predictor';
				$contentArray['tab3Link'] = '/jee-mains-branch-predictor';
				Modules::run('systemMailer/SystemMailer/inviteFriendsCPMail',$email,$contentArray);
			}
		}
	}

	function saveFeedback(){
		$this->init();
		$this->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$data['deviceType'] = "Desktop";
		$data['rating'] = $this->input->post('rating');
		if(strlen($this->input->post('pageName')) > 0) {
			$data['pageName']= $this->input->post('pageName');
		}
		if(isset($this->userStatus) && is_array($this->userStatus)){
			$signedInUser = $this->userStatus;
			$data['userId'] = $signedInUser[0]['userid'];
		}
		else{
			$data['userId'] = 0;
		}
		$data['tabUrl'] = $_SERVER['HTTP_REFERER'];
		$message = isset($_POST['message'])?$this->input->post('message'):'';
		$feedbackId = isset($_POST['feedbackId'])?$this->input->post('feedbackId'):'';
		if($feedbackId !='') $data['feedbackId']=$feedbackId;
		if($message !='') $data['message']=$message;
		$result = $this->cpmodel->saveFeedbackData($data);
		echo $result;
	}
	
	function sendEmail($examName) {
		$this->init();
		$emailArray = array();
		$contentArray = array();	
		$tabType  = $_REQUEST['tabType'];
		$urlLink = getUrlFromRequest($_REQUEST);
		$urlLink = $_SERVER['HTTP_REFERER'].'?'.$urlLink;
		if((!is_array($this->userStatus)) && ($this->userStatus == "false")){
			$userId = '';
			echo '1';
		}else{
			$userId = $this->userStatus[0]['userid'];
			$emailStr = $this->userStatus[0]['cookiestr'];
			$emailArray = explode('|',$emailStr);
			$email = $emailArray[0];
			$firstName = $this->userStatus[0]['firstname'];
			//$predictorText = 'JEE Mains 2014 College Predictor';
			$examNameNew = strtoupper($examName);
			$predictorText = $this->settingArray[$examNameNew]['mailerSubject'];
			$contentArray['name'] = $firstName;
			$contentArray['toEmail'] = $email;
			$contentArray['link'] = $urlLink;
			$contentArray['text'] = $predictorText;
			
			$this->makeLogEntry($contentArray,0,0,'Email');
			Modules::run('systemMailer/SystemMailer/collegePredictorMail',$email,$contentArray);
		}
	}
		
	function changeFilters($examName = 'JEE-Mains'){
		$examName = isset($_POST['examName'])?$this->input->post('examName'):$examName;
		$this->init($examName);
		$examNameNew = strtoupper($examName);
		$examinationName = $this->settingArray[$examNameNew]['examName'];
		$sortKey                = (isset($_POST['sort_keyname_'.$examinationName]) && $_POST['sort_keyname_'.$examinationName] != '') ? $_POST['sort_keyname_'.$examinationName] : "rank";
		$sortOrder              = (isset($_POST['sort_order_'.$examinationName]) && $_POST['sort_order_'.$examinationName] != '') ? $_POST['sort_order_'.$examinationName] : "desc";
		$filterTypeValueData    = (isset($_POST['filterTypeValue_'.$examinationName]) && $_POST['filterTypeValue_'.$examinationName] != '') ? $_POST['filterTypeValue_'.$examinationName] : "";
		$linkUrl = false;
		$defaultView = false;
		$start = 0 ;
		$cookieData = array();
		$tabType = $this->input->post('tabType');
		$displayData = array();
		//$displayData = $this->commonData;
		
		$inputData = array();
		$inputData = getInputFromPostData($_POST,$tabType,$examName);
		if($tabType=='college'){
			$collegeNames = $_REQUEST['college'];
			foreach ($collegeNames as $index => $value ) {
				$institutesOptions[]  =  $value;
			}
			$formatedData = getFormatedData($institutesOptions);
			$collegeGroupName  = $formatedData['collegeGroupName'];
			$instIdNotHavingGroupName = $formatedData['collegeId'];
		
			$instIdsHavingGroupName = $this->cpmodel->getInstituteIdForGroupName($collegeGroupName,$inputData['examName']);
			$inputData['instituteId']  = array_merge($instIdNotHavingGroupName,$instIdsHavingGroupName);
			$displayData['tab'] = '2';
		}else if($tabType=='branch'){
			$displayData['tab'] = '3';
		}else{
			$displayData['tab'] = '1';
		}
		
		$inputData['tabName'] = $tabType;

		//$cookieData = $inputData;
		//setcookie('collegepredictor_search_desktop',json_encode($cookieData),time() + 2592000,'/',COOKIEDOMAIN);
		
		$branchObj = $this->branchRepository->findMultiple($inputData);
		$branchObjData = $branchObj->getPageData();
		$i=0;$j=0;
		foreach($branchObjData as $key=>$value){
			$md5Data = md5($value->getInstituteId().$value->getCollegeName().$value->getCityName().$value->getStateName());
			if(!in_array($md5Data,$tmp['str'])){
				$displayData['institutesFilter'][$i]['id'] =  $value->getInstituteId();
				$displayData['institutesFilter'][$i]['collegeName'] =  $value->getCollegeName();
				$displayData['institutesFilter'][$i]['cityName'] =  $value->getCityName();
				$displayData['institutesFilter'][$i]['stateName'] =  $value->getStateName();
				$tmp['str'][] = md5($value->getInstituteId().$value->getCollegeName().$value->getCityName().$value->getStateName());
				$i++;
			}
			
			if(!in_array($value->getCollegeGroupName(),$temp) && $value->getCollegeGroupName()!=''){
				$displayData['instituteGroupsFilter'][$j]['collegeGroupName'] =  $value->getCollegeGroupName();
				$temp[] = $value->getCollegeGroupName();
				$j++;
			}
		
		}
		
		$displayData['branchInformation'] = $branchObj->getPageData();

		if(empty($displayData['branchInformation'])){
			setcookie('collegepredictor_showFilters_'.$examinationName,'notdisplay',time() + 2592000,'/',COOKIEDOMAIN);
			$this->load->view('sideFiltersCP',$displayData);
		}

		$defaultCollegePredictorFilters = '';
		$collegePredictorFilters        = '';
		$arr = array();
		if($inputData['rankType']=='Other' || $inputData['rankType'] == 'KCETGeneral'){
			$defaultArr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('state'=>''));
		}else{
			$defaultArr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('city'=>''));
		}
		$filterInputData = array();
		if(!empty($filterTypeValueData)){
			foreach($filterTypeValueData as $key=>$filterTypeValue){
				$filterTypeValueArr = explode('::::',$filterTypeValue);
				$filterType = $filterTypeValueArr[0];
				$filterValueArr = explode(':',$filterTypeValueArr[1]);
				if($filterType=='locationFilter'){
					if($inputData['rankType']=='Other' || $inputData['rankType']=='KCETGeneral'){
						$arr[$filterType]['state'] = $filterValueArr;
						$filterInputData[$filterType]['state'] = $filterValueArr;

					}else{
						$arr[$filterType]['city']  = $filterValueArr;
						$filterInputData[$filterType]['city'] = $filterValueArr;
					}
				}else if($filterType=='collegeFilter'){
						$filterInputData[$filterType] = $filterValueArr;
						$formatedData = getFormatedData($filterValueArr);
						$collegeGroupName  = $formatedData['collegeGroupName'];
						$instIdNotHavingGroupName = $formatedData['collegeId'];
			
						$instIdsHavingGroupName = $this->cpmodel->getInstituteIdForGroupName($collegeGroupName,$inputData['examName']);
						$filterValueArr  = array_merge($instIdNotHavingGroupName,$instIdsHavingGroupName);
						$arr[$filterType]= $filterValueArr;
				}else{
					$filterInputData[$filterType] = $filterValueArr;
					$arr[$filterType]= $filterValueArr;
				}
			}
		}else{
			if($inputData['rankType']=='Other' || $inputData['rankType']=='KCETGeneral'){
				$arr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('state'=>''));
			}else{
				$arr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('city'=>''));
			}
		}
		$this->CollegePredictorFilterManager = $this->branchBuilder->getFilterManager();
		$defaultCollegePredictorFilters    = $this->CollegePredictorFilterManager->getFilters($branchObj, $defaultArr, $inputData);
		$collegePredictorFilters    = $this->CollegePredictorFilterManager->getFilters($branchObj, $arr, $inputData);

		$displayData['collegePredictorFilters'] = $collegePredictorFilters;
		$displayData['defaultCollegePredictorFilters'] = $defaultCollegePredictorFilters;

		if((!empty($defaultCollegePredictorFilters['round']) && 
		count($defaultCollegePredictorFilters['round'])>1 && $tabType!='round' ) || (!empty($defaultCollegePredictorFilters['branch']) && 
		count($defaultCollegePredictorFilters['branch'])>1 && 
		$tabType!='branch')  || 
		(!empty($defaultCollegePredictorFilters['location']) && 
		count($defaultCollegePredictorFilters['location'])>1 && 
		$tabType!='location')|| 
		(!empty($defaultCollegePredictorFilters['college']) && 
		count($defaultCollegePredictorFilters['college'])>1 && 
		$tabType!='college')){
		setcookie('collegepredictor_showFilters_'.$examinationName,'display',time() 
		+ 2592000,'/',COOKIEDOMAIN);
                 }else{
setcookie('collegepredictor_showFilters_'.$examinationName,'notdisplay',time() 
+ 2592000,'/',COOKIEDOMAIN);
                 }
		$displayData['filterInputData'] = $filterInputData;
		$displayData['inputData'] = $inputData;
		$displayData['defaultView'] = $defaultView;

		$this->load->view('V2/sideFiltersCP',$displayData);
	}

	function getConfigData($examName,$tabName){
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$this->settingArray = $this->config->item('settings','CollegePredictorConfig');
		$displayData  = array();
		$displayData['course_pages_tabselected'] = 'CollegePredictor';
		$displayData['heading'] = $this->settingArray[$examName]['tab'][$tabName]['heading'];
		$displayData['imageTitle'] = $this->settingArray[$examName]['tab'][$tabName]['imageTitle'];
		$displayData['notice'] = $this->settingArray[$examName]['notice'];
		$displayData['showInvite'] = $this->settingArray[$examName]['showInvite'];
		$displayData['showEmail'] = $this->settingArray[$examName]['showEmail'];
		$displayData['showPrint'] = $this->settingArray[$examName]['showPrint'];
		$displayData['showFeeback'] = $this->settingArray[$examName]['showFeeback'];
		$displayData['showRankType'] = $this->settingArray[$examName]['showRankType'];
		$displayData['noStateDropDown'] = isset($this->settingArray[$examName]['noStateDropDown']) ? $this->settingArray[$examName]['noStateDropDown']:0 ;
		$displayData['categoryData'] = $this->settingArray[$examName]['categoryData'];
		$displayData['roundData'] = $this->settingArray[$examName]['roundData'];
		$displayData['inputType'] = $this->settingArray[$examName]['inputType'];
		$displayData['showRankOverlay'] = $this->settingArray[$examName]['showKnowYourRank'];
		if($displayData['showRankType']=='NO'){
			$displayData['rankType'] = $this->settingArray[$examName]['rankType'];
		}
		$displayData['stateName'] = $this->settingArray[$examName]['stateName'];
		$displayData['sortStatus'] = $this->settingArray[$examName]['showSorting'];
		$displayData['filterStatus'] = $this->settingArray[$examName]['showFilters'];
		$displayData['examinationName'] = $this->settingArray[$examName]['examName'];
		$displayData['tabDetail'] = $this->settingArray['CPEXAMS'][$examName];
		return $displayData;
	}

	function getContentLoderHtml($roundData){
		$numberOfRound = count($roundData);
		if($numberOfRound == 1){
			$data = $this->load->view('V2/contentLoaderTuple1','',true);
		}else{
			$data = $this->load->view('V2/contentLoaderTuple7',array('numberOfRound' => $numberOfRound),true);
		}
		return $data;
	}

	function loadCollegePredictor($examName = 'JEE-Mains', $folderName = 'b-tech', $urlType = 1) {
		ini_set("memory_limit","2048M");
		$this->collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
		
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$this->settingArray = $this->config->item('settings','CollegePredictorConfig');
		$this->JEE_InstituteData = $this->config->item('JEE-MAIN-InstituteData','CollegePredictorConfig');
		$this->load->helper('listingCommon/listingcommon');
		$this->load->config('CollegeReviewForm/collegeReviewConfig');

        $displayData['intervalsDisplayOrder'] = $this->config->item("intervalsDisplayOrder", 'collegeReviewConfig');
        $displayData['aggregateRatingDisplayOrder'] = $this->config->item('aggregateRatingDisplayOrder','collegeReviewConfig');

		if(!isset($this->settingArray[strtoupper($examName)]) || empty($this->settingArray[strtoupper($examName)])) {
			show_404();
		}

		$displayData = $this->collegepredictorlibrary->getDataForCollegePredictor($examName, $folderName,$urlType);
		$callType = isset($_REQUEST['callType'])?$this->input->post('callType'):'';
		// $displayData['total'] = count($displayData['objAfterAppliedFilter']);
		$displayData['nameToIdMappingForAllCollegePredictorPage'] = $this->config->item('nameToIdMappingForAllCollegePredictorPage','CollegePredictorConfig');

		$displayData['text'] = $this->getSearchText($displayData['inputData'],$displayData['tabType'],$displayData['total'],$displayData['defaultView']);

		$this->load->config('common/examGroupConfig');
        $examGroupConfig = $this->config->item('examMapping');
        $originalExamName= $this->settingArray[strtoupper($examName)]['examName'];
        $displayData['eResponseData'] = $examGroupConfig[$originalExamName];

		if($callType == 'Ajax') {
			// Redirect to ctpg if zrp for engineering predictors
			$displayData['branchInformation'] = $displayData['mainObj'];
			// _P($displayData['branchInformation']);die("com");
			$zrpRedirection = $this->collegepredictorlibrary->getCPzrpRedirection($displayData,$folderName);
			if(isset($displayData['type']) && $displayData['type'] =='search' ){
				$this->collegepredictorlibrary->makeDBTrackingEntry($displayData);
			}
			
			if($zrpRedirection['zrpRedirection']){
				echo json_encode($zrpRedirection);
				return;
			}
			$displayData['defaultView'] = 0;
			if($displayData['start']) {
				
				 $returnData['tuples'] = $this->load->view('V2/collegePredictorInner',$displayData, true);
			}else {
				
				$returnData['tuples'] = $this->load->view('V2/searchResultsCP',$displayData, true);
				$returnData['filters'] = $this->load->view('V2/sideFiltersCP',$displayData, true);
			}
			echo json_encode($returnData);
		}else {
			$displayData['breadCrumbData'] = $this->collegepredictorlibrary->getBreadCrumbDataForPredictor($displayData['eResponseData'], $originalExamName, "College Predictor");
			//Code added by Ankur for GA Custom variable tracking
			$displayData['subcatNameForGATracking'] = 'B.E./B.Tech';
			$displayData['pageTypeForGATracking'] = 'COLLEGE_PREDICTOR_DETAIL';

			$beaconTrackData = $this->collegepredictorlibrary->prepareBeaconTrackData('collegePredictor',$examName);

			$displayData['beaconTrackData'] = $beaconTrackData;
		$this->collegepredictorlibrary->getCollegePredictorTrackingKeys($displayData, $examName, 'rank','desktop');

			$this->benchmark->mark('dfp_data_start');
	        $dfpObj   = $this->load->library('common/DFPLib');
	        $dpfParam = array('parentPage'=>'DFP_CollegePredictor','entity_id'=>$examName,'inputRank'=>$displayData['inputData']['rank'],'examName'=>$examName);

	        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
	        $displayData['contentLoaderData'] = $this->getContentLoderHtml($displayData['roundData']);

			$displayData['JEE_InstituteData'] = $this->JEE_InstituteData;
			$this->load->view('V2/collegePredictorMain',$displayData);
		}
	}
}

?>
