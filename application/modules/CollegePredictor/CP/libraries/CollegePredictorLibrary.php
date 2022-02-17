<?php
class CollegePredictorLibrary{
	public $rankTypeMapping = array('Other' => 'AI',
							 'Home' => 'HS',
							 'OtherThanHome' => 'OTH');
	public $enableCachingCP = true;
	function __construct()
	{
                $this->CI = & get_instance();
		$this->cpmodel = $this->CI->load->model('CP/cpmodel');
		$this->cache = $this->CI->load->library('CP/cache/CollegePredictorCache');
	}

	function getCollegePredictorInfoBasedOnCourseId($courseId){
		$examArr  		= $this->cpmodel->getCollegePredictorExamsBasedOnCourseId($courseId);
		$collegePredictorUrl 	= $this->createCollegePredictorUrls($examArr);
		$finalArr 		= $this->formatData($examArr, $collegePredictorUrl);
		return $finalArr;
	}

	function createCollegePredictorUrls($examArr){
		global $collegePredictorExams , $rankPredictorExams;
		$baseUrl = SHIKSHA_HOME."/engineering/resources/";
		$collegePredictorUrl = array();
		foreach($examArr['examName'] as $key=>$value){
			$collegePredictorUrl[$value] = $collegePredictorExams[strtolower($value)]['url'];
			if(strtolower($value) == 'jee-mains'){
				$collegePredictorUrl['rank_predictor_url'] = $rankPredictorExams[strtolower($value)]['url'];
			}
		}
		return $collegePredictorUrl;
	}

	function formatData($examArr, $collegePredictorUrl){
		$data = array();$i=0;
		foreach($examArr['examName'] as $key=>$value){
			if($value == 'JEE-Mains'){
				$data[$i]['rank_predictor_url'] = $collegePredictorUrl['rank_predictor_url'];
			}
			if(!empty($collegePredictorUrl[$value])){
				$data[$i]['name']     = $value;
				$data[$i]['url']      = $collegePredictorUrl[$value];
				$i++;
			}
		}
		return $data;
	}

	function migrateOrDeletePredictorMappingForCourse($oldCourse, $newCourse, $dbHandle){
		if(empty($oldCourse)){
			return 'courseId can\'t be blank.Not able to Migrate / Delete College Predictor Mapping.';
		}

		if($oldCourse <=0){
			return 'courseId is not valid.Not able to Migrate / Delete College Predictor Mapping.';
		}

		$this->collegePredictorModel = $this->CI->load->model('CP/cpmodel');
        $result = $this->collegePredictorModel->checkIfPredictorMappingForCourseExist($oldCourse, $dbHandle);
        if($result == true){
        	if(!empty($newCourse)){
				if($newCourse <=0){
					return 'new course is not valid.Not able to Migrate / Delete College Predictor Mapping.';
				}
			}

			$response = $this->collegePredictorModel->migrateOrDeletePredictorMappingForCourse($oldCourse, $newCourse, $dbHandle);

			if($response){
				return 'College Predictor Mapping is migrated / deleted.';
			}else{
				return 'College Predictor Mapping is not migrated / deleted.';
			}
        }else{
        	return 'College Predictor migration not applicable.';
        }
	}


	function prepareBeaconTrackData($pageIdentifier,$examName,$type){
		$beaconTrackData = array(
			'pageIdentifier' => $pageIdentifier,
			'pageEntityId'   => '0', // No Page entity id for this one
			'extraData' => array(
				'hierarchy' => 	array(
					'streamId'			=> ENGINEERING_STREAM,
			        'substreamId' 		=> 0,
        			'specializationId' 	=> 0
					),
				'examName' 	=> 	strtolower($examName),
				'baseCourseId' => ENGINEERING_COURSE,
				'educationType' => EDUCATION_TYPE,
				'countryId' => 	2
				)
		);
		if(!empty($type)){
			$beaconTrackData['extraData']['type'] = $type;	
		}
		
		return $beaconTrackData;
	}

	function getGTMArray($pageType, $appliedFilters){
		if(is_array($appliedFilters['instituteId'])){
			$instituteId = implode(',', $appliedFilters['instituteId']);
		}else{
			$instituteId = $appliedFilters['instituteId'];
		}
		if(is_array($appliedFilters['branchAcronym'])){
			$branchAcronym = implode(',', $appliedFilters['branchAcronym']);
		}else{
			$branchAcronym = $appliedFilters['branchAcronym'];
		}
		$gtmArray = array(
		    "pageType" => $pageType,
		 	"exam"=> $appliedFilters['examName'],
		 	"inputRank"=>$appliedFilters['rank'],
		    "toolName"=>'CollegePredictor',
		    "instituteId"=>$instituteId,
		    "branch"=>$branchAcronym
		);
	    if($userStatus!='false' && $userStatus[0]['experience']!==""){
	        $gtmArray['workExperience'] = $userStatus[0]['experience'];
	    }
	    return $gtmArray;
	}

	function isValidPredictorForStream($examName, $streamName) {
        if(empty($this->settings) || empty($this->cpUrlMapping)) {
            $this->CI->load->config('CP/CollegePredictorConfig',TRUE);
            $this->cpUrlMapping = $this->CI->config->item('cpUrlMapping','CollegePredictorConfig');
            $this->settings = $this->CI->config->item('settings','CollegePredictorConfig');
        	$this->cpUrlMapping = array_flip($this->cpUrlMapping);
        }
        $examArr = $this->settings['CPEXAMS'][$examName];
        $directoryArr = array();
        $directoryArr = explode('/', $examArr['directoryName']);
        $directoryArr = array_filter($directoryArr);
        $directoryName = reset($directoryArr);
        if($directoryName == $this->cpUrlMapping[ucfirst($streamName)] || $directoryName == $streamName ) {
            return true;
        }
        else {
            return false;
        }
    }

    function generateCollegePredictorUrl($examArr) {
    	return SHIKSHA_HOME.$examArr['directoryName']."/".$examArr['collegeUrl'];
    }

