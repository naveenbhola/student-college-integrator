<?php
class CollegePredictorController extends ShikshaMobileWebSite_Controller
{
	private $instituteRepository;
	private $branchRepository;

	//Function to Load the Libraries, Configs, Helpers
    function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == "")){
			$this->userStatus = $this->checkUserValidation();
		}
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$this->settingArray =$this->config->item('settings','CollegePredictorConfig');
        $this->load->config('mcommon5/mobi_config');
        $this->load->helper('mcommon5/mobile_html5');
        $this->load->helper('CP/collegepredictor');
        $this->collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
		
	}	
		
	/******************
	 * Function to show the Branch Tab.
	 * If the Cookies are set, we will fetch the results and the show them. Else, we will simply show the Search form for Branches
	 ******************/
	function loadBranchTab($examName, $folderName = 'b-tech'){
		$url = SHIKSHA_HOME.'/'.$folderName.'/resources/'.$examName.'-college-predictor';
        redirect($url, 'location', 301);
		exit;

		if(strtoupper($examName)=='MAHCET')
		{
			show_404();
		}
		$this->collegepredictorredirectionrules = $this->load->library('CP/CollegePredictorRedirectionRules');
		$this->init();
		$this->collegepredictorredirectionrules->redirectionRule($examName, $folderName, 'branch');
		//$this->load->library('CP/CollegePredictorConfig');
		
		//get Shortlisted course from Db/cookie
		$getMShortlistedCourse = Modules::run('mobile_category5/CategoryMobile/getMShortlistedCourse'); 
		$courseShortArray = $getMShortlistedCourse;
		//get brochureUrl from courseId
		$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
		$brochureURL = $this->national_course_lib;
		$examNameNew = strtoupper($examName);
		if(!isset($this->settingArray[$examNameNew]) || empty($this->settingArray[$examNameNew]))
		{
			show_404();
		}

		$displayData['courseShortArray'] = $courseShortArray;
		$displayData['brochureURL'] = $brochureURL;
		
		$examHeadingArray = $this->_getDbExamAndHeading($examName);
		$displayData['pageHeading'] =  $examHeadingArray['pageHeading'];
		$displayData['examName'] = $examHeadingArray['examName'];
		$displayData['examYear'] = $examHeadingArray['examYear'];
		$displayData['examSettingsArray'] = $this->settingArray[strtoupper($examName)];				
		$this->_setExamCookie($displayData['examName']);
		
		$displayData['tab'] = '3';
		$displayData['userStatus'] = $this->userStatus;
		$displayData['stateArr'] = $this->getStates();
		$displayData['branchArr'] = $this->getBranches();
		$displayData['boomr_pageid'] = "college_predictor_branch";
		$displayData['inputType'] = $this->settingArray[$examNameNew]['inputType'];
		$displayData['stateName'] = isset($this->settingArray[$examNameNew]['stateName']) ? $this->settingArray[$examNameNew]['stateName'] : '';
		$displayData['noStateDropDown'] = 	isset($this->settingArray[$examNameNew]['noStateDropDown']) ? $this->settingArray[$examNameNew]['noStateDropDown'] : 0;

		$seoDetails = getSeoDataForPages(3,strtoupper($examName),'',0,$this->settingArray);
		if(is_array($seoDetails)){
			$displayData['m_meta_title'] = $seoDetails['title'];
			$displayData['m_meta_description'] = $seoDetails['description'];
			$displayData['canonicalURL'] = $seoDetails['canonicalURL'];
		}

		//Also, check if Query string is set. If yes, we will have to fetch the string and show search results
		if(isset($_GET['tabType']) && $_GET['tabType']!=''){
			$this->setLinkParamsInCookie($_GET);	
		}
		
		//If the Cookie is set, fetch the results from Backend. We will display these results instead of Search form
		if( isset($_COOKIE['collegepredictor_search_'.$displayData['examName']]) && $_COOKIE['collegepredictor_search_'.$displayData['examName']]!='' ){
			$searchParameters = json_decode($_COOKIE['collegepredictor_search_'.$displayData['examName']],true);
			if($searchParameters['tabType']=='branch'){

				//below line is used for cionversion ttracking purpose
				$searchParameters['downloadtrackingPageKeyId'] =289;
				$searchParameters['shortlistTrackingPageKeyId']=300;
				$searchParameters['comparetrackingPageKeyId'] = 302;

				$this->getDefaultSearchResults($displayData, $searchParameters);
				$displayData['searchParameters'] = $searchParameters;
			}
		}
                //Code added by Ankur for GA Custom variable tracking
                $displayData['subcatNameForGATracking'] = 'B.E./B.Tech';
                $displayData['pageTypeForGATracking'] = 'COLLEGE_PREDICTOR_DETAIL_MOBILE';
		
		//below cod eused for beacon tracking purpose
        $beaconTrackData = $this->collegepredictorlibrary->prepareBeaconTrackData('collegePredictor',$examHeadingArray['examName'],'Branch');
        $inputData = array( 
		 	"examName"=> $searchParameters['examName'],
		 	"rank"=>$searchParameters['rank'],
		    "toolName"=>'CollegePredictor',
		    "branchAcronym"=>$searchParameters['branchAcronym']
		);
        $gtmParams = $this->collegepredictorlibrary->getGTMArray($beaconTrackData['pageIdentifier'], $inputData);	
		$displayData['beaconTrackData'] = $beaconTrackData;	
		$displayData['gtmParams'] = $gtmParams;  
		//conversion tracking
		$displayData['emailtrackingPageKeyId']=292;
		$displayData['pageIdentifier'] = 'collegePredictor';
		$this->collegepredictorlibrary->getCollegePredictorTrackingKeys($displayData, $examNameNew, 'branch','mobile');
		$displayData['trackingPageKeyId']=$displayData['trackingKeyId'];
		$this->load->view('homepage',$displayData);
	}


	    

	/******************
	 * Function to get the State Array from DB
	 ******************/
        function getStates(){
                $this->init();
                $this->load->model('CP/cpmodel');
                $this->cpmodel = new CPModel();
                $stateData = $this->cpmodel->getStates();
				return $stateData;
        }

	/******************
	 * Function to get the Branch Array from DB
	 ******************/
        function getBranches(){
                $this->init();
                $this->load->model('CP/cpmodel');
                $this->cpmodel = new CPModel();
                
                $examName = 'JEE-Mains';
                if(isset($_COOKIE['collegePredictor_mobile_examName']) && !empty($_COOKIE['collegePredictor_mobile_examName']) ) {
                	$examName = $_COOKIE['collegePredictor_mobile_examName'];
                }                
                
                $branchData = $this->cpmodel->getBranches($examName);
		return $branchData;
        }
	
	
	/******************
	 * Function to show the College cut-off page directly
	 * This will take either CollegeName / College Id and simply call the default function to show the result page
	 ******************/
	function loadInstitutePage($collegeName,$instituteAndExamName = '', $folderName = 'b-tech'){
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
	
	/******************
	 * Function to Set the Search parameters in Cookie if they are found in Query string
	 * This case will arise in case of Mailer URLs
	 ******************/
	function setLinkParamsInCookie($queryString){
		$this->init();

		$examName = 'JEE-Mains';
		if(isset($_COOKIE['collegePredictor_mobile_examName']) && !empty($_COOKIE['collegePredictor_mobile_examName'])) {
			$examName = $_COOKIE['collegePredictor_mobile_examName'];
		}
		
		$rank = (isset($queryString['rank']))?$queryString['rank']:0;
		$tabType = (isset($queryString['tabType']))?$queryString['tabType']:'rank';
		$category = (isset($queryString['category']))?$queryString['category']:'GEN';
		$round = (isset($queryString['round']))?$queryString['round']:'all';
		$rankType = (isset($queryString['rank_type']))?$queryString['rank_type']:'Other';
		$stateName = (isset($queryString['state']))?$queryString['state']:'';
		
		$branches = (isset($queryString['branch']))?$queryString['branch']:'';
		$branchAcronym = explode(',',$branches);
		$displayCount = (isset($queryString['displayCount']))?$queryString['displayCount']:'';
		
		$college = (isset($queryString['college']))?$queryString['college']:'';
		if( $college != '' ){
			$institutesOptions = explode(',',$college);
			$formatedData = getFormatedData($institutesOptions);
			$collegeGroupName  = $formatedData['collegeGroupName'];
			$instIdNotHavingGroupName = $formatedData['collegeId'];
			$this->load->model('CP/cpmodel');
			$instIdsHavingGroupName = $this->cpmodel->getInstituteIdForGroupName($collegeGroupName,$examName);
			$instituteIds  = array_merge($instIdNotHavingGroupName,$instIdsHavingGroupName);			
		}

		$searchParameters = array('rankType'=>$rankType,'categoryName'=>$category,'domicile'=>'NO','round'=>$round,'examName'=>$examName,'tabType'=>$tabType,'stateName'=>$stateName,'instituteId'=>$instituteIds,'institutesOptions'=>$institutesOptions,'rank'=>$rank,'branchAcronym'=>$branchAcronym);
		//_p($searchParameters); exit;
		$_COOKIE['collegepredictor_search_' .$examName] = json_encode($searchParameters);
		setcookie('collegepredictor_search_' . $examName,json_encode($searchParameters),time() + 2592000,'/',COOKIEDOMAIN);
		setcookie('collegepredictor_filterTypeValueData_mobile5_'.$examName,json_encode($_GET['filterTypeValue_' .$examName]),time() + 2592000,'/',COOKIEDOMAIN);
		if($displayCount!=''){
			setcookie('displayCount_'.$examName,json_encode(explode(',',$displayCount)),time() + 2592000,'/',COOKIEDOMAIN);	
		}else{
			setcookie('displayCount_'.$examName,'',time() + 2592000,'/',COOKIEDOMAIN);	
		}
		$count = 0;
		foreach($_GET['filterTypeValue_' .$examName] as $key=>$value){
			if($count>0){
				$fs .= '&filterTypeValue_'.$examName.'%5B%5D='.urlencode($value);
			}else{
				$fs .= 'filterTypeValue_'.$examName.'%5B%5D='.urlencode($value);
			}
			$count++;
		}
		setcookie('FILTER_STRING_'.$examName,$fs,time() + 2592000,'/',COOKIEDOMAIN);
                $url = $_SERVER['SCRIPT_URI'];
                header("Location: $url",TRUE,302);
                exit;		
	}
	
	/******************
	 * Function to Insert an entry in College predictor Log table
	 ******************/
	function makeLogEntry($parameters,$start=0,$totalResults=0,$activity = ''){
		$this->init();
		$this->load->model('CP/cpmodel');
		//Fetch the Activity
		if($start==0){
			$data['activityType'] = "Search";
		}
		else if($start>0){
			$data['activityType'] = "LoadMore";
		}
		
                if(!empty($activity)){
                        $data['activityType'] = $activity;
                }
		
		//Fetch the User Id
		if(isset($this->userStatus) && is_array($this->userStatus)){
			$signedInUser = $this->userStatus;
			$data['userId'] = $signedInUser[0]['userid'];
		}
		else{
			$data['userId'] = 0;
		}
		$parameters['start'] = $start;
		$data['source'] = "Mobile";
		$data['url'] = $_SERVER['HTTP_REFERER'];
		$data['value'] = json_encode($parameters);
		$data['resultsFound'] = $totalResults;
		$this->cpmodel->insertActivityLog($data);
	}

	/******************
	 * Function to set exam cookie
	******************/
	
	private function _setExamCookie($examName) {
		if(isset($_COOKIE['collegePredictor_mobile_examName']) && !empty($_COOKIE['collegePredictor_mobile_examName'])) {
			if($_COOKIE['collegePredictor_mobile_examName'] != $examName) {
				$_COOKIE['collegePredictor_mobile_examName'] = $examName;
				setcookie('collegePredictor_mobile_examName',$examName,0,'/',COOKIEDOMAIN);
			}
		}else {
			$_COOKIE['collegePredictor_mobile_examName'] = $examName;
			setcookie('collegePredictor_mobile_examName',$examName,0,'/',COOKIEDOMAIN);
		}
	}
	
	/******************
	 * Function to get exam name configured for db
	******************/
	private function _getDbExamAndHeading($examName) {
		
		$returnArray = array();
		
		$settingsArray = $this->settingArray;
		$configExamName = strtoupper($examName);
		$configExamArray = $settingsArray[$configExamName];
		$returnArray['pageHeading'] =  $configExamArray['tab']['rank']['heading'];
		$returnArray['examName'] = $configExamArray['examName'];		
		$returnArray['examYear'] = $configExamArray['examYear'];		

		return $returnArray;
	}
	
	function setFilters(){
		$this->init();
		$examName = $_REQUEST['examName'];
		if($examName =='JEE-MAINS'){
			$examName ='JEE-Mains';	
		}

		$this->collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
		$filters = $this->collegepredictorlibrary->getAggregateFilters($examName);

		$this->load->builder('BranchBuilder','CP');
		$branchBuilder = new BranchBuilder;
		$this->branchRepository = $branchBuilder->getBranchRepository();
		$branchObj = $this->branchRepository->findMultiple($filters);

		
		
		$displayData = $this->collegepredictorlibrary->_getFilters($filters,$branchObj);//_p($filters);
		$displayData['filters'] = $filters;
		
			$branchData   = 'srchfilterTabBranch::::'.$this->load->view('V2/branch1',$displayData,true);
			$collegeData  = 'srchfilterTabCollege::::'.$this->load->view('V2/college1',$displayData,true);
			$locationData = 'srchfilterTabLocation::::'.$this->load->view('V2/location1',$displayData,true);
		
			echo $branchData.'####'.$collegeData.'####'.$locationData;		
		
		
	}
	function getSearchText($displayData){
		$text = ''; 
		if($displayData['examName']=='jee-mains'){
			$text .=$displayData['examNameDisplay']. " Rank <b>".$displayData['inputData']['rank']."</b>, <b>AIR</b>, <b>".$displayData['inputData']['categoryName']."</b>";
		}
		return $text;
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

	function loadCollegePredictor($examName = 'JEE-Mains', $folderName = 'b-tech' , $urlType = 1) {
		// $this->init();
		ini_set("memory_limit","2048M");
		$this->load->helper('mcommon5/mobile_html5');
		$this->load->helper('listingCommon/listingcommon');
		
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$this->settingArray = $this->config->item('settings','CollegePredictorConfig');
		$this->JEE_InstituteData = $this->config->item('JEE-MAIN-InstituteData','CollegePredictorConfig');
		$this->load->config('CollegeReviewForm/collegeReviewConfig');
        $displayData['intervalsDisplayOrder'] = $this->config->item("intervalsDisplayOrder", 'collegeReviewConfig');
        $displayData['aggregateRatingDisplayOrder'] = $this->config->item('aggregateRatingDisplayOrder','collegeReviewConfig');
		
		if(!isset($this->settingArray[strtoupper($examName)]) || empty($this->settingArray[strtoupper($examName)])) {
			show_404();
		}

		$this->collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
		$displayData = $this->collegepredictorlibrary->getDataForCollegePredictor($examName, $folderName,$urlType,true);
		/*_P($displayData);*/
		$callType = isset($_REQUEST['callType'])?$this->input->post('callType'):'';
		$this->collegepredictorlibrary->getCollegePredictorTrackingKeys($displayData, $examName, 'rank','mobile');
		$displayData['nameToIdMappingForAllCollegePredictorPage'] = $this->config->item('nameToIdMappingForAllCollegePredictorPage','CollegePredictorConfig');
		$displayData['trackingPageKeyId']=$displayData['trackingKeyId'];
		$isAjaxRequest = $this->input->is_ajax_request();
		$displayData['resultInformation'] = $displayData['branchInformation'];
		if(!empty($displayData['total'])) {
			$displayData['totalResults'] = $displayData['total'];
		}
		else {
			$displayData['totalResults'] = count($displayData['objAfterAppliedFilter']);
		}
		$displayData['examSettingsArray'] = $this->settingArray[strtoupper($examName)];
		$this->_setExamCookie(strtoupper($examName));
		$displayData['boomr_pageid'] = 'college_predictor_rank';

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CollegePredictor','entity_id'=>$examName,'examName'=>$examName);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $displayData['text'] = $this->getSearchText($displayData);
        
        $this->load->config('common/examGroupConfig');
        $examGroupConfig = $this->config->item('examMapping');
        $originalEPname  = $this->settingArray[strtoupper($examName)]['examName'];
        $displayData['eResponseData'] = $examGroupConfig[$originalEPname];

		if($isAjaxRequest) {
			$zrpRedirection = $this->collegepredictorlibrary->getCPzrpRedirection($displayData,$folderName);
			if(isset($displayData['type']) && $displayData['type'] =='search' ){
				$this->collegepredictorlibrary->makeDBTrackingEntry($displayData);
			}
			if($zrpRedirection['zrpRedirection']){
				echo json_encode($zrpRedirection);
				return;
			}
			if($displayData['start']) { 
				$return['result'] = $this->load->view('V2/collegePredictorInner',$displayData, true);
			}
			else {
				$return['result'] = $this->load->view('V2/collegePredictorList1',$displayData, true);
			}
			// _p($displayData['collegePredictorFilters']); die;
			$return['filtersContainer'] = $this->load->view('V2/collegePredictorFilters1',$displayData, true);
			echo json_encode($return);
		}else {
			$displayData['breadCrumbData'] = $this->collegepredictorlibrary->getBreadCrumbDataForPredictor($displayData['eResponseData'], $originalEPname, "College Predictor");
			//Code added by Ankur for GA Custom variable tracking
			$displayData['subcatNameForGATracking'] = 'B.E./B.Tech';
			$displayData['pageTypeForGATracking'] = 'COLLEGE_PREDICTOR_DETAIL';

			$beaconTrackData = $this->collegepredictorlibrary->prepareBeaconTrackData('collegePredictor',$examHeadingArray['examName']);
			$displayData['beaconTrackData'] = $beaconTrackData;
			$displayData['contentLoaderData'] = $this->getContentLoderHtml($displayData['roundData']);
			$displayData['JEE_InstituteData'] = $this->JEE_InstituteData;
			$this->load->view('V2/collegePredictorMain',$displayData);
		}
	}
}

?>