    function getCollegePredictorTrackingKeys(&$displayData, $examNameNew, $type, $device) {
    	if(empty($this->settings)) {
            $this->CI->load->config('CP/CollegePredictorConfig',TRUE);
            $this->settings = $this->CI->config->item('settings','CollegePredictorConfig');
        }

    	$directoryName = $this->settings['CPEXAMS'][strtoupper($examNameNew)]['directoryName'];
		$directoryName = explode('/', $directoryName);
		$directoryName = array_filter($directoryName);
		$displayData['directoryName'] = reset($directoryName);
		switch ($displayData['directoryName']) {
			case 'design':
				if($device == 'desktop') {
					$trackingKeyId = 1415;
				}
				else {
					$trackingKeyId = 1417;
				}
				break;
			case 'hospitality-travel':
				if($device == 'desktop') {
					$trackingKeyId = 1429;
				}
				else {
					$trackingKeyId = 1431;
				}
				break;
			case 'mba':
				if($device == 'desktop') {
					$trackingKeyId = 1433;
				}
				else {
					$trackingKeyId = 1435;
				}
				break;
			case 'law' :
				if($device == 'desktop') {
					$trackingKeyId = 1549;
				}
				else {
					$trackingKeyId = 1551;
				}
				break;
			default:
				switch ($type) {
					case 'rank':
						if($device == 'desktop') {
							$trackingKeyId = 173;
						}
						else {
							$trackingKeyId = 295;
						}
						break;
					case 'institute':
					case 'college':
						if($device == 'desktop') {
							$trackingKeyId = 174;
						}
						else {
							$trackingKeyId = 296;
						}
						break;
					case 'branch':
						if($device == 'desktop') {
							$trackingKeyId = 175;
						}
						else {
							$trackingKeyId = 297;
						}
					break;
				}
				break;
		}
		if($device == 'desktop') {

		}
		else {
			$displayData['downloadtrackingPageKeyId'] =288;
			$displayData['shortlistTrackingPageKeyId']=298;
			$displayData['comparetrackingPageKeyId'] = 301;
		}
		$displayData['trackingKeyId'] = $trackingKeyId;
    }

    function initCollegePredictor($examName){
			$library=array('ajax');
			$helper=array('url','image','shikshautility','utility_helper');
			if(is_array($helper)){
				$this->CI->load->helper($helper);
			}

			if(is_array($library)){
				$this->CI->load->library($library);
			}
			$this->userid = -1;
			if(($this->userStatus == "")){
				$this->userStatus = $this->CI->checkUserValidation();
				$this->userid = $this->userStatus[0]['userid'];
			}

			$this->CI->load->helper('CP/collegepredictor');
			$this->CI->load->helper('coursepages/course_page');
			
			$this->CI->load->builder("nationalCourse/CourseBuilder");
			    $courseBuilder = new CourseBuilder();
			    $this->courseRepository = $courseBuilder->getCourseRepository();
			

			$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		    	$instituteBuilder = new InstituteBuilder();
		    $this->instituteRepository = $instituteBuilder->getInstituteRepository();
			//$this->instituteRepository = $listingBuilder->getInstituteRepository();         // this is use for compare tool only
			
			$this->CI->load->builder('BranchBuilder','CP');
			$this->branchBuilder = new BranchBuilder;
			$this->branchRepository = $this->branchBuilder->getBranchRepository();		
			
			//$this->CI->load->library('CP/CollegePredictorConfig');
			//$this->CI->settingArray = CollegePredictorConfig::$settings;
			$this->CI->load->config('CP/CollegePredictorConfig',TRUE);
			$this->CI->settingArray = $this->CI->config->item('settings','CollegePredictorConfig');
			$this->cpUrlMapping = $this->CI->config->item('cpUrlMapping','CollegePredictorConfig');

			//$this->anarecommendationlib = $this->CI->load->library('ContentRecommendation/AnARecommendationLib');
			//$this->articlerecommendationlib = $this->CI->load->library('ContentRecommendation/ArticleRecommendationLib');
	}

	public function getDefaultFiltersForLaunchPage($examName){
		if(isMobileRequest()){
			$filters = $this->CI->settingArray['defaultTabInfo']['MOBILE5'][strtoupper($examName)];
		}
		else{
			$filters = $this->CI->settingArray['defaultTabInfo']['DESKTOP'][strtoupper($examName)];
		}
		return $filters;
	}

	public function getAggregateFilters($examName){
		// preference order of filters, 1. form filters 2.Cookie filters 3.Request Filters
		if(isset($_COOKIE['collegepredictor_search_'.$examName]) && !empty($_COOKIE['collegepredictor_search_'.$examName]) && $_COOKIE['collegepredictor_search_'.$examName] != 'null') {
			$filters = json_decode($_COOKIE['collegepredictor_search_'.$examName], true);
		}
		else{
			$filters = array();
		}
		$stateName = $this->CI->settingArray[$examName]['stateName'];
		$invertLogic = $this->CI->settingArray[$examName]['invertLogic'];
		if(!empty($stateName)){
			$filters['stateName'] = $stateName;
		}
		if(!empty($invertLogic)){
			$filters['invertLogic'] = $invertLogic;
		}
		// _p($filters);
		if(strtolower($examName) == 'mahcet'){
			$filters['locMainFilters'] = true;
		}
		$filters['tabType'] = 'rank';
		$cookieFilters = json_decode($_COOKIE['collegepredictor_filterTypeValueData_desktop_'.$examName],true);
		
		foreach ($cookieFilters as $key => $row) {
			if($key == 'start') {
				continue;
			}
			$filters[$key] = $row;
		}

		if($filters['rankType']=='Home' || $filters['rankType']=='StateLevel' || $filters['rankType']=='HomeUniversity' || $filters['rankType']=='HyderabadKarnatakaQuota' || strtolower($examName) == 'mhcet' || strtolower($examName) == 'ptu' || strtolower($examName) == 'mppet' || strtolower($examName) == 'upsee' || strtolower($examName) == 'wbjee'|| strtolower($filters['examName']) == 'kcet'){
			$locationType = 'city';
		}
		else{
			$locationType = 'state';
		}

		$isAjaxRequest = $this->CI->input->is_ajax_request();
		//_P($_REQUEST['rank']);die();
		if($isAjaxRequest){
			if(!empty($_REQUEST['score'])){
				$filters['score'] = $_REQUEST['score'];
			}
			
			if(!empty($_REQUEST['percentile'])){
				$filters['percentile'] = $_REQUEST['percentile'];
			}

			if(!empty($_REQUEST['percentile2'])){
				$filters['percentile2'] = $_REQUEST['percentile2'];	
			}

			$jeeMainCanidates1 = 884000;
			$jeeMainCanidates2 = 934000;

			if(!empty($_REQUEST['rank'])){
				$filters['rank'] = $_REQUEST['rank'];
			}
			else if(!empty($_REQUEST['percentile']) && !empty($_REQUEST['percentile2'])){
				 $percentile = $_REQUEST['percentile'] > $_REQUEST['percentile2'] ? $_REQUEST['percentile'] : $_REQUEST['percentile2'];
				 $totalCandidates = 874000;
				 if(strtolower($examName) == 'jee-mains'){
				 	$totalCandidates = $_REQUEST['percentile'] > $_REQUEST['percentile2'] ? $jeeMainCanidates1 : $jeeMainCanidates2;
				 }
				 $rank = round((1 - $percentile/100)*$totalCandidates);
				 if(strtolower($examName) == 'jee-mains'){
				 	$rank = round($rank * 1.20) + 1;
				 }
				 $filters['rank'] = $rank;
			}
			else if(!empty($_REQUEST['percentile2'])) {
				 $percentile = $_REQUEST['percentile2'];

				 $totalCandidates = 874000;
				 if(strtolower($examName) == 'jee-mains'){
				 	$totalCandidates = $jeeMainCanidates2;
				 }
				 $rank = round((1 - $percentile/100)*$totalCandidates);
				 if(strtolower($examName) == 'jee-mains'){
				 	$rank = round($rank * 1.20) + 1;
				 }
				 $filters['rank'] = $rank;
			}
			else if(empty($_REQUEST['rank']) && !empty($_REQUEST['percentile']) ) {
				 $percentile = $_REQUEST['percentile'];
				 $totalCandidates = 874000;
				 if(strtolower($examName) == 'jee-mains'){
				 	$totalCandidates = $jeeMainCanidates1;
				 }
				 $rank = round((1 - $percentile/100)*$totalCandidates);
				 if(strtolower($examName) == 'jee-mains'){
				 	$rank = round($rank * 1.20) + 1;
				 }
				 $filters['rank'] = $rank;
			}
			if(!empty($_REQUEST['category'])){
				$filters['categoryName'] = $_REQUEST['category'];
			}
			if(empty($_REQUEST['branch'])){
				$filters['branchFilter'] = array();
			}
			else{
				$filters['branchFilter'] = is_array($_REQUEST['branch']) ? $_REQUEST['branch'] : array();
			}
			//_P($_REQUEST);die;
			if(!empty($_REQUEST['rank_type'])){
				if(!empty($filters['examName']) && $filters['examName'] == 'JEE-Mains'){
					if(!empty($_REQUEST['stateName'])){
						$filters['rankType'] = 'Home';	
					}else{
						$filters['rankType'] = $_REQUEST['rank_type'];
					}
				}else {
					$filters['rankType'] = $_REQUEST['rank_type'];
				}
			}
			//_P($filters);die;
			if(!empty($_REQUEST['stateName'])){
				$filters['stateName'] = $_REQUEST['stateName'];
			}
			if(!empty($_REQUEST['examName'])){
				$filters['examName'] = $_REQUEST['examName'];
			}

			$filters['roundFilter'] = array();

			$filters['start'] = $_REQUEST['start'];
			
			if(empty($_REQUEST['college'])){
				$filters['collegeFilter'] = array();
			}
			else{
				$filters['collegeFilter'] = is_array($_REQUEST['college']) ? $_REQUEST['college'] : explode(',',$_REQUEST['college']);
			}

			if($filters['rankType']=='Home' || $filters['rankType']=='StateLevel' || $filters['rankType']=='HomeUniversity' || $filters['rankType']=='HyderabadKarnatakaQuota' || strtolower($examName) == 'mhcet' || strtolower($examName) == 'ptu' || strtolower($examName) == 'mppet' || strtolower($examName) == 'upsee' ||  strtolower($examName) == 'wbjee'|| strtolower($filters['examName']) == 'kcet'){
				$locationType = 'city';
			}
			else{
				$locationType = 'state';
			}
			
			if(!empty($_REQUEST['city'])){
				$filters['cityId'] = $_REQUEST['city'];
			}

			$filters['locationFilter'][$locationType] = empty($_REQUEST['location']) ? array() : explode(':',$_REQUEST['location']);

			$filterTypeValueData = $_REQUEST['filterTypeValue_'.$examName];

			if(!empty($filterTypeValueData)){
				foreach($filterTypeValueData as $key=>$filterTypeValue){
					$filterTypeValueArr = explode('::::',$filterTypeValue);
					$filterType = $filterTypeValueArr[0];
					$filterValueArr = explode(':',$filterTypeValueArr[1]);

					if($filterType=='locationFilter'){
						$filters[$filterType][$locationType] = array_unique(array_merge($filters[$filterType][$locationType], $filterValueArr));
					}
					else{
						$filters[$filterType]= array_unique(array_merge($filters[$filterType], $filterValueArr));
					}
				}
			}
		}
		$filters['collegeFilter'] = empty($filters['collegeFilter']) ? array() : array_unique($filters['collegeFilter']);
		$filters['branchFilter'] = empty($filters['branchFilter']) ? array() : array_unique($filters['branchFilter']);
		if(!empty($locationType)){
			$filters['locationFilter'][$locationType] = empty($filters['locationFilter']) ? array() : array_unique($filters['locationFilter'][$locationType]);
		}
		$filters['examName'] = $examName;
		if(empty($filters['start'])) {
			$filters['start'] = 0;
		}
		if(empty($filters['offset'])) {
			if(isMobileRequest()) {
				$filters['offset'] = 10;
			}
			else {
				$filters['offset'] = 15;
			}
		}

		return $filters;
	}

	function getCollegeDataForRankPredictor($examName,$rank){
		//remove below line before going production
		ini_set('memory_limit','2000M');
		if(strtolower($examName) == 'jee-main'){
			$examName = 'JEE-Mains';
		}
		$this->initCollegePredictor($examName);

		$examNameNew = strtoupper($examName);
		if(!isset($this->CI->settingArray[$examNameNew]) || empty($this->CI->settingArray[$examNameNew])) {
			return array();
		}
		$examinationName = $this->CI->settingArray[$examNameNew]['examName'];

		$appliedFilters = array();
		$appliedFilters['examName'] = $examinationName;
		$appliedFilters['rank'] = $rank;
		$appliedFilters['rankType'] = $this->CI->settingArray[$examNameNew]['rankType'];
		$appliedFilters['stateName'] = $this->CI->settingArray[$examNameNew]['stateName'];
		$appliedFilters['categoryName'] = $this->CI->settingArray['defaultTabInfo']['DESKTOP'][$examNameNew]['categoryName'];

		//_p($appliedFilters);die;

		$branchRepoData = $this->branchRepository->findMultiple($appliedFilters);
		$totalBranchCount = $branchRepoData['totalBranchCount'];
		$branchObj = $branchRepoData['branchEntityObj'];

		$data = (is_object($branchObj)) ? $branchObj->getPageData() : array();
		$count = isMobileRequest() ? 20 : 30;
		$data = $this->groupDataByBranchAndRounds($data, $appliedFilters);
		$result = createPagesForCollegePredictor($data,$start,$count);
		// _p($result);die;

		$displayData = array();
		$displayData['courseData'] = $this->getInterlinkingDataForTuples($result);
		$displayData['branchInformation'] = $result;
		$displayData['roundData'] = $this->CI->settingArray[$examNameNew]['roundData'];

		$seoDetails = getSeoDataForPages(1,strtoupper($examNameNew),'',0,$this->CI->settingArray);
		$displayData['collegePredictorUrl'] = $seoDetails['canonicalURL'];
		$displayData['collegePredictorExamName'] = $examinationName;

		// _p($displayData['collegePredictorUrl']);die;

		//conversion tracking for rank predictor
		if(isMobileRequest()){
			$displayData['comparetrackingPageKeyId'] = 1501;
			$displayData['brochuretrackingPageKeyId'] = 1499;
			$displayData['shortlisttrackingPageKeyId'] = 1503;
		}
		else{
			$displayData['comparetrackingPageKeyId'] = 1491;
			$displayData['brochuretrackingPageKeyId'] = 1489;
			$displayData['shortlisttrackingPageKeyId'] = 1493;
		}

		$displayData['shortlistedCoursesOfUser']	= Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $this->userid);

		return $displayData;
	}

	//this function gets all the data needed for result tuple
	function getInterlinkingDataForTuples($result, $iitsCourseIds, $showHomeState){
		$courseIds = array();$courseObjs = array();
		$instituteIds = array();$instituteObjs = array();
		foreach ($result as $branchObj) {
			if($branchObj->getShikshaCourseId() > 0){
				$courseIds[] = $branchObj->getShikshaCourseId();
			}
		}
		
		if(!empty($courseIds)){
			$courseObjs = $this->courseRepository->findMultiple($courseIds);
			foreach ($courseObjs as $courseId => $courseObj) {
				if($courseObj && $courseObj->getId() > 0){
					$instituteIds[$courseObj->getInstituteId()] = $courseObj->getInstituteId();
				}
			}
		}

		if(!empty($instituteIds)){
			$instituteObjs = $this->instituteRepository->findMultiple(array_values($instituteIds));
			foreach ($instituteObjs as $instituteId => $instituteObj) {
				if($instituteObj && $instituteObj->getId() > 0){
					$instituteData[$instituteId]['url'] = $instituteObj->getURL();
				}
			}
		}

		if(!empty($courseIds)){
			$courseObjs = $this->courseRepository->findMultiple($courseIds);
			foreach ($courseObjs as $courseId => $courseObj) {
				if($courseObj && $courseObj->getId() > 0){
					$instituteId = $courseObj->getInstituteId();
					$courseData[$courseId]['courseUrl'] = $courseObj->getURL();
					$courseData[$courseId]['courseName'] = $courseObj->getName();
					$courseData[$courseId]['instituteId'] = $instituteId;
					$courseData[$courseId]['instituteUrl'] = $instituteData[$instituteId]['url'];

					$courseData[$courseId]['reviewCount'] = $courseObj->getReviewCount();
					$courseData[$courseId]['reviewUrl'] = add_query_params($instituteObjs[$instituteId]->getAllContentPageUrl('reviews'),array("course"=>$courseId));

					$feesObj = $courseObj->getFees();
					$fees = array();
					if($feesObj){
						$fees['value'] = $feesObj->getFeesValue();
						$fees['unit'] = $feesObj->getFeesUnitName();	
					}
					
					$courseData[$courseId]['feesData'] = $fees; 
					$courseData[$courseId]['courseDuration'] = $courseObj->getDuration();
					
					if($iitsCourseIds[$courseId] == 1){
						$courseData[$courseId]['isIIT'] = 1;
					}
					else{
						$courseData[$courseId]['isIIT'] = 0;
					}
					if($showHomeState == 1){
						$courseData[$courseId]['showHomeState'] = 1;
					}
					else{
						$courseData[$courseId]['showHomeState'] = 0;	
					}
					
				}
			}
		}

		$collegeReviewLib = $this->CI->load->library('CollegeReviewForm/CollegeReviewLib');
        $aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($courseIds, 'course');
        foreach ($aggregateReviews as $key => $value) {
        	$courseData[$key]['aggregateReviewsData'] = $aggregateReviews[$key];	
        }
		return $courseData;
	}

    function getDataForCollegePredictor($examName, $folderName,$urlType =1 ,$mobile = false) {
		$this->collegepredictorredirectionrules = $this->CI->load->library('CP/CollegePredictorRedirectionRules');
        $this->collegepredictorredirectionrules->redirectionRule($examName, $folderName, $urlType);
		$this->initCollegePredictor($examName);
		
		$this->institutes = $this->getInstituteData($examName);
		$this->states = $this->getStates();
		$this->branches = $this->getBranches($examName);
		
		$this->commonData = array();
		$this->commonData['institutes'] = $this->institutes;
		$this->commonData['states'] = $this->states;
		$this->commonData['branches'] = $this->branches;
		$this->commonData['instituteGroups']; 

		$callType = isset($_REQUEST['callType'])?$this->CI->input->post('callType'):'';
		$isAjaxRequest = $this->CI->input->is_ajax_request();
		$examNameNew = strtoupper($examName);
		if(!isset($this->CI->settingArray[$examNameNew]) || empty($this->CI->settingArray[$examNameNew])) {
			show_404();
		}
		$filterStatus = $this->CI->settingArray[$examNameNew]['showFilters'];
		$examinationName = $this->CI->settingArray[$examNameNew]['examName'];
		$examYear = $this->CI->settingArray[$examNameNew]['examYear'];
		$invertLogic = $this->CI->settingArray[$examNameNew]['invertLogic'];
		$defaultTabStateName = $this->CI->settingArray['defaultTabInfo']['MOBILE5'][$examNameNew]['stateName'];
		
		$type    = (isset($_REQUEST['type']) && $_REQUEST['type'] != '') ? $this->CI->input->post('type') : "";
		$linkUrl = false;
		$defaultView = true;
		$start = 0 ;
		$appliedFilters = $this->getAggregateFilters($examinationName);


		if($isAjaxRequest) {
			if(isset($_REQUEST['start'])){
				$start = $_REQUEST['start'];
			}
			
			if($start == 0 ){
				setcookie('collegepredictor_search_'.$examinationName,json_encode($appliedFilters),0,'/',COOKIEDOMAIN);
			}
			$defaultView = false;
		}else {
			$mainFilters=$this->getMainFilters($appliedFilters);
			
			if($_COOKIE['collegepredictor_search_'.$examinationName] == 'null') {
				$_COOKIE['collegepredictor_search_'.$examinationName] = '';
			}
			if(isset($_COOKIE['collegepredictor_search_'.$examinationName]) && !empty($_COOKIE['collegepredictor_search_'.$examinationName]) && $_COOKIE['collegepredictor_search_'.$examinationName] != 'null') {
				$defaultView = false;
			}
		}
		setcookie('collegepredictor_filterTypeValueData_desktop_'.$examinationName,json_encode($appliedFilters),0,'/',COOKIEDOMAIN);

		$displayData = array();
		$displayData = $this->commonData;
		//_P($type);die("0op");
		$displayData['type'] = $_REQUEST['type'];
		$displayData['mainFilterCities']=$mainFilters['cities'];
		$displayData['tabType'] = 'rank';
		$displayData['tab'] = '1';

		$displayData['courseRepository'] = $this->courseRepository;
		$displayData['instituteRepository'] = $this->instituteRepository;  // this is used only for compare tool to get headerimage of institue

		
		if(!$defaultView) {
			$branchRepoData = $this->branchRepository->findMultiple($appliedFilters);
			$totalBranchCount = $branchRepoData['totalBranchCount'];
			$branchObj = $branchRepoData['branchEntityObj'];
			$branchObjData = $branchObj->getPageData();
			//setcookie('COLLEGE_PREDICTOR_TOTAL_RESULT_'.$examinationName,count($branchObjData),0,'/',COOKIEDOMAIN);
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
			}
		}
		else{
			$appliedFilters = $this->getDefaultFiltersForLaunchPage($examinationName);
			$appliedFilters['defaultView'] = $defaultView;
			if($this->enableCachingCP) {
				$result = $this->cache->getPredictorDefaultResults($examNameNew);
			}
			if(empty($result)) {
				$branchRepoData = $this->branchRepository->findMultiple($appliedFilters);
				$totalBranchCount = $branchRepoData['totalBranchCount'];
				$branchObj = $branchRepoData['branchEntityObj'];
				$branchObjData = $branchObj->getPageData();
			}
		}
// _p($displayData['institutesFilter']); die;
		if(empty($result)) {
			if(!$defaultView && $filterStatus=='YES'){
				$filters = $this->_getFilters($appliedFilters,$branchObj);
				$displayData['collegePredictorFilters'] = $filters['collegePredictorFilters'];
				$displayData['defaultCollegePredictorFilters'] = $filters['defaultCollegePredictorFilters'];
			}
			
			$defaultCollegePredictorFilters = '';
			$collegePredictorFilters        = '';
			$displayData['mainObj'] = (is_object($branchObj)) ? $branchObj->getPageData() : array();

			if(!$defaultView && $filterStatus=='YES'){
				$this->CollegePredictorFilterManager = $this->branchBuilder->getFilterManager();
				// $this->CollegePredictorFilterManager->applyFilters($branchObj, $appliedFilters);
			}

			$data = (is_object($branchObj)) ? $branchObj->getPageData() : array();
			$displayData['total'] = $totalBranchCount;
			if(isMobileRequest()) {
				$count = 10;
			}
			else {
				$count = COLLEGE_PREDICTOR_COUNT_OFFSET;
			}
			$data = $this->groupDataByBranchAndRounds($data, $appliedFilters);

			$result = $data;
			if($this->enableCachingCP) {
				$this->cache->storePredictorDefaultResults($result, $examNameNew);
			}
		}
		$configData = $this->getConfigData($examNameNew,'rank');
		$displayData['courseData'] = $this->getInterlinkingDataForTuples($result, $configData['iitsCourseIds'], $configData['showHomeState']);

		$seoDetails = getSeoDataForPages($urlType,strtoupper($examNameNew),'',0,$this->CI->settingArray);
		if(is_array($seoDetails)){
	        $displayData['m_meta_title'] = $seoDetails['title'];
	        $displayData['m_meta_description'] = $seoDetails['description'];
	        $displayData['canonicalURL'] = $seoDetails['canonicalURL'];
        }

        $displayData['objAfterAppliedFilter'] = $data;

        $displayData['linkUrl'] = $linkUrl;
        if($linkUrl) {
        	$displayData['rank'] = $appliedFilters['rank'];
        }      
		$displayData['branchInformation'] = $result;
		$displayData['start'] = $start;
		$displayData['count'] = $count;
		$displayData['validateuser'] = $this->userStatus;

		$displayData['examNameDisplay'] = $examName;
		if($examName == "jee-mains"){
		
			$displayData['examNameDisplay'] = "JEE-Main";
		}
		$displayData['examName'] = $examName;
		$displayData['examinationName'] = $examinationName;
		$displayData['examYear'] = $examYear;
		$displayData['inputData'] = $appliedFilters;
		$displayData['appliedFilters'] = $appliedFilters;
		$displayData['filters'] = $appliedFilters;
		$displayData['invertLogic'] = $invertLogic;
		$displayData['defaultTabStateName'] = $defaultTabStateName;

		if($urlType == 1){
			$displayData['noIndexNoFollow'] = false;
		}
		else{
			$displayData['noIndexNoFollow'] = true;
		}
		// Hard code for engineering
		$subcatId = "56";
		$displayData['tab_required_course_page'] = checkIfCourseTabRequired($subcatId);
		$displayData['subcat_id_course_page'] = $subcatId;
		$displayData['course_pages_tabselected'] = 'CollegePredictor';
		
		foreach($configData as $key=>$value){
			$displayData[$key]  = $value;
		}
		if($examName =='mppet' && $appliedFilters['rankType'] =='Other'){
			$displayData['roundData'] = array('1'=>'Round 1');
		}
			
		if($callType == 'Ajax') {
			$this->makeLogEntry($appliedFilters,$start,$displayData['total'],'');
		}

	
		$displayData['showCustomizedGNB'] = true;
		$displayData['callType'] = $callType;

		//conversion tracking
		if(isMobileRequest()){
			$displayData['comparetrackingPageKeyId'] = 1455;
			$displayData['brochuretrackingPageKeyId'] = 1451;
			$displayData['shortlisttrackingPageKeyId'] = 1459;
		}
		else{
			$displayData['comparetrackingPageKeyId'] = 1453;
			$displayData['brochuretrackingPageKeyId'] = 1449;
			$displayData['shortlisttrackingPageKeyId'] = 1457;
		}

		$beaconTrackData = $this->prepareBeaconTrackData('collegePredictor',$examName,'college');
		$gtmParams = $this->getGTMArray($beaconTrackData['pageIdentifier'], $appliedFilters);
		$displayData['gtmParams'] = $gtmParams;
		$displayData['defaultView'] = $defaultView;
		$displayData['folderName'] = $folderName;

		$displayData['shortlistedCoursesOfUser']	= Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $this->userid);
		return $displayData;	
    }

    function groupDataByBranchAndRounds($data, $appliedFilters){
    	$returnData = array();
    	$branchWiseData = array();
    	$isAllIndiaRankType = false;
    	if($appliedFilters['rankType'] == 'Other') {
    		$isAllIndiaRankType = true;
    	}

    	foreach($data as $row){
    		$branchWiseData[$row->getBranchId()][$row->getCategoryName()][] = $row;
    	}
    	// _p($branchWiseData); 
    	$inputRank = $appliedFilters['rank'];
    	$returnData = array();
    	foreach ($branchWiseData as $branchData) {
    		$branchDataNew = $branchData;
    		ksort($branchDataNew,1);
    			foreach ($branchDataNew as $categoryData) {
    				$data = array();
    				foreach ($categoryData as $row) {
    					$rankType = $row->getRankType();
    					$roundsInfo = $row->getRoundsInfo();
	    				foreach ($roundsInfo as $roundNumber => $roundsData) {
	    					$closingRank = $roundsData['closingRank'];
		    				if($isAllIndiaRankType || strtolower($appliedFilters['examName']) != 'mahcet') {
		    					if($rankType == 'Other') {
		    						if($inputRank <= $closingRank) {
	    								$data[$roundNumber][$rankType] = $closingRank;
		    						}
		    						else {
	    								$data[$roundNumber][$rankType] = $closingRank;
		    						}
		    					}
		    				}
		    				else {
	    						$data[$roundNumber][$rankType] = ($roundsData['closingRank'] > 0) ? $this->rankTypeMapping[$rankType].': '.$roundsData['closingRank'] : $this->rankTypeMapping[$rankType].': n/a';
		    				}
	    					$roundsInfo[$roundNumber]['isEligible'] = false;
	    					if($roundsData['closingRank'] >= $inputRank) {
	    						$roundsInfo[$roundNumber]['isEligible'] = true;
	    					}
	    				}
    					
    				}
    				
    				$sortedRank = array();
    				foreach ($data as $roundNumber => $value) {
    					// _p($value);
	    					$sortedRank = array();
	    				foreach ($this->rankTypeMapping as $rankType => $rankHeading) {
	    					if(!empty($value[$rankType])) {
	    						$sortedRank[] =  $value[$rankType];
	    					}
	    				}
	    				$roundsInfo[$roundNumber]['closingRankString'] = "<p>".implode('</p><p> ',$sortedRank)."</p>";
    				}
    				if($row->getShikshaCourseId() == 234869) {	
		    		}
    				
    				$row->setRoundsInfo($roundsInfo);
    				$returnData[] = $row;
    			}
    	}
    	return $returnData;
    }

    function getMainFilters($filters){
    	$mainFilters = array();
    	// city filters
		if($filters['locMainFilters']){
			$mainFilters['cities']=$this->cpmodel->getLocationsByExamAndState($filters['examName'],$filters['stateName']);
		}
		return $mainFilters;
    }

    function getInstituteData($examName='JEE-Mains'){
    	if($examName != 'jee-mains' && $examName != 'JEE-Mains' ){
    		$this->CI->load->model('CP/cpmodel');
			$this->cpmodel = new CPModel();
			$instituteData = $this->cpmodel->getInstiuteData($examName);
			return $instituteData;
		}
		else return null;
	}

	function getStates(){
		$this->CI->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$stateData = $this->cpmodel->getStates();
		return $stateData;
	}

	function getBranches($examName='JEE-Mains'){
		$this->CI->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$branchData = $this->cpmodel->getBranches($examName);
		return $branchData;
	}

	function getInstituteGroups($examName='JEE-Mains'){
		$this->CI->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$instituteGroupData = $this->cpmodel->getInstiuteGroups($examName);
		return $instituteGroupData;
	}

	function makeDBTrackingEntry($displayData){
	
		if($displayData['validateuser'][0]['userid']){
			$data['user_id'] = $displayData['validateuser'][0]['userid'];
		} else {
			$data['user_id'] = 0;
		}
		$data['exam_name'] = $displayData['inputData']['examName'];
		$data['score'] = $displayData['inputData']['score'];
		$data['percentile'] = $displayData['inputData']['percentile'];
		$data['rank'] = $displayData['inputData']['rank'];
		$data['category_name'] =$displayData['inputData']['categoryName'];
		$data['home_state'] = $displayData['inputData']['stateName'];
		$data['type'] = 'collegePredictor';
		//_P($data);die("lib");
		$this->addToRabbitMQ($data);
	}

	public function addToRabbitMQ($data){
		
			 if(empty($data)){
			 	return;
			 }
		        try {
                $this->CI->config->load('amqp');
                $this->CI->load->library("common/jobserver/JobManagerFactory");
                $jobManager = JobManagerFactory::getClientInstance();
                $jobManager->addBackgroundJob("Predictor_Tracking",$data);
            }
            catch(Exception $e){
                error_log("JOBQException: ".$e->getMessage());
            }
	}

	public function _getFilters($appliedFilters ,$branchObj){
		$this->CI->load->builder('BranchBuilder','CP');
		$branchBuilder = new BranchBuilder;
		$branchRepository = $branchBuilder->getBranchRepository();

		if($appliedFilters['rankType']=='Home' || $appliedFilters['rankType']=='StateLevel' || $appliedFilters['rankType']=='HomeUniversity' || $appliedFilters['rankType']=='HyderabadKarnatakaQuota' || strtolower($appliedFilters['examName']) == 'mhcet' || strtolower($appliedFilters['examName']) == 'ptu' || strtolower($appliedFilters['examName']) == 'mppet' || strtolower($appliedFilters['examName']) == 'upsee' || strtolower($appliedFilters['examName']) == 'wbjee'|| strtolower($appliedFilters['examName']) == 'kcet'){
			$defaultArr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('city'=>''));
		}
		else{
			$defaultArr = array('roundFilter'=>array(),'collegeFilter'=>array(),'branchFilter'=>array(),'locationFilter'=>array('state'=>''));
		}

		$defaultArr['rankType'] = $appliedFilters['rankType'];
		$defaultArr['examName'] = $appliedFilters['examName'];

		$collegePredictorFilterManager = $branchBuilder->getFilterManager();
		// _p($appliedFilters); die;
		// $defaultCollegePredictorFilters    = $collegePredictorFilterManager->getFilters($branchObj, $defaultArr);

		// $collegePredictorFilters    = $collegePredictorFilterManager->getFilters($branchObj,$appliedFilters);
		$defaultCollegePredictorFilters    = $collegePredictorFilterManager->getFilters($branchObj,$appliedFilters);
		// _p($defaultCollegePredictorFilters); die;
		$displayData['collegePredictorFilters'] = $collegePredictorFilters;
		$displayData['defaultCollegePredictorFilters'] = $defaultCollegePredictorFilters;

		return $displayData;
	}

	function getConfigData($examName,$tabName){
		$this->CI->load->config('CP/CollegePredictorConfig',TRUE);
		$this->CI->settingArray                      = $this->CI->config->item('settings','CollegePredictorConfig');
		$displayData                             = array();
		$displayData['course_pages_tabselected'] = 'CollegePredictor';
		$displayData['heading']                  = $this->CI->settingArray[$examName]['tab'][$tabName]['heading'];
		$displayData['imageTitle']               = $this->CI->settingArray[$examName]['tab'][$tabName]['imageTitle'];
		$displayData['notice']                   = $this->CI->settingArray[$examName]['notice'];
		$displayData['showInvite']               = $this->CI->settingArray[$examName]['showInvite'];
		$displayData['showEmail']                = $this->CI->settingArray[$examName]['showEmail'];
		$displayData['showPrint']                = $this->CI->settingArray[$examName]['showPrint'];
		$displayData['showFeeback']              = $this->CI->settingArray[$examName]['showFeeback'];
		$displayData['showRankType']             = $this->CI->settingArray[$examName]['showRankType'];
		$displayData['noStateDropDown']          = isset($this->CI->settingArray[$examName]['noStateDropDown']) ? $this->CI->settingArray[$examName]['noStateDropDown']:0 ;
		$displayData['categoryData']             = $this->CI->settingArray[$examName]['categoryData'];
		$displayData['roundData']                = $this->CI->settingArray[$examName]['roundData'];
		$displayData['inputType']                = $this->CI->settingArray[$examName]['inputType'];
		$displayData['showRankOverlay']          = $this->CI->settingArray[$examName]['showKnowYourRank'];
		$displayData['rankType']                 = $this->CI->settingArray[$examName]['rankType'];
		$displayData['stateName']                = $this->CI->settingArray[$examName]['stateName'];
		$displayData['filterStatus']             = $this->CI->settingArray[$examName]['showFilters'];
		$displayData['examinationName']          = $this->CI->settingArray[$examName]['examName'];
		$displayData['iitsCourseIds'] 			 = $this->CI->settingArray[$examName]['iitsCourseIds'];
		$displayData['showHomeState']			 = $this->CI->settingArray[$examName]['showHomeState'];
		$displayData['tabDetail']                = $this->CI->settingArray['CPEXAMS'][$examName];
		return $displayData;
	}

	function makeLogEntry($parameters,$start=0,$totalResults=0,$activity = ''){
		$data = array();
		$this->CI->load->model('CP/cpmodel');
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
			if(isset($this->CI->userStatus) && is_array($this->CI->userStatus)){
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

	public function getCPzrpRedirection($displayData,$folderName = 'b-tech'){
		if($displayData['total']==0 && $folderName == 'b-tech'){
			$ctpgMain=array();
			$ctpgFilters=array();
			global $btechBaseCourse;
			
            $CPExamNameToExamMainName = $this->CI->config->item('CPExamNameToExamMainName','CollegePredictorConfig');
            $CPStateNameToStateTableName = $this->CI->config->item('CPStateNameToStateTableName','CollegePredictorConfig');
			$this->examMainLib = $this->CI->load->library("examPages/ExamMainLib");
			$exammainName = $displayData['examinationName'];
			if(array_key_exists($exammainName, $CPExamNameToExamMainName)){
				$exammainName = $CPExamNameToExamMainName[$exammainName];
			}
			$examDetails = $this->examMainLib->getExamDetailsByName($exammainName,$addQuotes=false);
			if(isset($examDetails[$exammainName])){
				$ctpgFilters['exam']=$examDetails[$exammainName]['examId'];
			}

			if(!in_array($displayData['examinationName'], array('JEE-Mains','BITSAT'))){
				$stateName = $displayData['defaultTabStateName'];
				if(array_key_exists($stateName, $CPStateNameToStateTableName)){
					$stateName = $CPStateNameToStateTableName[$stateName];
				}
				if($exammainName=='GGSIPU'){
					$ctpgMain['city_id']=10223; // Delhi NCR
				}
				else if(isset($stateName)){
					$autoAnsweringModel = $this->CI->load->model("autoAnswer/autoansweringmodel"); 
					$ctpgFilters['state']=$autoAnsweringModel->getStateIdByName($stateName);
				}
			}
			
			$this->CI->load->library("nationalCategoryList/NationalCategoryPageLib");
			$nationalCategoryPageLib = new NationalCategoryPageLib();
			$zrpRedirectionText = "Sorry! We could not find colleges you could clear.\nWould you like to see other colleges";
			if(!empty($displayData['stateName'])){
				$zrpRedirectionText.=( " in ".$displayData['stateName']);
			}
			$zrpRedirectionText.=(" accepting ".$exammainName." scores?");
			if(!($ctpgMain['city_id']>0)){
				$ctpgMain['city_id'] = 1;
			}
			$ctpgMain['base_course_id'] = $btechBaseCourse; 
			$ctpgMain['orderby'] = 'result_count desc';
			$ctpgMain['min_result_count'] = 1;
			$ctpgMain['filterData'] = $ctpgFilters;
			$url = $nationalCategoryPageLib->getMultipleUrlByMultipleParams($ctpgMain,array(),1);
			if(count($url)>0){
				return array('zrpRedirection'=>true,'zrpRedirectionUrl'=>$url[0]['url'],'zrpRedirectionText'=>$zrpRedirectionText);
			}
		}
		return array();
	}

	public function getBreadCrumbDataForPredictor($param, $predictorName, $predictorType) {
		$breadCrumbData = array();
		if (empty($param['examId']) || empty($param['groupId'])) {
			return $breadCrumbData;
		}
		$this->CI->load->builder('ExamBuilder', 'examPages');
		$examBuilder = new ExamBuilder();
		$examRepository = $examBuilder->getExamRepository();
		$groupObject = $examRepository->findGroup($param['groupId']);
		$examObject = $examRepository->find($param['examId']);
		$entitiesMappedToGroup = $groupObject->getEntitiesMappedToGroup();
		$hierarchyData = $groupObject->getHierarchy();
		$chpPostData = array();
		if (!empty($entitiesMappedToGroup['course']) && (in_array(10, $entitiesMappedToGroup['course']) || in_array(30, $entitiesMappedToGroup['course']) || in_array(101, $entitiesMappedToGroup['course']))) {
			$chpPostData = array('bips' => array($entitiesMappedToGroup['course'][0]));
		} elseif (!empty($entitiesMappedToGroup['primaryHierarchy']) && !empty($hierarchyData[$entitiesMappedToGroup['primaryHierarchy'][0]])) {
			$chpPostData = array('sips' => array($hierarchyData[$entitiesMappedToGroup['primaryHierarchy'][0]]['stream']));
		}

		$this->CI->load->library("common/apiservices/APICallerLib");
		if (!empty($chpPostData)) {
			$output = $this->CI->apicallerlib->makeAPICall("CHP", "/coursehomepage/api/v1/info/getInterlinkingCHPsForUILP", "POST", "", json_encode($chpPostData), array(), "");
			$output = json_decode($output['output'], true);
			if (!empty($output['data'])) {
				$chpURL = $output['data'][0]['url'];

				$this->CI->load->builder('ListingBaseBuilder', 'listingBase');
				$this->ListingBaseBuilder = new ListingBaseBuilder();
				if (key_exists('sips', $chpPostData)) {
					$streamRepository = $this->ListingBaseBuilder->getStreamRepository();
					$stream = $streamRepository->find($chpPostData['sips'][0]);
					if (!empty($stream->getAlias())) {
						$chpName = $stream->getAlias();
					} else {
						$chpName = $stream->getName();
					}
				} else {
					$basecourseRepository = $this->ListingBaseBuilder->getBaseCourseRepository();
					$basecourse = $basecourseRepository->find($chpPostData['bips'][0]);
					if (!empty($basecourse->getAlias())) {
						$chpName = $basecourse->getAlias();
					} else {
						$chpName = $basecourse->getName();
					}
				}
			}
		}

		$breadCrumbData[] = array('name' => 'Home', 'url' => SHIKSHA_HOME);
		if (!empty($chpURL)) {
			$breadCrumbData[] = array('name' => $chpName, 'url' => SHIKSHA_HOME . $chpURL);
		}

		$examPrimaryGroup = $examObject->getPrimaryGroup();
		if (!empty($examPrimaryGroup) && $examPrimaryGroup['id'] == $param['groupId']) {
			$breadCrumbData[] = array('name' => $examObject->getName(), 'url' => $examObject->getUrl());
		} else {
			$breadCrumbData[] = array('name' => $examObject->getName(), 'url' => $examObject->getUrl() . "?course=" . $param['groupId']);
		}
		$breadCrumbData[] = array('name' => $predictorName . ' ' . $predictorType, 'url' => '');

		return $breadCrumbData;
	}

	public function addDataToRabbitMQ($data, $key=''){
			if(empty($data) || empty($key)){
				return;
			}
	        try {
	            $this->CI->config->load('amqp');
	            $this->CI->load->library("common/jobserver/JobManagerFactory");
	            $jobManager = JobManagerFactory::getClientInstance();
	            $jobManager->addBackgroundJob($key, $data);
            }catch(Exception $e){
                error_log("JOBQException: ".$e->getMessage());
                if($key == 'predictor_response_mapping_to_profile'){
                	mail('ugctech@shiksha.com','Error while adding Exam response mapping to queue at'.date('Y-m-d H:i:s'), "Exam response mapping to profile"."\n Error : ".print_r($e->getMessage(),true));
                }
            }
	}

    public function getESConnectionConfigurations(){
        $ESConnectionLib    = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientConn   = $ESConnectionLib->getESServerConnection();
        $this->clientConn6  = $ESConnectionLib->getShikshaESServerConnection();
    }

    public function buildESQuery($indexName, $type, $listingType, $listingTypeId, $startDateTime, $endDateTime, $excludeResponseTypes = array(),$from = 0){
        $esQuery = array();
        $esQuery['index']   = $indexName;
        $esQuery['type']    = $type;
        $esQuery['from']    = $from;
        $esQuery['size']    = 50000;
        $esQueryBody = array();
        $esQueryBody['_source'] = array('user_id', 'listing_type_id', 'response_action_type', 'response_time');
        $esFilterQuery = array( 'bool'  =>  array(  "must"  =>  array(  array(  "term"  =>  array(  "response_listing_type" =>  $listingType)
                                                                            ),
                                                                        array(  "range" =>  array(  "latest_response_time" =>  array(   "gte"   =>  $startDateTime,"lte"   =>  $endDateTime ))
                                                                        )
                                                                    ),
 
                                                )
                            );
        /*if (is_array($excludeResponseTypes) && !empty($excludeResponseTypes)){
            $esFilterQuery['bool']['must_not']    =   array(  array(  'terms' =>  array(  'response_action_type' =>   $excludeResponseTypes)
                                                                                )
                                                            );
        }*/
        $esQueryBody['query']['bool']['filter'] = $esFilterQuery;
        /**$esSortQuery    =   array(  "latest_response_time"  =>  array(  "order" =>  "asc")
                                );
        $esQueryBody['sort']    =   $esSortQuery;*/
        $esQuery['body']    = $esQueryBody;
        return $esQuery;
    }

    public function processAutoSubscriptionForLegacyUsers($startDate,$endDate,$from=0){
        $this->getESConnectionConfigurations();
        $this->CI->load->config('examPages/autoSubscribeConfig');
        $autoSubscriptionConfigArray = $this->CI->config->item('autoSubscribeConfigData');
        $indexName = LDB_RESPONSE_INDEX_NAME;
        $esQuery = $this->buildESQuery($indexName, "response", "exam", $groupId, $startDate, $endDate, $groupData['excludeResponse'],$from);
        $result = $this->clientConn6->search($esQuery);
        $gotResult = false;
        $allReturnData = array();
        foreach ($result['hits']['hits'] as $resultValue){
        	$gotResult = true;
        	$actionTypes = $resultValue['_source']['response_action_type'];
        	$flag = false;
        	if(is_array($actionTypes)){
        		foreach ($actionTypes as $key => $action) {
        			if($action != "exam_viewed_response"){
        				$flag=true;
        				break;
        			}
        		}
        	}
        	else{
        		continue;
        	}
        	if(!$flag || empty($resultValue['_source']['response_time'][$key]) ){
        		continue;
        	}
            $returnData['groupId']  = $resultValue['_source']['listing_type_id'];
            $returnData['userId'] = $resultValue['_source']['user_id'];
            $returnData['SubmitDate'] = DateTime::createFromFormat("Y-m-d\TH:i:s", $resultValue['_source']['response_time'][$key])->format("Y-m-d G:i:s");
            $allReturnData[] = $returnData;
        }
        return array("return" => $allReturnData,"gotResult"=>$gotResult);
    }
}

?>
